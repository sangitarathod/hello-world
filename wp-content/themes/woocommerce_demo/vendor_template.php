<?php
/*
Template Name: vendor_template
*/

get_header();

?>

	<div id="primary" class="site-content">
		
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>			
				<?php //get_template_part( 'content', 'page' ); ?>				
				<?php comments_template( '', true ); ?>				
			<?php endwhile; // end of the loop. ?>
			<?php 			
				 if(isset($_POST['product_csv_submit'])){					   
					//echo "inside submit bloack";
					   
					if( ! empty( $_FILES ) ) 
				    {
					  $file=$_FILES['product_csv'];   // file array
					  $file1=$_FILES['product_csv']['name'];
					  $upload_dir=wp_upload_dir();
					  $path=$upload_dir['basedir'].'/csvfiles/';  //upload dir.
					  if(!is_dir($path)) { mkdir($path); }
					  $attachment_id = upload_user_file( $file ,$path);					  
					  $filename=$path.$file1;
					  $rows = array_map('str_getcsv', file($filename));
					  $header = array_shift($rows);
					  $csv = array();
					  foreach($rows as $row) {
					    $csv[] = array_combine($header, $row);
					  }		
							
					  foreach( $csv as $item ) 
					  {		
						//$user_id = get_current_user(); // this has NO SENSE AT ALL, because wp_insert_post uses current user as default value
						//$user_id = $some_user_id_we_need_to_use; // So, user is selected..
						$user_id = get_current_user_id();									  						
						/* Check existing sku start*/
						$results=checkSku($item['product_sku']);														
						if(count($results)>0){
							foreach($results as $result)
							{
								$postid=$result->ID;
								$vendorid=$result->post_author;																
							}
							if($vendorid==$user_id){								
							$qty=get_post_meta($postid,'_stock',true );
							$qty=$qty+1;							
							update_post_meta($postid, '_stock', $qty );								
							}
							if($vendorid!=$user_id){								
								$e_post_id = wp_insert_post( array(
								'post_author' => $user_id,
								'post_title' => $item['product_title'],								
								'post_status' => 'pending',
								'post_type' => "product",
							) );							
							wp_set_object_terms( $e_post_id, 'simple', 'product_type' );
							update_post_meta( $e_post_id, '_regular_price', $item['product_wholsale_price']);
							update_post_meta( $e_post_id, '_sku', $item['product_sku'] );
							update_post_meta( $e_post_id, '_manage_stock', 'yes' );							
							update_post_meta( $e_post_id, '_stock', $item['product_stock'] );							
							update_post_meta( $e_post_id, '_retail_price',$item['product_suggested_retail'] );													
							update_post_meta( $e_post_id, '_is_marketplace', 'yes' );
							update_post_meta( $e_post_id, 'post_author_override',$user_id);							
							update_post_meta( $e_post_id, '_product_color', $item['product_color'] );	
							update_post_meta( $e_post_id, '_product_memory', $item['product_memory'] );	
							update_post_meta( $e_post_id, '_product_brand', $item['product_brand'] );	
							}
						/* Check existing sku end */							
						}else{	
							/* insert product start */
							$post_id = wp_insert_post( array(
								'post_author' => $user_id,
								'post_title' => $item['product_title'],								
								'post_status' => 'pending',
								'post_type' => "product",
							) );							
							wp_set_object_terms( $post_id, 'simple', 'product_type' );
							update_post_meta( $post_id, '_regular_price', $item['product_wholsale_price']);
							update_post_meta( $post_id, '_sku', $item['product_sku'] );							
							update_post_meta( $post_id, '_stock', $item['product_stock'] );							
							update_post_meta( $post_id, '_retail_price',$item['product_suggested_retail'] );													
							update_post_meta( $post_id, '_is_marketplace', 'yes' );
							update_post_meta( $post_id, 'post_author_override',$user_id);
							update_post_meta( $post_id, '_product_color', $item['product_color'] );							
							/* insert product end */
						}
					}				    
				}
			}
			?>

 <?php get_header(); ?>

<form name="frm_upload_product" method="post" name="product_upload" action="" enctype="multipart/form-data"> 

<h3>Upload woo-commerce product CSV</h3>

<!--<input type="text" name="product_csv_name" id="product_csv_name">-->
<input type="FILE" name="product_csv" id="product_csv">
<input type="submit" name="product_csv_submit" id="product_csv_submit">
<br><Br>
<a href="<?php echo site_url();?>/index.php/exportdata"> Download Csv sample file</a>
<br><br>

</form>

<?php 
$q="select * from wp_posts where ID in(1852,1742) AND post_status='publish'";
$res=$wpdb->get_results($q); 
foreach($res as $r){
	$v=get_post_meta($r->ID,'_vendor_quantity',true);
	//print_r($v);
	//echo $r->ID;
	foreach($v as $k=>$val){
		if($k==146){
			if($val['qty']==0){
				unset($v[$k]);
				echo $r->ID."=";
				//print_r($v);
				update_post_meta($r->ID,'_vendor_quantity',$v);
				
			}else{
				echo $r->ID."=".$k."=".$val['qty'];
			}
			
		}
	}
	//print_r($v);
	
}




?>

		</div><!-- #content -->
	</div><!-- #primary -->


 



<?php get_sidebar(); ?>
<?php get_footer(); ?>
