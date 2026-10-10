const {chromium}=require('playwright'),fs=require('fs'),assert=require('assert/strict');
(async()=>{
 const base='http://127.0.0.1:9401',records=[];
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
 async function context(options={}){const c=await browser.newContext({viewport:{width:390,height:844},reducedMotion:'reduce',...options});await c.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));await c.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({body:fs.readFileSync(fonts[r.request().url()]),contentType:'font/woff2'}):r.abort());return c;}
 function check(value,label){assert(value,label);records.push(label);}
 const c=await context(),page=await c.newPage();
 await page.goto(base+'/faq/',{waitUntil:'networkidle'});
 const items=page.locator('.gp-faq-list>details');check(await items.count()===13,'Central FAQ has thirteen questions');
 for(const index of [0,5,6,7,12]){
  const item=items.nth(index),summary=item.locator('summary');await summary.focus();await summary.press('Enter');check(await item.getAttribute('open')!==null,'Enter opens FAQ '+index);await summary.press('Enter');check(await item.getAttribute('open')===null,'Enter closes FAQ '+index);await summary.press('Space');check(await item.getAttribute('open')!==null,'Space opens FAQ '+index);await summary.press('Space');check(await item.getAttribute('open')===null,'Space closes FAQ '+index);
 }
 await items.first().locator('summary').focus();await page.keyboard.press('Tab');check(await items.nth(1).locator('summary').evaluate(e=>e===document.activeElement),'Tab advances between FAQ summaries');
 await page.goto(base+'/insights/book-discovery/');await page.locator('[data-gpc-article-links] a').filter({hasText:'Amazon Book Visibility service'}).click();check(new URL(page.url()).pathname==='/services/amazon-visibility/','Insight leads to relevant service');
 await page.locator('[data-gpc-journey] .gp-work-card a').click();check(new URL(page.url()).pathname==='/case-studies/my-dear-grandfather/','Service proof leads to case study');
 await page.locator('main .gp-cta a').first().click();check(new URL(page.url()).pathname==='/book-marketing-audit/','Case study leads to existing assessment URL');check(await page.locator('[name=request]').inputValue()==='assessment','Assessment CTA selects the assessment request');
 await page.goto(base+'/services/book-formatting/');await page.locator('[data-gpc-journey] .gp-work-card a').click();check(new URL(page.url()).pathname==='/case-studies/luma-the-sleepy-star/','Formatting proof leads to Luma');
 check((await page.locator('main').innerText()).includes('It did not include KDP upload, publication, metadata optimisation or Kindle conversion.'),'Luma page states actual delivery limits');
 for(const route of ['/case-studies/','/case-studies/my-dear-grandfather/','/case-studies/luma-the-sleepy-star/','/faq/']){await page.goto(base+route);check(await page.locator('footer a').filter({hasText:/^Case Studies$/}).count()===1,route+' has one work hub footer link');check(await page.locator('footer a').filter({hasText:/^FAQs$/}).count()===1,route+' has one FAQ footer link');check(await page.locator('h1').count()===1,route+' has one H1');}
 await c.close();
 const nojs=await context({javaScriptEnabled:false}),staticPage=await nojs.newPage();await staticPage.goto(base+'/faq/');const first=staticPage.locator('.gp-faq-list>details').first();await first.locator('summary').click();check(await first.getAttribute('open')!==null,'FAQ works with JavaScript disabled');await staticPage.goto(base+'/case-studies/');check(await staticPage.locator('.gp-work-card').count()===2,'Work cards remain available without JavaScript');await staticPage.locator('.gp-work-card a').first().click();check(new URL(staticPage.url()).pathname==='/case-studies/my-dear-grandfather/','Case-study navigation works without JavaScript');
 await nojs.close();await browser.close();fs.writeFileSync('/workspace/grant-qa/authority-after/journey-flows.json',JSON.stringify({checks:records.length,passed:records},null,2));console.log('PASS:',records.length,'FAQ, keyboard, no-JavaScript and complete conversion-path checks.');
})().catch(e=>{console.error(e);process.exit(1)});
