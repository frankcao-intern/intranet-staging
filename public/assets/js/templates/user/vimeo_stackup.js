/**
 * Created by: cravelo
 * Date: 2/10/12
 * Time: 10:49 AM
 */

/*jslint browser: true, white: true */
/*global base64, coreEngine, jQuery */

(function ($) {
	"use strict";

	var Engine = {
		secondsToHms: function(d) {
			var seconds = Number(d),
				h = Math.floor(seconds / 3600),
				m = Math.floor(seconds % 3600 / 60),
				s = Math.floor(seconds % 3600 % 60);

			//noinspection NestedConditionalExpressionJS
			return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") +
						(s < 10 ? "0" : "") + s);
		},
		videoURL: function(){
			$('#videoURL').delegate('input', 'change', function(){
				var vimeoID = this.value.replace(/[^\d]+/, '');

				coreEngine.getJSON('proxy/getvimeovideo/'+ vimeoID, '', function(result){
					var videoData = result || false,
						postData, duration, title;

					if (videoData){
						duration = Engine.secondsToHms(videoData.duration);
						title = base64.encode(videoData.title + ' (' + duration + ')');

						//save the title
						postData = 'pid=' + coreEngine.pageID;
						postData +='&data=' + JSON.stringify({"title": title});
						coreEngine.ajax('properties/updatepage/' + (new Date()).getTime(),
							postData, function(result){
								if (result.isError){
									$.message(result.errorStr, 'error');
								}else{
									$("#title").text(videoData.title + ' (' + duration + ')');
								}
							}, 'json');

						postData = 'pid=' + coreEngine.pageID;
						postData +='&data=' + JSON.stringify({
							"article": base64.encode(JSON.stringify(videoData.description))
						});

						coreEngine.ajax('article/save',	postData, function(result){
								if (result.isError){
									$.message(result.errorStr, 'error');
								}else{
									$('.article').text(videoData.description);
								}
							});

						postData = 'page_id=' + coreEngine.pageID;
						postData +='&gallery_id=main_image';
						postData +='&filename=' + videoData.thumb;
						coreEngine.ajax('images/upload/page/' + (new Date()).getTime(),
							postData, coreEngine.imageUploadCallback, 'json');
					}
				});
			});
		}
	};

	$(document).ready(function(){
		Engine.videoURL();
	});
}(jQuery));
