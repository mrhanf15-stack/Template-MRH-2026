<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Backend v2.0
   
   Speichert Template-Einstellungen als JSON-Dateien:
   - colors.json      → Farb-Einstellungen (inkl. Menü, Buttons)
   - tplsettings.json → Allgemeine Konfiguration
   - logos.json        → Zahlungs- und Versandlogos
   - social.json       → Social Media Links
   
   v2.0 (2026-04-10): Alle Button-Farben (gefüllt + outline) hinzugefügt
   
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
    if (preg_match('/^rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*[\d.]+\s*,\s*[\d.]+\s*\)$/', $value)) {
        return $value;
    }
    return '';
}

// === Standard-Werte ===
$mrh_color_defaults = [
    // ── Grundfarben ──
    'mrh-primary'           => 'rgb(74, 140, 42)',
    'mrh-secondary'         => 'rgb(30, 30, 30)',

    // ── Menü ──
    'mrh-menu-bg'           => 'rgb(22, 163, 74)',
    'mrh-menu-text'         => 'rgb(255, 255, 255)',
    'mrh-menu-hover-bg'     => 'rgba(255, 255, 255, 0.15)',
    'mrh-menu-active-bg'    => 'rgba(255, 255, 255, 0.25)',

    // ── Topbar ──
    'mrh-topbar-bg'         => 'rgb(30, 41, 59)',
    'mrh-topbar-text'       => 'rgb(255, 255, 255)',

    // ── Hintergrund ──
    'mrh-bg-color'          => 'rgb(255, 255, 255)',
    'mrh-bg-color-2'        => 'rgb(240, 253, 244)',
    'mrh-bg-productbox'     => 'rgb(255, 255, 255)',
    'mrh-bg-footer'         => 'rgb(15, 23, 42)',

    // ── Schrift ──
    'mrh-text-standard'        => 'rgb(15, 23, 42)',
    'mrh-text-headings'        => 'rgb(15, 23, 42)',
    'mrh-text-button'          => 'rgb(255, 255, 255)',
    'mrh-text-footer'          => 'rgb(148, 163, 184)',
    'mrh-text-footer-headings' => 'rgb(255, 255, 255)',

    // ── Sticky Header ──
    'mrh-sticky-bg'         => 'rgb(255, 255, 255)',
    'mrh-sticky-text'       => 'rgb(51, 65, 85)',

    // ══════════════════════════════════════════════════
    // ── Gefüllte Buttons (btn-*) ──
    // ══════════════════════════════════════════════════
    'mrh-btn-primary-bg'    => 'rgb(74, 140, 42)',
    'mrh-btn-primary-text'  => 'rgb(255, 255, 255)',
    'mrh-btn-primary-hover' => 'rgb(56, 112, 30)',

    'mrh-btn-secondary-bg'    => 'rgb(108, 117, 125)',
    'mrh-btn-secondary-text'  => 'rgb(255, 255, 255)',
    'mrh-btn-secondary-hover' => 'rgb(86, 94, 100)',

    'mrh-btn-success-bg'    => 'rgb(25, 135, 84)',
    'mrh-btn-success-text'  => 'rgb(255, 255, 255)',
    'mrh-btn-success-hover' => 'rgb(20, 108, 67)',

    'mrh-btn-danger-bg'     => 'rgb(220, 53, 69)',
    'mrh-btn-danger-text'   => 'rgb(255, 255, 255)',
    'mrh-btn-danger-hover'  => 'rgb(176, 42, 55)',

    'mrh-btn-warning-bg'    => 'rgb(255, 193, 7)',
    'mrh-btn-warning-text'  => 'rgb(33, 37, 41)',
    'mrh-btn-warning-hover' => 'rgb(255, 202, 44)',

    'mrh-btn-info-bg'       => 'rgb(23, 162, 184)',
    'mrh-btn-info-text'     => 'rgb(255, 255, 255)',
    'mrh-btn-info-hover'    => 'rgb(19, 132, 150)',

    'mrh-btn-light-bg'      => 'rgb(248, 249, 250)',
    'mrh-btn-light-text'    => 'rgb(33, 37, 41)',
    'mrh-btn-light-hover'   => 'rgb(211, 212, 213)',

    'mrh-btn-dark-bg'       => 'rgb(33, 37, 41)',
    'mrh-btn-dark-text'     => 'rgb(255, 255, 255)',
    'mrh-btn-dark-hover'    => 'rgb(66, 70, 73)',

    // ══════════════════════════════════════════════════
    // ── Outline Buttons (btn-outline-*) ──
    // ══════════════════════════════════════════════════
    'mrh-btn-outline-primary-bg'    => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-primary-text'  => 'rgb(74, 140, 42)',
    'mrh-btn-outline-primary-hover' => 'rgb(74, 140, 42)',

    'mrh-btn-outline-secondary-bg'    => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-secondary-text'  => 'rgb(108, 117, 125)',
    'mrh-btn-outline-secondary-hover' => 'rgb(108, 117, 125)',

    'mrh-btn-outline-success-bg'    => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-success-text'  => 'rgb(25, 135, 84)',
    'mrh-btn-outline-success-hover' => 'rgb(25, 135, 84)',

    'mrh-btn-outline-danger-bg'     => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-danger-text'   => 'rgb(220, 53, 69)',
    'mrh-btn-outline-danger-hover'  => 'rgb(220, 53, 69)',

    'mrh-btn-outline-warning-bg'    => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-warning-text'  => 'rgb(255, 193, 7)',
    'mrh-btn-outline-warning-hover' => 'rgb(255, 193, 7)',

    'mrh-btn-outline-info-bg'       => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-info-text'     => 'rgb(23, 162, 184)',
    'mrh-btn-outline-info-hover'    => 'rgb(23, 162, 184)',

    'mrh-btn-outline-light-bg'      => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-light-text'    => 'rgb(248, 249, 250)',
    'mrh-btn-outline-light-hover'   => 'rgb(248, 249, 250)',

    'mrh-btn-outline-dark-bg'       => 'rgba(0, 0, 0, 0)',
    'mrh-btn-outline-dark-text'     => 'rgb(33, 37, 41)',
    'mrh-btn-outline-dark-hover'    => 'rgb(33, 37, 41)',

    // ── Spezial-Buttons ──
    'mrh-btn-express-bg'    => 'rgb(23, 162, 184)',
    'mrh-btn-express-text'  => 'rgb(255, 255, 255)',
    'mrh-btn-express-hover' => 'rgb(200, 81, 81)',

    'mrh-btn-details-bg'    => 'rgb(255, 255, 255)',
    'mrh-btn-details-text'  => 'rgb(51, 65, 85)',
    'mrh-btn-details-hover' => 'rgb(74, 140, 42)',

    'mrh-btn-wishlist-bg'    => 'rgb(255, 255, 255)',
    'mrh-btn-wishlist-text'  => 'rgb(51, 65, 85)',
    'mrh-btn-wishlist-hover' => 'rgb(220, 53, 69)',

    'mrh-btn-compare-bg'    => 'rgb(255, 255, 255)',
    'mrh-btn-compare-text'  => 'rgb(51, 65, 85)',
    'mrh-btn-compare-hover' => 'rgb(23, 162, 184)',
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
