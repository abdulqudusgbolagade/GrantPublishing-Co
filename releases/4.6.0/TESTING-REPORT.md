# Book formatting portfolio and homepage showcase: 4.6.0

20 October 2026. A focused update to the existing approved Grant design.

## What changed

- **Book Formatting:** added all eight owner-supplied images in a grouped portfolio section, after the existing process and before the established Luma proof/enquiry section. Title pages, chapter openings and text spreads are grouped under the titles visible in the supplied artwork. The open-book image is explicitly described as a mockup and its chapter title is not invented into a new book project.
- **Full-size access:** each gallery card opens its exact supplied WordPress PNG in a protected new tab, including keyboard activation. Elementor lightbox interception is disabled for these links. The gallery also works without JavaScript.
- **Homepage:** retains the existing hero layout and copy, with a larger image area. Its rotation now contains the three established book projects plus eight formatting views. Formatting imagery appears from the second slide, and links to the matching sample on Book Formatting.
- **Controls:** removed the homepage pause button entirely. Previous/next arrows and a compact `1 / 11` indicator avoid crowding the mobile layout. Choosing an arrow or using keyboard navigation stops automatic rotation persistently for that page visit. Keyboard focus also stops it; hover holds the slide temporarily. Reduced-motion preferences disable autoplay. A short visible instruction explains manual browsing. No hidden pause button was substituted.
- **Shared component:** kept the Services showcase’s existing three projects, size, dots and pause/play controls. Home and Services use the same renderer/loader with appropriate contextual controls.
- **Reliable loading:** WordPress can add `loading="lazy"` to source-less hidden slides. The loader now explicitly fetches only the selected image before revealing it, avoiding a hidden-slide loading deadlock. Other slides remain deferred, and a failed image leaves the current image visible.

No new page, slug, testimonial, sales result, client count, publication claim or advertising claim was added. The screenshots are formatting samples, not eight newly claimed client projects. Supplied illustrations and book text were not presented as newly authored work. The existing Luma case study, verified project scopes, GRANT Framework, forms, legal pages, brand assets and Yoast metadata are retained.

## Measured image visibility

These measurements use the actual first image’s contained display size in WordPress, measured against the previous component with the same viewport and artwork.

| Viewport | Before | After | Increase in displayed width/height |
| --- | --- | --- | --- |
| 1440px | 350 × 350px | 400 × 400px | 14.3% |
| 390px | 140 × 140px | 210 × 210px | 50% |

The homepage frame is 610px high on desktop and 420px at 390px. Its dimensions stay reserved while slides change. Covers and spreads use `object-fit: contain`; the low-resolution original Grandfather cover remains constrained to its native dimensions. The existing Services image sizes remain 170 × 170px at desktop and 100 × 100px at 390px.

## Source artwork and performance

All eight supplied WordPress URLs returned usable PNG files. [Source inventory](evidence/sources.json) records exact URLs, dimensions, byte sizes and SHA-256 hashes.

The eight full-resolution WebPs preserve **exact RGBA pixel bytes** and total **1,640,050 bytes**, compared with **2,974,546 bytes** for the PNGs, a **44.86% reduction**. A separate 768px preview of the large mockup adds 332,868 bytes and is selected responsively where suitable. Desktop gallery presentation selects the full-resolution mockup. No original cover/title/artwork was regenerated or cropped.

The original sources range from 289 × 434px to 1536 × 1024px. Their original resolution limits the detail available when enlarged; the full-size links preserve access to the supplied originals. Gallery images have reserved space, dimensions and native lazy loading. The first hero image remains prioritised, while the other ten hero images have no network source until selected. No library or additional animation system was added. These asset measurements are not a Core Web Vitals or overall production speed claim.

## Verification

Actual isolated stack: WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6, Twenty Twenty-Five, PHP 8.5.10 and Chromium/Playwright. This is a local test installation, not production.

- **24 page/viewport combinations:** Home, Services and Book Formatting at 1440, 1280, 1024, 768, 480, 430, 390 and 360px. No page overflow, broken loaded images, duplicate H1, unexpected page/console error or detected axe WCAG 2 A/AA / 2.1 AA violation in the Grant shell. This is practical automated coverage, not a comprehensive accessibility certification.
- **99 active-slide layouts:** all 11 slides at 320, 360, 390, 430, 480, 768, 861, 1024 and 1440px. No image/caption/control overlap or page overflow.
- **63 interaction checks:** all 11 images and captions; five-second rotation; hover and persistent keyboard/manual stopping; arrows/Home/End; counter; reserved height; active/inactive focus exposure; mobile touch targets; reduced motion and preference changes; static fallback; gallery anchors/full-size actions; retained Luma proof; Book Formatting service preselection; optional Amazon link; preserved Services controls.
- **Focused detail checks:** keyboard opening of original images, responsive mockup source choice, mobile menu/Escape/focus restoration, and a deliberately failed later image preserving the current cover. See [results](evidence/detail-checks.json).
- **26-page final crawl:** HTTP 200, one title/H1/header/footer/description/canonical per page, unique IDs, 1,158 named link/destination/anchor checks, all ten service cards, protected new-tab links, both PDF paths, five original reviews and exact approved legal copy.
- **Saved data:** all 26 pages’ content, Elementor data, Yoast fields, dates, slugs, parents and statuses match the retained verified 4.5.0 test snapshot captured on 12 October. Selected form/contact/legal settings also match. The comparison uses that authentic retained baseline, not an inferred before-state.
- **PHP:** seven suites, 410 assertions, pass on PHP 8.2. The affected publishing and journey suites also pass 180 assertions on PHP 7.4. All 22 plugin PHP files pass 44 syntax checks across 7.4 and 8.2. Enquiry JavaScript scenarios, showcase syntax and the existing 26-template/native-layout parity checks pass.

Browser checks found and resolved the asset-path integration mismatch and WordPress hidden-image lazy-loading issue before the final passing runs. Gallery images reserve their own visible area before loading, preventing zero-sized lazy placeholders. Desktop/mobile screenshots are in [the gallery](GALLERY.md).

## Files and deployment

Main files: `includes/formatting-samples.php` (new shared inventory/gallery), `includes/portfolio.php` (contextual slides/controls), `assets/showcase.js` (loading and motion behaviour), `assets/design.css` (scoped gallery/home styles), `includes/journey.php` (portfolio placement), plugin entry/version and nine new WebP assets. [Exact plugin file list](evidence/changed-plugin-files.json). QA includes and documentation were updated. Existing templates and bundled Elementor JSON were not rewritten.

Source backup: commit `4ee3c87`, tag `backup/formatting-showcase-2026-10-20`. The previous [4.5.0 plugin ZIP](../4.5.0/grant-publishing-site-4.5.0.zip) remains unchanged. Replacing the plugin with that ZIP rolls back this release’s rendered gallery/showcase; 4.6.0 adds no page-content or form-setting migration.

The public Home and Formatting pages inspected at the start were running **4.5.0**. **4.6.0 is prepared for installation, not deployed to production by this task.** Production WordPress/hosting administrator access is unavailable here. Follow [installation instructions](INSTALLATION.md), back up production files/database and clear existing caches after replacement. Host/CDN behaviour, Safari/Firefox and physical-device checks remain live verification limits.

No new form test messages were sent. The owner’s 13 October confirmation that both existing live forms delivered their emails remains recorded in the 4.5.0 report. This update does not change the form backend or private key. No additional source asset is required to install this update.
