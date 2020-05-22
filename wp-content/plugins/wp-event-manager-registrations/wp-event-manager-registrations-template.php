<?php
if ( ! function_exists( 'event_registration_meta' ) ) {

	/**
	 * Output event_registration_meta
	 * @param  object $registration
	 */
	function event_registration_meta( $registration ) {
		if ( 'event_registration' === $registration->post_type ) {
			$meta    = get_post_custom( $registration->ID );		
			$hasmeta = false;
			if ( $meta ) {
				foreach ( $meta as $key => $value ) {
					if ( strpos( $key, '_' ) === 0 ) {
						continue;
					}
					if ( ! $hasmeta ) {
						echo '<dl class="event-registration-meta">';
					}
					$hasmeta = true;
					$field_label= get_event_registration_form_field_lable_by_key($key);	
					if($field_label){
					echo '<dt>' . __( $field_label.' :' , 'wp-event-manager-registrations' )  . '</dt>';
					echo '<dd>' . make_clickable( esc_html( strip_tags( $value[0] ) ) ) . '</dd>';
					}
				}
				if ( $hasmeta ) {
					echo '</dl>';
				}
			}
		}
	}
}

if ( ! function_exists( 'event_registration_content' ) ) {

	/**
	 * Output event_registration_content
	 * @param  object $registration
	 */
	function event_registration_content( $registration ) {
		if ( 'event_registration' === $registration->post_type ) {
			echo apply_filters( 'event_registration_content', wpautop( wptexturize( $registration->post_content ) ), $registration );
		}
	}
}

if ( ! function_exists( 'event_registration_edit' ) ) {

	/**
	 * Output event_registration_edit
	 * @param  object $registration
	 */
	function event_registration_edit( $registration ) {
		get_event_manager_template( 'event-registration-edit.php', array( 'registration' => $registration, 'event_id' => $registration->post_parent ), 'wp-event-manager-registrations', EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR . '/templates/' );
	}
}

if ( ! function_exists( 'event_registration_notes' ) ) {

	/**
	 * Output event_registration_notes
	 * @param  object $registration
	 */
	function event_registration_notes( $registration ) {
		if ( 'event_registration' === $registration->post_type ) {

			$args = array(
				'post_id' => $registration->ID,
				'approve' => 'approve',
				'type'    => 'event_registration',
				'order'   => 'asc',
			);

			remove_filter( 'comments_clauses', array( 'WP_Event_Manager_Registrations_Dashboard', 'exclude_registration_comments' ), 10, 1 );
			$notes = get_comments( $args );
			add_filter( 'comments_clauses', array( 'WP_Event_Manager_Registrations_Dashboard', 'exclude_registration_comments' ), 10, 1 );

			echo '<ul class="event-registration-notes-list">';
			if ( $notes ) {
				foreach( $notes as $note ) {
					?>
					<li rel="<?php echo absint( $note->comment_ID ) ; ?>" class="event-registration-note">
						<div class="event-registration-note-content">
							<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
						</div>
						<p class="event-registration-note-meta">
							<abbr class="exact-date" title="<?php echo $note->comment_date_gmt; ?> GMT"><?php printf( __( 'added %s ago', 'wp-event-manager-registrations' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?></abbr>
							<?php printf( ' ' . __( 'by %s', 'wp-event-manager-registrations' ), $note->comment_author ); ?>
							<a href="#" class="delete_note"><?php _e( 'Delete note', 'wp-event-manager-registrations' ); ?></a>
						</p>
					</li>
					<?php
				}
			}
			echo '</ul>';
			?>
			<div class="event-registration-note-add">
				<p><textarea type="text" name="event_registration_note" class="input-text" cols="20" rows="5" placeholder="<?php esc_attr_e( 'Private note regarding this registration', 'wp-event-manager-registrations' ); ?>"></textarea></p>
				<p><input type="button" data-registration_id="<?php echo absint( $registration->ID ); ?>" class="button" value="<?php esc_attr_e( 'Add note', 'wp-event-manager-registrations' ); ?>" /></p>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'event_registration_header' ) ) {

	/**
	 * Output event_registration_header
	 * @param  object $registration_id
	 */
	function event_registration_header( $registration ) {
		get_event_manager_template( 'event-registration-header.php', array( 'registration' => $registration, 'event_id' => $registration->post_parent ), 'wp-event-manager-registrations', EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR . '/templates/' );
	}
}

if ( ! function_exists( 'event_registration_footer' ) ) {

	/**
	 * Output event_registration_footer
	 * @param  object $registration_id
	 */
	function event_registration_footer( $registration ) {
		get_event_manager_template( 'event-registration-footer.php', array( 'registration' => $registration, 'event_id' => $registration->post_parent ), 'wp-event-manager-registrations', EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR . '/templates/' );
	}
}

if ( ! function_exists( 'get_event_registration_email' ) ) {

	/**
	 * Output get_event_registration_email
	 * @param  object $registration_id
	 */
	function get_event_registration_email( $registration_id ) {
		return get_post_meta( $registration_id, '_attendee_email', true );
	}
}


if ( ! function_exists( 'get_event_registration_avatar' ) ) {

	/**
	 * Output get_event_registration_avatar
	 * Retrieve the avatar for a user who provided a user ID or email address.
	 * Used wordpress in built method : get_avatar( $id_or_email, $size, $default, $alt, $args ); 
	 * @param  object $registration_id
	 */
	function get_event_registration_avatar( $registration_id, $size = 42 ) {
		
		$email     = get_event_registration_email( $registration_id );

		return $email ? get_avatar( $email, $size ) : '';
	}
}


/**
* At Backend Side : Admin 
* Display event registration statuses overview details on the top part of the registration list at admin side only.
* It will also show registration detail for single event.
* It will only show when post type is event_registration.
*/
function display_event_registration_status_overview_details_at_admin() {
    global $post;
    if( isset( $post->post_type) && 'event_registration' == $post->post_type )
    {
       $event_id = isset( $_REQUEST['_event_listing']) ? $_REQUEST['_event_listing'] : null;   
       $total_registrations = 0;  
       $total_new_registrations = 0;
       $total_confirm_registrations = 0;
       $total_waiting_registrations = 0;
       $total_cancelled_registrations = 0;
       $total_archived_registrations = 0;
       $event_link='';
       $event_start_date ='';
	   $event_start_time = '';
	   $event_end_date = '';
	   $event_end_time = '';
	   $event_location = '';
       
       if(!empty($event_id) && isset($event_id))
       {
          
          $event_link='<a href="' . get_edit_post_link( $event_id ) . '" title="' . esc_attr__( 'Edit Event', 'event-manager-registrations' ) . '">' . get_post_meta($event_id,'_event_title',true). '</a>';
         
          $event_start_date = get_post_meta($event_id,'_event_start_date',true);
	      $event_start_time = get_post_meta($event_id,'_event_start_time',true);
	      $event_end_date = get_post_meta($event_id,'_event_end_date',true);
	      $event_end_time = get_post_meta($event_id,'_event_end_time',true); 
	      $event_location = get_post_meta($event_id,'_event_venue_name',true); 
	            
         $total_registrations= get_event_registration_count( $event_id );
         foreach(get_event_registration_statuses() as $registration_status => $registration_status_lable )
		 {
				       
		   if($registration_status == 'new'){
			   $total_new_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
  			}
  			if($registration_status == 'confirmed'){
  				$total_confirm_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
    					
    		if($registration_status == 'waiting'){
    			$total_waiting_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
    		}	
    			
    		if($registration_status == 'cancelled'){
    			$total_cancelled_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}
    					
    		if($registration_status == 'archived'){
    			$total_archived_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
		 }
       }
       else
       {
           $count_posts = wp_count_posts( 'event_registration' );
		   $args = array( 'post_type' => 'event_registration','post_status'   => 'publish' );
		   $query = new WP_Query( $args );	
		   $total_registrations = $count_posts->new + $count_posts->confirmed + $count_posts->waiting + $count_posts->cancelled; 
		   $total_new_registrations =$count_posts->new;	
		   $total_confirm_registrations =$count_posts->confirmed;	
		   $total_waiting_registrations =$count_posts->waiting;
		   $total_cancelled_registrations =$count_posts->cancelled;
		   $total_archived_registrations =$count_posts->archived;	
		   
       }
        
        get_event_manager_template('admin-registration-status-overview-detail.php',
                                                                        array( 'event_id'=>$event_id,
                                                                               'event_link'=>$event_link,
                                                                               'event_start_date'=>$event_start_date,
                                                                               'event_start_time'=>$event_start_time,
                                                                               'event_end_date'=>$event_end_date,
                                                                               'event_end_time'=>$event_end_time,
                                                                               'event_location'=>$event_location,
                                                                               'total_registrations'=>$total_registrations,
                                                                               'total_new_registrations'=>$total_new_registrations,
                                                                               'total_confirm_registrations'=>$total_confirm_registrations,
                                                                               'total_waiting_registrations'=>$total_waiting_registrations,
                                                                               'total_cancelled_registrations'=>$total_cancelled_registrations,
                                                                               'total_archived_registrations'=>$total_archived_registrations
                                                                               ),'wp-event-manager-registrations',EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR. '/templates/' );
       
    }
}
add_action( 'admin_notices', 'display_event_registration_status_overview_details_at_admin',99 );


/**
* At Frontend Side
* Display event registration statuses overview details on the top part of the event manager' event dashboard and  single event dashboard'.
* Registration statuses details e.g new, confirmed, cancelled etc.
*/
function display_event_registration_status_overview_details($events)
{
   
   $event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : null;
   $total_registrations = 0;  
   $total_new_registrations = 0;
   $total_confirm_registrations = 0;
   $total_waiting_registrations = 0;
   $total_cancelled_registrations = 0;
   $total_archived_registrations = 0;

  
   if(!empty($event_id) && isset($event_id))
   {
         $total_registrations= get_event_registration_count( $event_id );
        foreach(get_event_registration_statuses() as $registration_status => $registration_status_lable )
		{
				       
		   if($registration_status == 'new'){
			   $total_new_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
  			}
  			if($registration_status == 'confirmed'){
  				$total_confirm_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
    					
    		if($registration_status == 'waiting'){
    			$total_waiting_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
    		}	
    			
    		if($registration_status == 'cancelled'){
    			$total_cancelled_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}
    					
    		if($registration_status == 'archived'){
    			$total_archived_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
		}
   }
   else   
   {
    
     $events = get_event_registration_events_by_organizer();             
    foreach($events as $events_key => $events_value)
	{
         $event_id=$events_value->ID;                  
         $total_registrations += get_event_registration_count( $event_id );
        foreach(get_event_registration_statuses() as $registration_status => $registration_status_lable )
		{
				       
		   if($registration_status == 'new'){
			   $total_new_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
  			}
  			if($registration_status == 'confirmed'){
  				$total_confirm_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
    					
    		if($registration_status == 'waiting'){
    			$total_waiting_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));	
    		}	
    			
    		if($registration_status == 'cancelled'){
    			$total_cancelled_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}
    					
    		if($registration_status == 'archived'){
    			$total_archived_registrations +=get_event_registration_status_count($event_id,array($registration_status=>$registration_status));		
    		}	
		}
    }
   }
   get_event_manager_template('registration-status-overview-detail.php',array( 'total_registrations'=>$total_registrations,
                                                                               'total_new_registrations'=>$total_new_registrations,
                                                                               'total_confirm_registrations'=>$total_confirm_registrations,
                                                                               'total_waiting_registrations'=>$total_waiting_registrations,
                                                                               'total_cancelled_registrations'=>$total_cancelled_registrations,
                                                                               'total_archived_registrations'=>$total_archived_registrations
                                                                               ),'wp-event-manager-registrations',EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR. '/templates/' );
}

 //show registration status overview details on event manager dashboard
add_action('event_manager_event_dashboard_before','display_event_registration_status_overview_details',1);

//show registration status overview details on single event registration dashboard
add_action('single_event_registration_dashboard_before','display_event_registration_status_overview_details');

