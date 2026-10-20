<?php
if (!defined('ABSPATH')) { exit; }

/** Owner-supplied interior samples. These are views, not eight separate clients. */
function gpc_formatting_samples() {
    return array(
        array('id'=>'seventh-title-pages','group'=>'seventh','book'=>'The Seventh Binding','view'=>'Title-page spread','detail'=>'Decorative title treatment and balanced front matter.','width'=>727,'height'=>542,'original'=>'Screenshot-2026-10-20-095233.png'),
        array('id'=>'seventh-chapter-one','group'=>'seventh','book'=>'The Seventh Binding','view'=>'Chapter one','detail'=>'An illustrated opening, a clear chapter title and a drop cap.','width'=>359,'height'=>538,'original'=>'Screenshot-2026-10-20-095257.png'),
        array('id'=>'seventh-chapter-two','group'=>'seventh','book'=>'The Seventh Binding','view'=>'Chapter two and facing page','detail'=>'An illustrated chapter opening alongside the body-text layout.','width'=>723,'height'=>539,'original'=>'Screenshot-2026-10-20-095746.png'),
        array('id'=>'seventh-text-spread','group'=>'seventh','book'=>'The Seventh Binding','view'=>'Body-text spread','detail'=>'Running heads, page numbers and consistent text alignment.','width'=>729,'height'=>535,'original'=>'Screenshot-2026-10-20-095350.png'),
        array('id'=>'names-title-page','group'=>'names','book'=>'The Names She Kept','view'=>'Title page','detail'=>'A restrained title hierarchy and generous spacing.','width'=>289,'height'=>434,'original'=>'Screenshot-2026-10-16-002003.png'),
        array('id'=>'names-front-matter','group'=>'names','book'=>'The Names She Kept','view'=>'Copyright and quotation','detail'=>'A clear front-matter layout with an opposing quotation page.','width'=>584,'height'=>434,'original'=>'Screenshot-2026-10-16-001815.png'),
        array('id'=>'names-chapter-one','group'=>'names','book'=>'The Names She Kept','view'=>'Chapter one and facing page','detail'=>'Chapter typography, a drop cap and a consistent reading layout.','width'=>584,'height'=>433,'original'=>'Screenshot-2026-10-16-001452.png'),
        array('id'=>'open-book-mockup','group'=>'mockup','book'=>'Open-book formatting sample','view'=>'Chapter 3: A Name I Didn’t Write','detail'=>'An open-book mockup showing chapter typography and a continuous spread.','width'=>1536,'height'=>1024,'original'=>'A-Name-I-Didnt-Write.png'),
    );
}
function gpc_formatting_sample_file($sample, $preview = false) {
    return 'formatting-' . $sample['id'] . ($preview && $sample['id'] === 'open-book-mockup' ? '-768' : '') . '.webp';
}
function gpc_formatting_sample_srcset($sample) {
    $set = array();
    if ($sample['id'] === 'open-book-mockup') { $set[] = gpc_asset_url(gpc_formatting_sample_file($sample, true)) . ' 768w'; }
    $set[] = gpc_asset_url(gpc_formatting_sample_file($sample)) . ' ' . $sample['width'] . 'w';
    return implode(', ', $set);
}
function gpc_formatting_gallery() {
    $html = '<section class="gp-section gp-tone-lilac gp-formatting-portfolio" id="formatting-samples" data-gpc-formatting-samples><div class="gp-wrap"><div class="gp-label"><p>FORMATTING PORTFOLIO</p></div><div class="gp-heading"><h2>See the formatting up close.</h2></div><div class="gp-copy"><p>Explore title pages, chapter openings and text spreads, alongside an open-book mockup. Open any sample for a closer look at the layout.</p></div>';
    foreach (array('seventh'=>'The Seventh Binding','names'=>'The Names She Kept','mockup'=>'Open-book presentation') as $group=>$name) {
        $html .= '<div class="gp-formatting-group gp-formatting-group-' . $group . '"><h3>' . esc_html($name) . '</h3><div class="gp-formatting-grid">';
        foreach (gpc_formatting_samples() as $sample) {
            if ($sample['group'] !== $group) { continue; }
            $alt = $sample['book'] . ': ' . $sample['view'] . '. ' . $sample['detail'];
            $sizes = $sample['group'] === 'mockup' ? '(max-width:720px) calc(100vw - 64px), (max-width:1120px) calc(100vw - 112px), 860px' : '(max-width:720px) calc(100vw - 64px), (max-width:1120px) 44vw, 580px';
            $original = 'https://grantpublishingco.com/wp-content/uploads/2026/10/' . $sample['original'];
            $html .= '<a class="gp-formatting-sample" id="formatting-sample-' . esc_attr($sample['id']) . '" href="' . esc_url($original) . '" target="_blank" rel="noopener noreferrer" data-elementor-open-lightbox="no"><figure><div class="gp-formatting-media"><img src="' . esc_url(gpc_asset_url(gpc_formatting_sample_file($sample, true))) . '" srcset="' . esc_attr(gpc_formatting_sample_srcset($sample)) . '" sizes="' . esc_attr($sizes) . '" width="' . $sample['width'] . '" height="' . $sample['height'] . '" alt="' . esc_attr($alt) . '" loading="lazy" decoding="async"></div><figcaption><h4>' . esc_html($sample['view']) . '</h4><p>' . esc_html($sample['detail']) . '</p><span class="gp-formatting-view">View full-size image <span aria-hidden="true">↗</span><span class="gpc-sr-only"> of ' . esc_html($sample['book'] . ', ' . $sample['view']) . ' (opens in a new tab)</span></span></figcaption></figure></a>';
        }
        $html .= '</div></div>';
    }
    return $html . '</div></section>';
}
add_shortcode('grant_formatting_samples', 'gpc_formatting_gallery');
