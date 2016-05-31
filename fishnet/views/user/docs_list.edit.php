<?php
$model_after_param = val($revision['revision_text']['model_after_param'], array(null, 'true'));
$last_mod_date = (val($revision['revision_text']['last_mod_date']) === "true");
?>
<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?php //don't indent
		$teaser = val($revision['revision_text']['teaser']);
		echo empty($teaser) ? "Enter the teaser here" : $teaser;
	?></div>

    <h3 class="c">Cover Image:</h3>
	<?php $this->load->view('page_parts/image_stack', array('width' => 380, 'height' => 236)); ?>

    <div class="edit-meta-oneline">
        <h3 class="c">Number of Columns for list:</h3>
		<span class="edit-textinline" data-key="columns"><?php //don't indent
			if (isset($revision['revision_text']["columns"])){
				echo empty($revision['revision_text']["columns"]) ? "1" : $revision['revision_text']["columns"];
			}else{ echo "1"; }
			?></span>
	</div>

	<div class="edit-meta-oneline">
		<h3 class="c">Folder ID from KnowledgeTree:</h3>
		<span class="edit-revision-obj" data-key="model_after_param"
		   data-obj="<?=htmlentities(json_encode($model_after_param))?>"
		   data-value="0"><?=val($model_after_param[0], 'Undefined')?></span>
	</div>

	<div class="last-mod-date edit-meta-oneline"><!--css only for margin bottom -->
		<label for="lastModDate"><h3 class="c">Display last modified date?</h3></label>
		<?=form_checkbox(array(
				'id' => 'lastModDate',
				'data-key' => 'last_mod_date',
				'class' => 'edit-checkbox',
				'checked' => $last_mod_date
			))?>
	</div>
</div>
