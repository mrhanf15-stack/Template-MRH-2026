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
    // Button: Outline
    'tpl-btn-outline-border'   => 'rgb(74, 140, 42)',
    'tpl-btn-outline-text'     => 'rgb(74, 140, 42)',
    'tpl-btn-outline-hover'    => 'rgb(74, 140, 42)',
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
  // --- Alias-Variablen --mrh-* fuer Abwaertskompatibilitaet ---
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
      if (isset($json_a[$tpl_key]) && !empty($json_a[$tpl_key])) {
          echo '--' . htmlspecialchars($mrh_key) . ':' . htmlspecialchars($json_a[$tpl_key]) . ';';
      }
  }
  // --- Berechnete Variablen ---
  echo '--mrh-header-shadow:0 1px 3px rgba(0,0,0,0.06);';
  echo '--mrh-shipping-bar-bg:rgba(240,253,244,0.8);';
  echo '--mrh-shipping-bar-text:' . htmlspecialchars($json_a['tpl-main-color-2'] ?? 'rgb(30,30,30)') . ';';
  echo '--mrh-green-accent:' . htmlspecialchars($json_a['tpl-main-color'] ?? 'rgb(74,140,42)') . ';';
  echo '--mrh-green-light:#f0fdf4;';
  echo '--mrh-nav-bg:' . htmlspecialchars($json_a['tpl-bg-color'] ?? 'rgb(255,255,255)') . ';';
  echo '--mrh-nav-hover:' . htmlspecialchars($json_a['tpl-bg-color-2'] ?? 'rgb(240,253,244)') . ';';
  echo '--mrh-badge-bg:' . htmlspecialchars($json_a['tpl-main-color'] ?? 'rgb(74,140,42)') . ';';
  echo '--mrh-badge-text:' . htmlspecialchars($json_a['tpl-text-button'] ?? 'rgb(255,255,255)') . ';';
?>
}
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