<?php
if (!defined('ABSPATH')) { exit; }

/** Verified projects only. Add future projects here after their evidence is reviewed. */
function gpc_case_projects() {
    return array(
        'case-grandfather'=>array(
            'title'=>'My Dear Grandfather', 'author'=>'Barsha Rai', 'image'=>'my-dear-grandfather-302.webp', 'width'=>302, 'height'=>466,
            'scope'=>'Description · Keyword research · Category review',
            'problem'=>'Bring the story into focus on an Amazon page where plot and author information competed for attention.',
            'summary'=>'A documented listing review for Cactus Rain Publishing, with description work, keyword research and category recommendations. Ranking observations are kept separate from claims about results.',
        ),
        'case-luma'=>array(
            'title'=>'Luma the Sleepy Star', 'author'=>'John Capon', 'image'=>'luma-final-800.webp', 'width'=>800, 'height'=>800,
            'scope'=>'Cover redesign · Interior formatting',
            'problem'=>'Bring an existing cover direction and illustrated manuscript together as a consistent paperback print package.',
            'summary'=>'A coordinated cover wrap and 35-page interior for a children’s picture book. The project ended at delivery of the print PDFs, before publishing or KDP upload.',
        ),
    );
}
function gpc_case_card($key, $compact = false, $context = '') {
    $projects = gpc_case_projects();
    if (!isset($projects[$key])) { return ''; }
    $p = $projects[$key];
    $image = $p['image'];
    // The existing final-cover rendition is shared with the book showcase.
    $srcset = $key === 'case-luma' ? ' srcset="' . esc_url(gpc_asset_url('luma-final-480.webp')) . ' 480w, ' . esc_url(gpc_asset_url('luma-final-800.webp')) . ' 800w" sizes="(max-width: 720px) 220px, 240px"' : '';
    return '<article class="gp-work-card' . ($compact ? ' gp-work-card-compact' : '') . '"><div class="gp-work-image"><img src="' . esc_url(gpc_asset_url($image)) . '"' . $srcset . ' alt="' . esc_attr($p['title'] . ' book cover, by ' . $p['author']) . '" width="' . $p['width'] . '" height="' . $p['height'] . '" loading="lazy" decoding="async"></div><div class="gp-work-copy"><p class="gp-label">' . esc_html($p['scope']) . '</p><h3>' . esc_html($p['title']) . '</h3><p>' . esc_html($compact ? ($context ?: $p['summary']) : $p['problem']) . '</p><a class="gp-button gp-button-secondary" href="' . esc_url(gpc_url($key)) . '">View ' . esc_html($p['title']) . ' case study</a></div></article>';
}
function gpc_case_cards() {
    $cards = '';
    foreach (gpc_case_projects() as $key=>$project) { $cards .= gpc_case_card($key); }
    return '<div class="gp-work-grid">' . $cards . '</div>';
}
add_shortcode('grant_case_cards', 'gpc_case_cards');

/** Create only the four requested pages. Never replace saved content or SEO fields. */
function gpc_create_evidence_pages($force = false) {
    if (!current_user_can('manage_options') || !current_user_can('publish_pages')) { return false; }
    if (!$force && get_option('gpc_evidence_pages_version', '') === '4.4.0') { return false; }
    $lock_key = 'gpc_evidence_pages_lock';
    $old = get_option($lock_key, false);
    if (is_array($old) && ($old['started_at'] ?? time()) < time() - 300 && get_option($lock_key, false) === $old) { delete_option($lock_key); }
    $lock = array('started_at'=>time(), 'token'=>wp_generate_uuid4());
    if (!add_option($lock_key, $lock, '', false)) { return false; }
    $results = array(); $errors = false; $created = false;
    try {
        $defs = gpc_pages(); $map = get_option('gpc_page_ids', array());
        if (!is_array($map)) { $map = array(); }
        foreach (array('case-studies','faq','case-grandfather','case-luma') as $key) {
            $def = $defs[$key]; $parent_id = 0;
            if (!empty($def['parent'])) {
                $parent = gpc_find_page($def['parent']);
                if (!$parent || $parent->post_status !== 'publish' || !gpc_matches_page($parent, $def['parent'])) {
                    $results[] = $def['title'] . ': first connect and publish the Case Studies hub. Existing content was preserved.'; $errors = true; continue;
                }
                $parent_id = (int) $parent->ID;
            }
            $page = gpc_find_page($key);
            $destination = get_page_by_path(gpc_page_path($key), OBJECT, 'page');
            if ($destination && $page && (int) $destination->ID !== (int) $page->ID) {
                $results[] = $def['title'] . ': another page occupies the required URL. Review it before retrying.'; $errors = true; continue;
            }
            if ($page) {
                if ($page->post_status !== 'publish' || !gpc_matches_page($page, $key) || gpc_relative_route(get_permalink($page->ID)) !== gpc_page_path($key)) {
                    $results[] = $def['title'] . ': existing content, status and URL preserved. Review and connect the page before retrying.'; $errors = true; continue;
                }
                $map[$key] = (int) $page->ID;
                $results[] = $def['title'] . ': already connected; saved content and Yoast metadata preserved.';
                continue;
            }
            $id = wp_insert_post(array('post_type'=>'page', 'post_status'=>'publish', 'post_title'=>$def['title'], 'post_name'=>$def['slug'], 'post_parent'=>$parent_id, 'post_content'=>'[' . $def['shortcode'] . ']', 'meta_input'=>array('_gpc_page_key'=>$key, '_wp_page_template'=>'gpc-full-page.php')), true);
            if (is_wp_error($id) || !$id) { $results[] = $def['title'] . ': could not be created. Please retry.'; $errors = true; continue; }
            $map[$key] = (int) $id; $created = true;
            update_option('gpc_page_ids', $map, false);
            $results[] = $def['title'] . ': created using the shared Grant components.';
        }
        update_option('gpc_page_ids', $map, false);
        if ($created) {
            foreach (gpc_pages() as $key=>$def) {
                $page = gpc_find_page($key);
                if ($page && $page->post_status === 'publish' && gpc_matches_page($page, $key)) {
                    gpc_clear_elementor_page_cache($page->ID);
                    do_action('litespeed_purge_url', get_permalink($page->ID));
                }
            }
        }
        if (!$errors) { update_option('gpc_evidence_pages_version', '4.4.0', false); }
        update_option('gpc_evidence_pages_results', $results, false);
        return !$errors;
    } catch (\Throwable $error) {
        update_option('gpc_evidence_pages_results', array_merge($results, array('Page setup was interrupted. Retry from Grant Website Setup.')));
        return false;
    } finally {
        if (get_option($lock_key, false) === $lock) { delete_option($lock_key); }
    }
}
add_action('admin_init', 'gpc_create_evidence_pages', 95);
add_action('admin_post_gpc_evidence_pages', function() {
    gpc_admin_post_guard('gpc_evidence_pages');
    gpc_create_evidence_pages(true);
    gpc_setup_results(get_option('gpc_evidence_pages_results', array()));
});

/** Fill only absent new-page social descriptions; Yoast remains authoritative. */
function gpc_case_social_description($description, $field) {
    $key = gpc_current_key();
    if (!in_array($key, array('case-studies','case-grandfather','case-luma','faq'), true) || trim((string) $description) !== '') { return $description; }
    if (trim((string) get_post_meta(get_queried_object_id(), '_yoast_wpseo_' . $field, true)) !== '') { return $description; }
    return gpc_pages()[$key]['description'];
}
add_filter('wpseo_opengraph_desc', function($description) { return gpc_case_social_description($description, 'opengraph-description'); }, 20);
add_filter('wpseo_twitter_description', function($description) { return gpc_case_social_description($description, 'twitter-description'); }, 20);
