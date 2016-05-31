<?php
/**
 * @filename attendance_admin.php
 * @author   : cravelo
 * @date     : 8/16/12 5:07 PM
 */
?>

<div class="field-group-a">
	<h2 class="c">Reports</h2>
	<ul class="styled-bulletlist">
		<li><?=anchor('/addon/attendance/getreport', 'YTD Attendance')?></li>
		<li><?=anchor('/addon/attendance/getexceptionreport', 'Exception Report - Non compliant employees')?></li>
	</ul>
</div>

<div class="field-group-a">
	<h2 class="c">View a user's attendance report</h2>
	<form id="attendance" action="" method="post" class="search">
		<p>
			<input type="hidden" id="user_id" />
			<label for="whoSearchQuery" class="offset">Search the Employee Directory</label>
			<input type="search" id="attnQuery" name="attnQuery"
			       placeholder="Type here to begin your search" />
			<button type="submit" id="attnSubmit">Go</button>
		</p>
	</form>
</div>
