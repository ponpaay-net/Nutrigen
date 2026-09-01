from playwright.sync_api import sync_playwright
import sys
with sync_playwright() as p:
    b = p.chromium.launch()
    for label, vp in [('desktop', {'width':1366,'height':900}), ('mobile', {'width':390,'height':844})]:
        page = b.new_page(viewport=vp)
        try:
            page.goto('http://127.0.0.1:8000/login', wait_until='networkidle')
            page.fill('input[name=email]', 'kader1@gmail.com')
            page.fill('input[name=password]', 'password')
            page.click('button[type=submit]')
            page.wait_for_timeout(2000)
            page.goto('http://127.0.0.1:8000/kader/balita/1/ukur', wait_until='networkidle')
            page.wait_for_timeout(900)
            page.screenshot(path=f'C:/19jlp/nutrigen_new/_ukur_{label}.png', full_page=True)
            print(f'{label} shot OK ->', page.url)
        except Exception as e:
            print(label, 'ERR', e)
        page.close()
    b.close()
