<?php
/*
Template Name: vendor_orders
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
			//echo get_current_user_id();
			
			$vendor_orders = get_posts( array(
				'numberposts' => -1,
				'meta_key'    => '_vendor_email',
				'meta_value'  => 3,
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
				
			) );			
			//echo count($vendor_orders);
			?>
			
<div class="woocommerce">
<div class="woocommerce-MyAccount-content">
	<input type="text" name="search_order" placeholder="Enter order number"><br><br>
	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Order</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Date</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Status</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Total</span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Actions</span></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($vendor_orders as $vendor_order){	
				//echo $vendor_order->ID;
				$order = new WC_Order( $vendor_order->ID );	
				$mailer = WC()->mailer();
				$mails = $mailer->get_emails();
				//echo "<pre>";print_r($mails);echo "</pre>";
				$totals = $order->get_order_item_totals();
				
				
				//echo "......<br>";
				//print_r($totals);
							
				$items = $order->get_items();
				//print_r($items); 
				$qty;
				foreach ( $items as $item ) {
					$qty=$item['quantity'];
			?>
			<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-on-hold order">
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="Order">
					<a href="http://localhost/woocommerce_demo/index.php/vendor-order-details?oid=<?php echo $vendor_order->ID?>"><?php echo $vendor_order->ID; echo get_post_meta($vendor_order->ID,'_last_vendor',true);?></a>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Date">
					<time datetime="2018-01-24T06:08:57+00:00"><?php echo date('Y:m:d', strtotime($vendor_order->post_date));?></time>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status" data-title="Status">
					<?php echo $vendor_order->post_status; ?>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Total">
					<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span><?php echo get_post_meta( $vendor_order->ID, '_order_total', true);?></span> for <?php echo $qty;?> item
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions" data-title="Actions">
					<a href="http://localhost/woocommerce_demo/index.php/vendor-order-details/" class="woocommerce-button button view">View</a>													
				</td>
			</tr>
			<?php }}?>						
		</tbody>
	</table>
</div>
</div>

<?php 
/*$vid = get_post_meta(86,'_vendor_quantity',true);
$last_vid = get_post_meta(86,'_last_vendor',true);
print_r($vid);
echo "<br>";
foreach($vid as $k=>$v){
	if($k==$last_vid){
		$v_w=$v;
	}
}
print_r($v_w['wprice']);
print_r($vid[0]);//['wprice'];

echo $last_vid;

$current_retailer = wp_get_current_user();
$retailer_email=$current_retailer->user_email;

echo $retailer_email;

echo "======<br>";*/
//do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
if(isset($_GET['a'])==1){
	echo "OK";
}
?>



<script type="text/javascript" src="jquery.min.js"></script> 

<script type="text/javascript"> 
function doSomething(var a) { 
    $.get("vendor_orders.php?a=1"); 
    return false; 
} 
</script>

<a href="http://w3school.com" onclick="doSomething(var a);" target="_blank">Click Me!</a>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
