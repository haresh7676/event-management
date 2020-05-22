<?php
/*
* This file use for setings at admin site for sell tickets settings.
* This setting to show and hide registration form  at single event listing and free and paid tickets field at submit event page.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WP_Event_Manager_Sell_Tickets_Settings class.
 */
class WP_Event_Manager_Sell_Tickets_Settings {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() 
    {		
		add_filter( 'event_manager_settings', array( $this, 'sell_tickets_settings' ) );       
	}
	
	
	/**
	 * export settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function sell_tickets_settings($settings) {
		$settings[ 'event_sell_tickets' ] =  array(
                        __( 'Sell Tickets', 'wp-event-manager-sell-tickets' ),
                        array(
                            array(
									'name'       => 'event_manager_paid_tickets',
		
									'std'        => '1',
									
									'cb_label'   => __( '', 'wp-event-manager-sell-tickets' ),
		
									'label'      => __( 'Show Paid tickets field', 'wp-event-manager-sell-tickets' ),
		
									'desc'       => __( 'You can show or hide Paid tickets field from [submit_event_form] page.', 'wp-event-manager-sell-tickets' ),
		
									'type'       => 'checkbox'
								),
								array(
									'name'       => 'event_manager_free_tickets',
		
									'std'        => '1',
									
									'cb_label'   => __( '', 'wp-event-manager-sell-tickets' ),
		
									'label'      => __( 'Show Free tickets field', 'wp-event-manager-sell-tickets' ),
		
									'desc'       => __( 'You can show or hide free tickets field from [submit_event_form] page.', 'wp-event-manager-sell-tickets' ),
		
									'type'       => 'checkbox'
								),
                            array(
                                'name'       => 'event_manager_donation_tickets',
                                
                                'std'        => '1',
                                
                                'cb_label'   => __( '', 'wp-event-manager-sell-tickets' ),
                                
                                'label'      => __( 'Show Donation tickets field', 'wp-event-manager-sell-tickets' ),
                                
                                'desc'       => __( 'You can show or hide donation tickets field from [submit_event_form] page.', 'wp-event-manager-sell-tickets' ),
                                
                                'type'       => 'checkbox'
                            ),
								array(
									'name'       => 'event_manager_event_registration_addon_form',
		
									'std'        => '1',
									
									'cb_label'   => __( '', 'wp-event-manager-sell-tickets' ),
		
									'label'      => __( 'Show event registration addon form ', 'wp-event-manager-sell-tickets' ),
		
									'desc'       => __( 'You can show or hide event registration addon form at single event page.', 'wp-event-manager-sell-tickets' ),
		
									'type'       => 'checkbox'
								),
								
                        )
				 );                       
         return $settings;		                                                          
	}
}
new WP_Event_Manager_Sell_Tickets_Settings();