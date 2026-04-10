/* -----------------------------------------------------------------------------------------
   Product Compare v2.0.0 - Vanilla JS (ohne PHP-Abhängigkeit)

   Hookpoint: templates/tpl_mrh_2026/javascript/extra/
   Wird als <script src="..."> direkt im Template eingebunden.

   v2.0.0: Konvertierung von .js.php → .js
           - Alle PHP-Variablen durch JS-Defaults ersetzt
           - ajaxUrl dynamisch aus window.location ermittelt
           - CSS wird per JS nachgeladen (product_compare.css)
           - Kein PHP-Wrapper mehr nötig
   v1.9.4: BUGFIX - Doppelter Confirm-Dialog behoben
   v1.9.3: BUGFIX - Clear-Button Selector fix + clearAll() global
   v1.9.2: BUGFIX - FPC-sichere Initialisierung
   v1.9.1: BUGFIX - "Liste leeren" per AJAX statt Page-Link
   v1.9.0: Cookie-basierte Persistenz

   @author    Mr. Hanf / Manus AI
   @version   2.0.0
   @date      2026-04-10
   -----------------------------------------------------------------------------------------*/
(function() {
    'use strict';

    // === CSS nachladen (product_compare.css) ===
    (function loadCSS() {
        var tplBase = '/templates/tpl_mrh_2026/';
        // Prüfe ob CSS bereits geladen
        var existing = document.querySelector('link[href*="product_compare.css"]');
        if (!existing) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = tplBase + 'css/product_compare.css';
            document.head.appendChild(link);
        }
    })();

    // === Cookie-Helper Funktionen ===
    function pcSaveCookie(ids) {
        var value = (ids || []).map(Number).filter(function(id){ return id > 0; }).join(',');
        var expires = new Date();
        expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 Tage
        document.cookie = 'pc_compare_ids=' + value + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
    }

    function pcLoadCookie() {
        var match = document.cookie.match(/(?:^|;\s*)pc_compare_ids=([^;]*)/);
        if (match && match[1] && match[1] !== '') {
            return match[1].split(',').map(Number).filter(function(id){ return id > 0; });
        }
        return [];
    }

    function pcClearCookie() {
        document.cookie = 'pc_compare_ids=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;SameSite=Lax';
    }

    // === Konfiguration (ohne PHP - reine JS-Defaults) ===
    var PC = {
        ajaxUrl: '/ajax.php?ext=product_compare',
        compareUrl: '/product_compare.php',
        maxProducts: 6,
        currentProducts: [], // FPC-sicher: IMMER leer, wird per AJAX geladen

        // Texte (Deutsch)
        text: {
            add: 'Vergleichen',
            added: 'Im Vergleich',
            compareNow: 'Jetzt vergleichen',
            msgAdded: 'Produkt zum Vergleich hinzugefügt',
            msgRemoved: 'Produkt aus dem Vergleich entfernt',
            msgAlready: 'Produkt ist bereits im Vergleich',
            msgMaxReached: 'Maximale Anzahl (6) erreicht',
            msgCleared: 'Vergleichsliste geleert'
        },

        // Cache: SKU → products_id Mapping
        skuMap: {}
    };

    // === FPC-sichere Initialisierung per AJAX ===
    var initDone = false;
    function initFromServer() {
        ajaxCompare('list', null, function(data) {
            if (data.success) {
                var serverProducts = (data.products || []).map(Number);
                var serverCount = data.count || 0;

                if (serverCount > 0) {
                    PC.currentProducts = serverProducts;
                    pcSaveCookie(PC.currentProducts);
                    updateBadge(serverCount);
                    updateAllButtons();
                    initDone = true;
                } else {
                    var cookieIds = pcLoadCookie();
                    if (cookieIds.length > 0) {
                        var restoreCount = 0;
                        var totalToRestore = cookieIds.length;

                        PC.currentProducts = cookieIds;
                        updateBadge(cookieIds.length);
                        updateAllButtons();

                        cookieIds.forEach(function(pid) {
                            var url = PC.ajaxUrl + '&sub_action=add&products_id=' + pid;
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', url, true);
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    restoreCount++;
                                    if (restoreCount >= totalToRestore) {
                                        ajaxCompare('list', null, function(data2) {
                                            if (data2.success) {
                                                PC.currentProducts = (data2.products || []).map(Number);
                                                pcSaveCookie(PC.currentProducts);
                                                updateBadge(PC.currentProducts.length);
                                                updateAllButtons();
                                            }
                                        });
                                    }
                                }
                            };
                            xhr.send();
                        });
                    } else {
                        PC.currentProducts = [];
                        updateBadge(0);
                        updateAllButtons();
                    }
                    initDone = true;
                }
            }
        });
    }

    // === Toast-Benachrichtigung ===
    var toastEl = null;
    var toastTimeout = null;

    function createToast() {
        if (toastEl) return;
        toastEl = document.createElement('div');
        toastEl.className = 'compare-toast';
        document.body.appendChild(toastEl);
    }

    function showToast(message, type) {
        createToast();
        toastEl.textContent = message;
        toastEl.className = 'compare-toast ' + (type || 'info');
        void toastEl.offsetWidth;
        toastEl.classList.add('show');

        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(function() {
            toastEl.classList.remove('show');
        }, 3000);
    }

    // === Badge aktualisieren ===
    function updateBadge(count) {
        var badge = document.getElementById('product-compare-badge');
        if (!badge) return;

        var countEl = badge.querySelector('.compare-count');
        if (countEl) countEl.textContent = count;

        if (count > 0) {
            badge.classList.add('active');
        } else {
            badge.classList.remove('active');
        }
    }

    // === AJAX-Aufruf ===
    function ajaxCompare(subAction, productId, callback) {
        var url = PC.ajaxUrl + '&sub_action=' + subAction;
        if (productId) url += '&products_id=' + productId;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (callback) callback(data);
                } catch(e) {
                    console.error('ProductCompare: JSON parse error', e);
                }
            }
        };
        xhr.send();
    }

    // === SKU → products_id per AJAX auflösen ===
    function resolveProductId(sku, callback) {
        if (PC.skuMap[sku]) {
            callback(PC.skuMap[sku]);
            return;
        }

        var url = PC.ajaxUrl + '&sub_action=resolve_sku&sku=' + encodeURIComponent(sku);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success && data.products_id) {
                        PC.skuMap[sku] = data.products_id;
                        callback(data.products_id);
                    }
                } catch(e) {
                    console.error('ProductCompare: SKU resolve error', e);
                }
            }
        };
        xhr.send();
    }

    // === Produkt hinzufügen/entfernen (Toggle) ===
    function toggleCompare(productId, button) {
        if (isNaN(productId)) {
            resolveProductId(productId, function(resolvedId) {
                doToggle(resolvedId, button);
            });
        } else {
            doToggle(productId, button);
        }
    }

    function doToggle(productId, button) {
        productId = parseInt(productId);
        var isInList = PC.currentProducts.indexOf(productId) !== -1;

        if (isInList) {
            ajaxCompare('remove', productId, function(data) {
                if (data.success) {
                    PC.currentProducts = data.products.map(Number);
                    pcSaveCookie(PC.currentProducts);
                    updateBadge(data.count);
                    updateAllButtons();
                    showToast(PC.text.msgRemoved, 'info');
                }
            });
        } else {
            if (PC.currentProducts.length >= PC.maxProducts) {
                showToast(PC.text.msgMaxReached, 'error');
                return;
            }

            ajaxCompare('add', productId, function(data) {
                if (data.success) {
                    PC.currentProducts = data.products.map(Number);
                    pcSaveCookie(PC.currentProducts);
                    updateBadge(data.count);
                    updateAllButtons();
                    showToast(PC.text.msgAdded, 'success');
                } else if (data.message === 'already_in_list') {
                    showToast(PC.text.msgAlready, 'info');
                } else if (data.message === 'max_reached') {
                    showToast(PC.text.msgMaxReached, 'error');
                }
            });
        }
    }

    // === Liste leeren per AJAX ===
    function clearCompare(reloadPage) {
        pcClearCookie();
        PC.currentProducts = [];
        updateBadge(0);
        updateAllButtons();

        ajaxCompare('clear', null, function(data) {
            if (data.success) {
                showToast(PC.text.msgCleared, 'info');
                if (reloadPage) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                }
            }
        });
    }

    // === Alle Buttons aktualisieren ===
    function updateAllButtons() {
        var buttons = document.querySelectorAll('[data-product-id]');
        buttons.forEach(function(btn) {
            // Nur Vergleichs-Buttons (nicht andere Elemente mit data-product-id)
            if (!btn.classList.contains('btn-compare') && !btn.closest('.compare-action')) {
                // Prüfe ob es ein Vergleichs-Button ist (onclick enthält ProductCompare)
                var onclick = btn.getAttribute('onclick') || '';
                if (onclick.indexOf('ProductCompare') === -1) return;
            }

            var pid = parseInt(btn.getAttribute('data-product-id'));
            var isInList = PC.currentProducts.indexOf(pid) !== -1;

            if (isInList) {
                btn.classList.add('active');
                // Icon und Text aktualisieren
                var iconSpan = btn.querySelector('span[class*="fa-"]');
                if (iconSpan) {
                    iconSpan.className = 'fa-solid fa-check me-1';
                }
                // Text-Node aktualisieren (nach dem Icon)
                var textNodes = Array.from(btn.childNodes).filter(function(n) {
                    return n.nodeType === 3 && n.textContent.trim().length > 0;
                });
                if (textNodes.length > 0) {
                    textNodes[textNodes.length - 1].textContent = ' ' + PC.text.added;
                }
            } else {
                btn.classList.remove('active');
                var iconSpan2 = btn.querySelector('span[class*="fa-"]');
                if (iconSpan2) {
                    iconSpan2.className = 'fa-solid fa-scale-balanced me-1';
                }
                var textNodes2 = Array.from(btn.childNodes).filter(function(n) {
                    return n.nodeType === 3 && n.textContent.trim().length > 0;
                });
                if (textNodes2.length > 0) {
                    textNodes2[textNodes2.length - 1].textContent = ' ' + PC.text.add;
                }
            }
        });
    }

    // === Seedfinder-Karten: SKU-basierte Buttons initialisieren ===
    function initSeedfinderButtons() {
        var skuButtons = document.querySelectorAll('.btn-compare[data-sku]:not([data-product-id])');

        skuButtons.forEach(function(btn) {
            var sku = btn.getAttribute('data-sku');
            if (!sku) return;

            resolveProductId(sku, function(productId) {
                btn.setAttribute('data-product-id', productId);

                var isInList = PC.currentProducts.indexOf(parseInt(productId)) !== -1;
                if (isInList) {
                    btn.classList.add('active');
                    var iconSpan = btn.querySelector('span[class*="fa-"]');
                    if (iconSpan) iconSpan.className = 'fa-solid fa-check me-1';
                }
            });
        });
    }

    // === Button-Click-Handler (Event Delegation) ===
    function handleCompareClick(e) {
        var btn = e.target.closest('.btn-compare, [data-product-id][onclick*="ProductCompare"]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        var productId = btn.getAttribute('data-product-id');
        var sku = btn.getAttribute('data-sku');

        if (productId) {
            toggleCompare(productId, btn);
        } else if (sku) {
            toggleCompare(sku, btn);
        }
    }

    // === Clear-Button Click-Handler (Event Delegation) ===
    function handleClearClick(e) {
        var link = e.target.closest('a[href*="action=clear"]');
        if (!link) link = e.target.closest('a.btn-outline-danger[href*="product_compare"]');
        if (!link) link = e.target.closest('a.btn-outline-danger[href*="vergleich"]');
        if (!link) return;

        if (link.hasAttribute('onclick')) return;

        e.preventDefault();
        e.stopPropagation();

        if (confirm('Vergleichsliste wirklich leeren?')) {
            clearCompare(true);
        }
    }

    // === Initialisierung ===
    function init() {
        updateBadge(0);
        initFromServer();
        initSeedfinderButtons();

        // Event Delegation für alle Vergleichen-Buttons
        document.addEventListener('click', handleCompareClick);
        document.addEventListener('click', handleClearClick);

        // MutationObserver für dynamisch geladene Karten (AJAX/Pagination)
        var observer = new MutationObserver(function(mutations) {
            var shouldUpdate = false;
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && (
                            (node.querySelector && node.querySelector('.btn-compare, [data-product-id]'))
                        )) {
                            shouldUpdate = true;
                        }
                    });
                }
            });
            if (shouldUpdate) {
                setTimeout(function() {
                    initSeedfinderButtons();
                    updateAllButtons();
                }, 200);
            }
        });

        var mainContent = document.getElementById('products-container') ||
                          document.querySelector('#content') ||
                          document.body;

        observer.observe(mainContent, {
            childList: true,
            subtree: true
        });
    }

    // DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Globale API
    window.ProductCompare = {
        toggle: toggleCompare,
        update: updateAllButtons,
        clear: clearCompare,
        clearAll: function() { clearCompare(true); },
        getProducts: function() { return PC.currentProducts; },
        getCount: function() { return PC.currentProducts.length; },
        saveCookie: function() { pcSaveCookie(PC.currentProducts); },
        clearCookie: pcClearCookie
    };

    // Globale Shortcut-Funktion für onclick-Aufrufe in Templates
    window.addToCompare = function(productId) {
        toggleCompare(productId);
    };

})();
