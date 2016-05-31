<?php
/**
 * @filename event_equipment.php
 * @author: cravelo
 * @date: 9/30/13 2:03 PM
 */
?>

<section class="primary" >
    <div style="float: left; width: 100%; ">
	<br/><h1>Event Equipment</h1>
	<h5><font color="red"> Note: For technical support and setup, please contact Helpdesk</font></h5>
    </div>

    <div style="float: left; width: 25%;">
    <p>
        <label><input type="checkbox" name="projector" value="X"
                      id="projector"/><b>Projector</b></label><br/><br/>
        <label><input type="checkbox" name="screen" value="X" id="screen"/><b>Screen</b></label><br/><br/>
        <label><input type="checkbox" name="podium" value="X" id="podium"/><b>Podium</b></label><br/><br/>
	    <label><input type="checkbox" name="table" value="X" id="table"/><b>Table</b></label>
    </p>
	</div>

	<div style="float: left;width: 25%;">
	<p>
        <label><input type="checkbox" name="audio" value="X" id="audio"/><b>Audio
	        System</b></label><br/><br/>
		<label><input type="checkbox" name="mic" value="X"
		              id="microphone"/><b>Microphone</b></label><br/><br/>
        <label><input type="checkbox" name="easel" value="X" id="easel""/><b>Easel</b></label><br/><br/>
		<label><input type="checkbox" name="chairs" value="X" id="chairs"/><b>Chairs</b></label>
	</p>
	</div>

	<div style="float: left; width: 50%;">
	<p>
        <label><input type="checkbox" name="chime" value="X" id="chime"/><b>Chime</b><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<i>Quantity Needed: </i></label>
        <input type="text" name="chimeNum" id="chimeNum" value="" size="20"/></input><br/>
		<label><input type="checkbox" name="charts" value="X" id="charts"/><b>Flip Charts & markers</b><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<i>Chart Qty: </i></label>
		<input type="text" name="chartNum" id="chartNum" value="" size="15"/></input>
		<label><i>Marker Qty: </i></label>
        <input type="text" name="markerNum" id="markerNum" value="" size="15"/></input><br/>
        <label><input type="checkbox" name="other" value="X" id="other"/><b>Other</b><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;<i>Please describe: </i></label><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;<textarea type="text" name="otherDesc" id="otherDesc" value="" style="width: 300px; height: 50px;"
                                          maxlength="500"></textarea>
	</p>
	</div>
</section>
