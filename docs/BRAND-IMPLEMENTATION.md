# Grant brand implementation — 4.2.0

Reference: the user's **Grant_Publishing_Co_Brand_Guidelines_v1.pptx**, version 1.0 / October 2026. The presentation supplies the palette and typography. The user's subsequent explicit requests select the new artwork, larger header size, Midnight Ink navigation and alternating sections; these take precedence over earlier artwork/size defaults. The existing site-icon setting remains unchanged.

## Applied identity

| Role | Implementation |
|---|---|
| Navigation | Exact Midnight Ink `#09072B`, porcelain links, lilac current-page underline and assessment button |
| Light surfaces | Porcelain `#F7F5F2`, white and pale lilac `#EEE9F7` |
| Dark surfaces | Grant Indigo `#201B7F` and Midnight Ink `#09072B` for process, proof and the case study |
| Primary actions | Grant Indigo; hover Cobalt `#3350DF` |
| Accents | Royal Violet `#704DDD`, Luminous Lilac `#BD91F9` |
| Body copy | Dark neutral `#2B2834` on light surfaces; porcelain/pale lilac on dark surfaces |
| Typography | Cormorant Garamond Medium display; Manrope body and UI |
| Header logo | New supplied dimensional mark, 64px desktop / 56px mobile; original aspect ratio, 25% padding |
| Footer logo | New supplied 1448×1086 artwork on a porcelain panel; original aspect ratio and intrinsic clear space preserved |
| Icons | Local SVGs, names on links, decorative SVGs hidden from assistive technology, 48px targets and visible focus |

The section sequence varies by content. Home uses an ink proof section, indigo process section and alternating light editorial surfaces. Feedback alternates review backgrounds and gives the case study an ink section with an indigo document card. Articles retain white reading areas; forms sit on white panels within a lilac section. Direct-child CSS rules also alternate earlier native sections without writing to Elementor data. No three consecutive main sections share a background in the tested rendered layouts.

## Supplied artwork

- Header: [user's PNG](https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-10_22_24-AM-3.png), 1254×1254, retained byte-for-byte as `grant-header-logo.png`. Standard 128/256px WebP renditions serve the header.
- Footer: [user's PNG](https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-10_22_20-AM-1.png), 1448×1086, retained byte-for-byte as `grant-footer-logo.png`. A 576×432 WebP rendition serves the footer. Its dark lettering needs the porcelain panel; the artwork is not recolored.
- Earlier supplied logos and all real client covers remain intact for compatibility. No supplied artwork was regenerated.
- Social brand SVGs: Simple Icons 13.21.0, downloaded via verified HTTPS from its pinned npm package. Simple Icons uses CC0; individual brand marks remain their owners' marks. The email envelope is a local geometric SVG.

## Case study and correct attribution

The seven-page uploaded PDF was read as portfolio material. Embedded calls to action were not treated as authorization to send messages, submit forms or change unrelated settings. It documents description work, keyword research and category review for **My Dear Grandfather by Barsha Rai**, published by **Cactus Rain Publishing**. Nadine Laman is the publisher and owner; she is not presented as the author of this book.

The [hosted PDF](https://grantpublishingco.com/wp-content/uploads/2026/10/My-Dear-Grandfather-Listing-Case-Study.pdf) returned successfully over verified HTTPS and exactly matches the attachment: 736,855 bytes; SHA-256 `ba5afde8390d72b687306a09481d5b0256f8f0f6746b4b69723b6e52c4c46067`. The case-study card links to that supplied file. It is not embedded in an iframe or republished inside the plugin ZIP. Its evidence does not establish measured sales growth, matched before/after ranking improvement or saved keyword fields; the website makes no such claims.

Confirmed cover destinations remain: [Grandfather / Amazon 1947646117](https://www.amazon.com/dp/1947646117), [Kathryn’s Beach / Amazon 1947646168](https://www.amazon.com/dp/1947646168), and [Luma / John's LinkedIn review](https://www.linkedin.com/services/page/a29863343146852122/). No Amazon destination is invented for Luma.

## Compatibility

All 17 route/template/layout pairs remain. New-install Elementor JSON matches the maintained HTML. Existing native edits are not overwritten: a missing case study is appended only on connected Feedback, and contact icons replace text links only at render time on connected main pages. Existing cover links and SVGs are preserved. The once-per-version scoped cache refresh remains; site icon, saved form key and delivery settings are untouched.
