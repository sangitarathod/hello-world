<?php 
/*Template Name: Pendingproductview  */
//echo "Pageid=".$_GET['page'];
$post_id = $_GET['postid'];
$queried_post = get_post($post_id);

global $wpdb;
$qry = "SELECT * FROM `wp_posts` WHERE post_type='product' AND post_status='publish'";
$results = $wpdb->get_results($qry); 
		
if(isset($_POST['btn_merge'])){	
	$product_id=$_POST['productlist'];	
	$product_title=get_post_field('post_title', $product_id);
	$product_color=$_POST['color'];
	$product_memory=$_POST['memory'];
	$product_brand=$_POST['brand'];
	//echo $product_id."-".$product_color."-".$product_memory."-".$product_brand;
	$_product = wc_get_product( $product_id );
	if( $_product->is_type( 'variable' ) ) {
	$v_qry="select * from wp_posts where post_type='product_variation' AND post_status='publish' and post_parent=".$product_id;
	$v_results = $wpdb->get_results($v_qry); 
	//print_r($v_qry);
	foreach($v_results as $v_result){
		$v_id=$v_result->ID;
		$v_parent_id=$v_result->post_parent;
		//echo "<br>".$v_id;
		$v_color=get_post_meta($v_id,'attribute_pa_color',true );
		$v_memory=get_post_meta($v_id,'attribute_pa_phone-capacity',true );
		$v_brand=get_post_meta($v_id,'attribute_pa_brand',true );
		//echo $v_color."-".$v_memory."-".$v_brand."<br>";
		if($product_color==$v_color && $product_memory==$v_memory && $product_brand==$v_brand)
		{
				$m_v_id=$v_result->ID;
				$v_qty=get_post_meta($m_v_id,'_stock',true );		
				//echo "<br>#".$m_v_id."-".$v_qty;
		}
	}
	}
	if( $_product->is_type( 'simple' ) ) {
	$s_qry="select * from wp_posts where post_type='product' AND post_status='publish' and ID=".$product_id;
	$s_results = $wpdb->get_results($s_qry); 
	//print_r($v_qry);
	foreach($s_results as $s_result){
		$s_id=$s_result->ID;
		//echo "<br>".$v_id;
		//$v_color=get_post_meta($v_id,'attribute_pa_color',true );
		//$v_memory=get_post_meta($v_id,'attribute_pa_phone-capacity',true );
		//$v_brand=get_post_meta($v_id,'attribute_pa_brand',true );
		//echo $v_color."-".$v_memory."-".$v_brand."<br>";
		//if($product_color==$v_color && $product_memory==$v_memory && $product_brand==$v_brand)
		//{
			//	$m_v_id=$v_result->ID;
				$s_qty=get_post_meta($s_id,'_stock',true );		
				//echo "<br>#".$s_id."-".$s_qty;
		}
	}
	
	//$product_title=get_post_field('post_title', $product_id);
	//$product_qty=get_post_meta($product_id,'_stock',true );		
	$qry_pending_products="SELECT * FROM `wp_posts` WHERE post_type='product' AND post_status='pending'  AND ID=$post_id";		
	$results_pending_products = $wpdb->get_results($qry_pending_products); 
	foreach($results_pending_products as $result_pending_product)
	{
		$pending_pro_id=$result_pending_product->ID;
		$pending_pro_author_id=$result_pending_product->post_author;
		$pending_pro_qty=get_post_meta($pending_pro_id,'_stock',true );	
		$pending_pro_price=get_post_meta($pending_pro_id,'_regular_price',true);			
		$save_vendor_qty = array(		
		$pending_pro_author_id=>array("qty"=>$pending_pro_qty,"wprice"=>$pending_pro_price)		   
		);	
		
		if($_product->is_type( 'variable' ) ) {			
			$v_qty=$v_qty+$pending_pro_qty;			
			update_post_meta($m_v_id, '_stock', $v_qty );
			$v_get_vendor=get_post_meta($m_v_id,'_vendor_quantity',true);
			if(empty($v_get_vendor)) {
				update_post_meta($m_v_id, '_vendor_quantity', $save_vendor_qty);
			}else{
				//print_r($v_get_vendor)."<br>";
				//print_r($save_vendor_qty)."<br>";
				$v_m_vendor=merge_vendor_qty($v_get_vendor,$save_vendor_qty); 								
				//print_r($v_m_vendor);
				update_post_meta($m_v_id, '_vendor_quantity', $v_m_vendor);
			}	
		}
		if( $_product->is_type( 'simple' ) ) {
			$s_qty=$s_qty+$pending_pro_qty;			
			update_post_meta($s_id, '_stock', $s_qty );			
			$get_vendor=get_post_meta($s_id,'_vendor_quantity',true);
			if(empty($get_vendor)) {
				update_post_meta($s_id, '_vendor_quantity', $save_vendor_qty);
			}
			else{
				//print_r($get_vendor)."<br>";
				//print_r($save_vendor_qty)."<br>";
				$m_vendor=merge_vendor_qty($get_vendor,$save_vendor_qty); 				
				
				//print_r($m_vendor);
				update_post_meta($s_id, '_vendor_quantity', $m_vendor);
				
			}
			
		}
		$qry_pen_pro_delete="delete from wp_posts where ID=$pending_pro_id";	
		$result_pen_pro_delete = $wpdb->get_results($qry_pen_pro_delete);		
		redirect(site_url()."/wp-admin/admin.php?page=pending-products&msg=1");
	}
}	
				
?>
<!DOCTYPE html>
<br>
<div style="overflow: hidden;">
<h1 class="wp-heading-inline">Product Details</h1>
<div style="width: 70%; float: left;margin:10px;">
<table class="wp-list-table widefat fixed striped posts" >
  <col width="15%">
  <col width="55%">	
	<tr> <td>Product title</td><td><?php echo $queried_post->post_title;?></td> </tr>
	<tr> <td>SKU</td><td><?php echo get_post_meta($post_id,'_sku',true );?></td> </tr>
	<tr> <td>Stock</td><td><?php echo get_post_meta($post_id,'_stock',true );?></td> </tr>
	<tr><td>Wholesale Price</td><td><?php echo get_post_meta($post_id,'_regular_price',true );?></td> </tr>
	<tr><td>Suggested Retail</td><td><?php echo get_post_meta($post_id,'_retail_price',true );?></td> </tr>
	<tr><td>Color</td><td><?php echo get_post_meta($post_id,'_product_color',true );?></td> </tr>
	<tr><td>Memory</td><td><?php echo get_post_meta($post_id,'_product_memory',true );?></td> </tr>
	<tr><td>Brand</td><td><?php echo get_post_meta($post_id,'_product_brand',true );?></td> </tr>
</table>
</div>
<div style="width: 25%; float: right;margin-top:10px;margin-right:15px;">
<form id="frm_merge_product" method="POST" name="frm_merge_product" action="" enctype="multipart/form-data"> 
<table class="wp-list-table widefat fixed striped posts	" >
	<tr><th colspan="2"><b>Merge Product</b></th></tr>
	<tr><td><select id="productlist" name="productlist">
		<?php 
		foreach($results as $result)
		{
			
		?>
			<option value="<?php echo $result->ID;?>"><?php echo $result->post_title;?></option>	
		<?php
			
		}
		?>
		</select>
		<div id="attrlist">
		</div>
		
		</td>
	</tr>
	<tr><td><input type="submit" name="btn_merge" id="btn_merge" value="Merge"</td></tr>
</table>
</form>
</div>
</div>
