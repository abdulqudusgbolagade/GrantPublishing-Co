<?php
if (!defined('ABSPATH')) { exit; }

/** Verified project scopes only. No generated artwork or invented outcomes. */
function gpc_portfolio_books() {
    return array(
        array('title'=>'Luma the Sleepy Star', 'author'=>'John Capon', 'scope'=>'Cover redesign & interior formatting', 'file'=>'luma-final-800.webp', 'set'=>array('luma-final-480.webp'=>480,'luma-final-800.webp'=>800,'luma-final-1000.webp'=>1000), 'width'=>1000, 'height'=>1000, 'url'=>'https://www.linkedin.com/services/page/a29863343146852122/', 'destination'=>'Read John’s review on LinkedIn'),
        array('title'=>'My Dear Grandfather', 'author'=>'Barsha Rai · Cactus Rain Publishing', 'scope'=>'Amazon listing optimisation', 'file'=>'my-dear-grandfather-302.webp', 'set'=>array('my-dear-grandfather-302.webp'=>302), 'width'=>302, 'height'=>466, 'url'=>'https://www.amazon.com/dp/1947646117', 'destination'=>'View the book on Amazon'),
        array('title'=>'Kathryn’s Beach', 'author'=>'Nadine Laman · Cactus Rain Publishing', 'scope'=>'Amazon assessment & recommendations', 'file'=>'kathryns-beach-640.webp', 'set'=>array('kathryns-beach-360.webp'=>360,'kathryns-beach-640.webp'=>640), 'width'=>1287, 'height'=>2048, 'url'=>'https://www.amazon.com/dp/1947646168', 'destination'=>'View the book on Amazon'),
    );
}

/** First cover is useful without JavaScript; later images have no network src. */
function gpc_book_showcase($context = 'home') {
    $context = $context === 'services' ? 'services' : 'home';
    wp_enqueue_script('gpc-showcase', gpc_asset_url('showcase.js'), array(), GPC_VERSION, true);
    $books = gpc_portfolio_books();
    $html = '<section class="gp-book-showcase gp-showcase-' . $context . '" data-book-showcase role="region" aria-roledescription="carousel" aria-label="Books from our client projects"><div class="gp-showcase-stage">';
    foreach ($books as $i => $book) {
        $set = array();
        foreach ($book['set'] as $file => $width) { $set[] = gpc_asset_url($file) . ' ' . $width . 'w'; }
        $html .= '<div class="gp-showcase-slide' . ($i === 0 ? ' is-active' : '') . '" data-slide role="group" aria-roledescription="slide" aria-label="' . esc_attr(($i + 1) . ' of ' . count($books) . ': ' . $book['title']) . '"' . ($i === 0 ? '' : ' hidden inert aria-hidden="true"') . '>';
        $html .= '<a class="gp-showcase-book-link" href="' . esc_url($book['url']) . '" target="_blank" rel="noopener noreferrer"' . ($i === 0 ? '' : ' tabindex="-1"') . ' aria-label="' . esc_attr($book['destination'] . ': ' . $book['title'] . ' (opens in a new tab)') . '">';
        $html .= '<img ' . ($i === 0 ? 'src' : 'data-src') . '="' . esc_url(gpc_asset_url($book['file'])) . '" ' . ($i === 0 ? 'srcset' : 'data-srcset') . '="' . esc_attr(implode(', ', $set)) . '" sizes="(max-width: 720px) calc(100vw - 64px), (max-width: 1120px) 42vw, 440px" width="' . (int) $book['width'] . '" height="' . (int) $book['height'] . '" alt="' . esc_attr($book['title'] . ' by ' . $book['author']) . '" decoding="async"' . ($i === 0 ? ' fetchpriority="high"' : '') . '></a>';
        $html .= '<div class="gp-showcase-caption"><p class="gp-showcase-title">' . esc_html($book['title']) . '</p><p class="gp-showcase-author">' . esc_html($book['author']) . '</p><p class="gp-showcase-scope">' . esc_html($book['scope']) . '</p></div></div>';
    }
    $html .= '</div><div class="gp-showcase-controls" hidden><button type="button" data-previous aria-label="Previous book">←</button><div class="gp-showcase-indicators" role="group" aria-label="Choose a book">';
    foreach ($books as $i => $book) { $html .= '<button type="button" data-indicator="' . $i . '" aria-label="Show ' . esc_attr($book['title']) . '" aria-pressed="' . ($i === 0 ? 'true' : 'false') . '"><span aria-hidden="true"></span></button>'; }
    return $html . '</div><button type="button" data-next aria-label="Next book">→</button><button type="button" class="gp-showcase-toggle" data-toggle aria-label="Pause automatic book rotation"><span aria-hidden="true">Ⅱ</span></button></div><p class="gp-showcase-status gpc-sr-only" role="status" aria-live="polite" aria-atomic="true"></p></section>';
}
add_shortcode('grant_book_showcase', function($atts) {
    return gpc_book_showcase(is_array($atts) && isset($atts['context']) ? $atts['context'] : 'home');
});

/** Swap only the genuine stock studio asset on its two connected native pages. */
function gpc_native_book_showcase($content, $widget) {
    if (!is_string($content) || !gpc_publishing_page(false, array('home','services')) || !is_object($widget) || !method_exists($widget,'get_name') || !method_exists($widget,'get_settings_for_display') || $widget->get_name() !== 'image') { return $content; }
    $image = $widget->get_settings_for_display('image');
    $url = is_array($image) ? preg_replace('/[?#].*$/', '', html_entity_decode($image['url'] ?? '', ENT_QUOTES, 'UTF-8')) : '';
    if ($url !== gpc_asset_url('publishing-studio.webp') || !gpc_has_client_cover($content, 'publishing-studio.webp', gpc_asset_url('publishing-studio.webp'))) { return $content; }
    return '<div class="elementor-widget-container">' . gpc_book_showcase(gpc_current_key()) . '</div>';
}
add_filter('elementor/widget/render_content', 'gpc_native_book_showcase', 25, 2);

function gpc_stock_copy_text($text) {
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode(wp_strip_all_tags($text), ENT_QUOTES, 'UTF-8')));
}
/** Exact stock-copy replacements at render time. Saved edits remain untouched. */
function gpc_native_service_copy($content) {
    if (!is_string($content) || !gpc_publishing_page(true, array('home','services','about','contact','publishing-support','feedback'))) { return $content; }
    $key = gpc_current_key();
    $home_links = array();
    if ($key === 'home') {
        $titles = array('Amazon Book Visibility'=>'amazon-visibility', 'Book Descriptions & A+ Content'=>'book-presentation', 'Book Launch & Promotion'=>'launch-promotion');
        preg_match_all('~<h3\b[^>]*>(.*?)</h3>~is', $content, $headings);
        foreach ($headings[1] as $heading) { $title = gpc_stock_copy_text($heading); if (isset($titles[$title])) { $home_links[] = $titles[$title]; } }
    }
    $maps = json_decode(file_get_contents(dirname(__DIR__) . '/native-copy-updates.json'), true);
    $updates = $maps[$key] ?? array();
    $content = preg_replace_callback('~<(p|h1|h2|h3|li)\b([^>]*)>(.*?)</\1>~is', function($m) use ($updates) {
        foreach ($updates as $old => $new) {
            if (gpc_stock_copy_text($m[3]) !== gpc_stock_copy_text($old)) { continue; }
            $inner = preg_replace('~^<(?:p|h1|h2|h3|li)\b[^>]*>|</(?:p|h1|h2|h3|li)>$~i', '', $new);
            return '<' . $m[1] . $m[2] . '>' . gpc_resolve_string($inner, true) . '</' . $m[1] . '>';
        }
        return $m[0];
    }, $content);
    if ($key === 'home') {
        // These old links occurred only in the stock homepage's service list.
        foreach (array('amazon-visibility'=>'book-formatting','book-presentation'=>'publishing-support','launch-promotion'=>'amazon-ads') as $old=>$new) {
            if (!in_array($old, $home_links, true)) { continue; }
            $content = preg_replace_callback('~<a\b([^>]*)>(.*?)</a>~is', function($m) use ($old,$new) {
                if (gpc_stock_copy_text($m[2]) !== 'Explore service' || strpos($m[1], 'href="' . esc_url(gpc_url($old)) . '"') === false) { return $m[0]; }
                return '<a' . str_replace('href="' . esc_url(gpc_url($old)) . '"', 'href="' . esc_url(gpc_url($new)) . '"', $m[1]) . '>' . $m[2] . '</a>';
            }, $content);
        }
    }
    if ($key === 'services') {
        $rows = file_get_contents(dirname(__DIR__) . '/partials/new-service-entries.html');
        $missing = '';
        // Balanced known rows are independent; never replace the saved list.
        foreach (array('book-formatting','cover-design','amazon-ads') as $service) {
            if (strpos($content, 'href="' . esc_url(gpc_url($service)) . '"') !== false) { continue; }
            if (preg_match('~<div class="gp-service-row" id="' . $service . '">.*?(?=<div class="gp-service-row"|\z)~s', $rows, $match)) { $missing .= $match[0]; }
        }
        if ($missing !== '') {
            if (substr_count($missing, 'class="gp-service-row"') === 3) {
                $content = preg_replace_callback('~(<div\b[^>]*class=["\x27][^"\x27]*\bgp-index\b[^"\x27]*["\x27][^>]*>(?:(?!<p\b).)*<p\b[^>]*>)(0[1-7])(</p>)~is', function($m) { return $m[1] . str_pad((string)((int)$m[2] + 3), 2, '0', STR_PAD_LEFT) . $m[3]; }, $content);
            }
            $content = preg_replace_callback('~(<div\b[^>]*class=["\x27][^"\x27]*\bgp-service-list-full\b[^"\x27]*["\x27][^>]*>)~i', function($m) use ($missing) { return $m[0] . gpc_resolve_string($missing, true); }, $content, 1);
        }
    }
    return $content;
}
add_filter('the_content', 'gpc_native_service_copy', 28);

/** Put the added feature immediately after John's review when its block exists. */
function gpc_add_luma_case($content) {
    $url = preg_quote(gpc_asset_url('luma-sleepy-star-case-study.pdf'), '~');
    if (preg_match('~<a\b[^>]*href\s*=\s*(["\x27])' . $url . '(?:[?#][^"\x27]*)?\1~i', html_entity_decode($content, ENT_QUOTES, 'UTF-8'))) { return $content; }
    $case = gpc_resolve_string(file_get_contents(dirname(__DIR__) . '/partials/luma-case-study.html'), true);
    $id = 'luma-case-study'; $suffix = 2;
    while (preg_match('/\bid\s*=\s*(["\x27])' . preg_quote($id,'/') . '\1/i', $content)) { $id = 'luma-case-study-' . $suffix++; }
    $case = str_replace('id="luma-case-study"', 'id="' . esc_attr($id) . '"', $case);
    if (preg_match('~<(div|section)\b[^>]*\bid\s*=\s*(["\x27])(?:john-capon-review|gpc-client-design-proof(?:-\d+)?)\2[^>]*>~i', $content, $start, PREG_OFFSET_CAPTURE)) {
        $tag = $start[1][0]; $offset = $start[0][1]; $depth = 0;
        preg_match_all('~</?' . $tag . '\b[^>]*>~i', substr($content, $offset), $tags, PREG_OFFSET_CAPTURE);
        foreach ($tags[0] as $match) {
            $depth += strpos($match[0], '</') === 0 ? -1 : 1;
            if ($depth === 0) { $end = $offset + $match[1] + strlen($match[0]); return substr($content,0,$end) . $case . substr($content,$end); }
        }
    }
    return $content . $case;
}
