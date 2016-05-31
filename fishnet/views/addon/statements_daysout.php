<?php
/**
 * Created by: cravelo
 * Date: 3/8/12
 * Time: 10:28 AM
 */

/** @var $totals array */
?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/addon/daysout_form.css?<?=FISHNET_VERSION?>" />
<div class="days-out">
	<h2 class="c">Days out requests</h2>
	<table border="0" cellspacing="0">
		<thead>
			<tr>
				<th>Date Submitted</th>
				<th>Type of days</th>
				<th>Date From</th>
				<th>Date To</th>
				<th>Total Days</th>
				<th>Status</th>
				<th>Reviewd By</th>
				<th>Reason for Rejection</th>
			</tr>
		</thead>
		<tbody>
			<?php //print_r($statements); ?>
			<?php foreach(val($statements, array()) as $statement): ?>
				<tr class="req<?=$statement['id']?>">
					<td><?=$statement['date_requested']?></td>
					<td><?=$statement['type']?></td>
					<td><?=$statement['date_from']?></td>
					<td><?=$statement['date_to']?></td>
					<td><?=$statement['total_hours'] / 8?></td>
					<td><?php
						if ($statement['status'] === 'pending'){
							echo anchor('addon/daysout/approve/'.$statement['id'], $statement['status']);
						}else{
							echo $statement['status'];
						}
					?></td>
					<td><?=
						empty($statement['reviewed_by']) ? '' :
							anchor('profiles/'.$statement['reviewed_by'], $statement['displayname'])
					?></td>
					<td><?=$statement['rejection_reason']?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfooter></tfooter>
	</table>
	<h2 class="c">Totals</h2>
	<table border="0" cellspacing="0">
		<thead>
			<tr>
				<th>Type of day</th>
				<th>Days requested</th>
				<th>Days taken</th>
			</tr>
		</thead>
		<tbody>
			<?php //print_r($totals); ?>
			<?php foreach($totals as $type => $total): ?>
				<tr>
					<td><?=$type?></td>
					<td><?=val($total['requested'], 0) / 8?></td>
					<td><?=val($total['taken'], 0) / 8?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfooter></tfooter>
	</table>
</div>
<script src="<?=STATIC_URL?>js/templates/addon/statements_daysout.js?<?=FISHNET_VERSION?>">
