<?php
if(!function_exists('wcfmmp_woocommerce_inactive_notice')) {
	function wcfmmp_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM Marketplace is inactive.%s The %sWooCommerce plugin%s must be active for the WCFM Marketplace to work. Please %sinstall & activate WooCommerce%s', 'wc-multivendor-marketplace' ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmmp_wcfm_inactive_notice')) {
	function wcfmmp_wcfm_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM Marketplace is inactive.%s The %sWooCommerce Frontend Manager%s must be active for the WCFM Marketplace to work. Please %sinstall & activate WooCommerce Frontend Manager%s', 'wc-multivendor-marketplace' ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-frontend-manager/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+frontend+manager' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmmp_stripe_phpversion_notice')) {
	function wcfmmp_stripe_phpversion_notice() {
		?>
		<div id="message" class="error">
			<p><?php printf(__("%WCFM Marketplace - Stripe Gateway%s requires PHP 5.3.29 or greater. We recommend upgrading to PHP %s or greater.", 'wc-multivendor-marketplace' ), '<strong>', '</strong>', '5.6' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmmp_stripe_curl_notice')) {
	function wcfmmp_stripe_curl_notice() {
		?>
		<div id="message" class="error">
			<p><?php printf(__("%WCFM Marketplace - Stripe Gateway depends on the %s PHP extension. Please enable it, or ask your hosting provider to enable it.", 'wc-multivendor-marketplace' ), '<strong>', '</strong>', 'curl' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmmp_stripe_mbstring_notice')) {
	function wcfmmp_stripe_mbstring_notice() {
		?>
		<div id="message" class="error">
			<p><?php printf(__("%WCFM Marketplace - Stripe Gateway depends on the %s PHP extension. Please enable it, or ask your hosting provider to enable it.", 'wc-multivendor-marketplace' ), '<strong>', '</strong>', 'mbstring' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmmp_stripe_json_notice')) {
	function wcfmmp_stripe_json_notice() {
		?>
		<div id="message" class="error">
			<p><?php printf(__("%WCFM Marketplace - Stripe Gateway depends on the %s PHP extension. Please enable it, or ask your hosting provider to enable it.", 'wc-multivendor-marketplace' ), '<strong>', '</strong>', 'json' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfm_refund_requests_url')) {
	function wcfm_refund_requests_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_refund_requests_url = wcfm_get_endpoint_url( 'wcfm-refund-requests', '', $wcfm_page );
		return $wcfm_refund_requests_url;
	}
}

if(!function_exists('wcfm_reviews_url')) {
	function wcfm_reviews_url( $reviews_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_reviews_url = wcfm_get_endpoint_url( 'wcfm-reviews', '', $wcfm_page );
		if( $reviews_status ) $get_wcfm_reviews_url = add_query_arg( 'reviews_status', $reviews_status, $get_wcfm_reviews_url );
		return $get_wcfm_reviews_url;
	}
}

if(!function_exists('wcfm_reviews_manage_url')) {
	function wcfm_reviews_manage_url( $review_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_reviews_manage_url = wcfm_get_endpoint_url( 'wcfm-reviews-manage', $review_id, $wcfm_page );
		return $get_wcfm_reviews_manage_url;
	}
}

if(!function_exists('wcfm_ledger_url')) {
	function wcfm_ledger_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_ledger_url = wcfm_get_endpoint_url( 'wcfm-ledger', '', $wcfm_page );
		return $get_wcfm_ledger_url;
	}
}

/**
 * Check if it's a store page
 *
 * @return boolean
 */
function wcfm_is_store_page() {
	$wcfm_store_url = get_option( 'wcfm_store_url', 'store' );
	if ( get_query_var( $wcfm_store_url ) ) {
		return true;
	}
	return false;
}

function wcfmmp_is_store_page() {
	return wcfm_is_store_page();
}

function wcfmmp_is_stores_list_page() {
	return wc_post_content_has_shortcode( 'wcfm_stores' );
}

/**
 * Get store page url of a seller
 *
 * @param int $user_id
 * @return string
 */
function wcfmmp_get_store_url( $user_id ) {
	$userdata = get_userdata( $user_id );
	$user_nicename = ( !false == $userdata ) ? $userdata->user_nicename : '';

	$wcfm_store_url = get_option( 'wcfm_store_url', 'store' );
	return sprintf( '%s/%s/', home_url( '/' . $wcfm_store_url ), $user_nicename );
}

/**
 * Get a store
 *
 * @param  integer $store_id
 *
 * @return \WCFMmp_Store
 */
function wcfmmp_get_store( $store_id = null ) {
	global $WCFMmp;
	if ( ! $store_id ) {
		$store_id = $WCFMmp->vendor_id;
	}
	return new WCFMmp_Store( $store_id );
}

/**
 * Get a store
 *
 * @param  integer $store_id
 *
 * @return \WCFMmp_Store
 */
function wcfmmp_get_store_info( $store_id = null ) {
	$wcfmmp_store = wcfmmp_get_store( $store_id );
	
	return $wcfmmp_store->get_shop_info();
}

/**
 * Display navigation to next/previous pages when applicable
 */
if ( ! function_exists( 'wcfmmp_content_nav' ) ) {
	function wcfmmp_content_nav( $nav_id, $query = null ) {
		global $wp_query, $post;
	
		if ( $query ) {
				$wp_query = $query;
		}
	
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
			return;
	
		?>
		<div id="<?php echo $nav_id; ?>">
	
			<?php if ( $wp_query->max_num_pages > 1 && wcfmmp_is_store_page() ) : ?>
					<?php wcfmmp_page_navi( '', '', $wp_query ); ?>
			<?php endif; ?>
	
		</div><!-- #<?php echo $nav_id; ?> -->
		<?php
	}
}

if ( ! function_exists( 'wcfmmp_page_navi' ) ) {
	function wcfmmp_page_navi( $before = '', $after = '', $wp_query ) {

    $posts_per_page = intval( get_query_var( 'posts_per_page' ) );
    $paged = intval( get_query_var( 'paged' ) );
    $numposts = $wp_query->found_posts;
    $max_page = $wp_query->max_num_pages;
    if ( $numposts <= $posts_per_page ) {
			return;
    }
    if ( empty( $paged ) || $paged == 0 ) {
			$paged = 1;
    }
    $pages_to_show = 7;
    $pages_to_show_minus_1 = $pages_to_show - 1;
    $half_page_start = floor( $pages_to_show_minus_1 / 2 );
    $half_page_end = ceil( $pages_to_show_minus_1 / 2 );
    $start_page = $paged - $half_page_start;
    if ( $start_page <= 0 ) {
        $start_page = 1;
    }
    $end_page = $paged + $half_page_end;
    if ( ($end_page - $start_page) != $pages_to_show_minus_1 ) {
			$end_page = $start_page + $pages_to_show_minus_1;
    }
    if ( $end_page > $max_page ) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
    }
    if ( $start_page <= 0 ) {
			$start_page = 1;
    }

    echo $before . '<div class="paginations"><ul class="wcfmmp-pagination">' . "";
    if ( $paged > 1 ) {
			$first_page_text = "&laquo;";
			echo '<li class="prev"><a href="' . get_pagenum_link() . '" title="First">' . $first_page_text . '</a></li>';
    }

    /*$prevposts = get_previous_posts_link( '&larr; Previous' );
    if ( $prevposts ) {
			echo '<li>' . $prevposts . '</li>';
    } else {
			echo '<li class="disabled"><a href="#">' . __( '&larr; Previous', 'wc-multivendor-marketplace' ) . '</a></li>';
    }*/

    for ($i = $start_page; $i <= $end_page; $i++) {
			if ( $i == $paged ) {
				echo '<li><a href="#" class="active">' . $i . '</a></li>';
			} else {
				echo '<li><a href="' . get_pagenum_link( $i ) . '">' . number_format_i18n( $i ) . '</a></li>';
			}
    }
    //echo '<li class="">';
    //next_posts_link( __('Next &rarr;', 'wc-multivendor-marketplace') );
   // echo '</li>';
    //if ( $end_page < $max_page ) {
			$last_page_text = "&raquo;";
			echo '<li class="next"><a href="' . get_pagenum_link( $max_page ) . '" title="Last">' . $last_page_text . '</a></li>';
    //}
    echo '</ul></div>' . $after . "";
  }
}

/**
 * Get active withdraw order status.
 *
 * Default is 'completed', 'processing', 'on-hold'
 *
 */
function wcfmmp_withdraw_get_active_order_status() {
	$order_status  = get_option( 'wcfm_withdraw_order_status', array( 'wc-completed' ) );
	$saving_status = array();

	foreach ( $order_status as $key => $status ) {
		if ( ! empty( $status ) ) {
			$saving_status[] = $status;
		}
	}

	return apply_filters( 'wcfm_withdraw_active_status', $saving_status );
}

/**
 * get comma seperated value from "wcfmmp_withdraw_get_active_order_status()" return array
 * @param array array
 */
function wcfmmp_withdraw_get_active_order_status_in_comma() {
	$order_status = wcfmmp_withdraw_get_active_order_status();
	$status = "'" . implode("', '", $order_status ) . "'";
	return $status;
}

/**
 * get commission types
 */
function get_wcfm_marketplace_commission_types() {
	$commission_type = array(
													'percent'       => __( 'Percent', 'wc-multivendor-marketplace' ),
													'fixed'         => __( 'Fixed', 'wc-multivendor-marketplace' ),
													'percent_fixed' => __( 'Percent + Fixed', 'wc-multivendor-marketplace' ),
													'by_sales'      => __( 'By Vendor Sales', 'wc-multivendor-marketplace' ),
													'by_products'   => __( 'By Product Price', 'wc-multivendor-marketplace' ),
													);
	
	return apply_filters( 'wcfm_marketplace_commission_types', $commission_type );
}

if(!function_exists('get_wcfm_marketplace_withdrwal_payment_methods')) {
	function get_wcfm_marketplace_withdrwal_payment_methods() {
		$marketplace_withdrwal_payment_methods = array( 
			                                      'paypal'        => __( 'PayPal', 'wc-frontend-manager' ),
			                                      'skrill'        => __( 'Skrill', 'wc-multivendor-marketplace' ),
			                                      'stripe'        => __( 'Stripe', 'wc-frontend-manager' ), 
			                                      'bank_transfer' => __( 'Bank Transfer', 'wc-multivendor-marketplace' ) 
			                                      );
		return apply_filters( 'wcfm_marketplace_withdrwal_payment_methods', $marketplace_withdrwal_payment_methods );
	}
}

if(!function_exists('get_wcfm_marketplace_active_withdrwal_payment_methods')) {
	function get_wcfm_marketplace_active_withdrwal_payment_methods() {
		$wcfm_marketplace_withdrwal_payment_methods = get_wcfm_marketplace_withdrwal_payment_methods();
		$wcfm_marketplace_active_withdrwal_payment_methods = array();
		$wcfm_withdrawal_options = get_option( 'wcfm_withdrawal_options', array() );
		$payment_methods = isset( $wcfm_withdrawal_options['payment_methods'] ) ? $wcfm_withdrawal_options['payment_methods'] : array( 'paypal', 'bank_transfer' );
		
		foreach( $wcfm_marketplace_withdrwal_payment_methods as $wcfm_marketplace_withdrwal_payment_method_key => $wcfm_marketplace_withdrwal_payment_method ) {
			if( in_array( $wcfm_marketplace_withdrwal_payment_method_key, $payment_methods ) ) {
				$wcfm_marketplace_active_withdrwal_payment_methods[$wcfm_marketplace_withdrwal_payment_method_key] = $wcfm_marketplace_withdrwal_payment_method;
			}
		}
		return apply_filters( 'wcfm_marketplace_active_withdrwal_payment_methods', $wcfm_marketplace_active_withdrwal_payment_methods );
	}
}

if(!function_exists('get_wcfm_marketplace_withdrwal_order_status')) {
	function get_wcfm_marketplace_withdrwal_order_status() {
		$marketplace_withdrwal_order_status = array( 
			                                      'wc-completed'     => __( 'Completed', 'wc-multivendor-marketplace' ), 
			                                      'wc-processing'    => __( 'Processing', 'wc-multivendor-marketplace' ),
			                                      'wc-pending'       => __( 'Pending', 'wc-multivendor-marketplace' ),
			                                      'wc-on-hold'       => __( 'On Hold', 'wc-multivendor-marketplace' ) 
			                                      );
		return apply_filters( 'wcfm_marketplace_withdrwal_order_status', $marketplace_withdrwal_order_status );
	}
}

if(!function_exists('get_wcfm_marketplace_active_withdrwal_order_status')) {
	function get_wcfm_marketplace_active_withdrwal_order_status() {
		$wcfm_marketplace_withdrawal_order_status = get_wcfm_marketplace_withdrwal_order_status();
		$wcfm_marketplace_active_withdrawal_order_status = array();
		$wcfm_withdrawal_options = get_option( 'wcfm_withdrawal_options', array() );
		$order_status = isset( $wcfm_withdrawal_options['order_status'] ) ? $wcfm_withdrawal_options['order_status'] : array( 'wc-completed' );
		
		foreach( $wcfm_marketplace_withdrawal_order_status as $wcfm_marketplace_withdrawal_order_status_key => $wcfm_marketplace_withdrawal_order_stat ) {
			if( in_array( $wcfm_marketplace_withdrawal_order_status_key, $order_status ) ) {
				$wcfm_marketplace_active_withdrawal_order_status[$wcfm_marketplace_withdrawal_order_status_key] = $wcfm_marketplace_withdrawal_order_stat;
			}
		}
		return apply_filters( 'wcfm_marketplace_active_withdrawal_order_status', $wcfm_marketplace_active_withdrawal_order_status );
	}
}

if(!function_exists('get_wcfm_marketplace_active_withdrwal_order_status_in_comma')) {
	function get_wcfm_marketplace_active_withdrwal_order_status_in_comma() {
		$get_wcfm_marketplace_active_withdrwal_order_status = get_wcfm_marketplace_active_withdrwal_order_status();
		$wcfm_marketplace_active_withdrwal_order_status_in_comma = "'" . implode("', '", array_keys($get_wcfm_marketplace_active_withdrwal_order_status) ) . "'";
		$wcfm_marketplace_active_withdrwal_order_status_in_comma = str_replace( "wc-", "", $wcfm_marketplace_active_withdrwal_order_status_in_comma );
		return apply_filters( 'wcfm_marketplace_active_withdrwal_order_status_in_comma', $wcfm_marketplace_active_withdrwal_order_status_in_comma );
	}
}

if(!function_exists('get_wcfm_marketplace_disallow_order_payment_methods')) {
	function get_wcfm_marketplace_disallow_order_payment_methods() {
		$wcfm_marketplace_disallow_order_payment_methods = array();
		
		if ( WC()->payment_gateways() ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
			foreach( $payment_gateways as $payment_method => $payment_gateway ) {
				$wcfm_marketplace_disallow_order_payment_methods[$payment_method] = esc_html( $payment_gateway->get_title() );
			}
		} else {
			$wcfm_marketplace_disallow_order_payment_methods = array();
		}
			                                      
		return apply_filters( 'wcfm_marketplace_disallow_order_payment_methods', $wcfm_marketplace_disallow_order_payment_methods );
	}
}

if(!function_exists('get_wcfm_marketplace_disallow_active_order_payment_methods')) {
	function get_wcfm_marketplace_disallow_active_order_payment_methods() {
		$wcfm_marketplace_disallow_order_payment_methods = get_wcfm_marketplace_disallow_order_payment_methods();
		$wcfm_marketplace_disallow_active_order_payment_methods = array();
		$wcfm_withdrawal_options = get_option( 'wcfm_withdrawal_options', array() );
		$disallow_order_payment_methods = isset( $wcfm_withdrawal_options['disallow_order_payment_methods'] ) ? $wcfm_withdrawal_options['disallow_order_payment_methods'] : array();
		
		foreach( $wcfm_marketplace_disallow_order_payment_methods as $wcfm_marketplace_disallow_order_payment_method_key => $wcfm_marketplace_disallow_order_payment_method ) {
			if( in_array( $wcfm_marketplace_disallow_order_payment_method_key, $disallow_order_payment_methods ) ) {
				$wcfm_marketplace_disallow_active_order_payment_methods[$wcfm_marketplace_disallow_order_payment_method_key] = $wcfm_marketplace_disallow_order_payment_method;
			}
		}
		return apply_filters( 'wcfm_marketplace_disallow_active_order_payment_methods', $wcfm_marketplace_disallow_active_order_payment_methods );
	}
}

if(!function_exists('get_wcfm_marketplace_default_review_categories')) {
	function get_wcfm_marketplace_default_review_categories() {
		$default_review_categories = array( 
																				array('category'       => __( 'Feature', 'wc-multivendor-marketplace' )),
																				array('category'       => __( 'Varity', 'wc-multivendor-marketplace' )),
																				array('category'       => __( 'Flexibility', 'wc-multivendor-marketplace' )),
																				array('category'       => __( 'Delivery', 'wc-multivendor-marketplace' )),
																				array('category'       => __( 'Support', 'wc-frontend-manager' )), 
																				);
		return apply_filters( 'wcfm_marketplace_default_review_categories', $default_review_categories );
	}
}

if(!function_exists('get_wcfm_marketplace_default_widgets')) {
	function get_wcfm_marketplace_default_widgets() {
		$default_widgets = array( 
															'store-location'            => __( 'Store Location', 'wc-multivendor-marketplace' ),
															'store-category'            => __( 'Store Category', 'wc-multivendor-marketplace' ),
															'store-top-products'        => __( 'Store Top Products', 'wc-multivendor-marketplace' ),
															'store-top-rated-products'  => __( 'Store Top Rated Products', 'wc-multivendor-marketplace' ),
															'store-recent-products'     => __( 'Store Recent Products', 'wc-multivendor-marketplace' ),
															'store-featured-products'   => __( 'Store Featured Products', 'wc-multivendor-marketplace' ),
															'store-on-sale-products'    => __( 'Store On Sale Products', 'wc-multivendor-marketplace' ),
															);
		return apply_filters( 'wcfm_marketplace_default_widgets', $default_widgets );
	}
}

if(!function_exists('get_wcfm_marketplace_active_review_categories')) {
	function get_wcfm_marketplace_active_review_categories() {
		global $WCFMmp;
		$wcfm_default_review_categories = get_wcfm_marketplace_default_review_categories();
		$wcfm_review_categories = isset( $WCFMmp->wcfmmp_review_options['review_categories'] ) ? $WCFMmp->wcfmmp_review_options['review_categories'] : $wcfm_default_review_categories;
		return $wcfm_review_categories;
	}
}

if(!function_exists('get_wcfm_reviews_messages')) {
	function get_wcfm_reviews_messages() {
		global $WCFM;
		
		$messages = array(
											'no_comment'                  => __( 'Please insert your comment before submit.', 'wc-multivendor-marketplace' ),
											'review_saved'       		      => __( 'Your review successfully submited.', 'wc-multivendor-marketplace' ),
											'review_response_saved'       => __( 'Your review response successfully submited.', 'wc-multivendor-marketplace' ),
											'refund_requests_failed'      => __( 'Your refund request failed, please try after sometime.', 'wc-multivendor-marketplace' ),
											'refund_requests_approved'    => __( 'Refund requests successfully approved.', 'wc-multivendor-marketplace' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_refund_requests_messages')) {
	function get_wcfm_refund_requests_messages() {
		global $WCFM;
		
		$messages = array(
											'no_refund_reason'            => __( 'Please insert your refund reason before submit.', 'wc-multivendor-marketplace' ),
											'refund_requests_saved'       => __( 'Your refund request successfully sent.', 'wc-multivendor-marketplace' ),
											'refund_requests_failed'      => __( 'Your refund request failed, please try after sometime.', 'wc-multivendor-marketplace' ),
											'refund_requests_approved'    => __( 'Refund requests successfully approved.', 'wc-multivendor-marketplace' ),
											);
		
		return $messages;
	}
}

/**
 * WCFM Product Mutivendor Tab - tab manager support
 *
 * @since		1.0.1
 */
function wcfm_product_multivendor_tab( $tabs) {
	global $WCFM, $WCFMmp;
	if( apply_filters( 'wcfm_is_pref_product_multivendor', true ) ) {
		$tabs['wcfm_product_multivendor_tab'] = apply_filters( 'wcfm_product_multivendor_tab_element',array(
																																								'title' 	=> __( 'More Offers', 'wc-multivendor-marketplace' ),
																																								'priority' 	=> apply_filters( 'wcfm_product_multivendor_tab_priority', 98 ),
																																								'callback' 	=> array( $WCFMmp->wcfmmp_product_multivendor, 'wcfmmp_product_multivendor_tab_content' )
																																							) );
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'wcfm_product_multivendor_tab', 98 );

/**
 * Single Product Location - GEO my WP suppport
 */
function wcfmmp_product_location_show() {
	global $product;
	if( $product && is_object( $product ) ) {
		echo do_shortcode( '[gmw_single_location map_width="100%"]' );
	}
}
function wcfmmp_location_product_tab( $tabs ) {
	global $WCFM, $product;
	if( $product ) {
		$tabs['wcfm_location_tab'] = array(
																			'title' 	=> 'Location',
																			'priority' 	=> apply_filters( 'wcfm_location_tab_priority', 97 ),
																			'callback' 	=> 'wcfmmp_product_location_show'
																		  );
	}
	return $tabs;
}
if( WCFM_Dependencies::wcfm_geo_my_wp_plugin_active_check() && apply_filters( 'wcfm_is_allow_product_location_display', false ) ) {
	add_filter( 'woocommerce_product_tabs', 'wcfmmp_location_product_tab', 97 );
}

if(!function_exists('wcfmmp_log')) {
	function wcfmmp_log( $message, $level = 'debug' ) {
		wcfm_create_log( $message, $level );
	}
}

//Shipping Functions

if(!function_exists('wcfmmp_get_shipping_types')) {
  function wcfmmp_get_shipping_types() {
    $shipping_type = array(
        '' => __( 'Select Shipping Type...', 'wc-multivendor-marketplace' ),
        'by_country' => __( 'Shipping by Country', 'wc-multivendor-marketplace' ),
        'by_zone' => __( 'Shipping by Zone', 'wc-multivendor-marketplace' ),
    );
    return apply_filters( 'wcfmmp_shipping_types', $shipping_type );
  }
}

if(!function_exists('wcfmmp_get_shipping_processing_times')) {
  function wcfmmp_get_shipping_processing_times() {
      $processing_times = array(
          '' => __( 'Ready to ship in...', 'wc-multivendor-marketplace' ),
          '1' => __( '1 business day', 'wc-multivendor-marketplace' ),
          '2' => __( '1-2 business day', 'wc-multivendor-marketplace' ),
          '3' => __( '1-3 business day', 'wc-multivendor-marketplace' ),
          '4' => __( '3-5 business day', 'wc-multivendor-marketplace' ),
          '5' => __( '1-2 weeks', 'wc-multivendor-marketplace' ),
          '6' => __( '2-3 weeks', 'wc-multivendor-marketplace' ),
          '7' => __( '3-4 weeks', 'wc-multivendor-marketplace' ),
          '8' => __( '4-6 weeks', 'wc-multivendor-marketplace' ),
          '9' => __( '6-8 weeks', 'wc-multivendor-marketplace' ),
      );

      return apply_filters( 'wcfmmp_shipping_processing_times', $processing_times );
  }
}

if(!function_exists('wcfmmp_is_shipping_enabled')) {
  function wcfmmp_is_shipping_enabled( $vendor_id ) {
    global  $WCFMmp;
    $vendor_shipping_details = get_user_meta( $vendor_id, '_wcfmmp_shipping', true );
    if(!empty($vendor_shipping_details)){
      $enabled = $vendor_shipping_details['_wcfmmp_user_shipping_enable'];
      $type = $vendor_shipping_details['_wcfmmp_user_shipping_type'];
      if ( ( !empty($enabled) && $enabled == 'yes' ) && ( !empty($type) ) && '' != $type ) {
          return true;
      }
    }

    return false;
  }
}

/**
  * Get shipping zone
  *
  * @since 1.0.0
  *
  * @return void
  */

if(!function_exists('wcfmmp_get_shipping_zone')) {
  function wcfmmp_get_shipping_zone($zoneID = '' ) {
    if ( isset( $zoneID ) && $zoneID != '' ) {
        $zones = WCFMmp_Shipping_Zone::get_zone( $zoneID );
    } else {
        $zones = WCFMmp_Shipping_Zone::get_zones();
    }
    return $zones;
  }
}

function wcfmmp_convert_to_array($a) {
  return (array) $a;
}

if(!function_exists('wcfmmp_state_key_alter')) {
  function wcfmmp_state_key_alter(&$value, $key) {
    $value = array_combine(
    array_map(function($k) use ($key){ return $key. ':' .$k; }, array_keys($value)),
        $value
    );
  }
}
?>