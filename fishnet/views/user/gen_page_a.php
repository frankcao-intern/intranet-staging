<div class="primary generic-page-a">
	<?php $this->load->view('page_parts/article_header') ?>

	<?php $this->load->view('page_parts/image_stack', array(
			'width' => 380,
			'height' => 236,
			'picture_info' => true));
	?>

	<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
</div>

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
