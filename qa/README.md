# Development checks

These checks are for plugin development. WordPress HTTP, mail and database APIs are mocked; no real enquiry or key is used. Native PHP 7.4+ can run the three PHP suites directly. Otherwise use the pinned official WordPress Playground runtime:

```bash
npm ci --prefix qa --ignore-scripts --cache /workspace/grant-qa/npm-cache
PHP=7.4 node qa/php.mjs qa/test-web3forms.php
PHP=7.4 node qa/php.mjs qa/test-publishing.php
PHP=7.4 node qa/php.mjs qa/test-upgrades.php
PHP=8.2 node qa/php.mjs qa/test-web3forms.php
PHP=8.2 node qa/php.mjs qa/test-publishing.php
PHP=8.2 node qa/php.mjs qa/test-upgrades.php
PHP=8.3 node qa/php.mjs qa/test-web3forms.php
PHP=8.3 node qa/php.mjs qa/test-publishing.php
PHP=8.3 node qa/php.mjs qa/test-upgrades.php
node qa/php-lint.cjs
node qa/test-enquiry.cjs
python3 qa/check-package.py
```

Use a writable npm cache; the cloud home cache is unavailable. Keep TLS and lockfile integrity verification enabled. The current runtime passed with Node 24.19 and npm 11.9 despite a package npm-version warning. Python checks require `lxml`; dependency files stay ignored.

`test-web3forms.php` covers 72 delivery/settings/asset/prepublication assertions; `test-publishing.php` covers 92 positioning/showcase/review/cover/case-study compatibility, contact-icon preservation and header/footer rendering assertions; `test-upgrades.php` covers 14 version, concurrency, retry and scoped cache-refresh assertions. Each suite passed on PHP 7.4, 8.2 and 8.3. `php.mjs` preserves interpreter exit codes.

`check-package.py` verifies all 20 template/layout pairs, native content parity, routes/assets, forms, real anchors, markup/IDs and CSS markers. After intentionally editing templates, `python3 qa/sync-layouts.py` regenerates bundled new-install native JSON only; it never contacts WordPress or changes installed pages. Review generated diffs before committing.

Browser evidence and its limitations are in [the 4.3 testing report](../releases/4.3.0/TESTING-REPORT.md). The release evidence ZIP includes the fixture builder and browser runners used in the prepared cloud workspace. They approximate Elementor DOM and intercept submissions, rather than running a live WordPress installation.


The 4.3 browser workflow exports actual shared header/footer/contact/form/showcase markup through PHP with mocked WordPress APIs, then approximates native Elementor widget DOM. `build-legacy-4.3.py` additionally runs the real PHP compatibility filters on the previous release’s rendered native markup. Reproduction steps and helpers are in `releases/4.3.0/evidence/browser-runners/README.md`. The before baseline is the retained 4.2.1 ZIP. The 320-combination audit loads requested images while preserving deferred carousel images; the dedicated showcase suite exercises every slide, timing, keyboard focus and PDF bytes. No WordPress editor or actual provider delivery is implied. Final captures decode requested images; isolated component crops hide sticky navigation only during capture.
