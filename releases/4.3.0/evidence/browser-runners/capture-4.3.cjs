const {chromium}=require('playwright'),fs=require('node:fs');
const defs=require('/workspace/GrantPublishing-Co/grant-publishing-site/pages.json');
(async()=>{
 const b=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const p=await b.newPage({reducedMotion:'reduce'});
 const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
 await p.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));
 await p.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({contentType:'font/ttf',body:fs.readFileSync(fonts[r.request().url()])}):r.abort());
 for(const variant of ['after','before'])for(const key of variant==='after'?Object.keys(defs):['home','about','services','contact'])for(const width of [1440,390]){
  await p.setViewportSize({width,height:900});await p.goto(`http://127.0.0.1:8767/${variant}/${key}-fallback.html`);await p.evaluate(()=>document.fonts.ready);
  await p.locator('img').evaluateAll(async xs=>{xs.forEach(x=>x.loading='eager');await Promise.all(xs.filter(x=>x.hasAttribute('src')).map(x=>x.decode()));});
  await p.locator('.gp-footer-brand img').scrollIntoViewIfNeeded();await p.evaluate(()=>scrollTo(0,0));
  await p.screenshot({path:`/workspace/grant-qa/4.3-screens/${variant}-${key}-${width}.png`,fullPage:true});
 }
 await p.setViewportSize({width:1440,height:900});await p.goto('http://127.0.0.1:8767/after/home-fallback.html');await p.evaluate(()=>document.fonts.ready);await p.locator('img').evaluateAll(async xs=>{xs.forEach(x=>x.loading='eager');await Promise.all(xs.filter(x=>x.hasAttribute('src')).map(x=>x.decode()));});
 await p.locator('.gp-footer').screenshot({path:'/workspace/grant-qa/4.3-screens/after-home-footer-1440.png'});
 await b.close();console.log('PASS: 48 final before/after full-page captures plus footer detail; all requested images decoded before capture.');
})().catch(e=>{console.error(e);process.exit(1)});
