<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$options = array(
				'new_event_email_nofication',
				'publish_event_email_nofication',
				'expired_event_email_nofication',
				'new_event_email_content',
				'new_event_email_subject',
				'published_event_email_content',
				'published_event_email_subject',
				'expired_event_email_content',
				'expired_event_email_subject'
			);

foreach ( $options as $option ) {
	delete_option( $option );
}
