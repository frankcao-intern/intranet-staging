<?php
/**
 * User: cravelo
 * Date: Jul 27, 2010
 * Time: 10:49:52 AM
 * these are the event properties.
 */
?>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/json2.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/templates/page_properties.lib.js"></script>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?php echo anchor("/event/$page_id", "&#x25c4; Go back to the Event"); ?></p>
		<h1 class="a">Event Properties</h1>
	</div>

	<!--form action="" method="post" class="properties-a"-->
	<!--h2 class="a">General Settings</h2-->
		<div class="field-group-a">
			<h3 class="c">Calendars</h3>
			<div class="field">
				<p class="desc">This is a list of sections/calendars which you have permissions to publish to, this event is published to the ones that are selected below. You can select more than one section.</p>
				<ul id="sections" class="sortable-list">
					<?php $other = array(); ?>
					<?php foreach($sections as $section): ?>
						<?php if ($section['permPublish'] == 0): ?>
							<?php if ($section['selected'] == 1) $other[] = $section['title']; ?>
						<?php else: ?>
							<li id="s<?php echo $section['page_id']; ?>" class="<?php echo ($section['selected'] == 1) ? 'ui-selected' : ''; ?>"><?php echo $section['title']; ?></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php if (count($other) > 0): ?>
					<p style="clear: both;">This event is also published to the following sections: <?php echo implode(", ", $other) ?></p>
				<?php endif; ?>
			</div>
		</div>
		<p><button id="btnSaveProp">Publish to these sections</button></p>
	<!-- /form -->
	<div class="header-a">
		<p><?php echo anchor("/event/$page_id", "&#x25c4; Go back to the Event"); ?></p>
		<h1 class="a">Event Properties</h1>		
	</div>
</section> <!--/ #primary -->
