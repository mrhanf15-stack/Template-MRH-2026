<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Panel v3.0
   
   Wird eingebunden via source/boxes/admin.php
   Benötigt: admin/includes/mrh_configurator.php (PHP-Backend)
   
   v3.0 (2026-04-10): ALLE Keys auf tpl-* vereinheitlicht
                       Kein mrh-* / tpl-* Dualismus mehr!
   
   Sektionen:
   1. Farben individualisieren (inkl. Menü + Topbar + Sticky + Buttons)
   2. Weitere Konfiguration
   3. Zahlungs- und Versandlogos
   4. Social Media Links
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

<div id="mrh-configurator" class="accordion accordion-flush" role="region">

  <!-- ===== SEKTION 1: FARBEN ===== -->
  <section class="card mt-4 mb-4 mx-3">
    <header class="card-header" id="mrhHeadingColors">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseColors"
              aria-expanded="true" aria-controls="mrhCollapseColors">
        <strong class="h5 mb-0"><i class="fa fa-palette me-2"></i>Farben individualisieren</strong>
      </button>
    </header>

    <div id="mrhCollapseColors" class="accordion-collapse collapse show"
         aria-labelledby="mrhHeadingColors" data-bs-parent="#mrh-configurator">
      <div class="card-body">
        <form id="mrh-colorsettings" class="row" method="post" action="">

          <!-- Hauptfarben -->
          <section class="col-sm-12 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-circle-half-stroke me-1"></i> Hauptfarben
            </h6>
            <div class="row">
              <div class="col-sm-4 mb-3">
                <label for="tpl-main-color"><strong>Prim&auml;rfarbe</strong></label>
                <input id="tpl-main-color" type="text" name="tpl-main-color"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-main-color'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-main-color'); ?>"></div>
              </div>
              <div class="col-sm-4 mb-3">
                <label for="tpl-main-color-2"><strong>Sekund&auml;rfarbe</strong></label>
                <input id="tpl-main-color-2" type="text" name="tpl-main-color-2"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-main-color-2'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-main-color-2'); ?>"></div>
              </div>
              <div class="col-sm-4 mb-3">
                <label for="tpl-secondary-color"><strong>Akzentfarbe</strong></label>
                <input id="tpl-secondary-color" type="text" name="tpl-secondary-color"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-secondary-color'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-secondary-color'); ?>"></div>
              </div>
            </div>
          </section>

          <hr class="mx-3">

          <!-- Men&uuml;-Farben -->
          <section class="col-sm-12 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-bars me-1"></i> Hauptnavigation (Men&uuml;-Leiste)
            </h6>
            <div class="row">
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
            </div>
          </section>

          <hr class="mx-3">

          <!-- Topbar-Farben -->
          <section class="col-sm-12 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-grip-lines me-1"></i> Topbar (Trust-Leiste)
            </h6>
            <div class="row">
              <div class="col-sm-6 mb-3">
                <label for="tpl-topbar-bg"><strong>Topbar Hintergrund</strong></label>
                <input id="tpl-topbar-bg" type="text" name="tpl-topbar-bg"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-topbar-bg'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-topbar-bg'); ?>"></div>
              </div>
              <div class="col-sm-6 mb-3">
                <label for="tpl-topbar-text"><strong>Topbar Textfarbe</strong></label>
                <input id="tpl-topbar-text" type="text" name="tpl-topbar-text"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-topbar-text'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-topbar-text'); ?>"></div>
              </div>
            </div>
          </section>

          <hr class="mx-3">

          <!-- Hintergrundfarben -->
          <section class="col-sm-6 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-fill-drip me-1"></i> Hintergrundfarben
            </h6>
<?php
$bg_fields = [
    'tpl-bg-color'      => 'Hintergrundfarbe 1',
    'tpl-bg-color-2'    => 'Hintergrundfarbe 2',
    'tpl-bg-productbox' => 'Produktboxen Hintergrund',
    'tpl-bg-footer'     => 'Footer Hintergrund',
];
foreach ($bg_fields as $key => $label) {
    echo '<div class="mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>
          </section>

          <!-- Schriftfarben -->
          <section class="col-sm-6 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-font me-1"></i> Schriftfarben
            </h6>
<?php
$text_fields = [
    'tpl-text-standard'        => 'Standard Schriftfarbe',
    'tpl-text-headings'        => '&Uuml;berschriften Schriftfarbe',
    'tpl-text-button'          => 'Schriftfarbe in Buttons &amp; Badges',
    'tpl-text-footer'          => 'Schriftfarbe Text &amp; Links im Footer',
    'tpl-text-footer-headings' => '&Uuml;berschriften im Footer',
];
foreach ($text_fields as $key => $label) {
    echo '<div class="mb-3">';
    echo '<label for="'.$key.'"><strong>'.$label.'</strong></label>';
    echo '<input id="'.$key.'" type="text" name="'.$key.'" class="form-control colorpicker-element" value="'.mrh_cv($c,$key).'">';
    echo '<div class="demo-farbe mt-1" style="background:'.mrh_cv($c,$key).'"></div>';
    echo '</div>';
}
?>
          </section>

          <!-- Sticky Header -->
          <section class="col-sm-12 mb-3">
            <hr>
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-thumbtack me-1"></i> Sticky Header
            </h6>
            <div class="row">
              <div class="col-sm-6 mb-3">
                <label for="tpl-sticky-bg"><strong>Sticky Header Hintergrund</strong></label>
                <input id="tpl-sticky-bg" type="text" name="tpl-sticky-bg"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-sticky-bg'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sticky-bg'); ?>"></div>
              </div>
              <div class="col-sm-6 mb-3">
                <label for="tpl-sticky-text"><strong>Sticky Header Textfarbe</strong></label>
                <input id="tpl-sticky-text" type="text" name="tpl-sticky-text"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'tpl-sticky-text'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'tpl-sticky-text'); ?>"></div>
              </div>
            </div>
          </section>

          <!-- ══════════════════════════════════════════════════ -->
          <!-- Gef&uuml;llte Buttons (btn-*) -->
          <!-- ══════════════════════════════════════════════════ -->
          <section class="col-sm-12 mb-3">
            <hr>
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-square me-1"></i> Gef&uuml;llte Buttons
            </h6>
<?php
$filled_buttons = [
    'primary'   => ['label' => 'Primary',   'icon' => 'fa-circle'],
    'secondary' => ['label' => 'Secondary', 'icon' => 'fa-circle'],
    'success'   => ['label' => 'Success',   'icon' => 'fa-check-circle'],
    'danger'    => ['label' => 'Danger',    'icon' => 'fa-exclamation-circle'],
    'warning'   => ['label' => 'Warning',   'icon' => 'fa-exclamation-triangle'],
    'info'      => ['label' => 'Info',      'icon' => 'fa-info-circle'],
    'light'     => ['label' => 'Light',     'icon' => 'fa-sun'],
    'dark'      => ['label' => 'Dark',      'icon' => 'fa-moon'],
];
foreach ($filled_buttons as $variant => $meta) {
    $bg_key    = 'tpl-btn-' . $variant . '-bg';
    $text_key  = 'tpl-btn-' . $variant . '-text';
    $hover_key = 'tpl-btn-' . $variant . '-hover';
    echo '<div class="row mb-2">';
    echo '<div class="col-12 mb-1"><strong><i class="fa ' . $meta['icon'] . ' me-1"></i> btn-' . $variant . '</strong></div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $bg_key . '">Hintergrund</label>';
    echo '<input id="' . $bg_key . '" type="text" name="' . $bg_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $bg_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $bg_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $text_key . '">Textfarbe</label>';
    echo '<input id="' . $text_key . '" type="text" name="' . $text_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $text_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $text_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $hover_key . '">Hover Hintergrund</label>';
    echo '<input id="' . $hover_key . '" type="text" name="' . $hover_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $hover_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $hover_key) . '"></div>';
    echo '</div>';
    echo '</div>';
}
?>
          </section>

          <!-- ══════════════════════════════════════════════════ -->
          <!-- Outline Buttons (btn-outline-*) -->
          <!-- ══════════════════════════════════════════════════ -->
          <section class="col-sm-12 mb-3">
            <hr>
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-square-o me-1"></i> Outline Buttons
            </h6>
<?php
// Allgemeine Outline-Felder (Legacy-Kompatibilit&auml;t)
$outline_general = [
    'tpl-btn-outline-border' => 'Outline Rahmenfarbe',
    'tpl-btn-outline-text'   => 'Outline Textfarbe',
    'tpl-btn-outline-hover'  => 'Outline Hover',
];
echo '<div class="row mb-3">';
foreach ($outline_general as $key => $label) {
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $key . '"><strong>' . $label . '</strong></label>';
    echo '<input id="' . $key . '" type="text" name="' . $key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $key) . '"></div>';
    echo '</div>';
}
echo '</div>';
echo '<hr class="my-2">';

// Einzelne Outline-Varianten
$outline_buttons = [
    'primary'   => ['label' => 'Outline Primary',   'icon' => 'fa-circle-o'],
    'secondary' => ['label' => 'Outline Secondary', 'icon' => 'fa-circle-o'],
    'success'   => ['label' => 'Outline Success',   'icon' => 'fa-check-circle-o'],
    'danger'    => ['label' => 'Outline Danger',    'icon' => 'fa-exclamation-circle'],
    'warning'   => ['label' => 'Outline Warning',   'icon' => 'fa-exclamation-triangle'],
    'info'      => ['label' => 'Outline Info',      'icon' => 'fa-info-circle'],
    'light'     => ['label' => 'Outline Light',     'icon' => 'fa-sun-o'],
    'dark'      => ['label' => 'Outline Dark',      'icon' => 'fa-moon-o'],
];
foreach ($outline_buttons as $variant => $meta) {
    $bg_key    = 'tpl-btn-outline-' . $variant . '-bg';
    $text_key  = 'tpl-btn-outline-' . $variant . '-text';
    $hover_key = 'tpl-btn-outline-' . $variant . '-hover';
    echo '<div class="row mb-2">';
    echo '<div class="col-12 mb-1"><strong><i class="fa ' . $meta['icon'] . ' me-1"></i> btn-outline-' . $variant . '</strong></div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $bg_key . '">Hintergrund</label>';
    echo '<input id="' . $bg_key . '" type="text" name="' . $bg_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $bg_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $bg_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $text_key . '">Textfarbe</label>';
    echo '<input id="' . $text_key . '" type="text" name="' . $text_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $text_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $text_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $hover_key . '">Hover Hintergrund</label>';
    echo '<input id="' . $hover_key . '" type="text" name="' . $hover_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $hover_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $hover_key) . '"></div>';
    echo '</div>';
    echo '</div>';
}
?>
          </section>

          <!-- ══════════════════════════════════════════════════ -->
          <!-- Spezial-Buttons (Express, Details, Wishlist, Compare) -->
          <!-- ══════════════════════════════════════════════════ -->
          <section class="col-sm-12 mb-3">
            <hr>
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-star me-1"></i> Spezial-Buttons
            </h6>
<?php
$special_buttons = [
    'express'  => ['label' => 'Express Kaufen', 'icon' => 'fa-bolt'],
    'details'  => ['label' => 'Details',        'icon' => 'fa-eye'],
    'wishlist' => ['label' => 'Merkzettel',     'icon' => 'fa-heart'],
    'compare'  => ['label' => 'Vergleichen',    'icon' => 'fa-exchange'],
];
foreach ($special_buttons as $variant => $meta) {
    $bg_key    = 'tpl-btn-' . $variant . '-bg';
    $text_key  = 'tpl-btn-' . $variant . '-text';
    $hover_key = 'tpl-btn-' . $variant . '-hover';
    echo '<div class="row mb-2">';
    echo '<div class="col-12 mb-1"><strong><i class="fa ' . $meta['icon'] . ' me-1"></i> ' . $meta['label'] . '</strong></div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $bg_key . '">Hintergrund</label>';
    echo '<input id="' . $bg_key . '" type="text" name="' . $bg_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $bg_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $bg_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $text_key . '">Textfarbe</label>';
    echo '<input id="' . $text_key . '" type="text" name="' . $text_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $text_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $text_key) . '"></div>';
    echo '</div>';
    echo '<div class="col-sm-4 mb-2">';
    echo '<label for="' . $hover_key . '">Hover Hintergrund</label>';
    echo '<input id="' . $hover_key . '" type="text" name="' . $hover_key . '" class="form-control colorpicker-element" value="' . mrh_cv($c, $hover_key) . '">';
    echo '<div class="demo-farbe mt-1" style="background:' . mrh_cv($c, $hover_key) . '"></div>';
    echo '</div>';
    echo '</div>';
}
?>
          </section>

          <section class="col-sm-12">
            <input type="submit" name="submit-colorsettings" id="submit-colorsettings"
                   class="btn btn-success btn-lg w-100" value="Farben speichern">
          </section>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== SEKTION 2: WEITERE KONFIGURATION ===== -->
  <section class="card mb-4 mx-3">
    <header class="card-header" id="mrhHeadingConfig">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseConfig"
              aria-expanded="false" aria-controls="mrhCollapseConfig">
        <strong class="h5 mb-0"><i class="fa fa-sliders me-2"></i>Weitere Konfiguration</strong>
      </button>
    </header>

    <div id="mrhCollapseConfig" class="accordion-collapse collapse"
         aria-labelledby="mrhHeadingConfig" data-bs-parent="#mrh-configurator">
      <div class="card-body">
        <form id="mrh-tplsettings" class="row" method="post" action="">

          <section class="col-sm-6 mb-3">
            <fieldset>
              <legend><strong>SSL-Zertifikat (https) aktiv?</strong></legend>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="mrh-ssl" name="tpl_cfg_ssl" value="on"
                       <?php if(isset($t['tpl_cfg_ssl']) && $t['tpl_cfg_ssl']==='on') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-ssl">SSL aktiv</label>
              </div>
            </fieldset>
          </section>

          <section class="col-sm-6 mb-3">
            <fieldset>
              <legend><strong>TrustedShops Siegel im Header?</strong></legend>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="mrh-ts" name="tpl_cfg_ts" value="on"
                       <?php if(isset($t['tpl_cfg_ts']) && $t['tpl_cfg_ts']==='on') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-ts">Anzeigen</label>
              </div>
            </fieldset>
          </section>

          <section class="col-sm-6 mb-3">
            <fieldset>
              <legend><strong>Men&uuml; horizontal oder vertikal?</strong></legend>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="tpl_cfg_menu"
                       value="horizontal" id="mrh-menu-h"
                       <?php if(isset($t['tpl_cfg_menu']) && $t['tpl_cfg_menu']==='horizontal') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-menu-h">Horizontal</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="tpl_cfg_menu"
                       value="vertikal" id="mrh-menu-v"
                       <?php if(isset($t['tpl_cfg_menu']) && $t['tpl_cfg_menu']==='vertikal') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-menu-v">Vertikal</label>
              </div>
            </fieldset>
          </section>

          <section class="col-sm-6 mb-3">
            <fieldset>
              <legend><strong>Infinite Scroll?</strong></legend>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="mrh-infinitescroll" name="tpl_cfg_infinitescroll" value="on"
                       <?php if(isset($t['tpl_cfg_infinitescroll']) && $t['tpl_cfg_infinitescroll']==='on') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-infinitescroll">Aktiv (Kategorie- &amp; Suchergebnisseiten)</label>
              </div>
            </fieldset>
          </section>

          <section class="col-sm-6 mb-3">
            <fieldset>
              <legend><strong>Barrierefrei-Tool?</strong></legend>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="mrh-barrierefreiTool" name="tpl_cfg_barrierefreiTool" value="on"
                       <?php if(isset($t['tpl_cfg_barrierefreiTool']) && $t['tpl_cfg_barrierefreiTool']==='on') echo 'checked'; ?>>
                <label class="form-check-label" for="mrh-barrierefreiTool">Anzeigen (unten links)</label>
              </div>
            </fieldset>
          </section>

          <section class="col-sm-12">
            <input type="submit" name="submit-tplsettings" id="submit-tplsettings"
                   class="btn btn-success btn-lg w-100" value="Konfiguration speichern">
          </section>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== SEKTION 3: ZAHLUNGS- UND VERSANDLOGOS ===== -->
  <section class="card mb-4 mx-3">
    <header class="card-header" id="mrhHeadingLogos">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseLogos"
              aria-expanded="false" aria-controls="mrhCollapseLogos">
        <strong class="h5 mb-0"><i class="fa fa-credit-card me-2"></i>Zahlungs- und Versandlogos</strong>
      </button>
    </header>

    <div id="mrhCollapseLogos" class="accordion-collapse collapse"
         aria-labelledby="mrhHeadingLogos" data-bs-parent="#mrh-configurator">
      <div class="card-body">
        <form id="mrh-logosettings" class="row" method="post" action="">

          <section class="col-sm-12 mb-4">
            <strong>Zahlungslogos</strong>
            <div class="row mt-2">
<?php
$payments = ['vorkasse','paypal','kreditkarten','applepay','googlepay','amazon','klarna','lastschrift','rechnung'];
$active_payments = isset($l['payment']) ? $l['payment'] : [];
foreach ($payments as $p) {
    $checked = in_array($p, $active_payments) ? 'checked' : '';
    $plabel = ucfirst($p);
    if ($p === 'applepay') $plabel = 'Apple Pay';
    if ($p === 'googlepay') $plabel = 'Google Pay';
    if ($p === 'kreditkarten') $plabel = 'Kreditkarten';
    echo '<div class="col-sm-3 mb-2">';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="mrh_cfg_payment_logos[]" value="'.$p.'" id="mrh_pay_'.$p.'" '.$checked.'>';
    echo '<label class="form-check-label" for="mrh_pay_'.$p.'">';
    echo '<img height="36" width="36" src="'.DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/img/payment/zahlungsarten_'.$p.'.webp" alt="'.$plabel.'" loading="lazy"> '.$plabel;
    echo '</label>';
    echo '</div>';
    echo '</div>';
}
?>
            </div>
          </section>

          <section class="col-sm-12 mb-4">
            <strong>Versandlogos</strong>
            <div class="row mt-2">
<?php
$shippers = ['hermes','dhl','dpd','ups','gls','fedex'];
$active_shipping = isset($l['shipping']) ? $l['shipping'] : [];
foreach ($shippers as $sh) {
    $checked = in_array($sh, $active_shipping) ? 'checked' : '';
    $slabel = strtoupper($sh);
    if ($sh === 'hermes') $slabel = 'Hermes';
    if ($sh === 'fedex') $slabel = 'FedEx';
    echo '<div class="col-sm-3 mb-2">';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="checkbox" name="mrh_cfg_shipping_logos[]" value="'.$sh.'" id="mrh_ship_'.$sh.'" '.$checked.'>';
    echo '<label class="form-check-label" for="mrh_ship_'.$sh.'">';
    echo '<img height="36" width="36" src="'.DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/img/shipping/'.$sh.'.webp" alt="'.$slabel.'" loading="lazy"> '.$slabel;
    echo '</label>';
    echo '</div>';
    echo '</div>';
}
?>
            </div>
          </section>

          <section class="col-sm-12">
            <input type="submit" name="submit-logosettings" id="submit-logosettings"
                   class="btn btn-success btn-lg w-100" value="Logos speichern">
          </section>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== SEKTION 5: CUSTOM CSS ===== -->
  <section class="card mb-4 mx-3">
    <header class="card-header" id="mrhHeadingCustomCSS">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseCustomCSS"
              aria-expanded="false" aria-controls="mrhCollapseCustomCSS">
        <strong class="h5 mb-0"><i class="fa fa-code me-2"></i>Custom CSS</strong>
      </button>
    </header>

    <div id="mrhCollapseCustomCSS" class="accordion-collapse collapse"
         aria-labelledby="mrhHeadingCustomCSS" data-bs-parent="#mrh-configurator">
      <div class="card-body">
        <form id="mrh-customcss" method="post" action="">
          <section class="col-sm-12 mb-3">
            <p class="text-muted">Eigenes CSS eingeben. &Auml;nderungen werden <strong>sofort live</strong> auf der Seite angezeigt. Zum dauerhaften Speichern den Button unten verwenden.</p>
            <textarea id="mrh-custom-css-textarea" name="mrh_custom_css" class="form-control font-monospace"
                      rows="18" spellcheck="false"
                      placeholder="/* Eigenes CSS hier eingeben */&#10;.mein-element {&#10;    color: red;&#10;    font-size: 16px;&#10;}"><?php echo htmlspecialchars(isset($GLOBALS['mrh_custom_css']) ? $GLOBALS['mrh_custom_css'] : ''); ?></textarea>
          </section>

          <section class="col-sm-12">
            <input type="submit" name="submit-customcss" id="submit-customcss"
                   class="btn btn-success btn-lg w-100" value="Custom CSS speichern">
          </section>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== SEKTION 4: SOCIAL MEDIA ===== -->
  <section class="card mb-4 mx-3">
    <header class="card-header" id="mrhHeadingSocial">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseSocial"
              aria-expanded="false" aria-controls="mrhCollapseSocial">
        <strong class="h5 mb-0"><i class="fa-brands fa-instagram me-2"></i>Social Media Links</strong>
      </button>
    </header>

    <div id="mrhCollapseSocial" class="accordion-collapse collapse"
         aria-labelledby="mrhHeadingSocial" data-bs-parent="#mrh-configurator">
      <div class="card-body">
        <form id="mrh-socialsettings" class="row" method="post" action="">
          <section class="col-sm-12 mb-3">
            <p class="text-muted">Tragen Sie Ihre Social Media Links ein. Leere Felder werden nicht angezeigt.</p>
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
    echo '<div class="row mb-3">';
    echo '<div class="col-sm-12">';
    echo '<label for="mrh-social-'.$skey.'"><strong><i class="'.$info['icon'].' me-1"></i>'.$info['label'].'</strong></label>';
    echo '<input type="url" class="form-control" id="mrh-social-'.$skey.'" name="'.$skey.'" value="'.$val.'" placeholder="'.$info['placeholder'].'">';
    echo '</div>';
    echo '</div>';
}
?>
          </section>

          <section class="col-sm-12">
            <input type="submit" name="submit-socialsettings" id="submit-socialsettings"
                   class="btn btn-success btn-lg w-100" value="Social Media Links speichern">
          </section>
        </form>
      </div>
    </div>
  </section>

</div>
