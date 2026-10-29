# Analytics and enquiry attribution

## Existing configuration

The read-only production snapshot contains Google Site Kit and a Google tag script identified publicly as `GT-T5MRBS89`. This is not enough to confirm its underlying GA4 property, permissions or reporting. No direct Search Console verification meta tag or Clarity script was identified in that HTML snapshot. DNS verification or configuration inside Google Tag Manager may exist; this inspection cannot rule it out.

No tracker identifier, additional analytics script, Clarity account, Search Console property or duplicate Google tag has been installed by this release. Existing WordPress integrations and settings are retained. Server-side Web3Forms delivery remains unchanged in principle, with validated optional project details added to its existing payload.

## Consent and events

`assets/interactions.js` can send events to an existing `window.gtag` only after statistics consent:

- If WordPress Consent API is available, `window.wp_has_consent('statistics')` must return `true`.
- Otherwise the installed consent manager must dispatch `grant:analytics-consent` with `{statistics:true}` after a valid grant, and `{statistics:false}` when consent is revoked. No consent is assumed, persisted or inferred by this plugin.
- Events before consent are dropped, not queued for later transmission.

Example bridge to invoke from the consent manager's existing callbacks, not as unconditional page code:

```js
document.dispatchEvent(new CustomEvent('grant:analytics-consent', {
  detail: { statistics: true } // Use false on withdrawal.
}));
```

Events:

| Event | Trigger | Fields |
| --- | --- | --- |
| `select_content` | Selected internal CTA | `content_type:website_cta`, destination path |
| `view_work` | Selected internal case-study CTA | `content_type:website_cta`, destination path |
| `generate_lead` | WordPress confirms provider acceptance or successful configured mail handling | Request type and allowlisted portfolio context |

Names, email addresses, messages, book URLs, arbitrary query strings and free-text fields are not sent in these events. A confirmed duplicate submission does not produce a second lead event. Provider acceptance is not a claim of inbox delivery. No-JavaScript submissions continue to work but do not emit browser events.

Portfolio context is an optional `project` query value (`luma`, `grandfather`, `kathryns`, or `formatting-samples`) carried into a hidden, server-validated field. The existing Luma project-enquiry and My Dear Grandfather assessment CTAs carry their corresponding source. Unknown values and URL-shaped attribution values are discarded. No cookie or browser storage is introduced.

## Owner setup and verification

1. In the existing Site Kit/GA4 account, confirm the connected property and Google tag. Do not add another tag because this plugin has added event support.
2. Connect the existing consent manager to WordPress Consent API or the bridge above. Test refusal and withdrawal, including in regions requiring prior consent. The bridge does not implement a consent banner.
3. Use GA4 DebugView/Realtime to inspect one approved owner enquiry after provider acceptance. Register `portfolio_context` and `request_type` as event-scoped custom dimensions if useful.
4. Mark `generate_lead` as a key event in the existing GA4 property. Review any existing automatic `form_submit` or Site Kit conversion configuration so the same enquiry is not counted twice as a business conversion.
5. Keep standard campaign UTM links for GA4's existing consent-aware attribution. This release does not copy arbitrary UTM strings, referrers or personal data into the enquiry payload. Confirm campaign reporting in the actual property.
6. In the current Search Console property, verify ownership, the existing sitemap and URL inspection. Do not create a second property or replace DNS records without checking existing verification.
7. If Clarity is wanted and not already configured, supply the authorised project ID and configure it once through the chosen existing integration, with appropriate consent. No ID has been invented.

Authenticated WordPress, GA4, Search Console and Clarity access was unavailable. Local tests verified event gating, revocation, accepted-submit counting, duplicate suppression and exclusion of personal fields using an instrumented `gtag`, without transmitting analytics or real enquiries. Production dashboard receipt requires the manual checks above.
