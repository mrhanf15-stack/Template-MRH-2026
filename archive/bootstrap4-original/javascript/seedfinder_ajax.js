/**
 * Seedfinder AJAX Handler - v8.0.0 (Performance-Optimierung)
 * Datum: 27. Februar 2026
 * Autor: Mr. Hanf / Manus AI
 * 
 * NEU in v8.0.0:
 * - ALLE console.log/warn/error entfernt (Production-Ready)
 * - Debouncing für Filter-Updates (300ms) - verhindert Mehrfach-Requests bei schnellem Klicken
 * - Request-Cancellation: Vorheriger AJAX-Request wird abgebrochen wenn neuer startet
 * - Doppelte Aufrufe nach loadProducts() eliminiert (Debouncing statt direkter Aufruf)
 * - Leere else-Blöcke und unnötige Variablen entfernt (Code-Cleanup)
 * - Duplikat-Prüfung bei loadProducts() Checkbox-Sammlung
 *
 * Basiert auf v7.0.3
 */

(function() {
    'use strict';

    if (window.SeedfindAjaxInitialized) {
        return;
    }
    window.SeedfindAjaxInitialized = true;

    function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() {
                waitForJQuery(callback);
            }, 50);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            waitForJQuery(init);
        });
    } else {
        waitForJQuery(init);
    }

    function init() {
        var productsContainer = document.getElementById('products-container');

        // v8.0: Request-Cancellation und Debounce-Timer
        var currentFilterXHR = null;
        var filterUpdateTimer = null;

        /**
         * Suchen-Buttons binden (Desktop + Mobile)
         */
        function bindSearchButtons() {
            document.addEventListener('click', function(e) {
                if (e.target && (e.target.id === 'search-filters-desktop' || e.target.closest('#search-filters-desktop'))) {
                    e.preventDefault();
                    loadProducts(1);
                }
                
                if (e.target && (e.target.id === 'search-filters-mobile' || e.target.closest('#search-filters-mobile'))) {
                    e.preventDefault();
                    loadProducts(1);
                    var bottomSheet = document.getElementById('filter-bottom-sheet');
                    if (bottomSheet) {
                        bottomSheet.classList.remove('active');
                    }
                }
            });
        }

        /**
         * Zurücksetzen-Buttons binden (Desktop + Mobile)
         */
        function bindResetButtons() {
            var resetDesktop = document.getElementById('reset-filters-desktop');
            if (resetDesktop) {
                resetDesktop.addEventListener('click', function(e) {
                    e.preventDefault();
                    resetFilters();
                });
            }

            var resetMobile = document.getElementById('reset-filters-mobile');
            if (resetMobile) {
                resetMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    resetFilters();
                });
            }
        }

        /**
         * Checkbox Change Event
         * v8.0: Debounced statt sofortigem AJAX-Call
         */
        function bindCheckboxChange() {
            var checkboxes = document.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateActiveFilterCount();
                    updateActiveChips();
                    // v8.0: Debounced Filter-Update (300ms Verzögerung)
                    debouncedFilterUpdate();
                });
            });
        }

        /**
         * v8.0: Debounced Filter-Update
         * Wartet 300ms nach dem letzten Checkbox-Change bevor AJAX-Request gesendet wird
         * Verhindert Mehrfach-Requests bei schnellem Klicken auf mehrere Checkboxen
         */
        function debouncedFilterUpdate() {
            if (filterUpdateTimer) {
                clearTimeout(filterUpdateTimer);
            }
            filterUpdateTimer = setTimeout(function() {
                updateAvailableFilters();
            }, 300);
        }

        /**
         * Lädt Produkte via AJAX
         */
        function loadProducts(page) {
            var params = new URLSearchParams();

            var urlParams = new URLSearchParams(window.location.search);
            var stage = urlParams.get('stage') || '2';
            var category = urlParams.get('category');

            params.set('stage', stage);
            if (category) {
                params.set('category', category);
            }

            // v8.0: Duplikate verhindern bei Checkbox-Sammlung
            var addedFilters = {};
            var checkboxes = document.querySelectorAll('.filter-checkbox:checked');
            checkboxes.forEach(function(checkbox) {
                var key = checkbox.name + '=' + checkbox.value;
                if (!addedFilters[key]) {
                    addedFilters[key] = true;
                    params.append(checkbox.name, checkbox.value);
                }
            });

            if (page) {
                params.set('page', page);
            }

            var ajaxUrl = 'seedfinder.php?' + params.toString();

            showLoadingIndicator();

            var xhr = new XMLHttpRequest();
            xhr.open('GET', ajaxUrl, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        updateProductsDisplay(xhr.responseText);
                        updateURL(page);
                        // v8.0: Debounced statt direktem Aufruf (verhindert Doppel-Request)
                        debouncedFilterUpdate();
                    } else {
                        hideLoadingIndicator();
                    }
                }
            };

            xhr.send();
        }

        /**
         * Aktualisiert die Produkt-Anzeige mit neuem HTML
         */
        function updateProductsDisplay(html) {
            var temp = document.createElement('div');
            temp.innerHTML = html;

            var newProducts = temp.querySelector('#products-container');
            if (newProducts && productsContainer) {
                productsContainer.innerHTML = newProducts.innerHTML;
            }

            var newPaginationTop = temp.querySelector('#pagination-top');
            var currentPaginationTop = document.getElementById('pagination-top');
            if (newPaginationTop && currentPaginationTop) {
                currentPaginationTop.innerHTML = newPaginationTop.innerHTML;
            }

            var newPaginationBottom = temp.querySelector('#pagination-bottom');
            var currentPaginationBottom = document.getElementById('pagination-bottom');
            if (newPaginationBottom && currentPaginationBottom) {
                currentPaginationBottom.innerHTML = newPaginationBottom.innerHTML;
            }

            if (productsContainer) {
                var offsetTop = productsContainer.offsetTop - 20;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }

            hideLoadingIndicator();
            attachPaginationListeners();
        }

        /**
         * Hängt Event-Listener an Pagination-Links
         */
        function attachPaginationListeners() {
            var paginationLinks = document.querySelectorAll(
                '.pagination a, #pagination-top a, #pagination-bottom a'
            );

            paginationLinks.forEach(function(link) {
                var href = link.getAttribute('href');

                if (!href || href === 'null' || href === '#' || href === '') {
                    return;
                }

                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    var urlParams = new URLSearchParams(href.split('?')[1]);
                    var page = urlParams.get('page');

                    if (page) {
                        loadProducts(page);
                    } else {
                        loadProducts(1);
                    }
                });
            });
        }

        /**
         * Lädt verfügbare Filter-Werte via AJAX
         * v8.0: Mit Request-Cancellation - bricht vorherigen Request ab
         */
        function updateAvailableFilters() {
            // v8.0: Vorherigen Request abbrechen falls noch laufend
            if (currentFilterXHR && currentFilterXHR.readyState !== 4) {
                currentFilterXHR.abort();
            }

            var params = new URLSearchParams();

            var urlParams = new URLSearchParams(window.location.search);
            var stage = urlParams.get('stage') || '2';
            var category = urlParams.get('category');

            params.set('ajax', 'get_available_filters');
            params.set('stage', stage);
            if (category) {
                params.set('category', category);
            }

            var addedAjaxFilters = {};
            var checkboxes = document.querySelectorAll('.filter-checkbox:checked');
            checkboxes.forEach(function(checkbox) {
                var key = checkbox.name + '=' + checkbox.value;
                if (!addedAjaxFilters[key]) {
                    addedAjaxFilters[key] = true;
                    params.append(checkbox.name, checkbox.value);
                }
            });

            var ajaxUrl = 'seedfinder.php?' + params.toString();

            currentFilterXHR = new XMLHttpRequest();
            currentFilterXHR.open('GET', ajaxUrl, true);

            currentFilterXHR.onreadystatechange = function() {
                if (currentFilterXHR.readyState === 4) {
                    if (currentFilterXHR.status === 200) {
                        try {
                            var response = JSON.parse(currentFilterXHR.responseText);
                            updateFilterCounts(response);
                        } catch (e) {
                            // JSON Parse-Fehler ignorieren (z.B. bei abgebrochenem Request)
                        }
                    }
                }
            };

            currentFilterXHR.send();
        }

        /**
         * Aktualisiert Filter-Counts in den Labels
         */
        function updateFilterCounts(response) {
            var filters = response.available_filters || response.filters;
            
            if (!response || !filters) {
                return;
            }

            var checkboxes = document.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(function(checkbox) {
                var filterId = checkbox.getAttribute('data-filter-id');
                var valueId = checkbox.getAttribute('data-value-id');

                var count = 0;
                var available = false;

                if (filters[filterId] && filters[filterId][valueId]) {
                    count = filters[filterId][valueId].count || 0;
                    available = filters[filterId][valueId].enabled !== false;
                }

                var label = checkbox.nextElementSibling;
                if (label) {
                    var countSpan = label.querySelector('.filter-count, .filter-value-count');
                    if (countSpan) {
                        countSpan.textContent = '(' + count + ')';
                        countSpan.style.display = '';
                    }
                }

                var parent = checkbox.closest('.custom-control');
                if (parent) {
                    if (available || checkbox.checked) {
                        parent.classList.remove('filter-disabled');
                        checkbox.disabled = false;
                    } else {
                        parent.classList.add('filter-disabled');
                        checkbox.disabled = true;
                    }
                }
            });

            // Sortierung nach Count-Update
            setTimeout(function() {
                if (typeof window.seedfinderSortCheckboxes === 'function') {
                    window.seedfinderSortCheckboxes();
                }
            }, 50);
        }

        /**
         * Aktualisiert die URL mit aktuellen Filter-Parametern
         */
        function updateURL(page) {
            var params = new URLSearchParams();
            
            var currentParams = new URLSearchParams(window.location.search);
            var stage = currentParams.get('stage');
            var category = currentParams.get('category');
            
            if (stage) params.set('stage', stage);
            if (category) params.set('category', category);

            var addedFilters = {};
            var checkboxes = document.querySelectorAll('.filter-checkbox:checked');
            checkboxes.forEach(function(checkbox) {
                var key = checkbox.name + '=' + checkbox.value;
                if (!addedFilters[key]) {
                    addedFilters[key] = true;
                    params.append(checkbox.name, checkbox.value);
                }
            });
            
            if (page) params.set('page', page);

            var newURL = window.location.pathname + '?' + params.toString();
            window.history.pushState({}, '', newURL);
        }

        /**
         * Alle Filter zurücksetzen
         */
        function resetFilters() {
            var checkboxes = document.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            updateActiveFilterCount();
            updateActiveChips();
            loadProducts(1);
        }

        /**
         * Active Filter Counter aktualisieren
         */
        function updateActiveFilterCount() {
            var count = document.querySelectorAll('.filter-checkbox:checked').length;

            var badges = document.querySelectorAll('#active-filter-count, #active-filter-count-mobile, #active-filter-count-sheet');
            badges.forEach(function(badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? '' : 'none';
            });
        }

        /**
         * Active Chips aktualisieren (horizontal scrollbar)
         */
        function updateActiveChips() {
            var chipsContainer = document.getElementById('active-filters-list');
            var chipsCard = document.getElementById('active-filters-card');

            if (!chipsContainer || !chipsCard) {
                return;
            }

            chipsContainer.innerHTML = '';
            var chips = [];

            var checkboxes = document.querySelectorAll('.filter-checkbox:checked');
            checkboxes.forEach(function(checkbox) {
                var filterId = checkbox.getAttribute('data-filter-id');
                var valueId = checkbox.getAttribute('data-value-id');

                var label = checkbox.nextElementSibling;
                if (label) {
                    var fullLabel = label.textContent.trim();
                    var cleanLabel = fullLabel.replace(/\s*\(\d+\)\s*$/, '').trim();

                    var chip = document.createElement('div');
                    chip.className = 'filter-chip';
                    chip.setAttribute('data-filter-id', filterId);
                    chip.setAttribute('data-value-id', valueId);

                    var span = document.createElement('span');
                    span.textContent = cleanLabel;
                    chip.appendChild(span);

                    var removeBtn = document.createElement('span');
                    removeBtn.className = 'remove-chip';
                    removeBtn.title = 'Entfernen';
                    removeBtn.textContent = '\u00D7';
                    removeBtn.addEventListener('click', function() {
                        checkbox.checked = false;
                        updateActiveFilterCount();
                        updateActiveChips();
                    });
                    chip.appendChild(removeBtn);

                    chips.push(chip);
                }
            });

            if (chips.length > 0) {
                chips.forEach(function(chip) {
                    chipsContainer.appendChild(chip);
                });
                chipsCard.style.display = '';
            } else {
                chipsCard.style.display = 'none';
            }
        }

        /**
         * Loading-Indikator zeigen
         */
        function showLoadingIndicator() {
            if (productsContainer) {
                productsContainer.style.opacity = '0.5';
                productsContainer.style.pointerEvents = 'none';
            }
        }

        /**
         * Loading-Indikator verstecken
         */
        function hideLoadingIndicator() {
            if (productsContainer) {
                productsContainer.style.opacity = '1';
                productsContainer.style.pointerEvents = '';
            }
        }

        // Initialisierung
        bindSearchButtons();
        bindResetButtons();
        bindCheckboxChange();
        updateActiveFilterCount();
        updateActiveChips();
        attachPaginationListeners();
    }

})();
