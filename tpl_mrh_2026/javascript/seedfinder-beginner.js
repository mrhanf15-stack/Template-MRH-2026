/**
 * Seedfinder Beginner Wizard - NEW 4-Step Version
 * With Multi-Select and OR Logic
 */

(function($) {
    'use strict';
    
    
    // Wizard State
    const wizard = {
        currentStep: 1,
        totalSteps: 4,
        answers: {},
        filters: {}, // { filter_id: [value1, value2, ...] }
        categoryId: 581210, // Default: Samenshop
        categories: {
            default: 581210,
            autoflowering: 58000,
            photoperiodisch: 581346
        }
    };
    
    /**
     * Initialize Wizard
     */
    function initWizard() {
        
        // Single-Select Cards (Step 1, 2)
        $(document).on('click', '.wizard-option-card-style:not(.wizard-multi-option)', function() {
            const step = $(this).data('step');
            const value = $(this).data('value');
            
            
            // Mark as selected
            $(this).siblings('.wizard-option-card-style').removeClass('selected');
            $(this).addClass('selected');
            
            // Save answer
            wizard.answers[`step${step}`] = value;
            
            // Preselect filters based on answer
            preselectFilters(step, value);
            
            // Show debug info
            
            // Check if this is a double-click (same card clicked twice)
            const lastClicked = $(this).data('last-clicked');
            const now = Date.now();
            
            if (lastClicked && (now - lastClicked) < 1000) {
                // Double-click detected - advance to next step
                setTimeout(function() {
                    if (wizard.currentStep < wizard.totalSteps) {
                        nextStep();
                    }
                }, 300);
            } else {
                // First click - save timestamp
                $(this).data('last-clicked', now);
            }
        });
        
        // Step 3 now uses single-select card logic (handled in single-select section above)
        
        // Multi-Select Cards (Step 4)
        $(document).on('click', '.wizard-multi-option', function() {
            const step = $(this).data('step');
            const value = $(this).data('value');
            const filterId = $(this).data('filter-id');
            
            // Only handle Step 4 here (Step 3 uses checkboxes now)
            if (step !== 4) return;
            
            
            // Toggle selected state
            $(this).toggleClass('selected');
            
            // Update filters for Step 4 (Blütezeit)
            if ($(this).hasClass('selected')) {
                // Add all bloom time filters for this option
                addBloomTimeFilters(value);
            } else {
                // Remove all bloom time filters for this option
                removeBloomTimeFilters(value);
            }
            
            // Show debug info
        });
        
        // Step 3 no longer needs a separate continue button (single-select with double-click)
        
        // Show Results Button (Step 4)
        $('#show-results-btn').on('click', function() {
            showResults();
        });
        
        // Back Button
        $('#wizard-back-btn').on('click', function() {
            previousStep();
        });
        
        // Reset Button
        $('#wizard-reset-btn').on('click', function() {
            resetWizard();
        });
        
        // Update UI
        updateProgress();
        updateBackButton();
        
    }
    
    /**
     * Add Filter
     */
    function addFilter(filterId, valueId) {
        if (!wizard.filters[filterId]) {
            wizard.filters[filterId] = [];
        }
        
        if (!wizard.filters[filterId].includes(valueId)) {
            wizard.filters[filterId].push(valueId);
        }
    }
    
    /**
     * Remove Filter
     */
    function removeFilter(filterId, valueId) {
        if (wizard.filters[filterId]) {
            wizard.filters[filterId] = wizard.filters[filterId].filter(v => v !== valueId);
            
            // Remove filter key if empty
            if (wizard.filters[filterId].length === 0) {
                delete wizard.filters[filterId];
            }
            
        }
    }
    
    /**
     * Add Bloom Time Filters
     */
    function addBloomTimeFilters(option) {
        const isAutoflowering = (wizard.categoryId === wizard.categories.autoflowering);
        
        if (option === 'normal') {
            // Normal: 6-9 Wochen
            if (isAutoflowering) {
                // Autoflowering: Nur Filter 55
                addFilter(55, 774);
                addFilter(55, 775);
                addFilter(55, 776);
            } else {
                // Photoperiodisch: Nur Filter 7
                addFilter(7, 496);
                addFilter(7, 92);
                addFilter(7, 58);
                addFilter(7, 59);
            }
        } else if (option === 'langsam') {
            // Langsam: 9-12+ Wochen
            if (isAutoflowering) {
                // Autoflowering: Nur Filter 55
                addFilter(55, 777);
                addFilter(55, 778);
                addFilter(55, 779);
                addFilter(55, 780);
            } else {
                // Photoperiodisch: Nur Filter 7
                addFilter(7, 60);
                addFilter(7, 61);
                addFilter(7, 62);
                addFilter(7, 63);
                addFilter(7, 98);
                addFilter(7, 99);
                addFilter(7, 100);
            }
        }
    }
    
    /**
     * Remove Bloom Time Filters
     */
    function removeBloomTimeFilters(option) {
        const isAutoflowering = (wizard.categoryId === wizard.categories.autoflowering);
        
        if (option === 'normal') {
            // Remove Normal filters
            if (isAutoflowering) {
                removeFilter(55, 774);
                removeFilter(55, 775);
                removeFilter(55, 776);
            } else {
                removeFilter(7, 496);
                removeFilter(7, 92);
                removeFilter(7, 58);
                removeFilter(7, 59);
            }
        } else if (option === 'langsam') {
            // Remove Langsam filters
            if (isAutoflowering) {
                removeFilter(55, 777);
                removeFilter(55, 778);
                removeFilter(55, 779);
                removeFilter(55, 780);
            } else {
                removeFilter(7, 60);
                removeFilter(7, 61);
                removeFilter(7, 62);
                removeFilter(7, 63);
                removeFilter(7, 98);
                removeFilter(7, 99);
                removeFilter(7, 100);
            }
        }
    }
    
    /**
     * Preselect Filters
     */
    function preselectFilters(step, value) {
        
        // Step 1: Indoor / Outdoor
        if (step === 1) {
            // Both stay in category 581210, no filters
            wizard.categoryId = wizard.categories.default;
        }
        
        // Step 2: Autoflowering / Photoperiodisch
        if (step === 2) {
            if (value === 'autoflowering') {
                wizard.categoryId = wizard.categories.autoflowering;
            } else if (value === 'photoperiodisch') {
                wizard.categoryId = wizard.categories.photoperiodisch;
            }
        }
        
        // Step 3: Genetik (Single-Select)
        if (step === 3) {
            // Clear previous Genetik filter
            delete wizard.filters[12];
            
            // Add new Genetik filter (value is already the filter value ID)
            addFilter(12, parseInt(value));
            
        }
        
    }
    
    /**
     * Show Debug Info
     */
        // Remove old debug panel
        
        // Create debug panel
        
        // Filters
        if (Object.keys(wizard.filters).length === 0) {
        } else {
            for (const [tagId, valueIds] of Object.entries(wizard.filters)) {
            }
        }
        
        // Category
        
        // Product Count (AJAX)
        
        // Hint
        
        
        // Append to wizard body
        
        // Load product count via AJAX
        loadProductCount();
    }
    
    /**
     * Load Product Count via AJAX
     */
    function loadProductCount() {
        let url = `seedfinder.php?ajax=get_product_count&category=${wizard.categoryId}`;
        
        for (const [tagId, valueIds] of Object.entries(wizard.filters)) {
            for (let i = 0; i < valueIds.length; i++) {
                url += `&filter[${tagId}][]=${valueIds[i]}`;
            }
        }
        
        
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && typeof response.count !== 'undefined') {
                    const count = response.count;
                    
                    // Color coding
                    let color = '#28a745'; // Green
                    if (count === 0) {
                        color = '#dc3545'; // Red
                    } else if (count > 100) {
                        color = '#ffc107'; // Yellow
                    }
                    
                } else {
                }
            },
            error: function() {
            }
        });
    }
    
    /**
     * Next Step
     */
    function nextStep() {
        if (wizard.currentStep >= wizard.totalSteps) {
            return;
        }
        
        $(`#wizard-step-${wizard.currentStep}`).removeClass('active');
        wizard.currentStep++;
        $(`#wizard-step-${wizard.currentStep}`).addClass('active');
        
        updateProgress();
        updateBackButton();
        
        // Keep debug panel visible and update it
        setTimeout(function() {
            }
        }, 100);
        
    }
    
    /**
     * Undo Filters of Current Step
     */
    function undoCurrentStepFilters() {
        const step = wizard.currentStep;
        const answer = wizard.answers[`step${step}`];
        
        
        // Step 2: Reset category to default
        if (step === 2) {
            wizard.categoryId = wizard.categories.default;
        }
        
        // Step 3: Remove Wirkung-Filter and uncheck checkboxes
        if (step === 3) {
            delete wizard.filters[65];
            $('.wizard-effect-checkbox').prop('checked', false);
            $('.wizard-effect-card').removeClass('selected');
        }
        
        // Step 4: Remove Blütezeit-Filter
        if (step === 4) {
            delete wizard.filters[7];
            delete wizard.filters[55];
        }
        
        // Clear answer
        delete wizard.answers[`step${step}`];
    }
    
    /**
     * Previous Step
     */
    function previousStep() {
        if (wizard.currentStep <= 1) {
            return;
        }
        
        // Undo filters of current step before going back
        undoCurrentStepFilters();
        
        $(`#wizard-step-${wizard.currentStep}`).removeClass('active');
        wizard.currentStep--;
        $(`#wizard-step-${wizard.currentStep}`).addClass('active');
        
        updateProgress();
        updateBackButton();
        
        // Keep debug panel visible when going back
        setTimeout(function() {
            }
        }, 100);
        
    }
    
    /**
     * Update Progress Bar
     */
    function updateProgress() {
        const percentage = (wizard.currentStep / wizard.totalSteps) * 100;
        $('#wizard-progress-bar').css('width', percentage + '%');
        $('#wizard-progress-text').text(`Schritt ${wizard.currentStep} von ${wizard.totalSteps}`);
    }
    
    /**
     * Update Back Button
     */
    function updateBackButton() {
        if (wizard.currentStep > 1) {
            $('#wizard-back-btn').show();
        } else {
            $('#wizard-back-btn').hide();
        }
    }
    
    /**
     * Show Results
     */
    function showResults() {
        
        // Build URL
        let url = 'seedfinder.php?stage=3';
        url += `&category=${wizard.categoryId}`;
        
        for (const [tagId, valueIds] of Object.entries(wizard.filters)) {
            for (let i = 0; i < valueIds.length; i++) {
                url += `&filter[${tagId}][]=${valueIds[i]}`;
            }
        }
        
        
        // Redirect
        window.location.href = url;
    }
    
    /**
     * Reset Wizard
     */
    function resetWizard() {
        
        // Reset state
        wizard.currentStep = 1;
        wizard.answers = {};
        wizard.filters = {};
        wizard.categoryId = wizard.categories.default;
        
        // Reset UI
        $('.wizard-step').removeClass('active');
        $('#wizard-step-1').addClass('active');
        $('.wizard-option-card-style').removeClass('selected');
        
        updateProgress();
        updateBackButton();
        
    }
    
    /**
     * Document Ready
     */
    $(document).ready(function() {
        
        if ($('#beginnerWizardModal').length > 0) {
            
            const optionCards = $('.wizard-option-card-style').length;
            
            initWizard();
        } else {
        }
    });
    
    
})(jQuery);