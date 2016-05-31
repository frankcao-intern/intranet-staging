<?php
/**
 * Created by: cravelo
 * Date: 1/23/12
 * Time: 11:14 AM
 */

$hotels = val($hotels, array());
 ?>
<script src="<?=STATIC_URL?>js/templates/user/place.js"></script>

<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=$revision['revision_text']['color']?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<?=date("l, F j, Y")?>
		</p>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>
	<div class="about">
		<p><strong>Book now!</strong> Use EILEEN FISHER special rates for your next hotel stay.</p>
	</div>
	<h2 class="c collapsible">Hotels</h2>
	<div class="content">
		<?php for ($i = 0; $i < count($hotels); $i += 2): ?>
			<div class="section-a columns double-a">
				<div class="col col-first">
					<p class="image">
						<a href="<?=site_url("article/".$hotels[$i]['page_id'])?>">
							<?php $main_image = val($hotels[$i]['revision_text']->main_image[0], array()); ?>
							<img
								src="<?php echo site_url("/images/src/".val($main_image->src, 'error')
										."/w/190/zc/118"); ?>"
								height="118" alt="<?=htmlentities(val($main_image->alt), ENT_COMPAT, 'UTF-8', false)?>" />
						</a>
					</p>
					<div class="place-info">
						<h2 class="a">
							<?=($hotels[$i]['title'] == "") ? "" : anchor('article/'.$hotels[$i]['page_id'], $hotels[$i]['title']); ?>
						</h2>
						<p class="address"><strong>Address: </strong><?=$hotels[$i]['revision_text']->address; ?></p>
						<p class="phone"><strong>Phone: </strong><?=$hotels[$i]['revision_text']->phone; ?></p>
						<p class="fax"><strong>Fax: </strong><?=$hotels[$i]['revision_text']->fax; ?></p>
						<p class="contact">
							<strong>Contact Person: </strong>
							<?=safe_mailto($hotels[$i]['revision_text']->contact_email,
								$hotels[$i]['revision_text']->contact_name)?>
						</p>
						<p class="info"><strong>Additional Information: </strong><?=$hotels[$i]['revision_text']->info; ?></p>
						<p class="links">
							<?=anchor($hotels[$i]['revision_text']->place_url, "Website",	'target="_blank" class="place-url"')?>
							<?=anchor($hotels[$i]['revision_text']->map_url, "Map &amp; Directions",
									'target="_blank" class="map-url"')?>
							<?=anchor($hotels[$i]['revision_text']->book_url, "Book Now", 'target="_blank" class="book-url"')?>
						</p>
					</div>
				</div>
				<?php if (isset($hotels[$i + 1])): ?>
					<div class="col">
						<p class="image">
							<a href="<?=site_url("article/".$hotels[$i + 1]['page_id'])?>">
								<?php $main_image = val($hotels[$i]['revision_text']->main_image[0], array()); ?>
								<img
									src="<?php echo site_url("/images/src/".val($main_image->src, 'error')
										                         ."/w/190/zc/118"); ?>"
									height="118" alt="<?=htmlentities(val($main_image->alt), ENT_COMPAT, 'UTF-8', false)?>" />
							</a>
						</p>
						<div class="place-info">
							<h2 class="a">
								<?=($hotels[$i + 1]['title'] == "") ? "" : anchor('article/'.$hotels[$i + 1]['page_id'], $hotels[$i + 1]['title']); ?>
							</h2>
							<p class="address"><strong>Address: </strong><?=$hotels[$i + 1]['revision_text']->address; ?></p>
							<p class="phone"><strong>Phone: </strong><?=$hotels[$i + 1]['revision_text']->phone; ?></p>
							<p class="fax"><strong>Fax: </strong><?=$hotels[$i + 1]['revision_text']->fax; ?></p>
							<p class="contact">
								<strong>Contact Person: </strong>
								<?=safe_mailto($hotels[$i + 1]['revision_text']->contact_email,
									$hotels[$i + 1]['revision_text']->contact_name)?>
							</p>
							<p class="info"><strong>Additional Information: </strong><?=$hotels[$i + 1]['revision_text']->info; ?></p>
							<p class="links">
								<?=anchor($hotels[$i + 1]['revision_text']->place_url, "Website",	'target="_blank" class="place-url"')?>
								<?=anchor($hotels[$i + 1]['revision_text']->map_url, "Map &amp; Directions",
										'target="_blank" class="map-url"')?>
								<?=anchor($hotels[$i + 1]['revision_text']->book_url, "Book Now",
								'target="_blank" class="book-url"')?>
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endfor; ?>
	</div>
</section> <!--/ #primary -->

<?php $this->load->view('page_parts/hp_rightcol');
