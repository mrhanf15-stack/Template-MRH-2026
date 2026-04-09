# MRH 2026 Template – Changelog

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
