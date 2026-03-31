/**
 * Seedfinder Modal JS v8.1.1 - Auto-Count-Update beim Seitenladen
 * 
 * v8.1.1 - 04. Mar 2026:
 * - FIX: Beim Seitenladen mit aktiven Filtern sofort Batch-Update ausfuehren
 *   Haupt-Filter Counts werden jetzt korrekt angezeigt nach Modal "Filter anwenden"
 *
 * v8.1.0 - 03. Mar 2026:
 * - FIX: Alle URLs dynamisch ueber SEEDFINDER_BASE_URL / SEEDFINDER_PHP_URL
 * - Fremdsprachen (en, es, fr, tr) funktionieren jetzt korrekt
 * - getSeedfinderBaseUrl() / getSeedfinderPhpUrl() Hilfsfunktionen
 *
 * v8.0.0 - 02. Mar 2026:
 * - PERFORMANCE: 3 AJAX-Requests -> 1 Batch-Request (ajax=batch_update)
 *   Spart 2 HTTP-Roundtrips pro Filter-Change (besonders auf Mobile spuerbar)
 * - PERFORMANCE: Batch-Response enthaelt filter_counts + category_counts + product_count
 * - Alle v7.3.0 Features beibehalten (Mobile-Fix, Sub-Accordion, etc.)
 *
 * v7.3.0 - 01. Mar 2026:
 * - FIX: Mobile Checkboxen in ALLEN Funktionen beruecksichtigt
 * - NEU: getAllCheckedFilters() - Zentrale deduplizierte Filter-Sammlung
 *
 * v7.2.0 - 27. Feb 2026:
 * - NEU: updateCategoryCounts() + applyCategoryCountUpdates()
 *
 * v7.1.0 - 27. Feb 2026:
 * - NEU: transformToSubAccordions() + Sub-Accordion Highlights
 *
 * v7.0.0 - 27. Feb 2026:
 * - Debouncing + Request-Cancellation + Production-Ready
 */
(function($) {
    'use strict';
    
    // Globale Variablen
    let currentCategoryId = null;
    let isUpdating = false;
    let isMobileView = false;
    
    // v8.0: Ein einziger XHR + Timer fuer den Batch-Request
    let currentBatchXHR = null;
    let batchUpdateTimer = null;
    
    /**
     * v8.1.0: Dynamische Base-URL fuer SEO-Links (Seitennavigation)
     * Gibt z.B. /seedfinder, /en/seedfinder, /fr/chercheur-de-graines zurueck
     */
    function getSeedfinderBaseUrl() {
        if (typeof SEEDFINDER_BASE_URL !== 'undefined' && SEEDFINDER_BASE_URL) {
            return SEEDFINDER_BASE_URL;
        }
        var path = window.location.pathname;
        if (path.length > 1 && path.endsWith('/')) {
            path = path.slice(0, -1);
        }
        if (path.match(/(seedfinder|seed-finder|chercheur-de-graines|buscador-de-semillas)$/i)) {
            return path;
        }
        var langMatch = path.match(/^\/(en|es|fr|tr)(\/|$)/);
        if (langMatch) {
            return '/' + langMatch[1] + '/seedfinder';
        }
        return '/seedfinder';
    }
    
    /**
     * v8.1.0: Direkte PHP-URL fuer AJAX-Requests
     */
    function getSeedfinderPhpUrl() {
        if (typeof SEEDFINDER_PHP_URL !== 'undefined' && SEEDFINDER_PHP_URL) {
            return SEEDFINDER_PHP_URL;
        }
        return 'seedfinder.php';
    }
    
    // Initialisierung
    $(document).ready(function() {
        initSeedfinder();
        
        setTimeout(function() {
            sortFilterOptions();
            updateCategoryFilterBadges();
            updateActiveFilterBadges();
        }, 100);
        
        checkAndOpenModal();
    });
    
    function initSeedfinder() {
        const urlParams = new URLSearchParams(window.location.search);
        currentCategoryId = urlParams.get('category');
        
        if (!currentCategoryId) {
            return;
        }
        
        setupEventListeners();
        initMobileAccordion();
        updateActiveFilterBadges();
        toggleResetButtons();
        updateCategoryFilterBadges();
        
        // v8.1.1: Beim Seitenladen mit aktiven Filtern sofort Counts aktualisieren
        // Damit nach "Filter anwenden" im Modal die Haupt-Filter korrekte Counts zeigen
        var searchStr = window.location.search;
        if (searchStr.indexOf('filter') !== -1 || searchStr.indexOf('filter%5B') !== -1) {
            setTimeout(function() {
                executeBatchUpdate();
            }, 200);
        }
    }
    
    /**
     * Mobile Accordion initialisieren
     */
    function initMobileAccordion() {
        if ($('#filter-accordion-mobile').length === 0) {
            return;
        }
        
        handleResponsiveLayout();
        
        $(window).on('resize', debounce(function() {
            handleResponsiveLayout();
        }, 250));
    }
    
    /**
     * Handhabt den responsiven Layout-Wechsel
     */
    function handleResponsiveLayout() {
        const wasMobile = isMobileView;
        isMobileView = $(window).width() < 768;
        
        if (wasMobile === isMobileView && wasMobile !== false) return;
        
        const categories = ['main', 'genetics', 'cultivation', 'taste', 'advanced'];
        
        if (isMobileView) {
            categories.forEach(function(cat) {
                const $source = $('#category-' + cat);
                const $target = $('#accordion-body-' + cat);
                
                if ($source.length && $target.length && $target.children().length === 0) {
                    var $clonedContent = $source.clone();
                    var $transformed = transformToSubAccordions($clonedContent, cat);
                    $target.html($transformed.html());
                    
                    $source.find('.modal-filter-checkbox:checked, .filter-checkbox:checked').each(function() {
                        const filterId = $(this).data('filter-id');
                        const valueId = $(this).data('value-id');
                        $target.find('[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]')
                               .prop('checked', true);
                    });
                }
            });
            
            $('#filter-categories-desktop').addClass('d-none d-md-block');
            updateSubAccordionHighlights();
        } else {
            $('#filter-categories-desktop').removeClass('d-none d-md-block');
            
            categories.forEach(function(cat) {
                const $accordion = $('#accordion-body-' + cat);
                const $desktop = $('#category-' + cat);
                
                if ($accordion.length && $desktop.length) {
                    $accordion.find('.modal-filter-checkbox:checked, .filter-checkbox:checked').each(function() {
                        const filterId = $(this).data('filter-id');
                        const valueId = $(this).data('value-id');
                        $desktop.find('[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]')
                                .prop('checked', true);
                    });
                }
            });
        }
    }
    
    /**
     * Debounce-Hilfsfunktion
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }
    
    /**
     * v7.1.0: Transformiert Filter-Gruppen zu Sub-Accordions
     */
    function transformToSubAccordions($content, categoryId) {
        var $row = $content.find('.row').first();
        if (!$row.length) return $content;
        
        var $subAccordionContainer = $('<div class="sf-sub-accordion-container"></div>');
        var subIndex = 0;
        
        $row.find('.col-12').each(function() {
            var $col = $(this);
            var $heading = $col.find('h5').first();
            var $filterOptions = $col.find('.filter-options').first();
            
            if (!$heading.length || !$filterOptions.length) return;
            
            var headingText = $heading.text().trim();
            var subAccordionId = categoryId + '-sub-' + subIndex;
            
            var $subItem = $('<div class="sf-sub-accordion-item"></div>');
            
            var $subHeader = $('<div class="sf-sub-accordion-header" data-toggle-sub-accordion="' + subAccordionId + '">' +
                '<span class="sf-sub-accordion-title">' + headingText + '</span>' +
                '<span class="sf-sub-accordion-chevron"><i class="fa fa-chevron-down"></i></span>' +
                '</div>');
            
            var $subBody = $('<div class="sf-sub-accordion-body" id="sub-accordion-' + subAccordionId + '"></div>');
            $subBody.html($filterOptions.html());
            
            $subItem.append($subHeader).append($subBody);
            $subAccordionContainer.append($subItem);
            
            subIndex++;
        });
        
        if (subIndex > 0) {
            $row.replaceWith($subAccordionContainer);
        }
        
        return $content;
    }
    
    /**
     * Pruefe ob Modal automatisch geoeffnet werden soll
     */
    function checkAndOpenModal() {
        const urlParams = new URLSearchParams(window.location.search);
        const openModal = urlParams.get('open_modal');
        
        if (openModal === '1') {
            setTimeout(function() {
                $('#seedfinder-filter-modal').modal('show');
                
                const newUrl = window.location.pathname + window.location.search.replace('&open_modal=1', '').replace('open_modal=1&', '').replace('?open_modal=1', '?').replace('?&', '?');
                window.history.replaceState({}, '', newUrl);
            }, 300);
        }
    }
    
    function setupEventListeners() {
        // Modal oeffnen
        $('.open-filters-btn').on('click', function() {
            syncMainFiltersToModal();
            $('#seedfinder-filter-modal').modal('show');
        });
        
        // Badges nach Modal-Oeffnung aktualisieren
        $('#seedfinder-filter-modal').on('shown.bs.modal', function() {
            if (isMobileView) {
                handleResponsiveLayout();
            }
            updateCategoryFilterBadges();
        });
        
        // Kategorie-Dropdown im Modal-Header
        $('#modal-category-selector').on('change', function() {
            const newCategoryId = $(this).val();
            
            if (newCategoryId && newCategoryId !== currentCategoryId) {
                showCategoryLoadingOverlay();
                
                const activeFilters = getAllCheckedFilters();
                
                const urlParams = new URLSearchParams(window.location.search);
                const stage = urlParams.get('stage') || '3';
                
                let newUrl = getSeedfinderBaseUrl() + '?stage=' + stage + '&category=' + newCategoryId;
                
                for (const filterId in activeFilters) {
                    for (const valueId of activeFilters[filterId]) {
                        newUrl += '&filter[' + filterId + '][]=' + valueId;
                    }
                }
                
                newUrl += '&open_modal=1';
                
                window.location.href = newUrl;
            }
        });
        
        // Kategorie-Navigation (Desktop)
        $('.filter-category-btn').on('click', function() {
            const category = $(this).data('category');
            switchFilterCategory(category);
            
            $('.filter-category-btn').removeClass('active');
            $(this).addClass('active');
        });
        
        // Accordion-Header Click (Mobile)
        $(document).on('click', '.sf-accordion-header', function() {
            const category = $(this).data('toggle-accordion');
            if (!category) return;
            
            const $item = $(this).closest('.sf-accordion-item');
            const $body = $item.find('.sf-accordion-body');
            const $chevron = $(this).find('.sf-accordion-chevron i');
            const isOpen = $body.hasClass('show');
            
            if (isOpen) {
                $body.slideUp(300, function() {
                    $body.removeClass('show');
                });
                $chevron.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                $(this).removeClass('active');
            } else {
                $('.sf-accordion-item').not($item).each(function() {
                    var $otherBody = $(this).find('.sf-accordion-body');
                    var $otherHeader = $(this).find('.sf-accordion-header');
                    var $otherChevron = $otherHeader.find('.sf-accordion-chevron i');
                    if ($otherBody.hasClass('show')) {
                        $otherBody.slideUp(300, function() {
                            $otherBody.removeClass('show');
                        });
                        $otherChevron.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        $otherHeader.removeClass('active');
                    }
                });
                
                $body.slideDown(300, function() {
                    $body.addClass('show');
                });
                $chevron.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                $(this).addClass('active');
            }
            
            updateCategoryFilterBadges();
        });
        
        // v7.1.0: Sub-Accordion-Header Click (Mobile)
        $(document).on('click', '.sf-sub-accordion-header', function() {
            var subAccordionId = $(this).data('toggle-sub-accordion');
            if (!subAccordionId) return;
            
            var $subItem = $(this).closest('.sf-sub-accordion-item');
            var $subBody = $subItem.find('.sf-sub-accordion-body');
            var $chevron = $(this).find('.sf-sub-accordion-chevron i');
            var isOpen = $subBody.hasClass('show');
            
            if (isOpen) {
                $subBody.slideUp(200, function() {
                    $subBody.removeClass('show');
                });
                $chevron.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                $(this).removeClass('active');
            } else {
                $subBody.slideDown(200, function() {
                    $subBody.addClass('show');
                });
                $chevron.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                $(this).addClass('active');
            }
        });
        
        // Haupt-Filter: Checkbox-Aenderung
        $(document).on('change', '.main-filter-checkbox', function() {
            if (!isUpdating) {
                syncFilters($(this));
                updateActiveFilterBadges();
                debouncedBatchUpdate();
                toggleResetButtons();
                updateCategoryFilterBadges();
                updateSubAccordionHighlights();
            }
        });
        
        // Modal-Filter: Checkbox-Aenderung (Desktop + Mobile)
        $(document).on('change', '.modal-filter-checkbox', function() {
            if (!isUpdating) {
                syncFilters($(this));
                updateActiveFilterBadges();
                debouncedBatchUpdate();
                toggleResetButtons();
                updateCategoryFilterBadges();
                updateSubAccordionHighlights();
            }
        });
        
        // Filter-Checkbox: Aenderung (Fallback fuer geklonte Elemente)
        $(document).on('change', '.filter-checkbox', function() {
            // Nur feuern wenn NICHT schon als modal-filter-checkbox behandelt
            if (!$(this).hasClass('modal-filter-checkbox') && !$(this).hasClass('main-filter-checkbox')) {
                if (!isUpdating) {
                    syncFilters($(this));
                    updateActiveFilterBadges();
                    debouncedBatchUpdate();
                    toggleResetButtons();
                    updateCategoryFilterBadges();
                    updateSubAccordionHighlights();
                }
            }
        });
        
        // Haupt-Filter anwenden
        $('#apply-main-filters').on('click', function() {
            applyFilters();
        });
        
        // Modal-Filter anwenden
        $('#apply-modal-filters').on('click', function() {
            applyFilters();
        });
        
        // Haupt-Filter zuruecksetzen
        $('#reset-main-filters').on('click', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');
            const stage = urlParams.get('stage');
            
            let newUrl = getSeedfinderBaseUrl();
            if (stage && category) {
                newUrl += '?stage=' + stage + '&category=' + category + '&filters_reset=1';
            }
            
            window.location.href = newUrl;
        });
        
        // Modal-Filter zuruecksetzen
        $('#reset-modal-filters').on('click', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');
            const stage = urlParams.get('stage');
            
            let newUrl = getSeedfinderBaseUrl();
            if (stage && category) {
                newUrl += '?stage=' + stage + '&category=' + category + '&filters_reset=1';
            }
            
            window.location.href = newUrl;
        });
    }
    
    /**
     * v7.3: Zentrale Funktion - Sammelt ALLE aktiven Filter dedupliziert
     */
    function getAllCheckedFilters() {
        const activeFilters = {};
        const seen = {};
        
        $('.filter-checkbox:checked, .modal-filter-checkbox:checked, .main-filter-checkbox:checked').each(function() {
            const filterId = $(this).data('filter-id');
            const valueId = $(this).data('value-id');
            
            if (!filterId || !valueId) return;
            
            const key = filterId + '_' + valueId;
            if (seen[key]) return;
            seen[key] = true;
            
            if (!activeFilters[filterId]) {
                activeFilters[filterId] = [];
            }
            activeFilters[filterId].push(valueId);
        });
        
        return activeFilters;
    }
    
    /**
     * v8.0: Debounced Batch-Update (ersetzt separate debouncedCountUpdate + debouncedCatCountUpdate)
     * Ein einziger Timer fuer alles - 300ms Debounce
     */
    function debouncedBatchUpdate() {
        if (batchUpdateTimer) {
            clearTimeout(batchUpdateTimer);
        }
        batchUpdateTimer = setTimeout(function() {
            executeBatchUpdate();
        }, 300);
    }
    
    /**
     * v8.0: BATCH-UPDATE - Ein einziger AJAX-Request fuer alles
     * Ersetzt: updateFilterCounts() + updateCategoryCounts() + get_product_count
     * Spart 2 HTTP-Roundtrips pro Filter-Change
     */
    function executeBatchUpdate() {
        if (isUpdating) return;
        
        isUpdating = true;
        
        // Vorherigen Request abbrechen
        if (currentBatchXHR && currentBatchXHR.readyState !== 4) {
            currentBatchXHR.abort();
        }
        
        const activeFilters = getAllCheckedFilters();
        
        currentBatchXHR = $.ajax({
            url: getSeedfinderPhpUrl(),
            method: 'GET',
            data: {
                ajax: 'batch_update',
                category: currentCategoryId,
                filter: activeFilters
            },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                if (response && response.success) {
                    // 1. Filter-Counts aktualisieren
                    if (response.data) {
                        applyCountUpdates(response.data);
                    }
                    
                    // 2. Kategorie-Counts aktualisieren
                    if (response.category_counts) {
                        applyCategoryCountUpdates(response.category_counts);
                    }
                    
                    // 3. Produkt-Count aktualisieren
                    if (typeof response.product_count !== 'undefined') {
                        updateProductCountDisplay(response.product_count);
                    }
                }
            },
            error: function(xhr, status, error) {
                if (status === 'abort') return;
            },
            complete: function() {
                isUpdating = false;
            }
        });
    }
    
    /**
     * v8.0: Aktualisiert die Produkt-Count-Anzeige
     */
    function updateProductCountDisplay(count) {
        var $countDisplay = $('#product-count-display, .product-count-display, .seedfinder-product-count');
        if ($countDisplay.length) {
            $countDisplay.text(count + ' Produkte gefunden');
        }
    }
    
    /**
     * Wendet aktualisierte Counts auf alle Filter an
     * v7.3: Aktualisiert ALLE Checkboxen (Desktop + Modal + Mobile)
     */
    function applyCountUpdates(data) {
        if (!data) {
            return;
        }
        
        $('.filter-checkbox, .modal-filter-checkbox').each(function() {
            const $checkbox = $(this);
            const filterId = $checkbox.data('filter-id');
            const valueId = $checkbox.data('value-id');
            const $label = $checkbox.next('label');
            const $count = $label.find('.filter-count');
            
            let newCount = 0;
            let isEnabled = true;
            
            if (data[filterId] && data[filterId][valueId]) {
                newCount = data[filterId][valueId].count || 0;
                isEnabled = data[filterId][valueId].enabled !== false;
            }
            
            $count.text('(' + newCount + ')');
            
            if (!$checkbox.is(':checked')) {
                if (!isEnabled || newCount === 0) {
                    $checkbox.prop('disabled', true);
                    $checkbox.closest('.custom-control').addClass('filter-disabled');
                    $checkbox.closest('.custom-control').css({
                        'display': 'block',
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                } else {
                    $checkbox.prop('disabled', false);
                    $checkbox.closest('.custom-control').removeClass('filter-disabled');
                    $checkbox.closest('.custom-control').css({
                        'display': 'block',
                        'opacity': '1',
                        'pointer-events': 'auto'
                    });
                }
            }
        });
        
        sortFilterOptions();
        updateCategoryFilterBadges();
        updateActiveFilterBadges();
        updateSubAccordionHighlights();
    }
    
    /**
     * v7.2: Wendet Kategorie-Counts auf das Dropdown an
     */
    function applyCategoryCountUpdates(counts) {
        var $select = $('#modal-category-selector');
        if (!$select.length) return;
        
        $select.find('option').each(function() {
            var $option = $(this);
            var catId = $option.val();
            var isSelected = $option.is(':selected');
            
            if (counts[catId]) {
                var count = counts[catId].count;
                var baseName = $option.text().replace(/\s*\(\d+\)\s*$/, '').trim();
                $option.text(baseName + ' (' + count + ')');
                
                if (count === 0 && !isSelected) {
                    $option.prop('disabled', true);
                    $option.css('color', '#999');
                } else {
                    $option.prop('disabled', false);
                    $option.css('color', '');
                }
            }
        });
    }
    
    /**
     * Loading-Overlay fuer Kategorie-Wechsel
     */
    function showCategoryLoadingOverlay() {
        let loadingText = $('#trans-loading-category-filters').text();
        
        if (!loadingText || loadingText.trim() === '') {
            loadingText = 'Lade Filter f&uuml;r neue Kategorie...';
        }
        
        const $overlay = $('<div class="seedfinder-loading-overlay">' +
            '<div class="seedfinder-loading-content">' +
                '<i class="fa fa-spinner fa-spin fa-4x text-primary mb-3"></i>' +
                '<h4 class="text-dark">' + loadingText + '</h4>' +
            '</div>' +
        '</div>');
        
        $('#seedfinder-filter-modal .modal-content').append($overlay);
        
        setTimeout(function() {
            $overlay.addClass('active');
        }, 10);
    }
    
    /**
     * Reset-Buttons ein-/ausblenden
     */
    function toggleResetButtons() {
        const hasActiveFilters = $('.filter-checkbox:checked, .modal-filter-checkbox:checked, .main-filter-checkbox:checked').length > 0;
        if (hasActiveFilters) {
            $('#reset-filters-btn').show();
        } else {
            $('#reset-filters-btn').hide();
        }
    }
    
    /**
     * Category Filter Badges aktualisieren
     */
    function updateCategoryFilterBadges() {
        const categories = ['main', 'genetics', 'cultivation', 'taste', 'advanced'];
        
        categories.forEach(function(cat) {
            const $container = $('#category-' + cat);
            const $accordionBody = $('#accordion-body-' + cat);
            const $badge = $('[data-category-badge="' + cat + '"], [data-category-badge="' + cat + '-mobile"]');
            
            const uniqueFilters = {};
            const $allCheckboxes = $container.find('.filter-checkbox:checked, .modal-filter-checkbox:checked')
                .add($accordionBody.find('.filter-checkbox:checked, .modal-filter-checkbox:checked'));
            $allCheckboxes.each(function() {
                const key = $(this).data('filter-id') + '_' + $(this).data('value-id');
                uniqueFilters[key] = true;
            });
            const count = Object.keys(uniqueFilters).length;
            
            if ($badge.length) {
                if (count > 0) {
                    $badge.css('animation', 'none');
                    $badge[0] && ($badge[0].offsetHeight);
                    $badge.text(count).css({
                        'display': 'inline-block',
                        'opacity': '1',
                        'transform': 'scale(1)'
                    });
                } else {
                    $badge.css('animation', 'none');
                    $badge.text('0').css({
                        'display': 'none',
                        'opacity': '0',
                        'transform': 'scale(0)'
                    });
                }
            }
        });
    }
    
    /**
     * v7.1.0: Sub-Accordion-Highlights aktualisieren
     */
    function updateSubAccordionHighlights() {
        $('.sf-sub-accordion-item').each(function() {
            var $subItem = $(this);
            var $subBody = $subItem.find('.sf-sub-accordion-body');
            var $subHeader = $subItem.find('.sf-sub-accordion-header');
            
            var hasActiveFilters = $subBody.find('.filter-checkbox:checked, .modal-filter-checkbox:checked').length > 0;
            
            if (hasActiveFilters) {
                $subHeader.addClass('has-active-filters');
            } else {
                $subHeader.removeClass('has-active-filters');
            }
        });
    }
    
    /**
     * Wechselt zwischen Filter-Kategorien im Modal (Desktop)
     */
    function switchFilterCategory(category) {
        $('.filter-category-content').hide();
        $('#category-' + category).fadeIn(300);
    }
    
    /**
     * Synchronisiert Filter zwischen Haupt-Seite, Modal und Mobile
     */
    function syncFilters($checkbox) {
        const filterId = $checkbox.data('filter-id');
        const valueId = $checkbox.data('value-id');
        const isChecked = $checkbox.is(':checked');
        
        const $targets = $('input[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]').not($checkbox);
        
        $targets.prop('checked', isChecked);
    }
    
    /**
     * Synchronisiert Haupt-Filter mit Modal-Filtern beim Oeffnen
     */
    function syncMainFiltersToModal() {
        $('.main-filter-checkbox').each(function() {
            const $mainCheckbox = $(this);
            const filterId = $mainCheckbox.data('filter-id');
            const valueId = $mainCheckbox.data('value-id');
            const isChecked = $mainCheckbox.is(':checked');
            
            $('input[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]')
                .not($mainCheckbox)
                .prop('checked', isChecked);
        });
    }
    
    /**
     * Sortiert Filter-Optionen: Checked > Enabled > Disabled
     */
    function sortFilterOptions() {
        $('.filter-options, .filter-options-main').each(function() {
            const $container = $(this);
            const $items = $container.find('.custom-control').detach();
            
            $items.sort(function(a, b) {
                const $aCheckbox = $(a).find('input');
                const $bCheckbox = $(b).find('input');
                
                const aChecked = $aCheckbox.is(':checked');
                const bChecked = $bCheckbox.is(':checked');
                const aDisabled = $aCheckbox.is(':disabled');
                const bDisabled = $bCheckbox.is(':disabled');
                
                if (aChecked && !bChecked) return -1;
                if (!aChecked && bChecked) return 1;
                
                if (!aDisabled && bDisabled) return -1;
                if (aDisabled && !bDisabled) return 1;
                
                let aOrder = parseInt($aCheckbox.data('sort-order'));
                let bOrder = parseInt($bCheckbox.data('sort-order'));
                
                if (isNaN(aOrder) && isNaN(bOrder)) {
                    const aText = $aCheckbox.data('value-name');
                    const bText = $bCheckbox.data('value-name');
                    return aText.localeCompare(bText);
                }
                
                if (isNaN(aOrder)) return 1;
                if (isNaN(bOrder)) return -1;
                
                if (aOrder !== bOrder) {
                    return aOrder - bOrder;
                }
                
                const aText = $aCheckbox.data('value-name');
                const bText = $bCheckbox.data('value-name');
                return aText.localeCompare(bText);
            });
            
            $container.append($items);
            reapplyFilterStyles();
        });
    }
    
    /**
     * Wendet Styles auf alle Filter an (nach Sortierung)
     */
    function reapplyFilterStyles() {
        $('.filter-checkbox, .modal-filter-checkbox').each(function() {
            const $checkbox = $(this);
            const $control = $checkbox.closest('.custom-control');
            
            if ($checkbox.is(':checked')) {
                $control.css({
                    'display': 'block',
                    'opacity': '1',
                    'pointer-events': 'auto'
                });
            }
            else if ($checkbox.is(':disabled')) {
                $control.css({
                    'display': 'block',
                    'opacity': '0.5',
                    'pointer-events': 'none'
                });
            }
            else {
                $control.css({
                    'display': 'block',
                    'opacity': '1',
                    'pointer-events': 'auto'
                });
            }
        });
    }
    
    /**
     * Aktualisiert die "Deine ausgewaehlten Kriterien"-Card
     */
    function updateActiveFilterBadges() {
        const $container = $('#active-filters-list');
        const $card = $('#active-filters-card');
        
        const activeFiltersMap = {};
        $('.filter-checkbox:checked, .modal-filter-checkbox:checked, .main-filter-checkbox:checked').each(function() {
            const filterName = $(this).data('filter-name');
            const valueName = $(this).data('value-name');
            const filterId = String($(this).data('filter-id'));
            const valueId = String($(this).data('value-id'));
            
            const key = filterId + '_' + valueId;
            
            if (!activeFiltersMap[key]) {
                activeFiltersMap[key] = {
                    filterName: filterName,
                    valueName: valueName,
                    filterId: filterId,
                    valueId: valueId
                };
            }
        });
        
        const activeFilters = Object.values(activeFiltersMap);
        
        if (activeFilters.length > 0) {
            $container.empty();
            
            activeFilters.forEach(function(filter) {
                const badge = $('<span class="badge badge-primary mr-2 mb-2"></span>')
                    .text(filter.filterName + ': ' + filter.valueName)
                    .append(' <i class="fa fa-times ml-1 remove-filter-badge" data-filter-id="' + filter.filterId + '" data-value-id="' + filter.valueId + '"></i>');
                
                $container.append(badge);
            });
            
            $card.show();
        } else {
            $card.hide();
        }
    }
    
    /**
     * Wendet alle ausgewaehlten Filter an (Seite neu laden)
     */
    function applyFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const stage = urlParams.get('stage');
        const category = currentCategoryId;
        
        const activeFilters = getAllCheckedFilters();
        
        const manufacturers = [];
        $('.manufacturer-checkbox:checked').each(function() {
            manufacturers.push($(this).val());
        });
        
        let newUrl = getSeedfinderBaseUrl() + '?stage=2&category=' + category;
        
        for (const filterId in activeFilters) {
            for (const valueId of activeFilters[filterId]) {
                newUrl += '&filter[' + filterId + '][]=' + valueId;
            }
        }
        
        for (const manufacturerId of manufacturers) {
            newUrl += '&manufacturer[]=' + manufacturerId;
        }
        
        window.location.href = newUrl + '#products-container';
    }
    
    // Event Delegation fuer Badge-Entfernung
    $(document).on('click', '.remove-filter-badge', function() {
        const filterId = $(this).data('filter-id');
        const valueId = $(this).data('value-id');
        
        $('input[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]').prop('checked', false).first().trigger('change');
    });
    
})(jQuery);