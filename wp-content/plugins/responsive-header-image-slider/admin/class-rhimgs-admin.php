<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP Responsive header image slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Rhimgs_Admin {
	
	function __construct() {

		// Action to register admin menu
		add_action( 'admin_menu', array($this, 'rhimgs_register_menu') );

		// Action to register plugin settings
		add_action ( 'admin_init', array($this,'rhimgs_register_settings') );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @package WP Responsive header image slider
	 * @since 1.0.0
	 */
	function rhimgs_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type=sp_responsiveslider', __('Upgrade to PRO - WP Responsive header image slider', 'responsive-header-image-slider'), '<span style="color:#2ECC71">'.__('Upgrade to PRO', 'responsive-header-image-slider').'</span>', 'manage_options', 'rhimgs-premium', array($this, 'rhimgs_premium_page') );
		
		// Register plugin hire us page
		add_submenu_page( 'edit.php?post_type=sp_responsiveslider', __('Hire Us', 'responsive-header-image-slider'), '<span style="color:#2ECC71">'.__('Hire Us', 'responsive-header-image-slider').'</span>', 'manage_options', 'rhimgs-hireus', array($this, 'rhimgs_hireus_page') );

	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Responsive header image slider
	 * @since 1.0.0
	 */
	function rhimgs_premium_page() {
		include_once( SP_RHIMGS_DIR . '/admin/settings/premium.php' );		
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Responsive header image slider
	 * @since 1.0.0
	 */
	function rhimgs_hireus_page() {		
		include_once( SP_RHIMGS_DIR . '/admin/settings/hire-us.php' );
	}


	/**
	 * Function register setings
	 * 
	 * @package WP Responsive header image slider
	 * @since 1.0.0
	 */
	function rhimgs_register_settings(){
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'rhimgs-plugin-notice' ) {
	    	set_transient( 'rhimgs_install_notice', true, 604800 );
	    }		
	    
	}

	
}

$rhimgs_admin = new Rhimgs_Admin();