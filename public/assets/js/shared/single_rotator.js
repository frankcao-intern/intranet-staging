/**
 * User: cravelo
 * Date: 9/26/12
 * Time: 12:34 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global define */

/**
 * Photos with captions rotator, e.g. on: Home page
 * @return jQuery Object pointing to the rotator object(s)
 */
define(['jquery', 'lib/jquery.cycle.all'], function($){
	'use strict';

	var $rotator = $('.rotator-a');

	$rotator.each(function(){
		var $container = $(this),
			$rotatorControls = $('.rotator-controls', $container),
			$next = $('.next', $rotatorControls),
			$prev = $('.prev', $rotatorControls),
			$current = $('.min', $rotatorControls),
			$items = $('.items', $container),
			$mainRotatorContainer = $('<div class="primary">'),
			$mainRotator = $('<ul>'),
			$temp;

		if ($container.data('rotator-a') === true){ return false; }
		$container.data('rotator-a', true);

		$items.add($rotatorControls).wrapAll('<div class="secondary">');

		$container.find('.max').text($('.items .thumb', $container).length);
		$current.text('1-' + $('> ul > li:first', $items).find('.item').length);

		$('.thumb', $items).each(function(i){
			$(this).data('index', i+1);
		});

		/* Equal height thumbs */
		$('li', $items).each(function(){
			$('.item', this).equalHeights(127);
		});

		$('> ul > li', $items).equalHeights(383);

		/* Main content rotator */
		$('.item', $items).each(function(){
			$temp = $('<li />').append($('.main', this));
			$mainRotator.append($temp);
		}).filter(':first').find('a').addClass('active');

		$mainRotatorContainer.append($mainRotator).prependTo($container);

		$('div.item a', $items).bind('mouseenter', function() {
			$('.active', $items).removeClass('active');
			$(this).addClass('active');
		});

		$container.show();

		$mainRotator.cycle({
			fx:     'scrollHorz',
			speed:  'medium',
			easing: 'easeInOutQuad',
			timeout: 0,
			pager:  '.items ul',
			activePagerClass: 'active',
			pagerEvent: 'mouseenter',
			allowPagerClickBubble: true,
			pagerAnchorBuilder: function(idx) {
				// return sel string for existing anchor
				return $('div.item:eq(' + idx + ') a', $items);
			}
		});

		/* Thumbnails rotator */
		$('ul', $items).cycle({
			fx:     'scrollHorz',
			speed:  'fast',
			easing: 'easeInOutQuad',
			timeout: 0,
			prev:   $prev,
			next:   $next,
			activePagerClass: '',
			after:  function(curr, next) {
				$current.text($(next).find('.thumb:first').data('index') + '-' +
					$(next).find('.thumb:last').data('index'));
			}
		});

		return true;
	});

	return $rotator;
});
