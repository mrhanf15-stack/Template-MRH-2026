<?php
/**
 * MRH 2026 Template - Box-Lade-Logik
 * 
 * Steuert welche Boxen geladen werden und in welcher Reihenfolge.
 * Kompatibel mit modified eCommerce v2.0.7.2 und vorbereitet fuer v3.0.
 * 
 * KEINE SIDEBAR: Boxen werden in Header, Footer, Startseite und
 * Produktseiten integriert (Option A).
 */

// Boxen die IMMER geladen werden (Header, Footer)
$box_CATEGORIES     = true;
$box_MANUFACTURERS  = true;
$box_SEARCH         = true;
$box_LANGUAGES      = true;
$box_CURRENCIES     = true;
$box_LOGIN          = true;
$box_CART           = true;
$box_WISHLIST       = true;
$box_SHIPPING_COUNTRY = true;
$box_INFORMATION    = true;
$box_CONTENT        = true;
$box_MISCELLANEOUS  = true;
$box_ADMIN          = true;

// Boxen die auf der STARTSEITE geladen werden
if (basename($PHP_SELF) == 'index.php' && (!isset($cPath) || $cPath == '')) {
    $box_BESTSELLERS  = (defined('MRH_STARTPAGE_BESTSELLERS') && MRH_STARTPAGE_BESTSELLERS == 'true');
    $box_SPECIALS     = (defined('MRH_STARTPAGE_SPECIALS') && MRH_STARTPAGE_SPECIALS == 'true');
    $box_WHATSNEW     = (defined('MRH_STARTPAGE_WHATSNEW') && MRH_STARTPAGE_WHATSNEW == 'true');
    $box_LAST_VIEWED  = (defined('MRH_STARTPAGE_LAST_VIEWED') && MRH_STARTPAGE_LAST_VIEWED == 'true');
}

// Boxen die auf PRODUKTSEITEN geladen werden
if (basename($PHP_SELF) == 'product_info.php') {
    $box_LAST_VIEWED  = true;
    $box_REVIEWS      = true;
    $box_BESTSELLERS  = false;
    $box_SPECIALS     = false;
    $box_WHATSNEW     = false;
}

// Boxen die im WARENKORB/CHECKOUT geladen werden
if (in_array(basename($PHP_SELF), ['shopping_cart.php', 'checkout_shipping.php', 'checkout_payment.php', 'checkout_confirmation.php'])) {
    $box_ADD_A_QUICKIE = true;
    $box_GIFTCODE      = true;
}

// Newsletter Box ist LEER (Uptain/Brevo extern)
$box_NEWSLETTER = false;

// Affiliate Box nur wenn Modul aktiv
$box_AFFILIATE = (defined('MODULE_AFFILIATE_STATUS') && MODULE_AFFILIATE_STATUS == 'true');
