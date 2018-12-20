<?php
/*Template Name: exportdata1  */
global $wpdb;
$qry = "SELECT * FROM `wp_posts` WHERE post_type='product' AND post_status='publish' ORDER BY ID desc";
$results = $wpdb->get_results($qry); 
if(count($results) > 0){
    $delimiter = ",";
    $filename = "products_" . date('Y-m-d') . ".csv";    
    //create a file pointer
   // $f = fopen('php://memory', 'w');
    //$f = fopen($filename, 'w'); 
    $f = fopen('php://output', 'w');
    //$f=fopen($filename,'w');
    //set column headers
    
    $fields = array('id','product_title', 'product_sku', 'product_stock', 'product_wholsale_price', 'product_suggested_retail', 'product_color','product_memory','product_brand');
    fputcsv($f, $fields, $delimiter);    
    //output each row of the data, format line as csv and write to file pointer
    foreach($results as $result){
		$postid=$result->ID;
		/* variable prodcut check statrt*/
		$_product = wc_get_product( $postid );
		if( $_product->is_type( 'simple' ) ) {
			$color='';
			foreach(wc_get_product_terms($postid, 'pa_color' ) as $attribute_value ){
			// Outputting the attibute values one by one
			if($color=='')
			{
				$color=$attribute_value;
			}else{
				$color=$color.",".$attribute_value;
			}
		}
		foreach( wc_get_product_terms($postid, 'pa_brand' ) as $attribute_value_c ){
			// Outputting the attibute values one by one
			if($brand=='')
			{
				$brand=$attribute_value_c;
			}else{
				$brand=$brand.",".$attribute_value_c;
			}
		}foreach( wc_get_product_terms($postid, 'pa_phone-capacity' ) as $attribute_value_m ){
			// Outputting the attibute values one by one
			if($memory=='')
			{
				$memory=$attribute_value_m;
			}else{
				$memory=$memory.",".$attribute_value_m;
			}
		}	
		
		$sku=get_post_meta($postid,'_sku',true );
        $stock=get_post_meta($postid,'_stock',true );
        $wholsale_price=get_post_meta($postid,'_regular_price',true );
        $retail_price=get_post_meta($postid,'_retail_price',true );
        		
        $lineData = array($postid,$result->post_title,$sku,$stock,$wholsale_price,$retail_price,$color,$memory,$brand);
        fputcsv($f, $lineData, $delimiter);
		 }else {
		$v_qry = "SELECT * FROM `wp_posts` WHERE post_type='product_variation' AND post_status='publish' AND post_parent=$postid ORDER BY ID desc";
		$v_results = $wpdb->get_results($v_qry); 
		
		foreach($v_results as $v_result){
				$variation_id=$v_result->ID;			
				$sku1=get_post_meta($variation_id,'_sku',true );
				$stock1=get_post_meta($variation_id,'_stock',true );
				$wholsale_price1=get_post_meta($variation_id,'_regular_price',true );
				$retail_price1=get_post_meta($variation_id,'_retail_price',true );
				$color=get_post_meta($variation_id,'attribute_pa_color',true );
				$brand=get_post_meta($variation_id,'attribute_pa_device-condition',true );
				$memory=get_post_meta($variation_id,'attribute_pa_phone-capacity',true );
				$lineData_ar[]= array($variation_id,$v_result->post_title,$sku1,$stock1,$wholsale_price1,$retail_price1,$color1,$memory1,$brand1);
			}
			
		}		
		/* variable prodcut check end*/
		
       }
       
       if(count($lineData_ar)>0){
           for($a=0;$a<count($lineData_ar);$a++){
               $lineData1 = $lineData_ar[$a];
               fputcsv($f, $lineData1, $delimiter);
	        }
	  }
      
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;
?>
