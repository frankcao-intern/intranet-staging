<?php
$articles = val($articles, array());
$articleCount = count($articles);
$prev = val($prev, array());
?>

<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p><?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?></p>
		<h2>
			<?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?> >
			<span class="season"><?=val($season)?></span>
			<span class="year"><?=val($year)?></span>
		</h2>
	</div>

	<?php $this->load->view('page_parts/digest_navigation') ?>

	<!-- test -->
	<?php if($order_by == null && $show_next == null): ?>
	<div id="sortOrder">
		<?php endif; ?>
		<?php foreach(val($articles, array()) as $article): ?>
			<?php

			$articleLink = site_url("article/".$article['page_id']);
			$articleAnchor = "article/".$article['page_id'];
			$revision = $article['revision_text'];
			$src = val($revision->main_image[0]->src, 'error');
			$flip = val($revision->main_image[0]->flip, false);
			$flip = (($flip === true) or ($flip === "true"));
			$angle = (int)val($revision->main_image[0]->angle, 0);
			$img = get_image_html($src, 252, 156, $flip, $angle);
			$alt = htmlentities(val($article['revision_text']->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false);
			?>
			<div class="section-a featured-a" id="<?php echo $article['page_id']; ?>">
				<p class="image">
					<a href="<?php echo site_url($articleAnchor); ?>">
						<img <?php echo $img?> alt="<?php echo htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false); ?>" />
					</a>
				</p>

				<p>
				<h3 class="b" style="text-align: left;"><?php echo anchor($articleAnchor, $article['title']); ?></h3>
				</p>

				<p class="published">
					<span class="date <?php echo (empty($order_by) or ($order_by === 'most_recent')) ? 'highlight' : ''?>">
						<?php echo date("l, F d, Y", strtotime($article['date_published']))?>
					</span>
					&nbsp;|&nbsp;
					<span class="<?php echo ($order_by === 'comments_count') ? 'highlight' : ''?>">
						<?php echo (int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments")?>
					</span>
					&nbsp;|&nbsp;
					<span class="<?php echo ($order_by === 'page_views') ? 'highlight' : ''?>">
						<?php echo $article['page_views']." read"; ?>
					</span>
				</p>
				<p><?php echo val($revision->article, "..."); ?></p>
				<p class="more-a"><?=anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;")?></p>
			</div>
		<?php endforeach; ?>
		<?php if($order_by == null): ?>
	</div>
<?php endif; ?>
	<!-- eof test -->

	<div class="section-a"><!-- this provides separation of the listing from the pagination bar -->
		<?php $this->load->view('page_parts/digest_navigation') ?>
	</div>
</section> <!--/ #primary -->

<aside class="secondary">
	<?php $past_articles = val($past_articles, array());?>
	<?php if (count($past_articles) > 0): ?>
	<div class="section-a">
		<h2 class="c"><?=
			anchor(implode('/', array_merge(array($this->uri->segment(1), $page_id), $prev)),
			       implode(' ', $prev))
		?></h2>
		<ul class="list-a">
		<?php foreach ($past_articles as $article): ?>
			<li>
				<h3 class="d"><a><?=anchor("article/".$article['page_id'], $article['title'])?></a></h3>
				<p><?=$article['revision_text']->teaser?></p>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="section-a">
		<h2 class="c">Jump to Season</h2>
		<?=form_open($this->uri->segment(1)."/$page_id", 'method="post" class="search search-a"')?>
			<p class="select-a select-month">
				<label for="season">Season</label>
				<select name="season" id="season">
					<option value="Spring">Spring</option>
					<option value="Fall">Fall</option>
					<option value="Resort">Resort</option>
				</select>
			</p>
			<p class="select-year">
				<label for="year" class="offset">Year</label>
				<input id="year" name="year" value="<?=date("Y")?>">
			</p>
			<p class="submit"><button type="submit" id="btnChangeSeason">Go</button></p>
		<?=form_close()?>
	</div>
</aside> <!--/ #secondary -->
