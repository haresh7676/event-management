// Browe Event Filter toggle
jQuery(document).ready(function($) {
    var removeClass = true;
    $(".browe-event-filter-btn").click(function () {
        $(".browe-event-filter-content").toggleClass('open');
        removeClass = false;
    });
    $(".browe-event-filter-content").click(function() {
        removeClass = false;
    });
    $("html").click(function () {
        if (removeClass) {
            $(".browe-event-filter-content").removeClass('open');
        }
        removeClass = true;
    });

    function setHeight() {
      windowHeight = $(window).innerHeight();
      headerHeight = $('.top-header').outerHeight(true);
      titleHeight = $('.tabbing-page-title').innerHeight();
      mainHeight = windowHeight - headerHeight - titleHeight - 15;
      $('.custom-tab-design').css('min-height', mainHeight);
    }
    setHeight();
  $('body').resize(function() {
      setHeight();
  });

    $('body').on('click','.ui-timepicker-input', function() {
        var outerwidth = $(this).outerWidth();
        console.log(outerwidth);
        $('.ui-timepicker-wrapper').css('width',outerwidth);
    });

  $(".custom-tab-toggle").click(function(){
      $(".custom-tab-toggle").toggleClass("open");
      $(".sidebar-tabs").toggleClass("open");
  });

  $(document).on("click", function (e) {
      if (!$(e.target).closest(".custom-tab-toggle").length){
          $(".custom-tab-toggle, .sidebar-tabs").removeClass("open");
      }
  });

    $(".create-ticket-status .setting").click(function(){
        $(".setting-description").toggleClass("open");
    });

});