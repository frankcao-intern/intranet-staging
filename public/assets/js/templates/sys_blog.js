/**
 * Created by: cravelo
 * Date: Aug, 2011
 * Time: 7:35:51 PM
 */

/*jslint browser: true, white: true, plusplus:true */
/*global require */

require(['jquery', 'shared/team_pages', 'shared/btl_rotator'], function($, teamPages){
	'use strict';

	var Engine = {
		images: function(){
			var $main_image = $('#main_image'),
				imgs_per_row = 4,
				total_rows = Math.ceil($main_image.find('img').length / imgs_per_row),
				i = 0, $img;

			for (i; i < total_rows; i++){
				$img = $('img', $main_image.find('.row' + i));

				/**
				 * 760 is the width of the main content area
				 */
				$img.width((760 - $img.length) / $img.length).height($img.width() * 471 / 760);
			}
		}
	};

	$(document).ready(function(){
		teamPages.calendar();
		teamPages.tabs();
		teamPages.hashes();
		Engine.images();
	});
});
