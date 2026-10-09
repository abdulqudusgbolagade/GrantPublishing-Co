"""Actual shared PHP markup, approximated Elementor widget wrappers, no WordPress."""
from pathlib import Path
import re,json,html,shutil
root=Path('/workspace/GrantPublishing-Co/grant-publishing-site')
out=Path('/workspace/grant-qa/fixtures');out.mkdir(exist_ok=True)
defs=json.loads((root/'pages.json').read_text())
def route(k):
 d=defs[k];return '/' if k=='home' else (route(d['parent']).rstrip('/') if d.get('parent') else '')+'/'+d['slug']+'/'
def resolve(s):
 return re.sub(r'\{\{(url|asset):([^}]+)\}\}',lambda m:route(m[2]) if m[1]=='url' else '/assets/'+m[2],s)
def shared_markup(s):
 return s.replace('https://local.test/plugin/assets/','/assets/').replace('https://local.test/wp-admin/admin-post.php','/mock-enquiry').replace('https://local.test','')
def native(nodes,shared):
 s=''
 for n in nodes:
  st=n['settings'];cls=st.get('css_classes',st.get('_css_classes',''));ident=' id="'+st['_element_id']+'"' if st.get('_element_id') else ''
  if n['elType']=='container':s+='<div class="e-con '+cls+'"'+ident+'>'+native(n['elements'],shared)+'</div>';continue
  t=n['widgetType'];content=''
  if t=='heading':
   title=st['title'];link=st.get('link',{}).get('url');title='<a href="'+link+'">'+title+'</a>' if link else title;content='<'+st['header_size']+' class="elementor-heading-title">'+title+'</'+st['header_size']+'>'
  elif t=='text-editor':content=st.get('editor','')
  elif t=='button':content='<a class="elementor-button" href="'+st['link']['url']+'"'+(' target="_blank" rel="noopener noreferrer"' if st['link'].get('is_external') else '')+'><span class="elementor-button-text">'+st['text']+'</span></a>'
  elif t=='image':
   content='<img src="'+st['image']['url']+'" alt="'+html.escape(st.get('image',{}).get('alt','Image'))+'">'
   if st.get('link_to')=='custom':
    l=st['link'];content='<a href="'+l['url']+'"'+(' target="_blank" rel="noopener noreferrer"' if l.get('is_external') else '')+'>'+content+'</a>'
  elif t=='shortcode':
   sc=st['shortcode']
   if 'enquiry_form' in sc:content=shared['form_assessment' if 'assessment' in sc else 'form_project']
   elif 'book_showcase' in sc:content=shared['showcase_services' if 'services' in sc else 'showcase_home']
   elif 'contact_links' in sc:content=shared['contacts']
   else:raise ValueError(sc)
  s+='<div class="elementor-widget '+cls+'"'+ident+'><div class="elementor-widget-container">'+content+'</div></div>'
 return shared_markup(s)
total=0
for variant,src,jsonfile in [('after',root,'shared-4.3.json'),('before',Path('/workspace/grant-qa/baseline-4.2.1/grant-publishing-site'),'shared-before-4.3.json')]:
 shared=json.loads((Path('/workspace/grant-qa')/jsonfile).read_text())
 source_defs=json.loads((src/'pages.json').read_text());dest=out/variant;dest.mkdir(exist_ok=True)
 if (dest/'assets').exists():shutil.rmtree(dest/'assets')
 shutil.copytree(src/'assets',dest/'assets')
 for k in source_defs:
  for mode in ['fallback','native']:
   head=shared_markup(shared['headers'][k]);foot=shared_markup(shared['footer'])
   if mode=='fallback':body=(src/'templates'/f'{k}.html').read_text().replace('{{header}}',head).replace('{{footer}}',foot)
   else:body='<div class="gpc-site">'+head+'<main id="gpc-main"><div class="elementor elementor-fixture">'+native(json.loads((src/'elementor'/f'{k}.json').read_text())['content'],shared)+'</div></main>'+foot+'</div>'
   body=re.sub(r'\{\{form:(assessment|project)\}\}',lambda m:shared_markup(shared['form_'+m[1]]),body).replace('{{contact_links}}',shared_markup(shared['contacts']))
   body=re.sub(r'\{\{showcase:(home|services)\}\}',lambda m:shared_markup(shared['showcase_'+m[1]]),body)
   body=resolve(body).replace('/assets/',f'/{variant}/assets/')
   script='<script src="/'+variant+'/assets/showcase.js"></script>' if (src/'assets/showcase.js').exists() else ''
   (dest/f'{k}-{mode}.html').write_text('<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'+html.escape(source_defs[k]['title'])+'</title><meta name="description" content="'+html.escape(source_defs[k]['description'])+'"><link rel="stylesheet" href="/'+variant+'/assets/site.css"><link rel="stylesheet" href="/'+variant+'/assets/design.css"><style>body{margin:0}</style>'+body+'<script src="/'+variant+'/assets/interactions.js"></script><script src="/'+variant+'/assets/enquiry.js"></script>'+script+'</html>')
   total+=1
print(f'Built {total} fixtures: actual shared PHP markup with mocked WordPress APIs; native Elementor DOM approximated.')
