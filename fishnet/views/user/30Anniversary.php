<!--?php
	$vimeoID = preg_replace("/[^\d]+/", '', $revision['revision_text']["videoURL"]); // -- http://vimeo.com/ID
	$width = val($params['w'], 759);
	$height = val($params['h'], 427);
	$protocol = $this->config->item('protocol');
?-->
<head>
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/user/30Anniversary.css" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/basic.css" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/galleriffic-3.css" />
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.history.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.galleriffic.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.opacityrollover.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/templates/user/30Anniversary.js"></script>



<!--script type="text/javascript">
	document.write('<style>.noscript {display: none; }</style>');	
</script-->
</head>
<div class="primary">

	<div class="header-a">
	<h2><b>Our People: The Heart at the Center</b></h2>
	</div>
	<br>
	<div class="header-c">
	<h3><b>Love Stories</b></h3>
	</div>
	<br>
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
					<a class="thumb" name="eileen" href="https://farm8.staticflickr.com/7482/15966021056_070bc9f706_b.jpg" title="1984">
						<img src="/assets/images/01_Eileen_1984_thumb.jpg" alt="1984" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="sigi" href="https://farm8.staticflickr.com/7567/15792967558_58e381fdd7_b.jpg" title="1985">
						<img src="/assets/images/02_Sigi_1985_thumb.jpg" alt="1985" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="diane" href="https://farm8.staticflickr.com/7544/15951224346_5dd93729b5_b.jpg" title="1986">
						<img src="/assets/images/03_Diane_1986_thumb.jpg" alt="1986" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="nelson" href="https://farm8.staticflickr.com/7515/15794626317_7efc493b92_b.jpg" title="1987">
						<img src="/assets/images/04_Nelson_1987_thumb.jpg" alt="1987" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="kaeo" href="https://farm8.staticflickr.com/7507/15791257947_c81e21e4c0_b.jpg" title="1988">
						<img src="/assets/images/05_Kaeo_1988_thumb.jpg" alt="1988" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="helen" href="https://farm8.staticflickr.com/7528/15951224326_3a3e4dd476_b.jpg" title="1989">
						<img src="/assets/images/06_Helen_1989_thumb.jpg" alt="1989" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="khaja" href="https://farm9.staticflickr.com/8641/16026407651_4309db8678_b.jpg" title="1991">
						<img src="/assets/images/07_khaja_1991_thumb.jpg" alt="1991" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="laurenB" href="https://farm8.staticflickr.com/7519/15954898346_e3a3e15a63_b.jpg" title="1992">
						<img src="/assets/images/08_LaurenB_1992_thumb.jpg" alt="1992" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="amy" href="https://farm8.staticflickr.com/7486/15976322922_4c219003bb_b.jpg" title="1993">
						<img src="/assets/images/10_Amy_1993_thumb.jpg" alt="1993" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="cathy" href="https://farm9.staticflickr.com/8581/15793278238_d0d6da7416_b.jpg" title="1994">
						<img src="/assets/images/11_Cathy_1994_thumb.jpg" alt="1994" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="hilary" href="https://farm9.staticflickr.com/8599/15976322902_31a6bd4aea_b.jpg" title="1995">
						<img src="/assets/images/12_Hilary_1995_thumb.png" alt="1995" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="mike" href="https://farm8.staticflickr.com/7580/15975001781_4745219375_b.jpg" title="1996">
						<img src="/assets/images/13_Michael_1996_thumb.jpg" alt="1996" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="laurenC" href="https://farm9.staticflickr.com/8574/15791257767_691e0f9697_b.jpg" title="1997">
						<img src="/assets/images/14_LaurenC_1997_thumb.jpg" alt="1997" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="yohanny" href="https://farm8.staticflickr.com/7467/15978744871_efe732ba69_b.jpg" title="1998">
						<img src="/assets/images/15_Yohanny_1998_thumb.jpg" alt="1998" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="ermine" href="https://farm8.staticflickr.com/7533/15357362053_0e6ee34e15_b.jpg" title="1999">
						<img src="/assets/images/16_Ermine_1999_thumb.jpg" alt="1999" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="jennH" href="https://farm9.staticflickr.com/8667/15975001721_58e2db9d94_b.jpg" title="2000">
						<img src="/assets/images/17_JennH_2000_thumb.jpg" alt="2000" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="kimberly" href="https://farm8.staticflickr.com/7552/15790974639_7378b2d96b_b.jpg" title="2001">
						<img src="/assets/images/18_Kim_2001_thumb.jpg" alt="2001" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="clarence" href="https://farm8.staticflickr.com/7495/15976972985_0670d356e4_b.jpg" title="2002">
						<img src="/assets/images/19_Clarence_2002_thumb.jpg" alt="2002" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="paul" href="https://farm8.staticflickr.com/7557/15789697720_97b35ab5f6_b.jpg" title="2003">
						<img src="/assets/images/20_Paul_2003_thumb.jpg" alt="2003" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="olivia" href="https://farm9.staticflickr.com/8576/15793345178_16548dc07f_b.jpg" title="2004">
						<img src="/assets/images/21_Olivia_2004_thumb.jpg" alt="2004" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="heidi" href="https://farm8.staticflickr.com/7531/15357361973_522581d900_b.jpg" title="2005">
						<img src="/assets/images/22_Heidi_2005_thumb.jpg" alt="2005" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="laurenP" href="https://farm9.staticflickr.com/8577/15976322712_cc6ee85849_b.jpg" title="2006">
						<img src="/assets/images/22_laurenP_2006_thumb.jpg" alt="2006" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="roslyne" href="https://farm8.staticflickr.com/7541/15793371718_d22d0b5594_b.jpg" title="2007">
						<img src="/assets/images/23_roslyne_2007_thumb.jpg" alt="2007" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="jennD" href="https://farm8.staticflickr.com/7493/15790974559_44c3d41b60_b.jpg" title="2008">
						<img src="/assets/images/24_JennD_2008_thumb.jpg" alt="2008" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="meggie" href="https://farm8.staticflickr.com/7562/15789605628_87a0391b40_b.jpg" title="2009">
						<img src="/assets/images/25_Meggie_2009_thumb.jpg" alt="2009" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="vanessa" href="https://farm9.staticflickr.com/8567/15805908697_657dec4b5e_b.jpg" title="2010">
						<img src="/assets/images/26_Vanessa_2010_thumb.jpg" alt="2010" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="jennB" href="https://farm8.staticflickr.com/7575/15982093371_0e8be4e1a7_b.jpg" title="2011">
						<img src="/assets/images/27_JennB_2011_thumb.jpg" alt="2011" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="lakeisha" href="https://farm8.staticflickr.com/7564/15364467753_b4e594d7cc_b.jpg" title="2012">
						<img src="/assets/images/28_laKiesha_2012_thumb.jpg" alt="2012" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="jennJ" href="https://farm8.staticflickr.com/7545/15805640059_9f827c05fd_b.jpg" title="2013">
						<img src="/assets/images/29_JennJ_2013_thumb.jpg" alt="2013" height="60" width="60"/>
					</a>
				</li>
				<li>
					<a class="thumb" name="chi" href="https://farm9.staticflickr.com/8657/15357361933_8e49f75513_b.jpg" title="2014">
						<img src="/assets/images/30_Chiron_2014_thumb.jpg" alt="2014" height="60" width="60"/>
					</a>
				</li>
				

			</ul>
		</div>
		<!-- End Advanced Gallery Html containers-->
		<div style="clear: both;"></div>
	</div>
	<br><br>
	<div class="header-c">
	<h3><b>Reel Love</b></h3>
	</div>
	<br>
	<div class="columns double-g">
		<div class="col">
			<div id="mainvideos">
				<video id="videoarea" controls="controls" poster="<?=STATIC_URL?>images/30thvideo.png" src=""></video>
			</div>
		</div>
		
		<div class="col">
			<div id="minivideos">
				<div id="playlist">
					<li movieurl="<?=STATIC_URL?>videos/30th_Anniversary_Master.mp4"
					movieposter="<?=STATIC_URL?>images/30thvideo.png">
						<img src="<?=STATIC_URL?>images/30th_people_video_cover.png">
						<p>30th Anniversary Video </p>
					</li>

					<br><br><br>
					<li movieurl="<?=STATIC_URL?>videos/30th_anniversary_company_timeline.mp4">
						<img src="<?=STATIC_URL?>images/30thvideo.png">
						<p>Company Timeline Slideshow</p>
					</li>

				</div>
			</div>
		</div>
	</div>
	
	<!--video id="videoarea" controls="controls" poster="" src="" ></video>
	
	<div id="playlist">
		<li movieurl="<?=STATIC_URL?>videos/30thvideo.mp4"
			movieposter="<?=STATIC_URL?>images/30thvideo.png">
			<img src="<?=STATIC_URL?>images/Meggy_2009_thumb.jpg">
			<p>30th People </p>
		</li>
						
		<li movieurl="<?=STATIC_URL?>videos/30year_facts.mp4">
			<img src="<?=STATIC_URL?>images/30thvideo.png">
			<p>30th Timeline</p>
			</li>
	</div-->	

	<div class="columns double-a">
		<div class="col">
			<div id="ecommerce">
				<a href="http://www.eileenfisher.com/EileenFisher/collection/resort/our_30th_anniversary.jsp" rel="external">
				<img src="/assets/images/ecom_30.jpg" alt="mission" width="70" height="100" style="border: 1px solid transparent;"></a>
			</div>		
		</div>
		
		<div class="col">
			<div id="fishnet_tp">
				<a href="/departments/3471">
				<img src="/assets/images/ef_badge_30_heart_black.jpg" alt="foundation" width="120" style="border: 1px solid transparent;"></a>
			</div>
		</div>
		
		<!-- video playlist on the bottom with the 2 30th icons-->
		
			<!--div id="playlist">
				<li movieurl="<?=STATIC_URL?>videos/30thvideo.mp4"
					movieposter="<?=STATIC_URL?>images/30thvideo.png">
					<img src="<?=STATIC_URL?>images/Meggy_2009_thumb.jpg">
					<p>30th People </p>
				</li>
								
				<li movieurl="<?=STATIC_URL?>videos/30year_facts.mp4">
					<img src="<?=STATIC_URL?>images/30thvideo.png">
					<p>30th Timeline</p>
				</li>
			</div-->	
	</div>

</div>

<!--/ #primary -->

<script>
	coreEngine = {
		canWrite: <?=(isset($canWrite) and $canWrite) ? 'true' : 'false'; ?>
	}
</script>

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
                                        numThumbs:                 18,
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

