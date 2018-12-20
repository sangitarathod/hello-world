<?php
/**
 * TourPress Uninstall
 *
 * Uninstalling TourPress deletes user roles, pages, tables, and options.
 *
 * @author      Explorer Technologies
 * @category    Core
 * @package     TourPress/Uninstaller
 * @version     1.0.0
 */
	// For old versions
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
	
	// Delete options
	delete_option('tourpress_servicetype');
	delete_option('tourpress_service_url');
	delete_option('tourpress_channelID'); 
	delete_option('tourpress_password');		
	delete_option('tourpress_bookstyle'); 
	delete_option('tourpress_bookheight'); 
	delete_option('tourpress_bookwidth'); 
	delete_option('tourpress_bookqs'); 
	delete_option('tourpress_booktext'); 
	delete_option('tourpress_update_frequency');
	delete_option('tourpress_unlinked_products');	
	
?>
