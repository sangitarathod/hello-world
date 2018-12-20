<?php
/**
 * Scripts
 *
 * @package     TourPress
 * @subpackage  Scripts
 * @copyright   Copyright (c) 2016, Explorer Technologies
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !is_admin() ) wp_enqueue_script('jquery');
