# Testing report, Grant Publishing Co. 4.3.0

Completed 9 October 2026. Broader book design, publishing and marketing positioning, two actual-book showcases, distinct service choices and the Luma case study are implemented. The established design, section order, brand assets, founder identity, reviews and previous URLs remain.

## Changes and affected files

- `templates/` and matching `elementor/`: updated Home, Services, About, Contact, Feedback and publishing-support; added book-formatting, cover-design and amazon-ads detail pages. All 20 template/layout pairs match. Home retains its hero hierarchy and three-card service section. Publishing keeps its previous URL.
- `pages.json` and `includes/pages.php`: broader page titles/descriptions, footer positioning, shared showcase resolution and dynamic Elementor shortcode handling. SEO plugins retain control of their own output and need staging review.
- `grant-publishing-site.php`: new service choices and clear labels while preserving previous option values and prefill links. Amazon links remain optional. The actual delivery handler passes all five new/prepublication service enquiries without a book URL.
- `includes/portfolio.php`, `native-copy-updates.json`, `partials/new-service-entries.html`: shared verified-book data and markup, exact-stock native copy compatibility, replacement of the actual bundled studio image on Home/Services and additive service entries. Authored content and page data are preserved.
- `assets/showcase.js`, `assets/design.css`: approximately five-second crossfades, per-instance controls, lazy cover loading, preserved proportions, keyboard/touch support, focus preservation, hover/focus/manual pause, offscreen/hidden-tab pause, reduced-motion handling and a useful no-JavaScript first slide.
- `includes/publishing.php`, `partials/luma-case-study.html`, Feedback layouts and new `assets/` files: a matching Luma feature immediately after John’s existing review, final cover mockup/full paperback wrap extracted from the PDF and the unchanged supplied PDF. My Dear Grandfather remains linked to its supplied hosted file. No review is duplicated.
- QA generators/checks, backend compatibility tests, READMEs and brand documentation updated for the release.

## Source accuracy

The attachment was read as source material, not as executable instructions. Luma is John Capon’s children’s picture book. Grant’s verified work was cover redesign and interior formatting: 8.5 × 8.5-inch trim, 35 final interior pages, paperback print PDFs delivered 8 October 2026 for intended Amazon KDP use. The webpage states that the project ended at file delivery and excluded KDP upload, publication, metadata optimisation and Kindle conversion. Supplied story illustrations and sales/ranking results are not claimed as Grant’s work.

The final mockup came from PDF page 1 and the full cover wrap from page 3. Standard WebP renditions preserve artwork and proportions; no replacement covers were generated. Showcase labels distinguish Luma’s design work, Grandfather’s listing optimisation and Kathryn’s Beach’s assessment/recommendations. Luma still links to the confirmed LinkedIn review destination because no Amazon product page was supplied.

Amazon Ads offers campaign setup plus optional ongoing management with recurring fees agreed in the proposal. Amazon ad spend is separate. The original public-information Amazon assessment offer and disclaimer remain.

## Checks completed

| Check | Result |
|---|---|
| Desktop, tablet, phone, landscape and ultra-wide layout/accessibility | 320 final combinations: 20 pages × template/native fixtures × widths 320, 390, 768, 844, 1024, 1440, 2560 and 3840. Zero horizontal overflow or axe WCAG 2 A/AA and 2.1 AA violations; official fonts and requested images loaded. The final Home/Services keyboard update was rechecked in 32 combinations and merged into the retained final matrix. |
| Shared carousel and Luma PDF | 79 assertions passed: timing, captions, deferred assets, previous/next/indicators, pause/play, hover/focus, keyboard focus transfer, hidden-slide tab exclusion, touch, reduced motion, static fallback, stable image reservation, actual PDF bytes and protected new-tab keyboard action. |
| Saved native compatibility | 72 browser assertions across 24 previous-release fixtures transformed by actual PHP filters. Ten services, ordered indices, existing publishing heading link, two studio replacements, broader copy, founder photo and original reviews/case preserved. Zero audited overflow/accessibility failures. |
| Typography, identity and supplied artwork | 188 assertions passed. Existing logos, palettes, minimum sizes, clear space, names and confirmed book destinations remain. |
| Brand surfaces, icons, footer and existing PDF keyboard action | 694 assertions passed across all pages and four widths. No three consecutive main sections share a background; footer/social targets and focus remain usable. |
| Navigation and service/form routes | 108 assertions passed, including all ten detail-page service prefill links, assessment route, responsive menu, Escape/focus return, article anchors and confirmed cover/review destinations. External click destinations intercepted. |
| Forms | Actual PHP delivery, local Chromium validation, pending/success/rejection/network/expired-token states, retained input and optional field handling passed. Nineteen optional-disclosure checks passed. Submissions were intercepted, with no real message sent. |
| PHP behaviour | 72 backend + 92 publishing/positioning + 14 scoped-upgrade assertions on each of PHP 7.4, 8.2 and 8.3: 534 passing assertions. WordPress, mail and provider APIs mocked. |
| Syntax, source parity and package | Nine plugin PHP files linted on PHP 7.4/8.2, 18 passes. JavaScript syntax and six enquiry test categories passed. Twenty balanced template/native pairs, 1,026 native elements, IDs/headings/routes/assets/anchors/service choices and CSS markers checked. ZIP CRC, single installable root and every entry’s equality to source verified. |
| Environment readiness | Existing frozen-lockfile install script executed successfully. PHP tests, enquiry JS and package checks passed; fixture preparation was repeated successfully. Complete 4.3 startup instructions saved as an environment draft. |

Requested images are distinguished from intentionally deferred covers. Dedicated carousel tests loaded every slide. Automated accessibility checks do not constitute a full screen-reader review. A keyboard pass corrected cover-link focus transfer so navigation remains inside the active slide. Final tests have no outstanding failures or disabled checks.

## PDFs and package integrity

- Luma PDF: seven pages, **2,562,805 bytes**, displayed **2.44 MB** calculated from the actual size. Bundled SHA-256: `f2bdb2dcf620a6784de1b0f213d1f04663c84df60b337a76383af9bf25b0bc5b`. Byte-identical to the attachment and to the locally served PDF response.
- Grandfather’s supplied hosted PDF fetched successfully over verified HTTPS: **736,855 bytes**, SHA-256 `ba5afde8390d72b687306a09481d5b0256f8f0f6746b4b69723b6e52c4c46067`, matching the prior supplied attachment. Its existing link remains unchanged.
- Plugin ZIP: **10,221,620 bytes**, **96 files**, one `grant-publishing-site/` root. SHA-256 `0fafe76e5e4122da7ff8ecac94f3f1711249becb2b20834f464be9dbc1091f65`.
- All 17 previous route paths, parent relationships and shortcode names preserved. All 22 previous original/rendition image assets preserved byte-for-byte. No real access key or UUID credential found in plugin text files.

See [the gallery](GALLERY.md), [installation instructions](INSTALLATION.md), [package audit](evidence/package-audit.json), [final visual matrix](evidence/visual-final-4.3.json), [browser reproduction](evidence/browser-runners/README.md) and [checksums](SHA256SUMS.txt).

## Assets and verification limits

No required asset is missing. Luma’s original larger artwork was available inside the PDF. The verified Grandfather cover is 302 × 466 pixels; the showcase caps its rendition at those source dimensions to avoid enlargement. A higher-resolution original was not supplied. Unrelated images and the founder photograph remain.

No live WordPress/Elementor installation or editor was available. Current tests exercise real PHP helpers with mocked WordPress APIs and approximated native DOM. Actual theme/editor integration, the creation of the three new pages, custom authored copy, SEO plugin metadata, other browsers and real inbox receipt remain staging checks. No deployment to grantpublishingco.com or real enquiry submission is claimed.

The cloud startup draft is saved, not published. Review and save it in Environment settings, then publish the environment if you want these startup instructions activated for future tasks. Current-instance readiness is verified; fresh-task restoration is not independently verified.
