<div class="primary two-columns">
	<span class="hide page-columns"><?php
		if (isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])){
			echo $revision['revision_text']["columns"];
		}else{
			echo "1";
		}
	?></span>
	<?php $this->load->view('page_parts/article_header') ?>

	<div <?=(isset($edit)) ? '' : 'class="article"'; ?>>
		<?php $this->load->view('page_parts/image_stack',
				array(
					'width' => 352,
					'height' => 218,
					'picture_info' => true
				)
		); ?>

		<?php if (isset($edit)): ?>
			<div data-key="article" class="edit-wysiwygadv article">
		<?php endif;?>
			<?=$revision['revision_text']['article']?>
		<?php if (isset($edit)): ?>
			</div>
		<?php endif; ?>
	</div>
</div> <!--/ #primary -->

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
