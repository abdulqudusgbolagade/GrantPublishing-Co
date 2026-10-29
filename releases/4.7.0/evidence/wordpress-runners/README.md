# Local WordPress reproduction

These runners are QA material, never plugin installation files. They use the actual isolated WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6, Twenty Twenty-Five and PHP 8.5.10/SQLite installation retained from previous releases. Yoast forced title rewriting remains disabled. The production website was not used for test submissions.

Follow [the existing bootstrap/restart instructions](../../../4.3.1/evidence/wordpress-runners/README.md). Reuse the already running listener on port 9401 rather than starting a second server or resetting the database. The plugin is mounted from the checkout. Keep provider/mail interception and the fake key in the local QA installation only.

Prerequisites in the prepared cloud workspace:

- Playwright at `/opt/codex/cua_node/lib/node_modules`, Chromium at `/usr/bin/chromium`.
- axe at `/workspace/grant-qa/node_modules/axe-core`.
- The original 26-route fixture `/workspace/grant-qa/framework-setup-4.5.json`.
- For the retained carousel runner's unused remote-font fallback, `/workspace/grant-qa/font-cache-4.1/`. Grant's current fonts load from the plugin itself.
- Node dependencies installed with `npm ci --prefix qa` and the writable cache documented in the main QA README.

The test MU plugin intercepts `https://api.web3forms.com/submit` and `wp_mail`, with response fixtures selected by `X-Grant-QA-Response`. `/qa-reset.php` clears only the local rate-limit bucket. No real access key is used. The shortcode comparison uses a local-only content filter on Home: when `gpc-qa-shortcode=home`, return `gpc_site_page('home')` at `the_content` priority 36. These local endpoints/filters must not be deployed.

Retained runners:

```bash
export NODE_PATH=/opt/codex/cua_node/lib/node_modules
node responsive.cjs
node forms.cjs
node carousel.cjs
node all-slide-layouts.cjs
ONLY_PAGES=home node shortcode.cjs
python3 crawl.py
```

Paths/output directories are explicit in the runners; adapt them when moving the prepared QA workspace. Each exits on a failed assertion. Check every log and JSON result. The final Home checks replaced its earlier six rows in the full 156-state matrix after the font-line-break fix; other pages retained the same tested shared styles and scripts. A further six-width shortcode check and 66-slide geometry check cover both rendering paths.

The responsive runner retrieves remote GET assets through curl's configured CA trust to accommodate the cloud browser's missing proxy CA. It does not disable certificate verification. Provider interception is independent from this GET routing.

Lighthouse 12.8.2 was installed outside the plugin. Before/final runs used the same URL and categories:

```bash
node /workspace/grant-performance/node_modules/lighthouse/cli/index.js \
  http://127.0.0.1:9401/ --chrome-flags='--headless --no-sandbox' \
  --only-categories=performance,accessibility,seo --output=json --output-path=mobile.json
```

Add `--preset=desktop` for desktop. Before results were captured before source changes; after results were captured once the final font and caption fixes passed. These lab results are not live-host Core Web Vitals. No analytics or inbox receipt is inferred from them.
