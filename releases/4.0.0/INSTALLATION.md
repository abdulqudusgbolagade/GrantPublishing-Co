# Grant Publishing Co. 4.0.0

WordPress 6.0+, PHP 7.4+. Elementor 3.16+ is required for native editing; the 17 Grant shortcodes also work without Elementor.

## Upgrade an existing site

1. Back up the plugin, database and saved Elementor content. Test on staging.
2. Upload `grant-publishing-site-4.0.0.zip` through **Plugins → Add New → Upload Plugin** and choose **Replace current with uploaded**. Activate if necessary.
3. Clear hosting/LiteSpeed, CDN and browser caches. Regenerate Elementor CSS & Data if stale. Both `site.css` and `design.css` must load with `ver=4.0.0`.
4. **Do not recreate pages or bulk-convert existing Elementor layouts.** Shared styles and shortcode templates update; already saved native page content is preserved.
5. Keep `/contact/` and `/book-marketing-audit/` excluded from full-page caching, then check all routes, menu, links and forms while logged out.

The plugin refreshes generated Elementor caches and requests LiteSpeed URL purges for connected, published Grant pages once per version. It does not clear the whole site or rewrite saved content. Other host/CDN caches may still require manual clearing. Earlier canonical pages served old cached markup while uncached form pages served newer styles.

## Design and interactions

All 17 pages use Grant blue `#363caf`, paper cream and ink with the existing Instrument Serif/Manrope fonts and logo. Home has an optimized decorative studio still life; founder portraits remain prominent. Services present concrete scope items and early enquiries that preselect the relevant service. Insights have a featured story; articles have genuine in-page contents and a comfortable reading measure. Forms put the enquiry before secondary information on mobile, group fields and disclose optional book details when useful.

The genuine Kathryn's Beach cover links to the user-confirmed Amazon product `https://www.amazon.com/dp/1947646168`. The additional five-star Nadine Laman quote and exact John Capon Graphic Design review are included. Luma's metadata and source link use `https://www.linkedin.com/services/page/a29863343146852122/`, as requested while North & Mercer is under construction. **The original Luma PNG/JPG remains pending**: the inline image had no downloadable file in the workspace. No substitute cover or missing-image element is included.

The logo, portrait and Kathryn cover are unchanged genuine supplied files. The studio image is generated decorative brand imagery, not a client project. No fabricated client, sales, ranking or portfolio claims are added.

## Saved Elementor pages

The bundled native JSON matches the updated templates for new installations. Upgrading does not replace `_elementor_data` or undo edits. On connected Grant Home/Feedback pages using the Grant full-page native template, rendering adds missing supplied reviews and links the original, unlinked Kathryn image to Amazon. Existing explicit image links and duplicate reviews are preserved. These additions are frontend rendering only.

Pages already using a full-page Grant shortcode inside Elementor receive the new template after cache clearing. Older saved native pages receive shared styling and review compatibility additions, while retaining their own saved structure. Do not reconvert an edited page merely to match a screenshot.

## Enquiry delivery

Existing Web3Forms key/provider settings remain saved. For first setup use **Tools → Grant Website Setup → Enquiry delivery**, save the key privately, then verify a clearly labelled enquiry in the linked inbox. The key is never included in plugin files or public form markup. Outbound verified HTTPS to `api.web3forms.com` is required. Provider acceptance does not prove inbox receipt or dashboard storage.

Name, email, message and consent are required. Optional book/project details are collapsed for a general enquiry, open for assessments or a preselected service, and open automatically for an invalid field. Inputs are retained on failure; busy, success and error states remain accessible. Existing nonce, honeypot, validation, rate limiting and duplicate handling remain. Uncertain provider outcomes do not silently fall back to mail or retry.

The form does not store enquiry bodies in WordPress, send autoresponders or subscribe visitors to a list. Public email/WhatsApp links remain available. No real submission was sent during development.

## First installation and editing

For a new site only, activate the plugin and open **Tools → Grant Website Setup**. Use **Create missing pages and connect navigation**. If ordinary Elementor editing is wanted, use **Apply editable editorial layouts** with Elementor containers enabled. These setup actions are not required to upgrade an existing site. Unrelated content is preserved; converted pages use **Grant Publishing Full Page**.

Edit native text, buttons and images in Elementor; shared contact details and delivery settings are under Grant Website Setup. Navigation is under Appearance → Menus with the Grant primary location assigned. Save changes and clear caches. Preserve the Grant full-page template. Native button links are resolved when layouts are applied; update them manually if a page permalink changes later.

The 17 routes are Home `/`, Services `/services/`, Assessment `/book-marketing-audit/`, About `/about/`, Feedback `/client-feedback/`, Insights `/insights/`, Contact `/contact/`; seven service children and three Insights children. Route/shortcode definitions remain in `pages.json`.

## Verification and rollback

The GitHub release contains all-page screenshots, before/after views and a testing report. Local checks passed 272 rendered combinations, 32 final form renders, navigation/form exercises and 122 mocked PHP assertions on each of 7.4, 8.2 and 8.3. They do not establish live WordPress/theme/Elementor compatibility, full assistive-technology accessibility or inbox delivery. Existing duplicate title tags from the live theme/Canvas integration need staging review. This release has not been installed live.

To roll back, replace the plugin with the retained 3.4.0 ZIP and clear the same caches. Saved Web3Forms settings and page edits remain; no content migration occurs. Restore a content backup only if separate manual edits were made. Restore pre-conversion content in setup replaces a page's current edits and is not needed for ordinary plugin rollback.
