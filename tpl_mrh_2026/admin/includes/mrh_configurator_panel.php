<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Panel v4.0
   
   Wird eingebunden via source/boxes/admin.php
   Benötigt: admin/includes/mrh_configurator.php (PHP-Backend)
   
   v4.0 (2026-04-10): Komplett-Umbau auf 7-Tab-Layout
     1. Allgemein    – Grundfarben, Hintergrund, Schrift, Footer, Topbar
     2. Navigation   – Menü, Sticky Header
     3. Buttons      – Gefüllt, Outline, Spezial
     4. Typografie   – h1-h6 (Größe+Farbe), Body, Links, text-*
     5. Komponenten  – bg-*, border-*, alert-*, card, form, table
     6. Einstellungen – Infinite Scroll, Barrierefrei, Logos, Social
     7. Custom CSS   – Textarea mit Live-Preview
   
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
$special_buttons = ['express'=>'Express Kaufen','details'=>'Details (Auge)','wishlist'=>'Merkzettel (Herz)','compare'=>'Vergleichen (Waage)'];
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

</div><!-- /#mrh-configurator-v4 -->

<!-- Tab-Navigation JS (Vanilla, kein jQuery) -->
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
        });
    });
    // Live-Preview für Größen-Inputs (rem-Werte -> CSS-Variablen)
    var sizeInputs = document.querySelectorAll('.mrh-size-input');
    sizeInputs.forEach(function(input){
        input.addEventListener('input', function(){
            var name = this.getAttribute('name');
            var val = this.value.trim();
            if (name && val) {
                document.documentElement.style.setProperty('--' + name, val);
            }
        });
    });
})();
</script>
