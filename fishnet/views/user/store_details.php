<section class="primary store_details">
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="gallery">
		<?php $this->load->view('page_parts/image_stack', array(
			"width" => 460,
			"class" => 'primary',
			"startingPic" => (isset($edit)) ? 0 : 1
		)); ?>
		<div class="secondary">
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
											 alt="<?=ucfirst(val($gallery[$j]['alt']))?>" />
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
	</div><!--end gallery-a-->
</section> <!--/ #primary -->

<aside class="secondary">
	<h2 class="c">Store Details: </h2>
	<ul class="store-details">
		<li>
			<strong>Store Number:</strong>
			<p class="edit-textinline" data-key="store_number"><?=$revision['revision_text']['store_number']?></p>
		</li>
		<li>
			<strong>Store Type:</strong>
			<p class="edit-select" data-key="store_type"><?=$revision['revision_text']['store_type']?></p>
		</li>
		<li>
			<strong>City:</strong>
			<p class="edit-textinline" data-key="city"><?=$revision['revision_text']['city']?></p>
		</li>
		<li>
			<strong>State:</strong>
			<p class="edit-textinline" data-key="state"><?=$revision['revision_text']['state']?></p>
		</li>
		<li>
			<strong>Opening Date:</strong>
			<p class="edit-textinline" data-key="opening_date"><?=$revision['revision_text']['opening_date']?></p>
		</li>
	</ul>

	<div class="section-a">
		<h2 class="c">Other Information: </h2>
		<div class="edit-wysiwygadv article" data-key="article">
			<?=$revision['revision_text']["article"]?>
		</div>
	</div>
</aside> <!--/ #secondary -->

<script type="text/javascript" src="<?=STATIC_URL?>js/templates/user/gallery.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.cycle.all.js"></script>
