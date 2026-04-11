<?php
/* =====================================================================
   MRH 2026 Template – Icon-Variablen Smarty-Plugin v1.0
   
   Liest config/icons.json und stellt Icon-Konfiguration als:
   1. CSS Custom Properties (--mrh-icon-*) im <style> Block
   2. Smarty-Variablen ($mrh_icon_*) fuer Template-Zugriff
   
   Einbindung in Templates:
     {mrh_icon_vars}                    → Gibt CSS-Variablen aus
     {mrh_icon_vars assign="icons"}     → Weist Icon-Array zu
   
   Verwendung in Templates:
     <i class="{$mrh_icon_cart_class}"></i>
     <i class="{$mrh_icon_wishlist_class}" style="color:{$mrh_icon_wishlist_color}"></i>
   
   v1.0 (2026-04-11): Initiale Version
   
   Pfad: templates/tpl_mrh_2026/source/smarty_plugins/function.mrh_icon_vars.php
   ===================================================================== */

function smarty_function_mrh_icon_vars($params, &$smarty) {
    
    // Pfade
    $tpl_dir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
    $config_file = $tpl_dir . 'config/icons.json';
    $default_file = $tpl_dir . 'config/default_icons.json';
    
    // JSON laden
    $icons_data = [];
    if (file_exists($config_file)) {
        $json = file_get_contents($config_file);
        $icons_data = json_decode($json, true);
    }
    if (empty($icons_data) && file_exists($default_file)) {
        $json = file_get_contents($default_file);
        $icons_data = json_decode($json, true);
    }
    
    if (empty($icons_data) || !isset($icons_data['icons'])) {
        return '';
    }
    
    $icons = $icons_data['icons'];
    $global = isset($icons_data['global']) ? $icons_data['global'] : [];
    $areas = isset($icons_data['areas']) ? $icons_data['areas'] : [];
    
    // Style-Map: style -> FA Prefix
    $style_map = [
        'solid'   => 'fas',
        'regular' => 'far',
        'light'   => 'fal',
        'brands'  => 'fab',
    ];
    
    // Size-Map: size -> CSS-Wert
    $size_map = [
        'xs'  => '0.75em',
        'sm'  => '0.875em',
        'md'  => '1em',
        'lg'  => '1.25em',
        'xl'  => '1.5em',
        '2xl' => '2em',
    ];
    
    // CSS-Variablen generieren
    $css_vars = [];
    $smarty_vars = [];
    
    foreach ($icons as $key => $icon) {
        // Key normalisieren: icon-cart -> cart
        $short_key = str_replace('icon-', '', $key);
        $css_key = str_replace('-', '_', $short_key);
        
        $style = isset($icon['style']) ? $icon['style'] : (isset($global['style']) ? $global['style'] : 'solid');
        $size = isset($icon['size']) ? $icon['size'] : (isset($global['size']) ? $global['size'] : 'md');
        $color = isset($icon['color']) && !empty($icon['color']) ? $icon['color'] : '';
        $class = isset($icon['class']) ? $icon['class'] : '';
        $prefix = isset($style_map[$style]) ? $style_map[$style] : 'fas';
        $full_class = $prefix . ' ' . $class;
        $css_size = isset($size_map[$size]) ? $size_map[$size] : '1em';
        
        // CSS Custom Properties
        $css_vars[] = '--mrh-icon-' . $short_key . '-class: "' . $full_class . '";';
        $css_vars[] = '--mrh-icon-' . $short_key . '-size: ' . $css_size . ';';
        if (!empty($color)) {
            $css_vars[] = '--mrh-icon-' . $short_key . '-color: ' . $color . ';';
        }
        
        // Smarty-Variablen
        $smarty_vars['mrh_icon_' . $css_key . '_class'] = $full_class;
        $smarty_vars['mrh_icon_' . $css_key . '_size'] = $css_size;
        $smarty_vars['mrh_icon_' . $css_key . '_color'] = $color;
        $smarty_vars['mrh_icon_' . $css_key . '_style'] = $style;
        $smarty_vars['mrh_icon_' . $css_key . '_raw'] = $class;
    }
    
    // Bereichs-Overrides als Smarty-Array bereitstellen
    $area_overrides = [];
    foreach ($areas as $area_key => $area_data) {
        if (empty($area_data['enabled'])) continue;
        if (empty($area_data['overrides'])) continue;
        
        foreach ($area_data['overrides'] as $icon_key => $ov) {
            $short_key = str_replace('icon-', '', $icon_key);
            $css_key = str_replace('-', '_', $short_key);
            
            // Override-Werte mit Fallback auf globale Icon-Werte
            $base_icon = isset($icons[$icon_key]) ? $icons[$icon_key] : [];
            $ov_style = isset($ov['style']) ? $ov['style'] : (isset($base_icon['style']) ? $base_icon['style'] : 'solid');
            $ov_class = isset($ov['class']) ? $ov['class'] : (isset($base_icon['class']) ? $base_icon['class'] : '');
            $ov_color = isset($ov['color']) ? $ov['color'] : (isset($base_icon['color']) ? $base_icon['color'] : '');
            $ov_size = isset($ov['size']) ? $ov['size'] : (isset($base_icon['size']) ? $base_icon['size'] : 'md');
            $ov_prefix = isset($style_map[$ov_style]) ? $style_map[$ov_style] : 'fas';
            
            $area_overrides[$area_key]['mrh_icon_' . $css_key . '_class'] = $ov_prefix . ' ' . $ov_class;
            $area_overrides[$area_key]['mrh_icon_' . $css_key . '_color'] = $ov_color;
            $area_overrides[$area_key]['mrh_icon_' . $css_key . '_size'] = isset($size_map[$ov_size]) ? $size_map[$ov_size] : '1em';
        }
    }
    
    // Smarty-Variablen zuweisen
    foreach ($smarty_vars as $var_name => $var_value) {
        $smarty->assign($var_name, $var_value);
    }
    
    // Bereichs-Overrides als Array zuweisen
    $smarty->assign('mrh_icon_areas', $area_overrides);
    
    // Wenn assign-Parameter gesetzt, komplettes Array zuweisen
    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $smarty_vars);
        return '';
    }
    
    // CSS-Block ausgeben
    if (!empty($css_vars)) {
        $output = "\n<style id=\"mrh-icon-vars\">\n:root {\n";
        foreach ($css_vars as $var) {
            $output .= "    " . $var . "\n";
        }
        $output .= "}\n</style>\n";
        return $output;
    }
    
    return '';
}
