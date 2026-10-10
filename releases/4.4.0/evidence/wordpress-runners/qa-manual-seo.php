<?php
require __DIR__.'/wp-load.php';wp_set_current_user(1);
$p=gpc_find_page('case-grandfather');$id=$p->ID;
$values=array('title'=>'Approved manual case title | Grant','metadesc'=>'Approved manual case description, preserved exactly.','focuskw'=>'approved manual keyphrase','canonical'=>home_url('/case-studies/my-dear-grandfather/?qa=canonical'),'opengraph-title'=>'Approved manual social title','opengraph-description'=>'Approved manual social description.','opengraph-image'=>gpc_asset_url('my-dear-grandfather-302.webp'),'twitter-title'=>'Approved manual Twitter title','twitter-description'=>'Approved manual Twitter description.','twitter-image'=>gpc_asset_url('my-dear-grandfather-302.webp'));
$backup=get_option('gpc_qa_manual_seo_backup',false);
if(($_GET['mode']??'')==='restore'){
 foreach(is_array($backup)?$backup:array() as $key=>$record){if($record['exists'])update_post_meta($id,$key,$record['value']);else delete_post_meta($id,$key);}
 delete_option('gpc_qa_manual_seo_backup');
}else{
 if($backup===false){$backup=array();foreach($values as $key=>$value){$full='_yoast_wpseo_'.$key;$backup[$full]=array('exists'=>metadata_exists('post',$id,$full),'value'=>get_post_meta($id,$full,true));}add_option('gpc_qa_manual_seo_backup',$backup,'',false);}
 foreach($values as $key=>$value)WPSEO_Meta::set_value($key,$value,$id);
}
YoastSEO()->classes->get('Yoast\\WP\\SEO\\Builders\\Indexable_Builder')->build_for_id_and_type($id,'post');
clean_post_cache($id);
header('Content-Type: application/json');echo json_encode(array('id'=>$id,'mode'=>$_GET['mode']??'seed','expected'=>$values,'url'=>get_permalink($id),'focus_keyphrase'=>get_post_meta($id,'_yoast_wpseo_focuskw',true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
