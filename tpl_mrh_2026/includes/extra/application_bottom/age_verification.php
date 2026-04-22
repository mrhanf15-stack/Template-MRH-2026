<?php
/**
 * age_verification.php
 *
 * Age verification modal – BS5.3 + Vanilla JS rewrite
 * Wireframe design, no jQuery dependency
 *
 * @version     2.0.0 - 22. Apr 2026
 * @author      Original: Jens Justen <support@web-looks.de>
 *              Rewrite:  MRH N-Trade GmbH (BS5.3 + Vanilla JS)
 * @copyright   Copyright (c) 2019-2026
 * @link        http://www.web-looks.de
 * @package     age verification
 * @since       Version 1.0
 */

// check for bots
$is_bot = (!empty($truncate_session_id) ? true : (xtc_check_agent() == 1 ? true : false));

if (defined('MODULE_AGE_VERIFICATION_STATUS')
        && MODULE_AGE_VERIFICATION_STATUS == 'true'
        && empty($_COOKIE['age_verification'])
        && !$is_bot
) {
        $output = '';

        // set expires date
        $expires = (MODULE_AGE_VERIFICATION_DAYS != '' ? (int)MODULE_AGE_VERIFICATION_DAYS : 0);
        $expires_js = '';
        if ($expires > 0) {
                $date = new Datetime('+'.$expires.' days');
                $expires_js = $date->format('r');
        }

        // Resolve template image path dynamically
        $tpl_dir = defined('CURRENT_TEMPLATE') ? CURRENT_TEMPLATE : 'tpl_mrh_2026';
        $logo_path = '/templates/' . $tpl_dir . '/img/logo_head.png';

        // Escape texts for JS
        $title    = addslashes(defined('TEXT_AGE_VERIFICATION_TITLE') ? TEXT_AGE_VERIFICATION_TITLE : '<br>Bitte bestätige dein Alter<br>');
        $subtitle = (defined('TEXT_AGE_VERIFICATION_SUBTITLE') && TEXT_AGE_VERIFICATION_SUBTITLE != '') ? addslashes(TEXT_AGE_VERIFICATION_SUBTITLE) : '';
        $btn_ok   = addslashes(defined('TEXT_AGE_VERIFICATION_BUTTON_CONFIRM') ? TEXT_AGE_VERIFICATION_BUTTON_CONFIRM : 'Ich bin 18 oder älter');
        $btn_no   = addslashes(defined('TEXT_AGE_VERIFICATION_BUTTON_CANCEL') ? TEXT_AGE_VERIFICATION_BUTTON_CANCEL : 'Ich bin unter 18');


        /**
         * default modal (modalBox) – legacy, kept for backwards compatibility
         */
        if (MODULE_AGE_VERIFICATION_MODAL == 'default') {

                // include modalBox script
                $output .= '<script src="'.DIR_WS_BASE.'includes/javascript/modalBox.min.js" type="text/javascript"></script>'."\n";

                $output .= '<script type="text/javascript">'."\n";
                $output .= '$(document).ready(function () {';
                $output .= 'if (document.cookie.indexOf("age_verification=true") < 0) {';
                $output .= 'var modalContent = \'<div class="content"><h3 class="title">'.$title.'</h3>'.($subtitle ? '<div class="subtitle">'.$subtitle.'</div>' : '').'<button class="button-confim">'.$btn_ok.'</button><div class="button-cancel-wrap"><a href="javascript:history.back()" class="button-cancel">'.$btn_no.'</a></div></div>\';';
                $output .= '$("html").addClass("no-scroll");';
                $output .= 'var modalDiv = $("<div />").attr("id", "ageVerification").html(modalContent);';
                $output .= '$("body").append(modalDiv);';
                $output .= '$("#ageVerification").modalBox({iconClose:false,keyClose:false,bodyClose:false,width:'.MODULE_AGE_VERIFICATION_WIDTH.',height:'.MODULE_AGE_VERIFICATION_HEIGHT.'});';
                $output .= '$("#ageVerification .button-confim").click(function(){';
                $output .= 'document.cookie="age_verification=true; expires='.$expires_js.'; path=/";';
                $output .= '$("#ageVerification").modalBox("close");';
                $output .= '$("html").removeClass("no-scroll");';
                $output .= '});';
                $output .= '}';
                $output .= '});';
                $output .= '</script>'."\n";


        /**
         * Bootstrap 5.3 modal – Vanilla JS, Wireframe design
         */
        } else if (MODULE_AGE_VERIFICATION_MODAL == 'bootstrap') {

                $output .= <<<AGEHTML

<!-- Age Verification Modal (BS5.3 / Vanilla JS / v2.0.0) -->
<div class="modal fade" id="ageVerification" tabindex="-1" aria-labelledby="ageVerificationLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
      <div class="modal-body text-center px-4 pt-4 pb-3">
        <img src="{$logo_path}" alt="Mr. Hanf" class="img-fluid mb-3" style="max-height:120px;">
        <h4 class="fw-bold mb-2" id="ageVerificationLabel">Bitte bestätige dein Alter</h4>
        <p class="text-secondary mb-1">Der Inhalt ist nur für Erwachsene ab <span class="text-danger fw-bold">18+</span> bestimmt.</p>
AGEHTML;

                if ($subtitle) {
                        $output .= '<p class="text-muted small mb-3">' . TEXT_AGE_VERIFICATION_SUBTITLE . '</p>';
                }

                $output .= <<<AGEHTML2
      </div>
      <div class="modal-footer flex-column border-0 px-4 pb-4 pt-0 gap-2">
        <button type="button" class="btn btn-success w-100 fw-semibold py-2 age-confirm">{$btn_ok}</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary w-100 py-2 age-cancel">{$btn_no}</a>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  if(document.cookie.indexOf('age_verification=true')>=0) return;
  var el=document.getElementById('ageVerification');
  if(!el) return;
  var m=new bootstrap.Modal(el,{keyboard:false,backdrop:'static'});
  m.show();
  el.querySelector('.age-confirm').addEventListener('click',function(){
    document.cookie='age_verification=true; expires={$expires_js}; path=/; SameSite=Lax';
    m.hide();
  });
  el.addEventListener('hidden.bs.modal',function(){
    el.remove();
    var bd=document.querySelector('.modal-backdrop');
    if(bd) bd.remove();
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
  });
})();
</script>
<!-- / Age Verification Modal -->
AGEHTML2;

        }


    // output
        echo "\n<!-- Diese Seite nutzt die Alterspruefung v2.0.0 (BS5.3) - Original: https://www.web-looks.de --> \n".
                 $output.
                 "\n<!-- / Alterspruefung -->\n";
}

?>
