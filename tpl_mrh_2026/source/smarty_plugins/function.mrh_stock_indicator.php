<?php
/**
 * MRH 2026 Smarty Plugin: Lagerampel (Stock Indicator)
 * 
 * Zeigt den Lagerstatus als farbige Ampel an.
 * 
 * Verwendung in Smarty:
 * {mrh_stock_indicator quantity=$PRODUCTS_QUANTITY}
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string HTML-Output
 */
function smarty_function_mrh_stock_indicator($params, &$smarty) {
    $qty = isset($params['quantity']) ? (int)$params['quantity'] : 0;
    
    if ($qty > 5) {
        $class = 'mrh-stock-green';
        $text  = 'Sofort lieferbar';
        $icon  = 'fa-check-circle';
    } elseif ($qty > 0) {
        $class = 'mrh-stock-yellow';
        $text  = 'Nur noch wenige verfügbar';
        $icon  = 'fa-exclamation-circle';
    } else {
        $class = 'mrh-stock-red';
        $text  = 'Nicht auf Lager';
        $icon  = 'fa-times-circle';
    }
    
    // Sprachvariablen verwenden falls verfuegbar
    if (defined('MRH_STOCK_GREEN') && $qty > 5) $text = MRH_STOCK_GREEN;
    if (defined('MRH_STOCK_YELLOW') && $qty > 0 && $qty <= 5) $text = MRH_STOCK_YELLOW;
    if (defined('MRH_STOCK_RED') && $qty <= 0) $text = MRH_STOCK_RED;
    
    return '<span class="mrh-stock-indicator ' . $class . '">'
         . '<span class="fa ' . $icon . '"></span> '
         . htmlspecialchars($text)
         . '</span>';
}
