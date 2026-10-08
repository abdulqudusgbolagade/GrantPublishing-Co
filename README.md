# Grant Publishing Co.

WordPress plugin source and installable version 3.2.0, prepared from the supplied project handoff. The update preserves the existing page structure, copy and brand assets while improving readability, linked service/article titles, mobile navigation and enquiry feedback. It also fixes the AJAX form endpoint lookup.

## Download and install

**[Download the installable WordPress plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.2.0/grant-publishing-site-3.2.0.zip)**

Upload that ZIP in **WordPress → Plugins → Add New → Upload Plugin**, then choose **Replace current with uploaded**. Back up and test on staging first. Clear caches after installing. Do not recreate pages or run bulk Elementor conversion for this update. Exclude Contact and Assessment from full-page caching.

If the direct link does not download, open [the ZIP file on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/3.2.0/grant-publishing-site-3.2.0.zip) and use **Download raw file**.

- [Testing report and staging checklist](releases/3.2.0/TESTING-REPORT.md)
- [Installation and rollback instructions](releases/3.2.0/INSTALLATION.md)
- [Screenshots and audit evidence ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/3.2.0/grant-publishing-3.2.0-evidence.zip)
- [SHA-256 checksums](releases/3.2.0/SHA256SUMS.txt)

## Source and validation

The editable plugin source is in `grant-publishing-site/`. Local validation covered 17 pages across fallback and approximate native Elementor layouts at desktop/phone widths (102 combinations), keyboard/menu behavior, intercepted form outcomes, syntax parsing and ZIP integrity. See the report for exact scope and limitations.

**These files have not been installed on the live website.** Local fixtures do not establish real WordPress/Elementor compatibility or inbox delivery; staging verification is still required. Publishing this repository does not deploy the website.
