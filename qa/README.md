# Development checks

These checks are for plugin development. WordPress HTTP, mail and database APIs are mocked; no real enquiry or key is used. Native PHP 7.4+ can run the seven PHP suites directly. Otherwise use the pinned official WordPress Playground runtime:

```bash
npm ci --prefix qa --ignore-scripts --cache /workspace/grant-qa/npm-cache
PHP=7.4 node qa/php.mjs qa/test-web3forms.php
PHP=7.4 node qa/php.mjs qa/test-publishing.php
PHP=7.4 node qa/php.mjs qa/test-upgrades.php
PHP=7.4 node qa/php.mjs qa/test-structure.php
PHP=7.4 node qa/php.mjs qa/test-legal.php
PHP=7.4 node qa/php.mjs qa/test-journey.php
PHP=7.4 node qa/php.mjs qa/test-framework.php
PHP=8.2 node qa/php.mjs qa/test-web3forms.php
PHP=8.2 node qa/php.mjs qa/test-publishing.php
PHP=8.2 node qa/php.mjs qa/test-upgrades.php
PHP=8.2 node qa/php.mjs qa/test-structure.php
PHP=8.2 node qa/php.mjs qa/test-legal.php
PHP=8.2 node qa/php.mjs qa/test-journey.php
PHP=8.2 node qa/php.mjs qa/test-framework.php
PHP=8.3 node qa/php.mjs qa/test-web3forms.php
PHP=8.3 node qa/php.mjs qa/test-publishing.php
PHP=8.3 node qa/php.mjs qa/test-upgrades.php
PHP=8.3 node qa/php.mjs qa/test-structure.php
PHP=8.3 node qa/php.mjs qa/test-legal.php
PHP=8.3 node qa/php.mjs qa/test-journey.php
PHP=8.3 node qa/php.mjs qa/test-framework.php
node qa/php-lint.cjs
node qa/test-enquiry.cjs
python3 qa/check-package.py
```

Use a writable npm cache; the cloud home cache is unavailable. Keep TLS and lockfile integrity verification enabled. The current runtime passed with Node 24.19 and npm 11.9 despite a package npm-version warning. Python checks require `lxml`; dependency files stay ignored.

`test-web3forms.php` covers 72 delivery/settings/prepublication assertions; `test-publishing.php` covers 95 positioning/showcase/review/contact/header/footer assertions; `test-upgrades.php` covers 14 migration/cache assertions; `test-structure.php` covers 73 route, permission, concurrency, backup, redirect, metadata, legal-link, image and compatibility assertions; `test-legal.php` covers 10 starter-policy preservation, backup, failure and WordPress template-carry-forward assertions. `test-journey.php` covers 85 scoped page-creation, preservation, contextual-link, FAQ, conversion and Yoast-priority assertions. `test-framework.php` covers 61 framework, title ownership, scoped starter cleanup, preservation, protected-draft creation and owner-publication-gate assertions. The seven suites total 410 assertions per runtime. Release 4.5.0 passed 1,230 across PHP 7.4, 8.2 and 8.3; the 4.6.0 execution results are listed below. `php.mjs` preserves interpreter exit codes.

`check-package.py` verifies all 26 template/layout pairs, native content parity, routes/assets, forms, anchors, markup/IDs and CSS markers. After intentionally editing templates, `python3 qa/sync-layouts.py` regenerates bundled new-install native JSON only; it never contacts WordPress or changes installed pages. Review generated diffs before committing.

## Actual WordPress browser checks

Release 4.4.0 also runs actual WordPress 7.0.7, Elementor 4.3.4 and Yoast 28.6 through official Playground CLI 3.1.57, PHP 8.5.10 and SQLite. This is an isolated test installation, never the production database. WordPress provider HTTP and mail are intercepted by a local-only MU plugin; the seed access key is fake. Do not copy the QA MU plugin, scripts, test users or database to production.

[Reproduction instructions and retained runners](../releases/4.3.1/evidence/wordpress-runners/README.md) describe the prepared workspace, a clean bootstrap and safe restart. [The testing report](../releases/4.3.1/TESTING-REPORT.md) distinguishes the read-only live baseline from the local repaired installation. No inbox receipt or production installation is implied.

The earlier 4.3.0 fixture checks remain documented in their own release. Those approximate Elementor DOM; the 4.3.1 audit uses actual Elementor output. Keep Playwright/axe dependencies and test WordPress outside the installable plugin. Preserve TLS and artifact checks; the cloud browser's missing proxy CA is handled through curl's configured trust store, without disabling certificate verification.

Release 4.4.0 adds [the internal-linking, Case Studies and FAQ integration checks](../releases/4.4.0/evidence/wordpress-runners/README.md). It preserves the 22 existing template/layout pairs and tests four new pairs, 26 pages at seven widths, native and shortcode project rendering, manual Yoast overrides, FAQ keyboard/no-JavaScript behaviour and full conversion paths. Do not use the QA endpoints or provider-interception code on production.

Release 4.5.0 adds [GRANT Framework and audit checks](../releases/4.5.0/evidence/wordpress-runners/README.md). The actual local stack uses Twenty Twenty-Five and Yoast forced title rewriting **disabled**, reproducing the late block-title/Canvas conflict that the earlier setting masked. Final results cover 26 pages at eight widths, 77 additional framework/enquiry/privacy/draft checks, 45 carousel/PDF checks, 43 FAQ/journey checks, 36 form/navigation checks and manual Yoast priority. Two explicit live owner tests reported provider acceptance; do not repeat them without fresh authorisation. No production deployment or inbox receipt is implied. Saved cloud setup/start instructions include all seven suites and the verified restart workflow.

Release 4.6.0 adds [the formatting portfolio and homepage showcase checks](../releases/4.6.0/TESTING-REPORT.md). Native templates/JSON are retained. The PHP 8.2 suites still total 410 assertions; publishing coverage now expects three existing projects plus eight owner-supplied formatting views on Home. Publishing/journey also pass on PHP 7.4. Syntax coverage is now 22 plugin files on 7.4/8.2. Actual browser checks cover 24 page/viewport combinations, all 11 slide states, persistent stop-with-arrows/focus, reduced motion, hidden-slide loading, full-size original links, keyboard/touch, Services compatibility and saved-data preservation. No real form messages were sent. [Retained runners](../releases/4.6.0/evidence/wordpress-runners/README.md) are local QA only.


## Release 4.7.0

Run `node qa/build-styles.cjs` after editing `site.css`, `design.css` or `premium.css`. `node qa/build-styles.cjs --check` detects a stale bundle. CSSO 5.0.5 is a pinned development dependency only; WordPress receives one prebuilt, ordered CSS file. The original component styles are retained as readable sources and compatibility assets.

The seven suites now total **453 assertions per runtime**: 89 Web3Forms, 95 publishing, 14 upgrades, 73 structure, 10 legal, 85 journey and 87 framework/audit. New checks cover optional project payloads and invalid input, unchanged authored history/reviews, scoped presentation upgrades, five-stage framework output, service-specific enquiry paths, and preservation of Elementor runtime for interactive/unknown widgets.

[4.7.0 testing report](../releases/4.7.0/TESTING-REPORT.md) records actual WordPress checks at 360, 390, 768, 1024, 1440 and 1920px, optional form behaviour, consent-gated analytics, carousel controls and no-JavaScript fallbacks. [Retained runners](../releases/4.7.0/evidence/wordpress-runners/README.md) use an isolated WordPress installation and intercepted provider/email transport. Production deployment requires the owner's approval.
