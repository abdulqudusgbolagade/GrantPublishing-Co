# Grant Publishing Co. 4.1.0 testing report

Prepared **9 October 2026**. The plugin/source are published to GitHub for installation. **This release has not been installed live.**

## Delivered

The supplied Brand Guidelines v1.0 define the identity across all 17 pages: Midnight Ink/Indigo, Cobalt/Violet/Lilac accents, Porcelain/white surfaces, Cormorant Garamond Medium display text and Manrope body/UI. The approved homepage headline is **Books built to be discovered.** The header uses the user's current solid icon; the footer uses the supplied full reverse logo from the deck. WordPress's existing site-icon setting is unchanged.

The genuine Luma and My Dear Grandfather covers are bundled and clickable alongside existing client feedback. Luma opens the confirmed LinkedIn review. Grandfather opens Amazon `1947646117`, with **Barsha Rai** credited as author and **Nadine Laman**, owner of **Cactus Rain Publishing**, as publisher. Kathryn's Beach retains its original cover and confirmed Amazon `1947646168`. All review wording remains unchanged. The original five supplied PNG/JPEG files are byte-identical to the source downloads/deck extractions; only standard 96/192px header-icon web renditions are encoded/resized (about 3/7 KB).

All 17 routes, shortcodes and structural definitions are preserved. Only Home and Feedback content templates/bundled layouts change; the other 15 pairs are byte-identical to 4.0. Shared branding applies across both rendering paths. Updated bundled layouts contain **838 native elements** and match template text. Existing saved native page data is never rewritten; missing reviews or book cards are added at render time with scope/duplicate guards. Existing custom native headlines remain saved.

Existing Web3Forms provider, enquiry script, setup, cache-refresh and editorial/full-page files are unchanged. Private delivery settings and the site icon are not modified. Existing once-per-version generated-cache refresh and scoped LiteSpeed URL purges remain.

## Passed verification

| Check | Result and scope |
|---|---|
| Main browser suite | **272 combinations:** 17 pages × shortcode/native fixtures × eight viewports. Intended fonts/images loaded; zero horizontal overflow and no axe WCAG A/AA violations reported. |
| Viewports | 3840×2160, 2560×1440, 1440×900, 1024×900, 768×900, 390×844, 320×900 and 844×390 landscape. |
| Brand and covers | **188 assertions:** approved headline/display family/weight, body family, accessible icon name, header/footer minimum sizes, proportions/clear space, primary colors, viewport fit and all three exact supplied covers/destinations, in both paths. |
| Navigation/destinations | **96 assertions:** service-prefill navigation, assessment, mobile form ordering/groups, article contents/anchors, exact reviews, actual popup clicks to all three confirmed cover destinations and LinkedIn source, and menus across 320–1440px including the 1120/1121 breakpoint. External destinations intercepted. |
| Optional form/toolbar | **19 assertions:** disclosure defaults, assessment/service preselection, invalid concealed-field focus, retained multipart values on rejection and simulated logged-in toolbar/menu bounds. |
| Chromium forms/keyboard/contrast | Required validation, request/action labels, busy/disabled state, success/reset, nonce failure, network/malformed recovery, retained input and focused status; skip link, Escape/outside dismissal, reduced motion and hostile-theme/hover/focus contrast passed. All submissions intercepted. |
| Backend/settings/assets | **62 assertions per runtime**, covering delivery settings, field/security validation, rate/duplicate behavior, strict provider responses and normal/late stylesheet loading. |
| Native compatibility/shared branding | **62 assertions per runtime:** existing-content preservation, quote/cover duplicate guards, genuine/source-upload image detection, exact book destinations, author/publisher attribution, scope guards and actual PHP header/footer markup. |
| Upgrade caches | **14 assertions per runtime:** version marker, published scoped page/URL selection, lock/deduplication and retry behavior without content/delivery writes. |
| PHP versions | All three suites passed on **PHP 7.4, 8.2 and 8.3**: 138 assertions each, **414 executions total**, official WordPress Playground interpreter with WordPress APIs mocked. |
| Syntax and JavaScript | Eight PHP files × 7.4/8.2 = **16 syntax checks**; existing enquiry JS mock tests passed. |
| Structure and links | 17 HTML/native pairs, 838 elements, content parity, headings/IDs, route/asset tokens, service choices, real anchors and balanced markup passed. **608 rendered actions** recorded; internal routes/fragments resolve. |
| Preservation/assets/archive | Original assets, delivery/setup/cache files and the other 15 page/layout pairs preserved; all five new source assets match byte-for-byte. One plugin root, **65 source-matching files**, ZIP integrity and no real access key verified. |
| Screenshots | 34 final desktop/phone captures for all pages, eight 4.0→4.1 comparison captures and one footer detail. Images decoded before final capture. |

Development found the lighter slide neutral had only **4.35:1** contrast on pale violet panels. The deck's darker neutral `#2B2834` now handles secondary copy; the final full suite passes. A real keyboard-focus race at the mobile→desktop breakpoint was corrected: the script retains menu focus context when CSS hides a focused link before the media-query event. Final navigation and form suites pass. Intermediate failed runs are not represented as release passes.

## What these checks establish

Browser evidence uses local fixtures with a synthetic shared shell/form and approximate native Elementor containers/widgets. It does not run WordPress, Elementor or a theme. Official font CSS/files were downloaded over verified TLS and mirrored locally for stable rendering; no font files are distributed in the plugin. Interaction suites also use fallback fonts with remote font requests blocked. PHP suites execute real interpreters with mocked WordPress APIs and never send HTTP/email, save real content or perform live cache operations.

The [gallery](GALLERY.md) shows every page; [design review](DESIGN-REVIEW.md) compares Home/About/Services/Contact with published 4.0. [Brand implementation](../../docs/BRAND-IMPLEMENTATION.md) records the source presentation, assets and user-confirmed decisions. The complete uploaded presentation is not republished.

## Staging acceptance still required

1. Follow [installation](INSTALLATION.md): back up, replace the plugin, clear host/LiteSpeed/CDN/browser caches and regenerate Elementor files if stale. Confirm both stylesheets load with `ver=4.1.0`. Do not recreate or bulk-convert edited pages.
2. Review actual WordPress/Elementor/theme rendering, saved native edits, logged-in toolbar and target browsers/devices. Shared branding should apply; old custom native headlines remain stored. Automated checks and keyboard exercises are not a full assistive-technology accessibility audit.
3. Keep Contact/Assessment uncached. Existing Web3Forms settings remain; send one labelled test yourself and verify the associated inbox/reply address. Connectivity, account restrictions, dashboard behavior and inbox receipt remain unverified. Provider acceptance does not guarantee receipt.
4. Confirm the WordPress site icon is still the existing one. Check the three real covers/source links; Amazon availability and public LinkedIn review visibility were not independently verified.
5. Review the duplicate theme/Canvas title tags observed in the earlier 8 October public audit. This existing integration issue remains outside the identity update; no global title output was removed. Actual activation/cache purges have not been exercised on the user's site.

Rollback uses retained **4.0.0**, followed by cache clearing. Saved delivery settings and page edits remain; no page-content migration occurs. The new logo/cover rendering additions revert with the plugin.

## Evidence

- [272 rendered checks](evidence/visual-results-4.1.json), [188 brand/cover checks](evidence/brand-results-4.1.json)
- [96 navigation/destination checks](evidence/flows-results-4.1.txt), [forms/keyboard/contrast](evidence/interactions-results-4.1.txt), [optional-field/toolbar checks](evidence/optional-results-4.1.txt)
- [Backend 7.4](evidence/backend-php74.txt), [8.2](evidence/backend-php82.txt), [8.3](evidence/backend-php83.txt)
- [Compatibility/shared markup 7.4](evidence/publishing-php74.txt), [8.2](evidence/publishing-php82.txt), [8.3](evidence/publishing-php83.txt)
- [Upgrade 7.4](evidence/upgrades-php74.txt), [8.2](evidence/upgrades-php82.txt), [8.3](evidence/upgrades-php83.txt), [syntax](evidence/php-lint-results.json)
- [Actions](evidence/route-actions.json), [asset hashes](evidence/supplied-asset-validation.json), [confirmed destinations](evidence/asset-destinations.md), [package/preservation checks](evidence/package-validation.txt)
- [Browser runners/prerequisites](evidence/browser-runners/README.md), [checksums](SHA256SUMS.txt)

The evidence ZIP is for review, not WordPress installation. The installable ZIP contains only `grant-publishing-site/`.
