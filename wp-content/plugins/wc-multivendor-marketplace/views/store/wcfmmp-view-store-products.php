<?php
/**
 * The Template for displaying all store products.
 *
 * @package WCfM Markeplace Views Store/products
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

?>

<?php do_action( 'wcfmmp_store_before_products', $store_user->get_id() ); ?>

<div class="" id="products">
	<div class="product_area">
	
		<?php do_action( 'wcfmmp_before_store_product', $store_user->data, $store_info ); ?>
	
		<?php if ( have_posts() ) { ?>
	
			<?php woocommerce_product_loop_start(); ?>
	
				<?php while ( have_posts() ) : the_post(); ?>
	
					<?php wc_get_template_part( 'content', 'product' ); ?>
	
				<?php endwhile; // end of the loop. ?>
	
			<?php if( function_exists( 'listify_php_compat_notice') ) { ?>
				</div>
			<?php } else { ?>
			  <?php woocommerce_product_loop_end(); ?>
			<?php } ?>
			
			<?php wcfmmp_content_nav( 'nav-below' ); ?>
	
		<?php } else { ?>
	
			<p class="wcfmmp-info"><?php _e( 'No products were found of this vendor!', 'wc-multivendor-marketplace' ); ?></p>
	
		<?php } ?>
		
		<?php do_action( 'wcfmmp_after_store_product_loop', $store_user->get_id(), $store_info ); ?>
		
	</div><!-- #products -->
</div><!-- .product_area -->

<?php do_action( 'wcfmmp_store_after_products', $store_user->get_id() ); ?>