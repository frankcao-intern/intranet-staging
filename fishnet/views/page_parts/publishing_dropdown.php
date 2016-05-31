<?php
/**
 * @filename publishing_dropdown.php
 * @author   : cravelo
 * @date     : 8/24/12 3:53 PM
 */

$other = val($other, array());
?>
<div class="publish-select-container">
	<label>
		Publish to:&nbsp;
		<select class="publish-select" tabindex="2" multiple="multiple">
		<?php foreach($user_sections as $section): ?>
			<?php if ($section['permPublish'] == 0): ?>
				<?php if ($section['selected'] == 1): ?>
					<?php $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
				<?php endif; ?>
			<?php else: ?>
				<option value="<?=$section['page_id']?>" <?=($section['selected'] == 1) ? 'selected' : ''?>>
					<?=$section['title']?>
				</option>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if (count($other) > 0): ?>
			<?php foreach($other as $section): ?>
				<option value="<?=$section['id'];?>" class="other-sections" selected disabled>
					<?=$section['title']?>
				</option>
			<?php endforeach; ?>
		<?php endif; ?>
		</select>
	</label>
</div>
