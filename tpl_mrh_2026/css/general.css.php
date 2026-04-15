<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.css.php 10665 2017-04-06 18:13:26Z web28 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  define('DIR_TMPL', 'templates/'.CURRENT_TEMPLATE.'/');
  define('DIR_TMPL_CSS', DIR_TMPL.'css/');

  if ($_SESSION['customers_status']['customers_status'] == '0') {
    echo '<link rel="stylesheet" property="stylesheet" href="'.DIR_WS_BASE.DIR_TMPL_CSS.'adminbar.css" type="text/css" media="screen" />';
  }

  $css_array = array(
    //DIR_TMPL.'stylesheet.css',
    //DIR_TMPL_CSS . 'jquery.mmenu.all.css', // MRH2026: deaktiviert - Mega-Menu jetzt Vanilla JS
    DIR_TMPL_CSS . 'bootstrap.min.css',
    DIR_TMPL_CSS . 'cssbuttons.css',
    DIR_TMPL_CSS . 'simple-line-icons.css',
    DIR_TMPL_CSS . 'template.css',
    DIR_TMPL_CSS . 'variables.css',
    DIR_TMPL_CSS . 'mrh-fonts.css',
    DIR_TMPL_CSS . 'mrh-custom.css',
    DIR_TMPL_CSS . 'pagination_layout.css',
    DIR_TMPL_CSS . 'mrh-product-options.css',
  );
  $css_min = DIR_TMPL.'stylesheet.min.css';

  $this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_CSS.'general.css.php');

  if (COMPRESS_STYLESHEET == 'true') {
    require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
    $css_array = combine_files($css_array,$css_min,true,$this_f_time);
  }

  // Put CSS-Inline-Definitions here, these CSS-files will be loaded at the TOP of every page
  
  foreach ($css_array as $css) {
    // Datei nur laden wenn sie existiert
    $full_path = DIR_FS_CATALOG . $css;
    if (!file_exists($full_path)) continue;
    $css .= strpos($css,$css_min) === false ? '?v=' . filemtime($full_path) : '';
    echo '<link rel="preload" as="style" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
  }
?>

<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.ttf" crossorigin="anonymous">
<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/mrh/PlusJakartaSans-Bold.woff2" type="font/woff2" crossorigin="anonymous">
<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/mrh/Inter-Regular.woff2" type="font/woff2" crossorigin="anonymous">

<?php
// Include and override colorsettings from json data
$colors_file = __DIR__ . "/../config/colors.json";
$json_a = [];
if (file_exists($colors_file)) {
    $string_colors = file_get_contents($colors_file);
    $decoded = json_decode($string_colors, true);
    if (is_array($decoded)) {
        $json_a = $decoded;
    }
}
// ======================================================================
// SINGLE SOURCE OF TRUTH: Alle Farb-Variablen-Defaults
// Wenn ein Key nicht in colors.json steht, wird dieser Default verwendet.
// Der Konfigurator (templateconfig.php) schreibt in colors.json.
// Diese Datei liest colors.json und gibt ALLE Variablen als inline
// <style>:root{}</style> aus – das ist das EINZIGE System.
// variables.css und mrh-custom.css enthalten KEINE :root-Farben mehr.
// ======================================================================
$defaults = [
    // Sekundaerfarbe
    'tpl-secondary-color'      => 'rgb(74, 140, 42)',
    // Navigation
    'tpl-menu-bg'              => 'rgb(22, 163, 74)',
    'tpl-menu-text'            => 'rgb(255, 255, 255)',
    'tpl-menu-hover'           => 'rgb(56, 112, 30)',
    'tpl-menu-active'          => 'rgb(255, 255, 255)',
    // Topbar
    'tpl-topbar-bg'            => 'rgb(30, 41, 59)',
    'tpl-topbar-text'          => 'rgb(255, 255, 255)',
    // Sticky Header
    'tpl-sticky-bg'            => 'rgb(255, 255, 255)',
    'tpl-sticky-text'          => 'rgb(51, 65, 85)',
    // Button: Primary (Warenkorb)
    'tpl-btn-primary-bg'       => 'rgb(74, 140, 42)',
    'tpl-btn-primary-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-primary-hover'    => 'rgb(58, 112, 32)',
    // Button: Secondary
    'tpl-btn-secondary-bg'     => 'rgb(108, 117, 125)',
    'tpl-btn-secondary-text'   => 'rgb(255, 255, 255)',
    'tpl-btn-secondary-hover'  => 'rgb(86, 94, 100)',
    // Button: Success
    'tpl-btn-success-bg'       => 'rgb(25, 135, 84)',
    'tpl-btn-success-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-success-hover'    => 'rgb(20, 108, 67)',
    // Button: Danger
    'tpl-btn-danger-bg'        => 'rgb(220, 53, 69)',
    'tpl-btn-danger-text'      => 'rgb(255, 255, 255)',
    'tpl-btn-danger-hover'     => 'rgb(176, 42, 55)',
    // Button: Warning
    'tpl-btn-warning-bg'       => 'rgb(255, 193, 7)',
    'tpl-btn-warning-text'     => 'rgb(33, 37, 41)',
    'tpl-btn-warning-hover'    => 'rgb(255, 202, 44)',
    // Button: Light
    'tpl-btn-light-bg'         => 'rgb(248, 249, 250)',
    'tpl-btn-light-text'       => 'rgb(33, 37, 41)',
    'tpl-btn-light-hover'      => 'rgb(211, 212, 213)',
    // Button: Dark
    'tpl-btn-dark-bg'          => 'rgb(33, 37, 41)',
    'tpl-btn-dark-text'        => 'rgb(255, 255, 255)',
    'tpl-btn-dark-hover'       => 'rgb(66, 70, 73)',
    // Button: Outline (Legacy – einzelner generischer Outline)
    'tpl-btn-outline-border'   => 'rgb(74, 140, 42)',
    'tpl-btn-outline-text'     => 'rgb(74, 140, 42)',
    'tpl-btn-outline-hover'    => 'rgb(74, 140, 42)',
    // Outline Primary
    'tpl-btn-outline-primary-bg'    => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-primary-text'  => 'rgb(74, 140, 42)',
    'tpl-btn-outline-primary-hover' => 'rgb(74, 140, 42)',
    // Outline Secondary
    'tpl-btn-outline-secondary-bg'    => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-secondary-text'  => 'rgb(108, 117, 125)',
    'tpl-btn-outline-secondary-hover' => 'rgb(108, 117, 125)',
    // Outline Success
    'tpl-btn-outline-success-bg'    => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-success-text'  => 'rgb(25, 135, 84)',
    'tpl-btn-outline-success-hover' => 'rgb(25, 135, 84)',
    // Outline Danger
    'tpl-btn-outline-danger-bg'     => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-danger-text'   => 'rgb(220, 53, 69)',
    'tpl-btn-outline-danger-hover'  => 'rgb(220, 53, 69)',
    // Outline Warning
    'tpl-btn-outline-warning-bg'    => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-warning-text'  => 'rgb(255, 193, 7)',
    'tpl-btn-outline-warning-hover' => 'rgb(255, 193, 7)',
    // Outline Info
    'tpl-btn-outline-info-bg'       => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-info-text'     => 'rgb(23, 162, 184)',
    'tpl-btn-outline-info-hover'    => 'rgb(23, 162, 184)',
    // Outline Light
    'tpl-btn-outline-light-bg'      => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-light-text'    => 'rgb(248, 249, 250)',
    'tpl-btn-outline-light-hover'   => 'rgb(248, 249, 250)',
    // Outline Dark
    'tpl-btn-outline-dark-bg'       => 'rgba(0, 0, 0, 0)',
    'tpl-btn-outline-dark-text'     => 'rgb(33, 37, 41)',
    'tpl-btn-outline-dark-hover'    => 'rgb(33, 37, 41)',
    // Button: Info
    'tpl-btn-info-bg'          => 'rgb(23, 162, 184)',
    'tpl-btn-info-text'        => 'rgb(255, 255, 255)',
    'tpl-btn-info-hover'       => 'rgb(19, 132, 150)',
    // Button: Express (Schnellkauf)
    'tpl-btn-express-bg'       => 'rgb(67, 200, 117)',
    'tpl-btn-express-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-express-hover'    => 'rgb(56, 168, 99)',
    // Button: Details (Auge-Icon)
    'tpl-btn-details-bg'       => 'rgb(255, 255, 255)',
    'tpl-btn-details-text'     => 'rgb(25, 135, 84)',
    'tpl-btn-details-hover'    => 'rgb(25, 135, 84)',
    // Button: Wishlist (Herz-Icon)
    'tpl-btn-wishlist-bg'      => 'rgb(108, 117, 125)',
    'tpl-btn-wishlist-text'    => 'rgb(255, 255, 255)',
    'tpl-btn-wishlist-hover'   => 'rgb(220, 53, 69)',
    // Button: Compare (Waage-Icon)
    'tpl-btn-compare-bg'       => 'rgb(108, 117, 125)',
    'tpl-btn-compare-text'     => 'rgb(255, 255, 255)',
    'tpl-btn-compare-hover'    => 'rgb(23, 162, 184)',
    // Button: Compare Remove (X-Button auf Vergleichsseite)
    'tpl-btn-compare-remove-bg'    => 'rgba(220, 53, 69, 0.9)',
    'tpl-btn-compare-remove-text'  => 'rgb(255, 255, 255)',
    'tpl-btn-compare-remove-hover' => 'rgb(200, 35, 51)',
    // ── Floating Vergleichs-Badge ──
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
    'tpl-compare-float-margin-top'  => 'auto',
    'tpl-compare-float-margin-right'=> '20px',
    'tpl-compare-float-margin-bottom'=> '80px',
    'tpl-compare-float-margin-left' => 'auto',
    // Abstand (Margin) – Mobile
    'tpl-compare-float-mob-margin-top'  => 'auto',
    'tpl-compare-float-mob-margin-right'=> '10px',
    'tpl-compare-float-mob-margin-bottom'=> '65px',
    'tpl-compare-float-mob-margin-left' => 'auto',
    'tpl-compare-float-mob-size'         => '44px',
    'tpl-compare-float-mob-font-size'    => '1rem',
    // ── Mobile Sidebar Navigation ──
    'tpl-mobile-panel-bg'           => '#fafcfa',
    'tpl-mobile-header-bg'          => '#2d7a3a',
    'tpl-mobile-header-text'        => '#ffffff',
    'tpl-mobile-link-color'         => '#333333',
    'tpl-mobile-link-hover'         => '#2d7a3a',
    'tpl-mobile-link-hover-bg'      => '#edf7ee',
    'tpl-mobile-search-border'      => '#2d7a3a',
    'tpl-mobile-search-btn-bg'      => '#2d7a3a',
    'tpl-mobile-icon-color'         => '#555555',
    // ── Floating Seedfinder-Button ──
    'tpl-sf-float-enabled'         => '1',
    'tpl-sf-float-bg'              => 'rgb(74, 140, 42)',
    'tpl-sf-float-text'            => 'rgb(255, 255, 255)',
    'tpl-sf-float-hover-bg'        => 'rgb(56, 112, 32)',
    'tpl-sf-float-size'            => '56px',
    'tpl-sf-float-font-size'       => '1.4rem',
    'tpl-sf-float-radius'          => '50%',
    'tpl-sf-float-shadow'          => '0 4px 12px rgba(0,0,0,0.3)',
    'tpl-sf-float-margin-top'      => 'auto',
    'tpl-sf-float-margin-right'    => 'auto',
    'tpl-sf-float-margin-bottom'   => '80px',
    'tpl-sf-float-margin-left'     => '20px',
    'tpl-sf-float-mob-margin-top'  => 'auto',
    'tpl-sf-float-mob-margin-right'=> 'auto',
    'tpl-sf-float-mob-margin-bottom'=> '65px',
    'tpl-sf-float-mob-margin-left' => '10px',
    'tpl-sf-float-mob-size'        => '44px',
    'tpl-sf-float-mob-font-size'   => '1rem',
    // ── Floating Filter-Button (Seedfinder Mobile) ──
    'tpl-ff-btn-enabled'           => '1',
    'tpl-ff-btn-bg'                => 'rgb(74, 140, 42)',
    'tpl-ff-btn-text'              => 'rgb(255, 255, 255)',
    'tpl-ff-btn-hover-bg'          => 'rgb(56, 112, 32)',
    'tpl-ff-btn-size'              => '56px',
    'tpl-ff-btn-font-size'         => '1.3rem',
    'tpl-ff-btn-radius'            => '50%',
    'tpl-ff-btn-shadow'            => '0 4px 12px rgba(0,0,0,0.25)',
    'tpl-ff-btn-margin-top'        => 'auto',
    'tpl-ff-btn-margin-right'      => '20px',
    'tpl-ff-btn-margin-bottom'     => '80px',
    'tpl-ff-btn-margin-left'       => 'auto',
    // ── Seedfinder Bottom-Bar Button ──
    'tpl-bb-sf-bg'                  => 'rgb(74, 140, 42)',
    'tpl-bb-sf-icon'                => 'rgb(255, 255, 255)',
    'tpl-bb-sf-text'                => 'rgb(74, 140, 42)',
    'tpl-bb-sf-hover'               => 'rgb(56, 112, 32)',
    'tpl-bb-sf-size'                => '40px',
    'tpl-bb-sf-icon-size'           => '22px',
    'tpl-bb-sf-shadow'              => 'rgba(74, 140, 42, 0.3)',
    'tpl-bb-sf-margin-top'          => '-14px',
    'tpl-bb-sf-margin-right'        => '0',
    'tpl-bb-sf-margin-bottom'       => '0',
    'tpl-bb-sf-margin-left'         => '0',
    // Bottom Bar Abstände
    'tpl-bb-padding-top'            => '0',
    'tpl-bb-padding-bottom'         => '0',
    'tpl-bb-padding-left'           => '0',
    'tpl-bb-padding-right'          => '0',
    // Seedfinder Mobile
    'tpl-bb-sf-mob-size'            => '36px',
    'tpl-bb-sf-mob-icon-size'       => '18px',
    'tpl-bb-sf-mob-margin-top'      => '-10px',
    'tpl-bb-sf-mob-margin-right'    => '0',
    'tpl-bb-sf-mob-margin-bottom'   => '0',
    'tpl-bb-sf-mob-margin-left'     => '0',
    // ── Cannabis Badge Pills (mrh-cbadge) ──
    'tpl-cbadge-font-size'          => '0.78rem',
    'tpl-cbadge-font-weight'        => '700',
    'tpl-cbadge-padding'            => '2px 8px',
    'tpl-cbadge-radius'             => '4px',
    'tpl-cbadge-gap'                => '3px',
    'tpl-cbadge-icon-bg'            => '#f0f0f0',
    'tpl-cbadge-icon-text'          => '#333333',
    'tpl-cbadge-icon-font-size'     => '0.85rem',
    'tpl-cbadge-icon-padding'       => '2px 6px',
    'tpl-cbadge-icon-radius'        => '4px',
    // ── Versandkosten-Leiste ──
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
    // ── Produkt-Badges (Geschlecht/Typ) ──
    'tpl-badge-bar-gap'            => '0.4rem',
    'tpl-badge-bar-margin'         => '0.4rem',
    'tpl-badge-padding'            => '0.25rem 0.7rem',
    'tpl-badge-radius'             => '50rem',
    'tpl-badge-font-size'          => '0.8rem',
    'tpl-badge-font-weight'        => '600',
    'tpl-badge-border-width'       => '0px',
    'tpl-badge-border-color'       => 'transparent',
    'tpl-badge-hover-enabled'      => '1',
    'tpl-badge-hover-transform'    => 'translateY(-1px)',
    'tpl-badge-hover-shadow'       => '0 2px 6px rgba(0,0,0,0.12)',
    'tpl-badge-fem-bg'             => 'rgb(252, 91, 150)',
    'tpl-badge-fem-text'           => 'rgb(255, 255, 255)',
    'tpl-badge-fem-border'         => 'transparent',
    'tpl-badge-fem-icon'           => 'fa-venus',
    'tpl-badge-reg-bg'             => 'rgb(46, 162, 240)',
    'tpl-badge-reg-text'           => 'rgb(255, 255, 255)',
    'tpl-badge-reg-border'         => 'transparent',
    'tpl-badge-photo-bg'           => 'rgb(108, 117, 125)',
    'tpl-badge-photo-text'         => 'rgb(255, 255, 255)',
    'tpl-badge-photo-border'       => 'transparent',
    'tpl-badge-auto-bg'            => 'rgb(240, 253, 244)',
    'tpl-badge-auto-text'          => 'rgb(21, 128, 61)',
    'tpl-badge-auto-border'        => 'rgba(34, 197, 94, 0.25)',
    'tpl-badge-auto-icon'          => 'fa-tachometer',
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
    // ── Hintergrundfarben (bg-*) ──
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
    // ── Typografie: Ueberschriften (h1-h6) ──
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
    // ── Text-Klassen (text-*) ──
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
    // ── Border-Klassen (border-*) ──
    'tpl-border-primary'       => 'rgb(74, 140, 42)',
    'tpl-border-secondary'     => 'rgb(108, 117, 125)',
    'tpl-border-success'       => 'rgb(25, 135, 84)',
    'tpl-border-danger'        => 'rgb(220, 53, 69)',
    'tpl-border-warning'       => 'rgb(255, 193, 7)',
    'tpl-border-info'          => 'rgb(23, 162, 184)',
    'tpl-border-light'         => 'rgb(222, 226, 230)',
    'tpl-border-dark'          => 'rgb(33, 37, 41)',
    // ── Alert-Klassen (alert-*) ──
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
    // ── Komponenten: Card, Form, Table ──
    'tpl-card-bg'              => 'rgb(255, 255, 255)',
    'tpl-card-border'          => 'rgb(222, 226, 230)',
    'tpl-card-header-bg'       => 'rgb(248, 249, 250)',
    'tpl-form-focus-border'    => 'rgb(74, 140, 42)',
    'tpl-form-focus-shadow'    => 'rgba(74, 140, 42, 0.25)',
    'tpl-table-striped-bg'     => 'rgba(0, 0, 0, 0.05)',
    'tpl-table-hover-bg'       => 'rgba(0, 0, 0, 0.075)',
    'tpl-table-border'         => 'rgb(222, 226, 230)',
    // ── Pagination ──
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

    // ── Filter-Tags (aktive Filter Chips) ──
    'tpl-filter-tag-bg'            => 'rgb(240, 240, 240)',
    'tpl-filter-tag-text'          => 'rgb(51, 51, 51)',
    'tpl-filter-tag-border'        => 'rgb(222, 226, 230)',
    'tpl-filter-tag-radius'        => '50rem',
    'tpl-filter-tag-padding'       => '3px 10px',
    'tpl-filter-tag-font-size'     => '0.8rem',
    'tpl-filter-tag-hover-bg'      => 'rgb(74, 140, 42)',
    'tpl-filter-tag-hover-text'    => 'rgb(255, 255, 255)',
    'tpl-filter-tag-hover-border'  => 'rgb(74, 140, 42)',

    // ── Seedfinder Modal ──
    'tpl-sf-modal-header-bg'       => 'rgb(93, 178, 51)',
    'tpl-sf-modal-header-text'     => 'rgb(255, 255, 255)',
    'tpl-sf-modal-body-bg'         => 'rgb(255, 255, 255)',
    'tpl-sf-modal-footer-bg'       => 'rgb(248, 249, 250)',
    'tpl-sf-modal-footer-border'   => 'rgb(222, 226, 230)',
    'tpl-sf-modal-radius'          => '12px',
    'tpl-sf-modal-shadow'          => '0 10px 40px rgba(0,0,0,0.2)',
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
    'tpl-sf-btn-reset-bg'          => 'transparent',
    'tpl-sf-btn-reset-text'        => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-border'      => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-hover-bg'    => 'rgb(108, 117, 125)',
    'tpl-sf-btn-reset-hover-text'  => 'rgb(255, 255, 255)',
    'tpl-sf-btn-search-bg'         => 'rgb(93, 178, 51)',
    'tpl-sf-btn-search-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-btn-search-hover-bg'   => 'rgb(74, 140, 42)',
    'tpl-sf-btn-search-hover-text' => 'rgb(255, 255, 255)',
    'tpl-sf-btn-close-bg'          => 'rgb(108, 117, 125)',
    'tpl-sf-btn-close-text'        => 'rgb(255, 255, 255)',
    'tpl-sf-btn-close-hover-bg'    => 'rgb(90, 98, 104)',
    'tpl-sf-btn-close-hover-text'  => 'rgb(255, 255, 255)',
    'tpl-sf-chip-bg'               => 'rgb(93, 178, 51)',
    'tpl-sf-chip-text'             => 'rgb(255, 255, 255)',
    'tpl-sf-chip-radius'           => '20px',
    'tpl-sf-chip-font-size'        => '0.78rem',
    'tpl-sf-chip-padding'          => '5px 10px',
    'tpl-sf-filter-tag-bg'         => 'rgb(13, 110, 253)',
    'tpl-sf-filter-tag-text'       => 'rgb(255, 255, 255)',
    'tpl-sf-filter-tag-radius'     => '4px',
    'tpl-sf-filter-tag-font-size'  => '0.75rem',
    'tpl-sf-filter-tag-padding'    => '2px 6px',
    'tpl-sf-checkbox-checked-bg'   => 'rgb(93, 178, 51)',
    'tpl-sf-checkbox-checked-border' => 'rgb(93, 178, 51)',
    'tpl-sf-accordion-bg'          => 'rgb(248, 249, 250)',
    'tpl-sf-accordion-hover-bg'    => 'rgb(233, 236, 239)',
    'tpl-sf-accordion-active-bg'   => 'rgb(93, 178, 51)',
    'tpl-sf-accordion-active-text' => 'rgb(255, 255, 255)',
    'tpl-sf-accordion-badge-bg'    => 'rgb(220, 53, 69)',
    'tpl-sf-accordion-badge-text'  => 'rgb(255, 255, 255)',
    'tpl-sf-fab-bg'                => 'rgb(93, 178, 51)',
    'tpl-sf-fab-text'              => 'rgb(255, 255, 255)',
    'tpl-sf-fab-size'              => '56px',
    'tpl-sf-fab-shadow'            => '0 4px 12px rgba(0,0,0,0.3)',
    'tpl-sf-fab-badge-bg'          => 'rgb(220, 53, 69)',
    'tpl-sf-fab-badge-text'        => 'rgb(255, 255, 255)',
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
];
// Defaults nur setzen wenn Key noch nicht in JSON vorhanden
foreach ($defaults as $dk => $dv) {
    if (!isset($json_a[$dk])) {
        $json_a[$dk] = $dv;
    }
}
// Fallback wenn JSON komplett leer – alle Keys mit Defaults befuellen
if (empty($json_a)) {
    $json_a = [
        'tpl-main-color'           => 'rgb(74, 140, 42)',
        'tpl-main-color-2'         => 'rgb(30, 30, 30)',
        'tpl-secondary-color'      => 'rgb(74, 140, 42)',
        'tpl-bg-color'             => 'rgb(255, 255, 255)',
        'tpl-bg-color-2'           => 'rgb(240, 253, 244)',
        'tpl-bg-productbox'        => 'rgb(255, 255, 255)',
        'tpl-bg-footer'            => 'rgb(15, 23, 42)',
        'tpl-text-standard'        => 'rgb(15, 23, 42)',
        'tpl-text-headings'        => 'rgb(15, 23, 42)',
        'tpl-text-button'          => 'rgb(255, 255, 255)',
        'tpl-text-footer'          => 'rgb(148, 163, 184)',
        'tpl-text-footer-headings' => 'rgb(255, 255, 255)',
    ];
    // Defaults oben werden sowieso gemergt, aber fuer Sicherheit hier auch setzen
    foreach ($defaults as $dk => $dv) {
        $json_a[$dk] = $dv;
    }
}
?>

<style id="mrh-color-vars">
:root{
  <?php
  // --- Alle --tpl-* Variablen aus colors.json + Defaults ---
  foreach ($json_a as $key => $value) {
    // submit-Buttons und Arrays ueberspringen
    if (is_array($value) || strpos($key, 'submit') !== false) continue;
    echo '--'. htmlspecialchars($key) . ':' . htmlspecialchars($value) . ';';
  }
  // --- Bidirektionales Mapping: mrh-* <-> tpl-* ---
  // Wenn mrh-* Keys in colors.json vorhanden sind (vom MRH-Konfigurator),
  // werden sie als --tpl-* Aliase ausgegeben, damit die CSS-Override-Regeln funktionieren.
  // Wenn tpl-* Keys vorhanden sind (vom alten REVplus-Konfigurator),
  // werden sie als --mrh-* Aliase ausgegeben.
  $mrh_to_tpl = [
      // Grundfarben
      'mrh-primary'              => 'tpl-main-color',
      'mrh-secondary'            => 'tpl-main-color-2',
      'mrh-bg-color'             => 'tpl-bg-color',
      'mrh-bg-color-2'           => 'tpl-bg-color-2',
      'mrh-bg-productbox'        => 'tpl-bg-productbox',
      'mrh-bg-footer'            => 'tpl-bg-footer',
      'mrh-text-standard'        => 'tpl-text-standard',
      'mrh-text-headings'        => 'tpl-text-headings',
      'mrh-text-button'          => 'tpl-text-button',
      'mrh-text-footer'          => 'tpl-text-footer',
      'mrh-text-footer-headings' => 'tpl-text-footer-headings',
      'mrh-menu-bg'              => 'tpl-menu-bg',
      'mrh-menu-text'            => 'tpl-menu-text',
      'mrh-menu-hover-bg'        => 'tpl-menu-hover',
      'mrh-menu-active-bg'       => 'tpl-menu-active',
      'mrh-topbar-bg'            => 'tpl-topbar-bg',
      'mrh-topbar-text'          => 'tpl-topbar-text',
      'mrh-sticky-bg'            => 'tpl-sticky-bg',
      'mrh-sticky-text'          => 'tpl-sticky-text',
  ];
  // Button-Mappings: mrh-btn-* -> tpl-btn-*
  $btn_types = ['primary','secondary','success','danger','warning','info','light','dark'];
  $btn_props = ['bg','text','hover'];
  foreach ($btn_types as $bt) {
      foreach ($btn_props as $bp) {
          $mrh_to_tpl['mrh-btn-'.$bt.'-'.$bp] = 'tpl-btn-'.$bt.'-'.$bp;
          $mrh_to_tpl['mrh-btn-outline-'.$bt.'-'.$bp] = 'tpl-btn-outline-'.$bt.'-'.$bp;
      }
  }
  // Spezial-Buttons
  foreach (['express','details','wishlist','compare'] as $sp) {
      foreach ($btn_props as $bp) {
          $mrh_to_tpl['mrh-btn-'.$sp.'-'.$bp] = 'tpl-btn-'.$sp.'-'.$bp;
      }
  }
  // mrh-* -> tpl-* Aliase ausgeben (MRH-Konfigurator -> CSS-Regeln)
  foreach ($mrh_to_tpl as $mrh_key => $tpl_key) {
      if (isset($json_a[$mrh_key]) && $json_a[$mrh_key] !== '') {
          // tpl-* Alias fuer CSS-Override-Regeln
          echo '--' . htmlspecialchars($tpl_key) . ':' . htmlspecialchars($json_a[$mrh_key]) . ';';
      }
  }
  // tpl-* -> mrh-* Aliase ausgeben (Abwaertskompatibilitaet)
  $tpl_to_mrh = array_flip($mrh_to_tpl);
  foreach ($tpl_to_mrh as $tpl_key => $mrh_key) {
      if (isset($json_a[$tpl_key]) && $json_a[$tpl_key] !== '') {
          echo '--' . htmlspecialchars($mrh_key) . ':' . htmlspecialchars($json_a[$tpl_key]) . ';';
      }
  }
  // --- Berechnete Variablen ---
  echo '--mrh-header-shadow:0 1px 3px rgba(0,0,0,0.06);';
  // mrh-shipping-bar Aliase: Konfigurator-Werte aus colors.json (nicht mehr hardcoded)
  echo '--mrh-shipping-bar-bg:' . htmlspecialchars($json_a['tpl-shipping-bar-bg'] ?? 'rgb(255, 251, 235)') . ';';
  echo '--mrh-shipping-bar-text:' . htmlspecialchars($json_a['tpl-shipping-bar-text'] ?? 'rgb(190, 158, 31)') . ';';
  echo '--mrh-green-accent:' . htmlspecialchars($json_a['tpl-main-color'] ?? 'rgb(74,140,42)') . ';';
  echo '--mrh-green-light:#f0fdf4;';
  echo '--mrh-nav-bg:' . htmlspecialchars($json_a['tpl-bg-color'] ?? 'rgb(255,255,255)') . ';';
  echo '--mrh-nav-hover:' . htmlspecialchars($json_a['tpl-bg-color-2'] ?? 'rgb(240,253,244)') . ';';
  echo '--mrh-badge-bg:' . htmlspecialchars($json_a['tpl-main-color'] ?? 'rgb(74,140,42)') . ';';
  echo '--mrh-badge-text:' . htmlspecialchars($json_a['tpl-text-button'] ?? 'rgb(255,255,255)') . ';';
  // Berechnete Display-Variablen fuer Ein/Aus-Toggles
  $sf_enabled = ($json_a['tpl-sf-float-enabled'] ?? '1') === '1' ? 'block' : 'none';
  echo '--tpl-sf-float-display:' . $sf_enabled . ';';
  $ff_enabled = ($json_a['tpl-ff-btn-enabled'] ?? '1') === '1' ? 'flex' : 'none';
  echo '--tpl-ff-btn-display:' . $ff_enabled . ';';
?>
}
/* ═══ Button-Overrides: CSS-Variablen → Bootstrap-Buttons ═══ */
<?php
$btn_variants = ['primary','secondary','success','danger','warning','info','light','dark'];
foreach ($btn_variants as $v) {
    $bg   = '--tpl-btn-'.$v.'-bg';
    $text = '--tpl-btn-'.$v.'-text';
    $hov  = '--tpl-btn-'.$v.'-hover';
    echo '.btn-'.$v.'{background-color:var('.$bg.') !important;color:var('.$text.') !important;border-color:var('.$bg.') !important;}';
    echo '.btn-'.$v.':hover,.btn-'.$v.':focus,.btn-'.$v.':active{background-color:var('.$hov.') !important;border-color:var('.$hov.') !important;}';
    // Outline
    $obg   = '--tpl-btn-outline-'.$v.'-bg';
    $otext = '--tpl-btn-outline-'.$v.'-text';
    $ohov  = '--tpl-btn-outline-'.$v.'-hover';
    echo '.btn-outline-'.$v.'{background-color:var('.$obg.') !important;color:var('.$otext.') !important;border-color:var('.$otext.') !important;}';
    echo '.btn-outline-'.$v.':hover,.btn-outline-'.$v.':focus,.btn-outline-'.$v.':active{background-color:var('.$ohov.') !important;color:#fff !important;border-color:var('.$ohov.') !important;}';
}
// Spezial-Buttons
$special = ['express','details','wishlist','compare'];
foreach ($special as $s) {
    $bg   = '--tpl-btn-'.$s.'-bg';
    $text = '--tpl-btn-'.$s.'-text';
    $hov  = '--tpl-btn-'.$s.'-hover';
    echo '.btn-'.$s.'{background-color:var('.$bg.');color:var('.$text.');border-color:var('.$bg.');}';    echo '.btn-'.$s.':hover,.btn-'.$s.':focus{background-color:var('.$hov.');border-color:var('.$hov.');}';}
// bg-* Hintergrundfarben-Overrides
$bg_variants = ['primary','secondary','success','danger','warning','info','light','dark'];
foreach ($bg_variants as $v) {
    $bg   = '--tpl-bg-'.$v;
    $text = '--tpl-bg-'.$v.'-text';
    echo '.bg-'.$v.'{background-color:var('.$bg.') !important;color:var('.$text.') !important;}';
}

// ═══ Typografie-Overrides: h1-h6, body, small, lead, links ═══
for ($i = 1; $i <= 6; $i++) {
    echo 'h'.$i.'{font-size:var(--tpl-h'.$i.'-size);color:var(--tpl-h'.$i.'-color);}';
}
echo 'body{font-size:var(--tpl-body-size);color:var(--tpl-body-color);}';
echo 'small,.small{font-size:var(--tpl-small-size);}';
echo '.lead{font-size:var(--tpl-lead-size);}';
echo 'a{color:var(--tpl-link-color);}a:hover{color:var(--tpl-link-hover);}';

// ═══ text-* Klassen-Overrides ═══
$text_variants = ['primary','secondary','success','danger','warning','info','light','dark','muted','white'];
foreach ($text_variants as $v) {
    echo '.text-'.$v.'{color:var(--tpl-text-'.$v.') !important;}';
}

// ═══ border-* Klassen-Overrides ═══
foreach ($bg_variants as $v) {
    echo '.border-'.$v.'{border-color:var(--tpl-border-'.$v.') !important;}';
}

// ═══ alert-* Klassen-Overrides ═══
$alert_variants = ['primary','secondary','success','danger','warning','info'];
foreach ($alert_variants as $v) {
    echo '.alert-'.$v.'{background-color:var(--tpl-alert-'.$v.'-bg) !important;color:var(--tpl-alert-'.$v.'-text) !important;border-color:var(--tpl-alert-'.$v.'-border) !important;}';
}

// ═══ Card-Overrides ═══
echo '.card{background-color:var(--tpl-card-bg);border-color:var(--tpl-card-border);}';
echo '.card-header{background-color:var(--tpl-card-header-bg);}';

// ═══ Form-Focus-Overrides ═══
echo '.form-control:focus,.form-select:focus{border-color:var(--tpl-form-focus-border) !important;box-shadow:0 0 0 .25rem var(--tpl-form-focus-shadow) !important;}';

// ═══ Table-Overrides ═══
echo '.table{border-color:var(--tpl-table-border);}';
echo '.table-striped>tbody>tr:nth-of-type(odd)>*{background-color:var(--tpl-table-striped-bg);}';
echo '.table-hover>tbody>tr:hover>*{background-color:var(--tpl-table-hover-bg);}';

// ═══ Pagination-Overrides: --tpl-pg-* → .mrh-pagination__* ═══
echo '.mrh-pagination__link{background:var(--tpl-pg-bg);color:var(--tpl-pg-text);border-color:var(--tpl-pg-border);font-size:var(--tpl-pg-font-size);border-radius:var(--tpl-pg-radius);min-width:var(--tpl-pg-size);height:var(--tpl-pg-size);}';
echo 'a.mrh-pagination__link:hover{background-color:var(--tpl-pg-hover-bg);color:var(--tpl-pg-hover-text);border-color:var(--tpl-pg-hover-border);}';
echo '.mrh-pagination__link--current{background-color:var(--tpl-pg-active-bg) !important;color:var(--tpl-pg-active-text) !important;border-color:var(--tpl-pg-active-border) !important;}';
echo '.mrh-pagination__link--disabled{color:var(--tpl-pg-disabled-text);border-color:var(--tpl-pg-disabled-border);}';

// ═══ Filter-Tag-Overrides: --tpl-filter-tag-* → .mrh-filter-tag-item a ═══
echo '.mrh-filter-tag-item a{background:var(--tpl-filter-tag-bg);color:var(--tpl-filter-tag-text);border-color:var(--tpl-filter-tag-border);border-radius:var(--tpl-filter-tag-radius);padding:var(--tpl-filter-tag-padding);font-size:var(--tpl-filter-tag-font-size);}';
echo '.mrh-filter-tag-item a:hover{background:var(--tpl-filter-tag-hover-bg);color:var(--tpl-filter-tag-hover-text);border-color:var(--tpl-filter-tag-hover-border);}';

// ═══ Seedfinder Pagination-Override: --tpl-pg-* → Bootstrap .page-link ═══
echo '.pagination .page-link{background:var(--tpl-pg-bg);color:var(--tpl-pg-text);border-color:var(--tpl-pg-border);font-size:var(--tpl-pg-font-size);border-radius:var(--tpl-pg-radius);min-width:var(--tpl-pg-size);height:var(--tpl-pg-size);display:inline-flex;align-items:center;justify-content:center;}';
echo '.pagination .page-link:hover{background-color:var(--tpl-pg-hover-bg);color:var(--tpl-pg-hover-text);border-color:var(--tpl-pg-hover-border);}';
echo '.pagination .page-item.active .page-link{background-color:var(--tpl-pg-active-bg) !important;color:var(--tpl-pg-active-text) !important;border-color:var(--tpl-pg-active-border) !important;}';
echo '.pagination .page-item.disabled .page-link{color:var(--tpl-pg-disabled-text);border-color:var(--tpl-pg-disabled-border);}';

// ═══ Seedfinder Modal-Overrides: --tpl-sf-* → #seedfinder-filter-modal + #filter-category-nav-desktop ═══
// Modal Grundstruktur
echo '#seedfinder-filter-modal .modal-content{border-radius:var(--tpl-sf-modal-radius);box-shadow:var(--tpl-sf-modal-shadow);}';
echo '#seedfinder-filter-modal .modal-header{background:var(--tpl-sf-modal-header-bg);color:var(--tpl-sf-modal-header-text);border-radius:var(--tpl-sf-modal-radius) var(--tpl-sf-modal-radius) 0 0;}';
echo '#seedfinder-filter-modal .modal-title{color:var(--tpl-sf-modal-header-text);}';
echo '#seedfinder-filter-modal .modal-body{background:var(--tpl-sf-modal-body-bg);}';
echo '#seedfinder-filter-modal .modal-footer{background:var(--tpl-sf-modal-footer-bg);border-top-color:var(--tpl-sf-modal-footer-border);border-radius:0 0 var(--tpl-sf-modal-radius) var(--tpl-sf-modal-radius);}';

// Tab-Navigation
echo '#filter-category-nav-desktop .filter-category-btn{background:var(--tpl-sf-tab-bg);color:var(--tpl-sf-tab-text);border-color:var(--tpl-sf-tab-border);border-radius:var(--tpl-sf-tab-radius) !important;font-size:var(--tpl-sf-tab-font-size);padding:var(--tpl-sf-tab-padding);}';
echo '#filter-category-nav-desktop .filter-category-btn:hover{background:var(--tpl-sf-tab-hover-bg);color:var(--tpl-sf-tab-hover-text);border-color:var(--tpl-sf-tab-hover-bg);}';
echo '#filter-category-nav-desktop .filter-category-btn.active{background:var(--tpl-sf-tab-active-bg);color:var(--tpl-sf-tab-active-text);border-color:var(--tpl-sf-tab-active-bg);}';
echo '.category-filter-badge{background:var(--tpl-sf-tab-badge-bg) !important;color:var(--tpl-sf-tab-badge-text) !important;}';

// Modal Footer Buttons
echo '#seedfinder-filter-modal #reset-filters-desktop{background:var(--tpl-sf-btn-reset-bg);color:var(--tpl-sf-btn-reset-text);border-color:var(--tpl-sf-btn-reset-border);}';
echo '#seedfinder-filter-modal #reset-filters-desktop:hover{background:var(--tpl-sf-btn-reset-hover-bg);color:var(--tpl-sf-btn-reset-hover-text);border-color:var(--tpl-sf-btn-reset-hover-bg);}';
echo '#seedfinder-filter-modal #search-filters-desktop{background:var(--tpl-sf-btn-search-bg);color:var(--tpl-sf-btn-search-text);border-color:var(--tpl-sf-btn-search-bg);}';
echo '#seedfinder-filter-modal #search-filters-desktop:hover{background:var(--tpl-sf-btn-search-hover-bg);color:var(--tpl-sf-btn-search-hover-text);border-color:var(--tpl-sf-btn-search-hover-bg);}';
echo '#seedfinder-filter-modal .modal-footer .btn-secondary:not(#reset-filters-desktop):not(#search-filters-desktop){background:var(--tpl-sf-btn-close-bg);color:var(--tpl-sf-btn-close-text);border-color:var(--tpl-sf-btn-close-bg);}';
echo '#seedfinder-filter-modal .modal-footer .btn-secondary:not(#reset-filters-desktop):not(#search-filters-desktop):hover{background:var(--tpl-sf-btn-close-hover-bg);color:var(--tpl-sf-btn-close-hover-text);}';

// Filter-Chips (aktive Filter im Modal)
echo '#active-filters-list .badge{background:var(--tpl-sf-chip-bg) !important;color:var(--tpl-sf-chip-text);border-radius:var(--tpl-sf-chip-radius);font-size:var(--tpl-sf-chip-font-size);padding:var(--tpl-sf-chip-padding);}';

// sf-filter-tag (Product Card Filter Tags)
echo '.sf-filter-tag .badge{background:var(--tpl-sf-filter-tag-bg) !important;color:var(--tpl-sf-filter-tag-text) !important;border-radius:var(--tpl-sf-filter-tag-radius);font-size:var(--tpl-sf-filter-tag-font-size);padding:var(--tpl-sf-filter-tag-padding);}';

// Checkbox
echo '#seedfinder-filter-modal .form-check-input:checked{background-color:var(--tpl-sf-checkbox-checked-bg);border-color:var(--tpl-sf-checkbox-checked-border);}';

// Accordion (Mobile)
echo '.sf-accordion-header{background:var(--tpl-sf-accordion-bg);}';
echo '.sf-accordion-header:hover{background:var(--tpl-sf-accordion-hover-bg);}';
echo '.sf-accordion-item.sf-open .sf-accordion-header{background:var(--tpl-sf-accordion-active-bg);color:var(--tpl-sf-accordion-active-text);}';
echo '.sf-accordion-badge{background:var(--tpl-sf-accordion-badge-bg);color:var(--tpl-sf-accordion-badge-text);}';

// FAB-Button (Mobile)
echo '.seedfinder-filter-fab{background:var(--tpl-sf-fab-bg);color:var(--tpl-sf-fab-text);width:var(--tpl-sf-fab-size);height:var(--tpl-sf-fab-size);box-shadow:var(--tpl-sf-fab-shadow);}';

// Schnellfilter-Dropdowns (sf-quick-filter-bar)
echo '#sf-quick-filter-bar .sf-dd-toggle{background:var(--tpl-sf-dd-bg);color:var(--tpl-sf-dd-text);border-color:var(--tpl-sf-dd-border);border-radius:var(--tpl-sf-dd-radius);font-size:var(--tpl-sf-dd-font-size);}';
echo '#sf-quick-filter-bar .sf-dd-toggle:hover,#sf-quick-filter-bar .sf-dd-toggle.sf-dd-open{border-color:var(--tpl-sf-dd-hover-border);color:var(--tpl-sf-dd-hover-text);}';
echo '#sf-quick-filter-bar .sf-dd-toggle.has-active{background:var(--tpl-sf-dd-active-bg);color:var(--tpl-sf-dd-active-text);border-color:var(--tpl-sf-dd-active-border);}';
echo '#sf-quick-filter-bar .sf-dd-menu{background:var(--tpl-sf-dd-menu-bg);border-color:var(--tpl-sf-dd-menu-border);border-radius:var(--tpl-sf-dd-menu-radius);}';
echo '#sf-quick-filter-bar .sf-quick-badge{background:var(--tpl-sf-dd-badge-bg);color:var(--tpl-sf-dd-badge-text);}';
echo '#sf-quick-filter-bar .sf-dd-count{color:var(--tpl-sf-dd-count-color);}';
echo '#sf-quick-filter-bar .mrh-btn-filter{background:var(--tpl-sf-dd-active-bg);color:var(--tpl-sf-dd-active-text);border-color:var(--tpl-sf-dd-active-border);}';
echo '#sf-quick-filter-bar .mrh-btn-filter:hover{opacity:.9;}';
// Modal Buttons mit sf-* Klassen (v12.1.0)
echo '.sf-modal-header{background:var(--tpl-sf-modal-header-bg);color:var(--tpl-sf-modal-header-text);}';
echo '.sf-modal-header .modal-title{color:var(--tpl-sf-modal-header-text);}';
echo '.sf-tab-btn{background:var(--tpl-sf-tab-bg);color:var(--tpl-sf-tab-text);border-color:var(--tpl-sf-tab-border);border-radius:var(--tpl-sf-tab-radius) !important;font-size:var(--tpl-sf-tab-font-size);padding:var(--tpl-sf-tab-padding);}';
echo '.sf-tab-btn:hover{background:var(--tpl-sf-tab-hover-bg);color:var(--tpl-sf-tab-hover-text);border-color:var(--tpl-sf-tab-hover-bg);}';
echo '.sf-tab-btn.active{background:var(--tpl-sf-tab-active-bg);color:var(--tpl-sf-tab-active-text);border-color:var(--tpl-sf-tab-active-bg);}';
echo '.sf-tab-badge{background:var(--tpl-sf-tab-badge-bg) !important;color:var(--tpl-sf-tab-badge-text) !important;}';
echo '.sf-btn-reset{background:var(--tpl-sf-btn-reset-bg);color:var(--tpl-sf-btn-reset-text);border-color:var(--tpl-sf-btn-reset-border);}';
echo '.sf-btn-reset:hover{background:var(--tpl-sf-btn-reset-hover-bg);color:var(--tpl-sf-btn-reset-hover-text);border-color:var(--tpl-sf-btn-reset-hover-bg);}';
echo '.sf-btn-search{background:var(--tpl-sf-btn-search-bg);color:var(--tpl-sf-btn-search-text);border-color:var(--tpl-sf-btn-search-bg);}';
echo '.sf-btn-search:hover{background:var(--tpl-sf-btn-search-hover-bg);color:var(--tpl-sf-btn-search-hover-text);border-color:var(--tpl-sf-btn-search-hover-bg);}';
echo '.sf-btn-close{background:var(--tpl-sf-btn-close-bg);color:var(--tpl-sf-btn-close-text);border-color:var(--tpl-sf-btn-close-bg);}';
echo '.sf-btn-close:hover{background:var(--tpl-sf-btn-close-hover-bg);color:var(--tpl-sf-btn-close-hover-text);}';

echo '.fab-badge{background:var(--tpl-sf-fab-badge-bg);color:var(--tpl-sf-fab-badge-text);}';
?>
</style>
<style>
  @font-face {
  font-family: 'simple-line-icons';
  src: url('<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.eot');
  src: url('<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.eot?#iefix') format('embedded-opentype'),
  url('<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.ttf') format('truetype'), 
  url('<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.woff') format('woff'), 
  url('<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.svg?#simple-line-icons') format('svg');
  font-weight: normal;
  font-style: normal;
  font-display:swap;
}
</style>
<?php if (strstr($PHP_SELF, FILENAME_PRODUCT_INFO )) { ?>
<link href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>unitegallery/css/unite-gallery.css" rel="stylesheet">
<link href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>css/shariff.complete.css" rel="stylesheet">
<?php } ?>


<?php
  //IE doesnt support CSS-Variables which the Template-Configuration is based on. We'll provide extra-CSS for IE-Users (also defined by the JSON-data generated in Templateconfig-Box)
  $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
  if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0; rv:11.0') !== false)) {
    include_once "ie-fixes.css.php";
  }
?>

<?php
  //add og:image for index-page for facebook template-presentation
  $demoindex = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  if($demoindex == 'www.modifiedtemplate.de/demo-revplus/') {
      echo '<meta property="og:image" content="https://' . $demoindex . 'fb_revplus_banner.jpg" />';
  }
?>

<?php
// ══════════════════════════════════════════════════════════════════
// Custom CSS aus dem Konfigurator laden (config/custom.css)
// Wird GANZ AM ENDE geladen, damit es ALLES ueberschreiben kann!
// ══════════════════════════════════════════════════════════════════
$custom_css_path = DIR_FS_CATALOG . DIR_TMPL . 'config/custom.css';
if (file_exists($custom_css_path) && filesize($custom_css_path) > 0) {
    $custom_css_url = DIR_WS_BASE . DIR_TMPL . 'config/custom.css?v=' . filemtime($custom_css_path);
    echo '<link rel="stylesheet" href="' . $custom_css_url . '" type="text/css" media="screen" />' . PHP_EOL;
}
?>