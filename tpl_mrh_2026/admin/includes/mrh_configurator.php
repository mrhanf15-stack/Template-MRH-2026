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
   v3.1 (2026-04-10): Preset/Backup/Restore-System hinzugefuegt
   v3.2 (2026-04-11): Icon-Konfigurator (Tab 9) – icons.json Speichern/Laden/Reset
   v3.3 (2026-04-11): Badge-Konfigurator – Produkt-Typ-Badges konfigurierbar

   Pfad: templates/tpl_mrh_2026/admin/includes/mrh_configurator.php
   ===================================================================== */

// Sicherheitscheck: Nur im Shop-Kontext ausfuehren
if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return; // Kein die() - verhindert White Screen
}

// === Pfade ===
$tpl_dir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
$json_dir = $tpl_dir . 'config/';
$presets_dir = $json_dir . 'presets/';
$backups_dir = $json_dir . 'backups/';

// Sicherstellen, dass Verzeichnisse existieren
if (!is_dir($json_dir))    { @mkdir($json_dir, 0755, true); }
if (!is_dir($presets_dir)) { @mkdir($presets_dir, 0755, true); }
if (!is_dir($backups_dir)) { @mkdir($backups_dir, 0755, true); }

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
    // CSS-Groessenangabe (rem, px, em, %, vw, vh) – auch negative Werte
    if (preg_match('/^-?[\d.]+(rem|px|em|%|vw|vh)$/', $value)) {
        return $value;
    }
    // CSS-Keyword: auto, none, inherit, initial, unset
    if (in_array($value, ['auto', 'none', 'inherit', 'initial', 'unset'], true)) {
        return $value;
    }
    // Reine Ganzzahl (fuer Toggle-Werte wie 0/1)
    if (preg_match('/^\d+$/', $value)) {
        return $value;
    }
    // CSS-Shorthand (z.B. "8px 0", "10px 20px 10px 20px") – max 4 Teile, auch negative
    if (preg_match('/^(-?[\d.]+(rem|px|em|%|vw|vh)|0)(\s+(-?[\d.]+(rem|px|em|%|vw|vh)|0)){1,3}$/', $value)) {
        return $value;
    }
    // Box-Shadow: z.B. "0 4px 12px rgba(0,0,0,0.3)" oder "0 2px 6px rgba(0,0,0,0.12)"
    if (preg_match('/^-?\d+\s+-?\d+(px|rem|em)?\s+-?\d+(px|rem|em)?\s+rgba?\(\s*[\d.,\s]+\)$/', $value)) {
        return $value;
    }
    // CSS-Transform: z.B. "translateY(-1px)" oder "scale(1.05)"
    if (preg_match('/^(translate[XY]?|scale|rotate|skew[XY]?)\(\s*-?[\d.]+(px|rem|em|deg|%)?\s*\)$/', $value)) {
        return $value;
    }
    // Allgemeiner CSS-Wert: nur sichere Zeichen (Buchstaben, Zahlen, Leerzeichen, Klammern, Komma, Punkt, Minus, Prozent, Hash)
    // Maximal 200 Zeichen, kein Semikolon, kein Script
    if (strlen($value) <= 200 && preg_match('/^[a-zA-Z0-9\s(),._#%\/-]+$/', $value) && stripos($value, 'script') === false && strpos($value, ';') === false) {
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

    // ── Footer Links & Typografie ──
    'tpl-footer-link-color'           => 'rgb(173, 181, 189)',
    'tpl-footer-link-hover'           => 'rgb(255, 255, 255)',
    'tpl-footer-link-decoration'      => 'none',
    'tpl-footer-link-hover-decoration'=> 'underline',
    'tpl-footer-font-size'            => '0.875rem',
    'tpl-footer-heading-size'         => '1.125rem',
    'tpl-footer-heading-weight'       => '700',
    'tpl-footer-border-color'         => 'rgba(255,255,255,0.1)',

    // ── Footertext (SEO-Text-Box .box3.footertext) ──
    'tpl-ft-bg'                       => 'transparent',
    'tpl-ft-text-color'               => 'rgb(148, 163, 184)',
    'tpl-ft-heading-color'            => 'rgb(255, 255, 255)',
    'tpl-ft-heading-size'             => '1rem',
    'tpl-ft-font-size'                => '0.8125rem',
    'tpl-ft-link-color'               => 'rgb(74, 140, 42)',
    'tpl-ft-link-hover'               => 'rgb(255, 255, 255)',
    'tpl-ft-link-decoration'          => 'none',
    'tpl-ft-link-hover-decoration'    => 'underline',
    'tpl-ft-border-color'             => 'rgba(255,255,255,0.1)',

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

    'tpl-btn-compare-remove-bg'    => 'rgba(220, 53, 69, 0.9)',
    'tpl-btn-compare-remove-text'  => 'rgb(255, 255, 255)',
    'tpl-btn-compare-remove-hover' => 'rgb(200, 35, 51)',

    // ══════════════════════════════════════════════════
    // ── Floating Vergleichs-Badge ──
    // ══════════════════════════════════════════════════
    'tpl-compare-float-bg'         => 'rgb(74, 140, 42)',
    'tpl-compare-float-text'       => 'rgb(255, 255, 255)',
    'tpl-compare-float-hover-bg'   => 'rgb(56, 112, 32)',
    'tpl-compare-float-size'       => '56px',
    'tpl-compare-float-font-size'  => '1.4rem',
    'tpl-compare-float-radius'     => '50%',
    'tpl-compare-float-shadow'     => '0 4px 12px rgba(0,0,0,0.3)',
    'tpl-compare-float-count-bg'   => 'rgb(220, 53, 69)',
    'tpl-compare-float-count-text' => 'rgb(255, 255, 255)',
    'tpl-compare-float-count-size' => '22px',
    'tpl-compare-float-count-font' => '0.75rem',
    // Abstand (Margin) – Desktop
    'tpl-compare-float-margin-top'    => 'auto',
    'tpl-compare-float-margin-right'  => '20px',
    'tpl-compare-float-margin-bottom' => '80px',
    'tpl-compare-float-margin-left'   => 'auto',
    // Abstand (Margin) – Mobile
    'tpl-compare-float-mob-margin-top'    => 'auto',
    'tpl-compare-float-mob-margin-right'  => '10px',
    'tpl-compare-float-mob-margin-bottom' => '65px',
    'tpl-compare-float-mob-margin-left'   => 'auto',
    'tpl-compare-float-mob-size'           => '44px',
    'tpl-compare-float-mob-font-size'      => '1rem',

    // ══════════════════════════════════════════════════
    // ── Mobile Sidebar Navigation ──
    // ══════════════════════════════════════════════════
    'tpl-mobile-panel-bg'              => '#fafcfa',
    'tpl-mobile-header-bg'             => '#2d7a3a',
    'tpl-mobile-header-text'           => '#ffffff',
    'tpl-mobile-link-color'            => '#333333',
    'tpl-mobile-link-hover'            => '#2d7a3a',
    'tpl-mobile-link-hover-bg'         => '#edf7ee',
    'tpl-mobile-search-border'         => '#2d7a3a',
    'tpl-mobile-search-btn-bg'         => '#2d7a3a',
    'tpl-mobile-icon-color'            => '#555555',

    // ══════════════════════════════════════════════════
    // ── Floating Seedfinder-Button ──
    // ══════════════════════════════════════════════════
    'tpl-sf-float-enabled'         => '1',
    'tpl-sf-float-bg'              => 'rgb(74, 140, 42)',
    'tpl-sf-float-text'            => 'rgb(255, 255, 255)',
    'tpl-sf-float-hover-bg'        => 'rgb(56, 112, 32)',
    'tpl-sf-float-size'            => '56px',
    'tpl-sf-float-font-size'       => '1.4rem',
    'tpl-sf-float-radius'          => '50%',
    'tpl-sf-float-shadow'          => '0 4px 12px rgba(0,0,0,0.3)',
    // Abstand (Margin) – Desktop
    'tpl-sf-float-margin-top'      => 'auto',
    'tpl-sf-float-margin-right'    => 'auto',
    'tpl-sf-float-margin-bottom'   => '80px',
    'tpl-sf-float-margin-left'     => '20px',
    // Abstand (Margin) – Mobile
    'tpl-sf-float-mob-margin-top'  => 'auto',
    'tpl-sf-float-mob-margin-right'=> 'auto',
    'tpl-sf-float-mob-margin-bottom'=> '65px',
    'tpl-sf-float-mob-margin-left' => '10px',
    'tpl-sf-float-mob-size'        => '44px',
    'tpl-sf-float-mob-font-size'   => '1rem',

    // ══════════════════════════════════════════════════
    // ── Floating Filter-Button (Seedfinder Mobile) ──
    // ══════════════════════════════════════════════════
    'tpl-ff-btn-enabled'           => '1',
    'tpl-ff-btn-bg'                => 'rgb(74, 140, 42)',
    'tpl-ff-btn-text'              => 'rgb(255, 255, 255)',
    'tpl-ff-btn-hover-bg'          => 'rgb(56, 112, 32)',
    'tpl-ff-btn-size'              => '56px',
    'tpl-ff-btn-font-size'         => '1.3rem',
    'tpl-ff-btn-radius'            => '50%',
    'tpl-ff-btn-shadow'            => '0 4px 12px rgba(0,0,0,0.25)',
    // Abstand (Margin)
    'tpl-ff-btn-margin-top'        => 'auto',
    'tpl-ff-btn-margin-right'      => '20px',
    'tpl-ff-btn-margin-bottom'     => '80px',
    'tpl-ff-btn-margin-left'       => 'auto',

    // ══════════════════════════════════════════════════
    // ── Seedfinder Bottom-Bar Button ──
    // ══════════════════════════════════════════════════
    'tpl-bb-sf-bg'                     => 'rgb(74, 140, 42)',
    'tpl-bb-sf-icon'                   => 'rgb(255, 255, 255)',
    'tpl-bb-sf-text'                   => 'rgb(74, 140, 42)',
    'tpl-bb-sf-hover'                  => 'rgb(56, 112, 32)',
    'tpl-bb-sf-size'                   => '40px',
    'tpl-bb-sf-icon-size'              => '22px',
    'tpl-bb-sf-shadow'                 => 'rgba(74, 140, 42, 0.3)',
    'tpl-bb-sf-margin-top'             => '-14px',
    'tpl-bb-sf-margin-right'           => '0',
    'tpl-bb-sf-margin-bottom'          => '0',
    'tpl-bb-sf-margin-left'            => '0',
    // Bottom Bar Abstände
    'tpl-bb-padding-top'               => '0',
    'tpl-bb-padding-bottom'            => '0',
    'tpl-bb-padding-left'              => '0',
    'tpl-bb-padding-right'             => '0',
    // Seedfinder Mobile
    'tpl-bb-sf-mob-size'               => '36px',
    'tpl-bb-sf-mob-icon-size'          => '18px',
    'tpl-bb-sf-mob-margin-top'         => '-10px',
    'tpl-bb-sf-mob-margin-right'       => '0',
    'tpl-bb-sf-mob-margin-bottom'      => '0',
    'tpl-bb-sf-mob-margin-left'        => '0',

    // ══════════════════════════════════════════════════
    // ── Cannabis Badge Pills (mrh-cbadge) ──
    // ══════════════════════════════════════════════════
    'tpl-cbadge-font-size'             => '0.78rem',
    'tpl-cbadge-font-weight'           => '700',
    'tpl-cbadge-padding'               => '2px 8px',
    'tpl-cbadge-radius'                => '4px',
    'tpl-cbadge-gap'                   => '3px',
    'tpl-cbadge-icon-bg'               => '#f0f0f0',
    'tpl-cbadge-icon-text'             => '#333333',
    'tpl-cbadge-icon-font-size'        => '0.85rem',
    'tpl-cbadge-icon-padding'          => '2px 6px',
    'tpl-cbadge-icon-radius'           => '4px',

    // ══════════════════════════════════════════════════
    // ── Versandkosten-Leiste (#mrh-shipping-bar) ──
    // ══════════════════════════════════════════════════
    'tpl-shipping-bar-bg'          => 'rgb(255, 251, 235)',
    'tpl-shipping-bar-text'        => 'rgb(190, 158, 31)',
    'tpl-shipping-bar-amount'      => 'rgb(40, 167, 69)',
    'tpl-shipping-bar-font-size'   => '11px',
    'tpl-shipping-bar-font-weight' => '600',
    'tpl-shipping-bar-padding'     => '8px 0',
    'tpl-shipping-bar-track-bg'    => 'rgb(209, 250, 229)',
    'tpl-shipping-bar-track-h'     => '6px',
    'tpl-shipping-bar-track-radius'=> '999px',
    'tpl-shipping-bar-fill-bg'     => 'rgb(74, 140, 42)',
    'tpl-shipping-bar-icon-size'   => '1rem',

    // ══════════════════════════════════════════════════
    // ── Produkt-Badges (Geschlecht/Typ) ──
    // ══════════════════════════════════════════════════
    // Badge-Container (.mrh-badge-bar)
    'tpl-badge-bar-gap'            => '0.4rem',
    'tpl-badge-bar-margin'         => '0.4rem',

    // Allgemeine Badge-Basis (.mrh-type-badge)
    'tpl-badge-padding'            => '0.25rem 0.7rem',
    'tpl-badge-radius'             => '50rem',
    'tpl-badge-font-size'          => '0.8rem',
    'tpl-badge-font-weight'        => '600',
    'tpl-badge-border-width'       => '0px',
    'tpl-badge-border-color'       => 'transparent',

    // Responsive Badge-Groessen
    'tpl-badge-font-size-tablet'   => '0.75rem',
    'tpl-badge-padding-tablet'     => '0.22rem 0.6rem',
    'tpl-badge-font-size-mobile'   => '0.7rem',
    'tpl-badge-padding-mobile'     => '0.2rem 0.5rem',

    'tpl-badge-hover-enabled'      => '1',
    'tpl-badge-hover-transform'    => 'translateY(-1px)',
    'tpl-badge-hover-shadow'       => '0 2px 6px rgba(0,0,0,0.12)',

    // Feminisiert (.mrh-badge-fem)
    'tpl-badge-fem-bg'             => 'rgb(252, 91, 150)',
    'tpl-badge-fem-text'           => 'rgb(255, 255, 255)',
    'tpl-badge-fem-border'         => 'transparent',
    'tpl-badge-fem-icon'           => 'fa-venus',

    // Regulaer (.mrh-badge-reg)
    'tpl-badge-reg-bg'             => 'rgb(46, 162, 240)',
    'tpl-badge-reg-text'           => 'rgb(255, 255, 255)',
    'tpl-badge-reg-border'         => 'transparent',

    // Photoperiodisch (.mrh-badge-photo)
    'tpl-badge-photo-bg'           => 'rgb(108, 117, 125)',
    'tpl-badge-photo-text'         => 'rgb(255, 255, 255)',
    'tpl-badge-photo-border'       => 'transparent',

    // Autoflowering (.mrh-badge-auto / .picto.templatestyle)
    'tpl-badge-auto-bg'            => 'rgb(240, 253, 244)',
    'tpl-badge-auto-text'          => 'rgb(21, 128, 61)',
    'tpl-badge-auto-border'        => 'rgba(34, 197, 94, 0.25)',
    'tpl-badge-auto-icon'          => 'fa-tachometer',

    // Picto-Container (.picto.templatestyle)
    'tpl-picto-bg'                 => 'rgb(240, 253, 244)',
    'tpl-picto-text'               => 'rgb(21, 128, 61)',
    'tpl-picto-border-color'       => 'rgba(34, 197, 94, 0.25)',
    'tpl-picto-border-width'       => '1px',
    'tpl-picto-border-radius'      => '12px',
    'tpl-picto-padding'            => '8px 16px',
    'tpl-picto-margin-bottom'      => '0.75rem',
    'tpl-picto-shadow'             => 'none',
    'tpl-picto-opacity'            => '1',
    'tpl-picto-icon-size'          => '1rem',

    // ══════════════════════════════════════════════════
    // ── Hintergrundfarben (bg-*) ──
    // ══════════════════════════════════════════════════
    'tpl-bg-primary'           => 'rgb(74, 140, 42)',
    'tpl-bg-primary-text'      => 'rgb(255, 255, 255)',

    'tpl-bg-secondary'         => 'rgb(108, 117, 125)',
    'tpl-bg-secondary-text'    => 'rgb(255, 255, 255)',

    'tpl-bg-success'           => 'rgb(25, 135, 84)',
    'tpl-bg-success-text'      => 'rgb(255, 255, 255)',

    'tpl-bg-danger'            => 'rgb(220, 53, 69)',
    'tpl-bg-danger-text'       => 'rgb(255, 255, 255)',

    'tpl-bg-warning'           => 'rgb(255, 193, 7)',
    'tpl-bg-warning-text'      => 'rgb(33, 37, 41)',

    'tpl-bg-info'              => 'rgb(23, 162, 184)',
    'tpl-bg-info-text'         => 'rgb(255, 255, 255)',

    'tpl-bg-light'             => 'rgb(248, 249, 250)',
    'tpl-bg-light-text'        => 'rgb(33, 37, 41)',

    'tpl-bg-dark'              => 'rgb(33, 37, 41)',
    'tpl-bg-dark-text'         => 'rgb(255, 255, 255)',

    // ══════════════════════════════════════════════════
    // ── Typografie: Ueberschriften (h1-h6) ──
    // ══════════════════════════════════════════════════
    'tpl-h1-size'              => '2.5rem',
    'tpl-h1-color'             => 'rgb(15, 23, 42)',
    'tpl-h2-size'              => '2rem',
    'tpl-h2-color'             => 'rgb(15, 23, 42)',
    'tpl-h3-size'              => '1.75rem',
    'tpl-h3-color'             => 'rgb(15, 23, 42)',
    'tpl-h4-size'              => '1.5rem',
    'tpl-h4-color'             => 'rgb(15, 23, 42)',
    'tpl-h5-size'              => '1.25rem',
    'tpl-h5-color'             => 'rgb(15, 23, 42)',
    'tpl-h6-size'              => '1rem',
    'tpl-h6-color'             => 'rgb(15, 23, 42)',

    // ── Typografie: Fliesstext & Links ──
    'tpl-body-size'            => '1rem',
    'tpl-body-color'           => 'rgb(33, 37, 41)',
    'tpl-small-size'           => '0.875rem',
    'tpl-lead-size'            => '1.25rem',
    'tpl-link-color'           => 'rgb(74, 140, 42)',
    'tpl-link-hover'           => 'rgb(58, 112, 32)',
    'tpl-link-decoration'      => 'none',
    'tpl-link-hover-decoration'=> 'underline',

    // ══════════════════════════════════════════════════
    // ── Text-Klassen (text-*) ──
    // ══════════════════════════════════════════════════
    'tpl-text-primary'         => 'rgb(74, 140, 42)',
    'tpl-text-secondary'       => 'rgb(108, 117, 125)',
    'tpl-text-success'         => 'rgb(25, 135, 84)',
    'tpl-text-danger'          => 'rgb(220, 53, 69)',
    'tpl-text-warning'         => 'rgb(255, 193, 7)',
    'tpl-text-info'            => 'rgb(23, 162, 184)',
    'tpl-text-light'           => 'rgb(248, 249, 250)',
    'tpl-text-dark'            => 'rgb(33, 37, 41)',
    'tpl-text-muted'           => 'rgb(108, 117, 125)',
    'tpl-text-white'           => 'rgb(255, 255, 255)',

    // ══════════════════════════════════════════════════
    // ── Border-Klassen (border-*) ──
    // ══════════════════════════════════════════════════
    'tpl-border-primary'       => 'rgb(74, 140, 42)',
    'tpl-border-secondary'     => 'rgb(108, 117, 125)',
    'tpl-border-success'       => 'rgb(25, 135, 84)',
    'tpl-border-danger'        => 'rgb(220, 53, 69)',
    'tpl-border-warning'       => 'rgb(255, 193, 7)',
    'tpl-border-info'          => 'rgb(23, 162, 184)',
    'tpl-border-light'         => 'rgb(222, 226, 230)',
    'tpl-border-dark'          => 'rgb(33, 37, 41)',

    // ══════════════════════════════════════════════════
    // ── Alert-Klassen (alert-*) ──
    // ══════════════════════════════════════════════════
    'tpl-alert-primary-bg'     => 'rgb(209, 231, 197)',
    'tpl-alert-primary-text'   => 'rgb(37, 70, 21)',
    'tpl-alert-primary-border' => 'rgb(183, 218, 166)',

    'tpl-alert-secondary-bg'   => 'rgb(226, 227, 229)',
    'tpl-alert-secondary-text' => 'rgb(65, 70, 75)',
    'tpl-alert-secondary-border' => 'rgb(214, 216, 219)',

    'tpl-alert-success-bg'     => 'rgb(209, 231, 221)',
    'tpl-alert-success-text'   => 'rgb(15, 81, 50)',
    'tpl-alert-success-border' => 'rgb(186, 219, 204)',

    'tpl-alert-danger-bg'      => 'rgb(248, 215, 218)',
    'tpl-alert-danger-text'    => 'rgb(132, 32, 41)',
    'tpl-alert-danger-border'  => 'rgb(245, 198, 203)',

    'tpl-alert-warning-bg'     => 'rgb(255, 243, 205)',
    'tpl-alert-warning-text'   => 'rgb(102, 77, 3)',
    'tpl-alert-warning-border' => 'rgb(255, 238, 186)',

    'tpl-alert-info-bg'        => 'rgb(207, 244, 252)',
    'tpl-alert-info-text'      => 'rgb(14, 97, 110)',
    'tpl-alert-info-border'    => 'rgb(182, 236, 249)',

    // ══════════════════════════════════════════════════
    // ── Komponenten: Card, Form, Table ──
    // ══════════════════════════════════════════════════
    'tpl-card-bg'              => 'rgb(255, 255, 255)',
    'tpl-card-border'          => 'rgb(222, 226, 230)',
    'tpl-card-header-bg'       => 'rgb(248, 249, 250)',

    'tpl-form-focus-border'    => 'rgb(74, 140, 42)',
    'tpl-form-focus-shadow'    => 'rgba(74, 140, 42, 0.25)',

    'tpl-table-striped-bg'     => 'rgba(0, 0, 0, 0.05)',
    'tpl-table-hover-bg'       => 'rgba(0, 0, 0, 0.075)',
    'tpl-table-border'         => 'rgb(222, 226, 230)',

    // ══════════════════════════════════════════════════
    // ── Pagination ──
    // ══════════════════════════════════════════════════
    'tpl-pg-bg'                => 'transparent',
    'tpl-pg-text'              => 'rgb(55, 65, 81)',
    'tpl-pg-border'            => 'rgb(209, 213, 219)',
    'tpl-pg-hover-bg'          => 'rgb(240, 253, 244)',
    'tpl-pg-hover-text'        => 'rgb(22, 101, 52)',
    'tpl-pg-hover-border'      => 'rgb(134, 239, 172)',
    'tpl-pg-active-bg'         => 'rgb(22, 163, 74)',
    'tpl-pg-active-text'       => 'rgb(255, 255, 255)',
    'tpl-pg-active-border'     => 'rgb(22, 163, 74)',
    'tpl-pg-disabled-text'     => 'rgb(156, 163, 175)',
    'tpl-pg-disabled-border'   => 'rgb(229, 231, 235)',
    'tpl-pg-font-size'         => '0.8125rem',
    'tpl-pg-radius'            => '0.375rem',
    'tpl-pg-size'              => '2.25rem',

    // ══════════════════════════════════════════════════
    // ── Filter-Tags (aktive Filter Chips) ──
    // ══════════════════════════════════════════════════
    'tpl-filter-tag-bg'            => 'rgb(240, 240, 240)',
    'tpl-filter-tag-text'          => 'rgb(51, 51, 51)',
    'tpl-filter-tag-border'        => 'rgb(222, 226, 230)',
    'tpl-filter-tag-radius'        => '50rem',
    'tpl-filter-tag-padding'       => '3px 10px',
    'tpl-filter-tag-font-size'     => '0.8rem',
    'tpl-filter-tag-hover-bg'      => 'rgb(74, 140, 42)',
    'tpl-filter-tag-hover-text'    => 'rgb(255, 255, 255)',
    'tpl-filter-tag-hover-border'  => 'rgb(74, 140, 42)',

    // ══════════════════════════════════════════════════
    // ── Seedfinder Modal ──
    // ══════════════════════════════════════════════════
    // Modal Grundstruktur
    'tpl-sf-modal-header-bg'       => 'rgb(93, 178, 51)',
    'tpl-sf-modal-header-text'     => 'rgb(255, 255, 255)',
    'tpl-sf-modal-body-bg'         => 'rgb(255, 255, 255)',
    'tpl-sf-modal-footer-bg'       => 'rgb(248, 249, 250)',
    'tpl-sf-modal-footer-border'   => 'rgb(222, 226, 230)',
    'tpl-sf-modal-radius'          => '12px',
    'tpl-sf-modal-shadow'          => '0 10px 40px rgba(0,0,0,0.2)',

    // Tab-Navigation (filter-category-nav-desktop)
    'tpl-sf-tab-bg'                => 'transparent',
    'tpl-sf-tab-text'              => 'rgb(93, 178, 51)',
    'tpl-sf-tab-border'            => 'rgb(93, 178, 51)',
    'tpl-sf-tab-radius'            => '6px',
    'tpl-sf-tab-font-size'         => '0.85rem',
    'tpl-sf-tab-padding'           => '6px 14px',
    'tpl-sf-tab-hover-bg'          => 'rgb(93, 178, 51)',
    'tpl-sf-tab-hover-text'        => 'rgb(255, 255, 255)',
    'tpl-sf-tab-active-bg'         => 'rgb(93, 178, 51)',
    'tpl-sf-tab-active-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-tab-badge-bg'          => 'rgb(220, 53, 69)',
    'tpl-sf-tab-badge-text'        => 'rgb(255, 255, 255)',

    // Filter-Button (Filterbar links)
    'tpl-sf-btn-filter-bg'         => 'rgb(93, 178, 51)',
    'tpl-sf-btn-filter-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-btn-filter-border'     => 'rgb(93, 178, 51)',
    'tpl-sf-btn-filter-hover-bg'   => 'rgb(74, 140, 42)',
    'tpl-sf-btn-filter-hover-text' => 'rgb(255, 255, 255)',
    'tpl-sf-btn-filter-hover-border' => 'rgb(74, 140, 42)',

    // Modal Footer Buttons
    'tpl-sf-btn-reset-bg'          => 'transparent',
    'tpl-sf-btn-reset-text'        => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-border'      => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-hover-bg'    => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-hover-text'  => 'rgb(255, 255, 255)',
    'tpl-sf-btn-reset-hover-border' => 'rgb(108, 117, 125)',
    'tpl-sf-btn-search-bg'         => 'rgb(93, 178, 51)',
    'tpl-sf-btn-search-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-btn-search-border'     => 'rgb(93, 178, 51)',
    'tpl-sf-btn-search-hover-bg'   => 'rgb(74, 140, 42)',
    'tpl-sf-btn-search-hover-text' => 'rgb(255, 255, 255)',
    'tpl-sf-btn-search-hover-border' => 'rgb(74, 140, 42)',
    'tpl-sf-btn-close-bg'          => 'rgb(108, 117, 125)',
    'tpl-sf-btn-close-text'        => 'rgb(255, 255, 255)',
    'tpl-sf-btn-close-border'      => 'rgb(108, 117, 125)',
    'tpl-sf-btn-close-hover-bg'    => 'rgb(90, 98, 104)',
    'tpl-sf-btn-close-hover-text'  => 'rgb(255, 255, 255)',
    'tpl-sf-btn-close-hover-border' => 'rgb(90, 98, 104)',

    // Filter-Chips (aktive Filter im Modal)
    'tpl-sf-chip-bg'               => 'rgb(93, 178, 51)',
    'tpl-sf-chip-text'             => 'rgb(255, 255, 255)',
    'tpl-sf-chip-radius'           => '20px',
    'tpl-sf-chip-font-size'        => '0.78rem',
    'tpl-sf-chip-padding'          => '5px 10px',

    // sf-filter-tag (Product Card Filter Tags)
    'tpl-sf-filter-tag-bg'         => 'rgb(13, 110, 253)',
    'tpl-sf-filter-tag-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-filter-tag-radius'     => '4px',
    'tpl-sf-filter-tag-font-size'  => '0.75rem',
    'tpl-sf-filter-tag-padding'    => '2px 6px',

    // Checkbox
    'tpl-sf-checkbox-checked-bg'   => 'rgb(93, 178, 51)',
    'tpl-sf-checkbox-checked-border' => 'rgb(93, 178, 51)',

    // Accordion (Mobile)
    'tpl-sf-accordion-bg'          => 'rgb(248, 249, 250)',
    'tpl-sf-accordion-hover-bg'    => 'rgb(233, 236, 239)',
    'tpl-sf-accordion-active-bg'   => 'rgb(93, 178, 51)',
    'tpl-sf-accordion-active-text' => 'rgb(255, 255, 255)',
    'tpl-sf-accordion-badge-bg'    => 'rgb(220, 53, 69)',
    'tpl-sf-accordion-badge-text'  => 'rgb(255, 255, 255)',

    // FAB-Button (Mobile)
    'tpl-sf-fab-bg'                => 'rgb(93, 178, 51)',
    'tpl-sf-fab-text'              => 'rgb(255, 255, 255)',
    'tpl-sf-fab-size'              => '56px',
    'tpl-sf-fab-shadow'            => '0 4px 12px rgba(0,0,0,0.3)',
    'tpl-sf-fab-hover-bg'          => 'rgb(74, 140, 42)',
    'tpl-sf-fab-hover-text'        => 'rgb(255, 255, 255)',
    'tpl-sf-fab-badge-bg'          => 'rgb(220, 53, 69)',
    'tpl-sf-fab-badge-text'        => 'rgb(255, 255, 255)',

    // Icons (Font Awesome Klassen)
    'tpl-sf-icon-tab-main'         => 'fa-star',
    'tpl-sf-icon-tab-genetics'     => 'fa-dna',
    'tpl-sf-icon-tab-cultivation'  => 'fa-seedling',
    'tpl-sf-icon-tab-taste'        => 'fa-leaf',
    'tpl-sf-icon-tab-advanced'     => 'fa-cog',
    'tpl-sf-icon-modal-header'     => 'fa-sliders',
    'tpl-sf-icon-btn-reset'        => 'fa-undo',
    'tpl-sf-icon-btn-search'       => 'fa-search',
    'tpl-sf-icon-btn-close'        => '',
    'tpl-sf-icon-btn-filter'       => 'fa-sliders',
    'tpl-sf-icon-fab'              => 'fa-sliders',

    // Schnellfilter-Dropdowns (sf-quick-filter-bar)
    'tpl-sf-dd-bg'                 => 'rgb(255, 255, 255)',
    'tpl-sf-dd-text'               => 'rgb(51, 51, 51)',
    'tpl-sf-dd-border'             => 'rgb(222, 226, 230)',
    'tpl-sf-dd-radius'             => '6px',
    'tpl-sf-dd-font-size'          => '0.8125rem',
    'tpl-sf-dd-hover-border'       => 'rgb(13, 148, 136)',
    'tpl-sf-dd-hover-text'         => 'rgb(13, 148, 136)',
    'tpl-sf-dd-active-bg'          => 'rgb(13, 148, 136)',
    'tpl-sf-dd-active-text'        => 'rgb(255, 255, 255)',
    'tpl-sf-dd-active-border'      => 'rgb(13, 148, 136)',
    'tpl-sf-dd-menu-bg'            => 'rgb(255, 255, 255)',
    'tpl-sf-dd-menu-border'        => 'rgb(222, 226, 230)',
    'tpl-sf-dd-menu-radius'        => '8px',
    'tpl-sf-dd-badge-bg'           => 'rgb(13, 110, 253)',
    'tpl-sf-dd-badge-text'         => 'rgb(255, 255, 255)',
    'tpl-sf-dd-count-color'        => 'rgb(153, 153, 153)',

    // ══════════════════════════════════════════════════
    // ── Seedfinder Seite (Hauptseite / Kategorie-Karten) ──
    // ══════════════════════════════════════════════════
    // Kategorie-Karten
    'tpl-sf-page-card-bg'            => 'rgb(255, 255, 255)',
    'tpl-sf-page-card-border'        => 'rgb(222, 226, 230)',
    'tpl-sf-page-card-hover-border'  => 'rgb(93, 178, 51)',
    'tpl-sf-page-card-radius'        => '12px',
    'tpl-sf-page-card-shadow'        => '0 2px 8px rgba(0,0,0,0.08)',
    'tpl-sf-page-card-hover-shadow'  => '0 6px 20px rgba(0,0,0,0.12)',

    // Kategorie-Button (Auswählen)
    'tpl-sf-page-btn-bg'             => 'rgb(93, 178, 51)',
    'tpl-sf-page-btn-text'           => 'rgb(255, 255, 255)',
    'tpl-sf-page-btn-hover-bg'       => 'rgb(74, 140, 42)',
    'tpl-sf-page-btn-hover-text'     => 'rgb(255, 255, 255)',
    'tpl-sf-page-btn-radius'         => '6px',

    // Badge (X Produkte)
    'tpl-sf-page-badge-bg'           => 'rgb(93, 178, 51)',
    'tpl-sf-page-badge-text'         => 'rgb(255, 255, 255)',

    // Icon-Container
    'tpl-sf-page-icon-bg'            => 'rgb(248, 249, 250)',
    'tpl-sf-page-icon-color'         => 'rgb(93, 178, 51)',

    // Info-Card (So funktioniert der Seedfinder)
    'tpl-sf-page-info-bg'            => 'rgb(248, 255, 245)',
    'tpl-sf-page-info-border'        => 'rgb(93, 178, 51)',
    'tpl-sf-page-info-text'          => 'rgb(33, 37, 41)',

    // Benefit-Icons
    'tpl-sf-page-benefit-color'      => 'rgb(93, 178, 51)',

    // Step-Badges (1, 2, 3)
    'tpl-sf-page-step-bg'            => 'rgb(93, 178, 51)',
    'tpl-sf-page-step-text'          => 'rgb(255, 255, 255)',

    // Wizard (Anfänger-Finder)
    'tpl-sf-page-wizard-header-bg'   => 'rgb(93, 178, 51)',
    'tpl-sf-page-wizard-header-text' => 'rgb(255, 255, 255)',
    'tpl-sf-page-wizard-btn-bg'      => 'rgb(93, 178, 51)',
    'tpl-sf-page-wizard-btn-text'    => 'rgb(255, 255, 255)',
    'tpl-sf-page-wizard-btn-hover-bg' => 'rgb(74, 140, 42)',
    'tpl-sf-page-wizard-progress-bg' => 'rgb(93, 178, 51)',

    // Finder-Cards (Anfänger/Profi)
    'tpl-sf-page-finder-bg'              => 'rgb(255, 255, 255)',
    'tpl-sf-page-finder-radius'          => '16px',
    'tpl-sf-page-finder-shadow'          => '0 4px 20px rgba(0,0,0,0.1)',
    'tpl-sf-page-finder-hover-shadow'    => '0 8px 30px rgba(0,0,0,0.15)',
    'tpl-sf-page-finder-header-bg'       => 'rgb(93, 178, 51)',
    'tpl-sf-page-finder-header-bg2'      => 'rgb(109, 144, 44)',
    'tpl-sf-page-finder-header-text'     => 'rgb(255, 255, 255)',
    'tpl-sf-page-profi-header-bg'        => 'rgb(23, 162, 184)',
    'tpl-sf-page-profi-header-bg2'       => 'rgb(17, 122, 139)',

    // Benefit-Cards (im Finder)
    'tpl-sf-page-benefit-card-bg'        => 'rgb(248, 249, 250)',
    'tpl-sf-page-benefit-card-border'    => 'rgb(233, 236, 239)',
    'tpl-sf-page-benefit-card-radius'    => '12px',
    'tpl-sf-page-benefit-icon-bg'        => 'rgb(93, 178, 51)',
    'tpl-sf-page-benefit-icon-bg2'       => 'rgb(109, 144, 44)',
    'tpl-sf-page-benefit-icon-text'      => 'rgb(255, 255, 255)',
    'tpl-sf-page-profi-icon-bg'          => 'rgb(23, 162, 184)',
    'tpl-sf-page-profi-icon-bg2'         => 'rgb(17, 122, 139)',

    // Trust-Section
    'tpl-sf-page-trust-bg'               => 'rgb(240, 248, 240)',
    'tpl-sf-page-trust-border'           => 'rgb(93, 178, 51)',
    'tpl-sf-page-trust-stars'            => 'rgb(255, 193, 7)',
    'tpl-sf-page-trust-text'             => 'rgb(51, 51, 51)',
    'tpl-sf-page-profi-trust-bg'         => 'rgb(232, 244, 248)',
    'tpl-sf-page-profi-trust-border'     => 'rgb(23, 162, 184)',

    // CTA-Buttons (Finder)
    'tpl-sf-page-cta-bg'                 => 'rgb(93, 178, 51)',
    'tpl-sf-page-cta-bg2'                => 'rgb(109, 144, 44)',
    'tpl-sf-page-cta-text'               => 'rgb(255, 255, 255)',
    'tpl-sf-page-cta-radius'             => '50px',
    'tpl-sf-page-cta-shadow'             => '0 4px 12px rgba(93,178,51,0.3)',
    'tpl-sf-page-profi-cta-bg'           => 'rgb(23, 162, 184)',
    'tpl-sf-page-profi-cta-bg2'          => 'rgb(17, 122, 139)',

    // Guarantee-Text
    'tpl-sf-page-guarantee-text'         => 'rgb(136, 136, 136)',
    'tpl-sf-page-guarantee-icon'         => 'rgb(109, 144, 44)',

    // Hersteller-Cards
    'tpl-sf-page-mfr-header-bg'          => 'rgb(248, 249, 250)',
    'tpl-sf-page-mfr-border'             => 'rgb(222, 226, 230)',
    'tpl-sf-page-mfr-btn-border'         => 'rgb(93, 178, 51)',
    'tpl-sf-page-mfr-btn-text'           => 'rgb(93, 178, 51)',
    'tpl-sf-page-mfr-btn-hover-bg'       => 'rgb(93, 178, 51)',
    'tpl-sf-page-mfr-btn-hover-text'     => 'rgb(255, 255, 255)',
    'tpl-sf-page-mfr-price-color'        => 'rgb(93, 178, 51)',

    // ═══ Blog – Post-Cards ═══
    'tpl-blog-card-bg'                   => 'rgb(255, 255, 255)',
    'tpl-blog-card-border'               => 'rgb(222, 226, 230)',
    'tpl-blog-card-radius'               => '8px',
    'tpl-blog-card-shadow'               => '0 2px 8px rgba(0,0,0,0.06)',
    'tpl-blog-card-hover-shadow'         => '0 4px 16px rgba(0,0,0,0.12)',
    'tpl-blog-card-img-height'           => '180px',
    'tpl-blog-card-title-color'          => 'rgb(51, 51, 51)',
    'tpl-blog-card-title-hover'          => 'rgb(93, 178, 51)',
    'tpl-blog-card-desc-color'           => 'rgb(108, 117, 125)',
    'tpl-blog-badge-date-bg'             => 'rgb(248, 249, 250)',
    'tpl-blog-badge-date-text'           => 'rgb(108, 117, 125)',
    'tpl-blog-badge-cat-bg'              => 'rgb(93, 178, 51)',
    'tpl-blog-badge-cat-text'            => 'rgb(255, 255, 255)',
    'tpl-blog-btn-more-bg'               => 'rgba(0,0,0,0)',
    'tpl-blog-btn-more-text'             => 'rgb(93, 178, 51)',
    'tpl-blog-btn-more-border'           => 'rgb(93, 178, 51)',
    'tpl-blog-btn-more-hover-bg'         => 'rgb(93, 178, 51)',
    'tpl-blog-btn-more-hover-text'       => 'rgb(255, 255, 255)',
    'tpl-blog-btn-more-label'            => '"Weiterlesen"',
    'tpl-blog-btn-more-icon'             => '"\\f061"',

    // ═══ Blog – Kategorie-Cards ═══
    'tpl-blog-cat-bg'                    => 'rgb(255, 255, 255)',
    'tpl-blog-cat-border'                => 'rgb(222, 226, 230)',
    'tpl-blog-cat-radius'                => '8px',
    'tpl-blog-cat-shadow'                => '0 2px 8px rgba(0,0,0,0.06)',
    'tpl-blog-cat-hover-shadow'          => '0 4px 16px rgba(0,0,0,0.12)',
    'tpl-blog-cat-name-color'            => 'rgb(68, 68, 68)',
    'tpl-blog-cat-name-hover'            => 'rgb(93, 178, 51)',

    // ═══ Blog – Post-Einzelansicht ═══
    'tpl-blog-post-title-color'          => 'rgb(33, 37, 41)',
    'tpl-blog-post-meta-color'           => 'rgb(108, 117, 125)',
    'tpl-blog-post-meta-link-color'      => 'rgb(93, 178, 51)',
    'tpl-blog-post-img-radius'           => '8px',
    'tpl-blog-post-content-color'        => 'rgb(51, 51, 51)',
    'tpl-blog-post-h2-color'             => 'rgb(33, 37, 41)',
    'tpl-blog-post-link-color'           => 'rgb(93, 178, 51)',

    // === FAQ Accordion v3.0 (2026-04-17) ===
    'tpl-faq-header-bg'              => '#2C5530',
    'tpl-faq-header-gradient'        => '#3a7d40',
    'tpl-faq-header-text'            => '#ffffff',
    'tpl-faq-header-radius'          => '8px',
    'tpl-faq-subheader-bg'           => '#3a7d40',
    'tpl-faq-subheader-gradient'     => '#4a9d50',
    'tpl-faq-subheader-text'         => '#ffffff',
    'tpl-faq-card-bg'                => '#ffffff',
    'tpl-faq-card-border'            => '#e1e5e9',
    'tpl-faq-card-radius'            => '8px',
    'tpl-faq-accent'                 => '#28a745',
    'tpl-faq-btn-bg'                 => 'transparent',
    'tpl-faq-btn-text'               => '#495057',
    'tpl-faq-btn-hover-bg'           => '#f8f9fa',
    'tpl-faq-btn-hover-text'         => '#2C5530',
    'tpl-faq-btn-active-bg'          => '#2C5530',
    'tpl-faq-btn-active-text'        => '#ffffff',
    'tpl-faq-btn-active-hover'       => '#1a3a1f',
    'tpl-faq-icon-color'             => '#28a745',
    'tpl-faq-icon-active'            => 'rgba(255, 255, 255, .8)',
    'tpl-faq-chevron-bg'             => '#f0f0f0',
    'tpl-faq-chevron-color'          => '#28a745',
    'tpl-faq-chevron-active-bg'      => 'rgba(255, 255, 255, .2)',
    'tpl-faq-chevron-active-color'   => '#ffffff',
    'tpl-faq-body-bg'                => '#ffffff',
    'tpl-faq-body-border'            => '#e1e5e9',
    'tpl-faq-body-text'              => '#495057',
    'tpl-faq-grid-cols'              => '2',
    'tpl-faq-grid-gap'               => '8px',
    'tpl-faq-grid-gap-md'            => '12px',

    // ── Checkout & Warenkorb ──
    'tpl-co-progress-active-bg'      => 'rgb(74, 140, 42)',
    'tpl-co-progress-active-text'    => 'rgb(255, 255, 255)',
    'tpl-co-progress-done-bg'        => 'rgb(74, 140, 42)',
    'tpl-co-progress-done-text'      => 'rgb(255, 255, 255)',
    'tpl-co-progress-inactive-bg'    => 'rgb(233, 236, 239)',
    'tpl-co-progress-inactive-text'  => 'rgb(156, 163, 175)',
    'tpl-co-progress-line-active'    => 'rgb(74, 140, 42)',
    'tpl-co-progress-line-inactive'  => 'rgb(222, 226, 230)',
    'tpl-co-progress-circle-size'    => '48px',
    'tpl-co-progress-icon-size'      => '1.1rem',
    'tpl-co-progress-label-size'     => '0.75rem',
    'tpl-co-progress-line-height'    => '3px',
    'tpl-co-icon-shipping'           => 'fa-truck',
    'tpl-co-icon-payment'            => 'fa-credit-card',
    'tpl-co-icon-confirm'            => 'fa-clipboard-check',
    'tpl-co-icon-success'            => 'fa-circle-check',
    'tpl-co-cart-header-bg'          => 'rgb(255, 255, 255)',
    'tpl-co-cart-header-text'        => 'rgb(33, 37, 41)',
    'tpl-co-cart-total-bg'           => 'rgb(248, 249, 250)',
    'tpl-co-cart-total-text'         => 'rgb(33, 37, 41)',
    'tpl-co-cart-delete-color'       => 'rgb(220, 53, 69)',
    'tpl-co-cart-coupon-bg'          => 'rgb(255, 243, 224)',
    'tpl-co-cart-btn-kasse-bg'       => 'rgb(74, 140, 42)',
    'tpl-co-cart-btn-kasse-text'     => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-kasse-hover'    => 'rgb(56, 112, 32)',
    'tpl-co-cart-btn-schnellkauf-bg' => 'rgb(0, 150, 136)',
    'tpl-co-cart-btn-schnellkauf-text' => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-schnellkauf-hover' => 'rgb(0, 121, 107)',
    'tpl-co-cart-btn-brief-bg'       => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-brief-text'     => 'rgb(33, 37, 41)',
    'tpl-co-cart-btn-brief-border'   => 'rgb(108, 117, 125)',
    'tpl-co-cart-btn-gutschein-bg'   => 'rgb(74, 140, 42)',
    'tpl-co-cart-btn-gutschein-text' => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-weiter-bg'      => 'rgb(108, 117, 125)',
    'tpl-co-cart-btn-weiter-text'    => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-radius'         => '6px',
    'tpl-co-cart-btn-update-bg'      => 'rgb(108, 117, 125)',
    'tpl-co-cart-btn-update-text'    => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-update-icon'    => 'fa-solid fa-rotate',
    'tpl-co-cart-btn-delete-bg'      => 'rgb(220, 53, 69)',
    'tpl-co-cart-btn-delete-text'    => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-delete-icon'    => 'fa-solid fa-trash',
    'tpl-co-cart-btn-wishlist-bg'    => 'rgb(233, 30, 99)',
    'tpl-co-cart-btn-wishlist-text'  => 'rgb(255, 255, 255)',
    'tpl-co-cart-btn-wishlist-icon'  => 'fa-solid fa-heart',
    'tpl-co-btn-next-bg'             => 'rgb(74, 140, 42)',
    'tpl-co-btn-next-text'           => 'rgb(255, 255, 255)',
    'tpl-co-btn-next-hover-bg'       => 'rgb(56, 112, 32)',
    'tpl-co-btn-back-bg'             => 'rgb(108, 117, 125)',
    'tpl-co-btn-back-text'           => 'rgb(255, 255, 255)',
    'tpl-co-btn-order-bg'            => 'rgb(74, 140, 42)',
    'tpl-co-btn-order-text'          => 'rgb(255, 255, 255)',
    'tpl-co-btn-order-hover-bg'      => 'rgb(56, 112, 32)',
    'tpl-co-btn-radius'              => '6px',
    'tpl-co-heading-color'           => 'rgb(33, 37, 41)',
    'tpl-co-section-icon-color'      => 'rgb(74, 140, 42)',
    'tpl-co-edit-icon-color'         => 'rgb(96, 125, 139)',
    'tpl-co-highlight-bg'            => 'rgb(248, 249, 250)',
    'tpl-co-highlight-border'        => 'rgb(222, 226, 230)',
    'tpl-co-success-color'           => 'rgb(74, 140, 42)',
    'tpl-co-success-icon-size'       => '3rem',
    'tpl-co-success-icon-color'      => 'rgb(74, 140, 42)',
    'tpl-co-print-btn-bg'            => 'rgb(108, 117, 125)',
    'tpl-co-print-btn-text'          => 'rgb(255, 255, 255)',

    // ── Responsive Groessen (aus Live-Konfiguration uebernommen) ──
    'tpl-badge-bar-gap-md'                     => '0.35rem',
    'tpl-badge-bar-gap-sm'                     => '0.3rem',
    'tpl-badge-bar-gap-xl'                     => '0.5rem',
    'tpl-badge-bar-gap-xs'                     => '0.2rem',
    'tpl-badge-font-size-md'                   => '0.75rem',
    'tpl-badge-font-size-sm'                   => '0.7rem',
    'tpl-badge-font-size-xl'                   => '0.88rem',
    'tpl-badge-font-size-xs'                   => '0.65rem',
    'tpl-badge-gap-md'                         => '0.25rem',
    'tpl-badge-gap-sm'                         => '0.2rem',
    'tpl-badge-gap-xl'                         => '0.35rem',
    'tpl-badge-gap-xs'                         => '0.15rem',
    'tpl-badge-icon-size-md'                   => '14px',
    'tpl-badge-icon-size-sm'                   => '13px',
    'tpl-badge-icon-size-xl'                   => '16px',
    'tpl-badge-icon-size-xs'                   => '12px',
    'tpl-badge-label-size-md'                  => '0.75rem',
    'tpl-badge-label-size-sm'                  => '0.7rem',
    'tpl-badge-label-size-xl'                  => '0.88rem',
    'tpl-badge-label-size-xs'                  => '0.65rem',
    'tpl-badge-padding-md'                     => '0.2rem 0.6rem',
    'tpl-badge-padding-sm'                     => '0.18rem 0.5rem',
    'tpl-badge-padding-xl'                     => '0.3rem 0.85rem',
    'tpl-badge-padding-xs'                     => '0.15rem 0.4rem',
    'tpl-bg-contentpage'                       => 'rgb(251, 255, 255)',
    'tpl-listing-badge-font-md'                => '0.75rem',
    'tpl-listing-badge-font-sm'                => '0.7rem',
    'tpl-listing-badge-font-xl'                => '0.88rem',
    'tpl-listing-badge-font-xs'                => '0.65rem',
    'tpl-listing-badge-padding-md'             => '0.2rem 0.6rem',
    'tpl-listing-badge-padding-sm'             => '0.18rem 0.5rem',
    'tpl-listing-badge-padding-xl'             => '0.3rem 0.85rem',
    'tpl-listing-badge-padding-xs'             => '0.15rem 0.4rem',
    'tpl-listing-badges-gap-md'                => '0.35rem',
    'tpl-listing-badges-gap-sm'                => '0.3rem',
    'tpl-listing-badges-gap-xl'                => '0.5rem',
    'tpl-listing-badges-gap-xs'                => '0.2rem',
    'tpl-listing-icon-font-md'                 => '0.9rem',
    'tpl-listing-icon-font-sm'                 => '0.85rem',
    'tpl-listing-icon-font-xl'                 => '1.1rem',
    'tpl-listing-icon-font-xs'                 => '0.8rem',
    'tpl-listing-icon-size-md'                 => '24px',
    'tpl-listing-icon-size-sm'                 => '22px',
    'tpl-listing-icon-size-xl'                 => '30px',
    'tpl-listing-icon-size-xs'                 => '20px',
    'tpl-picto-font-size-md'                   => '0.75rem',
    'tpl-picto-font-size-sm'                   => '0.7rem',
    'tpl-picto-font-size-xl'                   => '0.88rem',
    'tpl-picto-font-size-xs'                   => '0.65rem',
    'tpl-picto-gap-md'                         => '6px',
    'tpl-picto-gap-sm'                         => '5px',
    'tpl-picto-gap-xl'                         => '10px',
    'tpl-picto-gap-xs'                         => '4px',
    'tpl-picto-icon-font-md'                   => '0.9rem',
    'tpl-picto-icon-font-sm'                   => '0.85rem',
    'tpl-picto-icon-font-xl'                   => '1.1rem',
    'tpl-picto-icon-font-xs'                   => '0.8rem',
    'tpl-picto-padding-md'                     => '6px 12px',
    'tpl-picto-padding-sm'                     => '5px 10px',
    'tpl-picto-padding-xl'                     => '10px 18px',
    'tpl-picto-padding-xs'                     => '4px 8px',
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
    'mrh-footer-link-color'           => 'tpl-footer-link-color',
    'mrh-footer-link-hover'           => 'tpl-footer-link-hover',
    'mrh-footer-link-decoration'      => 'tpl-footer-link-decoration',
    'mrh-footer-link-hover-decoration'=> 'tpl-footer-link-hover-decoration',
    'mrh-footer-font-size'            => 'tpl-footer-font-size',
    'mrh-footer-heading-size'         => 'tpl-footer-heading-size',
    'mrh-footer-heading-weight'       => 'tpl-footer-heading-weight',
    'mrh-footer-border-color'         => 'tpl-footer-border-color',
    // Footertext
    'mrh-ft-bg'                       => 'tpl-ft-bg',
    'mrh-ft-text-color'               => 'tpl-ft-text-color',
    'mrh-ft-heading-color'            => 'tpl-ft-heading-color',
    'mrh-ft-heading-size'             => 'tpl-ft-heading-size',
    'mrh-ft-font-size'                => 'tpl-ft-font-size',
    'mrh-ft-link-color'               => 'tpl-ft-link-color',
    'mrh-ft-link-hover'               => 'tpl-ft-link-hover',
    'mrh-ft-link-decoration'          => 'tpl-ft-link-decoration',
    'mrh-ft-link-hover-decoration'    => 'tpl-ft-link-hover-decoration',
    'mrh-ft-border-color'             => 'tpl-ft-border-color',
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
    'mrh-btn-compare-remove-bg'    => 'tpl-btn-compare-remove-bg',
    'mrh-btn-compare-remove-text'  => 'tpl-btn-compare-remove-text',
    'mrh-btn-compare-remove-hover' => 'tpl-btn-compare-remove-hover',
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

// 1. Farben speichern (MERGE: nur gesendete Keys ueberschreiben, Rest beibehalten)
if (isset($_POST['submit-colorsettings'])) {
    // Bestehende Werte als Basis laden (nicht nur Defaults!)
    $save_colors = $mrh_colors;
    foreach ($mrh_color_defaults as $key => $default) {
        // Nur Keys ueberschreiben die tatsaechlich im POST vorhanden sind
        if (isset($_POST[$key])) {
            $posted = mrh_sanitize_color($_POST[$key]);
            // !== '' statt !empty(), da PHP '0' als empty betrachtet (Toggle-Bug)
            $save_colors[$key] = ($posted !== '') ? $posted : $default;
        }
        // Fehlende Keys (nicht im POST UND nicht in bestehender JSON) -> Default
        if (!isset($save_colors[$key])) {
            $save_colors[$key] = $default;
        }
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

// =====================================================================
// === 6. Preset laden ===
// =====================================================================
if (isset($_POST['submit-load-preset']) && !empty($_POST['preset_name'])) {
    $preset_file = $presets_dir . basename($_POST['preset_name']) . '.json';
    if (file_exists($preset_file)) {
        $preset_data = json_decode(file_get_contents($preset_file), true);
        if (isset($preset_data['colors']) && is_array($preset_data['colors'])) {
            // Basis: Defaults + aktuelle colors.json (damit keine Keys verloren gehen)
            $save_colors = array_merge($mrh_color_defaults, $mrh_colors);
            // Preset-Werte ueberschreiben (ALLE tpl-* Keys, nicht nur Defaults)
            foreach ($preset_data['colors'] as $key => $val) {
                if (strpos($key, 'tpl-') === 0) {
                    $sanitized = mrh_sanitize_color($val);
                    if ($sanitized !== '') {
                        $save_colors[$key] = $sanitized;
                    }
                }
            }
            if (mrh_write_json($json_dir . 'colors.json', $save_colors)) {
                $mrh_colors = $save_colors;
                $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-check-circle me-1"></i> Preset "' . htmlspecialchars($preset_data['preset_name'] ?? basename($_POST['preset_name'])) . '" erfolgreich geladen!</div>';
                // Cache leeren
                $min_css = $tpl_dir . 'css/stylesheet.min.css';
                if (file_exists($min_css)) @unlink($min_css);
                $tpl_c = DIR_FS_CATALOG . 'templates_c/';
                if (is_dir($tpl_c)) { foreach (glob($tpl_c . '*') as $f) { if (is_file($f)) @unlink($f); } }
            } else {
                $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Laden des Presets.</div>';
            }
        }
    } else {
        $mrh_config_message = '<div class="alert alert-warning mx-3">Preset-Datei nicht gefunden.</div>';
    }
}

// =====================================================================
// === 7. Backup erstellen ===
// =====================================================================
if (isset($_POST['submit-backup'])) {
    $colors_file = $json_dir . 'colors.json';
    if (file_exists($colors_file)) {
        $backup_name = 'backup_' . date('Y-m-d_H-i-s') . '.json';
        $current_colors = mrh_read_json($colors_file);
        $backup_data = [
            'backup_name' => 'Backup vom ' . date('d.m.Y H:i'),
            'backup_date' => date('c'),
            'backup_version' => 'v3.1',
            'colors' => $current_colors,
        ];
        if (mrh_write_json($backups_dir . $backup_name, $backup_data)) {
            $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-save me-1"></i> Backup "' . $backup_name . '" erfolgreich erstellt!</div>';
        } else {
            $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Erstellen des Backups.</div>';
        }
    } else {
        $mrh_config_message = '<div class="alert alert-warning mx-3">Keine colors.json vorhanden – nichts zu sichern.</div>';
    }
}

// =====================================================================
// === 8. Backup wiederherstellen ===
// =====================================================================
if (isset($_POST['submit-restore']) && !empty($_POST['backup_file'])) {
    $backup_file = $backups_dir . basename($_POST['backup_file']);
    if (file_exists($backup_file)) {
        $backup_data = json_decode(file_get_contents($backup_file), true);
        if (isset($backup_data['colors']) && is_array($backup_data['colors'])) {
            // Basis: Defaults (damit neue Keys nicht fehlen)
            $save_colors = $mrh_color_defaults;
            // Backup-Werte ueberschreiben (ALLE tpl-* Keys, nicht nur Defaults)
            foreach ($backup_data['colors'] as $key => $val) {
                if (strpos($key, 'tpl-') === 0) {
                    $sanitized = mrh_sanitize_color($val);
                    if ($sanitized !== '') {
                        $save_colors[$key] = $sanitized;
                    }
                }
            }
            if (mrh_write_json($json_dir . 'colors.json', $save_colors)) {
                $mrh_colors = $save_colors;
                $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-undo me-1"></i> Backup "' . htmlspecialchars($backup_data['backup_name'] ?? basename($_POST['backup_file'])) . '" erfolgreich wiederhergestellt!</div>';
                // Cache leeren
                $min_css = $tpl_dir . 'css/stylesheet.min.css';
                if (file_exists($min_css)) @unlink($min_css);
                $tpl_c = DIR_FS_CATALOG . 'templates_c/';
                if (is_dir($tpl_c)) { foreach (glob($tpl_c . '*') as $f) { if (is_file($f)) @unlink($f); } }
            } else {
                $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Wiederherstellen.</div>';
            }
        }
    } else {
        $mrh_config_message = '<div class="alert alert-warning mx-3">Backup-Datei nicht gefunden.</div>';
    }
}

// =====================================================================
// === 9. Auf Standard zuruecksetzen ===
// =====================================================================
if (isset($_POST['submit-reset-defaults'])) {
    if (mrh_write_json($json_dir . 'colors.json', $mrh_color_defaults)) {
        $mrh_colors = $mrh_color_defaults;
        $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-refresh me-1"></i> Alle Farben auf Standard zurueckgesetzt!</div>';
        // Cache leeren
        $min_css = $tpl_dir . 'css/stylesheet.min.css';
        if (file_exists($min_css)) @unlink($min_css);
        $tpl_c = DIR_FS_CATALOG . 'templates_c/';
        if (is_dir($tpl_c)) { foreach (glob($tpl_c . '*') as $f) { if (is_file($f)) @unlink($f); } }
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Zuruecksetzen.</div>';
    }
}

// =====================================================================
// === Presets und Backups fuer Panel bereitstellen ===
// =====================================================================
$available_presets = [];
if (is_dir($presets_dir)) {
    foreach (glob($presets_dir . '*.json') as $pf) {
        $pd = json_decode(file_get_contents($pf), true);
        $available_presets[] = [
            'file' => basename($pf, '.json'),
            'name' => isset($pd['preset_name']) ? $pd['preset_name'] : basename($pf, '.json'),
            'description' => isset($pd['preset_description']) ? $pd['preset_description'] : '',
        ];
    }
}

$available_backups = [];
if (is_dir($backups_dir)) {
    $backup_files = glob($backups_dir . 'backup_*.json');
    rsort($backup_files); // Neueste zuerst
    foreach ($backup_files as $bf) {
        $bd = json_decode(file_get_contents($bf), true);
        $available_backups[] = [
            'file' => basename($bf),
            'name' => isset($bd['backup_name']) ? $bd['backup_name'] : basename($bf),
            'date' => isset($bd['backup_date']) ? $bd['backup_date'] : '',
        ];
    }
}

// =====================================================================
// === 10. Icon-Konfiguration ===
// =====================================================================

// Icon-Defaults aus default_icons.json laden
$icon_defaults_file = $json_dir . 'default_icons.json';
$icon_config_file   = $json_dir . 'icons.json';
$mrh_icon_defaults  = mrh_read_json($icon_defaults_file);
$mrh_icons          = mrh_read_json($icon_config_file);

// Merge: Defaults als Basis, aktive Konfiguration drueber
if (!empty($mrh_icon_defaults) && !empty($mrh_icons)) {
    // Global-Einstellungen mergen
    if (isset($mrh_icon_defaults['global'])) {
        $mrh_icons['global'] = array_merge(
            $mrh_icon_defaults['global'],
            isset($mrh_icons['global']) ? $mrh_icons['global'] : []
        );
    }
    // Icons: Defaults als Basis, aktive Konfiguration drueber
    if (isset($mrh_icon_defaults['icons'])) {
        $merged_icons = $mrh_icon_defaults['icons'];
        if (isset($mrh_icons['icons']) && is_array($mrh_icons['icons'])) {
            foreach ($mrh_icons['icons'] as $key => $val) {
                if (is_array($val)) {
                    $merged_icons[$key] = isset($merged_icons[$key])
                        ? array_merge($merged_icons[$key], $val)
                        : $val;
                }
            }
        }
        $mrh_icons['icons'] = $merged_icons;
    }
    // Areas: Defaults als Basis
    if (isset($mrh_icon_defaults['areas']) && !isset($mrh_icons['areas'])) {
        $mrh_icons['areas'] = $mrh_icon_defaults['areas'];
    }
} elseif (empty($mrh_icons) && !empty($mrh_icon_defaults)) {
    $mrh_icons = $mrh_icon_defaults;
}

/**
 * Sanitize-Funktion fuer Icon-Werte
 */
function mrh_sanitize_icon_value($key, $value) {
    $value = trim($value);
    switch ($key) {
        case 'class':
            // Nur erlaubte Zeichen: a-z, 0-9, Bindestrich
            return preg_match('/^[a-z0-9\-]+$/', $value) ? $value : '';
        case 'style':
            return in_array($value, ['solid', 'regular', 'light', 'brands']) ? $value : 'solid';
        case 'size':
            return in_array($value, ['xs', 'sm', 'md', 'lg', 'xl', '2xl']) ? $value : 'md';
        case 'color':
            // Hex-Farbe oder leer
            if (empty($value)) return '';
            if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) return $value;
            if (preg_match('/^rgb/', $value)) return mrh_sanitize_color($value);
            return '';
        case 'opacity':
            $f = floatval($value);
            return ($f >= 0 && $f <= 1) ? (string)$f : '1';
        default:
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

// 10a. Icon-Konfiguration speichern
if (isset($_POST['submit-iconsettings'])) {
    $posted_json = isset($_POST['mrh_icons_json']) ? $_POST['mrh_icons_json'] : '';
    $posted_data = json_decode(stripslashes($posted_json), true);

    if (is_array($posted_data)) {
        // Global-Einstellungen sanitizen
        if (isset($posted_data['global']) && is_array($posted_data['global'])) {
            foreach ($posted_data['global'] as $gk => $gv) {
                $posted_data['global'][$gk] = mrh_sanitize_icon_value($gk, $gv);
            }
        }
        // Icons sanitizen
        if (isset($posted_data['icons']) && is_array($posted_data['icons'])) {
            foreach ($posted_data['icons'] as $icon_key => $icon_data) {
                // Icon-Key validieren (nur icon-* erlaubt)
                if (strpos($icon_key, 'icon-') !== 0) {
                    unset($posted_data['icons'][$icon_key]);
                    continue;
                }
                if (is_array($icon_data)) {
                    foreach ($icon_data as $prop => $val) {
                        if (in_array($prop, ['class','style','size','color','opacity'])) {
                            $posted_data['icons'][$icon_key][$prop] = mrh_sanitize_icon_value($prop, $val);
                        }
                    }
                }
            }
        }
        // Areas sanitizen
        if (isset($posted_data['areas']) && is_array($posted_data['areas'])) {
            $valid_areas = ['produktlisting','produktdetail','header','warenkorb','account','footer'];
            foreach ($posted_data['areas'] as $area_key => $area_data) {
                if (!in_array($area_key, $valid_areas)) {
                    unset($posted_data['areas'][$area_key]);
                    continue;
                }
                // enabled Flag
                if (isset($area_data['enabled'])) {
                    $posted_data['areas'][$area_key]['enabled'] = (bool)$area_data['enabled'];
                }
                // Overrides sanitizen
                if (isset($area_data['overrides']) && is_array($area_data['overrides'])) {
                    foreach ($area_data['overrides'] as $ov_key => $ov_data) {
                        if (strpos($ov_key, 'icon-') !== 0) {
                            unset($posted_data['areas'][$area_key]['overrides'][$ov_key]);
                            continue;
                        }
                        if (is_array($ov_data)) {
                            foreach ($ov_data as $prop => $val) {
                                if (in_array($prop, ['class','style','size','color','opacity'])) {
                                    $posted_data['areas'][$area_key]['overrides'][$ov_key][$prop] = mrh_sanitize_icon_value($prop, $val);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Meta-Daten hinzufuegen
        $posted_data['_meta'] = [
            'version' => '1.0',
            'description' => 'MRH 2026 Icon-Konfigurator',
            'date' => date('Y-m-d H:i:s'),
        ];

        if (mrh_write_json($icon_config_file, $posted_data)) {
            $mrh_icons = $posted_data;
            $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-check-circle me-1"></i> Icon-Konfiguration erfolgreich gespeichert!</div>';
            // templates_c leeren fuer sofortige Wirkung
            $tpl_c = DIR_FS_CATALOG . 'templates_c/';
            if (is_dir($tpl_c)) {
                $files = glob($tpl_c . '*');
                foreach ($files as $f) {
                    if (is_file($f)) @unlink($f);
                }
            }
        } else {
            $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Speichern der Icon-Konfiguration.</div>';
        }
    } else {
        $mrh_config_message = '<div class="alert alert-danger mx-3">Ungueltige Icon-Daten empfangen.</div>';
    }
}

// 10b. Icon-Konfiguration auf Standard zuruecksetzen
if (isset($_POST['submit-reset-icons'])) {
    if (file_exists($icon_defaults_file)) {
        $default_data = mrh_read_json($icon_defaults_file);
        if (mrh_write_json($icon_config_file, $default_data)) {
            $mrh_icons = $default_data;
            $mrh_config_message = '<div class="alert alert-success mx-3"><i class="fa fa-undo me-1"></i> Icon-Konfiguration auf Standard zurueckgesetzt!</div>';
            // templates_c leeren
            $tpl_c = DIR_FS_CATALOG . 'templates_c/';
            if (is_dir($tpl_c)) {
                $files = glob($tpl_c . '*');
                foreach ($files as $f) {
                    if (is_file($f)) @unlink($f);
                }
            }
        } else {
            $mrh_config_message = '<div class="alert alert-danger mx-3">Fehler beim Zuruecksetzen der Icons.</div>';
        }
    }
}

// === Globals setzen ===
$GLOBALS['mrh_colors']  = $mrh_colors;
$GLOBALS['mrh_tpl']     = $mrh_tpl;
$GLOBALS['mrh_logos']   = $mrh_logos;
$GLOBALS['mrh_social']  = $mrh_social;
$GLOBALS['mrh_custom_css'] = $mrh_custom_css;
$GLOBALS['mrh_config_message'] = $mrh_config_message;
$GLOBALS['mrh_presets'] = $available_presets;
$GLOBALS['mrh_backups'] = $available_backups;
