<?php
/**
 * WCFM plugin view
 *
 * WCfM Refund popup View
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/views/refund
 * @version   1.0.0
 */
 
global $wp, $WCFM, $WCFMmp, $_POST, $wpdb;

$item_id       = sanitize_text_field( $_POST['item_id'] );
$order_id      = sanitize_text_field( $_POST['order_id'] );
$commission_id = sanitize_text_field( $_POST['commission_id'] );
$order_id = str_replace( '#', '', $order_id );

if( !$order_id ) return;


$order                  = wc_get_order( $order_id );
$line_items             = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
$product_items          = array();
foreach ( $line_items as $order_item_id => $item ) {
	$product_items[$order_item_id] = $item->get_name() . ' (' . $item->get_total() . ' ' . $order->get_currency() . ')';
}

$wcfm_refund_product_attr = array( 'style' => 'width: 95%;' );
if( $item_id ) {
	$wcfm_refund_product_attr = array( 'style' => 'width: 95%;', 'readonly' => true );
}

?>

<h2 class="wcfm-refund-heading"><?php _e( 'Refund Request', 'wc-multivendor-marketplace' ); ?></h2>
<div class="wcfm-clearfix"></div><br />
<div class="refund_form_wrapper_hide">
	<div id="refund_form_wrapper">
		<div id="wcfm_refund_form_wrapper">
			<div id="respond" class="comment-respond">
				<form action="" method="post" id="wcfm_refund_requests_form" class="refund-form" novalidate="">
					<p class="wcfm-refund-form-product">
						<label for="wcfm_refund_product"><?php _e( 'Product', 'wc-multivendor-marketplace' ); ?> <span class="required">*</span></label> 
						<?php $WCFM->wcfm_fields->wcfm_generate_form_field( array( "wcfm_refund_product" => array( 'type' => 'select', 'attributes' => array( 'style' => 'width: 95%;' ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'options' => $product_items , 'value' => $item_id ) ) ); ?>
					</p>
					
					<p class="wcfm-refund-form-request">
						<label for="wcfm_refund_request"><?php _e( 'Refund Requests', 'wc-multivendor-marketplace' ); ?> <span class="required">*</span></label> 
						<?php $WCFM->wcfm_fields->wcfm_generate_form_field( array( "wcfm_refund_request" => array( 'type' => 'select', 'attributes' => array( 'style' => 'width: 95%;' ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'options' => array( 'full' => __( 'Full Refund', 'wc-multivendor-marketplace' ), 'partial' => __( 'Partial Refund', 'wc-multivendor-marketplace' ) ) ) ) ); ?>
					</p>
					
					<p class="wcfm-refund-form-request-amount">
						<label for="wcfm_refund_request_amount"><?php _e( 'Refund Amount', 'wc-multivendor-marketplace' ); ?> <span class="required">*</span></label> 
						<?php $WCFM->wcfm_fields->wcfm_generate_form_field( array( "wcfm_refund_request_amount" => array( 'type' => 'number', 'attributes' => array( 'style' => 'width: 95%;', 'min' => '1', 'step' => '1' ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'value' => '1' ) ) ); ?>
					</p>
				
					<p class="wcfm-refund-form-reason">
						<label for="comment"><?php _e( 'Refund Requests Reason', 'wc-multivendor-marketplace' ); ?> <span class="required">*</span></label>
						<textarea id="wcfm_refund_reason" name="wcfm_refund_reason" style="width: 95%;" aria-required="true" required=""></textarea>
					</p>
					
					<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
						<div class="wcfm_clearfix"></div>
						<div class="wcfm_gglcptch_wrapper" style="width: 100%;">
						  <?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_refund_requests_form' ); ?>
						</div>
					<?php } ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm-message" tabindex="-1"></div>
					<div class="wcfm_clearfix"></div><br />
					
					<p class="form-submit">
						<input name="submit" type="submit" id="wcfm_refund_requests_submit_button" class="submit" value="<?php _e( 'Submit', 'wc-multivendor-marketplace' ); ?>"> 
						<input type="hidden" name="wcfm_refund_order_id" value="<?php echo $order_id; ?>" id="wcfm_refund_order_id">
						<input type="hidden" name="wcfm_refund_commission_id" value="<?php echo $commission_id; ?>" id="wcfm_refund_commission_id">
					</p>	
				</form>
			</div><!-- #respond -->
		</div>
	</div>
</div>
<div class="wcfm-clearfix"></div>