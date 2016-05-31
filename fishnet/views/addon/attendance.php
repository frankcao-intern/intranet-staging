<?php
/**
 * Created by: cravelo
 * Date: 3/8/12
 * Time: 10:28 AM
 */

$its_me = val($its_me, false);
$its_my_boss = val($its_my_boss, false);
$cur_year = date('Y');
$cur_month = date('n');
$year = val($year, $cur_year);
$user_id = val($user_id);

$subtitle = "Year:&nbsp;<span class=\"year\">$year</span>";

if ($year == $cur_year){
	$subtitle = anchor('addon/attendance/index/'.($year - 1)."/$user_id", '&#x25c4; Show Previous Year')."&nbsp;|&nbsp;$subtitle";
}else{
	$subtitle = "$subtitle&nbsp;|&nbsp;".anchor('addon/attendance/index/'.date('Y')."/$user_id", 'Show Current Year &#x25ba;');
}

?>

<script>
	coreEngine = {
		attendance: {
			its_me: <?=$its_me ? 'true' : 'false'?>,
			its_my_boss: <?=$its_my_boss ? 'true' : 'false'?>,
			user_id: <?=$user_id?>,
			year: <?=$year?>
		}
	};
</script>

<ul id="dayTypes" class="list-b">
	<?php foreach($day_types as $day): ?>
		<li><a data-value="<?=$day['short_name']?>"><?=$day['name']?></a></li>
	<?php endforeach; ?>
	<?php if ($its_me or $its_my_boss): ?>
		<li><a class="delete">&#x25ba;Clear day</a></li>
		<li><a class="submit">&#x25ba;Submit a days out request</a></li>
	<?php endif; ?>
</ul>

<ul id="actions" class="list-b">
	<li><a class="submit">&#x25ba;Submit month for signature</a></li>
</ul>

<section class="primary">
	<?php if (!isset($tab)):?>
		<div class="header-a">
			<p><?=$subtitle?></p>
			<h2><?=$its_me ? '' : "$display_name's "?>Exempt Attendance Report</h2>
		</div>
	<?php endif; ?>

	<div class="attendance article">
		<table border="1">
			<thead>
				<tr>
					<th scope="row"></th>
					<th scope="col" data-month="1">Jan</th>
					<th scope="col" data-month="2">Feb</th>
					<th scope="col" data-month="3">Mar</th>
					<th scope="col" data-month="4">Apr</th>
					<th scope="col" data-month="5">May</th>
					<th scope="col" data-month="6">Jun</th>
					<th scope="col" data-month="7">Jul</th>
					<th scope="col" data-month="8">Aug</th>
					<th scope="col" data-month="9">Sep</th>
					<th scope="col" data-month="10">Oct</th>
					<th scope="col" data-month="11">Nov</th>
					<th scope="col" data-month="12">Dec</th>
					<th scope="row"></th>
				</tr>
			</thead>
			<tbody>
				<?php for($day = 1; $day <= 31; $day++): ?>
					<tr>
						<th scope="row"><?=$day?></th>
						<?php for($month = 1; $month <= 12; $month++): ?>
							<?php
								$item = val($days[$month][$day], array());
								$class = '';
								$month_class = val($months[$month], 'planned');
								if ((($year !== $cur_year) or ($month_class == 'confirmed')) and ($its_my_boss === false)){
									$class = 'signed';
								}
							?>
							<td class="<?=val($item['status'])." $class"?>" title="<?=val($item['name'])?>">
								<?=val($item['type'], "&nbsp;")?>
							</td>
						<?php endfor; ?>
						<th scope="row"><?=$day?></th>
					</tr>
				<?php endfor; ?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row"></th>
					<th scope="colgroup">Jan</th>
					<th scope="colgroup">Feb</th>
					<th scope="colgroup">Mar</th>
					<th scope="colgroup">Apr</th>
					<th scope="colgroup">May</th>
					<th scope="colgroup">Jun</th>
					<th scope="colgroup">Jul</th>
					<th scope="colgroup">Aug</th>
					<th scope="colgroup">Sep</th>
					<th scope="colgroup">Oct</th>
					<th scope="colgroup">Nov</th>
					<th scope="colgroup">Dec</th>
					<th scope="row"></th>
				</tr>
				<tr class="totals">
					<th scope="row">Totals</th>
					<?php for($month = 1; $month <= 12; $month++): ?>
						<td>&nbsp;</td>
					<?php endfor; ?>
					<th scope="row">Totals</th>
				</tr>
				<tr class="approved">
					<th scope="row">Sign Month</th>
					<?php for($i = 1; $i <= 12; $i++): ?>
						<td class="<?=($i < $cur_month) ? val($months[$i], 'planned') : 'empty'?>">&nbsp;</td>
					<?php endfor; ?>
					<th scope="row"></th>
				</tr>
			</tfoot>
		</table>
	</div>
</section>

<aside class="secondary article-sidebar">
	<?php if ($its_me and (count($direct_reports) > 0)): ?>
		<div class="section-a">
			<h2 class="c">Direct Reports:</h2>
			<ul class="styled-bulletlist" id="directReports">
				<?php foreach ($direct_reports as $user): ?>
				<li>
					<?=anchor(sprintf('/addon/attendance/index/%s/%s', date('Y'), $user['user_id']), $user['display_name'])?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="section-a">
		<h2 class="c">Days out codes:</h2>
		<ul class="styled-bulletlist">
			<?php foreach($day_types as $day): ?>
				<li><strong><?=$day['short_name']?></strong> = <?=$day['name']?></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="section-a attendance">
		<h2 class="c">Key:</h2>
		<p>
			<span class="color today"></span> = Today
			<span class="color planned"></span> = Planned
			<br>
			<span class="color ui-state-warning"></span> = Pending
			<span class="color confirmed"></span> = Approved
		</p>
	</div>

	<div class="section-a">
		<h2 class="c">Holiday Schedules</h2>
		<ul class="styled-bulletlist edit-wysiwygadv">
			<li><?=anchor('/documents/forms/Human%20Resources/Holiday%20Schedules/Wholesale%20Holiday%20Schedule%202012.pdf',
					'Wholesale Holiday Schedule')?></li>
			<li><?=anchor('/documents/forms/Human%20Resources/Holiday%20Schedules/Retail%20&%20Company%20Store%20Holiday%20Schedule%202012.pdf',
					'Retail Holiday Schedule')?></li>
			<li><?=anchor('/documents/forms/Human%20Resources/Holiday%20Schedules/Ecommerce%20Fulfillment%20Team%20(Secaucus)%20Holiday%20Schedule%202012.pdf',
			        'E-Commerce Holiday Schedule')?></li>
		</ul>
	</div>

	<div class="section-a">
		<h2 class="c">Employee Hadbooks</h2>
		<ul class="styled-bulletlist">
			<li><?=anchor('/documents/forms/Human%20Resources/Employee%20Handbooks/Wholesale%20Employee%20Handbook.pdf',
			              'Wholesale Employee Handbook')?></li>
			<li><?=anchor('/documents/forms/Human%20Resources/Employee%20Handbooks/Store%20Employee%20Handbook.pdf',
			              'Retail Employee Handbook')?></li>
		</ul>
	</div>
	<div class="section-a">
		<h2 class="c">How do I change days from signed months?</h2>
		<p>Talk to your leader to make the change. Only your leader can change those days that have been already
			approved and passed.</p>
	</div>
</aside>
