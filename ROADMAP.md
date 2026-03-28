# ROADMAP - Template MRH 2026

## Changelog

### 2026-03-28 - Projektinitialisierung & Analyse

**Durchgeführt:**
- Repository "Template-MRH-2026" erstellt (privat)
- Live-Template (bootstrap4) entpackt und archiviert
- Vollständige Template-Analyse durchgeführt (1490 Dateien)
- Alle Custom-Module und Erweiterungen identifiziert
- contentAnywhere-Referenzen (11 coIDs) dokumentiert
- Bootstrap 5.3 Migration Guide analysiert
- Breaking Changes BS4 → BS5 dokumentiert
- SEO 2026 Anforderungen recherchiert

**Erstellt:**
- `README.md` - Projektübersicht
- `ROADMAP.md` - Dieses Dokument
- `docs/ANALYSE_ERGEBNISSE.md` - Detaillierte Analyse
- `archive/bootstrap4-original/` - Komplettes Original-Template

**Erkenntnisse:**
- 9+ Custom-Module müssen migriert werden
- 11+ contentAnywhere-Blöcke sind im Template referenziert
- jQuery-Abhängigkeit muss komplett entfernt werden
- Pushy Navigation → BS5 Offcanvas
- EasyZoom → Native CSS/JS Lösung
- 4 Produktinfo-Varianten + 3 Listing-Varianten + 4 Options-Varianten
- Seedfinder ist das komplexeste Custom-Modul (7 JS-Dateien, eigenes CSS)

**Nächste Schritte:**
- Klärung offener Fragen mit dem Auftraggeber
- Design-Entscheidungen treffen
- Entwicklungsumgebung aufsetzen
- Phase 1: Grundgerüst (index.html, config.php, CSS-Architektur)

---

## Phasen-Übersicht

### Phase 0: Analyse & Planung (Aktuell)
- [x] Template-Analyse
- [x] Modul-Inventar
- [x] Repository-Setup
- [ ] Offene Fragen klären
- [ ] Design-Konzept festlegen

### Phase 1: Grundgerüst
- [ ] Bootstrap 5.3 einbinden
- [ ] index.html (Smarty) neu aufbauen
- [ ] config.php migrieren (BS4_ → MRH_ Konstanten)
- [ ] CSS-Architektur (SCSS/CSS Custom Properties)
- [ ] Mobile-First Grid-Layout
- [ ] Offcanvas-Navigation (ersetzt Pushy)
- [ ] Header/Logobar
- [ ] Footer

### Phase 2: Kern-Seiten
- [ ] Startseite
- [ ] Kategorie-Listing
- [ ] Produktdetailseite(n)
- [ ] Warenkorb
- [ ] Checkout-Prozess
- [ ] Login/Registrierung
- [ ] Content-Seiten

### Phase 3: Module migrieren
- [ ] Seedfinder
- [ ] Reklamation
- [ ] Blog-System
- [ ] Free Shipping Bar
- [ ] Gift Cart
- [ ] Newsletter Overlay
- [ ] Product Compare
- [ ] Advanced Contact
- [ ] FAQ Manager

### Phase 4: SEO & Performance
- [ ] Schema.org Structured Data (Product, Organization, BreadcrumbList, FAQ, Review)
- [ ] Open Graph / Twitter Cards
- [ ] Core Web Vitals Optimierung
- [ ] Critical CSS / Above-the-fold
- [ ] Lazy Loading (native)
- [ ] Image Optimization (WebP/AVIF)
- [ ] JS Bundle Minimierung

### Phase 5: Testing & Go-Live
- [ ] Cross-Browser Testing
- [ ] Mobile Testing (iOS/Android)
- [ ] Lighthouse Audit
- [ ] PageSpeed Insights
- [ ] Schema Markup Validierung
- [ ] Staging-Deployment
- [ ] Go-Live

---

## Offene Fragen

> Siehe Abschnitt "Fragen an den Auftraggeber" in der aktuellen Session-Dokumentation.
