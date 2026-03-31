<?php
function smarty_modifier_inserttags( $string ) {
	global $main;
	$mainModules = new mainModules();
	
	$return = $string;
	$tags = preg_split( '~{{([a-zA-Z0-9\x80-\xFF][^{}]*)}}~', $string, -1, PREG_SPLIT_DELIM_CAPTURE );
	$clearDomain = false;
	$domain = (ENABLE_SSL?HTTPS_SERVER:HTTP_SERVER) . DIR_WS_CATALOG;
	
	if ( is_array( $tags ) && count( $tags ) > 2 )
	{
		$arrUrlFlags = array( '?', '#' );
		$arrLinkFlags = array( 'class', 'id', 'style' );
		$arrImgFlags = array( 'class', 'id', 'style', 'height', 'width' );
		$arrListFlags = array( 'addbefore', 'addafter', 'replace', 'limit' , 'orderby', 'view' );
	
		for ( $i=0; $i < count( $tags ); $i+=2 )
		{
			$strTag = ( isset( $tags[$i+1] ) )?$tags[$i+1]:'';
			$flags = explode( '|', $strTag );
			$tag = array_shift( $flags );
			$flag = array_shift( $flags );
			$elements = explode( '::', $tag );
			
			//$tagRaw = $tag . ( ( $flag != '' )?'|' . $flag:'' );
			$tagRaw = $strTag;
			$tagCode = '<!-- ' . TEXT_NO_INSERTTAG . ' -->';
			
			if ( is_array( $elements ) && count( $elements ) == 2 )
			{
				$tagRaw = '{{' . $tagRaw . '}}';
				
				switch ( strtolower( $elements[0] ) )
				{
					case 'date':
						$tagCode = Date( $elements[1] );
						break;
						
					case 'env_tax':
						$tagCode = xtc_get_tax_rate( (int)$elements[1] );
						break;
					
					case 'env_taxdesc':
						$tagCode = xtc_get_tax_description( (int)$elements[1] );
						break;
						
					case 'products':
					case 'products_keywords':
					case 'products_categorie':
					case 'products_category':
					case 'products_categories':
						$smarty = new Smarty;
						$tagCode = '';
						$products_arr = $table_join = '';
						$flagArr = array( $flag );
						if ( $flags != '' ) $flagArr = array_merge( $flagArr, $flags );
						
						switch ( strtolower( $elements[0] ) )
						{
							case 'products':
							
								// create array with products ids
								$products_array = array();
								$products_array_tmp = explode(',', $elements[1]);
								for ($j = 0, $n2 = count($products_array_tmp); $j < $n2; $j++) {
									$products_array_tmp[$j] = (int)$products_array_tmp[$j];
									if ($products_array_tmp[$j] > 0) $products_array[] = $products_array_tmp[$j];
								}								
							
								if (!empty($products_array)) {
									if (count($products_array) > 1) {
										$products_arr = " AND p.products_id IN (".implode(',', $products_array).")";
									} else {
										$products_arr = " AND p.products_id = ".(int)$products_array[0];
									}
								}
								break;
								
							case 'products_keywords':
								$keywordsArr = explode( ',', $elements[1] );
								$keywordSql = '';
								for ($j = 0, $n2 = count($keywordsArr); $j < $n2; $j++) {
									$keyword = trim($keywordsArr[$j]);
									
									if (!empty($keyword)) {
										if (!empty($keywordSql)) $keywordSql .= " OR ";
										$keywordSql .= "pd.products_name LIKE '%".$keyword."%' 
														OR pd.products_heading_title LIKE '%".$keyword."%' 
														OR pd.products_description LIKE '%".$keyword."%' 
														OR pd.products_short_description LIKE '%".$keyword."%' 
														OR pd.products_keywords LIKE '%".$keyword."%' 
														OR pd.products_meta_title LIKE '%".$keyword."%' 
														OR pd.products_meta_description LIKE '%".$keyword."%'
														OR pd.products_meta_keywords LIKE '%".$keyword."%'
														OR pd.products_order_description LIKE '%".$keyword."%'";
									}
								}
								
								if (!empty($keywordSql)) {
									$products_arr = " AND (".$keywordSql.")";
								}
								break;
								
							case 'products_categorie':
							case 'products_category':
								$group_check = GROUP_CHECK == 'true' ? " AND c.group_permission_" . (int)$_SESSION['customers_status']['customers_status_id']  ." = '1'" : "";
						
								$category_query = xtDBquery("SELECT c.categories_image, 
																	cd.categories_name,
					 											    cd.categories_heading_title 
		                       								 FROM ".TABLE_CATEGORIES." c
		                       								 JOIN ".TABLE_CATEGORIES_DESCRIPTION." cd ON (cd.categories_id = c.categories_id)
		                      				WHERE c.categories_status = '1'
		                        			AND c.categories_id = ".(int)$elements[1]."
											AND cd.language_id = ".(int) $_SESSION['languages_id']."
		                                	AND trim(cd.categories_name) != ''".
											$group_check);

								if ( xtc_db_num_rows( $category_query, true ) > 0 ) 
								{
									$category = xtc_db_fetch_array( $category_query, true );
									
									$categorieyName = $category['categories_name'];
									if ( $category['categories_heading_title'] != '' ) $categorieyName = $category['categories_heading_title'];
									$tagCode = '<h2>' . $categorieyName . '</h2>';
									
									$table_join = " JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c ON (p2c.products_id = p.products_id)";
									$products_arr = " AND p2c.categories_id = ".(int)$elements[1];
								}
								break;

							case 'products_categories':

								// create array with categories ids
								$categories_array = array();
								$categories_array_tmp = explode(',', $elements[1]);
								for ($j = 0, $n2 = count($categories_array_tmp); $j < $n2; $j++) {
									$categories_array_tmp[$j] = (int)$categories_array_tmp[$j];
									if ($categories_array_tmp[$j] > 0) $categories_array[] = $categories_array_tmp[$j];
								}								
							
								if (!empty($categories_array)) {
									$table_join = " JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." p2c ON (p2c.products_id = p.products_id)";
									if (count($categories_array) > 1) {
										$products_arr = " AND p2c.categories_id IN (".implode(',', $categories_array).")";
									} else {
										$products_arr = " AND p2c.categories_id = ".(int)$categories_array[0];
									}
								}
								break;
						}
						
						if (!empty($products_arr)) {
							$group_check = GROUP_CHECK == 'true' ? " AND p.group_permission_".(int)$_SESSION['customers_status']['customers_status_id']." = 1" : "";
							$sqlLimit = '';
							$sqlOrderBy = '';
							$listing_view = 'grid';
							
							if ( is_array( $flagArr ) && count( $flagArr ) > 0 )
							{
								foreach ( $flagArr as $flag )
								{
									$flagElements = explode( ':', $flag );
									if (  strposa( $flagElements[0], $arrListFlags ) == 0 )
									{
										switch ( strtolower( $flagElements[0] ) )
										{
											case 'limit':
												$sqlLimit = ' LIMIT 0,' . $flagElements[1];
												break;
											case 'orderby':
												$sqlOrderBy = ' ORDER BY ' . $flagElements[1];
												break;
											case 'view':
												$listing_view = ($flagElements[1] == 'list' ? 'list' : 'grid');
												break;
										}
									}
								}
							}

							$products_sql = "SELECT ".ADD_SELECT_DEFAULT."
			                           p.products_id,
			                           p.products_ean,
			                           p.products_quantity,
			                           p.products_shippingtime,
			                           p.products_model,
			                           p.products_image,
			                           p.products_price,
			                           p.products_discount_allowed,
			                           p.products_weight,
			                           p.products_tax_class_id,
			                           p.manufacturers_id,
			                           p.products_fsk18,
			                           p.products_vpe,
			                           p.products_vpe_status,
			                           p.products_vpe_value,
			                           pd.products_name,
			                           pd.products_heading_title,
			                           pd.products_description,
			                           pd.products_short_description
			                      FROM ".TABLE_PRODUCTS." p
			                      JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (pd.products_id = p.products_id)".
								  $table_join." 
			                     WHERE p.products_status = 1 
								 AND pd.language_id = ".(int) $_SESSION['languages_id']." 
								 AND trim(pd.products_name) != '' ".
								 $group_check.
								 $products_arr.
								 $sqlOrderBy.$sqlLimit;
				 
							$products_query = xtDBquery($products_sql);
			
			  			if ( xtc_db_num_rows( $products_query, true ) > 0 ) 
							{
								$module_content = array();
								$product = new Product();
								
								while ($productsData = xtc_db_fetch_array($products_query, true)) 
								{
							    if ( is_array( $flagArr ) && count( $flagArr ) > 0 )
									{
										foreach ( $flagArr as $flag )
										{
											$flagElements = explode( ':', $flag );
											if (  strposa( $flagElements[0], $arrListFlags ) == 0 )
											{
												switch ( strtolower( $flagElements[0] ) )
												{
													case 'addbefore':
														$productsData['products_name'] = $flagElements[1] . $productsData['products_name'];
														break;
													case 'addafter':
														$productsData['products_name'] .= $flagElements[1];
														break;
													case 'replace':
														$tagCode = str_replace( $flagElements[1], $flagElements[2], $tagCode );
														$productsData['products_name'] = str_replace( $flagElements[1], $flagElements[2], $productsData['products_name'] );
														break;
												}
											}
										}
									}
									$module_content[] =  $product->buildDataArray($productsData);
							  }
								$smarty->assign('language', $_SESSION['language']);
								$smarty->assign('module_content', $module_content);
								$smarty->assign('listing_view', $listing_view);
								
								// load language files from product_listing_v1.html
								$smarty->config_load($_SESSION['language'].'/lang_'.$_SESSION['language'].'.conf', 'index');
								$smarty->config_load('lang_'.$_SESSION['language'].'.custom');
								$smarty->config_load('lang_'.$_SESSION['language'].'.section', 'index');
								
								// fetch template
								$tagCode .= $smarty->fetch(CURRENT_TEMPLATE.'/module/includes/product_listing_include.html');
							}
							else
							{
								$tagCode .= '<!-- ' . TEXT_NO_PRODUCTS_DATA . ' -->';
							}
						}
						
						break;
						
					case 'product':
					case 'product_url':
					case 'product_title':
					case 'product_name':
					case 'product_img':
					case 'product_thumb':
						$group_check = GROUP_CHECK == 'true' ? " AND p.group_permission_" . (int)$_SESSION['customers_status']['customers_status_id'] . " = 1" : "";
						
						$product_query = xtDBquery("SELECT p.products_model,
																	p.products_manufacturers_model,
																	p.products_ean,
																	p.products_image,
																	p.products_status,
                            			pd.products_name,
                            			p.manufacturers_id
                       				FROM " . TABLE_PRODUCTS . " p
                       				JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (pd.products_id = p.products_id)
                      				WHERE p.products_id = ".(int)$elements[1]." 
									AND pd.language_id = ".(int)$_SESSION['languages_id']."
                               		AND trim(pd.products_name) != ''".
									$group_check);

					  if ( xtc_db_num_rows( $product_query, true ) > 0 ) 
						{
							$productsData = xtc_db_fetch_array( $product_query, true );
							
							$productsLinkTitle = '';
							if ( $productsData['manufacturers_id'] > 0 )
							{
								$manu_sql = "SELECT manufacturers_name
                       				FROM " . TABLE_MANUFACTURERS . " 
															WHERE manufacturers_id = ".$productsData['manufacturers_id'];
								$manu_query = xtDBquery($manu_sql);
					    	if ( xtc_db_num_rows( $manu_query, true ) > 0 ) 
								{
									$manuData = xtc_db_fetch_array( $manu_query, true );
									if ( $manuData['manufacturers_name'] != '' ) $productsLinkTitle = $manuData['manufacturers_name'] . ' ';
								}
							}
							$productsLinkTitle .= htmlentities($productsData['products_name']) . ( ( $productsData['products_manufacturers_model'] != '' || $productsData['products_model'] != '' ) ? ' ' . TEXT_ARTIKELNR . ' ' : '' ) . ( ( $productsData['products_manufacturers_model'] != '' )?$productsData['products_manufacturers_model']:( ( $productsData['products_model'] != '' )?$productsData['products_model']:'' ) );
							$productsName = ( ( $flag != '' && strpos( $flag, 'linktext' ) !== false )?str_replace( 'linktext:', '', $flag ):$productsData['products_name'] );
							$productsLink = xtc_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $elements[1] ) . ( ( $flag != '' && strposa( $flag, $arrUrlFlags ) != '' && strposa( $flag, $arrUrlFlags ) == 0 )?$flag:'' );
							if ( $clearDomain ) $productsLink = '/' . str_replace( $domain, '', $productsLink );
							
							switch ( strtolower( $elements[0] ) )
							{
								case 'product':
									if ( $productsData['products_status'] == '1' )
										$tagCode = '<a' . ( ( $flag != '' && strposa( $flag, $arrLinkFlags ) == 0 )?' ' . $flag:'' ) . ' href="' . $productsLink . '" title="' . $productsLinkTitle . '">' . $productsName . '</a>';
									else
										$tagCode = $productsName;
									break;
								case 'product_url':
									$tagCode = $productsLink;
									break;
								case 'product_title':
									$tagCode = $productsLinkTitle;
									break;
								case 'product_name':
									$tagCode = $productsData['products_name'];
									break;
								case 'product_img':
								case 'product_thumb':
									if ( $productsData['products_image'] != '' ) 
									{
										$dir = DIR_WS_THUMBNAIL_IMAGES;
										if ( strtolower( $elements[0] ) == 'product_img' ) $dir = DIR_WS_INFO_IMAGES;
										$img = $dir . $productsData['products_image'];
										
									$tagCode = '<img' 
    . ( ( $flag != '' && strposa( $flag, $arrImgFlags ) == 0 ) ? ' ' . $flag : '' ) 
    . ' src="/' . $mainModules->getImage( $img, $dir, PRODUCT_IMAGE_SHOW_NO_IMAGE, 'noimage.gif', $productsData['products_image'] ) 
    . '" alt="' . $productsLinkTitle . '" title="' . $productsLinkTitle 
    . '" class="img-fluid float-right mb-2 ml-2 rounded">';

									}
									break;
							}
						}
						else
						{
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- ' . TEXT_NO_PRODUCTS_DATA . ' -->';
						}
						break;
					
					case 'categorie':
					case 'categorie_url':
					case 'categorie_title':
					case 'categorie_name':
					case 'categorie_img':
					case 'category':
					case 'category_url':
					case 'category_title':
					case 'category_name':
					case 'category_img':
						$group_check = GROUP_CHECK == 'true' ? " AND c.group_permission_" . (int)$_SESSION['customers_status']['customers_status_id']  ." = 1" : "";
						
						$categorie_query = xtDBquery("SELECT cd.categories_name,
																	 cd.categories_heading_title,
                             			 c.categories_image,
																	 c.categories_status
										 
                       				FROM " . TABLE_CATEGORIES . " c
                       				JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON (cd.categories_id = c.categories_id)                               			
                      				WHERE c.categories_id = " . (int)$elements[1] ." 
									AND cd.language_id = ".(int)$_SESSION['languages_id']."
                                 	AND trim(cd.categories_name) != ''".
									$group_check);

					    if ( xtc_db_num_rows( $categorie_query, true ) > 0 ) 
						{
							$categoriesData = xtc_db_fetch_array( $categorie_query, true );
							
							$categoriesLinkTitle = $categoriesData['categories_name'];
							if ( $categoriesData['categories_heading_title'] != '' ) $categoriesLinkTitle = $categoriesData['categories_heading_title'];
							$categoriesName = ( ( $flag != '' && strpos( $flag, 'linktext' ) !== false )?str_replace( 'linktext:', '', $flag ):$categoriesData['categories_name'] );
							$categoriesLink = xtc_href_link( FILENAME_DEFAULT, xtc_category_link( $elements[1], $categoriesData['categories_name'] ) ) . ( ( $flag != '' && strposa( $flag, $arrUrlFlags ) == 0 )?$flag:'' );
							if ( $clearDomain ) $categoriesLink = '/' . str_replace( $domain, '', $categoriesLink );
							
							switch ( strtolower( $elements[0] ) )
							{
								case 'categorie':
								case 'category':
									if ( $categoriesData['categories_status'] == '1' )
										$tagCode = '<a' . ( ( $flag != '' && strposa( $flag, $arrLinkFlags ) == 0 )?' ' . $flag:'' ) . ' href="' . $categoriesLink . '" title="' . $categoriesLinkTitle . '">' . $categoriesName . '</a>';
									else
										$tagCode = $categoriesName;
									break;
								case 'categorie_url':
								case 'category_url':
									$tagCode = $categoriesLink;
									break;
								case 'categorie_title':
								case 'category_title':
									$tagCode = $categoriesLinkTitle;
									break;
								case 'categorie_name':
								case 'category_name':
									$tagCode = $categoriesData['categories_name'];
									break;
								case 'categorie_img':
								case 'category_img':
									$dir = DIR_WS_IMAGES . 'categories/';
									$img = $dir . $categoriesData['categories_image'];
									$tagCode = '<img' . ( ( $flag != '' && strposa( $flag, $arrImgFlags ) == 0 )?' ' . $flag:'' ) . ' src="/' . $mainModules->getImage( $img, $dir, CATEGORIES_IMAGE_SHOW_NO_IMAGE, 'noimage.gif', $categoriesData['categories_image'] ) . '" alt="'.$categoriesLinkTitle.'" title="'.$categoriesLinkTitle.'">';
									break;
							}
						}
						else
						{
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- ' . TEXT_NO_CATEGORIES_DATA . ' -->';
						}
						break;
					
					case 'manufacturer':
					case 'manufacturer_url':
					case 'manufacturer_title':
					case 'manufacturer_name':
					case 'manufacturer_img':
						$manufacturer_sql = "SELECT m.manufacturers_name,
										 mi.manufacturers_meta_title,
                             			 m.manufacturers_image
                       				FROM " . TABLE_MANUFACTURERS . " m
                       				JOIN " . TABLE_MANUFACTURERS_INFO . " mi
                            			ON mi.manufacturers_id = m.manufacturers_id
                               			AND mi.languages_id = '".(int)$_SESSION['languages_id']."'
                                 		AND trim(m.manufacturers_name) != ''
                      				WHERE m.manufacturers_id = '" . (int)$elements[1] ."'
                            			";

					  	$manufacturer_query = xtDBquery($manufacturer_sql);
					
					    if ( xtc_db_num_rows( $manufacturer_query, true ) > 0 ) 
						{
							$manufacturersData = xtc_db_fetch_array( $manufacturer_query, true );
							
							$manufacturersLinkTitle = $manufacturersData['manufacturers_name'];
							if ( $manufacturersData['manufacturers_meta_title'] != '' ) $manufacturersLinkTitle = $manufacturersData['manufacturers_meta_title'];
							$manufacturersLink = xtc_href_link( FILENAME_DEFAULT, xtc_manufacturer_link( $elements[1], $manufacturersData['manufacturers_name'] ) ) . ( ( $flag != '' && strposa( $flag, $arrUrlFlags ) == 0 )?$flag:'' );
							if ( $clearDomain ) $manufacturersLink = '/' . str_replace( $domain, '', $manufacturersLink );
							
							switch ( strtolower( $elements[0] ) )
							{
								case 'manufacturer':
									$tagCode = '<a' . ( ( $flag != '' && strposa( $flag, $arrLinkFlags ) == 0 )?' ' . $flag:'' ) . ' href="' . $manufacturersLink . '" title="' . $manufacturersLinkTitle . '">' . $manufacturersData['manufacturers_name'] . '</a>';
									break;
								case 'manufacturer_url':
									$tagCode = $manufacturersLink;
									break;
								case 'manufacturer_title':
									$tagCode = $manufacturersLinkTitle;
									break;
								case 'manufacturer_name':
									$tagCode = $manufacturersData['manufacturers_name'];
									break;
								case 'manufacturer_img':
									$dir = DIR_WS_IMAGES. 'manufacturers/';
									$img = $dir . $manufacturersData['manufacturers_image'];
									$tagCode = '<img' . ( ( $flag != '' && strposa( $flag, $arrImgFlags ) == 0 )?' ' . $flag:'' ) . ' src="/' . $mainModules->getImage( $img, $dir . 'manufacturers/', MANUFACTURER_IMAGE_SHOW_NO_IMAGE, 'manufacturers/noimage.gif', str_replace( $dir, '', $manufacturersData['manufacturers_image'] ) ) . '" alt="'.$manufacturersLinkTitle.'" title="'.$manufacturersLinkTitle.'">';
									break;
							}
						}
						else
						{
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- ' . TEXT_NO_MANUFACTURER_DATA . ' -->';
						}
						break;
					
					case 'content':
					case 'content_url':
					case 'content_title':
					case 'content_name':
					case 'content_text':
						$group_check = GROUP_CHECK == 'true' ? "c.group_ids LIKE '%c_" . (int)$_SESSION['customers_status']['customers_status_id'] . "_group%' AND " : "";
							
						$content_sql = "SELECT c.content_title,
										 c.content_heading,
										 c.content_text,
										 c.content_active
                       				FROM " . TABLE_CONTENT_MANAGER . " c
                      				WHERE " . $group_check . "c.content_group = '" . (int)$elements[1] ."'
										AND c.languages_id = '".(int)$_SESSION['languages_id']."'
                                 		AND trim(c.content_title) != ''
                            			";

					  $content_query = xtDBquery($content_sql);
						
					  if ( xtc_db_num_rows( $content_query, true ) > 0 ) 
						{
							$contentData = xtc_db_fetch_array( $content_query, true );
							
							$contentLinkTitle = $contentData['content_title'];
							if ( $contentData['content_heading'] != '' ) $contentLinkTitle = $contentData['content_heading'];
							$contentLinkText = ( ( $flag != '' && strpos( $flag, 'linktext' ) !== false )?str_replace( 'linktext:', '', $flag ):$contentData['content_title'] );
							$contentLink = xtc_href_link( FILENAME_CONTENT, 'coID=' . $elements[1] ) . ( ( $flag != '' && strposa( $flag, $arrUrlFlags ) != '' && strposa( $flag, $arrUrlFlags ) == 0 )?$flag:'' );
							if ( $clearDomain ) $contentLink = '/' . str_replace( $domain, '', $contentLink );
							
							switch ( strtolower( $elements[0] ) )
							{
								case 'content':
									if ( $contentData['content_active'] == '1' )
										$tagCode = '<a' . ( ( $flag != '' && strposa( $flag, $arrLinkFlags ) == 0 )?' ' . $flag:'' ) . ' href="' . $contentLink . '" title="' . $contentLinkTitle . '">' . $contentLinkText . '</a>';
									else
										$tagCode = $contentLinkText;
									break;
								case 'content_url':
									$tagCode = $contentLink;
									break;
								case 'content_title':
									$tagCode = $contentLinkTitle;
									break;
								case 'content_name':
									$tagCode = $contentData['content_title'];
									break;
								case 'content_text':
									$tagCode = smarty_modifier_inserttags($contentData['content_text']);
									break;
							}
						}
						else
						{
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- ' . TEXT_NO_CONTENT_DATA . ' -->';
						}
						break;
						

					// -- BOF - BLOG ---------------------------------------------------------------------------
					case 'blog':

                        // link title
                        $link_title = NAVBAR_TITLE_BLOG;
                        
                        // link text
                        $link_text = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : $link_title);
                        
                        // link url
                        $link_url = xtc_href_link(FILENAME_BLOG, '').(($flag != '' && strposa($flag, $arrUrlFlags) != '' && strposa($flag, $arrUrlFlags) == 0) ? $flag : '');
                        if ($clearDomain) $link_url = '/'.str_replace($domain, '', $link_url);
                        
                        $tagCode = '<a'.(($flag != '' && strposa($flag, $arrLinkFlags) == 0) ? ' '.$flag : '').' href="'.$link_url.'" title="'.$link_title.'">'.$link_text.'</a>';

						break;
                   
					case 'blog_post':
					case 'blog_post_url':
					case 'blog_post_title':
					case 'blog_post_name':
					case 'blog_post_img':
					
						$query = xtDBquery("-- ".__FILE__." (line: ".__LINE__.") 
							 SELECT bp.id,
									bp.image,
									bp.image_list,
									bp.status,
									bpd.name, 
									bpd.title 
							 FROM ".TABLE_BLOG_POSTS." bp
							 JOIN ".TABLE_BLOG_POSTS_DESCRIPTION." bpd ON (bpd.id = bp.id) 
							 WHERE bp.id = ".(int)$elements[1]." 
							 AND bpd.language_id = ".(int)$_SESSION['languages_id']."
							 AND trim(bpd.name) != ''");

					    if (xtc_db_num_rows($query, true) > 0) {
							$result = xtc_db_fetch_array($query, true);
							
							// link title
							$link_title = (!empty($result['title']) ? $result['title'] : $result['name']);
							
							// link text
							$link_text = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : $result['name']);
							
							// link url
							$link_url = xtc_href_link(FILENAME_BLOG, 'p='.$result['id']).(($flag != '' && strposa($flag, $arrUrlFlags) != '' && strposa($flag, $arrUrlFlags) == 0) ? $flag : '');
							if ($clearDomain) $link_url = '/'.str_replace($domain, '', $link_url);
							
							switch (strtolower($elements[0])) {
								
								case 'blog_post':
									if ($result['status'] == '1') {
										$tagCode = '<a'.(($flag != '' && strposa($flag, $arrLinkFlags) == 0) ? ' '.$flag : '').' href="'.$link_url.'" title="'.$link_title.'">'.$link_text.'</a>';
									} else {
										$tagCode = $link_text;
									}
									break;
								
								case 'blog_post_url':
									$tagCode = $link_url;
									break;
									
								case 'blog_post_title':
									$tagCode = $link_title;
									break;
									
								case 'blog_post_name':
									$tagCode = $result['name'];
									break;
									
								case 'blog_post_img':
									$image = $main->getImage($result['image'], 'blog_posts/thumbnail_images/', PRODUCT_IMAGE_SHOW_NO_IMAGE);
									if (!empty($image)) {
										$tagCode = '<img'.(($flag != '' && strposa($flag, $arrImgFlags) == 0) ? ' '.$flag : '').' src="'.DIR_WS_BASE.$image.'" alt="'.$link_title.'" title="'.$link_title.'">';
									}
									break;
							}
						
						// no blog post found
						} else {
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- '.TEXT_NO_BLOG_POST_DATA.' -->';
						}
						
						break;


					case 'blog_category':
					case 'blog_category_url':
					case 'blog_category_title':
					case 'blog_category_name':
					case 'blog_category_img':
					
						$query = xtDBquery("-- ".__FILE__." (line: ".__LINE__.") 
							 SELECT bc.id,
									bc.image,
									bc.image_list,
									bc.status,
									bcd.name, 
									bcd.title 
							 FROM ".TABLE_BLOG_CATEGORIES." bc
							 JOIN ".TABLE_BLOG_CATEGORIES_DESCRIPTION." bcd ON (bcd.id = bc.id) 
							 WHERE bc.id = ".(int)$elements[1]." 
							 AND bcd.language_id = ".(int)$_SESSION['languages_id']."
							 AND trim(bcd.name) != ''");

					    if (xtc_db_num_rows($query, true) > 0) {
							$result = xtc_db_fetch_array($query, true);
							
							// link title
							$link_title = (!empty($result['title']) ? $result['title'] : $result['name']);

							// link text
							$link_text = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : $result['name']);
							
							// link url
							$link_url = xtc_href_link(FILENAME_BLOG, 'c='.$result['id']).(($flag != '' && strposa($flag, $arrUrlFlags) != '' && strposa($flag, $arrUrlFlags) == 0) ? $flag : '');
							if ($clearDomain) $link_url = '/'.str_replace($domain, '', $link_url);
							
							switch (strtolower($elements[0])) {
								
								case 'blog_category':
									if ($result['status'] == '1') {
										$tagCode = '<a'.(($flag != '' && strposa($flag, $arrLinkFlags) == 0) ? ' '.$flag : '').' href="'.$link_url.'" title="'.$link_title.'">'.$link_text.'</a>';
									} else {
										$tagCode = $link_text;
									}
									break;
								
								case 'blog_category_url':
									$tagCode = $link_url;
									break;
									
								case 'blog_category_title':
									$tagCode = $link_title;
									break;
									
								case 'blog_category_name':
									$tagCode = $result['name'];
									break;
									
								case 'blog_category_img':
									$image = $main->getImage($result['image'], 'blog_categories/thumbnail_images/', PRODUCT_IMAGE_SHOW_NO_IMAGE);
									if (!empty($image)) {
										$tagCode = '<img'.(($flag != '' && strposa($flag, $arrImgFlags) == 0) ? ' '.$flag : '').' src="'.DIR_WS_BASE.$image.'" alt="'.$link_title.'" title="'.$link_title.'">';
									}
									break;
							}
						
						// no blog post found
						} else {
							$tagCode = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : '').
									   '<!-- '.TEXT_NO_BLOG_CATEGORY_DATA.' -->';
						}
						
						break;

						// -- BOF - SEEDFINDER -----------------------------------------------------------------
						case 'seedfinder':
						case 'seedfinder_url':
							$link_title = 'Seedfinder';
							$link_text = (($flag != '' && strpos($flag, 'linktext') !== false) ? str_replace('linktext:', '', $flag) : $link_title);
							$link_url = xtc_href_link(FILENAME_SEEDFINDER, '').(($flag != '' && strposa($flag, $arrUrlFlags) != '' && strposa($flag, $arrUrlFlags) == 0) ? $flag : '');
							if ($clearDomain) $link_url = '/' . str_replace($domain, '', $link_url);
							switch (strtolower($elements[0])) {
								case 'seedfinder':
									$tagCode = '<a' . (($flag != '' && strposa($flag, $arrLinkFlags) == 0) ? ' ' . $flag : '') . ' href="' . $link_url . '" title="' . $link_title . '">' . $link_text . '</a>';
									break;
								case 'seedfinder_url':
									$tagCode = $link_url;
									break;
							}
							break;
						// -- EOF - SEEDFINDER -----------------------------------------------------------------
					// -- EOF - BLOG ---------------------------------------------------------------------------
					
				}
			}
			
			$return = str_replace( $tagRaw, $tagCode, $return );
		}
	}
	
	return $return;
}

function strposa( $string, $checkArr ) {
	$checkArr = (array) $checkArr;
	
	foreach ( $checkArr as $s )
	{
    	$pos = strpos( $string, $s );
		
		if ( $pos !== false )
		{
			return $pos;
    	}
  	}
	
	return -1;
}