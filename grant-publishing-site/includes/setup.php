<?php
if (!defined('ABSPATH')) { exit; }
add_action('admin_menu', function() {
    add_management_page('Grant Website Setup', 'Grant Website Setup', 'manage_options', 'grant-website-setup', 'gpc_setup_screen');
});
function gpc_setup_screen() {
    if (!current_user_can('manage_options')) { return; }
    $results = get_transient('gpc_setup_result_' . get_current_user_id());
    delete_transient('gpc_setup_result_' . get_current_user_id());
    echo '<div class="wrap"><h1>Grant Website Setup</h1><p>Grant website, version ' . esc_html(GPC_VERSION) . '. Existing content, Elementor edits and enquiry settings are preserved when you update the plugin.</p>';
    if (is_array($results)) {
        echo '<div class="notice notice-info"><ul>';
        foreach ($results as $result) { echo '<li>' . esc_html($result) . '</li>'; }
        echo '</ul></div>';
    }
    echo '<h2>Service and Insights route repair</h2><p>The update creates only the three requested missing services and corrects connected Insights article parents on an administrator’s dashboard visit. Saved content and layouts are preserved. If a page needs review, resolve the issue below and retry.</p>';
    $repair_results = get_option('gpc_structure_repair_results', array());
    if (is_array($repair_results)) { echo '<ul>'; foreach ($repair_results as $result) { echo '<li>' . esc_html($result) . '</li>'; } echo '</ul>'; }
    echo '<form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_repair_structure">';
    wp_nonce_field('gpc_repair_structure');
    submit_button('Repair service pages and Insights routes', 'secondary', 'submit', false);
    echo '</form>';
    echo '<h2>1. Complete the page structure</h2><p>This publishes missing pages, keeps existing content, and connects the navigation.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_setup_pages">';
    wp_nonce_field('gpc_setup_pages');
    submit_button('Create missing pages and connect navigation', 'secondary', 'submit', false);
    echo '</form><h2>2. Apply the editable design</h2><p>This replaces connected Grant shortcode pages with native Elementor headings, text, images and buttons. Each page receives a pre-conversion backup. Unrelated page content and pages already converted are preserved. Requires Elementor 3.16 or newer with containers enabled; Elementor Pro is not required.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_apply_editorial">';
    wp_nonce_field('gpc_apply_editorial');
    submit_button('Apply editable editorial layouts', 'primary', 'submit', false);
    echo '</form><h2>Your pages</h2><table class="widefat striped"><thead><tr><th>Page</th><th>Status</th><th>Fallback shortcode</th><th>Actions</th></tr></thead><tbody>';
    foreach (gpc_pages() as $key=>$def) {
        $page = gpc_find_page($key);
        $native = $page && get_post_meta($page->ID, '_gpc_elementor_layout', true);
        $status = !$page ? 'Missing' : ($native ? 'Editable in Elementor' : (gpc_matches_page($page, $key) ? 'Connected; ready for editable layout' : 'Existing content preserved'));
        if ($page && $page->post_status !== 'publish') { $status .= ' (' . $page->post_status . ')'; }
        echo '<tr><td>' . esc_html($def['title']) . '</td><td>' . esc_html($status) . '</td><td><code>[' . esc_html($def['shortcode']) . ']</code></td><td>';
        if ($page) {
            echo '<a href="' . esc_url(get_edit_post_link($page->ID)) . '">Edit page</a>';
            if ($native && did_action('elementor/loaded')) { echo ' | <a href="' . esc_url(add_query_arg(array('post'=>$page->ID,'action'=>'elementor'), admin_url('post.php'))) . '">Edit with Elementor</a>'; }
            if ($page->post_status === 'publish') { echo ' | <a href="' . esc_url(gpc_url($key)) . '" target="_blank" rel="noopener">View</a>'; }
            if (metadata_exists('post', $page->ID, '_gpc_editorial_backup')) {
                echo '<details style="margin-top:8px"><summary>Restore options</summary><p>Restore replaces this page’s current content and Elementor edits with its saved pre-conversion content.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_restore_editorial"><input type="hidden" name="page_id" value="' . esc_attr($page->ID) . '">';
                wp_nonce_field('gpc_restore_editorial', '_wpnonce', true, true);
                echo '<button type="submit" class="button">Restore pre-conversion content</button></form></details>';
            }
        }
        echo '</td></tr>';
    }
    echo '</tbody></table><p>If a page says Existing content preserved, save its current layout as an Elementor template, then replace its body with a Shortcode widget using the code in that row. Run step 2 again. Keep the page layout set to Grant Publishing Full Page after conversion.</p>';
    $c = gpc_contact_details();
    echo '<h2>Shared contact details</h2><p>These details update the footer, contact links and form. The email below receives enquiries when WordPress email is selected. With Web3Forms, delivery uses the inbox connected to your access key.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_save_contact">';
    wp_nonce_field('gpc_save_contact');
    echo '<table class="form-table">';
    foreach (array('email'=>'Email and enquiry recipient','whatsapp'=>'WhatsApp number, digits with country code','linkedin'=>'LinkedIn profile URL','upwork'=>'Upwork profile URL') as $key=>$label) {
        $type = $key === 'email' ? 'email' : ($key === 'whatsapp' ? 'text' : 'url');
        echo '<tr><th scope="row"><label for="gpc-setting-' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td><input class="regular-text" required type="' . esc_attr($type) . '" id="gpc-setting-' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($c[$key]) . '"></td></tr>';
    }
    echo '</table>';
    submit_button('Save shared contact details');
    echo '</form>';
    gpc_delivery_settings_screen();
    gpc_legal_settings_screen();
    $delivery = gpc_form_delivery_settings();
    $destination = $delivery['provider'] === 'web3forms' ? 'the inbox connected to your Web3Forms key' : $c['email'];
    echo '<h2>Finish the live checks</h2><ol><li>Clear your site cache. Check the home, service, article and contact pages on desktop and phone.</li><li>Edit navigation labels and order under Appearance > Menus, using the Grant Publishing primary navigation location.</li><li>Exclude Contact and Book Assessment from page caching, because their form tokens expire.</li><li>Send a clearly labelled test enquiry and confirm inbox delivery to ' . esc_html($destination) . '.</li></ol><p>The active delivery method is ' . ($delivery['provider'] === 'web3forms' ? 'Web3Forms' : 'WordPress email') . '. This plugin does not save message bodies in the WordPress database. Short-lived hashed identifiers support abuse and duplicate checks. A success message confirms acceptance for sending, not inbox delivery. If delivery cannot be confirmed, check with us before resending the same enquiry.</p></div>';
}
function gpc_setup_pages() {
    if (!current_user_can('manage_options') || !current_user_can('publish_pages')) { wp_die('You do not have permission to create these pages.', '', array('response'=>403)); }
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { wp_die('Please use the Grant Website Setup button.', '', array('response'=>405)); }
    check_admin_referer('gpc_setup_pages');
    $lock = (int) get_option('gpc_setup_lock', 0);
    if ($lock && $lock < time() - 120) { delete_option('gpc_setup_lock'); }
    if (!add_option('gpc_setup_lock', time(), '', false)) { wp_die('Setup is already running. Wait a moment, then reload the setup page.'); }
    $results = array();
    $map = get_option('gpc_page_ids', array());
    if (!is_array($map)) { $map = array(); }
    $defs = gpc_pages();
    $order = array_merge(array_keys(array_filter($defs, function($d) { return empty($d['parent']); })), array_keys(array_filter($defs, function($d) { return !empty($d['parent']); })));
    foreach ($order as $key) {
        $def = $defs[$key];
        $page = gpc_find_page($key);
        if ($page) {
            $map[$key] = $page->ID;
            update_option('gpc_page_ids', $map, false);
            if (!gpc_matches_page($page, $key)) { $results[] = $def['title'] . ': existing content preserved. Add [' . $def['shortcode'] . '] to connect.'; }
            if ($page->post_status !== 'publish') { $results[] = $def['title'] . ': review and publish the existing page, then run setup again.'; }
            continue;
        }
        if ($key === 'home') {
            $results[] = 'Choose your existing Home page under Settings > Reading, then add [grant_home] if needed.';
            continue;
        }
        $parent_id = 0;
        if (!empty($def['parent'])) {
            $parent = gpc_find_page($def['parent']);
            if (!$parent || $parent->post_status !== 'publish') {
                $results[] = $def['title'] . ': first publish the ' . $defs[$def['parent']]['title'] . ' page, then run setup again.';
                continue;
            }
            $parent_id = $parent->ID;
        }
        $id = wp_insert_post(array('post_type'=>'page', 'post_status'=>'publish', 'post_title'=>$def['title'], 'post_name'=>$def['slug'], 'post_parent'=>$parent_id, 'post_content'=>'[' . $def['shortcode'] . ']', 'meta_input'=>array('_gpc_page_key'=>$key, '_wp_page_template'=>'gpc-full-page.php')), true);
        if (is_wp_error($id) || !$id) {
            $results[] = $def['title'] . ': could not be created. ' . (is_wp_error($id) ? $id->get_error_message() : 'Please try again.');
            continue;
        }
        $map[$key] = (int) $id;
        update_option('gpc_page_ids', $map, false);
        $results[] = $def['title'] . ': created and published.';
    }
    $results[] = gpc_create_primary_menu();
    delete_option('gpc_setup_lock');
    if (!$results) { $results[] = 'All pages are already connected. No content was changed.'; }
    set_transient('gpc_setup_result_' . get_current_user_id(), $results, 5 * MINUTE_IN_SECONDS);
    wp_safe_redirect(admin_url('tools.php?page=grant-website-setup'));
    exit;
}
add_action('admin_post_gpc_setup_pages', 'gpc_setup_pages');
