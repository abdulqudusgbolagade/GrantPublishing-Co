<?php
define('ABSPATH','/isolated/');
class WP_Privacy_Policy_Content { static function get_default_content(){return 'Untouched WordPress starter policy';} }
class WP_Error {}
function add_action(...$args){}
function get_post_meta($id,$key,$single=false){return $GLOBALS['meta'][$key] ?? '';}
function metadata_exists($type,$id,$key){return isset($GLOBALS['meta'][$key]);}
function gpc_snapshot_page($id){return array('post_content'=>$GLOBALS['post']->post_content,'meta'=>array());}
function wp_slash($value){return $value;}
function add_post_meta($id,$key,$value,$unique=false){if($GLOBALS['fail_backup'])return false;$GLOBALS['meta'][$key]=$value;return true;}
function update_post_meta($id,$key,$value){$GLOBALS['meta'][$key]=$value;return true;}
function wp_update_post($data,$error=false){
 if($GLOBALS['fail_save'])return new WP_Error();
 $GLOBALS['writes']++;$GLOBALS['post']->post_status=$data['post_status'];$GLOBALS['post']->post_content=$data['post_content'];
 // Reproduce the actual WordPress existing-page template carry-forward.
 $GLOBALS['meta']=array_merge($GLOBALS['meta'],$data['meta_input']);$GLOBALS['meta']['_wp_page_template']='default';return $data['ID'];
}
function is_wp_error($v){return $v instanceof WP_Error;}
require dirname(__DIR__).'/grant-publishing-site/includes/legal.php';
function fixture(){
 $GLOBALS['post']=(object)array('ID'=>3,'post_type'=>'page','post_status'=>'draft','post_name'=>'privacy-policy','post_title'=>'Privacy Policy','post_content'=>WP_Privacy_Policy_Content::get_default_content());
 $GLOBALS['meta']=array();$GLOBALS['fail_backup']=false;$GLOBALS['fail_save']=false;$GLOBALS['writes']=0;
}
$count=0;
function check($condition,$label){global $count;if(!$condition)throw new Exception('FAIL: '.$label);$count++;echo 'PASS: '.$label."\n";}
fixture();check(gpc_publish_approved_default_policy(clone $GLOBALS['post']),'Untouched WordPress draft can publish the approved policy');
check($GLOBALS['post']->post_content==='[grant_privacy_policy]' && $GLOBALS['post']->post_status==='publish','Approved copy uses the maintained page template');
check($GLOBALS['meta']['_gpc_legal_backup']['post_content']===WP_Privacy_Policy_Content::get_default_content() && $GLOBALS['meta']['_gpc_legal_backup']['post_status']==='draft','Original default draft is backed up');
check($GLOBALS['meta']['_wp_page_template']==='gpc-full-page.php','Shared shell is assigned after the WP update to prevent duplicate headers and H1s');
check(!gpc_publish_approved_default_policy($GLOBALS['post']) && $GLOBALS['writes']===1,'Published legal content is not overwritten');
fixture();$GLOBALS['post']->post_content.=' Authored edit.';check(!gpc_publish_approved_default_policy($GLOBALS['post']) && !$GLOBALS['writes'],'An edited draft stays untouched');
fixture();$GLOBALS['meta']['_elementor_data']='Saved authored legal layout';check(!gpc_publish_approved_default_policy($GLOBALS['post']) && !$GLOBALS['writes'],'An authored Elementor policy stays untouched');
fixture();$GLOBALS['fail_backup']=true;check(!gpc_publish_approved_default_policy($GLOBALS['post']) && !$GLOBALS['writes'],'Backup failure blocks publication');
fixture();$GLOBALS['fail_save']=true;check(!gpc_publish_approved_default_policy(clone $GLOBALS['post']) && isset($GLOBALS['meta']['_gpc_legal_backup']) && !$GLOBALS['writes'],'Failed publication retains backup and original draft');
$GLOBALS['fail_save']=false;check(gpc_publish_approved_default_policy(clone $GLOBALS['post']),'Interrupted publication can retry using its original backup');
echo "\n".$count." legal assertions passed. No live policy changes occurred.\n";
