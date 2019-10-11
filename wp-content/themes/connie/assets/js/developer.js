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
    $(document).on("click", '.favoritelisting', function(e) {
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
            //fas fa-spinner fa-pulse
        
    });
    $(document).on("click", '.upcominglisting', function(e) {
            var url = $(this).data('ajax');
            var loadtime = {action : 'get_upcoming_ajax'};
            var loaderimage = $('.tableloaderupcoming').data('loader');
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {
                    $('.tableloaderupcoming').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
                },
                success: function(html) {
                    $('.tableloaderupcoming-data').html(html);
                    $('.tableloaderupcoming').html('');
                }
            });
            //fas fa-spinner fa-pulse
        
    });

    $(document).on("click", '.pasteventlisting', function(e) {
            var url = $(this).data('ajax');
            var loadtime = {action : 'get_pastevent_ajax'};
            var loaderimage = $('.tableloaderpast').data('loader');
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {
                    $('.tableloaderupcoming').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
                },
                success: function(html) {
                    $('.tableloaderpast-data').html(html);
                    $('.tableloaderpast').html('');
                }
            });
            //fas fa-spinner fa-pulse
        
    });

    $(document).on("click", '.addermovefav', function() {
        var $this = $(this);
        var url = $(this).data('ajax');
        var postid = $(this).data('postid');
        var userid = $(this).data('userid');
        var dtaaction = $this.data('action');        
        var loadtime = {action:'addremove_favoritelisting_ajax',postid:postid,userid:userid,dtaaction:dtaaction};  
        if(userid != 0){          
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $this.children('i').removeClass('far fas fa-heart').addClass('fas fa-spinner fa-pulse');
                },
                success: function(html) {                    
                    if(html.action == 'added'){
                        $this.children('i').removeClass('fas fa-spinner fa-pulse').addClass('fas fa-heart');                                                
                        $this.data("action", "remove");
                        swal({
                            text: html.msg,                            
                            type: 'success',                            
                            showConfirmButton: false,
                            timer: 2000
                        });
                        //alert(html.msg);                        
                    }else{
                        $this.children('i').removeClass('fas fa-spinner fa-pulse').addClass('far fa-heart');                        
                        //$this.attr("data-action", "add");
                        $this.data("action", "add");
                        //alert(html.msg);
                        swal({
                            text: html.msg,                            
                            type: 'error',                            
                            showConfirmButton: false,
                            timer: 2000
                        });
                        if(html.action == 'removed'){
                            if($this.parent().hasClass('myfavlist')){
                                $this.parent().parent().parent('.my-ticket-card-row').remove();
                            }
                        }
                    }
                    //$('.tableloaderfavorites-data').html(html);
                    //$('.tableloaderfavorites').html('');
                }
            });        
        }else{
            swal({              
              text: 'Please login to access your favourite events',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Sign In',
              cancelButtonText: 'No, Cancel'
            }).then(
                function () { 
                    var loginlinl = $('.loginlinktop').attr('href');
                    window.location.href = loginlinl;
                }, 
                function () { return false; });                        
        }
    });

    $(document).on("click", '.currenttimelink', function(e) {
        e.preventDefault();
        var tid = $(this).data('id');
        $('#'+tid).timepicker('setTime', new Date());
        $('#'+tid).trigger('change');
    });

    $(document).on("click", '.moreinfoticket', function(e) {
        e.preventDefault();
        var tickettitle = $(this).data('title');
        var tickecontent = $(this).data('content');
        swal({
            title: tickettitle,
            text: tickecontent,
            showCloseButton: true,
            showConfirmButton: false            
        });
    }); 

    $(document).on("click", '.getvolunteerdata', function(e) {
        e.preventDefault();
        var formdata = $(this).data('formdata');        
        var url = $('.sub-tab-design').data('ajax');
        var loadtime = {action : 'get_volunteer_dataajax', formdata : formdata};
        $.ajax({
            type: "POST",
            url: url,
            data: loadtime,
            cache: false,
            success: function(html) {
                swal({
                    html: html,                    
                    showCloseButton: true,
                    showConfirmButton: false            
                });
            }
        });       
        
    });   


    if ($('.get_volunteer_data').length > 0) {
        showRecords(10, 1, 'get_volunteer_data');
        $('.get_volunteer_data .event-dropdown').on("change", function(e) {
            showRecords(10, 1, 'get_volunteer_data');
        });
    }
    if ($('.get_team_member_data').length > 0) {
        showRecords(10, 1, 'get_team_member_data');
    }
    if ($('.get_report_problem_contact_data').length > 0) {
        showRecords(10, 1, 'get_report_problem_contact_data');
        $('.get_report_problem_contact_data .event-dropdown').on("change", function(e) {
            showRecords(10, 1, 'get_report_problem_contact_data');
        });
    }

    if ($('.get_attendees_data').length > 0) {
        showRecords(10, 1, 'get_attendees_data');
        $('.get_attendees_data .event-dropdown').on("change", function(e) {
            showRecords(10, 1, 'get_attendees_data');
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
    function showRecords(perPageCount, pageNumber, action) {
        var eventid = '';
        if ($('.'+action+' .event-dropdown').length > 0) {
            //var eventid = $('.'+action+' .event-dropdown').val();
            var eventid = $('.'+action+' .event-dropdown').find("option:selected").val();
            //console.log(eventid);
        }
        var loadtime = {action : action, pageNumber:pageNumber,perPageCount:perPageCount,eventid:eventid};
        var loaderimage = $('.'+action+' .tableloader').data('loader');
        $.ajax({
            type: "POST",
            url: $('.sub-tab-design').data('ajax'),
            data: loadtime,
            cache: false,
            beforeSend: function() {
                $('.'+action+' .tableloader').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
            },
            success: function(html) {
                $('.'+action+' .tabledataajax').html(html);
                $('.'+action+' .tableloader').html('');
            }
        });
    }
});

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