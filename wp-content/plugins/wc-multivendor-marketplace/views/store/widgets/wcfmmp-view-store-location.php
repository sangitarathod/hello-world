<?php
/**
 * The Template for displaying store sidebar location.
 *
 * @package WCfM Markeplace Views Store Sidebar Location
 *
 * For edit coping this to yourtheme/wcfm/store/widgets
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

?>

<div id="wcfmmp-store-map"></div>
<?php
	$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																											"store_lat" => array( 'type' => 'hidden', 'value' => $store_lat ),
																											"store_lng" => array( 'type' => 'hidden', 'value' => $store_lng ),
																											) );
?>