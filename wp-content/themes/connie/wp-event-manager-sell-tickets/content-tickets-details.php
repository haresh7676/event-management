<?php
// get all the require value from the get_sell_ticket_fees()
// this value will use to calculate fee and show in the fee column
$fee_enable       = get_option( 'wc_settings_fees_enable' );
$event_timezone   = get_event_timezone(); 
  
  //check if timezone settings is enabled as each event then set current time stamp according to the timezone
  // for eg. if each event selected then Berlin timezone will be different then current site timezone.
  if( WP_Event_Manager_Date_Time::get_event_manager_timezone_setting() == 'each_event'  )
      $current_timestamp = WP_Event_Manager_Date_Time::current_timestamp_from_event_timezone( $event_timezone );
  else
      $current_timestamp = current_time( 'timestamp' ); // If site wise timezone selected
  
  //view date format to view date in this template
   $view_date_format     = WP_Event_Manager_Date_Time::get_event_manager_view_date_format();
   $timepicker_format = WP_Event_Manager_Date_Time::get_timepicker_format();
   $view_date_format = $view_date_format . ' '.$timepicker_format; 
    
  ?>
  <div class="cart-page">
    <div class="cart-details">
          <div class="cart-detail-left">
            <div class="cart-details-title">
              <h3><?php _e('Choose your ticket','wp-event-manager-sell-tickets');?></h3>
            </div>
            <form name="event-tickets" method="post" class="event-tickets-form">
              <div class="table-responsive">
                <table class="table ">
                  <!-- <thead>
                                    <tr>
                                      <th><?php _e( 'Ticket Type' , 'wp-event-manager-sell-tickets');?></th>
                                      <th><?php _e('Price', 'wp-event-manager-sell-tickets');?> </th>
                                      <?php if( $fee_enable== 'yes' ){ ?>
                                      <th><?php _e('Fee', 'wp-event-manager-sell-tickets');?></th>
                                      <?php } ?>
                                      <th><?php /*_e('Start /End Date', 'wp-event-manager-sell-tickets');*/?></th>
                                      <th><?php _e('Quantity', 'wp-event-manager-sell-tickets');?></th>
                                    </tr>
                                  </thead> -->
                  <tbody>
                    <?php
                      global $product,$woocommerce;
                      $count_fields= 0;
                      
                      //get all the tickets of perticular event.
                      foreach ( $product_event_tickets as $post_data ) : setup_postdata( $post_data );
                        
                      //get all the product meta  
                      $product_id       = $post_data->ID;       
                      $show_description = get_post_meta($post_data->ID , '_ticket_show_description',true);
                      $price            = get_post_meta($post_data->ID,'_price',true);
                      $ticket_sales_start_date = get_post_meta($post_data->ID,'_ticket_sales_start_date',true);                      
                      $ticket_sales_end_date = get_post_meta($post_data->ID,'_ticket_sales_end_date',true);
                      $ticket_fee_pay_by = get_post_meta($product_id , '_ticket_fee_pay_by',true); //ticket_fee_pay_by : ticket_fee_pay_by_organizer or ticket_fee_pay_by_attendee 
                      $price            = $price == 0 ? __('Free', 'wp-event-manager-sell-tickets'): $price;     
                      $stock            = get_post_meta($post_data->ID,'_stock',true);
                          $stock_status     = get_post_meta($post_data->ID,'_stock_status',true);
                      $min_order        = get_post_meta($post_data->ID,'minimum_order',true);
                          $max_order        = get_post_meta($post_data->ID,'maximum_order',true);
                          $show_remaining_tickets = get_post_meta($post_data->ID,'_show_remaining_tickets',true);
                          $ticket_type = get_post_meta($post_data->ID,'_ticket_type',true);
                          
                          //get the fee settings, if fee is not apply based on country then it will take default fee settings.
                          $fee_settings = get_option('fee_settings_rules',get_default_fee_settings() );
                    ?>
                    <tr>            
                      <td width="40%"><h4 class="eventtitle"><?php _e( get_the_title($post_data->ID) , 'wp-event-manager-sell-tickets'); ?></h4>
                        <?php if($show_description == '1' || $show_description == 'on') :
                         echo '<p class="contetn-tickets">'.$post_data->post_content.'</p>';
                         echo '<a href="#" class="moreinfoticket" data-title="'.get_the_title($post_data->ID).'" data-content="'.$post_data->post_content.'">More Info</a>'; 
                         endif;?>                    
                        <input type="hidden" name="product_id" id="product-id-<?php echo $count_fields;?>" value="">
                      </td>      
                      <td width="10%" class="price">
                        <?php
                          if($ticket_type == 'donation'){ 
                              echo '<input type="number" class="input-number" name="donation_price-'.$count_fields.'" id="donation_price-'.$count_fields.'"value="'.$price.'"  min="'.$price.'" />';
                          }    
                          else if(is_numeric($price)){
                            _e( get_woocommerce_currency_symbol(),'wp-event-manager-sell-tickets');                 
                            _e( $price ,'wp-event-manager-sell-tickets');
                          }
                          else{
                            _e( 'Free','wp-event-manager-sell-tickets');
                          }
                         ?>
                     </td>
                  
                    <?php if($stock_status == 'outofstock' ) : ?>
                      <td>
                        <?php   _e('Sold Out','wp-event-manager-sell-tickets'); ?>
                      </td>
                      <?php else : ?>
                      
                      <td width="50%">
                        <?php
                          if(!empty($ticket_sales_start_date) &&  $current_timestamp > strtotime($ticket_sales_start_date) && $current_timestamp <   strtotime($ticket_sales_end_date) ) { ?>
                              <select name="ticket_quantity" class="ticket_quantity_select" id="quantity-<?php echo $count_fields;?>" >
                                 <option value="0">0</option>
                                 <?php
                                      //if minimum and maximum order quantity not set
                                  $min_order = empty($min_order) || $min_order > $max_order  ?  1 :  $min_order;
                                  $max_order = empty($max_order) || $max_order < $min_order ? 20 : $max_order;

                                 for($quantity = $min_order; $quantity <= $max_order; $quantity++) : 
                                      if($quantity<=$stock) : ?>
                                                <option value="<?php echo $quantity;?>" ><?php _e($quantity , 'wp-event-manager-sell-tickets');?></option>
                                <?php endif; 
                                endfor; ?>
                              </select>
                              <span class="remaining-tickets-counter">
                            <?php
                              if(isset($show_remaining_tickets) && $show_remaining_tickets == '1'){
                               printf( __('( Remaining tickets %s )' , 'wp-event-manager-sell-tickets') ,$stock); 
                              }
                              ?>
                              </span>
                            <?php
                          }
                          else{
                            $view_date_format= 'D, M jS, h:i A';
                            if(!empty( $ticket_sales_start_date ) &&  $current_timestamp <  strtotime( $ticket_sales_start_date ) ) {
                              printf(__('Sales Start :%s'),date($view_date_format, strtotime($ticket_sales_start_date)));
                            }elseif(!empty($ticket_sales_end_date) &&  $current_timestamp >   strtotime($ticket_sales_end_date) ){
                              printf(__('Sales Ended %s','wp-event-manager-sell-tickets'),date($view_date_format,strtotime($ticket_sales_end_date)));
                            }else{
                              printf(__('End : %s','wp-event-manager-sell-tickets'),date($view_date_format,strtotime($ticket_sales_end_date)));
                            }
                            //echo ' - ';
                          }
                          ?>     
                     </td>
                        <?php endif; //end sold out condition 
                        ?>                
                    </tr>
                    <input type="hidden" name="" id="product-<?php echo $count_fields;?>" value="<?php echo $post_data->ID;?>"> 
                    <?php $count_fields ++; 
                    endforeach; ?>
                 </tbody>
                  <tfoot>
                  <tr>
                    <td colspan=<?php  if( $fee_enable== 'yes' ) echo 2; else echo 2;  ?> > <label id="sell-ticket-status-message" class=""> </label></td>
                    <td align="right">
                      <br>
                      <input type="hidden" name="" id="total_ticket" value="<?php echo $count_fields;?>">
                      <input type="submit" name="order_now" class="unvisible"  value="<?php _e('Buy tickets' , 'wp-event-manager-sell-tickets');?>" id="order_now"  >
                    </td>
                  </tr>
                </tfoot>
             </table>
           </div>
        </form>
        <a href="<?php echo get_permalink(); ?>" class="first-step-back-btn">Back</a>
      </div>
  </div>
</div>
