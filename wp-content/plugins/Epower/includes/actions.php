<?php
/**
 * Actions
 *
 * @package     EPower
 * @subpackage  Actions
 * @copyright   Copyright (c) 2018, Neosoft Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Add config menus to the Admin area
add_action('admin_menu', 'epower_adminmenu');

// Register settings
add_action('admin_init', 'epower_register');
