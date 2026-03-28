<?php
/**
 * MRH 2026 Smarty Plugin: bs5button
 * 
 * Erzeugt Bootstrap 5.3 konforme Buttons.
 * Ersetzt das alte bs4button Plugin.
 * 
 * Verwendung in Smarty:
 * {bs5button href="url" text="Klick mich" style="success" size="lg" icon="fa-shopping-cart"}
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string HTML-Button
 */
function smarty_function_bs5button($params, &$smarty) {
    $href    = $params['href'] ?? '#';
    $text    = $params['text'] ?? '';
    $style   = $params['style'] ?? 'primary';
    $size    = isset($params['size']) ? ' btn-' . $params['size'] : '';
    $icon    = $params['icon'] ?? '';
    $outline = isset($params['outline']) && $params['outline'] === 'true' ? 'outline-' : '';
    $class   = $params['class'] ?? '';
    $target  = isset($params['target']) ? ' target="' . htmlspecialchars($params['target']) . '"' : '';
    $type    = $params['type'] ?? 'a';
    
    $icon_html = '';
    if ($icon !== '') {
        $icon_html = '<span class="fa ' . htmlspecialchars($icon) . '"></span> ';
    }
    
    $css_class = 'btn btn-' . $outline . htmlspecialchars($style) . $size;
    if ($class !== '') {
        $css_class .= ' ' . htmlspecialchars($class);
    }
    
    if ($type === 'button') {
        return '<button type="submit" class="' . $css_class . '">' . $icon_html . htmlspecialchars($text) . '</button>';
    }
    
    return '<a href="' . htmlspecialchars($href) . '" class="' . $css_class . '"' . $target . '>' . $icon_html . htmlspecialchars($text) . '</a>';
}

/**
 * Rueckwaertskompatibilitaet: bs4button ruft bs5button auf
 */
function smarty_function_bs4button($params, &$smarty) {
    return smarty_function_bs5button($params, $smarty);
}
