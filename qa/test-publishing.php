<?php
// Isolated real rendering helpers. No HTTP, database writes or real form sends.
namespace Elementor { class Plugin { public static $instance; } }
namespace {
define('ABSPATH', '/wordpress-test/');
define('OBJECT', 'OBJECT');
define('GPC_VERSION', '4.3.0');
function wp_enqueue_script(...$args) {}
$GLOBALS['filters'] = array(); $GLOBALS['assertions'] = 0;
function add_action(...$args) {}
function add_shortcode(...$args) {}
function add_filter(...$args) { $GLOBALS['filters'][] = $args; }
function is_admin() { return $GLOBALS['context']['admin']; }
function is_feed() { return $GLOBALS['context']['feed']; }
function is_preview() { return $GLOBALS['context']['preview']; }
function is_singular($type = '') { return $type === 'page' && $GLOBALS['context']['singular']; }
function is_main_query() { return $GLOBALS['context']['main']; }
function in_the_loop() { return $GLOBALS['context']['loop']; }
function get_queried_object_id() { return $GLOBALS['context']['queried']; }
function get_page_template_slug($id = null) { return $GLOBALS['context']['template']; }
function get_post($id = null) { return $id ? ($GLOBALS['posts'][$id] ?? null) : $GLOBALS['posts'][$GLOBALS['context']['post']]; }
function get_post_meta($id, $key, $single = false) { return $GLOBALS['meta'][$id][$key] ?? ''; }
function get_option($key, $fallback = false) {
    $options = array('show_on_front' => 'page', 'page_on_front' => 41, 'gpc_page_ids' => array('feedback' => 42));
    return $options[$key] ?? $fallback;
}
function get_page_by_path(...$args) { return null; }
function has_shortcode($content, $tag) { return strpos($content, '[' . $tag . ']') !== false; }
function get_permalink($id) { return $id === 42 ? 'https://grant.test/client-feedback/' : 'https://grant.test/'; }
function home_url($path = '') { return 'https://grant.test' . $path; }
function plugins_url($path, $file) { return 'https://grant.test/wp-content/plugins/grant-publishing-site/' . $path; }
function esc_attr($text) { return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8'); }
function esc_html($text) { return esc_attr($text); }
function esc_url($text) { return esc_attr($text); }
function has_nav_menu($location) { return false; }
function add_query_arg($key, $value, $url) { return $url . '?' . rawurlencode($key) . '=' . rawurlencode($value); }
function wp_date($format) { return '2026'; }
function wp_strip_all_tags($text) { return strip_tags(preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $text)); }
// Any persistence introduced into these rendering helpers fails immediately.
function update_post_meta(...$args) { throw new \Exception('Saved native layouts must remain unchanged'); }
function wp_update_post(...$args) { throw new \Exception('Saved page content must remain unchanged'); }
function wp_remote_post(...$args) { throw new \Exception('No network request is expected'); }
require dirname(__DIR__) . '/grant-publishing-site/includes/pages.php';
require dirname(__DIR__) . '/grant-publishing-site/includes/portfolio.php';
require dirname(__DIR__) . '/grant-publishing-site/includes/publishing.php';
class ImageWidget {
    public $name; public $image; public $link;
    function __construct($url, $link = 'none', $name = 'image') { $this->image = array('url' => $url); $this->link = $link; $this->name = $name; }
    function get_name() { return $this->name; }
    function get_settings_for_display($key) { return $key === 'image' ? $this->image : $this->link; }
}
class Mode {
    public $active;
    function __construct($active) { $this->active = $active; }
    function is_edit_mode() { return $this->active; }
    function is_preview_mode() { return $this->active; }
}
function fixture($key = 'home') {
    $id = $key === 'feedback' ? 42 : 41;
    $GLOBALS['context'] = array('admin'=>false, 'feed'=>false, 'preview'=>false, 'singular'=>true, 'main'=>true, 'loop'=>true, 'queried'=>$id, 'post'=>$id, 'template'=>'gpc-full-page.php');
    $GLOBALS['posts'] = array(41=>(object) array('ID'=>41, 'post_type'=>'page', 'post_status'=>'publish', 'post_content'=>'Saved author changes'), 42=>(object) array('ID'=>42, 'post_type'=>'page', 'post_status'=>'publish', 'post_content'=>'Saved feedback changes'));
    $GLOBALS['meta'] = array($id=>array('_gpc_elementor_layout'=>'3.4.0', '_gpc_page_key'=>$key, '_elementor_data'=>'[{"saved":"author edit retained"}]'));
    \Elementor\Plugin::$instance = null;
}
function check($condition, $name) { if (!$condition) { throw new \Exception('FAIL: ' . $name); } $GLOBALS['assertions']++; echo 'PASS: ' . $name . "\n"; }
function image_markup($url) { return '<div class="elementor-widget-container"><img src="' . esc_attr($url) . '" alt="Author supplied caption" width="400" height="600" loading="lazy"></div>'; }
fixture();
$url = gpc_asset_url('kathryns-beach.jpg'); $widget = new ImageWidget($url); $html = image_markup($url);
$result = gpc_link_native_client_book($html, $widget);
check(strpos($result, '<a class="gp-book-amazon-link" href="https://www.amazon.com/dp/1947646168" target="_blank" rel="noopener noreferrer"') !== false, 'An unlinked bundled native Home cover links to the exact supplied Amazon destination');
check(strpos($result, 'opens in a new tab') !== false && strpos($result, substr($html, strpos($html, '<img'), strpos($html, '</div>') - strpos($html, '<img'))) !== false, 'Link has a descriptive new-tab name and preserves the complete original image');
check(gpc_link_native_client_book($result, $widget) === $result, 'Already linked rendered cover does not gain nested links');
fixture('feedback'); check(gpc_link_native_client_book($html, $widget) !== $html, 'Connected native Feedback cover receives the same correct link');
foreach (array('services', 'about', 'contact') as $key) {
    fixture($key); check(gpc_link_native_client_book($html, $widget) === $html && gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Unrelated ' . $key . ' page remains untouched');
}
fixture();
foreach (array('', 'https://other.test/kathryns-beach.jpg', 'https://grant.test/wp-content/uploads/kathryns-beach.jpg', gpc_asset_url('grant-logo.png')) as $edited) {
    check(gpc_link_native_client_book(image_markup($edited), new ImageWidget($edited)) === image_markup($edited), 'Blank, external or edited image is preserved: ' . ($edited ?: '(blank)'));
}
check(gpc_link_native_client_book(image_markup('https://other.test/replaced.jpg'), $widget) === image_markup('https://other.test/replaced.jpg'), 'Rendered replacement is preserved even when old settings retain the cover URL');
check(gpc_link_native_client_book($html, new ImageWidget($url, 'custom')) === $html && gpc_link_native_client_book($html, new ImageWidget($url, 'file')) === $html, 'Existing custom or attachment link settings are preserved');
$linked = str_replace('<img', '<a href="https://author.test/book"><img', $html); $linked = str_replace('</div>', '</a></div>', $linked);
check(gpc_link_native_client_book($linked, $widget) === $linked, 'An existing author link is never replaced');
check(gpc_link_native_client_book($html . $html, $widget) === $html . $html, 'Multiple-image widget is not modified');
check(gpc_link_native_client_book($html, new ImageWidget($url, 'none', 'image-box')) === $html, 'Only the image widget is eligible');
$picture = '<picture><source srcset="cover.webp">' . $html . '</picture>';
check(gpc_link_native_client_book($picture, $widget) === $picture, 'Optimiser picture structure is preserved without invalid nested picture markup');
$mapped = str_replace(' alt=', ' usemap="#book-map" alt=', $html);
check(gpc_link_native_client_book($mapped, $widget) === $mapped, 'Existing image-map interaction is not replaced');
$versioned = $url . '?ver=4.0.0&size=full';
check(gpc_link_native_client_book(image_markup($versioned), new ImageWidget($versioned)) !== image_markup($versioned), 'Bundled URL cache parameters do not prevent the correct link');
$plain_src = '<img src=' . $url . ' alt="Kathryn’s Beach">';
check(gpc_link_native_client_book($plain_src, $widget) !== $plain_src, 'Valid unquoted HTML source is handled');
foreach (array('admin', 'feed', 'preview') as $flag) {
    fixture(); $GLOBALS['context'][$flag] = true;
    check(gpc_link_native_client_book($html, $widget) === $html && gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', $flag . ' rendering receives no automatic compatibility content');
}
foreach (array('editor', 'preview') as $property) {
    fixture(); \Elementor\Plugin::$instance = (object) array($property => new Mode(true));
    check(gpc_link_native_client_book($html, $widget) === $html && gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Elementor ' . $property . ' rendering remains untouched');
}
fixture(); $GLOBALS['meta'][41]['_gpc_elementor_layout'] = '';
check(gpc_link_native_client_book($html, $widget) === $html && gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Unmarked pages are not modified');
fixture(); $GLOBALS['meta'][41]['_gpc_page_key'] = 'services';
check(gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Wrong native page connection does not append proof');
foreach (array('singular', 'main', 'loop') as $flag) {
    fixture(); $GLOBALS['context'][$flag] = false;
    check(gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Proof requires the singular main page loop: ' . $flag);
}
fixture(); $GLOBALS['context']['template'] = 'elementor_canvas';
check(gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Only the Grant full-page native template gets automatic proof');
fixture(); $GLOBALS['context']['queried'] = 42;
check(gpc_native_client_proof('<p>Saved text</p>') === '<p>Saved text</p>', 'Secondary post cannot receive the main page proof');
fixture(); $original = '<section id="work"><p>Saved author content &amp; formatting</p></section>';
$saved = serialize(array($GLOBALS['posts'], $GLOBALS['meta']));
$proof = gpc_native_client_proof($original);
check(substr($proof, 0, strlen($original)) === $original && strpos($proof, gpc_publishing_review()) !== false, 'Old native content stays byte-for-byte intact with the supplied new review appended');
check(strpos($proof, '{{') === false && strpos($proof, $url) !== false && strpos($proof, 'https://grant.test/client-feedback/') !== false, 'Real shared helper resolves proof asset and feedback route tokens');
check(strpos($proof, 'Nadine Laman') !== false && strpos($proof, 'Cactus Rain Publishing') !== false && strpos($proof, 'https://www.amazon.com/dp/1947646168') !== false, 'Supplied client attribution, publisher and Amazon destination remain in proof');
check(substr_count($proof, 'id="work"') === 1 && strpos($proof, 'id="gpc-client-proof"') !== false, 'Old work anchor is retained and appended proof gets a distinct ID');
check(gpc_native_client_proof($proof) === $proof, 'Repeated filtering cannot duplicate the appended review');
check(serialize(array($GLOBALS['posts'], $GLOBALS['meta'])) === $saved, 'Rendering leaves saved page content and Elementor data untouched');
fixture('feedback'); check(strpos(gpc_native_client_proof('<p>Older native feedback</p>'), gpc_publishing_review()) !== false, 'Older connected native Feedback receives new proof');
fixture(); $new_layout = '<blockquote>' . str_replace('expertise and content creation', 'expertise <strong>and content</strong> creation', gpc_publishing_review()) . '</blockquote>';
$new_layout .= '<blockquote>' . gpc_design_review() . '</blockquote>';
$new_layout = str_replace('We will', "We\n will", $new_layout);
$new_result = gpc_native_client_proof($new_layout);
check(substr($new_result, 0, strlen($new_layout)) === $new_layout && substr_count($new_result, gpc_design_review()) === 1 && strpos($new_result, gpc_asset_url('luma-the-sleepy-star.png')) !== false, 'Existing inline/line-wrapped reviews stay intact and receive only the missing Luma cover');
$encoded = '<p>' . str_replace('Mr.', 'Mr&#46;', gpc_publishing_review()) . '</p><p>' . gpc_design_review() . '</p>';
$encoded_result = gpc_native_client_proof($encoded);
check(substr($encoded_result, 0, strlen($encoded)) === $encoded && substr_count($encoded_result, gpc_design_review()) === 1, 'Entity-encoded updated review is recognised without repeating its quote');
$collision = '<div id="gpc-client-proof"><p>Author custom block</p></div>';
check(strpos(gpc_native_client_proof($collision), 'id="gpc-client-proof-2"') !== false, 'Existing author proof ID is preserved without creating a duplicate ID');
$nadine_only = '<blockquote>' . gpc_publishing_review() . '</blockquote>';
$with_design = gpc_native_client_proof($nadine_only);
check(substr($with_design, 0, strlen($nadine_only)) === $nadine_only && substr_count($with_design, gpc_publishing_review()) === 1 && strpos($with_design, gpc_design_review()) !== false, 'A native page already containing Nadine receives only the missing John review');
check(strpos($with_design, 'John Capon') !== false && strpos($with_design, 'https://www.linkedin.com/services/page/a29863343146852122/') !== false && strpos($with_design, 'Graphic Design services') !== false, 'John review has its supplied name, LinkedIn source and graphic-design context');
check(gpc_native_client_proof($with_design) === $with_design, 'Both review guards prevent repeat additions');
$luma = gpc_asset_url('luma-the-sleepy-star.png');
$grandfather = gpc_asset_url('my-dear-grandfather.jpg');
check(gpc_has_client_cover(image_markup($luma . '?ver=4.1.0&amp;size=full'), 'luma-the-sleepy-star.png', 'https://grant.test/upload/luma.png'), 'Cover detection accepts the genuine bundled URL with HTML entities and cache parameters');
check(!gpc_has_client_cover(image_markup('https://other.test/luma-the-sleepy-star.png'), 'luma-the-sleepy-star.png', 'https://grant.test/upload/luma.png'), 'A same-name external image is not treated as the supplied cover');
check(gpc_has_client_cover('<img src=http://grant.test/upload/luma.png alt="Luma">', 'luma-the-sleepy-star.png', 'https://grant.test/upload/luma.png'), 'User upload cover detection supports the actual HTTP source and unquoted markup');
check(!gpc_has_client_cover('<a href="' . $luma . '">Cover download</a>', 'luma-the-sleepy-star.png', 'https://grant.test/upload/luma.png'), 'A URL without a displayed image does not suppress the genuine cover');
check(gpc_native_client_proof($new_result) === $new_result && substr_count($new_result, $luma) === 1, 'Adding only the missing Luma cover is idempotent');
check(strpos($new_result, 'https://www.linkedin.com/services/page/a29863343146852122/') !== false && strpos($new_result, 'Luma the Sleepy Star by John Capon') !== false, 'Missing Luma card has the confirmed LinkedIn destination and genuine alternative text');
fixture('feedback');
$father_review = '<blockquote>We successfully completed our third project together.</blockquote>';
$saved_feedback = $new_layout . image_markup($luma) . $father_review;
$with_father = gpc_native_client_proof($saved_feedback);
check(substr($with_father, 0, strlen($saved_feedback)) === $saved_feedback && substr_count($with_father, $grandfather) === 1, 'Old native Feedback retains saved content and gains only its missing Grandfather card');
check(substr_count($with_father, gpc_design_review()) === 1 && substr_count($with_father, 'We successfully completed our third project together.') === 1, 'Cover compatibility does not repeat either existing client review');
check(strpos($with_father, 'https://www.amazon.com/dp/1947646117') !== false && strpos($with_father, 'By Barsha Rai') !== false && strpos($with_father, 'Publisher: Nadine Laman') !== false, 'Grandfather card has the confirmed Amazon destination, author and publisher');
check(gpc_native_client_proof($with_father) === $with_father, 'Grandfather gallery is not duplicated on repeated rendering');
$complete_feedback = $saved_feedback . image_markup($grandfather);
check(strpos(gpc_native_client_proof($complete_feedback), $complete_feedback) === 0 && strpos(gpc_native_client_proof($complete_feedback), 'My-Dear-Grandfather-Listing-Case-Study.pdf') !== false, 'Already complete native reviews and covers stay intact while receiving the requested case study');
fixture();
$uploaded_luma = 'https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-08_17_20-AM.png';
$uploaded_complete = $new_layout . image_markup($uploaded_luma);
check(gpc_native_client_proof($uploaded_complete) === $uploaded_complete, 'Already supplied WordPress-uploaded Luma cover is preserved without a duplicate gallery');
$header = gpc_header('home'); $footer = gpc_footer();
check(strpos($header, gpc_asset_url('grant-header-128.webp')) !== false && strpos($header, gpc_asset_url('grant-header-256.webp')) !== false && strpos($header, gpc_asset_url('grant-logo.png')) === false, 'Actual PHP header renders the supplied responsive icon instead of the old logo');
check(strpos($header, 'aria-label="Grant Publishing Co. home"') !== false && strpos($header, 'width="64" height="64"') !== false, 'Actual icon header retains company identification and square dimensions');
check(strpos($footer, gpc_asset_url('grant-footer-576.webp')) !== false && strpos($footer, 'width="1448" height="1086"') !== false, 'Actual PHP footer renders the supplied new full footer logo with original proportions');
check(strpos($footer, 'Books built to<br>be discovered.') !== false && strpos($footer, 'https://grant.test/contact/') !== false, 'Actual PHP footer uses the approved tagline and retains working enquiry navigation');

fixture('feedback');
$case_url = 'https://grantpublishingco.com/wp-content/uploads/2026/10/My-Dear-Grandfather-Listing-Case-Study.pdf';
$case_result = gpc_native_client_proof($complete_feedback);
check(substr($case_result, 0, strlen($complete_feedback)) === $complete_feedback && substr_count($case_result, 'href="' . $case_url . '"') === 1, 'Existing native Feedback gains one linked case study without replacing saved content');
check(gpc_native_client_proof($case_result) === $case_result, 'Case-study rendering is idempotent');
$texturized = str_replace("children's", 'children’s', $case_result);
check(gpc_native_client_proof($texturized) === $texturized, 'WordPress smart apostrophes do not cause a duplicate John review');
$encoded_apostrophe = str_replace('children’s', 'children&#8217;s', $texturized);
check(gpc_native_client_proof($encoded_apostrophe) === $encoded_apostrophe, 'Encoded WordPress typography also preserves the single existing review');
$case_complete = gpc_add_luma_case($complete_feedback) . '<a href="' . $case_url . '?ver=4.2.0">Existing authored case-study link</a>';
check(gpc_native_client_proof($case_complete) === $case_complete, 'An existing authored PDF link with cache parameters prevents a duplicate card');
check(gpc_native_client_proof(str_replace($case_url, str_replace('https:', 'http:', $case_url), $case_complete)) === str_replace($case_url, str_replace('https:', 'http:', $case_url), $case_complete), 'The user-supplied HTTP PDF destination also prevents duplication');
$case_collision = $complete_feedback . '<div id="grandfather-case-study">Existing author section</div>';
check(strpos(gpc_native_client_proof($case_collision), 'id="grandfather-case-study-2"') !== false, 'Saved case-study ID is retained with a distinct appended card ID');
fixture();
check(strpos(gpc_native_client_proof($complete_feedback), $case_url) === false, 'Case study is scoped to Feedback rather than Home');
$contact_source = '<p>Context stays.</p><a href="mailto:custom@example.test">Email the team</a><a href="https://www.linkedin.com/services/page/example/" target="_blank">Read the review</a>';
$icons = gpc_render_contact_icons($contact_source);
check(strpos($icons, 'href="mailto:custom@example.test"') !== false && strpos($icons, 'aria-label="Email the team"') !== false && strpos($icons, '<svg') !== false, 'Saved email destination and accessible meaning survive icon conversion');
check(strpos($icons, 'aria-label="Read the review (opens in a new tab)"') !== false && strpos($icons, 'rel="noopener noreferrer"') !== false, 'External review icon keeps its exact URL and protected new-tab action');
check(gpc_render_contact_icons($icons) === $icons, 'Repeated content filters do not replace existing SVG controls');
$readable_email = '<a class="gp-footer-email" href="mailto:hello@grantpublishingco.com">hello@grantpublishingco.com</a>';
check(gpc_render_contact_icons($readable_email) === $readable_email, 'Footer business email stays readable in shortcode and native page output');
$cover_link = '<a href="https://www.linkedin.com/services/page/example/">' . image_markup($luma) . '</a>';
check(gpc_render_contact_icons($cover_link) === $cover_link, 'A book cover linked to LinkedIn is never converted to a social icon');
foreach (array('admin', 'feed', 'preview') as $flag) {
 fixture(); $GLOBALS['context'][$flag] = true;
 check(gpc_render_contact_icons($contact_source) === $contact_source, $flag . ' content is not iconized');
}
fixture(); $GLOBALS['meta'][41]['_gpc_elementor_layout'] = ''; $GLOBALS['posts'][41]->post_content = '';
check(gpc_render_contact_icons($contact_source) === $contact_source, 'Unconnected page content remains untouched');
fixture();
$contacts = gpc_contact_links();
check(substr_count($contacts, 'class="gp-social-link"') === 4 && substr_count($contacts, 'aria-hidden="true"') === 4 && strpos($contacts, 'mailto:hello@grantpublishingco.com') !== false, 'Shared contact output contains four labelled icons and the configured email destination');

$registered = array_values(array_filter($GLOBALS['filters'], function($filter) { return in_array($filter[0], array('elementor/widget/render_content', 'the_content'), true); }));
check($registered === array(array('the_content', 'gpc_render_contact_icons', 35), array('elementor/widget/render_content', 'gpc_native_book_showcase', 25, 2), array('the_content', 'gpc_native_service_copy', 28), array('elementor/widget/render_content', 'gpc_link_native_client_book', 20, 2), array('the_content', 'gpc_native_client_proof', 30)), 'Compatibility hooks register image context and post-Elementor proof rendering');
fixture('feedback');
$luma_case = gpc_add_luma_case('<div id="john-capon-review"><div><p>Saved John review</p></div></div><p>Next review</p>');
check(strpos($luma_case, '</div><div class="gp-section gp-case-study') !== false && strpos($luma_case, 'luma-case-study') < strpos($luma_case,'Next review'), 'Luma case study appears directly after the saved John review');
check(gpc_add_luma_case($luma_case) === $luma_case, 'Luma case-study link prevents duplicate additions');
check(strpos($luma_case,'2.44 MB') !== false && filesize(dirname(__DIR__).'/grant-publishing-site/assets/luma-sleepy-star-case-study.pdf') === 2562805, 'Displayed Luma size derives from the unchanged supplied PDF');
check(strpos($luma_case,'did not include KDP upload, publishing, metadata optimisation or Kindle conversion') !== false, 'Luma scope ends at file delivery with exclusions visible');
fixture();
$studio = gpc_asset_url('publishing-studio.webp');
$showcase = gpc_native_book_showcase(image_markup($studio), new ImageWidget($studio));
check(strpos($showcase,'data-book-showcase') !== false && strpos($showcase,'publishing-studio.webp') === false, 'Bundled native hero studio image becomes the actual-book showcase');
check(substr_count($showcase,'data-slide') === 3 && substr_count($showcase,'data-src=') === 2, 'Showcase renders three verified projects and defers the later covers');
check(strpos($showcase,'Cover redesign &amp; interior formatting') !== false && strpos($showcase,'Amazon assessment &amp; recommendations') !== false, 'Actual project scopes remain distinct in showcase captions');
check(gpc_native_book_showcase(image_markup($url),new ImageWidget($url)) === image_markup($url), 'Unrelated book images remain unchanged');
fixture('services');
$legacy_services = '<div class="gp-service-list gp-service-list-full"><p>Saved custom introduction</p><h2>Publishing Support</h2><p>Prepare the book’s presentation and files for its next step.</p></div>';
$modern_services = gpc_native_service_copy($legacy_services);
check(substr_count($modern_services,'id="book-formatting"')===1 && substr_count($modern_services,'id="cover-design"')===1 && substr_count($modern_services,'id="amazon-ads"')===1, 'Old native Services receives the three missing entries');
check(strpos($modern_services,'Saved custom introduction')!==false && strpos($modern_services,'Book Publishing &amp; Amazon KDP Setup')!==false, 'Custom service content stays intact while exact old publishing copy updates');
check(gpc_native_service_copy($modern_services)===$modern_services, 'Service compatibility remains idempotent');
fixture();
$old_home='<h1 class="elementor-heading-title">Books built<br>to be <em>discovered.</em></h1><p>My own introduction.</p>';
$modern_home=gpc_native_service_copy($old_home);
check(strpos($modern_home,'Bring your book to life.')!==false && strpos($modern_home,'My own introduction.')!==false, 'Exact saved stock headline updates and authored copy remains');
$authored_home = '<h3>My custom service offer</h3><a href="' . gpc_url('amazon-visibility') . '">Explore service</a>';
check(gpc_native_service_copy($authored_home) === $authored_home, 'An authored service heading and its existing destination stay intact');
$stock_home = '<h3>Amazon Book Visibility</h3><a href="' . gpc_url('amazon-visibility') . '"><span>Explore service</span></a>';
check(strpos(gpc_native_service_copy($stock_home),'Book Design &amp; Formatting') !== false && substr_count(gpc_native_service_copy($stock_home),gpc_url('book-formatting')) === 2, 'Stock homepage service title and action agree on the new detail route');
fixture();$GLOBALS['context']['admin']=true;
check(gpc_native_service_copy($old_home)===$old_home && gpc_native_book_showcase(image_markup($studio),new ImageWidget($studio))===image_markup($studio), 'Editor and admin content stays untouched by the positioning update');
echo "\n" . $GLOBALS['assertions'] . " publishing compatibility assertions passed. No HTTP, page saves or real messages occurred.\n";
}
