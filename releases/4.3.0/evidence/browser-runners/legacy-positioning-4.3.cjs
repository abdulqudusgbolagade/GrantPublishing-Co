const {chromium}=require('playwright'),assert=require('node:assert/strict'),fs=require('node:fs');
let checks=0;function check(v,label){assert.ok(v,label);checks++;}
(async()=>{const b=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const p=await b.newPage({reducedMotion:'reduce'});const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
await p.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));await p.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({contentType:'font/ttf',body:fs.readFileSync(fonts[r.request().url()])}):r.abort());
for(const width of [320,390,768,1440])for(const key of ['home','services','about','contact','publishing-support','feedback']){
 await p.setViewportSize({width,height:900});await p.goto(`http://127.0.0.1:8767/after/${key}-legacy.html`);await p.evaluate(()=>document.fonts.ready);await p.locator('img[src]').evaluateAll(xs=>xs.forEach(x=>x.loading='eager'));await p.waitForFunction(()=>Array.from(document.images).filter(x=>x.hasAttribute('src')).every(x=>x.complete&&x.naturalWidth>0));
 await p.addScriptTag({path:'/workspace/grant-qa/node_modules/axe-core/axe.min.js'});const state=await p.evaluate(async()=>({overflow:document.documentElement.scrollWidth>innerWidth,axe:(await axe.run(document.querySelector('.gpc-site'),{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21aa']}})).violations}));
 check(!state.overflow&&state.axe.length===0,`${key}/${width} actual compatibility keeps legacy layout accessible and within viewport`);
 if(['home','services'].includes(key)){
  check(await p.locator('[data-book-showcase]').count()===1&&await p.locator('img[src*="publishing-studio"]').count()===0,`${key}/${width} repeated studio image replaced in saved native layout`);
 }
 if(key==='home'){
  check((await p.locator('h1').textContent()).includes('Bring your book to life.'),`${width} stock native hero broadens`);
  check(await p.locator('.gp-service-row h3').allTextContents().then(xs=>xs.join('|').includes('Book Design & Formatting|Publishing & Launch Support|Book Marketing & Amazon Ads')),`${width} stock native service entry points update`);
  check(await p.locator('.gp-portrait img').count()===1,`${width} founder photograph remains`);
 }
 if(key==='services'){
  check(await p.locator('.gp-service-list-full .gp-service-row').count()===10,`${width} all ten native services stay accessible`);
  check(await p.locator('.gp-service-list-full .gp-index').allTextContents().then(xs=>xs.map(x=>x.trim()).join(',')==='01,02,03,04,05,06,07,08,09,10'),`${width} inserted service entries keep ordered indices`);
  check(await p.locator('.gp-row-title a[href="/services/publishing-support/"]').count()===1,`${width} publishing heading remains linked`);
 }
 if(key==='feedback'){
  check(await p.locator('#john-capon-review').evaluate(e=>e.nextElementSibling.id==='luma-case-study'),`${width} Luma feature inserted immediately after saved review`);
  check(await p.locator('#grandfather-case-study').count()===1&&await p.locator('blockquote').count()===5,`${width} previous case and five reviews remain`);
 }
 if(key==='publishing-support')check((await p.locator('h1').textContent()).includes('Amazon KDP Setup')&&(await p.locator('.gp-deliverables').textContent()).includes('Upload of agreed ebook or print files'),`${width} existing publishing detail route gains practical KDP scope`);
 if(key==='contact')check((await p.locator('.gp-form-intro').textContent()).includes('leave the Amazon link blank'),`${width} saved general contact welcomes unpublished books`);
}
await b.close();const result=`PASS: ${checks} actual-PHP legacy positioning, showcase replacement, service preservation/order/links, Luma placement, optional enquiry and accessibility/overflow assertions across 24 fixtures.\n`;fs.writeFileSync('/workspace/grant-qa/legacy-positioning-results-4.3.txt',result);console.log(result);
})().catch(e=>{console.error(e);process.exit(1)});
