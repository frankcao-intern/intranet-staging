/**
 * User: cravelo
 * Date: Nov 3, 2010
 * Time: 5:16:13 PM
 * Linted: Aug 23, 2011
 */

/*global require, coreEngine */
/*jslint browser: true, white: true, plusplus: true, regexp: true */

require(['jquery', 'lib/jquery.quicksearch', 'lib/jquery.columnizer', 'lib/jquery.treeview'], function($){
	'use strict';

	var Engine = {
		quickSearch : function(){
			$('#quickSearch').quicksearch('ul#formsList li', {
				'onAfter': function () {
					$('ul.forms-list li').each(function(){
						var $matchingHeader;

						if ($(this).css('display') !== 'none'){
							$matchingHeader = $(this.parentNode.parentNode).prev();
							if (!$matchingHeader.hasClass('ui-state-active')){
								$matchingHeader.click();
								$('#quickSearch').focus();
							}

							return false; //get out of the each
						}

						return true;
					});
				}
			});
		},
		treeview : function(){
			$("ul#treeview").treeview({
				collapsed: true,
				animated: "fast"
			});
		},
		styleTree : function(){
			$("ul#treeview > li:first-child").addClass('first');
			$("ul#treeview > li").addClass('forms-departments');
			$("ul#treeview > li > ul li").addClass('forms-subfolders');
			$("ul#treeview li.forms-file").removeClass('forms-subfolders');
			//$("ul#formsList li").removeClass('forms-file').addClass('forms-subfolders');
			$("ul#treeview li > span").each(function(){
				if ($(this).text() === 'related'){
					$(this).addClass('forms-related');
				}
			});
		},
		columnize : function(){
			var $tmp = $('ul#formsList'),
				numColumns = 2;

			if ($tmp.columnize){//check if the function exists, in edit mode this should be false.
				$tmp.columnize({ columns: numColumns });
				//49 = 24 margin + 24 padding + 1 border
				$(".column").width(Math.floor($tmp.width() / numColumns - (49 - 49/numColumns)));
			}
		}/**,
		topDocsCallback: function(result){
			$(document).ready(function(){
				var $resultDiv = $('#topDownloaded'),
					docs = result[0].subtable,
					$ul = $("<ul>", {"class": "styled-bulletlist"}),
					docCounter = 1,
					i, docName, $li;

				for (i = 0; i < docs.length; i++){
					docName = /\/documents\/forms\/(.*)\..*$/.exec(docs[i].label);
					if (docName){
						$li = $("<li>").appendTo($ul);
						$("<a>", {"href": docs[i].url})
							.text(docName[1])
							.appendTo($li);

						if (docCounter === 10){ break; }

						docCounter++;
					}
				}

				$resultDiv.html($ul);
			});
		}*/
	};

	//send the request right away, wait for ready to process it
//	coreEngine.getJSON('proxy/piwikTopDocs', '', Engine.topDocsCallback);

	$(document).ready(function(){
		Engine.styleTree();
		Engine.treeview();
		Engine.columnize();
		Engine.quickSearch();
	});
});
