<?php
/**
 * @filename daysout.php
 * @author   : cravelo
 * @date     : 5/24/12 11:03 AM
 */

$display_name = val($user['display_name']);
if (strtolower($display_name[strlen($display_name) - 1]) == 's'){
	$possesive = "$display_name'";
}else{
	$possesive = "$display_name's";
}
$user_id = val($user['user_id']);
$month = val($user['month']);
$month_name = val($user['month_name']);
$year = val($user['year']);
$request_id = val($user['request_id']);
$days = val($days, array());
?>
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/addon/attendance.css?<?=FISHNET_VERSION?>" />

<section class="primary">
	<div class="header-a">
		<p><?=anchor('addon/attendance/index/'.date('Y')."/$user_id", "&#x25c4; $display_name Attendance Report")?></p>
		<h2>Exempt Attendance Report - Signature</h2>
	</div>
	<div class="attendance article">
		<div class="field-group-a">
			<h2 class="c">
				<strong><?=$possesive?></strong> attendance report for the month of
				<strong><?=$month_name?>/<?=$year?></strong>.
			</h2>

			<?php if(count($days) > 0): ?>
				<table border=1 cellspacing=0 cellpadding=0 class="attendance">
					<thead>
						<tr>
							<th scope="column">Date</th><th scope="column">Type</th><th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($days as $day): ?>
						<tr>
							<td><?=date('F d, Y', strtotime($day['date']))?></td>
							<td data-type="<?=$day['type']?>"><?=$day['name']?></td>
							<td class="<?=$day['status']?>"><?=ucfirst($day['status'])?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<table><tr><td>
					<strong><?=$possesive?></strong> didn't take any days off on <?=$month_name?>/<?=$year?>
				</td></tr></table>
			<?php endif; ?>
		</div>
	</div>

	<?=form_open('/addon/attendance/month_submit', 'class="approve-form"')?>
	<div class="field-group-a">
		<h2 class="c">Is this report correct?</h2>
		<?=form_hidden('request_id', $request_id)?>
		<?=form_hidden('user_id', $user_id)?>
		<?=form_hidden('month', $month_name)?>
		<?=form_hidden('year', $year)?>
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
					"requested to be out of the office.\n\nMonth of $month_name/$year", 'id="msg"')?>
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
