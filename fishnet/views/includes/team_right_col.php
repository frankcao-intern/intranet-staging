<?php
/**
 * Created by: cravelo
 * Date: 8/15/11
 * Time: 10:29 AM
 */
 ?>

<aside class="secondary column-border-top">
	<h2 class="c"><span style="background-color: <?php echo $revision['revision_text']['color']; ?>"></span><?php echo anchor("calendar/".$revision['revision_text']['calendar'], "Events Calendar"); ?></h2>
	<div id="miniCalendar" class="mini-calendar"><?php echo $revision['revision_text']['calendar']; ?></div>
	<dl class="event">
		<dt class="date"><?php echo "Today:&nbsp;".date("D, F j, Y"); ?></dt>
	</dl>

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

	<ul class='edit_links' id="links">
		<?php
			$links = json_decode($revision['revision_text']['links'], true);
			foreach($links as $link): ?>
			<li class="section-a space-top-0">
				<h2 class="c"><?php echo anchor(prep_url($link['url']), $link['title'].' >'); ?></h2>
			</li>
		<?php endforeach; ?>
	</ul>
</aside> <!--/ #secondary -->
