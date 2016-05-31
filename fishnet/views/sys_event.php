<?php
/**
 * User: cravelo
 * Date: Aug 13, 2010
 * Time: 2:34:31 PM
 * Single Event template
 */
?>

<section class="primary">
	<?php if (!isset($embed)): ?>
		<div class="header-a header-a-space-bottom-b">
			<p><?=isset($breadcrumbs) ? $breadcrumbs['url'] : "&nbsp;"?></p>
			<h2><?=$event->event_title?></h2>
		</div>
	<?php endif; ?>

	<div class="vevent">
		<dl>
			<div class="event-detail">
				<?php if($event->rec_factor == null): ?>
					<dt class="a">When:</dt>
					<dd class="a">
						<?=date("l, F j, Y", strtotime($event->start_date))?>
						<?php if(($event->end_date != null) and ($event->start_date != $event->end_date)): ?>
							&nbsp;-&nbsp;<?=date("l, F j, Y", strtotime($event->end_date))?>
						<?php endif; ?>
						<?=($event->all_day) ? "All day" : "from ".date("h:i A", strtotime($event->start_time))." to ".date("h:i A", strtotime($event->end_time))?>
					</dd>
				<?php else: ?>
					<dt class="a">Occurs </dt>
					<dd class="a">
						<?php
						if(substr($event->rec_factor, 0, 1) == "+"){
							echo "every ".substr($event->rec_factor, 1, strlen($event->rec_factor))."(s)";
						}
						if(!empty($event->rec_rule)){
							$event->rec_rule = str_replace(array(' %Y', 'of %B'), '', $event->rec_rule);
							echo " on the ".$event->rec_rule;
						}
						?>
					</dd>
				<?php endif; ?>

				<?php if (isset($event->next_occurrence)): ?>
					<dt class="a">Next occurrence: </dt>
					<dd class="a"><?=date("l, F j, Y", strtotime($event->next_occurrence))?></dd>
				<?php endif; ?>
			</div>

			<?php if (!empty($event->where)): ?>
				<div class="event-detail">
					<dt class="a">Where:</dt>
					<dd class="a location">
						<?=$event->where?>
						<?=empty($event->where_room) ? "" : "&nbsp;-&nbsp;".$event->where_room?></dd>
				</div>
			<?php endif; ?>

			<?php if (!empty($event->event_desc)): ?>
				<div class="event-detail">
					<dt class="a">Info:</dt>
					<dd class="a"><?=$event->event_desc?></dd>
				</div>
			<?php endif; ?>

			<?php if (!empty($event->organizer)): ?>
				<div class="event-detail">
					<dt class="a">Organizer:</dt>
					<dd class="a">
						<span class="organizer">
							<?php if ($event->organizer === 'custom'): ?>
								<?=$event->organizer_name?>
							<?php else: ?>
								<?=anchor("profiles/".$event->organizer, $event->organizer_name)?>
							<?php endif; ?>
						</span>
					</dd>
				</div>
			<?php endif; ?>

			<dt class="a offset">Actions:</dt>
			<?php if (isset($canWrite) and $canWrite and isset($embed)): ?>
				<dd class="a"><?=anchor("edit/event/{$event->event_id}", "Edit this event")?></dd>
				<dd class="a">&nbsp;|&nbsp;</dd>
			<?php endif; ?>
			<?php if (isset($canProp) and $canProp and isset($embed)): ?>
				<dd class="a action"><?=anchor("eventprops/{$event->event_id}", "Publish this event")?></dd>
			<?php endif; ?>
		</dl>
	</div>
</section> <!--/ #primary -->

<?php if (!isset($embed)): ?>
	<aside class="secondary">
		<div class="section-a">
			<h2 class="c">This event is published to: </h2>
			<ul class="list-c">
			<?php foreach($event->sections as $cal_title => $cal_id): ?>
				<li><?=anchor("calendar/$cal_id", $cal_title)?></li>
			<?php endforeach; ?>
			</ul>
			<?php if (isset($canProp) and $canProp): ?>
                <?=anchor("eventprops/{$event->event_id}", "Publish this event to other calendars", 'id="btnPublish"')?>
			<?php endif; ?>
		</div>
	</aside> <!--/ #secondary -->
<?php endif; ?>
