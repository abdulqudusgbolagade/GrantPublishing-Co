(function () {
  // No analytics script or identifier is installed here. The consent manager
  // must grant statistics consent before events reach an existing GA4 instance.
  let consentGranted = false;
  document.addEventListener('grant:analytics-consent', function (event) { consentGranted = event.detail?.statistics === true; });
  function track(name, fields) {
    const allowed = typeof window.wp_has_consent === 'function' ? window.wp_has_consent('statistics') === true : consentGranted;
    if (allowed && typeof window.gtag === 'function') window.gtag('event', name, fields);
  }
  function setup() {
    document.querySelectorAll('.gpc-site').forEach(function (site) {
      if (site.dataset.interactionsReady) return;
      site.dataset.interactionsReady = '1';
      const header = site.querySelector('.gp-header');
      if (header) {
        const updateHeader = function () { header.classList.toggle('is-scrolled', window.scrollY > 24); };
        window.addEventListener('scroll', updateHeader, { passive: true });
        updateHeader();
      }
      site.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link || !link.matches('.gp-button,.elementor-button,.gp-text-link,.gp-selected-image')) return;
        const target = new URL(link.href, location.href);
        if (target.origin !== location.origin) return;
        // No query strings, book URLs, names, emails or free text are sent.
        track(target.pathname.includes('/case-studies/') ? 'view_work' : 'select_content', { content_type:'website_cta', destination:target.pathname });
      });
      site.addEventListener('grant:enquiry-accepted', function (event) {
        const request = event.detail?.request === 'assessment' ? 'assessment' : 'project';
        const project = ['luma','grandfather','kathryns','formatting-samples'].includes(event.detail?.project) ? event.detail.project : '';
        track('generate_lead', { request_type:request, portfolio_context:project });
      });
      const menu = site.querySelector('.gp-mobile-nav');
      if (menu) {
        const summary = menu.querySelector('summary');
        let lastMenuFocus = null;
        menu.addEventListener('focusin', function (event) { lastMenuFocus = event.target; });
        const close = function (restoreFocus) {
          if (!menu.open) return;
          menu.open = false;
          if (restoreFocus) summary.focus();
        };
        menu.addEventListener('keydown', function (event) {
          if (event.key === 'Escape' && menu.open) { event.preventDefault(); close(true); }
        });
        menu.addEventListener('click', function (event) {
          if (event.target.closest('a')) close(false);
        });
        document.addEventListener('click', function (event) {
          if (!menu.contains(event.target)) close(menu.contains(document.activeElement));
        });
        menu.addEventListener('focusout', function (event) {
          if (event.relatedTarget && event.relatedTarget !== document.body && !menu.contains(event.relatedTarget)) lastMenuFocus = null;
          setTimeout(function () {
            if (desktop.matches) resize();
            if (!menu.contains(document.activeElement)) close(false);
          }, 0);
        });
        const desktop = window.matchMedia('(min-width:1121px)');
        const resize = function () {
          if (desktop.matches && menu.open) {
            // Some browsers reset focus to body as soon as CSS hides the menu,
            // before the media-query event arrives. Keep that keyboard context.
            const focused = menu.contains(document.activeElement) || (lastMenuFocus && document.activeElement === document.body);
            close(false);
            lastMenuFocus = null;
            if (focused) site.querySelector('.gp-wordmark').focus();
          }
        };
        if (desktop.addEventListener) desktop.addEventListener('change', resize);
        else desktop.addListener(resize);
      }
      // Use native anchors, including in existing saved Elementor layouts. No card click interception.
      site.querySelectorAll('.gp-service-row, .gp-insight-card, .gp-article-row').forEach(function (card) {
        const action = card.querySelector('.elementor-button');
        const heading = card.querySelector('h2, h3');
        if (!action || !heading) return;
        if (!heading.querySelector('a')) {
          const link = document.createElement('a');
          link.className = 'gp-title-link';
          link.href = action.href;
          if (action.target) link.target = action.target;
          if (action.rel) link.rel = action.rel;
          while (heading.firstChild) link.appendChild(heading.firstChild);
          heading.appendChild(link);
        }
        heading.querySelector('a').classList.add('gp-title-link');
        if (!action.hasAttribute('aria-label')) {
          action.setAttribute('aria-label', action.textContent.trim() + ': ' + heading.textContent.trim());
        }
      });
      site.querySelectorAll('main#gpc-main').forEach(function (main) { main.setAttribute('tabindex', '-1'); });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup); else setup();
})();
