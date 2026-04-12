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
    <div class="mrh-tab" data-tab="einstellungen"><i class="fa fa-cog me-1"></i>Einstellungen</div>
    <div class="mrh-tab" data-tab="customcss"><i class="fa fa-code me-1"></i>Custom CSS</div>
    <div class="mrh-tab" data-tab="presets"><i class="fa fa-magic me-1"></i>Presets</div>
    <div class="mrh-tab" data-tab="icons"><i class="fa fa-icons me-1"></i>Icons</div>
    <div class="mrh-tab" data-tab="badges"><i class="fa fa-certificate me-1"></i>Badges</div>
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
    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-venus me-1" style="color:#fc5b96;"></i> Feminisiert</div></div>
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

    <div class="col-12"><hr><div class="mrh-sh"><i class="fa fa-mars me-1" style="color:#2ea2f0;"></i> Regul&auml;r</div></div>
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
    <div class="col-sm-4 mb-3">
        <label for="tpl-bb-sf-margin-top"><strong>Versatz oben</strong></label>
        <input id="tpl-bb-sf-margin-top" type="text" name="tpl-bb-sf-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-margin-top','-14px'); ?>">
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
        <label for="tpl-bb-sf-mob-margin-top"><strong>Versatz oben (Mobil)</strong></label>
        <input id="tpl-bb-sf-mob-margin-top" type="text" name="tpl-bb-sf-mob-margin-top" class="form-control mrh-size-input" value="<?php echo mrh_cv($c,'tpl-bb-sf-mob-margin-top','-10px'); ?>">
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
        var inputs = document.querySelectorAll('#mrh-configurator-v4 input[name^="tpl-"]');
        inputs.forEach(function(input) {
            // Nur Inputs die noch keinen Listener haben
            if (input.hasAttribute('data-mrh-live')) return;
            input.setAttribute('data-mrh-live', '1');

            var applyFn = function() {
                var name = input.getAttribute('name');
                var val = input.value.trim();
                if (name && val) {
                    document.documentElement.style.setProperty('--' + name, val);
                }
            };

            input.addEventListener('input', applyFn);
            input.addEventListener('change', applyFn);
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
                        if (el.tagName === 'INPUT' && el.name && el.name.indexOf('tpl-') === 0) {
                            var val = el.value.trim();
                            if (val) {
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
