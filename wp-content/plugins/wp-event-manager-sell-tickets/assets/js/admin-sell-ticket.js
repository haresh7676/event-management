var SellTicketAdmin = function () {
    /// <summary>Constructor function of the event SellTicket class.</summary>
    /// <returns type="Home" />

    return {

	    ///<summary>
        ///Initializes the sell ticket.  
        ///</summary>     
        ///<returns type="initialization settings" />   
        /// <since>1.0.0</since> 
        init: function() 
        {
        	if(jQuery( '#tickets_view_table' ).length > 0 )
            {
            //add links for paid and free tickets	
			jQuery( '.event_ticket_add_link' ).on('click',SellTicketAdmin.actions.addLink);
			
			//delete tickets 
			jQuery(document).delegate('.remove-row','click', SellTicketAdmin.actions.removeTickets);
			
			//save tickets
			jQuery(document).delegate( '#ticket_form_save','click',SellTicketAdmin.actions.saveTickets);
			
			//Delete tickets which is already saved
			jQuery( '.delete_tickets' ).on('click',SellTicketAdmin.actions.deleteTickets);
			
			//edit link
			jQuery( '.edit_ticket' ).on('click',SellTicketAdmin.actions.editTicket);
			jQuery("td[colspan=5]").hide();
			
			//hide the ajax loader div 
			jQuery('.loading').hide();
			
		    //ticket date picker		    		    
		    if(jQuery('.ticket_sales_start_date').length > 0 )
		    {   
		            jQuery('.ticket_sales_start_date').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
    				jQuery('.ticket_sales_end_date').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format});	        				
		    }
		    
			if(jQuery('[data-picker="timepicker"]').length > 0){
				jQuery('[data-picker="timepicker"]').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});
			}

            }
        },

	    actions:
	    {
	        
			/// <summary>
			/// On click add ticket link fields paid and free
			///It will generate dynamic name and id for ticket fields.
			/// </summary>                 
			/// <returns type="generate name and id " />     
			/// <since>1.0.0</since>  			
			addLink :function(event) {
				var max_index = 0;
					if(jQuery('#new_tickets_fields').find('tr').length){
						jQuery('#new_tickets_fields').find('tr').each(function(){
						max_index ++ ;
						});
					}
				max_index = max_index + 1
				var html = jQuery(this).data('row').replace( /%%repeated-row-index%%/g, max_index );
				
				jQuery('#new_tickets_fields').append( html );
				
				if(jQuery('input[name=_paid_tickets_ticket_sales_start_date_'+max_index+']').length > 0 ){
					//for paid ticket
					jQuery('input[name=_paid_tickets_ticket_sales_start_date_'+max_index+']').datepicker({minDate	: 0, dateFormat 	:  event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too 
					jQuery('input[name=_paid_tickets_ticket_sales_end_date_'+max_index+']').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format,
						beforeShow: function(input, inst) {
							var mindate = jQuery('input[name=_paid_tickets_ticket_sales_start_date_'+max_index+']').datepicker('getDate');
							return { minDate: mindate };
						}
					});	

					jQuery('input[name=_paid_tickets_ticket_sales_start_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
	        		jQuery('input[name=_paid_tickets_ticket_sales_end_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
				}
					
		        if(jQuery('input[name=_free_tickets_ticket_sales_start_date_'+max_index+']').length > 0 ){
		        	//for free ticket
				    jQuery('input[name=_free_tickets_ticket_sales_start_date_'+max_index+']').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
					jQuery('input[name=_free_tickets_ticket_sales_end_date_'+max_index+']').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format,
						beforeShow: function(input, inst) {
							var mindate = jQuery('input[name=_free_tickets_ticket_sales_start_date_'+max_index+']').datepicker('getDate');
							return { minDate: mindate };
						}
					});	

					jQuery('input[name=_free_tickets_ticket_sales_start_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
	        		jQuery('input[name=_free_tickets_ticket_sales_end_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
		        }
		        
				if(jQuery('input[name=_donation_tickets_ticket_sales_start_date_'+max_index+']').length > 0 ){
					//donation
					jQuery('input[name=_donation_tickets_ticket_sales_start_date_'+max_index+']').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
					jQuery('input[name=_donation_tickets_ticket_sales_end_date_'+max_index+']').datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_admin_sell_ticket.i18n_datepicker_format,
						beforeShow: function(input, inst) {
							var mindate = jQuery('input[name=_donation_tickets_ticket_sales_start_date_'+max_index+']').datepicker('getDate');
							return { minDate: mindate };
						}
					});	

					jQuery('input[name=_donation_tickets_ticket_sales_start_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
	        		jQuery('input[name=_donation_tickets_ticket_sales_end_time_'+max_index+']').timepicker({'timeFormat': event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_admin_sell_ticket.i18n_timepicker_step});	
						
				}
				
				event.preventDefault();
			},
			/// <summary>
	        /// Remove Paid and free tickets fields 
	        /// </summary>                 
	        /// <returns type="remove paid and free tickets fields" />     
	        /// <since>1.0.0</since>     
			
			removeTickets: function(event)
			{	
				if(confirm('Are you sure you want to delete this ticket?'))
				{
					jQuery("."+event.target.id).remove();
				}
			 event.preventDefault();
			},
			
			/// <summary>
	        /// Delete Paid and free tickets from event manager meta key and from woocommerce product  
	        /// </summary>                 
	        /// <returns type="remove paid and free tickets fields" />     
	        /// <since>1.0.0</since>     
			
			deleteTickets: function(event)
			{	
				if(confirm('Are you sure you want to delete this ticket?'))
				{
					jQuery("."+event.target.id).remove();
					var delete_id = jQuery(this).attr('href');
					SellTicketAdmin.actions.saveTickets()
					jQuery.ajax({
							 type: 'POST',
							 url : event_manager_sell_tickets_admin_sell_ticket.ajaxUrl.toString().replace("%%endpoint%%", "delete_tickets_meta_box"),
							 data :{delete_id : delete_id},			 
							 
							beforeSend: function(jqXHR, settings) 
							{},
							success: function(response)
							{},
							error: function(jqXHR, textStatus, errorThrown) 
							{
								console.log(errorThrown);
							},
							complete: function (jqXHR, textStatus) 
							{}
				        });
					
				}
				event.preventDefault();
											
			},
			/// <summary>
	        /// show and hide edit tickets filds 
	        /// </summary>                 
	        /// <returns type="edit paid and free tickets fields" />     
	        /// <since>1.0.0</since>     
			
			editTicket: function(event)
			{
				event.stopPropagation();
				var $target = jQuery(event.target);
				if ( $target.closest("td").attr("colspan") > 1 ) {
					$target.slideUp();
				} else {
					$target.closest("tr").next().find("td").slideToggle();
				}              
				event.preventDefault();
			},
			/// <summary>
	        /// save all the tickets
	        /// </summary>                 
	        /// <returns type="save paid and free tickets fields" />     
	        /// <since>1.0.0</since>     
			
			saveTickets: function(event)
			{
				var counter = 0;
				var paid_ticket_count = 0;
				var paid_tickets = {};
				var temp_paid_tickets = {};
				
				var free_counter = 0;
				var free_ticket_count = 0;
				var temp_free_tickets = {};
				var free_tickets = {}; 
				
				var donation_counter = 0;
				var donation_ticket_count = 0;
				var temp_donation_tickets = {};
				var donation_tickets = {}; 
				//this loop will get all the fields from meta box and store into one array this array will send in ajax
				jQuery('#tickets_meta_box').find("select, textarea, input").each(function(){
						if(jQuery(this).attr('attribute') == '_paid_tickets' ){
						    if( jQuery(this).is( ":checkbox" ) ){
						        if(jQuery(this).is(':checked')){
						           temp_paid_tickets[this.id] = 1; // temp_paid_tickets['control name '] = control value
						        }
						        else {
						                temp_paid_tickets[this.id] = 0;
						            }
						    }
						    else{
							    temp_paid_tickets[this.id] = this.value; 
						    }
							counter++;
							if(counter >= jQuery(this).closest("tr").attr('fields_count') ){
						
								paid_tickets[paid_ticket_count] = temp_paid_tickets;
								paid_ticket_count++;
								counter = 0;
								temp_paid_tickets = {};
							
							}
						}
						else if(jQuery(this).attr('attribute') == '_free_tickets' ){
						    if( jQuery(this).is( ":checkbox" ) ){
						        if(jQuery(this).is(':checked')){
						           temp_free_tickets[this.id] = 1; // temp_free_tickets['control name '] = control value
						        }
						        else {
						                temp_free_tickets[this.id] = 0;
						            }
						    }
						    else{
							    temp_free_tickets[this.id] = this.value; 
						    }
							free_counter++;
							if(free_counter >= jQuery(this).closest("tr").attr('fields_count') ){
								
								free_tickets[free_ticket_count] = temp_free_tickets;
								free_ticket_count++;
								free_counter = 0;
								temp_free_tickets = {};							
							}
						}
						//donation
						else if(jQuery(this).attr('attribute') == '_donation_tickets' ){
						    if( jQuery(this).is( ":checkbox" ) ){
						        if(jQuery(this).is(':checked')){
						           temp_donation_tickets[this.id] = 1; // temp_donation_tickets['control name '] = control value
						        }
						        else {
						                temp_donation_tickets[this.id] = 0;
						            }
						    }
						    else{
							    temp_donation_tickets[this.id] = this.value; 
						    }
							donation_counter++;
							if(donation_counter >= jQuery(this).closest("tr").attr('fields_count') ){
								
								donation_tickets[donation_ticket_count] = temp_donation_tickets;
								donation_ticket_count++;
								donation_counter = 0;
								temp_donation_tickets = {};							
							}
						}
						
					});
					
				var event_id = jQuery('#event_id').val();
				var dataObject =  new Object();
				dataObject = { _paid_tickets :  paid_tickets,
							   _free_tickets :  free_tickets,
							   _donation_tickets :  donation_tickets,
							   event_id		 :  event_id
							 };
				
				jQuery.ajax({
						type: 'POST',
						url : event_manager_sell_tickets_admin_sell_ticket.ajaxUrl.toString().replace("%%endpoint%%", "save_tickets_meta_box"),
						data : dataObject,			 
						 
						beforeSend: function(jqXHR, settings) 
						{
							console.log("Before Send...");
							jQuery('#sell-ticket-meta-box .inside').html(jQuery('.loading').show());								
						},
						success: function(response)
						{
							jQuery('#sell-ticket-meta-box .inside').html(response);
							
							
							jQuery( '.event_ticket_add_link' ).on('click',SellTicketAdmin.actions.addLink);
		
							
							//edit link
							jQuery( '.edit_ticket' ).on('click',SellTicketAdmin.actions.editTicket);
							jQuery("td[colspan=5]").hide();
							
							//save tickets
							jQuery( '#ticket_form_save' ).on('click',SellTicketAdmin.actions.saveTickets);
							
							//Delete tickets which is already saved
							jQuery( '.delete_tickets' ).on('click',SellTicketAdmin.actions.deleteTickets);
							
							//hide the ajax loader div 
							jQuery('.loading').hide();
						},
						error: function(jqXHR, textStatus, errorThrown) 
						{
							console.log(errorThrown);	
						},
						complete: function (jqXHR, textStatus) 
						{}
				        });
								
				
			},
			
		
		} //end of action
	    
    }; //enf of return
}; //end of class
SellTicketAdmin= SellTicketAdmin();

jQuery(document).ready(function($) 
{
   SellTicketAdmin.init();
});