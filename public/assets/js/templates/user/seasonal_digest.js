require(['jquery', 'lib/jquery.maskedinput-1.2.2'], function($) {
    'use strict';
    var siteRoot = coreEngine.siteRoot.substring(0, coreEngine.siteRoot.length - 1),
        Engine = {
            base_url: siteRoot + $('aside form').attr('action') + '/',
            search: function() {
                $('aside form').submit(function() {
                    return false;
                });
                $("#year").mask("9999").keypress(function(e) {
                    if (e.keyCode === 13) {
                        $("#btnChangeSeason").click();
                    }
                    return false;
                });
                $("#btnChangeSeason").click(function() {
                    document.location = Engine.base_url + $(".select-month p.current").text() + "/" + $("#year").val();
                });
            },
            sorting: function() {
                var url = document.location.toString() + '/',
                    recent, page_views, comments_count;
                url = url.replace(Engine.base_url, '');
                url = url.split('/');
                recent = (url[2] !== 'comments_count') && (url[2] !== 'page_views') ? 'active' : '';
                comments_count = (url[2] === 'comments_count') ? 'active' : '';
                page_views = (url[2] === 'page_views') ? 'active' : '';
                $(".sort-link .recent").addClass(recent).click(function() {
                    if (!$(this).is('.active')) {
                        document.location = Engine.base_url + url[0] + '/' + url[1];
                    }
                    return false;
                });
                $(".sort-link .popular").addClass(page_views).click(function() {
                    var date = $(".header-a span.season").text() + '/' + $(".header-a span.year").text();
                    if ($(this).is('.active')) {
                        return false;
                    }
                    document.location = Engine.base_url + date + "/page_views";
                    return false;
                });
                $(".sort-link .comments").addClass(comments_count).click(function() {
                    var date = $(".header-a span.season").text() + '/' + $(".header-a span.year").text();
                    if ($(this).is('.active')) {
                        return false;
                    }
                    document.location = Engine.base_url + date + "/comments_count";
                    return false;
                });
                $('.digest-nav').trigger('mouseleave');
            }
        };
    $(document).ready(function() {
        Engine.search();
        Engine.sorting();
        coreEngine.sortOrder('seasonal');
    });
});