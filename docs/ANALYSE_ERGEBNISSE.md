# Template-Analyse: mr-hanf.de (bootstrap4)

## 1. Aktuelle Library-Versionen (IST-Zustand)

| Library | Version | Status |
|---------|---------|--------|
| Bootstrap CSS/JS | 4.6.1 | Veraltet (EOL) |
| jQuery | 3.x (minified) | Wird in BS5 nicht mehr benötigt |
| Font Awesome | 6 Pro (all.min.css) | Aktuell |
| Smarty Template Engine | smarty_4 | Kompatibel |
| PHP | 8.3 | Aktuell |
| modified eCommerce | v2.0.7.2 rev 14622 | Stabil |

## 2. Template-Struktur (1490 Dateien)

### Hauptverzeichnisse
- `admin/` - Admin-Bereich (Reklamation, Mail-Templates)
- `boxes/` - Sidebar-Boxen (21 HTML-Templates)
- `buttons/` - Mehrsprachige Button-Grafiken
- `config/` - Template-Konfiguration (config.php, banners.php)
- `css/` - 35 CSS-Dateien
- `favicons/` - Favicon-Set
- `images/` + `img/` - Bilder, Payment/Shipping Icons
- `includes/` - PHP-Klassen, JavaScript
- `javascript/` - 48 JS-Dateien
- `lang/` - Sprachdateien (DE, EN, FR, ES)
- `mail/` - E-Mail-Templates (5 Sprachen)
- `module/` - Hauptmodule (70+ HTML-Templates)
- `smarty/` - Custom Smarty-Plugins
- `source/` - PHP-Quellcode (Boxes, Includes)
- `webfonts/` - OpenSans + Custom Fonts

## 3. Identifizierte Custom-Module & Erweiterungen

### Kern-Module (MÜSSEN migriert werden)
1. **Seedfinder** - Komplexes Suchmodul mit eigenem CSS/JS (7+ JS-Dateien, eigenes CSS)
2. **Reklamation** - Beschwerdeformular mit Admin-Backend + PDF-Export
3. **Blog-System** - Blog-Kategorien, Posts, Suche
4. **Free Shipping Bar** - Versandkostenfreie-Lieferung Fortschrittsbalken
5. **Gift Cart** - Gutschein-System
6. **Newsletter Overlay** - Popup-Newsletter
7. **Advanced Contact** - Erweitertes Kontaktformular (shopmodule)
8. **Product Compare** - Produktvergleich
9. **FAQ Manager** - FAQ-Verwaltung

### Integrierte Drittanbieter-Module
1. **contentAnywhere** - Content-Manager-Blöcke per coID (12+ Referenzen)
   - coIDs: 1003206, 1003207, 1003212, 1003215, 1003236, 1003237, 1003241, 1003256, 1003275, 1003278, 1003279
2. **Cookie Consent** (oil.min.js)
3. **Mailhive Newsletter**
4. **Shariff Social Media Buttons**
5. **EasyZoom** - Produktbild-Zoom
6. **Pushy** - Off-Canvas Navigation
7. **LazyLoad** (lazysizes)
8. **BSCarousel** - Bestseller-Karussell
9. **Traffic Light** - Lagerampel (Smarty-Plugin)
10. **Customers Notice** - Kundenhinweise/Countdown/Overlay

### Smarty-Plugins (Custom)
- `modifier.contentAnywhere.php` - Content-Blöcke laden
- `modifier.bs4button.php` - Bootstrap-Buttons generieren
- `modifier.inserttags.php` - Tags einfügen
- `modifier.stripTags.php` - Tags entfernen
- `function.traffic_light.php` - Lagerampel

## 4. Produkt-Info Varianten
- `aaa_produkt_info.html` - Haupt-Produktseite (Seeds)
- `non_seeds_info.html` - Nicht-Seeds Produkte
- `seedling.html` - Setzlinge
- `usa_STrain-patch.html` - USA Strains
- `Test/` - 9 Test-Varianten (Tabs, Accordion, 3-spaltig)

## 5. Produkt-Listing Varianten
- `product_listing_v1.html` - Standard-Listing
- `us_gentics_v1.html` - US Genetics Listing
- `promotion_product_listing_v1.html` - Promotion Listing

## 6. Produkt-Optionen Varianten
- `multi_options_1.html` - Multi-Optionen
- `product_options_dropdown.html` - Dropdown-Optionen
- `product_options_selection.html` - Auswahl-Optionen
- `table_listing.html` - Tabellen-Darstellung

## 7. Breaking Changes BS4 → BS5.3

### Kritische Änderungen
1. **jQuery entfernt** → Vanilla JS (betrifft bs4.js, pushy.js, easyzoom.js, etc.)
2. **data-toggle → data-bs-toggle** (alle Modals, Dropdowns, Collapse)
3. **data-dismiss → data-bs-dismiss** (Modals, Alerts)
4. **data-target → data-bs-target** (Modals, Collapse)
5. **.sr-only → .visually-hidden**
6. **.text-muted → .text-body-secondary**
7. **.float-left/right → .float-start/end**
8. **.ml-/mr-/pl-/pr- → .ms-/me-/ps-/pe-**
9. **.no-gutters → .g-0**
10. **.badge-* → .bg-***
11. **navbar-dark deprecated** → data-bs-theme="dark"
12. **Neuer xxl Breakpoint** (1400px)
13. **Offcanvas** als native Komponente (ersetzt Pushy)
14. **CSS Variables** für Theming

## 8. contentAnywhere Referenzen (Live-Shop)

| coID | Verwendung | Datei |
|------|-----------|-------|
| 1003206 | Footer Content | index.html |
| 1003207 | US Genetics Listing | product_listing/us_gentics_v1.html |
| 1003212 | US Genetics Top | product_listing/us_gentics_v1.html |
| 1003215 | Shipping-Hinweis (Lieferzeit) | product_info + product_listing |
| 1003236 | Samen Shop Kategorie | categorie_listing/samen_shop.html |
| 1003237 | Newsletter Box | box_newsletter.html |
| 1003241 | Topbar Saisonale Aktion | topbar.html |
| 1003256 | Footer Hauptcontent | index.html |
| 1003275 | Seedling Beschreibung | seedling_list_shortdescription.html |
| 1003278 | Hersteller Übersicht | manufacturers_overview.html |
| 1003279 | Seedfinder Content | seedfinder.html |

## 9. JavaScript-Architektur

### Synchron geladen
- jquery.min.js
- bootstrap.bundle.min.js
- bscarousel.min.js
- prepbigmenu.min.js
- preparemenu.min.js
- bs4.js (Custom Template JS)
- oil.min.js (Cookie Consent)

### Deferred geladen
- pushy.min.js
- jquery.lazysizes.min.js
- jquery.alertable.min.js
- customers_notice.js
- mailhive_newsletter.js
- easyzoom.min.js
- touchuse.min.js

### Extra JS (PHP-generiert)
- default.js.php
- cookieconsent.js.php
- get_states.js.php
- free_shipping_bar.js.php
- product_compare.js.php
- psw-sichtbar.php
- advanced_search_blog.js.php

## 10. CSS-Architektur

### Haupt-CSS
- `css/bootstrap/bootstrap.min.css` (BS 4.6.1)
- `css/bs4.css` (7464 Zeilen - Haupt-Template-CSS)
- `custom.css` (Shop-spezifische Anpassungen)
- `stylesheet.css` / `stylesheet.min.css` (leer)

### Modul-CSS
- seedfinder-combined.css/min.css
- reklamation.css
- blog.css
- cookieconsent.css
- easyzoom.css
- pushy.css
- product_compare.css
- product_filter_properties.css
- shopmodule_advanced_contact.css
- shop_reviews.css
- manufacturers_overview.css
- navbar.css
- pagination_layout.css
- shariff.min.css
- affiliate.css
- avg_progress.css
