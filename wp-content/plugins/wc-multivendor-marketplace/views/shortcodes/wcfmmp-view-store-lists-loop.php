<?php
/**
 * The Template for displaying store sidebar category.
 *
 * @package WCfM Markeplace Views Store Sidebar Category
 *
 * For edit coping this to yourtheme/wcfm/store/widgets
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

?>

<div id="wcfmmp-stores-wrap">
	<div class="wcfmmp-stores-content">
		<?php if ( !empty( $stores )  ) : ?>
			<ul class="wcfmmp-store-wrap">
				<?php
				foreach ( $stores as $store_id => $store_name ) {
					$store_user      = wcfmmp_get_store( $store_id );
					$store_info      = $store_user->get_shop_info();
					$gravatar        = $store_user->get_avatar();
					$banner          = $store_user->get_banner();
					if( !$banner ) {
						$banner = $WCFMmp->plugin_url . 'assets/images/default_banner.jpg';
					}
					$store_name      = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'wc-multivendor-marketplace' );
					$store_url       = wcfmmp_get_store_url( $store_id );
					$store_address   = $store_user->get_address_string(); 
					$store_description = $store_user->get_shop_description();
					?>

					<li class="wcfmmp-single-store woocommerce coloum-<?php echo $per_row; ?>">
						<div class="store-wrapper">
							<div class="store-content">
								<div class="store-info" style="background-image: url( '<?php echo $banner; ?>');"></div>
							</div>
							<div class="store-footer">
							
								<div class="store-avatar lft">
									<img src="<?php echo $gravatar; ?>" alt="Logo"/>
								</div>
								
								<div class="store-data-container rgt">
									<div class="store-data">
										<h2><a href="<?php echo $store_url; ?>"><?php echo $store_name; ?></a></h2>
										
										<div class="bd_rating">
											<?php $store_user->show_star_rating(); ?>
									
											<?php do_action( 'after_wcfmmp_store_list_rating', $store_id, $store_info ); ?>
										<div class="spacer"></div>
										</div>
										<?php if ( $store_address && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_id, 'vendor_address' ) ): ?>
											<p class="store-address"><?php echo $store_address; ?></p>
										<?php endif ?>

										<?php if ( !empty( $store_info['phone'] ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_id, 'vendor_phone' ) ) { ?>
											<p class="store-phone">
												<i class="fa fa-phone" aria-hidden="true"></i> <?php echo esc_html( $store_info['phone'] ); ?>
											</p>
										<?php } ?>
										<?php if ( $store_description && apply_filters( 'wcfm_is_allow_store_list_about', false ) ) { ?>
											<p class="store-phone">
												<?php 
												$pos = strpos( $store_description, ' ', 100 );
												echo substr( $store_description, 0, $pos ) . '...'; 
												?>
											</p>
										<?php } ?>
										<?php do_action( 'wcfmmp_store_list_after_store_info', $store_id, $store_info ); ?>
									</div>
								</div>
								<div class="spacer"></div>
								<a href="<?php echo $store_url; ?>" class="wcfmmp-visit-store"><?php _e( 'Visit <span>Store</span>', 'wc-multivendor-marketplace' ); ?></a>
								
								<?php do_action( 'wcfmmp_store_list_footer', $store_id, $store_info ); ?>
							</div>
						</div>
					</li>

				<?php } ?>
				<div class="wcfm-clearfix"></div>
			</ul> <!-- .wcfmmp-store-wrap -->

			<?php
			$all_stores = $WCFMmp->wcfmmp_vendor->wcfmmp_get_vendor_list( true, '', '', $search_query );
			$user_count   = count($all_stores);
			$num_of_pages = ceil( $user_count / $limit );

			if ( $num_of_pages > 1 ) {
				$args = array(
						'paged'           => $paged,
						'search_query'    => $search_query,
						'pagination_base' => $pagination_base,
						'num_of_pages'    => $num_of_pages,
				);
				$WCFMmp->template->get_template( 'shortcodes/wcfmmp-view-store-lists-pagination.php', $args );
			}
			?>

		<?php else:  ?>
			<p class="wcfmmp-error"><?php _e( 'No vendor found!', 'wc-multivendor-marketplace' ); ?></p>
		<?php endif; ?>
	</div>
</div>