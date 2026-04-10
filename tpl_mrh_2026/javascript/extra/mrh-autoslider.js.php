<?php
/* ============================================================
   MRH Auto-Slider – Vanilla JS (kein jQuery, kein Owl Carousel)
   ============================================================
   Ersetzt Owl Carousel für .product-carousel Container.
   Wird automatisch über auto_include() in general_bottom.js.php
   geladen und bei COMPRESS_JAVASCRIPT komprimiert.

   Features:
   - Auto-Slide (5s), Pfeil-Buttons, Hover-Pause
   - Touch-Swipe, Loop, Responsive (5/3/2 Spalten)
   - Dynamischer Track-Wrapper + Pfeil-Buttons
   - Kompatibel mit bestehenden Box-Templates
   ============================================================ */
?>
<script>
/**
 * MRH Auto-Slider v2.0 – Vanilla JS (kein jQuery)
 * Ersatz für Owl Carousel – Bootstrap 5.3 kompatibel
 * Stand: 2026-04-10
 *
 * Initialisiert automatisch alle .product-carousel Elemente.
 * Wrapping: Erstellt Track + Pfeile dynamisch um bestehende Listingboxen.
 */
(function () {
  'use strict';

  var AUTO_DELAY  = 5000;
  var SWIPE_THRESHOLD = 40;
  var GAP = 16; /* 1rem gap */

  /* --------------------------------------------------------
     CSS injizieren (einmalig)
     -------------------------------------------------------- */
  function injectCSS() {
    if (document.getElementById('mrh-autoslider-css')) return;
    var style = document.createElement('style');
    style.id = 'mrh-autoslider-css';
    style.textContent = [
      /* Container */
      '.mrh-as { position: relative; overflow: hidden; padding: 0 40px; }',

      /* Track */
      '.mrh-as-track {',
      '  display: flex; gap: ' + GAP + 'px;',
      '  transition: transform 0.45s cubic-bezier(0.25, 0.1, 0.25, 1);',
      '  will-change: transform;',
      '}',

      /* Items: 5 Spalten Desktop */
      '.mrh-as-track > .listingbox {',
      '  flex: 0 0 calc((100% - ' + (GAP * 4) + 'px) / 5);',
      '  max-width: calc((100% - ' + (GAP * 4) + 'px) / 5);',
      '  box-sizing: border-box;',
      '}',

      /* Pfeil-Buttons */
      '.mrh-as-btn {',
      '  position: absolute; top: 50%; transform: translateY(-50%);',
      '  z-index: 10; width: 36px; height: 36px;',
      '  border: none; border-radius: 50%;',
      '  background: rgba(45, 122, 58, 0.85); color: #fff;',
      '  font-size: 1rem; cursor: pointer;',
      '  display: flex; align-items: center; justify-content: center;',
      '  transition: background 0.2s, box-shadow 0.2s;',
      '  box-shadow: 0 2px 8px rgba(0,0,0,0.15);',
      '}',
      '.mrh-as-btn:hover { background: rgba(45, 122, 58, 1); box-shadow: 0 3px 12px rgba(0,0,0,0.25); }',
      '.mrh-as-prev { left: 2px; }',
      '.mrh-as-next { right: 2px; }',

      /* Responsive: 3 Spalten Tablet */
      '@media (max-width: 999px) {',
      '  .mrh-as-track > .listingbox {',
      '    flex: 0 0 calc((100% - ' + (GAP * 2) + 'px) / 3);',
      '    max-width: calc((100% - ' + (GAP * 2) + 'px) / 3);',
      '  }',
      '  .mrh-as { padding: 0 36px; }',
      '}',

      /* Responsive: 2 Spalten Mobile */
      '@media (max-width: 599px) {',
      '  .mrh-as-track > .listingbox {',
      '    flex: 0 0 calc((100% - ' + GAP + 'px) / 2);',
      '    max-width: calc((100% - ' + GAP + 'px) / 2);',
      '  }',
      '  .mrh-as { padding: 0 30px; }',
      '  .mrh-as-btn { width: 28px; height: 28px; font-size: 0.8rem; }',
      '}',

      /* Owl Carousel Klassen neutralisieren */
      '.product-carousel.owl-carousel { display: block !important; }',
      '.product-carousel .owl-stage-outer,',
      '.product-carousel .owl-stage,',
      '.product-carousel .owl-item { all: unset; }'

    ].join('\n');
    document.head.appendChild(style);
  }

  /* --------------------------------------------------------
     Einzelnen Slider initialisieren
     -------------------------------------------------------- */
  function initSlider(el) {
    /* Items sammeln (direkte .listingbox Kinder) */
    var items = [];
    var children = el.children;
    for (var i = 0; i < children.length; i++) {
      if (children[i].classList.contains('listingbox') ||
          children[i].classList.contains('lb_inner')) {
        items.push(children[i]);
      }
    }
    if (!items.length) return;

    /* Container-Klasse setzen */
    el.classList.add('mrh-as');

    /* Track-Wrapper erstellen */
    var track = document.createElement('div');
    track.className = 'mrh-as-track';

    /* Items in Track verschieben */
    for (var j = 0; j < items.length; j++) {
      track.appendChild(items[j]);
    }
    el.appendChild(track);

    /* Pfeil-Buttons erstellen */
    var prevBtn = document.createElement('button');
    prevBtn.className = 'mrh-as-btn mrh-as-prev';
    prevBtn.setAttribute('aria-label', 'Vorherige Produkte');
    prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';

    var nextBtn = document.createElement('button');
    nextBtn.className = 'mrh-as-btn mrh-as-next';
    nextBtn.setAttribute('aria-label', 'Nächste Produkte');
    nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';

    el.appendChild(prevBtn);
    el.appendChild(nextBtn);

    /* Slider-Logik */
    var idx = 0;
    var timer = null;
    var paused = false;
    var totalItems = items.length;

    function getVisibleCount() {
      var w = el.offsetWidth - 80; /* abzüglich Pfeil-Padding */
      var itemW = items[0].offsetWidth + GAP;
      return Math.max(1, Math.floor(w / itemW));
    }

    function getItemWidth() {
      return items[0].offsetWidth + GAP;
    }

    function maxIndex() {
      var m = totalItems - getVisibleCount();
      return m > 0 ? m : 0;
    }

    function slide(newIdx) {
      idx = newIdx;
      if (idx > maxIndex()) idx = 0;
      if (idx < 0) idx = maxIndex();
      track.style.transform = 'translateX(-' + (idx * getItemWidth()) + 'px)';
    }

    function next() { slide(idx + 1); }
    function prev() { slide(idx - 1); }

    function startAuto() {
      stopAuto();
      timer = setInterval(function () {
        if (!paused) next();
      }, AUTO_DELAY);
    }

    function stopAuto() {
      if (timer) { clearInterval(timer); timer = null; }
    }

    /* Events */
    prevBtn.addEventListener('click', function () { prev(); startAuto(); });
    nextBtn.addEventListener('click', function () { next(); startAuto(); });

    el.addEventListener('mouseenter', function () { paused = true; });
    el.addEventListener('mouseleave', function () { paused = false; });

    /* Touch-Swipe */
    var touchStartX = 0;
    track.addEventListener('touchstart', function (e) {
      paused = true;
      touchStartX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', function (e) {
      var diff = touchStartX - e.changedTouches[0].clientX;
      if (diff > SWIPE_THRESHOLD) next();
      else if (diff < -SWIPE_THRESHOLD) prev();
      paused = false;
      startAuto();
    }, { passive: true });

    /* Resize: Position korrigieren */
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () { slide(idx); }, 150);
    });

    /* Pfeile ausblenden wenn weniger Items als sichtbar */
    function checkArrows() {
      var show = totalItems > getVisibleCount();
      prevBtn.style.display = show ? '' : 'none';
      nextBtn.style.display = show ? '' : 'none';
    }
    checkArrows();
    window.addEventListener('resize', function () {
      setTimeout(checkArrows, 200);
    });

    /* Auto-Slide starten */
    startAuto();
  }

  /* --------------------------------------------------------
     Alle Slider auf der Seite initialisieren
     -------------------------------------------------------- */
  function initAll() {
    injectCSS();

    /* Primär: .product-carousel (aus Box-Templates) */
    var carousels = document.querySelectorAll('.product-carousel');
    for (var i = 0; i < carousels.length; i++) {
      initSlider(carousels[i]);
    }

    /* Fallback: .mrh-autoslider (für manuell gesetzte Slider) */
    var manualSliders = document.querySelectorAll('.mrh-autoslider');
    for (var j = 0; j < manualSliders.length; j++) {
      if (!manualSliders[j].classList.contains('mrh-as')) {
        initSlider(manualSliders[j]);
      }
    }
  }

  /* DOM ready */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  /* Global verfügbar machen für spätes Nachladen */
  window.MRHAutoSlider = { init: initAll, initSlider: initSlider };
})();
</script>
