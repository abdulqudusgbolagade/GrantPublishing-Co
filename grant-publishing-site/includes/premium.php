<?php
if (!defined('ABSPATH')) { exit; }

/** Presentation upgrades run on connected public pages, never on saved content. */
function gpc_project_enquiry_url($service = '', $project = '') {
    $args = array('request'=>'project');
    if (in_array($service, gpc_services(), true)) { $args['service'] = $service; }
    if (in_array($project, array('luma','grandfather','kathryns','formatting-samples'), true)) { $args['project'] = $project; }
    return add_query_arg($args, gpc_url('contact')) . '#gpc-enquiry';
}
function gpc_selected_work($home = true) {
    $projects = array(
        array('title'=>'Luma the Sleepy Star','author'=>'John Capon','tag'=>'COVER REDESIGN & INTERIOR FORMATTING','image'=>'luma-final-480.webp','width'=>480,'height'=>480,'copy'=>'An illustrated manuscript brought together as a cover wrap and 35-page paperback interior. Delivered as print PDFs.','url'=>gpc_url('case-luma'),'link'=>'Explore the Luma project'),
        array('title'=>'My Dear Grandfather','author'=>'Barsha Rai · Cactus Rain Publishing','tag'=>'AMAZON LISTING OPTIMISATION','image'=>'my-dear-grandfather-302.webp','width'=>302,'height'=>466,'copy'=>'Description work, keyword research and category recommendations, with a careful distinction between observations and outcomes.','url'=>gpc_url('case-grandfather'),'link'=>'Explore the listing project'),
        array('title'=>'Kathryn’s Beach','author'=>'Nadine Laman · Cactus Rain Publishing','tag'=>'APPROVED A+ CONTENT EXAMPLE','image'=>'kathryns-beach-aplus-600.webp','width'=>600,'height'=>606,'copy'=>'A coordinated presentation of the story, author and related titles. An approved deliverable example, without a performance claim.','url'=>gpc_url('book-presentation') . '#kathryns-aplus-example','link'=>'View the A+ Content example'),
    );
    $html = '<section class="gp-section gp-selected-work' . ($home ? '' : ' gp-work-aplus-feature') . '" id="selected-work" data-gpc-selected-work><div class="gp-wrap"><div class="gp-editorial-heading"><div><p class="gp-label">' . ($home ? 'SELECTED WORK' : 'BOOK PRESENTATION') . '</p><div class="gp-heading"><h2>' . ($home ? 'Real books.<br><em>Considered work.</em>' : 'A+ Content,<br>in context.') . '</h2></div></div><div class="gp-copy"><p>Different books call for different support. Explore the decisions, deliverables and scope behind the work.</p><a class="gp-text-link" href="' . esc_url(gpc_url('case-studies')) . '">Explore all our work ' . gpc_direction_icon() . '</a></div></div><div class="gp-selected-grid">';
    if (!$home) {
        $html = str_replace('<a class="gp-text-link" href="' . esc_url(gpc_url('case-studies')) . '">Explore all our work ' . gpc_direction_icon() . '</a>', '', $html);
    }
    foreach ($projects as $i=>$project) {
        if (!$home && $i !== 2) { continue; }
        $html .= '<article class="gp-selected-card gp-selected-card-' . $i . '"><a class="gp-selected-image" href="' . esc_url($project['url']) . '" aria-label="' . esc_attr($project['link']) . '"><img src="' . esc_url(gpc_asset_url($project['image'])) . '" width="' . $project['width'] . '" height="' . $project['height'] . '" alt="' . esc_attr($project['title'] . ($i === 2 ? ' approved A+ Content screenshot' : ' book cover')) . '" loading="lazy" decoding="async"></a><div class="gp-selected-copy"><p class="gp-label">' . esc_html($project['tag']) . '</p><h3>' . esc_html($project['title']) . '</h3><p class="gp-project-author">' . esc_html($project['author']) . '</p><p>' . esc_html($project['copy']) . '</p><a class="gp-text-link" href="' . esc_url($project['url']) . '">' . esc_html($project['link']) . ' ' . gpc_direction_icon() . '</a></div></article>';
    }
    return $html . '</div></div></section>';
}
function gpc_home_service_overview() {
    $categories = array(
        array('Book Design and Production','Readable interiors, genre-aware covers and appropriately prepared ebook and print files.','book-formatting','Book formatting','cover-design','Cover design'),
        array('Publishing and Launch Support','Edition preparation, agreed KDP setup and a clear plan for bringing your book to market.','publishing-support','Publishing support','launch-promotion','Launch support'),
        array('Amazon Visibility and Optimisation','Help readers understand your book through considered metadata, descriptions and A+ Content.','amazon-visibility','Amazon visibility','book-presentation','Descriptions & A+ Content'),
        array('Book Marketing and Growth','Set priorities, choose suitable channels and review progress within an agreed project or management scope.','book-strategy','Marketing strategy','amazon-ads','Amazon Ads'),
    );
    $html = '<section class="gp-section gp-tone-paper" id="services" data-gpc-service-overview><div class="gp-wrap"><div class="gp-editorial-heading"><div><p class="gp-label">OUR EXPERTISE</p><div class="gp-heading"><h2>From manuscript<br>to <em>market.</em></h2></div></div><div class="gp-copy"><p>Design, production and discoverability belong in the same conversation. Choose focused support for one stage or agree a connected scope.</p><a class="gp-text-link" href="' . esc_url(gpc_url('services')) . '">Explore all ten services ' . gpc_direction_icon() . '</a></div></div><div class="gp-category-grid">';
    foreach ($categories as $i=>$category) {
        $html .= '<article class="gp-category"><span class="gp-category-number" aria-hidden="true">0' . ($i + 1) . '</span><h3>' . esc_html($category[0]) . '</h3><p>' . esc_html($category[1]) . '</p><div class="gp-category-links"><a href="' . esc_url(gpc_url($category[2])) . '">' . esc_html($category[3]) . ' ' . gpc_direction_icon() . '</a><a href="' . esc_url(gpc_url($category[4])) . '">' . esc_html($category[5]) . ' ' . gpc_direction_icon() . '</a></div></article>';
    }
    return $html . '</div></div></section>';
}
function gpc_work_samples_feature() {
    return '<section class="gp-section gp-tone-lilac gp-work-samples" id="interior-design" data-gpc-work-samples><div class="gp-wrap"><div class="gp-split"><div><p class="gp-label">BOOK FORMATTING & INTERIOR DESIGN</p><div class="gp-heading"><h2>The detail<br>inside the book.</h2></div><p>Title pages, chapter openings and reading spreads show how typography and spacing shape the interior.</p><p>These owner-supplied formatting samples show individual layouts. They are not additional case studies or evidence of publication.</p><a class="gp-button" href="' . esc_url(gpc_url('book-formatting') . '#formatting-samples') . '">Explore the formatting samples</a></div><figure><a href="' . esc_url(gpc_url('book-formatting') . '#formatting-samples') . '"><img src="' . esc_url(gpc_asset_url('formatting-seventh-chapter-two.webp')) . '" width="723" height="539" alt="The Seventh Binding formatting sample: chapter two opening and facing text page" loading="lazy" decoding="async"></a><figcaption>An interior formatting sample. Author and publication details have not been supplied.</figcaption></figure></div></div></section>';
}
function gpc_service_hero_evidence($key) {
    $visuals = array(
        'book-formatting'=>array('formatting-seventh-chapter-two.webp',723,539,'The Seventh Binding: chapter two opening and facing text page','Interior formatting sample. Explore chapter openings, front matter and text spreads.','book-formatting','#formatting-samples'),
        'cover-design'=>array('luma-cover-wrap-760.webp',760,0,'Luma the Sleepy Star full paperback cover wrap','Luma the Sleepy Star. Cover redesign and print-cover preparation for John Capon.','case-luma',''),
        'publishing-support'=>array('luma-interior-spread-720.webp',720,0,'Luma the Sleepy Star formatted print interior spread','Print-file preparation for Luma. This documented project ended at file delivery, before KDP upload or publication.','case-luma',''),
        'amazon-visibility'=>array('grandfather-keyword-research-720.webp',720,0,'My Dear Grandfather keyword research and recommendations','A research deliverable from My Dear Grandfather. Recommendations are distinct from saved KDP settings and later ranking observations.','case-grandfather',''),
        'book-presentation'=>array('kathryns-beach-aplus-600.webp',600,606,'Kathryn’s Beach approved A+ Content example','Kathryn’s Beach by Nadine Laman. A supplied A+ Content example approved for public display.','book-presentation','#kathryns-aplus-example'),
    );
    if (!isset($visuals[$key])) { return ''; }
    $v = $visuals[$key];
    if (!$v[2]) { $size = getimagesize(dirname(__DIR__) . '/assets/' . $v[0]); $v[2] = $size[1]; }
    $sets = array('cover-design'=>array('luma-cover-wrap-760.webp'=>760,'luma-cover-wrap-1280.webp'=>1280,'luma-cover-wrap-1900.webp'=>1900), 'publishing-support'=>array('luma-interior-spread-720.webp'=>720,'luma-interior-spread-1400.webp'=>1400), 'amazon-visibility'=>array('grandfather-keyword-research-720.webp'=>720,'grandfather-keyword-research-1103.webp'=>1103));
    $responsive = '';
    if (isset($sets[$key])) {
        $candidates = array(); foreach ($sets[$key] as $file=>$width) { $candidates[] = gpc_asset_url($file) . ' ' . $width . 'w'; }
        $responsive = ' srcset="' . esc_attr(implode(', ', $candidates)) . '" sizes="(max-width:860px) calc(100vw - 112px), (max-width:1600px) 38vw, 574px"';
    }
    return '<div class="gp-service-evidence"><figure><a href="' . esc_url(gpc_url($v[5]) . $v[6]) . '"><img src="' . esc_url(gpc_asset_url($v[0])) . '"' . $responsive . ' width="' . $v[1] . '" height="' . $v[2] . '" alt="' . esc_attr($v[3]) . '" fetchpriority="high" decoding="async"></a><figcaption>' . esc_html($v[4]) . '</figcaption></figure></div>';
}
/** Exact stock strings only. Custom authored paragraphs and metadata are untouched. */
function gpc_premium_content($content) {
    $key = is_string($content) ? gpc_journey_context() : '';
    if (!$key || in_array($key, array('privacy-policy','terms-of-service'), true) || strpos($content, 'data-gpc-premium') !== false) { return $content; }
    if (in_array($key, array('home','about'), true)) {
        $identity = 'I’m AbdulQudus Tella, Book Publishing Specialist and Founder of Grant Publishing Co.';
        foreach (array(
            'I’m AbdulQudus Tella, a Book Marketing Strategist. Through Grant Publishing Co., I help authors with book design, formatting, publishing and marketing, with clear priorities and work that fits each book.',
            'I’m AbdulQudus Tella, a Book Marketing Strategist. Through Grant Publishing Co., I offer cover design, ebook and print formatting, publishing and Amazon KDP setup, alongside Amazon visibility, marketing strategy and advertising support.'
        ) as $stock) { $content = str_replace($stock, str_replace('I’m AbdulQudus Tella, a Book Marketing Strategist.', $identity, $stock), $content); }
        $content = str_replace('alt="AbdulQudus Tella, Book Marketing Strategist"', 'alt="AbdulQudus Tella, Book Publishing Specialist"', $content);
        $content = str_replace('Strategy led by<br>', 'Publishing led by<br>', $content);
        $content = str_replace('AbdulQudus Tella brings his established role as a Book Marketing Strategist to the broader services available through Grant.', 'AbdulQudus Tella is a Book Publishing Specialist and Founder of Grant Publishing Co.', $content);
    }
    if ($key === 'home') {
        $content = preg_replace('~Bring your book to life\.\s*<br\s*/?>\s*Help it\s*<em>reach readers\.</em>~i', 'Your manuscript.<br>Professionally <em>produced.</em>', $content);
        $content = str_replace('From cover design and formatting to publishing, Amazon optimisation and advertising setup, we help authors prepare their books and connect with readers.', 'From cover design and interior formatting to Amazon KDP publishing and discoverability, we help independent authors and publishers bring their books to market with confidence.', $content);
        $content = str_replace('Discuss your book', 'Discuss your project', $content);
        $content = preg_replace_callback('~<a\b([^>]*)>(.*?)</a>~is', function($m) {
            if (trim(wp_strip_all_tags($m[2])) !== 'Explore our expertise') { return $m[0]; }
            $attrs = preg_replace('~\bhref=["\x27][^"\x27]*["\x27]~i', 'href="' . esc_url(gpc_url('case-studies')) . '"', $m[1], 1);
            return '<a' . $attrs . '>' . str_replace('Explore our expertise', 'Explore our work', $m[2]) . '</a>';
        }, $content);
        $services = gpc_content_block_bounds($content, function($tag) { return preg_match('~\bid=["\x27]services["\x27]~i', $tag); });
        if ($services && strpos(substr($content, $services['start'], $services['end'] - $services['start']), 'Book Design &amp; Formatting') !== false) {
            // Move shipped proof intact only when upgrading the shipped overview.
            $proof = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-proof'); });
            $proof_html = '';
            if ($proof) { $proof_html = substr($content, $proof['start'], $proof['end'] - $proof['start']); $content = substr_replace($content, '', $proof['start'], $proof['end'] - $proof['start']); }
            $services = gpc_content_block_bounds($content, function($tag) { return preg_match('~\bid=["\x27]services["\x27]~i', $tag); });
            if ($services) { $content = substr_replace($content, gpc_home_service_overview() . $proof_html, $services['start'], $services['end'] - $services['start']); }
        }
        $hero = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-home-hero'); });
        if ($hero && strpos($content, 'data-gpc-selected-work') === false) { $content = substr_replace($content, gpc_selected_work(), $hero['end'], 0); }
    }
    if ($key === 'case-studies') {
        $content = preg_replace('~(<h1\b[^>]*>)Case Studies(</h1>)~', '$1Our Work$2', $content);
        if (strpos($content, 'data-gpc-work-samples') === false) { $content = gpc_insert_journey($content, gpc_selected_work(false) . gpc_work_samples_feature()); }
    }
    if (isset(gpc_service_connections()[$key])) {
        $evidence = gpc_service_hero_evidence($key);
        $hero = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-service-hero'); });
        if ($evidence && $hero) {
            $inner = substr($content, $hero['inner_start'], $hero['close'] - $hero['inner_start']);
            $art = gpc_content_block_bounds($inner, function($tag) { return gpc_tag_has_class($tag, 'gp-editorial-art'); });
            if ($art && strpos(substr($inner, $art['start'], $art['end'] - $art['start']), 'gp-concept-figure') !== false) { $inner = substr_replace($inner, $evidence, $art['start'], $art['end'] - $art['start']); $content = substr_replace($content, $inner, $hero['inner_start'], $hero['close'] - $hero['inner_start']); }
        }
    }
    if (in_array($key, array('case-luma','case-grandfather'), true)) {
        $project = $key === 'case-luma' ? 'luma' : 'grandfather';
        $content = preg_replace_callback('~href=(["\x27])([^"\x27]+)\1~i', function($m) use($project) {
            $url = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
            if (strpos($url, gpc_url('contact') . '?request=project') !== 0 && strpos($url, gpc_url('enquiry') . '?request=assessment') !== 0) { return $m[0]; }
            return 'href="' . esc_url(add_query_arg('project', $project, $url)) . '"';
        }, $content);
    }
    return str_replace('id="gpc-main"', 'id="gpc-main" data-gpc-premium', $content);
}
add_filter('the_content', 'gpc_premium_content', 45);
add_shortcode('grant_premium_block', function($atts) {
    $block = is_array($atts) ? ($atts['block'] ?? '') : '';
    if ($block === 'selected') { return gpc_selected_work(); }
    if ($block === 'services') { return gpc_home_service_overview(); }
    if ($block === 'work-samples') { return gpc_selected_work(false) . gpc_work_samples_feature(); }
    return '';
});

/** Optional enquiry context is validated before it enters either delivery path. */
function gpc_project_fields() {
    return array('general'=>'General enquiry','manuscript'=>'Preparing a manuscript','published'=>'Already published','publisher'=>'Publisher or multiple titles');
}
function gpc_optional_project_values() {
    $values = array(); $stage = gpc_site_value('project_stage');
    if ($stage !== '' && !isset(gpc_project_fields()[$stage])) { gpc_site_reply(false, 'Please choose a valid project stage.', 400); }
    $values['Project stage'] = $stage === '' ? '' : gpc_project_fields()[$stage];
    foreach (array('word_count'=>'Word count','title_count'=>'Number of titles') as $field=>$label) {
        $value = gpc_site_value($field);
        if ($value !== '' && (!ctype_digit($value) || strlen($value) > 7 || (int) $value < 1 || (int) $value > 2000000)) { gpc_site_reply(false, 'Please use a whole number for word count or number of titles, or leave it blank.', 400); }
        $values[$label] = $value;
    }
    foreach (array('genre'=>'Genre','marketplace'=>'Marketplace') as $field=>$label) {
        $value = gpc_site_value($field);
        if (strlen($value) > 400) { gpc_site_reply(false, 'Please shorten the genre or marketplace field.', 400); }
        $values[$label] = sanitize_text_field($value);
    }
    $formats = isset($_POST['formats']) ? wp_unslash($_POST['formats']) : array();
    if (!is_array($formats) || count($formats) > 3 || array_filter($formats, function($format) { return !is_string($format) || !in_array($format, array('Ebook / Kindle','Paperback','Hardcover'), true); })) { gpc_site_reply(false, 'Please select a listed book format.', 400); }
    $values['Desired formats'] = implode(', ', array_unique($formats));
    $project = gpc_site_value('project_context');
    $values['Portfolio context'] = in_array($project, array('luma','grandfather','kathryns','formatting-samples'), true) ? $project : '';
    return $values;
}

/** Keep Elementor's runtime whenever an authored widget or effect needs it. */
function gpc_static_elementor_layout($nodes) {
    if (!is_array($nodes) || !$nodes) { return false; }
    foreach ($nodes as $node) {
        if (!is_array($node)) { return false; }
        if (!in_array($node['elType'] ?? '', array('container','section','column','widget'), true)) { return false; }
        $settings = $node['settings'] ?? array();
        if (!is_array($settings)) { return false; }
        foreach ($settings as $name=>$value) {
            if (preg_match('/animation|motion|parallax|sticky|video|lightbox/i', $name) && !in_array($value, array('',false,null,0,'0','none','no'), true)) { return false; }
        }
        if (($node['elType'] ?? '') === 'widget') {
            $widget = $node['widgetType'] ?? '';
            if (!in_array($widget, array('heading','text-editor','image','button','shortcode'), true)) { return false; }
            if ($widget === 'image' && ($settings['link_to'] ?? '') === 'file') { return false; }
            if ($widget === 'text-editor' && preg_match('/data-elementor-open-lightbox=["\x27]yes|elementor-(?:animation|motion|lightbox)/i', $settings['editor'] ?? '')) { return false; }
            if ($widget === 'shortcode') {
                $known = array_merge(array_column(gpc_pages(), 'shortcode'), array('grant_site_header','grant_site_footer','grant_header','grant_footer','grant_enquiry_form','grant_contact_links','grant_book_showcase','grant_case_cards','grant_faqs','grant_framework','grant_premium_block','grant_formatting_samples'));
                if (!preg_match('/\A\s*\[([a-z_]+)(?:\s[^\]]*)?\]\s*\z/', $settings['shortcode'] ?? '', $match) || !in_array($match[1], $known, true)) { return false; }
            }
        }
        if (!empty($node['elements']) && !gpc_static_elementor_layout($node['elements'])) { return false; }
    }
    return true;
}
function gpc_optimize_static_elementor_scripts() {
    if (!gpc_current_key() || is_admin() || is_preview()) { return; }
    // Other page templates may contain interactive Theme Builder components.
    if (!in_array(get_page_template_slug(get_queried_object_id()), array('gpc-full-page.php','elementor_canvas'), true)) { return; }
    if (class_exists('\\Elementor\\Plugin') && isset(\Elementor\Plugin::$instance)) {
        $elementor = \Elementor\Plugin::$instance;
        foreach (array('editor'=>'is_edit_mode','preview'=>'is_preview_mode') as $property=>$method) {
            if (isset($elementor->$property) && is_object($elementor->$property) && method_exists($elementor->$property, $method) && $elementor->$property->$method()) { return; }
        }
    }
    $nodes = json_decode((string) get_post_meta(get_queried_object_id(), '_elementor_data', true), true);
    if (!gpc_static_elementor_layout($nodes)) { return; }
    // These pages use static Elementor markup and Grant's own controls/forms.
    // Other plugins' explicit dependencies remain authoritative in WordPress.
    foreach (array('elementor-frontend','elementor-frontend-modules','elementor-webpack-runtime') as $handle) { wp_dequeue_script($handle); }
}
add_action('wp_enqueue_scripts', 'gpc_optimize_static_elementor_scripts', 1000);
// Elementor can enqueue again while rendering saved widgets after wp_head.
add_action('wp_footer', 'gpc_optimize_static_elementor_scripts', 1);
