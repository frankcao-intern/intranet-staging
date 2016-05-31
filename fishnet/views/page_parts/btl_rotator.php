<?php
/**
 * Created by: cravelo
 * Date: 8/15/11
 * Time: 10:27 AM
 */
?>
<div class="rotator-d">
	<div class="items">
		<ul>
			<li>
			<?php for($i = 0; $i < count($articles); $i++): ?>
				<?php if (!isset($articles[$i])){ continue; } ?>
				<?php $main_image = val($articles[$i]['revision_text']->main_image, array()); ?>
				<div class="item">
					<h2 class="c c-b">
						<?=anchor('article/'.val($articles[$i]['section_id']),
							htmlentities(val($articles[$i]['section_title']), ENT_COMPAT, 'UTF-8', false))?>
					</h2>
					<p class="thumb">
						<a href="<?=site_url('article/'.$articles[$i]['page_id']); ?>" class="item-link">
							<?php
								$src = val($main_image[0]->src, 'error');
								$flip = val($main_image[0]->flip, false);
								$flip = (($flip === true) or ($flip === "true"));
								$angle = (int)val($main_image[0]->angle, 0);
								$img = get_image_html($src, 618, 383, $flip, $angle);
								$thumb = get_image_html($src, 139, 86, $flip, $angle);
								$alt = ucfirst(val($main_image[0]->alt));
								$article = val($articles[$i]['revision_text']->article);
							?>
							<img <?=$thumb?>/>
						</a>
					</p>
					<div class="main">
						<p class="image"><img <?=$img?>/></p>
						<div class="caption">
							<?php if (!empty($articles[$i]['title'])): ?>
								<h2 class="a">
									<?=anchor('article/'.$articles[$i]['page_id'],
										htmlentities($articles[$i]['title'], ENT_COMPAT, 'UTF-8', false)); ?>
								</h2>
							<?php endif; ?>
							<p>
								<?=htmlentities($article, ENT_COMPAT, 'UTF-8', false)?>
								<?=anchor('article/'.$articles[$i]['page_id'],
								          "Read More&nbsp;&#x25ba;", 'class="more-a"'); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endfor; ?>
			</li>
		</ul>
	</div>
</div> <!--/ .rotator-d -->
