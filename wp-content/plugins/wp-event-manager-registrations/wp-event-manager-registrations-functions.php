<?php
if ( ! function_exists( 'create_event_registration' ) ) {
	/**
	 * Create a new event registration
	 * @param  int $event_id
	 * @param  string $attendee_name	
	 * @param  string $attendee_email
	 * @param  array  $meta
	 * @param  bool $notification
	 * @return int|bool success
	 */
	function create_event_registration( $event_id, $registration_fields, $meta = array(), $notification = true, $source = '' ) {
		$event = get_post( $event_id );
		$user    =  wp_get_current_user();

		if ( ! $event || $event->post_type !== 'event_listing' ) {
			return false;
		}
		
		if (empty($registration_fields))
		{
			$attendee_name  = $user->display_name;
			$attendee_email = $user->user_email;
		}
		else{
		    if(!empty($registration_fields['attendee_name']))
		        $attendee_name=$registration_fields['attendee_name'];
			else if(!empty($registration_fields['full-name']))
				$attendee_name=$registration_fields['full-name'];
			else if(!empty($registration_fields['first-name']) && !empty($registration_fields['last-name']))
				$attendee_name=$registration_fields['first-name']. " " . $registration_fields['last-name'];
			else if(!empty($registration_fields['first-name']) )
				$attendee_name=$registration_fields['first-name'];
		    else if(!empty($registration_fields['name']) )
				$attendee_name=$registration_fields['name'];
			else if($registration_fields[0])
				$attendee_name =$registration_fields[0];
			else
				$attendee_name = array_shift( $registration_fields );
							
			//$registration_fields=apply_filters( 'event_registration_form_fields_email', $registration_fields );
			if(!empty($registration_fields['attendee_email'])){	 
			    $attendee_email=$registration_fields['attendee_email'];
			}
			else if(!empty($registration_fields['email-address']))
			{
				$attendee_email=$registration_fields['email-address'];
			}
			else if(!empty($registration_fields['email']))
			{
				$attendee_email=$registration_fields['email'];
			}
			else if(!empty($registration_fields['your-email']))
			{
				$attendee_email=$registration_fields['your-email'];
			}
			else
			{
				$attendee_email =$user->user_email;
			}
								
		}
		$registration_data = array(
			'post_title'     => wp_kses_post( $attendee_name ),			
			'post_status'    => current( array_keys( get_event_registration_statuses() ) ),
			'post_type'      => 'event_registration',
			'comment_status' => 'closed',
			'post_author'    => $event->post_author,
			'post_parent'    => $event_id
		);
		$registration_id = wp_insert_post( $registration_data );

		if ( $registration_id ) {
			update_post_meta( $registration_id, '_event_registered_for', $event->post_title );
			update_post_meta( $registration_id, '_attendee_email', $attendee_email );
			update_post_meta( $registration_id, '_attendee_user_id', get_current_user_id() );
			update_post_meta( $registration_id, '_rating', 0 );
			update_post_meta( $registration_id, '_registration_source', $source );

			if ( $meta ) {
				foreach ( $meta as $key => $value ) {
					update_post_meta( $registration_id, $key, $value );
				}
			}
            
			if ( $notification ) {
			    //send email to attendee
			    $method = get_event_registration_method( $event_id );
			    
			    if ( "email" === $method->type ) {
			        $send_to = $method->raw_email;
			    } elseif ( $event->post_author ) {
			        $send_to = $event->post_author;
			    } else {
			        $send_to = '';
			    }
			    
			    //send email to organizer
			    $organizer_name 	= get_organizer_name( $event );
			    $organizer_email 	= get_event_organizer_email( $event );
			    if ( $organizer_email  ) {
			        $existing_shortcode_tags = $GLOBALS['shortcode_tags'];
			        remove_all_shortcodes();
			        event_registration_email_add_shortcodes( array(
			            'registration_id'       => $registration_id,
			            'event_id'              => $event_id,
			            'user_id'               => get_current_user_id(),
			            'attendee_name'         => $attendee_name,
			            'attendee_email'        => $attendee_email,
			            'meta'                  => $meta
			        ) );
			        $subject = do_shortcode( get_event_registration_email_subject() );
			        $message = do_shortcode( get_event_registration_email_content() );
			        $message = str_replace( "\n\n\n\n", "\n\n", implode( "\n", array_map( 'trim', explode( "\n", $message ) ) ) );
			        $is_html = ( $message != strip_tags( $message ) );
			        // Does this message contain formatting already?
			        if ( $is_html && ! strstr( $message, '<p' ) && ! strstr( $message, '<br' ) ) {
			            $message = nl2br( $message );
			        }
			        
			        $GLOBALS['shortcode_tags'] = $existing_shortcode_tags;
			        wp_mail(
			            apply_filters( 'create_event_registration_organizer_notification_recipient', $organizer_email, $event_id, $registration_id ),
			            apply_filters( 'create_event_registration_organizer_notification_subject', $subject, $event_id, $registration_id ),
			            apply_filters( 'create_event_registration_organizer_notification_message', $message ),
			            apply_filters( 'create_event_registration_organizer_notification_headers', '', $event_id, $registration_id )
			            );
			    }
			    
			    //send email to attendee
			    
			    if ( $attendee_email  ) {
			        $existing_shortcode_tags = $GLOBALS['shortcode_tags'];
			        remove_all_shortcodes();
			        event_registration_email_add_shortcodes( array(
			            'registration_id'      => $registration_id,
			            'event_id'              => $event_id,
			            'user_id'             => get_current_user_id(),
			            'attendee_name'      => $attendee_name,
			            'attendee_email'     => $attendee_email,
			            'meta'                => $meta
			        ) );
			        $subject = do_shortcode( get_event_registration_attendee_email_subject() );
			        $message = do_shortcode( get_event_registration_attendee_email_content() );
			        $message = str_replace( "\n\n\n\n", "\n\n", implode( "\n", array_map( 'trim', explode( "\n", $message ) ) ) );
			        $is_html = ( $message != strip_tags( $message ) );
			        
			        // Does this message contain formatting already?
			        if ( $is_html && ! strstr( $message, '<p' ) && ! strstr( $message, '<br' ) ) {
			            $message = nl2br( $message );
			        }
			        $GLOBALS['shortcode_tags'] = $existing_shortcode_tags;
			        
			        wp_mail(
			            apply_filters( 'create_event_registration_attendee_notification_recipient', $attendee_email, $event_id, $registration_id ),
			            apply_filters( 'create_event_registration_attendee_notification_subject', $subject, $event_id, $registration_id ),
			            apply_filters( 'create_event_registration_attendee_notification_message', $message ),
			            apply_filters( 'create_event_registration_attendee_notification_headers', '', $event_id, $registration_id )
			            );       
			    }
			}
			
			return $registration_id;
		}

		return false;
	}
}

if ( ! function_exists( 'get_event_registration_count' ) ) {
	/**
	 * Get number of registrations for a event
	 * @param  int $event_id
	 * @return int
	 */
	function get_event_registration_count( $event_id ) {
		return sizeof( get_posts( array(
			'post_type'      => 'event_registration',
			'post_status'    => array_merge( array_keys( get_event_registration_statuses() ), array( 'publish' ) ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_parent'    => $event_id
		) ) );
	}
}

if ( ! function_exists( 'get_event_registration_status_count' ) ) {
	/**
	 * Get number of perticular registration status for a event 
	 * @param  int $event_id
	 * @param  string $registration_status
	 * @return int
	 */
	function get_event_registration_status_count( $event_id, $registration_status ) {
		return sizeof( get_posts( array(
			'post_type'      => 'event_registration',
			'post_status'    => array_merge(array_keys($registration_status), array( 'publish' ) ),
			'posts_per_page' => -1,			
			'post_parent'    => $event_id
		) ) );
	}
}

if ( ! function_exists( 'get_event_registration_events_by_organizer' ) ) {
	/**
	 * Get number of events of the organizer
	 *
	 * @return event listings
	 */
	function get_event_registration_events_by_organizer( ) {
		return  get_posts( array(
			'post_type'           => 'event_listing',
			'post_status'         => array( 'publish', 'expired', 'pending' ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => -1,		
			'orderby'             => 'date',
			'order'               => 'desc',
			'author'              => get_current_user_id()
		) );
	}
}

if ( ! function_exists( 'user_has_registered_for_event' ) ) {
	/**
	 * See if a user has already appled for a event
	 * @param  int $user_id
	 * @param  int $event_id
	 * @return bool
	 */
	function user_has_registered_for_event( $user_id, $event_id ) {
		if ( ! $user_id ) {
			return false;
		}
		return sizeof( get_posts( array(
			'post_type'      => 'event_registration',
			'post_status'    => array_merge( array_keys( get_event_registration_statuses() ), array( 'publish' ) ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'post_parent'    => $event_id,
			'meta_query'     => array(
				array(
					'key' => '_attendee_user_id',
					'value' => absint( $user_id )
				)
			)
		) ) );
	}
}

/**
 * Event Registration Statuses
 * @return array
 */
function get_event_registration_statuses() {
	return apply_filters( 'event_registration_statuses', array(
		'new'         => _x( 'New', 'event_registration', 'wp-event-manager-registrations' ),
		'confirmed' => _x( 'Confirmed', 'event_registration', 'wp-event-manager-registrations' ),
		'waiting'       => _x( 'Waiting', 'event_registration', 'wp-event-manager-registrations' ),
		'cancelled'       => _x( 'Cancelled', 'event_registration', 'wp-event-manager-registrations' ),
		'archived'       => _x( 'Archived', 'event_registration', 'wp-event-manager-registrations' )
		
	) );
}

/**
 * Get default form fields
 * @return array
 */
function get_event_registration_default_form_fields() {
	$default_fields = array(
		'attendee_name' => array(
			'label'       => __( 'Full name', 'wp-event-manager-registrations' ),
			'type'        => 'text',
			'required'    => true,
			'placeholder' => '',
			'priority'    => 1,
			'rules'       => array( 'from_name' )
		),
		'attendee_email' => array(
			'label'       => __( 'Email address', 'wp-event-manager-registrations' ),
			'description' => '',
			'type'        => 'text',
			'required'    => true,
			'placeholder' => '',
			'priority'    => 2,
			'rules'       => array( 'from_email' )
		)    
	
	);

	return $default_fields;
}

/**
 * Get the form fields for the registration form
 * @return array
 */
function get_event_registration_form_fields( $suppress_filters = true ) {
	$option = get_option( 'event_registration_form_fields', get_event_registration_default_form_fields() );
	return $suppress_filters ? $option : apply_filters( 'event_registration_form_fields', $option );
}

/**
 * Get the default email content
 * @return string
 */
function get_event_registration_default_email_content() {
	$message = <<<EOF
Hello

A attendee ([from_name]) has submitted their registration for the event "[event_title]".

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

[message]

=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

[meta_data]

[event_dashboard_url prefix="You can view this and any other registrations here: "]

You can contact them directly at: [from_email]
EOF;
	return $message;
}

/**
 * Get email content
 * @return string
 */
function get_event_registration_email_content() {
	return apply_filters( 'event_registration_email_content', get_option( 'event_registration_email_content', get_event_registration_default_email_content() ) );
}

/**
 * Get the default email subject
 * @return string
 */
function get_event_registration_default_email_subject() {
	return __( "New event registration for [event_title]", 'wp-event-manager-registrations' );
}

/**
 * Get email content
 * @return string
 */
function get_event_registration_email_subject() {
	return apply_filters( 'event_registration_email_subject', get_option( 'event_registration_email_subject', get_event_registration_default_email_subject() ) );
}

/**
 * Get attendee email content
 * @return string
 */
function get_event_registration_attendee_email_content() {
	return apply_filters( 'event_registration_attendee_email_content', get_option( 'event_registration_attendee_email_content' ) );
}

/**
 * Get the default email subject
 * @return string
 */
function get_event_registration_default_attendee_email_subject() {
	return __( "Your event registration for [event_title]", 'wp-event-manager-registrations' );
}

/**
 * Get email content
 * @return string
 */
function get_event_registration_attendee_email_subject() {
	return apply_filters( 'event_registration_attendee_email_subject', get_option( 'event_registration_attendee_email_subject', get_event_registration_default_attendee_email_subject() ) );
}

/**
 * Get tags to dynamically replace in the notification email
 * @return array
 */
function get_event_registration_email_tags() {
	$tags = array(
		'from_name'           => __( 'Attendee name', 'wp-event-manager-registrations' ),
		'from_email'          => __( 'Attendee Email', 'wp-event-manager-registrations' ),		
		'meta_data'           => __( 'All custom form fields in list format', 'wp-event-manager-registrations' ),
		'registration_id'     => __( 'Registration ID', 'wp-event-manager-registrations' ),
		'user_id'             => __( 'User ID of attendee', 'wp-event-manager-registrations' ),
		'event_id'            => __( 'Event ID', 'wp-event-manager-registrations' ),
		'event_title'         => __( 'Event Title', 'wp-event-manager-registrations' ),
		'event_dashboard_url' => __( 'URL to the frontend event dashboard page', 'wp-event-manager-registrations' ),
	    'event_url'           => __( 'URL to the  current event', 'wp-event-manager-registrations' ),
		'organizer_name'      => __( 'Name of the organizer which submitted the event listing', 'wp-event-manager-registrations' ),
		'event_post_meta'     => __( 'Some meta data from the event. e.g. <code>[event_post_meta key="_event_location"]</code>', 'wp-event-manager-registrations' )
	);

	foreach ( get_event_registration_form_fields() as $key => $field ) {
		if ( isset( $tags[ $key ] ) ) {
			continue;
		}
		if ( in_array( 'from_name', $field['rules'] ) || in_array( 'from_email', $field['rules'] ) ) {
			continue;
		}
		$tags[ $key ] = sprintf( __( 'Custom field named "%s"', 'wp-event-manager-registrations' ), $field['label'] );
	}

	return $tags;
}

/**
 * Shortcode handler
 * @param  array $atts
 * @return string
 */
function event_registration_email_shortcode_handler( $atts, $content, $value ) {
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
function event_registration_email_add_shortcodes( $data ) {
	extract( $data );

	$event_title         = strip_tags( get_the_title( $event_id ) );
	$dashboard_id      = get_option( 'event_manager_event_dashboard_page_id' );
	$event_dashboard_url = $dashboard_id ? htmlspecialchars_decode( add_query_arg( array( 'action' => 'show_registrations', 'event_id' => $event_id ), get_permalink( $dashboard_id ) ) ) : '';
	$event_url 			= get_permalink($event_id);
	$meta_data         = array();
	$organizer_name      = get_organizer_name( $event_id );
	$registration_id    = $data['registration_id'];
	$user_id           = $data['user_id'];

	add_shortcode( 'from_name', function( $atts, $content = '' ) use( $attendee_name ) {
		return event_registration_email_shortcode_handler( $atts, $content, $attendee_name );
	} );
	add_shortcode( 'from_email', function( $atts, $content = '' ) use( $attendee_email ) {
		return event_registration_email_shortcode_handler( $atts, $content, $attendee_email );
	} );	
	add_shortcode( 'event_id', function( $atts, $content = '' ) use( $event_id ) {
		return event_registration_email_shortcode_handler( $atts, $content, $event_id );
	} );
	add_shortcode( 'event_title', function( $atts, $content = '' ) use( $event_title ) {
		return event_registration_email_shortcode_handler( $atts, $content, $event_title );
	} );
	add_shortcode( 'event_dashboard_url', function( $atts, $content = '' ) use( $event_dashboard_url ) {
		return event_registration_email_shortcode_handler( $atts, $content, $event_dashboard_url );
	} );
    add_shortcode( 'event_url', function( $atts, $content = '' ) use( $event_url ) {
        return event_registration_email_shortcode_handler( $atts, $content, $event_url );
    } );
	add_shortcode( 'organizer_name', function( $atts, $content = '' ) use( $organizer_name ) {
		return event_registration_email_shortcode_handler( $atts, $content, $organizer_name );
	} );
	add_shortcode( 'registration_id', function( $atts, $content = '' ) use( $registration_id ) {
		return event_registration_email_shortcode_handler( $atts, $content, $registration_id );
	} );
	add_shortcode( 'user_id', function( $atts, $content = '' ) use( $user_id ) {
		return event_registration_email_shortcode_handler( $atts, $content, $user_id );
	} );
	add_shortcode( 'event_post_meta', function( $atts, $content = '' ) use( $event_id ) {
		$atts  = shortcode_atts( array( 'key' => '' ), $atts );
		$value = get_post_meta( $event_id, sanitize_text_field( $atts['key'] ), true );
		return event_registration_email_shortcode_handler( $atts, $content, $value );
	} );

	foreach ( get_event_registration_form_fields() as $key => $field ) {
		if (  in_array( 'from_name', $field['rules'] ) || in_array( 'from_email', $field['rules'] ) ) {
			continue;
		}
		$value = isset( $meta[ $key  ] ) ? $meta[ $key  ] : '';
		$meta_data[ $key ] = $value;

		add_shortcode( $key, function( $atts, $content = '' ) use( $value ) {
			return event_registration_email_shortcode_handler( $atts, $content, $value );
		} );
	}

	$meta_data         = array_filter( $meta_data );
	$meta_data_strings = array();
	foreach ( $meta_data as $label => $value ) {
		$meta_data_strings[] = $label . ': ' . $value;
	}
	$meta_data_strings = implode( "\n", $meta_data_strings );

	add_shortcode( 'meta_data', function( $atts, $content = '' ) use( $meta_data_strings ) {
		return event_registration_email_shortcode_handler( $atts, $content, $meta_data_strings );
	} );

	do_action( 'event_registration_email_add_shortcodes', $data );
}

/**
* Update the post meta of registration post type : checkin or undo check in 
* This method used at admin and frontend side to update post meta key : _check_in via ajax.
*/
function update_event_registration_checkin_data()
{
	$check_in_value = $_POST['check_in_value'];
	$registration_id = $_POST['registration_id'];
	
	if(isset($registration_id) && isset($check_in_value)){
		update_post_meta($registration_id ,'_check_in', $check_in_value);	
		echo get_total_checkedin_by_event_id();
	}
	wp_die();
}
add_action( 'event_manager_ajax_update_event_registration_checkin_data', 'update_event_registration_checkin_data'  );	

/**
* Get all the checkin from all registration and perticular event checkin
* @ retrun total check in 
*/
function get_total_checkedin_by_event_id()
{
    $total_checkedin = 0;
    if(isset($_REQUEST['event_id']) || isset($_REQUEST['_event_listing']) ){
        $event_id = isset($_REQUEST['event_id'] ) ? $_REQUEST['event_id'] : $_REQUEST['_event_listing'] ;
        $args = array(
        'post_type'     => 'event_registration',
        'post_status'   => 'any',
    
        'post_parent'   => $event_id,
        'meta_query'    => array(
                                array(
                                    'key'   => '_check_in',
                                    'value' => true
                                ))
        );
        $registrations = get_posts($args);
        $total_checkedin += count($registrations);
    }
   
    else{
            $events = get_event_registration_events_by_organizer();             
            foreach($events as $events_key => $events_value)
        	{
                 $event_id=$events_value->ID;                  
                 $args = array(
                'post_type'     => 'event_registration',
                'post_status'   => 'any',
                'post_parent'   => $event_id,
                'meta_query'    => array(
                                        array(
                                            'key'   => '_check_in',
                                            'value' => true
                                        )
                                    )
            );
            $registrations = get_posts($args);
            $total_checkedin += count($registrations);
            }
   }
   
   return $total_checkedin;
}


if ( ! function_exists( 'get_event_registration_form_field_lable_by_key' ) ) {
	/**
	 * Get the label of the field by key
	 * @param  string $key	
	 * @return string
	 */
	function get_event_registration_form_field_lable_by_key( $key ) {
	     $keyValueArray =  apply_filters( 'event_regitration_meta_fields', get_event_registration_form_fields(true) );
	   
		if(!empty($keyValueArray[$key]))
		{ 
	    	$fields = $keyValueArray[$key];	
		    return $fields['label'];
		}
	}
}

//update product stock when registration delete
function wp_delete_registration_post( $post_id ){
    
    do_action('wp_trash_registration_post_before', $post_id);
    //get delete post type
    $post_type = get_post_type( $post_id );
 
    //check post type is event_registration
    if ( $post_type != 'event_registration' ) return;
    
    //get total ticket of selected registration
    $total_ticket=get_post_meta( $post_id, '_total_ticket', true);
  
    //get product id of selected registration
    $product_id=get_post_meta($post_id, '_ticket_id', true);
    
    //get remaining stock of product
    $quantity=get_post_meta($product_id, '_stock', true);
    
    //add total ticket into product total stock
    $quantity=(int)$quantity+(int)$total_ticket;
    
    // 1. Updating the stock quantity
    update_post_meta($product_id, '_stock', $quantity);
    
    // 2. Updating the stock quantity
    update_post_meta( $product_id, '_stock_status', 'instock' );
    
    do_action('wp_trash_registration_post_after', $post_id);
}
add_action( 'before_delete_post', 'wp_delete_registration_post' );


//Change registration status when order status is change
add_action( 'woocommerce_order_status_changed', 'action_woocommerce_order_status_changed', 10, 3 );

// define the action_woocommerce_order_status_change callback
if(!function_exists('action_woocommerce_order_status_changed')){
    /**
     * Update all registration status of related order
     * When any order status is change
     * We need to update registration status of that order
     * This is very important when order status change
     * @parma $order_id, $from_status, $to_status
     **/
    function action_woocommerce_order_status_changed( $order_id, $from_status, $to_status ) {
        $registration_status = 'new';
        switch ( $to_status ) {
            case 'on-hold':
                $registration_status = 'waiting';
                break;
            case 'processing':
                $registration_status = 'new';
                break;
            case 'completed':
                $registration_status = 'confirmed';
                break;
            case 'cancelled':
                $registration_status = 'cancelled';
                break;
            case 'refunded':
                $registration_status = 'cancelled';
                break;
            case 'failed':
                $registration_status = 'cancelled';
                break;
            case 'pending':
                $registration_status = 'waiting';
                break;
            default:
                $registration_fields = 'new';
                break;
        }
        //get all registration_id from order_id for that order
        $args = array(
            'post_type'		=>	'event_registration',
            'meta_query'	=>	array(
                array(
                    'key'   => '_order_id',
                    'value'	=>	$order_id
                )
            )
        );
        $query_post = new WP_Query( $args );
        
        //update status of all registration id depends on order status
        if( $query_post->have_posts() ) {
            while( $query_post->have_posts() ) {
                $query_post->the_post();
                $update_post = array(
                    'ID'           => get_the_ID(),
                    'post_status'   => $registration_status,
                );
                
                // Update the registration status
                wp_update_post( $update_post );
            }
            wp_reset_postdata();
        }
    }
}
