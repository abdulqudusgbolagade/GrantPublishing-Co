# Grant Publishing Co.

Installable WordPress plugin **4.0.0**: all 17 Grant pages redesigned in **brand blue, cream and ink**, with an editorial hero, larger founder portrait, clearer services, article navigation, client reviews and easier enquiry forms. Existing routes and recognizable section sequence remain; saved Elementor edits and Web3Forms settings are preserved.

**[Download the WordPress plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.0.0/grant-publishing-site-4.0.0.zip)**

If that link fails, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/4.0.0/grant-publishing-site-4.0.0.zip) and choose **Download raw file**. Upload this plugin ZIP, not the whole-repository ZIP.

## Install

Back up and test on staging. In **WordPress → Plugins → Add New → Upload Plugin**, replace the existing Grant plugin. Clear hosting/LiteSpeed, CDN and browser caches; regenerate Elementor CSS & Data if stale. Confirm `site.css` and `design.css` both load with `ver=4.0.0`. **Do not recreate pages or run bulk conversion.** Keep Contact and Assessment out of full-page caching.

The upgrade requests scoped LiteSpeed purges and refreshes generated Elementor caches once per version. Earlier public HTML served a mixture of 3.2/3.3 pages while uncached pages served 3.4; this explains why some updates appeared only on Assessment. Cache refresh does not rewrite saved page content.

Existing Web3Forms settings remain. For initial configuration, save the key privately under **Tools → Grant Website Setup → Enquiry delivery** and verify one labelled enquiry in the linked inbox. No real key is bundled in source or ZIPs.

## Review the work

- [Design review and before/after views](releases/4.0.0/DESIGN-REVIEW.md)
- [All 17 pages on desktop and phone](releases/4.0.0/GALLERY.md)
- [Testing report and remaining checks](releases/4.0.0/TESTING-REPORT.md)
- [Installation and rollback](releases/4.0.0/INSTALLATION.md)
- [Polished Hero–17 publishing brief](docs/PUBLISHING-DESIGN-BRIEF.md)
- [Screenshots and evidence ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.0.0/grant-publishing-4.0.0-evidence.zip)
- [Checksums](releases/4.0.0/SHA256SUMS.txt)

Kathryn's Beach uses the genuine supplied cover and confirmed Amazon link. Nadine's additional review and John's exact five-star review are included. **Luma's original cover file is still needed**: the inline chat image was not available as a downloadable file. Its metadata and requested LinkedIn review link are present; no replacement cover has been invented.

Local validation passed 272 page/layout/viewport combinations, 32 final form renders, focused navigation/form checks and 122 PHP assertions on each of PHP 7.4, 8.2 and 8.3. These use local fixtures and mocked WordPress APIs. This ZIP has **not been installed live**; actual WordPress/Elementor integration and inbox delivery need staging checks.

Editable source is in `grant-publishing-site/`; [development checks](qa/README.md) are in `qa/`. [Version 3.4.0](releases/3.4.0/) remains available for rollback.
