# Template MRH 2026

**Modernes Bootstrap 5.3 Template für mr-hanf.de**

## Projektübersicht

| Eigenschaft | Wert |
|-------------|------|
| **Shop-System** | modified eCommerce Shopsoftware v2.0.7.2 rev 14622 |
| **Datenbank** | MOD_2.0.7.2 |
| **PHP-Version** | 8.3 |
| **Basis-Template** | KarlBogen/bootstrap4 (BS 4.6.1) |
| **Ziel-Framework** | Bootstrap 5.3.x |
| **Smarty-Engine** | smarty_4 |
| **Status** | Analyse-Phase |

## Projektziele

1. **Bootstrap 5.3 Migration** - Vollständige Migration von Bootstrap 4.6.1 auf 5.3.x
2. **SEO Standard 2026** - Schema.org Structured Data, Core Web Vitals, semantisches HTML5
3. **Mobile First** - Responsive Design mit Mobile-First-Ansatz
4. **Performance** - Optimale Ladezeiten, Lazy Loading, Critical CSS, minimale JS-Bundles
5. **Moderne UI/UX** - Zeitgemäßes Design mit optimaler Benutzerführung
6. **Modul-Kompatibilität** - Alle bestehenden Custom-Module müssen integriert werden

## Verzeichnisstruktur

```
Template-MRH-2026/
├── README.md                    # Dieses Dokument
├── ROADMAP.md                   # Entwicklungs-Roadmap
├── docs/                        # Dokumentation
│   └── ANALYSE_ERGEBNISSE.md    # Detaillierte Template-Analyse
├── archive/                     # Archiv
│   └── bootstrap4-original/     # Original Live-Template (Referenz)
└── tpl_mrh_2026/                # Neues Template (in Entwicklung)
```

## Zu migrierende Module

### Kern-Module
- Seedfinder (komplexes Suchmodul)
- Reklamation (Beschwerdeformular + Admin)
- Blog-System
- Free Shipping Bar
- Gift Cart (Gutschein-System)
- Newsletter Overlay
- Advanced Contact
- Product Compare
- FAQ Manager

### Integrierte Drittanbieter
- contentAnywhere (11+ Content-Blöcke)
- Cookie Consent
- Mailhive Newsletter
- EasyZoom (→ native CSS/JS Lösung)
- Pushy Navigation (→ BS5 Offcanvas)
- LazyLoad (lazysizes)
- Traffic Light (Lagerampel)
- Customers Notice

## Technologie-Stack (Ziel)

| Komponente | Aktuell | Ziel |
|-----------|---------|------|
| CSS Framework | Bootstrap 4.6.1 | Bootstrap 5.3.x |
| JavaScript | jQuery 3.x + Plugins | Vanilla JS + BS5 Bundle |
| Icons | Font Awesome 6 Pro | Font Awesome 6 Pro (optimiert) |
| Navigation | Pushy (Off-Canvas) | BS5 Offcanvas (nativ) |
| Bild-Zoom | EasyZoom (jQuery) | CSS/Vanilla JS Lösung |
| Lazy Loading | lazysizes (jQuery) | Native `loading="lazy"` + Intersection Observer |
| Template Engine | Smarty 4 | Smarty 4 |
| SEO | Basis Schema.org | Vollständiges Schema.org (Product, Organization, BreadcrumbList, FAQ, Review) |

## Links

- [modified eCommerce](https://www.modified-shop.org)
- [Bootstrap 5.3 Docs](https://getbootstrap.com/docs/5.3/)
- [KarlBogen/bootstrap4](https://github.com/KarlBogen/bootstrap4)
- [Bootstrap 5 Migration Guide](https://getbootstrap.com/docs/5.3/migration/)
