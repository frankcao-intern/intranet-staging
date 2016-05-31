<div class="primary grid text-only">
	<span class="hide" id="pageColumns"><?php
		if (isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])){
			echo $revision['revision_text']["columns"];
		}else{
			echo "1";
		}
	?></span>

	<?php $this->load->view('page_parts/article_header') ?>

	<?php $this->load->view('page_parts/image_grid') ?>

	<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
</div>

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
