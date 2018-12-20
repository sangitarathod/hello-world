<?php
/**
 * Product
 *
 * @package     TourPress
 * @subpackage  Product Functions
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Product Page More Fields (PODS)
add_filter( 'pods_meta_default_box_title', 'slug_pods_metabox_title' );
add_filter( 'pods_api_pre_save_pod_item_tourpress_product', 'tourpress_pre_save_product', 10, 2);

// Test some PODS functionality

//$object = pods(''); 
// $fields = array(); 
  
// // iterate through the fields in this pod 
// foreach($object->fields as $field => $data) { 
//     $fields[$field] = array('label' => $data['label']); 
// } 
  
// // exclude a specific field by field name 
// unset($fields['product_code']); 
  
// // customize the label for a particular field 
// $fields['product_name'] = array( 'label' => 'some_different_label'); 
  
// // hide some fields on edit screen but still have them on the add screen 
// $edit_fields = $fields; 
// unset($edit_fields['test']); 
  
// // fields visible on manage screens 
// //$manage_fields = array('few', 'manage', 'fields'); 
  
// $object->ui = array( 
//     'fields' => array( 
//         'add' => $fields, 
//         'edit' => $edit_fields, 
//         //'manage' => $manage_fields, 
//     ), 
//     //other parameters 
// ); 
  
//pods_ui($object);

// When the user is editing a Product display a box to let them select a TourPress product to link to.
// If this Product has been edited previously also show cached TourPress data
function tourpress_product_edit() {
	global $post;

	$unlinked_products = get_option('tourpress_unlinked_products');
	wp_nonce_field( 'tourpress', 'tourpressnonce', false, true );
	
	if (tourpress_configured() ) {

		$currentId = get_post_meta( $post->ID, 'product_id', true );

		if( $currentId !== null && $currentId > 0) {
			// Retrieve specific product details
			$api = new Explorer_Api();
			$result = $api->authenticate('');
			$result = $api->get_productList( null, null, null, array ( $currentId ) );
		}
		else {
			// Retrieve a set of products based on product type and location
			$product_type = tourpress_get_taxonomy($post->ID, 'tourpress_product_type');
			$location = tourpress_get_taxonomy($post->ID, 'location');
			print "<p>$product_type->name in $location->name</p>";

			$product_type_id = get_term_meta($product_type->term_id, 'product_type_id', true);
			$location_id = get_term_meta($location->term_id, 'location_id', true);
			//print "<p>$product_type_id - $location_id</p>";

			$api = new Explorer_Api();
			$result = $api->authenticate('');
			$result = $api->get_productList( $product_type_id, $location_id );
		}
		?>

		<div class="form-field form-required">
			
		<?php
		if ($currentId == 0) {
		// 	print "<p>Unlinked product</p>";
		//	print '<label><input type="checkbox" id="tourpress_link" value="tourpress_link">Link to TourPress</label><br>';

			//////// TESTING /////////
			// $terms = wp_get_object_terms( $post->ID,  'product_type' );
			// if ( ! empty( $terms ) ) {
			// 	if ( ! is_wp_error( $terms ) ) {
			// 		echo '<ul>';
			// 			foreach( $terms as $term ) {
			// 				$parent = $term->parent;
			// 				$child = in_array ( $parent, $terms );

			// 				echo '<li><a href="' . get_term_link( $term->slug, 'product_type' ) . '">' . esc_html( $term->name ) . '</a></li>';
			// 				echo $parent;
			// 				echo $child; 

							

			// 			}
			// 		//$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
			// 		echo '</ul>';
			// 	}
			// }
			// $term = tourpress_get_taxonomy($post->ID, 'product_type');
			// echo '<li><a href="' . get_term_link( $term->slug, 'product_type' ) . '">' . esc_html( $term->name ) . '</a></li>';
			// echo '<br />';
		}

		if ($api->error( $result ) )  {
			print "<p>Unable to link this Product with a product in TourPress at this time, the following error message was returned:</p>";
		//	var_dump($result);
			if(isset($result->code) && !empty($result->code)){
				print '<p>Error ' . $result->code . ': ' . $result->description . '</p>';
			}
			print '<p>You can find <a href="http://www.tourpress.com/support/api/mp/error_messages.php" target="_blank">explanations of these error messages</a>, view the <a href="http://www.tourpress.com/support/webdesign/wordpress/installation.php" target="_blank">plugin installation instructions</a> or <a href="http://www.tourpress.com/company/contact.php" target="_blank">contact us</a> if you need some help.</p>';

		} else {
			// Product list to select from
			echo '<label for="product_id">Product</label>';
			?>

			<select name="product_id">
				<?php
					if($unlinked_products == 1) {
						print '<option value="0">--- Unlinked ---</option>';
					} 
				
					foreach( $result->allProducts->XPLProduct as $product) {
						print '<option value="'.$product->id.'"';
						if($product->id==$currentId)
							print ' selected="selected"';
						print '>'.$product->name.' ('.$product->code.')</option>';
					}
				?>
			</select>

			<!-- <label><input type="checkbox" id="tourpress_link" value="tourpress_link">Link to TourPress</label> -->

			<?php if($currentId>0) : ?>
			<p><?php 
			(get_option('tourpress_update_frequency')=="") ? $tourpress_update_frequency = 14400 : $tourpress_update_frequency = intval(get_option('tourpress_update_frequency'));
			
			if($tourpress_update_frequency>1) {
				$hours = $tourpress_update_frequency / 3600;
				if($hours > 1)
					$hours = $hours." hours";
				else
					$hours = "hour";
				echo "The following data is refreshed from TourPress each time you save this Product plus automatically every $hours.";
			} else {
				echo "The following data is refreshed from TourPress each time you save this Product.";
			}
			?><br /></p>
			<table class="widefat">
				<thead>
					<tr>
						<th style="width: 175px;">Field</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="row-title" title="[last_updated]">Last updated</td>
						<td class="desc" style="overflow: hidden">
						<?php 
							$last_updated = get_post_meta( $post->ID, 'last_updated', true ); 
							$time_since_update = time() - $last_updated;
								
							echo tourpress_convtime($time_since_update)." ago";	

							//echo date("r", get_post_meta( $post->ID, 'last_updated', true ) );
						?>
						</td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[product_id]">Product ID</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'product_id', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[product_name]">Product Name</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'product_name', true ); ?></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[product_code]">Product Code</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'product_code', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[origin]">Origin</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'origin', true ) . ' ' . get_post_meta( $post->ID, 'geo_origin', true ); ?> <a href="http://maps.google.com/?q=<?php echo !empty( get_post_meta( $post->ID, 'geo_origin', true )) ? get_post_meta( $post->ID, 'geo_origin', true ) : get_post_meta( $post->ID, 'origin', true ); ?>" target="_blank" title="View on Google Maps">&raquo;</a></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[destination]">Destination</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'destination', true ) . ' ' . get_post_meta( $post->ID, 'geo_destination', true ); ?> <a href="http://maps.google.com/?q=<?php echo !empty( get_post_meta( $post->ID, 'geo_destination', true )) ? get_post_meta( $post->ID, 'geo_destination', true ) : get_post_meta( $post->ID, 'destination', true ); ?>" target="_blank" title="View on Google Maps">&raquo;</a></td>
					</tr>

					<tr>
						<td class="row-title" title="[geocode]">Geocode</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'geocode', true ); ?> <a href="http://maps.google.com/?q=<?php echo get_post_meta( $post->ID, 'geocode', true ); ?>" target="_blank" title="View on Google Maps">&raquo;</a></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[address]">Address</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'address', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[email]">Email</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'email', true ); ?></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[phone]">Phone</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'phone', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[fax]">Fax</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'fax', true ); ?></td>
					</tr>

					<tr class="alternate">
						<td class="row-title" title="[grade]">Grade</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'grade', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[star_rating]">Star Rating</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'star_rating', true ); ?></td>
					</tr>

					<tr class="alternate">
						<td class="row-title" title="[from_price]">From Price</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'sale_currency', true ) . ' ' .
						get_post_meta( $post->ID, 'from_price', true ) . ' ' . get_post_meta( $post->ID, 'from_price_type', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[duration]">Duration</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'duration', true ); ?></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[days_of_operation]">Days of Operation</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'days_of_operation', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[capacity_rule]">Capacity</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'capacity_rule', true ); ?></td>
					</tr>		
					<tr class="alternate">
						<td class="row-title" title="[child_policy]">Child Policy</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'child_policy', true ); ?></td>
					</tr>	
					<tr>
						<td class="row-title" title="[checkin_policy]">Checkin Policy</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'checkin_policy', true ); ?></td>
					</tr>	
					<tr class="alternate">
						<td class="row-title" title="[cancellation_policy]">Cancellation Policy</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'cancellation_policy', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[special_text]">Special Text</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'special_text', true ); ?></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[featured_text]">Featured Text</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'featured_text', true ); ?></td>
					</tr>
					<tr>
						<td class="row-title" title="[itinerary_text]">Itinerary Text</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'itinerary_text', true ); ?></td>
					</tr>
					<tr class="alternate">
						<td class="row-title" title="[meta_tags]">Meta Tags</td>
						<td class="desc"><?php echo get_post_meta( $post->ID, 'meta_tags', true ); ?></td>
					</tr>

				</tbody>
			</table>

			<?php
			// Review and reinstate Images PCS 07JUN17
			// Testing
			// $currentId =14699;
			// $product = $api->get_product( $currentId );	
			// echo $product->name . '<br />';
			//tourpress_update_facilities( $post->ID, $product->allFacilities->XPLFacility );
			//print_r ($product);
			// $images = $product->allImages->XPLImage;
			// print_r ($images);
			
			// // // Get service url
			// // $url = get_option('tourpress_service_url');
			// // if ( substr( $url, 0 , 7 ) !== 'http://' ) $url = 'http://' . $url;	// Prepend http:// if missing

			// // Get existing (attached) images
			// echo "<pre>";
			// $attached_media = get_attached_media('image', $post->ID);
			// print_r ($attached_media);
			// echo get_post_thumbnail_id( $post->ID);
			// echo "</pre>";

			// // See if the images are already attached
			// foreach ($images as $image) {
			// 	foreach ($attached_media as $attached) {
			// 		if ($attached->post_name == $image->name) {
			// 			continue 2;
			// 		}
			// 	}

			// 	// Upload the image to the library and attach
			// 	// echo $url.'/'.$image->url;
			// 	// $media = media_sideload_image($url.'/'.$image->url, $post_id);
			// 	// print_r ($media);
			// }

			// echo '<ul>';
			// foreach ($images as $image) {
			// 	echo '<li>'.$image->name.'</li>';
			// }
			// echo '</ul>';

			// // Testing Image Attachments
			// $array = get_attached_media('image', $post->ID);
			// echo '-----<br />';
			// echo '<ul>';
			// foreach ($array as $img) {
			// 	echo '<li>'.$img->post_name.'</li>';
			// }
			// echo '</ul>';
			?>


			<?php else : ?>
			<p>If linked to a TourPress product, additional fields will be displayed here once you have saved this Product.</p>
			<?php endif ?>
			<?php
			}
	?>
			</div>
	<?php
	} else { ?>
		<div class="form-field form-required">
		<p>You must configure the <a href="admin.php?page=tourpress_options">TourPress Settings</a> before you can link this Product to a TourPress product.</p>
		</div>
	<?php
	}
}

// Save custom meta when page is saved
function tourpress_save_product( $post_id, $post ) {		
	// Check nonce and permissions
	if (!wp_verify_nonce( $_POST[ 'tourpressnonce' ], 'tourpress'))
		return;
	if (!current_user_can( 'edit_post', $post_id ))
		return;
	if ($post->post_type != 'page' && $post->post_type != 'product')
		return;
	

	
	// Save Product ID
	$product_id = intval($_POST['product_id']);	
	tourpress_refresh_product( $post_id, $product_id );
}

// PODS...
function tourpress_pre_save_product( $pieces, $is_new_item ) {

    // $test = $pieces[ 'fields' ][ 'test' ][ 'value' ];
    // if($test == null) {
    // 	$pieces[ 'fields' ][ 'test' ][ 'value' ] = "ZZZ - PRE SAVE";
    // }

    return $pieces; 
}

// Get the product's term (with a parent if applicable)
function tourpress_get_taxonomy ( $post_id, $taxonomy ) {

	$terms = wp_get_object_terms( $post_id, $taxonomy );
	if (!empty( $terms ) && !is_wp_error( $terms )) {
		$lastterm = end($terms);
		foreach ($terms as $term) {
			if (in_array ($term->parent, $terms) || $term == $lastterm) {
				return $term;
			}	
		}
	}
}

?>