<?php

/**
 * WP_Event_Manager_Registrations_Form_Editor class.
 */
class WP_Event_Manager_Emails_Notifications {
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
		add_submenu_page( 'edit.php?post_type=event_listing', __( 'Email Notifications', 'wp-event-manager-emails' ),  __( 'Email Notifications', 'wp-event-manager-emails' ) , 'manage_options', 'event-emails-notifications', array( $this, 'output' ) );
	}
		
	/**
	 * Output the screen
	 */
	public function output() {
	    $tabs = array(
			'event-notification-settings'        => __('Notification Settings','wp-event-manager-emails'),
			'new-event-notification'        => __('New Event Notification','wp-event-manager-emails'),
			'published-event-notification'  => __('Published Event Notification','wp-event-manager-emails'),
			'expired-event-notification'    => __('Expired Event Notification','wp-event-manager-emails')
		);
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'event-notification-settings';
		?>
		<div class="wrap wp-event-manager-emails-notifications">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach( $tabs as $key => $value ) {
					$active = ( $key == $tab ) ? 'nav-tab-active' : '';
					echo '<a class="nav-tab ' . $active . '" href="' . admin_url( 'edit.php?post_type=event_listing&page=event-emails-notifications&tab=' . esc_attr( $key ) ) . '">' . esc_html( $value ) . '</a>';
				}
				?>
			</h2>
			<form method="post" id="mainform" action="edit.php?post_type=event_listing&amp;page=event-emails-notifications&amp;tab=<?php echo esc_attr( $tab ); ?>">
				<?php
				switch ( $tab ) {
				    case 'event-notification-settings' :
						$this->event_notification_settings();
					break;
					case 'new-event-notification' :
						$this->new_event_notification_email();
					break;
					case 'published-event-notification' :
					    $this->published_event_notification_email();
					break;
					case 'expired-event-notification' :
					    $this->expired_event_notification_email();
					break;
					default :
					    $this->event_notification_settings();
					break;
				}
				?>
				<?php wp_nonce_field( 'save-' . $tab ); ?>
			</form>
		</div>
		<?php
	    
	}
	
    /**
     * Event Notification settings
     */
	public function event_notification_settings(){
	    if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-event-notification-settings' )  ) {
			echo $this->save_event_notification_settings();
		}  
		$new_event      = get_option( 'new_event_email_nofication',true ) ? true : false;
		$publish_event  = get_option( 'publish_event_email_nofication',true )? true : false;
		$expire_event   = get_option( 'expired_event_email_nofication',true )? true : false;
		?>
			<div class="wp-event-emails-email-content-wrapper">	
             <div class="admin-setting-left">			     	
			      <div class="white-background">
			        <div class="wp-event-emails">
			            <p><input id="email-settings-new-event" name="email-settings-new-event" type="checkbox" <?php if( $new_event ) echo 'checked=checked';?>> <?php _e('New Event Notification','wp-event-manager-emails'); ?></p>
			            <p><input id="email-settings-pulish-event" name="email-settings-publish-event" type="checkbox" <?php if( $publish_event ) echo 'checked=checked';?>> <?php _e('Publish Event Notification','wp-event-manager-emails'); ?></p>
			            <p><input id="email-settings-expired-event" name="email-settings-expired-event" type="checkbox" <?php if( $expire_event ) echo 'checked=checked';?>> <?php _e('Expired Event Notification','wp-event-manager-emails'); ?></p>
			         
    				    <p class="submit-email save-actions"><input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-emails' ); ?>" /></p>
				     </div>
			     </div>	<!--white-background-->		       
			</div>	<!--admin-setting-left--> 
		<?php
	}
	
	/**
	 * Save the email
	 */
	private function save_event_notification_settings() {
	    
		$new_event      = isset( $_POST['email-settings-new-event'] ) ? true : false;
		$publish_event  = isset( $_POST['email-settings-publish-event'] ) ? true : false;
		$expire_event   = isset( $_POST['email-settings-expired-event'] ) ? true : false;
		
		$result        = update_option( 'new_event_email_nofication', $new_event );
		$result2       = update_option( 'publish_event_email_nofication', $publish_event );
		$result3       = update_option( 'expired_event_email_nofication', $expire_event );
		
		if ( true === $result || true === $result2 || true === $result3 ) {
			echo '<div class="updated"><p>' . __( 'Settings successfully saved.', 'wp-event-manager-emails' ) . '</p></div>';
		}
	}
	
	/**
	 * New email notification 
	 */
	public function new_event_notification_email()
	{
    	if ( ! empty( $_GET['reset-new-event-email'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
		    delete_option( 'new_event_email_content' );
		    delete_option( 'new_event_email_subject' );
		    echo '<div class="updated"><p>' . __( 'The email was successfully reset.', 'wp-event-manager-emails' ) . '</p></div>';
		}
		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-new-event-notification' )  ) {
			echo $this->save_new_event_notification();
		}
		?>
		<div class="wp-event-emails-email-content-wrapper">	
             <div class="admin-setting-left">			     	
			      <div class="white-background">
			      	<p><?php _e( 'Below you will find the email that is sent to an Organizer when event status is pendding for approval.', 'wp-event-manager-emails' ); ?></p>
			        <div class="wp-event-emails-email-content">
    					<p><input type="text" name="new-event-email-subject" value="<?php echo esc_attr( get_new_event_email_subject() ); ?>" placeholder="<?php echo esc_attr( __( 'Subject', 'wp-event-manager-emails' ) ); ?>" /></p>
    					<p>
    						<textarea name="new-event-email-content" cols="71" rows="10"><?php echo esc_textarea( get_new_event_email_content() ); ?></textarea>
    				    </p>
    				    <p class="submit-email save-actions">
			<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-new-event-email', 1 ), 'reset' ); ?>" class="reset"><?php _e( 'Reset to defaults', 'wp-event-manager-emails' ); ?></a>
			<input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-emails' ); ?>" />
		</p>
				     </div>
			     </div>	<!--white-background-->		       
			</div>	<!--admin-setting-left-->  	
			<?php $this->get_dynamic_shortcode_email_box();?>
		</div>
	  	
		<?php
	}
	
	/**
	 * Save the email
	 */
	private function save_new_event_notification() {
		$email_content = wp_unslash( $_POST['new-event-email-content'] );
		$email_subject = sanitize_text_field( wp_unslash( $_POST['new-event-email-subject'] ) );
		$result        = update_option( 'new_event_email_content', $email_content );
		$result2       = update_option( 'new_event_email_subject', $email_subject );

		if ( true === $result || true === $result2 ) {
			echo '<div class="updated"><p>' . __( 'The email was successfully saved.', 'wp-event-manager-emails' ) . '</p></div>';
		}
	}
	
	/**
	 * Published email notification 
	 */
	public function published_event_notification_email()
	{
    	if ( ! empty( $_GET['reset-published-event-email'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
		    delete_option( 'published_event_email_content' );
		    delete_option( 'published_event_email_subject' );
		    echo '<div class="updated"><p>' . __( 'The email was successfully reset.', 'wp-event-manager-emails' ) . '</p></div>';
		}
		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-published-event-notification' )  ) {
			echo $this->save_published_event_notification();
		}
		?>
		<div class="wp-event-emails-email-content-wrapper">	
             <div class="admin-setting-left">			     	
			      <div class="white-background">
			      	<p><?php _e( 'Below you will find the email that is sent to an Organizer after event is published.', 'wp-event-manager-emails' ); ?></p>
			        <div class="wp-event-emails-email-content">
    					<p><input type="text" name="published-event-email-subject" value="<?php echo esc_attr( get_published_event_email_subject() ); ?>" placeholder="<?php echo esc_attr( __( 'Subject', 'wp-event-manager-emails' ) ); ?>" /></p>
    					<p>
    						<textarea name="published-event-email-content" cols="71" rows="10"><?php echo esc_textarea( get_published_event_email_content() ); ?></textarea>
    				    </p>
    				    <p class="submit-email save-actions">
			<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-published-event-email', 1 ), 'reset' ); ?>" class="reset"><?php _e( 'Reset to defaults', 'wp-event-manager-emails' ); ?></a>
			<input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-emails' ); ?>" />
		</p>
				     </div>
			     </div>	<!--white-background-->		       
			</div>	<!--admin-setting-left-->  	
			<?php $this->get_dynamic_shortcode_email_box();?>
		</div>
		<?php
	}
	
	/**
	 * Save published email
	 */
	private function save_published_event_notification() {
		$email_content = wp_unslash( $_POST['published-event-email-content'] );
		$email_subject = sanitize_text_field( wp_unslash( $_POST['published-event-email-subject'] ) );
		$result        = update_option( 'published_event_email_content', $email_content );
		$result2       = update_option( 'published_event_email_subject', $email_subject );

		if ( true === $result || true === $result2 ) {
			echo '<div class="updated"><p>' . __( 'The email was successfully saved.', 'wp-event-manager-emails' ) . '</p></div>';
		}
	}
	
	/**
	 * Expired email notification 
	 */
	public function expired_event_notification_email()
	{
    	if ( ! empty( $_GET['reset-published-event-email'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'reset' ) ) {
		    delete_option( 'expired_event_email_content' );
		    delete_option( 'expired_event_email_subject' );
		    echo '<div class="updated"><p>' . __( 'The email was successfully reset.', 'wp-event-manager-emails' ) . '</p></div>';
		}
		if ( ! empty( $_POST ) && ! empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'save-expired-event-notification' )  ) {
			echo $this->save_expired_event_notification();
		}
		?>
		<div class="wp-event-emails-email-content-wrapper">	
             <div class="admin-setting-left">			     	
			      <div class="white-background">
			      	<p><?php _e( 'Below you will find the email that is sent to an Organizer after event is expired.', 'wp-event-manager-emails' ); ?></p>
			        <div class="wp-event-emails-email-content">
    					<p><input type="text" name="expired-event-email-subject" value="<?php echo esc_attr( get_expired_event_email_subject() ); ?>" placeholder="<?php echo esc_attr( __( 'Subject', 'wp-event-manager-emails' ) ); ?>" /></p>
    					<p>
    						<textarea name="expired-event-email-content" cols="71" rows="10"><?php echo esc_textarea( get_expired_event_email_content() ); ?></textarea>
    				    </p>
    				    <p class="submit-email save-actions">
							<a href="<?php echo wp_nonce_url( add_query_arg( 'reset-expired-event-email', 1 ), 'reset' ); ?>" class="reset"><?php _e( 'Reset to defaults', 'wp-event-manager-emails' ); ?></a>
							<input type="submit" class="save-email button-primary" value="<?php _e( 'Save Changes', 'wp-event-manager-emails' ); ?>" />
						</p>
				     </div>
			     </div>	<!--white-background-->		       
			</div>	<!--admin-setting-left-->  	
			<?php $this->get_dynamic_shortcode_email_box();?>
		</div>
		<?php
	}
	
	/**
	 * Save published email
	 */
	private function save_expired_event_notification() {
		$email_content = wp_unslash( $_POST['expired-event-email-content'] );
		$email_subject = sanitize_text_field( wp_unslash( $_POST['expired-event-email-subject'] ) );
		$result        = update_option( 'expired_event_email_content', $email_content );
		$result2       = update_option( 'expired_event_email_subject', $email_subject );

		if ( true === $result || true === $result2 ) {
			echo '<div class="updated"><p>' . __( 'The email was successfully saved.', 'wp-event-manager-emails' ) . '</p></div>';
		}
	}
	
	/**
	 * Dynamic shortcode box
	 */
	public function get_dynamic_shortcode_email_box(){
		?>
			<div class="box-info">
			   <div class="wp-event-emails-email-content-tags">
				<p><?php _e( 'The following tags can be used to add content dynamically:', 'wp-event-manager-emails' ); ?></p>
				<ul>
					<?php foreach ( get_event_manager_email_tags() as $tag => $name ) : ?>
						<li><code>[<?php echo esc_html( $tag ); ?>]</code> - <?php echo wp_kses_post( $name ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p><?php _e( 'All tags can be passed a prefix and a suffix which is only output when the value is set e.g. <code>[event_title prefix="Event Title: " suffix="."]</code>', 'wp-event-manager-emails' ); ?></p>
			   </div>
		    </div> <!--box-info--> 
		<?php 
	}
}
new WP_Event_Manager_Emails_Notifications();