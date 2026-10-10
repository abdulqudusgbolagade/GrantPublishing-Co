<?php
if (!defined('ABSPATH')) { exit; }

/** Find an existing Grant page even when its WordPress parent is incorrect. */
function gpc_structure_page($key) {
    $page = gpc_find_page($key);
    if ($page) { return $page; }
    $defs = gpc_pages();
    if (!isset($defs[$key])) { return null; }
    $matches = array();
    foreach (get_posts(array('post_type'=>'page', 'name'=>$defs[$key]['slug'], 'post_status'=>array('publish','draft','pending','private','future'), 'numberposts'=>-1)) as $candidate) {
        if (gpc_matches_page($candidate, $key)) { $matches[] = $candidate; }
    }
    return count($matches) === 1 ? $matches[0] : null;
}

/** Paths are relative to this WordPress installation, including subdirectories. */
function gpc_relative_route($url) {
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    $base = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($base !== '') {
        if (strpos($path, $base . '/') !== 0) { return ''; }
        $path = substr($path, strlen($base) + 1);
    }
    return $path;
}

/** Repair only the requested missing services and connected editorial parents. */
function gpc_repair_page_structure($force = false) {
    if (!current_user_can('manage_options') || !current_user_can('publish_pages')) { return false; }
    if (!$force && get_option('gpc_structure_repaired_version', '') === GPC_VERSION) { return false; }
    $lock_key = 'gpc_structure_repair_lock';
    $old_lock = get_option($lock_key, false);
    if (is_array($old_lock) && ($old_lock['started_at'] ?? time()) < time() - 300 && get_option($lock_key, false) === $old_lock) { delete_option($lock_key); }
    $lock = array('started_at'=>time(), 'token'=>wp_generate_uuid4());
    if (!add_option($lock_key, $lock, '', false)) { return false; }
    $results = array(); $errors = false; $changed_any = false;
    try {
        $defs = gpc_pages();
        $map = get_option('gpc_page_ids', array());
        if (!is_array($map)) { $map = array(); }
        $keys = array('book-formatting','cover-design','amazon-ads','book-discovery','book-product-page','connected-catalog');
        foreach (array('privacy-policy','terms-of-service') as $key) { if (isset($defs[$key])) { $keys[] = $key; } }
        foreach ($keys as $key) {
            $def = $defs[$key];
            $created_this_request = false;
            $legal = in_array($key, array('privacy-policy','terms-of-service'), true);
            $parent_key = $def['parent'] ?? 'services';
            $parent = gpc_find_page($parent_key);
            if (!$parent || $parent->post_status !== 'publish' || !gpc_matches_page($parent, $parent_key)) {
                $results[] = $def['title'] . ': publish and connect the ' . $parent_key . ' page, then run the repair again.'; $errors = true; continue;
            }
            $page = gpc_structure_page($key);
            $destination = get_page_by_path(gpc_page_path($key), OBJECT, 'page');
            if ($destination && (!$page || (int) $destination->ID !== (int) $page->ID)) {
                $results[] = $def['title'] . ': another page occupies the required URL. Its content was preserved; review it before retrying.'; $errors = true; continue;
            }
            if (!$page && ($legal || $def['parent'] === 'services')) {
                $id = wp_insert_post(array('post_type'=>'page', 'post_status'=>'publish', 'post_title'=>$def['title'], 'post_name'=>$def['slug'], 'post_parent'=>$legal ? 0 : $parent->ID, 'post_content'=>'[' . $def['shortcode'] . ']', 'meta_input'=>array('_gpc_page_key'=>$key, '_wp_page_template'=>'gpc-full-page.php')), true);
                if (is_wp_error($id) || !$id) { $results[] = $def['title'] . ': could not be created. Please retry the repair.'; $errors = true; continue; }
                $page = get_post($id);
                $created_this_request = true;
                $changed_any = true;
                $created = get_option('gpc_structure_created_pages', array());
                if (!is_array($created)) { $created = array(); }
                $created[$key] = (int) $id;
                update_option('gpc_structure_created_pages', $created, false);
                $results[] = $def['title'] . ($legal ? ': created with your approved copy and the shared reading template.' : ': created using the shared service template.');
            }
            if ($legal && $page) {
                if ($key === 'privacy-policy' && gpc_publish_approved_default_policy($page)) {
                    $changed_any = true;
                    $results[] = 'Privacy Policy: approved copy published with a backup of the untouched WordPress starter draft.';
                } elseif ($page->post_status !== 'publish') {
                    $results[] = $def['title'] . ': existing draft preserved. Review it with your approved copy and publish it.'; $errors = true;
                } elseif (!$created_this_request) {
                    $results[] = $def['title'] . ': existing legal page preserved.';
                }
                continue;
            }
            if (!$page || $page->post_status !== 'publish' || !gpc_matches_page($page, $key) || $page->post_name !== $def['slug']) {
                $results[] = $def['title'] . ': review the existing page and its publishing status. Content and custom slugs were preserved.'; $errors = true; continue;
            }
            $map[$key] = (int) $page->ID;
            update_option('gpc_page_ids', $map, false);
            if ((int) $page->post_parent === (int) $parent->ID) { continue; }
            $old_url = get_permalink($page->ID);
            $backups = get_option('gpc_structure_route_backups', array());
            if (!is_array($backups)) { $backups = array(); }
            if (!isset($backups[$page->ID])) {
                $backups[$page->ID] = array('post_parent'=>(int) $page->post_parent, 'post_name'=>$page->post_name, 'permalink'=>$old_url, 'version'=>GPC_VERSION);
                if (!update_option('gpc_structure_route_backups', $backups, false)) { $results[] = $def['title'] . ': route backup could not be saved. No parent was changed.'; $errors = true; continue; }
            }
            // Save the old route before moving the page, so an interruption
            // cannot lose its redirect. Targets are keys, never arbitrary URLs.
            $redirects = get_option('gpc_structure_redirects', array());
            if (!is_array($redirects)) { $redirects = array(); }
            $old_path = gpc_relative_route($old_url);
            if ($old_path && $old_path !== gpc_page_path($key)) {
                $redirects[$old_path] = $key;
                if (get_option('gpc_structure_redirects', array()) !== $redirects && !update_option('gpc_structure_redirects', $redirects, false)) {
                    $results[] = $def['title'] . ': redirect could not be saved. No parent was changed.'; $errors = true; continue;
                }
            }
            $changed = wp_update_post(array('ID'=>$page->ID, 'post_parent'=>$parent->ID), true);
            if (is_wp_error($changed) || !$changed) { $results[] = $def['title'] . ': parent repair failed. Content was preserved; retry the repair.'; $errors = true; continue; }
            $changed_any = true;
            gpc_clear_elementor_page_cache($page->ID);
            do_action('litespeed_purge_url', $old_url);
            do_action('litespeed_purge_url', get_permalink($page->ID));
            $results[] = $def['title'] . ': parent corrected; previous URL redirects permanently.';
        }
        // Links to an article can be cached on any connected Grant page.
        foreach ($changed_any ? gpc_pages() : array() as $key=>$def) {
            $page = gpc_find_page($key);
            if ($page && $page->post_status === 'publish' && gpc_matches_page($page, $key)) {
                gpc_clear_elementor_page_cache($page->ID);
                do_action('litespeed_purge_url', get_permalink($page->ID));
            }
        }
        if (!$errors) { update_option('gpc_structure_repaired_version', GPC_VERSION, false); }
        if (!$results) { $results[] = 'Service pages and Insights article parents are correct. Existing content was preserved.'; }
        update_option('gpc_structure_repair_results', $results, false);
        return !$errors;
    } catch (\Throwable $error) {
        update_option('gpc_structure_repair_results', array_merge($results, array('The repair was interrupted. Existing content was preserved; run the repair again.')), false);
        return false;
    } finally {
        if (get_option($lock_key, false) === $lock) { delete_option($lock_key); }
    }
}
// Public visits never write pages. Runs once on an eligible administrator's
// dashboard visit after an update, or through the explicit repair button.
add_action('admin_init', 'gpc_repair_page_structure', 90);
add_action('admin_post_gpc_repair_structure', function() {
    gpc_admin_post_guard('gpc_repair_structure');
    gpc_repair_page_structure(true);
    gpc_setup_results(get_option('gpc_structure_repair_results', array()));
});

/** Resolve only verified internal legacy routes, ahead of WP's 404 guessing. */
function gpc_structure_redirect_url($uri) {
    $aliases = array('services/connected-catalog'=>'connected-catalog', 'services/book-discovery'=>'book-discovery', 'services/book-product-page'=>'book-product-page');
    $saved = get_option('gpc_structure_redirects', array());
    if (is_array($saved)) { $aliases = array_merge($aliases, array_intersect($saved, array('book-formatting','cover-design','amazon-ads','book-discovery','book-product-page','connected-catalog'))); }
    $path = gpc_relative_route($uri);
    if (!isset($aliases[$path])) { return ''; }
    $key = $aliases[$path]; $page = gpc_find_page($key);
    if (!$page || $page->post_status !== 'publish' || !gpc_matches_page($page, $key)) { return ''; }
    $target = get_permalink($page->ID);
    if (gpc_relative_route($target) !== gpc_page_path($key) || $path === gpc_relative_route($target)) { return ''; }
    $query = wp_parse_url($uri, PHP_URL_QUERY);
    return $target . ($query ? '?' . $query : '');
}
add_action('template_redirect', function() {
    if (!in_array($_SERVER['REQUEST_METHOD'] ?? '', array('GET','HEAD'), true)) { return; }
    $target = gpc_structure_redirect_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''));
    if ($target) { wp_safe_redirect($target, 301, 'Grant Publishing Co.'); exit; }
}, 1);

/** Correct saved article links at render time; no authored text is rewritten. */
function gpc_repair_link_url($url) {
    $host = wp_parse_url($url, PHP_URL_HOST);
    if ($host && strtolower($host) !== strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST))) { return $url; }
    $target = gpc_structure_redirect_url($url);
    if (!$target) { return $url; }
    $fragment = wp_parse_url($url, PHP_URL_FRAGMENT);
    return $target . ($fragment ? '#' . $fragment : '');
}
function gpc_repair_rendered_links($content) {
    if (!is_string($content)) { return $content; }
    return preg_replace_callback('~(<a\b[^>]*\bhref\s*=\s*)(["\x27])([^"\x27]+)\2~i', function($m) {
        $old = html_entity_decode($m[3], ENT_QUOTES, 'UTF-8'); $new = gpc_repair_link_url($old);
        return $new === $old ? $m[0] : $m[1] . $m[2] . esc_url($new) . $m[2];
    }, $content);
}
/** Elementor's native buttons omit the rel value from the source template. */
function gpc_protect_new_tab_links($content) {
    if (!is_string($content)) { return $content; }
    return preg_replace_callback('~<a\b([^>]*)>~i', function($m) {
        if (!preg_match('~\btarget\s*=\s*(["\x27])_blank\1~i', $m[1])) { return $m[0]; }
        $attributes = $m[1];
        $values = array();
        if (preg_match('~\brel\s*=\s*(["\x27])([^"\x27]*)\1~i', $attributes, $rel)) {
            $values = preg_split('/\s+/', trim(html_entity_decode($rel[2], ENT_QUOTES, 'UTF-8')));
        }
        $values = array_values(array_unique(array_filter(array_merge($values, array('noopener','noreferrer')))));
        $replacement = 'rel="' . esc_attr(implode(' ', $values)) . '"';
        $attributes = isset($rel[0]) ? preg_replace('~\brel\s*=\s*(["\x27])[^"\x27]*\1~i', $replacement, $attributes, 1) : $attributes . ' ' . $replacement;
        return '<a' . $attributes . '>';
    }, $content);
}
add_filter('the_content', function($content) {
    return !is_admin() && !is_feed() && !is_preview() && is_singular('page') && is_main_query() && in_the_loop() && gpc_current_key() ? gpc_protect_new_tab_links(gpc_repair_rendered_links($content)) : $content;
}, 40);
add_filter('wp_nav_menu_objects', function($items, $args) {
    if (($args->theme_location ?? '') !== 'gpc_primary') { return $items; }
    foreach ($items as $item) { $item->url = gpc_repair_link_url($item->url); }
    return $items;
}, 10, 2);

/** Older Elementor base styles on this HTTPS site are stored with HTTP URLs. */
function gpc_secure_site_resource($url) {
    if (!gpc_current_key() || wp_parse_url(home_url('/'), PHP_URL_SCHEME) !== 'https' || wp_parse_url($url, PHP_URL_SCHEME) !== 'http') { return $url; }
    return strtolower((string) wp_parse_url($url, PHP_URL_HOST)) === strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST)) ? preg_replace('~^http:~i', 'https:', $url) : $url;
}
add_filter('style_loader_src', 'gpc_secure_site_resource', 20);
add_filter('script_loader_src', 'gpc_secure_site_resource', 20);
