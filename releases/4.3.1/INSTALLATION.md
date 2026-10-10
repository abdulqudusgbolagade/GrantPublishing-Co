# Install Grant Publishing Co. 4.3.1

This is a production repair of the approved design. The ZIP has been tested locally in actual WordPress/Elementor/Yoast and has **not** been installed on the live website.

1. Back up the WordPress database and current plugin files. The source backup is commit `19c3db032a850ed5b5195806b1307dca2287b3ef`, tag `backup/production-repair-2026-10-10`; it is not a production database backup.
2. Test on staging, then upload [grant-publishing-site-4.3.1.zip](grant-publishing-site-4.3.1.zip) through **Plugins → Add New → Upload Plugin**. Choose **Replace current with uploaded**. Activate if necessary. Upload the plugin ZIP rather than a ZIP of the whole GitHub repository.
3. Visit the dashboard as an administrator who can publish pages. A scoped repair runs once: publish the three missing services, correct connected Insights parents, and include the two supplied legal texts at their requested URLs. It preserves saved content, native layouts and private Web3Forms settings. Original route metadata is backed up before moving an article.
4. Open **Tools → Grant Website Setup → Service and Insights route repair** and read the results. If a draft, custom slug, disconnected parent or URL collision is reported, review that specific page and rerun **Repair service pages and Insights routes**. Authored drafts are not published automatically. Only WordPress's exact untouched starter Privacy Policy draft may be replaced with the approved text, with a backup.
5. Confirm **Legal page links** points to the correct published pages. Both footer links appear only for published pages with content. Keep your approved wording unchanged.
6. Clear WordPress, hosting and CDN caches. Regenerate Elementor CSS & Data if stale styles remain. Inspect plugin CSS/JS for `ver=4.3.1`. Keep Contact and Free Book Assessment excluded from full-page caching because their form tokens expire. Do not run **Apply editable editorial layouts** or bulk restoration as part of this repair.
7. Check the live pages, redirects, menu and both forms using the checklist in the testing report. Send one clearly labelled test enquiry through each form and verify actual inbox receipt. The existing server-side Web3Forms key should remain selected; no key is bundled in this release.

## Rollback

The retained [4.3.0 plugin ZIP](../4.3.0/grant-publishing-site-4.3.0.zip) restores the prior code. It does not undo created pages, article parents or publication of approved legal content. For a complete rollback, restore the database and plugin backups together, then clear caches.

If rolling back code alone, first preserve legal pages as normal approved page content because 4.3.0 does not provide the new legal shortcodes. New service pages remain valid with the older plugin's service shortcodes. Do not delete client work or bulk-restore Elementor layouts. The private `gpc_structure_route_backups` option records original parents/permalinks; restoring the incorrect article parent would reinstate the original routing problem and should be a deliberate database rollback.

QA PHP endpoints, blueprints, MU interception code, test users and SQLite databases are not part of the installable ZIP and must never be deployed to production.
