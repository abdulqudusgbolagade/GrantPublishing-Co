<?php
if (!defined('ABSPATH')) { exit; }
function gpc_editorial_meta_keys() {
    return array('_elementor_data','_elementor_edit_mode','_elementor_template_type','_elementor_version','_elementor_page_settings','_wp_page_template','_gpc_page_key','_gpc_elementor_layout');
}
function gpc_snapshot_page($id) {
    $post = get_post($id);
    $snapshot = array('post_content'=>$post->post_content, 'saved_at'=>current_time('mysql'), 'meta'=>array());
    foreach (gpc_editorial_meta_keys() as $key) {
        $snapshot['meta'][$key] = array('exists'=>metadata_exists('post', $id, $key), 'value'=>get_post_meta($id, $key, true));
    }
    return $snapshot;
}
function gpc_clear_elementor_page_cache($id) {
    if (class_exists('\Elementor\Core\Files\CSS\Post')) {
        \Elementor\Core\Files\CSS\Post::create($id)->delete();
    }
    delete_post_meta($id, '_elementor_element_cache');
    clean_post_cache($id);
}
function gpc_restore_snapshot($id, $snapshot) {
    if (!is_array($snapshot) || !isset($snapshot['post_content'], $snapshot['meta'])) { return false; }
    $result = wp_update_post(wp_slash(array('ID'=>$id, 'post_content'=>$snapshot['post_content'])), true);
    if (is_wp_error($result) || !$result) { return false; }
    foreach (gpc_editorial_meta_keys() as $key) {
        if (!empty($snapshot['meta'][$key]['exists'])) { update_post_meta($id, $key, wp_slash($snapshot['meta'][$key]['value'])); }
        else { delete_post_meta($id, $key); }
    }
    gpc_clear_elementor_page_cache($id);
    return true;
}
function gpc_resolve_elementor_value($value) {
    if (is_array($value)) {
        foreach ($value as $key=>$item) { $value[$key] = gpc_resolve_elementor_value($item); }
        return $value;
    }
    return is_string($value) ? gpc_resolve_string($value) : $value;
}
function gpc_apply_editorial_layout($page, $key) {
    if (!current_user_can('edit_post', $page->ID)) { return 'Skipped: you cannot edit this page.'; }
    if (get_post_meta($page->ID, '_gpc_elementor_layout', true)) { return 'Already editable. Your changes were preserved.'; }
    if (!gpc_matches_page($page, $key)) { return 'Existing content preserved. Connect the page using its Grant shortcode first.'; }
    $path = dirname(__DIR__) . '/elementor/' . $key . '.json';
    $layout = is_readable($path) ? json_decode(file_get_contents($path), true) : null;
    if (!is_array($layout) || empty($layout['content'])) { return 'Could not read the layout. No content changed.'; }
    $snapshot = gpc_snapshot_page($page->ID);
    if (!metadata_exists('post', $page->ID, '_gpc_editorial_backup')) {
        $saved = add_post_meta($page->ID, '_gpc_editorial_backup', wp_slash($snapshot), true);
        if (!$saved) { return 'Could not save a backup. No content changed.'; }
    }
    try {
        $document = \Elementor\Plugin::$instance->documents->get($page->ID);
        if (!$document || !method_exists($document, 'save') || !method_exists($document, 'set_is_built_with_elementor')) {
            return 'Elementor could not open this page. No content changed.';
        }
        $document->set_is_built_with_elementor(true);
        $ok = $document->save(array('elements'=>gpc_resolve_elementor_value($layout['content']), 'settings'=>array('hide_title'=>'yes')));
        if (!$ok) { throw new \RuntimeException('Elementor did not save the layout.'); }
        update_post_meta($page->ID, '_gpc_page_key', $key);
        update_post_meta($page->ID, '_gpc_elementor_layout', GPC_VERSION);
        update_post_meta($page->ID, '_wp_page_template', 'gpc-full-page.php');
        gpc_clear_elementor_page_cache($page->ID);
        return 'Editorial design applied. Ready to edit with Elementor.';
    } catch (\Throwable $error) {
        return gpc_restore_snapshot($page->ID, $snapshot)
            ? 'The layout could not be applied. Previous content was restored.'
            : 'The layout could not be applied. Use Restore pre-conversion content below before editing.';
    }
}
function gpc_admin_post_guard($action) {
    if (!current_user_can('manage_options')) { wp_die('You do not have permission to change this website.', '', array('response'=>403)); }
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { wp_die('Use the Grant Website Setup screen.', '', array('response'=>405)); }
    check_admin_referer($action);
}
function gpc_setup_results($results) {
    set_transient('gpc_setup_result_' . get_current_user_id(), $results, 5 * MINUTE_IN_SECONDS);
    wp_safe_redirect(admin_url('tools.php?page=grant-website-setup'));
    exit;
}
add_action('admin_post_gpc_apply_editorial', function() {
    gpc_admin_post_guard('gpc_apply_editorial');
    if (!did_action('elementor/loaded') || !defined('ELEMENTOR_VERSION') || version_compare(ELEMENTOR_VERSION, '3.16', '<')) {
        gpc_setup_results(array('Activate Elementor 3.16 or newer, then apply the editable layouts. The shortcode design remains available without Elementor.'));
    }
    $lock = (int) get_option('gpc_editorial_lock', 0);
    if ($lock && $lock < time() - 180) { delete_option('gpc_editorial_lock'); }
    if (!add_option('gpc_editorial_lock', time(), '', false)) { gpc_setup_results(array('An update is already running. Wait a moment and refresh this screen.')); }
    $results = array();
    try {
        foreach (gpc_pages() as $key=>$def) {
            $page = gpc_find_page($key);
            $results[] = $def['title'] . ': ' . ($page ? gpc_apply_editorial_layout($page, $key) : 'Create the missing page first.');
        }
    } finally {
        delete_option('gpc_editorial_lock');
    }
    gpc_setup_results($results);
});
add_action('admin_post_gpc_restore_editorial', function() {
    gpc_admin_post_guard('gpc_restore_editorial');
    $id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;
    if (!$id || !current_user_can('edit_post', $id)) { wp_die('You cannot restore this page.', '', array('response'=>403)); }
    $backup = get_post_meta($id, '_gpc_editorial_backup', true);
    gpc_setup_results(array(gpc_restore_snapshot($id, $backup) ? 'Pre-conversion content restored for ' . get_the_title($id) . '.' : 'The backup could not be restored. No backup was deleted.'));
});
add_action('admin_post_gpc_save_contact', function() {
    gpc_admin_post_guard('gpc_save_contact');
    $fields = array();
    foreach (array('email','whatsapp','linkedin','upwork') as $key) {
        $fields[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? trim(wp_unslash($_POST[$key])) : '';
    }
    if (!is_email($fields['email']) || !preg_match('/^[1-9][0-9]{7,14}$/', $fields['whatsapp'])) { gpc_setup_results(array('Enter a valid email and a WhatsApp number with country code, using digits only. No settings changed.')); }
    foreach (array('linkedin','upwork') as $key) {
        if (!filter_var($fields[$key], FILTER_VALIDATE_URL) || strtolower((string) wp_parse_url($fields[$key], PHP_URL_SCHEME)) !== 'https') { gpc_setup_results(array('Enter complete https profile links. No settings changed.')); }
        $fields[$key] = esc_url_raw($fields[$key], array('https'));
    }
    $fields['email'] = sanitize_email($fields['email']);
    update_option('gpc_contact_details', $fields, false);
    gpc_setup_results(array('Shared contact details and the enquiry email recipient were updated. Clear your page cache to show the changes.'));
});
function gpc_create_primary_menu() {
    if (has_nav_menu('gpc_primary')) { return 'Your existing Grant navigation was preserved.'; }
    $menu_id = (int) get_option('gpc_primary_menu_id', 0);
    if (!$menu_id || !wp_get_nav_menu_object($menu_id)) {
        $menu_id = wp_create_nav_menu('Grant Publishing Navigation');
        if (is_wp_error($menu_id)) { return 'The default navigation is active. You can create a custom menu in Appearance > Menus.'; }
        update_option('gpc_primary_menu_id', (int) $menu_id, false);
    }
    $existing = wp_get_nav_menu_items($menu_id);
    $objects = $existing ? wp_list_pluck($existing, 'object_id') : array();
    $warnings = array();
    foreach (array('home'=>'Home','services'=>'Services','about'=>'About','feedback'=>'Client Feedback','insights'=>'Insights','contact'=>'Contact') as $key=>$label) {
        $page = gpc_find_page($key);
        if (!$page || $page->post_status !== 'publish') { $warnings[] = $label; continue; }
        if (in_array((string) $page->ID, array_map('strval', $objects), true)) { continue; }
        $added = wp_update_nav_menu_item($menu_id, 0, array('menu-item-object-id'=>$page->ID,'menu-item-object'=>'page','menu-item-type'=>'post_type','menu-item-title'=>$label,'menu-item-status'=>'publish'));
        if (is_wp_error($added)) { $warnings[] = $label; }
    }
    // Keep the complete built-in navigation when a menu could not be fully created.
    if ($warnings) { return 'Default navigation retained. Finish these pages before connecting a custom menu: ' . implode(', ', $warnings) . '.'; }
    $locations = get_theme_mod('nav_menu_locations', array());
    $locations['gpc_primary'] = (int) $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
    return 'Navigation connected. You can edit its labels and order under Appearance > Menus.';
}
// The same menu appears in two responsive surfaces; omit duplicate DOM IDs.
add_filter('nav_menu_item_id', function($id, $item, $args) { return isset($args->theme_location) && $args->theme_location === 'gpc_primary' ? '' : $id; }, 10, 3);
