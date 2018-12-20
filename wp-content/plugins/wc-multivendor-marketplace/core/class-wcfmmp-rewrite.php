<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Rewrite
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Rewrites {

	public $query_vars = array();
	public $wcfm_store_url = '';

	/**
	 * Hook into the functions
	 */
	public function __construct() {
			$this->wcfm_store_url = get_option( 'wcfm_store_url', 'store' );

			add_action( 'init', array( $this, 'register_rule' ) );

			add_filter( 'template_include', array( $this, 'store_template' ) );

			add_filter( 'query_vars', array( $this, 'register_query_var' ) );
			add_filter( 'pre_get_posts', array( $this, 'store_query_filter' ) );
			add_filter( 'woocommerce_get_breadcrumb', array( $this, 'store_page_breadcrumb'), 10 ,1  );
	}


	/**
	 * Initializes the WCFMmp_Rewrites() class
	 *
	 * @since 1.0.0
	 *
	 * Checks for an existing WCFMmp_Rewrites() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new WCFMmp_Rewrites();
		}

		return $instance;
	}

	/**
	 * Generate breadcrumb for store page
	 *
	 * @since 1.0.0
	 *
	 * @param array $crumbs
	 *
	 * @return array $crumbs
	 */
	public function store_page_breadcrumb( $crumbs ){
		if (  wcfm_is_store_page() ) {
			$author      = get_query_var( $this->wcfm_store_url );
			$seller_info = get_user_by( 'slug', $author );
			$crumbs[1]   = array( ucwords($this->wcfm_store_url) , site_url().'/'.$this->wcfm_store_url );
			$crumbs[2]   = array( $author, wcfmmp_get_store_url( $seller_info->data->ID ) );
		}

		return $crumbs;
	}

	/**
	 * Register the rewrite rule
	 *
	 * @return void
	 */
	function register_rule() {
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/category/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&term_section=true', 'top' );
    add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/category/?([0-9]{1,})/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&paged=$matches[3]&term_section=true', 'top' );

		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/about?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&about=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/policies?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&policies=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/reviews?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&reviews=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/reviews/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&reviews=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/followers?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&followers=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/followers/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&followers=true', 'top' );
		
		do_action( 'wcfmmp_rewrite_rules_loaded', $this->wcfm_store_url );
	}
	
	/**
	 * Register the query var
	 *
	 * @param array  $vars
	 *
	 * @return array
	 */
	function register_query_var( $vars ) {
		$vars[] = $this->wcfm_store_url;
		$vars[] = 'term_section';
		$vars[] = 'about';
		$vars[] = 'policies';
		$vars[] = 'reviews';
		$vars[] = 'followers';
		
		foreach ( $this->query_vars as $var ) {
			$vars[] = $var;
		}

		return $vars;
	}

	/**
	 * Include store template
	 *
	 * @param type  $template
	 *
	 * @return string
	 */
	function store_template( $template ) {
		global $WCFM, $WCFMmp;
		
		$store_name = get_query_var( $this->wcfm_store_url );
		
		if ( !WCFMmp_Dependencies::woocommerce_plugin_active_check() ) {
			return $template;
		}

		if ( !empty( $store_name ) ) {
			$store_user = get_user_by( 'slug', $store_name );
			
			// no user found
			if ( ! $store_user ) {
				return get_404_template();
			}

			// check if the user is seller
			if ( ! wcfm_is_vendor( $store_user->ID ) ) {
				return get_404_template();
			}

			if ( get_query_var( 'about' ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'about' ) );
			} elseif ( get_query_var( 'policies' ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'policies' ) );
			} elseif ( get_query_var( 'reviews' ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'reviews' ) );
			} elseif ( get_query_var( 'followers' ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'followers' ) );
			} else {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'products' ) );
			}
		}

		return $template;
	}

	/**
	 * Store query filter
	 *
	 * Handles the product filtering by category in store page
	 *
	 * @param object  $query
	 *
	 * @return void
	 */
	function store_query_filter( $query ) {
			global $wp_query;

			$author = get_query_var( $this->wcfm_store_url );

			if ( !is_admin() && $query->is_main_query() && !empty( $author ) ) {
					$seller_info  = get_user_by( 'slug', $author );
					$store_info   = wcfmmp_get_store_info( $seller_info->data->ID );
					$post_per_page = isset( $store_info['store_ppp'] ) && !empty( $store_info['store_ppp'] ) ? $store_info['store_ppp'] : get_option('posts_per_page');
					set_query_var( 'posts_per_page', $post_per_page );
					$query->set( 'post_type', 'product' );
					$query->set( 'author_name', $author );
					$query->query['term_section'] = isset( $query->query['term_section'] ) ? $query->query['term_section'] : array();

					if ( $query->query['term_section'] ) {
							$query->set( 'tax_query',
									array(
											array(
													'taxonomy' => 'product_cat',
													'field'    => 'term_id',
													'terms'    => $query->query['term']
											)
									)
							);
					}
			}
	}
}
