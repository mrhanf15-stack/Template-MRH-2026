<?php
/* -----------------------------------------------------------------------------------------
   AdvancedSearch Blog Extension - JavaScript fuer Blog-Autocomplete
   
   Wird automatisch geladen ueber das Autoinclude System
   (templates/{TEMPLATE}/javascript/extra/*.js.php)
   
   Fuegt einen parallelen AJAX-Call fuer Blog-Ergebnisse in die
   bestehende Autocomplete-Suche ein.
   
   HINWEIS: Diese Datei wird moeglicherweise VOR jQuery geladen.
   Daher wird auf jQuery gewartet bevor die Initialisierung startet.
   
   v1.5.2: Sprache wird als Parameter an AJAX uebergeben, da
           $_SESSION['language'] im AJAX-Kontext unzuverlaessig ist.
   
   Copyright (c) 2026 AdvancedSearch
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// Nur laden wenn Autocomplete aktiv und Blog-Modul installiert
if (defined('SEARCH_AC_STATUS') && SEARCH_AC_STATUS == 'true'
    && defined('MODULE_ADVANCED_SEARCH_BLOG_STATUS') && MODULE_ADVANCED_SEARCH_BLOG_STATUS == 'true') {
?>
<script>
(function() {
  'use strict';
  
  var blogAcBaseUrl = '<?php echo DIR_WS_BASE; ?>';
  var blogAcMinLength = <?php echo (defined('SEARCH_AC_MIN_LENGTH') ? (int)SEARCH_AC_MIN_LENGTH : 3); ?>;
  var blogAcLanguage = '<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'german'; ?>';
  
  /**
   * Wartet bis jQuery verfuegbar ist und fuehrt dann den Callback aus.
   * Prueft alle 100ms ob window.jQuery existiert.
   * Timeout nach 15 Sekunden.
   */
  function waitForJQuery(callback) {
    var maxAttempts = 150; // 150 x 100ms = 15 Sekunden
    var attempts = 0;
    
    function check() {
      attempts++;
      if (typeof window.jQuery !== 'undefined') {
        callback(window.jQuery);
      } else if (attempts < maxAttempts) {
        setTimeout(check, 100);
      }
    }
    
    // Sofort pruefen, falls jQuery bereits geladen ist
    if (typeof window.jQuery !== 'undefined') {
      callback(window.jQuery);
    } else {
      setTimeout(check, 100);
    }
  }
  
  /**
   * Initialisiert die Blog-Autocomplete-Suche sobald jQuery verfuegbar ist.
   */
  waitForJQuery(function($) {
    
    var blogAcTimer = null;
    var blogAcDelay = 550; // etwas laenger als Produkt-AC (500ms)
    
    $(function() {
      
      // Container fuer Blog-Ergebnisse in der Suggestions-Box erstellen
      // Wird einmalig nach dem autoSuggestionsList div eingefuegt
      if ($('#autoSuggestionsList').length > 0 && $('#blogSuggestionsList').length === 0) {
        $('#autoSuggestionsList').after('<div id="blogSuggestionsList"></div>');
      }
      
      // Auf Eingabe im Suchfeld reagieren
      $('body').on('keydown paste cut input', '#inputString', function() {
        var $input = $(this);
        
        // Timer zuruecksetzen (Debounce)
        if (blogAcTimer) {
          clearTimeout(blogAcTimer);
        }
        
        blogAcTimer = setTimeout(function() {
          var keywords = $input.val();
          
          // Mindestlaenge pruefen
          if (!keywords || keywords.length < blogAcMinLength) {
            $('#blogSuggestionsList').html('');
            return;
          }
          
          // AJAX-Call fuer Blog-Ergebnisse
          // Sprache wird als Parameter uebergeben, da $_SESSION['language']
          // im AJAX-Kontext (ajax.php) unzuverlaessig ist
          $.ajax({
            dataType: 'json',
            type: 'post',
            url: blogAcBaseUrl + 'ajax.php?ext=get_autocomplete_blog',
            data: { keywords: keywords, language: blogAcLanguage },
            cache: false,
            async: true,
            success: function(data) {
              if (data !== null && typeof data === 'object') {
                if (data.result !== null && data.result !== undefined && data.result !== '') {
                  var html = data.result;
                  // decode_ajax nutzen falls verfuegbar
                  if (typeof window.decode_ajax === 'function') {
                    html = window.decode_ajax(html);
                  }
                  $('#blogSuggestionsList').html(html);
                  // Suggestions-Box anzeigen falls noch nicht sichtbar
                  if ($('#suggestions').is(':hidden')) {
                    $('#suggestions').slideDown();
                  }
                } else {
                  $('#blogSuggestionsList').html('');
                }
              }
            },
            error: function() {
              $('#blogSuggestionsList').html('');
            }
          });
        }, blogAcDelay);
      });
      
      // Blog-Ergebnisse leeren wenn Suggestions geschlossen werden
      var origAcClosing = window.ac_closing;
      window.ac_closing = function() {
        if (typeof origAcClosing === 'function') {
          origAcClosing();
        }
        setTimeout(function() {
          $('#blogSuggestionsList').html('');
        }, 150);
      };
      
    }); // Ende $(function() ...)
    
  }); // Ende waitForJQuery
  
})();
</script>
<?php
} // Ende if SEARCH_AC_STATUS && MODULE_ADVANCED_SEARCH_BLOG_STATUS
?>
