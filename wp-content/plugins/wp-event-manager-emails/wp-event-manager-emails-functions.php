<?php
if ( ! function_exists( 'wp_new_user_notification' ) ) :
/**
 * Redefine new user notification function
 *
 * emails new users their login info
 *
 * @param   integer $user_id user id
 * @param   string $plaintext_pass optional password
 */
function wp_new_user_notification( $user_id, $plaintext_pass = '' ) 
{ 	    	
	 // user
	 $user = new WP_User( $user_id );
	 $user_name = stripslashes($user->user_login);
	 $user_email = stripslashes($user->user_email);	
     
     // site/blog 
	 $siteUrl = get_site_url();
	 $admin_email=get_option( 'admin_email' );	  
	 $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	 $domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	 $noreply_email='noreply@'.$domain_name;	
     
     //header
	 $headers = array();
	 $headers[] = 'From: '. $blogname .' <'.$noreply_email.'>';		
	 $headers[] = 'MIME-Version: 1.0';
	 $headers[] = 'Content-type: text/html; charset=utf-8'; 
     $headers[] = 'X-Mailer: PHP';	
     
	  //Send mail to admin
	  $message  = __( 'A new user has been created', 'wp-event-manager-emails' )."\r\n\r\n";
	  $message .=__( 'Email:', 'wp-event-manager-emails' ) . ' ' .$user_email."\r\n";
	  @wp_mail( $admin_email, __( 'New User Created', 'wp-event-manager-emails' ), $message, $headers );
	        
	  //send login info to new user
	  $subject = 'Welcome to '.$blogname.' - Your login is ready!' ;     
	  ob_start();
      
      get_event_manager_template( 'send-email-to-new-registered-user.php', array(
			'user_name'     => $user_name,
            'user_email'     => $user_email,
            'plaintext_pass'     => $plaintext_pass
		), 'wp-event-manager-emails', EVENT_MANAGER_EMAILS_PLUGIN_DIR . '/templates/' );
        
	  $message = ob_get_contents();
	  ob_end_clean();
	  @wp_mail( $user_email, $subject, $message, $headers );	         
}
endif;

/**
 * Get the default email content
 * @return string
 */
function get_new_event_default_email_content() {
	$message = <<<EOF
Hello

New Event "[event_title]" Submitted Successfully.

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

[event_description]

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

You can contact them directly at: [organizer_email]
EOF;
	return $message;
}

/**
 * Get email content
 * @return string
 */
function get_new_event_email_content() {
	return apply_filters( 'new_event_email_content', get_option( 'new_event_email_content', get_new_event_default_email_content() ) );
}

/**
 * Get the default email subject
 * @return string
 */
function get_new_event_default_email_subject() {
	return __( "New Event \"[event_title]\" Submited Successfully", 'wp-event-manager-emails' );
}

/**
 * Get New event Email Content
 * @return string
 */
function get_new_event_email_subject() {
	return apply_filters( 'new_event_email_subject', get_option( 'new_event_email_subject', get_new_event_default_email_subject() ) );
}


/**
 * Get the published default email content
 * @return string
 */
function get_published_event_default_email_content() {
	$message = <<<EOF
Hello

New Event "[event_title]" Published Successfully.

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

[event_description]

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

You can contact them directly at: [organizer_email]
EOF;
	return $message;
}

/**
 * Get email content
 * @return string
 */
function get_published_event_email_content() {
	return apply_filters( 'published_event_email_content', get_option( 'published_event_email_content', get_published_event_default_email_content() ) );
}

/**
 * Get the default email subject
 * @return string
 */
function get_published_event_default_email_subject() {
	return __( "Event \"[event_title]\" Published Successfully", 'wp-event-manager-emails' );
}

/**
 * Get New event Email Content
 * @return string
 */
function get_published_event_email_subject() {
	return apply_filters( 'published_event_email_subject', get_option( 'published_event_email_subject', get_published_event_default_email_subject() ) );
}

/**
 * Get the expired default email content
 * @return string
 */
function get_expired_event_default_email_content() {
	$message = <<<EOF
Hello

Your Event "[event_title]" Expired.

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

[event_description]

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

You can contact them directly at: [organizer_email]
EOF;
	return $message;
}

/**
 * Get email content
 * @return string
 */
function get_expired_event_email_content() {
	return apply_filters( 'expired_event_email_content', get_option( 'expired_event_email_content', get_expired_event_default_email_content() ) );
}

/**
 * Get the default email subject
 * @return string
 */
function get_expired_event_default_email_subject() {
	return __( "Event \"[event_title]\" Expired", 'wp-event-manager-emails' );
}

/**
 * Get New event Email Content
 * @return string
 */
function get_expired_event_email_subject() {
	return apply_filters( 'expired_event_email_subject', get_option( 'expired_event_email_subject', get_expired_event_default_email_subject() ) );
}

/**
 * Get tags to dynamically replace in the notification email
 * @return array
 */
function get_event_manager_email_tags() {
	$tags = array(
		'organizer_email'   =>      __( 'Organizer Email', 'wp-event-manager-emails' ),	
		'organizer_name'    =>      __( 'Name of the organizer which submitted the event listing', 'wp-event-manager-emails' ),
		'event_type'        =>      __( 'Event Type', 'wp-event-manager-emails' ),
		'user_id'           =>      __( 'Organizer ID', 'wp-event-manager-emails' ),
		'event_id'          =>      __( 'Event ID', 'wp-event-manager-emails' ),
		'event_title'       =>      __( 'Event Title', 'wp-event-manager-emails' ),
		'event_description' =>      __( 'Event Description', 'wp-event-manager-emails' ),
		'event_post_meta'   =>      __( 'Some meta data from the event. e.g. <code>[event_post_meta key="_event_location"]</code>', 'wp-event-manager-emails' )
	);

	return $tags;
}

/**
 * Shortcode handler
 * @param  array $atts
 * @return string
 */
function event_manager_email_shortcode_handler( $atts, $content, $value ) {
	$atts = shortcode_atts( array(
		'prefix' => '',
		'suffix' => ''
	), $atts );

	if ( ! empty( $value ) ) {
		return wp_kses_post( $atts['prefix'] ) . $value . wp_kses_post( $atts['suffix'] );
	}
}

/**
 * Add shortcodes for email content
 * @param  array $data
 */
function event_manager_email_add_shortcodes( $data ) {
	extract( $data );

	$event_title         = strip_tags( get_the_title( $event_id ) );
	$event_description   = get_post_field('post_content', $event_id);
	$dashboard_id        = get_option( 'event_manager_event_dashboard_page_id' );
	$meta_data           = array();
	$organizer_name      = get_organizer_name( $event_id );
    $organizer_email = get_event_organizer_email( $event_id );
	$user_id           = $data['user_id'];

	add_shortcode( 'organizer_email', function( $atts, $content = '' ) use( $organizer_email ) {
		return event_manager_email_shortcode_handler( $atts, $content, $organizer_email );
	} );	
	add_shortcode( 'event_id', function( $atts, $content = '' ) use( $event_id ) {
		return event_manager_email_shortcode_handler( $atts, $content, $event_id );
	} );
	add_shortcode( 'event_title', function( $atts, $content = '' ) use( $event_title ) {
		return event_manager_email_shortcode_handler( $atts, $content, $event_title );
	} );
	add_shortcode( 'event_description', function( $atts, $content = '' ) use( $event_description ) {
		return event_manager_email_shortcode_handler( $atts, $content, $event_description );
	} );
	add_shortcode( 'organizer_name', function( $atts, $content = '' ) use( $organizer_name ) {
		return event_manager_email_shortcode_handler( $atts, $content, $organizer_name );
	} );
	add_shortcode( 'user_id', function( $atts, $content = '' ) use( $user_id ) {
		return event_manager_email_shortcode_handler( $atts, $content, $user_id );
	} );
	add_shortcode( 'event_post_meta', function( $atts, $content = '' ) use( $event_id ) {
		$atts  = shortcode_atts( array( 'key' => '' ), $atts );
		$value = get_post_meta( $event_id, sanitize_text_field( $atts['key'] ), true );
		return event_manager_email_shortcode_handler( $atts, $content, $value );
	} );

	do_action( 'new_event_email_add_shortcodes', $data );
}
?>