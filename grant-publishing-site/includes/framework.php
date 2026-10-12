<?php
if (!defined('ABSPATH')) { exit; }

/** Grant's named working method. Stage names are shared verbatim everywhere. */
function gpc_framework_stages() {
    return array(
        'G'=>array('name'=>'Ground the book in the right market.', 'brief'=>'Identify the intended reader, genre and market context.', 'full'=>'We consider the book’s genre, intended reader and comparable reading choices. This gives the work a useful starting point before choosing a design direction or promotional channel.'),
        'R'=>array('name'=>'Refine the positioning.', 'brief'=>'Clarify what the book offers and why it is relevant to that reader.', 'full'=>'We clarify the reading experience, central promise and reasons a reader might choose the book. Recommendations should reflect what the manuscript or supplied information actually supports.'),
        'A'=>array('name'=>'Align the listing and presentation.', 'brief'=>'Bring the cover, interior and public book information into agreement.', 'full'=>'We connect positioning with the cover, interior, description and other listing details relevant to the agreed work. Design and formatting projects can focus on this stage without including publication or marketing.'),
        'N'=>array('name'=>'Navigate visibility channels.', 'brief'=>'Choose suitable routes to readers, including launches and advertising where appropriate.', 'full'=>'We consider the channels that suit the book and its goals. A launch, author platform or advertising campaign is a choice to assess, rather than the automatic answer to every problem.'),
        'T'=>array('name'=>'Track growth and improve performance.', 'brief'=>'Review progress against an agreed baseline and make informed adjustments where included.', 'full'=>'Where review is part of the scope, we agree a baseline, timeframe and suitable measures. Tracking depends on available client-authorised data. Observations inform adjustments; they do not establish guaranteed growth or prove that one change caused a result.'),
    );
}
function gpc_framework_reference($letter) {
    $stages = gpc_framework_stages();
    return isset($stages[$letter]) ? '<strong class="gp-framework-stage-ref">' . esc_html($letter . ': ' . $stages[$letter]['name']) . '</strong>' : '';
}
function gpc_framework_link() {
    return '<a href="' . esc_url(gpc_url('about') . '#grant-framework') . '">GRANT Framework</a>';
}
/** Keep the established three-column process layout: G/R, A, then N/T. */
function gpc_framework_grid($context = 'home', $anchor = false) {
    $context = $context === 'about' ? 'about' : 'home';
    $stages = gpc_framework_stages();
    $intro = $context === 'about' ? 'The GRANT Framework is our working method for diagnosing needs, recommending services and evaluating progress. Market fit informs positioning; positioning guides presentation; a clear book proposition helps us choose visibility channels. Review then informs the next decision where it is included.' : 'The GRANT Framework is our working method for finding your book’s next priority, from market fit and presentation to suitable visibility channels and agreed review.';
    $html = '<div class="gp-framework gp-framework-' . $context . '" data-gpc-framework="' . $context . '"' . ($anchor ? ' id="grant-framework"' : '') . '><div class="gp-copy"><p>' . esc_html($intro) . '</p></div><div class="gp-three-columns">';
    foreach (array(array('G','R'), array('A'), array('N','T')) as $group) {
        $html .= '<div class="gp-step gp-framework-card">';
        foreach ($group as $letter) {
            $stage = $stages[$letter];
            $html .= '<div class="gp-framework-stage"><div class="gp-label"><p>' . $letter . '</p></div><div class="gp-item-title"><h3>' . esc_html($stage['name']) . '</h3></div><div class="gp-copy"><p>' . esc_html($stage[$context === 'about' ? 'full' : 'brief']) . '</p></div></div>';
        }
        $html .= '</div>';
    }
    $html .= '</div><div class="gp-copy gp-framework-scope"><p>Each project includes only the stages and deliverables we agree. Tracking depends on the scope, baseline, timeframe and available client-authorised data. Ongoing monitoring is not included with every service.</p></div>';
    if ($context === 'home') { $html .= '<div class="gp-action gp-secondary"><a class="elementor-button" href="' . esc_url(gpc_url('about') . '#grant-framework') . '"><span class="elementor-button-text">Explore the GRANT Framework</span></a></div>'; }
    return $html . '</div>';
}
function gpc_framework_assessment() {
    return '<p class="gp-framework-assessment" data-gpc-framework="assessment">The free assessment uses public Amazon-visible information to identify possible weaknesses and useful next steps. It is an initial diagnostic, not a complete five-stage engagement, access to private sales data or a promise of performance improvement. Learn how our ' . gpc_framework_link() . ' guides the wider work.</p>';
}
add_shortcode('grant_framework', function($atts) {
    $context = is_array($atts) ? ($atts['context'] ?? 'home') : 'home';
    return $context === 'assessment' ? gpc_framework_assessment() : gpc_framework_grid($context);
});
function gpc_framework_fit($key) {
    $fits = array(
        'book-formatting'=>array(array('A'), 'Readable interiors and appropriately prepared files support the book’s presentation. The agreed formats and file deliverables define the scope.'),
        'cover-design'=>array(array('A'), 'The cover communicates the book visually and prepares its agreed ebook or print formats. Design delivery does not automatically include publishing or advertising.'),
        'publishing-support'=>array(array('A'), 'Edition preparation connects the files and public book details. Account setup and uploads are included only within the agreed publishing scope.'),
        'book-presentation'=>array(array('R','A'), 'Descriptions and A+ Content communicate the reading proposition and bring the public presentation into agreement.'),
        'amazon-visibility'=>array(array('R','A'), 'Listing research considers how the book is positioned and whether its description, metadata and visible details communicate that consistently.'),
        'amazon-ads'=>array(array('N'), 'Advertising is one visibility channel to assess. Ongoing review is optional, recurring work, with measures, authorised data access and a review schedule agreed separately.'),
        'launch-promotion'=>array(array('N'), 'The launch plan chooses suitable routes to readers. A launch engagement does not automatically include ongoing performance monitoring.'),
        'author-platform'=>array(array('N'), 'The platform gives readers a clear route to the author and relevant books. Deliverables follow the agreed platform scope.'),
        'catalog-strategy'=>array(array('R','N'), 'We consider how the books relate and where readers can enter or continue. Recommendations depend on the catalog and information available.'),
        'book-strategy'=>array(array('G','R'), 'Strategy may also coordinate presentation, visibility or review where needed. The diagnosis and agreed scope determine which stages are included.'),
    );
    if (!isset($fits[$key])) { return ''; }
    $fit = $fits[$key]; $names = '';
    foreach ($fit[0] as $letter) { $names .= gpc_framework_reference($letter); }
    return '<div class="gp-framework-fit" data-gpc-framework-fit="' . esc_attr($key) . '"><p>Where this service fits the ' . gpc_framework_link() . ':</p><p>' . $names . '</p><p>' . esc_html($fit[1]) . '</p></div>';
}

/** Locate a balanced div/section without changing saved Elementor content. */
function gpc_content_block_bounds($content, $predicate) {
    preg_match_all('~<(div|section)\b[^>]*>~i', $content, $openings, PREG_OFFSET_CAPTURE);
    foreach ($openings[0] as $i=>$opening) {
        if (!$predicate($opening[0], $opening[1])) { continue; }
        $tag = strtolower($openings[1][$i][0]); $start = $opening[1]; $inner_start = $start + strlen($opening[0]); $depth = 1;
        preg_match_all('~</?' . $tag . '\b[^>]*>~i', substr($content, $inner_start), $tokens, PREG_OFFSET_CAPTURE);
        foreach ($tokens[0] as $token) {
            $depth += strpos($token[0], '</') === 0 ? -1 : 1;
            if ($depth === 0) {
                $close = $inner_start + $token[1];
                return array('start'=>$start, 'inner_start'=>$inner_start, 'close'=>$close, 'end'=>$close + strlen($token[0]));
            }
        }
    }
    return null;
}
function gpc_tag_has_class($tag, $class) {
    return preg_match('~\bclass\s*=\s*(["\x27])([^"\x27]*)\1~i', $tag, $m) && in_array($class, preg_split('/\s+/', trim($m[2])), true);
}
function gpc_section_heading_bounds($content, $heading) {
    if (!preg_match('~<h2\\b[^>]*>\\s*' . preg_quote($heading, '~') . '\\s*</h2>~i', $content, $match, PREG_OFFSET_CAPTURE)) { return null; }
    $heading_offset = $match[0][1]; $start = null;
    preg_match_all('~<(?:div|section)\\b[^>]*>~i', substr($content, 0, $heading_offset), $tags, PREG_OFFSET_CAPTURE);
    foreach ($tags[0] as $tag) { if (gpc_tag_has_class($tag[0], 'gp-section')) { $start = $tag[1]; } }
    return $start === null ? null : gpc_content_block_bounds($content, function($tag, $offset) use($start) { return $offset === $start; });
}
function gpc_framework_process($content, $key) {
    if (strpos($content, 'data-gpc-framework="' . $key . '"') !== false) { return $content; }
    $block = $key === 'about' ? gpc_section_heading_bounds($content, 'How we approach the work') : gpc_content_block_bounds($content, function($tag) { return preg_match('~\\bid\\s*=\\s*([\"\\x27])process\\1~i', $tag); });
    if (!$block) { return $content; }
    $inner = substr($content, $block['inner_start'], $block['close'] - $block['inner_start']);
    $grid = gpc_content_block_bounds($inner, function($tag) { return gpc_tag_has_class($tag, 'gp-three-columns'); });
    if (!$grid) { return $content; }
    $replacement = gpc_framework_grid($key, $key === 'about');
    $inner = substr($inner, 0, $grid['start']) . $replacement . substr($inner, $grid['end']);
    if ($key === 'home') { $inner = str_replace('THE WORKING RELATIONSHIP', 'THE GRANT FRAMEWORK', $inner); }
    return substr($content, 0, $block['inner_start']) . $inner . substr($content, $block['close']);
}
function gpc_framework_case($content, $key) {
    if (strpos($content, 'data-gpc-framework-case') !== false) { return $content; }
    $letters = $key === 'case-luma' ? array('A') : array('R','A'); $names = '';
    foreach ($letters as $letter) { $names .= gpc_framework_reference($letter); }
    $copy = $key === 'case-luma' ? 'Luma illustrates presentation through its documented cover redesign and interior formatting. The project ended at print-file delivery and did not complete the other framework stages.' : 'This project illustrates positioning and listing presentation through its documented description work, keyword research and category review. The later ranking record does not establish ongoing growth tracking or a causal result.';
    $addition = '<div class="gp-framework-fit" data-gpc-framework-case><p>How this documented work relates to the ' . gpc_framework_link() . ':</p><p>' . $names . '</p><p>' . esc_html($copy) . '</p></div>';
    $heading = $key === 'case-luma' ? 'Design and formatting decisions' : 'Diagnosis and strategy';
    $block = gpc_section_heading_bounds($content, $heading);
    if (!$block) { return $content; }
    $inner = substr($content, $block['inner_start'], $block['close'] - $block['inner_start']);
    $copy_block = gpc_content_block_bounds($inner, function($tag) { return gpc_tag_has_class($tag, 'gp-copy'); });
    if (!$copy_block) { return $content; }
    $position = $block['inner_start'] + $copy_block['close'];
    return substr($content, 0, $position) . $addition . substr($content, $position);
}
function gpc_framework_content($content) {
    $key = is_string($content) ? gpc_journey_context() : '';
    if (in_array($key, array('home','about'), true)) { return gpc_framework_process($content, $key); }
    if (in_array($key, array('case-luma','case-grandfather'), true)) { return gpc_framework_case($content, $key); }
    if ($key === 'enquiry' && strpos($content, 'data-gpc-framework="assessment"') === false) {
        $block = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-contact-aside'); });
        if ($block) { return substr($content, 0, $block['close']) . gpc_framework_assessment() . substr($content, $block['close']); }
    }
    return $content;
}
add_filter('the_content', 'gpc_framework_content', 37);

/** One supplied, approved screenshot. No upload or sales outcome is inferred. */
function gpc_aplus_example() {
    $url = gpc_asset_url('kathryns-beach-aplus-600.webp');
    return '<aside class="gp-aplus-example"><figure><a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" data-elementor-open-lightbox="no" aria-label="View the approved Kathryn’s Beach A+ Content screenshot (opens in a new tab)"><img src="' . esc_url($url) . '" width="600" height="606" alt="Kathryn’s Beach A+ Content example with story panels, Nadine Laman’s author panel and related titles" loading="lazy" decoding="async"></a><figcaption>Supplied and approved for public display.</figcaption></figure><div><p class="gp-label">A+ CONTENT EXAMPLE</p><h3>Kathryn’s Beach</h3><p>By Nadine Laman · Cactus Rain Publishing</p><p>This approved screenshot shows a coordinated story introduction, supporting panels, author information and related titles. It illustrates the A+ Content presentation deliverable, without claiming improved sales or advertising performance.</p><a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">View the approved A+ example<span class="gpc-sr-only"> (opens in a new tab)</span></a></div></aside>';
}
