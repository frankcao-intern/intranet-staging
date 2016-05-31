<?php

?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/basic.css" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/galleriffic-3.css" />
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.history.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.galleriffic.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.opacityrollover.js"></script>

<div id="container">

	<!-- Start Advanced Gallery Html Containers -->
	<div id="gallery" class="content">
		<!--div id="controls" class="controls"></div-->
		<div class="slideshow-container">
			<div id="loading" class="loader"></div>
			<div id="slideshow" class="slideshow"></div>
		</div>
		<!--div id="caption" class="caption-container"></div-->
	</div>
	<div id="thumbs" class="navigation">
		<ul class="thumbs">			
                        <li>
				<a class="thumb" name="eight" href="https://c1.staticflickr.com/1/695/22704347529_520864303f_c.jpg" title="eight">
					<img src="/assets/images/strategic_obj/StrategicObj-4_eight_obj.png" alt="eight" height="120" width="200"/>
				</a>
			</li>
                        <li>
				<a class="thumb" name="all" href="https://c1.staticflickr.com/1/659/22704395539_67b5fbc605_c.jpg" title="all">
					<img src="/assets/images/strategic_obj/StrategicObj-14_fullList.png" alt="all" height="120" width="200"/>
				</a>
			</li>                        
                                                
		    </ul>
	</div>
		<!-- End Advanced Gallery Html containers-->
    <div style="clear: both;"></div>
</div>

<script type="text/javascript">
	 jQuery(document).ready(function($) {
                                // We only want these styles applied when javascript is enabled
                                $('div.navigation').css({'width' : '203px', 'float' : 'left'});
                                $('div.content').css('display', 'block');

                                // Initially set opacity on thumbs and add
                                // additional styling for hover effect on thumbs
                                var onMouseOutOpacity = 0.67;
                                $('#thumbs ul.thumbs li').opacityrollover({
                                        mouseOutOpacity:   onMouseOutOpacity,
                                        mouseOverOpacity:  1.0,
                                        fadeSpeed:         'fast',
                                        exemptionSelector: '.selected'
                                });
                                
                                // Initialize Advanced Galleriffic Gallery
                                var gallery = $('#thumbs').galleriffic({
                                        delay:                     2500,
                                        numThumbs:                 3,
                                        preloadAhead:              10,
                                        enableTopPager:            true,
                                        enableBottomPager:         false,
                                        maxPagesToShow:            7,
                                        imageContainerSel:         '#slideshow',
                                        controlsContainerSel:      '#controls',
                                        captionContainerSel:       '#caption',
                                        loadingContainerSel:       '#loading',
                                        renderSSControls:          true,
                                        renderNavControls:         true,
                                       // playLinkText:              'Play Slideshow',
                                      // pauseLinkText:             'Pause Slideshow',
                                     //   prevLinkText:              '&lsaquo; Previous Photo',
                                   //     nextLinkText:              'Next Photo &rsaquo;',
                                        nextPageLinkText:          'Next &rsaquo;',
                                        prevPageLinkText:          '&lsaquo; Prev',
                                        enableHistory:             true,
                                        autoStart:                 false,
                                        syncTransitions:           true,
                                        defaultTransitionDuration: 900,
                                        onSlideChange:             function(prevIndex, nextIndex) {
                                                // 'this' refers to the gallery, which is an extension of $('#thumbs')
                                                this.find('ul.thumbs').children()
                                                        .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
                                                        .eq(nextIndex).fadeTo('fast', 1.0);
                                        },
                                        onPageTransitionOut:       function(callback) {
                                                this.fadeTo('fast', 0.0, callback);
                                        },
                                        onPageTransitionIn:        function() {
                                                this.fadeTo('fast', 1.0);
                                        }
                                });

                                /**** Functions to support integration of galleriffic with the jquery.history plugin ****/

                                // PageLoad function
                                // This function is called when:
                                // 1. after calling $.historyInit();
                                // 2. after calling $.historyLoad();
                                // 3. after pushing "Go Back" button of a browser
                                function pageload(hash) {
                                        // alert("pageload: " + hash);
                                        // hash doesn't contain the first # character.
                                        if(hash) {
                                                $.galleriffic.gotoImage(hash);
                                        } else {
                                                gallery.gotoIndex(0);
                                        }
                                }

                                // Initialize history plugin.
                                // The callback is called at once by present location.hash. 
                                $.historyInit(pageload, "advanced.html");

                                // set onlick event for buttons using the jQuery 1.3 live method
                                $("a[rel='history']").live('click', function(e) {
                                        if (e.button != 0) return true;
                                        
                                        var hash = this.href;
                                        hash = hash.replace(/^.*#/, '');

                                        // moves to a new page. 
                                        // pageload is called at once. 
                                        // hash don't contain "#", "?"
                                        $.historyLoad(hash);

                                        return false;
                                });

                                /****************************************************************************************/
                        });
</script>
