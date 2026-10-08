# OW Publishing House reference design audit

Inspected on 8 October 2026. The exact user-confirmed reference is **https://owpublishiing.com/** (double `i`). Its live page title is “OW Publishing House — Produce, Publish & Grow Your Book”. Public home, About, Self-Publishing and Consultation pages plus shared CSS were retrieved successfully.

## Evidence and rendering scope

`home.html` and `styles.css` are copies of the actual public response, with no source edits. `mirror/` is a local visual inspection copy of four public pages, CSS, relevant images, scripts and Google font files. All external downloads used curl with TLS verification enabled. The local mirror rewrites the Google Fonts CSS link and font URLs to downloaded font files; design markup/CSS remains as received.

A direct Chromium navigation through the configured HTTPS proxy failed with `ERR_CERT_AUTHORITY_INVALID`. TLS checking was not disabled. The screenshots therefore show **a local mirror of downloaded live reference assets**, rather than an authenticated/live browser session. This is adequate design evidence; it is not a functional production test. No reference form was submitted and no reference assets should be copied into the Grant plugin.

Screenshots: `mirror-{index,about,self-publishing,consultation}-{1440,390}.png`; top viewport screenshots `mirror-{index,about}-{1440,390}-hero.png`. Structured observations in `mirror-observations.json`.

## What gives the reference its character

- **Palette:** ink navy `#0F1A31`, raised navy `#14213D`, warm gold `#D4A24A`, pale gold `#E0C281`, paper cream `#FDFAF5`, warm panel `#F5EFE3`, cream divider `#E7DECD`. Light body text `#EAF0FA` and secondary `#A9B6CC` appear on navy; dark secondary copy `#4C5568` appears on cream.
- **Type:** Cormorant Garamond headings paired with Outfit body. Large restrained serif statements, occasional gold italic phrase, small widely tracked uppercase section labels. Home H1 3–4.6rem, body 17px at 1.65 line height; section introductions are limited to 640px.
- **Rhythm:** 1180px maximum wrapper with responsive 20–48px gutters. Generous 56–104px section padding. Each section has a clear title/lead group separated from cards by 32–56px.
- **Contrast of surfaces:** navy hero; cream chooser cards; navy value grid; warm cream process; cream platform grid; navy case study; cream resources; navy final CTA/footer. The alternating background bands make a long page legible at a glance.
- **Buttons:** gold filled pill with navy text, paired with gold outline pill on navy. At least one decisive action per important section. Card actions use dark text with gold underline instead of competing primary buttons.
- **Cards:** white backgrounds, fine warm border, 14–22px radii and light shadow. Dark sections use slightly raised navy cards with subtle darker borders. Hover motion is small and purposeful, e.g. 4–6px lift and a gold border/accent line.
- **Header:** slim sticky translucent navy header, small monogram/serif name, compact menu, gold consultation button. Menu collapses below 1024px. Gold underline identifies hover.
- **Portrait:** About hero gives the founder image an entire right column, almost equal weight to the text; aspect ratio 4:5, 20px rounded frame and dark shadow. Image fills its frame, with a bottom gradient caption. This is strongly more prominent than a small floated avatar.
- **Organization:** a route chooser starts early; a short values grid, numbered process, concrete work proof, resources and final CTA follow. Subpages share the same framing and visual vocabulary.
- **Forms:** grouped sections, clear legends, a two-column desktop field grid and one-column mobile grid in a white bordered panel; explicit privacy and submit feedback. Preserve Grant's already tested validation/Web3Forms transport while updating visual framing.

## Recommendations for Grant

Keep all 17 existing page routes, section order, truthful copy, logo, book cover, founder image and Web3Forms configuration. Apply the **surface, spacing and color relationships**, using Grant's existing Instrument Serif/Manrope pairing if desired so its typography remains familiar.

1. Make Home/page heroes and final CTA a confident navy anchor with white copy and pale gold highlights. Retain the current selected client book project in the existing right column; frame it in a raised navy/warm book showcase panel.
2. Introduce warm cream light sections and gold accents on labels, section rules and buttons. Use dark gold for small text on cream, pale gold only on navy.
3. Give existing service rows/steps/article cards stronger containment with warm borders, gentle corners, balanced padding and obvious link targets; do not turn static decorative cards into misleading links.
4. Keep the 3.3 portrait enlargement, improve its intentional navy/gold frame and lighting contrast, and retain full-width/mobile treatment without cropping the actual face.
5. Preserve accessible mobile menu, Escape/focus return, keyboard focus, reduced-motion handling and form feedback. Verify 320px/390px/768px/desktop, frozen/native Elementor fixtures and all pages after palette changes.

## Do not inherit reference defects

The reference CSS calls its small-text gold `#B4842F` on cream “AA”, but contrast is only **3.21:1** on `#FDFAF5` (fails normal-size 4.5:1). Recommended deeper gold `#8B651F` reaches **5.07:1**. Gold `#D4A24A` with navy `#0F1A31` text reaches **7.48:1**; pale gold `#E0C281` on navy reaches **10.07:1**.

The mirrored Home and About overflow at 390px; Consultation and Self-Publishing do not. Avoid copying absolute-position decorations and nonwrapping footer arrangements without testing. The reference also includes placeholders in comments and generated book mockups; these are irrelevant to Grant and must not become claims/assets in Grant's plugin.
