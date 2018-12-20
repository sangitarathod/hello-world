<?php
/**
 * The Template for displaying store banner.
 *
 * @package WCfM Markeplace Views Store/products
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$banner = $store_user->get_banner();

if( !$banner ) {
	$banner = $WCFMmp->plugin_url . 'assets/images/default_banner.jpg';
}

?>

<?php do_action( 'wcfmmp_store_before_bannar', $store_user->get_id() ); ?>

<section class="banner_area">
  <?php do_action( 'wcfmmp_store_before_bannar_image', $store_user->get_id() ); ?>
  
	<div class="banner_img"><img src="<?php echo $banner; ?>" alt="banner"/></div>
	
	<?php do_action( 'wcfmmp_store_after_bannar_image', $store_user->get_id() ); ?>
	
	<?php if( apply_filters( 'wcfm_is_allow_store_name_on_banner', true ) ) { ?>
		<div class="banner_text">
			<?php do_action( 'wcfmmp_store_before_bannar_text', $store_user->get_id() ); ?>
			
			<h2><?php echo $store_info['store_name']; ?></h2>
			
			<?php do_action( 'wcfmmp_store_after_bannar_text', $store_user->get_id() ); ?>
		</div>
	<?php } ?>
	
</section>

<?php do_action( 'wcfmmp_store_after_bannar', $store_user->get_id() ); ?>