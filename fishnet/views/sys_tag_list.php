<section class="primary">
	<div class="header-a">
		<p><?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?></p>
		<h2>
			<?=isset($revision['revision_text']['back_link_title']) ?
				anchor('article/'.$revision['revision_text']['back_link_id'],
				       $revision['revision_text']['back_link_title']) : ''?>
			Tag: <?=val($title, 'Untitled Page')?></h2>
	</div>

	<?php if (count(val($articles, array())) > 0): ?>
		<ul class="digest-nav">
			<?=$this->pagination->create_links()?>
		</ul>

		<ul class="search-results-a columns quadruple-a section-a">
		<?php foreach($articles as $article): ?>
			<?php $revision = $article['revision_text']; ?>
			<li class="col">
				<p class="image">
					<a href="<?=site_url("article/".$article['page_id'])?>">
						<?php
						$src = val($article['revision_text']->main_image[0]->src, 'error');
						$flip = val($article['revision_text']->main_image[0]->flip, false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($article['revision_text']->main_image[0]->angle, 0);
						$img = get_image_html($src, 189, 117, $flip, $angle);
						?>
						<img <?=$img?> alt="<?=$article['title']?>" />
					</a>
				</p>
				<div>
					<h2 class="b"><?=anchor("article/".$article['page_id'], $article['title'])?></h2>
					<p><?=isset($revision->teaser) ? $revision->teaser : "&nbsp;"?></p>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>

		<div class="section-a">
			<ul class="digest-nav">
				<?=$this->pagination->create_links()?>
			</ul>
		</div>
	<?php else: ?>
		<p>There are no pages in this category.</p>
	<?php endif; ?>
</section> <!--/ #primary -->

<?php if (isset($all_tags)): ?>
	<aside class="secondary">
		<?php $this->load->view('page_parts/tag_cloud'); ?>
	</aside>
<?php endif; ?>
