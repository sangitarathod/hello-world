<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Product
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Product {
	
	public function __construct() {
		global $WCFM;
		
		// Update Vendor Categories
		add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfmmp_update_vendor_categories' ), 10, 2 );
		
		apply_filters( 'wcfm_is_allow_new_product_notification_email', array( &$this, 'wcfmmp_new_product_notification_email' ) );
		
		// Product Manage Page
		if( !wcfm_is_vendor() && apply_filters( 'wcfm_is_allow_commission_manage', true ) ) {
			add_action( 'end_wcfm_products_manage', array( &$this, 'wcfmmp_product_commission' ), 500 );
			add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfmmp_product_commission_save' ), 500, 2 );
			
			// Variation Commission
			add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfmmp_commission_fields_variations' ), 500, 7 );
			add_filter( 'wcfm_variation_edit_data', array( &$this, 'wcfmmp_commission_data_variations' ), 500, 3 );
			add_filter( 'wcfm_product_variation_data_factory', array( &$this, 'wcfmmp_product_variation_commission_save' ), 500, 5 );
		}
		
		// Product Specific Shipping Settings
		add_filter( 'wcfm_product_manage_fields_shipping', array( &$this, 'wcfmmp_product_manage_fields_shipping' ), 10, 2 );
		add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfmmp_shipping_product_meta_save' ), 150, 2 );
		
	}
	
	/**
	 * Update vendor category list
	 */
	function wcfmmp_update_vendor_categories( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM, $WCFMmp;
		
		if( wcfm_is_vendor( $WCFMmp->vendor_id ) ) {
			if(isset($wcfm_products_manage_form_data['product_cats']) && !empty($wcfm_products_manage_form_data['product_cats'])) {
				$vendor_categories = get_user_meta( $WCFMmp->vendor_id, '_wcfm_store_product_cats', true );
				if( !$vendor_categories ) $vendor_categories = array();
				$vendor_categories = array_merge( $vendor_categories, $wcfm_products_manage_form_data['product_cats'] );
				update_user_meta( $WCFMmp->vendor_id, '_wcfm_store_product_cats', array_unique($vendor_categories) );
			}
			
			// Custom Taxonomies
			if(isset($wcfm_products_manage_form_data['product_custom_taxonomies']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies'])) {
				foreach($wcfm_products_manage_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
					if( !empty( $taxonomy_values ) ) {
						$vendor_taxonomies = get_user_meta( $WCFMmp->vendor_id, '_wcfm_store_'.$taxonomy, true );
						if( !$vendor_taxonomies ) $vendor_taxonomies = array();
						$vendor_taxonomies = array_merge( $vendor_taxonomies, $taxonomy_values );
						update_user_meta( $WCFMmp->vendor_id, '_wcfm_store_'.$taxonomy, array_unique($vendor_taxonomies) );
					}
				}
			}
		}
	}
	
	/**
	 * Product waiting for approval notificatiion email to Admin
	 */
	function wcfmmp_new_product_notification_email( $is_allow ) {
		return true;
	}
	
	// Commision setup
	function wcfmmp_product_commission( $product_id ) {
		global $WCFM, $WCFMmp;
		
		$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		$wcfm_commission_types = array_merge( array( 'global' => __( 'By Global Rule', 'wc-multivendor-marketplace' ) ), $wcfm_commission_types );
		if( isset( $wcfm_commission_types['by_sales'] ) ) unset( $wcfm_commission_types['by_sales'] );
		if( isset( $wcfm_commission_types['by_products'] ) ) unset( $wcfm_commission_types['by_products'] );
		
		$vendor_commission_mode = 'global';
		$vendor_commission_fixed = '';
		$vendor_commission_percent = '';
		if( $product_id  ) {
			$product_commission_data = get_post_meta( $product_id, '_wcfmmp_commission', true );
			if( empty($product_commission_data) ) $product_commission_data = array();
			
			$vendor_commission_mode = isset( $product_commission_data['commission_mode'] ) ? $product_commission_data['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $product_commission_data['commission_fixed'] ) ? $product_commission_data['commission_fixed'] : '';
			$vendor_commission_percent = isset( $product_commission_data['commission_percent'] ) ? $product_commission_data['commission_percent'] : '90';
		}
		?>
		<!-- collapsible 12 - WCV Commission Support -->
		<div class="page_collapsible products_manage_commission simple variable grouped external booking" id="wcfm_products_manage_form_commission_head"><label class="fa fa-percent"></label><?php _e('Commission', 'wc-frontend-manager'); ?><span></span></div>
		<div class="wcfm-container simple variable external grouped booking">
			<div id="wcfm_products_manage_form_commission_expander" class="wcfm-content">
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_commission', array(
					                                                                        "vendor_commission_mode" => array('label' => __('Commission Mode', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => $vendor_commission_mode, 'hints' => __( 'Keep this as Global to apply commission rule as per vendor or marketplace commission setup.', 'wc-multivendor-marketplace' ) ),
					                                                                        "vendor_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_percent]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => $vendor_commission_percent, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_fixed" => array('label' => __('Commission Fixed', 'wc-multivendor-marketplace') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'commission[commission_fixed]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => $vendor_commission_fixed, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																									) ) );
				?>
			</div>
		</div>
		<!-- end collapsible -->
		<div class="wcfm_clearfix"></div>
		<?php
	}
	
	// Commision Save
	function wcfmmp_product_commission_save( $new_product_id, $wcfm_products_manage_form_data ) {
		if( isset( $wcfm_products_manage_form_data['commission'] ) && !empty( $wcfm_products_manage_form_data['commission'] ) ) {
			update_post_meta( $new_product_id, '_wcfmmp_commission', $wcfm_products_manage_form_data['commission'] );
		}
	}
	
	// Variation commission option
	function wcfmmp_commission_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options, $products_array, $product_id, $product_type ) {
		global $WCFM, $WCFMmp;
		
		$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		$wcfm_commission_types = array_merge( array( 'global' => __( 'By Global Rule', 'wc-multivendor-marketplace' ) ), $wcfm_commission_types );
		if( isset( $wcfm_commission_types['by_sales'] ) ) unset( $wcfm_commission_types['by_sales'] );
		if( isset( $wcfm_commission_types['by_products'] ) ) unset( $wcfm_commission_types['by_products'] );
		
		$wcfm_variation_commission_fields = apply_filters( 'wcfm_marketplace_settings_fields_commission', array(
					                                                                        "vendor_commission_mode" => array('label' => __('Commission Mode', 'wc-multivendor-marketplace'), 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele variable var_commission_mode', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'Keep this as Global to apply commission rule as per vendor or marketplace commission setup.', 'wc-multivendor-marketplace' ) ),
					                                                                        "vendor_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele variable var_commission_mode_field var_commission_mode_percent var_commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele variable var_commission_mode_field var_commission_mode_percent var_commission_mode_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_fixed" => array('label' => __('Commission Fixed', 'wc-multivendor-marketplace') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele variable var_commission_mode_field var_commission_mode_fixed var_commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele variable var_commission_mode_field var_commission_mode_fixed var_commission_mode_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																									) );
		
		$variation_fileds = array_merge( $variation_fileds, $wcfm_variation_commission_fields );
		
		return $variation_fileds;
	}
	
	function wcfmmp_commission_data_variations( $variations, $variation_id, $variation_id_key ) {
		global $WCFM, $WCFMmp;
		
		if( $variation_id  ) {
			$variation_commission_data = get_post_meta( $variation_id, '_wcfmmp_commission', true );
			if( empty($variation_commission_data) ) $variation_commission_data = array();
			
			$vendor_commission_mode = isset( $variation_commission_data['commission_mode'] ) ? $variation_commission_data['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $variation_commission_data['commission_fixed'] ) ? $variation_commission_data['commission_fixed'] : '';
			$vendor_commission_percent = isset( $variation_commission_data['commission_percent'] ) ? $variation_commission_data['commission_percent'] : '90';
			
			$variations[$variation_id_key]['vendor_commission_mode'] = $vendor_commission_mode;
			$variations[$variation_id_key]['vendor_commission_percent'] = $vendor_commission_percent;
			$variations[$variation_id_key]['vendor_commission_fixed'] = $vendor_commission_fixed;
		}
		
		return $variations;
	}
	
	/**
	 * Variation Commission Save
	 */
	function wcfmmp_product_variation_commission_save( $wcfm_variation_data, $new_product_id, $variation_id, $variations, $wcfm_products_manage_form_data ) {
		global $WCFM, $WCFMmp;
		
		$variation_commission_data = get_post_meta( $variation_id, '_wcfmmp_commission', true );
		if( empty($variation_commission_data) ) $variation_commission_data = array();
			
		if( isset( $variations['vendor_commission_mode'] ) ) {
			$variation_commission_data['commission_mode'] = $variations['vendor_commission_mode'];
		}
		if( isset( $variations['vendor_commission_percent'] ) ) {
			$variation_commission_data['commission_percent'] = $variations['vendor_commission_percent'];
		}
		if( isset( $variations['vendor_commission_fixed'] ) ) {
			$variation_commission_data['commission_fixed'] = $variations['vendor_commission_fixed'];
		}
		
		update_post_meta( $variation_id, '_wcfmmp_commission', $variation_commission_data );
		
		return $wcfm_variation_data;
	}
	
	/**
	 * Return commission rule for a Product
	 */
	public function wcfmmp_get_product_commission_rule( $product_id, $variation_id = 0, $vendor_id = 0, $item_price = 0, $quantity = 1 ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$product_id ) return false;
		
		$vendor_commission_mode = 'global';
		$vendor_commission_fixed = '';
		$vendor_commission_percent = '';
		$vendor_commission_by_sales = array();
		$vendor_commission_by_products = array();
		
		if( $variation_id  ) {
			$product_commission_data = get_post_meta( $variation_id, '_wcfmmp_commission', true );
			if( empty($product_commission_data) ) $product_commission_data = array();
			
			$vendor_commission_mode = isset( $product_commission_data['commission_mode'] ) ? $product_commission_data['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $product_commission_data['commission_fixed'] ) ? $product_commission_data['commission_fixed'] : '';
			$vendor_commission_percent = isset( $product_commission_data['commission_percent'] ) ? $product_commission_data['commission_percent'] : '';
		}
		
		if( $product_id && ( $vendor_commission_mode == 'global' )  ) {
			$product_commission_data = get_post_meta( $product_id, '_wcfmmp_commission', true );
			if( empty($product_commission_data) ) $product_commission_data = array();
			
			$vendor_commission_mode = isset( $product_commission_data['commission_mode'] ) ? $product_commission_data['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $product_commission_data['commission_fixed'] ) ? $product_commission_data['commission_fixed'] : '';
			$vendor_commission_percent = isset( $product_commission_data['commission_percent'] ) ? $product_commission_data['commission_percent'] : '';
		}
		
		if( !$vendor_id ) {
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
		}
		
		if( $vendor_id && ( $vendor_commission_mode == 'global' ) ) {
			$vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
			
			$vendor_commission_mode = isset( $vendor_data['commission']['commission_mode'] ) ? $vendor_data['commission']['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $vendor_data['commission']['commission_fixed'] ) ? $vendor_data['commission']['commission_fixed'] : '';
			$vendor_commission_percent = isset( $vendor_data['commission']['commission_percent'] ) ? $vendor_data['commission']['commission_percent'] : '';
			$vendor_commission_by_sales = isset( $vendor_data['commission']['commission_by_sales'] ) ? $vendor_data['commission']['commission_by_sales'] : array();
			$vendor_commission_by_products = isset( $vendor_data['commission']['commission_by_products'] ) ? $vendor_data['commission']['commission_by_products'] : array();
		}
		
		if( $vendor_commission_mode == 'global' ) {
			$wcfm_commission_options = $WCFMmp->wcfmmp_commission_options;
			
			$vendor_commission_mode = isset( $wcfm_commission_options['commission_mode'] ) ? $wcfm_commission_options['commission_mode'] : 'percent';
			$vendor_commission_fixed = isset( $wcfm_commission_options['commission_fixed'] ) ? $wcfm_commission_options['commission_fixed'] : '';
			$vendor_commission_percent = isset( $wcfm_commission_options['commission_percent'] ) ? $wcfm_commission_options['commission_percent'] : '';
			$vendor_commission_by_sales = isset( $wcfm_commission_options['commission_by_sales'] ) ? $wcfm_commission_options['commission_by_sales'] : array();
			$vendor_commission_by_products = isset( $wcfm_commission_options['commission_by_products'] ) ? $wcfm_commission_options['commission_by_products'] : array();
		}
		
		$product_commission_rule = array( 'mode' => $vendor_commission_mode, 'percent' => 0, 'fixed' => 0 );
		
		switch( $vendor_commission_mode ) {
			case 'percent':
				$product_commission_rule['percent'] = $vendor_commission_percent;
			break;
			
			case 'fixed':
				$product_commission_rule['fixed'] = $vendor_commission_fixed;
			break;
			
			case 'percent_fixed':
				$product_commission_rule['percent'] = $vendor_commission_percent;
				$product_commission_rule['fixed'] = $vendor_commission_fixed;
			break;
			
			case 'by_sales':
				$product_commission_rule = $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_rule_by_sales_rule( $vendor_id, $vendor_commission_by_sales );
			break;
			
			case 'by_products':
				if( !$item_price ) {
					$product = wc_get_product( $product_id );
					$item_price = (float)$product->get_price() * (int)$quantity;
				}
				$product_commission_rule = $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_rule_by_product_rule( $product_id, $item_price, $quantity, $vendor_commission_by_products );
			break;
			
		}
		
		return apply_filters( 'wcfmmp_product_commission_rule', $product_commission_rule, $product_id, $vendor_id, $item_price, $quantity );
	}
	
	function wcfmmp_product_manage_fields_shipping( $shipping_fields, $product_id ) {
  	global $wp, $WCFM, $WCFMmp, $wpdb;
  	
  	if( apply_filters( 'wcfm_is_allow_shipping', true ) ) {
  		$processing_time = wcfmmp_get_shipping_processing_times();
  		$disable_shipping = 'no';
  		$overwrite_shipping = 'no';
			$additional_price = '';
			$additional_qty = '';
			$wcfmmp_processing_time = '';
			
			if( $product_id ) {
//				$disable_shipping = get_post_meta( $product_id, '_disable_shipping', true ) ? get_post_meta( $product_id, '_disable_shipping', true ) : 'no';
				$overwrite_shipping = get_post_meta( $product_id, '_overwrite_shipping', true ) ? get_post_meta( $product_id, '_overwrite_shipping', true ) : 'no';
				$additional_price = get_post_meta( $product_id, '_additional_price', true ) ? get_post_meta( $product_id, '_additional_price', true ) : '';
				$additional_qty = get_post_meta( $product_id, '_additional_qty', true ) ? get_post_meta( $product_id, '_additional_qty', true ) : '';
				$wcfmmp_processing_time = get_post_meta( $product_id, '_wcfmmp_processing_time', true ) ? get_post_meta( $product_id, '_wcfmmp_processing_time', true ) : '';
			}
			
//			$wcv_shipping_fileds = array( 
//					"_disable_shipping" => array('label' => __('Disable Shipping', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $disable_shipping, 'hints' => __('Disable shipping for this product', 'wc-multivendor-marketplace') )
//				);
//			$shipping_fields = array_merge( $wcv_shipping_fileds, $shipping_fields );
			
			$wcv_shipping_fileds = apply_filters( 'wcfmmp_product_manager_shipping_fileds', array( 
				"_overwrite_shipping" => array('label' => __('Override Shipping', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $overwrite_shipping, 'hints' => __('Override your store\'s default shipping cost for this product', 'wc-multivendor-marketplace') ),
				"_additional_price" => array('label' => __('Additional Price', 'wc-multivendor-marketplace'), 'placeholder' => '0.00', 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $additional_price, 'hints' => __('First product of this type will be charged with this price', 'wc-multivendor-marketplace') ),
				"_additional_qty" => array('label' => __('Per Qty Additional Price', 'wc-multivendor-marketplace'), 'placeholder' => '0.00', 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $additional_qty, 'hints' => __('Every second product of same type will be charged with this price', 'wc-multivendor-marketplace') ),
				"_wcfmmp_processing_time" => array('label' => __('Processing Time', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => $processing_time, 'value' => $wcfmmp_processing_time, 'hints' => __('The time required before sending the product for delivery', 'wc-multivendor-marketplace') ),
																	) );
			$shipping_fields = array_merge( $shipping_fields, $wcv_shipping_fileds );
			
			if( isset( $shipping_fields['shipping_class'] ) ) {
				$shipping_fields['shipping_class']['class'] = 'wcfm_custom_hide';
				$shipping_fields['shipping_class']['label_class'] = 'wcfm_custom_hide';
				//$shipping_fields['shipping_class']['hints'] = __( 'Shipping classes are used by certain shipping methods to group similar products.', 'wc-multivendor-marketplace' );
			}
		}
  	
  	return $shipping_fields;
  }
  
  function wcfmmp_shipping_product_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMmp, $_POST, $wpdb;
		
		if( apply_filters( 'wcfm_is_allow_shipping', true ) ) {
//			if( isset( $wcfm_products_manage_form_data['_disable_shipping'] ) ) {
//				update_post_meta( $new_product_id, '_disable_shipping', $wcfm_products_manage_form_data['_disable_shipping'] );
//			} else {
//				delete_post_meta( $new_product_id, '_disable_shipping' );
//			}
			if( isset( $wcfm_products_manage_form_data['_overwrite_shipping'] ) ) {
				update_post_meta( $new_product_id, '_overwrite_shipping', $wcfm_products_manage_form_data['_overwrite_shipping'] );
			} else {
				delete_post_meta( $new_product_id, '_overwrite_shipping' );
			}
			if( isset( $wcfm_products_manage_form_data['_additional_price'] ) ) {
				update_post_meta( $new_product_id, '_additional_price', $wcfm_products_manage_form_data['_additional_price'] );
			}
			if( isset( $wcfm_products_manage_form_data['_additional_qty'] ) ) {
				update_post_meta( $new_product_id, '_additional_qty', $wcfm_products_manage_form_data['_additional_qty'] );
			}
			if( isset( $wcfm_products_manage_form_data['_wcfmmp_processing_time'] ) ) {
				update_post_meta( $new_product_id, '_wcfmmp_processing_time', $wcfm_products_manage_form_data['_wcfmmp_processing_time'] );
			}
		}
  }
	
}