
require(['jquery'],function($){'use strict';var $context,$tmp,numColumns;if($(".ui-tabs-nav").length>0){$context=$($(".ui-tabs-nav .ui-state-active a").attr('href'));}else{$context=$('.text-only');}
$tmp=$('.article',$context);numColumns=window.parseInt($('#pageColumns',$context).text());if($('html').hasClass('oldie')&&(coreEngine.edit_mode===false)&&(numColumns>1)){require(['lib/jquery.columnizer'],function(){$tmp.columnize({columns:numColumns});$(".column",$context).width(Math.floor($tmp.width()/numColumns-(21-21/numColumns)));});}else{numColumns=String(numColumns);$tmp.css({'-webkit-column-count':numColumns,'-webkit-column-rule':'1px dotted #BBBBBB','-webkit-column-gap':'24px','-moz-column-count':numColumns,'-moz-column-rule':'1px dotted #BBBBBB','-moz-column-gap':'24px','column-count':numColumns,'column-rule':'1px dotted #BBBBBB','column-gap':'24px'});}});