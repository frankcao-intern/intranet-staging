<section class="primary gallery2-page">
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="gallery">
		<?php $this->load->view('page_parts/image_stack', array(
			"width" => 436,
			"class" => 'primary',
			"startingPic" => (isset($edit)) ? 0 : 1,
			"hidden" => false
		)); ?>
		<div class="secondary">
			<div class="caption">
				<h2 class="image-title"></h2>
				<p class="image-credits">
					<script type="application/template" id="creditsTempl">
						{{credits}}<a href="{{href}}">Download Original</a>
					</script>
				</p>
				<p class="image-desc"></p>
			</div>
		</div>
		<div class="gallery-nav">
			<div class="top pagination"><span class="pager"></span></div>
			<ul class="thumbs">
				<?php
				$gallery = val($revision['revision_text']['main_image'], array());
				for($i = 1; $i < count($gallery); $i += 9): ?>
					<div class="page">
						<?php for($j = $i; $j < ($i + 9); $j++): ?>
							<?php
								if (!isset($gallery[$j])){ break; }
								$src = val($gallery[$j]['src'], 'error');
								$flip = val($gallery[$j]['flip'], false);
								$flip = (($flip === true) or ($flip === "true"));
								$angle = (int)val($gallery[$j]['angle'], 0);
								$img = get_image_html($src, 73, 73, $flip, $angle);
							?>
							<li>
								<a class="thumb" href="#">
									<img <?=$img?>
										 alt="<?=ucfirst(htmlentities(val($gallery[$j]['alt']), ENT_COMPAT, 'UTF-8', false))?>" />
								</a>
							</li>
						<?php endfor; ?>
					</div>
				<?php endfor; ?>
			</ul>
			<div class="controls">
				<a class="prev">&#x25c4;</a>
				<a class="play">Play</a>
				<a class="next">&#x25ba;</a>
			</div>
		</div>
	</div><!--end gallery-a-->

	<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
</section> <!--/ #primary -->

<?php $this->load->view("page_parts/related_info");
