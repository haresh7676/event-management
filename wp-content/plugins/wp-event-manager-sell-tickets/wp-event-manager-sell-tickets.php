<?php
/*
Plugin Name: WP Event Manager - Sell Tickets
Plugin URI: http://www.wp-eventmanager.com/
Description: Sell tickets for your events and keep track of them. Sell Tickets addon runs on the most popular eCommerce system - Woo commerce for the WordPress & support many payment gateways.
	
Author: WP Event Manager
Author URI: http://www.wp-eventmanager.com/
Text Domain: wp-event-manager-sell-tickets
Domain Path: /languages
Version: 1.8.1
Since: 1.0
Requires WordPress Version at least: 4.1

Copyright: 2017 WP Event Manager
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;
	
if ( ! class_exists( 'GAM_Updater' ) ) 
     include( 'autoupdater/gam-plugin-updater.php' );	

function pre_check_before_installing_sell_tickets() 
{
	
/*
* Check weather WP Event Manager is installed or not. If WP Event Manger is not installed or active then it will give notification to admin panel
*/
if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
        global $pagenow;
    	if( $pagenow == 'plugins.php' )
    	{
           echo '<div id="error" class="error notice is-dismissible"><p>';
           echo __( 'WP Event Manager is require to use WP Event Manager Sell Tickets' , 'wp-event-manager-sell-tickets');
           echo '</p></div>';	
    	}
    	return true;
}

/*
 * Check weather WP Event Manager is installed and version of wp event manger is higher than or equal to 3.0
*/
if ( in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && defined('EVENT_MANAGER_VERSION') && EVENT_MANAGER_VERSION < 3.0 )
{
	global $pagenow;
	if( $pagenow == 'plugins.php' )
	{
		echo '<div id="error" class="error notice is-dismissible"><p>';
		echo __( 'WP Event Manager Sell Tickets add-on require WP Event Manager 3.0 or higher version.' , 'wp-event-manager-sell-tickets');
		echo '</p></div>';
	}
	return true;
}
	
	
/*
 * Check weather WP Event Manager Registrations is installed or not. if WP Event Manger Registrations is not installed or active then it will give notification to admin panel
 */
if (! in_array( 'wp-event-manager-registrations/wp-event-manager-registrations.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{	   
     global $pagenow;
     if( $pagenow == 'plugins.php' ) 
     {
          echo '<div id="error" class="error notice is-dismissible"><p>';
          echo __( 'WP Event Manager Registrations addon is require to use WP Event Manager Sell Tickets' , 'wp-event-manager-sell-tickets');
          echo '</p></div>';
     }  
     return false;
}
	
/*
 * Check weather woocommerce is installed or not. If Woocommerce is not active then it will give notification to admin panel
 */
if (! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{	   
     global $pagenow;
     if( $pagenow == 'plugins.php' )
     {
     	echo '<div id="error" class="error notice is-dismissible"><p>';
     	echo  __( 'Woocommerce is require to use WP Event Manager Sell Tickets' , 'wp-event-manager-sell-tickets');
     	echo '</p></div>';	
     }  
     return false;     
}

}
add_action( 'admin_notices', 'pre_check_before_installing_sell_tickets' );
	
/**
 * WP_Event_Manager_Sell_Tickets class.
 */
class WP_Event_Manager_Sell_Tickets extends GAM_Updater {
	
	/**
	 * Constructor
	 */
	public function __construct() 
	{	
		//if gam event manager is not actionve
		if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{
			return;
		}
		//if registration is not active
		if (! in_array( 'wp-event-manager-registrations/wp-event-manager-registrations.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{
			return;
		}
		//if woocommerce is not active
		if (! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{
			return;
		}
		
		// Define constants
		define( 'EVENT_MANAGER_SELL_TICKETS_VERSION', '1.8.1' );
		define( 'EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
        
		
		// Add actions
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );		
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		//include
	   	include( 'shortcodes/wp-event-manager-sell-tickets-shortcodes.php' );
		include( 'wp-event-manager-sell-tickets-template.php' );
		include( 'wp-event-manager-sell-tickets-functions.php' );
		include('forms/wp-event-manager-sell-tickets-form-submit-event.php');
		include('admin/wp-event-manager-sell-tickets-writepanels.php');
		
		//event ticket type and generate ticket
		include('core/wp-event-manager-event-ticket-product.php');
		include('core/wp-event-manager-product-event-ticket.php');
		include('core/wp-event-manager-generate-ticket.php');
		
		if( is_admin() ){
		    include('admin/wp-event-manager-sell-tickets-wc-settings.php');
		    include('admin/wp-event-manager-sell-tickets-settings.php');
		    
		    //add admin side event_ticket product type 
		    include('admin/wp-event-manager-sell-tickets-admin.php');
		    
		    add_action( 'admin_init', function () {
		    	if ( version_compare( get_option( 'sellticket_db_version', 0 ), '1.6', '<' ) ) {
		    		$this->wp_event_manager_sell_tickets_install();
		    	}
		    });
		}
		
		
		//save custom fields (paid and free tickets details) at the time of submitting event form		
		add_action( 'event_manager_event_submitted', 'submit_tickets', 10, 2 ); 
		add_action( 'event_manager_update_event_data', 'update_tickets', 10, 2 ); 
		
		//Add product to cart at single event listing page for the logged users and non logged users		
		add_action('event_manager_ajax_add_tickets_to_cart','add_tickets_to_cart' );
		
		//Add custom fields form (which has predefined registration fields form at registrations addon) on checkout page
		add_action('woocommerce_after_order_notes', 'display_registration_form_at_checkout_page');
		add_action('woocommerce_checkout_update_order_meta', 'save_registration_form_at_checkout_page' );		
		
		//To make calculation easy for tickets overview detail like paid, free and total tickets count.
		//we can assign author/organizer to product so based on organizer we can get all products.
		add_action( 'init', array( $this, 'add_author_support_to_woocommerce_products') );	
		
		register_activation_hook( __FILE__,array( $this,  'wp_event_manager_sell_tickets_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'wp_event_manager_sell_tickets_deactivate' ) );
		
		// Init updates
		$this->init_updates( __FILE__ );
	}
	
	/**
	 * Localisation
	 */
	public function load_plugin_textdomain() {
		$domain = 'wp-event-manager-sell-tickets';       
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain( $domain, WP_LANG_DIR . "/wp-event-manager-sell-tickets/".$domain."-" .$locale. ".mo" );
		load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Add author support to product post type
	 * This makes it easy to change the author assigned to a WooCommerce product post type.
	 * @since 1.0
	 *
	 */
	function add_author_support_to_woocommerce_products() {
		if ( post_type_exists( 'product' ) ) {
			add_post_type_support( 'product', 'author' );
		}
	}
    
	
   	 /**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() 
	{	
		global $woocommerce;
		$ajax_url         = WP_Event_Manager_Ajax::get_endpoint();
		wp_register_script( 'wp-event-manager-sell-tickets-sell-ticket', EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL . '/assets/js/sell-ticket.js', array('jquery','wp-event-manager-common'), EVENT_MANAGER_SELL_TICKETS_VERSION, true );		
		
		//localize javascript file
		wp_localize_script( 'wp-event-manager-sell-tickets-sell-ticket', 'event_manager_sell_tickets_sell_ticket', array( 
			           'ajaxUrl' 	 => $ajax_url,
					   'redirectUrl' => wc_get_cart_url(),
			           'i18n_btnOkLabel' => __( 'Delete', 'wp-event-manager-sell-tickets' ),
					   'i18n_btnCancelLabel' => __( 'Cancel', 'wp-event-manager-sell-tickets' ),
					   'i18n_confirm_delete' => __( 'Are you sure you want to delete this ticket?', 'wp-event-manager-sell-tickets' ),
					   'i18n_loading_message' => __( 'Processing, Please wait.', 'wp-event-manager-sell-tickets' ),
					   'i18n_added_to_cart' => __( 'Products added to cart', 'wp-event-manager-sell-tickets' ),
					   'i18n_error_message' => __( 'There was an unexpected error', 'wp-event-manager-sell-tickets' ),
					   'i18n_no_ticket_found' => __( 'No tickets selected', 'wp-event-manager-sell-tickets' ),
					   'i18n_minimum_donation_error' => __( 'Donation amount must be greater than minimum amount', 'wp-event-manager-sell-tickets' ),
					   'i18n_datepicker_format' => WP_Event_Manager_Date_Time::get_datepicker_format(),					   
					   'i18n_timepicker_format' => WP_Event_Manager_Date_Time::get_timepicker_format(),
					   'i18n_timepicker_step' => WP_Event_Manager_Date_Time::get_timepicker_step()
		                )
		); 
		wp_enqueue_script('wp-event-manager-sell-tickets-sell-ticket'); 
		
		wp_register_style( 'wp-event-manager-sell-tickets-css', EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL . '/assets/css/frontend.min.css',EVENT_MANAGER_SELL_TICKETS_VERSION, true,$media='all' );
	   	wp_enqueue_style('wp-event-manager-sell-tickets-css');
	
		
	}
	
	/**
	 * Remove fields of sell ticket if plugin is deactivated
	 * @parma
	 * @return
	 **/
	public function wp_event_manager_sell_tickets_deactivate(){
		$all_fields = get_option( 'event_manager_form_fields', true );
		if(is_array($all_fields)){
			$sell_tickets_fields = array('paid_tickets','free_tickets','donation_tickets');
			foreach ($sell_tickets_fields as $value) {
				if(isset($all_fields['event'][$value]))
					unset($all_fields['event'][$value]);
			}
		}
		update_option('event_manager_form_fields', $all_fields);
	}
	
	/**
	 * Check if the installed version of WooCommerce is older than a specified version.
	 *
	 * @from Prospress/woocommerce-subscriptions
	 */
	public static function is_woocommerce_pre( $version ) {
	
		if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, $version, '<' ) ) {
			$woocommerce_is_pre_version = true;
		} else {
			$woocommerce_is_pre_version = false;
		}
	
		return $woocommerce_is_pre_version;
	}
	
	/**
	 * Install sell ticket
	 * @since 1.5
	 */
	public function wp_event_manager_sell_tickets_install() {
		// Upgrades
		if ( version_compare( get_option( 'sellticket_db_version', 0 ), '1.6', '<' ) ) {
			$query = array(
					'post_type' => array('product'),
					'posts_per_page' => -1,
					'meta_query' => array(
							array(
									'key'     => '_event_id',
									'compare' => 'EXISTS'
							)
					)
	
			);
			$products = new WP_Query( $query );
			while ( $products->have_posts()) : $products->the_post();
			wp_set_object_terms( get_the_ID(), 'event_ticket', 'product_type' );
			endwhile;
			wp_reset_postdata();
			update_option('sellticket_db_version','1.5');
		}
	}
}

$GLOBALS['event_manager_sell_tickets'] = new WP_Event_Manager_Sell_Tickets();
?>