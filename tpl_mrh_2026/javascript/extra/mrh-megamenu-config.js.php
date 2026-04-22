<?php
/* -----------------------------------------------------------------------------------------
   $Id: mrh-megamenu-config.js.php 1.4.3 2026-04-22 Mr. Hanf $

   MRH Mega-Menu Config - Frontend JavaScript Output
   Autoinclude Hook: ~/templates/YOUR_TEMPLATE/javascript/extra/

   Liest Cache-Datei und gibt NUR eingetragene Links als JS-Objekt aus.
   Unterstuetzt DE/EN/FR/ES + Nav-Links mit MRH_-Sprachkonstanten.
   v1.4.3: Fix Script-Block: Output in eigene <script> Tags wrappen
   v1.4.2: Fix die() in Admin-Sprachdatei → _VALID_XTC vor include definieren
   v1.4.1: Fix MODULE_MRH_DASHBOARD_STATUS Check → nur Cache-Datei pruefen
   v1.4.0: Mobile-Icons + Mobile-Promos + Telefonnummer ans Frontend
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// Sprach-Mapping: language_id => code
$lang_map = array(2 => 'de', 1 => 'en', 5 => 'fr', 4 => 'es');
$active_lang = isset($lang_map[(int)($_SESSION['languages_id'] ?? 2)])
    ? $lang_map[(int)($_SESSION['languages_id'] ?? 2)]
    : 'de';

// Cache-Datei lesen
$cache_file = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/config/megamenu_config.json';

if (!file_exists($cache_file)) {
    return;
}

$json_raw = file_get_contents($cache_file);
$cache = json_decode($json_raw, true);

if (!is_array($cache) || empty($cache)) {
    return;
}

// Mega-Menu Konfiguration aufbereiten
// Abwaertskompatibilitaet: Cache kann LISTE (alt, v1.1.0) oder DICT (neu, v1.7.0+) sein
// ALT: megamenu_config.json = [ {parent_id:..., columns:[...]}, ... ]
// NEU: megamenu_config.json = { categories: [...], navlinks: [...] }
$megamenu_entries = array();
$nav_links = array();

if (isset($cache['categories'])) {
    // Neues Format (v1.7.0+): Dict mit 'categories' und 'navlinks'
    $megamenu_entries = $cache['categories'];
    if (isset($cache['navlinks'])) {
        $nav_links = $cache['navlinks'];
    }
} elseif (isset($cache[0]['parent_id'])) {
    // Altes Format (v1.1.0): Einfache Liste von Kategorien
    $megamenu_entries = $cache;
    // Keine navlinks im alten Format
}

$output_megamenu = array();

foreach ($megamenu_entries as $entry) {
    if (!isset($entry['columns']) || !is_array($entry['columns'])) continue;

    $columns_out = array();
    foreach ($entry['columns'] as $col) {
        // Sprachspezifischen Titel waehlen
        $title = '';
        if (isset($col['titles'][$active_lang]) && $col['titles'][$active_lang] !== '') {
            $title = $col['titles'][$active_lang];
        } elseif (isset($col['titles']['de'])) {
            $title = $col['titles']['de'];
        } elseif (isset($col['title'])) {
            $title = $col['title'];
        }

        // Nur Spalten mit Titel oder Items ausgeben
        $items_out = array();
        if (isset($col['items']) && is_array($col['items'])) {
            foreach ($col['items'] as $item) {
                // Sprachspezifisches Label
                $label = '';
                if (isset($item['labels'][$active_lang]) && $item['labels'][$active_lang] !== '') {
                    $label = $item['labels'][$active_lang];
                } elseif (isset($item['labels']['de'])) {
                    $label = $item['labels']['de'];
                } elseif (isset($item['label'])) {
                    $label = $item['label'];
                }

                // Nur Items mit Label ausgeben
                if ($label === '') continue;

                // v1.3.0: Sprachspezifische URL aus 'urls' Feld waehlen
                $item_url = '';
                if (isset($item['urls'][$active_lang]) && $item['urls'][$active_lang] !== '') {
                    $item_url = $item['urls'][$active_lang];
                } elseif (isset($item['urls']['de'])) {
                    $item_url = $item['urls']['de'];
                } elseif (isset($item['url'])) {
                    $item_url = $item['url'];
                }

                $items_out[] = array(
                    'category_id' => (int)$item['category_id'],
                    'label'       => $label,
                    'cpath'       => isset($item['cpath']) ? $item['cpath'] : '',
                    'url'         => $item_url,
                );
            }
        }

        // Nur Spalten mit mindestens einem Item ausgeben
        if (empty($items_out) && $title === '') continue;

        $columns_out[] = array(
            'title' => $title,
            'icon'  => isset($col['icon']) ? $col['icon'] : '',
            'items' => $items_out,
        );
    }

    // Nur Eintraege mit mindestens einer Spalte ausgeben
    if (empty($columns_out)) continue;

    // Sprachspezifischer Parent-Name
    $parent_name = '';
    if (isset($entry['parent_names'][$active_lang]) && $entry['parent_names'][$active_lang] !== '') {
        $parent_name = $entry['parent_names'][$active_lang];
    } elseif (isset($entry['parent_names']['de'])) {
        $parent_name = $entry['parent_names']['de'];
    } elseif (isset($entry['parent_name'])) {
        $parent_name = $entry['parent_name'];
    }

    $output_megamenu[] = array(
        'parent_id'   => (int)$entry['parent_id'],
        'parent_name' => $parent_name,
        'columns'     => $columns_out,
    );
}

// Nav-Links aufbereiten - MRH_-Konstanten aufloesen
// Die MRH_NAV_* Konstanten werden in lang/{sprache}/extra/admin/mrh_dashboard.php definiert.
// Diese Datei wird im Admin automatisch geladen, im Frontend muessen wir sie manuell einbinden.
// WICHTIG: Die Sprachdatei hat "defined('_VALID_XTC') or die(...)" → _VALID_XTC muss definiert sein!
$_mrh_lang_file_map = array(
    'de' => 'lang/german/extra/admin/mrh_dashboard.php',
    'en' => 'lang/english/extra/admin/mrh_dashboard.php',
    'fr' => 'lang/french/extra/admin/mrh_dashboard.php',
    'es' => 'lang/spanish/extra/admin/mrh_dashboard.php',
);
if (isset($_mrh_lang_file_map[$active_lang])) {
    $_mrh_lang_path = DIR_FS_CATALOG . $_mrh_lang_file_map[$active_lang];
    if (file_exists($_mrh_lang_path) && !defined('MRH_NAV_ANGEBOTE')) {
        if (!defined('_VALID_XTC')) define('_VALID_XTC', true);
        @include_once($_mrh_lang_path);
    }
}

// v1.7.0: Nav-Links mit mehrsprachigen URLs aufbereiten
$output_navlinks = array();
foreach ($nav_links as $link) {
    if (!isset($link['is_active']) || !$link['is_active']) continue;

    $name = isset($link['name']) ? $link['name'] : '';
    $name_constant = $name; // Original-Konstantenname merken fuer Duplikat-Erkennung

    // MRH_-Konstante aufloesen
    if (strpos($name, 'MRH_') === 0 && defined($name)) {
        $name = constant($name);
    }

    if ($name === '') continue;

    // v1.7.0: Sprachspezifische URL waehlen
    $link_url = '';
    if (isset($link['urls'][$active_lang]) && $link['urls'][$active_lang] !== '') {
        $link_url = $link['urls'][$active_lang];
    } elseif (isset($link['urls']['de'])) {
        $link_url = $link['urls']['de'];
    } elseif (isset($link['url'])) {
        $link_url = $link['url'];
    }

    $output_navlinks[] = array(
        'url'           => $link_url,
        'name'          => $name,
        'name_constant' => $name_constant,
        'icon'          => isset($link['icon']) ? $link['icon'] : '',
    );
}

// v1.4.0: Mobile-Icons aufbereiten (catId => iconClass)
$mobile_icons = array();
if (isset($cache['mobile_icons']) && is_array($cache['mobile_icons'])) {
    $mobile_icons = $cache['mobile_icons'];
}

// v1.4.0: Mobile-Promos aufbereiten (nur aktive)
$mobile_promos = array();
if (isset($cache['mobile_promos']) && is_array($cache['mobile_promos'])) {
    foreach ($cache['mobile_promos'] as $mp) {
        if (!isset($mp['is_active']) || !$mp['is_active']) continue;
        $mobile_promos[] = $mp;
    }
}

// JavaScript ausgeben
// v1.4.3: Eigene <script> Tags, weil vorherige extra-Dateien (z.B. product_compare)
// den ob_start()-Script-Block mit </script> + <link> vorzeitig schliessen.
echo "\n<script>\n";
echo "/* MRH Mega-Menu Config (Dashboard v1.4.0) */\n";
echo "window.MRH_MEGAMENU_CONFIG = " . json_encode($output_megamenu, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ";\n";
echo "window.MRH_MEGAMENU_NAVLINKS = " . json_encode($output_navlinks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ";\n";
echo "window.MRH_MEGAMENU_LANG = " . json_encode($active_lang) . ";\n";
echo "window.MRH_MOBILE_ICONS = " . json_encode($mobile_icons, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ";\n";
echo "window.MRH_MOBILE_PROMOS = " . json_encode($mobile_promos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ";\n";
echo "</script>\n";
