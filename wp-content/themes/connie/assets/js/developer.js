$(document).ready(function() {
    $('document').on('click','.view-more-video',function (e) {
        e.preventDefault();
        $('.morephotowpr').stop().slideToggle( "slow", function() {

        }).toggleClass('active');
    })
});