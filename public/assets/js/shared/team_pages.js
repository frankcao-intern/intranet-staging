/**
 * User: cravelo
 * Date: 6/19/12 2:33 PM
 */

/*jslint browser: true, white: true */
/*global define */

define(['jquery', 'shared/minicalendar', 'lib/jquery.ba-hashchange'], function($){
	'use strict';

	return {
		calendar: function(){
			var $miniCalendar = $("#miniCalendar"),
				cal_id = $miniCalendar.text();

			$miniCalendar.miniCalendar({cal_id: cal_id});
		},
		hashes: function(){
			$(window).hashchange(function(){
				if (window.location.hash){
					var hashstr = window.location.hash;
					$(".team-tabs").tabs("select", $(hashstr).index() - 1);
				}
			});
		},
		tabs: function(){
			$(".team-tabs").tabs({
				cache: true,
				spinner: '<img src="'+ window.coreEngine.siteRoot +
					'assets/images/loading_sm.gif" alt="Loading..." width="15" height="15" />',
				cookie: {
					expires: 1
				},
				select: function(e, ui){
					window.location.hash = $(ui.tab).attr('href');
				},
				selected: 0
			});
		}
	};
});
