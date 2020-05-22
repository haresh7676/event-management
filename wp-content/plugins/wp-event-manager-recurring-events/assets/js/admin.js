
var AdminRecurring = function () {

    /// <summary>Constructor function of the AdminRecurring class.</summary>
    /// <since>1.0.0</since>
    /// <returns type="Logs" />  
    return {
  
    	///<summary>
        /// initialize recurring 
        ///</summary>     
        ///<returns type="" />   
        /// <since>1.0.0</since> 
    	init: function () {
			if(jQuery('#_event_recurrence').length > 0){
    			//call this function when page is loaded
    			AdminRecurring.actions.onChangeRecurrence();
    			jQuery('#_event_recurrence').on('change', AdminRecurring.actions.onChangeRecurrence );	
    		}
			
			if(jQuery('input[name=_recure_time_period]').length > 0 && jQuery('#_event_recurrence').val()=='monthly'){
				//call this function when page is loaded
				AdminRecurring.actions.onchangeTimePeriod();
				jQuery('input[name=_recure_time_period]').on('change', AdminRecurring.actions.onchangeTimePeriod );	
			}
			jQuery('.recurring-now-btn').on('click', AdminRecurring.actions.recurreEventNow);
        },
        actions:
	    {
			///<summary>
            /// on change recurrence 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	onChangeRecurrence : function(){

				var recurrence_type = jQuery('#_event_recurrence').val();
				//if recuurence type is no
        		if(recurrence_type == '' || recurrence_type == 'no'){
        			//hide fields 
        			AdminRecurring.actions.updateRecureEvery(true);
        			AdminRecurring.actions.updateEventUntill(true);
        			AdminRecurring.actions.updateRecureWeekday(true);
        			AdminRecurring.actions.updateRecureTimePeriod(true);
        			AdminRecurring.actions.updateRecureMonthDay(true);
        			
        		}
					
				//if recuurence type is daily
        		if( recurrence_type == 'daily'){
        			//hide fields 
        			AdminRecurring.actions.updateRecureEvery(false);
        			AdminRecurring.actions.updateEventUntill(false);
        			AdminRecurring.actions.updateRecureWeekday(true);
        			AdminRecurring.actions.updateRecureTimePeriod(true);
        			AdminRecurring.actions.updateRecureMonthDay(true);
					
					//add text after every repeat fields
					if (jQuery('input[name=_recure_every]').length > 0) {
					    jQuery('input[name=_recure_every]').prev('lable span').html(event_manager_recurring_events.every_day);
					}
        		}
				
				//if recuurence type is Weekly
        		if( recurrence_type == 'weekly'){
        			//hide fields 
        			AdminRecurring.actions.updateRecureEvery(false);
        			AdminRecurring.actions.updateEventUntill(false);
        			AdminRecurring.actions.updateRecureWeekday(false);
        			AdminRecurring.actions.updateRecureTimePeriod(true);
        			AdminRecurring.actions.updateRecureMonthDay(true);
					
					//add text after every repeat fields
					if (jQuery('input[name=_recure_every]').length > 0) {
					    jQuery('input[name=_recure_every]').prev('lable span').html(event_manager_recurring_events.every_week);
					}
        		}
				
				//if recuurence type is Monthly
        		if( recurrence_type == 'monthly'){
        			//hide fields 
        			AdminRecurring.actions.updateRecureEvery(false);
        			AdminRecurring.actions.updateEventUntill(false);
        			AdminRecurring.actions.updateRecureWeekday(false);
        			AdminRecurring.actions.updateRecureTimePeriod(false);
        			AdminRecurring.actions.updateRecureMonthDay(true);
					
					//update time period
					AdminRecurring.actions.onchangeTimePeriod();
					
					//add text after every repeat fields
					if (jQuery('input[name=_recure_every]').length > 0) {
					    jQuery('input[name=_recure_every]').prev('lable span').html(event_manager_recurring_events.every_month);
					}
        		}
				//if recuurence type is yearly
        		if( recurrence_type == 'yearly'){
        			//hide fields 
        			AdminRecurring.actions.updateRecureEvery(false);
        			AdminRecurring.actions.updateEventUntill(false);
        			AdminRecurring.actions.updateRecureWeekday(true);
        			AdminRecurring.actions.updateRecureTimePeriod(true);
        			AdminRecurring.actions.updateRecureMonthDay(true);
					
					//add text after every repeat fields
					if (jQuery('input[name=_recure_every]').length > 0) {
					    jQuery('input[name=_recure_every]').prev('lable span').html(event_manager_recurring_events.every_year);
					}
        		}
			},
			///<summary>
            /// on click recurrence event button
            ///</summary>     
			recurreEventNow : function(event){
				var tag_id =this.id;
				var event_id = jQuery("#"+tag_id).attr('data-eventid');
				var start_date = jQuery("#"+tag_id).attr('data-start-date');
				var end_date = jQuery("#"+tag_id).attr('data-end-date');
				var data = {
					'action'     : 'create_event_recurring',
					'event_id'   : event_id,
					'start_date' : start_date,
					'end_date' : end_date
				};
				jQuery.ajax({
					type: 'POST',
					url: event_manager_recurring_events.ajax_url,
					data: data,
				   beforeSend: function(jqXHR, settings) 
				   {
						jQuery("#"+tag_id).attr("disabled", "disabled");
						jQuery("#"+event_id+"_view").attr("disabled", "disabled");
						jQuery("#"+tag_id).click(false);
						jQuery("#"+event_id+"_view").click(false);
				   },
				   success: function(data)
				   {
					   //if reurn status is true then call ajax again untill status returns false
					   if( data.status==true ){
							jQuery("#"+tag_id).attr('data-start-date', data.start_date);
							jQuery("#"+tag_id).attr('data-end-date', data.end_date);
							jQuery("#"+tag_id).trigger( "click" );
					   }else{
						  	location.reload();
					   }
				   },
				   error: function(jqXHR, textStatus, errorThrown) 
				   { 		           
					console.log('error');
				   },
				   complete: function (jqXHR, textStatus) 
				   {	
					   console.log('complete');		
				   }
				   });
			},
			///<summary>
            /// updateRecureEvery 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	updateRecureEvery : function(hide = true){
				if( hide == true ){
        			if (jQuery('input[name=_recure_every]').length > 0) {
        				jQuery('input[name=_recure_every]').removeAttr('required', 'required');
        				//jQuery('input[name=_recure_every]').hide();
        				jQuery('input[name=_recure_every]').parent("p").hide();
        			}
        		}
        		else{
        			if (jQuery('input[name=_recure_every]').length > 0) {
        				jQuery('input[name=_recure_every]').attr('required', 'required');
        				//jQuery('input[name=_recure_every]').show();
        				jQuery('input[name=_recure_every]').parent("p").show();
        			}
        		}
			},
			
			///<summary>
            /// updateEventUntill 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	updateEventUntill : function(hide = true){
				if( hide == true ){
        			if (jQuery('input[name=_recure_untill]').length > 0) {
        				jQuery('input[name=_recure_untill]').removeAttr('required', 'required');
        				//jQuery('input[name=_recure_untill]').hide();
        				jQuery('input[name=_recure_untill]').parent("p").hide();
        			}
        		}
        		else{
        			if (jQuery('input[name=_recure_untill]').length > 0) {
        				jQuery('input[name=_recure_untill]').attr('required', 'required');
        				//jQuery('input[name=_recure_untill]').show();
        				jQuery('input[name=_recure_untill]').parent("p").show();
        			}
        		}
			},
			
			///<summary>
            /// updateRecureWeekday 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	updateRecureWeekday : function(hide = true){
				if( hide == true ){
        			if (jQuery('select[name=_recure_weekday]').length > 0) {
        				jQuery('select[name=_recure_weekday]').removeAttr('required', 'required');
        				//jQuery('input[name=_recure_weekday]').hide();
        				jQuery('select[name=_recure_weekday]').parent("p").hide();
        			}
        		}
        		else{
        			if (jQuery('select[name=_recure_weekday]').length > 0) {
        				jQuery('select[name=_recure_weekday]').attr('required', 'required');
        				//jQuery('input[name=_recure_weekday]').show();
        				jQuery('select[name=_recure_weekday]').parent("p").show();
        			}
        		}
			},
			
			///<summary>
            /// updateRecureTimePeriod 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	updateRecureTimePeriod : function( hide = true ){
				if( hide == true ){
        			if (jQuery('input[name=_recure_time_period]').length > 0) {
        				jQuery('input[name=_recure_time_period]').removeAttr('required', 'required');
        				//jQuery('input[name=_recure_time_period]').hide();
        				jQuery('input[name=_recure_time_period]').closest("p").hide();
        			}
        		}
        		else{
        			if (jQuery('input[name=_recure_time_period]').length > 0) {
        				jQuery('input[name=_recure_time_period]').attr('required', 'required');
        				//jQuery('input[name=_recure_time_period]').show();
        				jQuery('input[name=_recure_time_period]').closest("p").show();
        			}
					
        		}
			},
			
			///<summary>
            /// updateRecureMonthDay 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	updateRecureMonthDay : function(hide = true){
				if( hide == true ){
        			if (jQuery('select[name=_recure_month_day]').length > 0) {
        				jQuery('select[name=_recure_month_day]').removeAttr('required', 'required');
        				//jQuery('input[name=_recure_month_day]').hide();
        				jQuery('select[name=_recure_month_day]').parent("p.form-field").hide();
        				
        			}
        		}
        		else{
        			if (jQuery('select[name=_recure_month_day]').length > 0) {
        				jQuery('select[name=_recure_month_day]').attr('required', 'required');
        				//jQuery('input[name=_recure_month_day]').show();
        				jQuery('select[name=_recure_month_day]').parent("p.form-field").show();
        			}
        		}
			},

         
			
			///<summary>
            /// onchangeTimePeriod 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	onchangeTimePeriod : function(hide = true){
        	    
				//check if time period is specific time
				if(jQuery('input[name=_recure_time_period]:checked').val() == 'specific_time'){
				
					AdminRecurring.actions.updateRecureMonthDay(false);
					AdminRecurring.actions.updateRecureWeekday(false);
				}
				else{
				    
					AdminRecurring.actions.updateRecureMonthDay(true);
					AdminRecurring.actions.updateRecureWeekday(true);
				}
			}
			
			
		} //end of action
    }
};
AdminRecurring = AdminRecurring();

jQuery(document).ready(function($) 
{
	AdminRecurring.init();
});