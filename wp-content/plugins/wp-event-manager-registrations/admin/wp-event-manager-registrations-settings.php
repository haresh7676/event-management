<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * WP_Event_Manager_Registrations_Settings class.
 */
class WP_Event_Manager_Registrations_Settings extends WP_Event_Manager_Settings {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->settings_group = 'wp-event-manager-registrations';
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * init_settings function.
	 *
	 * @access protected
	 * @return void
	 */
	protected function init_settings() {
		$this->settings = apply_filters( 'event_manager_registrations_settings', array(
			'registration_forms' => array(
				__( 'Registration Forms', 'wp-event-manager-registrations' ),
				array(
					array(
						'name' 		=> 'event_registration_form_for_email_method',
						'std' 		=> '1',
						'label' 	=> __( 'Email Registration Method', 'wp-event-manager-registrations' ),
						'cb_label' 	=> __( 'Use registration form', 'wp-event-manager-registrations' ),
						'desc'		=> __( 'Show registration form for events with an email registration method. Disable to use the default registration functionality, or another form plugin.', 'wp-event-manager-registrations' ),
						'type'      => 'checkbox'
					),
					array(
						'name' 		=> 'event_registration_form_for_url_method',
						'std' 		=> '1',
						'label' 	=> __( 'Website URL Registration Method', 'wp-event-manager-registrations' ),
						'cb_label' 	=> __( 'Use registration form', 'wp-event-manager-registrations' ),
						'desc'		=> __( 'Show registration form for events with a website url registration method. Disable to use the default registration functionality, or another form plugin.', 'wp-event-manager-registrations' ),
						'type'      => 'checkbox'
					),
					array(
						'name' 		=> 'event_registration_form_require_login',
						'std' 		=> '0',
						'label' 	=> __( 'User Restriction', 'wp-event-manager-registrations' ),
						'cb_label' 	=> __( 'Only allow registered users to register', 'wp-event-manager-registrations' ),
						'desc'		=> __( 'If enabled, only logged in users can register. Non-logged in users will see the contents of the <code>registration-form-login.php</code> file instead of a form.', 'wp-event-manager-registrations' ),
						'type'      => 'checkbox'
					),
					array(
						'name' 		=> 'event_registration_prevent_multiple_registrations',
						'std' 		=> '0',
						'label' 	=> __( 'Multiple Registrations', 'wp-event-manager-registrations' ),
						'cb_label' 	=> __( 'Prevent users from registering to the same event multiple times', 'wp-event-manager-registrations' ),
						'desc'		=> __( 'If enabled, the register form will be hidden after registering.', 'wp-event-manager-registrations' ),
						'type'      => 'checkbox'
					)
				)
			),
			'registration_management' => array(
				__( 'Management', 'wp-event-manager-registrations' ),
				array(
					array(
						'name' 		=> 'event_registration_delete_with_event',
						'std' 		=> '0',
						'label' 	=> __( 'Delete with Events', 'wp-event-manager-registrations' ),
						'cb_label' 	=> __( 'Delete registrations when a event is deleted', 'wp-event-manager-registrations' ),
						'desc'		=> __( 'If enabled, event registrations will be deleted when the parent event listing is deleted. Otherwise they will be kept on file and visible in the backend.', 'wp-event-manager-registrations' ),
						'type'      => 'checkbox'
					),
					array(
						'name'        => 'event_registration_purge_days',
						'std'         => '',
						'placeholder' => __( 'Do not purge data', 'wp-event-manager-registrations' ),
						'label'       => __( 'Purge Registrations', 'wp-event-manager-registrations' ),
						'desc'        => __( 'Purge registration data and files after X days. Leave blank to disable.', 'wp-event-manager-registrations' ),
						'type'        => 'text'
					)
				)
			)
		) );
	}
}
