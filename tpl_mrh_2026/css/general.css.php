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

// preload fonts
$fonts_array = array(
	'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-Regular.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-Italic.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-Bold.woff2',
	//'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-BoldItalic.woff2',
	//'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-Light.woff2',
	//'templates/'.CURRENT_TEMPLATE.'/webfonts/OpenSans-LightItalic.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/fa-brands-400.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/fa-regular-400.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/fa-solid-900.woff2',
	'templates/'.CURRENT_TEMPLATE.'/webfonts/produkt_info.woff2',
);	

for ($i = 0, $n = count($fonts_array); $i < $n; $i++) {
	if (!empty($fonts_array[$i])) {
		echo '<link rel="preload" href="'.(defined('DIR_WS_BASE') ? DIR_WS_BASE : '').$fonts_array[$i].'" as="font" type="font/woff2" crossorigin>'.PHP_EOL;
	}
}
// eof - preload fonts

if ($_SESSION['customers_status']['customers_status'] == '0') {
  echo '<link rel="stylesheet" property="stylesheet" href="'.DIR_WS_BASE.DIR_TMPL_CSS.'adminbar.css" type="text/css" media="screen" />';
}

$css_array = array();

// include bootstrap
$css_array[] = DIR_TMPL_CSS.'bootstrap/bootstrap.min.css';

// Fontawesome
// Font Awesome Pro 6
$css_array[] = DIR_TMPL_CSS.'all.min.css';

$css_array[] = DIR_TMPL_CSS.'fonts.css';

$css_array[] = DIR_TMPL.'stylesheet.css';
$css_array[] = DIR_TMPL_CSS.'navbar.css';
$css_array[] = DIR_TMPL_CSS.'bs4.css';
$css_array[] = DIR_TMPL_CSS.'pushy.min.css';

$css_array[] = DIR_TMPL_CSS.'customers_notice.css';
$css_array[] = DIR_TMPL_CSS.'blog.css';
$css_array[] = DIR_TMPL_CSS.'shop_reviews.css';
$css_array[] = DIR_TMPL_CSS.'manufacturers_overview.css';


$css_min = DIR_TMPL_CSS.'stylesheet.min.css';

$this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_CSS.'general.css.php');

if (COMPRESS_STYLESHEET == 'true') {
  require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
  $css_array = combine_files($css_array,$css_min,true,$this_f_time);
}

// Put CSS-Inline-Definitions here, these CSS-files will be loaded at the TOP of every page

foreach ($css_array as $css) {
  $css .= strpos($css,$css_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$css) : '';
  echo '<link rel="stylesheet" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
}

// MRH 2026: Konfigurierbare Farben als CSS Custom Properties laden
// Wird NACH den Stylesheets geladen, damit die Variablen die Defaults überschreiben
$mrh_color_vars = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/smarty/mrh_color_vars.php';
if (file_exists($mrh_color_vars)) {
    require($mrh_color_vars);
}
