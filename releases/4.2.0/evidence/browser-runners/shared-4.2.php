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
function wp_nonce_field(...$args) { echo '<input type="hidden" name="gpc_nonce" value="TEST-NONCE">'; }
function submit_button(...$args) { echo '<button type="submit">Save</button>'; }
function wp_enqueue_script(...$args) {}
function selected($current, $value, $echo = true) { $text = $current === $value ? ' selected="selected"' : ''; if ($echo) echo $text; return $text; }
require '/workspace/GrantPublishing-Co/grant-publishing-site/grant-publishing-site.php';

function has_nav_menu($location) { return false; }
function add_query_arg($key,$value,$url) { return $url.'?'.rawurlencode($key).'='.rawurlencode($value); }
function wp_date($format) { return '2026'; }
$shared = array('headers'=>array(), 'footer'=>gpc_footer(), 'contacts'=>gpc_contact_links(), 'form_project'=>gpc_site_form('project'), 'form_assessment'=>gpc_site_form('assessment'));
foreach(gpc_pages() as $key=>$def) { $shared['headers'][$key] = gpc_header($key); }
echo json_encode($shared, JSON_UNESCAPED_SLASHES);
