<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?=//dont indent
		(isset($revision['revision_text']["teaser"]) and ($revision['revision_text']["teaser"] == "")) ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>

	<h3 class="c">1st Video File:</h3>
	<div class="edit-textinline" data-key="videoURL"><?=$revision['revision_text']["videoURL"]?></div>

	<h3 class="c">2nd Video File:</h3>
	<div class="edit-textinline" data-key="videoURL2"><?=$revision['revision_text']["videoURL2"]?></div>

	<h3 class="c">3rd Video File:</h3>
	<div class="edit-textinline" data-key="videoURL3"><?=$revision['revision_text']["videoURL3"]?></div>

	<h3 class="c">Thumbnails:</h3>
	<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
</div>
