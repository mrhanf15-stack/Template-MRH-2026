<?php
  /* --------------------------------------------------------------
   $Id: default.js.php 12435 2019-12-02 09:21:20Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script>
  $(window).on('load',function() {
    $('.show_rating input').change(function () {
      var $radio = $(this);
      $('.show_rating .selected').removeClass('selected');
      $radio.closest('label').addClass('selected');
    });
  });     

  function alert(message, title) {
    title = title || "<?php echo TEXT_LINK_TITLE_INFORMATION; ?>";
    $.alertable.alert('<span id="alertable-title"></span><span id="alertable-content"></span>', { 
      html: true 
    });
    $('#alertable-content').html(message);
    $('#alertable-title').html(title);
  }


  var keyName = "<?php echo basename($PHP_SELF); ?>";
  var scrollPos = localStorage.getItem(keyName);
  if (parseInt(scrollPos) > 0) {
    localStorage.removeItem(keyName);
    $(window).scrollTop(scrollPos);
  }
  $('body').on('submit', '#gift_coupon, #cart_quantity', function() {
    localStorage.setItem(keyName, $(window).scrollTop());
  });


  <?php if (basename($PHP_SELF) != FILENAME_SHOPPING_CART && !strpos($PHP_SELF, 'checkout')) { ?>
    $(function() {
      $('body').on('click', '#toggle_cart', function() {
        $('body').addClass('no_scroll');
        $('.toggle_cart').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        $('.toggle_wishlist').removeClass('active');
        $('.toggle_account').removeClass('active');
        $('.toggle_settings').removeClass('active');
        ac_closing();
        return false;
      });
      <?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_cart'])) {
        unset($_SESSION['new_products_id_in_cart']); ?>
        //$('#offcanvasCart').addClass('show');
        let myOffcanvas = document.getElementById('offcanvasCart');
        let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        bsOffcanvas.show();

        timer = setTimeout(function(){
          bsOffcanvas.hide();

        }, 3000);
      
      <?php } ?>
    });     

    $(function() {
      $('body').on('click', '#toggle_wishlist', function() {
        $('body').addClass('no_scroll');
        $('.toggle_wishlist').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        $('.toggle_cart').removeClass('active');
        $('.toggle_account').removeClass('active');
        $('.toggle_settings').removeClass('active');
        ac_closing();
        return false;
      });
      <?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_wishlist'])) {
        unset($_SESSION['new_products_id_in_wishlist']); ?>
        let myOffcanvas = document.getElementById('offcanvasWishlist');
        let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        bsOffcanvas.show();

        timer = setTimeout(function(){
          bsOffcanvas.hide();

        }, 3000);
      
      <?php } ?>
    });     
  <?php } else {
    unset($_SESSION['new_products_id_in_cart']);
    unset($_SESSION['new_products_id_in_wishlist']);
  } ?>
  
</script>
