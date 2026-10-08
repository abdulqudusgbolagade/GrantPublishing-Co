# Development checks

These tools are for plugin development, not required to install it on WordPress. Backend and asset-loading tests stub WordPress HTTP, mail and database APIs; they never send an enquiry or use a real access key.

With native PHP 7.4+ available, run `php qa/test-web3forms.php` from the repository root. Otherwise install the pinned official WordPress Playground runtime:

```bash
npm ci --prefix qa --ignore-scripts
PHP=7.4 node qa/php.mjs qa/test-web3forms.php
PHP=8.2 node qa/php.mjs qa/test-web3forms.php
PHP=8.3 node qa/php.mjs qa/test-web3forms.php
node qa/php-lint.cjs
node qa/test-enquiry.cjs
python3 qa/check-package.py
```

The Python check requires `lxml`. `php.mjs` preserves the interpreter exit code. These are meaningful mocked backend and source checks, not full WordPress activation, provider connectivity or email-delivery validation. Browser fixture evidence and its limitations are in the versioned release report.
