/**
 * Seedfinder Filter Reset Modal Opener
 * Version: 3.0.0
 * 
 * Öffnet das Filter-Modal automatisch nach dem Zurücksetzen der Filter.
 * Funktioniert auf Desktop UND Mobil.
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var filtersReset = urlParams.get('filters_reset');
        
        if (filtersReset === '1') {
            setTimeout(function() {
                $('#seedfinder-filter-modal').modal('show');
                
                urlParams.delete('filters_reset');
                var newUrl = window.location.pathname + '?' + urlParams.toString();
                window.history.replaceState({}, '', newUrl);
            }, 500);
        }
    });
    
})(jQuery);
