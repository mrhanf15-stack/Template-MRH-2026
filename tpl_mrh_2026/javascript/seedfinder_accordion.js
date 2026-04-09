/**
 * Seedfinder Filter Controller - v8.0.0
 * Datum: 09. April 2026
 * Autor: Mr. Hanf / Manus AI
 * 
 * Phase 3 Umbau:
 * - Desktop (≥992px): BS5 Modal (#mrhFilterModal)
 * - Mobile (<992px): BS5 Offcanvas (#mrhFilterOffcanvas)
 * - Grundfilter-Leiste: Button #mrh-open-filters öffnet Modal/Offcanvas
 * - Kein jQuery mehr → Vanilla JS + Bootstrap 5 API
 * - Alle DOM-Hooks für seedfinder_ajax.js bleiben erhalten
 * - Checkbox-Sortierung beibehalten
 */

(function() {
    'use strict';

    // Verhindere doppelte Initialisierung
    if (window.SeedfindAccordionInitialized) {
        console.log('⚠️ SeedfindFilterController bereits initialisiert - überspringe');
        return;
    }
    window.SeedfindAccordionInitialized = true;

    // Warte bis DOM geladen ist
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFilterController);
    } else {
        initFilterController();
    }

    function initFilterController() {
        console.log('✅ SeedfindFilterController v8.0.0 initialisiert');

        // Elemente
        var openBtn = document.getElementById('mrh-open-filters');
        var modalEl = document.getElementById('mrhFilterModal');
        var offcanvasEl = document.getElementById('mrhFilterOffcanvas');

        // Bootstrap 5 Instanzen (lazy init)
        var bsModal = null;
        var bsOffcanvas = null;

        // Breakpoint-Check: Desktop ≥992px
        function isDesktop() {
            return window.innerWidth >= 992;
        }

        /**
         * Filter-Button in Grundfilter-Leiste: Öffnet Modal (Desktop) oder Offcanvas (Mobile)
         */
        if (openBtn) {
            openBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🎯 Filter-Button geklickt, isDesktop:', isDesktop());

                if (isDesktop() && modalEl) {
                    // Desktop: Bootstrap 5 Modal öffnen
                    if (!bsModal) {
                        bsModal = new bootstrap.Modal(modalEl, {
                            backdrop: true,
                            keyboard: true,
                            focus: true
                        });
                    }
                    bsModal.show();
                    console.log('📂 Desktop Modal geöffnet');
                } else if (offcanvasEl) {
                    // Mobile: Bootstrap 5 Offcanvas öffnen
                    if (!bsOffcanvas) {
                        bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl, {
                            backdrop: true,
                            keyboard: true,
                            scroll: false
                        });
                    }
                    bsOffcanvas.show();
                    console.log('📱 Mobile Offcanvas geöffnet');
                }
            });
        }

        /**
         * Sync: Wenn Modal/Offcanvas geschlossen wird, Filter-Count aktualisieren
         */
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function() {
                console.log('📁 Desktop Modal geschlossen');
                syncFilterCounts();
            });
        }
        if (offcanvasEl) {
            offcanvasEl.addEventListener('hidden.bs.offcanvas', function() {
                console.log('📁 Mobile Offcanvas geschlossen');
                syncFilterCounts();
            });
        }

        /**
         * Suchen-Buttons: Modal/Offcanvas schließen nach Klick
         * (seedfinder_ajax.js handled die eigentliche Suche)
         */
        var searchDesktop = document.getElementById('search-filters-desktop');
        var searchMobile = document.getElementById('search-filters-mobile');

        if (searchDesktop) {
            searchDesktop.addEventListener('click', function() {
                console.log('🔍 Desktop Suchen geklickt');
                if (bsModal) {
                    // Kurze Verzögerung damit seedfinder_ajax.js die Checkboxen lesen kann
                    setTimeout(function() {
                        bsModal.hide();
                    }, 100);
                }
            });
        }
        if (searchMobile) {
            searchMobile.addEventListener('click', function() {
                console.log('🔍 Mobile Suchen geklickt');
                if (bsOffcanvas) {
                    setTimeout(function() {
                        bsOffcanvas.hide();
                    }, 100);
                }
            });
        }

        /**
         * Reset-Buttons: Modal/Offcanvas schließen nach Reset
         * (seedfinder_ajax.js handled den eigentlichen Reset)
         */
        var resetDesktop = document.getElementById('reset-filters-desktop');
        var resetMobile = document.getElementById('reset-filters-mobile');

        if (resetDesktop) {
            resetDesktop.addEventListener('click', function() {
                console.log('🔄 Desktop Reset geklickt');
                if (bsModal) {
                    setTimeout(function() {
                        bsModal.hide();
                    }, 200);
                }
            });
        }
        if (resetMobile) {
            resetMobile.addEventListener('click', function() {
                console.log('🔄 Mobile Reset geklickt');
                if (bsOffcanvas) {
                    setTimeout(function() {
                        bsOffcanvas.hide();
                    }, 200);
                }
            });
        }

        /**
         * Active Filter Count synchronisieren
         * Aktualisiert alle Badge-Elemente mit der Anzahl aktiver Filter
         */
        function syncFilterCounts() {
            var count = document.querySelectorAll('.filter-checkbox:checked').length;
            
            var badges = [
                document.getElementById('active-filter-count'),
                document.getElementById('active-filter-count-modal'),
                document.getElementById('active-filter-count-mobile')
            ];

            badges.forEach(function(badge) {
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? '' : 'none';
                }
            });

            console.log('📊 Filter Count sync:', count);
        }

        /**
         * Mobile Accordion: Chevron-Rotation bei Collapse Toggle
         */
        var mobileAccordion = document.getElementById('filter-accordion-mobile');
        if (mobileAccordion) {
            var collapseEls = mobileAccordion.querySelectorAll('.collapse');
            collapseEls.forEach(function(collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function() {
                    var btn = mobileAccordion.querySelector('[data-bs-target="#' + collapseEl.id + '"]');
                    if (btn) {
                        var chevron = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
                        if (chevron) {
                            chevron.classList.remove('fa-chevron-down');
                            chevron.classList.add('fa-chevron-up');
                        }
                    }
                });
                collapseEl.addEventListener('hide.bs.collapse', function() {
                    var btn = mobileAccordion.querySelector('[data-bs-target="#' + collapseEl.id + '"]');
                    if (btn) {
                        var chevron = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
                        if (chevron) {
                            chevron.classList.remove('fa-chevron-up');
                            chevron.classList.add('fa-chevron-down');
                        }
                    }
                });
            });
        }

        // Initial sync
        syncFilterCounts();

        console.log('✅ SeedfindFilterController v8.0.0 bereit');
    }

})();


/**
 * Checkbox-Sortierung v8.0.0
 * Sortiert Checkboxen: Checked → Enabled → Disabled
 * Blendet disabled Checkboxen aus (außer checked)
 */
function sortCheckboxes() {
    console.log('✅ Checkbox-Sort v8.0.0: Sortiere und blende disabled Items aus');
    
    var totalContainers = 0;
    var totalItems = 0;
    
    // Container für Desktop (Modal) und Mobile (Offcanvas)
    var desktopContainers = document.querySelectorAll('#filter-accordion-desktop .filter-values-container');
    var mobileContainers = document.querySelectorAll('#filter-accordion-mobile .card-body');
    
    console.log('Desktop containers:', desktopContainers.length, '| Mobile containers:', mobileContainers.length);
    
    // Kombiniere beide Arrays
    var allContainers = [];
    desktopContainers.forEach(function(c) { allContainers.push(c); });
    mobileContainers.forEach(function(c) { allContainers.push(c); });
    
    allContainers.forEach(function(container) {
        var checkboxItems = container.querySelectorAll('.form-check');
        
        if (checkboxItems.length === 0) return;
        
        // Konvertiere zu Array für Sortierung
        var itemsArray = Array.from(checkboxItems);
        
        // Sortierung: Checked → Enabled → Disabled
        itemsArray.sort(function(a, b) {
            var checkboxA = a.querySelector('input[type="checkbox"]');
            var checkboxB = b.querySelector('input[type="checkbox"]');
            
            var checkedA = checkboxA ? checkboxA.checked : false;
            var checkedB = checkboxB ? checkboxB.checked : false;
            var disabledA = checkboxA ? checkboxA.disabled : false;
            var disabledB = checkboxB ? checkboxB.disabled : false;
            
            if (checkedA && !checkedB) return -1;
            if (!checkedA && checkedB) return 1;
            if (!disabledA && disabledB) return -1;
            if (disabledA && !disabledB) return 1;
            return 0;
        });
        
        // DOM neu aufbauen
        container.innerHTML = '';
        
        itemsArray.forEach(function(item) {
            var checkbox = item.querySelector('input[type="checkbox"]');
            var disabled = checkbox ? checkbox.disabled : false;
            var checked = checkbox ? checkbox.checked : false;
            
            // Disabled Items ausblenden (außer checked)
            if (disabled && !checked) {
                item.style.display = 'none';
            } else {
                item.style.display = '';
            }
            
            container.appendChild(item);
        });
        
        totalContainers++;
        totalItems += checkboxItems.length;
    });
    
    console.log('✅ Checkbox-Sort: ' + totalContainers + ' Container, ' + totalItems + ' Items');
}

// Global exportieren
window.seedfinderSortCheckboxes = sortCheckboxes;
