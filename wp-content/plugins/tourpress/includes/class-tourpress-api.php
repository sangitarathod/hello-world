<?php
/**
* Wrapper class for TourPress REST API Functions
*
* @package     TourPress
* @subpackage  PHP wrapper class for TourPress REST API
* @copyright   Copyright (c) 2016, Explorer Technologies
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

class TourPress_API {

	// General settings
	protected $base_url = "https://api.tourpress.com";
	protected $user_type = 0;
	protected $password = "";
	protected $result_type = "";
	
	// API config
	protected $api = array();
	
	/**
	 * __construct
	 *
	 * @author Peter C Solomon
	 * @param $type User Type
	 * @param $pw Password
	 * @param $res Result type, defaults to raw
	 */
	public function __construct($type, $pw, $res = "raw") {
		$this->user_type = $type;
		$this->password = $pw;
		$this->result_type = $res;
	}
	
	/**
	 * request
	 *
	 * @author Peter C Solomon
	 * @param $path API path to call
	 * @param $channelID Channel ID, defaults to zero
	 * @param $verb HTTP Verb, defaults to GET
	 * @return String or SimpleXML
	 */
	public function request($path, $channelID = 0, $verb = 'GET', $post_data = null) {
		// Prepare the URL we are sending to
		$url = $this->base_url.$path;
		// We need a signature for the header
		
		$outbound_time = time();
		$signature = $this->generate_signature($path, $verb, $channelID, $outbound_time);

		// Build headers
		$headers = array("Content-type: text/xml;charset=\"utf-8\"",
				 "Date: ".gmdate('D, d M Y H:i:s \G\M\T', $outbound_time),
				 "Authorization: TourPress $channelID:$this->user_type:$signature");
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		if($verb == "POST") {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
				if(!is_null($post_data))
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data->asXML());
		}
		
		$response = curl_exec($ch);
		
		$header_size = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
		$result = substr( $response, $header_size );
		
		// Check whether we need to return raw XML or
		// convert to SimpleXML first
		if($this->result_type == "simplexml")
			$result = simplexml_load_string($result);

		
		return($result);
	}
	
	/**
	 * generate_signature
	 *
	 * @author Peter C Solomon
	 * @param $path API Path
	 * @param $verb HTTP Verb
	 * @param $channelID Channel ID
	 * @return String
	 */
	protected function generate_signature($path, $verb, $channelID, $outbound_time) {
		
		$string_to_sign = trim($channelID."/".$this->user_type."/".$verb."/".$outbound_time.$path);
		
		$signature = rawurlencode(base64_encode((hash_hmac("sha256", utf8_encode($string_to_sign), $this->password, TRUE ))));
		
		return $signature;
	}
	
	// # API methods (Housekeeping)
	
	// public function api_rate_limit_status($channelID = 0) {
	// 	return($this->request('/api/rate_limit_status.xml', $channelID));
	// }
	
	// # Channel methods
	
	// public function list_channels() {
	// 	return($this->request('/p/channels/list.xml'));
	// }
	
	// public function show_channel($channelID) {
	// 	return($this->request('/c/channel/show.xml', $channelID));
	// }
	
	// public function channel_performance($channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/channels/performance.xml'));
	// 	else
	// 		return($this->request('/c/channel/performance.xml', $channelID));
	// }
	
	// # Product methods
	
	// public function search_products($params = "", $channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/products/search.xml?'.$params));
	// 	else
	// 		return($this->request('/c/products/search.xml?'.$params, $channelID));		
	// }
	
	// public function search_products_range($params = "", $product = "", $channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/products/search_range.xml?'.$params."&single_product_id=".$product));
	// 	else
	// 		return($this->request('/c/products/search_range.xml?'.$params."&single_product_id=".$product, $channelID));
	// }

	// public function search_products_specific($params = "", $product = "", $channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/products/search_avail.xml?'.$params."&single_product_id=".$product));
	// 	else
	// 		return($this->request('/c/products/search_avail.xml?'.$params."&single_product_id=".$product, $channelID));
	// }
	
	// public function update_product($product_data, $channelID) {
	// 	return($this->request('/c/product/update.xml', $channelID, "POST", $product_data));
	// }
	
	// public function update_product_url($product, $channelID, $product_url) {
	// 	// Create a SimpleXMLElement to hold the new url 
	// 	$url_data = new SimpleXMLElement('<product />'); 
	// 	$url_data->addChild('product_id', $product); 
	// 	$url_data->addChild('product_url', $product_url); 
		
	// 	return($this->update_product($url_data, $channelID));
	// }
	
	// public function list_products($channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/products/list.xml'));
	// 	else
	// 		return($this->request('/c/products/list.xml', $channelID));
	// }
	
	// public function list_product_images($channelID = 0) 
	// {
	// 	if($channelID==0) 
	// 		return($this->request('/p/products/images/list.xml'));
	// 	else
	// 		return($this->request('/c/products/images/list.xml', $channelID));	
	// }
	
	// public function show_product($product, $channelID) 
	// {
	// 	return($this->request('/c/product/show.xml?id='.$product, $channelID));		
	// }
	
	// public function check_product_availability($params, $product, $channelID)
	// {
	// 	return ($this->request('/c/product/datesprices/checkavail.xml?id='.$product."&".$params, $channelID));
	// }
	
	// public function show_product_datesanddeals($product, $channelID, $qs = "")
	// {
	// 	return($this->request('/c/product/datesprices/datesndeals/search.xml?id='.$product.'&'.$qs, $channelID));	
	// }

	
	// public function show_product_departures($product, $channelID)
	// {
	// 	return($this->request('/c/product/datesprices/dep/show.xml?id='.$product, $channelID));	
	// }
	
	// public function show_product_freesale($product, $channelID)
	// {
	// 	return($this->request('/c/product/datesprices/freesale/show.xml?id='.$product, $channelID));	
	// }
	
	// # Booking methods
	
	// /* 
	// 	Making bookings
	// */

	// public function get_booking_redirect_url($url_data, $channelID)
	// {
	// 	return($this->request('/c/booking/new/get_redirect_url.xml', $channelID, "POST", $url_data));
	// }
	
	// public function start_new_booking($booking_data, $channelID)
	// {
	// 	return($this->request('/c/booking/new/start.xml', $channelID, "POST", $booking_data));
	// }
	
	// public function commit_new_booking($booking_data, $channelID)
	// {
	// 	return($this->request('/c/booking/new/commit.xml', $channelID, "POST", $booking_data));
	// }
	
	// /*
	// 	Retrieving bookings
	// */
	
	// public function search_bookings($params = "", $channelID = 0) 
	// {
	// 	if($channelID==0) 
	// 		return($this->request('/p/bookings/search.xml?'.$params));
	// 	else
	// 		return($this->request('/c/bookings/search.xml?'.$params, $channelID));
	// }
	
	// public function show_booking($booking, $channelID) {
	// 	return($this->request('/c/booking/show.xml?booking_id='.$booking, $channelID));
	// }
	
	// /*
	// 	Updating bookings
	// */
	
	// public function update_booking($booking_data, $channelID)
	// {
	// 	return($this->request('/c/booking/update.xml', $channelID, "POST", $booking_data));
	// }
	
	// public function create_payment($payment_data, $channelID)
	// {
	// 	return($this->request('/c/booking/payment/new.xml', $channelID, "POST", $payment_data));
	// }
	
	// # Enquiry and customer methods
	
	// public function create_enquiry($enquiry_data, $channelID)
	// {
	// 	return($this->request('/c/enquiry/new.xml', $channelID, "POST", $enquiry_data));
	// }
	
	// public function update_customer($customer_data, $channelID)
	// {
	// 	return($this->request('/c/customer/update.xml', $channelID, "POST", $customer_data));
	// }
	
	// public function search_enquiries($params = "", $channelID = 0) {
	// 	if($channelID==0) 
	// 		return($this->request('/p/enquiries/search.xml?'.$params));
	// 	else
	// 		return($this->request('/c/enquiries/search.xml?'.$params, $channelID));
	// }
	
	// public function show_enquiry($enquiry, $channelID) {
	// 	return($this->request('/c/enquiry/show.xml?enquiry_id='.$enquiry, $channelID));
	// }
	
	// public function show_customer($customer, $channelID) {
	// 	return($this->request('/c/customer/show.xml?customer_id='.$customer, $channelID));
	// }
	
	// public function check_customer_login($customer, $password, $channelID) {
	// 	return($this->request('/c/customers/login_search.xml?customer_channelID='.$customer.'&customer_password='.$password, $channelID));
	// }
	
	// # Internal supplier methods
	// public function show_supplier($supplier, $channelID) {
	// 	return($this->request('/c/supplier/show.xml?supplier_id='.$supplier, $channelID));
	// }
}

?>