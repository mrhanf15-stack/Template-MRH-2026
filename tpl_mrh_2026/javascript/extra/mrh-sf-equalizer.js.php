<?php
/* -----------------------------------------------------------------------------------------
   $Id: mrh-sf-equalizer.js.php 1.3.0 2026-04-22 Mr. Hanf $
   MRH Seedfinder Row Equalizer
   Gleicht die Hoehe von Produktname, Badges und Footer in Seedfinder-Listings an,
   damit alle Karten in einer Reihe strukturiert aussehen.
   Tabelle bleibt flexibel (unterschiedliche Zeilenanzahl OK).
   Footer (Preis/Lager/Buttons) wird angeglichen, damit Buttons auf gleicher Hoehe.
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
   MRH Seedfinder Row Equalizer v1.3.0
   Gleicht pro Kartenreihe an:
   1. Produktname (.card-body.pb-1) – immer gleiche Hoehe
   2. Badges (.mrh-sf-badge-row)   – immer gleiche Hoehe
   3. Footer (.card-footer)        – immer gleiche Hoehe
      (Lager-Info macht manche Footer hoeher)
   Tabelle bleibt flexibel.
   ============================================================ */
(function() {
  'use strict';

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
    // Reset
    elements.forEach(function(card) {
      var el = card.querySelector(selector);
      if (el) el.style.minHeight = '';
    });

    // Messen
    var maxH = 0;
    elements.forEach(function(card) {
      var el = card.querySelector(selector);
      if (el) {
        var h = el.offsetHeight;
        if (h > maxH) maxH = h;
      }
    });

    // Anwenden
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
    var cards = Array.from(document.querySelectorAll('.card.h-100'));
    if (cards.length < 2) return;

    var rows = groupByRow(cards);

    rows.forEach(function(rowCards) {
      if (rowCards.length < 2) return;
      // 1. Produktname-Bereich (Hersteller + Name)
      equalizeRow(rowCards, '.card-body.pb-1');
      // 2. Badge-Bereich
      equalizeRow(rowCards, '.mrh-sf-badge-row');
      // 3. Footer (Preis/Lager/Buttons) – damit Buttons auf gleicher Hoehe
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

  function init() {
    equalize();
    window.addEventListener('load', equalize);
    window.addEventListener('resize', debounce(equalize, 200));
    var container = document.getElementById('sf-results') ||
                    document.querySelector('.row');
    if (container) {
      var observer = new MutationObserver(debounce(equalize, 150));
      observer.observe(container, { childList: true, subtree: true });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
