<?php
/**
 * Created by: cravelo
 * Date: 8/15/11
 * Time: 10:29 AM
 */

$links = val($revision['revision_text']['links']);

if (is_string($links)){
	$links = json_decode($links, true);
}

?>

<aside class="secondary">		
		<div>
			<a href='https://fishnet.eileenfisher.com/departments/6009'>
			<img src='/assets/images/sustainability_ambassadors.jpg' alt='sa' style="border: 1px solid transparent; width: 200px;">
			</a>	
			
		</div>
		
	<div class="section-a" style="margin-top:0px;">
		<h2 class="c">
			<span style="background-color: <?=val($revision['revision_text']['color'], '#000000')?>"></span>
			<?=anchor("calendar/".val($revision['revision_text']['calendar']), "Events Calendar")?>
		</h2>
		<div id="miniCalendar" class="mini-calendar"><?=val($revision['revision_text']['calendar'])?></div>
		<dl class="event">
			<dt class="date"><?="Today:&nbsp;".date("D, F j, Y"); ?></dt>
		</dl>
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

	<div class="section-a">
		<h2 class="c">Links</h2>
		<ul class="edit_links styled-bulletlist" id="links">
			<?php foreach($links as $link): ?>
				<?php $linkTitle = htmlentities(val($link['title']), ENT_COMPAT, 'UTF-8', false) . ' &#x25ba;'; ?>
				<li>
					<?php if (isset($edit)): ?>
						<span data-url="<?=prep_url(val($link['url']))?>" data-title="<?=val($link['title'])?>">
							<?=$linkTitle?>
						</span>
					<?php else: ?>
						<?=anchor(prep_url(val($link['url'])), $linkTitle)?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</aside> <!--/ #secondary -->
