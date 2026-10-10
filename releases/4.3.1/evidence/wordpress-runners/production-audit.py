from pathlib import Path
from concurrent.futures import ThreadPoolExecutor
from urllib.request import Request, urlopen
from urllib.error import HTTPError
from urllib.parse import urljoin, urlsplit, urldefrag
from lxml import html
import json, hashlib

ROOT=Path('/workspace/GrantPublishing-Co/grant-publishing-site')
OUT=Path('/workspace/grant-qa/production-before')
OUT.mkdir(exist_ok=True)
defs=json.loads((ROOT/'pages.json').read_text())
base='https://grantpublishingco.com/'
paths=['' if k=='home' else (defs[d['parent']]['slug']+'/' if d.get('parent') else '')+d['slug']+'/' for k,d in defs.items()]
paths += ['services/connected-catalog/','services/book-discovery/','services/book-product-page/','privacy-policy/','terms-of-service/','wp-json/wp/v2/pages?per_page=100&_fields=id,slug,parent,link,status,title','wp-sitemap.xml']
def fetch(path):
    url=urljoin(base,path)
    record={'requested':url}
    try:
        response=urlopen(Request(url,headers={'User-Agent':'GrantProductionAudit/1.0','Cache-Control':'no-cache'}),timeout=35)
    except HTTPError as e:
        response=e
    except Exception as e:
        record['error']=str(e); return record
    body=response.read()
    record.update(status=response.status,final_url=response.url,content_type=response.headers.get('Content-Type',''),bytes=len(body))
    file=OUT/(hashlib.sha256(url.encode()).hexdigest()[:16]+'.html')
    file.write_bytes(body); record['file']=str(file)
    if 'html' in record['content_type']:
        doc=html.fromstring(body)
        record.update(h1s=[x.text_content().strip() for x in doc.xpath('//h1')],titles=doc.xpath('//head/title/text()'),canonical=doc.xpath('//link[@rel="canonical"]/@href'),logos=doc.xpath('//header//img/@src|//footer//img/@src'),icons=doc.xpath('//link[contains(@rel,"icon")]/@href'),versions=doc.xpath('//link[contains(@href,"grant-publishing-site")]/@href'),links=doc.xpath('//a/@href'),images=doc.xpath('//img/@src'),forms=len(doc.xpath('//form')),privacy=doc.xpath('//p[contains(@class,"gpc-audit-form-disclaimer")]/text()'))
    return record
with ThreadPoolExecutor(max_workers=4) as pool:
    records=list(pool.map(fetch,paths))
known={r['requested'] for r in records}
extra=sorted({urldefrag(urljoin(r.get('final_url',base),link))[0] for r in records for link in r.get('links',[]) if urlsplit(urljoin(base,link)).hostname in ('grantpublishingco.com','www.grantpublishingco.com') and not any(x in link for x in ['wp-admin','wp-content','?'])}-known)
with ThreadPoolExecutor(max_workers=4) as pool:
    records+=list(pool.map(fetch,extra))
(OUT/'routes.json').write_text(json.dumps(records,indent=2))
print(json.dumps([{'requested':r['requested'],'status':r.get('status'),'final':r.get('final_url'),'h1s':r.get('h1s'),'canonical':r.get('canonical')} for r in records],indent=2))
