<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Backend v3.0
   
   Speichert Template-Einstellungen als JSON-Dateien:
   - colors.json      → Farb-Einstellungen (inkl. Menü, Buttons)
   - tplsettings.json → Allgemeine Konfiguration
   - logos.json        → Zahlungs- und Versandlogos
   - social.json       → Social Media Links
   
   v3.0 (2026-04-10): ALLE Keys auf tpl-* vereinheitlicht
                       Kein mrh-* / tpl-* Dualismus mehr!
   
   Pfad: templates/tpl_mrh_2026/admin/includes/mrh_configurator.php
   ===================================================================== */

// Sicherheitscheck: Nur im Shop-Kontext ausfuehren
if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return; // Kein die() - verhindert White Screen
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
 * RGB-String sanitizen (nur rgb(r,g,b), rgba(r,g,b,a) oder Hex erlaubt)
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
    if (preg_match('/^rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*[\d.]+\s*,\s*[\d.]+\s*\)$/', $value)) {
        return $value;
    }
    return '';
}

// =====================================================================
// === Standard-Werte (ALLE tpl-* Keys) ===
// =====================================================================
$mrh_color_defaults = [
    // ── Grundfarben ──
    'tpl-main-color'           => 'rgb(74, 140, 42)',
    'tpl-main-color-2'         => 'rgb(30, 30, 30)',
    'tpl-secondary-color'      => 'rgb(74, 140, 42)',

    // ── Navigation / Menü ──
    'tpl-menu-bg'              => 'rgb(22, 163, 74)',
    'tpl-menu-text'            => 'rgb(255, 255, 255)',
    'tpl-menu-hover'           => 'rgb(56, 112, 30)',
    'tpl-menu-active'          => 'rgb(255, 255, 255)',

    // ── Topbar ──
    'tpl-topbar-bg'            => 'rgb(30, 41, 59)',
    'tpl-topbar-text'          => 'rgb(255, 255, 255)',

    // ── Hintergrund ──
    'tpl-bg-color'             => 'rgb(255, 255, 255)',
    'tpl-bg-color-2'           => 'rgb(240, 253, 244)',
    'tpl-bg-productbox'        => 'rgb(255, 255, 255)',
    'tpl-bg-footer'            => 'rgb(15, 23, 42)',

    // ── Schrift ──
    'tpl-text-standard'        => 'rgb(15, 23, 42)',
    'tpl-text-headings'        => 'rgb(15, 23, 42)',
    'tpl-text-button'          => 'rgb(255, 255, 255)',
    'tpl-text-footer'          => 'rgb(148, 163, 184)',
    'tpl-text-footer-headings' => 'rgb(255, 255, 255)',

    // ── Sticky Header ──
    'tpl-sticky-bg'            => 'rgb(255, 255, 255)',
    'tpl-sticky-text'          => 'rgb(51, 65, 85)',

    // ══════════════════════════════════════════════════
    // ── Gefüllte Buttons (btn-*) ──
    // ══════════════════════════════════════════════════
    'tpl-btn-primary-bg'       => 'rgb(74, 140, 42)',
    'tpl-btn-primary-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-primary-hover'    => 'rgb(58, 112, 32)',

    'tpl-btn-secondary-bg'     => 'rgb(108, 117, 125)',
    'tpl-btn-secondary-text'   => 'rgb(255, 255, 255)',
    'tpl-btn-secondary-hover'  => 'rgb(86, 94, 100)',

    'tpl-btn-success-bg'       => 'rgb(25, 135, 84)',
    'tpl-btn-success-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-success-hover'    => 'rgb(20, 108, 67)',

    'tpl-btn-danger-bg'        => 'rgb(220, 53, 69)',
    'tpl-btn-danger-text'      => 'rgb(255, 255, 255)',
    'tpl-btn-danger-hover'     => 'rgb(176, 42, 55)',

    'tpl-btn-warning-bg'       => 'rgb(255, 193, 7)',
    'tpl-btn-warning-text'     => 'rgb(33, 37, 41)',
    'tpl-btn-warning-hover'    => 'rgb(255, 202, 44)',

    'tpl-btn-info-bg'          => 'rgb(23, 162, 184)',
    'tpl-btn-info-text'        => 'rgb(255, 255, 255)',
    'tpl-btn-info-hover'       => 'rgb(19, 132, 150)',

    'tpl-btn-light-bg'         => 'rgb(248, 249, 250)',
    'tpl-btn-light-text'       => 'rgb(33, 37, 41)',
    'tpl-btn-light-hover'      => 'rgb(211, 212, 213)',

    'tpl-btn-dark-bg'          => 'rgb(33, 37, 41)',
    'tpl-btn-dark-text'        => 'rgb(255, 255, 255)',
    'tpl-btn-dark-hover'       => 'rgb(66, 70, 73)',

    // ══════════════════════════════════════════════════
    // ── Outline Buttons (btn-outline-*) ──
    // ══════════════════════════════════════════════════
    'tpl-btn-outline-border'   => 'rgb(74, 140, 42)',
    'tpl-btn-outline-text'     => 'rgb(74, 140, 42)',
    'tpl-btn-outline-hover'    => 'rgb(74, 140, 42)',

    'tpl-btn-outline-primary-bg'       => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-primary-text'     => 'rgb(74, 140, 42)',
    'tpl-btn-outline-primary-hover'    => 'rgb(74, 140, 42)',

    'tpl-btn-outline-secondary-bg'     => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-secondary-text'   => 'rgb(108, 117, 125)',
    'tpl-btn-outline-secondary-hover'  => 'rgb(108, 117, 125)',

    'tpl-btn-outline-success-bg'       => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-success-text'     => 'rgb(25, 135, 84)',
    'tpl-btn-outline-success-hover'    => 'rgb(25, 135, 84)',

    'tpl-btn-outline-danger-bg'        => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-danger-text'      => 'rgb(220, 53, 69)',
    'tpl-btn-outline-danger-hover'     => 'rgb(220, 53, 69)',

    'tpl-btn-outline-warning-bg'       => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-warning-text'     => 'rgb(255, 193, 7)',
    'tpl-btn-outline-warning-hover'    => 'rgb(255, 193, 7)',

    'tpl-btn-outline-info-bg'          => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-info-text'        => 'rgb(23, 162, 184)',
    'tpl-btn-outline-info-hover'       => 'rgb(23, 162, 184)',

    'tpl-btn-outline-light-bg'         => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-light-text'       => 'rgb(248, 249, 250)',
    'tpl-btn-outline-light-hover'      => 'rgb(248, 249, 250)',

    'tpl-btn-outline-dark-bg'          => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-dark-text'        => 'rgb(33, 37, 41)',
    'tpl-btn-outline-dark-hover'       => 'rgb(33, 37, 41)',

    // ── Spezial-Buttons ──
    'tpl-btn-express-bg'       => 'rgb(67, 200, 117)',
    'tpl-btn-express-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-express-hover'    => 'rgb(56, 168, 99)',

    'tpl-btn-details-bg'       => 'rgb(255, 255, 255)',
    'tpl-btn-details-text'     => 'rgb(25, 135, 84)',
    'tpl-btn-details-hover'    => 'rgb(25, 135, 84)',

    'tpl-btn-wishlist-bg'      => 'rgb(108, 117, 125)',
    'tpl-btn-wishlist-text'    => 'rgb(255, 255, 255)',
    'tpl-btn-wishlist-hover'   => 'rgb(220, 53, 69)',

    'tpl-btn-compare-bg'       => 'rgb(108, 117, 125)',
    'tpl-btn-compare-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-compare-hover'    => 'rgb(23, 162, 184)',
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
// colors.json lesen und mit Defaults mergen
$raw_colors = mrh_read_json($json_dir . 'colors.json');

// ── Migration: Alte mrh-* Keys auf tpl-* mappen ──
// Falls colors.json noch mrh-* Keys enthält, werden sie auf tpl-* umgemappt
$mrh_to_tpl_map = [
    'mrh-primary'           => 'tpl-main-color',
    'mrh-secondary'         => 'tpl-main-color-2',
    'mrh-menu-bg'           => 'tpl-menu-bg',
    'mrh-menu-text'         => 'tpl-menu-text',
    'mrh-menu-hover-bg'     => 'tpl-menu-hover',
    'mrh-menu-active-bg'    => 'tpl-menu-active',
    'mrh-topbar-bg'         => 'tpl-topbar-bg',
    'mrh-topbar-text'       => 'tpl-topbar-text',
    'mrh-bg-color'          => 'tpl-bg-color',
    'mrh-bg-color-2'        => 'tpl-bg-color-2',
    'mrh-bg-productbox'     => 'tpl-bg-productbox',
    'mrh-bg-footer'         => 'tpl-bg-footer',
    'mrh-text-standard'     => 'tpl-text-standard',
    'mrh-text-headings'     => 'tpl-text-headings',
    'mrh-text-button'       => 'tpl-text-button',
    'mrh-text-footer'       => 'tpl-text-footer',
    'mrh-text-footer-headings' => 'tpl-text-footer-headings',
    'mrh-sticky-bg'         => 'tpl-sticky-bg',
    'mrh-sticky-text'       => 'tpl-sticky-text',
    // Buttons: mrh-btn-* → tpl-btn-*
    'mrh-btn-primary-bg'    => 'tpl-btn-primary-bg',
    'mrh-btn-primary-text'  => 'tpl-btn-primary-text',
    'mrh-btn-primary-hover' => 'tpl-btn-primary-hover',
    'mrh-btn-secondary-bg'    => 'tpl-btn-secondary-bg',
    'mrh-btn-secondary-text'  => 'tpl-btn-secondary-text',
    'mrh-btn-secondary-hover' => 'tpl-btn-secondary-hover',
    'mrh-btn-success-bg'    => 'tpl-btn-success-bg',
    'mrh-btn-success-text'  => 'tpl-btn-success-text',
    'mrh-btn-success-hover' => 'tpl-btn-success-hover',
    'mrh-btn-danger-bg'     => 'tpl-btn-danger-bg',
    'mrh-btn-danger-text'   => 'tpl-btn-danger-text',
    'mrh-btn-danger-hover'  => 'tpl-btn-danger-hover',
    'mrh-btn-warning-bg'    => 'tpl-btn-warning-bg',
    'mrh-btn-warning-text'  => 'tpl-btn-warning-text',
    'mrh-btn-warning-hover' => 'tpl-btn-warning-hover',
    'mrh-btn-info-bg'       => 'tpl-btn-info-bg',
    'mrh-btn-info-text'     => 'tpl-btn-info-text',
    'mrh-btn-info-hover'    => 'tpl-btn-info-hover',
    'mrh-btn-light-bg'      => 'tpl-btn-light-bg',
    'mrh-btn-light-text'    => 'tpl-btn-light-text',
    'mrh-btn-light-hover'   => 'tpl-btn-light-hover',
    'mrh-btn-dark-bg'       => 'tpl-btn-dark-bg',
    'mrh-btn-dark-text'     => 'tpl-btn-dark-text',
    'mrh-btn-dark-hover'    => 'tpl-btn-dark-hover',
    // Outline Buttons
    'mrh-btn-outline-primary-bg'       => 'tpl-btn-outline-primary-bg',
    'mrh-btn-outline-primary-text'     => 'tpl-btn-outline-primary-text',
    'mrh-btn-outline-primary-hover'    => 'tpl-btn-outline-primary-hover',
    'mrh-btn-outline-secondary-bg'     => 'tpl-btn-outline-secondary-bg',
    'mrh-btn-outline-secondary-text'   => 'tpl-btn-outline-secondary-text',
    'mrh-btn-outline-secondary-hover'  => 'tpl-btn-outline-secondary-hover',
    'mrh-btn-outline-success-bg'       => 'tpl-btn-outline-success-bg',
    'mrh-btn-outline-success-text'     => 'tpl-btn-outline-success-text',
    'mrh-btn-outline-success-hover'    => 'tpl-btn-outline-success-hover',
    'mrh-btn-outline-danger-bg'        => 'tpl-btn-outline-danger-bg',
    'mrh-btn-outline-danger-text'      => 'tpl-btn-outline-danger-text',
    'mrh-btn-outline-danger-hover'     => 'tpl-btn-outline-danger-hover',
    'mrh-btn-outline-warning-bg'       => 'tpl-btn-outline-warning-bg',
    'mrh-btn-outline-warning-text'     => 'tpl-btn-outline-warning-text',
    'mrh-btn-outline-warning-hover'    => 'tpl-btn-outline-warning-hover',
    'mrh-btn-outline-info-bg'          => 'tpl-btn-outline-info-bg',
    'mrh-btn-outline-info-text'        => 'tpl-btn-outline-info-text',
    'mrh-btn-outline-info-hover'       => 'tpl-btn-outline-info-hover',
    'mrh-btn-outline-light-bg'         => 'tpl-btn-outline-light-bg',
    'mrh-btn-outline-light-text'       => 'tpl-btn-outline-light-text',
    'mrh-btn-outline-light-hover'      => 'tpl-btn-outline-light-hover',
    'mrh-btn-outline-dark-bg'          => 'tpl-btn-outline-dark-bg',
    'mrh-btn-outline-dark-text'        => 'tpl-btn-outline-dark-text',
    'mrh-btn-outline-dark-hover'       => 'tpl-btn-outline-dark-hover',
    // Spezial
    'mrh-btn-express-bg'    => 'tpl-btn-express-bg',
    'mrh-btn-express-text'  => 'tpl-btn-express-text',
    'mrh-btn-express-hover' => 'tpl-btn-express-hover',
    'mrh-btn-details-bg'    => 'tpl-btn-details-bg',
    'mrh-btn-details-text'  => 'tpl-btn-details-text',
    'mrh-btn-details-hover' => 'tpl-btn-details-hover',
    'mrh-btn-wishlist-bg'   => 'tpl-btn-wishlist-bg',
    'mrh-btn-wishlist-text' => 'tpl-btn-wishlist-text',
    'mrh-btn-wishlist-hover'=> 'tpl-btn-wishlist-hover',
    'mrh-btn-compare-bg'    => 'tpl-btn-compare-bg',
    'mrh-btn-compare-text'  => 'tpl-btn-compare-text',
    'mrh-btn-compare-hover' => 'tpl-btn-compare-hover',
];

// Migration: mrh-* Werte auf tpl-* übertragen (nur wenn tpl-* Key noch nicht gesetzt)
foreach ($mrh_to_tpl_map as $old_key => $new_key) {
    if (isset($raw_colors[$old_key]) && !isset($raw_colors[$new_key])) {
        $raw_colors[$new_key] = $raw_colors[$old_key];
    }
    // Wenn mrh-* existiert UND tpl-* auch, bevorzuge mrh-* (neuerer Wert vom Konfigurator)
    if (isset($raw_colors[$old_key]) && isset($raw_colors[$new_key])) {
        $raw_colors[$new_key] = $raw_colors[$old_key];
    }
}

// Nur tpl-* Keys behalten, mrh-* und submit-* entfernen
$clean_colors = [];
foreach ($raw_colors as $k => $v) {
    if (strpos($k, 'tpl-') === 0) {
        $clean_colors[$k] = $v;
    }
}

$mrh_colors = array_merge($mrh_color_defaults, $clean_colors);
$mrh_tpl    = array_merge($mrh_tpl_defaults, mrh_read_json($json_dir . 'tplsettings.json'));
$mrh_logos  = array_merge($mrh_logo_defaults, mrh_read_json($json_dir . 'logos.json'));
$mrh_social = array_merge($mrh_social_defaults, mrh_read_json($json_dir . 'social.json'));

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
        // templates_c leeren für sofortige Wirkung
        $tpl_c = DIR_FS_CATALOG . 'templates_c/';
        if (is_dir($tpl_c)) {
            $files = glob($tpl_c . '*');
            foreach ($files as $f) {
                if (is_file($f)) @unlink($f);
            }
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

// 5. Custom CSS speichern
if (isset($_POST['submit-customcss'])) {
    $custom_css = isset($_POST['mrh_custom_css']) ? $_POST['mrh_custom_css'] : '';
    // Basis-Sanitierung: <script> Tags und PHP-Tags entfernen
    $custom_css = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $custom_css);
    $custom_css = preg_replace('/<\?.*?\?>/s', '', $custom_css);
    $css_file = $json_dir . 'custom.css';
    if (file_put_contents($css_file, $custom_css) !== false) {
        $mrh_config_message = '<div class="alert alert-success mx-3">Custom CSS erfolgreich gespeichert!</div>';
        // stylesheet.min.css loeschen damit CSS neu kompiliert wird
        $min_css = $tpl_dir . 'css/stylesheet.min.css';
        if (file_exists($min_css)) {
            @unlink($min_css);
        }
        // templates_c leeren fuer sofortige Wirkung
        $tpl_c = DIR_FS_CATALOG . 'templates_c/';
        if (is_dir($tpl_c)) {
            $files = glob($tpl_c . '*');
            foreach ($files as $f) {
                if (is_file($f)) @unlink($f);
            }
        }
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern des Custom CSS.</div>';
    }
}

// Custom CSS laden (fuer Panel-Anzeige)
$custom_css_file = $json_dir . 'custom.css';
$mrh_custom_css = file_exists($custom_css_file) ? file_get_contents($custom_css_file) : '';

// === Globals setzen ===
$GLOBALS['mrh_colors']  = $mrh_colors;
$GLOBALS['mrh_tpl']     = $mrh_tpl;
$GLOBALS['mrh_logos']   = $mrh_logos;
$GLOBALS['mrh_social']  = $mrh_social;
$GLOBALS['mrh_custom_css'] = $mrh_custom_css;
$GLOBALS['mrh_config_message'] = $mrh_config_message;
