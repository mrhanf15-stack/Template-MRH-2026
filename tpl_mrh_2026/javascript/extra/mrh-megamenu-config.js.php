<?php
/* =====================================================================
   MRH Mega-Menü Config – Frontend Output
   
   Wird automatisch über auto_include() in general_bottom.js.php geladen.
   Gibt window.MRH_MEGAMENU_CONFIG als JavaScript-Objekt aus, das von
   mrh-core.js.php konsumiert wird.
   
   Liest die Konfiguration aus der JSON-Datei die vom Dashboard-Admin
   gespeichert wurde. Alle URLs nutzen das System-Format:
   index.php?cPath=PARENT_CHILD
   Das SEO-Modul schreibt diese automatisch um.
   ===================================================================== */

// Sicherheitscheck
if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return;
}

// Dashboard-Kern laden
$dashboard_core = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/includes/mrh_dashboard.php';
if (file_exists($dashboard_core)) {
    require_once($dashboard_core);
}

// Mega-Menü Konfiguration laden
$megamenu_config = MRH_Dashboard::getConfig('mega_menu', 'categories', []);
$max_items = (int)MRH_Dashboard::getConfig('mega_menu', 'max_items_per_column', 5);

// Wenn keine Konfiguration vorhanden → kein Output (JS-Fallback greift)
if (empty($megamenu_config)) {
    return;
}

// Sprachabhängige Kategorienamen aus der DB laden
$language_id = isset($_SESSION['languages_id']) ? (int)$_SESSION['languages_id'] : 2;
if ($language_id < 1) $language_id = 2;

// Alle benötigten Kategorie-IDs sammeln
$all_cat_ids = [];
foreach ($megamenu_config as $parent_id => $parent_data) {
    $all_cat_ids[] = (int)$parent_id;
    if (isset($parent_data['columns']) && is_array($parent_data['columns'])) {
        foreach ($parent_data['columns'] as $col) {
            if (isset($col['items']) && is_array($col['items'])) {
                foreach ($col['items'] as $item_id) {
                    $all_cat_ids[] = (int)$item_id;
                }
            }
        }
    }
}
$all_cat_ids = array_unique(array_filter($all_cat_ids));

if (empty($all_cat_ids)) {
    return;
}

// Kategorienamen und Eltern-IDs aus DB laden
$cat_names = [];
$cat_parents = [];
$ids_str = implode(',', $all_cat_ids);

if (function_exists('xtc_db_query')) {
    $query = xtc_db_query("
        SELECT c.categories_id, cd.categories_name, c.parent_id
        FROM " . TABLE_CATEGORIES . " c
        JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON c.categories_id = cd.categories_id
        WHERE cd.language_id = " . $language_id . "
        AND c.categories_id IN (" . $ids_str . ")
    ");
    while ($row = xtc_db_fetch_array($query)) {
        $cat_names[(int)$row['categories_id']] = $row['categories_name'];
        $cat_parents[(int)$row['categories_id']] = (int)$row['parent_id'];
    }
}

/**
 * cPath für eine Kategorie aufbauen (parent_child Format)
 * Traversiert den Baum nach oben bis zur Root-Kategorie
 */
function mrh_megamenu_build_cpath($cat_id, &$cat_parents) {
    $path = [(int)$cat_id];
    $current = (int)$cat_id;
    $max_depth = 10;
    
    while (isset($cat_parents[$current]) && $cat_parents[$current] > 0 && $max_depth > 0) {
        $current = $cat_parents[$current];
        array_unshift($path, $current);
        $max_depth--;
    }
    
    return implode('_', $path);
}

// === JSON-Output aufbauen ===
$output = [];

foreach ($megamenu_config as $parent_id => $parent_data) {
    $parent_id = (int)$parent_id;
    
    if (empty($parent_data['enabled'])) {
        continue;
    }
    
    $parent_name = isset($cat_names[$parent_id]) ? $cat_names[$parent_id] : '';
    
    $parent_output = [
        'id' => $parent_id,
        'name' => $parent_name,
        'useStaticOnly' => true,
        'columns' => []
    ];
    
    if (isset($parent_data['columns']) && is_array($parent_data['columns'])) {
        foreach ($parent_data['columns'] as $col) {
            $column = [
                'title' => isset($col['title']) ? $col['title'] : '',
                'icon'  => isset($col['icon'])  ? $col['icon']  : 'fa-folder',
                'items' => []
            ];
            
            if (isset($col['items']) && is_array($col['items'])) {
                $item_count = 0;
                foreach ($col['items'] as $item_id) {
                    $item_id = (int)$item_id;
                    if ($item_count >= $max_items) break;
                    
                    $item_name = isset($cat_names[$item_id]) ? $cat_names[$item_id] : 'Kategorie #' . $item_id;
                    $cpath = mrh_megamenu_build_cpath($item_id, $cat_parents);
                    
                    $column['items'][] = [
                        'id'   => $item_id,
                        'name' => $item_name,
                        'url'  => 'index.php?cPath=' . $cpath
                    ];
                    
                    $item_count++;
                }
            }
            
            $parent_output['columns'][] = $column;
        }
    }
    
    $output[$parent_id] = $parent_output;
}

// Als JavaScript-Objekt ausgeben (nur wenn Daten vorhanden)
if (!empty($output)) {
    $json = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<script>
/* MRH Mega-Menü Config – generiert aus Dashboard */
window.MRH_MEGAMENU_CONFIG = <?php echo $json; ?>;
</script>
<?php
}
?>
