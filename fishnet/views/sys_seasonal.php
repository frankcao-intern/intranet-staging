<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery.maskedinput-1.2.2.js"></script>
<section class="primary">
	<div class="header-a">
		<p>
			<?php
				echo (isset($revision['revision_text']['back_link_id'])) ?
					anchor("/articles/".$revision['revision_text']['back_link_id'], "&#x25c4; ".
						$revision['revision_text']['back_link_title'])."&nbsp;|" : "";
			?>
			<span class="season"><?=$season?></span>
			|&nbsp;
			<span class="year"><?=$year?></span>
		</p>
		<h1><?=$title?></h1>
	</div>
	<ul class="sort-links">
		<li><a href="#" class="sort-link recent">Most Recent</a></li>
		<li><a href="#" class="sort-link popular">Most Popular</a></li>
		<li class="last"><a href="#" class="sort-link comments">Most Comments</a></li>
		<li class="right"><a class="sort-link icon" href="<?php echo site_url("news/rss/$page_id"); ?>">
			<img src="<?php echo site_url("static/images/feed-icon-14x14.png"); ?>" alt="<?php echo htmlentities($title, 2, 'UTF-8'); ?> Feed" height="14" width="14" />
		</a></li>
		<li class="highlighter">&nbsp;</li>
	</ul>
	<?php foreach ($f_articles as $article): ?>
		<?php $articleLink = site_url("articles/".$article['page_id']); ?>
		<?php $articleAnchor = "articles/".$article['page_id']; ?>

		<div class="section-a featured-a">
			<?php if($article['title']): ?>
				<h2 class="b"><?php echo anchor($articleAnchor, $article['title']); ?></h2>
			<?php endif; ?>
			<p class="image">
				<a href="<?php echo $articleLink; ?>">
					<img
						src="<?php echo site_url('images/src/' . $article['revision_text']->main_image[0]->src."/w/252/zc/160"); ?>"
						height="160"
						alt="<?php echo htmlentities($article['revision_text']->main_image[0]->alt, 2, 'UTF-8'); ?>" />
				</a>
			</p>
			<!-- h3 class="d"><?php echo $season . "/" . $year . " | " . $title; ?></h3 -->
			<p class="published">
				<?php echo date("l, F d, Y", strtotime($article['date_published'])); ?>
				&nbsp;|&nbsp;
				<?php echo (int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments"); ?>
			</p>
			<p><?php echo $article['revision_text']->article; ?></p>
			<p class="more-a"><?php echo anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;"); ?></p>
		</div>
	<?php endforeach; ?>
</section> <!--/ #primary -->

<aside class="secondary column-border-top">
	<?php if (count($articles) > 0): ?>
		<h2 class="c"><?php echo $season."&nbsp;".$year." | Other Articles"; ?></h2>
		<ul class="list-a">
		<?php foreach ($articles as $article): ?>
			<li>
				<h3 class="d"><a><?php echo anchor("articles/".$article['page_id'], $article['title']); ?></a></h3>
				<p><?php echo $article['revision_text']->teaser; ?></p>
			</li>
		<?php endforeach; ?>
		</ul>

		<div class="section-a">
	<?php endif; ?>
		<h2 class="c">Search Previous Seasons</h2>
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
				<input id="year" class="" value="<?php echo date("Y",time()) ?>">
			</p>
			<p class="submit"><button type="button" id="btnChangeSeason">Go</button></p>
		</form>
		<?php if (count($articles) > 0): ?> </div> <?php endif; ?>
</aside> <!--/ #secondary -->
