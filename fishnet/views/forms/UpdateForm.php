<?php
/**
 * @filename UpdateForm.php
 * @author: cravelo
 * @date: 11/14/13 3:27 PM
 */
$this->load->helper('form');
?>
<section class="primary" xmlns="http://www.w3.org/1999/html">
    <div class="header-a header-a-space-bottom-b">
        <p><?=anchor("/home", "&#x25c4;&nbsp;Home Page")?></p>
        <h2>Automated Forms Update Form</h2>
    </div>

	<?=form_open("/request/UpdateForm/");?>
    <div class="field-group-a">

        <div style="float: left; width: 50%;">
            <p>
                <label for="requester"><b>Name of person making request: </b></label>
                <input type="text" name="requester" id="requester" size="50" value=""/><br/>
                <label for="email" ><b>Email address of person making request:</b></label><br/>
                <input type="text" name="email" id="email" size="50" value=""><br/>
                <label for="updateDate" ><b>Updated form needed by:</b></label><br/>
                <input type="text" name="updateDate" id="datepicker" readonly="readonly" value="">
            </p>
        </div>

        <div style="float: left; width: 50%;">
            <p>
                <label for="requestDate" ><b>Date of this request:</b></label><br/>
                <input type="text" name="requestDate" id="datepicker2" readonly="readonly" value=""><br/>
                <label for="dept" ><b>Department requesting update: </b></label><br/>
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
            <h5><font color="red">Turn around time for completion of update depends on complexity and
                quantity of changes</font></h5>
            <label for="formName"><b>Name of form(s) to be updated</b></label><br/>
            <textarea type="text" name="formName" id="formName" value="" style="width:300px; height:150px;" maxlength="500"></textarea><br/><br/>
            <label for="frequency"><b>Do your form(s) need to be updated often?</b></label><br/>
            <label><input type="radio" name="frequency" value="Yes" id="Yes">Yes</label><br/>
            <label><input type="radio" name="frequency" value="No" id="No">No</label><br/><br/>
            <label for="owner"><b>Who maintains your forms? </b></label><br/>
            <input type="text" name="owner" id="owner" size="50" value=""><br/><br/>
            <label for="changes"><b>Please describe changes:</b></label><br/>
            <textarea type="text" name="changes" id="changes" value="" style="width: 600px; height: 150px;" maxlength="1000"></textarea>
		</p>
	    </div>

        <div style="float: left; width: 100%;">
            <p class="submit">
                <button id="submitUpdate" type="submit">Submit</button>
                <button id="resetUpdate" type="reset">Clear</button>
            </p>
        </div>
    </div>
	<?=form_close()?>

</section>
