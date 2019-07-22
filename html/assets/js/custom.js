// Browe Event Filter toggle
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


// Tab page get height
$(document).ready(function() {
  function setHeight() {
    windowHeight = $(window).innerHeight();
    headerHeight = $('.top-header').outerHeight(true);
    titleHeight = $('.tabbing-page-title').innerHeight();
    mainHeight = windowHeight - headerHeight - titleHeight;
    $('.custom-tab-design').css('min-height', mainHeight);
  };
  setHeight();
  $(body).resize(function() {
    setHeight();
  });
});

// Tab toggle
$(document).ready(function() {
  $(".custom-tab-toggle").click(function(){
    $(".custom-tab-toggle").toggleClass("open");
    $(".sidebar-tabs").toggleClass("open");
  });
});

$(document).on("click", function (e) {
  if (!$(e.target).closest(".custom-tab-toggle").length){
    $(".custom-tab-toggle, .sidebar-tabs").removeClass("open");
  }
});

$(document).ready(function() {
  $(".create-ticket-status .setting").click(function(){
    $(".setting-description").toggleClass("open");
  });
});