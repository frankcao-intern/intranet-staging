/**
 * Created by: cravelo
 * Date: Jan 22, 2011
 * Time: 7:35:51 PM
 */

/*jslint browser: true, white: true */
/*global require */

require(['jquery', 'shared/team_pages', 'templates/user/vimeo'], function($, teamPages){
	'use strict';

	$(document).ready(function(){
		teamPages.calendar();
		teamPages.tabs();
		teamPages.hashes();
	});
});
