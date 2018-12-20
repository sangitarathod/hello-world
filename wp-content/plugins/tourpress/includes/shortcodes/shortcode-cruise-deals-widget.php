<?php
//function tourpress_cruise_deals2(){}
function tourpress_cruise_deals($atts, $content){
	$xplAPI = new Explorer_Api();
	$xplResult = $xplAPI->authenticate('');
  
  //$result = $xplAPI->get_productsSummary( $productType, null, null, null, $productID, $date_in, $date_out, $pax_types, $pax_ages, $pax_units );
	$result = $xplAPI->get_productList( /*$productType =*/ 'CRZ', /*$location =*/ null, /*$name =*/ null, /*$productIDs =*/ null,
						/*$amendedFrom =*/ null, /*$isChildren =*/ false, /*$isDetailed =*/ false, /*$isFromPrice =*/ true, 
						/*$isImages =*/ true, /*$isText =*/ true, /*$isFacilities =*/ true, /*$isLocations =*/ false,
						/*$isPolicies =*/ true, /*$isFeatured =*/ false, /*$isPreferred =*/ false, /*$isSpecials =*/ true,
						/*$isRates =*/ false, /*$ratesFrom =*/ null, /*$ratesTo =*/ null );
  //var_dump($result);
	
	ob_start();
	
  if ($xplAPI->error( $result ) )
	{ 
    $text = 'Error ' . $result->code . ': ' . $result->description;
	}
  else
	{
    //$text = tourpress_product_result($result->allProductResults); 
		//echo json_encode($result->allProducts);
		tourpress_deals_result($result->allProducts);
		//tourpress_update_products($result->allProducts);
	}
	
	$text = ob_get_clean();
	return $text;
}
add_shortcode('xpl_cruise_deals_widget', 'tourpress_cruise_deals');

/*------------------------------------------------------------------*/
function tourpress_deals_result($allProducts)
{
	if (!empty( $allProducts ))
	{
		echo '<div class="row">';	
	 	foreach ($allProducts->XPLProduct as $product)
		{
			$productPost = tourpress_getPost($product->id);
			$productPost = reset($productPost->posts);
			$imageURL = get_the_post_thumbnail_url($productPost,'full');
			$imageURL = (strlen($imageURL)>0)?$imageURL:'/wp-content/uploads/2017/02/placeholder.png';
			?>
				<div class="col-sm-4">
					<div class="cruise-deal">
						<a href="<? echo get_post_permalink($productPost->ID) ?>"></a>
						<div class="cruise-deal-thumb" style="background-image: url(<? echo $imageURL ?>)"></div>
						<div class="cruise-deal-title"><? echo $product->name ?></div>	
					</div>
				</div>
			<?php
		}
		echo '</div>';
	}
}
  