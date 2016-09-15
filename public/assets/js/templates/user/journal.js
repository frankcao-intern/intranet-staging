/*
require(['jquery'],function($){'use strict';var Engine={base_url:function(){var url=document.location.toString()+'/';url=url.replace(coreEngine.siteRoot,'');url=url.split('/');return coreEngine.siteRoot+url[0]+'/'+coreEngine.pageID+'/';},sorting:function(){var url=document.location.toString()+'/',offset,recent,page_views,comments_count;url=url.replace(Engine.base_url,'');url=url.split('/');offset='0';if(url[0]){offset=/\d+/g.test(url[0])?url[0]:offset;}
recent=(url[1]!=='comments_count')&&(url[1]!=='page_views')?'active':'';comments_count=(url[1]==='comments_count')?'active':'';page_views=(url[1]==='page_views')?'active':'';$(".sort-link .recent").addClass(recent).click(function(){if(!$(this).is('.active')){document.location=Engine.base_url+offset;}
return false;});$(".sort-link .popular").addClass(page_views).click(function(){if(!$(this).is('.active')){document.location=Engine.base_url+offset+"/page_views";}
return false;});$(".sort-link .comments").addClass(comments_count).click(function(){if(!$(this).is('.active')){document.location=Engine.base_url+offset+"/comments_count";}
return false;});$(".digest-nav").trigger('mouseleave');}};$(document).ready(function(){Engine.base_url=Engine.base_url();Engine.sorting();});});
*/
require(['jquery'], function($) {
    'use strict';
    var Engine = {
        base_url: function() {
            var url = document.location.toString() + '/';
            url = url.replace(coreEngine.siteRoot, '');
            url = url.split('/');
            return coreEngine.siteRoot + url[0] + '/' + coreEngine.pageID + '/';
        },
        sorting: function() {
            var url = document.location.toString() + '/',
                offset, recent, page_views, comments_count;
            url = url.replace(Engine.base_url, '');
            url = url.split('/');
            offset = '0';
            if (url[0]) {
                offset = /\d+/g.test(url[0]) ? url[0] : offset;
            }
            recent = (url[1] !== 'comments_count') && (url[1] !== 'page_views') ? 'active' : '';
            comments_count = (url[1] === 'comments_count') ? 'active' : '';
            page_views = (url[1] === 'page_views') ? 'active' : '';
            $(".sort-link .recent").addClass(recent).click(function() {
                if (!$(this).is('.active')) {
                    document.location = Engine.base_url + offset;
                }
                return false;
            });
            $(".sort-link .popular").addClass(page_views).click(function() {
                if (!$(this).is('.active')) {
                    document.location = Engine.base_url + offset + "/page_views";
                }
                return false;
            });
            $(".sort-link .comments").addClass(comments_count).click(function() {
                if (!$(this).is('.active')) {
                    document.location = Engine.base_url + offset + "/comments_count";
                }
                return false;
            });
            $(".digest-nav").trigger('mouseleave');
        },

    };

    $(document).ready(function() {
        Engine.base_url = Engine.base_url();
        Engine.sorting();


        jQuery('#someid').sortable({

            axis: 'y',
            stop: function (event, ui) {
                var sortData = $(this).sortable('toArray');
                $('span').text(sortData);
                var postData = "data=" + JSON.stringify(sortData);
                coreEngine.ajax("article/sortOrder/" , postData, coreEngine.genericCallBack, 'json');

                // return msg
                //page_properties.setConfirmUnload(false);

                return false;

            }
        });

        jQuery('#sortOrder').sortable({

            axis: 'y',
            stop: function (event, ui) {
                var sortData = $(this).sortable('toArray');
                $('#after-sort').text(sortData);
                var postData = "data=" + JSON.stringify(sortData);
                coreEngine.ajax("article/sortOrder/" , postData, coreEngine.genericCallBack, 'json');

                // return msg
                //page_properties.setConfirmUnload(false);

                return false;

            }
        });
    });
});