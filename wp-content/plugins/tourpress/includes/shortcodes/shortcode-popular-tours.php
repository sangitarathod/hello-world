<?php

/*--------------------------------------------------
  POPULAR TOURS
--------------------------------------------------*/
function tourpress_popular_tours($atts, $content){
	$xplAPI = new Explorer_Api();
	$xplResult = $xplAPI->authenticate('');
  
  //$result = $xplAPI->get_productsSummary( $productType, null, null, null, $productID, $date_in, $date_out, $pax_types, $pax_ages, $pax_units );
	$result = $xplAPI->get_productList( /*$productType =*/ 'TOU', /*$location =*/ null, /*$name =*/ null, /*$productIDs =*/ null,
						/*$amendedFrom =*/ null, /*$isChildren =*/ false, /*$isDetailed =*/ false, /*$isFromPrice =*/ true, 
						/*$isImages =*/ true, /*$isText =*/ true, /*$isFacilities =*/ true, /*$isLocations =*/ false,
						/*$isPolicies =*/ true, /*$isFeatured =*/ false, /*$isPreferred =*/ false, /*$isSpecials =*/ false,
						/*$isRates =*/ false, /*$ratesFrom =*/ null, /*$ratesTo =*/ null );
  
  //$result = $api->get_products( null, null, null,'44633', null );

	ob_start();
	
  if ($xplAPI->error( $result ) )
	{ 
    $text = 'Error ' . $result->code . ': ' . $result->description;
	}
  else
	{
    //$text = tourpress_product_result($result->allProductResults); 
	//	var_dump($result->allProducts);
		//tourpress_deals_result($result->allProducts);
		tourpress_update_products($result->allProducts);
    
	}
	
	$text = ob_get_clean();
	return $text;
}

add_shortcode('xpl_popular_tours', 'tourpress_popular_tours');