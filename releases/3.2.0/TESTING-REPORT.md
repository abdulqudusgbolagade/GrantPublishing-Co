# Grant Publishing Co. 3.2.0 — testing and installation report

Prepared 8 October 2026 from the supplied 3.1.0 handoff. This is an installable plugin update, not a live deployment.

## Visitor-facing changes

- Scoped paragraph, label, footer, quote, footnote and button-span colors protect readability against inherited theme colors while retaining light text on dark sections. Placeholders and disabled submission controls also retain readable colors.
- Service and article headings now offer genuine links to their existing destinations. Descriptive action names distinguish repeated “Read article” and “Explore service” links for assistive technology. Native anchor behavior, keyboard operation and new-tab use are preserved; no whole-card click handlers or nested controls were introduced.
- Insights cards gain restrained surfaces and focus feedback. Existing service cards, section order and editorial design remain. Informational process steps and testimonials do not gain misleading click effects.
- Mobile navigation closes with Escape, outside click, focus leaving the menu, a navigation choice, or returning to desktop. Escape returns focus to Menu. Short-screen menus can scroll. A shared skip link covers shortcode and native full-page rendering paths.
- The enquiry form identifies project versus assessment actions, announces progress, exposes its busy state, disables duplicate submission, distinguishes success/error feedback, focuses feedback and retains entered text on failure.
- A real browser test uncovered and verified a fix for AJAX submission: WordPress’s hidden `name="action"` field masks the `form.action` DOM property. Fetch now reads `getAttribute('action')`, so it uses the actual submission endpoint.

The 17 page definitions/URLs, section order, page copy and heading levels are preserved. Supplied logo, portrait and book-cover files match the baseline byte for byte. Existing WordPress page records and saved Elementor edits are not rewritten. Nonce, consent, honeypot, rate-limit, duplicate protection and server-side validation/mail handling remain intact.

## Checks completed

| Check | Result | Scope |
|---|---|---|
| Existing static package suite | PASS | 17 matching HTML/native JSON layouts; 727 native elements; balanced fallback HTML; H1/main, IDs, route/asset tokens, anchors and supported service query choices |
| Source preservation audit | PASS | Unchanged page text, heading-level sequence, `pages.json`, supplied images; no page migration or automatic conversion added |
| Route/action audit | PASS | 570 action records across 17 rendered fallback fixtures, including shared header/footer/contact links; internal destinations and target IDs resolve; external destinations recorded, not fetched |
| Chromium + axe accessibility sweep | PASS in fixtures | 102 combinations: all 17 pages × fallback/approximate native DOM × widths 1440, 390 and 320 px, at 900 px height; zero reported WCAG A/AA rule violations and no document horizontal overflow |
| Form mock unit checks | PASS | Success, rejection, connection failure, malformed JSON, invalid input and request/service preselection |
| Real Chromium form behavior | PASS with intercepted responses | Required fields, request/service selection, action labels, pending/disabled/busy feedback, success reset, expired-form rejection, network/malformed recovery, retained input and focused status; actual form markup includes hidden WordPress action field |
| Keyboard/navigation | PASS in fixtures | Skip link to main, menu opening, Tab into menu, Escape/focus return, outside click, close when resized to desktop |
| Readability regression | PASS in fixtures | Injected common white paragraph/button-span theme rules; checked body text and small footnote; contrast scan while representative light/dark buttons were hovered/focused |
| Reduced motion | PASS | Browser-emulated reduced motion removes service-card transitions; shared CSS also removes animations |
| PHP compatibility syntax | PASS parser check | Five PHP files parsed using PHP 7.4 grammar with `php-parser`; **not PHP runtime execution or WordPress integration** |
| Package integrity | PASS | ZIP has a single `grant-publishing-site/` plugin root, consistent 3.2.0 header/constant, and exact source-to-archive byte matches |
| Visual evidence | Completed | Before/after Home and Contact at 1440 and 390 px; bundled images loaded; supplied layout/sections retained |

Automated accessibility checks do not establish complete accessibility conformance. Hover/focus checks cover representative shared components, not every possible style override or assistive technology combination.

## Pages covered

| Page | Route |
|---|---|
| Home | `/` |
| Services | `/services/` |
| Free Initial Book Assessment | `/book-marketing-audit/` |
| About | `/about/` |
| Client Feedback | `/client-feedback/` |
| Insights | `/insights/` |
| Contact | `/contact/` |
| Amazon Book Visibility | `/services/amazon-visibility/` |
| Book Descriptions & A+ Content | `/services/book-presentation/` |
| Book Launch & Promotion | `/services/launch-promotion/` |
| Author Platform | `/services/author-platform/` |
| Series & Catalog Strategy | `/services/catalog-strategy/` |
| Book Marketing Strategy | `/services/book-strategy/` |
| Publishing Support | `/services/publishing-support/` |
| Book Discovery article | `/insights/book-discovery/` |
| Book Product Page article | `/insights/book-product-page/` |
| Connected Catalog article | `/insights/connected-catalog/` |

## Evidence and limits

`grant-publishing-3.2.0-evidence.zip` contains eight before/after PNGs, machine-readable browser and route/action results, test scripts and this report. Screenshots use local HTML fixtures, not the live website. External Google Fonts were deliberately blocked during fixture tests, so screenshots show fallback typography; the plugin retains its Instrument Serif/Manrope references unchanged.

Fallback fixtures resolve supplied templates with shared PHP markup and a synthetic form. Native fixtures approximate Elementor’s container/widget output from the JSON, including both `css_classes` and `_css_classes`; they do not execute Elementor. Native image metadata and custom Elementor/theme CSS need verification on WordPress. Form security tokens and backend responses were mocked. No email or WhatsApp message was sent.

A read-only request to `https://grantpublishingco.com/` returned a network-proxy CONNECT 403. Its current plugin version, live route availability and live unreadable-text locations could not be inspected here. There is no WordPress administrator session, PHP runtime or site database in this workspace. Actual activation, native editor rendering, mail delivery, external profile availability, Google Fonts loading, page caching and no-JavaScript backend submission remain unverified. Locally passing fixtures do not establish that the live website has been updated or that a specific unidentified live text defect has been reproduced.

## Install and verify on staging

1. Back up the active plugin folder and WordPress database/Elementor content. Keep the supplied 3.1.0 baseline for rollback.
2. Upload **grant-publishing-site-3.2.0.zip** in WordPress Plugins > Add New > Upload Plugin, then replace the existing plugin. The evidence ZIP and original outer handoff ZIP are not installable plugins.
3. Do not recreate pages or apply bulk Elementor conversion for this upgrade. Existing recognized pages use shared CSS/JS; changes to stored Elementor content are intentionally preserved. Title links for existing native layouts are added at runtime using current action destinations.
4. Clear plugin/page/CDN/browser caches and, if necessary, regenerate Elementor CSS & Data. Exclude Contact and Assessment from full-page caching to avoid stale nonce tokens.
5. Open every route above at desktop and phone widths. Verify the existing fonts, portraits, heading hierarchy, child text colors and all light/dark button states under the actual theme. Test menu behavior, skip link, current-page indication, Tab order, readable focus and long enquiry text. Check short landscape screens and real iOS/Android browsers, which were not exercised locally.
6. Follow each service CTA to Contact and verify service selection; follow assessment CTAs and verify request selection. Test a required-field failure and safely simulate expired nonce/mail failure on staging. Check native Elementor editing without applying conversion or overwriting edits.
7. With explicit authorization, send one staging delivery test to the configured recipient and confirm receipt. Do not infer inbox delivery from the success response alone. Check the non-JavaScript fallback separately.

## Rollback

Replace the plugin with the backed-up version or supplied 3.1.0 ZIP, then clear the same caches. This release performs no database migration, page creation or automatic Elementor conversion. Restore database/page backups only if additional manual edits were made. Do not use the setup tool’s conversion restoration action solely to roll back shared CSS/JS.
