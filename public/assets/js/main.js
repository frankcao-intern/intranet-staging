;
/**
 * Created by: cravelo
 * Date: Apr 14, 2010
 * Time: 10:12:42 AM
 * This file contains all global javascript code excluding libraries and plugins.
 *
 * Modified by: mosrur
 * Date: Sept 19, 2016
 */

/*global jQuery, qq, jwplayer, define */
/*jslint browser: true, bitwise: true, white: true, nomen: true, plusplus: true, regexp: true */

/*!
 * jQuery Equal Height
 */
(function($){
	'use strict';

	$.fn.equalHeights = function(h){
		var height = h || 0;

		if (height === 0){
			$(this).each(function(){
				if($(this).height() > height){
					height = $(this).height();
				}
			});
		}

		$(this).css({'height': height});

		return this;
	};
}(jQuery));

/*!
 * Fancy Select
 */
(function($){
	'use strict';

	$.fn.fancySelect = function(){
		var $this = $(this),
			$dropdown;

		if (typeof($this.data('fancySelect')) === 'undefined'){

			$this.data('fancySelect', true).each(function(){
				var $select = $(this).addClass('offset'),
					$selected,
					options = '';

				$dropdown = $("<div class=\"dropdown fancy-select\"><div class=\"content\">" +
					"<ul class=\"list-b\"><\/ul><\/div><\/div>");
				$selected = $('<p class="selected-option">').prependTo($dropdown);

				$('option', $select).each(function(){
					options += '<li class="'+ $(this).prop('class') +'">' + $(this).text() + "<\/li>";
				});

				$('ul', $dropdown).html(options);

				$('li', $dropdown).click(function(){
					$('option:selected', $dropdown).prop('selected', false);
					$('option:eq(' + $(this).index() + ')', $select).prop('selected', true);

					$selected.text($(this).text());
					$('.content', $dropdown).hide();
					$('select', $select).change();
					return false;
				});

				$dropdown.hover(
					function(){ $('.content', this).show(); },
					function(){ $('.content', this).hide(); }
				);

				$selected.text($('option:selected', $select).length ? $('option:selected', $select).text() :
					$('option:first-child', $select) );

				$dropdown.insertBefore($select);
				$dropdown.parent().prepend($("label", $select));

				$('.content', $dropdown).css({
					'boxSizing': 'border-box',
					'width': $dropdown.outerWidth(),
					'top': $dropdown.outerHeight()
				});

				// IE bugs fix
				if(!$.browser.msie) {
					return;
				}
				$('li:last', $dropdown).addClass('last');

				if($.browser.version !== '6.0') {
					return;
				}
				$dropdown.hover(
					function(){ $(this).addClass('fancyselect-hover'); },
					function(){ $(this).removeClass('fancyselect-hover'); }
				);
			});
		}else{
			$dropdown = $this.prev('.dropdown');
		}

		return $dropdown;
	};
}(jQuery));

/**
 * coreEngine is my global object that holds some application state and global methods
 * @type {*|Object}
 */
var coreEngine = coreEngine || {};

/**
 * Functions that are needed globally
 * and scripts for all pages
 */
(function($){
	'use strict';

	/**
	* This is a base64 encoder/decoder that is compatible with PHP's base64 encode/decode functions
	* uses for transmitting complicated strings in URLs or POST payloads
	* I wanted to keep Who's Who search available by URL for advanced users and to be able to keep search results as favorites
	*
	* function encode(typeof input = string //string to be encoded)
	* function decode(typeof input = string //string to be decoded)
	*/
	window.base64 = {
		// private property
		_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/.",

		// public method for encoding
		encode : function (inp) {
			var output = "", input,
				chr1, chr2, chr3, enc1, enc2, enc3, enc4,
				i = 0;

			input = window.base64._utf8_encode(inp);

			while (i < input.length) {

				chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);

				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;

				if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				} else if (isNaN(chr3)) {
					enc4 = 64;
				}

				output = output +
				this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
				this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

			}

			return output;
		},

		// public method for decoding
		decode : function (inp) {
			var output = "", input,
				chr1, chr2, chr3,
				enc1, enc2, enc3, enc4,
				i = 0;

			input = inp.replace(/[^A-Za-z0-9\+\/=]/g, "");

			while (i < input.length) {

				enc1 = this._keyStr.indexOf(input.charAt(i++));
				enc2 = this._keyStr.indexOf(input.charAt(i++));
				enc3 = this._keyStr.indexOf(input.charAt(i++));
				enc4 = this._keyStr.indexOf(input.charAt(i++));

				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;

				output += String.fromCharCode(chr1);

				if (enc3 !== 64) {
					output += String.fromCharCode(chr2);
				}
				if (enc4 !== 64) {
					output += String.fromCharCode(chr3);
				}

			}

			output = window.base64._utf8_decode(output);

			return output;

		},

		// private method for UTF-8 encoding
		_utf8_encode : function (s) {
			var string = s.replace(/\r\n/g,"\n"),
				utftext = "",
				n, c;

			for (n = 0; n < string.length; n++) {
				c = string.charCodeAt(n);

				if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}else{
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
			}

			return utftext;
		},

		// private method for UTF-8 decoding
		_utf8_decode : function (utftext) {
			var string = "",
				i = 0, c = 0, c2 = 0, c3 = 0;

			while ( i < utftext.length ) {

				c = utftext.charCodeAt(i);

				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}

			}

			return string;
		}
	};

	/**
	 * transforms "bla bla {1} bla {2}" into: "bla bla something bla something else"
	 * by doing:
	 * "bla bla {1} bla {2}".format('something', 'something else');
	 */
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/\{(\d+)\}/g, function(match, number) {
			return (typeof args[number - 1] === 'undefined') ? match : args[number - 1];
		});
	};

	/**
	 * This is a callback for ajax calls that delete a page or section
	 * @param result
	 */
	var deletePage_callback = function(result){
		if (result.isError){
			$.message(result.errorStr, 'error');
		}else{
			document.location = coreEngine.siteRoot + 'my/profile';
		}
	};

	$.extend(coreEngine, {
		qtipDefaults: {
			style: {
				tip: {
					width: 20,
					height: 8,
					corner: 'bottom center'
				},
				def: false,
				classes: 'qtip-ef ui-tooltip-shadow ui-tooltip-rounded',
				width: 300
			},
			position: {
				my: 'bottom center',
				at: 'top center'
			},
			show: { effect: false },
			hide: {
				fixed: true,
				delay: 150,
				effect: false
			} // Make it fixed so it can be hovered over
		},
		/* function used to add a new favorite. */
		addFavorite: function(){
			var postData = "fav_url=" + document.location.toString() +
							"&fav_title=" + document.title;
			coreEngine.ajax('who/addfavorite/' + (new Date()).getTime(), postData,
					coreEngine.genericCallBack, 'json');
		},
		/**
		 * generic ajax callback function that displays a message either with error or normal
		 */
		genericCallBack: function(result){
			if ((typeof result === 'object') && (result.isError === false)){
				$.message(result.data, 'success');
			}else{
				$.message(result.errorStr, 'error');
			}
		},
		getJSON: function(url, postData, callBackFunc){
			return coreEngine.ajax(url, postData, callBackFunc, 'json', 'GET');
		},
		/**
		 * function to append the CSRF token to all ajax post requests
		 * @param {String}      url                     the url for the request
		 * @param {String}      postData                the extra data to pass
		 * @param {Function}    callBackFunc            the callback function
		 * @param {String}      [responseType='json]    the type of information expected.
		 * @param {String}      [type='POST']           the type of response.
		 */
		ajax: function(url, postData, callBackFunc, responseType, type){
			var ps = postData,
				lUrl = url,
				lResponseType = responseType,
				lType = type;

			//if (url[0] === '/'){ lUrl = url.substr(1); }else{ lUrl = url.replace(coreEngine.siteRoot, ''); }
			if (url[0] === '/'){
				lUrl = url.substr(1);
			}
			if (/^https?/.test(lUrl) === false){
				lUrl = coreEngine.siteRoot + lUrl;
			}

			if (typeof responseType === 'undefined'){ lResponseType = 'json'; }
			if (typeof type === 'undefined'){ lType = 'POST'; }

			ps += (postData === "") ? "" : "&";
			ps += "csrf_fishnet=" + coreEngine.csrf_token;

			return $.ajax({
				type: lType,
				url: lUrl,
				data: ps,
				success: callBackFunc,
				dataType: lResponseType,
				cache: false,
				statusCode: {
					401: function() {
						$.message('Your session has expired and you need to refresh the page. If you are editing, ' +
							'copy your content, refresh and then paste the content back.', 'error');
					},
					500: function() {
						$.message('An error occurred on the server. Please report this issue using the feedback form ' +
								'found in the footer. Be specific, explain where in the site it happen and what were ' +
								'you trying to do.', 'error');
					}
				}
			});
		},
		/**
		 * load the page content after ajax callback without refreshing the page
		 */
		loadUrl: function(selector, destUrl){
			var url = coreEngine.siteRoot + destUrl + coreEngine.pageID.match(/\d+/)[0];
			$("+selector+").html(ajax_load).load(url);
		},
		/**
		 * article listing - manual sorting order function which take
		 */
		sortOrder: function(sectionName){
			jQuery('#sortOrder').sortable({
				axis: 'y',
				stop: function (event, ui) {
					var sortData, postData;
					sortData = $(this).sortable('toArray');

					// creating data array and encoding with json stringify
					postData = "sid=" + coreEngine.pageID.match(/\d+/)[0];
					postData += "&data=" + JSON.stringify(sortData);

					// calling the ajax function to update the data
					coreEngine.ajax("article/sortOrder/" , postData, coreEngine.genericCallBack, 'json');

					// loading the content with updated data
					coreEngine.loadUrl('#sortOrder', sectionName);

					// return msg
					return false;

				}
			});
		},
		/**
		 * New page function, requests a list of templates form the server then displays the dialog to create a new page.
		 */
		newPage: function(){
			var displayTempl = function(result){
				if (result.isError){
					$.message(result.errorStr, 'error');
				}else{
					var $catSelect = $('#npdCat').html(''),
						categories = result.data.categories,
						templates = result.data.templates,
						tdata = [],
						$npdTempl = $('#npdTempl');

					$('#npdPrivate').checkbox({
						empty: coreEngine.siteRoot + 'assets/images/empty.png'
					}).bind('click', function(){
							var $publish_to = $('#newPageDialog').find('input[name=publish_to]');

							if ($('#npdPrivate').is(':checked')){
								$publish_to.val('');
							}else{
								$publish_to.val($publish_to.data('privateid'));
							}
						});

					//populate the templates list
					$(templates).each(function(i, templ){
						var t = {};

						t.id = templ.template_id;
						t.img = coreEngine.siteRoot + "assets/images/templates/" + templ.template_name + ".png";
						t.title = templ.template_title;
						t.name = templ.template_name;
						t.tclass = templ.category.replace(/([^a-zA-Z0-9])/g, '');

						tdata.push(t);
					});

					$npdTempl
						.html('')
						.append(coreEngine.attachTemplateToData($('#npdTemplates').text(), tdata))
						.find('li label img')
						.dblclick(function(){
							$('button:eq(0)', $("#newPageDialog").next('.ui-dialog-buttonpane')).click();
						})
						.click(function(){
							$('#npdTempl').find('li label img').css({'border':'none', 'margin': '0'});
							$(this).css({'border':'1px solid #F39814', 'margin': '-1px'});

							$('input:radio[name=tid]:checked').prop('checked', false);
							$('input:radio', $(this).closest('li')).prop('checked', true);
						});

					//populate the categories dropdown
					$(categories).each(function(i, cat){
						$catSelect.append('<option class="'+ cat.replace(/([^a-zA-Z0-9])/g, '') +'">'+
							cat +'<\/option>');
					});

					$("#newPageDialog").dialog("open");

					//on change for the template
					$catSelect.change(function() {
						var visible = $('option:selected', this).prop('class'),
							jspAPI = $('#npdTemplDiv').data('jsp');

						$('.templates').hide();
						$('.' + visible).show();

						jspAPI.reinitialise();
					})
						.change()
						.parent().fancySelect();
				}
			};

			coreEngine.getJSON("server/templates/" + (new Date()).getTime(), '', displayTempl);
		},
		/**
		 * redirects to event properties, this is needed to allow anyone to publish an event to their own calendar
		 */
		eventProperties: function(){
			var event_id = coreEngine.pageID.match(/\d+/)[0];
			document.location = coreEngine.siteRoot + "eventprops/" + event_id;
		},
		/**
		 * Make an ajax request to delete a page and handle the callback
		 */
		deletePage: function(){
			var res = window.confirm("Are you sure?");
			if (res){
				coreEngine.ajax("article/delete", 'delete=1&pid=' + coreEngine.pageID,
					deletePage_callback);
			}
		},
		/**
		 * Delete an event
		 */
		deleteEvent: function(){
			var res = window.confirm("Are you sure?"),
				event_id = coreEngine.pageID.match(/\d+/)[0];

			if (res){
				coreEngine.ajax("calendar/deleteevent", 'event_id=' + event_id, deletePage_callback);
			}
		},
		/**
		 * the 2 outlook functions to send emails, one for contacts and the other for events
		 */
		addContact: function(){
			coreEngine.ajax("who/sendcontact", 'user_id=' + coreEngine.pageID.match(/\d+/)[0],
				coreEngine.genericCallBack);
		},
		sendEvent: function(){
			coreEngine.ajax("calendar/sendevent", 'event_id=' + coreEngine.pageID.match(/\d+/)[0],
				coreEngine.genericCallBack);
		},
		fancybox: function(){//this is the activation of fancybox on all images on the site that have the class
			$("a.fancybox").fancybox({
				type: 'image',
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
					},
					thumbs	: {
						width	: 80,
						height	: 80
					}
				}
			});
		},
		imageUploadCallback: function(result){
			if (result.isError){
				$.message(result.errorStr, 'error');
			}else{
				$('#' + result.data.gallery_id).append(
					$("<li>")
						.hide()//TODO: make the template somehow control whether new pics should be hidden or not.
						.append(
							$('<div>')
								.addClass('image')
								.append(
									$("<a>").addClass("fancybox")
										.attr("rel", result.data.gallery_id)
										.attr("href", result.data.image_data.src)
										.attr("title", result.data.image_data.alt)
										.data("index", result.data.image_id)
										.append(
											$("<img>").attr("title", "")
												.addClass('article_main_image')
												.attr('alt', result.data.image_data.alt)
												.prop('src', result.data.image_data.src)
												.data('credit', '')
												.data('date', result.data.image_data.date)
												.data('desc', '')
												.data('flip', false)
												.data('angle', 0)
										)
								)
						)
				);

				coreEngine.fancybox();

				$.message(result.errorStr, 'success');
			}
		},
		attachTemplateToData: function(template, data) {
			var i = 0,
				len = data.length,
				fragment = '',
				replace = function (obj, idx) { // For each item in the object, make the necessary replacement
					var t = template, key, reg;

					//replace index
					obj.idx = idx;

					for (key in obj) {
						if (obj.hasOwnProperty(key)){
							reg = new RegExp('{{' + key + '}}', 'ig');
							t = (t || template).replace(reg, obj[key]);
						}
					}

					return t;
				};

			for (i; i < len; i++) {
				fragment += replace(data[i], i);
			}

			return fragment;
		},
		checkboxes: function(){
			$('.js-checkbox').each(function(){
				if ($('input[type="checkbox"]', this).length === 0){
					$(this).prepend($('<input type="checkbox" style="margin-right: 3px;">'));
				}
			});

			$('.js-checkbox input[type="checkbox"]').attr('disabled', true);
		}
	});

	var Engine = {
		dialogs: {
			//this is the What are you up to? question on the top links, the dialog and the click action.
			whatareyouupto: function(){
				$("#WRYDoingDiag").dialog({
					bgiframe: true,
					autoOpen: false,
					resizable: false,
					width: 320,
					modal: false,
					buttons: {
						"Update Status": function(){
							var postData = "t=" + $("#status", this.parent).val();
							coreEngine.ajax('who/userupdatestatus/' + (new Date()).getTime(), postData, coreEngine.genericCallBack, 'json');
							$(this).dialog('close');
						}
					}
				});

				$("#WRYDoing").click(function(){
					$("#WRYDoingDiag").dialog('open');

					return false;
				});
			},
			sharePage: function(){
				$("#btnShare").click(function(){
					$("#sharePageDiag").dialog('open');
				});

				$("#sharePageDiag").dialog({
					bgiframe: true,
					autoOpen: false,
					resizable: false,
					width: 252,
					modal: false,
					open: function(){
						$("ul", this).empty();
						$("textarea, input", this).val('');
					},
					buttons: {
						"Send": function() {
							var $lis = $("ul li", this),
									emails = [],
									postData;

							$lis.each(function () {
								emails.push($(this).data('email'));
							});

							if (emails.length === 0) {
								$.message("You must select at least one recipient.", 'error');
							} else {
								postData = "emails=" + JSON.stringify(emails);
								postData += "&msg=" + $("#sharePageDiag").find("textarea").val();
								postData += "&pid=" + coreEngine.pageID;
								//alert(postData);
								coreEngine.ajax('article/share', postData, coreEngine.genericCallBack);

								$(this).dialog('close');
							}
						},
						'Cancel': function(){
							$(this).dialog('close');
						}
					}
				});

				$('#shareEmail').autocomplete({
					minLength: 2,
					source: function(request, response) {
						coreEngine.getJSON("who/search/qkey/display_name/q" + "/" +
							window.base64.encode(request.term), "", response);
					},
					select: function(event, ui){
						var $li = $('<li>'),
							$ul = $("#sharePageDiag").find("ul"),
							$shareEmail = $('#shareEmail');

						if (ui.item.email){
							if ($('li[value="'+ ui.item.id +'"]', $ul).length === 0){
								$li
									.data('email', ui.item.email)
									.val(ui.item.id)
									.text(ui.item.value)
									.click(function(){
										$(this).remove();
									});
								$ul.append($li);
								$shareEmail.val('');
							}else{
								$shareEmail.val('Name already selected').get(0).select();
							}
						}

						return false;
					}
				});
			},
			newpage: function(){ //this is the new page dialog.
				var	checkRadios = function (o, m){
						if (o.length === 0){
							o.addClass('ui-state-error');
							$.message(m, 'error');

							return false;
						}

						return true;
					};

				$("#newPageDialog").dialog({
					bgiframe: true,
					autoOpen: false,
					resizable: false,
					modal: true,
					width: 500,
					buttons: {
						'Create page': function(){
							var $this = $(this),
								$templ = $("input[name=tid]:checked", $this),
								bValid = true;

							bValid = bValid && checkRadios($templ, "You must select a template.");

							if (bValid){
								$('#tname').val($templ.data('tname'));
								$('#newPageForm').get(0).submit();

								$(this).dialog('close');
							}
						},
						Cancel: function(){
							$(this).dialog('close');
						}
					},
					open: function(){
						$('#npdCat').change();
					}
				});
			}
		},
		utils : {
			links : function(){
				$('a[rel*=external]').click(function(){
					window.open($(this).attr('href'));

					return false;
				});
			},
			mails : function(){
				$('a[href^="mailto:"]').each(function(){
					var mail = $(this).attr('href').replace('mailto:',''),
						replaced = mail.replace('/at/','@');

					$(this).attr('href','mailto:'+replaced);
					if($(this).text() === mail) {
						$(this).text(replaced);
					}

					if($(this).attr('title') === mail) {
						$(this).attr("title", replaced);
					}
				});
			},
			/* Related links for article pages */
			relatedlinks : function(){
				var $rel = $("aside.secondary"),
					i, $h3, $p, relEach;

				if ($rel.length > 0){//if the element exists
					relEach = function(){
						$p = $(this).parent('p');
						$(this).removeClass("related_links_" + i).addClass('related_links_content').appendTo($rel);
						$p.remove();
					};
					//user wants five different "sections"
					for (i = 1; i < 6; i++){
						$h3 = $("h3.related_links_" + i)
							.removeClass("related_links_" + i)
							.addClass('related_link')
							.appendTo($rel);
						$h3.parent('p').remove();

						$(".related_links_" + i).each(relEach);
					}
				}
			},
			/*View Gallery link for when there is more than one image in a stack*/
			viewgallery : function(){
				$(".view-gallery").css("cursor", "pointer").click(function(){
					$("a.fancybox:eq(0)", $(this).parent()).click();

					return false;
				});
			}
		},
		enhancements : {
			lazyLoad: function(){
				$('img').lazyload({
					threshold : 200,
					effect : "fadeIn"
				});
			},
			buttons: function(){
				$('a.js-button').button();
			},
			footerPanels : function() {
				var $container = $('.footer-panels'),
					$nav = $('.footer-panels-nav', $container),
					$panels = $('.section', $container);

				$('a', $nav).click(function(){
					$(this).parent().siblings().find('.active').removeClass('active');
					$(this).toggleClass('active');

					$panels.filter('.active-section').removeClass('active-section');
					if($(this).is('.active')) {
						$panels.filter($(this).hrefId()).toggleClass('active-section');
					}

					if($('.active', $nav).length) {
						$container.addClass('footer-tab-active');
					} else {
						$container.removeClass('footer-tab-active');
					}

					$panels.not('.active-section').slideUp().end().filter('.active-section').slideToggle();

					return false;
				});

				//tutorials
				$('a', $nav).click(function(){
					jwplayer($('#tutVideo').get(0)).stop();
				});

				jwplayer($('#tutVideo').get(0)).setup({
					'autostart': 'false',
					'controlbar': 'over',
					'skin': coreEngine.siteRoot + "assets/flash/efglow/efglow.zip",
					'flashplayer': coreEngine.siteRoot + "assets/flash/jwplayer.swf?" + (new Date()).getTime(),
					'height': '257',
					'width': '388',
					'file': $('.tutorials-index a:eq(0)').get(0).href
				});

				$('.tutorials-index a', $container).click(function(){
					jwplayer($('#tutVideo').get(0)).load({file: this.href}).play();

					return false;
				});
			},
			placeholders: function(){
				$('input, textarea').placeholder();
			},
			sort_links: function(){
				var $ul = $(".digest-nav"),
					$li = $(".sort-link", $ul),
					$highliter = $(".highlighter", $ul);

				$li.bind('mouseenter', function(){
					var $this = $(this),
						pos = $this.position();

					$highliter.stop().animate({ 'left': pos.left, 'width': $this.width() }, function(){
						$(this).addClass('hovering');
					});
				});

				$ul.bind('mouseleave', function(){
					var $active = $("li a.active", $ul),
						pos;

					if ($active.length){
						pos = $active.parent().position();
						$highliter.stop().animate({
							'left': pos.left,
							'width': $active.parent().width()
						});
					}
				});
			},
			equalColumns : function() {
				$('.double-a .col').equalHeights();
				$('.double-a .col>div').equalHeights();
				$('.triple-a .col').equalHeights();
				$('.triple-a .col div').equalHeights();
				$('.quadruple-a .col').equalHeights();
				/*$('.quadruple-a .col h2.b').equalHeights();
				$('.quadruple-a .col div').equalHeights();*/
			},
			scrollbars: function(){
				/*Swaps scrollbars for divs with custom*/
				$('.scroll-pane').jScrollPane({
					verticalDragMinHeight: 60,
					verticalDragMaxHeight: 60,
					verticalGutter: 24
				});
			},
			collapsibleSections : function() {
				$(".collapsible")
					.unbind('click')
					.bind('click' , function(){
						$(this)
							.toggleClass("collapsible-closed")
							.next()
							.slideToggle(300);
					})
					.filter(".collapsible-closed")
					.next()
					.slideUp(300);
			},
			forms : {
				/* Up/Down buttons select */
				selectA: function() {
					var nextItem = function($select) {
							var $nextOption = '';

							if(!$('option:selected', $select).length || $('option:selected', $select).is(':last-child')) {
								$nextOption = $('option:first-child', $select);
							} else {
								$nextOption = $('option:selected', $select).next();
							}

							$('option:selected', $select).removeAttr('selected');
							$nextOption.attr('selected', 'selected');
						},
						prevItem = function($select){
							var $prevOption = '';

							if(!$('option:selected', $select).length || $('option:selected', $select).is(':first-child')) {
								$prevOption = $('option:last-child', $select);
							} else {
								$prevOption = $('option:selected', $select).prev();
							}

							$('option:selected', $select).removeAttr('selected');
							$prevOption.attr('selected', 'selected');
						};

					$('.select-a').each(function(){
						var $select = $(this),
							$replaced = $('<div class="select-a-replaced select-a-replaced-a"><p class="buttons">' +
								"<button class=\"prev\">Previous<\/button><button class=\"next\">Next<\/button>" +
								"<\/p><\/div>"),
							$next = $('.next', $replaced),
							$prev = $('.prev', $replaced),
							$current = $("<p class=\"current\"><\/p>").prependTo($replaced);

						$replaced.addClass($select.attr('class').replace('select-a', ''));

						$next.click(function(){
							nextItem($select);
							$current.text($('option:selected', $select).val());

							return false;
						});

						$prev.click(function(){
							prevItem($select);
							$current.text($('option:selected', $select).val());

							return false;
						});

						/* Hide real select */
						$select.addClass('offset');

						/* Default text on page load */
						$current.text( $('option:selected', $select).length ? $('option:selected', $select).val() : $('label', $select).text() );

						$replaced.insertBefore($select);
					});
				},
				/* Dropdown select for Who's Who */
				selectB : function() {
					$('.select-b').fancySelect();
				}
			},
			rotators : {
				/* Basic rotator, e.g. Department Overview */
				rotatorB : function() {
					$('.rotator-b').each(function(){
						var $container = $(this),
							$rotatorControls = $('.rotator-controls', $container),
							$currentContainer = $('<p class="current current-b"><em class="min"><\/em> ' +
									'<span>of<\/span> <em class="max"><\/em><\/p>'),
							$current = $('.min', $currentContainer),
							$rotatorNav = $('<p class="rotator-nav rotator-nav-a"><button class="prev">' +
									'Previous page<\/button><button class="next">Next page<\/button><\/p>'),
							$next = $('.next', $rotatorNav),
							$prev = $('.prev', $rotatorNav),
							len = $('.rotator ul > li', this).length;

						if ($container.data('rotator-b') === true){ return false; }
						$container.data('rotator-b', true);

						//if this rotator is already initialized then don't redo it
						if ($container.data('rotator-b') === true){ return false; }
						$container.data('rotator-b', true);

						//transform the DOM
						$currentContainer.prependTo($rotatorControls);
						$rotatorNav.prependTo($rotatorControls);

						//add current of max text
						$current.text('1').parent().find('.max').text(len);

						//call cycle
						$('.rotator ul').cycle({
							fx: "scrollHorz",
							speed: "fast",
							easing: 'easeInOutQuad',
							next: $next,
							prev: $prev,
							timeout: 0,
							after:  function(curr, next) {
								$current.parent().find('.min').text($(next).index() + 1);
							}
						});

						return true;
					});
				}
			},
			/* Contact info tooltip */
			whotooltip : function() {
				var $tooltip,
					offsetTop;

				$('.search-results-a .vcard')
					.each(function(){
						$tooltip = $('.tooltip-contact', this).hide();
						offsetTop = $('.image', this).outerHeight() - $tooltip.outerHeight();

						$tooltip.css('top', offsetTop + 3); //not sure why it has a 3 pixel offset
					})
					.hover(
						function(){
							if ($.browser.msie) {
								$('.tooltip-contact', this).show();
							} else {
								$('.tooltip-contact', this).stop().fadeTo(500, 1);
							}
						},
						function(){
							if ($.browser.msie) {
								$('.tooltip-contact', this).hide();
							} else {
								$('.tooltip-contact', this).stop().fadeTo(200, 0);
							}
						}
					);
			},
			ads_placements: function(){
				$('#adsSection').find('h2.ad a').each(function(){
					var $this = $(this),
						$tooltip = $this.next('.ad-tooltip').hide();

					$this.qtip($.extend({}, coreEngine.qtipDefaults, {
						content: {
							'text': $tooltip
						}
					}));
				});
			},
			thebuzz: function(){//this creates the BUZZ on the homepage right column
				var processRSS = function(feed){
					var i, $h3, $p,
						$rssNews = $("#rssNews");

					if (feed && ($('#rssNews').length > 0)){
						if ($rssNews.attr('title') === "loading"){
							$rssNews.empty().attr('title', '');
						}

						for (i = 0; i < feed.length; i++){
							$h3 = $("<h3>", {"class": "d"});
							$('<a>', {"href": feed[i].link, "target": "_blank"})
								.html(feed[i].myTitle + '&nbsp;&#x25ba;')
								.appendTo($h3);

							$p = $('<p>').html(feed[i].title);

							$rssNews
								.append($h3)
								.append($p)
								.find("a:last")
								.qtip($.extend({}, coreEngine.qtipDefaults, {
									content: {
										'text': feed[i].description
									}
								}));
						}
					}
				};

				if($('#rssNews').length > 0){//if that element exists
					coreEngine.ajax("proxy/buzz", '', processRSS, 'json', 'GET');
				}
			},
			savePrintPage: function(){
				$('.buttonset a:first').button({
					icons: {
						primary: 'ui-icon-star'
					}
				}).next().button({
					icons: {
						primary: 'ui-icon-extlink'
					}
				}).next().button({
					icons: {
						primary: 'ui-icon-print'
					}
				});
				$('.buttonset').buttonset();

				$('#btnSave').click(function(){
					coreEngine.addFavorite();
				});

				$('#btnPrint').click(function(){
					if (window.print){ window.print(); }
				});
			}
		},
		fixes : {
			hoverFix : function() {
				if(!$.browser.msie) {
					return;
				}

				$('#nav').find('> li:has(.dropdown)').hover(
					function(){ $(this).addClass('hover').find('.dropdown').show(); },
					function(){ $(this).removeClass('hover').find('.dropdown').hide(); }
				);
			},
			nthChild : function() {
				$('.search-results-a li:nth-child(4n+1)').addClass('first-in-row');

				$('.columns').each(function(){
					$('.col:first', this).addClass('col-first');
				});

				$('.tutorials-index ul:first').addClass('first');

				if(!$.browser.msie) {
					return;
				}

				$(".article h2:first-child").addClass('first');

				$('.dropdown').each(function(){
					$('ul:first', this).addClass('first');
				});

				$('ul.list-b li:last-child, #top-nav li ul li:last-child, .rotator-a .item:last-child, .video-rotator .item:last-child, .calendar-a th:last-child, .calendar-a td:last-child').addClass('last');

				$('table.b').each(function(){
					$(this).find('tbody tr:nth-child(odd)').addClass('odd');
				});
			},
			ie6 : function() {
				var $tabsNav = $('.footer-panels .footer-panels-nav'),
					$tabs = $('a span', $tabsNav),
					offset = $tabsNav.offset().left;

				if(!$.browser.msie || $.browser.version !== '6.0') {
					return;
				}

				$('.calendar-a th:first-child, .calendar-a td:first-child, #nav li div ul:first-child, #top-nav li:first-child, #top-nav li ul li:first-child, #footer p a:first-child, .video-controls li:first-child, .alphabet-a li:first-child, .photo-editor .item .tools li:first-child, .events-a li:first-child, .rotator-c .photos li:first-child, table.b thead th:first-child, table.b tbody td:first-child, table.b tbody th:first-child').addClass('first');

				$('.columns .col:first-child').addClass('col-first');

				$('.header-a').next('div.featured-a').addClass('featured-first');

				/* Hover on elements */
				$('#top-nav').find('li').hover(
					function(){ $(this).addClass('hover'); },
					function(){ $(this).removeClass('hover'); }
				);

				$('.rotator-a .item .thumb a, .rotator-c .photos a').hover(
					function(){ $('.hover-border', this).show(); },
					function(){ $('.hover-border', this).hide(); }
				);

				$('.dropdown').hover(
					function(){ $(this).addClass('dropdown-hover'); },
					function(){ $(this).removeClass('dropdown-hover'); }
				);

				/* Footer: active tab background */
				if($tabs.length > 1) {
					$tabs.css('width', parseInt($(document).width()/2, 10));
					$tabs.filter(':first').css('left', -offset);
				}
			},
			ieArrayIndexOf : function(){
				if (!Array.indexOf) {
					Array.prototype.indexOf = function (obj, start) {
						var i;

						for (i = (start || 0); i < this.length; i++) {
							if (this[i] === obj) {
								return i;
							}
						}
						return -1;
					};
				}
			}
		},
		whosWhoNotify: function(){
			if ((coreEngine.pageID === '1') || (coreEngine.pageID === '4')){
				coreEngine.getJSON('who/checkjoboverview', '', function(result){
					if (result.isError === true){
						$.message(
							'It looks like you haven\'t filled out your Who\'s Who profile. What are you waiting ' +
								'for? It only takes 2 minutes. <a href="' + coreEngine.siteRoot + 'edit/my/profile">' +
								'Update it now<\/a>.',
							'info'
						);
					}
				});
			}
		},
		showLoginMessage: function(){
			var msg = $('#msg').html();
			//if ((msg === '') & (coreEngine.pageID === '12'))
			//	{$.message ('The Doc Spot has a new clean look! You can easily search for documents by toggling between two buttons, "Forms & Documents by Category" ' +
			//			'and "All Forms & Documents". Our "Guidelines & Resources" are located on the right side of the page. If there are questions or comments, ' +
			//			'please provide them via the <a href="' + coreEngine.siteRoot + 'feedback">' +
			//			'"Feedback" form<\/a>','info');}

			//else {
				msg = msg.split('@');
				$.message(msg[1], msg[0]);
			//}
			
			//else {$.message ('The Doc Spot has a new clean look! You can easily search for documents by toggling between two buttons, "Forms & Documents by Category" ' +
			//			'and "All Forms & Documents". Our "Guidelines & Resources" are located on the right side of the page. If there are questions or comments, ' +
			//			'please provide them via the "Feedback" form, located at the bottom of the page.','info');}
		
		},
        /*
        pageReviewNotification: function(){
            if ((coreEngine.pageID === '1') || (coreEngine.pageID === '4')){
                coreEngine.getJSON('article/checkPagerview', '', function(result){
                    if (result.isError === true){
                        $.message(
                            'It looks like you haven\'t filled out your Who\'s Who profile. What are you waiting ' +
                            'for? It only takes 2 minutes. <a href="' + coreEngine.siteRoot + 'edit/my/profile">' +
                            'Update it now<\/a>.',
                            'info'
                        );
                    }
                });
            }
        },
        */

	};

	/*! * (v) hrefID jQuery extention * returns a valid #hash string from link href attribute in Internet Explorer */
	(function(a){a.fn.extend({hrefId:function(){return a(this).attr("href").substr(a(this).attr("href").indexOf("#"))}})})(jQuery);jQuery(function(b){var a={ui:{panelmenu:function(){b("ul.account-list span.account-list-item").click(function(){b(this).next().slideToggle("fast");b(this).toggleClass("active");return false});b("ul.account-list li.last span.account-list-item").click(function(){b(this).parent().toggleClass("changebgr");return false})},tabs:function(){var c=b("#tabs div.tab");if(c.length===0){return}var d=b(".nav-a a");d.click(function(f){d.parent().removeClass("active");f.preventDefault();c.hide();b(b(this).hrefId()).show();b(this).parent().addClass("active")});c.eq(0).show()},investmentmore:function(){b(".newbox-investment .investment-desc a.show-more, .ad-investment .investment-desc a.show-more").click(function(){if(b(".investment-desc-more:visible").length){b(".investment-desc-more").hide();b(".investment-desc-less").show()}else{b(".investment-desc-less").slideUp("fast",function(){b(".investment-desc-more").slideDown()})}return false})},importcheckbox:function(){b(".importBox a.show-options").click(function(){b(this).next().slideToggle("fast");var c=b(this).text()=="ukryj opcje"?"pokaż opcje":"ukryj opcje";b(this).text(c);return false})}}};a.ui.panelmenu();a.ui.tabs();a.ui.investmentmore();a.ui.importcheckbox()});function showTipActivate(){$(function(){$(".showTip").mouseover(function(){var a=$(this).position();var b=$(this).next();b.css("top",a.top-20-b.outerHeight());b.css("left",a.left-22);b.show()}).mouseout(function(){$(this).next().hide()})})}function scrollToObj(a){var c=$(a).offset();var b=c.top;$("html, body").animate({scrollTop:b},500)};

	//on DOM ready
	$(document).ready(function(){
		Engine.utils.links();
		Engine.utils.mails();
		Engine.utils.relatedlinks();
		Engine.utils.viewgallery();
		Engine.fixes.hoverFix();
		Engine.fixes.nthChild();
		Engine.fixes.ie6();
		Engine.fixes.ieArrayIndexOf();
		Engine.enhancements.equalColumns();
		Engine.enhancements.lazyLoad();
		Engine.enhancements.collapsibleSections();
		Engine.enhancements.scrollbars();
		Engine.enhancements.placeholders();
		Engine.enhancements.forms.selectA();
		Engine.enhancements.forms.selectB();
		Engine.enhancements.rotators.rotatorB();
		Engine.enhancements.whotooltip();
		Engine.enhancements.ads_placements();
		Engine.enhancements.thebuzz();
		Engine.enhancements.savePrintPage();
		Engine.enhancements.sort_links();
		Engine.enhancements.footerPanels();
		Engine.enhancements.buttons();
		Engine.dialogs.sharePage();
		Engine.dialogs.newpage();
		Engine.dialogs.whatareyouupto();
		Engine.whosWhoNotify();
		Engine.showLoginMessage();


		coreEngine.fancybox();
		coreEngine.checkboxes();
	});
}(jQuery));
