(function () {
  function setup() {
    document.querySelectorAll('.gpc-site').forEach(function (site) {
      if (site.dataset.interactionsReady) return;
      site.dataset.interactionsReady = '1';
      const menu = site.querySelector('.gp-mobile-nav');
      if (menu) {
        const summary = menu.querySelector('summary');
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
        menu.addEventListener('focusout', function () {
          setTimeout(function () { if (!menu.contains(document.activeElement)) close(false); }, 0);
        });
        const desktop = window.matchMedia('(min-width:981px)');
        const resize = function () {
          if (desktop.matches && menu.open) {
            const focused = menu.contains(document.activeElement);
            close(false);
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
