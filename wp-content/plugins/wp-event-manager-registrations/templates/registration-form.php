<?php global $post; ?>
<form class="event-manager-registration-form event-manager-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( get_permalink() );?>">
	<?php do_action( 'event_registration_form_fields_start' ); ?>

	<?php foreach ( $registration_fields as $key => $field ) : ?>
		<?php if ( 'output-content' === $field['type'] ) : ?>
			<div class="form-content">
				<h3><?php esc_html_e( $field['label'] ); ?></h3>
				<?php if ( ! empty( $field['description'] ) ) : ?><?php echo wpautop( wp_kses_post( $field['description'] ) ); ?><?php endif; ?>
			</div>
		<?php else : ?>
			<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">
				<label for="<?php esc_attr_e( $key ); ?>"><?php echo __( $field['label'] ) . apply_filters( 'submit_event_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-event-manager' ) . '</small>', $field ); ?></label>
				<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
					<?php $class->get_field_template( $key, $field ); ?>
				</div>
			</fieldset>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php do_action( 'event_registration_form_fields_end' ); ?>

	<p>
		<input type="submit" name="wp_event_manager_send_registration" value="<?php esc_attr_e( 'Send registration', 'wp-event-manager-registrations' ); ?>" />
		<input type="hidden" name="event_id" value="<?php echo absint( $post->ID ); ?>" />
	</p>
</form>