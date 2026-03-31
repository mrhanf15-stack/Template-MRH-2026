<?php
/* =====================================================================
   MRH Dashboard – Install / Health-Check Script
   
   Prüft die Ordnerstruktur und erstellt Default-Konfigurationen.
   Kann jederzeit erneut ausgeführt werden (idempotent).
   
   Ausführung auf dem Server:
   $ php install.php
   
   Oder im Browser (als Admin eingeloggt):
   https://mr-hanf.at/templates/tpl_mrh_2026/admin/mrh_dashboard/install.php
   ===================================================================== */

// Standalone-Modus: Wenn nicht aus modified eCommerce aufgerufen
if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    // Pfade manuell setzen für Standalone-Ausführung
    $tpl_dir = dirname(dirname(__DIR__)) . '/';
    $config_dir = $tpl_dir . 'config/';
    $modules_dir = __DIR__ . '/modules/';
    $standalone = true;
} else {
    $tpl_dir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
    $config_dir = $tpl_dir . 'config/';
    $modules_dir = $tpl_dir . 'admin/mrh_dashboard/modules/';
    $standalone = false;
}

$results = [];
$errors = 0;

// Header
echo "=== MRH Dashboard – Installation ===\n\n";

// 1. Config-Verzeichnis prüfen/erstellen
if (!is_dir($config_dir)) {
    if (mkdir($config_dir, 0755, true)) {
        $results[] = "[OK] Config-Verzeichnis erstellt: {$config_dir}";
    } else {
        $results[] = "[FEHLER] Config-Verzeichnis konnte nicht erstellt werden: {$config_dir}";
        $errors++;
    }
} else {
    $results[] = "[OK] Config-Verzeichnis existiert: {$config_dir}";
}

// 2. Config-Verzeichnis beschreibbar?
if (is_writable($config_dir)) {
    $results[] = "[OK] Config-Verzeichnis ist beschreibbar";
} else {
    $results[] = "[FEHLER] Config-Verzeichnis ist NICHT beschreibbar! chmod 755 oder 775 setzen.";
    $errors++;
}

// 3. Module-Verzeichnis prüfen
if (is_dir($modules_dir)) {
    $results[] = "[OK] Module-Verzeichnis existiert: {$modules_dir}";
    
    // Module zählen
    $module_count = 0;
    $dirs = glob($modules_dir . '*', GLOB_ONLYDIR);
    foreach ($dirs as $dir) {
        if (file_exists($dir . '/module.json')) {
            $meta = json_decode(file_get_contents($dir . '/module.json'), true);
            $name = $meta['name'] ?? basename($dir);
            $version = $meta['version'] ?? '?';
            $results[] = "  → Modul gefunden: {$name} v{$version}";
            $module_count++;
        }
    }
    $results[] = "[OK] {$module_count} Modul(e) erkannt";
} else {
    $results[] = "[FEHLER] Module-Verzeichnis nicht gefunden: {$modules_dir}";
    $errors++;
}

// 4. Dashboard-Kern prüfen
$dashboard_core = dirname(__DIR__) . '/includes/mrh_dashboard.php';
if (file_exists($dashboard_core)) {
    $results[] = "[OK] Dashboard-Kern vorhanden: mrh_dashboard.php";
} else {
    $results[] = "[FEHLER] Dashboard-Kern NICHT gefunden: {$dashboard_core}";
    $errors++;
}

// 5. Frontend-Output prüfen
$frontend_output = $tpl_dir . 'javascript/extra/mrh-megamenu-config.js.php';
if (file_exists($frontend_output)) {
    $results[] = "[OK] Frontend-Output vorhanden: mrh-megamenu-config.js.php";
} else {
    $results[] = "[WARNUNG] Frontend-Output nicht gefunden. Mega-Menü nutzt JS-Fallback.";
}

// 6. Dashboard-Module Status-Datei prüfen/erstellen
$status_file = $config_dir . 'dashboard_modules.json';
if (!file_exists($status_file)) {
    $default_status = ['mega_menu' => true];
    if (file_put_contents($status_file, json_encode($default_status, JSON_PRETTY_PRINT))) {
        $results[] = "[OK] Module-Status erstellt (mega_menu: aktiv)";
    } else {
        $results[] = "[FEHLER] Module-Status konnte nicht erstellt werden";
        $errors++;
    }
} else {
    $results[] = "[OK] Module-Status existiert bereits";
}

// 7. Mega-Menü Default-Config erstellen (wenn noch nicht vorhanden)
$megamenu_config_file = $config_dir . 'module_mega_menu.json';
if (!file_exists($megamenu_config_file)) {
    // Leere Default-Config – wird über Admin-Panel befüllt
    $default_config = [
        'categories' => [],
        'max_items_per_column' => 5,
        'show_promo' => true,
        '_info' => 'Konfiguration wird über das Admin-Panel (Erweiterte Konfiguration → Mega-Menü Manager) verwaltet.'
    ];
    if (file_put_contents($megamenu_config_file, json_encode($default_config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $results[] = "[OK] Mega-Menü Default-Config erstellt (leer – über Admin befüllen)";
    } else {
        $results[] = "[FEHLER] Mega-Menü Config konnte nicht erstellt werden";
        $errors++;
    }
} else {
    $config = json_decode(file_get_contents($megamenu_config_file), true);
    $cat_count = isset($config['categories']) ? count($config['categories']) : 0;
    $results[] = "[OK] Mega-Menü Config existiert ({$cat_count} Kategorie(n) konfiguriert)";
}

// === Zusammenfassung ===
echo implode("\n", $results);
echo "\n\n";

if ($errors === 0) {
    echo "=== INSTALLATION ERFOLGREICH ===\n";
    echo "Nächster Schritt: Im Admin-Panel unter 'Erweiterte Konfiguration → Mega-Menü Manager'\n";
    echo "die Kategorien und Spalten konfigurieren.\n";
} else {
    echo "=== {$errors} FEHLER GEFUNDEN ===\n";
    echo "Bitte die oben genannten Probleme beheben und erneut ausführen.\n";
}

echo "\n";
