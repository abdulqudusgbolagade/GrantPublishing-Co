<?php
// Actual upgrade code with isolated WordPress interfaces: no live cache requests.
define('ABSPATH', '/wordpress-test/');
define('GPC_VERSION', '4.0.0');
$GLOBALS['options'] = array();
$GLOBALS['events'] = array();
$GLOBALS['cleared'] = array();
$GLOBALS['posts'] = array();
$GLOBALS['matched'] = array();
$GLOBALS['definitions'] = array();
$GLOBALS['fail_clear'] = false;
$GLOBALS['registration'] = array();
$GLOBALS['pass'] = 0;
function add_action($name, $callback, $priority = 10) { $GLOBALS['registration'][] = array($name, $callback, $priority); }
function get_option($key, $fallback = false) { return $GLOBALS['options'][$key] ?? $fallback; }
function add_option($key, $value, $deprecated = '', $autoload = null) {
    if (array_key_exists($key, $GLOBALS['options'])) { return false; }
    $GLOBALS['options'][$key] = $value; return true;
}
function update_option($key, $value, $autoload = null) { $GLOBALS['options'][$key] = $value; $GLOBALS['events'][] = array('option', $key, $autoload); return true; }
function delete_option($key) { unset($GLOBALS['options'][$key]); return true; }
function wp_generate_uuid4() { static $counter = 0; return 'test-lock-' . ++$counter; }
function gpc_pages() { return $GLOBALS['definitions']; }
function gpc_find_page($key) { return $GLOBALS['posts'][$key] ?? null; }
function gpc_matches_page($page, $key) { return $GLOBALS['matched'][$key] ?? false; }
function gpc_clear_elementor_page_cache($id) {
    if ($GLOBALS['fail_clear']) { throw new Exception('Interrupted generated-cache cleanup'); }
    $GLOBALS['cleared'][] = $id;
}
function get_permalink($id) { return $id === 9 ? 'https://example.test/' : 'https://example.test/page-' . $id . '/'; }
function do_action($hook, ...$args) { $GLOBALS['events'][] = array($hook, $args); }
// Forbidden writes make preservation assertions fail immediately if introduced.
function wp_update_post(...$args) { throw new Exception('Page content must not be saved'); }
function wp_insert_post(...$args) { throw new Exception('Pages must not be created'); }
function update_post_meta(...$args) { throw new Exception('Saved page layouts must not be changed'); }
function wp_cache_flush(...$args) { throw new Exception('Global caches must not be flushed'); }
require dirname(__DIR__) . '/grant-publishing-site/includes/upgrades.php';
function fixture() {
    $GLOBALS['options'] = array('gpc_form_delivery'=>array('provider'=>'web3forms','access_key'=>'test-kept-key'),'gpc_contact_details'=>array('email'=>'kept@example.test'));
    $GLOBALS['events'] = array(); $GLOBALS['cleared'] = array(); $GLOBALS['fail_clear'] = false;
    $GLOBALS['definitions'] = array_fill_keys(array('home','services','services-alias','contact','unrelated','draft','missing','wrong-type'), array());
    $GLOBALS['posts'] = array(); $GLOBALS['matched'] = array();
    foreach (array('home'=>9,'services'=>108,'services-alias'=>108,'contact'=>186,'unrelated'=>777,'draft'=>888,'wrong-type'=>999) as $key=>$id) {
        $GLOBALS['posts'][$key] = (object) array('ID'=>$id,'post_type'=>$key === 'wrong-type' ? 'post' : 'page','post_status'=>$key === 'draft' ? 'draft' : 'publish','post_content'=>'Saved edits remain intact');
        $GLOBALS['matched'][$key] = $key !== 'unrelated';
    }
}
function check($condition, $name) { if (!$condition) { throw new Exception('FAIL: ' . $name); } $GLOBALS['pass']++; echo 'PASS: ' . $name . "\n"; }
function purge_events() { return array_values(array_filter($GLOBALS['events'], function($event) { return $event[0] === 'litespeed_purge_url'; })); }
check($GLOBALS['registration'] === array(array('init','gpc_refresh_site_assets',100)), 'Refresh registers after plugin initialization');
fixture(); $saved_posts = serialize($GLOBALS['posts']); $saved_delivery = $GLOBALS['options']['gpc_form_delivery']; $saved_contact = $GLOBALS['options']['gpc_contact_details'];
check(gpc_refresh_site_assets() === true, 'First version load refreshes connected pages');
check($GLOBALS['cleared'] === array(9,108,186), 'Only unique published connected Grant page IDs are cleared');
check(purge_events() === array(array('litespeed_purge_url',array('https://example.test/')),array('litespeed_purge_url',array('https://example.test/page-108/')),array('litespeed_purge_url',array('https://example.test/page-186/'))), 'LiteSpeed purge targets only exact Grant URLs including the front page');
check($GLOBALS['options']['gpc_assets_refreshed_version'] === GPC_VERSION, 'Successful refresh stores the current version');
check(!isset($GLOBALS['options']['gpc_asset_refresh_lock']), 'Completed refresh releases its lock');
check($GLOBALS['options']['gpc_form_delivery'] === $saved_delivery && $GLOBALS['options']['gpc_contact_details'] === $saved_contact && serialize($GLOBALS['posts']) === $saved_posts, 'Saved page edits, delivery credentials and contact options remain untouched');
$before = serialize(array($GLOBALS['cleared'],$GLOBALS['events'],$GLOBALS['options']));
check(gpc_refresh_site_assets() === false && serialize(array($GLOBALS['cleared'],$GLOBALS['events'],$GLOBALS['options'])) === $before, 'Repeated requests at the same version do no cache work or writes');
fixture(); $GLOBALS['options']['gpc_assets_refreshed_version'] = '3.4.0';
check(gpc_refresh_site_assets() === true && count(purge_events()) === 3 && $GLOBALS['options']['gpc_assets_refreshed_version'] === '4.0.0', 'Replacing an older asset version refreshes every connected page');
fixture(); $GLOBALS['options']['gpc_asset_refresh_lock'] = array('started_at'=>time(),'token'=>'other-request');
check(gpc_refresh_site_assets() === false && $GLOBALS['cleared'] === array() && !isset($GLOBALS['options']['gpc_assets_refreshed_version']) && $GLOBALS['options']['gpc_asset_refresh_lock']['token'] === 'other-request', 'Concurrent refresher respects an active lock without releasing it');
fixture(); $GLOBALS['options']['gpc_asset_refresh_lock'] = array('started_at'=>time()-301,'token'=>'interrupted-request');
check(gpc_refresh_site_assets() === true && count(purge_events()) === 3, 'A stale lock left by an interrupted request can recover');
fixture(); $GLOBALS['options']['gpc_assets_refreshed_version'] = '3.4.0'; $GLOBALS['fail_clear'] = true;
check(gpc_refresh_site_assets() === false && $GLOBALS['options']['gpc_assets_refreshed_version'] === '3.4.0' && !isset($GLOBALS['options']['gpc_asset_refresh_lock']), 'Failed cache cleanup leaves its old marker and releases the lock');
$GLOBALS['fail_clear'] = false;
check(gpc_refresh_site_assets() === true && count(purge_events()) === 3, 'A later request retries cache cleanup after a real failure');
fixture(); $GLOBALS['definitions'] = array();
check(gpc_refresh_site_assets() === true && $GLOBALS['cleared'] === array() && purge_events() === array(), 'A site with no connected Grant pages does not purge unrelated content');
echo "\n" . $GLOBALS['pass'] . " upgrade assertions passed. No live cache operations or content saves occurred.\n";
