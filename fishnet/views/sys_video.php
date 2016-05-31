<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			Videos &amp; Training
		</p>
		<a class="question-mark" href="<?=site_url("/about/video#submit")?>" title="How do I submit a video to be posted on Channel EF?">
			How do I submit a video to be posted on Channel EF?
		</a>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>

	<div class="video-rotator">
		<div class="primary">
			<div class="main">
				<iframe width="620"	height="384" frameborder="0"
					id="vimeo-player-1" data-progress="true" data-bounce="true" data-seek="true"
						webkitAllowFullScreen mozallowfullscreen allowFullScreen>
				</iframe>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
				<script src="<?=STATIC_URL?>js/lib/vimeo.ga.min.js" type="text/javascript"></script>
			</div>
		</div>		
		
		
		<div class="secondary">
			<div class="items">
				<ul>
				<?php for($i = 0; $i < count(val($featured, array())); $i += 3): ?>
					<li>
					<?php for($j = $i; $j < ($i + 3); $j++): ?>
						<?php
							if (!isset($featured[$j])){ continue; }
							$revision = $featured[$j]['revision_text'];
							$filename = preg_replace("/[^\d]+/", '', $revision->videoURL);
							$videoLink = site_url("video/".$featured[$j]['page_id']);
							$src = val($revision->main_image[0]->src, 'error');
							$flip = val($revision->main_image[0]->flip, false);
							$flip = (($flip === true) or ($flip === "true"));
							$angle = (int)val($revision->main_image[0]->angle, 0);
							$img = get_image_html($src, 139, 86, $flip, $angle);
							$alt = htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false);
						?>
						<div class="item">
							<p class="thumb">
								<a href="<?=$featured[$j]['page_id']?>">
									<img <?=$img?> alt="<?=$alt?>" />
									<span class="title">
										<?=htmlentities(ucwords($revision->teaser), ENT_COMPAT, 'UTF-8', false)?>
									</span>
									<span class="now-playing">Now Playing</span>
								</a>
							</p>
							<div class="caption">
								<h2 class="a">
									<a href="<?=$videoLink?>">
										<?=htmlentities($featured[$j]['title'], ENT_COMPAT, 'UTF-8', false)?>
									</a>
								</h2>
								<?=htmlentities(val($revision->article), ENT_COMPAT, 'UTF-8', false)?>
								<a href="<?=$videoLink?>" class="more-a">Read More&nbsp;&nbsp;&#x25ba;</a>
							</div>
						</div>
					<?php endfor; ?>
					</li>
				<?php endfor; ?>
				</ul>
			</div><!--/ .items -->

			<div class="rotator-controls">
				<p class="current current-a"><em class="min"></em> <span>of</span> <em class="max"></em></p>
				<p class="rotator-nav">
					<button class="prev">Previous page</button>
					<button class="next">Next page</button>
				</p>
			</div>
		</div>
	</div>

	<div class="columns quadruple-a section-a">
		<h2 class="c">Other videos</h2>
		<ul>
		<?php for($i = 0; $i < count(val($random, array())); $i += 4): ?>
			<li>
			<?php for($j = $i; $j < count($random) && $j < ($i + 4); $j++): ?>
				<?php
				$revision = $random[$j]['revision_text'];
				$videoLink = site_url("video/".$random[$j]['page_id']);
				$src = val($revision->main_image[0]->src, 'error');
				$flip = val($revision->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($revision->main_image[0]->angle, 0);
				$img = get_image_html($src, 189, 117, $flip, $angle);
				$alt = htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false);
				?>
				<div class="col">
					<p class="image">
						<a href="<?=$videoLink?>"><img <?=$img?> alt="<?=$alt?>" /></a>
					</p>
					<div>
						<h3 class="b">
							<a href="<?=$videoLink?>">
								<?=htmlentities(ucwords($random[$j]['title']), ENT_COMPAT, 'UTF-8', false)?>
							</a>
						</h3>
						<p><?=htmlentities(ucwords(val($revision->teaser)), ENT_COMPAT, 'UTF-8', false)?></p>
					</div>
				</div>
			<?php endfor; ?>
			</li>
		<?php endfor; ?>
		</ul>
	</div> <!--/ .quadruple-a -->

</section> <!--/ #primary -->

<aside class="secondary">
	<?php $this->load->view('page_parts/tag_cloud'); ?>
</aside>
