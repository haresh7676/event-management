<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_Event_Manager_Registrations_Integration class.
 *
 * Integrates the registrations plugin with other form plugins.
 */
class WP_Event_Manager_Registrations_Integration {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Integrate register with LinkedIn, XING and/or Facebook if forms are enabled
		if ( get_option( 'event_registration_form_for_url_method', '1' ) ) {
			add_filter( 'wp_event_manager_register_with_linkedin_enable_http_post', '__return_true' );
			add_filter( 'wp_event_manager_register_with_xing_enable_http_post', '__return_true' );
			add_filter( 'wp_event_manager_register_with_facebook_enable_http_post', '__return_true' );
		}

		add_action( 'wp_event_manager_register_with_linkedin_registration', array( $this, 'handle_apply_with_linkedin' ), 10, 3 );
		add_action( 'wp_event_manager_register_with_xing_registration', array( $this, 'handle_apply_with_xing' ), 10, 3 );
		add_action( 'wp_event_manager_register_with_facebook_registration', array( $this, 'handle_apply_with_facebook' ), 10, 4 );
		
	}

	/**
	 * Handle an registration from LinkedIn
	 * @param  array $registration
	 */
	public function handle_apply_with_linkedin( $event_id, $profile_data, $cover_letter ) {
		if ( ! $event_id || empty( $profile_data ) ) {
			return;
		}

		$attendee_name      = $profile_data->formattedName;
		$attendee_email     = $profile_data->emailAddress;
		$registration_message = $cover_letter;
		$registration_meta    = array();
		$registration_fields=array();
		if ( ! $registration_message ) {
			$registration_message = $profile_data->headline;
		} else {
			$registration_meta[ __( 'Title', 'wp-event-manager-registrations' ) ] = $profile_data->headline;
		}

		// Add meta data from submitted profile
		$registration_meta[ __( 'Location', 'wp-event-manager-registrations' ) ]     = $profile_data->location->name;
		$registration_meta[ __( 'Full Profile', 'wp-event-manager-registrations' ) ] = $profile_data->publicProfileUrl;
		
		$registration_fields['full-name']=$attendee_name;
		$registration_fields['email-address']=$attendee_email;

		create_event_registration( $event_id, $attendee_name, $attendee_email, $registration_message, $registration_meta, false, 'linkedin' );
	}

	/**
	 * Handle an registration from XING
	 * @param  array $registration
	 */
	public function handle_apply_with_xing( $event_id, $profile_data, $cover_letter ) {
		if ( ! $event_id || empty( $profile_data ) ) {
			return;
		}

		$attendee_name      = $profile_data->display_name;
		$attendee_email     = $profile_data->active_email;
		$registration_message = $cover_letter;
		$registration_meta    = array();
		$registration_fields=array();

		if ( ! $registration_message ) {
			$registration_message = $profile_data->haves;
		} else {
			$registration_meta[ __( 'Skills', 'wp-event-manager-registrations' ) ] = $profile_data->haves;
		}

		$location = __( 'Unknown location', 'wp-event-manager-registrations' );
		$address  = false;

		if ( $profile_data->business_address ) {
			$address = $profile_data->business_address;
		} elseif ( $profile_data->private_address ) {
			$address = $profile_data->private_address;
		}

		if ( $address ) {
			$location = '';
			if ( $address->city ) {
				$location = $address->city . ', ';
			}
			$location .= $address->country;
		}

		// Add meta data from submitted profile
		$registration_meta[ __( 'Location', 'wp-event-manager-registrations' ) ]     = $location;
		$registration_meta[ __( 'Full Profile', 'wp-event-manager-registrations' ) ] = $profile_data->permalink;
		
		$registration_fields['full-name']=$attendee_name;
		$registration_fields['email-address']=$attendee_email;

		create_event_registration( $event_id, $attendee_name, $attendee_email, $registration_message, $registration_meta, false, 'xing' );
	}

	/**
	 * Handle an registration from Facebook
	 * @param  array $registration
	 */
	public function handle_apply_with_facebook( $event_id, $profile_data, $profile_picture, $cover_letter ) {
		if ( ! $event_id || empty( $profile_data ) ) {
			return;
		}

		$attendee_name      = $profile_data->name;
		$attendee_email     = $profile_data->email;
		$registration_message = $cover_letter;
		$registration_meta    = array();
		$registration_fields=array();

		if ( ! $registration_message ) {
			$registration_message = $profile_data->bio;
		} else {
			$registration_meta[ __( 'Title', 'wp-event-manager-registrations' ) ] = $profile_data->bio;
		}

		// Add meta data from submitted profile
		$registration_meta[ __( 'Location', 'wp-event-manager-registrations' ) ]     = $profile_data->location->name;
		$registration_meta[ __( 'Full Profile', 'wp-event-manager-registrations' ) ] = $profile_data->link;
		
		$registration_fields['full-name']=$attendee_name;
		$registration_fields['email-address']=$attendee_email;
		
		create_event_registration( $event_id, $attendee_name, $attendee_email, $registration_message, $registration_meta, false, 'facebook' );
	}

}
new WP_Event_Manager_Registrations_Integration();