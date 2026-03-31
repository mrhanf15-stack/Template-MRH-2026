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
	DIR_TMPL_CSS . 'fivebytes.css',
  );
  $css_min = DIR_TMPL.'stylesheet.min.css';

  $this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_CSS.'general.css.php');

  if (COMPRESS_STYLESHEET == 'true') {
    require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
    $css_array = combine_files($css_array,$css_min,true,$this_f_time);
  }

  // Put CSS-Inline-Definitions here, these CSS-files will be loaded at the TOP of every page
  
  foreach ($css_array as $css) {
    $css .= strpos($css,$css_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$css) : '';
    echo '<link rel="preload" as="style" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
  }
?>
<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/Simple-Line-Icons.ttf" crossorigin="anonymous">
<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/mrh/PlusJakartaSans-Bold.woff2" type="font/woff2" crossorigin="anonymous">
<link rel="preload" as="font" href="<?php echo DIR_WS_BASE.DIR_TMPL; ?>fonts/mrh/Inter-Regular.woff2" type="font/woff2" crossorigin="anonymous">

<?php
// Include and override colorsettings from json data
// ? Alternative zu file_get_contents zum Holen der json-data ?
$string_colors = file_get_contents(__DIR__ . "/../config/colors.json",0);
$json_a = json_decode($string_colors, true);
?>

<style>
:root{
  <?php    
  foreach ($json_a as $key => $value) {
    if (!is_array($value)) {
        echo '--'. $key . ':' . $value . ';';
    } 
  }
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