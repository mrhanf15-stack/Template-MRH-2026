<?php
/**
 * blog_category.php
 *
 * @version 	1.1.0 - 13. Nov 2022
 * @author		Jens Justen <support@web-looks.de>
 * @copyright 	Copyright (c) 2022 Jens Justen
 * @link    	https://www.web-looks.de
 * @package 	blog
 * @since		Version 1.1.0
 */


// include smarty
include(DIR_FS_BOXES_INC.'smarty_default.php');

// prepare vars
$cID = (!empty($_GET['c']) ? (int)$_GET['c'] : 0);

// random mode (show a single random category)
$random = xtc_rand(1, 5);

// set cache id
$cache_id = md5('lID:'.$_SESSION['language'].
				'|csID:'.$_SESSION['customers_status']['customers_status'].
				'|cID:'.$cID.
				'|random:'.$random);

if ( (!$cache || !$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_blog_category.html', $cache_id))
	 && (MODULE_BLOG_TEST_MODE != 'true' || $_SESSION['customers_status']['customers_status'] == '0')
) {

	// include needed functions
	require_once (DIR_FS_INC.'get_blog_category_array.inc.php');

	// get categories
	$category_query = xtc_db_query("-- ".__FILE__." (line: ".__LINE__.") 
									 SELECT bc.id,
											bc.image,
											bc.image_list,
											bcd.name, 
											bcd.title
									 FROM ".TABLE_BLOG_CATEGORIES." bc
									 JOIN ".TABLE_BLOG_CATEGORIES_DESCRIPTION." bcd ON (bcd.id = bc.id)
									 WHERE bc.status = 1 
									 AND bcd.language_id = ".(int)$_SESSION['languages_id']."
									 AND trim(bcd.name) != '' 
									 ORDER BY RAND() LIMIT 1"); // no db cache for random query !
	
	$categories = array();
	if (xtc_db_num_rows($category_query) > 0) {
		$category = xtc_db_fetch_array($category_query);
		$categories = get_blog_category_array($category);
		$categories['CURRENT'] = ($category['id'] == $cID ? true : false);
	}

	if (!empty($categories)) $box_smarty->assign('BOX_CONTENT', $categories);
}

// output
if (!$cache) {
	$box_blog = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_blog_category.html');
} else {
	$box_blog = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_blog_category.html', $cache_id);
}

$smarty->assign('box_BLOG_CATEGORY', $box_blog);
