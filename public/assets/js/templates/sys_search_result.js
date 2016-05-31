/**
 * User: cravelo
 * Date: Nov 2, 2010
 * Time: 2:29:31 PM
 */

/*global require */
/*jslint browser: true, white: true */

require(['jquery', 'guidely'], function($, guidely){
	'use strict';

	var Engine = {
		focusSearch: function(){
			$("#query").focus();
		},
		guidely_setup: function(){
			if ($('.search-results > .section-a').length > 1){
				guidely.add ({
					attachTo: '.search-results .section-a:first',
					title: "Who's Who results",
					text: 'Who’s Who search results will always appear at the top of your query list, if applicable.',
					anchor: 'middle-middle'
				});

				guidely.add ({
					attachTo: '.search-results .section-a:eq(1)',
					title: "We've improved the search results",
					text: 'Now when you search on fishNET, your results will show in an easy to read list.',
					anchor: 'top-middle'
				});
			}else{
				guidely.add ({
					attachTo: '.search-results .section-a',
					title: "We've improved the search results",
					text: 'Now when you search on fishNET, your results will show in an easy to read list.',
					anchor: 'top-middle'
				});
			}

			guidely.add ({
				attachTo: 'aside.secondary',
				title: "How to search",
				text: 'Under “How to Search fishNET,” You will find easy tips to help maximize your search.',
				anchor: 'middle-middle'
			});

			if ($.cookie('fishNET-tour-search-12.10') === 'taken'){
				guidely.init({showOnStart: false, welcome: false});
			}else{
				$.cookie('fishNET-tour-search-12.10', 'taken', {expires: 365});

				guidely.init ({
					welcome: false
				});
			}

			$('.guidely-popup button').button();
		}
	};

	$(document).ready(function(){
		Engine.focusSearch();
//		Engine.guidely_setup();
	});

});
