<?php

	require_once 'shortcode-product-list.php';
	require_once 'shortcode-search-widget.php';
	require_once 'shortcode-specials-widget.php';
	require_once 'shortcode-cruise-deals-widget.php';
	require_once 'shortcode-regions-widget.php';
	require_once 'shortcode-popular-tours.php';

function tourpress_cruise_locations($atts, $content){
	$xplAPI = new Explorer_Api();
	$xplResult = $xplAPI->authenticate('');
  
  //$result = $xplAPI->get_productsSummary( $productType, null, null, null, $productID, $date_in, $date_out, $pax_types, $pax_ages, $pax_units );
	$result = $xplAPI->get_locations(null,true);
  //var_dump($result);
	
	ob_start();
	
  if ($xplAPI->error( $result ) )
	{ 
    $text = 'Error ' . $result->code . ': ' . $result->description;
	}
  else
	{
    //$text = tourpress_product_result($result->allProductResults); 
		echo json_encode($result);
		//tourpress_deals_result($result->allProducts);
		//tourpress_update_products($result->allProducts);
	}
	
	$text = ob_get_clean();
	return $text;
}
add_shortcode('xpl_cruise_locations', 'tourpress_cruise_locations');

  