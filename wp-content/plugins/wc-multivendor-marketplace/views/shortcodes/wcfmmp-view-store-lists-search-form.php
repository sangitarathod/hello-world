<?php
/**
 * The Template for displaying store list search form.
 *
 * @package WCfM Markeplace Views Store List Search Form
 *
 * For edit coping this to yourtheme/wcfm/store/shortcodes
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp, $post;

$pagination_base = str_replace( $post->ID, '%#%', esc_url( get_pagenum_link( $post->ID ) ) );

$search_query = isset( $_GET['wcfmmp_store_search'] ) ? sanitize_text_field( $_GET['wcfmmp_store_search'] ) : '';

if ( ! empty( $search_query ) ) {
		printf( '<h2>' . __( 'Search Results for: %s', 'wc-multivendor-marketplace' ) . '</h2>', $search_query );
}
?>

<form role="search" method="get" class="wcfmmp-store-search-form" action="">
	<input type="search" id="search" class="search-field wcfmmp-store-search" placeholder="<?php esc_attr_e( 'Search Store &hellip;', 'wc-multivendor-marketplace' ); ?>" value="<?php echo esc_attr( $search_query ); ?>" name="wcfmmp_store_search" title="<?php esc_attr_e( 'Search store &hellip;', 'wc-multivendor-marketplace' ); ?>" />
	<input type="hidden" id="pagination_base" name="pagination_base" value="<?php echo $pagination_base ?>" />
	<input type="hidden" id="nonce" name="nonce" value="<?php echo wp_create_nonce( 'wcfmmp-stores-list-search' ); ?>" />
	<div class="wcfmmp-overlay" style="display: none;"><span class="wcfmmp-ajax-loader"></span></div>
</form>

<?php do_action( 'wcfmmp_after_store_list_serach_form', $stores ); ?>

<script>
	$per_row = '<?php echo $per_row; ?>';
</script>