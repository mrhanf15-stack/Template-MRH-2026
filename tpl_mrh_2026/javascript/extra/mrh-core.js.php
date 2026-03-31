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
     08 MEGA-MENÜ: Vanilla JS Navigation
     ---------------------------------------------------------- */
  MRH.MegaMenu = {
    hoverDelay: 150,
    closeDelay: 250,
    openTimer: null,
    closeTimer: null,
    activeItem: null,
    activeDropdown: null,
    isTouch: false,

    init: function() {
      var nav = MRH.Utils.qs('#mrhMegaNav');
      if (!nav) return;
      this.nav = nav;
      this.bar = MRH.Utils.qs('.mrh-mega-nav-bar', nav);

      // Touch-Erkennung
      this.isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

      // 1. CatNavi in Nav-Items umwandeln
      this.transformCategories();

      // 2. Event-Listener
      this.bindEvents();

      // 3. Aktiven Nav-Item markieren
      this.markActive();
    },

    /**
     * Wandelt die Smarty-generierte CatNavi in mrh-nav-items um
     * und baut Mega-Dropdown-Panels für Kategorien mit Submenüs
     */
    transformCategories: function() {
      var catWrap = MRH.Utils.qs('#mrhNavCategories');
      if (!catWrap) return;

      var catNav = MRH.Utils.qs('.CatNavi', catWrap);
      if (!catNav) return;

      var level1Items = MRH.Utils.qsa(':scope > li', catNav);
      var fragment = document.createDocumentFragment();

      // Icon-Map für Hauptkategorien (aus Sprachdatei oder Fallback)
      var iconMap = {
        'samen': 'fa-seedling',
        'seed': 'fa-seedling',
        'cannabis': 'fa-cannabis',
        'cannabispflanz': 'fa-cannabis',
        'grow': 'fa-sun',
        'head': 'fa-bong',
        'default': 'fa-leaf'
      };

      var self = this;

      // Statische Nav-Items sammeln um Duplikate zu vermeiden (SEO: saubere URLs bevorzugen)
      var staticNavTexts = [];
      MRH.Utils.qsa('.mrh-nav-item[data-nav]', this.bar).forEach(function(item) {
        var span = item.querySelector('span');
        if (span) staticNavTexts.push(span.textContent.trim().toLowerCase());
      });

      level1Items.forEach(function(li) {
        var link = MRH.Utils.qs(':scope > a', li);
        if (!link) return;

        var text = link.textContent.trim();
        var href = link.getAttribute('href') || '#';

        // Duplikat-Check: Wenn ein statischer Nav-Item mit gleichem Text existiert, überspringen
        if (staticNavTexts.indexOf(text.toLowerCase()) > -1) return;

        var subUl = MRH.Utils.qs(':scope > ul', li);
        var hasSubmenu = !!subUl;

        // Icon bestimmen
        var iconClass = iconMap['default'];
        var textLower = text.toLowerCase();
        Object.keys(iconMap).forEach(function(key) {
          if (textLower.indexOf(key) > -1) iconClass = iconMap[key];
        });

        // Nav-Item erstellen
        var navItem = document.createElement('a');
        navItem.href = href;
        navItem.className = 'mrh-nav-item';
        navItem.setAttribute('data-nav', text.toLowerCase().replace(/\s+/g, '-'));
        navItem.innerHTML = '<i class="fa-solid ' + iconClass + '"></i> ' +
                            '<span>' + text + '</span>';

        if (hasSubmenu) {
          navItem.innerHTML += ' <i class="fa-solid fa-chevron-down mrh-nav-arrow"></i>';
          navItem.setAttribute('data-mega', 'true');

          // Mega-Dropdown Panel bauen
          var dropdown = self.buildDropdown(subUl, href, text);
          navItem._megaDropdown = dropdown;
          self.nav.appendChild(dropdown);
        }

        fragment.appendChild(navItem);
      });

      // Statische Items (Angebote, Neue Artikel, etc.) kommen NACH den Kategorien
      var staticItems = MRH.Utils.qsa('.mrh-nav-item[data-nav]', this.bar);
      var homeItem = MRH.Utils.qs('.mrh-nav-home', this.bar);

      // Kategorien nach Home einfügen
      if (homeItem && homeItem.nextSibling) {
        this.bar.insertBefore(fragment, homeItem.nextSibling);
      } else {
        this.bar.appendChild(fragment);
      }

      // Original CatNavi verstecken
      catWrap.style.display = 'none';
    },

    /**
     * Kategorie-spezifische Spalten-Konfiguration (SEO 2026)
     * Jede Hauptkategorie bekommt passende Überschriften und Icons
     */
    getCategoryConfig: function(parentText) {
      var textLower = (parentText || '').toLowerCase();

      // Samen Shop
      if (textLower.indexOf('samen') > -1 || textLower.indexOf('seed') > -1) {
        return {
          titles: ['Samenarten', 'Empfehlungen', 'Mehr entdecken'],
          icons:  ['fa-seedling', 'fa-star', 'fa-compass']
        };
      }
      // Growshop
      if (textLower.indexOf('grow') > -1) {
        return {
          titles: ['Grundausstattung', 'Pflanzenpflege', 'Spezialzubehör'],
          icons:  ['fa-box-open', 'fa-hand-holding-droplet', 'fa-screwdriver-wrench']
        };
      }
      // Headshop
      if (textLower.indexOf('head') > -1) {
        return {
          titles: ['Rauchen & Dampfen', 'Zubehör & Tools', 'Mehr entdecken'],
          icons:  ['fa-cloud', 'fa-wrench', 'fa-flask']
        };
      }
      // Cannabispflanzen
      if (textLower.indexOf('cannabispflanz') > -1 || textLower.indexOf('pflanz') > -1) {
        return {
          titles: ['Pflanzen kaufen', 'Sorten', 'Mehr entdecken'],
          icons:  ['fa-cannabis', 'fa-leaf', 'fa-compass']
        };
      }
      // Fallback
      return {
        titles: ['Sortiment', 'Highlights', 'Mehr entdecken'],
        icons:  ['fa-layer-group', 'fa-star', 'fa-compass']
      };
    },

    buildDropdown: function(subUl, parentHref, parentText) {
      var dropdown = document.createElement('div');
      dropdown.className = 'mrh-mega-dropdown';

      var content = document.createElement('div');
      content.className = 'mrh-mega-content';

      // Sub-Kategorien in Spalten aufteilen (max 3 Spalten + Promo)
      var subItems = MRH.Utils.qsa(':scope > li', subUl);
      var columns = this.splitIntoColumns(subItems, 3);

      // Kategorie-spezifische Spalten-Titel und Icons (SEO 2026)
      var config = this.getCategoryConfig(parentText);
      var colIcons = config.icons;
      var colTitles = config.titles;

      columns.forEach(function(colItems, idx) {
        var col = document.createElement('div');
        col.className = 'mrh-mega-col';

        // Spalten-Titel
        var title = document.createElement('div');
        title.className = 'mrh-mega-col-title';
        title.innerHTML = '<i class="fa-solid ' + (colIcons[idx] || 'fa-folder') + '"></i> ' +
                          (colTitles[idx] || 'Kategorie ' + (idx + 1));
        col.appendChild(title);

        // Links
        var ul = document.createElement('ul');
        colItems.forEach(function(item) {
          var a = MRH.Utils.qs('a', item);
          if (!a) return;
          var li = document.createElement('li');
          var link = document.createElement('a');
          link.href = a.getAttribute('href') || '#';
          link.textContent = a.textContent.trim();
          li.appendChild(link);
          ul.appendChild(li);
        });
        col.appendChild(ul);

        // "Alle anzeigen" Link
        var allLink = document.createElement('a');
        allLink.href = parentHref;
        allLink.className = 'mrh-mega-all';
        allLink.innerHTML = 'Alle anzeigen <i class="fa-solid fa-arrow-right"></i>';
        col.appendChild(allLink);

        content.appendChild(col);
      });

      // Promo-Spalte hinzufügen
      var promoData = MRH.Utils.qs('#mrhMegaPromoData');
      if (promoData) {
        var promo = document.createElement('div');
        promo.className = 'mrh-mega-promo';
        promo.innerHTML =
          '<div class="mrh-mega-promo-inner">' +
            '<div class="mrh-mega-promo-title">' +
              '<i class="fa-solid ' + (promoData.dataset.icon || 'fa-percent') + '"></i> ' +
              (promoData.dataset.title || 'Aktion') +
            '</div>' +
            '<div class="mrh-mega-promo-brand">' + (promoData.dataset.brand || '') + '</div>' +
            '<div class="mrh-mega-promo-text">' + (promoData.dataset.text || '') + '</div>' +
            '<a href="' + (promoData.dataset.link || '/angebote/') + '" class="mrh-mega-promo-btn">' +
              (promoData.dataset.button || 'Jetzt sparen') +
            '</a>' +
          '</div>';
        content.appendChild(promo);
      }

      dropdown.appendChild(content);
      return dropdown;
    },

    /**
     * Verteilt Sub-Items gleichmäßig auf n Spalten
     */
    splitIntoColumns: function(items, numCols) {
      var cols = [];
      for (var i = 0; i < numCols; i++) cols.push([]);
      items.forEach(function(item, idx) {
        cols[idx % numCols].push(item);
      });
      // Leere Spalten entfernen
      return cols.filter(function(c) { return c.length > 0; });
    },

    /**
     * Event-Listener für Hover (Desktop) und Click (Touch)
     */
    bindEvents: function() {
      var self = this;
      var megaItems = MRH.Utils.qsa('.mrh-nav-item[data-mega]', this.bar);

      megaItems.forEach(function(item) {
        if (self.isTouch) {
          // Touch: Click öffnet/schließt Dropdown
          item.addEventListener('click', function(e) {
            if (self.activeItem === item) {
              // Zweiter Klick: Navigiere zum Link
              self.closeAll();
              return;
            }
            e.preventDefault();
            self.closeAll();
            self.open(item);
          });
        } else {
          // Desktop: Hover mit Delay
          item.addEventListener('mouseenter', function() {
            clearTimeout(self.closeTimer);
            self.openTimer = setTimeout(function() {
              self.closeAll();
              self.open(item);
            }, self.hoverDelay);
          });

          item.addEventListener('mouseleave', function() {
            clearTimeout(self.openTimer);
            self.closeTimer = setTimeout(function() {
              self.closeAll();
            }, self.closeDelay);
          });
        }
      });

      // Dropdown selbst: Hover hält es offen
      var dropdowns = MRH.Utils.qsa('.mrh-mega-dropdown', this.nav);
      dropdowns.forEach(function(dd) {
        dd.addEventListener('mouseenter', function() {
          clearTimeout(self.closeTimer);
        });
        dd.addEventListener('mouseleave', function() {
          self.closeTimer = setTimeout(function() {
            self.closeAll();
          }, self.closeDelay);
        });
      });

      // Klick außerhalb schließt alles
      document.addEventListener('click', function(e) {
        if (!self.nav.contains(e.target)) {
          self.closeAll();
        }
      });

      // ESC schließt Dropdown
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') self.closeAll();
      });

      // Keyboard: Arrow-Navigation
      this.bindKeyboard(megaItems);
    },

    /**
     * Keyboard-Navigation (Tab, Enter, Arrow Keys)
     */
    bindKeyboard: function(megaItems) {
      var self = this;
      megaItems.forEach(function(item) {
        item.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            if (self.activeItem === item) {
              self.closeAll();
            } else {
              self.closeAll();
              self.open(item);
              // Fokus auf ersten Link im Dropdown
              var firstLink = item._megaDropdown ? item._megaDropdown.querySelector('a') : null;
              if (firstLink) firstLink.focus();
            }
          }
          if (e.key === 'ArrowDown' && self.activeItem === item) {
            e.preventDefault();
            var firstLink = item._megaDropdown ? item._megaDropdown.querySelector('a') : null;
            if (firstLink) firstLink.focus();
          }
        });
      });
    },

    /**
     * Dropdown öffnen
     */
    open: function(item) {
      if (!item._megaDropdown) return;
      item.classList.add('mrh-mega-open');
      item._megaDropdown.classList.add('open');
      item.setAttribute('aria-expanded', 'true');
      this.activeItem = item;
      this.activeDropdown = item._megaDropdown;
    },

    /**
     * Alle Dropdowns schließen
     */
    closeAll: function() {
      clearTimeout(this.openTimer);
      clearTimeout(this.closeTimer);
      var openItems = MRH.Utils.qsa('.mrh-mega-open', this.bar);
      openItems.forEach(function(item) {
        item.classList.remove('mrh-mega-open');
        item.setAttribute('aria-expanded', 'false');
      });
      var openDropdowns = MRH.Utils.qsa('.mrh-mega-dropdown.open', this.nav);
      openDropdowns.forEach(function(dd) {
        dd.classList.remove('open');
      });
      this.activeItem = null;
      this.activeDropdown = null;
    },

    /**
     * Aktiven Nav-Item basierend auf aktuellem Pfad markieren
     */
    markActive: function() {
      var path = window.location.pathname;
      var items = MRH.Utils.qsa('.mrh-nav-item', this.bar);
      items.forEach(function(item) {
        var href = item.getAttribute('href');
        if (!href || href === '#') return;
        if (href === '/' && path === '/') {
          item.classList.add('active');
        } else if (href !== '/' && path.indexOf(href) === 0) {
          item.classList.add('active');
        }
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
    MRH.MegaMenu.init();

    // Suchleisten-Placeholder anpassen (Core liefert nur "Suchen")
    var searchInput = document.querySelector('#search input[type="text"], #search input[name="keywords"]');
    if (searchInput) {
      var placeholders = {
        'german': 'Cannabis Samen suchen...',
        'english': 'Search cannabis seeds...',
        'french': 'Rechercher des graines...',
        'dutch': 'Cannabis zaden zoeken...'
      };
      var lang = document.documentElement.lang || 'de';
      // Sprache aus HTML-lang oder aus Body-Klasse ermitteln
      if (lang === 'de' || lang === 'de-AT') searchInput.placeholder = placeholders['german'];
      else if (lang === 'en') searchInput.placeholder = placeholders['english'];
      else if (lang === 'fr') searchInput.placeholder = placeholders['french'];
      else if (lang === 'nl') searchInput.placeholder = placeholders['dutch'];
      else searchInput.placeholder = placeholders['german'];
    }

    // ============================================================
    // Bottom Bar – Mobile Navigation
    // ============================================================
    var bottomBar = document.getElementById('mrhBottomBar');
    if (bottomBar) {

      // -- Suche: Overlay oeffnen/schliessen --
      var bbSearch = document.getElementById('mrhBottomSearch');
      var searchOverlay = document.getElementById('mrhSearchOverlay');
      var searchOverlayClose = document.getElementById('mrhSearchOverlayClose');
      var searchOverlayBg = document.getElementById('mrhSearchOverlayBg');

      if (bbSearch && searchOverlay) {
        bbSearch.addEventListener('click', function(e) {
          e.preventDefault();
          searchOverlay.classList.add('open');
          document.body.classList.add('mrh-no-scroll');
          var overlayInput = searchOverlay.querySelector('input[type="text"]');
          if (overlayInput) {
            setTimeout(function() { overlayInput.focus(); }, 200);
          }
        });

        var closeOverlay = function() {
          searchOverlay.classList.remove('open');
          document.body.classList.remove('mrh-no-scroll');
        };

        if (searchOverlayClose) searchOverlayClose.addEventListener('click', closeOverlay);
        if (searchOverlayBg) searchOverlayBg.addEventListener('click', closeOverlay);

        // ESC-Taste schliesst Overlay
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && searchOverlay.classList.contains('open')) {
            closeOverlay();
          }
        });
      }

      // -- Warenkorb-Badge: Live-Sync mit Header --
      var bbCartBadge = bottomBar.querySelector('.mrh-bb-cart-count');
      if (bbCartBadge) {
        var syncCartBadge = function() {
          var headerBadge = document.querySelector('#iconMenu .cart .cart_content');
          if (headerBadge && headerBadge.textContent.trim() !== '' && headerBadge.textContent.trim() !== '0') {
            bbCartBadge.textContent = headerBadge.textContent.trim();
            bbCartBadge.style.display = 'block';
          } else {
            bbCartBadge.style.display = 'none';
          }
        };
        syncCartBadge();
        var headerCartEl = document.querySelector('#iconMenu .cart .cart_content');
        if (headerCartEl) {
          new MutationObserver(syncCartBadge).observe(headerCartEl, { childList: true, characterData: true, subtree: true });
        }
        document.addEventListener('cartUpdated', syncCartBadge);
      }

      // -- Merkzettel-Badge: Live-Sync mit Header --
      var bbWishBadge = bottomBar.querySelector('.mrh-bb-wish-count');
      if (bbWishBadge) {
        var syncWishBadge = function() {
          var headerWish = document.querySelector('#iconMenu .wishlist .cart_content');
          if (headerWish && headerWish.textContent.trim() !== '' && headerWish.textContent.trim() !== '0') {
            bbWishBadge.textContent = headerWish.textContent.trim();
            bbWishBadge.style.display = 'block';
          } else {
            bbWishBadge.style.display = 'none';
          }
        };
        syncWishBadge();
        var headerWishEl = document.querySelector('#iconMenu .wishlist .cart_content');
        if (headerWishEl) {
          new MutationObserver(syncWishBadge).observe(headerWishEl, { childList: true, characterData: true, subtree: true });
        }
        document.addEventListener('wishlistUpdated', syncWishBadge);
      }

      // -- Active State: Aktuellen Pfad markieren --
      var currentPath = window.location.pathname;
      var bbLinks = bottomBar.querySelectorAll('a');
      bbLinks.forEach(function(link) {
        var href = link.getAttribute('href');
        if (href === '/' && currentPath === '/') {
          link.classList.add('active');
        } else if (href && href !== '/' && href !== '#' && currentPath.indexOf(href) === 0) {
          link.classList.add('active');
        }
      });
    }

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
