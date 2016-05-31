<?php
/**
 * User: cravelo
 * Date: Jul 27, 2010
 * Time: 10:49:52 AM
 * these are the event properties.
 */
?>
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/properties.css"

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/event/$page_id", "&#x25c4; Go back to the Event")?></p>
		<h2>Publish Event</h2>
	</div>

	<div class="field-group-a">
		<h3 class="c">Sections/Calendars</h3>
		<div class="field settings-a">
			<h3>Select the section(s) to publish your event to:</h3>
			<ul id="sections" class="sortable-list">
				<?php $other = array(); ?>
				<?php foreach($sections as $section): ?>
				<?php if ($section['permPublish'] == 0): ?>
					<?php if ($section['selected'] == 1): ?>
						<?php $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
						<?php endif; ?>
					<?php else: ?>
					<li id="s<?=$section['page_id']; ?>" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>">
						<?=$section['title']?>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php if (count($other) > 0): ?>
			<p class="sections-other">This page is also published to the following sections: </p>
			<ul class="sections-other">
				<?php foreach($other as $section): ?>
				<li id="s<?=$section['id']; ?>"><?php echo $section['title']?>,&nbsp;</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>
	<p><button id="btnSaveProp">Publish event</button></p>

	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/event/$page_id", "&#x25c4; Go back to the Event")?></p>
		<h2>Publish Event</h2>
	</div>
</section> <!--/ #primary -->

