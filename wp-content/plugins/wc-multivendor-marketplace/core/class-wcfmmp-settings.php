<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Settings
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Settings {

	public function __construct() {
		global $WCFM;
		
		// Marketplace Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_marketplace_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_marketplace_settings_update' ), 14 );
		
		
		// Commission Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_commission_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_commission_settings_update' ), 14 );
		
		// Withdrawal Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_withdrawal_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_withdrawal_settings_update' ), 14 );
		
		// Refund Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_refund_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_refund_settings_update' ), 14 );
		
		// Review Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_review_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_review_settings_update' ), 14 );
		
		// Vendor Registration Setting
		add_action( 'begin_wcfm_settings_form_style', array( &$this, 'wcfm_vendor_registration_settings' ), 14 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfm_vendor_registration_settings_update' ), 14 );
	}
	
	function wcfm_marketplace_settings( $wcfm_options ) {
		global $WCFM, $WCFMmp;
		
		if( !apply_filters( 'wcfm_is_allow_marketplace_manage', true ) ) return; 
		
		$wcfm_marketplace_options = get_option( 'wcfm_marketplace_options', array() );
    $wcfmmp_marketplace_shipping_options = get_option( 'woocommerce_wcfmmp_product_shipping_by_country_settings', array() );
    $wcfmmp_marketplace_shipping_enabled = ( !empty($wcfmmp_marketplace_shipping_options) && !empty($wcfmmp_marketplace_shipping_options['enabled']) ) ? $wcfmmp_marketplace_shipping_options['enabled'] : 'yes';
    
		$wcfm_store_url = isset( $wcfm_marketplace_options['wcfm_store_url'] ) ? $wcfm_marketplace_options['wcfm_store_url'] : 'store';
		$vendor_sold_by = isset( $wcfm_marketplace_options['vendor_sold_by'] ) ? $wcfm_marketplace_options['vendor_sold_by'] : 'yes';
		$product_mulivendor = isset( $wcfm_marketplace_options['product_mulivendor'] ) ? $wcfm_marketplace_options['product_mulivendor'] : 'yes';
		$wcfm_google_map_api = isset( $wcfm_marketplace_options['wcfm_google_map_api'] ) ? $wcfm_marketplace_options['wcfm_google_map_api'] : '';
		$delete_data_on_uninstall = isset( $wcfm_marketplace_options['delete_data_on_uninstall'] ) ? $wcfm_marketplace_options['delete_data_on_uninstall'] : 'no';
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_marketplace_head">
			<label class="fa fa-home"></label>
			<?php _e('Marketplace Settings', 'wc-multivendor-marketplace'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_marketplace_expander" class="wcfm-content">
			  <h2><?php _e('Marketplace Settings', 'wc-multivendor-marketplace'); ?></h2>
				<?php wcfm_video_tutorial( 'https://www.youtube.com/embed/ZKGD1PkdgYI' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_store', array(
					                                                                        "vendor_store_url" => array('label' => __('Vendor Store URL', 'wc-multivendor-marketplace') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'desc_class' => 'wcfm_page_options_desc', 'value' => $wcfm_store_url, 'desc' => sprintf( __( 'Define the seller store URL  (%s/[this-text]/[seller-name])', 'wc-multivendor-marketplace' ), get_site_url() )  ),
					                                                                        "vendor_sold_by" => array('label' => __('Visible Sold By', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_sold_by ),
					                                                                        "product_mulivendor" => array('label' => __('Product Multi-vendor', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $product_mulivendor, 'desc_class' => 'wcfm_page_options_desc','desc' => __( 'Enable this to allow vendors to sell other vendor products, single product multiple seller.', 'wc-multivendor-marketplace' ) ),
                                                                                  "enable_marketplace_shipping" => array('label' => __('Marketplace Shipping', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $wcfmmp_marketplace_shipping_enabled, 'desc_class' => 'wcfm_page_options_desc', 'desc' => __( 'Enable this to allow your vendors to setup their own shipping by country.', 'wc-multivendor-marketplace' ) ),
																																									"wcfm_google_map_api" => array('label' => __('Google Map API Key', 'wc-multivendor-marketplace') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'desc_class' => 'wcfm_page_options_desc', 'value' => $wcfm_google_map_api, 'desc' => sprintf( __( '%sAPI Key%s is needed to display map on store page', 'wc-multivendor-marketplace' ), '<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/">', '</a>' ) ),
																																									"delete_data_on_uninstall" => array('label' => __('On Uninstall', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $delete_data_on_uninstall, 'desc_class' => 'wcfm_page_options_desc', 'desc' => __( 'Delete all marketplace data on uninstall. Be careful, there is no way to retrieve those data if once deleted!', 'wc-multivendor-marketplace' ) ),
																																									) ) );
			  ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
		
	}
	
	function wcfm_marketplace_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		if( !apply_filters( 'wcfm_is_allow_marketplace_manage', true ) ) return; 
		
		$wcfm_marketplace_options = get_option( 'wcfm_marketplace_options', array() );
		
		if( isset( $wcfm_settings_form['vendor_store_url'] ) ) {
			$wcfm_marketplace_options['wcfm_store_url'] = $wcfm_settings_form['vendor_store_url'];
			update_option( 'wcfm_store_url', $wcfm_settings_form['vendor_store_url'] );
		}
		
		if( isset( $wcfm_settings_form['vendor_sold_by'] ) ) {
			$wcfm_marketplace_options['vendor_sold_by'] = 'yes';
		} else {
			$wcfm_marketplace_options['vendor_sold_by'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['product_mulivendor'] ) ) {
			$wcfm_marketplace_options['product_mulivendor'] = 'yes';
		} else {
			$wcfm_marketplace_options['product_mulivendor'] = 'no';
		}
    
		if( isset( $wcfm_settings_form['wcfm_google_map_api'] ) ) {
			$wcfm_marketplace_options['wcfm_google_map_api'] = $wcfm_settings_form['wcfm_google_map_api'];
		}
		
		if( isset( $wcfm_settings_form['delete_data_on_uninstall'] ) ) {
			$wcfm_marketplace_options['delete_data_on_uninstall'] = 'yes';
		} else {
			$wcfm_marketplace_options['delete_data_on_uninstall'] = 'no';
		}
		
		update_option( 'wcfm_marketplace_options', $wcfm_marketplace_options );
		
		if( isset( $wcfm_settings_form['enable_marketplace_shipping'] ) ) {
			$enable_marketplace_shipping = 'yes';
		} else {
			$enable_marketplace_shipping = 'no';
		}
		$wcfmmp_marketplace_shipping_options = get_option( 'woocommerce_wcfmmp_product_shipping_by_country_settings', array() );
    $wcfmmp_marketplace_shipping_options['enabled'] = $enable_marketplace_shipping;
    update_option( 'woocommerce_wcfmmp_product_shipping_by_country_settings', $wcfmmp_marketplace_shipping_options );
	}
	
	function wcfm_commission_settings( $wcfm_options ) {
		global $WCFM, $WCFMmp;
		
		if( !apply_filters( 'wcfm_is_allow_commission_manage', true ) ) return; 
		
		$wcfm_commission_options = get_option( 'wcfm_commission_options', array() );
		
		$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		
		$vendor_commission_for = isset( $wcfm_commission_options['commission_for'] ) ? $wcfm_commission_options['commission_for'] : 'vendor';
		$vendor_commission_mode = isset( $wcfm_commission_options['commission_mode'] ) ? $wcfm_commission_options['commission_mode'] : 'percent';
		$vendor_commission_fixed = isset( $wcfm_commission_options['commission_fixed'] ) ? $wcfm_commission_options['commission_fixed'] : '';
		$vendor_commission_percent = isset( $wcfm_commission_options['commission_percent'] ) ? $wcfm_commission_options['commission_percent'] : '90';
		$vendor_commission_by_sales = isset( $wcfm_commission_options['commission_by_sales'] ) ? $wcfm_commission_options['commission_by_sales'] : array();
		$vendor_commission_by_products = isset( $wcfm_commission_options['commission_by_products'] ) ? $wcfm_commission_options['commission_by_products'] : array();
		$vendor_get_shipping = isset( $wcfm_commission_options['get_shipping'] ) ? $wcfm_commission_options['get_shipping'] : 'yes';
		$vendor_get_tax = isset( $wcfm_commission_options['get_tax'] ) ? $wcfm_commission_options['get_tax'] : 'yes';
		$vendor_coupon_deduct = isset( $wcfm_commission_options['coupon_deduct'] ) ? $wcfm_commission_options['coupon_deduct'] : 'yes';
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_commission_head">
			<label class="fa fa-percent"></label>
			<?php _e('Commission Settings', 'wc-multivendor-commission'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_commission_expander" class="wcfm-content">
			  <h2><?php _e('Marketplace Commission Settings', 'wc-multivendor-commission'); ?></h2>
				<?php wcfm_video_tutorial( 'https://wclovers.com/knowledgebase/wcfm-marketplace-commission/' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_commission', array(
																																									"vendor_commission_for" => array('label' => __('Commission For', 'wc-multivendor-marketplace'), 'type' => 'select', 'options' => array( 'vendor' => __( 'Vendor', 'wc-multivendor-marketplace' ), 'admin' => __( 'Admin', 'wc-multivendor-marketplace' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_commission_for ),
					                                                                        "vendor_commission_mode" => array('label' => __('Commission Mode', 'wc-multivendor-marketplace'), 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_commission_mode ),
					                                                                        "vendor_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => $vendor_commission_percent, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_fixed" => array('label' => __('Commission Fixed', 'wc-multivendor-marketplace') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => $vendor_commission_fixed, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_by_sales" => array('label' => __('Commission By Sales Rule(s)', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_by_sales', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_title commission_mode_field commission_mode_by_sales', 'desc_class' => 'commission_mode_field commission_mode_by_sales instructions', 'value' => $vendor_commission_by_sales, 'desc' => sprintf( __( 'Commission rules depending upon vendors total sales. e.g 50&#37; commission when sales < %s1000, 75&#37; commission when sales > %s1000 but < %s2000 and so on. You may define any number of such rules. Please be sure, do not set conflicting rules.', 'wc-multivendor-marketplace' ), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol() ),  'options' => array( 
					                                                                        																			"sales" => array('label' => __('Sales', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '1', 'step' => '0.1') ),
					                                                                        																			"rule" => array('label' => __('Rule', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'upto' => __( 'Up to', 'wc-multivendor-marketplace' ), 'greater' => __( 'More than', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"type" => array('label' => __('Commission Type', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'percent' => __( 'Percent', 'wc-multivendor-marketplace' ), 'fixed' => __( 'Fixed', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"commission" => array('label' => __('Commission Amount', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			) ),
					                                                                        "vendor_commission_by_products" => array('label' => __('Commission By Product Price', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_by_products', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_title commission_mode_field commission_mode_by_products', 'desc_class' => 'commission_mode_field commission_mode_by_products instructions', 'value' => $vendor_commission_by_products, 'desc' => sprintf( __( 'Commission rules depending upon product price. e.g 80&#37; commission when product cost < %s1000, %s100 fixed commission when product cost > %s1000 and so on. You may define any number of such rules. Please be sure, do not set conflicting rules.', 'wc-multivendor-marketplace' ), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol() ),  'options' => array( 
					                                                                        																			"cost" => array('label' => __('Product Cost', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			"rule" => array('label' => __('Rule', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'upto' => __( 'Up to', 'wc-multivendor-marketplace' ), 'greater'   => __( 'More than', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"type" => array('label' => __('Commission Type', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'percent' => __( 'Percent', 'wc-multivendor-marketplace' ), 'fixed'   => __( 'Fixed', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"commission" => array('label' => __('Commission Amount', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			) ),
																																									"vendor_get_shipping" => array('label' => __('Shipping cost goes to vendor?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_get_shipping ),
																																									"vendor_get_tax" => array('label' => __('Tax goes to vendor?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_get_tax ),
																																									"vendor_coupon_deduct" => array('label' => __('Commission after deduct discounts?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_coupon_deduct, 'hints' => __( 'Generate vednor commission after deduct coupon or other discounts.', 'wc-multivendor-marketplace' ) ),
																																									) ) );
			  ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
		
	}
	
	function wcfm_commission_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		if( !apply_filters( 'wcfm_is_allow_commission_manage', true ) ) return; 
		
		$wcfm_commission_options = get_option( 'wcfm_commission_options', array() );
		
		if( isset( $wcfm_settings_form['vendor_commission_for'] ) ) {
			$wcfm_commission_options['commission_for'] = $wcfm_settings_form['vendor_commission_for'];
		}
		
		if( isset( $wcfm_settings_form['vendor_commission_mode'] ) ) {
			$wcfm_commission_options['commission_mode'] = $wcfm_settings_form['vendor_commission_mode'];
		}
		
		if( isset( $wcfm_settings_form['vendor_commission_fixed'] ) ) {
			$wcfm_commission_options['commission_fixed'] = $wcfm_settings_form['vendor_commission_fixed'];
		}
		
		if( isset( $wcfm_settings_form['vendor_commission_percent'] ) ) {
			$wcfm_commission_options['commission_percent'] = $wcfm_settings_form['vendor_commission_percent'];
		}
		
		if( isset( $wcfm_settings_form['vendor_commission_by_sales'] ) ) {
			$wcfm_commission_options['commission_by_sales'] = $wcfm_settings_form['vendor_commission_by_sales'];
		}
		
		if( isset( $wcfm_settings_form['vendor_commission_by_products'] ) ) {
			$wcfm_commission_options['commission_by_products'] = $wcfm_settings_form['vendor_commission_by_products'];
		}
		
		if( isset( $wcfm_settings_form['vendor_get_shipping'] ) ) {
			$wcfm_commission_options['get_shipping'] = 'yes';
		} else {
			$wcfm_commission_options['get_shipping'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['vendor_get_tax'] ) ) {
			$wcfm_commission_options['get_tax'] = 'yes';
		} else {
			$wcfm_commission_options['get_tax'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['vendor_coupon_deduct'] ) ) {
			$wcfm_commission_options['coupon_deduct'] = 'yes';
		} else {
			$wcfm_commission_options['coupon_deduct'] = 'no';
		}
		
		
		update_option( 'wcfm_commission_options', $wcfm_commission_options );
	}
	
	function wcfm_withdrawal_settings( $wcfm_options ) {
		global $WCFM, $WCFMmp;
		
		if( !apply_filters( 'wcfm_is_allow_withdrawal_manage', true ) ) return; 
		
		$wcfm_withdrawal_options = get_option( 'wcfm_withdrawal_options', array() );
		
		$wcfm_marketplace_withdrwal_payment_methods = get_wcfm_marketplace_withdrwal_payment_methods();
		$wcfm_marketplace_withdrawal_order_status   = get_wcfm_marketplace_withdrwal_order_status();
		$wcfm_marketplace_disallow_order_payment_methods = get_wcfm_marketplace_disallow_order_payment_methods();
		
		$request_auto_approve = isset( $wcfm_withdrawal_options['request_auto_approve'] ) ? $wcfm_withdrawal_options['request_auto_approve'] : 'no';
		$payment_methods = isset( $wcfm_withdrawal_options['payment_methods'] ) ? $wcfm_withdrawal_options['payment_methods'] : array( 'paypal', 'bank_transfer' );
		
		$withdrawal_test_mode = isset( $wcfm_withdrawal_options['test_mode'] ) ? 'yes' : 'no';
		
		$withdrawal_paypal_client_id          = isset( $wcfm_withdrawal_options['paypal_client_id'] ) ? $wcfm_withdrawal_options['paypal_client_id'] : '';
		$withdrawal_paypal_secret_key         = isset( $wcfm_withdrawal_options['paypal_secret_key'] ) ? $wcfm_withdrawal_options['paypal_secret_key'] : '';
		$withdrawal_stripe_client_id          = isset( $wcfm_withdrawal_options['stripe_client_id'] ) ? $wcfm_withdrawal_options['stripe_client_id'] : '';
		$withdrawal_stripe_published_key      = isset( $wcfm_withdrawal_options['stripe_published_key'] ) ? $wcfm_withdrawal_options['stripe_published_key'] : '';
		$withdrawal_stripe_secret_key         = isset( $wcfm_withdrawal_options['stripe_secret_key'] ) ? $wcfm_withdrawal_options['stripe_secret_key'] : '';
		
		$withdrawal_paypal_test_client_id     = isset( $wcfm_withdrawal_options['paypal_test_client_id'] ) ? $wcfm_withdrawal_options['paypal_test_client_id'] : '';
		$withdrawal_paypal_test_secret_key    = isset( $wcfm_withdrawal_options['paypal_test_secret_key'] ) ? $wcfm_withdrawal_options['paypal_test_secret_key'] : '';
		$withdrawal_stripe_test_client_id     = isset( $wcfm_withdrawal_options['stripe_test_client_id'] ) ? $wcfm_withdrawal_options['stripe_test_client_id'] : '';
		$withdrawal_stripe_test_published_key = isset( $wcfm_withdrawal_options['stripe_test_published_key'] ) ? $wcfm_withdrawal_options['stripe_test_published_key'] : '';
		$withdrawal_stripe_test_secret_key    = isset( $wcfm_withdrawal_options['stripe_test_secret_key'] ) ? $wcfm_withdrawal_options['stripe_test_secret_key'] : '';
		
		$order_status                    = isset( $wcfm_withdrawal_options['order_status'] ) ? $wcfm_withdrawal_options['order_status'] : array( 'wc-completed' );
		$disallow_order_payment_methods  = isset( $wcfm_withdrawal_options['disallow_order_payment_methods'] ) ? $wcfm_withdrawal_options['disallow_order_payment_methods'] : array();
		$withdrawal_limit                = isset( $wcfm_withdrawal_options['withdrawal_limit'] ) ? $wcfm_withdrawal_options['withdrawal_limit'] : '';
		$withdrawal_thresold             = isset( $wcfm_withdrawal_options['withdrawal_thresold'] ) ? $wcfm_withdrawal_options['withdrawal_thresold'] : '';
		$withdrawal_charge_type          = isset( $wcfm_withdrawal_options['withdrawal_charge_type'] ) ? $wcfm_withdrawal_options['withdrawal_charge_type'] : 'no';
		
		$withdrawal_charge               = isset( $wcfm_withdrawal_options['withdrawal_charge'] ) ? $wcfm_withdrawal_options['withdrawal_charge'] : array();
		$withdrawal_charge_paypal        = isset( $withdrawal_charge['paypal'] ) ? $withdrawal_charge['paypal'] : array();
		$withdrawal_charge_stripe        = isset( $withdrawal_charge['stripe'] ) ? $withdrawal_charge['stripe'] : array();
		$withdrawal_charge_skrill        = isset( $withdrawal_charge['skrill'] ) ? $withdrawal_charge['skrill'] : array();
		$withdrawal_charge_bank_transfer = isset( $withdrawal_charge['bank_transfer'] ) ? $withdrawal_charge['bank_transfer'] : array();
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_withdrawal_head">
			<label class="fa"><?php echo get_woocommerce_currency_symbol(); ?></label>
			<?php _e('Withdrawal Settings', 'wc-multivendor-marketplace'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_withdrawal_expander" class="wcfm-content">
			  <h2><?php _e('Marketplace Withdrawal Settings', 'wc-multivendor-marketplace'); ?></h2>
				<?php wcfm_video_tutorial( 'https://wclovers.com/knowledgebase/wcfm-marketplace-withdrawal/' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_withdrawal', array(
					                                                                        "withdrawal_request_auto_approve" => array('label' => __('Request auto-approve?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'name' => 'wcfm_withdrawal_options[request_auto_approve]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $request_auto_approve, 'desc_class' => 'instructions', 'desc' => __( 'Check this to automatically disburse payments to vendors on request, no admin approval required. Auto disbursement only works for auto-payment gateways, e.g. PayPal, Stripe etc. Bank Transfer or other non-autopay mode always requires approval, as these are manual transactions.', 'wc-multivendor-membership' ) ),
					                                                                        "withdrawal_payment_methods" => array( 'label' => __( 'Withdraw Payment Methods', 'wc-multivendor-membership' ), 'name' => 'wcfm_withdrawal_options[payment_methods]', 'type' => 'checklist', 'class' => 'wcfm-checkbox wcfm_ele payment_options', 'label_class' => 'wcfm_title wcfm_full_title', 'options' => $wcfm_marketplace_withdrwal_payment_methods, 'value' => $payment_methods  ),
					                                                                        "withdrawal_setting_break_1" => array( 'type' => 'html', 'value' => '<div style="height: 15px;"></div>' ),
					                                                                        "withdrawal_test_mode" => array('label' => __('Enable Test Mode', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'name' => 'wcfm_withdrawal_options[test_mode]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $withdrawal_test_mode ),
					                                                                        
					                                                                        "withdrawal_paypal_client_id" => array('label' => __('PayPal Client ID', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[paypal_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_paypal withdrawal_mode_live', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_paypal withdrawal_mode_live', 'value' => $withdrawal_paypal_client_id ),
					                                                                        "withdrawal_paypal_secret_key" => array('label' => __('PayPal Secret Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[paypal_secret_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_paypal withdrawal_mode_live', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_paypal withdrawal_mode_live', 'value' => $withdrawal_paypal_secret_key ),
					                                                                        "withdrawal_stripe_client_id" => array('label' => __('Stripe Client ID', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'value' => $withdrawal_stripe_client_id, 'desc_class' => 'withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live wcfm_page_options_desc', 'desc' => sprintf( __( 'Set redirect URL: %s', 'wc-multivendor-marketplace' ), get_wcfm_settings_url() ) ),
					                                                                        "withdrawal_stripe_published_key" => array('label' => __('Stripe Publish Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_published_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'value' => $withdrawal_stripe_published_key ),
					                                                                        "withdrawal_stripe_secret_key" => array('label' => __('Stripe Secret Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_secret_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_live', 'value' => $withdrawal_stripe_secret_key ),
					                                                                        
					                                                                        "withdrawal_paypal_test_client_id" => array('label' => __('PayPal Client ID', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[paypal_test_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_paypal withdrawal_mode_test', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_paypal withdrawal_mode_test', 'value' => $withdrawal_paypal_test_client_id ),
					                                                                        "withdrawal_paypal_test_secret_key" => array('label' => __('PayPal Secret Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[paypal_test_secret_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_paypal withdrawal_mode_test', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_paypal withdrawal_mode_test', 'value' => $withdrawal_paypal_test_secret_key ),
					                                                                        "withdrawal_stripe_test_client_id" => array('label' => __('Stripe Client ID', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_test_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'value' => $withdrawal_stripe_test_client_id, 'desc_class' => 'withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test wcfm_page_options_desc', 'desc' => sprintf( __( 'Set redirect URL: %s', 'wc-multivendor-marketplace' ), get_wcfm_settings_url() ) ),
					                                                                        "withdrawal_stripe_test_published_key" => array('label' => __('Stripe Publish Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_test_published_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'value' => $withdrawal_stripe_test_published_key ),
					                                                                        "withdrawal_stripe_test_secret_key" => array('label' => __('Stripe Secret Key', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[stripe_test_secret_key]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_stripe withdrawal_mode_test', 'value' => $withdrawal_stripe_test_secret_key ),
					                                                                        
					                                                                        
					                                                                        "withdrawal_order_status" => array( 'label' => __( 'Order Status for Withdraw', 'wc-multivendor-membership' ), 'name' => 'wcfm_withdrawal_options[order_status]', 'type' => 'checklist', 'class' => 'wcfm-checkbox wcfm_ele payment_options', 'label_class' => 'wcfm_title wcfm_full_title', 'options' => $wcfm_marketplace_withdrawal_order_status, 'value' => $order_status  ),
					                                                                        "withdrawal_setting_break_2" => array( 'type' => 'html', 'value' => '<div style="height: 15px;"></div>' ),
																																									"withdrawal_order_payment_methods" => array( 'label' => __( 'Disallow Order Payment Methods for Withdraw', 'wc-multivendor-membership' ), 'name' => 'wcfm_withdrawal_options[disallow_order_payment_methods]', 'type' => 'checklist', 'class' => 'wcfm-checkbox wcfm_ele payment_options', 'label_class' => 'wcfm_title wcfm_full_title', 'options' => $wcfm_marketplace_disallow_order_payment_methods, 'value' => $disallow_order_payment_methods, 'desc' => __( 'Order Payment Methods which are not applicable for vendor withdrawal request. e.g Order payment method COD and vendor receiving that amount directly from customers.', 'wc-multivendor-membership' )  ),
																																									"withdrawal_setting_break_3" => array( 'type' => 'html', 'value' => '<div style="height: 15px;"></div>' ),
																																									"withdrawal_limit" => array('label' => __('Minimum Withdraw Limit', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[withdrawal_limit]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'desc_class'=> 'wcfm_page_options_desc', 'value' => $withdrawal_limit, 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'desc' => __( 'Minimum balance required to make a withdraw request. Leave blank to set no minimum limits.', 'wc-multivendor-marketplace') ),
																																									"withdrawal_thresold" => array('label' => __('Withdraw Threshold', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[withdrawal_thresold]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $withdrawal_thresold, 'desc_class' => 'wcfm_page_options_desc', 'attributes' => array( 'min' => '1', 'step' => '1'), 'desc' => __('Withdraw Threshold Days, (Make order matured to make a withdraw request). Leave empty to inactive this option.', 'wc-multivendor-marketplace') ),
																																									"withdrawal_charge_type" => array('label' => __('Withdrawal Charges', 'wc-multivendor-marketplace'), 'name' => 'wcfm_withdrawal_options[withdrawal_charge_type]', 'type' => 'select', 'options' => array( 'no' => __( 'No Charge', 'wc-multivendor-marketplace' ), 'percent' => __( 'Percent', 'wc-multivendor-marketplace' ), 'fixed'   => __( 'Fixed', 'wc-multivendor-marketplace' ), 'percent_fixed' => __( 'Percent + Fixed', 'wc-multivendor-marketplace' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'desc_class' => 'wcfm_page_options_desc', 'value' => $withdrawal_charge_type , 'desc' => __('Charges applicable for each withdarwal.', 'wc-multivendor-marketplace') ),
																																									"withdrawal_setting_break_4" => array( 'type' => 'html', 'value' => '<div style="height: 15px;"></div>' ),
																																									
																																									"withdrawal_charge_paypal" => array( 'label' => __('PayPal Charge', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_withdrawal_options[withdrawal_charge][paypal]', 'class' => 'withdraw_charge_block withdraw_charge_paypal', 'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_paypal', 'value' => $withdrawal_charge_paypal, 'custom_attributes' => array( 'limit' => 1 ), 'options' => array(
																																																											"percent" => array('label' => __('Percent Charge(%)', 'wc-multivendor-marketplace'),  'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"fixed" => array('label' => __('Fixed Charge', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"tax" => array('label' => __('Charge Tax', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'wc-multivendor-marketplace' ) ),
																																																											) ),
																																									"withdrawal_charge_stripe" => array( 'label' => __('Stripe Charge', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_withdrawal_options[withdrawal_charge][stripe]', 'class' => 'withdraw_charge_block withdraw_charge_stripe', 'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_stripe', 'value' => $withdrawal_charge_stripe, 'custom_attributes' => array( 'limit' => 1 ), 'options' => array(
																																																											"percent" => array('label' => __('Percent Charge(%)', 'wc-multivendor-marketplace'),  'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"fixed" => array('label' => __('Fixed Charge', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"tax" => array('label' => __('Charge Tax', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'wc-multivendor-marketplace' ) ),
																																																											) ),
																																									"withdrawal_charge_skrill" => array( 'label' => __('Skrill Charge', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_withdrawal_options[withdrawal_charge][skrill]', 'class' => 'withdraw_charge_block withdraw_charge_skrill', 'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_skrill', 'value' => $withdrawal_charge_skrill, 'custom_attributes' => array( 'limit' => 1 ), 'options' => array(
																																																											"percent" => array('label' => __('Percent Charge(%)', 'wc-multivendor-marketplace'),  'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"fixed" => array('label' => __('Fixed Charge', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"tax" => array('label' => __('Charge Tax', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'wc-multivendor-marketplace' ) ),
																																																											) ),
																																									"withdrawal_charge_bank_transfer" => array( 'label' => __('Bank Transfer Charge', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_withdrawal_options[withdrawal_charge][bank_transfer]', 'class' => 'withdraw_charge_block withdraw_charge_bank_transfer', 'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_bank_transfer', 'value' => $withdrawal_charge_bank_transfer, 'custom_attributes' => array( 'limit' => 1 ), 'options' => array(
																																																											"percent" => array('label' => __('Percent Charge(%)', 'wc-multivendor-marketplace'),  'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"fixed" => array('label' => __('Fixed Charge', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																																											"tax" => array('label' => __('Charge Tax', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1'), 'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'wc-multivendor-marketplace' ) ),
																																																											) )
																																									) ) );
			  ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
		
	}
	
	function wcfm_withdrawal_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		if( !apply_filters( 'wcfm_is_allow_withdrawal_manage', true ) ) return; 
		
		if( isset( $wcfm_settings_form['wcfm_withdrawal_options'] ) ) {
			update_option( 'wcfm_withdrawal_options', $wcfm_settings_form['wcfm_withdrawal_options'] );
		}
	}
	
	function wcfm_refund_settings( $wcfm_options ) {
		global $WCFM, $WCFMmp;
		
		if( !apply_filters( 'wcfm_is_allow_refund_requests', true ) ) return; 
		
		$wcfm_refund_options = get_option( 'wcfm_refund_options', array() );
		
		$refund_auto_approve = isset( $wcfm_refund_options['refund_auto_approve'] ) ? $wcfm_refund_options['refund_auto_approve'] : 'no';
		$refund_threshold = isset( $wcfm_refund_options['refund_threshold'] ) ? $wcfm_refund_options['refund_threshold'] : '';
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_refund_head">
			<label class="fa fa-retweet"></label>
			<?php _e('Refund Settings', 'wc-multivendor-marketplace'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_refund_expander" class="wcfm-content">
			  <h2><?php _e('Store Refund Settings', 'wc-multivendor-marketplace'); ?></h2>
				<?php wcfm_video_tutorial( 'https://wclovers.com/knowledgebase/wcfm-marketplace-refund/' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_refund', array(
																																									"refund_auto_approve" => array('label' => __('Refund auto-approve?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'name' => 'wcfm_refund_options[refund_auto_approve]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $refund_auto_approve ),
					                                                                        "refund_threshold" => array('label' => __('Refund Threshold', 'wc-multivendor-marketplace'), 'type' => 'number', 'name' => 'wcfm_refund_options[refund_threshold]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $refund_threshold, 'desc_class' => 'wcfm_page_options_desc', 'attributes' => array( 'min' => '1', 'step' => '1'), 'desc' => __('Refund Threshold Days, (Allow an order available to make a refund request). Leave empty to inactive this option.', 'wc-multivendor-marketplace') ),
																																									) ) );
			  ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
		
	}
	
	function wcfm_refund_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		if( !apply_filters( 'wcfm_is_allow_refund_requests', true ) ) return; 
		
		if( isset( $wcfm_settings_form['wcfm_refund_options'] ) ) {
			update_option( 'wcfm_refund_options', $wcfm_settings_form['wcfm_refund_options'] );
		}
	}
	
	function wcfm_review_settings( $wcfm_options ) {
		global $WCFM, $WCFMmp;
		
		if( !apply_filters( 'wcfm_is_allow_reviews', true ) ) return; 
		
		$wcfm_review_options = get_option( 'wcfm_review_options', array() );
		
		$wcfm_default_review_categories = get_wcfm_marketplace_default_review_categories();
		
		$review_auto_approve    = isset( $wcfm_review_options['review_auto_approve'] ) ? $wcfm_review_options['review_auto_approve'] : 'no';
		$review_only_store_user = isset( $wcfm_review_options['review_only_store_user'] ) ? $wcfm_review_options['review_only_store_user'] : 'no';
		$wcfm_review_categories = isset( $wcfm_review_options['review_categories'] ) ? $wcfm_review_options['review_categories'] : $wcfm_default_review_categories;
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_review_head">
			<label class="fa fa-comments-o"></label>
			<?php _e('Review Settings', 'wc-multivendor-marketplace'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_review_expander" class="wcfm-content">
			  <h2><?php _e('Store Review Settings', 'wc-multivendor-marketplace'); ?></h2>
				<?php wcfm_video_tutorial( 'https://wclovers.com/knowledgebase/wcfm-marketplace-reviews/' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_review', array(
																																									"review_auto_approve" => array('label' => __('Review auto-approve?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'name' => 'wcfm_review_options[review_auto_approve]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $review_auto_approve ),
																																									"review_only_store_user" => array('label' => __('Review only store users?', 'wc-multivendor-marketplace'), 'type' => 'checkbox', 'name' => 'wcfm_review_options[review_only_store_user]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $review_only_store_user, 'desc_class' => 'wcfm_page_options_desc', 'desc' => __( 'Enable this to allow only users to review the store who already purchased something from this store.', 'wc-multivendor-membership' ) ),
					                                                                        "wcfm_review_categories" => array('label' => __('Review Categories', 'wc-multivendor-marketplace'), 'type' => 'multiinput', 'name' => 'wcfm_review_options[review_categories]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_title', 'value' => $wcfm_review_categories, 'options' => array( 
					                                                                        																			"category" => array('label' => __('Category', 'wc-multivendor-marketplace'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele' ),
					                                                                        																			) ),
																																									) ) );
			  ?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
		
	}
	
	function wcfm_review_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		if( !apply_filters( 'wcfm_is_allow_reviews', true ) ) return; 
		
		if( isset( $wcfm_settings_form['wcfm_review_options'] ) ) {
			update_option( 'wcfm_review_options', $wcfm_settings_form['wcfm_review_options'] );
		}
	}
	
	function wcfm_vendor_registration_settings() {
		global $WCFM, $WCFMmp, $_POST;
		
		$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
		
		$membership_reject_rules = array();
		if( isset( $wcfm_membership_options['membership_reject_rules'] ) ) $membership_reject_rules = $wcfm_membership_options['membership_reject_rules'];
		$required_approval = isset( $membership_reject_rules['required_approval'] ) ? $membership_reject_rules['required_approval'] : 'no';
		
		$wcfmvm_registration_static_fields = get_option( 'wcfmvm_registration_static_fields', array() );
		$enable_address = isset( $wcfmvm_registration_static_fields['address'] ) ? 'yes' : '';
		
		$field_types = apply_filters( 'wcfm_product_custom_filed_types', array( 'text' => 'Text', 'number' => 'Number', 'textarea' => 'textarea', 'datepicker' => 'Date Picker', 'timepicker' => 'Time Picker', 'checkbox' => 'Check Box', 'select' => 'Select' ) ); //, 'upload' => 'File/Image' ) );
		$wcfmvm_registration_custom_fields = get_option( 'wcfmvm_registration_custom_fields', array() );
		
		?>
		<div class="page_collapsible" id="membership_settings_form_custom_field_head">
			<label class="fa fa-user"></label>
			<?php _e('Vendor Registration', 'wc-multivendor-membership'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_custom_field_expander" class="wcfm-content">
				<h2><?php _e( 'Vendor Registration Settings', 'wc-multivendor-membership' ); ?></h2>
				<?php wcfm_video_tutorial( 'https://wclovers.com/knowledgebase/wcfm-marketplace-vendor-registration/' ); ?>
				<div class="wcfm_clearfix"></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'membership_setting_approval_fields', array(  
																																		"wcfmvm_required_approval" => array( 'label' => __( 'Required Approval', 'wc-multivendor-membership' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $required_approval, 'hints' => __( 'Whether user required Admin Approval to become vendor or not!', 'wc-multivendor-membership' ) ),
																																		) ) );
				
				?>
				<h2><?php _e( 'Registration Form Fields', 'wc-multivendor-membership' ); ?></h2>
				<div class="wcfm_clearfix"></div>
				<?php
				$pages = get_pages(); 
				$pages_array = array( '' => __( '-- Choose Terms Page --', 'wc-multivendor-membership' ) );
				$woocommerce_pages = array ( wc_get_page_id('shop'), wc_get_page_id('cart'), wc_get_page_id('checkout'), wc_get_page_id('myaccount'));
				foreach ( $pages as $page ) {
					if(!in_array($page->ID, $woocommerce_pages)) {
						$pages_array[$page->ID] = $page->post_title;
					}
				}
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmvm_registration_static_fields', array(
																																																							"address"     => array( 'label' => __( 'Store Address', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmvm_registration_static_fields[address]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmvm_registration_static_fields['address'] ) ? 'yes' : '' ),
																																																							"phone"     => array( 'label' => __( 'Store Phone', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmvm_registration_static_fields[phone]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmvm_registration_static_fields['phone'] ) ? 'yes' : '' ),
																																																							"terms"       => array( 'label' => __( 'Terms & Conditions', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmvm_registration_static_fields[terms]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmvm_registration_static_fields['terms'] ) ? 'yes' : '' ),
																																																							"terms_page"  => array( 'label' => __( 'Terms Page', 'wc-frontend-manager' ), 'type' => 'select', 'name' => 'wcfmvm_registration_static_fields[terms_page]', 'options' => $pages_array, 'class' => 'wcfm-select wcfm_ele terms_page_ele', 'label_class' => 'wcfm_title terms_page_ele', 'value' => isset( $wcfmvm_registration_static_fields['terms_page'] ) ? $wcfmvm_registration_static_fields['terms_page'] : '' )
																																																							)
																																		) );
				
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_membership_registration_custom_fields', array(
																																														"wcfmvm_registration_custom_fields" => array('label' => __( 'Registration Form Custom Fields', 'wc-multivendor-membership'), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_full_title', 'value' => $wcfmvm_registration_custom_fields, 'options' => array(
																																																						"enable"   => array('label' => __('Enable', 'wc-frontend-manager'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes'),
																																																						"type" => array( 'label' => __('Field Type', 'wc-frontend-manager'), 'type' => 'select', 'options' => $field_types, 'class' => 'wcfm-select wcfm_ele field_type_options', 'label_class' => 'wcfm_title'),           
																																																						"label" => array( 'label' => __('Label', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title'),
																																																						"options" => array( 'label' => __('Options', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele field_type_select_options', 'label_class' => 'wcfm_title field_type_select_options', 'placeholder' => __( 'Insert option values | separated', 'wc-frontend-manager' ) ),
																																																						"help_text" => array( 'label' => __('Help Content', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title' ),
																																																						"required" => array( 'label' => __('Required?', 'wc-frontend-manager'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes' ),
																																															) )
																																											) ) );
				?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		<?php
	}
	
	function wcfm_vendor_registration_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMmp, $_POST;
		
		$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
		
		if( isset( $wcfm_settings_form['wcfmvm_required_approval'] ) ) {
			$wcfm_membership_options['membership_reject_rules']['required_approval'] = 'yes';
		} else {
			$wcfm_membership_options['membership_reject_rules']['required_approval'] = 'no';
		}
	  update_option( 'wcfm_membership_options', $wcfm_membership_options );
		
		if( isset( $wcfm_settings_form['wcfmvm_registration_static_fields'] ) ) {
	  	update_option( 'wcfmvm_registration_static_fields', $wcfm_settings_form['wcfmvm_registration_static_fields'] );
	  } else {
	  	update_option( 'wcfmvm_registration_static_fields', array() );
	  }
	  
	  if( isset( $wcfm_settings_form['wcfmvm_registration_custom_fields'] ) ) {
	  	update_option( 'wcfmvm_registration_custom_fields', $wcfm_settings_form['wcfmvm_registration_custom_fields'] );
	  }
	}

}