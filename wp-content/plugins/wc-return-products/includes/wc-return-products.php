<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc_return_products{
	public function __construct() {
		// Add form to each order in user account
		add_action( 'woocommerce_order_details_after_order_table', array($this, 'wc_return_form_template'), 5 , 1 );

		if ( isset( $_GET['return_product_ids'] ) ) {
			add_action('init', array($this, 'wc_process_return_products'));
		}
	}
	

	public function wc_return_form_template( $order='' ) {
	// Get WooCommerce Global
		global $woocommerce;
		global $wp;
		$order_id = isset($wp->query_vars['view-order']) ? $wp->query_vars['view-order'] : '';

		$generate_url = home_url("/my-account/view-order/$order_id/?retun_order=$order_id");

		include('html-wc-return-form-template.php');
	}

	public function wc_process_return_products(){
		global $woocommerce;
		global $wp;
		$order_id = isset( $_GET['retun_order'] ) ? $_GET['retun_order'] : '';
		if(!$order_id){ ?>
			<div id="message" class="notice notice-error is-dismissible">
				<p>The product could not return, Invelid order</p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div><?php
			return false;
		}
	    $return_product_ids      =   json_decode(stripslashes(html_entity_decode($_GET["return_product_ids"])));
		$already_returned = get_post_meta( $order_id, 'wc_retured_products',1 );
		$already_returned = !empty( $already_returned ) ? $already_returned : array();

		update_post_meta( $order_id, 'wc_retured_products', array_unique( array_merge($already_returned, $return_product_ids) ) );
		update_post_meta( $order_id, 'wc_retured_reson', $_GET['reson'] );

		$order = new WC_Order($order_id);
		if (!empty($order)) {
		    $order->update_status( 'completed' );
		}
		
		wp_redirect( home_url("/my-account/view-order/$order_id/") );
		exit();
	}
}
new wc_return_products;