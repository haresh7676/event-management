<?php

/*
* This file use to cretae fields of gam event manager at admin side.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WP_EVENT_MANAGER_Attendee_inforamtion_Writepanels {
    
    /**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
	
		add_filter( 'event_manager_event_listing_data_fields', array($this ,'event_listing_attendee_inforatmation_fields') );
		
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
	}
	
	
/**
	 * event_listing_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public static function event_listing_attendee_inforatmation_fields($fields) {
	    	$fields['_attendee_information_type'] = array(
    													'label'       => __( 'Attendee Information Collection type', 'wp-event-manager-attendee-information' ),
    													'type'        => 'radio',
    													'options'     => array(
    																	'buyer_only' => __( 'Buyer Only', 'wp-event-manager-attendee-information' ),
    																	'each_attendee' => __( 'Each Attendee', 'wp-event-manager-attendee-information' ),				
    															        ),
    													'required'    => true,
    													'priority'    => 23,
    							                    );
	             //Buyer only and each attendee information field 
	    $options = WP_Event_Manager_Attendee_Information_Submit_Event_Form ::get_registration_fields_as_options(); 
	    $desc = empty($options) ? __('There is no any field in registration form','wp-event-manager-attendee-information') : __('Based on selected fields, you will collect information from the attendee','wp-event-manager-attendee-information');
	    $fields['_attendee_information_fields'] =  array(
	    														'label'       => __( 'Attendee Information to collect', 'wp-event-manager-attendee-information' ),
	    														'type'        => 'multiselect',
	    														'required'    => true,
	    														'priority'    => 23,
	    														'description'        => $desc,
	    														'options'     =>  $options
	    														);
    return $fields;							                       
	}
	 
}

new WP_EVENT_MANAGER_Attendee_inforamtion_Writepanels();
?>