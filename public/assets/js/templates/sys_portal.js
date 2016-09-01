/**
 * @author Carlos Ravelo
 * @date 2010-01-20
 */

/*global require, coreEngine, guidely */
/*jslint browser: true, white: true */

require(['jquery', 'guidely', '../shared/hp_news_withpic', '../shared/minicalendar'], function($, guidely){
	'use strict';

	var Engine = {
		anniversariescroll: function(){
			$('#anivWrap').next('.more-b').find('a').click(function(){
				var api = $('#anivWrap').find('.scroll-pane').data('jsp');

				api.scrollByY(330, true);

				return false;
			});						
		},
		guidely_setup: function(){
			
			guidely.add ({
				attachTo: 'section.primary .columns.double-f.rotator-a',
				title: "We've simplified our Company News section",
				text: "Using familiar functionality, we’ve streamlined how you find news and posts.  " +
					"All stories are now featured prominently in the Company News section. "+
					"Want to see all published articles for the month? Click 'See all Company News' "+
					"to view the current stories and posts from months past.",
				anchor: 'top-left'
			});
			
			guidely.add ({
				attachTo: '#ourbrand',
				title: "New! We now feature Company Touch Points",
				text: "These <b>five boxes</b> give you a glimpse of important company information and/or initiatives! " +
					"This is an exciting new change because this is where you, as EF employees, can easily find "+
					"high-level information about our company.",
				anchor:'top-left'
			});

			guidely.add ({
				attachTo: 'aside.secondary .current',
				title: "Behind the Label Gets a New Home",
				text: 'Get a seasonal overview and detailed product education information in Behind the Label, now featured ' +
					'in the right column.',
				anchor: 'top-left'
			});
			
			guidely.add ({
				attachTo:'#efvideos',
				title: "EF Videos Gets Blown Out",
				text: "EF Videos is bigger, better and you can click “Play” to watch a video right from the home page.",
				anchor: 'bottom-left'
			});
			
			guidely.add ({
				attachTo: 'aside.secondary .section-a',
				title: "We've simplified EF Snapshots",
				text: "Gone are the days of uselessly searching in EF Snapshots. This section is now organized by " +
					"the month for easier searching. ",
				anchor: 'bottom-left'
			});
			
			guidely.add({
				attachTo: '#top-nav li.help',
				title: 'The Help Menu is now in the action menu',
				text: "We’ve moved the 'Help Menu' to the top of the page by your name. The red 'Help' button provides " +
					"fishNET video tutorials, links to fishNET resources and general information about the site.",
				anchor: 'bottom-middle'
			});


			//if ($.cookie('fishNET-tour-home-12.10') === 'taken'){
			//	guidely.init({showOnStart: false, welcome: false});
			//}else{
			//	$.cookie('fishNET-tour-home-12.10', 'taken', {expires: 365});

				guidely.init ({
					welcome: true,
					welcomeTitle: "We’ve redesigned fishNET's Home Page!",
					welcomeText: " Please take a quick tour?"});
			//}
			
			//$('.guidely-popup button').button();
		},
		displayPictureOfTheDay: function(result){
			$(document).ready(function(){
				var template, data = [], image = {}, fancy;

				if (result.isError){
					$.message(result.errorStr, 'error');
				}else{
					template = $('#efphotosTempl').html();
					fancy = false;

					image.src = result.data.src + '/w/200';
					image.alt = result.data.alt;
					if (result.data.page_id){
						image.href = coreEngine.siteRoot + 'article/' + result.data.page_id;
					}else{
						image.href = result.data.src;
						fancy = true;
					}
					image.title = '';
					image.title += result.data.date;
					image.title += (result.data.alt === '') ? '' : ' - ' + result.data.alt;
					data.push(image);

					$(coreEngine.attachTemplateToData(template, data)).hide().appendTo('body').find('img').load(function(){
						$(this).closest('.section-a').insertAfter('#efphotosTempl').show();

						if (fancy){
							$(this).parent().addClass('fancybox');
							coreEngine.fancybox();
						}
					});
				}
			});
		}
	};

	$(document).ready(function(){
		Engine.anniversariescroll();
		//Engine.guidely_setup();

		$('#hpCalendar').miniCalendar({cal_id: 3});
		
		coreEngine.ajax('article/getpicture', '', Engine.displayPictureOfTheDay);
	});

	//coreEngine.ajax('article/getpicture', '', Engine.displayPictureOfTheDay);
});
