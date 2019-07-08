<?php

/**
 * WP_Event_Manager_Registrations_Form_Editor class.
 */
class WP_Event_Manager_Registrations_Notifications {
    /**
	 * Constructor
	 */
	public function __construct() {
	    add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
	
	/**
	 * Add form editor menu item
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=event_registration', __( 'Notifications', 'wp-event-manager-registrations' ),  __( 'Notifications', 'wp-event-manager-registrations' ) , 'manage_options', 'event-registrations-notifications', array( $this, 'output' ) );
	}
	
	/**
	 * Output the screen
	 */
	public function output() {
	    $tabs = array(
			'organizer-notification'  => 'Organizer Notification',
			'attendee-notification' => 'Attendee Notification'
		);
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'organizer-notification';
		?>
		<div class="wrap wp-event-manager-registrations-form-editor">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach( $tabs as $key => $value ) {
					$active = ( $key == $tab ) ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active . '" href="' . admin_url( 'edit.php?post_type=event_registration&page=event-registrations-notifications&tab=' . esc_attr( $key ) ) . '">' . esc_html( $value ) . '</a>';
				}
				?>
			</h2>
			<form method="post" id="mainform" action="edit.php?post_type=event_registration&amp;page=event-registrations-notifications&amp;tab=<?php echo esc_attr( $tab ); ?>">
				<?php
				switch ( $tab ) {
					case 'organizer-notification' :
						$this->organizer_notification_email();
					break;
					case 'attendee-notification' :
						$this->attendee_notification_email();
					break;
					default :
						$this->form_editor();
					break;
				}
				?>
				<?php wp_nonce_field( 'save-' . $tab ); ?>
			</form>
		</div>
		<?php
	    
	}
	/**
	 * Email editor
	 */
	private function organizer_notification_email() {
		if ( ! empty( $_GET['reset-email'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
			delete_option( 'event_registration_email_content' );
			delete_option( 'event_registration_email_subject' );
			echo '<div class="updated"><p>' . __( 'The email was successfully reset.', 'wp-event-manager-registrations' ) . '</p></div>';
		}

		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-organizer-notification' )  ) {
			echo $this->organizer_notification_editor_save();
		}
		?>
		
		<div class="wp-event-registrations-email-content-wrapper">	
		
             <div class="admin-setting-left">			     	
			      <div class="white-background">
			      	<p><?php _e( 'Below you will find the email that is sent to an Organizer after a attendee submits an registration.', 'wp-event-manager-registrations' ); ?></p>
			        <div class="wp-event-registrations-email-content">
    					<p>
    					   <input type="text" name="email-subject" value="<?php echo esc_attr( get_event_registration_email_subject() ); ?>" placeholder="<?php echo esc_attr( __( 'Subject', 'wp-event-manager-registrations' ) ); ?>" />
    				    </p>
    					<p>
    						<textarea name="email-content" cols="71" rows="10"><?php echo esc_textarea( get_event_registration_email_content() ); ?></textarea>
    				    </p>
				     </div>
			     </div>	<!--white-background-->		       
			</div>	<!--	admin-setting-left-->  	
			
			<div class="box-info">
			   <div class="wp-event-registrations-email-content-tags">
				<p><?php _e( 'The following tags can be used to add content dynamically:', 'wp-event-manager-registrations' ); ?></p>
				<ul>
					<?php foreach ( get_event_registration_email_tags() as $tag => $name ) : ?>
						<li><code>[<?php echo esc_html( $tag ); ?>]</code> - <?php echo wp_kses_post( $name ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p><?php _e( 'All tags can be passed a prefix and a suffix which is only output when the value is set e.g. <code>[event_title prefix="Event Title: " suffix="."]</code>', 'wp-event-manager-registrations' ); ?></p>
			   </div>
		    </div> <!--box-info--> 
		</div>
		<p class="submit-email save-actions">
			<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-email', 1 ), 'reset' ); ?>" class="reset"><?php _e( 'Reset to defaults', 'wp-event-manager-registrations' ); ?></a>
			<input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-registrations' ); ?>" />
		</p>
		<?php
	}
	
	
	/**
	 * Save the email
	 */
	private function organizer_notification_editor_save() {
		$email_content = wp_unslash( $_POST['email-content'] );
		$email_subject = sanitize_text_field( wp_unslash( $_POST['email-subject'] ) );
		$result        = update_option( 'event_registration_email_content', $email_content );
		$result2       = update_option( 'event_registration_email_subject', $email_subject );

		if ( true === $result || true === $result2 ) {
			echo '<div class="updated"><p>' . __( 'The email was successfully saved.', 'wp-event-manager-registrations' ) . '</p></div>';
		}
	}
	
	/**
	 * Email editor
	 */
	private function attendee_notification_email() {
		if ( ! empty( $_GET['reset-email'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
			delete_option( 'event_registration_attendee_email_content' );
			delete_option( 'event_registration_attendee_email_subject' );
			echo '<div class="updated"><p>' . __( 'The email was successfully reset.', 'wp-event-manager-registrations' ) . '</p></div>';
		}

		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-attendee-notification' )  ) {
			echo $this->attendee_notification_editor_save();
		}
		?>
		
		
		<div class="admin-setting-left">
		   <div class="white-background">		
    		   <p><?php _e( 'Below you will find the email that is sent to a attendee after submitting an registration. Leave blank to disable.', 'wp-event-manager-registrations' ); ?></p>
    		
    		   <div class="wp-event-registrations-email-content-wrapper">
    			  <div class="wp-event-registrations-email-content">
    				<p>
    					<input type="text" name="email-subject" value="<?php echo esc_attr( get_event_registration_attendee_email_subject() ); ?>" placeholder="<?php echo esc_attr( __( 'Subject', 'wp-event-manager-registrations' ) ); ?>" />
    				</p>
    				<p>
    					<textarea name="email-content" cols="71" rows="10" placeholder="<?php _e( 'N/A', 'wp-event-manager-registrations' ); ?>"><?php echo esc_textarea( get_event_registration_attendee_email_content() ); ?></textarea>
    				</p>
    			  </div>
    			</div>
		   </div>	<!--white-background-->
		 </div>	<!-- admin-setting-left-->     
		 
		 <div class="box-info">
			<div class="wp-event-registrations-email-content-tags">
				<p><?php _e( 'The following tags can be used to add content dynamically:', 'wp-event-manager-registrations' ); ?></p>
				<ul>
					<?php foreach ( get_event_registration_email_tags() as $tag => $name ) : ?>
						<li><code>[<?php echo esc_html( $tag ); ?>]</code> - <?php echo wp_kses_post( $name ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p><?php _e( 'All tags can be passed a prefix and a suffix which is only output when the value is set e.g. <code>[event_title prefix="Event Title: " suffix="."]</code>', 'wp-event-manager-registrations' ); ?></p>
			</div>
		 </div> <!--box-info--> 
		
		<p class="submit-email save-actions">
			<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-email', 1 ), 'reset' ); ?>" class="reset"><?php _e( 'Reset to defaults', 'wp-event-manager-registrations' ); ?></a>
			<input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-registrations' ); ?>" />
		</p>
		<?php
	}

	/**
	 * Save the email
	 */
	private function attendee_notification_editor_save() {
		$email_content = wp_unslash( $_POST['email-content'] );
		$email_subject = sanitize_text_field( wp_unslash( $_POST['email-subject'] ) );
		$result        = update_option( 'event_registration_attendee_email_content', $email_content );
		$result2       = update_option( 'event_registration_attendee_email_subject', $email_subject );

		if ( true === $result || true === $result2 ) {
			echo '<div class="updated"><p>' . __( 'The email was successfully saved.', 'wp-event-manager-registrations' ) . '</p></div>';
		}
	}


	
}
new WP_Event_Manager_Registrations_Notifications();