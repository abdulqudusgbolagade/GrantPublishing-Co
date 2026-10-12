# Install Grant Publishing Co. 4.5.0

The GitHub release ZIP is prepared; these changes are not yet installed on production. Use the established WordPress plugin replacement workflow.

1. Take a current **production database and files backup** with the host or your existing backup tool. Keep the previous 4.4.0 plugin ZIP. The source backup tag is `backup/framework-audit-2026-10-12` at `0104c25`.
2. Download **grant-publishing-site-4.5.0.zip**, not the repository ZIP. In WordPress, use **Plugins → Add New → Upload Plugin**, select this ZIP and choose **Replace current with uploaded**. Activate the Grant plugin if necessary.
3. Visit the administrator dashboard, then **Tools → Grant Website Setup**. Check the scoped starter-content cleanup and two authority-draft statuses. An eligible administrator visit prepares them automatically. Existing core pages, Elementor layouts, manual Yoast values and private delivery settings are preserved. Conflicts are reported rather than overwritten; the existing nonce-protected retry controls can be used after reviewing a conflict.
4. Clear WordPress/host/CDN caches through the existing controls. Do **not** bulk-convert, restore or overwrite Elementor pages. Do not reset the Web3Forms key, Yoast settings, favicon or legal copy.
5. Review the two editable **Draft** pages from the setup screen or Pages list. Read the [complete review copies](drafts/). Keep them unpublished until reviewed. Publishing a marked draft can add its row to the existing Insights index.

## Owner live checklist

- Home has one process section showing the five exact GRANT stages; hero/layout remain familiar. The framework button reaches `/about/#grant-framework` without the sticky header hiding the target.
- About, ten service pages, both case studies and Assessment show appropriate framework scope. Luma is design/formatting only; My Dear Grandfather does not claim causal growth.
- Closing CTAs on Formatting, Cover Design, Publishing Support and Luma open Contact with the intended service and `Discuss a project` selected. Unpublished enquiries need no Amazon URL.
- On Contact and Assessment, Privacy Policy appears beside consent and opens the existing policy. Consent and no-automatic-subscription wording remain.
- **Check your inbox and spam folder for the two owner tests already sent on 12 October at approximately 18:09 WAT.** Labels begin `OWNER DELIVERY TEST ONLY: contact form` and `OWNER DELIVERY TEST ONLY: assessment form`. They received successful browser and Web3Forms acceptance responses. Inbox receipt was not accessible here. Do not repeat these tests merely to reproduce this report.
- The three existing Insights display AbdulQudus Tella and 7 October 2026, with no guessed Updated date.
- Book Descriptions & A+ Content retains My Dear Grandfather proof and adds the approved Kathryn’s Beach screenshot. The full-image action opens the faithful asset in a new tab.
- Check both case-study PDFs, mobile menu/Escape, FAQ keyboard controls, book carousel controls/pause and reduced-motion behaviour at a small phone width.
- Inspect page source on Home, About, Contact, all ten services and Insights: exactly one `<title>` and one H1, approved descriptions/canonicals/social data still present. Review the [six proposed wording corrections](TITLE-REVIEW.md) privately against actual Yoast fields; this release has not edited them.
- Hello World is Draft with its original content; the sample comment is held. The associated unused archives are noindex and absent from Yoast sitemap output. Real posts and useful archives remain available.
- `/services/book-formatting/`, `/services/cover-design/`, `/services/amazon-ads/`, `/privacy-policy/` and `/terms-of-service/` load; `/insights/connected-catalog/` remains an Insight. New authority drafts are anonymous 404 and absent from public sitemap/index until published.
- Check the existing Hostinger/brand-agent widget integration: its public translation URL returned 404 during the cloud audit. Verify any floating UI and console behaviour on your device. No widget settings or Search Console property was reconnected here.

## Rollback

Replace the plugin with [4.4.0](../4.4.0/grant-publishing-site-4.4.0.zip) to roll back runtime presentation and hooks. Keep the new drafts unpublished. Plugin replacement does not undo database state: if starter-content restoration or draft removal is desired, use the specific pre-install database records or retained private starter backup, preserving unrelated new enquiries and edits. Do not republish starter content merely to roll back appearance. Existing manual SEO/delivery settings were not overwritten and need no reset.

Do not deploy anything from `qa/` or release `evidence/`: local test endpoints, MU plugins, users, configuration and databases are not production files. The installable ZIP contains only the `grant-publishing-site/` plugin directory.
