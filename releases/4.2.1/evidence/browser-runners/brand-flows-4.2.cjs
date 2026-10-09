const {chromium}=require('playwright'),assert=require('node:assert/strict'),fs=require('node:fs');
const defs=require('/workspace/GrantPublishing-Co/grant-publishing-site/pages.json');
let checks=0;const records=[];function check(v,label){assert.ok(v,label);checks++;}
(async()=>{const b=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const ctx=await b.newContext();const p=await ctx.newPage();await ctx.route('https://fonts.googleapis.com/**',r=>r.abort());await ctx.route('https://fonts.gstatic.com/**',r=>r.abort());
const pdf='https://grantpublishingco.com/wp-content/uploads/2026/10/My-Dear-Grandfather-Listing-Case-Study.pdf';await ctx.route(pdf,r=>r.fulfill({contentType:'text/html',body:'<title>Intercepted PDF destination</title>'}));
for(const mode of ['fallback','native'])for(const width of [320,390,768,1440])for(const key of Object.keys(defs)){
 await p.setViewportSize({width,height:900});await p.goto(`http://127.0.0.1:8767/after/${key}-${mode}.html`);
 const state=await p.evaluate(()=>{const main=document.querySelector('#gpc-main'),root=main.querySelector(':scope > .elementor')||main;const sections=Array.from(root.children).filter(e=>e.matches('.gp-section,.gp-proof,.gp-page-hero,.gp-home-hero'));const bg=sections.map(e=>getComputedStyle(e).backgroundColor);const links=Array.from(document.querySelectorAll('.gp-social-link')).map(e=>{const r=e.getBoundingClientRect();return {href:e.getAttribute('href'),name:e.getAttribute('aria-label'),w:r.width,h:r.height,icon:e.querySelectorAll('svg[aria-hidden="true"]').length,text:e.textContent.trim(),outline:getComputedStyle(e).outlineStyle};});const cols=Array.from(document.querySelector('.gp-footer-grid').children).map(e=>({x:e.getBoundingClientRect().x,y:e.getBoundingClientRect().y}));return {bg,links,cols,nav:getComputedStyle(document.querySelector('.gp-header')).backgroundColor,footerPanel:getComputedStyle(document.querySelector('.gp-footer-brand')).backgroundColor};});
 check(state.nav==='rgb(9, 7, 43)',`${key}/${mode}/${width} requested ink navigation`);
 check(state.bg.length>=2&&state.bg.every((x,i)=>i<2||x!==state.bg[i-1]||x!==state.bg[i-2]),`${key}/${mode}/${width} no three consecutive same backgrounds`);
 check(state.links.length>=4&&state.links.every(x=>x.name&&x.icon===1&&!x.text&&x.w>=44&&x.h>=44),`${key}/${mode}/${width} labelled icon links and touch targets everywhere`);
 check(state.footerPanel==='rgb(247, 245, 242)',`${key}/${mode}/${width} footer wordmark is on legible porcelain`);
 if(width===1440)check(state.cols.every(x=>Math.abs(x.y-state.cols[0].y)<1),`${key}/${mode} aligned desktop footer columns`);
 records.push({key,mode,width,...state});
}
for(const mode of ['fallback','native']){
 await p.setViewportSize({width:390,height:844});await p.goto(`http://127.0.0.1:8767/after/feedback-${mode}.html`);const link=p.locator('.gp-case-study a');check(await link.count()===1&&await link.getAttribute('href')===pdf,`${mode} one requested case-study PDF link`);
 await link.focus();check(await link.evaluate(e=>e===document.activeElement&&getComputedStyle(e).outlineStyle==='solid'),`${mode} visible keyboard focus on PDF action`);const wait=ctx.waitForEvent('page');await p.keyboard.press('Enter');const pop=await wait;await pop.waitForLoadState();check(pop.url()===pdf,`${mode} keyboard opens exact PDF destination`);await pop.close();
 for(const icon of await p.locator('.gp-footer-socials a').all()){await icon.focus();check(await icon.evaluate(e=>e===document.activeElement&&getComputedStyle(e).outlineStyle==='solid'),`${mode} footer social keyboard focus`);}
}
await b.close();fs.writeFileSync('/workspace/grant-qa/brand-flows-results-4.2.json',JSON.stringify({checks,records},null,2)+'\n');console.log(`PASS: ${checks} section color, navigation, footer alignment/panel, accessible icon/touch target and keyboard PDF checks. External destination intercepted; no message sent.`);
})().catch(e=>{console.error(e);process.exit(1)});
