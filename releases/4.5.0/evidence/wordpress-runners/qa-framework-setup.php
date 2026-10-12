<?php
require __DIR__.'/wp-load.php';wp_set_current_user(1);
function qa_framework_snapshot() {
 $rows=array();foreach(gpc_pages() as $key=>$def){$p=gpc_find_page($key);$seo=array();foreach(get_post_meta($p->ID) as $k=>$v){if(strpos($k,'_yoast_wpseo_')===0)$seo[$k]=$v;}ksort($seo);$rows[$key]=array('id'=>$p->ID,'content'=>hash('sha256',$p->post_content),'elementor'=>hash('sha256',(string)get_post_meta($p->ID,'_elementor_data',true)),'seo'=>hash('sha256',serialize($seo)),'date'=>$p->post_date,'status'=>$p->post_status,'parent'=>$p->post_parent,'slug'=>$p->post_name);}
 $options=array();foreach(array('gpc_form_delivery','gpc_contact_details','gpc_legal_pages') as $k)$options[$k]=hash('sha256',serialize(get_option($k)));return array('pages'=>$rows,'settings'=>$options);
}
$before=qa_framework_snapshot();$cleanup=gpc_cleanup_default_content(true);$drafts=gpc_prepare_editorial_drafts(true);$after=qa_framework_snapshot();
$repeat=gpc_prepare_editorial_drafts(true);$data=array('version'=>GPC_VERSION,'unchanged_core_pages_and_settings'=>$before===$after,'before'=>$before,'after'=>$after,'cleanup_success'=>$cleanup,'cleanup_results'=>get_option('gpc_default_cleanup_results'),'draft_success'=>$drafts,'draft_repeat_success'=>$repeat,'draft_results'=>get_option('gpc_editorial_draft_results'),'drafts'=>array(),'all_routes'=>array());
foreach(get_option('gpc_editorial_draft_ids',array()) as $key=>$id){$p=get_post($id);$data['drafts'][$key]=array('id'=>$id,'status'=>$p->post_status,'parent'=>$p->post_parent,'url'=>get_permalink($id));}
foreach(gpc_pages() as $key=>$def)$data['all_routes'][$key]=gpc_url($key);
header('Content-Type: application/json');echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
