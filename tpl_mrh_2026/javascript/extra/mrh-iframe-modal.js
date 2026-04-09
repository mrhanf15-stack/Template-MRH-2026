/**
 * MRH 2026 – Iframe Modal (Vanilla JS + Bootstrap 5)
 * Ersetzt Fancybox/Colorbox für Links mit class="iframe"
 * Öffnet den href als iframe in einem Bootstrap 5 Modal
 * Stand: 2026-04-09
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
      '<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">' +
        '<div class="modal-content">' +
          '<div class="modal-header py-2">' +
            '<h5 class="modal-title" id="' + MODAL_ID + 'Label"></h5>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>' +
          '</div>' +
          '<div class="modal-body p-0">' +
            '<iframe id="' + MODAL_ID + 'Frame" src="" ' +
              'style="width:100%;min-height:400px;border:none;" ' +
              'loading="lazy"></iframe>' +
          '</div>' +
        '</div>' +
      '</div>';

    document.body.appendChild(modal);
  }

  /** iframe-Höhe dynamisch anpassen */
  function adjustIframeHeight(iframe) {
    try {
      var doc = iframe.contentDocument || iframe.contentWindow.document;
      var height = doc.body.scrollHeight;
      if (height > 100) {
        iframe.style.minHeight = Math.min(height + 20, 600) + 'px';
      }
    } catch (e) {
      // Cross-Origin – Fallback-Höhe beibehalten
    }
  }

  /** Modal öffnen mit URL */
  function openModal(url, title) {
    createModal();

    var modalEl = document.getElementById(MODAL_ID);
    var iframe = document.getElementById(MODAL_ID + 'Frame');
    var titleEl = document.getElementById(MODAL_ID + 'Label');

    // Titel setzen
    titleEl.textContent = title || 'Information';

    // Alte iframe-Quelle leeren, dann neue setzen
    iframe.src = '';
    iframe.style.minHeight = '400px';
    iframe.src = url;

    // iframe-Höhe nach Laden anpassen
    iframe.onload = function () {
      adjustIframeHeight(iframe);
    };

    // Bootstrap 5 Modal öffnen
    var bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();

    // iframe leeren wenn Modal geschlossen wird
    modalEl.addEventListener('hidden.bs.modal', function handler() {
      iframe.src = '';
      modalEl.removeEventListener('hidden.bs.modal', handler);
    });
  }

  /** Click-Handler für alle a.iframe Links */
  function initIframeLinks() {
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a.iframe');
      if (!link) return;

      e.preventDefault();
      e.stopPropagation();

      var url = link.getAttribute('href');
      var title = link.getAttribute('title') || link.textContent.trim() || 'Information';

      if (url) {
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
