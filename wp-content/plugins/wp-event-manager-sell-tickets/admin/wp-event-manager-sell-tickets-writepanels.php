<?php
/*
* This file use to cretae fields of gam event manager at admin side.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_EVENT_MANAGER_Sell_Tickets_Writepanels {
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	
		add_filter( 'event_manager_event_listing_data_fields', array($this ,'event_listing_sell_tickets_fields') );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
		add_filter( 'manage_edit-event_registration_columns', array( $this, 'columns' ) ,12);
		add_action( 'manage_event_registration_posts_custom_column', array( $this, 'custom_columns' ), 2 );
		
		//save tickets delete from metabox using ajax
        add_action( 'event_manager_ajax_save_tickets_meta_box', array( $this, 'save_tickets_meta_box')  );		
        add_action( 'event_manager_ajax_delete_tickets_meta_box',array( $this,  'delete_tickets_meta_box') );
	}
	
	/**
	 * admin_enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$ajax_url         = WP_Event_Manager_Ajax::get_endpoint();
		wp_register_script( 'wp-event-manager-sell-tickets-admin-sell-ticket', EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL . '/assets/js/admin-sell-ticket.min.js', array( 'jquery' ), EVENT_MANAGER_SELL_TICKETS_VERSION, true);
		wp_localize_script( 'wp-event-manager-sell-tickets-admin-sell-ticket', 'event_manager_sell_tickets_admin_sell_ticket', array( 
							'ajaxUrl' 	 => $ajax_url,
							
							'i18n_datepicker_format' => WP_Event_Manager_Date_Time::get_datepicker_format(),
		
							'i18n_timepicker_format' => WP_Event_Manager_Date_Time::get_timepicker_format(),
							
							'i18n_timepicker_step' => WP_Event_Manager_Date_Time::get_timepicker_step()
							)
						  );
		wp_enqueue_script( 'wp-event-manager-sell-tickets-admin-sell-ticket');
		
		//stylesheet
		wp_register_style( 'wp-event-manager-sell-tickets-backend-css', EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL . '/assets/css/backend.min.css',EVENT_MANAGER_SELL_TICKETS_VERSION, true,$media='all' );
	   	wp_enqueue_style('wp-event-manager-sell-tickets-backend-css');
	}
	/**
	 * event_listing_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public static function event_listing_sell_tickets_fields($fields) {
		 
	 //Need to unset default field of the WP Event Manger  ticket options (Free & Paid) & Paid price textbox	   
	unset( $fields['_event_ticket_options'] ); 
	unset( $fields['_event_ticket_price'] ); 
	
	$fields['_paid_tickets'] = array(
					'label'       => __( 'Paid Tickets', 'wp-event-manager-sell-tickets' ),
					'type'        => 'repeated', //repeated paid tickets, when we use repeated type then we must need to give fields attribute.
					'required'    => false,
					'priority'    => 50,
					'fields'      => apply_filters('submit_event_paid_tickekts_backend_fields' , array(  //fields attribute must
					        'product_id' => array(			
							'type'        => 'hidden',
							'required'    => false,								
							'value'       => '',						
							'priority'    => 1
							),
							'ticket_name' => array(
								'label'       => __( 'Ticket Name', 'wp-event-manager-sell-tickets' ),
								'type'        => 'text',
								'required'    => true,
								'placeholder' => __('Give your ticket name', 'wp-event-manager-sell-tickets' ),
								'priority'    => 1
							),
							'ticket_quantity' => array(
								'label'       => __( 'Ticket Quantity', 'wp-event-manager-sell-tickets' ),
								'type'        => 'text',
								'required'    => true,
								'placeholder' => __('Enter number of tickets', 'wp-event-manager-sell-tickets' ),
								'priority'    => 2
							),
							'ticket_price' => array(
								'label'       => __( 'Ticket Price', 'wp-event-manager-sell-tickets' ),
								'type'        => 'number',
								'required'    => true,
								'placeholder' => __('Ticket price', 'wp-event-manager-sell-tickets' ),
								'priority'    => 3
							),						
							'ticket_description' => array(
								'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
								'type'        => 'textarea',
								'required'    => false,
								'placeholder' => __('Tell your attendees more about this ticket type', 'wp-event-manager-sell-tickets' ),
								'priority'    => 4
							),
							'ticket_show_description' => array(
								'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
								'type'        => 'checkbox',
								'required'    => false,
								'std'         => 0,
								'placeholder' => '',
								'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
								'priority'    => 5
							),
							'ticket_fee_pay_by' => array(
								'label'       => __( 'Fees Pay By', 'wp-event-manager-sell-tickets' ),
								'type'        => 'select',
								'required'    => true,
								'description' => __('Pay by attendee : fees will be added to the ticket price and paid by the attendee.','wp-event-manager-sell-tickets'),
								'priority'    => 6,
								'std'  => 'ticket_fee_pay_by_attendee',
								'options'     =>   
												array(							                    
													 'ticket_fee_pay_by_attendee'  => __( 'Pay By Attendee', 'wp-event-manager-sell-tickets' ),
													 'ticket_fee_pay_by_organizer'  => __( 'Pay By Organizer', 'wp-event-manager-sell-tickets' )
													)
							
							),
							'ticket_visibility' => array(
    							'label'       => __( 'Tickets Visibility', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'select',
    							'required'    => true,
    							'description' => __('Public ticket visible to all and Private ticket only visible to organizer.','wp-event-manager-sell-tickets'),
    							'priority'    => 7,
    							'std'  => 'public',
    							'options'     =>   
    							                array(							                    
    							                     'public'  => __( 'Public', 'wp-event-manager-sell-tickets' ),
    							                     'private'  => __( 'Private', 'wp-event-manager-sell-tickets' )
    							                    )
						    ),							
							'ticket_minimum' => array(
								'label'       => __( 'Minimum Tickets', 'wp-event-manager-sell-tickets' ),
								'type'        => 'number',
								'required'    => false,
								'placeholder' => __('Minimum tickets allowed per order','wp-event-manager-sell-tickets'),
								'priority'    => 8
							),
							'ticket_maximum' => array(
								'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
								'type'        => 'number',
								'required'    => false,
								'placeholder' => __('Maximum tickets allowed per order', 'wp-event-manager-sell-tickets' ),
								'priority'    => 9
							),	
							'ticket_sales_start_date' => array(
									'label'       => __( 'Sales start date', 'wp-event-manager-sell-tickets' ),
									'type'        => 'text',
									'required'    => false,
									'placeholder' => __('Sales start date', 'wp-event-manager-sell-tickets' ),
									'priority'    => 10
							),
							'ticket_sales_start_time' => array(
									'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
									'type'        => 'time',
									'required'    => true,
									'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
									'attribute'       => '',
									'priority'    => 11
							),
							'ticket_sales_end_date' => array(
									'label'       => __( 'Sales end date', 'wp-event-manager-sell-tickets' ),
									'type'        => 'text',
									'required'    => false,
									'placeholder' => __('Sales end date', 'wp-event-manager-sell-tickets' ),
									'priority'    => 12
							),
							'ticket_sales_end_time' => array(
									'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
									'type'        => 'time',
									'required'    => true,
									'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
									'priority'    => 13
							),
							'show_remaining_tickets' => array(
    							'label'       => __( 'Show remainging tickets', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'checkbox',
    							'required'    => false,
    							'placeholder' => '',
    							'description' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
    							'priority'    => 14
    						    ),
    						 'ticket_individually' => array(
    							'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'checkbox',
    						 	'std'  => '',
    							'required'    => false,
    							'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
    							'priority'    => 15
    							
					        )
							
							)
							) //end backend filter 
			
		 );
		 
		 $fields['_free_tickets'] = array(
				
					'label'       => __( 'Free Tickets', 'wp-event-manager-sell-tickets' ),
					'type'        => 'repeated', // repeated free tickets
					'required'    => false,
					'priority'    => 50,
					'fields'      => array(
				
					        'product_id' => array(		
							'type'        => 'hidden',
							'required'    => false,	
							'value'       => '',						
							'priority'    => 1
							),
							'ticket_name' => array(
								'label'       => __( 'Ticket Name ', 'wp-event-manager-sell-tickets' ),
								'type'        => 'text',
								'required'    => true,
								'placeholder' => __('Give your ticket name', 'wp-event-manager-sell-tickets' ),
								'priority'    => 1
							),
							'ticket_quantity' => array(
								'label'       => __( 'Ticket Quantity', 'wp-event-manager-sell-tickets' ),
								'type'        => 'number',
								'required'    => true,
								'placeholder' => __('Enter number of tickets','wp-event-manager-sell-tickets' ),
								'priority'    => 2
							),
							
							'ticket_description' => array(
								'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
								'type'        => 'textarea',
								'required'    => false,
								'placeholder' => __('Tell your attendees more about this ticket type','wp-event-manager-sell-tickets' ),
								'priority'    => 3
							),
							'ticket_show_description' => array(
								'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
								'type'        => 'checkbox',
								'required'    => false,
								'std'         => 0,
								'placeholder' => '',
								'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
								'priority'    => 4
							),	
							'ticket_visibility' => array(
    							'label'       => __( 'Tickets Visibility', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'select',
    							'required'    => true,
    							'description' => __('Public ticket visible to all and Private ticket only visible to organizer.','wp-event-manager-sell-tickets'),
    							'priority'    => 5,
    							'std'  => 'public',
    							'options'     =>   
    							                array(							                    
    							                     'public'  => __( 'Public', 'wp-event-manager-sell-tickets' ),
    							                     'private'  => __( 'Private', 'wp-event-manager-sell-tickets' )
    							                    )
						    ),
    						'ticket_minimum' => array(
    							'label'       => __( 'Minimum Tickets', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'number',
    							'required'    => false,
    							'placeholder' => __('Minimum tickets allowed per order', 'wp-event-manager-sell-tickets' ),
    							'priority'    => 7
    						),
    						'ticket_maximum' => array(
    							'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'number',
    							'required'    => false,
    							'placeholder' => __('Maximum tickets allowed per order', 'wp-event-manager-sell-tickets' ),
    							'priority'    => 8
    						),
							'ticket_sales_start_date' => array(
									'label'       => __( 'Sales start date', 'wp-event-manager-sell-tickets' ),
									'type'        => 'text',
									'required'    => false,
									'placeholder' => __('Sales star date', 'wp-event-manager-sell-tickets' ),
									'priority'    => 9
							),
							'ticket_sales_start_time' => array(
									'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
									'type'        => 'time',
									'required'    => true,
									'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
									'attribute'       => '',
									'priority'    => 10
							),
							'ticket_sales_end_date' => array(
									'label'       => __( 'Sales end date', 'wp-event-manager-sell-tickets' ),
									'type'        => 'text',
									'required'    => false,
									'placeholder' => __('Sales end date', 'wp-event-manager-sell-tickets' ),
									'priority'    => 11
							),
							'ticket_sales_end_time' => array(
									'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
									'type'        => 'time',
									'required'    => true,
									'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
									'priority'    => 12
							),
							'show_remaining_tickets' => array(
    							'label'       => __( 'Show remainging tickets', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'checkbox',
    							'required'    => false,
    							'placeholder' => '',
    							'description' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
    							'priority'    => 13
						    ),
						    'ticket_individually' => array(
    							'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
    							'type'        => 'checkbox',
						    	'std'  => '',
    							'required'    => false,
    							'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
    							'priority'    => 14,
    							
						    )
						
						)
			
		 );
		 $fields['_donation_tickets'] =  array(
		 		'label'       => __( 'Donation Tickets', 'wp-event-manager-sell-tickets' ),
		 		'type'        => 'repeated', //repeated paid tickets, when we use repeated type then we must need to give fields attribute.
		 		'required'    => false,
		 		'priority'    => 22,
		 		'fields'      =>  array(  //fields attribute must
		 				'product_id' => array(
		 						'type'        => 'hidden',
		 						'required'    => false,
		 						'value'       => '',
		 						'priority'    => 1
		 				),
		 				'ticket_name' => array(
		 						'label'       => __( 'Ticket Name', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'text',
		 						'required'    => true,
		 						'placeholder' => __('Give your ticket name','wp-event-manager-sell-tickets' ),
		 						'priority'    => 2
		 				),
		 				'ticket_quantity' => array(
		 						'label'       => __( 'Ticket Quantity', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'required'    => true,
		 						'placeholder' => __('Enter number of tickets','wp-event-manager-sell-tickets' ),
		 						'min'         => 1,
		 						'priority'    => 3
		 				),
		 				'ticket_price' => array(
		 						'label'       => __( 'Minimum Ticket Price', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'min'         => 1,
		 						'required'    => false,
		 						'placeholder' => __('Ticket price','wp-event-manager-sell-tickets' ),
		 						'priority'    => 4
		 				),
		 				'ticket_sales_start_date' => array(
		 						'label'       => __( 'Sales Start', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'text',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales start date','wp-event-manager-sell-tickets' ),
		 						'attribute'       => '',
		 						'priority'    => 5
		 				),
		 				'ticket_sales_start_time' => array(
		 						'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'time',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
		 						'attribute'       => '',
		 						'priority'    => 6
		 				),
		 				'ticket_sales_end_date' => array(
		 						'label'       => __( 'Sales End', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'text',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales end date','wp-event-manager-sell-tickets' ),
		 						'priority'    => 7
		 				),
		 				'ticket_sales_end_time' => array(
		 						'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'time',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
		 						'priority'    => 8
		 				),
		 				'ticket_description' => array(
		 						'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'textarea',
		 						'required'    => false,
		 						'placeholder' => __('Tell your attendees more about this ticket type','wp-event-manager-sell-tickets' ),
		 						'priority'    => 9
		 				),
		 				'ticket_show_description' => array(
		 						'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'std'         => 0,
		 						'placeholder' => '',
		 						'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
		 						'priority'    => 10
		 				),
		 				'ticket_fee_pay_by' => array(
		 						'label'       => __( 'Fees Pay By', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'select',
		 						'required'    => true,
		 						'description' => __('Pay by attendee : fees will be added to the ticket price and paid by the attendee .','wp-event-manager-sell-tickets'),
		 						'std'  => 'ticket_fee_pay_by_attendee',
		 						'options'     =>
		 						array(
		 								'ticket_fee_pay_by_attendee'  => __( 'Pay By Attendee', 'wp-event-manager-sell-tickets' ),
		 								'ticket_fee_pay_by_organizer'  => __( 'Pay By Organizer', 'wp-event-manager-sell-tickets' )
		 						),
		 						'priority'    => 11,
		 						
		 				),
		 				'ticket_visibility' => array(
		 						'label'       => __( 'Tickets Visibility', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'select',
		 						'required'    => true,
		 						'description' => __('Public ticket visible to all and Private ticket only visible to organizer.','wp-event-manager-sell-tickets'),
		 						'std'  => 'public',
		 						'options'     =>
		 						array(
		 								'public'  => __( 'Public', 'wp-event-manager-sell-tickets' ),
		 								'private'  => __( 'Private', 'wp-event-manager-sell-tickets' )
		 						),
		 						'priority'    => 12,
		 				),
		 				'ticket_minimum' => array(
		 						'label'       => __( 'Minimum Tickets', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'required'    => false,
		 						'placeholder' => __('Minimum tickets allowed per order','wp-event-manager-sell-tickets' ),
		 						'priority'    => 13
		 				),
		 				'ticket_maximum' => array(
		 						'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'required'    => false,
		 						'placeholder' => __('Maximum tickets allowed per order','wp-event-manager-sell-tickets' ),
		 						'priority'    => 14
		 				),
		 				
		 				'show_remaining_tickets' => array(
		 						'label'       => __( 'Show remaining tickets', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'description' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
		 						'priority'    => 15
		 				),
		 				'ticket_individually' => array(
		 						'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
		 						'priority'    => 16,
		 						'std'  => ''
		 				)
		 				
		 		)
		 );
	return $fields;
	}
	/**
	* Register meta box(es).
	*/
	public function add_meta_boxes() {
		add_meta_box( 'sell-ticket-meta-box', __( 'Tickets', 'wp-event-manager-sell-tickets' ), array($this , 'display_tickets_meta_box'), 'event_listing' );
	}

    /**
    * @Admin panel edit event 
    * This function will save the tickets added or updated from the admin panel
    * It will add the tickets in woocommerce and event manager meta key.
    */
    public function save_tickets_meta_box(){
    	
    	$paid_tickets = isset( $_REQUEST['_paid_tickets'] ) ? $_REQUEST['_paid_tickets'] : '';
    	$free_tickets = isset( $_REQUEST['_free_tickets'] ) ? $_REQUEST['_free_tickets'] : '';
    	$donation_tickets = isset( $_REQUEST['_donation_tickets'] ) ? $_REQUEST['_donation_tickets'] : '';
    	$event_id = isset( $_REQUEST['event_id'] ) ? $_REQUEST['event_id'] : '';
    	update_post_meta($event_id,'_paid_tickets',$paid_tickets );
    	update_post_meta($event_id,'_free_tickets',$free_tickets); 
    	update_post_meta($event_id,'_donation_tickets',$donation_tickets);  
     	//call the sumbit ticket function to save tickets in woocommerce
     	submit_tickets( $event_id );	
    	$ticket_fields = $this->event_listing_sell_tickets_fields($field=NULL);
    	
    	get_event_manager_template( 'tickets-meta-box.php', array( 'fields' =>$ticket_fields ,'post_id'=> $event_id), 'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR . '/admin/' );	
    }
    /*
    * @Admin panel edit event 
    * This function will delete the tickets from admin panel 
    * It will delete tickets from woocommerce and event manager meta key.
    */
    public function delete_tickets_meta_box(){
    	 global $wpdb;
    	$delete_id = isset( $_REQUEST['delete_id'] ) ? $_REQUEST['delete_id'] : '';
    	$delete_id = str_replace('#','',$delete_id);
    	$wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND id = %d", $delete_id ) );
    	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id = %d", $delete_id ) );
    }
	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function display_tickets_meta_box( $post ) {		
		$ticket_fields = $this->event_listing_sell_tickets_fields($field=NULL);
		get_event_manager_template( 'tickets-meta-box.php', array( 'fields' =>$ticket_fields ,'post_id'=>$post->ID), 'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR . '/admin/' );
	}
	
	/**
	 * columns function.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return void
	 */
	public function columns( $columns ) {
	    $new_columns = array();

		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;

			if ( 'check_in' === $key ) {
				$new_columns[ 'total_tickets' ] = __( 'Total tickets', 'wp-event-manager-sell-tickets' );
				$new_columns[ 'ticket_price' ] = __( 'Price', 'wp-event-manager-sell-tickets' );
				$new_columns[ 'order_id' ] = __( 'Order ID', 'wp-event-manager-sell-tickets' );
				
			}
		}

		return $new_columns;
	}
	
	/**
	 * custom_columns function.
	 *
	 * @access public
	 * @param mixed $column
	 * @return void
	 */
	public function custom_columns( $column ) {
	    global $post;
	    

		if ( 'total_tickets' === $column ) {
		   /*  $order_id = get_post_meta($post->ID,'_order_id',true);
		    if(isset($order_id)){
    		    $order = new WC_Order( $order_id );
    			_e($order->get_item_count(),'wp-event-manager-sell-tickets');
		    } */
		    $total_ticket = get_post_meta($post->ID,'_total_ticket',true);
		    printf( esc_html__( '%d', 'wp-event-manager-sell-tickets' ), $total_ticket );
		}
		if('ticket_price' === $column ){
		    $total_price = get_post_meta($post->ID,'_total_ticket_price',true) . get_woocommerce_currency_symbol();
		    printf( esc_html__( '%d', 'wp-event-manager-sell-tickets' ), $total_price );
// 			_e($total_price,'wp-event-manager-sell-tickets');
		}
		if('order_id' === $column   ){
		    $order_id = get_post_meta($post->ID,'_order_id',true);
		    if(isset($order_id)) 
		      echo "<a href='post.php?post=$order_id&action=edit'>#".$order_id ."</a>";			
		}
	}
}
new WP_EVENT_MANAGER_Sell_Tickets_Writepanels();
