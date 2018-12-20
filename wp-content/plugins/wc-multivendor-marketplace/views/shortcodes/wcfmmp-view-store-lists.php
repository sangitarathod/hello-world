<?php
/**
 * The Template for displaying store list.
 *
 * @package WCfM Markeplace Views Store Lists
 *
 * For edit coping this to yourtheme/wcfm/store/shortcodes
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp, $post;

$pagination_base = str_replace( $post->ID, '%#%', esc_url( get_pagenum_link( $post->ID ) ) );

$search_query = isset( $_GET['wcfmmp_store_search'] ) ? sanitize_text_field( $_GET['wcfmmp_store_search'] ) : '';

$args = array(
		'stores'          => $stores,
		'limit'           => $limit,
		'offset'          => $offset,
		'paged'           => $paged,
		'search_query'    => $search_query,
		'pagination_base' => $pagination_base,
		'per_row'         => $per_row,
		'search_enabled'  => $search,
		'image_size'      => $image_size,
);

?>

<div class="wcfmmp-stores-listing">

	<?php if( $search ) { $WCFMmp->template->get_template( 'shortcodes/wcfmmp-view-store-lists-search-form.php', $args ); } ?>
	

	<?php $WCFMmp->template->get_template( 'shortcodes/wcfmmp-view-store-lists-loop.php', $args ); ?>
	
</div>