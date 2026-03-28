/**
 * Seedfinder Cards Event Handler
 * Handles click events for Anfaenger and Profi Finder buttons
 * Version: 2.1.0 - Fremdsprachen-Fix
 */
(function() {
    'use strict';

    /**
     * v2.1.0: Dynamische Base-URL fuer Fremdsprachen-Support
     * Nutzt SEEDFINDER_BASE_URL (von Smarty/Template gesetzt) oder
     * erkennt den Pfad aus der aktuellen URL
     */
    function getSeedfinderBaseUrl() {
        if (typeof SEEDFINDER_BASE_URL !== 'undefined' && SEEDFINDER_BASE_URL) {
            return SEEDFINDER_BASE_URL;
        }
        // Fallback: Aktuellen Pfad nutzen (z.B. /en/seedfinder)
        var path = window.location.pathname.replace(/\/$/, '');
        if (path && path !== '/' && path.indexOf('seedfinder') !== -1) {
            return path;
        }
        // Letzter Fallback
        return 'seedfinder';
    }

    function getSeedfinderPhpUrl() {
        if (typeof SEEDFINDER_PHP_URL !== 'undefined' && SEEDFINDER_PHP_URL) {
            return SEEDFINDER_PHP_URL;
        }
        return 'seedfinder.php';
    }

    function initCards() {
        // Anfaenger Finder Button
        var beginnerBtn = document.getElementById('beginner-finder-btn');
        if (beginnerBtn) {
            beginnerBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = document.getElementById('beginnerWizardModal');
                if (modal) {
                    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
                        jQuery('#beginnerWizardModal').modal('show');
                    } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    } else {
                        modal.classList.add('show');
                        modal.style.display = 'block';
                        document.body.classList.add('modal-open');
                    }
                }
            });
        }

        // Profi Finder Button
        var proBtn = document.getElementById('pro-finder-btn');
        if (proBtn) {
            proBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = getSeedfinderBaseUrl() + '?stage=2&category=581210&open_filters=1';
            });
        }
    }

    // Warte auf DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCards);
    } else {
        initCards();
    }

})();