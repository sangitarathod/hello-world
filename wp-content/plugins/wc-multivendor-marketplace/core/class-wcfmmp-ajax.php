<?php
/**
 * WCFM Markeplace plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMa;
		
		// Vendor Order Status Update
		add_action( 'wcfmmp_vendor_order_status_update', array( &$this, 'wcfmmp_vendor_order_status_update' ), 10, 2 );
		
		// Store List Serch
		add_action( 'wp_ajax_wcfmmp_stores_list_search', array($this, 'wcfmmp_stores_list_search') );
    add_action( 'wp_ajax_nopriv_wcfmmp_stores_list_search', array($this, 'wcfmmp_stores_list_search') );
    
    // Zone Shipping Ajax
    add_action( 'wp_ajax_wcfmmp-get-shipping-zone', array( $this, 'wcfmmp_get_shipping_zone' ) );
    add_action( 'wp_ajax_wcfmmp-add-shipping-method', array( $this, 'wcfmmp_add_shipping_method' ) );
    add_action( 'wp_ajax_wcfmmp-toggle-shipping-method', array( $this, 'wcfmmp_toggle_shipping_method' ) );
    add_action( 'wp_ajax_wcfmmp-delete-shipping-method', array( $this, 'wcfmmp_delete_shipping_method' ) );
    add_action( 'wp_ajax_wcfmmp-update-shipping-method', array( $this, 'wcfmmp_update_shipping_method' ) );
		
	}
	
	/**
	 * Vendor Order - Commission Status Update
	 */
	function wcfmmp_vendor_order_status_update( $order_id, $order_status ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if( !$order_id ) {
			echo '{"status": false, "message": "' . __( 'No Order ID found.', 'wc-frontend-manager' ) . '"}';
			return;
		}
		
		if( $order_status == 'wc-refunded' ) {
			echo '{"status": false, "message": "' . __( 'This status not allowed, please go through Refund Request.', 'wc-frontend-manager' ) . '"}';
			return;
		}
		
		$vendor_id = $WCFMmp->vendor_id;
		
		if( $vendor_id ) {
			$order = wc_get_order( $order_id );
			$status = str_replace('wc-', '', $order_status);
			$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array('commission_status' => $status), array('order_id' => $order_id, 'vendor_id' => $vendor_id), array('%s'), array('%d', '%d'));
			
			// Fetch Product ID
			$sql = 'SELECT product_id  FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
			$sql .= ' WHERE 1=1';
			$sql .= " AND `order_id` = " . $order_id;
			$sql .= " AND `vendor_id` = " . $vendor_id;
			$commissions = $wpdb->get_results( $sql );
			$product_id = 0;
			if( !empty( $commissions ) ) {
				foreach( $commissions as $commission ) {
					$product_id = $commission->product_id;
				}
			}
			
			// Add Order Note for Log
			$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) );
			$wcfm_messages = sprintf( __( '<b>%s</b> order item <b>%s</b> status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager' ), '#<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_order_url($order_id) . '">' . $order_id . '</a>', '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_permalink($product_id) . '">' . get_the_title( $product_id ) . '</a>', $WCFMmp->wcfmmp_vendor->wcfmmp_vendor_order_status_name( $order_status ), $shop_name );
			$comment_id = $order->add_order_note( $wcfm_messages, '1');
			add_comment_meta( $comment_id, '_vendor_id', $vendor_id );
			
			$WCFM->wcfm_notification->wcfm_send_direct_message( $vendor_id, 0, 0, 1, $wcfm_messages, 'status-update' );
			
			do_action( 'wcfmmp_vendor_order_status_updated', $order_id, $vendor_id, $order_status );
			
			echo '{"status": true, "message": "' . __( 'Order status updated.', 'wc-frontend-manager' ) . '"}';
		}
	}
	
	function wcfmmp_stores_list_search() {
		global $WCFM, $WCFMmp, $wpdb;
		
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wcfmmp-stores-list-search' ) ) {
				wp_send_json_error( __( 'Error: Nonce verification failed', 'wc-multivendor-marketplace' ) );
		}

		$paged   = 1;
		$length  = 10;
		$offset  = ( $paged - 1 ) * $limit;

		$search_term     = isset( $_REQUEST['search_term'] ) ? sanitize_text_field( $_REQUEST['search_term'] ) : '';
		$pagination_base = isset( $_REQUEST['pagination_base'] ) ? sanitize_text_field( $_REQUEST['pagination_base'] ) : '';
		$per_row         = isset( $_REQUEST['per_row'] ) ? sanitize_text_field( $_REQUEST['per_row'] ) : '3';

		$stores = $WCFMmp->wcfmmp_vendor->wcfmmp_get_vendor_list( true, $offset, $length, $search_term );

		$template_args = apply_filters( 'wcfmmp_stores_args', array(
				'stores'       => $stores,
				'limit'        => $length,
				'offset'       => $offset,
				'paged'        => $paged,
				'image_size'   => 'full',
				'search_query' => $search_term,
				'search'       => $search_term,
				'per_row'      => $per_row
		) );
		
		ob_start();
		$WCFMmp->template->get_template( 'shortcodes/wcfmmp-view-store-lists-loop.php', $template_args );
		$content = ob_get_clean();

		wp_send_json_success( $content );
	}
	
  /**
   * Get shipping zone
   *
   * @since 1.0.0
   *
   * @return void
   */
  public function wcfmmp_get_shipping_zone() {

    global $WCFM, $WCFMmp;
    if ( isset( $_POST['zoneID'] ) ) {
			$zones = WCFMmp_Shipping_Zone::get_zone( $_POST['zoneID'] );
    } 
    $show_post_code_list = $show_state_list = $show_post_code_list = false; 
    //print_r($zones);die;
    $zone_id = $zones['data']['id']; 
    $zone_locations = $zones['data']['zone_locations'];
    //print_r($zone_locations);
    $zone_location_types = array_column(array_map('wcfmmp_convert_to_array', $zone_locations) , 'type' , 'code');
    //print_r($zone_location_types);
    $selected_continent_codes = array_keys($zone_location_types, 'continent');
    $all_continents = WC()->countries->get_continents();
    $all_allowed_countries = WC()->countries->get_allowed_countries();

    $countries_key_by_continent = array_intersect_key($all_continents, array_flip($selected_continent_codes));
    $countries_key_by_continent = call_user_func_array('array_merge',array_column( $countries_key_by_continent, 'countries' ));
    $countries_by_continent = array_intersect_key($all_allowed_countries, array_flip($countries_key_by_continent));
    //print_r($all_allowed_countries);
    $selected_country_codes = array_keys($zone_location_types, 'country');
    $all_states = WC()->countries->get_states();

    $state_key_by_country = array();
    $state_key_by_country = array_intersect_key($all_states, array_flip($selected_country_codes));

    array_walk($state_key_by_country, 'wcfmmp_state_key_alter');
    
    $state_key_by_country = call_user_func_array('array_merge', $state_key_by_country);
    

    $show_limit_location_link = apply_filters( 'show_limit_location_link', (!in_array('postcode', $zone_location_types)) );
    $vendor_shipping_methods = $zones['shipping_methods'];

    if($show_limit_location_link) {
      if ( in_array('state', $zone_location_types) ) {
          $show_post_code_list = true;
      } elseif ( in_array('country', $zone_location_types) ) {
          $show_state_list = true;
          $show_post_code_list = true;
          
      } elseif (in_array('continent', $zone_location_types)) {
          $show_country_list = true;
          $show_state_list = true;
          $show_post_code_list = true;
          
      }
    }

    $want_to_limit_location = !empty($zones['locations']);

    if($want_to_limit_location) {
      $countries = $states = $postcodes = array();
       
      foreach($zones['locations'] as $each_location ) {
        switch ($each_location['type']) {
          case 'country':
            $countries[] = $each_location['code'];  
          break;
          case 'state':
            $states[] = $each_location['code'];
          break;
          case 'postcode':
            $postcodes[] = $each_location['code'];
          break;
          default:
            break;
        }
      }
      $postcodes = implode(',', $postcodes);
    }    
    //print_r($states);

      ob_start();
    ?>

    <div class="zone-component">
      <div class="return-to-zone-list">
        <p>
          <a href="#" >&larr; <?php  _e('Back to Zone List', 'wc-multivendor-marketplace'); ?></a>
        </p>
      </div>
      <form action="" method="post">
        <div class="wcfmmp-form-group wcfmmp-clearfix">
          <p class="wcfm_title">
            <strong><?php  _e('Zone Name', 'wc-multivendor-marketplace'); ?></strong>
          </p>
          <label for="" class="screen-reader-text">
            <?php  _e('Zone Name', 'wc-multivendor-marketplace'); ?>
          </label>
          <p class="wcfm_title"> 
            <?php  _e($zones['data']['zone_name'], 'wc-multivendor-marketplace'); ?>
          </p>
        </div>
        <div class="wcfmmp-form-group wcfmmp-clearfix">
          <p class="wcfm_title">
          <strong>
            <?php  _e('Zone Location', 'wc-multivendor-marketplace'); ?>
          </strong>
          </p>
          <label for="" class="screen-reader-text">
            <?php  _e('Zone Location', 'wc-multivendor-marketplace'); ?>
          </label>
          <p class="wcfm_title">
            <?php  _e($zones['formatted_zone_location'], 'wc-multivendor-marketplace'); ?>
          </p>
        </div>
        
        <?php 
          $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array (
                "zone_id" => array(
                  'name' => 'wcfmmp_shipping_zone['. $zone_id .'][_zone_id]',
                  'type' => 'hidden', 
                  'class' => 'wcfm-hidden input-hidden wcfm_ele', 
                  'value' => $zone_id                    
                )
              )
            );
          if( $show_limit_location_link && $zone_id !== 0 ) {
            
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array (
                "limit_zone_location" => array(
                    'label' => __('Limit Zone Location', 'wc-multivendor-marketplace') ,
                    'name' => 'wcfmmp_shipping_zone['. $zone_id .'][_limit_zone_location]',
                    'type' => 'checkbox', 
                    'class' => 'wcfm-checkbox input-checkbox wcfm_ele', 
                    'value' => 1, 
                    'label_class' => 'wcfm_title checkbox_title', 
                    'dfvalue' => $want_to_limit_location
                    )
              )
            );
          }
          if( $show_country_list ) {
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "select_zone_country" => array(
                  'label' => __('Select Specific Countries', 'wc-multivendor-marketplace') , 
                  'name' => 'wcfmmp_shipping_zone['. $zone_id .'][_select_zone_country]',
                  'type' => 'select', 
                  'class' => 'wcfm-select wcfm-select2 wcfm_ele select_zone_country_select hide_if_zone_not_limited', 
                  'label_class' => 'wcfm_title select_title hide_if_zone_not_limited', 
                  'attributes' => array( 'multiple' => 'multiple' ),
                  'options' => $countries_by_continent,
                  'value' => $countries
                )
              )
            );
          }
          if(  $show_state_list ) {
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "select_zone_states" => array(
                  'label' => __('Select Specific States', 'wc-multivendor-marketplace') , 
                  'name' => 'wcfmmp_shipping_zone['. $zone_id .'][_select_zone_states]',
                  'type' => 'select', 
                  'class' => 'wcfm-select wcfm-select2 wcfm_ele select_zone_states_select hide_if_zone_not_limited', 
                  'label_class' => 'wcfm_title select_title hide_if_zone_not_limited', 
                  'attributes' => array( 'multiple' => 'multiple' ),
                  'options' => $state_key_by_country,
                  'value' => $states
                )
              )
            );
          }

          if( $show_post_code_list ) {
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "select_zone_postcodes" => array(
                  'label' => __('Set your postcode', 'wc-multivendor-marketplace'), 
                  'name' => 'wcfmmp_shipping_zone['. $zone_id .'][_select_zone_postcodes]',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele hide_if_zone_not_limited', 
                  'label_class' => 'wcfm_title wcfm_ele hide_if_zone_not_limited', 
                  'placeholder' => __('Postcodes need to be comma separated', 'wc-multivendor-marketplace'),
                  'value' => $postcodes  
                )
              )
            );
          }
        ?>
      </form>
      <div class="wcfmmp-zone-method-wrapper">
        <div class="wcfmmp-zone-method-heading">
          <h2>
            <i aria-hidden="true" class="fa fa-truck"></i>
            <?php  _e('Shipping Method', 'wc-multivendor-marketplace'); ?>
          </h2> 
          <span>
            <?php _e('Add your shipping method for appropiate zone', 'wc-multivendor-marketplace'); ?>
          </span> 
          <div class="clearfix"></div>
        </div>
        <div class="wcfmmp-zone-method-content">
          <table class="wcfmmp-table zone-method-table">
            <thead>
              <tr>
                <th class="title"><?php  _e('Method Title', 'wc-multivendor-marketplace'); ?></th>
                <th class="enabled"><?php  _e('Status', 'wc-multivendor-marketplace'); ?></th> 
                <th class="description"><?php  _e('Description', 'wc-multivendor-marketplace'); ?></th>
              </tr>
            </thead> 
            <tbody>
              <?php 
                if(empty($vendor_shipping_methods)) { ?> 
                  <tr>
                    <td colspan="3">
                      <?php _e('No shipping method found', 'wc-multivendor-marketplace'); ?>
                    </td>
                  </tr>
                <?php 
                } else { 
                  //print_r($vendor_shipping_methods);
                  foreach ( $vendor_shipping_methods as $vendor_shipping_method ) {
                  ?>
                  <tr>
                    <td>
                      <?php _e($vendor_shipping_method['title'], 'wc-multivendor-marketplace' ); ?>
                      <div 
                        data-instance_id="<?php echo $vendor_shipping_method['instance_id']; ?>" 
                        data-method_id="<?php echo $vendor_shipping_method['id']; ?>" 
                        data-method-settings='<?php echo json_encode($vendor_shipping_method); ?>'
                        class="row-actions edit_del_actions"
                        
                      >
                        <span class="edit">
                          <a href="#" class="edit_shipping_method">
                            <?php _e('Edit', 'wc-multivendor-marketplace' ); ?>
                          </a> |
                        </span> 
                        <span class="delete">
                          <a class="delete_shipping_method" href="#">
                            <?php _e('Delete', 'wc-multivendor-marketplace' ); ?>
                          </a>
                        </span>
                      </div>
                    </td>
                    <td>
                      <?php 
                        $WCFM->wcfm_fields->wcfm_generate_form_field ( 
                          array (
                            "method_status" => array(
                                'label' => false ,
                                'name' => 'method_status',
                                'type' => 'checkbox', 
                                'class' => 'wcfm-checkbox method_status input-checkbox wcfm_ele', 
                                'value' => $vendor_shipping_method['instance_id'],
                                'dfvalue' => ( $vendor_shipping_method['enabled'] == "yes" ) ? $vendor_shipping_method['instance_id'] : 0
                                )
                          )
                        );
                      ?>
                    </td>
                    <td>
                      <?php _e($vendor_shipping_method['settings']['description'], 'wc-multivendor-marketplace' ); ?>
                    </td>
                  </tr>
                <?php 
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
        <div class="wcfmmp-zone-method-footer">
          <a href="#" class="wcfmmp-btn wcfmmp-btn-theme wcfmmp-zone-method-add-btn">
            <i class="fa fa-plus"></i> 
            <?php _e('Add Shipping Method', 'wc-multivendor-marketplace') ?>
          </a>
        </div>
        <?php 
          $WCFMmp->template->get_template( 'shipping/wcfmmp-view-edit-method-popup.php' );
          $WCFMmp->template->get_template( 'shipping/wcfmmp-view-add-method-popup.php' );
        ?>
      </div>
    </div>


    <?php    
    $zone_html['html'] = ob_get_clean();
    $zone_html['states'] = json_encode($states);
    wp_send_json_success( $zone_html );
  }
  
  /**
    * Add shipping Method
    *
    * @since 1.0.0
    *
    * @return void
    */
  public function wcfmmp_add_shipping_method() {
    $data = array(
                'zone_id'   => $_POST['zoneID'],
                'method_id' => $_POST['method']
            );

    $result = WCFMmp_Shipping_Zone::add_shipping_methods( $data );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() , 'wc-multivendor-marketplace' );
		}

		wp_send_json_success( __( 'Shipping method added successfully', 'wc-multivendor-marketplace' ) );
    
  }
  
  /**
    * Toggle shipping Method
    *
    * @since 1.0.0
    *
    * @return void
    */
  public function wcfmmp_toggle_shipping_method() {
    //print_r($_POST);
    $data = array(
       'instance_id' => $_POST['instance_id'],
       'zone_id'     => $_POST['zoneID'],
       'checked'     => ( $_POST['checked'] == 'true' ) ? 1 : 0
    );
    $result = WCFMmp_Shipping_Zone::toggle_shipping_method( $data );
    if ( is_wp_error( $result ) ) {
      wp_send_json_error( $result->get_error_message() );
    }
    $message = $data['checked'] ? __( 'Shipping method enabled successfully',  'wc-multivendor-marketplace' ) : __( 'Shipping method disabled successfully',  'wc-multivendor-marketplace' );
    wp_send_json_success( $message );
  }
  
  /**
    * Delete shipping Method
    *
    * @since 1.0.0
    *
    * @return void
    */
  public function wcfmmp_delete_shipping_method() {
    $data = array(
      'zone_id'     => $_POST['zoneID'],
      'instance_id' => $_POST['instance_id']
    );

    $result = WCFMmp_Shipping_Zone::delete_shipping_methods( $data );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() , 'wc-multivendor-marketplace' );
    }

    wp_send_json_success( __( 'Shipping method deleted', 'wc-multivendor-marketplace' ) );
  }
  
  /**
    * Update shipping Method
    *
    * @since 1.0.0
    *
    * @return void
    */
  public function wcfmmp_update_shipping_method() {
    //print_r($_POST); die;
    $args =  $_POST['args'];
    if ( empty( $args['settings']['title'] ) ) {
      wp_send_json_error( __( 'Shipping title must be required', 'wc-multivendor-marketplace' ) );
    }

    $result = WCFMmp_Shipping_Zone::update_shipping_method( $args );
    if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() , 'wc-multivendor-marketplace' );
    }
    wp_send_json_success( __( 'Shipping method updated', 'wc-multivendor-marketplace' ) );
  }
}