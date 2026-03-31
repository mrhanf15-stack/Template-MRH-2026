# MRH 2026 Template – CHANGELOG

> **Zwischenergebnis Stand: 31. März 2026**
> Repository: `mrhanf15-stack/Template-MRH-2026`

---

## Projektübersicht

Das Template **tpl_mrh_2026** ist ein vollständiges Redesign des Mr. Hanf Online-Shops auf Basis von **Bootstrap 5.3**, **Font Awesome 6 Pro** und **modified eCommerce 3.x**. Ziel ist ein modernes, schnelles und mobiloptimiertes Einkaufserlebnis.

---

## Phase 1: Foundation (Commits `a98e99e` – `a32a1b1`)

### Projektinitialisierung & Template-Grundgerüst

| Commit | Beschreibung |
|--------|-------------|
| `a98e99e` | Projektinitialisierung und Template-Analyse |
| `d05f65d` | Technische Speed-Strategie, CSS/JS-Architektur, Mega-Menü Analyse |
| `2bbcd46` | MRH 2026 Template v1.0 – Komplettes Template für Testshop |
| `3978bca` | Alle 129 Frontend-Module in BS5.3 komplett neu geschrieben |
| `b7da5df` | Schema.org JSON-LD (Product, Brand, FAQ, Blog, ItemList, Organization), ShopVote, Sprachdateien (390+ Konstanten DE/EN) |
| `d1a9cf0` | 4-Sprachen-System komplett – DE, EN, FR, ES |
| `4675099` | Modified 3 Kompatibilität: Varianten-Verzeichnisse, BS5 Migration, Smarty 5 ready |
| `fde52cc` | CRITICAL FIX: Direct Access Fehler behoben |
| `a1f0d51` | Modified eCommerce Pflichtdateien ergänzt – Shop startet korrekt |
| `3569015` | opcache_reset.php – Token-geschützter OPCache Reset |
| `482a21d` | Datei-Upload (Logo-Assets) |
| `a32a1b1` | MRH 2026 Foundation V2 – FA6 Pro Kit, CSS/JS Foundation, Sprachdateien, Fonts |

---

## Phase 2: Header Redesign (Commits `0d62e57` – `f92938d`)

### Desktop Header

| Commit | Beschreibung |
|--------|-------------|
| `0d62e57` | Header modernisiert: Jubiläums-Logo, CSS-Overhaul, Suchleiste, Icon-Hover, Navigation-Styling |
| `1d06946` | Logo auf `logo_head_jubilaeum_png` (10 Jahre), WebP erstellt, adminspacer CSS-Fix |
| `a4ec730` | Trust-Badges entfernt, Icons auf FA 6 Pro, Hamburger als FA-Icon |
| `2307fc7` | Hamburger Desktop-hidden, Globe statt Flagge, Merkzettel+Warenkorb FA 6 Pro Icons |
| `9c71e6c` | **Wireframe-Style:** Pill-Suchleiste (border-radius 999px), grüner Such-Button, FA 6 Pro Icons, Placeholder "Cannabis Samen suchen..." per JS (mehrsprachig) |
| `c79398e` | Header-Feinschliff: Logo 100px, Such-Icon zentriert (flex statt absolute), Icon-Abstände (margin-top 4px) |
| `96b8cca` | Icon-Abstände 16px, einheitliche Zentrierung, Hamburger im Sticky Header sichtbar |
| `63e812c` | Such-Button weißer Spalt eliminiert (margin-left -2px, flex-shrink 0) |

### Desktop Header – Aktueller Stand

```
[Logo 100px]  [☰ Menü]  [🔍 Cannabis Samen suchen... 🟢]  [🌐 Einstellungen] [👤 Konto] [❤️ Merkzettel] [🛒 Warenkorb]
──────────────────────────────────────────────────────────────────────────────────
[🏠] [SAMEN SHOP ▾] [CANNABISPFLANZEN ▾] [GROWSHOP ▾] [HEADSHOP ▾] [ANGEBOTE] [NEUE ARTIKEL]
```

**Sticky Header (beim Scrollen):**
- Kompakter Header mit kleinerem Logo (52px)
- Hamburger-Menü wird auf Desktop sichtbar (ersetzt die ausgeblendete Navigation)

---

### Mobile Header & Bottom Bar

| Commit | Beschreibung |
|--------|-------------|
| `b485118` | **Mobile Bottom Bar:** Home, Suche, Seedfinder (hervorgehoben), Merkliste, Warenkorb – fixiert am unteren Bildschirmrand |
| `8c15b89` | Mobile Header schlank: Merkzettel/Warenkorb ausgeblendet (in Bottom Bar), Suche-Overlay statt Scroll, Badges live via MutationObserver |
| `eca37f2` | Beschriftungen auf Mobile ausgeblendet (nur Icons), kompakteres Layout |
| `908dc4f` | Suchleiste auf eigene Zeile (flex-basis 100%) |
| `c10eb09` | Suchleiste volle Breite (order 99, flex-wrap nowrap) |
| `f92938d` | **HTML umstrukturiert:** Suche als eigene Row (`mrh-header-row-2`), Icons in gleicher Zeile wie Logo (`mrh-header-row-1`) |

### Mobile Header – Aktueller Stand

```
Zeile 1:  [Logo 50px] [☰]                    [🌐] [👤]
Zeile 2:  [🔍 Cannabis Samen suchen...              🟢]
```

### Mobile Bottom Bar – Aktueller Stand

```
[🏠 Home] [🔍 Suche] [🌱 Seedfinder] [❤️ Merkliste] [🛒 Warenkorb]
```

**Features:**
- Fixiert am unteren Bildschirmrand (`position: fixed; bottom: 0`)
- Seedfinder als hervorgehobenes mittleres Icon (grüner Kreis, nach oben versetzt)
- **Suche:** Öffnet ein Slide-Up Overlay (nicht Scroll nach oben)
- **Merkzettel-Badge:** Live-Synchronisation mit Header via MutationObserver
- **Warenkorb-Badge:** Live-Synchronisation mit Header via MutationObserver
- iPhone Safe Area Support (`env(safe-area-inset-bottom)`)
- Backdrop-Blur Effekt

---

## Geänderte Dateien (Übersicht)

| Datei | Beschreibung |
|-------|-------------|
| `css/mrh-custom.css` | Alle Custom-Styles: Header, Suchleiste, Icons, Sticky, Bottom Bar, Suche-Overlay, Mobile Overrides |
| `tpl_parts/content_head.html` | Header-Struktur: 2 Rows (Icons + Suche getrennt), FA 6 Pro Icons, Offcanvas |
| `tpl_parts/bottom_bar.html` | Mobile Bottom Bar: 5 Items, Suche-Overlay HTML, Badge-Spans |
| `boxes/box_search.html` | Suchleiste: Pill-Shape, FA 6 Pro Lupe |
| `javascript/extra/mrh-core.js.php` | Placeholder-Override, Sticky Header, Bottom Bar JS (Overlay, Badges, Active State) |
| `lang/lang_german.custom` | Deutsche Sprachvariablen (Bottom Bar, Topbar, Shipping) |
| `lang/lang_english.custom` | Englische Sprachvariablen (Bottom Bar, Topbar, Shipping) |
| `index.html` | Bottom Bar Include eingefügt |

---

## CSS-Architektur (`mrh-custom.css`)

Die CSS-Datei ist in nummerierte Sektionen gegliedert:

| Nr. | Sektion | Beschreibung |
|-----|---------|-------------|
| 1 | CSS Variables | `--mrh-primary`, `--mrh-primary-dark` |
| 2 | Logo | Größe, Hover, Responsive |
| 3 | Suchleiste | Pill-Shape, grüner Button, Input-Styling |
| 4 | Icon-Menü | Abstände, Zentrierung, Hover |
| 5 | Navigation | Desktop-Nav, Dropdowns |
| 6 | Sticky Header | Kompakter Header beim Scrollen |
| 7 | Hamburger | Desktop hidden, Sticky sichtbar |
| 8–10 | Topbar & Shipping | Aktionsleisten |
| 11 | Tablet Breakpoint | 768–991px Anpassungen |
| 12 | Mobile Breakpoint | ≤767px: Header schlank, Beschriftungen hidden |
| 13 | Suche-Overlay | Slide-Up Overlay für Mobile-Suche |
| 14 | Bottom Bar | Fixierte Mobile-Navigation |

---

## Nächste Schritte (offen)

- Navigation / Mega-Menü Styling
- Produktkarten Redesign
- Footer Redesign
- Kategorie-Seiten
- Produktdetail-Seite
- Warenkorb / Checkout
- Performance-Optimierung (CSS/JS Minification)

---

## Deploy-Workflow

```bash
# 1. Im Template-Verzeichnis auf dem Server
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/

# 2. Neueste Dateien aus Repo holen
git fetch origin
git checkout origin/main -- tpl_mrh_2026/

# 3. An richtige Stellen kopieren
cp tpl_mrh_2026/css/mrh-custom.css css/mrh-custom.css
cp tpl_mrh_2026/tpl_parts/content_head.html tpl_parts/content_head.html
cp tpl_mrh_2026/tpl_parts/bottom_bar.html tpl_parts/bottom_bar.html
cp tpl_mrh_2026/boxes/box_search.html boxes/box_search.html
cp tpl_mrh_2026/javascript/extra/mrh-core.js.php javascript/extra/mrh-core.js.php
cp tpl_mrh_2026/lang/lang_german.custom lang/lang_german.custom
cp tpl_mrh_2026/lang/lang_english.custom lang/lang_english.custom
cp tpl_mrh_2026/index.html index.html

# 4. Cache leeren
rm -f stylesheet.min.css tpl_plugins.min.css javascript.min.js
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
curl "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
```
