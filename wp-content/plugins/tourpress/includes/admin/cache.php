<?php
/**
 * Cache
 *
 * @package     TourPress
 * @subpackage  Cache Functions
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// For media
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

// Check cache freshness, update if expired
function tourpress_refresh_cache() {
	//if(is_single() && get_query_var('post_type') == 'tourpress_product') {
	if(is_singular('product')) {	
		// Post details
		global $post;
		$last_updated = get_post_meta( $post->ID, 'last_updated', true );
		$product_id = get_post_meta( $post->ID, 'product_id', true );

		// Cache update frequency
		(get_option('tourpress_update_frequency')=="") ? $tourpress_update_frequency = 14400 : $tourpress_update_frequency = intval(get_option('tourpress_update_frequency'));
		// Calculate next update time
		$next_update = (int)$last_updated + (int)$tourpress_update_frequency;
		
		// Only update if cache is expired
		if(($tourpress_update_frequency!=-1) && ($next_update <= time())) 
			tourpress_refresh_product($post->ID, $product_id);
	}

}

function tourpress_refresh_content() {

	$amended_from = get_option('tourpress_last_product_cache');
	$specials_updated_from = get_option('tourpress_last_specials_cache');//get_option('tourpress_last_specials_cache');
	//$amended_from  = date('Y-m-d', strtotime('-1 days')); ?>
	<div class="wrap">

		<h2><?php echo get_admin_page_title(); ?></h2>

		<p>TourPress keeps up to date content in the WordPress database. Select one or more content types (post types and taxonomies) to refresh...</p>

		<form action="#" method="post">

			<h3>Taxonomies</h3>
			<input type="checkbox" name="check_list[]" value="product_types"><label>Product Types</label><br/>
			<input type="checkbox" name="check_list[]" value="locations"><label>Locations</label><br/>
			<input type="checkbox" name="check_list[]" value="facilities"><label>Facilities</label><br/>

			<h3>Products</h3>			
			<input type="checkbox" name="check_list[]" value="products"><label>Products amended since <span id="admin-amended-date"><?php echo date('F j, Y', strtotime($amended_from)); ?></span></label><br/>

			<h3>Widgets Cache</h3>			
			<input type="checkbox" name="check_list[]" value="specials_cache"><label>Specials last update <?php echo date('F j, Y', strtotime($specials_updated_from)); ?></label><br/>
			<?php 
				//update_option('tourpress_specials_cache','test string');
				echo "<script>console.log('".get_option('tourpress_specials_cache')."');</script>";
				//var_dump(get_option('tourpress_specials_cache'));
			?>
			
			<p class="submit">
				<input class="button-primary" type="submit" value="Refresh Content" name="submit" />
			</p>			
		</form>

	</div>
	<?php
	if( !empty( $_POST['check_list'] ) ) {

		if( tourpress_configured() ) {
			// Loop to store and display values of individual checked checkbox.
			foreach($_POST['check_list'] as $selected) { 
				$func = 'tourpress_refresh_'.$selected;
				echo '<h4>' . ucwords( str_replace( "_", " ", $selected ) ) . ' updated</h4>';
				$func();
			}
		} else {
			print ( 
				'<p>You must configure the <a href="admin.php?page=tourpress_options">TourPress Settings</a> before you can refresh content from a TourPress system.</p>' );
		}
	}
}

function tourpress_refresh_specials_cache(){
	
	
	$api = new Explorer_Api();
	$result = $api->authenticate('');
	
	$result = $api->get_products( /*$productType =*/ null, /*$location =*/ null, /*$name =*/ null, /*$productIDs =*/ null,
						/*$amendedFrom =*/ null, /*$isChildren =*/ true, /*$isDetailed =*/ true, /*$isFromPrice =*/ true, 
						/*$isImages =*/ true, /*$isText =*/ true, /*$isFacilities =*/ true, /*$isLocations =*/ false,
						/*$isPolicies =*/ true, /*$isFeatured =*/ false, /*$isPreferred =*/ false, /*$isSpecials =*/ true,
						/*$isRates =*/ null, /*$ratesFrom =*/ 0, /*$ratesTo =*/ null );

	$json = json_encode($result);


	update_option('tourpress_specials_cache',$json);
	update_option('tourpress_last_specials_cache',current_time( 'Y-m-d' ));
}
// Refresh Products
function tourpress_refresh_products() {
	$amended_from = get_option( 'tourpress_last_product_cache',true );

	$api = new Explorer_Api();
	$result = $api->authenticate('');
	//$result = $api->get_productList( 'ACC', null, null, null, $amended_from,false, true);
//
//$result = $api->get_productList(null,'DEN',null,null,$amended_from,false,true);
//$result = $api->get_productList(null,null,null,null,$amended_from,false,true);
$result = $api->get_products(null,null,null,null,$amended_from,false,true);

// echo '<pre>';
// print_r($result);
// echo '</pre>';
// die();
	if ($api->error( $result ) )
		echo $result->__toString();
	elseif (empty( $result->allProducts ))
		echo "No products to update since " . date('F j, Y', strtotime($amended_from));
	else{
		//foreach ($allProducts->XPLProduct as $product) {
		//	tourpress_update_product();
			//wp_schedule_single_event(time() + 60 , 'neo_tourpress_update_products',array('allProducts'=> $result->allProducts,'parent'=>null,'parent_id'=>0,'level'=>0));
		//}
		tourpress_update_products( $result->allProducts);
	}
	//	tourpress_update_products( $result->allProducts);

	// Update the last product cache date with today
	//update_option( 'tourpress_last_product_cache', current_time( 'Y-m-d' ) );

	$newDate = strtotime(get_option('tourpress_last_product_cache'));
	$newDate = strtotime('+6 day',$newDate);
	echo '<script>document.getElementById("admin-amended-date").textContent = "'.date('M d, Y', $newDate).'";</script>';
	$currentDate = strtotime("now");
	if($newDate <= $currentDate){
		update_option( 'tourpress_last_product_cache', date('Y-m-d', $newDate)); 
		?>
		<script>
			jQuery(document).ready(function($){
				//alert(111);
				$('input[type="checkbox"][value="products"]').prop('checked',true);
				

				function triggerRefresh(){
					
				//if(current_time( 'Y-m-d' ) != date('Y-m-d', $newDate)){
						setTimeout(function(){
							$('input[type="submit"][value="Refresh Content"]').trigger('click');
						},3000);
					}
				
				triggerRefresh();
			});
		</script>
		<?php
	}
}


//add_action( 'neo_tourpress_update_products', 'tourpress_update_products', 10,4);

// Recurse product hierarchy
function tourpress_update_products($allProducts, $parent = null, $parent_id = 0, $level = 0) {
	 $api = new Explorer_Api();
	 $result = $api->authenticate('');
	if (!empty( $allProducts )) {
	 	foreach ($allProducts->XPLProduct as $product) {
			$pos = stripos($product->name,'xxx');

			echo '<br> '. $product->name;


			$ratesFrom = date("Y-m-d");
			   	$ratesTo   = date('Y-m-d', strtotime('+3 years'));
				/*$product = $api->get_product( $product->id, true, $ratesFrom, $ratesTo );
				if(empty($product)){
					$product = $api->get_product( $product->id);
				}*/

	 		if (($product->id != null) && ($pos===false)) {
				$post_arr = get_posts( array(
					'post_type'		=> 'product',
					'post_status'	=> 'any',
				 	'meta_key'		=> 'product_id',
				 	'meta_value'	=> $product->id ) );

				if( $post_arr ) {
					$post = $post_arr[0];
					$post_id = $post->ID;
				}
				else {
					$post_id = wp_insert_post ( array (
						'post_type'		=> 'product',
						'post_title'	=> $product->name,
						'post_status'	=> 'publish',	// problem with hierarchy if set to draft
						'post_parent'	=> $parent_id
						) );
				}
				
			//	error_log('***************************INSIDE tourpress_update_products ********************');

				// Update main fields
				tourpress_update_product( $post_id, $product );
				//echo ( substr(" - - - -",0, $level * 2) . " " . $product->name . " ("  . $product->code . ")<br/> " );

 				// Recurse down the product hierarchy
 				//tourpress_update_products( $product->allChildren, $product, $post_id, $level + 1 );
 				wp_schedule_single_event(time() + 60 , 'neo_update_child_products_in_background',array('allProducts'=>$product->allChildren, 'product'=>$product,'post_id'=>$post_id,'main_parent_id'=>$post_id));
	 		}
	 	}
	}	
}



// Refresh Product Types
function tourpress_refresh_product_types() {
	$api = new Explorer_Api();
	$result = $api->authenticate('');
	if ($api->error( $result ) ) 
		echo 'Error ' . $result->code . ': ' . $result->description;
	else {
		$result = $api->get_productTypes( null, true);
		tourpress_update_product_types( $result->allProductTypes);
	}
}

// Recurse product type hierarchy
function tourpress_update_product_types($allProductTypes, $parent_term = null, $level = 0) {
 	if ( !empty( $allProductTypes )) {
 		$parent = $parent_term != null ? $parent_term['term_id'] : null;
	 	foreach ($allProductTypes->XPLProductType as $product_type) {
	 		if ($product_type->id != null) {

	 			$term = term_exists( $product_type->name, "tourpress_product_type", $parent );
	 			//print_r ($term);
	 			if ( $term == 0 || $term == null) {
 					//$term = wp_insert_term( $product_type->name , 'product_type' , array( 'parent' => $parent_term['term_id'] ) );
 					$term = wp_insert_term( $product_type->name , 'tourpress_product_type' , array( 'parent' => $parent ) );
 				}
  				// Update meta data
 				update_term_meta( $term[term_id], "product_type_id" , $product_type->id );
				echo ( substr(" - - - -",0, $level * 2) . " " . $product_type->name . "<br/>" );

 				// Recurse down the product type hierarchy
 				tourpress_update_product_types($product_type->allChildren, $term, $level + 1);
	 		}
	 	}
	}	
}

// Refresh Locations
function tourpress_refresh_locations() {
	$api = new Explorer_Api();
	$result = $api->authenticate('');
	$result = $api->get_locations( null, true );

	tourpress_update_locations( $result->allLocations);
}

// Recurse location hierarchy
function tourpress_update_locations($allLocations, $parent_term = null, $level = 0) {
 	if (!empty( $allLocations )) {
 		$parent = $parent_term != null ? $parent_term['term_id'] : null;
	 	foreach ($allLocations->XPLLocation as $location) {
	 		if ($location->id != null) {
	 			$term = term_exists( $location->name, "location", $parent );
	 			if ( $term == 0 || $term == null) {
 					$term = wp_insert_term( $location->name , 'location' , array( 'parent' => $parent ) );
 				}
 				// Update meta data
 				update_term_meta( $term[term_id], "location_id" , $location->id );
				echo ( substr(" - - - -",0, $level * 2) . " " . $location->name . "<br/>" );

 				// Recurse down the location hierarchy
 				tourpress_update_locations($location->allChildren, $term, $level + 1);
	 		}
	 	}
	}	
}

// Refresh Facilities (from root level Product Types)
function tourpress_refresh_facilities() {
	$api = new Explorer_Api();
	$result = $api->authenticate('');
	$result = $api->get_productTypes( null, false, true );	// Root level only

	$allProductTypes = $result->allProductTypes;

 	if (!empty( $allProductTypes )) {
	 	foreach ($allProductTypes->XPLProductType as $product_type) {

	 		if (!empty( $product_type->allFacilites )) {
		 		foreach ($product_type->allFacilites->XPLFacility as $facility) {

		 			// Insert the category if new
		 			$category = term_exists( $facility->category, "facility" );
		 			if ( $category == 0 || $category == null) {
	 					$category = wp_insert_term( $facility->category , 'facility' );
	 				}

	 				// Insert the term within it's parent category
	 				$parent = $category['term_id'];
		 			$term = term_exists( $facility->name, "facility", $parent );
		 			if ( $term == 0 || $term == null) {
	 					$term = wp_insert_term( $facility->name , 'facility' , array( 'parent' => $parent ) );
	 				}
	 				// Update meta data
	 				update_term_meta( $term[term_id], "facility_id" , $facility->id );
	 				echo $facility->name . ' [' . $facility->category . ']<br/>';
		 		}
	 		}
	 	}
	}	
}


/**********************************Neosoft Changes Start ******************************************************/

// Recurse product hierarchy
function neo_tourpress_update_products($allProducts, $parent = null, $parent_id = 0, $main_parent_id = 0, $level = 0,$dealCode=array()) {
    
   
error_log('********************START neo_tourpress_update_products***********************');
	$api = new Explorer_Api();
	$result = $api->authenticate('');

	if (!empty( $allProducts )) {
	 	foreach ($allProducts->XPLProduct as $product) {
			$pos = stripos($product->name,'xxx');
			error_log(print_r($product->id,1));
		    error_log('INISDE ALLPRODUCT');
		   // $post_id = '';
			//error_log(print_r($product->id,1));
         
	 		if (($product->id != null) && ($pos===false)) {
				$post_arr = get_posts( array(
					'post_type'		=> 'product',
					'post_status'	=> 'any',
				 	'meta_key'		=> 'product_id',
				 	'meta_value'	=> $product->id ) );



				$cur_product_id =$product->id;
				if( $post_arr ) {
					$post = $post_arr[0];
					$post_id = $post->ID;
					$ratesFrom = date("Y-m-d");
				   	$ratesTo   = date('Y-m-d', strtotime('+3 years'));

					//$product = $api->get_product( $product->id, true, $ratesFrom, $ratesTo );
					$result = $api->get_products( null, null, null, array ( $product->id ), null, true, false, false, true, false,false,false,false,false,false,false, true,$ratesFrom,$ratesTo);
					//error_log('***************************** NEW TEST ***************************************');
					//error_log(print_r($result,1));
					if($post->post_parent == 0){
						wp_update_post(
								array(
									'ID' => $post_id, 
									'post_parent' => $parent_id
								)
							);
					}
                   
                   error_log('errorline');
                   error_log(print_r($result,1));

					if(isset($result) && !empty($result) && !($api->error( $result ))){
						$product = $result->allProducts->XPLProduct[0];
					}
					 	
				}
				else {

					//error_log('InISDE ELSE----');
					$post_id = wp_insert_post ( array (
						'post_type'		=> 'product',
						'post_title'	=> $product->name,
						'post_status'	=> 'publish',	// problem with hierarchy if set to draft
						'post_parent'	=> $parent_id
						) );
					$product = $api->get_product( $product->id);
				}




error_log('***************************INSIDE neo_tourpress_update_products ********************');
				// Update main fields
				if(isset($product) && !empty($product)){
					tourpress_update_product( $post_id, $product );
				}else{
					$product = $api->get_product( $cur_product_id );
					tourpress_update_product( $post_id, $product );
				}
		
			if(isset($product->allSeasons->XPLProductSeason) && !empty($product->allSeasons->XPLProductSeason)){
				$allSeasons = $product->allSeasons->XPLProductSeason;
				if(isset($allSeasons) && !empty($allSeasons)){
					foreach($allSeasons as $season)
					{
						if(strtotime($season->dateTo) >= time()){
							if(!in_array($season->dealCode, $dealCode)){
								array_push($dealCode,$season->dealCode);
							}
						}
					}
				}
			}

 				// Recurse down the product hierarchy
 				neo_tourpress_update_products( $product->allChildren, $product, $post_id, $main_parent_id, $level + 1,$dealCode);
	 		}

               //error_log('post_id'.$post_id);
	 		$all_level_wise_credentials = get_all_membership_levels_credentials($post_id);
	 	}
	}	

	if(isset($dealCode) && !empty($dealCode)){
	 	update_post_meta($main_parent_id,'tourpress_deal_codes',$dealCode);
	}
 
    
    //error_log('mynewcode.................');

	//levelwise store tourpress rates
   // $api_membership_level_rate = new Explorer_Api();
  //  $result_mem_lev_rate = $api_membership_level_rate->authenticate('');

    // error_log('*************************neo_tourpress_update_products********************');
  

     //get_product_price_according_to_membership_level($main_parent_id);
   //  get_product_price_according_to_membership_level($main_parent_id,$all_level_wise_credentials);


}

add_action('neo_update_child_products_in_background','neo_tourpress_update_products',10,4);
/**********************************Neosoft Changes End ******************************************************/


// Updates TourPress content for a particular Product, called either when
// editing in WordPress or when being viewed with a stale cache
function tourpress_refresh_product($post_id, $product_id) {
	
	if(get_post_type($post_id) != 'product' || $product_id == 0){
		return;
	}

	//if(isset($product_id) && !empty($product_id)){
		update_post_meta( $post_id, 'product_id', $product_id);
	//}
	if (tourpress_configured() ) {

		// Query API
		$api = new Explorer_Api();
		$result = $api->authenticate('');

		$ratesFrom = date("Y-m-d");
	   	$ratesTo   = date('Y-m-d', strtotime('+3 years'));
	 	
		$response=$api->get_product($product_id,true,$ratesFrom,$ratesTo);

		if ($api->error( $response ) )
			return;

		if(isset($response) && !empty($response->id)){

		//	error_log(print_r($response,1));
		$product = $response;
		//error_log('***************************INSIDE IF ********************');
		tourpress_update_product($post_id, $product);
		if(isset($product->allChildren) && !empty($product->allChildren)){
			error_log('*************************** refresh function INSIDE IF ********************');
		 	wp_schedule_single_event(time() + 60 , 'neo_update_child_products_in_background',array('allProducts'=>$product->allChildren, 'product'=>$product,'post_id'=>$post_id,'main_parent_id'=>$post_id));
			}
	 	}else{
	 		$response = $api->get_product($product_id);
	 		$product = $response;
	 		if(isset($product) && !empty($product)){
	 			error_log('***************************INSIDE ELSE ********************');
	 			tourpress_update_product($post_id, $product);
	 			wp_schedule_single_event(time(), 'neo_update_child_products_in_background',array('allProducts'=>$product->allChildren, 'product'=>$product,'post_id'=>$post_id,'main_parent_id'=>$post_id));
	 		}

	 	}
	}
}

function tourpress_update_product ($post_id, $product) {
	
	if(get_post_type($post_id) != 'product'){
		return;
	}
//error_log(print_r($product,1));

	// Unhook the action to prevent an infinite loop (when using wp_update_post)
	remove_action( 'save_post', 'tourpress_save_product', 1, 2);

	// Mandatory fields
	update_post_meta( $post_id, 'last_updated', time());
	update_post_meta( $post_id, 'product_name', (string)$product->fullName);
	update_post_meta( $post_id, 'product_code', (string)$product->code);
	update_post_meta( $post_id, 'product_id', (int)$product->id);

	$sequential_number = get_post_meta($post_id,'sequential_number',true);
	if(!isset($sequential_number) || empty($sequential_number)){
		update_post_meta($post_id,'sequential_number','0');
		update_post_meta($post_id,'_sequential_number','field_5ab9c5e867866');
	}
// 	wp_update_post(
//     array(
//         'ID' => $image_id, 
//         'post_parent' => $new_post_id
//     )
// );


	$is_searchable = !empty($product->searchId);
	update_post_meta( $post_id, 'is_searchable', $is_searchable);


	/** 
	 * NeoSoft changes start
	 * Commented condition is_searchable
	 * uncommented product type updation code
	 */
	// Only interested in product types and locations taxonomies if searchable
	//if( $is_searchable ) {
		tourpress_update_taxonomy( $post_id, 'tourpress_product_type', 'product_type_id', $product->productTypeID );
		tourpress_attach_product_types( $post_id, $product->searchId );	// Product Type Ids returned as a comma delimeted string

		if(isset($product->myOrigin->id) && !empty( $product->myOrigin->id )){
			tourpress_update_taxonomy( $post_id, 'location', 'location_id', $product->myOrigin->id );
		
		}
		else if(isset($product->myDestination->id) && !empty($product->myDestination->id)){
			tourpress_update_taxonomy( $post_id, 'location', 'location_id', $product->myDestination->id );
		}
		$str = '';
		if(isset($product->allText->XPLText) && !empty($product->allText->XPLText)){
			$str = trim(strip_tags(htmlspecialchars_decode(tourpress_get_text( $product->allText->XPLText, 'Description'))));
			$str = str_replace("&nbsp;",'',$str);
		}

	wp_update_post( array( 'ID' => $post_id, 'post_content' => $str ) );
	
	wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => tourpress_get_text( $product->allText->XPLText, 'Brief Description', true) ) );

	// Origin, Destination and Geo Locations
	update_post_meta( $post_id, 'origin', (string)$product->myOrigin->name);
	update_post_meta( $post_id, 'geo_origin',  !empty( $product->myOrigin->longitude ) ? (string)$product->myOrigin->longitude . ', ' . (string)$product->myOrigin->latitude : null );
	update_post_meta( $post_id, 'destination', (string)$product->myDestination->name);
	update_post_meta( $post_id, 'geo_destination',  !empty( $product->myDestination->longitude ) ? (string)$product->myDestination->longitude . ', ' . (string)$product->Destination->latitude : null );
		
	// Meta keywords
	update_post_meta( $post_id, 'meta_tags', tourpress_get_text( $product->allText->XPLText, 'Meta Keywords', true) );

	// Text
	update_post_meta( $post_id, 'itinerary_text', tourpress_get_text( $product->allText->XPLText, 'Itinerary') );
	update_post_meta( $post_id, 'product_itinerary_text', tourpress_get_text( $product->allText->XPLText, 'Product Itinerary') );
	update_post_meta( $post_id, 'featured_text', tourpress_get_text( $product->allText->XPLText, 'Featured') );
	update_post_meta( $post_id, 'special_text', htmlspecialchars_decode(tourpress_get_text( $product->allText->XPLText, 'Special Deal') ));

	// From Price
	update_post_meta( $post_id, 'sale_currency', (string)$product->myPriceFrom->myCurrency->code);
	update_post_meta( $post_id, 'from_price', (string)$product->myPriceFrom->price);
	update_post_meta( $post_id, '_regular_price', $product->myPriceFrom->price);
	
	update_post_meta( $post_id, 'from_price_type', (string)$product->myPriceFrom->priceType);

	// Grade and Star Rating
	update_post_meta( $post_id, 'grade', (string)$product->myClass->name);
	update_post_meta( $post_id, 'star_rating', (string)$product->myClass->starRating);

	// Duration Rules
	update_post_meta( $post_id, 'duration', (string)$product->myDurationRules->description);
	update_post_meta( $post_id, 'min_duration', (int)$product->myDurationRules->minDuration);
	update_post_meta( $post_id, 'min_duration_type', (string)$product->myDurationRules->minDurationType);
	update_post_meta( $post_id, 'max_duration', (int)$product->myDurationRules->maxDuration);
	update_post_meta( $post_id, 'max_duration_type', (string)$product->myDurationRules->maxDurationType);

	// Address, Phone, Fax, Email and Geo Location
	update_post_meta( $post_id, 'address', tourpress_get_address( $product->allAddresses->XPLAddress, 'Street') );
	update_post_meta( $post_id, 'phone', tourpress_get_phone( $product->allPhones->XPLPhone, 'Phone') );	
	update_post_meta( $post_id, 'fax', tourpress_get_phone( $product->allPhones->XPLPhone, 'Fax') );
	update_post_meta( $post_id, 'email_address', (string)$product->emailAddress);
	update_post_meta( $post_id, 'geocode',  !empty( $product->longitude ) ? (string)$product->longitude . ', ' . (string)$product->latitude : null );
	// Capacity
	update_post_meta( $post_id, 'capacity', (string)$product->myCapacityRules->description);
	update_post_meta( $post_id, 'min_pax', (int)$product->myCapacityRules->minPax);
	update_post_meta( $post_id, 'max_pax', (int)$product->myCapacityRules->maxPax);
	update_post_meta( $post_id, 'min_adults', (int)$product->myCapacityRules->minAdults);
	update_post_meta( $post_id, 'max_adults', (int)$product->myCapacityRules->maxAdults);
	update_post_meta( $post_id, 'max_children', (int)$product->myCapacityRules->maxChildren);


	// Days of Operation
	update_post_meta( $post_id, 'days_of_operation', (string)$product->myDaysOfOperation->description);
	
	update_post_meta( $post_id, 'myChildRules', $product->myChildRules);

	update_post_meta( $post_id, 'tourpress_rooms', $product->rooms);

	update_post_meta( $post_id, 'myCheckInOutRules', $product->myCheckInOutRules);

	update_post_meta( $post_id, 'myCancellationFeeRules', $product->myCancellationFeeRules);


	/* NeoSoft changes end */
	

	if(isset($product->allSeasons) && !empty($product->allSeasons)){
				$selected_deal_code = get_post_meta($post_id,'select_deal_code',true);
				$parent_id = wp_get_post_parent_id( $post_id );
				if(!isset($selected_deal_code) || empty($selected_deal_code)){
					
					$selected_deal_code = get_post_meta($parent_id,'select_deal_code',true);
				}
				$allSeasons = $product->allSeasons->XPLProductSeason;
				$dealCode = $deal = array();
				if(isset($allSeasons) && !empty($allSeasons)){
					$i=0;
					$deal_price = 0;
					foreach($allSeasons as $season)
					{
						array_push($dealCode,$season->dealCode);
						$deal[$i]['deal_code'] = $season->dealCode;
						$deal[$i]['bookableFrom'] = $season->bookableFrom;
						$deal[$i]['bookableTo'] = $season->bookableTo;
						$deal[$i]['dateFrom'] = $season->dateFrom;
						$deal[$i]['dateTo'] = $season->dateTo;
						$deal[$i]['name'] = $season->name;
						$deal[$i]['brief_description'] = tourpress_get_text( $season->allText->XPLText, 'Brief Description');
						$deal[$i]['description'] = tourpress_get_text( $season->allText->XPLText, 'Description');
						$deal[$i]['other_description'] = tourpress_get_text( $season->allText->XPLText, 'Other...');
						$deal[$i]['deal_description'] = tourpress_get_text( $season->allText->XPLText, 'Quote');
						$deal[$i]['allRateBands'] = $season->allRateBands->XPLProductRateBand ;
						$deal[$i]['myDiscountRules'] = $season->myDiscountRules;
						$deal[$i]['myPaxRules'] = $season->myPaxRules;
						$deal[$i]['mySurchageRule'] = $season->mySurchageRule;
						$deal[$i]['myCapacityRules'] = $season->myCapacityRules;
						$deal[$i]['myChildRules'] = $season->myChildRules;
						$deal[$i]['myDurationRules'] = $season->myDurationRules;
						


						$term = term_exists( $season->name, 'product_cat' );
						$parent_term = term_exists( 'Tactical Specials', 'product_cat' ); // array is returned if taxonomy is given

						if ( $term == 0 || $term == null ) {
							
								$term_id = wp_insert_term(
								  $season->name, // the term 
								  'product_cat', // the taxonomy
								  array(
								    'description'=> tourpress_get_text( $season->allText->XPLText, 'Brief Description'),
								    'parent'=> $parent_term['term_id']  // get numeric term id
								  )
								);
								wp_set_post_terms( $post_id, array($term_id), 'product_cat');
								wp_set_post_terms( $parent_id, array($term_id), 'product_cat');
						}else{
							wp_set_post_terms( $post_id, array($term['term_id']), 'product_cat');
							wp_set_post_terms( $parent_id, array($term['term_id']), 'product_cat');
						}
						wp_set_post_terms( $post_id, array($parent_term['term_id']), 'product_cat');
						wp_set_post_terms( $parent_id, array($parent_term['term_id']), 'product_cat');
						if($season->dealCode == $selected_deal_code){
							
							foreach($season->allRateBands->XPLProductRateBand as $rateband){
								$price = floatval(tourpress_get_rates($rateband->allRates->XPLProductRate, 'Single'));
								if(!$price){
									$price = floatval(tourpress_get_rates($rateband->allRates->XPLProductRate, 'Person'));
								}	

								if($price > $deal_price && ($deal_price > 0 ) )
									$deal_price = $price;
							}
						}
						$i++;
					}
				}
				// if(isset($dealCode) && !empty($dealCode)){
	 		// 		update_post_meta($post_id,'tourpress_deal_codes',$dealCode);
	 		// 		if($deal_price > 0 ){
	 		// 			update_post_meta($post_id,'_regular_price',$deal_price);
				// 	}
				// }
				

				if(isset($parent_id) && !empty($parent_id)){
					$parent_current_price = get_post_meta($parent_id,'_regular_price',true);
					if($deal_price > 0 && $deal_price < $parent_current_price || $parent_current_price <= 0 ){
						update_post_meta($parent_id,'_regular_price',$deal_price);
					}
				}
				//error_log('deal_data '. print_r($deal,1));
				update_post_meta($post_id,'tourpress_deal_details',$deal);
	}
	/*else{
		update_post_meta($post_id,'_regular_price',0);
	}*/


	//update_post_meta( $post_id, '_regular_price', (string)$product->myPriceFrom->price);

	if(isset($product->allImages) && !empty($product->allImages)){
		tourpress_update_images( $post_id, $product->allImages->XPLImage );
	}
	// Facilities
	if(isset($product->allFacilities) && !empty($product->allFacilities)){
		tourpress_update_facilities( $post_id, $product->allFacilities->XPLFacility );
	}
	add_action( 'save_post', 'tourpress_save_product', 1, 2);
}

// Retrieve Address by Type
function tourpress_get_address( $addresses, $type, $sanitize = false ) { 
	if (is_array($addresses)) {
		foreach ($addresses as $address) {
			if ($address->type == $type) {
				$text = $address->addressLine . ' ' . $address->citySuburb . ' ' . $address->state . ' ' . $address->postCode . ' ' . $address->myCountry->name; 
				if ($sanitize)
					return sanitize_text_field( $text);
				else
					return $text;
			}
		}
	}
}

// Retrieve Phone by Type
function tourpress_get_phone( $phones, $type, $sanitize = false ) { 
	if (is_array($phones)) {
		foreach ($phones as $phone) {
			if ($phone->type == $type) { 
				if ($sanitize)
					return sanitize_text_field( $phone->number);
				else
					return $phone->number;
			}
		}
	}
}

// Retrieve Text by Type
function tourpress_get_text( $texts, $type, $sanitize = false ) { 
	if (is_array($texts)) {
		foreach ($texts as $text) {
			if ($text->type == $type) {
				if ($sanitize)
					return sanitize_text_field( $text->text );
				else
					return $text->text;
			}
		}
	}
}

/**
 * Retrieve Rates - Neosoft
 */
function tourpress_get_rates( $rates, $type, $sanitize = false ) { 
	if (is_array($rates)) {
		foreach ($rates as $rate) {
			if ($rate->type == $type) {
				if ($sanitize)
					return sanitize_text_field( $rate->value );
				else
					return $rate->value;
			}
		}
	}
}



// Upload and attach new images, and set the featured image
function tourpress_update_images( $post_id, $images ) {
	if (is_array($images)) {
		// Construct the image url
		$url = get_option( 'tourpress_service_url' );
		if ( substr( $url, 0 , 7 ) !== 'http://' ) $url = 'http://' . $url;	// Prepend http:// if missing
		//$url .= '/explorerImages/';
		$url .= '/';	// Incase Web Images is not prepended with it
			
		$new_gallery_images=array();

		// See if the images are already attached
		foreach ($images as $image) {
			global $wpdb;
			$results = $wpdb->get_col("SELECT id FROM $wpdb->posts WHERE post_title = '".$image->name."' AND post_type='attachment'");
			   // error_log($attachment_check->have_posts());
			$media = '';
			     if ( isset($results) && !empty($results)) {
			     	foreach ( $results as $result ) {
						//$media = $results->guid;
						$media = $result;
						break;
					}
					
			     }
			     else{
			     	$media = media_sideload_image($url.$image->url, $post_id, $image->name,'id');

			     }
			// If primary, retrieve the image and set it as featured ID
			if ($image->isPrimary == 1 && !empty($media) && !is_wp_error($media)){
		        set_post_thumbnail( $post_id, $media ); 
			}
			elseif($media){
				array_push($new_gallery_images, $media);
			}
		}

        if(isset($new_gallery_images) && !empty($new_gallery_images)){
			update_post_meta($post_id,'__fr_pol_image_gallery','field_58e5c7a14d8ad');
			$new_gallery_images = array_unique($new_gallery_images);
			update_post_meta($post_id,'_fr_pol_image_gallery',$new_gallery_images);
	 	}
		/** NeoSoft Changes End */
	}
}

// Upload and attach new images, and set the featured image
/*function tourpress_update_images( $post_id, $images ) {
	if (is_array($images)) {
		// Construct the image url
		$url = get_option( 'tourpress_service_url' );
		if ( substr( $url, 0 , 7 ) !== 'http://' ) $url = 'http://' . $url;	// Prepend http:// if missing
		//$url .= '/explorerImages/';
		$url .= '/';	// Incase Web Images is not prepended with it

		// Get existing (attached) images
		$attached_media = get_attached_media( 'image', $post_id );
		$thumbnail_id = get_post_thumbnail_id( $post_id );

		// echo '<pre>';
		// print_r($attached_media);
		// echo '</pre>';
		// 	echo '<br>Images***********<pre>';
		// print_r($images);
		// echo '</pre>';
	//	die();

			
		$new_gallery_images=array();
		//delete_post_meta($post_id,'%french_banner_image%');

		// See if the images are already attached
		foreach ($images as $image) {
			$exists = false;

			foreach ($attached_media as $attached) {
				if ($attached->post_title == $image->name) {
					$exists = true;
					break;
				}
			}
			
			// Only the image into the library if unattached
			if (!$exists){
				//echo $image->name;
				$media = media_sideload_image($url.$image->url, $post_id, $image->name,'src');
			}
			else{
				$media = $attached->guid;
			}
			// If primary, retrieve the image and set it as featured ID
			if ($image->isPrimary && !empty($media) && !is_wp_error($media) ){
				//wp_delete_attachment( get_post_thumbnail_id( $post_id), true );
				$args = array(
			        'post_type' => 'attachment',
			        'posts_per_page' => -1,
			        'post_status' => 'any',
			        'post_parent' => $post_id
		    	);

	   				// reference new image to set as featured
	    		$attachments = get_posts( $args );

		    	if (isset( $attachments ) && is_array( $attachments )) {
		        	foreach( $attachments as $attachment ) {
		            	// grab source of full size images (so no 300x150 nonsense in path)
		            	$image = wp_get_attachment_image_src( $attachment->ID, 'full' );
		            	// determine if in the $media image we created, the string of the URL exists
		            	if (strpos( $media, $image[0] ) !== false ) {
		                	// if so, we found our image. set it as thumbnail
		                	set_post_thumbnail( $post_id, $attachment->ID );
		                	// only want one image
		                	break;
			            }
			        }
			    }
			}
			else if(!empty($media) && !is_wp_error($media)){
					
				array_push($new_gallery_images, $media);
			}
		}

		
		/** NeoSoft Changes Start */
		/*if(get_post_type( $post_id ) == 'tourpress_product'){

			 $args = array(
		        'post_type' => 'attachment',
		        'posts_per_page' => -1,
		        'post_status' => 'any',
		        'post_parent' => $post_id
	    	);
			 $gallery_images=array();
				// reference new image to set as featured
			$attachments = get_posts( $args );
			if (isset( $attachments ) && is_array( $attachments )) {
	        	foreach( $attachments as $attachment ) {
	        		if(get_post_thumbnail_id( $post_id) != $attachment->ID){
	        			$image = wp_get_attachment_image_src( $attachment->ID, 'full' );
		  				if(!in_array($attachment->ID, $gallery_images) && in_array($image[0], $new_gallery_images)){
							array_push($gallery_images, $attachment->ID);
						}
					}
	        	}
	        }
	  
	        if(isset($gallery_images) && !empty($gallery_images)){
				update_post_meta($post_id,'__fr_pol_image_gallery','field_58e5c7a14d8ad');
				$gallery_images = array_unique($gallery_images);
				update_post_meta($post_id,'_fr_pol_image_gallery',$gallery_images);
		 	}
		}
		/** NeoSoft Changes End */
	/*}
}*/

// Attach Product Types
function tourpress_attach_product_types( $post_id, $product_type_ids ) {
	$ids = explode( ',' , $product_type_ids );
	foreach($ids as $id) {
 		tourpress_update_taxonomy( $post_id, 'tourpress_product_type', 'product_type_id', $id);
    }
}

// Attach Facilities
function tourpress_update_facilities( $post_id, $facilities ) {

	// Get the facility terms
	if (is_array($facilities)) {
		foreach ($facilities as $facility) {
			tourpress_update_taxonomy( $post_id, 'facility', 'facility_id', $facility->id, false );
		}
	}
}

// Update Taxonomy (hierarchical by default)
function tourpress_update_taxonomy( $post_id, $taxonomy, $key, $value, $hierarchy = true ) {
	$args = array(
		'taxonomy'   => $taxonomy,
	    'hide_empty' => false,	// also retrieve terms which are not used yet
	    'meta_query' => array(
	        array(
	           'key'       => $key,
	           'value'     => $value,
	           'compare'   => '='
	        )
	    )
	);
	                
	$terms = get_terms( $args );



	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		$term_id = $terms[0]->term_id;
		if ($hierarchy) {
			$term_ids = get_ancestors( $term_id, $taxonomy );	// Get the ancestors
		}
		$term_ids[] = $term_id; 							// Append self
		$term_taxonomy_ids = wp_set_object_terms( $post_id, $term_ids, $taxonomy, true );
	}
}

// Return true if TourPress settings configured for caching
function tourpress_configured() {

	// Load TourPress plugin settings
	$url 		= get_option('tourpress_service_url');
	$channelID  = get_option('tourpress_channelID');
	$password   = get_option('tourpress_password');	

	$configured = !( $url==false || $channelID==false || $password==false );		

	return $configured;
}

/** Neosoft Changes start */

function get_tag_data($string,$tag_name){

	if (strpos($string, $tag_name) !== false) {
		$str1 = explode("[$tag_name]",$string);
		$str1 = explode("[/$tag_name]",$str1[1]);
		return htmlspecialchars_decode($str1[0]);
	}
	return '';
}

function tourpress_refresh_product_content(){
	?>
	<div class="wrap">

		<h2><?php echo get_admin_page_title(); ?></h2>

		<form action="#" method="post">
			<label>Select Locations</label><br>
			<?php wp_dropdown_categories( 'taxonomy=location&hierarchical=1&show_option_all=All Locations&name=neo_location' );
			?><br>

			<label>Select Product Type</label>	<br>	
			<?php wp_dropdown_categories( 'taxonomy=tourpress_product_type&hierarchical=1&show_option_all=All Product Type&name=neo_product_type' );
			?>
			<p class="submit">
				<input id="refresh_product_content" class="button-primary" type="submit" value="Refresh Product Content" name="submit" />
				<img id="neo_loading_image" style="width:20px;height:20px;display:none;" src="<?php echo plugin_dir_url( dirname(dirname(__FILE__ ))) .'/assets/images/loading-icon.gif';?>">
			</p>
			<div id="neo_updated_products_list"></div>			
		</form>

	</div>
	<?php
}


add_action('admin_enqueue_scripts','neo_enqueue_update_prod_content_script');

function neo_enqueue_update_prod_content_script(){
	wp_register_script( "update_product_content", plugin_dir_url( dirname(dirname(__FILE__ ))) .'assets/js/update-product-content.js', array('jquery') );
   	wp_localize_script( 'update_product_content', 'product_data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));       
   	wp_enqueue_script( 'update_product_content' );
}

add_action('wp_ajax_update_products','neo_update_products');
add_action('wp_ajax_nopriv_update_products','neo_update_products');

function neo_update_products(){
	$location = $_POST['location'];
	$product_type = $_POST['product_type'];
	$offset = $_POST['offset'];
	if(!empty($location) && !empty($product_type)){
		$args = array( 'post_type' => 'product','offset'=>$offset,'posts_per_page' => 1,'orderby'=> 'date','order'=> 'DESC','post_parent' => 0,
	                'tax_query' => array(
	                    'relation' => 'AND',
	                    array(
	                        'taxonomy' => 'location',
	                        'field' => 'term_id',
	                        'terms' => $location,
	                    ),
	                    array(
	                        'taxonomy' => 'tourpress_product_type',  // but really any taxonomy
	                        'terms' =>$product_type,
	                        'field' => 'term_id',
	                      ),
	                ),
			);
	}
	else if(!empty($location) && empty($product_type)){
		$args = array( 'post_type' => 'product','offset'=>$offset,'posts_per_page' => 1,'orderby'=> 'date','order'=> 'DESC', 'post_parent' => 0,
	                'tax_query' => array(
	                    array(
	                        'taxonomy' => 'location',
	                        'field' => 'term_id',
	                        'terms' => $location,
	                    )
	                ),
			);
	}
	else if(empty($location) && !empty($product_type)){
		$args = array( 'post_type' => 'product','offset'=>$offset,'posts_per_page' => 1,'orderby'=> 'date','order'=> 'DESC', 'post_parent' => 0,
	                'tax_query' => array(
	                    array(
	                        'taxonomy' => 'tourpress_product_type',  // but really any taxonomy
	                        'terms' =>$product_type,
	                        'field' => 'term_id',
	                      )
	                ),
			);
	}
	else{
		$args = array( 'post_type' => 'product','offset'=>$offset,'posts_per_page' => 1,'orderby'=> 'date','order'=> 'DESC', 'post_parent' => 0);
	}
	$loop = get_posts( $args );

	if(isset($loop) && !empty($loop)){
		foreach($loop as $product)
		{
			$product_id = intval(get_post_meta( $product->ID, 'product_id', true ));
			//if(isset($product_id) && !empty($product_id)){
				tourpress_refresh_product($product->ID,$product_id);
				echo "<p>".$product->post_title."</p>";
			//}
		}
	}
	die();
}


//add_action( 'init', 'unregister_taxonomy');
//remove_action( 'genesis_entry_footer', 'genesis_post_meta' ); // uncomment this if you're using Genesis to avoid errors
/*function unregister_taxonomy(){
 global $wp_taxonomies;
 $taxonomies = array( 'product_cat', 'product_tag' );
 foreach( $taxonomies as $taxonomy ) {
 if ( taxonomy_exists( $taxonomy) )
 unset( $wp_taxonomies[$taxonomy]);
 }
}*/





/** Neosoft Changes end */


?>
