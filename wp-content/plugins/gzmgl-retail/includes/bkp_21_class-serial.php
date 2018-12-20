<?php
class Gzmgl_Retail_Serial {

	static function init() {

		if ( is_admin() ) {
			// WooCommerce settings tab
			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::settings_tabs', 50 );
			add_action( 'woocommerce_settings_tabs_gzmgl_retail', __CLASS__ . '::settings_tab' );
			add_action( 'woocommerce_update_options_gzmgl_retail', __CLASS__ . '::update_settings' );
		}
		
		add_action( 'wp_ajax_check_serial_number', __CLASS__ . '::check_serial' );

	}
	
	public static function settings_tabs( $settings_tabs ) {
		$settings_tabs['gzmgl_retail'] = __( 'Gizmogol Retail', 'gzmgl_retail' );
		return $settings_tabs;
	}

	public static function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}

	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	public static function get_settings() {
		$settings = array(
			'section_title' => array(
				'id'       => 'gzmgl_retail_section_title',
				'name'     => __( 'API Settings', 'gzmgl_retail' ),
				'type'     => 'title',
				'desc'     => '',
			),
			'api_url' => array(
				'id'       => 'gzmgl_retail_api_url',
				'name'     => __( 'API Url', 'gzmgl_retail' ),
				'type'     => 'text',
				'default'  => 'https://gapi.checkmend.com/duediligencedevice/',
				'desc'     => __( 'Default: https://gapi.checkmend.com/duediligencedevice/', 'gzmgl_retail' ),
				'css'      => 'min-width:300px;',
				'autoload' => false,
			),
			'partner_id' => array(
				'id'       => 'gzmgl_retail_partner_id',
				'name'     => __( 'Partner ID', 'gzmgl_retail' ),
				'type'     => 'text',
				'desc'     => __( 'Your Partner ID is given to you by CheckMEND Support', 'gzmgl_retail' ),
				'css'      => 'min-width:300px;',
				'autoload' => false,
			),
			'secret_key' => array(
				'id'       => 'gzmgl_retail_secret_key',
				'name'     => __( 'Secret Key', 'gzmgl_retail' ),
				'type'     => 'text',
				'desc'     => __( 'Your Secret Key is given to you by CheckMEND Support', 'gzmgl_retail' ),
				'css'      => 'min-width:300px;',
				'autoload' => false,
			),
			'store_id' => array(
				'id'       => 'gzmgl_retail_store_id',
				'name'     => __( 'Store ID', 'gzmgl_retail' ),
				'type'     => 'text',
				'default'  => '1',
				'desc'     => __( 'Default: 1 for testing, 2 for production', 'gzmgl_retail' ),
				'css'      => 'min-width:300px;',
				'autoload' => false,
			),
			'section_end' => array(
				'id' => 'gzmgl_retail_section_end',
				'type' => 'sectionend',
			)
		);
		return $settings;
	}

	static function check_serial() {
		if ( empty( $_REQUEST['security'] ) || ! wp_verify_nonce( $_REQUEST['security'], 'check_serial_number' ) ) { die(); }
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) { die(); }

		$response = array();
		
		if ( ! empty( $_REQUEST['serial'] ) ) {		
			$serials = explode( ',', preg_replace( '/[^0-9a-z,]/', '', strtolower( $_REQUEST['serial'] ) ) );
			if ( self::greguly_codeable_luhn_check( substr( $serials[0], 0, 15 ) ) ) {
				$response = self::api_response( $serials );
			} else {
				$response = array( 'check_error' => 'Invalid IMEI number.' );
			}
		} 
		wp_send_json( $response );
		die();
	}

	/* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
	* This code has been released into the public domain, however please      *
	* give credit to the original author where possible.                      */

	static function greguly_codeable_luhn_check($number) {

		// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
		$number=preg_replace('/\D/', '', $number);

		// Set the string length and parity
		$number_length=strlen($number);
		$parity=$number_length % 2;

		// Loop through each digit and do the maths
		$total=0;
		for ($i=0; $i<$number_length; $i++) {
			$digit=$number[$i];
			// Multiply alternate digits by two
			if ($i % 2 == $parity) {
				$digit*=2;
				// If the sum is two digits, add them together (in effect)
				if ($digit > 9) {
					$digit-=9;
				}
			}
			// Total up the digits
			$total+=$digit;
		}

		// If the total mod 10 equals 0, the number is valid
		return ($total % 10 == 0) ? TRUE : FALSE;

	}

	
	static function api_response( $serials ) {
		$result = array();

		// Validate serials
		if ( empty( $serials ) || ! is_array( $serials ) ) {
			return array( 'error' => 'Invalid serial number(s)' );
		}

		// Return from cache if available
		$transient_name = 'gserial_' . implode( '_', $serials );
		if ( false !== ( $api_result = get_transient( $transient_name ) ) ) {
			return array(
				'result' => $api_result['result'],
				'makes'  => empty( $api_result['makes'] ) ? array() : $api_result['makes'],
				'cached' => true,
			);
		}
		
		$api_url = get_option( 'gzmgl_retail_api_url' );
		$partner_id = get_option( 'gzmgl_retail_partner_id' );
		$secret_key = get_option( 'gzmgl_retail_secret_key' );
		$store_id = get_option( 'gzmgl_retail_store_id' );
		
		if ( empty( $api_url ) || empty( $partner_id ) || empty( $secret_key ) || empty( $store_id ) ) {
			return array( 'error' => 'API details are not configured' );
		}
		
		$request_url = trailingslashit( $api_url );
		$request = json_encode( array(
			'storeid'  => $store_id,
			'category' => 0, 
			'serials'  => $serials,
		) );

		// Calculate hash for basic authentication
		$hash = sha1( $secret_key . $request );

		if ( false && 35 == get_current_user_id() ) {
			wp_die( 'api_response ' . print_r( $request, 1 ));

		}

		// Send the request to the API
		$response = wp_remote_post( $request_url, array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $partner_id . ':' . $hash ),
				'Accept'        => 'application/json',
				'Content-Type'  => 'application/json',
			),
			'body' => $request,
			'timeout' => 10,
		) );

		// Check response
		if ( is_wp_error( $response ) ) {
			$result['error'] = $response->get_error_message();
		} else {
			$api_result = json_decode( wp_remote_retrieve_body( $response ), true );
			
			if ( ! empty( $api_result['errors'] ) ) {
				$result['error'] = $api_result['errors'][0]['message'];
			} elseif ( ! empty( $api_result['result'] ) ) {
				$result['result'] = $api_result['result'];
				$result['makes']  = empty( $api_result['makes'] ) ? array() : $api_result['makes'];

				// Save result in a transient
				set_transient( $transient_name, $api_result, 1 * HOUR_IN_SECONDS );
			}
		}		

		return $result;
	}
	


}
Gzmgl_Retail_Serial::init();