/**
 * User: cravelo
 * Date: 9/23/13 1:27 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function($){
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitDesk").button();
			$("#resetDesk").button();
		}
	};
	Engine.submit();

	$(function(){
		$("#datepicker").datepicker();

	});

	$(function(){
		$("#datepicker2").datepicker();
	});

});
