<?php
if (!defined('ABSPATH')) { exit; }

function gpc_editorial_draft_definitions() {
    return array(
        'listing-analysis'=>array('title'=>'What a listing review can establish: My Dear Grandfather', 'slug'=>'my-dear-grandfather-listing-analysis'),
        'ads-readiness'=>array('title'=>'Is your book ready for Amazon Ads?', 'slug'=>'amazon-ads-readiness'),
    );
}
function gpc_current_editorial_draft() {
    if (!is_singular('page')) { return ''; }
    $post = get_post(get_queried_object_id());
    $key = $post ? get_post_meta($post->ID, '_gpc_editorial_draft', true) : '';
    return isset(gpc_editorial_draft_definitions()[$key]) ? $key : '';
}
add_filter('template_include', function($template) {
    return gpc_current_editorial_draft() && get_page_template_slug() === 'gpc-full-page.php' ? __DIR__ . '/draft-page.php' : $template;
}, 99);
add_action('wp_enqueue_scripts', function() { if (gpc_current_editorial_draft()) { gpc_enqueue_assets(''); } }, 100);

/** Reviewable drafts only. Existing drafts, approvals and manual SEO are never replaced. */
function gpc_prepare_editorial_drafts($force = false) {
    if (!current_user_can('manage_options') || !current_user_can('edit_pages')) { return false; }
    if (!$force && get_option('gpc_editorial_drafts_version', '') === '4.5.0') { return false; }
    $parent = gpc_find_page('insights');
    if (!$parent || $parent->post_status !== 'publish' || !gpc_matches_page($parent, 'insights')) { return false; }
    $lock_key = 'gpc_editorial_drafts_lock'; $old = get_option($lock_key, false);
    if (is_array($old) && ($old['started_at'] ?? time()) < time() - 300 && get_option($lock_key, false) === $old) { delete_option($lock_key); }
    $lock = array('started_at'=>time(),'token'=>wp_generate_uuid4());
    if (!add_option($lock_key, $lock, '', false)) { return false; }
    $results = array(); $ok = true;
    try {
        $ids = get_option('gpc_editorial_draft_ids', array());
        if (!is_array($ids)) { $ids = array(); }
        foreach (gpc_editorial_draft_definitions() as $key=>$def) {
            $page = !empty($ids[$key]) ? get_post((int) $ids[$key]) : get_page_by_path('insights/' . $def['slug'], OBJECT, 'page');
            if ($page) {
                $results[] = $def['title'] . ': existing content and status preserved.';
                if (get_post_meta($page->ID, '_gpc_editorial_draft', true) === $key && $page->post_type === 'page' && (int) $page->post_parent === (int) $parent->ID && $page->post_status !== 'trash') { $ids[$key] = (int) $page->ID; }
                else { $results[] = 'Review the existing URL or draft mapping before retrying; no content was replaced.'; $ok = false; }
                continue;
            }
            $file = dirname(__DIR__) . '/drafts/' . $key . '.php';
            if (!is_readable($file)) { $results[] = $def['title'] . ': source draft unavailable; no page was created.'; $ok = false; continue; }
            $source = require $file;
            if (!is_string($source)) { $results[] = $def['title'] . ': invalid source; no page was created.'; $ok = false; continue; }
            $body = gpc_resolve_string($source, true);
            $id = wp_insert_post(array('post_type'=>'page','post_status'=>'draft','post_title'=>$def['title'],'post_name'=>$def['slug'],'post_parent'=>$parent->ID,'post_content'=>$body,'meta_input'=>array('_gpc_editorial_draft'=>$key,'_wp_page_template'=>'gpc-full-page.php')), true);
            if (is_wp_error($id) || !$id) { $results[] = $def['title'] . ': could not be saved; please retry.'; $ok = false; continue; }
            $ids[$key] = (int) $id;
            update_option('gpc_editorial_draft_ids', $ids, false);
            $results[] = $def['title'] . ': saved as an unpublished Insights draft for review.';
        }
        if ($ok) { update_option('gpc_editorial_drafts_version', '4.5.0', false); }
        update_option('gpc_editorial_draft_results', $results, false);
        return $ok;
    } catch (\Throwable $error) {
        update_option('gpc_editorial_draft_results', array_merge($results,array('Draft preparation was interrupted. Existing content is preserved; retry.')),false);
        return false;
    } finally { if (get_option($lock_key,false)===$lock) { delete_option($lock_key); } }
}
add_action('admin_init', 'gpc_prepare_editorial_drafts', 97);
add_action('admin_post_gpc_prepare_drafts', function() {
    gpc_admin_post_guard('gpc_prepare_drafts');gpc_prepare_editorial_drafts(true);gpc_setup_results(get_option('gpc_editorial_draft_results',array()));
});

/** Reviewed drafts join the existing index only after the owner publishes them. */
function gpc_index_approved_editorial($content) {
    if (!is_string($content) || gpc_journey_context() !== 'insights' || strpos($content, 'data-gpc-approved-insight') !== false) { return $content; }
    $parent = gpc_find_page('insights'); $rows = '';
    foreach (get_option('gpc_editorial_draft_ids', array()) as $key=>$id) {
        $page = get_post((int) $id);
        if (!$page || $page->post_status !== 'publish' || (int) $page->post_parent !== (int) $parent->ID || get_post_meta($page->ID, '_gpc_editorial_draft', true) !== $key) { continue; }
        $url = get_permalink($page->ID);
        if (strpos($content, esc_url($url)) !== false) { continue; }
        $rows .= '<div class="gp-article-row gp-approved-article" data-gpc-approved-insight><div class="gp-label"><p>INSIGHT</p></div><div class="gp-article-info"><div class="gp-row-title"><h2><a class="gp-title-link" href="' . esc_url($url) . '">' . esc_html($page->post_title) . '</a></h2></div><div class="gp-action gp-secondary"><a class="elementor-button" href="' . esc_url($url) . '"><span class="elementor-button-text">Read article</span></a></div></div></div>';
    }
    if (!$rows) { return $content; }
    $bounds = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-insight-index'); });
    return $bounds ? substr($content, 0, $bounds['close']) . $rows . substr($content, $bounds['close']) : $content;
}
add_filter('the_content', 'gpc_index_approved_editorial', 39);
