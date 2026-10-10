from pathlib import Path
from urllib.request import urlopen
from lxml import html
import json
base='http://127.0.0.1:9401'
seed=json.load(urlopen(base+'/qa-manual-seo.php'))
try:
 doc=html.fromstring(urlopen(seed['url']).read())
 v=seed['expected'];checks={
 'one_title':len(doc.xpath('//head/title'))==1,
 'manual_title':doc.xpath('//head/title/text()')==[v['title']],
 'manual_description':doc.xpath('//meta[@name="description"]/@content')==[v['metadesc']],
 'manual_canonical':doc.xpath('//link[@rel="canonical"]/@href')==[v['canonical']],
 'manual_social_title':doc.xpath('//meta[@property="og:title"]/@content')==[v['opengraph-title']],
 'manual_social_description':doc.xpath('//meta[@property="og:description"]/@content')==[v['opengraph-description']],
 'manual_social_image':doc.xpath('//meta[@property="og:image"]/@content')==[v['opengraph-image']],
 'manual_twitter_title':doc.xpath('//meta[@name="twitter:title"]/@content')==[v['twitter-title']],
 'manual_twitter_description':doc.xpath('//meta[@name="twitter:description"]/@content')==[v['twitter-description']],
 'manual_twitter_image':doc.xpath('//meta[@name="twitter:image"]/@content')==[v['twitter-image']],
 'focus_keyphrase_preserved':seed['focus_keyphrase']==v['focuskw'],
 }
 canvas=html.fromstring(urlopen(base+'/qa-canvas/').read())
 checks['canvas_one_title_after_late_title_support_removal']=len(canvas.xpath('//head/title'))==1
 Path('/workspace/grant-qa/authority-after/manual-yoast.json').write_text(json.dumps(checks,indent=2))
 print(checks)
 assert all(checks.values()),'Manual Yoast checks failed'
finally:
 restore=json.load(urlopen(base+'/qa-manual-seo.php?mode=restore'))
 print('Temporary test metadata restored:',restore['mode'])
