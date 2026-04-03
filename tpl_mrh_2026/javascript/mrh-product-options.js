/**
 * MRH Product Options v3.1
 * Vanilla JS – Preis-Updater fuer Produkt-Optionen
 *
 * Features:
 * - Aktualisiert BEIDE Preis-Anzeigen:
 *   1) pd_price[0] = Preis-Card oben rechts (oberhalb Optionen)
 *   2) pd_price[1] / #pd_puprice = "Jetzt nur ab" Box (unterhalb Optionen, ueber Warenkorb)
 * - Fallback: Baut JSON aus data-price/data-prefix wenn data-attrdata leer
 * - Stock-Status Anzeige (gruen/orange/rot)
 * - Kein jQuery erforderlich
 *
 * Aktualisiert: 2026-04-03
 */
(function () {
  'use strict';

  /* ── Hilfsfunktionen ── */

  function formatPrice(value) {
    return (Math.round(value * 100) / 100).toFixed(2).replace('.', ',');
  }

  function parsePrice(str) {
    if (!str) return 0;
    var cleaned = str
      .replace(/&nbsp;/g, '')
      .replace(/\u00A0/g, '')
      .replace(/EUR/gi, '')
      .replace(/CHF/gi, '')
      .replace(/\$/g, '')
      .replace(/[^\d,.\-]/g, '')
      .trim();
    cleaned = cleaned.replace(',', '.');
    return parseFloat(cleaned) || 0;
  }

  function extractCurrency(str) {
    if (!str) return { left: '', right: 'EUR' };
    var match = str.match(/(EUR|CHF|\$|USD)/i);
    if (match) {
      var currency = match[1].toUpperCase();
      var idx = str.indexOf(match[0]);
      var priceIdx = str.search(/\d/);
      if (idx < priceIdx) {
        return { left: currency, right: '' };
      }
      return { left: '', right: currency };
    }
    return { left: '', right: 'EUR' };
  }

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

    radios.forEach(function (radio) {
      if (firstPrice === 0) {
        var priceEl = radio.closest('.mrh-option-control')
          ? radio.closest('.mrh-option-control').querySelector('.mrh-current-price')
          : null;
        if (priceEl) {
          firstPrice = parsePrice(priceEl.textContent);
          currency = extractCurrency(priceEl.textContent);
        }
      }
    });

    radios.forEach(function (radio) {
      var existing = radio.getAttribute('data-attrdata');
      if (existing && existing.trim() !== '') return;

      var control = radio.closest('.mrh-option-control');
      var priceEl = control ? control.querySelector('.mrh-current-price') : null;
      var oldPriceEl = control ? control.querySelector('.mrh-old-price del') : null;
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
   * Berechnet den aktuellen Preis und aktualisiert ALLE Preis-Anzeigen
   */
  function calculatePrice(optionsContainer) {
    var checkedInputs = optionsContainer.querySelectorAll('input[type="radio"]:checked, select option:checked');
    var summe = 0;
    var attrvpevalue = 0;
    var data = null;

    checkedInputs.forEach(function (input) {
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
    var hasOldPrice = oprice !== gprice && oprice > 0;

    // VPE-Preis berechnen
    var newVpePrice = '';
    var vpevalue = parseFloat(data.vpevalue);
    if (data.vpevalue !== false && !isNaN(vpevalue) && vpevalue > 0) {
      var vpeBase = attrvpevalue > 0 ? attrvpevalue : vpevalue;
      newVpePrice = formatPrice((summe + gprice) / vpeBase);
    }

    var priceHtml = buildPriceHtml(symbolLeft, newPrice, symbolRight);
    var oldPriceHtml = buildPriceHtml(symbolLeft, oldPrice, symbolRight);

    // ═══════════════════════════════════════════════════════════════
    // ALLE pd_price Elemente aktualisieren
    // ═══════════════════════════════════════════════════════════════
    var allPdPrice = document.querySelectorAll('.pd_price');

    allPdPrice.forEach(function (pdPrice) {
      var newPriceEl = pdPrice.querySelector('.new_price');
      var oldPriceEl = pdPrice.querySelector('.old_price');
      var standardPriceEl = pdPrice.querySelector('.standard_price');
      var specialPriceEl = pdPrice.querySelector('.special_price');

      if (hasOldPrice) {
        // Sonderangebot: new_price + old_price anzeigen
        if (newPriceEl) {
          // small_price (Label "Jetzt nur ab") beibehalten
          var smallPrice = newPriceEl.querySelector('.small_price');
          var labelHtml = smallPrice ? smallPrice.outerHTML : '';
          newPriceEl.innerHTML = labelHtml + '\n                  ' + priceHtml + '\n                ';
        }
        if (oldPriceEl) {
          var oldSmallPrice = oldPriceEl.querySelector('.small_price');
          var oldLabelHtml = oldSmallPrice ? oldSmallPrice.outerHTML : '';
          oldPriceEl.innerHTML = oldLabelHtml + '\n                  <del>' + oldPriceHtml + '</del>\n                ';
        }
        // standard_price verstecken wenn vorhanden
        if (standardPriceEl) {
          standardPriceEl.style.display = 'none';
        }
        // special_price sichtbar machen
        if (specialPriceEl) {
          specialPriceEl.style.display = '';
        }
      } else {
        // Kein Sonderangebot: standard_price oder new_price aktualisieren
        if (standardPriceEl) {
          var stdSmallPrice = standardPriceEl.querySelector('.small_price');
          var stdLabelHtml = stdSmallPrice ? stdSmallPrice.outerHTML : '';
          standardPriceEl.innerHTML = stdLabelHtml + '\n                  ' + priceHtml + '\n                ';
          standardPriceEl.style.display = '';
        } else if (newPriceEl) {
          var npSmallPrice = newPriceEl.querySelector('.small_price');
          var npLabelHtml = npSmallPrice ? npSmallPrice.outerHTML : '';
          newPriceEl.innerHTML = npLabelHtml + '\n                  ' + priceHtml + '\n                ';
        }
        // old_price verstecken
        if (oldPriceEl) {
          oldPriceEl.style.display = 'none';
        }
      }
    });

    // ═══════════════════════════════════════════════════════════════
    // VPE-Preis aktualisieren (falls vorhanden)
    // ═══════════════════════════════════════════════════════════════
    var vpeEl = document.querySelector('.pd_vpe');
    if (vpeEl && newVpePrice && data.vpevalue !== false) {
      vpeEl.innerHTML = buildPriceHtml(symbolLeft, newVpePrice, symbolRight) + (data.protext || '') + (data.vpetext || '');
    }

    // ═══════════════════════════════════════════════════════════════
    // Preis-Zusammenfassung innerhalb der Optionen (cuPrice/cuVpePrice)
    // ═══════════════════════════════════════════════════════════════
    var cuPrice = optionsContainer.querySelector('.cuPrice');
    if (cuPrice) {
      cuPrice.innerHTML = priceHtml;
    }

    var cuVpePrice = optionsContainer.querySelector('.cuVpePrice');
    if (cuVpePrice) {
      if (newVpePrice && data.vpevalue !== false) {
        cuVpePrice.innerHTML = buildPriceHtml(symbolLeft, newVpePrice, symbolRight) + (data.protext || '') + (data.vpetext || '');
      } else {
        cuVpePrice.innerHTML = '';
      }
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

  /* ── Globale Funktion fuer Smarty-Kompatibilitaet ── */
  window.PriceUpdaterReady = function () {
    initOptions();
  };

  /* ── DOM Ready ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initOptions();
    });
  } else {
    initOptions();
  }
})();
