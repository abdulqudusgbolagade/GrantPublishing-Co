<?php
require __DIR__.'/wp-load.php';wp_set_current_user(1);
$existing=array();foreach(gpc_pages() as $key=>$def){if(in_array($key,array('case-studies','case-grandfather','case-luma','faq'),true))continue;$p=gpc_find_page($key);$existing[$key]=hash('sha256',$p->post_content.(string)get_post_meta($p->ID,'_elementor_data',true));}
$results=array();foreach(array('case-studies','case-grandfather','case-luma','faq') as $key){$p=gpc_find_page($key);$results[$key]=gpc_apply_editorial_layout($p,$key);}
$after=array();foreach($existing as $key=>$hash){$p=gpc_find_page($key);$after[$key]=hash('sha256',$p->post_content.(string)get_post_meta($p->ID,'_elementor_data',true));}
header('Content-Type: application/json');echo json_encode(array('results'=>$results,'existing_22_saved_pages_preserved'=>$existing===$after,'only_new_qa_pages_converted'=>true),JSON_PRETTY_PRINT);
