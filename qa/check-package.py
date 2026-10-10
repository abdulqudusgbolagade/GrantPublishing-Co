from pathlib import Path
from lxml import html
from html.parser import HTMLParser
from urllib.parse import unquote,parse_qs,urlsplit
import re,json,collections
r=Path(__file__).resolve().parents[1]/'grant-publishing-site';defs=json.loads((r/'pages.json').read_text());issues=[];count=0
class Balance(HTMLParser):
 def __init__(self):super().__init__();self.stack=[]
 def handle_starttag(self,t,a):
  if t not in {'area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr'}:self.stack.append(t)
 def handle_endtag(self,t):
  if not self.stack or self.stack.pop()!=t:raise AssertionError('Unbalanced '+t)
for k in defs:
 p=r/'templates'/f'{k}.html';raw=p.read_text();b=Balance();b.feed(raw);assert not b.stack,k
 doc=html.fromstring(raw);assert len(doc.xpath('//h1'))==1,(k,'headings');assert len(doc.xpath('//main'))==1,k
 ids=doc.xpath('//@id');assert len(ids)==len(set(ids)),(k,'duplicate IDs')
 for tok in re.findall(r'\{\{([^}]+)\}\}',raw):
  if tok.startswith('url:'):assert tok[4:] in defs,(k,tok)
  elif tok.startswith('asset:'):assert (r/'assets'/tok[6:]).is_file(),tok
  else:assert tok in ['header','footer','form:project','form:assessment','contact_links','showcase:home','showcase:services','cases:hub','faq:general'],tok
 for href in doc.xpath('//a/@href'):
  assert href and href!='#',(k,href)
  m=re.match(r'\{\{url:([^}]+)\}\}(.*)',href)
  if m:
   dest,suffix=m.groups()
   if '#' in suffix:
    anchor=suffix.split('#',1)[1]
    assert anchor in (r/'templates'/f'{dest}.html').read_text() or (anchor=='gpc-enquiry' and dest in ['contact','enquiry']),(k,href)
   params=parse_qs(urlsplit('https://example.com/'+suffix).query)
   if 'service' in params:assert params['service'][0] in ['Amazon listing optimization','Book descriptions and A+ Content','Book launch or relaunch','Author platform','Series and catalog strategy','Book marketing strategy','Publishing consultation','Book formatting','Cover design','Book publishing and Amazon KDP setup','Amazon Ads campaign setup'],params
 for img in doc.xpath('//img'):
  assert img.get('alt') is not None,(k,'missing image alternative')
  if not img.get('alt'):assert 'publishing-studio' in img.get('src',''),(k,'unlabelled meaningful image')
 for href in doc.xpath('//a/@href'):
  if href.startswith('#'):assert href[1:] in ids,(k,'missing local anchor',href)
 data=json.loads((r/'elementor'/f'{k}.json').read_text());assert data['type']=='page' and data['version']=='0.4'
 def layout_text(nodes):
  parts=[]
  for n in nodes:
   st=n['settings'];kind=n.get('widgetType','')
   if kind in ['heading','text-editor']:
    parts.append(html.fromstring('<div>'+st.get('title',st.get('editor',''))+'</div>').text_content())
   elif kind=='button':parts.append(st['text'])
   elif kind=='shortcode':
    if 'grant_enquiry_form' in st['shortcode']:parts.append('{{form:assessment}}' if 'assessment' in st['shortcode'] else '{{form:project}}')
    elif 'grant_contact_links' in st['shortcode']:parts.append('{{contact_links}}')
    elif 'grant_book_showcase' in st['shortcode']:parts.append('{{showcase:services}}' if 'services' in st['shortcode'] else '{{showcase:home}}')
    elif 'grant_case_cards' in st['shortcode']:parts.append('{{cases:hub}}')
    elif 'grant_faqs' in st['shortcode']:parts.append('{{faq:general}}')
   parts.append(layout_text(n['elements']))
  return ''.join(parts)
 expected=doc.xpath('//main')[0].text_content()
 assert re.sub(r'\s+','',expected)==re.sub(r'\s+','',layout_text(data['content'])),(k,'shortcode/native content differs')
 nativeids=[];headings=[]
 def visit(nodes):
  global count
  for n in nodes:
   count+=1;nativeids.append(n['id']);assert re.fullmatch('[0-9a-f]{8}',n['id']);assert isinstance(n['settings'],dict)
   if n['elType']=='widget':
    assert n['widgetType'] in ['heading','text-editor','button','image','shortcode'],n['widgetType']
    if n['widgetType']=='heading' and n['settings']['header_size']=='h1':headings.append(n)
    if n['widgetType']=='shortcode':assert 'grant_enquiry_form' in n['settings']['shortcode'] or 'grant_contact_links' in n['settings']['shortcode'] or 'grant_book_showcase' in n['settings']['shortcode'] or 'grant_case_cards' in n['settings']['shortcode'] or 'grant_faqs' in n['settings']['shortcode']
   else:assert n['elType']=='container'
   visit(n['elements'])
 visit(data['content']);assert len(nativeids)==len(set(nativeids));assert len(headings)==1
 assert '—' not in raw,k
css=(r/'assets/site.css').read_text();assert css.count('{')==css.count('}');assert '@media(max-width:720px)' in css;assert 'prefers-reduced-motion' in css;assert ':focus-visible' in css
design=(r/'assets/design.css').read_text();assert design.count('{')==design.count('}');assert all(color in design for color in ['#09072b','#201b7f','#3350df','#704ddd','#bd91f9','#f7f5f2']);assert 'prefers-reduced-motion' in design
for p in r.rglob('*.php'):
 assert p.read_text().startswith('<?php'),p
 assert "defined('ABSPATH')" in p.read_text(),p
print(f'PASS: {len(defs)} matching HTML/Elementor pages, {count} native elements, heading and ID checks, all route/asset tokens, link anchors, service choices, fallback HTML balance and responsive/focus CSS markers.')
