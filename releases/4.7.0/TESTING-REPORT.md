# Grant Publishing Co. 4.7.0 review report

Prepared 29 October 2026. **Review build only. No production deployment, production database change or new real enquiry email was made.**

[Installable ZIP](grant-publishing-site-4.7.0.zip) · [Before/after gallery](GALLERY.md) · [Installation and rollback](INSTALLATION.md) · [Analytics setup](ANALYTICS.md)

## Important changes

- Preserved WordPress/Elementor, existing URLs, logos, favicon assets, manual Yoast settings, original project evidence, five Client Feedback testimonials, both PDFs, approved legal text and server-side enquiry delivery.
- Refined the existing navy, blue, violet, lilac and ivory identity through shared spacing, editorial typography, buttons, image frames, service briefs, proof cards and responsive layouts. No replacement template, generated book art or animation library was introduced.
- Made Our Work directly accessible from desktop/mobile navigation at the existing `/case-studies/` URL. Retained the compact header mark and full footer logo. Added a restrained sticky-header scroll state and organised the footer links into two columns.
- Updated the homepage hero and project actions, placed three genuine work examples directly after it, introduced four service categories, moved the two existing verbatim reviews after services, and presented the five exact GRANT stages individually. Updated the founder's authorised positioning and balanced the authentic portrait and heading.
- Preserved the homepage's eleven-slide book/interior showcase, including all eight supplied formatting views. Home still has no pause button: arrow browsing or keyboard focus stops rotation, hover pauses it temporarily, and reduced motion disables automatic rotation. The existing Services showcase retains its pause/play control.
- Expanded Our Work with the approved Kathryn's Beach A+ example and a clearly labelled interior-sample feature. Kept the two existing HTML case studies and their qualifications. Added no placeholder projects, fake results or new publication claims.
- Replaced five shipped service-hero concept panels with relevant real formatting, cover-wrap, print-interior, keyword-research and A+ assets. Retained the remaining useful explanatory panels where no genuine campaign/project visual exists. Added or retained a service-specific enquiry path and two concise existing FAQs on every service page.
- Added optional enquiry-stage selection and relevant manuscript, published-book and publisher fields. General enquiries remain possible. Name, email, message and consent remain required; book links remain optional. Preserved validation, nonce, honeypot, rate limits, duplicate handling, uncertainty messages and retained input after errors. Both configured delivery paths receive the validated optional context.
- Added consent-gated support for CTA/proof and accepted-enquiry events using an existing Google tag. Added allowlisted case-study enquiry attribution. Installed no analytics account, tracking ID, cookie or marketing subscription.
- Hosted the existing font families locally with their OFL licences, preloaded critical fonts, delivered one minified CSS bundle, used an optimised derivative of the existing Luma review image, and added responsive evidence-image candidates. Preserved original artwork.
- Omitted Elementor frontend JavaScript only on recognised static Grant full-page/Canvas layouts. Interactive/unknown widgets, effects, lightboxes, other templates and the editor/preview retain it. Grant's forms, navigation and carousel remain independent.

## Pages and URLs

No new public pages, slugs or redirects were needed in this release.

| Page group | Changes |
| --- | --- |
| `/` | Hero, selected work, four service categories, proof order, five-stage framework, founder presentation, final CTA and shared styling |
| `/case-studies/` | Visible heading “Our Work”, improved existing case cards, approved A+ example and formatting-sample feature |
| `/case-studies/my-dear-grandfather/` | Shared visual improvements and assessment-source attribution; evidence/copy/PDF retained |
| `/case-studies/luma-the-sleepy-star/` | Shared visual improvements and project-source attribution; evidence/copy/PDF retained |
| `/services/` and all ten existing service URLs | Shared hierarchy, proof presentation, scoped FAQs and service-specific enquiries; five real hero visuals |
| `/about/` | Authorised founder title, five individually readable framework stages and shared presentation |
| `/contact/`, `/book-marketing-audit/` | Optional conditional context, delivery payload, recovery/reset behaviour and privacy-preserving event support |
| `/client-feedback/` | Shared styling and readable HTML case-study links on dark panels; all five original reviews and both PDF features retained |
| `/insights/` and all three existing articles | Shared typography/navigation/footer; existing authorship and contextual journeys retained |
| `/faq/` | Shared styles and native keyboard-accessible disclosures retained |
| `/privacy-policy/`, `/terms-of-service/` | Shared shell/fonts/styles only; approved legal wording unchanged |

The working service routes remain `amazon-visibility`, `book-presentation`, `launch-promotion`, `author-platform`, `catalog-strategy`, `book-strategy`, `publishing-support`, `book-formatting`, `cover-design` and `amazon-ads`, each under `/services/`.

The existing permanent redirect `/services/connected-catalog/` → `/insights/connected-catalog/` still returns 301. No new redirect or permalink rewrite was added. Established aliases were retained.

## Internal linking and conversion

The existing Insights → service → evidence → assessment journey remains intact. New contextual links include:

| Anchor | Destination |
| --- | --- |
| Explore our work / Our Work | `/case-studies/` |
| Explore the Luma project | `/case-studies/luma-the-sleepy-star/` |
| Explore the listing project | `/case-studies/my-dear-grandfather/` |
| View the A+ Content example | `/services/book-presentation/#kathryns-aplus-example` |
| Explore the formatting samples | `/services/book-formatting/#formatting-samples` |
| Book formatting / Cover design | Existing individual service URLs |
| Publishing support / Launch support | Existing individual service URLs |
| Amazon visibility / Descriptions & A+ Content | Existing individual service URLs |
| Marketing strategy / Amazon Ads | Existing individual service URLs |
| Discuss book formatting, cover design, Amazon Ads and other service-specific actions | `/contact/` with the existing allowed service preselected |
| Request a free book assessment from My Dear Grandfather | Existing assessment URL with a validated `grandfather` source |
| Explore the GRANT Framework | `/about/#grant-framework` |

All ten commercial pages remain linked from Services and have a next step. No orphan commercial page was found. Legal pages were not commercially interlinked. Portfolio enquiry context never changes the canonical page URL.

## Evidence and claims

- **My Dear Grandfather:** existing listing PDF, original Kindle capture, research screenshot and book cover. Barsha Rai is the author; Cactus Rain Publishing is the publisher. Description work, keyword research and category recommendations are distinguished from later Amazon-visible observations. No causal sales/ranking claim or cover-design credit was added.
- **Luma the Sleepy Star:** existing seven-page case-study PDF, original and final cover assets, full wrap, interior spread and John Capon's existing review. The documented scope remains cover redesign and 35-page interior formatting, 8.5 × 8.5 inches, paperback print PDFs, delivered 8 October 2026. No KDP upload, publishing, Kindle conversion, metadata work, illustration creation or sales outcome was inferred.
- **Kathryn's Beach:** the owner-supplied A+ screenshot explicitly approved for public display. It illustrates presentation, not measured conversion or sales gains. No new full case study was invented from this single asset.
- **Interior samples:** eight owner-supplied views grouped under The Seventh Binding, The Names She Kept and an open-book formatting mockup. Missing author/publication details are not filled in, and eight views are not described as eight clients.

The five framework stage names remain exactly as approved. Luma illustrates A; My Dear Grandfather illustrates R/A. Neither is described as a complete five-stage engagement or proof of tracked growth. Ongoing tracking remains scope-, baseline-, timeframe- and data-dependent.

## Accessibility and responsive checks

The release was checked in actual Chromium at **360, 390, 768, 1024, 1440 and 1920px**, across all 26 connected pages, plus shortcode rendering and interactive states.

Repairs include:

- Reserved proof-image boxes to prevent a lazy-loaded cover collapsing to zero dimensions.
- Corrected dark-panel case-study link colours and accent contrast on lilac backgrounds.
- Replaced decorative diagonal-arrow glyphs with local SVGs to avoid missing-font boxes.
- Improved hero/founder heading wrapping, mobile spacing, card stacking, form layout and footer columns.
- Preserved native links, buttons and details/summary controls, meaningful alternatives, one H1, visible focus, skip navigation, inactive-slide keyboard exclusion and reduced-motion behaviour.
- Kept newly conditional controls optional, labelled, disabled when irrelevant and available without JavaScript. Invalid optional URLs reveal the disclosure before focus; failures retain input and confirmation receives focus.

| Check | Result |
| --- | --- |
| Actual WordPress page/viewport audit | 26 pages × 6 widths = 156 states, no detected overflow, broken image, duplicate H1, axe A/AA violation or page/console error |
| Maintained shortcode Home | 6 widths, same clean results |
| Every homepage slide | 11 slides × 6 widths = 66 loaded, proportional, non-overlapping states |
| Form/navigation/consent interactions | 50 actual-browser checks passed, no external messages |
| Carousel, gallery, motion, keyboard, touch and no-JavaScript behaviour | 59 actual-browser checks passed |
| Internal crawl | 26 HTTP 200 pages; 1,134 named link/destination/anchor checks; all ten service cards valid |
| PHP regression suites | 453 assertions each on PHP 7.4, 8.2 and 8.3 = 1,359 passed |
| PHP syntax | 23 plugin PHP files on 7.4 and 8.2 = 46 passes |
| JavaScript syntax / delivery fixture | Passed for enquiry, interactions and showcase; fixture success/rejection/network/invalid-input checks passed |
| Bundled layouts / CSS build | 26 template/native pairs, 1,289 native elements, route/asset/anchor checks and deterministic bundle check passed |
| Legal and saved data | Approved legal paragraphs exact; all 26 saved page snapshots and three protected setting hashes match |
| Documents / sitemap / redirect | Both PDFs valid; external Grandfather PDF 736,855 bytes; sitemap 200; old article route 301 to Insights |

Automated accessibility checks cover the Grant shell with axe's WCAG A/AA rules, alongside practical keyboard/touch/focus checks. They are not a full assistive-technology certification.

## Performance measurements

| Profile / build | Performance | Accessibility | SEO | FCP | LCP | TBT | CLS | Transferred |
| --- | ---: | ---: | ---: | --- | --- | --- | --- | --- |
| Mobile / 4.6.0 baseline | 65 | 100 | 100 | 3.7 s | 5.4 s | 10 ms | 0 | 1,092 KiB |
| Mobile / 4.7.0 review | 79 | 100 | 100 | 2.7 s | 4.5 s | 0 ms | 0 | 721 KiB |
| Desktop / 4.6.0 baseline | 96 | 100 | 100 | 0.8 s | 1.1 s | 0 ms | 0 | 1,092 KiB |
| Desktop / 4.7.0 review | 97 | 100 | 100 | 0.6 s | 1.0 s | 0 ms | 0 | 829 KiB |

These are one before/one final after Lighthouse 12.8.2 lab run per device profile on the same isolated WordPress Playground server, not production field Core Web Vitals. The local server uses PHP.wasm/SQLite, no production CDN or compression, and has substantial server response time. Browser access to remote Google Fonts was limited by its proxy trust configuration in the original baseline; the final Grant fonts are local. Exact scores/timing can vary. Any metric that did not improve is shown rather than omitted.

The review image derivative is **77,860 bytes versus 315,250 bytes**, about 75% smaller, while the original remains available. The CSS build compacts three maintained sheets from **113,860 to 102,537 bytes** and delivers them in one request; this is not a claim that the new visual system has less CSS than 4.6.0. Critical above-fold imagery is not lazily deferred. Later showcase images remain deferred, below-fold images remain lazy, and image dimensions are reserved.

Hosting compression, full-page caching and Site Kit/Hostinger behaviour require live-host verification. Existing third-party integrations were preserved, not silently removed to inflate a score.

## Preservation and technical validation

The pre-edit rollback tag is `backup/premium-upgrade-2026-10-28` at `a588056a3042ae77411966d9f15d4344a7c5f0a5`.

The local before/after snapshot comparison confirms all 26 installed pages retain saved content, Elementor data, Yoast values, modification dates, status, parent and slug. Delivery/contact/legal setting hashes match. Approved Privacy Policy and Terms paragraphs match their approved source exactly. Original logo, favicon, PDF and portfolio files were not replaced.

Manual Yoast title, description, keyphrase, canonical and social metadata remain authoritative. The bundled About fallback description reflects the new founder title only when a manual value is absent. Existing manual wording proposals remain owner decisions. The crawl checked single title/description/canonical/H1 output, and the Yoast sitemap index returned 200.

The ZIP contains **152 plugin files**, **13,683,897 bytes**, with one correct `grant-publishing-site/` root, normal file permissions and the 4.7.0 plugin header. CRC and member SHA-256 checks passed; every archived file matches the tested source. No database, real delivery key, QA endpoint/MU plugin, test installation or Node dependency is included. Actual WordPress ran the same plugin source through a mounted directory; the dashboard ZIP installer itself was not driven.

ZIP SHA-256: `7984a853d9109eef49404a7cb884307061b4014e268673f129091a64aa87b899`.

## Files changed

See [the exact source-file list](FILES-CHANGED.txt) and [plugin file hashes](evidence/plugin-files.json).

Principal changes are `includes/premium.php`, `includes/pages.php`, `includes/framework.php`, `includes/journey.php`, `includes/production-audit.php`, `includes/images.php`, `includes/web3forms.php`, the plugin entry point, `assets/premium.css`, `assets/site.bundle.min.css`, `assets/fonts.css`, `assets/interactions.js`, `assets/enquiry.js`, bundled Home/About/Work templates and their three native JSON layouts. Added font files/licences, one image derivative and CSS build/QA coverage. Other saved pages are enhanced through shared components rather than database rewrites.

## Limits, remaining inputs and approval

- **Production is unchanged.** The latest brief requires approval before deployment. WordPress administrator/hosting access was unavailable; an authorised administrator must install the reviewed ZIP and clear caches after approval.
- Local provider/mail transport was intercepted. No real message was sent. The owner's earlier confirmation that both Web3Forms tests arrived remains recorded in the prior release; this build does not claim a new inbox test.
- Authenticated GA4, Search Console and Clarity settings/report receipt cannot be verified here. Follow [the setup guide](ANALYTICS.md), including consent-manager wiring and conversion deduplication.
- No blocking artwork is missing. Optional future improvements need original high-resolution A+ modules and verified author/genre/publication details for additional interior projects. The current approved screenshot and supplied samples remain useful without inventing those details.
- Real iOS/Android hardware, screen-reader testing and live-host caching/third-party behaviour still require manual checks. No production performance score or ranking improvement is claimed.
- No sales increases, bestseller outcomes, campaign profitability, publication results for file-only projects, fake team members, awards or experience statistics were added.

## Owner's live checklist after approved installation

1. Check the header, Our Work link, hero wording, founder role, book images, four service categories and footer on a phone and desktop in a logged-out/private window.
2. Open all ten services, both HTML case studies, both PDFs, the A+ example and a full-size formatting sample. Confirm the Insights connected-catalog URL stays under `/insights/`.
3. Browse all homepage slides with arrows, keyboard and touch. Confirm manual browsing stops rotation, hover pauses it, reduced motion disables autoplay and no homepage pause button appears.
4. Try each optional enquiry stage and a general enquiry. Confirm fields stay optional and an unpublished book can be discussed without an Amazon link.
5. With current approval, send one labelled Contact enquiry and one Assessment enquiry; check acceptance, the Web3Forms destination inbox/spam and delivered optional details. Do not paste passwords into forms.
6. Check menu opening/closing, Escape/focus, FAQ disclosures, visible focus and no horizontal scrolling on mobile.
7. Verify Privacy/Terms wording, existing Yoast manual metadata/canonicals, social previews, sitemap and cache behaviour.
8. Verify GA4 consent refusal/grant/withdrawal and one accepted-lead event in the existing property. Confirm the same enquiry is not counted twice. Check existing Search Console ownership rather than creating a duplicate property.
