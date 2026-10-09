# Grant Publishing Co.

Installable WordPress plugin **4.1.0**, applying the supplied **Brand Guidelines v1.0** across all 17 pages: midnight/indigo, cobalt/violet accents, porcelain surfaces, Cormorant Garamond headings and Manrope body/UI. The new header icon, full footer logo and approved “Books built to be discovered.” headline complete the identity update.

**[Download the WordPress plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.1.0/grant-publishing-site-4.1.0.zip)**

If needed, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/4.1.0/grant-publishing-site-4.1.0.zip) and choose **Download raw file**. Upload this plugin ZIP, not the whole-repository ZIP.

## Included

- Genuine clickable Luma, My Dear Grandfather and Kathryn’s Beach covers, with the user-confirmed LinkedIn/Amazon destinations.
- Correct credits: Barsha Rai is Grandfather’s author; Nadine Laman is its publisher and owner of Cactus Rain Publishing. Exact existing reviews are retained.
- Supplied icon in the header, supplied full reverse logo in the footer, optimized icon renditions, accessible naming and protected proportions/clear space.
- All 17 routes, existing page structure, prominent portrait, service enquiries, article navigation and Web3Forms settings preserved. The WordPress site icon remains unchanged.

## Install and review

Back up and test on staging. Replace the existing Grant plugin via **Plugins → Add New → Upload Plugin**. Clear hosting/LiteSpeed, CDN and browser caches; regenerate Elementor CSS & Data if stale. Confirm `site.css` and `design.css` both load with `ver=4.1.0`. **Do not recreate pages or run bulk conversion.** Keep Contact and Assessment out of full-page caching.

The existing once-per-version scoped cache refresh remains. Saved Elementor edits and private delivery settings are not overwritten. If Web3Forms is already configured, no key re-entry is needed; verify a labelled enquiry in the associated inbox yourself. No real key is included in source or ZIPs.

- [All-page screenshot gallery](releases/4.1.0/GALLERY.md)
- [Brand implementation and source assets](docs/BRAND-IMPLEMENTATION.md)
- [Testing report](releases/4.1.0/TESTING-REPORT.md)
- [Installation and rollback](releases/4.1.0/INSTALLATION.md)
- [Before/after design review](releases/4.1.0/DESIGN-REVIEW.md)
- [Adapted publishing design brief](docs/PUBLISHING-DESIGN-BRIEF.md)
- [Evidence ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.1.0/grant-publishing-4.1.0-evidence.zip) and [checksums](releases/4.1.0/SHA256SUMS.txt)

Validation passed 272 page/layout/viewport combinations, 188 brand/cover assertions, focused navigation/forms and 138 PHP assertions on each of three runtimes. Browser checks use local fixtures; PHP uses real interpreters with mocked WordPress APIs. **This release has not been installed live**; actual WordPress/Elementor/theme rendering and inbox delivery remain staging checks.

Source is in `grant-publishing-site/`; [development checks](qa/README.md) are in `qa/`. [4.0.0](releases/4.0.0/) is retained for rollback.
