jQuery(document).ready(function($) {
    $(document).on('click','.view-more-video',function (e) {
        e.preventDefault();
        var $this = $(this);
        $('.morephotowpr').stop().slideToggle("fast",function(){
            if(!$(this).is(':visible')) {
                $(this).removeClass('active');
                $this.html('View More');
            }else{
                $this.html('View Less');
            }
        }).toggleClass('active');
        $(this).toggleClass('active');
    });

    if ($('.event-tickets-form').length > 0) {
        $('select.ticket_quantity_select').on('change', function() {
            $( "#order_now" ).trigger("click");
        });
    }
    if ($('.woocommerce-message').length > 0) {
        setTimeout(function() {
          $('.woocommerce-message').fadeOut('slow');
        }, 3000);        
    }
    $(document).on("click", '.returntodetails', function(e) {  
        var tabid = $(this).data('href');
        $('.nav-tabs li a[href='+tabid+']').trigger('click');               
        var divouter = $(tabid).outerHeight();        
        $('html, body').animate({
            scrollTop: $(tabid).position().top-divouter-100
        }, 2000); 
    });
    $('.event-dashboard-action-delete').on('click', function(){
        $('.event-dashboard-action-delete').confirmation('hide');
    });
     if ($('.fieldset-event_album').length > 0) {
        $(".event-manager-uploaded-files" ).sortable();
     }
    /*$(document).on("click", '.favoritelisting', function(e) {        

        
            var url = $(this).data('ajax');
            var loadtime = {action : 'get_favoritelisting_ajax'};
            var loaderimage = $('.tableloaderfavorites').data('loader');
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {
                    $('.tableloaderfavorites').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
                },
                success: function(html) {
                    $('.tableloaderfavorites-data').html(html);
                    $('.tableloaderfavorites').html('');
                }
            });
        
    });*/

    if ($('.get_volunteer_data').length > 0) {
        showRecords(3, 1, 'get_volunteer_data');
        $('.get_volunteer_data .event-dropdown').on("change", function(e) {
            showRecords(3, 1, 'get_volunteer_data');
        });
    }
    if ($('.get_team_member_data').length > 0) {
        showRecords(3, 1, 'get_team_member_data');
    }
    if ($('.get_report_problem_contact_data').length > 0) {
        showRecords(3, 1, 'get_report_problem_contact_data');
        $('.get_report_problem_contact_data .event-dropdown').on("change", function(e) {
            showRecords(3, 1, 'get_report_problem_contact_data');
        });
    }

    if ($('.get_attendees_data').length > 0) {
        showRecords(3, 1, 'get_attendees_data');
        $('.get_attendees_data .event-dropdown').on("change", function(e) {
            showRecords(3, 1, 'get_attendees_data');
        });
    }

    $("#paypalconnect").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = $('.account-settings').data('ajax');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
               $('.notes').html(data);
            }
        });


    });

    if ($('#submit-event-form').length > 0) {
        var delay = 0;
        var offset = 150;

        document.addEventListener('invalid', function(e){
           $(e.target).addClass("invalid");
           $('html, body, .modal').animate({scrollTop: $($(".invalid")[0]).offset().top - offset }, delay);           
        }, true);
        document.addEventListener('change', function(e){
           $(e.target).removeClass("invalid")
        }, true);
    }


    /*$('#event_start_time').on('changeTime', function() {
        $('#event_end_time').timepicker('option',{'roundingFunction':false, 'minTime': $(this).val()});
    });*/
});
function showRecords(perPageCount, pageNumber, action) {
    var eventid = '';
    if (jQuery('.'+action+' .event-dropdown').length > 0) {
        eventid = jQuery('.'+action+' .event-dropdown').val();
    }
    var loadtime = {action : action, pageNumber:pageNumber,perPageCount:perPageCount,eventid:eventid};
    var loaderimage = jQuery('.'+action+' .tableloader').data('loader');
    jQuery.ajax({
        type: "POST",
        url: jQuery('.sub-tab-design').data('ajax'),
        data: loadtime,
        cache: false,
        beforeSend: function() {
            jQuery('.'+action+' .tableloader').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
        },
        success: function(html) {
            jQuery('.'+action+' .tabledataajax').html(html);
            jQuery('.'+action+' .tableloader').html('');
        }
    });
}
( function ( $ ) {
    "use strict";
// Define the PHP function to call from here
    var data = {
        'action': 'mode_theme_update_mini_cart'
    };
    $.post(
        woocommerce_params.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function(response){
            $('#mode-mini-cart').html(response); // Repopulate the specific element with the new content
        }
    );
// Close anon function.
}( jQuery ) );