<?php
add_filter('pre_http_request', function($pre, $args, $url) {
 if ($url === 'https://api.web3forms.com/submit') {
  $mode=$_SERVER['HTTP_X_GRANT_QA_RESPONSE'] ?? 'success';
  if($mode==='timeout')return new WP_Error('http_request_failed','Simulated timeout');
  return array('headers'=>array(), 'response'=>array('code'=>$mode==='rejected'?400:200,'message'=>'QA'),'body'=>$mode==='rejected'?'{}':'{"success":true}');
 }
 return $pre;
}, 10, 3);
add_filter('pre_wp_mail', function(){return true;});

// Reproduce the production Canvas/SEO duplicate-title path in the isolated QA page.
add_action('wp', function(){ if(is_page('qa-canvas')){ remove_theme_support('title-tag'); } }, 1);
