<?php
if (!defined('ABSPATH')) { exit; }
function gpc_pages() {
    static $pages;
    if ($pages === null) { $pages = json_decode(file_get_contents(dirname(__DIR__) . '/pages.json'), true); }
    return is_array($pages) ? $pages : array();
}
function gpc_page_path($key) {
    $defs = gpc_pages();
    if (!isset($defs[$key])) { return ''; }
    return !empty($defs[$key]['parent']) ? gpc_page_path($defs[$key]['parent']) . '/' . $defs[$key]['slug'] : $defs[$key]['slug'];
}
function gpc_find_page($key) {
    if ($key === 'home') {
        $id = (int) get_option('page_on_front');
        return get_option('show_on_front') === 'page' && $id ? get_post($id) : null;
    }
    $map = get_option('gpc_page_ids', array());
    if (is_array($map) && !empty($map[$key])) {
        $page = get_post((int) $map[$key]);
        if ($page && $page->post_type === 'page' && $page->post_status !== 'trash') { return $page; }
    }
    return get_page_by_path(gpc_page_path($key), OBJECT, 'page');
}
function gpc_url($key) {
    if ($key === 'home') { return home_url('/'); }
    $page = gpc_find_page($key);
    return $page && $page->post_status === 'publish' ? get_permalink($page->ID) : home_url('/' . trim(gpc_page_path($key), '/') . '/');
}
function gpc_asset_url($filename) {
    return plugins_url('assets/' . basename($filename), dirname(__DIR__) . '/grant-publishing-site.php');
}
function gpc_contact_details() {
    $defaults = array('email'=>'hello@grantpublishingco.com', 'whatsapp'=>'2349036016020', 'linkedin'=>'https://www.linkedin.com/in/abdulqudustella/', 'upwork'=>'https://www.upwork.com/freelancers/abdulqudust');
    $saved = get_option('gpc_contact_details', array());
    return array_merge($defaults, is_array($saved) ? array_intersect_key($saved, $defaults) : array());
}
function gpc_contact_links($class = 'gp-contact-links-list') {
    $c = gpc_contact_details();
    return '<div class="' . esc_attr($class) . '"><a href="mailto:' . esc_attr($c['email']) . '">' . esc_html($c['email']) . '</a><a href="https://wa.me/' . esc_attr($c['whatsapp']) . '">WhatsApp: +' . esc_html($c['whatsapp']) . '</a><a href="' . esc_url($c['linkedin']) . '" target="_blank" rel="noopener noreferrer">LinkedIn</a><a href="' . esc_url($c['upwork']) . '" target="_blank" rel="noopener noreferrer">Upwork</a></div>';
}
add_shortcode('grant_contact_links', function() { return gpc_contact_links(); });
add_action('after_setup_theme', function() { register_nav_menu('gpc_primary', 'Grant Publishing primary navigation'); });
function gpc_navigation($current) {
    if (has_nav_menu('gpc_primary')) {
        return wp_nav_menu(array('theme_location'=>'gpc_primary', 'container'=>false, 'menu_id'=>'', 'echo'=>false, 'fallback_cb'=>false, 'depth'=>1, 'items_wrap'=>'<ul>%3$s</ul>'));
    }
    $defs = gpc_pages(); $links = '';
    foreach (array('home'=>'Home','services'=>'Services','about'=>'About','feedback'=>'Client Feedback','insights'=>'Insights','contact'=>'Contact') as $key=>$label) {
        $active = $key === $current || (!empty($defs[$current]['parent']) && $defs[$current]['parent'] === $key);
        $links .= '<a href="' . esc_url(gpc_url($key)) . '"' . ($active ? ' aria-current="' . ($key === $current ? 'page' : 'true') . '"' : '') . '>' . esc_html($label) . '</a>';
    }
    return $links;
}
function gpc_header($key) {
    $nav = gpc_navigation($key);
    $cta = '<a class="gp-button" href="' . esc_url(add_query_arg('request', 'assessment', gpc_url('enquiry')) . '#gpc-enquiry') . '">Free book assessment</a>';
    return '<a class="gpc-skip-link" href="#gpc-main">Skip to content</a><header class="gp-header"><div class="gp-wrap gp-header-inner"><a class="gp-wordmark" href="' . esc_url(gpc_url('home')) . '" aria-label="Grant Publishing Co. home"><img class="gp-logo" src="' . esc_url(gpc_asset_url('grant-logo.png')) . '" alt="Grant Publishing Co." width="176" height="63"></a><nav class="gp-nav" aria-label="Main navigation">' . $nav . '</nav><div class="gp-header-cta">' . $cta . '</div><details class="gp-mobile-nav"><summary>Menu</summary><nav aria-label="Mobile navigation">' . $nav . $cta . '</nav></details></div></header>';
}
function gpc_footer() {
    $links = '';
    foreach (array('services'=>'Services','about'=>'About','feedback'=>'Client Feedback','insights'=>'Insights','contact'=>'Contact','enquiry'=>'Free book assessment') as $key=>$label) {
        $links .= '<a href="' . esc_url(gpc_url($key)) . '">' . esc_html($label) . '</a>';
    }
    return '<footer class="gp-footer" id="contact"><div class="gp-wrap"><div class="gp-footer-grid"><div><a class="gp-footer-brand" href="' . esc_url(gpc_url('home')) . '" aria-label="Grant Publishing Co. home"><img class="gp-logo" src="' . esc_url(gpc_asset_url('grant-logo.png')) . '" alt="Grant Publishing Co." width="176" height="63" loading="lazy" decoding="async"></a><h2>Let’s talk about<br>your book.</h2><p>Book marketing and publishing support<br>led by AbdulQudus Tella.</p><a class="gp-button" href="' . esc_url(gpc_url('contact') . '#gpc-enquiry') . '">Discuss your project</a></div><div><h3>Explore</h3><nav aria-label="Footer navigation" class="gp-footer-links">' . $links . '</nav></div><div><h3>Get in touch</h3>' . gpc_contact_links('gp-footer-links') . '</div></div><p class="gp-footer-fine">© ' . esc_html(wp_date('Y')) . ' Grant Publishing Co. All rights reserved.</p></div></footer>';
}
function gpc_resolve_string($text, $escape = false) {
    return preg_replace_callback('/\{\{(url|asset):([a-z0-9.\-]+)\}\}/', function($m) use ($escape) {
        $url = $m[1] === 'url' ? gpc_url($m[2]) : gpc_asset_url($m[2]);
        return $escape ? esc_url($url) : $url;
    }, $text);
}
function gpc_site_page($key) {
    if (!isset(gpc_pages()[$key])) { return ''; }
    $file = dirname(__DIR__) . '/templates/' . $key . '.html';
    if (!is_readable($file)) { return ''; }
    gpc_enqueue_assets();
    $html = str_replace(array('{{header}}','{{footer}}','{{contact_links}}'), array(gpc_header($key),gpc_footer(),gpc_contact_links()), file_get_contents($file));
    $html = preg_replace_callback('/\{\{form:(assessment|project)\}\}/', function($m) { return gpc_site_form($m[1]); }, $html);
    $html = gpc_resolve_string($html, true);
    // Shortcodes embedded in older Elementor Canvas pages can render after wp_head.
    if (!wp_style_is('gpc-site', 'done')) { $html .= '<link rel="stylesheet" href="' . esc_url(gpc_asset_url('site.css') . '?ver=' . GPC_VERSION) . '">'; }
    return $html;
}
foreach (gpc_pages() as $gpc_key=>$gpc_def) {
    add_shortcode($gpc_def['shortcode'], function() use ($gpc_key) { return gpc_site_page($gpc_key); });
}
add_shortcode('grant_enquiry_form', function($atts) {
    $atts = shortcode_atts(array('request'=>'project'), $atts);
    return gpc_site_form($atts['request'] === 'assessment' ? 'assessment' : 'project');
});
function gpc_matches_page($post, $key) {
    $defs = gpc_pages();
    if (!$post || !isset($defs[$key])) { return false; }
    if (get_post_meta($post->ID, '_gpc_elementor_layout', true) && get_post_meta($post->ID, '_gpc_page_key', true) === $key) { return true; }
    $shortcode = $defs[$key]['shortcode'];
    $elementor = get_post_meta($post->ID, '_elementor_data', true);
    return has_shortcode($post->post_content, $shortcode) || (is_string($elementor) && strpos($elementor, '[' . $shortcode . ']') !== false);
}
function gpc_current_key() {
    if (!is_singular('page')) { return ''; }
    $post = get_post();
    foreach (gpc_pages() as $key=>$def) { if (gpc_matches_page($post, $key)) { return $key; } }
    return '';
}
function gpc_enqueue_assets() {
    $deps = wp_style_is('elementor-frontend', 'registered') ? array('elementor-frontend') : array();
    wp_enqueue_style('gpc-site', gpc_asset_url('site.css'), $deps, GPC_VERSION);
    wp_enqueue_script('gpc-interactions', gpc_asset_url('interactions.js'), array(), GPC_VERSION, true);
}
add_action('wp_enqueue_scripts', function() { if (gpc_current_key()) { gpc_enqueue_assets(); } }, 100);
add_filter('theme_page_templates', function($templates) { $templates['gpc-full-page.php'] = 'Grant Publishing Full Page'; return $templates; });
add_filter('template_include', function($template) {
    return is_page() && get_page_template_slug() === 'gpc-full-page.php' && gpc_current_key() ? __DIR__ . '/full-page.php' : $template;
}, 99);
add_action('template_redirect', function() {
    if (in_array(gpc_current_key(), array('enquiry','contact'), true)) {
        if (!defined('DONOTCACHEPAGE')) { define('DONOTCACHEPAGE', true); }
        nocache_headers();
    }
    if (!is_404() || (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET')) { return; }
    $path = trim((string) wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
    $base = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($base && strpos($path, $base . '/') === 0) { $path = substr($path, strlen($base) + 1); }
    $aliases = array('audit-page'=>'enquiry','work'=>'feedback','portfolio'=>'feedback','publishing'=>'publishing-support','our-process'=>'about');
    if (isset($aliases[$path])) {
        $page = gpc_find_page($aliases[$path]);
        if ($page && $page->post_status === 'publish') { wp_safe_redirect(gpc_url($aliases[$path]), 301); exit; }
    }
});
add_filter('pre_get_document_title', function($title) {
    $key = gpc_current_key(); $defs = gpc_pages();
    return $key ? $defs[$key]['title'] . ($key === 'home' ? ' | Book Marketing & Publishing Support' : ' | Grant Publishing Co.') : $title;
}, 20);
add_action('wp_head', function() {
    $key = gpc_current_key(); $defs = gpc_pages();
    if ($key && !defined('WPSEO_VERSION') && !defined('RANK_MATH_VERSION') && !defined('AIOSEO_VERSION') && !defined('SEOPRESS_VERSION')) {
        echo '<meta name="description" content="' . esc_attr($defs[$key]['description']) . '">' . "\n";
    }
});
// Keep form nonces and shared contact details dynamic when Elementor output caching is enabled.
add_filter('elementor/element/is_dynamic_content', function($dynamic, $data) {
    if (($data['widgetType'] ?? '') === 'shortcode' && preg_match('/\[grant_(enquiry_form|contact_links)\b/', $data['settings']['shortcode'] ?? '')) { return true; }
    return $dynamic;
}, 10, 2);
