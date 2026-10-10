# Grant Publishing Co.

Installable WordPress plugin **4.3.1**, a focused production repair of the approved Grant publishing website. The existing design, purple/blue identity, homepage structure, logos, typography, reviews, case studies and founder identity are preserved.

**[Download the installable plugin ZIP](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/raw/refs/heads/main/releases/4.3.1/grant-publishing-site-4.3.1.zip)**

If downloading fails, [open the ZIP on GitHub](https://github.com/abdulqudusgbolagade/GrantPublishing-Co/blob/main/releases/4.3.1/grant-publishing-site-4.3.1.zip) and choose **Download raw file**. Upload this plugin ZIP, rather than the whole repository.

## Production repairs

- Publish the three missing formatting, cover design and Amazon Ads detail pages through the existing service architecture.
- Correct connected Insights article parents and add explicit permanent redirects from their incorrect service aliases.
- Include the supplied approved Privacy Policy and Terms of Service exactly, with conditional footer links and readable business email.
- Prevent duplicate reviews after WordPress typography processing and protect native Elementor buttons that open new tabs.
- Correct the Elementor Canvas/Yoast duplicate-title conflict, fill missing SEO descriptions and replace only the identified obsolete stock Home description. Authored descriptions remain.
- Upgrade same-host legacy stylesheet URLs to HTTPS on connected HTTPS Grant pages.
- Improve existing image delivery and font discovery without replacing artwork or changing typography.

## Install and verify

Back up files and the database, test on staging, replace the plugin, then visit the WordPress dashboard as an administrator. The scoped repair runs once. Review **Tools → Grant Website Setup → Service and Insights route repair**. Resolve any reported draft or URL collision before retrying the repair. Do not bulk-convert or restore saved Elementor pages.

The current installation has been audited read-only. This update **has not been installed on production**. Actual WordPress 7.0.7, Elementor 4.3.4 and Yoast 28.6 were tested locally through official Playground, including 22 pages, seven viewport widths, all ten service cards, forms and both legal texts. Provider calls were intercepted locally; inbox receipt still needs a live test.

- [Testing report, affected files and verification limits](releases/4.3.1/TESTING-REPORT.md)
- [Installation and rollback](releases/4.3.1/INSTALLATION.md)
- [Desktop and mobile screenshots](releases/4.3.1/GALLERY.md)
- [Checksums](releases/4.3.1/SHA256SUMS.txt)
- [Development checks](qa/README.md)
- [Actual WordPress reproduction instructions](releases/4.3.1/evidence/wordpress-runners/README.md)

The pre-repair commit is `19c3db032a850ed5b5195806b1307dca2287b3ef`, tagged `backup/production-repair-2026-10-10`. [4.3.0](releases/4.3.0/) remains available. No real Web3Forms key, production credentials or database is included in source or downloads.
