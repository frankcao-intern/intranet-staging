<?php
	$vimeoID = preg_replace("/[^\d]+/", '', $revision['revision_text']["videoURL"]); // -- http://vimeo.com/ID
	$width = val($params['w'], 759);
	$height = val($params['h'], 427);
	$protocol = $this->config->item('protocol');
?>
<section class="primary">
	<?php if (!isset($embed)): ?>
		<?php $this->load->view('page_parts/article_header') ?>
	<?php endif; ?>

	<iframe src="<?=$protocol?>://player.vimeo.com/video/<?=$vimeoID?>?byline=0&portrait=0&color=999999?api=1&player_id=vimeo-player-1"
			id="vimeo-player-1" width="<?=$width?>"	height="<?=$height?>" frameborder="0"
			data-progress="true" data-bounce = "true" data-seek = "true"
			webkitAllowFullScreen mozallowfullscreen allowFullScreen>
	</iframe>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script src="<?=STATIC_URL?>js/lib/vimeo.ga.min.js" type="text/javascript"></script>

	<?php if (!isset($embed) and !isset($tab)): ?>
		<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
	<?php endif; ?>
</section> <!--/ #primary -->

<?php (isset($tab) or isset($embed)) ? '' : $this->load->view("page_parts/related_info");



