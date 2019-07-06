<div class="table-responsive col-md-6 sell-tickets-overview ">
   <table class="pull-right table table-striped">	
    <tr>
        <td class="pull-right">
        <strong><?php  _e('Total Sold Tickets : ','wp-event-manager-sell-tickets'); ?></strong>
        <?php  _e($total_sales,'wp-event-manager-sell-tickets');?>
        </td>
    </tr>
    
    <tr>
        <td class="pull-right">
          <strong><?php  _e('Paid Tickets : ','wp-event-manager-sell-tickets'); ?></strong>
          <?php  _e($total_paid_tickets_sales,'wp-event-manager-sell-tickets');?>
        </td>
    </tr>
    
    <tr>
        <td class="pull-right">
        <strong><?php  _e('Free Tickets : ','wp-event-manager-sell-tickets'); ?></strong>
          <?php  _e($total_free_tickets_sales,'wp-event-manager-sell-tickets');?>
        </td>
    </tr>
    <?php  if($show_remaining_tickets==true) : ?> 
     <tr>
        <td class="pull-right">    
		        <?php
    		              foreach ( $all_tickets as $post_data ) : setup_postdata( $post_data );
    		                     $units_sold = get_post_meta( $post_data->ID, 'total_sales', true );
    						     $units_sold = $units_sold == 0 ? '' : $units_sold;
    						     $stock = get_post_meta( $post_data->ID, '_stock', true );
    						     $ticket_tile = "<a href='".get_edit_post_link($post_data->ID)."'>". $post_data->post_title."</a> : " ;
    						     
    						     printf( __( '%s' , 'wp-event-manager-sell-tickets'),$ticket_tile );
    						     printf( __('%s' , 'wp-event-manager-sell-tickets') ,$units_sold);
    						     printf( __( '( Remaining tickets %s)' , 'wp-event-manager-sell-tickets'),$stock );
    						     ?>
    						     <br/>
    						     <?php 
                                _e('Fee Type : ','wp-event-manager-sell-tickets');
                                if(empty($ticket_fee_pay_by) || $ticket_fee_pay_by == 'ticket_fee_pay_by_attendee')
                                     _e('Fee Pay By Attendee','wp-event-manager-sell-tickets');
                                else 
                                 _e('Fee Pay By Organizer','wp-event-manager-sell-tickets');
                                 echo '<br/> ';
    		            endforeach;
    		     ?>
	     </td>
    </tr>
     <?php endif;?>
  </table>
</div>