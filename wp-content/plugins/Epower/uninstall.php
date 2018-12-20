<?php
/**
 * EPower Uninstall
 *
 * Uninstalling EPower deletes options.
 *
 * @author      Neosoft Technologies
 * @category    Core
 * @package     EPower/Uninstaller
 * @version     1.0
 */
	// For old versions
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
	
	// Delete options
	
	delete_option('epower_api_url');
	delete_option('epower_username'); 
	delete_option('epower_password');		
	delete_option('epower_city_url'); 
	
	
?>
