<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


$options = array(
	'event_registration_form_for_email_method',
	'event_registration_form_for_url_method',
	'event_registration_form_require_login',
	'event_registration_prevent_multiple_registrations',
	'event_registration_delete_with_event',
	'event_registration_purge_days',
);

foreach ( $options as $option ) {
	delete_option( $option );
}



