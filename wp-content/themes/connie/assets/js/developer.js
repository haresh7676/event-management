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

    if ($('#submit-event-form').length > 0) {
        var delay = 0;
        var offset = 150;

        document.addEventListener('invalid', function(e){
           $(e.target).addClass("invalid");
           $('html, body').animate({scrollTop: $($(".invalid")[0]).offset().top - offset }, delay);
        }, true);
        document.addEventListener('change', function(e){
           $(e.target).removeClass("invalid")
        }, true);
    }
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