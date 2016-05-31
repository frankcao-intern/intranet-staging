<?php
$news = val($news, array());
$newsCount = count($news);
$prev = val($prev, array());
?>

<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], '#000000')?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?>
		</p>
		<h2>
			<?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?> >
			<span class="month"><?=val($month)?></span>
			<span class="year"><?=val($year)?></span>
		</h2>
	</div>

	<?php $this->load->view('page_parts/digest_navigation') ?>

	<ol class="digest-list">
	<?php for ($i = 0; $i < $newsCount; $i++): ?>
		<li class="section-a featured-a">
			<h3 class="b"><?=anchor("/article/".$news[$i]['page_id'], $news[$i]['title'])?></h3>
<!--            <span class="counter">--><?//=$i + 1?><!--</span>-->
			<?php
				$src = val($news[$i]['revision_text']->main_image[0]->src, 'error');
				$flip = val($news[$i]['revision_text']->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($news[$i]['revision_text']->main_image[0]->angle, 0);
				$img = get_image_html($src, 252, 156, $flip, $angle);
				$alt = htmlentities(val($news[$i]['revision_text']->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false);
			?>
			<p class="image">
				<a href="<?=site_url("article/".$news[$i]['page_id'])?>">
					<img <?=$img?> alt="<?=$alt?>" />
				</a>
			</p>
			<p class="published">
				<span class="date <?=(empty($order_by) or ($order_by === 'most_recent')) ? 'highlight' : ''?>">
					<?=date("l, F d, Y", strtotime($news[$i]['date_published']))?>
				</span>
				&nbsp;|&nbsp;
				<span class="<?=($order_by === 'comments_count') ? 'highlight' : ''?>">
					<?=(int)$news[$i]['comments_count']." ".(($news[$i]['comments_count'] == 1) ? "comment" : "comments")?>
				</span>
                &nbsp;|&nbsp;
				<span class="<?=($order_by === 'page_views') ? 'highlight' : ''?>">
					<?=$news[$i]['page_views']." read"?>
				</span>
			</p>
			<p><?=$news[$i]['revision_text']->article?></p>
			<p class="more-a"><?=anchor("/article/".$news[$i]['page_id'], "Read More&nbsp;&#x25ba;")?></p>
		</li>
	<?php endfor; ?>
	</ol>

	<div class="section-a"><!-- this provides separation of the listing from the pagination bar -->
		<?php $this->load->view('page_parts/digest_navigation') ?>
	</div>
</section> <!--/ #primary -->

<aside class="secondary">
	<?php $last_month = val($last_month, array());?>
	<?php if (count($last_month) > 0): ?>
	<div class="section-a">
		<h2 class="c">
			<?=anchor(implode('/', array_merge(array($this->uri->segment(1), $page_id), $prev)),
		             date('F Y', strtotime('01-'.implode('-', $prev)))) ?>
		</h2>
		<ul class="list-a">
			<?php foreach ($last_month as $page): ?>
				<li>
					<h3 class="d"><?=anchor("/article/".$page['page_id'], $page['title'])?></h3>
                    <p class="published"><?=date("F d, Y", strtotime($page['date_published']))?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="section-a">
		<h2 class="c"><label for="byDate">Search Previous Months</label></h2>
		<?=form_open($this->uri->segment(1)."/$page_id", 'method="post", class="search search-a"')?>
			<p>
				<input type="search" id="byDate" name="byDate" placeholder="e.g. 05/2012" />
				<button id="btnByDate">Go</button>
			</p>
		<?=form_close()?>
	</div>

</aside> <!--/ #secondary -->
