<?php
/**
 * User: cravelo
 * Date: Nov 5, 2010
 * Time: 1:48:27 PM
 *      This is the right column in the homepage, to be included in other pages that don't have a right column oif their own
 */
?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo site_url("/news/rss") ?>"
      xmlns="http://www.w3.org/1999/html"/>

<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery-1.7.2.js">
</script>
<script type="text/javascript">
$(function() {
//comment out when only 1 image is displayed or image will blink 
//setInterval("rotateImages()", 3000);
});
function rotateImages() {
var curPhoto = $("#photoshow div.current");
var nextPhoto = curPhoto.next();
if(nextPhoto.length == 0) {
nextPhoto = $("#photoshow div:first");
}
curPhoto.removeClass('current').addClass('previous');
nextPhoto.css({opacity:0.0}).addClass('current').animate({opacity: 1.0}, 1000, function() {
curPhoto.removeClass('previous');
});

}
</script>
<style>
#photoshow {
width:200px;
height: 90px;
/*border:2px solid #000;*/
overflow:hidden;

}
#photoshow div {
position:absolute;
z-index:0;
width:200px;
height: 90px;
overflow:hidden;
}
#photoshow div.previous {
z-index: 1;
}
#photoshow div.current {
z-index: 2;
}
</style>

<aside class="secondary" >
<br>
	<?php if ($this->session->userdata('show_apps')): ?>
		<!--div class="icons"-->
		<!--div class="icons section-a">
			<?=anchor('priorities', 'Company Priorities', 'class="priorities"')?>
		</div-->

		<!-- old hp right col icons -->
		<!--div class="icons">
			<div>
			<img src='/assets/images/eco.png' alt='eco' style="border: 1px solid transparent;">
			</div>
		</div-->
		
		<div id="photoshow">
			<div class="current">
				<a href='/btl'>
				<img src='/assets/images/hp_btl_image/resort-product-ed2015.png' alt='btl' style="border: 1px solid transparent; width: 200px; height: 90px;">
				</a>	
			</div>
			<!--div>
				<a href="/priorities">
				<img src='/assets/images/company_priorities.png' alt='eco' style="border: 1px solid transparent; ">	
				</a>

			</div-->
		</div>
	<?php endif; ?>
	

	<div class="section-a" style="margin-top: 20px;">
		<div id="calendar">
		<h2 class="c" style="margin-bottom: 6px;">
			<?=anchor("calendar", "COMPANY CALENDAR")?>
		</h2>
		</div>
		<!--br-->
		<div id="hpCalendar" class="mini-calendar"></div>
		<!--br style="line-height: 2px;"-->
		<!--dl class="event">
			<dt class="date"><!--?="Today:&nbsp;".date("D, F j, Y")?></dt>
			<dd></dd>
		</dl-->
		
	</div>

	<?php if (isset($announcements) and (count($announcements) > 0)): ?>
		<div class="section-a">
			<h2 class=" collapsible c">Announcements</h2>
			<div>
				<?php foreach($announcements as $announcement): ?>
					<p><strong><?=$announcement->start_date; ?></strong> - <?php echo $announcement->event_title?></p>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>	
	
	<!--old ef snap-->
	<!--br style="line-height: 10px;"-->
	<script type="application/template" id="efphotosTempl">
		<div class="section-a" style="margin-top: 0px;">
			<div id="efsnapshots">
			<h2 class="c" style="margin-bottom: 2px;"><?=anchor("photos", "EF Snapshots")?></a></h2>
			</div>
			<div class="efphoto">

				<br>
				<a href="{{href}}" title="{{title}}">
					<img src="{{src}}" alt="{{alt}}" />
				</a>
				<p class="caption">{{title}}</p>
				
			</div>
		</div>
	</script>
	
	
	<?php if (isset($ads) and (count($ads) > 0)): ?>
		<div class="section-a">
			<div id="ads">
			<h2 class="c"><?=anchor("/ads", "Ads & Placements")?></h2>
			</div>
			<div id="adsSection">
				<?php $ads = array_merge($ads, $placements); ?>
				<?php foreach($ads as $ad): ?>
					<h2 class="d ad">
						<?=anchor("/ads", "As seen in ...")?>
						<span class="ad-tooltip">
							<img class="thumb"
								 src="<?=site_url('images/preview/'.val($ad['revision_text']->main_image[0]->src)
										 .'/w/70')?>" alt="As seen in" />
							<div class="desc"><?=val($ad['revision_text']->article)?></div>
						</span>
					</h2>
					<h3 class="d"><?=($ad['revision_text']->teaser)?></h3>
					<p class="date">On <?=date("F j, Y", strtotime(val($ad['date_published'], 0)))?></p>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>


	<div class="section-a">
		<h2 class="c">The Buzz</h2>
        <div id="rssNews" title="loading">
            <img alt="loading..." src="<?=STATIC_URL?>images/loading.gif">
        </div>
	</div>

</aside><!--/ #secondary -->

<?php
	$assets = $this->load->get_var('assets');
	$assets[] = 'fullcalendar';
	$this->load->vars(array('assets' => $assets));
