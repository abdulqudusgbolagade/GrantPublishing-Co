<?php
if (!defined('ABSPATH')) { exit; }

/** Replace only WordPress's untouched, unpublished starter policy, with backup. */
function gpc_publish_approved_default_policy($page) {
    if (!$page || $page->post_type !== 'page' || $page->post_status !== 'draft' || $page->post_name !== 'privacy-policy' || get_post_meta($page->ID, '_elementor_data', true)) { return false; }
    if (!class_exists('WP_Privacy_Policy_Content')) { require_once ABSPATH . 'wp-admin/includes/class-wp-privacy-policy-content.php'; }
    if ($page->post_content !== WP_Privacy_Policy_Content::get_default_content()) { return false; }
    if (!metadata_exists('post', $page->ID, '_gpc_legal_backup')) {
        $backup = gpc_snapshot_page($page->ID);
        $backup['post_status'] = $page->post_status;
        $backup['post_title'] = $page->post_title;
        if (!add_post_meta($page->ID, '_gpc_legal_backup', wp_slash($backup), true)) { return false; }
    }
    $id = wp_update_post(array('ID'=>$page->ID, 'post_status'=>'publish', 'post_title'=>'Privacy Policy', 'post_content'=>'[grant_privacy_policy]', 'meta_input'=>array('_gpc_page_key'=>'privacy-policy', '_wp_page_template'=>'gpc-full-page.php')), true);
    if (is_wp_error($id) || !$id) { return false; }
    // wp_update_post carries forward an existing page's default page_template
    // and can overwrite meta_input. Assign the shared shell after that save.
    update_post_meta($id, '_wp_page_template', 'gpc-full-page.php');
    return get_post_meta($id, '_wp_page_template', true) === 'gpc-full-page.php';
}

function gpc_legal_settings_screen() {
    echo '<h2>Legal page links</h2><p>Choose the pages containing your approved Privacy Policy and Terms of Service. Drafts remain private; footer links appear only after the pages are published with content. This plugin does not create or approve legal terms.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_save_legal_pages">';
    wp_nonce_field('gpc_save_legal_pages');
    foreach (array('privacy-policy'=>'Privacy Policy', 'terms-of-service'=>'Terms of Service') as $key=>$label) {
        $page = gpc_legal_page($key);
        echo '<p><label for="gpc-legal-' . esc_attr($key) . '">' . esc_html($label) . '</label><br>';
        wp_dropdown_pages(array('name'=>$key, 'id'=>'gpc-legal-' . $key, 'selected'=>$page ? $page->ID : 0, 'show_option_none'=>'Choose a page', 'option_none_value'=>'0', 'post_status'=>array('publish','draft','pending','private')));
        echo '</p>';
    }
    submit_button('Save legal page links');
    echo '</form>';
}
add_action('admin_post_gpc_save_legal_pages', function() {
    gpc_admin_post_guard('gpc_save_legal_pages');
    $settings = array();
    foreach (array('privacy-policy','terms-of-service') as $key) {
        $id = isset($_POST[$key]) && is_scalar($_POST[$key]) ? absint($_POST[$key]) : 0;
        $page = $id ? get_post($id) : null;
        if ($id && (!$page || $page->post_type !== 'page' || !in_array($page->post_status, array('publish','draft','pending','private'), true))) {
            gpc_setup_results(array('Choose valid legal pages. No legal page settings were changed.'));
        }
        $settings[$key] = $id;
    }
    update_option('gpc_legal_pages', $settings, false);
    gpc_setup_results(array('Legal page links saved. Only published pages with content appear in the footer. Clear the page cache after publishing.'));
});
