# Install 4.3.0

1. Back up the plugin and WordPress database; install on staging first.
2. Download `grant-publishing-site-4.3.0.zip` from this directory. In WordPress, Plugins → Add New → Upload Plugin, select it and replace the current plugin. Do not upload the repository or evidence ZIP.
3. Go to Tools → Grant Website Setup → **Create missing pages and connect navigation**. This preserves existing content and publishes missing service pages. Confirm Book Formatting, Book Cover Design and Amazon Ads pages were created. Review the setup result if a requested slug already exists.
4. Clear hosting/CDN/browser caches and regenerate Elementor CSS & Data if stale. Confirm `ver=4.3.0` on the current assets. Keep Contact and Assessment outside full-page caching. Do not bulk-convert or restore saved Elementor layouts.
5. Check Home and Services book showcases on desktop and phone, all ten service entries and their links, general enquiry choices without an Amazon link, John’s review plus the new Luma case, and the preserved Grandfather case.
6. Try keyboard and touch navigation, hover/focus pause and reduced motion. Follow both PDF buttons. Check custom page copy and SEO plugin metadata for consistent positioning.
7. Send one clearly labelled test enquiry and confirm receipt in your actual Web3Forms inbox. The existing private key is preserved; do not put it in website files or GitHub. Development tests did not submit a real message.

Saved native page data remains unchanged. Exact stock copy updates at render time, and missing service/case features use duplicate guards. Custom copy is preserved and may need a manual review. New pages initially use maintained shortcodes; matching Elementor layouts are bundled for optional individual editing conversion.

The Luma PDF is inside the plugin at `assets/luma-sleepy-star-case-study.pdf`. Its button works without a new WordPress media upload. Grandfather keeps the original supplied hosted PDF. Both open a protected new tab.

## Roll back

Replace with the retained 4.2.1 ZIP and clear the same caches. Delivery settings and saved edits remain. Newly created service pages are not automatically deleted; review their navigation visibility separately. Restore a database backup only for separate manual changes that need reversal.

This release is published on GitHub and has not been installed on the live website.
