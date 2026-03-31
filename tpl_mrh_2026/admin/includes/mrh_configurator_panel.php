<?php
/* =====================================================================
   MRH 2026 Template – Konfigurator Panel (reine PHP-Version)
   
   Wird eingebunden via source/boxes/box_admin_extra.php
   Benötigt: admin/includes/mrh_configurator.php (PHP-Backend)
   
   Sektionen:
   1. Farben individualisieren (inkl. Menü + Topbar + Sticky)
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
              <div class="col-sm-6 mb-3">
                <label for="mrh-primary"><strong>Prim&auml;rfarbe</strong></label>
                <input id="mrh-primary" type="text" name="mrh-primary"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-primary'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-primary'); ?>"></div>
              </div>
              <div class="col-sm-6 mb-3">
                <label for="mrh-secondary"><strong>Sekund&auml;rfarbe</strong></label>
                <input id="mrh-secondary" type="text" name="mrh-secondary"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-secondary'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-secondary'); ?>"></div>
              </div>
            </div>
          </section>

          <hr class="mx-3">

          <!-- Men&uuml;-Farben (NEU) -->
          <section class="col-sm-12 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-bars me-1"></i> Hauptnavigation (Men&uuml;-Leiste)
              <span class="badge bg-success ms-2">NEU</span>
            </h6>
            <div class="row">
<?php
$menu_fields = [
    'mrh-menu-bg'        => 'Men&uuml; Hintergrund',
    'mrh-menu-text'      => 'Men&uuml; Textfarbe',
    'mrh-menu-hover-bg'  => 'Men&uuml; Hover',
    'mrh-menu-active-bg' => 'Men&uuml; Aktiv',
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

          <!-- Topbar-Farben (NEU) -->
          <section class="col-sm-12 mb-3">
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-grip-lines me-1"></i> Topbar (Trust-Leiste)
              <span class="badge bg-success ms-2">NEU</span>
            </h6>
            <div class="row">
              <div class="col-sm-6 mb-3">
                <label for="mrh-topbar-bg"><strong>Topbar Hintergrund</strong></label>
                <input id="mrh-topbar-bg" type="text" name="mrh-topbar-bg"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-topbar-bg'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-topbar-bg'); ?>"></div>
              </div>
              <div class="col-sm-6 mb-3">
                <label for="mrh-topbar-text"><strong>Topbar Textfarbe</strong></label>
                <input id="mrh-topbar-text" type="text" name="mrh-topbar-text"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-topbar-text'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-topbar-text'); ?>"></div>
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
    'mrh-bg-color'      => 'Hintergrundfarbe 1',
    'mrh-bg-color-2'    => 'Hintergrundfarbe 2',
    'mrh-bg-productbox' => 'Produktboxen Hintergrund',
    'mrh-bg-footer'     => 'Footer Hintergrund',
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
    'mrh-text-standard'        => 'Standard Schriftfarbe',
    'mrh-text-headings'        => '&Uuml;berschriften Schriftfarbe',
    'mrh-text-button'          => 'Schriftfarbe in Buttons &amp; Badges',
    'mrh-text-footer'          => 'Schriftfarbe Text &amp; Links im Footer',
    'mrh-text-footer-headings' => '&Uuml;berschriften im Footer',
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

          <!-- Sticky Header (NEU) -->
          <section class="col-sm-12 mb-3">
            <hr>
            <h6 class="text-muted text-uppercase small mb-3">
              <i class="fa fa-thumbtack me-1"></i> Sticky Header
              <span class="badge bg-success ms-2">NEU</span>
            </h6>
            <div class="row">
              <div class="col-sm-6 mb-3">
                <label for="mrh-sticky-bg"><strong>Sticky Header Hintergrund</strong></label>
                <input id="mrh-sticky-bg" type="text" name="mrh-sticky-bg"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-sticky-bg'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-sticky-bg'); ?>"></div>
              </div>
              <div class="col-sm-6 mb-3">
                <label for="mrh-sticky-text"><strong>Sticky Header Textfarbe</strong></label>
                <input id="mrh-sticky-text" type="text" name="mrh-sticky-text"
                       class="form-control colorpicker-element"
                       value="<?php echo mrh_cv($c,'mrh-sticky-text'); ?>">
                <div class="demo-farbe mt-1" style="background:<?php echo mrh_cv($c,'mrh-sticky-text'); ?>"></div>
              </div>
            </div>
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
    'facebook'  => ['label' => 'Facebook',   'icon' => 'fa-brands fa-facebook',  'placeholder' => 'https://www.facebook.com/IhrName'],
    'twitter'   => ['label' => 'X (Twitter)', 'icon' => 'fa-brands fa-x-twitter','placeholder' => 'https://x.com/IhrName'],
    'instagram' => ['label' => 'Instagram',  'icon' => 'fa-brands fa-instagram', 'placeholder' => 'https://www.instagram.com/IhrName'],
    'tiktok'    => ['label' => 'TikTok',     'icon' => 'fa-brands fa-tiktok',    'placeholder' => 'https://www.tiktok.com/@IhrName'],
    'youtube'   => ['label' => 'YouTube',    'icon' => 'fa-brands fa-youtube',   'placeholder' => 'https://www.youtube.com/@IhrName'],
    'pinterest' => ['label' => 'Pinterest',  'icon' => 'fa-brands fa-pinterest', 'placeholder' => 'https://www.pinterest.com/IhrName'],
    'linkedin'  => ['label' => 'LinkedIn',   'icon' => 'fa-brands fa-linkedin',  'placeholder' => 'https://www.linkedin.com/company/IhrName'],
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

  <!-- ===== SEKTION 5: ERWEITERTE KONFIGURATION (MRH Dashboard Module) ===== -->
  <section class="card mb-4 mx-3">
    <header class="card-header" id="mrhHeadingDashboard">
      <button class="btn btn-link text-start w-100 p-0" type="button"
              data-bs-toggle="collapse" data-bs-target="#mrhCollapseDashboard"
              aria-expanded="false" aria-controls="mrhCollapseDashboard">
        <strong class="h5 mb-0"><i class="fa fa-cogs me-2"></i>Erweiterte Konfiguration</strong>
      </button>
    </header>

    <div id="mrhCollapseDashboard" class="accordion-collapse collapse"
         aria-labelledby="mrhHeadingDashboard" data-bs-parent="#mrh-configurator">
      <div class="card-body">

        <!-- Mega-Men&uuml; Manager -->
        <h6 class="text-muted text-uppercase small mb-3">
          <i class="fa fa-bars me-1"></i> Mega-Men&uuml; Manager
          <span class="badge bg-success ms-2">NEU</span>
        </h6>
        <p class="text-muted small mb-3">
          Konfiguriere die Spalten und Kategorien f&uuml;r das Mega-Dropdown-Men&uuml;.
          Kategorien werden direkt aus der Datenbank geladen. Die Sortierung erfolgt per Drag &amp; Drop.
        </p>

<?php
// Dashboard-Kern laden
$dashboard_core = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/includes/mrh_dashboard.php';
if (file_exists($dashboard_core)) {
    require_once($dashboard_core);
}

// Mega-Men&uuml; Manager Admin-UI laden
$mega_menu_admin = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/admin/mrh_dashboard/modules/mega_menu/admin.php';
if (file_exists($mega_menu_admin)) {
    include($mega_menu_admin);
} else {
    echo '<div class="alert alert-warning">Mega-Men&uuml; Manager nicht gefunden. Bitte pr&uuml;fen Sie die Installation.</div>';
}
?>

      </div>
    </div>
  </section>

</div>
