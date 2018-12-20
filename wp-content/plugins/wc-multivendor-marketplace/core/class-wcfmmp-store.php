<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Store
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
class WCFMmp_Store {

	/**
	 * The store ID
	 *
	 * @var integer
	 */
	public $id = 0;

	/**
	 * Holds the user data object
	 *
	 * @var null|WP_User
	 */
	public $data = null;

	/**
	 * Holds the store info
	 *
	 * @var array
	 */
	private $shop_data = array();

	/**
	 * The constructor
	 *
	 * @param int|WP_User $vendor
	 */
	public function __construct( $vendor = null ) {
		if ( is_numeric( $vendor ) ) {
			$the_user = get_user_by( 'id', $vendor );

			if ( $the_user ) {
				$this->id   = $the_user->ID;
				$this->data = $the_user;
			}

		} elseif ( is_a( $vendor, 'WP_User' ) ) {
			$this->id   = $vendor->ID;
			$this->data = $vendor;
		}
	}

	/**
	 * Call undefined functions callback
	 *
	 * @param string $name
	 * @param [type] $param [description]
	 *
	 * @return [type] [description]
	 */
	public function __call( $name, $param ) {
		if ( strpos( $name, 'get_' ) === 0 ) {
			$function_name  = str_replace('get_', '', $name );
			return ! empty( $this->shop_data[$function_name] ) ? $this->shop_data[$function_name] : null;
		}
	}

	/**
	 * Store info to array
	 *
	 * @return array
	 */
	public function to_array() {

		$info = array(
				'id'                    => $this->get_id(),
				'store_name'            => $this->get_shop_name(),
				'about'                 => $this->get_shop_description(),
				'first_name'            => $this->get_first_name(),
				'last_name'             => $this->get_last_name(),
				'email'                 => $this->get_email(),
				'social'                => $this->get_social_profiles(),
				'phone'                 => $this->get_phone(),
				'show_email'            => $this->show_email(),
				'address'               => $this->get_address(),
				'address_string'        => $this->get_address_string(),
				'location'              => $this->get_location(),
				'banner'                => $this->get_banner(),
				'gravatar'              => $this->get_avatar(),
				'shop_url'              => $this->get_shop_url(),
				'products_per_page'     => $this->get_per_page(),
				'show_more_product_tab' => $this->show_more_products_tab(),
				'registered'            => $this->get_register_date(),
				'store_tabs'            => $this->get_store_tabs()
		);

		return $info;
	}

	/**
	 * Check if key is exist
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public function get_value( $key ) {
		return ! empty( $key ) ? $key : '';
	}

	/**
	 * Check if the user is vendor
	 *
	 * @return boolean
	 */
	public function is_vendor() {
		return wcfm_is_vendor( $this->id );
	}

	/**
	 * Get vendor's store tabs
	 *
	 * @return array
	 */
	public function get_store_tabs() {
		global $WCFM, $WCFMmp;
		$store_tabs = apply_filters( 'wcfmmp_store_tabs', array(
																					"products"  => __( 'Products', 'wc-multivendor-marketplace' ),
																					"about"     => __( 'About', 'wc-multivendor-marketplace' ),
																					"policies"  => __( 'Policies', 'wc-multivendor-marketplace' ),
																					"reviews"   => __( 'Reviews', 'wc-multivendor-marketplace' ),
																					"followers" => __( 'Followers', 'wc-multivendor-marketplace' ),
																					), $this->id );
		
		if( !WCFM_Dependencies::wcfmu_plugin_active_check() || !apply_filters( 'wcfm_is_pref_vendor_followers', true ) ) {
			if( isset( $store_tabs['followers'] ) ) unset( $store_tabs['followers'] );
		} else {
			$followers = $this->get_total_follower_count();
			$store_tabs['followers'] = __( 'Followers', 'wc-multivendor-marketplace' ) . ' (<span class="wcfm_followers_count">'.$followers.'</span>)';
		}
		
		if( !$WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $this->id, 'store_description' ) ) {
			if( isset( $store_tabs['about'] ) ) unset( $store_tabs['about'] );
		}
		
		if( !apply_filters( 'wcfm_is_pref_policies', true ) || !$WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $this->id, 'vendor_policy' ) ) {
			if( isset( $store_tabs['policies'] ) ) unset( $store_tabs['policies'] );
		}
		
		if( !apply_filters( 'wcfm_is_pref_vendor_reviews', true ) ) {
			//$WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $this->id, 'review_manage' )
			if( isset( $store_tabs['reviews'] ) ) unset( $store_tabs['reviews'] );
		} else {
			$reviews = $this->get_total_review_count();
			$store_tabs['reviews'] = __( 'Reviews', 'wc-multivendor-marketplace' ) . ' (<span class="wcfm_reviews_count">'.$reviews.'</span>)';
		}
		
		return $store_tabs;
	}
	
	/**
	 * Populate store info
	 *
	 * @return void
	 */
	public function popluate_store_data() {
		$defaults = array(
				'store_name'              => '',
				'social'                  => array(),
				'payment'                 => array( 'paypal' => array( 'email' ), 'bank' => array() ),
				'phone'                   => '',
				'show_email'              => 'no',
				'address'                 => array(),
				'location'                => '',
				'store_lat'               => 0,
				'store_lng'               => 0,
				'banner'                  => 0,
				'icon'                    => 0,
				'gravatar'                => 0,
				'store_ppp'               => 10,
				'store_seo'               => array(),
				'customer_support'        => array()
		);

		if ( ! $this->id ) {
				$this->shop_data = $defaults;
				return;
		}

		$shop_info = get_user_meta( $this->id, 'wcfmmp_profile_settings', true );
		$shop_info = is_array( $shop_info ) ? $shop_info : array();
		$shop_info = wp_parse_args( $shop_info, $defaults );

		$this->shop_data = $shop_info;
	}

	/**
	 * Get the store info by lazyloading
	 *
	 * @return array
	 */
	public function get_shop_info() {

		// return if already populated
		if ( $this->shop_data ) {
				return $this->shop_data;
		}

		$this->popluate_store_data();

		return $this->shop_data;
	}

	/**
	 * Get store info by key
	 *
	 * @param  string $item
	 *
	 * @return mixed
	 */
	public function get_info_part( $item ) {
		$info = $this->get_shop_info();

		if ( array_key_exists( $item, $info ) ) {
			return $info[ $item ];
		}
	}

	/**
	 * Get store ID
	 *
	 * @return void
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the vendor name
	 *
	 * @return string
	 */
	public function get_name() {
		if ( $this->id ) {
			return $this->get_value( $this->data->display_name );
		}
	}

	/**
	 * Get the shop name
	 *
	 * @return string
	 */
	public function get_shop_name() {
		return $this->get_info_part( 'store_name' );
	}
	
	/**
	 * Get shop description
	 *
	 */
	function get_shop_description() {
		return get_user_meta( $this->id, '_store_description', true );
	}

	/**
	 * Get the shop URL
	 *
	 * @return string
	 */
	public function get_shop_url() {
		return wcfmmp_get_store_url( $this->id );
	}
	
	/**
	 * Get the about URL
	 *
	 * @return string
	 */
	public function get_about_url() {
		return $this->get_shop_url() . 'about';
	}
	
	/**
	 * Get the policies URL
	 *
	 * @return string
	 */
	public function get_policies_url() {
		return $this->get_shop_url() . 'policies';
	}
	
	/**
	 * Get the reviews URL
	 *
	 * @return string
	 */
	public function get_reviews_url() {
		return $this->get_shop_url() . 'reviews';
	}
	
	/**
	 * Get the followers URL
	 *
	 * @return string
	 */
	public function get_followers_url() {
		return $this->get_shop_url() . 'followers';
	}
	
	/**
	 * Get the store tabs URL
	 *
	 * @return string
	 */
	public function get_store_tabs_url( $tab = '' ) {
		$store_tab_url = $this->get_shop_url();
		
		switch( $tab ) {
			case 'about':
				$store_tab_url = $this->get_about_url();
			break;
			
			case 'policies':
				$store_tab_url = $this->get_policies_url();
			break;
			
			case 'reviews':
				$store_tab_url = $this->get_reviews_url();
			break;
			
			case 'followers':
				$store_tab_url = $this->get_followers_url();
			break;
			
			default:
				$store_tab_url = $this->get_shop_url();
			break;
		}
		
		return $store_tab_url;
	}

	/**
	 * Get email address
	 *
	 * @return string
	 */
	public function get_email() {
		if ( $this->id ) {
			return $this->get_value( $this->data->user_email );
		}
	}

	/**
	 * Get first name
	 *
	 * @return string
	 */
	public function get_first_name() {
		if ( $this->id ) {
			return $this->get_value( $this->data->first_name );
		}
	}

	/**
	 * Get last name
	 *
	 * @return string
	 */
	public function get_last_name() {
		if ( $this->id ) {
			return $this->get_value( $this->data->last_name );
		}
	}

	/**
	 * Get last name
	 *
	 * @return string
	 */
	public function get_register_date() {
		if ( $this->id ) {
			return $this->get_value( $this->data->user_registered );
		}
	}

	/**
	 * Get the shop name
	 *
	 * @return array
	 */
	public function get_social_profiles() {
		return $this->get_info_part( 'social' );
	}

	/**
	 * Get the phone name
	 *
	 * @return string
	 */
	public function get_phone() {
		return $this->get_info_part( 'phone' );
	}

	/**
	 * Get the shop address
	 *
	 * @return array
	 */
	public function get_address() {
		return $this->get_info_part( 'address' );
	}
	
	/**
	 * Get the shop address
	 *
	 * @return array
	 */
	public function get_address_string() {
		$vendor_data = $this->shop_data;
		$address         = isset( $vendor_data['address'] ) ? $vendor_data['address'] : '';
		$addr_1 = isset( $vendor_data['address']['street_1'] ) ? $vendor_data['address']['street_1'] : '';
		$addr_2 = isset( $vendor_data['address']['street_2'] ) ? $vendor_data['address']['street_2'] : '';
		$city    = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : '';
		$zip     = isset( $vendor_data['address']['zip'] ) ? $vendor_data['address']['zip'] : '';
		$country = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : '';
		$state   = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : '';
		
		// Country -> States
		$country_obj   = new WC_Countries();
		$countries     = $country_obj->countries;
		$states        = $country_obj->states;
		$country_name = '';
		$state_name = '';
		if( $country ) $country_name = $country;
		if( $state ) $state_name = $state;
		if( $country && isset( $countries[$country] ) ) {
			$country_name = $countries[$country];
		}
		if( $state && isset( $states[$country] ) && is_array( $states[$country] ) ) {
			$state_name = isset($states[$country][$state]) ? $states[$country][$state] : '';
		}
		
		$store_address = '';
		if( $addr_1 ) $store_address .= $addr_1;
		if( $addr_2 ) $store_address .= ", " . $addr_2;
		if( $city ) $store_address .= ", " . $city;
		if( $state_name ) $store_address .= ", " . $state_name;
		if( $country_name ) $store_address .= " " . $country_name;
		if( $zip ) $store_address .= " - " . $zip;
	
		return $store_address;
		
	}
	
	/**
	 * Get the customer support details
	 *
	 * @return array
	 */
	function get_customer_support_details() {
		$this->get_shop_info();
		$vendor_data = $this->shop_data;
		$phone = isset( $vendor_data['customer_support']['phone'] ) ? $vendor_data['customer_support']['phone'] : '';
		$email = isset( $vendor_data['customer_support']['email'] ) ? $vendor_data['customer_support']['email'] : '';
		$addr_1 = isset( $vendor_data['customer_support']['address1'] ) ? $vendor_data['customer_support']['address1'] : '';
		$addr_2 = isset( $vendor_data['customer_support']['address2'] ) ? $vendor_data['customer_support']['address2'] : '';
		$country = isset( $vendor_data['customer_support']['country'] ) ? $vendor_data['customer_support']['country'] : '';
		$city = isset( $vendor_data['customer_support']['city'] ) ? $vendor_data['customer_support']['city'] : '';
		$state = isset( $vendor_data['customer_support']['state'] ) ? $vendor_data['customer_support']['state'] : '';
		$zip = isset( $vendor_data['customer_support']['zip'] ) ? $vendor_data['customer_support']['zip'] : '';
		
		// Country -> States
		$country_obj   = new WC_Countries();
		$countries     = $country_obj->countries;
		$states        = $country_obj->states;
		$country_name = '';
		$state_name = '';
		if( $country ) $country_name = $country;
		if( $state ) $state_name = $state;
		if( $country && isset( $countries[$country] ) ) {
			$country_name = $countries[$country];
		}
		if( $state && isset( $states[$country] ) && is_array( $states[$country] ) ) {
			$state_name = isset($states[$country][$state]) ? $states[$country][$state] : '';
		}
		
		$customer_support_details = '';
		if( $addr_1 ) $customer_support_details .= $addr_1;
		if( $addr_2 ) $customer_support_details .= ", " . $addr_2;
		if( $city ) $customer_support_details .= ", " . $city;
		if( $state_name ) $customer_support_details .= ", " . $state_name;
		if( $country_name ) $customer_support_details .= " " . $country_name;
		if( $zip ) $customer_support_details .= " - " . $zip;
		
		if( $email ) $customer_support_details .= "<br/>" . __( 'Email', 'wc-multivendor-marketplace' ) . ': ' . $email;
		if( $phone ) $customer_support_details .= "<br/>" . __( 'Phone', 'wc-multivendor-marketplace' ) . ': ' . $phone;
		
		return $customer_support_details;
	}

	/**
	 * Get the shop location
	 *
	 * @return array
	 */
	public function get_location() {
		$default  = array( 'lat' => 0, 'long' => 0 );
		$location = $this->get_info_part( 'location' );

		if ( $location ) {
			list( $default['lat'], $default['long'] ) = explode( ',', $location );
		}

		return $location;
	}

	/**
	 * Get the shop banner
	 *
	 * @return string
	 */
	public function get_banner() {
		$banner_id = (int) $this->get_info_part( 'banner' );

		if ( ! $banner_id ) {
			return false;
		}

		return wp_get_attachment_url( $banner_id );
	}

	/**
	 * Get the shop profile icon
	 *
	 * @return string
	 */
	public function get_avatar() {
		global $WCFM, $WCFMmp;
		$avatar_id = (int) $this->get_info_part( 'gravatar' );

		if ( ! $avatar_id && ! empty( $this->data->user_email ) ) {
			return $WCFM->plugin_url . 'assets/images/avatar.png'; //get_avatar_url( $this->data->user_email, 96 );
		}

		return wp_get_attachment_url( $avatar_id );
	}
	
	/**
	 * Get the store policies
	 *
	 * @return string
	 */
	public function get_store_policies() {
		return (array) get_user_meta( $this->id, 'wcfm_policy_vendor_options', true );
	}

	/**
	 * Get per page pagination
	 *
	 * @return integer
	 */
	public function get_per_page() {
		$per_page = (int) $this->get_info_part( 'store_ppp' );

		if ( ! $per_page ) {
			return get_option('posts_per_page');
		}

		return $per_page;
	}
	
	/**
	 * Get the store taxonomies
	 *
	 * @return array
	 */
	public function get_store_taxonomies( $taxonomy = 'product_cats' ) {
		$vendor_taxonomies = get_user_meta( $this->get_id(), '_wcfm_store_'.$taxonomy, true );
		if( !$vendor_taxonomies ) $vendor_taxonomies = array();
		return $vendor_taxonomies;
	}
	
	/**
	 * Get total follower count
	 *
	 * @return integer
	 */
	public function get_total_follower_count() {
		$followers = 0;
		$followers_arr = get_user_meta( $this->get_id(), '_wcfm_followers_list', true );
		if( $followers_arr && is_array( $followers_arr ) ) {
			$followers = count( $followers_arr );
		}
		return $followers;
	}

	/**
	 * Get total review count
	 *
	 * @return integer
	 */
	public function get_total_review_count() {
		global $WCFMmp;
		
		// Reset Review User Meta
		$total_review_count = $WCFMmp->wcfmmp_reviews->get_vendor_reviews_count( $this->get_id() );
		if( !$total_review_count ) {
			delete_user_meta( $this->get_id(), '_wcfmmp_total_review_count' );
			delete_user_meta( $this->get_id(), '_wcfmmp_total_review_rating' );
			delete_user_meta( $this->get_id(), '_wcfmmp_avg_review_rating' );
			delete_user_meta( $this->get_id(), '_wcfmmp_category_review_rating' );
			delete_user_meta( $this->get_id(), '_wcfmmp_last_author_id' );
			delete_user_meta( $this->get_id(), '_wcfmmp_last_author_name' );
		}
		
		//$total_review_count = get_user_meta( $this->get_id(), '_wcfmmp_total_review_count', true );
		//if( !$total_review_count ) $total_review_count = 0;
		//else $total_review_count = absint( $total_review_count );
		return $total_review_count;
	}
	
	/**
	 * Get total review rating
	 *
	 * @return integer
	 */
	public function get_total_review_rating() {
		$total_review_rating = get_user_meta( $this->get_id(), '_wcfmmp_total_review_rating', true );
		if( !$total_review_rating ) $total_review_rating = 0;
		else $total_review_rating = (float) $total_review_rating;
		return $total_review_rating;
	}
	
	/**
	 * Get avarage review rating
	 *
	 * @return integer
	 */
	public function get_avg_review_rating() {
		$avg_review_rating = get_user_meta( $this->get_id(), '_wcfmmp_avg_review_rating', true );
		if( !$avg_review_rating ) $total_review_rating = 0;
		else $avg_review_rating = round( $avg_review_rating, 2 );
		return $avg_review_rating;
	}
	
	/**
	 * Get category review rating
	 *
	 * @return integer
	 */
	public function get_category_review_rating() {
		$category_review_rating = get_user_meta( $this->get_id(), '_wcfmmp_category_review_rating', true );
		return $category_review_rating;
	}
	
	/**
	 * Get last review author ID
	 *
	 * @return integer
	 */
	public function get_last_review_author_id() {
		$last_author_id = get_user_meta( $this->get_id(), '_wcfmmp_last_author_id', true );
		if( !$last_author_id ) $last_author_id = 0;
		else $last_author_id = absint( $last_author_id );
		return $last_author_id;
	}
	
	/**
	 * Get last review author name
	 *
	 * @return integer
	 */
	public function get_last_review_author_name() {
		$last_author_name = get_user_meta( $this->get_id(), '_wcfmmp_last_author_name', true );
		return $last_author_name;
	}
	
	/**
	 * Get lastest reviews
	 *
	 * @return integer
	 */
	public function get_lastest_reviews( $offset = 0, $length = 5 ) {
		global $WCFM, $wpdb;
		
		$sql = 'SELECT *  FROM ' . $wpdb->prefix . 'wcfm_marketplace_reviews';
		$sql .= ' WHERE 1=1';
		$sql .= ' AND `approved` = 1';
		$sql .= " AND `vendor_id` = " . $this->get_id();
		$sql .= " ORDER BY `ID` DESC";
		$sql .= " LIMIT {$length}";
		$sql .= " OFFSET {$offset}";
		$reviews = $wpdb->get_results( $sql );
		return $reviews;
	}
	
	/**
	 * Show store start rating
	 *
	 * @return integer
	 */
	public function show_star_rating() {
		global $WCFM, $WCFMmp;
		$WCFMmp->wcfmmp_reviews->show_star_rating( 0, $this->id );
	}
	
}