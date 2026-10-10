<?php
if (!defined('ABSPATH')) { exit; }

/** Modern renditions of verified supplied images; preserve captions and links. */
function gpc_responsive_project_images($content) {
    if (!is_string($content)) { return $content; }
    $images = array(
        'abdulqudus-tella.png'=>array('file'=>'abdulqudus-tella-500.webp', 'width'=>500, 'height'=>500),
        'luma-the-sleepy-star.png'=>array('file'=>'luma-review-426.webp', 'width'=>426, 'height'=>423),
        'kathryns-beach.jpg'=>array('file'=>'kathryns-beach-640.webp', 'width'=>1287, 'height'=>2048, 'srcset'=>'kathryns-beach-360.webp 360w, kathryns-beach-640.webp 640w', 'sizes'=>'(max-width: 720px) 170px, 225px'),
    );
    return preg_replace_callback('~<img\b[^>]*>~i', function($match) use($images) {
        $tag = $match[0];
        if (!preg_match('~\bsrc\s*=\s*(["\x27])([^"\x27]*)\1~i', $tag, $src) || preg_match('~\bsrcset\s*=~i', $tag)) { return $tag; }
        foreach ($images as $source=>$image) {
            if (html_entity_decode($src[2], ENT_QUOTES, 'UTF-8') !== gpc_asset_url($source)) { continue; }
            $tag = str_replace($src[0], 'src="' . esc_url(gpc_asset_url($image['file'])) . '"', $tag);
            $attrs = ' decoding="async"';
            if (preg_match('~\bdecoding\s*=~i', $tag)) { $attrs = ''; }
            foreach (array('width','height') as $size) { if (!preg_match('~\b' . $size . '\s*=~i', $tag)) { $attrs .= ' ' . $size . '="' . $image[$size] . '"'; } }
            if (isset($image['srcset'])) {
                $srcset = preg_replace_callback('~([a-z0-9\-]+\.webp)~', function($m) { return gpc_asset_url($m[1]); }, $image['srcset']);
                $attrs .= ' srcset="' . esc_attr($srcset) . '"';
                if (!preg_match('~\bsizes\s*=~i', $tag)) { $attrs .= ' sizes="' . esc_attr($image['sizes']) . '"'; }
            }
            return preg_replace('~\s*/?>$~', $attrs . '>', $tag);
        }
        return $tag;
    }, $content);
}
add_filter('the_content', function($content) {
    return !is_admin() && !is_feed() && !is_preview() && is_singular('page') && is_main_query() && in_the_loop() && gpc_current_key() ? gpc_responsive_project_images($content) : $content;
}, 36);
