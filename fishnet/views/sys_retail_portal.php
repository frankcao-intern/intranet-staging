<?php
/**
 * Created By: emuentes
 * Date: April 27, 2011
 * Time: 9:00:00 AM
 */

?>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.cycle.all.js"></script>
<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?php
			if (isset($edit)){
				echo anchor("/article/$page_id", "&#x25c4; Preview Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
			}

			echo "Week of ".date('F d, Y', strtotime('last monday'));
		?></p>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>
		<div id="tabs_header"><h2>Retail News and Updates</h2><p><a href="#">Download this week's PDF &#x25BA;</a> <a href="#">ARCHIVE &#x25BA;</a></p></div>
	<div id="tabs" class="tabs-bottom">
		<ul>
			<?php foreach($revision['revision_text']['news'] as $key=>$tabs): ?>
				<li id="<?=$tabs['id']?>">
					<a href="#tabs-<?=($key+1)?>">
						<?=$tabs['name']?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php foreach($revision['revision_text']['news'] as $key=>$tabs): ?>
			<div id="tabs-<?php echo ($key+1);?>">
				<div class="edit_img" id="main_image">
					<img src="<?=site_url("/images/src/".$tabs['image']['src'].'/w/256/zc/300')?>" alt="Caption" title="Credits">
				</div>
				<span class="logo">M</span><h2><?=ucfirst($tabs['name'])?> Update 4.8.11:</h2>
				<div class="text">
					<p class="edit-textarea"><?=$tabs['text']?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="columns triple-a titled-columns">
		<div class="col">
			<?php if (isset($style) and isset($style[0])): ?>
				<?php $style = $style[0]; ?>
				<h2 class="b"><?php echo anchor('article/'.$style['section_id'], $style['section_title']) ?></h2>
				<?php if (isset($style['revision_text']->main_image) and isset($style['revision_text']->main_image[0])): ?>
					<img src="<?=site_url('/images/src/'.$style['revision_text']->main_image[0]->src.'/w/252/zc/171'); ?>" width="252" height="171" alt="<?php echo $style['revision_text']->main_image[0]->alt?>" />
				<?php else: ?>
					<div style="width: 252px; height: 171px;">&nbsp;</div>
				<?php endif; ?>
				<div>
					<h3 class="b"><?=anchor("article/".$style['page_id'], $style['title'])?></h3>
					<p><?=$style['revision_text']->teaser?></p>
					<?=anchor("article/".$style['page_id'], "Read more&nbsp;&nbsp;&#x25ba;", 'class="more-b"')?>
				</div>
			<?php endif; ?>
		</div>
		<div class="col">
			<?php if (isset($product) and isset($product[0])): ?>
				<?php $product = $product[0]; ?>
				<h2 class="b"><?php echo anchor('article/'.$product['section_id'], $product['section_title']) ?></h2>
				<?php if (isset($product['revision_text']->main_image) and isset($product['revision_text']->main_image[0])): ?>
					<img src="<?=site_url('/images/src/'.$product['revision_text']->main_image[0]->src.'/w/252/zc/171'); ?>" width="252" height="171" alt="<?php echo $product['revision_text']->main_image[0]->alt?>" />
				<?php else: ?>
					<div style="width: 252px; height: 171px;">&nbsp;</div>
				<?php endif; ?>
				<div>
					<h3 class="b"><?=anchor("article/".$product['page_id'], $product['title'])?></h3>
					<p><?=$product['revision_text']->teaser?></p>
					<?=anchor("article/".$product['page_id'], "Read more&nbsp;&nbsp;&#x25ba;", 'class="more-b"')?>
				</div>
			<?php endif; ?>
		</div>
		<div class="col feed">
			<?php if (isset($scoop) and isset($scoop[0])): ?>
				<h2 class="b"><?php echo anchor('article/'.$scoop['section_id'], $scoop['section_title']) ?></h2>
				<div>
					<?php foreach($scoop as $key => $articles): ?>
						<p class="strong"><?=anchor("article/".$articles['page_id'], $articles['title'])?></p>
						<p><?=$articles['revision_text']->article?></p>
						<p class="date">posted at 12:00am</p>
					<?php endforeach; ?>
					<?=anchor("sections/".$revision['revision_text']['buckets'][2]['section_id'], "Read all posts&nbsp;&nbsp;&#x25ba;", 'class="more-b"')?>
				</div>
			<?php endif; ?>
		</div>
	</div> <!--/ .triple-a -->

</section> <!--/ #primary -->

<aside class="secondary">
	<h2 class="c"><span>&nbsp;</span><?=anchor("calendar/".$revision['revision_text']['calendar'], "Store Calendar")?></h2>
	<div id="miniCalendar" class="mini-calendar"><?=$revision['revision_text']['calendar']?></div>
	<dl class="event">
		<dt class="date"><?="Today:&nbsp;".date("D, F j, Y")?></dt>
	</dl>

	<?php if (isset($announcements) and (count($announcements) > 0)): ?>
		<div class="section-a">
			<h2 class=" collapsible c">Announcements</h2>
			<div>
				<?php foreach($announcements as $announcement): ?>
					<p><strong><?=$announcement->start_date; ?></strong> - <?php echo $announcement->event_title?></p>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<ul class='edit_links' id="links">
		<?php
			$links = json_decode($revision['revision_text']['links'], true);
			foreach($links as $link): ?>
			<li class="section-a space-top-0">
				<h2 class="c"><?=anchor(prep_url($link['url']), $link['title'].' >')?></h2>
			</li>
		<?php endforeach; ?>
	</ul>
	<h2 class="c"><a href="#">Question of the week</a></h2>
	<div id="poll">
	<p class="strong"><a href="#">SKINNY JEANS:</a></p>
	<ul>
		<p>How is the length?</p>
		<li>too long</li>
		<li>too short</li>
		<li>just right</li>
		</ul>

	<ul>
		<p>How is the length?</p>
		<li>too long</li>
		<li>too short</li>
		<li>just right</li>
		</ul>

	<ul>
		<p>How is the length?</p>
		<li>too long</li>
		<li>too short</li>
		<li>just right</li>
		</ul>
	<p class="strong"><a href="#">LAST WEEKS SURVEY >></a></p>
	<p>Twinkle Linen Jacket fit is broad in shoulders, best for short waisted customer, overall an easy sell. See comments.</p>
	<p class="strong"><a href="#">ARCHIVE >></a></p>
	</div>
	<div class="section-a">
	<h2 class="c"><a href="#">STORE QUICK LINKS</a></h2>
	<ul>
		<li>Store Locator</li>
		<li>Store Who's Who</li>
		<li>Who to Call List</li>
		<li>Fishnet/Our Product</li>
		<li>Buying & Planning</li>
		<li>Human Relations</li>
		<li>Marketing</li>
		<li>Visuals</li>
		<li>Operations</li>
		<li>LL&amp;D</li>
		<li>EileenFisher.com</li>
		<li>UPS</li>
		<li>FedEX</li>
	</ul>
	</div>
</aside> <!--/ #secondary -->
