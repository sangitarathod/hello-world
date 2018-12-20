<?php
/**
 * Actions
 *
 * @package     TourPress
 * @subpackage  Actions
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Create custom post types and taxonomies
//add_action( 'init', 'tourpress_setup_tourpress_post_types' );
add_action( 'init', 'tourpress_setup_taxonomies' );

// Check/refresh cache
add_action('template_redirect', 'tourpress_refresh_cache', 1);

// Register settings
add_action('admin_init', 'tourpress_register');

// Add config menus to the Admin area
add_action('admin_menu', 'tourpress_adminmenu');

// Save post
add_action( 'save_post', 'tourpress_save_product', 1, 2);

// Add any standard booking engines
add_action('tourpress_book', 'tourpress_dobook');
add_action('tourpress_price', 'tourpress_doprice');
