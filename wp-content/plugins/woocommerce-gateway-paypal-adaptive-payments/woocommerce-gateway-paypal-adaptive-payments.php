<?php
/**
 * Plugin Name: WooCommerce PayPal Adaptive Payments
 * Plugin URI: https://woocommerce.com/products/paypal-adaptive-payments/
 * Description: PayPal Adaptive Payments integration for WooCommerce
 * Version: 1.1.11
 * Author: WooCommerce
 * Author URI: https://woocommerce.com
 * Text Domain: woocommerce-gateway-paypal-adaptive-payments
 * Domain Path: /languages
 * WC tested up to: 3.7
 * WC requires at least: 2.6
 * Tested up to: 5.0
 *
 * @package  WC_PayPal_Adaptive_Payments
 * @category Core
 * Woo: 442373:307e9af43581a11b4b6cb05525e2dd65
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '307e9af43581a11b4b6cb05525e2dd65', '442373' );

if ( ! class_exists( 'WC_PayPal_Adaptive_Payments' ) ) :

/**
 * WooCommerce PayPal Adaptive Payments main class.
 */
class WC_PayPal_Adaptive_Payments {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.1.11';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin public actions.
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add custom links on plugin action links.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

		// Checks with WooCommerce is installed.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			$this->includes();

			if ( is_admin() ) {
				$this->admin_includes();
			}

			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-gateway-paypal-adaptive-payments' );

		load_textdomain( 'woocommerce-gateway-paypal-adaptive-payments', trailingslashit( WP_LANG_DIR ) . 'woocommerce-gateway-paypal-adaptive-payments/woocommerce-gateway-paypal-adaptive-payments-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-gateway-paypal-adaptive-payments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add settings, docs, and support links in plugin action links.
	 *
	 * @since 1.1.6
	 *
	 * @param array $links Plugin action links
	 *
	 * @return array Plugin action links
	 */
	public function plugin_action_links( $links ) {
		$setting_link = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=paypal-adaptive-payments' );

		$plugin_links = array(
			'<a href="' . $setting_link . '">' . __( 'Settings', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</a>',
			'<a href="https://docs.woocommerce.com/document/paypal-adaptive-payments/">' . __( 'Docs', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</a>',
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	/**
	 * Includes.
	 */
	private function includes() {
		include_once 'includes/class-wc-paypal-adaptive-payments-gateway.php';
		include_once 'includes/class-wc-paypal-adaptive-payments-privacy.php';
	}

	/**
	 * Admin includes.
	 */
	private function admin_includes() {
		include_once 'includes/class-wc-paypal-adaptive-payments-admin.php';
	}

	/**
	 * Add the gateway.
	 *
	 * @param  array $methods WooCommerce payment methods.
	 *
	 * @return array          PayPal Adaptive Payments gateway.
	 */
	public function add_gateway( $methods ) {
		$methods[] = 'WC_PayPal_Adaptive_Payments_Gateway';

		return $methods;
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce PayPal Adaptive Payments Gateway depends on the last version of %s to work!', 'woocommerce-gateway-paypal-adaptive-payments' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">' . __( 'WooCommerce', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</a>' ) . '</p></div>';
	}
}

add_action( 'plugins_loaded', array( 'WC_PayPal_Adaptive_Payments', 'get_instance' ) );

endif;
