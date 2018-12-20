<?php

class Gzmgl_Retail_Serial {

    static function init() {

        if (is_admin()) {
            // WooCommerce settings tab
            add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::settings_tabs', 50);
            add_action('woocommerce_settings_tabs_gzmgl_retail', __CLASS__ . '::settings_tab');
            add_action('woocommerce_update_options_gzmgl_retail', __CLASS__ . '::update_settings');
        }

        add_action('wp_ajax_check_serial_number', __CLASS__ . '::check_serial');
    }

    public static function settings_tabs($settings_tabs) {
        $settings_tabs['gzmgl_retail'] = __('Gizmogol Retail', 'gzmgl_retail');
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields(self::get_settings());
    }

    public static function update_settings() {
        woocommerce_update_options(self::get_settings());
    }

    public static function get_settings() {
        $settings = array(
            'section_title' => array(
                'id' => 'gzmgl_retail_section_title',
                'name' => __('API Settings', 'gzmgl_retail'),
                'type' => 'title',
                'desc' => '',
            ),
            'api_url' => array(
                'id' => 'gzmgl_retail_api_url',
                'name' => __('API Url', 'gzmgl_retail'),
                'type' => 'text',
                'default' => 'https://gapi.checkmend.com/duediligencedevice/',
                'desc' => __('Default: https://gapi.checkmend.com/duediligencedevice/', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'partner_id' => array(
                'id' => 'gzmgl_retail_partner_id',
                'name' => __('Partner ID', 'gzmgl_retail'),
                'type' => 'text',
                'desc' => __('Your Partner ID is given to you by CheckMEND Support', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'secret_key' => array(
                'id' => 'gzmgl_retail_secret_key',
                'name' => __('Secret Key', 'gzmgl_retail'),
                'type' => 'text',
                'desc' => __('Your Secret Key is given to you by CheckMEND Support', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'store_id' => array(
                'id' => 'gzmgl_retail_store_id',
                'name' => __('Store ID', 'gzmgl_retail'),
                'type' => 'text',
                'default' => '1',
                'desc' => __('Default: 1 for testing, 2 for production', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'fmip_api_url' => array(
                'id' => 'gzmgl_retail_fmip_api_url',
                'name' => __('FMIP API Url', 'gzmgl_retail'),
                'type' => 'text',
                'default' => 'https://gapi.checkmend.com/duediligence/',
                'desc' => __('Default: https://gapi.checkmend.com/duediligence/', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'fmip_partner_id' => array(
                'id' => 'gzmgl_retail_fmip_partner_id',
                'name' => __('FMIP Partner ID', 'gzmgl_retail'),
                'type' => 'text',
                'desc' => __('Your Partner ID is given to you by CheckMEND Support', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'fmip_secret_key' => array(
                'id' => 'gzmgl_retail_fmip_secret_key',
                'name' => __('FMIP Secret Key', 'gzmgl_retail'),
                'type' => 'text',
                'desc' => __('Your Secret Key is given to you by CheckMEND Support', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'fmip_store_id' => array(
                'id' => 'gzmgl_retail_fmip_store_id',
                'name' => __('FMIP Store ID', 'gzmgl_retail'),
                'type' => 'text',
                'default' => '1',
                'desc' => __('Default: 1 for testing, 2 for production', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'fmip_organisation_id' => array(
                'id' => 'gzmgl_retail_fmip_organisation_id',
                'name' => __('FMIP Organisation ID', 'gzmgl_retail'),
                'type' => 'text',
                'default' => '1',
                'desc' => __('Default: 1 for testing, 2 for production', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
                'autoload' => false,
            ),
            'makeandmodel_api_url' => array(
                'id' => 'gzmgl_retail_model_api_url',
                'name' => __('Make & Model API Url', 'gzmgl_retail'),
                'type' => 'text',
                'default' => 'https://gapi.checkmend.com/makemodelext/',
                'desc' => __('Default: https://gapi.checkmend.com/makemodelext', 'gzmgl_retail'),
                'css' => 'min-width:300px;',
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
        if (empty($_REQUEST['security']) || !wp_verify_nonce($_REQUEST['security'], 'check_serial_number')) {
            die();
        }
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            die();
        }

        $response = array();
        /* if ( ! empty( $_REQUEST['serial'] ) && empty( $_REQUEST['serial_no'] ) && empty( $_REQUEST['imei'] )) {	

          $serials = explode( ',', preg_replace( '/[^0-9a-z,]/', '', strtolower( $_REQUEST['serial'] ) ) );

          if ( self::greguly_codeable_luhn_check( substr( $serials[0], 0, 15 ) ) ) {

          $response = self::api_response( $serials );
          } else {
          $response = array( 'check_error' => 'Invalid IMEI number.' );
          //$response = self::api_response( $serials );
          }
          } */
        //converer checking
        if (!empty($_REQUEST['serial'])) {
            if ($_REQUEST['dev_name'] == 'phone') {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.meidconverter.net/convert?meid=' . $_REQUEST['serial']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                // This is what solved the issue (Accepting gzip encoding)

                $converter_response = curl_exec($ch);

                //$converter_response = wp_remote_get('https://www.meidconverter.net/convert?meid='.$_REQUEST['serial']);

                $converter_response = json_decode($converter_response, true);
                $imei = $converter_response['MEID_HEX'];

                $imei = explode(',', preg_replace('/[^0-9a-z,]/', '', strtolower($imei)));
                $imei[0] = self::luhn_check($imei[0]);

                if (self::greguly_codeable_luhn_check(substr($imei[0], 0, 15))) {


                    $response = self::api_response($imei);
                } else {
                    $response = array('check_error' => 'Invalid IMEI number.');
                }
            } else {
                $imei = explode(',', preg_replace('/[^0-9a-z,]/', '', strtolower($_REQUEST['serial'])));
                /* $imei[0]=self::luhn_check($imei[0]);
                  print_r($imei); */
                $response = self::mac_api_response($imei);
            }
        }
        /* if(!empty( $_REQUEST['serial_no'] ) && !empty( $_REQUEST['imei'] )){

          $serials = explode( ',', preg_replace( '/[^0-9a-z,]/', '', strtolower( $_REQUEST['serial_no'] ) ) );
          $imei=explode( ',', preg_replace( '/[^0-9a-z,]/', '', strtolower( $_REQUEST['imei'] ) ) );

          if ( self::greguly_codeable_luhn_check( substr( $imei[0], 0, 15 ) ) &&  self::greguly_codeable_serial_check($serials[0]) ) {

          $response = self::api_response( $serials ,$imei);
          } else {
          $response = array( 'check_error' => 'Invalid IMEI Or Serial number.' );
          //$response = self::api_response( $serials );
          }
          } */
        /* if(!empty( $_REQUEST['serial_no'] ) && empty( $_REQUEST['imei'])  && empty( $_REQUEST['serial'])){

          $serials = explode( ',', preg_replace( '/[^0-9a-z,]/', '', strtolower( $_REQUEST['serial_no'] ) ) );


          if ( self::greguly_codeable_serial_check($serials[0]) ) {

          $response = self::api_response( $serials);
          } else {
          $response = array( 'check_error' => 'Invalid IMEI Or Serial number.' );
          //$response = self::api_response( $serials );
          }
          } */
        wp_send_json($response);
        die();
    }

    static function luhn_check($number) {
        $number = preg_replace('/\D/', '', $number);
        $number_length = strlen($number);
        $parity = $number_length % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            if ($i % 2 != 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
        }
        $modulo = $total % 10;
        $nex_digit = 10 - $modulo;
        $number[$number_length + 1] = $nex_digit;
        $number = preg_replace('/\D/', '', $number);
        return $number;
    }

    /* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
     * This code has been released into the public domain, however please      *
     * give credit to the original author where possible.                      */

    static function greguly_codeable_luhn_check($number) {

        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number = preg_replace('/\D/', '', $number);

        // Set the string length and parity
        $number_length = strlen($number);
        $parity = $number_length % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }

        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? TRUE : FALSE;
    }

    static function greguly_codeable_serial_check($number) {

        // If the total mod 10 equals 0, the number is valid
        return (ctype_alnum($number)) ? TRUE : FALSE;
    }

    static function mac_api_response($serials) {
        $serials[0] = substr($serials[0], 0, 15);

        // Validate serials
        $result = array();
        if (empty($serials) || !is_array($serials)) {
            return array('error' => 'Invalid serial number(s)');
        }
        $response = '{"results":[{"model":"iPad","item":"4th Gen (Wi-Fi\/Verizon & Sprint\/GPS)","thumbnailUrl":"\/images\/ipod_thumbnails\/apple-ipad-3rd-gen.jpg","processorSpeed":"1.4 GHz*","processorType":"Apple A6X","introductionDate":"October 23, 2012*","discontinuedDate":"October 16, 2014**","orderNumber":"MD522LL\/A*","modelNumber":"A1460","emcNumber":"2606*","gestaltId":"N\/A","appleSubFamily":"iPad 4th Gen (Wi-Fi )","modelIdentifier":"iPad3,6","standardRAM":"1 GB*","standardVRAM":"N\/A","standardHD":"16, 32, 64, 128 GB*","standardOptical":"N\/A","specUrl":"http:\/\/www.everymac.com\/systems\/apple\/ipad\/specs\/apple-ipad-a1460-4th-gen-late-2012-4g-lte-verizon-sprint-specs.html","everyMacId":"iPad012"}],"total":1}';
        //$response = wp_remote_get( 'https://www.everymac.com/api/search?token=b22b5d87f5cc66528f813c210532d2e14986927d&format=json&q='.$serials[0] );
        //$response= '{"total":0,"results":[{"model":"iPad","item":"4th Gen (Wi-Fi\/Verizon & Sprint\/GPS)","thumbnailUrl":"http:\/\/www.everymac.com\/images\/ipod_thumbnails\/apple-ipad-3rd-gen.jpg","processorSpeed":"1.4 GHz*","processorType":"Apple A6X","introductionDate":"October 23, 2012*","discontinuedDate":"October 16, 2014**","orderNumber":"MD522LL\/A*","modelNumber":"A1460","emcNumber":"2606*","gestaltId":"N\/A","appleSubFamily":"iPad mini (Wi-Fi)","modelIdentifier":"iPad3,6","standardRAM":"1 GB*","standardVRAM":"N\/A","standardHD":"16, 32, 64, 128 GB*","standardOptical":"N\/A","specUrl":"http:\/\/www.everymac.com\/systems\/apple\/ipad\/specs\/apple-ipad-a1460-4th-gen-late-2012-4g-lte-verizon-sprint-specs.html","everyMacId":"iPad012"}],"other":[],"token":{"requestsMade":9,"since":"2017-09-08 01:56:05","retryAfter":0,"limit":{"requests":"10","within":"86400"}}}';

        if (is_wp_error($response)) {
            $result['error'] = $response->get_error_message();
        } else {
            $api_result = json_decode($response, true);
            //$api_result = json_decode( wp_remote_retrieve_body( $response ), true );

            if (!empty($api_result['error'])) {
                $result['error'] = $api_result['error'];
            } elseif (!empty($api_result['results'])) {
                $result['result'] = $api_result['results'];
                $result['total'] = $api_result['total'];
            }
        }

        return $result;
    }

    static function api_response($serials, $imei) {
        $serials[0] = substr($serials[0], 0, 15);

        $result = array();
        $result2 = array();
        // Validate serials
        if (empty($serials) || !is_array($serials)) {
            return array('error' => 'Invalid serial number(s)');
        }

        // Return from cache if available
        /* $transient_name = 'gserial_' . implode( '_', $serials );
          if ( false !== ( $api_result = get_transient( $transient_name ) ) ) {

          return array(
          'result' => $api_result['result'],
          'makes'  => empty( $api_result['makes'] ) ? array() : $api_result['makes'],
          'cached' => true,

          );
          } */

        $api_url = get_option('gzmgl_retail_api_url');
        $partner_id = get_option('gzmgl_retail_partner_id');
        $secret_key = get_option('gzmgl_retail_secret_key');
        $store_id = get_option('gzmgl_retail_store_id');

        if (empty($api_url) || empty($partner_id) || empty($secret_key) || empty($store_id)) {
            return array('error' => 'API details are not configured');
        }

        $request_url = trailingslashit($api_url);

        $request = json_encode(array(
            'storeid' => $store_id,
            'category' => 0,
            'serials' => $serials,
                ));
        /* if($imei){
          $imei_request=json_encode( array(
          'storeid'  => $store_id,
          'category' => 0,
          'serials'  => $imei,
          ) );
          $imei_hash = sha1( $secret_key . $imei_request );
          $imei_response= wp_remote_post( $request_url, array(
          'headers' => array(
          'Authorization' => 'Basic ' . base64_encode( $partner_id . ':' . $imei_hash ),
          'Accept'        => 'application/json',
          'Content-Type'  => 'application/json',
          ),
          'body' => $imei_request,
          'timeout' => 10,
          ) );
          if ( is_wp_error( $imei_response ) ) {
          $result2['error'] = $imei_response->get_error_message();
          } else {
          $imei_result = json_decode( wp_remote_retrieve_body( $imei_response ), true );
          $result2['makes']  = empty( $imei_result['makes'] ) ? array() : $imei_result['makes'];

          }
          }
         */

        // Calculate hash for basic authentication
        $hash = sha1($secret_key . $request);

        //echo $request_url;die();
        if (false && 35 == get_current_user_id()) {
            wp_die('api_response ' . print_r($request, 1));
        }
        //$request_url='https://gapi.checkmend.com/duediligence/'.$store_id.'/'.$serials;
        // Send the request to the API
        $response = wp_remote_post($request_url, array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode($partner_id . ':' . $hash),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ),
            'body' => $request,
            'timeout' => 10,
                ));


        // Check response
        if (is_wp_error($response)) {
            $result['error'] = $response->get_error_message();
        } else {
            $api_result = json_decode(wp_remote_retrieve_body($response), true);
            if (!empty($api_result['errors'])) {
                $result['error'] = $api_result['errors'][0]['message'];
            } elseif (!empty($api_result['result'])) {
                $result['result'] = $api_result['result'];
                $result['fmip'] = array();
                $result['makes'] = empty($api_result['makes']) ? array() : $api_result['makes'];
                if (!empty($result['makes'])) {
                    //FMIP status check
                    $fmipapi_url = get_option('gzmgl_retail_fmip_api_url');
                    $fmippartner_id = get_option('gzmgl_retail_fmip_partner_id');
                    $fmipsecret_key = get_option('gzmgl_retail_fmip_secret_key');
                    $fmipstore_id = get_option('gzmgl_retail_fmip_store_id');
                    $fmiprequest_url = trailingslashit($fmipapi_url);
                    $fmiprequest = json_encode(array(
                        'storeid' => $fmipstore_id,
                        'category' => 0,
                        'serials' => $serials,
                        'organisationid' => 1,
                        'fmipstatus' => true,
                            ));
                    $fmiphash = sha1($fmipsecret_key . $fmiprequest);

                    $fmipresponse = wp_remote_post($fmiprequest_url . $fmipstore_id . '/' . $serials[0], array(
                        'headers' => array(
                            'Authorization' => 'Basic ' . base64_encode($fmippartner_id . ':' . $fmiphash),
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ),
                        'body' => $fmiprequest,
                        'timeout' => 10,
                            ));
                    $fmpi_result = json_decode(wp_remote_retrieve_body($fmipresponse), true);
                    $result['fmip'] = array('fmip_response' => $fmpi_result['fmip']);
                    /*
                    * iPhoneox API service
                     */
                    $result['sprint'] = array();
                    $us_sprint_resp = wp_remote_get('http://iphoneox.com/submitOrder?apikey=e2251335-8308-478d-9268-024ab48fa0bc&serviceId=11&imei='.$serials[0].'');
                    $us_sprint = json_decode($us_sprint_resp['body'],true);
                   
                    if($us_sprint['status'] == 'SUCCESS'){
                        $sprint_resp = $us_sprint['response'];
                        $resp_arr = explode('\\n',$sprint_resp);
                        $sprint_status = $resp_arr[count($resp_arr)-1];
                        $sprint_status_arr = explode(':',$sprint_status);                       
                        $status_key = trim(strtolower(str_replace(' ','_', $sprint_status_arr[0])));
                        
                        if($status_key == 'sprint_status'){
                            $result['sprint']['is_sprint'] = 1;
                            $result['sprint']['staus'] = trim($sprint_status_arr[1]);
                        }else{
                            $result['sprint']['is_sprint'] = 0;
                            $result['sprint']['status'] = 'NONE';
                        }
                    }else{
                        $result['sprint']['is_sprint'] = 0;
                        $result['sprint']['status'] = 'FAILED';
                    }
                    
                    if (!empty($imei) && !empty($serials)) {
                        if ($result['makes'][0]['models'] != $result2['makes'][0]['models']) {
                            $result['result'] = 'Not match';
                        }
                    }
                }
                // Save result in a transient
                set_transient($transient_name, $api_result, 1 * HOUR_IN_SECONDS);
            }
        }

        return $result;
    }

}

Gzmgl_Retail_Serial::init();