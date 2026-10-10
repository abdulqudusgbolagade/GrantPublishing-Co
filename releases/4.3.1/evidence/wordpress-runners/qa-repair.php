<?php
require __DIR__.'/wp-load.php';wp_set_current_user(1);gpc_repair_page_structure(true);
header('Content-Type: application/json');
$p=gpc_find_page('privacy-policy');$data=array('php'=>PHP_VERSION,'privacy_status'=>$p->post_status,'privacy_template'=>get_post_meta($p->ID,'_wp_page_template',true),'privacy_shortcode'=>gpc_matches_page($p,'privacy-policy'),'default_policy_backup'=>metadata_exists('post',$p->ID,'_gpc_legal_backup'),'repair_results'=>get_option('gpc_structure_repair_results'),'legal_links'=>gpc_legal_footer_links(),'all_routes'=>array());
foreach(gpc_pages() as $key=>$def){$data['all_routes'][$key]=gpc_url($key);}echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
