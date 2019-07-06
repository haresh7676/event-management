<?php
/**
 * WP_Event_Submit_Sell_Tickets_Form class.
 */

class WP_Event_Manager_Sell_Tickets_Submit_Event_Form {
		
	/**
	 * Constructor.
	 */
	public function __construct() {
		
		// Add filters
		add_filter( 'submit_event_form_fields', array( $this, 'init_fields') );
		
		/* paid ticket price fields validation  */
		add_filter( 'submit_event_form_validate_fields',array($this,'validate_sell_ticket_fields') , 10, 3 );// true, $this->fields, $values );
	}
	
	/**
	 * init_fields function.
	 * This function add the tickets fields to the submit event form
	 */
	public function init_fields($fields) {
	    //Need to visible false default field of the WP Event Manger  ticket options (Free & Paid) & Paid price textbox
	    $fields['event']['event_ticket_options']['visibility']=false;
	    $fields['event']['event_ticket_price']['visibility']=false;	    
	    unset($fields['event']['event_ticket_options']);
	    unset($fields['event']['event_ticket_price']);
	    
		$fields['event']['paid_tickets'] = array(
					'label'       => __( 'Paid Tickets', 'wp-event-manager-sell-tickets' ),
					'type'        => 'repeated', //repeated paid tickets, when we use repeated type then we must need to give fields attribute.
					'required'    => false,
					'priority'    => 22,
					'fields'      => apply_filters('submit_event_paid_tickekts_fields' , array(  //fields attribute must 
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
							'priority'    => 2
						),
						'ticket_price' => array(
							'label'       => __( 'Ticket Price', 'wp-event-manager-sell-tickets' ),
							'type'        => 'number',
							'required'    => true,
							'placeholder' => __('Ticket price','wp-event-manager-sell-tickets' ),
							'priority'    => 3
						),	
						'ticket_sales_start_date' => array(
							'label'       => __( 'Sales Start Date', 'wp-event-manager-sell-tickets' ),
							'type'        => 'text',
							'required'    => true,
							'placeholder' => __('Tickets sales start date','wp-event-manager-sell-tickets' ),
							'attribute'       => '',
							'priority'    => 4
						),
						'ticket_sales_start_time' => array(
								'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
								'type'        => 'time',
								'required'    => true,
								'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
								'attribute'       => '',
								'priority'    => 4
						),
						'ticket_sales_end_date' => array(
							'label'       => __( 'Sales End Date', 'wp-event-manager-sell-tickets' ),
							'type'        => 'text',
							'required'    => true,
							'placeholder' => __('Tickets sales end date','wp-event-manager-sell-tickets' ),
							'priority'    => 5
						),
						'ticket_sales_end_time' => array(
								'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
								'type'        => 'time',
								'required'    => true,
								'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
								'priority'    => 6
						),
						'ticket_description' => array(
							'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
							'type'        => 'textarea',
							'required'    => false,
							'placeholder' => __('Tell your attendees more about this ticket type','wp-event-manager-sell-tickets' ),
							'priority'    => 6
						),
						'ticket_show_description' => array(
							'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'std'         => 0,
							'placeholder' => '',
							'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
							'priority'    => 7
						),
						'ticket_fee_pay_by' => array(
							'label'       => __( 'Fees Pay By', 'wp-event-manager-sell-tickets' ),
							'type'        => 'select',
							'required'    => true,
							'description' => __('Pay by attendee : fees will be added to the ticket price and paid by the attendee .','wp-event-manager-sell-tickets'),
							'priority'    => 8,
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
							'priority'    => 9,
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
							'placeholder' => __('Minimum tickets allowed per order','wp-event-manager-sell-tickets' ),
							'priority'    => 10
						),
						'ticket_maximum' => array(
							'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
							'type'        => 'number',
							'required'    => false,
							'placeholder' => __('Maximum tickets allowed per order','wp-event-manager-sell-tickets' ),
							'priority'    => 11
						), 
						
						'show_remaining_tickets' => array(
							'label'       => __( 'Show remaining tickets', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'placeholder' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
							'priority'    => 12
						),
						'ticket_individually' => array(
							'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
							'priority'    => 13,
							'std'  => ''
						)
						
						)
					) //filter
			
		 );
		 
		 $fields['event']['free_tickets'] = array(
				
					'label'       => __( 'Free Tickets', 'wp-event-manager-sell-tickets' ),
					'type'        => 'repeated', // repeated free tickets
					'required'    => false,
					'priority'    => 23,
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
							'placeholder' => __('Give your ticket name','wp-event-manager-sell-tickets' ),
							'priority'    => 2
						),
						'ticket_quantity' => array(
							'label'       => __( 'Ticket Quantity', 'wp-event-manager-sell-tickets' ),
							'type'        => 'number',
							'required'    => true,
							'placeholder' => __('Enter number of tickets','wp-event-manager-sell-tickets' ),
							'priority'    => 3
						),
						'ticket_sales_start_date' => array(
							'label'       => __( 'Sales Start Date', 'wp-event-manager-sell-tickets' ),
							'type'        => 'text',
							'required'    => true,
							'placeholder' => __('Tickets sales start date','wp-event-manager-sell-tickets' ),
							'priority'    => 4
						),
						'ticket_sales_start_time' => array(
								'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
								'type'        => 'time',
								'required'    => true,
								'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
								'attribute'       => '',
								'priority'    => 4
						),
						'ticket_sales_end_date' => array(
							'label'       => __( 'Sales End Date', 'wp-event-manager-sell-tickets' ),
							'type'        => 'text',
							'required'    => true,
							'placeholder' => __('Tickets sales end date','wp-event-manager-sell-tickets' ),
							'priority'    => 5
						),
						'ticket_sales_end_time' => array(
								'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
								'type'        => 'time',
								'required'    => true,
								'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
								'priority'    => 6
						),
						
						'ticket_description' => array(
							'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
							'type'        => 'textarea',
							'required'    => false,
							'placeholder' => __('Tell your attendees more about this ticket type','wp-event-manager-sell-tickets' ),
							'priority'    => 6
						),
						'ticket_show_description' => array(
							'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'std'         => 0,
							'placeholder' => '',
							'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
							'priority'    => 7
						),
							
						'ticket_visibility' => array(
						'label'       => __( 'Tickets Visibility', 'wp-event-manager-sell-tickets' ),
						'type'        => 'select',
						'required'    => true,
						'description' => __('Public ticket visible to all and Private ticket only visible to organizer.','wp-event-manager-sell-tickets'),
						'priority'    => 8,
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
							'priority'    => 9
						),
						'ticket_maximum' => array(
							'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
							'type'        => 'number',
							'required'    => false,
							'placeholder' => __('Maximum tickets allowed per order', 'wp-event-manager-sell-tickets' ),
							'priority'    => 10
						),
						'show_remaining_tickets' => array(
							'label'       => __( 'Show remainging tickets', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'placeholder' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
							'priority'    => 11
						),
						'ticket_individually' => array(
							'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
							'type'        => 'checkbox',
							'required'    => false,
							'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
							'priority'    => 12,
							'std'  => ''
						)
						
						)
			
		 );
		 $fields['event']['donation_tickets'] = array(
		 		'label'       => __( 'Donation Tickets', 'wp-event-manager-sell-tickets' ),
		 		'type'        => 'repeated', //repeated paid tickets, when we use repeated type then we must need to give fields attribute.
		 		'required'    => false,
		 		'priority'    => 24,
		 		'fields'      => apply_filters('submit_event_paid_tickekts_fields' , array(  //fields attribute must
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
		 						'priority'    => 2
		 				),
		 				'ticket_price' => array(
		 						'label'       => __( 'Minimum Ticket Price', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'min'         => 1,
		 						'required'    => false,
		 						'placeholder' => __('Ticket price','wp-event-manager-sell-tickets' ),
		 						'priority'    => 3
		 				),
		 				'ticket_sales_start_date' => array(
		 						'label'       => __( 'Sales Start Date', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'text',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales start date','wp-event-manager-sell-tickets' ),
		 						'attribute'       => '',
		 						'priority'    => 4
		 				),
		 				'ticket_sales_start_time' => array(
		 						'label'       => __( 'Sales Start Time', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'time',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales start time','wp-event-manager-sell-tickets' ),
		 						'attribute'       => '',
		 						'priority'    => 4
		 				),
		 				'ticket_sales_end_date' => array(
		 						'label'       => __( 'Sales End Date', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'text',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales end date','wp-event-manager-sell-tickets' ),
		 						'priority'    => 5
		 				),
		 				'ticket_sales_end_time' => array(
		 						'label'       => __( 'Sales End Time', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'time',
		 						'required'    => true,
		 						'placeholder' => __('Tickets sales end time','wp-event-manager-sell-tickets' ),
		 						'priority'    => 6
		 				),
		 				'ticket_description' => array(
		 						'label'       => __( 'Ticket Description', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'textarea',
		 						'required'    => false,
		 						'placeholder' => __('Tell your attendees more about this ticket type','wp-event-manager-sell-tickets' ),
		 						'priority'    => 6
		 				),
		 				'ticket_show_description' => array(
		 						'label'       => __( 'Show Ticket Description', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'std'         => 0,
		 						'placeholder' => '',
		 						'description'=>  __( 'Show ticket description on event page', 'wp-event-manager-sell-tickets'),
		 						'priority'    => 7
		 				),
		 				'ticket_fee_pay_by' => array(
		 						'label'       => __( 'Fees Pay By', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'select',
		 						'required'    => true,
		 						'description' => __('Pay by attendee : fees will be added to the ticket price and paid by the attendee .','wp-event-manager-sell-tickets'),
		 						'priority'    => 8,
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
		 						'priority'    => 9,
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
		 						'placeholder' => __('Minimum tickets allowed per order','wp-event-manager-sell-tickets' ),
		 						'priority'    => 10
		 				),
		 				'ticket_maximum' => array(
		 						'label'       => __( 'Maximum Tickets', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'number',
		 						'required'    => false,
		 						'placeholder' => __('Maximum tickets allowed per order','wp-event-manager-sell-tickets' ),
		 						'priority'    => 11
		 				),
		 
		 				'show_remaining_tickets' => array(
		 						'label'       => __( 'Show remaining tickets', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'placeholder' => __('Show remaining tickets with tickets detail at single event page', 'wp-event-manager-sell-tickets' ),
		 						'priority'    => 12
		 				),
		 				'ticket_individually' => array(
		 						'label'       => __( 'Sold Tickets individually', 'wp-event-manager-sell-tickets' ),
		 						'type'        => 'checkbox',
		 						'required'    => false,
		 						'description' => __('Tickets will be sold one ticket per customer','wp-event-manager-sell-tickets'),
		 						'priority'    => 13,
		 						'std'  => ''
		 				)
		 
		 		)
		 				) //filter
		 			
		 );
		 
		 //Manage to show/hide tickets fields from admin
		 if(get_option('event_manager_paid_tickets') != 1 )
		 {
		     	unset( $fields['event']['paid_tickets'] );
		 }
		 if(get_option('event_manager_free_tickets') != 1 )
		 {
		        unset( $fields['event']['free_tickets'] );
		 }
		 if(get_option('event_manager_donation_tickets') != 1 )
		 {
		     unset( $fields['event']['donation_tickets'] );
		 }
	return $fields;
	}
	
	/**
	 * validate sell ticket paid tickets fields
	 * @param $validate , $fields , $values
	 * @throws Exception
	 * @return boolean
	 */
	function validate_sell_ticket_fields( $validate , $fields , $values)
	{
		//if any ticket price exist then and then check it numeric or not.
		if(isset($values['event']['paid_tickets']['ticket_price']))
		{
			if( empty( $values['event']['paid_tickets']['ticket_price'] ) || ! is_numeric( $values['event']['paid_tickets']['ticket_price'] ) ){
				throw new Exception( __( 'Paid tickets price must be in numeric', 'wp-event-manager-sell-tickets' ) );
			}
		}
	}
	
}

new WP_Event_Manager_Sell_Tickets_Submit_Event_Form();
?>