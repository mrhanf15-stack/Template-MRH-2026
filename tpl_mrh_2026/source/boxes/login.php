<?php
/* -----------------------------------------------------------------------------------------
   $Id: login.php 15291 2023-07-06 11:46:25Z GTB $
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  // include smarty
  include(DIR_FS_BOXES_INC . 'smarty_default.php');
  if (!isset($_SESSION['customer_id'])) {
    // include needed functions
    require_once (DIR_FS_INC.'xtc_draw_password_field.inc.php');
    require_once (DIR_FS_INC.'secure_form.inc.php');
    $box_smarty->assign('FORM_ACTION', xtc_draw_form('loginbox', xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="box-login"').secure_form());
    $box_smarty->assign('FIELD_EMAIL', xtc_draw_input_field('email_address', '', 'maxlength="50"'));
    $box_smarty->assign('FIELD_PWD', xtc_draw_password_field('password'));
    $box_smarty->assign('BUTTON', xtc_image_submit('button_login_small.gif', IMAGE_BUTTON_LOGIN));
    $box_smarty->assign('LINK_LOST_PASSWORD', xtc_href_link(FILENAME_PASSWORD_DOUBLE_OPT, '', 'SSL'));
    $box_smarty->assign('LINK_CREATE_ACCOUNT', xtc_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    $box_smarty->assign('LINK_CREATE_GUEST_ACCOUNT', xtc_href_link(FILENAME_CREATE_GUEST_ACCOUNT, '', 'SSL'));
    $box_smarty->assign('FORM_END', '</form>');
  } else {
    $box_smarty->assign('IS_CUSTOMER', ($_SESSION['customers_status']['customers_status_id'] != DEFAULT_CUSTOMERS_STATUS_ID_GUEST) ? 1 : 0);
    $box_smarty->assign('LINK_ACCOUNT', xtc_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    $box_smarty->assign('LINK_EDIT', xtc_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
    $box_smarty->assign('LINK_ADDRESS', xtc_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    $box_smarty->assign('LINK_PASSWORD', xtc_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
    $box_smarty->assign('LINK_ORDERS', xtc_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
    if (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '1') {
      $box_smarty->assign('LINK_DELETE', xtc_href_link(FILENAME_ACCOUNT_DELETE, '', 'SSL'));
    }
    if (defined('MODULE_CHECKOUT_EXPRESS_STATUS') && MODULE_CHECKOUT_EXPRESS_STATUS == 'true') {
      $box_smarty->assign('LINK_EXPRESS', xtc_href_link(FILENAME_ACCOUNT_CHECKOUT_EXPRESS, '', 'SSL'));
    }
    if (defined('MODULE_NEWSLETTER_STATUS') && MODULE_NEWSLETTER_STATUS == 'true') {
      $box_smarty->assign('LINK_NEWSLETTER', xtc_href_link(FILENAME_NEWSLETTER, '', 'SSL'));
    }
    $box_smarty->assign('LINK_LOGOFF', xtc_href_link(FILENAME_LOGOFF, '', 'SSL'));
  }

  // MRH 2026: Affiliate-Login Link (immer setzen, mit defined()-Check)
  $affiliate_file = defined('FILENAME_AFFILIATE') ? FILENAME_AFFILIATE : 'affiliate.php';
  $box_smarty->assign('LINK_AFFILIATE', xtc_href_link($affiliate_file, '', 'SSL'));

  // MRH 2026: Affiliate-Navigation wenn Partner eingeloggt
  if (isset($_SESSION['affiliate_id'])) {
    $box_smarty->assign('LINK_AFFILIATE_SUMMARY', xtc_href_link(defined('FILENAME_AFFILIATE_SUMMARY') ? FILENAME_AFFILIATE_SUMMARY : 'affiliate_summary.php', '', 'SSL'));
    $box_smarty->assign('LINK_AFFILIATE_DETAILS', xtc_href_link(defined('FILENAME_AFFILIATE_DETAILS') ? FILENAME_AFFILIATE_DETAILS : 'affiliate_details.php', '', 'SSL'));
    $box_smarty->assign('LINK_AFFILIATE_PAYMENT', xtc_href_link(defined('FILENAME_AFFILIATE_PAYMENT') ? FILENAME_AFFILIATE_PAYMENT : 'affiliate_payment.php', '', 'SSL'));
    $box_smarty->assign('LINK_AFFILIATE_CLICKS', xtc_href_link(defined('FILENAME_AFFILIATE_CLICKS') ? FILENAME_AFFILIATE_CLICKS : 'affiliate_clicks.php', '', 'SSL'));
    $box_smarty->assign('LINK_AFFILIATE_SALES', xtc_href_link(defined('FILENAME_AFFILIATE_SALES') ? FILENAME_AFFILIATE_SALES : 'affiliate_sales.php', '', 'SSL'));
    $box_smarty->assign('LINK_AFFILIATE_BANNERS', xtc_href_link(defined('FILENAME_AFFILIATE_BANNERS') ? FILENAME_AFFILIATE_BANNERS : 'affiliate_banners.php', 'type=b'));
    $box_smarty->assign('LINK_AFFILIATE_TEXTLINKS', xtc_href_link(defined('FILENAME_AFFILIATE_BANNERS') ? FILENAME_AFFILIATE_BANNERS : 'affiliate_banners.php', 'type=t'));
    $box_smarty->assign('LINK_AFFILIATE_CONTACT', xtc_href_link(defined('FILENAME_AFFILIATE_CONTACT') ? FILENAME_AFFILIATE_CONTACT : 'affiliate_contact.php'));
    $box_smarty->assign('LINK_AFFILIATE_FAQ', xtc_href_link(FILENAME_CONTENT, 'coID=902'));
    $box_smarty->assign('LINK_AFFILIATE_LOGOUT', xtc_href_link(defined('FILENAME_AFFILIATE_LOGOUT') ? FILENAME_AFFILIATE_LOGOUT : 'affiliate_logout.php'));
  }

  $box_smarty->caching = 0;
  $box_login = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_login.html');
  
  $smarty->assign('box_LOGIN', $box_login);
