# MRH Dashboard – Modulares Autoinclude-System

> Version 1.0.0 | Stand: 31.03.2026

## Übersicht

Das MRH Dashboard ist ein modulares Erweiterungssystem für das MRH 2026 Template
(modified eCommerce). Es ermöglicht die Verwaltung von Template-Funktionen über
ein Admin-Panel, das in die bestehende Template-Konfiguration integriert ist
(Sektion "Erweiterte Konfiguration" im Konfigurator-Panel).

**Kein jQuery** – Alle Frontend-Komponenten nutzen modernes Vanilla JS (ES2020+).

## Dateistruktur

```
tpl_mrh_2026/
├── admin/
│   ├── includes/
│   │   ├── mrh_dashboard.php          ← Dashboard-Kern (Singleton, Modul-Loader, Config-Manager)
│   │   └── mrh_configurator_panel.php ← Admin-Panel (Sektion 5: Erweiterte Konfiguration)
│   └── mrh_dashboard/
│       ├── ARCHITECTURE.md            ← Diese Datei
│       ├── install.php                ← Install/Health-Check Script
│       └── modules/
│           └── mega_menu/
│               ├── module.json        ← Modul-Metadaten
│               ├── admin.php          ← Admin-UI (Kategorien, Spalten, Drag&Drop)
│               └── output.php         ← Frontend-Output (Legacy, nicht mehr primär genutzt)
├── config/
│   ├── dashboard_modules.json         ← Modul-Status (aktiv/inaktiv)
│   └── module_mega_menu.json          ← Mega-Menü Konfiguration (JSON)
├── javascript/extra/
│   ├── mrh-core.js.php                ← Frontend-Logik (liest MRH_MEGAMENU_CONFIG)
│   └── mrh-megamenu-config.js.php     ← Auto-Include: Gibt window.MRH_MEGAMENU_CONFIG aus
└── css/
    └── mrh-custom.css                 ← Mega-Dropdown Styles
```

## Architektur-Prinzipien

1. **JSON-basierte Config** – Keine DB-Tabellen nötig. Config liegt als JSON im `config/` Ordner.
2. **Modul-Discovery** – Module werden automatisch aus `modules/` erkannt (module.json).
3. **Auto-Include** – Frontend-Output über `javascript/extra/` wird automatisch geladen.
4. **Systemnah** – URLs nutzen `index.php?cPath=...`, SEO-Modul schreibt um.
5. **Fallback-Kette** – Dashboard-Config → JS-staticLinks → JS-Keywords → gleichmäßige Verteilung.
6. **Vanilla JS** – ES2020+ Features: Optional Chaining, Nullish Coalescing, Template Literals, for...of.

## Datenfluss

```
[Admin-Panel] → speichert → [config/module_mega_menu.json]
                                     ↓
[mrh-megamenu-config.js.php] → liest JSON + DB-Kategorienamen
                                     ↓
                              → gibt window.MRH_MEGAMENU_CONFIG aus
                                     ↓
[mrh-core.js.php] → getDashboardConfig() prüft Config
                  → buildDropdown() baut Mega-Dropdown
                  → Fallback: getCategoryConfig() (hardcoded)
```

## Frontend-Priorisierung (buildDropdown)

```
1. getDashboardConfig(href)  → Prüft window.MRH_MEGAMENU_CONFIG nach cPath-ID
   ↓ (wenn vorhanden)
   _buildFromDashboardConfig()  → Spalten + Items aus Admin-Panel, cPath-URLs
   
2. getCategoryConfig(text)   → JS-Fallback mit Keywords
   ↓ (wenn useStaticOnly + staticLinks)
   _buildFromStaticLinks()   → Hardcoded Links als Backup
   ↓ (sonst)
   _buildFromCatNavi()       → Dynamische Keyword-Zuordnung aus DOM
```

## Neues Modul erstellen

1. Ordner erstellen: `admin/mrh_dashboard/modules/{modul_id}/`
2. `module.json` mit Metadaten anlegen:
   ```json
   {
     "id": "mein_modul",
     "name": "Mein Modul",
     "version": "1.0.0",
     "description": "Beschreibung",
     "author": "MRH Template",
     "icon": "fa-cog",
     "admin_file": "admin.php",
     "sort_order": 20
   }
   ```
3. `admin.php` für Admin-UI implementieren
4. Config über `MRH_Dashboard::getConfig()` / `setConfig()` lesen/schreiben
5. Optional: Frontend-Output als `javascript/extra/mrh-{modul_id}.js.php`

## Config-API

```php
// Einzelnen Wert lesen
$max = MRH_Dashboard::getConfig('mega_menu', 'max_items_per_column', 5);

// Einzelnen Wert setzen
MRH_Dashboard::setConfig('mega_menu', 'max_items_per_column', 5);

// Gesamte Config lesen
$config = MRH_Dashboard::getConfig('mega_menu');

// Gesamte Config speichern
MRH_Dashboard::saveConfig('mega_menu', $config);

// Modul-Status prüfen
$dashboard = MRH_Dashboard::getInstance();
$active = $dashboard->isModuleActive('mega_menu');
```

## Installation

```bash
# Auf dem Server ausführen:
php templates/tpl_mrh_2026/admin/mrh_dashboard/install.php
```

Das Script prüft Ordnerstruktur, Berechtigungen und erstellt Default-Configs.
Kann jederzeit erneut ausgeführt werden (idempotent).

## Vorteile

1. **Systemnah**: Nutzt modified eCommerce Template-Architektur
2. **Zukunftssicher**: Neue Module einfach als Ordner hinzufügen
3. **Kein DB-Schema**: JSON-Config statt DB-Tabellen = kein Migrations-Aufwand
4. **Admin-UI**: Konfiguration ohne Code-Änderungen
5. **Modular**: Jedes Modul ist unabhängig aktivierbar/deaktivierbar
6. **Fallback**: Funktioniert auch ohne Dashboard-Config (JS-Fallback)
