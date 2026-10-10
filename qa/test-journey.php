<?php
// Run the actual structure/redirect code against isolated WordPress interfaces.
define('ABSPATH', '/test/'); define('OBJECT', 'OBJECT'); define('GPC_VERSION', '4.4.0');
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
require dirname(__DIR__).'/grant-publishing-site/includes/case-studies.php';
require dirname(__DIR__).'/grant-publishing-site/includes/faq.php';
require dirname(__DIR__).'/grant-publishing-site/includes/journey.php';
function missing_evidence_fixture(){
 fixture(false);
 foreach(array('case-studies','case-grandfather','case-luma','faq') as $key){$id=$GLOBALS['options']['gpc_page_ids'][$key];unset($GLOBALS['posts'][$id],$GLOBALS['meta'][$id],$GLOBALS['options']['gpc_page_ids'][$key]);}
}
missing_evidence_fixture();$before=serialize(array($GLOBALS['posts'],$GLOBALS['meta']));$settings=$GLOBALS['options']['gpc_form_delivery'];
check(gpc_create_evidence_pages(),'Four requested pages created');
check(count($GLOBALS['insertions'])===4 && !$GLOBALS['updates'],'Only four missing pages inserted and no saved page is updated');
foreach(array('case-grandfather','case-luma') as $key)check(get_permalink(gpc_find_page($key)->ID)===home_url('/'.gpc_page_path($key).'/'),'Correct case-study parent and canonical route: '.$key);
$existing=array_diff_key($GLOBALS['posts'],array_flip($GLOBALS['insertions']));$preserved_meta=array_diff_key($GLOBALS['meta'],array_flip($GLOBALS['insertions']));
check(serialize(array($existing,$preserved_meta))===$before,'Saved content, Elementor data and all existing metadata preserved');
check($GLOBALS['options']['gpc_form_delivery']===$settings,'Provider configuration preserved');
foreach($GLOBALS['insertions'] as $id)check(!array_filter(array_keys($GLOBALS['meta'][$id]),function($key){return strpos($key,'_yoast')===0;}),'New page has no forced manual Yoast fields: '.$id);
$state=serialize(array($GLOBALS['posts'],$GLOBALS['meta'],$GLOBALS['options']));
check(!gpc_create_evidence_pages() && serialize(array($GLOBALS['posts'],$GLOBALS['meta'],$GLOBALS['options']))===$state,'Completed migration does not run again on dashboard visits');
check(gpc_create_evidence_pages(true) && count($GLOBALS['insertions'])===4,'Manual retry does not duplicate pages');
missing_evidence_fixture();$GLOBALS['can']=false;check(!gpc_create_evidence_pages() && !$GLOBALS['insertions'],'Public users cannot create pages');
missing_evidence_fixture();$GLOBALS['options']['gpc_evidence_pages_lock']=array('started_at'=>time(),'token'=>'other');check(!gpc_create_evidence_pages() && !$GLOBALS['insertions'],'Concurrent setup observes active lock');
missing_evidence_fixture();$GLOBALS['options']['gpc_evidence_pages_lock']=array('started_at'=>time()-301,'token'=>'interrupted');check(gpc_create_evidence_pages(),'Interrupted stale lock recovers');
missing_evidence_fixture();$GLOBALS['fail_insert']=true;check(!gpc_create_evidence_pages() && !isset($GLOBALS['options']['gpc_evidence_pages_lock']) && !isset($GLOBALS['options']['gpc_evidence_pages_version']),'Failed creation retains retry state and releases lock');
$GLOBALS['fail_insert']=false;check(gpc_create_evidence_pages(),'Failed creation can be retried');
missing_evidence_fixture();$GLOBALS['posts'][99]=(object)array('ID'=>99,'post_type'=>'page','post_status'=>'draft','post_name'=>'case-studies','post_parent'=>0,'post_content'=>'My unpublished editorial content');
check(!gpc_create_evidence_pages() && get_post(99)->post_status==='draft' && get_post(99)->post_content==='My unpublished editorial content' && count($GLOBALS['insertions'])===1,'Existing draft hub preserved; children wait and FAQ can be created');
missing_evidence_fixture();$GLOBALS['posts'][99]=(object)array('ID'=>99,'post_type'=>'page','post_status'=>'publish','post_name'=>'case-studies','post_parent'=>0,'post_content'=>'Unrelated published work');
check(!gpc_create_evidence_pages() && get_post(99)->post_content==='Unrelated published work' && count($GLOBALS['insertions'])===1,'Unrelated canonical hub is not overwritten or duplicated');
fixture(false);
$contracts=array(
 'amazon-visibility'=>array('book-strategy','amazon-ads','case-grandfather'),
 'book-presentation'=>array('amazon-visibility','cover-design','case-grandfather'),
 'book-formatting'=>array('publishing-support','cover-design','case-luma'),
 'cover-design'=>array('book-formatting','publishing-support','case-luma'),
 'amazon-ads'=>array('amazon-visibility','book-strategy'),
 'launch-promotion'=>array('amazon-visibility','amazon-ads','book-strategy','author-platform'),
 'author-platform'=>array('book-strategy','launch-promotion'),
 'book-strategy'=>array('amazon-visibility','amazon-ads','launch-promotion','author-platform'),
 'publishing-support'=>array('book-formatting','cover-design','amazon-visibility','case-luma'),
 'catalog-strategy'=>array('author-platform','book-strategy','launch-promotion'),
);
foreach($contracts as $key=>$targets){$html=gpc_service_journey($key);foreach(array_merge($targets,array('enquiry')) as $target)check(strpos($html,esc_url(gpc_url($target)))!==false,'Required next step: '.$key.' to '.$target);}
check(strpos(gpc_service_journey('amazon-ads'),'gp-work-card')===false,'No unsupported advertising project is presented as proof');
check(strpos(gpc_service_journey('publishing-support'),'KDP upload and publication were not part')!==false,'Publishing proof explains Luma file-delivery boundary');
check(substr_count(gpc_faq_group(),'<details')===13 && substr_count(gpc_faq_group(),'<summary>')===13,'All thirteen FAQs use native keyboard-accessible disclosures');
check(substr_count(gpc_faq_group(array('management','spend')),'<details')===2,'Advertising FAQ is short and scoped');
check(strpos(gpc_faq_group(array('spend')),'separate from our professional service fee')!==false,'Ad spend remains separate');
foreach(array('book-discovery','book-product-page','connected-catalog') as $key){$old=gpc_resolve_string(file_get_contents(dirname(__DIR__).'/grant-publishing-site/templates/'.$key.'.html'),true);$new=gpc_article_connections($old,$key);check(strpos($new,'data-gpc-article-links')!==false,'Contextual links added to '.$key);check(gpc_article_connections($new,$key)===$new,'Article links idempotent: '.$key);}
$custom='<main><h1>Edited Insight</h1><p>My approved revised article wording.</p></main>';
check(strpos(gpc_article_connections($custom,'book-discovery'),'My approved revised article wording.')!==false,'Edited article copy is preserved while related links are added separately');
$source='<div class="gpc-site"><main><p>Approved copy</p><div class="gp-section gp-cta"><p>CTA</p></div></main><footer>Footer</footer></div>';
$inserted=gpc_insert_journey($source,'<section>Proof</section>');check(strpos($inserted,'<section>Proof</section><div class="gp-section gp-cta">')!==false,'Proof sits before final CTA inside the existing main');
$original='<p><a href="https://grantpublishingco.com/wp-content/uploads/2026/10/My-Dear-Grandfather-Listing-Case-Study.pdf" target="_blank">Original PDF button</a></p>';
$new=gpc_feedback_case_links($original);check(strpos($new,'Original PDF button')!==false && strpos($new,'case-studies/my-dear-grandfather')!==false,'Feedback retains PDF and gains HTML case destination');
check(gpc_feedback_case_links($new)===$new,'Feedback links idempotent');
$GLOBALS['is_admin']=true;check(gpc_render_journey($source)===$source,'Editor and dashboard content are unaffected');$GLOBALS['is_admin']=false;
$GLOBALS['queried_id']=999;check(gpc_render_journey($source)===$source,'Secondary loop content is unaffected');$GLOBALS['queried_id']=9;
$GLOBALS['posts'][9]->post_content='[grant_terms_of_service]';$GLOBALS['meta'][9]['_gpc_page_key']='terms-of-service';check(gpc_render_journey($source)===$source,'Legal-page body is unchanged');
$GLOBALS['posts'][9]->post_content='[grant_amazon_visibility]';$GLOBALS['meta'][9]['_gpc_page_key']='amazon-visibility';$new=gpc_render_journey($source);check(strpos($new,'data-gpc-journey="amazon-visibility"')!==false,'Connected Canvas or native page can receive proof');
check(gpc_render_journey($new)===$new,'Public service enhancement is idempotent');
$GLOBALS['meta'][9]['_yoast_wpseo_metadesc']='Manual description';check(gpc_seo_description('Expanded manual description')==='Expanded manual description','Yoast manual description overrides page fallback');
$GLOBALS['posts'][9]->post_content='[grant_case_grandfather]';$GLOBALS['meta'][9]['_gpc_page_key']='case-grandfather';
check(gpc_case_social_description('', 'opengraph-description')===gpc_pages()['case-grandfather']['description'], 'New case study has a social-description fallback');
check(gpc_case_social_description('Approved social description', 'opengraph-description')==='Approved social description', 'Existing social description remains authoritative');
$GLOBALS['meta'][9]['_yoast_wpseo_opengraph-description']='Manual social description';
check(gpc_case_social_description('', 'opengraph-description')==='', 'Explicit manual social field is never replaced even when its incoming rendering is empty');
define('WPSEO_VERSION','test');check(gpc_document_title('Manual Yoast title')==='Manual Yoast title','Yoast owns the final title');
echo "\n".$GLOBALS['checks']." journey assertions passed. No production writes or messages occurred.\n";
