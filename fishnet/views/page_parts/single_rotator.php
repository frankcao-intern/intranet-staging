<?php
/**
 * Created by: cravelo
 * Date: 3/1/12
 * Time: 1:01 PM
 */

$articles = val($articles, array());
?>
<div class="rotator-a">
	<div class="items">
		<ul>
			<?php for($i = 0; $i < count($articles); $i += 3): ?>
				<li>
					<?php for($j = $i; $j < ($i + 3); $j++): ?>
						<?php if (!isset($articles[$j])){ break; } ?>
						<?php $main_image = val($articles[$j]['revision_text']->main_image, array()); ?>
						<div class="item">
							<p class="thumb">
								<a href="<?=site_url("article/".$articles[$j]['page_id'])?>">
									<?php
									$src = val($main_image[0]->src, 'error');
									$flip = val($main_image[0]->flip, false);
									$flip = (($flip === true) or ($flip === "true"));
									$angle = (int)val($main_image[0]->angle, 0);
									$img = get_image_html($src, 618, 383, $flip, $angle);
									$thumb = get_image_html($src, 139, 86, $flip, $angle);
									$alt = ucfirst(val($main_image[0]->alt));
									$teaser = val($articles[$j]['revision_text']->teaser);
									$article = val($articles[$j]['revision_text']->article);
									?>
									<img <?=$thumb?> alt="<?=$alt?>" />
									<span class="title"><?=htmlentities(ucwords($teaser), ENT_COMPAT, 'UTF-8', false)?></span>
								</a>
							</p>
							<div class="main">
								<p class="image"><img <?=$img?> alt="<?=$alt?>" /></p>
								<div class="caption">
									<h3 class="b">
										<?=anchor("article/".$articles[$j]['page_id'],
											htmlentities($articles[$j]['title'], ENT_COMPAT, 'UTF-8', false))?>
									</h3>
									<p>
										<?=htmlentities($article, ENT_COMPAT, 'UTF-8', false)?>
									</p>
									<p class="more-b">
										<?=anchor('article/'.$articles[$j]['page_id'],
											"Read More&nbsp;&nbsp;&#x25ba;")?>
									</p>
								</div>
							</div>
						</div>
					<?php endfor; ?>
				</li>
			<?php endfor; ?>
		</ul>
	</div> <!--/ .items -->

	<div class="rotator-controls">
		<p class="current current-a"><em class="min"></em> <span>of</span> <em class="max"></em></p>
		<p class="rotator-nav"><button class="prev">Previous page</button><button class="next">Next page</button></p>
		<p class="more-b">
			<?=anchor("/article/".val($articles[0]['section_id'], $page_id), "View All&nbsp;&nbsp;&#x25ba;")?>
		</p>
	</div>
</div> <!--/ .rotator-a -->

