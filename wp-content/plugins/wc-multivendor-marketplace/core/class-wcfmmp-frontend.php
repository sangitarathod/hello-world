<?php
/**
 * WCFM Marketplace plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMmp;
		
		// Show Product Sold By Label
		add_action( 'woocommerce_product_meta_start',	array( &$this, 'wcfmmp_sold_by_product' ), 50 );
		
		// Show Product Sold By in Loop
		add_action('woocommerce_after_shop_loop_item_title', array( $this, 'wcfmmp_sold_by_product' ), 50 );
		
		// Show Product Sold By in Cart
		add_filter('woocommerce_get_item_data', array( &$this, 'wcfmmp_sold_by_cart' ), 50, 2 );
		
		// My Account Vendor Registration URL
		add_action( 'woocommerce_register_form_end', array( &$this, 'wcfmmp_become_vendor_link' ) );
		add_action( 'woocommerce_after_my_account', array( &$this, 'wcfmmp_become_vendor_link' ) );
		
		// My Account Dashboard Menu
		add_filter( 'woocommerce_account_menu_items', array( &$this, 'wcfm_dashboard_my_account_menu_items' ), 210 );
		add_filter( 'woocommerce_get_endpoint_url', array( &$this,  'wcfm_dashboard_my_account_endpoint_redirect'), 10, 4 );
		
		// Membership Commission Rules
		add_filter( 'membership_manager_fields_commission', array( &$this, 'wcfmmp_membership_manager_fields_commission' ) );
		
		//enqueue scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'wcfmmp_scripts' ), 20 );
		
		//enqueue styles
		add_action( 'wp_enqueue_scripts', array( &$this, 'wcfmmp_styles' ), 20 );
		
	}
	
	/**
	 * Show sold by at Product Page
	 */
	function wcfmmp_sold_by_product() {
		global $WCFM, $WCFMmp, $product;
		
		if ( wcfm_is_store_page() ) return;
		
		if( $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() ) {
			$product_id = $product->get_id();
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			if( $vendor_id ) {
				if( apply_filters( 'wcfmmp_is_allow_sold_by', true, $vendor_id ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $vendor_id, 'sold_by' ) ) {
					$sold_by_text = $WCFMmp->wcfmmp_vendor->sold_by_label();
					$store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) );
					
					do_action('before_wcfmmp_sold_by_label_product_page', $vendor_id );
					echo '<div class="wcfmmp_sold_by_wrapper"><span class="wcfmmp_sold_by_label">' . $sold_by_text . ':&nbsp;</span>' . $store_name . '</div>';
					$WCFMmp->wcfmmp_reviews->show_star_rating( 0, $vendor_id );
					do_action('after_wcfmmp_sold_by_label_product_page', $vendor_id );
				}
			}
		}
	}
	
	/**
	 * Show sold by at Cart Page
	 */
	function wcfmmp_sold_by_cart( $cart_item_meta, $cart_item ) {
		global $WCFM, $WCFMmp;
		
		if( $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() ) {
			$product_id = $cart_item['product_id'];
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			if( $vendor_id ) {
				if( apply_filters( 'wcfmmp_is_allow_sold_by', true, $vendor_id ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $vendor_id, 'sold_by' ) ) {
					$sold_by_text = $WCFMmp->wcfmmp_vendor->sold_by_label();
					$store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) );
					
					do_action('before_wcfmmp_sold_by_label_cart_page', $vendor_id );
					$cart_item_meta = array_merge( $cart_item_meta, array( array( 'name' => $sold_by_text, 'value' => $store_name ) ) );
					do_action('after_wcfmmp_sold_by_label_cart_page', $vendor_id );
				}
			}
		}
		return $cart_item_meta;
	}
	
	/**
	 * WC Registration Become Vendor link
	 */
	function wcfmmp_become_vendor_link() {
		global $WCFM, $WCFMmp;
		
		if( apply_filters( 'wcfm_is_allow_my_account_become_vendor', true ) && WCFMmp_Dependencies::wcfmvm_plugin_active_check() ) {
			if( wcfm_is_allowed_membership() && !wcfm_has_membership() && !wcfm_is_vendor() ) {
				echo '<div class="wcfmmp_become_vendor_link">';
				$wcfm_memberships = get_wcfm_memberships();
				if( !empty( $wcfm_memberships ) ) {
					echo '<a href="' . get_wcfm_membership_url() . '">' . __( 'Become a Vendor', 'wc-multivendor-marketplace' ) . '</a>';
				} else {
					echo '<a href="' . get_wcfm_registration_page() . '">' . __( 'Become a Vendor', 'wc-multivendor-marketplace' ) . '</a>';
				}
				echo '</div>';
			}
		}
	}
	
	/**
	 * WC My Account Dashboard Link
	 */
	function wcfm_dashboard_my_account_menu_items( $items ) {
		global $WCFM, $WCFMmp;
		
		if( wcfm_is_vendor() ) {
			$dashboard_page_title = __( 'Store Manager', 'wc-multivendor-marketplace' );
			$pages = get_option("wcfm_page_options");
			if( isset($pages['wc_frontend_manager_page_id']) && $pages['wc_frontend_manager_page_id'] ) {
				$dashboard_page_title = get_the_title( $pages['wc_frontend_manager_page_id'] );
			}
			
			$items = array_slice($items, 0, count($items) - 2, true) +
																		array(
																					"wcfm-store-manager" => $dashboard_page_title
																					) +
																		array_slice($items, count($items) - 2, count($items) - 1, true) ;
		}
																	
		return $items;
	}
	
	function wcfm_dashboard_my_account_endpoint_redirect( $url, $endpoint, $value, $permalink ) {
		if( $endpoint == 'wcfm-store-manager')
      $url = get_wcfm_url();
    return $url;
	}
	
	/**
	 * Membership commission rules 
	 */
	function wcfmmp_membership_manager_fields_commission( $commission_fileds ) {
		global $WCFM, $WCFMmp, $wp;
		
		$membership_id = 0;
		if( isset( $wp->query_vars['wcfm-memberships-manage'] ) && !empty( $wp->query_vars['wcfm-memberships-manage'] ) ) {
			$membership_id = absint( $wp->query_vars['wcfm-memberships-manage'] );
		}
		
		// Commission
		$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		$wcfm_commission_types = array_merge( array( 'global' => __( 'By Global Rule', 'wc-multivendor-marketplace' ) ), $wcfm_commission_types );
		
		$vendor_commission_mode = 'global';
		$vendor_commission_fixed = '';
		$vendor_commission_percent = 90;
		$vendor_commission_by_sales = array();
		$vendor_commission_by_products = array();
		$vendor_get_shipping = 'yes';
		$vendor_get_tax = 'yes';
		$vendor_coupon_deduct = 'yes';
		
		if( $membership_id ) {
			$commission = (array) get_post_meta( $membership_id, 'commission', true );
			$vendor_commission_mode = isset( $commission['commission_mode'] ) ? $commission['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $commission['commission_fixed'] ) ? $commission['commission_fixed'] : '';
			$vendor_commission_percent = isset( $commission['commission_percent'] ) ? $commission['commission_percent'] : '90';
			$vendor_commission_by_sales = isset( $commission['commission_by_sales'] ) ? $commission['commission_by_sales'] : array();
			$vendor_commission_by_products = isset( $commission['commission_by_products'] ) ? $commission['commission_by_products'] : array();
			$vendor_get_shipping = isset( $commission['get_shipping'] ) ? $commission['get_shipping'] : 'yes';
			$vendor_get_tax = isset( $commission['get_tax'] ) ? $commission['get_tax'] : 'yes';
			$vendor_coupon_deduct = isset( $commission['coupon_deduct'] ) ? $commission['coupon_deduct'] : 'yes';
		}
		
		$commission_fileds = apply_filters( 'wcfm_marketplace_settings_fields_vendor_commission', array(
					                                                                        "vendor_commission_mode" => array('label' => __('Commission Mode', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $vendor_commission_mode ),
					                                                                        "vendor_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_percent]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => $vendor_commission_percent, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_fixed" => array('label' => __('Commission Fixed', 'wc-multivendor-marketplace') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'commission[commission_fixed]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => $vendor_commission_fixed, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        "vendor_commission_by_sales" => array('label' => __('Commission By Sales Rule(s)', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_by_sales]', 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_by_sales', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_title commission_mode_field commission_mode_by_sales', 'desc_class' => 'commission_mode_field commission_mode_by_sales instructions', 'value' => $vendor_commission_by_sales, 'desc' => sprintf( __( 'Commission rules depending upon vendors total sales. e.g 50&#37; commission when sales < %s1000, 75&#37; commission when sales > %s1000 but < %s2000 and so on. You may define any number of such rules. Please be sure, do not set conflicting rules.', 'wc-multivendor-marketplace' ), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol() ),  'options' => array( 
					                                                                        																			"sales" => array('label' => __('Sales', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '1', 'step' => '0.1') ),
					                                                                        																			"rule" => array('label' => __('Rule', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'upto' => __( 'Up to', 'wc-multivendor-marketplace' ), 'greater'   => __( 'More than', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"type" => array('label' => __('Commission Type', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'percent' => __( 'Percent', 'wc-multivendor-marketplace' ), 'fixed'   => __( 'Fixed', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"commission" => array('label' => __('Commission Amount', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			) ),
					                                                                        "vendor_commission_by_products" => array('label' => __('Commission By Product Price', 'wc-multivendor-marketplace'), 'name' => 'commission[commission_by_products]', 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_by_products', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_title commission_mode_field commission_mode_by_products', 'desc_class' => 'commission_mode_field commission_mode_by_products instructions', 'value' => $vendor_commission_by_products, 'desc' => sprintf( __( 'Commission rules depending upon product price. e.g 80&#37; commission when product cost < %s1000, %s100 fixed commission when product cost > %s1000 and so on. You may define any number of such rules. Please be sure, do not set conflicting rules.', 'wc-multivendor-marketplace' ), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol(), get_woocommerce_currency_symbol() ),  'options' => array( 
					                                                                        																			"cost" => array('label' => __('Product Cost', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			"rule" => array('label' => __('Rule', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'upto' => __( 'Up to', 'wc-multivendor-marketplace' ), 'greater'   => __( 'More than', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"type" => array('label' => __('Commission Type', 'wc-multivendor-marketplace'), 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'percent' => __( 'Percent', 'wc-multivendor-marketplace' ), 'fixed'   => __( 'Fixed', 'wc-multivendor-marketplace' ) ) ),
					                                                                        																			"commission" => array('label' => __('Commission Amount', 'wc-multivendor-marketplace'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                                                        																			) ),
																																									"vendor_get_shipping" => array('label' => __('Shipping cost goes to vendor?', 'wc-multivendor-marketplace'), 'name' => 'commission[get_shipping]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'label_class' => 'wcfm_title checkbox_title commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'value' => 'yes', 'dfvalue' => $vendor_get_shipping ),
																																									"vendor_get_tax" => array('label' => __('Tax goes to vendor?', 'wc-multivendor-marketplace'), 'name' => 'commission[get_tax]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'label_class' => 'wcfm_title checkbox_title commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'value' => 'yes', 'dfvalue' => $vendor_get_tax ),
																																									"vendor_coupon_deduct" => array('label' => __('Commission after deduct discounts?', 'wc-multivendor-marketplace'), 'name' => 'commission[coupon_deduct]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'label_class' => 'wcfm_title checkbox_title commission_mode_field commission_mode_percent commission_mode_fixed commission_mode_percent_fixed commission_mode_by_sales commission_mode_by_products', 'value' => 'yes', 'dfvalue' => $vendor_coupon_deduct, 'hints' => __( 'Generate vednor commission after deduct coupon or other discounts.', 'wc-multivendor-marketplace' ) ),
																																									) );
		
		return $commission_fileds;
	}
	
	/**
	 * WCFMmp Store JS
	 */
	function wcfmmp_scripts() {
 		global $WCFM, $WCFMmp, $wp, $WCFM_Query;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
 		if( wcfmmp_is_store_page() ) {
 			$WCFM->library->load_blockui_lib();
 			
			// Store JS
			wp_enqueue_script( 'wcfmmp_store_js', $WCFMmp->library->js_lib_url . 'store/wcfmmp-script-store.js', array('jquery' ), $WCFMmp->version, true );
			
			$scheme  = is_ssl() ? 'https' : 'http';
			$api_key = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] : '';
			if ( $api_key ) {
				wp_enqueue_script( 'wcfm-wcmarketplace-setting-google-maps', $scheme . '://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places' );
			}
			
			$wcfm_reviews_messages = get_wcfm_reviews_messages();
			wp_localize_script( 'wcfmmp_store_js', 'wcfm_reviews_messages', $wcfm_reviews_messages );
		}
		
		if( wcfmmp_is_stores_list_page() ) {
			wp_enqueue_script( 'wcfmmp_store_list_js', $WCFMmp->library->js_lib_url . 'store/wcfmmp-script-store-lists.js', array('jquery' ), $WCFMmp->version, true );
		}
 	}
 	
 	/**
 	 * WCFMmp Core CSS
 	 */
 	function wcfmmp_styles() {
 		global $WCFM, $WCFMmp, $wp, $WCFM_Query;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
 		if( is_product() ) {
			wp_enqueue_style( 'wcfmmp_product_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-product.css', array(), $WCFMmp->version );
		}
 		
 		if( wcfmmp_is_store_page() ) {
			// Store CSS
			wp_enqueue_style( 'wcfmmp_store_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-store.css', array(), $WCFMmp->version );

			// RTL CSS
      if( is_rtl() ) {
        wp_enqueue_style( 'wcfmmp_store_rtl_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-store-rtl.css', array('wcfmmp_store_css'), $WCFMmp->version );
      }
			
			// Store Responsive CSS
			wp_enqueue_style( 'wcfmmp_store_responsive_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-store-responsive.css', array(), $WCFMmp->version );
		}
		
		if( wcfmmp_is_stores_list_page() ) {
			wp_enqueue_style( 'wcfmmp_stores_list_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-stores-list.css', array(), $WCFMmp->version );
			
			if( is_rtl() ) {
        wp_enqueue_style( 'wcfmmp_stores_list_rtl_css',  $WCFMmp->library->css_lib_url . 'store/wcfmmp-style-stores-list-rtl.css', array('wcfmmp_stores_list_css'), $WCFMmp->version );
      }
		}
 	}
	
}