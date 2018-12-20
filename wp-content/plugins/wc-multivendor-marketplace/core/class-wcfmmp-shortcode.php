<?php
/**
 * WCFM Marketplace plugin core
 *
 * Plugin shortcode
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Shortcode {

	public $list_product;

	public function __construct() {
		
		// WC Markeptlace Store List
		add_shortcode('wcfm_stores', array(&$this, 'wcfmmp_stores_shortcode'));
		
		// WC FMarkeplace Store Info
		add_shortcode('wcfm_store_info', array(&$this, 'wcfmmp_store_info_shortcode'));
		
	}

	/**
	 * WCFM Stores Short Code
	 */
	public function wcfmmp_stores_shortcode( $atts ) {
		global $WCFM, $WCFMmp, $wp, $WCFM_Query;
		$WCFM->nocache();
		
		$defaults = array(
				'per_page' => 10,
				'search'   => 'yes',
				'per_row'  => 2,
		);

		$attr   = shortcode_atts( apply_filters( 'wcfmmp_stores_per_page', $defaults ), $atts );
		$paged  = max( 1, get_query_var( 'paged' ) );
		$length = $attr['per_page'];
		$offset = ( $paged - 1 ) * $length;

		$search_term = isset( $_GET['wcfmmp_store_search'] ) ? sanitize_text_field( $_GET['wcfmmp_store_search'] ) : '';
			

		$stores = $WCFMmp->wcfmmp_vendor->wcfmmp_get_vendor_list( true, $offset, $length, $search_term );

		$template_args = apply_filters( 'wcfmmp_stores_args', array(
				'stores'     => $stores,
				'limit'      => $length,
				'offset'     => $offset,
				'paged'      => $paged,
				'image_size' => 'full',
				'search'     => $attr['search'],
				'per_row'    => $attr['per_row']
		) );
		
		$WCFMmp->template->get_template( 'shortcodes/wcfmmp-view-store-lists.php', $template_args );
	}
	
	/**
	 * WCFM Marketplace Store Info Shortcode
	 */
	public function wcfmmp_store_info_shortcode( $attr ) {
		global $WCFM, $WCFMmp, $wp, $WCFM_Query, $post;
		
		$store_id = '';
		if ( isset( $attr['id'] ) && !empty( $attr['id'] ) ) { $store_id = absint($attr['id']); }
		
		if (  wcfm_is_store_page() ) {
			$wcfm_store_url = get_option( 'wcfm_store_url', 'store' );
			$store_name = get_query_var( $wcfm_store_url );
			$store_id  = 0;
			if ( !empty( $store_name ) ) {
				$store_user = get_user_by( 'slug', $store_name );
			}
			$store_id   		= $store_user->ID;
		}
		
		if( is_product() ) {
			$store_id = $post->post_author;
		}
		
		$data_info = '';
		if ( isset( $attr['data'] ) && !empty( $attr['data'] ) ) { $data_info = $attr['data']; }
		
		if( !$store_id ) return;
		if( !$data_info ) return;
		
		$store_user  = wcfmmp_get_store( $store_id );
		$store_info  = $store_user->get_shop_info();
		
		$content = '<div class="wcfmmp_store_info wcfmmp_store_info_' . $data_info . '">';
		
		switch( $data_info ) {
			case 'store_name':
				$content .= $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_name_by_vendor( $store_id );
				break;
				
			case 'store_url':
				$content .=  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $store_id );
				break;
				
			case 'store_address':
				$content .= $store_user->get_address_string();
				break;
				
		  case 'store_email':
				$content .= $store_user->get_email();
				break;
			
			case 'store_phone':
				$content .=  $store_user->get_phone();
				break;
				
			case 'store_gravatar':
				$content .=  '<img src="' . $store_user->get_avatar() . '" />';
				break;
				
			case 'store_banner':
				$content .=  '<img src="' . $store_user->get_banner() . '" />';
				break;
				
			case 'store_support':
				$content .=  $store_user->get_customer_support_details();
				break;
				
			case 'store_rating':
				ob_start();
				$store_user->show_star_rating();
				$content .=  ob_get_clean();
				break;
		}
		
		$content .= '</div>';
		
		return $content;
		
	}
	
	/**
	 * Helper Functions
	 */

	/**
	 * Shortcode Wrapper
	 *
	 * @access public
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper($function, $atts = array()) {
		ob_start();
		call_user_func($function, $atts);
		return ob_get_clean();
	}

	/**
	 * Shortcode CLass Loader
	 *
	 * @access public
	 * @param mixed $class_name
	 * @return void
	 */
	public function load_class($class_name = '') {
		global $WCFM;
		if ('' != $class_name && '' != $WCFM->token) {
			require_once ( $WCFM->plugin_path . 'includes/shortcodes/class-' . esc_attr($WCFM->token) . '-shortcode-' . esc_attr($class_name) . '.php' );
		}
	}

}
?>