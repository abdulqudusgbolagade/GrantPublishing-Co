# Actual WordPress 4.3.1 checks

These retained runners tested actual WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6 and PHP 8.5.10, using official Playground CLI 3.1.57 and SQLite. They are QA helpers outside the installable plugin. No real provider message was sent.

## Prepared workspace

The original integration tree is `/workspace/grant-wp-qa/site` on loopback port 9401. A separately bootstrapped clean tree is `/workspace/grant-wp-qa/repro-site` on port 9402. The latter verifies the final migration from 17 saved native pages, including original-content hashes, private fake provider settings and the default WordPress Privacy Policy draft. Both are isolated test installations, not a copy of the production database.

`grant-qa.php` intercepts every Web3Forms request and WordPress email. It simulates acceptance, rejection and timeout from `X-Grant-QA-Response`. The only seed access key is a fake all-zero UUID. **Never copy this MU plugin, the helper PHP endpoints, test users, blueprint or database to production.** Keep the server on loopback and do not expose preview URLs.

## Dependencies and clean bootstrap

Node 24.19/npm 11.9, Python `lxml`/Pillow, Chromium `/usr/bin/chromium`, Playwright via `NODE_PATH=/opt/codex/cua_node/lib/node_modules`, and axe-core at `/workspace/grant-qa/node_modules/axe-core` were used. CLI dependencies have the retained exact lockfile. The package emits an npm engine warning in this cloud but the tested commands complete.

Copy these helper files to `/workspace/grant-qa` for the retained browser runner paths. Preserve the existing `/workspace/grant-qa/font-cache-4.1` font mirror used by the interaction runners. The full responsive runner fetches the same fonts over verified HTTPS through curl; Chromium in this cloud does not inherit the proxy CA. TLS verification is never disabled.

For a clean local tree only:

```bash
python3 releases/4.3.1/evidence/wordpress-runners/prepare-wordpress.py
node /workspace/grant-wp-qa/node_modules/@wp-playground/cli/wp-playground.js server \
  --php=8.5 --wp=7.0.7 --wordpress-install-mode=install-from-existing-files \
  --mount-before-install=/workspace/grant-wp-qa/repro-site:/wordpress \
  --mount=/workspace/GrantPublishing-Co/grant-publishing-site:/wordpress/wp-content/plugins/grant-publishing-site \
  --blueprint=/workspace/GrantPublishing-Co/releases/4.3.1/evidence/wordpress-runners/bootstrap.json \
  --port=9402 --workers=1
```

The preparation script refuses to replace an existing tree. Official archives are checked against retained SHA-256 digests and ZIP CRCs. Bootstrap was tested from a clean tree with these mounts. One worker warns about throughput; avoid simultaneous state-changing runners. Bootstrap can take several minutes with Yoast and Elementor because it creates and converts 17 native pages before performing the repair.

The CLI writes `qa-result.json` into the mounted site. Read that file directly for hash/preservation evidence. `qa-repair.php` returns the final 22 route URLs and policy status/template/backup. A public request never performs the production repair; this isolated helper explicitly sets the fake test administrator.

**Never rerun bootstrap against an installed database.** To resume an existing tree, use `--wordpress-install-mode=install-from-existing-files-if-needed` and `resume.json`, rather than `bootstrap.json`. Stop/restart only a server you started. Fresh-task cloud snapshot restoration remains unverified; this clean local bootstrap is verified.

## Runners used

The retained browser runners target the original tree at 9401 and report into `/workspace/grant-qa/production-after`. Before running, confirm that it is your isolated tree with the interception MU plugin, obtain `/qa-repair.php` into `/workspace/grant-qa/wp-repair-final.json`, and keep the actual source stable during tests.

```bash
NODE_PATH=/opt/codex/cua_node/lib/node_modules node /workspace/grant-qa/wordpress-production.cjs
python3 /workspace/grant-qa/crawl-repaired.py
NODE_PATH=/opt/codex/cua_node/lib/node_modules node /workspace/grant-qa/wordpress-flows.cjs
NODE_PATH=/opt/codex/cua_node/lib/node_modules node /workspace/grant-qa/showcase-4.3.1.cjs
```

`wordpress-production.cjs` checks all 22 pages at 1440, 1280, 1024, 768, 480, 390 and 360px, requested-image loading, overflow, one H1, axe WCAG 2 A/AA and 2.1 AA rules, and JavaScript exceptions; it captures 1440px/390px full pages. Dedicated carousel checks exercise deferred covers, five-second timing with a controlled clock, manual pause/play, hover/focus, keyboard, touch, no-JavaScript fallback, reduced motion, accurate scopes and unchanged PDF bytes. Form flow checks exercise real WordPress handlers with intercepted provider responses.

The crawler validates every named link/anchor on these pages, all ten service cards, one title/description/canonical, visible footer email, new-tab protection, five reviews and exact approved legal paragraphs. External Amazon, LinkedIn and Upwork account pages are not asserted reachable behind login/anti-bot barriers. Both actual case-study PDF byte links were checked separately.

`production-audit.py` and `live-production.cjs` are the read-only production baseline runners. Do not use the form-flow runner against production. Raw live HTML and database files are excluded from published evidence. The testing report records the remaining production and browser limits.
