<?php
/**
 * Admin Pages
 *
 * @package     EPower
 * @subpackage  Admin Pages
 * @copyright   Copyright (c) 2018, Neosoft Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Admin menu page details
function epower_adminmenu() {

	add_menu_page(
		'EPower', 					//$page_title
		'EPower',					//$menu_title
		'manage_options',				//$capability
		'epower',					//$menu_slug
		'epower_settings',	//$function
		'dashicons-controls-repeat',	//$icon_url
		77							//$position below Tools
	);	

	add_submenu_page('epower', 'EPower Settings', 'Settings', 'manage_options', 'epower', 'epower_settings');	
	add_submenu_page('epower', 'Airline', 'Airline', 'manage_options', 'airline', 'airLineData');	

}




