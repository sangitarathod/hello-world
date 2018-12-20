<?php
/*
	Plugin Name: WC Return Products 
	Plugin URI: 
	Description: Provide option return product from front end.
	Version: 1.0.1
	Author: wc expert
*/
define("wc_return_ID", "wc_return_products");

/**
 * Check if WooCommerce is active
 */
if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {	
	
	if (!function_exists('get_settings_url')){
		function get_settings_url(){
			return version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
		}
	}
	
	if (!function_exists('plugin_override')){
		add_action( 'plugins_loaded', 'plugin_override' );
		function plugin_override() {
			if (!function_exists('WC')){
				function WC(){
					return $GLOBALS['woocommerce'];
				}
			}
		}
	}

	if(!class_exists('wc_return_wooCommerce_shipping_setup')){
		class wc_return_wooCommerce_shipping_setup {
			public function __construct() {
                $this->init();
				// add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
				add_filter( 'admin_enqueue_scripts', array( $this, 'wc_return_scripts' ) );		
				
				$wc_return_settings = get_option( 'woocommerce_'.wc_return_ID.'_settings', array() );

			}

			public function init() {
				if ( is_admin() ) {
					include_once('includes/wc-return-products-admin.php');
				}
				include_once('includes/wc-return-products.php');
				// Localisation
				// load_plugin_textdomain( 'wf-shipping-wc_return', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
			}
			
			public function wc_return_scripts() {
				wp_enqueue_script( 'jquery-ui-sortable' );
				// wp_enqueue_script( 'wf-common-script', plugins_url( '/resources/js/common.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_style( 'wc-common-style', plugins_url( '/resources/css/common_style.css', __FILE__ ));
			}
			
		}
		new wc_return_wooCommerce_shipping_setup();
	}
}
