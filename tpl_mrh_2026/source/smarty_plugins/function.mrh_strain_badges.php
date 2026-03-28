<?php
/**
 * MRH 2026 Smarty Plugin: Strain-Badges
 * 
 * Extrahiert die wichtigsten Werte aus der Short Description HTML-Tabelle
 * und gibt sie als kompakte Badges/Chips aus (Option A).
 * 
 * Verwendung in Smarty:
 * {mrh_strain_badges short_desc=$product_data.PRODUCTS_SHORT_DESCRIPTION max=3}
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string HTML-Output
 */
function smarty_function_mrh_strain_badges($params, &$smarty) {
    if (empty($params['short_desc'])) {
        return '';
    }
    
    $html = $params['short_desc'];
    $max  = isset($params['max']) ? (int)$params['max'] : 3;
    
    // Prioritaetsliste der Felder die als Badges angezeigt werden sollen
    $priority_fields = [
        'Sorte'            => 'fa-leaf',
        'Geschlecht'       => 'fa-venus-mars',
        'Blütezeit Indoor' => 'fa-clock-o',
        'Blütezeit'        => 'fa-clock-o',
        'THC'              => 'fa-flask',
        'CBD'              => 'fa-plus-circle',
        'Ertrag Indoor'    => 'fa-balance-scale',
        'Ertrag'           => 'fa-balance-scale',
        'Höhe Indoor'      => 'fa-arrows-v',
        'Kreuzung'         => 'fa-random',
        'Geschmack'        => 'fa-lemon-o',
    ];
    
    $badges = [];
    
    // HTML-Tabelle parsen
    if (preg_match_all('/<tr[^>]*>.*?<\/tr>/si', $html, $rows)) {
        foreach ($rows[0] as $row) {
            // Alle td-Inhalte extrahieren
            if (preg_match_all('/<td[^>]*>(.*?)<\/td>/si', $row, $cells)) {
                $label = '';
                $value = '';
                
                foreach ($cells[1] as $idx => $cell) {
                    $clean = strip_tags(trim($cell));
                    if ($clean === '') continue;
                    
                    if ($label === '') {
                        $label = $clean;
                    } else {
                        $value = $clean;
                        break;
                    }
                }
                
                if ($label !== '' && $value !== '') {
                    // Pruefen ob dieses Feld in der Prioritaetsliste ist
                    foreach ($priority_fields as $field => $icon) {
                        if (stripos($label, $field) !== false && count($badges) < $max) {
                            $badges[] = [
                                'label' => $label,
                                'value' => $value,
                                'icon'  => $icon,
                            ];
                            // Feld aus Prioritaetsliste entfernen (kein Duplikat)
                            unset($priority_fields[$field]);
                            break;
                        }
                    }
                }
            }
        }
    }
    
    if (empty($badges)) {
        return '';
    }
    
    // HTML-Output als kompakte Badges
    $output = '<div class="mrh-strain-badges">';
    foreach ($badges as $badge) {
        $output .= '<span class="mrh-strain-badge" title="' . htmlspecialchars($badge['label']) . '">';
        $output .= '<span class="fa ' . $badge['icon'] . '"></span> ';
        $output .= htmlspecialchars($badge['value']);
        $output .= '</span>';
    }
    $output .= '</div>';
    
    return $output;
}
