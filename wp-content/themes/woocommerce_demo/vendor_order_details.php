<?php
/*
Template Name: vendor_order_details
*/

get_header();
if(isset($_REQUEST['oid'])){
$order_id=$_REQUEST['oid'];
}
?>
 <div id="primary" class="site-content">
		
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>			
				<?php //get_template_part( 'content', 'page' ); ?>				
				<?php comments_template( '', true ); ?>				
			<?php endwhile; // end of the loop. ?>
			<?php 
			global $wpdb;
			$sql = "SELECT * FROM `wp_posts`  WHERE ID=".$order_id;
			$results = $wpdb->get_results($sql); 		
			foreach($results as $result){	
			$o_id=$result->ID;
			//echo $result->post_date;
			$order = new WC_Order( $result->ID );			
			$items = $order->get_items();
				//print_r($items); 
				$qty;
				foreach ( $items as $item ) {
					
			?>
<div class="woocommerce">
	<div class="woocommerce-MyAccount-content">
		<p>Order #<mark class="order-number"><?php echo $result->ID;?></mark> was placed on <mark class="order-date"><?php echo date('Y:m:d', strtotime($result->post_date));?></mark> and is currently <mark class="order-status"><?php echo $result->post_status; ?></mark>.</p><br>

		<section class="woocommerce-order-details">
			<h2 class="woocommerce-order-details__title">Order details</h2>

			<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
				<thead>
				<tr>
					<th class="woocommerce-table__product-name product-name">Product</th>
					<th class="woocommerce-table__product-table product-total">Total</th>
				</tr>
			</thead>
			<tbody>
				<tr class="woocommerce-table__line-item order_item">
					<td class="woocommerce-table__product-name product-name">
						<a href="http://localhost/woocommerce_demo/index.php/product/product-1/"><?php echo $item['name'];?></a> <strong class="product-quantity">× <?php echo $item['quantity'];?></strong>	
					</td>
					<td class="woocommerce-table__product-total product-total">
						<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span><?php echo get_post_meta( $result->ID, '_order_total', true);?></span>	
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row">Subtotal:</th>
					<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span><?php echo get_post_meta( $result->ID, '_order_total', true);?></span></td>
				</tr>
				<tr>
					<th scope="row">Shipping:</th>
					<td>Free shipping</td>
				</tr>
				<tr>
					<th scope="row">Payment method:</th>
					<td><?php echo get_post_meta( $result->ID, '_payment_method_title', true);?></td>
				</tr>
				<tr>
					<th scope="row">Total:</th>
					<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>500.00</span></td>
				</tr>
			</tfoot>
	</table>
	</section>
	<section class="woocommerce-customer-details">	
		<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
			<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">				
				<h2 class="woocommerce-column__title">Billing address</h2>
				<address>
					priya parmar<br>Near Mavdi chock<br>Rajkot - 360001<br><?php  $state_c=get_post_meta( $o_id, '_billing_country', true); $state=get_post_meta( $o_id, '_billing_state', true); echo WC()->countries->states[$state_c][$state]; ?><br>
					<?php $c=get_post_meta( $o_id, '_billing_country', true);echo WC()->countries->countries[$c];?><br>
					<p class="woocommerce-customer-details--phone">9898987878</p>
					<p class="woocommerce-customer-details--email">parmarpriya@gmail.com</p>
				</address>				
			</div><!-- /.col-1 -->
			<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
				<h2 class="woocommerce-column__title">Shipping address</h2>
				<address>
					priya parmar<br>Near Mavdi chock<br>Rajkot - 360001<br>Gujarat
				</address>
			</div><!-- /.col-2 -->
		</section><!-- /.col2-set -->	
	</section>
	<a href="http://localhost/woocommerce_demo/wp-admin/?print_packinglist=true&post=<?php echo $order_id; ?>&type=download_shipment_label&_wpnonce=d182436793">Print label</a>
</div>

</div>
<?php }} ?>
</div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
