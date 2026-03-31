<?php
/* =====================================================================
   MRH 2026 Template – CSS Custom Properties aus colors.json
   
   Wird in index.html eingebunden via:
   {include file="`$smarty.const.CURRENT_TEMPLATE`/smarty/mrh_color_vars.php"}
   
   Gibt ein <style>-Tag mit den konfigurierten Farben als CSS Custom Properties aus.
   Wird im <head> geladen, damit alle CSS-Dateien die Variablen nutzen können.
   ===================================================================== */

$json_file = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/config/colors.json';

// Standard-Werte (identisch mit mrh_configurator.php)
$defaults = [
    'mrh-primary'              => 'rgb(74, 140, 42)',
    'mrh-secondary'            => 'rgb(30, 30, 30)',
    'mrh-bg-color'             => 'rgb(255, 255, 255)',
    'mrh-bg-color-2'           => 'rgb(240, 253, 244)',
    'mrh-bg-productbox'        => 'rgb(255, 255, 255)',
    'mrh-bg-footer'            => 'rgb(15, 23, 42)',
    'mrh-text-standard'        => 'rgb(15, 23, 42)',
    'mrh-text-headings'        => 'rgb(15, 23, 42)',
    'mrh-text-button'          => 'rgb(255, 255, 255)',
    'mrh-text-footer'          => 'rgb(148, 163, 184)',
    'mrh-text-footer-headings' => 'rgb(255, 255, 255)',
    'mrh-menu-bg'              => 'rgb(22, 163, 74)',
    'mrh-menu-text'            => 'rgb(255, 255, 255)',
    'mrh-menu-hover-bg'        => 'rgba(255, 255, 255, 0.15)',
    'mrh-menu-active-bg'       => 'rgba(255, 255, 255, 0.25)',
    'mrh-topbar-bg'            => 'rgb(30, 41, 59)',
    'mrh-topbar-text'          => 'rgb(255, 255, 255)',
    'mrh-sticky-bg'            => 'rgb(255, 255, 255)',
    'mrh-sticky-text'          => 'rgb(51, 65, 85)',
];

// JSON laden und mergen
$colors = $defaults;
if (file_exists($json_file)) {
    $json = json_decode(file_get_contents($json_file), true);
    if (is_array($json)) {
        $colors = array_merge($defaults, $json);
    }
}

// CSS Custom Properties ausgeben
echo '<style id="mrh-color-vars">' . PHP_EOL;
echo ':root {' . PHP_EOL;
foreach ($colors as $key => $value) {
    // Nur gültige Farben ausgeben
    if (!empty($value)) {
        echo '  --' . htmlspecialchars($key) . ': ' . htmlspecialchars($value) . ';' . PHP_EOL;
    }
}
echo '}' . PHP_EOL;
echo '</style>' . PHP_EOL;
