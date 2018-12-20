<?php
	/*
	Plugin Name: TourPress
	Plugin URI:  http://www.tourpress.com/
	Description: Integrate WordPress with TourPress to build specialist dynamic Wholesale, Retail, Corporate Travel & Tour Operator websites.
	Version:     1.0.0
	Author:      Explorer Technologies
	Author URI:  http://www.xpl.com.au/
	License:     GPLv2

	Copyright (c) 2003-2017 Explorer Technologies

 	TourPress is a free plugin which may be redistributed and/or modified under the terms of the GNU General Public
 	License as published by the Free Software Foundation, either version 2 of the License, or any later version.

 	TourPress is distributed WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

	You should have received a copy of the GNU General Public License along with this TourPress plugin.
	If not, refer to <http://www.gnu.org/licenses/>
	*/

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;

	// Define some useful constants
	if ( ! defined( 'TOURPRESS_VERSION' ) ) define( 'TOURPRESS_VERSION', '1.0.0' );
	if ( ! defined( 'TOURPRESS_PLUGIN_DIR' ) ) define( 'TOURPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	if ( ! defined( 'TOURPRESS_PLUGIN_URL' ) ) define( 'TOURPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	if ( ! defined( 'TOURPRESS_PLUGIN_FILE' ) ) define( 'TOURPRESS_PLUGIN_FILE', __FILE__ );

	require_once TOURPRESS_PLUGIN_DIR . 'includes/actions.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/post-types.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/scripts.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/shortcodes.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/template-functions.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/class-tourpress-api.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/class-explorer-api.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/test-explorer-api.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/admin/admin-pages.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/admin/settings.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/admin/product.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/admin/cache.php';
	require_once TOURPRESS_PLUGIN_DIR . 'templates/productMap.php';
	require_once TOURPRESS_PLUGIN_DIR . 'templates/productAvail.php';
	require_once TOURPRESS_PLUGIN_DIR . 'templates/productRates.php';

	require_once TOURPRESS_PLUGIN_DIR . 'includes/utils.php';
	require_once TOURPRESS_PLUGIN_DIR . 'includes/shortcodes/config.php';


 	// const TOURPRESS_PRODUCT_TYPE = array(
	// 	'accommodation'						=> 'ACC'
	// );

	// const TOURPRESS_PRODUCT_TYPE_TEXT = array(
	// 	'accommodation'						=> 'Hotel',
	// 	'boatcruise'							=> 'Cruise',
	// 	'charge'									=> 'Charge',
	// 	'destination-information'	=> 'Destination Information',
	// 	'events'									=> 'Event',
	// 	'meal'										=> 'Meal',
	// 	'other'										=> 'Other',
	// 	'package'									=> 'Package',
	// 	'rail'										=> 'Rail',
	// 	'rental-vehicle'					=> 'Car Hire',
	// 	'sightseeing'							=> 'Experience',
	// 	'tour'										=> 'Tour',
	// 	'transfer'								=> 'XFR'
	// );

	// const TOURPRESS_MAIN_LOCATION_CODE = array(
	// 	'christmas-island'=>'XCH',
	// 	'cocos-keeling-islands'=>'CCK',
	// 	'exmouth'=>'',
	// 	'northern-territory'=>'NT',
	// 	'papua-new-guinea'=>'PG',
	// 	'queensland'=>'QLD',
	// 	'south-australia'=>'SA',
	// 	'sydney'=>'SYD',
	// 	'vanuatu'=>'VU',
	// 	'western-australia' => 'WA'
	// );
