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
<?php if ( ! empty( $field['value'] ) && is_array( $field['value'] )) : ?>

	<?php     
    foreach ( $field['value'] as $index => $value ) : ?>
 
		<div class="repeated-row-<?php echo esc_attr( $key ); ?>">
		<input type="hidden" class="repeated-row" data-tickettype="<?php echo esc_attr( $key ); ?>" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="<?php echo absint( $index ); ?>" />
            <h4 class="pull-right ticket-heading"><?php echo esc_attr(str_replace('_',' ',$key)); ?></h4>
         <ul class="nav nav-tabs">
             <!-- <li class="pull-right"><a class="ticket-notice-info1" data-toggle="popover" data-trigger="hover"   data-placement="top" data-content="<?php _e('You can\'t delete ticket once it is added.You can make it private from settings tab.','wp-event-manager');?>" > <span class="glyphicon glyphicon-info-sign"></span><?php _e( 'Remove', 'wp-event-manager' ); ?><i class="far fa-trash-alt"></i></a></li> -->
            <li><a class="active" data-toggle="tab" href="#<?php echo $key ;?>-details-<?php echo $key . '-' . $index; ?>"><?php _e('Ticket Details','wp-event-manager');?><i class="far fa-copy"></i></a></li>
            <li><a data-toggle="tab" href="#<?php echo $key . '_' . $index; ?>"><?php _e('Settings','wp-event-manager');?><i class="fas fa-cog"></i></a></li>
          </ul>
            <div class="tab-content">
                <div id="<?php echo $key ;?>-details-<?php echo $key . '-' . $index; ?>" class="tab-pane fade in active">
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
                <div id="<?php echo $key . '_' . $index; ?>" class="tab-pane fade setting-description">
                            <?php endif;?>        
                 <!--<div class="row">-->
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
                             <div class="col-md-12"> <label for="<?php esc_attr_e( $subkey ); ?>"><?php echo $subfield['label'] . ( $subfield['required'] ? '' : ' <small>' . __( '(optional)', 'wp-event-manager' ) . '</small>' ); ?></label></div>
    					   <?php endif; ?>
    					   <div class="col-md-12">
                                <div class="field">
                                    <?php                                
                                        $subfield['name']  = $key . '_' . $subkey . '_' . $index;
    									$subfield['id']  =$key . '_' . $subkey . '_' . $index;   
    							        $subfield['value'] = isset( $value[ $subkey ]) ? $value[ $subkey ] : '';
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
                        </fieldset>
                    <!--</div>-->
                <?php endforeach; ?>
                <fieldset class="fieldset-ticket_maximum settingsavebtn">
                    <a class="defulat-custom-btn returntodetails" href="javascript:void(0);" data-href="#<?php echo $key ;?>-details-<?php echo $key . '-' . $index; ?>"><?php _e('Save','wp-event-manager');?></a>
                </fieldset>
                </div><!------------end settings tab------>
		    </div>
        </div>
	<?php endforeach; ?>
<?php endif; ?>
