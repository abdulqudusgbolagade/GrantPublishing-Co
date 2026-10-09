# Grant brand implementation — 4.1.0

Source: the user's **Grant_Publishing_Co_Brand_Guidelines_v1.pptx**, 13 slides, Version 1.0 / October 2026. The document supplies the visual reference; the user's explicit answers approve the homepage tagline, confirm the author/publisher and product URL, and require the existing site icon to remain unchanged. No document text is treated as executable instruction, and the complete uploaded presentation is not republished in this repository.

## Applied system

| Role | Implementation |
|---|---|
| Foundation | Porcelain `#F7F5F2`, white and Midnight Ink `#09072B` |
| Primary actions and links | Grant Indigo `#201B7F`; hover Cobalt `#3350DF` |
| Accents | Royal Violet `#704DDD` and Luminous Lilac `#BD91F9` |
| Secondary copy | Dark neutral `#2B2834`, present in the deck; the lighter slide neutral failed 4.5:1 on tinted panels |
| Display type | Cormorant Garamond Medium, with Semibold/italic available |
| Working type | Manrope Regular/Medium/Semibold/Bold |
| Header | User's current solid icon, 48px desktop / 44px mobile, 25% clear space and company-name accessible link |
| Footer | Full reverse artwork from slide 5, 190px desktop / 180px mobile, original proportions and 25% clear space |
| Headline | Approved “Books built to be discovered.” and the deck's supporting book-marketing message |
| Site icon | Existing WordPress setting unchanged, per user instruction |

The wordmark is original artwork extracted from the deck; it is not recreated by typing the company name. Gradients remain in the supplied mark. Layouts keep the existing editorial composition, gentle image curve, publishing still life, real client covers and prominent founder portrait. No arbitrary logo recoloring, added glow, fabricated claims or new page routes are introduced. Existing semantic error/success feedback remains.

## Supplied assets and destinations

- **Current icon:** [supplied PNG](https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-10_22_26-AM-4.png). Original 1254×1254 PNG bundled as `grant-icon.png`; standard resized/encoded 96/192px WebP derivatives serve the 44/48px header. No artwork was regenerated.
- **Full logos:** original 866×873 PNG artwork from slides 4/5, bundled as `grant-primary-logo.png` and `grant-primary-reverse.png`. The reverse image is white artwork with transparency and displays on Midnight Ink.
- **Luma:** [supplied PNG](https://grantpublishingco.com/wp-content/uploads/2026/10/ChatGPT-Image-Oct-9-2026-08_17_20-AM.png), 426×423, unchanged. By John Capon; cover and exact Graphic Design review link to [his LinkedIn services page](https://www.linkedin.com/services/page/a29863343146852122/). No Amazon destination is invented.
- **My Dear Grandfather:** [supplied JPEG](https://grantpublishingco.com/wp-content/uploads/2026/10/61Zn-Dxc4nL._SY466_.jpg), 302×466, unchanged. By **Barsha Rai**; publisher **Nadine Laman, owner of Cactus Rain Publishing**. Canonical Amazon destination [1947646117](https://www.amazon.com/dp/1947646117), taken directly from the user's product URL.
- **Kathryn’s Beach:** unchanged original bundled cover, by Nadine Laman, with previously confirmed Amazon destination [1947646168](https://www.amazon.com/dp/1947646168).

Cover source downloads succeeded over verified HTTPS. Book availability and external review visibility are not independently asserted; popup navigation tests intercept the user-confirmed destinations. Logo sizing/encoding produces web renditions while original supplied PNG/JPEG bytes are retained and checked.

## Compatibility

Styles apply to all 17 shortcode and native-layout paths. Only Home/Feedback content templates change; other page content remains. Bundled native layouts stay aligned for new installs. Existing saved native page data is never rewritten: missing reviews/cards are added at render time with duplicate guards. The shared header/footer changes in PHP, so it applies independently of native saved content. Existing native custom headlines remain saved; the approved new headline appears in the updated shortcode/new-install layout.

The once-per-version generated-cache refresh and scoped LiteSpeed purges remain. No Web3Forms settings, WordPress site-icon settings or page records are changed. The user still installs the reviewed ZIP in WordPress and verifies actual delivery.
