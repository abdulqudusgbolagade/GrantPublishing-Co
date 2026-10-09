# Browser evidence reproduction, 4.3

Use the existing `/workspace/GrantPublishing-Co` checkout and `qa/README.md`. Install the pinned runtime with `npm ci --prefix qa --ignore-scripts --cache /workspace/grant-qa/npm-cache`. Python needs lxml; contact sheets additionally need Pillow. Verify Chromium at `/usr/bin/chromium`, Playwright through `NODE_PATH=/opt/codex/cua_node/lib/node_modules`, and axe-core at `/workspace/grant-qa/node_modules/axe-core`. These paths describe the prepared cloud environment.

Copy these helpers into `/workspace/grant-qa`. Run `python3 /workspace/grant-qa/prepare-4.3.py`. It extracts the published 4.2.1 baseline, exports actual shared PHP header/footer/form/contact/showcase markup with mocked WordPress APIs, downloads missing official font mirrors using verified HTTPS and builds 74 current/baseline fixtures. It also runs `build-legacy-4.3.py` to apply real PHP compatibility filters to six baseline native fixtures. WordPress and the Elementor editor are not installed.

Serve internally from `/workspace/grant-qa/fixtures` with `python3 -m http.server 8767 --bind 127.0.0.1 > /workspace/grant-qa/server-4.3.log 2>&1` only if no valid listener already serves those fixtures. Verify representative HTML plus design.css, showcase.js and the bundled Luma PDF. Do not expose loopback links or rebuild fixtures while browser runners are active.

Run `visual-4.3.cjs`, `brand-4.3.cjs`, `brand-flows-4.3.cjs`, `flows-4.3.cjs`, `interactions-4.3.cjs`, `optional-forms-4.3.cjs`, `legacy-colors-4.3.cjs`, `legacy-positioning-4.3.cjs` and `showcase-4.3.cjs` with the stated NODE_PATH. A targeted run can use `ONLY_PAGES=home,services`. The complete audit covers 320 combinations; the retained final matrix merges the full run with the 32-combination recheck after the final keyboard-focus adjustment.

The showcase runner uses Playwright’s controlled clock. It awaits reduced-motion changes before advancing time and checks actual selected image URLs and rendered dimensions, rather than equating responsive-image CSS intrinsic width with source-file pixels. Deferred covers are intentionally not loaded by the initial visual audit; the showcase runner exercises all of them.

After checks, run `capture-4.3.cjs`, `details-4.3.cjs`, `portfolio-captures-4.3.cjs` and optionally `contact-sheet-4.3.py`. Requested images are decoded. Isolated component crops hide the sticky navigation during capture only.

Forms and external click destinations are intercepted. The Luma PDF is also fetched locally as actual PDF bytes and checked against its bundled source before the keyboard click destination is intercepted. All WordPress API calls are mocks; Elementor widget DOM is approximated. Actual theme/editor rendering, other browsers and inbox delivery remain staging checks.
