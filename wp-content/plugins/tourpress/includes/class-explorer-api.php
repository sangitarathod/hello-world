<?php
/**
* Explorer API
*
* @package     TourPress
* @subpackage  Explorer API
* @copyright   Copyright (c) 2016, Explorer Technologies
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/
class Explorer_API {

	private $soapClient;
	private $sessionID;

	/**
	 * Constructor for the api class. Create the SOAP Client
	 *
	 * @access public
	 */
	public function __construct () {	
		$this->soapClient = $this->get_client();
	}
	
	// Get SOAP Client
	private function get_client() {

		$url = get_option('tourpress_service_url');
		if ( substr( $url, 0 , 7 ) !== 'http://' ) $url = 'http://' . $url;	// Prepend http:// 

		$url .= '/jadehttp.dll?TourPress&serviceName=ExplorerWebV3&wsdl=wsdl';
		$prev_value_soap = libxml_disable_entity_loader(false);
		$client	= new SoapClient($url, 
						array(	'soap_version'	=> SOAP_1_2, 
								'trace' 		=> true, 
								'exceptions' 	=> false,
								'connection_timeout' => 600,
								/*'keep_alive' => false,*/
								'cache_wsdl' 	=> WSDL_CACHE_BOTH, // NONE, DISK, MEMORY or BOTH
								'features'		=> SOAP_SINGLE_ELEMENT_ARRAYS ) );

		if(defined('RESPONSE_TIMEOUT') &&  RESPONSE_TIMEOUT != '') {
			 ini_set('default_socket_timeout', RESPONSE_TIMEOUT);
		}
		libxml_disable_entity_loader($prev_value_soap);
		return $client;
	}

	// Get Last Request
	public function get_last_request() {
		
		$request = $this->soapClient->__getLastRequest();
		
		return $request;
	}
	
	// Get Last Response
	public function get_last_response() {
		
		$response = $this->soapClient->__getLastResponse();
		
		return $response;
	}

	// Return true if result contains the decsriptionExtra element
	public function error( $result ) {
		if (is_soap_fault( $result )
		//|| (is_object($result) && 
		|| property_exists( $result, 'descriptionExtra' ) )
			return true;
	}

	// Ping
	public function ping( $text ) {
	
		$result = $this->soapClient->ping( $text );
		
		return $result;
	}

	// Authenticate
	public function authenticate( $referralAgent ) {
			
		$name = get_option('tourpress_channelID');
		$password = get_option('tourpress_password');

		$params = array('name' 			=> $name,
						'password'		=> $password,
						'referralAgent' => $referralAgent,
						'serviceType'	=> 0);

		// #TODO Get transient session ID
		//$sessionID = get_transient( 'sessionID' );
		if( empty( $sessionID ) ) {
			$result = $this->soapClient->authenticate( new SoapVar ($params, SOAP_ENC_OBJECT) );
		    $sessionID = $result->id;
		    set_transient( 'sessionID', $sessionID, HOUR_IN_SECONDS );
		    //var_dump($sessionID);

		}

		// Scope the Session ID within this API class
		$this->sessionID = $sessionID;

		// $result = $this->soapClient->authenticate( new SoapVar ($params, SOAP_ENC_OBJECT) );
		
		// // Scope the Session ID within this API class
		// $this->sessionID = $result->id;		
		
		return $this->sessionID;

	}

	// Login
	public function login( $userID, $username, $password, $userType ) {
		
		$params = array('sessionID' 	=> $this->sessionID,
						'id'			=> $userID,
						'userName'		=> $username,
						'userPassword'	=> $password,
						'userType' 		=> $userType);

		$result = $this->soapClient->login( new SoapVar ($params, SOAP_ENC_OBJECT) );	
		
		return $result;
	}

	// Get Currencies
	public function get_currencies( $customerID, $isDirect ) {

		$params = array('sessionID'			=> $this->sessionID,
						'customerID'		=> $customerID,
						'isDefaultDirect'	=> $isDirect);

		$result = $this->soapClient->getSellCurrencies_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}

	// Get Product Types
	public function get_productTypes( $productType = null, $isChildren = null, $isFacilities = false, $isClasses = false, $isLocations = false ) {

		$params = array('sessionID'		=> $this->sessionID,
						'productType'	=> $productType,
						'isChildren'	=> $isChildren,
						'isFacilities'	=> $isFacilities,
						'isClasses'		=> $isClasses,
						'isLocactions'	=> $isLocations);

		$result = $this->soapClient->getProductTypes_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}
	
	// Get Locations
	public function get_locations( $locationId = null, $isChildren = null ) {

		$params = array('sessionID'		=> $this->sessionID,
						'locationId'	=> $locationId,
						'isChildren'	=> $isChildren);

		$result = $this->soapClient->getLocations_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}
	
	// Get Product
	public function get_product( $productID, $isRates = false, $ratesFrom = null, $ratesTo = null ) {

		$params = array('sessionID'	=> $this->sessionID,
						'productID'	=> $productID,
						'isRates'	=> $isRates,
						'ratesFrom'	=> $ratesFrom,
						'ratesTo'	=> $ratesTo);

		$result = $this->soapClient->getProductInformation_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}

	// Get Products (Summary) - Search Results!!!
	public function get_productsSummary( $productType, $origin = null, $destination = null, $name = null, $productID = null, $dateIn = null, $dateOut = null, $paxTypes = null, $paxAges = null, $paxUnitRooms = null ) {

		$params = array('sessionID'		=> $this->sessionID,
						'productTypes'	=> array ( $productType ),	// Just one for now
						'origin'		=> $origin,
						'destination'	=> $destination,
						'productID'		=> $productID,
						'serviceName'	=> $name,
						'dateIn'		=> $dateIn,
						'dateOut'		=> $dateOut,
						'paxTypes'		=> $paxTypes, //array ( 'Adult', 'Adult' ),
						'paxAges'		=> $paxAges, //array ( 0, 0 ),
						'paxUnitRooms'	=> $paxUnitRooms //array ( 1, 1 )
						);

		$result = $this->soapClient->getProductsSummary_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}

	// Get Products (Detailed)
	public function get_products( $productType = null, $location = null, $name = null, $productIDs = null,
						$amendedFrom = null, $isChildren = true, $isDetailed = true, $isFromPrice = true, 
						$isImages = true, $isText = true, $isFacilities = true, $isLocations = false,
						$isPolicies = true, $isFeatured = false, $isPreferred = false, $isSpecials = false,
						$isRates = false, $ratesFrom = null, $ratesTo = null ) {

		$params = array('sessionID'		=> $this->sessionID,
						'productType'	=> $productType,
						'location'		=> $location,
						'serviceName'	=> $name,
						'productIDs'	=> $productIDs,						
						'amendedFrom'	=> $amendedFrom,
						'isChildren'	=> $isChildren,
						'isDetailed'	=> $isDetailed,
						'isFromPrice'	=> $isFromPrice,
						'isImages'		=> $isImages,
						'isText'		=> $isText,
						'isFacilities'	=> $isFacilities,
						'isLocations'	=> $isLocations,
						'isPolicies'	=> $isPolicies,
						'isFeatured'	=> $isFeatured,
						'isPreferred'	=> $isPreferred,
						'isSpecials'	=> $isSpecials,
						'isRates'		=> $isRates,
						'ratesFrom'		=> $ratesFrom,
						'ratesTo'		=> $ratesTo);

		$result = $this->soapClient->getProducts_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}

	// Get Product List
	public function get_productList( $productType = null, $location = null, $name = null, $productIDs = null,
						$amendedFrom = null, $isChildren = false, $isDetailed = false, $isFromPrice = false, 
						$isImages = false, $isText = false, $isFacilities = false, $isLocations = false,
						$isPolicies = false, $isFeatured = false, $isPreferred = false, $isSpecials = false,
						$isRates = false, $ratesFrom = null, $ratesTo = null ) {

		$params = array('sessionID'		=> $this->sessionID,
						'productType'	=> $productType,
						'location'		=> $location,
						'serviceName'	=> $name,
						'productIDs'	=> $productIDs,						
						'amendedFrom'	=> $amendedFrom,
						'isChildren'	=> $isChildren,
						'isDetailed'	=> $isDetailed,
						'isFromPrice'	=> $isFromPrice,
						'isImages'		=> $isImages,
						'isText'		=> $isText,
						'isFacilities'	=> $isFacilities,
						'isLocations'	=> $isLocations,
						'isPolicies'	=> $isPolicies,
						'isFeatured'	=> $isFeatured,
						'isPreferred'	=> $isPreferred,
						'isSpecials'	=> $isSpecials,
						'isRates'		=> $isRates,
						'ratesFrom'		=> $ratesFrom,
						'ratesTo'		=> $ratesTo);

		$result = $this->soapClient->getProducts_Web_By_Name( new SoapVar ($params, SOAP_ENC_OBJECT) );	

		return $result;
	}

	// Get Booking
	public function get_booking( $bookingID ) {

		$params = array('sessionID'	=> $this->sessionID,
						'bookingID'	=> $bookingID);

		$result = $this->soapClient->getBooking_Web( new SoapVar ($params, SOAP_ENC_OBJECT) );		
		
		return $result;
	}	

}