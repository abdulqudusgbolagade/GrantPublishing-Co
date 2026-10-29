# Grant Publishing Co.

WordPress plugin **4.7.0 review build** upgrades the existing publishing website's presentation, real-project portfolio, service journeys, optional enquiry fields and performance. Existing URLs, brand assets, genuine reviews, PDFs, legal copy, manual Yoast metadata and server-side Web3Forms settings are preserved.

**[Download the installable review ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.7.0/grant-publishing-site-4.7.0.zip)**

If downloading fails, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/4.7.0/grant-publishing-site-4.7.0.zip) and choose **Download raw file**. Upload the plugin ZIP, not the repository ZIP.

**Not deployed to production.** The latest brief requires review and approval before installation on the live WordPress site.

- [Changes, testing results, limitations and owner checklist](releases/4.7.0/TESTING-REPORT.md)
- [Before-and-after desktop/mobile screenshots](releases/4.7.0/GALLERY.md)
- [Installation and rollback after approval](releases/4.7.0/INSTALLATION.md)
- [Existing analytics, consent and conversion setup](releases/4.7.0/ANALYTICS.md)
- [Exact source files changed](releases/4.7.0/FILES-CHANGED.txt)
- [ZIP checksum](releases/4.7.0/SHA256SUMS.txt)
- [Development checks](qa/README.md)
- [Previous formatting/showcase release](releases/4.6.0/TESTING-REPORT.md)

The final local audit covers 26 pages at six widths, all 11 homepage slides, 50 form/navigation/consent checks, 59 carousel/gallery interactions, and 1,359 PHP assertions across 7.4/8.2/8.3. Mobile Lighthouse performance changed from 65 to 79 and desktop from 96 to 97 in the isolated test installation; final measured layout shift was zero. These are lab measurements, not production field scores.

The homepage still has no pause button. Its arrows stop rotation for manual browsing, keyboard focus stops it, hover pauses it and reduced-motion preferences disable automatic rotation. All eight owner-supplied formatting views remain available with full-size original links. No invented projects or results have been added.

Rollback source: `a588056`, tag `backup/premium-upgrade-2026-10-28`. Both earlier real Web3Forms test emails were confirmed received by the owner on 13 October 2026. No new external enquiries or analytics were transmitted during this release's testing. The installable ZIP includes no real key, production credential, QA endpoint or database.
