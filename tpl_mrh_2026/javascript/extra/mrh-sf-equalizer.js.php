<?php
/* -----------------------------------------------------------------------------------------
   $Id: mrh-sf-equalizer.js.php 1.4.0 2026-04-22 Mr. Hanf $
   MRH Seedfinder Row Equalizer
   Gleicht die Hoehe von Produktname, Badges und Footer in Seedfinder-Listings an,
   damit alle Karten in einer Reihe strukturiert aussehen.
   Tabelle bleibt flexibel (unterschiedliche Zeilenanzahl OK).
   Footer (Preis/Lager/Buttons) wird angeglichen, damit Buttons auf gleicher Hoehe.
   v1.4.0 – Fix: Wartet auf Bilder-Laden (lazy loading) bevor gemessen wird.
            Selektor auf #products-grid beschraenkt (keine Kategorie-Karten).
   Wird per auto_include() in general_bottom.js.php geladen.
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

// Nur auf Seedfinder-Seiten laden
$is_seedfinder = (strpos($_SERVER['REQUEST_URI'] ?? '', 'seedfinder') !== false);
if (!$is_seedfinder) return;
?>
<script>
/* ============================================================
   MRH Seedfinder Row Equalizer v1.4.0
   Gleicht pro Kartenreihe an:
   1. Produktname (.card-body.pb-1) – immer gleiche Hoehe
   2. Badges (.mrh-sf-badge-row)   – immer gleiche Hoehe
   3. Footer (.card-footer)        – immer gleiche Hoehe
      (Lager-Info macht manche Footer hoeher)
   Tabelle bleibt flexibel.
   v1.4.0 – Wartet auf Bilder-Laden, Selektor auf #products-grid.
   ============================================================ */
(function() {
  'use strict';

  /* Karten-Selektor: nur innerhalb #products-grid */
  var CARD_SEL = '#products-grid .card.h-100';

  function groupByRow(elements, tolerance) {
    tolerance = tolerance || 15;
    var rows = [];
    var currentRow = [];
    var currentTop = -999;

    elements.forEach(function(el) {
      var top = el.getBoundingClientRect().top + window.scrollY;
      if (currentRow.length === 0 || Math.abs(top - currentTop) <= tolerance) {
        currentRow.push(el);
        if (currentRow.length === 1) currentTop = top;
      } else {
        rows.push(currentRow);
        currentRow = [el];
        currentTop = top;
      }
    });
    if (currentRow.length > 0) rows.push(currentRow);
    return rows;
  }

  function equalizeRow(elements, selector) {
    /* Reset */
    elements.forEach(function(card) {
      var el = card.querySelector(selector);
      if (el) el.style.minHeight = '';
    });

    /* Messen */
    var maxH = 0;
    elements.forEach(function(card) {
      var el = card.querySelector(selector);
      if (el) {
        var h = el.offsetHeight;
        if (h > maxH) maxH = h;
      }
    });

    /* Anwenden */
    if (maxH > 0) {
      elements.forEach(function(card) {
        var el = card.querySelector(selector);
        if (el && el.offsetHeight < maxH - 2) {
          el.style.minHeight = maxH + 'px';
        }
      });
    }
  }

  function equalize() {
    var cards = Array.from(document.querySelectorAll(CARD_SEL));
    if (cards.length < 2) return;

    var rows = groupByRow(cards);

    rows.forEach(function(rowCards) {
      if (rowCards.length < 2) return;
      /* 1. Produktname-Bereich (Hersteller + Name) */
      equalizeRow(rowCards, '.card-body.pb-1');
      /* 2. Badge-Bereich */
      equalizeRow(rowCards, '.mrh-sf-badge-row');
      /* 3. Footer (Preis/Lager/Buttons) – damit Buttons auf gleicher Hoehe */
      equalizeRow(rowCards, '.card-footer');
    });
  }

  function debounce(fn, ms) {
    var timer;
    return function() {
      clearTimeout(timer);
      timer = setTimeout(fn, ms);
    };
  }

  var debouncedEqualize = debounce(equalize, 150);

  /**
   * Wartet bis alle sichtbaren Bilder in den Karten geladen sind,
   * dann fuehrt equalize() aus.
   */
  function equalizeAfterImages() {
    var cards = document.querySelectorAll(CARD_SEL);
    if (!cards.length) return;

    var imgs = [];
    cards.forEach(function(c) {
      var img = c.querySelector('img');
      if (img) imgs.push(img);
    });

    var pending = 0;
    imgs.forEach(function(img) {
      if (!img.complete) {
        pending++;
        img.addEventListener('load', function onLoad() {
          img.removeEventListener('load', onLoad);
          pending--;
          if (pending <= 0) equalize();
        });
        img.addEventListener('error', function onErr() {
          img.removeEventListener('error', onErr);
          pending--;
          if (pending <= 0) equalize();
        });
      }
    });

    /* Falls alle Bilder bereits geladen sind */
    if (pending === 0) {
      equalize();
    }
  }

  function init() {
    /* Sofort versuchen */
    equalize();

    /* Nach kurzem Delay nochmal (fuer lazy-loaded Bilder) */
    setTimeout(equalize, 300);
    setTimeout(equalize, 800);

    /* Bei window.load nochmal + Bilder-Check */
    window.addEventListener('load', function() {
      equalize();
      equalizeAfterImages();
      /* Sicherheits-Delays nach load */
      setTimeout(equalize, 500);
      setTimeout(equalize, 1500);
    });

    /* Bei Resize */
    window.addEventListener('resize', debounce(equalize, 200));

    /* MutationObserver fuer AJAX-Nachladen (Seedfinder-Filter) */
    var container = document.getElementById('products-grid') ||
                    document.getElementById('sf-results') ||
                    document.querySelector('.row');
    if (container) {
      var observer = new MutationObserver(function() {
        debouncedEqualize();
        /* Nach Mutation nochmal mit Delay (Bilder laden nach) */
        setTimeout(equalize, 500);
        setTimeout(equalizeAfterImages, 200);
      });
      observer.observe(container, { childList: true, subtree: true });
    }

    /* IntersectionObserver: equalize wenn Karten sichtbar werden */
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function(entries) {
        var anyVisible = entries.some(function(e) { return e.isIntersecting; });
        if (anyVisible) debouncedEqualize();
      }, { threshold: 0.1 });

      document.querySelectorAll(CARD_SEL).forEach(function(card) {
        io.observe(card);
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
