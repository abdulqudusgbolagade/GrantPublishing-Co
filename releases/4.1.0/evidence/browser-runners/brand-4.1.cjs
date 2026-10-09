const { chromium } = require('playwright');
const assert = require('node:assert/strict'), fs = require('node:fs');
let checks=0; const records=[];
function check(value,label) { assert.ok(value,label); checks++; }
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 const page=await browser.newPage();
 const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
 await page.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));
 await page.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({contentType:'font/ttf',body:fs.readFileSync(fonts[r.request().url()])}):r.abort());
 for(const mode of ['fallback','native']) for(const width of [320,390,768,1024,1120,1121,1440,2560]){
  await page.setViewportSize({width,height:900});await page.goto(`http://127.0.0.1:8767/after/home-${mode}.html`);await page.evaluate(()=>document.fonts.ready);
  await page.locator('img').evaluateAll(xs=>xs.forEach(x=>x.loading='eager'));await page.waitForFunction(()=>Array.from(document.images).every(x=>x.complete&&x.naturalWidth));
  const record=await page.evaluate(()=>{
   const header=document.querySelector('.gp-wordmark'),hi=header.querySelector('img'),footer=document.querySelector('.gp-footer-brand'),fi=footer.querySelector('img'),h1=document.querySelector('h1'),primary=document.querySelector('.gp-actions .elementor-button');
   const hs=getComputedStyle(header),fs=getComputedStyle(footer),h=hi.getBoundingClientRect(),f=fi.getBoundingClientRect();
   return {title:h1.innerText.replace(/\s+/g,' ').trim(),font:getComputedStyle(h1).fontFamily,weight:getComputedStyle(h1).fontWeight,loaded:document.fonts.check('500 32px "Cormorant Garamond"'),body:getComputedStyle(document.querySelector('.gpc-site')).fontFamily,headerLabel:header.getAttribute('aria-label'),headerSrc:hi.currentSrc,headerWidth:h.width,headerHeight:h.height,headerSpace:parseFloat(hs.paddingLeft),footerSrc:fi.src,footerWidth:f.width,footerRatio:f.width/f.height,naturalRatio:fi.naturalWidth/fi.naturalHeight,footerSpace:parseFloat(fs.paddingLeft),button:getComputedStyle(primary).backgroundColor,ink:getComputedStyle(h1).color,overflow:document.documentElement.scrollWidth>innerWidth};
  });records.push({mode,width,...record});
  check(record.title==='Books built to be discovered.',`${mode}/${width} approved headline`);
  check(record.font.includes('Cormorant Garamond')&&record.loaded&&record.weight==='500',`${mode}/${width} actual loaded display family and medium weight`);
  check(record.body.includes('Manrope'),`${mode}/${width} working type family`);
  check(record.headerLabel==='Grant Publishing Co. home',`${mode}/${width} icon-only link has company name`);
  check(record.headerSrc.includes('grant-icon-')&&record.headerWidth>=(width<=720?36:40)&&record.headerWidth<=48,`${mode}/${width} original icon rendition has approved size`);
  check(Math.abs(record.headerWidth-record.headerHeight)<.01,`${mode}/${width} square icon is not stretched`);
  check(record.headerSpace>=record.headerWidth*.25,`${mode}/${width} icon clear space`);
  check(record.footerSrc.includes('grant-primary-reverse.png')&&record.footerWidth>=180,`${mode}/${width} supplied full reverse logo meets minimum size`);
  check(Math.abs(record.footerRatio-record.naturalRatio)<.005&&record.footerSpace>=record.footerWidth*.25,`${mode}/${width} full logo aspect ratio and clear space`);
  check(record.button==='rgb(32, 27, 127)'&&record.ink==='rgb(9, 7, 43)',`${mode}/${width} approved primary action and headline colors`);
  check(!record.overflow,`${mode}/${width} brand placements remain within viewport`);
 }
 for(const mode of ['fallback','native']){
  await page.goto(`http://127.0.0.1:8767/after/client-feedback-${mode}.html`.replace('client-feedback-','feedback-'));
  for(const [name,title,href] of [['luma-the-sleepy-star.png','Luma the Sleepy Star by John Capon','https://www.linkedin.com/services/page/a29863343146852122/'],['my-dear-grandfather.jpg','My Dear Grandfather by Barsha Rai','https://www.amazon.com/dp/1947646117'],['kathryns-beach.jpg','Kathryn’s Beach by Nadine Laman','https://www.amazon.com/dp/1947646168']]){
   const img=page.locator(`main img[src$="${name}"]`);check(await img.count()===1&&await img.getAttribute('alt')===title,`${mode} genuine ${name} and author alternative`);
   check(await img.locator('..').getAttribute('href')===href,`${mode} ${name} exact confirmed destination`);
  }
 }
 await browser.close();fs.writeFileSync('/workspace/grant-qa/brand-results-4.1.json',JSON.stringify({checks,records},null,2)+'\n');console.log(`PASS: ${checks} rendered typography, identity, minimum size, clear-space, responsive framing, accessible brand naming and supplied-cover assertions.`);
})().catch(e=>{console.error(e);process.exit(1)});
