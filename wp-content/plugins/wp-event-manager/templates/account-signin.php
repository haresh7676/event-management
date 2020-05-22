<?php if ( is_user_logged_in() ) : ?>	<fieldset>		<label><?php _e( 'Your account', 'wp-event-manager' ); ?></label>		<div class="field account-sign-in">			<?php				$user = wp_get_current_user();				printf( wp_kses( __( 'You are currently signed in as <strong>%s</strong>.', 'wp-event-manager' ), array(  'strong' => array() ) ), $user->user_login );    		?>			<a class="button" href="<?php echo apply_filters( 'submit_event_form_logout_url', wp_logout_url( get_permalink() ) ); ?>"><?php _e( 'Sign out', 'wp-event-manager' ); ?></a>		</div>	</fieldset>	<?php else :	$account_required             = event_manager_user_requires_account();	$registration_enabled         = event_manager_enable_registration();	$registration_fields          = wp_event_manager_get_registration_fields();	$generate_username_from_email = event_manager_generate_username_from_email();	?>	<fieldset>		<label><?php _e( 'Have an account?', 'wp-event-manager' ); ?></label>		<div class="field account-sign-in">			<a class="button" href="<?php echo apply_filters( 'submit_event_form_login_url', get_option('event_manager_login_page_url') ); ?>"><?php _e( 'Sign in', 'wp-event-manager' ); ?></a>			<?php if ( $registration_enabled ) : ?>				<?php printf( __( 'If you don&rsquo;t have an account with us, just enter your email address and create a new one.  You will receive your password shortly in your email.', 'wp-event-manager' ), $account_required ? '' : __( 'optionally', 'wp-event-manager' ) . ' ' ); ?>			<?php elseif ( $account_required ) : ?>				<?php echo apply_filters( 'submit_event_form_login_required_message',  __(' You must sign in to create a new listing.', 'wp-event-manager' ) ); ?>			<?php endif; ?>		</div>	</fieldset><?php if ( $registration_enabled ) : 	if ( ! empty( $registration_fields ) ) {		foreach ( $registration_fields as $key => $field ) {			?>			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">				<label					for="<?php echo esc_attr( $key ); ?>"><?php echo $field[ 'label' ] . apply_filters( 'submit_event_form_required_label', $field[ 'required' ] ? '' : ' <small>' . __( '(optional)', 'wp-event-manager' ) . '</small>', $field ); ?></label>				<div class="field <?php echo $field[ 'required' ] ? 'required-field' : ''; ?>">					<?php get_event_manager_template( 'form-fields/' . $field[ 'type' ] . '-field.php', array( 'key'   => $key, 'field' => $field ) ); ?>				</div>			</fieldset>			<?php		}		do_action( 'event_manager_register_form' );	}	?>	<?php endif; ?>	<?php endif; ?>	