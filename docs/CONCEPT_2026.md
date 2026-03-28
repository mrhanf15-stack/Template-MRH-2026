# Navigations- und Speed-Konzept 2026

## 1. Einleitung

Dieses Dokument beschreibt die Architektur der neuen Navigation und die Strategien zur Geschwindigkeitsoptimierung für das Bootstrap 5.3 Template von mr-hanf.de. Ziel ist es, ein erstklassiges Benutzererlebnis zu schaffen, das den "Mobile First"-Ansatz konsequent umsetzt und die Google Core Web Vitals (LCP, INP, CLS) für das Jahr 2026 souverän erfüllt.

## 2. Navigations-Architektur 2026

Die Navigation wird grundlegend neu strukturiert, um die Auffindbarkeit von Produkten zu maximieren und die Conversion-Rate zu steigern. Wir unterscheiden dabei strikt zwischen der Desktop- und der mobilen Ansicht, da sich die Nutzungsgewohnheiten hier stark unterscheiden.

### 2.1 Desktop-Navigation

Für Desktop-Nutzer setzen wir auf ein horizontales Mega-Menu. Dies ermöglicht es, tiefere Kategoriestrukturen auf einen Blick zu erfassen, ohne dass der Nutzer mehrfach klicken muss.

| Komponente | Beschreibung |
|------------|--------------|
| **Top-Bar** | Schmale Leiste am oberen Rand für sekundäre Informationen (Sprache, Währung, USP-Banner wie "Kostenloser Versand ab 50€"). |
| **Header-Main** | Beinhaltet das Logo (links), eine prominente, breite Suchleiste mit Auto-Suggest-Funktion (Mitte) und die User-Aktionen (Account, Wunschzettel, Warenkorb-Preview) auf der rechten Seite. |
| **Kategorie-Leiste** | Horizontale Leiste mit maximal 5 bis 7 Hauptkategorien. Ein Hover über diese Kategorien öffnet ein Mega-Menu, das Unterkategorien strukturiert darstellt und Platz für kleine Teaser-Bilder oder Top-Produkte bietet. |
| **Sticky Header** | Beim Scrollen nach unten wird die Navigation zu einem kompakten Sticky-Header reduziert. Dieser enthält nur noch Logo, Such-Icon, Warenkorb und die Hauptkategorien, um maximalen Platz für den Content zu lassen. |

### 2.2 Mobile-Navigation (Mobile First)

Da mobile Endgeräte den Großteil des Traffics ausmachen, wird die mobile Navigation als eigenständiges, hochoptimiertes System entwickelt. Das bisherige "Pushy"-Script wird durch die native Bootstrap 5 Offcanvas-Komponente ersetzt, was die Performance deutlich verbessert.

| Komponente | Beschreibung |
|------------|--------------|
| **Mobile Header** | Kompakt gehalten: Hamburger-Icon (links), Logo (Mitte), Such-Icon und Warenkorb (rechts). |
| **Offcanvas Menu** | Ein Wisch von links oder Klick auf das Hamburger-Icon öffnet das Menü. Es nutzt Akkordeons für Unterkategorien, um die Ansicht übersichtlich zu halten. Große Touch-Targets (mindestens 44x44 Pixel) garantieren eine fehlerfreie Bedienung. |
| **Sticky Bottom Bar** | Eine optionale, am unteren Bildschirmrand fixierte Leiste (ähnlich wie bei nativen Apps) bietet Schnellzugriff auf Home, Suche, Wunschzettel und Warenkorb. Dies entspricht modernsten UX-Standards für E-Commerce im Jahr 2026. |

## 3. Speed-Strategie (Core Web Vitals Optimierung)

Um die strengen Vorgaben von Google für das Jahr 2026 zu erfüllen, wird das Template von Grund auf auf Performance getrimmt. Die Beseitigung der jQuery-Abhängigkeit durch Bootstrap 5 ist hierfür der erste, entscheidende Schritt.

### 3.1 Optimierung des Largest Contentful Paint (LCP)

Der LCP misst die Ladezeit des größten sichtbaren Elements im Viewport. Unser Ziel ist ein Wert von unter 2,5 Sekunden.

Wir erreichen dies, indem das Hero-Bild oder das wichtigste Produktbild im sichtbaren Bereich niemals per Lazy-Loading verzögert wird. Stattdessen wird dieses Bild mit einem `<link rel="preload">`-Tag im Header priorisiert geladen. Zudem setzen wir auf moderne Bildformate wie WebP oder AVIF und nutzen das `srcset`-Attribut, um für jede Bildschirmgröße das exakt passend skalierte Bild auszuliefern. Die Server-Antwortzeit (TTFB) wird durch effizientes Smarty-Caching unterstützt.

### 3.2 Optimierung des Cumulative Layout Shift (CLS)

Der CLS misst die visuelle Stabilität der Seite. Ein Wert unter 0,1 ist zwingend erforderlich, um Abstrafungen im Ranking zu vermeiden.

Um das Springen von Inhalten während des Ladevorgangs zu verhindern, erhalten alle Bilder, Videos und Werbebanner strikte Breiten- und Höhenangaben (`width` und `height` Attribute im HTML). Dadurch reserviert der Browser den benötigten Platz, bevor das Bild vollständig geladen ist. Für Webfonts nutzen wir `font-display: swap` in Kombination mit passend gewählten System-Fallback-Schriftarten, um den "Flash of Unstyled Text" (FOUT) und damit einhergehende Layout-Verschiebungen zu minimieren.

### 3.3 Optimierung der Interaction to Next Paint (INP)

Die INP-Metrik hat den alten First Input Delay (FID) abgelöst und misst die Reaktionsfähigkeit der gesamten Seite auf Benutzereingaben. Ein Wert unter 200 Millisekunden ist das Ziel.

Hier spielt der Wechsel auf Vanilla JavaScript seine größte Stärke aus. Wir verzichten auf monolithische JavaScript-Dateien und laden stattdessen nur die Skripte, die für die aktuelle Seite benötigt werden (Code Splitting). Schwere Berechnungen oder DOM-Manipulationen werden in kleine Tasks unter 50 Millisekunden aufgeteilt, um den Main Thread nicht zu blockieren. Die Größe des Document Object Models (DOM) wird durch ein sauberes, flaches HTML-Gerüst klein gehalten (maximal 1500 Nodes), was die Rendering-Geschwindigkeit des Browsers drastisch erhöht.

### 3.4 Critical CSS und Render-Blocking

Ein wesentlicher Performance-Faktor ist der Umgang mit CSS. Anstatt eine riesige CSS-Datei zu laden, die das Rendern der Seite blockiert, extrahieren wir das "Critical CSS" – also die Stile, die für den sofort sichtbaren Bereich (Above-the-Fold) benötigt werden. Dieses Critical CSS wird direkt inline in den `<head>` der HTML-Dokumente geschrieben. Alle weiteren CSS-Dateien werden asynchron nachgeladen. Dies führt zu einem extrem schnellen ersten Bildschirmaufbau.
