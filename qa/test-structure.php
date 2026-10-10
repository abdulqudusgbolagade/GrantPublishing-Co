<?php
// Run the actual structure/redirect code against isolated WordPress interfaces.
define('ABSPATH', '/test/'); define('OBJECT', 'OBJECT'); define('GPC_VERSION', '4.3.1');
class WP_Error {}
$GLOBALS['hooks'] = array(); $GLOBALS['checks'] = 0;
function add_action(...$args) { $GLOBALS['hooks'][] = $args; }
function add_filter(...$args) {}
function add_shortcode(...$args) {}
function get_option($key,$default=false) { return $GLOBALS['options'][$key] ?? $default; }
function update_option($key,$value,$autoload=null) { if (($GLOBALS['fail_option'] ?? '') === $key) return false; $GLOBALS['options'][$key]=$value; return true; }
function add_option($key,$value,...$args) { if (isset($GLOBALS['options'][$key])) return false; $GLOBALS['options'][$key]=$value; return true; }
function delete_option($key) { unset($GLOBALS['options'][$key]); return true; }
function current_user_can($cap) { return $GLOBALS['can']; }
function wp_generate_uuid4() { return 'isolated-lock'; }
function get_post($id=null) { return $GLOBALS['posts'][$id ?? 9] ?? null; }
function get_post_meta($id,$key,$single=false) { return $GLOBALS['meta'][$id][$key] ?? ''; }
function get_posts($args) { return array_values(array_filter($GLOBALS['posts'],function($p) use($args){return $p->post_name===$args['name'] && in_array($p->post_status,$args['post_status'],true);})); }
function has_shortcode($content,$tag) { return strpos($content,'['.$tag.']')!==false; }
function home_url($path='') { return $GLOBALS['base'].$path; }
function wp_parse_url($url,$component=-1) { return parse_url($url,$component); }
function get_permalink($id) {
    $p=get_post($id);if(!$p)return '';if($id===9)return home_url('/');
    return home_url('/'.($p->post_parent ? get_post($p->post_parent)->post_name.'/' : '').$p->post_name.'/');
}
function get_page_by_path($path,...$args) {
    foreach($GLOBALS['posts'] as $p){ if (trim(wp_parse_url(get_permalink($p->ID),PHP_URL_PATH),'/')===trim(wp_parse_url(home_url('/'.$path),PHP_URL_PATH),'/'))return $p; }return null;
}
function wp_insert_post($data,$error=false) {
    if($GLOBALS['fail_insert'])return new WP_Error();
    $id=max(array_keys($GLOBALS['posts']))+1;$data['ID']=$id;
    $GLOBALS['meta'][$id]=$data['meta_input'];unset($data['meta_input']);
    $GLOBALS['posts'][$id]=(object)$data;$GLOBALS['insertions'][]=$id;return $id;
}
function wp_update_post($data,$error=false) {
    if($GLOBALS['fail_update'])return new WP_Error();
    if(array_keys($data)!==array('ID','post_parent'))throw new Exception('Saved content must not be rewritten');
    $GLOBALS['updates'][]=$data;$GLOBALS['posts'][$data['ID']]->post_parent=$data['post_parent'];return $data['ID'];
}
function gpc_clear_elementor_page_cache($id) { $GLOBALS['cache'][]=$id; }
function do_action(...$args) { $GLOBALS['events'][]=$args; }
function is_wp_error($value) { return $value instanceof WP_Error; }
function esc_url($s) { return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }
function esc_html($s) { return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }
function is_singular(...$args) { return true; }
function gpc_publish_approved_default_policy($page) { return false; }
require dirname(__DIR__).'/grant-publishing-site/includes/pages.php';
require dirname(__DIR__).'/grant-publishing-site/includes/repairs.php';
function fixture($bad=true) {
    $GLOBALS['can']=true;$GLOBALS['base']='https://example.test';$GLOBALS['posts']=array();$GLOBALS['meta']=array();$GLOBALS['updates']=array();$GLOBALS['insertions']=array();$GLOBALS['cache']=array();$GLOBALS['events']=array();$GLOBALS['fail_insert']=false;$GLOBALS['fail_update']=false;$GLOBALS['fail_option']='';
    $GLOBALS['options']=array('gpc_page_ids'=>array(),'show_on_front'=>'page','page_on_front'=>9,'gpc_form_delivery'=>array('provider'=>'web3forms','access_key'=>'kept-private'),'gpc_contact_details'=>array('email'=>'kept@example.test'));
    $id=9;
    foreach(gpc_pages() as $key=>$def){
        if(in_array($key,array('book-formatting','cover-design','amazon-ads','privacy-policy','terms-of-service'),true))continue;
        if($key==='home')$id=9;else$id++;
        $parent=empty($def['parent'])?0:$GLOBALS['options']['gpc_page_ids'][$def['parent']];
        if($bad && $key==='connected-catalog')$parent=$GLOBALS['options']['gpc_page_ids']['services'];
        $GLOBALS['posts'][$id]=(object)array('ID'=>$id,'post_type'=>'page','post_status'=>'publish','post_name'=>$def['slug']?:'home','post_parent'=>$parent,'post_content'=>'['.$def['shortcode'].']');
        $GLOBALS['options']['gpc_page_ids'][$key]=$id;
        $GLOBALS['meta'][$id]=array('_gpc_page_key'=>$key,'_gpc_elementor_layout'=>'4.3.0','_elementor_data'=>'Saved custom Elementor content');
    }
}
function check($value,$label){if(!$value)throw new Exception('FAIL: '.$label);$GLOBALS['checks']++;echo 'PASS: '.$label."\n";}
fixture();$old=serialize($GLOBALS['meta']);$delivery=$GLOBALS['options']['gpc_form_delivery'];$before=array_map(function($p){return $p->post_content;},$GLOBALS['posts']);
check(gpc_secure_site_resource('http://example.test/wp-content/uploads/elementor/css/base-desktop.css?ver=1') === 'https://example.test/wp-content/uploads/elementor/css/base-desktop.css?ver=1', 'Same-host legacy Elementor stylesheet uses HTTPS on a connected HTTPS page');
check(gpc_secure_site_resource('http://external.test/custom.css') === 'http://external.test/custom.css', 'External stylesheet hosts are not rewritten');
$GLOBALS['base']='http://example.test';
check(gpc_secure_site_resource('http://example.test/custom.css') === 'http://example.test/custom.css', 'An HTTP development installation keeps its resource scheme');
$GLOBALS['base']='https://example.test';
check(gpc_seo_description('') === gpc_pages()['home']['description'], 'Missing SEO descriptions use the approved page metadata');
check(gpc_seo_description('Uncover strategies employed by a Book Marketing specialist to boost your book\'s popularity') === 'Uncover strategies employed by a Book Marketing specialist to boost your book\'s popularity', 'Nonempty Yoast descriptions remain authoritative, including earlier stock wording');
check(gpc_seo_description('My approved custom description.') === 'My approved custom description.', 'Authored SEO descriptions are preserved');
check(gpc_repair_page_structure(),'Repair completes');
check(count($GLOBALS['insertions'])===5,'Only three missing services and both approved legal pages are created');
check(count($GLOBALS['updates'])===1,'Only the wrongly parented article is moved');
$article=gpc_find_page('connected-catalog');
check(get_permalink($article->ID)==='https://example.test/insights/connected-catalog/','Article has the correct permalink');
foreach($before as $id=>$content){check(get_post($id)->post_content===$content && $GLOBALS['meta'][$id]['_elementor_data']==='Saved custom Elementor content','Existing content and native data preserved for page '.$id);}
check($GLOBALS['options']['gpc_form_delivery']===$delivery,'Private delivery settings preserved');
check(isset($GLOBALS['options']['gpc_structure_route_backups'][$article->ID]),'Original parent and route are backed up');
check(gpc_structure_redirect_url('/services/connected-catalog/')==='https://example.test/insights/connected-catalog/','Incorrect public URL has a permanent redirect target');
check(gpc_structure_redirect_url('/services/connected-catalog/?utm_source=test')==='https://example.test/insights/connected-catalog/?utm_source=test','Redirect keeps attribution query');
check(gpc_structure_redirect_url('/insights/connected-catalog/')==='','Canonical article never redirects back');
foreach(array('book-discovery','book-product-page') as $key)check(gpc_structure_redirect_url('/services/'.$key.'/')===home_url('/insights/'.$key.'/'),'Other editorial service aliases stay under Insights');
check(gpc_structure_redirect_url('/services/amazon-visibility/')==='','A working service is never redirected as editorial content');
check(gpc_repair_link_url('https://other.test/services/connected-catalog/')==='https://other.test/services/connected-catalog/','External links are preserved');
check(gpc_repair_link_url('https://example.test/services/connected-catalog/?ref=1#reading')==='https://example.test/insights/connected-catalog/?ref=1#reading','Saved links keep their query and fragment');
$source='<a class="saved" href="https://example.test/services/connected-catalog/">Approved article title</a>';$new=gpc_repair_rendered_links($source);
check(strpos($new,'href="https://example.test/insights/connected-catalog/"')!==false && strpos($new,'Approved article title')!==false,'Rendered link repair preserves authored text and attributes');
check(gpc_repair_rendered_links($new)===$new,'Link correction is idempotent');
$new_tab = '<a target="_blank" href="https://example.test/book.pdf">Read the case study</a>';
$protected = gpc_protect_new_tab_links($new_tab);
check(strpos($protected, 'rel="noopener noreferrer"') !== false && strpos($protected, 'Read the case study') !== false, 'Native new-tab buttons gain protection without changing their destination or wording');
check(gpc_protect_new_tab_links($protected) === $protected, 'New-tab protection is idempotent');
check(strpos(gpc_protect_new_tab_links('<a target="_blank" rel="nofollow" href="https://other.test/">Original</a>'), 'rel="nofollow noopener noreferrer"') !== false, 'Existing authored relationship tokens remain intact');
$state=serialize(array($GLOBALS['options'],$GLOBALS['posts']));
check(!gpc_repair_page_structure() && serialize(array($GLOBALS['options'],$GLOBALS['posts']))===$state,'Completed version does no repeated writes');
check(gpc_repair_page_structure(true) && count($GLOBALS['insertions'])===5 && count($GLOBALS['updates'])===1,'Manual retry never duplicates pages or moves');
fixture();$GLOBALS['can']=false;check(!gpc_repair_page_structure() && !$GLOBALS['insertions'] && !$GLOBALS['updates'],'Unauthorised and public users do not mutate pages');
fixture();$GLOBALS['options']['gpc_structure_repair_lock']=array('started_at'=>time(),'token'=>'other');check(!gpc_repair_page_structure() && !$GLOBALS['insertions'],'Concurrent repair respects active lock');
fixture();$GLOBALS['options']['gpc_structure_repair_lock']=array('started_at'=>time()-301,'token'=>'old');check(gpc_repair_page_structure(),'Stale interrupted lock can recover');
fixture();$GLOBALS['fail_update']=true;check(!gpc_repair_page_structure() && !isset($GLOBALS['options']['gpc_structure_repaired_version']) && !isset($GLOBALS['options']['gpc_structure_repair_lock']),'Failed update retains retry state and releases lock');
check(gpc_structure_redirect_url('/services/connected-catalog/')==='','No redirect loop is introduced when the move fails');
$GLOBALS['fail_update']=false;check(gpc_repair_page_structure() && count($GLOBALS['insertions'])===5,'Failed move retries without duplicate service pages');
fixture();$GLOBALS['fail_option']='gpc_structure_route_backups';check(!gpc_repair_page_structure() && !$GLOBALS['updates'],'Missing backup blocks the parent mutation');
fixture();$GLOBALS['fail_option']='gpc_structure_redirects';check(!gpc_repair_page_structure() && !$GLOBALS['updates'],'Failed redirect persistence blocks the parent mutation');
fixture();$GLOBALS['fail_insert']=true;check(!gpc_repair_page_structure() && !$GLOBALS['insertions'],'Failed page creation is reported for retry');
fixture();$id=$GLOBALS['options']['gpc_page_ids']['connected-catalog'];unset($GLOBALS['options']['gpc_page_ids']['connected-catalog']);check(gpc_repair_page_structure() && get_permalink($id)==='https://example.test/insights/connected-catalog/','Connected article can be recovered when its ID map is absent');
fixture();$GLOBALS['base']='https://example.test/subdir';check(gpc_repair_page_structure() && gpc_structure_redirect_url('/subdir/services/connected-catalog/')==='https://example.test/subdir/insights/connected-catalog/','Subdirectory installs keep their canonical route');
check(gpc_structure_redirect_url('/services/connected-catalog/')==='','Routes outside a subdirectory are left alone');
fixture(false);check(gpc_repair_page_structure() && !$GLOBALS['updates'],'Already correct article parents need no changes');
fixture();$id=99;$GLOBALS['posts'][$id]=(object)array('ID'=>$id,'post_type'=>'page','post_status'=>'publish','post_name'=>'book-formatting','post_parent'=>$GLOBALS['options']['gpc_page_ids']['services'],'post_content'=>'Unrelated existing authored formatting page');
check(!gpc_repair_page_structure() && get_post($id)->post_content==='Unrelated existing authored formatting page' && count($GLOBALS['insertions'])===4,'An unrelated page occupying a requested service URL is preserved');
fixture();$id=99;$GLOBALS['posts'][$id]=(object)array('ID'=>$id,'post_type'=>'page','post_status'=>'draft','post_name'=>'book-formatting','post_parent'=>$GLOBALS['options']['gpc_page_ids']['services'],'post_content'=>'[grant_book_formatting]');
check(!gpc_repair_page_structure() && get_post($id)->post_status==='draft' && count($GLOBALS['insertions'])===4,'An existing draft service is neither duplicated nor published silently');
fixture();$parent=$GLOBALS['options']['gpc_page_ids']['services'];$GLOBALS['posts'][$parent]->post_content='Unrelated services page';$GLOBALS['meta'][$parent]=array();
check(!gpc_repair_page_structure() && count($GLOBALS['insertions'])===0,'A disconnected service parent blocks new service and legal creation');
fixture();$article_id=$GLOBALS['options']['gpc_page_ids']['connected-catalog'];$collision=99;$GLOBALS['posts'][$collision]=(object)array('ID'=>$collision,'post_type'=>'page','post_status'=>'publish','post_name'=>'connected-catalog','post_parent'=>$GLOBALS['options']['gpc_page_ids']['insights'],'post_content'=>'Authored canonical article');
check(!gpc_repair_page_structure() && !$GLOBALS['updates'] && get_post($collision)->post_content==='Authored canonical article','A canonical article collision blocks reparenting and preserves both pages');
fixture();check(gpc_legal_footer_links()==='','Absent legal pages produce no dead footer links');
gpc_repair_page_structure();$links=gpc_legal_footer_links();check(substr_count($links,'<a ')===2 && strpos($links,'privacy-policy')!==false && strpos($links,'terms-of-service')!==false,'Both approved published legal pages appear in one footer group');
$privacy=gpc_find_page('privacy-policy');$privacy->post_status='draft';check(substr_count(gpc_legal_footer_links(),'<a ')===1,'A draft legal page is not linked publicly');
$privacy->post_status='publish';$privacy->post_content='';check(substr_count(gpc_legal_footer_links(),'<a ')===1,'An empty published legal page is not linked');
require dirname(__DIR__).'/grant-publishing-site/includes/images.php';
function plugins_url($path,$file){return home_url('/plugin/'.$path);}
function esc_attr($text){return htmlspecialchars($text,ENT_QUOTES,'UTF-8');}
$image='<a href="https://www.amazon.com/dp/1947646168"><img src="'.gpc_asset_url('kathryns-beach.jpg').'" alt="Approved book title" loading="lazy"></a>';
$optimised=gpc_responsive_project_images($image);
check(strpos($optimised,'kathryns-beach-640.webp')!==false && strpos($optimised,'360w')!==false && strpos($optimised,'width="1287" height="2048"')!==false,'Verified book rendition gains responsive sources and reserved proportions');
check(strpos($optimised,'Approved book title')!==false && strpos($optimised,'https://www.amazon.com/dp/1947646168')!==false && strpos($optimised,'loading="lazy"')!==false,'Image optimisation keeps caption, retailer destination and lazy loading');
check(gpc_responsive_project_images($optimised)===$optimised,'Modern image rendering is idempotent');
$authored='<img src="https://other.test/kathryns-beach.jpg" alt="Custom artwork">';check(gpc_responsive_project_images($authored)===$authored,'Unrelated external artwork is untouched');
echo "\n".$GLOBALS['checks']." structure assertions passed. No live WordPress writes occurred.\n";
