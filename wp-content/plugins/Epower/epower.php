<?php 
    /*
    Plugin Name: EPower
    Plugin URI: http://www.epower.com
    Description: Plugin for Airline Code and ICon Store to database.
    Author: Neosoft Technology
    Version: 1.0
    Author URI: http://www.xpl.com.au/
    */




	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;
	
	// Define some useful constants
	if ( ! defined( 'EPOWER_VERSION' ) ) define( 'EPOWER_VERSION', '1.0' );
	if ( ! defined( 'EPOWER_PLUGIN_DIR' ) ) define( 'EPOWER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	if ( ! defined( 'EPOWER_PLUGIN_URL' ) ) define( 'EPOWER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	if ( ! defined( 'EPOWER_PLUGIN_FILE' ) ) define( 'EPOWER_PLUGIN_FILE', __FILE__ );

	
	require_once EPOWER_PLUGIN_DIR . 'includes/actions.php';
	require_once EPOWER_PLUGIN_DIR . 'includes/admin/admin-pages.php';
	require_once EPOWER_PLUGIN_DIR . 'includes/admin/settings.php';
	require_once EPOWER_PLUGIN_DIR . 'includes/admin/airlinedata.php';
	
	
?>
