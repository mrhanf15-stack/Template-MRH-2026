<?php
/**
 * MRH 2026 Smarty Plugin: contentAnywhere
 * 
 * Kompatibilitaets-Wrapper fuer das contentAnywhere Plugin.
 * Laedt Content-Manager-Inhalte anhand der content_id.
 * 
 * Verwendung in Smarty:
 * {contentAnywhere content_id=42}
 * 
 * HINWEIS: Dieses Plugin ist identisch mit dem Original aus dem BS4-Template.
 * Es wird hier nur als Fallback bereitgestellt, falls das System-Plugin
 * nicht vorhanden ist.
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string HTML-Content
 */
function smarty_function_contentAnywhere($params, &$smarty) {
    if (empty($params['content_id'])) {
        return '';
    }
    
    $content_id = (int)$params['content_id'];
    
    // Pruefen ob die Funktion bereits vom System bereitgestellt wird
    if (function_exists('get_content_anywhere')) {
        return get_content_anywhere($content_id);
    }
    
    // Fallback: Direkte Datenbankabfrage
    if (!defined('TABLE_CONTENT_MANAGER')) {
        return '';
    }
    
    $language_id = isset($_SESSION['languages_id']) ? (int)$_SESSION['languages_id'] : 1;
    
    $query = xtc_db_query(
        "SELECT content_text FROM " . TABLE_CONTENT_MANAGER . " 
         WHERE content_group = " . $content_id . " 
         AND languages_id = " . $language_id . " 
         AND content_status = 1 
         LIMIT 1"
    );
    
    if (xtc_db_num_rows($query) > 0) {
        $row = xtc_db_fetch_array($query);
        return $row['content_text'];
    }
    
    return '';
}
