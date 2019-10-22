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
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {                    
                    $('.tableloaderfavorites').show();
                },
                success: function(html) {
                    $('.tableloaderfavorites-data').html(html);
                    $('.tableloaderfavorites').hide();
                }
            });
            //fas fa-spinner fa-pulse
        
    });
    $(document).on("click", '.upcominglisting', function(e) {
            var url = $(this).data('ajax');
            var loadtime = {action : 'get_upcoming_ajax'};            
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {
                    $('.tableloaderupcoming').show();
                },
                success: function(html) {
                    $('.tableloaderupcoming-data').html(html);
                    $('.tableloaderupcoming').hide();
                }
            });
            //fas fa-spinner fa-pulse
        
    });

    $(document).on("click", '.pasteventlisting', function(e) {
            var url = $(this).data('ajax');
            var loadtime = {action : 'get_pastevent_ajax'};            
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                beforeSend: function() {
                    $('.tableloaderupcoming').show();
                },
                success: function(html) {
                    $('.tableloaderpast-data').html(html);
                    $('.tableloaderpast').hide();
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
            beforeSend: function() {
                jQuery('.get_volunteer_data .tableloader').show();
            },
            success: function(html) {
                jQuery('.get_volunteer_data .tableloader').hide();
                swal({
                    html: html,                    
                    showCloseButton: true,
                    showConfirmButton: false            
                });
            }
        });
    });

    $(document).on("click", '.coupon-action-edit', function(e) {        
        var couponid = $(this).data('id');
        if($(this).prop("checked") == true){
            var status = 'publish';
        }
        else if($(this).prop("checked") == false){
            var status = 'draft';
        }        
        var url = $('.sub-tab-design').data('ajax');
        var loadtime = {action:'coupon_code_ajax_action',couponid:couponid, status:status};
        $.ajax({
            type: "POST",
            url: url,
            data: loadtime,
            cache: false,
            success: function(html) {
                
            }
        });
    });

    $(document).on("click", '.coupon-action-delete', function(e) {        
        var couponid = $(this).data('id');
        var status = 'trash';        
        var url = $('.sub-tab-design').data('ajax');
        var loadtime = {action:'coupon_code_ajax_action',couponid:couponid, status:status};
        swal({
            title: 'Confirm delete',
            text: 'Are you sure you want to delete all unused global discount codes? Codes already redeemed will not have their discounts removed.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then(
        function () { 
            $.ajax({
                type: "POST",
                url: url,
                data: loadtime,
                cache: false,
                success: function(html) {
                    showRecords(10, 1, 'get_discount_code_data');
                }
            });
        },
        function () { return false; });          
    });      

    $(document).on("click", '.reloaddiscount', function(e) {  
        e.preventDefault();
        setTimeout(function(){ 
            $("#AddcouponModal").modal("hide");                    
            showRecords(10, 1, 'get_discount_code_data');
        },2000);
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
    if ($('.get_discount_code_data').length > 0) {
        showRecords(10, 1, 'get_discount_code_data');        
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

    $('#contactmodule').on('hidden.bs.modal', function () {
        wpcf7.clearResponse( '#contactmodule form.wpcf7-form' );        
    });
    $('#exampleModal').on('hidden.bs.modal', function () {
        wpcf7.clearResponse( '#exampleModal form.wpcf7-form' );        
    });

    /*$('#event_start_time').on('changeTime', function() {
        $('#event_end_time').timepicker('option',{'roundingFunction':false, 'minTime': $(this).val()});
    });*/
    
});
function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  hours = hours < 10 ? '0'+hours : hours;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return strTime;
}

function formatWebsiteDate(date) {
 return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
}

function showRecords(perPageCount, pageNumber, action) {
    var eventid = '';
    if (jQuery('.'+action+' .event-dropdown').length > 0) {
        //var eventid = $('.'+action+' .event-dropdown').val();
        var eventid = jQuery('.'+action+' .event-dropdown').find("option:selected").val();
        //console.log(eventid);
    }
    var loadtime = {action : action, pageNumber:pageNumber,perPageCount:perPageCount,eventid:eventid};
    //var loaderimage = jQuery('.'+action+' .tableloader').data('loader');
    jQuery.ajax({
        type: "POST",
        url: jQuery('.sub-tab-design').data('ajax'),
        data: loadtime,
        cache: false,
        beforeSend: function() {
            //jQuery('.'+action+' .tableloader').html('<img src="'+loaderimage+'" alt="reload" width="20" height="20" style="margin-top:10px;">');
            jQuery('.'+action+' .tableloader').show();
        },
        success: function(html) {
            jQuery('.'+action+' .tabledataajax').html(html);
            //jQuery('.'+action+' .tableloader').html('');
            jQuery('.'+action+' .tableloader').hide();
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