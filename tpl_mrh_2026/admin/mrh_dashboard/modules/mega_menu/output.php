<?php
/* =====================================================================
   MRH Mega-Menü Manager – Frontend Output
   
   Generiert ein JavaScript-Objekt (window.MRH_MEGAMENU_CONFIG) das
   von mrh-core.js.php konsumiert wird.
   
   Wird als Autoinclude im Template-Footer eingebunden oder direkt
   per PHP-Include in der Template-Ausgabe.
   
   Alle URLs nutzen das System-Format: index.php?cPath=PARENT_CHILD
   Das SEO-Modul von modified eCommerce schreibt diese automatisch um.
   ===================================================================== */

if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return;
}

// Dashboard-Kern laden
$dashboard_core = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/includes/mrh_dashboard.php';
if (file_exists($dashboard_core)) {
    require_once($dashboard_core);
}

$megamenu_config = MRH_Dashboard::getConfig('mega_menu', 'columns', []);
$max_items = MRH_Dashboard::getConfig('mega_menu', 'max_items_per_column', 5);

if (empty($megamenu_config)) {
    return; // Keine Konfiguration vorhanden → Fallback auf JS-Defaults
}

// Sprachabhängige Kategorienamen aus der DB laden
$language_id = (int)$_SESSION['languages_id'];
if ($language_id < 1) $language_id = 2;

// Alle benötigten Kategorie-IDs sammeln
$all_cat_ids = [];
foreach ($megamenu_config as $parent_id => $parent_config) {
    $all_cat_ids[] = (int)$parent_id;
    if (isset($parent_config['columns'])) {
        foreach ($parent_config['columns'] as $col) {
            if (isset($col['items'])) {
                foreach ($col['items'] as $item_id) {
                    $all_cat_ids[] = (int)$item_id;
                }
            }
        }
    }
}
$all_cat_ids = array_unique($all_cat_ids);

// Kategorienamen und Eltern-IDs aus DB laden
$cat_names = [];
$cat_parents = [];
if (!empty($all_cat_ids)) {
    $ids_str = implode(',', $all_cat_ids);
    $query = xtc_db_query("
        SELECT c.categories_id, cd.categories_name, c.parent_id
        FROM " . TABLE_CATEGORIES . " c
        JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON c.categories_id = cd.categories_id
        WHERE cd.language_id = " . $language_id . "
        AND c.categories_id IN (" . $ids_str . ")
    ");
    while ($row = xtc_db_fetch_array($query)) {
        $cat_names[$row['categories_id']] = $row['categories_name'];
        $cat_parents[$row['categories_id']] = $row['parent_id'];
    }
}

/**
 * cPath für eine Kategorie aufbauen (parent_child Format)
 */
function mrh_build_cpath($cat_id, $cat_parents) {
    $path = [(int)$cat_id];
    $current = $cat_id;
    $max_depth = 10; // Schutz vor Endlosschleifen
    
    while (isset($cat_parents[$current]) && $cat_parents[$current] > 0 && $max_depth > 0) {
        $current = $cat_parents[$current];
        array_unshift($path, (int)$current);
        $max_depth--;
    }
    
    return implode('_', $path);
}

// === JSON-Output aufbauen ===
$output = [];

foreach ($megamenu_config as $parent_id => $parent_config) {
    $parent_id = (int)$parent_id;
    
    if (empty($parent_config['enabled'])) {
        continue; // Deaktivierte Kategorie überspringen
    }
    
    $parent_name = isset($cat_names[$parent_id]) ? $cat_names[$parent_id] : '';
    
    $parent_output = [
        'id' => $parent_id,
        'name' => $parent_name,
        'useStaticOnly' => true,
        'columns' => []
    ];
    
    if (isset($parent_config['columns'])) {
        foreach ($parent_config['columns'] as $col_idx => $col) {
            $column = [
                'title' => isset($col['title']) ? $col['title'] : '',
                'icon' => isset($col['icon']) ? $col['icon'] : '',
                'items' => []
            ];
            
            if (isset($col['items'])) {
                $item_count = 0;
                foreach ($col['items'] as $item_id) {
                    $item_id = (int)$item_id;
                    if ($item_count >= $max_items) break;
                    
                    $item_name = isset($cat_names[$item_id]) ? $cat_names[$item_id] : 'Kategorie #' . $item_id;
                    $cpath = mrh_build_cpath($item_id, $cat_parents);
                    
                    $column['items'][] = [
                        'id' => $item_id,
                        'name' => $item_name,
                        'url' => 'index.php?cPath=' . $cpath
                    ];
                    
                    $item_count++;
                }
            }
            
            $parent_output['columns'][] = $column;
        }
    }
    
    $output[$parent_id] = $parent_output;
}

// Als JavaScript-Objekt ausgeben
$json = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<script>
window.MRH_MEGAMENU_CONFIG = <?php echo $json; ?>;
</script>
