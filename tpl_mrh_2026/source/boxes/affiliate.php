<?php
/*------------------------------------------------------------------------------
   $Id: affiliate.php 40 2013-01-08 16:36:44Z Hubi $

   XTC-Affiliate - Contribution for XT-Commerce http://www.xt-commerce.com
   modified by http://www.netz-designer.de

   Copyright (c) 2003 - 2008 netz-designer
   -----------------------------------------------------------------------------
   Weiterentwickelt von: MRH N-Trade GmbH (MR. Hanf)
   v9.3 - Box immer anzeigen:
          Gäste: Login-Links (Info, AGB, Anmeldung)
          Partner: Volles Menü
          Titel: multilingual über .conf [affiliate_box]
   ---------------------------------------------------------------------------*/

$box_smarty = new smarty;
$box_smarty->assign('tpl_path',DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
$box_content='<ul id="categorymenu" class="list-group list-group-flush">';

if (isset($_SESSION['affiliate_id'])) {
	// === EINGELOGGT: Volles Partner-Menü ===
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . BOX_AFFILIATE_SUMMARY . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_DETAILS, '', 'SSL'). '">' . BOX_AFFILIATE_ACCOUNT . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . BOX_AFFILIATE_PAYMENT . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . BOX_AFFILIATE_CLICKRATE . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . BOX_AFFILIATE_SALES . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_BANNERS, 'type=b'). '">' . BOX_AFFILIATE_BANNERS . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_BANNERS, 'type=t'). '">' . BOX_AFFILIATE_TEXTLINKS . '</a></li>';
	if($product->isProduct()) {
		$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_BANNERS, 'individual_banner_id=' . $product->pID). '">' . BOX_AFFILIATE_PRODUCTLINK . '</a></li>';
	}
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_AFFILIATE_CONTACT). '">' . BOX_AFFILIATE_CONTACT . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_CONTENT, 'coID=902'). '">' . BOX_AFFILIATE_FAQ . '</a></li>';
	$box_content .= '<li class="list-group-item"><span class="fa fa-sign-out-alt list-group-item-danger"></span>  <a href="' . xtc_href_link(FILENAME_AFFILIATE_LOGOUT). '">' . BOX_AFFILIATE_LOGOUT . '</a></li>';
}
else {
	// === NICHT EINGELOGGT: Login-Links (Info, AGB, Anmeldung) ===
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_CONTENT,'coID=901'). '" title="' . BOX_AFFILIATE_INFO . '">' . BOX_AFFILIATE_INFO . '</a></li>';
	$box_content .= '<li class="list-group-item "><a href="' . xtc_href_link(FILENAME_CONTENT,'coID=900'). '">' . BOX_AFFILIATE_AGB . '</a></li>';
	$box_content .= '<li class="list-group-item"><span class="fa fa-sign-in-alt list-group-item-info"></span>  <a href="' . xtc_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . BOX_AFFILIATE_LOGIN . '</a></li>';
}

$box_content .= '</ul>';
$box_smarty->assign('BOX_CONTENT', $box_content);
$box_smarty->assign('language', $_SESSION['language']);

// set cache ID
$box_smarty->caching = 0;
$box_affiliate = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_affiliate.html');

$smarty->assign('box_AFFILIATE',$box_affiliate);
?>
