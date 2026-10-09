# Install Grant Publishing Co. 4.2.0

Requires WordPress 6.0+ and PHP 7.4+. Elementor 3.16+ is required for native editing; the Grant shortcodes also work without Elementor.

1. Back up the plugin and database; install on staging first.
2. Upload `grant-publishing-site-4.2.0.zip` through **Plugins → Add New → Upload Plugin**. Choose **Replace current with uploaded** and activate if necessary.
3. Clear hosting/LiteSpeed, CDN and browser caches. Regenerate Elementor CSS & Data if stale. Confirm both `site.css` and `design.css` have `ver=4.2.0`.
4. Keep `/contact/` and `/book-marketing-audit/` excluded from full-page caching. Check all pages while logged out.
5. Confirm the larger dimensional header mark, `#09072B` navigation, alternating sections, new footer artwork/icons and My Dear Grandfather PDF card on Client Feedback.
6. Verify the phone menu, three cover destinations, PDF action, service preselection and a clearly labelled test enquiry in your real inbox.

**Do not recreate pages or bulk-convert saved Elementor layouts.** Shared shell/styles update directly. Saved native Feedback receives a missing case study at render time; existing contact text links receive icons at render time. Authored page data remains saved and unchanged. Earlier saved native sections receive the CSS rhythm; arbitrary custom inner styling may need staging review.

The once-per-version generated-cache refresh and LiteSpeed purges are scoped to connected, published Grant pages. Other caches may need manual clearing. The existing WordPress site icon stays unchanged.

## Delivery and editing

Existing private Web3Forms settings stay saved; a configured key does not need re-entry. If needed, configure it privately under **Tools → Grant Website Setup → Enquiry delivery**. Provider acceptance does not establish inbox receipt. No real key is included in the ZIP; development tests used mocks and sent no messages.

Shared contact details remain configurable under Grant Website Setup. Icons use those saved destinations. Native text/images remain editable in Elementor; keep the Grant full-page template. The standard navigation remains under Appearance → Menus.

The linked PDF remains the user-supplied WordPress upload. If it is moved, update the maintained template/partial or authored page destination. The PDF reader's own download controls can save it; the website promises an open-in-new-tab action.

## Rollback

Replace the plugin with retained `releases/4.1.0/grant-publishing-site-4.1.0.zip` and clear the same caches. Saved page edits and delivery settings remain. No database/content migration runs in 4.2. Restore content backups only for separate manual edits; bulk restore/pre-conversion actions are not needed for plugin rollback.

This release is published on GitHub, not installed live. Actual WordPress/Elementor/theme integration, existing theme/Canvas duplicate title behavior, other browsers and inbox delivery remain staging checks.
