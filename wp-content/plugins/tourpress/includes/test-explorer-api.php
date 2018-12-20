<?php
session_start();
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function tourpress_test_api() {

$api = new Explorer_Api();
?>

<div class="wrap">
<h2><?php echo get_admin_page_title(); ?></h2>

<h3>Ping</h3>
<?php
	$params = 'TESTING 1234...';
	echo $api->ping( $params );
?>

<h3>Authenticate (XPL Session ID)</h3>
	 <?php
	$result = $api->authenticate('');
	echo( $result );
	?>

<h3>Login</h3>
<pre>
	 <?php
	//$result = $api->login( 88316,'HH_GOLD_TEST', 'HH@2127!' );
	//$result = $api->login( 91473,'lee_iwmf', 'V@2127!' );
	//print_r( $result );
	?>
</pre>

<h3>Currencies</h3>
<pre>
	 <?php
	// $result = $api->get_currencies( null, false );
	// print_r( $result );
	?>
</pre>

<h3>Locations</h3>
<pre>
	 <?php
	//$result = $api->get_locations( null, true );
	//print_r( $result );
	//tourpress_test_child_locations($result->allLocations);
	?>
</pre>

<h3>Product Types</h3>
<pre>
	 <?php
	//$result = $api->get_productTypes( 'ACC', false, true );
	$result = $api->get_productTypes(  );
	//print_r( $result );

	foreach ($result->allProductTypes->XPLProductType as $product_type) {

		echo '<br>'. $product_type->code . ' - ' . $product_type->name;
/*		 	//echo ( "\n".$product_type->name);
		$allFacilities = $product_type->allFacilities->XPLFacility;
		//print_r ($product_type);

		foreach ($product_type->allFacilites->XPLFacility as $facility) {
			echo $facility->name;
		}
		$parent_term = get_term_by( 'name' , $parent->name , 'product_type' );
		if ( !$parent_term ) {
			$parent_term = wp_insert_term( $parent->name , 'product_type' );
		}
		$parent_term_id = $parent_term->term_id;

		if ( !empty($product_type->allFacilities) ) {
			//echo "here";
			foreach ($producttype->allFacilities->XPLFacility as $facility) {
				if ($facility->id != null) {
					//echo ( "\n - ".$facility->name . " " . $facility->id );
				}
			}
		}*/
	}
	//die();
	?>
</pre>

<h3>Booking</h3>
<pre>
	 <?php
	// $result = $api->get_booking( '0802' );
	// print_r( $result );
	?>
</pre>

<h3>Product New</h3>

<pre>
<form id="ExtractEXP"  method="get" action="">
Please enter the product ID<br>
<input id="ProductID" name="ProductID" type="text" maxlength="255" value="<?php echo @$_GET['ProductID']; ?>"/>
<input type="hidden" name="page" value="tourpress_test_api" />
<input id="ExtractJSON" type="submit" name="ExtractJSON" value="Extract JSON" />
</form>
	 <?php
	 if (!empty(@$_GET['ProductID']))
	{
		$ratesFrom 			= 		date("Y-m-d");
		$ratesTo   			= 		date('Y-m-d', strtotime('+3 years'));

		$product_id			= 		@$_GET['ProductID'];

		$response 			= 		$api->get_product($product_id,true,$ratesFrom, $ratesTo);
		//if(!$response){
		//	$response 			= 		$api->get_product($product_id);
	//	}

		$cleanDATA 			= 		json_encode($response);

		//$cleanDATA 			= 		str_replace("\r\n", "", $cleanDATA);
		$cleanDATA 			= 		str_replace("&quot;", '\"', $cleanDATA);


		$_SESSION['clean_data'] = $cleanDATA ;
		echo '<textarea rows="50" cols="100">'.$cleanDATA.'</textarea>';

		//jsonmapping_json_viewer($cleanDATA);

	}


	if ($api->error( $response ) ){
		return;
	}



// 	   	$ratesFrom = date("Y-m-d");
// 	   	$ratesTo   = date('Y-m-d', strtotime('+3 years'));

// 	 	$amended_from = get_option( 'tourpress_last_product_cache' );

// 		//$result = $api->get_productList( null, null, null, null, $amended_from,false, true);

// //$result = $api->get_products(null,null,null,array(9966));

// 		$result=$api->get_product(60,true,$ratesFrom,$ratesTo	);
// 		//$result=$api->get_product(9966);
// 		echo '<pre>';
// 		print_r($result);
// 		echo '</pre>';
// 		//echo json_encode($result);

// 		die();
	//$result = $api->get_productList(null,null,null,null,$amended_from,false,true);
	// echo '*****************************CORFJ<pre>';
	// print_r($result);
	// echo '</pre>';
// $data = array(69785,69786,69788,69787,1828,1939,56371,1941,1943,1942);
// foreach($data as $d){
// $result = $api->get_products(null,null,null,array($d));

/**
*61267,5701,68481,92685,68483,5704,1757,1759,
*11344,11341,1762,11340,74773,11343,
*11346,11345,11342,197,1062,1060,1061,
*1063,86744,86745,119,6618,3946,123,
*121,81091,81092,81089,81090,122,72,
*80,76673,85,89,83,4830,4834,57932,9753,
*59557,57931,4837,57933,57934,9754,4076,
*4080,69011,69009,69010,58924,79023,58928,
*58930,58929,923,930,929,77674,926,77675,
*927,77676,925,77673,7875,933,5785,
*5787,5789,5786,64987,64992,64993,64994,
*1745,11377,11381,34172,11378,11379,11380,1752,
*60,68,69,67,63,70,71,76671,79844,79848,86784,79847,
*79846,79845,79849,79850,4070,66450,4073,
*4071,76231,4075,76232,7068,4074,86683,86686,86687,86688,
*4818,57676,4821,75043,75042,75041,42126,75047,
*4823,6335,34360,75040,4986,8214,8216,8215,8217,8218,8213,
*3542,3543,3544,3545,76599,76596,76597,76598,
*8313,8317,8316,8318,45899,8315,8319,8314,
*34363,34364,34366,34365,81535,81536,1830,1954,1955,
*82311,1956,1953,1951,82309,23,25,76668,26,76667,
*29,27,76665,28,3854,76663,30,76664,
*69785,69786,69788,69787,1828,1939,56371,1941,1943,1942
*
*
*
*
*
*
*
*
*
*
*
*
*
*
*
*
,68483,5704,1757,1759,11344,11341,1762
11340,74773,11343,11346,11345,11342,197,1062,1060,1061,1063,86744,86745,119,6618,3946,123,121,81091,81092,81089,81090,122,72,80,76673,85,89,83,4830,4834,57932,9753,59557,57931,4837,57933,57934,9754,4076,4080,69011,69009,69010,58924,79023,58928,58930,58929,923,930,929,77674,926,77675,927,77676,925,77673,7875,933,5785,5787,5789,5786,64987,64992,64993,64994,1745,11377,11381,34172,11378,11379,11380,1752,60,68,69,67,63,70,71,76671,79844,79848,86784,79847,79846,79845,79849,79850,4070,66450,4073,4071,76231,4075,76232,7068,4074,86683,86686,86687,86688,4818,57676,4821,75043,75042,75041,42126,75047,4823,6335,34360,75040,4986,8214,8216,8215,8217,8218,8213,3542,3543,3544,3545,76599,76596,76597,76598,8313,8317,8316,8318,45899,8315,8319,8314,34363,34364,34366,34365,81535,81536,1830,1954,1955,82311,1956,1953,1951,82309,23,25,76668,26,76667,29,27,76665,28,3854,76663,30,76664,69785,69786,69788,69787,1828,1939,56371,1941,1943,1942
*/

// echo '<pre>';
// print_r($result);
// echo '</pre>';
// die();
// 	if ($api->error( $result ) )
// 		echo $result->__toString();
// 	elseif (empty( $result->allProducts ))
// 		echo "No products to update since " . date('F j, Y', strtotime($amended_from));
// 	else{
// 		//foreach ($allProducts->XPLProduct as $product) {
// 		//	tourpress_update_product();
// 			//wp_schedule_single_event(time() + 60 , 'neo_tourpress_update_products',array('allProducts'=> $result->allProducts,'parent'=>null,'parent_id'=>0,'level'=>0));
// 		//}
// 		tourpress_update_products( $result->allProducts);
// 	}
// 	echo '<br>'. $d;
// }


	//}
	//die();
	//if ($api->error( $result ) )
//		echo 'Error ' . $result->code . ': ' . $result->description;
	//else
	//	print_r( $result );
	die();
	?>
</pre>

<h3>Products</h3>
<pre>
	 <?php
	//$amended_from  = date('Y-m-d', strtotime('-1 days'));
	$amended_from = get_option('tourpress_last_product_cache');

	$result = $api->get_productList( null, null, null, null, $amended_from );
	print_r( $result );
	if ($api->error( $result ) )
		echo 'Error ' . $result->code . ': ' . $result->description;
	else
		tourpress_test_child_products($result->allProducts);
	?>
</pre>

</div>

<?php

}

function tourpress_test_child_products1($allChildren, $parent = null, $parent_id = 0, $level = 0) {
if ( !empty($allChildren) ) {
	foreach ($allChildren->XPLProductSearchResult as $product) {
		//if ($product->id != null) {
			echo ( "\n".substr(" - - - -",0, $level * 2) . " " . $product->productName . " "  . $product->productId );
			//echo ", Search Id: " . $product->searchId;
			echo ", Date From: " . $product->dateFrom  . " to " . $product->dateTo;
			echo ", Price From: " . $product->totalPrice . " " . $product->totalDescription;

			if ( !empty($product->allRates) ) {
				foreach ($product->allRates->XPLProductPrice as $rate) {
					echo "\n   " . $rate->description;
					if( !empty( $rate->allDates )) {
						echo "\n   Dates: " . implode( ",", $rate->allDates->Date );
						echo "\n   Prices: " . implode( ",", $rate->allPrices->Decimal );
						echo "\n   Discounts: " . implode( ",", $rate->allDiscounts->Decimal );
						echo "\n   Statuses: " . implode( ",", $rate->allStatuses->String );
					}
					echo "\n   Total Price: " . $rate->currencyId . " " . $rate->totalPrice;
					echo ", " . $rate->totalStatus;
				}
			}

			// Recurse down the product hierarchy
			tourpress_test_child_products1($product->allChildren, $product, $post_id, $level + 1);
		//}
	}
}
}

function tourpress_test_child_products($allChildren, $parent = null, $parent_id = 0, $level = 0) {
if ( !empty($allChildren) ) {
	foreach ($allChildren->XPLProduct as $product) {
		if ($product->id != null) {
			echo ( "\n".substr(" - - - -",0, $level * 2) . " " . $product->name . " "  . $product->id
			. " " . $parent_id );
			echo ", Search Id: " . $product->searchId;
			echo ", Product Type ID: " . $product->productTypeID;
			echo ", Origin ID: " .  $product->myOrigin->id;

			// $post_arr = get_posts( array(
			// 	'post_type'		=> 'tourpress_product',
			// 	'post_status'	=> 'any',
			//  	'meta_key'		=> 'product_id',
			//  	'meta_value'	=> $product->id ) );

			// // echo ("\n");
			// // print_r ($post_arr);

			// if( $post_arr ) {
			// 	$post = $post_arr[0];
			// 	$post_id = $post->ID;

			// 	//print_r ($post);
			// 	echo " (Updated)";
			// }
			// else {
			// 	$post_id = wp_insert_post ( array (
			// 		'post_type'		=> 'tourpress_product',
			// 		'post_title'	=> $product->name,
			// 		'post_status'	=> 'publish',
			// 		'post_parent'	=> $parent_id
			// 		) );

			// 	echo ( "(Created)" );
			// }

			// Update main fields
			//tourpress_update_product ($post_id, $product);

			echo ", From Price: " . $product->myPriceFrom->price . ' ' . $product->myPriceFrom->priceType;

			// Only interested in product types and locations if the origin exists
			// if( $product->myOrigin !== null ) {

			// 	//echo " - Product Type: " . $product->productTypeID;

			// 	// Product Types
			// 	$args = array(
			// 	    'hide_empty' => false,	// also retrieve terms which are not used yet
			// 	    'meta_query' => array(
			// 	        array(
			// 	           'key'       => 'product_type_id',
			// 	           'value'     => $product->productTypeID,
			// 	           'compare'   => '='
			// 	        )
			// 	    )
			// 	);

			// 	$terms = get_terms( 'product_type', $args );
			// 	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			// 		$term_id = $terms[0]->term_id;
			// 		$term_ids = get_ancestors( $term_id, 'product_type' );	// Get the ancestors
			// 		$term_ids[] = $term_id; 	// Append self
			// 		$term_taxonomy_ids = wp_set_object_terms( $post_id, $term_ids, 'product_type' );
			// 	}

			// 	// Locations
			// 	$args = array(
			// 	    'hide_empty' => false,	// also retrieve terms which are not used yet
			// 	    'meta_query' => array(
			// 	        array(
			// 	           'key'       => 'location_id',
			// 	           'value'     => $product->myOrigin->id,
			// 	           'compare'   => '='
			// 	        )
			// 	    )
			// 	);

			// 	$terms = get_terms( 'location', $args );
			// 	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			// 		$term_id = $terms[0]->term_id;
			// 		$term_ids = get_ancestors( $term_id, 'location' );	// Get the ancestors
			// 		$term_ids[] = $term_id; 	// Append self
			// 		$term_taxonomy_ids = wp_set_object_terms( $post_id, $term_ids, 'location' );
			// 	}

			// }

			// Recurse down the product hierarchy
			tourpress_test_child_products($product->allChildren, $product, $post_id, $level + 1);
		}
	}
}
}

// Recurse location hierarchy
function tourpress_test_child_locations($allChildren, $parent_term = null, $level = 0) {
if ( !empty($allChildren) ) {
	foreach ($allChildren->XPLLocation as $location) {
		if ($location->id != null) {
			echo ( "\n".substr(" - - - -",0, $level * 2) . " " . $location->name . " " . $location->id );
			// $term = term_exists( $location->name, "location" );
			// if ( $term == 0 || $term == null) {
			// 	$term = wp_insert_term( $location->name , 'location' , array( 'parent' => $parent_term['term_id'] ) );
			// 	echo " (created)";
			// }

			// Update meta data
			//update_term_meta( $term[term_id], "location_id" , $location->id );

			// Recurse down the location hierarchy
			tourpress_test_child_locations($location->allChildren, $term, $level + 1);
		}
	}
}
}
