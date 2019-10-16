// Call this from the developer console and you can control both instances
var calendars = {};

jQuery(document).ready( function($) {
    if ($('.cal1').length > 0) {
        var startdate = $('.cal1').data('start');
        var enddate = $('.cal1').data('end');
        var thisMonth = moment().format('YYYY-MM');
        thisMonth = '2019-11';
        var eventArray = [
            {
                title: 'Multi-Day Event',
                endDate: enddate,
                startDate: startdate
            }
        ];
        calendars.clndr1 = $('.cal1').clndr({
            events: eventArray,
            clickEvents: {
                click: function (target) {
                },
                today: function () {
                },
                nextMonth: function () {
                },
                previousMonth: function () {
                },
                onMonthChange: function () {
                },
                nextYear: function () {
                },
                previousYear: function () {
                },
                onYearChange: function () {
                },
                nextInterval: function () {
                },
                previousInterval: function () {
                },
                onIntervalChange: function () {
                }
            },
            multiDayEvents: {
                singleDay: 'date',
                endDate: 'endDate',
                startDate: 'startDate'
            },
            startWithMonth: startdate,
            showAdjacentMonths: true,
            adjacentDaysChangeMonth: false
        });
        // Bind all clndrs to the left and right arrow keys
        $(document).keydown(function (e) {
            // Left arrow
            if (e.keyCode == 37) {
                calendars.clndr1.back();
            }

            // Right arrow
            if (e.keyCode == 39) {
                calendars.clndr1.forward();
            }
        });
    }
});