<?php
/* -----------------------------------------------------------------------------------------
   MRH 2026 Template – boxes.php
   
   Box-Lade-Logik basierend auf xtc5-Original + BS4-Erweiterungen.
   Kompatibel mit modified eCommerce v2.0.7.2+ und v3.0+
   -----------------------------------------------------------------------------------------
   Copyright (c) 2026 MRH N-Trade GmbH
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// -----------------------------------------------------------------------------------------
// define full content sites (Seiten OHNE Sidebar / nur Hauptinhalt)
// -----------------------------------------------------------------------------------------
$fullcontent = array(
    FILENAME_CHECKOUT_SHIPPING,
    FILENAME_CHECKOUT_PAYMENT,
    FILENAME_CHECKOUT_CONFIRMATION,
    FILENAME_CHECKOUT_SUCCESS,
    FILENAME_CHECKOUT_SHIPPING_ADDRESS,
    FILENAME_CHECKOUT_PAYMENT_ADDRESS,
    FILENAME_COOKIE_USAGE,
    FILENAME_ACCOUNT,
    FILENAME_ACCOUNT_EDIT,
    FILENAME_ACCOUNT_HISTORY,
    FILENAME_ACCOUNT_HISTORY_INFO,
    FILENAME_ACCOUNT_PASSWORD,
    FILENAME_ACCOUNT_DELETE,
    FILENAME_ACCOUNT_CHECKOUT_EXPRESS,
    FILENAME_CREATE_ACCOUNT,
    FILENAME_CREATE_GUEST_ACCOUNT,
    FILENAME_ADDRESS_BOOK,
    FILENAME_ADDRESS_BOOK_PROCESS,
    FILENAME_PASSWORD_DOUBLE_OPT,
    FILENAME_ADVANCED_SEARCH_RESULT,
    FILENAME_ADVANCED_SEARCH,
    FILENAME_SHOPPING_CART,
    FILENAME_GV_SEND,
    FILENAME_NEWSLETTER,
    FILENAME_LOGIN,
    FILENAME_CONTENT,
    FILENAME_REVIEWS,
    FILENAME_WISHLIST,
    FILENAME_CHECKOUT_PAYMENT_IFRAME,
);

if (defined('FILENAME_CHEAPLY_SEE')) {
    $fullcontent[] = FILENAME_CHEAPLY_SEE;
}

// -----------------------------------------------------------------------------------------
// Prüfe ob aktuelle Seite eine "full content" Seite ist
// -----------------------------------------------------------------------------------------
$is_fullcontent = in_array(basename($PHP_SELF), $fullcontent);

// -----------------------------------------------------------------------------------------
// BOC require boxes – IMMER geladen (wie xtc5-Original)
// -----------------------------------------------------------------------------------------
require_once(DIR_FS_BOXES . 'categories.php');
require_once(DIR_FS_BOXES . 'manufacturers.php');
require_once(DIR_FS_BOXES . 'search.php');
require_once(DIR_FS_BOXES . 'content.php');
require_once(DIR_FS_BOXES . 'information.php');
require_once(DIR_FS_BOXES . 'languages.php');
require_once(DIR_FS_BOXES . 'infobox.php');

// Miscellaneous (MRH-Erweiterung)
if (is_file(DIR_FS_BOXES . 'miscellaneous.php')) {
    require_once(DIR_FS_BOXES . 'miscellaneous.php');
}

// Last Viewed
if (is_file(DIR_FS_BOXES . 'last_viewed.php')) {
    require_once(DIR_FS_BOXES . 'last_viewed.php');
}

// -----------------------------------------------------------------------------------------
// Nur für eingeloggte Besucher
// -----------------------------------------------------------------------------------------
if (isset($_SESSION['customer_id'])) {
    // Newsletter
    if (!defined('MODULE_NEWSLETTER_STATUS') || MODULE_NEWSLETTER_STATUS == 'true') {
        if (is_file(DIR_FS_BOXES . 'newsletter.php')) {
            require_once(DIR_FS_BOXES . 'newsletter.php');
        }
    }
    // TrustedShops
    if (is_file(DIR_FS_BOXES . 'trustedshops.php')) {
        require_once(DIR_FS_BOXES . 'trustedshops.php');
    }
    // Login Box
    require_once(DIR_FS_BOXES . 'loginbox.php');
    // Add a Quickie (Schnellbestellung)
    if (is_file(DIR_FS_BOXES . 'add_a_quickie.php')) {
        require_once(DIR_FS_BOXES . 'add_a_quickie.php');
    }
    // Wishlist
    if (is_file(DIR_FS_BOXES . 'wishlist.php')) {
        require_once(DIR_FS_BOXES . 'wishlist.php');
    }
    // Order History
    if ($_SESSION['customers_status']['customers_status_read_reviews'] == '1') {
        require_once(DIR_FS_BOXES . 'reviews.php');
    }
    require_once(DIR_FS_BOXES . 'order_history.php');
} else {
    // Nicht eingeloggt: Newsletter + Login Box
    if (!defined('MODULE_NEWSLETTER_STATUS') || MODULE_NEWSLETTER_STATUS == 'true') {
        if (is_file(DIR_FS_BOXES . 'newsletter.php')) {
            require_once(DIR_FS_BOXES . 'newsletter.php');
        }
    }
    if (is_file(DIR_FS_BOXES . 'trustedshops.php')) {
        require_once(DIR_FS_BOXES . 'trustedshops.php');
    }
    require_once(DIR_FS_BOXES . 'loginbox.php');
}

// -----------------------------------------------------------------------------------------
// Nur wenn Preise angezeigt werden
// -----------------------------------------------------------------------------------------
if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
    require_once(DIR_FS_BOXES . 'shopping_cart.php');
}

// -----------------------------------------------------------------------------------------
// Admins only
// -----------------------------------------------------------------------------------------
if ($_SESSION['customers_status']['customers_status'] == '0') {
    require_once(DIR_FS_BOXES . 'admin.php');
    $smarty->assign('is_admin', true);
}

// -----------------------------------------------------------------------------------------
// Manufacturer Info
// -----------------------------------------------------------------------------------------
require_once(DIR_FS_BOXES . 'manufacturer_info.php');

// -----------------------------------------------------------------------------------------
// Während des Kauf-Abschlusses verborgen
// -----------------------------------------------------------------------------------------
if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    require_once(DIR_FS_BOXES . 'currencies.php');
    require_once(DIR_FS_BOXES . 'shipping_country.php');
}

// -----------------------------------------------------------------------------------------
// Startseiten-Boxen (nur Startseite, nicht fullcontent)
// -----------------------------------------------------------------------------------------
if (!$is_fullcontent) {
    // Bestseller
    if (defined('MRH_STARTPAGE_BESTSELLERS') && MRH_STARTPAGE_BESTSELLERS == 'true') {
        if (basename($PHP_SELF) == FILENAME_DEFAULT && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id'])) {
            require_once(DIR_FS_BOXES . 'best_sellers.php');
        }
    }
    // Specials
    if (defined('MRH_STARTPAGE_SPECIALS') && MRH_STARTPAGE_SPECIALS == 'true') {
        if (basename($PHP_SELF) == FILENAME_DEFAULT && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id'])) {
            require_once(DIR_FS_BOXES . 'specials.php');
        }
    }
    // What's New
    if (defined('MRH_STARTPAGE_WHATSNEW') && MRH_STARTPAGE_WHATSNEW == 'true') {
        if (substr(basename($PHP_SELF), 0, 8) != 'advanced') {
            require_once(DIR_FS_BOXES . 'whats_new.php');
        }
    }
}

// -----------------------------------------------------------------------------------------
// Blog (MRH-Erweiterung)
// -----------------------------------------------------------------------------------------
if (defined('MODULE_BLOG_STATUS') && MODULE_BLOG_STATUS == 'true') {
    $smarty->assign('link_blog', xtc_href_link(FILENAME_BLOG, ''));
    if (MODULE_BLOG_BOX_CATEGORIES == 'true' && is_file(DIR_FS_BOXES . 'blog_categories.php')) {
        require_once(DIR_FS_BOXES . 'blog_categories.php');
    }
    if (MODULE_BLOG_BOX_POSTS == 'true' && is_file(DIR_FS_BOXES . 'blog_posts.php')) {
        require_once(DIR_FS_BOXES . 'blog_posts.php');
    }
    if (MODULE_BLOG_BOX_CATEGORY == 'true' && is_file(DIR_FS_BOXES . 'blog_category.php')) {
        require_once(DIR_FS_BOXES . 'blog_category.php');
    }
}

// -----------------------------------------------------------------------------------------
// ShopVote / Shop Reviews (MailHive) – MRH-Erweiterung
// -----------------------------------------------------------------------------------------
if (defined('MH_ROOT_PATH')) {
    if (is_file(DIR_FS_CATALOG . 'includes/external/mailhive/configbeez/config_shopvoting/classes/Shopvoting_widget.php')) {
        require_once(DIR_FS_CATALOG . 'includes/external/mailhive/configbeez/config_shopvoting/classes/Shopvoting_widget.php');
        $shopvoting = new Shopvoting_widget();
        $output = $shopvoting->output();
        $output = preg_replace('~<link [^>]+/>~', '', $output);
        $output = str_replace('<a href=', '<a target="_blank" href=', $output);
        if (preg_match('~>([^<>]+)</h3>~', $output, $matches)) {
            $box_title = (!empty($matches[1]) ? trim($matches[1]) : '');
            if (!empty($box_title)) {
                $output = str_replace('>&nbsp;</a>', '>' . $box_title . '</a>', $output);
                $output = preg_replace('~<a target="_blank" href="([^"]+)">~', '<a target="_blank" href="$1" title="' . $box_title . '">', $output);
            }
        }
        $smarty->assign('box_shopvoting', $output);
    }
}

// -----------------------------------------------------------------------------------------
// Affiliate (MRH-Erweiterung)
// -----------------------------------------------------------------------------------------
if (defined('MODULE_AFFILIATE_STATUS') && MODULE_AFFILIATE_STATUS == 'true') {
    if (is_file(DIR_FS_BOXES . 'affiliate.php')) {
        require_once(DIR_FS_BOXES . 'affiliate.php');
    }
}

// -----------------------------------------------------------------------------------------
// Smarty Zuweisungen
// -----------------------------------------------------------------------------------------

// Startseite
$smarty->assign('home', ((basename($PHP_SELF) == FILENAME_DEFAULT && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id'])) ? 1 : 0));

// Fullcontent
$smarty->assign('fullcontent', $is_fullcontent);

// Bestseller-Seiten
$smarty->assign('bestseller', false);
$bestsellers = array(
    FILENAME_DEFAULT,
    FILENAME_LOGOFF,
    FILENAME_CHECKOUT_SUCCESS,
    FILENAME_SHOPPING_CART,
    FILENAME_NEWSLETTER,
);
if (in_array(basename($PHP_SELF), $bestsellers) && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id'])) {
    if (is_file(DIR_FS_BOXES . 'best_sellers.php')) {
        require_once(DIR_FS_BOXES . 'best_sellers.php');
    }
    $smarty->assign('bestseller', true);
}

// Template-Pfad
$smarty->assign('tpl_path', DIR_WS_BASE . 'templates/' . CURRENT_TEMPLATE . '/');

// -----------------------------------------------------------------------------------------
// Kategorieerweiterungen (Kategorien auf Startseite) - fivebytes
// -----------------------------------------------------------------------------------------
defined('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_SPECIALS') or define('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_SPECIALS', 'true');
defined('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_WHATSNEW') or define('MODULE_SYSTEM_FIVEBYTES_CATEGORIES_ADDS_STARTPAGE_WHATSNEW', 'true');

// -----------------------------------------------------------------------------------------
// EOC require boxes
// -----------------------------------------------------------------------------------------
