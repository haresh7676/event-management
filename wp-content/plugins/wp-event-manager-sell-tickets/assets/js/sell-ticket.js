var SellTicket= function () {
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
			Common.logInfo("SellTicket.init...");
			
			//Add product to cart using ajax
            jQuery('#order_now').on('click', SellTicket.actions.addtoCart);
            
            //add links for paid and free tickets	
			jQuery( '.event_ticket_add_link' ).on('click',SellTicket.actions.addLink);
			
			//delete tickets 
			jQuery(document).delegate('.remove-row','click', SellTicket.actions.deleteTickets);
			
			//jQuery('[data-toggle="tooltip"]').tooltip(); 
			if(typeof(jQuery.fn.popover) != 'undefined'){
 				jQuery('[data-toggle="popover"]').popover();   
			}
			
			//in edit mode load data picker
			if(jQuery('.repeated-row').length > 0)
			{	
			var current_index = 0;		    		    
			    jQuery('.fieldset-paid_tickets').find(':input.repeated-row').each(function()
			    {
			    	current_index = this.value;   	
		            jQuery('#paid_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
    				jQuery('#paid_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate : 0,
    					dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
    					beforeShow: function(input, inst) {
							var mindate = jQuery('#paid_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
							return { minDate: mindate };
						}
    				});		
        				
			    });
			    jQuery('.fieldset-free_tickets').find(':input.repeated-row').each(function()
			    {
			    	current_index = this.value;
    			    jQuery('#free_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate : 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
    				jQuery('#free_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate : 0,
    					dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
    					beforeShow: function(input, inst) {
							var mindate = jQuery('#free_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
							return { minDate: mindate };
						}
    				});
        				
			    });
			    jQuery('.fieldset-donation_tickets').find(':input.repeated-row').each(function()
				{
					current_index = this.value; 
    			    jQuery('#donation_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate : 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
    				jQuery('#donation_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate 	: 0,
    					dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
    					beforeShow: function(input, inst) {
							var mindate = jQuery('#donation_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
							return { minDate: mindate };
						}
    				});
	    				
				});
				
			}
        },

	    actions:
	    {
	        
			 /// <summary>
	        /// On click add ticket link fields paid and free
	        //It will generate dynamic name and id for ticket fields.
	        /// </summary>                 
	        /// <returns type="generate name and id " />     
	        /// <since>1.0.0</since>  			
			addLink :function(event) {
			    
			    Common.logInfo("SellTicket.addLink...");
			    
				var $wrap     = jQuery(this).closest('.field');
				var max_index = 0;

					$wrap.find('input.repeated-row').each(function(){
					if ( parseInt( jQuery(this).val() ) > max_index ) {
						max_index = parseInt( jQuery(this).val() );
					}
				});
		
				var html = jQuery(this).data('row').replace( /%%repeated-row-index%%/g, max_index + 1 );
				//jQuery(this).before( html );
				jQuery(this).parent().parent().parent().find('.ticketwprs').append(html);
				
				//initial hide settings details			
			    jQuery( '.settings-details' ).hide();
			 
			    //load date on sales start and end 
			    var current_index = max_index+1;
			    if(jQuery('#paid_tickets_ticket_sales_start_date_'+current_index).length > 0 ){
			    	jQuery('#paid_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
					jQuery('#paid_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate	: 0,
						dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
						beforeShow: function(input, inst) {
							var mindate = jQuery('#paid_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
							return { minDate: mindate };
						}
					});	
			
					jQuery('#paid_tickets_ticket_sales_start_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	
	        		jQuery('#paid_tickets_ticket_sales_end_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	
			    }
			    

			    jQuery('#free_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
				jQuery('#free_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate	: 0,
					dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
					beforeShow: function(input, inst) {
				       var mindate = jQuery('#free_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
				       return { minDate: mindate };
				   }
				});	
				
				jQuery('#free_tickets_ticket_sales_start_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	
    			jQuery('#free_tickets_ticket_sales_end_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	

				jQuery('#donation_tickets_ticket_sales_start_date_'+current_index).datepicker({minDate	: 0,dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format});// minDate: '0' would work too
				jQuery('#donation_tickets_ticket_sales_end_date_'+current_index).datepicker({minDate	: 0,
					dateFormat 	: event_manager_sell_tickets_sell_ticket.i18n_datepicker_format,
					beforeShow: function(input, inst) {
				       var mindate = jQuery('#donation_tickets_ticket_sales_start_date_'+current_index).datepicker('getDate');
				       return { minDate: mindate };
				   }
				});	

				jQuery('#donation_tickets_ticket_sales_start_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	
    			jQuery('#donation_tickets_ticket_sales_end_time_'+current_index).timepicker({'timeFormat': event_manager_sell_tickets_sell_ticket.i18n_timepicker_format,'step' : event_manager_sell_tickets_sell_ticket.i18n_timepicker_step});	
				
				event.preventDefault();
			},		
			
			
			/// <summary>
	        /// Remove Paid and free tickets fields 
	        /// </summary>                 
	        /// <returns type="remove paid and free tickets fields" />     
	        /// <since>1.0.0</since>     
			
			deleteTickets: function(event)
			{
				Common.logInfo("SellTicket.deleteTickets...");
					
				if(confirm(event_manager_sell_tickets_sell_ticket.i18n_confirm_delete))
				{
					jQuery("."+event.target.id).remove();
				}
				event.preventDefault();
											
			},
			
			/// <summary>
	        /// add tickets to cart 
	        // when user click on single event page order now
	        /// </summary>                 
	        /// <returns type="generate " />     
	        /// <since>1.0.0</since>     
			addtoCart: function(event)
			{	
				Common.logInfo("SellTicket.addtoCart...");			

				var quantity= new Array("");
				var product_id=new Array("");
				var tickets_to_add = {};
				var total_ticket = jQuery('#total_ticket').val();
			
				if(  total_ticket <= 0  ){ return false;}
				
				for ( i = 0; i <total_ticket; i++) {
				        //if tickets quantity is 0 then it will check next ticket.
				        if(jQuery('#quantity-'+i).length  <= 0  || jQuery('#quantity-'+i).val() == 0   ){
				            continue;
				        }
						  quantity = jQuery('#quantity-'+i).val();
						  product_id = jQuery('#product-'+i).val();
						  donation_price = jQuery('#donation_price-'+i).val(); 
						  tickets_to_add[i] = {'product_id':product_id ,'quantity' : quantity ,'price' : donation_price };
				}
				if(  quantity <= 0  ){ 
				    jQuery('#sell-ticket-status-message').html( event_manager_sell_tickets_sell_ticket.i18n_no_ticket_found );
				    return false;
				    }
				
				jQuery.ajax({
								 type: 'POST',
								 url: event_manager_sell_tickets_sell_ticket.ajaxUrl.toString().replace("%%endpoint%%", "add_tickets_to_cart"),
								 data: 
								 {
									 'tickets_to_add' : tickets_to_add,
								 },
								beforeSend: function(jqXHR, settings) 
								{
								    Common.logInfo("Before send called...");
								     jQuery('#sell-ticket-status-message').addClass('alert-infomation');
								    jQuery('#sell-ticket-status-message').html(event_manager_sell_tickets_sell_ticket.i18n_loading_message); 
								    
								},
								success: function(data)
								{
								   jQuery('#sell-ticket-status-message').removeClass('alert-infomation');
								   jQuery('#sell-ticket-status-message').addClass('alert-success');
								   jQuery('#sell-ticket-status-message').html(event_manager_sell_tickets_sell_ticket.i18n_added_to_cart);
								   //window.location.href = event_manager_sell_tickets_sell_ticket.redirectUrl;
								},
								error: function(jqXHR, textStatus, errorThrown) 
								{ 		           
									jQuery('#sell-ticket-status-message').removeClass('success-green-message');
									jQuery('#sell-ticket-status-message').addClass('error-red-message');
								    jQuery('#sell-ticket-status-message').html(event_manager_sell_tickets_sell_ticket.i18n_error_message); 
								},
								complete: function (jqXHR, textStatus) 
								{			
								}
				        });
				
			event.preventDefault();
			},
	
	    } //end of action
	    
    }; //enf of return
	
	
	
}; //end of class
SellTicket= SellTicket();

jQuery(document).ready(function($) 
{
   SellTicket.init();
});