EventSubmission= function () {

    /// <summary>Constructor function of the event EventSubmission class.</summary>
    /// <returns type="EventSubmission" />         
    return {        
	    ///<summary>
        ///Initializes the event submission. 
        ///</summary>     
        ///<returns type="initialization settings" />   
        /// <since>1.0.0</since> 
        init: function() 
        {			
			   Common.logInfo("EventSubmission.init...");   

			   jQuery( "form#submit-event-form" ).submit(function( event ) {			   	
			   	var selectedDate = jQuery('input[data-picker="datepicker"]#event_start_date').val();
			   	var selectedDate = new Date(selectedDate);
			   	selectedDate.setHours(0);
            	selectedDate.setMinutes(0);
            	selectedDate.setSeconds(0);
			   	var selectedAcion = jQuery('input[name="event_manager_form"]').val();			   				   	
            	var today = new Date();
            	today.setHours(0);
            	today.setMinutes(0);
            	today.setSeconds(0);
            	console.log(selectedDate);
            	console.log(today);            	
            	if( jQuery('.ticketwprs > div').length <= 0 ){
			    	swal({
			    		type: 'error',
	                    title: 'Any single ticket at least should be required.',                    
	                    showCloseButton: true,
	                    showConfirmButton: false            
	                });
			    	//alert('');
			    	return false;		
			    } else if (Date.parse(selectedDate) < Date.parse(today) && selectedAcion != 'edit-event') {
            		swal({
			    		type: 'error',
	                    title: 'Event date must be greater than equal today\'s date',                    
	                    showCloseButton: true,
	                    showConfirmButton: false            
	                });
            		return false;		
            	}
			    else{
			    	
			    }			    

			   });			    
			    

			   jQuery('body').on( 'click', '.event-manager-remove-uploaded-file', function() 
			   {
			       jQuery(this).closest( '.event-manager-uploaded-file' ).remove();
				    return false;		
			   });
				 if(jQuery( '#event_start_time' ).length > 0)
				{			
					jQuery('#event_start_time').timepicker({ 
												'timeFormat': wp_event_manager_event_submission.i18n_timepicker_format,
												'step': wp_event_manager_event_submission.i18n_timepicker_step,
												'showOn': ['click','keyup'],
												'disableTextInput': true,
											});
				}
				
				if(jQuery( '#event_end_time' ).length > 0)
				{
					jQuery('#event_end_time').timepicker({ 
												'timeFormat': wp_event_manager_event_submission.i18n_timepicker_format ,
												'step': wp_event_manager_event_submission.i18n_timepicker_step,
												'showOn': ['click','keyup'],
												'disableTextInput': true				
				    							});
				}
			

                   //EventSubmission.timeFormatSettings();
				  				
				     if(jQuery( 'input[data-picker="datepicker"]#event_start_date' ).length > 0)
				     {						
        				jQuery('input[data-picker="datepicker"]#event_start_date').datepicker({
        					minDate 	: 0,
        					dateFormat 	: wp_event_manager_event_submission.i18n_datepicker_format,
                            beforeShow  : function (input, inst) {
                                setTimeout(function(){
                                    inst.dpDiv.outerWidth(jQuery(input).outerWidth());
                                },0);
                            },
                            onSelect: function (dateText, inst) {                            	
                            	var mindate = jQuery('input[data-picker="datepicker"]#event_end_date').val();                            	
                            	if(mindate == ''){                            		
                            		jQuery('input[data-picker="datepicker"]#event_end_date').datepicker('setDate', dateText);
                            		jQuery('input[data-picker="datepicker"]#event_end_date').trigger('change');
                            	}else
                            	if(mindate < dateText){                            		
                            		jQuery('input[data-picker="datepicker"]#event_end_date').datepicker('setDate', dateText);
                            		jQuery('input[data-picker="datepicker"]#event_end_date').trigger('change');
                            		//jQuery('input[data-picker="datepicker"]#event_end_date').val('');	
                            	}
                            	/*var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()).getTime();
                            	var selected = new Date(dateText).getTime();
                            	if (today > selected) 
                            	else if (today < selected)
                            	else alert('today');*/			
                            	var selectedDate = jQuery('input[data-picker="datepicker"]#event_start_date').datepicker('getDate');
                            	var today = new Date();
                            	today.setHours(0);
                            	today.setMinutes(0);
                            	today.setSeconds(0);                            	
                            	if (Date.parse(today) == Date.parse(selectedDate)) {
                            		const start = moment();
                            		const remainder = 30 - (start.minute() % 30);								 
                            		const dateTime = moment(start).add(remainder, "minutes").format("hh:mm A");                            		
                            		if(dateTime != ''){
                            			jQuery('#event_start_time').timepicker('option',{minTime: dateTime, maxTime: '11:30PM'});
                            			if(jQuery('#event_start_time').val() != ''){
                            				var start_time = jQuery('#event_start_time').val();
                            				var stt = new Date("November 13, 2013 " + start_time);
	                            			stt = stt.getTime();	                            			
	                            			var endt = new Date("November 13, 2013 " + dateTime);
	                            			endt = endt.getTime();	                            			
	                            			if(stt < endt) {	                            				
	                            				jQuery('#event_start_time').timepicker('setTime', dateTime).trigger('change');	                            				
	                            			}
                            			}
                            		}
                            		/*if(jQuery('#event_start_time').val() != '' && jQuery('#event_start_time').val() > dateTime){
                            			
                            		}*/
                            	}else{
                            		jQuery('#event_start_time').timepicker('option', 'minTime', '12:00AM');                            		
                            	}
                            }
        				}).keydown(false);
				     }
				
				     if(jQuery( 'input[data-picker="datepicker"]#event_end_date' ).length > 0)
				     {
        				jQuery('input[data-picker="datepicker"]#event_end_date').datepicker({
        									 dateFormat 	: wp_event_manager_event_submission.i18n_datepicker_format ,
											 beforeShow: function(input, inst) {
											       var mindate = jQuery('input[data-picker="datepicker"]#event_start_date').datepicker('getDate');
											       jQuery(this).datepicker('option', 'minDate', mindate);
                                                 setTimeout(function(){
                                                     inst.dpDiv.outerWidth(jQuery(input).outerWidth());
                                                 },0);
											   }
        				}).on('change', function(){
                            // set the "event_start_date" end to not be later than "event_end_date" starts:
                           //jQuery('#event_start_date').datepicker('minDate', new Date(jQuery(this).val()));
                        }).keydown(false);
				     }

				     if (jQuery('#eventsExample').length > 0) {
				        var eventsExampleEl = document.getElementById('eventsExample');
				        var eventsExampleDatepair = new Datepair(eventsExampleEl);

				        jQuery('#eventsExample').on('rangeSelected', function(){
				            console.log('Valid range selected');
				        }).on('rangeIncomplete', function(){
				            console.log('Incomplete range');
				        }).on('rangeError', function(){
				            console.log('Invalid range');
				        });
				    }

			    if( jQuery('input[data-picker="datepicker"]').length > 0 ){
						jQuery('input[data-picker="datepicker"]').datepicker({minDate : 0,dateFormat : wp_event_manager_event_submission.i18n_datepicker_format,
							beforeShow  : function (input, inst) {
                                setTimeout(function(){
                                    inst.dpDiv.outerWidth(jQuery(input).outerWidth());
                                },0);
                            }
						});
				}
					
				
				//initially hide address, pincode, location textbox.
				if (jQuery('#event_online').length > 0)
	          		{
				        //hide event venue name, address, location and pincode fields at the edit event when select online event 
           	 			if(jQuery('input[name=event_online]:checked').val()=='yes')
			 		    {			 		        
			 		               if (jQuery('.fieldset-event_venue_name').length > 0 && jQuery('input[name=event_venue_name]').length > 0) {
			 		            	  
			 		            	   if(jQuery('input[name=event_venue_name]').attr('required'))
			                            	jQuery('input[name=event_venue_name]').attr('required', false);
			 		            	   
			                            jQuery('.fieldset-event_venue_name').hide();
			                        }
					 	           if (jQuery('.fieldset-event_address').length > 0 && jQuery('input[name=event_address]').length > 0) {
			                            
					 	        	  if(jQuery('input[name=event_address]').attr('required'))
			                            	jQuery('input[name=event_address]').attr('required', false);
					 	        	  
			                            jQuery('.fieldset-event_address').hide();
			                        }

			                        if (jQuery('.fieldset-event_pincode').length > 0 && jQuery('input[name=event_pincode]').length > 0) {
			                        	
			                        	if(jQuery('input[name=event_pincode]').attr('required'))
			                            	jQuery('input[name=event_pincode]').attr('required', false);
			                        	
			                            jQuery('.fieldset-event_pincode').hide();
			                        }

			                        if (jQuery('.fieldset-event_location').length > 0 && jQuery('input[name=event_location]').length > 0) {
			                        	
			                        	if(jQuery('input[name=event_location]').attr('required'))
			                            	jQuery('input[name=event_location]').attr('required', false);
			                        	
			                            jQuery('.fieldset-event_location').hide();
			                        }
					 }
				}

				//initially hide ticket price textbox
				if (jQuery('#event_ticket_options').length > 0 && jQuery('#event_ticket_options:checked').val() == 'free')
	            {
					if(jQuery('input[name=event_ticket_price]').attr('required'))
	                    jQuery('input[name=event_ticket_price]').attr('required', false);
					
				    jQuery('.fieldset-event_ticket_price').hide();
				}
				jQuery('input[name=event_online]').on('change', EventSubmission.actions.onlineEvent);
				jQuery('input[name=event_ticket_options]').on('change', EventSubmission.actions.eventTicketOptions);
	},
	
	actions:
	{	    
	      
				/// <summary>
				/// Hide address,location and pincode filed when online event.     
				/// </summary>       
				/// <returns type="initialization settings" />   
				/// <since>1.0.0</since> 
	            onlineEvent: function(event) 
	            {
	                event.preventDefault(); 
	                Common.logInfo("EventDashboard.actions.onlineEvent...");
	                if (jQuery('#event_online').length > 0)
	                {
	                    if (jQuery(this).val() == "yes") 
	                    {
	                        if (jQuery('.fieldset-event_venue_name').length > 0 && jQuery('input[name=event_venue_name]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_venue_name]').attr('required'))
	                            	jQuery('input[name=event_venue_name]').attr('required', false);
	                        	
	                            jQuery('.fieldset-event_venue_name').hide();
	                        }
	                        if (jQuery('.fieldset-event_address').length > 0 && jQuery('input[name=event_address]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_address]').attr('required'))
	                            	jQuery('input[name=event_address]').attr('required', false);
	                        	
	                            jQuery('.fieldset-event_address').hide();
	                        }
	                        if (jQuery('.fieldset-event_pincode').length > 0 && jQuery('input[name=event_pincode]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_pincode]').attr('required'))
	                            	jQuery('input[name=event_pincode]').attr('required', false);
	                        	
	                            jQuery('.fieldset-event_pincode').hide();
	                        }
	                        if (jQuery('.fieldset-event_location').length > 0 && jQuery('input[name=event_location]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_location]').attr('required'))
	                            	jQuery('input[name=event_location]').attr('required', false);
	                        	
	                            jQuery('.fieldset-event_location').hide();
	                        }
	                    }
	                    else {
                            if (jQuery('.fieldset-event_venue_name').length > 0 && jQuery('input[name=event_venue_name]').length > 0) {
	                            
                            	if(jQuery('input[name=event_venue_name]').attr('required'))
	                            	jQuery('input[name=event_venue_name]').attr('required', true);
                            	
	                            jQuery('.fieldset-event_venue_name').show();
	                        }
	                        if (jQuery('.fieldset-event_address').length > 0 && jQuery('input[name=event_address]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_address]').attr('required'))
	                            	jQuery('input[name=event_address]').attr('required', true);
	                        	
	                            jQuery('.fieldset-event_address').show();
	                        }
	                        if (jQuery('.fieldset-event_pincode').length > 0 && jQuery('input[name=event_pincode]').length > 0) {
	                            
	                        	if(jQuery('input[name=event_pincode]').attr('required'))
	                            	jQuery('input[name=event_pincode]').attr('required', true);
	                        	
	                            jQuery('.fieldset-event_pincode').show();
	                        }
	                        if (jQuery('.fieldset-event_location').length > 0 && jQuery('input[name=event_location]').length > 0) {
	                        	
	                        	if(jQuery('input[name=event_location]').attr('required'))
	                            	jQuery('input[name=event_location]').attr('required', true);
	                        	
	                            jQuery('.fieldset-event_location').show();
	                        }
	                    }
	                }						  
	            },

	            /// <summary>
	            /// Show and Hide ticket price textbox. 
	            /// </summary>     
	            /// <returns type="initialization ticket price settings" />    
	            /// <since>1.0.0</since>     
	            eventTicketOptions: function (event)
	            {	                
	                 event.preventDefault();
	                 Common.logInfo("EventDashboard.actions.eventTicketOptions...");
	                if (jQuery('#event_ticket_options').length > 0)
	                {	                    
	                    if (jQuery(this).val() == "free") {
	                         if (jQuery('.fieldset-event_ticket_price').length > 0 && jQuery('input[name=event_ticket_price]').length > 0) 
	                        {
	                        	 if(jQuery('input[name=event_ticket_price]').attr('required'))
		                            	jQuery('input[name=event_ticket_price]').attr('required', false);
	                        	 
	                            jQuery('.fieldset-event_ticket_price').hide();
	                        }
	                        
	                    } else {

	                         if (jQuery('.fieldset-event_ticket_price').length > 0 && jQuery('input[name=event_ticket_price]').length > 0) 
	                        	 
	                        	 if(jQuery('input[name=event_ticket_price]').attr('required'))
		                            	jQuery('input[name=event_ticket_price]').attr('required', true);
	                         
	                            jQuery('.fieldset-event_ticket_price').show();
	                        }
	                    }
	             },
	             
	       } //end of action
	           
	     			 
    } //enf of return	
}; //end of class
EventSubmission= EventSubmission();
jQuery(document).ready(function($) 
{
   EventSubmission.init();
});