<?php
/*
-----------------------------------------------------------------------------------------
 REVPLUS - TEMPLATEKONFIGURATOR
 FUER FARBEN UND LAYOUT-ELEMENTE

 IN DER DEMO IMMER SICHTBAR, BEI LIVESHOPS NUR FUER ADMINS EINSEHBAR.
 BESONDERHEIT IN DEMO: ZURUECKSETZEN DER WERTE DURCH CRONJOB (ALLE 30 MIN)

 GEFIXT: Speichert nur Farb-Keys (nicht ganzes $_POST mit Submit-Button)
 ERWEITERT: Sekundaerfarbe (tpl-secondary-color) hinzugefuegt
 ERWEITERT: Details/Wishlist/Compare Button-Variablen (2026-04-08)
 FIX: Merge-Logik – Farben/Buttons speichern ueberschreiben sich nicht mehr (2026-04-09)
-----------------------------------------------------------------------------------------
*/

/**
 * Die Box soll entweder nur dann ausgefuehrt werden,
 * wenn wir uns innerhalb der RevPLUS Demo-Installation
 * befinden, oder der eingeloggte Nutzer ein Administra-
 * tor ist.
 * @see https://www.modifiedtemplate.de/demo-revplus/
 */
if (stristr($_SERVER['SERVER_NAME'], 'modifiedtemplate.de') || (isset($_SESSION['customers_status']['customers_status']) && $_SESSION['customers_status']['customers_status'] == 0)) {
        // INCLUDE SMARTY
        include(DIR_FS_BOXES_INC .'smarty_default.php');
        defined('JSON_PRETTY_PRINT') or define('JSON_PRETTY_PRINT', FALSE);

        // SET FILE PATHS
        $myTplFilePath           = DIR_FS_CATALOG .'templates/'. CURRENT_TEMPLATE .'/';
        $fileDefaultColors       = $myTplFilePath .'config/default_colors.json';      // COLOR DEFAULT
        $fileCustomColors        = $myTplFilePath .'config/colors.json';              // COLOR CUSTOM
        $fileDefaultTplSettings  = $myTplFilePath .'config/default_tplsettings.json'; // SETTINGS DEFAULT
        $fileTplSettings         = $myTplFilePath .'config/tplsettings.json';         // SETTINGS CUSTOM
        $fileDefaultLogos        = $myTplFilePath .'config/default_logos.json';       // LOGOS DEFAULT
        $fileCustomLogos         = $myTplFilePath .'config/logos.json';               // LOGOS CUSTOM
        $fileDefaultSocial       = $myTplFilePath .'config/default_social.json';      // SOCIAL DEFAULT
        $fileCustomSocial        = $myTplFilePath .'config/social.json';              // SOCIAL CUSTOM

        // GET DEFAULT COLOR CONFIGURATION
        $jsonDefaultColors = file_get_contents($fileDefaultColors,0);
        $arrDefaultColors  = json_decode($jsonDefaultColors, TRUE);

        // ======================================================================
        // Liste aller gueltigen Farb-Keys (nur diese werden gespeichert)
        // ======================================================================
        $validColorKeys = [
                'tpl-main-color',
                'tpl-main-color-2',
                'tpl-secondary-color',
                'tpl-bg-color',
                'tpl-bg-color-2',
                'tpl-bg-productbox',
                'tpl-bg-footer',
                'tpl-text-standard',
                'tpl-text-headings',
                'tpl-text-button',
                'tpl-text-footer',
                'tpl-text-footer-headings',
                'tpl-menu-bg',
                'tpl-menu-hover',
                'tpl-menu-text',
                'tpl-menu-active',
                'tpl-topbar-bg',
                'tpl-topbar-text',
                'tpl-sticky-bg',
                'tpl-sticky-text',
                'tpl-btn-primary-bg',
                'tpl-btn-primary-text',
                'tpl-btn-primary-hover',
                'tpl-btn-secondary-bg',
                'tpl-btn-secondary-text',
                'tpl-btn-secondary-hover',
                'tpl-btn-success-bg',
                'tpl-btn-success-text',
                'tpl-btn-success-hover',
                'tpl-btn-danger-bg',
                'tpl-btn-danger-text',
                'tpl-btn-danger-hover',
                'tpl-btn-warning-bg',
                'tpl-btn-warning-text',
                'tpl-btn-warning-hover',
                'tpl-btn-info-bg',
                'tpl-btn-info-text',
                'tpl-btn-info-hover',
                'tpl-btn-light-bg',
                'tpl-btn-light-text',
                'tpl-btn-light-hover',
                'tpl-btn-dark-bg',
                'tpl-btn-dark-text',
                'tpl-btn-dark-hover',
                'tpl-btn-outline-border',
                'tpl-btn-outline-text',
                'tpl-btn-outline-hover',
                'tpl-btn-outline-primary-bg',
                'tpl-btn-outline-primary-text',
                'tpl-btn-outline-primary-hover',
                'tpl-btn-outline-secondary-bg',
                'tpl-btn-outline-secondary-text',
                'tpl-btn-outline-secondary-hover',
                'tpl-btn-outline-success-bg',
                'tpl-btn-outline-success-text',
                'tpl-btn-outline-success-hover',
                'tpl-btn-outline-danger-bg',
                'tpl-btn-outline-danger-text',
                'tpl-btn-outline-danger-hover',
                'tpl-btn-outline-warning-bg',
                'tpl-btn-outline-warning-text',
                'tpl-btn-outline-warning-hover',
                'tpl-btn-outline-info-bg',
                'tpl-btn-outline-info-text',
                'tpl-btn-outline-info-hover',
                'tpl-btn-outline-light-bg',
                'tpl-btn-outline-light-text',
                'tpl-btn-outline-light-hover',
                'tpl-btn-outline-dark-bg',
                'tpl-btn-outline-dark-text',
                'tpl-btn-outline-dark-hover',
                'tpl-btn-express-bg',
                'tpl-btn-express-text',
                'tpl-btn-express-hover',
                'tpl-btn-details-bg',
                'tpl-btn-details-text',
                'tpl-btn-details-hover',
                'tpl-btn-wishlist-bg',
                'tpl-btn-wishlist-text',
                'tpl-btn-wishlist-hover',
                'tpl-btn-compare-bg',
                'tpl-btn-compare-text',
                'tpl-btn-compare-hover',
                // FAQ Accordion v3.0
                'tpl-faq-header-bg',
                'tpl-faq-header-gradient',
                'tpl-faq-header-text',
                'tpl-faq-header-radius',
                'tpl-faq-subheader-bg',
                'tpl-faq-subheader-gradient',
                'tpl-faq-subheader-text',
                'tpl-faq-card-bg',
                'tpl-faq-card-border',
                'tpl-faq-card-radius',
                'tpl-faq-accent',
                'tpl-faq-btn-bg',
                'tpl-faq-btn-text',
                'tpl-faq-btn-hover-bg',
                'tpl-faq-btn-hover-text',
                'tpl-faq-btn-active-bg',
                'tpl-faq-btn-active-text',
                'tpl-faq-btn-active-hover',
                'tpl-faq-icon-color',
                'tpl-faq-icon-active',
                'tpl-faq-chevron-bg',
                'tpl-faq-chevron-color',
                'tpl-faq-chevron-active-bg',
                'tpl-faq-chevron-active-color',
                'tpl-faq-body-bg',
                'tpl-faq-body-border',
                'tpl-faq-body-text',
                'tpl-faq-grid-cols',
                'tpl-faq-grid-gap',
                'tpl-faq-grid-gap-md',
        ];

        /**
         * 1.
         *
         * KONFIGURATION SCHREIBEN
         *
         * FIX 2026-04-09: MERGE-LOGIK
         * Bestehende colors.json wird zuerst gelesen, dann werden NUR die Keys
         * ueberschrieben, die tatsaechlich im POST gesendet wurden.
         * So ueberschreiben sich "Farben speichern" und "Button-Farben speichern"
         * nicht mehr gegenseitig.
         */
        if (isset($_POST['submit-colorsettings'])) {
                // Bestehende colors.json lesen
                $existingColors = [];
                if (file_exists($fileCustomColors)) {
                        $existingJson = file_get_contents($fileCustomColors, 0);
                        $existingColors = json_decode($existingJson, TRUE);
                        if (!is_array($existingColors)) $existingColors = [];
                }

                // Nur Keys ueberschreiben, die tatsaechlich im POST gesendet wurden
                foreach ($validColorKeys as $key) {
                        if (isset($_POST[$key]) && !empty(trim($_POST[$key]))) {
                                // Key ist im POST → neuen Wert uebernehmen
                                $existingColors[$key] = trim($_POST[$key]);
                        } elseif (!isset($existingColors[$key]) && isset($arrDefaultColors[$key])) {
                                // Key existiert weder im POST noch in der bestehenden JSON → Default setzen
                                $existingColors[$key] = $arrDefaultColors[$key];
                        }
                        // Wenn Key nicht im POST aber in existingColors → BEIBEHALTEN (kein Reset!)
                }

                $jsonCustomColors = json_encode($existingColors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($fileCustomColors, $jsonCustomColors);
        }

        if (isset($_POST['submit-tplsettings'])) {
                // Nur bekannte Settings-Keys speichern
                $validTplKeys = ['tpl_cfg_ssl', 'tpl_cfg_ts', 'tpl_cfg_menu', 'tpl_cfg_infinitescroll', 'tpl_cfg_barrierefreiTool'];
                $saveTpl = [];
                foreach ($validTplKeys as $key) {
                        if (isset($_POST[$key])) {
                                $saveTpl[$key] = trim($_POST[$key]);
                        }
                }
                $jsonTplSettings = json_encode($saveTpl, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($fileTplSettings, $jsonTplSettings);
        }

        if (isset($_POST['submit-logosettings'])) {
                // Nur Logo-Arrays speichern
                $saveLogos = [
                        'tpl_cfg_payment_logos'  => isset($_POST['tpl_cfg_payment_logos']) ? $_POST['tpl_cfg_payment_logos'] : [],
                        'tpl_cfg_shipping_logos' => isset($_POST['tpl_cfg_shipping_logos']) ? $_POST['tpl_cfg_shipping_logos'] : [],
                ];
                $jsonLogoSettings = json_encode($saveLogos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($fileCustomLogos, $jsonLogoSettings);
        }

        if (isset($_POST['submit-socialsettings'])) {
                // Nur Social-Media-Keys speichern
                $validSocialKeys = ['facebook', 'twitter', 'instagram', 'pinterest', 'linkedin', 'tiktok', 'youtube'];
                $saveSocial = [];
                foreach ($validSocialKeys as $key) {
                        $saveSocial[$key] = isset($_POST[$key]) ? filter_var(trim($_POST[$key]), FILTER_SANITIZE_URL) : '';
                }
                $jsonSocialSettings = json_encode($saveSocial, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($fileCustomSocial, $jsonSocialSettings);
        }

        /**
         * 2.
         *
         * TEMPLATE DEMO
         *
         * In der RevPLUS-Standard-Installation werden alle Kon-
         * figurationswerte mithilfe eines angelegten CronJobs
         * alle 30 Minuten auf den Standard zurueckgesetzt. Das
         * sollte bei einer Kunden-Installation natuerlich nicht
         * passieren, daher wird der SERVER_NAME hier erneut ab-
         * gefragt.
         */
        if (stristr($_SERVER['SERVER_NAME'], 'modifiedtemplate.de') && isset($_GET['reset-revplus'])) {
                // RESET COLORS
                file_put_contents($fileCustomColors, $jsonDefaultColors);

                // RESET TPL SETTINGS
                $jsonDefaultTplSettings = file_get_contents($fileDefaultTplSettings,0);
                file_put_contents($fileTplSettings, $jsonDefaultTplSettings);

                // RESET LOGOS SETTINGS
                if (file_exists($fileDefaultLogos)) {
                        $jsonDefaultLogos = file_get_contents($fileDefaultLogos,0);
                        file_put_contents($fileCustomLogos, $jsonDefaultLogos);
                }

                // RESET SOCIAL SETTINGS
                if (file_exists($fileDefaultSocial)) {
                        $jsonDefaultSocial = file_get_contents($fileDefaultSocial,0);
                        file_put_contents($fileCustomSocial, $jsonDefaultSocial);
                }
        }

        /**
         * 3.
         *
         * TEMPLATE RENDERN
         *
         * Als letztes wird das Template gerendert. Die Konfigu-
         * rationswerte werden dabei frisch aus den jeweiligen
         * JSON-Dateien ausgelesen.
         */
        // GET CUSTOMER'S COLORS CONFIGURATION
        $jsonCustomColors  = file_get_contents($fileCustomColors,0);
        $arrCustomColors   = json_decode($jsonCustomColors, TRUE);

        // MERGE DEFAULT COLORS WITH CUSTOM COLORS
        if (is_array($arrCustomColors) && count($arrCustomColors)) {
                $arrCustomColors = array_merge($arrDefaultColors, $arrCustomColors);
        }
        else {
                $arrCustomColors = $arrDefaultColors;
        }

        // GET CUSTOMER'S LOGOS CONFIGURATION
        $arrCustomLogos = [];

        if (file_exists($fileCustomLogos)) {
                $jsonCustomLogos = file_get_contents($fileCustomLogos, 0);
                $arrCustomLogos = json_decode($jsonCustomLogos, TRUE);
        }

        // DEFAULT LOGOS WENN KEINE KONFIGURATION EXISTIERT
        $defaultPaymentLogos = ['vorkasse', 'paypal', 'kreditkarten', 'applepay', 'googlepay', 'amazon', 'klarna', 'lastschrift', 'rechnung'];
        $defaultShippingLogos = ['hermes', 'dhl', 'dpd', 'ups', 'gls', 'fedex'];

        $tpl_cfg_payment_logos = isset($arrCustomLogos['tpl_cfg_payment_logos']) ? $arrCustomLogos['tpl_cfg_payment_logos'] : $defaultPaymentLogos;
        $tpl_cfg_shipping_logos = isset($arrCustomLogos['tpl_cfg_shipping_logos']) ? $arrCustomLogos['tpl_cfg_shipping_logos'] : $defaultShippingLogos;

        // GET CUSTOMER'S SOCIAL MEDIA CONFIGURATION
        $arrCustomSocial = [];

        if (file_exists($fileCustomSocial)) {
                $jsonCustomSocial = file_get_contents($fileCustomSocial, 0);
                $arrCustomSocial = json_decode($jsonCustomSocial, TRUE);
        }

        // DEFAULT SOCIAL MEDIA LINKS WENN KEINE KONFIGURATION EXISTIERT
        $defaultSocialLinks = [
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'pinterest' => '',
                'linkedin' => '',
                'tiktok' => '',
                'youtube' => '',
        ];

        $tpl_cfg_social_links = array_merge($defaultSocialLinks, $arrCustomSocial);

        // ASSIGN TEMPLATE VARIABLES
        $box_smarty->assign(array(
                'tpl_cfg_colors' => $arrCustomColors,
                'tpl_cfg_ts'     => $smarty->get_template_vars('tpl_cfg_ts'),
                'tpl_cfg_ssl'    => $smarty->get_template_vars('tpl_cfg_ssl'),
                'tpl_cfg_menu'   => $smarty->get_template_vars('tpl_cfg_menu'),
                'tpl_cfg_infinitescroll'   => $smarty->get_template_vars('tpl_cfg_infinitescroll'),
                'tpl_cfg_barrierefreiTool'   => $smarty->get_template_vars('tpl_cfg_barrierefreiTool'),
                'tpl_cfg_payment_logos' => $tpl_cfg_payment_logos,
                'tpl_cfg_shipping_logos' => $tpl_cfg_shipping_logos,
                'tpl_cfg_social_links' => $tpl_cfg_social_links,
        ));

        $box_templateconfig = $box_smarty->fetch(CURRENT_TEMPLATE .'/boxes/box_templateconfig.html');
        $smarty->assign('box_templateconfig', $box_templateconfig);

        // Seitenreload nach erfolgreicher Konfiguration (ohne exit)
        if (isset($_POST['submit-colorsettings']) ||
                isset($_POST['submit-tplsettings']) ||
                isset($_POST['submit-logosettings']) ||
                isset($_POST['submit-socialsettings'])) {

                // Redirect ohne exit - Browser macht automatisch neuen GET-Request
                echo '<script>setTimeout(function(){window.location.href = window.location.pathname + window.location.search;}, 300);</script>';
        }
}

?>
