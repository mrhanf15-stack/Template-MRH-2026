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
    /* Nutze native loading="lazy" – kein externes Script noetig */
    /* Fuer aeltere Browser: IntersectionObserver als Fallback */
    if ('loading' in HTMLImageElement.prototype) return;

    const images = document.querySelectorAll('img[loading="lazy"]');
    if (!images.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          observer.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });

    images.forEach(img => observer.observe(img));
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

/* === INIT === */
document.addEventListener('DOMContentLoaded', () => {
  MRH_StickyHeader.init();
  MRH_Go2Top.init();
  MRH_MobileSearch.init();
  MRH_FreeShippingBar.init();
  MRH_MegaMenu.init();
  MRH_LazyLoad.init();
  MRH_Gallery.init();
});
