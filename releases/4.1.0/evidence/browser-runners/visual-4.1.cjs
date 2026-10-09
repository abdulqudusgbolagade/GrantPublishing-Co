const {chromium}=require('playwright'),fs=require('node:fs'),path=require('node:path'),assert=require('node:assert/strict');
const defs=require('/workspace/GrantPublishing-Co/grant-publishing-site/pages.json');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const page=await browser.newPage();
 const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
 await page.route('https://fonts.googleapis.com/**',route=>route.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));
 await page.route('https://fonts.gstatic.com/**',route=>{const file=fonts[route.request().url()];return file?route.fulfill({contentType:'font/woff2',body:fs.readFileSync(file)}):route.abort()});
 const keys=process.env.ONLY_PAGES?process.env.ONLY_PAGES.split(','):Object.keys(defs);
 const outcomes=[],screens='/workspace/grant-qa/4.1-screens';fs.mkdirSync(screens,{recursive:true});
 for(const mode of ['fallback','native'])for(const viewport of [{width:3840,height:2160},{width:2560,height:1440},{width:1440,height:900},{width:1024,height:900},{width:768,height:900},{width:390,height:844},{width:320,height:900},{width:844,height:390}])for(const key of keys){
  await page.setViewportSize(viewport);await page.goto(`http://127.0.0.1:8767/after/${key}-${mode}.html`);
  await page.evaluate(()=>document.fonts.ready);await page.locator('img').evaluateAll(imgs=>imgs.forEach(img=>img.loading='eager'));await page.waitForFunction(()=>Array.from(document.images).every(i=>i.complete&&i.naturalWidth>0));
  await page.addScriptTag({path:'/workspace/grant-qa/node_modules/axe-core/axe.min.js'});
  const state=await page.evaluate(async()=>{const audit=await axe.run(document.querySelector('.gpc-site'),{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21aa']}});return {fonts:document.fonts.check('16px Manrope')&&document.fonts.check('500 32px "Cormorant Garamond"'),overflow:document.documentElement.scrollWidth>innerWidth,violations:audit.violations.map(v=>({id:v.id,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))}))}});
  outcomes.push({key,mode,...viewport,...state});
  if(mode==='fallback'&&[1440,390].includes(viewport.width))await page.screenshot({path:path.join(screens,`after-${key}-${viewport.width}.png`),fullPage:true});
 }
 fs.writeFileSync(process.env.ONLY_PAGES?'/workspace/grant-qa/visual-targeted-4.1.json':'/workspace/grant-qa/visual-results-4.1.json',JSON.stringify(outcomes,null,2));
 const bad=outcomes.filter(r=>r.overflow||r.violations.length||!r.fonts);console.log('Audited',outcomes.length,'combinations with mirrored official Grant fonts. Failures:',bad.length);if(bad.length)console.log(JSON.stringify(bad,null,2));
 for(const variant of ['before','after'])for(const key of (process.env.ONLY_PAGES?['contact']:['home','about','services','contact']))for(const width of [1440,390]){
  await page.setViewportSize({width,height:900});await page.goto(`http://127.0.0.1:8767/${variant}/${key}-fallback.html`);await page.evaluate(()=>document.fonts.ready);await page.locator('img').evaluateAll(imgs=>imgs.forEach(img=>img.loading='eager'));await page.waitForFunction(()=>Array.from(document.images).every(i=>i.complete&&i.naturalWidth>0));await page.screenshot({path:path.join(screens,`${variant}-${key}-${width}.png`),fullPage:true});
 }
 await browser.close();assert.equal(bad.length,0,'All required visual/accessibility checks must pass');console.log('PASS: responsive/accessibility checks; all supplied images and fonts loaded; comparison screenshots saved.');
})().catch(e=>{console.error(e);process.exit(1)});
