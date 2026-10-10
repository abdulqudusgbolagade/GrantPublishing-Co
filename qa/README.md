# Development checks

These checks are for plugin development. WordPress HTTP, mail and database APIs are mocked; no real enquiry or key is used. Native PHP 7.4+ can run the five PHP suites directly. Otherwise use the pinned official WordPress Playground runtime:

```bash
npm ci --prefix qa --ignore-scripts --cache /workspace/grant-qa/npm-cache
PHP=7.4 node qa/php.mjs qa/test-web3forms.php
PHP=7.4 node qa/php.mjs qa/test-publishing.php
PHP=7.4 node qa/php.mjs qa/test-upgrades.php
PHP=7.4 node qa/php.mjs qa/test-structure.php
PHP=7.4 node qa/php.mjs qa/test-legal.php
PHP=8.2 node qa/php.mjs qa/test-web3forms.php
PHP=8.2 node qa/php.mjs qa/test-publishing.php
PHP=8.2 node qa/php.mjs qa/test-upgrades.php
PHP=8.2 node qa/php.mjs qa/test-structure.php
PHP=8.2 node qa/php.mjs qa/test-legal.php
PHP=8.3 node qa/php.mjs qa/test-web3forms.php
PHP=8.3 node qa/php.mjs qa/test-publishing.php
PHP=8.3 node qa/php.mjs qa/test-upgrades.php
PHP=8.3 node qa/php.mjs qa/test-structure.php
PHP=8.3 node qa/php.mjs qa/test-legal.php
node qa/php-lint.cjs
node qa/test-enquiry.cjs
python3 qa/check-package.py
```

Use a writable npm cache; the cloud home cache is unavailable. Keep TLS and lockfile integrity verification enabled. The current runtime passed with Node 24.19 and npm 11.9 despite a package npm-version warning. Python checks require `lxml`; dependency files stay ignored.

`test-web3forms.php` covers 72 delivery/settings/prepublication assertions; `test-publishing.php` covers 95 positioning/showcase/review/contact/header/footer assertions; `test-upgrades.php` covers 14 migration/cache assertions; `test-structure.php` covers 69 route, permission, concurrency, backup, redirect, metadata, legal-link, image and compatibility assertions; `test-legal.php` covers 10 starter-policy preservation, backup, failure and WordPress template-carry-forward assertions. All 260 assertions passed on PHP 7.4, 8.2 and 8.3, totalling 780. `php.mjs` preserves interpreter exit codes.

`check-package.py` verifies all 22 template/layout pairs, native content parity, routes/assets, forms, anchors, markup/IDs and CSS markers. After intentionally editing templates, `python3 qa/sync-layouts.py` regenerates bundled new-install native JSON only; it never contacts WordPress or changes installed pages. Review generated diffs before committing.

## Actual WordPress browser checks

Release 4.3.1 also runs actual WordPress 7.0.7, Elementor 4.3.4 and Yoast 28.6 through official Playground CLI 3.1.57, PHP 8.5.10 and SQLite. This is an isolated test installation, never the production database. WordPress provider HTTP and mail are intercepted by a local-only MU plugin; the seed access key is fake. Do not copy the QA MU plugin, scripts, test users or database to production.

[Reproduction instructions and retained runners](../releases/4.3.1/evidence/wordpress-runners/README.md) describe the prepared workspace, a clean bootstrap and safe restart. [The testing report](../releases/4.3.1/TESTING-REPORT.md) distinguishes the read-only live baseline from the local repaired installation. No inbox receipt or production installation is implied.

The earlier 4.3.0 fixture checks remain documented in their own release. Those approximate Elementor DOM; the 4.3.1 audit uses actual Elementor output. Keep Playwright/axe dependencies and test WordPress outside the installable plugin. Preserve TLS and artifact checks; the cloud browser's missing proxy CA is handled through curl's configured trust store, without disabling certificate verification.
