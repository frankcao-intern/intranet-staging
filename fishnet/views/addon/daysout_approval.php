<?php
/**
 * Created by: cravelo
 * Date: 2/24/12
 * Time: 11:48 AM
 */

$total_hours = 0;

?>

<section class="primary">
	<?php
		echo form_open('addon/daysout/mgrformproc', 'class="days-out"');
		echo form_hidden('mgr_user_id', $this->session->userdata('user_id'));
		echo form_hidden('email', val($lines[0]['email']));
		echo form_hidden('id', val($lines[0]['id']));
	?>
		<div class="field-group-a">
			<h2 class="c">General information</h2>
			<div class="field">
				<p class="one">
					<?=form_label('Name: ', 'displayname')?>
					<?=form_input('displayname', val($lines[0]['displayname']), 'id="displayname" readonly')?>
				</p>
				<p class="two">
					<?=form_label('Date: ', 'date')?>
					<?=form_input('date', date('Y-m-d', strtotime(val($lines[0]['timestamp']))),
						'id="date" readonly')?>
				</p>
			</div>
			<div class="field">
				<p class="one">
					<?=form_label('Location: ', 'location')?>
					<?=form_input('location', val($lines[0]['location']), 'id="location" readonly')?>
				</p>
				<p class="two">
					<?=form_label('This is a: ', 'use_notice')?>
					<?=form_input('use_notice', val($lines[0]['use_notice'], 0) ? 'Use Notice' : 'Request',
						'id="use_notice" readonly')?>
				</p>
			</div>
		</div>
		<div class="field-group-a">
			<h2 class="c">Days requested</h2>
			<table>
				<thead>
					<tr>
						<th>Type of day
						<th>From
						<th>To
						<th>Total
				</thead>
				<tbody>
					<?php foreach(val($lines, array()) as $line): ?>
						<tr>
							<td><?=$line['type']?>
							<td><?=$line['date_from']?>
							<td><?=$line['date_to']?>
							<td><?=$line['total_hours'] / 8?>
							<?php $total_hours += $line['total_hours']; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="text_right" colspan="3">TOTAL:
						<td><?=val($total_hours) / 8?>
				</tfoot>
			</table>
		</div>
		<div class="field-group-a">
			<h2 class="c">Leader *</h2>
			<p><strong>IMPORTANT NOTE:</strong> Enter your windows username and password:</p>
			<p>Your name: <?=val($mgrDisplayname)?></p>
			<p class="field">
				<?=form_label("Username:", 'mgrName')?>
				<?=form_input('mgr_username', val($mgrUsername), 'id="mgrName"')?>
			</p>
			<p class="field">
				<?=form_label("Password:", 'mgrPassw')?>
				<?=form_password('mgr_passw', '', 'id="mgrPassw"')?>
			</p>
			<p class="field">
				<?=form_label("Reason to reject:", 'rejectionReason')?>
				<?=form_textarea('rejection_reason', '', 'id="rejectionReason"')?>
			</p>
		</div>
		<div class="field-group-a">
			<p class="submit">
				<?=form_submit('action', 'Approve');?>
				<?=form_submit('action', 'Reject');?>
			</p>
		</div>
	<?=form_close();?>
</section>
