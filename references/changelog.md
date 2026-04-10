# MRH 2026 Template – Changelog

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
