# Changelog

## 2026-04-24 – Schema.org Phase 1: Microdata entfernt, nur JSON-LD Includes

**Ziel:** Alten hardcodierten Schema-Code (Microdata-Attribute und veraltete JSON-LD Bloecke) aus den Haupt-Templates entfernen. Schema.org wird kuenftig ausschliesslich ueber die modularen JSON-LD Include-Dateien (`schema_org_product.html`, `schema_org_blogposting.html`) gesteuert. Das macht den Code sauber, wartbar und optimal fuer KI-Suchmaschinen (GEO).

| Datei | Repo | Aenderung |
|-------|------|----------|
| `tpl_parts/content_head.html` | Template-MRH-2026 | 2 alte JSON-LD Bloecke entfernt (Organization + WebSite/SearchAction) |
| `module/product_info/seeds_info.html` | Template-MRH-2026 | Alle itemscope/itemprop/itemtype Attribute entfernt (article, brand, name, offers, description) |
| `module/product_info/usa_STrain-patch.html` | Template-MRH-2026 | Alle itemscope/itemprop/itemtype Attribute entfernt (identisch zu seeds_info) |
| `module/blog_post.html` | Template-MRH-2026 | Alle Microdata entfernt (BlogPosting, author, headline, image, articleBody, datePublished) |
| `module/includes/blog_post_listing.html` | Template-MRH-2026 | Alle Microdata entfernt (Blog, blogPosts, BlogPosting, image, headline, description) |
| `module/product_info.html` | Template-MRH-2026 | Alle itemscope/itemprop/itemtype Attribute entfernt |
| `module/product_info/aaa_produkt_info.html` | Template-MRH-2026 | Alle Microdata entfernt |
| `module/product_info/non_seeds_info.html` | Template-MRH-2026 | Alle Microdata entfernt |
| `module/product_info/product_info_v1.html` | Template-MRH-2026 | Alle Microdata entfernt |
| `module/product_info/product_info_v1-.html` | Template-MRH-2026 | Alle Microdata entfernt |
| `module/product_info/seedling.html` | Template-MRH-2026 | Alle Microdata entfernt |
| `module/includes/product_listing_include.html` | Template-MRH-2026 | Alter ItemList JSON-LD Block entfernt |
| `module/categorie_listing/samen_shop.html` | Template-MRH-2026 | Alter Product JSON-LD Block entfernt (267 Zeilen) |
| `module/seedfinder_product_cards.html` | Template-MRH-2026 | Schema-Relikte entfernt (meta, link) |
| `module/includes/price_info.html` | Template-MRH-2026 | 4 Microdata-Stellen entfernt |
| `module/includes/price_box.html` | Template-MRH-2026 | 1 Microdata-Stelle entfernt |
| `module/includes/product_gallery.html` | Template-MRH-2026 | 6 Microdata-Stellen entfernt |
| `module/includes/list_shortdescription.html` | Template-MRH-2026 | 12 Microdata-Stellen entfernt |
| `module/includes/usa_list_shortdescription.html` | Template-MRH-2026 | 12 Microdata-Stellen entfernt |
| `module/includes/seedling_list_shortdescription.html` | Template-MRH-2026 | 12 Microdata-Stellen entfernt |
| `module/includes/faq_product.html` | Template-MRH-2026 | 5 Microdata-Stellen entfernt |
| `module/breadcrumb.html` | Template-MRH-2026 | 6 Microdata-Stellen entfernt |
| `module/faq_manager.html` | Template-MRH-2026 | 9 Microdata-Stellen entfernt |
| `module/graduated_price.html` | Template-MRH-2026 | 5 Microdata-Stellen entfernt |
| `boxes/box_best_sellers.html` | Template-MRH-2026 | 11 Microdata-Stellen entfernt |
| `boxes/box_last_viewed.html` | Template-MRH-2026 | 6 Microdata-Stellen entfernt |
| `boxes/box_specials.html` | Template-MRH-2026 | 6 Microdata-Stellen entfernt |
| `boxes/box_whatsnew.html` | Template-MRH-2026 | 6 Microdata-Stellen entfernt |
| `module/includes/logobar.html` | Template-MRH-2026 | Alter Organization JSON-LD Block entfernt |
| `config/content-snippets/faq-samen-shop.html` | Template-MRH-2026 | Alle FAQPage Microdata entfernt |
| `module/product_info/Test/*.html` (9 Dateien) | Template-MRH-2026 | Alle Microdata aus Test-Templates entfernt |

**Ergebnis:** 0 verbleibende Microdata-Stellen in allen HTML-Templates (ausser den schema_org_*.html Include-Dateien). Die JSON-LD Include-Aufrufe am Ende der Produkt- und Blog-Templates bleiben erhalten. Die manufacturer_info.html und manufacturers_overview.html behalten ihre modernen JSON-LD Bloecke (bereits korrekt hinter MRH_SCHEMA_ORG Konstante).

## 2026-04-23 – Fix: Slider-Karten von 5 auf 4 pro Viewport

**Problem:** Das Inline-JS (`mrh-autoslider.js`) injiziert CSS direkt in den `<head>` mit `.mrh-as-track > .listingbox { flex: 0 0 calc((100%-64px)/5) }` – also 5 Spalten. Die externe CSS-Datei `mrh-autoslider.css` hatte zwar 4-Spalten-Regeln, aber fuer die falschen Selektoren (`.mrh-slider-item` statt `.mrh-as-track > .listingbox`).

**Loesung:** CSS-Override mit `!important` in `mrh-autoslider.css` fuer die tatsaechlichen Klassen `.mrh-as-track > .listingbox` mit `calc((100% - 48px) / 4)` (GAP=16px * 3 Gaps = 48px, geteilt durch 4). Responsive: 3 Spalten unter 999px, 2 Spalten unter 599px.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-autoslider.css` | Template-MRH-2026 | Override: .mrh-as-track > .listingbox von /5 auf /4 mit !important |

## 2026-04-23 – Fix: Produktlisting von 5-spaltig auf 4-spaltig

**Problem:** Das Produktlisting-Grid zeigte 5 Spalten (`repeat(5, 1fr)`), was die Karten zu schmal machte.

**Loesung:** `repeat(5, 1fr)` auf `repeat(4, 1fr)` geaendert in `mrh-custom.css`.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Grid: repeat(5, 1fr) → repeat(4, 1fr) |

## 2026-04-23 – Fix: Product Options Radio-Inputs (Compactor-Bug)

**Problem:** Der HTML-Compactor entfernte Whitespace zwischen `<input` und `type="radio"` in mehrzeiligen Tags, was `<inputtype="radio">` erzeugte – ungueltige HTML-Elemente, die nicht klickbar waren.

**Loesung:** Alle `<input>` Tag-Attribute in `multi_options_1.html` auf eine einzige Zeile gesetzt.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `module/product_options/multi_options_1.html` | Template-MRH-2026 | Input-Tags einzeilig fuer Compactor-Kompatibilitaet |

## 2026-04-22 – Fix: Eigenschafts-Tabelle Hoehe begrenzen (Seedfinder + Vergleichsliste)

**Problem:** Die Eigenschafts-Tabellen (Geschlecht, THC, Bluetezeit etc.) in den Produkt-Karten auf der Seedfinder-Seite und der Vergleichsliste hatten keine Hoehenbegrenzung. Bei Produkten mit vielen Attributen wurden die Karten extrem lang und ungleichmaessig.

**Loesung:** CSS max-height 220px mit Scrollbar fuer beide Seiten:
- `#seedfinder_module .mrh-sf-scroll.mrh-detail-table` (Seedfinder)
- `.product-compare-page .mrh-sf-scroll.mrh-detail-table` (Vergleichsliste)
- overflow-y: auto, scrollbar-width: thin, 4px Webkit-Scrollbar

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | max-height 220px + Scrollbar fuer Seedfinder + Vergleichsliste |

## 2026-04-22 – Rewrite: Altersverifizierung v2.0.0 (BS5.3 + Vanilla JS)

**Problem:** Das Altersverifizierung-Modal (age_verification.php v1.0.3) nutzte BS4-Klassen (ml-4, btn-block, center-block), jQuery-abhaengige Modal-API ($().modal("show")), und einen falschen Bild-Pfad (/templates/bootstrap4/img/logo_head.png statt /templates/tpl_mrh_2026/img/logo_head.png). Dadurch fehlte das Bild und das Modal funktionierte nicht korrekt mit BS5.3.

**Loesung:** Kompletter Rewrite des bootstrap-Modus:
- BS5.3 Modal mit data-bs-* Attributen und modal-dialog-centered
- Vanilla JS statt jQuery ($().modal → new bootstrap.Modal())
- Dynamischer Bild-Pfad ueber CURRENT_TEMPLATE Konstante
- Wireframe-Design: Zentriert, shadow-lg, border-radius 1rem, clean Typography
- SameSite=Lax Cookie-Attribut fuer moderne Browser
- Legacy default-Modus (modalBox) bleibt unveraendert fuer Abwaertskompatibilitaet

| Datei | Repo | Aenderung |
|-------|------|----------|
| `includes/extra/application_bottom/age_verification.php` | Template-MRH-2026 | v2.0.0: BS5.3 + Vanilla JS Rewrite |

## 2026-04-22 – Fix: Seedfinder Badge border-radius Override

**Problem:** `seedfinder-combined.min.css` setzt `border-radius: 3px` auf `#seedfinder_module .mrh-sf-badge-row .mrh-type-badge` und ueberschreibt damit den TPL-Konfigurator-Wert `var(--tpl-badge-radius, 50rem)`.

**Loesung:** Override in `mrh-custom.css` mit gleicher Spezifitaet, aber spaeterer Position im Stylesheet.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Badge border-radius Override: var(--tpl-badge-radius, 50rem) |

## 2026-04-22 – Refactor: Seedfinder-Karten Lagerampel v34.2.0 (Punkt neben Preis + Hover-Dropdown)

**Problem:** Die kompakte Lagerampel v34.1.0 ("Auf Lager – 3 Samen, 5 Samen") nahm immer noch eine eigene Zeile ein und verschob den Preis nach unten.

**Loesung:** Gruener Punkt direkt neben dem Preis:
- Nur ein 10px gruener Punkt links neben dem Preis (kein Text, kein Extra-Platz)
- Bei mehreren Attributen: Hover-Dropdown zeigt die verfuegbaren Varianten
- Bei nur 1 Attribut: Nur der Punkt, kein Dropdown
- Nicht lagernd: Kein Punkt (Ausverkauft-Badge im Preis-Bereich bleibt)
- Footer-Hoehe jetzt 100% einheitlich ueber alle Karten

| Datei | Repo | Aenderung |
|-------|------|----------|
| `module/seedfinder_product_cards.html` | Template-MRH-2026 | v34.2.0: Lagerampel als Punkt neben Preis mit Hover-Dropdown |
| `css/mrh-custom.css` | Template-MRH-2026 | Lagerampel v34.2.0: .mrh-sf-stock-indicator, .mrh-sf-stock-dropdown |

## 2026-04-22 – Refactor: Seedfinder-Karten Lagerampel v34.1.0 (attribute-text-list ersetzt)

**Problem:** Die `attribute-text-list` im Footer (jede Variante mit Preis + Lager-Warnung) brauchte ~70px pro Variante und verursachte unterschiedliche Footer-Hoehen. Karten mit Lager-Info hatten versetzt stehende Preise und Buttons.

**Loesung:** Kompakte Lagerampel statt voller Attribut-Liste:
- Lagernd: Gruener Punkt + "Auf Lager" + Attribut-Badges (z.B. "3 Samen", "5 Samen")
- Nicht lagernd: Nichts anzeigen (Ausverkauft wird bereits im Preis-Bereich gehandelt)
- Footer-Hoehe jetzt einheitlich ueber alle Karten
- Nutzt existierende Smarty-Variable `{#availability_in_stock_title#}` (DE: "Auf Lager", EN: "In stock", FR: "En stock")

| Datei | Repo | Aenderung |
|-------|------|----------|
| `module/seedfinder_product_cards.html` | Template-MRH-2026 | v34.1.0: attribute-text-list durch kompakte Lagerampel ersetzt |
| `css/mrh-custom.css` | Template-MRH-2026 | Lagerampel-CSS: .mrh-sf-stock-dot, .mrh-sf-stock-label, .mrh-sf-attr-badge |

## 2026-04-22 – Fix: Seedfinder-Karten Footer-Alignment per CSS (Preise + Buttons auf gleicher Hoehe)

**Problem:** Karten mit Lager-Info (`attribute-text-list`, z.B. "5 Samen / 52,99 EUR / nur noch 2 verfuegbar") haben einen ~70px hoeheren Footer als Karten ohne. Dadurch standen Preise und Buttons auf unterschiedlicher Hoehe innerhalb einer Reihe. Der JS-Equalizer (v1.4.0) konnte das Problem nicht zuverlaessig loesen, da `minHeight` auf dem Footer die Preiszeile nicht korrekt ausrichtet.

**Loesung:** Reiner CSS-Fix ohne JavaScript:
- `.card-body.pt-0` bekommt `flex-grow: 1` → Tabellen-Bereich fuellt den Zwischenraum zwischen Badges und Footer
- `.card-footer` bekommt `display: flex; flex-direction: column; justify-content: flex-end` → Preis und Buttons am unteren Rand des Footers, Lager-Info am oberen Rand
- Ergebnis: Preise und Buttons auf gleicher Hoehe in jeder Reihe, unabhaengig von Lager-Info

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Seedfinder Footer-Alignment: flex-grow + flex-column justify-end |

## 2026-04-22 – Fix: Seedfinder Row Equalizer v1.4.0 (Timing-Bug bei lazy-loaded Bildern)

**Problem:** Der Equalizer v1.3.0 lief bei DOMContentLoaded und window.load, aber zu diesem Zeitpunkt waren die Bilder (lazy loading) noch nicht geladen. Dadurch hatten die Karten beim Messen noch keine korrekte Hoehe, die Reihen-Gruppierung schlug fehl, und die Footer-Hoehen wurden nicht angeglichen. Karten mit Lager-Info (attribute-text-list) hatten einen 70px hoeheren Footer als Karten ohne.

**Loesung:** Equalizer v1.4.0 mit verbessertem Timing:
- Selektor auf `#products-grid .card.h-100` beschraenkt (keine Kategorie-Karten mehr)
- Mehrere Delay-Stufen nach DOMContentLoaded (300ms, 800ms) und nach window.load (500ms, 1500ms)
- Bilder-Laden-Abwartung: Jedes Bild in den Karten bekommt onload-Listener
- IntersectionObserver: equalize wenn Karten sichtbar werden (fuer lazy loading)
- MutationObserver: equalize bei AJAX-Nachladen (Seedfinder-Filter)

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-sf-equalizer.js.php` | Template-MRH-2026 | v1.4.0: Timing-Bug gefixt, Selektor auf #products-grid beschraenkt |

## 2026-04-22 – Feature: Seedfinder Row Equalizer (Karten-Alignment)

**Problem:** Im Seedfinder-Listing starten Badges und Eigenschafts-Tabelle auf unterschiedlicher Hoehe, wenn Produktnamen unterschiedlich lang sind (z.B. "Purple Urkle x Blue Pyramid (Gold Line)" vs. "Papayton").

**Loesung:** Neuer JS-Equalizer (`mrh-sf-equalizer.js.php`) gleicht die Hoehe des Name-Bereichs und Badge-Bereichs pro Kartenreihe automatisch an. Reagiert auf Resize und AJAX-Nachladen (Seedfinder-Filter).

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-sf-equalizer.js.php` | Template-MRH-2026 | NEU: Row Equalizer v1.0.0 |

## 2026-04-22 – Fix: picto.templatestyle @media 1401px ueberschreibt TPL-Konfigurator

**Problem:** Im `@media (min-width: 1401px)` Block (Sektion 27a) wurden `padding`, `font-size` und `gap` fuer `.picto.templatestyle` mit festen Werten ueberschrieben. Dadurch griff der TPL-Konfigurator (5px 5px) nicht auf Bildschirmen >1400px.

**Fix:** Feste Werte durch CSS-Variablen mit Fallback ersetzt: `var(--tpl-picto-padding, 10px 18px)` etc.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Sektion 27a: picto.templatestyle nutzt jetzt CSS-Variablen |

## 2026-04-22 – Fix v1.5.0: megamenu-config + core Icon-Rendering (3 Bugs in 1 Release)

**Bugs (alle seit v1.4.0 vorhanden):**
1. `MODULE_MRH_DASHBOARD_STATUS` Konstante im Frontend nicht verfuegbar → Datei gab nichts aus
2. Admin-Sprachdatei `mrh_dashboard.php` hat `die()` wenn `_VALID_XTC` nicht definiert → killte gesamten PHP-Output
3. `mrh-listing-desc.js.php` gibt eigene `</script>` Tags aus → nachfolgender Output von megamenu-config landete ausserhalb des Script-Blocks
4. `mrh-core.js.php` Zeile 788: Dashboard-Icons wurden als Text statt als `<i>` Tags gerendert

**Fixes:**
- `mrh-megamenu-config.js.php` v1.5.0: MODULE_MRH_DASHBOARD_STATUS durch Cache-Datei-Check ersetzt + `_VALID_XTC` vor Admin-Lang-Include definiert + Output in eigenen `</script><script>` Block gewrappt
- `mrh-core.js.php`: Icon-HTML von `<span>column.icon</span>` zu `<span><i class="column.icon"></i></span>` geaendert

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-megamenu-config.js.php` | Template-MRH-2026 | v1.5.0: 3 Bugs gefixt (Status-Check, die()-Bug, Script-Block) |
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | Icon-Rendering: `<i class>` statt Text |

## 2026-04-21 – Fix: mrh-megamenu-config.js.php MODULE_MRH_DASHBOARD_STATUS

**Problem:** `MODULE_MRH_DASHBOARD_STATUS` ist zwar in der DB `true`, aber die Konstante wird im Frontend-Kontext (auto_include) nicht definiert. Der Check in Zeile 16 blockierte daher die gesamte Ausgabe → `MRH_MOBILE_ICONS` und `MRH_MOBILE_PROMOS` blieben `undefined`.

**Fix (v1.4.1):** Den `MODULE_MRH_DASHBOARD_STATUS`-Check komplett entfernt. Die Existenz der Cache-Datei (`megamenu_config.json`) ist ein ausreichender Beweis, dass das Dashboard-Modul aktiv ist und die Datei geschrieben hat.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-megamenu-config.js.php` | Template-MRH-2026 | v1.4.1: MODULE_MRH_DASHBOARD_STATUS Check entfernt |

## 2026-04-21 – Feat: Mobile Menu Icons, Promos & Telefonnummer
**Erweiterung:** Mobile Offcanvas-Menü zeigt jetzt Dashboard-Daten an:
1. **Kategorie-Icons:** FA6 Icons aus dem Dashboard (Mobile Menü Tab) werden vor den Kategorienamen angezeigt
2. **Promo-Banner:** HTML-Content oder Banner aus dem Dashboard, Position oben/unten konfigurierbar
3. **Telefonnummer:** +43 512 312 411 als klickbarer Link im Offcanvas-Header
4. **mrh-megamenu-config.js.php v1.4.0:** Gibt `MRH_MOBILE_ICONS` und `MRH_MOBILE_PROMOS` ans Frontend aus

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-megamenu-config.js.php` | Template-MRH-2026 | v1.4.0: mobile_icons + mobile_promos Output |
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | MobileMenu: applyIcons(), insertPromos(), phoneBar |
| `css/mrh-custom.css` | Template-MRH-2026 | Phone-Bar, Cat-Icons, Promo-Bereiche Styling |

## 2026-04-21 – Feat: Mobile Menu Offcanvas

**Problem:** Das Burger-Menü (öffnet sich nicht. `#toggle_mobilemenu` hat keine `data-bs-toggle` Attribute und `#mobiles_menu` ist ein einfaches `<nav>` ohne Bootstrap Offcanvas-Klassen. Das RevPlus-Template nutzte `mmenu.js` (jQuery Plugin), das nicht mehr geladen wird.

**Loesung:** `MRH.MobileMenu.init()` in `mrh-core.js.php`:
1. Wrappt `#mobiles_menu` in Bootstrap Offcanvas-Struktur (Header + Close-Button + Body)
2. Setzt `data-bs-toggle="offcanvas"` + `href` auf den Toggle-Button
3. Submenu-Toggle per Klick auf Pfeil-Icons (auf/zu)
4. CSS: Kategorie-Navigation Styling im Offcanvas

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | MRH.MobileMenu Modul hinzugefügt |
| `css/mrh-custom.css` | Template-MRH-2026 | Mobile Menu Offcanvas Styling |

## 2026-04-21 – Refactor: StickyHeader v2 (RevPlus-Muster)

**Problem:** Trotz FAW_SCROLL_PAUSED und CSS-Isolation hackt der Sticky Header weiterhin. Die FAW Scroll-Handler feuern bei JEDEM Scroll-Event (nicht nur beim Sticky-Wechsel) und scannen alle 2671 Elemente. data-Attribute auf body triggern den FAW MutationObserver.

**Loesung:** Komplett-Rewrite nach dem RevPlus-Template-Muster:
1. **`.fixed` CSS-Klasse** statt `data-sticky` Attribute → FAW MutationObserver ignoriert class-Aenderungen auf Header
2. **`padding-top` auf `.page-wrapper`** statt Spacer-Element → kein zusaetzliches DOM-Element
3. **Kein `body[data-sticky-active]`** → kein FAW-Trigger auf body
4. **`.sticky-hidden` Klasse** statt `data-sticky-hidden` → konsistentes Muster
5. **Entfernt:** Spacer-Element, FAW Flood-Fix CSS, body data-Attribute, FAW_SCROLL_PAUSED Integration

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | StickyHeader komplett neu: .fixed + padding-top |
| `css/mrh-custom.css` | Template-MRH-2026 | Alle [data-sticky] → .fixed, alte FAW-Workarounds entfernt |

## 2026-04-21 – Feat: FAW Widget v1.1.0 + StickyHeader FAW_SCROLL_PAUSED

**Problem:** Das FAW Widget hat 4 Scroll-Event-Handler die bei jedem Scroll-Event debounced Rescans aller ~2671 Elemente ausloesen (D/q Contrast, T Accessible Names, U Landmarks, L Alt-Text). Beim Sticky-Wechsel fuehrt das zu 6000-9000 getComputedStyle-Aufrufen/s.

**Fix (FAW Widget v1.1.0):**
1. **`window.FAW_SCROLL_PAUSED` API** – Globales Flag das externe Scripts setzen koennen
2. **Alle 4 Scroll-Handler** pruefen `FAW_SCROLL_PAUSED` vor Rescan
3. **Optimierte Selektoren** – D(), q() verwenden `:not([data-faw-*-processed])` im querySelectorAll
4. **Font-Size Shortcut** – o() bei fontSize=1 nur neue Elemente ohne data-faw-orgFontSize

**Fix (StickyHeader):**
- `setHeaderState()` setzt `FAW_SCROLL_PAUSED=true` vor DOM-Aenderungen
- Nach 2 requestAnimationFrame Frames wird `FAW_SCROLL_PAUSED=false` gesetzt

| Datei | Repo | Aenderung |
|-------|------|----------|
| `fietz-accessibility-widget.js` | fietz-accessibility-widget | FAW_SCROLL_PAUSED + optimierte Selektoren |
| `fietz-accessibility-widget.min.js` | fietz-accessibility-widget | Minifizierte Produktionsversion |
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | FAW_SCROLL_PAUSED Integration in setHeaderState() |

## 2026-04-21 – Fix: FAW getComputedStyle-Flood beim Sticky-Wechsel (CSS-Versuch)

**Problem:** Das Fietz Accessibility Widget (FAW) ueberwacht 2671 Elemente auf der Seite. Beim Sticky-Wechsel (position:relative → fixed) loest der Browser ein Layout-Recalc aus. Das FAW reagiert darauf mit 6000-9000 getComputedStyle-Aufrufen pro Sekunde, was Scroll-Ruckler ("Hacken") verursacht. Haupttrigger: Die Free Shipping Bar Progress Fill hat `transition:width 0.5s` und der Track hat `transition:all` – diese Transitions loesen bei jedem Layout-Wechsel eine Kaskade von FAW-Rescans aus.

**Fix:**
1. **CSS:** Shipping Bar Transitions deaktiviert waehrend sticky (`body[data-sticky-active]`)
2. **CSS:** `contain:layout style paint` auf `#mrh-shipping-bar` und `#main-header[data-sticky]`
3. **CSS:** `contain:strict` + `will-change:transform` auf `.mrh-progress-track`
4. **CSS:** `isolation:isolate` auf sticky Header fuer eigenen Stacking Context

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | FAW Isolation CSS-Regeln |
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | Kommentare erweitert |

## 2026-04-21 – Fix: Sticky Header Scroll-Hang (Topbar/ShippingBar Kompensation)

**Problem:** Beim Scrollen "haengt" die Seite manchmal an der Topbar. Ursache: Topbar (27px) + ShippingBar (37px) = 64px sitzen UEBER dem Header im DOM-Flow. Wenn der Header sticky wird (position:fixed, top:0), verschwinden diese 64px aus dem Flow, aber der Spacer kompensiert nur die Header-Hoehe (173px). Zusaetzlich schrumpft der Header im Sticky-Modus auf 108px (NavRow ausgeblendet, Logo verkleinert), was einen 65px Content-Sprung verursacht.

**Fix:**
1. **CSS:** `body[data-sticky-active]` blendet `#mrh-topbar` + `#mrh-shipping-bar` aus (display:none)
2. **JS:** Spacer-Hoehe = Header (173px) + Bars (64px) = 237px – kompensiert den gesamten Bereich
3. **JS:** `activateAt` = Header + Bars (statt 2x Header) – frueherer, praeziserer Sticky-Wechsel
4. **JS:** `body[data-sticky-active]` Attribut wird gesetzt/entfernt beim Sticky-Wechsel
5. **JS:** Resize-Handler berechnet Bars-Hoehe dynamisch mit

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | `body[data-sticky-active]` Regeln fuer Topbar/ShippingBar |
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | StickyHeader: barsHeight, activateAt, body-Attribut |

## 2026-04-21 – Feature: Phase 1 Non-Seeds – Mini-Table fuer Non-Seed Produkte unterdrueckt

**Problem:** In Listing-Karten (Kategorie-Uebersichten) zeigen Non-Seed Produkte (Duenger, Growshop, Headshop, Cannabispflanzen etc.) die Seeds-spezifische Mini-Tabelle mit Sorte/THC/CBD an – Felder die fuer diese Produkte keine Bedeutung haben.

**Fix (2 Dateien, Repo: mrh-modified-autoinclude):**

1. **mrh_product_attributes.php v1.12.0** – `buildMiniTable()` Listing/Box-Kontext fuer `is_seed=0`: Zeigt die ersten 3 Custom Fields in der per Drag & Drop gespeicherten Reihenfolge aus dem Backend. Fallback auf Standard-Felder in gespeicherter `field_order`. Seeds-Listing bleibt unveraendert (Sorte/THC/CBD).

2. **mrh_product_attributes.php v1.12.0** – `buildBadgeHTML()` unterdrueckt Seeds-spezifische Badges (Gender: fem/reg/auto, Flowering: autoflower/photoperiod) fuer Non-Seed Produkte (`is_seed=0`). Manuell vergebene Pictos und Cannabis Cup Badges werden weiterhin angezeigt.

3. **mrh_product_attributes_listing.php v1.7.0** – Kategorie-basierte Auto-Erkennung fuer Produkte die NICHT in der DB sind: Wenn die aktuelle URL einen Non-Seed Pfad enthaelt (`/growshop/`, `/headshop/`, `/duenger/` etc.) — `/cannabispflanzen/` bewusst ausgenommen (Pflanzen haben THC/CBD/Sorte), wird die Mini-Tabelle uebersprungen. Kein manuelles Produkt-Editing noetig.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `includes/external/mrh_product_attributes/mrh_product_attributes.php` | mrh-modified-autoinclude | is_seed=0 Check in buildMiniTable() + buildBadgeHTML() |
| `includes/extra/modules/product_listing_content_ready/mrh_product_attributes_listing.php` | mrh-modified-autoinclude | URL-basierte Non-Seed Erkennung + Datei erstmals ins Repo aufgenommen |

## 2026-04-21 – Fix: MegaMenu Dropdown nicht sichtbar + Mobile-Menü im Footer

**Problem 1:** Die CSS-Regel `#main-header:not([data-sticky]) .mrh-mega-dropdown { display: none !important }` blockiert das Dropdown auch wenn `.open` gesetzt ist, weil die `.open`-Regel niedrigere Spezifitaet hat und kein `!important` nutzt. Dadurch oeffnet sich das MegaMenu bei Mouseover nicht.

**Problem 2:** `#mobiles_menu` (Offcanvas-Mobilmenue) ist `display: block` und zeigt alle Kategorie-Links als LI-Liste unter dem Footer an (y=8779px).

**Fix:** Override-Regel mit gleicher Spezifitaet + `!important` fuer `.mrh-mega-dropdown.open`. `#mobiles_menu` standardmaessig auf `display: none` gesetzt.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Override fuer `.mrh-mega-dropdown.open` + `#mobiles_menu { display: none }` |

## 2026-04-21 – Fix: MRH Core JS Syntax-Fehler (fehlende Klammer in MegaMenu)

**Problem:** `MRH.MegaMenu.buildDropdown()` hatte eine fehlende schliessende `}` fuer den aeusseren `else`-Block (Fallback auf getCategoryConfig). Dadurch waren alle nachfolgenden Methoden (`assignToColumns`, `bindEvents`, `open`, `closeAll`, `markActive`) syntaktisch innerhalb von `buildDropdown` verschachtelt. Der JS-Parser meldete `Unexpected token ','` und das gesamte `window.MRH` Namespace wurde nie erstellt → StickyHeader, MegaMenu, ShippingBar, BackToTop etc. funktionierten nicht.

**Fix:** Fehlende `}` nach dem inneren `if/else`-Block (Modus A/B) eingefuegt, um den aeusseren `else`-Block korrekt zu schliessen. Syntax-Check mit `node --check` bestanden.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `javascript/extra/mrh-core.js.php` | Template-MRH-2026 | Fehlende `}` in buildDropdown eingefuegt (Zeile 755) |


## 2026-04-20 – Fix: Icon Font Protection v3.0 (FA7 Dyslexie-Schutz)

**Problem:** Wenn im Fietz Accessibility Widget die Dyslexie-Schrift aktiviert wird, injiziert das Widget:
```css
html *:not(.material-icons,.fa) { font-family: OpenDyslexic3,...  !important }
```
FA7-Icons haben NICHT die Klasse `.fa`, sondern `.fa-solid`, `.fa-regular` etc. Dadurch greift die `:not(.fa)` Ausnahme nicht und die Icon-Fonts werden durch OpenDyslexic3 ueberschrieben → Icons verschwinden.

**Fix:** CSS-Regeln die `font-family` auf den `::before` Pseudo-Elementen aller FA7-Klassen mit `!important` zuruecksetzen. Nutzt FA7 CSS Custom Properties als Werte (`var(--fa-family-classic, "Font Awesome 7 Pro")`). Deckt ab:
- FA7 Classic (Solid, Regular, Light, Thin)
- FA7 Brands
- FA7 Duotone (::before + ::after)
- FA7 Sharp
- Simple-Line-Icons (::before hinzugefuegt)

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Icon Font Protection v3.0: ::before Schutz fuer alle FA7-Stile + SLI |


## 2026-04-20 – Fix: Seedfinder Filter-Checkboxen Layout

**Problem:** Die Filter-Checkboxen im Seedfinder-Modal (`.mrh-filter-checkbox`) hatten `display: inline-block` und wurden nebeneinander statt untereinander angezeigt.

**Fix:** CSS-Override in `mrh-custom.css`:
- `.mrh-filter-values` auf `display: flex; flex-direction: column` gesetzt
- `.mrh-filter-checkbox` auf `display: flex; align-items: center` gesetzt
- Checkbox-Input, Value-Name und Count korrekt ausgerichtet

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Seedfinder Filter Checkbox Layout Fix (vertikal statt horizontal) |

# MRH Template Changelog

## 2026-04-20 – Fix: MRH_BADGES in product_info_include.html (Cross-Sell)

**Problem:** Cross-Sell-Boxen ("Kunden kauften auch") zeigten alte Text-Badges ("Feminisiert", "Photoperiodisch") statt der neuen serverseitigen Icon-Badges. Das Template `product_info_include.html` gab `MRH_BADGES` nicht aus.

**Fix:**
- `{$module_data.MRH_BADGES}` zwischen `lb_title` und `lb_desc` eingefuegt
- PHP-Enrichment in `also_purchased_products.php` hinzugefuegt (Repo: mrh-modified-autoinclude)
- JS-Skip in `mrh2026.js` fuer Listings mit vorhandenen serverseitigen Badges

| Datei | Repo | Aenderung |
|-------|------|----------|
| `module/includes/product_info_include.html` | Template-MRH-2026 | MRH_BADGES Ausgabe hinzugefuegt |
| `includes/modules/also_purchased_products.php` | mrh-modified-autoinclude | Badge-Enrichment fuer Cross-Sell |
| `admin/mrh_product_attributes.php` | mrh-modified-autoinclude | bgcolor/bordercolor im Save-Handler |
| `javascript/mrh2026.js` | Server-direkt | v7.1 Skip wenn serverseitige Badges vorhanden |

## 2026-04-20 – HOTFIX: Icon Font Protection v2.0 (FA7-kompatibel)

**Problem:** FontAwesome-Icons wurden nicht angezeigt oder getauscht. Die Icon Font Protection v1.0/v1.1 setzte `font-family: "Font Awesome 6 Free" ... !important` auf alle FA-Klassen. Aber der Shop nutzt **FontAwesome 7** (`fontawesome-7.css`), das CSS Custom Properties (`--fa-family-classic = 'Font Awesome 7 Pro'`) fuer die Schriftart verwendet. Der `!important`-Override mit FA6-Schriftnamen ueberschrieb die FA7-Variablen mit nicht-existierenden Fonts.

**Fix:** FA6-font-family-Block komplett entfernt. FA7 darf NICHT mit hardcoded font-family ueberschrieben werden. Nur Simple-Line-Icons Schutz beibehalten.

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Icon Font Protection v2.0: FA6-Block entfernt, nur SLI-Schutz |

## 2026-04-20 – Fix: Icon Font Protection v1.1 (FA-Icons sichtbar)

**Problem:** FontAwesome-Icons wurden nicht angezeigt. Der `::before`-Block im Icon Font Protection CSS (v1.0) setzte `font-family: inherit !important` auf alle FA-Pseudo-Elemente. Da FA-Icons ueber `::before` mit spezifischer `font-family` gerendert werden, vererbte `inherit` stattdessen die Dyslexie-Schrift.

**Fix:**
- `::before`-Block komplett entfernt (Zeilen 7124-7135 alt)
- Basis-Klassen-Block (.fa, .fas, .fa-solid etc.) beibehalten — schuetzt korrekt
- Simple-Line-Icons Schutz von `::before` auf Basis-Klassen geaendert
- Version v1.0 → v1.1

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Icon Font Protection v1.1: ::before-Block entfernt, SLI-Schutz korrigiert |

# MRH 2026 Template # Changelog

## 2026-04-17 – Mobile Overflow Fix + Listing Grid Breakpoints

**Aenderung 1 (Grid Breakpoints):** Listing-Grid auf Mobil verbessert:
- 576px: 2 Spalten mit `overflow: hidden` und `font-size: 0.85rem` auf Cards
- 399px: 1 Spalte (volle Breite) – verhindert abgeschnittene Cards

**Aenderung 2 (Overflow Fix):** Globaler `overflow-x: hidden` auf `html, body` und `max-width: 100vw` auf `.offcanvas` und `.mrh-mobile-overlay`. Verhindert horizontales Scrollen auf Mobilgeraeten (Ursache: Offcanvas-Elemente ragten ueber Viewport).

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Zeile 2276-2283: Grid Breakpoints, Zeile 6633-6644: Overflow Fix |

## 2026-04-16 – Slider max-width 1320px + Banner Bilder-Fix (data-src → src)

**Aenderung 1:** Slider-Section in `index.html` bekommt `max-width:1320px;margin:0 auto;` statt `container-fluid px-0` (User-Vorgabe: exakt 1320px).

**Aenderung 2 (Bilder-Fix):** Shop-PHP gibt Banner-HTML mit `src="data:,"` und `data-src="URL"` aus. Die Smarty-Filter in `banners.html`, `banners2.html` und `BANNERHOME` (index.html) wandeln jetzt `data-src` in `src` um und entfernen `src="data:,"`. Klasse `lazyload` wird durch `img-fluid lazyloaded` ersetzt. Kein JS-Lazy-Load mehr noetig fuer Banner.

Smarty-Replace-Kette:
```
|replace:'src="data:,"':''|replace:'data-src=':'loading="lazy" src='|replace:'lazyload':'img-fluid lazyloaded'|replace:' role="banner"':''
```

**Aenderung 3:** `MRH_LazyLoad` in `javascript/mrh2026.js` erweitert: Behandelt jetzt auch `<source data-srcset>` Elemente innerhalb von `<picture>` Tags (fuer zukuenftige Lazy-Load-Faelle).

| Datei | Repo | Aenderung |
|-------|------|----------|
| `index.html` | modified-shop-dev | Slider: `max-width:1320px`, BANNERHOME: data-src→src Fix |
| `tpl_parts/banners.html` | beide | BANNER1+2: data-src→src, img-fluid lazyloaded |
| `tpl_parts/banners2.html` | beide | BANNER3-6: data-src→src, img-fluid lazyloaded |
| `javascript/mrh2026.js` | Template-MRH-2026 | MRH_LazyLoad: `<source data-srcset>` Handling |

## 2026-04-13 – Photoperiodisch Text-Badge + Globale Badge-Config + CSS-Fix

**Aenderung 1:** Photoperiodisch-Badge zeigt jetzt NUR Text (kein Icon) in allen Sprachen (DE: Photoperiodisch, EN: Photoperiod, FR: Photopériode, ES: Fotoperíodo). Gilt fuer alle Seed-Produkte die nicht Autoflowering sind.

**Aenderung 2:** Badge-System ist jetzt global konfigurierbar im Admin unter Hilfsprogramme → MRH Produkteigenschaften → Einstellungen → Badge-Konfiguration. Icons, Farben und SVG-Pfade koennen dort zentral geaendert werden. Auf der Produkterstellungsseite wird ein Hinweis angezeigt.

**Aenderung 3:** CSS-Fix: `mb-2` / `mb-3` aus `mrh-picto-bar` in allen 8 Templates entfernt fuer korrekten Abstand.

| Datei | Aenderung |
|-------|----------|
| `css/mrh-custom.css` | Neuer Abschnitt 21g: `.mrh-badge-textonly` + `.mrh-badge-label` Styles |
| `module/product_info/*.html` (8 Dateien) | `mrh-picto-bar mb-3` → `mrh-picto-bar` |

## 2026-04-13 – Layout-Fix: MRH Detail-Tabelle vor Art.Nr./Lieferzeit

**Aenderung:** In allen 7 Produktdetail-Templates die Reihenfolge getauscht: MRH Attribut-Tabelle (`$mrh_mini_table`) wird jetzt VOR der Short Description (Art.Nr. + Lieferzeit) angezeigt, nicht danach.

| Datei | Aenderung |
|-------|----------|
| `module/product_info/aaa_produkt_info.html` | mrh_mini_table Block vor list_shortdescription.html verschoben |
| `module/product_info/seeds_info.html` | mrh_mini_table Block vor list_shortdescription.html verschoben |
| `module/product_info/non_seeds_info.html` | mrh_mini_table Block vor list_shortdescription.html verschoben |
| `module/product_info/seedling.html` | mrh_mini_table Block vor seedling_list_shortdescription.html verschoben |
| `module/product_info/usa_STrain-patch.html` | mrh_mini_table Block vor usa_list_shortdescription.html verschoben |
| `module/product_info/product_info_v1.html` | mrh_mini_table Block vor list_shortdescription.html verschoben |
| `module/product_info/product_info_v1-.html` | mrh_mini_table Block vor list_shortdescription.html verschoben |

## 2026-04-10 – ProductCompare v2.0.0: JS-Einbindung gefixt

**Bug:** Vergleichs-Button auf Produktseite sichtbar, aber `window.ProductCompare` undefined → Button ohne Funktion.
**Ursache:** `product_compare.js.php` hatte PHP-Guard `MODULE_PRODUCT_COMPARE_STATUS == 'true'` der fehlschlug → leerer Output. Zusaetzlich falscher CSS-Pfad (`bootstrap4` statt `tpl_mrh_2026`).
**Fix:** PHP-Guard entfernt, CSS-Pfad auf `DIR_TMPL` korrigiert, `updateAllButtons()` Selector erweitert fuer Buttons ohne `.btn-compare` Klasse.

| Datei | Aenderung |
|-------|----------|
| `javascript/extra/product_compare.js.php` | v2.0.0: PHP-Guard entfernt, CSS-Pfad korrigiert (DIR_TMPL), compareUrl mit Fallback, updateAllButtons() erkennt jetzt auch Buttons ohne .btn-compare (via onclick-Attribut-Check) |

## 2026-04-09 – Phase 3: Filter-System (Modal + Grundfilter-Leiste)

| Datei | Aenderung |
|-------|----------|
| `module/listing_filter.html` | Komplett umgebaut: Neue Grundfilter-Leiste (.mrh-filter-bar) mit Filter-Button, Hersteller-Dropdown, Sortierung, Artikel pro Seite, Ansicht-Toggle. Aktive Filter-Tags als Chips. |
| `module/seedfinder_filters_accordion.html` | v8.0.0: Desktop 3-spaltige Card → BS5 Modal (#mrhFilterModal). Mobile Bottom Sheet → BS5 Offcanvas (#mrhFilterOffcanvas). Alle DOM-Hooks fuer seedfinder_ajax.js beibehalten. |
| `javascript/seedfinder_accordion.js` | v8.0.0: jQuery komplett entfernt → Vanilla JS. BS5 Modal/Offcanvas API. Checkbox-Sortierung beibehalten. |
| `css/mrh-custom.css` | Phase 3 CSS appended: .mrh-filter-bar (Grundfilter-Leiste), #mrhFilterModal (Desktop Modal), #mrhFilterOffcanvas (Mobile Offcanvas), .mrh-filter-grid, .mrh-filter-section, Filter-Buttons. |

## 2026-04-09 – Phase 1: Kategorie-Header + template.css Bereinigung

| Datei | Aenderung |
|-------|----------|
| `module/product_listing.html` | Auf Server-Stand gebracht + Phase 1 Kategorie-Header: Bild links, H1 + Kurzbeschreibung rechts (Flexbox, responsive). Fallback ohne Bild: nur H1 + Desc. |
| `css/mrh-custom.css` | CSS-Klassen .mrh-cat-header, .mrh-cat-image, .mrh-cat-info, .mrh-cat-short-desc appended. Mobile: Bild ueber H1. |
| `css/template.css` (Server) | .lb-buttons-wrap a !important Regel entfernt (Zeile 2596). Alte btn-primary/btn-secondary Regeln in lb-buttons-wrap entfernt (Zeile 2829-2843). |

## 2026-04-09 – Fix: Konfigurator Merge-Logik (Farben + Buttons)

**Bug:** Beim Klick auf "Farben speichern" wurden die Button-Farben auf Defaults zurueckgesetzt und umgekehrt.

**Ursache:** Beide Formulare schreiben in dieselbe `colors.json`. Die alte Logik iterierte ueber alle 41 Keys und setzte fehlende Keys (die im anderen Formular sind) auf Defaults zurueck.

**Fix:** Merge-Logik in `templateconfig.php` – bestehende `colors.json` wird zuerst gelesen, dann werden NUR die Keys ueberschrieben, die tatsaechlich im POST gesendet wurden. Alle anderen Keys bleiben erhalten.

| Datei | Aenderung |
|-------|----------|
| `source/boxes/templateconfig.php` | Zeile 107-118: Komplettes Ueberschreiben durch Merge-Logik ersetzt. Bestehende colors.json wird gelesen, nur POST-Keys werden aktualisiert. |

## 2026-04-09 – mrh_color_vars.php geloescht

| Datei | Aenderung |
|-------|----------|
| `smarty/mrh_color_vars.php` | Komplett geloescht. War nie eingebunden, seit Konsolidierung DEPRECATED. Alle Farb-Variablen kommen aus general.css.php. |

## 2026-04-09 – Konsolidierung: Single Source of Truth fuer CSS-Variablen

**Betroffene Dateien:**

| Datei | Aenderung |
|-------|-----------|
| `css/general.css.php` | $defaults-Array um 10 fehlende Keys erweitert (tpl-secondary-color, tpl-btn-details-*, tpl-btn-wishlist-*, tpl-btn-compare-*). Alias-Variablen (--mrh-*) werden jetzt inline im :root-Block generiert. CSS-Array um pagination_layout.css und mrh-product-options.css erweitert. Inline-Style bekommt id="mrh-color-vars". |
| `css/variables.css` | Alle Farb-Variablen entfernt. Nur noch --tpl-font-heading, --tpl-font-text, --tpl-borders-color. |
| `css/mrh-custom.css` | :root-Block mit Farb-Ueberschreibungen entfernt (Zeile 9-18 alt). |
| `smarty/mrh_color_vars.php` | Als DEPRECATED markiert, `return;` am Anfang eingefuegt. War nie eingebunden. |

**Architektur-Entscheidung:**

Vorher: 3 konkurrierende :root-Systeme (variables.css, mrh-custom.css, general.css.php inline).
Nachher: 1 System – general.css.php liest colors.json und gibt ALLE Farb-Variablen als inline `<style id="mrh-color-vars">:root{}</style>` aus.

**Warum:**
- Inline-Style im `<head>` hat hoechste Prioritaet in der CSS-Kaskade
- general.css.php hat bereits Zugriff auf colors.json
- Konfigurator-Aenderungen wirken sofort ohne Datei-Deployment
- Kein Smarty-Include noetig (reines PHP)

## 2026-04-09 – Button-System-Konsolidierung: Bootstrap → mrh-btn-*

**Betroffene Dateien (20+):**

| Datei | Aenderung |
|-------|----------|
| `css/mrh-custom.css` | mrh-btn-primary, mrh-btn-express, mrh-btn-outline Klassen hinzugefuegt |
| `module/product_listing.html` | Grid+List: Buttons mit mrh-btn-details/wishlist/compare ergaenzt |
| `module/product_info.html` | btn-success→mrh-btn-primary, btn-info→mrh-btn-express |
| `module/product_info/product_info_v1.html` | btn-success→mrh-btn-primary, btn-info→mrh-btn-express |
| `module/product_info/non_seeds_info.html` | btn-success→mrh-btn-primary, btn-info→mrh-btn-express |
| `module/product_info/seedling.html` | btn-success→mrh-btn-primary, btn-info→mrh-btn-express |
| `module/product_info/aaa_produkt_info.html` | btn-info→mrh-btn-compare/mrh-btn-express |
| `module/product_info/usa_STrain-patch.html` | btn-primary/success→mrh-btn-primary |
| `module/seedfinder_product_cards.html` | Alle 4 Buttons auf mrh-btn-* |
| `module/seedfinder_filters_accordion.html` | btn-success/primary→mrh-btn-primary |
| `module/seedfinder_beginner_results.html` | btn-info→mrh-btn-primary |
| `module/seedfinder.html` | btn-outline-primary→mrh-btn-outline |
| `module/shopping_cart.html` | btn-outline-primary→mrh-btn-express |
| `module/cheaply_see.html` | btn-outline-primary→mrh-btn-outline |
| `module/product_reviews.html` | btn-outline-primary→mrh-btn-outline |
| `module/popup_reviews.html` | btn-outline-primary→mrh-btn-outline |
| `module/includes/searchbar.html` | btn-success→mrh-btn-primary |
| `module/includes/product_info_include.html` | btn-primary→mrh-btn-details |
| `module/includes/blog_post_listing.html` | btn-outline-primary→mrh-btn-outline |
| `boxes/box_slider_*.html` (5 Dateien) | btn-secondary→mrh-btn-wishlist, btn-primary→mrh-btn-details |
| `boxes/box_add_a_quickie.html` | btn-success→mrh-btn-primary |
| `boxes/box_giftcode.html` | btn-success→mrh-btn-primary |
| `boxes/box_newsrss.html` | btn-primary→mrh-btn-details |

**Ergebnis:** 0 Bootstrap-Farbklassen in kundenrelevanten HTML-Dateien. Alle Buttons werden ueber CSS Custom Properties gesteuert.

**Button-Klassen-Mapping:**

| mrh-btn-* Klasse | Funktion | CSS-Variablen |
|---|---|---|
| mrh-btn-primary | Warenkorb, Suche, Formulare | --tpl-btn-primary-bg/text/hover |
| mrh-btn-details | Details-Button im Listing | --tpl-btn-details-bg/text/hover |
| mrh-btn-wishlist | Merkzettel-Button | --tpl-btn-wishlist-bg/text/hover |
| mrh-btn-compare | Vergleich-Button | --tpl-btn-compare-bg/text/hover |
| mrh-btn-express | Express-Checkout | --tpl-btn-express-bg/text/hover |
| mrh-btn-outline | Links, Blog, Reviews | --tpl-btn-primary-bg (Outline-Stil) |


---

### 2026-04-09 – Popup-Modal v3: iframe → AJAX-Fetch

**Problem:** Das iframe-basierte Modal hatte z-index Konflikte mit template.css (Backdrop z-index: 50000 > Modal z-index: 1060). Zusätzlich fehlten im iframe Bootstrap CSS und mrh-custom.css, sodass der Content unstyled war.

**Lösung:** Kompletter Umbau von iframe auf AJAX-Fetch:
- `mrh-iframe-modal.js` → Popup-Modal v3 mit `fetch()` statt `<iframe>`
- Content wird per AJAX geladen und direkt ins Modal-Body eingefügt
- Content erbt automatisch alle CSS-Styles der Hauptseite (Bootstrap, mrh-custom.css)
- Titel wird aus der geladenen Seite extrahiert, erste `<h1>` wird entfernt (als Modal-Title angezeigt)
- Fallback: Bei Fetch-Fehler wird Link zum Öffnen in neuem Tab angeboten

**Geänderte Dateien:**

| Datei | Änderung |
|---|---|
| `javascript/extra/mrh-iframe-modal.js` | Komplett neu: AJAX-Fetch statt iframe, Modal-ID: `mrhPopupModal` |
| `css/mrh-custom.css` | Modal-CSS umgeschrieben: `#mrhPopupModal` statt `#mrhIframeModal`, Padding für AJAX-Content, Content-Styling (img, h2, h3, table), z-index: 50001 |

**CSS-Änderungen (mrh-custom.css):**
- `#mrhPopupModal.modal { z-index: 50001; }` – über template.css Backdrop
- `.modal-body { padding: 20px 24px; }` – Content hat jetzt Padding (kein iframe mehr)
- `.modal-body img { max-width: 100%; }` – Bilder responsive
- `.modal-body h2/h3` – Schriftgrößen für Modal-Kontext angepasst
- `a.iframe` Links: Unterstreichung beibehalten

## 2026-04-13 – Phase 3: MRH Product Attributes – Frontend Badge Integration

**Ziel:** Serverseitige Badges/Pictos aus dem MRH Product Attributes Modul in alle Produktboxen, Listings und Detailseiten integrieren. Ersetzt schrittweise die clientseitige JS-Badge-Erzeugung (icon_sex.html, mrh-listing-desc.js.php) durch PHP-generiertes HTML via `buildBadgeHTML()` und `buildMiniTable()`.

**Smarty-Variablen (bereitgestellt durch Autoincludes in mrh-product-short_table):**
- Listings: `$module_data.MRH_BADGES`, `$module_data.MRH_MINI_TABLE`, `$module_data.MRH_HAS_ATTRS`
- Produktdetail: `$mrh_badges`, `$mrh_mini_table`, `$mrh_has_attrs`

| Datei | Aenderung |
|-------|----------|
| `module/includes/product_listing_include.html` | MRH_BADGES und MRH_MINI_TABLE in Box- und Listenansicht eingefuegt (nach Produktname, vor Kurzbeschreibung) |
| `module/includes/product_box.html` | MRH_BADGES nach Produktname eingefuegt (Karussell-Boxen) |
| `module/product_info.html` | mrh_badges als Primaer-Badge (Fallback: PRODUCTS_PICTO_ICONS), mrh_mini_table als Detail-Tabelle |
| `module/product_info/seeds_info.html` | mrh_badges als Primaer (Fallback: icon_sex.html), mrh_mini_table eingefuegt |
| `module/product_info/non_seeds_info.html` | mrh_badges als Primaer (Fallback: PRODUCTS_PICTO_ICONS), mrh_mini_table eingefuegt |
| `module/product_info/usa_STrain-patch.html` | mrh_badges als Primaer (Fallback: icon_sex.html), mrh_mini_table eingefuegt |
| `module/product_info/seedling.html` | mrh_badges als Primaer (Fallback: icon_sex.html), mrh_mini_table eingefuegt |
| `css/mrh-custom.css` | Abschnitt 21 appended: Badge-Styles fuer .mrh-badge-auto, .mrh-badge-picto, .mrh-badge-cup, .mrh-cup-count, .mrh-badge-text, .mrh-detail-table (inkl. Responsive) |

**Hinweis:** Slider-Boxen (box_slider_bestsellers, box_slider_new etc.) verwenden `$box_content`/`$box_data` statt `$module_content` und werden weiterhin durch das bestehende JS-System (mrh-listing-desc.js.php) bedient. Eine Smarty-Plugin-Loesung fuer diese Boxen ist fuer eine spaetere Phase geplant.

## 2026-04-17 – Sektion 21c-2 + Sektion 27: Legacy-Badge Farben + Responsive Badge-Sizing

**Ziel:** Zwei fehlende CSS-Sektionen ergaenzen, die im lokalen Repo vorhanden aber noch nicht auf dem Live-Server deployed sind.

### Sektion 21c-2: Legacy-Badge Farbzuordnung (Zeilen 5711-5759)
Die Funktion `mrh_extract_legacy_badges()` erzeugt Klassen `mrh-badge-picto-{type}` fuer Badges aus der `short_description`. Diese Klassen hatten bisher keine eigenen Farbregeln und waren daher ohne Hintergrund/Border.

| Klasse | Farbe | Quelle |
|--------|-------|--------|
| `.mrh-badge-picto-cup` | Gold-Gradient (#fbbf24 → #f59e0b) | = `.mrh-badge-cup` |
| `.mrh-badge-picto-fem` | Pink (#fc5b96) | = `.mrh-badge-fem` |
| `.mrh-badge-picto-auto` | Gruen (#f0fdf4 / #15803d) | = `.mrh-badge-auto` |
| `.mrh-badge-picto-reg` | Blau (#2ea2f0) | = `.mrh-badge-reg` |
| `.mrh-badge-picto-photo` | Grau (#6c757d) | = `.mrh-badge-photo` |
| `.mrh-badge-picto-medical` | Rot (#dc2626) | Neu |
| `.mrh-badge-picto-legacy` | Hellgrau (#f1f5f9) | = `.mrh-badge-picto` |

### Sektion 27: Responsive Badge-Sizing v1.0.0 (Zeilen 6309-6627)
Badges passen sich an alle Display-Groessen an. Betrifft: `.mrh-type-badge`, `.mrh-badge-bar`, `.mrh-badge-svg`, `.mrh-badge-icon`, `.mrh-badge-label`, `.mrh-listing-icon`, `.picto.templatestyle`, `.mrh-cup-count`, `.mrh-badge-text`.

| Breakpoint | Media Query | Beschreibung |
|-----------|-------------|---------------|
| 27a: Desktop XL | `min-width: 1401px` | Badges etwas groesser (font-size: 0.88rem) |
| 27b: Desktop Standard | 992-1400px | Basis (keine Overrides) |
| 27c: Tablet | `max-width: 991px` | Leicht kompakter (font-size: 0.75rem) |
| 27d: Mobile | `max-width: 767px` | Kompakt (font-size: 0.7rem, border-radius: 8px) |
| 27e: Mobile XS | `max-width: 480px` | Minimal (font-size: 0.65rem, border-radius: 6px) |

| Datei | Aenderung |
|-------|----------|
| `css/mrh-custom.css` | Sektion 21c-2 (Legacy-Badge Farben) + Sektion 27 (Responsive Badge-Sizing) appended |


---

## 2026-04-17 – Badge-Fixes: Vergleichsseite + Listenansicht + Gruener Container

**Ziel:** Drei Badge-Probleme beheben: 1) Vergleichsseite zeigt keine Badges, 2) Listenansicht (product_row.html) hat keine Badge-Ausgabe, 3) Gruener Container um Badges im Listing fehlt.

### Fix 1: compare_card.html v2.1.0 – Badge-Zeile befuellt
- `{$module_data.MRH_BADGES}` direkt in `.compare-badge-row` eingebunden
- `compare-desc-box` wieder aktiviert (mit `d-none`) als versteckte Datenquelle fuer MrhBadgeInit-Fallback
- Wenn MRH_BADGES server-seitig verfuegbar: direkte Anzeige
- Wenn nicht: MrhBadgeInit extrahiert Badges aus der short_description

### Fix 2: mrh-badge-init.js.php v1.1.0 – Server-Badge-Erkennung
- Neue Pruefung: Wenn `.compare-badge-row` bereits server-seitig gerenderte Badges enthaelt (`.mrh-badge-bar`, `.mrh-type-badge`, `.picto.templatestyle`), wird die JS-Extraktion uebersprungen
- Verhindert doppelte Badge-Anzeige

### Fix 3: product_row.html v1.1.0 – Badge-Zeile in Listenansicht
- `{$module_data.MRH_BADGES}` zwischen Bewertungs-Sternen und Kurzbeschreibung eingefuegt
- Wrapper: `.mrh-listing-badges mb-1` (nutzt bestehende Listing-Badge-Styles)

### Fix 4: mrh-custom.css Sektion 21d v2 – Gruener Container wiederhergestellt
- `.mrh-listing-badges .picto.templatestyle` bekommt wieder den gruenen Hintergrund
- Vorher: `background: transparent; border: none; box-shadow: none;`
- Nachher: `background: var(--tpl-picto-bg); border: 1px solid var(--tpl-picto-border-color); border-radius: 10px;`
- Padding kompakter als auf der Detailseite (6px 10px statt 8px 16px)

| Datei | Aenderung |
|-------|----------|
| `module/includes/compare_card.html` | v2.1.0: MRH_BADGES + desc-box Fallback |
| `javascript/extra/mrh-badge-init.js.php` | v1.1.0: Server-Badge-Erkennung |
| `module/includes/product_row.html` | v1.1.0: Badge-Zeile hinzugefuegt |
| `css/mrh-custom.css` | Sektion 21d v2: Gruener Container wiederhergestellt |

## 2026-04-17 – Badge-Fixes: Vergleichsseite + Responsive

### Fix 5: mrh-badge-init.js.php v1.2.0 – Lose Icons in Badge-Wrapper
- Lose Icons (shortfongc, shortfongc0 = Autoflowering, Medical, Cup) werden jetzt
  in `.mrh-type-badge .mrh-badge-{type}` Wrapper gepackt statt nackt eingefuegt
- Neues Icon-zu-Typ-Mapping: fa-gauge-high→auto, fa-venus→fem, fa-trophy→cup, etc.
- Autoflowering-Fallback: Wenn aus Tabelle erzeugt, wird jetzt auch `.mrh-badge-auto` erstellt
- Ergebnis: Alle Icons bekommen korrekte Hintergrundfarben aus dem Konfigurator

### Fix 6: product_compare.css v5.7.1 – Gruener Container auf Badge-Row
- `.compare-badge-row` bekommt direkt den gruenen Container-Hintergrund
  (da `.picto.templatestyle` per JS nicht uebertragen wird)
- `background: var(--tpl-picto-bg, var(--tpl-badge-auto-bg, #f0fdf4))`
- `border: 1px solid var(--tpl-picto-border-color, rgba(34,197,94,0.25))`
- `border-radius: var(--tpl-picto-border-radius, 10px)`
- Leere Badge-Row wird per `:empty` ausgeblendet

| Datei | Aenderung |
|-------|----------|
| `javascript/extra/mrh-badge-init.js.php` | v1.2.0: Lose Icons in Badge-Wrapper |
| `css/product_compare.css` | v5.7.1: Gruener Container auf Badge-Row |

## 2026-04-17 – Badge-Init v1.3.0: Duplikat-Vermeidung

### Fix 7: mrh-badge-init.js.php v1.3.0 – Duplikat-Vermeidung
- Problem: Wenn `mrh-badge-bar` bereits ein `mrh-badge-auto` enthaelt UND
  ein loses `shortfongc` Icon existiert, wurde Autoflowering doppelt angezeigt
- Fix: Nach dem Klonen der `mrh-badge-bar` werden alle vorhandenen Badge-Typen
  in `existingTypes` registriert (fem, auto, reg, photo, cup, medical)
- Lose Icons werden nur noch als Badge gewrappt, wenn ihr Typ NICHT bereits existiert
- Ergebnis: Jeder Badge-Typ erscheint maximal einmal pro Produkt

| Datei | Aenderung |
|-------|----------|
| `javascript/extra/mrh-badge-init.js.php` | v1.3.0: Duplikat-Vermeidung |

## 2026-04-17 – product_compare.css v5.7.5: Badge-Row Sizing + Abstand

### Fix 8: product_compare.css v5.7.5 – Badge-Row Breite und Abstand
- `.compare-badge-row` von `display: flex` auf `display: inline-flex` geaendert
  → Container passt sich an die Breite der enthaltenen Badges an (nicht volle Kartenbreite)
- `margin-bottom: 0.5rem` hinzugefuegt fuer Abstand zum Bild-Akkordeon
- Leere Badge-Row (`:empty`): `margin-bottom: 0` um unnoetige Luecke zu vermeiden

| Datei | Aenderung |
|-------|----------|
| `css/product_compare.css` | v5.7.5: inline-flex + margin-bottom |

## 2026-04-17 – product_compare.css v5.7.6: Badge-Row align-self

### Fix 9: product_compare.css v5.7.6 – Badge-Row Ausrichtung
- `align-self: flex-start` auf `.compare-badge-row` hinzugefuegt
  → Badge-Row nimmt nur die noetige Breite ein (nicht volle Kartenbreite)
- Cache-Buster in `product_compare.html` von `v=5.7.4` auf `v=5.7.6` aktualisiert

| Datei | Aenderung |
|-------|----------|
| `css/product_compare.css` | v5.7.6: align-self: flex-start |
| `module/product_compare.html` | Cache-Buster v=5.7.6 |

## 2026-04-17 – Fix 10: Kategoriebild lazyload → natives loading="lazy"

### Problem
Kategoriebilder auf Listing-Seiten wurden nicht angezeigt, weil das Template das
lazysizes.js-Pattern (`src="data:,"` + `data-src="..."` + Klasse `lazyload`) nutzte,
aber lazysizes.js nicht geladen war.

### Loesung
Alle drei Listing-Templates auf natives Browser-Lazyloading umgestellt:
- `class="lazyload"` → `class="img-fluid lazyloaded"`
- `src="data:,"` → `src="{$CATEGORIES_IMAGE}"`
- `data-src="..."` entfernt
- `loading="lazy"` Attribut hinzugefuegt
- `<noscript>` Fallbacks entfernt (nicht mehr noetig)

| Datei | Aenderung |
|-------|----------|
| `module/product_listing/product_listing_v1.html` | lazyload → loading="lazy" |
| `module/product_listing/us_gentics_v1.html` | lazyload → loading="lazy" |
| `module/product_listing/promotion_product_listing_v1 - Kopie.html` | lazyload → loading="lazy" |

## 2026-04-17 – Top-Produkte: Animierter Bokeh-Hintergrund (Sektion 29)

**Aenderung:** Das alte Boutique-Schaufenster-Bild (`bg_top-produkte.jpg`) wird per CSS ueberschrieben durch einen animierten Bokeh-Hintergrund:
- Dunkler Gradient (#111115 → #19191e → #1a1f16) als Basis
- Zwei Schichten animierter Bokeh-Lichtpunkte in Gold (rgba(195,167,88)) und Gruen (rgba(74,140,42))
- Sanfte Drift-Animation (18s/22s Zyklen, ease-in-out, alternate)
- Dezente Gold-Trennlinie ueber dem Heading
- Heading mit Gold-Glow text-shadow
- `prefers-reduced-motion` Support
- Mobile: Reduzierte Bokeh-Anzahl fuer Performance

| Datei | Repo | Aenderung |
|-------|------|----------|
| `css/mrh-custom.css` | Template-MRH-2026 | Sektion 29: Zeile 6534-6670 |

### Fix 12 – Offcanvas z-index Konflikt (Sektion 30)
- **Problem:** `.offcanvas-backdrop { z-index: 50000 !important }` in Sektion 22 (Filter) betraf ALLE Offcanvas-Panels. Konto, Einstellungen, Warenkorb (z-index: 1045) lagen unter dem Backdrop → unbedienbar auf Mobile.
- **Lösung:** Generischen `.offcanvas-backdrop` Override entfernt. Stattdessen nur Filter-Offcanvas per `#mrhFilterOffcanvas.offcanvas.show ~ .offcanvas-backdrop.show` gezielt hochgesetzt.
- **Dateien:** `css/mrh-custom.css` (Sektion 22 bereinigt + Sektion 30 neu)
- **Commit:** $(date +%Y-%m-%d)

### Feature: Content-Page Hintergrundfarbe im Konfigurator (Sektion 31)
- **Neuer Key:** `tpl-bg-contentpage` – Hintergrundfarbe für `.contentpage-content` (Impressum, AGB, Datenschutz etc.)
- **Konfigurator:** Im Allgemein-Tab unter "Hintergrundfarben" als "Content-Seiten Hintergrund" sichtbar
- **Dateien:**
  - `source/boxes/templateconfig.php` – validColorKeys erweitert
  - `admin/includes/mrh_configurator_panel.php` – Farbfeld im Allgemein-Tab
  - `css/general.css.php` – Default + mrh-Alias
  - `config/default_colors.json` – Default-Wert (#fff)
  - `css/mrh-custom.css` – Sektion 31: `.contentpage-content` Styling
- **Commit:** $(date +%Y-%m-%d)

### Feature: Content-Tab im Konfigurator + BS5 FAQ-Snippet (Tab 14)
- **Neuer Tab:** "Content" im TPL-Konfigurator – zeigt HTML-Snippets aus `config/content-snippets/`
- **Erstes Snippet:** `faq-samen-shop.html` – BS5-Accordion FAQ für /samen-shop/
- **Änderungen am BS4→BS5 Accordion:**
  - `data-toggle` → `data-bs-toggle`, `data-target` → `data-bs-target`, `data-parent` → `data-bs-parent`
  - `card` + `card-header` + `card-body` → flaches `.faq-card` + `.faq-button` + `.faq-body`
  - `mr-2` → `me-2` (BS5 Margin-Utilities)
  - Alle `data-faw-*` Attribute und inline `style="font-size:16px"` entfernt
  - Schema.org FAQPage Markup beibehalten
  - Nutzt bestehende FAQ v3 CSS-Klassen (Sektion 4522 in mrh-custom.css)
- **Dateien:**
  - `admin/includes/mrh_configurator_panel.php` – Tab 14 "Content" + Tab-Navigation
  - `config/content-snippets/faq-samen-shop.html` – BS5 FAQ HTML
- **Funktionsweise:** Jede `.html`-Datei in `config/content-snippets/` erscheint automatisch im Content-Tab mit Vorschau, Code-Anzeige und Kopier-Button
- **Commit:** $(date +%Y-%m-%d)

### Fix 14 – BS4 Accordion Compatibility Layer (Sektion 32)
- **Datei:** `tpl_mrh_2026/css/mrh-custom.css`
- **Problem:** Content-Seiten (z.B. /samen-shop/) nutzen altes BS4-Accordion-HTML (card, card-header, data-toggle). Im BS5-Template werden diese nicht korrekt gestylt.
- **Lösung:** CSS-Compatibility-Layer mappt BS4-Klassen (.card.faq-card, .card-header.faq-header, .card-body.faq-body) auf MRH-2026 FAQ-Design-Variablen (--tpl-faq-*). Inline font-size und color Overrides werden neutralisiert. BS4 .mr-* Utilities als Fallback definiert.
- **Commit:** $(date +%Y-%m-%d)

### Fix 15: BS4 Accordion Fix – mrh_core.js eingebunden + CSS-Selektoren erweitert
- **Datum:** 2026-04-20
- **Dateien:** `javascript/mrh_core.js` (NEU), `javascript/general_bottom.js.php`, `css/mrh-custom.css`
- **Problem:** mrh_core.js war nicht im Template eingebunden → BS4→BS5 Bridge lief nicht → Accordions ohne Funktion. CSS-Selektoren griffen nur auf `.contentpage-content` und `.content_body`, nicht auf `.faq-section`.
- **Lösung:**
  1. mrh_core.js ins Template-Repo kopiert und in general_bottom.js.php registriert
  2. Alle CSS-Selektoren in Sektion 32 um `.faq-section` erweitert

### Fix 15b: BS4 Bridge – Bootstrap manuell initialisieren nach Konvertierung
- **Datum:** 2026-04-20
- **Dateien:** `javascript/mrh_core.js`
- **Problem:** Bridge konvertierte Attribute korrekt, aber Bootstrap 5 hatte sich bereits initialisiert → konvertierte Elemente wurden nicht erkannt → Accordion ohne Funktion
- **Lösung:**
  1. Nach Attribut-Konvertierung: `bootstrap.Collapse` manuell auf konvertierte Elemente initialisieren
  2. Click-Handler manuell binden (toggle + collapsed-Klasse + aria-expanded)
  3. Init-Logik: Sofort ausführen wenn DOM ready, statt auf DOMContentLoaded zu warten
  4. Unterstützt auch Modal und Tab Konvertierung

### Fix 15c – Vanilla JS Accordion (kein Bootstrap nötig)
- **Datum:** 2026-04-20
- **Dateien:** `tpl_mrh_2026/javascript/mrh_core.js`
- **Problem:** BS4→BS5 Bridge funktionierte nicht, da Bootstrap 5 Collapse die nachträglich konvertierten Attribute nicht erkannte
- **Lösung:** Komplett neuer Vanilla JS Collapse-Handler (`MRH.Collapse`), der direkt auf `data-toggle="collapse"` reagiert – ohne jegliche Bootstrap-Abhängigkeit
- **Features:** Slide-Animation (350ms), Accordion-Verhalten (data-parent), collapsed-Klasse, aria-expanded, Event-Delegation
### Fix 16 – FAQ-Farben im Konfigurator (Defaults hinzugefügt)
- **Datum:** 2026-04-20
- **Dateien:** `config/default_colors.json`, `css/general.css.php`
- **Problem:** FAQ-Farbfelder im Konfigurator-Panel (Tab 13) waren zwar als UI-Felder vorhanden, aber die Default-Werte fehlten in `default_colors.json` und im `$defaults`-Array von `general.css.php`. Dadurch wurden die `--tpl-faq-*` CSS-Variablen nie gesetzt und die Farben konnten nicht live bearbeitet werden.
- **Lösung:**
  1. 30 FAQ-Keys mit passenden Defaults in `default_colors.json` hinzugefügt
  2. 30 FAQ-Keys mit identischen Defaults im `$defaults`-Array von `general.css.php` hinzugefügt
  3. Konfigurator-Panel (Tab 13) und `validColorKeys` in `templateconfig.php` waren bereits korrekt vorhanden
- **Betroffene Variablen:** `--tpl-faq-header-bg`, `--tpl-faq-header-gradient`, `--tpl-faq-header-text`, `--tpl-faq-header-radius`, `--tpl-faq-subheader-bg/gradient/text`, `--tpl-faq-card-bg/border/radius`, `--tpl-faq-accent`, `--tpl-faq-btn-bg/text/hover-bg/hover-text/active-bg/active-text/active-hover`, `--tpl-faq-icon-color/active`, `--tpl-faq-chevron-bg/color/active-bg/active-color`, `--tpl-faq-body-bg/border/text`, `--tpl-faq-grid-cols/gap/gap-md`
### Fix 15d – mrh_core.js v1.1.1: Defensive init() mit try-catch
- **Datum:** 2026-04-20
- **Datei:** `tpl_mrh_2026/javascript/mrh_core.js`
- **Problem:** `MRH.Utils.initLazyLoad is not a function` TypeError bricht die gesamte init()-Funktion ab. Dadurch wird `MRH.Collapse.init()` zwar aufgerufen (Zeile davor), aber `MRH.Events.emit('mrh:ready')` läuft nicht mehr. Bei bestimmten Cache-Konstellationen kann auch Collapse betroffen sein.
- **Lösung:** Jeder init()-Aufruf einzeln in try-catch gewrappt. Collapse läuft immer, LazyLoad wird nur aufgerufen wenn die Funktion existiert, Ready-Event hat eigenen try-catch. Version auf 1.1.1 erhöht.

### Fix 15e – mrh_core.js v1.2.0: jQuery BS4-Collapse-Konflikt behoben
- **Datum:** 2026-04-20
- **Datei:** `tpl_mrh_2026/javascript/mrh_core.js`
- **Problem:** jQuery 3.6.0 ist auf der Seite geladen und hat ein BS4-Collapse-Plugin (`$.fn.collapse`). Dieses registriert einen eigenen Click-Handler auf `[data-toggle="collapse"]` via `$(document).on('click.bs.collapse.data-api', ...)`. Wenn der User auf einen FAQ-Button klickt, feuern BEIDE Handler gleichzeitig: MRH.Collapse öffnet das Panel, jQuery-Collapse sieht es als offen und schließt es sofort wieder. Netto-Ergebnis: nichts passiert visuell.
- **Lösung:** In `init()` wird vor `MRH.Collapse.init()` der jQuery-Collapse-Handler entfernt: `jQuery(document).off('click.bs.collapse.data-api')`. Dadurch ist nur noch MRH.Collapse aktiv. Version auf 1.2.0 erhöht.
- **Verifizierung:** Auf der Live-Seite getestet – nach Entfernen des jQuery-Handlers öffnen sich alle FAQ-Accordions korrekt.

### Fix 17 – Offenes FAQ-Design (.product-faq) – Kein Accordion, kein JS
- **Datum:** 2026-04-20
- **Datei:** `tpl_mrh_2026/css/mrh-custom.css`
- **Aenderung:** Neues CSS fuer die offene FAQ-Struktur mit `dl/dt/dd` (`.product-faq > .faq-item > dl > dt.faq-question + dd.faq-answer`). Alle Fragen sind immer sichtbar, kein Accordion, kein JavaScript noetig. Farben ueber bestehende `--tpl-faq-*` Konfigurator-Variablen. Schema.org FAQPage Markup bleibt erhalten. Responsive, Print-optimiert.

### Fix 18 – Block-Level FA-Icons in Content-Sektionen zentrieren
- **Datum:** 2026-04-20
- **Datei:** `tpl_mrh_2026/css/mrh-custom.css`
- **Aenderung:** FA-Icons mit `d-block` Klasse (z.B. `.fa.d-block`, `.fa-solid.d-block`) in Content-Bereichen (.contentpage-content, .content_body, .hub-card, .text-center) werden jetzt mit `margin-left:auto; margin-right:auto` zentriert. Vorher waren sie links ausgerichtet, weil `text-align:center` nur Inline-Kinder zentriert, nicht Block-Elemente mit fester Breite.

### Fix 19 – Scroll-Erlebnis + Mega-Menü im Sticky-Header
- **Datum:** 2026-04-20
- **Dateien:** `tpl_mrh_2026/css/mrh-custom.css`, `tpl_mrh_2026/javascript/mrh_core.js`
- **Aenderungen:**
  - **Scroll-Fix:** Bootstrap `scroll-behavior:smooth` auf `:root` deaktiviert (verursachte ruckeliges Mausrad-Scrollen). Smooth Scroll jetzt nur noch fuer Anchor-Links via neues `MRH.SmoothAnchor`-Modul in mrh_core.js.
  - **Mega-Menü-Fix:** Im Sticky-Header bekommt `.mrh-mega-dropdown` jetzt `max-height: calc(100vh - 80px)` und `overflow-y:auto`, damit es nicht ueber den Viewport hinausragt. Neues `MRH.ScrollGuard`-Modul schliesst das Mega-Menü automatisch beim Wechsel zwischen sticky und nicht-sticky Zustand.
  - mrh_core.js Version 1.3.0

## 2026-04-20 – FAW Performance-Throttle v1.0
- **index.html**: Inline-Script VOR Fietz Widget eingefügt
  - Patcht addEventListener('scroll') um die 4 FAW scroll-Listener
    mit 2s idle-Debounce zu versehen (statt 200-400ms)
  - Reduziert getComputedStyle-Aufrufe von 10.000+/s auf ~5.000/2s-Burst
  - Restore nach 5s, damit nachfolgende scroll-Listener nicht betroffen
  - Ursache: FAW iteriert nach jedem Scroll-Stop über 4600+ Elemente
    und ruft getComputedStyle auf jedem auf (Kontrast, ARIA, Landmarks, Alt-Text)

## 2026-04-20 – FAW Performance-Throttle v1.2 (Hotfix)
- **index.html**: v1.0 hat den StickyHeader scroll-Listener abgefangen
  weil er als erster registriert wurde (mrh-core.js laedt VOR dem Widget)
- v1.2 erkennt FAW-Kontext ueber `FIETZ_ACCESSIBILITY_CONFIG` Existenz
  - Config wird am Anfang des Widget-Scripts definiert, BEVOR scroll-Listener
  - Alle Listener die VOR dem Widget registriert werden passieren ungehindert
  - StickyHeader funktioniert wieder korrekt
