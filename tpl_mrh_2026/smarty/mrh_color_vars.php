<?php
/* =====================================================================
   DEPRECATED seit 09.04.2026
   =====================================================================
   Diese Datei wird NICHT MEHR VERWENDET.

   Alle CSS Custom Properties (--tpl-*, --mrh-*) werden jetzt
   ausschliesslich von general.css.php als inline <style> gesetzt.
   general.css.php ist die SINGLE SOURCE OF TRUTH.

   Diese Datei bleibt nur als Referenz erhalten.
   Sie wird in KEINEM Template eingebunden.
   =====================================================================
   Alte Einbindung (NICHT VERWENDEN):
   {include file="`$smarty.const.CURRENT_TEMPLATE`/smarty/mrh_color_vars.php"}
   ===================================================================== */

// DEPRECATED: Code deaktiviert. Siehe general.css.php fuer die aktive Implementierung.
return;

$json_file = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/config/colors.json';

// Standard-Werte (tpl- Prefix, passend zum RevPlus Konfigurator)
$defaults = [
    'tpl-main-color'           => 'rgb(93, 178, 51)',
    'tpl-main-color-2'         => 'rgb(74, 140, 42)',
    'tpl-secondary-color'      => 'rgb(74, 140, 42)',
    'tpl-bg-color'             => 'rgb(255, 255, 255)',
    'tpl-bg-color-2'           => 'rgb(255, 255, 255)',
    'tpl-bg-productbox'        => 'rgb(255, 255, 255)',
    'tpl-bg-footer'            => 'rgb(15, 23, 42)',
    'tpl-text-standard'        => 'rgb(51, 65, 85)',
    'tpl-text-headings'        => 'rgb(51, 65, 85)',
    'tpl-text-button'          => 'rgb(255, 255, 255)',
    'tpl-text-footer'          => 'rgb(160, 169, 182)',
    'tpl-text-footer-headings' => 'rgb(255, 255, 255)',
    'tpl-menu-bg'              => 'rgb(93, 178, 51)',
    'tpl-menu-text'            => 'rgb(255, 255, 255)',
    'tpl-menu-hover'           => 'rgb(56, 112, 30)',
    'tpl-menu-active'          => 'rgb(244, 226, 104)',
    'tpl-topbar-bg'            => 'rgb(74, 140, 42)',
    'tpl-topbar-text'          => 'rgb(255, 255, 255)',
    'tpl-sticky-bg'            => 'rgb(255, 255, 255)',
    'tpl-sticky-text'          => 'rgb(51, 65, 85)',
    'tpl-btn-primary-bg'       => 'rgb(108, 195, 66)',
    'tpl-btn-primary-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-primary-hover'    => 'rgb(54, 134, 16)',
    'tpl-btn-secondary-bg'     => 'rgb(108, 117, 125)',
    'tpl-btn-secondary-text'   => 'rgb(255, 255, 255)',
    'tpl-btn-secondary-hover'  => 'rgb(86, 94, 100)',
    'tpl-btn-outline-border'   => 'rgb(74, 140, 42)',
    'tpl-btn-outline-text'     => 'rgb(74, 140, 42)',
    'tpl-btn-outline-hover'    => 'rgb(74, 140, 42)',
    'tpl-btn-info-bg'          => 'rgb(23, 162, 184)',
    'tpl-btn-info-text'        => 'rgb(255, 255, 255)',
    'tpl-btn-info-hover'       => 'rgb(19, 132, 150)',
    'tpl-btn-express-bg'       => 'rgb(23, 162, 184)',
    'tpl-btn-express-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-express-hover'    => 'rgb(19, 132, 150)',
    'tpl-btn-details-bg'       => 'rgb(255, 255, 255)',
    'tpl-btn-details-text'     => 'rgb(25, 135, 84)',
    'tpl-btn-details-hover'    => 'rgb(25, 135, 84)',
    'tpl-btn-wishlist-bg'      => 'rgb(108, 117, 125)',
    'tpl-btn-wishlist-text'    => 'rgb(255, 255, 255)',
    'tpl-btn-wishlist-hover'   => 'rgb(86, 94, 100)',
    'tpl-btn-compare-bg'       => 'rgb(108, 117, 125)',
    'tpl-btn-compare-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-compare-hover'    => 'rgb(86, 94, 100)',
];

// JSON laden und mergen
$colors = $defaults;
if (file_exists($json_file)) {
    $json = json_decode(file_get_contents($json_file), true);
    if (is_array($json)) {
        // Nur gültige Farb-Keys übernehmen (submit-Button etc. ignorieren)
        $valid_keys = array_keys($defaults);
        foreach ($json as $key => $value) {
            if (in_array($key, $valid_keys) && !empty($value)) {
                $colors[$key] = $value;
            }
        }
    }
}

// CSS Custom Properties ausgeben
echo '<style id="mrh-color-vars">' . PHP_EOL;
echo ':root {' . PHP_EOL;

foreach ($colors as $key => $value) {
    if (!empty($value)) {
        // Original tpl- Variable ausgeben
        echo '  --' . htmlspecialchars($key) . ': ' . htmlspecialchars($value) . ';' . PHP_EOL;
    }
}

// Zusätzliche Alias-Variablen für mrh-custom.css Kompatibilität
$aliases = [
    'tpl-main-color'      => 'mrh-primary',
    'tpl-main-color-2'    => 'mrh-primary-dark',
    'tpl-secondary-color' => 'mrh-primary-light',
    'tpl-topbar-bg'       => 'mrh-topbar-bg',
    'tpl-topbar-text'     => 'mrh-topbar-text',
    'tpl-sticky-bg'       => 'mrh-sticky-bg',
    'tpl-sticky-text'     => 'mrh-sticky-text',
    'tpl-bg-footer'       => 'mrh-bg-footer',
    'tpl-text-footer'     => 'mrh-text-footer',
    'tpl-menu-bg'         => 'mrh-menu-bg',
    'tpl-menu-text'       => 'mrh-menu-text',
    'tpl-menu-hover'      => 'mrh-menu-hover',
    'tpl-menu-active'     => 'mrh-menu-active',
];

foreach ($aliases as $tpl_key => $mrh_key) {
    if (isset($colors[$tpl_key]) && !empty($colors[$tpl_key])) {
        echo '  --' . htmlspecialchars($mrh_key) . ': ' . htmlspecialchars($colors[$tpl_key]) . ';' . PHP_EOL;
    }
}

// Zusätzliche berechnete Variablen
echo '  --mrh-header-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);' . PHP_EOL;
echo '  --mrh-shipping-bar-bg: rgba(240, 253, 244, 0.8);' . PHP_EOL;
echo '  --mrh-shipping-bar-text: ' . htmlspecialchars($colors['tpl-main-color-2']) . ';' . PHP_EOL;
echo '  --mrh-green-accent: ' . htmlspecialchars($colors['tpl-main-color']) . ';' . PHP_EOL;

echo '}' . PHP_EOL;
echo '</style>' . PHP_EOL;
