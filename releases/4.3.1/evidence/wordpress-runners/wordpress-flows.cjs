const {chromium}=require('playwright'),fs=require('fs'),assert=require('assert/strict');
const base='http://127.0.0.1:9401';const records=[];
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 const page=await browser.newPage({viewport:{width:390,height:844},reducedMotion:'reduce'});
 const fonts=JSON.parse(fs.readFileSync('/workspace/grant-qa/font-cache-4.1/mapping.json'));
 await page.route('https://fonts.googleapis.com/**',r=>r.fulfill({contentType:'text/css',body:fs.readFileSync('/workspace/grant-qa/font-cache-4.1/grant-fonts.css')}));
 await page.route('https://fonts.gstatic.com/**',r=>fonts[r.request().url()]?r.fulfill({body:fs.readFileSync(fonts[r.request().url()]),contentType:'font/woff2'}):r.abort());
 async function check(condition,label){assert(condition,label);records.push(label);}
 for(const route of ['/contact/','/book-marketing-audit/']){
  await page.goto(base+route);await page.request.get(base+'/qa-reset.php');
  const form=page.locator('.gpc-enquiry-form');await check(await form.count()===1,route+' one form');
  await check(await form.locator('[name=book_url]').getAttribute('required')===null,route+' Amazon URL stays optional');
  let posts=0;page.on('request',req=>{if(req.url().endsWith('admin-post.php'))posts++});
  await form.locator('[type=submit]').click();await check(posts===0,route+' empty submission blocked');
  await form.locator('[name=name]').fill('Grant QA');await form.locator('[name=email]').fill('qa@example.test');await form.locator('[name=message]').fill('Local isolated WordPress QA '+route+Date.now());
  await form.locator('[type=submit]').click();await check(posts===0,route+' consent required');await form.locator('[name=consent]').check();
  await page.setExtraHTTPHeaders({'X-Grant-QA-Response':'rejected'});await form.locator('[type=submit]').click();await page.waitForFunction(()=>document.querySelector('.gpc-form-status').dataset.state==='error');
  await check((await form.locator('[name=message]').inputValue()).startsWith('Local isolated'),route+' rejected submission preserves fields');
  await check(await form.locator('[type=submit]').isEnabled(),route+' rejection re-enables button');
  await page.setExtraHTTPHeaders({'X-Grant-QA-Response':'success'});await form.locator('[type=submit]').click();await page.waitForFunction(()=>document.querySelector('.gpc-form-status').dataset.state==='success');
  await check((await form.locator('.gpc-form-status').innerText()).includes('Web3Forms has accepted'),route+' actual PHP provider acceptance message');
  await check(await form.locator('[name=message]').inputValue()==='',route+' confirmed success resets fields');
  await form.locator('[name=name]').fill('Grant QA');await form.locator('[name=email]').fill('qa@example.test');await form.locator('[name=message]').fill('Local timeout '+route+Date.now());await form.locator('[name=consent]').check();
  await page.setExtraHTTPHeaders({'X-Grant-QA-Response':'timeout'});await form.locator('[type=submit]').click();await page.waitForFunction(()=>document.querySelector('.gpc-form-status').dataset.state==='error');
  await check((await form.locator('.gpc-form-status').innerText()).includes('could not confirm'),route+' uncertain delivery reports uncertainty');
  await check((await form.locator('[name=message]').inputValue()).startsWith('Local timeout'),route+' uncertain delivery preserves message');
  await page.setExtraHTTPHeaders({'X-Grant-QA-Response':'success'});await form.locator('[type=submit]').click();await page.waitForFunction(()=>document.querySelector('.gpc-form-status').dataset.state==='error');
  await check((await form.locator('.gpc-form-status').innerText()).includes('awaiting confirmation'),route+' duplicate uncertain request is held');
 }
 await page.setExtraHTTPHeaders({});await page.goto(base+'/services/book-formatting/');
 await page.locator('.gp-page-hero .gp-action a').first().click();
 await check(new URL(page.url()).pathname==='/contact/','Service CTA opens contact');await check(await page.locator('[name=service]').inputValue()==='Book formatting','Service choice is prefilled');
 const menu=page.locator('.gp-mobile-nav');await menu.locator('summary').click();await check(await menu.getAttribute('open')!==null,'Mobile menu opens');await menu.locator('a').first().focus();await page.keyboard.press('Escape');
 await check(await menu.getAttribute('open')===null,'Escape closes menu');await check(await menu.locator('summary').evaluate(e=>e===document.activeElement),'Escape restores focus');
 await menu.locator('summary').click();await menu.locator('a').filter({hasText:/^Services$/}).click();await check(new URL(page.url()).pathname==='/services/','Mobile link navigates');
 await check(await page.locator('.gp-mobile-nav').getAttribute('open')===null,'Menu is closed after navigation');
 await page.goto(base+'/');const showcase=page.locator('[data-book-showcase]');await check(await showcase.count()===1,'Actual native hero renders one shared showcase');
 await check(await showcase.locator('[data-toggle]').isDisabled(),'Reduced motion disables automatic rotation');
 await showcase.locator('[data-next]').click();await page.waitForFunction(()=>document.querySelectorAll('[data-slide]')[1].classList.contains('is-active'));
 await check((await showcase.locator('.is-active').innerText()).includes('My Dear Grandfather'),'Next cover and caption update');
 await showcase.locator('[data-next]').press('End');await page.waitForFunction(()=>document.querySelectorAll('[data-slide]')[2].classList.contains('is-active'));
 await check((await showcase.locator('.is-active').innerText()).includes('Kathryn'),'Keyboard End selects final project');
 await page.goto(base+'/client-feedback/');for(const id of ['luma-case-study','grandfather-case-study'])await check(await page.locator('#'+id+' a[target="_blank"]').count()>0,id+' protected new-tab PDF action exists');
 await page.goto(base+'/privacy-policy/');await check(await page.locator('footer .gp-footer-legal a').count()===2,'Both legal pages appear together in footer');
 fs.writeFileSync('/workspace/grant-qa/production-after/flows.json',JSON.stringify({checks:records.length,passed:records,provider:'local stub; no real messages sent'},null,2));await browser.close();console.log('PASS:',records.length,'real WordPress form/navigation/showcase checks with intercepted provider; no real messages sent.');
})().catch(e=>{console.error(e);process.exit(1)});
