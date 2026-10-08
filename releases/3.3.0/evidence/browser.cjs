const {chromium}=require('playwright'),fs=require('fs'),assert=require('node:assert/strict');
const defs=require('/workspace/grant-publishing-codex-handoff/deliverables/grant-publishing-site/pages.json');
(async()=>{
const browser=await chromium.launch({executablePath:'/usr/bin/chromium',headless:true,args:['--no-sandbox']});const page=await browser.newPage();let results=[];
await page.route('https://fonts.googleapis.com/**',r=>r.abort());await page.route('https://fonts.gstatic.com/**',r=>r.abort());
if(!process.env.INTERACTIONS_ONLY){
for(const mode of ['fallback','native'])for(const width of [1440,390,320])for(const k of Object.keys(defs)){
 await page.setViewportSize({width,height:900});await page.goto(`http://127.0.0.1:8766/after/${k}-${mode}.html`);await page.addScriptTag({path:'/workspace/grant-qa/node_modules/axe-core/axe.min.js'});
 const audit=await page.evaluate(async()=>{const result=await axe.run(document.querySelector('.gpc-site'),{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21aa']}});return {overflow:document.documentElement.scrollWidth>innerWidth,violations:result.violations.map(v=>({id:v.id,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))}))};});
 results.push({k,mode,width,...audit});
}
fs.writeFileSync('/workspace/grant-qa/browser-results.json',JSON.stringify(results,null,2));console.log('Audited',results.length,'page/layout/viewport combinations');console.log(JSON.stringify(results.filter(r=>r.overflow||r.violations.length),null,2));
}
await page.setViewportSize({width:390,height:844});await page.goto('http://127.0.0.1:8766/after/home-fallback.html');
await page.locator('.gp-mobile-nav summary').click();assert.equal(await page.locator('.gp-mobile-nav').evaluate(e=>e.open),true);await page.keyboard.press('Tab');await page.keyboard.press('Escape');assert.equal(await page.locator('.gp-mobile-nav').evaluate(e=>e.open),false);assert.equal(await page.locator('.gp-mobile-nav summary').evaluate(e=>e===document.activeElement),true);
await page.locator('.gp-mobile-nav summary').click();await page.mouse.click(385,800);assert.equal(await page.locator('.gp-mobile-nav').evaluate(e=>e.open),false);
await page.locator('.gp-mobile-nav summary').click();await page.setViewportSize({width:1440,height:900});await page.waitForFunction(()=>!document.querySelector('.gp-mobile-nav').open);assert.equal(await page.locator('.gp-mobile-nav').evaluate(e=>e.open),false);
for(const variant of ['before','after'])for(const width of [1440,390]){
 await page.setViewportSize({width,height:900});await page.goto(`http://127.0.0.1:8766/${variant}/home-fallback.html`);await page.screenshot({path:`/workspace/grant-qa/${variant}-home-${width}.png`,fullPage:true});
}
console.log('PASS: mobile opening, Escape/focus return, outside click, desktop resize; 4 before/after screenshots.');
await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
