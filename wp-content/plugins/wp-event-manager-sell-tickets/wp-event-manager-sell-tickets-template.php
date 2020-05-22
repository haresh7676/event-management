<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
       exit; 

/**
 * Display registration form using registration form template based on predefined form fields at registrations add on
 * Fields are created from the registration form at registrations addon
 */
function display_registration_form_at_checkout_page( ) 
{
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    foreach($items as $item => $values) {
          $event_id = get_post_meta($values['product_id'] , '_event_id', true);
          break;
    }
    //Only show registrations fields for ticket product, not for other woocommerce product
    if(!empty($event_id ))
    { 
     get_event_manager_template( 'registration-form.php', array( 'registration_fields' => get_event_registration_form_fields(), 'class'=> new WP_Event_Manager_Registrations_Register(),'show_submit_registration_button'=>false ), 'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR . '/templates/' );   
    }
}


/**
* Add registration fields to form at checkout page
* Get all predefined registration fields at registration form of the registrations addon 
* Thi form fields will be bind with checkout fields.
* This function create fields from number of product in the cart
* This wil return woocommerce form field 
*/ 	
function add_registration_fields_to_form($field_number) 
{
    //Attendee information plugin is active then it is call this function it will give organizer selected fields only.
    //Default give all the fields
    if (function_exists('get_event_organizer_attendee_fields')) {
        $registration_fields = get_event_organizer_attendee_fields(get_eventid_from_cart());
    }
    else{
        $registration_fields =  get_event_registration_form_fields($suppress_filters = false);
    }

    if (isset($registration_fields) && (sizeof($registration_fields) >= 1)) 
    {    
        foreach ($registration_fields as $key=>$value) 
        {
            //Validate the custome fields at checkout page.
             if ($value['required'] == 1) {
    	            $value['required'] = true;
    	            $value['custom_attributes'] =  array('required'=>'required');    	            
    	     }
    	     
            /*
            * Add fields to checkout page.
            */
            woocommerce_form_field( $key.'-'.$field_number,  $value ); 	  
        }
    }
}

/**
* This is custom fields for registration multiselect 
* This field is used with woocommerce checkout page
* Woocommerce not supporting multiselect directly.
* This fucntion provide functionality to show multiselectbox at checkout page.
* Filter  woocommerce_form_field_multiselect.
*/
function registration_multiselect_handler( $field, $key, $args, $value ) {

    $options = '';

    if ( ! empty( $args['options'] ) ) {
        foreach ( $args['options'] as $option_key => $option_value ) {
            $options .= '<option value="' . $option_key . '" '. selected( $value, $option_key, false ) . '>' . $option_value .'</option>';
        }

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. '</label>
            <select name="' . $key . '" id="' . $key . '" class="select" multiple="multiple">
                ' . $options . '
            </select>
        </p>' ;
    }

    return $field;
}
//woocommerce form field filter
add_filter( 'woocommerce_form_field_multiselect', 'registration_multiselect_handler', 10, 4 );



/***
* This filter will Add the ticket type Ex Paid or Free
* It is bind with registration header template file.
*/
function show_ticket_detail_registration_header($registration){    
    
    $registration_tickets= get_post_meta($registration->ID,'product_type',true);
    echo "<h5>";
    _e($registration_tickets,'wp-event-manager-sell-tickets');
    echo "</h5>";

}
//add ticket details like paid or fee at registration header
add_action('after_event_registration_header','show_ticket_detail_registration_header');


/**
* This will Add the show the tickets details
* It will show in product in admin panel ,It will show settings from the woocommerce fees tab

function show_fee_details_woocommerce_general_tab(){
    global $woocommerce, $post;
    $product_id=get_the_ID() ;
    $price            = get_post_meta($product_id , '_price',true);
    
    //show fees attributes for paid ticket only. For free ticket we do not need to show fees attribute at single product update page.
    if($price > 0)
    {
        get_event_manager_template('wc-product-data-general-settings.php',array('product_id' => $product_id),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/' );
    }
}
add_action( 'woocommerce_product_options_general_product_data', 'show_fee_details_woocommerce_general_tab' );
*/

/**
* Frontend and Backend side
* Get all tickets (paid, free and total) status for the organizer either for all events or single event.
* Show tickets overview detail with event registration block
* This will show total tickets sold, paid and free tickets sold details.
* This detaill will append to registration details for the whole events at event dashboard.
*/
function display_event_tickets_overview_detail()
{   
    global $post;
  	$total_sales = 0;
	$total_free_tickets_sales =0;
	$total_paid_tickets_sales = 0;  
	$args = array();
	$event_id = isset( $_REQUEST['event_id']) ? $_REQUEST['event_id'] : null;   
	$show_remaining_tickets=false;
   
	
	if( isset( $post->post_type) && 'event_registration' == $post->post_type ) //at admin side, top side of all registrations of all events and for single event
	{
	        $event_id = isset( $_REQUEST['_event_listing']) ? $_REQUEST['_event_listing'] : null; 
	        if(empty($event_id)) //top side of all registrations of all events
	        {
        	    $args = array( 		  
                           'post_type' => 'product', 
        				   'posts_per_page' => -1,
        				   'post_status'   => 'publish'
        				 );
	        }
	        else // for single event
	        {   
	            $show_remaining_tickets=true;
	            $args = array( 		  
	                   'post_type' => 'product', 
					   'posts_per_page' => -1, 								
					   'meta_key'     => '_event_id',
					   'meta_value'   => $event_id,
				    );
	           
	        }
	}
	else
	{
    	if(empty($event_id))   //for all event dashboard of the organizer
    	{
    	
        $args = array( 		  
                       'post_type' => 'product',   
        				'post_status'   => array('publish','private'),
    				   'posts_per_page' => -1,
    				   'author' =>get_current_user_id()
    				 );
    	}
    	else if(!empty($event_id))  //for single event dashboard
    	{
    	    $args = array( 		  
    	                   'post_type' => 'product', 
    	    			   'post_status'   => array('publish','private'),
    					   'posts_per_page' => -1, 								
    					   'meta_key'     => '_event_id',
    					   'meta_value'   => $_REQUEST['event_id'],
    				 );
    	}
	}
	
	$all_tickets=get_posts($args);

	foreach ( $all_tickets as $post_data ) : setup_postdata( $post_data );
		   $product_id  = $post_data->ID;	
		   $total_sales += get_post_meta($product_id,'total_sales',true); 
		   $ticket_type = get_post_meta($product_id,'_ticket_type',true);
		   if($ticket_type == 'paid'){
		       $total_paid_tickets_sales += get_post_meta($product_id,'total_sales',true);     
		  }
		  else{
		        $total_free_tickets_sales += get_post_meta($product_id,'total_sales',true);   
		  }		
	endforeach;  
	
	
	
   get_event_manager_template('tickets-overview-detail.php',array(  'all_tickets'=>$all_tickets,
                                                                    'show_remaining_tickets' => $show_remaining_tickets,
                                                                    'total_sales'=>$total_sales,
                                                                    'total_paid_tickets_sales'=>$total_paid_tickets_sales,
                                                                    'total_free_tickets_sales'=>$total_free_tickets_sales                                                                   
                                                                    ),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/' );
}

//show ticket overview details on event manager dashboard
add_action('event_registration_dashboard_registration_status_overview_detail_after','display_event_tickets_overview_detail');

//show ticket overview details on admin side, top at all registrations list
add_action('admin_event_registration_status_overview_detail_after','display_event_tickets_overview_detail');

/**
 * This function shows the total tickets purchsed by the perticular attendee.
 * It will show at the single event registration board.
 * This functin wil get the perticular registration tickets type Paid or Free ,Total tickets purchased by registred user,total price.
 * @event_registration_footer_meta_end 
 */
 function total_purchased_tickets_by_registered_user($registration){
     $tickets_type 				= get_post_meta($registration->ID,'_ticket_type',true);
     $total_ticket_quantity 	= get_post_meta($registration->ID,'_ticket_quantity',true);
     $ticket_price 				= get_post_meta($registration->ID,'_total_ticket_price',true);
     $order_id 					= get_post_meta($registration->ID,'_order_id',true);
     $ticket_id 				= get_post_meta($registration->ID,'_ticket_id',true);
     $event_id 					= get_post_meta($registration->ID,'_event_id',true);
     
     $event_id 	= wp_get_post_parent_id( $registration->ID );
     $attendee_information_type = get_post_meta( $event_id , '_attendee_information_type',true);
     
     $order = new WC_Order( $order_id );
     $items = $order->get_items();
     foreach ( $items as $item ) {
     	if($attendee_information_type == 'each_attendee')
     	{
     		if($item['product_id'] == $ticket_id)
     		{
     			$ticket_names[] =  $item['name'];
     		}
     	}
     	else
     	{
     		$ticket_names[] =  $item['name'];
     	}
     }
     get_event_manager_template('registered-user-purchased-tickets-detail.php',array(  
                                                                    'tickets_type'=>$tickets_type,
                                                                    'ticket_price' => $ticket_price,
                                                                    'total_ticket_quantity'=>$total_ticket_quantity,    
     																'ticket_names'=>isset($ticket_names) ? $ticket_names : '',   
                                                                    ),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/' );
}
add_action('event_registration_footer_meta_end','total_purchased_tickets_by_registered_user');

/**
 * This filter will hide or show registration button form of registration addon.
 * @return boolean 
 */
 function event_registration_form_addon(){
    $registration_addon_form = get_option('event_manager_event_registration_addon_form',true);
    $registration_addon_form =  $registration_addon_form ==1 ? true : false;  
    return  $registration_addon_form;
}
add_filter('event_manager_registration_addon_form','event_registration_form_addon');

if ( ! function_exists( 'attendee_buyer_information' ) ) {
    
    function attendee_buyer_information( $registration_id ){ 
       $order_id = get_post_meta($registration_id,'_order_id',true);
       if($order_id){                      
              get_event_manager_template('event-registration-dashboard-buyer-details.php',array('order_id' => $order_id),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/');
        }
    }   
}
add_action('event_registration_dashboard_meta_end','attendee_buyer_information');


?>