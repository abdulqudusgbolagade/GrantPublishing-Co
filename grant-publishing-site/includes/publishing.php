<?php
if (!defined('ABSPATH')) { exit; }

/** The supplied review is also the duplicate guard for already updated layouts. */
function gpc_publishing_review() {
    return 'Mr. Tella provided expertise and content creation that resulted in the success of this project. We will be hiring him again in the near future. Very satisfied with his work.';
}

/** Graphic-design review supplied by the user, linked to its LinkedIn source. */
function gpc_design_review() {
    return "Abdul outperformed himself in the creation of my children's book, Luma the Sleepy Star, and did so in a very timely fashion. All kudos to Abdul; I will definitely be using him again. He is highly recommended";
}

/** Limit automatic compatibility rendering to the connected native page. */
function gpc_publishing_page($main_loop = false) {
    if (is_admin() || is_feed() || is_preview() || !is_singular('page')) { return false; }
    if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance)) {
        $elementor = \Elementor\Plugin::$instance;
        foreach (array('editor' => 'is_edit_mode', 'preview' => 'is_preview_mode') as $property => $method) {
            if (isset($elementor->$property) && is_object($elementor->$property) && method_exists($elementor->$property, $method) && $elementor->$property->$method()) { return false; }
        }
    }
    $key = gpc_current_key();
    $post = get_post();
    if (!in_array($key, array('home', 'feedback'), true) || !$post || $post->post_type !== 'page' || (int) $post->ID !== (int) get_queried_object_id()) { return false; }
    if (!get_post_meta($post->ID, '_gpc_elementor_layout', true) || get_post_meta($post->ID, '_gpc_page_key', true) !== $key) { return false; }
    if ($main_loop && (!is_main_query() || !in_the_loop() || get_page_template_slug($post->ID) !== 'gpc-full-page.php')) { return false; }
    return $post;
}

/** A similarly named external or edited image is not the bundled client cover. */
function gpc_is_client_book_asset($url) {
    if (!is_string($url) || $url === '') { return false; }
    $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');
    return preg_replace('/[?#].*$/', '', $url) === preg_replace('/[?#].*$/', '', gpc_asset_url('kathryns-beach.jpg'));
}

function gpc_link_native_client_book($content, $widget) {
    if (!gpc_publishing_page() || !is_string($content) || !is_object($widget) || !method_exists($widget, 'get_name') || !method_exists($widget, 'get_settings_for_display') || $widget->get_name() !== 'image') { return $content; }
    $image = $widget->get_settings_for_display('image');
    $link_to = $widget->get_settings_for_display('link_to');
    if (!is_array($image) || !gpc_is_client_book_asset($image['url'] ?? '') || !in_array($link_to, array(null, '', 'none'), true)) { return $content; }
    // Preserve every existing link, image-map or optimiser picture structure.
    if (preg_match('/<(?:a|picture)\b|\busemap\s*=/i', $content)) { return $content; }
    if (preg_match_all('/<img\b(?:[^>\'\"]|\"[^\"]*\"|\'[^\']*\')*>/i', $content, $images) !== 1) { return $content; }
    $img = $images[0][0];
    if (!preg_match('/\bsrc\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+))/i', $img, $source)) { return $content; }
    $src = isset($source[1]) && $source[1] !== '' ? $source[1] : (isset($source[2]) && $source[2] !== '' ? $source[2] : ($source[3] ?? ''));
    if (!gpc_is_client_book_asset($src)) { return $content; }
    $link = '<a class="gp-book-amazon-link" href="https://www.amazon.com/dp/1947646168" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr('View Kathryn’s Beach by Nadine Laman on Amazon (opens in a new tab)') . '">' . $img . '</a>';
    return str_replace($img, $link, $content);
}
add_filter('elementor/widget/render_content', 'gpc_link_native_client_book', 20, 2);

function gpc_native_client_proof($content) {
    if (!is_string($content) || !gpc_publishing_page(true)) { return $content; }
    $text = html_entity_decode(wp_strip_all_tags($content), ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text);
    $parts = array(
        array('client-proof.html', gpc_publishing_review(), 'work', 'gpc-client-proof'),
        array('client-design-proof.html', gpc_design_review(), 'design-work', 'gpc-client-design-proof'),
    );
    foreach ($parts as $part) {
        if (strpos($text, $part[1]) !== false) { continue; }
        $file = dirname(__DIR__) . '/partials/' . $part[0];
        if (!is_readable($file)) { continue; }
        $proof = file_get_contents($file);
        if ($proof === false || $proof === '') { continue; }
        $proof = gpc_resolve_string($proof, true);
        $id = $part[3]; $suffix = 2;
        while (preg_match('/\bid\s*=\s*(["\'])' . preg_quote($id, '/') . '\\1/i', $content)) { $id = $part[3] . '-' . $suffix++; }
        $proof = preg_replace('/\bid\s*=\s*(["\'])' . preg_quote($part[2], '/') . '\\1/i', 'id="' . esc_attr($id) . '"', $proof, 1);
        $content .= $proof;
    }
    return $content;
}
// Run after Elementor's content rendering; this only appends server markup.
add_filter('the_content', 'gpc_native_client_proof', 30);
