# Grant Publishing Co. 4.0.0 testing report

Prepared 9 October 2026. This release is prepared for installation and published to GitHub; **it has not been installed on the live WordPress site**.

## Changes and preservation

All 17 pages are redesigned in the user's confirmed **Grant blue, cream and ink** palette. The later full-site redesign request supersedes the handoff's earlier visual preservation direction; routes, shortcodes, recognizable section sequence and genuine supplied assets remain. New compositions include an editorial studio hero, contextual book/review proof, larger portrait, service directory and explanatory diagrams, article contents, grouped forms and responsive navigation.

Both supplied additional five-star reviews are included verbatim with confirmed authors. Kathryn's Beach uses the genuine bundled cover and the user-confirmed Amazon product URL. John Capon's Graphic Design quote and Luma metadata link to his LinkedIn services page as explicitly requested; North & Mercer is under construction. **Luma's original PNG/JPG is still required**: its inline chat image was visible but had no downloadable file identifier or workspace file. This ZIP includes John's review, not a fabricated or broken Luma cover.

The decorative publishing-studio photograph is generated brand imagery, optimized to 106/49/21 KB WebP variants. It does not depict completed client work. The original logo, portrait and Kathryn cover are unchanged. Existing provider, page shell, setup, editorial/full-page PHP and base CSS are byte-identical to 3.4.0. Delivery settings are never rewritten. The feedback metadata description now includes both clients.

All 17 bundled native layouts match updated template text and contain **835 native elements**. These layouts serve new installations; upgrading does not overwrite saved `_elementor_data`. Scoped frontend compatibility appends missing reviews and links the original unlinked Kathryn image on connected native Home/Feedback pages, preserving existing links and content. Other old native structure remains as saved.

The new version refreshes generated Elementor/WordPress post caches and requests official LiteSpeed URL purges only for connected, published Grant pages, once per version. Lock, retry and duplicate guards are exercised locally; actual cache integration remains a staging check.

## Passed local checks

| Check | Result and practical scope |
|---|---|
| Full browser suite | **272 combinations:** 17 pages × shortcode/native fixtures × eight viewports. Intended fonts/images loaded; zero horizontal overflow and no axe WCAG A/AA violations reported. |
| Viewports | 3840×2160, 2560×1440, 1440×900, 1024×900, 768×900, 390×844, 320×900 and 844×390 landscape. |
| Final forms | **32 combinations:** Contact/Assessment × both paths × eight viewports after the native assessment-default fixture correction. |
| Navigation and real destinations | **90 focused assertions:** seven service-prefill navigations, assessment CTA, form order/groups, article contents/anchors, exact reviews, intercepted Amazon/LinkedIn popup destinations and menu behavior across 320–1440px including 1120/1121 breakpoint. |
| Optional fields and admin-toolbar layout | **19 assertions:** collapsed/default/open states, assessment and service selection, invalid concealed-field focus, retained multipart values on rejection and simulated toolbar/menu bounds. |
| Chromium forms and keyboard | Required validation, request/service selection and labels, disabled/busy state, success/reset, nonce failure, network/malformed recovery, retained input and focused status; skip link, Escape/focus return, outside closure, reduced motion and inherited-theme/hover/focus contrast passed. All submissions intercepted. |
| Backend/settings/assets | **62 assertions per PHP version** covering private settings, validation, nonce/consent/honeypot, rate/duplicate handling, strict provider responses and normal/late stylesheet loading. |
| Native review compatibility | **46 assertions per PHP version:** scope guards, exact quote/duplicate detection, partial/ID handling, existing-content preservation and genuine bundled image/link handling. |
| Upgrade caches | **14 assertions per PHP version:** version marker, scoped published page/URL selection, deduplication, lock behavior, exceptions/retry and no saved-content/delivery writes. |
| PHP runtimes | The three suites passed on **PHP 7.4, 8.2 and 8.3**: 122 assertions per runtime, 366 executions total, official WordPress Playground interpreter with WordPress APIs mocked. |
| PHP syntax | All eight PHP files passed on 7.4 and 8.2: **16 interpreter checks**. |
| JavaScript and source | Mocked enquiry tests; all 17 HTML/native pairs, content parity, headings/IDs, routes/assets, real anchors, service choices and balanced markup passed. |
| Action inventory | **605 rendered actions** recorded across 17 shortcode fixtures; internal routes/fragments resolve. External messages were not sent and product availability is not claimed. |
| Packaging | ZIP integrity passed; one `grant-publishing-site/` root, **56 files** byte-identical to final source. No real access key or dependencies included. |

Browser tests use local fixtures with synthetic shared shell/form markup. Native fixtures approximate Elementor containers/widgets and do not run WordPress or a theme. Intended Instrument Serif/Manrope fonts were mirrored from official Google Fonts URLs using verified TLS; font files and reference-site assets are not bundled in the plugin. Other interaction suites also exercise fallback fonts with remote fonts blocked. Automated accessibility checks plus focused keyboard tests do not establish a complete assistive-technology audit.

The [gallery](GALLERY.md) has desktop and phone screenshots for all pages. [Design review](DESIGN-REVIEW.md) includes before/after Home, About, Services and Contact comparisons with the published 3.4 baseline.

## Read-only public observations

On **8 October**, all 17 live routes returned HTTP 200 in the prior release audit. A later six-page audit found Home/About LiteSpeed cache hits referencing 3.2, Services/Feedback hits referencing 3.3, and uncached Contact/Assessment referencing both 3.4 stylesheets. Cache-miss Home/About responses also served 3.4. All six were full-page Grant shortcodes inside Elementor, so template changes apply after cache clearing. Served versions are cache observations, not proof of the installed plugin version.

Fresh Home/Contact responses still contained duplicate title tags from the existing theme/Elementor Canvas integration. The Grant plugin does not print an extra title; global theme title behavior was not changed. Some stale cached generated Elementor CSS URLs returned HTML. Regenerate Elementor files if stale and review title integration on staging.

The publisher home page returned HTTP 200 but displayed Coming Soon. The LinkedIn destination returned a sign-in page, so independent public verification of the review/cover was unavailable. Its destination and quote are user-supplied. Amazon availability was not independently verified. No external submission or message was sent.

## Remaining acceptance checks

1. Obtain the original Luma PNG/JPG as a downloadable attachment and bundle it, linking the cover to the confirmed LinkedIn review page. This is the remaining artwork task.
2. Follow [installation instructions](INSTALLATION.md) on staging. Replace the plugin, clear relevant caches and confirm both stylesheets use 4.0.0. Do not recreate or bulk-convert edited pages.
3. Review all routes in actual WordPress/Elementor/theme rendering and on target browsers/devices, including logged-in toolbar, keyboard and saved native edits. Cache hooks and activation have been mocked, not exercised on the user's installation.
4. Keep Contact/Assessment uncached. Preserve existing Web3Forms settings or configure privately if absent; send one labelled test yourself and verify the associated inbox and reply address. Account connectivity, restrictions, dashboard behavior and inbox receipt remain unverified. Provider acceptance does not guarantee receipt; duplicate protection is not atomic exactly-once delivery.
5. Review duplicate titles and any generated CSS conflicts. Roll back with retained 3.4.0 and clear caches if necessary; no page-content migration is involved.

## Evidence

- [272 browser results](evidence/visual-results-4.0.json), [32 final form results](evidence/visual-targeted-4.0.json)
- [Navigation/destination results](evidence/flows-results-4.0.txt), [optional-field/toolbar results](evidence/optional-results-4.0.txt), [form/keyboard/contrast results](evidence/interactions-4.0-log.txt)
- [PHP backend 7.4](evidence/backend-php74.txt), [8.2](evidence/backend-php82.txt), [8.3](evidence/backend-php83.txt)
- [Review compatibility 7.4](evidence/publishing-php74.txt), [8.2](evidence/publishing-php82.txt), [8.3](evidence/publishing-php83.txt)
- [Upgrade checks 7.4](evidence/upgrades-php74.txt), [8.2](evidence/upgrades-php82.txt), [8.3](evidence/upgrades-php83.txt), [syntax checks](evidence/php-lint-results.json)
- [Action inventory](evidence/route-actions.json), [package/preservation checks](evidence/package-validation.txt)
- [Six-page cache observations](evidence/live-cache-observations-2026-10-08.json), [17-route observations](evidence/live-route-observations-2026-10-08.json)
- [Browser runners and prerequisites](evidence/browser-runners/README.md), [checksums](SHA256SUMS.txt)

The separate evidence ZIP contains reports/screenshots/runners and is for review, not WordPress installation.
