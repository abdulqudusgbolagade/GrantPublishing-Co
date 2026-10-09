<?php
// Isolated backend checks. No live HTTP, database, email or real access key is used.
define('ABSPATH', '/wordpress-test/');
define('MINUTE_IN_SECONDS', 60);
define('OBJECT', 'OBJECT');
class TestStop extends Exception {
    public $ok; public $status; public $text;
    function __construct($ok, $text, $status = 200) { parent::__construct($text); $this->ok = $ok; $this->text = $text; $this->status = $status; }
}
class WP_Error { }
$GLOBALS['test_options'] = array(); $GLOBALS['test_transients'] = array();
$GLOBALS['test_remote'] = array(); $GLOBALS['test_mail'] = array();
$GLOBALS['test_response'] = array('response'=>array('code'=>200),'body'=>'{"success":true}');
$GLOBALS['test_admin'] = true; $GLOBALS['test_nonce'] = true; $GLOBALS['test_mail_ok'] = true;
function add_action(...$args) {} function add_filter(...$args) {} function add_shortcode(...$args) {}
function get_option($key, $fallback = false) { return $GLOBALS['test_options'][$key] ?? $fallback; }
function update_option($key, $value, $autoload = null) { $GLOBALS['test_options'][$key] = $value; $GLOBALS['test_autoload'][$key] = $autoload; return true; }
function get_transient($key) { return $GLOBALS['test_transients'][$key] ?? false; }
function set_transient($key, $value, $ttl) { $GLOBALS['test_transients'][$key] = $value; return true; }
function delete_transient($key) { unset($GLOBALS['test_transients'][$key]); return true; }
function current_user_can(...$args) { return $GLOBALS['test_admin']; }
function get_current_user_id() { return 42; }
function wp_unslash($text) { return stripslashes($text); }
function wp_verify_nonce(...$args) { return $GLOBALS['test_nonce']; }
function check_admin_referer(...$args) { if (!$GLOBALS['test_nonce']) wp_die('Invalid nonce', '', array('response'=>403)); }
function sanitize_text_field($text) { return trim(strip_tags(preg_replace('/[\r\n\t]+/', ' ', $text))); }
function sanitize_textarea_field($text) { return trim(strip_tags($text)); }
function sanitize_email($text) { return filter_var($text, FILTER_SANITIZE_EMAIL); }
function is_email($text) { return filter_var($text, FILTER_VALIDATE_EMAIL); }
function wp_parse_url($url, $component) { return parse_url($url, $component); }
function esc_url_raw($url, $protocols = null) { return $url; }
function esc_html($text) { return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8'); }
function esc_attr($text) { return esc_html($text); } function esc_url($text) { return esc_html($text); }
function wp_salt(...$args) { return 'test-only-salt'; }
function wp_json_encode($value) { return json_encode($value); }
function wp_remote_post($url, $args) { $GLOBALS['test_remote'][] = array($url, $args); return $GLOBALS['test_response']; }
function wp_remote_retrieve_response_code($response) { return $response['response']['code']; }
function wp_remote_retrieve_body($response) { return $response['body']; }
function is_wp_error($response) { return $response instanceof WP_Error; }
function wp_mail(...$args) { $GLOBALS['test_mail'][] = $args; return $GLOBALS['test_mail_ok']; }
function wp_send_json_success($data, $status = 200) { throw new TestStop(true, $data['message'], $status); }
function wp_send_json_error($data, $status = 200) { throw new TestStop(false, $data['message'], $status); }
function wp_die($text, $title = '', $args = array()) { $GLOBALS['test_die_title'] = $title; throw new TestStop(false, $text, $args['response'] ?? 500); }
function wp_safe_redirect($url) { throw new TestStop(true, $url, 302); }
function get_page_by_path(...$args) { return null; }
function home_url($path) { return 'https://local.test' . $path; }
function admin_url($path) { return 'https://local.test/wp-admin/' . $path; }
function plugins_url($path, $file) { return 'https://local.test/plugin/' . $path; }
function wp_nonce_field(...$args) { echo '<input name="gpc_nonce" value="TEST-NONCE">'; }
function submit_button(...$args) { echo '<button type="submit">Save</button>'; }
function wp_enqueue_script(...$args) { $GLOBALS['test_scripts'][$args[0]] = $args; }
function selected($current, $value, $echo = true) { $text = $current === $value ? ' selected="selected"' : ''; if ($echo) echo $text; return $text; }
require dirname(__DIR__) . '/grant-publishing-site/grant-publishing-site.php';
function reset_test($web3 = true) {
    $GLOBALS['test_options'] = $web3 ? array('gpc_form_delivery'=>array('provider'=>'web3forms','access_key'=>'00000000-0000-0000-0000-000000000001')) : array();
    $GLOBALS['test_transients'] = array(); $GLOBALS['test_remote'] = array(); $GLOBALS['test_mail'] = array();
    $GLOBALS['test_response'] = array('response'=>array('code'=>200),'body'=>'{"success":true}');
    $GLOBALS['test_admin'] = true; $GLOBALS['test_nonce'] = true; $GLOBALS['test_mail_ok'] = true;
    $_SERVER['REQUEST_METHOD'] = 'POST'; $_SERVER['REMOTE_ADDR'] = '192.0.2.10';
    $_POST = array('gpc_nonce'=>'TEST-NONCE','gpc_ajax'=>'1','name'=>'Example Author','email'=>'author@example.test','request'=>'assessment','message'=>'Please assess my book.','consent'=>'yes','fax'=>'','book'=>'Example Book','service'=>'Book formatting','publication'=>'Preparing for launch','book_url'=>'https://example.test/book','website'=>'https://example.test/author');
}
function send_form() { try { gpc_site_send(); } catch (TestStop $e) { return $e; } throw new Exception('send handler did not return'); }
function save_settings() { try { gpc_save_delivery(); } catch (TestStop $e) { return $e; } throw new Exception('save handler did not stop'); }
$GLOBALS['passed'] = 0;
function check($condition, $name) { if (!$condition) { throw new Exception('FAIL: ' . $name); } $GLOBALS['passed']++; echo 'PASS: ' . $name . "\n"; }
function response_fixture($status, $body) { $GLOBALS['test_response'] = array('response'=>array('code'=>$status), 'body'=>$body); }
reset_test(false); $result = send_form();
check($result->ok && count($GLOBALS['test_mail']) === 1 && count($GLOBALS['test_remote']) === 0, 'Existing installs default to WordPress mail');
reset_test(false); $GLOBALS['test_mail_ok'] = false; $result = send_form();
check(!$result->ok && $result->status === 503, 'WordPress email failure remains visible');
reset_test(); $result = send_form();
check($result->ok && count($GLOBALS['test_remote']) === 1 && count($GLOBALS['test_mail']) === 0, 'Web3Forms sends once without email fallback');
$remote = $GLOBALS['test_remote'][0]; $payload = json_decode($remote[1]['body'], true);
check($remote[0] === 'https://api.web3forms.com/submit' && $remote[1]['sslverify'] === true && $remote[1]['redirection'] === 0, 'Fixed HTTPS provider endpoint, TLS and no redirect');
check($remote[1]['headers']['Content-Type'] === 'application/json' && $remote[1]['timeout'] < 25, 'Server JSON transport completes before browser timeout');
check($payload['name'] === 'Example Author' && $payload['email'] === 'author@example.test' && $payload['replyto'] === $payload['email'] && $payload['message'] === $_POST['message'], 'Name, email, reply address and message included');
check($payload['book_title'] === 'Example Book' && $payload['service'] === 'Book formatting' && $payload['publication_status'] === 'Preparing for launch' && $payload['book_url'] === $_POST['book_url'] && $payload['website'] === $_POST['website'] && $payload['contact_permission'] === 'Yes', 'Every optional field and consent included');
$result = send_form(); check($result->ok && count($GLOBALS['test_remote']) === 1, 'Confirmed duplicate submission does not send again');
foreach (array(400, 401, 403, 422, 429) as $status) {
    reset_test(); response_fixture($status, '{"success":false,"message":"Fixture rejection"}'); $result = send_form();
    check(!$result->ok && count($GLOBALS['test_remote']) === 1 && count($GLOBALS['test_mail']) === 0, 'HTTP ' . $status . ' rejection is visible without fallback');
    response_fixture(200, '{"success":true}'); $result = send_form();
    check($result->ok && count($GLOBALS['test_remote']) === 2, 'HTTP ' . $status . ' confirmed rejection permits intentional retry');
}
reset_test(); response_fixture(200, '{"success":false}'); $result = send_form();
check(!$result->ok && strpos($result->text, 'could not accept') !== false, 'Provider negative success is confirmed rejection');
foreach (array(array(500, '{"success":false}'), array(503, '<html>Unavailable</html>'), array(200, 'not json'), array(200, '{"success":"true"}'), array(200, '{}'), array(302, '')) as $fixture) {
    reset_test(); response_fixture($fixture[0], $fixture[1]); $result = send_form();
    check(!$result->ok && strpos($result->text, 'could not confirm') !== false && count($GLOBALS['test_mail']) === 0, 'Uncertain response ' . $fixture[0] . '/' . $fixture[1] . ' preserves uncertainty');
    $result = send_form(); check(!$result->ok && count($GLOBALS['test_remote']) === 1, 'Uncertain duplicate is held, never silently retried');
}
reset_test(); $GLOBALS['test_response'] = new WP_Error(); $result = send_form();
check(!$result->ok && strpos($result->text, 'could not confirm') !== false, 'Transport error/timeout is uncertain');
$result = send_form(); check(!$result->ok && count($GLOBALS['test_remote']) === 1, 'Transport-error duplicate is held');
reset_test(); $GLOBALS['test_options']['gpc_form_delivery']['access_key'] = ''; $result = send_form();
check(!$result->ok && count($GLOBALS['test_remote']) === 0 && count($GLOBALS['test_mail']) === 0, 'Missing key does not send or fall back');
foreach (array('fax'=>'spam','consent'=>'','email'=>'invalid','book_url'=>'ftp://example.test/book','request'=>'wrong','message'=>'') as $field=>$value) {
    reset_test(); $_POST[$field] = $value; $result = send_form();
    check(!$result->ok && $result->status === 400 && count($GLOBALS['test_remote']) === 0, 'Validation blocks invalid ' . $field);
}
reset_test(); $GLOBALS['test_nonce'] = false; $result = send_form();
check(!$result->ok && $result->status === 403 && count($GLOBALS['test_remote']) === 0, 'Expired form nonce blocks send');
reset_test(); $_SERVER['REQUEST_METHOD'] = 'GET'; $result = send_form();
check(!$result->ok && $result->status === 405 && count($GLOBALS['test_remote']) === 0, 'GET cannot submit enquiry');
reset_test(); for ($i=0;$i<5;$i++) { $_POST['message'] = 'Distinct message ' . $i; send_form(); } $_POST['message']='Sixth message'; $result=send_form();
check(!$result->ok && $result->status === 429 && count($GLOBALS['test_remote']) === 5, 'Existing address rate limit retained');
reset_test(); $html = gpc_site_form();
check(strpos($html, '00000000-0000-0000-0000-000000000001') === false && strpos($html, 'name="access_key"') === false, 'Access key is absent from public form markup');
check(strpos($html, 'sent securely to Web3Forms') !== false, 'Active provider disclosed in form privacy text');
reset_test(false); $html = gpc_site_form();
check(strpos($html, 'emailed to Grant Publishing') !== false && strpos($html, 'sent securely to Web3Forms') === false, 'WordPress mode retains email disclosure');
reset_test(); ob_start(); gpc_delivery_settings_screen(); $html=ob_get_clean();
check(strpos($html, 'type="password"') !== false && strpos($html, '00000000-0000-0000-0000-000000000001') === false, 'Admin key field is masked and never prefilled');
reset_test(false); $_POST=array('provider'=>'email','access_key'=>'00000000-0000-0000-0000-000000000002'); $result=save_settings();
check($result->ok && gpc_form_delivery_settings()['provider'] === 'web3forms' && $GLOBALS['test_autoload']['gpc_form_delivery'] === false, 'Saving a valid key selects provider and disables autoload');
reset_test(); $_POST=array('provider'=>'web3forms','access_key'=>''); $result=save_settings();
check($result->ok && gpc_form_delivery_settings()['access_key'] === '00000000-0000-0000-0000-000000000001', 'Blank key preserves existing credential');
reset_test(); $_POST=array('provider'=>'email','access_key'=>''); $result=save_settings();
check($result->ok && gpc_form_delivery_settings()['provider'] === 'email' && gpc_form_delivery_settings()['access_key'] !== '', 'Provider can be paused without removing key');
reset_test(); $_POST=array('provider'=>'web3forms','access_key'=>'','remove_key'=>'yes'); $result=save_settings();
check($result->ok && gpc_form_delivery_settings()['provider'] === 'email' && gpc_form_delivery_settings()['access_key'] === '', 'Removal clears credential and restores email delivery');
reset_test(); $_POST=array('provider'=>'web3forms','access_key'=>'not-a-valid-key'); $before=$GLOBALS['test_options']; $result=save_settings();
check(!$result->ok && $result->status === 400 && $GLOBALS['test_options'] === $before, 'Invalid key cannot overwrite saved settings');
reset_test(false); $_POST=array('provider'=>'web3forms','access_key'=>''); $result=save_settings();
check(!$result->ok && $result->status === 400 && $GLOBALS['test_options'] === array(), 'Web3Forms cannot be activated without credential');
reset_test(); $_POST=array('provider'=>'email','access_key'=>''); $GLOBALS['test_admin']=false; $result=save_settings();
check(!$result->ok && $result->status === 403 && gpc_form_delivery_settings()['provider'] === 'web3forms', 'Administrator capability protects delivery settings');
reset_test(); $_POST=array('provider'=>'email','access_key'=>''); $GLOBALS['test_nonce']=false; $result=save_settings();
check(!$result->ok && $result->status === 403 && gpc_form_delivery_settings()['provider'] === 'web3forms', 'CSRF nonce protects delivery settings');
reset_test(); $_POST=array('provider'=>'email','access_key'=>''); $_SERVER['REQUEST_METHOD']='GET'; $result=save_settings();
check(!$result->ok && $result->status === 405 && gpc_form_delivery_settings()['provider'] === 'web3forms', 'Delivery changes require POST');
reset_test(); $_POST=array('provider'=>'other','access_key'=>''); $result=save_settings();
check(!$result->ok && $result->status === 400 && gpc_form_delivery_settings()['provider'] === 'web3forms', 'Unsupported provider rejected');
reset_test(); $_POST['gpc_ajax'] = ''; $GLOBALS['test_response'] = new WP_Error(); $result = send_form();
check(!$result->ok && $GLOBALS['test_die_title'] === 'Enquiry delivery unconfirmed' && strpos($result->text, 'could not confirm') !== false, 'No-JavaScript uncertain delivery preserves uncertainty in title and body');
// Verify both normal and late shortcode asset paths for the 3.4 design layer.
$GLOBALS['test_styles'] = array(); $GLOBALS['test_styles_done'] = false;
function wp_style_is($handle, $state = 'enqueued') { return $state === 'done' ? $GLOBALS['test_styles_done'] : ($state === 'registered' && $handle === 'elementor-frontend'); }
function wp_enqueue_style($handle, $url, $deps, $version) { $GLOBALS['test_styles'][$handle] = array($url, $deps, $version); }
function has_nav_menu(...$args) { return false; }
function add_query_arg($key, $value, $url) { return $url . '?' . urlencode($key) . '=' . urlencode($value); }
function wp_date($format) { return '2026'; }
gpc_enqueue_assets();
check(isset($GLOBALS['test_styles']['gpc-design']) && $GLOBALS['test_styles']['gpc-design'][1] === array('gpc-site') && $GLOBALS['test_styles']['gpc-design'][2] === GPC_VERSION, 'Design layer enqueues after base styles with current cache version');
check(is_file(dirname(__DIR__) . '/grant-publishing-site/assets/design.css'), 'Design stylesheet exists in plugin source');
$html = gpc_site_page('home');
check(strpos($html, 'assets/site.css?ver=' . GPC_VERSION) !== false && strpos($html, 'assets/design.css?ver=' . GPC_VERSION) !== false, 'Shortcode rendered after wp_head includes both stylesheet links');
$GLOBALS['test_styles_done'] = true; $html = gpc_site_page('home');
check(strpos($html, '<link rel="stylesheet"') === false, 'Already-printed styles do not add duplicate fallback links');

foreach (array('Book formatting','Cover design','Book publishing and Amazon KDP setup','Amazon Ads campaign setup','Amazon Ads management') as $service) {
    reset_test(); $_POST['request']='project'; $_POST['service']=$service; $_POST['book_url']=''; $_POST['website']=''; $_POST['publication']='Still writing'; $result=send_form();
    $payload=json_decode($GLOBALS['test_remote'][0][1]['body'],true);
    check($result->ok && $payload['service']===$service && $payload['book_url']==='' && $payload['publication_status']==='Still writing', 'Prepublication enquiry retains ' . $service . ' without an Amazon link');
}
reset_test();$html=gpc_site_form();
check(strpos($html,'Book formatting: ebook, paperback &amp; hardcover')!==false && strpos($html,'Book cover design')!==false && strpos($html,'Amazon Ads ongoing management')!==false, 'All broader service labels are exposed by the actual PHP form');
check(strpos($html,'Amazon or book link (optional)')!==false && !preg_match('/name="book_url"[^>]*required/', $html), 'Amazon link remains optional in rendered form');
check(strpos(gpc_site_page('home'),'data-book-showcase')!==false && strpos(gpc_site_page('services'),'data-book-showcase')!==false, 'Both actual shortcode pages resolve the shared showcase');
foreach (array('home','services') as $page) {
    $GLOBALS['test_scripts']=array(); gpc_enqueue_assets($page);
    check(isset($GLOBALS['test_scripts']['gpc-showcase']) && $GLOBALS['test_scripts']['gpc-showcase'][1]===gpc_asset_url('showcase.js') && $GLOBALS['test_scripts']['gpc-showcase'][3]===GPC_VERSION, 'Cached native ' . $page . ' always queues the current showcase script');
}
echo "\n" . $GLOBALS['passed'] . " backend assertions passed. No real HTTP request or email was sent.\n";
