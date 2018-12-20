<?php
/**
 * Admin Pages
 *
 * @package     TourPress
 * @subpackage  Admin Pages
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Admin menu page details
function tourpress_adminmenu() {

	add_menu_page(
		'TourPress', 					//$page_title
		'TourPress',					//$menu_title
		'manage_options',				//$capability
		'tourpress',					//$menu_slug
		'tourpress_refresh_content',	//$function
		'dashicons-controls-repeat',	//$icon_url
		76 								//$position below Tools
	);

	add_submenu_page('tourpress', 'TourPress Refresh Content', 'Refresh Content', 'manage_options', 'tourpress', 'tourpress_refresh_content');

	add_submenu_page('tourpress', 'TourPress Refresh Product Content', 'Refresh Product Content', 'manage_options', 'tourpress_update_product_content', 'tourpress_refresh_product_content');

	add_submenu_page('tourpress', 'TourPress Settings', 'Settings', 'manage_options', 'tourpress_options', 'tourpress_options');	
	
	add_submenu_page('tourpress', 'TourPress Test API', 'Test API' , 'manage_options', 'tourpress_test_api', 'tourpress_test_api');

}

// Product Page More Fields (PODS)
function slug_pods_metabox_title( $title ) {
	$title = __( 'Customised (Pods) Fields', 'pods' );
	return $title;
}
	