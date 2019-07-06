<?php 
if ( ! function_exists( 'validate_sell_tickets_field' ) ) {
/**
* Validate all sell tickets fields at the time of event submit form.
* It will validate all free and paid ticket fields at event submission form.
*/
function validate_sell_tickets_field($passed, $fields, $values )
{
    
    if(isset($values['event']['paid_tickets']) ){
        $ticket = $values['event']['paid_tickets'];
        foreach($ticket as $tickets){
            
            //validate tickets quantity
            if( isset( $tickets['ticket_quantity'] )  ){ 
                if (!preg_match('/^[0-9]*$/', $tickets['ticket_quantity'] )){
                     return new WP_Error( 'validation-error',sprintf( __( 'Please enter valid ticket quantity', 'wp-event-manager-sell-tickets' ), $tickets['ticket_quantity'] ) );        
                }
            }
            
            //validate tickets price
            if( isset($tickets['ticket_price']) ){    
                if (! is_numeric($tickets['ticket_price'] )){
                     return new WP_Error( 'validation-error',sprintf( __( 'Please enter valid ticket price', 'wp-event-manager-sell-tickets' ), $tickets['ticket_price'] ) );        
                }
            }
            
        } 
    } 
    
    if(isset($values['event']['free_tickets']) ){
        $ticket = $values['event']['free_tickets'];
        foreach($ticket as $tickets){
            //validate tickets quantity
            if( isset( $tickets['ticket_quantity'] )  ){ 
                if (!preg_match('/^[0-9]*$/', $tickets['ticket_quantity'] )){
                     return new WP_Error( 'validation-error',sprintf( __( 'Please enter valid ticket quantity', 'wp-event-manager-sell-tickets' ), $tickets['ticket_quantity'] ) );        
                }
            }
        }           
    }
    return $passed;
}

}
//Validate sell tickets fields at submit event page
add_filter( 'submit_event_form_validate_fields',  'validate_sell_tickets_field', 10, 3 );

if ( ! function_exists( 'submit_tickets' ) ) {
/**
* When the form is submitted, then update the tickets data  at woocommerce products.
*
* All data is stored in the $values variable that is in the same
* format as the fields array.
*
* @param int $event_id The ID of the event being submitted.
* @param array $values The values of each field.
* @return void
* @since 1.0
 */
function submit_tickets( $event_id ) 
{
    //define fee atteribute blank array.
    //if fee not set to current location then it will add blank.
    $fees_attributes = [];
     
    //get the fee settings, if fee is not apply based on country then it will take default fee settings.
    $fee_settings = get_option('fee_settings_rules',get_default_fee_settings() );
    
    //get date and time setting defined in admin panel Event listing -> Settings -> Date & Time formatting
    $datepicker_date_format 	= WP_Event_Manager_Date_Time::get_datepicker_format();
    
    //covert datepicker format  into php date() function date format
    $php_date_format 		= WP_Event_Manager_Date_Time::get_view_date_format_from_datepicker_date_format( $datepicker_date_format );
    
    //Assign fee to product meta based on event location
    if(count($fee_settings ) != count($fee_settings , COUNT_RECURSIVE) )
    {
        
        $fee_has_country=false;
        $event_country=get_event_host_country_code($event_id);
                                
        foreach($fee_settings  as $fee)
        {
            if(isset($fee['fee_country']) && strtoupper( $fee['fee_country'] )  ==  strtoupper($event_country))
            {
                $fee_has_country=true;
                break;
            }
        }
       
	    foreach ($fee_settings  as $fee)
	    { 
	        if( (isset($fee['fee_country']) && strtoupper( $fee['fee_country'] )  ==  strtoupper($event_country) )  && $fee_has_country==true)	        
            {
                $fees_attributes[]=$fee;
            }
            elseif ($fee_has_country==false && empty($fee['fee_country']))
            {
                 $fees_attributes[]=$fee;
            }           
            
	    }
    }
    
    //Making workout Ticke search with sell tickets we need to set value either free or paid or both to _event_ticket_options of the wp event manager.
    //get the paid ticket from the _paid_tickets meta key
    $paid_tickets = get_post_meta($event_id,'_paid_tickets',true);
  
        
    $updated_paid_tickets = array();
    if( !empty($paid_tickets) && is_array( $paid_tickets )  )
    { 
	    foreach ( $paid_tickets as $ticket )
	    { 
	            $product_id         = isset($ticket['product_id']) ? $ticket['product_id'] : '';
	            $ticket_name        = isset( $ticket['ticket_name'] ) ?  $ticket['ticket_name'] : '';
	            if( empty( $ticket_name ) ){ continue; }
	            $ticket_visibility  = isset( $ticket['ticket_visibility']) && $ticket['ticket_visibility'] == 'private'  ?  'private' : 'publish';
	            $ticket_quantity    = isset( $ticket['ticket_quantity'] ) ? $ticket['ticket_quantity'] : '';
	            $ticket_price       = isset( $ticket['ticket_price'] ) ? $ticket['ticket_price'] : '';
	            $ticket_sales_start_date = isset( $ticket['ticket_sales_start_date'] ) ? $ticket['ticket_sales_start_date'] : '';
	            $sales_start_time 	= isset( $ticket['ticket_sales_start_time'] ) ? $ticket['ticket_sales_start_time'] : '';
	            $ticket_sales_end_date   = isset( $ticket['ticket_sales_end_date'] ) ? $ticket['ticket_sales_end_date'] : '';
	            $sales_end_time 	= isset( $ticket['ticket_sales_end_time'] ) ? $ticket['ticket_sales_end_time'] : '';
	            $ticket_description = isset( $ticket['ticket_description'] ) ? $ticket['ticket_description'] : ''; 
	            $ticket_show_description =isset( $ticket['ticket_show_description'] ) ? $ticket['ticket_show_description'] : 0;  
	            $ticket_fee_pay_by  = isset( $ticket['ticket_fee_pay_by'] ) ? $ticket['ticket_fee_pay_by'] : 'ticket_fee_pay_by_attendee';   
	            $ticket_minimum     = isset( $ticket['ticket_minimum'] ) ? $ticket['ticket_minimum'] : '';  
	            $ticket_maximum     = isset( $ticket['ticket_maximum'] ) ? $ticket['ticket_maximum'] : '';
	            $show_remaining_tickets = isset( $ticket['show_remaining_tickets'] ) ? $ticket['show_remaining_tickets'] : '';
	            $sold_tickets_individually = isset( $ticket['ticket_individually'] ) && !empty( $ticket['ticket_individually'] ) ? $ticket['ticket_individually'] : '';
	            
	            //Convert sales start date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
	            $start_dbformatted_date = WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_start_date );
	            $start__dbformatted_time = WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_start_time );
	            $start_dbformatted_date  = isset($start_dbformatted_date,$start__dbformatted_time) ? $start_dbformatted_date.' '.$start__dbformatted_time : $ticket_sales_start_date;
	             
	            //Convert sales end date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
	            $end_dbformatted_date 		= WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_end_date );
	            $end__dbformatted_time 		= WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_end_time );
	            $end_dbformatted_date  			= isset($end_dbformatted_date,$end__dbformatted_time) ? $end_dbformatted_date.' '.$end__dbformatted_time : $ticket_sales_end_date;

	            //submit product attributes (paid and free tickets all attributes) entry in woo-commerce from submitted , event submit form.
	            if(empty( $product_id ) )
	            {            
	                $product_id = wp_insert_post( array(
	    							'post_type'     => 'product',
	    							'post_title'    =>  $ticket_name,
	    							'post_content'  => $ticket_description,
	    							'post_status'   => $ticket_visibility 
	    							) );
	    		update_post_meta( $product_id , 'total_sales', 0 );
	            }
	            else
	            {
	                $product_id = wp_update_post( array(
	                                'ID'           => $product_id,
	    							'post_type'     => 'product',
	    							'post_title'    =>  $ticket_name,
	    							'post_content'  => $ticket_description,
	    							'post_status'   => $ticket_visibility 
	    						  ) );
	            }
	            
            //set product type as event ticket
            wp_set_object_terms( $product_id, 'event_ticket', 'product_type' );
	            
	       //update all the post meta
	       // $filename should be the path to a file in the upload directory.
		   $filename = get_event_banner($event_id);	
		   if(isset($filename) ){
		       if(is_array($filename)) $filename = $filename[0];
    		   $filetype = wp_check_filetype( $filename );
    		   $wp_upload_dir = wp_upload_dir();
    		   // Prepare an array of post data for the attachment.
    		   $attachment = array(
    	    			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
    	    			'post_mime_type' => $filetype['type'],
    	    			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
    	    			'post_content'   => '',
    	    			'post_status'    => 'inherit'
    	    		);
    	            
        	    // Insert the attachment.
        		$attach_id = wp_insert_attachment( $attachment, $filename);
        		set_post_thumbnail($product_id, $attach_id );
		   }
		  
		//start paid tickets meta    
        do_action('sell_tickets_save_paid_ticket_product_meta_start',$ticket,$product_id); 
		   
		//all woocommerce product meta keys
		update_post_meta($product_id, '_thumbnail_id',$attach_id );					
		update_post_meta($product_id, '_regular_price', $ticket_price );
		update_post_meta($product_id, '_price', $ticket_price );
		update_post_meta($product_id, '_stock', $ticket_quantity );
		update_post_meta($product_id, '_stock_status', 'instock' );
		update_post_meta($product_id, '_manage_stock', 'yes' );
		update_post_meta($product_id, 'minimum_order', $ticket_minimum );  //woocommerce meta key
		update_post_meta($product_id, 'maximum_order', $ticket_maximum );  //woocommerce meta key
		update_post_meta($product_id, '_sold_individually', $sold_tickets_individually );
				
		//add event id in product
		update_post_meta($product_id, '_event_id', $event_id);
		update_post_meta($product_id, '_ticket_sales_start_date',  $start_dbformatted_date);
		update_post_meta($product_id, '_ticket_sales_end_date', $end_dbformatted_date);
		update_post_meta($product_id, '_ticket_type', 'paid');
		update_post_meta($product_id, '_ticket_show_description',$ticket_show_description );
		update_post_meta($product_id, '_show_remaining_tickets',$show_remaining_tickets );
				
		//add all fee details as custom attributes of the product, values will get from decided fees tab as get_options setttings.
		update_post_meta($product_id, '_ticket_fee_pay_by', $ticket_fee_pay_by );
		
		//store all the settings of fee
		//update_post_meta($product_id, '_woo_fee_settings', $fees_attributes );	
		
		//end paid tickets meta    
        do_action('sell_tickets_save_paid_ticket_product_meta_end',$ticket,$product_id);
		
		$ticket['product_id'] =  $product_id ; 
				
		//Update array of _paid tickets with product id
		array_push($updated_paid_tickets,$ticket);
	    } //endforeach paid tickets	
    }
    update_post_meta($event_id, '_paid_tickets', $updated_paid_tickets );
    
    
    //Making workout Ticke search with sell tickets we need to set value either free or paid or both to _event_ticket_options of the wp event manager.
    //get the free ticket from the _free_tickets meta key
    $free_tickets = get_post_meta($event_id,'_free_tickets',true);   
        
    $updated_free_tickets = array();
    if( !empty($free_tickets) && is_array( $free_tickets ) ){ 
	    foreach ( $free_tickets as $ticket )
	    {
	        
	            $product_id         = isset($ticket['product_id']) ? $ticket['product_id'] : '';
	            $ticket_name             = isset( $ticket['ticket_name'] ) ?  $ticket['ticket_name'] : '';
	            if( empty($ticket_name) ){ continue;}
	            $ticket_visibility  = isset( $ticket['ticket_visibility'] ) && ($ticket['ticket_visibility'] == 'public') ? 'publish' : 'private';;
	            $ticket_quantity    = isset( $ticket['ticket_quantity'] ) ? $ticket['ticket_quantity'] : '';
	            $ticket_price       = isset( $ticket['ticket_price'] ) ? $ticket['ticket_price'] : '';
	            $ticket_sales_start_date = isset( $ticket['ticket_sales_start_date'] ) ? $ticket['ticket_sales_start_date'] : '';
	            $sales_start_time 	= isset( $ticket['ticket_sales_start_time'] ) ? $ticket['ticket_sales_start_time'] : '';
	            $ticket_sales_end_date   = isset( $ticket['ticket_sales_end_date'] ) ? $ticket['ticket_sales_end_date'] : '';
	            $sales_end_time 	= isset( $ticket['ticket_sales_end_time'] ) ? $ticket['ticket_sales_end_time'] : '';
	            $ticket_description = isset( $ticket['ticket_description'] ) ? $ticket['ticket_description'] : ''; 
	            $ticket_show_description =isset( $ticket['ticket_show_description'] ) ? $ticket['ticket_show_description'] : 0;  
	            $ticket_fee_pay_by  = isset( $ticket['ticket_fee_pay_by'] ) ? $ticket['ticket_fee_pay_by'] : 'ticket_fee_pay_by_attendee';   
	            $ticket_minimum     = isset( $ticket['ticket_minimum'] ) ? $ticket['ticket_minimum'] : '';  
	            $ticket_maximum     = isset( $ticket['ticket_maximum'] ) ? $ticket['ticket_maximum'] : '';
	            $show_remaining_tickets = isset( $ticket['show_remaining_tickets'] ) ? $ticket['show_remaining_tickets'] : '';
	            $sold_tickets_individually = isset( $ticket['ticket_individually'] ) && !empty( $ticket['ticket_individually'] ) ? $ticket['ticket_individually'] : '';
	            
	            //Convert sales start date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
	            $start_dbformatted_date 		= WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_start_date );
	            $start__dbformatted_time 		= WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_start_time );
	            $start_dbformatted_date  		= isset($start_dbformatted_date,$start__dbformatted_time) ? $start_dbformatted_date.' '.$start__dbformatted_time : $ticket_sales_start_date;
	            	            
	            //Convert sales end date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
	            $end_dbformatted_date 			= WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_end_date );
	            $end__dbformatted_time 			= WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_end_time );	            
	            $end_dbformatted_date  			= isset($end_dbformatted_date,$end__dbformatted_time) ? $end_dbformatted_date.' '.$end__dbformatted_time : $ticket_sales_end_date;
	            
	            //submit product attributes (paid and free tickets all attributes) entry in woo commerce from submitted , event submit form.
	            if(empty($product_id) )
	            {            
	                $product_id = wp_insert_post( array(
	    							'post_type'     => 'product',
	    							'post_title'    =>  $ticket_name,
	    							'post_content'  => $ticket_description,
	    							'post_status'   => $ticket_visibility 
	    							) );
	    		update_post_meta( $product_id , 'total_sales', 0 );
	            }
	            else
	            {
	                $product_id = wp_update_post( array(
	                                'ID'           => $product_id,
	    							'post_type'     => 'product',
	    							'post_title'    =>  $ticket_name,
	    							'post_content'  => $ticket_description,
	    							'post_status'   => $ticket_visibility 
	    						  ) );
	            }
	            
            //set product type as event ticket
            wp_set_object_terms( $product_id, 'event_ticket', 'product_type' );
	            
	       //update all the post meta
	       // $filename should be the path to a file in the upload directory.
		   $filename = get_event_banner($event_id);	
		   
		   //if banner is array
		   if(is_array($filename)) $filename = $filename[0];
		   
		   $filetype = wp_check_filetype( $filename );
		   $wp_upload_dir = wp_upload_dir();
		   // Prepare an array of post data for the attachment.
		   $attachment = array(
	    			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
	    			'post_mime_type' => $filetype['type'],
	    			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
	    			'post_content'   => '',
	    			'post_status'    => 'inherit'
	    		);
	            
	        // Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filename);
		set_post_thumbnail($product_id, $attach_id );
		update_post_meta($product_id, '_thumbnail_id',$attach_id );	
		
		//all woocommerce product meta keys		
		update_post_meta($product_id, '_regular_price', 0 );
		update_post_meta($product_id, '_price', 0 );
		update_post_meta($product_id, '_stock', $ticket_quantity );
		update_post_meta($product_id, '_stock_status', 'instock' );
		update_post_meta($product_id, '_manage_stock', 'yes' );
		update_post_meta($product_id, 'minimum_order', $ticket_minimum );
		update_post_meta($product_id, 'maximum_order', $ticket_maximum );
		update_post_meta($product_id, '_sold_individually', $sold_tickets_individually );
		
		//add event id in product
		update_post_meta($product_id, '_event_id', $event_id);
		update_post_meta($product_id, '_ticket_sales_start_date', $start_dbformatted_date);
		update_post_meta($product_id, '_ticket_sales_end_date', $end_dbformatted_date);
		update_post_meta($product_id, '_ticket_type', 'free');	
		update_post_meta($product_id, '_attendee_information_type', $event_id);
		update_post_meta($product_id, '_ticket_show_description',$ticket_show_description );
		update_post_meta($product_id, '_show_remaining_tickets',$show_remaining_tickets );
		
	    //product id = ticket id in whole sell tickets addon 		
		$ticket['product_id'] =  $product_id ; 
				
		//Update array of _paid tickets with product id
		array_push($updated_free_tickets,$ticket);
	    } 
    }
    
     //Making workout Ticke search with sell tickets we need to set value either free or paid or both to _event_ticket_options of the wp event manager.
    update_post_meta($event_id, '_free_tickets', $updated_free_tickets );
    
    //get the donation ticket from the _donation_tickets meta key
    $donation_tickets = get_post_meta($event_id,'_donation_tickets',true);
    $updated_donation_tickets = array();
    
    if( !empty($donation_tickets) && is_array( $donation_tickets )  )
    {
    	foreach ( $donation_tickets as $ticket )
    	{
    		$product_id         = isset($ticket['product_id']) ? $ticket['product_id'] : '';
    		$ticket_name        = isset( $ticket['ticket_name'] ) ?  $ticket['ticket_name'] : '';
    		if( empty( $ticket_name ) ){ continue; }
    		$ticket_visibility  = isset( $ticket['ticket_visibility']) && $ticket['ticket_visibility'] == 'private'  ?  'private' : 'publish';
    		$ticket_quantity    = isset( $ticket['ticket_quantity'] ) ? $ticket['ticket_quantity'] : '';
    		$ticket_price       = isset( $ticket['ticket_price'] ) ? $ticket['ticket_price'] : '';
    		$ticket_sales_start_date = isset( $ticket['ticket_sales_start_date'] ) ? $ticket['ticket_sales_start_date'] : '';
            $sales_start_time 	= isset( $ticket['ticket_sales_start_time'] ) ? $ticket['ticket_sales_start_time'] : '';
            $ticket_sales_end_date   = isset( $ticket['ticket_sales_end_date'] ) ? $ticket['ticket_sales_end_date'] : '';
            $sales_end_time 	= isset( $ticket['ticket_sales_end_time'] ) ? $ticket['ticket_sales_end_time'] : '';
    		$ticket_description = isset( $ticket['ticket_description'] ) ? $ticket['ticket_description'] : '';
    		$ticket_show_description =isset( $ticket['ticket_show_description'] ) ? $ticket['ticket_show_description'] : 0;
    		$ticket_fee_pay_by  = isset( $ticket['ticket_fee_pay_by'] ) ? $ticket['ticket_fee_pay_by'] : 'ticket_fee_pay_by_attendee';
    		$ticket_minimum     = isset( $ticket['ticket_minimum'] ) ? $ticket['ticket_minimum'] : '';
    		$ticket_maximum     = isset( $ticket['ticket_maximum'] ) ? $ticket['ticket_maximum'] : '';
    		$show_remaining_tickets = isset( $ticket['show_remaining_tickets'] ) ? $ticket['show_remaining_tickets'] : '';
    		$sold_tickets_individually = isset( $ticket['ticket_individually'] ) && !empty( $ticket['ticket_individually'] ) ? $ticket['ticket_individually'] : '';
    		
    		//Convert sales start date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
    		$start_dbformatted_date 		= WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_start_date );
    		$start__dbformatted_time 		= WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_start_time );
    		$start_dbformatted_date  		= isset($start_dbformatted_date,$start__dbformatted_time) ? $start_dbformatted_date.' '.$start__dbformatted_time : $ticket_sales_start_date;
    		
    		//Convert sales end date date and time value into DB formatted format and save eg. 1970-01-01 00:00:00
    		$end_dbformatted_date  = WP_Event_Manager_Date_Time::date_parse_from_format($php_date_format, $ticket_sales_end_date );
    		$end__dbformatted_time = WP_Event_Manager_Date_Time::get_db_formatted_time( $sales_end_time );    		
    		$end_dbformatted_date  = isset($end_dbformatted_date,$end__dbformatted_time) ? $end_dbformatted_date.' '.$end__dbformatted_time : $ticket_sales_end_date;
    		 
    		//submit product attributes (paid and free tickets all attributes) entry in woo-commerce from submitted , event submit form.
    		if(empty( $product_id ) )
    		{
    			$product_id = wp_insert_post( array(
    					'post_type'     => 'product',
    					'post_title'    =>  $ticket_name,
    					'post_content'  => $ticket_description,
    					'post_status'   => $ticket_visibility
    			) );
    			update_post_meta( $product_id , 'total_sales', 0 );
    		}
    		else
    		{
    			$product_id = wp_update_post( array(
    					'ID'           => $product_id,
    					'post_type'     => 'product',
    					'post_title'    =>  $ticket_name,
    					'post_content'  => $ticket_description,
    					'post_status'   => $ticket_visibility
    			) );
    		}
    		
    		//set product type as event ticket
    		wp_set_object_terms( $product_id, 'event_ticket', 'product_type' );
    		 
    		//update all the post meta
    		// $filename should be the path to a file in the upload directory.
    		$filename = get_event_banner($event_id);
    		if(isset($filename) ){
    			if(is_array($filename)) $filename = $filename[0];
    			$filetype = wp_check_filetype( $filename );
    			$wp_upload_dir = wp_upload_dir();
    			// Prepare an array of post data for the attachment.
    			$attachment = array(
    					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
    					'post_mime_type' => $filetype['type'],
    					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
    					'post_content'   => '',
    					'post_status'    => 'inherit'
    			);
    			 
    			// Insert the attachment.
    			$attach_id = wp_insert_attachment( $attachment, $filename);
    			set_post_thumbnail($product_id, $attach_id );
    		}
    
    		//start paid tickets meta
    		do_action('sell_tickets_save_donation_ticket_product_meta_start',$ticket,$product_id);
    			
    		//all woocommerce product meta keys
    		update_post_meta($product_id, '_thumbnail_id',$attach_id );
    		update_post_meta($product_id, '_regular_price', $ticket_price );
    		update_post_meta($product_id, '_price', $ticket_price );
    		update_post_meta($product_id, '_stock', $ticket_quantity );
    		update_post_meta($product_id, '_stock_status', 'instock' );
    		update_post_meta($product_id, '_manage_stock', 'yes' );
    		update_post_meta($product_id, 'minimum_order', $ticket_minimum );  //woocommerce meta key
    		update_post_meta($product_id, 'maximum_order', $ticket_maximum );  //woocommerce meta key
    		update_post_meta($product_id, '_sold_individually', $sold_tickets_individually );
    
    		//add event id in product
    		update_post_meta($product_id, '_event_id', $event_id);
    		update_post_meta($product_id, '_ticket_sales_start_date', $start_dbformatted_date);
    		update_post_meta($product_id, '_ticket_sales_end_date', $end_dbformatted_date);
    		update_post_meta($product_id, '_ticket_type', 'donation');
    		update_post_meta($product_id, '_ticket_show_description',$ticket_show_description );
    		update_post_meta($product_id, '_show_remaining_tickets',$show_remaining_tickets );
    
    		//add all fee details as custom attributes of the product, values will get from decided fees tab as get_options setttings.
    		update_post_meta($product_id, '_ticket_fee_pay_by', $ticket_fee_pay_by );
    
    		//store all the settings of fee
    		//update_post_meta($product_id, '_woo_fee_settings', $fees_attributes );
    
    		//end paid tickets meta
    		do_action('sell_tickets_save_donation_ticket_product_meta_end',$ticket,$product_id);
    
    		$ticket['product_id'] =  $product_id ;
    
    		//Update array of _donation_tickets with product id
    		array_push($updated_donation_tickets,$ticket);
    	} //endforeach paid tickets
    }
    update_post_meta($event_id, '_donation_tickets', $updated_donation_tickets );
    
    if(!empty($free_tickets) && !empty($paid_tickets) ) 
        update_post_meta($event_id  , '_event_ticket_options','paid/free');
    elseif (!empty($paid_tickets)  && empty($free_tickets)) 
        update_post_meta($event_id  , '_event_ticket_options','paid');
    elseif (!empty($free_tickets)  && empty($paid_tickets)) 
        update_post_meta($event_id  , '_event_ticket_options','free');
    else
        update_post_meta($event_id  , '_event_ticket_options','free');
    
    
} 
}

if ( ! function_exists( 'update_tickets' ) ) {
/**
* When organizer update event details from event dashboard, then also update the tickets data at woocommerce part (if user has made changes in tickets).
*
* All data is stored in the $fields variable that is in the same
* format as the fields array.
*
* @param int $event_id The ID of the event being submitted.
* @param array $fields The values of each field.
* @return void
* @since 1.0
 */
function update_tickets( $event_id, $fields ) 
{
  if( isset($_REQUEST['action']) )
  {
      $action=$_REQUEST['action'];
      $request_event_id=$_REQUEST['event_id'];
      if($action=='edit' &&  $request_event_id == $event_id )
      {
        submit_tickets( $event_id ); 	
      }
  }
}
}

if ( ! function_exists( 'ticket_details' ) ) {
/*
* Show Tickets details block after event overview block
* This action will show all tickets details which is associated with single event.
* single_event_overview_end action must be present at file : wp-event-manager/templates/content-single-event_listing.php:30
*/
function ticket_details() 
{
	echo do_shortcode('[event_sell_tickets event_id="'.get_the_ID().'"]');		 
}
}
add_action( 'single_event_overview_end',  'ticket_details' );

if ( ! function_exists( 'add_tickets_to_cart' ) ) {
/** 
* Add products (tickets) to cart at single event listing page
* Selected product will be added to cart. 
* This function is called when order now button clicked.
* This function called via ajax
*/
function add_tickets_to_cart()
{
	global $woocommerce;
	
	//This is allow to user to purchase ticket at the time per event.
	//User can not buy ticket two different events at the time.
	$woocommerce->cart->empty_cart();
	$tickets_to_add = $_POST['tickets_to_add'];
	$donation_tickets = [];
	foreach($tickets_to_add as $ticket){
		if(isset($ticket['product_id']) && $ticket['quantity'] )
		{
			$product_id = $ticket['product_id'];
			$woocommerce->cart->add_to_cart( $product_id, $ticket['quantity']);
			$ticket_type = get_post_meta($product_id,'_ticket_type',true);
			if($ticket_type == 'donation'){
				$donation_tickets[] = array('product_id' => $product_id,'price' => $ticket['price'] );
			}
		}
	}
	
	// Set the donation data to wc session
	WC()->session->set( 'donation', json_encode($donation_tickets) );
	
	wp_die();
}
}

add_action( 'woocommerce_before_calculate_totals', 'add_donation_price' );
/**
 * This will change the donation ticket price according to user price added in input.
 * It will get user price from session vatiable.
 * @param : $cart_object
 * @return : $cart_object
 */
function add_donation_price( $cart_object ) {
	
	$donation_tickets = json_decode(WC()->session->get( 'donation' ),true);
	if(!empty($donation_tickets)){
		foreach ( $cart_object->cart_contents as $value ) {
			foreach($donation_tickets as $dontation_ticket_key => $dontation_ticket ){
				if ($value['product_id'] == $dontation_ticket['product_id'] ) {
					$value['data']->set_price( $dontation_ticket['price'] );
				}
			}
		}

	}

	return $cart_object;
}

if ( ! function_exists( 'save_registration_form_at_checkout_page' ) ) {
/**
* This will save the data when woocommerce_checkout_update_order_meta fires.
* Registration fields (registration Post type) will save at woocommerce product as custom attributes of the product(ticket)
* @param : $order_id order id
*/
function save_registration_form_at_checkout_page($order_id) 
{
   global $woocommerce;
  

   $order = new WC_Order($order_id);
   $items=$order->get_items();   
   
   foreach ( $items as $item ) 
   {
      $product_id = $item['product_id'];
      break;
   }
   
   if(empty($product_id))
   	return;
   $_product = wc_get_product( $product_id );
   if(! $_product->is_type( 'event_ticket' ) ) {
   	return;
   }
		  
   $event_id = get_post_meta( $product_id, '_event_id', true );
   update_post_meta($order_id,'_event_id',$event_id);

   $attendee_information_type = get_post_meta( $event_id, '_attendee_information_type', true );
   $total_registration = isset( $attendee_information_type ) && $attendee_information_type == 'each_attendee' ? $woocommerce->cart->cart_contents_count : 1;
   

   for($i=1; $i<= $total_registration; $i++)
   {
   	$registration_fields= array();
	$fields = get_event_registration_form_fields();
	if(!empty($fields)){
		foreach ($fields as $key=>$field) 
		{	
						
			if ( ! empty( $_POST[$key.'-'.$i] ) ) 
			{
				if(in_array('from_name',$field['rules']) )
					$from_name = $_POST[$key.'-'.$i];
				if(in_array('from_email',$field['rules']) )
					$from_email = $_POST[$key.'-'.$i];
	
				if (is_array($_POST[$key.'-'.$i]))  
				{
				    $keyValue= implode(',', $_POST[$key.'-'.$i]);						
				}
				else 
				{
				    $keyValue= $_POST[$key.'-'.$i];
				   
				    $registration_fields[$key]=$keyValue;
				}
				$meta[$key] = $keyValue;
							
			}					
		}
   }
   else{
   		if(isset($_POST['billing_first_name']))
   			$registration_fields['first-name'] = $_POST['billing_first_name'];
   		
   		if(isset($_POST['billing_last_name']))
   			$registration_fields['last-name'] = $_POST['billing_last_name'];
   			
   		if(isset($_POST['billing_email']))
   			$registration_fields['email-address'] = $_POST['billing_email'];
   }
	//extra meta keys and values
	$ticket_type         = isset($_POST['ticket_type_'.$i]) ? $_POST['ticket_type_'.$i] : '';
	$ticket_id           = isset($_POST['ticket_id_'.$i]) ? $_POST['ticket_id_'.$i]: '';
	$total_ticket_price  = isset($_POST['total_ticket_price_'.$i]) ? $_POST['total_ticket_price_'.$i] : '' ;
	$total_ticket = isset( $attendee_information_type ) && $attendee_information_type == 'each_attendee' ? 1 : $woocommerce->cart->cart_contents_count;
	
	$meta['_ticket_type'] = $ticket_type;
	$meta['_order_id']    = $order_id;
	$meta['_ticket_id']   = $ticket_id;
	$meta['_total_ticket_price'] = $total_ticket_price;
	$meta['_total_ticket'] = $total_ticket;
	
	/**
	 * Create a new event registration
	 * @param  int $event_id
	 * @param  string $attendee_name	
	 * @param  string $attendee_email
	 * @param  array  $meta
	 * @param  bool $notification
	 * @return int|bool success
	 */
   	// Create registration
	if ( ! $registration_id = create_event_registration( $event_id, $registration_fields,  $meta , true , $source = '' ) ) {
		throw new Exception( __( 'Could not create event registration', 'wp-event-manager-registrations' ) );
	}
   }
}  
}   
if ( ! function_exists( 'add_fees_to_cart' ) ) {
	/**
	 * WooCommerce Extra Feature : woocommerce_cart_calculate_fees hook
	 * ----------------------------------------------------------------
	 * Add fee to cart automatically
	 * This fee is added from the seller when adding tickets.
	 * This will add fee below subtotal row.
	 */
	function add_fees_to_cart()
	{
		global $woocommerce;
		$fee_enable = get_option('wc_settings_fees_enable');
		$fee_settings = get_option('fee_settings_rules',get_default_fee_settings() );
		
		if($woocommerce->cart->cart_contents_count <= 0 || $fee_enable== 'no')
		{
			return;
		}
		
		$cart_total = $woocommerce->cart->cart_contents_total;
		$total_fees = 0;
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item )
		{
			$product_id        =  $cart_item['product_id'];
			$price             = get_post_meta($product_id , '_price',true);
			$event_id          = get_post_meta($product_id , '_event_id',true);
			$ticket_fee_pay_by = get_post_meta($product_id , '_ticket_fee_pay_by',true); //ticket_fee_pay_by : ticket_fee_pay_by_organizer or ticket_fee_pay_by_attendee
			//$fee_settings      = get_post_meta($product_id ,'_woo_fee_settings',true);
			
			//fee will apply based on event location country.
			$event_country      = get_event_host_country_code($event_id);
			
			
			$fixed_fee_value = 0;
			$percentage_fee_value = 0;
			$total_fee_value=0;
			$order_fixed_fee_value = 0;
			$order_percentage_fee_value = 0;
			if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
				foreach( $woocommerce->cart->get_coupons() as  $coupon){
					if( in_array($product_id , $coupon->get_product_ids() ) )
					{
						//if user given 100% discount for the total price.
						if($coupon->get_amount() >= 100 && $coupon->get_discount_type() == 'percent')
						{
							continue 2;//it will skip all 3 foreach because we dont need to add fee for this product
						}
						//if user given less than 100% discount for the total price. like 20%, 30%
						else if($coupon->get_amount() < 100 && $coupon->get_discount_type() == 'percent')
						{
							$discount_amount = ( $coupon->get_amount() * $price) /100;
							if( $price > $discount_amount )
								$price = $price - $discount_amount;
						}
						//if user given total amount of price as discount same as total price e.g Ticket Price 100$ and user assigned discount code with 100$.
						elseif($coupon->get_amount() >=   $price && $coupon->get_discount_type() == 'fixed_product' )
						{
							continue 2; //it will skip all 3 foreach because we dont need to add fee for this product+
						}
						//if user given total amount of price as discount less than total price e.g Ticket Price 100$ and user assigned discount code with 50$.
						elseif($coupon->get_amount() <  $price && $coupon->get_discount_type() == 'fixed_product' )
						{
							$price =  $price - $coupon->get_amount();
						}
						
						
						//if user given 100% discount for the cart total.
						if($coupon->get_amount() >= $cart_total && $coupon->get_discount_type() == 'fixed_cart')
						{
							continue 2;//it will skip all 2 foreach because we dont need to add fee for this product
						}
						
						
					}
				}
			}
			//check array is multidimentional or not
			if(count($fee_settings ) != count($fee_settings , COUNT_RECURSIVE) )
			{
				foreach ($fee_settings  as $key => $value)
				{
					if( (isset($value['fee_country']) && strtoupper( $value['fee_country'] )  ==  strtoupper($event_country) )  ||  empty($value['fee_country']) )
					{
						//when fee pay by attendee
						if($value['fee_value'] > 0 && $price > 0 && $ticket_fee_pay_by == 'ticket_fee_pay_by_attendee')
						{
							if( $value['fee_mode'] == 'fee_per_ticket' )
							{
								if( $value['fee_type'] == 'fixed_fee')
								{
									$fixed_fee_value += $value['fee_value'];
								}
								elseif($value['fee_type'] == 'fee_in_percentage')
								{
									$percentage_fee_value += ($price * $value['fee_value'])  / 100;
									//$percentage_fee_value += $price * ($value['fee_value'] * $cart_item['quantity'] / 100);
								}
							}
							elseif($value['fee_mode'] == 'fee_per_order' )
							{
								//do the stuff for per order
								if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
									foreach( $woocommerce->cart->get_coupons() as  $coupon){
										if( in_array($product_id , $coupon->get_product_ids() ) )
										{
											
											//if user given less than 100% discount for the cart total.
											if($coupon->get_amount() < $cart_total && $coupon->get_discount_type() == 'fixed_cart')
											{
												$discount_amount = ( $coupon->get_amount() * $cart_total) /100;
												if( $cart_total > $discount_amount )
													$cart_total = $cart_total - $discount_amount;
											}
										}
									}
								}
								
								if( $value['fee_type'] == 'fixed_fee')
								{
									$order_fixed_fee_value += $value['fee_value'];
								}
								elseif($value['fee_type'] == 'fee_in_percentage')
								{
									$order_percentage_fee_value += ($cart_total * $value['fee_value'])  / 100;
								}
							}
						}
						else if($ticket_fee_pay_by == 'ticket_fee_pay_by_organizer')
						{
							
							//If Tickets fee paid by organizer., developer has to do theme side based on need and payment gateway selection.
							//$per_product_fee_based_on_organizer  += $cart_item['quantity'] *$price;
							//apply_filters('get_fees_pay_by_organizer',$product_id);
						}
						
						$total_fee_value = $fixed_fee_value + $percentage_fee_value;
						
						//check if total fee value is not greater than maximum fee value.
						if( isset($value['maximum_fee'] ) && $total_fee_value >= $value['maximum_fee'])
							$total_fee_value = $value['maximum_fee'];
					}
				} //end of fee loop
				
				//add per ticket fees to total fee.
				$total_fee_value=$total_fee_value*$cart_item['quantity'];
				$total_fees= $total_fees+$total_fee_value;
				
			} //end of if is multidimentional
		} //end of cart item loop
		
		
		
		//set label
		$fees_attributes=get_default_fee_settings();
		$fee_label =$fees_attributes[0]['fee_label'];
		
		//add fee type per order fees to total fee.
		$total_fees = $total_fees + $order_percentage_fee_value + $order_fixed_fee_value;
		
		if($total_fees != 0)
		{
			$woocommerce->cart->add_fee(apply_filters('event_manager_sell_tickets_fee_label',$fee_label,$event_country),  $total_fees , true, 'standard' );
		}
		
	}
}
//Add extra fee per product
add_action( 'woocommerce_cart_calculate_fees', 'add_fees_to_cart' );

if ( ! function_exists( 'get_sell_ticket_fees' ) ) {
/**
 * Location based fees will work following way.
 * 1. if user is login in then it will consider user saved or entered country in user info data.
 * 2. if user is not login in then it will consider country of current location of user, from where he opend site or try to buy ticket
 * 3. if user country (with login from entered data, without login from site location) not find in the fee settings table rules, then it will use default fees settings.
 **/
function get_sell_ticket_fees()
{
   global $woocommerce;    
   $fees_attributes=array();    
   $fee_enable = get_option('wc_settings_fees_enable');
   if($fee_enable== 'no')
   {
       return;
   }
    
  $fee_settings_rules = get_option('fee_settings_rules',array());  

  if(isset($fee_settings_rules) && !empty($fee_settings_rules) )
  {    
  	  $fee_counter = 0;
      foreach($fee_settings_rules as $key => $value)
      { 
        if(strtoupper( $value['fee_country'] )  ==  $woocommerce->customer->get_country())
        { 
            $fees_attributes[$fee_counter]['fee_enable']    = $fee_enable;
            $fees_attributes[$fee_counter]['fee_label']     = $value['fee_label'];
            $fees_attributes[$fee_counter]['fee_value']     = $value['fee_value'];
            $fees_attributes[$fee_counter]['maximum_fee']   = $value['maximum_fee'];
            $fees_attributes[$fee_counter]['fee_mode']      = $value['fee_mode'];            
            $fees_attributes[$fee_counter]['fee_type']      = $value['fee_type'];
            $fee_counter++;
        }
      }
  }
  
  //if we did not find any match above then $fees_attributes array is empty so need to set default fees.
  if(empty($fees_attributes))
  { 
     $fees_attributes = get_default_fee_settings();
  }
  return $fees_attributes;
}
}

if ( ! function_exists( 'get_default_fee_settings' ) ) {
/**
 * IF user doesn't set the fee per country then this default_fee_settings will use.
 * It will call get the default fee options
 */
 function get_default_fee_settings()
 {
     global $woocommerce;
     $fees_attributes=array();    
     
     $fees_attributes[0]['fee_enable']  = get_option('wc_settings_fees_enable');
     $fees_attributes[0]['fee_label']   = get_option('wc_settings_fee_label');
     $fees_attributes[0]['fee_mode']    = get_option('wc_settings_fee_modes');
     $fees_attributes[0]['fee_type']    = get_option('wc_settings_fee_types');  
     
     if($fees_attributes[0]['fee_enable']=='yes')
     {
	    $fees_attributes[0]['fee_value']   = get_option('wc_settings_fee_value');
     }
     else
     {
        //otherwise it will add value to total even if fee disabled so need to assign zero.
        //function submit_tickets: 145
	    $fees_attributes[0]['fee_value']   = 0;
     }    
     
     return  $fees_attributes;
 }
}
   
if ( ! function_exists( 'sell_tickets_woocommerce_locate_template' ) ) { 
/**
*Override Woo Commerce templates from folder templates/woocommerce
* Every woocommerce template file put in the  woocommerce folder will be override with woocommerce main file 
* It will use same structure like woocommerce/template folder.
*/ 	
function sell_tickets_woocommerce_locate_template( $template, $template_name, $template_path ) 
{
	
	  global $woocommerce; 
	  $_template = $template;
	 
	  if ( ! $template_path ) $template_path = $woocommerce->template_url;
	 
	  $plugin_path  = EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR.'/templates/woocommerce/';

	  //Look within passed path within the plugin - this is priority
	 
	  $template = locate_template( array(
						$template_path . $template_name,
						$template_name
					 ));
	  //Modification: Get the template from this plugin, if it exists
	  if ( ! $template && file_exists( $plugin_path . $template_name ) )
		$template = $plugin_path . $template_name;
		
	  // Use default template
	  if ( ! $template )
		$template = $_template;
		
	 //Return what we found
	 return $template;
	
}
}
//Locate woocommerce template files
//override woocommerce template --- THIS IS COMMENTED BECAUSE WE DONT NEED TO OVERRIDE TEMPLATE FROM SELL TICKETS
//add_filter( 'woocommerce_locate_template', 'sell_tickets_woocommerce_locate_template',10, 3 );

if ( ! function_exists( 'save_sell_tickets_wc_settings_tab_fees_fields' ) ) {  
/**
* This will update the custom fields with product
* Update fees settings fields at general tab in product custom meta.
*/ 	
function save_sell_tickets_wc_settings_tab_fees_fields( $post_id )
{
  
        // save fee mode
	
	if( !empty( $_POST['_fee_mode'] ) )
		update_post_meta( $post_id, '_fee_mode', esc_attr( $_POST['_fee_mode'] ) );
		
        // Fee label save
	if( !empty( $_POST['_fee_label'] ) )
		update_post_meta( $post_id, '_fee_label', esc_attr( $_POST['_fee_label'] ) );
	
	// Fee value save
	if( !empty( $_POST['_fee_value'] ) )
		update_post_meta( $post_id, '_fee_value', esc_attr( $_POST['_fee_value'] ) );
	
	// Fee type save
	if( !empty( $_POST['_fee_type'] ) )
		update_post_meta( $post_id, '_fee_type', esc_attr( $_POST['_fee_type'] ) );
}
}	

// Save woo commerce fields when edit product
add_action( 'woocommerce_process_product_meta', 'save_sell_tickets_wc_settings_tab_fees_fields' );

if ( ! function_exists( 'update_event_manager_tickets_meta_with_woocommerce_product_update' ) ) {  
/*
* @Update woocommerce product from admin panel 
* This function will update tickets detail in to event manager tickets meta key.
*/
function update_event_manager_tickets_meta_with_woocommerce_product_update( $post_id ){
     global $post;
 
     $event_id 	= get_post_meta($post_id , '_event_id',true);
     $price 	= get_post_meta($post_id , '_price',true );
     $qty 	= get_post_meta($post_id , '_stock',true );
     
     if($price > 0 ){
     	$paid_tickets = get_post_meta($event_id , '_paid_tickets',true );
        if(is_array($paid_tickets)){
	     	foreach($paid_tickets as $tickets_key => $tickets_value){
	     		
	     		if($tickets_value['product_id'] ==  $post_id ){
	     			$tickets_value['ticket_name'] = $_POST['post_title'];
	     			$tickets_value['ticket_quantity'] = $_POST['_stock'];
	     			$tickets_value['ticket_price'] = $_POST['_regular_price'];
	     			$tickets_value['ticket_description'] = $_POST['content'];
	     			
	     			
	     			$paid_tickets[$tickets_key] = $tickets_value;
	     		}
	     	}
	     	update_post_meta($event_id , '_paid_tickets',$paid_tickets );
	}
     }
     else{
     	$free_tickets = get_post_meta($event_id , '_free_tickets',true );
     	if(is_array($free_tickets)){
	     	foreach($free_tickets as $tickets_key => $tickets_value){
	     		
	     		if($tickets_value['product_id'] ==  $post_id ){
	     			$tickets_value['ticket_name'] = $_POST['post_title'];
	     			$tickets_value['ticket_quantity'] = $_POST['_stock'];
	     			$tickets_value['ticket_price'] = $_POST['_regular_price'];
	                $tickets_value['ticket_description'] = $_POST['content'];
	
	     			$free_tickets[$tickets_key] = $tickets_value;
	     		}
	     	}
	     	update_post_meta($event_id , '_free_tickets',$free_tickets );
	}
     }
    
}
}
// Save Fields
add_action( 'woocommerce_process_product_meta', 'update_event_manager_tickets_meta_with_woocommerce_product_update' );

if ( ! function_exists( 'delete_product_from_woocommerce' ) ) { 
/**
 *  @before_delete_post 
 * This function will delete the tickets from wp-event-manager meta key _paid_tickets or _free_tickets.
 * This will fire when user delete the product permanently from admin panel.
 * This tickets also deleted from wp-event-manager .
 */
function delete_product_from_woocommerce($post_id){
    $WC_Product = wc_get_product( $post_id);
   $post_type = get_post_type ($post_id );
    if(!empty($WC_Product) && $post_type == 'product'){
        
        $event_id = get_post_meta($post_id,'_event_id',true);
        $price = get_post_meta($post_id,'_regular_price',true);
        
        if($price > 0){
           $paid_tickets  = get_post_meta($event_id,'_paid_tickets',true);
           foreach($paid_tickets as $tickets_key => $tickets_value){
	     		
	     		if($tickets_value['product_id'] ==  $post_id ){
	     		    unset($paid_tickets[$tickets_key]);
	     		    
	     		}
           }
           update_post_meta($event_id , '_paid_tickets',$paid_tickets );
           
        }
        else{
           $free_tickets  = get_post_meta($event_id,'_free_tickets',true);
           foreach($free_tickets as $tickets_key => $tickets_value){
	     		
	     		if($tickets_value['product_id'] ==  $post_id ){
	     		    unset($free_tickets[$tickets_key]);
	     		    
	     		}
           }
           update_post_meta($event_id , '_free_tickets',$free_tickets);            
        }          
    }
}
}
add_action( 'before_delete_post', 'delete_product_from_woocommerce' );


if ( ! function_exists( 'get_event_sell_tickets_meta_fields' ) ) { 
    /**
     * Get the meta fields for the registered attendee and it will use at attendee list view at registration addon	
     * This meta already saved in the method save_registration_form_at_checkout_page
     * We have use this filter at registration addon method get_event_registration_form_field_lable_by_key.
     * @param  string $key	
     * @return array
     */
    function get_event_sell_tickets_meta_fields($fields) 
    {
    		$sell_tickets_fields = array(
    		'_ticket_type' => array(
    			'label'       => __( 'Ticket Type', 'wp-event-manager-sell-tickets' )
    		),
    		'_total_ticket_price' => array(
    		'label'       => __( 'Ticket Price', 'wp-event-manager-sell-tickets' ),
    		
    	    ),
    	);
    	$sell_tickets_fields = array_merge($sell_tickets_fields,$fields);
    	return $sell_tickets_fields;
    }

}
add_filter('event_regitration_meta_fields','get_event_sell_tickets_meta_fields');

if ( ! function_exists( 'event_registration_dashboard_csv_header' ) ) { 
    function event_registration_dashboard_csv_header($row_header){
         array_push($row_header,__('Buyer\'s Name','wp-event-manager-sell-tickets'),__('Buyer\'s Email','wp-event-manager-sell-tickets'),__('Total Quantity','wp-event-manager-sell-tickets') );
        return $row_header;
    }
}
add_filter('event_registration_dashboard_csv_header','event_registration_dashboard_csv_header');

if (! function_exists('event_registration_dashboard_csv_row_value') ){
    function event_registration_dashboard_csv_row_value( $row_value ,$registration_id){
       $order_id = get_post_meta($registration_id,'_order_id',true );
       $order = new WC_Order( $order_id );
       array_push($row_value,$order->get_billing_first_name().' '.$order->get_billing_last_name());
       array_push($row_value,$order->get_billing_email());
       array_push($row_value , $order->get_item_count() );
      return $row_value;
    }
}
add_filter('event_registration_dashboard_csv_row_value','event_registration_dashboard_csv_row_value', 10, 2 );


/*
* We are calculating fees based on organizer's event location. e.g Event Host Location (Country).
* Get Organizer Event country code and it will use at fee calculation.
*/
function get_event_host_country_code($event_id)
{
    $country_code=get_post_meta($event_id,'geolocation_country_short',true);
    if(empty($country_code))
      $country_code="";
    
    return $country_code;
}

/**
 * Get EventId from cart
 * @param
 * @return event_id
 **/
function get_eventid_from_cart() {
    global $woocommerce;
    
    $items = $woocommerce->cart->get_cart();
    foreach($items as $item => $values) {
        $product_id = $values['product_id'];
        break;
    }
    return get_post_meta(  $product_id , '_event_id',true);
}

/**
 * Validate attendee registration fields at checkout page
 * @oarna
 * @return notice html
 **/
function woocommerce_registration_fields_validate_at_checkout() {
	global $woocommerce;
	
	if (function_exists('get_event_organizer_attendee_fields')) {
	    $registration_fields = get_event_organizer_attendee_fields(get_eventid_from_cart());
	}
	else{
		$registration_fields =  get_event_registration_form_fields($suppress_filters = false);
	}
	if (function_exists('get_display_count_of_attendee_information_fields')) {
		$cart_contents = get_display_count_of_attendee_information_fields();
	}
	else
	{
		$cart_contents = $woocommerce->cart->get_cart_contents_count();
	}
	
	for($i = 1 ; $i <= $cart_contents;$i++  ){
		foreach($registration_fields as $field_key => $field_value){
		    if(!isset($_POST[$field_key.'-'.$i])) continue;
		    for($j = 0 ; $j <= 5;$j++  ){
		        if($field_value['rules'][$j]=='required'){
		            if(empty($_POST[$field_key.'-'.$i])) {
		                wc_add_notice(sprintf(__('Attendee <strong> %s </strong>is a required field.'),$field_value['label']), 'error');
		                return;
		            }
		        }else{
		            if($field_value['rules'][$j]=='numeric'){
		                if(!is_numeric($_POST[$field_key.'-'.$i])) {
		                    wc_add_notice(sprintf(__('Attendee <strong> %s </strong>is not a valid number.'),$field_value['label']), 'error');
		                    return;
		                }
		            }else if($field_value['rules'][$j]=='email' || $field_value['rules'][$j]=='from_email'){
		                if(!is_email($_POST[$field_key.'-'.$i])) {
		                    wc_add_notice(sprintf(__('Attendee <strong> %s </strong>is not a valid email.'),$field_value['label']), 'error');
		                    return;
		                }
		            }
		        }
		    }
		}
	}
}
add_action('woocommerce_checkout_process', 'woocommerce_registration_fields_validate_at_checkout');


if(!function_exists('update_tickets_data_with_submit_event_form_edit_mode')){
	/**
	 * Update ticket data after adding new ticket in event edit mode
	 * Abstract WP_Event_Manager_Form by default update fields value which is submitted in form
	 * So we need to update product id which is we are not submitting in form .
	 * This is very important when ticket added first time.
	 * @parma $fields, $event
	 * @return $fields
	 **/
	function update_tickets_data_with_submit_event_form_edit_mode($fields, $event){
		foreach ( $fields as $group_key => $group_fields ) {
			foreach ( $group_fields as $key => $field ) {
				
				if ( isset( $fields[ $group_key ][ $key ]['value'] )  ) {
					
					if (  'free_tickets' === $key ||    'paid_tickets' === $key  ||  'donation_tickets' === $key) {
						$fields[ $group_key ][ $key ]['value'] = get_post_meta( $event->ID, '_' . $key, true );
					}
				}
			}
		}
		return $fields;
	}
}

//fill product id which is missing in submit_event_form_fields_get_event_data filter
add_filter('submit_event_form_fields_get_event_data','update_tickets_data_with_submit_event_form_edit_mode',10,2);

/*
 * Set minimum and maximum sell ticket as per the organizer selection
 */
function wp_event_manager_sell_tickets_quantity_changes( $args, $product ) {
    $product_id = $product->get_id();
    if($product_id > 0){
        $min = get_post_meta($product_id, 'minimum_order', true);
        $max = get_post_meta($product_id, 'maximum_order', true);
    }
    if ( ! is_cart() ) {
        $args['input_value'] = $min; // Start from this value (default = 1)
        $args['max_value'] = $max; // Max quantity (default = -1)
        $args['min_value'] = $min; // Min quantity (default = 0)
    } else {
        // Cart's "min_value" is already 0 and we don't need "input_value"
        $args['max_value'] = (int)$max; // Max quantity (default = -1)
        // ONLY ADD FOLLOWING IF STEP < MIN_VALUE
        $args['min_value'] = $min; // Min quantity (default = 0)
    }
    return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'wp_event_manager_sell_tickets_quantity_changes', 10, 2 );


/*
 * Set header "Ticket name" for registration csv file
 */
function dashboard_csv_header_ticket_name( $row ) {
    $row[] = '_ticket_name';
    return $row;
}
add_filter( 'event_registration_dashboard_csv_header', 'dashboard_csv_header_ticket_name' );

/*
 * Set value of "Ticket name" for registration csv file
 */
function dashboard_csv_row_value_ticket_name( $row, $registration ) {
    $ticket_id = get_post_meta($registration, '_ticket_id', true);
    $row[] = get_the_title($ticket_id);
    return $row;
}
add_filter( 'event_registration_dashboard_csv_row_value', 'dashboard_csv_row_value_ticket_name', 10, 2 );
