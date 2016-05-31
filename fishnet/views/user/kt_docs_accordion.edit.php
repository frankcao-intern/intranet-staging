<?php
$model_after_param = val($revision['revision_text']['model_after_param'], array(null, 'false'));
?>
<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?=//dont indent
		(isset($revision['revision_text']["teaser"]) and ($revision['revision_text']["teaser"] == "")) ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>

	<h3 class="c">Folder ID from KnowledgeTree:</h3>
	<p class="edit-revision-obj" data-key="model_after_param"
	   data-obj="<?=htmlentities(json_encode($model_after_param))?>"
	   data-value="0"><?=val($model_after_param[0], 'Undefined')?></p>

    <?php if ($model_after_param[1] !== 'false'): ?>
        <p class="edit-trigger" data-key="model_after_param"
            data-obj="<?=htmlentities(json_encode($model_after_param))?>"
            data-value="1" data-type="obj">false</p>
    <?php endif; ?>

	<h3 class="c">Cover Image:</h3>
	<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
</div>
