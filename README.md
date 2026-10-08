# Grant Publishing Co.

WordPress plugin source and installable version **3.3.0**. This update makes the founder portraits more prominent within the existing Home/About sections and adds private, server-side Web3Forms enquiry delivery. It retains the 17 pages, editorial design, copy and supplied brand assets.

## Download

**[Download the installable WordPress plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.3.0/grant-publishing-site-3.3.0.zip)**

If the direct link does not download, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/3.3.0/grant-publishing-site-3.3.0.zip) and use **Download raw file**. Upload that plugin ZIP, not GitHub's whole-repository Download ZIP.

## Install and enable Web3Forms

1. Back up and test on staging. Upload the plugin ZIP in WordPress → Plugins → Add New → Upload Plugin and replace the existing plugin. Clear caches; regenerate Elementor CSS & Data if needed. Do not recreate pages or run bulk conversion.
2. Open **Tools → Grant Website Setup → Enquiry delivery**, paste the access key from your Web3Forms account and click **Save enquiry delivery**. Saving a valid key selects Web3Forms. The key is never included in this repository, plugin ZIP or public form.
3. Send one labelled enquiry and confirm receipt in the inbox associated with the key. Contact and Assessment pages should be excluded from full-page caching.

Until a key is saved, existing installations continue using WordPress email. Delivery uses one selected provider; uncertain Web3Forms delivery does not silently fall back or resend.

- [Testing report and staging checklist](releases/3.3.0/TESTING-REPORT.md)
- [Installation and rollback instructions](releases/3.3.0/INSTALLATION.md)
- [Before/after portrait comparison](releases/3.3.0/portrait-comparison.md)
- [Screenshots and audit evidence ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.3.0/grant-publishing-3.3.0-evidence.zip)
- [SHA-256 checksums](releases/3.3.0/SHA256SUMS.txt)
- [Previous 3.2.0 package](releases/3.2.0/)

## Source and validation

Editable source is in `grant-publishing-site/`; reproducible mocked backend/package checks are in [qa/](qa/README.md). Version 3.3 passes 58 backend assertions on PHP 7.4 and 8.2, twelve PHP lint checks, 102 browser fixture page/layout/viewport checks, and additional portrait, form and keyboard checks. See the report for scope and limitations.

These files are published code, not a live installation. Actual WordPress/Elementor activation, Web3Forms access/inbox delivery and provider domain restrictions still need staging verification. The reference `owpublishiing.com` was blocked by the development environment's network proxy, so no reference-site visual comparison was performed.
