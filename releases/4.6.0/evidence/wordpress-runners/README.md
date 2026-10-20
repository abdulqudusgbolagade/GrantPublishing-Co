# Reproduce 4.6.0 checks

Use the existing isolated WordPress installation and [verified restart instructions](../../../4.5.0/evidence/wordpress-runners/README.md). The pinned CLI tree and fake-key provider/mail interception remain outside the plugin. Never deploy QA endpoints, configuration, test users or databases; never bootstrap over an existing installation.

Working directory: `/workspace/GrantPublishing-Co`. Tests use actual WordPress 7.0.7 / Elementor 4.3.4 / Yoast 28.6 / PHP 8.5 / SQLite / Twenty Twenty-Five on local port 9401. Use `NODE_PATH=/opt/codex/cua_node/lib/node_modules`, Chromium `/usr/bin/chromium`, axe-core `/workspace/grant-qa/node_modules/axe-core` and the retained fonts under `/workspace/grant-qa/font-cache-4.1`. Copy these runners into `/workspace/grant-qa/formatting-4.6/` if needed.

- `responsive.cjs`: Home, Services and Book Formatting at eight widths. It scrolls/decode-loads visible images, checks overflow/H1/images/errors and runs axe. Set `ONLY_PAGES=home,services,book-formatting`.
- `interactions.cjs`: actual timing, deferred loading, all slide captions/proportions, controls, persistent stopping, gallery links, reduced motion, touch, static fallback, existing Services controls and before/after geometry. It expects the retained authentic `before-geometry.json`; do not invent an old baseline from the updated build.
- `detail-checks.cjs`: keyboard original-image opening, full-resolution mockup selection, menu/Escape/focus, intentional failed-image fallback and component captures.
- `all-slide-layouts.cjs`: all 11 slides across narrow, tablet, desktop and the stacking breakpoint. Checks image/caption/control overlap and overflow.
- `crawl.py`: all 26 core routes, metadata element counts, named links/destinations/anchors, ten service cards, original reviews and exact legal text. Route inventory uses the retained `/workspace/grant-qa/framework-setup-4.5.json` map.

The gallery’s external PNG originals were downloaded from the exact owner URLs using TLS-verified curl, checked as images, hashed and visually inspected. External image popup tests use curl’s trusted CA because Chromium does not inherit the cloud proxy CA. Font requests use retained approved bytes. Do not disable TLS verification.

`preservation.json` compares saved page/native/Yoast data with the verified 12 October 4.5.0 snapshot using the same snapshot algorithm. `asset-optimisation.json` records byte savings and exact RGBA equality for all eight full-resolution assets. The new preview is a faithful downsample, not a claim of equality to the full-size source.

No real enquiry submissions are needed for this task. Unit/JS form tests use isolated stubs; prior live owner receipt is already confirmed. The Services showcase intentionally keeps its original pause control while Home has none. Preserve the shared framework, metadata and private settings when extending these tests.
