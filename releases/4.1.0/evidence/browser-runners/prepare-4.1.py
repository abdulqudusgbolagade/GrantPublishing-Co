from pathlib import Path
import json,re,subprocess,zipfile
r=Path('/workspace/GrantPublishing-Co'); qa=Path('/workspace/grant-qa')
before=qa/'baseline-4.0'
with zipfile.ZipFile(r/'releases/4.0.0/grant-publishing-site-4.0.0.zip') as z:z.extractall(before)
builder=(qa/'build-fixtures.py').read_text().replace('baseline-3.4','baseline-4.0')
(qa/'build-fixtures-4.1.py').write_text(builder)
subprocess.run(['python3',str(qa/'build-fixtures-4.1.py')],check=True)
cache=qa/'font-cache-4.1';cache.mkdir(exist_ok=True)
css_url='https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500;1,600&family=Instrument+Serif:ital@0;1&family=Manrope:wght@400;500;600;700&display=swap'
def download(url,dest):subprocess.run(['curl','--fail','--location','--silent','--show-error','--max-time','45','--output',str(dest),url],check=True)
download(css_url,cache/'grant-fonts.css')
css=(cache/'grant-fonts.css').read_text();mapping={}
for i,url in enumerate(dict.fromkeys(re.findall(r'url\((https://[^)]+)\)',css))):
 dest=cache/f'font-{i}.font';download(url,dest);mapping[url]=str(dest)
(cache/'mapping.json').write_text(json.dumps(mapping,indent=2)+'\n')
visual=(qa/'visual-4.0.cjs').read_text().replace('4.0','4.1').replace('font-cache/','font-cache-4.1/').replace('32px "Instrument Serif"','500 32px "Cormorant Garamond"')
(qa/'visual-4.1.cjs').write_text(visual)
interactions=(qa/'interactions-test.cjs').read_text().replace('rgb(84, 93, 112)','rgb(43, 40, 52)').replace('interactions-result.txt','interactions-results-4.1.txt')
(qa/'interactions-4.1.cjs').write_text(interactions)
optional=(qa/'optional-forms-4.0.cjs').read_text().replace('4.0','4.1');(qa/'optional-forms-4.1.cjs').write_text(optional)
flows=(qa/'flows-4.0.cjs').read_text().replace('4.0','4.1')
flows=flows.replace("const book=p.locator('.gp-book-visual a').first();", "const book=p.locator('.gp-book-visual a[href=\"https://www.amazon.com/dp/1947646168\"]').first();")
needle=" const review=p.locator('.gp-review-john .gp-review-source a');"
insertion=''' const luma=p.locator('.gp-review-john .gp-book-visual a');const lpPromise=ctx.waitForEvent('page');await luma.click();const lp=await lpPromise;await lp.waitForLoadState();check(lp.url()==='https://www.linkedin.com/services/page/a29863343146852122/',`${mode} actual Luma cover destination`);await lp.close();
 const father=p.locator('.gp-book-grandfather a');const fpPromise=ctx.waitForEvent('page');await father.click();const fp=await fpPromise;await fp.waitForLoadState();check(fp.url()==='https://www.amazon.com/dp/1947646117',`${mode} actual Grandfather cover destination`);await fp.close();
 check((await p.locator('.gp-book-grandfather img').getAttribute('alt'))==='My Dear Grandfather by Barsha Rai',`${mode} actual Grandfather author credit`);
'''
assert needle in flows;flows=flows.replace(needle,insertion+needle)
(qa/'flows-4.1.cjs').write_text(flows)
print(f'Prepared 4.1 fixtures, browser runners and {len(mapping)} verified-TLS official font files. Baseline: published 4.0.')
