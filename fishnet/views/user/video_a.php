
<?php
	$videoURL = $revision['revision_text']["videoURL"];
	$filename = STATIC_URL.'videos/'.substr($videoURL, 0, 1).'/'.substr($videoURL, 0,	2).'/'.
			substr($videoURL, 0, 3).'/'.substr($videoURL, 0, 4)."/$videoURL";

	$width = val($params['w'], 760);
	$height = val($params['h'], 427);
?>
<section class="primary">
	<?php if (!isset($embed)): ?>
		<?php $this->load->view('page_parts/article_header') ?>
	<?php endif; ?>

	<div id="video">
		<?php
			$thumbnails = $revision['revision_text']['main_image'];
			$image_properties = array(
				'src' => 'images/preview/'.val($thumbnails[0]['src'], 'error')."/w/$width/zc/$height",
				'width' => $width,
				'height' => $height
			);

			echo img($image_properties);
		?>
		<p class="filename"><?=$filename?></p>
		<p class="width"><?=$width?></p>
		<p class="height"><?=$height?></p>
	</div>

	<?php if (!isset($embed)): ?>
		<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
	<?php endif; ?>
</section> <!--/ #primary -->

<script src="<?=STATIC_URL?>js/lib/jwplayer.js"></script>

<?php (isset($tab) or isset($embed)) ? '' : $this->load->view("page_parts/related_info");
