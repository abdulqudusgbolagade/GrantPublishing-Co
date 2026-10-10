from pathlib import Path
from urllib.request import urlopen
from urllib.parse import urlsplit,urljoin,urldefrag
from lxml import html
import json

OUT=Path('/workspace/grant-qa/authority-after');OUT.mkdir(exist_ok=True)
routes=json.loads(Path('/workspace/grant-qa/journey-migration-4.4.json').read_text())['all_routes']
docs={};report=[];checks=0
for key,url in routes.items():
 response=urlopen(url,timeout=30);body=response.read();(OUT/(key+'.html')).write_bytes(body)
 doc=html.fromstring(body);docs[key]=doc
 report.append({'key':key,'url':url,'status':response.status,'h1_count':len(doc.xpath('//h1')),'header_count':len(doc.xpath('//header')),'footer_count':len(doc.xpath('//footer')),'canonical':doc.xpath('//link[@rel="canonical"]/@href'),'links':[]})
 assert response.status==200 and len(doc.xpath('//h1'))==1 and len(doc.xpath('//header'))==1 and len(doc.xpath('//footer'))==1,key
 assert len(doc.xpath('//head/title'))==1 and len(doc.xpath('//meta[@name="description"]'))==1 and len(doc.xpath('//link[@rel="canonical"]'))==1,(key,'SEO head duplicates or omissions')
 assert doc.xpath('//footer//a[contains(@class,"gp-footer-email")]/text()')==['hello@grantpublishingco.com'],(key,'footer email not readable')
 assert '00000000-0000-0000-0000-000000000001' not in body.decode(),(key,'private test key exposed')
 paths={urlsplit(url).path:key for key,url in routes.items()}
for row in report:
 key=row['key'];doc=docs[key]
 ids=doc.xpath('//@id');assert len(ids)==len(set(ids)),(key,'duplicate id')
 for anchor in doc.xpath('//a[@href]'):
  href=anchor.get('href');
  if anchor.get('target')=='_blank':assert {'noopener','noreferrer'}.issubset(set((anchor.get('rel') or '').split())),(key,href,'new tab missing protection')
  parsed=urlsplit(urljoin(row['url'],href));label=anchor.text_content().strip() or anchor.get('aria-label')
  assert href and href!='#' and not href.startswith('javascript:'),(key,href)
  if parsed.hostname=='127.0.0.1':
   if parsed.path.endswith('.pdf'):
    response=urlopen(urldefrag(href)[0],timeout=20);assert response.status==200 and response.read(5)==b'%PDF-',(key,href,'invalid document')
    row['links'].append({'label':label,'href':href});checks+=1;continue
   assert parsed.path in paths,(key,href,'unknown route')
   if parsed.fragment:assert docs[paths[parsed.path]].xpath('//*[@id=$id]',id=parsed.fragment),(key,href,'missing anchor')
   assert not parsed.path.startswith('/services/connected-catalog'),(key,href)
  assert label,(key,href,'link has no name')
  row['links'].append({'label':label,'href':href});checks+=1
 service=docs['services'];assert len(service.xpath('//*[contains(concat(" ",normalize-space(@class)," ")," gp-service-row ")]'))==10
 assert all(urlsplit(a.get('href')).path in paths for a in service.xpath('//*[contains(concat(" ",normalize-space(@class)," ")," gp-service-row ")]//a[contains(@class,"elementor-button")]'))
assert len(docs['feedback'].xpath('//blockquote'))==5,'Expected five preserved client reviews'
for key,source in [('privacy-policy','approved-privacy-policy.txt'),('terms-of-service','approved-terms-of-service.txt')]:
 expected=Path('/workspace/GrantPublishing-Co/docs',source).read_text().strip().split('\n\n')
 blocks=docs[key].xpath('//main//h1|//main//h2|//main//p|//main//ul')
 actual=[]
 for block in blocks:
  if block.tag=='ul':actual.append('\n'.join('• '+li.text_content() for li in block.xpath('./li')))
  else:
   # Preserve line breaks rather than joining the contact paragraph's words.
   for br in block.xpath('.//br'):br.tail='\n'+(br.tail or '')
   actual.append(block.text_content())
 assert actual==expected,(key,'legal wording differs')
(OUT/'crawl.json').write_text(json.dumps({'pages':report,'named_link_checks':checks,'legal_copy_exact':True,'service_cards':10},indent=2))
print('PASS:',len(report),'real WordPress pages,',checks,'named link/destination/anchor checks, 10 service cards, original approved legal paragraphs exact.')
