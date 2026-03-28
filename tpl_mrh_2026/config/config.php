<?php
/* -----------------------------------------------------------------------------------------
   MRH 2026 Template – config.php
   
   Template fuer modified eCommerce Shopsoftware
   Basierend auf Bootstrap 5.3 | Vanilla JS | Mobile First | SEO 2026
   
   Kompatibel mit: modified eCommerce v2.0.7.2+ und v3.0+
   PHP: 8.2 / 8.3
   -----------------------------------------------------------------------------------------
   Copyright (c) 2026 MRH N-Trade GmbH
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// Hinweis: Kein _VALID_XTC Guard hier, da die Template-Config
// VOR der Definition dieser Konstante geladen wird.

// Sprachdatei laden
require_once(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/lang/template_' . $_SESSION['language'] . '.php');

// === 1. TOPLEISTE ===
defined('MRH_SHOW_TOP1') or define('MRH_SHOW_TOP1', 'true');
defined('MRH_SHOW_TOP2') or define('MRH_SHOW_TOP2', 'true');
defined('MRH_SHOW_TOP3') or define('MRH_SHOW_TOP3', 'true');
defined('MRH_SHOW_TOP4') or define('MRH_SHOW_TOP4', 'true');
defined('MRH_SHOW_JS_DISABLED') or define('MRH_SHOW_JS_DISABLED', 'true');

// === 2. LOGO ===
defined('MRH_SHOP_LOGO') or define('MRH_SHOP_LOGO', 'img/logo_head.png');

// === 3. SUCHFELD ===
defined('MRH_SEARCHFIELD_PERMANENT') or define('MRH_SEARCHFIELD_PERMANENT', 'true');

// === 4. ICON-LEISTE ===
defined('MRH_SHOW_ICON_WITH_NAMES') or define('MRH_SHOW_ICON_WITH_NAMES', 'false');

// === 5. STICKY HEADER ===
defined('MRH_STICKY_HEADER') or define('MRH_STICKY_HEADER', 'true');
defined('MRH_STICKY_HEADER_CLASSES') or define('MRH_STICKY_HEADER_CLASSES', 'bg-white shadow-sm');

// === 6. MOBILE NAVIGATION (BS5 Offcanvas) ===
defined('MRH_OFFCANVAS_MENU') or define('MRH_OFFCANVAS_MENU', 'true');
defined('MRH_OFFCANVAS_POSITION') or define('MRH_OFFCANVAS_POSITION', 'start');

// === 7. MOBILE BOTTOM NAV ===
defined('MRH_BOTTOM_NAV') or define('MRH_BOTTOM_NAV', 'true');

// === 8. HAUPTNAVIGATION / KK-MEGA ===
defined('MRH_MAINMENU_SHOW') or define('MRH_MAINMENU_SHOW', 'true');
defined('MRH_TOUCH_USE') or define('MRH_TOUCH_USE', 'true');
defined('MRH_MAXLEVEL_IN_TOPCATMENU') or define('MRH_MAXLEVEL_IN_TOPCATMENU', 'false');
defined('MRH_SHOW_PRODUCTS_IN_TOPCATMENU') or define('MRH_SHOW_PRODUCTS_IN_TOPCATMENU', 'false');
defined('MRH_SHOW_HOMEBUTTON_IN_TOPCATMENU') or define('MRH_SHOW_HOMEBUTTON_IN_TOPCATMENU', 'true');
defined('MRH_ADD_LINK_IN_TOPCATMENU_LAST') or define('MRH_ADD_LINK_IN_TOPCATMENU_LAST', '');
defined('MRH_KK_MEGAS') or define('MRH_KK_MEGAS', 'main-3');
$KK_MEGAS = array();
if (MRH_KK_MEGAS !== '') {
    $KK_MEGAS = explode(',', trim(MRH_KK_MEGAS));
}

// === 9. KATEGORIEN ===
defined('MRH_CATEGORIESMENU_MAXLEVEL') or define('MRH_CATEGORIESMENU_MAXLEVEL', 'false');
defined('MRH_CATEGORIESMENU_AJAX') or define('MRH_CATEGORIESMENU_AJAX', 'true');
defined('MRH_CATEGORIESMENU_AJAX_SCROLL') or define('MRH_CATEGORIESMENU_AJAX_SCROLL', 'true');
defined('MRH_SPECIALS_CATEGORIES') or define('MRH_SPECIALS_CATEGORIES', 'true');
defined('MRH_WHATSNEW_CATEGORIES') or define('MRH_WHATSNEW_CATEGORIES', 'true');
defined('MRH_WHATSNEW_SPECIALS_UPPERCASE') or define('MRH_WHATSNEW_SPECIALS_UPPERCASE', 'true');
defined('MRH_HIDE_EMPTY_CATEGORIES') or define('MRH_HIDE_EMPTY_CATEGORIES', 'true');

// === 10. BANNER-MANAGER ===
defined('MRH_DEFAULT_BANNER_SETTINGS') or define('MRH_DEFAULT_BANNER_SETTINGS', 'n,btn-primary,n,n,btn-primary,n,4000');

// === 11. CAROUSEL / SLIDER ===
defined('MRH_CAROUSEL_SHOW') or define('MRH_CAROUSEL_SHOW', 'column');
defined('MRH_CAROUSEL_FADE') or define('MRH_CAROUSEL_FADE', 'true');
defined('MRH_TOP_PROD_IN_SLIDER') or define('MRH_TOP_PROD_IN_SLIDER', 'true');
defined('MRH_TOPCAROUSEL_FADE') or define('MRH_TOPCAROUSEL_FADE', 'true');
defined('MRH_TOPCAROUSEL_NAME_LINES') or define('MRH_TOPCAROUSEL_NAME_LINES', 0);
defined('MRH_BSCAROUSEL_SHOW') or define('MRH_BSCAROUSEL_SHOW', 'true');
defined('MRH_BSCAROUSEL_FADE') or define('MRH_BSCAROUSEL_FADE', 'true');
defined('MRH_BSCAROUSEL_NAME_LINES') or define('MRH_BSCAROUSEL_NAME_LINES', 0);

// === 12. BOXEN STARTSEITE ===
defined('MRH_STARTPAGE_BOX_CATEGORIES') or define('MRH_STARTPAGE_BOX_CATEGORIES', 'true');
defined('MRH_STARTPAGE_BOX_ADD_QUICKIE') or define('MRH_STARTPAGE_BOX_ADD_QUICKIE', 'true');
defined('MRH_STARTPAGE_BOX_LOGIN') or define('MRH_STARTPAGE_BOX_LOGIN', 'true');
defined('MRH_STARTPAGE_BOX_WHATSNEW') or define('MRH_STARTPAGE_BOX_WHATSNEW', 'true');
defined('MRH_STARTPAGE_BOX_SPECIALS') or define('MRH_STARTPAGE_BOX_SPECIALS', 'true');
defined('MRH_STARTPAGE_BOX_LAST_VIEWED') or define('MRH_STARTPAGE_BOX_LAST_VIEWED', 'true');
defined('MRH_STARTPAGE_BOX_REVIEWS') or define('MRH_STARTPAGE_BOX_REVIEWS', 'true');
defined('MRH_STARTPAGE_BOX_CUSTOM') or define('MRH_STARTPAGE_BOX_CUSTOM', 'true');
defined('MRH_STARTPAGE_BOX_MANUFACTURERS') or define('MRH_STARTPAGE_BOX_MANUFACTURERS', 'true');
defined('MRH_STARTPAGE_BOX_MANUFACTURERS_INFO') or define('MRH_STARTPAGE_BOX_MANUFACTURERS_INFO', 'true');
defined('MRH_STARTPAGE_BOX_CURRENCIES') or define('MRH_STARTPAGE_BOX_CURRENCIES', 'true');
defined('MRH_STARTPAGE_BOX_SHIPPING_COUNTRY') or define('MRH_STARTPAGE_BOX_SHIPPING_COUNTRY', 'true');
defined('MRH_STARTPAGE_BOX_INFOBOX') or define('MRH_STARTPAGE_BOX_INFOBOX', 'true');
defined('MRH_STARTPAGE_BOX_HISTORY') or define('MRH_STARTPAGE_BOX_HISTORY', 'true');
defined('MRH_STARTPAGE_BOX_TRUSTEDSHOPS') or define('MRH_STARTPAGE_BOX_TRUSTEDSHOPS', 'true');

// === 13. BOXEN ANDERE SEITEN ===
defined('MRH_NOT_STARTPAGE_BOX_CATEGORIES') or define('MRH_NOT_STARTPAGE_BOX_CATEGORIES', 'true');
defined('MRH_NOT_STARTPAGE_BOX_ADD_QUICKIE') or define('MRH_NOT_STARTPAGE_BOX_ADD_QUICKIE', 'true');
defined('MRH_NOT_STARTPAGE_BOX_LOGIN') or define('MRH_NOT_STARTPAGE_BOX_LOGIN', 'true');
defined('MRH_NOT_STARTPAGE_BOX_WHATSNEW') or define('MRH_NOT_STARTPAGE_BOX_WHATSNEW', 'true');
defined('MRH_NOT_STARTPAGE_BOX_SPECIALS') or define('MRH_NOT_STARTPAGE_BOX_SPECIALS', 'true');
defined('MRH_NOT_STARTPAGE_BOX_LAST_VIEWED') or define('MRH_NOT_STARTPAGE_BOX_LAST_VIEWED', 'true');
defined('MRH_NOT_STARTPAGE_BOX_REVIEWS') or define('MRH_NOT_STARTPAGE_BOX_REVIEWS', 'true');
defined('MRH_NOT_STARTPAGE_BOX_CUSTOM') or define('MRH_NOT_STARTPAGE_BOX_CUSTOM', 'true');
defined('MRH_NOT_STARTPAGE_BOX_MANUFACTURERS') or define('MRH_NOT_STARTPAGE_BOX_MANUFACTURERS', 'true');
defined('MRH_NOT_STARTPAGE_BOX_MANUFACTURERS_INFO') or define('MRH_NOT_STARTPAGE_BOX_MANUFACTURERS_INFO', 'true');
defined('MRH_NOT_STARTPAGE_BOX_CURRENCIES') or define('MRH_NOT_STARTPAGE_BOX_CURRENCIES', 'true');
defined('MRH_NOT_STARTPAGE_BOX_SHIPPING_COUNTRY') or define('MRH_NOT_STARTPAGE_BOX_SHIPPING_COUNTRY', 'true');
defined('MRH_NOT_STARTPAGE_BOX_INFOBOX') or define('MRH_NOT_STARTPAGE_BOX_INFOBOX', 'true');
defined('MRH_NOT_STARTPAGE_BOX_HISTORY') or define('MRH_NOT_STARTPAGE_BOX_HISTORY', 'true');
defined('MRH_NOT_STARTPAGE_BOX_TRUSTEDSHOPS') or define('MRH_NOT_STARTPAGE_BOX_TRUSTEDSHOPS', 'true');
defined('MRH_HIDE_ALL_BOXES') or define('MRH_HIDE_ALL_BOXES', 'false');

// === 14. FULLCONTENT (Option A: Alle Fullcontent) ===
defined('MRH_STARTPAGE_FULLCONTENT') or define('MRH_STARTPAGE_FULLCONTENT', 'true');
defined('MRH_PROD_LIST_FULLCONTENT') or define('MRH_PROD_LIST_FULLCONTENT', 'true');
defined('MRH_PROD_DETAIL_FULLCONTENT') or define('MRH_PROD_DETAIL_FULLCONTENT', 'true');
defined('MRH_PROD_DETAIL_SHOW_MANUIMAGE') or define('MRH_PROD_DETAIL_SHOW_MANUIMAGE', 'true');

// === 15. PRODUKTLISTEN ===
defined('MRH_PRODUCT_LIST_BOX_STARTPAGE') or define('MRH_PRODUCT_LIST_BOX_STARTPAGE', 'true');
defined('MRH_PROD_LIST_BOX') or define('MRH_PROD_LIST_BOX', 'true');
defined('MRH_PRODUCT_LIST_BOX') or define('MRH_PRODUCT_LIST_BOX', ((isset($_SESSION['listbox'])) ? $_SESSION['listbox'] : MRH_PROD_LIST_BOX));
defined('MRH_PRODUCT_INFO_BOX') or define('MRH_PRODUCT_INFO_BOX', 'true');

// === 16. CSS-KLASSEN ===
defined('MRH_TOP1_NAVBAR') or define('MRH_TOP1_NAVBAR', 'navbar-dark');
defined('MRH_TOP1_BG') or define('MRH_TOP1_BG', 'dark');
defined('MRH_TOP1_TEXT') or define('MRH_TOP1_TEXT', '');
defined('MRH_LOGOBAR_TEXT') or define('MRH_LOGOBAR_TEXT', 'text-secondary');
defined('MRH_TOP2_NAVBAR') or define('MRH_TOP2_NAVBAR', 'navbar-light');
defined('MRH_TOP2_BG') or define('MRH_TOP2_BG', 'light');
defined('MRH_FOOTER_NAVBAR') or define('MRH_FOOTER_NAVBAR', 'navbar-dark');
defined('MRH_FOOTER_BG') or define('MRH_FOOTER_BG', 'dark');

// === 17. MODULE ===
defined('MRH_CUSTOMERS_REMIND') or define('MRH_CUSTOMERS_REMIND', 'false');
defined('MRH_CUSTOMERS_REMIND_SENDMAIL') or define('MRH_CUSTOMERS_REMIND_SENDMAIL', 'false');
defined('MRH_CHEAPLY_SEE') or define('MRH_CHEAPLY_SEE', 'false');
defined('MRH_PRODUCT_INQUIRY') or define('MRH_PRODUCT_INQUIRY', 'false');
defined('MRH_ATTR_PRICE_UPDATER') or define('MRH_ATTR_PRICE_UPDATER', 'false');
defined('MRH_ATTR_PRICE_UPDATER_UPDATE_PRICE') or define('MRH_ATTR_PRICE_UPDATER_UPDATE_PRICE', 'true');
defined('MRH_AGI_REDUCE_CART') or define('MRH_AGI_REDUCE_CART', 'false');
defined('MRH_AGI_REDUCE_CART_SHOW_AVAILABLE') or define('MRH_AGI_REDUCE_CART_SHOW_AVAILABLE', 'false');
defined('MRH_AWIDSRATINGBREAKDOWN') or define('MRH_AWIDSRATINGBREAKDOWN', 'false');
defined('MRH_AWIDSRATINGBREAKDOWN_PRODLIST') or define('MRH_AWIDSRATINGBREAKDOWN_PRODLIST', 'true');
defined('MRH_AWIDSRATINGBREAKDOWN_SHOW_NULL_REVIEWS') or define('MRH_AWIDSRATINGBREAKDOWN_SHOW_NULL_REVIEWS', 'true');
defined('MRH_AWIDSRATINGBREAKDOWN_URL') or define('MRH_AWIDSRATINGBREAKDOWN_URL', 'true');

// === 18. LAGERAMPEL ===
defined('MRH_TRAFFIC_LIGHTS') or define('MRH_TRAFFIC_LIGHTS', 'false');
defined('MRH_TRAFFIC_LIGHTS_PRODLISTING') or define('MRH_TRAFFIC_LIGHTS_PRODLISTING', 'false');
defined('MRH_TRAFFIC_LIGHTS_PRODINFO') or define('MRH_TRAFFIC_LIGHTS_PRODINFO', 'false');
defined('MRH_TRAFFIC_LIGHTS_PRODATTRIBUTES') or define('MRH_TRAFFIC_LIGHTS_PRODATTRIBUTES', 'false');
defined('MRH_MODULE_TRAFFIC_LIGHTS_STOCK_RED_YELL') or define('MRH_MODULE_TRAFFIC_LIGHTS_STOCK_RED_YELL', 0);
defined('MRH_MODULE_TRAFFIC_LIGHTS_STOCK_GREEN') or define('MRH_MODULE_TRAFFIC_LIGHTS_STOCK_GREEN', 10);

// === 19. FLAGS ===
defined('MRH_FLAG_NEW_SHOW') or define('MRH_FLAG_NEW_SHOW', 'true');
defined('MRH_FLAG_TOP_SHOW') or define('MRH_FLAG_TOP_SHOW', 'true');
defined('MRH_FLAG_SPECIAL_SHOW') or define('MRH_FLAG_SPECIAL_SHOW', 'true');
defined('MRH_FLAG_PERCENT_SHOW') or define('MRH_FLAG_PERCENT_SHOW', 'true');

// === 20. VALIDIERUNG ===
defined('MRH_ADVANCED_JS_VALIDATION') or define('MRH_ADVANCED_JS_VALIDATION', 'true');

// === 21. BILD-ZOOM (CSS-basiert) ===
defined('MRH_USE_IMAGE_ZOOM') or define('MRH_USE_IMAGE_ZOOM', 'true');

// === 22. FILTER ===
defined('MRH_FILTERCOLOR_AKTIV') or define('MRH_FILTERCOLOR_AKTIV', 'primary');
defined('MRH_SEARCHFIELD_ONE_ROW') or define('MRH_SEARCHFIELD_ONE_ROW', 'true');

// === 23. FREE SHIPPING BAR ===
defined('MRH_FREE_SHIPPING_BAR') or define('MRH_FREE_SHIPPING_BAR', 'true');
defined('MRH_FREE_SHIPPING_THRESHOLD') or define('MRH_FREE_SHIPPING_THRESHOLD', '50.00');

// === 24. FIVEBYTES ===
defined('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_SPECIALS') or define('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_SPECIALS', 'true');
defined('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_WHATSNEW') or define('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_WHATSNEW', 'true');

// === 25. SEO 2026 ===
defined('MRH_SCHEMA_ORG') or define('MRH_SCHEMA_ORG', 'true');
defined('MRH_SCHEMA_ORGANIZATION') or define('MRH_SCHEMA_ORGANIZATION', 'true');
defined('MRH_SCHEMA_BREADCRUMB') or define('MRH_SCHEMA_BREADCRUMB', 'true');
defined('MRH_SCHEMA_PRODUCT') or define('MRH_SCHEMA_PRODUCT', 'true');
defined('MRH_SPEAKABLE') or define('MRH_SPEAKABLE', 'true');

// === 26. EXTERNE DIENSTE ===
defined('MRH_TRUSTED_SHOPS_WIDGET') or define('MRH_TRUSTED_SHOPS_WIDGET', 'true');
defined('MRH_UPTAIN_ENABLED') or define('MRH_UPTAIN_ENABLED', 'true');

// === 27. SHARIFF (Social Share) ===
defined('MRH_SHARIFF_SHOW') or define('MRH_SHARIFF_SHOW', 'true');

// === 28. SHOPVOTE ===
defined('MRH_SHOPVOTE_ENABLED') or define('MRH_SHOPVOTE_ENABLED', 'true');
defined('MRH_SHOPVOTE_SHOP_ID') or define('MRH_SHOPVOTE_SHOP_ID', '');  // ShopVote Shop-ID hier eintragen
defined('MRH_SHOPVOTE_BADGE_FOOTER') or define('MRH_SHOPVOTE_BADGE_FOOTER', 'true');
defined('MRH_SHOPVOTE_REVIEWS_HOME') or define('MRH_SHOPVOTE_REVIEWS_HOME', 'true');
defined('MRH_SHOPVOTE_PRODUCT_PAGE') or define('MRH_SHOPVOTE_PRODUCT_PAGE', 'true');
defined('MRH_SHOPVOTE_CHECKOUT_SUCCESS') or define('MRH_SHOPVOTE_CHECKOUT_SUCCESS', 'true');
defined('MRH_SHOPVOTE_RICH_SNIPPET') or define('MRH_SHOPVOTE_RICH_SNIPPET', 'true');

// =========================================================================
//  AB HIER NICHTS AENDERN
// =========================================================================

if (MRH_SPECIALS_CATEGORIES === 'true') {
    require_once(DIR_FS_INC . 'check_specials.inc.php');
    defined('SPECIALS_EXISTS') or define('SPECIALS_EXISTS', check_specials());
}

$pictureset_active = false;
if (defined('DIR_WS_MINI_IMAGES')) {
    $pictureset_active = (defined('MRH_PICTURESET_ACTIVE') && constant('MRH_PICTURESET_ACTIVE') === 'true');
}
define('PICTURESET_ACTIVE', $pictureset_active);
define('PICTURESET_ROW', '768:thumbnail');

define('DIR_FS_BOXES', DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/boxes/');
define('DIR_FS_BOXES_INC', DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/');

define('TPL_POPUP_SHIPPING_LINK_PARAMETERS', '');
define('TPL_POPUP_SHIPPING_LINK_CLASS', 'iframe');
define('TPL_POPUP_CONTENT_LINK_PARAMETERS', '');
define('TPL_POPUP_CONTENT_LINK_CLASS', 'iframe');
define('TPL_POPUP_PRODUCT_LINK_PARAMETERS', '');
define('TPL_POPUP_PRODUCT_LINK_CLASS', 'iframe');
define('TPL_POPUP_COUPON_HELP_LINK_PARAMETERS', '');
define('TPL_POPUP_COUPON_HELP_LINK_CLASS', 'iframe');
define('TPL_POPUP_PRODUCT_PRINT_SIZE', '');
define('TPL_POPUP_PRINT_ORDER_SIZE', '');

define('TEMPLATE_ENGINE', 'smarty_4');
define('TEMPLATE_HTML_ENGINE', 'html5');
define('TEMPLATE_RESPONSIVE', 'true');
defined('COMPRESS_JAVASCRIPT') or define('COMPRESS_JAVASCRIPT', 'false');
defined('DIR_WS_BASE') or define('DIR_WS_BASE', xtc_href_link('', '', $request_type, false, false));

if (is_file(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/css_button.inc.php')) {
    require_once(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/css_button.inc.php');
}
