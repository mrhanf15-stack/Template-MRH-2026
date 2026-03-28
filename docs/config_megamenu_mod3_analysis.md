# Analyse: Template-Konfiguration, KK-Mega-Menü und Modified 3.0

## 1. KK-Mega-Menü (Antwort auf die Frage des Users)

**Ja, das aktuelle Template hat ein Mega-Menü!** Es heißt "KK-Megamenü" und ist direkt in der config.php konfigurierbar.

### Konfiguration:
```php
defined('BS4_KK_MEGAS') or define('BS4_KK_MEGAS', 'main-3');
```
- `main-3` = ALLE Kategorien als Mega-Menü mit 3 Spalten
- `li5-3,li22-4` = Nur bestimmte Kategorien (ID 5 mit 3 Spalten, ID 22 mit 4 Spalten)
- `main-3-5` = 3 Spalten, ab dem 5. Link "mehr anzeigen..."

### Implementierung:
- jQuery-Plugin `$.fn.KKMega` in `javascript/extra/default.js.php` (Zeile 300-420)
- Unterkategorien werden per AJAX nachgeladen
- CSS-Klassen: `.kk-mega`, `.level2`
- Breite der Spalten: `Math.floor(100/rows)` Prozent

### Für MRH 2026:
- Muss als **Vanilla JS** neu implementiert werden (kein jQuery)
- Bootstrap 5.3 Offcanvas + CSS Grid statt jQuery-Plugin
- AJAX-Nachladen der Unterkategorien beibehalten

## 2. Banner-Platzierung (Template-Konfiguration)

### Banner-Manager:
```php
defined('BS_DEFAULT_BANNER_SETTINGS') or define('BS_DEFAULT_BANNER_SETTINGS', 'n,btn-primary,n,n,btn-primary,n,4000');
```
Schema: Controls, Controlsclass, Controlsrounded, Indicators, Indicatorsclass, Indicatorsrounded, Sliderduration

### Carousel/Slider:
```php
BS4_CAROUSEL_SHOW = 'column'  // 'false', 'screen', 'shop', 'column'
BS4_CAROUSEL_FADE = 'true'    // Fade- oder Slide-Effekt
BS4_TOP_PROD_IN_SLIDER = 'true'  // Top-Artikel als Slider
BS4_BSCAROUSEL_SHOW = 'true'    // Bestseller-Karussell
```

### Banner-Positionen in index.html:
1. **Screen-/Shop-breiter Slider** (Zeile 28-29) - Vor dem Content
2. **Column-Slider** (Zeile 69-70) - In der rechten Spalte
3. **BANNER** (Zeile 82) - Nach main_content
4. **MAREKTINGQ + MAREKTINGQA** (Zeile 86-98) - 3+9 Spalten Layout
5. **Bestseller-Karussell** (Zeile 104)
6. **MAREKTINGQB + MAREKTINGQC** (Zeile 106-118) - 9+3 Spalten Layout
7. **BANNERLINKS** (Zeile 44, 137) - In Sidebar und nach Content

### Für MRH 2026:
- Alle Banner-Positionen müssen erhalten bleiben
- `data-ride="carousel"` → `data-bs-ride="carousel"` (BS5)
- `data-slide` → `data-bs-slide`
- lazysizes → native `loading="lazy"`

## 3. Template-Konfiguration (Vollständig zu übernehmen)

### Hauptbereiche der config.php:
1. **Topleiste** (BS4_SHOW_TOP1-4) - 4 konfigurierbare Spalten
2. **Logo** (BS4_SHOP_LOGO)
3. **Suchfeld** (BS4_SEARCHFIELD_PERMANENT)
4. **Icon-Leiste** (BS4_SHOW_ICON_WITH_NAMES)
5. **Menüleiste** (BS4_MENUBAR_FIXEDTOP, BS4_LOGOBAR_FIXEDTOP)
6. **Responsivemenü** (BS4_RESPONSIVEMENU_SHOW)
7. **Superfishmenü/KK-Mega** (BS4_SUPERFISHMENU_SHOW, BS4_KK_MEGAS)
8. **Kategorien** (BS4_CATEGORIESMENU_MAXLEVEL, _AJAX, _AJAX_SCROLL)
9. **Banner-Manager** (BS_DEFAULT_BANNER_SETTINGS)
10. **Carousel** (BS4_CAROUSEL_SHOW, _FADE)
11. **Boxen Startseite** (BS4_STARTPAGE_BOX_*)
12. **Boxen andere Seiten** (BS4_NOT_STARTPAGE_BOX_*)
13. **Fullcontent** (BS4_STARTPAGE_FULLCONTENT, BS4_PROD_LIST_FULLCONTENT, BS4_PROD_DETAIL_FULLCONTENT)
14. **Produktlisten** (BS4_PRODUCT_LIST_BOX, BS4_PROD_LIST_BOX)
15. **CSS-Klassen** (BS4_TOP1_NAVBAR, BS4_TOP1_BG, etc.)
16. **Module** (BS4_CUSTOMERS_REMIND, BS4_CHEAPLY_SEE, BS4_PRODUCT_INQUIRY, etc.)
17. **Flags** (BS4_FLAG_NEW_SHOW, BS4_FLAG_TOP_SHOW, BS4_FLAG_SPECIAL_SHOW)
18. **Lagerampel** (BS4_TRAFFIC_LIGHTS)
19. **Bootstrap-Theme** (BS4_BOOTSTRAP_THEME - Bootswatch)
20. **Fivebytes Kategorieerweiterungen**

## 4. Modified 3.0+ Kompatibilität

### Wichtige Änderungen (aus Wiki Stand 29.01.2026, Shopversion 3.3.0):

#### Zwingend:
- **Font Awesome 6** statt 4/5 (ab 3.0.1)
- **PHP 8.2/8.3 Kompatibilität**: strpos() Korrekturen
- **jQuery Selektoren** aktualisieren (ab 3.1.1)
- **Smarty 4** (TEMPLATE_ENGINE = 'smarty_4')
- **get_admin_access()** Funktion nutzen (ab 3.1.1)
- **Anrede-Auswahl**: Checkbox → Dropdown (ab 3.1.3)
- **banners.html** überarbeitet (ab 3.1.3)
- **VPE in Warenkorb** anzeigen (ab 3.1.0)

#### Template-Struktur:
- Nova-Template nutzt **Megamenü nativ** (nicht KK-Mega)
- **OIL.js Cookie Consent** mit Google Consent Mode
- **Autocomplete Suche** überarbeitet
- **PayPal Buttons** in Warenkorb-Box

### Für MRH 2026:
- Template-Prefix: `MRH_` statt `BS4_` (eigener Namespace)
- TEMPLATE_ENGINE = 'smarty_4'
- Font Awesome 6 (bereits geplant)
- PHP 8.3 kompatibel
- Alle strpos() mit striktem Vergleich
- Google Consent Mode v2 integrieren
