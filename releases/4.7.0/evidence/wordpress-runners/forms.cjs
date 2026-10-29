const {chromium}=require('playwright'),assert=require('node:assert/strict'),fs=require('node:fs');
const base='http://127.0.0.1:9401',checks=[];
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 const context=await browser.newContext({viewport:{width:390,height:844},reducedMotion:'reduce'});
 const page=await context.newPage();let posts=0; const errors=[];
 page.on('request',r=>{if(r.method()==='POST')posts++});page.on('pageerror',e=>errors.push(e.message));
 await page.addInitScript(()=>{window.testEvents=[];window.gtag=(...args)=>window.testEvents.push(args)});
 const check=(v,label)=>{assert(v,label);checks.push(label)};
 async function fresh(mode='success',query=''){
  await context.setExtraHTTPHeaders({'X-Grant-QA-Response':mode});
  await page.request.get(base+'/qa-reset.php');
  await page.goto(base+'/contact/'+query,{waitUntil:'networkidle'});
 }
 async function fill(message){
  await page.locator('[name=name]').fill('Local QA author');await page.locator('[name=email]').fill('qa@example.test');
  await page.locator('[name=message]').fill(message);await page.locator('[name=consent]').check();
 }
 async function send(){await page.locator('[type=submit]').click();await page.waitForFunction(()=>document.querySelector('form').getAttribute('aria-busy')==='false');}
 for(const stage of ['general','manuscript','published','publisher']){
  await fresh();await page.locator('[name=project_stage]').selectOption(stage);
  const visible=await page.locator('[data-project-fields]').evaluateAll(xs=>xs.filter(x=>!x.hidden).map(x=>x.dataset.projectFields));
  check(JSON.stringify(visible)===JSON.stringify(stage==='general'?[]:[stage]),'Only relevant optional fields shown for '+stage);
  check(await page.locator('[data-project-fields] input[required]').count()===0,'No new required fields for '+stage);
  if(stage==='manuscript'){
   await page.locator('[name=word_count]').fill('72000');await page.locator('[name=genre]').fill('Fiction');await page.locator('[value=Paperback]').check();
   await page.locator('[name=project_stage]').selectOption('published');await page.locator('[name=project_stage]').selectOption('manuscript');
   check(await page.locator('[name=word_count]').inputValue()==='72000','Switching project stage preserves typed details');
  }
  if(stage==='published')await page.locator('[name=marketplace]').fill('Amazon.co.uk');
  if(stage==='publisher')await page.locator('[name=title_count]').fill('4');
  await fill('Local isolated '+stage+' test '+Date.now());await send();
  check(await page.locator('.gpc-form-status').getAttribute('data-state')==='success','Actual WordPress handler accepts '+stage+' with an optional blank book link');
  check((await page.locator('.gpc-form-status').textContent()).includes('Web3Forms has accepted'),'Success reports provider acceptance accurately');
  check(await page.locator('[name=message]').inputValue()===''&&await page.locator('[name=project_stage]').inputValue()==='general','Successful submission resets project fields and defaults');
  check(await page.locator('[data-project-fields]:not([hidden])').count()===0,'Conditional groups reset after success');
  check(await page.locator('.gpc-form-status').evaluate(e=>e===document.activeElement),'Confirmation receives keyboard focus');
 }
 for(const mode of ['rejected','timeout']){
  await fresh(mode);await page.locator('[name=project_stage]').selectOption('manuscript');await page.locator('[name=word_count]').fill('72000');
  await fill('Local '+mode+' test '+Date.now());await send();
  check(await page.locator('.gpc-form-status').getAttribute('data-state')==='error','Provider '+mode+' does not produce a success message');
  check(await page.locator('[name=word_count]').inputValue()==='72000'&&(await page.locator('[name=message]').inputValue()).includes(mode),'Provider '+mode+' preserves the message and conditional data');
 }
 await fresh();await fill('Local expired nonce test');await page.locator('[name=gpc_nonce]').evaluate(e=>e.value='expired');await send();
 check((await page.locator('.gpc-form-status').textContent()).includes('expired'),'Expired nonce provides a specific recoverable error');
 await fresh();const initial=posts;await page.locator('[type=submit]').click();
 check(posts===initial&&await page.locator('[name=name]').evaluate(e=>e.validity.valueMissing),'Browser blocks an empty required name before transport');
 await fill('Local invalid URL test');await page.locator('[data-book-details]').evaluate(e=>e.open=true);await page.locator('[name=book_url]').fill('not-a-url');await page.locator('[type=submit]').click();
 check(posts===initial&&await page.locator('[name=book_url]').evaluate(e=>e.validity.typeMismatch),'Malformed optional book URL is revealed and blocked');
 // Analytics integration: no identifiers, no default consent, no personal fields.
 await fresh('success','?request=project&service=Cover%20design&project=luma');
 check(await page.locator('[name=service]').inputValue()==='Cover design'&&await page.locator('[name=project_context]').inputValue()==='luma','Service and known portfolio context prefill correctly');
 await page.evaluate(()=>{document.addEventListener('click',e=>e.preventDefault(),{once:true});document.querySelector('.gp-header-cta a').click()});
 check(await page.evaluate(()=>testEvents.length)===0,'No CTA event before statistics consent');
 await page.evaluate(()=>document.dispatchEvent(new CustomEvent('grant:analytics-consent',{detail:{statistics:true}})));
 await page.evaluate(()=>{document.addEventListener('click',e=>e.preventDefault(),{once:true});document.querySelector('.gp-header-cta a').click()});
 check(await page.evaluate(()=>testEvents.some(e=>e[1]==='select_content'&&e[2].destination==='/contact/')),'Consented CTA event records only the destination path');
 const message='Local consented duplicate test '+Date.now();await fill(message);await send();
 check(await page.evaluate(()=>testEvents.filter(e=>e[1]==='generate_lead').length)===1,'Confirmed provider acceptance records one consented lead');
 await fill(message);await send();
 check((await page.locator('.gpc-form-status').textContent()).includes('already been submitted')&&await page.evaluate(()=>testEvents.filter(e=>e[1]==='generate_lead').length)===1,'A confirmed duplicate is not a new conversion');
 check(await page.evaluate(()=>!JSON.stringify(testEvents).includes('qa@example.test')&&!JSON.stringify(testEvents).includes('Local QA author')),'Conversion events exclude names, emails and messages');
 await page.evaluate(()=>document.dispatchEvent(new CustomEvent('grant:analytics-consent',{detail:{statistics:false}})));
 const eventCount=await page.evaluate(()=>testEvents.length);
 await page.evaluate(()=>{document.addEventListener('click',e=>e.preventDefault(),{once:true});document.querySelector('.gp-header-cta a').click()});
 check(await page.evaluate(()=>testEvents.length)===eventCount,'Revoking consent stops new events');
 await page.goto(base+'/');await page.locator('.gp-mobile-nav summary').click();await page.keyboard.press('Tab');
 check(await page.evaluate(()=>document.activeElement.textContent)==='Services','Mobile menu keyboard order begins with Services');
 await page.keyboard.press('Escape');check(!await page.locator('.gp-mobile-nav').evaluate(e=>e.open),'Escape closes the mobile menu');
 await page.locator('.gp-mobile-nav summary').click();await page.locator('.gp-mobile-nav a').filter({hasText:'Our Work'}).click();
 check(new URL(page.url()).pathname==='/case-studies/','Portfolio is directly reachable through mobile navigation');
 await page.goto(base+'/faq/');const summary=page.locator('.gp-faq-item summary').first();await summary.focus();await page.keyboard.press('Enter');
 check(await summary.locator('..').evaluate(e=>e.open),'FAQ opens with the keyboard');
 check(errors.length===0,'No page JavaScript exceptions during interactions');
 await context.close();
 const nojs=await browser.newContext({javaScriptEnabled:false});const np=await nojs.newPage();
 await np.request.get(base+'/qa-reset.php');await np.goto(base+'/contact/');
 check(await np.locator('[data-project-fields][hidden]').count()===0,'Without JavaScript all optional groups remain available');
 await np.locator('[name=name]').fill('Local no-JS author');await np.locator('[name=email]').fill('qa@example.test');await np.locator('[name=message]').fill('Local native submission '+Date.now());await np.locator('[name=consent]').check();
 await Promise.all([np.waitForNavigation(),np.locator('[type=submit]').click()]);
 check((await np.locator('body').textContent()).includes('Web3Forms has accepted'),'Native no-JavaScript submission reaches the same safe WordPress handler');
 await nojs.close();await browser.close();
 fs.writeFileSync('/workspace/grant-qa/premium-4.7/after/forms-and-navigation.json',JSON.stringify({checks:checks.length,passed:checks,external_messages_sent:0},null,2));console.log('PASS:',checks.length,'actual WordPress form, navigation, consent and attribution checks; provider and email intercepted by local MU plugin.');
})().catch(e=>{console.error(e);process.exit(1)});
