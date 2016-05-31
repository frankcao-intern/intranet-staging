<?php
/**
 * @filename event_furniture.php
 * @author: cravelo
 * @date: 9/30/13 2:03 PM
 */
?>

<section class="primary" >
    <div style="float: left; width: 100%; ">
	<br/><h1>Furniture Arrangement</h1>
	<h5><font color="red">**Please note: Large and heavy furniture moves require 5 days notice.</font></h5>
    </div>

    <div style="float: left; width: 50%;">
    <p>
        <label><input type="radio" name="furniture" value="Conference Tables (Square)" id="furniture"/><b>Conference
	        Tables (Square)</b></label><br/><br/>
        <label><input type="radio" name="furniture" value="Conference Tables (Attached Long)"
                      id="furniture"/><b>Conference Tables (Attached Long)</b></label><br/><br/>
        <label><input type="radio" name="furniture" value="Theater" id="furniture"/><b>Theater (Chairs Only)</b><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<i>Please indicate # of chairs needed: </i></label>
        <input type="text" name="furnitureNumT" id="furnitureNumT" value="" size="10"/></input><br/>
        <label><input type="radio" name="furniture" value="Classroom Style" id="furniture"/><b>Classroom Style
        </b><br/>&nbsp;&nbsp;&nbsp;&nbsp;<i>Number of tables need: &nbsp;&nbsp;&nbsp;&nbsp;Number of chairs need:
        </i></label><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="furnitureNumTb" id="furnitureNumTb" value=""
                                       size="15"/></input>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="furnitureNumCh" id="furnitureNumCh" value=""
                                       size="15"/></input><br/>
    </p>
	</div>

	<div style="float: left; width: 50%;">
	<p>
        <label><input type="radio" name="furniture" value="Conference Tables (U-Shaped)" id="furniture"/><b>Conference
            Tables (U-Shaped)</b></label><br/><br/>
		<label><input type="radio" name="furniture" value="Room cleared" id="furniture"/><b>Room Cleared
			Completely</b></label><br/><br/>
        <label><input type="radio" name="furniture" value="Circular" id="furniture"/><b>Circular (Chairs Only)</b><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<i>Please indicate # of chairs needed: </i></label>
        <input type="text" name="furnitureNumC" id="furnitureNumC" value="" size="10"/></input><br/>
        <label><input type="radio" name="furniture" value="Other" id="furniture"/><b>Other</b><br/>
	    &nbsp;&nbsp;&nbsp;&nbsp;<i>Please describe: </i></label><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;<textarea type="text" name="furnitureNumO" id="furnitureNumO" value=""
                                          style="width: 300px; height: 50px;" maxlength="500"/></textarea><br/>

	</p>
	</div>
</section>
