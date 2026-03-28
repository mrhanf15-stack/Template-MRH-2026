<?php
/* -----------------------------------------------------------------------------------------
   Template MRH 2026 - Bootstrap 5.3 / Mobile First / SEO 2026
   für modified eCommerce Shopsoftware v2.0.7.2
   
   Basierend auf KarlBogen/bootstrap4, komplett neu entwickelt.
   Copyright (c) 2026 MRH N-Trade GmbH "Mr. Hanf"
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// Sprachdatei laden
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/lang/template_'.$_SESSION['language'].'.php');

// ============================================================
// TOPBAR
// ============================================================
defined('MRH_SHOW_TOPBAR') or define('MRH_SHOW_TOPBAR', 'true');
defined('MRH_TOPBAR_USP_COUNT') or define('MRH_TOPBAR_USP_COUNT', '3');

// ============================================================
// HEADER / LOGO
// ============================================================
defined('MRH_SHOP_LOGO') or define('MRH_SHOP_LOGO', 'img/logo_head.png');
defined('MRH_SHOP_LOGO_WIDTH') or define('MRH_SHOP_LOGO_WIDTH', '200');
defined('MRH_SHOP_LOGO_HEIGHT') or define('MRH_SHOP_LOGO_HEIGHT', '60');

// ============================================================
// SUCHE
// ============================================================
defined('MRH_SEARCH_PROMINENT') or define('MRH_SEARCH_PROMINENT', 'true');
defined('MRH_SEARCH_AUTOSUGGEST') or define('MRH_SEARCH_AUTOSUGGEST', 'true');

// ============================================================
// NAVIGATION
// ============================================================
defined('MRH_NAV_STICKY') or define('MRH_NAV_STICKY', 'true');
defined('MRH_NAV_MEGAMENU') or define('MRH_NAV_MEGAMENU', 'true');
defined('MRH_NAV_MAX_CATEGORIES') or define('MRH_NAV_MAX_CATEGORIES', '7');
defined('MRH_NAV_MAX_LEVEL') or define('MRH_NAV_MAX_LEVEL', '3');
defined('MRH_NAV_SHOW_IMAGES') or define('MRH_NAV_SHOW_IMAGES', 'true');

// Mobile Navigation
defined('MRH_MOBILE_OFFCANVAS') or define('MRH_MOBILE_OFFCANVAS', 'true');
defined('MRH_MOBILE_OFFCANVAS_POSITION') or define('MRH_MOBILE_OFFCANVAS_POSITION', 'start');
defined('MRH_MOBILE_BOTTOM_BAR') or define('MRH_MOBILE_BOTTOM_BAR', 'true');

// Kategorien
defined('MRH_CATEGORIES_AJAX') or define('MRH_CATEGORIES_AJAX', 'true');
defined('MRH_HIDE_EMPTY_CATEGORIES') or define('MRH_HIDE_EMPTY_CATEGORIES', 'true');
defined('MRH_SPECIALS_IN_CATEGORIES') or define('MRH_SPECIALS_IN_CATEGORIES', 'true');
defined('MRH_WHATSNEW_IN_CATEGORIES') or define('MRH_WHATSNEW_IN_CATEGORIES', 'true');

// ============================================================
// SLIDER / CAROUSEL
// ============================================================
defined('MRH_CAROUSEL_SHOW') or define('MRH_CAROUSEL_SHOW', 'column');
defined('MRH_CAROUSEL_FADE') or define('MRH_CAROUSEL_FADE', 'true');
defined('MRH_BESTSELLER_CAROUSEL') or define('MRH_BESTSELLER_CAROUSEL', 'true');
defined('MRH_TOP_PRODUCTS_CAROUSEL') or define('MRH_TOP_PRODUCTS_CAROUSEL', 'true');

// ============================================================
// LAYOUT
// ============================================================
defined('MRH_STARTPAGE_FULLCONTENT') or define('MRH_STARTPAGE_FULLCONTENT', 'true');
defined('MRH_PROD_LIST_FULLCONTENT') or define('MRH_PROD_LIST_FULLCONTENT', 'false');
defined('MRH_PROD_DETAIL_FULLCONTENT') or define('MRH_PROD_DETAIL_FULLCONTENT', 'true');
defined('MRH_PRODUCT_LIST_BOX_VIEW') or define('MRH_PRODUCT_LIST_BOX_VIEW', 'true');

// ============================================================
// BOXEN - STARTSEITE
// ============================================================
defined('MRH_START_BOX_CATEGORIES') or define('MRH_START_BOX_CATEGORIES', 'true');
defined('MRH_START_BOX_INFOBOX') or define('MRH_START_BOX_INFOBOX', 'true');
defined('MRH_START_BOX_MANUFACTURERS') or define('MRH_START_BOX_MANUFACTURERS', 'true');
defined('MRH_START_BOX_LOGIN') or define('MRH_START_BOX_LOGIN', 'true');
defined('MRH_START_BOX_WHATSNEW') or define('MRH_START_BOX_WHATSNEW', 'true');
defined('MRH_START_BOX_SPECIALS') or define('MRH_START_BOX_SPECIALS', 'true');
defined('MRH_START_BOX_LAST_VIEWED') or define('MRH_START_BOX_LAST_VIEWED', 'true');
defined('MRH_START_BOX_REVIEWS') or define('MRH_START_BOX_REVIEWS', 'true');
defined('MRH_START_BOX_CUSTOM') or define('MRH_START_BOX_CUSTOM', 'true');
defined('MRH_START_BOX_SHIPPING_COUNTRY') or define('MRH_START_BOX_SHIPPING_COUNTRY', 'true');

// ============================================================
// BOXEN - UNTERSEITEN
// ============================================================
defined('MRH_SUB_BOX_CATEGORIES') or define('MRH_SUB_BOX_CATEGORIES', 'true');
defined('MRH_SUB_BOX_INFOBOX') or define('MRH_SUB_BOX_INFOBOX', 'true');
defined('MRH_SUB_BOX_MANUFACTURERS') or define('MRH_SUB_BOX_MANUFACTURERS', 'true');
defined('MRH_SUB_BOX_LOGIN') or define('MRH_SUB_BOX_LOGIN', 'true');
defined('MRH_SUB_BOX_WHATSNEW') or define('MRH_SUB_BOX_WHATSNEW', 'true');
defined('MRH_SUB_BOX_SPECIALS') or define('MRH_SUB_BOX_SPECIALS', 'true');
defined('MRH_SUB_BOX_LAST_VIEWED') or define('MRH_SUB_BOX_LAST_VIEWED', 'true');
defined('MRH_SUB_BOX_REVIEWS') or define('MRH_SUB_BOX_REVIEWS', 'true');
defined('MRH_SUB_BOX_CUSTOM') or define('MRH_SUB_BOX_CUSTOM', 'true');
defined('MRH_SUB_BOX_SHIPPING_COUNTRY') or define('MRH_SUB_BOX_SHIPPING_COUNTRY', 'true');
defined('MRH_HIDE_ALL_BOXES') or define('MRH_HIDE_ALL_BOXES', 'false');

// ============================================================
// MODULE
// ============================================================
defined('MRH_CUSTOMERS_REMIND') or define('MRH_CUSTOMERS_REMIND', 'false');
defined('MRH_CHEAPLY_SEE') or define('MRH_CHEAPLY_SEE', 'false');
defined('MRH_PRODUCT_INQUIRY') or define('MRH_PRODUCT_INQUIRY', 'false');
defined('MRH_ATTR_PRICE_UPDATER') or define('MRH_ATTR_PRICE_UPDATER', 'false');
defined('MRH_AGI_REDUCE_CART') or define('MRH_AGI_REDUCE_CART', 'false');

// Lagerampel
defined('MRH_TRAFFIC_LIGHTS') or define('MRH_TRAFFIC_LIGHTS', 'false');
defined('MRH_TRAFFIC_LIGHTS_LISTING') or define('MRH_TRAFFIC_LIGHTS_LISTING', 'false');
defined('MRH_TRAFFIC_LIGHTS_INFO') or define('MRH_TRAFFIC_LIGHTS_INFO', 'false');
defined('MRH_TRAFFIC_LIGHTS_ATTRIBUTES') or define('MRH_TRAFFIC_LIGHTS_ATTRIBUTES', 'false');

// Bewertungen
defined('MRH_RATING_BREAKDOWN') or define('MRH_RATING_BREAKDOWN', 'false');
defined('MRH_RATING_BREAKDOWN_LISTING') or define('MRH_RATING_BREAKDOWN_LISTING', 'true');

// Flags
defined('MRH_FLAG_NEW') or define('MRH_FLAG_NEW', 'true');
defined('MRH_FLAG_TOP') or define('MRH_FLAG_TOP', 'true');
defined('MRH_FLAG_SPECIAL') or define('MRH_FLAG_SPECIAL', 'true');
defined('MRH_FLAG_PERCENT') or define('MRH_FLAG_PERCENT', 'true');

// ============================================================
// PERFORMANCE / SPEED
// ============================================================
defined('MRH_CRITICAL_CSS_INLINE') or define('MRH_CRITICAL_CSS_INLINE', 'true');
defined('MRH_LAZY_LOADING_NATIVE') or define('MRH_LAZY_LOADING_NATIVE', 'true');
defined('MRH_PRELOAD_LCP_IMAGE') or define('MRH_PRELOAD_LCP_IMAGE', 'true');
defined('MRH_FONT_DISPLAY_SWAP') or define('MRH_FONT_DISPLAY_SWAP', 'true');
defined('MRH_DEFER_NON_CRITICAL_JS') or define('MRH_DEFER_NON_CRITICAL_JS', 'true');
defined('MRH_COMPRESS_ASSETS') or define('MRH_COMPRESS_ASSETS', 'true');

// ============================================================
// SEO
// ============================================================
defined('MRH_SCHEMA_ORGANIZATION') or define('MRH_SCHEMA_ORGANIZATION', 'true');
defined('MRH_SCHEMA_PRODUCT') or define('MRH_SCHEMA_PRODUCT', 'true');
defined('MRH_SCHEMA_BREADCRUMB') or define('MRH_SCHEMA_BREADCRUMB', 'true');
defined('MRH_SCHEMA_FAQ') or define('MRH_SCHEMA_FAQ', 'true');
defined('MRH_SCHEMA_REVIEW') or define('MRH_SCHEMA_REVIEW', 'true');
defined('MRH_OG_TAGS') or define('MRH_OG_TAGS', 'true');

// ============================================================
// DESIGN / THEME
// ============================================================
defined('MRH_THEME') or define('MRH_THEME', 'default');
defined('MRH_COLOR_MODE') or define('MRH_COLOR_MODE', 'light');
defined('MRH_TOPBAR_BG') or define('MRH_TOPBAR_BG', 'dark');
defined('MRH_HEADER_BG') or define('MRH_HEADER_BG', 'white');
defined('MRH_NAV_BG') or define('MRH_NAV_BG', 'dark');
defined('MRH_FOOTER_BG') or define('MRH_FOOTER_BG', 'dark');

// ============================================================
// EASYZOOM (Vanilla JS Ersatz)
// ============================================================
defined('MRH_IMAGE_ZOOM') or define('MRH_IMAGE_ZOOM', 'true');

// ============================================================
// FILTER
// ============================================================
defined('MRH_FILTER_ACTIVE') or define('MRH_FILTER_ACTIVE', 'true');
defined('MRH_FILTER_SIDEBAR') or define('MRH_FILTER_SIDEBAR', 'true');

// ============================================================
// FOOTER
// ============================================================
defined('MRH_FOOTER_COLUMNS') or define('MRH_FOOTER_COLUMNS', '4');
defined('MRH_FOOTER_NEWSLETTER') or define('MRH_FOOTER_NEWSLETTER', 'true');
defined('MRH_FOOTER_SOCIAL_MEDIA') or define('MRH_FOOTER_SOCIAL_MEDIA', 'true');
defined('MRH_FOOTER_PAYMENT_ICONS') or define('MRH_FOOTER_PAYMENT_ICONS', 'true');
defined('MRH_FOOTER_SHIPPING_ICONS') or define('MRH_FOOTER_SHIPPING_ICONS', 'true');
defined('MRH_FOOTER_BACK_TO_TOP') or define('MRH_FOOTER_BACK_TO_TOP', 'true');

// ============================================================
// COOKIE CONSENT
// ============================================================
defined('MRH_COOKIE_CONSENT') or define('MRH_COOKIE_CONSENT', 'true');
defined('MRH_COOKIE_CONSENT_STYLE') or define('MRH_COOKIE_CONSENT_STYLE', 'modal');

// ============================================================
// SHARIFF SOCIAL SHARING
// ============================================================
defined('MRH_SHARIFF') or define('MRH_SHARIFF', 'true');
defined('MRH_SHARIFF_SERVICES') or define('MRH_SHARIFF_SERVICES', 'facebook,twitter,whatsapp,telegram,email');

// ============================================================
// FREE SHIPPING BAR
// ============================================================
defined('MRH_FREE_SHIPPING_BAR') or define('MRH_FREE_SHIPPING_BAR', 'true');

// ============================================================
// NEWSLETTER OVERLAY
// ============================================================
defined('MRH_NEWSLETTER_OVERLAY') or define('MRH_NEWSLETTER_OVERLAY', 'true');

// ============================================================
// PRODUCT COMPARE
// ============================================================
defined('MRH_PRODUCT_COMPARE') or define('MRH_PRODUCT_COMPARE', 'true');

// ============================================================
// BLOG
// ============================================================
defined('MRH_BLOG_ACTIVE') or define('MRH_BLOG_ACTIVE', 'true');

// ============================================================
// SEEDFINDER
// ============================================================
defined('MRH_SEEDFINDER_ACTIVE') or define('MRH_SEEDFINDER_ACTIVE', 'true');

// ============================================================
// REKLAMATION
// ============================================================
defined('MRH_REKLAMATION_ACTIVE') or define('MRH_REKLAMATION_ACTIVE', 'true');

// ============================================================
// GIFT CART
// ============================================================
defined('MRH_GIFT_CART_ACTIVE') or define('MRH_GIFT_CART_ACTIVE', 'true');
