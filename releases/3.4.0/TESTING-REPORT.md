# Grant Publishing Co. 3.4.0 testing report

Prepared on 8 October 2026. The installable plugin and source are published to GitHub; **this release has not been installed on the live WordPress site**.

## Change and preservation

The confirmed reference was [OW Publishing House](https://owpublishiing.com/) (double `i`). Its public HTML/CSS and four mirrored page renders were reviewed. Grant now uses navy heroes/navigation, warm cream surfaces, gold actions, framed cards and consistent spacing while retaining its own design structure, content and images.

Compared with the published 3.3.0 source, all 17 HTML templates, all 17 Elementor layouts (727 native elements), page definitions, supplied logo, book cover and portrait are unchanged. The base stylesheet, navigation script, form script and Web3Forms provider code are unchanged. Version 3.4.0 adds a scoped `assets/design.css` layer and loads it after `site.css` through both normal enqueue and late shortcode rendering. The prominent Home/About portrait sizing from 3.3 is retained. Updating does not rewrite saved WordPress/Elementor content or delivery settings.

Small text on cream uses deeper gold `#785719`; filled gold actions use navy text. Keyboard focus remains visible, informational panels do not imply click behavior, and reduced-motion preferences are respected.

## Completed checks

| Check | Result and scope |
|---|---|
| Main browser layout/accessibility suite | **204 combinations passed:** 17 pages × shortcode/native-layout fixtures × six viewports. Intended Instrument Serif/Manrope fonts and supplied images loaded. No horizontal overflow or axe WCAG A/AA violations were reported. |
| Viewports | 1440 × 900, 1024 × 900, 768 × 900, 390 × 844, 320 × 900 and 844 × 390 landscape. |
| Final form layout rerun | **24 combinations passed** for Contact and Assessment after adjusting textarea specificity; confirms the final stylesheet at all six viewports in both fixture paths. |
| Font-fallback browser suite | **102 combinations passed:** all 17 pages, both paths, 1440/390/320 widths with remote fonts blocked. No overflow or automated accessibility failures. |
| Keyboard/navigation | Skip link, menu Tab traversal, Escape with focus return, outside-click dismissal, desktop resize and reduced motion passed in Chromium. |
| Sticky navigation/short screens | **17 focused assertions passed:** form anchor visibility, textarea height, menu bounds/scrolling at a short viewport, Escape and simulated WordPress admin-toolbar offset. |
| Forms in Chromium | Required fields, request/service preselection, action labels, disabled/busy sending state, success/reset, expired-nonce response, network/malformed-response recovery, retained input and focused feedback passed. All submissions were intercepted locally. |
| Theme contrast protection | Simulated inherited white theme text and light/dark button hover/focus checks passed. A footer button specificity conflict found during development was fixed before the final passing run. |
| PHP backend/assets | **62 assertions passed on each of PHP 7.4, 8.2 and 8.3**, using the official WordPress Playground PHP runtime with WordPress APIs mocked. Covers private settings, validation, nonce/consent/honeypot, rate/duplicate handling, strict provider responses and both stylesheet loading paths. |
| PHP syntax | All six PHP files passed on PHP 7.4 and 8.2: **12 interpreter checks**. |
| JavaScript/source structure | Existing enquiry mock tests passed. All 17 matching HTML/Elementor pairs, 727 native elements, headings/IDs, route/asset tokens, service choices, form anchors and balanced markup passed. |
| Action destinations | **570 actions** across the 17 shortcode fixtures were audited; internal routes and fragment destinations resolve. External email/social/WhatsApp actions were not sent or messaged. |
| Public live routes | **17/17 returned HTTP 200** in a read-only HTTPS audit. This checked availability and served markup, not the new design installation. |

Browser checks used local fixtures generated from the supplied plugin templates/layouts and shared shell. Native fixtures approximate Elementor's rendered containers/widgets; they do not run Elementor or a WordPress theme. Grant font files were downloaded from their existing official Google Fonts URLs with TLS verification and served locally for consistent screenshots. No font or reference assets were added to the plugin.

[The gallery](GALLERY.md) contains desktop and phone captures for every page. Before/after comparisons cover Home, About, Services and Contact. [Design review](DESIGN-REVIEW.md) explains the reference adaptation.

## Public-site observations requiring staging review

The live HTML referenced a mixture of `site.css?ver=3.2.0` and `3.3.0` across routes. These are served-HTML observations and do not establish the installed plugin version; page caches may differ. After installation, confirm both `site.css` and `design.css` are served with version `3.4.0` and clear all relevant caches.

All 17 public responses contained two `<title>` tags in the document head. Review the WordPress theme/SEO/plugin title integration on staging. This existing issue is outside the stylesheet refinement and remains unresolved in this release. Each audited page had one H1 and one main landmark.

## Not verified

- Actual plugin replacement/activation inside the user's WordPress, saved Elementor edits, theme conflicts, cache purging and a logged-in admin toolbar have not been tested on that installation.
- Web3Forms account connectivity, account domain restrictions, dashboard behavior and inbox receipt have not been tested. No real enquiry, email or WhatsApp message was sent; no real key was included in tests, source or ZIPs.
- Automated accessibility checks and focused keyboard tests do not constitute a full accessibility audit with assistive technology. Other browsers/devices were not tested.
- A provider success response indicates acceptance, not guaranteed inbox delivery. Duplicate protection is not an atomic exactly-once delivery guarantee for simultaneous requests.

## Installation acceptance checks

1. Back up the site/database and test the ZIP on staging. Replace the existing plugin; do not recreate pages or run bulk Elementor conversion.
2. Clear WordPress/LiteSpeed, CDN and browser caches; regenerate Elementor CSS & Data if stale. Confirm both 3.4.0 stylesheets load. Keep Contact and Assessment out of full-page caching.
3. Review all 17 routes on phone/tablet/desktop, the large Home/About portraits, keyboard navigation, short-screen menus, sticky-header anchors and form feedback. Review the duplicate-title integration.
4. Existing Web3Forms settings should remain saved. If not previously configured, use **Tools → Grant Website Setup → Enquiry delivery** to save the key privately. Send one clearly labelled test with your own email, verify the linked inbox, and confirm the assessment/service selection and reply address.
5. Roll back by replacing the plugin with the retained 3.3.0 ZIP and clearing the same caches. Both versions support the saved delivery settings; no database migration is involved.

## Evidence

- [204 rendered checks](evidence/visual-results-3.4.json), [final targeted checks](evidence/visual-targeted-3.4.json), [font-fallback checks](evidence/browser-results.json)
- [Form/keyboard results](evidence/interactions-3.4-log.txt), [sticky-navigation results](evidence/navigation-results-3.4.txt)
- [PHP 7.4](evidence/backend-3.4-php74.txt), [PHP 8.2](evidence/backend-3.4-php82.txt), [PHP 8.3](evidence/backend-3.4-php83.txt), [syntax results](evidence/php-lint-results.json)
- [Internal action inventory](evidence/route-action-3.4.json), [read-only live route observations](evidence/live-route-results.json), [preservation/package checks](evidence/package-validation.txt)
- [Reference audit](evidence/REFERENCE-AUDIT.md). Reference images are labelled local mirrors of downloaded public assets, not live Chromium navigation.

The plugin ZIP has one `grant-publishing-site/` root with 49 files. The separate evidence ZIP is for review, not WordPress installation. Both archive hashes are in [SHA256SUMS.txt](SHA256SUMS.txt).
