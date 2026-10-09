<?php
if (!defined('ABSPATH')) { exit; }

/** Refresh only connected Grant pages after an asset version changes. */
function gpc_refresh_site_assets() {
    if (get_option('gpc_assets_refreshed_version', '') === GPC_VERSION) { return false; }

    // add_option is atomic: concurrent requests normally leave one refresher.
    // Recover a lock left by an interrupted request after five minutes.
    $lock_key = 'gpc_asset_refresh_lock';
    $previous_lock = get_option($lock_key, false);
    if (is_array($previous_lock) && isset($previous_lock['started_at']) && (int) $previous_lock['started_at'] < time() - 300) {
        if (get_option($lock_key, false) === $previous_lock) { delete_option($lock_key); }
    }
    $lock = array('started_at' => time(), 'token' => wp_generate_uuid4());
    if (!add_option($lock_key, $lock, '', false)) { return false; }

    try {
        // Another request may have completed while this request acquired its lock.
        if (get_option('gpc_assets_refreshed_version', '') === GPC_VERSION) { return false; }
        $seen_ids = array();
        $seen_urls = array();
        foreach (gpc_pages() as $key => $definition) {
            $page = gpc_find_page($key);
            if (!$page || $page->post_type !== 'page' || $page->post_status !== 'publish' || !gpc_matches_page($page, $key)) { continue; }
            $id = (int) $page->ID;
            if (!$id || isset($seen_ids[$id])) { continue; }
            $seen_ids[$id] = true;

            // Deletes generated Elementor CSS/output caches and cleans the WordPress
            // post cache. It never saves post content or the editable layout data.
            gpc_clear_elementor_page_cache($id);

            $url = get_permalink($id);
            if (!is_string($url) || $url === '' || isset($seen_urls[$url])) { continue; }
            $seen_urls[$url] = true;
            // LiteSpeed's documented URL hook is deliberately narrower than its
            // post hook, which can also invalidate configured archive/REST tags.
            // It is harmless when LiteSpeed Cache is not installed.
            do_action('litespeed_purge_url', $url);
        }
        update_option('gpc_assets_refreshed_version', GPC_VERSION, false);
        return true;
    } catch (\Throwable $error) {
        // Keep the old marker so a later request can retry the interrupted refresh.
        return false;
    } finally {
        if (get_option($lock_key, false) === $lock) { delete_option($lock_key); }
    }
}
// Elementor and cache plugins have initialized before this late init callback.
add_action('init', 'gpc_refresh_site_assets', 100);
