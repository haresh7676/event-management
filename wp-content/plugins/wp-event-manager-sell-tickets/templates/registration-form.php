<?php 

/**
 * This is registration form will show at checkout page when user will buy ticket.
 * This registration form will show below Additional Information : Order Notes field at checkout page.
 * 
 * if registration fields not found or not activated registration addon, then it will return and not allow to further proceed.
 *
 */
global $post,$woocommerce; 
  
   if( empty($registration_fields ) ) 
    {         
        echo  __( 'There is no any field in registration form' , 'wp-event-manager-sell-tickets');
    }
    else
    {  
        //If wp-event-manager-attendee-information plugin is active then it will get the the cart contents count of the attendee information fields to show on checkout pgae.
       if (function_exists('get_display_count_of_attendee_information_fields')) {
        	$cart_contents = get_display_count_of_attendee_information_fields();
       }
       else
       {
           $cart_contents = $woocommerce->cart->get_cart_contents_count();
       }
    $i=1;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$product_id = $cart_item['product_id'];
	       $_product     = apply_filters( 'sell_tickets_woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	       				
	       if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'sell_tickets_woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) 
	       {				    
				    $j=1;	
                    for($i; $j<= $cart_item['quantity']; $i++){
                        $j++;
                        if($cart_contents == 1){ 
                        	echo  "<h3>".__( 'Registration', 'wp-event-manager-registrations' )."</h3>"; 
                        	$cart_item['quantity'] = $woocommerce->cart->get_cart_contents_count() ;
                        	$ticket_price =  WC()->cart->cart_contents_total;
                        }
                        else{ echo "<h3>".__( 'Ticket', 'wp-event-manager-registrations' ). " " . $i." : ".$_product->get_title()."</h3>";  
                        $ticket_price = get_post_meta( $product_id, '_price',true);
                        }
                                  
                            $ticket_type =  !$ticket_price <= 0 ? __( 'Paid', 'wp-event-manager-registrations' ): __( 'Free', 'wp-event-manager-registrations' );
                        ?>
                       
                        <input type="hidden" name="ticket_id_<?php echo $i; ?>" value="<?php echo $product_id; ?>"/>
                        <input type="hidden" name="ticket_type_<?php echo $i; ?>" value="<?php echo $ticket_type; ?>"/>
                        <input type="hidden" name="total_ticket_price_<?php echo $i; ?>" value="<?php echo $ticket_price; ?>"/>
                        <?php
                       
                        /*
                        *This function will gives loop of all the tickets which is in the cart.
                        *Thi will add fields at the checkout page
                        *
                        */ 
    			        add_registration_fields_to_form($i);
            			if($show_submit_registration_button == true){ ?>
    		             <input type="submit" name="gam_event_manager_send_registration" value="<?php esc_attr_e( 'Send registration', 'wp-event-manager-registrations' ); ?>" />
                        
                       <?php }?>
    		         
    	               </p>
                   <?php 
                   //if attendee type is Buyer onlly
                   if($cart_contents == 1)
	                break;
                   }
	      }
	      
	      if($cart_contents == 1)
	        break;
	}
    }		
?>
<input type="hidden" name="event_id" value="<?php echo  absint($post->ID); ?>" />