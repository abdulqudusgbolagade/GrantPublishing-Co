/* Independent, progressively enhanced client-book showcases. */
(() => {
  'use strict';
  const init = root => {
    if (root.dataset.showcaseReady) return;
    const slides = Array.from(root.querySelectorAll('[data-slide]'));
    const indicators = Array.from(root.querySelectorAll('[data-indicator]'));
    const toggle = root.querySelector('[data-toggle]');
    const status = root.querySelector('.gp-showcase-status');
    if (slides.length < 2 || !toggle) return;
    root.dataset.showcaseReady = 'true';
    root.querySelector('.gp-showcase-controls').hidden = false;
    const motion = matchMedia('(prefers-reduced-motion: reduce)');
    let current = 0, timer, request = 0, hovered = false, focused = false;
    let paused = false, visible = true;
    const updateToggle = () => {
      toggle.disabled = motion.matches;
      toggle.setAttribute('aria-label', motion.matches ? 'Automatic rotation disabled for reduced motion' : paused ? 'Play automatic book rotation' : 'Pause automatic book rotation');
      toggle.firstElementChild.innerHTML = '<svg aria-hidden="true" focusable="false" viewBox="0 0 16 16"><path d="' + (motion.matches || paused ? 'M4 2l10 6-10 6z' : 'M3 2h4v12H3zM10 2h4v12h-4z') + '"/></svg>';
    };
    const schedule = () => {
      clearTimeout(timer);
      if (!motion.matches && !paused && !hovered && !focused && visible && !document.hidden) timer = setTimeout(() => show(current + 1, false), 5000);
    };
    const load = async slide => {
      const img = slide.querySelector('img');
      if (img.dataset.src) {
        if (img.dataset.srcset) img.srcset = img.dataset.srcset;
        img.src = img.dataset.src;
        delete img.dataset.src; delete img.dataset.srcset;
      }
      if (img.decode) await img.decode();
      else if (!img.complete) await new Promise((resolve, reject) => {
        img.addEventListener('load', resolve, {once:true}); img.addEventListener('error', reject, {once:true});
      });
      if (!img.naturalWidth) throw new Error('Cover unavailable');
    };
    const show = async (index, announce = true) => {
      clearTimeout(timer);
      const next = (index + slides.length) % slides.length;
      const pending = ++request;
      if (next === current) { schedule(); return; }
      try { await load(slides[next]); }
      catch (_) {
        if (pending !== request) return;
        if (announce) status.textContent = 'This cover could not load. The current book remains visible.';
        paused = true; updateToggle(); return;
      }
      if (pending !== request) return;
      if (!announce && (motion.matches || paused || hovered || focused || !visible || document.hidden)) return;
      const previous = slides[current];
      const moveFocus = previous.contains(document.activeElement);
      previous.classList.remove('is-active'); previous.setAttribute('aria-hidden', 'true'); previous.inert = true;
      previous.querySelector('a').tabIndex = -1;
      slides[next].hidden = false; slides[next].inert = false; slides[next].removeAttribute('aria-hidden');
      slides[next].querySelector('a').removeAttribute('tabindex');
      void slides[next].offsetWidth;
      slides[next].classList.add('is-active'); current = next;
      if (moveFocus) slides[next].querySelector('a').focus({preventScroll:true});
      setTimeout(() => { if (previous !== slides[current]) previous.hidden = true; }, motion.matches ? 0 : 650);
      indicators.forEach((button, i) => button.setAttribute('aria-pressed', String(i === current)));
      if (announce) status.textContent = slides[current].getAttribute('aria-label');
      schedule();
    };
    root.querySelector('[data-previous]').addEventListener('click', () => show(current - 1));
    root.querySelector('[data-next]').addEventListener('click', () => show(current + 1));
    indicators.forEach((button, i) => button.addEventListener('click', () => show(i)));
    toggle.addEventListener('click', () => { paused = !paused; updateToggle(); schedule(); });
    root.addEventListener('keydown', event => {
      if (event.altKey || event.ctrlKey || event.metaKey) return;
      if (!['ArrowLeft','ArrowRight','Home','End'].includes(event.key)) return;
      event.preventDefault(); show(event.key === 'Home' ? 0 : event.key === 'End' ? slides.length - 1 : current + (event.key === 'ArrowRight' ? 1 : -1));
    });
    root.addEventListener('mouseenter', () => { hovered = true; schedule(); });
    root.addEventListener('mouseleave', () => { hovered = false; schedule(); });
    root.addEventListener('focusin', () => { focused = true; schedule(); });
    root.addEventListener('focusout', event => { focused = root.contains(event.relatedTarget); schedule(); });
    document.addEventListener('visibilitychange', schedule);
    const changed = () => { updateToggle(); schedule(); };
    if (motion.addEventListener) motion.addEventListener('change', changed); else motion.addListener(changed);
    if ('IntersectionObserver' in window) {
      visible = false;
      new IntersectionObserver(entries => { visible = entries[0].isIntersecting; schedule(); }, {threshold:0.1}).observe(root);
    }
    updateToggle(); schedule();
  };
  const start = () => document.querySelectorAll('[data-book-showcase]').forEach(init);
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
