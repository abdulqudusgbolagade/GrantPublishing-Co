<?php
// Run the actual structure/redirect code against isolated WordPress interfaces.
define('ABSPATH', '/test/'); define('OBJECT', 'OBJECT'); define('GPC_VERSION', '4.5.0');
class WP_Error {}
$GLOBALS['hooks'] = array(); $GLOBALS['checks'] = 0;
function add_action(...$args) { $GLOBALS['hooks'][] = $args; if($args[0]==='wp_head' && is_string($args[1]))$GLOBALS['head_actions'][$args[1]]=$args[2]??10; }
function add_filter(...$args) {}
function add_shortcode(...$args) {}
function get_option($key,$default=false) { return $GLOBALS['options'][$key] ?? $default; }
function update_option($key,$value,$autoload=null) { if (($GLOBALS['fail_option'] ?? '') === $key) return false; $GLOBALS['options'][$key]=$value; return true; }
function add_option($key,$value,...$args) { if (isset($GLOBALS['options'][$key])) return false; $GLOBALS['options'][$key]=$value; return true; }
function delete_option($key) { unset($GLOBALS['options'][$key]); return true; }
function current_user_can($cap) { return $GLOBALS['can']; }
function wp_generate_uuid4() { return 'isolated-lock'; }
function get_post($id=null) { return $GLOBALS['posts'][$id ?? ($GLOBALS['loop_id'] ?? 9)] ?? null; }
function get_post_meta($id,$key,$single=false) { return $GLOBALS['meta'][$id][$key] ?? ''; }
function get_posts($args) { $found=array_filter($GLOBALS['posts'],function($p) use($args){return (!isset($args['name']) || $p->post_name===$args['name']) && (!isset($args['post_type']) || $p->post_type===$args['post_type']) && (!isset($args['post_status']) || in_array($p->post_status,(array)$args['post_status'],true)) && (!isset($args['author']) || ($p->post_author??0)===$args['author']) && (!isset($args['cat']) || in_array($args['cat'],$GLOBALS['categories'][$p->ID]??array(),true));}); return ($args['fields']??'')==='ids'?array_keys($found):array_values($found); }
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
function wp_update_post($data,$error=false){if($GLOBALS['fail_update'])return new WP_Error();$GLOBALS['updates'][]=$data;foreach($data as $key=>$value){if($key!=='ID')$GLOBALS['posts'][$data['ID']]->$key=$value;}return $data['ID'];}
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

function add_query_arg($args,$url){return $url.'?'.http_build_query($args);}
function plugins_url($path,$file){return home_url('/plugin/'.$path);}
function esc_attr($text){return htmlspecialchars($text,ENT_QUOTES,'UTF-8');}
function wp_strip_all_tags($s){return strip_tags($s);}
function is_admin(){return $GLOBALS['is_admin'] ?? false;}
function is_feed(){return false;}
function is_preview(){return $GLOBALS['is_preview'] ?? false;}
function is_main_query(){return $GLOBALS['is_main'] ?? true;}
function in_the_loop(){return true;}
function get_queried_object_id(){return $GLOBALS['queried_id'] ?? 9;}
require dirname(__DIR__).'/grant-publishing-site/includes/portfolio.php';
require dirname(__DIR__) . '/grant-publishing-site/includes/formatting-samples.php';
require dirname(__DIR__).'/grant-publishing-site/includes/case-studies.php';
require dirname(__DIR__).'/grant-publishing-site/includes/faq.php';
require dirname(__DIR__).'/grant-publishing-site/includes/journey.php';
require dirname(__DIR__).'/grant-publishing-site/includes/framework.php';
require dirname(__DIR__).'/grant-publishing-site/includes/production-audit.php';
function missing_evidence_fixture(){
 fixture(false);
 foreach(array('case-studies','case-grandfather','case-luma','faq') as $key){$id=$GLOBALS['options']['gpc_page_ids'][$key];unset($GLOBALS['posts'][$id],$GLOBALS['meta'][$id],$GLOBALS['options']['gpc_page_ids'][$key]);}
}

function remove_action($hook,$callback,$priority=10){if($hook==='wp_head')unset($GLOBALS['head_actions'][$callback]);}
function current_theme_supports($feature){return $GLOBALS['title_support']??false;}
function add_theme_support($feature){$GLOBALS['title_support']=true;}
function get_comments($args){return array_values($GLOBALS['comments']??array());}
function get_comment($id){return $GLOBALS['comments'][$id];}
function get_post_thumbnail_id($id){return 0;}
function wp_get_post_categories($id){return $GLOBALS['categories'][$id]??array();}
function get_term($id,$taxonomy){return (object)array('slug'=>'uncategorized');}
function current_time($format){return '2026-10-12 12:00:00';}
function wp_set_comment_status($id,$status){$GLOBALS['comments'][$id]->comment_approved=$status==='hold'?'0':'1';return true;}
function get_post_time($format,$gmt,$post){return $format==='U'?1791374400:'2026-10-07T12:00:00+00:00';}
function wp_date($format,$stamp=null){return '7 October 2026';}
require dirname(__DIR__).'/grant-publishing-site/includes/drafts.php';
fixture(false);
$exact=array('G'=>'Ground the book in the right market.','R'=>'Refine the positioning.','A'=>'Align the listing and presentation.','N'=>'Navigate visibility channels.','T'=>'Track growth and improve performance.');
foreach($exact as $letter=>$name)check(gpc_framework_stages()[$letter]['name']===$name,'Exact stage name: '.$letter);
foreach(array('home','about') as $key){$grid=gpc_framework_grid($key);foreach($exact as $name)check(substr_count($grid,$name)===1,'One exact stage name in '.$key.': '.$name);check(substr_count($grid,'gp-step gp-framework-card')===3,'Existing three-column grouping retained on '.$key);check(strpos($grid,'Ongoing monitoring is not included with every service.')!==false,'Monitoring scope explicit on '.$key);}
$old='<div class="gp-section" id="process"><div class="gp-wrap"><h2>Understand first.</h2><div class="gp-three-columns"><div class="gp-step">Old process</div></div></div></div><div class="gp-section">Unrelated approved section</div>';
$new=gpc_framework_process($old,'home');check(strpos($new,'data-gpc-framework="home"')!==false && substr_count($new,'id="process"')===1,'Home framework replaces the existing process in place');check(strpos($new,'Unrelated approved section')!==false,'Unrelated homepage section preserved');check(gpc_framework_process($new,'home')===$new,'Home method replacement idempotent');
$old='<div class="gp-section"><h2>Meet the founder</h2><p>Approved portrait and copy</p></div><div class="gp-section"><div class="gp-wrap"><h2>How we approach the work</h2><div class="gp-three-columns"><div>Old approach</div></div></div></div>';
$new=gpc_framework_process($old,'about');check(strpos($new,'id="grant-framework"')!==false && strpos($new,'Approved portrait and copy')!==false,'About framework is linkable and founder content stays intact');check(gpc_framework_process($new,'about')===$new,'About method replacement idempotent');
foreach(array('case-luma'=>array('A'),'case-grandfather'=>array('R','A')) as $key=>$letters){$html=file_get_contents(dirname(__DIR__).'/grant-publishing-site/templates/'.$key.'.html');$rendered=gpc_framework_case($html,$key);foreach($letters as $letter)check(strpos($rendered,$exact[$letter])!==false,'Supported framework stage on '.$key.': '.$letter);check(strpos($rendered,$exact['T'])===false,'No tracked-growth stage claimed for '.$key);check(gpc_framework_case($rendered,$key)===$rendered,'Case method connection idempotent: '.$key);}
foreach(array('book-formatting','cover-design','publishing-support') as $key){$cta=gpc_service_project_cta($key);check(strpos($cta,'/contact/')!==false && strpos($cta,'request=project')!==false,'Production closing enquiry reaches Contact: '.$key);}
check(strpos(gpc_framework_assessment(),'not a complete five-stage engagement')!==false && strpos(gpc_framework_assessment(),'private sales data')!==false,'Assessment limitations are explicit');
check(strpos(gpc_aplus_example(),'kathryns-beach-aplus-600.webp')!==false && strpos(gpc_aplus_example(),'without claiming improved sales')!==false,'Approved A+ evidence has restrained scope');
$seo=serialize($GLOBALS['meta']);$GLOBALS['title_support']=true;$GLOBALS['head_actions']=array('_block_template_render_title_tag'=>1,'_wp_render_title_tag'=>1);define('WPSEO_VERSION','test');
$path=dirname(__DIR__).'/grant-publishing-site/includes/full-page.php';check(gpc_prepare_title_output($path)===$path && !$GLOBALS['head_actions'],'Obsolete core title output is removed when Yoast owns the head');check(serialize($GLOBALS['meta'])===$seo,'Title integration leaves all saved SEO fields untouched');
$GLOBALS['title_support']=false;$GLOBALS['head_actions']=array('_block_template_render_title_tag'=>1);$canvas='/wordpress/wp-content/plugins/elementor/modules/page-templates/templates/canvas.php';check(gpc_prepare_title_output($canvas)===$canvas && !$GLOBALS['head_actions'],'Late-removed support uses one Canvas fallback, without duplicate block output');
$GLOBALS['head_actions']=array();gpc_prepare_title_output($path);check(isset($GLOBALS['head_actions']['_block_template_render_title_tag']),'A custom shell without title capability retains one core fallback');
function starter_fixture(){fixture(false);$GLOBALS['options']['default_category']=1;$GLOBALS['categories']=array(99=>array(1));$GLOBALS['posts'][99]=(object)array('ID'=>99,'post_type'=>'post','post_status'=>'publish','post_name'=>'hello-world','post_title'=>'Hello world!','post_parent'=>0,'post_author'=>1,'post_password'=>'','post_content'=>'<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>');$GLOBALS['comments']=array(1=>(object)array('comment_ID'=>1,'comment_author'=>'A WordPress Commenter','comment_author_url'=>'https://wordpress.org/','comment_content'=>'Hi, this is a comment. To get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard. Commenter avatars come from Gravatar.','comment_approved'=>'1'));}
starter_fixture();$before=get_post(99)->post_content;check(gpc_cleanup_default_content(),'Untouched WordPress defaults are cleaned');check(get_post(99)->post_status==='draft' && get_post(99)->post_content===$before,'Starter post drafted without rewriting content');check($GLOBALS['comments'][1]->comment_approved==='0','Only the sample comment is held');check(isset($GLOBALS['options']['gpc_default_content_backup']),'Original starter state backed up before mutation');check(gpc_default_archive_unused('author',1)&&gpc_default_archive_unused('category',1),'Associated empty archives identified');
$GLOBALS['posts'][100]=(object)array('ID'=>100,'post_type'=>'post','post_status'=>'publish','post_name'=>'genuine-post','post_parent'=>0,'post_author'=>1,'post_content'=>'Real work');$GLOBALS['categories'][100]=array(1);check(!gpc_default_archive_unused('author',1)&&!gpc_default_archive_unused('category',1),'Archive exclusions automatically stop when genuine posts exist');
starter_fixture();$GLOBALS['posts'][99]->post_content='My edited welcome article.';check(!gpc_cleanup_default_content() && get_post(99)->post_status==='publish','Edited welcome article preserved');
starter_fixture();$GLOBALS['comments'][2]=(object)array('comment_ID'=>2,'comment_author'=>'Reader','comment_author_url'=>'','comment_content'=>'A real discussion','comment_approved'=>'1');check(!gpc_cleanup_default_content() && get_post(99)->post_status==='publish','Genuine discussion prevents automatic cleanup');
starter_fixture();$GLOBALS['can']=false;check(!gpc_cleanup_default_content() && get_post(99)->post_status==='publish','Public users cannot change default content');
starter_fixture();$GLOBALS['options']['gpc_default_cleanup_lock']=array('started_at'=>time(),'token'=>'other');check(!gpc_cleanup_default_content() && get_post(99)->post_status==='publish','Concurrent default cleanup respects active lock');
starter_fixture();$GLOBALS['fail_update']=true;check(!gpc_cleanup_default_content() && get_post(99)->post_status==='publish' && isset($GLOBALS['options']['gpc_default_content_backup']),'Failed mutation keeps original content and its backup');
fixture(false);$before=serialize(array($GLOBALS['posts'],$GLOBALS['meta']));check(gpc_prepare_editorial_drafts(),'Two editorial drafts prepared');check(count($GLOBALS['insertions'])===2,'Only the two requested drafts inserted');foreach($GLOBALS['insertions'] as $id){$p=get_post($id);check($p->post_status==='draft' && $p->post_parent===gpc_find_page('insights')->ID,'New authority article stays unpublished under Insights: '.$id);}
$oldposts=array_diff_key($GLOBALS['posts'],array_flip($GLOBALS['insertions']));$oldmeta=array_diff_key($GLOBALS['meta'],array_flip($GLOBALS['insertions']));check(serialize(array($oldposts,$oldmeta))===$before,'Existing core content and metadata preserved by draft creation');check(gpc_prepare_editorial_drafts(true)&&count($GLOBALS['insertions'])===2,'Repeated draft preparation does not duplicate or overwrite');
$GLOBALS['queried_id']=gpc_find_page('insights')->ID;
$GLOBALS['loop_id']=$GLOBALS['queried_id'];
$index='<div class="gp-insight-index"><div>Existing approved article</div></div>';
check(gpc_index_approved_editorial($index)===$index,'Unpublished drafts are absent from the public Insights index');
$id=$GLOBALS['insertions'][0];$GLOBALS['posts'][$id]->post_status='publish';
$indexed=gpc_index_approved_editorial($index);
check(substr_count($indexed,'data-gpc-approved-insight')===1 && strpos($indexed,'Existing approved article')!==false,'Only an owner-published article joins the existing index');
check(gpc_index_approved_editorial($indexed)===$indexed,'Published draft index row is not duplicated');
$GLOBALS['meta'][$id]['_gpc_editorial_draft']='unrecognised';
check(gpc_index_approved_editorial($index)===$index,'Unverified page markers do not enter the public Insights index');
echo "\n".$GLOBALS['checks']." framework/audit assertions passed. No real request or message was sent.\n";
