<?php
/**
 * @filename event_info_form.php
 * @author: cravelo
 * @date: 9/30/13 2:03 PM
 */
?>

<section class="primary">
    <h1>Event Information</h1>
    <div style="float: left; width: 50%; ">
        <p>
            <label for="eventTitle">Event Title</label><br/>
            <input type="text" name="eventTitle" id="eventTitle" size="54" value=""/><br/>
            <label for="coordinator">Name of Event Coordinator</label><br/>
            <input type="text" name="coordinator" id="coordinator" size="54" value=""/><br/>
            <label for="eventDate">Date of Event</label><br/>
            <input type="text" name="eventDate" id="eventDate" value="" readonly="readonly"/><br/>
            <label for="noParticipants">Number of Participants Expected</label><br/>
            <input type="text" name="noParticipants" id="noParticipants" size="54" value=""/>
        </p>
    </div>

    <div style="float: right; width: 50%;">
        <p>
            <label for="requestDate">Date of this request</label><br/>
            <input type="text" name="requestDate" id="requestDate" readonly="readonly" value=""><br/>
			<label for="deptRequesting">Department/Store Requesting Event</label><br/>
	        <input type="text" name="deptRequesting" id="deptRequesting" size="54" value=""/><br/>
	    </p>
	</div>

	<div style="float: left; width: 25%;">
		<p>
	        <label for="startTime">Event Start Time</label><br/>
			<select id="startTime" name="startTime" value="">
				<option>01:00</option>
                <option>01:30</option>
				<option>02:00</option>
                <option>02:30</option>
                <option>03:00</option>
                <option>03:30</option>
                <option>04:00</option>
                <option>04:30</option>
                <option>05:00</option>
                <option>05:30</option>
                <option>06:00</option>
                <option>06:30</option>
                <option>07:00</option>
                <option>07:30</option>
                <option>08:00</option>
                <option>08:30</option>
                <option>09:00</option>
                <option>09:30</option>
                <option>10:00</option>
                <option>10:30</option>
                <option>11:00</option>
                <option>11:30</option>
                <option>12:00</option>
                <option>12:30</option>
			</select>
            <select id="startTimeAMPM" name="startTimeAMPM" value="">
	            <option>AM</option>
	            <option>PM</option>
	        </select><br/>
		</p>
	</div>

	<div style="float: right; width: 25%;">
		<p>
            <label for="endTime">Event End Time</label><br/>
            <select id="endTime" name="endTime" value="">
                <option>01:00</option>
                <option>01:30</option>
                <option>02:00</option>
                <option>02:30</option>
                <option>03:00</option>
                <option>03:30</option>
                <option>04:00</option>
                <option>04:30</option>
                <option>05:00</option>
                <option>05:30</option>
                <option>06:00</option>
                <option>06:30</option>
                <option>07:00</option>
                <option>07:30</option>
                <option>08:00</option>
                <option>08:30</option>
                <option>09:00</option>
                <option>09:30</option>
                <option>10:00</option>
                <option>10:30</option>
                <option>11:00</option>
                <option>11:30</option>
                <option>12:00</option>
                <option>12:30</option>
            </select>
            <select id="endTimeAMPM" name="endTimeAMPM" value="">
                <option>AM</option>
                <option>PM</option>
            </select><br/>
        </p>
    </div>
</section>

