<?php
/*
Plugin Name: WP Event Manager - Emails
Plugin URI: http://www.wp-eventmanager.com/
Description: Changes the default user email templates. When new user register then send mail with own defined template.

Author: WP Event Manager
Author URI: http://www.wp-eventmanager.com/
Text Domain: wp-event-manager-emails
Domain Path: /languages
Version: 1.2
Since: 1.0
Requires WordPress Version at least: 4.1

Copyright: 2017 WP Event Manager
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

function pre_check_before_installing_emails()
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
			echo __( 'WP Event Manager is require to use WP Event Manager Emails' , 'wp-event-manager-emails');
			echo '</p></div>';
		}
		return true;
	}
}
add_action( 'admin_notices', 'pre_check_before_installing_emails' );
/**
 * GAM_Event_Manager_Email class.
 */
class WP_Event_Manager_Emails extends GAM_Updater {

	/**
	 * Constructor
	 */
	public function __construct() {
	    
		// Define constants
		define( 'EVENT_MANAGER_EMAILS_VERSION', '1.2' );
		define( 'EVENT_MANAGER_EMAILS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EVENT_MANAGER_EMAILS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		// Add actions
		add_action( 'init', array( $this, 'load_plugin_textdomain' ));
	
		
		//include
		include( 'wp-event-manager-emails-functions.php' );	
		if(is_admin()){
		    include( 'admin/wp-event-manager-emails-notifications.php' );
		}
		
		/**
		 * Send email notification if settings is enabled
		 */
		if(get_option( 'new_event_email_nofication', true ) == true) 
		    add_action(  'pending_event_listing',  array( $this , 'send_new_event_email_notifications'),10, 2 );
        
        if(get_option( 'publish_event_email_nofication', true ) == true)
            add_action(  'publish_event_listing',  array( $this , 'send_published_event_email_notifications'),10, 2 );
        
        if(get_option( 'expired_event_email_nofication', true ) == true)
            add_action(  'expired_event_listing',  array( $this , 'send_expired_event_email_notifications'),10, 2 );   
        
        // Init updates
        $this->init_updates( __FILE__ );
	}
	
	/**
	 * Localisation
	 */
	public function load_plugin_textdomain() {
		$domain = 'wp-event-manager-emails';       
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain( $domain, WP_LANG_DIR . "/wp-event-manager-emails/".$domain."-" .$locale. ".mo" );
		load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Sent new event submitted email notification to organizer
	 */
	public function send_new_event_email_notifications( $id, $post ) {
	    if ( $post->post_type !== 'event_listing' ) return;
	    
	   $organizer_name = get_organizer_name( $post );
	   $organizer_email = get_event_organizer_email( $post );
	   $admin_email = get_option('admin_email');
	    
        if ( $organizer_email ) {
					
					$existing_shortcode_tags = $GLOBALS['shortcode_tags'];
					remove_all_shortcodes();
					event_manager_email_add_shortcodes( array( 'event_id' => $id, 'user_id' => '' ) );
					$subject = do_shortcode( get_new_event_email_subject() );
					$message = do_shortcode( get_new_event_email_content() );
					$message = str_replace( "\n\n\n\n", "\n\n", implode( "\n", array_map( 'trim', explode( "\n", $message ) ) ) );
					$is_html = ( $message != strip_tags( $message ) );

					// Does this message contain formatting already?
					if ( $is_html && ! strstr( $message, '<p' ) && ! strstr( $message, '<br' ) ) {
						$message = nl2br( $message );
					}

					$GLOBALS['shortcode_tags'] = $existing_shortcode_tags;

					$headers   = array();
					$headers[] = 'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>';
					$headers[] = 'Reply-To: ' . $organizer_email;
					$headers[] = $is_html ? 'Content-Type: text/html' : 'Content-Type: text/plain';
					$headers[] = 'charset=utf-8';

					wp_mail(
						apply_filters( 'send_new_event_email_notification_recipient', $organizer_email, $id ),
						apply_filters( 'send_new_event_email_notification_subject', $subject, $id ),
						apply_filters( 'send_new_event_email_notification_message', $message ),
						apply_filters( 'send_new_event_email_notification_headers', $headers, $id )						
					);
					
				}
    }
    
    /**
	 * Sent event published email notification to organizer
	 */
    public function send_published_event_email_notifications( $id, $post ) {
	    if ( $post->post_type !== 'event_listing' ) return;
	    
	   $organizer_name = get_organizer_name( $post );
	   $organizer_email = get_event_organizer_email( $post );
	   $admin_email = get_option('admin_email');
	    
        if ( $organizer_email ) {
					
					$existing_shortcode_tags = $GLOBALS['shortcode_tags'];
					remove_all_shortcodes();
					event_manager_email_add_shortcodes( array( 'event_id' => $id, 'user_id' => '' ) );
					$subject = do_shortcode( get_published_event_email_subject() );
					$message = do_shortcode( get_published_event_email_content() );
					$message = str_replace( "\n\n\n\n", "\n\n", implode( "\n", array_map( 'trim', explode( "\n", $message ) ) ) );
					$is_html = ( $message != strip_tags( $message ) );

					// Does this message contain formatting already?
					if ( $is_html && ! strstr( $message, '<p' ) && ! strstr( $message, '<br' ) ) {
						$message = nl2br( $message );
					}

					$GLOBALS['shortcode_tags'] = $existing_shortcode_tags;

					$headers   = array();
					$headers[] = 'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>';
					$headers[] = 'Reply-To: ' . $organizer_email;
					$headers[] = $is_html ? 'Content-Type: text/html' : 'Content-Type: text/plain';
					$headers[] = 'charset=utf-8';

					wp_mail(
						apply_filters( 'send_published_event_email_notification_recipient', $organizer_email, $id ),
						apply_filters( 'send_published_event_email_notification_subject', $subject, $id ),
						apply_filters( 'send_published_event_email_notification_message', $message ),
						apply_filters( 'send_published_event_email_notification_headers', $headers, $id )						
					);
				}
    }
    
    /**
	 * Sent event published email notification to organizer
	 */
    public function send_expired_event_email_notifications( $id, $post ) {
	    if ( $post->post_type !== 'event_listing' ) return;
	    
	   $organizer_name = get_organizer_name( $post );
	   $organizer_email = get_event_organizer_email( $post );
	   $admin_email = get_option('admin_email');
	    
        if ( $organizer_email ) {
					
					$existing_shortcode_tags = $GLOBALS['shortcode_tags'];
					remove_all_shortcodes();
					event_manager_email_add_shortcodes( array( 'event_id' => $id, 'user_id' => '' ) );
					$subject = do_shortcode( get_expired_event_email_subject() );
					$message = do_shortcode( get_expired_event_email_content() );
					$message = str_replace( "\n\n\n\n", "\n\n", implode( "\n", array_map( 'trim', explode( "\n", $message ) ) ) );
					$is_html = ( $message != strip_tags( $message ) );

					// Does this message contain formatting already?
					if ( $is_html && ! strstr( $message, '<p' ) && ! strstr( $message, '<br' ) ) {
						$message = nl2br( $message );
					}

					$GLOBALS['shortcode_tags'] = $existing_shortcode_tags;

					$headers   = array();
					$headers[] = 'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>';
					$headers[] = 'Reply-To: ' . $organizer_email;
					$headers[] = $is_html ? 'Content-Type: text/html' : 'Content-Type: text/plain';
					$headers[] = 'charset=utf-8';

					wp_mail(
						apply_filters( 'send_expired_event_email_notification_recipient', $organizer_email, $id ),
						apply_filters( 'send_expired_event_email_notification_subject', $subject, $id ),
						apply_filters( 'send_expired_event_email_notification_message', $message ),
						apply_filters( 'send_expired_event_email_notification_headers', $headers, $id )						
					);
				}
    }
}
$GLOBALS['event_manager_emails'] = new WP_Event_Manager_Emails();