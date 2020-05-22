<?php global $wp_post_statuses; ?>
<ul class="meta">
    <?php do_action('event_registration_footer_meta_start',$registration);?>
	<li><?php echo $wp_post_statuses[ $registration->post_status ]->label; ?></li>
	<li><?php echo date_i18n( get_option( 'date_format' ), strtotime( $registration->post_date ) ); ?></li>
    <?php do_action('event_registration_footer_meta_end',$registration);?>
</ul>
<ul class="actions">
	<?php do_action('event_registration_footer_action_start',$registration);?>
	<li class="edit"><a href="#" title="<?php _e( 'Edit', 'wp-event-manager-registrations' ); ?>" class="event-registration-toggle-edit"><?php _e( 'Edit', 'wp-event-manager-registrations' ); ?></a></li>
	<li class="notes <?php echo get_comments_number( $registration->ID ) ? 'has-notes' : ''; ?>"><a href="#" title="<?php _e( 'Notes', 'wp-event-manager-registrations' ); ?>" class="event-registration-toggle-notes"><?php _e( 'Notes', 'wp-event-manager-registrations' ); ?></a></li>

	<?php if ( $email = get_event_registration_email( $registration->ID ) ) : ?>
		<li class="email"><a href="mailto:<?php echo esc_attr( $email ); ?>?subject=<?php echo esc_attr( sprintf( __( 'Your event registration for %s', 'wp-event-manager-registrations' ), strip_tags( get_the_title( $event_id ) ) ) ); ?>&amp;body=<?php echo esc_attr( sprintf( __( 'Hello %s', 'wp-event-manager-registrations' ), get_the_title( $registration->ID ) ) ); ?>" title="<?php _e( 'Email', 'wp-event-manager-registrations' ); ?>" class="event-registration-contact"><?php _e( 'Email', 'wp-event-manager-registrations' ); ?></a></li>
	<?php endif; ?>

	<li class="content"><a href="#" title="<?php _e( 'Details', 'wp-event-manager-registrations' ); ?>" class="event-registration-toggle-content"><?php _e( 'Details', 'wp-event-manager-registrations' ); ?></a></li>
	<?php do_action('event_registration_footer_action_end',$registration);?>
</ul>