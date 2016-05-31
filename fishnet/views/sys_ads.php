<?php
/**
 * User: cravelo
 * Date: Nov 3, 2010
 * Time: 12:48:14 PM
 */
 ?>
<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?php if (isset($edit)): ?>
				<?=anchor("article/$page_id", '&#x25c4; Preview Page')?>
			<?php else: ?>
				<?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?>
			<?php endif; ?>
		</p>
		<h2><?=val($title, 'Advertising and Editorial Placement')?> for <?=val($season).' '.val($year)?></h2>
	</div>

	<div class="rotator-ads">
		<?php if (isset($edit)): ?>
			<ul>
			<?php foreach($ads as $ad): ?>
				<li>
					<?php if (!isset($ad)){ break; } ?>
					<?=anchor("edit/".$ad['page_id'], "Edit - ".$ad['title'])?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<ul class="slider">
				<?php foreach($ads as $ad): ?>
					<li>
						<?php
						$revision = $ad['revision_text'];
						$src = val($revision->main_image[0]->src, 'error');
						$flip = val($revision->main_image[0]->flip, false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($revision->main_image[0]->angle, 0);
						$thumb = get_image_html($src, 69, 89, $flip, $angle);
						$img = get_image_html($src, 353, 470, $flip, $angle);
						$alt = ucfirst(htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false));
						?>
						<p class="image"><img <?=$img?> alt="<?=$alt?>" /></p>
						<div class="caption">
							<h2 class="as-seen-in">As Seen In</h2>
							<p class="as-seen-in"><?=ucwords(val($revision->teaser))?></p>
							<p>On <?=date("F j, Y", strtotime($ad['date_published']))?></p>
							<div class="desc"><?=$revision->article?></div>
						</div>
					</li>
					<li class="item"><a><img <?=$thumb?> alt="<?=$alt?>" /></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="items-container"><ul class="items"></ul></div>
		<div class="rotator-controls">
			<?php if (isset($season)): ?>
				<h2 class="d">Advertising for <?="$season&nbsp;$year"?></h2>
			<?php endif; ?>
			<p class="rotator-nav">
				<button class="prev">Previous page</button><button class="next">Next page</button>
			</p>
		</div>
	</div> <!--/ .rotator-ads -->

	<div class="rotator-editorials">
		<div class="items">
			<ul>
				<?php for($i = 0; $i < count(val($placements, array())); $i += 3): ?>
				<li>
					<?php for($j = $i; $j < ($i + 3); $j++): ?>
						<?php
						if (!isset($placements[$j])){ break; }
						$revision = $placements[$j]['revision_text'];

						$src = val($revision->main_image[0]->src, 'error');
						$flip = val($revision->main_image[0]->flip, false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($revision->main_image[0]->angle, 0);
						$thumb = get_image_html($src, 139, 86, $flip, $angle);
						$img = get_image_html($src, 618, 395, $flip, $angle);
						$alt = ucfirst(htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false));
						?>
						<div class="item">
							<p class="thumb">
								<a href="<?=site_url("article/".$placements[$j]['page_id'])?>">
									<img <?=$thumb?> alt="<?=$alt?>" />
									<span class="title"><?=ucwords(val($revision->teaser))?></span>
								</a>
							</p>
							<div class="main">
								<p class="image"><img <?=$img?> alt="<?=$alt?>" /></p>
								<div class="caption">
									<h2 class="d">
										Editorial Placement:
										<?=anchor("article/".$placements[$j]['page_id'],
												htmlentities($placements[$j]['title'], ENT_COMPAT, 'UTF-8', false))?>
									</h2>
								</div>
							</div>
						</div>
					<?php endfor; ?>
				</li>
				<?php endfor; ?>
			</ul>
		</div> <!--/ .items -->

		<div class="rotator-controls">
			<p class="rotator-nav">
				<button class="prev">Previous page</button><button class="next">Next page</button>
			</p>
		</div>
	</div> <!--/ .rotator-editorials -->
</section> <!--/ #primary -->

<aside class="secondary">
	<div class="section-a">
		<h2 class="c">Jump to Season</h2>
		<form action="" method="post" class="search">
			<p class="select-a select-month">
				<label for="month">Season</label>
				<select name="season" id="month">
					<option value="Spring">Spring</option>
					<option value="Fall">Fall</option>
					<option value="Resort">Resort</option>
				</select>
			</p>
			<p class="select-year">
				<label for="year" class="offset">Year</label>
				<input id="year" type="text" value="<?php echo date("Y",time()) ?>">
				<input id="category" type="hidden" value="<?=$this->uri->segment(2)?>" />
			</p>
			<p class="submit"><button type="button" id="btnChangeSeason">Go</button></p>
		</form>
	</div>
</aside>
