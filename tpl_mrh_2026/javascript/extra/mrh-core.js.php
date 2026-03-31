<?php
/* ============================================================
   MRH 2026 – Core JavaScript (Vanilla JS)
   ============================================================
   Alle neuen MRH-Funktionen in reinem Vanilla JS.
   Phase 1: Koexistenz mit jQuery (kein jQuery verwenden!)
   Phase 2: jQuery komplett entfernen
   
   Wird automatisch über auto_include() in general_bottom.js.php
   geladen und bei COMPRESS_JAVASCRIPT komprimiert.
   ============================================================ */
?>
<script>
/* ============================================================
   MRH 2026 Core – v1.0.0
   Vanilla JS – kein jQuery!
   ============================================================ */
(function() {
  'use strict';

  /* ----------------------------------------------------------
     NAMESPACE: Alle MRH-Funktionen unter window.MRH
     ---------------------------------------------------------- */
  window.MRH = window.MRH || {};

  /* ----------------------------------------------------------
     UTILITY: Hilfs-Funktionen
     ---------------------------------------------------------- */
  MRH.Utils = {
    /**
     * Sicheres querySelector mit Fallback
     */
    qs: function(selector, parent) {
      return (parent || document).querySelector(selector);
    },

    /**
     * Sicheres querySelectorAll als Array
     */
    qsa: function(selector, parent) {
      return Array.from((parent || document).querySelectorAll(selector));
    },

    /**
     * Event-Delegation (wie jQuery .on())
     */
    on: function(parent, event, selector, handler) {
      var el = typeof parent === 'string' ? document.querySelector(parent) : parent;
      if (!el) return;
      el.addEventListener(event, function(e) {
        var target = e.target.closest(selector);
        if (target && el.contains(target)) {
          handler.call(target, e);
        }
      });
    },

    /**
     * Cookie setzen
     */
    setCookie: function(name, value, days) {
      var d = new Date();
      d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
      document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
    },

    /**
     * Cookie lesen
     */
    getCookie: function(name) {
      var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? match[2] : '';
    },

    /**
     * Debounce (für Scroll/Resize Events)
     */
    debounce: function(fn, delay) {
      var timer;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() { fn.apply(context, args); }, delay);
      };
    },

    /**
     * Throttle (für häufige Events)
     */
    throttle: function(fn, limit) {
      var waiting = false;
      return function() {
        if (!waiting) {
          fn.apply(this, arguments);
          waiting = true;
          setTimeout(function() { waiting = false; }, limit);
        }
      };
    }
  };

  /* ----------------------------------------------------------
     01 TOPBAR: Marquee-Effekt für Mobile (optional)
     ---------------------------------------------------------- */
  MRH.Topbar = {
    init: function() {
      var topbar = MRH.Utils.qs('#mrh-topbar');
      if (!topbar) return;
      // Topbar ist rein CSS – hier nur für zukünftige
      // Erweiterungen (z.B. rotierende Nachrichten)
    }
  };

  /* ----------------------------------------------------------
     02 FREE SHIPPING BAR: Warenkorb-Fortschritt
     ---------------------------------------------------------- */
  MRH.ShippingBar = {
    threshold: <?php echo defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER') 
                      ? (float)MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER 
                      : 50; ?>,

    init: function() {
      var bar = MRH.Utils.qs('#mrh-shipping-bar');
      if (!bar) return;
      this.bar = bar;
      this.fill = MRH.Utils.qs('.mrh-progress-fill', bar);
      this.text = MRH.Utils.qs('.mrh-shipping-text', bar);
      this.update();
    },

    /**
     * Fortschritt aktualisieren basierend auf Warenkorb-Wert
     * Wird aufgerufen nach AJAX-Cart-Updates
     */
    update: function(cartTotal) {
      if (!this.fill) return;
      cartTotal = cartTotal || 0;
      var pct = Math.min(100, Math.round((cartTotal / this.threshold) * 100));
      this.fill.style.width = pct + '%';
      
      if (pct >= 100 && this.text) {
        this.text.innerHTML = '<i class="fas fa-check-circle"></i> Gratis Versand!';
        this.fill.style.backgroundColor = 'var(--mrh-green-accent)';
      }
    }
  };

  /* ----------------------------------------------------------
     03 STICKY HEADER
     ---------------------------------------------------------- */
  MRH.StickyHeader = {
    lastScroll: 0,
    headerHeight: 0,

    init: function() {
      var header = MRH.Utils.qs('#main-header');
      if (!header) return;
      this.header = header;
      this.headerHeight = header.offsetHeight;

      // Sticky-Klasse nur bei Scroll nach unten > Headerhöhe
      window.addEventListener('scroll', MRH.Utils.throttle(this.onScroll.bind(this), 100), { passive: true });
    },

    onScroll: function() {
      var st = window.pageYOffset || document.documentElement.scrollTop;
      
      if (st > this.headerHeight + 100) {
        // Scrolled past header
        if (!this.header.classList.contains('mrh-sticky')) {
          this.header.classList.add('mrh-sticky');
        }
        // Show/Hide basierend auf Scroll-Richtung
        if (st > this.lastScroll && st > this.headerHeight + 200) {
          // Scroll Down → Header verstecken
          this.header.classList.add('mrh-sticky-hidden');
        } else {
          // Scroll Up → Header zeigen
          this.header.classList.remove('mrh-sticky-hidden');
        }
      } else {
        this.header.classList.remove('mrh-sticky', 'mrh-sticky-hidden');
      }
      
      this.lastScroll = st;
    }
  };

  /* ----------------------------------------------------------
     04 BACK TO TOP BUTTON
     ---------------------------------------------------------- */
  MRH.BackToTop = {
    init: function() {
      // Button erstellen
      var btn = document.createElement('button');
      btn.id = 'mrh-back-to-top';
      btn.className = 'mrh-back-to-top';
      btn.setAttribute('aria-label', 'Nach oben scrollen');
      btn.innerHTML = '<i class="fas fa-chevron-up"></i>';
      document.body.appendChild(btn);
      this.btn = btn;

      // Klick-Handler
      btn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // Sichtbarkeit bei Scroll
      window.addEventListener('scroll', MRH.Utils.throttle(this.toggle.bind(this), 200), { passive: true });
    },

    toggle: function() {
      var st = window.pageYOffset || document.documentElement.scrollTop;
      if (st > 400) {
        this.btn.classList.add('visible');
      } else {
        this.btn.classList.remove('visible');
      }
    }
  };

  /* ----------------------------------------------------------
     05 LAZY LOADING: Native + Fallback
     ---------------------------------------------------------- */
  MRH.LazyLoad = {
    init: function() {
      // Native lazy loading für Browser die es unterstützen
      var images = MRH.Utils.qsa('img[loading="lazy"]');
      if ('loading' in HTMLImageElement.prototype) {
        // Browser unterstützt native lazy loading – nichts zu tun
        return;
      }
      // Fallback: IntersectionObserver
      if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var img = entry.target;
              if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
              }
              observer.unobserve(img);
            }
          });
        }, { rootMargin: '200px' });

        images.forEach(function(img) { observer.observe(img); });
      }
    }
  };

  /* ----------------------------------------------------------
     06 ACCESSIBILITY: Keyboard-Navigation + Focus-Trap
     ---------------------------------------------------------- */
  MRH.A11y = {
    init: function() {
      // Skip-to-Content Link
      this.addSkipLink();
      // Focus-Visible Polyfill (nur wenn nötig)
      this.focusVisible();
    },

    addSkipLink: function() {
      var main = MRH.Utils.qs('#main-content');
      if (!main) return;
      
      var existing = MRH.Utils.qs('.mrh-skip-link');
      if (existing) return;

      var link = document.createElement('a');
      link.href = '#main-content';
      link.className = 'mrh-skip-link';
      link.textContent = 'Zum Inhalt springen';
      document.body.insertBefore(link, document.body.firstChild);
    },

    focusVisible: function() {
      // Füge .using-keyboard Klasse hinzu wenn Tab gedrückt wird
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
          document.body.classList.add('using-keyboard');
        }
      });
      document.addEventListener('mousedown', function() {
        document.body.classList.remove('using-keyboard');
      });
    }
  };

  /* ----------------------------------------------------------
     07 PERFORMANCE: Prefetch + Preconnect
     ---------------------------------------------------------- */
  MRH.Performance = {
    init: function() {
      // Prefetch bei Hover über Links (nur Desktop)
      if (window.matchMedia('(hover: hover)').matches) {
        this.prefetchOnHover();
      }
    },

    prefetchOnHover: function() {
      var prefetched = new Set();
      
      MRH.Utils.on(document.body, 'mouseenter', 'a[href]', function() {
        var href = this.getAttribute('href');
        // Nur interne Links, keine Anker, keine bereits geladenen
        if (!href || href.startsWith('#') || href.startsWith('javascript') || 
            href.startsWith('mailto') || href.startsWith('tel') ||
            prefetched.has(href)) return;
        
        // Nur gleiche Domain
        try {
          var url = new URL(href, window.location.origin);
          if (url.origin !== window.location.origin) return;
        } catch(e) { return; }

        prefetched.add(href);
        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = href;
        document.head.appendChild(link);
      });
    }
  };

  /* ----------------------------------------------------------
     INIT: Alles starten wenn DOM bereit
     ---------------------------------------------------------- */
  function mrhInit() {
    MRH.Topbar.init();
    MRH.ShippingBar.init();
    MRH.StickyHeader.init();
    MRH.BackToTop.init();
    MRH.LazyLoad.init();
    MRH.A11y.init();
    MRH.Performance.init();

    // Debug-Info in Konsole (nur Entwicklung)
    if (window.location.hostname === 'localhost' || window.location.search.indexOf('debug=1') > -1) {
      console.log('[MRH Core] v1.0.0 initialized', {
        modules: Object.keys(MRH).filter(function(k) { return typeof MRH[k] === 'object' && MRH[k].init; }),
        shippingThreshold: MRH.ShippingBar.threshold
      });
    }
  }

  // DOM Ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mrhInit);
  } else {
    mrhInit();
  }

})();
</script>
