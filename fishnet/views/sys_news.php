<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo site_url("/news/rss") ?>" />
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery.maskedinput-1.2.2.js"></script>
<section class="primary">
	<div class="header-a">
		<p>
			<?php echo (isset($revision['revision_text']['back_link_id'])) ? anchor("/articles/".$revision['revision_text']['back_link_id'], "&#x25c4; ".$revision['revision_text']['back_link_title'])."&nbsp;|" : ""; ?>
			<span class="month"><?php echo $month; ?></span>&nbsp;|&nbsp;<span class="year"><?php echo $year; ?></span>
		</p>
		<h1><?php echo anchor('news/'.$page_id, $title); ?></h1>
	</div>
	<ul class="sort-links">
		<li><a href="#" class="sort-link recent">Most Recent</a></li>
		<li><a href="#" class="sort-link popular">Most Popular</a></li>
		<li class="last"><a href="#" class="sort-link comments">Most Comments</a></li>
		<li class="right"><a class="sort-link icon" href="<?php echo site_url("news/rss/$page_id"); ?>">
			<img src="<?=STATIC_URL."images/feed-icon-14x14.png"?>" alt="<?=$title?> Feed" height="14" width="14" />
		</a></li>
		<li class="highlighter">&nbsp;</li>
	</ul>
	<?php foreach ($thisMonth as $page): ?>
		<?php //print_r($page); ?>
		<div class="section-a featured-a">
			<?php if($page['title'] != ""): ?>
				<h2 class="b">
					<?php echo anchor("/articles/".$page['page_id'], $page['title']); ?>
				</h2>
			<?php endif; ?>
			<?php if(isset($page['revision_text']->main_image[0])): ?>
				<p class="image">
					<a href="<?php echo site_url("articles/".$page['page_id']); ?>">
						<img
							src="<?php echo site_url("/images/src/".$page['revision_text']->main_image[0]->src."/w/252/zc/160"); ?>"
							height="160"
							alt="<?php echo htmlentities($page['revision_text']->main_image[0]->alt, 2, 'UTF-8'); ?>" />
					</a>
				</p>
			<?php else: ?>
				<p class="image" style="width: 252px; height: 160px;"></p>
			<?php endif; ?>
			<p class="published">
				<?php echo date("l, F d, Y", strtotime($page['date_published'])); ?>
				&nbsp;|&nbsp;
				<?php echo (int)$page['comments_count']." ".(($page['comments_count'] == 1) ? "comment" : "comments"); ?>
			</p>
			<p><?php echo $page['revision_text']->article; ?></p>
			<p class="more-a"><?php echo anchor("/articles/".$page['page_id'], "Read More&nbsp;&nbsp;&#x25ba;"); ?></p>
		</div>
	<?php endforeach; ?>
</section> <!--/ #primary -->

<aside class="secondary column-border-top">
	<h2 class="c">Previous Month</h2>
	<ul class="list-a">
		<?php foreach ($lastMonth as $page): ?>
			<li>
				<p class="published"><?php echo date("F d, Y", strtotime($page['date_published'])); ?></p>
				<h3 class="d"><?php echo anchor("/articles/".$page['page_id'], $page['title']); ?></h3>
				<p><!--Design, Press Release, Textiles--></p>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="section-a">
		<h2 class="c">Search Previous Months</h2>
		<?php echo form_open($this->uri->uri_string(), 'method="post", class="search search-a"'); ?>
			<p>
				<label class="offset" for="byDate">Type a month-year</label>
				<input type="text" id="byDate" name="byDate" placeholder="Type a month-year" />
				<button id="btnByDate">Go</button>
			</p>
		<?php echo form_close(); ?>
	</div>

</aside> <!--/ #secondary -->
