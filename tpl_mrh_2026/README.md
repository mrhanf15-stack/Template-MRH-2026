# MRH 2026 Template – tpl_mrh_2026

**Modified eCommerce Template auf Basis Bootstrap 5.3**
Entwickelt fuer mr-hanf.de | Kompatibel mit modified v2.0.7.2 und vorbereitet fuer v3.0

## Installation

1. Den Ordner `tpl_mrh_2026` in `/templates/` des Shops kopieren
2. Im Admin unter **Konfiguration > Template-Verwaltung** das Template `tpl_mrh_2026` aktivieren
3. Bootstrap 5.3 CSS/JS wird via CDN geladen (kein lokaler Build noetig)
4. Font Awesome 4.7 wird via CDN geladen

## Dateistruktur

```
tpl_mrh_2026/
├── config/
│   ├── config.php          # Template-Konfiguration (MRH_-Prefix)
│   └── banners.php         # Banner-Manager Gruppen
├── css/
│   └── stylesheet.css      # Komplett neues CSS (CSS Layers, Custom Properties)
├── javascript/
│   └── mrh2026.js          # Vanilla JS (ES2024+, kein jQuery)
├── index.html              # Haupt-Smarty-Template
├── boxes/                  # 30 Box-Templates
├── module/
│   ├── includes/           # Header, Footer, Navigation, etc.
│   ├── product_info.html   # Produktdetailseite
│   └── product_listing.html # Kategorie-Listing
├── source/
│   ├── boxes.php           # Box-Lade-Logik
│   └── smarty_plugins/     # Smarty-Plugins (Strain-Badges, etc.)
└── lang/
    ├── german/             # Deutsche Sprachdateien
    └── english/            # Englische Sprachdateien
```

## Wichtige Merkmale

- **Keine Sidebar** – Boxen intelligent auf Header, Footer, Startseite verteilt
- **Mobile First** – Bottom Navigation Bar, Offcanvas-Menu
- **Speed-optimiert** – CSS Layers, content-visibility, native lazy loading
- **SEO 2026** – Schema.org Product/BreadcrumbList/AggregateRating, GEO-ready
- **KK-Mega-Menu** kompatibel (Bootstrap 5 Vanilla JS Implementierung)
- **Shop-Komprimierung** kompatibel (COMPRESS_JAVASCRIPT, CSS-Minification)
- **Font Awesome 4** Icons in `<span>`-Tags (nicht `<i>`)
- **Uptain/Brevo** fuer Newsletter (extern via JS, keine Template-Box)
- **Trusted Shops** dynamisch via JS geladen (async/defer fuer CWV)

## Konfiguration

Alle Template-Einstellungen werden ueber Konstanten mit dem Prefix `MRH_` gesteuert.
Siehe `config/config.php` fuer die vollstaendige Liste.

## Hinweise

- Dieses Template benoetigt **kein jQuery**
- Bootstrap 5.3 wird via CDN geladen und nutzt `data-bs-*` Attribute
- Alle Icons verwenden `<span class="fa fa-*">` statt `<i>`
- Die Shop-eigene CSS/JS-Komprimierung ist vollstaendig kompatibel
