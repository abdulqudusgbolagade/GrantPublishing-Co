const {chromium}=require('playwright'),assert=require('node:assert/strict'),fs=require('fs');
(async()=>{const b=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});const p=await b.newPage({viewport:{width:390,height:844}});let errors=[];p.on('pageerror',e=>errors.push(e.message));
await p.route('https://fonts.googleapis.com/**',r=>r.abort());await p.route('https://fonts.gstatic.com/**',r=>r.abort());
async function form(mode){
 await p.goto('http://127.0.0.1:8767/after/contact-fallback.html?request=assessment&service=Amazon%20listing%20optimization');
 assert.equal(await p.locator('[name=request]').inputValue(),'assessment');assert.equal(await p.locator('[name=service]').inputValue(),'Amazon listing optimization');assert.equal(await p.locator('[type=submit]').textContent(),'Request free assessment');
 await p.locator('[name=request]').selectOption('project');assert.equal(await p.locator('[type=submit]').textContent(),'Send project enquiry');
 await p.locator('[name=name]').fill('Test Author');await p.locator('[name=email]').fill('author@example.com');await p.locator('[name=message]').fill('A local test message, never sent.');await p.locator('[name=consent]').check();
 let calls=0;await p.route('**/mock-enquiry',async r=>{calls++;await new Promise(resolve=>setTimeout(resolve,150));if(mode==='network')return r.abort();if(mode==='malformed')return r.fulfill({status:200,body:'not json'});return r.fulfill({status:mode==='failure'?403:200,contentType:'application/json',body:JSON.stringify({success:mode!=='failure',data:{message:mode==='failure'?'This form has expired. Copy your message, refresh and try again.':'Submitted locally.'}})});});
 await p.locator('[type=submit]').click();assert.equal(await p.locator('form').getAttribute('aria-busy'),'true');assert.equal(await p.locator('[type=submit]').isDisabled(),true);
 await p.waitForFunction(()=>document.querySelector('form').getAttribute('aria-busy')==='false');assert.equal(calls,1);assert.equal(await p.locator('.gpc-form-status').evaluate(e=>e===document.activeElement),true);
 assert.equal(await p.locator('.gpc-form-status').getAttribute('data-state'),mode==='success'?'success':'error');assert.equal(await p.locator('[name=message]').inputValue(),mode==='success'?'':'A local test message, never sent.');
 await p.unroute('**/mock-enquiry');
}
for(const mode of ['success','failure','network','malformed'])await form(mode);
await p.goto('http://127.0.0.1:8767/after/contact-fallback.html');await p.locator('[type=submit]').click();assert.equal(await p.locator('[name=name]').evaluate(e=>e.validity.valueMissing),true);
await p.goto('http://127.0.0.1:8767/after/home-fallback.html');await p.keyboard.press('Tab');assert.equal(await p.evaluate(()=>document.activeElement.className),'gpc-skip-link');await p.keyboard.press('Enter');assert.equal(await p.evaluate(()=>document.activeElement.id),'gpc-main');
await p.locator('summary').click();await p.keyboard.press('Tab');assert.equal(await p.evaluate(()=>document.activeElement.textContent),'Home');await p.keyboard.press('Escape');assert.equal(await p.locator('details').evaluate(e=>e.open),false);
await p.locator('summary').click();await p.mouse.click(385,800);assert.equal(await p.locator('details').evaluate(e=>e.open),false);
await p.emulateMedia({reducedMotion:'reduce'});assert.equal(await p.locator('.gp-service-row').first().evaluate(e=>getComputedStyle(e).transitionDuration),'0s');
// Simulate common inherited theme colors and verify text/buttons retain readable component colors.
await p.addStyleTag({content:'.elementor p{color:white}.elementor .elementor-button-text{color:white}'});await p.locator('.gpc-site').evaluate(e=>e.classList.add('elementor'));assert.equal(await p.locator('.gp-copy p').first().evaluate(e=>getComputedStyle(e).color),'rgb(43, 40, 52)');
await p.addScriptTag({path:'/workspace/grant-qa/node_modules/axe-core/axe.min.js'});
for(const selector of ['.gp-actions .elementor-button','.gp-proof .elementor-button','.gp-footer .gp-button']){
 const e=p.locator(selector).first();await e.hover();await e.focus();const violations=await p.evaluate(async()=> (await axe.run(document.querySelector('.gpc-site'),{runOnly:['color-contrast']})).violations);if(violations.length)console.log(JSON.stringify(violations.map(v=>({id:v.id,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))})),null,2));assert.equal(violations.length,0,selector+' hover/focus contrast');
}
assert.deepEqual(errors,[]);fs.writeFileSync('/workspace/grant-qa/interactions-results-4.2.txt','PASS: real Chromium form validation, selection/action labels, pending/disabled/busy feedback, success, expired nonce response, network/malformed recovery, retained input, status focus; keyboard skip/menu, Escape and outside close, reduced motion, hostile inherited theme colors, light/dark button hover/focus contrast. All submissions intercepted locally; no messages sent.\n');console.log(fs.readFileSync('/workspace/grant-qa/interactions-results-4.2.txt','utf8'));await b.close();})().catch(e=>{console.error(e);process.exit(1)});
