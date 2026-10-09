# Grant Publishing Co. 4.3.0

Requires WordPress 6.0+ and PHP 7.4+. Elementor 3.16+ is required for native editing; Grant shortcodes also work without Elementor.

1. Back up the plugin and database and test on staging.
2. Upload `grant-publishing-site-4.3.0.zip` under Plugins → Add New → Upload Plugin. Replace the existing plugin and activate if needed.
3. Under Tools → Grant Website Setup, run **Create missing pages and connect navigation** once. It preserves existing pages and publishes missing pages, including `/services/book-formatting/`, `/services/cover-design/` and `/services/amazon-ads/`. Review the setup result if a slug already belongs to another page. The publishing page keeps `/services/publishing-support/`.
4. Clear hosting/CDN/browser caches and regenerate Elementor CSS & Data if stale. Confirm `site.css`, `design.css` and `showcase.js` use `ver=4.3.0`. Keep Contact and Assessment outside full-page caching.
5. Review broader positioning, all ten services, the two book showcases and both case studies while logged out on desktop and phone.
6. Check reduced motion, keyboard and touch navigation, optional Amazon links, service preselection and a clearly labelled test enquiry in your actual inbox.

Do not bulk-convert or restore existing Elementor pages. Exact bundled stock copy updates only on connected native pages at render time. Authored copy remains as saved and should be reviewed manually if it still uses narrower positioning. The stock studio image changes only on Home and Services. Missing service entries and the Luma case feature use duplicate guards. No saved page content, Elementor data, site icon or delivery configuration is overwritten.

The three new pages initially use maintained Grant shortcodes. Matching new-install Elementor layouts are bundled for optional individual conversion. Preserve existing pages when editing or converting.

## Book projects and PDFs

The Luma showcase uses the final book mockup extracted from the supplied case-study PDF. Its feature shows the full paperback cover wrap and explains cover redesign and interior formatting through file delivery. Supplied illustrations, KDP upload, publication, metadata and Kindle conversion are not claimed as work by Grant on this project.

Luma’s unchanged seven-page PDF is bundled at `assets/luma-sleepy-star-case-study.pdf`, so its link does not depend on an unprovided WordPress upload. The displayed 2.44 MB is calculated from 2,562,805 bytes. My Dear Grandfather continues to use the supplied hosted WordPress PDF. Both buttons open a protected new tab. Luma’s book links still open John’s LinkedIn review; no Amazon product destination is invented.

## Forms and editing

Design, formatting, publishing/KDP, Amazon Ads setup and ongoing management choices are available alongside all previous service values. An Amazon link is optional for project enquiries, including unpublished books. Free initial Amazon assessments retain their public-information disclaimer.

Existing private Web3Forms settings remain saved. If needed, configure them privately under Tools → Grant Website Setup → Enquiry delivery. Provider acceptance does not establish inbox receipt. No real key is included and development tests sent no messages. Shared contacts and navigation remain editable in their existing settings.

## Rollback and limits

Replace with the retained 4.2.1 plugin and clear caches. Newly created service pages are not deleted by rollback; review their menu visibility separately. Saved edits and delivery settings remain. Restore a database backup only for separate manual changes that need reversal.

This ZIP is published on GitHub and has not been installed live. Actual WordPress/Elementor/theme/editor integration, other browsers, SEO plugin output and inbox delivery require staging review.
