<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>

<h3 class="c">Teaser:</h3>
<div class="edit-textarea" id="teaser" data-key="teaser"><?php
	echo ($revision['revision_text']["teaser"] == "") ?
			"Enter the teaser here" :
			$revision['revision_text']['teaser'];
?></div>

<h3 class="c">Video File Name (Don't touch this if you dont know what it is):</h3>
<div class="edit-textinline" data-key="videoURL"><?=$revision['revision_text']["videoURL"]?></div>

<h3 class="c">Thumbnail:</h3>
<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
