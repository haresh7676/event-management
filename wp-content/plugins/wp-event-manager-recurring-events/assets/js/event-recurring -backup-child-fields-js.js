var Recurring = function () {

    /// <summary>Constructor function of the Recurring class.</summary>
    /// <since>1.0.0</since>
    /// <returns type="Logs" />  
    return {
  
    	///<summary>
        /// initialize recurring 
        ///</summary>     
        ///<returns type="" />   
        /// <since>1.0.0</since> 
    	init: function () {
    	    
            /// <summary>Initializes the Recurring.</summary>
            /// <since>1.0.0</since>
    		if(jQuery('.fieldset-event_recurrence').length > 0){
    			//call this function when page is loaded
    			Recurring.actions.onChangeRecurrence();
    			jQuery('#event_recurrence').on('change', Recurring.actions.onChangeRecurrence );	
    		}
    		
    	
    		if (jQuery('.fieldset-on_time_period').length > 0) {
				jQuery('input[name=on_time_period]').on('change', Recurring.actions.onChangeTimePeriod)
    		}
    		//date picker in until date
    		if (jQuery('.fieldset-recure_until').length > 0) {
    			jQuery('#recure_until').datepicker({autoclose:true, startDate: 'Beginning of time' });
    		}
        },
        actions:
	    { 
        	///<summary>
            /// on change recurrece 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	onChangeRecurrence : function(){
        		var recurrence_type = jQuery('#event_recurrence').val();
        		//if recuurence type is no
        		if(recurrence_type == '' || recurrence_type == 'no'){
        			//hide recure every fieldset
        			Recurring.actions.hideFieldsetRecureEvery(true);
        			Recurring.actions.hideEventUntill(true);
        		}
        		
        		//if recuurence type is daily
        		if( recurrence_type == 'daily'){
        			Recurring.actions.hideFieldsetRecureEvery(true);
        			Recurring.actions.hideEventUntill(false);
        		}
        		
        		//if recuurence type is weekly
        		if( recurrence_type == 'weekly'){
        			Recurring.actions.hideFieldsetRecureEvery(false);
        			Recurring.actions.hideEventUntill(false);
        			Recurring.actions.hideOnTimePeriod(false);
        			
        			if(jQuery('input[name=on_time_period]:checked').val() == 'specific_time'){
        			    
        				Recurring.actions.hideWeekMonth(true);
        				Recurring.actions.hideWeekDays(false);
        			}
        			else{
        				Recurring.actions.hideWeekMonth(true);
        				Recurring.actions.hideWeekDays(true);
        			}
        			
        			
        		}
        		
        		//if recuurence type is monthly
        		if( recurrence_type == 'monthly'){
        			Recurring.actions.hideFieldsetRecureEvery(false);
        			Recurring.actions.hideEventUntill(false);
        			Recurring.actions.hideOnTimePeriod(false);
        			if(jQuery('input[name=on_time_period]:checked').val() == 'specific_time'){
        				Recurring.actions.hideWeekMonth(false);
        				Recurring.actions.hideWeekDays(false);
        			}
        			else{
        				Recurring.actions.hideWeekMonth(true);
        				Recurring.actions.hideWeekDays(true);
        			}	
        		}
        		
        		//if recuurence type is yearly
        		if( recurrence_type == 'yearly'){
        			Recurring.actions.hideFieldsetRecureEvery(false);
        			Recurring.actions.hideEventUntill(false);
        			Recurring.actions.hideWeekMonth(true);
    				Recurring.actions.hideWeekDays(true);
    				Recurring.actions.hideOnTimePeriod(true);	
        		}
        	},
        	
        	///<summary>
            /// on change time period 
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	onChangeTimePeriod: function(){
        	    
        		if(jQuery('#event_recurrence').val() == 'weekly' ){
        		    
        			if(jQuery('input[name=on_time_period]:checked').val()== 'specific_time'){
        			    
        				Recurring.actions.hideWeekMonth(true);
                		Recurring.actions.hideWeekDays(false);
        			}
        			else{
        				Recurring.actions.hideWeekMonth(true);
                		Recurring.actions.hideWeekDays(true);
        			}
        			
        		}
        		if(jQuery('#event_recurrence').val() == 'monthly'){
        			if(jQuery('input[name=on_time_period]:checked').val()== 'specific_time'){
        				Recurring.actions.hideWeekMonth(false);
                		Recurring.actions.hideWeekDays(false);
        			}
        			else{
        				Recurring.actions.hideWeekMonth(true);
                		Recurring.actions.hideWeekDays(true);
        			}
        		}
        	},
        	
        	///<summary>
            /// hide event untill
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	hideEventUntill: function(hide){
        		if(hide == true){
	        		//recure untill
	    			if (jQuery('.fieldset-recure_until').length > 0) {
	    				jQuery('input[name=recure_until]').removeAttr('required', 'required');
	    				jQuery('.fieldset-recure_until').hide();
	    			}
        		}
        		else{
        			if (jQuery('.fieldset-recure_until').length > 0) {
	    				jQuery('input[name=recure_until]').attr('required', 'required');
	    				jQuery('.fieldset-recure_until').show();
	    			}
        		}
        	},
        	
        	///<summary>
            /// hide whole fields sest of recurrence
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	hideFieldsetRecureEvery: function(hide){
        		if( hide == true ){
        			if (jQuery('.fieldset-recure_every').length > 0) {
        				jQuery('input[name=recurrence_count]').removeAttr('required', 'required');
        				jQuery('.fieldset-recure_every').hide();
        			}
        		}
        		else{
        			if (jQuery('.fieldset-recure_every').length > 0) {
        				jQuery('input[name=recurrence_count]').attr('required', 'required');
        				jQuery('.fieldset-recure_every').show();
        			}
        		}
        	},
        	
        	///<summary>
            /// hide week of the month
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	hideWeekMonth : function(hide){
        		if(hide == true){
        			if (jQuery('.fieldset-week_month').length > 0) {
        			    jQuery('.fieldset-week_month').hide();
        				jQuery('select[name=week_month]').hide();
        			}
        		}
        		else{
        			if (jQuery('.fieldset-week_month').length > 0) {
        			    jQuery('.fieldset-week_month').show();
        				jQuery('select[name=week_month]').show();
        			}
        		}
        	},
        	
        	///<summary>
            /// hide days of the week ex sun,mon...
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	hideWeekDays : function(hide){
        		if(hide == true){
        			if (jQuery('.fieldset-week_days').length > 0) {
        			    jQuery('.fieldset-week_days').hide();
        				jQuery('select[name=week_days]').hide();
        			}
        		}
        		else{
        			if (jQuery('.fieldset-week_days').length > 0) {
        			    jQuery('.fieldset-week_days').show();
        				jQuery('select[name=week_days]').show();
        				
        				var txt = jQuery('select[name=event_recurrence] :selected').text();
        				jQuery('select[name=week_days]').next('.after-child-field').html('of '+txt);
        				
        			}
        		}
        	},
        	
        	///<summary>
            /// hide on time period
            ///</summary>     
            ///<returns type="" />   
            /// <since>1.0.0</since> 
        	hideOnTimePeriod: function(hide){
        		if(hide == true){
        			if (jQuery('.fieldset-on_time_period').length > 0) {
        				jQuery('input[name=on_time_period]').hide();
        				jQuery('.fieldset-on_time_period').hide();
        			}
        		}
        		else{
        			if (jQuery('.fieldset-on_time_period').length > 0) {
        				jQuery('input[name=on_time_period]').show();
        				jQuery('.fieldset-on_time_period').show();
        				
        				var txt = jQuery('select[name=event_recurrence] :selected').text();
        				jQuery('select[name=on_time_period]').next('.after-child-field').html('of '+txt);
        				
        			}
        		}
        	}
	    }
    }
};
Recurring = Recurring();

jQuery(document).ready(function($) 
{
	Recurring.init();
});