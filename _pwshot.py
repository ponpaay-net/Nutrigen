from playwright.sync_api import sync_playwright
with sync_playwright() as p:
    b = p.chromium.launch(channel='chrome')
    pg = b.new_page(viewport={'width':1366,'height':900})
    pg.goto('http://127.0.0.1:8000/login', wait_until='domcontentloaded')
    pg.fill('input[name=email]','kader1@gmail.com'); pg.fill('input[name=password]','password'); pg.click('button[type=submit]'); pg.wait_for_url('**/kader/dashboard', timeout=10000); pg.wait_for_timeout(1200)
    for path, name in [('/kader/jadwal','jadwal'), ('/kader/dashboard','dash'), ('/kader/balita/1','profil'), ('/kader/balita','daftar')]:
        pg.goto('http://127.0.0.1:8000'+path, wait_until='domcontentloaded'); pg.wait_for_timeout(2000)
        n = pg.evaluate("()=>[...document.querySelectorAll('i[class*=ph-]')].filter(e=>getComputedStyle(e).width==='0px').length")
        print(name, 'empty_icon:', n)
        pg.screenshot(path=f'C:/19jlp/nutrigen_new/_G_{name}.png')
    b.close()
