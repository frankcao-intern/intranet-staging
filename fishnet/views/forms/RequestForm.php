<?php
/**
 * @filename RequestForm.php
 * @author: cravelo
 * @date: 11/14/13 3:27 PM
 */
$this->load->helper('form');
?>
<section class="primary">
    <div class="header-a header-a-space-bottom-b">
        <p><?=anchor("/departments/117", "&#x25c4;&nbsp;INFORMATION TECHNOLOGY")?></p>
        <h2>Automated Forms Request Form</h2>
    </div>

	<?=form_open("/request/createForm/");?>
    <div class="field-group-a">
        <h5><font color="red">Forms must be updated versions containing accurate information before
            submission.</font></h5>
        <div style="float: left; width: 50%;">
            <p>

                <label for="requester"><b>Name of person making request: </b></label>
                <input type="text" name="requester" id="requester" size="50" value=""/><br/>
                <label for="email" ><b>Email address of person making request:</b></label><br/>
                <input type="text" name="email" id="email" size="50" value=""><br/>
                <label for="meeting" ><b>Proposed date & time of meeting to discuss forms:</b></label><br/>
                <input type="text" name="meeting" id="datepicker" readonly="readonly" value="">
                <select id="time" name="time" value="">
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
                <select id="timeAMPM" name="timeAMPM" value="">
                    <option>AM</option>
                    <option>PM</option>
	            </select>
            <h5><font color="red">Meeting required before coding begins.</font></h5>

            </p>
        </div>

        <div style="float: left; width: 50%;">
            <p>
                <label for="date" ><b>Date of this request:</b></label><br/>
                <input type="text" name="requestDate" id="datepicker2" readonly="readonly" value=""><br/>
                <label for="dept" ><b>Department requesting automation: </b></label><br/>
                <select id="dept" name="dept" value="">
                    <option>Accounting</option>
                    <option>Administrative</option>
                    <option>Architecture, Planning & Design</option>
                    <option>Business Planning</option>
                    <option>Core Concept</option>
                    <option>Creative Services</option>
                    <option>Customer Service</option>
                    <option>Department Store Merchandising</option>
                    <option>Design</option>
                    <option>Design/Production</option>
                    <option>Distribution</option>
                    <option>E-Commerce</option>
                    <option>EF Merchandising</option>
                    <option>Facilities</option>
                    <option>Human Resources</option>
                    <option>Information Technology</option>
                    <option>Internal Communications</option>
                    <option>Leadership, Learning & Development</option>
                    <option>Manufacturing</option>
                    <option>Operational Sales Support</option>
                    <option>Product Development</option>
                    <option>Retail Buying</option>
                    <option>Retail Marketing</option>
                    <option>Retail Teaming & Development</option>
                    <option>Showroom/Sales</option>
                    <option>Social Consciousness</option>
                    <option>Store Operations</option>
                    <option>Visual Presentation/Retail Visuals</option>
                </select><br/>
            </p>
        </div>

	    <div style="float: left; width: 100%;">
		<p>
            <h1>Form Information</h1>
            <h5><font color="red">Turn around time for completion of automation dependent on complexity and
                quantity of forms</font></h5>
            <label for="tp"><b>Do you have a team page?</b></label><br/>
            <label><input type="radio" name="tp" value="Yes" id="Yes"/>Yes</label><br/>
            <label><input type="radio" name="tp" value="No" id="No"/>No</label><br/><br/>
            <label for="location"><b>Where would you like your forms to live on fishNET? </b></label><br/>
            <label><input type="radio" name="location" value="Team Page" id="tp"/>Team Page</label><br/>
            <label><input type="radio" name="location" value="Doc Spot" id="ds"/>Doc Spot</label><br/>
            <label><input type="radio" name="location" value="Both" id="both"/>Both</label>

		</p>
	    </div>

        <div style="float: left; width: 100%;">
            <p class="submit">
                <button id="submitRequest" type="submit">Submit</button>
                <button id="resetRequest" type="reset">Clear</button>
            </p>
        </div>
    </div>
	<?=form_close()?>

</section>
