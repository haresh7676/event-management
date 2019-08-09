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
<?php if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) : ?>

	<?php foreach ( $field['value'] as $index => $value ) : ?>
 
		<div class="repeated-row-<?php echo esc_attr( $key ); ?> ticketwprs">
		<input type="hidden" class="repeated-row" data-tickettype="<?php echo esc_attr( $key ); ?>" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="<?php echo absint( $index ); ?>" />
            <h4 class="pull-right ticket-heading"><?php echo esc_attr(str_replace('_',' ',$key)); ?></h4>
         <ul class="nav nav-tabs">
             <li class="pull-right"><a class="ticket-notice-info" data-toggle="popover" data-trigger="hover"   data-placement="top" data-content="<?php _e('You can\'t delete ticket once it is added.You can make it private from settings tab.','wp-event-manager');?>" > <span class="glyphicon glyphicon-info-sign"></span></a></li>
            <li class="active"><a data-toggle="tab" href="#sell-ticket-details-<?php echo $key . '-' . $index; ?>"><?php _e('Ticket Details','wp-event-manager');?></a></li>
            <li><a data-toggle="tab" href="#<?php echo $key . '_' . $index; ?>"><?php _e('Settings','wp-event-manager');?></a></li>
          </ul>
            <div class="tab-content">
                <div id="sell-ticket-details-<?php echo $key . '-' . $index; ?>" class="tab-pane fade in active">
                    <div class="ticket-inner-wpr">
                  <?php foreach ( $field['fields'] as $subkey => $subfield ) : 
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
    							        get_event_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
                                    ?>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    <!--</div>-->
                <?php endforeach; ?>
                </div><!------------end settings tab------>
		    </div>
	<?php endforeach; ?>
<?php endif; ?>

<a href="#" class="event_ticket_add_link" data-type="<?php echo esc_attr( $key ); ?>" data-row="<?php
	ob_start();
	?>
		<div class="repeated-row-<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>">
		
		<input type="hidden" class="repeated-row" data-tickettype="<?php echo esc_attr( $key ); ?>" name="repeated-row-<?php echo esc_attr( $key ); ?>[]" value="%%repeated-row-index%%" />
            <h4 class="pull-right ticket-heading"><?php echo esc_attr(str_replace('_',' ',$key)); ?></h4>
		<ul class="nav nav-tabs">
            <li class="pull-right"><a href="#remove" class="remove-row" id="repeated-row-<?php echo esc_attr( $key.'_%%repeated-row-index%%' ); ?>" ><?php _e( 'Remove', 'wp-event-manager' ); ?><i class="far fa-trash-alt"></i></a></li>
            <li><a class="active" data-toggle="tab" href="#sell-ticket-details_%%repeated-row-index%%"><?php _e('Ticket Details','wp-event-manager');?><i class="far fa-copy"></i></a></li>
            <li><a data-toggle="tab" href="#<?php echo $key ;?>_%%repeated-row-index%%"><?php _e('Settings','wp-event-manager');?><i class="fas fa-cog"></i></a></li>
            
          </ul>
            <div class="tab-content">
                <div id="sell-ticket-details_%%repeated-row-index%%" class="tab-pane fade in active">
                    <div class="ticket-inner-wpr">
                <?php  foreach ( $field['fields'] as $subkey => $subfield ) : 
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
                                <label for="<?php esc_attr_e( $subkey ); ?>"><?php echo $subfield['label'] . ( $subfield['required'] ? '' : ' <small>' . __( '(optional)', 'wp-event-manager' ) . '</small>' ); ?></label>
                           </div>
    					   <?php endif; ?>
        					<div class="col-md-12">
            					<div class="field">
            					 <div>
            						<?php							
            							$subfield['name']  = $key . '_' . $subkey . '_%%repeated-row-index%%';
            							$subfield['id']  = $key . '_' . $subkey . '_%%repeated-row-index%%';	
            							get_event_manager_template( 'form-fields/' . $subfield['type'] . '-field.php', array( 'key' => $subkey, 'field' => $subfield ) );
            						?>
            						</div>
            					</div>
        					</div>
                        </div>
    				</fieldset>   
    		<?php endforeach; ?>
                </div><!------------end settings tab------>     
		</div>
	<?php
	echo esc_attr( ob_get_clean() );
?>">+ <?php if( ! empty( $field['label'] ) ){ echo $field['label'];};
?></a>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
