# Grant Publishing Co.

WordPress plugin source and installable **version 3.4.0**, refined using OW Publishing House's navy, cream and gold design as a reference. The upgrade keeps Grant's 17 pages, section order, copy, supplied logo/book cover/portrait and Instrument Serif/Manrope fonts. Existing Web3Forms settings are preserved.

**[Download the installable WordPress plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.4.0/grant-publishing-site-3.4.0.zip)**

If the direct link does not download, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/3.4.0/grant-publishing-site-3.4.0.zip) and use **Download raw file**. Upload this plugin ZIP, not GitHub's whole-repository ZIP.

## Install

Back up and test on staging. Upload the plugin ZIP in WordPress → Plugins → Add New → Upload Plugin, replacing the current plugin. Clear WordPress/LiteSpeed/CDN/browser caches and regenerate Elementor CSS & Data if needed. Do not recreate pages or run bulk conversion. Contact and Assessment should stay out of full-page caching.

If Web3Forms is already configured, its key/provider remain saved. For first setup, open **Tools → Grant Website Setup → Enquiry delivery**, paste your Web3Forms key and save. Confirm one labelled test in the linked inbox. No real access key is included in this repository or any ZIP.

- [Reference review and before/after preview](releases/3.4.0/DESIGN-REVIEW.md)
- [All-page screenshot gallery](releases/3.4.0/GALLERY.md)
- [Testing report](releases/3.4.0/TESTING-REPORT.md)
- [Installation and rollback instructions](releases/3.4.0/INSTALLATION.md)
- [Screenshots/audit evidence ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.4.0/grant-publishing-3.4.0-evidence.zip)
- [Checksums](releases/3.4.0/SHA256SUMS.txt)
- [Previous 3.3.0 package](releases/3.3.0/)

Editable source is in `grant-publishing-site/`; development checks are in [qa/](qa/README.md). The design layer is scoped to Grant components and loads after base styles, including late shortcode rendering.

Local validation passed 204 rendered viewport/layout checks, additional form/navigation checks, and 62 real PHP assertions with WordPress APIs mocked on PHP 7.4, 8.2 and 8.3. All 17 public live routes responded successfully; their cached HTML still references older styles and has duplicate title tags to review on staging. Published files are not a live installation. WordPress/Elementor integration and inbox delivery remain staging checks.
