<?php
/**
 * User: cravelo
 * Date: Nov 5, 2010
 * Time: 1:48:27 PM
 *      This is the right column in the homepage, to be included in other pages that don't have a right column oif their own
 */
?>
<aside class="secondary column-border-top">
	<?php if ($this->session->userdata('show_apps')): ?>
		<div class="icons">
			<a class="priority-tree" href="http://priorities.eileenfisher.net/" rel="external">Priority Tree</a>
			<a class="applications" href="http://apps.eileenfisher.net/" rel="external">Applications Portal</a>
		</div>
		<div class="section-a">
	<?php else: ?>
		<div>
	<?php endif; ?>
		<h2 class="c"><?php echo anchor("calendar", "Company calendar"); ?></h2>
		<div id="hpCalendar" class="mini-calendar"></div>
		<dl class="event">
			<dt class="date"><?php echo "Today:&nbsp;".date("D, F j, Y"); ?></dt>
		</dl>
	</div>

	<?php if (isset($announcements) and (count($announcements) > 0)): ?>
		<div class="section-a">
			<h2 class=" collapsible c">Announcements</h2>
			<div>
				<?php foreach($announcements as $announcement): ?>
					<p><strong><?php echo $announcement->start_date; ?></strong> - <?php echo $announcement->event_title; ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<!--script type="application/template" id="efphotosTempl">
		<div class="section-a">
			<h2 class="c"><a href="<?=site_url('photos')?>">EF Snapshots</a></h2>
			<div class="efphoto">
				<a href="{{href}}" title="{{title}}">
					<img src="{{src}}" alt="{{alt}}" />
				</a>
				<div class="caption">{{title}}</div>
			</div>
		</div>
	</script-->

	<?php if (isset($ads) and (count($ads) > 0)): ?>
		<div class="section-a">
			<h2 class="collapsible ads c">
				<!--img class="thumb" src="#" alt="As seen in" /-->
				<img src="<?php echo STATIC_URL; ?>images/ads_logo.png" alt="Placements/Ads">
			</h2>
			<div id="adsSection">
				<?php foreach($ads as $ad): ?>
					<h2 class="d ad">
						<?php echo anchor("/ads", "As seen in ..."); ?>
						<span class="ad-tooltip">
							<img class="thumb" src="<?php echo site_url('images/src/'.$ad['revision_text']->main_image[0]->src.'/w/70'); ?>" alt="As seen in" />
							<div class="desc"><?php echo $ad['revision_text']->article; ?></div>
						</span>
					</h2>
					<h3 class="d"><?php echo $ad['revision_text']->teaser; ?></h3>
					<p class="date">On <?php echo date("F j, Y", strtotime($ad['date_published'])); ?></p>
				<?php endforeach; ?>
				<?php //print_r($ads); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="section-a">
		<h2 class="c collapsible">The Buzz</h2>
		<div id="rssNews" title="loading">
			<img alt="loading..." src="<?php echo STATIC_URL; ?>images/loading.gif">
		</div>
	</div>
</aside><!--/ #secondary -->
