<?php
class Gzmgl_Retail_Order {

	static function init() {
		add_action( 'wp', __CLASS__ . '::mark_complete' );
		add_shortcode( 'order-list', __CLASS__ . '::order_list' );
		add_shortcode( 'order-overview', __CLASS__ . '::overview' );
		
		add_action( 'wp_ajax_generate_overview', __CLASS__ . '::generate_overview_pdf_ajax' );
		add_action( 'wp_ajax_generate_packing', __CLASS__ . '::generate_packing_pdf_ajax' );
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
				<?php $print_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_packing' ), 'gzmgl_retail' ); ?>
				<a href="<?php echo $print_url; ?>" target="_blank" class="button hide_from_print"><?php _e( 'Print Packing Slip', 'gzmgl_retail' ); ?></a>
				<?php /*<button id="print_packing_slip" class="hide_from_print"><?php _e( 'Print Packing Slip', 'gzmgl_retail' ); ?></button>*/ ?>
				<a class="button hide_from_print" href="http://labels.gizmogul.com/print_retail.php?id=<?php echo get_current_user_id(); ?>" target="_blank"><?php _e( 'Download Shipping Label', 'gzmgl_retail' ); ?></a>
				<button onclick="location.href='<?php echo add_query_arg( 'order_complete_all', 1, get_permalink() ); ?>';" class="hide_from_print"><?php _e( 'Complete All Orders', 'gzmgl_retail' ); ?></button>
			</div>

			<?php /*
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					
					$( '#print_packing_slip' ).click( function() {
						window.print();
					} );

				} );
			</script>
			*/ ?>

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
			self::overview_html( $entry, $form );

			/*
		?>
			<button id="print_overview" class="hide_from_print"><?php _e( 'Print Overview', 'gzmgl_retail' ); ?></button>
			<button id="print_labels" class="hide_from_print"><?php _e( 'Print Label', 'gzmgl_retail' ); ?></button>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					
					$( '#print_overview' ).click( function() {
						window.print();
					} );

					$( '#print_labels' ).click( function() {
						var additional = $( '.order-overview' ).find( 'h3, .order-details' ); 
						additional.addClass( 'hide_from_print' );
						window.print();
						additional.removeClass( 'hide_from_print' );
					} );

				} );
			</script>
		*/ ?>

			<div class="button_row">
				<?php $print_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_overview&entry_id=' . $entry_id ), 'gzmgl_retail' ); ?>
				<a href="<?php echo $print_url; ?>" target="_blank" class="button"><?php _e( 'Print Overview', 'gzmgl_retail' ); ?></a>
				<?php /*$print_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_overview&only_label=1&entry_id=' . $entry_id ), 'gzmgl_retail' ); ?>
				<a href="<?php echo $print_url; ?>" target="_blank" class="button"><?php _e( 'Print Label', 'gzmgl_retail' ); ?></a> */ ?>
			</div>

			<?php
		} else {
			echo '<p>' . __( 'Order not found.', 'gzmgl_retail' ) . '</p>';
		}
		
		return ob_get_clean();
	}
	
	static function overview_html( $entry, $form, $only_labels = false, $pdf = false ) {
		$values = self::entry_values( $form, $entry );
			
		// Permission check
		if ( $values['retailer-id']['value'] != get_current_user_id() ) {
			echo '<p>' . __( 'You do not have permission to view this order', 'gzmgl_retail' ) . '</p>';
			return;
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
		$address .= '<br/>';
		$address .= $values['driver-passport']['value'] . '<br/>';
		$address .= $values['document-type']['value'] . '<br/>';

		?>
		<div class="order-overview">
			<?php if ( $pdf ) { ob_start(); } ?>
			<?php if ( ! $only_labels ) : ?>

			<p>The Visa Prepaid Card ending in <em><?php echo substr( $values['card-number']['value'], -4 ) ?></em> is Active & Ready for Use.</p>

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
			<?php endif; ?>
			
		<?php
		if ( $pdf ) { $order_overview = ob_get_clean(); }

		// Get the trade number from the retailer id and form entry
		$trade_nr = ( empty( $values['retailer-id']['value'] ) ? '' : $values['retailer-id']['value'] . '-' ) . $entry['id'];
		
		// Get all serials
		$serials = empty( $values['serial']['value'] ) ? array() : array_map( 'trim', explode( ',', $values['serial']['value'] ) );

		$count = 1;			
		foreach( $ids as $id ) {

			// Add a count to the trade number for multiple devices in one order
			$trade_nr = self::add_count( $trade_nr, $count );

			$label = Gzmgl_Retail_Gravity_Forms::product_label( $id, false, true );
			
			$trade_label = 'TRADE: ' . $trade_nr;
			$serial = array_shift( $serials );
			
			$sep = ' | ';
			//$data = $label . $sep . ( empty( $serial ) ? '' : $serial . $sep ) . $trade_label; // QR code string
			$data = $trade_nr; // Barcode string
			$image = self::generate_barcode_image( $trade_nr, $data );
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
		
		if ( $pdf ) { echo $order_overview; }

		?>
		</div>
		<?php
	}

	static function generate_barcode_image( $name, $data ) {
		
		$uploads_dir = wp_upload_dir();
		$dir = trailingslashit( $uploads_dir['basedir'] ) . trailingslashit( 'gzmgl_retail' );
		if ( ! file_exists( $dir ) ) { mkdir( $dir, 0777, true ); }
		
		$barcode_image = $dir . $name . '.png';

		if ( file_exists( $barcode_image ) ) { return str_replace( $uploads_dir['basedir'], $uploads_dir['baseurl'], $barcode_image ); }

		/*// Generate qrcode		
		if ( ! class_exists( 'QRcode' ) ) { require_once( Gzmgl_Retail::$dir . '/lib/phpqrcode/qrlib.php' ); }
		QRcode::png( $data, $barcode_image, QR_ECLEVEL_L, 20 );*/
		
		// Generate barcode		
		if ( ! function_exists( 'barcode' ) ) { require_once( Gzmgl_Retail::$dir . '/lib/php-barcode/barcode.php' ); }
		barcode( $barcode_image, $data, 320, 'horizontal', 'code128', false );

		// Check if barcode was created
		if ( ! file_exists( $barcode_image ) ) { return false; }

		return str_replace( $uploads_dir['basedir'], $uploads_dir['baseurl'], $barcode_image );
	}
	
	static function add_count( $string, $count ) {
		if ( $count == 2 ) {
			$string .= '-' . $count;
		} elseif ( $count > 2 ) {
			$prefix = substr( $string, 0, strrpos( $string, '-' ) );
			if ( ! empty( $prefix ) ) { $string = $prefix . '-' . $count; }
		}
		return $string;
	}

	public static function generate_overview_pdf_ajax() {
		// Check the nonce
		if( empty( $_GET['action'] ) || ! is_user_logged_in() || ! wp_verify_nonce( $_GET['_wpnonce'], 'gzmgl_retail' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'gzmgl_retail' ) );
		}

		// Check if all parameters are set
		if ( empty( $_GET['entry_id'] ) ) {
			wp_die( __( 'Some of the export parameters are missing.', 'gzmgl_retail' ) );
		}

		$entry_id = intval( $_GET['entry_id'] );

		$entry = RGFormsModel::get_lead( $entry_id ); 
		$form = GFFormsModel::get_form_meta( $entry['form_id'] );

		if ( empty( $entry ) || empty( $form ) ) {
			wp_die( __( 'No data found.', 'gzmgl_retail' ) );
		}

		ob_start();
		$only_labels = empty( $_GET['only_label'] ) ? false : true;
		self::overview_html( $entry, $form, $only_labels, true );
		if ( ! $only_labels ) {
			echo '<p>I am the rightful owner of the goods and I am entitled to sell, consign, or trade the goods.</p>';
			echo '<p>Signature: ____________________ &nbsp; &nbsp; Date: ____________________</p>';
		}
		$data = ob_get_clean();

		if ( ! ( $pdf = self::generate_pdf( $data ) ) ) { exit; }

		$filename = 'overview.pdf';

		// Switch headers according to output setting
		if ( empty( $output_mode ) || $output_mode == 'display' ) {
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
		} else {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $filename . '"'); 
			header('Content-Transfer-Encoding: binary');
			header('Connection: Keep-Alive');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}

		// output PDF data
		echo( $pdf );

		exit;
	}

	public static function generate_packing_pdf_ajax() {
		// Check the nonce
		if( empty( $_GET['action'] ) || ! is_user_logged_in() || ! wp_verify_nonce( $_GET['_wpnonce'], 'gzmgl_retail' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'gzmgl_retail' ) );
		}

		$data = self::order_list();

		if ( ! ( $pdf = self::generate_pdf( $data ) ) ) { exit; }

		$filename = 'overview.pdf';

		// Switch headers according to output setting
		if ( empty( $output_mode ) || $output_mode == 'display' ) {
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
		} else {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $filename . '"'); 
			header('Content-Transfer-Encoding: binary');
			header('Connection: Keep-Alive');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}

		// output PDF data
		echo( $pdf );

		exit;
	}

	static function generate_pdf( $data ) {
		
		// Load html template
		$html = file_get_contents( Gzmgl_Retail::$dir . '/templates/overview.html' );
		
		// Replace placeholders
		$search = array(
			'**data**',
		);
		$replace = array(
			$data,
		);
		$html = str_replace( $search, $replace, $html );
		
		try {
		
			// Generate frontpage PDF
			if ( ! class_exists( 'DOMPDF' ) ) { require_once( Gzmgl_Retail::$dir . '/lib/dompdf/dompdf_config.inc.php' ); }
			$dompdf = new DOMPDF();
			$dompdf->set_paper( 'A4' );
			$dompdf->set_base_path( Gzmgl_Retail::$dir . '/templates/' );
			$dompdf->load_html( $html );
			$dompdf->render();
	
			return $dompdf->output();
			
		} catch( Exception $e ) {
			return false;
		}
	}

}
Gzmgl_Retail_Order::init();