<?php
if (!defined('ABSPATH')) { exit; }

/** Declare title ownership at the supported lifecycle point. WordPress rejects
 * add_theme_support('title-tag') after wp_loaded, so the former late calls
 * could not suppress Canvas's inline fallback. No SEO setting is changed. */
add_action('after_setup_theme', function() {
    if (defined('WPSEO_VERSION') && !current_theme_supports('title-tag')) { add_theme_support('title-tag'); }
}, 20);
/** Block-template resolution can re-add a core title callback after Yoast
 * removed it at init. Reconcile the actual output sources after resolution. */
function gpc_prepare_title_output($template) {
    $connected = gpc_current_key();
    $draft = function_exists('gpc_current_editorial_draft') ? gpc_current_editorial_draft() : '';
    $canvas = basename($template) === 'canvas.php' && strpos(str_replace('\\', '/', $template), '/elementor/') !== false;
    $grant = in_array(basename($template), array('full-page.php','draft-page.php'), true) && dirname($template) === __DIR__;
    $yoast_owns_head = defined('WPSEO_VERSION') && current_theme_supports('title-tag');
    if (!$yoast_owns_head && (!$connected && !$draft || !$canvas && !$grant)) { return $template; }
    foreach (array('_block_template_render_title_tag','_wp_render_title_tag','gutenberg_render_title_tag') as $callback) { remove_action('wp_head', $callback, 1); }
    if (!$yoast_owns_head && !$canvas) {
        // A custom Grant shell has no inline title fallback. With no title-tag
        // capability, use the core unconditional renderer; wp_get_document_title
        // still uses Yoast's approved value when Yoast is installed.
        add_action('wp_head', '_block_template_render_title_tag', 1);
    }
    // Canvas already provides one inline fallback when title support is absent.
    return $template;
}
add_filter('template_include', 'gpc_prepare_title_output', PHP_INT_MAX);

/** Confirmed authorship; publication comes from the site's original page record.
 * post_modified is deliberately not treated as a substantive editorial update. */
function gpc_insight_byline($content) {
    if (!is_string($content) || strpos($content, 'data-gpc-byline') !== false || !in_array(gpc_journey_context(), array('book-discovery','book-product-page','connected-catalog'), true)) { return $content; }
    $post = get_post(get_queried_object_id());
    $stamp = $post ? get_post_time('U', true, $post) : false;
    if (!$stamp || (int) $stamp <= 0) { return $content; }
    $bounds = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-reading-meta'); });
    if (!$bounds) { return $content; }
    $existing = gpc_stock_copy_text(substr($content, $bounds['inner_start'], $bounds['close'] - $bounds['inner_start']));
    $byline = '<p data-gpc-byline>By <a href="' . esc_url(gpc_url('about')) . '">AbdulQudus Tella</a> · <time datetime="' . esc_attr(get_post_time('c', true, $post)) . '">Published ' . esc_html(wp_date('j F Y', $stamp)) . '</time><br><span>' . esc_html($existing) . '</span></p>';
    return substr($content, 0, $bounds['inner_start']) . $byline . substr($content, $bounds['close']);
}
add_filter('the_content', 'gpc_insight_byline', 39);

function gpc_project_cta_url($service) {
    return add_query_arg(array('request'=>'project','service'=>$service), gpc_url('contact')) . '#gpc-enquiry';
}
function gpc_service_project_cta($key) {
    $services = array('book-formatting'=>array('Book formatting','Discuss book formatting'), 'cover-design'=>array('Cover design','Discuss cover design'), 'publishing-support'=>array('Book publishing and Amazon KDP setup','Discuss publishing support'), 'amazon-visibility'=>array('Amazon listing optimization','Discuss Amazon visibility'), 'book-presentation'=>array('Book descriptions and A+ Content','Discuss your book presentation'), 'amazon-ads'=>array('Amazon Ads campaign setup','Discuss Amazon Ads'), 'launch-promotion'=>array('Book launch or relaunch','Discuss your book launch'), 'author-platform'=>array('Author platform','Discuss your author platform'), 'catalog-strategy'=>array('Series and catalog strategy','Discuss your catalog'), 'book-strategy'=>array('Book marketing strategy','Discuss your book strategy'));
    if (!isset($services[$key])) { return ''; }
    return '<a class="gp-button" href="' . esc_url(gpc_project_cta_url($services[$key][0])) . '">' . esc_html($services[$key][1]) . '</a>';
}
function gpc_luma_closing_cta($content) {
    if (!is_string($content) || gpc_journey_context() !== 'case-luma') { return $content; }
    $bounds = gpc_content_block_bounds($content, function($tag) { return gpc_tag_has_class($tag, 'gp-cta'); });
    if (!$bounds) { return $content; }
    $inner = substr($content, $bounds['inner_start'], $bounds['close'] - $bounds['inner_start']);
    $inner = preg_replace_callback('~<a\b([^>]*)>(.*?)</a>~is', function($m) {
        $text = gpc_stock_copy_text($m[2]);
        $choices = array('Request a free book assessment'=>array('Cover design','Discuss cover design'), 'Explore all services'=>array('Book formatting','Discuss book formatting'));
        if (!isset($choices[$text])) { return $m[0]; }
        $choice = $choices[$text];
        $attributes = preg_replace('~\bhref\s*=\s*(["\x27])[^"\x27]*\1~i', 'href="' . esc_url(gpc_project_cta_url($choice[0])) . '"', $m[1], 1);
        $label = str_replace($text, esc_html($choice[1]), $m[2]);
        return '<a' . $attributes . '>' . $label . '</a>';
    }, $inner);
    return substr($content, 0, $bounds['inner_start']) . $inner . substr($content, $bounds['close']);
}
add_filter('the_content', 'gpc_luma_closing_cta', 39);

/** Draft only untouched English WordPress starter content; real posts/comments
 * and all manual SEO fields are preserved. The prior state is retained privately. */
function gpc_cleanup_default_content($force = false) {
    if (!current_user_can('manage_options') || !current_user_can('edit_posts')) { return false; }
    if (!$force && get_option('gpc_default_cleanup_version', '') === '4.5.0') { return false; }
    $lock_key = 'gpc_default_cleanup_lock';
    $old = get_option($lock_key, false);
    if (is_array($old) && ($old['started_at'] ?? time()) < time() - 300 && get_option($lock_key, false) === $old) { delete_option($lock_key); }
    $lock = array('started_at'=>time(), 'token'=>wp_generate_uuid4());
    if (!add_option($lock_key, $lock, '', false)) { return false; }
    $results = array(); $ok = true;
    try {
        $post = get_page_by_path('hello-world', OBJECT, 'post');
        $starter = 'Welcome to WordPress. This is your first post. Edit or delete it, then start writing!';
        if ($post && $post->post_status === 'publish') {
            $comments = get_comments(array('post_id'=>$post->ID,'status'=>'approve'));
            $sample_ids = array(); $genuine = false;
            foreach ($comments as $comment) {
                $sample = $comment->comment_author === 'A WordPress Commenter' && $comment->comment_author_url === 'https://wordpress.org/' && gpc_stock_copy_text($comment->comment_content) === 'Hi, this is a comment. To get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard. Commenter avatars come from Gravatar.';
                if ($sample) { $sample_ids[] = (int) $comment->comment_ID; } else { $genuine = true; }
            }
            $default_category = (int) get_option('default_category');
            $categories = wp_get_post_categories($post->ID);
            $term = get_term($default_category, 'category');
            $untouched = $post->post_title === 'Hello world!' && gpc_stock_copy_text($post->post_content) === $starter && !$genuine && $post->post_password === '' && !get_post_thumbnail_id($post->ID) && !get_post_meta($post->ID, '_elementor_data', true) && $categories === array($default_category) && $term && !is_wp_error($term) && $term->slug === 'uncategorized';
            if (!$untouched) {
                $results[] = 'Hello World: existing content, discussion or category use needs review. No post or comment was changed.'; $ok = false;
            } else {
                $backup = get_option('gpc_default_content_backup', false);
                if ($backup === false) {
                    $backup = array('post'=>get_object_vars($post),'comments'=>array(),'saved_at'=>current_time('mysql'));
                    foreach ($sample_ids as $id) { $c = get_comment($id); $backup['comments'][$id] = $c->comment_approved; }
                    if (!add_option('gpc_default_content_backup', $backup, '', false)) { $results[] = 'Default content backup could not be saved. Nothing was changed.'; $ok = false; }
                }
                if ($ok) {
                    $changed = wp_update_post(array('ID'=>$post->ID,'post_status'=>'draft'), true);
                    if (is_wp_error($changed) || !$changed) { $results[] = 'The starter post could not be drafted. Retry after reviewing the backup.'; $ok = false; }
                    else {
                        foreach ($sample_ids as $id) { wp_set_comment_status($id, 'hold'); }
                        update_option('gpc_default_archive_scope', array('author'=>(int) $post->post_author, 'category'=>$default_category), false);
                        $results[] = 'Untouched Hello World post moved to Draft; its sample comment held. Original state is backed up. Empty associated archives are excluded and noindexed only while unused.';
                        if (class_exists('WPSEO_Sitemaps_Cache')) { WPSEO_Sitemaps_Cache::clear(); }
                        do_action('litespeed_purge_post', $post->ID);
                    }
                }
            }
        } else { $results[] = 'No published Hello World starter post needs cleanup. Existing drafts and other content were preserved.'; }
        if ($ok) { update_option('gpc_default_cleanup_version', '4.5.0', false); }
        update_option('gpc_default_cleanup_results', $results, false);
        return $ok;
    } catch (\Throwable $error) {
        update_option('gpc_default_cleanup_results', array_merge($results, array('Default-content cleanup was interrupted. The backup is retained; review and retry.')), false);
        return false;
    } finally { if (get_option($lock_key, false) === $lock) { delete_option($lock_key); } }
}
add_action('admin_init', 'gpc_cleanup_default_content', 96);
add_action('admin_post_gpc_default_cleanup', function() {
    gpc_admin_post_guard('gpc_default_cleanup');
    gpc_cleanup_default_content(true);
    gpc_setup_results(get_option('gpc_default_cleanup_results', array()));
});
function gpc_default_archive_unused($kind, $id) {
    $scope = get_option('gpc_default_archive_scope', array());
    if (!$id || (int) ($scope[$kind] ?? 0) !== (int) $id) { return false; }
    $query = array('post_type'=>'post','post_status'=>'publish','numberposts'=>1,'fields'=>'ids');
    $query[$kind === 'author' ? 'author' : 'cat'] = (int) $id;
    return !get_posts($query);
}
function gpc_unused_archive_request() {
    if (is_author()) { return gpc_default_archive_unused('author', get_queried_object_id()); }
    if (is_category()) { return gpc_default_archive_unused('category', get_queried_object_id()); }
    return false;
}
add_filter('wp_robots', function($robots) { if (gpc_unused_archive_request()) { unset($robots['index']); $robots['noindex'] = true; } return $robots; });
add_filter('wpseo_robots_array', function($robots) { if (gpc_unused_archive_request()) { $robots['index'] = 'noindex'; } return $robots; });
add_filter('wpseo_exclude_from_sitemap_by_term_ids', function($ids) {
    $scope = get_option('gpc_default_archive_scope', array()); $id = (int) ($scope['category'] ?? 0);
    return gpc_default_archive_unused('category', $id) ? array_values(array_unique(array_merge($ids, array($id)))) : $ids;
});
add_filter('wpseo_sitemap_exclude_author', function($users) {
    return array_values(array_filter($users, function($user) { return !gpc_default_archive_unused('author', (int) $user->ID); }));
});
add_filter('wp_sitemaps_taxonomies_query_args', function($args, $taxonomy) {
    $scope = get_option('gpc_default_archive_scope', array()); $id = (int) ($scope['category'] ?? 0);
    if ($taxonomy === 'category' && gpc_default_archive_unused('category', $id)) { $args['exclude'] = array_values(array_unique(array_merge((array) ($args['exclude'] ?? array()), array($id)))); }
    return $args;
}, 10, 2);
add_filter('wp_sitemaps_users_query_args', function($args) {
    $scope = get_option('gpc_default_archive_scope', array()); $id = (int) ($scope['author'] ?? 0);
    if (gpc_default_archive_unused('author', $id)) { $args['exclude'] = array_values(array_unique(array_merge((array) ($args['exclude'] ?? array()), array($id)))); }
    return $args;
});
