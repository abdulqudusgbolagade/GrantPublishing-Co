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
/** Local, decorative SVGs: no icon font, remote request or untrusted file path. */
function gpc_social_icon($channel) {
    static $icons = array();
    if (!in_array($channel, array('email', 'whatsapp', 'linkedin', 'upwork'), true)) { return ''; }
    if (!isset($icons[$channel])) {
        $icons[$channel] = file_get_contents(dirname(__DIR__) . '/assets/icons/' . $channel . '.svg');
    }
    return $icons[$channel];
}
function gpc_icon_link($channel, $url, $label, $external = false) {
    return '<a class="gp-social-link" href="' . esc_url($url) . '" aria-label="' . esc_attr($label) . '" title="' . esc_attr($label) . '"' . ($external ? ' target="_blank" rel="noopener noreferrer"' : '') . '>' . gpc_social_icon($channel) . '</a>';
}
/** Replace text contact links in older native layouts at render time only. */
function gpc_iconize_contact_links($content) {
    if (!is_string($content)) { return $content; }
    return preg_replace_callback('~<a\b([^>]*)>(.*?)</a>~is', function($m) {
        // Keep cover links, authored SVG icons and linked images intact.
        if (preg_match('/<(?:img|svg|picture)\b/i', $m[2]) || !preg_match('~\bhref\s*=\s*(["\x27])([^"\x27]+)\1~i', $m[1], $href)) { return $m[0]; }
        $url = html_entity_decode($href[2], ENT_QUOTES, 'UTF-8');
        $channel = '';
        if (strpos($url, 'mailto:') === 0) { $channel = 'email'; }
        elseif (preg_match('~^https?://wa\.me/~i', $url)) { $channel = 'whatsapp'; }
        elseif (preg_match('~^https?://(?:www\.)?linkedin\.com/~i', $url)) { $channel = 'linkedin'; }
        elseif (preg_match('~^https?://(?:www\.)?upwork\.com/~i', $url)) { $channel = 'upwork'; }
        if ($channel === '') { return $m[0]; }
        $label = trim(html_entity_decode(wp_strip_all_tags($m[2]), ENT_QUOTES, 'UTF-8'));
        if ($label === '') { return $m[0]; }
        $external = preg_match('/\btarget\s*=\s*(["\x27])_blank\1/i', $m[1]);
        return gpc_icon_link($channel, $url, $label . ($external ? ' (opens in a new tab)' : ''), (bool) $external);
    }, $content);
}
function gpc_render_contact_icons($content) {
    if (is_admin() || is_feed() || is_preview() || !is_singular('page') || !is_main_query() || !in_the_loop() || !gpc_current_key()) { return $content; }
    $post = get_post();
    if (!$post || (int) $post->ID !== (int) get_queried_object_id()) { return $content; }
    if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance)) {
        $elementor = \Elementor\Plugin::$instance;
        foreach (array('editor' => 'is_edit_mode', 'preview' => 'is_preview_mode') as $property => $method) {
            if (isset($elementor->$property) && is_object($elementor->$property) && method_exists($elementor->$property, $method) && $elementor->$property->$method()) { return $content; }
        }
    }
    return gpc_iconize_contact_links($content);
}
add_filter('the_content', 'gpc_render_contact_icons', 35);
function gpc_contact_links($class = 'gp-contact-links-list') {
    $c = gpc_contact_details();
    return '<div class="gp-social-links ' . esc_attr($class) . '" role="group" aria-label="Contact Grant Publishing Co.">' .
        gpc_icon_link('linkedin', $c['linkedin'], 'View AbdulQudus on LinkedIn (opens in a new tab)', true) .
        gpc_icon_link('upwork', $c['upwork'], 'View AbdulQudus on Upwork (opens in a new tab)', true) .
        gpc_icon_link('whatsapp', 'https://wa.me/' . $c['whatsapp'], 'Message Grant Publishing Co. on WhatsApp') .
        gpc_icon_link('email', 'mailto:' . $c['email'], 'Email ' . $c['email']) . '</div>';
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
    return '<a class="gpc-skip-link" href="#gpc-main">Skip to content</a><header class="gp-header"><div class="gp-wrap gp-header-inner"><a class="gp-wordmark" href="' . esc_url(gpc_url('home')) . '" aria-label="Grant Publishing Co. home"><img class="gp-logo" src="' . esc_url(gpc_asset_url('grant-header-128.webp')) . '" srcset="' . esc_url(gpc_asset_url('grant-header-128.webp')) . ' 128w, ' . esc_url(gpc_asset_url('grant-header-256.webp')) . ' 256w" sizes="(max-width: 720px) 56px, 64px" alt="Grant Publishing Co." width="64" height="64" decoding="async"></a><nav class="gp-nav" aria-label="Main navigation">' . $nav . '</nav><div class="gp-header-cta">' . $cta . '</div><details class="gp-mobile-nav"><summary>Menu</summary><nav aria-label="Mobile navigation">' . $nav . $cta . '</nav></details></div></header>';
}
function gpc_footer() {
    $links = '';
    foreach (array('services'=>'Services','about'=>'About','feedback'=>'Client Feedback','insights'=>'Insights','contact'=>'Contact','enquiry'=>'Free book assessment') as $key=>$label) {
        $links .= '<a href="' . esc_url(gpc_url($key)) . '">' . esc_html($label) . '</a>';
    }
    return '<footer class="gp-footer" id="contact"><div class="gp-wrap"><div class="gp-footer-intro"><h2>Books built to<br>be discovered.</h2><a class="gp-button" href="' . esc_url(gpc_url('contact') . '#gpc-enquiry') . '">Discuss your project</a></div><div class="gp-footer-grid"><div class="gp-footer-identity"><a class="gp-footer-brand" href="' . esc_url(gpc_url('home')) . '" aria-label="Grant Publishing Co. home"><img class="gp-logo" src="' . esc_url(gpc_asset_url('grant-footer-576.webp')) . '" alt="Grant Publishing Co." width="1448" height="1086" loading="lazy" decoding="async"></a><p>Book marketing and publishing support<br>led by AbdulQudus Tella.</p></div><div class="gp-footer-explore"><h3>Explore</h3><nav aria-label="Footer navigation" class="gp-footer-links">' . $links . '</nav></div><div class="gp-footer-contact"><h3>Let’s connect</h3><p>Share your book, your goals<br>and the support you need.</p>' . gpc_contact_links('gp-footer-socials') . '<p class="gp-footer-contact-note">Choose a channel to start<br>a direct conversation.</p></div></div><div class="gp-footer-bottom"><p class="gp-footer-fine">© ' . esc_html(wp_date('Y')) . ' Grant Publishing Co. All rights reserved.</p><p class="gp-footer-fine">Clear strategy. Thoughtful publishing.</p></div></div></footer>';
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
    if (!wp_style_is('gpc-design', 'done')) { $html .= '<link rel="stylesheet" href="' . esc_url(gpc_asset_url('design.css') . '?ver=' . GPC_VERSION) . '">'; }
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
    wp_enqueue_style('gpc-design', gpc_asset_url('design.css'), array('gpc-site'), GPC_VERSION);
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
