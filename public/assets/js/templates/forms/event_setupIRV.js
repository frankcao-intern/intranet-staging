/**
 * User: cravelo
 * Date: 10/22/13 9:07 AM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function ($) {
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitevent_setupIRV").button();
			$("#resetevent_setupIRV").button();
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
