<?php
require __DIR__.'/wp-load.php';
wp_set_current_user(1);
function qa_preservation_snapshot() {
    $pages=array();
    foreach(get_posts(array('post_type'=>'page','post_status'=>array('publish','draft','private','pending'),'numberposts'=>-1)) as $p) {
        if(in_array(get_post_meta($p->ID,'_gpc_page_key',true),array('case-studies','case-grandfather','case-luma','faq'),true))continue;
        $seo=array();foreach(get_post_meta($p->ID) as $k=>$values){if(strpos($k,'_yoast_wpseo_')===0)$seo[$k]=$values;}
        ksort($seo);
        $pages[$p->ID]=array('title'=>$p->post_title,'name'=>$p->post_name,'parent'=>$p->post_parent,'status'=>$p->post_status,'content_sha256'=>hash('sha256',$p->post_content),'elementor_sha256'=>hash('sha256',(string)get_post_meta($p->ID,'_elementor_data',true)),'yoast_sha256'=>hash('sha256',json_encode($seo)),'template'=>get_post_meta($p->ID,'_wp_page_template',true));
    }
    ksort($pages);$options=array();foreach(array('gpc_form_delivery','gpc_contact_details','gpc_legal_pages','show_on_front','page_on_front','theme_mods_twentytwentyfour','wp_page_for_privacy_policy') as $k)$options[$k]=hash('sha256',serialize(get_option($k)));
    return array('pages'=>$pages,'settings_sha256'=>$options);
}
$before=qa_preservation_snapshot();$result=gpc_create_evidence_pages(true);$after=qa_preservation_snapshot();
$repeat=gpc_create_evidence_pages(true);
$data=array('php'=>PHP_VERSION,'version'=>GPC_VERSION,'creation_success'=>$result,'repeat_success'=>$repeat,'existing_pages_and_settings_preserved'=>$before===$after,'before'=>$before,'after'=>$after,'results'=>get_option('gpc_evidence_pages_results'),'all_routes'=>array());
foreach(gpc_pages() as $key=>$def){$data['all_routes'][$key]=gpc_url($key);}
header('Content-Type: application/json');echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
