
(function(factory){"use strict";if(typeof define==='function'&&define.amd){define(['jquery'],factory);}
else if(jQuery&&!jQuery.fn.qtip){factory(jQuery);}}
(function($){"use strict";var TRUE=true,FALSE=false,NULL=null,X='x',Y='y',WIDTH='width',HEIGHT='height',TOP='top',LEFT='left',BOTTOM='bottom',RIGHT='right',CENTER='center',FLIP='flip',FLIPINVERT='flipinvert',SHIFT='shift',QTIP,PLUGINS,MOUSE,usedIDs={},uitooltip='ui-tooltip',widget='ui-widget',disabled='ui-state-disabled',selector='div.qtip.'+uitooltip,defaultClass=uitooltip+'-default',focusClass=uitooltip+'-focus',hoverClass=uitooltip+'-hover',fluidClass=uitooltip+'-fluid',hideOffset='-31000px',replaceSuffix='_replacedByqTip',oldtitle='oldtitle',trackingBound;function log(){log.history=log.history||[];log.history.push(arguments);if('object'===typeof console){var c=console[console.warn?'warn':'log'],args=Array.prototype.slice.call(arguments),a;if(typeof arguments[0]==='string'){args[0]='qTip2: '+args[0];}
a=c.apply?c.apply(console,args):c(args);}}
function sanitizeOptions(opts)
{var content;if(!opts||'object'!==typeof opts){return FALSE;}
if(opts.metadata===NULL||'object'!==typeof opts.metadata){opts.metadata={type:opts.metadata};}
if('content'in opts){if(opts.content===NULL||'object'!==typeof opts.content||opts.content.jquery){opts.content={text:opts.content};}
content=opts.content.text||FALSE;if(!$.isFunction(content)&&((!content&&!content.attr)||content.length<1||('object'===typeof content&&!content.jquery))){opts.content.text=FALSE;}
if('title'in opts.content){if(opts.content.title===NULL||'object'!==typeof opts.content.title){opts.content.title={text:opts.content.title};}
content=opts.content.title.text||FALSE;if(!$.isFunction(content)&&((!content&&!content.attr)||content.length<1||('object'===typeof content&&!content.jquery))){opts.content.title.text=FALSE;}}}
if('position'in opts){if(opts.position===NULL||'object'!==typeof opts.position){opts.position={my:opts.position,at:opts.position};}}
if('show'in opts){if(opts.show===NULL||'object'!==typeof opts.show){if(opts.show.jquery){opts.show={target:opts.show};}
else{opts.show={event:opts.show};}}}
if('hide'in opts){if(opts.hide===NULL||'object'!==typeof opts.hide){if(opts.hide.jquery){opts.hide={target:opts.hide};}
else{opts.hide={event:opts.hide};}}}
if('style'in opts){if(opts.style===NULL||'object'!==typeof opts.style){opts.style={classes:opts.style};}}
$.each(PLUGINS,function(){if(this.sanitize){this.sanitize(opts);}});return opts;}
function QTip(target,options,id,attr)
{var self=this,docBody=document.body,tooltipID=uitooltip+'-'+id,isPositioning=0,isDrawing=0,tooltip=$(),namespace='.qtip-'+id,elements,cache;self.id=id;self.rendered=FALSE;self.destroyed=FALSE;self.elements=elements={target:target};self.timers={img:{}};self.options=options;self.checks={};self.plugins={};self.cache=cache={event:{},target:$(),disabled:FALSE,attr:attr,onTarget:FALSE,lastClass:''};function convertNotation(notation)
{var i=0,obj,option=options,levels=notation.split('.');while(option=option[levels[i++]]){if(i<levels.length){obj=option;}}
return[obj||options,levels.pop()];}
function setWidget(){var on=options.style.widget;tooltip.toggleClass(widget,on).toggleClass(defaultClass,options.style.def&&!on);elements.content.toggleClass(widget+'-content',on);if(elements.titlebar){elements.titlebar.toggleClass(widget+'-header',on);}
if(elements.button){elements.button.toggleClass(uitooltip+'-icon',!on);}}
function removeTitle(reposition)
{if(elements.title){elements.titlebar.remove();elements.titlebar=elements.title=elements.button=NULL;if(reposition!==FALSE){self.reposition();}}}
function createButton()
{var button=options.content.title.button,isString=typeof button==='string',close=isString?button:'Close tooltip';if(elements.button){elements.button.remove();}
if(button.jquery){elements.button=button;}
else{elements.button=$('<a />',{'class':'ui-state-default ui-tooltip-close '+(options.style.widget?'':uitooltip+'-icon'),'title':close,'aria-label':close}).prepend($('<span />',{'class':'ui-icon ui-icon-close','html':'&times;'}));}
elements.button.appendTo(elements.titlebar).attr('role','button').click(function(event){if(!tooltip.hasClass(disabled)){self.hide(event);}
return FALSE;});self.redraw();}
function createTitle()
{var id=tooltipID+'-title';if(elements.titlebar){removeTitle();}
elements.titlebar=$('<div />',{'class':uitooltip+'-titlebar '+(options.style.widget?'ui-widget-header':'')}).append(elements.title=$('<div />',{'id':id,'class':uitooltip+'-title','aria-atomic':TRUE})).insertBefore(elements.content).delegate('.ui-tooltip-close','mousedown keydown mouseup keyup mouseout',function(event){$(this).toggleClass('ui-state-active ui-state-focus',event.type.substr(-4)==='down');}).delegate('.ui-tooltip-close','mouseover mouseout',function(event){$(this).toggleClass('ui-state-hover',event.type==='mouseover');});if(options.content.title.button){createButton();}
else if(self.rendered){self.redraw();}}
function updateButton(button)
{var elem=elements.button,title=elements.title;if(!self.rendered){return FALSE;}
if(!button){elem.remove();}
else{if(!title){createTitle();}
createButton();}}
function updateTitle(content,reposition)
{var elem=elements.title;if(!self.rendered||!content){return FALSE;}
if($.isFunction(content)){content=content.call(target,cache.event,self);}
if(content===FALSE||(!content&&content!=='')){return removeTitle(FALSE);}
else if(content.jquery&&content.length>0){elem.empty().append(content.css({display:'block'}));}
else{elem.html(content);}
self.redraw();if(reposition!==FALSE&&self.rendered&&tooltip[0].offsetWidth>0){self.reposition(cache.event);}}
function updateContent(content,reposition)
{var elem=elements.content;if(!self.rendered||!content){return FALSE;}
if($.isFunction(content)){content=content.call(target,cache.event,self)||'';}
if(content.jquery&&content.length>0){elem.empty().append(content.css({display:'block'}));}
else{elem.html(content);}
function detectImages(next){var images,srcs={};function imageLoad(image){if(image){delete srcs[image.src];clearTimeout(self.timers.img[image.src]);$(image).unbind(namespace);}
if($.isEmptyObject(srcs)){self.redraw();if(reposition!==FALSE){self.reposition(cache.event);}
next();}}
if((images=elem.find('img[src]:not([height]):not([width])')).length===0){return imageLoad();}
images.each(function(i,elem){if(srcs[elem.src]!==undefined){return;}
var iterations=0,maxIterations=3;(function timer(){if(elem.height||elem.width||(iterations>maxIterations)){return imageLoad(elem);}
iterations+=1;self.timers.img[elem.src]=setTimeout(timer,700);}());$(elem).bind('error'+namespace+' load'+namespace,function(){imageLoad(this);});srcs[elem.src]=elem;});}
if(self.rendered<0){tooltip.queue('fx',detectImages);}
else{isDrawing=0;detectImages($.noop);}
return self;}
function assignEvents()
{var posOptions=options.position,targets={show:options.show.target,hide:options.hide.target,viewport:$(posOptions.viewport),document:$(document),body:$(document.body),window:$(window)},events={show:$.trim(''+options.show.event).split(' '),hide:$.trim(''+options.hide.event).split(' ')},IE6=$.browser.msie&&parseInt($.browser.version,10)===6;function showMethod(event)
{if(tooltip.hasClass(disabled)){return FALSE;}
clearTimeout(self.timers.show);clearTimeout(self.timers.hide);var callback=function(){self.toggle(TRUE,event);};if(options.show.delay>0){self.timers.show=setTimeout(callback,options.show.delay);}
else{callback();}}
function hideMethod(event)
{if(tooltip.hasClass(disabled)||isPositioning||isDrawing){return FALSE;}
var relatedTarget=$(event.relatedTarget||event.target),ontoTooltip=relatedTarget.closest(selector)[0]===tooltip[0],ontoTarget=relatedTarget[0]===targets.show[0];clearTimeout(self.timers.show);clearTimeout(self.timers.hide);if((posOptions.target==='mouse'&&ontoTooltip)||(options.hide.fixed&&((/mouse(out|leave|move)/).test(event.type)&&(ontoTooltip||ontoTarget)))){try{event.preventDefault();event.stopImmediatePropagation();}catch(e){}return;}
if(options.hide.delay>0){self.timers.hide=setTimeout(function(){self.hide(event);},options.hide.delay);}
else{self.hide(event);}}
function inactiveMethod(event)
{if(tooltip.hasClass(disabled)){return FALSE;}
clearTimeout(self.timers.inactive);self.timers.inactive=setTimeout(function(){self.hide(event);},options.hide.inactive);}
function repositionMethod(event){if(self.rendered&&tooltip[0].offsetWidth>0){self.reposition(event);}}
tooltip.bind('mouseenter'+namespace+' mouseleave'+namespace,function(event){var state=event.type==='mouseenter';if(state){self.focus(event);}
tooltip.toggleClass(hoverClass,state);});if(/mouse(out|leave)/i.test(options.hide.event)){if(options.hide.leave==='window'){targets.window.bind('mouseleave'+namespace+' blur'+namespace,function(event){if(!/select|option/.test(event.target.nodeName)&&!event.relatedTarget){self.hide(event);}});}}
if(options.hide.fixed){targets.hide=targets.hide.add(tooltip);tooltip.bind('mouseover'+namespace,function(){if(!tooltip.hasClass(disabled)){clearTimeout(self.timers.hide);}});}
else if(/mouse(over|enter)/i.test(options.show.event)){targets.hide.bind('mouseleave'+namespace,function(event){clearTimeout(self.timers.show);});}
if((''+options.hide.event).indexOf('unfocus')>-1){posOptions.container.closest('html').bind('mousedown'+namespace,function(event){var elem=$(event.target),enabled=self.rendered&&!tooltip.hasClass(disabled)&&tooltip[0].offsetWidth>0,isAncestor=elem.parents(selector).filter(tooltip[0]).length>0;if(elem[0]!==target[0]&&elem[0]!==tooltip[0]&&!isAncestor&&!target.has(elem[0]).length&&!elem.attr('disabled')){self.hide(event);}});}
if('number'===typeof options.hide.inactive){targets.show.bind('qtip-'+id+'-inactive',inactiveMethod);$.each(QTIP.inactiveEvents,function(index,type){targets.hide.add(elements.tooltip).bind(type+namespace+'-inactive',inactiveMethod);});}
$.each(events.hide,function(index,type){var showIndex=$.inArray(type,events.show),targetHide=$(targets.hide);if((showIndex>-1&&targetHide.add(targets.show).length===targetHide.length)||type==='unfocus')
{targets.show.bind(type+namespace,function(event){if(tooltip[0].offsetWidth>0){hideMethod(event);}
else{showMethod(event);}});delete events.show[showIndex];}
else{targets.hide.bind(type+namespace,hideMethod);}});$.each(events.show,function(index,type){targets.show.bind(type+namespace,showMethod);});if('number'===typeof options.hide.distance){targets.show.add(tooltip).bind('mousemove'+namespace,function(event){var origin=cache.origin||{},limit=options.hide.distance,abs=Math.abs;if(abs(event.pageX-origin.pageX)>=limit||abs(event.pageY-origin.pageY)>=limit){self.hide(event);}});}
if(posOptions.target==='mouse'){targets.show.bind('mousemove'+namespace,function(event){MOUSE={pageX:event.pageX,pageY:event.pageY,type:'mousemove'};});if(posOptions.adjust.mouse){if(options.hide.event){tooltip.bind('mouseleave'+namespace,function(event){if((event.relatedTarget||event.target)!==targets.show[0]){self.hide(event);}});elements.target.bind('mouseenter'+namespace+' mouseleave'+namespace,function(event){cache.onTarget=event.type==='mouseenter';});}
targets.document.bind('mousemove'+namespace,function(event){if(self.rendered&&cache.onTarget&&!tooltip.hasClass(disabled)&&tooltip[0].offsetWidth>0){self.reposition(event||MOUSE);}});}}
if(posOptions.adjust.resize||targets.viewport.length){($.event.special.resize?targets.viewport:targets.window).bind('resize'+namespace,repositionMethod);}
if(targets.viewport.length||(IE6&&tooltip.css('position')==='fixed')){targets.viewport.bind('scroll'+namespace,repositionMethod);}}
function unassignEvents()
{var targets=[options.show.target[0],options.hide.target[0],self.rendered&&elements.tooltip[0],options.position.container[0],options.position.viewport[0],window,document];if(self.rendered){$([]).pushStack($.grep(targets,function(i){return typeof i==='object';})).unbind(namespace);}
else{options.show.target.unbind(namespace+'-create');}}
self.checks.builtin={'^id$':function(obj,o,v){var id=v===TRUE?QTIP.nextid:v,tooltipID=uitooltip+'-'+id;if(id!==FALSE&&id.length>0&&!$('#'+tooltipID).length){tooltip[0].id=tooltipID;elements.content[0].id=tooltipID+'-content';elements.title[0].id=tooltipID+'-title';}},'^content.text$':function(obj,o,v){updateContent(v);},'^content.title.text$':function(obj,o,v){if(!v){return removeTitle();}
if(!elements.title&&v){createTitle();}
updateTitle(v);},'^content.title.button$':function(obj,o,v){updateButton(v);},'^position.(my|at)$':function(obj,o,v){if('string'===typeof v){obj[o]=new PLUGINS.Corner(v);}},'^position.container$':function(obj,o,v){if(self.rendered){tooltip.appendTo(v);}},'^show.ready$':function(){if(!self.rendered){self.render(1);}
else{self.toggle(TRUE);}},'^style.classes$':function(obj,o,v){tooltip.attr('class',uitooltip+' qtip ui-helper-reset '+v);},'^style.widget|content.title':setWidget,'^events.(render|show|move|hide|focus|blur)$':function(obj,o,v){tooltip[($.isFunction(v)?'':'un')+'bind']('tooltip'+o,v);},'^(show|hide|position).(event|target|fixed|inactive|leave|distance|viewport|adjust)':function(){var posOptions=options.position;tooltip.attr('tracking',posOptions.target==='mouse'&&posOptions.adjust.mouse);unassignEvents();assignEvents();}};$.extend(self,{render:function(show)
{if(self.rendered){return self;}
var text=options.content.text,title=options.content.title.text,posOptions=options.position,callback=$.Event('tooltiprender');$.attr(target[0],'aria-describedby',tooltipID);tooltip=elements.tooltip=$('<div/>',{'id':tooltipID,'class':uitooltip+' qtip ui-helper-reset '+defaultClass+' '+options.style.classes+' '+uitooltip+'-pos-'+options.position.my.abbrev(),'width':options.style.width||'','height':options.style.height||'','tracking':posOptions.target==='mouse'&&posOptions.adjust.mouse,'role':'alert','aria-live':'polite','aria-atomic':FALSE,'aria-describedby':tooltipID+'-content','aria-hidden':TRUE}).toggleClass(disabled,cache.disabled).data('qtip',self).appendTo(options.position.container).append(elements.content=$('<div />',{'class':uitooltip+'-content','id':tooltipID+'-content','aria-atomic':TRUE}));self.rendered=-1;isDrawing=1;isPositioning=1;if(title){createTitle();if(!$.isFunction(title)){updateTitle(title,FALSE);}}
if(!$.isFunction(text)){updateContent(text,FALSE);}
self.rendered=TRUE;setWidget();$.each(options.events,function(name,callback){if($.isFunction(callback)){tooltip.bind(name==='toggle'?'tooltipshow tooltiphide':'tooltip'+name,callback);}});$.each(PLUGINS,function(){if(this.initialize==='render'){this(self);}});assignEvents();tooltip.queue('fx',function(next){callback.originalEvent=cache.event;tooltip.trigger(callback,[self]);isDrawing=0;isPositioning=0;self.redraw();if(options.show.ready||show){self.toggle(TRUE,cache.event,FALSE);}
next();});return self;},get:function(notation)
{var result,o;switch(notation.toLowerCase())
{case'dimensions':result={height:tooltip.outerHeight(),width:tooltip.outerWidth()};break;case'offset':result=PLUGINS.offset(tooltip,options.position.container);break;default:o=convertNotation(notation.toLowerCase());result=o[0][o[1]];result=result.precedance?result.string():result;break;}
return result;},set:function(option,value)
{var rmove=/^position\.(my|at|adjust|target|container)|style|content|show\.ready/i,rdraw=/^content\.(title|attr)|style/i,reposition=FALSE,redraw=FALSE,checks=self.checks,name;function callback(notation,args){var category,rule,match;for(category in checks){for(rule in checks[category]){if(match=(new RegExp(rule,'i')).exec(notation)){args.push(match);checks[category][rule].apply(self,args);}}}}
if('string'===typeof option){name=option;option={};option[name]=value;}
else{option=$.extend(TRUE,{},option);}
$.each(option,function(notation,value){var obj=convertNotation(notation.toLowerCase()),previous;previous=obj[0][obj[1]];obj[0][obj[1]]='object'===typeof value&&value.nodeType?$(value):value;option[notation]=[obj[0],obj[1],value,previous];reposition=rmove.test(notation)||reposition;redraw=rdraw.test(notation)||redraw;});sanitizeOptions(options);isPositioning=isDrawing=1;$.each(option,callback);isPositioning=isDrawing=0;if(self.rendered&&tooltip[0].offsetWidth>0){if(reposition){self.reposition(options.position.target==='mouse'?NULL:cache.event);}
if(redraw){self.redraw();}}
return self;},toggle:function(state,event)
{if(!self.rendered){return state?self.render(1):self;}
var type=state?'show':'hide',opts=options[type],otherOpts=options[!state?'show':'hide'],posOptions=options.position,contentOptions=options.content,visible=tooltip[0].offsetWidth>0,animate=state||opts.target.length===1,sameTarget=!event||opts.target.length<2||cache.target[0]===event.target,delay,callback;if((typeof state).search('boolean|number')){state=!visible;}
if(!tooltip.is(':animated')&&visible===state&&sameTarget){return self;}
if(event){if((/over|enter/).test(event.type)&&(/out|leave/).test(cache.event.type)&&options.show.target.add(event.target).length===options.show.target.length&&tooltip.has(event.relatedTarget).length){return self;}
cache.event=$.extend({},event);}
callback=$.Event('tooltip'+type);callback.originalEvent=event?cache.event:NULL;tooltip.trigger(callback,[self,90]);if(callback.isDefaultPrevented()){return self;}
$.attr(tooltip[0],'aria-hidden',!!!state);if(state){cache.origin=$.extend({},MOUSE);self.focus(event);if($.isFunction(contentOptions.text)){updateContent(contentOptions.text,FALSE);}
if($.isFunction(contentOptions.title.text)){updateTitle(contentOptions.title.text,FALSE);}
if(!trackingBound&&posOptions.target==='mouse'&&posOptions.adjust.mouse){$(document).bind('mousemove.qtip',function(event){MOUSE={pageX:event.pageX,pageY:event.pageY,type:'mousemove'};});trackingBound=TRUE;}
self.reposition(event,arguments[2]);if((callback.solo=!!opts.solo)){$(selector,opts.solo).not(tooltip).qtip('hide',callback);}}
else{clearTimeout(self.timers.show);delete cache.origin;if(trackingBound&&!$(selector+'[tracking="true"]:visible',opts.solo).not(tooltip).length){$(document).unbind('mousemove.qtip');trackingBound=FALSE;}
self.blur(event);}
function after(){if(state){if($.browser.msie){tooltip[0].style.removeAttribute('filter');}
tooltip.css('overflow','');if('string'===typeof opts.autofocus){$(opts.autofocus,tooltip).focus();}
opts.target.trigger('qtip-'+id+'-inactive');}
else{tooltip.css({display:'',visibility:'',opacity:'',left:'',top:''});}
callback=$.Event('tooltip'+(state?'visible':'hidden'));callback.originalEvent=event?cache.event:NULL;tooltip.trigger(callback,[self]);}
if(opts.effect===FALSE||animate===FALSE){tooltip[type]();after.call(tooltip);}
else if($.isFunction(opts.effect)){tooltip.stop(1,1);opts.effect.call(tooltip,self);tooltip.queue('fx',function(n){after();n();});}
else{tooltip.fadeTo(90,state?1:0,after);}
if(state){opts.target.trigger('qtip-'+id+'-inactive');}
return self;},show:function(event){return self.toggle(TRUE,event);},hide:function(event){return self.toggle(FALSE,event);},focus:function(event)
{if(!self.rendered){return self;}
var qtips=$(selector),curIndex=parseInt(tooltip[0].style.zIndex,10),newIndex=QTIP.zindex+qtips.length,cachedEvent=$.extend({},event),focusedElem,callback;if(!tooltip.hasClass(focusClass))
{callback=$.Event('tooltipfocus');callback.originalEvent=cachedEvent;tooltip.trigger(callback,[self,newIndex]);if(!callback.isDefaultPrevented()){if(curIndex!==newIndex){qtips.each(function(){if(this.style.zIndex>curIndex){this.style.zIndex=this.style.zIndex-1;}});qtips.filter('.'+focusClass).qtip('blur',cachedEvent);}
tooltip.addClass(focusClass)[0].style.zIndex=newIndex;}}
return self;},blur:function(event){var cachedEvent=$.extend({},event),callback;tooltip.removeClass(focusClass);callback=$.Event('tooltipblur');callback.originalEvent=cachedEvent;tooltip.trigger(callback,[self]);return self;},reposition:function(event,effect)
{if(!self.rendered||isPositioning){return self;}
isPositioning=1;var target=options.position.target,posOptions=options.position,my=posOptions.my,at=posOptions.at,adjust=posOptions.adjust,method=adjust.method.split(' '),elemWidth=tooltip.outerWidth(),elemHeight=tooltip.outerHeight(),targetWidth=0,targetHeight=0,callback=$.Event('tooltipmove'),fixed=tooltip.css('position')==='fixed',viewport=posOptions.viewport,position={left:0,top:0},container=posOptions.container,visible=tooltip[0].offsetWidth>0,adjusted,offset,win;if($.isArray(target)&&target.length===2){at={x:LEFT,y:TOP};position={left:target[0],top:target[1]};}
else if(target==='mouse'&&((event&&event.pageX)||cache.event.pageX)){at={x:LEFT,y:TOP};event=(event&&(event.type==='resize'||event.type==='scroll')?cache.event:event&&event.pageX&&event.type==='mousemove'?event:MOUSE&&MOUSE.pageX&&(adjust.mouse||!event||!event.pageX)?{pageX:MOUSE.pageX,pageY:MOUSE.pageY}:!adjust.mouse&&cache.origin&&cache.origin.pageX&&options.show.distance?cache.origin:event)||event||cache.event||MOUSE||{};position={top:event.pageY,left:event.pageX};}
else{if(target==='event'&&event&&event.target&&event.type!=='scroll'&&event.type!=='resize'){cache.target=$(event.target);}
else if(target!=='event'){cache.target=$(target.jquery?target:elements.target);}
target=cache.target;target=$(target).eq(0);if(target.length===0){return self;}
else if(target[0]===document||target[0]===window){targetWidth=PLUGINS.iOS?window.innerWidth:target.width();targetHeight=PLUGINS.iOS?window.innerHeight:target.height();if(target[0]===window){position={top:(viewport||target).scrollTop(),left:(viewport||target).scrollLeft()};}}
else if(PLUGINS.imagemap&&target.is('area')){adjusted=PLUGINS.imagemap(self,target,at,PLUGINS.viewport?method:FALSE);}
else if(PLUGINS.svg&&typeof target[0].xmlbase==='string'){adjusted=PLUGINS.svg(self,target,at,PLUGINS.viewport?method:FALSE);}
else{targetWidth=target.outerWidth();targetHeight=target.outerHeight();position=PLUGINS.offset(target,container);}
if(adjusted){targetWidth=adjusted.width;targetHeight=adjusted.height;offset=adjusted.offset;position=adjusted.position;}
if((PLUGINS.iOS>3.1&&PLUGINS.iOS<4.1)||(PLUGINS.iOS>=4.3&&PLUGINS.iOS<4.33)||(!PLUGINS.iOS&&fixed)){win=$(window);position.left-=win.scrollLeft();position.top-=win.scrollTop();}
position.left+=at.x===RIGHT?targetWidth:at.x===CENTER?targetWidth/2:0;position.top+=at.y===BOTTOM?targetHeight:at.y===CENTER?targetHeight/2:0;}
position.left+=adjust.x+(my.x===RIGHT?-elemWidth:my.x===CENTER?-elemWidth/2:0);position.top+=adjust.y+(my.y===BOTTOM?-elemHeight:my.y===CENTER?-elemHeight/2:0);if(PLUGINS.viewport){position.adjusted=PLUGINS.viewport(self,position,posOptions,targetWidth,targetHeight,elemWidth,elemHeight);if(offset&&position.adjusted.left){position.left+=offset.left;}
if(offset&&position.adjusted.top){position.top+=offset.top;}}
else{position.adjusted={left:0,top:0};}
callback.originalEvent=$.extend({},event);tooltip.trigger(callback,[self,position,viewport.elem||viewport]);if(callback.isDefaultPrevented()){return self;}
delete position.adjusted;if(effect===FALSE||!visible||isNaN(position.left)||isNaN(position.top)||target==='mouse'||!$.isFunction(posOptions.effect)){tooltip.css(position);}
else if($.isFunction(posOptions.effect)){posOptions.effect.call(tooltip,self,$.extend({},position));tooltip.queue(function(next){$(this).css({opacity:'',height:''});if($.browser.msie){this.style.removeAttribute('filter');}
next();});}
isPositioning=0;return self;},redraw:function()
{if(self.rendered<1||isDrawing){return self;}
var container=options.position.container,perc,width,max,min;isDrawing=1;if(options.style.height){tooltip.css(HEIGHT,options.style.height);}
if(options.style.width){tooltip.css(WIDTH,options.style.width);}
else{tooltip.css(WIDTH,'').addClass(fluidClass);width=tooltip.width()+1;max=tooltip.css('max-width')||'';min=tooltip.css('min-width')||'';perc=(max+min).indexOf('%')>-1?container.width()/100:0;max=((max.indexOf('%')>-1?perc:1)*parseInt(max,10))||width;min=((min.indexOf('%')>-1?perc:1)*parseInt(min,10))||0;width=max+min?Math.min(Math.max(width,min),max):width;tooltip.css(WIDTH,Math.round(width)).removeClass(fluidClass);}
isDrawing=0;return self;},disable:function(state)
{if('boolean'!==typeof state){state=!(tooltip.hasClass(disabled)||cache.disabled);}
if(self.rendered){tooltip.toggleClass(disabled,state);$.attr(tooltip[0],'aria-disabled',state);}
else{cache.disabled=!!state;}
return self;},enable:function(){return self.disable(FALSE);},destroy:function()
{var t=target[0],title=$.attr(t,oldtitle),elemAPI=target.data('qtip');self.destroyed=TRUE;if(self.rendered){tooltip.stop(1,0).remove();$.each(self.plugins,function(){if(this.destroy){this.destroy();}});}
clearTimeout(self.timers.show);clearTimeout(self.timers.hide);unassignEvents();if(!elemAPI||self===elemAPI){$.removeData(t,'qtip');if(options.suppress&&title){$.attr(t,'title',title);target.removeAttr(oldtitle);}
target.removeAttr('aria-describedby');}
target.unbind('.qtip-'+id);delete usedIDs[self.id];return target;}});}
function init(id,opts)
{var obj,posOptions,attr,config,title,elem=$(this),docBody=$(document.body),newTarget=this===document?docBody:elem,metadata=(elem.metadata)?elem.metadata(opts.metadata):NULL,metadata5=opts.metadata.type==='html5'&&metadata?metadata[opts.metadata.name]:NULL,html5=elem.data(opts.metadata.name||'qtipopts');try{html5=typeof html5==='string'?$.parseJSON(html5):html5;}
catch(e){log('Unable to parse HTML5 attribute data: '+html5);}
config=$.extend(TRUE,{},QTIP.defaults,opts,typeof html5==='object'?sanitizeOptions(html5):NULL,sanitizeOptions(metadata5||metadata));posOptions=config.position;config.id=id;if('boolean'===typeof config.content.text){attr=elem.attr(config.content.attr);if(config.content.attr!==FALSE&&attr){config.content.text=attr;}
else{log('Unable to locate content for tooltip! Aborting render of tooltip on element: ',elem);return FALSE;}}
if(!posOptions.container.length){posOptions.container=docBody;}
if(posOptions.target===FALSE){posOptions.target=newTarget;}
if(config.show.target===FALSE){config.show.target=newTarget;}
if(config.show.solo===TRUE){config.show.solo=posOptions.container.closest('body');}
if(config.hide.target===FALSE){config.hide.target=newTarget;}
if(config.position.viewport===TRUE){config.position.viewport=posOptions.container;}
posOptions.container=posOptions.container.eq(0);posOptions.at=new PLUGINS.Corner(posOptions.at);posOptions.my=new PLUGINS.Corner(posOptions.my);if($.data(this,'qtip')){if(config.overwrite){elem.qtip('destroy');}
else if(config.overwrite===FALSE){return FALSE;}}
if(config.suppress&&(title=$.attr(this,'title'))){$(this).removeAttr('title').attr(oldtitle,title).attr('title','');}
obj=new QTip(elem,config,id,!!attr);$.data(this,'qtip',obj);elem.bind('remove.qtip-'+id+' removeqtip.qtip-'+id,function(){obj.destroy();});return obj;}
QTIP=$.fn.qtip=function(options,notation,newValue)
{var command=(''+options).toLowerCase(),returned=NULL,args=$.makeArray(arguments).slice(1),event=args[args.length-1],opts=this[0]?$.data(this[0],'qtip'):NULL;if((!arguments.length&&opts)||command==='api'){return opts;}
else if('string'===typeof options)
{this.each(function()
{var api=$.data(this,'qtip');if(!api){return TRUE;}
if(event&&event.timeStamp){api.cache.event=event;}
if((command==='option'||command==='options')&&notation){if($.isPlainObject(notation)||newValue!==undefined){api.set(notation,newValue);}
else{returned=api.get(notation);return FALSE;}}
else if(api[command]){api[command].apply(api[command],args);}});return returned!==NULL?returned:this;}
else if('object'===typeof options||!arguments.length)
{opts=sanitizeOptions($.extend(TRUE,{},options));return QTIP.bind.call(this,opts,event);}};QTIP.bind=function(opts,event)
{return this.each(function(i){var options,targets,events,namespace,api,id;id=$.isArray(opts.id)?opts.id[i]:opts.id;id=!id||id===FALSE||id.length<1||usedIDs[id]?QTIP.nextid++:(usedIDs[id]=id);namespace='.qtip-'+id+'-create';api=init.call(this,id,opts);if(api===FALSE){return TRUE;}
options=api.options;$.each(PLUGINS,function(){if(this.initialize==='initialize'){this(api);}});targets={show:options.show.target,hide:options.hide.target};events={show:$.trim(''+options.show.event).replace(/ /g,namespace+' ')+namespace,hide:$.trim(''+options.hide.event).replace(/ /g,namespace+' ')+namespace};if(/mouse(over|enter)/i.test(events.show)&&!/mouse(out|leave)/i.test(events.hide)){events.hide+=' mouseleave'+namespace;}
targets.show.bind('mousemove'+namespace,function(event){MOUSE={pageX:event.pageX,pageY:event.pageY,type:'mousemove'};api.cache.onTarget=TRUE;});function hoverIntent(event){function render(){api.render(typeof event==='object'||options.show.ready);targets.show.add(targets.hide).unbind(namespace);}
if(api.cache.disabled){return FALSE;}
api.cache.event=$.extend({},event);api.cache.target=event?$(event.target):[undefined];if(options.show.delay>0){clearTimeout(api.timers.show);api.timers.show=setTimeout(render,options.show.delay);if(events.show!==events.hide){targets.hide.bind(events.hide,function(){clearTimeout(api.timers.show);});}}
else{render();}}
targets.show.bind(events.show,hoverIntent);if(options.show.ready||options.prerender){hoverIntent(event);}});};PLUGINS=QTIP.plugins={Corner:function(corner){corner=(''+corner).replace(/([A-Z])/,' $1').replace(/middle/gi,CENTER).toLowerCase();this.x=(corner.match(/left|right/i)||corner.match(/center/)||['inherit'])[0].toLowerCase();this.y=(corner.match(/top|bottom|center/i)||['inherit'])[0].toLowerCase();var f=corner.charAt(0);this.precedance=(f==='t'||f==='b'?Y:X);this.string=function(){return this.precedance===Y?this.y+this.x:this.x+this.y;};this.abbrev=function(){var x=this.x.substr(0,1),y=this.y.substr(0,1);return x===y?x:this.precedance===Y?y+x:x+y;};this.invertx=function(center){this.x=this.x===LEFT?RIGHT:this.x===RIGHT?LEFT:center||this.x;};this.inverty=function(center){this.y=this.y===TOP?BOTTOM:this.y===BOTTOM?TOP:center||this.y;};this.clone=function(){return{x:this.x,y:this.y,precedance:this.precedance,string:this.string,abbrev:this.abbrev,clone:this.clone,invertx:this.invertx,inverty:this.inverty};};},offset:function(elem,container){var pos=elem.offset(),docBody=elem.closest('body')[0],parent=container,scrolled,coffset,overflow;function scroll(e,i){pos.left+=i*e.scrollLeft();pos.top+=i*e.scrollTop();}
if(parent){do{if(parent.css('position')!=='static'){coffset=parent.position();pos.left-=coffset.left+(parseInt(parent.css('borderLeftWidth'),10)||0)+(parseInt(parent.css('marginLeft'),10)||0);pos.top-=coffset.top+(parseInt(parent.css('borderTopWidth'),10)||0)+(parseInt(parent.css('marginTop'),10)||0);if(!scrolled&&(overflow=parent.css('overflow'))!=='hidden'&&overflow!=='visible'){scrolled=parent;}}}
while((parent=$(parent[0].offsetParent)).length);if(scrolled&&scrolled[0]!==docBody){scroll(scrolled,1);}}
return pos;},iOS:parseFloat((''+(/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent)||[0,''])[1]).replace('undefined','3_2').replace('_','.').replace('_',''))||FALSE,fn:{attr:function(attr,val){if(this.length){var self=this[0],title='title',api=$.data(self,'qtip');if(attr===title&&api&&'object'===typeof api&&api.options.suppress){if(arguments.length<2){return $.attr(self,oldtitle);}
else{if(api&&api.options.content.attr===title&&api.cache.attr){api.set('content.text',val);}
return this.attr(oldtitle,val);}}}
return $.fn['attr'+replaceSuffix].apply(this,arguments);},clone:function(keepData){var titles=$([]),title='title',elems=$.fn['clone'+replaceSuffix].apply(this,arguments);if(!keepData){elems.filter('['+oldtitle+']').attr('title',function(){return $.attr(this,oldtitle);}).removeAttr(oldtitle);}
return elems;}}};$.each(PLUGINS.fn,function(name,func){if(!func||$.fn[name+replaceSuffix]){return TRUE;}
var old=$.fn[name+replaceSuffix]=$.fn[name];$.fn[name]=function(){return func.apply(this,arguments)||old.apply(this,arguments);};});if(!$.ui){$['cleanData'+replaceSuffix]=$.cleanData;$.cleanData=function(elems){for(var i=0,elem;(elem=elems[i])!==undefined;i++){try{$(elem).triggerHandler('removeqtip');}
catch(e){}}
$['cleanData'+replaceSuffix](elems);};}
QTIP.version='@VERSION';QTIP.nextid=0;QTIP.inactiveEvents='click dblclick mousedown mouseup mousemove mouseleave mouseenter'.split(' ');QTIP.zindex=15000;QTIP.defaults={prerender:FALSE,id:FALSE,overwrite:TRUE,suppress:TRUE,content:{text:TRUE,attr:'title',title:{text:FALSE,button:FALSE}},position:{my:'top left',at:'bottom right',target:FALSE,container:FALSE,viewport:FALSE,adjust:{x:0,y:0,mouse:TRUE,resize:TRUE,method:'flip flip'},effect:function(api,pos,viewport){$(this).animate(pos,{duration:200,queue:FALSE});}},show:{target:FALSE,event:'mouseenter',effect:TRUE,delay:90,solo:FALSE,ready:FALSE,autofocus:FALSE},hide:{target:FALSE,event:'mouseleave',effect:TRUE,delay:0,fixed:FALSE,inactive:FALSE,leave:'window',distance:FALSE},style:{classes:'',widget:FALSE,width:FALSE,height:FALSE,def:TRUE},events:{render:NULL,move:NULL,show:NULL,hide:NULL,toggle:NULL,visible:NULL,hidden:NULL,focus:NULL,blur:NULL}};function Ajax(api)
{var self=this,tooltip=api.elements.tooltip,opts=api.options.content.ajax,defaults=QTIP.defaults.content.ajax,namespace='.qtip-ajax',rscript=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,first=TRUE,stop=FALSE,xhr;api.checks.ajax={'^content.ajax':function(obj,name,v){if(name==='ajax'){opts=v;}
if(name==='once'){self.init();}
else if(opts&&opts.url){self.load();}
else{tooltip.unbind(namespace);}}};$.extend(self,{init:function(){if(opts&&opts.url){tooltip.unbind(namespace)[opts.once?'one':'bind']('tooltipshow'+namespace,self.load);}
return self;},load:function(event){if(stop){stop=FALSE;return;}
var hasSelector=opts.url.indexOf(' '),url=opts.url,selector,hideFirst=!opts.loading&&first;if(hideFirst){try{event.preventDefault();}catch(e){}}
else if(event&&event.isDefaultPrevented()){return self;}
if(xhr&&xhr.abort){xhr.abort();}
if(hasSelector>-1){selector=url.substr(hasSelector);url=url.substr(0,hasSelector);}
function after(){var complete;if(api.destroyed){return;}
first=FALSE;if(hideFirst){stop=TRUE;api.show(event.originalEvent);}
if((complete=defaults.complete||opts.complete)&&$.isFunction(complete)){complete.apply(opts.context||api,arguments);}}
function successHandler(content,status,jqXHR){var success;if(api.destroyed){return;}
if(selector){content=$('<div/>').append(content.replace(rscript,"")).find(selector);}
if((success=defaults.success||opts.success)&&$.isFunction(success)){success.call(opts.context||api,content,status,jqXHR);}
else{api.set('content.text',content);}}
function errorHandler(xhr,status,error){if(api.destroyed||xhr.status===0){return;}
api.set('content.text',status+': '+error);}
xhr=$.ajax($.extend({error:defaults.error||errorHandler,context:api},opts,{url:url,success:successHandler,complete:after}));},destroy:function(){if(xhr&&xhr.abort){xhr.abort();}
api.destroyed=TRUE;}});self.init();}
PLUGINS.ajax=function(api)
{var self=api.plugins.ajax;return'object'===typeof self?self:(api.plugins.ajax=new Ajax(api));};PLUGINS.ajax.initialize='render';PLUGINS.ajax.sanitize=function(options)
{var content=options.content,opts;if(content&&'ajax'in content){opts=content.ajax;if(typeof opts!=='object'){opts=options.content.ajax={url:opts};}
if('boolean'!==typeof opts.once&&opts.once){opts.once=!!opts.once;}}};$.extend(TRUE,QTIP.defaults,{content:{ajax:{loading:TRUE,once:TRUE}}});PLUGINS.viewport=function(api,position,posOptions,targetWidth,targetHeight,elemWidth,elemHeight)
{var target=posOptions.target,tooltip=api.elements.tooltip,my=posOptions.my,at=posOptions.at,adjust=posOptions.adjust,method=adjust.method.split(' '),methodX=method[0],methodY=method[1]||method[0],viewport=posOptions.viewport,container=posOptions.container,cache=api.cache,tip=api.plugins.tip,adjusted={left:0,top:0},fixed,newMy,newClass;if(!viewport.jquery||target[0]===window||target[0]===document.body||adjust.method==='none'){return adjusted;}
fixed=tooltip.css('position')==='fixed';viewport={elem:viewport,height:viewport[(viewport[0]===window?'h':'outerH')+'eight'](),width:viewport[(viewport[0]===window?'w':'outerW')+'idth'](),scrollleft:fixed?0:viewport.scrollLeft(),scrolltop:fixed?0:viewport.scrollTop(),offset:viewport.offset()||{left:0,top:0}};container={elem:container,scrollLeft:container.scrollLeft(),scrollTop:container.scrollTop(),offset:container.offset()||{left:0,top:0}};function calculate(side,otherSide,type,adjust,side1,side2,lengthName,targetLength,elemLength){var initialPos=position[side1],mySide=my[side],atSide=at[side],isShift=type===SHIFT,viewportScroll=-container.offset[side1]+viewport.offset[side1]+viewport['scroll'+side1],myLength=mySide===side1?elemLength:mySide===side2?-elemLength:-elemLength/2,atLength=atSide===side1?targetLength:atSide===side2?-targetLength:-targetLength/2,tipLength=tip&&tip.size?tip.size[lengthName]||0:0,tipAdjust=tip&&tip.corner&&tip.corner.precedance===side&&!isShift?tipLength:0,overflow1=viewportScroll-initialPos+tipAdjust,overflow2=initialPos+elemLength-viewport[lengthName]-viewportScroll+tipAdjust,offset=myLength-(my.precedance===side||mySide===my[otherSide]?atLength:0)-(atSide===CENTER?targetLength/2:0);if(isShift){tipAdjust=tip&&tip.corner&&tip.corner.precedance===otherSide?tipLength:0;offset=(mySide===side1?1:-1)*myLength-tipAdjust;position[side1]+=overflow1>0?overflow1:overflow2>0?-overflow2:0;position[side1]=Math.max(-container.offset[side1]+viewport.offset[side1]+(tipAdjust&&tip.corner[side]===CENTER?tip.offset:0),initialPos-offset,Math.min(Math.max(-container.offset[side1]+viewport.offset[side1]+viewport[lengthName],initialPos+offset),position[side1]));}
else{adjust*=(type===FLIPINVERT?2:0);if(overflow1>0&&(mySide!==side1||overflow2>0)){position[side1]-=offset+adjust;newMy['invert'+side](side1);}
else if(overflow2>0&&(mySide!==side2||overflow1>0)){position[side1]-=(mySide===CENTER?-offset:offset)+adjust;newMy['invert'+side](side2);}
if(position[side1]<viewportScroll&&-position[side1]>overflow2){position[side1]=initialPos;newMy=undefined;}}
return position[side1]-initialPos;}
if(methodX!=='shift'||methodY!=='shift'){newMy=my.clone();}
adjusted={left:methodX!=='none'?calculate(X,Y,methodX,adjust.x,LEFT,RIGHT,WIDTH,targetWidth,elemWidth):0,top:methodY!=='none'?calculate(Y,X,methodY,adjust.y,TOP,BOTTOM,HEIGHT,targetHeight,elemHeight):0};if(newMy&&cache.lastClass!==(newClass=uitooltip+'-pos-'+newMy.abbrev())){tooltip.removeClass(api.cache.lastClass).addClass((api.cache.lastClass=newClass));}
return adjusted;};function calculateTip(corner,width,height)
{var width2=Math.ceil(width/2),height2=Math.ceil(height/2),tips={bottomright:[[0,0],[width,height],[width,0]],bottomleft:[[0,0],[width,0],[0,height]],topright:[[0,height],[width,0],[width,height]],topleft:[[0,0],[0,height],[width,height]],topcenter:[[0,height],[width2,0],[width,height]],bottomcenter:[[0,0],[width,0],[width2,height]],rightcenter:[[0,0],[width,height2],[0,height]],leftcenter:[[width,0],[width,height],[0,height2]]};tips.lefttop=tips.bottomright;tips.righttop=tips.bottomleft;tips.leftbottom=tips.topright;tips.rightbottom=tips.topleft;return tips[corner.string()];}
function Tip(qTip,command)
{var self=this,opts=qTip.options.style.tip,elems=qTip.elements,tooltip=elems.tooltip,cache={top:0,left:0},size={width:opts.width,height:opts.height},color={},border=opts.border||0,namespace='.qtip-tip',hasCanvas=!!($('<canvas />')[0]||{}).getContext;self.corner=NULL;self.mimic=NULL;self.border=border;self.offset=opts.offset;self.size=size;qTip.checks.tip={'^position.my|style.tip.(corner|mimic|border)$':function(){if(!self.init()){self.destroy();}
qTip.reposition();},'^style.tip.(height|width)$':function(){size={width:opts.width,height:opts.height};self.create();self.update();qTip.reposition();},'^content.title.text|style.(classes|widget)$':function(){if(elems.tip&&elems.tip.length){self.update();}}};function swapDimensions(){size.width=opts.height;size.height=opts.width;}
function resetDimensions(){size.width=opts.width;size.height=opts.height;}
function reposition(event,api,pos,viewport){if(!elems.tip){return;}
var newCorner=self.corner.clone(),adjust=pos.adjusted,method=qTip.options.position.adjust.method.split(' '),horizontal=method[0],vertical=method[1]||method[0],shift={left:FALSE,top:FALSE,x:0,y:0},offset,css={},props;if(self.corner.fixed!==TRUE){if(horizontal===SHIFT&&newCorner.precedance===X&&adjust.left&&newCorner.y!==CENTER){newCorner.precedance=newCorner.precedance===X?Y:X;}
else if(horizontal!==SHIFT&&adjust.left){newCorner.x=newCorner.x===CENTER?(adjust.left>0?LEFT:RIGHT):(newCorner.x===LEFT?RIGHT:LEFT);}
if(vertical===SHIFT&&newCorner.precedance===Y&&adjust.top&&newCorner.x!==CENTER){newCorner.precedance=newCorner.precedance===Y?X:Y;}
else if(vertical!==SHIFT&&adjust.top){newCorner.y=newCorner.y===CENTER?(adjust.top>0?TOP:BOTTOM):(newCorner.y===TOP?BOTTOM:TOP);}
if(newCorner.string()!==cache.corner.string()&&(cache.top!==adjust.top||cache.left!==adjust.left)){self.update(newCorner,FALSE);}}
offset=self.position(newCorner,adjust);offset[newCorner.x]+=borderWidth(newCorner,newCorner.x,TRUE);offset[newCorner.y]+=borderWidth(newCorner,newCorner.y,TRUE);if(offset.right!==undefined){offset.left=-offset.right;}
if(offset.bottom!==undefined){offset.top=-offset.bottom;}
offset.user=Math.max(0,opts.offset);if(shift.left=(horizontal===SHIFT&&!!adjust.left)){if(newCorner.x===CENTER){css['margin-left']=shift.x=offset['margin-left']-adjust.left;}
else{props=offset.right!==undefined?[adjust.left,-offset.left]:[-adjust.left,offset.left];if((shift.x=Math.max(props[0],props[1]))>props[0]){pos.left-=adjust.left;shift.left=FALSE;}
css[offset.right!==undefined?RIGHT:LEFT]=shift.x;}}
if(shift.top=(vertical===SHIFT&&!!adjust.top)){if(newCorner.y===CENTER){css['margin-top']=shift.y=offset['margin-top']-adjust.top;}
else{props=offset.bottom!==undefined?[adjust.top,-offset.top]:[-adjust.top,offset.top];if((shift.y=Math.max(props[0],props[1]))>props[0]){pos.top-=adjust.top;shift.top=FALSE;}
css[offset.bottom!==undefined?BOTTOM:TOP]=shift.y;}}
elems.tip.css(css).toggle(!((shift.x&&shift.y)||(newCorner.x===CENTER&&shift.y)||(newCorner.y===CENTER&&shift.x)));pos.left-=offset.left.charAt?offset.user:horizontal!==SHIFT||shift.top||!shift.left&&!shift.top?offset.left:0;pos.top-=offset.top.charAt?offset.user:vertical!==SHIFT||shift.left||!shift.left&&!shift.top?offset.top:0;cache.left=adjust.left;cache.top=adjust.top;cache.corner=newCorner.clone();}
function borderWidth(corner,side,backup){side=!side?corner[corner.precedance]:side;var isFluid=tooltip.hasClass(fluidClass),isTitleTop=elems.titlebar&&corner.y===TOP,elem=isTitleTop?elems.titlebar:elems.tooltip,css='border-'+side+'-width',val;tooltip.addClass(fluidClass);val=parseInt(elem.css(css),10);val=(backup?val||parseInt(tooltip.css(css),10):val)||0;tooltip.toggleClass(fluidClass,isFluid);return val;}
function borderRadius(corner){var isTitleTop=elems.titlebar&&corner.y===TOP,elem=isTitleTop?elems.titlebar:elems.content,moz=$.browser.mozilla,prefix=moz?'-moz-':$.browser.webkit?'-webkit-':'',nonStandard='border-radius-'+corner.y+corner.x,standard='border-'+corner.y+'-'+corner.x+'-radius';function r(c){return parseInt(elem.css(c),10)||parseInt(tooltip.css(c),10);}
return r(standard)||r(prefix+standard)||r(prefix+nonStandard)||r(nonStandard)||0;}
function calculateSize(corner){var y=corner.precedance===Y,width=size[y?WIDTH:HEIGHT],height=size[y?HEIGHT:WIDTH],isCenter=corner.string().indexOf(CENTER)>-1,base=width*(isCenter?0.5:1),pow=Math.pow,round=Math.round,bigHyp,ratio,result,smallHyp=Math.sqrt(pow(base,2)+pow(height,2)),hyp=[(border/base)*smallHyp,(border/height)*smallHyp];hyp[2]=Math.sqrt(pow(hyp[0],2)-pow(border,2));hyp[3]=Math.sqrt(pow(hyp[1],2)-pow(border,2));bigHyp=smallHyp+hyp[2]+hyp[3]+(isCenter?0:hyp[0]);ratio=bigHyp/smallHyp;result=[round(ratio*height),round(ratio*width)];return{height:result[y?0:1],width:result[y?1:0]};}
$.extend(self,{init:function()
{var enabled=self.detectCorner()&&(hasCanvas||$.browser.msie);if(enabled){self.create();self.update();tooltip.unbind(namespace).bind('tooltipmove'+namespace,reposition);}
return enabled;},detectCorner:function()
{var corner=opts.corner,posOptions=qTip.options.position,at=posOptions.at,my=posOptions.my.string?posOptions.my.string():posOptions.my;if(corner===FALSE||(my===FALSE&&at===FALSE)){return FALSE;}
else{if(corner===TRUE){self.corner=new PLUGINS.Corner(my);}
else if(!corner.string){self.corner=new PLUGINS.Corner(corner);self.corner.fixed=TRUE;}}
cache.corner=new PLUGINS.Corner(self.corner.string());return self.corner.string()!=='centercenter';},detectColours:function(actual){var i,fill,border,tip=elems.tip.css('cssText',''),corner=actual||self.corner,precedance=corner[corner.precedance],borderSide='border-'+precedance+'-color',borderSideCamel='border'+precedance.charAt(0)+precedance.substr(1)+'Color',invalid=/rgba?\(0, 0, 0(, 0)?\)|transparent|#123456/i,backgroundColor='background-color',transparent='transparent',important=' !important',useTitle=elems.titlebar&&(corner.y===TOP||(corner.y===CENTER&&tip.position().top+(size.height/2)+opts.offset<elems.titlebar.outerHeight(1))),colorElem=useTitle?elems.titlebar:elems.tooltip;tooltip.addClass(fluidClass);color.fill=fill=tip.css(backgroundColor);color.border=border=tip[0].style[borderSideCamel]||tip.css(borderSide)||tooltip.css(borderSide);if(!fill||invalid.test(fill)){color.fill=colorElem.css(backgroundColor)||transparent;if(invalid.test(color.fill)){color.fill=tooltip.css(backgroundColor)||fill;}}
if(!border||invalid.test(border)||border===$(document.body).css('color')){color.border=colorElem.css(borderSide)||transparent;if(invalid.test(color.border)||color.border===colorElem.css('color')){color.border=tooltip.css(borderSide)||tooltip.css(borderSideCamel)||border;}}
$('*',tip).add(tip).css('cssText',backgroundColor+':'+transparent+important+';border:0'+important+';');tooltip.removeClass(fluidClass);},create:function()
{var width=size.width,height=size.height,vml;if(elems.tip){elems.tip.remove();}
elems.tip=$('<div />',{'class':'ui-tooltip-tip'}).css({width:width,height:height}).prependTo(tooltip);if(hasCanvas){$('<canvas />').appendTo(elems.tip)[0].getContext('2d').save();}
else{vml='<vml:shape coordorigin="0,0" style="display:inline-block; position:absolute; behavior:url(#default#VML);"></vml:shape>';elems.tip.html(vml+vml);$('*',elems.tip).bind('click mousedown',function(event){event.stopPropagation();});}},update:function(corner,position)
{var tip=elems.tip,inner=tip.children(),width=size.width,height=size.height,regular='px solid ',transparent='px dashed transparent',mimic=opts.mimic,round=Math.round,precedance,context,coords,translate,newSize;if(!corner){corner=cache.corner||self.corner;}
if(mimic===FALSE){mimic=corner;}
else{mimic=new PLUGINS.Corner(mimic);mimic.precedance=corner.precedance;if(mimic.x==='inherit'){mimic.x=corner.x;}
else if(mimic.y==='inherit'){mimic.y=corner.y;}
else if(mimic.x===mimic.y){mimic[corner.precedance]=corner[corner.precedance];}}
precedance=mimic.precedance;if(corner.precedance===X){swapDimensions();}
else{resetDimensions();}
elems.tip.css({width:(width=size.width),height:(height=size.height)});self.detectColours(corner);if(color.border!=='transparent'){border=borderWidth(corner,NULL,TRUE);if(opts.border===0&&border>0){color.fill=color.border;}
self.border=border=opts.border!==TRUE?opts.border:border;}
else{self.border=border=0;}
coords=calculateTip(mimic,width,height);self.size=newSize=calculateSize(corner);tip.css(newSize);if(corner.precedance===Y){translate=[round(mimic.x===LEFT?border:mimic.x===RIGHT?newSize.width-width-border:(newSize.width-width)/2),round(mimic.y===TOP?newSize.height-height:0)];}
else{translate=[round(mimic.x===LEFT?newSize.width-width:0),round(mimic.y===TOP?border:mimic.y===BOTTOM?newSize.height-height-border:(newSize.height-height)/2)];}
if(hasCanvas){inner.attr(newSize);context=inner[0].getContext('2d');context.restore();context.save();context.clearRect(0,0,3000,3000);context.fillStyle=color.fill;context.strokeStyle=color.border;context.lineWidth=border*2;context.lineJoin='miter';context.miterLimit=100;context.translate(translate[0],translate[1]);context.beginPath();context.moveTo(coords[0][0],coords[0][1]);context.lineTo(coords[1][0],coords[1][1]);context.lineTo(coords[2][0],coords[2][1]);context.closePath();if(border){if(tooltip.css('background-clip')==='border-box'){context.strokeStyle=color.fill;context.stroke();}
context.strokeStyle=color.border;context.stroke();}
context.fill();}
else{coords='m'+coords[0][0]+','+coords[0][1]+' l'+coords[1][0]+','+coords[1][1]+' '+coords[2][0]+','+coords[2][1]+' xe';translate[2]=border&&/^(r|b)/i.test(corner.string())?parseFloat($.browser.version,10)===8?2:1:0;inner.css({antialias:''+(mimic.string().indexOf(CENTER)>-1),left:translate[0]-(translate[2]*Number(precedance===X)),top:translate[1]-(translate[2]*Number(precedance===Y)),width:width+border,height:height+border}).each(function(i){var $this=$(this);$this[$this.prop?'prop':'attr']({coordsize:(width+border)+' '+(height+border),path:coords,fillcolor:color.fill,filled:!!i,stroked:!i}).css({display:border||i?'block':'none'});if(!i&&$this.html()===''){$this.html('<vml:stroke weight="'+(border*2)+'px" color="'+color.border+'" miterlimit="1000" joinstyle="miter" '+' style="behavior:url(#default#VML); display:inline-block;" />');}});}
if(position!==FALSE){self.position(corner);}},position:function(corner)
{var tip=elems.tip,position={},userOffset=Math.max(0,opts.offset),precedance,dimensions,corners;if(opts.corner===FALSE||!tip){return FALSE;}
corner=corner||self.corner;precedance=corner.precedance;dimensions=calculateSize(corner);corners=[corner.x,corner.y];if(precedance===X){corners.reverse();}
$.each(corners,function(i,side){var b,br;if(side===CENTER){b=precedance===Y?LEFT:TOP;position[b]='50%';position['margin-'+b]=-Math.round(dimensions[precedance===Y?WIDTH:HEIGHT]/2)+userOffset;}
else{b=borderWidth(corner,side);br=borderRadius(corner);position[side]=i?0:(userOffset+(br>b?br:-b));}});position[corner[precedance]]-=dimensions[precedance===X?WIDTH:HEIGHT];tip.css({top:'',bottom:'',left:'',right:'',margin:''}).css(position);return position;},destroy:function()
{if(elems.tip){elems.tip.remove();}
elems.tip=false;tooltip.unbind(namespace);}});self.init();}
PLUGINS.tip=function(api)
{var self=api.plugins.tip;return'object'===typeof self?self:(api.plugins.tip=new Tip(api));};PLUGINS.tip.initialize='render';PLUGINS.tip.sanitize=function(options)
{var style=options.style,opts;if(style&&'tip'in style){opts=options.style.tip;if(typeof opts!=='object'){options.style.tip={corner:opts};}
if(!(/string|boolean/i).test(typeof opts.corner)){opts.corner=TRUE;}
if(typeof opts.width!=='number'){delete opts.width;}
if(typeof opts.height!=='number'){delete opts.height;}
if(typeof opts.border!=='number'&&opts.border!==TRUE){delete opts.border;}
if(typeof opts.offset!=='number'){delete opts.offset;}}};$.extend(TRUE,QTIP.defaults,{style:{tip:{corner:TRUE,mimic:FALSE,width:6,height:6,border:TRUE,offset:0}}});function Modal(api)
{var self=this,options=api.options.show.modal,elems=api.elements,tooltip=elems.tooltip,overlaySelector='#qtip-overlay',globalNamespace='.qtipmodal',namespace=globalNamespace+api.id,attr='is-modal-qtip',docBody=$(document.body),focusableSelector=PLUGINS.modal.focusable.join(','),focusableElems={},overlay;api.checks.modal={'^show.modal.(on|blur)$':function(){self.init();elems.overlay.toggle(tooltip.is(':visible'));},'^content.text$':function(){updateFocusable();}};function updateFocusable(){focusableElems=$(focusableSelector,tooltip).not('[disabled]').map(function(){return typeof this.focus==='function'?this:null;});}
function focusInputs(blurElems){if(focusableElems.length<1&&blurElems.length){blurElems.not('body').blur();}
else{focusableElems.first().focus();}}
function stealFocus(event){var target=$(event.target),container=target.closest('.qtip'),targetOnTop;targetOnTop=container.length<1?FALSE:(parseInt(container[0].style.zIndex,10)>parseInt(tooltip[0].style.zIndex,10));if(!targetOnTop&&($(event.target).closest(selector)[0]!==tooltip[0])){focusInputs(target);}}
$.extend(self,{init:function()
{if(!options.on){return self;}
overlay=self.create();tooltip.attr(attr,TRUE).css('z-index',PLUGINS.modal.zindex+$(selector+'['+attr+']').length).unbind(globalNamespace).unbind(namespace).bind('tooltipshow'+globalNamespace+' tooltiphide'+globalNamespace,function(event,api,duration){var oEvent=event.originalEvent;if(event.target===tooltip[0]){if(oEvent&&event.type==='tooltiphide'&&/mouse(leave|enter)/.test(oEvent.type)&&$(oEvent.relatedTarget).closest(overlay[0]).length){try{event.preventDefault();}catch(e){}}
else if(!oEvent||(oEvent&&!oEvent.solo)){self[event.type.replace('tooltip','')](event,duration);}}}).bind('tooltipfocus'+globalNamespace,function(event){if(event.isDefaultPrevented()||event.target!==tooltip[0]){return;}
var qtips=$(selector).filter('['+attr+']'),newIndex=PLUGINS.modal.zindex+qtips.length,curIndex=parseInt(tooltip[0].style.zIndex,10);overlay[0].style.zIndex=newIndex-2;qtips.each(function(){if(this.style.zIndex>curIndex){this.style.zIndex-=1;}});qtips.end().filter('.'+focusClass).qtip('blur',event.originalEvent);tooltip.addClass(focusClass)[0].style.zIndex=newIndex;try{event.preventDefault();}catch(e){}}).bind('tooltiphide'+globalNamespace,function(event){if(event.target===tooltip[0]){$('['+attr+']').filter(':visible').not(tooltip).last().qtip('focus',event);}});if(options.escape){$(document).unbind(namespace).bind('keydown'+namespace,function(event){if(event.keyCode===27&&tooltip.hasClass(focusClass)){api.hide(event);}});}
if(options.blur){elems.overlay.unbind(namespace).bind('click'+namespace,function(event){if(tooltip.hasClass(focusClass)){api.hide(event);}});}
updateFocusable();return self;},create:function()
{var elem=$(overlaySelector);if(elem.length){return(elems.overlay=elem.insertAfter($(selector).last()));}
overlay=elems.overlay=$('<div />',{id:overlaySelector.substr(1),html:'<div></div>',mousedown:function(){return FALSE;}}).hide().insertAfter($(selector).last());function resize(){overlay.css({height:$(window).height(),width:$(window).width()});}
$(window).unbind(globalNamespace).bind('resize'+globalNamespace,resize);resize();return overlay;},toggle:function(event,state,duration)
{if(event&&event.isDefaultPrevented()){return self;}
var effect=options.effect,type=state?'show':'hide',visible=overlay.is(':visible'),modals=$('['+attr+']').filter(':visible').not(tooltip),zindex;if(!overlay){overlay=self.create();}
if((overlay.is(':animated')&&visible===state)||(!state&&modals.length)){return self;}
if(state){overlay.css({left:0,top:0});overlay.toggleClass('blurs',options.blur);if(options.stealfocus!==FALSE){docBody.bind('focusin'+namespace,stealFocus);focusInputs($('body *'));}}
else{docBody.unbind('focusin'+namespace);}
overlay.stop(TRUE,FALSE);if($.isFunction(effect)){effect.call(overlay,state);}
else if(effect===FALSE){overlay[type]();}
else{overlay.fadeTo(parseInt(duration,10)||90,state?1:0,function(){if(!state){$(this).hide();}});}
if(!state){overlay.queue(function(next){overlay.css({left:'',top:''});next();});}
return self;},show:function(event,duration){return self.toggle(event,TRUE,duration);},hide:function(event,duration){return self.toggle(event,FALSE,duration);},destroy:function()
{var delBlanket=overlay;if(delBlanket){delBlanket=$('['+attr+']').not(tooltip).length<1;if(delBlanket){elems.overlay.remove();$(document).unbind(globalNamespace);}
else{elems.overlay.unbind(globalNamespace+api.id);}
docBody.undelegate('*','focusin'+namespace);}
return tooltip.removeAttr(attr).unbind(globalNamespace);}});self.init();}
PLUGINS.modal=function(api){var self=api.plugins.modal;return'object'===typeof self?self:(api.plugins.modal=new Modal(api));};PLUGINS.modal.initialize='render';PLUGINS.modal.sanitize=function(opts){if(opts.show){if(typeof opts.show.modal!=='object'){opts.show.modal={on:!!opts.show.modal};}
else if(typeof opts.show.modal.on==='undefined'){opts.show.modal.on=TRUE;}}};PLUGINS.modal.zindex=QTIP.zindex+1000;PLUGINS.modal.focusable=['a[href]','area[href]','input','select','textarea','button','iframe','object','embed','[tabindex]','[contenteditable]'];$.extend(TRUE,QTIP.defaults,{show:{modal:{on:FALSE,effect:TRUE,blur:TRUE,stealfocus:TRUE,escape:TRUE}}});PLUGINS.imagemap=function(api,area,corner,adjustMethod)
{if(!area.jquery){area=$(area);}
var cache=(api.cache.areas={}),shape=(area[0].shape||area.attr('shape')).toLowerCase(),coordsString=area[0].coords||area.attr('coords'),baseCoords=coordsString.split(','),coords=[],image=$('img[usemap="#'+area.parent('map').attr('name')+'"]'),imageOffset=image.offset(),result={width:0,height:0,position:{top:1e10,right:0,bottom:0,left:1e10}},i=0,next=0,dimensions;function polyCoordinates(result,coords,corner)
{var i=0,compareX=1,compareY=1,realX=0,realY=0,newWidth=result.width,newHeight=result.height;while(newWidth>0&&newHeight>0&&compareX>0&&compareY>0)
{newWidth=Math.floor(newWidth/2);newHeight=Math.floor(newHeight/2);if(corner.x===LEFT){compareX=newWidth;}
else if(corner.x===RIGHT){compareX=result.width-newWidth;}
else{compareX+=Math.floor(newWidth/2);}
if(corner.y===TOP){compareY=newHeight;}
else if(corner.y===BOTTOM){compareY=result.height-newHeight;}
else{compareY+=Math.floor(newHeight/2);}
i=coords.length;while(i--)
{if(coords.length<2){break;}
realX=coords[i][0]-result.position.left;realY=coords[i][1]-result.position.top;if((corner.x===LEFT&&realX>=compareX)||(corner.x===RIGHT&&realX<=compareX)||(corner.x===CENTER&&(realX<compareX||realX>(result.width-compareX)))||(corner.y===TOP&&realY>=compareY)||(corner.y===BOTTOM&&realY<=compareY)||(corner.y===CENTER&&(realY<compareY||realY>(result.height-compareY)))){coords.splice(i,1);}}}
return{left:coords[0][0],top:coords[0][1]};}
imageOffset.left+=Math.ceil((image.outerWidth()-image.width())/2);imageOffset.top+=Math.ceil((image.outerHeight()-image.height())/2);if(shape==='poly'){i=baseCoords.length;while(i--)
{next=[parseInt(baseCoords[--i],10),parseInt(baseCoords[i+1],10)];if(next[0]>result.position.right){result.position.right=next[0];}
if(next[0]<result.position.left){result.position.left=next[0];}
if(next[1]>result.position.bottom){result.position.bottom=next[1];}
if(next[1]<result.position.top){result.position.top=next[1];}
coords.push(next);}}
else{i=-1;while(i++<baseCoords.length){coords.push(parseInt(baseCoords[i],10));}}
switch(shape)
{case'rect':result={width:Math.abs(coords[2]-coords[0]),height:Math.abs(coords[3]-coords[1]),position:{left:Math.min(coords[0],coords[2]),top:Math.min(coords[1],coords[3])}};break;case'circle':result={width:coords[2]+2,height:coords[2]+2,position:{left:coords[0],top:coords[1]}};break;case'poly':result.width=Math.abs(result.position.right-result.position.left);result.height=Math.abs(result.position.bottom-result.position.top);if(corner.abbrev()==='c'){result.position={left:result.position.left+(result.width/2),top:result.position.top+(result.height/2)};}
else{if(!cache[corner+coordsString]){result.position=polyCoordinates(result,coords.slice(),corner);if(adjustMethod&&(adjustMethod[0]==='flip'||adjustMethod[1]==='flip')){result.offset=polyCoordinates(result,coords.slice(),{x:corner.x===LEFT?RIGHT:corner.x===RIGHT?LEFT:CENTER,y:corner.y===TOP?BOTTOM:corner.y===BOTTOM?TOP:CENTER});result.offset.left-=result.position.left;result.offset.top-=result.position.top;}
cache[corner+coordsString]=result;}
result=cache[corner+coordsString];}
result.width=result.height=0;break;}
result.position.left+=imageOffset.left;result.position.top+=imageOffset.top;return result;};PLUGINS.svg=function(api,svg,corner,adjustMethod)
{var doc=$(document),elem=svg[0],result={width:0,height:0,position:{top:1e10,left:1e10}},box,mtx,root,point,tPoint;while(!elem.getBBox){elem=elem.parentNode;}
if(elem.getBBox&&elem.parentNode){box=elem.getBBox();mtx=elem.getScreenCTM();root=elem.farthestViewportElement||elem;if(!root.createSVGPoint){return result;}
point=root.createSVGPoint();point.x=box.x;point.y=box.y;tPoint=point.matrixTransform(mtx);result.position.left=tPoint.x;result.position.top=tPoint.y;point.x+=box.width;point.y+=box.height;tPoint=point.matrixTransform(mtx);result.width=tPoint.x-result.position.left;result.height=tPoint.y-result.position.top;result.position.left+=doc.scrollLeft();result.position.top+=doc.scrollTop();}
return result;};function BGIFrame(api)
{var self=this,elems=api.elements,tooltip=elems.tooltip,namespace='.bgiframe-'+api.id;$.extend(self,{init:function()
{elems.bgiframe=$('<iframe class="ui-tooltip-bgiframe" frameborder="0" tabindex="-1" src="javascript:\'\';" '+' style="display:block; position:absolute; z-index:-1; filter:alpha(opacity=0); '+'-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";"></iframe>');elems.bgiframe.appendTo(tooltip);tooltip.bind('tooltipmove'+namespace,self.adjust);},adjust:function()
{var dimensions=api.get('dimensions'),plugin=api.plugins.tip,tip=elems.tip,tipAdjust,offset;offset=parseInt(tooltip.css('border-left-width'),10)||0;offset={left:-offset,top:-offset};if(plugin&&tip){tipAdjust=(plugin.corner.precedance==='x')?['width','left']:['height','top'];offset[tipAdjust[1]]-=tip[tipAdjust[0]]();}
elems.bgiframe.css(offset).css(dimensions);},destroy:function()
{elems.bgiframe.remove();tooltip.unbind(namespace);}});self.init();}
PLUGINS.bgiframe=function(api)
{var browser=$.browser,self=api.plugins.bgiframe;if($('select, object').length<1||!(browser.msie&&(''+browser.version).charAt(0)==='6')){return FALSE;}
return'object'===typeof self?self:(api.plugins.bgiframe=new BGIFrame(api));};PLUGINS.bgiframe.initialize='render';}));