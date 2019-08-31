var EventDashboard= function () {
    /// <summary>Constructor function of the event EventDashboard class.</summary>
    /// <returns type="Home" />      
    return {
	    ///<summary>
        ///Initializes the event dashboard.  
        ///</summary>     
        ///<returns type="initialization settings" />   
        /// <since>1.0.0</since> 
        init: function() 
        {
	  	    Common.logInfo("EventDashboard.init...");  
	  	    if(jQuery('.event-dashboard-action-delete').length >0)
		    {
				 var options={
						    "title"     : event_manager_event_dashboard.i18n_confirm_delete,
						    "btnOkLabel": event_manager_event_dashboard.i18n_btnOkLabel,
						    "btnCancelLabel" : event_manager_event_dashboard.i18n_btnCancelLabel,
						    "btnOkClass" : 'btn-danger',
						    "btnCancelClass" :  "btn btn-default"
			     };
				jQuery('.event-dashboard-action-delete').confirmation(options);
				jQuery('.event-dashboard-action-delete').css({'cursor':'pointer'});  					
				//for delete event confirmation dialog / tooltip 
				jQuery('.event-dashboard-action-delete').on('click', EventDashboard.confirmation.showDialog);	
				jQuery('.event-dashboard-action-delete').on('confirmed.bs.confirmation', EventDashboard.confirmation.confirmedDeleteEventDeleteConfirmation);
				jQuery('.event-dashboard-action-delete').on('canceled.bs.confirmation', EventDashboard.confirmation.cancelledDeleteEventDeleteConfirmation);
	        }	 	  	  
 	 }, 

	confirmation:{	    
             /// <summary>
	        /// Show bootstrap third party confirmation dialog when click on 'Delete' options on event dashboard page where show delete event option.	     
	        /// </summary>
	        /// <param name="parent" type="assign"></param>           
	        /// <returns type="actions" />     
	        /// <since>1.0.0</since>       
	        showDialog: function(event) 
	        {
	        	consol.log('dsadas');
	        	Common.logInfo("EventDashboard.confirmation.showDialog...");	            
			    jQuery('.event-dashboard-action-delete').confirmation('show');	           	
	           	event.preventDefault(); 
	        },
	        /// <summary>
	        /// Finally delete event alert and return true so further action will do for deleting event alert from database.	     
	        /// </summary>
	        /// <param name="parent" type="assign"></param>           
	        /// <returns type="bool" />     
	        /// <since>1.0.0</since>  
	        
	        confirmedDeleteEventDeleteConfirmation: function(event) 
	        {	        
	        	Common.logInfo("EventDashboard.confirmation.confirmedDeleteEventDeleteConfirmation...");		 
    			jQuery('.event-dashboard-action-delete').confirmation('hide');
    			jQuery('.event-dashboard-action-delete').css({'cursor':'pointer'});
    			event.preventDefault(); 
    		    return true;
	        },
	        
	        /// <summary>
	        /// Cancel delete event alert and return false so further actoin will stop.	     
	        /// </summary>
	        /// <param name="parent" type="assign"></param>           
	        /// <returns type="bool" />     
	        /// <since>1.0.0</since>       

	        cancelledDeleteEventDeleteConfirmation: function(event) 
	        {	        
	        	Common.logInfo("EventDashboard.confirmation.cancelledDeleteEventDeleteConfirmation...");	
    			jQuery('.event-dashboard-action-delete').confirmation('hide');
    			jQuery('.event-dashboard-action-delete').css({'cursor':'pointer'});
    			event.preventDefault();
		        return false;
	        }
	    }			 
    } //enf of return	
}; //end of class

EventDashboard= EventDashboard();
jQuery(document).ready(function($) 
{
   EventDashboard.init();
});