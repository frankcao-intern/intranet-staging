<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<span class="season"><?=val($season)?></span>&nbsp;|&nbsp;<span class="year"><?=val($year)?></span>
		</p>
		<h2><?=anchor($this->uri->segment(1), val($title, 'Untitled Page'))?></h2>
	</div>

	<?php $this->load->view('page_parts/digest_navigation') ?>

	<?php foreach (val($articles, array()) as $article): ?>
		<div class="section-a featured-a">
			<h2 class="b"><?=anchor("articles/".$article['page_id'], $article['title'])?></h2>
			<?php
				$src = val($article['revision_text']->main_image[0]->src, 'error');
				$flip = val($article['revision_text']->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($article['revision_text']->main_image[0]->angle, 0);
				$img = get_image_html($src, 252, 156, $flip, $angle);
				$alt = htmlentities(val($article['revision_text']->main_image[0]->alt), 2, 'UTF-8');
			?>
			<p class="image">
				<a href="<?=site_url("articles/".$article['page_id'])?>"><img <?=$img?> alt="<?=$alt?>" /></a>
			</p>
			<p class="published">
				<?=date("l, F d, Y", strtotime($article['date_published']))?>
				&nbsp;|&nbsp;
				<?=(int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments")?>
			</p>
			<p><?=$article['revision_text']->article?></p>
			<p class="more-a"><?=anchor("articles/".$article['page_id'], "Read More&nbsp;&nbsp;&#x25ba;")?></p>
		</div>
	<?php endforeach; ?>

	<div class="section-a"><!-- this provides separation of the listing from the pagination bar -->
		<?php $this->load->view('page_parts/digest_navigation') ?>
	</div>
</section> <!--/ #primary -->

<aside class="secondary column-border-top">
	<h2 class="c"><?=
		anchor(implode('/', array($this->uri->segment(1), $page_id, val($last_season), val($last_year))),
				'Previous Season')
	?></h2>
	<ul class="list-a">
	<?php foreach (val($past_articles, array()) as $article): ?>
		<li>
			<h3 class="d"><a><?=anchor("articles/".$article['page_id'], $article['title'])?></a></h3>
			<p><?=$article['revision_text']->teaser?></p>
		</li>
	<?php endforeach; ?>
	</ul>

	<div class="section-a">
		<h2 class="c">Search Previous Seasons</h2>
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
				<input id="year" name="year" value="<?php echo date("Y",time()) ?>">
			</p>
			<p class="submit"><button type="submit" id="btnChangeSeason">Go</button></p>
		<?=form_close()?>
	</div>
</aside> <!--/ #secondary -->

<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.maskedinput-1.2.2.js"></script>
<script>siteRoot = "<?=base_url()?>"; </script>
