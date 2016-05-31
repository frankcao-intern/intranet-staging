<?php
$model_after_param = val($revision['revision_text']['model_after_param'], array(null, 'true'));
?>
<p class="back-a"><?php echo anchor("/articles/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?php //don't indent
		$teaser = val($revision['revision_text']['teaser']);
		echo empty($teaser) ? "Enter the teaser here" : $teaser;
	?></div>

	<h3 class="c">Number of Columns for folder list:</h3>
	<div class="edit-textinline" data-key="columns"><?php //don't indent
		if (isset($revision['revision_text']["columns"])){
			echo empty($revision['revision_text']["columns"]) ? "1" : $revision['revision_text']["columns"];
		}else{ echo "1"; }
	?></div>

	<h3 class="c">Folder ID from KnowledgeTree:</h3>
	<p class="edit-revision-obj" data-key="model_after_param"
	   data-obj="<?=htmlentities(json_encode($model_after_param))?>"
	   data-value="0"><?=val($model_after_param[0], 'Undefined')?></p>

	<?php if ($model_after_param[1] !== 'true'): ?>
	<p class="edit-trigger" data-key="model_after_param"
	   data-obj="<?=htmlentities(json_encode($model_after_param))?>"
	   data-value="1" data-type="obj">true</p>
	<?php endif; ?>

	<h3 class="c">Cover Image:</h3>
	<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
</div>
