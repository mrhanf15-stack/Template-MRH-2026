<?php
/* ═══════════════════════════════════════════════════════════════════════
   MRH Badge-Init v1.1.0 – Zentrale Badge-Logik
   
   Einheitliche Badge-Erstellung fuer Vergleich + Seedfinder.
   Ersetzt die duplizierte Badge-Logik in product_compare.html und
   seedfinder.html. Wird per auto_include geladen.
   
   Icons: FA6 Pro (fa-solid fa-venus), male.svg fuer Regulaer.
   Farben/Groessen: CSS-Variablen aus Konfigurator (--tpl-badge-*).
   CSS: mrh-custom.css ist Single Source of Truth.
   ═══════════════════════════════════════════════════════════════════════ */
if (!defined('MODULE_PRODUCT_COMPARE_STATUS') || MODULE_PRODUCT_COMPARE_STATUS !== 'true') return;
?>
<script>
(function(){
  'use strict';

  /* Template-Pfad ermitteln */
  var tplLink = document.querySelector('link[href*="tpl_mrh_2026"]');
  var tplBase = tplLink ? tplLink.href.replace(/\/css\/.*$/, '') : '/templates/tpl_mrh_2026';
  var MALE_SVG = tplBase + '/img/badges/male.svg';

  /**
   * initBadges(scope) – Badge-Zeile in .compare-badge-row befuellen
   * @param {string} scope  CSS-Selektor des Containers (z.B. '.product-compare-page', '#seedfinder_module')
   */
  function initBadges(scope) {
    var cards = document.querySelectorAll(scope + ' .card.h-100');
    cards.forEach(function(card) {
      var badgeRow = card.querySelector('.compare-badge-row');
      if (!badgeRow || badgeRow.dataset.badgesDone) return;
      badgeRow.dataset.badgesDone = '1';

      /* v1.1.0: Skip wenn bereits server-seitig gerenderte Badges vorhanden */
      if (badgeRow.querySelector('.mrh-badge-bar, .mrh-type-badge, .picto.templatestyle')) return;

      var descBox = card.querySelector('.compare-desc-box .lr_desc, .card-body .lr_desc');
      if (!descBox) return;

      /* 1. Existierende .picto.templatestyle suchen und uebernehmen */
      var pictos = descBox.querySelectorAll('.picto.templatestyle');
      pictos.forEach(function(picto) {
        if (picto.classList.contains('off')) {
          picto.style.display = 'none';
          return;
        }
        /* mrh-badge-bar (Fem/Reg/Photo Badges) 1:1 uebernehmen */
        var existingBar = picto.querySelector('.mrh-badge-bar');
        if (existingBar) {
          badgeRow.appendChild(existingBar.cloneNode(true));
        }
        /* Nur Icons (span.fa, span.fa-solid, img, svg) einzeln extrahieren */
        var icons = picto.querySelectorAll('.fa, .fa-solid, img, svg');
        icons.forEach(function(icon) {
          if (icon.closest('.mrh-badge-bar')) return;
          badgeRow.appendChild(icon.cloneNode(true));
        });
        picto.style.display = 'none';
      });

      /* 2. Fallback: Wenn KEINE existierende Badge-Bar, aus Tabelle erzeugen */
      if (!badgeRow.querySelector('.mrh-badge-bar')) {
        var table = descBox.querySelector('table');
        if (!table) return;
        var firstRow = table.querySelector('tr');
        if (!firstRow) return;

        var rowClass = (firstRow.className || '').trim().toLowerCase();
        var isAuto = (rowClass === 'aut');
        var isFem  = (rowClass === 'fem');
        var isReg  = (rowClass === 'reg');

        if (isAuto) {
          var tds = firstRow.querySelectorAll('td');
          if (tds.length >= 2) {
            var gText = (tds[1].textContent || '').trim().toLowerCase();
            if (gText.indexOf('femin') !== -1) isFem = true;
            else if (gText.indexOf('regul') !== -1) isReg = true;
          }
        }

        if (!isAuto && !isFem && !isReg) return;

        var bar = document.createElement('span');
        bar.className = 'mrh-badge-bar';

        if (isFem) {
          var femBadge = document.createElement('span');
          femBadge.className = 'mrh-type-badge mrh-badge-fem';
          femBadge.innerHTML = '<span class="fa-solid fa-fw fa-venus"></span>';
          femBadge.title = 'Feminisiert';
          bar.appendChild(femBadge);
        } else if (isReg) {
          var regBadge = document.createElement('span');
          regBadge.className = 'mrh-type-badge mrh-badge-reg';
          regBadge.innerHTML = '<img class="mrh-badge-icon" src="' + MALE_SVG + '" alt="Regul\u00e4r"> Regul\u00e4r';
          regBadge.title = 'Regul\u00e4r';
          bar.appendChild(regBadge);
        }

        if (!isAuto) {
          var photoBadge = document.createElement('span');
          photoBadge.className = 'mrh-type-badge mrh-badge-photo';
          photoBadge.textContent = 'Photoperiodisch';
          photoBadge.title = 'Photoperiodisch';
          bar.appendChild(photoBadge);
        }

        if (bar.children.length > 0) {
          badgeRow.insertBefore(bar, badgeRow.firstChild);
        }
      }
    });
  }

  /* Global verfuegbar machen */
  window.MrhBadgeInit = initBadges;

})();
</script>
