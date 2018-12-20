<?php

function tourpress_specials_widget__HOLD($atts, $content){
	$xplAPI = new Explorer_Api();
	$xplResult = $xplAPI->authenticate('');
  
  //$result = $xplAPI->get_productsSummary( $productType, null, null, null, $productID, $date_in, $date_out, $pax_types, $pax_ages, $pax_units );
	$result = $xplAPI->get_products( /*$productType =*/ 'ACC', /*$location =*/ null, /*$name =*/ null, /*$productIDs =*/ null,
						/*$amendedFrom =*/ null, /*$isChildren =*/ true, /*$isDetailed =*/ false, /*$isFromPrice =*/ true, 
						/*$isImages =*/ true, /*$isText =*/ true, /*$isFacilities =*/ true, /*$isLocations =*/ false,
						/*$isPolicies =*/ true, /*$isFeatured =*/ false, /*$isPreferred =*/ false, /*$isSpecials =*/ false,
						/*$isRates =*/ false, /*$ratesFrom =*/ null, /*$ratesTo =*/ null );
  //var_dump($result);
	//tourpress_update_products($result->allProducts);
	ob_start();

  if ($xplAPI->error( $result ) ) 
    $text = 'Error ' . $result->code . ': ' . $result->description;
  else
	{
		$text = tourpress_specials_results($result->allProducts); 	
	}
  $text = ob_get_clean();
	return $text;
}
//get data from cached specials
function tourpress_specials_widget($atts, $content){
	$string = get_option('tourpress_specials_cache');
	$specials_cache = json_decode($string);
	
	ob_start();
	tourpress_specials_results($specials_cache->allProducts);
	$text = ob_get_clean();
	
	return $text;
}
add_shortcode('xpl_specials_widget', 'tourpress_specials_widget');

/**************************************/

function tourpress_specials_results($allProducts){
	if (!empty( $allProducts ))
	{
		echo '<div class="row">';
		$counter = 0;
		foreach ($allProducts->XPLProduct as $product)
		{
			if($product->myPriceFrom != null){
			$productPost = tourpress_getPost($product->id);
			$productPost = reset($productPost->posts);
			$imageURL = get_the_post_thumbnail_url($productPost,'full');
			$imageURL = (strlen($imageURL)>0)?$imageURL:'/wp-content/uploads/2017/02/placeholder.png';
			//var_dump($productPost);
		?>
			<div class="col-md-4">
				<div class="specials-wrapper">
					<div class="specials-thumb" style="background-image:url(<? echo $imageURL; ?>)">
						<img src="<? echo $imageURL; ?>" />
						<div class="specials-title">
							<a href="<? echo get_post_permalink($productPost->ID); ?>"><? echo $product->name; ?></a>
						</div>	
					</div>	
					
					<div class="specials-details">
						<div class="specials-highlights">
							<label>Highlights Include: </label>	
							<? 
								//echo get_post_meta($productPost->ID , 'special_text', true );
								$text = strip_tags ( get_post_meta($productPost->ID , 'special_text', true ));
								$maxPos = 50;
								if (strlen($text) > $maxPos)
								{
										$lastPos = ($maxPos - 3) - strlen($text);
										$text = substr($text, 0, strrpos($text, ' ', $lastPos)) . '...';
								}
								echo '<span>'.$text.'</span>';
							?>
						</div>
						<div class="specials-price">
							<label>Price starts from</label>
							<strong><? echo $product->myPriceFrom->myCurrency->code.' '.$product->myPriceFrom->price ?></strong>
							<span><? echo $product->myPriceFrom->priceType ?></span>
						</div>
					</div>
				</div>		
			</div>
		<?php
			$counter++;
			if($counter == 6 )
				break;
		}
		}
		echo '</div>';
	}	
}
  