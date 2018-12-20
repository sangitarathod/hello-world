<?php
/**
 * Plugin Name: YITH Woocommerce Request A Quote
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-request-a-quote
 * Version: 1.3.8
 * Author: YITHEMES
 * Author URI: https://yithemes.com/
 * Description: The <code><strong>YITH Woocommerce Request A Quote</strong></code> plugin lets your customers ask for an estimate of a list of products they are interested into. It allows hiding add to cart button so that your customers can request a quote on every product page. <a href="https://yithemes.com/" target="_blank">Find new awesome plugins on <strong>YITH</strong></a>.
 * Text Domain: yith-woocommerce-request-a-quote
 * Domain Path: /languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.0
 *
 */

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


/*
 * @package YITH Woocommerce Request A Quote
 * @since   1.0.3
 * @author  YITH
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! defined( 'YITH_YWRAQ_DIR' ) ) {
    define( 'YITH_YWRAQ_DIR', plugin_dir_path( __FILE__ ) );
}


/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWRAQ_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YITH_YWRAQ_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_YWRAQ_DIR  );


// This version can't be activate if premium version is active  ________________________________________
if ( defined( 'YITH_YWRAQ_PREMIUM' ) ) {
    function yith_ywraq_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'You can\'t activate the free version of YITH Woocommerce Request A Quote while you are using the premium one.', 'yith-woocommerce-request-a-quote' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'yith_ywraq_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}

// Registration hook  ________________________________________
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


// Define constants ________________________________________
if ( defined( 'YITH_YWRAQ_VERSION' ) ) {
    return;
}else{
    define( 'YITH_YWRAQ_VERSION', '1.3.8' );
}

if ( ! defined( 'YITH_YWRAQ_FREE_INIT' ) ) {
    define( 'YITH_YWRAQ_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWRAQ_INIT' ) ) {
    define( 'YITH_YWRAQ_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWRAQ_FILE' ) ) {
    define( 'YITH_YWRAQ_FILE', __FILE__ );
}

if ( ! defined( 'YITH_YWRAQ_DIR' ) ) {
    define( 'YITH_YWRAQ_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_YWRAQ_URL' ) ) {
    define( 'YITH_YWRAQ_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_YWRAQ_ASSETS_URL' ) ) {
    define( 'YITH_YWRAQ_ASSETS_URL', YITH_YWRAQ_URL . 'assets' );
}

if ( ! defined( 'YITH_YWRAQ_TEMPLATE_PATH' ) ) {
    define( 'YITH_YWRAQ_TEMPLATE_PATH', YITH_YWRAQ_DIR . 'templates' );
}

if ( ! defined( 'YITH_YWRAQ_INC' ) ) {
    define( 'YITH_YWRAQ_INC', YITH_YWRAQ_DIR . '/includes/' );
}

if( ! function_exists('yith_ywraq_install_woocommerce_admin_notice') ){
    function yith_ywraq_install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH Woocommerce Request A Quote is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-request-a-quote' ); ?></p>
        </div>
        <?php
    }
}

function yith_ywraq_constructor() {

    // Woocommerce installation check _________________________
    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'yith_ywraq_install_woocommerce_admin_notice' );
        return;
    }

    // Load YWCM text domain ___________________________________
    load_plugin_textdomain( 'yith-woocommerce-request-a-quote', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    // Load required classes and functions

    if( ! class_exists('WC_Session') ){
        include_once( WC()->plugin_path().'/includes/abstracts/abstract-wc-session.php');
    }

    require_once( YITH_YWRAQ_INC . 'functions.yith-request-quote.php' );
    require_once( YITH_YWRAQ_INC . 'class.yith-ywraq-session.php' );
    require_once( YITH_YWRAQ_INC . 'class.yith-ywraq-shortcodes.php' );

    require_once( YITH_YWRAQ_INC . 'class.yith-request-quote.php' );
    if ( is_admin() ) {
        require_once( YITH_YWRAQ_INC . 'class.yith-request-quote-admin.php' );
    } else {
        require_once( YITH_YWRAQ_INC . 'class.yith-request-quote-frontend.php' );
        YITH_YWRAQ_Frontend();
    }

   YITH_Request_Quote();

}
add_action( 'plugins_loaded', 'yith_ywraq_constructor' );