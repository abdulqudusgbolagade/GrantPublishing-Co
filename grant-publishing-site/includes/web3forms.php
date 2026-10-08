<?php
if (!defined('ABSPATH')) { exit; }

/** Keep the delivery credential on the server, outside page markup and JavaScript. */
function gpc_form_delivery_settings() {
    $saved = get_option('gpc_form_delivery', array());
    if (!is_array($saved)) { $saved = array(); }
    $key = isset($saved['access_key']) && is_string($saved['access_key']) ? $saved['access_key'] : '';
    $provider = isset($saved['provider']) && $saved['provider'] === 'web3forms' ? 'web3forms' : 'email';
    return array('provider' => $provider, 'access_key' => $key);
}
function gpc_web3forms_key_valid($key) {
    return is_string($key) && (bool) preg_match('/\A[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\z/i', $key);
}
function gpc_form_privacy_text() {
    if (gpc_form_delivery_settings()['provider'] === 'web3forms') {
        return 'Your enquiry details are sent securely to Web3Forms for delivery to Grant Publishing Co. so we can respond. This form does not subscribe you to a mailing list.';
    }
    return 'Your enquiry is emailed to Grant Publishing Co. so we can respond. This form does not subscribe you to a mailing list.';
}
function gpc_delivery_settings_screen() {
    $settings = gpc_form_delivery_settings();
    $has_key = gpc_web3forms_key_valid($settings['access_key']);
    echo '<h2>Enquiry delivery</h2><p>Choose how Contact and Book Assessment enquiries reach you. Web3Forms sends to the address connected to your Web3Forms access key. Shared contact email above remains the public email link.</p><form action="' . esc_url(admin_url('admin-post.php')) . '" method="post"><input type="hidden" name="action" value="gpc_save_delivery">';
    wp_nonce_field('gpc_save_delivery');
    echo '<table class="form-table"><tr><th scope="row"><label for="gpc-delivery-provider">Delivery method</label></th><td><select id="gpc-delivery-provider" name="provider"><option value="email"' . selected($settings['provider'], 'email', false) . '>WordPress email</option><option value="web3forms"' . selected($settings['provider'], 'web3forms', false) . '>Web3Forms</option></select></td></tr>';
    echo '<tr><th scope="row"><label for="gpc-web3forms-key">Web3Forms access key</label></th><td><input class="regular-text" type="password" id="gpc-web3forms-key" name="access_key" value="" maxlength="36" autocomplete="new-password" spellcheck="false" aria-describedby="gpc-key-help"><p class="description" id="gpc-key-help">' . ($has_key ? 'A key is saved. Leave this field blank to keep it.' : 'No key is saved. Paste the access key from your Web3Forms account.') . ' Saving a new valid key selects Web3Forms. The key stays in this WordPress installation and is never included in the public form.</p></td></tr>';
    echo '<tr><th scope="row">Remove key</th><td><label><input type="checkbox" name="remove_key" value="yes"> Remove the saved key and return to WordPress email</label><p class="description">To pause Web3Forms while keeping its key, select WordPress email and leave the key field blank.</p></td></tr></table>';
    submit_button('Save enquiry delivery');
    echo '</form><p>After saving, clear the website cache, send one labelled test enquiry and check the receiving inbox and your Web3Forms account. This setting does not send a test automatically. Web3Forms requires outbound HTTPS access to <code>api.web3forms.com</code>. A success response confirms provider acceptance, not inbox delivery.</p>';
}
function gpc_save_delivery() {
    if (!current_user_can('manage_options')) { wp_die('You do not have permission to change enquiry delivery.', '', array('response' => 403)); }
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { wp_die('Please use the enquiry delivery settings form.', '', array('response' => 405)); }
    check_admin_referer('gpc_save_delivery');
    $settings = gpc_form_delivery_settings();
    $key = gpc_site_value('access_key');
    $provider = gpc_site_value('provider');
    if (!in_array($provider, array('email', 'web3forms'), true)) { wp_die('Choose a supported enquiry delivery method.', '', array('response' => 400)); }
    if (gpc_site_value('remove_key') === 'yes') {
        $settings = array('provider' => 'email', 'access_key' => '');
        $notice = 'Web3Forms key removed. Enquiries now use WordPress email.';
    } else {
        if ($key !== '' && !gpc_web3forms_key_valid($key)) { wp_die('The access key must use the 36-character format supplied by Web3Forms. The saved settings have not changed.', '', array('response' => 400)); }
        if ($key !== '') { $settings['access_key'] = $key; $provider = 'web3forms'; }
        if ($provider === 'web3forms' && !gpc_web3forms_key_valid($settings['access_key'])) { wp_die('Add your Web3Forms access key before selecting Web3Forms. The saved settings have not changed.', '', array('response' => 400)); }
        $settings['provider'] = $provider;
        $notice = $provider === 'web3forms' ? 'Web3Forms delivery saved. Send one labelled enquiry to confirm receipt in the inbox connected to your key.' : 'WordPress email delivery selected. Any saved Web3Forms key has been retained.';
    }
    // Avoid loading the credential on every WordPress request through autoload.
    update_option('gpc_form_delivery', $settings, false);
    set_transient('gpc_setup_result_' . get_current_user_id(), array($notice), 5 * MINUTE_IN_SECONDS);
    wp_safe_redirect(admin_url('tools.php?page=grant-website-setup'));
    exit;
}
add_action('admin_post_gpc_save_delivery', 'gpc_save_delivery');

/** No retries or email fallback: a transport failure can occur after acceptance. */
function gpc_web3forms_send($values, $message, $key) {
    if (!gpc_web3forms_key_valid($key)) {
        return array('state' => 'failed', 'message' => 'The enquiry delivery service is not configured. Your details are still in the form. Please contact us by email or WhatsApp.', 'status' => 503);
    }
    $payload = array(
        'access_key' => $key,
        'subject' => '[Grant Publishing Co.] ' . $values['Request'],
        'from_name' => 'Grant Publishing Co. Website',
        'replyto' => $values['Email'],
        'email' => $values['Email'],
        'name' => $values['Name'],
        'message' => $message,
        'request' => $values['Request'],
        'book_title' => $values['Book title'],
        'service' => $values['Service'],
        'publication_status' => $values['Publication status'],
        'book_url' => $values['Book link'],
        'website' => $values['Author website'],
        'contact_permission' => 'Yes',
    );
    $response = wp_remote_post('https://api.web3forms.com/submit', array(
        'timeout' => 18,
        'redirection' => 0,
        'sslverify' => true,
        'headers' => array('Content-Type' => 'application/json', 'Accept' => 'application/json'),
        'body' => wp_json_encode($payload),
        'data_format' => 'body',
        'limit_response_size' => 16384,
    ));
    $uncertain = array('state' => 'uncertain', 'message' => 'We could not confirm whether the enquiry service received your message. Your details are still here. Please contact us by email or WhatsApp to check before sending the same enquiry again.', 'status' => 503);
    if (is_wp_error($response)) { return $uncertain; }
    $status = (int) wp_remote_retrieve_response_code($response);
    $result = json_decode(wp_remote_retrieve_body($response), true);
    if ($status >= 200 && $status < 300 && is_array($result) && isset($result['success']) && $result['success'] === true) {
        return array('state' => 'sent');
    }
    // An HTTP rejection or explicit negative API result confirms non-acceptance.
    if (($status >= 400 && $status < 500) || ($status >= 200 && $status < 300 && is_array($result) && isset($result['success']) && $result['success'] === false)) {
        return array('state' => 'failed', 'message' => $status === 429 ? 'The enquiry service is busy. Your details are still in the form. Please wait a few minutes and try again, or contact us directly.' : 'The enquiry service could not accept your message. Your details are still in the form. Please try again later or contact us by email or WhatsApp.', 'status' => 503);
    }
    // Invalid JSON, unrecognized success flags, redirects and 5xx may be ambiguous.
    return $uncertain;
}
