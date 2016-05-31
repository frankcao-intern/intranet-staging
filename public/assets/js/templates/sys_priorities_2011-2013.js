/**
 * User: cravelo
 * Date: 6/20/12 5:22 PM
 */

/*jslint browser: true, white: true */
/*global require */

require(['jquery'], function ($) {
	"use strict";

	$(document).ready(function(){
		$('a.priority-link').fancybox({
			type: 'inline',
			openEffect: 'elastic',
			closeEffect: 'elastic',
			nextEffect: 'fade',
			prevEffect: 'fade',
			helpers	: {
				title	: {
					type: 'inside'
				},
				overlay	: {
					opacity : 0.8,
					css : {
						'background-color' : '#000'
					}
				}
			}
		});
	});
});
