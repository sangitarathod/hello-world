<?php


function getProductType($post){

  if ($post->post_parent ) {
		$ancestors=get_post_ancestors($post->ID);
		$root=count($ancestors)-1;
		$topParentID = $ancestors[$root];
		//var_dump($topParentID);
		//echo 'a sub page:::';
	} else {
		$topParentID = $post->ID;
		//var_dump($topParentID);
		//echo 'a page:::';
	}
	
	$terms = wp_get_post_terms( $topParentID, 'tourpress_product_type');   
	$termsArrayObj = [];
	//var_dump($terms);
	foreach($terms as $term){
		$parent = $term;
		$termID = $term->term_id;
		$termSlug = $term->slug;
		
		if($term->parent){
			do{
				$parent = get_term_by( 'id', $parent->parent, 'tourpress_product_type');
				$termID = $parent->term_id;
				$termSlug = $parent->slug;	
			}
			while($parent->parent);
		}
		// push top most parent term ID
		array_push($termsArrayObj,$termSlug);
	}
	
	$termsArrayObj = array_unique($termsArrayObj);
	//var_dump($termsArrayObj);
  return $termsArrayObj;
}

/*-----------------------------------
	GET FACILITIES
------------------------------------*/
function getFacilities(){
	$terms = wp_get_post_terms( get_the_ID(), 'facility');	
	if(count($terms) > 0){
		ob_start();
		//echo json_encode($terms);
		echo '<p style=""><span style="font-weight:bold;">FACILITIES</span></p>';
		echo '<div class="single-inner-facilities">';

		foreach($terms as $term){
			echo '<div class="single-inner-facility">';	
			echo '<i class="fa-check-circle-o"></i> '.$term->name;
			echo '</div>';
		}

		echo '</div>';
		$text = ob_get_clean();
		return $text;
	}
	return "";
}


/*-----------------------------------
	GET CHILD POLICY
------------------------------------*/
function getChildPolicy(){

	$policyText = get_post_meta( get_the_ID(), 'child_policy', true );
	if(strlen($policyText) > 0){
		ob_start();
		//echo json_encode($terms);
		echo '<div class="single-inner-policy-wrap"><p style=""><span style="font-weight:bold;">Child Policy</span></p>';
		echo '<div class="single-inner-policy">';

		echo $policyText;

		echo '</div></div>';
		$text = ob_get_clean();
		return $text;
	}
	return "";
}


/*-----------------------------------
	GET CHECK IN CHECKOUT POLICY
------------------------------------*/
function getCheckinPolicy(){

	$policyText = get_post_meta( get_the_ID(), 'checkin_policy', true );
	if(strlen($policyText) > 0){
		ob_start();
		//echo json_encode($terms);
		echo '<div class="single-inner-policy-wrap"><p style=""><span style="font-weight:bold;">Check-In/Check-Out Policy</span></p>';
		echo '<div class="single-inner-policy">';

		echo $policyText;

		echo '</div></div>';
		$text = ob_get_clean();
		return $text;
	}
	return "";
}

/*-----------------------------------
	GET CHECK IN CHECKOUT POLICY
------------------------------------*/
function getCancellationPolicy(){

	$policyText = get_post_meta( get_the_ID(), 'cancellation_policy', true );
	if(strlen($policyText) > 0){
		ob_start();
		//echo json_encode($terms);
		echo '<div class="single-inner-policy-wrap"><p style=""><span style="font-weight:bold;">Cancellation Policy</span></p>';
		echo '<div class="single-inner-policy">';

		echo $policyText;

		echo '</div></div>';
		$text = ob_get_clean();
		return $text;
	}
	return "";
}

/* LIMIT TEXT */
function limit_text($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = substr($text, 0, $pos[$limit]) . '...';
	}
	return $text;
}


