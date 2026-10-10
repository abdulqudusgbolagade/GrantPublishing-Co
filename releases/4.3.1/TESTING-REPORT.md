# Grant Publishing Co. 4.3.1 production repair

Prepared October 10, 2026. **The update is complete in source and the installable plugin; production installation is pending.** The public production website was inspected read-only. No authenticated live WordPress access was available, no live settings were changed, and no real enquiry was sent.

## 1. Important changes and preserved design

The existing premium purple/blue system, homepage structure and hierarchy, compact flowing-G header mark, full footer logo, site icon, typography, layout components, client projects, founder portrait, testimonials, Insights and both case studies are preserved. This release adds focused route, compatibility, metadata, footer, accessibility and performance repairs.

A backup tag, `backup/production-repair-2026-10-10`, points to the clean pre-edit commit `19c3db032a850ed5b5195806b1307dca2287b3ef`. The prior 4.3.0 ZIP remains available. This source backup does not replace a WordPress database backup before installation.

The new scoped administrative repair runs once after an eligible administrator visits the dashboard. It creates only requested missing service/legal pages, repairs the three known connected editorial parents if necessary, and records route backups and verified permanent redirect targets before a move. Public requests never mutate pages. A lock, capability checks, nonce-protected manual retry, failure reporting and version marker prevent repeated or concurrent changes. Existing authored drafts, custom slugs, content and URL collisions are preserved for review. Connected caches are cleared only when something changed.

A clean actual WordPress bootstrap verified that all **17 existing native pages retained their original saved content and Elementor-data hashes**, private fake provider settings were unchanged, and a repeat repair was a no-op. The clean bootstrap also verified publication of the approved Privacy Policy from the exact untouched WordPress starter draft, with a backup and correct shared page template. The three services and Terms were newly created; no client pages were deleted.

## 2. Files changed

Runtime files within `grant-publishing-site/`:

- `grant-publishing-site.php`: release 4.3.1 and load the repair, legal and responsive-image modules.
- `includes/repairs.php` (new): scoped page migration, backups, retries, explicit 301 targets, rendered-link correction, native new-tab protection and same-host HTTPS asset repair.
- `includes/legal.php` (new): approved starter-policy publication with backup and shared template, plus private legal-page selectors.
- `includes/images.php` (new): faithful existing-image renditions, responsive sources and reserved intrinsic proportions.
- `includes/pages.php`: footer descriptor/email/legal links, font enqueue/preconnects, Yoast metadata and Canvas title compatibility.
- `includes/publishing.php`: typography-tolerant duplicate guards for the existing review and book features.
- `includes/setup.php`: repair results/retry controls and legal settings.
- `assets/site.css`: remove the font CSS import in favour of the enqueued stylesheet.
- `assets/design.css`: focused footer/legal wrapping, touch-target and reading-page styles using existing tokens.
- `assets/enquiry.js`: retain an uncertain enquiry and advise checking delivery before retrying.
- `assets/abdulqudus-tella-500.webp` and `assets/luma-review-426.webp` (new): faithful optimised renditions, original files retained.
- `pages.json`: add the two requested legal routes and established metadata.
- `templates/book-formatting.html`, `templates/cover-design.html`, `templates/amazon-ads.html`: complete the three requested service pages using the existing service architecture and practical service scope.
- `templates/privacy-policy.html` and `templates/terms-of-service.html` (new): exact owner-approved legal copy, shared reading layout.
- `elementor/book-formatting.json`, `elementor/cover-design.json`, `elementor/amazon-ads.json`: matching native layouts for these three templates.
- `elementor/privacy-policy.json` and `elementor/terms-of-service.json` (new): matching optional legal layouts.
- `README.md`: updated installation, preservation and rollback instructions.

Repository-only development/release files:

- Root `README.md` and `qa/README.md`: current download, installation and verification instructions.
- `docs/approved-privacy-policy.txt`, `docs/approved-terms-of-service.txt`: the exact supplied source texts.
- `qa/test-structure.php`, `qa/test-legal.php` (new), `qa/test-publishing.php`, `qa/sync-layouts.py`: migration, legal, native-compatibility assertions and matching-layout generation.
- `releases/4.3.1/`: installable ZIP, report, installation/rollback, gallery, checksums, sanitised evidence, screenshots and local-only reproduction helpers. QA dependencies, helper endpoints and databases are excluded from the installable ZIP.

## 3. Pages created or repaired

| Canonical URL | Implementation |
| --- | --- |
| `/services/book-formatting/` | Existing service hero, inclusion/scope panel, practical three-step process, client inputs, enquiry CTA and all-services link. Ebook/Kindle navigation, trim/front matter, paper/hardcover interiors, print PDF and ebook files; platform approval is not promised. |
| `/services/cover-design/` | Matching service system; ebook front cover, paperback/hardcover wraps, spine/back cover, genre/thumbnail considerations, KDP/print preparation, inputs/process/CTA. No sales guarantee. |
| `/services/amazon-ads/` | Matching service system; keyword/product/category research, campaign structure, budgets/bids, testing/search-term review, optimisation and performance review. Setup plus optional recurring management; ad spend separate, results not guaranteed. |
| `/privacy-policy/` | Exact supplied approved Privacy Policy, October 10, 2026; clean shared reading layout. |
| `/terms-of-service/` | Exact supplied approved Terms of Service, October 10, 2026; no wording shortened, expanded or rewritten. |
| `/insights/connected-catalog/` | Existing article retained; correct Insights parent, content and page ID preserved. |

All seven existing service routes remain available. Together, all ten service-card destinations passed. The other two articles remain at `/insights/book-discovery/` and `/insights/book-product-page/`.

## 4. Broken links and technical findings

| Finding before editing | Repair |
| --- | --- |
| Three requested service URLs returned live 404s. Their templates existed but published WordPress pages had not been created. | Scoped post-update repair publishes their existing shared templates and connects their page IDs. |
| Insights Connected Catalog redirected to a Services URL; public page data confirmed its parent was Services. | Move only its WordPress parent to Insights, preserve ID/content/native data, back up original route, add verified 301. |
| Legal URLs returned 404s. | Publish the supplied approved texts where URLs are available and add conditional footer links. Authored collisions/drafts are reported rather than overwritten. |
| Same-host Elementor `base-desktop.css` and `base-mobile.css` were enqueued over HTTP on the HTTPS site. | Upgrade only same-host HTTP style/script URLs on connected HTTPS Grant pages. External resources and HTTP development installations are unchanged. |
| Live pages had duplicate `<title>` tags; most had no meta description, and Home had obsolete narrow stock SEO wording. | Reproduced Canvas/Yoast title conflict and resolved it without changing the saved template. Reuse established titles, fill missing descriptions and replace only the identified obsolete stock Home description. Custom authored descriptions are retained. |
| Actual native integration exposed a review duplicate when WordPress converted straight to smart apostrophes. The live baseline still contained five reviews. | Normalise only comparison text; preserve the displayed testimonial and prevent duplicate additions. Final native Feedback retains five original reviews. |
| Elementor native case-study buttons omitted the source template's `rel` attribute. | Add `noopener noreferrer` to connected Grant new-tab links, retaining existing relationship tokens, wording, destinations and new-tab behaviour. |
| Footer provided email only as an icon. | Add readable configured business email, retain four labelled social/contact icons, and protect the address from legacy icon conversion. |

The final crawl found **no known internal page or anchor failures** across the 22 implemented pages. It checked 848 named links/anchors and all ten service cards. Third-party account-page reachability is a separate limit below.

## 5. Redirects

The plugin now supplies explicit, verified HTTP **301** redirects:

| Old alias | Correct canonical |
| --- | --- |
| `/services/connected-catalog/` | `/insights/connected-catalog/` |
| `/services/book-discovery/` | `/insights/book-discovery/` |
| `/services/book-product-page/` | `/insights/book-product-page/` |

The latter two already relied on WordPress's automatic guessing on the live site; they now have explicit architectural redirects. Additional old paths recorded during a connected-page parent repair map only to verified published Grant page keys. Attribution query parameters survive; rendered link correction also preserves fragments. No redirect is emitted to an unpublished target or a wrong canonical parent, and the canonical routes do not loop. Existing unrelated legacy aliases remain unchanged.

Insights canonicals use their corrected permalinks even if a Yoast indexable still contains the old parent path. All three real HEAD requests returned 301 to the intended destination; each canonical GET returned 200 with the test query retained.

## 6. Responsive/mobile checks and fixes

The initial read-only live audit covered 17 pages × seven widths, **119 combinations**. It found no general horizontal overflow, loaded-image failure, duplicate H1, axe rule violation or JavaScript exception. No broad responsive redesign was justified.

The final actual WordPress audit covered all 22 pages at **1440, 1280, 1024, 768, 480, 390 and 360px: 154/154 combinations passed**, with zero overflow, requested-image failures, page JavaScript exceptions, duplicate H1s or axe findings. Desktop/phone screenshots are available for every page.

Focused changes give the footer legal links/email natural wrapping and usable targets, and legal text follows the existing reading width, spacing and heading scale. A WordPress `wp_update_post` behaviour that overwrote the starter policy's selected template was caught in actual integration and fixed; the page now renders one Grant header, H1 and footer. The header/logo, seven-item navigation, service grid, CTAs, all forms and original hero hierarchy remain.

At 390px, the mobile menu opens, navigates, closes on Escape, restores focus and closes after navigation. The existing showcase additionally passed a 320px touch test and keeps the supplied covers uncropped at their proportions.

## 7. Accessibility and interactions

- One sensible H1, semantic numbered H2 legal sections, readable text/list spacing and existing skip/header/main/footer landmarks.
- Existing form labels, required indications, consent, focus states, native button/link semantics and no automatic marketing subscription retained.
- Labelled social icons plus visible business email; named legal navigation with real published destinations.
- Existing keyboard menu handling and carousel focus/indicator/pause behaviour verified.
- Reduced-motion changes stop automatic carousel movement; manual controls, hover/focus pause and a useful no-JavaScript first slide remain.
- Case-study buttons preserve protected new-tab behaviour; no unnecessary ARIA or animation library was added.

Axe checked WCAG 2 A/AA and WCAG 2.1 AA tags in actual Chromium output. A clean automated scan is not a certification or a substitute for a screen-reader/usability review.

## 8. Performance and asset preservation

All **32 original visual assets** from the 4.3.0 package, including logos, supplied covers, portrait, project PDFs and SVGs, are byte-identical. Original artwork was not generated, altered or replaced.

| Existing image | Delivery improvement | Bytes before → after |
| --- | --- | --- |
| Founder portrait, 500 × 500 | Faithful WebP rendition, quality 90 | 240,145 → 26,810, 88.8% smaller |
| Luma review cover, 426 × 423 | Lossless WebP, verified exact pixel match | 433,824 → 315,250, 27.3% smaller |
| Kathryn's Beach review cover | Existing 360/640px WebP sources selected responsively | 371,261 → 75,148 for 640px, 79.8% smaller; 360px is 32,050 bytes |

Intrinsic dimensions reserve image space; existing lazy loading, alt text, cover destinations and custom responsive sources are preserved. The carousel still defers two later covers and reserves its image area. All covers retain full titles and correct project scope.

Font families/weights remain Cormorant Garamond and Manrope. An enqueued stylesheet with `display=swap` and appropriate preconnects replaces the serial CSS import. Existing design tokens are reused; no new animation or icon font library was introduced. Production's other plugin assets were retained because their dependencies/settings cannot safely be determined through public HTML alone. No synthetic speed score or Core Web Vitals improvement is claimed.

## 9. Tests performed and remaining limits

| Check | Result |
| --- | --- |
| Read-only live page/width audit | 119 combinations passed; route/metadata/mixed-content defects separately recorded. |
| Final actual WordPress/Elementor/Yoast responsive audit | 154 combinations passed. |
| Complete repaired page/link/legal crawl | 22 routes 200; 848 named link/anchor checks; ten service cards; one title/description/canonical; exact legal text; five reviews. |
| Real WordPress form/navigation flows with isolated provider | 36 checks passed. |
| Showcase controls/timing/reduced motion/static fallback/PDF tests | 45 checks passed. |
| PHP isolated backend/publishing/upgrades/structure/legal suites | 260 assertions per runtime × PHP 7.4, 8.2 and 8.3 = **780 passed**. |
| Plugin PHP syntax | All 12 files on PHP 7.4 and 8.2, **24 checks passed**. |
| Enquiry JavaScript | Success, rejection, connection failure, malformed response, invalid input and request/service selection passed. |
| Static package/template/layout checks | 22 matching pairs, 1,199 native elements, routes/assets/forms/anchors/IDs/heading checks passed. |
| Real redirects | Three HEAD 301s, correct destinations and canonical 200s; no loops; test query retained. |
| Clean final WordPress bootstrap | 17 saved-native page hashes/provider settings preserved; 22 final routes; policy backup/template correct; repeat repair no-op. |
| Installable plugin ZIP | 105 files under one `grant-publishing-site/` root; 10,592,178 bytes; CRC and source-byte equality passed; no QA endpoints, databases or real key. |
| Original visuals | 32 byte-identical original assets; lossless Luma rendition pixel match. |
| Case-study bytes | Hosted My Dear Grandfather PDF returned 200/valid PDF; bundled Luma returned 200 and exact supplied 2,562,805 bytes. |

Local integration used official Playground CLI 3.1.57, WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6, PHP 8.5.10 and SQLite. It exercised actual WordPress/Elementor rendering and request handlers, rather than the previous release's approximate DOM fixtures. Production uses its own hosting/database and additional plugins, so deployment remains necessary.

**Not completed on production:** installation, administrator/editor checks, actual CDN/host invalidation, live database migration and inbox receipt. No production administrator access was supplied. Full Safari/Firefox/device/screen-reader testing, host-specific redirects, SEO indexation/search-engine recrawl and real Web3Forms delivery are not established. Third-party Amazon/LinkedIn/Upwork account pages may enforce login or anti-bot rules; their provided URLs are preserved but unrestricted reachability is not claimed.

Forms were tested with real WordPress request handling and a local-only provider interception MU plugin. Required fields/consent, optional Amazon links, service selection, rejection, accepted response, uncertain response, preservation/reset behaviour, honeypot, rate limit and duplicate handling were checked through browser and backend suites. Acceptance is distinguished from inbox delivery; no real key or marketing subscription is introduced.

The Luma PDF is byte-identical to the supplied source. My Dear Grandfather's existing supplied hosted PDF URL remains. Existing accurate attribution and project exclusions are unchanged. No new client result, team member, experience figure, sales guarantee or project was invented.

The plugin ZIP SHA-256 is `783d339e520eb23393c47401f1e25e7af21408a6bc3d771b3bd21af0102c2dbe`.

## 10. Assets, copy and settings still needed

**No further brand assets or legal copy are needed.** Correct compact header and full footer assets already exist; the existing WordPress site icon is the supplied compact symbol and remains unchanged. Both approved legal texts are included exactly.

An authorised WordPress administrator must install and review the update, clear production caches, retain the existing private Web3Forms provider/key, and confirm inbox receipt. If the scoped setup reports an authored draft, custom slug or URL collision, that specific content needs administrator review before retrying. The source repair deliberately does not overwrite it. Third-party analytics/cookie settings should be reviewed against the supplied policy separately; this release does not add tracking or a marketing subscription.

## 11. Owner's live checklist

1. Back up files/database, install the 4.3.1 ZIP and visit the dashboard. Read the repair results under Tools → Grant Website Setup; do not bulk-convert or restore layouts. Clear host/CDN/WordPress caches.
2. Open all ten Services cards, especially Formatting, Cover Design and Amazon Ads. Check their enquiry buttons preselect the correct service. Confirm all three Insights articles stay under `/insights/`; open the old `/services/connected-catalog/` alias and verify it redirects correctly.
3. Open both footer legal links and compare the displayed text with your supplied approved copy. Check the compact header mark, full footer logo, existing browser icon and readable email on desktop and phone.
4. Check Home, Services, Contact, Assessment and Feedback at phone/tablet/desktop sizes. Open/close the mobile menu, tab through controls, pause the book showcase and test reduced motion. Confirm five reviews and both PDF buttons remain.
5. Send one clearly labelled test through Contact and Assessment, including an unpublished-book enquiry without an Amazon URL. Confirm receipt in the inbox connected to Web3Forms and check spam/provider logs if missing. A provider acceptance message alone is not proof of delivery; avoid repeating an uncertain submission until checked.

See [installation/rollback](INSTALLATION.md), [screenshots](GALLERY.md) and [sanitised evidence](evidence/). All automated test results refer to the isolated repaired installation unless expressly identified as the read-only live baseline.
