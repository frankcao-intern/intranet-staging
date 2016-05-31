<?php
/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 9:41 AM
 */
 ?>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?></p>
		<h2 class="a"><?=val($title, 'Untitled Page')?></h2>
	</div>

	<?php $main_image = val($revision['revision_text']['main_image'], array()); ?>
	<div id="photos" class="clearfix">
		<?php for ($i = 0; $i < count($main_image); $i++): ?>
			<?php $date = val($main_image[$i]['date'], strtotime('1970-01-01 00:00:00 GMT-0500')); ?>
			<div class="item" data-date="<?=$date*1000?>">
				<?php
					$src = val($main_image[$i]['src'], 'error');
					$flip = val($main_image[$i]['flip'], false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[$i]['angle'], 0);
					$img = get_image($src, false, false, $flip, $angle);
					$img = basename($img);
					$thumb = get_image_html($src, 172, false, $flip, $angle);

					$title = '';
					$title .= empty($main_image[$i]['credit']) ? '' :
								'By: '.ucfirst(htmlentities($main_image[$i]['credit'], ENT_COMPAT, 'UTF-8', false)).' - ';
					$title .= date('m-d-Y', $date);
					$title .= empty($main_image[$i]['alt']) ? '' :
								' - '.ucfirst(htmlentities($main_image[$i]['alt'], ENT_COMPAT, 'UTF-8', false));
				?>
				<a href="<?=site_url("images/src/$img")?>" class="fancybox" rel="photos" title="<?=$title?>">
					<img <?=$thumb?> alt="<?=$title?>" />
				</a>
				<div class="caption">
					<?=$title?>
				</div>
			</div>
		<?php endfor; ?>

		<?php for ($i = 0; $i < count(val($photos, array())); $i++): ?>
			<?php $main_image = $photos[$i]['revision_text']->main_image; ?>
			<?php $date = val($main_image[0]->date, strtotime('1970-01-01 00:00:00 GMT-0500')); ?>
			<div class="item" data-date="<?=$date*1000?>">
				<?php
					$src = val($main_image[0]->src, 'error');
					$flip = val($main_image[0]->flip, false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[0]->angle, 0);
					$thumb = get_image_html($src, 172, false, $flip, $angle);

					$title = '';
					$title .= empty($main_image[0]->credit) ? '' :
								'By: '.ucfirst(htmlentities($main_image[0]->credit, ENT_COMPAT, 'UTF-8', false)).' - ';
					$title .= date('m-d-Y', $date);
					$title .= empty($main_image[0]->alt) ? '' :
								' - '.ucfirst(htmlentities($main_image[0]->alt, ENT_COMPAT, 'UTF-8', false));
				?>
				<a href="<?=site_url('article/'.$photos[$i]['page_id'])?>" title="<?=$title?>">
					<img <?=$thumb?> alt="<?=$title?>" />
				</a>
				<div class="caption">
					<?=$title?>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</section>

<aside class="secondary">
	<?php $this->load->view('page_parts/tag_cloud'); ?>

	<div class="section-a">
		<h2 class="c">Guidelines for submitting your pictures to EF Photos</h2>
		<p>
			Do you want to recognize someone or a group?<br>
			Have you attended an event that showcased EILEEN FISHER?<br>
			Are you hosting a special workshop?<br>
			Do you have an inspirational image to share?<br>
			If so, we want your pictures!<br>
		</p>

		<ul class="styled-bulletlist">
			<li>Photo content: All images must be EILEEN FISHER related.
			<li>Format: jpeg
			<li>Image size: < 1 MB
			<li>Name of photograph
			<li>Caption: Please describe who is in the picture, what is happening in the picture and where the picture
				was taken.
			<li>Photo credit: name of photographer
			<li>Why you choose this picture?
		</ul>

		<p>Please send all submissions to fishnet@eileenfisher.com</p>

		<h3 class="c">For your info…</h3>
		<p>
			By submitting your images, you are consenting to share your pictures on fishNET.  (And, if it makes sense,
			we may want to use it on our social media channels.)  The fishNET team reserves the right to choose when
			and	what images we will publish.
		</p>
	</div>
</aside>
