<?php
/**
 * blog_posts.php
 *
 * @version 	1.1.0 - 13. Nov 2022
 * @author		Jens Justen <support@web-looks.de>
 * @copyright 	Copyright (c) 2022 Jens Justen
 * @link    	https://www.web-looks.de
 * @package 	blog
 * @since		Version 1.0
 */


// include smarty
include(DIR_FS_BOXES_INC.'smarty_default.php');

// prepare vars
$pID = (!empty($_GET['p']) ? (int)$_GET['p'] : 0);
$limit = (MODULE_BLOG_BOX_POSTS_MAX != '' ? (int)MODULE_BLOG_BOX_POSTS_MAX : 0);

// random mode (show a single random post)
$random = (MODULE_BLOG_BOX_POSTS_SORTING == 'random' && $limit == 1 ? xtc_rand(1, 5) : 0);

// set cache id
$cache_id = md5('lID:'.$_SESSION['language'].
				'|csID:'.$_SESSION['customers_status']['customers_status'].
				'|pID:'.$pID.
				'|random:'.$random);

if ( (!$cache || !$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_blog_posts.html', $cache_id))
	 && (MODULE_BLOG_TEST_MODE != 'true' || $_SESSION['customers_status']['customers_status'] == '0')
) {

	// include needed functions
	require_once (DIR_FS_INC.'get_blog_date_array.inc.php');
	require_once (DIR_FS_INC.'get_blog_post_array.inc.php');

	// order by
	if (MODULE_BLOG_BOX_POSTS_SORTING == 'random') {
		$order_by = " ORDER BY RAND()";
	} else {
		$order_by = " ORDER BY ".MODULE_BLOG_BOX_POSTS_SORTING." ".strtoupper(MODULE_BLOG_BOX_POSTS_SORTING_DIR);
	}

	// get posts
	$post_query = xtDBquery("-- ".__FILE__." (line: ".__LINE__.") 
							 SELECT DISTINCT
									bp.id,
									bp.image,
									bp.image_list,
									bp.date_added,
									bp.date_modified,
									bpd.name, 
									bpd.title,
									bpd.short_description,
									bcd.id as categories_id,
									bcd.name as categories_name
							 FROM ".TABLE_BLOG_POSTS." bp
							 JOIN ".TABLE_BLOG_POSTS_DESCRIPTION." bpd ON (bpd.id = bp.id)
							 LEFT JOIN ".TABLE_BLOG_POSTS_TO_CATEGORIES." bp2c ON (bp2c.posts_id = bp.id)
							 LEFT JOIN ".TABLE_BLOG_CATEGORIES_DESCRIPTION." bcd ON (bcd.id = bp2c.categories_id AND bcd.language_id = ".(int)$_SESSION['languages_id'].")
							 WHERE bp.status = 1
							 AND bpd.language_id = ".(int)$_SESSION['languages_id']."
							 AND trim(bpd.name) != ''
							 GROUP BY bp.id".
							 $order_by.
							 ($limit > 0 ? " LIMIT ".(int)$limit : ""));
	
	$posts = array(); $count_posts = 0;
	if (xtc_db_num_rows($post_query, true) > 0) {
		while ($post = xtc_db_fetch_array($post_query, true)) {
			$post['author'] = STORE_OWNER;
			$post['author_link'] = xtc_href_link(FILENAME_DEFAULT, '', 'NONSSL', false);
			$posts[] = get_blog_post_array($post);		
			$posts[$count_posts]['CURRENT'] = ($post['id'] == $pID ? true : false);
			$count_posts++;
		}
	}

	if (!empty($posts)) $box_smarty->assign('BOX_CONTENT', $posts);
}

// output
if (!$cache) {
	$box_blog = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_blog_posts.html');
} else {
	$box_blog = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_blog_posts.html', $cache_id);
}

$smarty->assign('box_BLOG_POSTS', $box_blog);
