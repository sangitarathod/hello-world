<?php
/**
 * Post Types
 *
 * @package     TourPress
 * @subpackage  Post Type Functions
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function tourpress_setup_tourpress_post_types() {

	register_post_type( 'product', array(	
		'label' 			=> 'Products',
		'singular_label' 	=> 'Product',
		'labels' 			=> array("add_new_item" => "New Product", "edit_item" => "Edit Product", "view_item" => "View Product", "search_items" => "Search Products", "not_found" => "No Products found", "not_found_in_trash" => "No Products found in Trash"),
		'rewrite' 			=> array("slug" => "products"),
		'supports' 			=> array('page-attributes', 'title', 'editor', 'author', 'excerpt', 'thumbnail'),
		'hierarchical'		=> true,
		'taxonomies'		=> array ('tourpress_product_type', 'location', 'facility'),	
		'menu_position' 	=> 20,
		'menu_icon'   		=> 'dashicons-palmtree',
		'show_in_nav_menus' => true,
		'public' 			=> true
		)
	);

}

function tourpress_setup_taxonomies() {

	// Product Types
	$labels = array(
		'name'                           => 'Product Types',
		'singular_name'                  => 'Product Type',
		'search_items'                   => 'Search Product Types',
		'all_items'                      => 'All Product Types',
		'edit_item'                      => 'Edit Product Type',
		'update_item'                    => 'Update Product Type',
		'add_new_item'                   => 'Add New Product Type',
		'new_item_name'                  => 'New Product Type Name',
		'menu_name'                      => 'Product Types',
		'view_item'                      => 'View Product Type',
		'popular_items'                  => 'Popular Product Types',
		'separate_items_with_commas'     => 'Separate product types with commas',
		'add_or_remove_items'            => 'Add or remove product types',
		'choose_from_most_used'          => 'Choose from the most used product types',
		'not_found'                      => 'No product types found'
	);

	register_taxonomy(
		'tourpress_product_type',
		array( 'product' ),
		array( 
			'label' => __( 'Product Types' ),
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'products-by-type' )
		)
	);

	// Locations
	$labels = array(
		'name'                           => 'Locations',
		'singular_name'                  => 'Location',
		'search_items'                   => 'Search Locations',
		'all_items'                      => 'All Locations',
		'edit_item'                      => 'Edit Location',
		'update_item'                    => 'Update Location',
		'add_new_item'                   => 'Add New Location',
		'new_item_name'                  => 'New Location Name',
		'menu_name'                      => 'Locations',
		'view_item'                      => 'View Location',
		'popular_items'                  => 'Popular Locations',
		'separate_items_with_commas'     => 'Separate locations with commas',
		'add_or_remove_items'            => 'Add or remove locations',
		'choose_from_most_used'          => 'Choose from the most used locations',
		'not_found'                      => 'No locations found'
	);

	register_taxonomy(
		'location',
		array( 'product' ),
		array( 
			'label' => __( 'Locations' ),
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'products-by-location')
		)
	);

	// Facilities
	$labels = array(
		'name'                           => 'Facilities',
		'singular_name'                  => 'Facility',
		'search_items'                   => 'Search Facilities',
		'all_items'                      => 'All Facilities',
		'edit_item'                      => 'Edit Facility',
		'update_item'                    => 'Update Facility',
		'add_new_item'                   => 'Add New Facility',
		'new_item_name'                  => 'New Facility Name',
		'menu_name'                      => 'Facilities',
		'view_item'                      => 'View Facility',
		'popular_items'                  => 'Popular Facilities',
		'separate_items_with_commas'     => 'Separate facilities with commas',
		'add_or_remove_items'            => 'Add or remove facilities',
		'choose_from_most_used'          => 'Choose from the most used facilities',
		'not_found'                      => 'No facilities found'
	);

	register_taxonomy(
		'facility',
		array( 'product' ),
		array( 
			'label' => __( 'Facilities' ),
			'hierarchical' => true,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'products-by-facility' )
		)
	);

}
