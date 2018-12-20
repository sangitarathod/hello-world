<?php
/**
 * Shortcodes
 *
 * @package     TourPress
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Generic TourPress Shortcode handler
function tourpress_shortcode($atts, $content, $code) {
	global $post;
	
	if(substr($code, 0, 4) == 'xpl_') {
		$code = str_replace('xpl_', '', $code);
	}
	
	//error_log($tag);
	
	//$text = get_post_meta( $post->ID, 'tourpress_'.$code, true );
	$text = get_post_meta( $post->ID, $code, true );

	if($code=="from_price")
		//$text = round(get_post_meta( $post->ID, 'tourpress_'.$code, true ));
		$text = round(get_post_meta( $post->ID, $code, true ));
		
	return $text;
}

// Product Name, Code, Id
add_shortcode('xpl_product_name', 'tourpress_shortcode');
add_shortcode('xpl_product_code', 'tourpress_shortcode');
add_shortcode('xpl_product_id', 'tourpress_shortcode');

// Origin, Destination and Geo Locations
add_shortcode('xpl_origin', 'tourpress_shortcode');
add_shortcode('xpl_geo_origin', 'tourpress_shortcode');
add_shortcode('xpl_destination', 'tourpress_shortcode');
add_shortcode('xpl_geo_destination', 'tourpress_shortcode');	

// Meta keywords
add_shortcode('xpl_meta_tags', 'tourpress_shortcode');

// Text
add_shortcode('xpl_itinerary_text', 'tourpress_shortcode');
add_shortcode('xpl_featured_text', 'tourpress_shortcode');
add_shortcode('xpl_special_text', 'tourpress_shortcode');

// From Price
add_shortcode('xpl_from_price', 'tourpress_shortcode');
add_shortcode('xpl_from_price_type', 'tourpress_shortcode');
add_shortcode('xpl_sale_currency', 'tourpress_shortcode');

// Grade and Star Rating
add_shortcode('xpl_grade', 'tourpress_shortcode');
add_shortcode('xpl_star_rating', 'tourpress_shortcode');

// Duration Rules
add_shortcode('xpl_duration', 'tourpress_shortcode');
add_shortcode('xpl_min_duration', 'tourpress_shortcode');
add_shortcode('xpl_min_duration_type', 'tourpress_shortcode');
add_shortcode('xpl_max_duration', 'tourpress_shortcode');
add_shortcode('xpl_max_duration_type', 'tourpress_shortcode');

// Address, Phone, Fax, Email and Geo Location
add_shortcode('xpl_address', 'tourpress_shortcode');
add_shortcode('xpl_phone', 'tourpress_shortcode');
add_shortcode('xpl_fax', 'tourpress_shortcode');
add_shortcode('xpl_email_address', 'tourpress_shortcode');
add_shortcode('xpl_geocode', 'tourpress_shortcode');

// Capacity
add_shortcode('xpl_capacity', 'tourpress_shortcode');
add_shortcode('xpl_max_pax', 'tourpress_shortcode');
add_shortcode('xpl_max_adults', 'tourpress_shortcode');
add_shortcode('xpl_max_children', 'tourpress_shortcode');
add_shortcode('xpl_min_pax', 'tourpress_shortcode');
add_shortcode('xpl_max_pax', 'tourpress_shortcode');

// Policies
add_shortcode('xpl_child_age', 'tourpress_shortcode');
add_shortcode('xpl_child_policy', 'tourpress_shortcode');
add_shortcode('xpl_checkin_policy', 'tourpress_shortcode');
add_shortcode('xpl_cancellation_policy', 'tourpress_shortcode');

// Days of Operation
add_shortcode('xpl_days_of_operation', 'tourpress_shortcode');


//return parent tax id wdonayre
// function getParentTaxonomyID($taxID, $TxSlug){
// 	while ($parent->parent != '0'){
// 			$term_id = $parent->parent;

// 			$parent  = get_term_by( 'id', $term_id, $taxonomy);
// 	}		
// }

// Product Search
function tourpress_product_search($atts, $content, $code) {

 	global $post;
	
	$productID = get_post_meta( $post->ID, 'product_id', true );	
	$api = new Explorer_Api();
	$result = $api->authenticate('');
	
	/* GET PRODUCT TYPE --------------------- */

	$currentProductType = reset(getProductType($post));
	//var_dump($currentProductType);
	
	//Hook for pre product search shortcode : wd
	do_action('xpl_before_product_search');
	//var_dump($api->get_productTypes());
	
	
	// SET PRODUCT TYPE - if get parameter is empty, it gets its current product type from taxonomy
	$productType = $_GET['ptype'];
	if(!empty($productType)){
		//echo 'VALUE :: '.TOURPRESS_PRODUCT_TYPE[reset($termsArrayObj)];
		//$productType = TOURPRESS_PRODUCT_TYPE[reset($termsArrayObj)];
		$productType = "ACC";
	}
	else{
		$productType = "ACC";	
	}
	$productType = "ACC";
	
	
	if( !empty( $_POST['date_in'] ) ) {

		// extract( shortcode_atts( array( 'posts' => 1, ), $atts));
	 	



		//$date_in = date("Y-m-d");
	   	//$date_out = date('Y-m-d', strtotime('+3 days'));
		
		
		// Date In and Out
		$date_in = isset( $_POST['date_in'] ) ? sanitize_text_field( $_POST['date_in'] ) : '';
		$date_out = isset( $_POST['date_out'] ) ? sanitize_text_field( $_POST['date_out'] ) : '';
		$units = isset( $_POST['units'] ) ? sanitize_text_field( $_POST['units'] ) : 1;
		$adults = isset( $_POST['adults'] ) ? sanitize_text_field( $_POST['adults'] ) : 0;
		$children = isset( $_POST['children'] ) ? sanitize_text_field( $_POST['children'] ) : 0;
	
		echo '<script>var GLOBAL_DATE_IN = "'.$date_in.'";</script>';

		// Pax Types
		$pax_types = array();
		$pax_ages = array();
		$pax_units = array();

		for ($i = 1; $i <= $units; $i++) {
			for ($j = 1; $j <= $adults; $j++) {
				$pax_types[] = 'Adult';
				$pax_ages[] = 0;
				$pax_units[] = $i;
			}
			for ($j = 1; $j <= $children; $j++) {
				$pax_types[] = 'Child';
				$pax_ages[] = 10;	// Default to 10 years old
				$pax_units[] = $i;
			}

		}
		//$pax_ages = array( 0, 0 );
		//$pax_units = array( 1, 1 );

		//return $units . " " . $adults . " " . $children . " " . $pax_types[1];

		$result = $api->get_productsSummary( $productType, null, null, null, $productID, null, null, null, null, null );
		
		if ($api->error( $result ) ) 
		 	$text = 'Error ' . $result->code . ': ' . $result->description;
		else{
			//var_dump($result->allProductResults->XPLProductSearchResult);
			$text = tourpress_product_result($result->allProductResults); 
		}
		
		
		return $text;
	}
	/* show predefined date sub product rates and availability */
	else{
		$args = shortcode_atts( array(
			'defaultdatein' => '',
			'defaultdateout' => '',
			'units' 		=> '',
			'adults' 		=> '',
			'children'	=> ''
		), $atts );
		
		$date_in = $args['defaultdatein'];
		$date_out = $args['defaultdateout'];
		$units = $args['units'];
		$adults = $args['adults'];
		$children = $args['children'];


		// Pax Types
		$pax_types = array();
		$pax_ages = array();
		$pax_units = array();

		for ($i = 1; $i <= $units; $i++) {
			for ($j = 1; $j <= $adults; $j++) {
				$pax_types[] = 'Adult';
				$pax_ages[] = 0;
				$pax_units[] = $i;
			}
			for ($j = 1; $j <= $children; $j++) {
				$pax_types[] = 'Child';
				$pax_ages[] = 10;	// Default to 10 years old
				$pax_units[] = $i;
			}

		}
		//$pax_ages = array( 0, 0 );
		//$pax_units = array( 1, 1 );

		//return $units . " " . $adults . " " . $children . " " . $pax_types[1];
		$debugFilter = array(
			'productType' => $productType,
			'productID' => $productID,
			'date_in' => $date_in,
			'date_out' => $date_out,
			'pax_types' => $pax_types,
			'pax_ages' => $pax_ages,
			'pax_units' => $pax_units
		);
		//var_dump($debugFilter);
		
		
		$result = $api->get_productsSummary( $productType, null, null, null, $productID, $date_in, $date_out, $pax_types, $pax_ages, $pax_units );
		

		
		if ($api->error( $result ) ) 
		 	$text = 'Error ' . $result->code . ': ' . $result->description;
		else{
			
			$text = tourpress_product_result($result->allProductResults); 
		}

			//echo 'wdonayre:::';
			//var_dump($result);
		return $text;
	}

}

add_shortcode('xpl_product_search', 'tourpress_product_search');

function tourpress_product_result($allChildren, $parent = null, $parent_id = 0, $level = 0) {
	
	
  if ( !empty($allChildren) ) {
		ob_start();
		?>
		<div class="secondary-prods-wrap">
		<?php
		foreach ($allChildren->XPLProductSearchResult as $product) {
			if($level)
			{
				$productPost = tourpress_getPost($product->productId);
				$productPost = reset(($productPost->posts));
				//$productPost = productPost[0];

				$imageURL = get_the_post_thumbnail_url($productPost,'full');
				$imageURL = (strlen($imageURL)>0)?$imageURL:'/wp-content/uploads/2017/02/placeholder.png';
		?>
			
			<div class="secondary-prod" id="product-<? echo $product->productId; ?>">
				<??>
				<!-- meta -->
				<div class="secondary-prod__meta">
					<div class="secondary-prod__thumb" style="background-image: url(<? echo $imageURL; ?>)"></div>
					<div class="secondary-prod__text">
						<?
							$currentPermaLink = get_post_permalink($productPost->ID);
						?>
						<h2 class="secondary-prod__text__title"><a href="<?php echo $currentPermaLink;?>"><?php echo $product->productName; ?></a></h2>
						<p>
							<?php echo $productPost->post_excerpt; ?>
						</p>
					</div> 
					<div class="secondary-prod__cta">
						<div class="secondary-prod__price">
							<strong>from</strong> <? echo $product->totalPrice; ?> <small>per night</small>
						</div>
						<?php if ( !empty($product->allRates) ) { ?>
						<button class="block dl-btn dl-btn-lg">VIEW DETAILS</button>
						<?php } ?>
					</div>
				</div> 
				
				<?php
					if ( !empty($product->allRates) ) {
						?>
							<div class="secondary-prod__details">
								<div class="secondary-prod__date">
									<?php
										echo $product->dateFrom .' - '. $product->dateTo;
									?> 
								</div>	
								<div class="secondary-prod__seasons">
									<div class="secondary-prod__season">
										<div class="secondary-prod__season__text">
											<div class="secondary-prod__season__meta">
												<h3><? echo $product->productName; ?></h3> 
												<span><? echo $product->allRates->XPLProductPrice[0]->description; ?></span>
											</div>
											
											<?php
												$rates = $product->allRates->XPLProductPrice[0];
												$len= count($rates->allDates->Date);
												$weekdayCount = 1;
												$dateMap = array();
												//echo '<script>alert("'.$len.'");</script>';
												for($i=0;$i<$len;$i++){
													//$dateMap[substr(date('l', strtotime($rates->allDates->Date[$i])),0,3)] = array('date'=>$rates->allDates->Date[$i], 'price'=>$rates->allPrices->Decimal[$i]);
													
													$dayKey = substr(	date('l', strtotime($rates->allDates->Date[$i])),0,3	);
													if(empty($dateMap[$dayKey])){
														$dateMap[$dayKey] = array(array('date'=>$rates->allDates->Date[$i], 'price'=>$rates->allPrices->Decimal[$i]));
													}
													else
													{
														array_push
														(
															$dateMap
															[
																$dayKey
															],
															array
															(
																'date'=>$rates->allDates->Date[$i],
																'price'=>$rates->allPrices->Decimal[$i]
															)
														); 	
													} 
													
													//echo '<script>console.log("'.substr(date('l', strtotime($rates->allDates->Date[$i])),0,3).'");</script>';
													?>
<!-- 														<div class="secondary-prod__season__price">
															<h3>Fri</h3>
															<span>59</span>
														</div> -->
													<?php
												}
												foreach($dateMap as $key => $value){
													
													echo '<div class="secondary-prod__season__price">';
													echo '<h3>'.$key.'</h3>';
													
													$xLen = count($value);
													//echo '<script>alert("'.$xLen.'");</script>';
													//echo json_encode($value);
													for($col=0;$col<$xLen;$col++){
														echo '<div title="'.$value[$col]['date'].'">'.intval($value[$col]['price']).'</div>';			
													}	
													echo '</div>';
												}
											?>
											
											

										</div>
										<div class="secondary-prod__season__cta">
											<h2 class="secondary-prod__season__cta__price">$<?php echo intval($product->allRates->XPLProductPrice[0]->totalPrice) ?></h2>
											<button class="product-enquire-now dl-btn dl-btn-warning pull-right" 
															data-xpl-type="" 
															data-xpl-id="" 
															data-xpl-name="<? echo $product->productName ?>" 
															data-xpl-desc="" 
															data-xpl-date-in="<? echo $product->dateFrom ?>" 
															data-xpl-date-out="<? echo $product->dateFrom ?>" 
															data-xpl-adults="" 
															data-xpl-price="<?php echo intval($product->allRates->XPLProductPrice[0]->totalPrice) ?>" 
															data-xpl-rooms="" 
															data-xpl-link="<? echo $currentPermaLink ?>",
															data-xpl-thumb="<? echo $imageURL ?>"
											>ENQUIRE NOW</button>
										</div>
									</div>
								</div> <!-- end season -->
							</div>
						<?php
					}
				?>
			</div>

		<?php
			}
		}
		?>
		</div> <!-- end products wrap -->
		<?php
		$text = ob_get_clean();
		$text .= tourpress_product_result($product->allChildren, $product, $post_id, $level + 1);
	}
	
//  	if ( !empty($allChildren) ) {
// 	 	foreach ($allChildren->XPLProductSearchResult as $product) {
// 	 		$text .= ( "<br/>".substr(" - - - -",0, $level * 2) . " " . $product->productName . " "  . $product->productId );
//  			//echo ", Search Id: " . $product->searchId;
//  			$text .= ", Date From: " . $product->dateFrom  . " to " . $product->dateTo;
// 			$text .= ", Price From: " . $product->totalPrice . " " . $product->totalDescription;

// 			if ( !empty($product->allRates) ) {
// 				foreach ($product->allRates->XPLProductPrice as $rate) {
// 					$text .= "<br/>   " . $rate->description;
// 					if( !empty( $rate->allDates )) {
// 						$text .= "<br/>   Dates: " . implode( ",", $rate->allDates->Date );
// 						$text .= "<br/>   Prices: " . implode( ",", $rate->allPrices->Decimal );
// 						$text .= "<br/>   Discounts: " . implode( ",", $rate->allDiscounts->Decimal );
// 						$text .= "<br/>   Statuses: " . implode( ",", $rate->allStatuses->String );
// 					}
// 					$text .= "<br/>   Total Price: " . $rate->currencyId . " " . $rate->totalPrice;
// 					$text .= ", " . $rate->totalStatus;
// 				}
// 			}
			
// 			// Recurse down the product hierarchy
// 			$text .= tourpress_product_result($product->allChildren, $product, $post_id, $level + 1);
// 	 	}
// 	}
 	return $text;
}

function tourpress_getPost($productID){
	global $wp_query;
	wp_reset_query();
	

	
	$args = array(
		'post_type'							=> 'product',
	 	'post_status' 					=> 'publish',
		'orderby' 							=> 'date',
		'order'									=>'DESC',
		'caller_get_posts' 			=> 1,
	 	'paged'                 => 1,
		'meta_query'						=> 	array(
																	 array(
																		"key" 		=> "product_id",
																		"value" 	=> $productID,
																		"type"		=> "CHAR",
																		"compare"	=> "IN",	
																	)
																)
	);
	$query_result = new WP_Query($args);
	
	return $query_result;
}

// // Generate a hyperlink to the booking engine
// function tourpress_booklink($atts, $content, $code) {
// 	global $post;
// 	extract( shortcode_atts( array(
// 	      'style' => 'standard',
// 	      'height' => (get_option('tourpress_bookheight')=="") ? "600" : get_option('tourpress_bookheight'),
// 	      'width' => (get_option('tourpress_bookwidth')=="") ? "600" : get_option('tourpress_bookwidth')
// 	      ), $atts ) );    
	
// 	$link = get_post_meta( $post->ID, 'tourpress_book_url', true );		

// 	if($style=="popup") {
// 		// Popup window
// 		$if_width = (int)$width - 20;
// 		$link .= "&if=1&ifwidth=$if_width";
// 		$text = '<a href="'.$link.'" onclick="window.open(this, \'_blank\', \'height='.$height.',width='.$width.',statusbar=0,scrollbars=1\'); return false;">'.$content.'</a>';
// 	} else {
// 		$text = '<a href="'.$link.'">'.$content.'</a>';
// 	}

// 	return $text;
// }
// add_shortcode('book_link', 'tourpress_booklink');
// add_shortcode('tourpress_book_link', 'tourpress_booklink');

