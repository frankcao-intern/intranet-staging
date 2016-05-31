<?php
/**
 * @filename daysout.php
 * @author   : cravelo
 * @date     : 5/24/12 11:03 AM
 */

$displayname = "";
$user_id = "";
$days = val($days, array());
if (count($days) > 0){
	$display_name = val($days[0]['display_name']);
	$user_id = val($days[0]['user_id']);
}
?>
<section class="primary">
	<div class="header-a">
		<p><?=anchor('addon/attendance/index/'.date('Y')."/$user_id", "&#x25c4; $display_name Attendance Report")?></p>
		<h2>Exempt Attendance Report</h2>
	</div>
	<div class="field-group-a">
		<h2 class="c">Days Out Request</h2>
		<p><strong><?=$display_name?></strong> is planning to take the following days off.</p>

		<div class="attendance article">
			<table border=1 cellspacing=0 cellpadding=0>
				<thead>
					<tr>
						<th>Date</th><th>Type</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($days as $day): ?>
						<tr>
							<td><?=date('F d, Y', strtotime($day['date']))?></td>
							<td data-type="<?=$day['type']?>"><?=$day['name']?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<?=form_open('/addon/daysout/daysout_submit', 'class="approve-form"')?>
	<div class="field-group-a">
		<h2 class="c">Do you approve this request?</h2>
		<?=form_hidden('request_id', val($request_id))?>
		<?=form_hidden('user_id', $user_id)?>
		<?=form_hidden('days', json_encode($days))?>
		<p class="field">
			<?=form_label('Username *:', 'username')?>
			<?=form_input('username', $this->session->userdata('username'), 'id="username"')?>
		</p>
		<p class="field">
			<?=form_label('Password *:', 'password')?>
			<?=form_password('password', '', 'id="password"')?>
		</p>
		<p class="field">
			<?=form_label("If you don't approve, send a note back *:", 'msg')?>
			<?=form_textarea('msg', "Your request was denied, but let's discuss the days you've ".
					"requested to be out of the office.",
					'id="msg"')?>
		</p>
	</div>
	<div class="field-group-a">
		<p class="field">
			<?=form_submit('approve', 'Yes')?>
			<?=form_submit('reply', 'No')?>
		</p>
	</div>
	<?=form_close()?>
</section>
