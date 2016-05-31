<?php
/**
 * Created By: cravelo
 * Date: 5/9/11
 * Time: 2:37 PM
 */
?>
<script src="<?=STATIC_URL?>js/lib/jquery.tablesorter.js"></script>
<script src="<?=STATIC_URL?>js/lib/jquery.quicksearch.js"></script>

<section class="primary tablesorter-aggregator">
	<div class="filter">
		<label for="tblFilter">Filter:</label>
		<input type="text" name="tblFilter" id="tblFilter" />
	</div>

	<table class="tablesorter">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Number</th>
				<th>Type</th>
				<th>City</th>
				<th>State</th>
				<th>Opening Date</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($stores as $store): ?>
			<tr>
				<td>
					<?php
					$main_image = $store['revision_text']->main_image;
					$src = val($main_image[0]->src, 'error');
					$flip = val($main_image[0]->flip, false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[0]->angle, 0);
					$img = basename(get_image($src, false, false, $flip, $angle));
					$thumb = get_image_html($src, 32, 32, $flip, $angle);
					?>
					<a class="fancybox" href="<?=site_url("images/src/$img")?>" title="<?=$store['title']?>">
						<img alt="<?=$store['title']?>" <?=$thumb?> />
					</a>
				</td>
				<td><?=anchor("article/".$store['page_id'], $store['title'])?></td>
				<td><?=$store['revision_text']->store_number?></td>
				<td><?=$store['revision_text']->store_type?></td>
				<td><?=$store['revision_text']->city?></td>
				<td><?=$store['revision_text']->state?></td>
				<td><?=$store['revision_text']->opening_date?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</section>
