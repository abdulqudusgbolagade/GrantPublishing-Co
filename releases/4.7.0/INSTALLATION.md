# Review build 4.7.0: installation and rollback

Prepared 29 October 2026. **Not installed on the production website.** The latest brief explicitly requires review and approval before production deployment.

## Before approval

Review [the screenshots](GALLERY.md), [testing report](TESTING-REPORT.md) and [analytics guide](ANALYTICS.md). Review the homepage wording, four service categories, Our Work presentation, founder's updated title, optional enquiry fields and visual changes at desktop and mobile sizes. The source and ZIP can be reviewed on GitHub without changing the live website.

## Installation after approval

1. Back up the live WordPress database, current plugin directory, uploaded files and Elementor settings. Keep the currently installed plugin ZIP, not only this new ZIP.
2. In WordPress, go to Plugins → Add New → Upload Plugin. Upload `grant-publishing-site-4.7.0.zip` and choose to replace the existing Grant Publishing Co. Website plugin. Do not upload the repository ZIP.
3. Keep the existing delivery provider, saved Web3Forms key, contact details, page mappings, legal-page settings, favicon and Yoast configuration. This release does not ask you to enter the key again.
4. Do not bulk import native layouts, overwrite installed Elementor pages, reset page setup or replace manual Yoast fields. Shared presentation updates work with connected saved pages. New-install templates are supplied separately in the plugin.
5. Clear the page/server/CDN cache and regenerate Elementor CSS/data if necessary. Check both an ordinary logged-out browser and a private window.
6. Follow the live checklist in the testing report. Send a clearly labelled owner test from Contact and Assessment only after choosing the receiving mailbox and obtaining current approval for those messages. Local tests in this release did not send external emails.

## Editing and compatibility

The shared `premium.php` components render selected work, service categories, real project hero imagery and updated stock founder text without rewriting stored page content. Existing custom copy is preserved. For older saved Elementor layouts, use Preview or a logged-out public page to review the shared changes; the editor can still display the older saved widgets. New-install templates use the corresponding reusable `[grant_premium_block]` shortcodes. Do not reimport whole pages to obtain this update.

Elementor frontend scripts are omitted only for recognised static layouts. Unknown widgets/shortcodes, animations, motion settings and image-file lightboxes retain the Elementor runtime. The Elementor editor and preview are excluded from this optimisation.

## Rollback

Replace 4.7.0 with the exact plugin version backed up from the live installation and clear caches. The source rollback point for this development round is `a588056a3042ae77411966d9f15d4344a7c5f0a5`, tagged `backup/premium-upgrade-2026-10-28`; the 4.6.0 review ZIP also remains in the repository. This release does not migrate or replace the saved content, legal text or manual Yoast values. Restore the database backup only if a separate production action changed it.

QA databases, fake access keys, local PHP endpoints, test MU plugins, browsers and Node dependencies must never be installed on production. The installable ZIP contains only the plugin directory.
