<?php
/*-----------------------------------------------------------
   $Id:$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------
*/
define('DIR_TMPL_JS', DIR_TMPL.'javascript/');
// this javascriptfile get includes at the TOP of every template page in shop
// you can add your template specific js scripts here
?>
<script type="text/javascript">
  var DIR_WS_CATALOG = "<?php echo DIR_WS_CATALOG ?>";
  var SetSecCookie = "<?php echo ((HTTP_SERVER == HTTPS_SERVER && $request_type == 'SSL') ? true : false); ?>";
</script>
<link rel="preload" as="script" href="<?php echo DIR_WS_BASE.DIR_TMPL_JS; ?>jquery.min.js">
<script src="<?php echo DIR_WS_BASE.DIR_TMPL_JS; ?>jquery.min.js" type="text/javascript"></script>
<script>
// Passive event listeners
  $.event.special.touchstart={setup:function(e,t,n){this.addEventListener("touchstart",n,{passive:!t.includes("noPreventDefault")})}},
  $.event.special.touchmove={setup:function(e,t,n){this.addEventListener("touchmove",n,{passive:!t.includes("noPreventDefault")})}};
</script>
