<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<span class="month"><?=val($month)?></span>&nbsp;|&nbsp;<span class="year"><?=val($year)?></span>
		</p>
		<h2><?=anchor('monthly/'.$page_id, val($title, 'Untitled Page'))?></h2>
	</div>
	<?php echo "test by mosrurrrrr"; ?>
	<?php $this->load->view('page_parts/digest_navigation') ?>

	<?php foreach (val($thisMonth, array()) as $page): ?>
		<?php //print_r($page); ?>
		<div class="section-a featured-a">
			<h2 class="b"><?=anchor("/articles/".$page['page_id'], $page['title'])?></h2>
			<?php
				$src = val($page['revision_text']->main_image[0]->src, 'error');
				$flip = val($page['revision_text']->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($page['revision_text']->main_image[0]->angle, 0);
				$img = get_image_html($src, 252, 156, $flip, $angle);
				$alt = htmlentities(val($page['revision_text']->main_image[0]->alt), 2, 'UTF-8');
			?>
			<p class="image">
				<a href="<?=site_url("articles/".$page['page_id'])?>">
					<img <?=$img?> alt="<?=$alt?>" />
				</a>
			</p>
			<p class="published">
				<?=date("l, F d, Y", strtotime($page['date_published']))?>
				&nbsp;|&nbsp;
				<?=(int)$page['comments_count']." ".(($page['comments_count'] == 1) ? "comment" : "comments")?>
			</p>
			<p><?=$page['revision_text']->article?></p>
			<p class="more-a"><?=anchor("/articles/".$page['page_id'], "Read More&nbsp;&nbsp;&#x25ba;")?></p>
		</div>
	<?php endforeach; ?>

	<div class="section-a"><!-- this provides separation of the listing from the pagination bar -->
		<?php $this->load->view('page_parts/digest_navigation') ?>
	</div>
</section> <!--/ #primary -->

<aside class="secondary column-border-top">
	<h2 class="c">Previous Month</h2>
	<ul class="list-a">
		<?php foreach (val($lastMonth, array()) as $page): ?>
			<li>
				<p class="published"><?=date("F d, Y", strtotime($page['date_published']))?></p>
				<h3 class="d"><?=anchor("/articles/".$page['page_id'], $page['title'])?></h3>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="section-a">
		<h2 class="c"><label for="byDate">Search Previous Months</label></h2>
		<?=form_open($this->uri->segment(1)."/$page_id", 'method="post", class="search search-a"')?>
			<p>
				<input type="search" id="byDate" name="byDate" placeholder="Type a month/year" />
				<button id="btnByDate">Go</button>
			</p>
		<?=form_close()?>
	</div>

</aside> <!--/ #secondary -->

<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.maskedinput-1.2.2.js"></script>
