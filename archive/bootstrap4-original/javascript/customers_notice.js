/**
 * Customers notice
 * 
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2014, Timo Paul Dienstleistungen
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 *            GNU General Public License (GPL), Version 2.0
 */
 
;var customersNoticeModal = (function() {
  var 
  method = {},
  $useCookie = true,
  $cookieName = 'customersNoticeClosed',
  $overlay,
  $modal,
  $content,
  $close,
  $elementIds = {
    'overlay':  'customers-notice-overlay',
    'modal':    'customers-notice-modal',
    'content':  'customers-notice-content',
    'close':    'customers-notice-close'
  };
  
  /* Center the modal in the viewport */
  method.center = function () {
    var top, left;

    top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
    left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;

    $modal.css({
      top:top + $(window).scrollTop(), 
      left:left + $(window).scrollLeft()
    });
  };

  /* Open the modal */
  method.open = function (settings) {

    var display = true;
    if ($useCookie) {
      var cookieValue = document.cookie.match('(^|;) ?' + $cookieName + '=([^;]*)(;|$)');
      if (cookieValue && '1' == cookieValue[2]) {
        display = false;
      }
    }

    if (display) {
      $modal.css({
        width: settings.width || 'auto', 
        height: settings.height || 'auto'
      });

      method.center();
      $(window).bind('resize.modal', method.center);
      $modal.show();
      $overlay.show();
    }
  };

  /* Close the modal */
  method.close = function () {
    $modal.hide();
    $overlay.hide();
    $(window).unbind('resize.modal');
    
    /* set cookie */
    if ($useCookie) {
      var expires = new Date();
      expires.setTime(expires.getTime() + (30 * 24 * 60 * 60 * 1000)); /* 30 days */
      document.cookie = $cookieName + '=1;expires=' + expires.toUTCString();
    }
  };

  /* Generate the HTML and add it to the document */
  $overlay = $('#' + $elementIds.overlay);
  $modal = $('#' + $elementIds.modal);
  $content = $('#' + $elementIds.content);
  $close = $('#' + $elementIds.close);

  $modal.hide();
  $overlay.hide();
  $modal.append($content, $close);

  $(document).ready(function(){
    $('body').append($overlay, $modal);						
  });

  $close.click(function(e){
    e.preventDefault();
    method.close();
  });
  $overlay.click(function(e){
    e.preventDefault();
    method.close();
  });

  return method;
}());

/* Wait until the DOM has loaded before querying the document */
$(document).ready(function() {
  $(window).on('scroll', function() {
    if ($(window).scrollTop() > 100) {
      customersNoticeModal.open({});
    }
  });
});
