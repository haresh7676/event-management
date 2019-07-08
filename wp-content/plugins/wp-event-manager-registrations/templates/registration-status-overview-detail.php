<div class="row">
	<?php do_action('event_registration_dashboard_registration_status_overview_detail_before');?>
	  <div class="table-responsive col-md-6">
        <table>				
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
			<tr>
			 <td>
			    <strong><?php _e("Total Checkin : ",'wp-event-manager-registrations');?></strong>
			    <span  class='check_in_total'>
			    <?php _e( get_total_checkedin_by_event_id(),'wp-event-manager-registrations');  ?>
			    <span>
			 </td>		
			</tr>
		  </table>
	    </div>
	<?php do_action('event_registration_dashboard_registration_status_overview_detail_after');?>
</div>
	
	