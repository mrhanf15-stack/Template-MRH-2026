<?php
/*-----------------------------------------------------------
   MRH 2026 Template – general_bottom.js.php
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
  -----------------------------------------------------------*/
  // this javascriptfile gets included at the BOTTOM of every template page in shop
  // you can add your template specific js scripts here
  defined('DIR_TMPL') OR define('DIR_TMPL', 'templates/'.CURRENT_TEMPLATE.'/');
  defined('DIR_TMPL_JS') OR define('DIR_TMPL_JS', DIR_TMPL.'javascript/');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php
$script_array = array(
  DIR_TMPL_JS.'mrh2026.js',
);

// Cookie Consent
if (defined('MODULE_COOKIE_CONSENT_STATUS')
  && strtolower(MODULE_COOKIE_CONSENT_STATUS) == 'true'
) {
  if (is_file(DIR_FS_CATALOG.DIR_TMPL_JS.'oil.min.js')) {
    $script_array[] = DIR_TMPL_JS.'oil.min.js';
  }
}

// Social Media Shariff
if (defined('MODULE_SOCIAL_BUTTON_STATUS')
  && MODULE_SOCIAL_BUTTON_STATUS == 'true'
) {
  if (defined('MODULE_SOCIAL_BUTTON_JS_TYPE') && MODULE_SOCIAL_BUTTON_JS_TYPE == 'minified') {
    $script_array[] = DIR_WS_EXTERNAL.'shariff/shariff.min.js';
  } else if (defined('MODULE_SOCIAL_BUTTON_JS_TYPE') && MODULE_SOCIAL_BUTTON_JS_TYPE == 'standard') {
    $script_array[] = DIR_WS_EXTERNAL.'shariff/shariff.complete.js';
  }
}

$script_min = DIR_TMPL_JS.'tpl_plugins.min.js';
$this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_JS.'general_bottom.js.php');

if (COMPRESS_JAVASCRIPT == 'true') {
  require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
  $script_array = combine_files($script_array,$script_min,false,$this_f_time);
}

foreach ($script_array as $script) {
  $script .= strpos($script,$script_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$script) : '';
  echo '<script src="'.DIR_WS_BASE.$script.'" type="text/javascript"></script>'.PHP_EOL;
}

// Extra JS Dateien aus /javascript/extra/
ob_start();
foreach(auto_include(DIR_FS_CATALOG.DIR_TMPL_JS.'/extra/','php') as $file) require ($file);
$javascript = ob_get_clean();
if (COMPRESS_JAVASCRIPT == 'true') {
  if (is_file(DIR_FS_CATALOG.DIR_TMPL.'source/external/compactor/compactor.php')) {
    require_once(DIR_FS_CATALOG.DIR_TMPL.'source/external/compactor/compactor.php');
    $compactor_class = class_exists('MRH_Compactor') ? 'MRH_Compactor' : (class_exists('BS4_Compactor') ? 'BS4_Compactor' : (class_exists('Compactor') ? 'Compactor' : ''));
    if ($compactor_class != '') {
      $compactor = new $compactor_class(array('strip_php_comments' => false, 'compress_scripts' => true));
      $javascript = $compactor->squeeze($javascript);
    }
  }
}
echo $javascript.PHP_EOL;
?>
