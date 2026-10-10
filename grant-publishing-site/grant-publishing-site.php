<?php
/**
 * Plugin Name: Grant Publishing Co. Website
 * Description: Grant Publishing Co. pages, navigation, setup and enquiry forms.
 * Version: 4.3.1
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Grant Publishing Co.
 */
if (!defined('ABSPATH')) { exit; }
define('GPC_VERSION', '4.3.1');

require_once __DIR__ . '/includes/pages.php';
require_once __DIR__ . '/includes/editorial.php';
require_once __DIR__ . '/includes/upgrades.php';
require_once __DIR__ . '/includes/portfolio.php';
require_once __DIR__ . '/includes/publishing.php';
require_once __DIR__ . '/includes/web3forms.php';
require_once __DIR__ . '/includes/setup.php';
require_once __DIR__ . '/includes/repairs.php';
require_once __DIR__ . '/includes/legal.php';
require_once __DIR__ . '/includes/images.php';
function gpc_services() {
    // Preserve old option values so existing service-prefill links still work.
    return array('Book formatting','Cover design','Book publishing and Amazon KDP setup','Amazon Ads campaign setup','Amazon Ads management','Amazon listing optimization','Book descriptions and A+ Content','Book launch or relaunch','Author platform','Series and catalog strategy','Book marketing strategy','Publishing consultation','Not sure yet');
}
function gpc_service_label($value) {
    $labels = array('Book formatting'=>'Book formatting: ebook, paperback & hardcover', 'Cover design'=>'Book cover design', 'Amazon Ads management'=>'Amazon Ads ongoing management', 'Amazon listing optimization'=>'Amazon Book Visibility / listing optimisation', 'Publishing consultation'=>'Publishing consultation');
    return $labels[$value] ?? $value;
}
function gpc_query_value($key) {
    return isset($_GET[$key]) && is_string($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : '';
}
function gpc_site_form($default = 'project') {
    $request = gpc_query_value('request');
    if (!in_array($request, array('project','assessment'), true)) { $request = $default === 'assessment' ? 'assessment' : 'project'; }
    $service = gpc_query_value('service');
    if (!in_array($service, gpc_services(), true)) { $service = ''; }
    wp_enqueue_script('gpc-enquiry', plugins_url('assets/enquiry.js', __FILE__), array(), GPC_VERSION, true);
    $contact = gpc_contact_details();
    ob_start();
    ?>
    <form class="gpc-audit-form gpc-enquiry-form" id="gpc-enquiry" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" data-contact-email="<?php echo esc_attr($contact['email']); ?>">
        <input type="hidden" name="action" value="gpc_send_enquiry">
        <?php wp_nonce_field('gpc_send_enquiry', 'gpc_nonce', false); ?>
        <div class="gpc-honeypot" aria-hidden="true"><label for="gpc-fax">Leave this field empty</label><input id="gpc-fax" name="fax" type="text" tabindex="-1" autocomplete="off"></div>
        <p class="gpc-required-note">Fields marked * are required.</p>
        <div class="gpc-audit-field"><label for="gpc-request">How can we help? *</label><select id="gpc-request" name="request" required><option value="project" <?php selected($request, 'project'); ?>>Discuss a project</option><option value="assessment" <?php selected($request, 'assessment'); ?>>Request a free initial book assessment</option></select></div>
        <fieldset class="gpc-form-group"><legend>Your details</legend>
        <div class="gpc-audit-form-row">
            <div class="gpc-audit-field"><label for="gpc-name">Your name *</label><input id="gpc-name" name="name" type="text" autocomplete="name" maxlength="120" required></div>
            <div class="gpc-audit-field"><label for="gpc-email">Email address *</label><input id="gpc-email" name="email" type="email" autocomplete="email" maxlength="254" required></div>
        </div>
        </fieldset>

        <fieldset class="gpc-form-group"><legend>Your goals</legend>
        <div class="gpc-audit-field"><label for="gpc-message">What would you like help with? *</label><textarea id="gpc-message" name="message" maxlength="5000" required placeholder="Tell us about your book, the support you need or what you would like to improve."></textarea></div>
        </fieldset>
        <details class="gpc-book-details" data-book-details<?php echo $request === 'assessment' || $service !== '' ? ' open' : ''; ?>>
            <summary>Book &amp; project details (optional)</summary>
        <fieldset class="gpc-form-group"><legend>Your book &amp; project</legend>
        <div class="gpc-audit-form-row">
            <div class="gpc-audit-field"><label for="gpc-book">Book title (optional)</label><input id="gpc-book" name="book" type="text" maxlength="240"></div>
            <div class="gpc-audit-field"><label for="gpc-link">Amazon or book link (optional)</label><input id="gpc-link" name="book_url" type="url" placeholder="https://" maxlength="1000"></div>
        </div>
        <div class="gpc-audit-form-row">
            <div class="gpc-audit-field"><label for="gpc-service">Service of interest (optional)</label><select id="gpc-service" name="service"><option value="">Please select</option><?php foreach (gpc_services() as $option) { echo '<option value="' . esc_attr($option) . '"' . selected($service, $option, false) . '>' . esc_html(gpc_service_label($option)) . '</option>'; } ?></select></div>
            <div class="gpc-audit-field"><label for="gpc-status">Publication status (optional)</label><select id="gpc-status" name="publication"><option value="">Please select</option><option>Already published</option><option>Preparing for launch</option><option>Relaunching a book</option><option>Still writing</option></select></div>
        </div>
        <div class="gpc-audit-field"><label for="gpc-website">Author website (optional)</label><input id="gpc-website" name="website" type="url" placeholder="https://" maxlength="1000"></div>
        </fieldset>
        </details>
        <p class="gpc-audit-form-disclaimer">Free initial assessments use publicly available information on Amazon. Please include your Amazon book link if you are requesting an assessment. They do not include a manuscript review or access to your private sales data. For paid work, we may request a synopsis or manuscript to understand the book more fully.</p>
        <div class="gpc-audit-field"><label class="gpc-consent" for="gpc-consent"><input id="gpc-consent" name="consent" type="checkbox" value="yes" required><span>I agree that Grant Publishing Co. may use these details to respond to my enquiry. *</span></label></div>
        <button class="gpc-audit-submit" type="submit"><?php echo $request === 'assessment' ? 'Request free assessment' : 'Send project enquiry'; ?></button>
        <p class="gpc-form-status" role="status" aria-live="polite" aria-atomic="true" tabindex="-1"></p>
        <p>Prefer a direct conversation?</p>
        <div class="gp-social-links" role="group" aria-label="Direct contact options">
            <?php echo gpc_icon_link('whatsapp', 'https://wa.me/' . $contact['whatsapp'], 'Message Grant Publishing Co. on WhatsApp'); ?>
            <?php echo gpc_icon_link('email', 'mailto:' . $contact['email'], 'Email ' . $contact['email']); ?>
        </div>
        <p class="gpc-audit-form-disclaimer"><?php echo esc_html(gpc_form_privacy_text()); ?> You can request deletion by emailing <?php echo esc_html($contact['email']); ?>.</p>
    </form>
    <?php
    return ob_get_clean();
}
function gpc_site_value($key) {
    return isset($_POST[$key]) && is_string($_POST[$key]) ? trim(wp_unslash($_POST[$key])) : '';
}
function gpc_site_reply($ok, $message, $status = 200, $uncertain = false) {
    if (gpc_site_value('gpc_ajax') === '1') {
        if ($ok) { wp_send_json_success(array('message' => $message), $status); }
        wp_send_json_error(array('message' => $message), $status);
    }
    $body = '<p>' . esc_html($message) . '</p><p><a href="' . esc_url(gpc_url('contact') . '#gpc-enquiry') . '">Return to the enquiry page</a></p>';
    if (!$ok) { $body .= '<p>Use your browser Back button to return to your completed form, or email ' . esc_html(gpc_contact_details()['email']) . '.</p>'; }
    wp_die($body, $ok ? 'Enquiry submitted' : ($uncertain ? 'Enquiry delivery unconfirmed' : 'Enquiry not sent'), array('response' => $status, 'back_link' => false));
}
function gpc_site_send() {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { gpc_site_reply(false, 'Please use the enquiry form.', 405); }
    if (!wp_verify_nonce(gpc_site_value('gpc_nonce'), 'gpc_send_enquiry')) { gpc_site_reply(false, 'This form has expired. Copy your message, refresh the page and try again.', 403); }
    if (gpc_site_value('fax') !== '') { gpc_site_reply(false, 'We could not send this enquiry. Please contact us by email.', 400); }
    $raw_email = gpc_site_value('email');
    $email = sanitize_email($raw_email);
    $name = sanitize_text_field(gpc_site_value('name'));
    $message = sanitize_textarea_field(gpc_site_value('message'));
    $kind = gpc_site_value('request');
    if (!$name || strlen($name) > 480 || !is_email($raw_email) || $email !== $raw_email || strlen($email) > 254 || !$message || strlen($message) > 20000 || gpc_site_value('consent') !== 'yes' || !in_array($kind, array('project','assessment'), true)) {
        gpc_site_reply(false, 'Please enter your name, a valid email address and a message, and tick the contact permission box.', 400);
    }
    $values = array('Name' => $name, 'Email' => $email, 'Request' => $kind === 'assessment' ? 'Book assessment' : 'Project enquiry');
    foreach (array('book' => 'Book title', 'service' => 'Service', 'publication' => 'Publication status') as $key => $label) {
        $value = gpc_site_value($key);
        if (strlen($value) > 1000) { gpc_site_reply(false, 'One of the fields is too long. Please shorten it and try again.', 400); }
        $values[$label] = sanitize_text_field($value);
    }
    foreach (array('book_url' => 'Book link', 'website' => 'Author website') as $key => $label) {
        $url = gpc_site_value($key);
        if ($url !== '' && (strlen($url) > 1000 || !filter_var($url, FILTER_VALIDATE_URL) || !in_array(strtolower((string) wp_parse_url($url, PHP_URL_SCHEME)), array('http', 'https'), true))) {
            gpc_site_reply(false, 'Please use a complete http or https website address, or leave the optional link blank.', 400);
        }
        $values[$label] = esc_url_raw($url, array('http','https'));
    }
    // Basic per-address abuse protection. Never trust forwarded IP headers.
    $remote = isset($_SERVER['REMOTE_ADDR']) && is_string($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $rate_key = 'gpc_rate_' . hash_hmac('sha256', $remote, wp_salt('auth'));
    $count = (int) get_transient($rate_key);
    if ($count >= 5) { gpc_site_reply(false, 'Too many attempts in a short time. Please try again in 15 minutes or contact us directly.', 429); }
    set_transient($rate_key, $count + 1, 15 * MINUTE_IN_SECONDS);
    $dedupe_key = 'gpc_sent_' . hash_hmac('sha256', wp_json_encode(array($values, $message)), wp_salt('auth'));
    $previous = get_transient($dedupe_key);
    if (is_array($previous) && ($previous['state'] ?? '') === 'uncertain') {
        gpc_site_reply(false, 'A recent attempt to send this enquiry is awaiting confirmation. Your details are still here. Please contact us by email or WhatsApp to check before submitting it again.', 503, true);
    }
    if ($previous) { gpc_site_reply(true, 'This enquiry has already been submitted. Thank you.'); }
    $delivery = gpc_form_delivery_settings();
    if ($delivery['provider'] === 'web3forms') {
        // Record an in-flight attempt so an uncertain response is never retried silently.
        set_transient($dedupe_key, array('state' => 'uncertain'), 10 * MINUTE_IN_SECONDS);
        $result = gpc_web3forms_send($values, $message, $delivery['access_key']);
        if ($result['state'] !== 'sent') {
            if ($result['state'] === 'failed') { delete_transient($dedupe_key); }
            gpc_site_reply(false, $result['message'], $result['status'], $result['state'] === 'uncertain');
        }
        set_transient($dedupe_key, 1, 10 * MINUTE_IN_SECONDS);
        gpc_site_reply(true, 'Thank you. Web3Forms has accepted your enquiry for delivery to Grant Publishing Co. If you do not hear back, please contact us by email or WhatsApp.');
    }
    $body = "New Grant Publishing Co. website enquiry\n\n";
    foreach ($values as $label => $value) { $body .= $label . ': ' . ($value !== '' ? $value : 'Not provided') . "\n"; }
    $body .= "\nMessage:\n" . $message . "\n\nContact permission: Yes\n";
    $recipient = gpc_contact_details()['email'];
    $sent = wp_mail($recipient, '[Grant Publishing Co.] ' . $values['Request'], $body, array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email));
    if (!$sent) { gpc_site_reply(false, 'Your enquiry could not be sent. Your details are still in the form. Please try again or email ' . $recipient . '.', 503); }
    set_transient($dedupe_key, 1, 10 * MINUTE_IN_SECONDS);
    gpc_site_reply(true, 'Thank you. Your enquiry has been submitted. If you do not hear back, please contact us by email or WhatsApp.');
}
add_action('admin_post_gpc_send_enquiry', 'gpc_site_send');
add_action('admin_post_nopriv_gpc_send_enquiry', 'gpc_site_send');
