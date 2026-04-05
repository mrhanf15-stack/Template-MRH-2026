/**
 * Seedfinder v6.7.0-bs5 - Accordion Filter JavaScript + Auto-Expand + Checkbox-Sort
 * Accordion-Logik + Aktive Filter Card + Auto-Expand + Checkbox-Sortierung
 * 
 * BS5.3 Migration (2026-04-05):
 * - data-target → data-bs-target
 * - data-toggle → data-bs-toggle
 * - Alle anderen Attribute bleiben (data-filter-id, data-value-id etc.)
 * 
 * Features:
 * - Checkboxen sortieren: checked > enabled > disabled
 * - Disabled Checkboxen ausblenden (außer checked)
 * - KEINE Card-Hide-Logik!
 * AJAX wird von seedfinder_ajax.js übernommen
 * Datum: 10. Februar 2026 | BS5-Migration: 05. April 2026
 * Autor: Mr. Hanf / Manus AI
 */

(function() {
    'use strict';
    
    // ========================================
    // DOM Ready
    // ========================================
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Seedfinder Accordion v6.7.0-bs5 initialized (mit Checkbox-Sort)');
        
        initAccordion();
        initActiveFiltersCard();
        initResetButton();
        updateFilterCount();
        
        // Auto-Expand + Checkbox-Sort nach kurzer Verzögerung
        setTimeout(function() {
            autoExpandAvailableAccordions();
            sortCheckboxes(); // Sortierung beim initialen Laden
        }, 500);
    });
    
    // ========================================
    // Accordion Initialisierung
    // ========================================
    
    function initAccordion() {
        const accordionHeaders = document.querySelectorAll('.accordion-filter-header');
        
        accordionHeaders.forEach(header => {
            // Einfacher Klick: Schließen
            header.addEventListener('click', function() {
                // BS5.3: data-bs-target statt data-target
                const target = this.getAttribute('data-bs-target') || this.getAttribute('data-target');
                const collapse = document.querySelector(target);
                
                if (!collapse) return;
                
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Nur schließen wenn geöffnet
                if (isExpanded) {
                    this.setAttribute('aria-expanded', 'false');
                    collapse.classList.remove('show');
                }
            });
            
            // Doppelklick: Öffnen
            header.addEventListener('dblclick', function() {
                // BS5.3: data-bs-target statt data-target
                const target = this.getAttribute('data-bs-target') || this.getAttribute('data-target');
                const collapse = document.querySelector(target);
                
                if (!collapse) return;
                
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Nur öffnen wenn geschlossen
                if (!isExpanded) {
                    this.setAttribute('aria-expanded', 'true');
                    collapse.classList.add('show');
                }
            });
        });
    }
    
    // ========================================
    // Reset-Button Initialisierung
    // ========================================
    
    function initResetButton() {
        const resetButton = document.getElementById('reset-all-filters');
        
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                // Alle Checkboxen deaktivieren
                const checkboxes = document.querySelectorAll('.filter-checkbox:checked');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Aktive Filter Card aktualisieren
                updateActiveFiltersCard();
                updateFilterCount();
                
                // AJAX-Update triggern (wird von seedfinder_ajax.js übernommen)
                const firstCheckbox = document.querySelector('.filter-checkbox');
                if (firstCheckbox) {
                    firstCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    }
    
    // ========================================
    // Filter-Counter aktualisieren
    // ========================================
    
    function updateFilterCount() {
        const activeFilterCount = document.getElementById('active-filter-count');
        
        if (activeFilterCount) {
            const count = document.querySelectorAll('.filter-checkbox:checked').length;
            activeFilterCount.textContent = count;
        }
    }
    
    // ========================================
    // Aktive Filter Card Initialisierung
    // ========================================
    
    function initActiveFiltersCard() {
        // Initial aktualisieren
        updateActiveFiltersCard();
        
        // Bei jeder Filter-Änderung aktualisieren
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('filter-checkbox')) {
                updateActiveFiltersCard();
                updateFilterCount();
            }
        });
    }
    
    // ========================================
    // Aktive Filter Card aktualisieren
    // ========================================
    
    function updateActiveFiltersCard() {
        const activeFiltersCard = document.getElementById('active-filters-card');
        const activeFiltersList = document.getElementById('active-filters-list');
        
        if (!activeFiltersCard || !activeFiltersList) return;
        
        // Alle aktiven Checkboxen finden
        const activeCheckboxes = document.querySelectorAll('.filter-checkbox:checked');
        
        if (activeCheckboxes.length === 0) {
            // Keine aktiven Filter → Card ausblenden
            activeFiltersCard.style.display = 'none';
            return;
        }
        
        // Card einblenden
        activeFiltersCard.style.display = '';
        
        // Liste leeren
        activeFiltersList.innerHTML = '';
        
        // Aktive Filter als Badges hinzufügen
        activeCheckboxes.forEach(checkbox => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            if (!label) return;
            
            const filterName = label.textContent.trim();
            const filterId = checkbox.getAttribute('data-filter-id');
            const valueId = checkbox.getAttribute('data-value-id');
            
            // Badge erstellen
            const badge = document.createElement('div');
            badge.className = 'active-filter-badge';
            badge.innerHTML = `
                <input 
                    type="checkbox" 
                    checked 
                    data-filter-id="${filterId}" 
                    data-value-id="${valueId}"
                    id="active-filter-${filterId}-${valueId}"
                >
                <label for="active-filter-${filterId}-${valueId}">
                    ${filterName}
                </label>
            `;
            
            // Event-Listener für Checkbox
            const badgeCheckbox = badge.querySelector('input[type="checkbox"]');
            badgeCheckbox.addEventListener('change', function() {
                if (!this.checked) {
                    // Badge entfernen mit Animation
                    badge.classList.add('removing');
                    setTimeout(() => {
                        badge.remove();
                        
                        // Original-Checkbox abwählen
                        const originalCheckbox = document.getElementById(`filter_${filterId}_${valueId}`);
                        if (originalCheckbox) {
                            originalCheckbox.checked = false;
                            originalCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                        
                        // Card aktualisieren
                        updateActiveFiltersCard();
                    }, 300);
                }
            });
            
            activeFiltersList.appendChild(badge);
        });
    }
    
    // ========================================
    // Auto-Expand: Accordions mit verfügbaren Filtern öffnen
    // ========================================
    
    function autoExpandAvailableAccordions() {
        const accordionHeaders = document.querySelectorAll('.accordion-filter-header');
        
        accordionHeaders.forEach(header => {
            // BS5.3: data-bs-target statt data-target
            const target = header.getAttribute('data-bs-target') || header.getAttribute('data-target');
            const collapse = document.querySelector(target);
            
            if (!collapse) return;
            
            // Prüfe ob dieses Accordion verfügbare Filter hat
            const availableFilters = collapse.querySelectorAll('.filter-checkbox:not(:disabled)');
            const hasAvailableFilters = availableFilters.length > 0;
            
            // v6.6.6 NO-HIDE: NUR Auto-Expand, KEINE Card-Hide-Logik!
            if (hasAvailableFilters) {
                // Verfügbare Filter vorhanden → Accordion öffnen
                header.setAttribute('aria-expanded', 'true');
                collapse.classList.add('show');
            } else {
                // Keine verfügbaren Filter → Accordion schließen (aber Card BLEIBT sichtbar!)
                header.setAttribute('aria-expanded', 'false');
                collapse.classList.remove('show');
            }
        });
    }
    
    // ========================================
    // Checkbox-Sortierung
    // ========================================
    
    function sortCheckboxes() {
        console.log('Checkbox-Sort: Sortiere und blende disabled Items aus');
        
        let totalContainers = 0;
        let totalItems = 0;
        
        document.querySelectorAll('.filter-accordion-body').forEach((container, containerIndex) => {
            totalContainers++;
            const scrollContainer = container.querySelector('.filter-values-scroll');
            if (!scrollContainer) {
                console.warn(`Container ${containerIndex}: Kein .filter-values-scroll gefunden!`);
                return;
            }
            
            // Alle Checkbox-Items sammeln
            const checkboxItems = Array.from(scrollContainer.querySelectorAll('.form-check'));
            totalItems += checkboxItems.length;
            
            console.log(`Container ${containerIndex}: ${checkboxItems.length} Checkboxen gefunden`);
            
            // VOR Sortierung: Status loggen
            checkboxItems.forEach((item, i) => {
                const checkbox = item.querySelector('.filter-checkbox');
                const label = item.querySelector('label');
                if (checkbox && label) {
                    console.log(`  [${i}] VOR: ${checkbox.checked ? '✓' : '○'} ${checkbox.disabled ? 'DIS' : 'EN'} - ${label.textContent.trim().substring(0, 30)}`);
                }
            });
            
            // Sortieren nach Priorität
            checkboxItems.sort((a, b) => {
                const checkboxA = a.querySelector('.filter-checkbox');
                const checkboxB = b.querySelector('.filter-checkbox');
                
                if (!checkboxA || !checkboxB) return 0;
                
                // Priorität 1: Checked (ausgewählt) → ganz oben
                if (checkboxA.checked && !checkboxB.checked) return -1;
                if (!checkboxA.checked && checkboxB.checked) return 1;
                
                // Priorität 2: Enabled (wählbar) → Mitte
                if (!checkboxA.disabled && checkboxB.disabled) return -1;
                if (checkboxA.disabled && !checkboxB.disabled) return 1;
                
                // Gleiche Priorität: Original-Reihenfolge beibehalten
                return 0;
            });
            
            // DOM neu anordnen
            checkboxItems.forEach((item, i) => {
                scrollContainer.appendChild(item);
                const checkbox = item.querySelector('.filter-checkbox');
                const label = item.querySelector('label');
                if (checkbox && label) {
                    console.log(`  [${i}] NACH: ${checkbox.checked ? '✓' : '○'} ${checkbox.disabled ? 'DIS' : 'EN'} - ${label.textContent.trim().substring(0, 30)}`);
                }
            });
            
            // Disabled Items ausblenden (außer checked)
            checkboxItems.forEach(item => {
                const checkbox = item.querySelector('.filter-checkbox');
                if (checkbox && checkbox.disabled && !checkbox.checked) {
                    item.style.display = 'none';
                } else {
                    item.style.display = '';
                }
            });
        });
        
        console.log(`Checkbox-Sort abgeschlossen: ${totalContainers} Container, ${totalItems} Items sortiert`);
    }
    
    // ========================================
    // Global verfügbar machen
    // ========================================
    
    window.seedfinderAutoExpand = autoExpandAvailableAccordions;
    window.seedfinderSortCheckboxes = sortCheckboxes;
    
})();
