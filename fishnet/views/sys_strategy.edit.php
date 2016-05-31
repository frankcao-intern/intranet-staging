<h3 class="c">URL from vimeo:</h3>
<div class="edit-textinline" data-key="videoURL" id="videoURL"><?php echo $revision['revision_text']["videoURL"];
	?></div>

<h3 class="c">Cover:</h3>
<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
