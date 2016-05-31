/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 11:42 AM
 */

/*global require, coreEngine, base64*/
/*jslint browser: true, white: true, nomen: true*/

(function(w, require){
	"use strict";

	require(['jquery'], function($){

		var requirements = ['jquery', 'lib/jquery.isotope'];

		//if (coreEngine.canWrite === true){ requirements.push('lib/jquery.myeditable'); }

		if ($('html').hasClass('oldie') && (coreEngine.edit_mode === false)){
			requirements.push('lib/jquery.columnizer');
		}

		require(requirements, function(){
			var Engine = {
				$photos: $('#photos'),
				_editing: {
					editPicture: function(){
						var $title = $('.fancybox-title');

						if ($.editable){
							$('h3, .credits span', $title).editable({
								type: 'textInline',
								save: Engine._editing.saveCaption,
								saveText: 'Save',
								cancelText: 'Cancel',
								showLink: false
							});
						}
					},
					saveCaption: function(){
						var	$context = $(this).closest('.fancybox-title'),
							$data = $('.js-data', $context),
							$caption = $('h3', $context),
							$credits = $('.credits span', $context),
							postData;

							postData = "image_id=" + $data.data('img_id') +
								"&page_id=" + coreEngine.pageID +
								"&gallery_id=" + $data.data('gallery_id') +
								"&caption=" + base64.encode($caption.text()) +
								"&byline=" + base64.encode($credits.text());

						//w.alert(postData);
						coreEngine.ajax('images/addtitle', postData, Engine._editing.addTitleCallback, 'json');
					},
					addTitleCallback: function(result){
						var $item;

						if(result.isError){
							$.message(result.errorStr, 'error');
						}else{
							//alert(result.data.new_src);
							$item = $("#photos").find(".item:eq("+ (result.data.image_id - 1) +")");

							$('h3', $item).text(result.data.alt);
							$('.credits span', $item).text(result.data.credit);

							$.message(result.errorStr, 'success');
						}
					}
				},
				fancybox: function(){
					$('a', this.$photos).fancybox({
						type: 'image',
						nextEffect: 'fade',
						prevEffect: 'fade',
						beforeShow: function() {
							var index = $(this.element).closest('.item').index() + 1;

							this.title = $('#img' + index).html();
						},
						afterShow: this._editing.editPicture,
						helpers : {
							title : {
								type: 'inside'
							}
						}
					});
				},
				isotope: function(){
					var $images = $('.item', this.$photos),
						factor = 1.35;

					//this.$photos.imagesLoaded(function(){
						this.$photos.isotope({
							itemSelector : '.item',
							masonry: {
								columnWidth: 190
							}
						});
					//});

					$('body').scrollTop(0);

					//zoom
					$images.each(function () {
							//original dimensions of image
						var od = { width: this.offsetWidth, height: this.offsetHeight },
							//enlarged dimensions of image
							nd = { width: Math.ceil(od.width * factor), height: Math.ceil(od.height * factor)},
							//original coords
							ocoords = { x: /\d+/.exec($(this).css('left'))[0], y: /\d+/.exec($(this).css('top'))[0] },
							//coords to move enlarged image to
							ncoords = { x: ocoords.x - (nd.width - od.width) / 2, y: ocoords.y - (nd.height - od.height) / 2};

						$(this).data("specs", { //cache image specs
							od: od,
							nd: nd,
							ocoords: ocoords,
							ncoords: ncoords
						});
					});

					$images.off('mouseover').on('mouseover', function(){
						var $this = $(this),
							specs = $this.data('specs');

						$this
							.css('z-index', 10000)
							.find('img')
							.andSelf()
							.stop(true)
							.animate({
								'width': specs.nd.width,
								'height': specs.nd.height,
								'left': specs.ncoords.x,
								'top': specs.ncoords.y
							}, {
								speed: 'fast',
								easing: 'easeOutQuint',
								queue: false
							});

						if ($.trim($('h3', $this).text()) !== ''){
							$('.caption', $this)
								.show()
								.stop(true)
								.animate({
									opacity: 1
								}, {
									speed: 'fast',
									easing: 'easeOutQuint',
									queue: false
								});
						}
					}).off('mouseleave').on('mouseleave', function () {
							var $this = $(this),
								specs = $this.data('specs');

							$('.caption', $this)
								.stop()
								.animate({
									opacity: 0
								}, {
									speed: 'fast',
									easing: 'easeOutQuint',
									queue: false
								});

							$this
								.find('img')
								.andSelf()
								.stop()
								.css('z-index', 1)
								.animate({
									'width': specs.od.width,
									'height': specs.od.height,
									'left': specs.ocoords.x,
									'top': specs.ocoords.y
								}, {
									speed: 'fast',
									easing: 'easeOutQuint',
									queue: false
								});
						}).on('click', function(){
							$(this).trigger('mouseleave');
						});
				},
				columnize : function(){
					var $context, $tmp, numColumns;

					if ( $(".ui-tabs-nav").length > 0 ){ //in case this page is inside a tab target the right containers
						$context = $($(".ui-tabs-nav .ui-state-active a").attr('href'));
					}else{
						$context = $('div.fancy-masonry');
					}
					$tmp = $('.article', $context);
					numColumns = w.parseInt($('.page-columns', $context).text(), 10);

					if ($tmp.columnize){//check if the function exists, in edit mode this should be false.
						if (numColumns > 1){
							$tmp.columnize({ columns: numColumns });
							$(".column", $context).width(Math.floor($tmp.width() / numColumns - (21 - 21/numColumns)));
						}
					}else{ //columnize is only active out of edit mode on IE
						numColumns = String(numColumns);

						$tmp.css({
							'-webkit-column-count': numColumns,
							'-webkit-column-rule': '1px dotted #BBBBBB',
							'-webkit-column-gap': '24px',
							'-moz-column-count': numColumns,
							'-moz-column-rule': '1px dotted #BBBBBB',
							'-moz-column-gap': '24px',
							'column-count': numColumns,
							'column-rule': '1px dotted #BBBBBB',
							'column-gap': '24px'
						});
					}
				}
			};

			$(document).ready(function(){
				Engine.fancybox();
				Engine.isotope();
				Engine.columnize();
			});
		});
	});
}(window, require));
