<div class="row notice">
	<?php do_action('admin_event_registration_status_overview_detail_before');?>
	  <div class="table-responsive col-md-6 event-registration-overview">
        <table>	
        
            <tr>
			 <td>			    
			    <h4><?php if(!empty($event_link)) echo $event_link;  ?></h4>			   			   
			 </td>		
			</tr>
			
			<?php if(!empty($event_start_date)) : ?>
			<tr>
			 <td>			    
			    <span><strong><?php _e('Start Date :','wp-event-manager-registrations'); ?></strong></span>
			    <?php echo $event_start_date;  ?>
			    <span><strong><?php _e('Start Time :','wp-event-manager-registrations'); ?></strong></span>
			    <?php echo $event_start_time;  ?>	
			 </td>		
			</tr>
			<?php endif; ?>
			
			<?php if(!empty($event_end_date)) : ?>
			<tr>
			 <td>			    
			    <span><strong><?php _e('End Date :','wp-event-manager-registrations'); ?></strong></span>
			    <?php echo $event_end_date;  ?>	
			    <span><strong><?php _e('End Time :','wp-event-manager-registrations'); ?></strong></span>
			    <?php echo $event_end_time;  ?>
			 </td>		
			</tr>
			<?php endif; ?>
			
			<?php if(!empty($event_location)) : ?>
			<tr>
			 <td>			    
			    <span><strong><?php _e('Event Venue :','wp-event-manager-registrations'); ?></strong></span>
			    <?php echo $event_location;  ?>				   
			 </td>		
			</tr>
			<?php endif; ?>
			
			<tr>
			 <td>
			    <strong><?php _e("Total Registration : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			
			<tr>
			 <td>
			    <strong><?php _e("New : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_new_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			
			<tr>
			 <td>
			    <strong><?php _e("Confirm : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_confirm_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			
			<tr>
			 <td>
			    <strong><?php _e("Waiting : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_waiting_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			
			<tr>
			 <td>
			    <strong><?php _e("Cancelled : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_cancelled_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			
			<tr>
			 <td>
			    <strong><?php _e("Archived : ",'wp-event-manager-registrations');?></strong>
			    <?php _e($total_archived_registrations,'wp-event-manager-registrations');  ?>
			 </td>		
			</tr>
			<?php
			if(isset($_REQUEST['_event_listing'])){ ?>
			<tr>
			 <td>
			    <strong><?php _e("Total Checkin : ",'wp-event-manager-registrations');?></strong>
			    <span  class='check_in_total'>
			    <?php _e( get_total_checkedin_by_event_id(),'wp-event-manager-registrations');  ?>
			    <span>
			    <input type="hidden" name="event_id" value="<?php echo $event_id;?>" />
			 </td>		
			</tr>
			<?php } ?>
		  </table>
	    </div>
	<?php do_action('admin_event_registration_status_overview_detail_after');?>
</div>
	
	