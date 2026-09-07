const http = require('http');
const fs = require('fs');

async function capture() {
    // 1. Get targets from CDP
    const getJSON = (url) => new Promise((resolve, reject) => {
        http.get(url, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(JSON.parse(data)));
        }).on('error', reject);
    });

    try {
        const pages = await getJSON('http://127.0.0.1:9222/json');
        console.log('Available pages:', pages.length);
        const wsUrl = pages[0]?.webSocketDebuggerUrl;
        if (!wsUrl) {
            console.error('No page target found');
            return;
        }

        const WebSocket = require('ws');
        const ws = new WebSocket(wsUrl);

        let msgId = 1;
        const send = (method, params = {}) => new Promise((resolve) => {
            const id = msgId++;
            const handler = (data) => {
                const msg = JSON.parse(data);
                if (msg.id === id) {
                    ws.off('message', handler);
                    resolve(msg.result);
                }
            };
            ws.on('message', handler);
            ws.send(JSON.stringify({ id, method, params }));
        });

        ws.on('open', async () => {
            console.log('Connected to CDP');
            await send('Page.enable');
            await send('Emulation.setDeviceMetricsOverride', {
                width: 1280,
                height: 900,
                deviceScaleFactor: 1,
                mobile: false
            });

            console.log('Navigating to /kader/laporan/generate?periode=2026-09');
            await send('Page.navigate', { url: 'http://127.0.0.1:8000/kader/laporan/generate?periode=2026-09' });

            setTimeout(async () => {
                const screenshot = await send('Page.captureScreenshot', { format: 'png' });
                fs.writeFileSync('C:/Users/Naufal/.gemini/antigravity-ide/brain/9a4134cc-0f2e-43fd-ad2c-d02b281fb6ec/screenshot_kader_laporan_pdf_aligned.png', Buffer.from(screenshot.data, 'base64'));
                console.log('Screenshot saved to screenshot_kader_laporan_pdf_aligned.png');
                ws.close();
                process.exit(0);
            }, 3000);
        });

    } catch (e) {
        console.error('CDP Error:', e.message);
        process.exit(1);
    }
}

capture();
