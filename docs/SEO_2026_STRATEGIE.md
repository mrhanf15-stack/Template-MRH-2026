# MRH 2026 – SEO & GEO Strategie (Schema.org, AI-Suchmaschinen, Speed)

## 1. Schema.org Strukturierte Daten – Vollständige Implementierung

### 1.1 Pflicht-Schemas pro Seitentyp

| Seitentyp | Schema-Typen | Zweck |
|---|---|---|
| **Alle Seiten** | `WebSite`, `Organization`, `BreadcrumbList` | Basis-Identität, Navigation |
| **Startseite** | `WebSite` mit `SearchAction`, `Organization` mit `ContactPoint` | Sitelinks-Suchbox, Firmendaten |
| **Kategorie-Listing** | `ItemList`, `CollectionPage`, `BreadcrumbList` | Produkt-Carousels in SERPs |
| **Produktdetail** | `Product`, `Offer`, `AggregateRating`, `Review`, `Brand` | Rich Results: Preis, Sterne, Verfügbarkeit |
| **Blog/Ratgeber** | `Article`, `FAQPage`, `HowTo`, `Author` | Featured Snippets, AI-Zitate |
| **Kontakt/Impressum** | `Organization`, `LocalBusiness`, `ContactPoint` | Knowledge Panel |

### 1.2 Product Schema – Erweiterte Properties für 2026

Jede Produktseite erhält ein vollständiges JSON-LD `Product`-Schema mit folgenden Properties:

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Amnesia Haze Feminisiert",
  "description": "Amnesia Haze feminisierte Samen...",
  "image": ["url1.webp", "url2.webp"],
  "brand": { "@type": "Brand", "name": "Royal Queen Seeds" },
  "sku": "MRH-AH-FEM-5",
  "gtin13": "1234567890123",
  "category": "Cannabis Samen > Feminisierte Samen > THC-reiche Sorten",
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "9.99",
    "highPrice": "49.99",
    "priceCurrency": "EUR",
    "availability": "https://schema.org/InStock",
    "seller": { "@type": "Organization", "name": "Mr. Hanf" },
    "shippingDetails": { "@type": "OfferShippingDetails", ... },
    "hasMerchantReturnPolicy": { "@type": "MerchantReturnPolicy", ... }
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "reviewCount": "127"
  },
  "review": [ ... ],
  "additionalProperty": [
    { "@type": "PropertyValue", "name": "THC-Gehalt", "value": "22%" },
    { "@type": "PropertyValue", "name": "Blütezeit", "value": "8-9 Wochen" },
    { "@type": "PropertyValue", "name": "Sorte", "value": "Sativa 70% / Indica 30%" }
  ]
}
```

### 1.3 Organization Schema – Knowledge Panel

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Mr. Hanf",
  "url": "https://mr-hanf.de",
  "logo": "https://mr-hanf.de/logo.webp",
  "description": "Europas führender Online-Shop für Cannabis Samen seit über 20 Jahren",
  "foundingDate": "2004",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+43-...",
    "contactType": "customer service",
    "availableLanguage": ["German", "English"]
  },
  "sameAs": [
    "https://www.instagram.com/mrhanf/",
    "https://www.facebook.com/mrhanf/"
  ]
}
```

### 1.4 WebSite Schema mit SearchAction (Sitelinks-Suchbox)

```json
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Mr. Hanf",
  "url": "https://mr-hanf.de",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://mr-hanf.de/advanced_search_result.php?keywords={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
```

## 2. GEO – Generative Engine Optimization (AI-Suchmaschinen)

### 2.1 Warum GEO 2026 entscheidend ist

Laut Gartner sinkt das traditionelle Suchvolumen 2026 um 25%, da Nutzer zu AI-Suchmaschinen wechseln. Google AI Overviews hat über 2 Milliarden monatliche Nutzer, ChatGPT bedient 800 Millionen und Perplexity verarbeitet hunderte Millionen Anfragen monatlich. Für mr-hanf.de bedeutet das: Das Template muss so gebaut sein, dass AI-Engines den Content optimal parsen, zitieren und empfehlen können.

### 2.2 Template-seitige GEO-Maßnahmen

**Passage-Optimierung:** AI-Engines zerlegen Seiten in einzelne Passagen und bewerten jede für sich. Das Template muss daher eine klare Heading-Hierarchie (H1 > H2 > H3) erzwingen und jede Sektion mit einer direkten, klaren Antwort beginnen lassen.

**FAQ-Sektionen:** AI-Engines nutzen Frage-Antwort-Paare intensiv. Das Template integriert auf Produktdetailseiten und Kategorieseiten automatisch eine FAQ-Sektion mit `FAQPage`-Schema, die aus den Produkteigenschaften (Short Description Tabelle) generiert wird.

**TL;DR-Statements:** Unter wichtigen Überschriften zeigt das Template kurze Zusammenfassungen an, die als eigenständige Antworten für AI-Engines dienen können.

**Entity-Signale:** Konsistente Markennennung ("Mr. Hanf") über alle Seiten, klare Author-/About-Seiten und strukturierte Firmendaten stärken die Entity-Erkennung durch AI-Engines.

### 2.3 Technische GEO-Maßnahmen

**robots.txt:** AI-Crawler (GPTBot, ClaudeBot, PerplexityBot) dürfen nicht blockiert werden. Das Template liefert eine optimierte robots.txt-Vorlage mit.

**llms.txt:** Eine neue `llms.txt`-Datei im Root-Verzeichnis des Shops gibt AI-Systemen Hinweise zur Interpretation der Website-Struktur.

**Structured Data für AI:** Über die Standard-Schema.org-Implementierung hinaus werden `additionalProperty`-Felder für produktspezifische Daten (THC, Blütezeit, Sorte) genutzt, damit AI-Engines diese als eigenständige Fakten extrahieren können.

## 3. Core Web Vitals 2026 – Speed-Strategie

### 3.1 Zielwerte

| Metrik | Zielwert | Maßnahme |
|---|---|---|
| **LCP** (Largest Contentful Paint) | < 2,5s | Critical CSS inline, Bilder via WebP-Modul, Preload Hero |
| **INP** (Interaction to Next Paint) | < 200ms | Kein jQuery, Vanilla JS, Event Delegation |
| **CLS** (Cumulative Layout Shift) | < 0,1 | Feste Bild-Dimensionen, Font-Display: swap |

### 3.2 CSS-Strategie

Das Template nutzt eine modulare CSS-Architektur: Critical CSS (Above-the-fold) wird inline im `<head>` geladen, der Rest asynchron via `<link rel="preload" as="style">`. Bootstrap 5.3 wird nur mit den tatsächlich genutzten Modulen kompiliert (kein volles Bootstrap-Bundle).

### 3.3 JavaScript-Strategie

Kein jQuery. Alle Interaktionen laufen über natives JavaScript und die Bootstrap 5.3 Vanilla JS API. Scripts werden mit `defer` oder `type="module"` geladen. Drittanbieter-Scripts (Shariff, Analytics) werden lazy geladen.

### 3.4 Bild-Strategie

Natives `loading="lazy"` für alle Bilder unterhalb des Viewports. Hero-Bilder werden mit `fetchpriority="high"` und `<link rel="preload">` priorisiert. Das WebP-Modul des Shops liefert optimierte Formate. Das Template gibt `width` und `height` Attribute auf allen `<img>`-Tags aus, um CLS zu vermeiden.

### 3.5 Font-Strategie

System-Font-Stack als Fallback, Web-Fonts via `font-display: swap` und `<link rel="preload" as="font">`. Maximal 2 Font-Familien, um HTTP-Requests zu minimieren.

## 4. Boxen-Verteilung (Option A – Keine Sidebar)

### 4.1 Verteilungsplan

| Box (alt) | Neue Position | Begründung |
|---|---|---|
| `box_CATEGORIES` | Mega-Menu in Hauptnavigation | Immer sichtbar, Mobile via Offcanvas |
| `box_MANUFACTURERS` | Mega-Menu unter "Marken" + eigene Seite | Bessere Auffindbarkeit |
| `box_SHIPPING_COUNTRY` | Header (Flaggen-Dropdown) | Sofort zugänglich |
| `box_SPECIALS` | Startseite: Horizontales Carousel "Angebote" | Volle Breite, mehr Sichtbarkeit |
| `box_WHATSNEW` | Startseite: Horizontales Carousel "Neuheiten" | Volle Breite, mehr Sichtbarkeit |
| `box_BESTSELLERS` | Startseite: Horizontales Carousel "Bestseller" | Volle Breite, mehr Sichtbarkeit |
| `box_LAST_VIEWED` | Produktdetail: Horizontaler Streifen unten | Kontextuell relevant |
| `box_NEWSLETTER` | Eigene Sektion (RevPlus-Stil) + Footer | Prominent, mit Incentive |
| `box_LOGIN` | Header-Icon (Konto-Dropdown/Offcanvas) | Standard E-Commerce UX |
| `box_ADD_QUICKIE` | Warenkorb-Seite | Kontextuell sinnvoller |
| `box_GIFTCODE` | Warenkorb/Checkout | Wo er gebraucht wird |
| `box_INFORMATION` | Footer (4-Spalten) | Bleibt wie bisher |
| `box_CONTENT` | Footer (4-Spalten) | Bleibt wie bisher |
| `box_MISCELLANEOUS` | Footer (4-Spalten) | Bleibt wie bisher |
| `box_REVIEWS` | Produktdetail: Review-Sektion | Kontextuell relevant |
| `box_shopvoting` | Footer: Trust-Badge-Leiste | Sichtbar auf allen Seiten |
| `box_BLOG_*` | Footer oder eigene Blog-Sektion | SEO-relevant |
| `box_CUSTOM` | Flexibel via contentAnywhere | Wie bisher |
| `BANNERLINKS` | Startseite: Banner-Sektion | Volle Breite |

### 4.2 Newsletter-Box (RevPlus-Stil, erweitert)

Die Newsletter-Box wird als eigene Fullwidth-Sektion zwischen Produktlisten und Footer platziert. Das Design orientiert sich am RevPlus Template, wird aber modernisiert:

**Aufbau:** Dunkler oder grüner Gradient-Hintergrund, zentrierter Text mit Headline ("Verpasse keine Angebote!"), Subline ("Melde dich an und erhalte exklusive Deals"), E-Mail-Eingabefeld mit CTA-Button ("Jetzt anmelden"), DSGVO-Checkbox. Optional: Incentive-Badge ("10% Rabatt auf deine erste Bestellung").

**Technisch:** Das Formular nutzt die bestehende modified-Newsletter-Funktionalität (`newsletter.php`). Die Box wird als Smarty-Include realisiert und kann auf jeder Seite eingebunden werden.

## 5. llms.txt – AI-Crawler-Anweisungen

```
# Mr. Hanf - llms.txt
# Europas führender Online-Shop für Cannabis Samen

> Mr. Hanf ist ein seit über 20 Jahren etablierter Online-Shop für Cannabis Samen
> mit Sitz in Österreich. Das Sortiment umfasst feminisierte, autoflowering und
> reguläre Samen von über 100 Herstellern.

## Hauptbereiche
- Samen Shop: Feminisierte, Autoflowering, Reguläre, CBD-reiche Samen
- Seedfinder: Interaktives Tool zur Sortensuche nach Eigenschaften
- Blog: Anbauanleitungen, Sortenbewertungen, Branchennews
- Hersteller: Über 100 Samenbanken (Royal Queen Seeds, Dutch Passion, etc.)

## Kontakt
- Website: https://mr-hanf.de
- Kundenservice: Montag-Freitag, 9-17 Uhr
```
