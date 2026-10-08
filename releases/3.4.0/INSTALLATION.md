# Grant Publishing Co. 3.4.0 — navy, cream and gold refinement

Install `grant-publishing-site-3.4.0.zip`, which contains one `grant-publishing-site/` folder. WordPress 6.0+, PHP 7.4+; Elementor 3.16+ for native editing. The 17 existing shortcodes also work without Elementor.

## Upgrade

1. Back up the plugin and database/Elementor content. Test on staging first.
2. In WordPress → Plugins → Add New → Upload Plugin, upload the ZIP and replace the current plugin. Activate if needed.
3. Clear WordPress/LiteSpeed, CDN, browser and Elementor caches. Regenerate Elementor CSS & Data if stale. The new `design.css` layer must load alongside `site.css`, both with version 3.4.0. Cached HTML can otherwise continue referencing an old release.
4. Do not recreate pages or run bulk Elementor conversion. Existing page records, copy, routes, section order and saved edits are preserved; shared styles refine recognized components in both rendering paths.
5. Check the 17 routes on desktop, phone and tablet, including keyboard focus, the sticky header/menu, form anchors and failure feedback. Keep Contact and Assessment out of full-page caching so nonce tokens do not expire in cached pages.

## Design changes

The visual reference was the confirmed `https://owpublishiing.com` OW Publishing House site: navy opening bands, warm cream sections, restrained gold accents, pill-shaped actions, framed cards and generous spacing. Grant keeps its own logo, real book cover, founder portrait, Instrument Serif/Manrope typography and truthful content. No reference copy, fabricated statistics or reference images were added to the plugin.

The upgrade adds a compact sticky navy navigation bar with the existing logo on a cream plate; navy page heroes; gold actions with readable navy text; deeper gold for small text on cream; clearer service, article, process, testimonial and contact surfaces; and consistent responsive spacing. Informational process panels and testimonials have no clickable hover effects. Title/action links, form fields, mobile dismissal and reduced-motion behavior are retained. The prominent Home/About portrait columns from 3.3 remain.

## Web3Forms

If Web3Forms is already configured, its saved key and selected provider remain. There is no need to enter the key again for this upgrade.

For initial setup, open **Tools → Grant Website Setup → Enquiry delivery**, paste the key from your Web3Forms account into the masked field and click **Save enquiry delivery**. Saving a valid key selects Web3Forms. Until then, WordPress email remains selected. The key stays in WordPress and is never distributed in source/ZIPs or public form markup.

Delivery uses the inbox associated with the Web3Forms key, while public email links remain unchanged. The server requires outbound verified HTTPS to `api.web3forms.com`. A provider success response confirms acceptance, not inbox delivery or dashboard storage. Send one labelled test yourself and verify receipt. Do not assume account domain restrictions work without that test.

Failures retain entries. Unknown delivery outcomes do not silently trigger email fallback or automatic retries; matching enquiries are held briefly while the sender checks receipt. Existing nonce, honeypot, consent, field validation, rate limiting and duplicate checks remain.

## Testing and limits

The release report in the GitHub repository documents 204 rendered page/layout/viewport combinations with the intended font files, 24 targeted form layout checks, keyboard/menu/contrast checks, 17 sticky-anchor/short-screen checks and 62 PHP backend/asset assertions on 7.4, 8.2 and 8.3. Reference screenshots were made from a local mirror of actual reference assets downloaded with verified TLS; Grant screenshots use local fixtures, not the installed live site.

The live route audit returned 200 for all 17 pages, but cached HTML referenced 3.2.0/3.3.0 styles and showed duplicate title tags. Actual WordPress/Elementor/theme rendering, caches, account connectivity and inbox receipt need staging verification. This release has not been installed live.

## Rollback

Replace the plugin with the retained 3.3.0 ZIP and clear the same caches. Saved Web3Forms settings remain because both releases support them. No database migration or automatic page conversion occurs. Restore content backups only if separate manual edits were made. To stop Web3Forms, select WordPress email under Enquiry delivery; Remove key erases the stored key if desired.
