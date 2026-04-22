<?php
/* ═══════════════════════════════════════════════════════════════════════
   MRH Badge-Init v1.3.0 – Zentrale Badge-Logik
   
   Einheitliche Badge-Erstellung fuer Vergleich + Seedfinder.
   Ersetzt die duplizierte Badge-Logik in product_compare.html und
   seedfinder.html. Wird per auto_include geladen.
   
   Icons: FA6 Pro (fa-solid fa-venus), male.svg fuer Regulaer.
   Farben/Groessen: CSS-Variablen aus Konfigurator (--tpl-badge-*).
   CSS: mrh-custom.css ist Single Source of Truth.
   
   v1.2.0: Lose Icons (shortfongc, shortfongc0) werden in
   .mrh-type-badge Wrapper gepackt. Gruener Container wird
   um die Badge-Row gelegt.
   v1.3.0: Duplikat-Vermeidung: Bereits vorhandene Badge-Typen
   (aus mrh-badge-bar) werden nicht nochmal als lose Icons hinzugefuegt.
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

  /* v1.2.0: Icon-Klasse zu Badge-Typ Mapping */
  var ICON_TYPE_MAP = {
    'fa-gauge-high': 'auto',
    'fa-tachometer': 'auto',
    'fa-venus': 'fem',
    'fa-mars': 'reg',
    'fa-sun': 'photo',
    'fa-trophy': 'cup',
    'fa-medkit': 'medical',
    'fa-kit-medical': 'medical'
  };

  /**
   * v1.2.0: Detect badge type from an icon element's classes
   */
  function detectBadgeType(iconEl) {
    var cls = iconEl.className || '';
    for (var iconClass in ICON_TYPE_MAP) {
      if (cls.indexOf(iconClass) !== -1) return ICON_TYPE_MAP[iconClass];
    }
    return 'legacy';
  }

  /**
   * v1.2.0: Wrap a loose icon in a .mrh-type-badge container
   */
  function wrapIconInBadge(iconEl) {
    var type = detectBadgeType(iconEl);
    var wrapper = document.createElement('span');
    wrapper.className = 'mrh-type-badge mrh-badge-' + type;
    wrapper.title = iconEl.title || iconEl.getAttribute('title') || '';
    wrapper.appendChild(iconEl.cloneNode(true));
    return wrapper;
  }

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

      /* v1.1.0+v1.5.0: Wenn bereits server-seitig gerenderte Badges vorhanden,
         normalisiere sie (Text entfernen, Icons einsetzen) und ueberspringe Neuerstellung */
      if (badgeRow.querySelector('.mrh-badge-bar, .mrh-type-badge, .picto.templatestyle')) {
        /* v1.5.0: Vorhandene Badges normalisieren */
        badgeRow.querySelectorAll('.mrh-type-badge').forEach(function(b) {
          /* Reg-Badge: Text-Knoten entfernen (nur Icon behalten) */
          if (b.classList.contains('mrh-badge-reg')) {
            var hasIcon = b.querySelector('img, .fa, .fa-solid, svg');
            if (hasIcon) {
              /* Entferne alle Text-Knoten */
              Array.from(b.childNodes).forEach(function(n) {
                if (n.nodeType === 3 && n.textContent.trim()) n.textContent = '';
              });
            } else {
              /* Kein Icon vorhanden: male.svg einsetzen */
              b.innerHTML = '<img class="mrh-badge-icon" src="' + tplBase + '/img/badges/male.svg" alt="Regulaer">';
            }
          }
          /* Photo-Badge: Text durch Icon ersetzen */
          if (b.classList.contains('mrh-badge-photo')) {
            var hasPhotoIcon = b.querySelector('.fa, .fa-solid, img, svg');
            if (!hasPhotoIcon) {
              b.innerHTML = '<span class="fa-solid fa-fw fa-sun"></span>';
            } else {
              Array.from(b.childNodes).forEach(function(n) {
                if (n.nodeType === 3 && n.textContent.trim()) n.textContent = '';
              });
            }
          }
        });
        return;
      }

      var descBox = card.querySelector('.compare-desc-box .lr_desc, .card-body .lr_desc');
      if (!descBox) return;

      /* 1. Existierende .picto.templatestyle suchen und uebernehmen */
      var pictos = descBox.querySelectorAll('.picto.templatestyle');
      /* v1.3.0: Bereits vorhandene Badge-Typen tracken (Duplikat-Vermeidung) */
      var existingTypes = {};
      pictos.forEach(function(picto) {
        if (picto.classList.contains('off')) {
          picto.style.display = 'none';
          return;
        }
        /* mrh-badge-bar (Fem/Reg/Photo Badges) 1:1 uebernehmen */
        var existingBar = picto.querySelector('.mrh-badge-bar');
        if (existingBar) {
          var clonedBar = existingBar.cloneNode(true);
          badgeRow.appendChild(clonedBar);
          /* v1.4.0: Geklonte Badges normalisieren (Text entfernen, Icons einsetzen) */
          clonedBar.querySelectorAll('.mrh-type-badge').forEach(function(b) {
            var m = (b.className || '').match(/mrh-badge-(fem|auto|reg|photo|cup|medical)/);
            if (m) existingTypes[m[1]] = true;
            /* Reg-Badge: Text-Knoten entfernen (nur Icon behalten) */
            if (b.classList.contains('mrh-badge-reg')) {
              b.childNodes.forEach(function(n) {
                if (n.nodeType === 3 && n.textContent.trim()) n.textContent = '';
              });
              /* Falls kein Icon vorhanden, male.svg einsetzen */
              if (!b.querySelector('img, .fa, .fa-solid, svg')) {
                b.innerHTML = '<img class="mrh-badge-icon" src="' + tplBase + '/img/badges/male.svg" alt="Regulaer">';
              }
            }
            /* Photo-Badge: Text durch Icon ersetzen */
            if (b.classList.contains('mrh-badge-photo')) {
              var hasIcon = b.querySelector('.fa, .fa-solid, img, svg');
              if (!hasIcon) {
                b.innerHTML = '<span class="fa-solid fa-fw fa-sun"></span>';
              } else {
                b.childNodes.forEach(function(n) {
                  if (n.nodeType === 3 && n.textContent.trim()) n.textContent = '';
                });
              }
            }
          });
        }
        /* v1.2.0+v1.3.0: Lose Icons in .mrh-type-badge Wrapper packen,
           aber nur wenn der Typ noch nicht vorhanden ist */
        var icons = picto.querySelectorAll('.fa, .fa-solid, img, svg');
        icons.forEach(function(icon) {
          if (icon.closest('.mrh-badge-bar')) return;
          var type = detectBadgeType(icon);
          if (existingTypes[type]) return; /* v1.3.0: Duplikat vermeiden */
          var wrapped = wrapIconInBadge(icon);
          badgeRow.appendChild(wrapped);
          existingTypes[type] = true;
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
          regBadge.innerHTML = '<img class="mrh-badge-icon" src="' + MALE_SVG + '" alt="Regul\u00e4r">';
          regBadge.title = 'Regul\u00e4r';
          bar.appendChild(regBadge);
        }

        if (isAuto) {
          var autoBadge = document.createElement('span');
          autoBadge.className = 'mrh-type-badge mrh-badge-auto';
          autoBadge.innerHTML = '<span class="fa-solid fa-fw fa-gauge-high"></span>';
          autoBadge.title = 'Autoflowering';
          bar.appendChild(autoBadge);
        }

        if (!isAuto) {
          var photoBadge = document.createElement('span');
          photoBadge.className = 'mrh-type-badge mrh-badge-photo';
          photoBadge.innerHTML = '<span class="fa-solid fa-fw fa-sun"></span>';
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
