<?php
$rootPath = realpath(__DIR__ . '/../../img/');
$pngFile = $rootPath . '/logo.png';
$webpFile = $rootPath . '/logo.webp';
// Konvertiere Zahlungsarten-Bilder
$paymentPath = realpath(__DIR__ . '/../../img/payment/');
if ($paymentPath && is_dir($paymentPath)) {
    $pngFiles = glob($paymentPath . '/*.png');
    foreach ($pngFiles as $pngFile) {
        $webpFile = str_replace('.png', '.webp', $pngFile);
        
        if (!file_exists($webpFile) || (filemtime($webpFile) < (time() - (30 * 24 * 60 * 60)))) {
            if (function_exists('imagewebp') && function_exists('imagecreatefrompng')) {
                $img = @imagecreatefrompng($pngFile);
                if ($img !== false) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    if (@imagewebp($img, $webpFile, 80)) {
                        imagedestroy($img);
                        echo "<script>console.log('Payment WebP erstellt: " . basename($webpFile) . "');</script>";
                    }
                }
            }
        }
    }
}

// This is for development purposes only
// Konvertiere Versandarten-Bilder 
$shippingPath = realpath(__DIR__ . '/../../img/shipping/');
if ($shippingPath && is_dir($shippingPath)) {
    $jpgFiles = glob($shippingPath . '/*.jpg');
    foreach ($jpgFiles as $jpgFile) {
        $webpFile = str_replace('.jpg', '.webp', $jpgFile);
        
        if (!file_exists($webpFile) || (filemtime($webpFile) < (time() - (30 * 24 * 60 * 60)))) {
            if (function_exists('imagewebp') && function_exists('imagecreatefromjpeg')) {
                $img = @imagecreatefromjpeg($jpgFile);
                if ($img !== false) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    if (@imagewebp($img, $webpFile, 80)) {
                        imagedestroy($img);
                        echo "<script>console.log('Shipping WebP erstellt: " . basename($webpFile) . "');</script>";
                    }
                }
            }
        }
    }
}

if ($rootPath && file_exists($pngFile)) {
    // Check if the WebP file doesn't exist or is older than 30 days
    if (!file_exists($webpFile) || (filemtime($webpFile) < (time() - (30 * 24 * 60 * 60)))) {
        // Convert PNG to WebP
        if (function_exists('imagewebp') && function_exists('imagecreatefrompng')) {
            $img = @imagecreatefrompng($pngFile);
            if ($img !== false) {
                if (@imagewebp($img, $webpFile, 80)) { // Adjust quality (0 - 100) as needed
                    imagedestroy($img);
                    echo "<script>console.log('Logo WebP file created or updated.');</script>";
                } else {
                    echo "<script>console.log('Failed to create Logo WebP file.');</script>";
                }
            } else {
                echo "<script>console.log('Failed to create image from PNG. Possible file or GD library issue.');</script>";
            }
        } else {
            echo "<script>console.log('WebP conversion or PNG support not available on this server.');</script>";
        }
    } else {
        echo "<script>console.log('WebP file exists and is less than 30 days old. No need to convert.');</script>";
    }
} else {
    echo "<script>console.log('Invalid file path for PNG or root directory not found.');</script>";
}
// Konvertiere alle Bilder im /img/ Verzeichnis
$imgPath = realpath(__DIR__ . '/../../img/');
if ($imgPath && is_dir($imgPath)) {
    // Rekursive Funktion zum Durchsuchen aller Unterverzeichnisse
    function convertImagesInDir($dir) {
        $images = array_merge(
            glob($dir . '/*.jpg'),
            glob($dir . '/*.jpeg'),
            glob($dir . '/*.png')
        );
        
        foreach ($images as $image) {
            $webpFile = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image);
            
            // Überprüfe ob WebP existiert oder älter als 30 Tage ist
            if (!file_exists($webpFile) || (filemtime($webpFile) < (time() - (30 * 24 * 60 * 60)))) {
                $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                
                if (function_exists('imagewebp')) {
                    $img = false;
                    
                    if ($extension === 'png' && function_exists('imagecreatefrompng')) {
                        $img = @imagecreatefrompng($image);
                    } elseif (($extension === 'jpg' || $extension === 'jpeg') && function_exists('imagecreatefromjpeg')) {
                        $img = @imagecreatefromjpeg($image);
                    }
                    
                    if ($img !== false) {
                        imagepalettetotruecolor($img);
                        imagealphablending($img, true);
                        imagesavealpha($img, true);
                        
                        if (@imagewebp($img, $webpFile, 80)) {
                            imagedestroy($img);
                            echo "<script>console.log('WebP erstellt: " . basename($webpFile) . "');</script>";
                        }
                    }
                }
            }
        }
        
        // Rekursiv durch Unterverzeichnisse
        $dirs = glob($dir . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            convertImagesInDir($dir);
        }
    }
    
    // Starte Konvertierung
    convertImagesInDir($imgPath);
}

?>








<script>
    
$(document).ready(function(){
    $('.product-carousel').owlCarousel({
    rewind:true,
    loop:false,
    margin:10,
    stageElement: 'ul',
    itemElement: 'li',
    lazyLoad:true,
    dots:false,
    nav:true,
    navigation:true,
    responsiveClass:true,
    responsive:{
        0:{
            items:2
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})
});


$('.field_eye').on('click', '.fa-eye, .fa-eye-slash', function() {
    var pass_name = $(this).data('name');
    var pass_state = $("input[name='"+pass_name+"']").attr('type');
    $("input[name='"+pass_name+"']").attr('type', (pass_state == 'text') ? 'password' : 'text');
    $(this).toggleClass("fa-eye fa-eye-slash");    
  });
  
</script>
<script defer type="text/javascript" charset="utf-8">
var _extends = Object.assign || function(t) {
        for (var e = 1; e < arguments.length; e++) {
            var n = arguments[e];
            for (var a in n) Object.prototype.hasOwnProperty.call(n, a) && (t[a] = n[a])
        }
        return t
    },
    _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    };
! function(t, e) {
    "object" === ("undefined" == typeof exports ? "undefined" : _typeof(exports)) && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : t.LazyLoad = e()
}(this, function() {
    "use strict";

    function a(t, e, n) {
        var a = e._settings;
        !n && o(t) || (g(a.callback_enter, t), -1 < k.indexOf(t.tagName) && (function(n, a) {
            var r = function t(e) {
                    p(e, !0, a), y(n, t, o)
                },
                o = function t(e) {
                    p(e, !1, a), y(n, r, t)
                };
            b(n, r, o)
        }(t, e), h(t, a.class_loading)), function(t, e) {
            var n = e._settings,
                a = t.tagName,
                r = j[a];
            if (r) return r(t, n), e._updateLoadingCount(1), e._elements = l(e._elements, t);
            _(t, n)
        }(t, e), function(t) {
            r(t, "was-processed", "true")
        }(t), g(a.callback_set, t))
    }

    function c(t, e) {
        return t.getAttribute("data-" + e)
    }

    function r(t, e, n) {
        var a = "data-" + e;
        null !== n ? t.setAttribute(a, n) : t.removeAttribute(a)
    }

    function o(t) {
        return "true" === c(t, "was-processed")
    }

    function i(t, e) {
        return r(t, "ll-timeout", e)
    }

    function s(t) {
        return c(t, "ll-timeout")
    }

    function l(t, e) {
        return t.filter(function(t) {
            return t !== e
        })
    }

    function u(t, e) {
        var n, a = new t(e);
        try {
            n = new CustomEvent("LazyLoad::Initialized", {
                detail: {
                    instance: a
                }
            })
        } catch (t) {
            (n = document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized", !1, !1, {
                instance: a
            })
        }
        window.dispatchEvent(n)
    }

    function d(t, e) {
        return e ? t.replace(/\.(jpe?g|png)/gi, ".webp") : t
    }

    function f(t, e, n, a) {
        for (var r, o = 0; r = t.children[o]; o += 1)
            if ("SOURCE" === r.tagName) {
                var i = c(r, n);
                S(r, e, i, a)
            }
    }

    function _(t, e) {
        var n = C && e.to_webp,
            a = c(t, e.data_src),
            r = c(t, e.data_bg);
        if (a) {
            var o = d(a, n);
            t.style.backgroundImage = 'url("' + o + '")'
        }
        if (r) {
            var i = d(r, n);
            t.style.backgroundImage = i
        }
    }

    function h(t, e) {
        z ? t.classList.add(e) : t.className += (t.className ? " " : "") + e
    }

    function g(t, e) {
        t && t(e)
    }

    function v(t, e, n) {
        t.addEventListener(e, n)
    }

    function m(t, e, n) {
        t.removeEventListener(e, n)
    }

    function b(t, e, n) {
        v(t, "load", e), v(t, "loadeddata", e), v(t, "error", n)
    }

    function y(t, e, n) {
        m(t, "load", e), m(t, "loadeddata", e), m(t, "error", n)
    }

    function p(t, e, n) {
        var a = n._settings,
            r = e ? a.class_loaded : a.class_error,
            o = e ? a.callback_load : a.callback_error,
            i = t.target;
        (function(t, e) {
            z ? t.classList.remove(e) : t.className = t.className.replace(new RegExp("(^|\\s+)" + e + "(\\s+|$)"), " ").replace(/^\s+/, "").replace(/\s+$/, "")
        })(i, a.class_loading), h(i, r), g(o, i), n._updateLoadingCount(-1)
    }

    function w(t, e, n) {
        a(t, n), e.unobserve(t)
    }

    function E(t) {
        var e = s(t);
        e && (clearTimeout(e), i(t, null))
    }

    function L(t) {
        return t.isIntersecting || 0 < t.intersectionRatio
    }

    function t(t, e) {
        this._settings = function(t) {
            return _extends({}, n, t)
        }(t), this._setObserver(), this._loadingCount = 0, this.update(e)
    }
    var e, n = {
            elements_selector: "img",
            container: document,
            threshold: 300,
            thresholds: null,
            data_src: "src",
            data_srcset: "srcset",
            data_sizes: "sizes",
            data_bg: "bg",
            class_loading: "loading",
            class_loaded: "loaded",
            class_error: "error",
            load_delay: 0,
            callback_load: null,
            callback_error: null,
            callback_set: null,
            callback_enter: null,
            callback_finish: null,
            to_webp: !1
        },
        I = "undefined" != typeof window,
        A = I && !("onscroll" in window) || /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),
        O = I && "IntersectionObserver" in window,
        z = I && "classList" in document.createElement("p"),
        C = I && (!(!(e = document.createElement("canvas")).getContext || !e.getContext("2d")) && 0 === e.toDataURL("image/webp").indexOf("data:image/webp")),
        S = function(t, e, n, a) {
            n && t.setAttribute(e, d(n, a))
        },
        j = {
            IMG: function(t, e) {
                var n = C && e.to_webp,
                    a = e.data_srcset,
                    r = t.parentNode;
                r && "PICTURE" === r.tagName && f(r, "srcset", a, n);
                var o = c(t, e.data_sizes);
                S(t, "sizes", o);
                var i = c(t, a);
                S(t, "srcset", i, n);
                var s = c(t, e.data_src);
                S(t, "src", s, n)
            },
            IFRAME: function(t, e) {
                var n = c(t, e.data_src);
                S(t, "src", n)
            },
            VIDEO: function(t, e) {
                var n = e.data_src,
                    a = c(t, n);
                f(t, "src", n), S(t, "src", a), t.load()
            }
        },
        k = ["IMG", "IFRAME", "VIDEO"];
    return t.prototype = {
        _manageIntersection: function(t) {
            var e = this._observer,
                n = this._settings.load_delay,
                a = t.target;
            n ? L(t) ? function(t, e, n) {
                var a = n._settings.load_delay,
                    r = s(t);
                r || (r = setTimeout(function() {
                    w(t, e, n), E(t)
                }, a), i(t, r))
            }(a, e, this) : E(a) : L(t) && w(a, e, this)
        },
        _onIntersection: function(t) {
            t.forEach(this._manageIntersection.bind(this))
        },
        _setObserver: function() {
            O && (this._observer = new IntersectionObserver(this._onIntersection.bind(this), function(t) {
                return {
                    root: t.container === document ? null : t.container,
                    rootMargin: t.thresholds || t.threshold + "px"
                }
            }(this._settings)))
        },
        _updateLoadingCount: function(t) {
            this._loadingCount += t, 0 === this._elements.length && 0 === this._loadingCount && g(this._settings.callback_finish)
        },
        update: function(t) {
            var e = this,
                n = this._settings,
                a = t || n.container.querySelectorAll(n.elements_selector);
            this._elements = function(t) {
                return t.filter(function(t) {
                    return !o(t)
                })
            }(Array.prototype.slice.call(a)), !A && this._observer ? this._elements.forEach(function(t) {
                e._observer.observe(t)
            }) : this.loadAll()
        },
        destroy: function() {
            var e = this;
            this._observer && (this._elements.forEach(function(t) {
                e._observer.unobserve(t)
            }), this._observer = null), this._elements = null, this._settings = null
        },
        load: function(t, e) {
            a(t, this, e)
        },
        loadAll: function() {
            var e = this;
            this._elements.forEach(function(t) {
                e.load(t)
            })
        }
    }, I && function(t, e) {
        if (e)
            if (e.length)
                for (var n, a = 0; n = e[a]; a += 1) u(t, n);
            else u(t, e)
    }(t, window.lazyLoadOptions), t
});
var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/),
    iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
if (isSafari || iOS) jQuery("img").each(function() {
    jQuery(this).attr("src", jQuery(this).data("src")), jQuery(this).removeAttr("data-src"), jQuery(this).removeClass("lazy")
}), jQuery("*[data-bg]").each(function() {
    jQuery(this).css("background-image", jQuery(this).data("bg")), jQuery(this).removeAttr("data-bg")
});
else var myLazyLoad = new LazyLoad({
    elements_selector: ".lazy"
});

document.addEventListener(
        "DOMContentLoaded", () => {
   
    var e = $("#main-header").height();
    $(window).scroll(function() {
        scrollPos = $(window).scrollTop(), scrollPos >= e ? ($("#main-header").addClass("fixed"), $(".page-wrapper").css("padding-top", e)) : ($("#main-header").removeClass("fixed"), $(".page-wrapper").removeAttr("style"))
    }), $(".toggle-mega").on("click", function(e) {
        $("#main-header div.navibar").toggleClass("toggled")
    })
}), document.addEventListener(
        "DOMContentLoaded", () => {
    $(".nav-cat-img").each(function(e, a) {
        $(this).appendTo($(this).siblings("ul"))
    })
});

/* MRH2026: mmenu komplett entfernt - Mega-Menu jetzt Vanilla JS in mrh-core.js.php
   Mobile Offcanvas wird durch MRH.MobileMenu ersetzt */


/* MRH2026: Alte mainnavi jQuery-Logik entfernt - wird jetzt in MRH.MegaMenu.init() gemacht */
window.addEventListener("load", function() {
    $("#admin").length && $(".navibar").css("top", "36px"), $("#toggle_login").click(function() {
        return $(".toggle_login").slideToggle("slow"), ac_closing(), !1
    });
});

function setCookie(e, t, i) {
    var n = new Date;
    n.setTime(n.getTime() + 24 * i * 60 * 60 * 1e3);
    var o = "expires=" + n.toUTCString();
    document.cookie = e + "=" + t + ";" + o + ";path=/"
}

function getCookie(e) {
    for (var t = e + "=", i = document.cookie.split(";"), n = 0; n < i.length; n++) {
        for (var o = i[n];
            " " == o.charAt(0);) o = o.substring(1);
        if (0 == o.indexOf(t)) return o.substring(t.length, o.length)
    }
    return ""
}
$(".radioswitch label").on("click", function() {
    var e = $(this).parent(".radioswitch").find("span");
    $(this).hasClass("right") ? $(e).addClass("right") : $(e).removeClass("right")
}) || jQuery("#announcement-bar .close").click(function() {
    jQuery("#announcement-bar").fadeOut(), setCookie("weggeklickt", 1, 1)
});

</script>

<script defer>
$( "a.iframe, .as-oil-imprint-links a" ).click(function(openpopupcontent) {
	openpopupcontent.preventDefault();
	let popupContentURL = $(this).attr('href');
	$('#popupContent').modal('show');
	$("#popupContent iframe").attr("src", popupContentURL );
});
if (getCookie("weggeklickt") == 1){
    jQuery("#announcement-bar").hide();
}
</script>

<?php if (strstr($PHP_SELF, FILENAME_PRODUCT_INFO )) { ?>
<script src="<?php echo DIR_WS_BASE . DIR_TMPL_JS; ?>unitegallery/js/unitegallery.min.js"></script>
<script src="<?php echo DIR_WS_BASE . DIR_TMPL_JS; ?>unitegallery/themes/compact/ug-theme-compact.js"></script>

    <script type="text/javascript">
        jQuery("#gallery1").unitegallery({
        slider_enable_arrows:true,
        slider_enable_progress_indicator:false,
        slider_enable_play_button:false,
        slider_control_zoom:true,
        slider_enable_fullscreen_button:true,
        slider_enable_zoom_panel:true,
        slider_enable_text_panel:false,
        strippanel_enable_handle:false,
        gridpanel_enable_handle:true,
        slider_zoom_max_ratio: 1,
        slider_scale_mode: "fit",
        thumb_color_overlay_effect: false,
        thumb_image_overlay_effect: false,
        });
    </script>


<script>
    // extend alt attributes for accessibility reasons - Unitegallery somehow doesnt :/
    document.addEventListener("DOMContentLoaded", function() {
    const gallery = document.querySelector("#gallery1");
    const images = gallery.querySelectorAll("img");

    // Create a map to store the src and alt attributes
    const srcAltMap = new Map();

    // First pass to collect all src-alt pairs
    images.forEach(img => {
        const src = img.getAttribute("src");
        const alt = img.getAttribute("alt");

        if (alt) {
            srcAltMap.set(src, alt);
        }
    });

    // Second pass to set the missing alt attributes
    images.forEach(img => {
        const src = img.getAttribute("src");
        if (!img.hasAttribute("alt") && srcAltMap.has(src)) {
            img.setAttribute("alt", srcAltMap.get(src));
        }
    });
});
</script>

<?php } ?>