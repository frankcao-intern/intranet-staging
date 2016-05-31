/**
 * User: cravelo
 * Date: 10/18/13 3:55 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function($){
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitfacility_workorder").button();
			$("#resetfacility_workorder").button();
		}
	};
	Engine.submit();

	$(function(){
		$("#submitalDate").datepicker();
	});

	$(function(){
		$("#completionDate").datepicker();
	});
});
