# Grant Publishing Co. 4.4.0: internal linking, documented work and conversion

Prepared October 10, 2026. **Implementation and the installable plugin are complete; production installation is pending.** The public website was inspected read-only. Tests used an isolated WordPress installation, not the live database. No real enquiry was sent.

Source backup: commit `077d7065dd316e2f5ee4ffe486bdac70046e4ac5`, tag `backup/linking-case-studies-2026-10-10`. This is a source checkpoint, not a live database backup.

## 1. Pages created

| Page | Exact intended production URL |
| --- | --- |
| Case Studies | https://grantpublishingco.com/case-studies/ |
| My Dear Grandfather | https://grantpublishingco.com/case-studies/my-dear-grandfather/ |
| Luma the Sleepy Star | https://grantpublishingco.com/case-studies/luma-the-sleepy-star/ |
| Frequently Asked Questions | https://grantpublishingco.com/faq/ |

`/case-studies/` fits the existing descriptive architecture. A second `/work/` hub was not created; the pre-existing legacy `/work/` alias remains unchanged. Case Studies and FAQs join the footer’s existing Explore links, keeping the approved header navigation uncluttered.

A scoped administrator-only setup creates the four missing pages in parent-first order. It observes capability checks, a concurrency lock, stale-lock recovery, a completion marker and a nonce-protected retry button. Existing drafts, unrelated content, occupied URLs and custom slugs are preserved for review. Public requests do not create pages.

## 2. Pages modified

Render-time additions affect the body of all ten service pages, the three Insights articles, About, Services and Client Feedback: **16 existing pages**. Existing saved page content and Elementor data are preserved. The shared footer adds the two support links across connected pages. Home’s body and structure, Contact, Free Book Assessment, Insights index and legal-page bodies remain unchanged.

All **22 existing page definitions, HTML templates and bundled Elementor layouts are byte-identical** to the backup. The test migration preserved existing saved content, layouts, Yoast fields, templates, page status/routes and selected form/contact/legal settings. Four native new-page conversions were also tested without changing the existing 22 saved pages.

## 3. Internal links added

The added architecture follows **Insights → relevant service → documented proof → Free Book Assessment**. The three priority articles now connect to their requested service groups. Every commercial service has contextual connections to complementary services, a body assessment CTA and a FAQ route. Relevant services also include project proof or a related Insight.

The crawl recorded **73 contextual body links added to existing pages**, including proof, practical service connections and next steps. The [complete inventory](LINK-INVENTORY.md) lists each source, destination and anchor, including links on the four new pages. Links are grouped around the discussion rather than inserted into every paragraph.

No existing internal page or anchor failure was found in the final implemented-page crawl. All ten service cards retain their working URLs. Contact CTAs are preserved without adding repetitive Contact links. Legal-page bodies receive no commercial interlinking.

## 4. Anchor text used

Descriptive anchors include **Amazon Book Visibility service**, **Book Marketing Strategy**, **Book Descriptions & A+ Content**, **Book Cover Design**, **Book Formatting**, **Publishing Support**, **Amazon Ads Setup & Management**, **Book Launch & Promotion**, **Author Platform**, **View My Dear Grandfather case study**, **View Luma the Sleepy Star case study**, **Request a free book assessment**, **documented client projects** and **Read all frequently asked questions**.

Existing generic Insights “Explore the relevant service” button text now names its service. Existing article prose is not rewritten. Where saved Elementor prose differs from the known paragraph, a separate related note supplies the connections while preserving the edited wording. Duplicate guards prevent repeated enhancements. Exact anchors and destinations are in [LINK-INVENTORY.md](LINK-INVENTORY.md).

## 5. Case studies created

Both HTML pages include introduction, project context, challenge, observations, decisions, completed work, available evidence, observable outcomes, limitations, relevant services, the original PDF and an assessment CTA. The hub uses a reusable verified-project data source and two editorial cards with actual book images, concise problems and clearly labelled scope.

**My Dear Grandfather** covers description work, keyword research and category review for Barsha Rai’s novel, commissioned by Cactus Rain Publishing. Its supplied cover is explicitly identified as outside this listing scope. The earlier Kindle screenshot and later paperback record are not represented as a matched comparison.

**Luma the Sleepy Star** covers John Capon’s cover redesign and 35-page paperback interior. The earlier cover direction, final cover, full wrap and interior spread illustrate the actual work. Its 8.5 × 8.5-inch trim, final page count, print package and 8 October 2026 delivery are stated. KDP was the intended platform; the project ended at print-file delivery.

## 6. Source evidence used

| Project | Verified source and evidence |
| --- | --- |
| My Dear Grandfather | Supplied seven-page listing case-study PDF, existing hosted PDF URL, supplied cover; page 2 original Kindle screenshot, page 3 attributed/reproduced description excerpts, page 4 keyword research, page 5 recommendations versus final visible categories, page 6 qualified paperback ranking record and source limitations. |
| Luma the Sleepy Star | Supplied seven-page project PDF, existing final-cover/wrap renditions; page 2 earlier/final presentation, page 3 full wrap, page 4 interior spread, remaining delivery/specification and scope records. Existing John Capon review remains on Client Feedback. |

Six additional WebP renditions were extracted faithfully from those PDFs: the original Kindle screenshot, two keyword-table sizes, the earlier Luma cover and two interior-spread sizes. No replacement artwork, book title, illustration or project was generated. All **34 pre-existing visual/PDF assets remain byte-identical**.

The hosted Grandfather PDF was re-downloaded with verified TLS: **736,855 bytes, seven pages**, SHA-256 `ba5afde8390d72b687306a09481d5b0256f8f0f6746b4b69723b6e52c4c46067`. The unchanged Luma PDF is **2,562,805 bytes, seven pages**, SHA-256 `f2bdb2dcf620a6784de1b0f213d1f04663c84df60b337a76383af9bf25b0bc5b`. Displayed sizes derive from those actual byte counts. Both existing PDF links and their protected new-tab behaviour remain.

## 7. Proof sections added

| Service | Evidence shown |
| --- | --- |
| Amazon Book Visibility | My Dear Grandfather listing case study |
| Book Descriptions & A+ Content | My Dear Grandfather, with description/research scope labelled; no claim that A+ Content was delivered |
| Book Marketing Strategy | My Dear Grandfather’s diagnosis and listing research |
| Book Formatting | Luma’s delivered interior and cover package |
| Book Cover Design | Luma’s documented cover redesign |
| Publishing Support | Luma’s prepared files, explicitly stating that KDP upload and publication were not included |

Amazon Ads, launch, author-platform and catalog pages use appropriate service/Insight connections. Unsupported advertising or launch results are not presented as proof. Client Feedback keeps both PDF features and all five testimonials, adding two discreet online-case-study links without duplicating the reviews.

## 8. FAQ sections added

The central page answers all thirteen requested questions: guarantees, fiction/nonfiction, unpublished books, preliminary assessment scope, materials, KDP access, recurring Ads management, separate ad spend, revisions, Kindle/print formats, published books, schedules and international enquiries.

Two relevant questions also appear on Visibility, Formatting, Cover Design, Publishing Support and Amazon Ads. Answers are drawn from the existing service scope and approved arrangements. Fees, revisions and timelines are agreed in the proposal; no arbitrary turnaround or revision count is invented.

FAQs use semantic native `details`/`summary`, work without JavaScript and require no animation or accordion library. No competing FAQ schema is added; Yoast remains responsible for site SEO.

## 9. Conversion improvements

About explains publication versus discoverability, the brand line, the role of design/publishing/marketing, evidence-led decisions and why advertising is not the starting answer to every problem. AbdulQudus Tella remains a Book Marketing Strategist; the existing founder section/photo stays in place.

Services offers routes to documented work and practical FAQs. Each service has a clear assessment next step. Case studies link to relevant services and the existing assessment form. Existing Insights assessment/contact CTAs remain. A browser test followed the complete article → Visibility → Grandfather → assessment journey and Formatting → Luma journey.

The approved free-assessment disclaimer and form privacy wording remain. Unpublished authors can use general project enquiries without an Amazon URL. No automatic marketing subscription is added.

## 10. Accessibility changes

New pages have one H1, semantic sections and meaningful evidence captions/alt text. FAQs support Enter, Space and Tab through native controls. New actions reuse visible focus styles, proper links and existing button sizing. Evidence remains explained as webpage text, rather than relying exclusively on images/PDFs.

The final responsive scans reported no axe findings for WCAG 2 A/AA and 2.1 AA tags. Keyboard and no-JavaScript FAQ/navigation paths passed. Existing skip links, menu focus/Escape behaviour, form labels, required/consent checks and protected new-tab PDF links are retained.

Automated scans are not a certification or a substitute for a full screen-reader review.

## 11. Responsive and performance changes

The new work/proof grids stack at the existing responsive breakpoints. Covers retain proportions, descriptions and buttons wrap, fact tables become a readable single column, and FAQs keep usable targets. New page evidence uses the approved paper/lilac modes without changing existing page backgrounds. A service-FAQ heading spacing conflict was corrected.

Images have intrinsic dimensions, responsive sources where useful, asynchronous decoding and lazy loading. Original covers and title content are uncropped. The six extracted renditions total **616,252 bytes**; the original PDFs retain full evidence. No new JavaScript, animation library, font family or tracking code is introduced. The existing carousel’s reduced-motion behaviour is preserved. No synthetic speed score or Core Web Vitals improvement is claimed.

## 12. Claims intentionally excluded

- Grandfather: no proven sales increase, optimisation-caused ranking improvement, final Kindle category selection, confirmed saved backend keyword fields, measured search volumes, cover-design work or advertising return.
- The later paperback rank snapshot has no supplied date. Kindle and paperback observations are not a matched before/after test; visible categories differ from the research recommendations.
- Reader Views review wording is attributed, rather than claimed as original project prose.
- Luma: no KDP upload, publication, metadata optimisation, Kindle conversion, platform approval, sales/ranking result or claim that Grant created the supplied story illustrations.
- No fake projects, team scale, awards, experience figures, client statistics or guaranteed outcomes.

## 13. Content, assets and settings still needed

**No further project assets, brand assets or legal copy are required.** The PDFs and supplied images provided enough evidence for both case studies.

An authorised WordPress administrator must install the plugin, review the scoped setup, clear production caches and verify live inbox delivery. Any existing draft or route collision reported by setup needs specific review before retrying. No production administrator connection was available in this session.

## 14. Orphan pages

The complete rendered-link graph found **no orphan page among the 26 connected pages** and **no orphan commercial service**. All ten services have incoming body links from Services and an assessment CTA. Project pages have hub, service and Feedback links; Case Studies and FAQs also have footer access.

## 15. URLs and redirects

The four exact new URLs are listed in section 1. No existing slug or URL is changed and no new redirect is required. Existing explicit permanent redirects remain:

- `/services/connected-catalog/` → `/insights/connected-catalog/`
- `/services/book-discovery/` → `/insights/book-discovery/`
- `/services/book-product-page/` → `/insights/book-product-page/`

Editorial pages remain within Insights; services remain within Services. Existing legacy aliases are preserved.

## 16. Testing results and limits

| Check | Result |
| --- | --- |
| Isolated WordPress page creation | Four requested pages, repeat-safe; existing saved content, layouts, Yoast fields and selected settings preserved |
| Existing source preservation | 22 definitions/template/layout pairs and 34 visual/PDF assets unchanged; form provider module, enquiry JS and approved legal sources unchanged |
| Actual WordPress responsive/accessibility audit | 26 pages × seven widths = **182 combinations passed** at 1440, 1280, 1024, 768, 480, 390 and 360px |
| New native Elementor layouts | All four converted and checked at seven widths: **28 combinations passed**, existing 22 saved pages unchanged |
| Final focused render checks | New pages plus Visibility and Ads rechecked after spacing/template refinements; image candidates awaited and decoded |
| Full route/anchor/legal crawl | 26 routes and **1,121 named link/anchor checks**, all ten service cards, no known internal 404 or broken anchor; exact legal paragraphs and five unchanged testimonials |
| Yoast sitemap | Local sitemap index and page sitemap return 200; all four new pages and existing non-home Grant pages included |
| Console/resource replay | All 26 final WordPress HTML captures executed with normal local assets and verified HTTPS fonts; no console errors, page exceptions or failed requests |
| Manual Yoast priority | **12 checks passed**: title, description, canonical, focus keyphrase, Open Graph/Twitter fields and late Canvas title-support conflict; temporary test metadata restored |
| PHP suites | **349 assertions × PHP 7.4, 8.2 and 8.3 = 1,047 passed** |
| PHP syntax | All 15 runtime files checked on PHP 7.4 and 8.2; final template adjustment rechecked |
| Static source | 26 matching HTML/native pairs, 1,336 native elements, route/asset/heading/ID/anchor/markup checks passed |
| Enquiry JavaScript | Success, rejection, connection failure, malformed response, invalid input and service/request selection passed |
| Real WordPress form/navigation regression | **36 checks passed**, provider calls and mail intercepted locally; no real messages sent |
| FAQ/conversion flows | **43 checks passed**, including Enter/Space/Tab, no-JavaScript operation and end-to-end paths |
| ZIP | 122 files under one plugin root; CRC and source-byte equality passed; no QA helpers, databases or real key included |

Integration used WordPress **7.0.7**, Elementor **4.3.4**, Yoast **28.6**, PHP **8.5.10**, SQLite and official Playground CLI **3.1.57**. Existing pages were tested through actual WordPress/Elementor output; new pages were tested in both shortcode and native forms.

Yoast is the source of truth. The prior unconditional plugin title override is removed. Explicit manual descriptions and canonicals are preserved, including previous stock wording. New-page missing descriptions have fallbacks, including social text, without writing manual Yoast fields. A late Canvas compatibility check prevents its fallback title from duplicating Yoast’s tag. No focus keyphrase or manual social metadata is overwritten.

**Verification limits:** installation, live administrator/editor review, hosting/CDN invalidation, production database migration, production sitemap refresh and actual inbox receipt remain unverified. The local provider is a fake-key interception, so an accepted response does not establish live delivery. Chromium was used; Safari/Firefox, physical devices, full screen-reader testing and search-engine indexation are not established. Supplied third-party profile/retailer URLs remain, but unrestricted reachability behind login or anti-bot protections is not claimed.

## 17. Files changed

Runtime/source:

- `grant-publishing-site/grant-publishing-site.php`: version 4.4.0 and load three scoped modules.
- `includes/case-studies.php`: verified project data/cards, four-page creation, retry guards and new-page social-text fallback.
- `includes/faq.php`: thirteen concise questions and reusable native disclosures.
- `includes/journey.php`: contextual article/service links, six proof sections, About rationale and Feedback HTML-case links; public-context and duplicate guards.
- `includes/pages.php`: footer links, new component tokens, dynamic shortcode handling and manual-Yoast/Canvas compatibility.
- `includes/full-page.php`: page-specific class only on the four new native pages, allowing their scoped evidence backgrounds.
- `includes/setup.php`: results and nonce-protected four-page retry control.
- `pages.json`: four new definitions; existing 22 unchanged.
- `templates/{case-studies,case-grandfather,case-luma,faq}.html` and matching `elementor/*.json`: four new page pairs.
- `assets/design.css`: scoped work/proof/evidence/FAQ styles, existing tokens and responsive rules.
- `assets/grandfather-original-kindle-767.webp`, `grandfather-keyword-research-{720,1103}.webp`, `luma-cover-before-674.webp`, `luma-interior-spread-{720,1400}.webp`: faithful PDF evidence renditions.

Development/delivery: `qa/check-package.py`, `qa/sync-layouts.py`, `qa/test-structure.php`, new `qa/test-journey.php`, `qa/README.md`, repository `README.md`, and the versioned release ZIP, report, link inventory, installation guide, screenshots, checksums and sanitised evidence. QA endpoints and provider-interception code stay outside the installable plugin.

## 18. Owner’s live verification checklist

1. Back up files/database, install the 4.4.0 ZIP, visit the dashboard and read **Tools → Grant Website Setup → Case Studies and FAQs**. Resolve any reported conflict and clear caches. Do not bulk-convert existing Elementor pages.
2. Open all four new URLs on desktop and phone. Confirm both hub cards, uncropped covers, original/final evidence images, concise limitations, original PDF buttons and assessment actions.
3. Follow each priority Insight into its service. Follow Visibility to Grandfather and Formatting/Cover Design to Luma, then to assessment. Open all ten Services cards and inspect footer Case Studies/FAQ access.
4. Use Tab, Enter and Space on FAQs; open and close the mobile menu with Escape. Check readable text, wrapped buttons, stacked cards and absence of horizontal scrolling on phone/tablet.
5. Confirm five existing testimonials, both approved legal pages, founder photograph, logos and favicon are unchanged. Review manual Yoast title/description/canonical/social settings and `/sitemap_index.xml` plus its page sitemap after cache refresh.
6. Send one labelled test through each form, including an unpublished-book enquiry without an Amazon URL. Confirm actual Web3Forms inbox receipt and check spam/provider logs if absent. Do not repeatedly resend a submission whose delivery is unconfirmed.

See [installation/rollback](INSTALLATION.md), [link inventory](LINK-INVENTORY.md), [screenshots](GALLERY.md) and [sanitised evidence](evidence/).
