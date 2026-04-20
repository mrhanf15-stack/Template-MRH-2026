<?php
/*-----------------------------------------------------------
   $Id: general_bottom.js.php 12425 2019-11-29 16:43:02Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------
   MRH2026: Bootstrap 5.3.0 Bundle (inkl. Popper) + Vanilla JS
   - popper.min.js ENTFERNT (in bundle enthalten)
   - bootstrap.min.js ERSETZT durch bootstrap.bundle.min.js
   - owl.carousel.min.js ENTFERNT (BS5 Carousel nutzen)
   - mrh2026.js HINZUGEFUEGT (Vanilla JS Module)
   -----------------------------------------------------------
*/
// this javascriptfile get includes at the BOTTOM of every template page in shop
// you can add your template specific js scripts here
defined('DIR_TMPL_JS') OR define('DIR_TMPL_JS', DIR_TMPL.'javascript/');
?>


<?php
$script_array = array(
  // Bootstrap 5.3.0 Bundle (inkl. Popper.js) - KEINE separaten Dateien noetig
  DIR_TMPL_JS.'bootstrap.bundle.min.js',
  // MRH Core: Namespace, Event-System, BS4-Bridge, Utilities
  DIR_TMPL_JS.'mrh_core.js',
  // MRH2026 Vanilla JS Hauptmodul
  DIR_TMPL_JS.'mrh2026.js'
);
$script_min = DIR_TMPL_JS.'tpl_plugins.min.js';
  
$this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_JS.'general_bottom.js.php');
  
if (COMPRESS_JAVASCRIPT == 'true') {
  require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
  $script_array = combine_files($script_array,$script_min,false,$this_f_time);
}

foreach ($script_array as $script) {
  $script .= strpos($script,$script_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$script) : '';
  echo '<script src="'.DIR_WS_BASE.$script.'" type="text/javascript" ></script>'.PHP_EOL;
}

ob_start();
foreach(auto_include(DIR_FS_CATALOG.DIR_TMPL_JS.'/extra/','php') as $file) require ($file);
$javascript = ob_get_clean();
if (COMPRESS_JAVASCRIPT == 'true') {
  require_once(DIR_FS_EXTERNAL.'compactor/compactor.php');
  $compactor = new Compactor(array('strip_php_comments' => false, 'compress_css' => false, 'compress_scripts' => true));
  $javascript = $compactor->squeeze($javascript);
}
echo $javascript.PHP_EOL;

?>


