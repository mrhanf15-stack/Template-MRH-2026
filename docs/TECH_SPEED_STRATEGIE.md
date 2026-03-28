# MRH 2026 – Technische Speed-Strategie & CSS/JS-Architektur

**Stand:** 28.03.2026  
**Ziel:** Google Core Web Vitals bestehen, maximaler Speed-Boost, modified 3.0+ kompatibel

---

## 1. CSS-Architektur: Komplett neu, kein Legacy

### Grundprinzip
Kein einziges Byte altes CSS wird übernommen. Das gesamte Stylesheet wird von Grund auf neu geschrieben, basierend auf modernsten CSS-Standards 2026.

### CSS-Dateistruktur
```
css/
├── critical.css          ← Inline im <head>, nur Above-the-Fold (< 14 KB)
├── stylesheet.css        ← Haupt-Stylesheet (wird per Shop komprimiert)
├── components/
│   ├── _navigation.css   ← Header, Mega-Menu, Offcanvas, Bottom Bar
│   ├── _products.css     ← Produktkarten, Listing, Detailseite
│   ├── _forms.css        ← Formulare, Checkout, Login
│   ├── _boxes.css        ← Carousels, Newsletter, Trust-Elemente
│   └── _utilities.css    ← Helfer-Klassen
└── print.css             ← Druck-Stylesheet (media="print")
```

### Moderne CSS-Techniken 2026

| Technik | Einsatz | Vorteil |
|---|---|---|
| **CSS Cascade Layers** (`@layer`) | Strukturierung: reset → base → components → utilities | Keine Spezifitäts-Konflikte, saubere Überschreibbarkeit |
| **CSS Container Queries** (`@container`) | Produktkarten, Boxen | Komponenten reagieren auf ihren Container, nicht auf Viewport |
| **CSS Nesting** (nativ) | Alle Komponenten | Kein Sass/Less nötig, weniger Code, bessere Lesbarkeit |
| **`content-visibility: auto`** | Offscreen-Bereiche (Footer, Tabs) | Browser rendert nur sichtbare Bereiche → schnellerer Paint |
| **`contain: layout style paint`** | Produktkarten, Boxen | Isoliert Reflows → weniger Layout-Shifts |
| **CSS Custom Properties** | Farben, Abstände, Schriften | Zentrale Design-Tokens, Theme-fähig |
| **`color-mix()` / OKLCH** | Farbvarianten | Keine doppelten Farbdefinitionen |
| **`aspect-ratio`** | Bilder, Produktkarten | Kein CLS (Cumulative Layout Shift) |
| **`scrollbar-gutter: stable`** | Body | Kein Layout-Shift beim Scrollbar-Erscheinen |
| **`prefers-reduced-motion`** | Animationen | Barrierefreiheit + Performance |

### CSS Layers Architektur
```css
/* Reihenfolge definiert Priorität (letztes gewinnt) */
@layer reset, base, bootstrap, components, modules, utilities, overrides;

@layer reset {
  /* Moderner CSS Reset (< 1 KB) */
  *, *::before, *::after { box-sizing: border-box; margin: 0; }
  img, picture, video, canvas, svg { display: block; max-width: 100%; height: auto; }
}

@layer base {
  /* Design-Tokens als Custom Properties */
  :root {
    --mrh-green-500: oklch(0.55 0.15 145);
    --mrh-green-600: oklch(0.45 0.15 145);
    --mrh-font-body: system-ui, -apple-system, sans-serif;
    --mrh-font-heading: 'Outfit', system-ui, sans-serif;
    --mrh-space-xs: clamp(0.25rem, 0.5vw, 0.5rem);
    --mrh-space-sm: clamp(0.5rem, 1vw, 0.75rem);
    --mrh-space-md: clamp(0.75rem, 1.5vw, 1.25rem);
    --mrh-space-lg: clamp(1.25rem, 2.5vw, 2rem);
  }
}

@layer bootstrap {
  /* Nur die genutzten Bootstrap 5.3 Utilities (Tree-Shaken) */
  /* Grid, Flexbox, Spacing, Display, Text-Alignment */
  /* KEIN vollständiges Bootstrap CSS! */
}

@layer components {
  /* Eigene Komponenten: Navigation, Produktkarten, etc. */
}

@layer modules {
  /* Shop-Module: Seedfinder, Blog, Reklamation */
}

@layer utilities {
  /* Eigene Utility-Klassen */
}

@layer overrides {
  /* contentAnywhere Styles, Admin-Overrides */
}
```

### Shop-eigene Komprimierung
Die modified eCommerce Shopsoftware bietet eine eingebaute CSS/JS-Komprimierung. Damit diese funktioniert:

```php
// config.php
defined('COMPRESS_JAVASCRIPT') or define('COMPRESS_JAVASCRIPT', 'true');
```

**Regeln für Kompatibilität:**
- Alle CSS-Dateien müssen als separate `.css`-Dateien vorliegen (kein Inline-CSS außer Critical CSS)
- Keine ES-Module (`import`/`export`) in den JS-Dateien, da der Shop-Kompressor diese nicht versteht
- JS-Dateien müssen als IIFEs (Immediately Invoked Function Expressions) geschrieben werden
- Keine Template-Literals mit Backticks in JS (Shop-Kompressor kann diese brechen)
- Semikolons am Ende jeder Anweisung (ASI-Probleme bei Komprimierung vermeiden)
- Keine CSS-Variablen in `calc()` mit Leerzeichen-Problemen

---

## 2. JavaScript-Architektur: Clean ES2024+, Vanilla JS

### Grundprinzip
- **Null jQuery** – Kein jQuery, kein jQuery UI, keine jQuery-Plugins
- **Vanilla JS only** – Natives JavaScript mit modernen APIs
- **Progressive Enhancement** – Basis-Funktionalität ohne JS, Erweiterung mit JS
- **Kompatibel mit Shop-Kompressor** – IIFE-Pattern, kein ES-Module-Syntax

### JS-Dateistruktur
```
javascript/
├── mrh2026.js              ← Haupt-Script (IIFE, komprimierbar)
├── extra/
│   ├── default.js.php      ← PHP-generiertes JS (Shop-Standard, Pflicht!)
│   └── mrh_modules.js.php  ← Modulspezifisches JS (Mega-Menu, etc.)
├── vendors/
│   ├── bootstrap.bundle.min.js  ← BS 5.3 (nur benötigte Komponenten)
│   └── fontawesome.min.js       ← FA 6 (Subset)
└── deferred/
    ├── mrh_carousel.js     ← Lazy-loaded: Produktkarussells
    ├── mrh_seedfinder.js   ← Lazy-loaded: Seedfinder-Modul
    └── mrh_gallery.js      ← Lazy-loaded: Produktbilder-Galerie
```

### Moderne JS-Techniken 2026

| Technik | Einsatz | Vorteil |
|---|---|---|
| **Intersection Observer** | Lazy Loading, Scroll-Animationen | Kein Scroll-Event-Listener, performant |
| **`requestIdleCallback`** | Nicht-kritische Initialisierungen | Blockiert nicht den Main Thread |
| **`navigator.scheduling.isInputPending()`** | Event-Handler | Yield to Browser bei User-Input |
| **`structuredClone()`** | Daten-Kopien | Schneller als JSON.parse(JSON.stringify()) |
| **`AbortController`** | AJAX-Requests | Sauberes Cleanup, keine Memory Leaks |
| **`<script type="speculationrules">`** | Prefetch/Prerender | Instant Page Transitions |
| **`View Transitions API`** | Seitenübergänge | Smooth Transitions ohne SPA |
| **`Popover API`** (nativ) | Tooltips, Dropdowns | Kein JS für einfache Popovers |
| **`dialog` Element** | Modals | Natives Modal ohne JS-Library |
| **`fetchpriority`** | Bilder, Scripts | Browser priorisiert kritische Ressourcen |

### IIFE-Pattern für Shop-Kompressor-Kompatibilität
```javascript
/* MRH 2026 Template - Haupt-Script */
;(function(window, document) {
  'use strict';

  /* ========================================
   * NAVIGATION: Mega-Menu, Offcanvas, Sticky
   * ======================================== */
  var MRH_Nav = {
    init: function() {
      this.stickyHeader();
      this.megaMenu();
      this.mobileOffcanvas();
      this.bottomBar();
    },

    stickyHeader: function() {
      var header = document.getElementById('mrh-header');
      if (!header) return;
      var observer = new IntersectionObserver(function(entries) {
        document.body.classList.toggle('header-stuck', !entries[0].isIntersecting);
      }, { threshold: 0 });
      observer.observe(document.getElementById('mrh-header-sentinel'));
    },

    megaMenu: function() {
      /* Vanilla JS Mega-Menu mit AJAX-Nachladen */
      /* Konfigurierbar über MRH_KK_MEGAS aus config.php */
    },

    mobileOffcanvas: function() {
      /* Bootstrap 5.3 native Offcanvas */
    },

    bottomBar: function() {
      /* Mobile Bottom Navigation Bar */
    }
  };

  /* ========================================
   * INIT: DOMContentLoaded
   * ======================================== */
  document.addEventListener('DOMContentLoaded', function() {
    MRH_Nav.init();
  });

  /* Nicht-kritische Module per requestIdleCallback */
  if ('requestIdleCallback' in window) {
    requestIdleCallback(function() {
      /* Carousels, Animationen, etc. */
    });
  }

})(window, document);
```

---

## 3. Speed-Optimierung: Core Web Vitals 2026

### Critical Rendering Path
```html
<head>
  <!-- 1. Critical CSS inline (< 14 KB, Above-the-Fold) -->
  <style>{critical_css_inline}</style>

  <!-- 2. Preconnect zu externen Domains -->
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>

  <!-- 3. Preload kritischer Ressourcen -->
  <link rel="preload" as="image" href="{logo_url}" fetchpriority="high">
  <link rel="preload" as="style" href="stylesheet.css">

  <!-- 4. Haupt-CSS asynchron laden -->
  <link rel="stylesheet" href="stylesheet.css" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="stylesheet.css"></noscript>

  <!-- 5. Font Awesome als Subset (nur genutzte Icons) -->
  <link rel="stylesheet" href="css/fa-subset.css" media="print" onload="this.media='all'">
</head>
<body>
  <!-- Content -->

  <!-- 6. JS am Ende, defer -->
  <script src="javascript/vendors/bootstrap.bundle.min.js" defer></script>
  <script src="javascript/mrh2026.js" defer></script>

  <!-- 7. Speculation Rules für Prefetch -->
  <script type="speculationrules">
  {
    "prefetch": [{
      "where": { "href_matches": "/*" },
      "eagerness": "moderate"
    }]
  }
  </script>
</body>
```

### Bild-Optimierung
```html
<!-- Responsive Images mit Art Direction -->
<picture>
  <!-- WebP/AVIF wird vom Shop-Modul generiert -->
  <source type="image/avif" srcset="{avif_url}" width="300" height="300">
  <source type="image/webp" srcset="{webp_url}" width="300" height="300">
  <img src="{jpg_url}" alt="{alt}" width="300" height="300"
       loading="lazy" decoding="async" fetchpriority="low">
</picture>

<!-- Hero/LCP-Bild: Kein lazy loading! -->
<img src="{hero_url}" alt="{alt}" width="1200" height="400"
     loading="eager" decoding="sync" fetchpriority="high">
```

### Core Web Vitals Zielwerte

| Metrik | Zielwert | Strategie |
|---|---|---|
| **LCP** (Largest Contentful Paint) | < 1.5s | Critical CSS inline, Hero-Bild preload, fetchpriority="high" |
| **INP** (Interaction to Next Paint) | < 100ms | Kein jQuery, Event-Delegation, requestIdleCallback |
| **CLS** (Cumulative Layout Shift) | < 0.05 | aspect-ratio auf Bilder, Font-Display: optional, scrollbar-gutter |
| **FCP** (First Contentful Paint) | < 1.0s | Critical CSS < 14 KB, keine Render-Blocking Resources |
| **TTFB** (Time to First Byte) | < 200ms | Server-seitig (FPC Cache bereits aktiv) |
| **TBT** (Total Blocking Time) | < 100ms | JS defer, Code-Splitting, requestIdleCallback |

---

## 4. Modified 3.0+ Kompatibilität

### Template-Engine
```php
define('TEMPLATE_ENGINE', 'smarty_4');
define('TEMPLATE_HTML_ENGINE', 'html5');
define('TEMPLATE_RESPONSIVE', 'true');
```

### Naming Convention
- Prefix: `MRH_` statt `BS4_` (eigener Namespace, keine Konflikte)
- Beispiel: `MRH_SHOW_TOP1`, `MRH_KK_MEGAS`, `MRH_CAROUSEL_SHOW`

### PHP 8.3 Kompatibilität
- Strikte Typisierung wo möglich
- `strpos()` mit striktem Vergleich (`!== false`)
- Keine deprecated Funktionen
- `defined() or define()` Pattern beibehalten (modified-Standard)

### Zukunftssicherheit modified 3.x
- Keine Abhängigkeit von internen Shop-Funktionen, die sich ändern könnten
- Smarty 4 kompatibel (kein Smarty 2/3 Syntax)
- Font Awesome 6 (wie Nova-Template)
- Google Consent Mode v2 vorbereitet
- Alle Template-Dateien in Standard-Verzeichnisstruktur

---

## 5. Font-Strategie

### System Font Stack (Maximal schnell)
```css
:root {
  /* Body: System Fonts = 0 KB Download */
  --mrh-font-body: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;

  /* Headings: Optional Google Font mit font-display: optional */
  --mrh-font-heading: 'Outfit', var(--mrh-font-body);
}

@font-face {
  font-family: 'Outfit';
  src: url('webfonts/outfit-var.woff2') format('woff2');
  font-weight: 400 700;
  font-display: optional; /* Kein FOUT, kein CLS */
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+2000-206F;
}
```

### Font Awesome 6 Subset
- Nur die tatsächlich genutzten Icons als Subset
- Selbst gehostet (kein CDN-Request)
- `font-display: block` (Icons müssen sichtbar sein)
