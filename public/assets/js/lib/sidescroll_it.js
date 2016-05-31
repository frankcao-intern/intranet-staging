// JavaScript Document
/*
 * jQuery WidowFix Plugin
 * http://matthewlein.com/widowfix/
 * Copyright (c) 2010 Matthew Lein
 * Version: 1.3.2 (7/23/2011)
 * Dual licensed under the MIT and GPL licenses
 * Requires: jQuery v1.4 or later
 */

(function(a){jQuery.fn.widowFix=function(d){var c={letterLimit:null,prevLimit:null,linkFix:false,dashes:false};var b=a.extend(c,d);if(this.length){return this.each(function(){var i=a(this);var n;if(b.linkFix){var h=i.find("a:last");h.wrap("<var>");var e=a("var").html();n=h.contents()[0];h.contents().unwrap()}var f=a(this).html().split(" "),m=f.pop();if(f.length<=1){return}function k(){if(m===""){m=f.pop();k()}}k();if(b.dashes){var j=["-","–","—"];a.each(j,function(o,p){if(m.indexOf(p)>0){m='<span style="white-space:nowrap;">'+m+"</span>";return false}})}var l=f[f.length-1];if(b.linkFix){if(b.letterLimit!==null&&n.length>=b.letterLimit){i.find("var").each(function(){a(this).contents().replaceWith(e);a(this).contents().unwrap()});return}else{if(b.prevLimit!==null&&l.length>=b.prevLimit){i.find("var").each(function(){a(this).contents().replaceWith(e);a(this).contents().unwrap()});return}}}else{if(b.letterLimit!==null&&m.length>=b.letterLimit){return}else{if(b.prevLimit!==null&&l.length>=b.prevLimit){return}}}var g=f.join(" ")+"&nbsp;"+m;i.html(g);if(b.linkFix){i.find("var").each(function(){a(this).contents().replaceWith(e);a(this).contents().unwrap()})}})}}})(jQuery);


(function($){
	//scroll load


var mobile = false;
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	mobile = true;
}	
var isflash = false;	
track = new analytics();



function hoverButtons() {
	//loop through .hoverBtn class
	$(".hoverBtn").each(function() {
		//setup the interactions	
		var mouseEnter = function() {
			$(this).removeClass("closed");
			$(this).find('p').stop().delay(100).animate({bottom:0}, 400);		
		};
		var mouseLeave = function() {
			$(this).addClass("closed");
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-22;
			$(shade).stop().animate({bottom:pheight}, 200);	
		}
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1);
			
			//open up
			if ($(shade).hasClass("closed")) {
				$(shade).addClass("up");
				$(shade).stop().animate({bottom:"0px"}, 400, function() {
					$(this).delay(300).show(400, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			
			//close down
			if ($(shade).hasClass("open")) {
				$(shade).removeClass("up");
				$(shade).stop().animate({bottom:pheight+"px"}, 200, function() {
					$(this).delay(300).show(400, function() {
						$(this).addClass("closed");
						$(this).removeClass("open");
						$(this).parent().bind('tap', mobileTap );
					});
				});
			}
		}
		//bind the events
		if(mobile == true) {
            if ($(this).hasClass('mobileOff')) {
                var shade = $(this).find('p');
                var pheight = (($(shade).height())*-1);
                $(shade).css('bottom', pheight);    
            } else {
                //$(this).bind('tap', mobileTap );
                var shade = $(this).find('p');
                $(shade).css('bottom', 0);
                $(shade).addClass("mobilearrow open up");
            }

		} else {
            if ($(this).hasClass('mobileOff')) {
                var shade = $(this).find('p');
                $(shade).css('display', 'block');
            }
			var shade = $(this).find('p');
			$(shade).css('bottom', 0);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
		}
	
	});	
}

function scrollButtons() {
	//loop through scrollBtns
	
	
   $(".scrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".scrollBtn").each(function() {
				if ($(".scrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".scrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".scrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".scrollBtn").each(function() {
				  if ($(".scrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".scrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".scrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});
   
	//infrastructure scroll button functionality
      $(".infraScrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".infraScrollBtn").each(function() {
				if ($(".infraScrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".infraScrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".infraScrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".infraScrollBtn").each(function() {
				  if ($(".infraScrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".infraScrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".infraScrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});
      
      	//wholesale business application team scroll button functionality
      $(".wbaScrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".wbaScrollBtn").each(function() {
				if ($(".wbaScrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".wbaScrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".wbaScrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".wbaScrollBtn").each(function() {
				  if ($(".wbaScrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".wbaScrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".wbaScrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});


	//retail scroll button functionality
      $(".retailScrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".retailScrollBtn").each(function() {
				if ($(".retailScrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".retailScrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".retailScrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".retailScrollBtn").each(function() {
				  if ($(".retailScrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".retailScrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".retailScrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});
	
	//web technology scroll button functionality
      $(".webScrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".webScrollBtn").each(function() {
				if ($(".webScrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".webScrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".webScrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".webScrollBtn").each(function() {
				  if ($(".webScrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".webScrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".webScrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});
      
      	
	//plm scroll button functionality
      $(".plmScrollBtn").each(function() {
		var pheight = (($(this).find("p").height())*-1)-22;
   		$(this).find("p").css("bottom", pheight).hide();
		//$(this).find("p").widowFix();
		var url = $(this).find("h3 a").attr("href");
		if (url == null || url == "" || url == "undefined") {
			$(this).addClass("noLink");
		}
		//setup the interactions
		var scrollClick = function() {
			var url = $(this).find("h3 a").attr("href");
			if (url == null || url == "" || url == "undefined") {
				//console.log("nothing to see here");
			} else {
				window.location = url;
			}
		}
		var mouseEnter = function() {
			$(this).addClass('active');
			$(this).unbind('mouseenter');
			//check if anything is open
			$(".plmScrollBtn").each(function() {
				if ($(".plmScrollBtn").hasClass('active')) {
					//ignore current
				} else {
					//hide rest
					var shade = $(this).find('p');
					$(".plmScrollBtn").find("h3").stop().fadeTo(300, 1);
					$(".plmScrollBtn").find("h4").stop().fadeTo(300, 1);
					var pheight = (($(shade).height())*-1)-23;
					$(shade).stop().animate({bottom:pheight}, 100);
				}
			});
			//set current
			$(this).find("h3").stop().fadeTo(300, 0);
			$(this).find("h4").stop().fadeTo(300, 0);
			$(this).find('p').stop().delay(100).show().animate({bottom:0}, 300);
		};
		var mouseLeave = function() {
			$(this).removeClass('active');
			var shade = $(this).find('p');
			$(this).find("h3").stop().delay(100).fadeTo(300, 1);
			$(this).find("h4").stop().delay(100).fadeTo(300, 1);
			var pheight = (($(shade).height())*-1)-23;
			$(shade).stop().animate({bottom:pheight}, 100, function() {
				$(this).parent().bind('mouseenter', mouseEnter);				
			});
			
		};
		var mobileTap = function() {
			$(this).unbind('tap');
			var shade = $(this).find('p');
			var pheight = (($(shade).height())*-1)-23;
			if ($(shade).hasClass("closed")) {
				//loop through all scrolls and close any open ones
				$(".plmScrollBtn").each(function() {
				  if ($(".plmScrollBtn").find('p').hasClass('open')) {
					  var shade2 = $(this).find('p');
					  $(".plmScrollBtn").find("h3").stop().fadeTo(300, 1);
					  $(".plmScrollBtn").find("h4").stop().fadeTo(300, 1);
					  var pheight2 = (($(shade2).height())*-1)-23;
					  $(shade2).stop().animate({bottom:pheight2+"px"}, 100);
				  }
				});
				
				$(this).find("h3").stop().fadeTo(300, 0);
				$(this).find("h4").stop().fadeTo(300, 0);				
				$(shade).stop().animate({bottom:"0px"}, 300, function() {
					$(this).delay(300).show(300, function() {
						$(this).addClass("open");
						$(this).removeClass("closed");
						$(this).parent().bind('tap', mobileTap );
					});			
				});
			}
			if ($(shade).hasClass("open")) {
				var url = $(this).find("h3 a").attr("href");
				if (url == null || url == "" || url == "undefined") {
					if(mobile == true) {
						$(this).find("h3").stop().delay(100).fadeTo(300, 1);
						$(this).find("h4").stop().delay(100).fadeTo(300, 1);
						$(shade).stop().animate({bottom:pheight+"px"}, 100, function() {
							$(this).delay(300).show(300, function() {
								$(this).addClass("closed");
								$(this).removeClass("open");
								$(this).parent().bind('tap', mobileTap );
							});
						});					
					}
				} else {
					window.location = url;
				}
			}
		}
		//bind the events
		if(mobile == true) {
			$(this).bind('tap', mobileTap );
			$(this).find("p").addClass("closed");
		} else {
			var pheight = (($(this).find('p').height())*-1)-22;
			$(this).find('p').css('bottom', pheight);
			$(this).bind('mouseenter', mouseEnter);
			$(this).bind('mouseleave', mouseLeave);
			$(this).bind('click', scrollClick);
		}
		});
	
}

//sidebar scroller
function setScroll() {
	var startNum = 0,
	listNum = $('#sideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#tipsterContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#tipsterSideScroll').css("height", contentHeight+"px");
	$('#scrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#sideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#sideScrollItems>li:first').height()+60;
	  $('#sideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#sideScrollItems').css("bottom", "");
		  $('#sideScrollItems').css("top", 0);
		  $('#sideScrollItems>li:first').appendTo('#sideScrollItems');
		  //$('#sideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#sideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#sideScrollItems>li:first').height()+60;
	  $('#sideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#sideScrollItems').css("bottom", "");
		  $('#sideScrollItems').css("top", 0);
		  $('#sideScrollItems>li:first').appendTo('#sideScrollItems');
		  //$('#sideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#sideArrowDown").bind('click', downClick);	
	
}

function infraSetScroll() {
	var startNum = 0,
	listNum = $('#infraSideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#infraContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#infraSideScroll').css("height", contentHeight+"px");
	$('#infraScrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#infraSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#infraSideScrollItems>li:first').height()+60;
	  $('#infraSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#infraSideScrollItems').css("bottom", "");
		  $('#infraSideScrollItems').css("top", 0);
		  $('#infraSideScrollItems>li:first').appendTo('#infraSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#infraSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#infraSideScrollItems>li:first').height()+60;
	  $('#infraSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#infraSideScrollItems').css("bottom", "");
		  $('#infraSideScrollItems').css("top", 0);
		  $('#infraSideScrollItems>li:first').appendTo('#infraSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#infraSideArrowDown").bind('click', downClick);	
	
}

function wbaSetScroll() {
	var startNum = 0,
	listNum = $('#wbaSideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#wbaContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#wbaSideScroll').css("height", contentHeight+"px");
	$('#wbaScrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#wbaSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#wbaSideScrollItems>li:first').height()+60;
	  $('#wbaSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#wbaSideScrollItems').css("bottom", "");
		  $('#wbaSideScrollItems').css("top", 0);
		  $('#wbaSideScrollItems>li:first').appendTo('#wbaSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#wbaSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#wbaSideScrollItems>li:first').height()+60;
	  $('#wbaSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#wbaSideScrollItems').css("bottom", "");
		  $('#wbaSideScrollItems').css("top", 0);
		  $('#wbaSideScrollItems>li:first').appendTo('#wbaSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#wbaSideArrowDown").bind('click', downClick);	
	
}

function retailSetScroll() {
	var startNum = 0,
	listNum = $('#retailSideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#retailContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#retailSideScroll').css("height", contentHeight+"px");
	$('#retailScrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#retailSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#retailSideScrollItems>li:first').height()+60;
	  $('#retailSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#retailSideScrollItems').css("bottom", "");
		  $('#retailSideScrollItems').css("top", 0);
		  $('#retailSideScrollItems>li:first').appendTo('#retailSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#retailSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#retailSideScrollItems>li:first').height()+60;
	  $('#retailSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#retailSideScrollItems').css("bottom", "");
		  $('#retailSideScrollItems').css("top", 0);
		  $('#retailSideScrollItems>li:first').appendTo('#retailSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#retailSideArrowDown").bind('click', downClick);	
	
}

function webSetScroll() {
	var startNum = 0,
	listNum = $('#webSideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#webContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#webSideScroll').css("height", contentHeight+"px");
	$('#webScrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#webSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#webSideScrollItems>li:first').height()+60;
	  $('#webSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#webSideScrollItems').css("bottom", "");
		  $('#webSideScrollItems').css("top", 0);
		  $('#webSideScrollItems>li:first').appendTo('#webSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#webSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#webSideScrollItems>li:first').height()+60;
	  $('#webSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#webSideScrollItems').css("bottom", "");
		  $('#webSideScrollItems').css("top", 0);
		  $('#webSideScrollItems>li:first').appendTo('#webSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#webSideArrowDown").bind('click', downClick);	
	
}

function plmSetScroll() {
	var startNum = 0,
	listNum = $('#plmSideScrollItems li').length;
	//check slide num
	var contentHeight = ($('#plmContent').height())-15;
	var scrollHeight = contentHeight-75;
	$('#plmSideScroll').css("height", contentHeight+"px");
	$('#plmScrollWrapper').css("height", scrollHeight+"px");
	
	//up button
	function upClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Up Arrow", "Click");
	 $("#plmSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#plmSideScrollItems>li:first').height()+60;
	  $('#plmSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#plmSideScrollItems').css("bottom", "");
		  $('#plmSideScrollItems').css("top", 0);
		  $('#plmSideScrollItems>li:first').appendTo('#plmSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//down button
	function downClick() {
	 $("#sideArrowUp").unbind();
	 track.eventTrack("Sidebar Down Arrow", "Click");
	 $("#plmSideScrollItems>li").each(function() {
		 $(this).unbind();		 
	 });
	  startNum++;
	  if(startNum >= listNum) {
		  startNum = 0;
	  }
	  var liHeight = $('#plmSideScrollItems>li:first').height()+60;
	  $('#plmSideScrollItems').animate({top:'-'+liHeight+'px'}, 400, function() {
		  $('#plmSideScrollItems').css("bottom", "");
		  $('#plmSideScrollItems').css("top", 0);
		  $('#plmSideScrollItems>li:first').appendTo('#plmSideScrollItems');
		  //$('#infraSideScrollItems>li:first').find("p").widowFix();
		  $("#sideArrowUp").bind('click', upClick);
		  scrollButtons();
		  
	  });	
	};
	
	//bind the events
	$("#sideArrowUp").bind('click', upClick);
	$("#plmSideArrowDown").bind('click', downClick);	
	
}
function analytics() {
	
	this.eventTrack = function($itemID, $eventType)
		{
			eventType = $eventType;
			itemID = $itemID;
			
			//console.log('Tracking Event: '+ eventType + ' : '+itemID);
		}	
};

$(window).load(function(){
	if (document.getElementById("tipster")) {
		setScroll();		  
	}
	if (document.getElementById("infrastructure")){
		infraSetScroll();
	}
	if (document.getElementById("wholesale")){
		wbaSetScroll();
	}
	if (document.getElementById("retail")) {
		retailSetScroll();
	}
	if (document.getElementById("web")) {
		webSetScroll();
	}
	if (document.getElementById("plm")) {
		plmSetScroll();
	}	
	hoverButtons();
	scrollButtons();
	 
 });
 
 

})(jQuery);