<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Backend
   
   Speichert Template-Einstellungen als JSON-Dateien:
   - colors.json      → Farb-Einstellungen (inkl. Menü-Farben)
   - tplsettings.json → Allgemeine Konfiguration
   - logos.json        → Zahlungs- und Versandlogos
   - social.json       → Social Media Links
   
   Pfad: templates/tpl_mrh_2026/admin/includes/mrh_configurator.php
   ===================================================================== */

if (!defined('_VALID_XTC')) {
    // Fallback: Prüfe ob Admin eingeloggt ist
    if (!isset($_SESSION['customers_status']['customers_status']) || $_SESSION['customers_status']['customers_status'] !== '0') {
        die('Zugriff verweigert');
    }
}

// === Pfade ===
$tpl_dir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
$json_dir = $tpl_dir . 'config/';

// Sicherstellen, dass config-Verzeichnis existiert
if (!is_dir($json_dir)) {
    mkdir($json_dir, 0755, true);
}

// === Hilfsfunktionen ===

/**
 * JSON-Datei lesen und als Array zurückgeben
 */
function mrh_read_json($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }
    return [];
}

/**
 * Array als JSON-Datei speichern
 */
function mrh_write_json($file, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return file_put_contents($file, $json) !== false;
}

/**
 * RGB-String sanitizen (nur rgb(r,g,b) oder Hex erlaubt)
 */
function mrh_sanitize_color($value) {
    $value = trim($value);
    // Hex-Farbe
    if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
        return $value;
    }
    // rgb(r, g, b) Format
    if (preg_match('/^rgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\)$/', $value)) {
        return $value;
    }
    // rgba(r, g, b, a) Format
    if (preg_match('/^rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*[\d.]+\s*\)$/', $value)) {
        return $value;
    }
    return '';
}

// === Standard-Werte ===
$mrh_color_defaults = [
    // Bestehende Farben (portiert von RevPlus)
    'mrh-primary'           => 'rgb(74, 140, 42)',
    'mrh-secondary'         => 'rgb(30, 30, 30)',
    'mrh-bg-color'          => 'rgb(255, 255, 255)',
    'mrh-bg-color-2'        => 'rgb(240, 253, 244)',
    'mrh-bg-productbox'     => 'rgb(255, 255, 255)',
    'mrh-bg-footer'         => 'rgb(15, 23, 42)',
    'mrh-text-standard'     => 'rgb(15, 23, 42)',
    'mrh-text-headings'     => 'rgb(15, 23, 42)',
    'mrh-text-button'       => 'rgb(255, 255, 255)',
    'mrh-text-footer'       => 'rgb(148, 163, 184)',
    'mrh-text-footer-headings' => 'rgb(255, 255, 255)',
    
    // NEU: Menü-Farben
    'mrh-menu-bg'           => 'rgb(22, 163, 74)',   // --mrh-green-600
    'mrh-menu-text'         => 'rgb(255, 255, 255)',
    'mrh-menu-hover-bg'     => 'rgba(255, 255, 255, 0.15)',
    'mrh-menu-active-bg'    => 'rgba(255, 255, 255, 0.25)',
    
    // NEU: Topbar-Farben
    'mrh-topbar-bg'         => 'rgb(30, 41, 59)',     // slate-800
    'mrh-topbar-text'       => 'rgb(255, 255, 255)',
    
    // NEU: Sticky Header
    'mrh-sticky-bg'         => 'rgb(255, 255, 255)',
    'mrh-sticky-text'       => 'rgb(51, 65, 85)',
];

$mrh_tpl_defaults = [
    'tpl_cfg_ssl'              => 'on',
    'tpl_cfg_ts'               => 'off',
    'tpl_cfg_menu'             => 'horizontal',
    'tpl_cfg_infinitescroll'   => 'on',
    'tpl_cfg_barrierefreiTool' => 'on',
];

$mrh_logo_defaults = [
    'payment' => ['vorkasse', 'kreditkarten', 'applepay', 'googlepay', 'lastschrift', 'rechnung'],
    'shipping' => ['dhl', 'ups', 'gls'],
];

$mrh_social_defaults = [
    'facebook'  => '',
    'twitter'   => '',
    'instagram' => '',
    'pinterest' => '',
    'linkedin'  => '',
    'tiktok'    => '',
    'youtube'   => '',
];

// === Daten laden ===
$mrh_colors   = array_merge($mrh_color_defaults, mrh_read_json($json_dir . 'colors.json'));
$mrh_tpl      = array_merge($mrh_tpl_defaults, mrh_read_json($json_dir . 'tplsettings.json'));
$mrh_logos    = array_merge($mrh_logo_defaults, mrh_read_json($json_dir . 'logos.json'));
$mrh_social   = array_merge($mrh_social_defaults, mrh_read_json($json_dir . 'social.json'));

// === Formulare verarbeiten ===
$mrh_config_message = '';

// 1. Farben speichern
if (isset($_POST['submit-colorsettings'])) {
    $save_colors = [];
    foreach ($mrh_color_defaults as $key => $default) {
        $posted = isset($_POST[$key]) ? mrh_sanitize_color($_POST[$key]) : '';
        $save_colors[$key] = !empty($posted) ? $posted : $default;
    }
    if (mrh_write_json($json_dir . 'colors.json', $save_colors)) {
        $mrh_colors = $save_colors;
        $mrh_config_message = '<div class="alert alert-success mx-3">Farben erfolgreich gespeichert! Cache leeren nicht vergessen.</div>';
        // stylesheet.min.css löschen damit Farben neu kompiliert werden
        $min_css = $tpl_dir . 'css/stylesheet.min.css';
        if (file_exists($min_css)) {
            @unlink($min_css);
        }
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern der Farben.</div>';
    }
}

// 2. Template-Einstellungen speichern
if (isset($_POST['submit-tplsettings'])) {
    $save_tpl = [];
    foreach ($mrh_tpl_defaults as $key => $default) {
        $save_tpl[$key] = isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key])) : $default;
    }
    if (mrh_write_json($json_dir . 'tplsettings.json', $save_tpl)) {
        $mrh_tpl = $save_tpl;
        $mrh_config_message = '<div class="alert alert-success mx-3">Konfiguration erfolgreich gespeichert!</div>';
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern der Konfiguration.</div>';
    }
}

// 3. Logos speichern
if (isset($_POST['submit-logosettings'])) {
    $save_logos = [
        'payment'  => isset($_POST['mrh_cfg_payment_logos']) ? $_POST['mrh_cfg_payment_logos'] : [],
        'shipping' => isset($_POST['mrh_cfg_shipping_logos']) ? $_POST['mrh_cfg_shipping_logos'] : [],
    ];
    if (mrh_write_json($json_dir . 'logos.json', $save_logos)) {
        $mrh_logos = $save_logos;
        $mrh_config_message = '<div class="alert alert-success mx-3">Logos erfolgreich gespeichert!</div>';
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern der Logos.</div>';
    }
}

// 4. Social Media speichern
if (isset($_POST['submit-socialsettings'])) {
    $save_social = [];
    foreach ($mrh_social_defaults as $key => $default) {
        $save_social[$key] = isset($_POST[$key]) ? filter_var(trim($_POST[$key]), FILTER_SANITIZE_URL) : '';
    }
    if (mrh_write_json($json_dir . 'social.json', $save_social)) {
        $mrh_social = $save_social;
        $mrh_config_message = '<div class="alert alert-success mx-3">Social Media Links erfolgreich gespeichert!</div>';
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern der Social Media Links.</div>';
    }
}
