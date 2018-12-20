<?php
/*

Plugin Name: Gizmogol Retail
Plugin URI: http://anda.lu/design

Description: Customize the Gizmogol Retail site

Version: 1.1.1
Author: ANDA.lu
Author URI: http://anda.lu/design

*/

if ( ! class_exists( 'Gzmgl_Retail' ) ) :
class Gzmgl_Retail {

	public static $url, $dir;
	
	static function init() {
		self::$url = plugins_url('', __FILE__);
		self::$dir = plugin_dir_path(__FILE__);
		
		load_plugin_textdomain( 'gzmgl_retail', false, self::$dir . '/languages' );

		// Force users to login when visiting the site
		add_action( 'init', __CLASS__ . '::force_login' );

		// Redirect to homepage after login
		//add_filter( 'wppb_after_login_redirect_url', __CLASS__ . '::after_login_redirect' );

		// Add logout link to the menu
		add_filter( 'wp_nav_menu_items', __CLASS__ . '::logout_link', 10, 2 );

		// Redirect to edit shipping address after address save
		add_action( 'woocommerce_customer_save_address', __CLASS__ . '::save_address', 10, 2 );

		// Disable admin bar on frontend
		if ( ! is_admin() ) {
			show_admin_bar( false );
		}	
		
		require_once( 'includes/class-cart.php' );
		require_once( 'includes/class-serial.php' );
		require_once( 'includes/class-gravity-forms.php' );
		require_once( 'includes/class-order.php' );

	}

	// Force users to login when visiting the site
	static function force_login() {
		if ( ! is_user_logged_in() ) {
			// Get URL
			$url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
			$url .= '://' . $_SERVER['HTTP_HOST'];
			$url .= $_SERVER['REQUEST_URI'];
			
			$whitelist = array( site_url( 'login/' ), site_url( 'recover-password/' ) );
			$redirect_url = preg_replace( '/\?.*/', '', $url );
			
			// if ( $redirect_url != preg_replace( '/\?.*/', '', wp_login_url() ) && ! in_array( $redirect_url, $whitelist ) ) {
			// 	wp_safe_redirect( site_url( 'login/' ), 302 );
			// 	exit;
			// }
		}
	}
	
	// Redirect to homepage after login
	static function after_login_redirect( $url ) {
			return site_url( '/sell-your-device');
	}

	// Add logout link to the menu
	static function logout_link( $items, $args ) {
		if ( $args->theme_location == 'secondary-menu' ) {
			if ( is_user_logged_in() ) {
				$items .= '<li class="menu-item"><a href="'. wp_logout_url( site_url( '/' ) ) .'">' . __( 'Logout', 'gzmgl_retail' ) . '</a></li>';
			}
		}

		// Add print shipping label to main menu
		if ( $args->theme_location == 'main-menu' ) {
			if ( is_user_logged_in() ) {
				$url = 'http://labels.gizmogul.com/print_retail.php?id=' . get_current_user_id();
				$items = str_replace( '#print-shipping-label', $url, $items );

				$url = 'http://labels.gizmogul.com/print_retail_usps.php?id=' . get_current_user_id();
				$items = str_replace( '#print-usps-shipping-label', $url, $items );
			}
		}
		return $items;
	}

	// Redirect to edit shipping address after address save
	static function save_address( $user_id, $load_address ) {
		if ( 'shipping' == $load_address ) {
			wp_safe_redirect( wc_get_endpoint_url( 'edit-address', 'shipping' ) );
			exit;
		}
	}

	
}
Gzmgl_Retail::init();
endif;
/**
$test_cards = array( 
	'4842240111858862',
	'4842240111858896',
	'4842240111858888',
	'4842240111858870',
	'4842240111858904',
/**
	$test_cards = array( 
	'4444333322221111',
	);

foreach ( $test_cards as $card )
	echo Gzmgl_Retail_Gravity_Forms::greguly_codeable_swift_check( $card, $debug = true );
/**/