<?php
/* -----------------------------------------------------------------------------------------
   $Id: loginbox.php 12894 2020-09-22 12:13:33Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(search.php,v 1.22 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (search.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce - TPC Loginbox V1 - Aubrey Kilian <aubrey@mycon.co.za>

   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// include smarty
include(DIR_FS_BOXES_INC . 'smarty_default.php');

if (!isset($_SESSION['customer_id'])) {
  // include needed functions
  require_once (DIR_FS_INC.'xtc_draw_password_field.inc.php');

    $box_smarty->assign('FORM_ACTION', xtc_draw_form('loginbox', xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="box-login"'));
    $box_smarty->assign('FIELD_EMAIL', xtc_draw_input_field('email_address', '', 'class="form-control form-control-sm" aria-label="email"'));
    $box_smarty->assign('FIELD_PWD', xtc_draw_password_field('password', '', 'class="form-control form-control-sm" aria-label="password"'));
    $box_smarty->assign('BUTTON', xtc_image_submit('button_login_small.gif', IMAGE_BUTTON_LOGIN));
    $box_smarty->assign('LINK_LOST_PASSWORD', xtc_href_link(FILENAME_PASSWORD_DOUBLE_OPT, '', 'SSL'));
    $box_smarty->assign('FORM_END', '</form>');
    // MRH 2026: Affiliate-Login Link (SEO-URL über xtc_href_link)
    $box_smarty->assign('LINK_AFFILIATE', xtc_href_link(FILENAME_AFFILIATE, '', 'SSL'));
}

// MRH 2026: Affiliate-Navigation Links wenn Partner eingeloggt
if (isset($_SESSION['affiliate_id'])) {
  $box_smarty->assign('LINK_AFFILIATE_SUMMARY', xtc_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL'));
  $box_smarty->assign('LINK_AFFILIATE_DETAILS', xtc_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'));
  $box_smarty->assign('LINK_AFFILIATE_PAYMENT', xtc_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'));
  $box_smarty->assign('LINK_AFFILIATE_CLICKS', xtc_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'));
  $box_smarty->assign('LINK_AFFILIATE_SALES', xtc_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'));
  $box_smarty->assign('LINK_AFFILIATE_BANNERS', xtc_href_link(FILENAME_AFFILIATE_BANNERS, 'type=b'));
  $box_smarty->assign('LINK_AFFILIATE_TEXTLINKS', xtc_href_link(FILENAME_AFFILIATE_BANNERS, 'type=t'));
  $box_smarty->assign('LINK_AFFILIATE_CONTACT', xtc_href_link(FILENAME_AFFILIATE_CONTACT));
  $box_smarty->assign('LINK_AFFILIATE_FAQ', xtc_href_link(FILENAME_CONTENT, 'coID=902'));
  $box_smarty->assign('LINK_AFFILIATE_LOGOUT', xtc_href_link(FILENAME_AFFILIATE_LOGOUT));
}

$box_smarty->caching = 0;
$box_loginbox = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_login.html');
$smarty->assign('box_LOGIN', $box_loginbox);
?>