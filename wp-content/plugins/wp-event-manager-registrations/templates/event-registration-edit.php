<form class="event-manager-registration-edit-form event-manager-form" method="post">

	<fieldset class="fieldset-status">
		<label for="registration-status-<?php esc_attr_e( $registration->ID ); ?>"><?php _e( 'Registration status', 'wp-event-manager-registrations' ); ?>:</label>
		<div class="field">
			<select id="registration-status-<?php esc_attr_e( $registration->ID ); ?>" name="registration_status">
				<?php foreach ( get_event_registration_statuses() as $name => $label ) : ?>
					<option value="<?php echo esc_attr( $name ); ?>" <?php selected( $registration->post_status, $name ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</fieldset>

	<p>
		<a class="delete_event_registration" href="<?php echo wp_nonce_url( add_query_arg( 'delete_event_registration', $registration->ID ), 'delete_event_registration' ); ?>"><?php _e( 'Delete', 'wp-event-manager-registrations' ); ?></a>
		<input type="submit" name="wp_event_manager_edit_registration" value="<?php esc_attr_e( 'Save changes', 'wp-event-manager-registrations' ); ?>" />
		<input type="hidden" name="registration_id" value="<?php echo absint( $registration->ID ); ?>" />
		<?php wp_nonce_field( 'edit_event_registration' ); ?>
	</p>
</form>