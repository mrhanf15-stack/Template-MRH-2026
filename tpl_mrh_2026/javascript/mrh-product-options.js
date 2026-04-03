/**
 * MRH Product Options v3.0
 * Vanilla JS – Preis-Updater fuer Produkt-Optionen
 * 
 * Features:
 * - Aktualisiert Preis oben (Summarybox) + Preis-Zusammenfassung
 * - Fallback: Baut JSON aus data-price/data-prefix wenn data-attrdata leer
 * - Stock-Status Anzeige (gruen/orange/rot)
 * - Kein jQuery erforderlich
 * 
 * Aktualisiert: 2026-04-03
 */
(function () {
  'use strict';

  /* ── Hilfsfunktionen ── */

  /**
   * Formatiert eine Zahl als Preis-String (Komma als Dezimaltrenner)
   */
  function formatPrice(value) {
    return (Math.round(value * 100) / 100).toFixed(2).replace('.', ',');
  }

  /**
   * Parst einen Preis-String zurueck in eine Zahl
   * z.B. "34,01 EUR" → 34.01, "&nbsp;12,87 EUR" → 12.87
   */
  function parsePrice(str) {
    if (!str) return 0;
    // Entferne HTML-Entities, Waehrung, Leerzeichen
    var cleaned = str
      .replace(/&nbsp;/g, '')
      .replace(/\u00A0/g, '')
      .replace(/EUR/gi, '')
      .replace(/CHF/gi, '')
      .replace(/\$/g, '')
      .replace(/[^\d,.\-]/g, '')
      .trim();
    // Komma als Dezimaltrenner
    cleaned = cleaned.replace(',', '.');
    return parseFloat(cleaned) || 0;
  }

  /**
   * Extrahiert die Waehrung aus einem Preis-String
   */
  function extractCurrency(str) {
    if (!str) return { left: '', right: 'EUR' };
    var match = str.match(/(EUR|CHF|\$|USD)/i);
    if (match) {
      var currency = match[1].toUpperCase();
      // Pruefen ob Waehrung links oder rechts steht
      var idx = str.indexOf(match[0]);
      var priceIdx = str.search(/\d/);
      if (idx < priceIdx) {
        return { left: currency, right: '' };
      }
      return { left: '', right: currency };
    }
    return { left: '', right: 'EUR' };
  }

  /**
   * Baut den formatierten Preis-String mit Waehrungssymbol
   */
  function buildPriceHtml(symbolLeft, priceStr, symbolRight) {
    var parts = [];
    if (symbolLeft) parts.push(symbolLeft + '\u00A0');
    parts.push(priceStr);
    if (symbolRight) parts.push('\u00A0' + symbolRight);
    return parts.join('');
  }

  /**
   * Baut attrdata JSON aus data-Attributen wenn JSON_ATTRDATA leer ist
   */
  function buildAttrDataFromHtml(container) {
    var pid = container.id.replace('optionen', '');
    var radios = container.querySelectorAll('input[type="radio"].mrh-option-radio');
    var firstPrice = 0;
    var currency = { left: '', right: 'EUR' };

    // Erste Option finden um Basispreis zu ermitteln
    radios.forEach(function (radio) {
      if (firstPrice === 0) {
        var priceEl = radio.closest('.mrh-option-control').querySelector('.mrh-current-price');
        if (priceEl) {
          firstPrice = parsePrice(priceEl.textContent);
          currency = extractCurrency(priceEl.textContent);
        }
      }
    });

    // Fuer jede Option attrdata setzen
    radios.forEach(function (radio) {
      var existing = radio.getAttribute('data-attrdata');
      if (existing && existing.trim() !== '') return; // Bereits vorhanden

      var priceEl = radio.closest('.mrh-option-control').querySelector('.mrh-current-price');
      var oldPriceEl = radio.closest('.mrh-option-control').querySelector('.mrh-old-price del');
      var currentPrice = priceEl ? parsePrice(priceEl.textContent) : 0;
      var oldPrice = oldPriceEl ? parsePrice(oldPriceEl.textContent) : 0;

      var prefix = radio.getAttribute('data-prefix') || '=';
      if (!prefix || prefix.trim() === '') prefix = '=';

      var attrData = {
        pid: parseInt(pid) || 0,
        gprice: firstPrice,
        oprice: oldPrice > 0 ? oldPrice : firstPrice,
        cleft: currency.left,
        cright: currency.right,
        prefix: prefix.trim() || '=',
        aprice: currentPrice,
        vpetext: 'VPE',
        vpevalue: false,
        attrvpevalue: false,
        onlytext: '',
        protext: ' / ',
        insteadtext: ''
      };

      radio.setAttribute('data-attrdata', JSON.stringify(attrData));
    });
  }

  /* ── Haupt-Logik ── */

  /**
   * Berechnet den aktuellen Preis basierend auf den gewaehlten Optionen
   */
  function calculatePrice(optionsContainer) {
    var pid = optionsContainer.id.replace('optionen', '');
    var checkedInputs = optionsContainer.querySelectorAll('input[type="radio"]:checked, select option:checked');
    var summe = 0;
    var attrvpevalue = 0;
    var data = null;

    checkedInputs.forEach(function (input) {
      // Pruefen ob das Element in einer versteckten pmatrix-Zeile liegt
      var pmatrixParent = input.closest('[id^="pmatrix_v"]');
      if (pmatrixParent && pmatrixParent.style && pmatrixParent.style.display === 'none') {
        return;
      }

      var rawData = input.getAttribute('data-attrdata');
      if (!rawData || rawData.trim() === '') return;

      try {
        data = JSON.parse(rawData);
      } catch (e) {
        return;
      }

      if (data.aprice !== 0 && data.aprice !== '0') {
        var aprice = parseFloat(data.aprice) || 0;
        if (data.prefix === '-') {
          summe -= aprice;
        } else if (data.prefix === '+') {
          summe += aprice;
        } else if (data.prefix === '=') {
          summe += aprice - (parseFloat(data.gprice) || 0);
        }
      }
      if (data.attrvpevalue) {
        attrvpevalue += parseFloat(data.attrvpevalue) || 0;
      }
    });

    if (!data) return;

    var gprice = parseFloat(data.gprice) || 0;
    var oprice = parseFloat(data.oprice) || 0;
    var symbolLeft = data.cleft || '';
    var symbolRight = data.cright || '';

    var newPrice = formatPrice(summe + gprice);
    var oldPrice = formatPrice(summe + oprice);

    // VPE-Preis berechnen
    var newVpePrice = '';
    var vpevalue = parseFloat(data.vpevalue);
    if (data.vpevalue !== false && !isNaN(vpevalue) && vpevalue > 0) {
      var vpeBase = attrvpevalue > 0 ? attrvpevalue : vpevalue;
      newVpePrice = formatPrice((summe + gprice) / vpeBase);
    }

    // 1. Preis-Zusammenfassung aktualisieren (unterhalb der Optionen)
    var cuPrice = optionsContainer.querySelector('.cuPrice');
    if (cuPrice) {
      cuPrice.innerHTML = buildPriceHtml(symbolLeft, newPrice, symbolRight);
    }

    var cuVpePrice = optionsContainer.querySelector('.cuVpePrice');
    if (cuVpePrice) {
      if (newVpePrice && data.vpevalue !== false) {
        cuVpePrice.innerHTML = buildPriceHtml(symbolLeft, newVpePrice, symbolRight) + (data.protext || '') + (data.vpetext || '');
      } else {
        cuVpePrice.innerHTML = '';
      }
    }

    // 2. Preis oben in der Summarybox aktualisieren
    var summaryStandard = document.querySelector('.pd_summarybox .pd_price .standard_price');
    var summaryNew = document.querySelector('.pd_summarybox .pd_price .new_price');
    var summaryOld = document.querySelector('.pd_summarybox .pd_price .old_price');
    var summaryVpe = document.querySelector('.pd_summarybox .pd_vpe');

    // Auch den pd_price direkt aktualisieren (fuer .at Layout)
    var pdPrice = document.querySelector('#pd_puprice .standard_price');
    if (pdPrice) {
      // Behalte den "ab" Text bei, ersetze nur den Preis
      var smallPrice = pdPrice.querySelector('.small_price');
      var abText = smallPrice ? smallPrice.outerHTML : '';
      pdPrice.innerHTML = abText + buildPriceHtml(symbolLeft, newPrice, symbolRight);
    }

    if (summaryStandard && !pdPrice) {
      summaryStandard.innerHTML = buildPriceHtml(symbolLeft, newPrice, symbolRight);
    }
    if (summaryNew) {
      summaryNew.innerHTML = (data.onlytext || '') + buildPriceHtml(symbolLeft, newPrice, symbolRight);
    }
    if (summaryOld && oprice !== gprice) {
      summaryOld.innerHTML = (data.insteadtext || '') + buildPriceHtml(symbolLeft, oldPrice, symbolRight);
    }
    if (summaryVpe && newVpePrice && data.vpevalue !== false) {
      summaryVpe.innerHTML = buildPriceHtml(symbolLeft, newVpePrice, symbolRight) + (data.protext || '') + (data.vpetext || '');
    }
  }

  /**
   * Aktualisiert den aktiven Zustand der Option-Items
   */
  function updateActiveState(optionsContainer) {
    var items = optionsContainer.querySelectorAll('.mrh-option-item');
    items.forEach(function (item) {
      var radio = item.querySelector('.mrh-option-radio');
      if (radio && radio.checked) {
        item.classList.add('mrh-option-active');
        item.classList.add('is-active');
        item.setAttribute('aria-checked', 'true');
      } else {
        item.classList.remove('mrh-option-active');
        item.classList.remove('is-active');
        item.setAttribute('aria-checked', 'false');
      }
    });
  }

  /**
   * Globale Funktion fuer onclick im Template
   */
  window.mrhSelectOption = function (el) {
    if (el.classList.contains('mrh-option-unavailable')) return;
    var radio = el.querySelector('.mrh-option-radio');
    if (radio && !radio.disabled) {
      radio.checked = true;
      radio.dispatchEvent(new Event('change', { bubbles: true }));
    }
  };

  /**
   * Initialisiert die Options-Interaktivitaet
   */
  function initOptions() {
    var containers = document.querySelectorAll('[id^="optionen"]');

    containers.forEach(function (container) {
      // Fallback: JSON aus HTML bauen wenn data-attrdata leer
      buildAttrDataFromHtml(container);

      // Klick auf Option-Item = Radio auswaehlen
      var items = container.querySelectorAll('.mrh-option-item');
      items.forEach(function (item) {
        item.addEventListener('click', function (e) {
          if (item.classList.contains('mrh-option-unavailable')) return;
          if (e.target.classList.contains('mrh-option-radio')) return;

          var radio = item.querySelector('.mrh-option-radio');
          if (radio && !radio.disabled) {
            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });

        item.addEventListener('keydown', function (e) {
          if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            item.click();
          }
        });
      });

      // Change-Event auf Radios
      var radios = container.querySelectorAll('input[type="radio"]');
      radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
          updateActiveState(container);
          calculatePrice(container);
        });
      });

      // Select-Dropdowns (falls vorhanden)
      var selects = container.querySelectorAll('select');
      selects.forEach(function (sel) {
        sel.addEventListener('change', function () {
          calculatePrice(container);
        });
      });

      // Initial: aktiven Zustand setzen + Preis berechnen
      updateActiveState(container);
      calculatePrice(container);

      // Preis-Zusammenfassung sichtbar machen
      var summary = container.querySelector('.mrh-price-summary');
      if (summary) {
        summary.style.display = 'block';
      }
    });
  }

  /**
   * Initialisiert die Stock-Legende Info-Modal
   */
  function initStockLegend() {
    // Bootstrap Modal wird automatisch ueber data-bs-toggle initialisiert
    // Keine zusaetzliche JS-Initialisierung noetig
  }

  /* ── Globale Funktion fuer Smarty-Kompatibilitaet ── */
  window.PriceUpdaterReady = function () {
    initOptions();
  };

  /* ── DOM Ready ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initOptions();
      initStockLegend();
    });
  } else {
    initOptions();
    initStockLegend();
  }
})();
