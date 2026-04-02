# Live-Template Sync Report

> **Datum:** 2. April 2026
> **Quelle:** `https://mr-hanf.at/tpl_mrh_2026_live.zip` (105 MB)
> **Ziel:** `mrhanf15-stack/Template-MRH-2026` Repository

---

## Zusammenfassung

Die vollständige Analyse des Live-Templates von mr-hanf.at im Vergleich zum Template-MRH-2026 Repository ergab folgende Ergebnisse:

| Metrik | Wert |
|--------|------|
| **Live-Template Dateien** (ohne .git) | 2.088 |
| **Repo-Template Dateien** (ohne .git) | 1.450 (vorher) / 1.662 (nach Sync) |
| **Nur im Live** | 757 Dateien |
| **Nur im Repo** | 119 Dateien |
| **Gemeinsame Dateien** | 1.331 |
| **Davon inhaltlich unterschiedlich** | 660 |
| **Synchronisierte Dateien** | 212 (kritische + wichtige) |

---

## Versionsvergleich

| Komponente | Live (mr-hanf.at) | Repo (GitHub) | Status |
|------------|-------------------|---------------|--------|
| **Bootstrap** | 5.2.3 | 5.3.0 | Repo ist neuer |
| **jQuery** | 3.6.0 | 3.7.1 | Repo ist neuer |
| **Font Awesome** | 6 Pro | 6 Pro | Identisch |
| **product_info_v1.html** | 842 Zeilen | 842 Zeilen | Identisch |

---

## Synchronisierte Dateien (212 Dateien)

### tpl_parts/ (5 Dateien)

Fehlende Template-Includes aus dem Live-System übernommen:

| Datei | Beschreibung |
|-------|-------------|
| `content_home.html` | Startseiten-Content-Bereich |
| `content_home_2.html` | Alternativer Startseiten-Content |
| `map.html` | Google Maps Integration |
| `side_content.html` | Sidebar-Content |
| `social_icons.html` | Social Media Icons |

### config/ (10 Dateien)

JSON-Konfigurationsdateien für das Template-Dashboard:

| Datei | Beschreibung |
|-------|-------------|
| `colors.json` | Aktive Farbkonfiguration |
| `default_colors.json` | Standard-Farbwerte |
| `logos.json` | Logo-Konfiguration |
| `default_logos.json` | Standard-Logo-Pfade |
| `tplsettings.json` | Template-Einstellungen |
| `default_tplsettings.json` | Standard-Template-Einstellungen |
| `megamenu_config.json` | Mega-Menü Konfiguration |
| `module_mega_menu.json` | Mega-Menü Modul-Einstellungen |
| `social.json` | Social Media Links |
| `default_social.json` | Standard Social Media Links |
| `dashboard_modules.json` | Dashboard-Modul-Konfiguration |

### boxes/ (10 Dateien)

Zusätzliche Box-Templates:

| Datei | Beschreibung |
|-------|-------------|
| `box_cart_mobile.html` | Mobiler Warenkorb |
| `box_footer_text.html` | Footer-Text |
| `box_miscellaneous2.html` | Zusätzliche Informationen |
| `box_newsrss.html` | News RSS Feed |
| `box_shopinfo.html` | Shop-Informationen |
| `box_slider_bestsellers.html` | Bestseller-Slider |
| `box_slider_new.html` | Neue Artikel Slider |
| `box_slider_random.html` | Zufällige Artikel Slider |
| `box_slider_specials.html` | Angebote-Slider |
| `box_slider_top.html` | Top-Artikel Slider |

### module/ (22 Dateien)

Shop-Module und Includes:

- `module/ajax/modal-cart-content.html` – AJAX Warenkorb-Modal
- `module/categorie_listing/samen_shop.html` – Samen-Shop Kategorie-Listing
- `module/customers_notice/` (8 Dateien) – Kundenhinweise, Cookie-Consent, Overlays
- `module/includes/` (8 Dateien) – Produkt-Box, Preis-Listing, Shipping-Tooltip etc.
- `module/product_info/product_info_v1.html` – Aktuelle Produktseite

### webfonts/ (8 Dateien)

Font Awesome Pro Webfonts (Duotone, Utility, Whiteboard).

### fonts/ (9 Dateien)

Pe-icon-7-stroke und Simple-Line-Icons Schriften.

### unitegallery/ (107 Dateien)

Komplettes Unite Gallery Plugin (Bildergalerie).

### admin/ (4 Dateien)

MRH Dashboard und Mega-Menü Admin-Modul.

### smarty/ (1 Datei)

`function.traffic_light.php` – Smarty-Funktion für Ampel-Anzeige.

### assets/bootstrap/ (2 Dateien)

Bootstrap 5.2.3 CSS + JS (wird auf 5.3.0 aktualisiert).

### Root-Dateien (3 Dateien)

`custom.css`, `favicon.ico`, `apple-touch-icon.png`.

---

## Wichtige Unterschiede: index.html

Die `index.html` ist die zentrale Template-Datei. Es gibt 5 relevante Unterschiede:

### 1. inserttags Modifier

```smarty
{* LIVE: *}
{$main_content|inserttags}

{* REPO: *}
{$main_content}
```

**Analyse:** Der `inserttags` Modifier ersetzt Content-Tags (z.B. `{$content_1234}`) innerhalb des Main-Contents. Er wird in 7 Live-Templates und 6 Repo-Templates verwendet. Die Entfernung im Repo-index.html war beabsichtigt, da der Modifier Performance kostet und die meisten Inhalte keine eingebetteten Tags verwenden.

**Empfehlung:** Beibehalten wie im Repo (ohne `inserttags`). Falls Content-Tags in `main_content` benötigt werden, kann der Modifier gezielt wieder hinzugefügt werden.

### 2. Newsletter-Bereich

```smarty
{* LIVE: *}
{1003206|contentAnywhere|inserttags}

{* REPO: *}
{if isset($box_NEWSLETTER)}
    {$box_NEWSLETTER}
{/if}
```

**Analyse:** Live nutzt `contentAnywhere` mit Content-ID 1003206 für den Newsletter. Das Repo nutzt das Standard-Box-System. Beide Ansätze funktionieren.

**Empfehlung:** Repo-Version beibehalten (Standard-Box-System ist flexibler und wartbarer).

### 3. Mobile Navigation (nur im Repo)

```smarty
{include file="tpl_parts/navigation_mobile.html"}
{include file="tpl_parts/menu_offcanvas.html"}
```

**Analyse:** Neue Mobile-Navigation im Repo, die im Live noch fehlt.

**Empfehlung:** Beim nächsten Deployment auf Live übernehmen.

---

## Dateien nur im Repo (119 Dateien)

Diese Dateien existieren im Repo, aber nicht im Live-Template. Die wichtigsten:

| Kategorie | Anzahl | Beispiele |
|-----------|--------|-----------|
| CSS (Basis) | ~45 | `css/affiliate.css`, `css/blog.css`, `css/pushy.css`, `css/seedfinder-combined.css` |
| CSS Fonts | ~12 | Open Sans Webfonts (woff2) |
| CSS Images | ~10 | Carousel Controls, Loading GIF, Stars Rating |
| Module | ~15 | `module/content.html`, `module/popup_content.html`, `module/product_info.html` |
| Boxes | ~5 | `boxes/box_giftcode.html` |
| README.md | 1 | Projekt-Dokumentation |

**Analyse:** Diese Dateien wurden im Repo als Teil des BS5.3-Redesigns erstellt und müssen noch auf den Live-Server deployed werden.

---

## Noch nicht synchronisierte Live-Dateien (545 verbleibend)

Folgende Kategorien wurden bewusst nicht synchronisiert:

| Kategorie | Anzahl | Grund |
|-----------|--------|-------|
| **img/** | 183 | Bilder – zu groß für Git, werden direkt auf Server verwaltet |
| **css/** (zusätzliche) | ~47 | Alte CSS-Dateien die durch BS5.3 ersetzt werden |
| **javascript/** (zusätzliche) | ~144 | Alte JS-Dateien, jQuery-Plugins die durch Vanilla JS ersetzt werden |
| **source/** | 15 | PHP-Source-Dateien (Box-Logik) – bereits im Repo vorhanden |
| **assets/bootstrap/** (Rest) | ~42 | Vollständiges BS 5.2.3 Paket – wird durch 5.3.0 ersetzt |
| **module/ (Backups/Tests)** | ~10 | `.bak`-Dateien, Test-Verzeichnisse, Kopien |
| **buttons/** | ~4 | Button-Grafiken (sprachspezifisch) |
| **lang/** | ~5 | Zusätzliche Sprachdateien |

---

## Deployment-Plan: Repo -> Live (mr-hanf.at)

### Phase 1: Bootstrap 5.3.0 Update

```bash
# Auf dem Server
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/

# Bootstrap 5.3.0 von Repo holen
curl -o javascript/bootstrap.bundle.min.js \
  "https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026/javascript/bootstrap.bundle.min.js"

# jQuery 3.7.1 von Repo holen
curl -o javascript/jquery.min.js \
  "https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026/javascript/jquery.min.js"
```

### Phase 2: Mobile Navigation

```bash
# navigation_mobile.html und menu_offcanvas.html deployen
curl -o tpl_parts/navigation_mobile.html \
  "https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026/tpl_parts/navigation_mobile.html"

curl -o tpl_parts/menu_offcanvas.html \
  "https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026/tpl_parts/menu_offcanvas.html"
```

### Phase 3: index.html Update

```bash
# ACHTUNG: Backup zuerst!
cp index.html index.html.bak_$(date +%Y%m%d)

# Neue index.html von Repo
curl -o index.html \
  "https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026/index.html"
```

### Phase 4: Cache leeren

```bash
rm -f stylesheet.min.css tpl_plugins.min.css javascript.min.js
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
curl "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
```

---

## Cleanup

Nach Abschluss der Analyse die ZIP-Datei vom Webroot entfernen:

```bash
rm /home/www/doc/28856/dcp288560004/mr-hanf.at/www/tpl_mrh_2026_live.zip
```

---

## TODO (nächste Schritte)

1. Bootstrap 5.2.3 -> 5.3.0 Update auf Live deployen
2. jQuery 3.6.0 -> 3.7.1 Update auf Live deployen
3. Mobile Navigation (navigation_mobile.html + menu_offcanvas.html) deployen
4. index.html mit box_NEWSLETTER und Mobile-Nav-Includes deployen
5. Neue CSS/JS-Dateien aus Repo auf Live deployen
6. `tpl_mrh_2026_live.zip` vom Webroot entfernen
7. Produktseiten-Template (MRH 2026 Produkt-Template) integrieren
