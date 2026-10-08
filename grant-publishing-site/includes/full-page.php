<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('gpc-full-page'); ?>>
<?php wp_body_open(); ?>
<?php
while (have_posts()) {
    the_post();
    $key = gpc_current_key();
    $native = get_post_meta(get_the_ID(), '_gpc_elementor_layout', true);
    if ($native && did_action('elementor/loaded')) {
        echo '<div class="gpc-site">' . gpc_header($key) . '<main id="gpc-main">';
        the_content();
        echo '</main>' . gpc_footer() . '</div>';
    } elseif ($native) {
        echo gpc_site_page($key);
    } else {
        the_content();
    }
}
wp_footer();
?>
</body></html>
