<?php
require __DIR__.'/wp-load.php';
$remote=$_SERVER['REMOTE_ADDR'] ?? 'unknown';delete_transient('gpc_rate_'.hash_hmac('sha256',$remote,wp_salt('auth')));
header('Content-Type: application/json');echo '{"reset":true}';
