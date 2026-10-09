const vm = require('node:vm'),fs = require('node:fs'),assert = require('node:assert/strict');
const code = fs.readFileSync(require('node:path').join(__dirname,'../grant-publishing-site/assets/enquiry.js'),'utf8');
async function test(mode, query='?request=assessment&service=Amazon%20listing%20optimization') {
 let submit, fetched=0, reset=0, focused=0;
 const status={dataset:{},textContent:'',focus(){focused++}},button={disabled:false,textContent:'Send enquiry'};
 const form={dataset:{contactEmail:'new@example.com'},action:'https://example.com/wp-admin/admin-post.php',setAttribute(){},getAttribute(){return this.action},elements:{request:{value:'project',addEventListener(){}},service:{value:'',options:[{value:''},{value:'Amazon listing optimization'}]}},reportValidity(){return mode!=='invalid'},querySelector(s){return s.includes('button')?button:s==='.gpc-form-status'?status:null},addEventListener(e,f){submit=f},reset(){reset++}};
 const ctx={document:{readyState:'complete',querySelectorAll(){return [form]}},window:{location:{search:query}},URLSearchParams,FormData:class{set(){}},AbortController,setTimeout,clearTimeout,fetch:async()=>{fetched++;if(mode==='network')throw Error();if(mode==='malformed')return {ok:true,json:async()=>{throw SyntaxError()}};return {ok:mode!=='reject',json:async()=>({success:mode!=='reject',data:{message:mode==='reject'?'Rejected':'Submitted'}})}}};
 vm.runInNewContext(code,ctx);
 assert.equal(form.elements.request.value,query.includes('assessment')?'assessment':'project');
 assert.equal(form.elements.service.value,query.includes('Amazon')?'Amazon listing optimization':'');
 await submit({preventDefault(){}});
 if(mode==='invalid'){assert.equal(fetched,0);return}
 assert.equal(fetched,1);assert.equal(button.disabled,false);assert.equal(form.dataset.sending,'0');assert.equal(focused,1);
 if(mode==='success'){assert.equal(reset,1);assert.equal(status.textContent,'Submitted');assert.equal(status.dataset.state,'success');assert.equal(button.textContent,'Request free assessment');assert.equal(form.elements.request.value,'assessment')}
 else {assert.equal(reset,0);assert.equal(status.dataset.state,'error');assert.ok(status.textContent);if(mode==='network'||mode==='malformed')assert.ok(status.textContent.includes('new@example.com'))}
}
(async()=>{for(const mode of ['success','reject','network','malformed','invalid'])await test(mode);await test('invalid','?request=bad&service=Unknown');console.log('PASS: enquiry JS success, rejection, connection failure, malformed response, invalid input, and request/service selection. No emails sent.');})().catch(e=>{console.error(e);process.exitCode=1});
