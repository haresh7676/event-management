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