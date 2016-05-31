/**
 * User: cravelo
 * Date: 10/22/13 9:08 AM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function($){
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitevent_setup111").button();
			$("#resetevent_setup111").button();
		}
	};

	Engine.submit();

	$(function(){
		$("#eventDate").datepicker();
	});

	$(function(){
		$("#requestDate").datepicker();
	});
});
