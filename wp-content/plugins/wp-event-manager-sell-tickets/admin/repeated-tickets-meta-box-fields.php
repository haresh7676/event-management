<?php 
/**
 * This if only for Admin Panel
 * Repeated fields is generated from this page .
 * Repeated fields for the paid and free tickets.
 * This is field is used tickets metabox in edit event at admin panel.
 **/
 ?>
<a href="#" class="event_ticket_add_link" data-row="<?php
	
	ob_start(); 
?>
			<tr fields_count="<?php echo count($field['fields']);?>" class="<?php echo esc_attr( $key );?> repeated-row<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>">
			<td>
			<a href="#" class="remove-row col-md-2 text-center" id="repeated-row<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>" ><?php _e( 'Remove', 'wp-event-manager-sell-tickets' ); ?></a>
			
			<?php  foreach( $field['fields'] as $subkey => $subfield ) : ?>
					
						<?php							
							$subfield['name']  	= $key.'_'. $subkey.'_%%repeated-row-index%%' ;
							$subfield['id']  	=  $subkey ;	
							$subfield['attribute'] = $key;	
							get_event_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
						?>
    
			<?php endforeach; ?>
			</td>
			</tr>
	<?php
	echo esc_attr( ob_get_clean() );

?>">+  <?php if( ! empty( $field['label'] ) ){ echo $field['label'];};
?></a>

