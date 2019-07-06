<?php 
/*Template ticket meta box */
?>
<div class="tickets_meta_box" id="tickets_meta_box">
<div class="loading" >
<center>
<img src="<?php echo EVENT_MANAGER_SELL_TICKETS_PLUGIN_URL ?>/assets/image/ajax-loader.gif"/>
</center>
</div>

<table class="new_tickets_fields" id="new_tickets_fields">
<?php
	//This will provide free and paid ticket link .So user can add multiple paid or free tickets button.
	foreach($fields as $group_key => $group_value){		
	    
	    //Manage to show/hide tickets fields from admin
	    if(get_option('event_manager_paid_tickets', true) != 1 && $group_key=='_paid_tickets')
	        continue;
	    if(get_option('event_manager_free_tickets', true) != 1 && $group_key=='_free_tickets')
	        continue;
	    if(get_option('event_manager_donation_tickets', true) != 1 && $group_key=='_donation_tickets')
	        continue;
	    
		//add attribute in the every tickets fields .This will use to identify the fields.
		foreach($group_value['fields'] as $tickets_field_key => $tickets_value ){
			$group_value['fields'][$tickets_field_key]['attribute'] =$group_key;
		}
		get_event_manager_template( 'repeated-tickets-meta-box-fields.php', array( 'key' => $group_key, 'field' => $group_value ),'wp-event-manager-sell-tickets',EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/admin/' );
	}
?>
</table>
	<table class="widefat fixed" id="tickets_view_table" cellspacing="0">
		<thead>
		<tr>
			<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e('Ticket Type','wp-event-manager-sell-tickets');?></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e('Ticket Name','wp-event-manager-sell-tickets');?></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e('Price','wp-event-manager-sell-tickets');?></th>		
			<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e('Sold','wp-event-manager-sell-tickets');?></th>
			<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e('Action','wp-event-manager-sell-tickets');?></th> 
			 
			
		</tr>
		</thead>
		<tbody>
		
		<?php 
		//group_key has two value _paid_tickets and _free_tickets
		foreach ( $fields as $group_key => $group_fields ) { 
				$tickets_values = get_post_meta($post_id,$group_key ,true);
				if(empty($tickets_values)) continue;
			
				foreach($tickets_values as $ticket_field_key =>$ticket_field_value){
					$price = isset($ticket_field_value['ticket_price']) ? $ticket_field_value['ticket_price'].get_woocommerce_currency_symbol() : __('Free','wp-event-manager-sell-tickets') ;
		?>
					<tr class="alternate tickets<?php echo $ticket_field_value['product_id'];?>" >
						<td class="column-columnname"><?php if ($group_key == '_paid_tickets'){ 
																_e('Paid','wp-event-manager-sell-tickets');
															} 
															elseif($group_key == '_free_tickets'){
																_e('Free','wp-event-manager-sell-tickets');
															}
															elseif($group_key == '_donation_tickets'){
																_e('Donation','wp-event-manager-sell-tickets');
															}
													  ?>
						</td>
						<td class="column-columnname"><a href="<?php echo get_edit_post_link($ticket_field_value['product_id']);?>"><?php _e($ticket_field_value['ticket_name'],'wp-event-manager-sell-tickets');?></a></td>
						<td class="column-columnname"><?php
															_e($price,'wp-event-manager-sell-tickets');								
															?>
						</td>
						<td>
						    <?php 
						     $units_sold = get_post_meta( $ticket_field_value['product_id'], 'total_sales', true );
						     $stock = get_post_meta( $ticket_field_value['product_id'], '_stock', true );
						     $units_sold = $units_sold == 0 ? '' : $units_sold;
						     printf( __('%s' , 'wp-event-manager-sell-tickets') ,$units_sold);
						  
						     printf( __( ' ( Remaining tickets %s)' , 'wp-event-manager-sell-tickets'),$stock );
						     ?>
						</td>
						<td class="column-columnname">
							<div class="">
								<span><a href="#" class="edit_ticket" id="edit_ticket_<?php echo $ticket_field_value['product_id'];?>"><?php _e('Edit','wp-event-manager-sell-tickets');?></a> | </span>
								<span><a href="#<?php echo $ticket_field_value['product_id'];?>" id="tickets<?php echo $ticket_field_value['product_id'];?>" class="delete_tickets"><?php _e('Delete','wp-event-manager-sell-tickets');?></a></span>							 
							</div>
						</td>
					</tr>
					<tr class="<?php echo $group_key;?> tickets<?php echo $ticket_field_value['product_id'];?>" fields_count="<?php echo count($fields[$group_key]['fields']);?>" >
						<td colspan="5">
						<?php 
							foreach($ticket_field_value as $data_field_key => $data_field_value){ 
								$group_fields['fields'][$data_field_key]['value'] = $data_field_value;
							
								$group_fields['fields'][$data_field_key]['attribute'] = $group_key;
							}
							foreach($group_fields['fields'] as $key => $value){ 
							    if(isset($value['type'] )){
    								if($value['type'] == 'checkbox'){
    									$value['attribute'] = $group_key;
    								}
    								get_event_manager_template( 'form-fields/' . $value['type'] . '-field.php', array( 'key' => $key, 'field' => $value ) );
							    }
							}
					    ?>
						</td>
					</tr>
				<?php 
				} 
			} ?>
			
		</tbody>
		<tfoot>
			<td>
			 <input type="hidden" name="event_id" id="event_id" value="<?php echo $post_id;?>"/>
			 <input type="button" name="ticket_form_save" id="ticket_form_save" class="button-primary" value="<?php _e('Save all tickets','wp-event-manager-sell-tickets');?>">
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tfoot>		
	
	</table>	
</div>
