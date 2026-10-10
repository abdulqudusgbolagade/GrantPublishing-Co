(function () {
  function setup() {
    document.querySelectorAll('.gpc-enquiry-form').forEach(function (form) {
      if (form.dataset.ready) return;
      form.dataset.ready = '1';
      const query = new URLSearchParams(window.location.search);
      const choice = query.get('request');
      if (choice === 'project' || choice === 'assessment') form.elements.request.value = choice;
      const service = query.get('service');
      if (service && Array.from(form.elements.service.options).some(function (option) { return option.value === service; })) form.elements.service.value = service;
      const button = form.querySelector('button[type="submit"]');
      const bookDetails = form.querySelector('[data-book-details]');
      if (bookDetails) {
        const hasDetails = Array.from(bookDetails.querySelectorAll('input,select')).some(function (field) { return field.value !== ''; });
        if (hasDetails || form.elements.request.value === 'assessment') bookDetails.open = true;
        // Reveal invalid optional fields before the browser tries to focus them.
        form.addEventListener('invalid', function (event) {
          if (bookDetails.contains(event.target)) bookDetails.open = true;
        }, true);
      }
      function updateAction() {
        if (form.dataset.sending === '1') return;
        button.textContent = form.elements.request.value === 'assessment' ? 'Request free assessment' : 'Send project enquiry';
        if (bookDetails && form.elements.request.value === 'assessment') bookDetails.open = true;
      }
      form.elements.request.addEventListener('change', updateAction);
      updateAction();
      const initialRequest = form.elements.request.value;
      const initialService = form.elements.service.value;
      const initialDetailsOpen = bookDetails ? bookDetails.open : false;
      form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (!form.reportValidity() || form.dataset.sending === '1') return;
        const button = form.querySelector('button[type="submit"]');
        const status = form.querySelector('.gpc-form-status');
        const data = new FormData(form);
        data.set('gpc_ajax', '1');
        const controller = new AbortController();
        const timeout = setTimeout(function () { controller.abort(); }, 25000);
        form.dataset.sending = '1';
        button.disabled = true;
        button.textContent = 'Sending…';
        status.textContent = 'Sending your enquiry…';
        status.dataset.state = 'sending';
        form.setAttribute('aria-busy', 'true');
        let confirmedFailure = false;
        try {
          const response = await fetch(form.getAttribute('action'), { method: 'POST', body: data, credentials: 'same-origin', signal: controller.signal });
          const result = await response.json();
          if (!response.ok || !result.success) {
            confirmedFailure = true;
            throw new Error(result.data?.message || 'Your enquiry could not be sent. Please try again or contact us directly.');
          }
          if (typeof result.data?.message !== 'string') throw new Error('Unexpected response');
          status.textContent = result.data.message;
          status.dataset.state = 'success';
          form.reset();
          form.elements.request.value = initialRequest;
          form.elements.service.value = initialService;
          if (bookDetails) bookDetails.open = initialDetailsOpen;
        } catch (error) {
          status.dataset.state = 'error';
          status.textContent = confirmedFailure ? error.message : 'We could not confirm whether your enquiry was received. Your details are still here. Please contact ' + (form.dataset.contactEmail || 'hello@grantpublishingco.com') + ' to check before sending it again.';
        } finally {
          clearTimeout(timeout);
          form.dataset.sending = '0';
          button.disabled = false;
          form.setAttribute('aria-busy', 'false');
          updateAction();
          status.focus();
        }
      });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup); else setup();
})();
