<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Commission
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Commission {

	public function __construct() {
		global $WCFM, $WCFMmp;
		
		// Generating Marketplace Order on WC New Order
		add_action( 'woocommerce_checkout_order_processed', array(&$this, 'wcfmmp_checkout_order_processed'), 30, 3 );
		
		// Update Marketplace Order Status on WC Order Status changed
		add_action( 'woocommerce_order_status_changed', array(&$this, 'wcfmmp_order_status_changed'), 30, 4 );
		
		// Withdrawal Status Completed
		add_action( 'wcfmmp_withdraw_status_completed_by_commission', array(&$this, 'wcfmmp_commission_withdrawal_id_update' ), 10, 2 );
	}
	
	/**
	 * WCfM Marketplace Order create on WC Order Process
	 */
	public function wcfmmp_checkout_order_processed( $order_id, $order_posted, $order ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$order_id ) return;
		if ( get_post_meta($order_id, '_wcfmmp_order_processed', true) ) return;
		
		if (!$order)
      $order = wc_get_order( $order_id );
    
    $customer_id = 0;
    if ( $order->get_user_id() ) 
    	$customer_id = $order->get_user_id();
    
    $payment_method = ! empty( $order->get_payment_method() ) ? $order->get_payment_method() : '';
    $order_status = $order->get_status();
    $shipping_status = 'pending'; 
    $is_withdrawable = 1;
    $is_auto_withdrawal = 0;
    
    $disallow_payment_methods = get_wcfm_marketplace_disallow_active_order_payment_methods();
    if( !empty( $disallow_payment_methods ) && in_array( $payment_method, array_keys( $disallow_payment_methods ) ) ) {
    	$is_auto_withdrawal = 1;
    }
    
    // Ger Shipping Vendor Packages
		$vendor_shipping = $WCFMmp->wcfmmp_shipping->get_order_vendor_shipping( $order );
    
    $items = $order->get_items('line_item');
    if( !empty( $items ) ) {
			foreach( $items as $order_item_id => $item ) {
				$line_item = new WC_Order_Item_Product( $item );
				$product  = $line_item->get_product();
				$product_id = $line_item->get_product_id();
				$variation_id = $line_item->get_variation_id();
				if( $product_id ) {
					$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
					if( $vendor_id ) {
						
						// Updating Order Item meta with Vendor ID
						wc_add_order_item_meta( $order_item_id, '_vendor_id', $vendor_id );
						
						$purchase_price = get_post_meta( $product_id, '_purchase_price', true );
						if( !$purchase_price ) $purchase_price = $product->get_price();
						$shipping_cost = $shipping_tax = 0;
						if ( !empty($vendor_shipping) && isset($vendor_shipping[$vendor_id]) && $product->needs_shipping() ) {
							$shipping_cost = (float) round(($vendor_shipping[$vendor_id]['shipping'] / $vendor_shipping[$vendor_id]['package_qty']) * $line_item->get_quantity(), 2);
							$shipping_tax = (float) round(($vendor_shipping[$vendor_id]['shipping_tax'] / $vendor_shipping[$vendor_id]['package_qty']) * $line_item->get_quantity(), 2);
						}
						if( $WCFMmp->wcfmmp_vendor->is_vendor_deduct_discount( $vendor_id ) ) {
							$commission_amount = $this->wcfmmp_get_order_item_commission( $vendor_id, $product_id, $variation_id, $line_item->get_total(), $line_item->get_quantity() );
						} else {
							$commission_amount = $this->wcfmmp_get_order_item_commission( $vendor_id, $product_id, $variation_id, $line_item->get_subtotal(), $line_item->get_quantity() );
						}
						$discount_amount = 0;
						$discount_type = '';
						$other_amount = 0;
						$other_amount_type = '';
						$withdraw_charges = 0;
						$refunded_amount = 0;
						$total_commission = $commission_amount;
						if( $get_shipping = $WCFMmp->wcfmmp_vendor->is_vendor_get_shipping( $vendor_id ) ) {
							$total_commission += (float) $shipping_cost;
						}
						if( $WCFMmp->wcfmmp_vendor->is_vendor_get_tax( $vendor_id ) ) {
							$total_commission += (float) $line_item->get_total_tax();
							if( $get_shipping ) {
								$total_commission += (float) $shipping_tax;
							}
						}
						if( !$is_auto_withdrawal ) {
							$withdraw_charges = $WCFMmp->wcfmmp_withdraw->calculate_withdrawal_charges( $total_commission, $vendor_id );
						}
						
						$wpdb->query(
										$wpdb->prepare(
											"INSERT INTO `{$wpdb->prefix}wcfm_marketplace_orders` 
													( vendor_id
													, order_id
													, customer_id
													, payment_method
													, product_id
													, variation_id
													, quantity
													, product_price
													, purchase_price
													, item_id
													, item_type
													, item_sub_total
													, item_total
													, shipping
													, tax
													, shipping_tax_amount
													, commission_amount
													, discount_amount
													, discount_type
													, other_amount
													, other_amount_type
													, refunded_amount
													, withdraw_charges
													, total_commission
													, order_status
													, shipping_status 
													, is_withdrawable
													, is_auto_withdrawal
													) VALUES ( %d
													, %d
													, %d
													, %s
													, %d
													, %d 
													, %d
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %s
													, %d
													, %d
													) ON DUPLICATE KEY UPDATE `created` = now()"
											, $vendor_id
											, $order_id
											, $customer_id
											, $payment_method
											, $product_id
											, $variation_id
											, $line_item->get_quantity()
											, $product->get_price()
											, $purchase_price
											, $order_item_id
											, $line_item->get_type()
											, $line_item->get_subtotal()
											, $line_item->get_total()
											, $shipping_cost
											, $line_item->get_total_tax()
											, $shipping_tax
											, $commission_amount
											, $discount_amount
											, $discount_type
											, $other_amount
											, $other_amount_type
											, $refunded_amount
											, $withdraw_charges
											, $total_commission
											, $order_status
											, $shipping_status 
											, $is_withdrawable
											, $is_auto_withdrawal
							)
						);
						$commission_id = $wpdb->insert_id;
						do_action( 'wcfmmp_order_item_processed', $commission_id, $order_id, $order, $vendor_id, $product_id, $order_item_id, $total_commission, $is_auto_withdrawal );
					}
				}
			}
			update_post_meta( $order_id, '_wcfmmp_order_processed', true );
			do_action( 'wcfmmp_order_processed', $order_id, $is_auto_withdrawal );
		}
		return;
	}
	
	/**
	 * Marketplace Order Status update on WC Order status change
	 */
	function wcfmmp_order_status_changed( $order_id, $status_from, $status_to, $order ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		// Update Marketplace Order Status
		$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array('order_status' => $status_to), array('order_id' => $order_id), array('%s'), array('%d'));
		
		// Fetch commission ids for this order
		$sql = 'SELECT ID, is_auto_withdrawal  FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND `order_id` = " . $order_id;
		$commissions = $wpdb->get_results( $sql );
		
		if( $status_to == 'completed' ) {
			if( !empty( $commissions ) ) {
				foreach( $commissions as $commission ) {
					// Update commission ledger status
					$WCFMmp->wcfmmp_vendor->wcfmmp_ledger_status_update( $commission->ID, 'completed' );
					
					// Update auto withdrawal complated
					if( $commission->is_auto_withdrawal ) {
						$WCFMmp->wcfmmp_withdraw->wcfmmp_withdraw_status_update_by_commission( $commission->ID, 'completed' );
					}
					
					do_action( 'wcfmmp_order_status_completed', $order_id, $commission->ID, $order );
				}
			}
		}
		do_action( 'wcfmmp_order_status_updated', $order_id, $status_from, $status_to, $order );
	}
	
	/**
	 * Commission withdrawal Update on complete
	 */
	function wcfmmp_commission_withdrawal_id_update( $withdrawal_id, $commission_id ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$withdrawal_id ) return;
		if( !$commission_id ) return;
		
		// Set Withdrawal ID at Vendor Orders table
		$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array('withdrawal_id' => $withdrawal_id, 'withdraw_status' => 'completed', 'commission_paid_date' => date('Y-m-d H:i:s', time())), array('ID' => $commission_id), array('%d', '%s', '%s'), array('%d'));
	}
	
	/**
	 * Generate commission for an Order Item
	 */
	public function wcfmmp_get_order_item_commission( $vendor_id, $product_id, $variation_id = 0, $item_price, $quantity ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$commission_rule = $WCFMmp->wcfmmp_product->wcfmmp_get_product_commission_rule( $product_id, $variation_id, $vendor_id, $item_price, $quantity );
		//wcfm_log( "commission_rule::" . implode( "=>", $commission_rule ) );
		
		$item_price = apply_filters( 'wcfmmp_order_item_price', $item_price, $product_id, $variation_id, $quantity, $vendor_id );
		
		$item_commission = 0;
		if( $commission_rule && is_array( $commission_rule ) ) {
			switch( $commission_rule['mode'] ) {
				case 'percent':
					$item_commission = $item_price * ((float) $commission_rule['percent']/100);
				break;
				
				case 'fixed':
					$item_commission = (float) $commission_rule['fixed'] * $quantity;
				break;
				
				case 'percent_fixed':
					$item_commission = (float) $item_price * ((float) $commission_rule['percent']/100);
					$item_commission += (float) $commission_rule['fixed'] * $quantity;
				break;
			}
			
			$admin_fee_mode = apply_filters( 'wcfm_is_admin_fee_mode', false );
			if( $admin_fee_mode ) {
				$item_commission = (float) $item_price - (float) $item_commission;
			}
		}
		
		return apply_filters( 'wcfmmp_order_item_commission', $item_commission, $vendor_id, $product_id, $variation_id, $item_price, $quantity, $commission_rule );
	}
	
	/**
	 * Generate Commission Rule by Vednor Sales
	 */
	public function wcfmmp_get_commission_rule_by_sales_rule( $vendor_id, $vendor_commission_sales_rules ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$vendor_id ) return false;
		
		$commission_rule = array( 'mode' => 'fixed', 'percent' => 0, 'fixed' => 0 );
		if( empty( $vendor_commission_sales_rules ) ) return $commission_rule;
		
		$vendor_gross_sales = $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor( $vendor_id, 'all' );
		
		$matched_rule_price = 0;
		foreach( $vendor_commission_sales_rules as $vendor_commission_sales_rule ) {
			$rule_price = $vendor_commission_sales_rule['sales'];
			$rule = $vendor_commission_sales_rule['rule'];
			
			if( ( $rule == 'upto' ) && ( (float)$vendor_gross_sales <= (float)$rule_price ) && ( !$matched_rule_price || ( (float)$rule_price <= (float)$matched_rule_price ) ) ) {
				$matched_rule_price = $rule_price;
				$commission_rule = array( 'mode' => $vendor_commission_sales_rule['type'], 'percent' => $vendor_commission_sales_rule['commission'], 'fixed' => $vendor_commission_sales_rule['commission'] );
			} elseif( ( $rule == 'greater' ) && ( (float)$vendor_gross_sales > (float)$rule_price ) && ( !$matched_rule_price || ( (float)$rule_price >= (float)$matched_rule_price ) ) ) {
				$matched_rule_price = $rule_price;
				$commission_rule = array( 'mode' => $vendor_commission_sales_rule['type'], 'percent' => $vendor_commission_sales_rule['commission'], 'fixed' => $vendor_commission_sales_rule['commission'] );
			}
		}
		return apply_filters( 'wcfmmp_commission_rule_by_sales_rule', $commission_rule, $vendor_id, $vendor_commission_sales_rules );
	}
	
	/**
	 * Generate Commission Rule by Product Price
	 */
	public function wcfmmp_get_commission_rule_by_product_rule( $product_id, $item_price, $quantity, $vendor_commission_product_rules = array() ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$product_id ) return false;
		
		$commission_rule = array( 'mode' => 'fixed', 'percent' => 0, 'fixed' => 0 );
		if( empty( $vendor_commission_product_rules ) ) return $commission_rule;
		
		if( !$item_price ) {
			$product = wc_get_product( $product_id );
			$item_price = (float)$product->get_price() * (int)$quantity;
		}
		
		$matched_rule_price = 0;
		foreach( $vendor_commission_product_rules as $vendor_commission_product_rule ) {
			$rule_price = $vendor_commission_product_rule['cost'];
			$rule = $vendor_commission_product_rule['rule'];
			
			if( ( $rule == 'upto' ) && ( (float) $item_price <= (float)$rule_price ) && ( !$matched_rule_price || ( (float)$rule_price <= (float)$matched_rule_price ) ) ) {
				$matched_rule_price = $rule_price;
				$commission_rule = array( 'mode' => $vendor_commission_product_rule['type'], 'percent' => $vendor_commission_product_rule['commission'], 'fixed' => $vendor_commission_product_rule['commission'] );
			} elseif( ( $rule == 'greater' ) && ( (float) $item_price > (float)$rule_price ) && ( !$matched_rule_price || ( (float)$rule_price >= (float)$matched_rule_price ) ) ) {
				$matched_rule_price = $rule_price;
				$commission_rule = array( 'mode' => $vendor_commission_product_rule['type'], 'percent' => $vendor_commission_product_rule['commission'], 'fixed' => $vendor_commission_product_rule['commission'] );
			}
		}
		return apply_filters( 'wcfmmp_commission_rule_by_sales_rule', $commission_rule, $product_id, $item_price, $quantity, $vendor_commission_sales_rules );
	}
}