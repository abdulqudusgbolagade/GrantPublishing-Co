<?php
if (!defined('ABSPATH')) { exit; }

/** Public rendering only, including connected Elementor Canvas pages. */
function gpc_journey_context() {
    if (is_admin() || is_feed() || is_preview() || !is_singular('page') || !is_main_query() || !in_the_loop()) { return ''; }
    $post = get_post();
    if (!$post || (int) $post->ID !== (int) get_queried_object_id()) { return ''; }
    if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance)) {
        $elementor = \Elementor\Plugin::$instance;
        foreach (array('editor'=>'is_edit_mode', 'preview'=>'is_preview_mode') as $property=>$method) {
            if (isset($elementor->$property) && is_object($elementor->$property) && method_exists($elementor->$property, $method) && $elementor->$property->$method()) { return ''; }
        }
    }
    return gpc_current_key();
}
function gpc_journey_link($key, $label) {
    return '<a href="' . esc_url(gpc_url($key)) . '">' . esc_html($label) . '</a>';
}
function gpc_journey_assessment() {
    return '<a class="gp-button" href="' . esc_url(gpc_url('enquiry') . '?request=assessment#gpc-enquiry') . '">Request a free book assessment</a>';
}
/** Independent section before an existing final CTA, or inside the shared main. */
function gpc_insert_journey($content, $section) {
    if (preg_match('~<div\b[^>]*\bclass\s*=\s*(["\x27])[^"\x27]*\bgp-cta\b[^"\x27]*\1[^>]*>~i', $content, $match, PREG_OFFSET_CAPTURE)) {
        $offset = $match[0][1];
        return substr($content, 0, $offset) . $section . substr($content, $offset);
    }
    $offset = strripos($content, '</main>');
    return $offset === false ? $content . $section : substr($content, 0, $offset) . $section . substr($content, $offset);
}
function gpc_service_connections() {
    $link = 'gpc_journey_link';
    return array(
        'amazon-visibility'=>array('case'=>'case-grandfather', 'copy'=>'Listing improvements should fit a wider plan. ' . $link('book-strategy','Book Marketing Strategy') . ' helps establish priorities; ' . $link('amazon-ads','Amazon Ads Setup & Management') . ' can support promotion once the book and listing are ready.', 'insight'=>'book-discovery', 'faq'=>array('guarantees','assessment')),
        'book-presentation'=>array('case'=>'case-grandfather', 'copy'=>'Clear descriptions and A+ Content work alongside ' . $link('amazon-visibility','Amazon Book Visibility') . ' and ' . $link('cover-design','Book Cover Design') . '. Each should communicate the same reading experience.', 'insight'=>'book-product-page'),
        'book-formatting'=>array('case'=>'case-luma', 'copy'=>'A readable interior is one part of the finished book. Pair it with ' . $link('cover-design','Book Cover Design') . ', then agree any account setup or upload work separately through ' . $link('publishing-support','Publishing Support') . '.', 'faq'=>array('formats','revisions')),
        'cover-design'=>array('case'=>'case-luma', 'copy'=>'The cover and interior should work together. ' . $link('book-formatting','Book Formatting') . ' prepares the reading experience; ' . $link('publishing-support','Publishing Support') . ' covers agreed preparation and account work beyond design delivery.', 'faq'=>array('revisions','formats')),
        'amazon-ads'=>array('copy'=>'Before committing an advertising budget, review the listing through ' . $link('amazon-visibility','Amazon Book Visibility') . ' and set priorities with ' . $link('book-strategy','Book Marketing Strategy') . '. More traffic cannot resolve every issue with book presentation.', 'insight'=>'book-discovery', 'faq'=>array('management','spend')),
        'launch-promotion'=>array('copy'=>'A launch brings several decisions together: ' . $link('amazon-visibility','Amazon Book Visibility') . ' for the listing, ' . $link('amazon-ads','Amazon Ads Setup & Management') . ' where advertising is appropriate, and ' . $link('author-platform','Author Platform') . ' for the reader’s next step. ' . $link('book-strategy','Book Marketing Strategy') . ' connects these choices to the book’s goals.', 'insight'=>'connected-catalog'),
        'author-platform'=>array('copy'=>'An author platform should support a clear reader journey. ' . $link('book-strategy','Book Marketing Strategy') . ' establishes its role, while ' . $link('launch-promotion','Book Launch & Promotion') . ' connects it to a specific release.', 'insight'=>'connected-catalog'),
        'book-strategy'=>array('case'=>'case-grandfather', 'copy'=>'A strategy may prioritise ' . $link('amazon-visibility','Amazon Book Visibility') . ', ' . $link('amazon-ads','Amazon Ads Setup & Management') . ', ' . $link('launch-promotion','Book Launch & Promotion') . ' or ' . $link('author-platform','Author Platform') . '. The choice follows the diagnosis, rather than a fixed list of tactics.', 'insight'=>'book-discovery'),
        'publishing-support'=>array('case'=>'case-luma', 'context'=>'See the cover and interior files prepared for Luma. This project ended at file delivery; KDP upload and publication were not part of that work.', 'copy'=>'Prepare the right files through ' . $link('book-formatting','Book Formatting') . ' and ' . $link('cover-design','Book Cover Design') . '. Once the book is ready to be listed, ' . $link('amazon-visibility','Amazon Book Visibility') . ' addresses how it is presented to readers.', 'faq'=>array('kdp','unpublished')),
        'catalog-strategy'=>array('copy'=>'A connected catalog needs clear routes between books. ' . $link('author-platform','Author Platform') . ' supports those connections, ' . $link('book-strategy','Book Marketing Strategy') . ' sets priorities, and ' . $link('launch-promotion','Book Launch & Promotion') . ' considers the next release.', 'insight'=>'connected-catalog'),
    );
}
function gpc_service_journey($key) {
    $connections = gpc_service_connections();
    if (!isset($connections[$key])) { return ''; }
    $data = $connections[$key];
    $proof = isset($data['case']) ? gpc_case_card($data['case'], true, $data['context'] ?? '') : '';
    $html = '<section class="gp-section gp-tone-paper gp-journey" data-gpc-journey="' . esc_attr($key) . '"><div class="gp-wrap"><div class="gp-label"><p>' . ($proof ? 'SEE THE WORK IN CONTEXT' : 'PLAN THE NEXT STEP') . '</p></div><div class="gp-heading"><h2>' . ($proof ? 'How this works in practice' : 'Where this service fits') . '</h2></div><div class="gp-journey-grid' . ($proof ? '' : ' gp-journey-single') . '">' . $proof . '<div class="gp-journey-copy"><h3>Connect the right support</h3><p>' . $data['copy'] . '</p>';
    if (isset($data['insight'])) { $html .= '<p>Related insight: ' . gpc_journey_link($data['insight'], gpc_pages()[$data['insight']]['title']) . '.</p>'; }
    $html .= '<p>A free preliminary assessment is based on public information and details you choose to share. For an unpublished book, tell us its stage and the help you need.</p>' . gpc_journey_assessment();
    if (!empty($data['faq'])) { $html .= '<h3 class="gp-journey-faq-heading">Before you begin</h3>' . gpc_faq_group($data['faq']); }
    return $html . '<p class="gp-small">' . gpc_journey_link('faq','Read all frequently asked questions') . '</p></div></div></div></section>';
}
function gpc_article_connections($content, $key) {
    $link = 'gpc_journey_link';
    $maps = array(
        'book-discovery'=>array(
            'Consistency does not mean making every book look identical. It means giving the reader enough reliable signals to judge whether the book is relevant to them.'=>'Our ' . $link('amazon-visibility','Amazon Book Visibility service') . ' reviews these public-facing signals and identifies practical listing priorities.',
            'The useful question is not simply whether the book needs more exposure. It is whether the page is ready to make that exposure meaningful.'=>'A wider ' . $link('book-strategy','Book Marketing Strategy') . ' puts that diagnosis before a choice of promotional tactics.',
        ),
        'book-product-page'=>array(
            'If you use A+ Content, plan its message before designing the images. Ask what a reader needs to understand at each point. Decorative space alone does not answer a buying question.'=>'That is the focus of ' . $link('book-presentation','Book Descriptions & A+ Content') . '. ' . $link('cover-design','Book Cover Design') . ' gives the same promise a clear visual introduction.',
            'A useful review produces a short list of changes with reasons: what is unclear, what should change and how the revision better represents the actual book.'=>'Our ' . $link('amazon-visibility','Amazon Book Visibility service') . ' considers those details together, rather than treating each part of a listing in isolation.',
        ),
        'connected-catalog'=>array(
            'Review author names, series details, descriptions and the information inside each book’s back matter. Look for outdated links or an unclear next-book recommendation.'=>'An ' . $link('author-platform','Author Platform') . ' can give readers a clear place to find those connections beyond an individual retailer listing.',
            'If you have access to sales or reader data, use it to test your assumptions. Public listings can show how a catalog is presented, but they cannot prove how readers move between titles. Treat the first review as a starting point for better decisions.'=>'Use ' . $link('book-strategy','Book Marketing Strategy') . ' to set catalog priorities, and ' . $link('launch-promotion','Book Launch & Promotion') . ' to plan how a new release fits the existing work.',
        ),
    );
    if (!isset($maps[$key]) || strpos($content, 'data-gpc-article-links') !== false) { return $content; }
    $remaining = $maps[$key];
    $content = preg_replace_callback('~<p\b[^>]*>(.*?)</p>~is', function($m) use (&$remaining) {
        $text = gpc_stock_copy_text($m[1]);
        if (!isset($remaining[$text])) { return $m[0]; }
        $addition = $remaining[$text]; unset($remaining[$text]);
        return $m[0] . '<p data-gpc-article-links>' . $addition . '</p>';
    }, $content);
    // Authored paragraphs that have been changed in Elementor are never replaced.
    if ($remaining) {
        $html = '<section class="gp-section gp-reading" data-gpc-article-links><div class="gp-wrap"><div class="gp-article-body"><h2>Explore the idea further</h2>';
        foreach ($remaining as $text) { $html .= '<p>' . $text . '</p>'; }
        $content = gpc_insert_journey($content, $html . '</div></div></section>');
    }
    $service = array('book-discovery'=>'amazon-visibility','book-product-page'=>'book-presentation','connected-catalog'=>'catalog-strategy');
    return str_replace('Explore the relevant service', esc_html(gpc_pages()[$service[$key]]['title']), $content);
}
function gpc_about_journey() {
    return '<section class="gp-section gp-tone-lilac gp-journey" data-gpc-journey="about"><div class="gp-wrap"><div class="gp-label"><p>THE THINKING BEHIND GRANT</p></div><div class="gp-heading"><h2>Books built to be discovered.</h2></div><div class="gp-journey-grid"><div class="gp-journey-copy"><h3>Publication is a beginning</h3><p>Grant Publishing Co. helps authors and publishers prepare, publish and promote books. Cover design and formatting shape the reading experience. Publishing support prepares the editions. Listing and marketing work help the right reader understand what the book offers.</p><p>A published book is available. A discoverable book also needs clear signals: who it is for, what makes it relevant and where a reader can go next. That is the thinking behind “Books built to be discovered.”</p></div><div class="gp-journey-copy"><h3>Diagnosis before tactics</h3><p>AbdulQudus Tella brings his established role as a Book Marketing Strategist to the broader services available through Grant. The work begins with the book, its intended reader and the evidence available.</p><p>Advertising can be useful, but it is not the answer to every problem. An unclear description, inconsistent presentation or unfinished print file may need attention first. We distinguish what we can observe from what needs more information, then agree a practical scope.</p><p>Explore ' . gpc_journey_link('services','our book design, publishing and marketing services') . ' or see ' . gpc_journey_link('case-studies','documented client projects') . '.</p></div></div></div></section>';
}
function gpc_feedback_case_links($content) {
    if (strpos($content, 'data-gpc-feedback-case') !== false) { return $content; }
    return preg_replace_callback('~<a\b([^>]*)>(.*?)</a>~is', function($m) {
        if (!preg_match('~\bhref\s*=\s*(["\x27])([^"\x27]+)\1~i', $m[1], $href)) { return $m[0]; }
        $path = (string) wp_parse_url(html_entity_decode($href[2], ENT_QUOTES, 'UTF-8'), PHP_URL_PATH);
        $file = basename($path);
        $keys = array('My-Dear-Grandfather-Listing-Case-Study.pdf'=>'case-grandfather','luma-sleepy-star-case-study.pdf'=>'case-luma');
        if (!isset($keys[$file])) { return $m[0]; }
        $key = $keys[$file];
        // A span is valid inside Elementor's button wrapper, without nesting anchors.
        return $m[0] . '<span class="gp-case-html-link" data-gpc-feedback-case>' . gpc_journey_link($key, 'Read the ' . gpc_case_projects()[$key]['title'] . ' project online') . '</span>';
    }, $content);
}
function gpc_render_journey($content) {
    if (!is_string($content)) { return $content; }
    $key = gpc_journey_context();
    if (!$key) { return $content; }
    if (in_array($key, array('book-discovery','book-product-page','connected-catalog'), true)) { return gpc_article_connections($content, $key); }
    if ($key === 'feedback') { return gpc_feedback_case_links($content); }
    if (strpos($content, 'data-gpc-journey=') !== false) { return $content; }
    if ($key === 'about') { return gpc_insert_journey($content, gpc_about_journey()); }
    if ($key === 'services') {
        return gpc_insert_journey($content, '<section class="gp-section gp-tone-paper gp-journey" data-gpc-journey="services"><div class="gp-wrap"><div class="gp-label"><p>BEFORE YOU CHOOSE</p></div><div class="gp-heading"><h2>See the work. Understand the scope.</h2></div><div class="gp-copy"><p>Read ' . gpc_journey_link('case-studies','our documented case studies') . ' to see what was delivered and what the evidence supports. For practical questions about formats, account work and project arrangements, visit ' . gpc_journey_link('faq','frequently asked questions') . '.</p></div></div></section>');
    }
    $section = gpc_service_journey($key);
    return $section ? gpc_insert_journey($content, $section) : $content;
}
add_filter('the_content', 'gpc_render_journey', 38);
