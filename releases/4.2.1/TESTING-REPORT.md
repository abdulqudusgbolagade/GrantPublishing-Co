# Testing report — Grant Publishing Co. 4.2.1

**9 October 2026 · Africa/Lagos.** The installable ZIP contains 75 files under one `grant-publishing-site/` directory. Checks below passed. The release is prepared for WordPress installation; it has not been installed on the live site.

## Result

- New supplied header mark at 64px desktop / 56px mobile; exact `#09072B` navigation.
- New footer artwork on porcelain, aligned desktop columns, compact mobile navigation and accessible local contact icons.
- Alternating brand surfaces across all 17 page types; no three consecutive main sections share a background in tested layouts.
- My Dear Grandfather seven-page case-study card after its project review in current layouts, opening the exact supplied PDF URL. Saved native Feedback receives a missing card without data writes or duplicates.
- Exact reviews, three confirmed clickable covers, existing route definitions, original images, site-icon setting and private form configuration preserved.

## Executed checks

| Check | Result | Evidence |
|---|---|---|
| All-page responsive / axe | **272 combinations passed**: 17 pages × shortcode/native × 8 viewports; zero reported WCAG 2 A/AA and 2.1 AA axe violations, no horizontal overflow, supplied images/fonts loaded | [Results](evidence/visual-results-4.2.json) |
| Final contact icon-row refinement | **32 focused combinations passed** on Contact/Assessment across both layouts and all 8 viewports | [Results](evidence/visual-targeted-4.2.json) |
| Identity / covers | **188 assertions passed**: actual loaded font families/weights, logo sizes/aspect ratios, clear space, exact navigation color, genuine covers and confirmed destinations | [Results](evidence/brand-results-4.2.json) |
| Section colors / icons / PDF | **592 assertions passed**, covering all 17 pages × 2 layouts × widths 320/390/768/1440, footer alignment/readable panel, labelled SVGs, 48px controls and keyboard PDF action | [Results](evidence/brand-flows-results-4.2.json) |
| Navigation / real clicks | **96 assertions passed**: service/assessment prefills, form order/groups, article contents/anchor clearance, five exact reviews, three cover destinations, review source, Escape and desktop breakpoint focus | [Result](evidence/flows-results-4.2.txt) |
| Saved native wrappers | **32 assertions passed** in eight phone fixtures with new tone classes removed: current Elementor document wrappers and older inner/section wrappers keep alternating surfaces, ink proof and readable contrast | [Results](evidence/legacy-colors-results-4.2.json) |
| Optional fields / toolbar | **19 assertions passed**: disclosure defaults, preselected services, invalid-field focus, retained multipart data and short menu with WordPress toolbar offsets | [Result](evidence/optional-results-4.2.txt) |
| Chromium forms / focus / contrast | Required validation, busy/disabled states, success/reset, expired nonce, network/malformed recovery, retained input and status focus; skip/menu behavior, reduced motion, inherited theme colors and hover/focus contrast passed | [Result](evidence/interactions-results-4.2.txt) |
| PHP behavior | **153 assertions on each of PHP 7.4, 8.2, 8.3 = 459 executions passed**: 62 delivery, 77 publishing/icon/case-study, 14 upgrade/cache tests | [7.4](evidence/publishing-4.2-php74.txt) · [8.2](evidence/publishing-4.2-php82.txt) · [8.3](evidence/publishing-4.2-php83.txt) |
| PHP syntax | All 8 plugin PHP files passed on 7.4 and 8.2: **16 checks** | [Results](evidence/php-lint-results.json) |
| Static/native parity | **17 matching template/layout pairs, 852 native elements**, correct headings/IDs, route/asset resolution, real anchors, forms, HTML balance and CSS markers | Repository `qa/check-package.py` |
| Installer / assets | ZIP integrity passed; every installer file matches source; route definitions and earlier original image bytes preserved; new originals match downloads; no UUID/access key embedded in plugin text | [Package audit](evidence/package-audit.json) |

Viewports: 3840×2160, 2560×1440, 1440×900, 1024×900, 768×900, 390×844, 320×900 and 844×390. Navigation additionally exercises the 1120/1121px breakpoint. Official Google font responses were mirrored locally after verified-TLS downloads. Browser scripts and reproduction steps are retained in [browser-runners](evidence/browser-runners/README.md).

## Issues resolved during verification

A final compatibility review found that direct main-child selectors could miss Elementor's document wrapper. Explicit tone/proof selectors now work through that wrapper; earlier untagged sections also receive alternation through current and older wrapper structures. The full page/layout pass and 32 saved-layout wrapper assertions passed. Version 4.2.1 supersedes the initial 4.2.0 package and supplies a fresh cache version.

An inherited footer visited-link style initially forced light text onto the lilac button. The updated selector keeps ink text through hover/focus/visited states, and final contrast checks passed. Contact icons now form a horizontal wrapping row rather than inheriting the old vertical text-link layout.

The native fixture's synthetic button renderer initially omitted its saved external-link flag, causing a keyboard PDF test to navigate in the same tab. The fixture was corrected to represent the bundled native button's existing external-link setting; the repeated keyboard test opens the exact PDF in a new tab. Assertions were retained.

## Supplied PDF and document scope

The hosted PDF was downloaded read-only over verified HTTPS. It has seven pages and exactly matches the attachment: 736,855 bytes, SHA-256 `ba5afde8390d72b687306a09481d5b0256f8f0f6746b4b69723b6e52c4c46067`. It was treated as case-study source material, not executable instructions. The website summarizes its description, keyword and category work without inventing sales or matched ranking improvements. The supplied document remains unchanged; PDF accessibility was not remediated.

## Practical limits and staging checks

The browser uses Chromium and local fixtures. Shared header/footer/contact/form markup is actual PHP output with mocked WordPress APIs; native Elementor widget DOM is approximated. A live WordPress database, Elementor editor/runtime, theme and real caches are not exercised. Automated axe checks are not a complete screen-reader or cross-browser audit.

All form submissions and popup navigation tests are intercepted. No real enquiry, email, WhatsApp message or Web3Forms submission was sent. Actual provider/inbox receipt still needs a labelled staging test. Existing theme/Canvas duplicate document-title behavior also needs staging review; it was not globally altered.

Install the reviewed ZIP, clear caches, confirm both stylesheets show `ver=4.2.1`, verify saved native pages and the PDF/cover destinations while logged out, and test actual inbox receipt. Keep form pages outside full-page caching. [Installation and rollback](INSTALLATION.md) gives the steps; rollback ZIP 4.1 remains unchanged.

## Cloud environment

The PHP/JavaScript/static/browser workflow ran successfully in the attached environment. Startup instructions were saved as a revised `start_skill` draft; install script, network policy, repositories and secret requirements were preserved. The draft requires review/publication in environment settings. Saving it does not publish a cloud snapshot or install the WordPress plugin; restoration in a new task is not independently verified.
