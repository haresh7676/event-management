// Browe Event Filter toggle
jQuery(document).ready(function($) {
  $(".browe-event-filter-btn").click(function(){
    $(".browe-event-filter-content").toggleClass("open");
  });

  $(document).on("click", function (e) {
      if (!$(e.target).closest(".browe-event-filter-btn").length){
          $(".browe-event-filter-content").removeClass("open");
      }
  });

  function setHeight() {
      windowHeight = $(window).innerHeight();
      headerHeight = $('.top-header').outerHeight(true);
      titleHeight = $('.tabbing-page-title').innerHeight();
      mainHeight = windowHeight - headerHeight - titleHeight;
      $('.custom-tab-design').css('min-height', mainHeight);
  };
  setHeight();
  $('body').resize(function() {
      setHeight();
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

});
