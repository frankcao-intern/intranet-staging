/**
 * User: cravelo
 * Date: Jun 29, 2010
 * Time: 10:19:33 AM
 * This file contains the JS code for the edit_mode, the gallery management, etc
 * Passed JSLint on Jul 22, 2011 (except for unused variable "uploader" (559))
 */

/*jslint unparam: true, white: true, browser: true */
/*global require, base64, coreEngine, qq */

require(['jquery',
	'lib/tinymce/plugins/ibrowser/interface/common',
	'lib/jquery.myeditable',
	'lib/fileuploader',
	'lib/jquery.jcrop',
	'lib/jquery.multiselect',
	'lib/jquery.checkbox'], function($){

	'use strict';
	require(['lib/jquery.multiselect.filter', 'lib/tinymce/jquery.tinymce'], function(){

		var jcrop_api,
			color_pallette,
			EditEngine = {
				setup_edit_in_place: function(){
					$('.edit-textinline').editable({
						type: 'textInline',
						save: EditEngine.saveElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-textarea').editable({
						type: 'textarea',
						save: EditEngine.saveElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-select').editable({
						type: 'selectInline',
						options: ['Retail', 'Company', 'Boutique'],//TODO: implement that the template defines this
						save: EditEngine.saveElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-page-property').editable({
						type: 'textInline',
						save: EditEngine.changePageTitle,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-datepicker').editable({
						type: 'datepicker',
						save: EditEngine.updatePage,
						dateFormat: 'yy-mm-dd',
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					color_pallette = "face46,fabc00,faa500,fa8d00,fc7a1b," + //yellow
						"fb875a,e76f58,cf4e35,a93720,654515,996625,bf873d," + //earth
						"cd3315,ae1d00,dd3d3d,cb5e5e,b26060,9a5656," + //reds
						"b26060,905171,783858,652444,a10d6b,7c004e,a95699,6c4480,563766,795c87," + //purples
						"607DB7,5E759E,444989,5a61b7,757ff0,7376a5,9fa3d0,427ed9,77a8f2,a6c9fd,607fae,3f5a84,32496e," + //blues
						"005a7c,0e7094,2582a4,1d9ac8,75a9bc,54899d,54899d,428785,79a3a2," + //teals
						"3e6f33,4e7e43,76a06c,496743,657804,7e9117,9fb61c,BADA55,9a9e5b,787d36,5c6123," + //greens
						"F6F4F1,BBBBBB,EAE5DC,86827E,333333,454545,000000"; //grays

					$('.edit-wysiwygadv').editable({
						type: 'wysiwyg',
						save: EditEngine.saveElement,
						saveText: 'Save',
						cancelText: 'Cancel',
						tinyMCEOpts: {
							// Location of TinyMCE script
							script_url : coreEngine.siteRoot + 'assets/js/lib/tinymce/tiny_mce.js?' + (new Date()).getTime(),

							plugins : "lists,inlinepopups,advimage,advlink,advlist,table,paste,media,searchreplace," +
								"print,contextmenu,fullscreen,xhtmlxtras,spellchecker,wordcount,autosave,ibrowser",
							advimage_update_dimensions_onchange: false,
							advlink_styles: "Zoom in=fancybox",
							paste_strip_class_attributes: "all",
							paste_remove_spans: true,
							paste_postprocess : function(pl, o) {
								// remove extra line breaks
								$(o.node).html($(o.node).html().replace(/<p[^>]*>\s*(<br>|&nbsp;)\s*<\/p>/ig, ""));
							},

							theme : "advanced",
							theme_advanced_buttons1: "fullscreen,|,bold,italic,underline,strikethrough,forecolor," +
								"backcolor,sub,sup,|,bullist,numlist,|,styleselect,removeformat",
							theme_advanced_buttons2: "cut,copy,paste,pastetext,|,justifyleft,justifycenter,justifyright," +
								"justifyfull,|,outdent,indent,blockquote,|,restoredraft,undo,redo,|,link,unlink,anchor," +
								"image,ibrowser,charmap,media",
							theme_advanced_buttons3: "search,replace,|,spellchecker,|,abbr,acronym,del,ins,|,cleanup," +
								"code,|,tablecontrols",

							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
							theme_advanced_background_colors: color_pallette,
							theme_advanced_text_colors : color_pallette,
							theme_advanced_more_colors : false,
							theme_advanced_styles : "Table Caption=table_caption",

							spellchecker_languages : "+English=en,Spanish=es",

							//template_external_list_url : "js/template_list.js",

							// this css's classes will become available in the styles list in the editor, if its licked to the page it will
							// allow users to quickly format text with preset styles.
							content_css : coreEngine.siteRoot + '/assets/css/screen.css?' + (new Date()).getTime(),
							body_class : "article",
							style_formats : [
								{title: 'Normal Text', block : 'p', classes: ''},
								{title: 'Subtitles ---'},
								{title: 'Large Subtitle', block : 'h2'},
								{title: 'Medium Subtitle', block : 'h3'},
								{title: 'Small Subtitle', block : 'h4'},
								{title: 'Sidebar Heading', block : 'h3'},
								{title: 'Pullquotes ---'},
								{title: 'Pullquote Right', block : 'p', classes: "pullquote pullquote_right"},
								{title: 'Pullquote Left', block : 'p', classes: "pullquote pullquote_left"},
								{title: 'Blue Pullquote Right', block : 'p', classes: "pullquote blue_pullquote_right"},
								{title: 'Blue Pullquote Left', block : 'p', classes: "pullquote blue_pullquote_left"},
								{title: 'Pullquote Signature', inline: 'span', classes: "pullquote_signature"},
								{title: 'Other ---'},
								{title: 'Dropcap', inline: 'span', classes: "dropcap"},
								{title: 'Checkbox', selector: 'p', classes: 'js-checkbox'}
							],
							verify_css_classes : true
						}
					});

					$('.edit-profile-wysiwyg').editable({
						type: 'wysiwyg',
						save: EditEngine.saveProfileElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-profile-textarea').editable({
						type: 'textarea',
						save: EditEngine.saveProfileElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-profile-inline').editable({
						type: 'textInline',
						save: EditEngine.saveProfileElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-profile-pcm').editable({
						type: 'selectInline',
						options: ['Phone','Email','Cellphone','Fax','Instant Messenger','Face to Face'],
						save: EditEngine.saveProfileElement,
						saveText: 'Save',
						cancelText: 'Cancel'
					});

					$('.edit-checkbox').checkbox({
						empty: '/assets/images/empty.png'
					}).click(function(){
						EditEngine.saveElement.call(this, String(!$(this).is(':checked')));
					});
				},
				/**
				 * This class (edit-trigger) allows you to auto trigger a save element. is useful for template conversion when
				 * the template wants to make sure that a value exists and has the default value.
				 */
				edit_auto_trigger: function(){
					$('.edit-trigger').each(function(i, elem){
						var $this = $(elem),
							content = $this.text(),
							type = $this.data('type');

						switch(type){
							case 'obj':
								EditEngine.saveElementObj.call(elem, content);
								break;
							case 'value':
								EditEngine.saveElement.call(elem, content);
								break;
							default: return true;
						}

						return true;
					});
				},
				/* Edit Article Author ---------------------------------------------------------------------- */
				edit_author: function(){
					$('#editAuthor').autocomplete({
						minLength: 2,
						source: function(request, response) {
							var myResponse = function(json){
								json.push({
									'email': null,
									'id': 'custom',
									'value': $('#editAuthor').val()
								});
								json.push({
									'email': null,
									'id': 'empty',
									'value': 'Select this to disable the by line'
								});

								response(json);
							};

							$('#editAuthor').data('selected', false);

							coreEngine.getJSON("who/search/qkey/display_name/q/" + base64.encode(request.term), "", myResponse);
						},
						select: function(event, ui){
							if (ui.item.id){
								var data = {
										'author': base64.encode(JSON.stringify(ui.item.id)),
										'author_name': base64.encode(JSON.stringify(ui.item.value))
									},
									postData = "data=" + JSON.stringify(data) + "&pid=" + coreEngine.pageID;

								coreEngine.ajax("article/update_author/" + (new Date()).getTime(), postData,
									EditEngine.saveElementCallBack, "json");
							}
						}
					});
				},
				/* manage gallery dialog and all tools code ----------------------------------------------------- */
				manage_gallery_diag: function(){
					$('#mgrGalleryDiag').dialog({
						autoOpen: false,
						resizable: false,
						width: 832,
						modal: true,
						buttons: {
							'Delete': function() {
								var images = [], postData;

								$('#imageList li.ui-selected').each(function(){
									images.push($(this).data('index'));
								});
								if (images.length === 0) {
									$.message("You must select at least 1 image to delete. Selected images will " +
										"have a different color border around them.", 'ui-state-hightlight');
								} else {
									postData = "images=" + JSON.stringify(images);
									postData += "&page_id=" + coreEngine.pageID;
									postData += "&gallery_id=" + $(this).dialog('option', 'gallery');
									//alert(postData);
									coreEngine.ajax('images/delete', postData,	EditEngine.deletedCallback);
								}
							},
							'Close': function(){
								$(this).dialog('close');
							},
							'Save': function(){
								var postData,
									imageList = [],
									$imageList;

								postData =  "page_id=" + coreEngine.pageID;
								postData += "&gallery_id=" + $('#mgrGalleryDiag').dialog('option', 'gallery');

								$imageList = $("#imageList").css("opacity", 0.3);
								EditEngine.showLoading($imageList);

								$("#imageList li").each(function(){
									var $this = $(this),
										index;

									imageList.push({
										"src": $this.data("img_id"),
										"flip": $this.data("flip"),
										"angle": $this.data("angle"),
										"old_index": parseInt($this.data("index"), 10)
									});
									index = imageList.length - 1;
									imageList[index].new_index = index;
								});
								postData += "&data=" + JSON.stringify(imageList);
								//alert(postData);
								coreEngine.ajax('images/save', postData, EditEngine.saveCallBack, 'json');
							}
						},
						open: function(){
							var gallery_id = $(this).dialog('option', 'gallery'),
								thumbSrc = coreEngine.siteRoot + 'images/preview/{1}/f/{2}/a/{3}/w/110/zc/100';

							$("#imageList").html('');

							$("#" + gallery_id + " .image a").each(function(){
								//variables for the attributes
								var $srcImg = $('img', this),
									src = this.href,
									index = $(this).data('index'),
									$toolBar, $handle, $thumb, $li, $thumbWrap, img_id;

								if ($srcImg.length === 0) { return false; }

								src = /(src|preview)\/([A-Fa-f0-9x]+)\-?[A-Fa-f0-9]*(\.[A-Za-z]{3,4})/.exec(src);
								if (src){
									img_id = src[2] + src[3];
								}else{
									img_id = '#';
								}

								//build the toolbar
								$toolBar = $("<div>").addClass('tools');
								$("<button>")
									.addClass('js-thumb-tool redo')
									.prop('title', 'Rotate right')
									.prop('id', 'cw-'+index)
									.appendTo($toolBar);
								$("<button>")
									.addClass('js-thumb-tool undo')
									.prop('title', 'Rotate left')
									.prop('id', 'ccw-'+index)
									.appendTo($toolBar);
								$("<button>")
									.addClass('js-thumb-tool refresh')
									.prop('title', 'Flip horizontally. To flip vertically rotate twice then flip.')
									.prop('id', 'flip-'+index)
									.appendTo($toolBar);
								$("<button>")
									.addClass('js-thumb-tool crop')
									.prop('title', 'Crop')
									.prop('id', 'crop-'+index)
									.appendTo($toolBar);
								$("<button>")
									.addClass('js-thumb-tool caption')
									.prop('title', 'Add a caption/by line')
									.prop('id', 'title-'+index)
									.appendTo($toolBar);
								$("<button>")
									.addClass('js-thumb-tool reset')
									.prop('title', 'Reset to original picture')
									.prop('id', 'reset-'+index)
									.appendTo($toolBar);

								//build the handle and the image
								$handle = $("<span>").addClass('g-thumb-handle ui-icon ui-icon-arrow-4');
								$thumb = $("<img>");
								$thumb.prop({
									'src': thumbSrc.format(img_id, $srcImg.data('flip'), $srcImg.data('angle')),
									'alt': $srcImg.prop('alt')
								}).data({
										'credit': $srcImg.data('credit'),
										'date': $srcImg.data('date'),
										'desc': $srcImg.data('desc')
									});
								$thumbWrap = $("<p>")
									.addClass('image')
									.append($thumb);
								//put it all together
								$li = $("<li>")
									.addClass("item")
									.append($thumbWrap)
									.append($handle)
									.append($toolBar)
									.data("img_id", img_id)
									.data("index", index)
									.data("flip", $srcImg.data('flip'))
									.data("angle", $srcImg.data('angle'));
								//append it to the list
								$("#imageList").append($li);
							});

							$('.js-thumb-tool').click(function(){
								var toolType = this.id.split("-")[0],
									$this = $(this).parent().parent(), //this will select the LI
									flip = $this.data("flip"),
									cw = (toolType === 'cw'),
									ccw = (toolType === 'ccw'),
									crop = (toolType === 'crop'),
									title = (toolType === 'title'),
									reset = (toolType === 'reset'),
									img_id = $this.data('img_id'),
									angle, $img;

								if (toolType === 'flip') { flip = !flip; }

								if (crop){
									$("#jcropDiag").dialog('option', 'index', $this.data('index')).dialog('open');
								}
								else
								if (title){
									$("#captionDiag").dialog('option', 'index', $this.data('index')).dialog('open');
								}else{
									if (reset){
										img_id = img_id.substr(0, 40) + img_id.substr(img_id.indexOf('.'));
										$this.data("img_id", img_id)
											.data("angle", 0)
											.data("flip", false);
									}else{
										angle = $this.data("angle");
										if (!angle && cw){ angle = 360; }
										if (!angle && ccw){
											if (flip) { angle = 360; }else{ angle = 0; }
										}

										if (cw) {
											angle -= 90;
										}else{
											if (ccw) { angle += 90;	}
										}

										angle %= 360;

										$this.data("angle", angle)
											.data("flip", flip);
									}

									$img = $this.find("img").css('opacity', 0.3).load(function(){
										$img.css("opacity", 1);
										$('img.loading').hide();
									});

									EditEngine.showLoading($img);

									$img.prop('src',
										thumbSrc.format($this.data('img_id'), $this.data('flip'), $this.data('angle')));
								}
							});//thumb-tool click function

							$("#imageList").sortable({items: 'li', handle: 'span'}).selectable();
						}
					});
				},
				/* cropping dialog ------------------------------------------------------------------------------ */
				crop_diag: function(){
					$("#jcropDiag").dialog({
						autoOpen: false,
						resizable: false,
						width: 832,
						modal: true,
						buttons: {
							'Fix Ratio (Slider)': function(){
								jcrop_api.setSelect([0,0,130,130]);
								jcrop_api.setOptions({ aspectRatio: 1.61206 });
								jcrop_api.focus();
							},
							'Cancel': function() {
								$(this).dialog('close');
							},
							'Save': function() {
								var postData = "gallery_id=" + $('#mgrGalleryDiag').dialog('option', 'gallery'),
									imageData = {},
									index = $(this).dialog('option', 'index'),
									$image = $("#imageList li:eq("+ index +")");

								imageData.x = $('input[name=x]', this).val();
								imageData.y = $('input[name=y]', this).val();
								imageData.w = $('input[name=w]', this).val();
								imageData.h = $('input[name=h]', this).val();
								imageData.angle = $image.data('angle');
								imageData.flip = $image.data('flip');
								imageData.img_id = $image.data('img_id');
								imageData.index = index;

								postData += "&data=" + JSON.stringify(imageData);
								//alert(postData);
								coreEngine.ajax('images/crop', postData, EditEngine.cropCallBack, 'json');
							}
						},//buttons
						open: function(){
							var index = $(this).dialog('option', 'index'),
								$img = $('<img>'),
								$li = $("#imageList li:eq("+ index +")"),
								setCoords, called;

							/***
							 * This function is an event handler for JCrop
							 * @param c object with the coordinates
							 */
							setCoords = function(c){
								$('#jcropDiag input[name=x]').val(c.x);
								$('#jcropDiag input[name=y]').val(c.y);
								$('#jcropDiag input[name=w]').val(c.w);
								$('#jcropDiag input[name=h]').val(c.h);
							};
							setCoords({
								x: 0,
								y: 0,
								w: 0,
								h: 0
							});

							$img
								.prop('src', coreEngine.siteRoot + "images/preview/" + $li.data('img_id') +
									"/f/" + $li.data('flip') + "/a/" + $li.data('angle'))
								.load(function(){
									var $this = $(this);

									if (called){ return false; }

									called = true;

									$("#jcropDiag").append($this);

									$this.Jcrop({
										onChange: setCoords,
										onSelect: setCoords,
										trueSize: [this.width, this.height],
										boxWidth: 760,
										boxHeight: 450
									}, function(){
										jcrop_api = this;
									});
								})
								.each(function(){
									if (this.complete){ $(this).trigger("load"); }
								});
						},//open function
						close: function(){
							jcrop_api.destroy();
							$("#jcropDiag").find('img, div').remove();
						}
					});
				},
				/* caption dialog -----------------------------------------------------------------------------------*/
				caption_diag: function(){
					$("#captionDiag").dialog({
						autoOpen: false,
						resizable: false,
						width: 330,
						modal: false,
						buttons: {
							'Save': function() {
								var postData = "image_id=" + parseInt($(this).dialog('option', 'index'), 10) +
									"&page_id=" + coreEngine.pageID +
									"&caption=" + base64.encode($("#imgCaption").val()) +
									"&byline=" + base64.encode($("#imgByLine").val()) +
									"&date=" + $("#imgDate").val() +
									"&desc=" + base64.encode($("#imgDesc").val()) +
									"&gallery_id=" + $('#mgrGalleryDiag').dialog('option', 'gallery');
								//alert(postData);
								coreEngine.ajax('images/addtitle', postData, EditEngine.addTitleCallback, 'json');
							},
							'Cancel': function() {
								$(this).dialog('close');
							}
						},
						'open': function() {
							var image_id = parseInt($(this).dialog('option', 'index'), 10),
								$img = $("#imageList li:eq(" + image_id + ") img");

							$("#imgUrl").val($img.prop('src'));
							$("#imgCaption").val($img.prop('alt'));
							$("#imgByLine").val($img.data('credit'));
							$("#imgDate").val($img.data('date'));
							$("#imgDesc").val($img.data('desc'));

							$("#imgDate").datepicker({dateFormat: "yy-mm-dd"}).click(function(){
								$(this).datepicker("show");
							});
						}
					});

					$("#imgDesc").tinymce({
						// Location of TinyMCE script
						script_url: coreEngine.siteRoot + 'assets/js/lib/tinymce/tiny_mce.js?' + (new Date()).getTime(),
						content_css: coreEngine.siteRoot + "assets/css/screen.css",

						theme : "advanced",
						theme_advanced_buttons1 : "fullscreen,|,bold,italic,underline,strikethrough,forecolor,backcolor,sub,sup,|,bullist,numlist,|,styleselect,removeformat",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,blockquote,|,restoredraft,undo,redo,|,link,unlink,anchor," +
								"image,ibrowser,charmap,media",
						theme_advanced_buttons3 : "search,replace,|,spellchecker,|,abbr,acronym,del,ins,|,cleanup," +
								"code,|,tablecontrols",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "none",
						theme_advanced_resizing : false,
						theme_advanced_background_colors: color_pallette,
						theme_advanced_text_colors : color_pallette,
						theme_advanced_more_colors : false,
						theme_advanced_styles : "Table Caption=table_caption",
						
						spellchecker_languages : "+English=en,Spanish=es",

						plugins : "lists,inlinepopups,advimage,advlink,advlist,table,paste,media,searchreplace," +
								"print,contextmenu,fullscreen,xhtmlxtras,spellchecker,wordcount,autosave,ibrowser",
						advimage_update_dimensions_onchange: false,
						advlink_styles: "Zoom in=fancybox",
						
						style_formats : [
								{title: 'Normal Text', block : 'p', classes: ''},
								{title: 'Subtitles ---'},
								{title: 'Large Subtitle', block : 'h2'},
								{title: 'Medium Subtitle', block : 'h3'},
								{title: 'Small Subtitle', block : 'h4'},
								{title: 'Sidebar Heading', block : 'h3'},
								{title: 'Pullquotes ---'},
								{title: 'Pullquote Right', block : 'p', classes: "pullquote pullquote_right"},
								{title: 'Pullquote Left', block : 'p', classes: "pullquote pullquote_left"},
								{title: 'Blue Pullquote Right', block : 'p', classes: "pullquote blue_pullquote_right"},
								{title: 'Blue Pullquote Left', block : 'p', classes: "pullquote blue_pullquote_left"},
								{title: 'Pullquote Signature', inline: 'span', classes: "pullquote_signature"},
								{title: 'Other ---'},
								{title: 'Dropcap', inline: 'span', classes: "dropcap"},
								{title: 'Checkbox', selector: 'p', classes: 'js-checkbox'}
							],
						verify_css_classes : true,
						paste_strip_class_attributes: "all",
						paste_remove_spans: true,	
						paste_postprocess : function(pl, o) {
							// remove extra line breaks
							o.node.innerHTML = o.node.innerHTML.replace(/<p[^>]*>\s*(<br>|&nbsp;)\s*<\/p>/ig, "");
						},

						width: 305
					});
				},
				/*Edit link list ---------------------------------------------------------------------------------------*/
				edit_links: function(className, saveClick){
					$("ul" + className).each(function(){
						var $ul = $(this),
							deleteClick = function(){
								$(this).parent().parent().remove();

								return false;
							},
							arrowUpClick = function(){
								var $li = $(this).parent().parent();
								$li.insertBefore($li.prev('li'));
								$('div', $li).hide();

								return false;
							},
							arrowDownClick = function(){
								var $li = $(this).parent().parent();
								$li.insertAfter($li.next('li'));
								$('div', $li).hide();

								return false;
							},
							editClick = function(){
								var li = $(this).parent().parent().get(0),
									index = $('li', $ul).index(li),
									$span = $('span', li),
									title = $span.data('title'),
									url = $span.data('url');

								$("#linkURL").val(url);
								$("#linkText").val(title);
								$("#addLinkDiag").dialog('option', 'editing', index).dialog('open');

								return false;
							},
						//setup each li
							setupLI = function(index, li){
								$(li).hover(function(){ $('div', this).show(); }, function(){ $('div', this).hide(); })
									.append(
									$('<div>').append(
										$('<a href="#" class="action edit-link" title="Edit">Edit<\/a>').click(editClick)
									).append(
										$('<a href="#" class="action arrow-up" title="Move Up">Up<\/a>').click(arrowUpClick)
									).append(
										$('<a href="#" class="action arrow-down" title="Move Down">Down<\/a>').click(arrowDownClick)
									).append(
										$('<a href="#" class="action delete-a" title="Delete">Delete<\/a>').click(deleteClick)
									)
								);
							};

						$('#addLinkDiag').dialog({
							autoOpen: false,
							resizable: false,
							width: 328,
							modal: false,
							buttons: {
								'Done': function() {
									var linkURL = $("#linkURL").val(),
										linkText = $("#linkText").val(),
										index;

									if ((linkURL !== '') && (linkText !== '')){
										index = $(this).dialog('option', 'editing');
										if (index === false){//we are adding
											setupLI(0, $('<li><span data-url="'+ linkURL +'" data-title="'+ linkText + '">' +
												linkText + ' &#x25ba;<\/span><\/li>').insertBefore($("button:first", $ul)));
										}else{//we are editing
											$("li:eq(" + index + ") > span", $ul)
												.data('url', linkURL)
												.data('title', linkText)
												.html(linkText + " &#x25ba;");
										}

										$(this).dialog('close');
									}
								},
								'Cancel': function() {
									$(this).dialog('close');
								}
							}
						});

						$("li", this).each(setupLI);
						//setup the button to add a new item
						$("<button>").button({label: "Add New Link"}).click(function(){
							$("#linkURL").val('http://');
							$("#linkText").val('');
							$("#addLinkDiag").dialog('option', 'editing', false).dialog('open');
						}).appendTo($(this));
						//setup button to save changes
						$("<button>").button({label: "Save Changes to Link List"}).click(function(){
							var link_data = [], //all links
								$this = $(this);

							$('li span', $this.parent()).each(function(){
								var $span = $(this),
									link_obj = {
										"url": $span.data('url'),
										"title": $span.data('title')
									};

								link_data.push(link_obj);
							});

							saveClick($this.parent().prop('id'), link_data);

						}).appendTo($(this));
					});
				},
				/* picture stacks, add buttons to upload and manage the gallery -------------------------------------- */
				edit_img: function(collection, uploadbutton){
					$(collection).each(function(){
						var $li = $("<div>").addClass("edit_img_buttons").appendTo($(this)),
							gallery_id = this.id;

						$("<button>")
							.addClass("btn-manage-gallery")
							.button({label: "Manage Gallery"})
							.click(function(){
								$('#mgrGalleryDiag').dialog('option', 'gallery', gallery_id).dialog('open');
							})
							.appendTo($li);
						$("<div>")
							.addClass(uploadbutton)
							.data("gallery_id", this.id)
							.appendTo($li);
					});
				},
				/* generic uploader for images. -----------------------------------------------------------------------------*/
				uploader: function(selector, complete){
					$(selector).each(function(){
						var uploader = new qq.FileUploader({
							element: this,
							action: coreEngine.siteRoot + 'upload.php',
							allowedExtensions: ['jpg', 'jpeg', 'bmp', 'png', 'gif', 'pdf'],
							onComplete: complete,
							debug: true
						});
					});
				},
				/* profile picture upload ------------------------------------------------------------------ */
				profile_pic_upload: function(){
					$("<div>Upload a new picture<\/div>")
						.prop('id', 'profilePictureUp')
						.button()
						.appendTo($('.edit-profile-pic').append("<br>"));

					this.uploader('#profilePictureUp', function(id, fileName, response){
						var page_id, postData;

						if (response.error){
							$.message(response.error, 'error');
						}else{
							page_id = $("img.photo").data('username');
							postData = "page_id=" + page_id + '&filename=' + response.filename;

							coreEngine.ajax('images/upload/profile/' + (new Date()).getTime(), postData,
								function(result){
									if (result.isError){
										$.message(result.errorStr, 'error');
									}else{
										//Change image on the page and reapply fancybox
										$("img.photo").prop('src', result.data);
										$("a.fancybox").fancybox({titlePosition: 'inside'});
										$.message("The new picture was saved successfully!", 'success');
									}
								}, 'json');
						}
					});
				},
				/* edit a template element that is an object */
				edit_revision_obj: function(){
					$(".edit-revision-obj").editable({
						type: 'textInline',
						save: EditEngine.saveElementObj,
						saveText: 'Save',
						cancelText: 'Cancel'
					});
				},
				saveElementObj: function(content){
					var $el = $(this),
						obj = $el.data('obj'),
						myContent = content;

					if (!myContent){
						myContent = $el.children('div').html();
					}

					obj[$el.data('value')] = myContent;

					EditEngine.saveElement.call(this, obj);
				},
				saveElement: function(content){
					var key = $(this).data('key'),
						myContent = content,
						data = {},
						postData;

					if (!myContent){
						myContent = $(this).children('div').html();
					}

					data[key] = base64.encode(JSON.stringify(myContent));

					postData = 'data=' + JSON.stringify(data);
					postData += "&pid=" + coreEngine.pageID;

					return coreEngine.ajax("article/save", postData, EditEngine.saveElementCallBack);
				},
				updatePage: function(){
					var key = $(this).data('key'),
						postData = "",
						data = {};

					data[key] = $(this).children('div').html();

					postData += "pid=" + coreEngine.pageID;
					postData += "&data=" + JSON.stringify(data);

					coreEngine.ajax("properties/updatepage/", postData, EditEngine.saveElementCallBack);
				},
				changePageTitle: function(){
					var	postData;

					postData = "pid=" + coreEngine.pageID;
					postData += "&title=" + base64.encode($(this).children('div').html());

					coreEngine.ajax("article/change_page_title/", postData, EditEngine.saveElementCallBack);
				},
				saveProfileElement: function(){
					var key = $(this).data('key'),
						content = $(this).children('div').html(),
						postData = "content=" + base64.encode(content) +
							"&uid=" + coreEngine.pageID.match(/\d+/)[0] +
							"&element=" + key;

					coreEngine.ajax("who/saveprofile/", postData, EditEngine.saveElementCallBack);
				},
				saveElementCallBack: function(result){
					if(result.isError){
						$.message(result.errorStr, 'error');
					}else{
						$.message("Saved successfully!", 'success');
					}
				},
				cropCallBack: function(result){
					var selector, $li;

					if(result.isError){
						$.message(result.errorStr, 'error');
					}else{
						selector = "#" + result.data.gallery_id;
						$li = $("#imageList li:eq("+ result.data.index +")");

						$(selector + " li:eq("+ result.data.index +") a").prop('href', result.data.src);
						$(selector + " li:eq("+ result.data.index +") a img").prop('src', result.data.src + "?" + (new Date()).getTime());

						$("img", $li).prop('src', result.data.src + "?" + (new Date()).getTime());
						$li
							.data('img_id', result.data.img_id)
							.data('flip', false)
							.data('angle', 0);

						$("#jcropDiag").dialog('close');
					}
				},
				saveCallBack: function(result){
					var i, selector, $caption, $img, imgUrl, imgData,
						fImgUrl = coreEngine.siteRoot + "images/preview/{1}/f/{2}/a/{3}";

					if(result.isError){
						$.message(result.errorStr, 'error');
					}else{
						if (result.data !== ''){
							selector = "#" + result.data.gallery_id;

							for (i = 0; i < result.data.images.length; i += 1){

								imgData = result.data.images[i];
								imgUrl = fImgUrl.format(imgData.src, imgData.flip, imgData.angle);

								//update the anchor
								$(selector + " li:eq("+ i +") a").prop('href', imgUrl);

								//update the image itself
								$img = $(selector + " li:eq("+ i +") img");
								$img.attr('src', imgUrl + '?' + (new Date()).getTime())
									.prop('alt', imgData.alt)
									.data('credit', imgData.credit)
									.data('desc', imgData.desc)
									.data('flip', imgData.flip)
									.data('angle', imgData.angle)

									//remove image attributes
									.removeAttr('data-desc')
									.removeAttr('data-credit')
									.removeAttr('data-flip')
									.removeAttr('data-angle')
									.removeAttr('width');

								//update the caption and credits
								$caption = $(selector + " li:eq("+ i +") a + div");
								$('.picture-caption', $caption).html(imgData.alt);
								$('.picture-credits', $caption).text('Photo By: ' + imgData.credit);
							}
						}

						$('#mgrGalleryDiag').dialog('close');
					}

					$("#imageList").css("opacity", 1);
					$("img.loading").hide();
				},
				deletedCallback: function(result){
					var i;

					if(result.isError){
						$.message(result.errorStr, 'error');
					}else{
						//delete images from the dialog and the page
						for (i = 0; i < result.data.images.length; i += 1){
							$("#imageList li:eq("+ result.data.images[i] +")").addClass('js-delete');
							$("#" + result.data.gallery_id + " li:eq("+ result.data.images[i] +")").addClass('js-delete');
						}

						$("#imageList .js-delete, #" + result.data.gallery_id + " js-delete").remove();

						//reindex dialog
						$("#imageList li").each(function(i){
							$(this).data('index', i);
						});

						//reindex page
						$("#" + result.data.gallery_id + " a").each(function(i){
							$(this).data('index', i);
						});
					}
				},
				addTitleCallback: function(result){
					if(result.isError){
						$.message(result.errorStr, 'error');
					}else{
						//alert(result.data.new_src);
						$("#imageList li:eq("+ result.data.image_id +") img")
							.prop('alt', result.data.alt)
							.data('credit', result.data.credit)
							.data('date', result.data.date)
							.data('desc', result.data.desc);
						$("#" + result.data.gallery_id + " li:eq("+ result.data.image_id +") img")
							.prop('alt', result.data.alt)
							.data('credit', result.data.credit)
							.data('date', result.data.date)
							.data('desc', result.data.desc);

						$("#captionDiag").dialog('close');
					}
				},
				showLoading: function($obj){
					var pos = $obj.offset();
					$('img.loading')
						.css('top', pos.top + ($obj.height() / 2) - 15.5)
						.css('left', pos.left + ($obj.width() / 2) - 15.5)
						.show();
				},
				publishToDropdown: function(){
					$('.publish-select').multiselect({
						selectedList: 2,
						noneSelectedText: "Select sections",
						header: "",
						close: function(){
							var postData, data = {},
								getSections = function(){
									var $option = $(this);

									if ($option.is(':selected')){
										data.sections.push($option.val());
									}
								};

							data.sections = [];
							$('option', this).each(getSections);
							data.published = data.sections.length ? 1 : 0;

							postData = "pid=" + coreEngine.pageID;
							postData += "&data=" + JSON.stringify(data);
							coreEngine.ajax("properties/updatepage/", postData, coreEngine.genericCallBack);
						},
						open: function(){
							$('.ui-multiselect-filter input').focus();
						}
					}).multiselectfilter();
				}
			};

		$(document).ready(function(){
			EditEngine.setup_edit_in_place();
			EditEngine.edit_author();
			EditEngine.edit_revision_obj();
			EditEngine.edit_auto_trigger();
			EditEngine.manage_gallery_diag();
			EditEngine.publishToDropdown();
			//Uploader for profiles
			EditEngine.profile_pic_upload();
			//uploader for articles
			EditEngine.edit_img('.edit_img', 'btn-upload-image');
			EditEngine.uploader('.btn-upload-image', function(id, fileName, response){
				var postData;

				if (response.error){
					$.message(response.error, 'error');
				}else{
					postData = "page_id=" + coreEngine.pageID +
						'&gallery_id=' + $(this.element).data("gallery_id") +
						'&filename=' + response.filename +
						'&orig_filename=' + response.orig_filename;

					coreEngine.ajax(
						'images/upload/page/' + (new Date()).getTime(),
						postData, coreEngine.imageUploadCallback, 'json'
					);
				}
			});
			EditEngine.crop_diag();
			EditEngine.caption_diag();
			EditEngine.edit_links('.edit_links', function(key, content){
				var data = {},
					postData;

				data[key] = base64.encode(JSON.stringify(content));

				postData = 'data=' + JSON.stringify(data);
				postData += "&pid=" + coreEngine.pageID;

				return coreEngine.ajax("article/save", postData, EditEngine.saveElementCallBack);
			});

			EditEngine.edit_links('.edit_who_links', function(id, link_data){
				var postData = 'data=' + base64.encode(JSON.stringify(link_data));
				coreEngine.ajax("who/updatemylinks/", postData, coreEngine.genericCallBack);
			});
		});//end document.ready function
	});
});
