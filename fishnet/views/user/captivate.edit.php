<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>

<h3 class="c">Teaser:</h3>
<div class="edit-textarea" data-key="teaser"><?=//dont indent
	(isset($revision['revision_text']["teaser"]) and !empty($revision['revision_text']["teaser"])) ?
		$revision['revision_text']['teaser'] : "Enter your teaser here.";
?></div>

<h3 class="c">Video File Name (Don't touch this if you dont know what it is):</h3>
<div class="edit-textinline" data-key="videoURL"><?=$revision['revision_text']["videoURL"]?></div>

<h3 class="c">Captivate Flash File Name (Don't touch this if you dont know what it is):</h3>
<div class="edit-textinline" data-key="flashURL"><?=$revision['revision_text']["flashURL"]?></div>

<h3 class="c">Thumbnail:</h3>
<?php $this->load->view('page_parts/image_stack', array('width' => 618, 'height' => 383)); ?>
