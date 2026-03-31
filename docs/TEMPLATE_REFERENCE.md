# MRH 2026 Template – Technische Referenz

> Zuletzt aktualisiert: 2026-03-31
> Template-Basis: RevPLUS (modified eCommerce / Bootstrap 4)
> Template-Engine: Smarty 4

---

## 1. Verzeichnisstruktur

```
tpl_mrh_2026/
├── config/                    # JSON-Konfigurationsdateien
│   ├── config.php             # Template-Hauptkonfiguration (Konstanten)
│   ├── banners.php            # Banner-Konfiguration
│   ├── tplsettings.json       # Template-Einstellungen → Smarty-Variablen
│   ├── colors.json            # Farbkonfiguration → CSS-Variablen
│   ├── logos.json             # Payment/Shipping-Logos
│   ├── social.json            # Social-Media-Links
│   ├── default_*.json         # Default-Werte für Reset
├── css/                       # Stylesheets
│   ├── general.css.php        # HEAD CSS-Loader (Bootstrap, FA, etc.)
│   ├── general_bottom.css.php # BOTTOM CSS-Loader (Plugins, Custom)
│   ├── fontawesome-6.css      # Font Awesome 6 Pro (Kit)
│   ├── fontawesome-6-custom.css # FA Custom Icons
│   ├── mrh-custom.css         # MRH 2026 Custom Styles
│   ├── mrh-fonts.css          # @font-face Deklarationen
│   ├── variables.css          # CSS Custom Properties
│   ├── bootstrap-4.6.2.min.css
│   ├── revplus.css            # RevPLUS Base-Styles
│   └── fonts/                 # (alt) FA Free Webfonts
├── javascript/
│   ├── general.js.php         # HEAD JS-Loader
│   ├── general_bottom.js.php  # BOTTOM JS-Loader + auto_include()
│   └── extra/                 # Auto-Include JS-Dateien
│       ├── default.js.php     # RevPLUS Standard-JS
│       ├── revplus.js.php     # RevPLUS Erweitert (Slider, Lightbox)
│       └── mrh-core.js.php    # MRH 2026 Vanilla JS Fundament
├── fonts/mrh/                 # WOFF2 Webfonts (Inter + Plus Jakarta Sans)
├── webfonts/                  # FA 6 Pro Webfonts (WOFF2 + TTF)
├── lang/                      # Sprachdateien
│   ├── lang_german.custom     # DE Sprachvariablen (aktiv)
│   ├── lang_english.custom    # EN Sprachvariablen (aktiv)
│   ├── lang_french.custom     # FR Sprachvariablen (aktiv)
│   ├── lang_dutch.custom      # NL Sprachvariablen (aktiv)
│   ├── lang_*.section         # Seitenspezifische Variablen
│   ├── __lang_*.custom        # BS4 Original-Backups
│   ├── banners_*.php          # Banner-Sprachdateien
│   └── buttons_*.php          # Button-Sprachdateien
├── source/
│   ├── boxes/                 # PHP Box-Module
│   │   ├── gettplconfigs.php  # Lädt JSON → Smarty-Variablen
│   │   ├── templateconfig.php # Admin Template-Konfigurator
│   │   ├── shipping_country.php # Lieferland-Box
│   │   └── *.php              # Weitere Boxen
│   ├── boxes.php              # Haupt-Box-Loader
│   └── inc/                   # Include-Dateien
├── boxes/                     # Smarty HTML-Templates für Boxen
├── module/                    # Modul-Templates
├── mail/                      # E-Mail-Templates
├── img/                       # Template-Bilder (Icons, Logos)
├── index.html                 # Haupt-Template
└── popup/                     # Popup-Templates
```

---

## 2. Konfigurationssystem

### 2.1 PHP-Konstanten (config/config.php)

| Konstante | Wert | Beschreibung |
|-----------|------|-------------|
| `TEMPLATE_ENGINE` | `smarty_4` | Smarty-Version |
| `TEMPLATE_HTML_ENGINE` | `html5` | HTML-Ausgabe |
| `TEMPLATE_RESPONSIVE` | `true` | Responsive Design |
| `COMPRESS_JAVASCRIPT` | `true` | JS-Komprimierung |
| `TPLCFG_BOXED` | `false` | Boxed Layout |
| `PRODUCT_LIST_BOX` | Session-basiert | Box/Listen-Ansicht |
| `PRODUCT_LIST_BOX_STARTPAGE` | `true` | Startseite Box-Ansicht |
| `PRODUCT_INFO_BOX` | `true` | Detailseite Box-Ansicht |
| `SPECIALS_CATEGORIES` | `true` | Angebote im Kategoriebaum |
| `WHATSNEW_CATEGORIES` | `true` | Neue Artikel im Kategoriebaum |
| `MAX_PRODUCTS_BOX` | `10` | Max. Produkte pro Box |

### 2.2 JSON-Konfiguration (config/tplsettings.json → Smarty)

Wird über `gettplconfigs.php` geladen und als Smarty-Variablen verfügbar gemacht:

| JSON-Key | Smarty-Variable | Wert | Beschreibung |
|----------|----------------|------|-------------|
| `tpl_cfg_ssl` | `{$tpl_cfg_ssl}` | `on` | SSL aktiv |
| `tpl_cfg_ts` | `{$tpl_cfg_ts}` | `off` | Trusted Shops |
| `tpl_cfg_menu` | `{$tpl_cfg_menu}` | `horizontal` | Menü-Typ |
| `tpl_cfg_infinitescroll` | `{$tpl_cfg_infinitescroll}` | `on` | Infinite Scroll |
| `tpl_cfg_barrierefreiTool` | `{$tpl_cfg_barrierefreiTool}` | `on` | Barrierefreiheit-Tool |

### 2.3 Farb-Konfiguration (config/colors.json)

| CSS-Variable | Wert | Verwendung |
|-------------|------|-----------|
| `tpl-main-color` | `rgb(74, 140, 42)` | Hauptfarbe (Grün) |
| `tpl-main-color-2` | `rgb(30, 30, 30)` | Sekundärfarbe (Dunkel) |
| `tpl-bg-color` | `rgb(255, 255, 255)` | Hintergrund |
| `tpl-bg-color-2` | `rgb(240, 253, 244)` | Hintergrund Akzent |
| `tpl-bg-productbox` | `rgb(255, 255, 255)` | Produktbox BG |
| `tpl-bg-footer` | `rgb(15, 23, 42)` | Footer BG |
| `tpl-text-standard` | `rgb(15, 23, 42)` | Standard-Text |
| `tpl-text-headings` | `rgb(15, 23, 42)` | Überschriften |
| `tpl-text-button` | `rgb(255, 255, 255)` | Button-Text |
| `tpl-text-footer` | `rgb(148, 163, 184)` | Footer-Text |
| `tpl-text-footer-headings` | `rgb(255, 255, 255)` | Footer-Überschriften |

### 2.4 Logo-Konfiguration (config/logos.json)

**Payment:** vorkasse, kreditkarten, applepay, googlepay, lastschrift, rechnung
**Shipping:** dhl, ups, gls

### 2.5 Social-Media-Konfiguration (config/social.json)

| Plattform | Smarty-Variable |
|-----------|----------------|
| Facebook | `{$tpl_cfg_social_links.facebook}` |
| Twitter | `{$tpl_cfg_social_links.twitter}` |
| Instagram | `{$tpl_cfg_social_links.instagram}` |
| Pinterest | `{$tpl_cfg_social_links.pinterest}` |
| LinkedIn | `{$tpl_cfg_social_links.linkedin}` |

---

## 3. CSS-Pipeline

### 3.1 HEAD CSS (general.css.php)

Geladen im `<head>` – kritische Styles:

1. Bootstrap 4.6.2 (`bootstrap-4.6.2.min.css`)
2. Font Awesome 6 Pro (`fontawesome-6.css`)
3. Font Awesome Custom Icons (`fontawesome-6-custom.css`)
4. RevPLUS Base (`revplus.css`)
5. MRH Fonts (`mrh-fonts.css`)
6. MRH CSS Variables (`variables.css`)
7. MRH Custom Styles (`mrh-custom.css`)

### 3.2 BOTTOM CSS (general_bottom.css.php)

Geladen vor `</body>` – nicht-kritische Styles:

1. Swiper
2. Fancybox
3. Animate.css
4. RevPLUS Plugins
5. Auto-Include aus `css/extra/`

### 3.3 Komprimierung

Bei `COMPRESS_STYLESHEET = true`:
- HEAD CSS → `stylesheet.min.css`
- BOTTOM CSS → `css/tpl_plugins.min.css`

**Wichtig:** Nach Änderungen an CSS-Dateien müssen die `.min.css`-Dateien gelöscht werden, damit sie neu generiert werden!

---

## 4. JavaScript-Pipeline

### 4.1 HEAD JS (general.js.php)

- jQuery
- Bootstrap Bundle
- Popper.js

### 4.2 BOTTOM JS (general_bottom.js.php)

1. Swiper
2. Fancybox
3. Auto-Include aus `javascript/extra/`:
   - `default.js.php` – RevPLUS Standard
   - `revplus.js.php` – RevPLUS Erweitert
   - `mrh-core.js.php` – MRH 2026 Features

### 4.3 Komprimierung

Bei `COMPRESS_JAVASCRIPT = true`:
- Alle JS → `javascript.min.js`

**Wichtig:** Nach Änderungen an JS-Dateien muss `javascript.min.js` gelöscht werden!

---

## 5. Sprachsystem

### 5.1 Dateitypen

| Datei | Scope | Syntax |
|-------|-------|--------|
| `lang_*.custom` | Global (alle Seiten) | `variable = 'Wert'` |
| `lang_*.section` | Seitenspezifisch (`[section]`) | `[seitenname]` + `variable = 'Wert'` |
| `__lang_*.custom` | BS4 Original-Backup | Nicht aktiv, nur Referenz |

### 5.2 Verwendung in Templates

```smarty
{* Globale Variable *}
{#mrh_topbar_phone#}

{* Seitenspezifische Variable (aus [section]) *}
{#checkout_edit_address#}
```

### 5.3 Verfügbare Sprachen

| Sprache | Custom-Datei | Section-Datei |
|---------|-------------|---------------|
| Deutsch | `lang_german.custom` | `lang_german.section` |
| Englisch | `lang_english.custom` | `lang_english.section` |
| Französisch | `lang_french.custom` | `lang_french.section` |
| Niederländisch | `lang_dutch.custom` | `lang_dutch.section` |

### 5.4 MRH 2026 Sprachvariablen

| Variable | DE | EN |
|----------|----|----|
| `mrh_topbar_phone` | +43 512 312 411 | +43 512 312 411 |
| `mrh_topbar_delivery` | Schnelle Lieferung | Fast Delivery |
| `mrh_topbar_payment` | Einfache, sichere Zahlung | Easy, secure payment |
| `mrh_topbar_letter` | Briefbestellung | Letter Order |
| `mrh_topbar_freeshipping` | Kostenfrei ab: AT 40€, DE 85€, EU 150€ | Free from: AT €40, DE €85, EU €150 |
| `mrh_shipping_bar_text` | Kostenloser Versand ab 40€ Bestellwert | Free shipping on orders over €40 |
| `mrh_freeship_at` | 40 | 40 |
| `mrh_freeship_de` | 85 | 85 |
| `mrh_freeship_eu` | 150 | 150 |

---

## 6. Smarty-Variablen Referenz

### 6.1 Globale Template-Variablen

| Variable | Quelle | Beschreibung |
|----------|--------|-------------|
| `{$tpl_path}` | boxes.php | Template-Pfad |
| `{$home}` | boxes.php | 1 = Startseite, 0 = andere |
| `{$bestseller}` | boxes.php | Bestseller vorhanden |
| `{$fullcontent}` | boxes.php | Vollbreite-Seite (Checkout etc.) |
| `{$is_admin}` | boxes.php | Admin eingeloggt |
| `{$checkoutClass}` | index.html | CSS-Klasse für Checkout |

### 6.2 Konfigurationsvariablen (aus tplsettings.json)

| Variable | Beschreibung |
|----------|-------------|
| `{$tpl_cfg_ssl}` | SSL on/off |
| `{$tpl_cfg_ts}` | Trusted Shops on/off |
| `{$tpl_cfg_menu}` | horizontal/vertical |
| `{$tpl_cfg_infinitescroll}` | on/off |
| `{$tpl_cfg_barrierefreiTool}` | on/off |
| `{$tpl_cfg_colors}` | Array mit Farbwerten |
| `{$tpl_cfg_payment_logos}` | Array Payment-Logos |
| `{$tpl_cfg_shipping_logos}` | Array Shipping-Logos |
| `{$tpl_cfg_social_links}` | Array Social-Links |

### 6.3 PHP-Konstanten in Templates

| Smarty-Syntax | PHP-Konstante |
|--------------|---------------|
| `{$smarty.const.STORE_NAME}` | Shop-Name |
| `{$smarty.const.TPLCFG_BOXED}` | Boxed Layout |
| `{$smarty.const.SHIPPING_TIME}` | Lieferzeit-Text |
| `{$smarty.const.YOUR_PRICE}` | "Ihr Preis" |
| `{$smarty.const.UNIT_PRICE}` | Grundpreis |
| `{$smarty.const.INSTEAD}` | "statt" |
| `{$smarty.const.MSRP}` | UVP |
| `{$smarty.const.ONLY}` | "nur" |
| `{$smarty.const.TEXT_RESULT_PAGE}` | Ergebnisseite |

### 6.4 Box-Variablen

| Variable | Box-PHP | Beschreibung |
|----------|---------|-------------|
| `{$box_SHIPPING_COUNTRY}` | shipping_country.php | Lieferland-Dropdown |
| `{$box_CURRENCIES}` | currencies.php | Währungs-Dropdown |
| `{$box_LANGUAGES}` | languages.php | Sprach-Auswahl |
| `{$box_SEARCH}` | search.php | Suchbox |
| `{$box_BESTSELLERS}` | best_sellers.php | Bestseller |
| `{$box_CONTENT}` | content.php | Content-Box |
| `{$box_INFORMATION}` | information.php | Info-Box |
| `{$box_NEWSLETTER}` | newsletter.php | Newsletter-Box |
| `{$box_ADMIN}` | admin.php | Admin-Toolbar |
| `{$box_templateconfig}` | templateconfig.php | Template-Konfigurator |

---

## 7. Lieferland-System

### 7.1 Session-Variable

Das Lieferland wird in der PHP-Session gespeichert:

```php
$_SESSION['country']              // Primär (countries_id)
$_SESSION['customer_country_id']  // Fallback
STORE_COUNTRY                     // Default (Shop-Konfiguration)
```

### 7.2 Verfügbarkeit in Templates

Das Lieferland ist **nicht direkt** als Smarty-Variable verfügbar. Es wird nur über die Box `{$box_SHIPPING_COUNTRY}` als fertiges HTML-Dropdown ausgegeben.

**Für länderspezifische Logik im Template gibt es zwei Optionen:**

1. **PHP-Lösung:** Eigene Box erstellen, die `$_SESSION['country']` als Smarty-Variable zuweist
2. **JS-Lösung:** Das `#shipping-country-select` Dropdown im DOM auslesen

### 7.3 Versandkostenfrei-Schwellen

| Land/Region | Schwelle |
|-------------|----------|
| Österreich (AT) | 40 € |
| Deutschland (DE) | 85 € |
| EU (alle anderen) | 150 € |

---

## 8. Font Awesome 6 Pro (Kit)

### 8.1 Installation

- **CSS:** `css/fontawesome-6.css` + `css/fontawesome-6-custom.css`
- **Webfonts:** `webfonts/` (12 Dateien: WOFF2 + TTF)
- **Styles:** Solid, Regular, Light, Thin, Brands + Custom

### 8.2 Custom Icons

| Klasse | Beschreibung |
|--------|-------------|
| `fak fa-zylinder-fontawesome-schief` | MRH Zylinder-Logo |

### 8.3 Verfügbare Icon-Klassen (Beispiele)

```html
<i class="fa-solid fa-truck-fast"></i>      <!-- Schnelle Lieferung -->
<i class="fa-solid fa-shield-halved"></i>   <!-- Sichere Zahlung -->
<i class="fa-solid fa-envelope"></i>        <!-- Briefbestellung -->
<i class="fa-solid fa-gift"></i>            <!-- Versandkostenfrei -->
<i class="fa-solid fa-phone"></i>           <!-- Telefon -->
<i class="fa-light fa-..."></i>             <!-- Light-Variante (Pro) -->
<i class="fa-thin fa-..."></i>              <!-- Thin-Variante (Pro) -->
<i class="fa-regular fa-..."></i>           <!-- Regular-Variante (Pro) -->
```

---

## 9. Sprachvariablen-Statistik

### 9.1 Übersicht

| Kategorie | Anzahl |
|-----------|--------|
| Verwendete Sprachvariablen in Templates | 374 |
| Definiert in `.custom` | 139 |
| Definiert in `.section` | 89 |
| Vom modified Core bereitgestellt | ~146 |

### 9.2 Fehlende Variablen (verwendet aber nicht in .custom/.section)

Diese ~194 Variablen werden vom modified eCommerce Core bereitgestellt und müssen **nicht** in den Template-Sprachdateien definiert werden. Sie kommen aus den Core-Sprachdateien unter `lang/german/` im Shop-Root.

---

## 10. Deploy-Checkliste

### Nach CSS/JS-Änderungen:

1. Dateien auf Server hochladen
2. Komprimierte Dateien löschen:
   ```bash
   rm templates/tpl_mrh_2026/stylesheet.min.css
   rm templates/tpl_mrh_2026/css/tpl_plugins.min.css
   rm templates/tpl_mrh_2026/javascript.min.js
   ```
3. OPcache Reset: `curl "https://mr-hanf.de/opcache_reset.php?token=MrHanf2024Reset"`
4. Template-Cache leeren: `rm -rf templates_c/*`

### Nach Sprachdatei-Änderungen:

1. Dateien hochladen
2. Template-Cache leeren: `rm -rf templates_c/*`

---

## 11. Wichtige Hinweise

### 11.1 CSS-Komprimierung

Wenn `COMPRESS_STYLESHEET` aktiv ist, werden alle CSS-Dateien zu einer `.min.css` zusammengefasst. Neue CSS-Dateien werden erst sichtbar, wenn die alte `.min.css` gelöscht wird.

### 11.2 JS Auto-Include

Alle `.php`-Dateien in `javascript/extra/` werden automatisch per `auto_include()` geladen. Neue JS-Dateien müssen nur in diesen Ordner gelegt werden.

### 11.3 Smarty-Syntax

```smarty
{* Variable *}           {$variablename}
{* Sprachvariable *}     {#variablename#}
{* PHP-Konstante *}      {$smarty.const.KONSTANTE}
{* Bedingung *}          {if $var == 'wert'}...{/if}
{* Schleife *}           {foreach $array as $item}...{/foreach}
{* Include *}            {include file='pfad/datei.html'}
{* Kommentar *}          {* Dies ist ein Kommentar *}
```
