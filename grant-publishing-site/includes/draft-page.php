<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('gpc-full-page'); ?>>
<?php wp_body_open(); ?>
<div class="gpc-site gp-page-editorial-draft"><?php echo gpc_header('insights'); ?><main id="gpc-main">
<?php while (have_posts()) { the_post(); the_content(); } ?>
</main><?php echo gpc_footer(); ?></div>
<?php wp_footer(); ?>
</body></html>
