<?php
class Gzmgl_Retail_Order {

	static function init() {
		add_action( 'wp', __CLASS__ . '::mark_complete' );
		add_shortcode( 'order-list', __CLASS__ . '::order_list' );
		add_shortcode( 'order-overview', __CLASS__ . '::overview' );
	}
	
	static function mark_complete() {
		if ( ! empty( $_GET['order_complete'] ) || ! empty( $_GET['order_complete_all'] ) ) {
			global $wpdb;
			
			$form_id = self::get_form_id( 'Checkout' );
			$entry_id = empty( $_GET['order_complete'] ) ? 0 : intval( $_GET['order_complete'] );

			$where = array(
				'form_id' => $form_id,
				'created_by' => get_current_user_id()
			); 
			if ( ! empty( $entry_id ) ) { $where['id'] = $entry_id; }

			if ( ! empty( $entry_id ) || ! empty( $_GET['order_complete_all'] ) ) {
				$wpdb->update( $wpdb->prefix . 'rg_lead', array( 'status' => 'trash' ), $where );
			}			
			
			wp_safe_redirect( get_permalink() );
			exit;
		}
	}

	static function order_list() {
		ob_start();

		$form_id = self::get_form_id( 'Checkout' );
		$form = GFFormsModel::get_form_meta( $form_id );
		if ( ! empty( $form['fields'] ) ) {
			$field_id = 0;
			foreach( $form['fields'] as $field ) {
				if ( $field->label == 'Retailer ID' ) {
					$field_id = $field->id;
					break;
				}
			}
			
			$entries = self::get_leads_by_field( $field_id, get_current_user_id() );

			if ( empty( $entries ) ) {
				echo '<p>' . __( 'No orders found.', 'gzmgl_retail' ) . '</p>';
			} else { ?>

			<table class="order-list">
				<thead>
					<tr>
						<th><?php _e( 'Date', 'gzmgl_retail' ); ?></th>
						<th><?php _e( 'Device', 'gzmgl_retail' ); ?></th>
						<th class="hide_from_print"><?php _e( 'Actions', 'gzmgl_retail' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $entries as $entry_id ) :
						$entry = RGFormsModel::get_lead( $entry_id ); 
						$values = self::entry_values( $form, $entry );
					?>
						<tr>
							<td><?php echo date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $entry['date_created'] ) ); ?></td>
							<td><?php echo $values['device']['value']; ?></td>
							<td class="hide_from_print">
								<a href="<?php echo add_query_arg( 'c', $entry_id, site_url( '/order') ); ?>" class="button" ><?php _e( 'Overview', 'gzmgl_retail' ); ?></a>
								<a href="<?php echo add_query_arg( 'order_complete', $entry_id, get_permalink() ); ?>" class="button" ><?php _e( 'Complete', 'gzmgl_retail' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>			

			<div class="button_row">
				<button id="print_packing_slip" class="hide_from_print"><?php _e( 'Print Packing Slip', 'gzmgl_retail' ); ?></button>
				<a class="button hide_from_print" href="http://label.gizmogul.com/print_retail.php?id=<?php echo get_current_user_id(); ?>" target="_blank"><?php _e( 'Download Shipping Label', 'gzmgl_retail' ); ?></a>
				<button onclick="location.href='<?php echo add_query_arg( 'order_complete_all', 1, get_permalink() ); ?>';" class="hide_from_print"><?php _e( 'Complete All Orders', 'gzmgl_retail' ); ?></button>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					
					$( '#print_packing_slip' ).click( function() {
						window.print();
					} );

				} );
			</script>

		<?php
			}				
		}
		
		return ob_get_clean();
	}
	
	static function get_form_id( $name ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT id from ' . $wpdb->prefix . 'rg_form WHERE title = %s', $name ) );
	}

	static function get_leads_by_field( $field_id, $field_value ) {
		global $wpdb;

		if ( ! empty( $field_id ) ) {
			$sql = $wpdb->prepare( 'SELECT l.id FROM ' . $wpdb->prefix .'rg_lead l
				INNER JOIN ' . $wpdb->prefix . 'rg_lead_detail d ON l.id = d.lead_id
				WHERE l.status = "active" AND d.field_number = %d AND d.value = %s
				ORDER BY l.id DESC',
				$field_id, $field_value
			);
			$leads = $wpdb->get_col( $sql );
			if ( ! empty( $leads ) && ! is_wp_error( $leads ) ) {
				return $leads;
			}
		}
		
		return array();
	}
	
	static function entry_values( $form, $entry ) {
		
		if ( ! is_array( $entry ) ) {
			$entry = RGFormsModel::get_lead( $entry ); 
		}

		$values = array();
		foreach( $form['fields'] as $field ) {
			if ( 'html' == $field['type'] || 'page' == $field['type'] ) continue;
			
			$id = sanitize_title( $field['label'] );
			$values[ $id ] = array(
				'label' => $field['label'],
				'value' => $entry[ $field['id'] ],
			);
		}
		return $values;
	}

	static function overview() {
		if ( empty( $_GET['c'] ) ) { return; }
		
		ob_start();
		
		$entry_id = intval( $_GET['c'] );
		$entry = RGFormsModel::get_lead( $entry_id ); 
		$form = GFFormsModel::get_form_meta( $entry['form_id'] );
		
		if ( ! empty( $entry ) && ! empty( $form ) ) {
			
			$values = self::entry_values( $form, $entry );
			
			// Permission check
			if ( $values['retailer-id']['value'] != get_current_user_id() ) {
				echo '<p>' . __( 'You do not have permission to view this order', 'gzmgl_retail' ) . '</p>';
				return ob_get_clean();
			}

			// Get all the product ids
			$ids = empty( $values['device-id']['value'] ) ? array() : array_map( 'intval', explode( ',', $values['device-id']['value'] ) );
			
			$address = '';
			$address .= $values['first-name']['value'] . ( empty( $values['last-name']['value'] ) ? '' : ' ' . $values['last-name']['value'] ) . '<br/>';
			$address .= $values['street-address']['value'] . ( empty( $values['address-line-2']['value'] ) ? '' : ' ' . $values['address-line-2']['value'] ) . '<br/>';
			$address .= $values['city']['value'] . ( empty( $values['state']['value'] ) ? '' : ' ' . $values['state']['value'] ) . ( empty( $values['province']['value'] ) ? '' : ' ' . $values['province']['value'] );
			$address .= ( empty( $values['zip-code']['value'] ) ? '' : ' ' . $values['zip-code']['value'] ) . ( empty( $values['postal-code']['value'] ) ? '' : ' ' . $values['postal-code']['value'] ) . '<br/>';
			$address .= $values['country']['value'] . '<br/>';
			$address .= '<br/>';
			$address .= $values['email']['value'] . '<br/>';
			$address .= $values['phone']['value'] . '<br/>';

			?>
			<div class="order-overview">
				<h3><?php _e( 'Order Overview', 'gzmgl_retail' ); ?></h3>
			
			
				<table class="order-details">
					<thead>
						<tr>
							<th><?php _e( 'Device', 'gzmgl_retail' ); ?></th>
							<th><?php _e( 'Address', 'gzmgl_retail' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php
								foreach( $ids as $id ) {
									$label = Gzmgl_Retail_Gravity_Forms::product_label( $id, false, false, true, '<br/>' );
									echo '<p>' . $label . '</p>';
								}
								if ( ! empty( $values['serial']['value'] ) && 'false' != $values['serial']['value'] ) {
									echo '<p>' . $values['serial']['value'] . '</p>';
								}
							?></td>
							<td><?php echo $address; ?></td>
						</tr>
					</tbody>
				</table>

				<br/>
				
			<?php

			// Get the trade number from the retailer id and form entry
			$trade_nr = ( empty( $values['retailer-id']['value'] ) ? '' : $values['retailer-id']['value'] . '-' ) . $entry_id;
			
			// Get all serials
			$serials = empty( $values['serial']['value'] ) ? array() : array_map( 'trim', explode( ',', $values['serial']['value'] ) );

			$count = 1;			
			foreach( $ids as $id ) {

				// Add a count to the trade number for multiple devices in one order
				if ( $count > 1 ) { $trade_nr .= '-' . $count; }
				
				$label = Gzmgl_Retail_Gravity_Forms::product_label( $id, false, true );
				
				$trade_label = 'TRADE: ' . $trade_nr;
				$serial = array_shift( $serials );
				
				$sep = ' | ';
				$data = $label . $sep . ( empty( $serial ) ? '' : $serial . $sep ) . $trade_label;
				$image = self::generate_qrcode_image( $trade_nr, $data );
				?>
				
				<div class="sticker-label">
					<span class="device"><?php echo $label; ?></span>
					<?php if ( ! empty( $serial ) && 'false' != $serial ) : ?>
					<span class="serial"><?php echo $serial; ?></span>
					<?php endif; ?>
					<img src="<?php echo $image; ?>" />
					<span class="trade"><?php echo $trade_label; ?></span>
				</div>	
				<?php							
				
				$count++;
			}

			?>
			</div>
			
			<button id="print_overview" class="hide_from_print"><?php _e( 'Print Overview', 'gzmgl_retail' ); ?></button>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					
					$( '#print_overview' ).click( function() {
						window.print();
					} );

				} );
			</script>

			<?php
		} else {
			echo '<p>' . __( 'Order not found.', 'gzmgl_retail' ) . '</p>';
		}
		
		return ob_get_clean();
	}

	static function generate_qrcode_image( $name, $data ) {
		
		$uploads_dir = wp_upload_dir();
		$dir = trailingslashit( $uploads_dir['basedir'] ) . trailingslashit( 'gzmgl_retail' );
		if ( ! file_exists( $dir ) ) { mkdir( $dir, 0777, true ); }
		
		$qrcode_image = $dir . $name . '.png';

		if ( file_exists( $qrcode_image ) ) { return str_replace( $uploads_dir['basedir'], $uploads_dir['baseurl'], $qrcode_image ); }

		// Generate qrcode		
		if ( ! class_exists( 'QRcode' ) ) { require_once( Gzmgl_Retail::$dir . '/lib/phpqrcode/qrlib.php' ); }
		QRcode::png( $data, $qrcode_image, QR_ECLEVEL_L, 20 );

		// Check if qrcode was created
		if ( ! file_exists( $qrcode_image ) ) { return false; }

		return str_replace( $uploads_dir['basedir'], $uploads_dir['baseurl'], $qrcode_image );
	}
	

}
Gzmgl_Retail_Order::init();