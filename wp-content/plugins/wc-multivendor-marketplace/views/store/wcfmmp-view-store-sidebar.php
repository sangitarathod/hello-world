<?php
/**
 * The Template for displaying store sidebar.
 *
 * @package WCfM Markeplace Views Store Sidebar
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$widget_args = array(
							'before_widget' => '<aside class="widget">',
							'after_widget'  => '</aside>',
							'before_title'  => '<div class="sidebar_heading"><h4>',
							'after_title'   => '</h4></div>',
					);

?>

<div class="lft left_sidebar widget-area">

  <?php do_action( 'wcfmmp_store_before_sidabar', $store_user->get_id() ); ?>
  
  <?php if( !dynamic_sidebar( 'sidebar-wcfmmp-store' ) ) { ?>
  	
  	<?php the_widget( 'WCFMmp_Store_Category', array( 'title' => __( 'Store Categories', 'wc-multivendor-marketplace' ) ), $widget_args ); ?>
		
		<?php the_widget( 'WCFMmp_Store_Location', array( 'title' => __( 'Store Location', 'wc-multivendor-marketplace' ) ), $widget_args ); ?>
		
	<?php } else { ?>
		<?php //get_sidebar( 'store' ); ?>
	<?php } ?>
	
	<?php do_action( 'wcfmmp_store_after_sidebar', $store_user->get_id() ); ?>
	
</div><!-- .left_sidebar -->
<div class="spacer"></div>