/**
 * Seedfinder Modal + Filterbar JS v12.2.0 (tpl_mrh_2026)
 * Datum: 2026-04-15
 *
 * v12.2.0: CAPTURE-PHASE Event Delegation – FAW-Widget-sicher
 *          Keine onclick-Attribute auf Quick-Filter-Buttons noetig!
 *          Buttons verwenden data-sf-toggle="ID" und data-sf-action="open-modal"
 * v12.1.0: AJAX-Response-Format korrigiert + Greying-Out
 * v12.0.0: Globale onclick-Funktionen
 *
 * Abhaengigkeiten: Bootstrap 5 (nur Modal-Klasse, kein Popper)
 * KEIN jQuery!
 */

(function() {
    'use strict';

    // Hilfsfunktionen
    function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
    function qsa(sel, ctx) { return (ctx || document).querySelectorAll(sel); }

    // ============================================================
    // DROPDOWN TOGGLE
    // ============================================================
    var activeDD = null;

    function sfToggleInner(filterId) {
        var menu = document.getElementById('sf-quick-dd-' + filterId);
        var btn = document.getElementById('sf-quick-btn-' + filterId);
        if (!menu || !btn) { return; }

        var isOpen = menu.classList.contains('sf-dd-visible');

        // Alle schliessen
        closeAllDropdowns();

        if (!isOpen) {
            menu.classList.add('sf-dd-visible');
            btn.classList.add('sf-dd-open');
            activeDD = filterId;
            var searchInput = menu.querySelector('.sf-dd-search');
            if (searchInput) {
                searchInput.value = '';
                // Verzoegertes Focus um Race Conditions zu vermeiden
                setTimeout(function() { searchInput.focus(); }, 50);
            }
        }
    }

    // Auch als globale Funktion fuer Rueckwaertskompatibilitaet
    window.sfToggle = sfToggleInner;

    function closeAllDropdowns() {
        var allMenus = qsa('.sf-dd-menu.sf-dd-visible');
        var allBtns = qsa('.sf-dd-toggle.sf-dd-open');
        for (var i = 0; i < allMenus.length; i++) allMenus[i].classList.remove('sf-dd-visible');
        for (var j = 0; j < allBtns.length; j++) allBtns[j].classList.remove('sf-dd-open');
        activeDD = null;
    }

    // ============================================================
    // CAPTURE-PHASE EVENT DELEGATION (FAW-Widget-sicher!)
    //
    // Dieser Handler laeuft VOR allen anderen Click-Handlern.
    // Er faengt Klicks auf Quick-Filter-Buttons ab und verhindert,
    // dass das FAW-Widget oder andere Scripts sie umleiten.
    // ============================================================
    document.addEventListener('click', function(e) {
        var target = e.target;

        // 1. Klick auf Quick-Filter-Toggle-Button?
        var toggleBtn = target.closest ? target.closest('[data-sf-toggle]') : null;
        if (toggleBtn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var filterId = toggleBtn.getAttribute('data-sf-toggle');
            if (filterId) {
                sfToggleInner(filterId);
            }
            return;
        }

        // 2. Klick auf "Filter oeffnen" Button (Modal)?
        var modalBtn = target.closest ? target.closest('[data-sf-action="open-modal"]') : null;
        if (modalBtn) {
            e.preventDefault();
            e.stopImmediatePropagation();
            sfOpenModalInner();
            return;
        }

        // 3. Klick innerhalb eines offenen Dropdowns → offen lassen
        if (activeDD) {
            var insideWrap = target.closest ? target.closest('.sf-dd-wrap') : null;
            if (insideWrap) {
                // Innerhalb des Dropdowns → nichts tun
                return;
            }
            // Klick ausserhalb → alle Dropdowns schliessen
            closeAllDropdowns();
        }
    }, true); // ← CAPTURE PHASE!

    // ============================================================
    // SUCHFELD IN DROPDOWN
    // ============================================================
    window.sfSearchDD = function(input, menuId) {
        var term = input.value.toLowerCase();
        var menu = document.getElementById(menuId);
        if (!menu) return;
        var items = menu.querySelectorAll('.sf-dd-item');
        for (var i = 0; i < items.length; i++) {
            var label = items[i].querySelector('.sf-dd-label');
            var text = label ? label.textContent.toLowerCase() : '';
            items[i].style.display = (text.indexOf(term) !== -1) ? '' : 'none';
        }
    };

    // ============================================================
    // MODAL OEFFNEN
    // ============================================================
    function sfOpenModalInner() {
        var modalEl = document.getElementById('seedfinder-filter-modal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            var m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        }
    }
    window.sfOpenModal = sfOpenModalInner;

    // ============================================================
    // FILTER STATE
    // ============================================================
    var filterState = {};
    var debounceTimer = null;
    var categoryId = 0;

    function initFilterState() {
        var bar = qs('#sf-quick-filter-bar');
        categoryId = bar ? (parseInt(bar.getAttribute('data-category-id')) || 0) : 0;

        var checked = qsa('.filter-checkbox:checked');
        for (var i = 0; i < checked.length; i++) {
            var cb = checked[i];
            var fid = String(cb.getAttribute('data-filter-id'));
            var vid = String(cb.getAttribute('data-value-id'));
            if (!fid || fid === 'undefined' || fid === 'null') continue;
            if (!filterState[fid]) filterState[fid] = [];
            if (filterState[fid].indexOf(vid) === -1) filterState[fid].push(vid);
        }
    }

    // ============================================================
    // CHECKBOX CHANGE (aufgerufen via onchange im HTML)
    // ============================================================
    window.sfCheckboxChanged = function(cb) {
        var filterId = String(cb.getAttribute('data-filter-id'));
        var valueId = String(cb.getAttribute('data-value-id'));
        var isChecked = cb.checked;

        // State aktualisieren
        if (!filterState[filterId]) filterState[filterId] = [];
        if (isChecked) {
            if (filterState[filterId].indexOf(valueId) === -1) filterState[filterId].push(valueId);
        } else {
            filterState[filterId] = filterState[filterId].filter(function(v) { return v !== valueId; });
            if (filterState[filterId].length === 0) delete filterState[filterId];
        }

        // Sync: Alle Checkboxen mit gleicher filter-id + value-id synchronisieren
        var allCbs = qsa('input.filter-checkbox[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]');
        for (var i = 0; i < allCbs.length; i++) {
            if (allCbs[i] !== cb) allCbs[i].checked = isChecked;
        }

        // UI aktualisieren
        updateBadges();
        updateFilterTags();

        // AJAX Counts mit Debounce
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchFilterCounts, 400);
    };

    // ============================================================
    // BADGES AKTUALISIEREN
    // ============================================================
    var quickIds = ['73', '12', '11', '8'];

    function updateBadges() {
        var totalActive = 0;

        for (var q = 0; q < quickIds.length; q++) {
            var fid = quickIds[q];
            var count = filterState[fid] ? filterState[fid].length : 0;
            totalActive += count;
            var badge = qs('#sf-quick-badge-' + fid);
            var btn = qs('#sf-quick-btn-' + fid);
            if (count > 0) {
                if (badge) { badge.textContent = count; badge.style.display = ''; }
                if (btn) btn.classList.add('has-active');
            } else {
                if (badge) badge.style.display = 'none';
                if (btn) btn.classList.remove('has-active');
            }
        }

        // Andere Filter zaehlen
        for (var fid2 in filterState) {
            if (filterState.hasOwnProperty(fid2) && quickIds.indexOf(fid2) === -1) {
                totalActive += filterState[fid2].length;
            }
        }

        // Gesamt-Badge
        var barCount = qs('#sf-bar-filter-count');
        var fabCount = qs('#active-filter-count-fab');
        if (totalActive > 0) {
            if (barCount) { barCount.textContent = totalActive; barCount.style.display = ''; }
            if (fabCount) { fabCount.textContent = totalActive; fabCount.style.display = ''; }
        } else {
            if (barCount) barCount.style.display = 'none';
            if (fabCount) fabCount.style.display = 'none';
        }

        // Modal Category Badges
        var categoryMap = {
            'main': ['11','12','8','7','15','73'],
            'genetics': ['12','59','71','72','31','9'],
            'cultivation': ['15','60','34','27'],
            'taste': ['64','65']
        };

        for (var cat in categoryMap) {
            if (!categoryMap.hasOwnProperty(cat)) continue;
            var catCount = 0;
            var ids = categoryMap[cat];
            for (var ci = 0; ci < ids.length; ci++) {
                if (filterState[ids[ci]]) catCount += filterState[ids[ci]].length;
            }
            var badges = qsa('[data-category-badge="' + cat + '"], [data-category-badge="' + cat + '-mobile"]');
            for (var b = 0; b < badges.length; b++) {
                if (catCount > 0) { badges[b].textContent = catCount; badges[b].style.display = ''; }
                else { badges[b].style.display = 'none'; }
            }
        }
    }

    // ============================================================
    // FILTER TAGS (Chips)
    // ============================================================
    function updateFilterTags() {
        var container = qs('#sf-active-tags-list');
        var wrapper = qs('#sf-active-tags');
        if (!container) return;
        container.innerHTML = '';

        var hasActive = false;
        for (var fid in filterState) {
            if (!filterState.hasOwnProperty(fid)) continue;
            var vals = filterState[fid];
            for (var vi = 0; vi < vals.length; vi++) {
                hasActive = true;
                var vid = vals[vi];
                var label = '';

                // Label aus dem DOM holen
                var cb = qs('input.filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]');
                if (cb) {
                    var ddItem = cb.closest ? cb.closest('.sf-dd-item') : null;
                    if (ddItem) {
                        var ddLabel = ddItem.querySelector('.sf-dd-label');
                        if (ddLabel) label = ddLabel.textContent.trim();
                    }
                    if (!label) {
                        var formCheck = cb.closest ? cb.closest('.form-check') : null;
                        if (formCheck) {
                            var formLabel = formCheck.querySelector('.form-check-label');
                            if (formLabel) {
                                label = formLabel.textContent.trim();
                                label = label.replace(/\s*\(\d+\)\s*$/, '');
                            }
                        }
                    }
                }
                if (!label) label = 'Filter ' + fid + ':' + vid;

                var tag = document.createElement('span');
                tag.className = 'badge bg-success text-white sf-filter-tag';
                tag.style.cssText = 'cursor:pointer;font-size:.8rem;padding:.4em .6em;';
                tag.setAttribute('data-fid', fid);
                tag.setAttribute('data-vid', vid);
                tag.innerHTML = label + ' <i class="fa fa-times ms-1"></i>';
                tag.onclick = function() {
                    var tfid = this.getAttribute('data-fid');
                    var tvid = this.getAttribute('data-vid');
                    var tcb = qs('input.filter-checkbox[data-filter-id="' + tfid + '"][data-value-id="' + tvid + '"]');
                    if (tcb) {
                        tcb.checked = false;
                        sfCheckboxChanged(tcb);
                    }
                };
                container.appendChild(tag);
            }
        }

        // Modal active filters sync
        var modalList = qs('#modal-active-filters-list');
        var modalCard = qs('#modal-active-filters-card');
        if (modalList) modalList.innerHTML = container.innerHTML;
        if (hasActive) {
            if (wrapper) wrapper.style.display = '';
            if (modalCard) modalCard.style.display = '';
        } else {
            if (wrapper) wrapper.style.display = 'none';
            if (modalCard) modalCard.style.display = 'none';
        }
    }

    // ============================================================
    // AJAX FILTER COUNTS
    // ============================================================
    function fetchFilterCounts() {
        var queryParts = ['ajax=update_counts', 'category=' + categoryId];
        for (var fid in filterState) {
            if (!filterState.hasOwnProperty(fid)) continue;
            var vals = filterState[fid];
            for (var i = 0; i < vals.length; i++) {
                queryParts.push('filter[' + fid + '][]=' + encodeURIComponent(vals[i]));
            }
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'seedfinder.php?' + queryParts.join('&'), true);
        xhr.timeout = 15000;
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (xhr.status !== 200) return;

            var resp;
            try { resp = JSON.parse(xhr.responseText); } catch(e) { return; }
            if (!resp || !resp.success) return;

            var countsData = resp.data || resp.counts || {};
            var totalProducts = 0;
            var hasTotal = false;

            if (resp.total !== undefined) {
                totalProducts = parseInt(resp.total) || 0;
                hasTotal = true;
            }

            for (var filterId in countsData) {
                if (!countsData.hasOwnProperty(filterId)) continue;
                for (var valueId in countsData[filterId]) {
                    if (!countsData[filterId].hasOwnProperty(valueId)) continue;

                    var entry = countsData[filterId][valueId];
                    var count;
                    if (typeof entry === 'object' && entry !== null) {
                        count = parseInt(entry.count) || 0;
                    } else {
                        count = parseInt(entry) || 0;
                    }

                    var cbs = qsa('input.filter-checkbox[data-filter-id="' + filterId + '"][data-value-id="' + valueId + '"]');
                    for (var ci = 0; ci < cbs.length; ci++) {
                        var formCheck = cbs[ci].closest ? cbs[ci].closest('.form-check') : null;
                        if (formCheck) {
                            var countEl = formCheck.querySelector('.filter-value-count');
                            if (countEl) countEl.textContent = '(' + count + ')';
                            if (count === 0 && !cbs[ci].checked) {
                                cbs[ci].disabled = true;
                                formCheck.classList.add('filter-disabled');
                            } else {
                                cbs[ci].disabled = false;
                                formCheck.classList.remove('filter-disabled');
                            }
                        }

                        var ddItem = cbs[ci].closest ? cbs[ci].closest('.sf-dd-item') : null;
                        if (ddItem) {
                            var ddCount = ddItem.querySelector('.sf-dd-count');
                            if (ddCount) ddCount.textContent = count > 0 ? '(' + count + ')' : '';
                            if (count === 0 && !cbs[ci].checked) {
                                cbs[ci].disabled = true;
                                ddItem.classList.add('sf-dd-disabled');
                            } else {
                                cbs[ci].disabled = false;
                                ddItem.classList.remove('sf-dd-disabled');
                            }
                        }
                    }
                }
            }

            if (hasTotal) {
                var els = [qs('#sf-product-count-value'), qs('#sf-modal-product-count'), qs('#sf-modal-apply-count')];
                for (var ei = 0; ei < els.length; ei++) {
                    if (els[ei]) els[ei].textContent = totalProducts;
                }
            }

            sortFilterOptions();
        };
        xhr.send();
    }

    // ============================================================
    // FILTER-OPTIONEN SORTIEREN
    // ============================================================
    function sortFilterOptions() {
        var scrollables = qsa('.filter-options-scrollable');
        for (var s = 0; s < scrollables.length; s++) {
            var container = scrollables[s];
            var items = Array.prototype.slice.call(container.querySelectorAll('.form-check'));
            if (items.length === 0) continue;
            items.sort(function(a, b) {
                var aChecked = a.querySelector('input:checked') ? 1 : 0;
                var bChecked = b.querySelector('input:checked') ? 1 : 0;
                if (aChecked !== bChecked) return bChecked - aChecked;
                var aDisabled = a.classList.contains('filter-disabled') ? 1 : 0;
                var bDisabled = b.classList.contains('filter-disabled') ? 1 : 0;
                if (aDisabled !== bDisabled) return aDisabled - bDisabled;
                return 0;
            });
            for (var i = 0; i < items.length; i++) container.appendChild(items[i]);
        }

        var ddContainers = qsa('.sf-dd-items');
        for (var d = 0; d < ddContainers.length; d++) {
            var ddContainer = ddContainers[d];
            var ddItems = Array.prototype.slice.call(ddContainer.querySelectorAll('.sf-dd-item'));
            if (ddItems.length === 0) continue;
            ddItems.sort(function(a, b) {
                var aChecked = a.querySelector('input:checked') ? 1 : 0;
                var bChecked = b.querySelector('input:checked') ? 1 : 0;
                if (aChecked !== bChecked) return bChecked - aChecked;
                var aDisabled = a.classList.contains('sf-dd-disabled') ? 1 : 0;
                var bDisabled = b.classList.contains('sf-dd-disabled') ? 1 : 0;
                if (aDisabled !== bDisabled) return aDisabled - bDisabled;
                return 0;
            });
            for (var j = 0; j < ddItems.length; j++) ddContainer.appendChild(ddItems[j]);
        }
    }

    // ============================================================
    // TAB-NAVIGATION (Desktop Modal)
    // ============================================================
    window.sfSwitchTab = function(cat) {
        var allBtns = qsa('.filter-category-btn');
        for (var i = 0; i < allBtns.length; i++) allBtns[i].classList.remove('active');
        var activeBtn = qs('.filter-category-btn[data-category="' + cat + '"]');
        if (activeBtn) activeBtn.classList.add('active');

        var allCats = qsa('.filter-category-content');
        for (var j = 0; j < allCats.length; j++) allCats[j].classList.add('sf-filter-category-hidden');
        var target = qs('#category-' + cat);
        if (target) target.classList.remove('sf-filter-category-hidden');
    };

    // ============================================================
    // MOBILE ACCORDION
    // ============================================================
    window.sfToggleAccordion = function(header) {
        var item = header.closest ? header.closest('.sf-accordion-item') : header.parentElement;
        if (!item) return;
        var body = item.querySelector('.sf-accordion-body');
        if (!body) return;
        var cat = item.getAttribute('data-accordion-category');

        if (body.style.display !== 'none' && body.offsetHeight > 0) {
            body.style.display = 'none';
            item.classList.remove('sf-accordion-open');
        } else {
            if (!body.innerHTML.trim()) {
                var source = qs('#category-' + cat);
                if (source) {
                    body.innerHTML = source.innerHTML;
                    var srcChecked = source.querySelectorAll('.filter-checkbox:checked');
                    for (var i = 0; i < srcChecked.length; i++) {
                        var fid = srcChecked[i].getAttribute('data-filter-id');
                        var vid = srcChecked[i].getAttribute('data-value-id');
                        var tgt = body.querySelector('.filter-checkbox[data-filter-id="' + fid + '"][data-value-id="' + vid + '"]');
                        if (tgt) tgt.checked = true;
                    }
                }
            }
            body.style.display = 'block';
            item.classList.add('sf-accordion-open');
        }
    };

    // ============================================================
    // FILTER ANWENDEN
    // ============================================================
    window.sfApplyFilters = function() {
        var params = ['stage=2', 'category=' + categoryId];
        for (var fid in filterState) {
            if (!filterState.hasOwnProperty(fid)) continue;
            var vals = filterState[fid];
            for (var i = 0; i < vals.length; i++) {
                params.push('filter[' + fid + '][]=' + encodeURIComponent(vals[i]));
            }
        }
        window.location.href = 'seedfinder.php?' + params.join('&');
    };

    // ============================================================
    // FILTER ZURUECKSETZEN
    // ============================================================
    window.sfResetFilters = function() {
        filterState = {};
        var allCbs = qsa('input.filter-checkbox');
        for (var i = 0; i < allCbs.length; i++) {
            allCbs[i].checked = false;
            allCbs[i].disabled = false;
        }
        var disabledItems = qsa('.filter-disabled, .sf-dd-disabled');
        for (var j = 0; j < disabledItems.length; j++) {
            disabledItems[j].classList.remove('filter-disabled');
            disabledItems[j].classList.remove('sf-dd-disabled');
        }
        updateBadges();
        updateFilterTags();
        fetchFilterCounts();
    };

    // ============================================================
    // KATEGORIE WECHSELN
    // ============================================================
    window.sfCategoryChange = function(newCatId) {
        var params = ['stage=2', 'category=' + newCatId];
        for (var fid in filterState) {
            if (!filterState.hasOwnProperty(fid)) continue;
            var vals = filterState[fid];
            for (var i = 0; i < vals.length; i++) {
                params.push('filter[' + fid + '][]=' + encodeURIComponent(vals[i]));
            }
        }
        window.location.href = 'seedfinder.php?' + params.join('&');
    };

    // ============================================================
    // Sortierung Export
    // ============================================================
    window.seedfinderSortCheckboxes = function(container) {
        if (!container) return;
        var items = Array.prototype.slice.call(container.querySelectorAll('.form-check'));
        items.sort(function(a, b) {
            var aChecked = a.querySelector('input:checked') ? 1 : 0;
            var bChecked = b.querySelector('input:checked') ? 1 : 0;
            if (aChecked !== bChecked) return bChecked - aChecked;
            var aDisabled = a.classList.contains('filter-disabled') ? 1 : 0;
            var bDisabled = b.classList.contains('filter-disabled') ? 1 : 0;
            if (aDisabled !== bDisabled) return aDisabled - bDisabled;
            return 0;
        });
        for (var i = 0; i < items.length; i++) container.appendChild(items[i]);
    };

    // ============================================================
    // INITIALISIERUNG
    // ============================================================
    function init() {
        initFilterState();
        updateBadges();
        updateFilterTags();

        var hasFilters = false;
        for (var k in filterState) {
            if (filterState.hasOwnProperty(k)) { hasFilters = true; break; }
        }
        if (hasFilters) {
            fetchFilterCounts();
        }

        console.log('seedfinder_modal v12.2.0 initialisiert (CAPTURE-PHASE, FAW-sicher)');
    }

    // DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
