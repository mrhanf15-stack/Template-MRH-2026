# Speed & Navigation Research 2026

## Core Web Vitals Schwellenwerte 2026

| Metrik | Gut | Verbesserungswürdig | Schlecht |
|--------|-----|---------------------|----------|
| **LCP** (Largest Contentful Paint) | < 2.5s | 2.5s - 4.0s | > 4.0s |
| **INP** (Interaction to Next Paint) | < 200ms | 200ms - 500ms | > 500ms |
| **CLS** (Cumulative Layout Shift) | < 0.1 | 0.1 - 0.25 | > 0.25 |

## Speed-Optimierungstechniken

### LCP Optimierung
- Server Response Time verbessern (TTFB)
- Render-blocking Resources eliminieren
- Critical CSS inline im <head>
- Hero/LCP-Bild sofort laden (kein lazy loading!)
- Preload für kritische Ressourcen: `<link rel="preload">`
- Moderne Bildformate: WebP/AVIF mit srcset
- CDN verwenden

### CLS Optimierung
- Explizite width/height für alle Bilder und Videos
- Platzhalter für Ads/Embeds reservieren
- font-display: swap + Fallback-Fonts die ähnlich sind
- Kein dynamisch injizierter Content über bestehendem Content
- CSS transform statt height/width Animationen

### INP Optimierung
- Lange Tasks aufbrechen (< 50ms pro Task)
- JavaScript in kleinere Chunks splitten
- Debouncing/Throttling für häufige Events
- Passive Event Listeners verwenden
- DOM-Größe minimieren (< 1500 Nodes, Tiefe < 32, < 60 Kinder)
- Web Workers für schwere Berechnungen

## Navigation Best Practices 2026

### Struktur
- 5-7 primäre Kategorien maximal
- Flache Hierarchie (max 3 Ebenen)
- Mega-Menu für große Kataloge
- Prominente Suchleiste oben
- Sticky Navigation (kompakt, nicht zu viel Platz)

### Mobile (80% des Traffics!)
- Hamburger-Menu → Offcanvas
- Große Tap-Targets (min 44x44px)
- Weniger Taps zum Produkt (max 3)
- Sticky kompakte Navigation
- Search + Categories kombinieren

### Desktop
- Horizontale Hauptnavigation
- Mega-Menu mit Kategorien + Bildern
- Hover-Effekte für Subcategorien
- Cart-Preview on Hover
- Account-Dropdown

### 2026 UX Trends
- AI-powered Search
- Personalisierte Navigation
- Voice Search Integration
- Breadcrumbs als SEO-Boost
- Sticky Mini-Cart
- Bottom Navigation Bar (Mobile)
