<?php
require __DIR__.'/wp-load.php';wp_set_current_user(1);
$live=json_decode(file_get_contents('/wordpress/qa-live-metadata.json'),true);$records=array();
foreach(gpc_pages() as $key=>$def){$p=gpc_find_page($key);$record=null;foreach($live as $candidate){if($candidate['slug']===$p->post_name || $key==='home'&&$candidate['slug']==='grant-publishing-co'){$record=$candidate;break;}}
 if(!$record)continue;$meta=$record['yoast_head_json'];WPSEO_Meta::set_value('title',$meta['title'],$p->ID);WPSEO_Meta::set_value('metadesc',$meta['description']??'',$p->ID);WPSEO_Meta::set_value('canonical',get_permalink($p->ID),$p->ID);WPSEO_Meta::set_value('focuskw','QA preservation sentinel: '.$key,$p->ID);
 foreach(array('opengraph-title'=>'og_title','opengraph-description'=>'og_description','twitter-title'=>'twitter_title','twitter-description'=>'twitter_description') as $field=>$json_key){if(isset($meta[$json_key]))WPSEO_Meta::set_value($field,$meta[$json_key],$p->ID);}
 if(in_array($key,array('book-discovery','book-product-page','connected-catalog'),true))wp_update_post(array('ID'=>$p->ID,'post_date'=>$record['date'],'post_date_gmt'=>$record['date_gmt'],'edit_date'=>true));
 YoastSEO()->classes->get('Yoast\WP\SEO\Builders\Indexable_Builder')->build_for_id_and_type($p->ID,'post');$records[$key]=array('id'=>$p->ID,'manual_title'=>$meta['title'],'published_date'=>get_post($p->ID)->post_date);
}
header('Content-Type: application/json');echo json_encode(array('qa_only'=>true,'records'=>$records),JSON_PRETTY_PRINT);
