/**
 * MRH 2026 – Iframe Modal (Vanilla JS + Bootstrap 5)
 * Ersetzt Fancybox/Colorbox für Links mit class="iframe"
 * Öffnet den href als iframe in einem Bootstrap 5 Modal
 * Stand: 2026-04-09 v2
 */
(function () {
  'use strict';

  var MODAL_ID = 'mrhIframeModal';

  /** Modal-HTML einmalig ins DOM einfügen */
  function createModal() {
    if (document.getElementById(MODAL_ID)) return;

    var modal = document.createElement('div');
    modal.id = MODAL_ID;
    modal.className = 'modal fade';
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML =
      '<div class="modal-dialog modal-lg modal-dialog-scrollable">' +
        '<div class="modal-content">' +
          '<div class="modal-header">' +
            '<h5 class="modal-title" id="' + MODAL_ID + 'Label"></h5>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>' +
          '</div>' +
          '<div class="modal-body p-0">' +
            '<iframe id="' + MODAL_ID + 'Frame" src="about:blank" ' +
              'style="width:100%;border:none;display:block;" ' +
              'allowfullscreen></iframe>' +
          '</div>' +
        '</div>' +
      '</div>';

    document.body.appendChild(modal);
  }

  /** iframe-Höhe dynamisch anpassen (same-origin only) */
  function adjustIframeHeight(iframe) {
    try {
      var doc = iframe.contentDocument || iframe.contentWindow.document;
      if (!doc || !doc.body) return;
      var height = doc.body.scrollHeight;
      if (height > 100) {
        iframe.style.height = Math.min(height + 30, window.innerHeight * 0.78) + 'px';
      }
    } catch (e) {
      // Cross-Origin – CSS-Fallback-Höhe beibehalten
    }
  }

  /** Modal öffnen mit URL */
  function openModal(url, title) {
    createModal();

    var modalEl = document.getElementById(MODAL_ID);
    var iframe  = document.getElementById(MODAL_ID + 'Frame');
    var titleEl = document.getElementById(MODAL_ID + 'Label');

    // Titel setzen
    titleEl.textContent = title || 'Information';

    // iframe zurücksetzen und neue URL laden
    iframe.src = 'about:blank';
    iframe.style.height = '';

    // Kurze Verzögerung damit about:blank geladen wird bevor neue URL gesetzt wird
    setTimeout(function () {
      iframe.src = url;
    }, 50);

    // iframe-Höhe nach Laden anpassen
    iframe.onload = function () {
      adjustIframeHeight(iframe);
    };

    // Bootstrap 5 Modal öffnen
    var bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
      backdrop: true,
      keyboard: true
    });
    bsModal.show();

    // iframe leeren wenn Modal geschlossen wird (Speicher freigeben)
    modalEl.addEventListener('hidden.bs.modal', function handler() {
      iframe.src = 'about:blank';
      iframe.style.height = '';
      modalEl.removeEventListener('hidden.bs.modal', handler);
    });
  }

  /** Click-Handler für alle a.iframe Links (Event Delegation) */
  function initIframeLinks() {
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a.iframe');
      if (!link) return;

      e.preventDefault();
      e.stopPropagation();

      var url   = link.getAttribute('href');
      var title = link.getAttribute('title') || link.textContent.trim() || 'Information';

      if (url && url !== '#') {
        openModal(url, title);
      }
    });
  }

  /** Initialisierung */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIframeLinks);
  } else {
    initIframeLinks();
  }
})();
