<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>

<h3 class="c">Teaser:</h3>
<div class="edit-textarea" id="teaser" data-key="teaser"><?php
	echo ($revision['revision_text']["teaser"] == "") ?
			"Enter the teaser here" :
			$revision['revision_text']['teaser'];
?></div>

<h3 class="c">URL from vimeo:</h3>
<div class="edit-textinline" data-key="videoURL" id="videoURL"><?php echo $revision['revision_text']["videoURL"];
	?></div>

<h3 class="c">Cover:</h3>
<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
