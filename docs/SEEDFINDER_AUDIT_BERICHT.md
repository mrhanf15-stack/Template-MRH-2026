# Seedfinder Modul – Audit-Bericht gegen Bot-Anweisung CSS-Integration

**Datum:** 03.04.2026  
**Template:** tpl_mrh_2026 (Bootstrap 5)  
**Geprüft auf:** mr-hanf.at

---

## 1. Zusammenfassung

Der Seedfinder wurde teilweise korrekt ins neue Template integriert. Die CSS-Dateien verwenden bereits CSS Custom Properties mit Fallback-Ketten (`--sf-*` → `--mrh-*` → `--tpl-*` → `--bs-*`). Es gibt jedoch **kritische Probleme** die behoben werden müssen, bevor das Modul vollständig funktioniert.

| Bereich | Status | Priorität |
|---|---|---|
| PHP-Keys UPPERCASE | **GEFIXT** (heute) | Kritisch |
| Caching deaktiviert | **GEFIXT** (heute, temporär) | Kritisch |
| seedfinder-combined.min.css (veraltet) | **PROBLEM** | Hoch |
| Lang-Variablen (125 benötigt, ~15 vorhanden) | **PROBLEM** | Hoch |
| seedfinder_beginner_results.html lowercase Keys | **PROBLEM** | Hoch |
| seedfinder_product_cards.html Smarty 3 Syntax | **PROBLEM** | Hoch |
| Include-Pfade ohne CURRENT_TEMPLATE | **WARNUNG** | Mittel |
| b4.css Referenz im Template | **WARNUNG** | Mittel |
| Hardcoded Farben in CSS | **WARNUNG** | Niedrig |
| .bak Dateien aufräumen | **AUFRÄUMEN** | Niedrig |

---

## 2. Kritische Probleme (MÜSSEN gefixt werden)

### 2.1 seedfinder-combined.min.css ist VERALTET

**Problem:** Das Template lädt `seedfinder-combined.min.css` (65 KB) UND `seedfinder_accordion.css` (11 KB) separat. Die combined-Datei verwendet **alte CSS-Variablen-Namen** (`--primary-green`, `--accent-gold`, `--bg-light`), die individuellen Dateien verwenden **neue Namen** (`--sf-primary`, `--sf-accordion-bg`, `--sf-chip-bg`).

**Auswirkung:** Doppelte CSS-Definitionen, Konflikte, 65 KB unnötiger Ballast.

**Fix:** Die `seedfinder-combined.min.css` muss **gelöscht** oder durch eine neu generierte Version aus den aktuellen Einzeldateien ersetzt werden.

**Befehle:**
```bash
# Option A: Löschen und nur Einzeldateien laden
rm /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/css/seedfinder-combined.min.css
```

Dann in `seedfinder.html` Zeile 6 ändern:
```smarty
{* ALT: *}
<link rel="stylesheet" href="{$tpl_path}/css/seedfinder-combined.min.css">

{* NEU: Einzeldateien laden *}
<link rel="stylesheet" href="{$tpl_path}css/seedfinder.css">
<link rel="stylesheet" href="{$tpl_path}css/seedfinder_modal.css">
<link rel="stylesheet" href="{$tpl_path}css/seedfinder_disabled_headers.css">
```

**Hinweis:** `seedfinder_accordion.css` wird bereits separat in `seedfinder_filters_accordion.html` Zeile 232 geladen – das ist korrekt.

---

### 2.2 Lang-Variablen: 110 von 125 FEHLEN

**Problem:** Das Template verwendet 125 verschiedene `{#variable#}` Lang-Variablen. Die `lang_german.conf` definiert nur ca. 15 davon (Basis-Seedfinder-Texte). Die restlichen 110 Variablen (Cards, Wizard, Product Cards, Beginner Results) sind **nicht definiert**.

**Betroffene Bereiche:**

| Template | Fehlende Variablen | Beispiele |
|---|---|---|
| seedfinder-cards.html | ~30 | beginner_title, beginner_subtitle, pro_title, pro_badge, beginner_cta_button |
| beginner-wizard-modal | ~56 | wizard_title, step1_title, step1_opt1_title, badge_easy, badge_classic |
| seedfinder_product_cards.html | ~11 | cart_button_title, compare_button, details_button, special_badge |
| seedfinder_beginner_results.html | ~11 | beginner_results_title, no_products_title, total_products_found |

**Fix:** Alle 125 Variablen müssen in die `lang_german.conf` (und `lang_english.conf`) eingefügt werden. Die vollständige Liste der benötigten Variablen:

```ini
[seedfinder]
; === Basis (bereits vorhanden) ===
title = "Seedfinder - Finde deinen perfekten Samen aus 7500+ Sorten"
subtitle = "Bei mehr als 7.500 Cannabis-Sorten..."
; ... (existierende Variablen)

; === Cards (FEHLT) ===
beginner_badge = "Empfohlen"
beginner_title = "Anfänger Seedfinder"
beginner_subtitle = "Perfekt für Einsteiger - wir führen dich Schritt für Schritt"
beginner_cta_button = "Jetzt starten"
beginner_problem_title = "Die Herausforderung"
beginner_problem_text = "Bei über 7.500 Sorten den Überblick zu behalten ist schwer"
beginner_solution_title = "Unsere Lösung"
beginner_solution_text = "Unser Wizard stellt dir 4 einfache Fragen und findet die perfekten Sorten"
beginner_guarantee = "100% kostenlos und unverbindlich"
beginner_trust_headline = "Vertraut von über 50.000 Growern"
beginner_trust_quote = "Der Seedfinder hat mir geholfen, die perfekte Sorte zu finden!"
pro_badge = "Für Experten"
pro_title = "Profi Seedfinder"
pro_subtitle = "Volle Kontrolle über alle Filter und Attribute"
pro_cta_button = "Filter öffnen"
pro_info_text = "Wähle zuerst eine Kategorie, dann stehen dir alle Filter zur Verfügung"
pro_challenge_title = "Die Herausforderung"
pro_challenge_text = "Du weißt genau was du willst, aber brauchst den schnellsten Weg"
pro_solution_title = "Unsere Lösung"
pro_solution_text = "29 Filter mit Echtzeit-Aktualisierung für präzise Ergebnisse"
pro_trust_headline = "Professionelle Filterung"
pro_trust_quote = "Die detaillierten Filter sparen mir enorm viel Zeit!"
pro_benefit1_title = "Multi-Select Filter"
pro_benefit1_text = "Kombiniere mehrere Werte pro Filter"
pro_benefit2_title = "Echtzeit-Ergebnisse"
pro_benefit2_text = "Sofortige Aktualisierung bei jeder Filteränderung"
pro_benefit3_title = "29 Filteroptionen"
pro_benefit3_text = "THC, CBD, Ertrag, Blütezeit und mehr"
pro_benefit4_title = "Smart-Deaktivierung"
pro_benefit4_text = "Unmögliche Kombinationen werden automatisch ausgeblendet"

; === Wizard Steps (FEHLT) ===
wizard_title = "Anfänger Seedfinder"
wizard_subtitle = "Beantworte 4 einfache Fragen und finde deine perfekte Sorte"
wizard_close_button_aria = "Schließen"
wizard_back_button = "Zurück"
wizard_reset_button = "Neu starten"
wizard_progress_text = "Schritt"
wizard_what_is_difference = "Was ist der Unterschied?"
wizard_why_important = "Warum ist das wichtig?"
step1_title = "Anbauort"
step1_subtitle = "Wo möchtest du anbauen?"
step1_explanation = "Der Anbauort bestimmt welche Sorten für dich geeignet sind"
step1_opt1_title = "Indoor"
step1_opt1_benefit1 = "Kontrollierte Umgebung"
step1_opt1_benefit2 = "Ganzjährig möglich"
step1_opt2_title = "Outdoor"
step1_opt2_benefit1 = "Natürliches Sonnenlicht"
step1_opt2_benefit2 = "Größere Pflanzen möglich"
step2_title = "Erfahrungslevel"
step2_subtitle = "Wie erfahren bist du?"
step2_explanation = "Wir passen die Empfehlungen an dein Level an"
step2_opt1_title = "Anfänger"
step2_opt1_benefit1 = "Pflegeleichte Sorten"
step2_opt1_benefit2 = "Fehlerverzeihend"
step2_opt2_title = "Fortgeschritten"
step2_opt2_benefit1 = "Höherer Ertrag"
step2_opt2_benefit2 = "Mehr Vielfalt"
step3_title = "Gewünschte Wirkung"
step3_subtitle = "Was suchst du?"
step3_explanation = "Die Genetik bestimmt die Wirkung"
step3_opt1_title = "Entspannung"
step3_opt1_benefit1 = "Beruhigend"
step3_opt1_benefit2 = "Schmerzlindernd"
step3_opt1_benefit3 = "Schlaffördernd"
step3_opt2_title = "Energie"
step3_opt2_benefit1 = "Kreativitätsfördernd"
step3_opt2_benefit2 = "Motivierend"
step3_opt2_benefit3 = "Gesellig"
step3_opt3_title = "Ausgewogen"
step3_opt3_benefit1 = "Vielseitig"
step3_opt3_benefit2 = "Mild"
step3_opt3_benefit3 = "Alltagstauglich"
step3_subtitle = "Was suchst du?"
step4_title = "Blütezeit"
step4_subtitle = "Wie schnell soll es gehen?"
step4_explanation_title = "Blütezeit erklärt"
step4_explanation_text = "Die Blütezeit bestimmt wie lange du bis zur Ernte warten musst"
step4_opt1_title = "Schnell (6-8 Wochen)"
step4_opt1_desc = "Autoflowering und schnelle Photoperiodische"
step4_opt2_title = "Normal (8-12 Wochen)"
step4_opt2_desc = "Klassische Blütezeit für maximalen Ertrag"

; === Badges (FEHLT) ===
badge_easy = "Einfach"
badge_recommended = "Empfohlen"
badge_popular = "Beliebt"
badge_classic = "Klassisch"
badge_natural = "Natürlich"

; === Product Cards (FEHLT) ===
cart_button_title = "In den Warenkorb"
compare_button = "Vergleichen"
compare_button_title = "Zum Vergleich hinzufügen"
details_button = "Details"
from_price_prefix = "ab"
sold_out_badge = "Ausverkauft"
special_badge = "Angebot"
tax_info = "inkl. MwSt., zzgl. Versand"
wishlist_button_title = "Auf den Merkzettel"

; === Beginner Results (FEHLT) ===
beginner_results_title = "Deine Empfehlungen"
beginner_results_info_title = "Basierend auf deiner Auswahl"
beginner_results_info_text = "Wir haben die besten Sorten für dich gefiltert"
beginner_results_back_to_selection = "Zurück zur Auswahl"
beginner_results_adjust_filters = "Filter anpassen"
beginner_results_new_selection = "Neue Auswahl"
no_products_title = "Keine Produkte gefunden"
no_products_text = "Versuche andere Filtereinstellungen"
total_products_found = "Produkte gefunden"

; === Filter (FEHLT) ===
active_filters_title = "Aktive Filter"
filter_open_button = "Filter öffnen"
apply_filters = "Filter anwenden"
back_to_categories = "Zurück zu Kategorien"
manufacturer_all_products = "Alle Produkte"
manufacturer_products_count = "Produkte"

; === Benefits (bereits teilweise vorhanden, prüfen) ===
benefit4_title = "Riesige Auswahl"
benefit4_text = "Über 7.500 Sorten von 200+ Züchtern"
```

**Hinweis:** Die Texte oben sind Vorschläge basierend auf dem Kontext. Die endgültigen Texte sollten vom Shopbetreiber geprüft und angepasst werden. Gleiches gilt für die englische Version.

---

### 2.3 seedfinder_beginner_results.html: lowercase Keys

**Problem:** Dieses Template verwendet **lowercase** Keys für die FILTERS-Variable:
```smarty
{$filter.options_name}     ← lowercase!
{$filter.options_id}       ← lowercase!
{$value.values_id}         ← lowercase!
{$value.values_name}       ← lowercase!
{$value.count}             ← lowercase!
{$value.enabled}           ← lowercase!
{$value.selected}          ← lowercase!
```

Die PHP-Funktion `getFiltersForModal()` wurde heute auf **UPPERCASE** umgestellt. Damit crasht dieses Template!

**Fix:** Entweder:
- **Option A (empfohlen):** Template auf UPPERCASE umstellen (konsistent mit allen anderen Templates)
- **Option B:** PHP-Funktion beide Varianten liefern lassen

**Fix Option A – Template anpassen:**
```bash
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/module/
sed -i 's/\$filter\.options_name/\$filter.OPTIONS_NAME/g' seedfinder_beginner_results.html
sed -i 's/\$filter\.options_id/\$filter.OPTIONS_ID/g' seedfinder_beginner_results.html
sed -i 's/\$filter\.values/\$filter.VALUES/g' seedfinder_beginner_results.html
sed -i 's/\$value\.values_id/\$value.VALUES_ID/g' seedfinder_beginner_results.html
sed -i 's/\$value\.values_name/\$value.VALUES_NAME/g' seedfinder_beginner_results.html
sed -i 's/\$value\.count/\$value.COUNT/g' seedfinder_beginner_results.html
sed -i 's/\$value\.enabled/\$value.AVAILABLE/g' seedfinder_beginner_results.html
sed -i 's/\$value\.selected/\$value.ACTIVE/g' seedfinder_beginner_results.html
```

---

### 2.4 seedfinder_product_cards.html: Smarty 3 Syntax

**Problem:** Zeile 82 verwendet **Smarty 3 Syntax** die in Smarty 2 nicht funktioniert:
```smarty
{foreach $product.ATTRIBUTES as $attr}    ← Smarty 3!
```

**Fix:** Umschreiben auf Smarty 2 Syntax:
```smarty
{foreach from=$product.ATTRIBUTES item=attr}    ← Smarty 2!
```

**Zusätzlich:** Die `$attr` Sub-Keys sind lowercase (`$attr.value_name`, `$attr.price`, `$attr.stock`, `$attr.old_price`). Prüfen ob die PHP-Seite diese Keys auch in lowercase liefert.

---

## 3. Warnungen (sollten behoben werden)

### 3.1 Include-Pfade ohne CURRENT_TEMPLATE

**Problem:** Zwei Includes verwenden relative Pfade statt `CURRENT_TEMPLATE`:
```smarty
{include file="module/seedfinder_product_cards.html"}     ← seedfinder.html:215
{include file="module/seedfinder_product_cards.html"}     ← seedfinder_beginner_results.html:132
```

**Risiko:** Wenn ein anderes Template aktiv ist, wird die falsche Datei geladen.

**Fix:**
```smarty
{include file="`$smarty.const.CURRENT_TEMPLATE`/module/seedfinder_product_cards.html"}
```

---

### 3.2 b4.css Referenz

**Problem:** `seedfinder.html` Zeile 5 lädt `b4.css`:
```html
<link rel="stylesheet" href="{$tpl_path}css/b4.css">
```

**Frage:** Ist das eine Bootstrap 4 Kompatibilitäts-Datei? Wenn ja, sollte sie entfernt werden da das Template BS5 nutzt. Prüfen ob `b4.css` existiert und was sie enthält.

---

### 3.3 Caching ist deaktiviert

**Problem:** `$cache = false` wurde heute als Workaround gesetzt. Das muss langfristig wieder aktiviert werden, sobald alle Template-Probleme behoben sind.

---

## 4. CSS-Variablen-Integration (Status)

### 4.1 Positiv: Korrekte Fallback-Ketten

Die individuellen CSS-Dateien verwenden bereits korrekte Fallback-Ketten gemäß Bot-Anweisung:

```css
/* seedfinder.css */
:root {
    --sf-primary: var(--mrh-primary, var(--tpl-primary, #5db233));
    --sf-primary-dark: var(--mrh-primary-dark, var(--tpl-primary-dark, #4a8c2a));
    --sf-success: var(--bs-success, #28a745);
    /* ... */
}

/* seedfinder_accordion.css */
:root {
    --sf-accordion-active: var(--mrh-primary, var(--tpl-primary, #5db233));
    /* ... */
}
```

### 4.2 Anpassung an Template-Farben

Die Farben aus dem Anpassungsmenü (Screenshot):

| Variable | Wert | CSS-Variable |
|---|---|---|
| Primärfarbe | rgb(119, 204, 77) | `--tpl-primary` |
| Sekundärfarbe | rgb(93, 178, 51) | `--tpl-secondary` |
| Standard Schriftfarbe | rgb(51, 65, 85) | `--tpl-text` |
| Überschriften Schriftfarbe | rgb(51, 65, 85) | `--tpl-heading` |
| Menü Hintergrund | rgb(93, 178, 51) | `--tpl-nav-bg` |
| Topbar Hintergrund | rgb(74, 140, 42) | `--tpl-topbar-bg` |

**Status:** Die Seedfinder-CSS Fallback-Werte (`#5db233`) stimmen mit der Sekundärfarbe `rgb(93, 178, 51)` überein. Die Primärfarbe `rgb(119, 204, 77)` ist heller – wenn die `--tpl-primary` Variable korrekt gesetzt wird, übernimmt der Seedfinder automatisch die richtige Farbe.

### 4.3 Noch hardcoded Farben

In den individuellen CSS-Dateien gibt es noch einige hardcoded Farben die nicht über Variablen laufen:

| Datei | Hardcoded Farben | Empfehlung |
|---|---|---|
| seedfinder.css | 14 hex, 3 rgba | Bereits gut – nutzt var() mit Fallbacks |
| seedfinder_accordion.css | 13 hex, 5 rgba | Bereits gut – nutzt var() mit Fallbacks |
| seedfinder_modal.css | 5 hex, 1 rgba | Bereits gut – nutzt var() mit Fallbacks |
| seedfinder_disabled_headers.css | 3 hex, 0 rgba | **Keine var()** – sollte angepasst werden |

### 4.4 JS hardcoded Farben

`seedfinder-beginner-with-results.js` enthält hardcoded Farben:
```javascript
'#28a745'  // success green
'#dc3545'  // danger red
'#ffc107'  // warning yellow
```

**Empfehlung:** Durch `getComputedStyle(document.documentElement).getPropertyValue('--bs-success')` ersetzen.

---

## 5. Anpassungsmenü-Problem

**Problem:** Das Anpassungsmenü (Farben individualisieren) funktioniert nicht korrekt nach Änderungen.

**Wahrscheinliche Ursache:** Das Anpassungsmenü schreibt CSS-Variablen als Inline-Styles in den `<head>`. Wenn Seedfinder-CSS eigene `:root`-Variablen definiert, können diese die vom Anpassungsmenü gesetzten Werte überschreiben (CSS-Spezifität).

**Prüfpunkte:**
1. Prüfen ob `seedfinder-combined.min.css` eigene `:root`-Variablen setzt die mit dem Anpassungsmenü kollidieren
2. Prüfen ob die Ladereihenfolge stimmt (Anpassungsmenü-CSS muss NACH den Template-CSS geladen werden)
3. Prüfen ob `!important` in den Seedfinder-CSS Dateien Template-Variablen überschreibt

**Fix-Empfehlung:** Nach dem Löschen der `seedfinder-combined.min.css` (Punkt 2.1) sollte das Anpassungsmenü wieder korrekt funktionieren, da die alten `--primary-green` etc. Variablen dann nicht mehr kollidieren.

---

## 6. Checkliste – Offene Aufgaben

| Nr | Aufgabe | Priorität | Status |
|---|---|---|---|
| 1 | `seedfinder-combined.min.css` löschen, Einzeldateien laden | Hoch | OFFEN |
| 2 | 110 fehlende Lang-Variablen in conf einfügen (DE + EN) | Hoch | OFFEN |
| 3 | `seedfinder_beginner_results.html` auf UPPERCASE Keys umstellen | Hoch | OFFEN |
| 4 | `seedfinder_product_cards.html` Zeile 82: Smarty 3 → Smarty 2 Syntax | Hoch | OFFEN |
| 5 | Include-Pfade mit CURRENT_TEMPLATE ergänzen | Mittel | OFFEN |
| 6 | `b4.css` Referenz prüfen und ggf. entfernen | Mittel | OFFEN |
| 7 | `seedfinder_disabled_headers.css` auf var() umstellen | Niedrig | OFFEN |
| 8 | JS hardcoded Farben durch CSS-Variablen ersetzen | Niedrig | OFFEN |
| 9 | .bak Dateien aufräumen | Niedrig | OFFEN |
| 10 | Caching wieder aktivieren (nach allen Fixes) | Niedrig | OFFEN |
| 11 | Anpassungsmenü testen nach Löschen von combined.min.css | Hoch | OFFEN |

---

## 7. Bereits erledigte Fixes (heute)

| Fix | Details |
|---|---|
| PHP UPPERCASE Keys | `getFiltersForModal()` in `seedfinder_filters_modal.php` liefert jetzt UPPERCASE Keys |
| Caching deaktiviert | `$cache = false` in `seedfinder.php` als temporärer Workaround |
| Lang-Dateien erstellt | `lang_german.conf` und `lang_english.conf` mit [seedfinder] Section erstellt |
| `.custom` Datei | `lang_german.custom` erstellt (leer, verhindert Smarty-Fehler) |
