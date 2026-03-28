# IST-Zustand: Layout & Darstellung mr-hanf.de

## 1. Startseite - Seitenstruktur (von oben nach unten)

| Position | Element | Beschreibung |
|----------|---------|--------------|
| 1 | **Topbar** | Schmale Leiste mit USPs: Telefon, "Schnelle Lieferung", Schlüssel-Icon, E-Mail, LKW-Icon. Klasse: `navbar bg-light text-secondary` |
| 2 | **Logobar** | Logo links (300x171px, Jubiläums-Logo), Versandland-Auswahl rechts. ID: `logobar` |
| 3 | **Hauptnavigation** | Hamburger-Menü links, Seitenleiste-Button rechts (mobile). Desktop: Kategorien-Menü. Klasse: `navbar-brand menu-btn` |
| 4 | **Sidebar links** | Boxen: Infobox, Versandland, Login, Bewertungen, Partner-Bereich, Kategorien-Menü, Blog-Links |
| 5 | **Content-Bereich** | TOP-Artikel Carousel, Bestseller Carousel |
| 6 | **Footer** | Mehrspaltig: "Mehr über...", "Informationen", Zahlungsweisen, Versanddienstleister, Soziale Medien, Newsletter, Beliebte Seiten, SEO-Text (contentAnywhere), Kontakt, Copyright |

## 2. Produktkarte (Listing/Carousel) - IST-Zustand

Jede Produktkarte ist eine Bootstrap 4 `.card` mit folgender Struktur:

| Position | Element | CSS-Klasse | Beschreibung |
|----------|---------|------------|--------------|
| 1 | **Ribbon/Flag** | `.ribbon .bg-primary` | "Top", "Neu", "Angebot" Badge oben links |
| 2 | **Produktbild** | `.lb_image img.lazyload.img-fluid` | Lazy-loaded, 200x200px, data-src Pattern |
| 3 | **Produktname** | `.lb_title .lead .text-secondary` | H2-Tag, zentriert |
| 4 | **Hersteller** | `blockquote-footer cite` | Name des Herstellers unter dem Titel |
| 5 | **Bewertung** | `.lb_ratings .bs4_avg_container` | Sterne + Anzahl Bewertungen |
| 6 | **Buttons** | `.lb_buttons` | Warenkorb (cart), Wunschliste (heart), Details (eye) |
| **FEHLT** | **Preis** | - | Kein Preis in der Produktkarte sichtbar! |
| **FEHLT** | **Short Description** | - | Keine Kurzbeschreibung im Listing! |

## 3. Produktdetailseite - IST-Zustand

| Position | Element | Beschreibung |
|----------|---------|--------------|
| 1 | **Breadcrumb** | `nav#breadcrumb` - Navigationspfad |
| 2 | **Produktbild** | EasyZoom Overlay mit Thumbnails. Klasse: `.easyzoom--overlay` |
| 3 | **Hersteller-Logo** | Rechts neben dem Bild, `.manufacturer_images w-25` |
| 4 | **Preis** | `.pd_price .standard_price .lead` - "ab 9,99 EUR" |
| 5 | **Produktdaten-Tabelle 1** | `.bg-custom .teabls .table-striped` - Hersteller, Typ, etc. |
| 6 | **Produktdaten-Tabelle 2** | Zweite Tabelle mit weiteren Spezifikationen |
| 7 | **Lager-Ampel** | `.stock-indicator` - Grün/Gelb/Rot Kreise mit Info-Modal |
| 8 | **Produktoptionen** | `#optionen{ID} .product-options-container` - Samen-Auswahl (1, 3, 5, 10 Samen) mit Preis und Verfügbarkeit pro Option |
| 9 | **Warenkorb-Button** | `.btn-cart` |
| 10 | **Wunschliste** | `.btn-wish` |
| 11 | **Vergleich** | `.btn-compare` |
| 12 | **Shariff Social** | Social Sharing Buttons |
| 13 | **Tabs/Accordion** | Produktbeschreibung, Bewertungen, etc. |

## 4. Kategorie-Listing - IST-Zustand

| Position | Element | Beschreibung |
|----------|---------|--------------|
| 1 | **Breadcrumb** | Navigationspfad |
| 2 | **Trust Badges** | "7500+ Sorten", "90%+ Keimrate", "20+ Jahre", "Check-Circle". Klasse: `.badge .badge-secondary .badge-pill` |
| 3 | **Unterkategorien** | Karten mit Kategorie-Bildern und -Namen als Grid |
| 4 | **Sidebar** | Boxen: Hersteller-Dropdown, Kategorien-Menü, Login, Bewertungen, Blog |
| 5 | **Produktliste** | Produktkarten im Grid (wie oben beschrieben) |

## 5. Identifizierte Schwächen (IST-Zustand)

| Problem | Auswirkung | Priorität |
|---------|-----------|-----------|
| **Kein Preis im Listing** | Nutzer muss auf jedes Produkt klicken um den Preis zu sehen → hohe Absprungrate | KRITISCH |
| **Keine Short Description** | Keine Produktinformation im Listing → schlechte Kaufentscheidung | HOCH |
| **Lazy Loading per JS** | lazysizes jQuery Plugin statt natives loading="lazy" → unnötiges JS, langsamer | HOCH |
| **jQuery-Abhängigkeit** | Gesamtes Template hängt an jQuery → Performance-Killer | HOCH |
| **EasyZoom jQuery** | Produktbild-Zoom per jQuery Plugin → kann nativ gelöst werden | MITTEL |
| **Pushy Navigation** | Externes JS für Mobile-Menü statt BS5 Offcanvas | MITTEL |
| **Bootstrap 4 data-Attribute** | data-toggle, data-dismiss statt data-bs-* → veraltet | MITTEL |
| **Altersverifikation per jQuery** | Modal mit jQuery → kann mit BS5 nativ gelöst werden | NIEDRIG |
| **Logo 300x171px** | Großes Logo-Bild ohne responsive srcset → CLS-Risiko | MITTEL |
| **Keine WebP/AVIF Bilder** | Nur PNG Produktbilder → unnötig große Dateien | HOCH |
