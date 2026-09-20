(function () {
  'use strict';

  function showBanner(form, success, text) {
    var existing = form.parentNode.querySelector('.contact-form__banner');
    if (existing) {
      existing.remove();
    }
    var banner = document.createElement('p');
    banner.className = 'contact-form__banner ' + (success ? 'contact-form__banner--success' : 'contact-form__banner--error');
    banner.setAttribute('role', 'status');
    banner.textContent = text;
    form.insertAdjacentElement('beforebegin', banner);
    banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function wireForm(form) {
    // Fill in the page context for the email (more precise than the
    // static hidden-field fallback already in the HTML).
    var sourceField = form.querySelector('input[name="source_page"]');
    if (sourceField) {
      sourceField.value = document.title || window.location.pathname;
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var submitBtn = form.querySelector('input[type="submit"]');
      var originalValue = submitBtn ? submitBtn.value : null;
      if (submitBtn) {
        submitBtn.value = 'Sending…';
        submitBtn.disabled = true;
      }

      var formData = new FormData(form);

      fetch('contact-handler.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
      })
        .then(function (response) { return response.json(); })
        .then(function (data) {
          showBanner(form, !!data.success, data.message || 'Something went wrong.');
          if (data.success) {
            form.reset();
          }
        })
        .catch(function () {
          showBanner(form, false, "Sorry, something went wrong sending your message. Please email info@nurtureimpact.ie directly.");
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.value = originalValue;
            submitBtn.disabled = false;
          }
        });
    });
  }

  function showRedirectFallbackBanner() {
    // Handles the case where the form was submitted without JavaScript
    // (contact-handler.php redirects back here with a status in the URL).
    var params = new URLSearchParams(window.location.search);
    if (!params.has('contact_status')) {
      return;
    }
    var form = document.getElementById('contact-form');
    if (form) {
      showBanner(form, params.get('contact_status') === 'success', params.get('contact_message') || '');
    }
    // Clean the URL so refreshing the page doesn't re-show the banner.
    params.delete('contact_status');
    params.delete('contact_message');
    var newSearch = params.toString();
    var newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '') + window.location.hash;
    window.history.replaceState({}, document.title, newUrl);
  }

  document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('#contact-form');
    forms.forEach(wireForm);
    showRedirectFallbackBanner();
  });
})();
