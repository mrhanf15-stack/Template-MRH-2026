/**
 * Seedfinder Modal Controller v9.0.0 – Vanilla JS + Bootstrap 5
 * Datum: 2026-04-09
 * Autor: Mr. Hanf / Manus AI
 *
 * Passend zu: seedfinder_filters_accordion.html v9.0.0
 *
 * Architektur:
 *   - Desktop (>=768px): Haupt-Filter Cards + "Alle Filter" Button → BS5 Modal
 *   - Mobile (<768px): FAB-Button → gleiches Modal (fullscreen), Accordion-Navigation
 *   - Im Modal: Tab-Navigation (5 Kategorien) mit 3-spaltigem Grid (Desktop)
 *   - Accordion NUR mobil im Modal (nicht standalone auf der Seite)
 *
 * DOM-Hooks (kompatibel mit seedfinder_ajax.js v7.0.1):
 *   .filter-checkbox           – Alle Filter-Checkboxen (Desktop + Modal)
 *   .main-filter-checkbox      – Haupt-Filter auf der Seite
 *   .modal-filter-checkbox     – Filter im Modal
 *   .manufacturer-checkbox     – Hersteller-Checkboxen
 *   #search-filters-desktop    – Suchen-Button im Modal Footer
 *   #reset-filters-desktop     – Reset-Button im Modal Footer
 *   #products-container        – Produkt-Container (AJAX-Target)
 *   #active-filters-card       – Aktive-Filter-Chips Container
 *   #active-filters-list       – Chips-Liste
 *
 * Exportiert: window.seedfinderSortCheckboxes (fuer seedfinder_ajax.js)
 *
 * Changelog:
 *   v9.0.0 – Komplett-Rewrite: jQuery → Vanilla JS, neue DOM-Struktur
 */

(function () {
    'use strict';

    /* ──────────────────────────────────────────────
       Guard: Verhindere doppelte Initialisierung
       ────────────────────────────────────────────── */
    if (window.SeedfinderModalInitialized) return;
    window.SeedfinderModalInitialized = true;

    /* ──────────────────────────────────────────────
       Bootstrap auf DOM-Ready
       ────────────────────────────────────────────── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /* ================================================================
       INIT
       ================================================================ */
    function init() {

        /* ── Elemente ─────────────────────────────── */
        var modalEl         = document.getElementById('seedfinder-filter-modal');
        var openDesktop     = document.getElementById('open-filter-modal');
        var openMobile      = document.getElementById('open-filter-modal-mobile');
        var fabBadge        = document.getElementById('active-filter-count-fab');
        var categoryNav     = document.getElementById('filter-category-nav-desktop');
        var mobileAccordion = document.getElementById('filter-accordion-mobile');
        var categoriesWrap  = document.getElementById('filter-categories-desktop');
        var catSelector     = document.getElementById('modal-category-selector');

        /* ── BS5 Modal Instanz (lazy) ─────────────── */
        var bsModal = null;

        function getModal() {
            if (!bsModal && modalEl && typeof bootstrap !== 'undefined') {
                bsModal = new bootstrap.Modal(modalEl, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
            }
            return bsModal;
        }

        /* ── Breakpoint ───────────────────────────── */
        function isMobile() {
            return window.innerWidth < 768;
        }

        /* ================================================================
           1. MODAL OEFFNEN / SCHLIESSEN
           ================================================================ */

        /** Desktop: "Alle Filter" Button */
        if (openDesktop) {
            openDesktop.addEventListener('click', function (e) {
                e.preventDefault();
                syncMainToModal();
                var m = getModal();
                if (m) m.show();
            });
        }

        /** Mobile: FAB-Button */
        if (openMobile) {
            openMobile.addEventListener('click', function (e) {
                e.preventDefault();
                syncMainToModal();
                var m = getModal();
                if (m) m.show();
            });
        }

        /** Haupt-Filter Buttons (Desktop) */
        var applyMainBtn = document.getElementById('apply-main-filters');
        var resetMainBtn = document.getElementById('reset-main-filters');

        if (applyMainBtn) {
            applyMainBtn.addEventListener('click', function () {
                applyFilters();
            });
        }
        if (resetMainBtn) {
            resetMainBtn.addEventListener('click', function () {
                resetAndReload();
            });
        }

        /** Modal geoeffnet: Mobile-Accordion befuellen */
        if (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function () {
                if (isMobile()) {
                    populateMobileAccordion();
                }
                updateCategoryBadges();
                updateFabBadge();
            });
        }

        /* ================================================================
           2. TAB-NAVIGATION (Desktop im Modal)
           ================================================================ */
        if (categoryNav) {
            categoryNav.addEventListener('click', function (e) {
                var btn = e.target.closest('.filter-category-btn');
                if (!btn) return;

                var cat = btn.getAttribute('data-category');
                if (!cat) return;

                // Alle Tabs deaktivieren
                var allBtns = categoryNav.querySelectorAll('.filter-category-btn');
                allBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');

                // Alle Kategorie-Inhalte verstecken / zeigen
                switchCategory(cat);
            });
        }

        function switchCategory(cat) {
            if (!categoriesWrap) return;
            var panels = categoriesWrap.querySelectorAll('.filter-category-content');
            panels.forEach(function (p) {
                if (p.id === 'category-' + cat) {
                    p.classList.remove('sf-filter-category-hidden');
                } else {
                    p.classList.add('sf-filter-category-hidden');
                }
            });
        }

        /* ================================================================
           3. MOBILE ACCORDION (im Modal)
           ================================================================ */

        /**
         * Befuellt die Accordion-Bodies mit geklontem Content aus den
         * Desktop-Kategorie-Panels (nur beim ersten Oeffnen).
         */
        function populateMobileAccordion() {
            if (!mobileAccordion || !categoriesWrap) return;

            var cats = ['main', 'genetics', 'cultivation', 'taste', 'advanced'];
            cats.forEach(function (cat) {
                var body = document.getElementById('accordion-body-' + cat);
                var source = document.getElementById('category-' + cat);
                if (!body || !source) return;

                // Nur befuellen wenn leer
                if (body.children.length > 0) return;

                // Klone den Inhalt
                var clone = source.cloneNode(true);

                // IDs im Klon anpassen (Duplikate vermeiden)
                var allIds = clone.querySelectorAll('[id]');
                allIds.forEach(function (el) {
                    el.id = el.id + '-mobile';
                });

                // Checkbox-IDs + for-Attribute anpassen
                var inputs = clone.querySelectorAll('input[id]');
                inputs.forEach(function (inp) {
                    var origId = inp.id.replace(/-mobile$/, '');
                    var label = clone.querySelector('label[for="' + origId + '"]');
                    if (label) {
                        label.setAttribute('for', inp.id);
                    }
                });

                body.innerHTML = clone.innerHTML;

                // Checkbox-States synchronisieren
                syncCheckboxStates(source, body);
            });
        }

        /** Accordion-Header Click → Toggle sf-open */
        if (mobileAccordion) {
            mobileAccordion.addEventListener('click', function (e) {
                var header = e.target.closest('.sf-accordion-header');
                if (!header) return;

                var item = header.closest('.sf-accordion-item');
                if (!item) return;

                var wasOpen = item.classList.contains('sf-open');

                // Alle schliessen
                var allItems = mobileAccordion.querySelectorAll('.sf-accordion-item');
                allItems.forEach(function (ai) { ai.classList.remove('sf-open'); });

                // Dieses oeffnen (toggle)
                if (!wasOpen) {
                    item.classList.add('sf-open');
                }
            });
        }

        /* ================================================================
           4. CHECKBOX-SYNC (Haupt ↔ Modal)
           ================================================================ */

        /**
         * Synchronisiert Haupt-Filter-Checkboxen → Modal-Checkboxen
         */
        function syncMainToModal() {
            var mainCbs = document.querySelectorAll('.main-filter-checkbox');
            mainCbs.forEach(function (cb) {
                var fid = cb.getAttribute('data-filter-id');
                var vid = cb.getAttribute('data-value-id');
                var targets = document.querySelectorAll(
                    '.modal-filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]'
                );
                targets.forEach(function (t) {
                    t.checked = cb.checked;
                });
            });
        }

        /**
         * Synchronisiert Modal-Checkboxen → Haupt-Filter-Checkboxen
         */
        function syncModalToMain() {
            var modalCbs = document.querySelectorAll(
                '#filter-categories-desktop .modal-filter-checkbox'
            );
            modalCbs.forEach(function (cb) {
                var fid = cb.getAttribute('data-filter-id');
                var vid = cb.getAttribute('data-value-id');
                var targets = document.querySelectorAll(
                    '.main-filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]'
                );
                targets.forEach(function (t) {
                    t.checked = cb.checked;
                });
            });
        }

        /**
         * Synchronisiert Checkbox-States zwischen zwei Containern
         */
        function syncCheckboxStates(source, target) {
            var sourceCbs = source.querySelectorAll('.filter-checkbox:checked');
            sourceCbs.forEach(function (cb) {
                var fid = cb.getAttribute('data-filter-id');
                var vid = cb.getAttribute('data-value-id');
                var targetCb = target.querySelector(
                    '.filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]'
                );
                if (targetCb) targetCb.checked = true;
            });
        }

        /* ── Checkbox-Change: Bidirektionaler Sync ── */
        document.addEventListener('change', function (e) {
            var cb = e.target;
            if (!cb.classList.contains('filter-checkbox')) return;

            var fid = cb.getAttribute('data-filter-id');
            var vid = cb.getAttribute('data-value-id');
            var isChecked = cb.checked;

            // Alle gleichwertigen Checkboxen synchronisieren
            var all = document.querySelectorAll(
                'input.filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]'
            );
            all.forEach(function (other) {
                if (other !== cb) other.checked = isChecked;
            });

            // UI-Updates
            updateCategoryBadges();
            updateFabBadge();
        });

        /* ================================================================
           5. KATEGORIE-BADGES (Tab-Buttons + Accordion-Headers)
           ================================================================ */

        function updateCategoryBadges() {
            var cats = ['main', 'genetics', 'cultivation', 'taste', 'advanced'];
            cats.forEach(function (cat) {
                var panel = document.getElementById('category-' + cat);
                var accBody = document.getElementById('accordion-body-' + cat);

                // Deduplizierte Zaehlung
                var unique = {};
                var containers = [panel, accBody].filter(Boolean);
                containers.forEach(function (c) {
                    var checked = c.querySelectorAll('.filter-checkbox:checked');
                    checked.forEach(function (cb) {
                        var key = cb.getAttribute('data-filter-id') + '_' + cb.getAttribute('data-value-id');
                        unique[key] = true;
                    });
                });
                var count = Object.keys(unique).length;

                // Desktop-Badge
                var dBadge = document.querySelector('[data-category-badge="' + cat + '"]');
                if (dBadge) {
                    dBadge.textContent = count;
                    dBadge.style.display = count > 0 ? 'inline-block' : 'none';
                }

                // Mobile-Badge
                var mBadge = document.querySelector('[data-category-badge="' + cat + '-mobile"]');
                if (mBadge) {
                    mBadge.textContent = count;
                    mBadge.style.display = count > 0 ? 'inline-flex' : 'none';
                }
            });
        }

        /* ================================================================
           6. FAB-BADGE (Gesamtzahl aktiver Filter)
           ================================================================ */

        function updateFabBadge() {
            if (!fabBadge) return;

            // Deduplizierte Zaehlung ueber alle Checkboxen
            var unique = {};
            var all = document.querySelectorAll('.filter-checkbox:checked');
            all.forEach(function (cb) {
                var key = cb.getAttribute('data-filter-id') + '_' + cb.getAttribute('data-value-id');
                unique[key] = true;
            });
            var count = Object.keys(unique).length;

            fabBadge.textContent = count;
            fabBadge.style.display = count > 0 ? 'flex' : 'none';
        }

        /* ================================================================
           7. KATEGORIE-DROPDOWN (Modal-Header)
           ================================================================ */

        if (catSelector) {
            catSelector.addEventListener('change', function () {
                var newCat = catSelector.value;
                if (!newCat) return;

                var urlParams = new URLSearchParams(window.location.search);
                var stage = urlParams.get('stage') || '2';

                // Aktive Filter beibehalten
                var params = new URLSearchParams();
                params.set('stage', stage);
                params.set('category', newCat);

                var checked = document.querySelectorAll('.filter-checkbox:checked');
                checked.forEach(function (cb) {
                    params.append(cb.name, cb.value);
                });

                params.set('open_filters', '1');

                window.location.href = window.location.pathname + '?' + params.toString();
            });
        }

        /* ================================================================
           8. FILTER ANWENDEN / ZURUECKSETZEN
           ================================================================ */

        function applyFilters() {
            var urlParams = new URLSearchParams(window.location.search);
            var stage = urlParams.get('stage') || '2';
            var category = urlParams.get('category');

            var params = new URLSearchParams();
            params.set('stage', stage);
            if (category) params.set('category', category);

            // Deduplizierte Filter sammeln
            var unique = {};
            var checked = document.querySelectorAll('.filter-checkbox:checked');
            checked.forEach(function (cb) {
                var key = cb.getAttribute('data-filter-id') + '_' + cb.getAttribute('data-value-id');
                if (!unique[key]) {
                    unique[key] = { name: cb.name, value: cb.value };
                }
            });
            Object.values(unique).forEach(function (f) {
                params.append(f.name, f.value);
            });

            window.location.href = window.location.pathname + '?' + params.toString() + '#products-container';
        }

        function resetAndReload() {
            var urlParams = new URLSearchParams(window.location.search);
            var stage = urlParams.get('stage') || '2';
            var category = urlParams.get('category');

            var newUrl = window.location.pathname;
            if (stage && category) {
                newUrl += '?stage=' + stage + '&category=' + category + '&filters_reset=1';
            }
            window.location.href = newUrl;
        }

        /* ================================================================
           9. INITIAL STATE
           ================================================================ */

        // Badges initial setzen
        updateCategoryBadges();
        updateFabBadge();

        // Initiale Sortierung
        setTimeout(function () {
            if (typeof window.seedfinderSortCheckboxes === 'function') {
                window.seedfinderSortCheckboxes();
            }
        }, 200);

    } // end init()

})();


/* ================================================================
   CHECKBOX-SORTIERUNG (Global Export)
   Wird von seedfinder_ajax.js aufgerufen nach Count-Updates.
   Sortiert: Checked → Enabled → Disabled
   Blendet disabled (nicht-checked) aus.
   ================================================================ */
(function () {
    'use strict';

    function sortCheckboxes() {
        // Alle Container mit Filter-Checkboxen
        var containers = document.querySelectorAll(
            '.filter-options, .filter-options-scrollable, .manufacturer-filter-options'
        );

        containers.forEach(function (container) {
            var items = Array.from(container.querySelectorAll('.form-check'));
            if (items.length === 0) return;

            // Sortierung: Checked → Enabled → Disabled, dann alphabetisch
            items.sort(function (a, b) {
                var cbA = a.querySelector('input[type="checkbox"]');
                var cbB = b.querySelector('input[type="checkbox"]');
                if (!cbA || !cbB) return 0;

                var checkedA = cbA.checked;
                var checkedB = cbB.checked;
                var disabledA = cbA.disabled;
                var disabledB = cbB.disabled;

                if (checkedA && !checkedB) return -1;
                if (!checkedA && checkedB) return 1;
                if (!disabledA && disabledB) return -1;
                if (disabledA && !disabledB) return 1;

                // Alphabetisch nach Label-Text
                var labelA = a.querySelector('label');
                var labelB = b.querySelector('label');
                var textA = labelA ? labelA.textContent.replace(/\s*\(\d+\)\s*$/, '').trim() : '';
                var textB = labelB ? labelB.textContent.replace(/\s*\(\d+\)\s*$/, '').trim() : '';
                return textA.localeCompare(textB, 'de');
            });

            // DOM neu aufbauen
            items.forEach(function (item) {
                var cb = item.querySelector('input[type="checkbox"]');
                if (cb && cb.disabled && !cb.checked) {
                    item.style.display = 'none';
                } else {
                    item.style.display = '';
                }
                container.appendChild(item);
            });
        });
    }

    // Global exportieren
    window.seedfinderSortCheckboxes = sortCheckboxes;

})();
