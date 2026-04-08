/**
 * MRH 2026 – Pagination Vanilla JS
 * Template: tpl_mrh_2026
 * Version: 2.0.0
 * Datum: 08. April 2026
 *
 * Features:
 *   1. Scroll-to-Products nach Seitenwechsel (URL enthält ?page=)
 *   2. Keyboard-Navigation (Pfeiltasten links/rechts)
 *   3. Prefetch nächste Seite bei Hover (Performance)
 *
 * Kein jQuery. Kein Framework. Reines Vanilla JS.
 * Wird am Ende der Seite geladen (defer oder bottom).
 */

(function () {
  'use strict';

  /* ─── Konfiguration ─── */
  var CONFIG = {
    scrollTarget:    '#products-listing, #product_listing, .mrh-product-listing, #content',
    scrollOffset:    -20,         /* px über dem Ziel */
    scrollBehavior:  'smooth',    /* 'smooth' oder 'auto' */
    prefetchEnabled: true,
    keyNavEnabled:   true
  };


  /* ─── 1. Scroll-to-Products ─── */
  function scrollToProducts() {
    var params = new URLSearchParams(window.location.search);
    if (!params.has('page') || params.get('page') === '1') return;

    var selectors = CONFIG.scrollTarget.split(',');
    var target = null;

    for (var i = 0; i < selectors.length; i++) {
      target = document.querySelector(selectors[i].trim());
      if (target) break;
    }

    if (!target) return;

    var rect = target.getBoundingClientRect();
    var scrollY = window.pageYOffset || document.documentElement.scrollTop;
    var targetY = rect.top + scrollY + CONFIG.scrollOffset;

    /* Kurze Verzögerung damit der Browser das Layout berechnet hat */
    setTimeout(function () {
      window.scrollTo({
        top: Math.max(0, targetY),
        behavior: CONFIG.scrollBehavior
      });
    }, 100);
  }


  /* ─── 2. Keyboard-Navigation ─── */
  function initKeyNav() {
    if (!CONFIG.keyNavEnabled) return;

    document.addEventListener('keydown', function (e) {
      /* Nur reagieren wenn kein Input/Textarea/Select fokussiert */
      var tag = document.activeElement ? document.activeElement.tagName : '';
      if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') return;

      var link = null;

      if (e.key === 'ArrowLeft') {
        link = document.querySelector('.mrh-pagination__item--prev a.mrh-pagination__link');
      } else if (e.key === 'ArrowRight') {
        link = document.querySelector('.mrh-pagination__item--next a.mrh-pagination__link');
      }

      if (link && link.href) {
        e.preventDefault();
        link.click();
      }
    });
  }


  /* ─── 3. Prefetch nächste Seite ─── */
  function initPrefetch() {
    if (!CONFIG.prefetchEnabled) return;
    if (!('IntersectionObserver' in window)) return;

    var nextLink = document.querySelector('.mrh-pagination__item--next a.mrh-pagination__link');
    if (!nextLink || !nextLink.href) return;

    var prefetched = false;

    nextLink.addEventListener('mouseenter', function () {
      if (prefetched) return;
      prefetched = true;

      var link = document.createElement('link');
      link.rel = 'prefetch';
      link.href = nextLink.href;
      link.as = 'document';
      document.head.appendChild(link);
    }, { once: true });
  }


  /* ─── Init ─── */
  function init() {
    scrollToProducts();
    initKeyNav();
    initPrefetch();
  }

  /* DOM Ready */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
