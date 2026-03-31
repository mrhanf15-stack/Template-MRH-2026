/**
 * Seedfinder Beginner Results JavaScript v3.0.0
 * Filter functionality for beginner results page with LIVE updates
 */
(function($) {
    'use strict';
    
    /**
     * Initialize Beginner Results
     */
    function initBeginnerResults() {
        // Apply Filters Button
        $('#apply-beginner-filters').on('click', function() {
            applyFilters();
        });
        
        // Live Filter Updates on Checkbox Change
        $('.beginner-filter-checkbox').on('change', function() {
            onCheckboxChange($(this));
        });
    }
    
    /**
     * Handle Checkbox Change - Live Update
     */
    function onCheckboxChange($checkbox) {
        const filterId = $checkbox.data('filter-id');
        const valueId = $checkbox.data('value-id');
        const isChecked = $checkbox.is(':checked');
        
        
        // Update URL without reload
        updateURL();
        
        // Trigger AJAX filter update
        updateFilters();
    }
    
    /**
     * Update URL with current filter state (without reload)
     */
    function updateURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const newParams = new URLSearchParams();
        
        // 1. Stage und Category übernehmen
        if (urlParams.has('stage')) {
            newParams.set('stage', urlParams.get('stage'));
        }
        if (urlParams.has('category')) {
            newParams.set('category', urlParams.get('category'));
        }
        
        // 2. Bestehende Wizard-Filter übernehmen (aus URL)
        urlParams.forEach((value, key) => {
            // Nur filter[...] Parameter übernehmen, die NICHT in den Checkbox-Filtern sind
            if (key.startsWith('filter[')) {
                const match = key.match(/filter\[(\d+)\]/);
                if (match) {
                    const filterIdFromUrl = match[1];
                    // Nur übernehmen, wenn dieser Filter NICHT in den Checkboxen ist
                    const hasCheckboxForFilter = $('.beginner-filter-checkbox[data-filter-id="' + filterIdFromUrl + '"]').length > 0;
                    if (!hasCheckboxForFilter) {
                        newParams.append(key, value);
                    }
                }
            }
        });
        
        // 3. Checkbox-Filter hinzufügen
        const checkboxFilters = {};
        
        $('.beginner-filter-checkbox:checked').each(function() {
            const filterId = $(this).data('filter-id');
            const valueId = $(this).data('value-id');
            
            if (!checkboxFilters[filterId]) {
                checkboxFilters[filterId] = [];
            }
            checkboxFilters[filterId].push(valueId);
        });
        
        // Checkbox-Filter zur URL hinzufügen
        Object.keys(checkboxFilters).forEach(filterId => {
            checkboxFilters[filterId].forEach(valueId => {
                newParams.append(`filter[${filterId}][]`, valueId);
            });
        });
        
        // 4. URL aktualisieren (ohne Reload)
        const newUrl = window.location.pathname + '?' + newParams.toString();
        window.history.pushState({}, '', newUrl);
        
    }
    
    /**
     * Update Filters via AJAX
     */
    function updateFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Build AJAX URL
        let ajaxUrl = 'seedfinder.php?ajax=update_filters';
        
        // Add category
        if (urlParams.has('category')) {
            ajaxUrl += '&category=' + urlParams.get('category');
        }
        
        // Add all filters
        urlParams.forEach((value, key) => {
            if (key.startsWith('filter[')) {
                ajaxUrl += '&' + key + '=' + encodeURIComponent(value);
            }
        });
        
        
        // Show loading state
        $('.beginner-filter-group').addClass('loading').css('opacity', '0.6');
        
        // AJAX Request
        $.ajax({
            url: ajaxUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                
                if (response && response.filters) {
                    // Update each filter group
                    Object.keys(response.filters).forEach(filterId => {
                        updateFilterGroup(filterId, response.filters[filterId]);
                    });
                }
                
                // Remove loading state
                $('.beginner-filter-group').removeClass('loading').css('opacity', '1');
            },
            error: function(xhr, status, error) {
                
                // Remove loading state
                $('.beginner-filter-group').removeClass('loading').css('opacity', '1');
            }
        });
    }
    
    /**
     * Update Filter Group with new data
     */
    function updateFilterGroup(filterId, filterData) {
        
        const $filterGroup = $('.beginner-filter-group[data-filter-id="' + filterId + '"]');
        if ($filterGroup.length === 0) {
            return;
        }
        
        // Sort options: checked ? enabled ? disabled
        const sortedOptions = filterData.sort((a, b) => {
            // Checked first
            if (a.checked && !b.checked) return -1;
            if (!a.checked && b.checked) return 1;
            
            // Then enabled
            if (a.enabled && !b.enabled) return -1;
            if (!a.enabled && b.enabled) return 1;
            
            // Then by sort_order
            return a.sort_order - b.sort_order;
        });
        
        // Update each option
        sortedOptions.forEach(option => {
            const $checkbox = $filterGroup.find('.beginner-filter-checkbox[data-value-id="' + option.values_id + '"]');
            const $outerLabel = $checkbox.parent('label.custom-control');
            const $innerLabel = $outerLabel.find('label.custom-control-label');
            const $countSpan = $innerLabel.find('.filter-count');
            
            if ($checkbox.length === 0) {
                return;
            }
            
            // Update count
            if ($countSpan.length > 0) {
                $countSpan.text('(' + option.count + ')');
            }
            
            // Update enabled/disabled state
            
            if (option.enabled) {
                $checkbox.prop('disabled', false);
                $outerLabel.removeClass('filter-disabled');
            } else {
                $checkbox.prop('disabled', true);
                $outerLabel.addClass('filter-disabled');
            }
            
            // Move to top if checked
            if (option.checked) {
                $outerLabel.prependTo($filterGroup);
            }
        });
    }
    
    /**
     * Apply Filters - Behält Wizard-Filter und fügt Checkbox-Filter hinzu
     */
    function applyFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Neue URL-Parameter sammeln
        const newParams = new URLSearchParams();
        
        // 1. Stage und Category übernehmen
        if (urlParams.has('stage')) {
            newParams.set('stage', urlParams.get('stage'));
        }
        if (urlParams.has('category')) {
            newParams.set('category', urlParams.get('category'));
        }
        
        // 2. Bestehende Wizard-Filter übernehmen (aus URL)
        urlParams.forEach((value, key) => {
            // Nur filter[...] Parameter übernehmen
            if (key.startsWith('filter[')) {
                newParams.append(key, value);
            }
        });
        
        // 3. Neue Checkbox-Filter hinzufügen (überschreiben bestehende aus gleicher Kategorie)
        const checkboxFilters = {};
        
        $('.beginner-filter-checkbox:checked').each(function() {
            const filterId = $(this).data('filter-id');
            const valueId = $(this).data('value-id');
            
            if (!checkboxFilters[filterId]) {
                checkboxFilters[filterId] = [];
            }
            checkboxFilters[filterId].push(valueId);
        });
        
        // Checkbox-Filter zur URL hinzufügen
        Object.keys(checkboxFilters).forEach(filterId => {
            checkboxFilters[filterId].forEach(valueId => {
                newParams.append(`filter[${filterId}][]`, valueId);
            });
        });
        
        // 4. Neue URL erstellen und laden
        const newUrl = window.location.pathname + '?' + newParams.toString();
        window.location.href = newUrl;
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initBeginnerResults();
    });
    
})(jQuery);