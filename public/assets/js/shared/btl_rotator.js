/**
 * User: cravelo
 * Date: 9/27/12
 * Time: 1:38 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global define */

define(['jquery', 'lib/jquery.cycle.all'], function($){
	'use strict';

	/* Rotator with 3 thumbs on the right each with its own subtitle */
	return $('.rotator-d').each(function(){
		var $container = $(this),
			$items = $('.items', $container),
			$mainRotatorContainer = $('<div class="primary">'),
			$mainRotator = $('<ul>'),
			$temp;

		if ($container.data('rotator-d') === true){ return false; }
		$container.data('rotator-d', true);

		$items.wrapAll('<div class="secondary">');

		$('.thumb', $items).each(function(i){
			$(this).data('index', i+1);
		});

		/* Equal height thumbs */
		$('li', $items).each(function(){
			$('.item', this).equalHeights(127);
		});

		/* Main content rotator */
		$('.item', $items).each(function(){
			$temp = $('<li />').append($('.main', this));
			$mainRotator.append($temp);
		}).filter(':first').find('a.item-link').addClass('active');

		$mainRotatorContainer.append($mainRotator);
		$mainRotatorContainer.prependTo($container);

		$('a.item-link', $items).bind('mouseover', function() {
			//if($mainRotator.is(':animated')) return false;
			$('.active', $items).removeClass('active');
			$(this).addClass('active');
		});

		$container.show();

		$mainRotatorContainer.find('ul').cycle({
			fx: 'scrollHorz',
			allowPagerClickBubble: true,
			speed: 'fast',
			easing: 'easeInOutQuad',
			timeout: 0,
			pager:  '.items ul',
			pagerEvent: 'mouseenter',
			pagerAnchorBuilder: function(idx) {
				// return sel string for existing anchor
				return '.items ul a.item-link:eq(' + (idx) + ')';
			}
		});

		$("li", $mainRotator).equalHeights(511);

		return true;
	});
});
