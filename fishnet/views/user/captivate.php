<?php
	$videoURL = val($revision['revision_text']["videoURL"]);
	$videoURL = STATIC_URL.'videos/'.substr($videoURL, 0, 1).'/'.substr($videoURL, 0,	2).'/'.
			substr($videoURL, 0, 3).'/'.substr($videoURL, 0, 4)."/$videoURL";

	$flashURL = val($revision['revision_text']["flashURL"]);
	$flashURL = "/images/src/$flashURL?".time();

	$width = val($params['w'], 760);
	$height = val($params['h'], 611);

	$src = val($main_image[0]['src'], 'error');
	$flip = val($main_image[0]['flip'], false);
	$flip = (($flip === true) or ($flip === "true"));
	$angle = (int)val($main_image[0]['angle'], 0);
	$image = get_image($src, false,false, $flip, $angle);
?>
<section class="primary">
	<?php if (!isset($embed)): ?>
		<?php $this->load->view('page_parts/article_header') ?>
	<?php endif; ?>

	<div id="CaptivateContent">
		<p class="hide flash-url"><?=$flashURL?></p>
		<p class="hide width"><?=$width?></p>
		<p class="hide height"><?=$height?></p>
		<video id="single3" controls autoplay preload="auto" width="<?=$width?>" height="<?=$height?>" poster="<?=$image?>">
			<source src="<?=$videoURL?>" type="video/mp4">
		</video>
	</div>

	<?php if (!isset($embed)): ?>
		<div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>
	<?php endif; ?>
</section> <!--/ #primary -->

<script src="<?=STATIC_URL?>js/lib/jwplayer.js"></script>

<?php (isset($tab) or isset($embed)) ? '' : $this->load->view("page_parts/related_info");
