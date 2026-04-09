# MRH 2026 Template – Changelog

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
