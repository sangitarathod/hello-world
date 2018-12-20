<?php
class Gzmgl_Retail_Gravity_Forms {
	
	static function init() {

		// Add a shortcode for the site url to use in a gravity form
		add_shortcode( 'site_url', __CLASS__ . '::site_url' );

		// Populate cart_total variable
		add_filter( 'gform_field_value_cart_total', __CLASS__ . '::cart_total' );

		// Populate cart_ids variable
		add_filter( 'gform_field_value_cart_ids', __CLASS__ . '::cart_ids' );

		// Populate cart_contents variable
		add_filter( 'gform_field_value_cart_contents', __CLASS__ . '::cart_contents' );

		// Populate cart_serial variable
		add_filter( 'gform_field_value_cart_serial', __CLASS__ . '::cart_serial' );

		// Populate retailer_id variable
		add_filter( 'gform_field_value_retailer_id', __CLASS__ . '::retailer_id' );

		// Populate retailer_name variable
		add_filter( 'gform_field_value_retailer_name', __CLASS__ . '::retailer_name' );

		// Adjust Horizontal height
		add_action( 'wp_footer', __CLASS__ . '::mfn_sectionH' );

		// Post form after submission
		add_action( 'gform_after_submission_9', __CLASS__ . '::post_to_third_party', 10, 2 );

	}
	
	// Add a shortcode for the site url
	static function site_url() {
		return site_url();
	}

	// Populate cart_total variable
	static function cart_total( $value ) {
		return WC()->cart->get_cart_total();
	}

	// Populate cart_ids variable
	static function cart_ids( $value ) {
		$ids = array();

		foreach( WC()->cart->get_cart() as $item => $values ) {
			for ( $i = 0; $i < $values['quantity']; $i++ ) {
				$ids[] = $values['product_id'];
			}
		}
		
		return implode( ', ', $ids ); 
	}

	// Populate cart_contents variable
	static function cart_contents( $value ) {
		$cart_contents = '';

		foreach( WC()->cart->get_cart() as $item => $values ) { 
			$_product = $values['data']->post;
			
			$label = self::product_label( $values['product_id'], $values ); 
	
			for ( $i = 0; $i < $values['quantity']; $i++ ) {
				$cart_contents .= $label . ' '; 
			}
			
		} 
		
		return trim( $cart_contents );
	}
	
	// Get product label
	static function product_label( $product_id, $cart_item = false, $short = false, $formatted_price = false, $sep = ' ^ ' ) {
		$price = get_post_meta( $product_id, '_price', true );
		$taxonomies = array(
			'pa_phone-brand',
			'pa_phone-carrier',
			'pa_phone-model',
			'pa_phone-capacity',
			'pa_phone-condition',
			'pa_ipod-model',
			'pa_ipod-gen',
			'pa_ipod-memory',
			'pa_is-your-ipod-engraved',
			'pa_ipod-condition',
			'pa_watch-brand',
			'pa_watch-model',
			'pa_watch-size',
			'pa_watch-color',
			'pa_watch-band',
			'pa_watch-condition',
			'pa_tablet-brand',
			'pa_tablet-model',
			'pa_tablet-carrier',
			'pa_tablet-screensize',
			'pa_tablet-capacity',
			'pa_tablet-processor',
			'pa_tablet-mpn',
			'pa_tablet-condition',
			'pa_ipad-carrier',
			'pa_ipad-model',
			'pa_ipad-capacity',
			'pa_ipad-condition',
			'pa_computer-model',
			'pa_computer-screen-size',
			'pa_computer-release-year',
			'pa_computer-retina-display',
			'pa_computer-processor-speed',
			'pa_computer-hard-drive',
			'pa_computer-condition'
		);		
		$terms = wp_get_post_terms( $product_id, $taxonomies );		
		foreach ( $terms as $term ) {
			switch( $term->taxonomy ) { 

				//------------------------------------------------------------------------	

				// PHONE CONDITION
				case 'pa_phone-condition':
					$condition = $term->name;
					$type      = 'phone';
				break;

				// PHONE CARRIER
				case 'pa_phone-carrier':
					$carrier = $term->name;
				break;

				// PHONE BRAND
				case 'pa_phone-brand':
					$brand = $term->name;
				break;

				// PHONE MODEL
				case 'pa_phone-model':
					$model = $term->name;
				break;
				
				case 'pa_phone-capacity':
					$capacity = $term->name;
				break;

				//------------------------------------------------------------------------	
			
				// IPOD CONDITION & CARRIER
				case 'pa_ipod-condition':
					$condition = $term->name;
					$type      = 'ipod';			
					$carrier   = 'No Carrier';
				break;

				// IPOD MODEL
				case 'pa_ipod-model':
					$model = $term->name;
				break;

				// IPOD ENGRAVED
				case 'pa_is-your-ipod-engraved':
					if ( $term->name == 'yes' ) {
						$engraved = 'Engraved';
					}
				break;

				case 'pa_ipod-memory':
					$capacity = $term->name;
				break;

				//------------------------------------------------------------------------	
			
				// WATCH CONDITION & CARRIER
				case 'pa_watch-condition':
					$condition = $term->name;
					$type      = 'watch';
					$carrier   = 'No Carrier';
				break;

				// WATCH BRAND
				case 'pa_watch-brand':
					// MAKES BRAND APPEAR TWICE
					// $brand = $term->name;
				break;

				// WATCH MODEL
				case 'pa_watch-model':
					$model = $term->name;
				break;

				//------------------------------------------------------------------------			

				// TABLET CONDITION
				case 'pa_tablet-condition':
					$condition = $term->name;
					$type      = 'tablet';
				break;

				// TABLET BRAND
				case 'pa_tablet-brand':
					$brand = $term->name;
				break;

				// TABLET CARRIER
				case 'pa_tablet-carrier':
					$carrier	= $term->name;
				break;

				// TABLET MODEL
				case 'pa_tablet-model':
					$model = $term->name;
				break;

				case 'pa_tablet-capacity':
					$capacity = $term->name;
				break;

				//------------------------------------------------------------------------

				// IPAD CONDITION 
				case 'pa_ipad-condition':
					$condition = $term->name;
					$type      = 'tablet';
				break;

				// IPAD CARRIER 
				case 'pa_ipad-carrier':
					$carrier = $term->name;
				break;

				// IPAD MODEL
				case 'pa_ipad-model':
					$model = $term->name;
				break;

				case 'pa_ipad-capacity':
					$capacity = $term->name;
				break;
			
				//------------------------------------------------------------------------

				// COMPUTER CONDITION & CARRIER
				case 'pa_computer-condition':
					$condition = $term->name;
					$type      = 'laptop';
					$carrier   = 'No Carrier';
				break;

				// COMPUTER MODEL
				case 'pa_computer-model':
					$model = $term->name;
				break;
				
				case 'pa_computer-hard-drive':
					$capacity = $term->name;
				break;
				
				default:
					$final_attributes .= $term->name . ' ';
			}
		}			


		$label = '';

		if ( ! $short ) {
			
			// Create a label with all the details
			if ( ! empty( $brand ) ) { $label .= $brand . ' '; }
			$label .= $model;
			if ( ! empty( $capacity ) ) { $label .= ' ' . $capacity; }
			if ( ! empty( $final_attributes ) ) { $label .= ' ' . $final_attributes; }
			if ( ! empty( $engraved ) ) { $label .= ' ' . $engraved; }
			$label .= $sep;
			$label .= $carrier . $sep;
			$label .= $condition . $sep;
			
			if ( $formatted_price ) {
				$label .= wc_price( $price ) . $sep;
			} else {
				$label .= $price . ',' . $price . $sep;
			}
			
			$label .= $type . ' ';
				
			// Add serial number
			if ( ! empty( $cart_item['serial'] ) ) {
				$label .= '^ ' . $cart_item['serial'] . ' ';
			}
		} else {
			
			// Create a shorthand label
			$label = self::shorten_string( $model );
			if ( ! empty( $capacity ) ) { $label .= '-' . self::shorten_string( $capacity, false ); }
			if ( ! empty( $carrier ) && 'No Carrier' != $carrier ) { $label .= '-' . self::shorten_carrier( $carrier ); }
			if ( ! empty( $condition ) ) { $label .= '-' . self::shorten_condition( $condition ); }

		}
		
		return $label;
	}
	
	// Shorten string
	static function shorten_string( $string, $shorten_words = true, $length = 0 ) {
		$string = preg_replace( '/[^A-Z0-9]/', '', strtoupper( $string ) );
		if ( $shorten_words ) {
			$search = array(
				'PHONE',
				'FITBIT',
				'SAMSUNGGALAXY',
				'GALAXY',
				'IPAD',
				'WINDOWS',
				'MACBOOK',
			);
			$replace = array(
				'PH',
				'FB',
				'SG',
				'SG',
				'IP',
				'W',
				'MB',
			);
			$string = str_replace( $search, $replace, $string );
		}
		if ( $length ) {
			$string = substr( $string, 0, $length );
		}
		return $string;
	}

	// Shorten carrier
	static function shorten_carrier( $string ) {
		$string = preg_replace( '/[^A-Z0-9]/', '', strtoupper( $string ) );
		switch( $string ) {
			case 'VERIZON':
				$string = 'VZ';
			break;
			
			case 'WIFIONLY':
				$string = 'WF';
			break;
			
			default:
				$string = substr( $string, 0, 2 );
		}
		return $string;
	}

	// Shorten condition
	static function shorten_condition( $string ) {
		$string = preg_replace( '/[^A-Z0-9]/', '', strtoupper( $string ) );
		switch( $string ) {
			case 'FLAWLESS':
				$string = 'A';
			break;
			
			case 'GOOD':
				$string = 'B';
			break;

			case 'BROKEN':
			case 'DAMAGED':
				$string = 'C';
			break;

			case 'NOPOWER':
			case 'DAMAGEDNOPOWER':
				$string = 'D';
			break;
			
			default:
				$string = substr( $string, 0, 2 );
		}
		return $string;
	}

	// Populate cart_serial variable
	static function cart_serial( $value ) {
		$serials = array();

		foreach( WC()->cart->get_cart() as $item => $values ) {
			if ( ! empty( $values['serial'] ) ) {
				$serials[] = $values['serial'];
			}
		}
		
		return implode( ' | ', $serials );
	} 

	// Populate retailer_id variable
	static function retailer_id( $value ) {
		return get_current_user_id();
	} 

	// Populate retailer_name variable
	static function retailer_name( $value ) {
		$user = wp_get_current_user();
		return empty( $user->display_name ) ? '' : $user->display_name;
	} 

	/*-----------------------------------------------------------------------------------*/
	/*	Gravity Forms: Full Screen resize on each step.
	/*  Copied from themes/betheme/js/scripts.js mfn_sectionH();
	/*-----------------------------------------------------------------------------------*/
	static function mfn_sectionH() { ?>
	<script type="text/javascript">
		jQuery( document ).ready( function ( $ ) {

        function mfn_sectionH() {
	        	var windowH = $( window ).height();

				$( '.section.checkout-full-screen' ).each( function () {
					var section = $( this );
					var wrapper = section.find( '.gform_fields:visible' );
					var description = wrapper.find( '.title-description' );
					
					section.css( 'padding', 0 ).css( 'min-height', windowH - 75 );
					
					var height = Math.ceil( wrapper.height() - description.outerHeight() );
					if ( height < 1 ) {
						var padding = Math.ceil( windowH - 580 );
					} else {
						var padding = Math.ceil( ( windowH - 247 ) - height );
					}
					
					// 20 = column margin-bottom / 2
					var padding_t = Math.max( 0, Math.ceil( padding / 2 - 50 ) );
					var padding_b = Math.max( 0, Math.ceil( padding / 2 + 50 ) );
					
					description.css( 'padding-bottom', padding_t );
					wrapper.css( 'padding-bottom', padding_b );
				} );
			}

			$( window ).bind( 'debouncedresize', mfn_sectionH );
			
			// Full Screen Checkout Form
			$( document ).bind( 'gform_post_render', mfn_sectionH );
	        
		} );
	</script>
	<?php
	}


	// Gravity Forms: Send entry data to third-party
	// https://www.gravityhelp.com/documentation/article/gform_after_submission/
	static function post_to_third_party( $entry, $form ) {
		
		$url = 'http://labels.gizmogul.com/insert_retailer.php';
		define( "ENCRYPTION_KEY", "p2G0zVwNnnK70P1bBhamsbVOUtyE8915" );
		
		// Get all entry values
		$values = Gzmgl_Retail_Order::entry_values( $form, $entry );

		// Add Retailer Location
		if ( ! empty( $values['retailer-id']['value'] ) && class_exists( 'KWS_User_Groups' ) ) {
			$groups = KWS_User_Groups::get_user_user_groups( intval( $values['retailer-id']['value'] ) );
			
			// Add first group
			if ( ! empty( $groups[0] ) ) {
				$values['location'] = array( 'label' => $groups[0]->name, 'value' => $groups[0]->term_id );
			}
		}

		// Add Retailer Address
		if ( ! empty( $values['retailer-id']['value'] ) && class_exists( 'KWS_User_Groups' ) ) {
			$retailer_id = intval( $values['retailer-id']['value'] );
			$retailer = get_userdata( $retailer_id );
			
			$address = array(
				'first_name' => ucwords( get_user_meta( $retailer_id, 'shipping_first_name', true ) ),
				'last_name'  => ucwords( get_user_meta( $retailer_id, 'shipping_last_name', true ) ),
				'company'    => ucwords( get_user_meta( $retailer_id, 'shipping_company', true ) ),
				'address_1'  => ucwords( get_user_meta( $retailer_id, 'shipping_address_1', true ) ),
				'address_2'  => ucwords( get_user_meta( $retailer_id, 'shipping_address_2', true ) ),
				'city'       => get_user_meta( $retailer_id, 'shipping_city', true ),
				'state'      => get_user_meta( $retailer_id, 'shipping_state', true ),
				'postcode'   => get_user_meta( $retailer_id, 'shipping_postcode', true ),
				'country'    => get_user_meta( $retailer_id, 'shipping_country', true ),
				'phone'      => get_user_meta( $retailer_id, 'billing_phone', true ),
				'email'      => empty( $retailer->user_email ) ? '' : $retailer->user_email,
			);
			
			$values['retailer-address'] = array( 'label' => __( 'Retailer Address', 'gzmgl_retail' ), 'value' => $address );
		}
		
		// Add user IP
		if ( ! empty( $entry['ip'] ) ) {
			$values['ip'] = array( 'label' => __( 'IP', 'gzmgl_retail' ), 'value' => $entry['ip'] );
		}
		
		// Add Serial API calls
		if ( ! empty( $values['serial']['value'] ) ) {
			$serials = explode( '|', preg_replace( '/[^0-9a-z,|]/', '', strtolower( $values['serial']['value'] ) ) );
			if ( ! empty( $serials ) && is_array( $serials ) ) {
				foreach ( $serials as $serial ) {
					$values['api_calls'][ $serial ] = Gzmgl_Retail_Serial::api_response( explode( ',', $serial ) ); 
				}
			}
		}

		// Post data to url
		$response = wp_remote_post( $url, array(
			'body' => array(
				'data' => self::base64_url_encode( self::encrypt( serialize( $values ) , ENCRYPTION_KEY ) ),
			),
		) );

		/* We currently do not do anything with the response
		if ( ! is_wp_error( $response ) ) {
			
			$body = wp_remote_retrieve_body($response);

			// Decode the output
			$decode = self::base64_url_decode( $body );
			$decrypt = self::decrypt( $decode, ENCRYPTION_KEY );
			$decrypt = trim( $decrypt );
			$d = explode( '|', $decrypt );

			$id = $d[0];
			$success = $d[1];
			$method = $d[2];
			$label = $d[3];

		}*/
	
	}

	static function base64_url_encode($input) {
		return strtr( base64_encode( $input ), '+/=', '-_,' );
	}

	static function base64_url_decode( $input ) {
		return base64_decode( strtr( $input, '-_,', '+/=' ) );
	}

	static function encrypt( $pure_string, $encryption_key ) {
		$iv_size = mcrypt_get_iv_size( MCRYPT_BLOWFISH, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		$encrypted_string = mcrypt_encrypt( MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv );
		return $encrypted_string;
	}

	static function decrypt( $encrypted_string, $encryption_key ) {
		$iv_size = mcrypt_get_iv_size( MCRYPT_BLOWFISH, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		$decrypted_string = mcrypt_decrypt( MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv );
		return $decrypted_string;
	}


}
Gzmgl_Retail_Gravity_Forms::init();