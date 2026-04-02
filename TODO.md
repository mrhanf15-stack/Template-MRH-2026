# MRH 2026 Template – TODO

> **Projektstand: 2. April 2026**
> Repository: `mrhanf15-stack/Template-MRH-2026`

---

## Legende

- [x] Erledigt
- [ ] Offen
- [!] Blockiert / Warten auf Entscheidung

---

## 1. Foundation & Grundgerüst

- [x] Projektinitialisierung und Template-Analyse
- [x] Technische Speed-Strategie, CSS/JS-Architektur
- [x] MRH 2026 Template v1.0 – Komplettes Template für Testshop
- [x] Alle 129 Frontend-Module in BS5.3 komplett neu geschrieben
- [x] Schema.org JSON-LD (Product, Brand, FAQ, Blog, ItemList, Organization)
- [x] 4-Sprachen-System komplett (DE, EN, FR, ES)
- [x] Modified 3 Kompatibilität: Varianten-Verzeichnisse, BS5 Migration, Smarty 5 ready
- [x] Critical Fix: Direct Access Fehler behoben
- [x] Modified eCommerce Pflichtdateien ergänzt
- [x] OPCache Reset Script (Token-geschützt)
- [x] FA6 Pro Kit, CSS/JS Foundation, Fonts

---

## 2. Header – Desktop

- [x] Jubiläums-Logo (10 Jahre), WebP-Format
- [x] Icons auf Font Awesome 6 Pro umgestellt
- [x] Pill-Shape Suchleiste (border-radius 999px, grüner Such-Button)
- [x] Placeholder "Cannabis Samen suchen..." per JS (mehrsprachig DE/EN/FR/NL)
- [x] Icon-Abstände vergrößert (gap 16px), einheitliche Zentrierung
- [x] Logo-Größe auf 100px (Sticky: 52px)
- [x] Such-Icon zentriert (flex statt absolute)
- [x] Weißer Spalt am Such-Button eliminiert
- [x] Hamburger im Sticky Header auf Desktop sichtbar
- [x] Beschriftungen unter Icons (Einstellungen, Konto, Merkzettel, Warenkorb)
- [x] Hover-Effekt auf Icons (Farbe → grün)

---

## 3. Header – Mobile

- [x] Schlanker Header: Logo + Hamburger + Einstellungen + Konto
- [x] Merkzettel & Warenkorb aus Mobile-Header entfernt (in Bottom Bar)
- [x] Beschriftungen auf Mobile ausgeblendet (nur Icons)
- [x] Suchleiste als eigene Zeile (volle Breite)
- [x] HTML umstrukturiert: 2 Rows (mrh-header-row-1 + mrh-header-row-2)

---

## 4. Mobile Bottom Bar

- [x] 5 Items: Home, Suche, Seedfinder, Merkliste, Warenkorb
- [x] Fixiert am unteren Bildschirmrand
- [x] Seedfinder als hervorgehobenes mittleres Icon (grüner Kreis)
- [x] Suche öffnet Slide-Up Overlay (nicht Scroll nach oben)
- [x] Merkzettel-Badge: Live-Synchronisation via MutationObserver
- [x] Warenkorb-Badge: Live-Synchronisation via MutationObserver
- [x] iPhone Safe Area Support
- [x] Backdrop-Blur Effekt
- [ ] Bottom Bar Beschriftungen auf sehr kleinen Displays prüfen (< 360px)

---

## 5. Sticky Header

- [x] Kompakter Header beim Scrollen (Logo 52px)
- [x] Hamburger-Menü auf Desktop sichtbar im Sticky
- [x] Navigation-Row im Sticky ausgeblendet
- [x] Sticky auf Mobile beibehalten (Entscheidung 31.03.)

---

## 6. Navigation / Mega-Menü ← AKTUELL IN ARBEIT

### 6a. Navigation Bar (Desktop)
- [ ] Grüner Hintergrund (`var(--mrh-primary)`)
- [ ] Weiße Schrift für Menüpunkte
- [ ] Icons vor Menüpunkten (fa-seedling, fa-cannabis, fa-tags, etc.)
- [ ] Menüpunkte laut Wireframe: Samen Shop, Cannabispflanzen, Growshop, Headshop, Angebote, Neue Artikel, Marken, Blog, Seedfinder

### 6b. Mega-Menü Dropdown (bei Hover auf "Samen Shop")
- [ ] 4-Spalten Layout
- [ ] Spalte 1: "Sortenvielfalt" (THC-Reiche, CBD-Reiche, Autoflowering, Feminisierte, Reguläre)
- [ ] Spalte 2: "Beliebte Sorten" (Amnesia Haze, White Widow, Northern Lights, Girl Scout Cookies, Gorilla Glue)
- [ ] Spalte 3: "Top Hersteller" (Royal Queen Seeds, Barney's Farm, Dutch Passion, Sensi Seeds, Fast Buds)
- [ ] Spalte 4: "Aktion" – dynamisch aus Sprachdatei (Hersteller + Aktionstext + "Jetzt sparen" Button)
- [ ] Spalten-Überschriften mit Icons und grüner Farbe
- [ ] Weißer Hintergrund mit leichtem Schatten
- [ ] Hover-Effekte auf Links

### 6c. Aktions-Spalte (Sprachdatei-Variante)
- [ ] Sprachvariablen in lang_german.custom: mega_promo_title, mega_promo_brand, mega_promo_text, mega_promo_link
- [ ] Sprachvariablen in lang_english.custom
- [ ] HTML-Block in navigation.html für Aktions-Spalte
- [!] **Upgrade-Option:** PHP-Modul für automatische Aktions-Daten aus DB (Zukunft)

### 6d. Mobile Menü (Offcanvas)
- [ ] Offcanvas-Menü Styling anpassen
- [ ] Mega-Menü Inhalte im Offcanvas darstellen

---

## 7. Produktkarten / Listing

- [ ] Produktkarten Redesign (Box-Ansicht)
- [ ] Produktkarten Redesign (Listen-Ansicht)
- [ ] Preis-Darstellung modernisieren
- [ ] "In den Warenkorb" Button Styling
- [ ] Rabatt-Badge Styling
- [ ] Hover-Effekte auf Produktkarten
- [ ] Responsive Grid (2 Spalten Mobile, 3-4 Desktop)

---

## 8. Produktdetail-Seite

- [ ] Bildergalerie modernisieren
- [ ] Preis-Box Redesign
- [ ] Varianten-Auswahl Styling
- [ ] Tab-Navigation (Beschreibung, Bewertungen, etc.)
- [ ] Cross-Selling / Ähnliche Produkte
- [ ] Breadcrumb Styling

---

## 9. Kategorie-Seiten

- [ ] Kategorie-Header / Banner
- [ ] Filter / Sortierung Styling
- [ ] Seitenleiste (Vertikal-Menü) Styling
- [ ] Pagination Styling

---

## 10. Warenkorb & Checkout

- [ ] Warenkorb-Seite Redesign
- [ ] Checkout-Schritte Styling
- [ ] Formular-Styling (Inputs, Selects, Buttons)
- [ ] Bestellübersicht

---

## 11. Footer

- [ ] Footer Redesign nach Wireframe
- [ ] Newsletter-Box Styling
- [ ] Footer-Navigation
- [ ] Zahlungsmethoden Icons
- [ ] Social Media Links
- [ ] Kontakt-Informationen
- [ ] Copyright-Zeile

---

## 12. Allgemein / Übergreifend

- [ ] Topbar Styling (Versandkosten-Info)
- [ ] Shipping Bar Styling
- [ ] Toast / Benachrichtigungen Styling
- [ ] Cookie-Banner Styling
- [ ] 404-Seite Styling
- [ ] Ladeanimationen / Skeleton Screens
- [ ] Print-Stylesheet

---

## 13. Performance & Qualität

- [ ] CSS Minification (stylesheet.min.css)
- [ ] JS Minification (javascript.min.js)
- [ ] Bilder-Optimierung (WebP, Lazy Loading)
- [ ] Core Web Vitals prüfen (LCP, CLS, FID)
- [ ] Cross-Browser Testing (Chrome, Firefox, Safari, Edge)
- [ ] Accessibility Audit (WCAG 2.1 AA)
- [ ] SEO-Check (Meta-Tags, Structured Data)

---

## 14. Sprachdateien & i18n

- [x] Deutsch (lang_german.custom) – Basis
- [x] Englisch (lang_english.custom) – Basis
- [ ] Französisch (lang_french.custom) – Bottom Bar + Mega-Menü Variablen
- [ ] Spanisch (lang_spanish.custom) – Bottom Bar + Mega-Menü Variablen
- [ ] Niederländisch – Prüfen ob benötigt

---

## 15. Live-Template Sync (2. April 2026)

- [x] Live-Template ZIP heruntergeladen und analysiert (2.088 Dateien)
- [x] Datei-für-Datei Vergleich mit Repo (1.331 gemeinsame, 757 nur Live, 119 nur Repo)
- [x] 212 kritische Dateien synchronisiert (tpl_parts, config, boxes, module, webfonts, fonts, unitegallery, admin, smarty)
- [x] Smarty-Variablen-Kompatibilität geprüft (product_info_v1.html identisch)
- [x] Versionsvergleich dokumentiert (BS 5.2.3→5.3.0, jQuery 3.6.0→3.7.1)
- [x] LIVE_SYNC_REPORT.md erstellt
- [x] Änderungen auf GitHub gepusht
- [ ] Bootstrap 5.2.3 → 5.3.0 auf Live deployen
- [ ] jQuery 3.6.0 → 3.7.1 auf Live deployen
- [ ] Mobile Navigation (navigation_mobile.html + menu_offcanvas.html) auf Live deployen
- [ ] index.html mit box_NEWSLETTER und Mobile-Nav-Includes auf Live deployen
- [ ] tpl_mrh_2026_live.zip vom Webroot entfernen

---

## 16. Dokumentation

- [x] CHANGELOG.md erstellt
- [x] TODO.md erstellt
- [x] LIVE_SYNC_REPORT.md erstellt
- [ ] README.md für Template-Installation
- [ ] Deploy-Anleitung für Server-Team
- [ ] CSS-Architektur Dokumentation (Sektionen-Übersicht)

---

## Notizen

**Entscheidungen:**
- Sticky Header auf Mobile beibehalten (31.03.2026)
- Aktions-Spalte im Mega-Menü: Sprachdatei-Variante (manuell pflegbar)
- PHP-Modul für automatische Aktions-Daten als späteres Upgrade geplant

**Bekannte Probleme:**
- `404: Not Found` erscheint oben auf der Seite (favicon.ico oder anderes Asset fehlt)
- Trusted Shops Badge überlappt Bottom Bar auf Mobile (z-index prüfen)

**Live-Template Sync (2. April 2026):**
- Live nutzt `inserttags` Modifier in index.html, Repo nicht (beabsichtigt)
- Live nutzt `contentAnywhere` (ID 1003206) für Newsletter, Repo nutzt `box_NEWSLETTER`
- Live hat Bootstrap 5.2.3, Repo hat 5.3.0 (Repo ist neuer)
- Live hat jQuery 3.6.0, Repo hat 3.7.1 (Repo ist neuer)
- 545 Dateien bewusst nicht synchronisiert (Bilder, alte CSS/JS, Backups)
