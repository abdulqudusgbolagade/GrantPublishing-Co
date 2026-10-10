const {chromium}=require('playwright'), fs=require('fs'),{execFile}=require('child_process'),{promisify}=require('util');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 const page=await browser.newPage({reducedMotion:'reduce'});
 // Chromium does not inherit the cloud proxy CA. Preserve TLS verification by
 // fetching through curl's configured trusted CA, then fulfil the browser GET.
 const cache=new Map();
 await page.route('**/*',async route=>{
  const req=route.request();if(req.method()!=='GET')return route.abort();
  if(!req.url().startsWith('https://'))return route.continue();
  const url=req.url();
  if(!cache.has(url))cache.set(url,promisify(execFile)('curl',['--fail','--silent','--show-error','--location','--max-time','40',url],{encoding:'buffer',maxBuffer:20000000}));
  try{const {stdout}=await cache.get(url);await route.fulfill({status:200,body:stdout,contentType:url.includes('fonts.googleapis')?'text/css':url.includes('fonts.gstatic')?'font/woff2':url.includes('.css')?'text/css':url.includes('.js')?'text/javascript':url.includes('.webp')?'image/webp':url.includes('.png')?'image/png':url.includes('.jpg')?'image/jpeg':'text/html'});}catch(e){await route.abort();}
 });
 const records=require('/workspace/grant-qa/production-before/routes.json').slice(0,20).filter(r=>r.status===200);
 const out='/workspace/grant-qa/production-before';const results=[];let errors=[];
 page.on('pageerror',e=>errors.push(e.message));
 for(const record of records){
  errors=[];await page.goto(record.final_url,{waitUntil:'networkidle',timeout:45000});
  await page.addScriptTag({path:'/workspace/grant-qa/node_modules/axe-core/axe.min.js'});
  for(const width of [1440,1280,1024,768,480,390,360]){
   await page.setViewportSize({width,height:900});await page.evaluate(()=>document.fonts.ready);
   await page.locator('img[src]').evaluateAll(imgs=>imgs.forEach(i=>i.loading='eager'));
   await page.waitForFunction(()=>Array.from(document.images).filter(i=>i.hasAttribute('src')).every(i=>i.complete),{timeout:15000});
   const state=await page.evaluate(async()=>({overflow:document.documentElement.scrollWidth>innerWidth,overflowElements:Array.from(document.querySelectorAll('body *')).filter(e=>{const r=e.getBoundingClientRect();return r.width>0&&(r.right>innerWidth+1||r.left<-1)}).slice(0,12).map(e=>e.className),h1:document.querySelectorAll('h1').length,brokenImages:Array.from(document.images).filter(i=>i.hasAttribute('src')&&!i.naturalWidth).map(i=>i.src),violations:(await axe.run(document.querySelector('.gpc-site')||document,{runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21aa']}})).violations.map(v=>({id:v.id,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))}))}));
   results.push({url:record.requested,width,...state,errors:[...errors]});
   if([1440,390].includes(width))await page.screenshot({path:out+'/'+(new URL(record.requested).pathname.replaceAll('/','-')||'home')+width+'.png',fullPage:true});
  }
 }
 fs.writeFileSync(out+'/browser.json',JSON.stringify(results,null,2));console.log(JSON.stringify({combinations:results.length,issues:results.filter(x=>x.overflow||x.violations.length||x.brokenImages.length||x.errors.length||x.h1!==1)},null,2));
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
