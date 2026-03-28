# MRH 2026 – Regeln fuer Shop-eigene CSS/JS-Komprimierung

## Shop-Kompressor: `combine_files.inc.php` + `BS4_Compactor`

Der modified eCommerce Shop hat einen eingebauten CSS/JS-Kompressor:
1. Sammelt alle CSS/JS-Dateien aus einem Array
2. Prueft Aenderungszeitpunkte (auch config.php!)
3. Komprimiert via `BS4_Compactor` (strip comments, minify)
4. Speichert als einzelne `.min`-Datei mit `?v=timestamp`

## Strikte Regeln fuer CSS

1. **Keine CSS-Imports** (`@import`) in Stylesheets - der Kompressor loest diese NICHT auf
2. **Alle CSS in separaten `.css`-Dateien** - kein Inline-CSS ausser Critical CSS im `<head>`
3. **Keine CSS-Syntax die der Kompressor bricht:**
   - `@layer` → MUSS getestet werden (Kompressor koennte `@layer` nicht kennen)
   - CSS Nesting → Kompressor koennte verschachtelte Selektoren brechen
   - `@container` → Muss getestet werden
4. **Fallback-Strategie:** Wenn der alte Kompressor moderne CSS-Syntax bricht:
   - Eigenen Kompressor schreiben (`MRH_Compactor`) der moderne Syntax versteht
   - ODER: CSS bereits pre-minified ausliefern und den Shop-Kompressor umgehen
   - ODER: CSS Layers/Nesting nur in der pre-built Version nutzen, nicht im Quellcode

## Strikte Regeln fuer JavaScript

1. **IIFE-Pattern** - Alle Scripts als `(function(){ ... })();`
2. **Keine ES-Module** - Kein `import`/`export` (Kompressor versteht das nicht)
3. **Keine Template-Literals** - Keine Backticks `` ` `` (Kompressor kann brechen)
4. **Semikolons** - Am Ende JEDER Anweisung (ASI-Probleme bei Minification)
5. **Keine Arrow Functions in kritischen Pfaden** - `function(){}` statt `() => {}`
   (Kompressor koennte `=>` als Vergleichsoperator interpretieren)
6. **`'use strict';`** - Innerhalb der IIFE, nicht global
7. **Keine optionalen Chaining** (`?.`) oder Nullish Coalescing (`??`) 
   - Kompressor koennte diese Operatoren nicht kennen
   - Stattdessen: `obj && obj.prop` oder `obj !== null ? obj : fallback`

## Empfohlene Strategie: Eigener MRH-Kompressor

Da der alte `BS4_Compactor` wahrscheinlich keine modernen CSS/JS-Features versteht,
entwickeln wir einen eigenen `MRH_Compactor`:

```php
class MRH_Compactor {
    // Nutzt clean-css (via Node.js CLI) fuer CSS
    // Nutzt terser (via Node.js CLI) fuer JS
    // Beide verstehen ALLE modernen Features
    // Fallback auf einfache Whitespace-Entfernung wenn Node nicht verfuegbar
}
```

**Alternativ:** Pre-Build-Pipeline mit `npm run build`:
- CSS: PostCSS + cssnano (versteht Layers, Nesting, Container Queries)
- JS: Terser (versteht alle ES2024+ Features)
- Ergebnis: Bereits minifizierte Dateien, Shop-Kompressor wird umgangen

## Dateien die der Kompressor verarbeitet

```php
// CSS-Array (aus smarty_default.php)
$css_files = array(
    DIR_TMPL.'css/bootstrap.min.css',
    DIR_TMPL.'css/fontawesome.min.css',
    DIR_TMPL.'css/stylesheet.css',
    // ... weitere CSS-Dateien
);

// JS-Array
$js_files = array(
    DIR_TMPL.'javascript/vendors/bootstrap.bundle.min.js',
    DIR_TMPL.'javascript/mrh2026.js',
    // ... weitere JS-Dateien
);
```

## Font-Pfad-Problem

Der Kompressor aendert relative Pfade in `bootstrap.min.css`:
- `../fonts/` wird zu `fonts/`
- Unsere Font-Dateien muessen daher im richtigen Verzeichnis liegen
