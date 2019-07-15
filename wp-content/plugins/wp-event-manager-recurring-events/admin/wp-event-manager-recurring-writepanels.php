<?php 
/*
 * This file use to cretae fields of gam event manager at admin side.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_Event_Manager_event_recurring_Writepanels {
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_filter( 'event_manager_event_listing_data_fields', array($this ,'event_listing_event_recurring_fields'),100  );
        add_action( 'admin_enqueue_scripts', array($this ,'admin_enqueue_script' ) );
        
        if(!get_option('event_manager_recurring_events')){
            add_action( 'event_manager_save_event_listing', array($this , 'update_event_recurrence' ),99,2);
        }
        
        //Our class extends the WP_List_Table class, so we need to make sure that it's there
        if(!class_exists('WP_List_Table')){
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }
        
        // Create menu for recurring event page
        if(get_option('event_manager_recurring_events')){
            add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
        }
        
        //Call to ajax for recurring event from admin side
        add_action( 'wp_ajax_create_event_recurring', array( $this, 'create_event_recurring')  );
        
        //create serach for event-listing for recurring event list
        add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_parents_posts' ) );
        add_filter( 'request', array( $this, 'request' ) );
        
        //create settings for duplicate event recurreing or schedual cron
        add_filter( 'event_manager_settings', array( $this, 'event_manager_recurring_settings' ), 99 );
        
	}
	
    /**
	 * event recurring setting function.
	 *
	 * @access public
	 * @return array
	 */
	public function event_manager_recurring_settings($fields) {
	    $fields['attendee_inforatmation'] = array(
	        __( 'Event Recurring', 'wp-event-manager-attendee-information' ),
	        array(
	            array(
	                'name' 		=> 'event_manager_recurring_events',
	                'std' 		=> '1',
	                'label'      => __( 'Duplicate Recurring Events', 'wp-event-manager-recurring' ),
	                
	                'cb_label'   => __( 'Enable Duplicate Recurring Events', 'wp-event-manager-recurring' ),
	                
	                'desc'       => __( 'If enable, recurring events creates duplicate events, else Cron scheduals and update current event after event end.', 'wp-event-manager-recurring' ),
	                
	                'type'       => 'checkbox',
	            )
	        )
	    );
	    
	    return $fields;
	}
	
	/**
	 * Filter for recurring event list
	 */
	public function restrict_manage_parents_posts() {
	    global $typenow, $wp_query, $wpdb;
	    
	    if ( 'event_listing' != $typenow ) {
	        return;
	    }
	    
	    // Customers
	    ?>
		<select id="dropdown_event_listings" name="post_parent">
			<option value=""><?php _e( 'Parent events', 'wp-event-manager' ) ?></option>
			<?php
				$events_with_registrations = $wpdb->get_col( "SELECT DISTINCT post_parent FROM {$wpdb->posts} WHERE post_type = 'event_listing';" );
				$current                = isset( $_GET['post_parent'] ) ? $_GET['post_parent'] : 0;
				foreach ( $events_with_registrations as $event_id ) {
					if ( ( $title = get_the_title( $event_id ) ) && $event_id ) {
						echo '<option value="' . $event_id . '" ' . selected( $current, $event_id, false ) . '">' . $title . '</option>';
					}
				}
			?>
		</select>
		<?php
	}

	/**
 	 * modify what recurring event list are shown
 	 */
	  public function request( $vars ) {
		global $typenow, $wp_query;

		if ( $typenow == 'event_listing' && isset($_GET['post_parent'] )  && $_GET['post_parent'] > 0 ) {
			$vars['post_parent'] = (int) $_GET['post_parent'];
		}
		return $vars;
	}
	
	/**
	 * admin_enqueue_script load admin side enqueue script
	 *
	 **/
	public function admin_enqueue_script(){
				wp_register_script('wp-event-manager-recurring-events-admin-script', EVENT_MANAGER_RECURRING_PLUGIN_URL . '/assets/js/admin.js',array ('jquery'), false, false);
		//localize javascript file
		wp_localize_script( 'wp-event-manager-recurring-events-admin-script', 'event_manager_recurring_events', array(
		'every_day' 	 => __( 'day(s)' , 'wp-event-manager-recurring'),
		'every_week' 	 => __( 'week(s) on' , 'wp-event-manager-recurring'),
		'every_month' 	 => __( 'month(s) on' , 'wp-event-manager-recurring'),
		'ofthe_month' 	 => __( 'of the month(s)' , 'wp-event-manager-recurring'),
		'every_year' 	 => __( 'year(s) on' , 'wp-event-manager-recurring'),
		'ajax_url'       => admin_url('admin-ajax.php'),
		)
		);
		//always enqueue the script after registering or nothing will happen
		wp_enqueue_script('wp-event-manager-recurring-events-admin-script');
	
	}
	
	/**
	 * event_listing_event_recurring_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public static function event_listing_event_recurring_fields( $fields ) {
		
		$fields['_event_recurrence'] = array(
				'label'		=> __( 'Event Recurrence', 'wp-event-manager-recurring' ),
				'type'  	=> 'select',
				'default'  	=> 'no',
				'priority'  => 50,
				'required'	=> true,
				'options'  	=> array(
									'no' 		    => __( 'Dont\'t repeat','wp-event-manager-recurring'),
									'daily'         => __( 'Daily','wp-event-manager-recurring'),
									'weekly'        => __( 'Weekly','wp-event-manager-recurring'),
									'monthly'       => __( 'Monthly','wp-event-manager-recurring'),
									'yearly'        => __( 'Yearly','wp-event-manager-recurring')
							)
		);
		
		$fields['_recure_every'] = array(
				'label'			=> __( 'Repeat Every', 'wp-event-manager-recurring' ),
				'type'  		=> 'number',
				'default'  		=> '',
				'priority'    	=> 51,
				'placeholder'	=> '',
				'required'		=> true,
				'description'	=> ' '
				);
		$fields['_recure_time_period'] =  array(
								'label'		  => __('on the','wp-event-manager-recurring'),
								'type'        => 'radio',
								'required'    => true,
								'priority'    => 52,
								'options'=> array(
										'same_time'=> __( 'same day','wp-event-manager-recurring'),
										'specific_time'=> __( 'specific day','wp-event-manager-recurring')
								)
						);
						
		$fields['_recure_month_day'] =  array(
								'label'		  => __('Day Number','wp-event-manager-recurring'),
								'type'        => 'select',
								'required'    => true,
								'priority'    => 53,
								'options'=> array(
										'first'		=> __( 'First','wp-event-manager-recurring'),
										'second'	=> __( 'Second','wp-event-manager-recurring'),
										'third'		=> __( 'Third','wp-event-manager-recurring'),
										'fourth'	=> __( 'Fourth','wp-event-manager-recurring'),
										'last'		=> __( 'Last','wp-event-manager-recurring')
										
								)
						);
		$fields['_recure_weekday'] = array(
								'label'		  => __('Day Name','wp-event-manager-recurring'),
								'type'        => 'select',
								'required'    => true,
								'priority'    => 54,
								'options'=> array(
										'sun'=> __( 'Sunday','wp-event-manager-recurring'),
										'mon'=> __( 'Monday','wp-event-manager-recurring'),
										'tue'=> __( 'Tuesday','wp-event-manager-recurring'),
										'wen'=> __( 'Wednesday','wp-event-manager-recurring'),
										'thu'=> __( 'Thursday','wp-event-manager-recurring'),
										'fri'=> __( 'Friday','wp-event-manager-recurring'),
										'sat'=> __( 'Saturday','wp-event-manager-recurring'),
								)
						);
						
		
		
				
		$fields['_recure_untill'] = array(
										'label'=> __( 'Repeat untill', 'wp-event-manager-recurring' ),
										'type'  => 'date',
										'default'  => '',
										'priority'    => 55,
										'placeholder'	=> '',
										'required'=>true,
									);
	return $fields;
    }
    
    /**
     * create_event_recurring function to duplicate selected events
     *
     * @access public
     * @return json_array
     */
    public function create_event_recurring()
    {
    	global $wpdb;
    	if(isset($_POST['event_id']) && isset( $_POST['start_date']) && isset($_POST['end_date']) ){
    		$event_id=	$_POST['event_id'];
	        $start_date= $_POST['start_date'];
	        $end_date= $_POST['end_date'];
	        
	        
	        $event = get_post( $event_id);
	        $recurrece_frequency = get_post_meta( $event_id ,'_event_recurrence',true);
	        
	        $recure_every = get_post_meta( $event_id ,'_recure_every',true);
	        $recure_weekday = get_post_meta( $event_id ,'_recure_weekday',true);
	        $recure_month_day = get_post_meta( $event_id ,'_recure_month_day',true);
	        $recure_untill = strtotime(get_post_meta( $event_id ,'_recure_untill',true));
	        
	        $start_time = get_post_meta( $event_id, '_event_start_time',true );
	        $end_time = get_post_meta( $event_id, '_event_end_time',true );
	        
	        $registration_expiry_date=get_post_meta($event_id, '_event_registration_deadline', true);
	        $expiry_date=get_post_meta($event_id, '_event_expiry_date', true);
	        
	        if(!empty($start_date) && !empty($end_date) )
	        {
	            $str_time =  strtotime($end_date) - strtotime($start_date);
	            $diff_days = floor($str_time/3600/24);//get the timestamp from start and end date
	            $diff_days = ' + '.$diff_days.' days';
	        }
	        $post = get_post( $event_id );
	        
	        
	        if(!empty($event_id) && !empty($recurrece_frequency)  && !empty($recure_every) && !empty($recure_weekday) && !empty($recure_month_day) && get_option('event_manager_submission_requires_approval')!= 1){
	            
	            if($recure_untill<strtotime($start_date)){
	                update_post_meta($event_id, '_check_event_recurrence', 1);
	                
	                wp_send_json( array('status'=>false) );
	            }
	        }
	        
	        switch ( $recurrece_frequency ) {
	            case 'daily' :
	                $next = ' + '.$recure_every.' day';
	                break;
	            case 'weekly' :
	                $next = ' + '.$recure_every.' week '.$recure_weekday;
	                break;
	            case 'monthly' :
	                if($fields['event']['recure_time_period'] == 'specific_time'){
	                    $next = ' '.$recure_month_day.' '.$recure_weekday.' of + '.$recure_every.' month';
	                }
	                else{
	                    $next = ' + '.$recure_every.' month today';
	                }
	                break;
	            case 'yearly' :
	                $next = ' + '.$recure_every.' year';
	                break;
	            default :
	                break;
	        }
	        $start_date=date('Y-m-d', strtotime($start_date. $next));
	        error_log($start_date. $diff_days);
	        $end_date=date('Y-m-d', strtotime($start_date. $diff_days));
	        $registration_expiry_date=date('Y-m-d', strtotime($registration_expiry_date. $next));
	        
	        if($recure_untill<strtotime($start_date)){
	            update_post_meta($event_id, '_check_event_recurrence', 1);
	            wp_send_json( array('status'=>false));
	        }
	        
	        /**
	         * Recurre the event.
	         */
	        $new_event_id = wp_insert_post( array(
	        'comment_status' => $post->comment_status,
	        'ping_status'    => $post->ping_status,
	        'post_author'    => $post->post_author,
	        'post_content'   => $post->post_content,
	        'post_excerpt'   => $post->post_excerpt,
	        'post_name'      => $post->post_name,
	        'post_parent'    => $event_id,
	        'post_password'  => $post->post_password,
	        'post_status'    => 'publish',
	        'post_title'     => $post->post_title,
	        'post_type'      => $post->post_type,
	        'to_ping'        => $post->to_ping,
	        'menu_order'     => $post->menu_order
	        ) );
	        
	        /**
	         * Copy taxonomies.
	         */
	        $taxonomies = get_object_taxonomies( $post->post_type );
	        
	        foreach ( $taxonomies as $taxonomy ) {
	            $post_terms = wp_get_object_terms( $event_id, $taxonomy, array( 'fields' => 'slugs' ) );
	            wp_set_object_terms( $new_event_id, $post_terms, $taxonomy, false );
	        }
	        
	        /*
	         * Duplicate post meta, aside from some reserved fields.
	         */
	        $post_meta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id=%d", $event_id ) );
	        
	        if ( ! empty( $post_meta ) ) {
	            $post_meta = wp_list_pluck( $post_meta, 'meta_value', 'meta_key' );
	            foreach ( $post_meta as $meta_key => $meta_value ) {
	                if($meta_key=='_event_start_date'){
	                    update_post_meta( $new_event_id, '_event_start_date',  $start_date.' '.$start_time);
	                }elseif($meta_key=='_event_end_date'){
	                    update_post_meta( $new_event_id, '_event_end_date',  $end_date.' '.$end_time);
	                }elseif($meta_key=='_event_registration_deadline'){
	                    update_post_meta( $new_event_id, '_event_registration_deadline',  $registration_expiry_date);
	                }elseif($meta_key=='_event_expiry_date'){
	                    error_log($end_date.' Expiry date ');
	                    update_post_meta( $new_event_id, '_event_expiry_date',  $end_date);
	                }elseif($meta_key=='_featured'){
	                    update_post_meta( $new_event_id, '_featured',  0);
	                }elseif($meta_key=='_cancelled'){
	                    update_post_meta( $new_event_id, '_cancelled',  0);
	                }else{
	                    error_log($meta_key.' '.$meta_value);
	                    update_post_meta( $new_event_id, $meta_key, maybe_unserialize( $meta_value ) );
	                }
	            }
	        }
	        

	        wp_send_json( array('status'=>true,'start_date'=>$start_date,'end_date'=>$end_date) );
    	}

    	 wp_send_json( array('status'=>true,'message'=> __('Opps! something went wrong.','wp-event-manager-recurring')) );
    }
    
    /**
     * admin_menu function.
     *
     * @access public
     * @return void
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=event_listing', __( 'Recurring Events', 'wp-event-manager-event-recurring' ), __( 'Recurring Events', 'wp-event-manager-event-recurring' ), 'manage_options', 'event-manager-recurring', array( $this, 'recurring_output' ) );
    }
    
    /**
     * recurring_output function to create recurring event page.
     *
     */
    public function recurring_output(){
        include_once( 'wp-event-manager-recurring-events-listing.php' );
        $class = new WP_Event_Manager_Recurring_Events();
        // $wp_list_table = new Links_List_Table();
        $class->prepare_items();
        ?>
		<div class="wrap">
			<div id="icon-users" class="icon32"></div>
			<h2>Recurring Event List</h2>
			<?php $class->display(); ?>
		</div>
		<?php
	}
	
    /**
    * update_event_recurrence save recurrence on event update
    * @param $event_id, $post
    * @since 1.4

    */
    public function update_event_recurrence( $event_id, $post  ){

				$event = get_post( $event_id);
				$recurrece_frequency = get_post_meta( $event_id ,'_event_recurrence',true);
				
				
				$recure_every = get_post_meta( $event_id ,'_recure_every',true);
				$recure_weekday = get_post_meta( $event_id ,'_recure_weekday',true);
				$recure_month_day = get_post_meta( $event_id ,'_recure_month_day',true);	
			
			if(!empty($event_id) && !empty($recurrece_frequency)  && !empty($recure_every) && !empty($recure_weekday) && !empty($recure_month_day) ){
				wp_clear_scheduled_hook( 'event_manager_event_recurring', array( $event_id) );
				// Schedule new recurrece
				switch ( $recurrece_frequency ) {
					case 'daily' :
						$next = strtotime( '+'.$recure_every.' day' );
						break;
					case 'weekly' :
						$next = strtotime( '+'.$recure_every.' week '.$recure_weekday );
						break;
					case 'monthly' :
						if($fields['event']['recure_time_period'] == 'specific_time'){
							$next = strtotime($recure_month_day.' '.$recure_weekday.' of +'.$recure_every.' month');
						}
						else{
							$next = strtotime( '+'.$recure_every.' month today' );
						}
						break;
					case 'yearly' :
						$next = strtotime( '+'.$recure_every.' year' );
						break;
					default :
						break;
				}
				//Create cron
				wp_schedule_event( $next,$recurrece_frequency,'event_manager_event_recurring', array( $event_id ) );
			}		
		}
}
new WP_Event_Manager_event_recurring_Writepanels();
?>