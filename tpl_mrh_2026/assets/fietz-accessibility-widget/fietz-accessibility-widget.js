/*!
* 8888888888 8888888 8888888888 88888888888 8888888888P
* 888          888   888            888           d88P
* 888          888   888            888          d88P
* 8888888      888   8888888        888         d88P
* 888          888   888            888        d88P
* 888          888   888            888       d88P
* 888          888   888            888      d88P
* 888        8888888 8888888888     888     d8888888888
*
* FIETZ ACCESSIBILITY WIDGET (faw)
* by FIETZ (fietz-medien.de)
*
* optimized for use with our modified eCommerce Template Revplus
* optimizing for other systems or general use for all websites - in progress
*
*/

/**
 * =======================================================================
 * FIETZ ACCESSIBILITY WIDGET CONFIGURATION
 * =======================================================================
 * Central configuration for the Accessibility Widget
 */
var FIETZ_ACCESSIBILITY_CONFIG = {
    // WCAG Compliance Level - 'AA' or 'AAA'
    // AA: 4.5:1 for normal text, 3:1 for large text
    // AAA: 7:1 for normal text, 4.5:1 for large text
    wcagLevel: 'AAA',

    // Automatically upgrade to AAA when font size is increased
    autoUpgradeToAAA: true,

    // Fallback color when no primary color can be detected
    defaultFallbackColor: '#3369FF', // FIETZ Corporate Blue

    // Enable debug mode (shows console logs)
    debugMode: false,

    // Enable performance monitoring
    performanceMonitoring: false,

    // Performance optimizations
    optimizations: {
        // Lazy loading for non-critical features
        lazyLoadNonCritical: false,

        // Batch processing for DOM queries
        batchDOMProcessing: true,

        // Debounce for multiple initializations
        debounceInitialization: 200,

        // Maximum elements processed per batch
        maxElementsPerBatch: 100,

        // Delay between batches (ms)
        batchDelay: 1,

        // Process visible elements first only
        prioritizeVisibleElements: false
    },

    // Show hover tooltips for contrast ratios (development only)
    showContrastTooltips: false,

    // Use text shadow for medium backgrounds
    useTextShadowFallback: true,

    // Elements that should ALWAYS be colored black (text color)
    alwaysBlackElements: ['#pe_rating', '.pe_u'],

    // Landmark enhancement settings
    landmarkEnhancement: {
        // Enable automatic landmark detection
        enabled: true,

        // Use German labels for landmarks
        useGermanLabels: true,

        // Minimum text length for main content detection
        minContentLength: 200,

        // Elements that should NOT be recognized as landmarks
        excludeSelectors: ['.faw-', '[class*="faw-"]', '#faw-', '[id*="faw-"]'],

        // Maximum allowed nesting depth for div analysis
        maxDivDepth: 4
    },

    // Elements excluded from enhanced hover/focus effects (CSS selectors)
    // Links matching these selectors will NOT get the blue focus outline or hover effects
    excludeFromHoverEffects: [
        '.subcatlist a',
        '.subcats a',
        '.the-subcats a',
        '.mrh-subcategories a'
    ],

    // Elements excluded from automatic contrast correction (CSS selectors)
    // Elements matching these selectors will keep their original colors
    excludeFromContrastCheck: [
        '.box3_header',
        '.footer-heading',
        '[class*="footer"] h4',
        '[class*="footer"] h5'
    ],


    // Text-to-Speech (Read Aloud) settings
    textToSpeech: {
        enabled: true, // Feature availability (not auto-initialization)
        voice: null, // Can be used to set a specific voice later
        lang: 'de-DE' // Default language
    }
};

/**
 * =======================================================================
 * PERFORMANCE MONITORING SYSTEM
 * =======================================================================
 * Tracks execution times and performance metrics for all accessibility features
 */
var FIETZ_PERFORMANCE = {
    startTime: performance.now(),
    measurements: {},

    // Start timing a specific operation
    start: function(operationName) {
        if (!FIETZ_ACCESSIBILITY_CONFIG.performanceMonitoring) return;
        this.measurements[operationName] = {
            start: performance.now(),
            end: null,
            duration: null
        };
    },

    // End timing and calculate duration
    end: function(operationName) {
        if (!FIETZ_ACCESSIBILITY_CONFIG.performanceMonitoring) return;
        if (!this.measurements[operationName]) return;

        this.measurements[operationName].end = performance.now();
        this.measurements[operationName].duration =
            this.measurements[operationName].end - this.measurements[operationName].start;
    },

    // Log performance summary
    logSummary: function() {
        if (!FIETZ_ACCESSIBILITY_CONFIG.performanceMonitoring) return;

        var totalInitTime = performance.now() - this.startTime;
        var summary = {
            'Total Initialization Time': totalInitTime.toFixed(2) + 'ms',
            'Individual Operations': {}
        };

        var totalOperationTime = 0;
        for (var operation in this.measurements) {
            var measurement = this.measurements[operation];
            if (measurement.duration !== null) {
                summary['Individual Operations'][operation] = measurement.duration.toFixed(2) + 'ms';
                totalOperationTime += measurement.duration;
            }
        }

        summary['Total Operation Time'] = totalOperationTime.toFixed(2) + 'ms';
        summary['Overhead Time'] = (totalInitTime - totalOperationTime).toFixed(2) + 'ms';

        console.group('🚀 **FAW:** Performance Report');
        console.log('📊 Performance Summary:', summary);

        // Performance warnings
        if (totalInitTime > 100) {
            console.warn('⚠️ Initialization time is high:', totalInitTime.toFixed(2) + 'ms');
        }
        if (totalInitTime > 900) {
            //console.error('❌ Initialization time is critical:', totalInitTime.toFixed(2) + 'ms');
        }

        // Show slowest operations
        var operations = Object.keys(this.measurements)
            .map(function(name) {
                return {
                    name: name,
                    duration: FIETZ_PERFORMANCE.measurements[name].duration || 0
                };
            })
            .sort(function(a, b) { return b.duration - a.duration; })
            .slice(0, 3);

        if (operations.length > 0) {
            console.log('🐌 Slowest Operations:');
            operations.forEach(function(op, index) {
                console.log(`${index + 1}. ${op.name}: ${op.duration.toFixed(2)}ms`);
            });
        }

        console.groupEnd();
    },

    // Measure function execution time
    measure: function(operationName, fn) {
        if (!FIETZ_ACCESSIBILITY_CONFIG.performanceMonitoring) {
            return fn();
        }

        this.start(operationName);
        var result = fn();
        this.end(operationName);
        return result;
    },

    // Async version for promises/callbacks
    measureAsync: function(operationName, asyncFn) {
        if (!FIETZ_ACCESSIBILITY_CONFIG.performanceMonitoring) {
            return asyncFn();
        }

        this.start(operationName);
        var self = this;
        return Promise.resolve(asyncFn()).finally(function() {
            self.end(operationName);
        });
    }
};

/**
 * =======================================================================
 * PERFORMANCE OPTIMIZER
 * =======================================================================
 * Optimizes processing for better performance on content-heavy pages
 */
var FIETZ_OPTIMIZER = {
    // Queue for delayed processing
    processingQueue: [],
    isProcessing: false,

    // Visibility cache
    visibilityCache: new Map(),

    // Debounce for initialization
    initDebounceTimer: null,

    // Queue element for processing
    queueElement: function(element, processingFunction, priority) {
        if (!FIETZ_ACCESSIBILITY_CONFIG.optimizations.batchDOMProcessing) {
            return processingFunction(element);
        }

        this.processingQueue.push({
            element: element,
            process: processingFunction,
            priority: priority || 1,
            visible: this.isElementVisible(element)
        });

        if (!this.isProcessing) {
            this.processQueue();
        }
    },

    // Process queue
    processQueue: function() {
        if (this.processingQueue.length === 0) {
            this.isProcessing = false;
            return;
        }

        this.isProcessing = true;
        var self = this;
        var config = FIETZ_ACCESSIBILITY_CONFIG.optimizations;

        // Sort by priority (visible elements first)
        if (config.prioritizeVisibleElements) {
            this.processingQueue.sort(function(a, b) {
                if (a.visible && !b.visible) return -1;
                if (!a.visible && b.visible) return 1;
                return b.priority - a.priority;
            });
        }

        // Process batch
        var batchSize = Math.min(config.maxElementsPerBatch, this.processingQueue.length);
        var batch = this.processingQueue.splice(0, batchSize);

        // Process elements in current batch
        for (var i = 0; i < batch.length; i++) {
            try {
                batch[i].process(batch[i].element);
            } catch (error) {
                if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.warn('⚠️ **FAW:** Error processing element in batch:', error);
                }
            }
        }

        // Schedule next batch
        if (this.processingQueue.length > 0) {
            setTimeout(function() {
                self.processQueue();
            }, config.batchDelay);
        } else {
            this.isProcessing = false;
        }
    },

    // Check if element is visible
    isElementVisible: function(element) {
        if (!element || !element.getBoundingClientRect) return false;

        // Cache check
        if (this.visibilityCache.has(element)) {
            return this.visibilityCache.get(element);
        }

        var rect = element.getBoundingClientRect();
        var visible = (
            rect.width > 0 &&
            rect.height > 0 &&
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.top <= window.innerHeight &&
            rect.left <= window.innerWidth
        );

        // Cache result for 5 seconds
        this.visibilityCache.set(element, visible);
        setTimeout(function() {
            FIETZ_OPTIMIZER.visibilityCache.delete(element);
        }, 5000);

        return visible;
    },

    // Chunked array processing (for large element arrays)
    processArrayInChunks: function(array, processingFunction, chunkSize, callback) {
        chunkSize = chunkSize || FIETZ_ACCESSIBILITY_CONFIG.optimizations.maxElementsPerBatch;
        var index = 0;
        var self = this;

        function processChunk() {
            var chunk = array.slice(index, index + chunkSize);

            for (var i = 0; i < chunk.length; i++) {
                try {
                    processingFunction(chunk[i]);
                } catch (error) {
                    if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                        console.warn('⚠️ **FAW:** Error processing chunk element:', error);
                    }
                }
            }

            index += chunkSize;

            if (index < array.length) {
                setTimeout(processChunk, FIETZ_ACCESSIBILITY_CONFIG.optimizations.batchDelay);
            } else if (callback) {
                callback();
            }
        }

        processChunk();
    }
};

/**
 * Automatic CSS Loading Function
 * Dynamically loads the corresponding CSS file based on the current script's location.
 * Prevents duplicate loading and provides fallback path resolution.
 */
function loadAccessibilityCSS() {
    // Prevent duplicate CSS loading by checking for existing stylesheet link
    if (document.querySelector('link[href*="fietz-accessibility-widget"]')) {
        console.log('📄 **FAW:** Accessibility CSS already loaded');
        return;
    }

    // Locate the current script tag to determine CSS path dynamically
    var scripts = document.querySelectorAll('script[src*="fietz-accessibility-widget"]');
    var scriptSrc = '';

    if (scripts.length > 0) {
        scriptSrc = scripts[scripts.length - 1].src;
    }

    // Calculate CSS path based on script location with fallback handling
    var cssPath;
    if (scriptSrc) {
        // Transform JavaScript file path to corresponding CSS file path
        // Support both minified and non-minified versions
        if (scriptSrc.includes('.min.js')) {
            cssPath = scriptSrc.replace('fietz-accessibility-widget.min.js', 'fietz-accessibility-widget.min.css');
        } else {
            cssPath = scriptSrc.replace('fietz-accessibility-widget.js', 'fietz-accessibility-widget.css');
        }
    } else {
        // Fallback path when script source cannot be determined
        cssPath = 'accessability/fietz-accessibility-widget.css';
    }

    // Create and inject CSS link element into document head
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = cssPath;

    // Safely append to document head with error handling
    var head = document.getElementsByTagName('head')[0];
    if (head) {
        head.appendChild(link);
        //console.log('Accessibility CSS loaded from:', cssPath);
    } else {
        //console.warn('Could not find head element to load CSS');
    }
}

/**
 * Configurable fallback color for accessibility contrast calculations
 * Used when no primary color can be detected from CSS variables
 */
const DEFAULT_FALLBACK_COLOR = FIETZ_ACCESSIBILITY_CONFIG.defaultFallbackColor;

/**
 * Primary Color Detection System
 * Automatically detects the website's primary color from various CSS frameworks and variables.
 * Supports Bootstrap 3-5, custom commerce systems, and generic CSS variables.
 * @returns {string} The detected primary color in CSS format (hex, rgb, etc.)
 */
function detectPrimaryColor() {
    const colorVariables = [
        '--tpl-main-color',           // RevPlus Template main color variable
        '--cc-primary-color',         // Custom Commerce primary color variable
        '--bs-primary',               // Bootstrap 5 primary color variable
        '--primary',                  // Bootstrap 4/5 alternative primary variable
        '--color-primary',            // Bootstrap 3 alternative primary variable
        '--primary-color'             // Generic primary color variable
    ];

    const computedStyle = getComputedStyle(document.documentElement);

    // Iterate through modern CSS custom properties first
    for (const variable of colorVariables) {
        const color = computedStyle.getPropertyValue(variable).trim();
        if (color && color !== '') {
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.log(`🎨 **FAW:** Primary color detected from ${variable}: ${color}`);
            }
            return color;
        }
    }

    // Fallback to legacy Bootstrap 3 specific variables
    const bootstrap3Variables = [
        '--brand-primary',
        '--btn-primary-bg'
    ];

    for (const variable of bootstrap3Variables) {
        const color = computedStyle.getPropertyValue(variable).trim();
        if (color && color !== '') {
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.log(`🎨 **FAW:** Primary color detected from Bootstrap 3 ${variable}: ${color}`);
            }
            return color;
        }
    }

    // Use configurable fallback when no CSS variables are found
    if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
        console.log(`🎨 **FAW:** No primary color CSS variable found, using fallback: ${DEFAULT_FALLBACK_COLOR}`);
    }
    return DEFAULT_FALLBACK_COLOR;
}

/**
 * Primary Color Detection and Threshold Calculation
 * Initializes the color system for accessibility contrast adjustments
 */
let primaryCiColor = detectPrimaryColor();
// Debug: console.log('primary color: '+primaryCiColor); // Example output: rgb(216, 183, 90)
const primaryColor = primaryCiColor;
const threshold = calculateThreshold(primaryColor);

/**
 * Luminance-Based Threshold Calculation
 * Calculates the relative luminance of a color to determine appropriate contrast adjustments.
 * Uses the standard luminance formula: L = 0.299*R + 0.587*G + 0.114*B
 * @param {string} color - Color in hex (#RRGGBB) or rgb(r,g,b) format
 * @returns {number|string} Luminance value (0-1) for light colors, empty string for dark colors
 */
function calculateThreshold(color) {
    // Process hexadecimal color format (#RRGGBB)
    if (color.startsWith('#')) {
        const hex = color.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        const rgb = [r, g, b];

        // Apply standard luminance calculation formula (ITU-R BT.709)
        const luminance = (0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2]) / 255;

        // Return luminance for light colors (>0.5), empty for dark colors
        // Debug: console.log('primary color luminance: ' + luminance);
        if (luminance > 0.5) {
            return (luminance); // Light background requires darkening
        } else {
            return ''; // Dark background requires no adjustment
        }
    }

    // Process RGB color format (rgb(r, g, b) or rgba(r, g, b, a))
    const rgbMatch = color.match(/\d+/g);
    if (rgbMatch && rgbMatch.length >= 3) {
        const rgb = rgbMatch.map(Number);

        // Apply standard luminance calculation formula (ITU-R BT.709)
        const luminance = (0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2]) / 255;

        // Return luminance for light colors (>0.5), empty for dark colors
        // Debug: console.log('primary color luminance: ' + luminance);
        if (luminance > 0.5) {
            return (luminance); // Light background requires darkening
        } else {
            return ''; // Dark background requires no adjustment
        }
    }

    // Return empty string for unrecognized color formats
    return '';
}

/**
 * Color Darkening Function
 * Reduces the brightness of a color by subtracting a specified amount from each RGB component.
 * Ensures RGB values stay within valid range (0-255).
 * @param {string} color - Input color in hex (#RRGGBB) or rgb() format
 * @param {number} amount - Amount to subtract from each RGB component (0-255)
 * @returns {string} Darkened color in rgb() format
 */
function darkenColor(color, amount) {
    // Process hexadecimal color format and convert to RGB
    if (color.startsWith('#')) {
        const hex = color.replace('#', '');
        const r = Math.max(parseInt(hex.substring(0, 2), 16) - amount, 0);
        const g = Math.max(parseInt(hex.substring(2, 4), 16) - amount, 0);
        const b = Math.max(parseInt(hex.substring(4, 6), 16) - amount, 0);
        return `rgb(${r}, ${g}, ${b})`;
    }

    // Process RGB/RGBA color format
    const rgbMatch = color.match(/\d+/g);
    if (rgbMatch && rgbMatch.length >= 3) {
        const rgb = rgbMatch.map(Number);

        // Subtract amount from each RGB component, ensuring minimum value of 0
        const newRgb = rgb.map(value => Math.max(value - amount, 0));

        // Return darkened color in standardized rgb() format
        const newColor = `rgb(${newRgb.join(', ')})`;

        return newColor;
    }

    // Return original color if format is not recognized
    return color;
}

/**
 * Adaptive Color Processing
 * Applies darkening to light colors for improved accessibility contrast.
 * Dark colors are used as-is since they already provide sufficient contrast.
 */
// Debug: console.log(threshold); // Shows luminance value or empty string
if (threshold != ''){
    // Apply proportional darkening based on luminance (threshold * 100 = percentage)
    // Debug: console.log('needs to be darkened by: '+threshold*100+'%');
    var accessibleprimaryCiColor = darkenColor(primaryColor, threshold*100);
    // Debug: console.log('new color: '+accessibleprimaryCiColor); // Example: rgb(196, 163, 70)
} else {
    // Use original color for dark backgrounds (no darkening needed)
    accessibleprimaryCiColor = primaryColor;
    // Debug: console.log('Color is already dark enough for accessibility');
}

(()=>{
    "use strict";
    /**
     * Object.assign Polyfill
     * Provides compatibility for older browsers that don't support Object.assign
     */
    var objectAssign = function() {
        return objectAssign = Object.assign || function(target) {
            for (var source, i = 1, n = arguments.length; i < n; i++)
                for (var key in source = arguments[i])
                    Object.prototype.hasOwnProperty.call(source, key) && (target[key] = source[key]);
            return target
        }
        ,
        objectAssign.apply(this, arguments)
    }
      , globalSettings = {}
      , cookieName = "faw";

    /**
     * Update Settings Function
     * Merges new settings with existing ones and triggers save to cookie
     * @param {Object} newSettings - Settings object to merge
     * @returns {Object} Updated settings object
     */
    function updateSettings(newSettings) {
        var updatedSettings = objectAssign(objectAssign({}, globalSettings), {
            states: objectAssign(objectAssign({}, globalSettings.states), newSettings)
        });
        return saveSettings(updatedSettings),
        updatedSettings
    }

    /**
     * Save Settings Function
     * Persists settings to browser cookie with 1-day expiration
     * @param {Object} settings - Settings object to save
     */
    function saveSettings(settings) {
        globalSettings = objectAssign(objectAssign({}, globalSettings), settings),
        function(cookieName, cookieValue, expirationDays) {
            var expirationDate = new Date;
            expirationDate.setTime(expirationDate.getTime() + 24 * expirationDays * 60 * 60 * 1e3);
            var expires = "expires=" + expirationDate.toUTCString();
            document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/"
        }(cookieName, JSON.stringify(globalSettings))
    }

    /**
     * Get Single Setting Value
     * Retrieves a specific setting value from the current state
     * @param {string} settingKey - Key of the setting to retrieve
     * @returns {*} Setting value or undefined
     */
    function getSetting(settingKey) {
        var states;
        return null === (states = null == globalSettings ? void 0 : globalSettings.states) || void 0 === states ? void 0 : states[settingKey]
    }

    /**
     * Load Settings Function
     * Loads settings from cookie or returns current global settings
     * @param {boolean} useCache - Whether to use cached settings (default: true)
     * @returns {Object} Current settings object
     */
    function loadSettings(useCache) {
        if (void 0 === useCache && (useCache = !0),
        useCache)
            return globalSettings;
        var cookieValue = function(cookieName) {
            for (var searchString = cookieName + "=", cookieArray = decodeURIComponent(document.cookie).split(";"), i = 0; i < cookieArray.length; i++) {
                for (var cookie = cookieArray[i]; " " == cookie.charAt(0); )
                    cookie = cookie.substring(1);
                if (0 == cookie.indexOf(searchString))
                    return cookie.substring(searchString.length, cookie.length)
            }
            return ""
        }(cookieName);
        return cookieValue && (globalSettings = JSON.parse(cookieValue)),
        globalSettings
    }
    /**
     * Font Size Adjustment Function
     * Dynamically adjusts font sizes across the website based on a scaling factor.
     * Preserves original font sizes and excludes icon fonts from scaling.
     * @param {number} scaleFactor - Scaling factor for font sizes (default: 1.0)
     */
    function adjustFontSizes(scaleFactor) {
        void 0 === scaleFactor && (scaleFactor = 1),
        document.querySelectorAll("h1,h2,h3,h4,h5,h6,p,a,dl,dt,li,ol,th,td,main span,footer span, blockquote,.faw-text,main div,footer div, main small,footer small, main strong,footer strong").forEach((function(element) {
            var originalSize;
            // Skip icon fonts to prevent visual issues
            if (!element.classList.contains("material-icons") && !element.classList.contains("fa")) {
                // Get stored original font size or calculate it
                var originalFontSize = Number(null !== (originalSize = element.getAttribute("data-faw-orgFontSize")) && void 0 !== originalSize ? originalSize : 0);
                originalFontSize || (originalFontSize = parseInt(window.getComputedStyle(element).getPropertyValue("font-size")),
                element.setAttribute("data-faw-orgFontSize", String(originalFontSize)));
                // Apply scaling factor to original font size
                var newFontSize = originalFontSize * scaleFactor;
                element.style["font-size"] = newFontSize + "px"
            }
        }
        ));
        // Update percentage display in UI
        var percentageDisplay = document.querySelector(".faw-amount");
        percentageDisplay && (percentageDisplay.innerText = "".concat((100 * scaleFactor).toFixed(0), "%"))
    }
    /**
     * CSS Injection Function
     * Injects CSS styles into the document head, either updating existing styles or creating new ones.
     * @param {Object} options - Configuration object
     * @param {string} options.id - Unique identifier for the style element
     * @param {string} options.css - CSS content to inject
     */
    function injectCSS(options) {
        var styleId = options.id
          , cssContent = options.css;
        if (cssContent) {
            // Find existing style element or create new one
            var styleElement = document.getElementById(styleId || "") || document.createElement("style");
            styleElement.innerHTML = cssContent,
            // Add to document if not already present
            styleElement.id || (styleElement.id = styleId,
            document.head.appendChild(styleElement))
        }
    }
    /**
     * Browser Vendor Prefixes for CSS Properties
     * Used for cross-browser compatibility of CSS features
     */
    var vendorPrefixes = ["-o-", "-ms-", "-moz-", "-webkit-", ""]
      , propertiesRequiringPrefixes = ["filter"];

    /**
     * CSS Generator Function
     * Generates CSS strings with vendor prefixes and selector combinations.
     * @param {Object} styleConfig - Configuration object for CSS generation
     * @param {Object} styleConfig.styles - CSS properties and values
     * @param {string} styleConfig.selector - Base CSS selector
     * @param {Array} styleConfig.childrenSelector - Child selectors to combine
     * @param {string} styleConfig.css - Additional CSS content
     * @returns {string} Generated CSS string
     */
    function generateCSS(styleConfig) {
        var additionalCSS, generatedCSS = "";
        return styleConfig && ((generatedCSS += function(styles) {
            var cssOutput = "";
            if (styles) {
                var processProperty = function(property) {
                    (propertiesRequiringPrefixes.includes(property) ? vendorPrefixes : [""]).forEach((function(prefix) {
                        cssOutput += "".concat(prefix).concat(property, ":").concat(styles[property], " !important;")
                    }
                    ))
                };
                for (var property in styles)
                    processProperty(property)
            }
            return cssOutput
        }(styleConfig.styles)).length && styleConfig.selector && (generatedCSS = function(selectorConfig) {
            var baseSelector = selectorConfig.selector
              , childSelectors = selectorConfig.childrenSelector
              , defaultSelectors = void 0 === childSelectors ? [""] : childSelectors
              , cssContent = selectorConfig.css
              , combinedCSS = "";
            return defaultSelectors.forEach((function(childSelector) {
                combinedCSS += "".concat(baseSelector, " ").concat(childSelector, "{").concat(cssContent, "}")
            }
            )),
            combinedCSS
        }({
            selector: styleConfig.selector,
            childrenSelector: styleConfig.childrenSelector,
            css: generatedCSS
        })),
        generatedCSS += null !== (additionalCSS = styleConfig.css) && void 0 !== additionalCSS ? additionalCSS : ""),
        generatedCSS
    }
    /**
     * Style Application Function
     * Applies or removes accessibility styles and manages corresponding CSS classes.
     * @param {Object} styleOptions - Style configuration object
     * @param {string} styleOptions.id - Unique identifier for the style
     * @param {boolean} styleOptions.enable - Whether to enable or disable the style
     */
    function applyAccessibilityStyle(styleOptions) {
        var existingElement, styleId = styleOptions.id,
            defaultId = void 0 === styleId ? "" : styleId,
            enableStyle = styleOptions.enable,
            isEnabled = void 0 !== enableStyle && enableStyle,
            cssClassName = "faw-".concat(defaultId);

        if (isEnabled) {
            // Apply styles by injecting CSS
            injectCSS({
                css: generateCSS(styleOptions),
                id: cssClassName
            })
        } else {
            // Remove styles by deleting style element
            null === (existingElement = document.getElementById(cssClassName)) || void 0 === existingElement || existingElement.remove()
        }

        // Toggle CSS class on document root for styling hooks
        document.documentElement.classList.toggle(cssClassName, isEnabled)
    }
    /**
     * Array Concatenation Helper Function
     * Provides array spreading functionality for compatibility
     */
    var arraySpread = function(target, source, includeUndefined) {
        if (includeUndefined || 2 === arguments.length)
            for (var result, index = 0, sourceLength = source.length; index < sourceLength; index++)
                !result && index in source || (result || (result = Array.prototype.slice.call(source, 0, index)),
                result[index] = source[index]);
        return target.concat(result || Array.prototype.slice.call(source))
    }

    /**
     * CSS Selector Groups for Accessibility Features
     */
    var baseSelectors = ["", "*:not(.material-icons,.faw-menu,.faw-menu *)"]
      , headingSelectors = ["h1", "h2", "h3", "h4", "h5", "h6", ".wsite-headline", ".wsite-content-title", ":root"]
      , textElements = arraySpread(arraySpread([], headingSelectors, !0), ["img", "p", "i", "svg", "a", "button:not(.faw-btn)", "label", "li", "ol"], !1)
    /**
     * Contrast Filter Configurations
     * Defines different contrast modes and their corresponding CSS styles
     */
    var contrastFilters = {
        "dark-contrast": {
            styles: {
                filter: "brightness(0.8) contrast(150%)",
                "--tpl-main-color": accessibleprimaryCiColor
            }
        },
        "light-contrast": {
            styles: {
                color: "#000",
                fill: "#000",
                "background-color": "#FFF",
                "--tpl-main-color": accessibleprimaryCiColor
            },
            childrenSelector: textElements
        },
        "high-contrast": {
            styles: {
                filter: "contrast(125%)",
                "--tpl-main-color": accessibleprimaryCiColor
            }
        },
        "high-saturation": {
            styles: {
                filter: "saturate(200%)"
            }
        },
        "low-saturation": {
            styles: {
                filter: "saturate(50%)"
            }
        },
        monochrome: {
            styles: {
                filter: "grayscale(100%)"
            }
        }
    };
    // Object.assign alias for contrast filters
    var objectAssignForContrast = objectAssign;
    /**
     * Contrast Filter Application Function
     * Applies contrast filters based on current settings state
     */
    function applyContrastFilters() {
        var currentContrastSetting = loadSettings().states.contrast
          , generatedCSS = ""
          , filterConfig = contrastFilters[currentContrastSetting];
        filterConfig && (generatedCSS = generateCSS(objectAssign(objectAssign({}, filterConfig), {
            selector: "html.aws-filter"
        }))),
        injectCSS({
            css: generatedCSS,
            id: "faw-filter-style"
        }),
        document.documentElement.classList.toggle("aws-filter", Boolean(currentContrastSetting))
    }
    /**
     * Accessibility Feature Configurations
     * These objects define the CSS styles and selectors for various accessibility features
     */

    // Stop Animations Configuration
    var stopAnimationsConfig = {
        id: "stop-animations",
        selector: "html",
        childrenSelector: ["*"],
        styles: {
            transition: "none",
            "animation-fill-mode": "forwards",
            "animation-iteration-count": "1",
            "animation-duration": ".01s"
        }
    };

    // Dyslexia-Friendly Font Configuration
    var dyslexiaFontConfig = {
        id: "readable-font",
        selector: "html",
        childrenSelector: arraySpread(["", "*:not(.material-icons,.fa)"], textElements, !0),
        styles: {
            "font-family": "OpenDyslexic3,Comic Sans MS,Arial,Helvetica,sans-serif"
        }
    };

    // Big Cursor Configuration
    var bigCursorConfig = {
        id: "huge-cursor",
        selector: "body",
        childrenSelector: ["*"],
        styles: {
            cursor: "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='98px' height='98px' viewBox='0 0 48 48'%3E%3Cpath fill='%23E0E0E0' d='M27.8 39.7c-.1 0-.2 0-.4-.1s-.4-.3-.6-.5l-3.7-8.6-4.5 4.2c-.1.2-.3.3-.6.3-.1 0-.3 0-.4-.1-.3-.1-.6-.5-.6-.9V12c0-.4.2-.8.6-.9.1-.1.3-.1.4-.1.2 0 .5.1.7.3l16 15c.3.3.4.7.3 1.1-.1.4-.5.6-.9.7l-6.3.6 3.9 8.5c.1.2.1.5 0 .8-.1.2-.3.5-.5.6l-2.9 1.3c-.2-.2-.4-.2-.5-.2z'/%3E%3Cpath fill='%23212121' d='m18 12 16 15-7.7.7 4.5 9.8-2.9 1.3-4.3-9.9L18 34V12m0-2c-.3 0-.5.1-.8.2-.7.3-1.2 1-1.2 1.8v22c0 .8.5 1.5 1.2 1.8.3.2.6.2.8.2.5 0 1-.2 1.4-.5l3.4-3.2 3.1 7.3c.2.5.6.9 1.1 1.1.2.1.5.1.7.1.3 0 .5-.1.8-.2l2.9-1.3c.5-.2.9-.6 1.1-1.1.2-.5.2-1.1 0-1.5l-3.3-7.2 4.9-.4c.8-.1 1.5-.6 1.7-1.3.3-.7.1-1.6-.5-2.1l-16-15c-.3-.5-.8-.7-1.3-.7z'/%3E%3C/svg%3E\") 40 15, auto"
        }
    };

    // Title Highlighting Configuration
    var titleHighlightConfig = {
        id: "highlight-title",
        selector: "html",
        childrenSelector: headingSelectors,
        styles: {
            outline: "2px solid #334696",
            "outline-offset": "2px"
        }
    };

    // Reading Guide HTML Template
    const readingGuideHTML = '<style>.faw-rg{position:fixed;top:0;left:0;right:0;width:100%;height:0;pointer-events:none;background-color:rgba(0,0,0,.8);z-index:1000000}</style> <div class="faw-rg faw-rg-top"></div> <div class="faw-rg faw-rg-bottom" style="top:auto;bottom:0"></div>';

    // Link Highlighting Configuration
    var linkHighlightConfig = {
        id: "highlight-links",
        selector: "html",
        childrenSelector: ["a[href]"],
        styles: {
            outline: "2px solid #334696",
            "outline-offset": "2px"
        }
    };

    // Letter Spacing Configuration
    var letterSpacingConfig = {
        id: "letter-spacing",
        selector: "html",
        childrenSelector: baseSelectors,
        styles: {
            "letter-spacing": "2px",
            "word-spacing": "4px"
        }
    };

    // Line Height Configuration
    var lineHeightConfig = {
        id: "line-height",
        selector: "main,footer",
        childrenSelector: baseSelectors,
        styles: {
            "line-height": "3"
        }
    };

    // Font Weight Configuration
    var fontWeightConfig = {
        id: "font-weight",
        selector: "html",
        childrenSelector: baseSelectors,
        styles: {
            "font-weight": "700"
        }
    };
    /**
     * Apply All Accessibility Features
     * Reads current settings and applies all accessibility features accordingly.
     * This is the main function that coordinates all accessibility enhancements.
     */
    function applyAllAccessibilityFeatures() {
        var highlightTitleEnabled, currentSettings = loadSettings().states;

        // Apply title highlighting
        void 0 === (highlightTitleEnabled = currentSettings["highlight-title"]) && (highlightTitleEnabled = !1),
        applyAccessibilityStyle(objectAssign(objectAssign({}, titleHighlightConfig), {
            enable: highlightTitleEnabled
        })),

        // Apply link highlighting
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, linkHighlightConfig), {
                enable: isEnabled
            }))
        }(currentSettings["highlight-links"]),

        // Apply letter spacing
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, letterSpacingConfig), {
                enable: isEnabled
            }))
        }(currentSettings["letter-spacing"]),

        // Apply line height adjustment
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, lineHeightConfig), {
                enable: isEnabled
            }))
        }(currentSettings["line-height"]),

        // Apply font weight enhancement
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, fontWeightConfig), {
                enable: isEnabled
            }))
        }(currentSettings["font-weight"]),

        // Apply dyslexia-friendly font
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, dyslexiaFontConfig), {
                enable: isEnabled
            }))
        }(currentSettings["readable-font"]),

        // Apply reading guide
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1);
            var readingGuideContainer = document.querySelector(".faw-rg-container");
            if (isEnabled) {
                if (!readingGuideContainer) {
                    (readingGuideContainer = document.createElement("div")).setAttribute("class", "faw-rg-container"),
                    readingGuideContainer.innerHTML = readingGuideHTML;
                    var topOverlay = readingGuideContainer.querySelector(".faw-rg-top")
                      , bottomOverlay = readingGuideContainer.querySelector(".faw-rg-bottom");
                    window.__faw__onScrollReadableGuide = function(mouseEvent) {
                        topOverlay.style.height = mouseEvent.clientY - 50 + "px",
                        bottomOverlay.style.height = window.innerHeight - mouseEvent.clientY - 100 + "px"
                    }
                    ,
                    document.addEventListener("mousemove", window.__faw__onScrollReadableGuide, {
                        passive: !1
                    }),
                    document.body.appendChild(readingGuideContainer)
                }
            } else
                readingGuideContainer && readingGuideContainer.remove(),
                window.__faw__onScrollReadableGuide && (document.removeEventListener("mousemove", window.__faw__onScrollReadableGuide),
                delete window.__faw__onScrollReadableGuide)
        }(currentSettings["readable-guide"]),

        // Apply animation stopping
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, stopAnimationsConfig), {
                enable: isEnabled
            }))
        }(currentSettings["stop-animations"]),

        // Apply big cursor
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1),
            applyAccessibilityStyle(objectAssign(objectAssign({}, bigCursorConfig), {
                enable: isEnabled
            }))
        }(currentSettings["huge-cursor"]),

        // Apply text-to-speech
        function(isEnabled) {
            void 0 === isEnabled && (isEnabled = !1);
            if (typeof toggleTextToSpeech === 'function') {
                toggleTextToSpeech(isEnabled);
            }
        }(currentSettings["textToSpeech"])
    }

    /**
     * Initialize All Accessibility Systems
     * Main initialization function that applies font sizes, accessibility features, and contrast filters.
     */
    function initializeAccessibility() {
        var currentSettings = loadSettings().states;
        adjustFontSizes((null == currentSettings ? void 0 : currentSettings.fontSize) || 1),
        applyAllAccessibilityFeatures(),
        applyContrastFilters()
    }

    /**
     * Intelligent Alt-Text Generation from Filename
     * Converts image filenames into human-readable alt text using multiple text processing techniques.
     * Handles common naming conventions, removes technical artifacts, and applies German localization.
     * @param {string} filename - Full image path or filename
     * @returns {string} Generated alt text or fallback text
     */
    function generateAltTextFromFilename(filename) {
        if (!filename) return '';

        // Extract filename from path and remove file extension
        var name = filename.split('/').pop().split('.')[0];

        // Convert common filename separators to spaces
        name = name.replace(/[-_+]/g, ' ');

        // Transform camelCase notation into readable words
        name = name.replace(/([a-z])([A-Z])/g, '$1 $2');

        // Remove technical artifacts commonly found in image filenames
        name = name.replace(/\b\d+x\d+\b/g, ''); // Remove image dimensions (e.g., "150x150")
        name = name.replace(/\b\d{3,}\b/g, ''); // Remove long number sequences (likely IDs)

        // Normalize whitespace (remove multiple spaces, trim)
        name = name.replace(/\s+/g, ' ').trim();

        // Apply proper capitalization (sentence case)
        if (name.length > 0) {
            name = name.charAt(0).toUpperCase() + name.slice(1);
        }

        // Provide German fallback for very short or empty results
        if (name.length < 3) {
            name = 'Bild';
        }

        return name;
    }

    /**
     * Batch Image Processing for Alt-Text Generation
     * Scans the DOM for images lacking alt attributes and automatically generates descriptive text.
     * Supports standard img tags as well as lazy-loading implementations.
     * @returns {number} Number of images processed successfully
     */
    function processImagesForAltText() {
        // Query for images with missing, empty, or whitespace-only alt attributes
        var images = document.querySelectorAll('img:not([alt]), img[alt=""], img[alt=" "]');
        var processedCount = 0;

        images.forEach(function(img) {
            // Prevent duplicate processing by checking for processing marker
            if (img.hasAttribute('data-faw-processed')) {
                return;
            }

            // Support multiple image source attributes (standard, lazy-loading, data attributes)
            var src = img.src || img.getAttribute('data-src') || img.getAttribute('data-lazy-src');
            if (src) {
                var altText = generateAltTextFromFilename(src);
                if (altText) {
                    img.setAttribute('alt', altText);
                    img.setAttribute('data-faw-processed', 'true'); // Mark as processed
                    processedCount++;
                    //console.log('Generated alt text for image:', src, '→', altText);
                }
            }
        });

        // Log batch processing results for debugging
        if (processedCount > 0) {
            console.log('🖼️ **FAW:** Auto-generated alt text for', processedCount, 'images');
        }

        return processedCount;
    }

    /**
     * Auto Alt-Text System Initialization
     * Sets up comprehensive monitoring for image alt-text generation including:
     * - Initial processing of existing images
     * - Dynamic monitoring for AJAX-loaded content
     * - Lazy-loading image detection via scroll events
     */

    /**
     * Generate accessible name for button based on context and content
     * @param {HTMLElement} button - Button element to analyze
     * @returns {string} Generated accessible name
     */
    function generateButtonAccessibleName(button) {
        // Check existing accessible name sources (in order of preference)
        var existingName = button.getAttribute('aria-label') ||
                          button.getAttribute('aria-labelledby') ||
                          button.getAttribute('title') ||
                          (button.textContent ? button.textContent.trim() : '');

        if (existingName && existingName.length > 2) {
            return existingName;
        }

        // Analyze button context and appearance
        var generatedName = '';

        // 1. Check for icon classes and generate meaningful names
        if (button.querySelector('.fa-search, [class*="search"]') || button.classList.contains('search')) {
            generatedName = 'Suchen';
        } else if (button.querySelector('.fa-close, .fa-times, [class*="close"]') || button.classList.contains('close')) {
            generatedName = 'Schließen';
        } else if (button.querySelector('.fa-menu, .fa-bars, [class*="menu"]') || button.classList.contains('menu')) {
            generatedName = 'Menü öffnen';
        } else if (button.querySelector('.fa-user, [class*="login"]') || button.classList.contains('login')) {
            generatedName = 'Anmelden';
        } else if (button.querySelector('.fa-shopping-cart, [class*="cart"]') || button.classList.contains('cart')) {
            generatedName = 'Warenkorb';
        } else if (button.querySelector('.fa-phone, [class*="phone"]') || button.classList.contains('phone')) {
            generatedName = 'Anrufen';
        } else if (button.querySelector('.fa-mail, .fa-envelope, [class*="mail"]') || button.classList.contains('mail')) {
            generatedName = 'E-Mail senden';
        } else if (button.querySelector('.fa-download, [class*="download"]') || button.classList.contains('download')) {
            generatedName = 'Herunterladen';
        } else if (button.querySelector('.fa-upload, [class*="upload"]') || button.classList.contains('upload')) {
            generatedName = 'Hochladen';
        } else if (button.querySelector('.fa-edit, .fa-pencil, [class*="edit"]') || button.classList.contains('edit')) {
            generatedName = 'Bearbeiten';
        } else if (button.querySelector('.fa-trash, .fa-delete, [class*="delete"]') || button.classList.contains('delete')) {
            generatedName = 'Löschen';
        } else if (button.querySelector('.fa-save, [class*="save"]') || button.classList.contains('save')) {
            generatedName = 'Speichern';
        } else if (button.querySelector('.fa-print, [class*="print"]') || button.classList.contains('print')) {
            generatedName = 'Drucken';
        } else if (button.querySelector('.fa-share, [class*="share"]') || button.classList.contains('share')) {
            generatedName = 'Teilen';
        } else if (button.querySelector('.fa-arrow-left, [class*="back"]') || button.classList.contains('back')) {
            generatedName = 'Zurück';
        } else if (button.querySelector('.fa-arrow-right, [class*="next"]') || button.classList.contains('next')) {
            generatedName = 'Weiter';
        }

        // 2. Check form context
        if (!generatedName) {
            var form = button.closest('form');
            if (form) {
                if (button.type === 'submit' || button.getAttribute('type') === 'submit') {
                    generatedName = 'Formular absenden';
                } else if (button.type === 'reset' || button.getAttribute('type') === 'reset') {
                    generatedName = 'Formular zurücksetzen';
                }
            }
        }

        // 3. Check data attributes for hints
        if (!generatedName) {
            var dataAction = button.getAttribute('data-action') || button.getAttribute('data-function');
            if (dataAction) {
                generatedName = 'Aktion: ' + dataAction.replace(/[-_]/g, ' ');
            }
        }

        // 4. Use nearby text as context
        if (!generatedName) {
            var nearbyLabel = '';
            if (button.previousElementSibling && button.previousElementSibling.textContent) {
                nearbyLabel = button.previousElementSibling.textContent.trim();
            } else if (button.nextElementSibling && button.nextElementSibling.textContent) {
                nearbyLabel = button.nextElementSibling.textContent.trim();
            } else if (button.parentElement && button.parentElement.querySelector('label')) {
                var label = button.parentElement.querySelector('label');
                if (label && label.textContent) {
                    nearbyLabel = label.textContent.trim();
                }
            }
            if (nearbyLabel && nearbyLabel.length > 2 && nearbyLabel.length < 50) {
                generatedName = nearbyLabel;
            }
        }

        // 5. Fallback based on position and context
        if (!generatedName) {
            if (button.closest('header, .header')) {
                generatedName = 'Header-Schaltfläche';
            } else if (button.closest('nav, .nav, .navigation')) {
                generatedName = 'Navigations-Schaltfläche';
            } else if (button.closest('footer, .footer')) {
                generatedName = 'Footer-Schaltfläche';
            } else {
                generatedName = 'Schaltfläche';
            }
        }

        return generatedName;
    }

    /**
     * Generate accessible name for link based on context and content
     * @param {HTMLElement} link - Link element to analyze
     * @returns {string} Generated accessible name
     */
    function generateLinkAccessibleName(link) {
        // Check existing accessible name sources
        var existingName = link.getAttribute('aria-label') ||
                          link.getAttribute('aria-labelledby') ||
                          link.getAttribute('title') ||
                          (link.textContent ? link.textContent.trim() : '');

        if (existingName && existingName.length > 2) {
            return existingName;
        }

        var generatedName = '';
        var href = link.getAttribute('href') || '';

        // 1. Analyze href for common patterns
        if (href.includes('mailto:')) {
            var email = href.replace('mailto:', '').split('?')[0];
            generatedName = 'E-Mail an ' + email;
        } else if (href.includes('tel:')) {
            var phone = href.replace('tel:', '').replace(/[^\d+\-\s]/g, '');
            generatedName = 'Anrufen: ' + phone;
        } else if (href.includes('download') || link.hasAttribute('download')) {
            var filename = href.split('/').pop() || 'Datei';
            generatedName = 'Download: ' + filename;
        } else if (href.match(/\.(pdf|doc|docx|xls|xlsx|zip|rar)$/i)) {
            var fileType = href.split('.').pop().toUpperCase();
            var fileName = href.split('/').pop();
            generatedName = fileType + '-Datei herunterladen: ' + fileName;
        } else if (href.includes('#')) {
            var anchor = href.split('#')[1];
            if (anchor) {
                var targetElement = document.getElementById(anchor);
                if (targetElement) {
                    var targetText = '';
                    if (targetElement.textContent) {
                        targetText = targetElement.textContent.trim().substring(0, 30) || anchor;
                    } else {
                        targetText = anchor;
                    }
                    generatedName = 'Springe zu: ' + targetText;
                } else {
                    generatedName = 'Springe zu Abschnitt: ' + anchor.replace(/[-_]/g, ' ');
                }
            }
        } else if (href.match(/^https?:\/\/(?!.*\b(www\.)?([^.]+)\.(de|com|org|net)\b)/)) {
            // External link
            try {
                var domain = new URL(href).hostname;
                generatedName = 'Externer Link zu ' + domain;
            } catch (e) {
                generatedName = 'Externer Link';
            }
        }

        // 2. Check for icon context
        if (!generatedName) {
            if (link.querySelector('.fa-home, [class*="home"]') || link.classList.contains('home')) {
                generatedName = 'Zur Startseite';
            } else if (link.querySelector('.fa-user, [class*="profile"]') || link.classList.contains('profile')) {
                generatedName = 'Zum Profil';
            } else if (link.querySelector('.fa-shopping-cart, [class*="cart"]') || link.classList.contains('cart')) {
                generatedName = 'Zum Warenkorb';
            } else if (link.querySelector('.fa-phone, [class*="contact"]') || link.classList.contains('contact')) {
                generatedName = 'Kontakt';
            } else if (link.querySelector('.fa-info, [class*="about"]') || link.classList.contains('about')) {
                generatedName = 'Über uns';
            }
        }

        // 3. Check navigation context
        if (!generatedName) {
            if (link.closest('nav, .nav, .navigation')) {
                var navText = '';
                if (link.textContent) {
                    navText = link.textContent.trim();
                }
                if (navText && navText.length > 0) {
                    generatedName = 'Navigation: ' + navText;
                } else {
                    generatedName = 'Navigationslink';
                }
            } else if (link.closest('.breadcrumb')) {
                generatedName = 'Breadcrumb-Navigation';
            }
        }

        // 4. Check data attributes
        if (!generatedName) {
            var dataLabel = link.getAttribute('data-label') || link.getAttribute('data-title');
            if (dataLabel) {
                generatedName = dataLabel;
            }
        }

        // 5. Use image/SVG content if link contains only visual elements
        if (!generatedName) {
            var hasOnlyVisualContent = true;
            var visualDescription = '';

            // Check if link has meaningful text content
            var textContent = link.textContent ? link.textContent.trim() : '';
            if (textContent.length > 2) {
                hasOnlyVisualContent = false;
            }

            if (hasOnlyVisualContent) {
                // Check for images with alt text
                var img = link.querySelector('img');
                if (img) {
                    var altText = img.getAttribute('alt');
                    if (altText && altText.trim()) {
                        visualDescription = altText.trim();
                    } else {
                        // Generate from image filename if no alt text
                        var src = img.getAttribute('src') || '';
                        if (src) {
                            var filename = src.split('/').pop().split('.')[0];
                            visualDescription = filename.replace(/[-_]/g, ' ');
                        }
                    }
                }

                // Check for SVG elements
                var svg = link.querySelector('svg');
                if (svg && !visualDescription) {
                    // Try to get SVG title or use/class information
                    var svgTitle = svg.querySelector('title');
                    if (svgTitle && svgTitle.textContent) {
                        visualDescription = svgTitle.textContent.trim();
                    } else {
                        // Look for common icon patterns in classes or use elements
                        var svgClasses = svg.getAttribute('class') || '';
                        var useElement = svg.querySelector('use');
                        var useHref = useElement ? (useElement.getAttribute('href') || useElement.getAttribute('xlink:href')) : '';

                        // Extract icon name from classes or use href
                        var iconName = '';
                        if (svgClasses.includes('icon-')) {
                            iconName = svgClasses.match(/icon-([a-zA-Z-]+)/);
                            iconName = iconName ? iconName[1] : '';
                        } else if (useHref.includes('#')) {
                            iconName = useHref.split('#')[1];
                        }

                        if (iconName) {
                            // Convert icon names to readable descriptions
                            var iconDescriptions = {
                                'home': 'Startseite',
                                'user': 'Benutzer',
                                'profile': 'Profil',
                                'cart': 'Warenkorb',
                                'shopping': 'Einkaufen',
                                'phone': 'Telefon',
                                'contact': 'Kontakt',
                                'mail': 'E-Mail',
                                'email': 'E-Mail',
                                'info': 'Information',
                                'about': 'Über uns',
                                'menu': 'Menü',
                                'search': 'Suche',
                                'close': 'Schließen',
                                'back': 'Zurück',
                                'next': 'Weiter',
                                'prev': 'Vorherige',
                                'previous': 'Vorherige',
                                'download': 'Download',
                                'upload': 'Upload',
                                'edit': 'Bearbeiten',
                                'delete': 'Löschen',
                                'settings': 'Einstellungen',
                                'help': 'Hilfe',
                                'facebook': 'Facebook',
                                'twitter': 'Twitter',
                                'instagram': 'Instagram',
                                'linkedin': 'LinkedIn',
                                'youtube': 'YouTube'
                            };

                            visualDescription = iconDescriptions[iconName.toLowerCase()] || iconName.replace(/[-_]/g, ' ');
                        } else {
                            visualDescription = 'Icon';
                        }
                    }
                }

                // Check for Font Awesome or other icon fonts
                if (!visualDescription) {
                    var iconElement = link.querySelector('[class*="fa-"], [class*="icon-"], .material-icons');
                    if (iconElement) {
                        var iconClasses = iconElement.getAttribute('class') || '';
                        var iconMatch = iconClasses.match(/(?:fa-|icon-)([a-zA-Z-]+)/);
                        if (iconMatch) {
                            var iconName = iconMatch[1];
                            var iconDescriptions = {
                                'home': 'Startseite',
                                'user': 'Benutzer',
                                'cart': 'Warenkorb',
                                'phone': 'Telefon',
                                'envelope': 'E-Mail',
                                'info': 'Information',
                                'bars': 'Menü',
                                'search': 'Suche',
                                'times': 'Schließen',
                                'arrow-left': 'Zurück',
                                'arrow-right': 'Weiter',
                                'download': 'Download',
                                'edit': 'Bearbeiten',
                                'trash': 'Löschen',
                                'cog': 'Einstellungen',
                                'question': 'Hilfe'
                            };
                            visualDescription = iconDescriptions[iconName] || iconName.replace(/-/g, ' ');
                        } else {
                            visualDescription = 'Icon';
                        }
                    }
                }

                if (visualDescription) {
                    generatedName = 'Link: ' + visualDescription;
                }
            }
        }

        // 6. Final fallback
        if (!generatedName) {
            if (href && href !== '#' && href !== '') {
                try {
                    var url = new URL(href, window.location.href);
                    var path = url.pathname.split('/').filter(function(p) { return p; }).pop() || 'Seite';
                    generatedName = 'Link zu: ' + path.replace(/[-_]/g, ' ');
                } catch (e) {
                    generatedName = 'Link';
                }
            } else {
                generatedName = 'Link';
            }
        }

        return generatedName;
    }

    /**
     * Process buttons and links to add accessible names
     * @returns {number} Number of elements processed
     */
    function processElementsForAccessibleNames() {
        return FIETZ_PERFORMANCE.measure('Accessible Names Processing', function() {
            var processedCount = 0;

        // Process buttons without accessible names
        document.querySelectorAll('button, input[type="button"], input[type="submit"], input[type="reset"]').forEach(function(button) {
            // Skip if already processed or has sufficient accessible name
            if (button.getAttribute('data-faw-accessible-processed')) {
                return;
            }

            var currentName = button.getAttribute('aria-label') ||
                             button.getAttribute('aria-labelledby') ||
                             (button.textContent ? button.textContent.trim() : '');

            if (!currentName || currentName.length < 3) {
                var generatedName = generateButtonAccessibleName(button);
                if (generatedName) {
                    button.setAttribute('aria-label', generatedName);
                    button.setAttribute('data-faw-accessible-processed', 'true');
                    processedCount++;
                    //console.log('Generated accessible name for button:', generatedName);
                }
            }
        });

        // Process elements with role="button" - check for nested actual buttons first
        document.querySelectorAll('[role="button"]').forEach(function(roleButton) {
            // Skip if already processed
            if (roleButton.getAttribute('data-faw-accessible-processed')) {
                return;
            }

            // Check if this element contains actual button elements
            var nestedButtons = roleButton.querySelectorAll('button, input[type="button"], input[type="submit"], input[type="reset"]');

            if (nestedButtons.length > 0) {
                // Found nested actual buttons - remove role="button" from wrapper and process the actual buttons
                roleButton.removeAttribute('role');
                roleButton.setAttribute('data-faw-nested-button-processed', 'true');

                nestedButtons.forEach(function(nestedButton) {
                    // Skip if already processed
                    if (nestedButton.getAttribute('data-faw-accessible-processed')) {
                        return;
                    }

                    var currentName = nestedButton.getAttribute('aria-label') ||
                                     nestedButton.getAttribute('aria-labelledby') ||
                                     (nestedButton.textContent ? nestedButton.textContent.trim() : '');

                    if (!currentName || currentName.length < 3) {
                        // Try to get name from the wrapper element first
                        var wrapperLabel = roleButton.getAttribute('aria-label') ||
                                          (roleButton.textContent ? roleButton.textContent.trim() : '');

                        var generatedName = '';
                        if (wrapperLabel && wrapperLabel.length >= 3) {
                            generatedName = wrapperLabel;
                        } else {
                            generatedName = generateButtonAccessibleName(nestedButton);
                        }

                        if (generatedName) {
                            nestedButton.setAttribute('aria-label', generatedName);
                            nestedButton.setAttribute('data-faw-accessible-processed', 'true');
                            processedCount++;
                            //console.log('Generated accessible name for nested button:', generatedName);
                        }
                    }
                });

                roleButton.setAttribute('data-faw-accessible-processed', 'true');
            } else {
                // No nested buttons found, process as normal role="button" element
                var currentName = roleButton.getAttribute('aria-label') ||
                                 roleButton.getAttribute('aria-labelledby') ||
                                 (roleButton.textContent ? roleButton.textContent.trim() : '');

                if (!currentName || currentName.length < 3) {
                    var generatedName = generateButtonAccessibleName(roleButton);
                    if (generatedName) {
                        roleButton.setAttribute('aria-label', generatedName);
                        roleButton.setAttribute('data-faw-accessible-processed', 'true');
                        processedCount++;
                        //console.log('Generated accessible name for role button:', generatedName);
                    }
                }
            }
        });

        // Process links without accessible names
        document.querySelectorAll('a[href]').forEach(function(link) {
            // Skip if already processed or has sufficient accessible name
            if (link.getAttribute('data-faw-accessible-processed')) {
                return;
            }

            var currentName = link.getAttribute('aria-label') ||
                             link.getAttribute('aria-labelledby') ||
                             (link.textContent ? link.textContent.trim() : '');

            if (!currentName || currentName.length < 3) {
                var generatedName = generateLinkAccessibleName(link);
                if (generatedName) {
                    link.setAttribute('aria-label', generatedName);
                    link.setAttribute('data-faw-accessible-processed', 'true');
                    processedCount++;
                    //console.log('Generated accessible name for link:', generatedName);
                }
            }
        });

        // Process form elements for missing labels
        var formElementsProcessed = processFormElementsForLabels();
        processedCount += formElementsProcessed;

        if (processedCount > 0) {
            console.log('🏷️ **FAW:** Auto-generated accessible names for', processedCount, 'elements');
        }

        return processedCount;
        });
    }

    /**
     * Generate accessible label for form elements based on context and type
     * @param {HTMLElement} element - Form element to generate label for
     * @returns {string} Generated accessible label or empty string
     */
    function generateFormElementLabel(element) {
        var elementType = element.type ? element.type.toLowerCase() : '';
        var tagName = element.tagName.toLowerCase();
        var generatedLabel = '';

        // Check for existing label text from associated label element
        var labelElement = null;
        if (element.id) {
            labelElement = document.querySelector('label[for="' + element.id + '"]');
        }
        if (!labelElement) {
            // Check for parent label element
            labelElement = element.closest('label');
        }

        if (labelElement) {
            var labelText = labelElement.textContent.trim();
            if (labelText && labelText.length >= 2) {
                return labelText;
            }
        }

        // Check for placeholder as fallback
        var placeholder = element.getAttribute('placeholder');
        if (placeholder && placeholder.trim().length >= 2) {
            generatedLabel = placeholder.trim();
        }

        // Check for name attribute
        var nameAttr = element.getAttribute('name');
        if (!generatedLabel && nameAttr) {
            // Clean up name attribute for better readability
            generatedLabel = nameAttr
                .replace(/[_-]/g, ' ')
                .replace(/([a-z])([A-Z])/g, '$1 $2') // camelCase to spaced
                .toLowerCase()
                .replace(/\b\w/g, function(l) { return l.toUpperCase(); }); // Capitalize
        }

        // Context-based label generation for specific input types
        switch (elementType) {
            case 'email':
                generatedLabel = generatedLabel || 'E-Mail-Adresse';
                break;
            case 'password':
                generatedLabel = generatedLabel || 'Passwort';
                break;
            case 'tel':
                generatedLabel = generatedLabel || 'Telefonnummer';
                break;
            case 'url':
                generatedLabel = generatedLabel || 'Website-URL';
                break;
            case 'search':
                generatedLabel = generatedLabel || 'Suchen';
                break;
            case 'number':
                generatedLabel = generatedLabel || 'Nummer';
                break;
            case 'date':
                generatedLabel = generatedLabel || 'Datum';
                break;
            case 'time':
                generatedLabel = generatedLabel || 'Uhrzeit';
                break;
            case 'datetime-local':
                generatedLabel = generatedLabel || 'Datum und Uhrzeit';
                break;
            case 'month':
                generatedLabel = generatedLabel || 'Monat';
                break;
            case 'week':
                generatedLabel = generatedLabel || 'Kalenderwoche';
                break;
            case 'color':
                generatedLabel = generatedLabel || 'Farbe auswählen';
                break;
            case 'range':
                generatedLabel = generatedLabel || 'Bereich auswählen';
                break;
            case 'file':
                generatedLabel = generatedLabel || 'Datei auswählen';
                break;
            case 'checkbox':
                generatedLabel = generatedLabel || 'Checkbox';
                break;
            case 'radio':
                generatedLabel = generatedLabel || 'Option auswählen';
                break;
            case 'submit':
                generatedLabel = generatedLabel || 'Absenden';
                break;
            case 'reset':
                generatedLabel = generatedLabel || 'Zurücksetzen';
                break;
            case 'button':
                generatedLabel = generatedLabel || 'Schaltfläche';
                break;
            default:
                if (tagName === 'select') {
                    generatedLabel = generatedLabel || 'Auswahl treffen';
                } else if (tagName === 'textarea') {
                    generatedLabel = generatedLabel || 'Textbereich';
                } else {
                    generatedLabel = generatedLabel || 'Eingabefeld';
                }
                break;
        }

        // Check surrounding context for better label generation
        if (!generatedLabel || generatedLabel === 'Eingabefeld') {
            var contextLabel = findContextualLabel(element);
            if (contextLabel) {
                generatedLabel = contextLabel;
            }
        }

        // Add required indicator if field is required
        if (element.hasAttribute('required') && generatedLabel) {
            generatedLabel += ' (Pflichtfeld)';
        }

        return generatedLabel || '';
    }

    /**
     * Find contextual label from surrounding elements
     * @param {HTMLElement} element - Form element to find context for
     * @returns {string} Contextual label or empty string
     */
    function findContextualLabel(element) {
        var contextualText = '';

        // Check previous sibling text nodes and elements
        var prevSibling = element.previousElementSibling;
        while (prevSibling && !contextualText) {
            var text = prevSibling.textContent.trim();
            if (text && text.length >= 2 && text.length <= 50) {
                // Clean up text (remove colons, etc.)
                contextualText = text.replace(/[:\*\(\)]+$/g, '').trim();
                break;
            }
            prevSibling = prevSibling.previousElementSibling;
        }

        // Check parent elements for fieldset legends or headings
        if (!contextualText) {
            var fieldset = element.closest('fieldset');
            if (fieldset) {
                var legend = fieldset.querySelector('legend');
                if (legend) {
                    contextualText = legend.textContent.trim();
                }
            }
        }

        // Check for nearby headings
        if (!contextualText) {
            var headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            var elementRect = element.getBoundingClientRect();
            var closestHeading = null;
            var minDistance = Infinity;

            for (var i = 0; i < headings.length; i++) {
                var heading = headings[i];
                var headingRect = heading.getBoundingClientRect();

                // Calculate distance between heading and form element
                var distance = Math.sqrt(
                    Math.pow(elementRect.left - headingRect.left, 2) +
                    Math.pow(elementRect.top - headingRect.top, 2)
                );

                // Only consider headings that are above the form element
                if (headingRect.top < elementRect.top && distance < minDistance && distance < 300) {
                    minDistance = distance;
                    closestHeading = heading;
                }
            }

            if (closestHeading) {
                contextualText = closestHeading.textContent.trim();
            }
        }

        return contextualText || '';
    }

    /**
     * Process form elements for missing labels
     * @returns {number} Number of form elements processed
     */
    function processFormElementsForLabels() {
        var processedCount = 0;

        // Select form elements that need labels
        var formElementsSelector = 'input:not([type="hidden"]):not([type="submit"]):not([type="reset"]):not([type="button"]), select, textarea';

        document.querySelectorAll(formElementsSelector).forEach(function(element) {
            // Skip if already processed
            if (element.getAttribute('data-faw-label-processed')) {
                return;
            }

            // Check if element already has a proper label
            var hasLabel = false;

            // Check for aria-label
            if (element.getAttribute('aria-label') && element.getAttribute('aria-label').trim().length >= 2) {
                hasLabel = true;
            }

            // Check for aria-labelledby
            if (!hasLabel && element.getAttribute('aria-labelledby')) {
                var labelledById = element.getAttribute('aria-labelledby');
                var referencedElement = document.getElementById(labelledById);
                if (referencedElement && referencedElement.textContent.trim().length >= 2) {
                    hasLabel = true;
                }
            }

            // Check for associated label element
            if (!hasLabel && element.id) {
                var labelElement = document.querySelector('label[for="' + element.id + '"]');
                if (labelElement && labelElement.textContent.trim().length >= 2) {
                    hasLabel = true;
                }
            }

            // Check for parent label element
            if (!hasLabel) {
                var parentLabel = element.closest('label');
                if (parentLabel && parentLabel.textContent.trim().length >= 2) {
                    hasLabel = true;
                }
            }

            // Generate label if none exists
            if (!hasLabel) {
                var generatedLabel = generateFormElementLabel(element);
                if (generatedLabel && generatedLabel.length >= 2) {
                    element.setAttribute('aria-label', generatedLabel);
                    element.setAttribute('data-faw-label-processed', 'true');
                    processedCount++;
                    //console.log('Generated label for form element:', generatedLabel);
                }
            } else {
                // Mark as processed even if it already has a label
                element.setAttribute('data-faw-label-processed', 'true');
            }
        });

        if (processedCount > 0) {
            console.log('📝 **FAW:** Auto-generated labels for', processedCount, 'form elements');
        }

        return processedCount;
    }

    /**
     * Calculate relative luminance of a color according to WCAG guidelines
     * @param {number} r - Red value (0-255)
     * @param {number} g - Green value (0-255)
     * @param {number} b - Blue value (0-255)
     * @returns {number} Relative luminance (0-1)
     */
    function calculateRelativeLuminance(r, g, b) {
        // Convert RGB to sRGB
        var rsRGB = r / 255;
        var gsRGB = g / 255;
        var bsRGB = b / 255;

        // Apply gamma correction
        var rLinear = rsRGB <= 0.03928 ? rsRGB / 12.92 : Math.pow((rsRGB + 0.055) / 1.055, 2.4);
        var gLinear = gsRGB <= 0.03928 ? gsRGB / 12.92 : Math.pow((gsRGB + 0.055) / 1.055, 2.4);
        var bLinear = bsRGB <= 0.03928 ? bsRGB / 12.92 : Math.pow((bsRGB + 0.055) / 1.055, 2.4);

        // Calculate relative luminance using WCAG formula
        return 0.2126 * rLinear + 0.7152 * gLinear + 0.0722 * bLinear;
    }

    /**
     * Calculate contrast ratio between two colors
     * @param {number} luminance1 - Luminance of first color
     * @param {number} luminance2 - Luminance of second color
     * @returns {number} Contrast ratio (1-21)
     */
    function calculateContrastRatio(luminance1, luminance2) {
        var lighter = Math.max(luminance1, luminance2);
        var darker = Math.min(luminance1, luminance2);
        return (lighter + 0.05) / (darker + 0.05);
    }

    /**
     * Parse color string and extract RGB values
     * @param {string} colorStr - Color string (rgb, rgba, hex, named)
     * @returns {Array|null} RGB array [r, g, b] or null if invalid
     */
    function parseColor(colorStr) {
        if (!colorStr || colorStr === 'transparent') {
            return null;
        }

        // Handle rgb() and rgba() formats
        var rgbMatch = colorStr.match(/rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*[\d.]+)?\s*\)/);
        if (rgbMatch) {
            return [parseInt(rgbMatch[1]), parseInt(rgbMatch[2]), parseInt(rgbMatch[3])];
        }

        // Handle hex colors
        var hexMatch = colorStr.match(/^#([a-f\d]{3}|[a-f\d]{6})$/i);
        if (hexMatch) {
            var hex = hexMatch[1];
            if (hex.length === 3) {
                return [
                    parseInt(hex[0] + hex[0], 16),
                    parseInt(hex[1] + hex[1], 16),
                    parseInt(hex[2] + hex[2], 16)
                ];
            } else {
                return [
                    parseInt(hex.substr(0, 2), 16),
                    parseInt(hex.substr(2, 2), 16),
                    parseInt(hex.substr(4, 2), 16)
                ];
            }
        }

        // Handle named colors (basic set)
        var namedColors = {
            'black': [0, 0, 0],
            'white': [255, 255, 255],
            'red': [255, 0, 0],
            'green': [0, 128, 0],
            'blue': [0, 0, 255],
            'yellow': [255, 255, 0],
            'cyan': [0, 255, 255],
            'magenta': [255, 0, 255],
            'gray': [128, 128, 128],
            'grey': [128, 128, 128],
            'orange': [255, 165, 0],
            'gold': [255, 215, 0],
            'silver': [192, 192, 192]
        };

        if (namedColors[colorStr.toLowerCase()]) {
            return namedColors[colorStr.toLowerCase()];
        }

        return null;
    }

    /**
     * Extract color from gradient string (simplified)
     * @param {string} gradient - CSS gradient string
     * @returns {Array|null} RGB array of dominant color or null
     */
    function extractGradientColor(gradient) {
        if (!gradient) return null;

        // Extract colors from linear/radial gradients
        var colorMatches = gradient.match(/rgba?\([^)]+\)|#[a-f\d]{3,6}|[a-z]+(?=\s|,|$)/gi);
        if (colorMatches && colorMatches.length > 0) {
            // Use the first color as approximation
            return parseColor(colorMatches[0]);
        }

        return null;
    }

    /**
     * Analyze background image to estimate dominant color
     * @param {HTMLElement} element - Element with background image
     * @returns {Array|null} RGB array estimate or null
     */
    function analyzeBackgroundImage(element) {
        var styles = window.getComputedStyle(element);
        var bgImage = styles.backgroundImage;

        if (!bgImage || bgImage === 'none') {
            return null;
        }

        // For gradients, try to extract colors
        if (bgImage.includes('gradient')) {
            return extractGradientColor(bgImage);
        }

        // For images, we can't easily analyze without canvas
        // But we can make educated guesses based on common patterns
        var bgColor = styles.backgroundColor;
        if (bgColor && bgColor !== 'transparent') {
            var parsed = parseColor(bgColor);
            if (parsed) {
                // If there's a background color with image, assume 50% blend
                return [
                    Math.round(parsed[0] * 0.7 + 128 * 0.3), // Assume medium gray overlay
                    Math.round(parsed[1] * 0.7 + 128 * 0.3),
                    Math.round(parsed[2] * 0.7 + 128 * 0.3)
                ];
            }
        }

        // Default estimates based on common image types
        if (bgImage.includes('url(')) {
            var imageUrl = bgImage.match(/url\(['"]?([^'"]*?)['"]?\)/);
            if (imageUrl && imageUrl[1]) {
                var url = imageUrl[1].toLowerCase();

                // Educated guesses based on common naming patterns
                if (url.includes('dark') || url.includes('black')) {
                    return [40, 40, 40]; // Dark gray
                } else if (url.includes('light') || url.includes('white')) {
                    return [220, 220, 220]; // Light gray
                } else if (url.includes('hero') || url.includes('banner')) {
                    return [80, 80, 80]; // Medium-dark (often overlaid with text)
                } else if (url.includes('button') || url.includes('btn')) {
                    return [180, 180, 180]; // Light-medium gray
                }
            }
        }

        // Default to medium gray for unknown images
        return [128, 128, 128];
    }

    /**
     * Check for pseudo-element backgrounds
     * @param {HTMLElement} element - Element to check
     * @returns {Array|null} RGB array or null
     */
    function checkPseudoElementBackground(element) {
        try {
            // Check ::before and ::after pseudo-elements
            var beforeStyles = window.getComputedStyle(element, '::before');
            var afterStyles = window.getComputedStyle(element, '::after');

            for (var i = 0; i < 2; i++) {
                var pseudoStyles = i === 0 ? beforeStyles : afterStyles;

                if (pseudoStyles && pseudoStyles.content !== 'none' && pseudoStyles.content !== '""') {
                    var bgColor = pseudoStyles.backgroundColor;
                    if (bgColor && bgColor !== 'transparent' && bgColor !== 'rgba(0, 0, 0, 0)') {
                        var parsed = parseColor(bgColor);
                        if (parsed) return parsed;
                    }

                    // Check for background images in pseudo-elements
                    var bgImage = pseudoStyles.backgroundImage;
                    if (bgImage && bgImage !== 'none') {
                        var imageColor = analyzeBackgroundImage({ style: {},
                            getBoundingClientRect: function() { return {}; }
                        });
                        if (imageColor) return imageColor;
                    }
                }
            }
        } catch (e) {
            // Pseudo-element access might fail in some browsers
            console.debug('Could not access pseudo-element styles:', e);
        }

        return null;
    }

    /**
     * Advanced background detection using canvas sampling (when possible)
     * @param {HTMLElement} element - Element to analyze
     * @returns {Array|null} RGB array or null
     */
    function sampleElementBackground(element) {
        try {
            // Create a temporary canvas for color sampling
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            if (!ctx) return null;

            var rect = element.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return null;

            canvas.width = Math.min(rect.width, 100);
            canvas.height = Math.min(rect.height, 100);

            // Try to draw the element's background
            // This is complex and might not work reliably across all scenarios
            var computedStyle = window.getComputedStyle(element);

            // Fill with background color first
            if (computedStyle.backgroundColor && computedStyle.backgroundColor !== 'transparent') {
                ctx.fillStyle = computedStyle.backgroundColor;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }

            // Sample center pixel
            var imageData = ctx.getImageData(canvas.width / 2, canvas.height / 2, 1, 1);
            var data = imageData.data;

            if (data[3] > 0) { // If alpha > 0
                return [data[0], data[1], data[2]];
            }
        } catch (e) {
            // Canvas operations might fail due to CORS or other issues
            console.debug('Canvas sampling failed:', e);
        }

        return null;
    }

    /**
     * Get effective background color for an element (enhanced version)
     * @param {HTMLElement} element - Element to analyze
     * @returns {Array|null} RGB array or null
     */
    function getEffectiveBackgroundColor(element) {
        // First try advanced canvas-based sampling like Lighthouse does
        var sampledColor = sampleElementBackgroundAdvanced(element);
        if (sampledColor) {
            return sampledColor;
        }

        // Fallback to DOM traversal method
        var currentElement = element;
        var foundColors = [];

        while (currentElement && currentElement !== document.documentElement) {
            var styles = window.getComputedStyle(currentElement);

            // 1. Check for standard background color
            var bgColor = styles.backgroundColor;
            if (bgColor && bgColor !== 'transparent' && bgColor !== 'rgba(0, 0, 0, 0)') {
                var parsed = parseColor(bgColor);
                if (parsed) {
                    // Check opacity/alpha
                    var alpha = 1;
                    if (bgColor.includes('rgba')) {
                        var alphaMatch = bgColor.match(/rgba\([^)]+,\s*([^)]+)\)/);
                        if (alphaMatch) {
                            alpha = parseFloat(alphaMatch[1]);
                        }
                    }

                    if (alpha >= 0.9) {
                        // Nearly opaque background - this is our answer
                        var bgImage = styles.backgroundImage;
                        if (bgImage && bgImage !== 'none') {
                            var imageColor = analyzeBackgroundImage(currentElement);
                            if (imageColor) {
                                // Blend background color with estimated image color
                                return [
                                    Math.round((parsed[0] + imageColor[0]) / 2),
                                    Math.round((parsed[1] + imageColor[1]) / 2),
                                    Math.round((parsed[2] + imageColor[2]) / 2)
                                ];
                            }
                        }
                        return parsed;
                    } else if (alpha > 0.1) {
                        // Semi-transparent - collect for blending
                        foundColors.push({color: parsed, alpha: alpha});
                    }
                }
            }

            // 2. Check for background images/gradients without background color
            var bgImage = styles.backgroundImage;
            if (bgImage && bgImage !== 'none') {
                var imageColor = analyzeBackgroundImage(currentElement);
                if (imageColor) {
                    foundColors.push({color: imageColor, alpha: 1.0});
                }
            }

            // 3. Check for pseudo-element backgrounds
            var pseudoColor = checkPseudoElementBackground(currentElement);
            if (pseudoColor) {
                foundColors.push({color: pseudoColor, alpha: 1.0});
            }

            currentElement = currentElement.parentElement;
        }

        // If we found semi-transparent colors, blend them
        if (foundColors.length > 0) {
            var blended = blendColors(foundColors);
            if (blended) return blended;
        }

        // Check document backgrounds
        var docBackgrounds = getDocumentBackgrounds();
        if (docBackgrounds) return docBackgrounds;

        // Default to white background if nothing found
        return [255, 255, 255];
    }

    /**
     * Advanced background sampling using canvas - similar to Lighthouse approach
     */
    function sampleElementBackgroundAdvanced(element) {
        try {
            var rect = element.getBoundingClientRect();
            if (rect.width <= 0 || rect.height <= 0) return null;

            // Create a temporary canvas
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            // Set canvas size to element size (capped for performance)
            var maxSize = 100;
            var width = Math.min(rect.width, maxSize);
            var height = Math.min(rect.height, maxSize);

            canvas.width = width;
            canvas.height = height;

            // Use html2canvas-like approach if available, otherwise try direct sampling
            if (typeof html2canvas !== 'undefined') {
                // If html2canvas is available, use it for accurate rendering
                return null; // Skip for now - html2canvas is async and complex
            }

            // Try alternative approach: sample at element center and corners
            var elementRect = element.getBoundingClientRect();
            var samplePoints = [
                // Center
                {x: elementRect.left + elementRect.width / 2, y: elementRect.top + elementRect.height / 2},
                // Corners (slightly inset)
                {x: elementRect.left + 5, y: elementRect.top + 5},
                {x: elementRect.right - 5, y: elementRect.top + 5},
                {x: elementRect.left + 5, y: elementRect.bottom - 5},
                {x: elementRect.right - 5, y: elementRect.bottom - 5}
            ];

            // Use DOM-based background analysis instead of canvas sampling
            return null; // Let fallback method handle it

        } catch (e) {
            return null;
        }
    }

    /**
     * Blend multiple semi-transparent colors
     */
    function blendColors(colorArray) {
        if (colorArray.length === 0) return null;
        if (colorArray.length === 1) return colorArray[0].color;

        // Start with white background
        var result = [255, 255, 255];

        // Blend colors from back to front
        for (var i = colorArray.length - 1; i >= 0; i--) {
            var layer = colorArray[i];
            var alpha = layer.alpha;

            result[0] = Math.round(layer.color[0] * alpha + result[0] * (1 - alpha));
            result[1] = Math.round(layer.color[1] * alpha + result[1] * (1 - alpha));
            result[2] = Math.round(layer.color[2] * alpha + result[2] * (1 - alpha));
        }

        return result;
    }

    /**
     * Get document-level backgrounds
     */
    function getDocumentBackgrounds() {
        // Check body background
        var bodyStyles = window.getComputedStyle(document.body);
        var bodyBgColor = bodyStyles.backgroundColor;
        if (bodyBgColor && bodyBgColor !== 'transparent' && bodyBgColor !== 'rgba(0, 0, 0, 0)') {
            var parsed = parseColor(bodyBgColor);
            if (parsed) return parsed;
        }

        var bodyBgImage = bodyStyles.backgroundImage;
        if (bodyBgImage && bodyBgImage !== 'none') {
            var imageColor = analyzeBackgroundImage(document.body);
            if (imageColor) return imageColor;
        }

        // Check html background
        var htmlStyles = window.getComputedStyle(document.documentElement);
        var htmlBgColor = htmlStyles.backgroundColor;
        if (htmlBgColor && htmlBgColor !== 'transparent' && htmlBgColor !== 'rgba(0, 0, 0, 0)') {
            var parsed = parseColor(htmlBgColor);
            if (parsed) return parsed;
        }

        return null;
    }

    /**
     * Convert RGB values to HSL
     * @param {number} r - Red component (0-255)
     * @param {number} g - Green component (0-255)
     * @param {number} b - Blue component (0-255)
     * @returns {Array} HSL values [h (0-360), s (0-1), l (0-1)]
     */
    function rgbToHsl(r, g, b) {
        r /= 255;
        g /= 255;
        b /= 255;

        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0; // achromatic
        } else {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }

        return [h * 360, s, l];
    }

    /**
     * Convert HSL values to RGB
     * @param {number} h - Hue (0-360)
     * @param {number} s - Saturation (0-1)
     * @param {number} l - Lightness (0-1)
     * @returns {Array} RGB values [r (0-255), g (0-255), b (0-255)]
     */
    function hslToRgb(h, s, l) {
        h /= 360;

        function hue2rgb(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }

        var r, g, b;

        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            var p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
    }

    /**
     * Determine if text is considered large according to WCAG
     * @param {HTMLElement} element - Text element to check
     * @returns {boolean} True if text is large
     */
    function isLargeText(element) {
        var styles = window.getComputedStyle(element);
        var fontSize = parseFloat(styles.fontSize);
        var fontWeight = styles.fontWeight;

        // Convert fontSize to points (assuming 96 DPI)
        var fontSizePt = fontSize * 0.75;

        // Large text: 18pt or larger, or 14pt bold or larger
        if (fontSizePt >= 18) {
            return true;
        }

        if (fontSizePt >= 14 && (fontWeight === 'bold' || fontWeight === '700' || parseInt(fontWeight) >= 700)) {
            return true;
        }

        return false;
    }

    /**
     * Generate improved color with better contrast
     * @param {Array} originalRgb - Original RGB color
     * @param {Array} backgroundRgb - Background RGB color
     * @param {number} targetRatio - Target contrast ratio
     * @returns {string} Improved color in hex format
     */
    function generateImprovedColor(originalRgb, backgroundRgb, targetRatio) {
        var bgLuminance = calculateRelativeLuminance(backgroundRgb[0], backgroundRgb[1], backgroundRgb[2]);
        var originalLuminance = calculateRelativeLuminance(originalRgb[0], originalRgb[1], originalRgb[2]);
        var originalRatio = calculateContrastRatio(originalLuminance, bgLuminance);

        // If original ratio is already good enough, don't modify
        if (originalRatio >= targetRatio) {
            return '#' + originalRgb.map(function(c) {
                var hex = c.toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('');
        }

        // Determine if we need to make text darker or lighter
        var shouldDarken = bgLuminance > 0.5;

        var bestColor = originalRgb.slice();
        var bestRatio = originalRatio;

        // Enhanced algorithm with more nuanced color preservation
        // Try to preserve color hue and saturation while adjusting brightness

        // Convert RGB to HSL for better color manipulation
        var hsl = rgbToHsl(originalRgb[0], originalRgb[1], originalRgb[2]);
        var originalHue = hsl[0];
        var originalSaturation = hsl[1];
        var originalLightness = hsl[2];

        // Try adjusting lightness while preserving hue and saturation
        // For WCAG AAA, use more aggressive steps to reach higher contrast ratios
        var lightnessSteps = shouldDarken ?
            (targetRatio >= 7.0 ?
                [0.7, 0.6, 0.5, 0.4, 0.35, 0.3, 0.25, 0.2, 0.15, 0.1, 0.08, 0.05, 0.03] :
                [0.8, 0.7, 0.6, 0.5, 0.4, 0.35, 0.3, 0.25, 0.2, 0.15, 0.1, 0.05]) :
            (targetRatio >= 7.0 ?
                [1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2.0, 2.2, 2.5, 3.0, 4.0, 5.0] :
                [1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2.0, 2.5, 3.0, 4.0]);

        for (var i = 0; i < lightnessSteps.length; i++) {
            var newLightness = Math.min(1, Math.max(0, originalLightness * lightnessSteps[i]));
            var newRgb = hslToRgb(originalHue, originalSaturation, newLightness);

            var testLuminance = calculateRelativeLuminance(newRgb[0], newRgb[1], newRgb[2]);
            var testRatio = calculateContrastRatio(testLuminance, bgLuminance);

            if (testRatio >= targetRatio) {
                return '#' + newRgb.map(function(c) {
                    var hex = Math.round(c).toString(16);
                    return hex.length === 1 ? '0' + hex : hex;
                }).join('');
            }

            if (testRatio > bestRatio) {
                bestColor = newRgb;
                bestRatio = testRatio;
            }
        }

        // If HSL adjustment didn't work well enough, try gradual desaturation with darker/lighter tones
        // For WCAG AAA, we need stricter thresholds before trying desaturation
        var desaturationThreshold = targetRatio >= 7.0 ? 0.98 : 0.9; // Stricter for AAA
        if (bestRatio < targetRatio * desaturationThreshold) {
            var saturationSteps = [0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1];

            for (var s = 0; s < saturationSteps.length; s++) {
                var newSaturation = originalSaturation * saturationSteps[s];

                for (var l = 0; l < lightnessSteps.length; l++) {
                    var newLightness = Math.min(1, Math.max(0, originalLightness * lightnessSteps[l]));
                    var newRgb = hslToRgb(originalHue, newSaturation, newLightness);

                    var testLuminance = calculateRelativeLuminance(newRgb[0], newRgb[1], newRgb[2]);
                    var testRatio = calculateContrastRatio(testLuminance, bgLuminance);

                    if (testRatio >= targetRatio) {
                        return '#' + newRgb.map(function(c) {
                            var hex = Math.round(c).toString(16);
                            return hex.length === 1 ? '0' + hex : hex;
                        }).join('');
                    }

                    if (testRatio > bestRatio) {
                        bestColor = newRgb;
                        bestRatio = testRatio;
                    }
                }
            }
        }

        // If we achieved a good improvement, use it
        // For WCAG AAA, we need to be stricter with tolerances
        var toleranceFactor = targetRatio >= 7.0 ? 0.95 : 0.85; // Stricter for AAA (7:1)
        if (bestRatio >= targetRatio * toleranceFactor && bestRatio > originalRatio * 1.3) {
            return '#' + bestColor.map(function(c) {
                var hex = Math.round(c).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('');
        }

        // As a last resort, use high contrast but try to preserve some color characteristics
        // For WCAG AAA, be more aggressive about falling back to high contrast
        var highContrastThreshold = targetRatio >= 7.0 ? 0.8 : 0.5; // More aggressive for AAA
        if (originalRatio < targetRatio * highContrastThreshold) {
            if (shouldDarken) {
                // Try a very dark version of the original hue first
                var darkRgb = hslToRgb(originalHue, Math.min(0.8, originalSaturation * 1.2), 0.1);
                var darkLuminance = calculateRelativeLuminance(darkRgb[0], darkRgb[1], darkRgb[2]);
                var darkRatio = calculateContrastRatio(darkLuminance, bgLuminance);

                if (darkRatio >= targetRatio) {
                    return '#' + darkRgb.map(function(c) {
                        var hex = Math.round(c).toString(16);
                        return hex.length === 1 ? '0' + hex : hex;
                    }).join('');
                }
                return '#000000';
            } else {
                // Try a very light version of the original hue first
                var lightRgb = hslToRgb(originalHue, Math.min(0.3, originalSaturation * 0.8), 0.95);
                var lightLuminance = calculateRelativeLuminance(lightRgb[0], lightRgb[1], lightRgb[2]);
                var lightRatio = calculateContrastRatio(lightLuminance, bgLuminance);

                if (lightRatio >= targetRatio) {
                    return '#' + lightRgb.map(function(c) {
                        var hex = Math.round(c).toString(16);
                        return hex.length === 1 ? '0' + hex : hex;
                    }).join('');
                }
                return '#ffffff';
            }
        }

        // Return best improvement we found
        return '#' + bestColor.map(function(c) {
            var hex = Math.round(c).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        }).join('');
    }

    /**
     * Add enhanced hover effects to links that have permanent underlines
     * @param {HTMLElement} linkElement - The link element
     * @param {string} improvedColor - The improved text color
     * @param {Array} backgroundRgb - Background RGB values
     */
    function addLinkHoverEffects(linkElement, improvedColor, backgroundRgb) {
        // Skip if hover effects already added
        if (linkElement.getAttribute('data-faw-hover-effects-added')) {
            return;
        }

        // Skip if element matches excludeFromHoverEffects selectors
        var excludeSelectors = FIETZ_ACCESSIBILITY_CONFIG.excludeFromHoverEffects || [];
        for (var ei = 0; ei < excludeSelectors.length; ei++) {
            try {
                if (linkElement.matches(excludeSelectors[ei]) || linkElement.closest(excludeSelectors[ei].replace(/ a$/, ''))) {
                    linkElement.setAttribute('data-faw-hover-effects-added', 'excluded');
                    return;
                }
            } catch(e) { /* invalid selector, skip */ }
        }

        // Calculate appropriate hover colors
        var originalRgb = parseColor(improvedColor);
        var bgLuminance = calculateRelativeLuminance(backgroundRgb[0], backgroundRgb[1], backgroundRgb[2]);
        var isLightBackground = bgLuminance > 0.5;

        // Generate hover color variations
        var hoverColor, hoverBackgroundColor;

        if (isLightBackground) {
            // On light backgrounds: darker text, light background highlight
            var darkerRgb = originalRgb.map(function(c) { return Math.max(0, c - 40); });
            hoverColor = 'rgb(' + darkerRgb.join(',') + ')';
            hoverBackgroundColor = 'rgba(0, 0, 0, 0.05)';
        } else {
            // On dark backgrounds: lighter text, dark background highlight
            var lighterRgb = originalRgb.map(function(c) { return Math.min(255, c + 40); });
            hoverColor = 'rgb(' + lighterRgb.join(',') + ')';
            hoverBackgroundColor = 'rgba(255, 255, 255, 0.1)';
        }

        // Store original styles
        var originalTextDecoration = linkElement.style.textDecoration;
        var originalBackgroundColor = linkElement.style.backgroundColor;
        var originalTransform = linkElement.style.transform;

        // Add hover event listeners
        linkElement.addEventListener('mouseenter', function() {
            this.style.setProperty('color', hoverColor, 'important');
            // MRH: underline deaktiviert // this.style.setProperty("text-decoration", "underline", "important");
            this.style.setProperty('text-decoration-thickness', '2px', 'important');
            this.style.setProperty('text-underline-offset', '2px', 'important');
            this.style.setProperty('transform', 'translateY(-1px)', 'important');

        });

        linkElement.addEventListener('mouseleave', function() {
            this.style.setProperty('color', improvedColor, 'important');
            // MRH: underline deaktiviert // this.style.setProperty("text-decoration", originalTextDecoration, "important");
            this.style.removeProperty('text-decoration-thickness');
            this.style.removeProperty('text-underline-offset');
            this.style.removeProperty('transform');

        });

        // Add focus effects for keyboard navigation
        linkElement.addEventListener('focus', function() {
            this.style.setProperty('outline', '2px solid #0066cc', 'important');
            this.style.setProperty('outline-offset', '2px', 'important');
            this.style.setProperty('background-color', hoverBackgroundColor, 'important');
            this.style.setProperty('border-radius', '2px', 'important');
            this.style.setProperty('padding', '1px 2px', 'important');
        });

        linkElement.addEventListener('blur', function() {
            this.style.removeProperty('outline');
            this.style.removeProperty('outline-offset');
            this.style.removeProperty('background-color');
            this.style.removeProperty('border-radius');
            this.style.removeProperty('padding');
        });

        linkElement.setAttribute('data-faw-hover-effects-added', 'true');

                        if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('🔗 **FAW:** Added enhanced hover effects to link:', {
                element: linkElement.tagName,
                href: linkElement.href || 'keine URL',
                hoverColor: hoverColor,
                hoverBackground: hoverBackgroundColor,
                originalColor: improvedColor
            });
        }
    }

    /**
     * Process elements for contrast issues and apply fixes
     * @returns {number} Number of elements fixed
     */
    function processContrastForElement(element) {
        // Skip if already processed or not visible
        if (element.getAttribute('data-faw-contrast-processed') ||
            element.offsetWidth === 0 || element.offsetHeight === 0) {
            return false;
        }

        // Skip elements excluded from contrast checking (e.g. footer headings with intentional colors)
        if (FIETZ_ACCESSIBILITY_CONFIG.excludeFromContrastCheck &&
            FIETZ_ACCESSIBILITY_CONFIG.excludeFromContrastCheck.some(function(selector) {
                try { return element.matches(selector) || element.closest(selector); }
                catch(e) { return false; }
            })) {
            return false;
        }

        // Check if element is in the list of elements that should always be black
        if (FIETZ_ACCESSIBILITY_CONFIG.alwaysBlackElements &&
            FIETZ_ACCESSIBILITY_CONFIG.alwaysBlackElements.some(selector =>
                element.matches(selector) || element.closest(selector))) {
            element.style.setProperty('color', '#000000', 'important');
            element.setAttribute('data-faw-contrast-processed', 'true');
            element.setAttribute('data-faw-forced-black', 'true');
            return true;
        }

        // Skip if no text content
        var textContent = element.textContent || element.value || '';
        if (!textContent.trim()) {
            return false;
        }

        var styles = window.getComputedStyle(element);
        var textColor = styles.color;
        var textRgb = parseColor(textColor);

        if (!textRgb) {
            return false;
        }

        // Preserve white text color
        if (textRgb[0] >= 250 && textRgb[1] >= 250 && textRgb[2] >= 250) {
            return false;
        }

        // Preserve black text color
        if (textRgb[0] <= 5 && textRgb[1] <= 5 && textRgb[2] <= 5) {
            return false;
        }

        var backgroundRgb = getEffectiveBackgroundColor(element);
        if (!backgroundRgb) {
            return false;
        }

        var textLuminance = calculateRelativeLuminance(textRgb[0], textRgb[1], textRgb[2]);
        var bgLuminance = calculateRelativeLuminance(backgroundRgb[0], backgroundRgb[1], backgroundRgb[2]);
        var contrastRatio = calculateContrastRatio(textLuminance, bgLuminance);

        // Enhanced WCAG standards support
        var useWCAG_AAA = FIETZ_ACCESSIBILITY_CONFIG.wcagLevel === 'AAA' ||
                         document.body.getAttribute('data-faw-wcag-level') === 'AAA' ||
                         document.documentElement.getAttribute('data-faw-wcag-level') === 'AAA' ||
                         getSetting('wcagLevel') === 'AAA';

        var minRequiredRatio, interventionThreshold;
        if (useWCAG_AAA) {
            minRequiredRatio = isLargeText(element) ? 4.5 : 7.0;
            // For AAA, be stricter about intervention threshold to ensure we reach target
            interventionThreshold = isLargeText(element) ? 4.3 : 6.8;
        } else {
            minRequiredRatio = isLargeText(element) ? 3.0 : 4.5;
            interventionThreshold = isLargeText(element) ? 2.8 : 4.2;
        }

        // Check if contrast is insufficient
        if (contrastRatio < interventionThreshold) {
            var improvedColor = generateImprovedColor(textRgb, backgroundRgb, minRequiredRatio);

            var improvedRgb = parseColor(improvedColor);
            var improvedLuminance = calculateRelativeLuminance(improvedRgb[0], improvedRgb[1], improvedRgb[2]);
            var improvedRatio = calculateContrastRatio(improvedLuminance, bgLuminance);

            if (improvedRatio < minRequiredRatio && contrastRatio < 2.0) {
                var isLightBackground = bgLuminance > 0.5;

                if (FIETZ_ACCESSIBILITY_CONFIG.useTextShadowFallback && bgLuminance > 0.25 && bgLuminance < 0.75) {
                    var shadowColor = isLightBackground ? 'rgba(0,0,0,0.7)' : 'rgba(255,255,255,0.7)';
                    element.style.setProperty('text-shadow', '1px 1px 2px ' + shadowColor, 'important');
                    element.setAttribute('data-faw-text-shadow', 'applied');
                    improvedColor = textColor;
                } else {
                    improvedColor = isLightBackground ? '#000000' : '#ffffff';
                }
            } else if (improvedRatio < minRequiredRatio && contrastRatio >= 2.0) {
                // For WCAG AAA, don't give up too early - try harder to reach the target
                if (useWCAG_AAA && improvedRatio < minRequiredRatio * 0.9) {
                    // Force high contrast for AAA when we can't reach acceptable levels
                    var isLightBackground = bgLuminance > 0.5;
                    improvedColor = isLightBackground ? '#000000' : '#ffffff';
                } else {
                    return false;
                }
            }

            element.style.setProperty('color', improvedColor, 'important');

            if (element.tagName.toLowerCase() === 'a' || element.closest('a')) {
                var linkElement = element.tagName.toLowerCase() === 'a' ? element : element.closest('a');
                linkElement.style.setProperty('text-decoration', 'underline', 'important');
                linkElement.setAttribute('data-faw-underline-added', 'true');
                addLinkHoverEffects(linkElement, improvedColor, backgroundRgb);
            }

            element.setAttribute('data-faw-contrast-processed', 'true');
            element.setAttribute('data-faw-original-color', textColor);
            element.setAttribute('data-faw-contrast-ratio', contrastRatio.toFixed(2));
            element.setAttribute('data-faw-background-estimate', 'rgb(' + backgroundRgb.join(',') + ')');

            var finalRgb = parseColor(improvedColor);
            var finalLuminance = calculateRelativeLuminance(finalRgb[0], finalRgb[1], finalRgb[2]);
            var finalRatio = calculateContrastRatio(finalLuminance, bgLuminance);
            element.setAttribute('data-faw-improved-ratio', finalRatio.toFixed(2));

            // Enhanced debugging for AAA issues
            if (useWCAG_AAA && finalRatio < minRequiredRatio && FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.warn('🔍 **FAW AAA Debug:** Element failed to reach AAA target', {
                    element: element,
                    originalRatio: contrastRatio.toFixed(2),
                    finalRatio: finalRatio.toFixed(2),
                    targetRatio: minRequiredRatio,
                    originalColor: textColor,
                    improvedColor: improvedColor,
                    background: 'rgb(' + backgroundRgb.join(',') + ')',
                    isLargeText: isLargeText(element),
                    fontSize: window.getComputedStyle(element).fontSize
                });
            }

            return true;
        }

        return false;
    }

    function processElementsForContrastIssues() {
        return FIETZ_PERFORMANCE.measure('Contrast Issues Processing', function() {
            var fixedCount = 0;

        // Target text elements that might have contrast issues
        var textElements = document.querySelectorAll('p, a, span, div, h1, h2, h3, h4, h5, h6, li, td, th, label, button, input[type="text"], input[type="email"], textarea');

        // Performance optimization: Chunked processing for large element arrays
        if (FIETZ_ACCESSIBILITY_CONFIG.optimizations.batchDOMProcessing && textElements.length > FIETZ_ACCESSIBILITY_CONFIG.optimizations.maxElementsPerBatch) {
            var elementsArray = Array.from(textElements);

            FIETZ_OPTIMIZER.processArrayInChunks(elementsArray, function(element) {
                if (processContrastForElement(element)) {
                    fixedCount++;
                }
            }, FIETZ_ACCESSIBILITY_CONFIG.optimizations.maxElementsPerBatch, function() {
                if (fixedCount > 0 && FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('🎨 **FAW:** Auto-fixed contrast issues for', fixedCount, 'elements (chunked processing)');
                }
            });

            return fixedCount;
        }

        // Standard processing for smaller element arrays
        textElements.forEach(function(element) {
            if (processContrastForElement(element)) {
                fixedCount++;
            }
        });

        if (fixedCount > 0 && FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
            console.log('🎨 **FAW:** Auto-fixed contrast issues for', fixedCount, 'elements');
        }

        return fixedCount;
        });
    }



    /**
     * Check and enhance form control contrast according to WCAG 1.4.11
     * @returns {number} Number of form controls enhanced
     */
    function processFormControlsForContrast() {
        return FIETZ_PERFORMANCE.measure('Form Controls Contrast Processing', function() {
            var enhancedCount = 0;

        // Select form controls that need contrast checking
        // Exclude buttons and .btn elements from automatic outline processing
        var formControls = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="reset"]):not([type="button"]), select, textarea');

        formControls.forEach(function(control) {
            // Skip if already processed or not visible
            if (control.getAttribute('data-faw-form-contrast-processed') ||
                control.offsetWidth === 0 || control.offsetHeight === 0) {
                return;
            }

            // Skip FAW elements
            if (control.closest('.faw-menu, .faw-widget') ||
                control.classList.contains('faw-btn') ||
                control.id && control.id.startsWith('faw-')) {
                return;
            }

            // Skip buttons and .btn elements explicitly (additional safety check)
            if (control.tagName.toLowerCase() === 'button' ||
                control.classList.contains('btn') ||
                control.matches('[type="button"], [type="submit"], [type="reset"]')) {
                return;
            }

            var styles = window.getComputedStyle(control);
            var backgroundColor = styles.backgroundColor;
            var borderColor = styles.borderColor;

            // Parse background and border colors
            var bgRgb = parseColor(backgroundColor);
            var borderRgb = parseColor(borderColor);

            if (!bgRgb) {
                // Try to get effective background from parent
                bgRgb = getEffectiveBackgroundColor(control);
            }

            if (!bgRgb) {
                return; // Can't determine background
            }

            // Calculate luminance values
            var bgLuminance = calculateRelativeLuminance(bgRgb[0], bgRgb[1], bgRgb[2]);

            // Get document background for comparison
            var docBg = getDocumentBackgrounds();
            var docBgRgb = docBg.backgroundColor ? parseColor(docBg.backgroundColor) : [255, 255, 255];
            var docBgLuminance = calculateRelativeLuminance(docBgRgb[0], docBgRgb[1], docBgRgb[2]);

            // Calculate contrast between form control and surrounding background
            var contrastRatio = calculateContrastRatio(bgLuminance, docBgLuminance);

            // Enhanced WCAG standards support for form controls
            var useWCAG_AAA = FIETZ_ACCESSIBILITY_CONFIG.wcagLevel === 'AAA' ||
                             document.body.getAttribute('data-faw-wcag-level') === 'AAA' ||
                             document.documentElement.getAttribute('data-faw-wcag-level') === 'AAA' ||
                             getSetting('wcagLevel') === 'AAA';

            // WCAG 1.4.11 requires 3:1 contrast for form controls
            // For AAA configuration, we use slightly higher threshold
            var minRequiredRatio = useWCAG_AAA ? 3.5 : 3.0;
            var interventionThreshold = useWCAG_AAA ? 3.2 : 2.8;

            // Check if border has sufficient contrast if background contrast is poor
            var needsBorderEnhancement = false;
            var borderContrastRatio = 1.0;

            if (borderRgb && borderRgb.join(',') !== bgRgb.join(',')) {
                var borderLuminance = calculateRelativeLuminance(borderRgb[0], borderRgb[1], borderRgb[2]);
                borderContrastRatio = calculateContrastRatio(borderLuminance, docBgLuminance);
            }

            // Determine if enhancement is needed
            if (contrastRatio < interventionThreshold ||
                (contrastRatio < minRequiredRatio && borderContrastRatio < minRequiredRatio)) {
                needsBorderEnhancement = true;
            }

            if (needsBorderEnhancement) {
                // Determine appropriate border/outline color based on document background
                var isLightDocBackground = docBgLuminance > 0.5;
                var enhancementColor, enhancementType;

                // If control background is similar to document background, add contrasting border
                if (Math.abs(bgLuminance - docBgLuminance) < 0.3) {
                    enhancementColor = isLightDocBackground ? '#666666' : '#cccccc';
                    enhancementType = 'border';
                } else {
                    // If backgrounds differ but contrast is still poor, enhance the existing border
                    if (contrastRatio < 2.0) {
                        enhancementColor = isLightDocBackground ? '#333333' : '#ffffff';
                        enhancementType = 'outline';
                    } else {
                        enhancementColor = isLightDocBackground ? '#888888' : '#aaaaaa';
                        enhancementType = 'border';
                    }
                }

                // Apply enhancement
                if (enhancementType === 'outline') {
                    control.style.setProperty('outline', '2px solid ' + enhancementColor, 'important');
                    control.style.setProperty('outline-offset', '-1px', 'important');
                } else {
                    control.style.setProperty('border', '1px solid ' + enhancementColor, 'important');
                }

                // Enhanced focus styles for better usability
                var focusColor = isLightDocBackground ? '#0066cc' : '#4da6ff';
                control.addEventListener('focus', function() {
                    this.style.setProperty('outline', '2px solid ' + focusColor, 'important');
                    this.style.setProperty('outline-offset', '1px', 'important');
                });

                control.addEventListener('blur', function() {
                    if (enhancementType === 'outline') {
                        this.style.setProperty('outline', '2px solid ' + enhancementColor, 'important');
                        this.style.setProperty('outline-offset', '-1px', 'important');
                    } else {
                        this.style.removeProperty('outline');
                        this.style.removeProperty('outline-offset');
                    }
                });

                // Mark as processed and store debug info
                control.setAttribute('data-faw-form-contrast-processed', 'true');
                control.setAttribute('data-faw-form-contrast-ratio', contrastRatio.toFixed(2));
                control.setAttribute('data-faw-form-enhancement-type', enhancementType);
                control.setAttribute('data-faw-form-enhancement-color', enhancementColor);

                enhancedCount++;

                if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('🔧 **FAW:** Enhanced form control contrast:', {
                        element: control.tagName + (control.type ? '[' + control.type + ']' : ''),
                        classes: control.className,
                        originalContrast: contrastRatio.toFixed(2),
                        borderContrast: borderContrastRatio.toFixed(2),
                        enhancementType: enhancementType,
                        enhancementColor: enhancementColor,
                        backgroundEstimate: 'rgb(' + bgRgb.join(',') + ')',
                        documentBackground: 'rgb(' + docBgRgb.join(',') + ')',
                        minRequiredRatio: minRequiredRatio
                    });
                }
            } else {
                // Mark as processed even if no enhancement was needed
                control.setAttribute('data-faw-form-contrast-processed', 'true');
            }
        });

        if (enhancedCount > 0 && FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
            console.log('🔧 **FAW:** Enhanced contrast for', enhancedCount, 'form controls (WCAG 1.4.11)');
        }

        return enhancedCount;
        });
    }

    /**
     * Initialize contrast enhancement system
     * Sets up automatic processing and monitoring for contrast issues
     */
    function initContrastEnhancement() {
        // Check if we should automatically enable WCAG AAA mode based on accessibility settings
        if (FIETZ_ACCESSIBILITY_CONFIG.autoUpgradeToAAA) {
            var currentSettings = loadSettings(true);
            if (currentSettings && (currentSettings.fontSize > 1.0 || currentSettings.fontSizeIncrease)) {
                // If font size has been increased, automatically use stricter WCAG AAA standards
                updateSettings({wcagLevel: 'AAA'});
                if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('⚡ **FAW:** Auto-enabled WCAG AAA standards due to increased font size');
                }
            }
        }

        // Set tooltip visibility based on configuration
        if (FIETZ_ACCESSIBILITY_CONFIG.showContrastTooltips) {
            document.body.setAttribute('data-faw-show-tooltips', 'true');
        }

        // Process existing elements immediately
        processElementsForContrastIssues();

        // Process form controls for WCAG 1.4.11 compliance
        processFormControlsForContrast();

        // Initialize DOM mutation observer for dynamic content monitoring
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function(mutations) {
                var shouldProcess = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1) { // Ensure it's an element node
                                // Check if new node contains text elements
                                if (node.textContent && node.textContent.trim().length > 0) {
                                    shouldProcess = true;
                                    break;
                                }
                            }
                        }
                    }
                });

                if (shouldProcess) {
                    // Add processing delay to accommodate dynamic styling
                    setTimeout(function() {
                        processElementsForContrastIssues();
                        processFormControlsForContrast();
                    }, 200);
                }
            });

            // Start observing document changes
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            //console.log('Contrast enhancement monitoring initialized');
        }

        // Re-process on scroll for lazy-loaded content
        var scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                processElementsForContrastIssues();
                processFormControlsForContrast();
            }, 300);
        });

        // Re-process on window resize (responsive design changes)
        var resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                processElementsForContrastIssues();
                processFormControlsForContrast();
            }, 500);
        });

        //console.log('Contrast enhancement system initialized');
    }

    /**
     * Initialize accessible names generation system
     * Sets up automatic processing and monitoring for elements without accessible names
     */
    function initAccessibleNames() {
        // Process existing elements immediately
        processElementsForAccessibleNames();

        // Initialize DOM mutation observer for dynamic content monitoring
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function(mutations) {
                var shouldProcess = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1) { // Ensure it's an element node
                                // Check if new node is a button/link or contains buttons/links
                                if (node.matches && (node.matches('button, a[href], input[type="button"], input[type="submit"], [role="button"]') ||
                                    node.querySelector('button, a[href], input[type="button"], input[type="submit"], [role="button"]'))) {
                                    shouldProcess = true;
                                    break;
                                }
                            }
                        }
                    }
                });

                if (shouldProcess) {
                    // Add processing delay to accommodate dynamic loading
                    setTimeout(processElementsForAccessibleNames, 100);
                }
            });

            // Start observing document changes
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            //console.log('Accessible names monitoring initialized');
        }

        // Re-process on scroll for lazy-loaded content
        var scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                processElementsForAccessibleNames();
            }, 250);
        });

        //console.log('Accessible names generation system initialized');
    }

    /**
     * Landmark Recognition and Enhancement System
     * Automatically adds appropriate ARIA landmarks to elements based on their semantic meaning
     * Analyzes class names, structure, and content to determine the most suitable landmark role
     */

    /**
     * Landmark configuration mapping class patterns to ARIA roles
     * Organized by priority - more specific patterns first
     */
    var LANDMARK_PATTERNS = {
                 // Header landmarks (role="banner")
         banner: {
             role: 'banner',
             selectors: [
                 // NOTE: Removed 'header:not([role])' as <header> elements have implicit banner role
                 '.header:not([role])',
                 '.page-header:not([role])',
                 '.site-header:not([role])',
                 '.top-header:not([role])',
                 '.main-header:not([role])',
                 '.header-wrapper:not([role])',
                 '.header-container:not([role])',
                 '#header:not([role])',
                 '#site-header:not([role])',
                 '#page-header:not([role])',
                 '#main-header:not([role])',
                 '#top-header:not([role])',
                 '#sp-page-title:not([role])',
                 'section[id*="header"]:not([role])',
                 'section[class*="header"]:not([role])',
                 'section[id*="title"]:not([role])'
             ],
             // Additional class name patterns to match
             classPatterns: [
                 /^header/i,
                 /header$/i,
                 /^site-?header/i,
                 /^page-?header/i,
                 /^main-?header/i,
                 /^top-?header/i,
                 /masthead/i,
                 /banner/i,
                 /page.*title/i,
                 /title.*page/i,
                 /sp.*title/i,
                 /sp.*header/i,
                 /top.*section/i
             ]
         },

        // Navigation landmarks (role="navigation")
        navigation: {
            role: 'navigation',
            selectors: [
                // NOTE: Removed 'nav:not([role])' as <nav> elements have implicit navigation role
                '.nav:not([role])',
                '.navigation:not([role])',
                '.navbar:not([role])',
                '.main-nav:not([role])',
                '.primary-nav:not([role])',
                '.site-nav:not([role])',
                '.menu:not([role])',
                '.main-menu:not([role])',
                '.primary-menu:not([role])',
                '#nav:not([role])',
                '#navigation:not([role])',
                '#navbar:not([role])',
                '#main-nav:not([role])',
                '#primary-nav:not([role])',
                '#menu:not([role])',
                '#main-menu:not([role])',
                '#primary-menu:not([role])'
            ],
                         classPatterns: [
                 /^nav/i,
                 /navigation/i,
                 /navbar/i,
                 /menu/i,
                 /^main-?nav/i,
                 /^primary-?nav/i,
                 /^site-?nav/i,
                 /breadcrumb/i,
                 /megamenu/i,
                 /topnav/i,
                 /topmenu/i
             ]
        },

                 // Main content landmarks (role="main")
         main: {
             role: 'main',
             selectors: [
                 // NOTE: Removed 'main:not([role])' as <main> elements have implicit main role
                 '.main:not([role])',
                 '.main-content:not([role])',
                 '.content:not([role])',
                 '.page-content:not([role])',
                 '.site-content:not([role])',
                 '.primary-content:not([role])',
                 '#main:not([role])',
                 '#main-content:not([role])',
                 '#content:not([role])',
                 '#page-content:not([role])',
                 '#site-content:not([role])',
                 '#primary-content:not([role])',
                 '#sp-main-body:not([role])',
                 // NOTE: Removed section selectors as <section> elements should not automatically get main role
             ],
             classPatterns: [
                 /^main$/i,
                 /^main-?content/i,
                 /^page-?content/i,
                 /^site-?content/i,
                 /^primary-?content/i,
                 /^content$/i,
                 /wrapper.*content/i,
                 /content.*wrapper/i,
                 /main.*body/i,
                 /body.*main/i,
                 /sp.*main/i,
                 /main.*area/i,
                 /central.*content/i,
                 /primary.*area/i
             ]
         },

        // Complementary content landmarks (role="complementary") - Sidebars, etc.
        complementary: {
            role: 'complementary',
            selectors: [
                // NOTE: Removed 'aside:not([role])' as <aside> elements have implicit complementary role
                '.aside:not([role])',
                '.sidebar:not([role])',
                '.secondary:not([role])',
                '.widget-area:not([role])',
                '.sidebar-content:not([role])',
                '#sidebar:not([role])',
                '#aside:not([role])',
                '#secondary:not([role])',
                '#widget-area:not([role])'
            ],
            classPatterns: [
                /^aside/i,
                /sidebar/i,
                /secondary/i,
                /widget.*area/i,
                /related.*content/i,
                /additional.*info/i
            ]
        },

                 // Footer landmarks (role="contentinfo")
         contentinfo: {
             role: 'contentinfo',
             selectors: [
                 // NOTE: Removed 'footer:not([role])' as <footer> elements have implicit contentinfo role
                 '.footer:not([role])',
                 '.page-footer:not([role])',
                 '.site-footer:not([role])',
                 '.main-footer:not([role])',
                 '.footer-wrapper:not([role])',
                 '.footer-container:not([role])',
                 '#footer:not([role])',
                 '#site-footer:not([role])',
                 '#page-footer:not([role])',
                 '#main-footer:not([role])',
                 '#sp-bottom:not([role])',
                 // NOTE: Removed section selectors as <section> elements should not automatically get contentinfo role
             ],
             classPatterns: [
                 /^footer/i,
                 /footer$/i,
                 /^site-?footer/i,
                 /^page-?footer/i,
                 /^main-?footer/i,
                 /contentinfo/i,
                 /bottom.*section/i,
                 /sp.*bottom/i,
                 /sp.*footer/i,
                 /page.*bottom/i,
                 /site.*bottom/i
             ]
         },

        // Search landmarks (role="search")
        search: {
            role: 'search',
            selectors: [
                '.search:not([role])',
                '.search-form:not([role])',
                '.search-box:not([role])',
                '.search-container:not([role])',
                '.searchform:not([role])',
                '#search:not([role])',
                '#search-form:not([role])',
                '#searchform:not([role])'
            ],
            classPatterns: [
                /^search/i,
                /search.*form/i,
                /search.*box/i,
                /search.*container/i
            ]
        },

                 // Article content (role="article")
         article: {
             role: 'article',
             selectors: [
                 // NOTE: Removed 'article:not([role])' as <article> elements have implicit article role
                 '.post:not([role])',
                 '.article:not([role])',
                 '.blog-post:not([role])',
                 '.entry:not([role])',
                 '.news-item:not([role])',
                 '#post:not([role])',
                 '#article:not([role])'
             ],
             classPatterns: [
                 /^article/i,
                 /^post$/i,
                 /blog.*post/i,
                 /post.*content/i,
                 /news.*item/i,
                 /^entry/i,
                 /item.*post/i,
                 /content.*item/i
             ]
         },

        // Region landmarks (role="region") - for other significant sections
        region: {
            role: 'region',
            selectors: [
                '.hero:not([role])',
                '.banner-section:not([role])',
                '.featured:not([role])',
                '.highlight:not([role])',
                '#hero:not([role])',
                '#featured:not([role])'
            ],
            classPatterns: [
                /^hero/i,
                /banner.*section/i,
                /featured.*section/i,
                /highlight.*section/i,
                /promo.*section/i,
                /intro.*section/i
            ]
        }
    };

    /**
     * Determine the most appropriate ARIA role for an element
     * @param {HTMLElement} element - Element to analyze
     * @returns {string|null} Recommended ARIA role or null
     */
         function determineLandmarkRole(element) {
         var tagName = element.tagName.toLowerCase();
         var className = element.className || '';
         var id = element.id || '';
         var combinedText = (className + ' ' + id).toLowerCase();

         // Skip if element already has a role
         if (element.hasAttribute('role')) {
             return null;
         }

                 // Skip semantic HTML5 elements that already have implicit landmark roles
        // These elements should NEVER get additional ARIA roles as per WCAG/ARIA spec
        var semanticElements = ['main', 'nav', 'header', 'footer', 'aside', 'article', 'section'];
        if (semanticElements.includes(tagName)) {
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.log('🏛️ **FAW:** Skipping semantic element:', {
                    element: `<${tagName}>`,
                    id: element.id || 'keine ID',
                    classes: element.className || 'keine Klassen',
                    reason: `<${tagName}> elements have implicit ARIA landmark roles and should not get explicit roles`
                });
            }
            return null;
        }

         // Skip if landmark enhancement is disabled
         if (!FIETZ_ACCESSIBILITY_CONFIG.landmarkEnhancement.enabled) {
             return null;
         }

         // Skip excluded elements
         var excludeSelectors = FIETZ_ACCESSIBILITY_CONFIG.landmarkEnhancement.excludeSelectors;
         for (var i = 0; i < excludeSelectors.length; i++) {
             try {
                 if (element.matches(excludeSelectors[i])) {
                     return null;
                 }
             } catch (e) {
                 // Fallback for simple class/id checks
                 if (className.includes('faw-') || id.includes('faw-')) {
                     return null;
                 }
             }
         }

         // Skip wrapper elements that contain the entire page structure
         // These should not get landmark roles as they are just layout containers
         if (isPageWrapper(combinedText, element)) {
             return null;
         }

               // NOTE: Removed semantic HTML5 element processing as these elements
       // already have implicit ARIA landmark roles and should not get explicit roles

        // Check for search forms
        if ((tagName === 'form' || tagName === 'div') &&
            (className.toLowerCase().includes('search') || id.toLowerCase().includes('search'))) {
            var hasSearchInput = element.querySelector('input[type="search"], input[name*="search"], input[placeholder*="search" i]');
            if (hasSearchInput) {
                return 'search';
            }
        }

                 // Analyze class names and IDs against patterns
         var combinedText = (className + ' ' + id).toLowerCase();

         for (var landmarkType in LANDMARK_PATTERNS) {
             var config = LANDMARK_PATTERNS[landmarkType];

             // Check class patterns
             for (var i = 0; i < config.classPatterns.length; i++) {
                 if (config.classPatterns[i].test(combinedText)) {
                     return config.role;
                 }
             }
         }

         // Additional substring-based checks for template systems
         if (checkTemplatePatterns(combinedText)) {
             return checkTemplatePatterns(combinedText);
         }

                 // Additional heuristic checks based on content and structure
         return analyzeElementStructure(element);
     }

     /**
      * Check if element is a page wrapper that should not get landmark roles
      * @param {string} combinedText - Combined class names and IDs
      * @param {HTMLElement} element - Element to check
      * @returns {boolean} True if element is a page wrapper
      */
     function isPageWrapper(combinedText, element) {
         // Common wrapper patterns that should be excluded from landmark detection
         var wrapperPatterns = [
             'page-wrapper', 'page_wrapper', 'pagewrapper',
             'body-wrapper', 'body_wrapper', 'bodywrapper',
             'site-wrapper', 'site_wrapper', 'sitewrapper',
             'main-wrapper', 'main_wrapper', 'mainwrapper',
             'content-wrapper', 'content_wrapper', 'contentwrapper',
             'layout-wrapper', 'layout_wrapper', 'layoutwrapper',
             'template-wrapper', 'template_wrapper', 'templatewrapper',
             'container-fluid', 'container_fluid', 'containerfluid',
             'page-container', 'page_container', 'pagecontainer',
             'site-container', 'site_container', 'sitecontainer',
             'outer-wrapper', 'outer_wrapper', 'outerwrapper',
             'inner-wrapper', 'inner_wrapper', 'innerwrapper'
         ];

         // Check for exact wrapper pattern matches
         for (var i = 0; i < wrapperPatterns.length; i++) {
             if (combinedText.includes(wrapperPatterns[i])) {
                 return true;
             }
         }

         // Additional checks for structural wrappers
         var isStructuralWrapper = false;

         // Check if element is direct child of body and contains multiple major sections
         if (element.parentElement && element.parentElement.tagName === 'BODY') {
             var majorSections = element.querySelectorAll('header, nav, main, aside, footer, section, article');
             if (majorSections.length >= 3) {
                 isStructuralWrapper = true;
             }
         }

         // Check if element has very generic wrapper-like class/id patterns
         var genericWrapperPatterns = ['wrapper', 'container', 'layout', 'page', 'site'];
         var hasGenericPattern = false;
         for (var j = 0; j < genericWrapperPatterns.length; j++) {
             if (combinedText === genericWrapperPatterns[j] ||
                 combinedText.endsWith('-' + genericWrapperPatterns[j]) ||
                 combinedText.endsWith('_' + genericWrapperPatterns[j]) ||
                 combinedText.startsWith(genericWrapperPatterns[j] + '-') ||
                 combinedText.startsWith(genericWrapperPatterns[j] + '_')) {
                 hasGenericPattern = true;
                 break;
             }
         }

         // Element is likely a page wrapper if it's structural or has generic patterns
         return isStructuralWrapper || hasGenericPattern;
     }

     /**
      * Check for template-specific patterns using substring matching
      * @param {string} combinedText - Combined class names and IDs
      * @returns {string|null} Recommended ARIA role or null
      */
     function checkTemplatePatterns(combinedText) {
         // Template-specific patterns with substring matching
         var templatePatterns = {
             // Main content patterns
             main: [
                 'main-body', 'main_body', 'mainbody',
                 'main-content', 'main_content', 'maincontent',
                 'content-main', 'content_main', 'contentmain',
                 'primary-content', 'primary_content', 'primarycontent',
                 'central-content', 'central_content', 'centralcontent',
                 'site-content', 'site_content', 'sitecontent',
                 'page-content', 'page_content', 'pagecontent'
             ],

             // Header/Banner patterns
             banner: [
                 'page-title', 'page_title', 'pagetitle',
                 'site-title', 'site_title', 'sitetitle',
                 'page-header', 'page_header', 'pageheader',
                 'site-header', 'site_header', 'siteheader',
                 'main-header', 'main_header', 'mainheader',
                 'top-header', 'top_header', 'topheader',
                 'header-section', 'header_section', 'headersection'
             ],

             // Footer patterns
             contentinfo: [
                 'page-footer', 'page_footer', 'pagefooter',
                 'site-footer', 'site_footer', 'sitefooter',
                 'main-footer', 'main_footer', 'mainfooter',
                 'bottom-section', 'bottom_section', 'bottomsection',
                 'footer-section', 'footer_section', 'footersection'
             ],

             // Navigation patterns
             navigation: [
                 'main-nav', 'main_nav', 'mainnav',
                 'primary-nav', 'primary_nav', 'primarynav',
                 'site-nav', 'site_nav', 'sitenav',
                 'top-nav', 'top_nav', 'topnav',
                 'main-menu', 'main_menu', 'mainmenu',
                 'primary-menu', 'primary_menu', 'primarymenu',
                 'nav-menu', 'nav_menu', 'navmenu',
                 'mega-menu', 'mega_menu', 'megamenu'
             ],

             // Sidebar/Complementary patterns
             complementary: [
                 'side-bar', 'side_bar', 'sidebar',
                 'widget-area', 'widget_area', 'widgetarea',
                 'secondary-content', 'secondary_content', 'secondarycontent',
                 'aside-content', 'aside_content', 'asidecontent',
                 'right-sidebar', 'right_sidebar', 'rightsidebar',
                 'left-sidebar', 'left_sidebar', 'leftsidebar'
             ],

             // Search patterns
             search: [
                 'search-form', 'search_form', 'searchform',
                 'search-box', 'search_box', 'searchbox',
                 'search-area', 'search_area', 'searcharea',
                 'search-container', 'search_container', 'searchcontainer'
             ]
         };

         // Check each pattern category
         for (var role in templatePatterns) {
             var patterns = templatePatterns[role];
             for (var i = 0; i < patterns.length; i++) {
                 if (combinedText.includes(patterns[i])) {
                     return role;
                 }
             }
         }

         // Special handling for SP Page Builder patterns
         if (combinedText.includes('sp-')) {
             if (combinedText.includes('sp-main') || combinedText.includes('sp-body')) {
                 return 'main';
             }
             if (combinedText.includes('sp-title') || combinedText.includes('sp-header')) {
                 return 'banner';
             }
             if (combinedText.includes('sp-bottom') || combinedText.includes('sp-footer')) {
                 return 'contentinfo';
             }
             if (combinedText.includes('sp-nav') || combinedText.includes('sp-menu')) {
                 return 'navigation';
             }
             if (combinedText.includes('sp-side') || combinedText.includes('sp-widget')) {
                 return 'complementary';
             }
         }

         // Template-specific prefixes (T3, Gantry, etc.)
         var commonPrefixes = ['t3-', 'g5-', 'rt-', 'yt-', 'ja-', 'jm-', 'tx-'];
         for (var j = 0; j < commonPrefixes.length; j++) {
             var prefix = commonPrefixes[j];
             if (combinedText.includes(prefix)) {
                 if (combinedText.includes(prefix + 'main') || combinedText.includes(prefix + 'content')) {
                     return 'main';
                 }
                 if (combinedText.includes(prefix + 'header') || combinedText.includes(prefix + 'title')) {
                     return 'banner';
                 }
                 if (combinedText.includes(prefix + 'footer') || combinedText.includes(prefix + 'bottom')) {
                     return 'contentinfo';
                 }
                 if (combinedText.includes(prefix + 'nav') || combinedText.includes(prefix + 'menu')) {
                     return 'navigation';
                 }
                 if (combinedText.includes(prefix + 'side') || combinedText.includes(prefix + 'aside')) {
                     return 'complementary';
                 }
             }
         }

         return null;
     }

     /**
      * Analyze element structure and content for landmark role determination
      * @param {HTMLElement} element - Element to analyze
      * @returns {string|null} Recommended ARIA role or null
      */
         function analyzeElementStructure(element) {
         var className = element.className || '';
         var id = element.id || '';
         var combinedText = (className + ' ' + id).toLowerCase();
         var tagName = element.tagName.toLowerCase();

         // Check if element contains navigation links
         // Be more conservative about navigation detection to avoid false positives
         var navLinks = element.querySelectorAll('a[href], button');
         var linkCount = navLinks.length;

         // Only consider it navigation if it has clear navigation indicators
         var hasNavStructure = linkCount >= 3 &&
             linkCount <= 20 && // Avoid page wrappers with many links
             (element.querySelector('ul, ol') ||
              (combinedText.includes('nav') && !combinedText.includes('wrapper')) ||
              (combinedText.includes('menu') && !combinedText.includes('wrapper'))) &&
             // Exclude if it looks like a wrapper or container
             !isPageWrapper(combinedText, element) &&
             // Only if it's not containing multiple major sections (which indicates it's a wrapper)
             element.querySelectorAll('header, main, aside, footer, section').length <= 1;

         if (hasNavStructure) {
             return 'navigation';
         }

         // Enhanced main content detection for template systems
         var textContent = element.textContent || '';
         var minContentLength = FIETZ_ACCESSIBILITY_CONFIG.landmarkEnhancement.minContentLength;
         var hasSubstantialContent = textContent.trim().length > minContentLength;

         // More flexible main content identification
         var isLikelyMainContent = hasSubstantialContent && (
             combinedText.includes('content') ||
             combinedText.includes('main') ||
             combinedText.includes('body') ||
             element.querySelector('h1, h2, p, article') ||
             (tagName === 'section' && element.querySelector('h1, h2, h3'))
         );

         // Special case for sections with substantial content and no existing main
         if ((tagName === 'section' || tagName === 'div') &&
             hasSubstantialContent &&
             !element.closest('[role="main"], main') &&
             !document.querySelector('[role="main"], main')) {

             // Check if this section seems to be the primary content area
             var isTopLevelContent = element.parentElement &&
                 (element.parentElement.tagName === 'BODY' ||
                  element.parentElement.id.includes('page') ||
                  element.parentElement.id.includes('wrapper'));

             if (isTopLevelContent || isLikelyMainContent) {
                 return 'main';
             }
         }

         if (isLikelyMainContent && !element.closest('[role="main"], main')) {
             return 'main';
         }

         // Enhanced sidebar detection
         var isLikelySidebar = (
             combinedText.includes('side') ||
             combinedText.includes('widget') ||
             combinedText.includes('secondary') ||
             combinedText.includes('aside') ||
             combinedText.includes('right') ||
             combinedText.includes('left')
         ) && (element.querySelector('div, section, article') || textContent.trim().length > 50);

         if (isLikelySidebar) {
             return 'complementary';
         }

         // Enhanced region detection for sections
         var hasHeading = element.querySelector('h1, h2, h3, h4, h5, h6');
         var isSignificantSection = hasHeading && (
             textContent.trim().length > 100 ||
             element.querySelector('p, div, section')
         ) && (
             combinedText.includes('section') ||
             combinedText.includes('area') ||
             combinedText.includes('block') ||
             combinedText.includes('region') ||
             tagName === 'section'
         );

         if (isSignificantSection && !isLikelyMainContent && !isLikelySidebar) {
             return 'region';
         }

         // Special handling for template wrapper elements
         // Only apply to specific, non-page-level wrappers
         if ((combinedText.includes('wrapper') || combinedText.includes('container')) &&
             !isPageWrapper(combinedText, element)) {

             // Check what kind of content is wrapped
             var wrappedContent = element.children;
             if (wrappedContent.length === 1) { // Only for single-child wrappers
                 var firstChildText = (wrappedContent[0].className + ' ' + wrappedContent[0].id).toLowerCase();

                 if (firstChildText.includes('main') || firstChildText.includes('content')) {
                     return 'region'; // Use region instead of main for wrappers
                 }
                 if (firstChildText.includes('header') || firstChildText.includes('title')) {
                     return 'region'; // Use region instead of banner for wrappers
                 }
                 if (firstChildText.includes('footer') || firstChildText.includes('bottom')) {
                     return 'region'; // Use region instead of contentinfo for wrappers
                 }
                 if (firstChildText.includes('nav') || firstChildText.includes('menu')) {
                     return 'region'; // Use region instead of navigation for wrappers
                 }
             }
         }

         return null;
     }

    /**
     * Apply ARIA landmark to element with appropriate label
     * @param {HTMLElement} element - Element to enhance
     * @param {string} role - ARIA role to apply
     */
    function applyLandmarkRole(element, role) {
        element.setAttribute('role', role);
        element.setAttribute('data-faw-landmark-added', 'true');

        // Add appropriate aria-label if the landmark needs one
        var label = null;
        if (!element.hasAttribute('aria-label') && !element.hasAttribute('aria-labelledby')) {
            label = generateLandmarkLabel(element, role);
            if (label) {
                element.setAttribute('aria-label', label);
                element.setAttribute('data-faw-landmark-label', 'true');
            }
        }

        // Debug logging for landmark creation
                        if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('🏛️ **FAW:** Applied landmark role:', {
                element: element.tagName,
                id: element.id || 'keine ID',
                classes: element.className || 'keine Klassen',
                role: role,
                label: label || element.getAttribute('aria-label') || 'kein Label',
                position: element.getBoundingClientRect().top + 'px von oben'
            });
        }
    }

    /**
     * Generate appropriate label for landmark
     * @param {HTMLElement} element - Element to label
     * @param {string} role - ARIA role of the landmark
     * @returns {string|null} Generated label or null
     */
    function generateLandmarkLabel(element, role) {
        var className = element.className || '';
        var id = element.id || '';
        var headingElement = element.querySelector('h1, h2, h3, h4, h5, h6');

        // Use existing heading as label
        if (headingElement && headingElement.textContent.trim()) {
            return headingElement.textContent.trim();
        }

        // Generate contextual labels based on role and common patterns
        switch (role) {
            case 'banner':
                if (className.includes('top') || id.includes('top')) {
                    return 'Seiten-Kopfbereich';
                }
                return 'Haupt-Kopfbereich';

            case 'navigation':
                if (className.includes('main') || id.includes('main')) {
                    return 'Hauptnavigation';
                } else if (className.includes('secondary') || className.includes('sub')) {
                    return 'Sekundäre Navigation';
                } else if (className.includes('breadcrumb')) {
                    return 'Breadcrumb-Navigation';
                } else if (className.includes('footer') || id.includes('footer')) {
                    return 'Footer-Navigation';
                }
                return 'Seitennavigation';

            case 'main':
                return 'Hauptinhalt';

            case 'complementary':
                if (className.includes('sidebar')) {
                    return 'Seitenleiste';
                } else if (className.includes('widget')) {
                    return 'Widget-Bereich';
                }
                return 'Ergänzender Inhalt';

            case 'contentinfo':
                return 'Seiten-Fußbereich';

            case 'search':
                return 'Suche';

            case 'region':
                if (className.includes('hero') || id.includes('hero')) {
                    return 'Hero-Bereich';
                } else if (className.includes('featured')) {
                    return 'Hervorgehobener Bereich';
                }
                return 'Inhaltsbereich';

            default:
                return null;
        }
    }

    /**
     * Process elements for landmark enhancement
     * @returns {number} Number of landmarks added
     */
    function processElementsForLandmarks() {
        return FIETZ_PERFORMANCE.measure('Landmarks Processing', function() {
            var addedCount = 0;
        var processedSelectors = new Set();

        // Process all landmark patterns
        for (var landmarkType in LANDMARK_PATTERNS) {
            var config = LANDMARK_PATTERNS[landmarkType];

            // Process predefined selectors
            config.selectors.forEach(function(selector) {
                if (processedSelectors.has(selector)) return;
                processedSelectors.add(selector);

                try {
                    document.querySelectorAll(selector).forEach(function(element) {
                        if (!element.hasAttribute('role') &&
                            !element.getAttribute('data-faw-landmark-processed')) {
                            applyLandmarkRole(element, config.role);
                            element.setAttribute('data-faw-landmark-processed', 'true');
                            addedCount++;
                        }
                    });
                } catch (e) {
                    console.debug('Invalid selector:', selector, e);
                }
            });
        }

        // Process other div elements that might need landmarks
        document.querySelectorAll('div:not([role]):not([data-faw-landmark-processed])').forEach(function(element) {
            // Skip very small elements or hidden elements
            if (element.offsetWidth === 0 || element.offsetHeight === 0) {
                return;
            }

                         // Skip elements that are too nested (likely not major page sections)
             var maxDepth = FIETZ_ACCESSIBILITY_CONFIG.landmarkEnhancement.maxDivDepth;
             var divDepth = 0;
             var parent = element.parentElement;
             while (parent && divDepth < (maxDepth + 1)) {
                 if (parent.tagName === 'DIV') divDepth++;
                 parent = parent.parentElement;
             }
             if (divDepth >= maxDepth) return;

            var role = determineLandmarkRole(element);
            if (role) {
                applyLandmarkRole(element, role);
                element.setAttribute('data-faw-landmark-processed', 'true');
                addedCount++;
            } else {
                element.setAttribute('data-faw-landmark-processed', 'true');
            }
        });

        // Ensure there's only one main landmark
        var mainLandmarks = document.querySelectorAll('[role="main"]');
        if (mainLandmarks.length > 1) {
            // Keep the first one, convert others to region or remove role
            for (var i = 1; i < mainLandmarks.length; i++) {
                var element = mainLandmarks[i];
                if (element.getAttribute('data-faw-landmark-added')) {
                    element.setAttribute('role', 'region');
                    if (!element.hasAttribute('aria-label')) {
                        element.setAttribute('aria-label', 'Inhaltsbereich');
                    }
                }
            }
        }

        if (addedCount > 0) {
            console.log('🏛️ **FAW:** Auto-generated ARIA landmarks for', addedCount, 'elements');

            // Detailed summary of landmark types when debug mode is enabled
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                var landmarkSummary = {};
                document.querySelectorAll('[data-faw-landmark-added="true"]').forEach(function(element) {
                    var role = element.getAttribute('role');
                    if (!landmarkSummary[role]) {
                        landmarkSummary[role] = 0;
                    }
                    landmarkSummary[role]++;
                });

                console.log('🏛️ **FAW:** Landmark summary:', landmarkSummary);
            }
        }

        return addedCount;
        });
    }

    /**
     * Initialize landmark enhancement system
     * Sets up automatic processing and monitoring for landmark improvements
     */
         function initLandmarkEnhancement() {
         // Check if landmark enhancement is enabled
         if (!FIETZ_ACCESSIBILITY_CONFIG.landmarkEnhancement.enabled) {
             if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                 console.log('⚙️ **FAW:** Landmark enhancement is disabled in configuration');
             }
             return;
         }

         // Process existing elements immediately
         processElementsForLandmarks();

        // Initialize DOM mutation observer for dynamic content monitoring
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function(mutations) {
                var shouldProcess = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1) { // Ensure it's an element node
                                // Check if new node might need landmark processing
                                if (node.tagName === 'DIV' || node.tagName === 'SECTION' ||
                                    node.querySelector('div, section, header, footer, nav, aside')) {
                                    shouldProcess = true;
                                    break;
                                }
                            }
                        }
                    }
                });

                if (shouldProcess) {
                    // Add processing delay to accommodate dynamic styling
                    setTimeout(processElementsForLandmarks, 150);
                }
            });

            // Start observing document changes
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            //console.log('Landmark enhancement monitoring initialized');
        }

        // Re-process on scroll for lazy-loaded content
        var scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                processElementsForLandmarks();
            }, 400);
        });

        //console.log('Landmark enhancement system initialized');
    }

    function initAutoAltText() {
        // Process all existing images in the initial DOM
        processImagesForAltText();

        // Initialize DOM mutation observer for dynamic content monitoring
        if (typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function(mutations) {
                var shouldProcess = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1) { // Ensure it's an element node
                                // Check if new node is an image or contains images
                                if (node.tagName === 'IMG' || node.querySelector('img')) {
                                    shouldProcess = true;
                                    break;
                                }
                            }
                        }
                    }
                });

                if (shouldProcess) {
                    // Add processing delay to accommodate lazy-loading mechanisms
                    setTimeout(processImagesForAltText, 100);
                }
            });

            // Start observing document changes with comprehensive settings
            observer.observe(document.body, {
                childList: true,   // Monitor direct children
                subtree: true      // Monitor all descendants
            });
        }

        // Handle lazy-loaded images triggered by scroll events
        var scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(processImagesForAltText, 200); // Debounced processing
        }, { passive: true }); // Passive listener for better performance
    }
    const I = '<style>.faw-menu,.faw-widget{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;font-weight:400;-webkit-font-smoothing:antialiased}.faw-menu *,.faw-widget *{box-sizing:border-box!important}.faw-menu-btn{position:fixed;z-index:500000;left:30px;bottom:30px;box-shadow:0 5px 15px 0 rgba(0,0,0,0.2);transition:transform .2s ease;border-radius:50%;align-items:center;justify-content:center;width:50px;height:50px;left:84px!important;display:flex;cursor:pointer;border:none!important;outline:none!important;text-decoration:none!important;background:#334696!important;background:linear-gradient(96deg,#334696 0,#334696 100%)!important}.faw-menu-btn svg{width:36px;height:36px;min-height:36px;min-width:36px;max-width:36px;max-height:36px;background:0 0!important}.faw-menu-btn:hover{transform:scale(1.05)}@media only screen and (max-width:768px){.faw-menu-btn{width:42px;height:42px}.faw-menu-btn svg{width:26px;height:26px;min-height:26px;min-width:26px;max-width:26px;max-height:26px}}</style> <div class="faw-widget"> <a href="" target="_blank" class="faw-menu-btn" title="Open Accessibility Menu" role="button" aria-expanded="false"> <svg xmlns="http://www.w3.org/2000/svg" style="fill:white" viewBox="0 0 24 24" width="30px" height="30px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20.5 6c-2.61.7-5.67 1-8.5 1s-5.89-.3-8.5-1L3 8c1.86.5 4 .83 6 1v13h2v-6h2v6h2V9c2-.17 4.14-.5 6-1l-.5-2zM12 6c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg> </a> </div>';
    /**
     * Toggles visibility of DOM element
     * @param {HTMLElement} element - The DOM element to toggle
     * @param {boolean|undefined} forceState - Force visible (true) or hidden (false), or undefined to toggle
     */
    function toggleElementVisibility(element, forceState) {
        if (forceState === undefined) {
            // Toggle current state
            element.style.display = element.style.display === "none" ? "block" : "none";
        } else {
            // Force specific state
            element.style.display = forceState ? "block" : "none";
        }
    }
    const G = '<style>.faw-menu{position:fixed;left:0;top:0;box-shadow:0 0 20px #00000080;opacity:1;transition:.3s;z-index:500000;overflow:hidden;background:#eff1f5;width:500px;line-height:1;font-size:16px;height:100%;letter-spacing:.015em}.faw-menu *{color:#000!important;font-family:inherit;padding:0;margin:0;line-height:1!important;letter-spacing:normal!important}.faw-menu-header{display:flex;align-items:center;justify-content:space-between;padding-left:18px;padding-right:18px;height:55px;font-weight:700!important;background-color:#334696!important}.faw-menu-title{font-size:16px!important;color:#fff!important}.faw-menu-header svg{fill:#334696!important;width:24px!important;height:24px!important;min-width:24px!important;min-height:24px!important;max-width:24px!important;max-height:24px!important}.faw-menu-header>div{display:flex}.faw-menu-header div[role=button]{padding:5px;background:#fff!important;cursor:pointer;border-radius:50%;transition:opacity .3s ease}.faw-menu-header div[role=button]:hover{opacity:.8}.faw-card{margin:0 15px 20px}.faw-card-title{font-size:14px!important;padding:15px 0;font-weight:600!important;opacity:.8}.faw-menu .faw-select{width:100%!important;padding:0 15px!important;font-size:16px!important;font-family:inherit!important;font-weight:600!important;border-radius:45px!important;background:#fff!important;border:none!important;min-height:45px!important;max-height:45px!important;height:45px!important;color:inherit!important}.faw-items{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}.faw-btn{aspect-ratio:6/5;border-radius:12px;padding:0 15px;display:flex;align-items:center;justify-content:center;flex-direction:column;text-align:center;color:#333;font-size:16px!important;background:#fff!important;border:2px solid transparent!important;transition:border-color .2s ease;cursor:pointer;word-break:break-word;gap:10px;position:relative;width:auto!important;height:auto!important}.faw-adjust-font .faw-label div,.faw-btn .faw-translate{font-size:14px!important;font-weight:600!important}.faw-minus,.faw-plus{background-color:#eff1f5!important;border:2px solid transparent;transition:border .2s ease}.faw-minus:hover,.faw-plus:hover{border-color:#334696!important}.faw-amount{font-size:18px!important;font-weight:600!important}.faw-adjust-font svg{width:24px!important;height:24px!important;min-width:24px!important;min-height:24px!important;max-width:24px!important;max-height:24px!important}.faw-btn svg{width:34px!important;height:34px!important;min-width:34px!important;min-height:34px!important;max-width:34px!important;max-height:34px!important}.faw-btn.faw-selected,.faw-btn:hover{border-color:#334696!important}.faw-btn.faw-selected span,.faw-btn.faw-selected svg{fill:#334696!important;color:#334696!important}.faw-btn.faw-selected:after{content:"âœ" position:absolute;top:10px;right:10px;background-color:#334696!important;color:#fff;padding:6px;font-size:10px;width:18px;height:18px;border-radius:100%;line-height:6px}.faw-footer{position:absolute;bottom:0;left:0;right:0;background:#fff;padding:20px;text-align:center;border-top:2px solid #eff1f5}.faw-footer a{font-size:16px!important;text-decoration:none!important;color:#000!important;background:0 0!important;font-weight:600!important}.faw-footer a:hover,.faw-footer a:hover span{color:#334696!important}.faw-menu-content{overflow:scroll;max-height:calc(100% - 80px);padding:30px 0 15px}.faw-adjust-font{background:#fff;padding:20px;margin-bottom:20px}.faw-adjust-font .faw-label{display:flex;justify-content:flex-start}.faw-adjust-font>div{display:flex;justify-content:space-between;margin-top:20px;align-items:center;font-size:15px}.faw-adjust-font .faw-label div{font-size:15px!important}.faw-adjust-font div[role=button]{background:#eff1f5!important;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer}.faw-overlay{position:fixed;top:0;left:0;width:100%;height:100%;z-index:10000}@media only screen and (max-width:560px){.faw-menu{width:100%}}@media only screen and (max-width:420px){.faw-items{grid-template-columns:repeat(2,minmax(0,1fr));gap:.5rem}}</style> <div class="faw-menu"> <div class="faw-menu-header"> <div class="faw-menu-title faw-translate"> Accessibility Menu </div> <div style="gap:15px"> <div role="button" class="faw-menu-reset" title="Reset settings"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M12 4c2.1 0 4.1.8 5.6 2.3 3.1 3.1 3.1 8.2 0 11.3a7.78 7.78 0 0 1-6.7 2.3l.5-2c1.7.2 3.5-.4 4.8-1.7a6.1 6.1 0 0 0 0-8.5A6.07 6.07 0 0 0 12 6v4.6l-5-5 5-5V4M6.3 17.6C3.7 15 3.3 11 5.1 7.9l1.5 1.5c-1.1 2.2-.7 5 1.2 6.8.5.5 1.1.9 1.8 1.2l-.6 2a8 8 0 0 1-2.7-1.8Z"/> </svg> </div> <div role="button" class="faw-menu-close" title="Close"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41Z"/> </svg> </div> </div> </div> <div class="faw-menu-content"> <div class="faw-card"> <select id="faw-language" title="Language" class="faw-select"></select> </div> <div class="faw-card"> <div class="faw-card-title"> Content Adjustments </div> <div class="faw-adjust-font"> <div class="faw-label" style="margin:0"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="margin-right:15px"> <path d="M2 4v3h5v12h3V7h5V4H2m19 5h-9v3h3v7h3v-7h3V9Z"/> </svg> <div class="faw-translate"> Adjust Font Size </div> </div> <div> <div class="faw-minus" data-key="font-size" role="button" aria-pressed="false" title="Decrease Font Size" tabindex="0"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M19 13H5v-2h14v2Z"/> </svg> </div> <div class="faw-amount"> 100% </div> <div class="faw-plus" data-key="font-size" role="button" aria-pressed="false" title="Increase Font Size" tabindex="0"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"/> </svg> </div> </div> </div> <div class="faw-items content"> </div> </div> <div class="faw-card"> <div class="faw-card-title"> Color Adjustments </div> <div class="faw-items contrast"> </div> </div> <div class="faw-card"> <div class="faw-card-title"> Tools </div> <div class="faw-items tools"> </div> </div> </div> <div class="faw-footer"> Web-Barrierefreiheit</div> </div> <div class="faw-overlay"> </div>'
      , E = [{
        label: "Monochrome",
        key: "monochrome",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="m19 19-7-8v8H5l7-8V5h7m0-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Z"/>\r\n</svg>'
    }, {
        label: "Low Saturation",
        key: "low-saturation",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M11 9h2v2h-2V9m-2 2h2v2H9v-2m4 0h2v2h-2v-2m2-2h2v2h-2V9M7 9h2v2H7V9m12-6H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2M9 18H7v-2h2v2m4 0h-2v-2h2v2m4 0h-2v-2h2v2m2-7h-2v2h2v2h-2v-2h-2v2h-2v-2h-2v2H9v-2H7v2H5v-2h2v-2H5V5h14v6Z"/>\r\n</svg>'
    }, {
        label: "High Saturation",
        key: "high-saturation",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M12 16a4 4 0 0 1-4-4 4 4 0 0 1 4-4 4 4 0 0 1 4 4 4 4 0 0 1-4 4m6.7-3.6a6.06 6.06 0 0 0-.86-.4 5.98 5.98 0 0 0 3.86-5.59 6 6 0 0 0-6.78.54A5.99 5.99 0 0 0 12 .81a6 6 0 0 0-2.92 6.14A6 6 0 0 0 2.3 6.4 5.95 5.95 0 0 0 6.16 12a6 6 0 0 0-3.86 5.58 6 6 0 0 0 6.78-.54A6 6 0 0 0 12 23.19a6 6 0 0 0 2.92-6.14 6 6 0 0 0 6.78.54 5.98 5.98 0 0 0-3-5.19Z"/>\r\n</svg>'
    }, {
        label: "High Contrast",
        key: "high-contrast",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1 17.93a8 8 0 0 1 0-15.86v15.86zm2-15.86a8 8 0 0 1 2.87.93H13v-.93zM13 7h5.24c.25.31.48.65.68 1H13V7zm0 3h6.74c.08.33.15.66.19 1H13v-1zm0 9.93V19h2.87a8 8 0 0 1-2.87.93zM18.24 17H13v-1h5.92c-.2.35-.43.69-.68 1zm1.5-3H13v-1h6.93a8.4 8.4 0 0 1-.19 1z"/>\r\n</svg>'
    }, {
        label: "Light Contrast",
        key: "light-contrast",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M12 18a6 6 0 0 1-6-6 6 6 0 0 1 6-6 6 6 0 0 1 6 6 6 6 0 0 1-6 6m8-2.69L23.31 12 20 8.69V4h-4.69L12 .69 8.69 4H4v4.69L.69 12 4 15.31V20h4.69L12 23.31 15.31 20H20v-4.69Z"/>\r\n</svg>'
    }, {
        label: "Dark Contrast",
        key: "dark-contrast",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M18 12c0-4.5-1.92-8.74-6-10a10 10 0 0 0 0 20c4.08-1.26 6-5.5 6-10Z"/>\r\n</svg>'
    }]
      , contentAdjustmentButtons = [{
        label: "Font Weight",
        key: "font-weight",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M13.5 15.5H10v-3h3.5A1.5 1.5 0 0 1 15 14a1.5 1.5 0 0 1-1.5 1.5m-3.5-9h3A1.5 1.5 0 0 1 14.5 8 1.5 1.5 0 0 1 13 9.5h-3m5.6 1.29c.97-.68 1.65-1.79 1.65-2.79 0-2.26-1.75-4-4-4H7v14h7.04c2.1 0 3.71-1.7 3.71-3.79 0-1.52-.86-2.82-2.15-3.42Z"/>\r\n</svg>'
    }, {
        label: "Line Height",
        key: "line-height",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M21 22H3v-2h18v2m0-18H3V2h18v2m-11 9.7h4l-2-5.4-2 5.4M11.2 6h1.7l4.7 12h-2l-.9-2.6H9.4L8.5 18h-2l4.7-12Z"/>\r\n</svg>'
    }, {
        label: "Letter Spacing",
        key: "letter-spacing",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M22 3v18h-2V3h2M4 3v18H2V3h2m6 10.7h4l-2-5.4-2 5.4M11.2 6h1.7l4.7 12h-2l-.9-2.6H9.4L8.5 18h-2l4.7-12Z"/>\r\n</svg>'
    }, {
        label: "Dyslexia Font",
        key: "readable-font",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="m21.59 11.59-8.09 8.09L9.83 16l-1.41 1.41 5.08 5.09L23 13M6.43 11 8.5 5.5l2.07 5.5m1.88 5h2.09L9.43 3H7.57L2.46 16h2.09l1.12-3h5.64l1.14 3Z"/>\r\n</svg>'
    }, {
        label: "Highlight Links",
        key: "highlight-links",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m0 16H5V5h14v14m-5.06-8.94a3.37 3.37 0 0 1 0 4.75L11.73 17A3.29 3.29 0 0 1 7 17a3.31 3.31 0 0 1 0-4.74l1.35-1.36-.01.6c-.01.5.07 1 .23 1.44l.05.15-.4.41a1.6 1.6 0 0 0 0 2.28c.61.62 1.67.62 2.28 0l2.2-2.19c.3-.31.48-.72.48-1.15 0-.44-.18-.83-.48-1.14a.87.87 0 0 1 0-1.24.91.91 0 0 1 1.24 0m4.06-.7c0 .9-.35 1.74-1 2.38l-1.34 1.36v-.6c.01-.5-.07-1-.23-1.44l-.05-.14.4-.42a1.6 1.6 0 0 0 0-2.28 1.64 1.64 0 0 0-2.28 0l-2.2 2.2c-.3.3-.48.71-.48 1.14 0 .44.18.83.48 1.14.17.16.26.38.26.62s-.09.46-.26.62a.86.86 0 0 1-.62.25.88.88 0 0 1-.62-.25 3.36 3.36 0 0 1 0-4.75L12.27 7A3.31 3.31 0 0 1 17 7c.65.62 1 1.46 1 2.36Z"/>\r\n</svg>'
    }, {
        label: "Highlight Title",
        key: "highlight-title",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M5 4v3h5.5v12h3V7H19V4H5Z"/>\r\n</svg>'
    }]
      , toolButtons = [{
        label: "Big Cursor",
        key: "huge-cursor",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M11 1.07C7.05 1.56 4 4.92 4 9h7m-7 6a8 8 0 0 0 8 8 8 8 0 0 0 8-8v-4H4m9-9.93V9h7a8 8 0 0 0-7-7.93Z"/>\r\n</svg>'
    }, {
        label: "Stop Animations",
        key: "stop-animations",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M22 12c0-5.54-4.46-10-10-10-1.17 0-2.3.19-3.38.56l.7 1.94A7.15 7.15 0 0 1 12 3.97 8.06 8.06 0 0 1 20.03 12 8.06 8.06 0 0 1 12 20.03 8.06 8.06 0 0 1 3.97 12c0-.94.19-1.88.53-2.72l-1.94-.66A10.37 10.37 0 0 0 2 12c0 5.54 4.46 10 10 10s10-4.46 10-10M5.47 3.97c.85 0 1.53.71 1.53 1.5C7 6.32 6.32 7 5.47 7c-.79 0-1.5-.68-1.5-1.53 0-.79.71-1.5 1.5-1.5M18 12c0-3.33-2.67-6-6-6s-6 2.67-6 6 2.67 6 6 6 6-2.67 6-6m-7-3v6H9V9m6 0v6h-2V9"/>\r\n</svg>'
    }, {
        label: "Reading Guide",
        key: "readable-guide",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M12 8a3 3 0 0 0 3-3 3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3m0 3.54A13.15 13.15 0 0 0 3 8v11c3.5 0 6.64 1.35 9 3.54A13.15 13.15 0 0 1 21 19V8c-3.5 0-6.64 1.35-9 3.54Z"/>\r\n</svg>'
    }, {
        label: "Read Aloud",
        key: "textToSpeech",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\r<path d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.84 14,18.7V20.77C18,19.86 21,16.28 21,12C21,7.72 18,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.76 16.5,12M3,9V15H7L12,20V4L7,9H3Z"/>\r\n</svg>'
    }];
    /**
     * Generate UI Buttons
     * Generates HTML for accessibility feature buttons with icons and labels
     * @param {Array} buttonConfigs - Array of button configuration objects
     * @param {string} additionalClasses - Optional additional CSS classes
     * @returns {string} Generated HTML string for buttons
     */
    function generateAccessibilityButtons(buttonConfigs, additionalClasses) {
        var htmlString = "";
        for (var index = buttonConfigs.length; index--; ) {
            var config = buttonConfigs[index];
            htmlString += '<button class="faw-btn '.concat(additionalClasses || "", '" type="button" data-key="').concat(config.key, '" title="').concat(config.label, '">').concat(config.icon, '<span class="faw-translate">').concat(config.label, "</span></button>");
        }
        return htmlString;
    }
    var K = {
        de: JSON.parse('{"Accessibility Menu":"Barrierefreiheit","Reset settings":"Einstellungen zur\u00fccksetzen","Close":"Schlie\u00dfen","Content Adjustments":"Inhaltsanpassungen","Adjust Font Size":"Schriftgr\u00f6\u00dfe anpassen","Highlight Title":"Titel hervorheben","Highlight Links":"Links hervorheben","Readable Font":"Lesbare Schrift","Color Adjustments":"Farbanpassungen","Dark Contrast":"Dunkler Kontrast","Light Contrast":"Heller Kontrast","High Contrast":"Hoher Kontrast","High Saturation":"Hohe Farbs\u00e4ttigung","Low Saturation":"Niedrige Farbs\u00e4ttigung","Monochrome":"Monochrom","Tools":"Werkzeuge","Reading Guide":"Lesehilfe","Stop Animations":"Animationen stoppen","Big Cursor":"Gro\u00dfer Cursor","Read Aloud":"Vorlesen","Increase Font Size":"Schriftgr\u00f6\u00dfe vergr\u00f6\u00dfern","Decrease Font Size":"Schriftgr\u00f6\u00dfe verkleinern","Letter Spacing":"Zeichenabstand","Line Height":"Zeilenh\u00f6he","Font Weight":"Schriftst\u00e4rke","Dyslexia Font":"Dyslexie-Schrift","Language":"Sprache","Open Accessibility Menu":"Barrierefreiheitsmen\u00fc \u00f6ffnen"}'),
        en: JSON.parse('{"Accessibility Menu":"Accessibility Menu","Reset settings":"Reset settings","Close":"Close","Content Adjustments":"Content Adjustments","Adjust Font Size":"Adjust Font Size","Highlight Title":"Highlight Title","Highlight Links":"Highlight Links","Readable Font":"Readable Font","Color Adjustments":"Color Adjustments","Dark Contrast":"Dark Contrast","Light Contrast":"Light Contrast","High Contrast":"High Contrast","High Saturation":"High Saturation","Low Saturation":"Low Saturation","Monochrome":"Monochrome","Tools":"Tools","Reading Guide":"Reading Guide","Stop Animations":"Stop Animations","Big Cursor":"Big Cursor","Read Aloud":"Read Aloud","Increase Font Size":"Increase Font Size","Decrease Font Size":"Decrease Font Size","Letter Spacing":"Letter Spacing","Line Height":"Line Height","Font Weight":"Font Weight","Dyslexia Font":"Dyslexia Font","Language":"Language","Open Accessibility Menu":"Open Accessibility Menu"}'),
        es: JSON.parse('{"Accessibility Menu":"Menú de accesibilidad","Reset settings":"Restablecer configuración","Close":"Cerrar","Content Adjustments":"Ajustes de contenido","Adjust Font Size":"Ajustar el tamaÃ±o de fuente","Highlight Title":"Destacar título","Highlight Links":"Destacar enlaces","Readable Font":"Fuente legible","Color Adjustments":"Ajustes de color","Dark Contrast":"Contraste oscuro","Light Contrast":"Contraste claro","High Contrast":"Alto contraste","High Saturation":"Alta saturación","Low Saturation":"Baja saturación","Monochrome":"Monocromo","Tools":"Herramientas","Reading Guide":"Guía de lectura","Stop Animations":"Detener animaciones","Big Cursor":"Cursor grande","Increase Font Size":"Aumentar tamaÃ±o de fuente","Decrease Font Size":"Reducir tamaÃ±o de fuente","Letter Spacing":"Espaciado entre letras","Line Height":"Altura de línea","Font Weight":"Grosor de fuente","Dyslexia Font":"Fuente para dislexia","Language":"Idioma","Open Accessibility Menu":"Abrir menú de accesibilidad"}'),
        fr: JSON.parse('{"Accessibility Menu":"Menu d\'accessibilité","Reset settings":"Réinitialiser les paramètres","Close":"Fermer","Content Adjustments":"Ajustements de contenu","Adjust Font Size":"Ajuster la taille de police","Highlight Title":"Surligner le titre","Highlight Links":"Surligner les liens","Readable Font":"Police lisible","Color Adjustments":"Ajustements de couleur","Dark Contrast":"Contraste foncé","Light Contrast":"Contraste clair","High Contrast":"Contraste élevé","High Saturation":"Saturation élevée","Low Saturation":"Saturation faible","Monochrome":"Monochrome","Tools":"Outils","Reading Guide":"Guide de lectu","Stop Animations":"Arrêter les animations","Big Cursor":"Gros curseur","Increase Font Size":"Augmenter la taille de police","Decrease Font Size":"Réduire la taille de police","Letter Spacing":"Espacement des lettres","Line Height":"Hauteur de ligne","Font Weight":"Poids de la police","Dyslexia Font":"Police dyslexie","Language":"Langue","Open Accessibility Menu":"Ouvrir le menu d\'accessibilité"}'),
        it: JSON.parse('{"Accessibility Menu":"Menu di accessibilità","Reset settings":"Ripristina impostazioni","Close":"Chiudi","Content Adjustments":"Regolazioni del contenuto","Adjust Font Size":"Regola la dimensione del carattere","Highlight Title":"Evidenzia il titolo","Highlight Links":"Evidenzia i collegamenti","Readable Font":"Carattere leggibile","Color Adjustments":"Regolazioni del colore","Dark Contrast":"Contrasto scuro","Light Contrast":"Contrasto chiaro","High Contrast":"Alto contrasto","High Saturation":"Alta saturazione","Low Saturation":"Bassa saturazione","Monochrome":"Monocromatico","Tools":"Strumenti","Reading Guide":"Guida alla lettura","Stop Animations":"Arresta le animazioni","Big Cursor":"Cursore grande","Increase Font Size":"Aumenta la dimensione del carattere","Decrease Font Size":"Diminuisci la dimensione del carattere","Letter Spacing":"Spaziatura delle lettere","Line Height":"Altezza della linea","Font Weight":"Peso del carattere","Dyslexia Font":"Carattere per dislessia","Language":"Lingua","Open Accessibility Menu":"Apri il menu di accessibilità"}'),
        nl: JSON.parse('{"Accessibility Menu":"Toegankelijkheidsmenu","Reset settings":"Instellingen resetten","Close":"Sluiten","Content Adjustments":"Inhoudsaanpassingen","Adjust Font Size":"Lettergrootte aanpassen","Highlight Title":"Titel markeren","Highlight Links":"Links markeren","Readable Font":"Leesbaar lettertype","Color Adjustments":"Kleur aanpassingen","Dark Contrast":"Donker contrast","Light Contrast":"Licht contrast","High Contrast":"Hoog contrast","High Saturation":"Hoge verzadiging","Low Saturation":"Lage verzadiging","Monochrome":"Monochroom","Tools":"Gereedschappen","Reading Guide":"Leesgids","Stop Animations":"Animaties stoppen","Big Cursor":"Grote cursor","Increase Font Size":"Lettergrootte vergroten","Decrease Font Size":"Lettergrootte verkleinen","Letter Spacing":"Letterafstand","Line Height":"Regelhoogte","Font Weight":"Letterdikte","Dyslexia Font":"Dyslexie lettertype","Language":"Taal","Open Accessibility Menu":"Toegankelijkheidsmenu openen"}'),
        no: JSON.parse('{"Accessibility Menu":"Tilgjengelighetsmeny","Reset settings":"Tilbakestill innstillinger","Close":"Lukk","Content Adjustments":"Innholdstilpasninger","Adjust Font Size":"Juster skriftstørrelse","Highlight Title":"Fremhev tittel","Highlight Links":"Fremhev lenker","Readable Font":"Lesbar skrifttype","Color Adjustments":"Fargejusteringer","Dark Contrast":"Mørk kontrast","Light Contrast":"Lys kontrast","High Contrast":"Høy kontrast","High Saturation":"Høy metning","Low Saturation":"Lav metning","Monochrome":"Monokrom","Tools":"Verktøy","Reading Guide":"Leseguide","Stop Animations":"Stopp animasjoner","Big Cursor":"Stor peker","Increase Font Size":"Øk skriftstørrelsen","Decrease Font Size":"Reduser skriftstørrelsen","Letter Spacing":"Bokstavavstand","Line Height":"Linjehøyde","Font Weight":"Skriftvekt","Dyslexia Font":"Dysleksisk skrifttype","Language":"Språk","Open Accessibility Menu":"Åpne tilgjengelighetsmeny"}'),
        pl: JSON.parse('{"Accessibility Menu":"Menu dostępności","Reset settings":"Reset ustawień","Close":"Zamknij","Content Adjustments":"Dostosowanie zawartości","Adjust Font Size":"Dostosuj rozmiar czcionki","Highlight Title":"Podświetl tytuły","Highlight Links":"Podświetl linki","Readable Font":"Czytelna czcionka","Color Adjustments":"Dostosowanie kolorów","Dark Contrast":"Ciemny kontrast","Light Contrast":"Jasny kontrast","High Contrast":"Wysoki kontrast","High Saturation":"Wysoka saturacja","Low Saturation":"Niska saturacja","Monochrome":"Monochromatyczność","Tools":"Narzędzia","Reading Guide":"Pomocnik czytania","Stop Animations":"Wstrzymaj animacje","Big Cursor":"Du\u017cy kursor","Increase Font Size":"Zwiększ rozmiar czcionki","Decrease Font Size":"Zmniejsz rozmiar czcionki","Letter Spacing":"Odstępy między literami","Line Height":"Wysokość wierszy","Font Weight":"Pogrubiona czcionka","Dyslexia Font":"Czcionka dla dysletyków","Language":"Język","Open Accessibility Menu":"Otwórz menu dostępności"}'),
        pt: JSON.parse('{"Accessibility Menu":"Menu de Acessibilidade","Reset settings":"Redefinir configurações","Close":"Fechar","Content Adjustments":"Ajustes de Conteúdo","Adjust Font Size":"Ajustar Tamanho da Fonte","Highlight Title":"Destacar Título","Highlight Links":"Destacar Links","Readable Font":"Fonte Legível","Color Adjustments":"Ajustes de Cor","Dark Contrast":"Contraste Escuro","Light Contrast":"Contraste Claro","High Contrast":"Alto Contraste","High Saturation":"Saturação Alta","Low Saturation":"Saturação Baixa","Monochrome":"Monocromático","Tools":"Ferramentas","Reading Guide":"Guia de Leitura","Stop Animations":"Parar Animações","Big Cursor":"Cursor Grande","Increase Font Size":"Aumentar Tamanho da Fonte","Decrease Font Size":"Diminuir Tamanho da Fonte","Letter Spacing":"Espaçamento entre Letras","Line Height":"Altura da Linha","Font Weight":"Espessura da Fonte","Dyslexia Font":"Fonte para Dislexia","Language":"Idioma","Open Accessibility Menu":"Abrir menu de acessibilidade"}'),
        cs: JSON.parse('{"Accessibility Menu":"Přístupnostní menu","Reset settings":"Obnovit nastavení","Close":"Zavřít","Content Adjustments":"Úpravy obsahu","Adjust Font Size":"Nastavit velikost písma","Highlight Title":"Zvýraznit nadpis","Highlight Links":"Zvýraznit odkazy","Readable Font":"Čitelný font","Color Adjustments":"Nastavení barev","Dark Contrast":"Tmavý kontrast","Light Contrast":"Světlý kontrast","High Contrast":"Vysoký kontrast","High Saturation":"Vysoká saturace","Low Saturation":"Nízká saturace","Monochrome":"Monochromatické","Tools":"Nástroje","Reading Guide":"Průvodce čtením","Stop Animations":"Zastavit animace","Big Cursor":"Velk\u00fd kurzor","Increase Font Size":"Zvětšit velikost písma","Decrease Font Size":"Zmenšit velikost písma","Letter Spacing":"Mezery mezi písmeny","Line Height":"Výška řádku","Font Weight":"Tloušťka písma","Dyslexia Font":"Dyslexický font","Language":"Jazyk","Open Accessibility Menu":"Otevřít přístupnostní menu"}'),
        sk: JSON.parse('{"Accessibility Menu":"Menu prístupnosti","Reset settings":"Obnoviť nastavenia","Close":"Zavrieť","Content Adjustments":"Nastavenia obsahu","Adjust Font Size":"Prispôsobiť veľkosť písma","Highlight Title":"Zvýrazniť nadpis","Highlight Links":"Zvýrazniť odkazy","Readable Font":"Čitateľné písmo","Color Adjustments":"Nastavenia farieb","Dark Contrast":"Tmavý kontrast","Light Contrast":"Svetlý kontrast","High Contrast":"Vysoký kontrast","High Saturation":"Vysoká saturácia","Low Saturation":"Nízka saturácia","Monochrome":"Monochromatické","Tools":"Nástroje","Reading Guide":"Sprievodca Äítaním","Stop Animations":"Zastaviť animácie","Big Cursor":"Veľký kurzor","Increase Font Size":"Zväčšiť veľkosť písma","Decrease Font Size":"Zmenšiť veľkosť písma","Letter Spacing":"Rozostup písmen","Line Height":"Výška riadku","Font Weight":"Tlak písma","Dyslexia Font":"Písmo pre dyslexiu","Language":"Jazyk","Open Accessibility Menu":"Otvoriť menu prístupnosti"}'),
        hu: JSON.parse('{"Accessibility Menu":"HozzáférhetÅ\'ségi menü","Reset settings":"Beállítások visszaállítása","Close":"Bezárás","Content Adjustments":"Tartalom beállításai","Adjust Font Size":"Betűméret beállítása","Highlight Title":"Cím kiemelése","Highlight Links":"Linkek kiemelése","Readable Font":"Olvasható betűtípus","Color Adjustments":"Színbeállítások","Dark Contrast":"Sötét kontraszt","Light Contrast":"Világos kontraszt","High Contrast":"Magas kontraszt","High Saturation":"Magas telítettség","Low Saturation":"Alacsony telítettség","Monochrome":"Monokróm","Tools":"Eszközök","Reading Guide":"Olvasási útmutató","Stop Animations":"Animációk leállítása","Big Cursor":"Nagy kurzor","Increase Font Size":"Betűméret növelése","Decrease Font Size":"Betűméret csökkentése","Letter Spacing":"Betűtávolság","Line Height":"Sor magasság","Font Weight":"Betűtípus vastagsága","Dyslexia Font":"Dyslexia betűtípus","Language":"Nyelv","Open Accessibility Menu":"HozzáférhetÅ\'ségi menü megnyitása"}'),
        ro: JSON.parse('{"Accessibility Menu":"Meniu de accesibilitate","Reset settings":"Resetează setările","Close":"Închide","Content Adjustments":"Ajustări conținut","Adjust Font Size":"Ajustează dimensiunea fontului","Highlight Title":"Evidențiază titlul","Highlight Links":"Evidențiază legăturile","Readable Font":"Font lizibil","Color Adjustments":"Ajustări de culoare","Dark Contrast":"Contrast întunecat","Light Contrast":"Contrast luminos","High Contrast":"Contrast ridicat","High Saturation":"Saturație ridicată","Low Saturation":"Saturație redusă","Monochrome":"Monocrom","Tools":"Instrumente","Reading Guide":"Ghid de lectură","Stop Animations":"Opriți animațiile","Big Cursor":"Cursor mare","Increase Font Size":"Mărește dimensiunea fontului","Decrease Font Size":"Micșorează dimensiunea fontului","Letter Spacing":"Spațierea literelor","Line Height":"Înălțimea liniei","Font Weight":"Grosimea fontului","Dyslexia Font":"Font pentru dislexie","Language":"Limbă","Open Accessibility Menu":"Deschideți meniul de accesibilitate"}'),
        hr: JSON.parse('{"Accessibility Menu":"Izbornik PristupaÄnosti","Reset settings":"Resetiraj postavke","Close":"Zatvori","Content Adjustments":"Prilagodbe Sadržaja","Adjust Font Size":"Prilagodi VeliÄinu Fonta","Highlight Title":"Istakni Naslove","Highlight Links":"Istakni Poveznice","Readable Font":"Čitljiv Font","Color Adjustments":"Prilagodbe Boja","Dark Contrast":"Tamni Kontrast","Light Contrast":"Svijetli Kontrast","High Contrast":"Visoki Kontrast","High Saturation":"Visoka Zasićenost","Low Saturation":"Niska Zasićenost","Monochrome":"Jednobojno","Tools":"Alati","Reading Guide":"Vodič Za Čitanje","Stop Animations":"Zaustavi Animacije","Big Cursor":"Veliki Kursor","Increase Font Size":"Povećaj VeliÄinu Fonta","Decrease Font Size":"Smanji VeliÄinu Fonta","Letter Spacing":"Razmak IzmeÄ\'u Slova","Line Height":"Visina Linije","Font Weight":"Debljina Fonta","Dyslexia Font":"Font Za Disleksiju","Language":"Jezik","Open Accessibility Menu":"Otvori Izbornik PristupaÄnosti"}'),
        sl: JSON.parse('{"Accessibility Menu":"Meni dostopnosti","Reset settings":"Ponastavi nastavitve","Close":"Zapri","Content Adjustments":"Prilagoditve vsebine","Adjust Font Size":"Prilagodi velikost pisave","Highlight Title":"OznaÄi naslov","Highlight Links":"OznaÄi povezave","Readable Font":"Bralna pisava","Color Adjustments":"Prilagoditve barv","Dark Contrast":"Temni kontrast","Light Contrast":"Svetli kontrast","High Contrast":"Visoki kontrast","High Saturation":"Visoka nasiÄenost","Low Saturation":"Nizka nasiÄenost","Monochrome":"Monokromno","Tools":"Orodja","Reading Guide":"Bralni vodnik","Stop Animations":"Ustavi animacije","Big Cursor":"Velik kazalec","Increase Font Size":"PoveÄaj velikost pisave","Decrease Font Size":"Zmanjšaj velikost pisave","Letter Spacing":"Razmik med Ärkami","Line Height":"Višina vrstice","Font Weight":"Debelina pisave","Dyslexia Font":"Pisava za disleksijo","Language":"Jezik","Open Accessibility Menu":"Odpri meni dostopnosti"}'),
        fi: JSON.parse('{"Accessibility Menu":"Saavutettavuusvalikko","Reset settings":"Palauta asetukset","Close":"Sulje","Content Adjustments":"Sisällön säädöt","Adjust Font Size":"Säädä fonttikokoa","Highlight Title":"Korosta otsikko","Highlight Links":"Korosta linkit","Readable Font":"Helposti luettava fontti","Color Adjustments":"Värien säädöt","Dark Contrast":"Tumma kontrasti","Light Contrast":"Vaalea kontrasti","High Contrast":"Korkea kontrasti","High Saturation":"Korkea kylläisyys","Low Saturation":"Matala kylläisyys","Monochrome":"Yksivärinne","Tools":"Työkalut","Reading Guide":"Lukemisopas","Stop Animations":"Pysäytä animaatiot","Big Cursor":"Iso kohdistin","Increase Font Size":"Suurenna fonttikokoa","Decrease Font Size":"Pienennä fonttikokoa","Letter Spacing":"Kirjainten välistys","Line Height":"Rivin korkeus","Font Weight":"Fontin paksuus","Dyslexia Font":"Dysleksiafontti","Language":"Kieli","Open Accessibility Menu":"Avaa saavutettavuusvalikko"}')
    }
      , _ = [{
        code: "de",
        label: "Deutsch (German)"
    }, {
        code: "en",
        label: "English (English)"
    }, {
        code: "fr",
        label: "Français (French)"
    }, {
        code: "es",
        label: "Español (Spanish)"
    }, {
        code: "it",
        label: "Italiano (Italian)"
    }, {
        code: "nl",
        label: "Nederlands (Dutch)"
    }, {
        code: "no",
        label: "Norsk (Norwegian/Danish)"
    }, {
        code: "pl",
        label: "Polski (Polish)"
    }, {
        code: "pt",
        label: "Português (Portuguese)"
    }, {
        code: "cs",
        label: "Čeština (Czech)"
    }, {
        code: "sk",
        label: "Slovenčina (Slovak)"
    }, {
        code: "hu",
        label: "Magyar (Hungarian)"
    }, {
        code: "ro",
        label: "Română (Romanian)"
    }, {
        code: "hr",
        label: "Hrvatski (Croatian)"
    }, {
        code: "sl",
        label: "Slovenščina (Slovenian)"
    }, {
        code: "fi",
        label: "suomi (Finnish)"
    }];
    /**
     * Translation function with automatic data-translate attribute handling
     * @param {HTMLElement} element - Element to translate
     * @param {string} fallbackText - Fallback text if no data-translate attribute exists
     * @returns {string} Translated text
     */
    function translateElement(element, fallbackText) {
        var translateKey = element.getAttribute("data-translate");

        // If no data-translate attribute and fallback provided, set it
        if (!translateKey && fallbackText) {
            translateKey = fallbackText;
            element.setAttribute("data-translate", translateKey);
        }

        // Get translation from language pack
        function getTranslation(key) {
            var currentLang = loadSettings().lang;
            return (K[currentLang] || K.en)[key] || key;
        }

        return getTranslation(translateKey);
    }

    /**
     * Apply translations to all translatable elements in container
     * @param {HTMLElement} container - Container to search for translatable elements
     */
    function applyTranslations(container) {
        // Translate elements with .faw-card-title and .faw-translate classes
        container.querySelectorAll(".faw-card-title, .faw-translate").forEach(function(element) {
            element.innerText = translateElement(element, String(element.innerText || "").trim());
        });

        // Translate title attributes
        container.querySelectorAll("[title]").forEach(function(element) {
            element.setAttribute("title", translateElement(element, element.getAttribute("title")));
        });
    }
    var U = function(t, e) {
        var i = {};
        for (var n in t)
            Object.prototype.hasOwnProperty.call(t, n) && e.indexOf(n) < 0 && (i[n] = t[n]);
        if (null != t && "function" == typeof Object.getOwnPropertySymbols) {
            var a = 0;
            for (n = Object.getOwnPropertySymbols(t); a < n.length; a++)
                e.indexOf(n[a]) < 0 && Object.prototype.propertyIsEnumerable.call(t, n[a]) && (i[n[a]] = t[n[a]])
        }
        return i
    };
    /**
     * Create the accessibility menu
     * @param {Object} options - Configuration options for the menu
     * @returns {HTMLElement} Created menu element
     */
    function createAccessibilityMenu(options) {
        var e, i, l, c,
            container = options.container,
            position = options.position,
            otherOptions = U(options, ["container", "position"]),
            menuWrapper = document.createElement("div");
        menuWrapper.innerHTML = G;
        var menuElement = menuWrapper.querySelector(".faw-menu");

        // Position menu on the right if specified
        if (position && position.includes("right")) {
            menuElement.style.right = "0px";
            menuElement.style.left = "auto";
        }

        // Populate menu sections with buttons
        menuElement.querySelector(".content").innerHTML = generateAccessibilityButtons(contentAdjustmentButtons);
        menuElement.querySelector(".tools").innerHTML = generateAccessibilityButtons(toolButtons, "faw-tools");
        menuElement.querySelector(".contrast").innerHTML = generateAccessibilityButtons(E, "faw-filter");

        // Add close button functionality
        menuWrapper.querySelectorAll(".faw-menu-close, .faw-overlay").forEach(function(element) {
            element.addEventListener("click", function() {
                toggleElementVisibility(menuWrapper, false);
            });
        });

        // Setup font size adjustment controls
        menuElement.querySelectorAll(".faw-adjust-font div[role='button']").forEach(function(buttonElement) {
            buttonElement.addEventListener("click", function() {
                var currentFontSize = getSetting("fontSize") || 1;
                var adjustmentValue = currentFontSize;

                if (buttonElement.classList.contains("faw-minus")) {
                    adjustmentValue -= 0.1;
                } else {
                    adjustmentValue += 0.1;
                }

                adjustmentValue = Math.max(adjustmentValue, 0.1);
                adjustmentValue = Math.min(adjustmentValue, 2);
                adjustmentValue = Number(adjustmentValue.toFixed(2));

                adjustFontSizes(adjustmentValue || 1);
                updateSettings({ fontSize: adjustmentValue });
            });
        });

        // Setup accessibility feature buttons
        menuElement.querySelectorAll(".faw-btn").forEach(function(buttonElement) {
            buttonElement.addEventListener("click", function() {
                var featureKey = buttonElement.dataset.key;
                var isEnabling = !buttonElement.classList.contains("faw-selected");

                if (buttonElement.classList.contains("faw-filter")) {
                    // Handle contrast filter buttons
                    menuElement.querySelectorAll(".faw-filter").forEach(function(filterBtn) {
                        filterBtn.classList.remove("faw-selected");
                    });

                    updateSettings({ contrast: !!isEnabling && featureKey });

                    if (isEnabling) {
                        buttonElement.classList.add("faw-selected");
                    }

                    applyContrastFilters();
                } else {
                    // Handle other accessibility feature buttons
                    buttonElement.classList.toggle("faw-selected", isEnabling);

                    var settingsUpdate = {};
                    settingsUpdate[featureKey] = isEnabling;
                    updateSettings(settingsUpdate);

                    applyAllAccessibilityFeatures();
                }
            });
        });

        // Setup reset button
        var resetButton = menuElement.querySelector(".faw-menu-reset");
        if (resetButton) {
            resetButton.addEventListener("click", function() {
                // Reset all accessibility settings
                saveSettings({ states: {} });

                // IMPORTANT: Remove all contrast-related inline styles before re-initializing
                var contrastProcessed = document.querySelectorAll('[data-faw-contrast-processed]');
                contrastProcessed.forEach(function(el) {
                    var originalColor = el.getAttribute('data-faw-original-color');
                    if (originalColor) {
                        el.style.setProperty('color', originalColor);
                    } else {
                        el.style.removeProperty('color');
                    }
                    el.style.removeProperty('text-shadow');
                    el.removeAttribute('data-faw-contrast-processed');
                    el.removeAttribute('data-faw-original-color');
                    el.removeAttribute('data-faw-contrast-ratio');
                    el.removeAttribute('data-faw-background-estimate');
                    el.removeAttribute('data-faw-improved-ratio');
                    el.removeAttribute('data-faw-text-shadow');
                    el.removeAttribute('data-faw-forced-black');
                });

                // Remove form contrast processing
                var formProcessed = document.querySelectorAll('[data-faw-form-contrast-processed]');
                formProcessed.forEach(function(el) {
                    el.style.removeProperty('border-color');
                    el.style.removeProperty('outline');
                    el.style.removeProperty('box-shadow');
                    el.removeAttribute('data-faw-form-contrast-processed');
                    el.removeAttribute('data-faw-form-contrast-ratio');
                    el.removeAttribute('data-faw-form-enhancement-type');
                    el.removeAttribute('data-faw-form-enhancement-color');
                });

                // Remove underline additions from links
                var underlined = document.querySelectorAll('[data-faw-underline-added]');
                underlined.forEach(function(el) {
                    el.style.removeProperty('text-decoration');
                    el.removeAttribute('data-faw-underline-added');
                });

                initializeAccessibility();

                // Remove selected state from all buttons
                var selectedElements = document.querySelectorAll(".faw-selected");
                if (selectedElements) {
                    selectedElements.forEach(function(element) {
                        if (element.classList) {
                            element.classList.remove("faw-selected");
                        }
                    });
                }
            });
        }

        // Setup current font size display
        var currentSettings = loadSettings();
        var fontSizeValue = 1;
        if (currentSettings && currentSettings.states && currentSettings.states.fontSize) {
            fontSizeValue = Number(currentSettings.states.fontSize) || 1;
        }

        if (fontSizeValue !== 1) {
            var amountElement = menuElement.querySelector(".faw-amount");
            if (amountElement) {
                amountElement.innerHTML = (100 * fontSizeValue) + "%";
            }
        }

        // Setup language selector
        var languageSelect = menuElement.querySelector("#faw-language");
        if (languageSelect) {
            languageSelect.innerHTML = _.map(function(langOption) {
                return '<option value="' + langOption.code + '">' + langOption.label + '</option>';
            }).join("");

            // Set current language
            var currentLang = (otherOptions && otherOptions.lang) || "en";
            if (currentSettings.lang !== currentLang) {
                saveSettings({ lang: currentLang });
            }

            languageSelect.value = currentLang;

            // Add language change handler
            languageSelect.addEventListener("change", function() {
                saveSettings({ lang: languageSelect.value });
                applyTranslations(container);
            });
        }

        // Apply translations to menu
        applyTranslations(menuElement);

        // Restore selected states for active features
        if (currentSettings.states) {
            for (var featureKey in currentSettings.states) {
                if (currentSettings.states[featureKey] && featureKey !== "fontSize") {
                    var dataKey = (featureKey === "contrast") ? currentSettings.states[featureKey] : featureKey;
                    var featureButton = menuElement.querySelector('.faw-btn[data-key="' + dataKey + '"]');
                    if (featureButton && featureButton.classList) {
                        featureButton.classList.add("faw-selected");
                    }
                }
            }
        }

        // Add menu to container and return it
        container.appendChild(menuWrapper);
        return menuWrapper;
    }
    /**
     * Object.assign polyfill for merging objects
     * @param {Object} target - Target object to merge into
     * @param {...Object} sources - Source objects to merge from
     * @returns {Object} Merged target object
     */
    var objectAssign = function() {
        return objectAssign = Object.assign || function(target) {
            for (var source, i = 1, argsLength = arguments.length; i < argsLength; i++) {
                source = arguments[i];
                for (var key in source) {
                    if (Object.prototype.hasOwnProperty.call(source, key)) {
                        target[key] = source[key];
                    }
                }
            }
            return target;
        },
        objectAssign.apply(this, arguments);
    };

    /**
     * Alternative object merge function
     * @param {Object} target - Target object to merge into
     * @param {...Object} sources - Source objects to merge from
     * @returns {Object} Merged target object
     */
    var mergeObjects = function() {
        return mergeObjects = Object.assign || function(target) {
            for (var source, i = 1, argsLength = arguments.length; i < argsLength; i++) {
                source = arguments[i];
                for (var key in source) {
                    if (Object.prototype.hasOwnProperty.call(source, key)) {
                        target[key] = source[key];
                    }
                }
            }
            return target;
        },
        mergeObjects.apply(this, arguments);
         };

     var defaultSettings = {
        lang: "en",
        position: "bottom-left"
    };
    /**
     * Initialize accessibility widget
     * @param {Object} options - Configuration options for the widget
     */
    function initializeAccessibilityWidget(options) {
        var settings = mergeObjects({}, defaultSettings);
        try {
            var loadedSettings = loadSettings(false);
            settings = mergeObjects(mergeObjects({}, settings), loadedSettings);
            initializeAccessibility();
        } catch (error) {}
        saveSettings(settings = mergeObjects(mergeObjects({}, settings), options));

        // Initialize the widget UI
        createAccessibilityWidget(settings);
    }

    /**
     * Create the accessibility widget UI
     * @param {Object} options - Widget configuration options
     */
    function createAccessibilityWidget(options) {
        var position = options.position || "bottom-left";
        var offset = options.offset || [20, 20];
        var container = document.createElement("div");

        container.innerHTML = I; // Use the existing widget HTML template
        container.classList.add("faw-container");

        var menuButton = container.querySelector(".faw-menu-btn");
        var offsetX = (offset && offset[0] !== undefined) ? offset[0] : 20;
        var offsetY = (offset && offset[1] !== undefined) ? offset[1] : 25;

        // Calculate position styles
        var positionStyles = {
            left: offsetX + "px",
            bottom: offsetY + "px"
        };

        // Apply position-specific styles
        if (position === "bottom-right") {
            positionStyles = objectAssign(objectAssign({}, positionStyles), {
                right: offsetX + "px",
                left: "auto"
            });
        } else if (position === "top-left") {
            positionStyles = objectAssign(objectAssign({}, positionStyles), {
                top: offsetY + "px",
                bottom: "auto"
            });
        } else if (position === "center-left") {
            positionStyles = objectAssign(objectAssign({}, positionStyles), {
                bottom: "calc(50% - (55px / 2) - " + ((offset && offset[1] !== undefined) ? offset[1] : 0) + "px)"
            });
        } else if (position === "top-right") {
            positionStyles = {
                top: offsetY + "px",
                bottom: "auto",
                right: offsetX + "px",
                left: "auto"
            };
        } else if (position === "center-right") {
            positionStyles = {
                right: offsetX + "px",
                left: "auto",
                bottom: "calc(50% - (55px / 2) - " + ((offset && offset[1] !== undefined) ? offset[1] : 0) + "px)"
            };
        } else if (position === "bottom-center") {
            positionStyles = objectAssign(objectAssign({}, positionStyles), {
                left: "calc(50% - (55px / 2) - " + ((offset && offset[0] !== undefined) ? offset[0] : 0) + "px)"
            });
        } else if (position === "top-center") {
            positionStyles = {
                top: offsetY + "px",
                bottom: "auto",
                left: "calc(50% - (55px / 2) - " + ((offset && offset[0] !== undefined) ? offset[0] : 0) + "px)"
            };
        }

        // Apply calculated styles to menu button
        Object.assign(menuButton.style, positionStyles);

        var menuElement = null;

        // Add click event listener to menu button
        if (menuButton) {
            menuButton.addEventListener("click", function(event) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

                if (menuElement) {
                    toggleElementVisibility(menuElement);
                } else {
                    menuElement = createAccessibilityMenu(objectAssign(objectAssign({}, options), {
                        container: container
                    }));
                }
            });
        }

        // Apply translations to the widget
        applyTranslations(container);

        // Add widget to page
        document.body.appendChild(container);

        // Initialize Text-to-Speech feature
        if (FIETZ_ACCESSIBILITY_CONFIG.textToSpeech.enabled) {
            initTextToSpeech();
        }
    }
    /**
     * Get data attribute value with faw prefix
     * @param {string} attributeName - Name of the attribute (without data-faw- prefix)
     * @returns {string|null} Attribute value or null if not found
     */
    function getDataAttribute(attributeName) {
        var fullAttributeName = "data-faw-" + attributeName;
        var element = document.querySelector("[" + fullAttributeName + "]");
        return element ? element.getAttribute(fullAttributeName) : null;
    }

    // Initialize accessibility system after complete document load
    function initializeAccessibilitySystem() {
            // Initialize accessibility CSS loading system (critical - load immediately)
            FIETZ_PERFORMANCE.measure('CSS Loading', function() {
                loadAccessibilityCSS();
            });

            // Lazy loading for less critical features
            if (FIETZ_ACCESSIBILITY_CONFIG.optimizations.lazyLoadNonCritical) {
                // Load critical features immediately
                FIETZ_PERFORMANCE.measure('Contrast Enhancement Initialization', function() {
                    initContrastEnhancement();
                });

                // Load less critical features with a delay
                                 setTimeout(function() {
                     FIETZ_PERFORMANCE.measure('Alt-Text Initialization', function() {
                         initAutoAltText();
                     });
                 }, 30);

                 setTimeout(function() {
                     FIETZ_PERFORMANCE.measure('Accessible Names Initialization', function() {
                         initAccessibleNames();
                     });
                 }, 40);

                 setTimeout(function() {
                     FIETZ_PERFORMANCE.measure('Landmark Enhancement Initialization', function() {
                         initLandmarkEnhancement();
                     });
                 }, 50);
            } else {
                // Standard loading (all features immediately)
                FIETZ_PERFORMANCE.measure('Alt-Text Initialization', function() {
                    initAutoAltText();
                });

                FIETZ_PERFORMANCE.measure('Accessible Names Initialization', function() {
                    initAccessibleNames();
                });

                FIETZ_PERFORMANCE.measure('Contrast Enhancement Initialization', function() {
                    initContrastEnhancement();
                });

                FIETZ_PERFORMANCE.measure('Landmark Enhancement Initialization', function() {
                    initLandmarkEnhancement();
                });
            }

            // Get configuration from data attributes
            var lang = getDataAttribute("lang");
            var position = getDataAttribute("position");
            var offset = getDataAttribute("offset");

            // Fallback language detection
            if (!lang) {
                var htmlElement = document.querySelector("html");
                if (htmlElement) {
                    var htmlLang = htmlElement.getAttribute("lang");
                    if (htmlLang) {
                        lang = htmlLang.replace(/[_-].*/, "");
                    }
                }
            }

            // Browser language fallback
            if (!lang && typeof navigator !== "undefined" && navigator.language) {
                lang = navigator.language;
            }

            // Parse offset if provided
            if (offset) {
                offset = offset.split(",").map(function(value) {
                    return parseInt(value);
                });
            }

            // Initialize the accessibility widget
            FIETZ_PERFORMANCE.measure('Widget Initialization', function() {
                initializeAccessibilityWidget({
                    lang: lang,
                    position: position,
                    offset: offset
                });
            });

            // Log performance summary after main initialization
            setTimeout(function() {
                FIETZ_PERFORMANCE.logSummary();

                // Additional performance report after full lazy loading
                if (FIETZ_ACCESSIBILITY_CONFIG.optimizations.lazyLoadNonCritical) {
                    setTimeout(function() {
                        console.group('🚀 **FAW:** Full Performance Report (after Lazy Loading)');
                        FIETZ_PERFORMANCE.logSummary();

                        var totalElements = document.querySelectorAll('*').length;
                        var processedElements = document.querySelectorAll('[data-faw-contrast-processed], [data-faw-landmark-processed], [data-faw-accessible-name-added]').length;

                        console.log('📈 **FAW:** Full Processing Stats:', {
                            'Total DOM Elements': totalElements,
                            'Processed Elements': processedElements,
                            'Processing Rate': ((processedElements / totalElements) * 100).toFixed(1) + '%'
                        });

                        console.groupEnd();
                                         }, 100); // After all lazy-loading timeouts
                }
            }, 50); // Faster initial feedback

    }

    // Initialize after complete document load (including images, stylesheets, etc.)
    if (document.readyState === "complete") {
        // Document already loaded, initialize immediately
        initializeAccessibilitySystem();
    } else {
        // Wait for complete document load
        window.addEventListener("load", initializeAccessibilitySystem, { once: true });
    }

    /**
     * =======================================================================
     * TEXT-TO-SPEECH (READ ALOUD) FEATURE - AUTOMATIC VERSION
     * =======================================================================
     */

    var currentUtterance = null;
    var speechTimeout = null;
    var lastSpokenText = '';
    var speechSettings = {
        rate: 0.9,
        volume: 0.8,
        delay: 500 // Delay before speaking (ms)
    };

    /**
     * Get readable text content from an element
     * Filters out navigation elements and other non-content
     */
    function getReadableText(element) {
        if (!element) return '';

        // Skip certain elements that shouldn't be read
        if (element.matches && element.matches([
            '.faw-menu', '.faw-widget', '.faw-read-aloud-button',
            'script', 'style', 'noscript', 'iframe',
            '[aria-hidden="true"]', '[role="presentation"]'
        ].join(', '))) {
            return '';
        }

        // Get text content, prioritizing aria-label and title
        var text = element.getAttribute('aria-label') ||
                  element.getAttribute('title') ||
                  element.getAttribute('alt') || '';

        // If no aria-label/title, get text content but clean it up
        if (!text) {
            // For form elements, get their labels
            if (element.matches('input, select, textarea, button')) {
                var label = document.querySelector('label[for="' + element.id + '"]');
                if (label) {
                    text = label.textContent.trim();
                } else {
                    text = element.value || element.textContent || element.innerText || '';
                }

                // Add element type context for form elements
                if (element.tagName === 'INPUT') {
                    var inputType = element.type || 'text';
                    if (inputType === 'submit') text += ' Button';
                    else if (inputType === 'checkbox') text += element.checked ? ' aktiviert' : ' deaktiviert';
                    else if (inputType === 'radio') text += element.checked ? ' ausgewählt' : ' nicht ausgewählt';
                    else text += ' Eingabefeld';
                } else if (element.tagName === 'BUTTON') {
                    text += ' Button';
                } else if (element.tagName === 'SELECT') {
                    text += ' Auswahl';
                } else if (element.tagName === 'TEXTAREA') {
                    text += ' Textbereich';
                }
            } else {
                text = element.textContent || element.innerText || '';
            }
        }

        // Clean up the text
        text = text.trim().replace(/\s+/g, ' ');

        // Skip very short or repetitive content
        if (text.length < 3 || text === lastSpokenText) {
            return '';
        }

        // Limit length to avoid very long readings
        if (text.length > 200) {
            text = text.substring(0, 200) + '...';
        }

        return text;
    }

    /**
     * Speak text with improved error handling
     */
    function speakTextAuto(text) {
        if (!text || !'speechSynthesis' in window) return;

        // Stop any current speech
        stopSpeakingAuto();

        setTimeout(function() {
            try {
                currentUtterance = new SpeechSynthesisUtterance(text);

                var configLang = FIETZ_ACCESSIBILITY_CONFIG.textToSpeech.lang ||
                               document.documentElement.lang || 'de-DE';
                currentUtterance.lang = configLang;
                currentUtterance.rate = speechSettings.rate;
                currentUtterance.volume = speechSettings.volume;

                currentUtterance.onend = function() {
                    currentUtterance = null;
                    lastSpokenText = text;
                };

                currentUtterance.onerror = function(event) {
                    if (event.error !== 'interrupted' && FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                        console.warn('⚠️ **FAW:** Speech error:', event.error);
                    }
                    currentUtterance = null;
                };

                if (speechSynthesis.speaking) {
                    speechSynthesis.cancel();
                }

                setTimeout(function() {
                    if (currentUtterance) {
                        speechSynthesis.speak(currentUtterance);
                        if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                            console.log('🔊 **FAW:** Speaking:', text.substring(0, 50) + '...');
                        }
                    }
                }, 50);

            } catch (error) {
                if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.warn('**FAW:** Speech creation error:', error);
                }
                currentUtterance = null;
            }
        }, 30);
    }

    /**
     * Stop current speech
     */
    function stopSpeakingAuto() {
        if (speechTimeout) {
            clearTimeout(speechTimeout);
            speechTimeout = null;
        }

        if (speechSynthesis.speaking || speechSynthesis.pending) {
            speechSynthesis.cancel();
        }

        if (currentUtterance) {
            currentUtterance.onend = null;
            currentUtterance.onerror = null;
            currentUtterance = null;
        }
    }

    /**
     * Handle element focus/hover with debouncing
     */
    function handleElementInteraction(element) {
        if (!getSetting('textToSpeech')) return;

        // Clear any pending speech
        if (speechTimeout) {
            clearTimeout(speechTimeout);
        }

        // Get readable text from the element
        var text = getReadableText(element);
        if (!text) return;

        // Debounce the speech to avoid rapid-fire reading
        speechTimeout = setTimeout(function() {
            speakTextAuto(text);
        }, speechSettings.delay);
    }

    /**
     * Handle mouse leave - stop speech after a delay
     */
    function handleElementLeave() {
        if (speechTimeout) {
            clearTimeout(speechTimeout);
            speechTimeout = null;
        }

        // Stop speech after a short delay (allows moving to child elements)
        setTimeout(function() {
            if (!document.querySelector(':hover')) {
                stopSpeakingAuto();
            }
        }, 100);
    }

    /**
     * Add event listeners for automatic text-to-speech
     */
    function addTextToSpeechListeners() {
        // Prevent duplicate event listeners
        if (window.__faw_tts_listeners_added) {
            return;
        }

        // Elements that should be read when focused/hovered
        var readableElements = [
            'a', 'button', 'input', 'select', 'textarea',
            '[role="button"]', '[role="link"]', '[role="menuitem"]',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'li', '[tabindex]'
        ].join(', ');

        // Add focus listeners (for keyboard navigation)
        document.addEventListener('focusin', function(event) {
            if (event.target.matches && event.target.matches(readableElements)) {
                handleElementInteraction(event.target);
            }
        });

        // Add mouse enter listeners (for mouse users)
        document.addEventListener('mouseover', function(event) {
            if (event.target.matches && event.target.matches(readableElements)) {
                handleElementInteraction(event.target);
            }
        });

        // Add mouse leave listeners
        document.addEventListener('mouseout', function(event) {
            if (event.target.matches && event.target.matches(readableElements)) {
                handleElementLeave();
            }
        });

        // Stop speech when user starts typing
        document.addEventListener('keydown', function(event) {
            // Don't stop for navigation keys
            var navigationKeys = ['Tab', 'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Enter', 'Escape'];
            if (!navigationKeys.includes(event.key)) {
                stopSpeakingAuto();
            }
        });

        // Mark listeners as added
        window.__faw_tts_listeners_added = true;
    }

    /**
     * Remove event listeners for automatic text-to-speech
     */
    function removeTextToSpeechListeners() {
        // Note: We can't easily remove the specific listeners without storing references
        // This is a limitation of this approach, but the functions will check getSetting('textToSpeech')
        stopSpeakingAuto();
    }



    /**
     * Toggle text-to-speech functionality
     */
    function toggleTextToSpeech(enabled) {
        updateSettings({ textToSpeech: enabled });

        if (enabled) {
            // Lazy initialization: only initialize when first activated
            if (!window.__faw_tts_initialized) {
                initTextToSpeech();
                window.__faw_tts_initialized = true;
                if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                    console.log('🎤 **FAW:** Text-to-Speech lazy-loaded on first activation');
                }
            }
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.log('🔊 **FAW:** Automatic text-to-speech enabled');
            }
        } else {
            stopSpeakingAuto();
            if (FIETZ_ACCESSIBILITY_CONFIG.debugMode) {
                console.log('🔇 **FAW:** Automatic text-to-speech disabled');
            }
        }
    }

    /**
     * Initialize automatic text-to-speech (lazy-loaded)
     */
    function initTextToSpeech() {
        // Add event listeners (they check the setting internally)
        addTextToSpeechListeners();
    }
}
)();