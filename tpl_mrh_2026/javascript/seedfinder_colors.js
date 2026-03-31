/**
 * Seedfinder Dynamic Colors
 * Applies category colors from data-color attributes
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Apply category icon colors
        document.querySelectorAll('.seedfinder-category-icon[data-color]').forEach(function(el) {
            el.style.color = el.getAttribute('data-color');
        });
        
        // Apply category button colors
        document.querySelectorAll('.seedfinder-category-btn[data-color]').forEach(function(el) {
            el.style.backgroundColor = el.getAttribute('data-color');
        });
    });
})();
