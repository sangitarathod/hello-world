<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc_return_products_admin{
	public function __construct() {
		add_action( 'woocommerce_admin_html_order_item_class', array($this, 'wc_return_product_css_class'), 5, 3 );
		
		add_action( 'woocommerce_admin_order_items_after_line_items', array($this, 'wc_reson_for_return'), 5 , 1 );
	}
	

	public function wc_return_product_css_class( $class, $item, $order ) {
		$retured_products = get_post_meta( $order->get_id(), 'wc_retured_products', 1 ); 
		if( empty($retured_products) ){
			return;
		}
		if( ! in_array( $item->get_product_id(), $retured_products) ){
			return false;
		}
		return $class.' retuned';
	}

	public function wc_reson_for_return( $order_id ){
		// $reason_return = delete_post_meta( $order_id, 'wc_retured_reson' );
		// $reason_return = delete_post_meta( $order_id, 'wc_retured_products' );

		$retured_products = get_post_meta( $order_id, 'wc_retured_products',1 );
		$reason_return = get_post_meta( $order_id, 'wc_retured_reson',1 );
		if( empty($reason_return) ){
			return;
		}?>
		<tbody id="order_shipping_line_items">
			<tr>
				<td colspan="6" style="background: #f7f5df"><?php echo $reason_return; ?></td>
			</tr>
		</tbody><?php
	}
}
new wc_return_products_admin;