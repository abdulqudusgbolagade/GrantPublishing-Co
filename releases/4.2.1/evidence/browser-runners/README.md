# Browser evidence reproduction — 4.2

Use the existing `/workspace/GrantPublishing-Co` checkout. Install the pinned QA runtime through `npm ci --prefix qa --ignore-scripts --cache /workspace/grant-qa/npm-cache`. Python needs lxml; optional contact sheets need Pillow. Chromium is `/usr/bin/chromium`; Playwright is available in the prepared environment with `NODE_PATH=/opt/codex/cua_node/lib/node_modules`. axe-core is under `/workspace/grant-qa/node_modules/axe-core`. Verify these paths/dependencies rather than assuming a process survives.

Copy these helpers into `/workspace/grant-qa`. Run `python3 /workspace/grant-qa/prepare-4.2.py` before any browser runner. It extracts the published 4.1 baseline, exports actual shared PHP markup with mocked WordPress APIs and builds 68 before/after fixtures. Missing official font mirrors are downloaded over verified TLS. Paths intentionally target the prepared cloud checkout; adapt them explicitly elsewhere.

Serve internally from `/workspace/grant-qa/fixtures` with `python3 -m http.server 8767 --bind 127.0.0.1 > /workspace/grant-qa/server-4.2.log 2>&1` if a valid listener is absent. Check HTTP 200 for the Home fixture and its design.css/interactions.js. Do not expose loopback preview links or mutate source/fixtures while checks run.

Run visual-4.2.cjs, brand-4.2.cjs, brand-flows-4.2.cjs, flows-4.2.cjs, interactions-4.2.cjs optional-forms-4.2.cjs and legacy-colors-4.2.cjs using the stated NODE_PATH. A focused visual run uses `ONLY_PAGES=contact,enquiry`. After tests run capture-4.2.cjs and details-4.2.cjs for final screenshots. Details hide sticky navigation/skip links during isolated component capture only; full-page captures retain production styles.

Forms and external click destinations are intercepted. After shared header/footer/forms/contact markup is actual PHP output; native Elementor widget wrappers are approximated. WordPress, themes, Elementor JavaScript/editor and actual inbox delivery are not run. The PHP runtime retains failing exit status. No real access key or client submission is used.
