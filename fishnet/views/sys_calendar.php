<?php
/**
 * User: cravelo
 * Date: Aug 9, 2010
 * Time: 5:21:01 PM
 * calendar template
 */
?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo site_url("/calendar/rss/".$page_id) ?>" />

<link rel="stylesheet" media="screen, projection" href="<?=STATIC_URL?>css/lib/fullcalendar/fullcalendar.css" />
<link rel="stylesheet" media="print" href="<?=STATIC_URL?>css/lib/fullcalendar/fullcalendar.print.css" />

<div title="Add Calendar" id="addCalDiag" class="hide">
	<label for="acdCalName">Start typing the name of a calendar:</label>
	<input id="acdCalName" type="text" />
	<label for="acdCalID">OR If you know the calendar ID:</label>
	<input id="acdCalID" type="text" />
</div>

<section class="primary">
	<div class="header-a calendar-header" style="border-left: 12px solid <?=$revision['revision_text']['color']?>">
		<p><?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?></p>
		<a class="question-mark" href="<?=site_url("/about/calendar#newevent")?>" title="How do I submit an event to be posted to the company calendar?">
			How do I submit an event to be posted to the company calendar?
		</a>
		<h2><?=anchor("calendar/$page_id", $title)?></h2>
	</div>

	<div id="calendar"></div>
</section> <!--/ #primary -->

<aside class="secondary">
	<div class="section-a">
		<h2 class="c">Selected Calendars</h2>
		<button id="btnFeed">Add a Calendar</button>
		<ul id="selectedCalendars"></ul>
	</div>
	<div class="section-a">
		<h2 class="c">Jump to Month</h2>
		<form action="" method="post" class="search search-a">
			<p>
				<label for="byDate" class="offset">Type a month-year</label>
				<input type="search" id="byDate" name="byDate" placeholder="mm-yyyy" />
				<button id="btnByDate">Go</button>
			</p>
		</form>
	</div>
</aside> <!--/ #secondary -->

<script>
    coreEngine = {
        canPublish: <?=(isset($canPublish) and $canPublish) ? 'true' : 'false'?>,
        calendar: {
            name: "<?=$title?>"
        }
    };
</script>
