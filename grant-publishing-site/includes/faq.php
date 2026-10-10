<?php
if (!defined('ABSPATH')) { exit; }

function gpc_faq_items() {
    return array(
        'guarantees'=>array('Do you guarantee book sales or rankings?', 'No. We provide the agreed research, design, publishing or marketing work. Sales, rankings, platform approval and advertising profitability depend on factors outside our control.'),
        'genres'=>array('Do you work with fiction and nonfiction?', 'Yes. The approach depends on the book’s genre, intended readers and publishing goals. We confirm the right scope after reviewing your project.'),
        'unpublished'=>array('Do I need to have already published my book?', 'No. You can enquire about cover design, formatting and publishing preparation before publication. An Amazon link is optional for general project enquiries.'),
        'assessment'=>array('What does the free book assessment include?', 'Preliminary observations based on publicly visible book information and details you choose to share. It is a starting point, not a full manuscript review or an audit of private sales and advertising data.'),
        'materials'=>array('What information do you need from me?', 'Start with your book title, genre, goals, publishing stage and the support you need. Depending on the service, we may also need a manuscript, synopsis, trim size, cover assets, existing listing or relevant account information. Do not send passwords through the website form.'),
        'kdp'=>array('Do you work directly inside KDP?', 'KDP setup and uploads can be included in an agreed publishing-support scope, using appropriate access. A design or formatting project that ends at file delivery does not automatically include account work or publication.'),
        'management'=>array('Do you manage Amazon Ads after setup?', 'Yes, optional ongoing management is available on a recurring basis. Setup, management responsibilities, review arrangements and fees are agreed in the proposal.'),
        'spend'=>array('Does advertising spend come out of the service fee?', 'No. Amazon advertising spend is separate from our professional service fee unless a written agreement explicitly says otherwise. The advertising budget is agreed before campaigns are launched.'),
        'revisions'=>array('How many revisions are included?', 'The proposal or project agreement states the number and type of revisions included. A major change in manuscript, format or approved direction may require a revised scope.'),
        'formats'=>array('Do you work with Kindle, paperback and hardcover?', 'Yes. Formatting, cover preparation and publishing support can cover these formats within the agreed scope. Each edition has its own file and specification requirements.'),
        'published'=>array('Can you help with books that are already published?', 'Yes. We can review an existing listing, improve book presentation, prepare revised files or plan marketing support. We first identify what needs attention and what information is available.'),
        'timing'=>array('How long does a project take?', 'Timing depends on the manuscript, formats, deliverables and review process. We agree a schedule after seeing the materials. Missing files, late feedback or platform review can affect delivery.'),
        'international'=>array('Do you work with authors outside the United States?', 'Yes. Authors and publishers outside the United States are welcome to enquire. We confirm the relevant marketplace, formats, communication arrangements and project scope together.'),
    );
}
function gpc_faq_group($ids = array()) {
    $items = gpc_faq_items(); $html = '';
    if (!$ids) { $ids = array_keys($items); }
    foreach ($ids as $id) {
        if (!isset($items[$id])) { continue; }
        $item = $items[$id];
        $html .= '<details class="gp-faq-item"><summary>' . esc_html($item[0]) . '</summary><div class="gp-faq-answer"><p>' . esc_html($item[1]) . '</p></div></details>';
    }
    return '<div class="gp-faq-list">' . $html . '</div>';
}
add_shortcode('grant_faqs', function() { return gpc_faq_group(); });
