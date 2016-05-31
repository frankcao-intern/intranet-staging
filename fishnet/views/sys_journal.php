<section class="primary">
	<div class="header-a">
		<p>
			<?=(isset($revision['revision_text']['back_link_id'])) ?
					anchor("/articles/".$revision['revision_text']['back_link_id'], "&#x25c4; ".
						$revision['revision_text']['back_link_title'])."&nbsp;|" : "";
			?>
			<?=date("l, F j, Y")?>
		</p>
		<h1><?=$title?></h1>
	</div>
	<?php echo $this->pagination->create_links(); ?>
	
	<?php if (count($articles) > 0): ?>
		<?php foreach($articles as $article): ?>
			<?php $articleLink = site_url("articles/".$article['page_id']); ?>
			<?php $articleAnchor = "articles/".$article['page_id']; ?>

			<div class="section-a entry">
				<?php $revision = $article['revision_text']; ?>

				<?php if (isset($revision->main_image[0]->src)): ?>
					<p class="image">
						<a href="<?php echo $articleLink; ?>">
							<img src="<?php echo site_url('images/src/' . $revision->main_image[0]->src."/w/380/zc/236"); ?>" width="380" height="235.5" alt="<?php echo $revision->main_image[0]->alt; ?>" />
						</a>
					</p>
				<?php endif; ?>

				<h2 class="a"><?php echo anchor($articleAnchor, $article['title']); ?></h2>

				<p class="published"><?php echo date("F d, Y",strtotime($article['date_published'])); ?></p>
				<p><?php echo isset($revision->article) ? $revision->article : "..."; ?></p>
				<p class="more-a"><?php echo anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;"); ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="section-a entry">&nbsp;<!-- this provides separation of the listing from the pagination bar --></div>

	<?php echo $this->pagination->create_links(); ?>
</section> <!--/ #primary -->

<?php $this->load->view('includes/hp_rightcol'); ?>
