<?php
/*
Template Name: variation_product
*/
?>
<?php
global $wpdb;
$qry = "SELECT * FROM `wp_posts` WHERE post_type='product' AND post_status='publish' ORDER BY ID desc";
$results = $wpdb->get_results($qry); 
foreach($results as $result)
{	
		$postid=$result->ID;
		
		$_product = wc_get_product( $postid );
		if( $_product->is_type( 'simple' ) ) {
		$title=$result->post_title;
		echo $postid."-".$title."<br>";
		} else {
		$v_qry = "SELECT * FROM `wp_posts` WHERE post_type='product_variation' AND post_status='publish' ORDER BY ID desc";
		$v_results = $wpdb->get_results($v_qry); 
		$data = array();
		foreach($v_results as $v_result){
				$variation_id=$v_result->ID;
				$title=$v_result->post_title;
				echo $variation_id."-".$title."<br>";
				
			}
			
		}
		
		/*if($result->post_type=='product_variation')
		{
			$variation_id=$result->ID;
			
			
			$color=get_post_meta($variation_id,'attribute_color',true );
			$memory=get_post_meta($variation_id,'attribute_phone-capacity',true );
			$brand=get_post_meta($variation_id,'attribute_brand',true );
	
 
		
			
		}*/
		
}
	
?>
