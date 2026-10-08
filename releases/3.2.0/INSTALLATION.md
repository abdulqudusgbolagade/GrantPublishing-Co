# Grant Publishing Co. 3.2.0 — UI/UX update

Upload `grant-publishing-site-3.2.0.zip` as a WordPress plugin; the archive has one `grant-publishing-site/` folder. WordPress 6.0+, PHP 7.4+, Elementor 3.16+ for native editing; shortcode pages also work without Elementor.

## Install safely

1. Back up the current plugin folder and WordPress database, including Elementor page content. Test on staging first.
2. In Plugins > Add New > Upload Plugin, select this installable ZIP. Replace the existing Grant Publishing plugin when WordPress prompts, then activate it if needed.
3. **Do not run page creation or bulk Elementor conversion to apply this update.** Existing page records, routes, content and edits are preserved. Shared CSS/JavaScript applies the refinement to recognized existing pages; bundled title links are also available for future native conversions.
4. Clear WordPress/page/CDN/browser caches. Use Elementor > Tools > Regenerate CSS & Data (wording may vary by version) if stale styles remain. Exclude Contact and Free Initial Book Assessment from full-page caching to avoid expired nonces.
5. Follow the staging checklist in the accompanying TESTING-REPORT.md. Verify Google Fonts access and actual WordPress/theme/Elementor styles. Confirm delivery only with an authorized test message.

## What changed

- Explicit text colors on light/dark components and Elementor button spans; readable placeholder, pending, success and error colors.
- Native linked titles and clearer keyboard states for service/article cards; existing saved native content gets a runtime title link using its current action destination. No whole-card click interception or nested controls.
- Mobile menu Escape, outside-click and focus-leave closing, with focus return on Escape and sensible handling when returning to desktop; scrollable menu on short screens.
- A shared skip link covers both shortcode and native full-page paths.
- Enquiry/assessment action labels, pending/busy feedback, success/error styling, and preserved entries on unsuccessful submission.
- Fixed an AJAX submission defect: the hidden WordPress `action` input shadows the DOM `form.action` property. Submission now reads the form attribute directly.

Page copy, section order, routes, heading levels, supplied logo/portrait/book cover, fonts and palette are preserved. Page updates do not overwrite saved Elementor content. Custom content lacking the existing component classes needs separate review.

## Roll back

Replace the plugin with the backed-up version (the supplied 3.1.0 baseline is also available in the original handoff), then clear the same caches. This release performs no database migration, page creation or automatic conversion. Restore database/page backups only if separate manual edits were made. Do not use the conversion Restore action merely to undo CSS/JavaScript changes.

## Verification limits

Local Chromium fixtures and package checks passed; see the separate report for exact coverage. Fixtures approximate native Elementor DOM and form/shared PHP output. PHP parsing is not PHP execution. Actual WordPress/Elementor integration, external fonts, live routes and mailbox delivery require staging verification. No live installation was performed.

---

The following is the historical 3.1 installation/editing guide. Its page creation/conversion instructions are for initial setup, **not required for this upgrade**.

# Grant Publishing Co. website refinements

Version 3.1.0. This update keeps the approved editorial design and page structure. It changes only the homepage service-card styling, adds the supplied portrait to the homepage introduction and About biography, and uses the supplied logo in the shared header and footer.

## Updating the website you already installed

1. Download **grant-publishing-site.zip** and keep it zipped.
2. In WordPress, open **Plugins > Add New Plugin > Upload Plugin**.
3. Upload the ZIP and choose **Replace current with uploaded** when prompted. Confirm version **3.1.0**.
4. Clear the WordPress and hosting page caches, then reload the site.

**You do not need to recreate pages or reapply all layouts for this update.** Existing Grant shortcodes stay the same. The live homepage inspected on October 8, 2026 uses the shortcode layout, so its cards, logo and portrait come from this plugin update.

The homepage keeps its three services in the same section and order. Each now has a light card surface, a subtle border and restrained hover/focus treatment. All three remain visible; there is no carousel or automatic movement. Your photo appears in the existing introduction and biography sections. The dark footer gives the supplied logo a small light background so it remains readable. Both supplied image files are bundled unchanged.

The shared logo and service-card styling also apply to existing native Elementor layouts. To protect saved page edits, updating the plugin does not replace their Elementor content. If you have already converted Home or About to native Elementor, add your portrait using an Image widget in the existing introduction/biography. The supplied native layouts include it for pages converted for the first time. Do not restore or reconvert edited pages just to add a photo.

This update has been prepared, not installed by the assistant. Verification confirmed that all 17 pages retain their existing text, headings, content links and section order. Only the Home and About content templates gained image markup. The form and setup logic are unchanged. After installation, check the new logo, portraits and cards at desktop and phone widths.

The complete installation and editing instructions below are retained for reference or a first-time installation.

## 1. Install the updated plugin

1. Keep a current WordPress backup and the previous plugin ZIP. A staging site is useful if your host provides one.
2. Download **grant-publishing-site.zip**. Keep it zipped.
3. Sign in at https://grantpublishingco.com/wp-admin/.
4. Go to **Plugins > Add New Plugin > Upload Plugin**. Upload the ZIP and select **Install Now**.
5. Choose **Replace current with uploaded** if the Grant plugin is already installed. Otherwise, activate **Grant Publishing Co. Website**.
6. Confirm that its version is **3.1.0**.

Pages already using Grant shortcodes take on the new design when this plugin is updated. The next step makes their content editable as individual Elementor widgets.

## 2. Complete the pages and apply the editable layouts

Open **Tools > Grant Website Setup** and use these two buttons in order:

1. **Create missing pages and connect navigation.** This publishes missing pages, links the navigation to the page records and preserves existing content.
2. **Apply editable editorial layouts.** This converts connected Grant pages into native Elementor headings, text, images and buttons. It saves each page’s pre-conversion content and selected Elementor settings first.

The editable layouts require Elementor 3.16 or newer with containers enabled. Elementor Pro is not required. If Elementor is unavailable, the plugin’s shortcode layouts still display the editorial design.

Review the results and the page table. Successfully converted pages show **Editable in Elementor**. Running the button again skips converted pages, preserving subsequent edits.

If a row says **Existing content preserved**, save that page’s current layout as an Elementor template. Replace its body with one **Shortcode** widget using the shortcode shown in the table, update the page, then run step 2 again. Unrelated existing page content is not automatically overwritten.

If Home is missing, select your existing homepage under **Settings > Reading > A static page**, then run the buttons again. Existing drafts remain drafts; review and publish them separately. Converted pages use **Grant Publishing Full Page**. Keep that page template selected so the shared header and footer appear correctly.

## 3. Make everyday changes

| What you want to change | Where to change it |
|---|---|
| Page text or headings | Pages > choose the page > Edit with Elementor. Select the text and update it. |
| A button’s label or destination | Edit with Elementor > select the Button widget > Content. |
| Book cover or another page image | Edit with Elementor > select the Image widget > choose the replacement image. |
| Header navigation labels or order | Appearance > Menus > Grant Publishing Navigation. Keep the Grant Publishing primary navigation location assigned. Only top-level items appear in this design. |
| Email, WhatsApp or social profiles | Tools > Grant Website Setup > Shared contact details. |
| The inbox receiving enquiries | The Email and enquiry recipient field in Shared contact details. |
| Site-wide colors, typography or spacing | The shared stylesheet in the plugin. These can be updated together in a future design revision. |

Click **Update** in Elementor and clear the site cache after editing. The form and contact links remain managed widgets; page headings, copy, images and buttons are ordinary editable Elementor widgets. Keep the plugin active because it supplies the shared design, navigation, contact links and form handling.

The supplied cover is bundled with the plugin and used as an image URL. When replacing it, choose an image from WordPress Media Library and provide appropriate alternative text.

Native Elementor button URLs are resolved when the layouts are applied. If you later change a page’s URL, update buttons and text links pointing to that page in Elementor. The shared menu and footer use the mapped page’s current permalink automatically.

## 4. Check the live result

Clear the WordPress and hosting page caches. Exclude **/contact/** and **/book-marketing-audit/** from full-page caching because their form security tokens expire. Keep Elementor caching disabled for the enquiry-form and contact-links widgets if you change their Advanced settings.

Open the website while logged out and check:

- Home, Services, About, Client Feedback, Insights and Contact from the header and footer.
- Each service detail page and all three complete Insights articles.
- A service’s enquiry button, confirming that Contact opens with that service selected.
- Free book assessment, confirming that the assessment request is selected.
- The mobile menu, readable text, button spacing, cover image and form at phone and desktop widths.
- Email, WhatsApp, LinkedIn and Upwork links, confirming the intended destinations.

If a new page gives a 404, open **Settings > Permalinks**, keep your current setting and click **Save Changes**, then clear the cache again. If an Elementor page displays stale styling, use Elementor’s tools to regenerate its files/data, then clear the cache.

## 5. Confirm enquiry delivery

1. Send a clearly labelled test enquiry with your own email address from the logged-out Contact page.
2. Confirm arrival at **hello@grantpublishingco.com**, or your updated recipient. Check spam too.
3. Click Reply and confirm that it addresses the email entered in the form.
4. Send an assessment request with a different message and confirm the request type is included.

A success message means WordPress accepted the email for sending. It does not prove inbox delivery. If the message does not arrive, configure authenticated outgoing email through your host or mailbox provider and repeat the test.

Name, email, message and permission to respond are required. The other fields are optional. The form emails enquiries and does not store message bodies in the WordPress database, send autoresponders or subscribe visitors to a mailing list. Short-lived hashed identifiers support duplicate and abuse checks.

## Pages included

| Page | Default route |
|---|---|
| Home | `/` |
| Services | `/services/` |
| Free initial assessment | `/book-marketing-audit/` |
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
| Book discovery article | `/insights/book-discovery/` |
| Product page article | `/insights/book-product-page/` |
| Catalog article | `/insights/connected-catalog/` |

The old `/audit-page/`, `/work/`, `/portfolio/`, `/publishing/` and `/our-process/` addresses redirect to relevant pages only if the old address would otherwise be a 404 and the destination is published. Existing content at those addresses is preserved.

The testimonials are the supplied reviews from Nadine Laman’s three projects. They are presented as one continuing client relationship. Initial assessments use public Amazon information. Deeper paid work may require a synopsis or manuscript. No sales or ranking increases are invented or guaranteed.

## Recovery and source files

In **Tools > Grant Website Setup**, a converted page has **Restore options > Restore pre-conversion content**. This replaces the page’s current content and selected Elementor settings with its first saved pre-conversion version. Save any newer edits you want to retain before using it. The backup is retained after restoring.

This is a content backup, not a full site or visual-version backup. Restoring a shortcode page while version 3 remains active still uses version 3 styling. For a complete visual rollback, restore the page content and the previous plugin version from your WordPress backup. Newly created page records remain until you remove them or set them to draft.

The package contains all 17 native layouts in `elementor/`, corresponding shortcode templates in `templates/`, page definitions in `pages.json`, shared styling in `assets/site.css` and form interactions in `assets/enquiry.js`. PHP files provide setup, conversion, recovery, the shared shell and mail handling. The layout JSON contains route/asset placeholders intended for this installer; do not import it manually without resolving them.

Local verification covered 17 matching page/layout pairs, 727 native elements, HTML structure, headings, IDs, route and asset references, form anchors, service selections, JavaScript syntax and simulated form success/failure behavior. Responsive breakpoints and focus/reduced-motion rules are included. These checks do not replace actual WordPress, PHP, visual or mailbox testing.

Official references: [WordPress plugin installation](https://wordpress.org/documentation/article/manage-plugins/) · [Elementor layout structure](https://developers.elementor.com/docs/data-structure/) · [WordPress email behavior](https://developer.wordpress.org/reference/functions/wp_mail/)
