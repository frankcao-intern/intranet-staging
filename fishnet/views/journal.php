<H1>this is mosrur</H1>
<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<?=date("l, F j, Y")?>
		</p>
		<h2><?=anchor("journal/$page_id", val($title, 'Untitled Page'))?></h2>
	</div>

	<?php if (isset($this->pagination)): ?>
		<ul class="digest-nav">
			<li class="sort-link"><a href="#" class="recent">Most Recent</a></li>
			<li class="sort-link"><a href="#" class="popular">Most Popular</a></li>
			<li class="sort-link"><a href="#" class="comments">Most Comments</a></li>
			<li class="highlighter">&nbsp;</li>
			<?=$this->pagination->create_links()?>
		</ul>

		<?php foreach(val($articles, array()) as $article): ?>
			<?php
				$articleLink = site_url("articles/".$article['page_id']);
				$articleAnchor = "articles/".$article['page_id'];
				$revision = $article['revision_text'];
				$src = val($revision->main_image[0]->src, 'error');
				$flip = val($revision->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($revision->main_image[0]->angle, 0);
				$img = get_image_html($src, 380, 236, $flip, $angle);
			?>
			<div class="section-a entry">
				<p class="image">
					<a href="<?=site_url($articleAnchor)?>">
						<img <?=$img?> alt="<?=htmlentities(val($revision->main_image[0]->alt), 2, 'UTF-8')?>" />
					</a>
				</p>
				<h3 class="b"><?=anchor($articleAnchor, $article['title'])?></h3>
				<p class="published">
					<?=date("F d, Y",strtotime($article['date_published']))?>
					&nbsp;|&nbsp;
					<?=(int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments")?>
				</p>
				<p><?=val($revision->article, "...")?></p>
				<p class="more-a"><?=anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;")?></p>
			</div>
		<?php endforeach; ?>
		<div class="section-a entry">&nbsp;<!-- this provides separation of the listing from the pagination bar --></div>

		<ul class="digest-nav">
			<li class="sort-link"><a href="#" class="recent">Most Recent</a></li>
			<li class="sort-link"><a href="#" class="popular">Most Popular</a></li>
			<li class="sort-link"><a href="#" class="comments">Most Comments</a></li>
			<li class="highlighter">&nbsp;</li>
			<?=$this->pagination->create_links()?>
		</ul>
	<?php endif; ?>

</section> <!--/ #primary -->

<aside class="secondary column-border-top">
	<?php $this->load->view('page_parts/tag_cloud'); ?>
</aside>

<script>siteRoot = "<?=base_url()?>"; </script>
