/**
 * User: cravelo
 * Date: Jun 7, 2010
 * Time: 3:35:40 PM
 */


/*global require, base64, coreEngine */
/*jslint white: true, browser:true */

require(['jquery', 'guidely', 'lib/jquery.columnizer'], function($, guidely){
	'use strict';

	var Engine = {
		whoUrl : "who/",
		setup: function(){
			$("#whoSearchQuery").autocomplete({
				minLength: 2,
				source: function(request, response) {
					var reqUrl = coreEngine.siteRoot + Engine.whoUrl + "search/limit/15/q/",
						myresponse = function(data){
							$(".search-field").children('img').remove();
							response(data);
						};

					$('<img>')
							.attr('src', coreEngine.siteRoot + 'assets/images/Jcrop.gif')
							.css('height', '8px')
							.css('position', 'absolute')
							.css('left', "434px")
							.css('top', "11px")
							.appendTo($(".search-field"));

					coreEngine.getJSON(reqUrl + base64.encode(request.term), "", myresponse);
				},
				select: function(event, ui){
					if (ui.item.id){
						$("#whoUserName").val(ui.item.id);
					}

					$("#whoSubmit").click();
				}
			})
			.keypress(function(e){
				if (e.keyCode === 13){
					$("#whoSubmit").click();
					return false;
				}

				$("#whoUserName").val("");

				return true;
			});

			$("#whoSubmit").click(function(){
				var reqUrl = coreEngine.siteRoot + Engine.whoUrl + "people/q/",
					str = "",
					userID = $("#whoUserName").val();

				if (userID === ""){
					str += $("#whoSearchQuery").val();
					if ($.trim(str) === ""){ return false; }
					document.location = reqUrl + base64.encode(str);
				}else{
					document.location = coreEngine.siteRoot + "profiles/" + userID;
				}

				return false;
			});
		},//setup
		departments : function(){
			var $depts = $('.js-who-departments'),
				numColumns;

			if ($depts.length > 0){
				numColumns = 3;
				$depts.columnize({ columns: numColumns, lastNeverTallest: true });
				$(".column").width(Math.floor($depts.width() / numColumns - (20 - 20/numColumns)));
			}
		},
		guidely_setup: function(){
			guidely.add ({
				attachTo: '#whoSearchQuery',
				title: "New Search Field!",
				text: 'Simply type your Who’s Who search entry and click “Go”!',
				anchor: 'bottom-middle'
			});

			guidely.add ({
				attachTo: 'aside.secondary .section-a:first',
				title: "Printable Phone List",
				text: 'Need to take a phone list with you? Simply click on a list link and an Excel version or PDF ' +
					'will download to your computer.',
				anchor: 'middle-middle'
			});

			guidely.add ({
				attachTo: 'aside.secondary .section-a:eq(1)',
				title: "Learn how to search on Who's Who",
				text: 'Look under “How to Search,” for easy tips to help maximize your Who’s Who search.',
				anchor: 'middle-middle'
			});

			guidely.add ({
				attachTo: 'ul.js-who-departments',
				title: "Departments",
				text: 'The departments list allows you to search for people by clicking on their team name. Your ' +
					'search results will show every person who works on that department.',
				anchor: 'top-middle'
			});

			if ($.cookie('fishNET-tour-whoswho-12.10') === 'taken'){
				guidely.init({showOnStart: false, welcome: false});
			}else{
				$.cookie('fishNET-tour-whoswho-12.10', 'taken', {expires: 365});

				guidely.init ({
					welcome: false
				});
			}

			$('.guidely-popup button').button();
		}
	};//engine

	$(document).ready(function(){
		Engine.setup();
		//if (coreEngine.pageID === 4){
		//	Engine.guidely_setup();
		//}
		Engine.departments();
	});
});
