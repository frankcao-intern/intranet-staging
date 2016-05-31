<?php
$SID = session_id();
$checked = "checked='checked'";

if ($mgrReview){
	if ($dataArr['isError']){
		exit($dataArr['strResult']);
	}else{
		$dataArr = $dataArr['strResult'];
	}
	?>
	<script type="text/javascript">
		(function($){
			var Engine = {
				openSections: function(){
					formEngine.sectionOpeners.apply($("#hOther")[0]);
					formEngine.sectionOpeners.apply($("#uOther")[0]);
				},
				selectOptions: function(){
					$("#account").prop('selectedIndex', <?php echo isset($dataArr['account']) ? "\"".$dataArr['account']."\"" : "0"; ?>);
				},
				autoComplete: function(){
					$("#mUser").autocomplete({
						source: "public/getnames.php?SID=" + formEngine.SID + '&p=samaccountname',
						minLength: 2,
						select: function( event, ui ) {
							$("#mUser").val(ui.item.label);
							$("#mUserEmail").val(ui.item.value);

							return false;
						}
					});
				}
			};

			$(document).ready(function(){
				Engine.openSections();
				Engine.selectOptions();
				Engine.autoComplete();
			});
		}(jQuery));
	</script>
	<?php
}else{
	//DATES BEGIN -----------------------------------------------------------------------------------------------------------------
	date_default_timezone_set("America/New_York");
	$dataArr['needDate'] = strtotime("+1 week", time()); //current date + 1 week
	$dataArr['needDate'] = date("m/d/Y", $dataArr['needDate']);
	//DATES END ---------------------------------------------------------------------------------------------------------------------
}
?>

<?php $action = $settings['base_url'].($mgrReview ? "?p=approved" : "?p=thank_you"); ?>

<form method="post" id="form" action="<?php echo $action; ?>">
	<fieldset class="action">
		<div class="header">
			<img src="assets/css/logo.jpg" alt="Eileen Fisher Inc." width="150" height="80" >
			<div>
				<h1>
					IT Department Equipment Loan Request Form
					<?php if ($mgrReview == 'true') echo "- Pending Approval"; ?>
				</h1>
				<a href="assets/Instructions to complete the Form.doc" class="redNotice">
					Instructions to complete this form
				</a>
				<a href="assets/guidelines.doc" class="redNotice">
					Guidelines for Cell Phone, BlackBerry and iPad Approvals
				</a>
			</div>
		</div>
		<?php if ($mgrReview == 'true'):?>
			<div class="mgr-instructions">
				<h2>Instructions for Leader</h2>
				<ol>
					<li>Read the instructions document</li>
					<li>Review this request and make any necessary changes</li>
					<li>To approve this request type in your windows password in the signature section at the bottom of this form</li>
				</ol>
				<p class="redNotice">THIS REQUEST WILL AUTOMATICALLY EXPIRE AFTER 60 DAYS.</p>
			</div>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>General Information</legend>
		<label for="userName">Requested by*:</label>
		<input type="text" name="userName" id="userName" autocomplete="off" size="30" value="<?php if (isset($dataArr['userName'])) echo $dataArr['userName']; ?>" />
		<label for="reqEmail">Email*:</label>
		<input type="text" name="reqEmail" id="reqEmail" autocomplete="off" size="35" value="<?php if (isset($dataArr['reqEmail'])) echo $dataArr['reqEmail']; ?>" />
		<br><br>
		<label for="needDate">Date needed*:</label>
		<input name="needDate" id="needDate" type="text" size="10" readonly="readonly" value="<?php if (isset($dataArr['needDate'])) echo $dataArr['needDate']; ?>" />
		<label for="returnDate">When will you return it? *</label>
		<input name="returnDate" id="returnDate" type="text" size="10" readonly="readonly" value="<?php if (isset($dataArr['returnDate'])) echo $dataArr['returnDate']; ?>" />
		<br/><br/>

		<div style="float: left; clear: both; width: 100%; padding-top: 5px; ">
			<p class="redNotice">Allow 1 business week for this request to be completed.</p>
		</div>
	</fieldset>			

	<fieldset id="equipment">
		<legend>Equipment needed *</legend>

		<label><input type="checkbox" name="h1" value="Laptop"					    <?php if (isset($dataArr['h1'])) echo $checked; ?> />Laptop</label>
		<label><input type="checkbox" name="h2" value="Blackberry (Verizon)"    	<?php if (isset($dataArr['h2'])) echo $checked; ?> />Blackberry (Verizon)</label>
		<label><input type="checkbox" name="h3" value="Wireless Card (Verizon)"	    <?php if (isset($dataArr['h3'])) echo $checked; ?> />Wireless Card (Verizon)</label>
		<label><input type="checkbox" name="h4" value="Android Device (Verizon)"	<?php if (isset($dataArr['h4'])) echo $checked; ?> />Android Device (Verizon)</label>
		<label><input type="checkbox" name="h5" value="iPhone (Verizon)"	        <?php if (isset($dataArr['h5'])) echo $checked; ?> />iPhone (Verizon)</label>
		<label><input type="checkbox" name="h6" value="iPad (Verizon)"	            <?php if (isset($dataArr['h6'])) echo $checked; ?> />iPad (Verizon)</label>
		<label><input type="checkbox" name="hOther" id="hOther" value="hOtherStr"	<?php if (isset($dataArr['hOther'])) echo $checked; ?> class="section-open"/>Other</label>
		<span id="hOtherStr" class="hidethis" style="display: none">
			<label>Specify: <input type="text" name="hOtherStr" value="<?php if (isset($dataArr['hOtherStr'])) echo $dataArr['hOtherStr']; ?>" style="width: 540px; "/></label>
		</span>
		<label for="hReason">Reason/Description for the selected equipment:</label>
		<textarea name="hReason" id="hReason" cols="100" rows="5"><?php
			if (isset($dataArr['hReason'])) echo $dataArr['hReason'];
		?></textarea>
	</fieldset>

	<fieldset id="usage">
		<legend>Usage requirements *</legend>

		<label><input type="checkbox" name="u1" value="Home"					    <?php if (isset($dataArr['u1'])) echo $checked; ?> />Home</label>
		<label><input type="checkbox" name="u2" value="Domestic Travel (US)"    	<?php if (isset($dataArr['u2'])) echo $checked; ?> />Domestic Travel (US)</label>
		<label><input type="checkbox" name="u3" value="International Travel"	    <?php if (isset($dataArr['u3'])) echo $checked; ?> />International Travel</label>
		<label><input type="checkbox" name="uOther" id="uOther" value="uOtherStr"	<?php if (isset($dataArr['uOther'])) echo $checked; ?> class="section-open"/>Other</label>
		<span id="uOtherStr" class="hidethis" style="display: none">
			<label>Specify: <input type="text" name="uOtherStr" value="<?php if (isset($dataArr['uOtherStr'])) echo $dataArr['uOtherStr']; ?>" style="width: 540px; "/></label>
		</span>
		<label for="uReason">Additional description for usage requirements:</label>
		<textarea name="uReason" id="uReason" cols="100" rows="5"><?php
			if (isset($dataArr['uReason'])) echo $dataArr['uReason'];
		?></textarea>
	</fieldset>

	<fieldset>
		<legend>Additional Notes:</legend>

		<label for="textDesc">Use this section to ask a question, describe a special request that isn't covered in the
			form or for any notes related to your request.</label><br/><br/>
		<textarea name="textDesc" id="textDesc" rows="10"
		          cols="100"><?php if (isset($dataArr['textDesc'])) echo $dataArr['textDesc']; ?></textarea>
	</fieldset>

	<fieldset>
		<legend>Signature*</legend>
		<?php
			$strAccount = "Make sure to select the correct department/account to be charged.
			<br/><br/>Department/Account to be charged:
			<select name='account' id='account'>
			<option>-- Select one --</option>
			<option>Accounting</option>
			<option>Administrative</option>
			<option>Architecture, Planning & Design </option>
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
			<option>Facilities Management</option>
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
			</select><br/><br/>";

			if ($mgrReview == 'true'):
		?>
				To approve this request fill in your username and password.<br/>
				<?php echo $strAccount; ?>
				Manager's Email: <input type="text" name="mUserEmail" autocomplete="off" size="35" readonly="readonly" value="<?php if(isset($dataArr['mUserEmail'])) echo $dataArr['mUserEmail']; ?>" /><br/>
				Manager's Windows Username: <input type="text" name="mUser" id="mUser" autocomplete="off" /><br/>
				Manager's Windows Password: <input type="password" name="mPassw" id="mPassw" /><br/>
				<input name="signDate" type="hidden" value="<?php echo date("m/d/Y"); ?>"/>
				<input name="mUserName" type="hidden" value="<?php if(isset($dataArr['mUserName'])) echo $dataArr['mUserName']; ?>"/>
				<input name="reqTimeStamp" type="hidden" value="<?php if(isset($dataArr['reqTimeStamp'])) echo $dataArr['reqTimeStamp']; ?>"/>
				<input type="hidden" name="requestSerial" value="<?php if(isset($dataArr['requestSerial'])) echo $dataArr['requestSerial']; ?>" />
			</fieldset>
			<fieldset class="action">
				<p class="redNotice">NOTICE: By submitting this form you are approving this request and is going to be submitted to the helpdesk for processing. Please click only once.</p>
				<br />
				<input type="submit" value="I approve this request" />
			</fieldset>
		<?php else: ?>
				All requests require your leader's signature for approval. If you are the leader for your area please fill in
				your name and email address below anyways. If you are not please fill in your leader's contact information and he/she will receive
				an email for approval.<br/><br/>
				<?php echo $strAccount; ?>
				Manager's Name:  <input type="text" name="mUserName" id="mUserName" autocomplete="off" size="30" />
				Manager's Email: <input type="text" name="mUserEmail" id="mUserEmail" autocomplete="off" size="35" />
			</fieldset>
			<fieldset class="action">
				<input type="submit" value="Submit the request" />
				<input type="button" value="Clear the whole form" id="btnReset" />
			</fieldset>
		<?php endif; ?>
</form>
