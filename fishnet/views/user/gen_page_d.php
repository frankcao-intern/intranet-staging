<section class="primary">
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="double-e content-a generic-page-d">
		<?php $this->load->view('page_parts/image_stack',
				array(
					'width' => 309,
					'height' => 191,
					'picture_info' => true,
					'class' => 'one',
					'hidden' => false
				)
		); ?>
		<div class="two">
			<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
		</div>
	</div>
</section> <!--/ #primary -->

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
