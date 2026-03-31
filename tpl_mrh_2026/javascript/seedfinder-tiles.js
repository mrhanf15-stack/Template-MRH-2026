/**
 * Seedfinder Tiles JavaScript
 * Event-Handler für Anfänger/Profi Finder Kacheln
 * Version: 2.0.0
 */

(function() {
    'use strict';

    /**
     * Initialisierung mit jQuery
     */
    function initTiles($) {
        // Anfänger Tile - Öffnet Wizard Modal
        $('#beginner-tile').on('click', function(e) {
            e.preventDefault();
            var modal = $('#beginnerWizardModal');
            if (modal.length) {
                modal.modal('show');
            }
        });

        // Profi Tile - Weiterleitung zu Kategorie
        $('#profi-tile').on('click', function(e) {
            e.preventDefault();
            window.location.href = 'seedfinder.php?stage=2&category=581210';
        });

        // Hover-Effekt (zusätzlich zu CSS)
        $('.seedfinder-tile-card').hover(
            function() {
                $(this).css('transform', 'translateY(-5px)');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
            }
        );
    }

    /**
     * Initialisierung mit Vanilla JavaScript (Fallback)
     */
    function initTilesVanilla() {
        var beginnerTile = document.getElementById('beginner-tile');
        var profiTile = document.getElementById('profi-tile');

        if (beginnerTile) {
            beginnerTile.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = document.getElementById('beginnerWizardModal');
                if (modal) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    }
                }
            });
        }

        if (profiTile) {
            profiTile.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = 'seedfinder.php?stage=2&category=581210';
            });
        }
    }

    /**
     * Hauptinitialisierung
     */
    if (typeof jQuery === 'undefined') {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTilesVanilla);
        } else {
            initTilesVanilla();
        }
    } else {
        jQuery(document).ready(function($) {
            initTiles($);
        });
    }

})();
