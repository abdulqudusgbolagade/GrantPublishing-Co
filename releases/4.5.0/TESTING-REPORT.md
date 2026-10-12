# Grant Publishing Co. 4.5.0 testing report

12 October 2026. This is a focused repair and GRANT Framework integration, preserving the approved website design.

## Delivery status

- **Implemented and tested in isolated WordPress:** the plugin update below, including runtime changes to installed Elementor pages without rewriting their saved content.
- **Prepared, not published:** two complete authority article drafts. Installation prepares editable WordPress drafts under the existing Insights parent. They do not appear in the public index or sitemap until the owner publishes them.
- **Live testing completed:** the current 4.4.0 site was inspected, including all 26 core URLs and 55 browser/viewport combinations. Exactly one labelled owner test was submitted through each live form. Both returned HTTP 200, a successful browser state and Web3Forms acceptance for delivery. **Inbox receipt is unverified.**
- **Production installation remains pending:** this environment has no authenticated production WordPress administrator, hosting deployment connection, mailbox or Search Console connection. Publishing source and the ZIP to GitHub does not install WordPress changes. No production SEO settings or Search Console connections were changed.

## Important changes

| Area | Change |
| --- | --- |
| Homepage | Integrated the five GRANT stages into the existing `#process` section. Kept its location, heading hierarchy and three-column arrangement, grouping G/R, A, and N/T. Hero and surrounding sections retained. |
| About | Expanded the existing approach section with the method and its scope. Added `/about/#grant-framework`; no standalone framework page or extra navigation item. Founder identity and photograph retained. |
| Ten services | Added concise, relevant framework connections inside the existing service journey/proof component. No suggestion that every engagement includes all five stages. |
| Assessment | Explained the public Amazon-visible initial diagnostic and wider framework connection. No extra required fields, private-data access or performance promise. |
| Case studies | Luma connects only to presentation through cover redesign and interior formatting. My Dear Grandfather connects to positioning and listing presentation; its isolated later ranking record is expressly not growth tracking or causal evidence. |
| Four closing enquiry paths | Formatting, Cover Design, Publishing Support and Luma now send unpublished-book visitors to Contact with existing service values selected. Existing primary and sidebar enquiry paths retained. |
| Forms | Added a protected new-tab Privacy Policy link immediately after consent on both forms. Preserved validation, consent, honeypot, delivery configuration, privacy wording and backend handling. |
| Existing Insights | Confirmed AbdulQudus Tella byline linked to About. Original published date is 7 October 2026 for all three, established from production WordPress page records. No fabricated updated date. |
| A+ proof | Added the supplied, publicly approved Kathryn’s Beach A+ screenshot alongside retained My Dear Grandfather proof on Book Descriptions & A+ Content. Correct attribution, restrained scope and full-image link. |
| Default WordPress content | Administrator-only, backed-up cleanup drafts only an exact untouched English starter post and holds its exact sample comment. Excludes/noindexes only associated unused archives; those restrictions stop when genuine published posts exist. No deletion or homepage redirects. |
| Titles | Corrected WordPress/Elementor/Yoast title ownership at the appropriate lifecycle stages. One title per core page without output buffering or manual SEO rewrites. |
| Authority drafts | Prepared a qualified My Dear Grandfather listing analysis and an Amazon Ads readiness article with official sources checked on 12 October 2026. Protected bundled PHP sources return no draft text on direct requests. |

The exact shared stage names are:

1. **G: Ground the book in the right market.**
2. **R: Refine the positioning.**
3. **A: Align the listing and presentation.**
4. **N: Navigate visibility channels.**
5. **T: Track growth and improve performance.**

GRANT is presented as the company’s working method. Tracking depends on agreed scope, baseline, timeframe and available client-authorised data. Ongoing monitoring is not included with every service.

## Title diagnosis and metadata preservation

The live source reproduced two title elements on 18 of 26 pages. There were two interacting causes: Canvas supplied an inline fallback when title support was absent, and WordPress block-template resolution added a core title callback after Yoast’s earlier hook removal. The prior plugin attempted to register title support after `wp_loaded`; WordPress rejects that late call. Earlier local verification had also used Yoast’s forced title rewriting, which masked the duplicate output. That earlier title result was insufficient.

The repair declares support at `after_setup_theme`, then reconciles known title callbacks after template resolution. Yoast remains responsible when installed; a single native fallback remains when needed. Final tests use Twenty Twenty-Five, actual Elementor and Yoast **with forced title rewriting disabled**, including a late-support-removal Canvas fixture. There is no output buffer or regex removal of rendered title tags.

Saved manual Yoast titles, descriptions, focus keyphrases, canonicals and social values are not modified. The isolated installation was seeded with production-visible metadata and distinct focus-keyphrase sentinels. Before/after hashes confirm all 26 existing core pages, saved Elementor data, SEO fields, dates, status, parents, slugs, legal configuration and delivery settings remained unchanged. Twelve separate actual-rendering checks confirm manual Yoast priority, including canonical and social overrides.

Repeated wording is already present in publicly expanded Yoast titles. Raw production fields require authenticated inspection to distinguish literal text from a `%%title%%` template prefix. [Six exact proposed corrections](TITLE-REVIEW.md) are supplied for review; **none was silently applied**. Tools → Grant Website Setup now shows saved manual titles privately to an administrator.

## Links, pages and conversion

No new public page or slug is introduced in this release. All 26 existing core pages remain. The ten service cards, established internal linking, case-study hub, FAQ, legal pages and routes were already working and retained. No new redirect is created or modified; the existing repair from `/services/connected-catalog/` to `/insights/connected-catalog/` is retained.

New contextual links use “GRANT Framework” or “Explore the GRANT Framework” and point to `/about/#grant-framework`. Form links use “Privacy Policy”. The A+ example uses “View the approved A+ example”. These appear where relevant, without adding a competing process page or commercially interlinking legal copy.

| Closing link | Actual Contact selection |
| --- | --- |
| Discuss book formatting | `request=project`, `service=Book formatting` |
| Discuss cover design | `request=project`, `service=Cover design` |
| Discuss publishing support | `request=project`, `service=Book publishing and Amazon KDP setup` |
| Luma: Discuss cover design / Discuss book formatting | The two corresponding existing selections above |

All append `#gpc-enquiry`. An Amazon link remains optional. Relevant published-book visibility and marketing CTAs continue to use `/book-marketing-audit/`.

The two **future URLs, still drafts**, are:

- `/insights/my-dear-grandfather-listing-analysis/`
- `/insights/amazon-ads-readiness/`

Complete review copies: [listing analysis](drafts/listing-analysis.md) and [Ads readiness](drafts/ads-readiness.md). Matching HTML copies are supplied. The creator preserves existing drafts, edits, status and manual SEO. Once the owner publishes a marked draft, it can join the existing Insights index using the current row treatment. No public commercial page was found orphaned in the scoped final crawl. The two unpublished drafts are intentionally unlinked publicly.

## Evidence and claims

- **My Dear Grandfather:** existing seven-page case-study PDF and its documented listing audit, optimisation research, description excerpts, action plan and qualified screenshots. Seven researched keyword phrases are recommendations unless saved backend fields are evidenced. Different editions and an undated later paperback ranking snapshot are not a controlled before/after experiment.
- **Luma:** supplied case-study PDF and faithful final artwork. Actual scope remains cover redesign, 8.5 × 8.5-inch interior formatting, 35 pages and paperback print-PDF delivery on 8 October 2026. No KDP upload, publication, metadata work, Kindle conversion, supplied-illustration authorship or commercial outcome is added.
- **Kathryn’s Beach:** user-supplied 600 × 606 PNG screenshot, explicitly approved for public display on 12 October. It shows A+ presentation, not evidence of Amazon approval, upload date, sales or advertising results. It is a screenshot, not separate high-resolution module deliverables. Related titles within the screenshot are not claimed as additional Grant projects.
- **Official rules:** [KDP keyword guidance](https://kdp.amazon.com/en_US/help/topic/G201298500) and [Amazon Sponsored Products](https://advertising.amazon.com/solutions/products/sponsored-products), checked on 12 October. Account, book and marketplace eligibility still require current checking before campaign launch.

No scientifically validated, certified, guaranteed or sales-proven framework claim was introduced. No new advertising, launch, author-platform or catalog case study was invented. Five testimonials remain intact without duplication. Both original PDFs remain available.

## Browser, responsive and accessibility results

Actual isolated stack: WordPress 7.0.7, Elementor 4.3.4, Yoast 28.6, Twenty Twenty-Five, PHP 8.5.10, SQLite, official Playground CLI 3.1.57 and Chromium/Playwright with axe-core. This is not the production database. TLS verification remains enabled; curl’s trusted CA handles the cloud browser’s proxy-certificate limitation.

- **26 pages × eight widths = 208 combinations:** 1440, 1280, 1024, 768, 480, 430, 390 and 360 px. No horizontal page overflow, broken loaded images, duplicate H1s, page exceptions or console errors in the final local results. No detected WCAG 2 A/AA or 2.1 AA axe violations within Grant’s page shell. Automated results are not a certification of every accessibility requirement.
- Initial audit found the new framework button label dark against indigo at five widths. Fixed using the existing paper-text token; five-width targeted rerun passed. The initial failure remains in evidence rather than being hidden.
- New A+ image link was intercepted by Elementor’s default lightbox. Disabled that intercept specifically for this link, retaining the intended protected new-tab full-image action.
- **1,230 isolated PHP assertions** pass across 7.4/8.2/8.3; 21 bundled PHP files pass 42 syntax checks on 7.4/8.2. The frozen cloud installer also executed successfully with all seven suites (410 assertions) on 8.2, plus enquiry JS and package checks.
- **36 existing form/navigation checks, 43 FAQ/journey checks, 45 carousel/PDF checks, 77 framework/enquiry/privacy/draft checks and 12 manual Yoast checks** passed. Dedicated normal/hover/focus contrast checks passed for the new framework button.
- Existing form flow checks cover empty/consent validation, optional URL, rejection, confirmed acceptance, timeout uncertainty, preserved fields, re-enabled button, duplicate uncertain-request handling and service selection.
- Navigation, FAQ, complete Insight → service → case study → assessment journey and no-JavaScript FAQ/work cards were exercised in the browser.
- Carousel checks cover five-second timing, deferred covers, unchanged reserved dimensions, accurate captions, hover/focus pause, explicit pause/play, arrows/Home/End/indicators, touch, reduced motion, inactive-slide focus exclusion and useful static fallback.
- Framework links, sticky-header anchor clearance, four closing enquiry paths, privacy touch targets/new tabs, accurate bylines, retained proof and private/authenticated draft access were checked separately.

Desktop/mobile screenshots are in [the gallery](GALLERY.md). They are evidence of the local build or explicitly labelled live baseline, not a live deployment preview.

## Performance and preservation

Measured the supplied A+ PNG at **257,684 bytes**, then encoded the same 600 × 606 RGBA pixels as lossless WebP at **153,192 bytes**, a **40.55% reduction**. Pixel equality was verified. The image has explicit dimensions and lazy loading. Existing important artwork was not recompressed; 43 pre-existing non-CSS asset files remain byte-identical. No animation library, extra script or font was added for the framework/proof update.

Live baseline HTML sizes and curl request timings were recorded before editing. They are cloud request samples, not Core Web Vitals or a production speed score. No unmeasured claim of faster overall page loading is made.

Both legal templates and installed legal copy are preserved exactly. Header/footer/favicon/logos, service URLs, approved hero copy, founder image, existing forms and existing case-study PDFs were retained.

## Remaining limits and owner actions

1. Back up production files/database, install the ZIP through the existing WordPress plugin replacement workflow, visit the dashboard and review Tools → Grant Website Setup. The administrator visit performs scoped starter cleanup and draft preparation. Clear relevant caches and verify the live checklist in [installation instructions](INSTALLATION.md). No bulk Elementor conversion is needed.
2. Confirm receipt of the two **OWNER DELIVERY TEST ONLY** messages sent at approximately **18:09 WAT on 12 October 2026**, one Contact and one Assessment. Provider acceptance is established; inbox receipt is not.
3. Review the six proposed title-wording corrections against raw Yoast fields. Review the two complete articles and their intended bylines before publication. These are not blocked by missing source assets.
4. The existing public Hostinger/brand-agent injection requests an external translation JSON that returned **404** from this cloud environment. Live audit console also contains deliberately blocked analytics POSTs. No Grant JavaScript exception or visible floating-widget obstruction was reproduced in the checked layouts. Production third-party widget settings/loading and Search Console health cannot be fully verified without authorised access. No reconnection or guessed configuration was attempted.

There is no missing asset request for this implementation. Higher-resolution separate A+ modules would be optional future material, not a condition of this release.

## Cloud environment setup

The reusable frozen `install_script` and complete `start_skill` were saved using cloud-environment-onboarding:setup. The updated installer and isolated WordPress restart were exercised successfully, and retained dependencies/files were restored during this continuation. Network policy and credential bindings were preserved. This is a saved configuration draft requiring environment publication through the product; it is not a claim that the new configuration was published or WordPress deployed.

## Backup and rollback

Source rollback point: `0104c252c66839976bf0158f1e8fc0ad0b546746`, tag `backup/framework-audit-2026-10-12`. The previous [4.4.0 ZIP](../4.4.0/grant-publishing-site-4.4.0.zip) remains available.

This does not substitute for a production database/file backup before installation. The scoped default cleanup records its original post fields and sample-comment status privately before mutation. Two new drafts remain editable and unpublished. Replacing the plugin with 4.4.0 rolls back runtime presentation/code; it does not automatically republish the starter post, delete drafts or undo database changes. Restore the specific database records from the pre-install backup if that is necessary. Do not restore or overwrite unrelated new enquiries, project settings or authored content.

## Files and retained evidence

Changed implementation files are listed in [changed-plugin-files.json](evidence/changed-plugin-files.json). Main groups: version/form markup; `pages.php`, `journey.php`, `setup.php`; new shared framework, production-audit and draft modules; four template/native-JSON pairs; scoped design CSS; approved lossless A+ asset; two protected draft sources. Developer QA checks and release documentation were also updated.

Evidence includes the full crawl, original and corrected browser results, final browser matrix, preservation hashes, manual Yoast checks, sitemap/default-content results, interaction checks, public live baseline, actual owner-form responses, source provenance, package checksum and reproduction runners. QA endpoints, test users, provider interception, dependency trees, databases and reports are **outside** the installable ZIP. No real Web3Forms key or production credential is included.
