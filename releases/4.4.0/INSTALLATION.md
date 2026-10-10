# Install Grant Publishing Co. 4.4.0

The code and installable plugin are complete. **This update has not been installed on production.** Public live pages were inspected read-only; integration tests used an isolated WordPress installation.

1. Back up the live WordPress database and plugin files. Source backup: `077d7065dd316e2f5ee4ffe486bdac70046e4ac5`, tag `backup/linking-case-studies-2026-10-10`. This does not replace a database backup.
2. Test on staging, then upload [grant-publishing-site-4.4.0.zip](grant-publishing-site-4.4.0.zip) through **Plugins → Add New → Upload Plugin**. Choose **Replace current with uploaded**. Upload this plugin ZIP, not the entire GitHub repository.
3. Visit the dashboard as an administrator with permission to publish pages. The scoped setup creates only `/case-studies/`, its two project pages and `/faq/`. Existing page content, Elementor layouts, Yoast fields and Web3Forms settings are preserved. Public requests do not create pages.
4. Open **Tools → Grant Website Setup → Case Studies and FAQs**. Read the results. If a draft, custom URL or collision is reported, review that specific page before using **Create missing Case Studies and FAQ pages** to retry. The update does not silently publish authored drafts or replace unrelated content.
5. Clear WordPress, hosting and CDN caches. Regenerate Elementor CSS & Data if needed. Keep Contact and Free Book Assessment excluded from full-page caching. **Do not run a bulk layout conversion or restoration as part of this update.** The new pages work through their Grant shortcodes immediately; native layouts are supplied for later deliberate editing.
6. Verify the four new pages, all service cards, the three Insights journeys, existing PDF buttons, footer links, FAQs and mobile menu. Confirm your manual Yoast metadata, sitemap, legal pages and five testimonials remain correct.
7. Send one clearly labelled live test enquiry through Contact and Free Book Assessment, and confirm inbox receipt. The existing private Web3Forms key/provider should remain selected. Provider acceptance alone is not inbox confirmation.

## Rollback

The retained [4.3.1 plugin ZIP](../4.3.1/grant-publishing-site-4.3.1.zip) restores the previous code, but it does not remove the four new pages or reverse database changes. For a complete rollback, restore the database and plugin backups together, then clear caches.

If rolling back code alone, new-page shortcodes will require 4.4.0. Preserve their content before switching versions; do not leave those pages published with unsupported shortcodes. Do not delete existing client material or bulk-restore saved Elementor content.

QA helpers, blueprints, MU interception code, test users and databases are excluded from the installable ZIP and must never be deployed to production.
