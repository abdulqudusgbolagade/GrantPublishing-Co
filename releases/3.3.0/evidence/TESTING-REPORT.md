# Grant Publishing Co. 3.3.0 — portraits and Web3Forms

Prepared 8 October 2026 as an update to 3.2.0. These files have not been installed on the live WordPress website.

## Changes

The Home portrait now has a dedicated column within its existing founder section, up to 380 px wide rather than 160 px. About uses a portrait column up to 420 px rather than 240 px. Phone portraits are up to 340 px and fit smaller screens; the original 500×500 PNG is unchanged, with no crop or replacement. Copy, headings, route definitions, page order and section order are preserved. Portrait surfaces use the existing cream, ink and purple/blue palette.

Contact and Assessment enquiries can now use Web3Forms. Administrators save an access key under **Tools → Grant Website Setup → Enquiry delivery**. Saving a new valid key selects Web3Forms; until then, existing installs retain WordPress email. The key remains server-side in this WordPress installation, is never included in public markup, and is not distributed in GitHub source or either ZIP. The administrator input is masked and never prefilled; blank saves preserve the key, provider switching can pause it, and Remove key deletes it.

The server posts JSON to `https://api.web3forms.com/submit`, including name, email/reply address, request type, message, book title/link, service, publication status, author website and contact consent. Recipient selection is controlled by the Web3Forms key; the site's public email link remains unchanged. Existing WordPress nonce, required/optional fields, honeypot, validation, rate limit and hashed duplicate checks remain. Visitor privacy text reflects the selected provider.

The sender sees confirmed success only for an HTTP 2xx response with JSON `success === true`. Confirmed rejection permits an intentional retry. Timeout, transport failure, malformed JSON, unexpected redirects and HTTP 5xx retain uncertainty and entered text; matching enquiries are held for 10 minutes while the sender checks receipt. There is no automatic resend or email fallback. Hashed identifiers support duplicate checks; message bodies are not stored in WordPress by this plugin. This is sequential duplicate protection, not a guarantee of atomic delivery under concurrent requests.

## Validation

| Check | Result and scope |
|---|---|
| Real PHP backend tests | **58 assertions pass on PHP 7.4 and PHP 8.2** (116 executions): provider selection; all payload fields; fixed HTTPS endpoint; TLS verification and redirects disabled; default email behavior; no fallback; HTTP 400/401/403/422/429; API rejection; timeout/transport failure, HTTP 5xx/redirects/malformed JSON; duplicate holds and retry after confirmed rejection; nonce, validation, honeypot, consent and rate limit; admin permissions/CSRF/POST; preserve/remove/masked key; privacy disclosure; no-JavaScript uncertain delivery title/body |
| Real PHP syntax | All six plugin PHP files lint on PHP 7.4 and 8.2: **12 checks** |
| Package/static checks | **17 pages, 727 native Elementor elements**, balanced fallback HTML, H1/main, IDs, tokens, anchors and supported service selections; unchanged page copy, heading-level sequence, routes and supplied image bytes |
| Browser accessibility/layout | **102 Chromium fixture combinations**: 17 pages × fallback/approximate native layout × 1440/390/320 px widths, 900 px height; zero axe-reported WCAG A/AA violations and no document horizontal overflow |
| Portrait behavior | **24 combinations**: Home/About × fallback/native × 1440/980/768/720/390/320 px; larger bounded portraits remain square, inside the viewport and without horizontal overflow; all images loaded |
| Browser form/menu regression | Required fields; query preselection; action labels; disabled/busy/progress state; success/reset; server rejection, network and malformed-response recovery; retained input and focused status; skip link, menu Tab/Escape/focus return, outside close, resize close; reduced motion and representative hover/focus contrast under simulated inherited white theme text |
| Route/action audit | **570 action records across all 17 fallback pages**, with header/footer/contact actions included; internal routes and anchor IDs resolve. External URLs recorded, not network-verified |
| Package integrity | Single `grant-publishing-site/` ZIP root, consistent 3.3.0 header/constant, archive integrity and byte-for-byte source comparison; checksums provided |
| Visual evidence | **16 screenshots** comparing 3.2 to 3.3 Home/About at 1440 and 390 px: full pages and founder-section crops |

Backend tests execute actual PHP interpreters from the integrity-verified official WordPress Playground runtime and stub WordPress APIs. The runtime wrapper preserves PHP exit codes so failed assertions cannot look successful. Official API fields and boolean success behavior were checked against [Web3Forms React source](https://github.com/web3forms/web3forms-react/blob/653a4ba0fc9a70526b9aff7796858ebf5965cc3b/src/index.tsx).

All provider HTTP, email and database writes in these tests were mocked. **No live enquiry was submitted, no email was sent and no real access key was used in tests.**

## Reference and integration limits

`owpublishiing.com` was explicitly confirmed as the reference address, but this environment's network proxy returns CONNECT 403. No visual inspection or design comparison was possible. This release improves portrait prominence within the existing design and does not claim to match that reference. Access or reference screenshots are needed for further color/organization work.

The required reference/site/Web3Forms/font domains were saved to the cloud environment's network configuration draft. That save does not apply the network policy or publish the environment; review/save and publication in environment settings remain required. These cloud settings do not configure the separate WordPress host.

Browser evidence uses local resolved templates and approximate native Elementor DOM, not a running WordPress/Elementor installation. PHP form output, nonce and HTTP results are synthetic in browser fixtures; backend tests separately exercise real PHP with WordPress stubs. Google Fonts were deliberately blocked for repeatable browser tests, so screenshots use fallback fonts; Instrument Serif/Manrope references remain unchanged. Automated accessibility checks do not establish full conformance or cover all assistive technologies.

Actual WordPress activation, saved Elementor/theme overrides, key/account validity, outbound hosting access, Web3Forms domain restrictions and inbox/dashboard availability remain unverified. Web3Forms response acceptance does not establish inbox delivery or dashboard storage; available provider features depend on the account. Real iOS/Android browsers, short landscape layouts and no-JavaScript backend submission still need staging checks.

## Install and activate delivery

1. Back up plugin and database/Elementor content; retain the 3.2.0 package. Test on staging.
2. Download `grant-publishing-site-3.3.0.zip`, upload it in WordPress Plugins → Add New → Upload Plugin and replace the current plugin. The evidence ZIP and GitHub whole-repository ZIP are not installable plugins.
3. Clear caches and regenerate Elementor CSS & Data if stale. Do not create pages or run bulk conversion. Existing page records and Elementor edits are preserved; shared CSS updates recognized component classes.
4. Open **Tools → Grant Website Setup → Enquiry delivery**. Paste the access key from your Web3Forms account into the masked field and select **Save enquiry delivery**. Saving a valid key selects Web3Forms. This step must happen in the actual WordPress site; GitHub publication cannot save its options.
5. Keep Contact and Assessment outside full-page caching to avoid stale nonce tokens. Hosting must allow outbound HTTPS to `api.web3forms.com`.
6. Send one clearly labelled test enquiry yourself and confirm it in the inbox connected to the key. Verify all optional fields and reply address. If account domain restrictions are enabled, test on the intended site domain. Do not infer receipt or dashboard storage from the success message alone.
7. Review Home/About portrait placement on phone and desktop, then check all 17 routes listed in the 3.2 report. Test keyboard navigation, mobile menu, service/request preselection, failure recovery and the non-JavaScript fallback on staging.

## Rollback

Select WordPress email and optionally Remove key before replacing the plugin with 3.2.0, then clear caches. Old versions ignore the new delivery option. No page migration or automatic Elementor conversion occurs; database/page restoration is unnecessary unless separate manual content edits were made. Uninstalling/replacing files does not automatically delete the saved key; remove it through the setting first if desired.
