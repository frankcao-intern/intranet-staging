<?php
/**
 * @filename event_food.php
 * @author: cravelo
 * @date: 9/30/13 2:03 PM
 */
?>

<section class="primary">
    <div style="float: left; width: 100%; ">
	<h1>Food Service</h1>
	<h5><font color="red">**Catering orders and food deliveries are the responsibility of the
	    event coordinator</font></h5>

        <p>
            <label><input type="radio" name="meal" id="meal" value="Breakfast"/>Breakfast</label>&nbsp;&nbsp;&nbsp;
	        &nbsp;
            <label><input type="radio" name="meal" id="meal" value="Lunch"/>Lunch</label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="meal" id="meal" value="Dinner"/>Dinner</label>&nbsp;&nbsp;&nbsp;&nbsp;
	        <label><input type="radio" name="meal" id="meal" value="Other"/>Other</label>
        </p>
    </div>

    <div style="float: left; width: 50%;">
    <p>
        <label for="compMeal">Company Providing Meal Service</label><br/>
        <input type="text" name="compMeal" id="compMeal" value="" size="54"/><br/>
      	<label for="efPerson">EF Person Meeting Delivery</label>
	    <input type="text" name="efPerson" id="efPerson" value="" size="54"/><br/>

	</p>
	</div>

	<div style="float: left; width: 25%;">
	<p>
	<label for="caterContact">Catering Contact Person</label><br/>
	<input type="text" name="caterContact" id="caterContact"  value="" size="25"/><br/>
	</p>
	</div>

	<div style="float: right; width: 25%;">
    <p>
    <label for="caterPhone">Cater Phone Number</label><br/>
    <input type="text" name="caterPhone" id="caterPhone"  value="" size="23"/><br/>
    </p>
    </div>

    <div style="float: right; width: 50%;">
    <p>
        <label for="efPhone">EF Phone Number/ Cell Phone</label><br/>
        <input type="text" name="efPhone" id="efPhone" value="" size="54"/>
    </p>
    </div>

    <div style="float: left; width: 25%;">
    <p>
    <label for="time">Delivery Time</label><br/>
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
        <select id="AMPM" name="AMPM" value="">
            <option>AM</option>
            <option>PM</option>
        </select><br/>
    </p>
    </div>

    <div style="float: left; width: 25%;">
    <p>
    <label for="floordeliver">Floor Delivering To</label><br/>
    <input type="text" name="floordeliver" id="floordeliver" value="" size="23"/>
    </p>
    </div>

	<div style="float: left; width: 50%;">
	<p>
	<label for="tips"><b>Please note: Tips should be arranged at the time of the order and included in the charge.</b></label>
	</p>
	</div>

</section>
