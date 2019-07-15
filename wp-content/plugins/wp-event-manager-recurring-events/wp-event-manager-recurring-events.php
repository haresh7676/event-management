<?php
/**
 Plugin Name: WP Event Manager - Event Recurring
 Plugin URI: http://www.wp-eventmanager.com/product-category/plugins/
 Description: Repeated events after specific time like daily, weekly, monthly or yearly.Automatically relist event.Your event will be republished after a specific time.
 Author: WP Event Manager
 Author URI: https://www.wp-eventmanager.com/
 Text Domain: wp-event-manager-recurring
 Domain Path: /languages
 Version: 1.4.1
 Since: 1.0
 Requires WordPress Version at least: 4.1
 Copyright: 2017 WP Event Manager
 License: GNU General Public License v3.0
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 **/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
	
	if ( ! class_exists( 'GAM_Updater' ) ) {
		include( 'autoupdater/gam-plugin-updater.php' );
	}
	
	function pre_check_before_installing_recurring()
	{
		/*
		 * Check weather WP Event Manager is installed or not
		 */
		if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
		{
			global $pagenow;
			if( $pagenow == 'plugins.php' )
			{
				echo '<div id="error" class="error notice is-dismissible"><p>';
				echo __( 'WP Event Manager is require to use Wp Event Manager - Event Recurring' , 'wp-event-manager-recurring');
				echo '</p></div>';
			}
			
		}
	}
	add_action( 'admin_notices', 'pre_check_before_installing_recurring' );
	
	/**
	 * WP_Event_Manager_Recurring class.
	 */
	class WP_Event_Manager_Recurring extends GAM_Updater {
		
		/**
		 * __construct function.
		 */
		public function __construct() {
			
			// Init updates
			$this->init_updates( __FILE__ );
			

			
			// Define constants
			define( 'EVENT_MANAGER_RECURRING_VERSION', '1.4.1' );
			define( 'EVENT_MANAGER_RECURRING_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'EVENT_MANAGER_RECURRING_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
			
			//forms
			include( 'forms/wp-event-manager-recurring-form-submit.php' );
			if(is_admin()){
				include( 'admin/wp-event-manager-recurring-writepanels.php');
			}
			// Add actions
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
			
			
			
			/*validation  */
			add_filter( 'before_submit_event_form_validate_fields',array($this,'validate_recurring_fields') ,10,2 );
			
			
			add_filter( 'cron_schedules', array( $this,'wp_event_recurrence_add_intervals' ) );
			//update event data
			if(!get_option('event_manager_recurring_events')){
			     add_action( 'event_manager_update_event_data', array( $this,'update_event_recurrence'), 10, 2 );
			}
			
			add_action( 'event_manager_event_recurring', array( $this, 'event_manager_event_recurring' ) );
			//add_action( 'event_manager_get_posted_child_field', array( $this, 'get_posted_child_field' ));

			register_deactivation_hook( __FILE__, array( $this, 'wp_event_manager_recurring_events_deactivate' ) );
		}
		
		/**
		 * Localisation
		 *
		 * @access private
		 * @return void
		 */
		public function load_plugin_textdomain() {
			
			$domain = 'wp-event-manager-recurring';
			$locale = apply_filters('plugin_locale', get_locale(), $domain);
			load_textdomain( $domain, WP_LANG_DIR . "/wp-event-manager-recurring/".$domain."-" .$locale. ".mo" );
			load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
		
		/**
		 * frontend_scripts function.
		 *
		 * @access public
		 * @return void
		 */
		public function frontend_scripts() {
		
			wp_register_style( 'wp-event-manager-event-recurring-css', EVENT_MANAGER_RECURRING_PLUGIN_URL. '/assets/css/frontend.css',EVENT_MANAGER_RECURRING_VERSION, true,$media='all' );
			wp_enqueue_style('wp-event-manager-event-recurring-css');
		}
			
		/**
		 * Validate recurring fields
		 * @parma $validate , $fields , $values
		 * @return $validate
		 **/
		public function validate_recurring_fields( $fields,$values ){
			if($values['event']['event_recurrence'] == 'no' || $values['event']['event_recurrence'] == '')
    		{
				$fields['event']['recure_every']['required'] 		= false; 
				$fields['event']['recure_time_period']['required'] 	= false; 
				$fields['event']['recure_month_day']['required'] 	= false; 
				$fields['event']['recure_weekday']['required'] 		= false; 
				$fields['event']['recure_untill']['required'] 		= false; 
			}
			elseif($values['event']['event_recurrence'] == 'daily'){
				$fields['event']['recure_time_period']['required'] 	= false; 
				$fields['event']['recure_month_day']['required'] 	= false; 
				$fields['event']['recure_weekday']['required'] 		= false; 
			}
			elseif($values['event']['event_recurrence'] == 'weekly'){
				$fields['event']['recure_time_period']['required'] 	= false; 
				$fields['event']['recure_month_day']['required'] 	= false; 
			}
			elseif($values['event']['event_recurrence'] == 'monthly'){
				if($values['event']['recure_time_period'] == 'same_time'){
					$fields['event']['recure_month_day']['required'] 	= false; 
					$fields['event']['recure_weekday']['required'] 		= false; 
				}	
			}
			elseif($values['event']['event_recurrence'] == 'yearly'){
				$fields['event']['recure_time_period']['required'] 	= false; 
				$fields['event']['recure_month_day']['required'] 	= false; 
				$fields['event']['recure_weekday']['required'] 		= false; 
			}
			return $fields;
		}
		
		/**
		 * set recurring intervals
		 **/
		function wp_event_recurrence_add_intervals( $schedules ) {
			// add a 'weekly' schedule to the existing set
			$schedules['weekly'] = array(
					'interval' => 604800,
					'display' => __('Once Weekly','wp-event-manager-recurring')
			);
			$schedules['monthly'] = array(
					'interval' => 2635200,
					'display' => __('Once a month','wp-event-manager-recurring')
			);
			$schedules['yearly'] = array(
					'interval' => 31557600,
					'display' => __('Once Yearly','wp-event-manager-recurring')
			);
			
			return $schedules;
		}
		
		public function update_event_recurrence( $event_id, $fields = array()){
			
			//if it called from event manager hook event_manager_update_event_data
			if(isset($fields['event']['event_recurrence'] ) && $fields['event']['event_recurrence'] != 'no' ) {
				$recurrece_frequency = $fields['event']['event_recurrence'];
				$recure_every = $fields['event']['recure_every'];
				$recure_weekday = $fields['event']['recure_weekday'];
				$recure_month_day = $fields['event']['recure_month_day'];
			}
			else{
				//if it called after cron created from  event_manager_event_recurring function
				$event = get_post( $event_id);
				$recurrece_frequency = get_post_meta( $event_id ,'_event_recurrence',true);
				
				
				$recure_every = get_post_meta( $event_id ,'_recure_every',true);
				$recure_weekday = get_post_meta( $event_id ,'_recure_weekday',true);
				$recure_month_day = get_post_meta( $event_id ,'_recure_month_day',true);	
			}
			
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

		/**
		 * Update event status and event date
		 */
		public function event_manager_event_recurring( $event_id ) {
			$event = get_post( $event_id);
			$recure_untill = get_post_meta( $event_id, '_recure_untill',true );
			$event_timezone = get_event_timezone();

			  //check if timezone settings is enabled as each event then set current time stamp according to the timezone
			  // for eg. if each event selected then Berlin timezone will be different then current site timezone.
			  if( WP_Event_Manager_Date_Time::get_event_manager_timezone_setting() == 'each_event'  )
			      $current_timestamp = WP_Event_Manager_Date_Time::current_timestamp_from_event_timezone( $event_timezone );
			  else
			      $current_timestamp = current_time( 'timestamp' ); // If site wise timezone selected

			if( strtotime( $recure_untill ) < $current_timestamp )
				return false;
			
			$start_date = get_post_meta( $event_id, '_event_start_date',true );
			$start_time = get_post_meta( $event_id, '_event_start_time',true );
			$end_date = get_post_meta( $event_id, '_event_end_date',true );
			$end_time = get_post_meta( $event_id, '_event_end_time',true );
			
			if(!empty($start_date) && !empty($start_time) && !empty($end_date) && !empty($end_time) )
			{
				
				$str_time =  strtotime($end_date) - strtotime($start_date);
				
				$diff_days = floor($str_time/3600/24);//get the timestamp from start and end date
				$diff_days = strtotime(date("Y-m-d H:i:s") .' +'.$diff_days.' day');
				
				$end_date = date("Y-m-d H:i:s",$diff_days	);
			}
			
			$recure_every = get_post_meta( $event_id, '_recure_every',true );
			if(!empty($recure_every)) {
				if($recure_every['recurrence_count']  < 1 ){
					return; //return without updating
				}
				$recure_every['recurrence_count'] = $recure_every['recurrence_count']  - 1;
			}
			
			$current_post['post_status'] = 'publish';
			wp_update_post($current_post);
			update_post_meta( $event_id, '_event_start_date', date("Y-m-d H:i:s") );
			update_post_meta( $event_id, '_event_end_date', $end_date );
            update_post_meta( $event_id, '_recure_every', $recure_every );
            
			do_action('event_manager_event_recurring_update_data', $event_id);
			//set new occurrence for this event
			$this->update_event_recurrence( $event_id, $fields = array());
		}
		
		/**
		 * Remove fields of recurring fields if plugin is deactivated
		 * @parma
		 * @return
		 **/
		public function wp_event_manager_recurring_events_deactivate(){
			$all_fields = get_option( 'event_manager_form_fields', true );
			if(is_array($all_fields)){
				$recurring_fields = array('event_recurrence','recure_every','recure_time_period','recure_month_day','recure_weekday','recure_untill');
				foreach ($recurring_fields as $value) {
					if(isset($all_fields['event'][$value]))
						unset($all_fields['event'][$value]);
				}
			}
			update_option('event_manager_form_fields', $all_fields);
		}
	}
	
	$GLOBALS['event_manager_recrring'] = new WP_Event_Manager_Recurring();
