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

/** WordPress texturizes apostrophes before the compatibility filter runs. */
function gpc_review_match_text($text) {
    $text = html_entity_decode(wp_strip_all_tags($text), ENT_QUOTES, 'UTF-8');
    $text = strtr($text, array('’'=>"'", '‘'=>"'", '“'=>'"', '”'=>'"'));
    return trim(preg_replace('/\s+/u', ' ', $text));
}

/** Detect the supplied covers in rendered markup, including the user's upload. */
function gpc_has_client_cover($content, $filename, $upload) {
    if (!is_string($content)) { return false; }
    $sources = array(gpc_asset_url($filename), $upload, preg_replace('/^https:/', 'http:', $upload));
    if (!preg_match_all('/<img\b(?:[^>\'\"]|\"[^\"]*\"|\'[^\']*\')*>/i', $content, $images)) { return false; }
    foreach ($images[0] as $image) {
        if (!preg_match('/\bsrc\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+))/i', $image, $match)) { continue; }
        $src = isset($match[1]) && $match[1] !== '' ? $match[1] : (isset($match[2]) && $match[2] !== '' ? $match[2] : ($match[3] ?? ''));
        $src = preg_replace('/[?#].*$/', '', html_entity_decode($src, ENT_QUOTES, 'UTF-8'));
        if (in_array($src, $sources, true)) { return true; }
    }
    return false;
}

/** Limit automatic compatibility rendering to the connected native page. */
function gpc_publishing_page($main_loop = false, $keys = array('home', 'feedback')) {
    if (is_admin() || is_feed() || is_preview() || !is_singular('page')) { return false; }
    if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance)) {
        $elementor = \Elementor\Plugin::$instance;
        foreach (array('editor' => 'is_edit_mode', 'preview' => 'is_preview_mode') as $property => $method) {
            if (isset($elementor->$property) && is_object($elementor->$property) && method_exists($elementor->$property, $method) && $elementor->$property->$method()) { return false; }
        }
    }
    $key = gpc_current_key();
    $post = get_post();
    if (!in_array($key, $keys, true) || !$post || $post->post_type !== 'page' || (int) $post->ID !== (int) get_queried_object_id()) { return false; }
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
    $text = gpc_review_match_text($content);
    $parts = array(
        array('client-proof.html', gpc_publishing_review(), 'work', 'gpc-client-proof'),
        array('client-design-proof.html', gpc_design_review(), 'design-work', 'gpc-client-design-proof'),
    );
    foreach ($parts as $part) {
        if (strpos($text, gpc_review_match_text($part[1])) !== false) { continue; }
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

    // Earlier native layouts can already contain the review without the cover.
    // Add only the missing book cards, rather than repeating an existing quote
    // or changing saved author content. New bundled layouts already have them.
    $cards = '';
    $books = array(
        array('client-book-luma.html', 'luma-the-sleepy-star.png', 'https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-08_17_20-AM.png', gpc_design_review()),
        array('client-book-grandfather.html', 'my-dear-grandfather.jpg', 'https://grantpublishingco.com/wp-content/uploads/2026/10/61Zn-Dxc4nL._SY466_.jpg', 'We successfully completed our third project together.'),
    );
    $rendered_text = gpc_review_match_text($content);
    foreach ($books as $book) {
        if (strpos($rendered_text, gpc_review_match_text($book[3])) === false || gpc_has_client_cover($content, $book[1], $book[2])) { continue; }
        $file = dirname(__DIR__) . '/partials/' . $book[0];
        if (is_readable($file)) { $card = file_get_contents($file); if (is_string($card)) { $cards .= gpc_resolve_string($card, true); } }
    }
    if ($cards !== '') {
        $content .= '<section class="gp-section gp-client-books" aria-label="Books from the client projects"><div class="gp-wrap"><div class="gp-heading"><h2>Behind the client feedback.</h2></div><div class="gp-client-books-grid">' . $cards . '</div></div></section>';
    }
    // The new case study is additive on connected saved Feedback layouts only.
    // Match an actual link, so plain text mentioning the file cannot hide it.
    if (gpc_current_key() === 'feedback' && !preg_match('~<a\b[^>]*\bhref\s*=\s*(["\x27])https?://grantpublishingco\.com/wp-content/uploads/2026/10/My-Dear-Grandfather-Listing-Case-Study\.pdf(?:[?#][^"\x27]*)?\1~i', $content)) {
        $file = dirname(__DIR__) . '/partials/client-case-study.html';
        if (is_readable($file)) {
            $case = file_get_contents($file);
            if (is_string($case)) {
                $id = 'grandfather-case-study'; $suffix = 2;
                while (preg_match('/\bid\s*=\s*(["\x27])' . preg_quote($id, '/') . '\1/i', $content)) { $id = 'grandfather-case-study-' . $suffix++; }
                $content .= str_replace('id="grandfather-case-study"', 'id="' . esc_attr($id) . '"', $case);
            }
        }
    }
    if (gpc_current_key() === 'feedback') { $content = gpc_add_luma_case($content); }
    return $content;
}
// Run after Elementor's content rendering; this only appends server markup.
add_filter('the_content', 'gpc_native_client_proof', 30);
