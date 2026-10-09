# Browser evidence runners

These are actual cloud development scripts with explicit /workspace paths; they are not production or portable WordPress integration tests. The prepared environment retains Playwright, Chromium, axe-core and official-font mirrors. If reproducing elsewhere, install equivalent prerequisites and adapt the paths explicitly. Dependencies and font files are not included in this evidence ZIP.

The fixture builder reads /workspace/GrantPublishing-Co and baseline-4.0/grant-publishing-site (extracted from the retained 4.0 plugin ZIP). prepare-4.1.py extracts that baseline, builds fixtures and downloads the official combined Cormorant/Manrope/Instrument font CSS/files over verified HTTPS. It also prepares the versioned browser runners; captures before/after use the appropriate intended families.

Serve /workspace/grant-qa/fixtures internally with `python3 -m http.server 8767 --bind 127.0.0.1 > /workspace/grant-qa/server-4.1.log 2>&1` and verify HTTP 200 for a fixture and its CSS/JS. A stale server with a closed output stream was restarted during development. Redirect logs to a retained file so the server does not rely on an expired tool output stream. Do not publish loopback preview links.

Use `NODE_PATH=/opt/codex/cua_node/lib/node_modules node /workspace/grant-qa/visual-4.1.cjs` and the other versioned runners. Chromium is /usr/bin/chromium, axe-core is /workspace/grant-qa/node_modules/axe-core, and the visual/brand/capture scripts use /workspace/grant-qa/font-cache-4.1. The capture script decodes all images and visits the footer before full-page capture so deep lazy images appear correctly.

All form and external popup destinations are intercepted. The fixtures use synthetic shared markup/forms and approximate native Elementor widgets. They do not run WordPress, Elementor or the user's theme, send email/WhatsApp or verify provider connectivity/inbox receipt.
