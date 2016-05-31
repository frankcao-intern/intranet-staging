<?php
//	$vimeoID = preg_replace("/[^\d]+/", '', $revision['revision_text']["videoURL"]); // -- http://vimeo.com/ID
	$width = val($params['w'], 759);
	$height = val($params['h'], 427);
//	$protocol = $this->config->item('protocol');
?>
<section class="primary">
	<?php if (!isset($embed)): ?>
		<?php $this->load->view('page_parts/article_header') ?>
	<?php endif; ?>

	<iframe src="<?=STATIC_URL?>videos/Paper.mp4"
			width="<?=$width?>"	height="<?=$height?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen
			allowFullScreen>
	</iframe>

	<?php if (!isset($embed) and !isset($tab)): ?>
		<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
	<?php endif; ?>
</section> <!--/ #primary -->

<?php (isset($tab) or isset($embed)) ? '' : $this->load->view("page_parts/related_info");



