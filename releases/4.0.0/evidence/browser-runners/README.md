# Cloud browser runners

These are the actual development runners retained as evidence, with explicit paths to the prepared /workspace cloud environment. They are not portable production scripts or a WordPress installation.

From /workspace/grant-qa, build fixtures with `python3 build-fixtures.py`. It reads the current checkout and /workspace/grant-qa/baseline-3.4/grant-publishing-site (the plugin extracted from the retained 3.4 ZIP). Serve fixtures internally with `python3 -m http.server 8767 --bind 127.0.0.1` from the fixtures directory. Verify a fixture returns HTTP 200 before running tests. Do not expose a loopback preview link.

Chromium is /usr/bin/chromium. Playwright resolves via `NODE_PATH=/opt/codex/cua_node/lib/node_modules`; axe-core is /workspace/grant-qa/node_modules/axe-core. The visual runner also uses locally mirrored official Grant font files in /workspace/grant-qa/font-cache. These dependencies/font mirrors are retained environment files, not distributed in the plugin/evidence archive. If reproducing elsewhere, install equivalent tools and explicitly adapt paths and font mapping; browser results are not claimed reproducible without these prerequisites.

Run visual-4.0.cjs, interactions-test.cjs, flows-4.0.cjs and optional-forms-4.0.cjs. ONLY_PAGES=contact,enquiry limits the visual runner for the final 32-check form audit. The builder approximates Elementor widgets and synthetic shared shell/form. It does not start WordPress or Elementor. Form and external popup destinations are intercepted; no enquiry, mail, WhatsApp or third-party submission is sent.
