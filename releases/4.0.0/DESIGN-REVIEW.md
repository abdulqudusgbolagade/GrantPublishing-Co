# Grant Publishing Co. 4.0 design review

Prepared 9 October 2026. This is a plugin redesign prepared for installation, not screenshots of an upgraded live WordPress site.

## The direction

OW Publishing's strong hierarchy, spacious composition and clear organization informed the review. The user's later direction superseded the earlier gold treatment: **Grant blue, cream and ink** now lead all 17 pages. The Hero–17 prompt was adapted into a publishing studio composition with expressive serif type and a still image. The [polished brief](../../docs/PUBLISHING-DESIGN-BRIEF.md) explains the implementation.

The opening pairs Grant's existing truthful positioning with an atmospheric book-studio photograph and a clear enquiry. The generated still life is decorative; the supplied logo, founder portrait and Kathryn's Beach cover remain genuine original files. Warm light and the founder's blue jacket support the palette without presenting imaginary client work.

## Visitor improvements

- Home: composed photographic hero, two contextual client reviews, genuine clickable Kathryn cover, three clear service choices, numbered process, larger founder portrait and useful article previews.
- Services and seven details: a scannable directory with concrete deliverables, service-specific explanatory figures and early enquiries that preselect the actual service.
- About: readable biography, prominent portrait and a direct client-feedback route.
- Feedback: John's exact Graphic Design review, all three original Nadine projects, the additional Nadine quote and truthful client attribution. Kathryn opens the confirmed Amazon product; John opens the requested LinkedIn source.
- Insights and three articles: a featured story, purposeful diagrams, comfortable reading width, approximate reading time and real in-page contents links.
- Contact and Assessment: form before supporting information, semantic field groups, persistent labels, required/optional guidance and optional book details that expand when useful. Error retention and delivery handling remain.
- Shared shell: cream sticky header, brand-blue actions, ink footer, accessible menu at tablet/phone widths, clear focus and reduced-motion support.

Luma's genuine cover is the remaining asset task. The inline cover could be seen in chat but was not supplied as a downloadable file. This version shows the book title, author and exact review without inventing or displaying a broken image. Its final cover destination is the user's confirmed LinkedIn review page while North & Mercer is under construction.

## Before and after

Before captures use published 3.4.0; after captures use final 4.0 local shortcode fixtures. Intended Grant fonts are mirrored from official Google Fonts sources. Both are local renders, not live installation evidence.

| Page | Before desktop | After desktop | Before phone | After phone |
|---|---|---|---|---|
| Home | [View](evidence/screens/before-home-1440.png) | [View](evidence/screens/after-home-1440.png) | [View](evidence/screens/before-home-390.png) | [View](evidence/screens/after-home-390.png) |
| About | [View](evidence/screens/before-about-1440.png) | [View](evidence/screens/after-about-1440.png) | [View](evidence/screens/before-about-390.png) | [View](evidence/screens/after-about-390.png) |
| Services | [View](evidence/screens/before-services-1440.png) | [View](evidence/screens/after-services-1440.png) | [View](evidence/screens/before-services-390.png) | [View](evidence/screens/after-services-390.png) |
| Contact | [View](evidence/screens/before-contact-1440.png) | [View](evidence/screens/after-contact-1440.png) | [View](evidence/screens/before-contact-390.png) | [View](evidence/screens/after-contact-390.png) |

![Redesigned Home desktop](evidence/screens/after-home-1440.png)

![Redesigned Contact phone](evidence/screens/after-contact-390.png)

## Why only some earlier pages changed

A read-only audit on 8 October found Home/About LiteSpeed cache hits serving 3.2 styles and Services/Feedback hits serving 3.3; uncached Contact/Assessment served both 3.4 stylesheets. Cache-miss Home/About responses also served 3.4. All six used full-page Grant shortcodes inside Elementor, so updating the templates will apply after cache clearing. Elementor document IDs alone do not mean the pages use native Grant containers.

Version 4.0 refreshes generated caches and requests official, URL-scoped LiteSpeed purges once per version. It preserves saved page content and private delivery settings. Manual hosting/CDN clearing and staging review remain necessary.
