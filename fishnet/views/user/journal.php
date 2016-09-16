<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<?=date("l, F j, Y")?>
		</p>
		<h2><?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?></h2>
	</div>

	<?php if (isset($this->pagination)): ?>
		<ul class="digest-nav">
			<li class="sort-link"><a href="#" class="recent">Most Recent</a></li>
			<li class="sort-link"><a href="#" class="comments">Most Comments</a></li>
            <li class="sort-link"><a href="#" class="popular">Most Reads</a></li>
            <li class="highlighter">&nbsp;</li>
			<?=$this->pagination->create_links()?>
		</ul>

        <div id="sortOrder">
		<?php foreach(val($articles, array()) as $article): ?>
			<?php
				$articleLink = site_url("article/".$article['page_id']);
				$articleAnchor = "article/".$article['page_id'];
				$revision = $article['revision_text'];
				$src = val($revision->main_image[0]->src, 'error');
				$flip = val($revision->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($revision->main_image[0]->angle, 0);
				$img = get_image_html($src, 380, 236, $flip, $angle);
			?>
			<div class="section-a entry" id="<?php echo $article['page_id']; ?>">
				<p class="image">
					<a href="<?=site_url($articleAnchor)?>">
						<img <?=$img?> alt="<?=htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false)?>" />
					</a>
				</p>
                <!--<p class="more-a">
					<?php /*echo anchor("/article/sortOrder/".$article['page_id']."/".$article, "Up&nbsp;&uarr;");*/?>
					&nbsp | &nbsp
					<?php /*echo anchor("/article/sortOrder/".$article['page_id']."/".$article, "Down&nbsp;&darr;");*/?>
				</p>-->
				<p>
					<h3 class="b" style="text-align: left;"><?=anchor($articleAnchor, $article['title'])?></h3>
				</p>
				<div>
					&nbsp;
				</div>

                <p class="published">
					<span class="date <?=(empty($order_by) or ($order_by === 'most_recent')) ? 'highlight' : ''?>">
						<?=date("l, F d, Y", strtotime($article['date_published']))?>
					</span>
	                    &nbsp;|&nbsp;
					<span class="<?=($order_by === 'comments_count') ? 'highlight' : ''?>">
						<?=(int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments")?>
					</span>
	                    &nbsp;|&nbsp;
					<span class="<?=($order_by === 'page_views') ? 'highlight' : ''?>">
						<?=$article['page_views']." read"?>
					</span>
                </p>
				<p><?=val($revision->article, "...")?></p>
				<p class="more-a"><?=anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;")?></p>
			</div>
		<?php endforeach; ?>
        </div>
        <!--<div id="after-sort"></div>-->
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

<aside class="secondary">
	<?php $this->load->view('page_parts/tag_cloud'); ?>
</aside>
