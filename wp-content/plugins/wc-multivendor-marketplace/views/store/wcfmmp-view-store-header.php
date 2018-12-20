<?php
/**
 * The Template for displaying all store header
 *
 * @package WCfM Markeplace Views Store Header
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$gravatar = $store_user->get_avatar();
$email    = $store_user->get_email();
$phone    = $store_user->get_phone(); 
$address  = $store_user->get_address_string(); 

?>

<?php do_action( 'wcfmmp_store_before_header', $store_user->get_id() ); ?>

<div id="wcfm_store_header">
	<div class="wrapper">
		<div class="header_area">
			<div class="lft header_left">
			
				<?php do_action( 'wcfmmp_store_before_avatar', $store_user->get_id() ); ?>
				
				<div class="logo_area lft"><a href="#"><img src="<?php echo $gravatar; ?>" alt="Logo"/></a></div>
				
				<?php do_action( 'wcfmmp_store_after_avatar', $store_user->get_id() ); ?>
				
				<div class="address rgt">
				  <?php if( apply_filters( 'wcfm_is_allow_store_name_on_header', false ) ) { ?>
				  	<h2><?php echo $store_info['store_name']; ?></h2>
				  <?php } ?>
				  
				  <?php do_action( 'before_wcfmmp_store_header_info', $store_user->get_id() ); ?>
					<?php do_action( 'wcfmmp_store_before_address', $store_user->get_id() ); ?>
					
					<?php if( $address && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_address' ) ) { ?>
						<p><i class="fa fa-map-marker" aria-hidden="true"></i><span><?php echo $address; ?></span></p>
					<?php } ?>
					
					<?php do_action( 'wcfmmp_store_after_address', $store_user->get_id() ); ?>
					<?php do_action( 'wcfmmp_store_before_phone', $store_user->get_id() ); ?>
					
					<?php if( $phone && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_phone' ) ) { ?>
						<p><i class="fa fa-phone" aria-hidden="true"></i><span><?php echo $phone; ?></span></p>
					<?php } ?>
					
					<?php do_action( 'wcfmmp_store_after_phone', $store_user->get_id() ); ?>
					<?php do_action( 'wcfmmp_store_before_email', $store_user->get_id() ); ?>
					
					<?php if( $email && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_email' ) ) { ?>
						<p><i class="fa fa-envelope" aria-hidden="true"></i><span><a href="#"><?php echo $email; ?></a></span></p>
					<?php } ?>
					
					<?php do_action( 'wcfmmp_store_after_email', $store_user->get_id() ); ?>
					<?php do_action( 'after_wcfmmp_store_header_info', $store_user->get_id() ); ?>
				</div>
			  <div class="spacer"></div>    
			</div>
			<div class="rgt header_right">
				<div class="bd_icon_area lft">
				
				  <?php do_action( 'before_wcfmmp_store_header_actions', $store_user->get_id() ); ?>
				
					<?php do_action( 'wcfmmp_store_before_enquiry', $store_user->get_id() ); ?>
					
					<?php if( apply_filters( 'wcfm_is_pref_enquiry', true ) && apply_filters( 'wcfm_is_pref_enquiry_button', true ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'enquiry' ) ) { ?>
						<?php do_action( 'wcfmmp_store_enquiry', $store_user->get_id() ); ?>
					<?php } ?>
					
					<?php do_action( 'wcfmmp_store_after_enquiry', $store_user->get_id() ); ?>
					<?php do_action( 'wcfmmp_store_before_follow_me', $store_user->get_id() ); ?>
					
					<?php 
					if( apply_filters( 'wcfm_is_pref_vendor_followers', true ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_follower' ) ) {
						do_action( 'wcfmmp_store_follow_me', $store_user->get_id() );
					}
					?>
					
					<?php do_action( 'wcfmmp_store_after_follow_me', $store_user->get_id() ); ?>
					
					<div class="spacer"></div>   
					
					<?php do_action( 'after_wcfmmp_store_header_actions', $store_user->get_id() ); ?>
					
					<div class="spacer"></div>   
				</div>
				<?php if( !empty( $store_info['social'] ) && $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $store_user->get_id(), 'vendor_social' ) ) { ?>
					<div class="social_area rgt">
						<ul>
						  <?php do_action( 'wcfmmp_store_before_social', $store_user->get_id() ); ?>
						  
							<?php if( isset( $store_info['social']['fb'] ) && !empty( $store_info['social']['fb'] ) ) { ?>
								<li><a href="<?php echo $store_info['social']['fb']; ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if( isset( $store_info['social']['twitter'] ) && !empty( $store_info['social']['twitter'] ) ) { ?>
								<li><a href="<?php echo $store_info['social']['twitter']; ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true" target="_blank"></i></a></li>
							<?php } ?>
							<?php if( isset( $store_info['social']['linkedin'] ) && !empty( $store_info['social']['linkedin'] ) ) { ?>
								<li><a href="<?php echo $store_info['social']['linkedin']; ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true" target="_blank"></i></a></li>
							<?php } ?>
							<?php if( isset( $store_info['social']['youtube'] ) && !empty( $store_info['social']['youtube'] ) ) { ?>
								<li><a href="<?php echo $store_info['social']['youtube']; ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true" target="_blank"></i></a></li>
							<?php } ?>
							
							<?php do_action( 'wcfmmp_store_after_social', $store_user->get_id() ); ?>
						</ul>
					</div>
					 <div class="spacer"></div>
				<?php } ?>
			</div>
		  <div class="spacer"></div>    
		</div>
	</div>
</div>

<?php do_action( 'wcfmmp_store_after_header', $store_user->get_id() ); ?>