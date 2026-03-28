<?php
  /* --------------------------------------------------------------
   $Id: cookieconsent.js.php 14628 2022-07-06 10:12:08Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/

if (defined('MODULE_COOKIE_CONSENT_STATUS') && strtolower(MODULE_COOKIE_CONSENT_STATUS) == 'true') {
  $lang_links = '';
  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    require_once(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  if (count($lng->catalog_languages) > 1) {
    $lang_content = array();
    foreach ($lng->catalog_languages as $key => $value) {
      $lng_link_url = xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type);
      if ($lng_link_url != '#') {
        $lang_links .= "<a class='as-oil-lang' href='" . $lng_link_url . "'>" . $value['name'] . "</a>";
      }
    }
  }
?>
<script id="oil-configuration" type="application/configuration">
{
  "config_version": 1,
  "preview_mode": <?php echo defined('COOKIE_CONSENT_NO_TRACKING') ? 'true' : 'false'; ?>,
  "advanced_settings": true,
  "timeout": 0,
  "iabVendorListUrl": "<?php echo decode_htmlentities(xtc_href_link('ajax.php', 'ext=get_cookie_consent&speed=1&language='.$_SESSION['language_code'], $request_type, false)); ?>",
  "locale": {
    "localeId": "<?php echo $_SESSION['language_code']; ?>",
    "version": 1,
    "texts": {
      "label_intro_heading": "<?php echo TEXT_COOKIE_CONSENT_LABEL_INTRO_HEADING; ?>",
      "label_intro": "<?php echo TEXT_COOKIE_CONSENT_LABEL_INTRO; ?>",
      "label_button_yes": "<?php echo TEXT_COOKIE_CONSENT_LABEL_BUTTON_YES; ?>",
      "label_button_back": "<?php echo TEXT_COOKIE_CONSENT_LABEL_BUTTON_BACK; ?>",
      "label_button_yes_all": "<?php echo TEXT_COOKIE_CONSENT_LABEL_BUTTON_YES_ALL; ?>",
      "label_button_only_essentials": "<?php echo TEXT_COOKIE_CONSENT_LABEL_BUTTON_ESSENTIALS_ONLY; ?>",
      "label_button_advanced_settings": "<?php echo TEXT_COOKIE_CONSENT_LABEL_BUTTON_ADVANCED_SETTINGS; ?>",
      "label_cpc_heading": "<?php echo TEXT_COOKIE_CONSENT_LABEL_CPC_HEADING; ?>",
      "label_cpc_activate_all": "<?php echo TEXT_COOKIE_CONSENT_LABEL_CPC_ACTIVATE_ALL; ?>",
      "label_cpc_deactivate_all": "<?php echo TEXT_COOKIE_CONSENT_LABEL_CPC_DEACTIVATE_ALL; ?>",
      "label_nocookie_head": "<?php echo TEXT_COOKIE_CONSENT_LABEL_NOCOOKIE_HEAD; ?>",
      "label_nocookie_text": "<?php echo TEXT_COOKIE_CONSENT_LABEL_NOCOOKIE_TEXT; ?>",
      "label_third_party": " ",
      "label_imprint_links": "<?php echo $lang_links; ?><a href='<?php echo xtc_href_link(FILENAME_POPUP_CONTENT, "coID=2"); ?>' onclick='return cc_popup_content(this)'><?php echo TEXT_COOKIE_CONSENT_LABEL_INTRO_TEXT_PRIVACY; ?></a> <a href='<?php echo xtc_href_link(FILENAME_POPUP_CONTENT, "coID=4"); ?>' onclick='return cc_popup_content(this)'><?php echo TEXT_COOKIE_CONSENT_LABEL_INTRO_TEXT_IMPRINT; ?></a>"
    }
  }
}
</script>
<?php }