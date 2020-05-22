<?php
 global $post;
	
		$count_posts = wp_count_posts( 'event_registration' );
		$args = array( 'post_type' => 'event_registration' );
		$query = new WP_Query( $args );	
	?>
	<div class="notice">
	hell
			<?php			
			if( isset($event_id)){
				//$event_id = $_REQUEST['_event_listing'];
				$toatl_tickets_sold = get_event_registration_count( $event_id );
				?>
			  <div class="">
              	<div class="row">
                	<div class="col-md-8"><h4><?php echo '<a href="' . get_edit_post_link( $event_id ) . '" title="' . esc_attr__( 'Edit Event', 'wp-event-manager-sell-tickets' ) . '">' . get_post_meta($event_id,'_event_title',true). '</a>'; ?></h4>
                	<br/>
                	<span><?php _e('Start Date :','wp-event-manager-sell-tickets'); ?></span>
                	<?php $start_date = get_post_meta($event_id,'_event_start_date',true);
                	     _e($start_date,'wp-event-manager-sell-tickets');
                	
                	?>
                	
                	<span><?php _e('Star Time :',''); ?></span>
                	<?php $start_time = get_post_meta($event_id,'_event_start_time',true);
                	     _e($start_time,'wp-event-manager-sell-tickets');
                	
                	?>
                	<br/>
                	<span><?php _e('End Date :',''); ?></span>
                	<?php $end_date = get_post_meta($event_id,'_event_end_date',true);
                	      _e($end_date , 'wp-event-manager-sell-tickets');
                	?>
                	<span><?php _e('End Time :',''); ?></span>
                	<?php $end_time = get_post_meta($event_id,'_event_end_time',true);
                	      _e($end_time , 'wp-event-manager-sell-tickets');
                	?>
                	<br/>
                	<span><?php _e('Location :','wp-event-manager-sell-tickets'); ?></span>
                	<?php $event_location = get_post_meta($event_id,'_event_venue_name',true);
                	      _e($event_location , 'wp-event-manager-sell-tickets');
                	?>
                	<br/>
                	<?php
                	
                	
                	 $args = array( 		  
	                   'post_type' => 'product', 
					   'posts_per_page' => -1, 								
					   'meta_key'     => '_event_id',
					   'meta_value'   => $event_id,
				    );
        		    $all_tickets = get_posts($args);
                	
                	//get all the tickets of perticular event.
		            foreach ( $all_tickets as $post_data ) : setup_postdata( $post_data );
		                     $units_sold = get_post_meta( $post_data->ID, 'total_sales', true );
						     $units_sold = $units_sold == 0 ? '' : $units_sold;
						     $stock = get_post_meta( $post_data->ID, '_stock', true );
						     $ticket_tile = $post_data->post_title ;
						     
						     printf( __( '%s' , 'wp-event-manager-sell-tickets'),$ticket_tile );
						     printf( __('%s' , 'wp-event-manager-sell-tickets') ,$units_sold);
						     printf( __( ' ( Remaining tickets %s)' , 'wp-event-manager-sell-tickets'),$stock );
						     ?>
						     <br/>
						     <?php
		            endforeach;
                	?>
                	
                	
                </div>
				<div class="col-md-4 text-right">
			 	<?php 
			 	  _e('Total Sold Tickets : ','wp-event-manager-sell-tickets'); 
			 	
			 	  printf( __('%s','wp-event-manager-sell-tickets'),$toatl_tickets_sold); 
			 	  echo "<br/>";
			 	  
			 	 
				foreach(get_event_registration_statuses() as $registration_status => $registration_status_lable ){
						$arg = array( 'post_type' => 'event_registration', 'post_parent' => $event_id ,'post_status' => $registration_status);
						$query = new WP_Query( $arg );				
				
				      printf( __( "%s : ",'wp-event-manager-sell-tickets'),$registration_status_lable); 
			          printf( __('%s','wp-event-manager-sell-tickets'),$query->post_count);
			          echo "</br>";
				}				
				?>
			      </div>
                </div>
			 </div>	
			 
			<?php
			}
			else{	
			?>
			<table>	
			<tr>
			<th>
			<?php
				_e('Total Sold Tickets : ','wp-event-manager-sell-tickets');
				_e($query->found_posts,'wp-event-manager-sell-tickets');
			
			?>
			</th>
			</tr>
			
			<tr>
			<th>
			<?php
				_e('New Tickets : ','wp-event-manager-sell-tickets');
				_e($count_posts->new,'wp-event-manager-sell-tickets');
			?>
			</th>
			</tr>
			
			<tr>
			<th>
			<?php 
				_e('Confirm Tickets : ','wp-event-manager-sell-tickets');
				_e($count_posts->confirmed,'wp-event-manager-sell-tickets');
			?>
			</th>
			</tr>
			
			<tr>
			<th>
			<?php 
				_e('Waiting Tickets : ','wp-event-manager-sell-tickets');
				_e($count_posts->waiting,'wp-event-manager-sell-tickets');
			?>
			</th>
			</tr>
			
			<tr>
			<th>
			<?php 
				_e('Cancelled Tickets : ','wp-event-manager-sell-tickets');
				_e($count_posts->cancelled,'wp-event-manager-sell-tickets');
			?>
			</th>
			</tr>
			</table>
			
			<?php
			}
			?>
			
		
    </div><!-----end notice-->
