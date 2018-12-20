<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Admin
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
class WCFMmp_Admin {
	
	public function __construct() {
 		global $WCFM, $WCFMmp;
 		
 		// Browse WCFM Marketplace setup page
 		add_action( 'admin_init', array( &$this, 'wcfmmp_redirect_to_setup' ), 5 );
 		
 		/**
		 * Register our WCFM Marketplace to the admin_menu action hook
		 */
		add_action( 'admin_menu', array( &$this, 'wcfmmp_options_page' ) );
		
		// Vendor Column in Product List
		add_filter( 'manage_product_posts_columns', array( &$this, 'wcfmmp_store_product_columns' ) );
		add_action( 'manage_product_posts_custom_column' , array( &$this, 'wcfmmp_store_product_custom_column' ), 10, 2 );
		
		// Vendor data tab at Product Page
		add_action( 'admin_head', array( &$this, 'wcfmmp_store_tab_style' ) );
		add_filter( 'woocommerce_product_data_tabs', array( &$this, 'wcfmmp_store_product_data_tab' ), 500 );
		add_action( 'woocommerce_product_data_panels', array( &$this, 'wcfmmp_store_product_data_fields' ) );
		
		// Product Commission
		add_action( 'woocommerce_product_data_panels', array( &$this, 'wcfmmp_store_commission_product_data_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( &$this, 'wcfmmp_store_product_data_save' ), 500 );
		
		// Variation Commission
		add_action( 'woocommerce_product_after_variable_attributes', array( &$this, 'wcfmmp_store_variation_settings_fields' ), 500, 3 );
		add_action( 'woocommerce_save_product_variation', array( &$this, 'wcfmmp_store_save_variation_settings_fields' ), 10, 2 );

 	}
 	
 	/**
	 * WCFM Marketplace activation redirect transient
	 */
	function wcfmmp_redirect_to_setup(){
		if ( get_transient( '_wc_activation_redirect' ) ) {
			delete_transient( '_wc_activation_redirect' );
			return;
		}
		if ( get_transient( '_wcfmmp_activation_redirect' ) ) {
			delete_transient( '_wcfmmp_activation_redirect' );
			if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'wcfmmp-setup' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || apply_filters( 'wcfmmp_prevent_automatic_setup_redirect', false ) ) {
			  return;
			}
			wp_safe_redirect( admin_url( 'index.php?page=wcfmmp-setup' ) );
			exit;
		}
	}
	
	/**
	 * WCFM Marketplace Menu at WP Menu
	 */
	function wcfmmp_options_page() {
    global $menu, $WCFMmp;
    
    if( function_exists( 'get_wcfm_settings_url' ) ) {
    	add_menu_page( __( 'Marketplace', 'wc-multivendor-marketplace' ), __( 'Marketplace', 'wc-multivendor-marketplace' ), 'manage_options', 'wcfm_settings_form_marketplace_head', null, null, '55' );
    	$menu[55] = array( __( 'Marketplace', 'wc-multivendor-marketplace' ), 'manage_options', get_wcfm_settings_url() . '#wcfm_settings_form_marketplace_head', '', 'open-if-no-js menu-top', '', $WCFMmp->plugin_url . 'assets/images/wcfmmp_icon.svg' );
    }
  }  
  
  function wcfmmp_store_product_columns( $columns ) {
  	$columns['wcfm_vendor'] = __( 'Store', 'wc-multivendor-marketplace' );
    return $columns;
  }
  
  function wcfmmp_store_product_custom_column( $column, $product_id ) {
  	global $WCFM, $WCFMmp;
  	
  	switch ( $column ) {
			case 'wcfm_vendor' :
				$vendor_name = '&ndash;';
				$vednor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
				$store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $vednor_id );
				if( $store_name ) {
					$vendor_name = $store_name;
				}
				echo $vendor_name;
			break;
		}
  }
  
  function wcfmmp_store_product_data_tab( $product_data_tabs ) {
  	global $WCFM, $WCFMmp;
  	
  	$product_data_tabs['wcfmmp-store-tab'] = array(
        'label' => __( 'Store', 'wc-multivendor-marketplace' ),
        'target' => 'wcfmmp_store_product_data',
    );
    
    $product_data_tabs['wcfmmp-store-commission-tab'] = array(
        'label' => __( 'Commission', 'wc-multivendor-marketplace' ),
        'target' => 'wcfmmp_store_commission_product_data',
    );
    return $product_data_tabs;
  }
  
  function wcfmmp_store_tab_style() {?>
		<style>
		#woocommerce-product-data ul.wc-tabs li.wcfmmp-store-tab_options a:before { font-family: WooCommerce; content: '\e038'; }
		#woocommerce-product-data ul.wc-tabs li.wcfmmp-store-commission-tab_options a:before { font-family: WooCommerce; content: '\e604'; }
		</style>
		<script>
		jQuery(document).ready(function($) {
			$('#vendor_commission_mode').change(function() {
				$vendor_commission_mode = $(this).val();
				$('.commission_mode_field').hide();
				$('.commission_mode_'+$vendor_commission_mode).show();
			}).change();
			
			$(document).on('woocommerce_variations_loaded', function(event) {
				$('.var_commission_mode').each(function() {
					$(this).change(function() {
						$vendor_commission_mode = $(this).val();
						$(this).parent().parent().find('.var_commission_mode_field').hide();
						$(this).parent().parent().find('.var_commission_mode_'+$vendor_commission_mode).show();
					}).change();
				});
			});
			
			if( $("#wcfmmp_store").length > 0 ) {
				$("#wcfmmp_store").select2({
					placeholder: '<?php echo __( "Choose Vendor ...", "wc-frontend-manager" ); ?>'
				});
			}
		});
		</script>
		<?php
	}
  
  function wcfmmp_store_product_data_fields() {
  	global $WCFM, $WCFMmp, $post;
  	
  	echo '<div id ="wcfmmp_store_product_data" class="panel woocommerce_options_panel"><div class="options_group"><p class="form-field _wcfmmp_store_field">';
  	echo '<label for="wcfmmp_store">' . __( 'Store', 'wc-multivendor-marketplace' ) . '</label>';
  	$vendor_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list();
  	$vednor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );
  	$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																											"wcfmmp_store" => array( 'type' => 'select', 'class' => 'select short', 'options' => $vendor_arr, 'value' => $vednor_id, 'attributes' => array( 'style' => 'width:400px;' ) )
																											 ) );
		echo '</p></div></div>';
  }
  
  function wcfmmp_store_commission_product_data_fields() {
  	global $WCFM, $WCFMmp, $post;
  	
  	echo '<div id ="wcfmmp_store_commission_product_data" class="panel woocommerce_options_panel"><div class="options_group">';
  	
  	$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		$wcfm_commission_types = array_merge( array( 'global' => __( 'By Global Rule', 'wc-multivendor-marketplace' ) ), $wcfm_commission_types );
		if( isset( $wcfm_commission_types['by_sales'] ) ) unset( $wcfm_commission_types['by_sales'] );
		if( isset( $wcfm_commission_types['by_products'] ) ) unset( $wcfm_commission_types['by_products'] );
		
		$vendor_commission_mode = 'global';
		$vendor_commission_fixed = '';
		$vendor_commission_percent = '';
		if( $post  ) {
			$product_commission_data = get_post_meta( $post->ID, '_wcfmmp_commission', true );
			if( empty($product_commission_data) ) $product_commission_data = array();
			
			$vendor_commission_mode = isset( $product_commission_data['commission_mode'] ) ? $product_commission_data['commission_mode'] : 'global';
			$vendor_commission_fixed = isset( $product_commission_data['commission_fixed'] ) ? $product_commission_data['commission_fixed'] : '';
			$vendor_commission_percent = isset( $product_commission_data['commission_percent'] ) ? $product_commission_data['commission_percent'] : '90';
		}
		
		echo '<p class="form-field _wcfmmp_store_field"><label for="vendor_commission_mode">' . __( 'Commission Mode', 'wc-multivendor-marketplace' ) . '</label>';
		$WCFM->wcfm_fields->wcfm_generate_form_field( array(
					                                              "vendor_commission_mode" => array('name' => 'commission[commission_mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele select short variable external grouped booking', 'value' => $vendor_commission_mode )
					                                              ) );
		echo '<br/><br><span class="desciption">' . __( 'Keep this as Global to apply commission rule as per vendor or marketplace commission setup.', 'ec-multivendor-marketplace' ) . '</span>';
		echo '</p>';
		
		echo '<p class="form-field _wcfmmp_commission_percent_field commission_mode_field commission_mode_percent commission_mode_percent_fixed"><label for="vendor_commission_percent">' . __( 'Commission Percent(%)', 'wc-multivendor-marketplace' ) . '</label>';
		$WCFM->wcfm_fields->wcfm_generate_form_field( array(
					                                              "vendor_commission_percent" => array('name' => 'commission[commission_percent]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => $vendor_commission_percent, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
					                                              ) );
		echo '</p>';
		
		echo '<p class="form-field _wcfmmp_commission_fixed_field commission_mode_field commission_mode_fixed commission_mode_percent_fixed"><label for="vendor_commission_fixed">' . __( 'Commission Fixed', 'wc-multivendor-marketplace' ) . '(' . get_woocommerce_currency_symbol() . ')' . '</label>';
		$WCFM->wcfm_fields->wcfm_generate_form_field( array(
					                                                                        "vendor_commission_fixed" => array('name' => 'commission[commission_fixed]', 'type' => 'number', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => $vendor_commission_fixed, 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																									) );
		echo '</p>';
  	
  	echo '</p></div></div>';
  }
  
  function wcfmmp_store_product_data_save( $product_id ) {
  	global $WCFM, $WCFMmp;
  	
  	$wcfmmp_store = isset( $_POST['wcfmmp_store'] ) ? absint( $_POST['wcfmmp_store'] ) : '';
  	if( $wcfmmp_store ) {
  		$arg = array(
							'ID' => $product_id,
							'post_author' => $wcfmmp_store,
						);
			wp_update_post( $arg );
			
			// Update vendor category list
			$pcategories = get_the_terms( $product_id, 'product_cat' );
			if( !empty($pcategories) ) {
				foreach($pcategories as $pkey => $pcategory) {
					$categories[] = $pcategory->term_id;
				}
			}
			$vendor_categories = get_user_meta( $wcfmmp_store, '_wcfm_store_product_cats', true );
			if( !$vendor_categories ) $vendor_categories = array();
			$vendor_categories = array_merge( $vendor_categories, $categories );
			update_user_meta( $wcfmmp_store, '_wcfm_store_product_cats', array_unique($vendor_categories) );
  	} else {
			$arg = array(
				'ID' => $product_id,
				'post_author' => get_current_user_id(),
			);
			wp_update_post( $arg );
		}
		
		// Update Product Commission
		if( isset( $_POST['commission'] ) && !empty( $_POST['commission'] ) ) {
			update_post_meta( $product_id, '_wcfmmp_commission', $_POST['commission'] );
		}
  }
  
  function wcfmmp_store_variation_settings_fields( $loop, $variation_data, $variation ) {
  	global $WCFM, $WCFMmp;
  	
  	$wcfm_commission_types = get_wcfm_marketplace_commission_types();
		$wcfm_commission_types = array_merge( array( 'global' => __( 'By Global Rule', 'wc-multivendor-marketplace' ) ), $wcfm_commission_types );
		if( isset( $wcfm_commission_types['by_sales'] ) ) unset( $wcfm_commission_types['by_sales'] );
		if( isset( $wcfm_commission_types['by_products'] ) ) unset( $wcfm_commission_types['by_products'] );
  	
  	$variation_commission_data = get_post_meta( $variation->ID, '_wcfmmp_commission', true );
		if( empty($variation_commission_data) ) $variation_commission_data = array();
		
		$vendor_commission_mode = isset( $variation_commission_data['commission_mode'] ) ? $variation_commission_data['commission_mode'] : 'global';
		$vendor_commission_fixed = isset( $variation_commission_data['commission_fixed'] ) ? $variation_commission_data['commission_fixed'] : '';
		$vendor_commission_percent = isset( $variation_commission_data['commission_percent'] ) ? $variation_commission_data['commission_percent'] : '90';
		
  	// Commission Mode
		woocommerce_wp_select( 
		array( 
			'id'            => 'vendor_commission_mode[' . $variation->ID . ']', 
			'label'         => __( 'Commission Mode', 'wc-multivendor-marketplace' ), 
			'desc_tip'      => 'true',
			'wrapper_class' => 'form-row form-row-full',
			'class'         => 'var_commission_mode',
			'description'   => __( 'Keep this as Global to apply commission rule as per vendor or marketplace commission setup.', 'wc-multivendor-marketplace' ),
			'value'         => $vendor_commission_mode,
			'options'       => $wcfm_commission_types
			)
		);
  	
		// Commission Percent
		woocommerce_wp_text_input( 
			array( 
				'id'                => 'vendor_commission_percent[' . $variation->ID . ']', 
				'label'             => __( 'Commission Percent(%)', 'wc-multivendor-marketplace' ), 
				'value'             => $vendor_commission_percent,
				'wrapper_class'     => 'var_commission_mode_field var_commission_mode_percent var_commission_mode_percent_fixed',
				'custom_attributes' => array(
								'step' 	=> '0.1',
								'min'	=> '0'
							) 
			)
		);
		
		// Commission Fixed
		woocommerce_wp_text_input( 
			array( 
				'id'                => 'vendor_commission_fixed[' . $variation->ID . ']', 
				'label'             => __( 'Commission Fixed', 'wc-multivendor-marketplace' ) . '(' . get_woocommerce_currency_symbol() . ')', 
				'value'             => $vendor_commission_fixed,
				'wrapper_class'     => 'var_commission_mode_field var_commission_mode_fixed var_commission_mode_percent_fixed',
				'custom_attributes' => array(
								'step' 	=> '0.1',
								'min'	=> '0'
							) 
			)
		);
		
	}
	
	function wcfmmp_store_save_variation_settings_fields( $variation_id ) {
		global $WCFM, $WCFMmp;
		
		$variation_commission_data = get_post_meta( $variation_id, '_wcfmmp_commission', true );
		if( empty($variation_commission_data) ) $variation_commission_data = array();
			
		if( isset( $_POST['vendor_commission_mode'][$variation_id] ) ) {
			$variation_commission_data['commission_mode'] = $_POST['vendor_commission_mode'][$variation_id];
		}
		if( isset( $_POST['vendor_commission_percent'][$variation_id] ) ) {
			$variation_commission_data['commission_percent'] = $_POST['vendor_commission_percent'][$variation_id];
		}
		if( isset( $_POST['vendor_commission_fixed'][$variation_id] ) ) {
			$variation_commission_data['commission_fixed'] = $_POST['vendor_commission_fixed'][$variation_id];
		}
		
		update_post_meta( $variation_id, '_wcfmmp_commission', $variation_commission_data );
	}
 	
}