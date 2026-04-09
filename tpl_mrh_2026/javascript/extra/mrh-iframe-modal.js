/**
 * MRH 2026 – Popup Modal (Vanilla JS + Bootstrap 5)
 * Ersetzt Fancybox/Colorbox für Links mit class="iframe"
 * Lädt den Inhalt per AJAX-Fetch direkt ins Bootstrap 5 Modal
 * (kein iframe nötig → erbt CSS der Hauptseite)
 * Stand: 2026-04-09 v3
 */
(function () {
  'use strict';

  var MODAL_ID  = 'mrhPopupModal';
  var LOADING   = '<div class="text-center py-5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Laden…</span></div></div>';

  /* ── Modal-HTML einmalig ins DOM einfügen ─────────────────── */
  function ensureModal() {
    if (document.getElementById(MODAL_ID)) return;

    var wrapper = document.createElement('div');
    wrapper.innerHTML =
      '<div id="' + MODAL_ID + '" class="modal fade" tabindex="-1" aria-hidden="true">' +
        '<div class="modal-dialog modal-lg modal-dialog-scrollable">' +
          '<div class="modal-content">' +
            '<div class="modal-header">' +
              '<h5 class="modal-title" id="' + MODAL_ID + 'Label"></h5>' +
              '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>' +
            '</div>' +
            '<div class="modal-body" id="' + MODAL_ID + 'Body">' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>';

    document.body.appendChild(wrapper.firstChild);
  }

  /* ── Body-Inhalt aus HTML-Dokument extrahieren ────────────── */
  function extractBody(html) {
    // <body …> bis </body> extrahieren
    var match = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (match && match[1]) return match[1].trim();
    // Fallback: gesamten HTML-String verwenden
    return html;
  }

  /* ── Titel aus HTML-Dokument extrahieren ──────────────────── */
  function extractTitle(html) {
    var match = html.match(/<title[^>]*>([\s\S]*?)<\/title>/i);
    if (match && match[1]) return match[1].trim();
    // Fallback: erste <h1> suchen
    var h1 = html.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i);
    if (h1 && h1[1]) return h1[1].replace(/<[^>]+>/g, '').trim();
    return '';
  }

  /* ── Modal öffnen und Inhalt per Fetch laden ──────────────── */
  function openModal(url, linkTitle) {
    ensureModal();

    var modalEl  = document.getElementById(MODAL_ID);
    var bodyEl   = document.getElementById(MODAL_ID + 'Body');
    var titleEl  = document.getElementById(MODAL_ID + 'Label');

    // Sofort Titel + Loader anzeigen
    titleEl.textContent = linkTitle || 'Information';
    bodyEl.innerHTML    = LOADING;

    // Bootstrap 5 Modal öffnen
    var bsModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
      backdrop: true,
      keyboard: true
    });
    bsModal.show();

    // Inhalt per Fetch laden
    fetch(url, { credentials: 'same-origin' })
      .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
      })
      .then(function (html) {
        // Titel aus der geladenen Seite übernehmen (falls vorhanden)
        var pageTitle = extractTitle(html);
        if (pageTitle) {
          titleEl.textContent = pageTitle;
        }

        // Body-Inhalt extrahieren und einfügen
        var content = extractBody(html);

        // Erste <h1> entfernen (wird schon als Modal-Title angezeigt)
        content = content.replace(/<h1[^>]*>[\s\S]*?<\/h1>/i, '');

        bodyEl.innerHTML = content;
      })
      .catch(function (err) {
        bodyEl.innerHTML =
          '<div class="alert alert-warning m-3">' +
            '<strong>Inhalt konnte nicht geladen werden.</strong><br>' +
            '<a href="' + url + '" target="_blank" rel="noopener">Seite in neuem Tab öffnen</a>' +
          '</div>';
        console.warn('[MRH-Popup] Fetch-Fehler:', err);
      });

    // Body leeren wenn Modal geschlossen wird
    modalEl.addEventListener('hidden.bs.modal', function handler() {
      bodyEl.innerHTML = '';
      modalEl.removeEventListener('hidden.bs.modal', handler);
    });
  }

  /* ── Click-Handler für alle a.iframe Links ────────────────── */
  function initPopupLinks() {
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a.iframe');
      if (!link) return;

      e.preventDefault();
      e.stopPropagation();

      var url   = link.getAttribute('href');
      var title = link.getAttribute('title')
               || link.textContent.trim()
               || 'Information';

      if (url && url !== '#') {
        openModal(url, title);
      }
    });
  }

  /* ── Initialisierung ──────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPopupLinks);
  } else {
    initPopupLinks();
  }
})();
