/**
 * User: cravelo
 * Date: 9/26/12
 * Time: 12:56 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global define, coreEngine, console */

/**
 * minicalendar jquery plugin.
 * TODO: Depends on coreEngine not sure how good that is... it depends on coreEngine's ajax
 * facilities, the global stuff can be passed in as an option. Will think about this.
 */
define(['jquery', 'lib/fullcalendar'], function($) {
	'use strict';

	$.fn.miniCalendar = function(opts){
		var $calendar = $(this),
			todayEvents;//expecting to be called on a single element

		if (opts.cal_id) {
			$calendar.empty().fullCalendar({
				'dayNamesShort':['S', 'M', 'T', 'W', 'T', 'F', 'S'],
				'header':{
					'left':'prev',
					'center':'title',
					right:'next'
				},
				titleFormat:{
					'month':'MMMM yyyy'
				},
				'events':function (start, end, callback) {
					var st = Math.round(start.getTime() / 1000),
						fin = Math.round(end.getTime() / 1000);

					coreEngine.getJSON('calendar/fullcal_json/' + opts.cal_id + '/' + st + '/' + fin, '', function (eventData) {

						callback(eventData.events);
					});
				},
				eventRender:function (event) {
					var date = event.start,
						today = (new Date()),
						day = date.getDate(),
						month = (date.getMonth() + 1),
						event_date =  month + '/' + day,
						$cell = $calendar.find('div.fc-view tbody td:not(.fc-other-month):eq(' + (day - 1) + ') div.fc-day-number'),
						event_templ = '<a href="{1}">{2} - {3}<\/a><br \/>'.format(event.url, event_date, event.title);

					if ($cell.find('a').size() > 0) {
						$cell.find('a span.tooltip').append(event_templ);
					} else {
						$cell.wrapInner('<a href="#"><\/a>'.format());
						$cell.find('a').append('<span class="tooltip">'+ event_templ +'<\/span>');
					}

					//if we are processing today's events, add them to the list below the calendar
					if (month === (today.getMonth() + 1) && (day === today.getDate())){
						$("aside.secondary dl.event dd").append(event_templ);
					}

					$cell.qtip($.extend({}, coreEngine.qtipDefaults, {
						content:{ text: $cell.find('a span.tooltip') }
					}));

					return false;
				},
				dayClick:function (date) {
					var url = coreEngine.siteRoot + 'calendar/{1}/#[{1}]/agendaDay/{2}-{3}-{4}';

					document.location = url.format(opts.cal_id, date.getFullYear(), date.getMonth() + 1, date.getDate());
				}
			});

			//make the days act like links
			$('.fc-content table td', $calendar).css('cursor', 'pointer').hover(function () {
				$(this).css({
					'background':'#9A0000',
					'color':'#FFF'
				});
			}, function () {
				$(this).css({
					'background':'none',
					'color':'inherit'
				});
			});

			//make the month title clickable
			$('.fc-header .fc-header-title h2', $calendar)
				.css('cursor', 'pointer')
				.hover(function () {
					$(this).css({
						'color':'#9A0000'
					});
				}, function () {
					$(this).css({
						'color':'inherit'
					});
				})
				.click(function () {
					var url = coreEngine.siteRoot + 'calendar/{1}/#[{1}]/month/{2}-{3}-1',
						date = $calendar.fullCalendar('getDate');

					document.location = url.format(opts.cal_id, date.getFullYear(), date.getMonth() + 1);
				});
		} else {
			if (console.log) {
				console.log('I need a calendar ID! {cal_id: Number}');
			}
		}

		return $calendar;
	};
});
