# Install the portfolio update

1. Back up the existing production plugin/files and database. Retain the [4.5.0 ZIP](../4.5.0/grant-publishing-site-4.5.0.zip).
2. Download **grant-publishing-site-4.6.0.zip**. In WordPress use **Plugins → Add New → Upload Plugin**, then **Replace current with uploaded**. Upload the plugin ZIP, not the repository ZIP.
3. Visit the dashboard and clear existing WordPress/host/CDN caches. Assets should use `ver=4.6.0`. **Do not bulk-convert or restore Elementor layouts.** This update renders through existing components and does not rewrite saved pages or Yoast fields.
4. On Home, confirm the artwork is larger, no pause button appears, formatting samples enter the rotation and clicking one opens its matching Formatting sample. Arrows stop autoplay for manual browsing; hover/focus/reduced motion also control movement.
5. Open `/services/book-formatting/#formatting-samples`. Confirm all eight samples and the original Luma proof appear. Gallery images should open the supplied full-size originals in new tabs, including with keyboard Enter. Check a narrow phone width and the mobile menu.
6. Check the existing Book Formatting enquiry button still opens Contact with **Book formatting** selected. No additional test email is needed merely to install this update; both forms’ owner-confirmed delivery is already recorded. The Services showcase intentionally retains its previous controls.

The 4.6.0 ZIP has been tested locally, not installed remotely. No authenticated production deployment connection was available. Nothing from `qa/` or release `evidence/` belongs on WordPress; the ZIP contains only the plugin directory.

Rollback: replace the plugin with 4.5.0 and clear the same caches. This update adds no page-content, SEO or private-form-setting migration, so no bulk restoration is needed. Preserve unrelated edits and new enquiries. Source backup tag: `backup/formatting-showcase-2026-10-20` at `4ee3c87`.
