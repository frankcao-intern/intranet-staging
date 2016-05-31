<?php
/**
 * @filename sys_store_list.php
 * @author   : cravelo
 * @date     : 6/18/12 8:53 PM
 */

$stores = val($stores, array());
?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/datatables/jquery.dataTables.css?<?=FISHNET_VERSION?>" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/datatables/jquery.dataTables_jqueryui.css?<?=FISHNET_VERSION?>" />

<section class="primary">
	<div class="header-a" style="border-left: 12px solid <?=val($revision['revision_text']['color'], "black")?>">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			<?=date("l, F j, Y")?>
		</p>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>
	<div class="table-container">
		<table border="1" id="stores">
			<thead>
				<tr>
					<th>Store Name</th>
					<th>City</th>
					<th>State</th>
					<th>Zip</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($stores as $store): ?>
				<?php
					if (isset($store['link']) and !empty($store['link'])){
						$store['name'] = anchor($store['link'], $store['name']);
					}
				?>
				<tr>
					<td><?=$store['name']?></td>
					<td><?=$store['city']?></td>
					<td><?=$store['state']?></td>
					<td><?=$store['zip']?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="edit-wysiwygadv clearfix desc article" data-key="desc">
		<?php if (isset($revision['revision_text']['desc']) and !empty($revision['revision_text']['desc'])): ?>
			<?=$revision['revision_text']['desc']?>
		<?php else: ?>
			<?=isset($edit) ? "Enter a description here." : ""?>
		<?php endif;?>
	</div>
</section> <!--/ #primary -->

