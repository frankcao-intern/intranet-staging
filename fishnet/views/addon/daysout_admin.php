<?php
/**
 * Created by: cravelo
 * Date: 4/3/12
 * Time: 1:58 PM
 */
 
?>

<!--h2 class="c">Attendance Report</h2>
<label for="userName">Enter someone's name and select from the list: </label>
<input id="userID" type="hidden" />
<input id="userName" type="text" />
<div id="attendanceReport"></div-->

<div class="columns double-a">
	<?=form_open('/addon/daysout/getreport', 'class="col"')?>
		<div class="field-group-a">
			<h2 class="c">Days requested for this pay period</h2>
			<div class="field">
				<label for="dateFrom">Payperiod Starts:</label>
				<input type="date" id="dateFrom" name="date_from" />
			</div>
			<div class="field">
				<label for="dateTo">Payperiod Ends:</label>
				<input type="date" id="dateTo" name="date_to" />
			</div>
			<div class="field">
				<input type="submit" value="Get Days Out Report" />
			</div>
		</div>
	<?=form_close()?>
	<?=form_open('/addon/daysout/getusenotices', 'class="col"')?>
		<div class="field-group-a">
			<h2 class="c">Use Notices since last payroll</h2>
			<div class="field">
				<label for="dateRequested">When did you run the last payroll?</label>
				<input type="date" id="dateRequested" name="date_since" />
			</div>
			<div class="field">
				<input type="submit" value="Get Days Out Report" />
			</div>
		</div>
	<?=form_close()?>
</div>
