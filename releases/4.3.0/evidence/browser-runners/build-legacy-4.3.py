"""Exercise actual PHP compatibility on the previous release's native fixtures."""
from pathlib import Path
import json,re,subprocess
q=Path('/workspace/grant-qa');r=Path('/workspace/GrantPublishing-Co')
s=(r/'qa/test-publishing.php').read_text().split('fixture();\n$url =')[0]
s=s.replace("dirname(__DIR__) . '/grant-publishing-site/", "'/workspace/GrantPublishing-Co/grant-publishing-site/")
s+='''$input=json_decode(file_get_contents('/workspace/grant-qa/legacy-input-4.3.json'),true);
$out=array();
foreach($input as $key=>$body) {
 fixture($key);
 $body=str_replace('/before/assets/','https://grant.test/wp-content/plugins/grant-publishing-site/assets/',$body);
 $body=preg_replace('~href="/(?!/)~','href="https://grant.test/',$body);
 $body=preg_replace_callback('~<div class="elementor-widget-container">(<img\\b[^>]*publishing-studio\\.webp[^>]*>)</div>~',function($m){return gpc_native_book_showcase($m[0],new ImageWidget(gpc_asset_url('publishing-studio.webp')));},$body);
 $body=gpc_native_service_copy($body);$body=gpc_native_client_proof($body);$body=gpc_render_contact_icons($body);
 $out[$key]=str_replace(array('https://grant.test/wp-content/plugins/grant-publishing-site/assets/','https://grant.test/'),array('/after/assets/','/'),$body);
}
echo json_encode($out,JSON_UNESCAPED_SLASHES);
}
'''
(q/'legacy-4.3.php').write_text(s)
keys=['home','services','about','contact','publishing-support','feedback']
inputs={k:re.search(r'<main[^>]*>(.*?)</main>',(q/'fixtures/before'/f'{k}-native.html').read_text(),re.S)[1] for k in keys}
(q/'legacy-input-4.3.json').write_text(json.dumps(inputs))
with (q/'legacy-output-4.3.json').open('w') as output:
 subprocess.run(['node','qa/php.mjs',str(q/'legacy-4.3.php')],cwd=r,stdout=output,check=True)
data=json.loads((q/'legacy-output-4.3.json').read_text())
for k,body in data.items():
 s=(q/'fixtures/after'/f'{k}-native.html').read_text();s=re.sub('<main[^>]*>.*?</main>',lambda m:'<main id="gpc-main">'+body+'</main>',s,flags=re.S)
 (q/'fixtures/after'/f'{k}-legacy.html').write_text(s)
print('Built 6 actual-PHP transformed legacy-native fixtures; saved data is untouched.')
