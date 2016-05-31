<?php
/**
 * User: cravelo
 * Date: Aug 17, 2010
 * Time: 10:57:26 AM
 * form to submit a new event
 */
?>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=isset($breadcrumbs) ? anchor("article/{$breadcrumbs['section_id']}", '&#x25c4;&nbsp;Cancel') : "&nbsp;"?></p>
		<?php if (isset($event['event_title'])): ?>
			<h2>Updating: <?=$event['event_title']?></h2>
		<?php else: ?>
			<h2>Calendar New Event</h2>
		<?php endif; ?>
	</div>

	<div id="newEventForm">
		<div class="field-group-a">
			<h2 class="c">General Event Information</h2>

			<p class="field">
				<label for="event_title">Title: *</label>
				<input name="event_title" id="event_title" type="text" value="<?=isset($event['event_title']) ? $event['event_title'] : ""?>" />
				<?php if(isset($event['event_id'])): ?>
					<input name="event_id" id="event_id" type="hidden" value="<?=$event['event_id']?>"/>
				<?php endif; ?>
			</p>
			<div class="field">
				<p class="one">
					<?=form_label('Where:', 'where')?>
					<?php
						$locations = array(
							"Irvington",
							"Creative Center",
							"Secaucus Distribution Center",
							"Retail Stores",
							"Other"
						);
						echo form_dropdown('where', $locations, isset($event['where']) ? array_search($event['where'], $locations) : "", 'id="where"');
					?>
				</p>
				<p class="two">
					<label for="where_room">Specify on the location</label>
					<input type="text" id="where_room" name="where_room" value="<?=isset($event['where_room']) ? $event['where_room'] : ""?>" />
				</p>
			</div>
			<p class="field">
				<label for="organizer_name">Organizer: *</label>
				<input type="text" id="organizer_name" name="organizer_name" value="<?=isset($event['organizer_name']) ? $event['organizer_name'] : $this->session->userdata('display_name')?>"/>
				<input type="hidden" id="organizer" name="organizer" value="<?=isset($event['organizer']) ? $event['organizer'] : $this->session->userdata('user_id')?>" />
			</p>
		</div>
		<div class="field-group-a">
			<h2 class="c">When</h2>

			<?php
				if (isset($event['start_time']) and isset($event['end_time'])){
					$start_time = strtotime($event['start_time']);
					$end_time = strtotime($event['end_time']);
				}else{
					$round_numerator = 60 * 15; // 60 seconds per minute * 15 minutes equals 900 seconds
					//$round_numerator = 60 * 60 or to the nearest hour
					//$round_numerator = 60 * 60 * 24 or to the nearest day

					// Calculate time to nearest 15 minutes in the future (for nearest 15 minutes back or forward use round instead of ceil)
					$start_time = (ceil(time() / $round_numerator) * $round_numerator);
					$end_time = strtotime("+30 mins", $start_time);
				}

				$start_time = date("h:i a", $start_time);
				$end_time = date("h:i a", $end_time);

				$end_date = (isset($event['end_date']) and (is_string($event['end_date']) or is_null($event['end_date']))) ?
								$event['end_date'] :
								date("Y-m-d");
			?>

			<div class="one">
				<p class="field">
					<label for="start_date">From: *</label>
					<input type="text" id="start_date" name="start_date" value="<?=isset($event['start_date']) ? $event['start_date'] : date("Y-m-d")?>"/>
				</p>
				<p class="field">
					<label for="start_time">Start Time: *</label>
					<input type="text" id="start_time" name="start_time" value="<?=$start_time?>" />
				</p>
			</div>
			<div class="two">
				
				<p class="field">
					<label for="end_date">To: </label>
					<?php

					?>
					<input type="text" id="end_date" name="end_date" value="<?=$end_date?>"/>
					<a href="#" title="For recurring events to display correctly the end date cannot be the same as the start date." style="color: #464646; max-width: 30px;" >
						<img src="<?=STATIC_URL?>images/tooltip_questionmark.png" /></a>
	
				</p>
				
				<p class="field">
					<label for="end_time">Time End: *</label>
					<input type="text" name="end_time" id="end_time" value="<?=$end_time?>" />
				</p>
			</div>

			<p class="checkbox-a" style="clear: both;">
				<?=form_checkbox('allDayEvent', 0, isset($event['all_day']) and $event['all_day'], 'id="allDayEvent"')?>
				<label for="allDayEvent">All Day Event?</label>
			</p>
		</div>
		<div class="field-group-a">
			<h2 class="c collapsible collapsible-closed">Event Description</h2>
			<div class="event-desc">
				<label for="event_desc" class="hide">Event Description:</label>
				<textarea id="event_desc" name="event_desc" rows="8" cols="90"><?=
					isset($event['event_desc']) ? $event['event_desc'] : ""
				?></textarea>
			</div>
		</div>
		<form method="" action="" id="recurrenceForm"><!--this form tag is only for be able to serialize with jquery-->
			<div class="field-group-a">
				<h2 class="c collapsible collapsible-closed">Recurrence</h2>
				<div>
					<ul id="recurrence">
						<li>
							<?=form_radio('recurrence', 0, true, 'id="recurrence0"')?>
							<label for="recurrence0">&nbsp;None</label>
						</li>
						<li>
							<?=form_radio('recurrence', 1, (isset($event['recurrence']) and ($event['recurrence'] == '1')), 'id="recurrence1"')?>
							<label for="recurrence1">&nbsp;Daily</label>
						</li>
						<li>
							<?=form_radio('recurrence', 2, (isset($event['recurrence']) and ($event['recurrence'] == '2')), 'id="recurrence2"')?>
							<label for="recurrence2">&nbsp;Weekly</label>
						</li>
						<li>
							<?=form_radio('recurrence', 3, (isset($event['recurrence']) and ($event['recurrence'] == '3')), 'id="recurrence3"')?>
							<label for="recurrence3">&nbsp;Monthly</label>
						</li>
						<li>
							<?=form_radio('recurrence', 4, (isset($event['recurrence']) and ($event['recurrence'] == '4')), 'id="recurrence4"')?>
							<label for="recurrence4">&nbsp;Yearly</label>
						</li>
					</ul>
					<div id="recurrenceTabs">
						<div id="tabs-1">
						
							<label for="daily">
								Every&nbsp;
								<input id="daily" name="daily" type="text" value="<?=isset($event['daily']) ? $event['daily'] : "1"?>"/>
								&nbsp;days.
							</label>
						</div>
						<div id="tabs-2">

							<label for="weekly">
								Every&nbsp;
								<input id="weekly" name="weekly" type="text" value="<?=isset($event['weekly']) ? $event['weekly'] : "1"?>"/>
								&nbsp;weeks.
							</label>
							<br><br>
							<label class="description">On: </label>
							<span>
								<?=form_checkbox('monday', 3, (isset($event['monday'])), 'id="weekly_1"')?>
								<label for="weekly_1">Monday</label>
								<?=form_checkbox('tuesday', 3, (isset($event['tuesday'])), 'id="weekly_2"')?>
								<label for="weekly_2">Tuesday</label>
								<?=form_checkbox('wednesday', 3, (isset($event['wednesday'])), 'id="weekly_3"')?>
								<label for="weekly_3">Wednesday</label>
								<?=form_checkbox('thursday', 3, (isset($event['thursday'])), 'id="weekly_4"')?>
								<label for="weekly_4">Thursday</label>
								<?=form_checkbox('friday', 3, (isset($event['friday'])), 'id="weekly_5"')?>
								<label for="weekly_5">Friday</label>
								<?=form_checkbox('saturday', 3, (isset($event['saturday'])), 'id="weekly_6"')?>
								<label for="weekly_6">Saturday</label>
								<?=form_checkbox('sunday', 3, (isset($event['sunday'])), 'id="weekly_7"')?>
								<label for="weekly_7">Sunday</label>
							</span>
						</div>
						
						<div id="tabs-3">
							<?=form_radio('monthly_choice', 1,
									(isset($event['monthly_choice']) and ($event['monthly_choice'] == '1')),
							        'id="monthly_choice_1"')?>

							<label for="monthly_day">On day: </label>
							<input id="monthly_day" name="monthly_day" type="text"
							       value="<?=isset($event['monthly_day']) ? $event['monthly_day'] : date("d")?>" />
							<label for="monthly_1">
								of every
								<input id="monthly_1" name="monthly_1" type="text"
								       value="<?=isset($event['monthly_1']) ? $event['monthly_1'] : "1"?>" />
								months.
							</label>
							<br><br>
							<?=form_radio('monthly_choice', 2,
									(isset($event['monthly_choice']) and ($event['monthly_choice'] == '2')),
							        'id="monthly_choice_2"')?>

							<label for="monthly_choice_2"> On the: </label>
							<?php
								echo form_dropdown('monthly_cardinal', array(
									"First",
									"Second",
									"Third",
									"Fourth",
									"Last"
								), isset($event['monthly_cardinal']) ? $event['monthly_cardinal'] : "", 'id="monthly_cardinal"');
							?>
							<?php
								echo form_dropdown('monthly_weekday', array(
									"Monday",
									"Tuesday",
									"Wednesday",
									"Thursday",
									"Friday",
									"Saturday",
									"Sunday"
								), isset($event['monthly_weekday']) ? $event['monthly_weekday'] : "", 'id="monthly_weekday"');
							?>
							<label for="monthly_2">of every
								<input id="monthly_2" name="monthly_2" type="text"
									   value="<?=isset($event['monthly_2']) ? $event['monthly_2'] : "1"?>"/>
								months.
							</label>
						</div>
						<div id="tabs-4">
							<label for="yearly">
								Every
								<input id="yearly" name="yearly" type="text"
									   value="<?=isset($event['yearly']) ? $event['yearly'] : "1"?>"/>
								years.
							</label>
							<br><br>

							<?=form_radio('yearly_choice', 1,
									(isset($event['yearly_choice']) and ($event['yearly_choice'] == '1')),
							        'id="yearly_choice_1"')?>

							<label for="yearly_choice_1">On: </label>
							<?php
								echo form_dropdown('yearly_month', array(
									"January",
									"February",
									"March",
									"April",
									"May",
									"June",
									"July",
									"August",
									"September",
									"October",
									"November",
									"December"
								), isset($event['yearly_month']) ? $event['yearly_month'] : "", 'id="yearly_month"');
							?>
							<label for="yearly_month_day">/</label>
							<input id="yearly_month_day" name="yearly_month_day" type="text"
							       value="<?=isset($event['yearly_month_day']) ? $event['yearly_month_day'] : date("d")?>"/>
							<br><br>

							<?=form_radio('yearly_choice', 2,
									(isset($event['yearly_choice']) and ($event['yearly_choice'] == '2')),
						            'id="yearly_choice_2"')?>
							<label for="yearly_choice_2">On the: </label>

							<select id="yearly_cardinal" name="yearly_cardinal">
								<option selected="true">First</option>
								<option>Second</option>
								<option>Third</option>
								<option>Fourth</option>
								<option>Last</option>
							</select>
							<select id="yearly_weekday" name="yearly_weekday">
								<option selected="true">Monday</option>
								<option>Tuesday</option>
								<option>Wednesday</option>
								<option>Thursday</option>
								<option>Friday</option>
								<option>Saturday</option>
								<option>Sunday</option>
							</select>
							<label for="yearly_cardinal_month">of: </label>
							<select id="yearly_cardinal_month" name="yearly_cardinal_month">
								<option value="1" selected="true">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
						</div>
					</div>
				</div>

				<!--p class="field clear">
					<label for="endAfter">End after </label><input id="endAfter" name="endAfter" type="text" /> occurrences (This will set the end date accordingly) </label>
				</p-->
			</div><!--recurrence-->
		</form>

		<?php if (isset($event)): ?>
			<div class="field-group-a">
				<p class="submit">
					<button id="changeEvent">Save & Publish Event</button>
				</p>
			</div>
		<?php else: ?>
			<div class="field-group-a">
				<p class="submit">
					<button id="saveForm">Create & Publish Event</button>
				</p>
			</div>
		<?php endif; ?>
	</div>
</section>

<aside class="secondary">
	<div>
		<h2 class="c">Sections/Calendars</h2>
		<div>
			<h3>Select the section(s) to publish your event to:</h3>
			<ul id="sections" class="sortable-list">
				<?php foreach(val($sections, array()) as $section): ?>
					<?php if ($section['permPublish'] == 0): ?>
						<?php if ($section['selected'] == 1): ?>
							<?php $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
						<?php endif; ?>
					<?php else: ?>
						<li id="s<?=$section['page_id']; ?>" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>">
							<?=$section['title']?>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php if (count(val($other, array())) > 0): ?>
				<h3 class="sections-other">This page is also published to the following sections: </h3>
				<ul class="sections-other">
					<?php foreach($other as $section): ?>
					<li id="s<?=$section['id']; ?>"><?php echo $section['title']?>,&nbsp;</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
	</div>
</aside>
