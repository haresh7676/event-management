<?php
/*
* This file is use to create a shortcode of gam event manager sell tickets plugin. 
* This file include shortcode to show all tickets per event.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) 
     exit; 

/**
 * WP_Event_Manager_Sell_Tickets_Shortcodes class.
 */
class WP_Event_Manager_Sell_Tickets_Shortcodes {
	
	/**
	 * Constructor
	 */
	 public function __construct()
	 {					
		//shortcode for event sell tickets
		add_shortcode( 'event_sell_tickets', array( $this, 'output_event_sell_tickets' ) );
	 }
	
	/**
	 *  It is very simply a plugin that outputs a list of all sell_tickets that have listed events on your website. 
	 *  Once you have added a title to your page add the this shortcode: [event_sell_tickets]
	 *  This will output a grouped all sell tickets.
	 */
	 public function output_event_sell_tickets($atts)
	 {
		ob_start();
		extract( shortcode_atts( array('event_id'  => ''), $atts ) );
		$post_author = get_post_field( 'post_author', $event_id );
		$current_user_id = get_current_user_id();
		if( isset( $current_user_id ) && $current_user_id == $post_author){
		     $author_id = $current_user_id;
		     $post_status = 'any';
		}
		else{
		    $current_user_id = '';
		    $post_status = 'publish';
		    
		}
		
	        $args = array( 	
	                   'author'        =>  $current_user_id ,
	                   'post_type' => 'product', 
	                   'post_status'=>$post_status,
					   'posts_per_page' => -1, 								
					   'meta_key'     => '_event_id',
					   'meta_value'   => $event_id,
				 );
			
		$all_tickets=get_posts($args);
		

		if(!empty($all_tickets) && $all_tickets[0]->ID >= 1) {
		    
		   	// Get the content tickets detail template.
			// All tickets will show after event overview.
		    get_event_manager_template( 'content-tickets-details.php', array( 'event_id'=> $event_id , 'product_event_tickets' => $all_tickets), 'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/' );
		}  
	   return ob_get_clean();
	}
	
}
new WP_Event_Manager_Sell_Tickets_Shortcodes();
?>