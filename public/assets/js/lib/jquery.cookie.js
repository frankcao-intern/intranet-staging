
(function($,document,undefined){'use strict';$.cookie=function(key,value,options){var pluses=/\+/g,val=value,raw=function(s){return s;},decoded=function(s){return decodeURIComponent(s.replace(pluses,' '));},opts,days,t,i,parts,decode,cookies;if(val!==undefined&&!/Object/.test(Object.prototype.toString.call(val))){opts=$.extend({},$.cookie.defaults,options);if(val===null){opts.expires=-1;}
if(typeof opts.expires==='number'){days=opts.expires;t=opts.expires=new Date();t.setDate(t.getDate()+days);}
val=String(val);return(document.cookie=[encodeURIComponent(key),'=',opts.raw?val:encodeURIComponent(val),opts.expires?'; expires='+opts.expires.toUTCString():'',opts.path?'; path='+opts.path:'',opts.domain?'; domain='+opts.domain:'',opts.secure?'; secure':''].join(''));}
opts=val||$.cookie.defaults||{};decode=opts.raw?raw:decoded;cookies=document.cookie.split('; ');for(i=0;(parts=cookies[i]&&cookies[i].split('='));i++){if(decode(parts.shift())===key){return decode(parts.join('='));}}
return null;};$.cookie.defaults={};$.removeCookie=function(key,options){if($.cookie(key,options)!==null){$.cookie(key,null,options);return true;}
return false;};}(jQuery,document));