<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Panel v4.0
   
   Wird eingebunden via source/boxes/admin.php
   Benötigt: admin/includes/mrh_configurator.php (PHP-Backend)
   
   v4.0 (2026-04-10): Komplett-Umbau auf 8-Tab-Layout
     1. Allgemein    – Grundfarben, Hintergrund, Schrift, Footer, Topbar
     2. Navigation   – Menü, Sticky Header
     3. Buttons      – Gefüllt, Outline, Spezial
     4. Typografie   – h1-h6 (Größe+Farbe), Body, Links, text-*
     5. Komponenten  – bg-*, border-*, alert-*, card, form, table
     6. Einstellungen – Infinite Scroll, Barrierefrei, Logos, Social
     7. Custom CSS   – Textarea mit Live-Preview
     8. Presets      – Farb-Presets laden, Backup/Restore, Reset
   v4.1 (2026-04-11): Tab 9 Icon-Konfigurator hinzugefuegt
     9. Icons        – Icon-Tausch, Farbe, Groesse, Stil, Bereichs-Overrides
   v4.2 (2026-04-11): Tab 10 Badge-Konfigurator hinzugefuegt
    10. Badges       – Produkt-Typ-Badges: Farbe, Groesse, Rundung, Hover, Umrandung
   v4.3 (2026-04-16): Tab 12 Blog-Konfigurator hinzugefuegt
    12. Blog         – Post-Cards, Kategorie-Cards, Badges, Buttons, Einzelansicht
   v4.4 (2026-04-25): Tab Widgets hinzugefuegt
    Widgets      – Floating-Widget-Positionierung per Drag & Drop
   
   v3.0 (2026-04-10): ALLE Keys auf tpl-* vereinheitlicht
   ===================================================================== */

// Backend laden (liest/schreibt JSON, setzt $GLOBALS)
$configurator = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/includes/mrh_configurator.php';
if (file_exists($configurator)) {
    require_once($configurator);
}

// Kurzreferenzen
$c = isset($GLOBALS['mrh_colors']) ? $GLOBALS['mrh_colors'] : [];
$t = isset($GLOBALS['mrh_tpl'])    ? $GLOBALS['mrh_tpl']    : [];
$l = isset($GLOBALS['mrh_logos'])   ? $GLOBALS['mrh_logos']  : [];
$s = isset($GLOBALS['mrh_social'])  ? $GLOBALS['mrh_social'] : [];
$msg = isset($GLOBALS['mrh_config_message']) ? $GLOBALS['mrh_config_message'] : '';
$presets = isset($GLOBALS['mrh_presets']) ? $GLOBALS['mrh_presets'] : [];
$backups = isset($GLOBALS['mrh_backups']) ? $GLOBALS['mrh_backups'] : [];
$icons   = isset($GLOBALS['mrh_icons'])   ? $GLOBALS['mrh_icons']   : [];

// Hilfsfunktion: Farbwert sicher ausgeben
function mrh_cv($colors, $key) {
    return isset($colors[$key]) ? htmlspecialchars($colors[$key]) : '';
}

// Erfolgsmeldung
if (!empty($msg)) echo $msg;
?>

<!-- MRH Konfigurator v4.0 – Tab-Layout -->
<style>
.mrh-tabs{display:flex;border-bottom:2px solid #dee2e6;margin:0 12px;flex-wrap:wrap;gap:2px;}
.mrh-tab{padding:7px 12px;cursor:pointer;font-size:12px;font-weight:600;color:#666;border:1px solid transparent;border-bottom:none;border-radius:4px 4px 0 0;margin-bottom:-2px;white-space:nowrap;user-select:none;transition:all .15s;}
.mrh-tab:hover{color:#333;background:#f8f9fa;}
.mrh-tab.active{color:#4a8c2a;border-color:#dee2e6;border-bottom:2px solid #fff;background:#fff;}
.mrh-tab-pane{display:none;padding:12px;}
.mrh-tab-pane.active{display:block;}
.mrh-sh{border-bottom:2px solid #4a8c2a;padding:5px 0;margin:14px 0 8px;font-weight:700;font-size:13px;color:#333;}
.mrh-fg{background:#f8f9fa;border-radius:6px;padding:8px 10px;margin-bottom:6px;}
.mrh-fg-title{font-size:12px;font-weight:700;margin-bottom:4px;}
</style>

<div id="mrh-configurator-v4">

<!-- Tab-Navigation -->
<div class="mrh-tabs" id="mrh-config-tabs">
    <div class="mrh-tab active" data-tab="allgemein"><i class="fa fa-palette me-1"></i>Allgemein</div>
    <div class="mrh-tab" data-tab="navigation"><i class="fa fa-bars me-1"></i>Navigation</div>
    <div class="mrh-tab" data-tab="buttons"><i class="fa fa-square me-1"></i>Buttons</div>
    <div class="mrh-tab" data-tab="typografie"><i class="fa fa-font me-1"></i>Typografie</div>
    <div class="mrh-tab" data-tab="komponenten"><i class="fa fa-puzzle-piece me-1"></i>Komponenten</div>
    <div class="mrh-tab" data-tab="footer"><i class="fa fa-shoe-prints me-1"></i>Footer</div>
    <div class="mrh-tab" data-tab="warenkorb"><i class="fa fa-cart-shopping me-1"></i>Warenkorb</div>
    <div class="mrh-tab" data-tab="checkout"><i class="fa fa-cash-register me-1"></i>Checkout</div>
    <div class="mrh-tab" data-tab="seedfinder-modal"><i class="fa fa-cannabis me-1"></i>SF Modal</div>
    <div class="mrh-tab" data-tab="seedfinder-seite"><i class="fa fa-seedling me-1"></i>SF Seite</div>
    <div class="mrh-tab" data-tab="blog"><i class="fa fa-newspaper me-1"></i>Blog</div>
    <div class="mrh-tab" data-tab="faq"><i class="fa fa-circle-question me-1"></i>FAQ</div>
    <div class="mrh-tab" data-tab="badges"><i class="fa fa-certificate me-1"></i>Badges</div>
    <div class="mrh-tab" data-tab="icons"><i class="fa fa-icons me-1"></i>Icons</div>
    <div class="mrh-tab" data-tab="einstellungen"><i class="fa fa-sliders me-1"></i>Einstellungen</div>
    <div class="mrh-tab" data-tab="customcss"><i class="fa fa-code me-1"></i>Custom CSS</div>
    <div class="mrh-tab" data-tab="presets"><i class="fa fa-paint-brush me-1"></i>Presets</div>
    <div class="mrh-tab" data-tab="content"><i class="fa fa-file-code me-1"></i>Content</div>
    <div class="mrh-tab" data-tab="widgets"><i class="fa fa-layer-group me-1"></i>Widgets</div>
</div>

<!-- ================================================================== -->
<!-- TAB 1: ALLGEMEIN -->
<!-- ================================================================== -->
<div class="mrh-tab-pane active" id="tab-allgemein">
<form id="mrh-form-allgemein" class="row mx-0" method="post" action="">

    <div class="col-12"><div class="mrh-sh"><i class="fa fa-circle-half-stroke me-1"></i> Hauptfarben</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-main-color"><strong>Prim&auml;rfarbe</strong></label>
        <input id="tpl-main-color" type="text" name="tpl-main-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-main-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-main-color'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-main-color-2"><strong>Sekund&auml;rfarbe</strong></label>
        <input id="tpl-main-color-2" type="text" name="tpl-main-color-2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-main-color-2'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-main-color-2'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-secondary-color"><strong>Akzentfarbe</strong></label>
        <input id="tpl-secondary-color" type="text" name="tpl-secondary-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-secondary-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-secondary-color'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-grip-lines me-1"></i> Topbar (Trust-Leiste)</div></div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-topbar-bg"><strong>Topbar Hintergrund</strong></label>
        <input id="tpl-topbar-bg" type="text" name="tpl-topbar-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-topbar-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-topbar-bg'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-topbar-text"><strong>Topbar Textfarbe</strong></label>
        <input id="tpl-topbar-text" type="text" name="tpl-topbar-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-topbar-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-topbar-text'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-fill-drip me-1"></i> Hintergrundfarben</div></div>
<?php
$bg_fields = [
    'tpl-bg-color'      => 'Hintergrundfarbe 1',
    'tpl-bg-color-2'    => 'Hintergrundfarbe 2',
    'tpl-bg-productbox' => 'Produktboxen Hintergrund',
    'tpl-bg-footer'     => 'Footer Hintergrund',
    'tpl-bg-contentpage' => 'Content-Seiten Hintergrund',
];
foreach ($bg_fields as $key => $label) {
    echo '<div class="col-sm-6 mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-font me-1"></i> Schriftfarben</div></div>
<?php
$text_fields = [
    'tpl-text-standard'        => 'Standard Schriftfarbe',
    'tpl-text-headings'        => '&Uuml;berschriften Schriftfarbe',
    'tpl-text-button'          => 'Schriftfarbe in Buttons &amp; Badges',
    'tpl-text-footer'          => 'Schriftfarbe Text &amp; Links im Footer',
    'tpl-text-footer-headings' => '&Uuml;berschriften im Footer',
];
foreach ($text_fields as $key => $label) {
    echo '<div class="col-sm-6 mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12 mt-2">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Allgemein speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 2: NAVIGATION -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-navigation">
<form id="mrh-form-navigation" class="row mx-0" method="post" action="">

    <div class="col-12"><div class="mrh-sh"><i class="fa fa-bars me-1"></i> Hauptnavigation (Men&uuml;-Leiste)</div></div>
<?php
$menu_fields = [
    'tpl-menu-bg'     => 'Men&uuml; Hintergrund',
    'tpl-menu-text'   => 'Men&uuml; Textfarbe',
    'tpl-menu-hover'  => 'Men&uuml; Hover',
    'tpl-menu-active' => 'Men&uuml; Aktiv',
];
foreach ($menu_fields as $key => $label) {
    echo '<div class="col-sm-3 mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-thumbtack me-1"></i> Sticky Header</div></div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-sticky-bg"><strong>Sticky Header Hintergrund</strong></label>
        <input id="tpl-sticky-bg" type="text" name="tpl-sticky-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sticky-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sticky-bg'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-sticky-text"><strong>Sticky Header Textfarbe</strong></label>
        <input id="tpl-sticky-text" type="text" name="tpl-sticky-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sticky-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sticky-text'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-mobile-screen me-1"></i> Mobile Sidebar Navigation</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Das seitliche Men&uuml; auf Mobilger&auml;ten (Hamburger-Men&uuml;)</small></div>
<?php
$mobile_fields = [
    'tpl-mobile-panel-bg'      => 'Panel Hintergrund',
    'tpl-mobile-header-bg'     => 'Header Hintergrund',
    'tpl-mobile-header-text'   => 'Header Textfarbe',
    'tpl-mobile-link-color'    => 'Link-Farbe',
    'tpl-mobile-link-hover'    => 'Link Hover-Farbe',
    'tpl-mobile-link-hover-bg' => 'Link Hover-Hintergrund',
    'tpl-mobile-search-border' => 'Suchfeld Rahmenfarbe',
    'tpl-mobile-search-btn-bg' => 'Such-Button Hintergrund',
    'tpl-mobile-icon-color'    => 'Icon-Farbe',
];
foreach ($mobile_fields as $key => $label) {
    echo '<div class="col-sm-3 mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12 mt-2">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Navigation speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 3: BUTTONS -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-buttons">
<form id="mrh-form-buttons" class="row mx-0" method="post" action="">

    <div class="col-12"><div class="mrh-sh"><i class="fa fa-square me-1"></i> Gef&uuml;llte Buttons (btn-*)</div></div>
<?php
$filled_buttons = ['primary'=>'Primary','secondary'=>'Secondary','success'=>'Success','danger'=>'Danger','warning'=>'Warning','info'=>'Info','light'=>'Light','dark'=>'Dark'];
foreach ($filled_buttons as $variant => $label) {
    $bg_key    = 'tpl-btn-' . $variant . '-bg';
    $text_key  = 'tpl-btn-' . $variant . '-text';
    $hover_key = 'tpl-btn-' . $variant . '-hover';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">btn-'.$variant.' ('.$label.')</div><div class="row">';
    echo '<div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>';
    echo '<input type="text" name="'.$bg_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$bg_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$bg_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>';
    echo '<input type="text" name="'.$text_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$text_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$text_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Hover</small></label>';
    echo '<input type="text" name="'.$hover_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$hover_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$hover_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-square-full me-1"></i> Outline Buttons (btn-outline-*)</div></div>
<?php
foreach ($filled_buttons as $variant => $label) {
    $bg_key    = 'tpl-btn-outline-' . $variant . '-bg';
    $text_key  = 'tpl-btn-outline-' . $variant . '-text';
    $hover_key = 'tpl-btn-outline-' . $variant . '-hover';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">btn-outline-'.$variant.' ('.$label.')</div><div class="row">';
    echo '<div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>';
    echo '<input type="text" name="'.$bg_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$bg_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$bg_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>';
    echo '<input type="text" name="'.$text_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$text_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$text_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Hover</small></label>';
    echo '<input type="text" name="'.$hover_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$hover_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$hover_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-star me-1"></i> Spezial-Buttons</div></div>
<?php
$special_buttons = ['express'=>'Express Kaufen','details'=>'Details (Auge)','wishlist'=>'Merkzettel (Herz)','compare'=>'Vergleichen (Waage)','compare-remove'=>'Vergleich entfernen (X)'];
foreach ($special_buttons as $variant => $label) {
    $bg_key    = 'tpl-btn-' . $variant . '-bg';
    $text_key  = 'tpl-btn-' . $variant . '-text';
    $hover_key = 'tpl-btn-' . $variant . '-hover';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">'.$label.'</div><div class="row">';
    echo '<div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>';
    echo '<input type="text" name="'.$bg_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$bg_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$bg_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>';
    echo '<input type="text" name="'.$text_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$text_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$text_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Hover</small></label>';
    echo '<input type="text" name="'.$hover_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$hover_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$hover_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <!-- Legacy Outline-Keys (Kompatibilität) -->
    <input type="hidden" name="tpl-btn-outline-border" value="<?php echo mrh_cv($c,'tpl-btn-outline-border'); ?>">
    <input type="hidden" name="tpl-btn-outline-text" value="<?php echo mrh_cv($c,'tpl-btn-outline-text'); ?>">
    <input type="hidden" name="tpl-btn-outline-hover" value="<?php echo mrh_cv($c,'tpl-btn-outline-hover'); ?>">

    <div class="col-12 mt-2">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Buttons speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 4: TYPOGRAFIE -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-typografie">
<form id="mrh-form-typografie" class="row mx-0" method="post" action="">

    <div class="col-12"><div class="mrh-sh"><i class="fa fa-heading me-1"></i> &Uuml;berschriften (h1 – h6)</div></div>
<?php
for ($i = 1; $i <= 6; $i++) {
    $size_key  = 'tpl-h'.$i.'-size';
    $color_key = 'tpl-h'.$i.'-color';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">h'.$i.'</div><div class="row">';
    echo '<div class="col-sm-6 mb-2"><label><small>Schriftgr&ouml;&szlig;e (rem)</small></label>';
    echo '<input type="text" name="'.$size_key.'" class="form-control form-control-sm mrh-size-input" value="'.mrh_cv($c,$size_key).'" placeholder="z.B. 2.5rem"></div>';
    echo '<div class="col-sm-6 mb-2"><label><small>Farbe</small></label>';
    echo '<input type="text" name="'.$color_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$color_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$color_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-paragraph me-1"></i> Flie&szlig;text &amp; Allgemein</div></div>
    <div class="col-sm-6 mb-3">
        <label><strong>Body Schriftgr&ouml;&szlig;e</strong></label>
        <input type="text" name="tpl-body-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-body-size'); ?>" placeholder="z.B. 1rem">
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Body Textfarbe</strong></label>
        <input type="text" name="tpl-body-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-body-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-body-color'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Small / .small Gr&ouml;&szlig;e</strong></label>
        <input type="text" name="tpl-small-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-small-size'); ?>" placeholder="z.B. 0.875rem">
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>.lead Gr&ouml;&szlig;e</strong></label>
        <input type="text" name="tpl-lead-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-lead-size'); ?>" placeholder="z.B. 1.25rem">
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-link me-1"></i> Links</div></div>
    <div class="col-sm-6 mb-3">
        <label><strong>Link-Farbe</strong></label>
        <input type="text" name="tpl-link-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-link-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-link-color'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Link Hover-Farbe</strong></label>
        <input type="text" name="tpl-link-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-link-hover'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-link-hover'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Link-Unterstreichung</strong></label>
        <select name="tpl-link-decoration" class="form-select">
            <option value="none" <?php echo mrh_cv($c,'tpl-link-decoration','none') === 'none' ? 'selected' : ''; ?>>Keine</option>
            <option value="underline" <?php echo mrh_cv($c,'tpl-link-decoration','none') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
        </select>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Link-Hover Unterstreichung</strong></label>
        <select name="tpl-link-hover-decoration" class="form-select">
            <option value="underline" <?php echo mrh_cv($c,'tpl-link-hover-decoration','underline') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
            <option value="none" <?php echo mrh_cv($c,'tpl-link-hover-decoration','underline') === 'none' ? 'selected' : ''; ?>>Keine</option>
        </select>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-text-height me-1"></i> Text-Klassen (text-*)</div></div>
<?php
$text_classes = ['primary'=>'text-primary','secondary'=>'text-secondary','success'=>'text-success','danger'=>'text-danger','warning'=>'text-warning','info'=>'text-info','light'=>'text-light','dark'=>'text-dark','muted'=>'text-muted','white'=>'text-white'];
foreach ($text_classes as $variant => $label) {
    $key = 'tpl-text-' . $variant;
    echo '<div class="col-sm-4 mb-3">';
    echo '<label><strong>'.$label.'</strong></label>';
    echo '<input type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-quote-left me-1"></i> Blockquote / Zitate</div></div>
    <div class="col-sm-4 mb-3">
        <label><strong>Hintergrund</strong></label>
        <input type="text" name="tpl-bq-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bq-bg','rgba(93, 178, 51, 0.08)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bq-bg','rgba(93, 178, 51, 0.08)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Textfarbe</strong></label>
        <input type="text" name="tpl-bq-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bq-text','rgb(51, 51, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bq-text','rgb(51, 51, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Rahmenfarbe (links)</strong></label>
        <input type="text" name="tpl-bq-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bq-border','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bq-border','rgb(93, 178, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Schriftgröße</strong></label>
        <input type="text" name="tpl-bq-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-bq-font-size','1rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Footer-Textfarbe (Quelle)</strong></label>
        <input type="text" name="tpl-bq-footer-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bq-footer-text','rgb(108, 117, 125)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bq-footer-text','rgb(108, 117, 125)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Sterne-Farbe (.text-gelb)</strong></label>
        <input type="text" name="tpl-text-gelb" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-text-gelb','rgb(255, 193, 7)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-text-gelb','rgb(255, 193, 7)'); ?>"></div>
    </div>

    <div class="col-12 mt-2">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Typografie speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 5: KOMPONENTEN -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-komponenten">
<form id="mrh-form-komponenten" class="row mx-0" method="post" action="">

    <div class="col-12"><div class="mrh-sh"><i class="fa fa-fill-drip me-1"></i> Hintergrundfarben (bg-*)</div></div>
<?php
$bg_variants = ['primary','secondary','success','danger','warning','info','light','dark'];
foreach ($bg_variants as $variant) {
    $bg_key   = 'tpl-bg-' . $variant;
    $text_key = 'tpl-bg-' . $variant . '-text';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">bg-'.$variant.'</div><div class="row">';
    echo '<div class="col-sm-6 mb-2"><label><small>Hintergrund</small></label>';
    echo '<input type="text" name="'.$bg_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$bg_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$bg_key).'"></div></div>';
    echo '<div class="col-sm-6 mb-2"><label><small>Textfarbe</small></label>';
    echo '<input type="text" name="'.$text_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$text_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$text_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-border-all me-1"></i> Rahmenfarben (border-*)</div></div>
<?php
foreach ($bg_variants as $variant) {
    $key = 'tpl-border-' . $variant;
    echo '<div class="col-sm-3 mb-3">';
    echo '<label><strong>border-'.$variant.'</strong></label>';
    echo '<input type="text" name="'.$key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-bell me-1"></i> Alerts (alert-*)</div></div>
<?php
$alert_variants = ['primary','secondary','success','danger','warning','info'];
foreach ($alert_variants as $variant) {
    $bg_key     = 'tpl-alert-' . $variant . '-bg';
    $text_key   = 'tpl-alert-' . $variant . '-text';
    $border_key = 'tpl-alert-' . $variant . '-border';
    echo '<div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">alert-'.$variant.'</div><div class="row">';
    echo '<div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>';
    echo '<input type="text" name="'.$bg_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$bg_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$bg_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>';
    echo '<input type="text" name="'.$text_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$text_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$text_key).'"></div></div>';
    echo '<div class="col-sm-4 mb-2"><label><small>Rahmen</small></label>';
    echo '<input type="text" name="'.$border_key.'" class="form-control form-control-sm colorpicker-element" value="'.mrh_cv($c,$border_key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$border_key).'"></div></div>';
    echo '</div></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-id-card me-1"></i> Card</div></div>
    <div class="col-sm-4 mb-3">
        <label><strong>Card Hintergrund</strong></label>
        <input type="text" name="tpl-card-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-card-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-card-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Card Rahmen</strong></label>
        <input type="text" name="tpl-card-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-card-border'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-card-border'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Card Header BG</strong></label>
        <input type="text" name="tpl-card-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-card-header-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-card-header-bg'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-keyboard me-1"></i> Formular-Elemente</div></div>
    <div class="col-sm-6 mb-3">
        <label><strong>Focus Rahmenfarbe</strong></label>
        <input type="text" name="tpl-form-focus-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-form-focus-border'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-form-focus-border'); ?>"></div>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Focus Schatten</strong></label>
        <input type="text" name="tpl-form-focus-shadow" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-form-focus-shadow'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-form-focus-shadow'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-table me-1"></i> Tabellen</div></div>
    <div class="col-sm-4 mb-3">
        <label><strong>Striped Hintergrund</strong></label>
        <input type="text" name="tpl-table-striped-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-table-striped-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-table-striped-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Hover Hintergrund</strong></label>
        <input type="text" name="tpl-table-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-table-hover-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-table-hover-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label><strong>Tabellen-Rahmen</strong></label>
        <input type="text" name="tpl-table-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-table-border'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-table-border'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-ellipsis-h me-1"></i> Pagination (Seitennavigation)</div></div>
    <div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">Normal (Standard)</div><div class="row">
        <div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>
            <input type="text" name="tpl-pg-bg" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-bg'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-bg'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>
            <input type="text" name="tpl-pg-text" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-text'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-text'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Rahmen</small></label>
            <input type="text" name="tpl-pg-border" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-border'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-border'); ?>"></div></div>
    </div></div></div>

    <div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">Hover</div><div class="row">
        <div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>
            <input type="text" name="tpl-pg-hover-bg" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-hover-bg'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-hover-bg'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>
            <input type="text" name="tpl-pg-hover-text" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-hover-text'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-hover-text'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Rahmen</small></label>
            <input type="text" name="tpl-pg-hover-border" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-hover-border'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-hover-border'); ?>"></div></div>
    </div></div></div>

    <div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">Aktiv (aktuelle Seite)</div><div class="row">
        <div class="col-sm-4 mb-2"><label><small>Hintergrund</small></label>
            <input type="text" name="tpl-pg-active-bg" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-active-bg'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-active-bg'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Textfarbe</small></label>
            <input type="text" name="tpl-pg-active-text" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-active-text'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-active-text'); ?>"></div></div>
        <div class="col-sm-4 mb-2"><label><small>Rahmen</small></label>
            <input type="text" name="tpl-pg-active-border" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-active-border'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-active-border'); ?>"></div></div>
    </div></div></div>

    <div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">Deaktiviert</div><div class="row">
        <div class="col-sm-6 mb-2"><label><small>Textfarbe</small></label>
            <input type="text" name="tpl-pg-disabled-text" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-disabled-text'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-disabled-text'); ?>"></div></div>
        <div class="col-sm-6 mb-2"><label><small>Rahmen</small></label>
            <input type="text" name="tpl-pg-disabled-border" class="form-control form-control-sm colorpicker-element" value="<?php echo mrh_cv($c,'tpl-pg-disabled-border'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-pg-disabled-border'); ?>"></div></div>
    </div></div></div>

    <div class="col-12"><div class="mrh-fg"><div class="mrh-fg-title">Gr&ouml;&szlig;en</div><div class="row">
        <div class="col-sm-4 mb-2"><label><small>Schriftgr&ouml;&szlig;e</small></label>
            <input type="text" name="tpl-pg-font-size" class="form-control form-control-sm mrh-size-input" value="<?php echo mrh_cv($c,'tpl-pg-font-size'); ?>" placeholder="0.8125rem"></div>
        <div class="col-sm-4 mb-2"><label><small>Eckenradius</small></label>
            <input type="text" name="tpl-pg-radius" class="form-control form-control-sm mrh-size-input" value="<?php echo mrh_cv($c,'tpl-pg-radius'); ?>" placeholder="0.375rem"></div>
        <div class="col-sm-4 mb-2"><label><small>Button-Gr&ouml;&szlig;e</small></label>
            <input type="text" name="tpl-pg-size" class="form-control form-control-sm mrh-size-input" value="<?php echo mrh_cv($c,'tpl-pg-size'); ?>" placeholder="2.25rem"></div>
    </div></div></div>

    <div class="col-12 mt-2">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Komponenten speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 6: EINSTELLUNGEN -->
<!-- ================================================================== -->

<!-- ================================================================== -->
<!-- TAB: FOOTER -->
<!-- ================================================================== -->
<div class="mrh-tab-pane" id="tab-footer">
<h5 class="mb-3"><i class="fa fa-shoe-prints me-2"></i>Footer</h5>
<p class="text-muted small mb-3">Steuert Farben, Typografie und Links im Footer-Bereich.</p>

<form method="post" action="">

    <!-- Footer Hintergrund & Text -->
    <h6 class="border-bottom pb-2 mb-3"><i class="fa fa-fill-drip me-1"></i>Hintergrund &amp; Text</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-bg-footer"><strong>Footer Hintergrund</strong></label>
            <input id="tpl-bg-footer" type="text" name="tpl-bg-footer" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bg-footer','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bg-footer','rgb(33, 37, 41)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-text-footer"><strong>Footer Textfarbe</strong></label>
            <input id="tpl-text-footer" type="text" name="tpl-text-footer" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-text-footer','rgb(173, 181, 189)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-text-footer','rgb(173, 181, 189)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-text-footer-headings"><strong>&Uuml;berschriften Farbe</strong></label>
            <input id="tpl-text-footer-headings" type="text" name="tpl-text-footer-headings" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-text-footer-headings','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-text-footer-headings','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- Footer Links -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-link me-1"></i>Links</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-link-color"><strong>Link-Farbe</strong></label>
            <input id="tpl-footer-link-color" type="text" name="tpl-footer-link-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-footer-link-color','rgb(173, 181, 189)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-footer-link-color','rgb(173, 181, 189)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-link-hover"><strong>Link-Hover Farbe</strong></label>
            <input id="tpl-footer-link-hover" type="text" name="tpl-footer-link-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-footer-link-hover','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-footer-link-hover','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-link-decoration"><strong>Link-Unterstreichung</strong></label>
            <select id="tpl-footer-link-decoration" name="tpl-footer-link-decoration" class="form-select">
                <option value="none" <?php echo mrh_cv($c,'tpl-footer-link-decoration','none') === 'none' ? 'selected' : ''; ?>>Keine</option>
                <option value="underline" <?php echo mrh_cv($c,'tpl-footer-link-decoration','none') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
            </select>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-link-hover-decoration"><strong>Link-Hover Unterstreichung</strong></label>
            <select id="tpl-footer-link-hover-decoration" name="tpl-footer-link-hover-decoration" class="form-select">
                <option value="underline" <?php echo mrh_cv($c,'tpl-footer-link-hover-decoration','underline') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
                <option value="none" <?php echo mrh_cv($c,'tpl-footer-link-hover-decoration','underline') === 'none' ? 'selected' : ''; ?>>Keine</option>
            </select>
        </div>
    </div>

    <!-- Footer Typografie -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-font me-1"></i>Typografie</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-footer-font-size" type="text" name="tpl-footer-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-footer-font-size','0.875rem'); ?>" placeholder="0.875rem">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-heading-size"><strong>&Uuml;berschriften Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-footer-heading-size" type="text" name="tpl-footer-heading-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-footer-heading-size','1.125rem'); ?>" placeholder="1.125rem">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-heading-weight"><strong>&Uuml;berschriften Gewicht</strong></label>
            <select id="tpl-footer-heading-weight" name="tpl-footer-heading-weight" class="form-select">
                <option value="400" <?php echo mrh_cv($c,'tpl-footer-heading-weight','700') === '400' ? 'selected' : ''; ?>>Normal (400)</option>
                <option value="500" <?php echo mrh_cv($c,'tpl-footer-heading-weight','700') === '500' ? 'selected' : ''; ?>>Medium (500)</option>
                <option value="600" <?php echo mrh_cv($c,'tpl-footer-heading-weight','700') === '600' ? 'selected' : ''; ?>>Semibold (600)</option>
                <option value="700" <?php echo mrh_cv($c,'tpl-footer-heading-weight','700') === '700' ? 'selected' : ''; ?>>Bold (700)</option>
            </select>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-footer-border-color"><strong>Trennlinien Farbe</strong></label>
            <input id="tpl-footer-border-color" type="text" name="tpl-footer-border-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-footer-border-color','rgba(255,255,255,0.1)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-footer-border-color','rgba(255,255,255,0.1)'); ?>"></div>
        </div>
    </div>

    <!-- Footertext-Sektion (SEO-Text unten) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-align-left me-1"></i>Footertext (SEO-Text unten)</h6>
    <p class="text-muted small mb-2">Steuert die <code>.box3.footertext</code> Sektion (SEO-Text &amp; Kontakt). &Uuml;berschreibt Inline-Styles vom CMS-Editor.</p>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-bg"><strong>Footertext Hintergrund</strong></label>
            <input id="tpl-ft-bg" type="text" name="tpl-ft-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-bg','transparent'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-bg','transparent'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-text-color"><strong>Footertext Textfarbe</strong></label>
            <input id="tpl-ft-text-color" type="text" name="tpl-ft-text-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-text-color','rgb(148, 163, 184)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-text-color','rgb(148, 163, 184)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-heading-color"><strong>&Uuml;berschriften Farbe</strong></label>
            <input id="tpl-ft-heading-color" type="text" name="tpl-ft-heading-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-heading-color','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-heading-color','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-heading-size"><strong>&Uuml;berschriften Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-ft-heading-size" type="text" name="tpl-ft-heading-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ft-heading-size','1rem'); ?>" placeholder="1rem">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-ft-font-size" type="text" name="tpl-ft-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ft-font-size','0.8125rem'); ?>" placeholder="0.8125rem">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-link-color"><strong>Link-Farbe</strong></label>
            <input id="tpl-ft-link-color" type="text" name="tpl-ft-link-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-link-color','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-link-color','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-link-hover"><strong>Link-Hover Farbe</strong></label>
            <input id="tpl-ft-link-hover" type="text" name="tpl-ft-link-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-link-hover','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-link-hover','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-link-decoration"><strong>Link-Unterstreichung</strong></label>
            <select id="tpl-ft-link-decoration" name="tpl-ft-link-decoration" class="form-select">
                <option value="none" <?php echo mrh_cv($c,'tpl-ft-link-decoration','none') === 'none' ? 'selected' : ''; ?>>Keine</option>
                <option value="underline" <?php echo mrh_cv($c,'tpl-ft-link-decoration','none') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
            </select>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-link-hover-decoration"><strong>Link-Hover Unterstreichung</strong></label>
            <select id="tpl-ft-link-hover-decoration" name="tpl-ft-link-hover-decoration" class="form-select">
                <option value="underline" <?php echo mrh_cv($c,'tpl-ft-link-hover-decoration','underline') === 'underline' ? 'selected' : ''; ?>>Unterstrichen</option>
                <option value="none" <?php echo mrh_cv($c,'tpl-ft-link-hover-decoration','underline') === 'none' ? 'selected' : ''; ?>>Keine</option>
            </select>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-ft-border-color"><strong>Trennlinien Farbe</strong></label>
            <input id="tpl-ft-border-color" type="text" name="tpl-ft-border-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ft-border-color','rgba(255,255,255,0.1)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ft-border-color','rgba(255,255,255,0.1)'); ?>"></div>
        </div>
    </div>

    <!-- Live-Vorschau Footer -->
    <div class="card mb-4 mt-3">
        <div class="card-header"><strong>Live-Vorschau Footer</strong></div>
        <div class="card-body" id="mrh-footer-preview" style="padding:20px;">
            <div id="mrh-footer-demo" style="padding:24px;border-radius:8px;">
                <h6 id="fp-heading" style="margin-bottom:8px;">Footer &Uuml;berschrift</h6>
                <p id="fp-text" style="margin-bottom:6px;">Beispieltext im Footer-Bereich</p>
                <a href="#" id="fp-link" onclick="return false;">Beispiel-Link</a>
                <hr id="fp-hr" style="margin:12px 0;">
                <small id="fp-small">&copy; 2026 Mr. Hanf</small>
            </div>
        </div>
    </div>
    <script>
    (function(){
        function updateFooterPreview(){
            var bg = document.getElementById('tpl-bg-footer');
            var txt = document.getElementById('tpl-text-footer');
            var head = document.getElementById('tpl-text-footer-headings');
            var lnk = document.getElementById('tpl-footer-link-color');
            var lnkH = document.getElementById('tpl-footer-link-hover');
            var lnkDec = document.getElementById('tpl-footer-link-decoration');
            var fs = document.getElementById('tpl-footer-font-size');
            var hs = document.getElementById('tpl-footer-heading-size');
            var hw = document.getElementById('tpl-footer-heading-weight');
            var bc = document.getElementById('tpl-footer-border-color');
            var demo = document.getElementById('mrh-footer-demo');
            var fpH = document.getElementById('fp-heading');
            var fpT = document.getElementById('fp-text');
            var fpL = document.getElementById('fp-link');
            var fpHr = document.getElementById('fp-hr');
            var fpS = document.getElementById('fp-small');
            if(demo && bg) demo.style.background = bg.value;
            if(fpT && txt) fpT.style.color = txt.value;
            if(fpS && txt) fpS.style.color = txt.value;
            if(fpH && head) fpH.style.color = head.value;
            if(fpL && lnk) fpL.style.color = lnk.value;
            if(fpL && lnkDec) fpL.style.textDecoration = lnkDec.value;
            if(fpT && fs) fpT.style.fontSize = fs.value;
            if(fpS && fs) fpS.style.fontSize = fs.value;
            if(fpH && hs) fpH.style.fontSize = hs.value;
            if(fpH && hw) fpH.style.fontWeight = hw.value;
            if(fpHr && bc) fpHr.style.borderColor = bc.value;
            if(fpL && lnkH){
                fpL.onmouseenter = function(){ this.style.color = lnkH.value; var hd = document.getElementById('tpl-footer-link-hover-decoration'); if(hd) this.style.textDecoration = hd.value; };
                fpL.onmouseleave = function(){ this.style.color = lnk.value; if(lnkDec) this.style.textDecoration = lnkDec.value; };
            }
        }
        var els = document.querySelectorAll('#tab-footer input, #tab-footer select');
        els.forEach(function(el){ el.addEventListener('input', updateFooterPreview); el.addEventListener('change', updateFooterPreview); });
        setTimeout(updateFooterPreview, 100);
    })();
    </script>

    <div class="row mt-4">
        <div class="col-12">
            <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Footer speichern">
        </div>
    </div>

</form>
</div><!-- /#tab-footer -->


<!-- ================================================================== -->
<!-- TAB: WARENKORB -->
<!-- ================================================================== -->
<div class="mrh-tab-pane" id="tab-warenkorb">
<h5 class="mb-3"><i class="fa fa-cart-shopping me-2"></i>Warenkorb</h5>
<p class="text-muted small mb-3">Steuert das Aussehen der Warenkorb-Seite: Artikel-Buttons (L&ouml;schen/Aktualisieren/Merkzettel) und Kasse/Schnellkauf/Brief-Buttons.</p>

<form method="post" action="">

    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-cart-shopping me-1"></i>Warenkorb-Seite</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-header-bg"><strong>&Uuml;berschrift Hintergrund</strong></label>
            <input id="tpl-co-cart-header-bg" type="text" name="tpl-co-cart-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-header-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-header-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-header-text"><strong>&Uuml;berschrift Text</strong></label>
            <input id="tpl-co-cart-header-text" type="text" name="tpl-co-cart-header-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-header-text','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-header-text','rgb(33, 37, 41)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-total-bg"><strong>Summen-Bereich BG</strong></label>
            <input id="tpl-co-cart-total-bg" type="text" name="tpl-co-cart-total-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-total-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-total-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-total-text"><strong>Summen-Text</strong></label>
            <input id="tpl-co-cart-total-text" type="text" name="tpl-co-cart-total-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-total-text','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-total-text','rgb(33, 37, 41)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-delete-color"><strong>L&ouml;schen-Icon Farbe</strong></label>
            <input id="tpl-co-cart-delete-color" type="text" name="tpl-co-cart-delete-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-delete-color','rgb(220, 53, 69)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-delete-color','rgb(220, 53, 69)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-coupon-bg"><strong>Gutschein-Bereich BG</strong></label>
            <input id="tpl-co-cart-coupon-bg" type="text" name="tpl-co-cart-coupon-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-coupon-bg','rgb(255, 243, 224)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-coupon-bg','rgb(255, 243, 224)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Warenkorb-Buttons ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-cart-shopping me-1"></i>Warenkorb-Buttons</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-kasse-bg"><strong>Kasse-Button BG</strong></label>
            <input id="tpl-co-cart-btn-kasse-bg" type="text" name="tpl-co-cart-btn-kasse-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-kasse-text"><strong>Kasse-Button Text</strong></label>
            <input id="tpl-co-cart-btn-kasse-text" type="text" name="tpl-co-cart-btn-kasse-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-kasse-hover"><strong>Kasse-Button Hover</strong></label>
            <input id="tpl-co-cart-btn-kasse-hover" type="text" name="tpl-co-cart-btn-kasse-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-hover','rgb(56, 112, 32)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-kasse-hover','rgb(56, 112, 32)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-schnellkauf-bg"><strong>Schnellkauf-Button BG</strong></label>
            <input id="tpl-co-cart-btn-schnellkauf-bg" type="text" name="tpl-co-cart-btn-schnellkauf-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-bg','rgb(0, 150, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-bg','rgb(0, 150, 136)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-schnellkauf-text"><strong>Schnellkauf-Button Text</strong></label>
            <input id="tpl-co-cart-btn-schnellkauf-text" type="text" name="tpl-co-cart-btn-schnellkauf-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-schnellkauf-hover"><strong>Schnellkauf-Button Hover</strong></label>
            <input id="tpl-co-cart-btn-schnellkauf-hover" type="text" name="tpl-co-cart-btn-schnellkauf-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-hover','rgb(0, 121, 107)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-schnellkauf-hover','rgb(0, 121, 107)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-brief-bg"><strong>Brief-Bestellung BG</strong></label>
            <input id="tpl-co-cart-btn-brief-bg" type="text" name="tpl-co-cart-btn-brief-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-brief-text"><strong>Brief-Bestellung Text</strong></label>
            <input id="tpl-co-cart-btn-brief-text" type="text" name="tpl-co-cart-btn-brief-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-text','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-text','rgb(33, 37, 41)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-brief-border"><strong>Brief-Bestellung Border</strong></label>
            <input id="tpl-co-cart-btn-brief-border" type="text" name="tpl-co-cart-btn-brief-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-border','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-brief-border','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-gutschein-bg"><strong>Gutschein-Button BG</strong></label>
            <input id="tpl-co-cart-btn-gutschein-bg" type="text" name="tpl-co-cart-btn-gutschein-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-gutschein-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-gutschein-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-gutschein-text"><strong>Gutschein-Button Text</strong></label>
            <input id="tpl-co-cart-btn-gutschein-text" type="text" name="tpl-co-cart-btn-gutschein-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-gutschein-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-gutschein-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-weiter-bg"><strong>Weiter Einkaufen BG</strong></label>
            <input id="tpl-co-cart-btn-weiter-bg" type="text" name="tpl-co-cart-btn-weiter-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-weiter-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-weiter-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-weiter-text"><strong>Weiter Einkaufen Text</strong></label>
            <input id="tpl-co-cart-btn-weiter-text" type="text" name="tpl-co-cart-btn-weiter-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-weiter-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-weiter-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-radius"><strong>Warenkorb-Button Rundung</strong></label>
            <input id="tpl-co-cart-btn-radius" type="text" name="tpl-co-cart-btn-radius" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-radius','6px'); ?>">
        </div>
    </div>

    <!-- ═══ Warenkorb Artikel-Buttons ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-pen-to-square me-1"></i>Warenkorb Artikel-Buttons</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-update-bg"><strong>Aktualisieren BG</strong></label>
            <input id="tpl-co-cart-btn-update-bg" type="text" name="tpl-co-cart-btn-update-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-update-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-update-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-update-text"><strong>Aktualisieren Text</strong></label>
            <input id="tpl-co-cart-btn-update-text" type="text" name="tpl-co-cart-btn-update-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-update-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-update-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-update-icon"><strong>Aktualisieren Icon</strong></label>
            <input id="tpl-co-cart-btn-update-icon" type="text" name="tpl-co-cart-btn-update-icon" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-update-icon','fa-solid fa-rotate'); ?>" placeholder="z.B. fa-solid fa-rotate">
            <small class="text-muted">FA7-Klasse, z.B. fa-solid fa-rotate</small>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-delete-bg"><strong>Löschen BG</strong></label>
            <input id="tpl-co-cart-btn-delete-bg" type="text" name="tpl-co-cart-btn-delete-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-delete-bg','rgb(220, 53, 69)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-delete-bg','rgb(220, 53, 69)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-delete-text"><strong>Löschen Text</strong></label>
            <input id="tpl-co-cart-btn-delete-text" type="text" name="tpl-co-cart-btn-delete-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-delete-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-delete-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-delete-icon"><strong>Löschen Icon</strong></label>
            <input id="tpl-co-cart-btn-delete-icon" type="text" name="tpl-co-cart-btn-delete-icon" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-delete-icon','fa-solid fa-trash'); ?>" placeholder="z.B. fa-solid fa-trash">
            <small class="text-muted">FA7-Klasse, z.B. fa-solid fa-trash</small>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-wishlist-bg"><strong>Merkzettel BG</strong></label>
            <input id="tpl-co-cart-btn-wishlist-bg" type="text" name="tpl-co-cart-btn-wishlist-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-wishlist-bg','rgb(233, 30, 99)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-wishlist-bg','rgb(233, 30, 99)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-wishlist-text"><strong>Merkzettel Text</strong></label>
            <input id="tpl-co-cart-btn-wishlist-text" type="text" name="tpl-co-cart-btn-wishlist-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-wishlist-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-cart-btn-wishlist-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-cart-btn-wishlist-icon"><strong>Merkzettel Icon</strong></label>
            <input id="tpl-co-cart-btn-wishlist-icon" type="text" name="tpl-co-cart-btn-wishlist-icon" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-cart-btn-wishlist-icon','fa-solid fa-heart'); ?>" placeholder="z.B. fa-solid fa-heart">
            <small class="text-muted">FA7-Klasse, z.B. fa-solid fa-heart</small>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Warenkorb speichern">
        </div>
    </div>

</form>
</div><!-- /#tab-warenkorb -->


<!-- ================================================================== -->
<!-- TAB: CHECKOUT -->
<!-- ================================================================== -->
<div class="mrh-tab-pane" id="tab-checkout">
<h5 class="mb-3"><i class="fa fa-cash-register me-2"></i>Checkout</h5>
<p class="text-muted small mb-3">Steuert den Checkout-Fortschrittsbalken, Checkout-Buttons und die Checkout-Seiten.</p>

<form method="post" action="">

<!-- Live-Vorschau -->
<div class="card mb-4">
    <div class="card-header"><strong>Live-Vorschau Fortschrittsbalken</strong></div>
    <div class="card-body" id="mrh-checkout-preview" style="background:#f5f5f5;padding:20px;"></div>
</div>

    <!-- ═══ Fortschrittsbalken ═══ -->
    <h6 class="border-bottom pb-2 mb-3"><i class="fa fa-list-ol me-1"></i>Fortschrittsbalken (Checkout-Navigation)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-active-bg"><strong>Aktiver Schritt BG</strong></label>
            <input id="tpl-co-progress-active-bg" type="text" name="tpl-co-progress-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-active-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-active-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-active-text"><strong>Aktiver Schritt Text</strong></label>
            <input id="tpl-co-progress-active-text" type="text" name="tpl-co-progress-active-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-active-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-active-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-done-bg"><strong>Erledigter Schritt BG</strong></label>
            <input id="tpl-co-progress-done-bg" type="text" name="tpl-co-progress-done-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-done-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-done-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-done-text"><strong>Erledigter Schritt Text</strong></label>
            <input id="tpl-co-progress-done-text" type="text" name="tpl-co-progress-done-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-done-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-done-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-inactive-bg"><strong>Inaktiver Schritt BG</strong></label>
            <input id="tpl-co-progress-inactive-bg" type="text" name="tpl-co-progress-inactive-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-inactive-bg','rgb(233, 236, 239)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-inactive-bg','rgb(233, 236, 239)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-inactive-text"><strong>Inaktiver Schritt Text</strong></label>
            <input id="tpl-co-progress-inactive-text" type="text" name="tpl-co-progress-inactive-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-inactive-text','rgb(156, 163, 175)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-inactive-text','rgb(156, 163, 175)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-line-active"><strong>Verbindungslinie aktiv</strong></label>
            <input id="tpl-co-progress-line-active" type="text" name="tpl-co-progress-line-active" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-line-active','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-line-active','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-line-inactive"><strong>Verbindungslinie inaktiv</strong></label>
            <input id="tpl-co-progress-line-inactive" type="text" name="tpl-co-progress-line-inactive" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-progress-line-inactive','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-progress-line-inactive','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-circle-size"><strong>Kreis-Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-co-progress-circle-size" type="text" name="tpl-co-progress-circle-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-progress-circle-size','48px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-icon-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-co-progress-icon-size" type="text" name="tpl-co-progress-icon-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-progress-icon-size','1.1rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-label-size"><strong>Label-Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-co-progress-label-size" type="text" name="tpl-co-progress-label-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-progress-label-size','0.75rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-progress-line-height"><strong>Linien-St&auml;rke</strong></label>
            <input id="tpl-co-progress-line-height" type="text" name="tpl-co-progress-line-height" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-progress-line-height','3px'); ?>">
        </div>
    </div>

    <!-- ═══ Fortschrittsbalken Icons ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-icons me-1"></i>Fortschrittsbalken Icons (FA7)</h6>
    <div class="row">
        <div class="col-sm-3 mb-3">
            <label for="tpl-co-icon-shipping"><strong>Versand-Icon</strong></label>
            <input id="tpl-co-icon-shipping" type="text" name="tpl-co-icon-shipping" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-icon-shipping','fa-truck'); ?>">
            <small class="text-muted">z.B. fa-truck</small>
        </div>
        <div class="col-sm-3 mb-3">
            <label for="tpl-co-icon-payment"><strong>Zahlungs-Icon</strong></label>
            <input id="tpl-co-icon-payment" type="text" name="tpl-co-icon-payment" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-icon-payment','fa-credit-card'); ?>">
            <small class="text-muted">z.B. fa-credit-card</small>
        </div>
        <div class="col-sm-3 mb-3">
            <label for="tpl-co-icon-confirm"><strong>Best&auml;tigungs-Icon</strong></label>
            <input id="tpl-co-icon-confirm" type="text" name="tpl-co-icon-confirm" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-icon-confirm','fa-clipboard-check'); ?>">
            <small class="text-muted">z.B. fa-clipboard-check</small>
        </div>
        <div class="col-sm-3 mb-3">
            <label for="tpl-co-icon-success"><strong>Erfolgs-Icon</strong></label>
            <input id="tpl-co-icon-success" type="text" name="tpl-co-icon-success" class="form-control" value="<?php echo mrh_cv($c,'tpl-co-icon-success','fa-circle-check'); ?>">
            <small class="text-muted">z.B. fa-circle-check</small>
        </div>
    </div>

    <!-- ═══ Warenkorb-Seite ═══ -->
<!-- ═══ Checkout-Buttons ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-square me-1"></i>Checkout-Buttons</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-next-bg"><strong>Weiter-Button BG</strong></label>
            <input id="tpl-co-btn-next-bg" type="text" name="tpl-co-btn-next-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-next-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-next-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-next-text"><strong>Weiter-Button Text</strong></label>
            <input id="tpl-co-btn-next-text" type="text" name="tpl-co-btn-next-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-next-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-next-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-next-hover-bg"><strong>Weiter-Button Hover</strong></label>
            <input id="tpl-co-btn-next-hover-bg" type="text" name="tpl-co-btn-next-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-next-hover-bg','rgb(56, 112, 32)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-next-hover-bg','rgb(56, 112, 32)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-back-bg"><strong>Zur&uuml;ck-Button BG</strong></label>
            <input id="tpl-co-btn-back-bg" type="text" name="tpl-co-btn-back-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-back-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-back-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-back-text"><strong>Zur&uuml;ck-Button Text</strong></label>
            <input id="tpl-co-btn-back-text" type="text" name="tpl-co-btn-back-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-back-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-back-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-order-bg"><strong>Bestellen-Button BG</strong></label>
            <input id="tpl-co-btn-order-bg" type="text" name="tpl-co-btn-order-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-order-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-order-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-order-text"><strong>Bestellen-Button Text</strong></label>
            <input id="tpl-co-btn-order-text" type="text" name="tpl-co-btn-order-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-order-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-order-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-order-hover-bg"><strong>Bestellen-Button Hover</strong></label>
            <input id="tpl-co-btn-order-hover-bg" type="text" name="tpl-co-btn-order-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-btn-order-hover-bg','rgb(56, 112, 32)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-btn-order-hover-bg','rgb(56, 112, 32)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-btn-radius"><strong>Button-Rundung</strong></label>
            <input id="tpl-co-btn-radius" type="text" name="tpl-co-btn-radius" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-btn-radius','6px'); ?>">
        </div>
    </div>

    <!-- ═══ Checkout-Seiten Allgemein ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-file-lines me-1"></i>Checkout-Seiten Allgemein</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-heading-color"><strong>&Uuml;berschriften Farbe</strong></label>
            <input id="tpl-co-heading-color" type="text" name="tpl-co-heading-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-heading-color','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-heading-color','rgb(33, 37, 41)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-section-icon-color"><strong>Section-Icon Farbe</strong></label>
            <input id="tpl-co-section-icon-color" type="text" name="tpl-co-section-icon-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-section-icon-color','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-section-icon-color','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-edit-icon-color"><strong>Bearbeiten-Icon Farbe</strong></label>
            <input id="tpl-co-edit-icon-color" type="text" name="tpl-co-edit-icon-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-edit-icon-color','rgb(96, 125, 139)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-edit-icon-color','rgb(96, 125, 139)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-highlight-bg"><strong>Highlight-Box BG</strong></label>
            <input id="tpl-co-highlight-bg" type="text" name="tpl-co-highlight-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-highlight-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-highlight-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-highlight-border"><strong>Highlight-Box Border</strong></label>
            <input id="tpl-co-highlight-border" type="text" name="tpl-co-highlight-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-highlight-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-highlight-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-success-color"><strong>Erfolgs-Farbe</strong></label>
            <input id="tpl-co-success-color" type="text" name="tpl-co-success-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-success-color','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-success-color','rgb(74, 140, 42)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Bestell-Erfolg Seite ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-circle-check me-1"></i>Bestell-Erfolg Seite</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-success-icon-size"><strong>Erfolgs-Icon Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-co-success-icon-size" type="text" name="tpl-co-success-icon-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-co-success-icon-size','3rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-success-icon-color"><strong>Erfolgs-Icon Farbe</strong></label>
            <input id="tpl-co-success-icon-color" type="text" name="tpl-co-success-icon-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-success-icon-color','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-success-icon-color','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-print-btn-bg"><strong>Drucken-Button BG</strong></label>
            <input id="tpl-co-print-btn-bg" type="text" name="tpl-co-print-btn-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-print-btn-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-print-btn-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-co-print-btn-text"><strong>Drucken-Button Text</strong></label>
            <input id="tpl-co-print-btn-text" type="text" name="tpl-co-print-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-co-print-btn-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-co-print-btn-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Checkout speichern">
        </div>
    </div>

</form>

<!-- Checkout Live-Preview Script -->
<script>
(function(){
    'use strict';
    function gv(id, def) {
        var el = document.getElementById(id);
        return el ? (el.value || def) : def;
    }
    function renderCheckoutPreview() {
        var preview = document.getElementById('mrh-checkout-preview');
        if (!preview) return;
        var activeBg = gv('tpl-co-progress-active-bg','rgb(74, 140, 42)');
        var activeText = gv('tpl-co-progress-active-text','rgb(255, 255, 255)');
        var doneBg = gv('tpl-co-progress-done-bg','rgb(74, 140, 42)');
        var doneText = gv('tpl-co-progress-done-text','rgb(255, 255, 255)');
        var inactiveBg = gv('tpl-co-progress-inactive-bg','rgb(233, 236, 239)');
        var inactiveText = gv('tpl-co-progress-inactive-text','rgb(156, 163, 175)');
        var lineActive = gv('tpl-co-progress-line-active','rgb(74, 140, 42)');
        var lineInactive = gv('tpl-co-progress-line-inactive','rgb(222, 226, 230)');
        var circleSize = gv('tpl-co-progress-circle-size','48px');
        var iconSize = gv('tpl-co-progress-icon-size','1.1rem');
        var labelSize = gv('tpl-co-progress-label-size','0.75rem');
        var lineH = gv('tpl-co-progress-line-height','3px');
        var iconShip = gv('tpl-co-icon-shipping','fa-truck');
        var iconPay = gv('tpl-co-icon-payment','fa-credit-card');
        var iconConf = gv('tpl-co-icon-confirm','fa-clipboard-check');
        var iconSucc = gv('tpl-co-icon-success','fa-circle-check');

        var steps = [
            {icon: iconShip, label: 'Versand', state: 'done'},
            {icon: iconPay, label: 'Zahlung', state: 'active'},
            {icon: iconConf, label: 'Best&auml;tigung', state: 'inactive'},
            {icon: iconSucc, label: 'Fertig', state: 'inactive'}
        ];

        var html = '<div style="display:flex;align-items:flex-start;justify-content:center;gap:0;">';
        steps.forEach(function(s, i){
            var bg = s.state === 'active' ? activeBg : (s.state === 'done' ? doneBg : inactiveBg);
            var text = s.state === 'active' ? activeText : (s.state === 'done' ? doneText : inactiveText);
            var shadow = s.state === 'active' ? 'box-shadow:0 0 0 4px ' + activeBg.replace('rgb','rgba').replace(')',',0.15)') + ';' : '';
            html += '<div style="display:flex;flex-direction:column;align-items:center;min-width:60px;">';
            html += '<div style="width:'+circleSize+';height:'+circleSize+';border-radius:50%;background:'+bg+';color:'+text+';display:flex;align-items:center;justify-content:center;font-size:'+iconSize+';'+shadow+'">';
            html += '<span class="fa-solid '+s.icon+'"></span>';
            html += '</div>';
            html += '<span style="font-size:'+labelSize+';font-weight:'+(s.state==='inactive'?'500':'700')+';color:'+(s.state==='inactive'?inactiveText:activeBg)+';margin-top:6px;">'+s.label+'</span>';
            html += '</div>';
            if (i < 3) {
                var lc = (i < 1) ? lineActive : lineInactive;
                html += '<div style="flex:1;height:'+lineH+';background:'+lc+';margin-top:calc('+circleSize+' / 2 - '+lineH+' / 2);border-radius:2px;"></div>';
            }
        });
        html += '</div>';
        preview.innerHTML = html;
    }
    renderCheckoutPreview();
    var coTab = document.querySelector('[data-tab="checkout"]');
    if (coTab) {
        coTab.addEventListener('click', function(){ setTimeout(renderCheckoutPreview, 50); });
    }
    document.querySelectorAll('#tab-checkout input, #tab-checkout select').forEach(function(el){
        el.addEventListener('input', renderCheckoutPreview);
        el.addEventListener('change', renderCheckoutPreview);
    });
})();
</script>



</div><!-- /#tab-checkout -->

<div class="mrh-tab-pane" id="tab-seedfinder-modal">
<h5 class="mb-3"><i class="fa fa-cannabis me-2"></i>Seedfinder Modal</h5>
<p class="text-muted small mb-3">Steuert das Aussehen des Seedfinder Filter-Modals, der Tab-Navigation, Buttons, Filter-Chips und des mobilen FAB-Buttons.</p>

<!-- Live-Vorschau -->
<div class="card mb-4">
    <div class="card-header"><strong>Live-Vorschau</strong></div>
    <div class="card-body" id="mrh-sf-modal-preview" style="background:#f5f5f5;padding:20px;"></div>
</div>

<form method="post" action="">

    <!-- Modal Grundstruktur -->
    <h6 class="border-bottom pb-2 mb-3"><i class="fa fa-window-maximize me-1"></i>Modal Grundstruktur</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-header-bg"><strong>Header Hintergrund</strong></label>
            <input id="tpl-sf-modal-header-bg" type="text" name="tpl-sf-modal-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-modal-header-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-modal-header-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-header-text"><strong>Header Text</strong></label>
            <input id="tpl-sf-modal-header-text" type="text" name="tpl-sf-modal-header-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-modal-header-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-modal-header-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-body-bg"><strong>Body Hintergrund</strong></label>
            <input id="tpl-sf-modal-body-bg" type="text" name="tpl-sf-modal-body-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-modal-body-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-modal-body-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-footer-bg"><strong>Footer Hintergrund</strong></label>
            <input id="tpl-sf-modal-footer-bg" type="text" name="tpl-sf-modal-footer-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-modal-footer-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-modal-footer-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-footer-border"><strong>Footer Border</strong></label>
            <input id="tpl-sf-modal-footer-border" type="text" name="tpl-sf-modal-footer-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-modal-footer-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-modal-footer-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-modal-radius"><strong>Modal Rundung</strong></label>
            <input id="tpl-sf-modal-radius" type="text" name="tpl-sf-modal-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-modal-radius','12px'); ?>">
        </div>
    </div>

    <!-- Tab-Navigation -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-folder-open me-1"></i>Tab-Navigation (Desktop)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-bg"><strong>Tab Hintergrund</strong></label>
            <input id="tpl-sf-tab-bg" type="text" name="tpl-sf-tab-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-bg','transparent'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-bg','transparent'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-text"><strong>Tab Text</strong></label>
            <input id="tpl-sf-tab-text" type="text" name="tpl-sf-tab-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-text','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-text','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-border"><strong>Tab Border</strong></label>
            <input id="tpl-sf-tab-border" type="text" name="tpl-sf-tab-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-radius"><strong>Tab Rundung</strong></label>
            <input id="tpl-sf-tab-radius" type="text" name="tpl-sf-tab-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-tab-radius','6px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-font-size"><strong>Tab Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-sf-tab-font-size" type="text" name="tpl-sf-tab-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-tab-font-size','0.85rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-padding"><strong>Tab Padding</strong></label>
            <input id="tpl-sf-tab-padding" type="text" name="tpl-sf-tab-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-tab-padding','6px 14px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-hover-bg"><strong>Tab Hover BG</strong></label>
            <input id="tpl-sf-tab-hover-bg" type="text" name="tpl-sf-tab-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-hover-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-hover-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-hover-text"><strong>Tab Hover Text</strong></label>
            <input id="tpl-sf-tab-hover-text" type="text" name="tpl-sf-tab-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-active-bg"><strong>Tab Aktiv BG</strong></label>
            <input id="tpl-sf-tab-active-bg" type="text" name="tpl-sf-tab-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-active-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-active-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-active-text"><strong>Tab Aktiv Text</strong></label>
            <input id="tpl-sf-tab-active-text" type="text" name="tpl-sf-tab-active-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-active-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-active-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-badge-bg"><strong>Tab Badge BG</strong></label>
            <input id="tpl-sf-tab-badge-bg" type="text" name="tpl-sf-tab-badge-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-badge-bg','rgb(220, 53, 69)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-badge-bg','rgb(220, 53, 69)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-tab-badge-text"><strong>Tab Badge Text</strong></label>
            <input id="tpl-sf-tab-badge-text" type="text" name="tpl-sf-tab-badge-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-tab-badge-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-tab-badge-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- Modal Footer Buttons -->
    <!-- Filter-Button (Filterbar) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-filter me-1"></i>Filter-Button (Filterbar)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-bg"><strong>Filter BG</strong></label>
            <input id="tpl-sf-btn-filter-bg" type="text" name="tpl-sf-btn-filter-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-text"><strong>Filter Text</strong></label>
            <input id="tpl-sf-btn-filter-text" type="text" name="tpl-sf-btn-filter-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-border"><strong>Filter Border</strong></label>
            <input id="tpl-sf-btn-filter-border" type="text" name="tpl-sf-btn-filter-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-hover-bg"><strong>Filter Hover BG</strong></label>
            <input id="tpl-sf-btn-filter-hover-bg" type="text" name="tpl-sf-btn-filter-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-hover-text"><strong>Filter Hover Text</strong></label>
            <input id="tpl-sf-btn-filter-hover-text" type="text" name="tpl-sf-btn-filter-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-filter-hover-border"><strong>Filter Hover Border</strong></label>
            <input id="tpl-sf-btn-filter-hover-border" type="text" name="tpl-sf-btn-filter-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-border','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-filter-hover-border','rgb(74, 140, 42)'); ?>"></div>
        </div>
    </div>

    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-hand-pointer-o me-1"></i>Modal Buttons</h6>
    <div class="row">
        <div class="col-12 mb-2"><small class="text-muted"><strong>Zur&uuml;cksetzen-Button</strong></small></div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-bg"><strong>Reset BG</strong></label>
            <input id="tpl-sf-btn-reset-bg" type="text" name="tpl-sf-btn-reset-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-bg','transparent'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-bg','transparent'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-text"><strong>Reset Text</strong></label>
            <input id="tpl-sf-btn-reset-text" type="text" name="tpl-sf-btn-reset-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-text','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-text','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-border"><strong>Reset Border</strong></label>
            <input id="tpl-sf-btn-reset-border" type="text" name="tpl-sf-btn-reset-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-border','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-border','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-hover-bg"><strong>Reset Hover BG</strong></label>
            <input id="tpl-sf-btn-reset-hover-bg" type="text" name="tpl-sf-btn-reset-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-hover-text"><strong>Reset Hover Text</strong></label>
            <input id="tpl-sf-btn-reset-hover-text" type="text" name="tpl-sf-btn-reset-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-reset-hover-border"><strong>Reset Hover Border</strong></label>
            <input id="tpl-sf-btn-reset-hover-border" type="text" name="tpl-sf-btn-reset-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-border','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-reset-hover-border','rgb(108, 117, 125)'); ?>"></div>
        </div>

        <div class="col-12 mb-2 mt-3"><small class="text-muted"><strong>Suchen-Button</strong></small></div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-bg"><strong>Suchen BG</strong></label>
            <input id="tpl-sf-btn-search-bg" type="text" name="tpl-sf-btn-search-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-text"><strong>Suchen Text</strong></label>
            <input id="tpl-sf-btn-search-text" type="text" name="tpl-sf-btn-search-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-border"><strong>Suchen Border</strong></label>
            <input id="tpl-sf-btn-search-border" type="text" name="tpl-sf-btn-search-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-hover-bg"><strong>Suchen Hover BG</strong></label>
            <input id="tpl-sf-btn-search-hover-bg" type="text" name="tpl-sf-btn-search-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-hover-text"><strong>Suchen Hover Text</strong></label>
            <input id="tpl-sf-btn-search-hover-text" type="text" name="tpl-sf-btn-search-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-search-hover-border"><strong>Suchen Hover Border</strong></label>
            <input id="tpl-sf-btn-search-hover-border" type="text" name="tpl-sf-btn-search-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-border','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-search-hover-border','rgb(74, 140, 42)'); ?>"></div>
        </div>

        <div class="col-12 mb-2 mt-3"><small class="text-muted"><strong>Schlie&szlig;en-Button</strong></small></div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-bg"><strong>Schlie&szlig;en BG</strong></label>
            <input id="tpl-sf-btn-close-bg" type="text" name="tpl-sf-btn-close-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-bg','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-bg','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-text"><strong>Schlie&szlig;en Text</strong></label>
            <input id="tpl-sf-btn-close-text" type="text" name="tpl-sf-btn-close-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-border"><strong>Schlie&szlig;en Border</strong></label>
            <input id="tpl-sf-btn-close-border" type="text" name="tpl-sf-btn-close-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-border','rgb(108, 117, 125)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-border','rgb(108, 117, 125)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-hover-bg"><strong>Schlie&szlig;en Hover BG</strong></label>
            <input id="tpl-sf-btn-close-hover-bg" type="text" name="tpl-sf-btn-close-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-bg','rgb(90, 98, 104)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-bg','rgb(90, 98, 104)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-hover-text"><strong>Schlie&szlig;en Hover Text</strong></label>
            <input id="tpl-sf-btn-close-hover-text" type="text" name="tpl-sf-btn-close-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-btn-close-hover-border"><strong>Schlie&szlig;en Hover Border</strong></label>
            <input id="tpl-sf-btn-close-hover-border" type="text" name="tpl-sf-btn-close-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-border','rgb(90, 98, 104)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-btn-close-hover-border','rgb(90, 98, 104)'); ?>"></div>
        </div>
    </div>

    <!-- Filter-Chips -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-tags me-1"></i>Filter-Chips (aktive Filter)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-chip-bg"><strong>Chip BG</strong></label>
            <input id="tpl-sf-chip-bg" type="text" name="tpl-sf-chip-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-chip-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-chip-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-chip-text"><strong>Chip Text</strong></label>
            <input id="tpl-sf-chip-text" type="text" name="tpl-sf-chip-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-chip-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-chip-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-chip-radius"><strong>Chip Rundung</strong></label>
            <input id="tpl-sf-chip-radius" type="text" name="tpl-sf-chip-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-chip-radius','20px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-chip-font-size"><strong>Chip Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-sf-chip-font-size" type="text" name="tpl-sf-chip-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-chip-font-size','0.78rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-chip-padding"><strong>Chip Padding</strong></label>
            <input id="tpl-sf-chip-padding" type="text" name="tpl-sf-chip-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-chip-padding','5px 10px'); ?>">
        </div>
    </div>

    <!-- sf-filter-tag -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-tag me-1"></i>Product Card Filter Tags (.sf-filter-tag)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-filter-tag-bg"><strong>Tag BG</strong></label>
            <input id="tpl-sf-filter-tag-bg" type="text" name="tpl-sf-filter-tag-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-filter-tag-bg','rgb(13, 110, 253)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-filter-tag-bg','rgb(13, 110, 253)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-filter-tag-text"><strong>Tag Text</strong></label>
            <input id="tpl-sf-filter-tag-text" type="text" name="tpl-sf-filter-tag-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-filter-tag-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-filter-tag-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-filter-tag-radius"><strong>Tag Rundung</strong></label>
            <input id="tpl-sf-filter-tag-radius" type="text" name="tpl-sf-filter-tag-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-filter-tag-radius','4px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-filter-tag-font-size"><strong>Tag Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-sf-filter-tag-font-size" type="text" name="tpl-sf-filter-tag-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-filter-tag-font-size','0.75rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-filter-tag-padding"><strong>Tag Padding</strong></label>
            <input id="tpl-sf-filter-tag-padding" type="text" name="tpl-sf-filter-tag-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-filter-tag-padding','2px 6px'); ?>">
        </div>
    </div>

    <!-- Checkbox -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-check-square me-1"></i>Checkbox (Filter-Optionen)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-checkbox-checked-bg"><strong>Checked BG</strong></label>
            <input id="tpl-sf-checkbox-checked-bg" type="text" name="tpl-sf-checkbox-checked-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-checkbox-checked-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-checkbox-checked-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-checkbox-checked-border"><strong>Checked Border</strong></label>
            <input id="tpl-sf-checkbox-checked-border" type="text" name="tpl-sf-checkbox-checked-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-checkbox-checked-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-checkbox-checked-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
    </div>

    <!-- Accordion (Mobile) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-bars me-1"></i>Accordion (Mobile)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-bg"><strong>Accordion BG</strong></label>
            <input id="tpl-sf-accordion-bg" type="text" name="tpl-sf-accordion-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-hover-bg"><strong>Accordion Hover BG</strong></label>
            <input id="tpl-sf-accordion-hover-bg" type="text" name="tpl-sf-accordion-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-hover-bg','rgb(233, 236, 239)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-hover-bg','rgb(233, 236, 239)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-active-bg"><strong>Accordion Aktiv BG</strong></label>
            <input id="tpl-sf-accordion-active-bg" type="text" name="tpl-sf-accordion-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-active-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-active-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-active-text"><strong>Accordion Aktiv Text</strong></label>
            <input id="tpl-sf-accordion-active-text" type="text" name="tpl-sf-accordion-active-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-active-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-active-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-badge-bg"><strong>Accordion Badge BG</strong></label>
            <input id="tpl-sf-accordion-badge-bg" type="text" name="tpl-sf-accordion-badge-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-badge-bg','rgb(220, 53, 69)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-badge-bg','rgb(220, 53, 69)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-accordion-badge-text"><strong>Accordion Badge Text</strong></label>
            <input id="tpl-sf-accordion-badge-text" type="text" name="tpl-sf-accordion-badge-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-accordion-badge-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-accordion-badge-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- FAB-Button (Mobile) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-circle me-1"></i>FAB-Button (Mobile)</h6>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-bg"><strong>FAB Hintergrund</strong></label>
            <input id="tpl-sf-fab-bg" type="text" name="tpl-sf-fab-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-text"><strong>FAB Icon-Farbe</strong></label>
            <input id="tpl-sf-fab-text" type="text" name="tpl-sf-fab-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-size"><strong>FAB Gr&ouml;&szlig;e</strong></label>
            <input id="tpl-sf-fab-size" type="text" name="tpl-sf-fab-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-fab-size','56px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-hover-bg"><strong>FAB Hover BG</strong></label>
            <input id="tpl-sf-fab-hover-bg" type="text" name="tpl-sf-fab-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-hover-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-hover-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-hover-text"><strong>FAB Hover Text</strong></label>
            <input id="tpl-sf-fab-hover-text" type="text" name="tpl-sf-fab-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-badge-bg"><strong>FAB Badge BG</strong></label>
            <input id="tpl-sf-fab-badge-bg" type="text" name="tpl-sf-fab-badge-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-badge-bg','rgb(220, 53, 69)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-badge-bg','rgb(220, 53, 69)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-fab-badge-text"><strong>FAB Badge Text</strong></label>
            <input id="tpl-sf-fab-badge-text" type="text" name="tpl-sf-fab-badge-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-fab-badge-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-fab-badge-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- Schnellfilter-Dropdowns (sf-quick-filter-bar) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-filter me-1"></i>Schnellfilter-Dropdowns</h6>
    <p class="text-muted small">Steuert die Dropdown-Buttons in der Filterbar (Hersteller, Genetik, THC, Ertrag).</p>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-bg"><strong>DD Hintergrund</strong></label>
            <input id="tpl-sf-dd-bg" type="text" name="tpl-sf-dd-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-text"><strong>DD Text</strong></label>
            <input id="tpl-sf-dd-text" type="text" name="tpl-sf-dd-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-text','rgb(51, 51, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-text','rgb(51, 51, 51)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-border"><strong>DD Rahmen</strong></label>
            <input id="tpl-sf-dd-border" type="text" name="tpl-sf-dd-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-radius"><strong>DD Rundung</strong></label>
            <input id="tpl-sf-dd-radius" type="text" name="tpl-sf-dd-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-dd-radius','6px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-font-size"><strong>DD Schriftgr&ouml;&szlig;e</strong></label>
            <input id="tpl-sf-dd-font-size" type="text" name="tpl-sf-dd-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-dd-font-size','0.8125rem'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-hover-border"><strong>DD Hover Rahmen</strong></label>
            <input id="tpl-sf-dd-hover-border" type="text" name="tpl-sf-dd-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-hover-border','rgb(13, 148, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-hover-border','rgb(13, 148, 136)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-hover-text"><strong>DD Hover Text</strong></label>
            <input id="tpl-sf-dd-hover-text" type="text" name="tpl-sf-dd-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-hover-text','rgb(13, 148, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-hover-text','rgb(13, 148, 136)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-active-bg"><strong>DD Aktiv Hintergrund</strong></label>
            <input id="tpl-sf-dd-active-bg" type="text" name="tpl-sf-dd-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-active-bg','rgb(13, 148, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-active-bg','rgb(13, 148, 136)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-active-text"><strong>DD Aktiv Text</strong></label>
            <input id="tpl-sf-dd-active-text" type="text" name="tpl-sf-dd-active-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-active-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-active-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-active-border"><strong>DD Aktiv Rahmen</strong></label>
            <input id="tpl-sf-dd-active-border" type="text" name="tpl-sf-dd-active-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-active-border','rgb(13, 148, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-active-border','rgb(13, 148, 136)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-menu-bg"><strong>Men&uuml; Hintergrund</strong></label>
            <input id="tpl-sf-dd-menu-bg" type="text" name="tpl-sf-dd-menu-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-menu-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-menu-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-menu-border"><strong>Men&uuml; Rahmen</strong></label>
            <input id="tpl-sf-dd-menu-border" type="text" name="tpl-sf-dd-menu-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-menu-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-menu-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-menu-radius"><strong>Men&uuml; Rundung</strong></label>
            <input id="tpl-sf-dd-menu-radius" type="text" name="tpl-sf-dd-menu-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-dd-menu-radius','8px'); ?>">
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-badge-bg"><strong>Badge Hintergrund</strong></label>
            <input id="tpl-sf-dd-badge-bg" type="text" name="tpl-sf-dd-badge-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-badge-bg','rgb(13, 110, 253)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-badge-bg','rgb(13, 110, 253)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-badge-text"><strong>Badge Text</strong></label>
            <input id="tpl-sf-dd-badge-text" type="text" name="tpl-sf-dd-badge-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-badge-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-badge-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-dd-count-color"><strong>Count Farbe</strong></label>
            <input id="tpl-sf-dd-count-color" type="text" name="tpl-sf-dd-count-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-dd-count-color','rgb(153, 153, 153)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-dd-count-color','rgb(153, 153, 153)'); ?>"></div>
        </div>
    </div>

    <!-- Icons -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-icons me-1"></i>Icons (Font Awesome Klassen)</h6>
    <p class="text-muted small">Gib die FA-Klasse ohne "fa " Pr&auml;fix ein, z.B. <code>fa-star</code>, <code>fa-dna</code>, <code>fa-leaf</code></p>
    <div class="row">
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-tab-main"><strong>Tab: Haupt</strong></label>
            <input id="tpl-sf-icon-tab-main" type="text" name="tpl-sf-icon-tab-main" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-tab-main','fa-star'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-tab-main','fa-star'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-tab-genetics"><strong>Tab: Genetik</strong></label>
            <input id="tpl-sf-icon-tab-genetics" type="text" name="tpl-sf-icon-tab-genetics" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-tab-genetics','fa-dna'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-tab-genetics','fa-dna'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-tab-cultivation"><strong>Tab: Anbau</strong></label>
            <input id="tpl-sf-icon-tab-cultivation" type="text" name="tpl-sf-icon-tab-cultivation" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-tab-cultivation','fa-seedling'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-tab-cultivation','fa-seedling'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-tab-taste"><strong>Tab: Geschmack</strong></label>
            <input id="tpl-sf-icon-tab-taste" type="text" name="tpl-sf-icon-tab-taste" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-tab-taste','fa-leaf'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-tab-taste','fa-leaf'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-tab-advanced"><strong>Tab: Erweitert</strong></label>
            <input id="tpl-sf-icon-tab-advanced" type="text" name="tpl-sf-icon-tab-advanced" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-tab-advanced','fa-cog'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-tab-advanced','fa-cog'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-modal-header"><strong>Modal Header</strong></label>
            <input id="tpl-sf-icon-modal-header" type="text" name="tpl-sf-icon-modal-header" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-modal-header','fa-sliders'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-modal-header','fa-sliders'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-btn-reset"><strong>Button: Reset</strong></label>
            <input id="tpl-sf-icon-btn-reset" type="text" name="tpl-sf-icon-btn-reset" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-btn-reset','fa-undo'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-btn-reset','fa-undo'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-btn-search"><strong>Button: Suchen</strong></label>
            <input id="tpl-sf-icon-btn-search" type="text" name="tpl-sf-icon-btn-search" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-btn-search','fa-search'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-btn-search','fa-search'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-btn-close"><strong>Button: Schlie&szlig;en</strong></label>
            <input id="tpl-sf-icon-btn-close" type="text" name="tpl-sf-icon-btn-close" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-btn-close',''); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-btn-close',''); ?> mt-1" style="font-size:1.2rem;"></span>
            <small class="text-muted">Leer lassen = kein Icon</small>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-btn-filter"><strong>Button: Filter</strong></label>
            <input id="tpl-sf-icon-btn-filter" type="text" name="tpl-sf-icon-btn-filter" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-btn-filter','fa-sliders'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-btn-filter','fa-sliders'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
        <div class="col-sm-4 mb-3">
            <label for="tpl-sf-icon-fab"><strong>FAB-Button</strong></label>
            <input id="tpl-sf-icon-fab" type="text" name="tpl-sf-icon-fab" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-icon-fab','fa-sliders'); ?>">
            <span class="fa <?php echo mrh_cv($c,'tpl-sf-icon-fab','fa-sliders'); ?> mt-1" style="font-size:1.2rem;"></span>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Seedfinder Modal speichern">
        </div>
    </div>

</form>

</div><!-- /#tab-seedfinder-modal -->

<!-- ================================================================== -->
<!-- TAB: SEEDFINDER SEITE -->
<!-- ================================================================== -->
<div class="mrh-tab-pane" id="tab-seedfinder-seite">
<form method="post" action="">

<!-- ═══ Seedfinder Seite (Hauptseite / Kategorie-Karten) ═══ -->
    <h5 class="border-bottom pb-2 mb-3 mt-5" style="color:#198754;"><i class="fa fa-home me-2"></i>Seedfinder Seite (Hauptseite)</h5>

    <!-- Live-Vorschau Seedfinder Seite -->
    <div class="col-12 mb-3">
        <div id="mrh-sf-page-preview" style="border:1px solid #dee2e6;border-radius:8px;overflow:hidden;background:#f8f9fa;padding:16px;"></div>
    </div>

    <!-- Kategorie-Karten -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-th-large me-1"></i>Kategorie-Karten</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-card-bg"><strong>Karten-Hintergrund</strong></label>
            <input id="tpl-sf-page-card-bg" type="text" name="tpl-sf-page-card-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-card-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-card-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-card-border"><strong>Karten-Rahmen</strong></label>
            <input id="tpl-sf-page-card-border" type="text" name="tpl-sf-page-card-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-card-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-card-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-card-hover-border"><strong>Hover-Rahmen</strong></label>
            <input id="tpl-sf-page-card-hover-border" type="text" name="tpl-sf-page-card-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-card-hover-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-card-hover-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-card-radius"><strong>Rundung</strong></label>
            <input id="tpl-sf-page-card-radius" type="text" name="tpl-sf-page-card-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-card-radius','12px'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-card-shadow"><strong>Schatten</strong></label>
            <input id="tpl-sf-page-card-shadow" type="text" name="tpl-sf-page-card-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-card-shadow','0 2px 8px rgba(0,0,0,0.08)'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-card-hover-shadow"><strong>Hover-Schatten</strong></label>
            <input id="tpl-sf-page-card-hover-shadow" type="text" name="tpl-sf-page-card-hover-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-card-hover-shadow','0 6px 20px rgba(0,0,0,0.12)'); ?>">
        </div>
    </div>

    <!-- Kategorie-Button (Auswählen) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-hand-pointer me-1"></i>Kategorie-Button (Auswählen)</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-btn-bg"><strong>Button-Hintergrund</strong></label>
            <input id="tpl-sf-page-btn-bg" type="text" name="tpl-sf-page-btn-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-btn-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-btn-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-btn-text"><strong>Button-Text</strong></label>
            <input id="tpl-sf-page-btn-text" type="text" name="tpl-sf-page-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-btn-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-btn-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-btn-hover-bg"><strong>Hover-Hintergrund</strong></label>
            <input id="tpl-sf-page-btn-hover-bg" type="text" name="tpl-sf-page-btn-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-btn-hover-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-btn-hover-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-btn-hover-text"><strong>Hover-Text</strong></label>
            <input id="tpl-sf-page-btn-hover-text" type="text" name="tpl-sf-page-btn-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-btn-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-btn-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-btn-radius"><strong>Button-Rundung</strong></label>
            <input id="tpl-sf-page-btn-radius" type="text" name="tpl-sf-page-btn-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-btn-radius','6px'); ?>">
        </div>
    </div>

    <!-- Badge (X Produkte) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-tag me-1"></i>Badge (X Produkte)</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-badge-bg"><strong>Badge-Hintergrund</strong></label>
            <input id="tpl-sf-page-badge-bg" type="text" name="tpl-sf-page-badge-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-badge-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-badge-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-badge-text"><strong>Badge-Text</strong></label>
            <input id="tpl-sf-page-badge-text" type="text" name="tpl-sf-page-badge-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-badge-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-badge-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- Icon-Container -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-circle me-1"></i>Icon-Container</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-icon-bg"><strong>Icon-Hintergrund</strong></label>
            <input id="tpl-sf-page-icon-bg" type="text" name="tpl-sf-page-icon-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-icon-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-icon-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-icon-color"><strong>Icon-Farbe</strong></label>
            <input id="tpl-sf-page-icon-color" type="text" name="tpl-sf-page-icon-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-icon-color','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-icon-color','rgb(93, 178, 51)'); ?>"></div>
        </div>
    </div>

    <!-- Info-Card -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-info-circle me-1"></i>Info-Card (So funktioniert...)</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-info-bg"><strong>Info-Hintergrund</strong></label>
            <input id="tpl-sf-page-info-bg" type="text" name="tpl-sf-page-info-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-info-bg','rgb(248, 255, 245)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-info-bg','rgb(248, 255, 245)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-info-border"><strong>Info-Rahmen (links)</strong></label>
            <input id="tpl-sf-page-info-border" type="text" name="tpl-sf-page-info-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-info-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-info-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-info-text"><strong>Info-Text</strong></label>
            <input id="tpl-sf-page-info-text" type="text" name="tpl-sf-page-info-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-info-text','rgb(33, 37, 41)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-info-text','rgb(33, 37, 41)'); ?>"></div>
        </div>
    </div>

    <!-- Benefit-Icons + Step-Badges -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-check-circle me-1"></i>Benefit-Icons &amp; Step-Badges</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-color"><strong>Benefit-Icon-Farbe</strong></label>
            <input id="tpl-sf-page-benefit-color" type="text" name="tpl-sf-page-benefit-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-color','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-color','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-step-bg"><strong>Step-Badge BG</strong></label>
            <input id="tpl-sf-page-step-bg" type="text" name="tpl-sf-page-step-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-step-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-step-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-step-text"><strong>Step-Badge Text</strong></label>
            <input id="tpl-sf-page-step-text" type="text" name="tpl-sf-page-step-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-step-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-step-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
    </div>

    <!-- Wizard (Anfänger-Finder) -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-magic me-1"></i>Wizard (Anfänger-Finder)</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-header-bg"><strong>Header-Hintergrund</strong></label>
            <input id="tpl-sf-page-wizard-header-bg" type="text" name="tpl-sf-page-wizard-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-header-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-header-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-header-text"><strong>Header-Text</strong></label>
            <input id="tpl-sf-page-wizard-header-text" type="text" name="tpl-sf-page-wizard-header-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-header-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-header-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-btn-bg"><strong>Button-Hintergrund</strong></label>
            <input id="tpl-sf-page-wizard-btn-bg" type="text" name="tpl-sf-page-wizard-btn-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-btn-text"><strong>Button-Text</strong></label>
            <input id="tpl-sf-page-wizard-btn-text" type="text" name="tpl-sf-page-wizard-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-btn-hover-bg"><strong>Button-Hover</strong></label>
            <input id="tpl-sf-page-wizard-btn-hover-bg" type="text" name="tpl-sf-page-wizard-btn-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-hover-bg','rgb(74, 140, 42)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-btn-hover-bg','rgb(74, 140, 42)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-wizard-progress-bg"><strong>Fortschrittsbalken</strong></label>
            <input id="tpl-sf-page-wizard-progress-bg" type="text" name="tpl-sf-page-wizard-progress-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-wizard-progress-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-wizard-progress-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Finder-Cards (Anfänger/Profi Hero-Cards) ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-id-card me-1"></i>Finder-Cards (Anfänger/Profi)</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-bg"><strong>Karten-Hintergrund</strong></label>
            <input id="tpl-sf-page-finder-bg" type="text" name="tpl-sf-page-finder-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-bg','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-finder-bg','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-radius"><strong>Rundung</strong></label>
            <input id="tpl-sf-page-finder-radius" type="text" name="tpl-sf-page-finder-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-radius','16px'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-shadow"><strong>Schatten</strong></label>
            <input id="tpl-sf-page-finder-shadow" type="text" name="tpl-sf-page-finder-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-shadow','0 4px 20px rgba(0,0,0,0.1)'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-hover-shadow"><strong>Hover-Schatten</strong></label>
            <input id="tpl-sf-page-finder-hover-shadow" type="text" name="tpl-sf-page-finder-hover-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-hover-shadow','0 8px 30px rgba(0,0,0,0.15)'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-header-bg"><strong>Anfänger Header BG</strong></label>
            <input id="tpl-sf-page-finder-header-bg" type="text" name="tpl-sf-page-finder-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-header-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-finder-header-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-header-bg2"><strong>Anfänger Header BG2</strong></label>
            <input id="tpl-sf-page-finder-header-bg2" type="text" name="tpl-sf-page-finder-header-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-header-bg2','rgb(109, 144, 44)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-finder-header-bg2','rgb(109, 144, 44)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-finder-header-text"><strong>Header-Text</strong></label>
            <input id="tpl-sf-page-finder-header-text" type="text" name="tpl-sf-page-finder-header-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-finder-header-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-finder-header-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-header-bg"><strong>Profi Header BG</strong></label>
            <input id="tpl-sf-page-profi-header-bg" type="text" name="tpl-sf-page-profi-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-header-bg','rgb(23, 162, 184)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-header-bg','rgb(23, 162, 184)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-header-bg2"><strong>Profi Header BG2</strong></label>
            <input id="tpl-sf-page-profi-header-bg2" type="text" name="tpl-sf-page-profi-header-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-header-bg2','rgb(17, 122, 139)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-header-bg2','rgb(17, 122, 139)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Benefit-Cards (Feature-Icons) ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-th me-1"></i>Benefit-Cards (Feature-Icons)</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-card-bg"><strong>Karten-Hintergrund</strong></label>
            <input id="tpl-sf-page-benefit-card-bg" type="text" name="tpl-sf-page-benefit-card-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-card-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-card-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-card-border"><strong>Karten-Rahmen</strong></label>
            <input id="tpl-sf-page-benefit-card-border" type="text" name="tpl-sf-page-benefit-card-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-card-border','rgb(233, 236, 239)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-card-border','rgb(233, 236, 239)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-card-radius"><strong>Rundung</strong></label>
            <input id="tpl-sf-page-benefit-card-radius" type="text" name="tpl-sf-page-benefit-card-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-card-radius','12px'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-icon-bg"><strong>Anfänger Icon BG</strong></label>
            <input id="tpl-sf-page-benefit-icon-bg" type="text" name="tpl-sf-page-benefit-icon-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-icon-bg2"><strong>Anfänger Icon BG2</strong></label>
            <input id="tpl-sf-page-benefit-icon-bg2" type="text" name="tpl-sf-page-benefit-icon-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-bg2','rgb(109, 144, 44)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-bg2','rgb(109, 144, 44)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-benefit-icon-text"><strong>Icon-Text</strong></label>
            <input id="tpl-sf-page-benefit-icon-text" type="text" name="tpl-sf-page-benefit-icon-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-benefit-icon-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-icon-bg"><strong>Profi Icon BG</strong></label>
            <input id="tpl-sf-page-profi-icon-bg" type="text" name="tpl-sf-page-profi-icon-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-icon-bg','rgb(23, 162, 184)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-icon-bg','rgb(23, 162, 184)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-icon-bg2"><strong>Profi Icon BG2</strong></label>
            <input id="tpl-sf-page-profi-icon-bg2" type="text" name="tpl-sf-page-profi-icon-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-icon-bg2','rgb(17, 122, 139)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-icon-bg2','rgb(17, 122, 139)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Trust-Section (Bewertungen) ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-star me-1"></i>Trust-Section (Bewertungen)</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-trust-bg"><strong>Anfänger BG</strong></label>
            <input id="tpl-sf-page-trust-bg" type="text" name="tpl-sf-page-trust-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-trust-bg','rgb(240, 248, 240)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-trust-bg','rgb(240, 248, 240)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-trust-border"><strong>Anfänger Rahmen</strong></label>
            <input id="tpl-sf-page-trust-border" type="text" name="tpl-sf-page-trust-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-trust-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-trust-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-trust-stars"><strong>Sterne-Farbe</strong></label>
            <input id="tpl-sf-page-trust-stars" type="text" name="tpl-sf-page-trust-stars" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-trust-stars','rgb(255, 193, 7)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-trust-stars','rgb(255, 193, 7)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-trust-text"><strong>Text-Farbe</strong></label>
            <input id="tpl-sf-page-trust-text" type="text" name="tpl-sf-page-trust-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-trust-text','rgb(51, 51, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-trust-text','rgb(51, 51, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-trust-bg"><strong>Profi BG</strong></label>
            <input id="tpl-sf-page-profi-trust-bg" type="text" name="tpl-sf-page-profi-trust-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-trust-bg','rgb(232, 244, 248)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-trust-bg','rgb(232, 244, 248)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-trust-border"><strong>Profi Rahmen</strong></label>
            <input id="tpl-sf-page-profi-trust-border" type="text" name="tpl-sf-page-profi-trust-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-trust-border','rgb(23, 162, 184)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-trust-border','rgb(23, 162, 184)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ CTA-Buttons (Finder) ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-play-circle me-1"></i>CTA-Buttons (Finder)</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-cta-bg"><strong>Anfänger BG</strong></label>
            <input id="tpl-sf-page-cta-bg" type="text" name="tpl-sf-page-cta-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-cta-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-cta-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-cta-bg2"><strong>Anfänger BG2 (Gradient)</strong></label>
            <input id="tpl-sf-page-cta-bg2" type="text" name="tpl-sf-page-cta-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-cta-bg2','rgb(109, 144, 44)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-cta-bg2','rgb(109, 144, 44)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-cta-text"><strong>Text-Farbe</strong></label>
            <input id="tpl-sf-page-cta-text" type="text" name="tpl-sf-page-cta-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-cta-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-cta-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-cta-radius"><strong>Rundung</strong></label>
            <input id="tpl-sf-page-cta-radius" type="text" name="tpl-sf-page-cta-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-cta-radius','50px'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-cta-shadow"><strong>Schatten</strong></label>
            <input id="tpl-sf-page-cta-shadow" type="text" name="tpl-sf-page-cta-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-page-cta-shadow','0 4px 12px rgba(93,178,51,0.3)'); ?>">
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-cta-bg"><strong>Profi BG</strong></label>
            <input id="tpl-sf-page-profi-cta-bg" type="text" name="tpl-sf-page-profi-cta-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-cta-bg','rgb(23, 162, 184)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-cta-bg','rgb(23, 162, 184)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-profi-cta-bg2"><strong>Profi BG2 (Gradient)</strong></label>
            <input id="tpl-sf-page-profi-cta-bg2" type="text" name="tpl-sf-page-profi-cta-bg2" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-profi-cta-bg2','rgb(17, 122, 139)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-profi-cta-bg2','rgb(17, 122, 139)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Guarantee-Text ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-check-circle me-1"></i>Guarantee-Text</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <label for="tpl-sf-page-guarantee-text"><strong>Text-Farbe</strong></label>
            <input id="tpl-sf-page-guarantee-text" type="text" name="tpl-sf-page-guarantee-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-guarantee-text','rgb(136, 136, 136)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-guarantee-text','rgb(136, 136, 136)'); ?>"></div>
        </div>
        <div class="col-md-6">
            <label for="tpl-sf-page-guarantee-icon"><strong>Icon-Farbe</strong></label>
            <input id="tpl-sf-page-guarantee-icon" type="text" name="tpl-sf-page-guarantee-icon" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-guarantee-icon','rgb(109, 144, 44)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-guarantee-icon','rgb(109, 144, 44)'); ?>"></div>
        </div>
    </div>

    <!-- ═══ Hersteller-Cards (Top Hersteller) ═══ -->
    <h6 class="border-bottom pb-2 mb-3 mt-4"><i class="fa fa-industry me-1"></i>Hersteller-Cards</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-header-bg"><strong>Header-Hintergrund</strong></label>
            <input id="tpl-sf-page-mfr-header-bg" type="text" name="tpl-sf-page-mfr-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-header-bg','rgb(248, 249, 250)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-header-bg','rgb(248, 249, 250)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-border"><strong>Karten-Rahmen</strong></label>
            <input id="tpl-sf-page-mfr-border" type="text" name="tpl-sf-page-mfr-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-border','rgb(222, 226, 230)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-border','rgb(222, 226, 230)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-btn-border"><strong>Button-Rahmen</strong></label>
            <input id="tpl-sf-page-mfr-btn-border" type="text" name="tpl-sf-page-mfr-btn-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-border','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-border','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-btn-text"><strong>Button-Text</strong></label>
            <input id="tpl-sf-page-mfr-btn-text" type="text" name="tpl-sf-page-mfr-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-text','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-text','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-btn-hover-bg"><strong>Button Hover BG</strong></label>
            <input id="tpl-sf-page-mfr-btn-hover-bg" type="text" name="tpl-sf-page-mfr-btn-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-hover-bg','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-hover-bg','rgb(93, 178, 51)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-btn-hover-text"><strong>Button Hover Text</strong></label>
            <input id="tpl-sf-page-mfr-btn-hover-text" type="text" name="tpl-sf-page-mfr-btn-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-hover-text','rgb(255, 255, 255)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-btn-hover-text','rgb(255, 255, 255)'); ?>"></div>
        </div>
        <div class="col-md-4">
            <label for="tpl-sf-page-mfr-price-color"><strong>Preis-Farbe</strong></label>
            <input id="tpl-sf-page-mfr-price-color" type="text" name="tpl-sf-page-mfr-price-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-page-mfr-price-color','rgb(93, 178, 51)'); ?>">
            <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-page-mfr-price-color','rgb(93, 178, 51)'); ?>"></div>
        </div>
    </div>

    <!-- Speichern -->
    <div class="col-12 mt-3 mb-3">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Seedfinder Seite speichern">
    </div>

</form>

<!-- Seedfinder Modal Live-Preview -->
<script>
(function(){
    'use strict';
    function gv(n,fb){var e=document.getElementById(n);return e?(e.value||fb):fb;}

    // Snapshot aller aktuellen Werte fuer Aenderungserkennung
    var _sfSnapshot = {};
    function sfTakeSnapshot() {
        var els = document.querySelectorAll('#tab-seedfinder-seite input, #tab-seedfinder-seite select');
        els.forEach(function(el){ if(el.id) _sfSnapshot[el.id] = el.value; });
    }
    function sfHasChanged() {
        var els = document.querySelectorAll('#tab-seedfinder-seite input, #tab-seedfinder-seite select');
        for (var i = 0; i < els.length; i++) {
            var el = els[i];
            if (el.id && _sfSnapshot[el.id] !== el.value) return true;
        }
        return false;
    }

    function renderSfModalPreview() {
        var p = document.getElementById('mrh-sf-modal-preview');
        if (!p) return;
        sfTakeSnapshot();
        var hbg = gv('tpl-sf-modal-header-bg','rgb(93, 178, 51)');
        var htx = gv('tpl-sf-modal-header-text','rgb(255, 255, 255)');
        var bbg = gv('tpl-sf-modal-body-bg','rgb(255, 255, 255)');
        var fbg = gv('tpl-sf-modal-footer-bg','rgb(248, 249, 250)');
        var fbd = gv('tpl-sf-modal-footer-border','rgb(222, 226, 230)');
        var rad = gv('tpl-sf-modal-radius','12px');
        var tbg = gv('tpl-sf-tab-bg','transparent');
        var ttx = gv('tpl-sf-tab-text','rgb(93, 178, 51)');
        var tbd = gv('tpl-sf-tab-border','rgb(93, 178, 51)');
        var trad = gv('tpl-sf-tab-radius','6px');
        var tfs = gv('tpl-sf-tab-font-size','0.85rem');
        var tpad = gv('tpl-sf-tab-padding','6px 14px');
        var tabg = gv('tpl-sf-tab-active-bg','rgb(93, 178, 51)');
        var tatx = gv('tpl-sf-tab-active-text','rgb(255, 255, 255)');
        var rbg = gv('tpl-sf-btn-reset-bg','transparent');
        var rtx = gv('tpl-sf-btn-reset-text','rgb(108, 117, 125)');
        var rbd = gv('tpl-sf-btn-reset-border','rgb(108, 117, 125)');
        var sbg = gv('tpl-sf-btn-search-bg','rgb(93, 178, 51)');
        var stx = gv('tpl-sf-btn-search-text','rgb(255, 255, 255)');
        var cbg = gv('tpl-sf-btn-close-bg','rgb(108, 117, 125)');
        var ctx = gv('tpl-sf-btn-close-text','rgb(255, 255, 255)');
        var chipbg = gv('tpl-sf-chip-bg','rgb(93, 178, 51)');
        var chiptx = gv('tpl-sf-chip-text','rgb(255, 255, 255)');
        var chiprad = gv('tpl-sf-chip-radius','20px');
        var chipfs = gv('tpl-sf-chip-font-size','0.78rem');
        var chippad = gv('tpl-sf-chip-padding','5px 10px');
        var chkbg = gv('tpl-sf-checkbox-checked-bg','rgb(93, 178, 51)');
        var ftbg = gv('tpl-sf-filter-tag-bg','rgb(13, 110, 253)');
        var fttx = gv('tpl-sf-filter-tag-text','rgb(255, 255, 255)');
        var ftrad = gv('tpl-sf-filter-tag-radius','4px');
        var ftfs = gv('tpl-sf-filter-tag-font-size','0.75rem');
        var ftpad = gv('tpl-sf-filter-tag-padding','2px 6px');
        var iMain = gv('tpl-sf-icon-tab-main','fa-star');
        var iGen = gv('tpl-sf-icon-tab-genetics','fa-dna');
        var iCult = gv('tpl-sf-icon-tab-cultivation','fa-seedling');
        var iTaste = gv('tpl-sf-icon-tab-taste','fa-leaf');
        var iAdv = gv('tpl-sf-icon-tab-advanced','fa-cog');
        var iHeader = gv('tpl-sf-icon-modal-header','fa-sliders');
        var iReset = gv('tpl-sf-icon-btn-reset','fa-undo');
        var iSearch = gv('tpl-sf-icon-btn-search','fa-search');

        var tabStyle = 'display:inline-block;padding:'+tpad+';font-size:'+tfs+';border:1px solid '+tbd+';border-radius:'+trad+';margin-right:4px;cursor:pointer;';
        var h = '';
        // Modal Header
        h += '<div style="background:'+hbg+';color:'+htx+';padding:12px 20px;border-radius:'+rad+' '+rad+' 0 0;font-weight:600;"><span class="fa '+iHeader+'" style="margin-right:8px;"></span>Alle Filter</div>';
        // Modal Body
        h += '<div style="background:'+bbg+';padding:16px 20px;">';
        // Chips
        h += '<div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;">';
        h += '<span style="background:'+chipbg+';color:'+chiptx+';border-radius:'+chiprad+';font-size:'+chipfs+';padding:'+chippad+';cursor:pointer;">Feminisiert &times;</span>';
        h += '<span style="background:'+chipbg+';color:'+chiptx+';border-radius:'+chiprad+';font-size:'+chipfs+';padding:'+chippad+';cursor:pointer;">Indoor &times;</span>';
        h += '</div>';
        // Tabs
        h += '<div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:12px;">';
        h += '<span style="'+tabStyle+'background:'+tabg+';color:'+tatx+';border-color:'+tabg+';"><span class="fa '+iMain+'" style="margin-right:4px;"></span>Haupt</span>';
        h += '<span style="'+tabStyle+'background:'+tbg+';color:'+ttx+';"><span class="fa '+iGen+'" style="margin-right:4px;"></span>Genetik</span>';
        h += '<span style="'+tabStyle+'background:'+tbg+';color:'+ttx+';"><span class="fa '+iCult+'" style="margin-right:4px;"></span>Anbau</span>';
        h += '<span style="'+tabStyle+'background:'+tbg+';color:'+ttx+';"><span class="fa '+iTaste+'" style="margin-right:4px;"></span>Geschmack</span>';
        h += '<span style="'+tabStyle+'background:'+tbg+';color:'+ttx+';"><span class="fa '+iAdv+'" style="margin-right:4px;"></span>Erweitert</span>';
        h += '</div>';
        // Checkboxen
        h += '<div style="margin-bottom:12px;">';
        h += '<span style="display:inline-block;width:16px;height:16px;background:'+chkbg+';border-radius:3px;margin-right:6px;vertical-align:middle;"></span><span style="font-size:0.9rem;">Feminisiert (42)</span><br>';
        h += '<span style="display:inline-block;width:16px;height:16px;border:1px solid #dee2e6;border-radius:3px;margin-right:6px;vertical-align:middle;margin-top:4px;"></span><span style="font-size:0.9rem;">Autoflowering (28)</span>';
        h += '</div>';
        // Filter Tags
        h += '<div style="margin-bottom:8px;"><small style="color:#999;">Product Card Filter Tags:</small><br>';
        h += '<span style="display:inline-block;background:'+ftbg+';color:'+fttx+';border-radius:'+ftrad+';font-size:'+ftfs+';padding:'+ftpad+';margin:2px;">Indoor</span>';
        h += '<span style="display:inline-block;background:'+ftbg+';color:'+fttx+';border-radius:'+ftrad+';font-size:'+ftfs+';padding:'+ftpad+';margin:2px;">Feminisiert</span>';
        h += '<span style="display:inline-block;background:'+ftbg+';color:'+fttx+';border-radius:'+ftrad+';font-size:'+ftfs+';padding:'+ftpad+';margin:2px;">THC: Hoch</span>';
        h += '</div>';
        h += '</div>';
        // Modal Footer
        h += '<div style="background:'+fbg+';border-top:1px solid '+fbd+';padding:10px 20px;border-radius:0 0 '+rad+' '+rad+';display:flex;gap:8px;">';
        h += '<span style="background:'+rbg+';color:'+rtx+';border:1px solid '+rbd+';padding:6px 14px;border-radius:4px;font-size:0.85rem;cursor:pointer;"><span class="fa '+iReset+'" style="margin-right:4px;"></span>Zur\u00fccksetzen</span>';
        h += '<span style="background:'+sbg+';color:'+stx+';border:none;padding:6px 14px;border-radius:4px;font-size:0.85rem;cursor:pointer;"><span class="fa '+iSearch+'" style="margin-right:4px;"></span>Suchen</span>';
        h += '<span style="background:'+cbg+';color:'+ctx+';border:none;padding:6px 14px;border-radius:4px;font-size:0.85rem;cursor:pointer;">Schlie\u00dfen</span>';
        h += '</div>';
        p.innerHTML = h;
    }
    // Kombinierte Render-Funktion
    function renderAllSfPreviews(){ renderSfModalPreview(); renderSfPagePreview(); }

    // 1. Standard-Events (input/change) fuer manuelle Eingaben
    document.querySelectorAll('#tab-seedfinder-seite input, #tab-seedfinder-seite select').forEach(function(el){
        el.addEventListener('input',renderAllSfPreviews);
        el.addEventListener('change',renderAllSfPreviews);
    });

    // 2. MutationObserver fuer Colorpicker (Spectrum/Pickr setzen value-Attribut per JS)
    var sfPane = document.getElementById('tab-seedfinder');
    if (sfPane && typeof MutationObserver !== 'undefined') {
        var sfObserver = new MutationObserver(function(mutations) {
            var needsRender = false;
            mutations.forEach(function(m) {
                if (m.type === 'attributes' && m.attributeName === 'value') {
                    needsRender = true;
                }
            });
            if (needsRender) renderAllSfPreviews();
        });
        sfObserver.observe(sfPane, { attributes: true, attributeFilter: ['value'], subtree: true });
    }

    // 3. Polling als Fallback (Colorpicker die nur .value Property setzen, nicht das Attribut)
    var _sfPollTimer = null;
    function sfStartPolling() {
        if (_sfPollTimer) return;
        _sfPollTimer = setInterval(function() {
            if (sfHasChanged()) renderAllSfPreviews();
        }, 250);
    }
    function sfStopPolling() {
        if (_sfPollTimer) { clearInterval(_sfPollTimer); _sfPollTimer = null; }
    }

    // 4. Tab-Wechsel: Polling starten/stoppen + Preview rendern
    var sfTab = document.querySelector('[data-tab="seedfinder-modal"]');
    if (sfTab) {
        sfTab.addEventListener('click', function(){
            setTimeout(function(){
                renderAllSfPreviews();
                sfStartPolling();
            }, 150);
        });
    }
    // SF Seite Tab: auch Previews rendern + Polling starten
    var sfSeiteTab = document.querySelector('[data-tab="seedfinder-seite"]');
    if (sfSeiteTab) {
        sfSeiteTab.addEventListener('click', function(){
            setTimeout(function(){
                renderAllSfPreviews();
                sfStartPolling();
            }, 150);
        });
    }
    // Polling stoppen wenn anderer Tab geklickt wird
    document.querySelectorAll('#mrh-config-tabs .mrh-tab:not([data-tab="seedfinder-modal"]):not([data-tab="seedfinder-seite"])').forEach(function(tab){
        tab.addEventListener('click', sfStopPolling);
    });

    // ═══ Seedfinder Seite Live-Preview ═══
    function renderSfPagePreview() {
        var p = document.getElementById('mrh-sf-page-preview');
        if (!p) return;
        var cbg = gv('tpl-sf-page-card-bg','rgb(255, 255, 255)');
        var cbd = gv('tpl-sf-page-card-border','rgb(222, 226, 230)');
        var chbd = gv('tpl-sf-page-card-hover-border','rgb(93, 178, 51)');
        var crad = gv('tpl-sf-page-card-radius','12px');
        var csh = gv('tpl-sf-page-card-shadow','0 2px 8px rgba(0,0,0,0.08)');
        var bbg = gv('tpl-sf-page-btn-bg','rgb(93, 178, 51)');
        var btx = gv('tpl-sf-page-btn-text','rgb(255, 255, 255)');
        var brad = gv('tpl-sf-page-btn-radius','6px');
        var bgbg = gv('tpl-sf-page-badge-bg','rgb(93, 178, 51)');
        var bgtx = gv('tpl-sf-page-badge-text','rgb(255, 255, 255)');
        var ibg = gv('tpl-sf-page-icon-bg','rgb(248, 249, 250)');
        var icl = gv('tpl-sf-page-icon-color','rgb(93, 178, 51)');
        var infbg = gv('tpl-sf-page-info-bg','rgb(248, 255, 245)');
        var infbd = gv('tpl-sf-page-info-border','rgb(93, 178, 51)');
        var inftx = gv('tpl-sf-page-info-text','rgb(33, 37, 41)');
        var bencl = gv('tpl-sf-page-benefit-color','rgb(93, 178, 51)');
        var stbg = gv('tpl-sf-page-step-bg','rgb(93, 178, 51)');
        var sttx = gv('tpl-sf-page-step-text','rgb(255, 255, 255)');
        var wbg = gv('tpl-sf-page-wizard-header-bg','rgb(93, 178, 51)');
        var wtx = gv('tpl-sf-page-wizard-header-text','rgb(255, 255, 255)');
        var wbbg = gv('tpl-sf-page-wizard-btn-bg','rgb(93, 178, 51)');
        var wbtx = gv('tpl-sf-page-wizard-btn-text','rgb(255, 255, 255)');
        var wpbg = gv('tpl-sf-page-wizard-progress-bg','rgb(93, 178, 51)');

        // Finder-Cards Variablen
        var fbg = gv('tpl-sf-page-finder-bg','rgb(255, 255, 255)');
        var frad = gv('tpl-sf-page-finder-radius','16px');
        var fsh = gv('tpl-sf-page-finder-shadow','0 4px 20px rgba(0,0,0,0.1)');
        var fhbg = gv('tpl-sf-page-finder-header-bg','rgb(93, 178, 51)');
        var fhbg2 = gv('tpl-sf-page-finder-header-bg2','rgb(109, 144, 44)');
        var fhtx = gv('tpl-sf-page-finder-header-text','rgb(255, 255, 255)');
        var phbg = gv('tpl-sf-page-profi-header-bg','rgb(23, 162, 184)');
        var phbg2 = gv('tpl-sf-page-profi-header-bg2','rgb(17, 122, 139)');
        // Benefit-Cards
        var bcbg = gv('tpl-sf-page-benefit-card-bg','rgb(248, 249, 250)');
        var bcbd = gv('tpl-sf-page-benefit-card-border','rgb(233, 236, 239)');
        var bcrad = gv('tpl-sf-page-benefit-card-radius','12px');
        var bibg = gv('tpl-sf-page-benefit-icon-bg','rgb(93, 178, 51)');
        var bibg2 = gv('tpl-sf-page-benefit-icon-bg2','rgb(109, 144, 44)');
        var bitx = gv('tpl-sf-page-benefit-icon-text','rgb(255, 255, 255)');
        var pibg = gv('tpl-sf-page-profi-icon-bg','rgb(23, 162, 184)');
        var pibg2 = gv('tpl-sf-page-profi-icon-bg2','rgb(17, 122, 139)');
        // Trust
        var trbg = gv('tpl-sf-page-trust-bg','rgb(240, 248, 240)');
        var trbd = gv('tpl-sf-page-trust-border','rgb(93, 178, 51)');
        var trst = gv('tpl-sf-page-trust-stars','rgb(255, 193, 7)');
        var trtx = gv('tpl-sf-page-trust-text','rgb(51, 51, 51)');
        var ptbg = gv('tpl-sf-page-profi-trust-bg','rgb(232, 244, 248)');
        var ptbd = gv('tpl-sf-page-profi-trust-border','rgb(23, 162, 184)');
        // CTA
        var ctbg = gv('tpl-sf-page-cta-bg','rgb(93, 178, 51)');
        var ctbg2 = gv('tpl-sf-page-cta-bg2','rgb(109, 144, 44)');
        var cttx = gv('tpl-sf-page-cta-text','rgb(255, 255, 255)');
        var ctrad = gv('tpl-sf-page-cta-radius','50px');
        var pctbg = gv('tpl-sf-page-profi-cta-bg','rgb(23, 162, 184)');
        var pctbg2 = gv('tpl-sf-page-profi-cta-bg2','rgb(17, 122, 139)');
        // Guarantee
        var gutx = gv('tpl-sf-page-guarantee-text','rgb(136, 136, 136)');
        var guic = gv('tpl-sf-page-guarantee-icon','rgb(109, 144, 44)');
        // Hersteller
        var mhbg = gv('tpl-sf-page-mfr-header-bg','rgb(248, 249, 250)');
        var mbd = gv('tpl-sf-page-mfr-border','rgb(222, 226, 230)');
        var mbbd = gv('tpl-sf-page-mfr-btn-border','rgb(93, 178, 51)');
        var mbtx = gv('tpl-sf-page-mfr-btn-text','rgb(93, 178, 51)');
        var mbhbg = gv('tpl-sf-page-mfr-btn-hover-bg','rgb(93, 178, 51)');
        var mbhtx = gv('tpl-sf-page-mfr-btn-hover-text','rgb(255, 255, 255)');
        var mpc = gv('tpl-sf-page-mfr-price-color','rgb(93, 178, 51)');

        var h = '';

        // ===== Finder-Cards (Anf\u00e4nger + Profi) =====
        h += '<div style="display:flex;gap:12px;margin-bottom:16px;">';
        // Anf\u00e4nger Card
        h += '<div style="flex:1;background:'+fbg+';border-radius:'+frad+';box-shadow:'+fsh+';overflow:hidden;display:flex;flex-direction:column;">';
        h += '<div style="background:linear-gradient(135deg,'+fhbg+' 0%,'+fhbg2+' 100%);color:'+fhtx+';padding:16px 12px;text-align:center;">';
        h += '<span style="background:rgba(255,255,255,0.25);color:'+fhtx+';padding:3px 10px;border-radius:10px;font-size:0.6rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Anf\u00e4nger</span>';
        h += '<div style="font-size:0.95rem;font-weight:700;margin-top:6px;">Beginner Finder</div>';
        h += '<div style="font-size:0.7rem;opacity:0.9;margin-top:2px;">Perfekt f\u00fcr Einsteiger</div>';
        h += '</div>';
        h += '<div style="padding:12px;flex:1;display:flex;flex-direction:column;">';
        // Mini Benefits Grid
        h += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">';
        var bIcons = ['fa-shield-alt','fa-clock','fa-chart-line','fa-leaf'];
        for(var bi=0;bi<4;bi++){
            h += '<div style="background:linear-gradient(135deg,'+bcbg+' 0%,#fff 100%);border:1px solid '+bcbd+';border-radius:'+bcrad+';padding:8px;text-align:center;">';
            h += '<div style="width:24px;height:24px;background:linear-gradient(135deg,'+bibg+' 0%,'+bibg2+' 100%);color:'+bitx+';border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.55rem;margin-bottom:4px;"><span class="fa '+bIcons[bi]+'"></span></div>';
            h += '<div style="font-size:0.55rem;font-weight:600;">Vorteil '+(bi+1)+'</div>';
            h += '</div>';
        }
        h += '</div>';
        // Trust
        h += '<div style="background:linear-gradient(135deg,'+trbg+' 0%,#fff 100%);border:1px solid '+trbd+';border-radius:6px;padding:6px;text-align:center;margin-bottom:8px;">';
        h += '<div style="color:'+trst+';font-size:0.65rem;"><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span></div>';
        h += '<div style="font-size:0.6rem;font-weight:600;color:'+trtx+';margin-top:2px;">Kundenbewertung</div>';
        h += '</div>';
        // CTA
        h += '<div style="background:linear-gradient(135deg,'+ctbg+' 0%,'+ctbg2+' 100%);color:'+cttx+';border-radius:'+ctrad+';padding:8px;text-align:center;font-size:0.75rem;font-weight:600;cursor:pointer;">Jetzt starten <span class="fa fa-arrow-right" style="margin-left:4px;"></span></div>';
        // Guarantee
        h += '<div style="text-align:center;margin-top:6px;font-size:0.55rem;color:'+gutx+';"><span class="fa fa-check-circle" style="color:'+guic+';margin-right:3px;"></span>100% Zufriedenheit</div>';
        h += '</div></div>';

        // Profi Card
        h += '<div style="flex:1;background:'+fbg+';border-radius:'+frad+';box-shadow:'+fsh+';overflow:hidden;display:flex;flex-direction:column;">';
        h += '<div style="background:linear-gradient(135deg,'+phbg+' 0%,'+phbg2+' 100%);color:'+fhtx+';padding:16px 12px;text-align:center;">';
        h += '<span style="background:rgba(255,255,255,0.25);color:'+fhtx+';padding:3px 10px;border-radius:10px;font-size:0.6rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;">Profi</span>';
        h += '<div style="font-size:0.95rem;font-weight:700;margin-top:6px;">Pro Finder</div>';
        h += '<div style="font-size:0.7rem;opacity:0.9;margin-top:2px;">F\u00fcr erfahrene Grower</div>';
        h += '</div>';
        h += '<div style="padding:12px;flex:1;display:flex;flex-direction:column;">';
        // Mini Benefits Grid (Profi)
        h += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">';
        var pIcons = ['fa-filter','fa-th-large','fa-star','fa-chart-bar'];
        for(var pi=0;pi<4;pi++){
            h += '<div style="background:linear-gradient(135deg,'+bcbg+' 0%,#fff 100%);border:1px solid '+bcbd+';border-radius:'+bcrad+';padding:8px;text-align:center;">';
            h += '<div style="width:24px;height:24px;background:linear-gradient(135deg,'+pibg+' 0%,'+pibg2+' 100%);color:'+bitx+';border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.55rem;margin-bottom:4px;"><span class="fa '+pIcons[pi]+'"></span></div>';
            h += '<div style="font-size:0.55rem;font-weight:600;">Feature '+(pi+1)+'</div>';
            h += '</div>';
        }
        h += '</div>';
        // Trust (Profi)
        h += '<div style="background:linear-gradient(135deg,'+ptbg+' 0%,#fff 100%);border:1px solid '+ptbd+';border-radius:6px;padding:6px;text-align:center;margin-bottom:8px;">';
        h += '<div style="color:'+trst+';font-size:0.65rem;"><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span></div>';
        h += '<div style="font-size:0.6rem;font-weight:600;color:'+trtx+';margin-top:2px;">Profi-Bewertung</div>';
        h += '</div>';
        // CTA (Profi)
        h += '<div style="background:linear-gradient(135deg,'+pctbg+' 0%,'+pctbg2+' 100%);color:'+cttx+';border-radius:'+ctrad+';padding:8px;text-align:center;font-size:0.75rem;font-weight:600;cursor:pointer;">Profi-Suche <span class="fa fa-arrow-right" style="margin-left:4px;"></span></div>';
        // Guarantee (Profi)
        h += '<div style="text-align:center;margin-top:6px;font-size:0.55rem;color:'+gutx+';"><span class="fa fa-info-circle" style="color:'+phbg+';margin-right:3px;"></span>Erweiterte Filter</div>';
        h += '</div></div>';
        h += '</div>';

        // ===== Kategorie-Karten =====
        h += '<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;">';
        // 3 Kategorie-Karten
        var cats = [{icon:'fa-cannabis',name:'Feminisiert',count:'1.250'},{icon:'fa-bolt',name:'Autoflowering',count:'890'},{icon:'fa-sun',name:'Outdoor',count:'320'}];
        for(var i=0;i<cats.length;i++){
            var isHover = (i===1);
            h += '<div style="flex:1;min-width:140px;background:'+cbg+';border:2px solid '+(isHover?chbd:cbd)+';border-radius:'+crad+';box-shadow:'+csh+';padding:16px;text-align:center;">';
            h += '<div style="width:48px;height:48px;border-radius:50%;background:'+ibg+';display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;"><span class="fa '+cats[i].icon+'" style="font-size:1.2rem;color:'+icl+';"></span></div>';
            h += '<div style="font-weight:600;font-size:0.95rem;">'+cats[i].name+'</div>';
            h += '<span style="display:inline-block;background:'+bgbg+';color:'+bgtx+';font-size:0.7rem;padding:2px 8px;border-radius:10px;margin:6px 0;">'+cats[i].count+' Produkte</span>';
            h += '<div><span style="display:inline-block;background:'+bbg+';color:'+btx+';padding:6px 16px;border-radius:'+brad+';font-size:0.8rem;cursor:pointer;margin-top:4px;">Ausw\u00e4hlen</span></div>';
            h += '</div>';
        }
        h += '</div>';

        // Info-Card
        h += '<div style="background:'+infbg+';border-left:4px solid '+infbd+';padding:12px 16px;border-radius:6px;margin-bottom:12px;color:'+inftx+';font-size:0.85rem;">';
        h += '<span class="fa fa-circle-info" style="color:'+infbd+';margin-right:8px;"></span>So funktioniert der Mr. Hanf Seedfinder';
        h += '</div>';

        // Benefits + Steps
        h += '<div style="display:flex;gap:16px;margin-bottom:12px;">';
        var steps = ['Kategorie w\u00e4hlen','Filter setzen','Ergebnisse erhalten'];
        for(var s=0;s<steps.length;s++){
            h += '<div style="flex:1;text-align:center;">';
            h += '<span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:'+stbg+';color:'+sttx+';font-size:0.75rem;font-weight:700;">'+(s+1)+'</span>';
            h += '<div style="font-size:0.78rem;margin-top:4px;"><span class="fa fa-check-circle" style="color:'+bencl+';margin-right:4px;"></span>'+steps[s]+'</div>';
            h += '</div>';
        }
        h += '</div>';

        // Wizard Mini-Preview
        h += '<div style="border-radius:8px;overflow:hidden;border:1px solid #dee2e6;">';
        h += '<div style="background:'+wbg+';color:'+wtx+';padding:10px 16px;font-weight:600;font-size:0.85rem;"><span class="fa fa-magic" style="margin-right:6px;"></span>Anf\u00e4nger-Finder</div>';
        h += '<div style="padding:12px 16px;">';
        h += '<div style="height:6px;background:#e9ecef;border-radius:3px;margin-bottom:10px;"><div style="width:60%;height:100%;background:'+wpbg+';border-radius:3px;"></div></div>';
        h += '<span style="display:inline-block;background:'+wbbg+';color:'+wbtx+';padding:6px 14px;border-radius:4px;font-size:0.8rem;cursor:pointer;">Weiter</span>';
        h += '</div></div>';

        // ===== Hersteller-Cards =====
        h += '<div style="display:flex;gap:10px;margin-top:12px;">';
        var mfrs = ['Royal Queen Seeds','Dutch Passion','Barney\'s Farm'];
        for(var mi=0;mi<3;mi++){
            var isH = (mi===1);
            h += '<div style="flex:1;border:1px solid '+mbd+';border-radius:8px;overflow:hidden;">';
            h += '<div style="background:'+mhbg+';padding:8px;text-align:center;font-size:0.7rem;font-weight:600;">'+mfrs[mi]+'</div>';
            h += '<div style="padding:8px;text-align:center;">';
            h += '<div style="font-size:0.6rem;color:#666;margin-bottom:4px;">ab <span style="color:'+mpc+';font-weight:700;">8,90 \u20ac</span></div>';
            h += '<span style="display:inline-block;border:1px solid '+(isH?mbhbg:mbbd)+';color:'+(isH?mbhtx:mbtx)+';background:'+(isH?mbhbg:'transparent')+';padding:4px 10px;border-radius:4px;font-size:0.6rem;cursor:pointer;">Ansehen</span>';
            h += '</div></div>';
        }
        h += '</div>';

        p.innerHTML = h;
    }

    // 5. Globale Funktion fuer externen Aufruf (z.B. vom Colorpicker-Callback)
    window.mrhRenderSfPreview = function(){ renderSfModalPreview(); renderSfPagePreview(); };

    // Initial rendern + Snapshot
    sfTakeSnapshot();
    renderSfModalPreview();
    renderSfPagePreview();
})();
</script>



</div><!-- /#tab-seedfinder-seite -->

<div class="mrh-tab-pane" id="tab-blog">
<h5 class="mb-3"><i class="fa fa-newspaper me-2"></i>Blog</h5>
<p class="text-muted small mb-3">Steuert das Aussehen der Blog-Hauptseite, Kategorie-Ansicht und Post-Einzelansicht (4-Spalten-Layout).</p>

<!-- Live-Vorschau -->
<div class="card mb-4">
    <div class="card-header"><strong>Live-Vorschau</strong></div>
    <div class="card-body" id="mrh-blog-preview" style="background:#f5f5f5;padding:20px;"></div>
</div>

<form method="post" action="">

<!-- ═══ Post-Cards ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-th-large me-1"></i>Post-Cards (Listing)</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-bg"><strong>Karten-Hintergrund</strong></label>
        <input id="tpl-blog-card-bg" type="text" name="tpl-blog-card-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-card-bg','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-card-bg','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-border"><strong>Karten-Rahmen</strong></label>
        <input id="tpl-blog-card-border" type="text" name="tpl-blog-card-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-card-border','rgb(222, 226, 230)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-card-border','rgb(222, 226, 230)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-radius"><strong>Rundung</strong></label>
        <input id="tpl-blog-card-radius" type="text" name="tpl-blog-card-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-card-radius','8px'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-shadow"><strong>Schatten</strong></label>
        <input id="tpl-blog-card-shadow" type="text" name="tpl-blog-card-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-card-shadow','0 2px 8px rgba(0,0,0,0.06)'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-hover-shadow"><strong>Hover-Schatten</strong></label>
        <input id="tpl-blog-card-hover-shadow" type="text" name="tpl-blog-card-hover-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-card-hover-shadow','0 4px 16px rgba(0,0,0,0.12)'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-img-height"><strong>Bild-H&ouml;he</strong></label>
        <input id="tpl-blog-card-img-height" type="text" name="tpl-blog-card-img-height" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-card-img-height','180px'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-title-color"><strong>Titel-Farbe</strong></label>
        <input id="tpl-blog-card-title-color" type="text" name="tpl-blog-card-title-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-card-title-color','rgb(51, 51, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-card-title-color','rgb(51, 51, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-title-hover"><strong>Titel Hover</strong></label>
        <input id="tpl-blog-card-title-hover" type="text" name="tpl-blog-card-title-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-card-title-hover','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-card-title-hover','rgb(93, 178, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-card-desc-color"><strong>Beschreibung</strong></label>
        <input id="tpl-blog-card-desc-color" type="text" name="tpl-blog-card-desc-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-card-desc-color','rgb(108, 117, 125)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-card-desc-color','rgb(108, 117, 125)'); ?>"></div>
    </div>
</div>

<!-- ═══ Badges ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-tag me-1"></i>Badges (Datum / Kategorie)</h6>
<div class="row">
    <div class="col-sm-3 mb-3">
        <label for="tpl-blog-badge-date-bg"><strong>Datum BG</strong></label>
        <input id="tpl-blog-badge-date-bg" type="text" name="tpl-blog-badge-date-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-badge-date-bg','rgb(248, 249, 250)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-badge-date-bg','rgb(248, 249, 250)'); ?>"></div>
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-blog-badge-date-text"><strong>Datum Text</strong></label>
        <input id="tpl-blog-badge-date-text" type="text" name="tpl-blog-badge-date-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-badge-date-text','rgb(108, 117, 125)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-badge-date-text','rgb(108, 117, 125)'); ?>"></div>
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-blog-badge-cat-bg"><strong>Kategorie BG</strong></label>
        <input id="tpl-blog-badge-cat-bg" type="text" name="tpl-blog-badge-cat-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-badge-cat-bg','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-badge-cat-bg','rgb(93, 178, 51)'); ?>"></div>
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-blog-badge-cat-text"><strong>Kategorie Text</strong></label>
        <input id="tpl-blog-badge-cat-text" type="text" name="tpl-blog-badge-cat-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-badge-cat-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-badge-cat-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
</div>

<!-- ═══ Weiterlesen-Button ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-arrow-right me-1"></i>Weiterlesen-Button</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-btn-more-bg"><strong>Button BG</strong></label>
        <input id="tpl-blog-btn-more-bg" type="text" name="tpl-blog-btn-more-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-btn-more-bg','rgba(0,0,0,0)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-btn-more-bg','rgba(0,0,0,0)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-btn-more-text"><strong>Button Text</strong></label>
        <input id="tpl-blog-btn-more-text" type="text" name="tpl-blog-btn-more-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-btn-more-text','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-btn-more-text','rgb(93, 178, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-btn-more-border"><strong>Button Rahmen</strong></label>
        <input id="tpl-blog-btn-more-border" type="text" name="tpl-blog-btn-more-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-btn-more-border','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-btn-more-border','rgb(93, 178, 51)'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-btn-more-hover-bg"><strong>Hover BG</strong></label>
        <input id="tpl-blog-btn-more-hover-bg" type="text" name="tpl-blog-btn-more-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-btn-more-hover-bg','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-btn-more-hover-bg','rgb(93, 178, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-btn-more-hover-text"><strong>Hover Text</strong></label>
        <input id="tpl-blog-btn-more-hover-text" type="text" name="tpl-blog-btn-more-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-btn-more-hover-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-btn-more-hover-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-sm-6 mb-3">
        <label for="tpl-blog-btn-more-label"><strong>Button-Text</strong></label>
        <input id="tpl-blog-btn-more-label" type="text" name="tpl-blog-btn-more-label" class="form-control" value="<?php echo htmlspecialchars(mrh_cv($c,'tpl-blog-btn-more-label','"Weiterlesen"')); ?>">
        <small class="text-muted">Text in Anführungszeichen, z.B. "Weiterlesen" oder "Mehr lesen"</small>
    </div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-blog-btn-more-icon"><strong>Button-Icon (FA Unicode)</strong></label>
        <input id="tpl-blog-btn-more-icon" type="text" name="tpl-blog-btn-more-icon" class="form-control" value="<?php echo htmlspecialchars(mrh_cv($c,'tpl-blog-btn-more-icon','"\\f061"')); ?>">
        <small class="text-muted">Font Awesome Unicode, z.B. "\f061" (Pfeil), "\f105" (Chevron), "\f054" (Winkel). Leer lassen für kein Icon.</small>
    </div>
</div>

<!-- ═══ Kategorie-Cards ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-folder me-1"></i>Kategorie-Cards</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-bg"><strong>Karten-Hintergrund</strong></label>
        <input id="tpl-blog-cat-bg" type="text" name="tpl-blog-cat-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-cat-bg','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-cat-bg','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-border"><strong>Rahmen</strong></label>
        <input id="tpl-blog-cat-border" type="text" name="tpl-blog-cat-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-cat-border','rgb(222, 226, 230)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-cat-border','rgb(222, 226, 230)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-radius"><strong>Rundung</strong></label>
        <input id="tpl-blog-cat-radius" type="text" name="tpl-blog-cat-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-cat-radius','8px'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-shadow"><strong>Schatten</strong></label>
        <input id="tpl-blog-cat-shadow" type="text" name="tpl-blog-cat-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-cat-shadow','0 2px 8px rgba(0,0,0,0.06)'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-hover-shadow"><strong>Hover-Schatten</strong></label>
        <input id="tpl-blog-cat-hover-shadow" type="text" name="tpl-blog-cat-hover-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-cat-hover-shadow','0 4px 16px rgba(0,0,0,0.12)'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-name-color"><strong>Name Farbe</strong></label>
        <input id="tpl-blog-cat-name-color" type="text" name="tpl-blog-cat-name-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-cat-name-color','rgb(68, 68, 68)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-cat-name-color','rgb(68, 68, 68)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-cat-name-hover"><strong>Name Hover</strong></label>
        <input id="tpl-blog-cat-name-hover" type="text" name="tpl-blog-cat-name-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-cat-name-hover','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-cat-name-hover','rgb(93, 178, 51)'); ?>"></div>
    </div>
</div>

<!-- ═══ Post-Einzelansicht ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-file-text me-1"></i>Post-Einzelansicht</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-title-color"><strong>Titel-Farbe</strong></label>
        <input id="tpl-blog-post-title-color" type="text" name="tpl-blog-post-title-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-title-color','rgb(33, 37, 41)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-title-color','rgb(33, 37, 41)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-meta-color"><strong>Meta-Farbe</strong></label>
        <input id="tpl-blog-post-meta-color" type="text" name="tpl-blog-post-meta-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-meta-color','rgb(108, 117, 125)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-meta-color','rgb(108, 117, 125)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-meta-link-color"><strong>Meta-Link</strong></label>
        <input id="tpl-blog-post-meta-link-color" type="text" name="tpl-blog-post-meta-link-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-meta-link-color','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-meta-link-color','rgb(93, 178, 51)'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-img-radius"><strong>Bild-Rundung</strong></label>
        <input id="tpl-blog-post-img-radius" type="text" name="tpl-blog-post-img-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-blog-post-img-radius','8px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-content-color"><strong>Inhalt-Farbe</strong></label>
        <input id="tpl-blog-post-content-color" type="text" name="tpl-blog-post-content-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-content-color','rgb(51, 51, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-content-color','rgb(51, 51, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-h2-color"><strong>&Uuml;berschriften (H2/H3)</strong></label>
        <input id="tpl-blog-post-h2-color" type="text" name="tpl-blog-post-h2-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-h2-color','rgb(33, 37, 41)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-h2-color','rgb(33, 37, 41)'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-blog-post-link-color"><strong>Link-Farbe</strong></label>
        <input id="tpl-blog-post-link-color" type="text" name="tpl-blog-post-link-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-blog-post-link-color','rgb(93, 178, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-blog-post-link-color','rgb(93, 178, 51)'); ?>"></div>
    </div>
</div>

<div class="text-end mt-3">
    <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Blog speichern">
</div>
</form>

<!-- Blog Live-Preview Script -->
<script>
(function(){
    'use strict';
    function gv(n,fb){var e=document.getElementById(n);return e?(e.value||fb):fb;}

    function renderBlogPreview(){
        var p = document.getElementById('mrh-blog-preview');
        if(!p) return;
        var cardBg     = gv('tpl-blog-card-bg','rgb(255,255,255)');
        var cardBorder = gv('tpl-blog-card-border','rgb(222,226,230)');
        var cardRadius = gv('tpl-blog-card-radius','8px');
        var cardShadow = gv('tpl-blog-card-shadow','0 2px 8px rgba(0,0,0,0.06)');
        var imgH       = gv('tpl-blog-card-img-height','180px');
        var titleC     = gv('tpl-blog-card-title-color','rgb(51,51,51)');
        var titleH     = gv('tpl-blog-card-title-hover','rgb(93,178,51)');
        var descC      = gv('tpl-blog-card-desc-color','rgb(108,117,125)');
        var dateBg     = gv('tpl-blog-badge-date-bg','rgb(248,249,250)');
        var dateText   = gv('tpl-blog-badge-date-text','rgb(108,117,125)');
        var catBg      = gv('tpl-blog-badge-cat-bg','rgb(93,178,51)');
        var catText    = gv('tpl-blog-badge-cat-text','rgb(255,255,255)');
        var btnBg      = gv('tpl-blog-btn-more-bg','rgba(0,0,0,0)');
        var btnText    = gv('tpl-blog-btn-more-text','rgb(93,178,51)');
        var btnBorder  = gv('tpl-blog-btn-more-border','rgb(93,178,51)');
        var btnHBg     = gv('tpl-blog-btn-more-hover-bg','rgb(93,178,51)');
        var btnHTx     = gv('tpl-blog-btn-more-hover-text','rgb(255,255,255)');
        var btnLabel   = gv('tpl-blog-btn-more-label','"Weiterlesen"').replace(/"/g,'');
        var btnIcon    = gv('tpl-blog-btn-more-icon','"\\f061"').replace(/"/g,'');
        var catCardBg  = gv('tpl-blog-cat-bg','rgb(255,255,255)');
        var catCardBd  = gv('tpl-blog-cat-border','rgb(222,226,230)');
        var catCardRd  = gv('tpl-blog-cat-radius','8px');
        var catNameC   = gv('tpl-blog-cat-name-color','rgb(68,68,68)');
        var postTitleC = gv('tpl-blog-post-title-color','rgb(33,37,41)');
        var postMetaC  = gv('tpl-blog-post-meta-color','rgb(108,117,125)');
        var postMetaL  = gv('tpl-blog-post-meta-link-color','rgb(93,178,51)');
        var postImgR   = gv('tpl-blog-post-img-radius','8px');
        var postContC  = gv('tpl-blog-post-content-color','rgb(51,51,51)');

        var h = '';
        // Kategorie-Cards Vorschau
        h += '<div style="margin-bottom:16px;"><strong style="font-size:13px;color:#666;">Kategorie-Cards:</strong></div>';
        h += '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px;">';
        var cats = ['Anbau-Tipps','Sorten-Guide','News','Rezepte'];
        for(var i=0;i<4;i++){
            h += '<div style="background:'+catCardBg+';border:1px solid '+catCardBd+';border-radius:'+catCardRd+';overflow:hidden;text-align:center;">';
            h += '<div style="height:60px;background:linear-gradient(135deg,#e0e0e0,#c0c0c0);"></div>';
            h += '<div style="padding:8px;font-weight:bold;font-size:12px;color:'+catNameC+';">'+cats[i]+'</div>';
            h += '</div>';
        }
        h += '</div>';

        // Post-Cards Vorschau (4-spaltig)
        h += '<div style="margin-bottom:16px;"><strong style="font-size:13px;color:#666;">Post-Cards (4-spaltig):</strong></div>';
        h += '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px;">';
        var posts = ['Cannabis richtig gie&szlig;en','Top 10 Sorten 2026','Indoor vs Outdoor','Ernte-Tipps f&uuml;r Anf&auml;nger'];
        var descs = ['Alles &uuml;ber die richtige Bew&auml;sserung...','Die beliebtesten Sorten im &Uuml;berblick...','Vor- und Nachteile beider Methoden...','So erntest du zum perfekten Zeitpunkt...'];
        for(var j=0;j<4;j++){
            h += '<div style="background:'+cardBg+';border:1px solid '+cardBorder+';border-radius:'+cardRadius+';box-shadow:'+cardShadow+';overflow:hidden;">';
            h += '<div style="height:'+imgH+';max-height:80px;background:linear-gradient(135deg,#d4edda,#a8d5a2);display:flex;align-items:center;justify-content:center;"><i class="fa fa-image" style="font-size:20px;color:#6c757d;"></i></div>';
            h += '<div style="padding:8px;">';
            h += '<div style="display:flex;gap:4px;margin-bottom:4px;flex-wrap:wrap;">';
            h += '<span style="background:'+dateBg+';color:'+dateText+';font-size:9px;padding:1px 5px;border-radius:3px;"><i class="fa fa-calendar" style="margin-right:2px;"></i>15. Apr</span>';
            h += '<span style="background:'+catBg+';color:'+catText+';font-size:9px;padding:1px 5px;border-radius:3px;"><i class="fa fa-folder" style="margin-right:2px;"></i>Tipps</span>';
            h += '</div>';
            h += '<div style="font-weight:bold;font-size:11px;color:'+titleC+';margin-bottom:3px;">'+posts[j]+'</div>';
            h += '<div style="font-size:10px;color:'+descC+';margin-bottom:6px;">'+descs[j]+'</div>';
            var iconHtml = btnIcon ? '<i class="fa" style="margin-right:3px;">&#x'+btnIcon.replace('\\f','f').replace('\f','f')+';</i>' : '';
            h += '<a href="#" onclick="return false" style="display:inline-block;background:'+btnBg+';color:'+btnText+';border:1px solid '+btnBorder+';font-size:9px;padding:2px 8px;border-radius:3px;text-decoration:none;">'+iconHtml+btnLabel+'</a>';
            h += '</div></div>';
        }
        h += '</div>';

        // Post-Einzelansicht Vorschau
        h += '<div style="margin-bottom:16px;"><strong style="font-size:13px;color:#666;">Post-Einzelansicht:</strong></div>';
        h += '<div style="background:#fff;border:1px solid #dee2e6;border-radius:8px;padding:16px;">';
        h += '<div style="height:80px;background:linear-gradient(135deg,#d4edda,#a8d5a2);border-radius:'+postImgR+';margin-bottom:12px;display:flex;align-items:center;justify-content:center;"><i class="fa fa-image" style="font-size:24px;color:#6c757d;"></i></div>';
        h += '<h3 style="color:'+postTitleC+';margin:0 0 8px;font-size:16px;">Cannabis richtig gie&szlig;en: Der ultimative Guide</h3>';
        h += '<div style="color:'+postMetaC+';font-size:11px;margin-bottom:12px;"><i class="fa fa-calendar" style="margin-right:4px;"></i>15. April 2026 &nbsp; <a href="#" onclick="return false" style="color:'+postMetaL+';text-decoration:none;"><i class="fa fa-folder" style="margin-right:4px;"></i>Anbau-Tipps</a> &nbsp; <i class="fa fa-user" style="margin-right:4px;"></i>Mr. Hanf</div>';
        h += '<p style="color:'+postContC+';font-size:12px;line-height:1.6;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...</p>';
        h += '</div>';

        p.innerHTML = h;
    }

    // Event-Listener fuer alle Blog-Felder
    document.querySelectorAll('[id^="tpl-blog-"]').forEach(function(el){
        el.addEventListener('input', renderBlogPreview);
        el.addEventListener('change', renderBlogPreview);
    });
    // Initial rendern wenn Tab sichtbar
    var blogTab = document.querySelector('[data-tab="blog"]');
    if(blogTab){
        blogTab.addEventListener('click', function(){ setTimeout(renderBlogPreview, 50); });
    }
    renderBlogPreview();
})();
</script>

</div><!-- /#tab-blog -->

<div class="mrh-tab-pane" id="tab-faq">
<h5 class="mb-3"><i class="fa fa-circle-question me-2"></i>FAQ Accordion</h5>
<p class="text-muted small mb-3">Steuert das Aussehen des FAQ-Akkordeons (Kategorie-Header, Frage-Cards, Chevron, Antwort-Body). Alle Werte werden als CSS-Variablen <code>--tpl-faq-*</code> gesetzt.</p>

<!-- Live-Vorschau -->
<div class="card mb-4">
    <div class="card-header"><strong>Live-Vorschau</strong></div>
    <div class="card-body" id="mrh-faq-preview" style="background:#f5f5f5;padding:20px;"></div>
</div>

<form method="post" action="">

<!-- ═══ Kategorie-Header ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-folder-open me-1"></i>Kategorie-Header</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-header-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-faq-header-bg" type="text" name="tpl-faq-header-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-header-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-header-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-header-gradient"><strong>Gradient-Ende</strong></label>
        <input id="tpl-faq-header-gradient" type="text" name="tpl-faq-header-gradient" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-header-gradient'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-header-gradient'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-header-text"><strong>Text-Farbe</strong></label>
        <input id="tpl-faq-header-text" type="text" name="tpl-faq-header-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-header-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-header-text'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-header-radius"><strong>Rundung</strong></label>
        <input id="tpl-faq-header-radius" type="text" name="tpl-faq-header-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-faq-header-radius'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-subheader-bg"><strong>Sub-Header BG</strong></label>
        <input id="tpl-faq-subheader-bg" type="text" name="tpl-faq-subheader-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-subheader-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-subheader-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-subheader-gradient"><strong>Sub-Header Gradient</strong></label>
        <input id="tpl-faq-subheader-gradient" type="text" name="tpl-faq-subheader-gradient" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-subheader-gradient'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-subheader-gradient'); ?>"></div>
    </div>
</div>

<!-- ═══ FAQ Cards ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-square me-1"></i>FAQ Cards</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-card-bg"><strong>Karten-Hintergrund</strong></label>
        <input id="tpl-faq-card-bg" type="text" name="tpl-faq-card-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-card-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-card-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-card-border"><strong>Karten-Rahmen</strong></label>
        <input id="tpl-faq-card-border" type="text" name="tpl-faq-card-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-card-border'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-card-border'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-card-radius"><strong>Rundung</strong></label>
        <input id="tpl-faq-card-radius" type="text" name="tpl-faq-card-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-faq-card-radius'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-accent"><strong>Akzent-Farbe (Hover)</strong></label>
        <input id="tpl-faq-accent" type="text" name="tpl-faq-accent" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-accent'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-accent'); ?>"></div>
    </div>
</div>

<!-- ═══ Frage-Button ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-hand-pointer me-1"></i>Frage-Button</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-faq-btn-bg" type="text" name="tpl-faq-btn-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-text"><strong>Text-Farbe</strong></label>
        <input id="tpl-faq-btn-text" type="text" name="tpl-faq-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-text'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-hover-bg"><strong>Hover BG</strong></label>
        <input id="tpl-faq-btn-hover-bg" type="text" name="tpl-faq-btn-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-hover-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-hover-bg'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-hover-text"><strong>Hover Text</strong></label>
        <input id="tpl-faq-btn-hover-text" type="text" name="tpl-faq-btn-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-hover-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-hover-text'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-active-bg"><strong>Aktiv BG</strong></label>
        <input id="tpl-faq-btn-active-bg" type="text" name="tpl-faq-btn-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-active-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-active-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-active-text"><strong>Aktiv Text</strong></label>
        <input id="tpl-faq-btn-active-text" type="text" name="tpl-faq-btn-active-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-active-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-active-text'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-btn-active-hover"><strong>Aktiv Hover BG</strong></label>
        <input id="tpl-faq-btn-active-hover" type="text" name="tpl-faq-btn-active-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-btn-active-hover'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-btn-active-hover'); ?>"></div>
    </div>
</div>

<!-- ═══ Icon + Chevron ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-chevron-down me-1"></i>Icon &amp; Chevron</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-icon-color"><strong>Icon-Farbe</strong></label>
        <input id="tpl-faq-icon-color" type="text" name="tpl-faq-icon-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-icon-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-icon-color'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-icon-active"><strong>Icon Aktiv</strong></label>
        <input id="tpl-faq-icon-active" type="text" name="tpl-faq-icon-active" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-icon-active'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-icon-active'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-chevron-bg"><strong>Chevron BG</strong></label>
        <input id="tpl-faq-chevron-bg" type="text" name="tpl-faq-chevron-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-chevron-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-chevron-bg'); ?>"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-chevron-color"><strong>Chevron Farbe</strong></label>
        <input id="tpl-faq-chevron-color" type="text" name="tpl-faq-chevron-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-chevron-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-chevron-color'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-chevron-active-bg"><strong>Chevron Aktiv BG</strong></label>
        <input id="tpl-faq-chevron-active-bg" type="text" name="tpl-faq-chevron-active-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-chevron-active-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-chevron-active-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-chevron-active-color"><strong>Chevron Aktiv Farbe</strong></label>
        <input id="tpl-faq-chevron-active-color" type="text" name="tpl-faq-chevron-active-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-chevron-active-color'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-chevron-active-color'); ?>"></div>
    </div>
</div>

<!-- ═══ Antwort-Body ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-align-left me-1"></i>Antwort-Body</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-body-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-faq-body-bg" type="text" name="tpl-faq-body-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-body-bg'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-body-bg'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-body-border"><strong>Trennlinie</strong></label>
        <input id="tpl-faq-body-border" type="text" name="tpl-faq-body-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-body-border'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-body-border'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-body-text"><strong>Text-Farbe</strong></label>
        <input id="tpl-faq-body-text" type="text" name="tpl-faq-body-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-faq-body-text'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-faq-body-text'); ?>"></div>
    </div>
</div>

<!-- ═══ Grid-Layout ═══ -->
<h6 class="border-bottom pb-2 mb-3"><i class="fa fa-th-large me-1"></i>Grid-Layout</h6>
<div class="row">
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-grid-cols"><strong>Spalten (Desktop)</strong></label>
        <select id="tpl-faq-grid-cols" name="tpl-faq-grid-cols" class="form-control">
            <option value="1" <?php if(mrh_cv($c,'tpl-faq-grid-cols')=='1') echo 'selected'; ?>>1 Spalte</option>
            <option value="2" <?php if(mrh_cv($c,'tpl-faq-grid-cols')=='2' || mrh_cv($c,'tpl-faq-grid-cols')=='') echo 'selected'; ?>>2 Spalten</option>
            <option value="3" <?php if(mrh_cv($c,'tpl-faq-grid-cols')=='3') echo 'selected'; ?>>3 Spalten</option>
        </select>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-grid-gap"><strong>Abstand (Mobil)</strong></label>
        <input id="tpl-faq-grid-gap" type="text" name="tpl-faq-grid-gap" class="form-control" value="<?php echo mrh_cv($c,'tpl-faq-grid-gap'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-faq-grid-gap-md"><strong>Abstand (Desktop)</strong></label>
        <input id="tpl-faq-grid-gap-md" type="text" name="tpl-faq-grid-gap-md" class="form-control" value="<?php echo mrh_cv($c,'tpl-faq-grid-gap-md'); ?>">
    </div>
</div>

<div class="text-end mt-3">
    <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="FAQ speichern">
</div>
</form>

<!-- FAQ Live-Preview Script -->
<script>
(function(){
    'use strict';
    function gv(n,fb){var e=document.getElementById(n);return e?(e.value||fb):fb;}

    function renderFaqPreview(){
        var p = document.getElementById('mrh-faq-preview');
        if(!p) return;

        var hdrBg    = gv('tpl-faq-header-bg','#2C5530');
        var hdrGrad  = gv('tpl-faq-header-gradient','#3a7d40');
        var hdrText  = gv('tpl-faq-header-text','#ffffff');
        var hdrRad   = gv('tpl-faq-header-radius','8px');
        var cardBg   = gv('tpl-faq-card-bg','#ffffff');
        var cardBd   = gv('tpl-faq-card-border','#e1e5e9');
        var cardRad  = gv('tpl-faq-card-radius','8px');
        var accent   = gv('tpl-faq-accent','#28a745');
        var btnBg    = gv('tpl-faq-btn-bg','transparent');
        var btnText  = gv('tpl-faq-btn-text','#495057');
        var actBg    = gv('tpl-faq-btn-active-bg','#2C5530');
        var actText  = gv('tpl-faq-btn-active-text','#ffffff');
        var iconC    = gv('tpl-faq-icon-color','#28a745');
        var chevBg   = gv('tpl-faq-chevron-bg','#f0f0f0');
        var chevC    = gv('tpl-faq-chevron-color','#28a745');
        var chevABg  = gv('tpl-faq-chevron-active-bg','rgba(255,255,255,.2)');
        var chevAC   = gv('tpl-faq-chevron-active-color','#ffffff');
        var bodyBg   = gv('tpl-faq-body-bg','#ffffff');
        var bodyBd   = gv('tpl-faq-body-border','#e1e5e9');
        var bodyText = gv('tpl-faq-body-text','#495057');

        var h = '';

        // Kategorie-Header
        h += '<div style="background:linear-gradient(135deg,'+hdrBg+','+hdrGrad+');color:'+hdrText+';text-align:center;border-radius:'+hdrRad+';padding:14px 16px;margin-bottom:12px;font-size:1.15rem;font-weight:600;display:flex;align-items:center;justify-content:center;gap:10px;">';
        h += '<i class="fa-solid fa-folder-open"></i> Allgemeine Fragen';
        h += '</div>';

        // FAQ Grid (2 Spalten)
        h += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">';

        var questions = [
            {q: 'Wie funktioniert die Bestellung?', a: 'Einfach das gew&uuml;nschte Produkt ausw&auml;hlen, in den Warenkorb legen und zur Kasse gehen. Wir akzeptieren verschiedene Zahlungsmethoden.', open: false},
            {q: 'Wie lange dauert der Versand?', a: '&Ouml;sterreich: 2-3 Werktage, Deutschland: 3-5 Werktage, EU: 5-7 Werktage. Express-Versand ist ebenfalls verf&uuml;gbar.', open: true},
            {q: 'Gibt es eine Keimgarantie?', a: 'Ja! Wir bieten eine Keimgarantie auf alle Samen. Bei Problemen kontaktiere unseren Support.', open: false},
            {q: 'Welche Zahlungsarten gibt es?', a: 'Vorkasse, Kreditkarte, Apple Pay, Google Pay, Lastschrift und Rechnung.', open: false}
        ];

        for(var i=0; i<questions.length; i++){
            var isOpen = questions[i].open;
            h += '<div style="border:1px solid '+(isOpen?accent:cardBd)+';border-radius:'+cardRad+';overflow:hidden;background:'+cardBg+';">';
            // Button
            h += '<div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:'+(isOpen?actBg:btnBg)+';color:'+(isOpen?actText:btnText)+';font-size:.9rem;font-weight:500;cursor:pointer;">';
            h += '<span style="display:flex;align-items:center;gap:6px;"><i class="fa-solid fa-circle-question" style="color:'+(isOpen?'rgba(255,255,255,.8)':iconC)+';flex-shrink:0;"></i>'+questions[i].q+'</span>';
            h += '<span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;min-width:28px;border-radius:50%;background:'+(isOpen?chevABg:chevBg)+';font-size:.7rem;color:'+(isOpen?chevAC:chevC)+';margin-left:8px;'+(isOpen?'transform:rotate(180deg);':'')+'">';
            h += '<i class="fa-solid fa-chevron-down"></i></span>';
            h += '</div>';
            // Body (nur bei offener Frage)
            if(isOpen){
                h += '<div style="padding:14px;border-top:1px solid '+bodyBd+';background:'+bodyBg+';color:'+bodyText+';font-size:.85rem;line-height:1.6;">'+questions[i].a+'</div>';
            }
            h += '</div>';
        }
        h += '</div>';

        p.innerHTML = h;
    }

    // Event-Listener fuer alle FAQ-Felder
    document.querySelectorAll('[id^="tpl-faq-"]').forEach(function(el){
        el.addEventListener('input', renderFaqPreview);
        el.addEventListener('change', renderFaqPreview);
    });
    var faqTab = document.querySelector('[data-tab="faq"]');
    if(faqTab){
        faqTab.addEventListener('click', function(){ setTimeout(renderFaqPreview, 50); });
    }
    renderFaqPreview();
})();
</script>

</div><!-- /#tab-faq -->

<div class="mrh-tab-pane" id="tab-badges">
<form id="mrh-form-badges" class="row mx-0" method="post" action="">

    <!-- Live-Vorschau -->
    <div class="col-12"><div class="mrh-sh"><i class="fa fa-eye me-1"></i> Live-Vorschau</div></div>
    <div class="col-12 mb-3">
        <div id="mrh-badge-preview" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;padding:12px;background:#f8f9fa;border-radius:8px;"></div>
    </div>

    <!-- Allgemeine Badge-Einstellungen -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-sliders me-1"></i> Allgemeine Einstellungen</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-radius"><strong>Rundung</strong></label>
        <input id="tpl-badge-radius" type="text" name="tpl-badge-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-radius','50rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-badge-font-size" type="text" name="tpl-badge-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-font-size','0.8rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-font-weight"><strong>Schriftgewicht</strong></label>
        <select id="tpl-badge-font-weight" name="tpl-badge-font-weight" class="form-control">
            <?php $fw = $c['tpl-badge-font-weight'] ?? '600'; ?>
            <option value="400" <?php echo $fw==='400'?'selected':''; ?>>Normal (400)</option>
            <option value="500" <?php echo $fw==='500'?'selected':''; ?>>Medium (500)</option>
            <option value="600" <?php echo $fw==='600'?'selected':''; ?>>Semibold (600)</option>
            <option value="700" <?php echo $fw==='700'?'selected':''; ?>>Bold (700)</option>
        </select>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-padding"><strong>Padding (innen)</strong></label>
        <input id="tpl-badge-padding" type="text" name="tpl-badge-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-padding','0.25rem 0.7rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-bar-gap"><strong>Abstand (gap)</strong></label>
        <input id="tpl-badge-bar-gap" type="text" name="tpl-badge-bar-gap" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-bar-gap','0.4rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-border-width"><strong>Rahmenbreite</strong></label>
        <input id="tpl-badge-border-width" type="text" name="tpl-badge-border-width" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-border-width','0px'); ?>">
    </div>
    <div class="col-sm-6 mb-3">
        <label for="tpl-badge-border-color"><strong>Rahmenfarbe (global)</strong></label>
        <input id="tpl-badge-border-color" type="text" name="tpl-badge-border-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-border-color','transparent'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-border-color','transparent'); ?>"></div>
    </div>

    <!-- Responsive Größen -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-mobile me-1"></i> Responsive Gr&ouml;&szlig;en</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Desktop verwendet die Werte aus &laquo;Allgemeine Einstellungen&raquo; oben. Hier k&ouml;nnen Tablet- und Mobil-Werte &uuml;berschrieben werden.</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-badge-font-size-tablet"><strong>Schrift Tablet (&le;992px)</strong></label>
        <input id="tpl-badge-font-size-tablet" type="text" name="tpl-badge-font-size-tablet" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-font-size-tablet','0.75rem'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-badge-padding-tablet"><strong>Padding Tablet (&le;992px)</strong></label>
        <input id="tpl-badge-padding-tablet" type="text" name="tpl-badge-padding-tablet" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-padding-tablet','0.22rem 0.6rem'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-badge-font-size-mobile"><strong>Schrift Mobil (&le;576px)</strong></label>
        <input id="tpl-badge-font-size-mobile" type="text" name="tpl-badge-font-size-mobile" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-font-size-mobile','0.7rem'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-badge-padding-mobile"><strong>Padding Mobil (&le;576px)</strong></label>
        <input id="tpl-badge-padding-mobile" type="text" name="tpl-badge-padding-mobile" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-padding-mobile','0.2rem 0.5rem'); ?>">
    </div>

    <!-- Hover-Einstellungen -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-hand-pointer-o me-1"></i> Hover-Effekt</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-hover-enabled"><strong>Hover aktiviert</strong></label>
        <select id="tpl-badge-hover-enabled" name="tpl-badge-hover-enabled" class="form-control">
            <?php $he = $c['tpl-badge-hover-enabled'] ?? '1'; ?>
            <option value="1" <?php echo $he==='1'?'selected':''; ?>>Ja</option>
            <option value="0" <?php echo $he==='0'?'selected':''; ?>>Nein</option>
        </select>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-hover-transform"><strong>Hover-Transform</strong></label>
        <input id="tpl-badge-hover-transform" type="text" name="tpl-badge-hover-transform" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-hover-transform','translateY(-1px)'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-hover-shadow"><strong>Hover-Schatten</strong></label>
        <input id="tpl-badge-hover-shadow" type="text" name="tpl-badge-hover-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-badge-hover-shadow','0 2px 6px rgba(0,0,0,0.12)'); ?>">
    </div>

    <!-- Badge-Typen -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa-solid fa-venus me-1" style="color:#fc5b96;"></i> Feminisiert</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-fem-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-badge-fem-bg" type="text" name="tpl-badge-fem-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-fem-bg','rgb(252, 91, 150)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-fem-bg','rgb(252, 91, 150)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-fem-text"><strong>Textfarbe</strong></label>
        <input id="tpl-badge-fem-text" type="text" name="tpl-badge-fem-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-fem-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-fem-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-fem-border"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-badge-fem-border" type="text" name="tpl-badge-fem-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-fem-border','transparent'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-fem-border','transparent'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa-solid fa-mars me-1" style="color:#2ea2f0;"></i> Regul&auml;r</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-reg-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-badge-reg-bg" type="text" name="tpl-badge-reg-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-reg-bg','rgb(46, 162, 240)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-reg-bg','rgb(46, 162, 240)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-reg-text"><strong>Textfarbe</strong></label>
        <input id="tpl-badge-reg-text" type="text" name="tpl-badge-reg-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-reg-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-reg-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-reg-border"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-badge-reg-border" type="text" name="tpl-badge-reg-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-reg-border','transparent'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-reg-border','transparent'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-sun-o me-1" style="color:#6c757d;"></i> Photoperiodisch</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-photo-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-badge-photo-bg" type="text" name="tpl-badge-photo-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-photo-bg','rgb(108, 117, 125)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-photo-bg','rgb(108, 117, 125)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-photo-text"><strong>Textfarbe</strong></label>
        <input id="tpl-badge-photo-text" type="text" name="tpl-badge-photo-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-photo-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-photo-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-photo-border"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-badge-photo-border" type="text" name="tpl-badge-photo-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-photo-border','transparent'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-photo-border','transparent'); ?>"></div>
    </div>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-tachometer me-1" style="color:#15803d;"></i> Autoflowering (Picto-Container)</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-auto-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-badge-auto-bg" type="text" name="tpl-badge-auto-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-auto-bg','rgb(240, 253, 244)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-auto-bg','rgb(240, 253, 244)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-auto-text"><strong>Textfarbe</strong></label>
        <input id="tpl-badge-auto-text" type="text" name="tpl-badge-auto-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-auto-text','rgb(21, 128, 61)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-auto-text','rgb(21, 128, 61)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-badge-auto-border"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-badge-auto-border" type="text" name="tpl-badge-auto-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-badge-auto-border','rgba(34, 197, 94, 0.25)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-badge-auto-border','rgba(34, 197, 94, 0.25)'); ?>"></div>
    </div>

    <!-- Picto-Container (.picto.templatestyle) -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-cube me-1" style="color:#15803d;"></i> Picto-Container (Autoflowering-Box)</div></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-picto-bg" type="text" name="tpl-picto-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-picto-bg','rgb(240, 253, 244)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-picto-bg','rgb(240, 253, 244)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-text"><strong>Textfarbe</strong></label>
        <input id="tpl-picto-text" type="text" name="tpl-picto-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-picto-text','rgb(21, 128, 61)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-picto-text','rgb(21, 128, 61)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-border-color"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-picto-border-color" type="text" name="tpl-picto-border-color" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-picto-border-color','rgba(34, 197, 94, 0.25)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-picto-border-color','rgba(34, 197, 94, 0.25)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-border-width"><strong>Rahmenbreite</strong></label>
        <input id="tpl-picto-border-width" type="text" name="tpl-picto-border-width" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-border-width','1px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-border-radius"><strong>Rundung</strong></label>
        <input id="tpl-picto-border-radius" type="text" name="tpl-picto-border-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-border-radius','12px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-padding"><strong>Padding</strong></label>
        <input id="tpl-picto-padding" type="text" name="tpl-picto-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-padding','8px 16px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-margin-bottom"><strong>Abstand unten</strong></label>
        <input id="tpl-picto-margin-bottom" type="text" name="tpl-picto-margin-bottom" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-margin-bottom','0.75rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-shadow"><strong>Schatten</strong></label>
        <input id="tpl-picto-shadow" type="text" name="tpl-picto-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-shadow','none'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-opacity"><strong>Deckkraft</strong></label>
        <input id="tpl-picto-opacity" type="text" name="tpl-picto-opacity" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-opacity','1'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-picto-icon-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-picto-icon-size" type="text" name="tpl-picto-icon-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-picto-icon-size','1rem'); ?>">
    </div>

    <!-- ═══ Floating Vergleichs-Badge ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-balance-scale me-1" style="color:#4a8c2a;"></i> Floating Vergleichs-Badge</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Der schwebende Vergleichs-Button (unten rechts) mit Z&auml;hler</small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-compare-float-bg" type="text" name="tpl-compare-float-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-compare-float-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-compare-float-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-text"><strong>Icon-Farbe</strong></label>
        <input id="tpl-compare-float-text" type="text" name="tpl-compare-float-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-compare-float-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-compare-float-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-hover-bg"><strong>Hover-Hintergrund</strong></label>
        <input id="tpl-compare-float-hover-bg" type="text" name="tpl-compare-float-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-compare-float-hover-bg','rgb(56, 112, 32)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-compare-float-hover-bg','rgb(56, 112, 32)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-size"><strong>Gr&ouml;&szlig;e (Breite/H&ouml;he)</strong></label>
        <input id="tpl-compare-float-size" type="text" name="tpl-compare-float-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-size','56px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-font-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-compare-float-font-size" type="text" name="tpl-compare-float-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-font-size','1.4rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-radius"><strong>Rundung</strong></label>
        <input id="tpl-compare-float-radius" type="text" name="tpl-compare-float-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-radius','50%'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-shadow"><strong>Schatten</strong></label>
        <input id="tpl-compare-float-shadow" type="text" name="tpl-compare-float-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-shadow','0 4px 12px rgba(0,0,0,0.3)'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-count-bg"><strong>Z&auml;hler-Hintergrund</strong></label>
        <input id="tpl-compare-float-count-bg" type="text" name="tpl-compare-float-count-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-compare-float-count-bg','rgb(220, 53, 69)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-compare-float-count-bg','rgb(220, 53, 69)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-count-text"><strong>Z&auml;hler-Textfarbe</strong></label>
        <input id="tpl-compare-float-count-text" type="text" name="tpl-compare-float-count-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-compare-float-count-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-compare-float-count-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-count-size"><strong>Z&auml;hler-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-compare-float-count-size" type="text" name="tpl-compare-float-count-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-count-size','22px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-compare-float-count-font"><strong>Z&auml;hler-Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-compare-float-count-font" type="text" name="tpl-compare-float-count-font" class="form-control" value="<?php echo mrh_cv($c,'tpl-compare-float-count-font','0.75rem'); ?>">
    </div>

    <!-- Margin-Einstellungen fuer Floating Badge -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-arrows-alt me-1"></i> Abstand (Margin) &ndash; Abstand vom Bildschirmrand</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-margin-top"><strong>Oben</strong></label>
        <input id="tpl-compare-float-margin-top" type="text" name="tpl-compare-float-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-margin-top','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-margin-right"><strong>Rechts</strong></label>
        <input id="tpl-compare-float-margin-right" type="text" name="tpl-compare-float-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-margin-right','20px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-margin-bottom"><strong>Unten</strong></label>
        <input id="tpl-compare-float-margin-bottom" type="text" name="tpl-compare-float-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-margin-bottom','80px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-margin-left"><strong>Links</strong></label>
        <input id="tpl-compare-float-margin-left" type="text" name="tpl-compare-float-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-margin-left','auto'); ?>">
    </div>

    <!-- Mobile Margin -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-mobile-screen me-1"></i> Abstand (Margin) &ndash; Mobile (unter 768px)</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-margin-top"><strong>Oben (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-margin-top" type="text" name="tpl-compare-float-mob-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-margin-top','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-margin-right"><strong>Rechts (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-margin-right" type="text" name="tpl-compare-float-mob-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-margin-right','10px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-margin-bottom"><strong>Unten (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-margin-bottom" type="text" name="tpl-compare-float-mob-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-margin-bottom','65px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-margin-left"><strong>Links (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-margin-left" type="text" name="tpl-compare-float-mob-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-margin-left','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-size"><strong>Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-size" type="text" name="tpl-compare-float-mob-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-size','44px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-compare-float-mob-font-size"><strong>Icon-Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-compare-float-mob-font-size" type="text" name="tpl-compare-float-mob-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-compare-float-mob-font-size','1rem'); ?>">
    </div>

    <!-- ═══ Floating Seedfinder-Button ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-seedling me-1" style="color:#4a8c2a;"></i> Floating Seedfinder-Button</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Schwebendes Seedfinder-Icon (fixed positioniert, Link zu /Seedfinder/). Wird auf der Seedfinder-Seite selbst ausgeblendet.</small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-enabled"><strong>Anzeigen (Ein/Aus)</strong></label>
        <select id="tpl-sf-float-enabled" name="tpl-sf-float-enabled" class="form-control">
            <?php $sfe = $c['tpl-sf-float-enabled'] ?? '1'; ?>
            <option value="1" <?php echo $sfe==='1'?'selected':''; ?>>Ja (sichtbar)</option>
            <option value="0" <?php echo $sfe==='0'?'selected':''; ?>>Nein (ausgeblendet)</option>
        </select>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-sf-float-bg" type="text" name="tpl-sf-float-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-float-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-float-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-text"><strong>Icon-Farbe</strong></label>
        <input id="tpl-sf-float-text" type="text" name="tpl-sf-float-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-float-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-float-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-hover-bg"><strong>Hover-Hintergrund</strong></label>
        <input id="tpl-sf-float-hover-bg" type="text" name="tpl-sf-float-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-sf-float-hover-bg','rgb(56, 112, 32)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sf-float-hover-bg','rgb(56, 112, 32)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-size"><strong>Gr&ouml;&szlig;e (Breite/H&ouml;he)</strong></label>
        <input id="tpl-sf-float-size" type="text" name="tpl-sf-float-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-float-size','56px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-font-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-sf-float-font-size" type="text" name="tpl-sf-float-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-float-font-size','1.4rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-radius"><strong>Rundung</strong></label>
        <input id="tpl-sf-float-radius" type="text" name="tpl-sf-float-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-float-radius','50%'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-sf-float-shadow"><strong>Schatten</strong></label>
        <input id="tpl-sf-float-shadow" type="text" name="tpl-sf-float-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-sf-float-shadow','0 4px 12px rgba(0,0,0,0.3)'); ?>">
    </div>

    <!-- SF Float Margin Desktop -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-arrows-alt me-1"></i> Abstand (Margin) &ndash; Abstand vom Bildschirmrand (Desktop)</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-margin-top"><strong>Oben</strong></label>
        <input id="tpl-sf-float-margin-top" type="text" name="tpl-sf-float-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-margin-top','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-margin-right"><strong>Rechts</strong></label>
        <input id="tpl-sf-float-margin-right" type="text" name="tpl-sf-float-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-margin-right','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-margin-bottom"><strong>Unten</strong></label>
        <input id="tpl-sf-float-margin-bottom" type="text" name="tpl-sf-float-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-margin-bottom','80px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-margin-left"><strong>Links</strong></label>
        <input id="tpl-sf-float-margin-left" type="text" name="tpl-sf-float-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-margin-left','20px'); ?>">
    </div>

    <!-- SF Float Margin Mobile -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-mobile-screen me-1"></i> Abstand (Margin) &ndash; Mobile (unter 768px)</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-margin-top"><strong>Oben (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-margin-top" type="text" name="tpl-sf-float-mob-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-margin-top','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-margin-right"><strong>Rechts (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-margin-right" type="text" name="tpl-sf-float-mob-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-margin-right','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-margin-bottom"><strong>Unten (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-margin-bottom" type="text" name="tpl-sf-float-mob-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-margin-bottom','65px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-margin-left"><strong>Links (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-margin-left" type="text" name="tpl-sf-float-mob-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-margin-left','10px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-size"><strong>Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-size" type="text" name="tpl-sf-float-mob-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-size','44px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-sf-float-mob-font-size"><strong>Icon-Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-sf-float-mob-font-size" type="text" name="tpl-sf-float-mob-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-sf-float-mob-font-size','1rem'); ?>">
    </div>

    <!-- ═══ Floating Filter-Button (Seedfinder Mobile) ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-filter me-1" style="color:#4a8c2a;"></i> Floating Filter-Button (Seedfinder Mobile)</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Schwebender Filter-Button auf der Seedfinder-Seite (nur mobil sichtbar, &ouml;ffnet das Filter-Modal). Klasse: <code>.floating-filter-btn</code></small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-enabled"><strong>Anzeigen (Ein/Aus)</strong></label>
        <select id="tpl-ff-btn-enabled" name="tpl-ff-btn-enabled" class="form-control">
            <?php $ffe = $c['tpl-ff-btn-enabled'] ?? '1'; ?>
            <option value="1" <?php echo $ffe==='1'?'selected':''; ?>>Ja (sichtbar)</option>
            <option value="0" <?php echo $ffe==='0'?'selected':''; ?>>Nein (ausgeblendet)</option>
        </select>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-ff-btn-bg" type="text" name="tpl-ff-btn-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ff-btn-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ff-btn-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-text"><strong>Icon-Farbe</strong></label>
        <input id="tpl-ff-btn-text" type="text" name="tpl-ff-btn-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ff-btn-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ff-btn-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-hover-bg"><strong>Hover-Hintergrund</strong></label>
        <input id="tpl-ff-btn-hover-bg" type="text" name="tpl-ff-btn-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-ff-btn-hover-bg','rgb(56, 112, 32)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-ff-btn-hover-bg','rgb(56, 112, 32)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-size"><strong>Gr&ouml;&szlig;e (Breite/H&ouml;he)</strong></label>
        <input id="tpl-ff-btn-size" type="text" name="tpl-ff-btn-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-ff-btn-size','56px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-font-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-ff-btn-font-size" type="text" name="tpl-ff-btn-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-ff-btn-font-size','1.3rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-radius"><strong>Rundung</strong></label>
        <input id="tpl-ff-btn-radius" type="text" name="tpl-ff-btn-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-ff-btn-radius','50%'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-ff-btn-shadow"><strong>Schatten</strong></label>
        <input id="tpl-ff-btn-shadow" type="text" name="tpl-ff-btn-shadow" class="form-control" value="<?php echo mrh_cv($c,'tpl-ff-btn-shadow','0 4px 12px rgba(0,0,0,0.25)'); ?>">
    </div>

    <!-- FF Button Margin -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-arrows-alt me-1"></i> Abstand (Margin) &ndash; Position vom Bildschirmrand</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-ff-btn-margin-top"><strong>Oben</strong></label>
        <input id="tpl-ff-btn-margin-top" type="text" name="tpl-ff-btn-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ff-btn-margin-top','auto'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-ff-btn-margin-right"><strong>Rechts</strong></label>
        <input id="tpl-ff-btn-margin-right" type="text" name="tpl-ff-btn-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ff-btn-margin-right','20px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-ff-btn-margin-bottom"><strong>Unten</strong></label>
        <input id="tpl-ff-btn-margin-bottom" type="text" name="tpl-ff-btn-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ff-btn-margin-bottom','80px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-ff-btn-margin-left"><strong>Links</strong></label>
        <input id="tpl-ff-btn-margin-left" type="text" name="tpl-ff-btn-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-ff-btn-margin-left','auto'); ?>">
    </div>

    <!-- ═══ Cannabis Badge Pills (mrh-cbadge) ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-cannabis me-1" style="color:#4a8c2a;"></i> Cannabis Badge Pills (Vergleich/Seedfinder)</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Kleine Badges f&uuml;r Sortentyp-Icons (Autoflowering, Feminisiert etc.) in Vergleich &amp; Seedfinder</small></div>
    <!-- Basis-Badge (.mrh-cbadge) -->
    <div class="col-12"><small class="fw-bold text-secondary">Basis-Badge</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-cbadge-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-cbadge-font-size" type="text" name="tpl-cbadge-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-font-size','0.78rem'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-cbadge-font-weight"><strong>Schriftst&auml;rke</strong></label>
        <input id="tpl-cbadge-font-weight" type="text" name="tpl-cbadge-font-weight" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-font-weight','700'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-cbadge-padding"><strong>Padding</strong></label>
        <input id="tpl-cbadge-padding" type="text" name="tpl-cbadge-padding" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-padding','2px 8px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-cbadge-radius"><strong>Radius</strong></label>
        <input id="tpl-cbadge-radius" type="text" name="tpl-cbadge-radius" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-radius','4px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-cbadge-gap"><strong>Abstand (gap)</strong></label>
        <input id="tpl-cbadge-gap" type="text" name="tpl-cbadge-gap" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-gap','3px'); ?>">
    </div>
    <!-- Icon-Badge (.mrh-cbadge-icon) -->
    <div class="col-12 mt-2"><small class="fw-bold text-secondary">Icon-Badge (Autoflowering etc.)</small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-cbadge-icon-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-cbadge-icon-bg" type="text" name="tpl-cbadge-icon-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-cbadge-icon-bg','#f0f0f0'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-cbadge-icon-bg','#f0f0f0'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-cbadge-icon-text"><strong>Textfarbe</strong></label>
        <input id="tpl-cbadge-icon-text" type="text" name="tpl-cbadge-icon-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-cbadge-icon-text','#333333'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-cbadge-icon-text','#333333'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-cbadge-icon-font-size"><strong>Icon-Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-cbadge-icon-font-size" type="text" name="tpl-cbadge-icon-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-icon-font-size','0.85rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-cbadge-icon-padding"><strong>Icon-Padding</strong></label>
        <input id="tpl-cbadge-icon-padding" type="text" name="tpl-cbadge-icon-padding" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-icon-padding','2px 6px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-cbadge-icon-radius"><strong>Icon-Radius</strong></label>
        <input id="tpl-cbadge-icon-radius" type="text" name="tpl-cbadge-icon-radius" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-cbadge-icon-radius','4px'); ?>">
    </div>

    <!-- ═══ Seedfinder Bottom Bar Button ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-seedling me-1" style="color:#4a8c2a;"></i> Seedfinder Bottom-Bar Button</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Der hervorgehobene Seedfinder-Button in der mobilen Bottom Bar</small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-bb-sf-bg" type="text" name="tpl-bb-sf-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bb-sf-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bb-sf-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-icon"><strong>Icon-Farbe</strong></label>
        <input id="tpl-bb-sf-icon" type="text" name="tpl-bb-sf-icon" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bb-sf-icon','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bb-sf-icon','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-text"><strong>Textfarbe</strong></label>
        <input id="tpl-bb-sf-text" type="text" name="tpl-bb-sf-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bb-sf-text','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bb-sf-text','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-hover"><strong>Hover-Farbe</strong></label>
        <input id="tpl-bb-sf-hover" type="text" name="tpl-bb-sf-hover" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bb-sf-hover','rgb(56, 112, 32)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bb-sf-hover','rgb(56, 112, 32)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-size"><strong>Button-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-bb-sf-size" type="text" name="tpl-bb-sf-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-size','40px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-icon-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-bb-sf-icon-size" type="text" name="tpl-bb-sf-icon-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-icon-size','22px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-shadow"><strong>Schatten-Farbe</strong></label>
        <input id="tpl-bb-sf-shadow" type="text" name="tpl-bb-sf-shadow" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-bb-sf-shadow','rgba(74, 140, 42, 0.3)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-bb-sf-shadow','rgba(74, 140, 42, 0.3)'); ?>"></div>
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-margin-top"><strong>Margin oben</strong></label>
        <input id="tpl-bb-sf-margin-top" type="text" name="tpl-bb-sf-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-margin-top','-14px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-margin-right"><strong>Margin rechts</strong></label>
        <input id="tpl-bb-sf-margin-right" type="text" name="tpl-bb-sf-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-margin-right','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-margin-bottom"><strong>Margin unten</strong></label>
        <input id="tpl-bb-sf-margin-bottom" type="text" name="tpl-bb-sf-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-margin-bottom','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-margin-left"><strong>Margin links</strong></label>
        <input id="tpl-bb-sf-margin-left" type="text" name="tpl-bb-sf-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-margin-left','0'); ?>">
    </div>

    <!-- Bottom Bar Margins -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-arrows-alt me-1"></i> Bottom Bar &ndash; Abst&auml;nde (Padding/Margin)</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-padding-top"><strong>Padding oben</strong></label>
        <input id="tpl-bb-padding-top" type="text" name="tpl-bb-padding-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-padding-top','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-padding-bottom"><strong>Padding unten</strong></label>
        <input id="tpl-bb-padding-bottom" type="text" name="tpl-bb-padding-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-padding-bottom','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-padding-left"><strong>Padding links</strong></label>
        <input id="tpl-bb-padding-left" type="text" name="tpl-bb-padding-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-padding-left','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-padding-right"><strong>Padding rechts</strong></label>
        <input id="tpl-bb-padding-right" type="text" name="tpl-bb-padding-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-padding-right','0'); ?>">
    </div>

    <!-- Seedfinder Mobile Größen -->
    <div class="col-12"><hr class="my-2"><small class="text-muted"><i class="fa fa-mobile-screen me-1"></i> Seedfinder Button &ndash; Mobile (unter 480px)</small></div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-size"><strong>Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-size" type="text" name="tpl-bb-sf-mob-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-size','36px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-icon-size"><strong>Icon-Gr&ouml;&szlig;e (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-icon-size" type="text" name="tpl-bb-sf-mob-icon-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-icon-size','18px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-margin-top"><strong>Margin oben (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-margin-top" type="text" name="tpl-bb-sf-mob-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-margin-top','-10px'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-margin-right"><strong>Margin rechts (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-margin-right" type="text" name="tpl-bb-sf-mob-margin-right" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-margin-right','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-margin-bottom"><strong>Margin unten (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-margin-bottom" type="text" name="tpl-bb-sf-mob-margin-bottom" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-margin-bottom','0'); ?>">
    </div>
    <div class="col-sm-3 mb-3">
        <label for="tpl-bb-sf-mob-margin-left"><strong>Margin links (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-margin-left" type="text" name="tpl-bb-sf-mob-margin-left" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-margin-left','0'); ?>">
    </div>

    <!-- ═══ Filter-Tags (aktive Filter Chips) ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><span class="fa fa-tags me-1" style="color:#4a8c2a;"></span> Filter-Tags (aktive Filter Chips)</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Die aktiven Filter-Chips im Seedfinder und Produktlisting (z.B. &quot;Feminisiert &times;&quot;)</small></div>

    <!-- Live-Vorschau -->
    <div class="col-12 mb-3">
        <div id="mrh-filter-tag-preview" style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;padding:12px;background:#f8f9fa;border-radius:8px;"></div>
    </div>

    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-filter-tag-bg" type="text" name="tpl-filter-tag-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-bg','rgb(240, 240, 240)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-bg','rgb(240, 240, 240)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-text"><strong>Textfarbe</strong></label>
        <input id="tpl-filter-tag-text" type="text" name="tpl-filter-tag-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-text','rgb(51, 51, 51)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-text','rgb(51, 51, 51)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-border"><strong>Rahmenfarbe</strong></label>
        <input id="tpl-filter-tag-border" type="text" name="tpl-filter-tag-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-border','rgb(222, 226, 230)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-border','rgb(222, 226, 230)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-radius"><strong>Rundung</strong></label>
        <input id="tpl-filter-tag-radius" type="text" name="tpl-filter-tag-radius" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-filter-tag-radius','50rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-padding"><strong>Padding (innen)</strong></label>
        <input id="tpl-filter-tag-padding" type="text" name="tpl-filter-tag-padding" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-filter-tag-padding','3px 10px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-filter-tag-font-size" type="text" name="tpl-filter-tag-font-size" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-filter-tag-font-size','0.8rem'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-hover-bg"><strong>Hover-Hintergrund</strong></label>
        <input id="tpl-filter-tag-hover-bg" type="text" name="tpl-filter-tag-hover-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-hover-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-hover-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-hover-text"><strong>Hover-Textfarbe</strong></label>
        <input id="tpl-filter-tag-hover-text" type="text" name="tpl-filter-tag-hover-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-hover-text','rgb(255, 255, 255)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-hover-text','rgb(255, 255, 255)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-filter-tag-hover-border"><strong>Hover-Rahmenfarbe</strong></label>
        <input id="tpl-filter-tag-hover-border" type="text" name="tpl-filter-tag-hover-border" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-filter-tag-hover-border','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-filter-tag-hover-border','rgb(74, 140, 42)'); ?>"></div>
    </div>

    <!-- ═══ Versandkosten-Leiste ═══ -->
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-truck me-1" style="color:#be9e1f;"></i> Versandkosten-Leiste</div></div>
    <div class="col-12 mb-2"><small class="text-muted">Die Leiste &quot;Noch X EUR bis kostenloser Versand&quot; im Header</small></div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-bg"><strong>Hintergrund</strong></label>
        <input id="tpl-shipping-bar-bg" type="text" name="tpl-shipping-bar-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-shipping-bar-bg','rgb(255, 251, 235)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-shipping-bar-bg','rgb(255, 251, 235)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-text"><strong>Textfarbe</strong></label>
        <input id="tpl-shipping-bar-text" type="text" name="tpl-shipping-bar-text" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-shipping-bar-text','rgb(190, 158, 31)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-shipping-bar-text','rgb(190, 158, 31)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-amount"><strong>Betragsfarbe</strong></label>
        <input id="tpl-shipping-bar-amount" type="text" name="tpl-shipping-bar-amount" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-shipping-bar-amount','rgb(40, 167, 69)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-shipping-bar-amount','rgb(40, 167, 69)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-font-size"><strong>Schriftgr&ouml;&szlig;e</strong></label>
        <input id="tpl-shipping-bar-font-size" type="text" name="tpl-shipping-bar-font-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-font-size','11px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-font-weight"><strong>Schriftgewicht</strong></label>
        <input id="tpl-shipping-bar-font-weight" type="text" name="tpl-shipping-bar-font-weight" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-font-weight','600'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-padding"><strong>Padding</strong></label>
        <input id="tpl-shipping-bar-padding" type="text" name="tpl-shipping-bar-padding" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-padding','8px 0'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-track-bg"><strong>Fortschrittsbalken-Hintergrund</strong></label>
        <input id="tpl-shipping-bar-track-bg" type="text" name="tpl-shipping-bar-track-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-shipping-bar-track-bg','rgb(209, 250, 229)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-shipping-bar-track-bg','rgb(209, 250, 229)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-track-h"><strong>Balken-H&ouml;he</strong></label>
        <input id="tpl-shipping-bar-track-h" type="text" name="tpl-shipping-bar-track-h" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-track-h','6px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-track-radius"><strong>Balken-Rundung</strong></label>
        <input id="tpl-shipping-bar-track-radius" type="text" name="tpl-shipping-bar-track-radius" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-track-radius','999px'); ?>">
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-fill-bg"><strong>Fortschrittsbalken-F&uuml;llung</strong></label>
        <input id="tpl-shipping-bar-fill-bg" type="text" name="tpl-shipping-bar-fill-bg" class="form-control colorpicker-element" value="<?php echo mrh_cv($c,'tpl-shipping-bar-fill-bg','rgb(74, 140, 42)'); ?>">
        <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-shipping-bar-fill-bg','rgb(74, 140, 42)'); ?>"></div>
    </div>
    <div class="col-sm-4 mb-3">
        <label for="tpl-shipping-bar-icon-size"><strong>Icon-Gr&ouml;&szlig;e</strong></label>
        <input id="tpl-shipping-bar-icon-size" type="text" name="tpl-shipping-bar-icon-size" class="form-control" value="<?php echo mrh_cv($c,'tpl-shipping-bar-icon-size','1rem'); ?>">
    </div>

    <!-- Speichern -->
    <div class="col-12 mt-3 mb-3">
        <input type="submit" name="submit-colorsettings" class="btn btn-success btn-lg w-100" value="Badges speichern">
    </div>

</form>

<script>
(function(){
    'use strict';
    function renderBadgePreview() {
        var preview = document.getElementById('mrh-badge-preview');
        if (!preview) return;
        var radius = gv('tpl-badge-radius','50rem');
        var fs = gv('tpl-badge-font-size','0.8rem');
        var fw = gv('tpl-badge-font-weight','600');
        var pad = gv('tpl-badge-padding','0.25rem 0.7rem');
        var bw = gv('tpl-badge-border-width','0px');
        var bc = gv('tpl-badge-border-color','transparent');
        var types = [
            {k:'fem',l:'Feminisiert',i:'fa-venus'},
            {k:'reg',l:'Regul\u00e4r',i:'fa-mars'},
            {k:'photo',l:'Photoperiodisch',i:'fa-sun-o'},
            {k:'auto',l:'Autoflowering',i:'fa-tachometer'}
        ];
        var h = '';
        types.forEach(function(t){
            var bg = gv('tpl-badge-'+t.k+'-bg','#6c757d');
            var tx = gv('tpl-badge-'+t.k+'-text','#fff');
            var bd = gv('tpl-badge-'+t.k+'-border','transparent');
            h += '<span style="display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap;'
                +'background:'+bg+';color:'+tx+';border-radius:'+radius+';font-size:'+fs+';font-weight:'+fw+';'
                +'padding:'+pad+';border:'+bw+' solid '+(bd!=='transparent'?bd:bc)+';'
                +'"><span class="fa '+t.i+'" style="font-size:.9em"></span> '+t.l+'</span>';
        });
        preview.innerHTML = h;
    }
    function gv(n,fb){var e=document.getElementById(n);return e?(e.value||fb):fb;}
    document.querySelectorAll('#tab-badges input, #tab-badges select').forEach(function(el){
        el.addEventListener('input',renderBadgePreview);
        el.addEventListener('change',renderBadgePreview);
    });
    renderBadgePreview();
})();
</script>

</div>

<!-- ================================================================== -->
<!-- TAB 11: SEEDFINDER MODAL -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-icons">

<style>
/* === Icon-Konfigurator Tab 9 Styles === */
.mrh-icon-cfg { padding: 8px 12px; }
.mrh-icon-cfg .mrh-icon-section { margin-bottom: 16px; }
.mrh-icon-cfg .mrh-icon-section-title {
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    color: #4a8c2a; letter-spacing: 0.5px; padding: 4px 0;
    border-bottom: 2px solid #4a8c2a; margin-bottom: 8px;
}
.mrh-icon-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
.mrh-icon-card {
    background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px;
    padding: 8px 10px; display: flex; align-items: center; gap: 8px;
    transition: border-color 0.15s, box-shadow 0.15s; cursor: default;
}
.mrh-icon-card:hover { border-color: #4a8c2a; box-shadow: 0 1px 4px rgba(74,140,42,0.15); }
.mrh-icon-card .mrh-ic-preview {
    width: 36px; height: 36px; display: flex; align-items: center;
    justify-content: center; background: #fff; border-radius: 4px;
    border: 1px solid #dee2e6; font-size: 16px; flex-shrink: 0;
}
.mrh-icon-card .mrh-ic-info { flex: 1; min-width: 0; }
.mrh-icon-card .mrh-ic-label { font-size: 11px; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mrh-icon-card .mrh-ic-class { font-size: 10px; color: #888; font-family: monospace; }
.mrh-icon-card .mrh-ic-actions { display: flex; gap: 3px; flex-shrink: 0; }
.mrh-icon-card .mrh-ic-actions button {
    width: 24px; height: 24px; border: 1px solid #dee2e6; border-radius: 3px;
    background: #fff; cursor: pointer; font-size: 10px; color: #666;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s; padding: 0;
}
.mrh-icon-card .mrh-ic-actions button:hover { border-color: #4a8c2a; color: #4a8c2a; background: #f0fdf4; }

/* Global-Einstellungen Bar */
.mrh-icon-global-bar {
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px;
    padding: 8px 12px; margin-bottom: 12px; display: flex;
    flex-wrap: wrap; gap: 10px; align-items: center;
}
.mrh-icon-global-bar label { font-size: 11px; font-weight: 600; color: #333; }
.mrh-icon-global-bar select, .mrh-icon-global-bar input {
    font-size: 11px; padding: 3px 6px; border: 1px solid #d1d5db;
    border-radius: 4px; background: #fff;
}
.mrh-icon-global-bar select { min-width: 80px; }
.mrh-icon-global-bar input[type="color"] {
    width: 28px; height: 26px; padding: 1px; border: 1px solid #d1d5db;
    border-radius: 4px; cursor: pointer;
}

/* Bereichs-Overrides */
.mrh-area-section { margin-top: 16px; }
.mrh-area-toggle {
    display: flex; align-items: center; gap: 8px; padding: 6px 10px;
    background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px;
    cursor: pointer; margin-bottom: 4px; transition: background 0.15s;
}
.mrh-area-toggle:hover { background: #f0fdf4; }
.mrh-area-toggle .mrh-area-name { font-size: 12px; font-weight: 600; flex: 1; }
.mrh-area-toggle .mrh-area-badge {
    font-size: 10px; padding: 1px 6px; border-radius: 10px;
    background: #e9ecef; color: #666;
}
.mrh-area-toggle.active .mrh-area-badge { background: #4a8c2a; color: #fff; }
.mrh-area-overrides { display: none; padding: 6px 0 6px 12px; }
.mrh-area-overrides.open { display: block; }
.mrh-area-override-row {
    display: flex; align-items: center; gap: 6px; padding: 3px 0;
    font-size: 11px; border-bottom: 1px solid #f3f4f6;
}
.mrh-area-override-row:last-child { border-bottom: none; }
.mrh-area-override-row .mrh-aor-icon { width: 20px; text-align: center; color: #666; }
.mrh-area-override-row .mrh-aor-name { flex: 1; font-weight: 500; }
.mrh-area-override-row select, .mrh-area-override-row input {
    font-size: 10px; padding: 2px 4px; border: 1px solid #d1d5db;
    border-radius: 3px; background: #fff;
}
.mrh-area-override-row input[type="color"] {
    width: 22px; height: 20px; padding: 0; cursor: pointer;
}

/* Icon-Picker Modal */
.mrh-icon-picker-overlay {
    display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5); z-index: 200000;
}
.mrh-icon-picker-overlay.open { display: flex; align-items: center; justify-content: center; }
.mrh-icon-picker-modal {
    background: #fff; border-radius: 8px; width: 480px; max-width: 90vw;
    max-height: 70vh; display: flex; flex-direction: column;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.mrh-icon-picker-header {
    padding: 10px 14px; border-bottom: 1px solid #e9ecef;
    display: flex; align-items: center; gap: 8px;
}
.mrh-icon-picker-header input {
    flex: 1; border: 1px solid #d1d5db; border-radius: 4px;
    padding: 5px 8px; font-size: 12px;
}
.mrh-icon-picker-header button {
    background: none; border: 1px solid #d1d5db; border-radius: 4px;
    width: 28px; height: 28px; cursor: pointer; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
}
.mrh-icon-picker-body {
    overflow-y: auto; padding: 10px; flex: 1;
    display: grid; grid-template-columns: repeat(8, 1fr); gap: 4px;
}
.mrh-icon-picker-item {
    width: 100%; aspect-ratio: 1; display: flex; align-items: center;
    justify-content: center; border: 1px solid #e9ecef; border-radius: 4px;
    cursor: pointer; font-size: 16px; color: #555; transition: all 0.15s;
    background: #fff;
}
.mrh-icon-picker-item:hover { border-color: #4a8c2a; color: #4a8c2a; background: #f0fdf4; }
.mrh-icon-picker-item.selected { border-color: #4a8c2a; background: #dcfce7; color: #166534; }

/* Live-Vorschau */
.mrh-icon-preview-box {
    background: #fff; border: 1px solid #e9ecef; border-radius: 6px;
    padding: 10px; margin-top: 12px;
}
.mrh-icon-preview-box .mrh-preview-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    color: #666; margin-bottom: 8px; letter-spacing: 0.5px;
}
.mrh-icon-preview-row {
    display: flex; flex-wrap: wrap; gap: 12px; align-items: center;
    padding: 6px 0;
}
.mrh-icon-preview-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 4px; font-size: 12px;
    font-weight: 600; border: 1px solid transparent;
}
.mrh-icon-preview-btn.cart { background: var(--tpl-btn-primary-bg, #4a8c2a); color: var(--tpl-btn-primary-text, #fff); }
.mrh-icon-preview-btn.express { background: var(--tpl-btn-express-bg, #43c875); color: var(--tpl-btn-express-text, #fff); }
.mrh-icon-preview-btn.wishlist { background: var(--tpl-btn-wishlist-bg, #6c757d); color: var(--tpl-btn-wishlist-text, #fff); }
.mrh-icon-preview-btn.compare { background: var(--tpl-btn-compare-bg, #6c757d); color: var(--tpl-btn-compare-text, #fff); }
.mrh-icon-preview-btn.details { background: var(--tpl-btn-details-bg, #fff); color: var(--tpl-btn-details-text, #198754); border-color: var(--tpl-btn-details-text, #198754); }
.mrh-icon-preview-stars { color: #ffc107; font-size: 14px; }
.mrh-icon-preview-nav {
    display: flex; gap: 16px; align-items: center;
    padding: 6px 10px; background: #f8f9fa; border-radius: 4px;
}
.mrh-icon-preview-nav i { font-size: 16px; color: #555; }
.mrh-icon-preview-status {
    display: flex; gap: 12px; align-items: center;
}
.mrh-icon-preview-status span { display: flex; align-items: center; gap: 4px; font-size: 11px; }
</style>

<div class="mrh-icon-cfg">

    <!-- Globale Einstellungen -->
    <div class="mrh-sh"><i class="fa fa-globe me-1"></i> Globale Icon-Einstellungen</div>
    <div class="mrh-icon-global-bar" id="mrh-icon-global">
        <div>
            <label>Stil:</label>
            <select id="mrh-ig-style">
                <option value="solid">Solid</option>
                <option value="regular">Regular</option>
                <option value="light">Light</option>
            </select>
        </div>
        <div>
            <label>Groesse:</label>
            <select id="mrh-ig-size">
                <option value="xs">XS</option>
                <option value="sm">SM</option>
                <option value="md" selected>MD</option>
                <option value="lg">LG</option>
                <option value="xl">XL</option>
            </select>
        </div>
        <div>
            <label>Farbe:</label>
            <input type="color" id="mrh-ig-color" value="#333333">
        </div>
        <div>
            <label>Deckkraft:</label>
            <select id="mrh-ig-opacity">
                <option value="1">100%</option>
                <option value="0.8">80%</option>
                <option value="0.6">60%</option>
                <option value="0.4">40%</option>
            </select>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="mrhIconApplyGlobal()" title="Globale Einstellungen auf alle Icons anwenden">
                <i class="fa fa-sync-alt me-1"></i> Auf alle anwenden
            </button>
        </div>
    </div>

    <!-- Filter-Leiste -->
    <div style="margin-bottom:10px;display:flex;gap:4px;flex-wrap:wrap;">
        <button type="button" class="btn btn-sm btn-success mrh-icon-filter active" data-section="all">Alle</button>
        <button type="button" class="btn btn-sm btn-outline-secondary mrh-icon-filter" data-section="buttons"><i class="fa fa-mouse-pointer me-1"></i>Buttons</button>
        <button type="button" class="btn btn-sm btn-outline-secondary mrh-icon-filter" data-section="navigation"><i class="fa fa-compass me-1"></i>Navigation</button>
        <button type="button" class="btn btn-sm btn-outline-secondary mrh-icon-filter" data-section="ratings"><i class="fa fa-star me-1"></i>Bewertungen</button>
        <button type="button" class="btn btn-sm btn-outline-secondary mrh-icon-filter" data-section="status"><i class="fa fa-bell me-1"></i>Status</button>
        <button type="button" class="btn btn-sm btn-outline-secondary mrh-icon-filter" data-section="product"><i class="fa fa-seedling me-1"></i>Produkt</button>
    </div>

    <!-- Icon-Grid (wird per JS generiert) -->
    <div id="mrh-icon-grid-container"></div>

    <!-- Bereichs-Overrides -->
    <div class="mrh-area-section">
        <div class="mrh-sh"><i class="fa fa-layer-group me-1"></i> Bereichs-Ueberschreibungen</div>
        <p class="text-muted small mb-2">Aktiviere einen Bereich, um Icons dort individuell zu ueberschreiben.</p>
        <div id="mrh-area-list"></div>
    </div>

    <!-- Live-Vorschau -->
    <div class="mrh-icon-preview-box">
        <div class="mrh-preview-title"><i class="fa fa-eye me-1"></i> Live-Vorschau</div>
        
        <div style="font-size:11px;font-weight:600;color:#888;margin-bottom:4px;">Buttons:</div>
        <div class="mrh-icon-preview-row" id="mrh-preview-buttons"></div>
        
        <div style="font-size:11px;font-weight:600;color:#888;margin:8px 0 4px;">Navigation:</div>
        <div class="mrh-icon-preview-nav" id="mrh-preview-nav"></div>
        
        <div style="font-size:11px;font-weight:600;color:#888;margin:8px 0 4px;">Bewertungen &amp; Status:</div>
        <div class="mrh-icon-preview-status" id="mrh-preview-status"></div>
    </div>

    <!-- Speichern / Reset -->
    <div class="mt-3" style="display:flex;gap:8px;">
        <form method="post" action="" style="flex:1;" id="mrh-icon-save-form">
            <input type="hidden" name="mrh_icons_json" id="mrh-icons-json-input" value="">
            <button type="submit" name="submit-iconsettings" class="btn btn-success btn-lg w-100">
                <i class="fa fa-save me-1"></i> Icon-Konfiguration speichern
            </button>
        </form>
        <form method="post" action="" onsubmit="return confirm('Alle Icons auf Standard zuruecksetzen?');">
            <button type="submit" name="submit-reset-icons" class="btn btn-outline-danger btn-lg">
                <i class="fa fa-undo me-1"></i> Reset
            </button>
        </form>
    </div>

    <!-- Export/Import -->
    <div class="mt-2" style="display:flex;gap:8px;">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="mrhIconExport()">
            <i class="fa fa-download me-1"></i> Exportieren (JSON)
        </button>
        <label class="btn btn-sm btn-outline-secondary mb-0" style="cursor:pointer;">
            <i class="fa fa-upload me-1"></i> Importieren
            <input type="file" accept=".json" style="display:none;" onchange="mrhIconImport(event)">
        </label>
    </div>

</div>

<!-- Icon-Picker Modal -->
<div class="mrh-icon-picker-overlay" id="mrh-icon-picker">
    <div class="mrh-icon-picker-modal">
        <div class="mrh-icon-picker-header">
            <input type="text" id="mrh-icon-picker-search" placeholder="Icon suchen (z.B. cart, heart, star...)">
            <button onclick="mrhIconPickerClose()" title="Schliessen">&times;</button>
        </div>
        <div class="mrh-icon-picker-body" id="mrh-icon-picker-grid"></div>
    </div>
</div>

<?php
// Icon-Daten als JSON fuer JavaScript bereitstellen
$icons_json_safe = json_encode($icons, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
<script>
(function(){
    // === Icon-Konfigurator Daten ===
    var mrhIconData = <?php echo $icons_json_safe; ?>;
    if (!mrhIconData || !mrhIconData.icons) {
        mrhIconData = {global:{style:'solid',size:'md',color:'',opacity:'1'},icons:{},areas:{}};
    }

    // FA-Icon-Bibliothek (haeufigste Icons fuer den Picker)
    var faIcons = [
        'fa-shopping-cart','fa-cart-plus','fa-cart-arrow-down','fa-bolt','fa-bolt-lightning',
        'fa-heart','fa-heart-crack','fa-star','fa-star-half-alt','fa-balance-scale',
        'fa-info-circle','fa-info','fa-check-circle','fa-check','fa-times-circle','fa-times',
        'fa-exclamation-triangle','fa-exclamation-circle','fa-question-circle',
        'fa-search','fa-search-plus','fa-search-minus','fa-magnifying-glass',
        'fa-user','fa-user-circle','fa-user-plus','fa-user-check','fa-users',
        'fa-sign-in-alt','fa-sign-out-alt','fa-right-to-bracket','fa-right-from-bracket',
        'fa-bars','fa-ellipsis-v','fa-ellipsis-h','fa-grip-lines',
        'fa-chevron-down','fa-chevron-up','fa-chevron-left','fa-chevron-right',
        'fa-angle-down','fa-angle-up','fa-angle-left','fa-angle-right',
        'fa-arrow-down','fa-arrow-up','fa-arrow-left','fa-arrow-right',
        'fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right',
        'fa-trash','fa-trash-alt','fa-trash-can','fa-eraser',
        'fa-seedling','fa-leaf','fa-cannabis','fa-tree','fa-pagelines',
        'fa-print','fa-file-pdf','fa-file-alt','fa-copy','fa-clipboard',
        'fa-comment','fa-comment-alt','fa-comments','fa-comment-dots',
        'fa-sync-alt','fa-rotate','fa-refresh','fa-redo','fa-undo',
        'fa-expand','fa-compress','fa-maximize','fa-minimize',
        'fa-eye','fa-eye-slash','fa-glasses',
        'fa-home','fa-house','fa-building','fa-store',
        'fa-envelope','fa-envelope-open','fa-paper-plane','fa-inbox',
        'fa-phone','fa-phone-alt','fa-mobile','fa-headset',
        'fa-truck','fa-truck-fast','fa-shipping-fast','fa-box','fa-boxes',
        'fa-credit-card','fa-wallet','fa-money-bill','fa-coins',
        'fa-lock','fa-unlock','fa-shield-alt','fa-key',
        'fa-cog','fa-cogs','fa-gear','fa-gears','fa-sliders','fa-wrench',
        'fa-bell','fa-bell-slash','fa-bullhorn','fa-flag',
        'fa-tag','fa-tags','fa-bookmark','fa-thumbtack',
        'fa-clock','fa-calendar','fa-calendar-alt','fa-calendar-days',
        'fa-map-marker-alt','fa-location-dot','fa-globe','fa-map',
        'fa-share','fa-share-alt','fa-share-nodes','fa-link','fa-external-link-alt',
        'fa-download','fa-upload','fa-cloud-download-alt','fa-cloud-upload-alt',
        'fa-image','fa-images','fa-camera','fa-video',
        'fa-filter','fa-sort','fa-sort-up','fa-sort-down',
        'fa-list','fa-list-ul','fa-list-ol','fa-th','fa-th-large','fa-grid',
        'fa-plus','fa-plus-circle','fa-minus','fa-minus-circle',
        'fa-edit','fa-pen','fa-pencil-alt','fa-pen-to-square',
        'fa-power-off','fa-circle-notch','fa-spinner','fa-hourglass',
        'fa-award','fa-trophy','fa-medal','fa-certificate','fa-crown',
        'fa-fire','fa-fire-flame-curved','fa-snowflake','fa-sun','fa-moon',
        'fa-percent','fa-percentage','fa-hashtag','fa-at'
    ];

    // Size-Map fuer CSS
    var sizeMap = {xs:'0.75em',sm:'0.875em',md:'1em',lg:'1.25em',xl:'1.5em','2xl':'2em'};
    // Style-Map fuer FA-Prefix
    var styleMap = {solid:'fas',regular:'far',light:'fal',brands:'fab'};

    // === Sektions-Labels ===
    var sectionLabels = {
        buttons: 'Buttons',
        navigation: 'Navigation',
        ratings: 'Bewertungen',
        status: 'Status-Meldungen',
        product: 'Produkt'
    };

    // === Globale Einstellungen laden ===
    function loadGlobalSettings() {
        var g = mrhIconData.global || {};
        var elStyle = document.getElementById('mrh-ig-style');
        var elSize = document.getElementById('mrh-ig-size');
        var elColor = document.getElementById('mrh-ig-color');
        var elOpacity = document.getElementById('mrh-ig-opacity');
        if (elStyle) elStyle.value = g.style || 'solid';
        if (elSize) elSize.value = g.size || 'md';
        if (elColor && g.color) elColor.value = g.color;
        if (elOpacity) elOpacity.value = g.opacity || '1';
    }

    // === Icon-Grid rendern ===
    function renderIconGrid(filterSection) {
        var container = document.getElementById('mrh-icon-grid-container');
        if (!container) return;
        container.innerHTML = '';
        var icons = mrhIconData.icons || {};
        // Nach Sektionen gruppieren
        var sections = {};
        for (var key in icons) {
            var ic = icons[key];
            var sec = ic.section || 'other';
            if (filterSection && filterSection !== 'all' && sec !== filterSection) continue;
            if (!sections[sec]) sections[sec] = [];
            sections[sec].push({key: key, data: ic});
        }
        for (var secKey in sections) {
            var secDiv = document.createElement('div');
            secDiv.className = 'mrh-icon-section';
            secDiv.innerHTML = '<div class="mrh-icon-section-title">' + (sectionLabels[secKey] || secKey) + ' (' + sections[secKey].length + ')</div>';
            var grid = document.createElement('div');
            grid.className = 'mrh-icon-grid';
            sections[secKey].forEach(function(item) {
                var ic = item.data;
                var prefix = styleMap[ic.style] || 'fas';
                var colorStyle = ic.color ? 'color:' + ic.color + ';' : '';
                var sizeStyle = 'font-size:' + (sizeMap[ic.size] || '1em') + ';';
                var card = document.createElement('div');
                card.className = 'mrh-icon-card';
                card.setAttribute('data-icon-key', item.key);
                card.innerHTML = 
                    '<div class="mrh-ic-preview"><i class="' + prefix + ' ' + ic.class + '" style="' + colorStyle + sizeStyle + '"></i></div>' +
                    '<div class="mrh-ic-info">' +
                        '<div class="mrh-ic-label">' + (ic.label || item.key) + '</div>' +
                        '<div class="mrh-ic-class">' + prefix + ' ' + ic.class + '</div>' +
                    '</div>' +
                    '<div class="mrh-ic-actions">' +
                        '<button type="button" onclick="mrhIconPickerOpen(\'' + item.key + '\')" title="Icon tauschen"><i class="fa fa-exchange-alt"></i></button>' +
                        '<input type="color" value="' + (ic.color || '#333333') + '" title="Farbe" style="width:24px;height:24px;padding:0;border:1px solid #dee2e6;border-radius:3px;cursor:pointer;" onchange="mrhIconSetColor(\'' + item.key + '\', this.value)">' +
                        '<select onchange="mrhIconSetSize(\'' + item.key + '\', this.value)" title="Groesse" style="font-size:10px;padding:1px 2px;border:1px solid #dee2e6;border-radius:3px;width:38px;">' +
                            '<option value="xs"' + (ic.size==='xs'?' selected':'') + '>XS</option>' +
                            '<option value="sm"' + (ic.size==='sm'?' selected':'') + '>SM</option>' +
                            '<option value="md"' + (ic.size==='md'?' selected':'') + '>MD</option>' +
                            '<option value="lg"' + (ic.size==='lg'?' selected':'') + '>LG</option>' +
                            '<option value="xl"' + (ic.size==='xl'?' selected':'') + '>XL</option>' +
                        '</select>' +
                        '<select onchange="mrhIconSetStyle(\'' + item.key + '\', this.value)" title="Stil" style="font-size:10px;padding:1px 2px;border:1px solid #dee2e6;border-radius:3px;width:52px;">' +
                            '<option value="solid"' + (ic.style==='solid'?' selected':'') + '>Solid</option>' +
                            '<option value="regular"' + (ic.style==='regular'?' selected':'') + '>Regular</option>' +
                            '<option value="light"' + (ic.style==='light'?' selected':'') + '>Light</option>' +
                        '</select>' +
                    '</div>';
                grid.appendChild(card);
            });
            secDiv.appendChild(grid);
            container.appendChild(secDiv);
        }
    }

    // === Bereichs-Overrides rendern ===
    function renderAreas() {
        var container = document.getElementById('mrh-area-list');
        if (!container) return;
        container.innerHTML = '';
        var areas = mrhIconData.areas || {};
        for (var areaKey in areas) {
            var area = areas[areaKey];
            var isActive = area.enabled ? true : false;
            var overrideCount = area.overrides ? Object.keys(area.overrides).length : 0;
            
            var areaDiv = document.createElement('div');
            areaDiv.innerHTML = 
                '<div class="mrh-area-toggle' + (isActive ? ' active' : '') + '" onclick="mrhAreaToggle(this, \'' + areaKey + '\')">' +
                    '<input type="checkbox" ' + (isActive ? 'checked' : '') + ' style="margin:0;" onclick="event.stopPropagation();mrhAreaEnable(\'' + areaKey + '\', this.checked);">' +
                    '<span class="mrh-area-name">' + (area.label || areaKey) + '</span>' +
                    '<span class="mrh-area-badge">' + overrideCount + ' Overrides</span>' +
                '</div>' +
                '<div class="mrh-area-overrides" id="mrh-area-ov-' + areaKey + '">' +
                    mrhBuildAreaOverrides(areaKey) +
                '</div>';
            container.appendChild(areaDiv);
        }
    }

    function mrhBuildAreaOverrides(areaKey) {
        var icons = mrhIconData.icons || {};
        var overrides = (mrhIconData.areas && mrhIconData.areas[areaKey]) ? (mrhIconData.areas[areaKey].overrides || {}) : {};
        var html = '<div style="font-size:10px;color:#888;margin-bottom:4px;">Waehle Icons, die in diesem Bereich anders dargestellt werden sollen:</div>';
        for (var iconKey in icons) {
            var ic = icons[iconKey];
            var ov = overrides[iconKey] || {};
            var hasOverride = Object.keys(ov).length > 0;
            var prefix = styleMap[ov.style || ic.style] || 'fas';
            html += '<div class="mrh-area-override-row">' +
                '<span class="mrh-aor-icon"><i class="' + prefix + ' ' + (ov.class || ic.class) + '"></i></span>' +
                '<span class="mrh-aor-name">' + (ic.label || iconKey) + '</span>' +
                '<select onchange="mrhAreaSetOverride(\'' + areaKey + '\',\'' + iconKey + '\',\'size\',this.value)" style="width:38px;">' +
                    '<option value=""' + (!ov.size?' selected':'') + '>-</option>' +
                    '<option value="xs"' + (ov.size==='xs'?' selected':'') + '>XS</option>' +
                    '<option value="sm"' + (ov.size==='sm'?' selected':'') + '>SM</option>' +
                    '<option value="md"' + (ov.size==='md'?' selected':'') + '>MD</option>' +
                    '<option value="lg"' + (ov.size==='lg'?' selected':'') + '>LG</option>' +
                    '<option value="xl"' + (ov.size==='xl'?' selected':'') + '>XL</option>' +
                '</select>' +
                '<input type="color" value="' + (ov.color || ic.color || '#333333') + '" onchange="mrhAreaSetOverride(\'' + areaKey + '\',\'' + iconKey + '\',\'color\',this.value)" style="width:22px;height:20px;">' +
                '<button type="button" onclick="mrhIconPickerOpen(\'' + iconKey + '\',\'' + areaKey + '\')" style="font-size:9px;padding:1px 4px;border:1px solid #dee2e6;border-radius:3px;background:#fff;cursor:pointer;" title="Icon tauschen"><i class="fa fa-exchange-alt"></i></button>' +
                (hasOverride ? '<button type="button" onclick="mrhAreaClearOverride(\'' + areaKey + '\',\'' + iconKey + '\')" style="font-size:9px;padding:1px 4px;border:1px solid #fca5a5;border-radius:3px;background:#fff;color:#dc3545;cursor:pointer;" title="Override entfernen"><i class="fa fa-times"></i></button>' : '') +
            '</div>';
        }
        return html;
    }

    // === Live-Vorschau rendern ===
    function renderPreview() {
        var icons = mrhIconData.icons || {};
        // Buttons
        var btnContainer = document.getElementById('mrh-preview-buttons');
        if (btnContainer) {
            var ic = icons;
            btnContainer.innerHTML = 
                mrhPreviewBtn('cart', 'In den Warenkorb', ic['icon-cart']) +
                mrhPreviewBtn('express', 'Schnellkauf', ic['icon-express']) +
                mrhPreviewBtn('wishlist', 'Merkzettel', ic['icon-wishlist']) +
                mrhPreviewBtn('compare', 'Vergleichen', ic['icon-compare']) +
                mrhPreviewBtn('details', 'Details', ic['icon-details']);
        }
        // Navigation
        var navContainer = document.getElementById('mrh-preview-nav');
        if (navContainer) {
            navContainer.innerHTML = 
                mrhPreviewIcon(ic['icon-menu']) + '&nbsp;&nbsp;' +
                mrhPreviewIcon(ic['icon-search']) + '&nbsp;&nbsp;' +
                mrhPreviewIcon(ic['icon-account']) + '&nbsp;&nbsp;' +
                mrhPreviewIcon(ic['icon-login']) + '&nbsp;&nbsp;' +
                mrhPreviewIcon(ic['icon-dropdown']);
        }
        // Status
        var statusContainer = document.getElementById('mrh-preview-status');
        if (statusContainer) {
            statusContainer.innerHTML = 
                '<span>' + mrhPreviewIcon(ic['icon-star-full']) + mrhPreviewIcon(ic['icon-star-full']) + mrhPreviewIcon(ic['icon-star-full']) + mrhPreviewIcon(ic['icon-star-empty']) + mrhPreviewIcon(ic['icon-star-empty']) + '</span>' +
                '<span>' + mrhPreviewIcon(ic['icon-success']) + ' Erfolg</span>' +
                '<span>' + mrhPreviewIcon(ic['icon-warning']) + ' Warnung</span>' +
                '<span>' + mrhPreviewIcon(ic['icon-error']) + ' Fehler</span>';
        }
    }

    function mrhPreviewBtn(type, label, iconData) {
        if (!iconData) return '';
        var prefix = styleMap[iconData.style] || 'fas';
        var colorStyle = iconData.color ? 'color:' + iconData.color + ';' : '';
        return '<span class="mrh-icon-preview-btn ' + type + '">' +
            '<i class="' + prefix + ' ' + iconData.class + '" style="' + colorStyle + '"></i> ' + label +
        '</span>';
    }

    function mrhPreviewIcon(iconData) {
        if (!iconData) return '';
        var prefix = styleMap[iconData.style] || 'fas';
        var style = '';
        if (iconData.color) style += 'color:' + iconData.color + ';';
        if (iconData.size) style += 'font-size:' + (sizeMap[iconData.size] || '1em') + ';';
        return '<i class="' + prefix + ' ' + iconData.class + '" style="' + style + '"></i>';
    }

    // === Aktionen ===
    window.mrhIconSetColor = function(key, color) {
        if (mrhIconData.icons[key]) {
            mrhIconData.icons[key].color = color;
            renderIconGrid(currentFilter);
            renderPreview();
        }
    };
    window.mrhIconSetSize = function(key, size) {
        if (mrhIconData.icons[key]) {
            mrhIconData.icons[key].size = size;
            renderIconGrid(currentFilter);
            renderPreview();
        }
    };
    window.mrhIconSetStyle = function(key, style) {
        if (mrhIconData.icons[key]) {
            mrhIconData.icons[key].style = style;
            renderIconGrid(currentFilter);
            renderPreview();
        }
    };
    window.mrhIconApplyGlobal = function() {
        var g = {
            style: document.getElementById('mrh-ig-style').value,
            size: document.getElementById('mrh-ig-size').value,
            color: document.getElementById('mrh-ig-color').value,
            opacity: document.getElementById('mrh-ig-opacity').value
        };
        mrhIconData.global = g;
        for (var key in mrhIconData.icons) {
            mrhIconData.icons[key].style = g.style;
            mrhIconData.icons[key].size = g.size;
            if (g.color && g.color !== '#333333') mrhIconData.icons[key].color = g.color;
        }
        renderIconGrid(currentFilter);
        renderPreview();
    };

    // === Bereichs-Funktionen ===
    window.mrhAreaToggle = function(el, areaKey) {
        var ov = document.getElementById('mrh-area-ov-' + areaKey);
        if (ov) ov.classList.toggle('open');
    };
    window.mrhAreaEnable = function(areaKey, enabled) {
        if (mrhIconData.areas && mrhIconData.areas[areaKey]) {
            mrhIconData.areas[areaKey].enabled = enabled;
        }
    };
    window.mrhAreaSetOverride = function(areaKey, iconKey, prop, value) {
        if (!mrhIconData.areas) mrhIconData.areas = {};
        if (!mrhIconData.areas[areaKey]) mrhIconData.areas[areaKey] = {enabled:true,overrides:{}};
        if (!mrhIconData.areas[areaKey].overrides) mrhIconData.areas[areaKey].overrides = {};
        if (!mrhIconData.areas[areaKey].overrides[iconKey]) mrhIconData.areas[areaKey].overrides[iconKey] = {};
        if (value) {
            mrhIconData.areas[areaKey].overrides[iconKey][prop] = value;
        } else {
            delete mrhIconData.areas[areaKey].overrides[iconKey][prop];
            if (Object.keys(mrhIconData.areas[areaKey].overrides[iconKey]).length === 0) {
                delete mrhIconData.areas[areaKey].overrides[iconKey];
            }
        }
        renderAreas();
    };
    window.mrhAreaClearOverride = function(areaKey, iconKey) {
        if (mrhIconData.areas && mrhIconData.areas[areaKey] && mrhIconData.areas[areaKey].overrides) {
            delete mrhIconData.areas[areaKey].overrides[iconKey];
        }
        renderAreas();
    };

    // === Icon-Picker ===
    var pickerTarget = null;
    var pickerArea = null;

    window.mrhIconPickerOpen = function(iconKey, areaKey) {
        pickerTarget = iconKey;
        pickerArea = areaKey || null;
        var overlay = document.getElementById('mrh-icon-picker');
        if (overlay) overlay.classList.add('open');
        renderPickerGrid('');
        var searchInput = document.getElementById('mrh-icon-picker-search');
        if (searchInput) { searchInput.value = ''; searchInput.focus(); }
    };
    window.mrhIconPickerClose = function() {
        var overlay = document.getElementById('mrh-icon-picker');
        if (overlay) overlay.classList.remove('open');
        pickerTarget = null;
        pickerArea = null;
    };

    function renderPickerGrid(filter) {
        var grid = document.getElementById('mrh-icon-picker-grid');
        if (!grid) return;
        grid.innerHTML = '';
        var currentClass = '';
        if (pickerTarget && mrhIconData.icons[pickerTarget]) {
            currentClass = mrhIconData.icons[pickerTarget].class;
        }
        faIcons.forEach(function(cls) {
            if (filter && cls.indexOf(filter) === -1) return;
            var item = document.createElement('div');
            item.className = 'mrh-icon-picker-item' + (cls === currentClass ? ' selected' : '');
            item.innerHTML = '<i class="fas ' + cls + '"></i>';
            item.title = cls;
            item.addEventListener('click', function() {
                if (pickerArea) {
                    // Bereichs-Override
                    mrhAreaSetOverride(pickerArea, pickerTarget, 'class', cls);
                } else {
                    // Globaler Icon-Tausch
                    if (mrhIconData.icons[pickerTarget]) {
                        mrhIconData.icons[pickerTarget].class = cls;
                        renderIconGrid(currentFilter);
                        renderPreview();
                    }
                }
                mrhIconPickerClose();
            });
            grid.appendChild(item);
        });
    }

    // Picker-Suche
    var pickerSearchEl = document.getElementById('mrh-icon-picker-search');
    if (pickerSearchEl) {
        pickerSearchEl.addEventListener('input', function() {
            renderPickerGrid(this.value.toLowerCase().trim());
        });
    }

    // Overlay-Klick schliesst Picker
    var pickerOverlay = document.getElementById('mrh-icon-picker');
    if (pickerOverlay) {
        pickerOverlay.addEventListener('click', function(e) {
            if (e.target === pickerOverlay) mrhIconPickerClose();
        });
    }

    // === Filter ===
    var currentFilter = 'all';
    document.querySelectorAll('.mrh-icon-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.mrh-icon-filter').forEach(function(b) {
                b.classList.remove('active','btn-success');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active','btn-success');
            currentFilter = this.getAttribute('data-section');
            renderIconGrid(currentFilter);
        });
    });

    // === Speichern: JSON in Hidden-Input schreiben ===
    var saveForm = document.getElementById('mrh-icon-save-form');
    if (saveForm) {
        saveForm.addEventListener('submit', function() {
            // Globale Einstellungen aktualisieren
            mrhIconData.global = {
                style: document.getElementById('mrh-ig-style').value,
                size: document.getElementById('mrh-ig-size').value,
                color: document.getElementById('mrh-ig-color').value,
                opacity: document.getElementById('mrh-ig-opacity').value
            };
            document.getElementById('mrh-icons-json-input').value = JSON.stringify(mrhIconData);
        });
    }

    // === Export ===
    window.mrhIconExport = function() {
        var blob = new Blob([JSON.stringify(mrhIconData, null, 2)], {type: 'application/json'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'mrh-icons-export-' + new Date().toISOString().slice(0,10) + '.json';
        a.click();
        URL.revokeObjectURL(url);
    };

    // === Import ===
    window.mrhIconImport = function(event) {
        var file = event.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            try {
                var data = JSON.parse(e.target.result);
                if (data && data.icons) {
                    mrhIconData = data;
                    loadGlobalSettings();
                    renderIconGrid(currentFilter);
                    renderAreas();
                    renderPreview();
                    alert('Icon-Konfiguration erfolgreich importiert! Klicke "Speichern" um die Aenderungen zu uebernehmen.');
                } else {
                    alert('Ungueltige JSON-Datei: Kein "icons" Objekt gefunden.');
                }
            } catch(err) {
                alert('Fehler beim Lesen der Datei: ' + err.message);
            }
        };
        reader.readAsText(file);
    };

    // === Init ===
    loadGlobalSettings();
    renderIconGrid('all');
    renderAreas();
    renderPreview();

})();
</script>

</div>

<!-- ================================================================== -->
<!-- TAB 10: BADGE-KONFIGURATOR -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-einstellungen">

<form id="mrh-form-tplsettings" class="row mx-0" method="post" action="">
    <div class="col-12"><div class="mrh-sh"><i class="fa fa-sliders me-1"></i> Allgemeine Einstellungen</div></div>
    <div class="col-sm-6 mb-3">
        <label><strong>Infinite Scroll</strong></label>
        <select name="tpl_cfg_infinitescroll" class="form-select">
            <option value="on" <?php echo ($t['tpl_cfg_infinitescroll'] ?? 'on') === 'on' ? 'selected' : ''; ?>>Aktiviert</option>
            <option value="off" <?php echo ($t['tpl_cfg_infinitescroll'] ?? 'on') === 'off' ? 'selected' : ''; ?>>Deaktiviert</option>
        </select>
    </div>
    <div class="col-sm-6 mb-3">
        <label><strong>Barrierefrei-Tool</strong></label>
        <select name="tpl_cfg_barrierefreiTool" class="form-select">
            <option value="on" <?php echo ($t['tpl_cfg_barrierefreiTool'] ?? 'on') === 'on' ? 'selected' : ''; ?>>Aktiviert</option>
            <option value="off" <?php echo ($t['tpl_cfg_barrierefreiTool'] ?? 'on') === 'off' ? 'selected' : ''; ?>>Deaktiviert</option>
        </select>
    </div>
    <div class="col-12 mt-2 mb-4">
        <input type="submit" name="submit-tplsettings" class="btn btn-success btn-lg w-100" value="Einstellungen speichern">
    </div>
</form>

<form id="mrh-form-logos" class="row mx-0" method="post" action="">
    <div class="col-12"><div class="mrh-sh"><i class="fa fa-credit-card me-1"></i> Zahlungslogos</div></div>
<?php
$payments = ['vorkasse','paypal','kreditkarten','applepay','googlepay','amazon','klarna','lastschrift','rechnung'];
$active_payments = isset($l['payment']) ? $l['payment'] : [];
foreach ($payments as $p) {
    $checked = in_array($p, $active_payments) ? 'checked' : '';
    $plabel = ucfirst($p);
    if ($p === 'applepay') $plabel = 'Apple Pay';
    if ($p === 'googlepay') $plabel = 'Google Pay';
    if ($p === 'kreditkarten') $plabel = 'Kreditkarten';
    echo '<div class="col-sm-3 mb-2"><div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="mrh_cfg_payment_logos[]" value="'.$p.'" id="mrh_pay_'.$p.'" '.$checked.'>';
    echo '<label class="form-check-label" for="mrh_pay_'.$p.'">';
    echo '<img height="28" width="28" src="'.DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/img/payment/zahlungsarten_'.$p.'.webp" alt="'.$plabel.'" loading="lazy"> '.$plabel;
    echo '</label></div></div>';
}
?>

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-truck me-1"></i> Versandlogos</div></div>
<?php
$shippers = ['hermes','dhl','dpd','ups','gls','fedex'];
$active_shipping = isset($l['shipping']) ? $l['shipping'] : [];
foreach ($shippers as $sh) {
    $checked = in_array($sh, $active_shipping) ? 'checked' : '';
    $slabel = strtoupper($sh);
    if ($sh === 'hermes') $slabel = 'Hermes';
    if ($sh === 'fedex') $slabel = 'FedEx';
    echo '<div class="col-sm-3 mb-2"><div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="mrh_cfg_shipping_logos[]" value="'.$sh.'" id="mrh_ship_'.$sh.'" '.$checked.'>';
    echo '<label class="form-check-label" for="mrh_ship_'.$sh.'">';
    echo '<img height="28" width="28" src="'.DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/img/shipping/'.$sh.'.webp" alt="'.$slabel.'" loading="lazy"> '.$slabel;
    echo '</label></div></div>';
}
?>

    <div class="col-12 mt-2 mb-4">
        <input type="submit" name="submit-logosettings" class="btn btn-success btn-lg w-100" value="Logos speichern">
    </div>
</form>

<form id="mrh-form-social" class="row mx-0" method="post" action="">
    <div class="col-12"><div class="mrh-sh"><i class="fa-brands fa-instagram me-1"></i> Social Media Links</div></div>
    <div class="col-12 mb-2"><p class="text-muted small">Tragen Sie Ihre Social Media Links ein. Leere Felder werden nicht angezeigt.</p></div>
<?php
$socials = [
    'facebook'  => ['label' => 'Facebook',    'icon' => 'fa-brands fa-facebook',  'placeholder' => 'https://www.facebook.com/IhrName'],
    'twitter'   => ['label' => 'X (Twitter)', 'icon' => 'fa-brands fa-x-twitter','placeholder' => 'https://x.com/IhrName'],
    'instagram' => ['label' => 'Instagram',   'icon' => 'fa-brands fa-instagram', 'placeholder' => 'https://www.instagram.com/IhrName'],
    'tiktok'    => ['label' => 'TikTok',      'icon' => 'fa-brands fa-tiktok',    'placeholder' => 'https://www.tiktok.com/@IhrName'],
    'youtube'   => ['label' => 'YouTube',     'icon' => 'fa-brands fa-youtube',   'placeholder' => 'https://www.youtube.com/@IhrName'],
    'pinterest' => ['label' => 'Pinterest',   'icon' => 'fa-brands fa-pinterest', 'placeholder' => 'https://www.pinterest.com/IhrName'],
    'linkedin'  => ['label' => 'LinkedIn',    'icon' => 'fa-brands fa-linkedin',  'placeholder' => 'https://www.linkedin.com/company/IhrName'],
];
foreach ($socials as $skey => $info) {
    $val = isset($s[$skey]) ? htmlspecialchars($s[$skey]) : '';
    echo '<div class="col-sm-6 mb-3">';
    echo '<label for="mrh-social-'.$skey.'"><strong><i class="'.$info['icon'].' me-1"></i>'.$info['label'].'</strong></label>';
    echo '<input type="url" class="form-control" id="mrh-social-'.$skey.'" name="'.$skey.'" value="'.$val.'" placeholder="'.$info['placeholder'].'">';
    echo '</div>';
}
?>

    <div class="col-12 mt-2">
        <input type="submit" name="submit-socialsettings" class="btn btn-success btn-lg w-100" value="Social Media Links speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 7: CUSTOM CSS -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-customcss">
<form id="mrh-form-customcss" method="post" action="">

    <div class="mrh-sh mx-2"><i class="fa fa-code me-1"></i> Eigenes CSS (Live-Preview)</div>
    <p class="text-muted small mx-2">CSS wird sofort auf der Seite angewendet. Erst beim Speichern wird es persistent.</p>

    <textarea id="mrh-custom-css-textarea" name="mrh_custom_css" class="form-control font-monospace mx-2"
              rows="20" spellcheck="false"
              style="width:calc(100% - 24px);background:#1e1e2e;color:#cdd6f4;font-size:13px;line-height:1.6;tab-size:4;resize:vertical;"
              placeholder="/* Eigenes CSS hier eingeben */&#10;.mein-element {&#10;    color: red;&#10;    font-size: 16px;&#10;}"><?php echo htmlspecialchars(isset($GLOBALS['mrh_custom_css']) ? $GLOBALS['mrh_custom_css'] : ''); ?></textarea>

    <div class="mx-2 mt-3">
        <input type="submit" name="submit-customcss" class="btn btn-success btn-lg w-100" value="Custom CSS speichern">
    </div>
</form>
</div>

<!-- ================================================================== -->
<!-- TAB 8: PRESETS & BACKUP/RESTORE -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-presets">

    <!-- Preset laden -->
    <div class="mrh-sh mx-2"><i class="fa fa-paint-brush me-1"></i> Farb-Preset laden</div>
    <p class="text-muted small mx-2 mb-3">Lade ein vorkonfiguriertes Farbschema. Alle aktuellen Farben werden ueberschrieben.</p>
    <form method="post" action="" class="mx-2 mb-4">
        <div class="row align-items-end">
            <div class="col-sm-8 mb-2">
                <select name="preset_name" class="form-select">
                    <option value="">-- Preset auswaehlen --</option>
                    <?php foreach ($presets as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['file']); ?>">
                        <?php echo htmlspecialchars($p['name']); ?>
                        <?php if (!empty($p['description'])): ?> &ndash; <?php echo htmlspecialchars($p['description']); ?><?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 mb-2">
                <button type="submit" name="submit-load-preset" class="btn btn-success w-100">
                    <i class="fa fa-download me-1"></i> Preset laden
                </button>
            </div>
        </div>
    </form>

    <hr class="mx-2">

    <!-- Backup erstellen -->
    <div class="mrh-sh mx-2"><i class="fa fa-save me-1"></i> Backup erstellen</div>
    <p class="text-muted small mx-2 mb-3">Speichert die aktuellen Farben als Backup mit Zeitstempel.</p>
    <form method="post" action="" class="mx-2 mb-4">
        <button type="submit" name="submit-backup" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Aktuelles Farbschema als Backup speichern
        </button>
    </form>

    <hr class="mx-2">

    <!-- Backup wiederherstellen -->
    <div class="mrh-sh mx-2"><i class="fa fa-undo me-1"></i> Backup wiederherstellen</div>
    <?php if (count($backups) > 0): ?>
    <form method="post" action="" class="mx-2 mb-4">
        <div class="row align-items-end">
            <div class="col-sm-8 mb-2">
                <select name="backup_file" class="form-select">
                    <option value="">-- Backup auswaehlen --</option>
                    <?php foreach ($backups as $b): ?>
                    <option value="<?php echo htmlspecialchars($b['file']); ?>">
                        <?php echo htmlspecialchars($b['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 mb-2">
                <button type="submit" name="submit-restore" class="btn btn-warning w-100">
                    <i class="fa fa-undo me-1"></i> Wiederherstellen
                </button>
            </div>
        </div>
    </form>
    <?php else: ?>
    <p class="text-muted small mx-2">Noch keine Backups vorhanden. Erstelle zuerst ein Backup.</p>
    <?php endif; ?>

    <hr class="mx-2">

    <!-- Auf Standard zuruecksetzen -->
    <div class="mrh-sh mx-2"><i class="fa fa-exclamation-triangle me-1"></i> Auf Standard zuruecksetzen</div>
    <p class="text-muted small mx-2 mb-3">Setzt alle Farben auf die Standardwerte zurueck. <strong>Erstelle vorher ein Backup!</strong></p>
    <form method="post" action="" class="mx-2" onsubmit="return confirm('Alle Farben auf Standard zuruecksetzen? Erstelle vorher ein Backup!');">
        <button type="submit" name="submit-reset-defaults" class="btn btn-outline-danger">
            <i class="fa fa-exclamation-triangle me-1"></i> Auf Standard zuruecksetzen
        </button>
    </form>

</div>

<!-- ================================================================== -->
<!-- TAB 9: ICON-KONFIGURATOR -->
<!-- ================================================================== -->

<div class="mrh-tab-pane" id="tab-content">
<h5 class="mb-3"><i class="fa fa-file-code me-2"></i>Content-Bausteine (BS5)</h5>
<p class="text-muted small mb-3">Fertige HTML-Snippets f&uuml;r Content-Seiten. Code kopieren und im Content-Manager einf&uuml;gen. Alle Snippets nutzen Bootstrap 5 und die MRH-2026 CSS-Klassen.</p>

<?php
// Content-Snippets aus config/content-snippets/ laden
$snippetDir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/config/content-snippets/';
if (is_dir($snippetDir)) {
    $snippetFiles = glob($snippetDir . '*.html');
    if (!empty($snippetFiles)) {
        sort($snippetFiles);
        foreach ($snippetFiles as $idx => $file) {
            $basename = basename($file, '.html');
            $label = ucwords(str_replace('-', ' ', $basename));
            $content = file_get_contents($file);
            $snippetId = 'snippet-' . $idx;
?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center" style="cursor:pointer;" onclick="document.getElementById('<?php echo $snippetId; ?>').classList.toggle('d-none')">
        <strong><i class="fa fa-code me-2"></i><?php echo htmlspecialchars($label); ?></strong>
        <span class="badge bg-success">BS5</span>
    </div>
    <div id="<?php echo $snippetId; ?>" class="d-none">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Vorschau:</label>
                <div class="border rounded p-3" style="background:#f8f9fa;max-height:400px;overflow-y:auto;">
                    <?php echo $content; ?>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label fw-bold">HTML-Code:</label>
                <textarea class="form-control" rows="12" readonly onclick="this.select()" style="font-family:monospace;font-size:12px;"><?php echo htmlspecialchars($content); ?></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="var ta=this.previousElementSibling.querySelector('textarea');ta.select();document.execCommand('copy');this.textContent='Kopiert!';var b=this;setTimeout(function(){b.textContent='Code kopieren';},2000);">
                <i class="fa fa-copy me-1"></i>Code kopieren
            </button>
        </div>
    </div>
</div>
<?php
        }
    } else {
        echo '<div class="alert alert-info"><i class="fa fa-info-circle me-2"></i>Keine Content-Snippets vorhanden. Lege HTML-Dateien in <code>config/content-snippets/</code> ab.</div>';
    }
} else {
    echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle me-2"></i>Verzeichnis <code>config/content-snippets/</code> nicht gefunden.</div>';
}
?>

<div class="alert alert-secondary mt-3">
    <i class="fa fa-lightbulb me-2"></i><strong>Tipp:</strong> Neue Snippets als <code>.html</code>-Datei in <code>templates/tpl_mrh_2026/config/content-snippets/</code> ablegen. Sie erscheinen automatisch hier.
</div>
</div><!-- /#tab-content -->

<!-- ================================================================== -->
<!-- TAB: WIDGET-POSITIONIERUNG -->
<!-- ================================================================== -->
<div class="mrh-tab-pane" id="tab-widgets">

<style>
/* === Widget-Positionierung Tab Styles === */
.mrh-widget-cfg { padding: 8px 12px; }
.mrh-widget-layout { display: grid; grid-template-columns: 1fr 320px; gap: 16px; }
@media (max-width: 900px) { .mrh-widget-layout { grid-template-columns: 1fr; } }

/* Widget-Karten */
.mrh-widget-card {
    background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px;
    padding: 10px 12px; margin-bottom: 8px;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.mrh-widget-card:hover { border-color: #4a8c2a; box-shadow: 0 1px 4px rgba(74,140,42,0.15); }
.mrh-widget-card.disabled { opacity: 0.5; }
.mrh-widget-header {
    display: flex; align-items: center; gap: 8px; margin-bottom: 6px;
}
.mrh-widget-icon {
    width: 32px; height: 32px; border-radius: 6px; display: flex;
    align-items: center; justify-content: center; font-size: 14px; color: #fff; flex-shrink: 0;
}
.mrh-widget-title { font-size: 12px; font-weight: 700; color: #333; flex: 1; }
.mrh-widget-pos { font-size: 10px; color: #888; font-family: monospace; }
.mrh-widget-desc { font-size: 10px; color: #666; margin-bottom: 6px; }
.mrh-widget-fields { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; }
.mrh-widget-fields label { font-size: 10px; font-weight: 600; color: #555; display: block; }
.mrh-widget-fields input {
    width: 100%; font-size: 11px; padding: 3px 6px; border: 1px solid #d1d5db;
    border-radius: 4px; background: #fff;
}
.mrh-widget-selector {
    font-size: 10px; color: #4a8c2a; font-family: monospace;
    background: #f0fdf4; padding: 2px 6px; border-radius: 3px;
    margin-top: 4px; display: inline-block;
}

/* Toggle Switch */
.mrh-widget-toggle { position: relative; width: 36px; height: 20px; flex-shrink: 0; }
.mrh-widget-toggle input { opacity: 0; width: 0; height: 0; }
.mrh-widget-toggle .slider {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background: #ccc; border-radius: 20px; transition: 0.2s;
}
.mrh-widget-toggle .slider:before {
    position: absolute; content: ""; height: 16px; width: 16px; left: 2px; bottom: 2px;
    background: #fff; border-radius: 50%; transition: 0.2s;
}
.mrh-widget-toggle input:checked + .slider { background: #4a8c2a; }
.mrh-widget-toggle input:checked + .slider:before { transform: translateX(16px); }

/* Phone Mockup */
.mrh-widget-phone {
    width: 300px; height: 560px; border: 3px solid #1a1a1a; border-radius: 28px;
    overflow: hidden; position: relative; background: #fff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}
.mrh-widget-phone-header {
    background: #4a8c2a; color: #fff; padding: 8px 12px;
    display: flex; align-items: center; justify-content: space-between;
    font-size: 13px; font-weight: 700;
}
.mrh-widget-phone-content {
    position: relative; height: calc(100% - 80px); overflow: hidden;
    background: #f5f5f5;
}
.mrh-widget-phone-product {
    background: #fff; margin: 8px; border-radius: 8px; padding: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.mrh-widget-phone-product img {
    width: 100%; height: 140px; object-fit: cover; border-radius: 6px;
    background: #e8f5e2;
}
.mrh-widget-phone-nav {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: #fff; border-top: 1px solid #e5e5e5;
    display: flex; justify-content: space-around; padding: 6px 0;
    font-size: 9px; color: #888;
}
.mrh-widget-phone-nav i { display: block; font-size: 14px; margin-bottom: 2px; text-align: center; }

/* Draggable Widgets in Phone */
.mrh-draggable-widget {
    position: absolute; cursor: grab; z-index: 10;
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    transition: box-shadow 0.15s;
    user-select: none;
}
.mrh-draggable-widget:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.35); }
.mrh-draggable-widget:active { cursor: grabbing; box-shadow: 0 6px 16px rgba(0,0,0,0.4); }
.mrh-draggable-widget.hidden { display: none; }

/* Export Panel */
.mrh-widget-export {
    background: #1e293b; color: #e2e8f0; border-radius: 6px;
    padding: 10px; margin-top: 10px; font-family: monospace; font-size: 11px;
    max-height: 250px; overflow-y: auto; white-space: pre-wrap;
    display: none;
}
.mrh-widget-export.show { display: block; }
.mrh-widget-export-tabs { display: flex; gap: 4px; margin-bottom: 6px; }
.mrh-widget-export-tabs button {
    padding: 3px 10px; font-size: 10px; border: 1px solid #475569;
    background: transparent; color: #94a3b8; border-radius: 3px; cursor: pointer;
}
.mrh-widget-export-tabs button.active { background: #334155; color: #fff; border-color: #4a8c2a; }
</style>

<div class="mrh-widget-cfg">
    <div class="mrh-sh"><i class="fa fa-layer-group me-1"></i> Widget-Positionierung</div>
    <p class="text-muted small mb-3">Floating-Widgets per Drag &amp; Drop in der Live-Vorschau positionieren. Positionen werden als CSS gespeichert.</p>

    <div class="mrh-widget-layout">
        <!-- Linke Spalte: Widget-Karten -->
        <div id="mrh-widget-cards">
            <!-- Vergleich -->
            <div class="mrh-widget-card" data-widget="compare">
                <div class="mrh-widget-header">
                    <div class="mrh-widget-icon" style="background:#0d9488;"><i class="fa fa-scale-balanced"></i></div>
                    <span class="mrh-widget-title">Vergleich</span>
                    <span class="mrh-widget-pos" id="pos-compare">right: 20px, bottom: 80px</span>
                    <label class="mrh-widget-toggle">
                        <input type="checkbox" checked data-widget="compare" onchange="mrhWidgetToggle(this)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="mrh-widget-desc">Produktvergleich Floating-Button</div>
                <div class="mrh-widget-fields">
                    <div><label>Ecke</label><select data-widget="compare" data-axis="anchor" onchange="mrhWidgetFieldChange(this)"><option value="bottom-right" selected>Unten Rechts</option><option value="bottom-left">Unten Links</option><option value="top-right">Oben Rechts</option><option value="top-left">Oben Links</option></select></div>
                    <div><label>Abstand X (px)</label><input type="number" min="0" max="500" value="20" data-widget="compare" data-axis="offsetX" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Abstand Y (px)</label><input type="number" min="0" max="500" value="80" data-widget="compare" data-axis="offsetY" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Z-Index</label><input type="number" min="1" max="9999" value="1050" data-widget="compare" data-axis="z" onchange="mrhWidgetFieldChange(this)"></div>
                </div>
                <div class="mrh-widget-selector">&lt;/&gt; .product-compare-badge</div>
            </div>

            <!-- Barrierefreiheit -->
            <div class="mrh-widget-card" data-widget="a11y">
                <div class="mrh-widget-header">
                    <div class="mrh-widget-icon" style="background:#2563eb;"><i class="fa fa-universal-access"></i></div>
                    <span class="mrh-widget-title">Barrierefreiheit</span>
                    <span class="mrh-widget-pos" id="pos-a11y">left: 20px, bottom: 20px</span>
                    <label class="mrh-widget-toggle">
                        <input type="checkbox" checked data-widget="a11y" onchange="mrhWidgetToggle(this)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="mrh-widget-desc">Web-Barrierefreiheit Widget</div>
                <div class="mrh-widget-fields">
                    <div><label>Ecke</label><select data-widget="a11y" data-axis="anchor" onchange="mrhWidgetFieldChange(this)"><option value="bottom-right">Unten Rechts</option><option value="bottom-left" selected>Unten Links</option><option value="top-right">Oben Rechts</option><option value="top-left">Oben Links</option></select></div>
                    <div><label>Abstand X (px)</label><input type="number" min="0" max="500" value="20" data-widget="a11y" data-axis="offsetX" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Abstand Y (px)</label><input type="number" min="0" max="500" value="20" data-widget="a11y" data-axis="offsetY" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Z-Index</label><input type="number" min="1" max="9999" value="1040" data-widget="a11y" data-axis="z" onchange="mrhWidgetFieldChange(this)"></div>
                </div>
                <div class="mrh-widget-selector">&lt;/&gt; .faw-menu-btn</div>
            </div>

            <!-- eTrust -->
            <div class="mrh-widget-card" data-widget="etrust">
                <div class="mrh-widget-header">
                    <div class="mrh-widget-icon" style="background:#ca8a04;"><i class="fa fa-shield-halved"></i></div>
                    <span class="mrh-widget-title">eTrust / Trusted Shops</span>
                    <span class="mrh-widget-pos" id="pos-etrust">left: 0px, bottom: 80px</span>
                    <label class="mrh-widget-toggle">
                        <input type="checkbox" checked data-widget="etrust" onchange="mrhWidgetToggle(this)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="mrh-widget-desc">Trusted Shops G&uuml;tesiegel Widget</div>
                <div class="mrh-widget-fields">
                    <div><label>Ecke</label><select data-widget="etrust" data-axis="anchor" onchange="mrhWidgetFieldChange(this)"><option value="bottom-right">Unten Rechts</option><option value="bottom-left" selected>Unten Links</option><option value="top-right">Oben Rechts</option><option value="top-left">Oben Links</option></select></div>
                    <div><label>Abstand X (px)</label><input type="number" min="0" max="500" value="0" data-widget="etrust" data-axis="offsetX" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Abstand Y (px)</label><input type="number" min="0" max="500" value="80" data-widget="etrust" data-axis="offsetY" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Z-Index</label><input type="number" min="1" max="9999" value="1030" data-widget="etrust" data-axis="z" onchange="mrhWidgetFieldChange(this)"></div>
                </div>
                <div class="mrh-widget-selector">&lt;/&gt; ._uuhri8</div>
            </div>

            <!-- Cookies -->
            <div class="mrh-widget-card" data-widget="cookies">
                <div class="mrh-widget-header">
                    <div class="mrh-widget-icon" style="background:#78350f;"><i class="fa fa-cookie-bite"></i></div>
                    <span class="mrh-widget-title">Cookies</span>
                    <span class="mrh-widget-pos" id="pos-cookies">left: 20px, bottom: 80px</span>
                    <label class="mrh-widget-toggle">
                        <input type="checkbox" checked data-widget="cookies" onchange="mrhWidgetToggle(this)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="mrh-widget-desc">Cookie-Einstellungen Trigger-Icon</div>
                <div class="mrh-widget-fields">
                    <div><label>Ecke</label><select data-widget="cookies" data-axis="anchor" onchange="mrhWidgetFieldChange(this)"><option value="bottom-right">Unten Rechts</option><option value="bottom-left" selected>Unten Links</option><option value="top-right">Oben Rechts</option><option value="top-left">Oben Links</option></select></div>
                    <div><label>Abstand X (px)</label><input type="number" min="0" max="500" value="20" data-widget="cookies" data-axis="offsetX" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Abstand Y (px)</label><input type="number" min="0" max="500" value="80" data-widget="cookies" data-axis="offsetY" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Z-Index</label><input type="number" min="1" max="9999" value="1020" data-widget="cookies" data-axis="z" onchange="mrhWidgetFieldChange(this)"></div>
                </div>
                <div class="mrh-widget-selector">&lt;/&gt; [data-trigger-cookie-consent-panel]</div>
            </div>

            <!-- Scroll-to-Top -->
            <div class="mrh-widget-card" data-widget="scrolltop">
                <div class="mrh-widget-header">
                    <div class="mrh-widget-icon" style="background:#4a8c2a;"><i class="fa fa-arrow-up"></i></div>
                    <span class="mrh-widget-title">Scroll-to-Top</span>
                    <span class="mrh-widget-pos" id="pos-scrolltop">right: 24px, bottom: 24px</span>
                    <label class="mrh-widget-toggle">
                        <input type="checkbox" checked data-widget="scrolltop" onchange="mrhWidgetToggle(this)">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="mrh-widget-desc">Nach-oben-scrollen Button</div>
                <div class="mrh-widget-fields">
                    <div><label>Ecke</label><select data-widget="scrolltop" data-axis="anchor" onchange="mrhWidgetFieldChange(this)"><option value="bottom-right" selected>Unten Rechts</option><option value="bottom-left">Unten Links</option><option value="top-right">Oben Rechts</option><option value="top-left">Oben Links</option></select></div>
                    <div><label>Abstand X (px)</label><input type="number" min="0" max="500" value="24" data-widget="scrolltop" data-axis="offsetX" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Abstand Y (px)</label><input type="number" min="0" max="500" value="24" data-widget="scrolltop" data-axis="offsetY" onchange="mrhWidgetFieldChange(this)"></div>
                    <div><label>Z-Index</label><input type="number" min="1" max="9999" value="1060" data-widget="scrolltop" data-axis="z" onchange="mrhWidgetFieldChange(this)"></div>
                </div>
                <div class="mrh-widget-selector">&lt;/&gt; .mrh-back-to-top</div>
            </div>

            <!-- Aktions-Buttons -->
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="mrhWidgetReset()"><i class="fa fa-undo me-1"></i>Zur&uuml;cksetzen</button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="mrhWidgetToggleExport()"><i class="fa fa-code me-1"></i>CSS / PHP Export</button>
            </div>

            <!-- Export-Panel -->
            <div id="mrh-widget-export" class="mrh-widget-export">
                <div class="mrh-widget-export-tabs">
                    <button class="active" onclick="mrhWidgetExportTab('css',this)">CSS</button>
                    <button onclick="mrhWidgetExportTab('php',this)">PHP</button>
                </div>
                <pre id="mrh-widget-export-code"></pre>
                <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="mrhWidgetCopyExport()"><i class="fa fa-copy me-1"></i>Kopieren</button>
                </div>
            </div>
        </div>

        <!-- Rechte Spalte: Phone Mockup -->
        <div>
            <div class="text-center mb-2"><small class="text-muted fw-bold" style="font-size:10px;letter-spacing:1px;"><i class="fa fa-circle text-success me-1" style="font-size:6px;vertical-align:middle;"></i>DRAG &amp; DROP VORSCHAU</small></div>
            <div class="mrh-widget-phone" id="mrh-widget-phone">
                <div class="mrh-widget-phone-header">
                    <span>Mr. Hanf</span>
                    <span><i class="fa fa-search me-2"></i><i class="fa fa-heart me-2"></i><i class="fa fa-cart-shopping"></i></span>
                </div>
                <div class="mrh-widget-phone-content" id="mrh-widget-phone-content">
                    <div class="mrh-widget-phone-product">
                        <div style="width:100%;height:140px;background:linear-gradient(135deg,#e8f5e2,#c8e6c0);border-radius:6px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-cannabis" style="font-size:48px;color:#4a8c2a;opacity:0.3;"></i>
                        </div>
                        <div style="margin-top:8px;">
                            <strong style="font-size:12px;">Northern Lights Auto</strong>
                            <div style="font-size:10px;color:#888;">Royal Queen Seeds</div>
                            <div style="margin-top:4px;">
                                <i class="fa fa-star" style="color:#f59e0b;font-size:10px;"></i>
                                <i class="fa fa-star" style="color:#f59e0b;font-size:10px;"></i>
                                <i class="fa fa-star" style="color:#f59e0b;font-size:10px;"></i>
                                <i class="fa fa-star" style="color:#f59e0b;font-size:10px;"></i>
                                <i class="fa fa-star-half-stroke" style="color:#f59e0b;font-size:10px;"></i>
                                <span style="font-size:9px;color:#888;">(23)</span>
                            </div>
                            <div style="margin-top:6px;font-size:13px;font-weight:700;">ab 22,00 EUR</div>
                            <div style="margin-top:6px;background:#4a8c2a;color:#fff;text-align:center;padding:6px;border-radius:4px;font-size:11px;font-weight:600;"><i class="fa fa-cart-shopping me-1"></i>In den Warenkorb</div>
                        </div>
                    </div>
                    <div style="padding:0 8px;">
                        <table style="width:100%;font-size:9px;border-collapse:collapse;">
                            <tr style="border-bottom:1px solid #eee;"><td style="padding:3px 0;color:#888;">Bl&uuml;tezeit</td><td style="padding:3px 0;font-weight:600;">9 &ndash; 10 Wochen</td></tr>
                            <tr style="border-bottom:1px solid #eee;"><td style="padding:3px 0;color:#888;">Ertrag Indoor</td><td style="padding:3px 0;font-weight:600;">550 &ndash; 800 g/m&sup2;</td></tr>
                            <tr><td style="padding:3px 0;color:#888;">Klima</td><td style="padding:3px 0;font-weight:600;">sehr hei&szlig;, gem&auml;&szlig;igt</td></tr>
                        </table>
                    </div>

                    <!-- Draggable Widgets (realistische Darstellung) -->
                    <!-- Vergleich: Roter Kreis mit Waage + Badge-Counter -->
                    <div class="mrh-draggable-widget" id="drag-compare" style="background:#ef4444;right:15%;bottom:22%;position:absolute;" data-widget="compare" title="Vergleich">
                        <i class="fa fa-scale-balanced" style="font-size:16px;"></i>
                        <span style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:8px;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:1.5px solid #fff;">7</span>
                    </div>
                    <!-- Barrierefreiheit: Blaues rundes Icon mit Accessibility-SVG -->
                    <div class="mrh-draggable-widget" id="drag-a11y" style="background:#4054B2;left:2%;bottom:22%;position:absolute;width:40px;height:40px;" data-widget="a11y" title="Barrierefreiheit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="white"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20.5 6c-2.61.7-5.67 1-8.5 1s-5.89-.3-8.5-1L3 8c1.86.5 4 .83 6 1v13h2v-6h2v6h2V9c2-.17 4.14-.5 6-1l-.5-2zM12 6c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                    </div>
                    <!-- eTrust: Ovales weisses Badge mit grunem e-Logo und Sternen -->
                    <div class="mrh-draggable-widget" id="drag-etrust" style="background:#fff;left:2%;bottom:40%;position:absolute;width:56px;height:72px;border-radius:28px;border:1px solid #ddd;box-shadow:0 2px 8px rgba(0,0,0,0.15);flex-direction:column;padding:4px 2px;" data-widget="etrust" title="eTrust">
                        <div style="width:28px;height:28px;margin:0 auto 2px;">
                            <svg viewBox="0 0 40 40" width="28" height="28"><circle cx="20" cy="20" r="18" fill="#fff" stroke="#c8c8c8" stroke-width="1"/><text x="20" y="24" text-anchor="middle" font-size="18" font-weight="bold" fill="#1a1a1a" font-family="serif">e</text></svg>
                        </div>
                        <div style="display:flex;gap:1px;justify-content:center;">
                            <i class="fa fa-star" style="font-size:5px;color:#f59e0b;"></i>
                            <i class="fa fa-star" style="font-size:5px;color:#f59e0b;"></i>
                            <i class="fa fa-star" style="font-size:5px;color:#f59e0b;"></i>
                            <i class="fa fa-star" style="font-size:5px;color:#f59e0b;"></i>
                            <i class="fa fa-star-half-stroke" style="font-size:5px;color:#f59e0b;"></i>
                        </div>
                        <div style="font-size:7px;font-weight:700;color:#333;text-align:center;line-height:1;">4,46</div>
                    </div>
                    <!-- Cookies: Kleines rundes Icon -->
                    <div class="mrh-draggable-widget" id="drag-cookies" style="background:#6b7280;left:2%;bottom:10%;position:absolute;width:32px;height:32px;" data-widget="cookies" title="Cookies">
                        <i class="fa fa-cookie-bite" style="font-size:14px;"></i>
                    </div>
                    <!-- Scroll-to-Top: Gruener Kreis mit Pfeil -->
                    <div class="mrh-draggable-widget" id="drag-scrolltop" style="background:#4a8c2a;right:10%;bottom:15%;position:absolute;width:40px;height:40px;" data-widget="scrolltop" title="Scroll-to-Top">
                        <i class="fa fa-chevron-up" style="font-size:16px;"></i>
                    </div>
                </div>
                <div class="mrh-widget-phone-nav">
                    <div><i class="fa fa-house"></i>Home</div>
                    <div><i class="fa fa-search"></i>Suche</div>
                    <div style="color:#4a8c2a;"><i class="fa fa-seedling"></i>Seedfinder</div>
                    <div><i class="fa fa-heart"></i>Merkliste</div>
                    <div><i class="fa fa-cart-shopping"></i>Warenkorb</div>
                </div>
            </div>
            <div class="text-center mt-2"><small class="text-muted" style="font-size:9px;">Widgets mit der Maus ziehen um zu positionieren</small></div>
        </div>
    </div>

    <!-- Hidden Form fuer Speichern -->
    <form id="mrh-form-widgets" method="post" action="" style="display:none;">
        <input type="hidden" name="mrh_widgets_json" id="mrh_widgets_json" value="">
        <input type="hidden" name="submit-widgetsettings" value="1">
    </form>

    <!-- Speichern-Button (nutzt den globalen Speichern-Mechanismus) -->
    <div class="d-flex justify-content-end mt-3 gap-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="mrhWidgetReset()"><i class="fa fa-undo me-1"></i>Zur&uuml;cksetzen</button>
        <button type="button" class="btn btn-sm btn-success" onclick="mrhWidgetSave()"><i class="fa fa-save me-1"></i>Positionen speichern</button>
    </div>
</div>

<script>
(function(){
    // === Widget-Konfiguration (Edge-Anchoring mit Pixel-Abstaenden) ===
    var widgets = {
        compare:   { anchor: 'bottom-right', offsetX: 20, offsetY: 80, z: 1050, visible: true, selector: '.product-compare-badge',          label: 'Vergleich' },
        a11y:      { anchor: 'bottom-left',  offsetX: 20, offsetY: 20, z: 1040, visible: true, selector: '.faw-menu-btn',                   label: 'Barrierefreiheit' },
        etrust:    { anchor: 'bottom-left',  offsetX: 0,  offsetY: 80, z: 1030, visible: true, selector: '._uuhri8',                        label: 'eTrust' },
        cookies:   { anchor: 'bottom-left',  offsetX: 20, offsetY: 80, z: 1020, visible: true, selector: '[data-trigger-cookie-consent-panel]', label: 'Cookies' },
        scrolltop: { anchor: 'bottom-right', offsetX: 24, offsetY: 24, z: 1060, visible: true, selector: '.mrh-back-to-top',                label: 'Scroll-to-Top' }
    };

    // Gespeicherte Werte laden (aus PHP/JSON)
    <?php
    $widgets_file = $json_dir . 'widgets.json';
    $saved_widgets = mrh_read_json($widgets_file);
    if (!empty($saved_widgets)) {
        echo 'var saved = ' . json_encode($saved_widgets) . ';';
        echo 'if (saved) { for (var k in saved) { if (widgets[k]) { if (saved[k].anchor !== undefined) widgets[k].anchor = saved[k].anchor; if (saved[k].offsetX !== undefined) widgets[k].offsetX = parseInt(saved[k].offsetX); if (saved[k].offsetY !== undefined) widgets[k].offsetY = parseInt(saved[k].offsetY); if (saved[k].z !== undefined) widgets[k].z = parseInt(saved[k].z); if (saved[k].visible !== undefined) widgets[k].visible = !!saved[k].visible; if (saved[k].selector !== undefined) widgets[k].selector = saved[k].selector; } } }';
    }
    ?>

    // Hilfsfunktion: Anchor in CSS-Positionierung umrechnen
    function anchorToLabel(w) {
        var parts = w.anchor.split('-');
        var vDir = parts[0]; // top oder bottom
        var hDir = parts[1]; // left oder right
        return hDir + ': ' + w.offsetX + 'px, ' + vDir + ': ' + w.offsetY + 'px';
    }

    // Hilfsfunktion: Anchor+Offset in Prozent-Position fuer Phone-Mockup umrechnen
    function widgetToPhonePos(w) {
        var parts = w.anchor.split('-');
        var vDir = parts[0]; // top oder bottom
        var hDir = parts[1]; // left oder right
        // Phone-Mockup ist ca 320px breit, 568px hoch
        var phoneW = 320, phoneH = 500;
        var pxX = w.offsetX, pxY = w.offsetY;
        var leftPct, topPct;
        if (hDir === 'right') { leftPct = ((phoneW - pxX) / phoneW) * 100; }
        else { leftPct = (pxX / phoneW) * 100; }
        if (vDir === 'bottom') { topPct = ((phoneH - pxY) / phoneH) * 100; }
        else { topPct = (pxY / phoneH) * 100; }
        return { left: Math.max(2, Math.min(98, leftPct)), top: Math.max(2, Math.min(98, topPct)) };
    }

    // UI initialisieren
    function initWidgetUI() {
        for (var key in widgets) {
            var w = widgets[key];
            var card = document.querySelector('.mrh-widget-card[data-widget="'+key+'"]');
            if (card) {
                var anchorSel = card.querySelector('select[data-axis="anchor"]');
                if (anchorSel) anchorSel.value = w.anchor;
                var oxInput = card.querySelector('input[data-axis="offsetX"]');
                if (oxInput) oxInput.value = w.offsetX;
                var oyInput = card.querySelector('input[data-axis="offsetY"]');
                if (oyInput) oyInput.value = w.offsetY;
                var zInput = card.querySelector('input[data-axis="z"]');
                if (zInput) zInput.value = w.z;
                card.querySelector('input[type="checkbox"]').checked = w.visible;
                if (!w.visible) card.classList.add('disabled');
                else card.classList.remove('disabled');
            }
            var posEl = document.getElementById('pos-' + key);
            if (posEl) posEl.textContent = anchorToLabel(w);
            updateDragPosition(key);
        }
    }

    function updateDragPosition(key) {
        var el = document.getElementById('drag-' + key);
        var w = widgets[key];
        if (!el) return;
        var pos = widgetToPhonePos(w);
        el.style.left = pos.left + '%';
        el.style.top = pos.top + '%';
        el.style.right = 'auto';
        el.style.bottom = 'auto';
        el.style.zIndex = w.z;
        el.style.transform = 'translate(-50%, -50%)';
        if (w.visible) { el.classList.remove('hidden'); } else { el.classList.add('hidden'); }
    }

    // === Drag & Drop ===
    // WICHTIG: phoneContent wird erst beim Tab-Wechsel sichtbar,
    // daher getBoundingClientRect() immer live im mousemove aufrufen
    var dragging = null;
    var dragInitialized = false;

    function initDragListeners() {
        if (dragInitialized) return;
        dragInitialized = true;

        document.querySelectorAll('#tab-widgets .mrh-draggable-widget').forEach(function(el) {
            // Mouse events
            el.addEventListener('mousedown', startDrag);
            // Touch events fuer Mobile
            el.addEventListener('touchstart', startDragTouch, { passive: false });
        });

        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('mouseup', endDrag);
        document.addEventListener('touchmove', onDragMoveTouch, { passive: false });
        document.addEventListener('touchend', endDrag);
    }

    function startDrag(e) {
        e.preventDefault();
        e.stopPropagation();
        dragging = this;
        this.style.transition = 'none';
        this.style.cursor = 'grabbing';
    }

    function startDragTouch(e) {
        e.preventDefault();
        e.stopPropagation();
        dragging = this;
        this.style.transition = 'none';
    }

    function onDragMove(e) {
        if (!dragging) return;
        e.preventDefault();
        moveDrag(e.clientX, e.clientY);
    }

    function onDragMoveTouch(e) {
        if (!dragging) return;
        e.preventDefault();
        var touch = e.touches[0];
        moveDrag(touch.clientX, touch.clientY);
    }

    function moveDrag(clientX, clientY) {
        var phoneContent = document.getElementById('mrh-widget-phone-content');
        if (!phoneContent) return;
        var rect = phoneContent.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return;

        var key = dragging.getAttribute('data-widget');
        var w = widgets[key];
        // Mausposition relativ zum Phone-Content (0..1)
        var relX = (clientX - rect.left) / rect.width;
        var relY = (clientY - rect.top) / rect.height;
        relX = Math.max(0, Math.min(1, relX));
        relY = Math.max(0, Math.min(1, relY));

        // In Pixel-Abstaende umrechnen (Phone = 320x500)
        var phoneW = 320, phoneH = 500;
        var parts = w.anchor.split('-');
        var vDir = parts[0], hDir = parts[1];
        if (hDir === 'right') { w.offsetX = Math.round((1 - relX) * phoneW); }
        else { w.offsetX = Math.round(relX * phoneW); }
        if (vDir === 'bottom') { w.offsetY = Math.round((1 - relY) * phoneH); }
        else { w.offsetY = Math.round(relY * phoneH); }
        w.offsetX = Math.max(0, Math.min(500, w.offsetX));
        w.offsetY = Math.max(0, Math.min(500, w.offsetY));

        // Visuell aktualisieren
        dragging.style.left = (relX * 100) + '%';
        dragging.style.top = (relY * 100) + '%';
        dragging.style.right = 'auto';
        dragging.style.bottom = 'auto';
        dragging.style.transform = 'translate(-50%, -50%)';

        // Felder aktualisieren
        var card = document.querySelector('.mrh-widget-card[data-widget="'+key+'"]');
        if (card) {
            var oxInput = card.querySelector('input[data-axis="offsetX"]');
            if (oxInput) oxInput.value = w.offsetX;
            var oyInput = card.querySelector('input[data-axis="offsetY"]');
            if (oyInput) oyInput.value = w.offsetY;
        }
        var posEl = document.getElementById('pos-' + key);
        if (posEl) posEl.textContent = anchorToLabel(w);
    }

    function endDrag() {
        if (dragging) {
            dragging.style.transition = '';
            dragging.style.cursor = 'grab';
            dragging = null;
        }
    }

    // Drag-Listener initialisieren wenn Widgets-Tab sichtbar wird
    // (Tab-Wechsel-Event abfangen)
    var widgetsTabBtn = document.querySelector('.mrh-tab[data-tab="widgets"]');
    if (widgetsTabBtn) {
        widgetsTabBtn.addEventListener('click', function() {
            // Kurz warten bis Tab sichtbar ist, dann Drag initialisieren
            setTimeout(function() {
                initDragListeners();
                initWidgetUI();
            }, 50);
        });
    }
    // Falls Tab schon aktiv ist (z.B. nach Speichern)
    var tabPane = document.getElementById('tab-widgets');
    if (tabPane && tabPane.offsetParent !== null) {
        initDragListeners();
    }

    // === Event-Handler (global verfuegbar) ===
    window.mrhWidgetToggle = function(checkbox) {
        var key = checkbox.getAttribute('data-widget');
        widgets[key].visible = checkbox.checked;
        var card = checkbox.closest('.mrh-widget-card');
        if (card) { checkbox.checked ? card.classList.remove('disabled') : card.classList.add('disabled'); }
        updateDragPosition(key);
    };

    window.mrhWidgetFieldChange = function(input) {
        var key = input.getAttribute('data-widget');
        var axis = input.getAttribute('data-axis');
        if (axis === 'anchor') { widgets[key].anchor = input.value; }
        else if (axis === 'offsetX') { widgets[key].offsetX = Math.max(0, Math.min(500, parseInt(input.value) || 0)); }
        else if (axis === 'offsetY') { widgets[key].offsetY = Math.max(0, Math.min(500, parseInt(input.value) || 0)); }
        else if (axis === 'z') { widgets[key].z = Math.max(1, Math.min(9999, parseInt(input.value) || 0)); }
        updateDragPosition(key);
        var posEl = document.getElementById('pos-' + key);
        if (posEl) posEl.textContent = anchorToLabel(widgets[key]);
    };

    window.mrhWidgetReset = function() {
        widgets.compare   = { anchor: 'bottom-right', offsetX: 20, offsetY: 80, z: 1050, visible: true, selector: '.product-compare-badge',          label: 'Vergleich' };
        widgets.a11y      = { anchor: 'bottom-left',  offsetX: 20, offsetY: 20, z: 1040, visible: true, selector: '.faw-menu-btn',                   label: 'Barrierefreiheit' };
        widgets.etrust    = { anchor: 'bottom-left',  offsetX: 0,  offsetY: 80, z: 1030, visible: true, selector: '._uuhri8',                        label: 'eTrust' };
        widgets.cookies   = { anchor: 'bottom-left',  offsetX: 20, offsetY: 80, z: 1020, visible: true, selector: '[data-trigger-cookie-consent-panel]', label: 'Cookies' };
        widgets.scrolltop = { anchor: 'bottom-right', offsetX: 24, offsetY: 24, z: 1060, visible: true, selector: '.mrh-back-to-top',                label: 'Scroll-to-Top' };
        initWidgetUI();
    };

    // === Export ===
    var exportMode = 'css';
    window.mrhWidgetToggleExport = function() {
        var panel = document.getElementById('mrh-widget-export');
        panel.classList.toggle('show');
        if (panel.classList.contains('show')) generateExport();
    };

    window.mrhWidgetExportTab = function(mode, btn) {
        exportMode = mode;
        btn.parentNode.querySelectorAll('button').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        generateExport();
    };

    function generateExport() {
        var code = '';
        var d = new Date().toLocaleDateString('de-DE');
        if (exportMode === 'css') {
            code += '/* ========================================= */\n';
            code += '/* MRH Widget-Positionen – Konfigurator      */\n';
            code += '/* Datum: ' + d + '                          */\n';
            code += '/* ========================================= */\n\n';
            for (var key in widgets) {
                var w = widgets[key];
                if (!w.visible) { code += '/* ' + w.label + ' – AUSGEBLENDET */\n\n'; continue; }
                var parts = w.anchor.split('-');
                var vDir = parts[0], hDir = parts[1];
                var vOpp = (vDir === 'bottom') ? 'top' : 'bottom';
                var hOpp = (hDir === 'right') ? 'left' : 'right';
                code += '/* ' + w.label + ' */\n';
                code += w.selector + ' {\n';
                code += '  position: fixed !important;\n';
                code += '  ' + hDir + ': ' + w.offsetX + 'px !important;\n';
                code += '  ' + vDir + ': ' + w.offsetY + 'px !important;\n';
                code += '  ' + hOpp + ': auto !important;\n';
                code += '  ' + vOpp + ': auto !important;\n';
                code += '  z-index: ' + w.z + ' !important;\n';
                code += '}\n\n';
            }
        } else {
            code += '<' + '?php\n';
            code += '/* ========================================= */\n';
            code += '/* MRH Widget-Positionen als PHP-Konstanten  */\n';
            code += '/* Datum: ' + d + '                          */\n';
            code += '/* ========================================= */\n\n';
            for (var key in widgets) {
                var w = widgets[key];
                var K = key.toUpperCase();
                code += '// ' + w.label + '\n';
                code += "define('WIDGET_" + K + "_VISIBLE', " + (w.visible ? "'true'" : "'false'") + ");\n";
                code += "define('WIDGET_" + K + "_ANCHOR', '" + w.anchor + "');\n";
                code += "define('WIDGET_" + K + "_OFFSET_X', '" + w.offsetX + "px');\n";
                code += "define('WIDGET_" + K + "_OFFSET_Y', '" + w.offsetY + "px');\n";
                code += "define('WIDGET_" + K + "_Z', '" + w.z + "');\n\n";
            }
        }
        document.getElementById('mrh-widget-export-code').textContent = code;
    }

    window.mrhWidgetCopyExport = function() {
        var code = document.getElementById('mrh-widget-export-code').textContent;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(function() {
                alert('Code in die Zwischenablage kopiert!');
            });
        } else {
            var ta = document.createElement('textarea');
            ta.value = code;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            alert('Code in die Zwischenablage kopiert!');
        }
    };

    // === Speichern ===
    window.mrhWidgetSave = function() {
        document.getElementById('mrh_widgets_json').value = JSON.stringify(widgets);
        document.getElementById('mrh-form-widgets').submit();
    };

    // Init
    initWidgetUI();
})();
</script>

</div><!-- /#tab-widgets -->


</div><!-- /#mrh-configurator-v4 -->

<!-- Tab-Navigation JS + Live-Preview (Vanilla, kein jQuery) -->
<script>
(function(){
    var tabs = document.querySelectorAll('#mrh-config-tabs .mrh-tab');
    var panes = document.querySelectorAll('.mrh-tab-pane');
    tabs.forEach(function(tab){
        tab.addEventListener('click', function(){
            var target = this.getAttribute('data-tab');
            tabs.forEach(function(t){ t.classList.remove('active'); });
            panes.forEach(function(p){ p.classList.remove('active'); });
            this.classList.add('active');
            var el = document.getElementById('tab-' + target);
            if (el) el.classList.add('active');
            // Colorpicker in neu sichtbarem Tab initialisieren (admin.php)
            if (typeof window.mrhReinitColorpickers === 'function') {
                setTimeout(window.mrhReinitColorpickers, 50);
            }
        });
    });
    // Live-Preview fuer Groessen-Inputs (rem-Werte -> CSS-Variablen)
    document.querySelectorAll('.mrh-size-input').forEach(function(input){
        input.addEventListener('input', function(){
            var name = this.getAttribute('name');
            var val = this.value.trim();
            if (name && val) {
                document.documentElement.style.setProperty('--' + name, val);
            }
        });
    });

    // ===== Live-Preview fuer ALLE Konfigurator-Inputs (v1.1.0) =====
    // Setzt CSS-Variablen auf :root fuer sofortige Vorschau ohne Speichern.
    // Gilt fuer alle input[name^="tpl-"] im Konfigurator.
    function mrhLiveApplyAll() {
        // Inputs UND Selects mit tpl-* Namen abfangen
        var elements = document.querySelectorAll('#mrh-configurator-v4 input[name^="tpl-"], #mrh-configurator-v4 select[name^="tpl-"]');
        elements.forEach(function(el) {
            // Nur Elemente die noch keinen Listener haben
            if (el.hasAttribute('data-mrh-live')) return;
            el.setAttribute('data-mrh-live', '1');

            var applyFn = function() {
                var name = el.getAttribute('name');
                var val = el.value.trim();
                // val !== '' statt val (damit '0' nicht als falsy ignoriert wird)
                if (name && val !== '') {
                    document.documentElement.style.setProperty('--' + name, val);
                }
            };

            el.addEventListener('input', applyFn);
            el.addEventListener('change', applyFn);
        });
    }
    // Initial + nach Tab-Wechsel (Colorpicker werden erst bei Tab-Wechsel initialisiert)
    mrhLiveApplyAll();
    document.querySelectorAll('#mrh-config-tabs .mrh-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            setTimeout(mrhLiveApplyAll, 100);
        });
    });

    // Colorpicker-Aenderungen abfangen (Spectrum/Pickr setzen Werte per JS)
    if (typeof MutationObserver !== 'undefined') {
        var configForm = document.getElementById('mrh-configurator-v4');
        if (configForm) {
            var cpObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(m) {
                    if (m.type === 'attributes' && m.attributeName === 'value') {
                        var el = m.target;
                        if ((el.tagName === 'INPUT' || el.tagName === 'SELECT') && el.name && el.name.indexOf('tpl-') === 0) {
                            var val = el.value.trim();
                            if (val !== '') {
                                document.documentElement.style.setProperty('--' + el.name, val);
                            }
                        }
                    }
                });
            });
            cpObserver.observe(configForm, { attributes: true, attributeFilter: ['value'], subtree: true });
        }
    }
})();
</script>
