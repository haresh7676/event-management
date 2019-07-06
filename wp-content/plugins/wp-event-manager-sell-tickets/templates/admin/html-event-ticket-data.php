<?php
global $post;
$post_id = $post->ID;
$event_id = get_post_meta($post_id,'_event_id',true );
?>
<div class="options_group show_if_event_ticket show_if_event_ticket_subscription">
	 <p class="form-field">
	 <label for="event_ids"><?php esc_html_e( 'Events', 'woocommerce' ); ?></label>
		<select class="woo-select"  style="width: 50%;" id="event_id" name="event_id" data-placeholder="<?php esc_attr_e( 'Search for a event&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_events" data-exclude="">
			<?php

			$args = array(
                'post_type' 	=> 'event_listing',
           		'post_status'	=> array('publish')
            	);
        	$event_objects = get_posts($args);
			foreach ( $event_objects as $event ) {
			

				echo '<option value="' . esc_attr( $event->ID) . '" '.selected( $event_id, $event->ID ).' >' . $event->post_title . '</option>';
			}
			?>
		</select> <?php echo wc_help_tip( __( 'Event id', 'woocommerce' ) ); // WPCS: XSS ok. ?>
		</p>
	<?php
	$datepicker_date_format 	= WP_Event_Manager_Date_Time::get_datepicker_format();
	$php_date_format 		    = WP_Event_Manager_Date_Time::get_view_date_format_from_datepicker_date_format( $datepicker_date_format );
	$time_format                = WP_Event_Manager_Date_Time::get_timepicker_format();
	
	$sales_start_date = get_post_meta($post_id,'_ticket_sales_start_date',true );
	$sales_end_date = get_post_meta($post_id,'_ticket_sales_end_date',true );
	
	$start_time       = date($time_format,strtotime($sales_start_date));
	$end_time         = date($time_format,strtotime($sales_end_date));
	
	$sales_start_date 	= date( $php_date_format, strtotime( $sales_start_date ) );
	$sales_end_date 	= date( $php_date_format, strtotime( $sales_end_date ) );
	

				woocommerce_wp_checkbox(
									    array(
									      'id' => '_show_ticket_description',
									      'label' => __( 'Show Ticket Description', 'wp-event-manger-sell-tickets' ),
									      'placeholder' => '',
									      'desc_tip' => 'true',
									      'description' => __( 'Show ticket description on event page
', 'wp-event-manger-sell-tickets' ),
									      'type' => 'checkbox'
									    )
									    );

				woocommerce_wp_radio(
									array(
									  'id' 				=> '_ticket_fee_pay_by',
									  'label' 			=> __( 'Fees Pay By', 'wp-event-manger-sell-tickets' ),
									  'placeholder' 	=> '',
									  'desc_tip' 		=> 'true',
									  'description' 	=> __( 'Pay by attendee : fees will be added to the ticket price and paid by the attendee .', 'wp-event-manger-sell-tickets' ),
									  'type' 			=> 'radio',
									  'options'			=> array(		
									  								'ticket_fee_pay_by_attendee'  => __('Pay By Attendee','wp-event-manger-sell-0'),
									  								'ticket_fee_pay_by_organizer' => __( 'Pay By Organizer','wp-event-manger-sell-tickets')
									  							)
									)
								);

				woocommerce_wp_text_input(
										    array(
											      'id' => 'minimum_order',
											      'label' => __( 'Minimum Tickets', 'wp-event-manger-sell-tickets' ),
											      'placeholder' => '',
											      'desc_tip' => 'true',
											      'description' => __( 'Minimum tickets allowed per order', 'wp-event-manger-sell-tickets' ),
											      'type' => 'number',
											      'custom_attributes' => array(
																	            'step' => 'any',
																	            'min' => '0'
																	  	 )
										    )
    									);
				woocommerce_wp_text_input(
										    array(
										      'id' => 'maximum_order',
										      'label' => __( 'Maximum Tickets', 'wp-event-manger-sell-tickets' ),
										      'placeholder' => '',
										      'desc_tip' => 'true',
										      'description' => __( 'Minimum tickets allowed per order', 'wp-event-manger-sell-tickets' ),
										      'type' => 'number',
											      'custom_attributes' => array(
																	            'step' => 'any',
																	            'min' => '0'
																	  	 )
										    )
    									);

				woocommerce_wp_checkbox(
										    array(
										      'id' => '_remaining_tickets',
										      'label' => __( 'Show remaining tickets', 'wp-event-manger-sell-tickets' ),
										      'placeholder' => '',
										      'desc_tip' => 'true',
										      'description' => __( 'Show remaining tickets', 'wp-event-manger-sell-tickets' ),
										      'type' => 'checkbox'
										    )
    									);
				woocommerce_wp_text_input(
										array(
											'id' => '_ticket_sales_start_date',
											'label' => __( 'Sales Start Date', 'wp-event-manger-sell-tickets' ),
											'placeholder' => '',
											'desc_tip' => 'true',
											'description' => __( 'Tickets sales start date', 'wp-event-manger-sell-tickets' ),
											'type' => 'text',
											'value' =>  $sales_start_date
											
										)
									);
				woocommerce_wp_text_input(
										array(
												'id' => '_ticket_sales_start_time',
												'label' => __( 'Sales Start Time', 'wp-event-manger-sell-tickets' ),
												'placeholder' => '',
												'desc_tip' => 'true',
												'description' => __( 'Tickets sales start time', 'wp-event-manger-sell-tickets' ),
												'type' => 'text',
												'value'   => $start_time
										)
				);
				woocommerce_wp_text_input(
									array(
										'id' => '_ticket_sales_end_date',
										'label' => __( 'Sales End Date', 'wp-event-manger-sell-tickets' ),
										'placeholder' => '',
										'desc_tip' => 'true',
										'description' => __( 'Tickets sales end date', 'wp-event-manger-sell-tickets' ),
										'type' => 'text',
										'value' => $sales_end_date
									)
				);
				woocommerce_wp_text_input(
									array(
										'id' => '_ticket_sales_end_time',
										'label' => __( 'Sales End Time', 'wp-event-manger-sell-tickets' ),
										'placeholder' => '',
										'desc_tip' => 'true',
										'description' => __( 'Tickets sales end time', 'wp-event-manger-sell-tickets' ),
										'type' => 'text',
										'value'   => $end_time
									)
				);
				

?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('#product-type').change( function() {
			jQuery('#woocommerce-product-data').removeClass(function(i, classNames) {
				var classNames = classNames.match(/is\_[a-zA-Z\_]+/g);
				if ( ! classNames ) {
					return '';
				}
				return classNames.join(' ');
			});
			jQuery('#woocommerce-product-data').addClass( 'is_' + jQuery(this).val() );
		} );
	
		
		//pricing and tax
        jQuery('.pricing').addClass( 'show_if_event_ticket' );
		
		jQuery('#product-type').change();
		jQuery('.stock').addClass( 'show_if_event_ticket' );
        //inventory tab
        jQuery('.inventory_options').addClass('show_if_event_ticket').show();
        jQuery('#inventory_product_data ._manage_stock_field').addClass('show_if_event_ticket').show();
        jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_event_ticket').show();
        jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_event_ticket').show();
        
        
        
        jQuery('#_ticket_sales_start_date').datepicker({minDate	: 0,dateFormat 	: '<?php echo WP_Event_Manager_Date_Time::get_datepicker_format();?>' });// minDate: '0' would work too
				jQuery('#_ticket_sales_end_date').datepicker({minDate	: 0,
					dateFormat 	: '<?php echo WP_Event_Manager_Date_Time::get_datepicker_format();?>' ,
					beforeShow: function(input, inst) {
				       var mindate = jQuery('#_ticket_sales_start_date').datepicker('getDate');
				       return { minDate: mindate };
				   }
				});	
		jQuery('#_ticket_sales_start_time').timepicker({'timeFormat': '<?php echo WP_Event_Manager_Date_Time::get_timepicker_format();?>','step' : '<?php echo WP_Event_Manager_Date_Time::get_timepicker_step();?>'});	
    	jQuery('#_ticket_sales_end_time').timepicker({'timeFormat': '<?php echo WP_Event_Manager_Date_Time::get_timepicker_format();?>','step' : '<?php echo WP_Event_Manager_Date_Time::get_timepicker_step();?>'});	

				
				
		$('.woo-select').selectWoo();

	});
</script>
</div>
