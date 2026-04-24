<?php
  /* --------------------------------------------------------------
   $Id: autocomplete.js.php 16228 2024-12-04 12:18:51Z GTB $
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   MRH 2026: Erweitert für alle Suchfelder (box_search, sticky_header, bottom_bar)
   Jedes Suchfeld bekommt ein eigenes Dropdown, positioniert relativ zum Input.
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script>
  <?php if (SEARCH_AC_STATUS == 'true') { ?>
  (function() {
    'use strict';

    var session_id = '<?php echo xtc_session_id(); ?>';
    var ac_url     = '<?php echo DIR_WS_BASE; ?>ajax.php?ext=get_autocomplete&MODsid=' + session_id;
    var ac_blog_url= '<?php echo DIR_WS_BASE; ?>ajax.php?ext=get_autocomplete_blog&MODsid=' + session_id;
    var ac_timer   = null;
    var ac_active  = null; // aktuell aktives Dropdown-Element

    /* ── Selektoren aller Suchfelder ── */
    var AC_SELECTOR = [
      '#inputString',                                       /* box_search.html (Desktop + Mobile) */
      'form[name="sticky_find"] input[name="keywords"]',    /* sticky_header.html */
      '.mrh-search-overlay-form input[name="keywords"]'     /* bottom_bar.html (Mobile Overlay) */
    ].join(', ');

    /* ── Dropdown erstellen oder wiederverwenden ── */
    function ac_get_dropdown(input) {
      var $input = $(input);
      var $drop  = $input.data('ac-dropdown');
      if ($drop && $drop.length) return $drop;

      /* Neues Dropdown erstellen */
      $drop = $('<div class="mrh-ac-dropdown" style="display:none;"></div>');

      /* Positionierung: relativ zum nächsten position:relative Parent */
      var $wrap = $input.closest('.input-group, .d-flex, .mrh-search-overlay-form, form');
      if ($wrap.length) {
        $wrap.css('position', 'relative');
        $wrap.append($drop);
      } else {
        $input.after($drop);
      }

      $input.data('ac-dropdown', $drop);
      return $drop;
    }

    /* ── HTML-Entities dekodieren ── */
    function ac_decode(str) {
      var ta = document.createElement('textarea');
      ta.innerHTML = str;
      return ta.value;
    }

    /* ── AJAX-Aufruf ── */
    function ac_fetch(input) {
      var $input = $(input);
      var query  = $.trim($input.val());
      var $drop  = ac_get_dropdown(input);

      if (query.length < <?php echo (defined('SEARCH_AC_MIN_LENGTH') ? (int)SEARCH_AC_MIN_LENGTH : 3); ?>) {
        $drop.slideUp(150);
        return;
      }

      /* Form-Daten sammeln (keywords + ggf. categories_id) */
      var $form = $input.closest('form');
      var post_params = $form.length ? $form.serialize() : 'keywords=' + encodeURIComponent(query);

      $.ajax({
        dataType: 'json',
        type: 'post',
        url: ac_url,
        data: post_params,
        cache: false,
        success: function(data) {
          if (data && data.result) {
            $drop.html(ac_decode(data.result));
            ac_active = $drop;
            $drop.slideDown(200);
            /* Blog-Ergebnisse nachladen */
            ac_fetch_blog(query, $drop);
          } else {
            $drop.slideUp(150);
          }
        }
      });
    }

    /* ── Blog-Ergebnisse nachladen ── */
    function ac_fetch_blog(query, $drop) {
      $.ajax({
        dataType: 'json',
        type: 'post',
        url: ac_blog_url,
        data: { keywords: query },
        cache: false,
        success: function(data) {
          if (data && data.result) {
            $drop.append(ac_decode(data.result));
          }
        }
      });
    }

    /* ── Event-Binding: Input-Events auf alle Suchfelder ── */
    $('body').on('keydown paste cut input', AC_SELECTOR, function() {
      var self = this;
      clearTimeout(ac_timer);
      ac_timer = setTimeout(function() {
        ac_fetch(self);
      }, 350);
    });

    /* ── Schließen bei Klick außerhalb ── */
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.mrh-ac-dropdown').length
          && !$(e.target).closest(AC_SELECTOR).length) {
        $('.mrh-ac-dropdown').slideUp(150);
        ac_active = null;
      }
    });

    /* ── Keyboard-Navigation (Escape schließt) ── */
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape' && ac_active) {
        ac_active.slideUp(150);
        ac_active = null;
      }
    });

    /* ── Altes #suggestions Dropdown ausblenden (Kompatibilität) ── */
    $('#suggestions').remove();

    <?php if(defined('SEARCH_AC_CATEGORIES') && SEARCH_AC_CATEGORIES == 'true') { ?>
    $('body').on('change', '#cat_search', function() {
      var $input = $(this).closest('form').find('input[name="keywords"]');
      if ($input.length && $.trim($input.val()).length >= <?php echo (defined('SEARCH_AC_MIN_LENGTH') ? (int)SEARCH_AC_MIN_LENGTH : 3); ?>) {
        ac_fetch($input[0]);
      }
    });
    <?php } ?>

    /* ── Globale ac_closing() für Kompatibilität mit default.js.php ── */
    window.ac_closing = function() {
      $('.mrh-ac-dropdown').slideUp(150);
      ac_active = null;
    };

  })();
  <?php } ?>
</script>
