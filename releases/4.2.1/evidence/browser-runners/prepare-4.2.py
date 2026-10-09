"""Restore the release's local fixtures; never connects to WordPress."""
from pathlib import Path
import json,re,subprocess,zipfile
r=Path('/workspace/GrantPublishing-Co');q=Path('/workspace/grant-qa');q.mkdir(exist_ok=True)
with zipfile.ZipFile(r/'releases/4.1.0/grant-publishing-site-4.1.0.zip') as z:z.extractall(q/'baseline-4.1')
with (q/'shared-4.2.json').open('w') as output:
 subprocess.run(['node','qa/php.mjs',str(q/'shared-4.2.php')],cwd=r,stdout=output,check=True)
cache=q/'font-cache-4.1';cache.mkdir(exist_ok=True)
css_url='https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500;1,600&family=Instrument+Serif:ital@0;1&family=Manrope:wght@400;500;600;700&display=swap'
def download(url,path):subprocess.run(['curl','--fail','--location','--silent','--show-error','--max-time','45','--output',str(path),url],check=True)
if not (cache/'mapping.json').exists():
 download(css_url,cache/'grant-fonts.css');css=(cache/'grant-fonts.css').read_text();mapping={}
 for i,url in enumerate(dict.fromkeys(re.findall(r'url\((https://[^)]+)\)',css))):
  dest=cache/f'font-{i}.font';download(url,dest);mapping[url]=str(dest)
 (cache/'mapping.json').write_text(json.dumps(mapping,indent=2)+'\n')
subprocess.run(['python3',str(q/'build-fixtures-4.2.py')],check=True)
print('Prepared 4.2 with the retained published 4.1 baseline and official fonts. After shared markup uses actual PHP with mocked WordPress APIs; Elementor DOM remains an approximation.')
