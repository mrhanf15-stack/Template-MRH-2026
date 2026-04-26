<?php

require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/lang/buttons_'.$_SESSION['language'].'.php');

// Fallback-Definitionen für Konstanten die erst auf bestimmten Seiten geladen werden
defined('PRODUCTS_REVIEW_LINK') OR define('PRODUCTS_REVIEW_LINK', '');
defined('TEXT_CHECKOUT_EXPRESS_INFO_LINK') OR define('TEXT_CHECKOUT_EXPRESS_INFO_LINK', '');
defined('PRINTVIEW_INFO') OR define('PRINTVIEW_INFO', '');
defined('IMAGE_REDEEM_GIFT') OR define('IMAGE_REDEEM_GIFT', '');
defined('TEXT_PRINT') OR define('TEXT_PRINT', '');

function css_button($image, $alt, $parameters = '', $submit = false) {

    $name           = substr(basename($image), 0);
    $html           = '';
    $title          = $alt;
    $leer			= '';

    // MRH 2026 – FA7-Upgrade (22.04.2026)
    // Alle Icons auf Font Awesome 7 Syntax umgestellt: fa-solid fa-*, fa-regular fa-*
    // Verwendet <span> Tags (nicht <i>)
    /* Buttons array */
    $buttons = array(
	// default
    'default'                      	=> array('Text' => $alt,								'icon' => '',                   	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),

    // PayPal
   // 'epaypal_'.$_SESSION['language_code'].'.gif'	=> array('Text' => constant('BUTTON_EPAYPAL_'.strtoupper($_SESSION['language_code']).'_TEXT'),	'icon' => '',	'iconposition' => 'right',	'Class' => 'btn btn-paypal btn-sm  btn-block mb-2'),

	// Modified Button
	// Addressbuch
    'button_add_address.gif'        => array('Text' => $alt,								'icon' => 'fa-solid fa-square-plus',		'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Box Add a quickie
    'button_add_quick.gif'          => array('Text' => '',									'icon' => 'fa-solid fa-cart-shopping',   	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm px-4'),
	// Box Admin
    'button_admin.gif'              => array('Text' => $alt,								'icon' => 'fa-solid fa-wrench',          	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Account, Checkout, Bewertungen, Contentseiten
    'button_back.gif'               => array('Text' => $alt,								'icon' => 'fa-solid fa-arrow-left',      	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Produktseiten
    'button_buy_now.gif'            => array('Text' => '',									'icon' => 'fa-solid fa-cart-shopping',   	'iconposition' => 'left',		'Class' => 'btn mrh-btn-primary btn-sm'),
	// Mehrfachnutzung Checkout Rechnungsadresse, Lieferadresse
    'button_change_address.gif'     => array('Text' => $alt,								'icon' => 'fa-solid fa-pen-to-square',   	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Warenkorb
    'button_checkout.gif'           => array('Text' => $alt,								'icon' => 'fa-regular fa-credit-card',   	'iconposition' => 'right',		'Class' => 'btn btn-checkout btn-success btn-block'),
	// Mehrfachnutzung Nutzerkonto-Bestellhistorie, Produktdetailseiten, Warenkorb
    'button_checkout_express.gif'   => array('Text' => TEXT_CHECKOUT_EXPRESS_INFO_LINK,		'icon' => 'fa-solid fa-cart-plus',	    'iconposition' => 'left',		'Class' => 'btn mrh-btn-express btn-sm w-100'),
	// Mehrfachnutzung Downloads-Login, Payone, PayPal
    'button_confirm.gif'            => array('Text' => $alt,								'icon' => 'fa-solid fa-check',            'iconposition' => 'right',		'Class' => 'btn btn-secondary btn-sm'),
	// Checkout -> Kaufen-Button
    'button_confirm_order.gif'      => array('Text' => $alt,								'icon' => 'fa-solid fa-check',  			'iconposition' => 'right',		'Class' => 'btn btn-danger'),
	// Mehrfachnutzung Account, Checkout, Downloads, Gutschein, Login, Logout, Bewertungen, Warenkorb, Merkzettel
    'button_continue.gif'           => array('Text' => $alt,								'icon' => 'fa-solid fa-arrow-right',     	'iconposition' => 'right',		'Class' => 'btn btn-success btn-sm'),
	// Warenkorb
    'button_continue_shopping.gif'  => array('Text' => $alt,								'icon' => 'fa-solid fa-arrow-left',      	'iconposition' => 'left',		'Class' => 'btn btn-shop btn-secondary btn-block'),
	// Addressbuch
    'button_delete.gif'             => array('Text' => $alt,								'icon' => 'fa-solid fa-xmark',			'iconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
	// Produktseiten Downloads
    'button_download.gif'           => array('Text' => $alt,								'icon' => 'fa-solid fa-download',        	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Nutzerkonto-Bestellhistorie, Produktseiten, Produktdetailseiten, Bewertungen
    'button_in_cart.gif'            => array('Text' => $alt,								'icon' => 'fa-solid fa-cart-shopping',   	'iconposition' => 'left',		'Class' => 'btn mrh-btn-primary btn-sm w-100'),
	// Mehrfachnutzung Produktseiten, Produktdetailseiten
    'button_in_wishlist.gif'        => array('Text' => $alt,								'icon' => 'fa-solid fa-heart',            'iconposition' => 'left',		'Class' => 'btn mrh-btn-wishlist btn-sm w-100'),
	// Login
    'button_login.gif'              => array('Text' => $alt,								'icon' => 'fa-solid fa-user',            	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Newsletteranmeldung
    'button_login_newsletter.gif'   => array('Text' => '',									'icon' => 'fa-solid fa-share-from-square fa-lg',	'iconposition' => 'left',	'Class' => 'btn btn-secondary btn-sm'),
	// Box Login
    'button_login_small.gif'        => array('Text' => $alt,								'icon' => 'fa-solid fa-user',            	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm float-right mb-2'),
	// Nutzerkonto-Bestellhistorie
    'button_print.gif'              => array('Text' => $alt,								'icon' => 'fa-solid fa-print',           	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Produktseiten, Bewertungen
    'button_product_more.gif'       => array('Text' => $alt,								'icon' => 'fa-solid fa-eye',             	'iconposition' => 'left',		'Class' => 'btn btn-info btn-sm'),
	// Box Suche
    'button_quick_find.gif'         => array('Text' => $alt,								'icon' => '',          				'iconposition' => 'left',		'Class' => 'btn btn-outline-primary search_button'),
	// Warenkorb-Guthabenkonto
    'button_redeem.gif'             => array('Text' => $alt,								'icon' => 'fa-solid fa-asterisk',        	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm btn-block'),
	// Checkout Bestätigungsseite (Aktualisieren)
    'button_save.gif'               => array('Text' => $alt,								'icon' => 'fa-solid fa-rotate',              'iconposition' => 'left',		'Class' => 'btn btn-outline-dark btn-sm'),
	// Erweiterte Suche
    'button_search.gif'             => array('Text' => $alt,								'icon' => 'fa-solid fa-magnifying-glass',  	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Warenkorb -> Gutschein senden, Kontakt, Newsletteranmeldung
    'button_send.gif'               => array('Text' => $alt,								'icon' => 'fa-solid fa-check',            'iconposition' => 'left',		'Class' => 'btn btn-success btn-sm'),
	// Adressbuch
    'button_update.gif'             => array('Text' => $alt,								'icon' => 'fa-solid fa-rotate',	         	'iconposition' => 'right',		'Class' => 'btn btn-success btn-sm'),
	// Warenkorb -> aktualisieren
    'button_update_cart.gif'        => array('Text' => '',									'icon' => 'fa-solid fa-rotate',	         	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Produktdetailseiten -> Downloads
    'button_view.gif'               => array('Text' => $alt,								'icon' => 'fa-solid fa-eye',        	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Bewertung
    'button_write_review.gif'       => array('Text' => $alt,								'icon' => 'fa-solid fa-pen-to-square',  	'iconposition' => 'left',		'Class' => 'btn btn-info btn-sm'),
	// Mehrfachnutzung Merkzettel, Warenkorb, Box Warenkorb
    'cart_del.gif'                  => array('Text' => '',									'icon' => 'fa-solid fa-trash',      		'iconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
	// Box Bestellhistorie
    'icon_cart.png'					=> array('Text' => '',									'icon' => 'fa-solid fa-cart-shopping',	'iconposition' => 'left',		'Class' => 'btn btn-success btn-sm h-100'),
	// Mehrfachnutzung Nutzerkonto-Bestellhistorie, Checkout 'fertig', Produktinfoseiten
    'print.gif'                     => array('Text' => TEXT_PRINT,							'icon' => 'fa-solid fa-print',           	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Nutzerkonto, Nutzerkonto-Bestellhistorie
    'small_cart.gif'                => array('Text' => '',									'icon' => 'fa-solid fa-cart-shopping',   	'iconposition' => 'left',		'Class' => 'btn btn-incart btn-secondary btn-sm'),
	// Mehrfachnutzung Adressbuch, Payone
    'small_delete.gif'              => array('Text' => $alt,								'icon' => 'fa-solid fa-xmark',			'iconposition' => 'right',		'Class' => 'btn btn-danger btn-sm'),
	// Adressbuch
    'small_edit.gif'                => array('Text' => $alt,								'icon' => 'fa-solid fa-pen-to-square',  	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),
	// Mehrfachnutzung Nutzerkonto, Nutzerkonto-Bestellhistorie
    'small_express.gif'             => array('Text' => '',									'icon' => 'fa-solid fa-cart-plus',	    'iconposition' => 'left',		'Class' => 'btn mrh-btn-express btn-sm'),
    'small_view.gif'                => array('Text' => $alt,								'icon' => 'fa-solid fa-eye',        	'iconposition' => 'right',		'Class' => 'btn btn-secondary btn-sm'),
	// Merkzettel
    'wishlist_del.gif'              => array('Text' => '',									'icon' => 'fa-solid fa-trash',      		'iconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
	// Checkout (Adresse aktualisieren)
    'small_continue.gif'              => array('Text' => $alt,								'icon' => 'fa-solid fa-square-check',      'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm'),

	// BS4 Button
	// Nutzerkonto
    'account_adressbook'			=> array('Text' => $alt,								'icon' => 'fa-solid fa-address-book',		'iconposition' => 'left',		'Class' => 'btn btn-sm btn-info btn-block'),
    'account_delete'				=> array('Text' => $alt,								'icon' => 'fa-solid fa-user-minus',		'iconposition' => 'left',		'Class' => 'btn btn-sm btn-danger btn-block'),
    'account_edit'					=> array('Text' => $alt,								'icon' => 'fa-solid fa-pen-to-square',  	'iconposition' => 'left',		'Class' => 'btn btn-sm btn-outline-secondary btn-block'),
    'account_express'				=> array('Text' => $alt,								'icon' => 'fa-solid fa-cart-plus',        'iconposition' => 'left',		'Class' => 'btn btn-sm btn-primary btn-block'),
    'account_login'					=> array('Text' => $alt,								'icon' => 'fa-solid fa-user',	        	'iconposition' => 'left',		'Class' => 'btn btn-sm btn-secondary'),
    'account_newsletter'			=> array('Text' => $alt,								'icon' => 'fa-solid fa-envelope',        	'iconposition' => 'left',		'Class' => 'btn btn-sm btn-info btn-block'),
    'account_password'				=> array('Text' => $alt,								'icon' => 'fa-solid fa-lock',	        	'iconposition' => 'left',	'Class' => 'btn btn-sm btn-secondary'),
	// Mehrfachnutzung Box Warenkorb, Box Merkzettel    'box_cart'						=> array('Text' => $alt.'&nbsp;&raquo;',				'icon' => '',				       	'iconposition' => 'left',		'Class' => 'btn btn-outline-primary btn-sm'),
	// Suche -> Autovervollständigung
    'button_autocomp_next'			=> array('Text' => '&raquo;',							'icon' => '',				       	'iconposition' => 'left',		'Class' => 'btn btn-primary'),
    'button_autocomp_prev'			=> array('Text' => '&laquo;',							'icon' => '',				       	'iconposition' => 'left',		'Class' => 'btn btn-primary'),
	// Mehrfachnutzung Slider Topartikel, Slider Bestseller
    'button_carousel1'				=> array('Text' => '',									'icon' => 'fa-solid fa-chevron-left',		'iconposition' => 'left',		'Class' => 'btn btn-outline-primary mx-1'),
    'button_carousel2'				=> array('Text' => '',									'icon' => 'fa-solid fa-chevron-right',	'iconposition' => 'left',		'Class' => 'btn btn-outline-primary mx-1'),
	// Button Billiger gesehen
    'button_cheaply_see'			=> array('Text' => $alt,								'icon' => 'fa-regular fa-envelope',		'iconposition' => 'left',		'Class' => 'btn btn-xs btn-info btn-block'),
	// Button Kundenerinnerung bei Wiederverfügbarkeit
    'button_customers_remind'		=> array('Text' => $alt,								'icon' => 'fa-regular fa-bell',			'iconposition' => 'left',		'Class' => 'btn btn-sm btn-secondary btn-block'),
	// Button Easyzoom Produktdetailseiten
    'button_easyzoom'				=> array('Text' => $alt,								'icon' => 'fa-solid fa-magnifying-glass-plus',     	'iconposition' => 'right',		'Class' => 'btn btn-info btn-sm'),
	// Button Frage zum Artikel
    'button_product_inquiry'		=> array('Text' => $alt,								'icon' => 'fa-regular fa-envelope',		'iconposition' => 'left',		'Class' => 'btn btn-xs btn-info btn-block'),
	// Mehrfachnutzung Box Bestsellers, alle Produktlisten, Neue Artikel, Warenkorb -> Produkte
    'button_wishlist_now'	    	=> array('Text' => '',									'icon' => 'fa-solid fa-heart',			'iconposition' => 'left',		'Class' => 'btn mrh-btn-wishlist btn-sm'),
	// Mehrfachnutzung Bewertungen, Produktdetailseiten
    'button_write_review'			=> array('Text' => PRODUCTS_REVIEW_LINK,				'icon' => 'fa-solid fa-pen-to-square',  	'iconposition' => 'left',		'Class' => 'btn btn-info btn-xs btn-block'),
	// Warenkorb Gutscheine
    'create_account'				=> array('Text' => $alt,								'icon' => '',				     	'iconposition' => 'right',		'Class' => 'btn btn-secondary btn-sm btn-block'),
	// alle Produktdetailseiten
    'express_content'				=> array('Text' => TEXT_CHECKOUT_EXPRESS_INFO_LINK,		'icon' => 'fa-solid fa-circle-info',		'iconposition' => 'left',		'Class' => 'btn btn-info btn-xs btn-block'),
    'express_dropdown'				=> array('Text' => $alt,								'icon' => 'fa-solid fa-circle-info',		'iconposition' => 'left',		'Class' => 'btn btn-info btn-xs btn-block dropdown-toggle'),
    'express_link'					=> array('Text' => $alt,								'icon' => '',			        	'iconposition' => 'left',		'Class' => 'btn btn-info btn-xs btn-block'),
	// Warenkorb Gutschein senden
    'gift_cart'						=> array('Text' => $alt,								'icon' => 'fa-solid fa-share-from-square',		'iconposition' => 'left',		'Class' => 'btn btn-info btn-sm'),
	// Warenkorb Link zum Gutschein
    'gift_link'						=> array('Text' => IMAGE_REDEEM_GIFT,					'icon' => 'fa-solid fa-asterisk',			'iconposition' => 'left',		'Class' => 'btn btn-outline-secondary btn-block'),
	// Button go2top auf allen Seiten rechts unten
    'go2top'						=> array('Text' => '',									'icon' => 'fa-solid fa-chevron-up',		'iconposition' => 'left',		'Class' => 'btn btn-secondary'),
	// Button 'Schließen' in der Modal-Popup-Box
    'modal_close'					=> array('Text' => $alt,								'icon' => '',			        	'iconposition' => 'left',		'Class' => 'btn btn-dark btn-sm'),
	// Box Bestellhistorie
    'order_history'					=> array('Text' => $alt,								'icon' => '',			        	'iconposition' => 'left',		'Class' => 'btn btn-secondary btn-sm w-100" style="white-space:normal;'),
	// alle Produktdetailseiten
    'print_product'					=> array('Text' => PRINTVIEW_INFO,						'icon' => 'fa-solid fa-print',           	'iconposition' => 'left',		'Class' => 'btn btn-info btn-xs btn-block'),
    'print_product_small'			=> array('Text' => '',									'icon' => 'fa-solid fa-print',           	'iconposition' => 'left',		'Class' => 'btn btn-outline-info btn-sm'),
	// Responsivmenü
    'responsive_back'				=> array('Text' => '',									'icon' => 'fa-solid fa-chevron-left',		'iconposition' => 'left',		'Class' => 'btn btn-light'),
    'responsive_home'				=> array('Text' => '',									'icon' => 'fa-solid fa-house',				'iconposition' => 'left',		'Class' => 'btn btn-light'),

	// Erweiterung Anfrageliste und Ratenzahlung
    'button_in_requestlist.gif'     => array('Text' => $alt,								'icon' => 'fa-solid fa-circle-question', 	'iconposition' => 'left',		'Class' => 'btn btn-request btn-secondary btn-sm btn-block'),
    'button_requestlist.gif'        => array('Text' => '',									'icon' => 'fa-solid fa-circle-question', 	'iconposition' => 'left',		'Class' => 'btn btn-outline-info btn-sm'),
    'button_send_rental.gif'        => array('Text' => $alt,								'icon' => 'fa-solid fa-check',			'iconposition' => 'left',		'Class' => 'btn btn-rental btn-danger btn-block'),
    'requestlist_del.gif'           => array('Text' => '',									'icon' => 'fa-solid fa-trash',			'iconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),

	// fax order
    'button_fax_order.gif' => array('Text' => $alt, 'icon' => 'fa-solid fa-print', 'iconposition' => 'left', 'Class' => 'btn btn-outline-info btn-block'),

    );

	// default
    if (!array_key_exists($name, $buttons)) {$name = 'default';}

    // kein Submitbutton
    if (!$submit)
    {
        $html .= '<span';
        if ($buttons[$name]['Class']) {
        	$html .= ' class="'.$buttons[$name]['Class'].'"';
        }
        if (xtc_not_null($parameters)) {
			$html .= ' '.$parameters.'>';
        } else {
			$html .= '>';
        }
		if  ($buttons[$name]['iconposition'] == 'left' && $buttons[$name]['icon'] != '') {
			$html .= '<span class="'.$buttons[$name]['icon'].'"></span>';
            if  ($buttons[$name]['Text'] != '') {
				$html .= '<span>&nbsp;&nbsp;'.$buttons[$name]['Text'].'</span>';
			}
		}
		elseif ($buttons[$name]['iconposition'] == 'right' && $buttons[$name]['icon'] != '') {
            if  ($buttons[$name]['Text'] != '') {
				$html .= '<span>'.$buttons[$name]['Text'].'&nbsp;&nbsp;</span>';
			}
			$html .= '<span class="'.$buttons[$name]['icon'].'"></span>';
		}
		else {
			$html .= $buttons[$name]['Text'];
		}
        $html .= '</span>';
    }

    // wenn Submitbutton
    if ($submit) 
    {
		$html .= '<button';
		if ($buttons[$name]['Class']) {
			$html .= ' class="'.$buttons[$name]['Class'].'"';
		}
        if (xtc_not_null($parameters)) {
			$html .= ' '.$parameters;
        }
		if ($submit <> true) {
			$html .= ' name="'.$submit.'"';
		}
		if ($submit == true || $submit == "submit"){
			$html .= ' type="submit"';
		}
		$html .= ' title="'.$title.'">';
		if  ($buttons[$name]['iconposition'] == 'left' && $buttons[$name]['icon'] != '') {
			$html .= '<span class="'.$buttons[$name]['icon'].'"></span>';
            if  ($buttons[$name]['Text'] != '') {
				$html .= '<span>&nbsp;&nbsp;'.$buttons[$name]['Text'].'</span>';
			}
		}
		elseif ($buttons[$name]['iconposition'] == 'right' && $buttons[$name]['icon'] != '') {
            if  ($buttons[$name]['Text'] != '') {
				$html .= '<span>'.$buttons[$name]['Text'].'&nbsp;&nbsp;</span>';
			}
			$html .= '<span class="'.$buttons[$name]['icon'].'"></span>';
		}
		else {
			$html .= $buttons[$name]['Text'];
		}
		$html .= '</button>';
	}
	return $html;

}

?>
