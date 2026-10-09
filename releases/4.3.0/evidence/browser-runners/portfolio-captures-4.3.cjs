const {chromium}=require('playwright'),fs=require('node:fs');
(async()=>{const b=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const p=await b.newPage({reducedMotion:'reduce'});const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));await p.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));await p.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({contentType:'font/ttf',body:fs.readFileSync(fonts[r.request().url()])}):r.abort());
for(const [key,width] of [['home',1440],['services',390]]){
 await p.setViewportSize({width,height:900});await p.goto(`http://127.0.0.1:8767/after/${key}-fallback.html`);await p.evaluate(()=>document.fonts.ready);await p.addStyleTag({content:'.gp-header,.gpc-skip-link{visibility:hidden!important}'});
 for(const [i,name] of ['luma','grandfather','kathryn'].entries()){
  const root=p.locator('[data-book-showcase]');await root.locator(`[data-indicator="${i}"]`).click();await p.waitForFunction(i=>document.querySelector(`[data-indicator="${i}"]`).getAttribute('aria-pressed')==='true',i);await root.locator('.is-active img').evaluate(x=>x.decode());
  await root.screenshot({path:`/workspace/grant-qa/4.3-screens/showcase-${key}-${name}-${width}.png`});
 }
}
await b.close();console.log('PASS: six final loaded slide captures, desktop Home and mobile Services.');})().catch(e=>{console.error(e);process.exit(1)});
