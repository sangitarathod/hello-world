<?php
/**
 * Template Name: Confirmation
 *
 */

get_header();
?>
		
<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
		
			<div class="entry-content" itemprop="mainContentOfPage">
			
				<div class="section mcb-section full-width sell-your-device checkout-congratulations" style="padding-top:0px; padding-bottom:0px; background-color:">
					<div class="section_wrapper mcb-section-inner">
						<div class="wrap mcb-wrap one  column-margin-0px clearfix" style="padding:16% 0% 11% 0%; background-color:">
							<div class="column mcb-column one-sixth column_placeholder">
								<div class="placeholder">&nbsp;</div>
							</div>
						
							<div class="column mcb-column two-third column_column  column-margin-">
								<div class="column_attr align_center" style="">
<?
if( $_COOKIE['giz_method'] == 'fedex' && $_COOKIE['giz_success'] == 1){
	$message_1 = "Congrats! You're all done.";
	$message_2 = 'Download your FedEx shipping label below + follow these simple instructions:';
	$box_1_img = '/wp-content/themes/gizmogul/images/confirmation_print_giz_icon.png';
	$box_1_ver = 'Download & Print Label';
	$box_2_img = '/wp-content/themes/gizmogul/images/confirmation_ship_giz_icon.png';
	$box_2_ver = 'Pack your Devices';
	$box_3_img = '/wp-content/themes/gizmogul/images/confirmation_cash_giz_icon.png';
	$box_3_ver = 'Ship & Get Paid!';
}
elseif( $_COOKIE['giz_method'] == 'usps' && $_COOKIE['giz_success'] == 1 ){
	$message_1 = "Congrats! You're all done.";
	$message_2 = 'Download your USPS shipping label below + follow these simple instructions:';
	$box_1_img = '/wp-content/themes/gizmogul/images/confirmation_print_giz_icon.png';
	$box_1_ver = 'Download & Print Label';
	$box_2_img = '/wp-content/themes/gizmogul/images/confirmation_ship_usps_giz_icon.png';
	$box_2_ver = 'Pack your Devices';
	$box_3_img = '/wp-content/themes/gizmogul/images/confirmation_cash_giz_icon.png';
	$box_3_ver = 'Ship & Get Paid!';
} elseif( $_COOKIE['giz_method'] == 'standard' ){
	$message_1 = "Congrats! You're all done.";
	$message_2 = 'Your shipping mailer is on the way. Once you get it, here’s what to do:';
	$box_1_img = '/wp-content/themes/gizmogul/images/confirmation_pack_giz_icon.png';
	$box_1_ver = 'Pack Up Your Devices';
	$box_2_img = '/wp-content/themes/gizmogul/images/confirmation_mail_giz_icon.png';
	$box_2_ver = 'Ship Back for Free';
	$box_3_img = '/wp-content/themes/gizmogul/images/confirmation_cash_giz_icon.png';
	$box_3_ver = ' Get Paid!';
} else {
	$message_1 = "Eek! Something went wrong ;(";
	$message_2 = "Sorry we couldn't print a label right now,<br/>but we will email you one ASAP";
}
?>								
									<h1 class="title"><?=$message_1?></h1>
									<h4 class="page-subtitle" style="padding-bottom: 15px;"><?=$message_2?></h4>


									<? if( $box_1_img && $_COOKIE['giz_success'] == 1 ){ ?>
									<table border=0>
									<tr>
										<td style='text-align:center;width:33%;'><?=$box_1_ver?></td>
										<td style='text-align:center;width:33%;'><?=$box_2_ver?></td>
										<td style='text-align:center;width:33%;'><?=$box_3_ver?></td>
									</tr>		
									<tr>
										<td style='text-align:center;'><img src='<?=$box_1_img?>' style='width:128px;' /></td>
										<td style='text-align:center;'><img src='<?=$box_2_img?>' style='width:128px;' /></td>	
										<td style='text-align:center;'><img src='<?=$box_3_img?>' style='width:128px;' /></td>
									</tr>
									</table>
									<? } ?>
									
															
									<? if( $_COOKIE['giz_success'] == 1 ) { ?>
										<a class="button  button_js" href="http://labels.gizmogul.com/print/gizmogul.com_shipping_label_<?=$_COOKIE['giz_id']?>_<?=$_COOKIE['giz_label']?>.pdf" target="_blank" style=" background-color:#ffffff !important;"><span class="button_label">DOWNLOAD SHIPPING LABEL</span></a>
									<? } ?>
									<br/>
									<? //print_r($_COOKIE); ?>

<?									
//echo ">".print_r(get_woocommerce_product_list())."<";
// ********* Get all products and variations and sort alphbetically, return in array (title, sku, id)*******
function get_woocommerce_product_list() {
	$full_product_list = array();
	$loop = new WP_Query( array( 'post_type' => array('product', 'product_variation'), 'posts_per_page' => -1 ) );
 
	while ( $loop->have_posts() ) : $loop->the_post();
		$theid = get_the_ID();
		$product = new WC_Product($theid);
		// its a variable product
		if( get_post_type() == 'product_variation' ){
			$parent_id 	= wp_get_post_parent_id($theid );
			$sku 		= get_post_meta($theid, '_sku', true );
			$thetitle 	= get_the_title( $parent_id);
	        	$price 		= get_post_meta($values['product_id'] , '_price', true);
			$taxonomies 	= array(
				'pa_phone-brand',
				'pa_phone-carrier',
				'pa_phone-model',
				'pa_phone-capacity',
				'pa_phone-condition',
				'pa_ipod-model',
				'pa_ipod-gen',
				'pa_ipod-memory',
				'pa_is-your-ipod-engraved',
				'pa_ipod-condition',
				'pa_watch-brand',
				'pa_watch-model',
				'pa_watch-size',
				'pa_watch-color',
				'pa_watch-band',
				'pa_watch-condition',
				'pa_tablet-brand',
				'pa_tablet-model',
				'pa_tablet-carrier',
				'pa_tablet-screensize',
				'pa_tablet-capacity',
				'pa_tablet-processor',
				'pa_tablet-mpn',
				'pa_tablet-condition',
				'pa_ipad-carrier',
				'pa_ipad_model',
				'pa_ipad-capacity',
				'pa_ipad-condition',
				'pa_computer-model',
				'pa_computer-screen-size',
				'pa_computer-release-year',
				'pa_computer-retina-display',
				'pa_computer-processor-speed',
				'pa_computer-hard-drive',
				'pa_computer-condition'
			);		
			$terms = wp_get_post_terms( $values['product_id'], $taxonomies, $args );

			foreach($terms as $term) { 
				if( $term->taxonomy == 'pa_phone-condition' ){
					$condition 	= $term->name;
				}
			}
			
        // its a simple product
        } else {
            $sku = get_post_meta($theid, '_sku', true );
            $thetitle = get_the_title();
        }
        // add product to array but don't add the parent of product variations
        if (!empty($sku)) $full_product_list[] = array($thetitle, $sku, $theid);
    endwhile; wp_reset_query();
    // sort into alphabetical order, by title
    sort($full_product_list);
    return $full_product_list;
}
?>																		
								</div>
							</div>
							<div class="column mcb-column one-sixth column_placeholder">
								<div class="placeholder">&nbsp;</div>
							</div>
						</div>
					</div>
				</div>
			
				<?php 
					while ( have_posts() ){
						the_post();						// Post Loop
						mfn_builder_print( get_the_ID() );	// Content Builder & WordPress Editor Content
					}
				?>

			</div>
			
			<?php if( mfn_opts_get('page-comments') ): ?>
				<div class="section section-page-comments">
					<div class="section_wrapper clearfix">
					
						<div class="column one comments">
							<?php comments_template( '', true ); ?>
						</div>
						
					</div>
				</div>
			<?php endif; ?>
	
		</div>
		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer(); ?>