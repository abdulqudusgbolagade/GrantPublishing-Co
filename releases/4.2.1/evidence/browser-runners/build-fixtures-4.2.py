from pathlib import Path
import re,json,html,shutil
root=Path('/workspace/GrantPublishing-Co/grant-publishing-site')
out=Path('/workspace/grant-qa/fixtures');out.mkdir(exist_ok=True)
defs=json.loads((root/'pages.json').read_text())
def route(k):
 d=defs[k];return (route(d['parent']).rstrip('/') if d.get('parent') else '')+'/'+d['slug']+'/' if k!='home' else '/'
def resolve(s):
 return re.sub(r'\{\{(url|asset):([^}]+)\}\}',lambda m: route(m[2]) if m[1]=='url' else '/assets/'+m[2],s)
# Shared markup is extracted from PHP string literals; dynamic sections use fixture equivalents.
pages=(root/'includes/pages.php').read_text()
shared=json.loads(Path('/workspace/grant-qa/shared-4.2.json').read_text())
def current_shared(s):
 return s.replace('https://local.test/plugin/assets/','/assets/').replace('https://local.test/wp-admin/admin-post.php','/mock-enquiry').replace('https://local.test','')
def get_return(fn,src=root):
 pages=(src/'includes/pages.php').read_text()
 section=pages.split('function '+fn+'(')[1].split('\nfunction ')[0]
 return section.split("return '")[-1].split("';")[0]
def dynamic(s,key):
 nav=''.join('<a href="'+route(k)+'"'+(' aria-current="page"' if k==key else '')+'>'+label+'</a>' for k,label in [('home','Home'),('services','Services'),('about','About'),('feedback','Client Feedback'),('insights','Insights'),('contact','Contact')])
 links=''.join('<a href="'+route(k)+'">'+{'services':'Services','about':'About','feedback':'Client Feedback','insights':'Insights','contact':'Contact','enquiry':'Free book assessment'}[k]+'</a>' for k in ['services','about','feedback','insights','contact','enquiry'])
 s=s.replace("' . $nav . $cta . '",nav+'<a class="gp-button" href="/book-marketing-audit/?request=assessment#gpc-enquiry">Free book assessment</a>').replace("' . $nav . '",nav).replace("' . $cta . '",'<a class="gp-button" href="/book-marketing-audit/?request=assessment#gpc-enquiry">Free book assessment</a>').replace("' . $links . '",links)
 s=re.sub(r"' \. esc_url\(gpc_url\('([^']+)'\)(?: \. '([^']*)')?\) \. '",lambda m:route(m[1])+(m[2] or ''),s)
 s=re.sub(r"' \. esc_url\(gpc_asset_url\('([^']+)'\)\) \. '",lambda m:'/assets/'+m[1],s)
 s=s.replace("' . gpc_contact_links('gp-footer-links') . '",'<div class="gp-footer-links"><a href="mailto:hello@grantpublishingco.com">hello@grantpublishingco.com</a><a href="https://wa.me/2349036016020">WhatsApp: +2349036016020</a><a href="https://www.linkedin.com/in/abdulqudustella/" target="_blank" rel="noopener noreferrer">LinkedIn</a><a href="https://www.upwork.com/freelancers/abdulqudust" target="_blank" rel="noopener noreferrer">Upwork</a></div>').replace("' . esc_html(wp_date('Y')) . '",'2026')
 return s
php=(root/'grant-publishing-site.php').read_text();form=php.split('    <form class=')[1].split('    </form>')[0];form='    <form class='+form+'    </form>'
services=re.findall("'([^']+)'",php.split('return array(')[1].split(');')[0])
form=re.sub(r'<\?php foreach[\s\S]*?\?>',''.join('<option>'+html.escape(x)+'</option>' for x in services),form)
form=form.replace('<?php echo esc_url(admin_url(\'admin-post.php\')); ?>','/mock-enquiry').replace('<?php echo esc_attr($contact[\'email\']); ?>','hello@grantpublishingco.com').replace('<?php echo esc_attr($contact[\'whatsapp\']); ?>','2349036016020').replace('<?php echo esc_html($contact[\'email\']); ?>','hello@grantpublishingco.com')
form=re.sub(r'<\?php[\s\S]*?\?>','',form);form=form.replace('type="submit"></button>','type="submit">Send project enquiry</button>')
def native(nodes):
 s=''
 for n in nodes:
  st=n['settings'];cls=st.get('css_classes',st.get('_css_classes',''));ident=' id="'+st['_element_id']+'"' if st.get('_element_id') else ''
  if n['elType']=='container':s+='<div class="e-con '+cls+'"'+ident+'>'+native(n['elements'])+'</div>';continue
  t=n['widgetType'];content=''
  if t=='heading':
   title=st['title'];link=st.get('link',{}).get('url');title='<a href="'+link+'">'+title+'</a>' if link else title;content='<'+st['header_size']+'>'+title+'</'+st['header_size']+'>'
  elif t=='text-editor':content=st.get('editor','')
  elif t=='button':content='<a class="elementor-button" href="'+st['link']['url']+'"'+(' target="_blank" rel="noopener noreferrer"' if st['link'].get('is_external') else '')+'><span class="elementor-button-text">'+st['text']+'</span></a>'
  elif t=='image':
   content='<img src="'+st['image']['url']+'" alt="'+html.escape(st.get('image',{}).get('alt',st.get('alt','Image')))+'">'
   if st.get('link_to')=='custom':
    l=st['link'];content='<a href="'+l['url']+'"'+(' target="_blank" rel="noopener noreferrer"' if l.get('is_external') else '')+'>'+content+'</a>'
  elif t=='shortcode':content=(current_shared(shared['form_assessment']) if variant=='after' and 'assessment' in st['shortcode'] else (form.replace('value="assessment"','value="assessment" selected') if 'assessment' in st['shortcode'] else form)) if 'enquiry_form' in st['shortcode'] else '<div class="gp-contact-links-list"><a href="mailto:hello@grantpublishingco.com">hello@grantpublishingco.com</a><a href="https://wa.me/2349036016020">WhatsApp: +2349036016020</a><a href="https://www.linkedin.com/in/abdulqudustella/" target="_blank" rel="noopener noreferrer">LinkedIn</a><a href="https://www.upwork.com/freelancers/abdulqudust" target="_blank" rel="noopener noreferrer">Upwork</a></div>'
  s+='<div class="elementor-widget '+cls+'"'+ident+'><div class="elementor-widget-container">'+content+'</div></div>'
 return s
for variant,src in [('after',root),('before',Path('/workspace/grant-qa/baseline-4.1/grant-publishing-site'))]:
 form_php=(src/'grant-publishing-site.php').read_text();baseform='    <form class='+form_php.split('    <form class=')[1].split('    </form>')[0]+'    </form>'
 baseform=re.sub(r'<\?php foreach[\s\S]*?\?>',''.join('<option>'+html.escape(x)+'</option>' for x in services),baseform)
 baseform=baseform.replace("<?php echo esc_url(admin_url('admin-post.php')); ?>",'/mock-enquiry').replace("<?php echo esc_attr($contact['email']); ?>",'hello@grantpublishingco.com').replace("<?php echo esc_attr($contact['whatsapp']); ?>",'2349036016020').replace("<?php echo esc_html($contact['email']); ?>",'hello@grantpublishingco.com')
 form=re.sub(r'<\?php[\s\S]*?\?>','',baseform).replace('type="submit"></button>','type="submit">Send project enquiry</button>')
 dest=out/variant;dest.mkdir(exist_ok=True);
 if (dest/'assets').exists():shutil.rmtree(dest/'assets')
 shutil.copytree(src/'assets',dest/'assets',dirs_exist_ok=True)
 if variant=='after':form=current_shared(shared['form_project'])
 for k in defs:
  for mode in ['fallback','native']:
   if mode=='fallback':body=(src/'templates'/f'{k}.html').read_text().replace('{{header}}',dynamic(get_return('gpc_header',src),k)).replace('{{footer}}',dynamic(get_return('gpc_footer',src),k))
   else:body='<div class="gpc-site">'+dynamic(get_return('gpc_header',src),k)+'<main id="gpc-main"><div class="elementor elementor-fixture">'+native(json.loads((src/'elementor'/f'{k}.json').read_text())['content'])+'</div></main>'+dynamic(get_return('gpc_footer',src),k)+'</div>'
   if variant=='after':
    body=body.replace(dynamic(get_return('gpc_header',src),k),current_shared(shared['headers'][k])).replace(dynamic(get_return('gpc_footer',src),k),current_shared(shared['footer']))
   body=re.sub(r'\{\{form:(assessment|project)\}\}',lambda m:current_shared(shared['form_'+('assessment' if m[1]=='assessment' else 'project')]) if variant=='after' else (form.replace('value="assessment"','value="assessment" selected') if m[1]=='assessment' else form),body).replace('{{contact_links}}','<div class="gp-contact-links-list"><a href="mailto:hello@grantpublishingco.com">hello@grantpublishingco.com</a><a href="https://wa.me/2349036016020">WhatsApp: +2349036016020</a><a href="https://www.linkedin.com/in/abdulqudustella/" target="_blank" rel="noopener noreferrer">LinkedIn</a><a href="https://www.upwork.com/freelancers/abdulqudust" target="_blank" rel="noopener noreferrer">Upwork</a></div>')
   if variant=='after':
    body=re.sub(r'<div class="gp-contact-links-list">.*?</div>',current_shared(shared['contacts']),body)
   body=resolve(body).replace('/assets/',f'/{variant}/assets/')
   design_link='<link rel="stylesheet" href="/'+variant+'/assets/design.css">' if (src/'assets/design.css').exists() else ''
   (dest/f'{k}-{mode}.html').write_text('<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'+defs[k]['title']+'</title><link rel="stylesheet" href="/'+variant+'/assets/site.css">'+design_link+'<style>body{margin:0}</style>'+body+'<script src="/'+variant+'/assets/interactions.js"></script><script src="/'+variant+'/assets/enquiry.js"></script></html>')
print('Built 68 fixtures; after uses actual shared PHP header/footer/forms/contact markup with mocked WordPress APIs; native Elementor DOM approximated.')
