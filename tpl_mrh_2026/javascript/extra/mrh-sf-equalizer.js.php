<?php
/* -----------------------------------------------------------------------------------------
   $Id: mrh-sf-equalizer.js.php 1.0.0 2026-04-22 Mr. Hanf $
   MRH Seedfinder Row Equalizer
   Gleicht die Hoehe von Produktname, Badges und Tabelle in Seedfinder-Listings an,
   damit alle Karten in einer Reihe auf gleicher Hoehe starten.
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
   MRH Seedfinder Row Equalizer v1.0.0
   Gleicht card-body (Name) Hoehen pro Kartenreihe an.
   ============================================================ */
(function() {
  'use strict';

  /**
   * Gruppiert Elemente nach ihrer vertikalen Position (Reihe).
   * Elemente mit aehnlichem offsetTop (+/- tolerance) gehoeren zur selben Reihe.
   */
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

  /**
   * Setzt min-height auf alle Elemente in einer Reihe basierend auf dem hoechsten Element.
   */
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

    // Anwenden (nur wenn Unterschied > 5px)
    if (maxH > 0) {
      elements.forEach(function(card) {
        var el = card.querySelector(selector);
        if (el && el.offsetHeight < maxH - 2) {
          el.style.minHeight = maxH + 'px';
        }
      });
    }
  }

  /**
   * Hauptfunktion: Alle Karten-Reihen equalisieren
   */
  function equalize() {
    var cards = Array.from(document.querySelectorAll('.card.h-100'));
    if (cards.length < 2) return;

    var rows = groupByRow(cards);

    rows.forEach(function(rowCards) {
      if (rowCards.length < 2) return;
      // 1. Produktname-Bereich (erster .card-body)
      equalizeRow(rowCards, '.card-body.pb-1');
      // 2. Badge-Bereich
      equalizeRow(rowCards, '.mrh-sf-badge-row');
    });
  }

  /**
   * Debounce-Helfer
   */
  function debounce(fn, ms) {
    var timer;
    return function() {
      clearTimeout(timer);
      timer = setTimeout(fn, ms);
    };
  }

  // Init: Nach DOM-Ready und Bildern laden
  function init() {
    // Sofort nach DOM-Ready
    equalize();
    // Nochmal nach Bildern (koennen Layout verschieben)
    window.addEventListener('load', equalize);
    // Bei Resize neu berechnen
    window.addEventListener('resize', debounce(equalize, 200));
    // MutationObserver fuer AJAX-Nachladen (Seedfinder Filter)
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
