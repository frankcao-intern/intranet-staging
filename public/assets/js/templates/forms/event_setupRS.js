/**
 * User: cravelo
 * Date: 10/22/13 9:08 AM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function ($) {
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitevent_setupRS").button();
			$("#resetevent_setupRS").button();
		}
	};

	Engine.submit();

	$(function(){
		$("#requestDate").datepicker();
	});

	$(function(){
		$("#eventDate").datepicker();
	});

});
