<div class="primary postcard text-only">
	<span class="hide" id="pageColumns"><?php
		if (isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])){
			echo $revision['revision_text']["columns"];
		}else{
			echo "1";
		}
	?></span>

	<?php $this->load->view('page_parts/article_header') ?>

	<?php $this->load->view('page_parts/image_stack',
			array(
				'width' => 760,
				'height' => 471,
				'picture_info' => true
			)
	); ?>

	<div class="edit-wysiwygadv article" data-key="article"><?=
		val($revision['revision_text']['article'])
	?><p></p></div>
</div>

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
