# Grant Publishing Co. 4.3.1

Requires WordPress 6.0+ and PHP 7.4+. Elementor 3.16+ is required for native editing; Grant shortcodes also work without Elementor.

1. Back up the plugin and WordPress database and test on staging.
2. Upload `grant-publishing-site-4.3.1.zip` under Plugins → Add New → Upload Plugin. Replace the existing plugin and activate if needed.
3. Visit the dashboard as an administrator who can publish pages. This update runs a scoped, locked repair once: it creates the three requested missing services, corrects connected Insights parents and publishes the supplied approved legal copy where the URLs are available. Saved page content and Elementor data remain intact.
4. Review **Tools → Grant Website Setup → Service and Insights route repair**. Existing authored legal pages and service drafts are preserved. Review any reported collision or draft, then use **Repair service pages and Insights routes** to retry. Select the correct published legal pages under **Legal page links** if needed. Do not run bulk layout conversion or restoration.
5. Clear WordPress, host and CDN page caches. If needed, regenerate Elementor CSS & Data. Confirm plugin assets use `ver=4.3.1`. Keep Contact and Free Book Assessment excluded from full-page caching so their form tokens remain usable.
6. Check all ten services, the three Insights articles, both case studies, footer legal links and phone navigation while logged out. Send one clearly labelled test enquiry through each form and confirm receipt in the Web3Forms-connected inbox.

## Scope and preservation

The approved Grant design, homepage structure, compact header icon, full footer logo, existing site icon, typography, project artwork, testimonials, case studies, articles and private delivery settings remain. Five original reviews are preserved. New services use the maintained shared service template, with matching Elementor layouts available for optional individual conversion.

The incorrect service aliases for Connected Catalog, Book Discovery and Book Product Page redirect permanently to their published Insights counterparts. The old parent and permalink are backed up before any move. Public requests never create or move pages. Drafts, authored content and conflicting URLs require review rather than overwrite.

Both legal templates contain the exact copy supplied and approved by the owner, dated October 10, 2026. Only an untouched unpublished WordPress starter Privacy Policy may be replaced automatically, with a full backup; authored drafts remain private until reviewed and published. Footer links appear only for published pages with content.

## Forms, books and performance

Web3Forms stays server-side using the existing private settings. Amazon URLs remain optional. Consent and the no-automatic-subscription wording remain. Provider acceptance does not prove inbox receipt. An uncertain submission keeps the message and asks the visitor to check before sending again. No real key is included, and isolated development checks sent no external messages.

The existing real-book carousel, linked cover destinations and project scopes remain. Luma's supplied 2,562,805-byte PDF and My Dear Grandfather's existing hosted PDF are preserved. Native new-tab controls receive `noopener noreferrer`. WordPress smart quotes no longer trigger an extra John Capon review.

Faithful WebP renditions improve the existing portrait and Luma review image. Kathryn's Beach uses its existing responsive renditions. Original files are retained. Font loading uses an enqueued stylesheet and preconnects instead of a CSS import. Same-host HTTP script/style URLs are upgraded only on HTTPS Grant pages.

Yoast uses the established page titles. Missing descriptions and only the identified obsolete stock Home description use approved page metadata; authored descriptions remain. Insights canonicals reflect their corrected permalinks. A scoped Canvas compatibility hook prevents the duplicate browser title without changing saved templates.

## Rollback and verification limits

The backup point is commit `19c3db032a850ed5b5195806b1307dca2287b3ef`, tag `backup/production-repair-2026-10-10`, and the retained 4.3.0 ZIP. A plugin rollback alone does not undo new pages, parents or legal publication. Restore the pre-upgrade database backup for a full rollback. Otherwise preserve the new legal copy as normal page content before installing an older plugin that lacks its shortcodes. Never bulk-restore saved Elementor layouts to roll back a routing repair.

This release has been tested in actual isolated WordPress/Elementor/Yoast and has not been installed on production. The live theme/plugin stack, host/CDN caching, production editor, Safari/Firefox, external account pages and actual Web3Forms inbox receipt still need deployment checks.
