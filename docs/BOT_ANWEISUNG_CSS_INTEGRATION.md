# Bot-Anweisung: CSS-Integration Module in tpl_mrh_2026

**Gültig ab:** 03. April 2026  
**Template:** tpl_mrh_2026 (Mr. Hanf BS5 Template, modified-Shop)  
**Zweck:** Saubere Integration aller Module mit korrekter CSS-Zuordnung

---

## 1. CSS-Architektur des Templates

Das Template verwendet ein **zweistufiges Ladesystem** über PHP-Dateien, die CSS-Arrays definieren und optional komprimieren.

### 1.1 Lade-Reihenfolge (HEAD)

```
┌─────────────────────────────────────────────────────────┐
│  general.css.php  →  stylesheet.min.css (bei Komprim.)  │
│  ────────────────────────────────────────────────────── │
│  1. bootstrap.min.css        (Framework)                │
│  2. cssbuttons.css           (Button-Styles)            │
│  3. simple-line-icons.css    (Icon-Font)                │
│  4. template.css             (Haupt-Layout)             │
│  5. variables.css            (CSS Custom Properties)    │
│  6. mrh-fonts.css            (Schriftarten)             │
│  7. mrh-custom.css           (Custom Overrides)         │
│  8. mrh-product-options.css  (Produktoptionen)          │
│  ────────────────────────────────────────────────────── │
│  + Inline <style>: :root Variablen aus config/colors    │
│  + Inline <style>: @font-face Deklarationen             │
│  + Bedingt: shariff.min.css (nur Produktseiten)         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  general_bottom.css.php → tpl_plugins.min.css           │
│  ────────────────────────────────────────────────────── │
│  1. owl.carousel.css         (Carousel-Plugin)          │
│  2. cookieconsent.css        (Cookie-Banner)             │
│  3. fontawesome-7.css        (Icons FA7)                │
│  4. blog.css                 (Blog-Styles)              │
└─────────────────────────────────────────────────────────┘
```

### 1.2 Modul-spezifische CSS (separat geladen)

Diese Dateien werden **nicht** über die PHP-Loader geladen, sondern direkt in den jeweiligen Smarty-Templates per `<link>` oder `{literal}<style>{/literal}` eingebunden.

| CSS-Datei | Geladen von | Zweck |
|---|---|---|
| `seedfinder.css` | `seedfinder.html` | Seedfinder Basis-Layout |
| `seedfinder_accordion.css` | `seedfinder_filters_accordion.html` | Filter-Accordion |
| `seedfinder_modal.css` | `seedfinder_beginner-wizard-modal-with-results.html` | Beginner-Wizard Modal |
| `seedfinder_disabled_headers.css` | `seedfinder.html` | Disabled Filter-States |
| `product_compare.css` | `product_compare.html` | Produktvergleich |
| `affiliate.css` | Affiliate-Seiten | Affiliate-Styles |
| `adminbar.css` | `index.html` (nur Admin) | Admin-Leiste |
| `fietz-accessibility-widget.min.css` | `index.html` | Barrierefreiheit-Widget |

---

## 2. Regeln fuer neue Modul-CSS

### 2.1 Wohin gehoert die CSS?

Entscheide anhand dieser Logik:

```
Wird die CSS auf JEDER Seite gebraucht?
  ├── JA → In general.css.php ODER general_bottom.css.php eintragen
  │        (je nachdem ob HEAD oder BOTTOM besser passt)
  │
  └── NEIN → Wird sie nur auf bestimmten Seiten gebraucht?
              ├── JA → Als separate Datei im css/ Verzeichnis anlegen
              │        und im jeweiligen Smarty-Template per <link> einbinden
              │
              └── Sehr wenig CSS (< 50 Zeilen)?
                  └── Inline im Smarty-Template mit {literal}<style>...{/literal}
```

### 2.2 Namenskonvention

Neue CSS-Dateien **muessen** diesem Schema folgen:

| Typ | Muster | Beispiel |
|---|---|---|
| Modul-CSS | `modulname.css` | `seedfinder.css`, `product_compare.css` |
| Modul-Komponente | `modulname_komponente.css` | `seedfinder_accordion.css` |
| MRH-spezifisch | `mrh-zweck.css` | `mrh-custom.css`, `mrh-fonts.css` |
| Plugin/Library | `pluginname.css` oder `pluginname.min.css` | `owl.carousel.css` |

**Verboten:**
- Keine Versionsnummern im Dateinamen (nicht `seedfinder_v2.css`)
- Keine Duplikate mit `.min.css` UND unkomprimierter Version gleichzeitig aktiv
- Keine `.bak`-Dateien im Produktiv-Verzeichnis

### 2.3 CSS Custom Properties (Variablen)

Alle Template-weiten Variablen sind in `variables.css` definiert. Module **muessen** diese Variablen verwenden statt eigene Farbwerte hardzucoden.

**Verfuegbare Variablen:**

```css
/* Hauptfarben */
var(--tpl-main-color)          /* Gruen: #4a8c2a */
var(--tpl-main-color-2)        /* Dunkel: #1e1e1e */
var(--tpl-secondary-color)     /* Dunkelgruen: #3a7020 */

/* Hintergruende */
var(--tpl-bg-color)            /* Weiss: #ffffff */
var(--tpl-bg-color-2)          /* Hellgruen: #f0fdf4 */
var(--tpl-bg-productbox)       /* Produktbox-BG: #ffffff */
var(--tpl-bg-footer)           /* Footer-BG: #0f172a */

/* Texte */
var(--tpl-text-standard)       /* Standard-Text: #0f172a */
var(--tpl-text-headings)       /* Ueberschriften: #0f172a */
var(--tpl-text-button)         /* Button-Text: #ffffff */
var(--tpl-text-footer)         /* Footer-Text: #94a3b8 */
var(--tpl-text-footer-headings)/* Footer-Headings: #ffffff */

/* Rahmen */
var(--tpl-borders-color)       /* Rahmenfarbe: #e2e8f0 */

/* Schriften */
var(--tpl-font-heading)        /* Plus Jakarta Sans */
var(--tpl-font-text)           /* Inter */

/* Navigation */
var(--mrh-menu-bg)             /* Menue-BG: #16a34a */
var(--mrh-menu-text)           /* Menue-Text: #ffffff */
var(--mrh-menu-hover)          /* Menue-Hover: #38701e */

/* Sonstige */
var(--mrh-green-light)         /* Hellgruen: #f0fdf4 */
var(--mrh-green-accent)        /* Akzentgruen: #22c55e */
var(--mrh-badge-bg)            /* Badge-BG */
var(--mrh-badge-text)          /* Badge-Text */
var(--mrh-header-shadow)       /* Header-Schatten */
```

**Regel:** Wenn ein Modul eine Farbe braucht die in `variables.css` existiert, MUSS `var(--tpl-...)` oder `var(--mrh-...)` verwendet werden. Nur wenn keine passende Variable existiert, darf ein direkter Farbwert verwendet werden - dann aber als neue Variable in `variables.css` hinzufuegen.

---

## 3. Smarty-Template Variablen-Konvention

### 3.1 UPPERCASE vs. lowercase Problem

> **KRITISCH:** Die Smarty-Templates erwarten Array-Keys in **UPPERCASE** (`$filter.OPTIONS_ID`, `$product.PRODUCTS_NAME`). PHP-Funktionen die Daten an Smarty uebergeben, muessen die Keys in UPPERCASE liefern.

**Bekanntes Problem (gefixt):** Die Funktion `getFiltersForModal()` in `seedfinder_filters_modal.php` lieferte lowercase Keys (`options_id`, `values_name`), aber das Template erwartete UPPERCASE (`OPTIONS_ID`, `VALUES_NAME`). Dies fuehrte zu einem Smarty-Crash beim `{foreach}`.

**Regel fuer alle neuen Module:**

```php
// RICHTIG - UPPERCASE Keys fuer Smarty
$smarty->assign('PRODUCTS', array(
    array(
        'PRODUCTS_ID'    => $row['products_id'],
        'PRODUCTS_NAME'  => $row['products_name'],
        'PRODUCTS_PRICE' => $row['products_price'],
    )
));

// FALSCH - lowercase Keys crashen Smarty bei foreach
$smarty->assign('PRODUCTS', array(
    array(
        'products_id'    => $row['products_id'],
        'products_name'  => $row['products_name'],
    )
));
```

### 3.2 Smarty Config-Variablen (Lang-Dateien)

Sprachvariablen werden ueber `{config_load}` geladen und mit `{#variablenname#}` referenziert.

**Dateien und Ladereihenfolge:**

```
1. {config_load file="$language/lang_$language.conf" section="modulname"}
   → templates/tpl_mrh_2026/lang/german/lang_german.conf [seedfinder]
   
2. {config_load file="lang_`$language`.custom"}
   → templates/tpl_mrh_2026/lang/german/lang_german.custom
   
3. {config_load file="lang_`$language`.section" section="modulname"}
   → templates/tpl_mrh_2026/lang/german/lang_german.section [seedfinder]
```

**Regel:** Jedes Modul das Texte anzeigt, MUSS seine Variablen in der `lang_german.conf` unter einem eigenen `[section]`-Block definieren. Die `.custom`-Datei muss mindestens als leere Datei existieren, sonst crasht Smarty.

**Beispiel fuer ein neues Modul:**

```ini
; In lang_german.conf hinzufuegen:
[produktvergleich]
compare_title = "Produktvergleich"
compare_add = "Zum Vergleich hinzufuegen"
compare_remove = "Aus Vergleich entfernen"
compare_empty = "Noch keine Produkte zum Vergleichen ausgewaehlt"
compare_max = "Maximal 4 Produkte vergleichbar"
```

---

## 3a. Anpassungsmodul (Farb-Konfigurator)

### 3a.1 Funktionsweise

Das Template verfuegt ueber ein **Admin-Anpassungsmodul** (Farb-Konfigurator), das die Farben des gesamten Shops steuert. Die Farben werden in `config/colors.json` gespeichert und ueber `smarty/mrh_color_vars.php` als CSS Custom Properties im `<head>` ausgegeben.

**Dateien:**

| Datei | Zweck |
|---|---|
| `config/colors.json` | Gespeicherte Farben (wird vom Admin ueber das Formular beschrieben) |
| `config/default_colors.json` | Standard-Farben (Fallback bei Reset) |
| `source/boxes/templateconfig.php` | PHP-Handler: Liest/Schreibt `colors.json`, rendert das Admin-Formular |
| `smarty/mrh_color_vars.php` | Liest `colors.json` → gibt `<style id="mrh-color-vars">` mit `:root { --tpl-* }` aus |
| `css/general.css.php` | Liest ebenfalls `colors.json` fuer die CSS-Generierung |

### 3a.2 Verfuegbare Farb-Variablen aus dem Konfigurator

Diese Variablen werden vom Konfigurator gesetzt und stehen als CSS Custom Properties zur Verfuegung:

| JSON-Key | CSS-Variable | Alias-Variable | Beschreibung |
|---|---|---|---|
| `tpl-main-color` | `--tpl-main-color` | `--mrh-primary` | Primaerfarbe |
| `tpl-main-color-2` | `--tpl-main-color-2` | `--mrh-primary-dark` | Sekundaerfarbe |
| `tpl-secondary-color` | `--tpl-secondary-color` | `--mrh-primary-light` | Dritte Farbe |
| `tpl-bg-color` | `--tpl-bg-color` | – | Hintergrundfarbe 1 |
| `tpl-bg-color-2` | `--tpl-bg-color-2` | – | Hintergrundfarbe 2 |
| `tpl-bg-productbox` | `--tpl-bg-productbox` | – | Produktboxen Hintergrund |
| `tpl-bg-footer` | `--tpl-bg-footer` | `--mrh-bg-footer` | Footer Hintergrund |
| `tpl-text-standard` | `--tpl-text-standard` | – | Standard Schriftfarbe |
| `tpl-text-headings` | `--tpl-text-headings` | – | Ueberschriften Schriftfarbe |
| `tpl-text-button` | `--tpl-text-button` | – | Schriftfarbe in Buttons & Badges |
| `tpl-text-footer` | `--tpl-text-footer` | `--mrh-text-footer` | Schriftfarbe Footer |
| `tpl-text-footer-headings` | `--tpl-text-footer-headings` | – | Ueberschriften im Footer |
| `tpl-menu-bg` | `--tpl-menu-bg` | `--mrh-menu-bg` | Menue Hintergrund |
| `tpl-menu-hover` | `--tpl-menu-hover` | `--mrh-menu-hover` | Menue Hover Hintergrund |
| `tpl-menu-text` | `--tpl-menu-text` | `--mrh-menu-text` | Menue Textfarbe |
| `tpl-menu-active` | `--tpl-menu-active` | `--mrh-menu-active` | Menue Aktiv/Hover Textfarbe |
| `tpl-topbar-bg` | `--tpl-topbar-bg` | `--mrh-topbar-bg` | Topbar Hintergrund |
| `tpl-topbar-text` | `--tpl-topbar-text` | `--mrh-topbar-text` | Topbar Textfarbe |
| `tpl-sticky-bg` | `--tpl-sticky-bg` | `--mrh-sticky-bg` | Sticky Header Hintergrund |
| `tpl-sticky-text` | `--tpl-sticky-text` | `--mrh-sticky-text` | Sticky Header Textfarbe |

### 3a.3 KRITISCHE REGEL: Farben aus dem Konfigurator verwenden

> **PFLICHT:** Alle Module MUESSEN die CSS-Variablen aus dem Farb-Konfigurator verwenden. Hardcoded Farbwerte sind **NUR als Fallback** in `var()` erlaubt.

**Richtig:**
```css
.sf-card-header {
    background: var(--tpl-main-color, #5db233);
    color: var(--tpl-text-button, #ffffff);
}
```

**Falsch:**
```css
.sf-card-header {
    background: #5db233;  /* VERBOTEN: hardcoded ohne var() */
    color: white;
}
```

**Modul-eigene Variablen** duerfen definiert werden, muessen aber auf die Konfigurator-Variablen zurueckfallen:

```css
:root {
    --sf-primary: var(--mrh-primary, var(--tpl-main-color, #5db233));
}
```

### 3a.4 Konfigurator-Kompatibilitaet bei neuen Modulen

Bei der Integration neuer Module sicherstellen:

- [ ] Keine CSS-Regel ueberschreibt die `:root`-Variablen des Konfigurators
- [ ] Keine `!important` auf Farben die vom Konfigurator gesteuert werden
- [ ] Modul-CSS wird NACH `mrh_color_vars.php` geladen (nicht davor)
- [ ] Testen: Farbe im Konfigurator aendern → Modul uebernimmt die neue Farbe

---

## 3b. Sprachdateien und Texte – Keine Hardcoded Texte

### 3b.1 KRITISCHE REGEL: Alle Texte in Sprachdateien

> **PFLICHT:** Es duerfen **KEINE Texte direkt in HTML/Smarty-Templates hardcodiert** werden. Alle sichtbaren Texte (Buttons, Labels, Titel, Beschreibungen, Tooltips, Platzhalter, Fehlermeldungen) MUESSEN ueber Smarty-Config-Variablen `{#variablenname#}` aus den Sprachdateien geladen werden.

### 3b.2 Vier Sprachen erforderlich

Der Shop unterstuetzt **vier Sprachen**. Jede Sprachvariable MUSS in allen vier Sprachen definiert werden:

| Sprache | Conf-Datei |
|---|---|
| Deutsch | `lang/german/lang_german.conf` |
| Englisch | `lang/english/lang_english.conf` |
| Franzoesisch | `lang/french/lang_french.conf` |
| Spanisch | `lang/spanish/lang_spanish.conf` |

**Zusaetzlich** muss fuer jede Sprache eine `.custom`-Datei existieren (kann leer sein):
- `lang/german/lang_german.custom`
- `lang/english/lang_english.custom`
- `lang/french/lang_french.custom`
- `lang/spanish/lang_spanish.custom`

### 3b.3 Beispiel: Neues Modul mit Texten

**Im Smarty-Template:**
```smarty
{config_load file="$language/lang_$language.conf" section="meinmodul"}
<h2>{#modul_title#}</h2>
<p>{#modul_beschreibung#}</p>
<button>{#modul_button_text#}</button>
```

**In `lang_german.conf`:**
```ini
[meinmodul]
modul_title = "Mein Modul"
modul_beschreibung = "Beschreibung auf Deutsch"
modul_button_text = "Jetzt starten"
```

**In `lang_english.conf`:**
```ini
[meinmodul]
modul_title = "My Module"
modul_beschreibung = "Description in English"
modul_button_text = "Start now"
```

**In `lang_french.conf`:**
```ini
[meinmodul]
modul_title = "Mon Module"
modul_beschreibung = "Description en français"
modul_button_text = "Commencer"
```

**In `lang_spanish.conf`:**
```ini
[meinmodul]
modul_title = "Mi Módulo"
modul_beschreibung = "Descripción en español"
modul_button_text = "Empezar ahora"
```

### 3b.4 Verboten

- Keine deutschen Texte direkt im HTML: `<h2>Produktvergleich</h2>` → **VERBOTEN**
- Keine Texte in JavaScript hardcoded: `alert('Fehler')` → **VERBOTEN**, stattdessen `data-*` Attribute mit Smarty-Variablen fuellen
- Keine Texte nur in einer Sprache definieren → **ALLE VIER Sprachen PFLICHT**

---

## 4. Checkliste: Modul-Integration

Bei jeder Modul-Uebernahme ins tpl_mrh_2026 diese Punkte pruefen:

### 4.1 Vor der Integration

- [ ] Welche CSS-Dateien bringt das Modul mit?
- [ ] Werden CSS Custom Properties aus `variables.css` verwendet oder eigene Farben?
- [ ] Welche Smarty-Variablen werden im Template erwartet?
- [ ] Sind die PHP-Array-Keys in UPPERCASE?
- [ ] Welche Lang-Variablen (`{#...#}`) werden benoetigt?
- [ ] Existieren die Lang-Variablen in ALLEN VIER Sprachen (DE, EN, FR, ES)?
- [ ] Existiert die `.custom`-Datei fuer alle vier Sprachen (auch wenn leer)?
- [ ] Sind KEINE Texte direkt im Template hardcodiert?

### 4.2 CSS-Integration

- [ ] CSS-Datei im `css/`-Verzeichnis abgelegt
- [ ] Dateiname folgt der Namenskonvention (`modulname.css` oder `modulname_komponente.css`)
- [ ] Farben verwenden `var(--tpl-*)` oder `var(--mrh-*)` aus dem Farb-Konfigurator
- [ ] Keine hardcoded Farben ohne `var()`-Fallback
- [ ] Schriften verwenden `var(--tpl-font-heading)` und `var(--tpl-font-text)`
- [ ] Keine `!important`-Deklarationen ausser bei zwingender Notwendigkeit
- [ ] CSS wird im richtigen Smarty-Template per `<link>` eingebunden
- [ ] Keine Konflikte mit bestehenden Bootstrap-5-Klassen
- [ ] Responsive: Mobile-Breakpoints getestet (`@media` fuer 576px, 768px, 992px, 1200px)

### 4.3 Template-Integration

- [ ] Smarty-Template im `module/`-Verzeichnis abgelegt
- [ ] `{config_load}` am Anfang des Templates (falls Lang-Variablen benoetigt)
- [ ] Alle `{foreach}`-Schleifen mit UPPERCASE-Keys getestet
- [ ] Alle `{include}`-Pfade verwenden `` `$smarty.const.CURRENT_TEMPLATE` ``
- [ ] Kein `{break}` verwendet (existiert nicht in Smarty 2!)
- [ ] Alle referenzierten Sub-Templates existieren

### 4.4 Nach der Integration

- [ ] Smarty-Cache geleert: `rm -rf templates_c/tpl_mrh_2026/*`
- [ ] OPcache geleert: `curl "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"`
- [ ] Seite im Browser getestet (Desktop + Mobile)
- [ ] Keine weisse Seite / kein Smarty-Crash
- [ ] Alle Texte werden angezeigt (keine leeren `{#...#}`)
- [ ] Farbe im Konfigurator geaendert → Modul uebernimmt neue Farbe korrekt
- [ ] Alle vier Sprachen getestet (DE, EN, FR, ES)

---

## 5. Bekannte Fallstricke

### 5.1 Smarty 2 Einschraenkungen

Das Template laeuft auf **Smarty 2** (nicht Smarty 3/4). Folgende Features sind **NICHT verfuegbar**:

| Feature | Smarty 2 | Alternative |
|---|---|---|
| `{break}` | Nicht vorhanden | `{if $smarty.foreach.name.iteration <= N}` |
| `{continue}` | Nicht vorhanden | `{if}`-Bedingung um den Block |
| `{block}` / `{extends}` | Nicht vorhanden | `{include}` verwenden |
| `{function}` | Nicht vorhanden | Separate Template-Datei + `{include}` |
| Ternary `{$a ?: $b}` | Nicht vorhanden | `{if $a}{$a}{else}{$b}{/if}` |
| `|default` mit Objekten | Kann crashen | Immer `isset()` vorher pruefen |

### 5.2 Caching-Probleme

Wenn `$cache = true` in der PHP-Datei gesetzt ist und das Template sehr grosse Datenmengen verarbeitet (z.B. 29 Filter mit hunderten Values), kann der Smarty-Cache-Schreibvorgang zu Problemen fuehren. Bei komplexen Modulen `$cache = false` setzen oder die Cache-Lifetime begrenzen.

### 5.3 CSS-Komprimierung

Die Shop-interne Komprimierung (`COMPRESS_STYLESHEET = true`) ist aktuell **deaktiviert**, weil der alte PHP-Minifier CSS Custom Properties (`var()`, `calc()`) beschaedigt. Solange Module noch integriert werden, Komprimierung **AUS lassen**. Erst nach Abschluss aller Modul-Integrationen die Komprimierung reparieren (siehe CSS-Audit-Bericht).

### 5.4 FontAwesome-Versionen

Das Template laedt aktuell **FontAwesome 7** ueber `tpl_plugins.min.css`. Aeltere Module die FA4-Klassen (`fa fa-icon`) oder FA6-Klassen (`fa-solid fa-icon`) verwenden, muessen auf FA7-Syntax umgestellt werden, oder die entsprechende FA-Version muss zusaetzlich geladen werden.

| FA-Version | Syntax | Status |
|---|---|---|
| FA4 | `<span class="fa fa-heart"></span>` | Veraltet, `font-awesome.css` kann entfernt werden |
| FA6 | `<span class="fa-solid fa-heart"></span>` | Noch in einigen Modulen, `fontawesome-6.css` vorhanden |
| FA7 | `<span class="fa-solid fa-heart"></span>` | Aktiv geladen, Zielversion |

> **Hinweis:** FA6 und FA7 verwenden die gleiche Klassensyntax (`fa-solid`, `fa-regular`, `fa-brands`). Module die bereits FA6-Klassen verwenden, funktionieren in der Regel auch mit FA7 ohne Aenderung. Nur FA4-Klassen (`fa fa-*`) muessen migriert werden.

---

## 6. Dateistruktur-Uebersicht

```
templates/tpl_mrh_2026/
├── css/
│   ├── general.css.php              ← HEAD-Loader (NICHT EDITIEREN ausser zum Array erweitern)
│   ├── general_bottom.css.php       ← BOTTOM-Loader (NICHT EDITIEREN ausser zum Array erweitern)
│   ├── variables.css                ← Design-System Variablen (erweitern bei neuen Variablen)
│   ├── bootstrap.min.css            ← Framework (NICHT AENDERN)
│   ├── template.css                 ← Haupt-Layout (erweitern fuer globale Aenderungen)
│   ├── mrh-custom.css               ← Custom Overrides (erweitern fuer spezifische Anpassungen)
│   ├── mrh-fonts.css                ← Schriftarten (erweitern bei neuen Fonts)
│   ├── mrh-product-options.css      ← Produktoptionen-Styles
│   ├── mrh_product_2026.css         ← Produktseiten 2026
│   ├── [modulname].css              ← Modul-spezifische CSS (pro Modul eine Datei)
│   └── [modulname]_[komponente].css ← Modul-Komponenten CSS
│
├── lang/
│   ├── german/
│   │   ├── lang_german.conf         ← Sprachvariablen (erweitern mit [section] pro Modul)
│   │   ├── lang_german.custom       ← Custom Overrides (muss existieren, kann leer sein)
│   │   └── lang_german.section      ← Section-spezifische Overrides
│   └── english/
│       ├── lang_english.conf        ← Englische Variablen (gleiche Struktur wie german)
│       ├── lang_english.custom      ← Muss existieren
│       └── lang_english.section     ← Section Overrides
│
├── module/
│   ├── [modulname].html             ← Smarty-Templates pro Modul
│   └── [modulname]_[teil].html      ← Sub-Templates
│
├── boxes/
│   └── [boxname].html               ← Sidebar/Box-Templates
│
└── javascript/
    └── [modulname].js               ← Modul-JavaScript
```

---

## 7. Schnellreferenz: Wo was hingehoert

| Was wird hinzugefuegt? | Wohin? | Wie einbinden? |
|---|---|---|
| Neue globale Farbe/Variable | `css/variables.css` | `:root { --mrh-neue-var: #wert; }` |
| Globales Layout-CSS | `css/template.css` | Ans Ende der Datei anfuegen |
| Spezifisches Override | `css/mrh-custom.css` | Ans Ende der Datei anfuegen |
| Modul-CSS (nur auf Modul-Seiten) | `css/modulname.css` | `<link>` im Smarty-Template |
| Modul-Komponenten-CSS | `css/modulname_teil.css` | `<link>` im Sub-Template |
| Neue Sprachvariable (DE) | `lang/german/lang_german.conf` | `[section]` Block hinzufuegen |
| Neue Sprachvariable (EN) | `lang/english/lang_english.conf` | `[section]` Block hinzufuegen |
| Neue Sprachvariable (FR) | `lang/french/lang_french.conf` | `[section]` Block hinzufuegen |
| Neue Sprachvariable (ES) | `lang/spanish/lang_spanish.conf` | `[section]` Block hinzufuegen |
| Neues Smarty-Template | `module/modulname.html` | PHP: `$smarty->fetch(CURRENT_TEMPLATE.'/module/...')` |
| Neues Sub-Template | `module/modulname_teil.html` | `{include file="..."}` im Haupt-Template |
| Neues JavaScript | `javascript/modulname.js` | `<script>` im Smarty-Template |
| Neue CSS auf JEDER Seite | `css/datei.css` + Eintrag in `general.css.php` oder `general_bottom.css.php` | `$css_array[] = DIR_TMPL_CSS.'datei.css';` |

---

*Bot-Anweisung erstellt am 03.04.2026 - Aktualisiert am 03.04.2026 (Farb-Konfigurator-Regeln, 4-Sprachen-Pflicht, Hardcoded-Text-Verbot hinzugefuegt)*
