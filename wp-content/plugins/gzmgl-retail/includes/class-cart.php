<?php
class Gzmgl_Retail_Cart {

	static function init() {

		//	Append ?clear-cart to any site url to trigger this
		add_action( 'init', __CLASS__ . '::clear_cart_url' );

		//	Add to Cart and then redirect
		//add_filter( 'woocommerce_add_to_cart_redirect', __CLASS__ . '::add_to_cart_redirect', 90 );

		// Change empty cart "Return to Shop" URL
		add_filter( 'woocommerce_return_to_shop_redirect', __CLASS__ . '::return_to_shop_redirect', 90 );

		// Change "Return to Shop" to "Sell Your Device"
		add_filter( 'gettext',  __CLASS__ . '::translate_text' );

		// Load cart data per page load
		add_filter( 'woocommerce_get_cart_item_from_session', __CLASS__ . '::get_cart_item_from_session', 20, 2 );

		// Add item data to the cart
		add_filter( 'woocommerce_add_cart_item_data', __CLASS__ . '::add_cart_item_data', 10, 2 );

		// Get item data to display in cart
		add_filter( 'woocommerce_get_item_data', __CLASS__ . '::get_item_data', 10, 2 );

		//Get response data to display in cart
		add_filter( 'woocommerce_get_response_data', __CLASS__ . '::get_response_data', 10, 2 );

	}

	//	Append ?clear-cart to any site url to trigger this
	static function clear_cart_url() {
		if ( isset( $_GET['clear-cart'] ) ) {
			WC()->cart->empty_cart();
		}
	}

	//	Add to Cart and then redirect
	static function add_to_cart_redirect() { 
		if ( isset( $_GET['go-checkout'] ) ) {
			if ( WC()->cart->get_cart_contents_count() <= 1 ) {
				return site_url( 'checkout/' );
			} else {
				return site_url( 'cart/' );
			}
		} else if ( isset( $_GET['go-cart'] ) ) {
			return site_url( 'cart/' );
		} else {
			return site_url( '/' );
		}
	     
	}
	
	// Change empty cart "Return to Shop" URL
	static function return_to_shop_redirect() {
		return site_url( '/' );
	}

	// Change "Return to Shop" to "Sell Your Device"
	static function translate_text( $translated ) {
		$translated = str_ireplace('Return to Shop',  'Sell Your Device',  $translated );
		return $translated;
	}

	// Load cart data per page load
	static function get_cart_item_from_session( $cart_item, $values ) {
		if ( ! empty( $values['serial'] ) ) {
			$cart_item['serial'] = $values['serial'];
		}
		return $cart_item;
	}

	// Add item data to the cart
	static function add_cart_item_data( $cart_item_meta, $product_id, $post_data = null ) {
		if ( is_null( $post_data ) ) { $post_data = $_REQUEST;	}

		if ( ! empty( $post_data['serial'] ) ) {
			$cart_item_meta['serial'] = $post_data['serial'];
		}

		return $cart_item_meta;
	}

	// Get item data to display in cart
	static function get_item_data( $other_data, $cart_item ) {
		if ( ! empty( $cart_item['serial'] ) ) {
			$other_data[] = array(
				'name' => __( 'IMEI #', 'gzmgl_retail' ),
				'display' => $cart_item['serial'],
			);
		}
		return $other_data;
	}

	static function get_response_data($response_data,$cart_item){
		if ( ! empty( $cart_item['serial'] ) ) {
			$response_data[] = array(
				'name' => __( 'IMEI #', 'gzmgl_retail' ),
				'display' => $cart_item['serial'],
			);
		}
		return $response_data;
	}

}
Gzmgl_Retail_Cart::init();