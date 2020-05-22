<?php
/*
Plugin Name: WP Event Manager - Registrations
Plugin URI: http://www.wp-eventmanager.com/plugins/

Description: Lets attendees submit registrations to events which are stored on the organizers events page, rather than simply emailed. Works standalone with it's built in registration form.
Author: WP Event Manager
Author URI: http://www.wp-eventmanager.com

Text Domain: wp-event-manager-registrations
Domain Path: /languages
Version: 1.6.1
Since: 1.0

Requires WordPress Version at least: 4.1
Copyright: 2015 WP Event Manager
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'GAM_Updater' ) ) {
	include( 'autoupdater/gam-plugin-updater.php' );
}
include_once(ABSPATH.'wp-admin/includes/plugin.php');
function pre_check_before_installing_registrations() 
{
    /*
    * Check weather WP Event Manager is installed or not
    */
    if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
    {
            global $pagenow;
        	if( $pagenow == 'plugins.php' )
        	{
                echo '<div id="error" class="error notice is-dismissible"><p>';
                echo __( 'WP Event Manager is require to use WP Event Manager - Registrations' , 'wp-event-manager-registrations');
                echo '</p></div>';		
        	}
        	return false;          	
    }    
}
add_action( 'admin_notices', 'pre_check_before_installing_registrations' );

/**
 * Create link on plugin page for registration settings
 */
function add_plugin_page_registration_settings_link( $links ) {
    $links[] = '<a href="' .
        admin_url( 'edit.php?post_type=event_registration&page=event-registrations-settings') .
        '">' . __('Settings', 'wp-event-manager-registrations') . '</a>';
        return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'add_plugin_page_registration_settings_link');

/**
 * WP_Event_Manager_Registrations class.
 */
class WP_Event_Manager_Registrations extends GAM_Updater {

	/**
	 * __construct function.
	 */
	public function __construct() 
	{
		// Define constants
		define( 'EVENT_MANAGER_REGISTRATIONS_VERSION', '1.6.1' );
		define( 'EVENT_MANAGER_REGISTRATIONS_FILE', __FILE__ );
		define( 'EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		// Check requirements
		if ( version_compare( phpversion(), '5.3', '<' ) ) {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				add_action( 'admin_notices', array( $this, 'php_admin_notice' ) );
			}
			return;
		}

		// Core
		include( 'core/wp-event-manager-registrations-post-types.php' );
		include( 'core/wp-event-manager-registrations-register.php' );
		include( 'core/wp-event-manager-registrations-dashboard.php' );
		include( 'core/wp-event-manager-registrations-past.php' );

		// Init classes
		$this->post_types = new WP_Event_Manager_Registrations_Post_Types();

		// Add actions
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 12 );
        add_action( 'after_setup_theme', array( $this, 'include_template_functions' ) );
		add_action( 'plugins_loaded', array( $this, 'integration' ), 12 );
		add_action( 'init', array( $this, 'load_admin' ), 12 );
		add_action( 'admin_init', array( $this, 'updater' ) );

		add_action( 'single_event_listing_meta_after', array( $this, 'already_registered_message' ) );
		// Activate
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		
		// Init updates
		$this->init_updates( __FILE__ );
	}

	/**
	 * Output a notice when using an old non-supported version of PHP
	 */
	public function php_admin_notice() {
		echo '<div class="error">';
		echo '<p>' . esc_html__( 'Unfortunately, WP Event Manager Registrations can not run on PHP versions older than 5.3. Read more information about <a href="https://github.com/WPupdatePHP/wp-update-php">how you can update</a>.', 'wp-event-manager-registrations' ) . '</p>';
		echo '</div>';
	}
    
    /**
	 * Localisation
	 **/
	public function load_plugin_textdomain() {
		$domain = 'wp-event-manager-registrations'; 
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain( $domain, WP_LANG_DIR . "/wp-event-manager-registrations/".$domain."-" .$locale. ".mo" );
		load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
    
	/**
	 * Load template functions
	 */
	public function include_template_functions() {
		include( 'wp-event-manager-registrations-functions.php' );
        include( 'wp-event-manager-registrations-template.php' );
	}
    
    /**
	 * Integrate with other plugins
	 */
	public function integration() {
		include_once( 'core/wp-event-manager-registrations-integration.php' );
	}

	/**
	 * Init the admin area
	 */
	public function load_admin() {
		if ( is_admin() && class_exists( 'WP_EVENT_Manager' ) ) {
			include_once( 'admin/wp-event-manager-registrations-admin.php' );
		}
	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		if ( version_compare( EVENT_MANAGER_REGISTRATIONS_VERSION, get_option( 'wp_event_manager_registrations_version' ), '>' ) ) {
			$this->install();
		}
	}

	/**
	 * Install
	 */
	public function install() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		if ( is_object( $wp_roles ) ) {
			$capabilities = $this->get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}

		wp_clear_scheduled_hook( 'event_registrations_purge' );
		wp_schedule_event( time(), 'daily', 'event_registrations_purge' );

		update_option( 'wp_event_manager_registrations_version', EVENT_MANAGER_REGISTRATIONS_VERSION );
	}

	/**
	 * Get capabilities
	 *
	 * @return array
	 */
	public function get_core_capabilities() {
		$capabilities     = array();
		$capability_types = array( 'event_registration' );

		foreach ( $capability_types as $capability_type ) {
			$capabilities[ $capability_type ] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms"
			);
		}

		return $capabilities;
	}
	
	/**
	 * Show message if already registered
	 */
	public function already_registered_message() {
		global $post;
		if ( user_has_registered_for_event( get_current_user_id(), $post->ID ) ) {
			get_event_manager_template( 'registered-notice.php', array(), 'wp-event-manager-registrations', EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR . '/templates/' );
		}
	}	
}

$GLOBALS['event_manager_registrations'] = new WP_Event_Manager_Registrations();