# CSS-Audit und Optimierungsplan: tpl_mrh_2026

**Erstellt:** 03. April 2026  
**Template:** tpl_mrh_2026 (Mr. Hanf BS5 Template)  
**Gesamtvolumen:** 7,08 MB (80 Dateien im css-Verzeichnis)

---

## 1. Zusammenfassung

Das CSS-Verzeichnis des Templates enthält aktuell **80 Dateien** mit einem Gesamtvolumen von **7,08 MB**. Durch die nachfolgend beschriebenen Maßnahmen lässt sich das Volumen auf ca. **580 KB** reduzieren, was einer **Einsparung von 92 %** entspricht. Die Shop-interne Komprimierung (`COMPRESS_STYLESHEET`) bricht derzeit, weil die `combine_files()`-Funktion des Shops mit CSS Custom Properties (`var()`, `calc()`) und der Ladereihenfolge der Variablen-Dateien nicht korrekt umgehen kann.

---

## 2. Aktuelle Dateistruktur

### 2.1 Lademechanismus

Das Template lädt CSS über zwei PHP-Dateien, die jeweils ein Array von CSS-Dateien definieren und bei aktivierter Komprimierung (`COMPRESS_STYLESHEET = true`) zu einer einzigen `.min.css`-Datei zusammenfassen.

| Loader-Datei | Erzeugte Min-Datei | Enthaltene CSS-Dateien |
|---|---|---|
| `general.css.php` | `stylesheet.min.css` | bootstrap.min.css, cssbuttons.css, simple-line-icons.css, template.css, variables.css, mrh-fonts.css, mrh-custom.css, mrh-product-options.css |
| `general_bottom.css.php` | `tpl_plugins.min.css` | owl.carousel.css, cookieconsent.css, fontawesome-7.css, blog.css |

Zusätzlich gibt `general.css.php` **Inline-Styles** aus: dynamische `:root`-Variablen aus `config/colors.json` sowie `@font-face`-Deklarationen. Diese Inline-Blöcke können von der `combine_files()`-Funktion **nicht erfasst** werden.

### 2.2 Dateien nach Kategorie

| Kategorie | Dateien | Gesamtgröße | Status |
|---|---|---|---|
| Bootstrap 5 (alle Varianten) | 24 Dateien | 4,92 MB | Nur `bootstrap.min.css` (195 KB) wird benötigt |
| Source Maps (.map) | 16 Dateien | 4,19 MB | Nur für Entwicklung, nicht für Produktion |
| RTL-Varianten (.rtl.) | 10 Dateien | 1,54 MB | Nicht benötigt (kein RTL-Shop) |
| FontAwesome (alle Versionen) | 4 Dateien | 559 KB | FA7 in `tpl_plugins.min.css` reicht |
| Template-Kern | 5 Dateien | 272 KB | Behalten und optimieren |
| Seedfinder | 5 Dateien | 90 KB | Aufräumen (Duplikate) |
| Plugin-Libraries | 8 Dateien | 63 KB | Prüfen und reduzieren |
| Sonstige | 8 Dateien | 48 KB | Teilweise obsolet |

---

## 3. Identifizierte Probleme

### 3.1 Warum die Shop-Komprimierung das Layout verzieht

Die `combine_files()`-Funktion des modified-Shops wurde für einfache CSS-Dateien ohne moderne Features entwickelt. Drei Faktoren verursachen den Bruch:

**Problem A: CSS Custom Properties werden vom Minifier beschädigt.** Die Dateien `variables.css`, `template.css`, `mrh-custom.css` und `fontawesome-7.css` verwenden zusammen über **6.000 CSS Custom Properties** (`--variable`), **1.600+ `var()`-Aufrufe** und **200+ `calc()`-Ausdrücke**. Der Shop-interne Minifier (basierend auf einer älteren PHP-Bibliothek) kann diese modernen CSS-Features nicht korrekt verarbeiten und entfernt oder beschädigt Leerzeichen innerhalb von `calc()`-Ausdrücken oder bricht `var()`-Fallback-Werte ab.

**Problem B: Die Ladereihenfolge der CSS-Variablen geht verloren.** Die Datei `variables.css` definiert `:root`-Variablen, die von `template.css` und `mrh-custom.css` verwendet werden. Wenn `combine_files()` die Dateien zusammenfügt, kann die Reihenfolge abweichen, sodass Variablen zum Zeitpunkt ihrer Verwendung noch nicht definiert sind.

**Problem C: Inline-Styles werden nicht erfasst.** Die dynamischen `:root`-Variablen aus `config/colors.json` werden als `<style>`-Block direkt im HTML ausgegeben. Diese überschreiben die Fallback-Werte in `variables.css`. Wenn die Komprimierung die Dateireihenfolge ändert, stimmt die Kaskade nicht mehr.

### 3.2 Massive Duplikate

**Bootstrap 5:** Das Verzeichnis enthält die vollständige Bootstrap-Distribution mit allen Varianten (unminified, minified, RTL, Grid-only, Reboot-only, Utilities-only) plus allen Source Maps. Von den 24 Bootstrap-Dateien wird **nur eine einzige** (`bootstrap.min.css`, 195 KB) benötigt. Die restlichen 23 Dateien belegen **4,73 MB** ohne Nutzen.

**FontAwesome:** Vier verschiedene Versionen sind vorhanden: FA4 (`font-awesome.css`, 31 KB), FA6 (`fontawesome-6.css`, 298 KB), FA6-Custom (`fontawesome-6-custom.css`, 0,7 KB) und FA7 (`fontawesome-7.css`, 230 KB). Die `tpl_plugins.min.css` enthält bereits FA7. Wenn FA6 nicht mehr aktiv im Template referenziert wird, können FA4 und FA6 entfernt werden, was **329 KB** spart.

**Seedfinder:** Die Datei `seedfinder-combined.min.css` (65 KB) ist eine ältere gebündelte Version, die **nicht** die aktuellen Einzeldateien enthält (andere Selektoren). Die vier Einzeldateien (`seedfinder.css`, `seedfinder_accordion.css`, `seedfinder_modal.css`, `seedfinder_disabled_headers.css`) summieren sich auf 25 KB. Hier muss geklärt werden, welche Version aktiv geladen wird.

**Shariff:** Sowohl `shariff.complete.css` (39 KB) als auch `shariff.min.css` (13 KB) sind vorhanden. Nur die Min-Version wird benötigt.

### 3.3 Obsolete Dateien

| Datei | Größe | Grund für Entfernung |
|---|---|---|
| `ie8fix.css` | 73 B | Internet Explorer 8 wird seit 2014 nicht mehr unterstützt |
| `ie-fixes.css.php` | 22 KB | IE-Fixes nicht mehr nötig; wird nur bei IE-User-Agent geladen |
| `boxsizing.htc` | 14 KB | HTC-Polyfill für IE; kein moderner Browser benötigt dies |
| `fonts.css` | 0 B | Leere Datei ohne Inhalt |
| `general_bottom.css.php.bak` | 1,7 KB | Backup-Datei |
| `hover.css` | 98 KB | 98 KB für Hover-Effekte; prüfen ob `.hvr-*` Klassen tatsächlich verwendet werden |

---

## 4. Optimierungsplan

### Phase 1: Sofort umsetzbar (Dateien löschen)

Diese Dateien können ohne Risiko entfernt werden, da sie entweder Duplikate, Development-Only-Dateien oder obsolet sind.

**Zu löschende Dateien (sortiert nach Einsparung):**

| Datei(en) | Einsparung | Begründung |
|---|---|---|
| Alle `.map`-Dateien (16 Stück) | 4.190 KB | Source Maps nur für lokale Entwicklung |
| Alle `.rtl.*`-Dateien (10 Stück) | 1.540 KB | Kein RTL-Support nötig |
| `bootstrap.css` (unminified) | 249 KB | `bootstrap.min.css` wird geladen |
| `bootstrap-grid.css` + `.min.css` | 129 KB | Bereits in `bootstrap.min.css` enthalten |
| `bootstrap-utilities.css` + `.min.css` | 139 KB | Bereits in `bootstrap.min.css` enthalten |
| `bootstrap-reboot.css` + `.min.css` | 15 KB | Bereits in `bootstrap.min.css` enthalten |
| `font-awesome.css` (FA4) | 31 KB | Ersetzt durch FA7 in `tpl_plugins.min.css` |
| `ie8fix.css` | 0,1 KB | Obsolet |
| `ie-fixes.css.php` | 22 KB | Obsolet |
| `boxsizing.htc` | 14 KB | Obsolet |
| `fonts.css` | 0 KB | Leere Datei |
| `general_bottom.css.php.bak` | 1,7 KB | Backup |
| `shariff.complete.css` | 39 KB | `shariff.min.css` vorhanden |
| `fietz-accessibility-widget.css` | 3,6 KB | `.min.css` vorhanden |
| **Gesamt Phase 1** | **~6,37 MB** | |

**Lösch-Befehl:**
```bash
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/css/

# Source Maps
rm -f *.map

# RTL-Varianten
rm -f *rtl*

# Bootstrap-Duplikate (NUR bootstrap.min.css behalten!)
rm -f bootstrap.css bootstrap-grid.css bootstrap-grid.min.css \
     bootstrap-reboot.css bootstrap-reboot.min.css \
     bootstrap-utilities.css bootstrap-utilities.min.css

# Obsolete Dateien
rm -f font-awesome.css ie8fix.css ie-fixes.css.php boxsizing.htc \
     fonts.css general_bottom.css.php.bak shariff.complete.css \
     fietz-accessibility-widget.css
```

### Phase 2: Prüfen und entscheiden

Diese Dateien erfordern eine Prüfung, ob sie noch aktiv verwendet werden.

| Datei | Größe | Prüfung |
|---|---|---|
| `fontawesome-6.css` | 298 KB | Werden `fa-solid`, `fa-regular`, `fa-brands` (FA6-Syntax) im Template verwendet, oder nur FA7? |
| `fontawesome-6-custom.css` | 0,7 KB | Hängt von FA6 ab |
| `hover.css` | 98 KB | Suche nach `.hvr-` Klassen in allen HTML-Templates |
| `jquery.bxslider.css` | 4 KB | Wird bxSlider noch verwendet oder nur Owl Carousel? |
| `jquery.colorbox.css` | 4 KB | Bereits auskommentiert in `general_bottom.css.php` |
| `jquery.alertable.css` | 2 KB | Bereits auskommentiert in `general_bottom.css.php` |
| `jquery.alerts.css` | 1,5 KB | Wird jQuery Alerts noch verwendet? |
| `jquery.easyTabs.css` | 3 KB | Werden EasyTabs noch verwendet? |
| `slicknav.min.css` | 2,5 KB | Wird SlickNav noch verwendet? (Mega-Menu ist jetzt Vanilla JS) |
| `pe-icon-7-stroke.css` | 10 KB | Werden PE-7 Icons noch verwendet? |
| `simple-line-icons.css` | 13 KB | Wird in `general.css.php` geladen; prüfen ob noch nötig |
| `progressively.min.css` | 0,3 KB | Wird Progressive Image Loading verwendet? |
| `seedfinder-combined.min.css` | 65 KB | Alte Version; prüfen ob irgendwo referenziert |

**Prüf-Befehle:**
```bash
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/

# FA6-Klassen suchen
grep -rn "fa-solid\|fa-regular\|fa-brands\|fa-light\|fa-thin" module/ boxes/ *.html 2>/dev/null | head -20

# hover.css Klassen suchen
grep -rn "hvr-" module/ boxes/ *.html 2>/dev/null | head -20

# bxSlider suchen
grep -rn "bxslider\|bx-wrapper" module/ boxes/ *.html javascript/ 2>/dev/null | head -10

# SlickNav suchen
grep -rn "slicknav" module/ boxes/ *.html javascript/ 2>/dev/null | head -10

# PE-7 Icons suchen
grep -rn "pe-7s-" module/ boxes/ *.html 2>/dev/null | head -10

# Simple Line Icons suchen
grep -rn "icon-" module/ boxes/ *.html 2>/dev/null | head -20
```

### Phase 3: Komprimierung reparieren

Um die Shop-interne Komprimierung (`COMPRESS_STYLESHEET`) wieder funktionsfähig zu machen, sind folgende Änderungen an `general.css.php` nötig:

**Schritt 1:** Die Datei `variables.css` muss **vor** allen anderen Template-CSS-Dateien geladen werden und sollte **nicht** in die Komprimierung einbezogen werden, da sie CSS Custom Properties definiert, die von den nachfolgenden Dateien verwendet werden.

**Schritt 2:** Die `combine_files()`-Funktion muss entweder durch eine moderne Alternative ersetzt werden (z.B. `MatthiasMullie\Minify` oder `scssphp`), oder die CSS-Dateien sollten **vorab manuell minifiziert** werden, sodass die Shop-Komprimierung nur noch die Zusammenführung übernimmt.

**Empfohlene Änderung an `general.css.php`:**
```php
// variables.css NICHT in die Komprimierung einbeziehen
echo '<link rel="stylesheet" href="'.DIR_WS_BASE.DIR_TMPL_CSS.'variables.css" />';

$css_array = array(
    DIR_TMPL_CSS . 'bootstrap.min.css',
    DIR_TMPL_CSS . 'template.min.css',      // vorab minifiziert
    DIR_TMPL_CSS . 'mrh-custom.min.css',     // vorab minifiziert
    DIR_TMPL_CSS . 'mrh-product-options.css',
);
```

**Schritt 3:** Erstelle vorab minifizierte Versionen der großen Template-Dateien mit einem modernen Minifier:
```bash
# Mit cssnano (Node.js) oder clean-css
npx clean-css-cli -o template.min.css template.css
npx clean-css-cli -o mrh-custom.min.css mrh-custom.css
```

### Phase 4: Optimale Zielstruktur

Nach vollständiger Optimierung sollte das CSS-Verzeichnis nur noch diese Dateien enthalten:

| Datei | Größe (ca.) | Zweck |
|---|---|---|
| `bootstrap.min.css` | 195 KB | Bootstrap 5 Framework |
| `variables.css` | 2 KB | CSS Custom Properties (Design-System) |
| `template.min.css` | ~90 KB | Haupt-Template-Styles (vorab minifiziert) |
| `mrh-fonts.css` | 2 KB | Font-Deklarationen |
| `mrh-custom.min.css` | ~30 KB | Custom Overrides (vorab minifiziert) |
| `mrh-product-options.css` | 6 KB | Produkt-Optionen |
| `mrh_product_2026.css` | 13 KB | Produkt-Seiten 2026 |
| `cookieconsent.css` | 20 KB | Cookie-Banner |
| `owl.carousel.css` | 5 KB | Carousel-Plugin |
| `tpl_plugins.min.css` | 229 KB | FA7 + Owl (vorab gebündelt) |
| `seedfinder.css` | 9 KB | Seedfinder Basis |
| `seedfinder_accordion.css` | 11 KB | Seedfinder Filter |
| `seedfinder_modal.css` | 4 KB | Seedfinder Modal |
| `seedfinder_disabled_headers.css` | 1 KB | Seedfinder Disabled States |
| `product_compare.css` | 11 KB | Produktvergleich |
| `shariff.min.css` | 13 KB | Social Sharing (nur Produktseiten) |
| `adminbar.css` | 3 KB | Admin-Leiste (nur für Admins) |
| `fietz-accessibility-widget.min.css` | 2 KB | Barrierefreiheit |
| `affiliate.css` | 10 KB | Affiliate-Seiten |
| `blog.css` | 0,2 KB | Blog-Styles |
| `general.css.php` | 7 KB | Loader (angepasst) |
| `general_bottom.css.php` | 2 KB | Bottom-Loader |
| **Gesamt** | **~665 KB** | **Reduktion: 92%** |

---

## 5. Zusammenfassung der Einsparungen

| Maßnahme | Einsparung |
|---|---|
| Source Maps entfernen | 4.190 KB |
| RTL-Varianten entfernen | 1.540 KB |
| Bootstrap-Duplikate entfernen | 532 KB |
| Obsolete Dateien entfernen (IE, HTC, Backups) | 39 KB |
| FA4 entfernen | 31 KB |
| Shariff/Accessibility Duplikate | 43 KB |
| **Phase 1 Gesamt** | **6.375 KB (6,2 MB)** |
| FA6 entfernen (nach Prüfung) | 299 KB |
| hover.css entfernen (nach Prüfung) | 98 KB |
| Weitere jQuery-Plugins (nach Prüfung) | ~25 KB |
| seedfinder-combined.min.css (nach Prüfung) | 65 KB |
| **Phase 2 Gesamt (geschätzt)** | **~487 KB** |
| Template/Custom CSS minifizieren | ~80 KB |
| **Phase 3 Gesamt** | **~80 KB** |
| **Gesamteinsparung** | **~6,9 MB (92%)** |

---

## 6. Prioritäten-Empfehlung

1. **Sofort:** Phase 1 durchführen (Dateien löschen) - kein Risiko, sofortige Wirkung
2. **Diese Woche:** Phase 2 Prüfungen durchführen und nicht verwendete Dateien entfernen
3. **Nächste Woche:** Phase 3 - Komprimierung reparieren durch vorab-minifizierte Dateien und angepasste `general.css.php`
4. **Fortlaufend:** Bei neuen CSS-Dateien immer nur minifizierte Versionen deployen

---

*Bericht erstellt von Manus AI, 03.04.2026*
