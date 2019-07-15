<?php
/**
 * WP_Event_Manager_Recurring_Submit_Event_Form class.
 */

class WP_Event_Manager_Recurring_Submit_Event_Form {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		
		// Add filters
		add_filter( 'submit_event_form_fields', array( $this, 'init_fields') );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
	}
	
	/**
	 * frontend_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {
		 
		wp_register_script( 'wp-event-manager-recurring', EVENT_MANAGER_RECURRING_PLUGIN_URL . '/assets/js/event-recurring.js', array('jquery'), EVENT_MANAGER_RECURRING_VERSION, true );
		//localize javascript file
		wp_localize_script( 'wp-event-manager-recurring', 'event_manager_recurring_events', array(
		'every_day' 	 => __( 'day(s)' , 'wp-event-manager-recurring'),
		'every_week' 	 => __( 'week(s) on' , 'wp-event-manager-recurring'),
		'every_month' 	 => __( 'month(s) on' , 'wp-event-manager-recurring'),
		'ofthe_month' 	 => __( 'of the month(s)' , 'wp-event-manager-recurring'),
		'every_year' 	 => __( 'year(s) on' , 'wp-event-manager-recurring'),
		'i18n_datepicker_format' => WP_Event_Manager_Date_Time::get_datepicker_format(),
		'i18n_timepicker_format' => WP_Event_Manager_Date_Time::get_timepicker_format(),
		'i18n_timepicker_step' => WP_Event_Manager_Date_Time::get_timepicker_step(),
)
		);
		wp_enqueue_script('wp-event-manager-recurring');
	}
	
	/**
	 * init_fields function.
	 * This function add the tickets fields to the submit event form
	 */
	public function init_fields($fields) {
		
		
		$fields['event']['event_recurrence'] = array(
				'label'=> __( 'Event Recurrence', 'wp-event-manager-recurring' ),
				'type'  => 'select',
				'default'  => 'no',
				'options'  => array(
						'no' 		    => __( "Don't repeat",'wp-event-manager-recurring'),
						'daily'         => __( 'Daily','wp-event-manager-recurring'),
						'weekly'        => __( 'Weekly','wp-event-manager-recurring'),
						'monthly'       => __( 'Monthly','wp-event-manager-recurring'),
						'yearly'        => __( 'Yearly','wp-event-manager-recurring')
						
				),
				'priority'    => 27,
				'required'=>true
		);
		$fields['event']['recure_every'] = array(
				'label'			=> __( 'Repeat Every', 'wp-event-manager-recurring' ),
				'type'  		=> 'number',
				'default'  		=> '',
				'priority'    	=> 28,
				'placeholder'	=> '',
				'required'		=> true,
				'description'	=>  ' '
				);
		$fields['event']['recure_time_period'] =  array(
								'label'		  => __('on the','wp-event-manager-recurring'),
								'type'        => 'radio',
								'required'    => true,
								'priority'    => 29,
								'options'=> array(
										'same_time'		=> __( 'same day','wp-event-manager-recurring'),
										'specific_time'	=> __( 'specific day','wp-event-manager-recurring')
								)
						);
						
		$fields['event']['recure_month_day'] =  array(
								'label'		  => __('Day Number','wp-event-manager-recurring'),
								'type'        => 'select',
								'required'    => true,
								'priority'    => 30,
								'options'=> array(
										'first'		=> __( 'First','wp-event-manager-recurring'),
										'second'	=> __( 'Second','wp-event-manager-recurring'),
										'third'		=> __( 'Third','wp-event-manager-recurring'),
										'fourth'	=> __( 'Fourth','wp-event-manager-recurring'),
										'last'		=> __( 'Last','wp-event-manager-recurring')
										
								)
						);
		$fields['event']['recure_weekday'] = array(
								'label'		  => __('Day Name','wp-event-manager-recurring'),
								'type'        => 'select',
								'required'    => true,
								'priority'    => 31,
								'options'=> array(
										'sun'=> __( 'Sunday','wp-event-manager-recurring'),
										'mon'=> __( 'Monday','wp-event-manager-recurring'),
										'tue'=> __( 'Tuesday','wp-event-manager-recurring'),
										'wed'=> __( 'Wednesday','wp-event-manager-recurring'),
										'thu'=> __( 'Thursday','wp-event-manager-recurring'),
										'fri'=> __( 'Friday','wp-event-manager-recurring'),
										'sat'=> __( 'Saturday','wp-event-manager-recurring'),
								)
						);
						
		
		
				
		$fields['event']['recure_untill'] = array(
										'label'=> __( 'Repeat untill', 'wp-event-manager-recurring' ),
										'type'  => 'date',
										'default'  => '',
										'priority'    => 31,
										'placeholder'	=> '',
										'required'=>true,
							);
				
		return $fields;
	}
	
}

new WP_Event_Manager_Recurring_Submit_Event_Form();
?>