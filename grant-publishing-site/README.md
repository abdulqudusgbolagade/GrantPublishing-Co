# Grant Publishing Co. 4.6.0

Requires WordPress 6.0+ and PHP 7.4+. Elementor 3.16+ supports native editing; Grant shortcodes also work without Elementor. The approved design and existing pages remain.

## This update

Book Formatting gains the eight owner-supplied interior samples, grouped as title pages, chapter openings, spreads and a mockup. Each card opens its original full-size WordPress image in a protected new tab. The existing Luma proof and project-enquiry path remain.

Home retains its hero layout/copy with larger contained artwork. Its shared showcase combines three established projects with eight formatting views. Formatting slides lead to matching gallery anchors. The homepage pause button is removed; arrows and keyboard browsing stop rotation persistently. Hover holds the current image, reduced motion disables automatic rotation, and the first image remains useful without JavaScript. Services retains its three-project layout and pause/play control.

Images load on selection, with explicit handling for WordPress-added lazy-loading attributes on hidden slides. Gallery images reserve space and load lazily. Faithful full-resolution WebPs preserve exact source pixels; a responsive preview serves the large mockup where appropriate. New content describes samples without invented authors, clients, publication results or illustration-authorship claims.

## Install and check

1. Back up production files/database and retain the previous 4.5.0 ZIP.
2. Upload `grant-publishing-site-4.6.0.zip` through Plugins → Add New → Upload Plugin, replace the existing Grant plugin and activate if needed.
3. Visit the dashboard, clear existing WordPress/host/CDN caches and confirm assets use `ver=4.6.0`. Do not bulk-convert or restore saved Elementor layouts.
4. Check Home’s larger images, controls, formatting links and phone layout. Check all eight samples and full-size actions at `/services/book-formatting/#formatting-samples`.
5. Check the existing enquiry button selects Book formatting. Existing legal copy, Yoast metadata, drafts, testimonials, framework, logos/favicon and private form settings are preserved. Both live forms’ email delivery was confirmed by the owner on 13 October 2026; no further live messages were sent for this release.

This release is prepared and tested in actual isolated WordPress/Elementor/Yoast; remote production installation remains pending. [Complete testing report, source inventory, screenshots and install guide](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/tree/main/releases/4.6.0).

Rollback: replace with 4.5.0 and clear caches. 4.6.0 adds no page-content or private-setting migration. Source backup: `4ee3c87`, tag `backup/formatting-showcase-2026-10-20`. Never deploy QA endpoints, users, test databases, provider interception or dependency trees.
