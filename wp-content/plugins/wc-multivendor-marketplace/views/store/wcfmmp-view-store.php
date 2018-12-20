<?php
/**
 * The Template for displaying store.
 *
 * @package WCfM Markeplace Views Store
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$store_user   = wcfmmp_get_store( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();

get_header( 'shop' );
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>
<?php do_action( 'wcfmmp_before_store' ); ?>

<div id="wcfmmp-store" class="wcfmmp-single-store-holder">
	<div id="wcfmmp-store-content" class="wcfmmp-store-page-wrap woocommerce" role="main">
			
		<?php $WCFMmp->template->get_template( 'store/wcfmmp-view-store-banner.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) ); ?>
		<?php $WCFMmp->template->get_template( 'store/wcfmmp-view-store-header.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) ); ?>

		<?php do_action( 'wcfmmp_after_store_header', $store_user->data, $store_info ); ?>
            
    <div class="body_area">
			
				<div class="rgt right_side">
						<div id="tabsWithStyle" class="tab_area">
						  
							<?php do_action( 'wcfmmp_before_store_tabs', $store_user->data, $store_info ); ?>
						  
							<?php $WCFMmp->template->get_template( 'store/wcfmmp-view-store-tabs.php', array( 'store_user' => $store_user, 'store_info' => $store_info, 'store_tab' => $store_tab ) ); ?>
						  
						  <?php do_action( 'wcfmmp_after_store_tabs', $store_user->data, $store_info ); ?>
						  
						  <?php 
								switch( $store_tab ) {
									case 'about':
										$WCFMmp->template->get_template( 'store/wcfmmp-view-store-about.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) );
										break;
										
									case 'policies':
										$WCFMmp->template->get_template( 'store/wcfmmp-view-store-policies.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) );
										break;
										
									case 'reviews':
										$WCFMmp->template->get_template( 'store/wcfmmp-view-store-reviews.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) );
										break;
										
									case 'followers':
										$WCFMmp->template->get_template( 'store/wcfmmp-view-store-followers.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) );
										break;
										
									default:
										$WCFMmp->template->get_template( 'store/wcfmmp-view-store-products.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) );
										break;
								}	
						  ?>
							
						</div><!-- .tab_area -->
					</div><!-- .right_side -->
					
					
					
					<?php $WCFMmp->template->get_template( 'store/wcfmmp-view-store-sidebar.php', array( 'store_user' => $store_user, 'store_info' => $store_info ) ); ?>
					  
    </div><!-- .body_area -->

	</div><!-- .wcfmmp-store-page-wrap -->
</div><!-- .wcfmmp-single-store-holder -->

<div class="wcfm-clearfix"></div>

<?php do_action( 'wcfmmp_after_store' ); ?>
<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>