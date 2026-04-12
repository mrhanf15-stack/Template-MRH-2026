/* =====================================================================
   MRH 2026 Template – mrh2026.js
   
   Vanilla JS | ES2024+ | Kein jQuery
   Modular aufgebaut | Kompatibel mit Shop-Komprimierung
   ===================================================================== */

'use strict';

/* === STICKY HEADER === */
const MRH_StickyHeader = {
  el: null,
  threshold: 200,
  lastScrollY: 0,
  ticking: false,

  init() {
    this.el = document.getElementById('mrh-sticky-header');
    if (!this.el) return;
    window.addEventListener('scroll', () => this.onScroll(), { passive: true });
  },

  onScroll() {
    if (!this.ticking) {
      requestAnimationFrame(() => {
        const scrollY = window.scrollY;
        if (scrollY > this.threshold && scrollY < this.lastScrollY) {
          this.el.classList.add('visible');
          this.el.setAttribute('aria-hidden', 'false');
        } else {
          this.el.classList.remove('visible');
          this.el.setAttribute('aria-hidden', 'true');
        }
        this.lastScrollY = scrollY;
        this.ticking = false;
      });
      this.ticking = true;
    }
  }
};

/* === GO TO TOP BUTTON === */
const MRH_Go2Top = {
  el: null,
  threshold: 400,
  ticking: false,

  init() {
    this.el = document.getElementById('mrh-go2top');
    if (!this.el) return;
    window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    this.el.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  },

  onScroll() {
    if (!this.ticking) {
      requestAnimationFrame(() => {
        if (window.scrollY > this.threshold) {
          this.el.classList.add('visible');
        } else {
          this.el.classList.remove('visible');
        }
        this.ticking = false;
      });
      this.ticking = true;
    }
  }
};

/* === MOBILE SEARCH TOGGLE === */
const MRH_MobileSearch = {
  init() {
    const toggle = document.getElementById('mrh-search-toggle');
    const searchBox = document.getElementById('mrh-mobile-search');
    const bnavSearch = document.getElementById('mrh-bnav-search');
    if (!searchBox) return;

    const toggleSearch = (e) => {
      e.preventDefault();
      const bsCollapse = bootstrap.Collapse.getOrCreateInstance(searchBox);
      bsCollapse.toggle();
      const input = searchBox.querySelector('input[name="keywords"]');
      if (input) {
        setTimeout(() => input.focus(), 300);
      }
    };

    if (toggle) toggle.addEventListener('click', toggleSearch);
    if (bnavSearch) bnavSearch.addEventListener('click', toggleSearch);
  }
};

/* === FREE SHIPPING BAR === */
const MRH_FreeShippingBar = {
  init() {
    const bar = document.getElementById('mrh-free-shipping-bar');
    if (!bar) return;
    const threshold = parseFloat(bar.dataset.threshold) || 50;
    this.update(0, threshold);
  },

  update(currentTotal, threshold) {
    const bar = document.getElementById('mrh-free-shipping-bar');
    if (!bar) return;
    const fill = bar.querySelector('.mrh-fs-progress-fill');
    const text = bar.querySelector('.mrh-fs-text');
    if (!fill || !text) return;

    const percent = Math.min((currentTotal / threshold) * 100, 100);
    fill.style.width = percent + '%';

    const remaining = threshold - currentTotal;
    if (remaining <= 0) {
      text.textContent = bar.dataset.textFree || 'Gratis Versand!';
      fill.style.backgroundColor = 'var(--mrh-green-500)';
    } else {
      text.textContent = (bar.dataset.textRemaining || 'Noch {amount} bis zum Gratis-Versand')
        .replace('{amount}', remaining.toFixed(2).replace('.', ',') + ' \u20AC');
    }
  }
};

/* === MEGA MENU (KK-Mega) === */
const MRH_MegaMenu = {
  activeItem: null,
  closeTimeout: null,

  init() {
    const navItems = document.querySelectorAll('#mrh-nav-list > .nav-item.has-mega');
    if (!navItems.length) return;

    navItems.forEach(item => {
      const link = item.querySelector('.nav-link');
      const mega = item.querySelector('.mrh-mega-dropdown');
      if (!link || !mega) return;

      /* Desktop: Hover */
      item.addEventListener('mouseenter', () => {
        clearTimeout(this.closeTimeout);
        this.closeAll();
        mega.classList.add('show');
        link.setAttribute('aria-expanded', 'true');
        this.activeItem = item;
      });

      item.addEventListener('mouseleave', () => {
        this.closeTimeout = setTimeout(() => {
          mega.classList.remove('show');
          link.setAttribute('aria-expanded', 'false');
          this.activeItem = null;
        }, 200);
      });

      /* Keyboard: Enter/Space */
      link.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const isOpen = mega.classList.contains('show');
          this.closeAll();
          if (!isOpen) {
            mega.classList.add('show');
            link.setAttribute('aria-expanded', 'true');
            const firstLink = mega.querySelector('a');
            if (firstLink) firstLink.focus();
          }
        }
      });
    });

    /* Escape schliesst Mega-Menu */
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.closeAll();
    });

    /* Klick ausserhalb schliesst Mega-Menu */
    document.addEventListener('click', (e) => {
      if (!e.target.closest('#mrh-nav-list')) this.closeAll();
    });
  },

  closeAll() {
    document.querySelectorAll('.mrh-mega-dropdown.show').forEach(m => {
      m.classList.remove('show');
      const link = m.closest('.nav-item')?.querySelector('.nav-link');
      if (link) link.setAttribute('aria-expanded', 'false');
    });
  }
};

/* === LAZY LOAD IMAGES (native + fallback) === */
const MRH_LazyLoad = {
  init() {
    /* Bilder mit class="lazyload" und data-src (lazysizes-Pattern) */
    const lazyImages = document.querySelectorAll('img.lazyload[data-src]');
    if (lazyImages.length) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
            img.classList.remove('lazyload');
            img.classList.add('lazyloaded');
            observer.unobserve(img);
          }
        });
      }, { rootMargin: '200px' });
      lazyImages.forEach(img => observer.observe(img));
    }

    /* Fallback fuer native loading="lazy" in aelteren Browsern */
    if (!('loading' in HTMLImageElement.prototype)) {
      const nativeImages = document.querySelectorAll('img[loading="lazy"][data-src]');
      if (nativeImages.length) {
        const obs = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
              obs.unobserve(img);
            }
          });
        }, { rootMargin: '200px' });
        nativeImages.forEach(img => obs.observe(img));
      }
    }
  }
};

/* === CART COUNT SYNC === */
const MRH_CartSync = {
  update(count) {
    const badges = [
      document.getElementById('mrh-cart-count'),
      document.getElementById('mrh-sticky-cart-count'),
      document.getElementById('mrh-bnav-cart-count')
    ];
    badges.forEach(badge => {
      if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? '' : 'none';
      }
    });
  }
};

/* === PRODUCT IMAGE GALLERY (Modal) === */
const MRH_Gallery = {
  images: [],
  currentIndex: 0,

  init() {
    const modal = document.getElementById('mrh-modal');
    if (!modal) return;

    document.addEventListener('click', (e) => {
      const trigger = e.target.closest('[data-mrh-gallery]');
      if (!trigger) return;
      e.preventDefault();

      const group = trigger.dataset.mrhGallery || 'default';
      this.images = Array.from(document.querySelectorAll(`[data-mrh-gallery="${group}"]`));
      this.currentIndex = this.images.indexOf(trigger);
      this.show(modal);
    });

    const prev = document.getElementById('mrh-modal-prev');
    const next = document.getElementById('mrh-modal-next');
    if (prev) prev.addEventListener('click', () => this.navigate(-1, modal));
    if (next) next.addEventListener('click', () => this.navigate(1, modal));

    modal.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') this.navigate(-1, modal);
      if (e.key === 'ArrowRight') this.navigate(1, modal);
    });
  },

  show(modal) {
    const body = modal.querySelector('.modal-body');
    const counter = modal.querySelector('.mrh-modal-counter');
    const img = this.images[this.currentIndex];
    const src = img.dataset.mrhFull || img.querySelector('img')?.src || img.href;

    body.innerHTML = `<img src="${src}" class="img-fluid" alt="" loading="eager">`;
    if (counter) counter.textContent = `${this.currentIndex + 1} / ${this.images.length}`;

    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
    bsModal.show();
  },

  navigate(direction, modal) {
    this.currentIndex = (this.currentIndex + direction + this.images.length) % this.images.length;
    this.show(modal);
  }
};

/* === SEEDFINDER WIZARD === */
const MRH_SeedfinderWizard = {
  selections: {},
  currentStep: 1,
  totalSteps: 4,

  init() {
    const container = document.getElementById('wizard-steps-container');
    if (!container) return;

    /* Option-Card Klicks */
    container.addEventListener('click', (e) => {
      const card = e.target.closest('.wizard-option-card');
      if (!card) return;
      const step = parseInt(card.dataset.step);
      const value = card.dataset.value;
      if (!step || !value) return;

      /* Multi-Select oder Single-Select */
      if (card.classList.contains('wizard-multi-option')) {
        card.classList.toggle('selected');
      } else {
        container.querySelectorAll(`#wizard-step-${step} .wizard-option-card`).forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
      }

      this.selections[step] = value;
      this.updateProgress();

      /* Auto-Advance nach kurzer Verzoegerung */
      if (!card.classList.contains('wizard-multi-option') && step < this.totalSteps) {
        setTimeout(() => this.goToStep(step + 1), 300);
      }
    });

    /* Back Button */
    const backBtn = document.getElementById('wizard-back-btn');
    if (backBtn) {
      backBtn.addEventListener('click', () => {
        if (this.currentStep > 1) this.goToStep(this.currentStep - 1);
      });
    }

    /* Reset Button */
    const resetBtn = document.getElementById('wizard-reset-btn');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => this.reset());
    }
  },

  goToStep(step) {
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    const target = document.getElementById(`wizard-step-${step}`);
    if (target) {
      target.classList.add('active');
      this.currentStep = step;
      this.updateProgress();
    }
    const backBtn = document.getElementById('wizard-back-btn');
    if (backBtn) backBtn.style.display = step > 1 ? '' : 'none';
  },

  updateProgress() {
    const bar = document.getElementById('wizard-progress-bar');
    const text = document.getElementById('wizard-progress-text');
    const percent = (this.currentStep / this.totalSteps) * 100;
    if (bar) bar.style.width = percent + '%';
    if (text) {
      const tpl = text.dataset.template || 'Schritt {step} von {total}';
      text.textContent = tpl.replace('{step}', this.currentStep).replace('{total}', this.totalSteps);
    }
  },

  reset() {
    this.selections = {};
    this.currentStep = 1;
    document.querySelectorAll('.wizard-option-card.selected').forEach(c => c.classList.remove('selected'));
    this.goToStep(1);
  }
};

/* === SEEDFINDER FILTER === */
const MRH_SeedfinderFilter = {
  init() {
    const applyBtn = document.getElementById('seedfinder-filter-apply');
    const resetBtn = document.getElementById('seedfinder-filter-reset');
    if (!applyBtn) return;

    applyBtn.addEventListener('click', () => this.apply());
    if (resetBtn) resetBtn.addEventListener('click', () => this.reset());
  },

  apply() {
    const form = document.getElementById('seedfinder-filter-form');
    if (!form) return;
    const formData = new FormData(form);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
      if (value) params.append(key, value);
    }
    /* Redirect mit Filtern */
    const baseUrl = window.location.pathname;
    window.location.href = baseUrl + '?' + params.toString();
  },

  reset() {
    const form = document.getElementById('seedfinder-filter-form');
    if (form) form.reset();
  }
};

/* === CHECKOUT STEPPER === */
const MRH_CheckoutStepper = {
  init() {
    const nav = document.querySelector('.mrh-checkout-nav');
    if (!nav) return;
    /* Stepper ist rein visuell – wird per Smarty-Klassen gesteuert */
  }
};

/* === AUTOCOMPLETE === */
const MRH_Autocomplete = {
  debounceTimer: null,

  init() {
    const inputs = document.querySelectorAll('[data-mrh-autocomplete]');
    if (!inputs.length) return;

    inputs.forEach(input => {
      const url = input.dataset.mrhAutocomplete;
      const resultsId = input.dataset.mrhAutocompleteResults;
      const results = resultsId ? document.getElementById(resultsId) : null;
      if (!url || !results) return;

      input.addEventListener('input', () => {
        clearTimeout(this.debounceTimer);
        const query = input.value.trim();
        if (query.length < 2) {
          results.innerHTML = '';
          results.style.display = 'none';
          return;
        }
        this.debounceTimer = setTimeout(() => this.fetch(url, query, results), 250);
      });

      /* Schliessen bei Klick ausserhalb */
      document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-mrh-autocomplete]') && !e.target.closest('.mrh-autocomplete-results')) {
          results.innerHTML = '';
          results.style.display = 'none';
        }
      });
    });
  },

  fetch(url, query, container) {
    fetch(`${url}?keywords=${encodeURIComponent(query)}`)
      .then(r => r.json())
      .then(data => {
        if (!data || !data.length) {
          container.innerHTML = '';
          container.style.display = 'none';
          return;
        }
        container.innerHTML = data.map(item => `
          <a href="${item.link}" class="mrh-autocomplete-item">
            ${item.image ? `<img src="${item.image}" alt="" loading="lazy">` : ''}
            <div>
              <div class="fw-semibold small">${item.name}</div>
              ${item.price ? `<div class="small text-body-secondary">${item.price}</div>` : ''}
            </div>
          </a>
        `).join('');
        container.style.display = 'block';
      })
      .catch(() => {
        container.innerHTML = '';
        container.style.display = 'none';
      });
  }
};

/* === PRODUCT OPTIONS (Variant Selector) === */
const MRH_ProductOptions = {
  init() {
    /* Delegiert an Shop-eigene Optionen-Logik */
    /* Hier nur visuelle Verbesserungen */
    document.querySelectorAll('.mrh-option-swatch').forEach(swatch => {
      swatch.addEventListener('click', function() {
        this.closest('.mrh-option-swatches')?.querySelectorAll('.mrh-option-swatch').forEach(s => s.classList.remove('active'));
        this.classList.add('active');
        const radio = this.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      });
    });
  }
};

/* ── Listing/Box Kurzbeschreibung: Mini-Tabelle ── */
const MRH_ListingDesc = {
  /* Felder die extrahiert werden (Reihenfolge = Anzeigereihenfolge) */
  fields: ['Geschlecht', 'THC', 'CBD', 'Kreuzung'],
  /* Felder die ein Samen-Produkt identifizieren */
  seedMarkers: ['Geschlecht', 'THC', 'Sorte', 'Blütezeit Indoor'],

  init() {
    document.querySelectorAll('.lb_desc, .lr_desc').forEach(desc => {
      const table = desc.querySelector('table.tebals');
      if (!table) return;

      /* Daten aus der Tabelle extrahieren */
      const data = {};
      table.querySelectorAll('tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 2) {
          const label = cells[0].textContent.trim();
          const value = cells[cells.length - 1].textContent.trim();
          if (label && value) data[label] = value;
        }
      });

      /* Pruefen ob Samen-Produkt (mind. 1 Seed-Marker vorhanden) */
      const isSeed = this.seedMarkers.some(m => data[m]);
      desc.setAttribute('data-mrh-seed', isSeed ? '1' : '0');

      if (!isSeed) return; /* Nicht-Samen: Tabelle bleibt sichtbar per CSS */

      /* Mini-Tabelle erstellen (NUR fuer Samen) */
      const miniTable = document.createElement('table');
      miniTable.className = 'mrh-mini-table';
      let hasRows = false;

      this.fields.forEach(field => {
        if (!data[field]) return;
        hasRows = true;
        const tr = document.createElement('tr');
        const tdLabel = document.createElement('td');
        tdLabel.textContent = field;
        const tdValue = document.createElement('td');
        tdValue.textContent = data[field];
        tr.appendChild(tdLabel);
        tr.appendChild(tdValue);
        miniTable.appendChild(tr);
      });

      if (hasRows) {
        desc.appendChild(miniTable);
      }
    });
  }
};

/* === INIT === */
document.addEventListener('DOMContentLoaded', () => {
  MRH_StickyHeader.init();
  MRH_Go2Top.init();
  MRH_MobileSearch.init();
  MRH_FreeShippingBar.init();
  MRH_MegaMenu.init();
  MRH_LazyLoad.init();
  MRH_Gallery.init();
  MRH_SeedfinderWizard.init();
  MRH_SeedfinderFilter.init();
  MRH_CheckoutStepper.init();
  MRH_Autocomplete.init();
  MRH_ProductOptions.init();
  MRH_ListingDesc.init();
});
