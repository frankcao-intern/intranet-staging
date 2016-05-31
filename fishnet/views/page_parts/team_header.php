<?php
/**
 * Created by: cravelo
 * Date: 2/15/12
 * Time: 2:45 PM
 */
$subtitle = val($revision['revision_text']['subtitle'], '');
?>
<div class="header-a header-a-space-bottom-b"
	 style="border-left: 12px solid <?=$revision['revision_text']['color']?>">
	<p>
		<?php if (isset($edit)): ?>
			<?=anchor("/departments/$page_id", "&#x25c4; Preview Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?>
			<span data-key="subtitle" class="edit-textinline" id="subtitle"><?=
				(empty($subtitle)) ? "Enter your subtitle here" : $subtitle;
			?></span>
		<?php else: ?>
			<span><?=(empty($subtitle)) ? "&nbsp;" : $revision['revision_text']['subtitle'];?></span>
		<?php endif; ?>
	</p>
	<h2><?=val($title, 'Untitled Page')?></h2>
</div>
