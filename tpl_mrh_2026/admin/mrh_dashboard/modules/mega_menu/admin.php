<?php
/* =====================================================================
   MRH Mega-Menü Manager – Admin Panel
   
   Wird als Accordion-Sektion im Template-Konfigurator eingebunden.
   Liest Kategorien aus der modified eCommerce Datenbank und ermöglicht
   die Konfiguration der Mega-Dropdown-Spalten pro Hauptkategorie.
   
   Speichert die Konfiguration als JSON in config/module_mega_menu.json
   ===================================================================== */

if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return;
}

// Dashboard-Kern laden
$dashboard_core = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/includes/mrh_dashboard.php';
if (file_exists($dashboard_core)) {
    require_once($dashboard_core);
}

$dashboard = MRH_Dashboard::getInstance();

// === Kategorien aus der Datenbank lesen ===
$language_id = (int)$_SESSION['languages_id'];
if ($language_id < 1) $language_id = 2; // Deutsch als Fallback

/**
 * Alle Kategorien mit Eltern-Kind-Beziehung laden
 */
function mrh_get_categories_tree($language_id) {
    $tree = [];
    
    // Hauptkategorien (parent_id = 0, also Top-Level)
    $query = xtc_db_query("
        SELECT c.categories_id, cd.categories_name, c.parent_id, c.sort_order, c.categories_status
        FROM " . TABLE_CATEGORIES . " c
        JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON c.categories_id = cd.categories_id
        WHERE cd.language_id = " . $language_id . "
        AND c.categories_status = 1
        ORDER BY c.sort_order, cd.categories_name
    ");
    
    $all_cats = [];
    while ($row = xtc_db_fetch_array($query)) {
        $all_cats[$row['categories_id']] = $row;
    }
    
    // Baum aufbauen
    foreach ($all_cats as $id => $cat) {
        if ($cat['parent_id'] == 0) {
            // Top-Level Kategorie
            $tree[$id] = [
                'id' => $id,
                'name' => $cat['categories_name'],
                'sort_order' => $cat['sort_order'],
                'children' => []
            ];
        }
    }
    
    // Level 1 Kinder zuordnen
    foreach ($all_cats as $id => $cat) {
        if ($cat['parent_id'] > 0 && isset($tree[$cat['parent_id']])) {
            $tree[$cat['parent_id']]['children'][$id] = [
                'id' => $id,
                'name' => $cat['categories_name'],
                'sort_order' => $cat['sort_order'],
                'children' => []
            ];
        }
    }
    
    // Level 2 Kinder zuordnen (Unterkategorien der Unterkategorien)
    foreach ($all_cats as $id => $cat) {
        if ($cat['parent_id'] > 0) {
            foreach ($tree as $topId => &$topCat) {
                if (isset($topCat['children'][$cat['parent_id']])) {
                    $topCat['children'][$cat['parent_id']]['children'][$id] = [
                        'id' => $id,
                        'name' => $cat['categories_name'],
                        'sort_order' => $cat['sort_order']
                    ];
                }
            }
            unset($topCat);
        }
    }
    
    return $tree;
}

$categories_tree = mrh_get_categories_tree($language_id);

// === Formular-Verarbeitung: Mega-Menü Config speichern ===
if (isset($_POST['submit-megamenu'])) {
    $megamenu_config = [];
    
    // Für jede Hauptkategorie die Spalten-Konfiguration speichern
    if (isset($_POST['megamenu']) && is_array($_POST['megamenu'])) {
        foreach ($_POST['megamenu'] as $parent_id => $parent_config) {
            $parent_id = (int)$parent_id;
            $megamenu_config[$parent_id] = [
                'enabled' => isset($parent_config['enabled']) ? 1 : 0,
                'columns' => []
            ];
            
            if (isset($parent_config['columns']) && is_array($parent_config['columns'])) {
                foreach ($parent_config['columns'] as $col_idx => $col_config) {
                    $col_idx = (int)$col_idx;
                    $column = [
                        'title' => isset($col_config['title']) ? trim($col_config['title']) : '',
                        'icon' => isset($col_config['icon']) ? trim($col_config['icon']) : '',
                        'items' => []
                    ];
                    
                    // Items (Kategorie-IDs) in der richtigen Reihenfolge
                    if (isset($col_config['items']) && is_array($col_config['items'])) {
                        foreach ($col_config['items'] as $sort => $item_id) {
                            $item_id = (int)$item_id;
                            if ($item_id > 0) {
                                $column['items'][] = $item_id;
                            }
                        }
                    }
                    
                    $megamenu_config[$parent_id]['columns'][$col_idx] = $column;
                }
            }
        }
    }
    
    // Max Items pro Spalte
    $max_items = isset($_POST['megamenu_max_items']) ? (int)$_POST['megamenu_max_items'] : 5;
    
    MRH_Dashboard::setConfig('mega_menu', 'columns', $megamenu_config);
    MRH_Dashboard::setConfig('mega_menu', 'max_items_per_column', $max_items);
    
    $GLOBALS['mrh_megamenu_message'] = '<div class="alert alert-success alert-dismissible fade show mx-3 mt-2" role="alert">
        <i class="fa fa-check-circle me-1"></i> Mega-Menü Konfiguration gespeichert!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

// === Aktuelle Konfiguration laden ===
$megamenu_config = MRH_Dashboard::getConfig('mega_menu', 'columns', []);
$max_items = MRH_Dashboard::getConfig('mega_menu', 'max_items_per_column', 5);

// Erfolgsmeldung
if (!empty($GLOBALS['mrh_megamenu_message'])) {
    echo $GLOBALS['mrh_megamenu_message'];
}

// === Hilfsfunktion: Alle verfügbaren Kategorien als flache Liste (für Dropdown) ===
function mrh_get_flat_categories($tree, $prefix = '') {
    $flat = [];
    foreach ($tree as $cat) {
        $flat[$cat['id']] = $prefix . $cat['name'];
        if (!empty($cat['children'])) {
            $flat += mrh_get_flat_categories($cat['children'], $prefix . '— ');
        }
    }
    return $flat;
}
?>

<!-- Mega-Menü Manager Admin-UI -->
<form id="megamenu-settings" method="post" action="">
    
    <div class="mb-3 mx-0">
        <label class="form-label"><strong>Max. Einträge pro Spalte:</strong></label>
        <select name="megamenu_max_items" class="form-select" style="width:auto;display:inline-block;">
            <?php for ($i = 3; $i <= 8; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo ($max_items == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
    </div>
    
    <div class="accordion" id="megamenuAccordion">
    <?php 
    $cat_index = 0;
    foreach ($categories_tree as $parent_id => $parent_cat): 
        // Nur Kategorien mit Unterkategorien anzeigen
        if (empty($parent_cat['children'])) continue;
        
        $cat_index++;
        $is_enabled = isset($megamenu_config[$parent_id]['enabled']) ? $megamenu_config[$parent_id]['enabled'] : 1;
        $saved_columns = isset($megamenu_config[$parent_id]['columns']) ? $megamenu_config[$parent_id]['columns'] : [];
        
        // Alle verfügbaren Unterkategorien (Level 1 + Level 2) als flache Liste
        $available_cats = mrh_get_flat_categories($parent_cat['children']);
    ?>
        <div class="accordion-item mb-2 border rounded">
            <h2 class="accordion-header" id="megamenu-head-<?php echo $parent_id; ?>">
                <button class="accordion-button <?php echo ($cat_index > 1) ? 'collapsed' : ''; ?> py-2" 
                        type="button" data-bs-toggle="collapse" 
                        data-bs-target="#megamenu-body-<?php echo $parent_id; ?>"
                        aria-expanded="<?php echo ($cat_index == 1) ? 'true' : 'false'; ?>">
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch mb-0" onclick="event.stopPropagation();">
                            <input class="form-check-input" type="checkbox" 
                                   name="megamenu[<?php echo $parent_id; ?>][enabled]" value="1"
                                   <?php echo $is_enabled ? 'checked' : ''; ?>
                                   id="megamenu-enable-<?php echo $parent_id; ?>">
                        </div>
                        <strong><?php echo htmlspecialchars($parent_cat['name']); ?></strong>
                        <span class="badge bg-secondary"><?php echo count($available_cats); ?> Kategorien</span>
                        <small class="text-muted">cPath=<?php echo $parent_id; ?></small>
                    </div>
                </button>
            </h2>
            <div id="megamenu-body-<?php echo $parent_id; ?>" 
                 class="accordion-collapse collapse <?php echo ($cat_index == 1) ? 'show' : ''; ?>"
                 data-bs-parent="#megamenuAccordion">
                <div class="accordion-body">
                    
                    <div class="row" id="columns-<?php echo $parent_id; ?>">
                        <?php for ($col = 0; $col < 3; $col++): 
                            $col_title = isset($saved_columns[$col]['title']) ? $saved_columns[$col]['title'] : '';
                            $col_icon = isset($saved_columns[$col]['icon']) ? $saved_columns[$col]['icon'] : '';
                            $col_items = isset($saved_columns[$col]['items']) ? $saved_columns[$col]['items'] : [];
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light py-2">
                                    <strong>Spalte <?php echo ($col + 1); ?></strong>
                                </div>
                                <div class="card-body p-2">
                                    <!-- Spalten-Überschrift -->
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="megamenu[<?php echo $parent_id; ?>][columns][<?php echo $col; ?>][title]"
                                               value="<?php echo htmlspecialchars($col_title); ?>"
                                               placeholder="Spalten-Überschrift">
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="megamenu[<?php echo $parent_id; ?>][columns][<?php echo $col; ?>][icon]"
                                               value="<?php echo htmlspecialchars($col_icon); ?>"
                                               placeholder="Icon (z.B. 🌿 oder fa-leaf)">
                                    </div>
                                    
                                    <!-- Kategorie-Einträge (sortierbar) -->
                                    <div class="megamenu-sortable list-group list-group-flush" 
                                         data-parent="<?php echo $parent_id; ?>" 
                                         data-col="<?php echo $col; ?>">
                                        <?php 
                                        $item_sort = 0;
                                        foreach ($col_items as $item_id): 
                                            $item_name = isset($available_cats[$item_id]) ? $available_cats[$item_id] : 'Kategorie #' . $item_id;
                                        ?>
                                        <div class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-1 px-2 megamenu-item" 
                                             data-cat-id="<?php echo $item_id; ?>">
                                            <i class="fa fa-grip-vertical text-muted" style="cursor:grab;"></i>
                                            <span class="flex-grow-1 small"><?php echo htmlspecialchars($item_name); ?></span>
                                            <small class="text-muted">ID:<?php echo $item_id; ?></small>
                                            <input type="hidden" 
                                                   name="megamenu[<?php echo $parent_id; ?>][columns][<?php echo $col; ?>][items][<?php echo $item_sort; ?>]" 
                                                   value="<?php echo $item_id; ?>">
                                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 megamenu-remove-item" 
                                                    title="Entfernen">&times;</button>
                                        </div>
                                        <?php 
                                            $item_sort++;
                                        endforeach; 
                                        ?>
                                    </div>
                                    
                                    <!-- Kategorie hinzufügen -->
                                    <div class="mt-2">
                                        <select class="form-select form-select-sm megamenu-add-select" 
                                                data-parent="<?php echo $parent_id; ?>" 
                                                data-col="<?php echo $col; ?>">
                                            <option value="">+ Kategorie hinzufügen...</option>
                                            <?php foreach ($available_cats as $cat_id => $cat_name): ?>
                                            <option value="<?php echo $cat_id; ?>"><?php echo htmlspecialchars($cat_name); ?> (ID:<?php echo $cat_id; ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="text-muted small mt-1">
                        <i class="fa fa-info-circle"></i> 
                        Ziehe Einträge per Drag &amp; Drop um die Reihenfolge zu ändern. 
                        System-URL: <code>index.php?cPath=<?php echo $parent_id; ?>_[ID]</code>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    
    <div class="mt-3">
        <input type="submit" name="submit-megamenu" id="submit-megamenu" 
               class="btn btn-success btn-lg btn-block" 
               value="Mega-Menü Konfiguration speichern">
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Kategorie hinzufügen ===
    document.querySelectorAll('.megamenu-add-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var catId = this.value;
            if (!catId) return;
            
            var catName = this.options[this.selectedIndex].text;
            var parentId = this.dataset.parent;
            var colIdx = this.dataset.col;
            var sortable = this.closest('.card-body').querySelector('.megamenu-sortable');
            
            // Prüfen ob bereits vorhanden
            var existing = sortable.querySelector('[data-cat-id="' + catId + '"]');
            if (existing) {
                alert('Diese Kategorie ist bereits in dieser Spalte!');
                this.value = '';
                return;
            }
            
            // Neues Item erstellen
            var itemCount = sortable.querySelectorAll('.megamenu-item').length;
            var div = document.createElement('div');
            div.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2 py-1 px-2 megamenu-item';
            div.dataset.catId = catId;
            div.innerHTML = '<i class="fa fa-grip-vertical text-muted" style="cursor:grab;"></i>' +
                '<span class="flex-grow-1 small">' + catName.replace(/ \(ID:\d+\)/, '') + '</span>' +
                '<small class="text-muted">ID:' + catId + '</small>' +
                '<input type="hidden" name="megamenu[' + parentId + '][columns][' + colIdx + '][items][' + itemCount + ']" value="' + catId + '">' +
                '<button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 megamenu-remove-item" title="Entfernen">&times;</button>';
            
            sortable.appendChild(div);
            this.value = '';
            
            // Hidden-Input Namen neu nummerieren
            reindexItems(sortable, parentId, colIdx);
        });
    });
    
    // === Kategorie entfernen (Event Delegation) ===
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('megamenu-remove-item')) {
            var item = e.target.closest('.megamenu-item');
            var sortable = item.closest('.megamenu-sortable');
            var parentId = sortable.dataset.parent;
            var colIdx = sortable.dataset.col;
            
            item.remove();
            reindexItems(sortable, parentId, colIdx);
        }
    });
    
    // === Drag & Drop Sortierung (natives HTML5) ===
    document.querySelectorAll('.megamenu-sortable').forEach(function(sortable) {
        enableDragSort(sortable);
    });
    
    function enableDragSort(container) {
        var dragItem = null;
        
        container.addEventListener('dragstart', function(e) {
            dragItem = e.target.closest('.megamenu-item');
            if (dragItem) {
                dragItem.style.opacity = '0.4';
                e.dataTransfer.effectAllowed = 'move';
            }
        });
        
        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            var target = e.target.closest('.megamenu-item');
            if (target && target !== dragItem) {
                var rect = target.getBoundingClientRect();
                var midY = rect.top + rect.height / 2;
                if (e.clientY < midY) {
                    container.insertBefore(dragItem, target);
                } else {
                    container.insertBefore(dragItem, target.nextSibling);
                }
            }
        });
        
        container.addEventListener('dragend', function(e) {
            if (dragItem) {
                dragItem.style.opacity = '1';
                reindexItems(container, container.dataset.parent, container.dataset.col);
                dragItem = null;
            }
        });
        
        // Alle Items draggable machen
        container.querySelectorAll('.megamenu-item').forEach(function(item) {
            item.setAttribute('draggable', 'true');
        });
        
        // Neue Items auch draggable machen (MutationObserver)
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('megamenu-item')) {
                        node.setAttribute('draggable', 'true');
                    }
                });
            });
        });
        observer.observe(container, { childList: true });
    }
    
    // === Hidden-Input Namen neu nummerieren nach Sortierung/Entfernung ===
    function reindexItems(container, parentId, colIdx) {
        var items = container.querySelectorAll('.megamenu-item');
        items.forEach(function(item, idx) {
            var input = item.querySelector('input[type="hidden"]');
            if (input) {
                input.name = 'megamenu[' + parentId + '][columns][' + colIdx + '][items][' + idx + ']';
            }
        });
    }
});
</script>
