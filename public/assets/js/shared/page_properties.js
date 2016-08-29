/**
 Modified by MLanni
 6/14/2016
 */


/**
 * Created by: cravelo
 * Date: 5/16/11
 * Time: 1:10 PM
 * Passed JSLint on Aug 15, 2011
 */

/*global define, coreEngine, qq, base64 */
/*jslint white: true, browser: true */

(function(w){
	"use strict";
	define(['jquery', 'lib/jquery.maskedinput-1.2.2'], function($){

		$( document ).ready(function() {
//alert(coreEngine.pageID.match(/\d+/)[0]);
			// create a PHP file for select statement
			// pass the file page and section ID's*/
			// wait for return of pubDate and expDate
			$.get( "getSections", function( data ) {
				alert( "Data Loaded: " + data );
			});


		});
		var page_properties = {
			setConfirmUnload: function(on){
				var unloadMessage = function(){
					return 'You have changed settings on this page. If you navigate away from this page without ' +
						'first saving, the changes will be lost.';
				};

				$(w).on('beforeunload', (on) ? unloadMessage : null);
			},
			split: function( val ){
				return val.split( /,\s*/ );
			},
			setup: function(){
				if ($.fn.mask && $.fn.datepicker){
					$.mask.definitions['1'] = '[01]';
					$.mask.definitions['3'] = '[0-3]';

					$("#expiration_date").datepicker({dateFormat: "yy-mm-dd"}).mask("9999-19-39");
					$("#date_published").datepicker({dateFormat: "yy-mm-dd"}).mask("9999-19-39");
					$("#show_until").datepicker({dateFormat: "yy-mm-dd"}).mask("9999-19-39");
					$("#featured_from").datepicker({dateFormat: "yy-mm-dd"}).mask("9999-19-39");
					$("#featured_until").datepicker({dateFormat: "yy-mm-dd"}).mask("9999-19-39");
				}

				if ($.fn.checkbox){
					$("input[type=checkbox].js-gen-settings").checkbox({
						empty: coreEngine.siteRoot + 'assets/images/empty.png'
					});
				}

				$('#sections').selectable({
					selected: function(){
						page_properties.setConfirmUnload(true);
					},
					unselected: function(){
						page_properties.setConfirmUnload(true);
					}
				});

				$('.select-c.templates').fancySelect();

				//if any input changes I want to know, so when the user navigates away I can alert them
				$('input, select').change(function(){
					page_properties.setConfirmUnload(true);
				});
			},
			tags: function(){
				var extractLast = function( term ) {
					return page_properties.split( term ).pop();
				};

				$("#tags")
				// don't navigate away from the field on tab when selecting an item
					.bind( "keydown", function( event ) {
						if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
							event.preventDefault();
						}
					})
					.autocomplete({
						minLength: 1,
						source: function( request, response ) {
							coreEngine.ajax("server/gettags/" + (new Date()).getTime(), "tag_name=" + extractLast(request.term), response, 'json');
						},
						focus: function() {
							// prevent value inserted on focus
							return false;
						},
						select: function( event, ui ) {
							var terms = page_properties.split( this.value );
							// remove the current input
							terms.pop();
							// add the selected item
							terms.push( ui.item.value );
							// add placeholder to get the comma-and-space at the end
							terms.push("");
							this.value = terms.join(", ");
							return false;
						}
					});
			},
			//save properties-------------------------------------------------------------------------
			saveProps: function(){
				$(".btn-save-prop").button().bind('click', function(event){
					var date_published = Date.parse($("#date_published").val()),
						show_until = Date.parse($("#show_until").val()),
						featured_from = Date.parse($("#featured_from").val()),
						featured_until = Date.parse($("#featured_until").val()),
						tags = $("#tags"),
						settings = {}, //pubdates = {},
						answer, getSections, postData, id;

					if ($("#expiration_date").val()){
						answer = w.confirm("You have selected an Expiration Date, this page will be deleted " +
							"at the end of that day. Are you sure?");
						if (!answer){
							event.stopImmediatePropagation();
							return false;
						}
					}

					if (show_until < date_published ){
						$.message("Show Until cannot be earlier than Date Published, please correct this and " +
							"try again.", 'error');
						event.stopImmediatePropagation();
						return false;
					}

					if (featured_until < featured_from ){
						$.message("Featured Until cannot be earlier than Featured From, please correct this and " +
							"try again.", 'error');
						event.stopImmediatePropagation();
						return false;
					}

					// pageID = coreEngine.pageID.match(/\d+/)[0]
					// sectionID = sections[i],
					// pubdate: pull
					// expdate: pull





					//Sections ----------------------------------------
					settings.sections = [];
					var storeID;
					getSections = function(){
						id = $(this).attr("id");

						settings.sections.push(id.substr(1));

					};
					$("ul#sections li.ui-selected").each(getSections);
					$("ul.sections-other li").each(getSections);
					var i;
					var p;
					var q;
					var z;
					for( i = 0;i < settings.sections.length; i++)
					{

						// section ID = settings.sections[i]
						$.message("Published to section: " + settings.sections[i]);
						//$.message($("#date_published").val());
						//alert($("ul#sections li.ui-selected").field("#date_published").text());
						p = "#dateContainer" + settings.sections[i];
						$.message(p);
						// we have page ID, section ID
						// we need published dates
						//alert($("input[name^='date_published']").val()); ---->> this works
						q = "date_published" + settings.sections[i];
						z = "show_until" + settings.sections[i];
						// ************************************************************************************
						//alert($("#"+q).val()); // this prints the date corressponding to the selected section
						// ************************************************************************************

						$.ajax({
							url: "../exists",
							method: "POST",
							data:{article:coreEngine.pageID.match(/\d+/)[0], section:settings.sections[i], pubDate:$("#"+q).val(), expDate:$("#"+z).val()},
							dataType:"text",
							success:function(data){
								alert("Success data from front end passed into backend");
							}

						});
					}
					// Published Dates
					settings.dates = [];
					getDates = function(){
						alert($("ul#sections li.ui-selected").getDates);
					};


					//Tags -------------------------------------------
					if (tags.length > 0){
						tags = tags.val();
						tags = tags.replace(/ *,/g, ",");
						tags = tags.replace(/, */g, ",");
						tags = tags.split(",");
						if ((tags[tags.length - 1] === "") || (tags[tags.length - 1] === " ")){ tags.pop(); }
						settings.tags = tags;
					}

					//All other settings -------------------------------------------------------------------------------
					$(".js-gen-settings").each(function(){
						if ($(this).is(":checkbox, :radio")){//if it's a checkbox or radio
							settings[this.name] = $(this).is(":checked");
						}else{
							settings[this.name] = this.value || null;
						}
					});
					postData = "pid=" + coreEngine.pageID;
					postData += "&data=" + JSON.stringify(settings);
					//alert(postData);

					coreEngine.ajax("properties/updatepage/" + (new Date()).getTime(), postData, function(result){
						//save permissions
						var permissions = [], postData;

						if (result.isError){
							$.message(result.errorStr, 'error');
						}else{
							$.message(result.data, 'success');

							$("#propPermissions").find("tr").each(function(){
								permissions.push(page_properties.getPerms($(this)));
							});

							postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
							postData += "&data=" + JSON.stringify(permissions);
							//alert(postData);
							coreEngine.ajax("/server/permupdate/" + (new Date()).getTime(), postData,
								coreEngine.genericCallBack, 'json');

							page_properties.setConfirmUnload(false);

//							return false;
						}
					}, 'json');

					page_properties.setConfirmUnload(false);

					return false;
				});
			},
			//retrieve all permissions in an object ----------------------------------------------------------------
			getPerms: function($tr){
				var perm = {},
					gid = $tr.find("th").attr("id");

				perm.group_id = gid.match(/\d+/)[0];
				perm.access = 0;

				$('td input[type="checkbox"]', $tr).not('.perm-checkbox-all').each(function(){
					var $this = $(this);

					if ($this.is(':checked')){
						perm.access += $this.data('perm');
					}
				});

				return perm;
			},
			//permissions-----------------------------------------------------------------------------------------------
			permissions: function(){
				var deleteClick, selectAll;

				//delete a permissions row -----------------------------------------------------------------------------
				deleteClick = function(){
					if (w.confirm("Are you sure?")){
						var permissions = [],
							$tr = $(this).closest('tr').addClass("perm-delete"), //mark for deletion
							postData;

						permissions.push(page_properties.getPerms($tr));

						postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
						postData += "&data=" + JSON.stringify(permissions);
						coreEngine.ajax("server/permdelete/" + (new Date()).getTime(), postData,
							page_properties.deleteCallback, 'json');
					}

					return false;
				};
				//select all perms -------------------------------------------------------------------------------------
				selectAll = function(){
					$(this).parent().parent().find('input[type="checkbox"]').prop('checked', $(this).is(":checked"));
				};
				$('#propPermissions').on('click', '.perm-checkbox-all', selectAll);
				//add permissions---------------------------------------------------------------------------------------
				$("#btnAddPerm").css("cursor", "pointer").click(function(){
					var $tr = $("<tr>"),
						$input = $("<input>"),
						$first_tr;

					$input.autocomplete({
						minLength: 2,
						source: function(request, response) {
							coreEngine.getJSON("who/groups/q/" + request.term, "", response);
						},
						select: function(event, ui){
							var permissions = [], postData,$deleteButton;

							$(this).parent().append(ui.item.value).attr("id", "gid-" + ui.item.id);

							permissions.push(page_properties.getPerms($(this).parent().parent()));

							postData = "pid=" + coreEngine.pageID.match(/\d+/)[0];
							postData += "&data=" + JSON.stringify(permissions);
							//alert(postData);
							coreEngine.ajax("server/permadd/" + (new Date()).getTime(),
								postData, coreEngine.genericCallBack, 'json');

							$deleteButton = $("<a>")
								.addClass("perm-btn-delete delete-a")
								.css("cursor", "pointer")
								.click(deleteClick);
							$(this).parent().parent()
								.find("td:last-child")
								.append($deleteButton);
							$(this).remove();
						}
					});

					$("<th>", {"scope": "row"}).append($input).appendTo($tr);

					$first_tr = $("#propPermissions").find("tr:first");

					$('td input[type="checkbox"]', $first_tr).each(function(){
						var $this = $(this),
							$td = $('<td>').appendTo($tr);

						$('<input>', {"class": $this.attr("class"), "type": "checkbox"})
							.data('perm', $this.data('perm'))
							.appendTo($td);
					});

					//give new group read permissions
					$('td:first input[type="checkbox"]', $tr).prop('checked', true);

					//Add an additional TD for the actions
					$('<td>', {"class": "actions"}).appendTo($tr);

					$("#propPermissions").append($tr);

					$input.focus();
				});

				//delete permissions ---------------------------------------------------------------------------------------
				$(".perm-btn-delete").css("cursor", "pointer").click(deleteClick);
			},
			//Revisions-----------------------------------------------------------------------------------------------------
			revisions: function(){
				//delete a revision --------------------------------------------------------------------------------------------
				$(".revs-btn-delete").css("cursor", "pointer").click(function(){
					if (w.confirm("Are you sure?")){
						var revID = $(this).parent().parent().addClass("perm-delete").find("td:first-child").text(),
							postData;

						postData = "pid=" + coreEngine.pageID;
						postData += "&revid=" + revID;
						//alert(postData);
						coreEngine.ajax("server/revdelete/" + (new Date()).getTime(), postData, page_properties.deleteCallback, 'json');
					}
				});
				//Revert back to a revision --------------------------------------------------------------------------------------------
				$(".revs-btn-revert").css("cursor", "pointer").click(function(){
					if (w.confirm("Are you sure?")){
						var revID = $(this).parent().parent().find("td:first-child").text(),
							postData;

						postData = "pid=" + coreEngine.pageID;
						postData += "&revid=" + revID;
						//alert(postData);
						coreEngine.ajax("server/revrev/" + (new Date()).getTime(), postData, page_properties.revrevCallback, 'json');
					}
				});
				//view a revision -----------------------------------------------------------------------------------------------
				$(".revs-btn-view").css("cursor", "pointer").click(function(){
					var revID = $(this).parent().parent().find("td:first-child").text();
					document.location = coreEngine.siteRoot + "article/" + coreEngine.pageID + "/revision/" + revID;

					return false;
				});
			},
			deleteCallback: function(result){
				if(result.isError){
					$.message(result.errorStr, 'error');
				}else{
					$(".perm-delete").remove();
				}
			},
			revrevCallback: function(result){
				if(result.isError){
					$.message(result.errorStr, 'error');
				}else{
					document.location = coreEngine.siteRoot + "article/" + coreEngine.pageID;
				}
			}
		};

		return page_properties;
	});
}(window));

// page properties - section settings toggles
function toggle_visibility(id) {
	// Get the DOM reference
	var contentId = document.getElementById(id);
	contentId.style.display == "none" ? contentId.style.display = "block" : contentId.style.display = "none";
}

// page properties - section selection dropdown values
function setValues(indexID, isPublished, articleID) {



	if(isPublished===0) // it's not saved as published but was just clicked and should be
	{
		//alert("the item you have selected wasn't selected previously.");
	}
	//alert('indexID is: '.concat(indexID));
	//alert(document.getElementById('date_published'.concat(indexID)).value);
	//alert('Second indexID is: '.concat(indexID));
	//alert(document.getElementById('expiration_date'.concat(indexID)).value);
	//alert('Third indexID is: '.concat(indexID));

// 1.
// .Load does not seem to execute 755 datePublish.php at all *************
//$("#updateDiv").Load("datePublish", showLoader); //?articleID=1&section=5000&pubDate=1/1/2016&expDate=1/1/2017", hideLoader);
// **********************************************************************

// 2.
// HREF method causes 'are you sure you want to close this page' popup
//window.location.href="http://fishnet.eileenfisher.net:8080/datePublish";
// **********************************************************************

//3.
// Write method causes a new page to load with the 0x0 image
//document.open();
//document.write('<img src="http://fishnet.eileenfisher.net:8080/datePublish">');
//document.close();
// **********************************************************************

//4.
// Standalone Img tag works with DB but leaves behind a small box on screen and I can't figure out how to generate on the fly properly.
//

// 5.
// window.location.replace also caused pop-up
}
