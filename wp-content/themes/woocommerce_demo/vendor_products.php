<?php
/*
Template Name: vendor_products
*/

get_header();

?>
 <link rel="stylesheet" type="text/css" href="http://localhost/woocommerce_demo/wp-content/themes/woocommerce_demo/datatables/css/jquery.dataTables.css">
 <script type="text/javascript" src="http://localhost/woocommerce_demo/wp-content/themes/woocommerce_demo/datatables/js/jquery.dataTables.js"></script>
 <script type="text/javascript" src="http://localhost/woocommerce_demo/wp-content/themes/woocommerce_demo/js/custom.js"></script>
	<div id="primary" class="site-content">
		
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>			
				<?php //get_template_part( 'content', 'page' ); ?>				
				<?php comments_template( '', true ); ?>				
			<?php endwhile; // end of the loop. ?>
<table id="all-devices" class="nav display dataTable no-footer" data-page-length="10" role="grid" aria-describedby="all-devices_info">														<thead>
															<tr role="row">
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 80px;">ID</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 80px;">Image</th>
																<th class="sorting_asc" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Retail pak: activate to sort column descending" aria-sort="ascending" style="width: 65px;">Name</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 109px;">Carrier</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Device: activate to sort column ascending" style="width: 249px;">Capacity</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Condition: activate to sort column ascending" style="width: 88px;">Color</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Payment: activate to sort column ascending" style="width: 81px;">Qty</th>
																<th class="sorting" tabindex="0" aria-controls="all-devices" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 84px;">Status</th></tr>
														</thead>

														<tbody>
			<?php 
			global $wpdb;
			$current_user=get_current_user_id();
			$qry="select * from wp_posts where post_type in ('product','product_variation') and post_status='publish'";
			$results = $wpdb->get_results($qry); 	
			
			foreach($results as $result)
			{
				$pid=$result->ID;
				//echo $pid."#";
				$merged_vender_p=get_post_meta($pid,'_vendor_quantity',true);
				foreach($merged_vender_p as $key => $value){
					if($key==$current_user){
					$capacity=get_post_meta($pid,'attribute_pa_phone-capacity',true );	?>
					<tr>
						<td><?php echo $pid;?></td>
						<td>&nbsp;</td>
						<td><?php echo $result->post_title;?></td>
						<td>&nbsp;</td>
						<td><?php echo $capacity; ?></td>
						<td><?php $color; ?></td>
						<td><?php echo $value['qty']; ?></td>
						<td>Enable</td>
					</tr>
					<?php
					
					}
				}
			
			}
			$qry_vendor_products = "SELECT * FROM `wp_posts` WHERE post_type='product' AND post_status='pending' AND post_author=".$current_user;
			$results_vendor_products = $wpdb->get_results($qry_vendor_products); 
			foreach($results_vendor_products as $result_vendor_product)
			{
				
			?>
			<tr>
						<td><?php echo $result_vendor_product->ID;?></td>
						<td>&nbsp;</td>
						<td><?php echo $result_vendor_product->post_title;?></td>
						<td>&nbsp;</td>
						<td><?php echo $capacity; ?></td>
						<td><?php $color; ?></td>
						<td><?php echo $qty; ?></td>
						<td>Enable</td>
					</tr>	
			<?php	
			}
			
			?>
		<tbody>            
		</table>
		
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
