<?php
/* -----------------------------------------------------------------------------------------
   MRH 2026 Template – banners.php (Admin Banner-Konfiguration)
   ---------------------------------------------------------------------------------------*/

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

if (is_file(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/lang/banners_' . $_SESSION['language'] . '.php')) {
    require_once(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/lang/banners_' . $_SESSION['language'] . '.php');
} else {
    require_once(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/lang/banners_english.php');
}

echo '
<div id="banner" class="admin_contentbox blog_container" style="display:none;">
  <div class="blog_title">
    <div class="blog_header">' . TEXT_BANNER_GROUP_FOR_TEMPLATE . '</div>
  </div>
  <div class="blogentry">
    <div class="blog_desc">

      <div class="banner_headline">' . TEXT_RECOMMENDED_BANNER_SETTINGS . ' ' . CURRENT_TEMPLATE . '</div>
      <div class="banner_config">
        ' . TEXT_CONFIG_IMAGE_OPTIONS . '<br />
        ' . TEXT_BANNER_IMAGES_WIDTH . ' 1320 Pixel (Fullcontent)<br />
        ' . TEXT_BANNER_IMAGES_HEIGHT . ' 500 Pixel<br />
        ' . TEXT_BANNER_IMAGES_WIDTH_MOBILE . ' 576 Pixel<br />
        ' . TEXT_BANNER_IMAGES_HEIGHT_MOBILE . ' 400 Pixel
      </div>

      <div class="banner_headline">' . TEXT_SLIDER . '</div>
      <table class="banner">
        <tr>
          <td style="width:100%">' . TEXT_BANNER_GROUP . ' <b>' . TEXT_SLIDER . '</b><br />(100%)<br />Desktop: 1320 x 500 Pixel<br />Mobile: 576 x 400 Pixel</td>
        </tr>
      </table>

    </div>
  </div>
</div>';
?>
<style>
.banner_headline { font-weight: bold; margin: 5px 0; font-size: 12px; }
.banner_config { margin: 0 0 15px 0; font-size: 10px; line-height: 16px; }
table.banner { border: 4px solid #ccc; border-collapse: collapse; width: 100%; margin: 0 0 15px 0; font-size: 10px; line-height: 16px; }
table.banner td { border: 4px solid #ccc; background: #f5f5f5; text-align: center; padding: 10px; }
.blog_title { padding: 9px 5px !important; margin-bottom: 10px; border-bottom: 2px solid #198754; }
.blog_header { text-align: center; font-size: 12px; font-weight: bold; }
.blogentry { display: none; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var banner = document.getElementById('banner');
  var target = document.querySelector('.boxCenterLeft') || document.querySelector('.tableConfig');
  if (banner && target) {
    target.parentNode.insertBefore(banner, target);
    banner.style.display = '';
    banner.addEventListener('click', function() {
      var entry = banner.querySelector('.blogentry');
      entry.style.display = entry.style.display === 'none' ? 'block' : 'none';
    });
  }
});
</script>
