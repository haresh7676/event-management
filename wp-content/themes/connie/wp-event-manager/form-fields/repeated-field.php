<?php 
/**
 * Repeated fields is generated from this page .
 * Repeated fields for the paid and free tickets.
 * This is field is used in submit event form.
 **/
?>

<?php 
    $datedfields = array();
    $datedfields['datefields'] = array('ticket_sales_start_date','ticket_sales_end_date');
    $datedfields['timefields'] = array('ticket_sales_start_time','ticket_sales_end_time');
?>


<a href="#" class="event_ticket_add_link" data-type="<?php echo esc_attr( $key ); ?>" data-row="<?php
	ob_start();
	?>
		<div class="repeated-row-<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>">
		
		<input type="hidden" class="repeated-row" data-tickettype="<?php echo esc_attr( $key ); ?>" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="%%repeated-row-index%%" />
            <h4 class="pull-right ticket-heading"><?php echo esc_attr(str_replace('_',' ',$key)); ?></h4>
		<ul class="nav nav-tabs">
            <li class="pull-right"><a href="#remove" class="remove-row" id="repeated-row-<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>" ><?php _e( 'Remove', 'wp-event-manager' ); ?><i class="far fa-trash-alt"></i></a></li>
            <li><a class="active" data-toggle="tab" href="#<?php echo $key ;?>-details_%%repeated-row-index%%"><?php _e('Ticket Details','wp-event-manager');?><i class="far fa-copy"></i></a></li>
            <li><a data-toggle="tab" href="#<?php echo $key ;?>_%%repeated-row-index%%"><?php _e('Settings','wp-event-manager');?><i class="fas fa-cog"></i></a></li>
            
          </ul>
            <div class="tab-content">
                <div id="<?php echo $key ;?>-details_%%repeated-row-index%%" class="tab-pane fade in active">
                    <div class="ticket-inner-wpr">
                <?php  
                if(isset($field['fields']['show_remaining_tickets'])){
                    unset($field['fields']['show_remaining_tickets']);
                }
                if(isset($field['fields']['ticket_individually'])){
                 unset($field['fields']['ticket_individually']);   
                }

                foreach ( $field['fields'] as $subkey => $subfield ) : 
                                if ($subkey == 'ticket_description') : ?>           
                </div><!------------end ticket details tab------>
                </div><!------ end wpr div ------>
                <div id="<?php echo $key; ?>_%%repeated-row-index%%" class="tab-pane fade setting-description">
                <?php endif;?>
                <?php 
                    $customclass = ''; /*Add custom Class */
                    if(in_array($subkey, $datedfields['datefields'])){ 
                        $customclass =' form-group field_mideam calicon'; 
                    }
                    if(in_array($subkey, $datedfields['timefields'])){
                        $customclass =' form-group field_small';
                    }
                    ?>
                
                    <fieldset class="fieldset-<?php esc_attr_e( $subkey ); ?><?php esc_attr_e( $customclass ); ?>">
                        <div class="row">
                        <?php if(!empty($subfield['label'])) : ?>
                           <div class="col-md-12">
                                <label for="<?php esc_attr_e( $subkey ); ?>"><?php echo $subfield['label'] . ( $subfield['required'] ? '<span class="require-field">*</span>' : ' <small>' . __( '(optional)', 'wp-event-manager' ) . '</small>' ); ?></label>
                           </div>
    					   <?php endif; ?>
        					<div class="col-md-12">
            					<div class="field">
            					 <div>
            						<?php							
            							$subfield['name']  = $key . '_' . $subkey . '_%%repeated-row-index%%';
            							$subfield['id']  = $key . '_' . $subkey . '_%%repeated-row-index%%';
                                        if($subkey == 'ticket_price' || $subkey == 'ticket_quantity'){
                                            $subfield['min']  = 0;
                                            $subfield['max']  = 9999999;
                                            $subfield['maxlength']  = 7;
                                        }
            							get_event_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
            						?>
            						</div>
            					</div>
        					</div>
                        </div>
    				</fieldset>   
    		<?php endforeach; ?>
            <fieldset class="fieldset-ticket_maximum settingsavebtn">
                <a class="defulat-custom-btn returntodetails" href="javascript:void(0);" data-href="#<?php echo $key ;?>-details_%%repeated-row-index%%"><?php _e('Save','wp-event-manager');?></a>
            </fieldset>
                </div><!------------end settings tab------>     
		</div>
	<?php
	echo esc_attr( ob_get_clean() );
?>">+ <?php if( ! empty( $field['label'] ) ){ echo $field['label'];};
?></a>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
