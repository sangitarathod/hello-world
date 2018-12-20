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

<div class="categories_list">
	<ul>
		<?php foreach( $vendor_categories as $vendor_category ) { $vendor_term = get_term( absint( $vendor_category ), 'product_cat' ); ?>
			<li><a class="<?php if( $vendor_term->term_id == $selected_term ) echo 'active'; ?>" href="<?php echo $store_user->get_shop_url() . 'category/' . $vendor_term->term_id ?>"><?php echo $vendor_term->name; ?></a></li>
		<?php } ?>
	</ul>
</div>