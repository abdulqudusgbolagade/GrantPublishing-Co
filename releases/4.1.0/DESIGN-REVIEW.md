# Grant Publishing Co. 4.1 design review

Prepared 9 October 2026. The supplied Brand Guidelines v1.0 now define the identity across the existing 17-page redesign. The user's approved headline, genuine new covers and confirmed author/publisher details complete the update. [Implementation](../../docs/BRAND-IMPLEMENTATION.md) records source assets and decisions.

The header uses the current glossy icon with protected clear space and an accessible company name. The footer uses the supplied full reverse artwork, without reconstructing its wordmark. Midnight and indigo give the site a stronger identity; porcelain and white keep content spacious. Cobalt/violet supply controlled accents. Cormorant Garamond Medium replaces Instrument Serif for display text; Manrope remains for body/UI.

Feedback presents the real Luma and Grandfather covers next to their exact existing reviews. The new Grandfather attribution distinguishes Barsha Rai, the author, from Nadine Laman, Cactus Rain's publisher/owner. Clickable covers open the confirmed Amazon or LinkedIn destination. The WordPress site icon remains unchanged.

Testing found insufficient contrast for the deck's lighter neutral on pale violet surfaces. The deck's darker neutral now handles secondary text. A menu resize focus issue was also corrected so keyboard focus returns to the brand link when the menu becomes hidden.

## Before and after

Before: published 4.0.0 blue/cream/ink local fixtures. After: final 4.1.0 guidelines-based local fixtures. Font files are mirrored from official Google Fonts sources with verified TLS. These are prepared previews, not a live installation.

| Page | Before desktop | After desktop | Before phone | After phone |
|---|---|---|---|---|
| Home | [View](evidence/screens/before-home-1440.png) | [View](evidence/screens/after-home-1440.png) | [View](evidence/screens/before-home-390.png) | [View](evidence/screens/after-home-390.png) |
| About | [View](evidence/screens/before-about-1440.png) | [View](evidence/screens/after-about-1440.png) | [View](evidence/screens/before-about-390.png) | [View](evidence/screens/after-about-390.png) |
| Services | [View](evidence/screens/before-services-1440.png) | [View](evidence/screens/after-services-1440.png) | [View](evidence/screens/before-services-390.png) | [View](evidence/screens/after-services-390.png) |
| Contact | [View](evidence/screens/before-contact-1440.png) | [View](evidence/screens/after-contact-1440.png) | [View](evidence/screens/before-contact-390.png) | [View](evidence/screens/after-contact-390.png) |

![Home desktop](evidence/screens/after-home-1440.png)

![Feedback phone](evidence/screens/after-feedback-390.png)

## Installation

Updating preserves saved Elementor edits and delivery settings. Shared branding applies to existing native pages; missing reviews/covers are added in frontend rendering without changing stored layouts. Old custom native headlines remain saved. The live pages previously audited used Grant shortcodes inside Elementor and therefore receive updated templates after cache clearing. Confirm both 4.1 stylesheets on staging, preserve the site icon and verify inbox receipt yourself.
