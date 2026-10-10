# Actual WordPress 4.4.0 checks

These QA helpers operate only on the isolated tree at `/workspace/grant-wp-qa/site`, loopback port **9401**. They are **excluded from the installable ZIP**. Never copy endpoints, the MU provider interception, test users or a QA database to production.

The prepared runtime is WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6, PHP 8.5.10 and SQLite through official Playground CLI 3.1.57. [The earlier preparation/bootstrap instructions](../../../4.3.1/evidence/wordpress-runners/README.md) retain verified archives and the exact dependency lockfile. Resume an existing isolated tree with `resume.json`; never rerun a bootstrap against an installed database.

The 4.4.0 work resumed the 4.3.1 test site. Its 22 connected pages and the additional Canvas regression page remained. The seed Web3Forms key is fake. The MU helper intercepts every provider request and WordPress mail; no real message is sent. One-worker mode warns about throughput, so run state-changing helpers sequentially.

## Order used

1. Mount the current plugin into the isolated site and resume the existing tree on 9401. Confirm the local MU interception remains active.
2. Put `qa-journey.php`, `qa-manual-seo.php` and `qa-new-native.php` at the local WordPress root only. `qa-journey.php` explicitly uses the test administrator, creates just the four pages, records preservation hashes and verifies repeat setup. Save its response as `/workspace/grant-qa/journey-migration-4.4.json`.
3. Run the full rendered crawl and 26-page responsive audit. The responsive runner reads that route map and outputs to `/workspace/grant-qa/authority-after`; Playwright is available through `NODE_PATH=/opt/codex/cua_node/lib/node_modules`. axe-core is in `/workspace/grant-qa/node_modules`.
4. `check-seo.py` temporarily sets manual Yoast fields on the new Grandfather test page, checks rendered values and the Canvas regression path, and restores the fields in `finally`. The MU helper additionally removes title support at `template_include` priority 100 on the dedicated Canvas page, testing the later compatibility repair. No production SEO setting is involved.
5. `qa-new-native.php` deliberately converts only the four new QA pages. It verifies saved hashes of the existing 22 pages remain unchanged. This is a test of both rendering modes, not an installation instruction to bulk-convert pages.
6. Recheck the new native pages at all seven widths. After the final spacing/template refinements, rerun the full crawl and the four new pages plus Visibility/Ads at all widths. Keep the plugin source stable during the final checks.
7. Run `form-flows.cjs` and `journey-flows.cjs` sequentially. The form runner expects the isolated `qa-reset.php` from the previous release and the local provider MU stub. Never target production.

```bash
python3 releases/4.4.0/evidence/wordpress-runners/crawl.py
NODE_PATH=/opt/codex/cua_node/lib/node_modules node releases/4.4.0/evidence/wordpress-runners/responsive.cjs
python3 releases/4.4.0/evidence/wordpress-runners/check-seo.py
NODE_PATH=/opt/codex/cua_node/lib/node_modules node releases/4.4.0/evidence/wordpress-runners/form-flows.cjs
NODE_PATH=/opt/codex/cua_node/lib/node_modules node releases/4.4.0/evidence/wordpress-runners/journey-flows.cjs
```

The final focused responsive pass used `ONLY_PAGES=case-studies,case-grandfather,case-luma,faq,amazon-visibility,amazon-ads`. The normal image-loading semantics are retained: each image is scrolled into view and awaited, then responsive candidates are decoded before screenshot capture. Changing `loading` to eager on WordPress `sizes="auto"` images can produce misleading screenshot blanks; the final runner does not do that. Static first-slide/deferred carousel images are preserved.

The responsive runner uses curl’s configured trusted CA for external font GETs because the cloud Chromium does not inherit the proxy CA. Certificate verification is never disabled. Interaction runners reuse the verified font mirror retained at `/workspace/grant-qa/font-cache-4.1`.

Published evidence contains structural results and hashes, not raw nonce-bearing HTML, credentials, message bodies or databases. Fresh-task cloud restoration, production installation, production sitemap refresh and live inbox receipt remain separate verification limits.

`console.cjs` replays the final actual WordPress HTML captures at their original loopback URLs, loading the normal local CSS/JavaScript and verified HTTPS fonts. It records console errors, page exceptions and failed requests across all 26 pages. This complements the real WordPress crawl/flows and does not replace backend or production checks.
