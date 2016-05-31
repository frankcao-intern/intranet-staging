<?php
$SID = session_id();
$checked = "checked='checked'";

if ($mgrReview){ ?>
	<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				<?php //TODO: find a new way to do this task
					//----------------------------------- Users --------------------------------------
					if (isset($dataArr['forUsers'])){
						//echo "<option>".$dataArr['forUsers']."</option>\n";
						$usersArr = json_decode($dataArr['forUsers'], true); //convert into array
						//print_r($usersArr);
						for ($i = 0; $i < count($usersArr); $i++){
							echo "$('.userList').append('<option value=\"".$usersArr[$i]."\">".$usersArr[$i]."</option>');\n";
						}
					}
				?>
			});
		}(jQuery));
	</script>
	<?php
	//----------------------------------- Hardware Checks --------------------------------------
	if (isset($dataArr['computer'])){
		if ($dataArr['computer'] == "Dell Laptop"){
			$computer1 = $checked;
		}elseif ($dataArr['computer'] == "Desktop"){
			$computer2 = $checked;
		}elseif ($dataArr['computer'] == "Mac Book"){
			$computer3 = $checked;
		}
	}
	//----------------------------------- User type Checks --------------------------------------
	if (isset($dataArr['uHours'])){
		if ($dataArr['uHours'] == "parttime"){
			$uHours1 = $checked;
		}else if ($dataArr['uHours'] == "fulltime"){
			$uHours2 = $checked;
		}
	}
}else{
	//DATES BEGIN -----------------------------------------------------------------------------------------------------------------
	date_default_timezone_set("America/New_York");
	$dataArr['needDate'] = strtotime("+3 week", time()); //current date + 3 weeks
	$dataArr['needDate'] = date("m/d/Y", $dataArr['needDate']);
	//DATES END ---------------------------------------------------------------------------------------------------------------------
}
?>

<?php $action = $settings['base_url'].($mgrReview ? "?p=approved" : "?p=thank_you"); ?>

<form method="post" name="reqForm" id="reqForm" action="<?php echo $action; ?>">
	<fieldset class="action">
		<div class="header">
			<img src="assets/css/logo.jpg" alt="Eileen Fisher Inc." width="150" height="80" >
			<div>
				<h1>
					IT Department Requisition Form
					<?php if ($mgrReview == 'true') echo "- Pending Approval"; ?>
				</h1>
				<a href="assets/Instructions to complete the Requisition Form.doc" class="redNotice">
					Instructions to complete this form
				</a>
				<a href="assets/guidelines.doc" class="redNotice">
					Guidelines for Mobile Approvals
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
		<label for="needDate">Date needed*:</label>
		<input name="needDate" id="needDate" type="text" size="10" readonly="readonly" value="<?php if (isset($dataArr['needDate'])) echo $dataArr['needDate']; ?>" />
		<br/><br/>

		<label for="addText">
			This request is for the following employee(s):&nbsp;&nbsp;&nbsp;
		</label>
		<div class="list-section">
			<div>
				<input type="text" name="addText" id="addText" autocomplete="off" size="25"/><br />
				<input name="forUsers" id="forUsersStr" type="hidden" />
				Employee List*:<br />
				<?php
					//this is for the width of the select, because is not rendered the same way in gecko/webkit/MSIE
					//echo $_SERVER['HTTP_USER_AGENT'];
					if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
						echo "<select id='forUsers' size='4' style='width: 185px' class='userList'>\n";
					else
					if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "AppleWebKit"))
						echo "<select id='forUsers' size='4' style='width: 185px' class='userList'>\n";
					else
						echo "<select id='forUsers' size='4' style='width: 176px' class='userList'>\n";

					echo "</select>";
				?>
			</div>
			<div class="buttons">
				<input type="button" value="Add to the list" style="width: 148px;" id="btnAddUser"
				       data-select="#forUsers" data-text="#addText" /><br/>
				<input type="button" value="Remove from the list" id="btnDelUser" data-select="#forUsers" /><br/>
				<b>NOTE:</b> The same request is going to be created for each user you add to this list.<br/>
				In order to submit the form there should be at least one name in this list. <br />
				If the request is for yourself then add yourself to this list.
			</div>
		</div>
		<div style="float: left; clear: both; width: 100%; padding-top: 5px; ">
			<p class="redNotice">If new hardware or software is required, allow 3 weeks for this request to be completed. For any other requests allow three days.</p>
		</div>
	</fieldset>

	<fieldset id="reqTypes">
		<legend>Request type*</legend>
		<label><input type="checkbox" name="t1" id="t1" value="newUser"       class="section-open" <?php if (isset($dataArr['t1'])) echo $checked; ?> />New User</label>
		<label><input type="checkbox" name="t2" id="t2" value="newSoft"       class="section-open" <?php if (isset($dataArr['t2'])) echo $checked; ?> />New Software</label>
		<label><input type="checkbox" name="t3" id="t3" value="newHardware"   class="section-open" <?php if (isset($dataArr['t3'])) echo $checked; ?> />New Hardware (i.e. new computer, screen, mouse, etc)</label>
		<label><input type="checkbox" name="t4" id="t4" value="sharedAccess"  class="section-open" <?php if (isset($dataArr['t4'])) echo $checked; ?> />Shared Drives (Use this section to request access to things like the F:\ drive or the J:\ drive for example)</label>
		<label><input type="checkbox" name="t5" id="t5" value="distroLists"   class="section-open" <?php if (isset($dataArr['t5'])) echo $checked; ?> />Email Distribution Lists (Add/Remove people(s) from or Create/Delete distribution lists)</label>
		<label><input type="checkbox" name="t6" id="t6" value="webExUsersDiv" class="section-open" <?php if (isset($dataArr['t6'])) echo $checked; ?> />WebEx Account (To be able to host WebEx meetings)</label>
	</fieldset>

	<fieldset id="newUser" class="hidethis" style="display: none">
		<legend>New employee information (Please fill all the fields)</legend>
		<label for="uName">Legal Name*:</label>
		<input type="text" name="uName" id="uName" size="43" value="<?php if (isset($dataArr['uName'])) echo $dataArr['uName']; ?>" />
		<label for="uTitle">Title*:</label>
		<input type="text" name="uTitle" id="uTitle" size="43" value="<?php if (isset($dataArr['uTitle'])) echo $dataArr['uTitle']; ?>" />
		<label>Select one*:</label>
		<label>
			<input type="radio" name="uHours" value="parttime" id="uHoursPart"
					<?php if (isset($uHours1)) echo $uHours1; ?> />Part-Time
		</label>
		<label>
			<input type="radio" name="uHours" value="fulltime" id="uHoursFull"
					<?php if (isset($uHours2)) echo $uHours2; ?> />Full-Time
		</label>
		<label for="uType">Select one*:</label>
		<select name="uType" id="uType">
			<option>Consultant</option>
			<option>Intern</option>
			<option>Permanent</option>
			<option>Temp</option>
		</select>
		<label for="uLocation">Location*:</label>
		<select name="uLocation" id="uLocation">
			<option>Corporate Headquarters (Irvington, NY)</option>
			<option>NYC Creative Center (111 5th Ave - 8th Floor)</option>
			<option>NYC Creative Center (111 5th Ave - 9th Floor)</option>
			<option>NYC Creative Center (111 5th Ave - 10th Floor)</option>
			<option>NYC Creative Center (111 5th Ave - 11th Floor)</option>
			<option>NYC Creative Center (111 5th Ave - 13th Floor)</option>
			<option>Warehouse (Secaucus, NJ)</option>
			<option>Remote/Home Users</option>
			<option>Other Location (Not listed here)</option>
		</select>
		<label for="uDate">Start Date*:</label>
		<input type="text" name="uDate" id="uDate" size="10" readonly="readonly"
		   value="<?php if (isset($dataArr['needDate'])) echo $dataArr['needDate']; ?>" />
	</fieldset>

	<fieldset id="newSoft" class="hidethis" style="display: none">
		<legend>Software needed*</legend>

		<label><input type="checkbox" name="s1"  value="Microsoft Office"			 <?php if (isset($dataArr['s1']))  echo $checked; ?> />Microsoft Office</label>
		<label><input type="checkbox" name="s2"  value="BlueCherry"				 <?php if (isset($dataArr['s2']))  echo $checked; ?> />BlueCherry</label>
		<label><input type="checkbox" name="s14" value="PLM Application"			<?php if (isset($dataArr['s14'])) echo $checked; ?> />PLM Application</label>
		<label><input type="checkbox" name="s3"  value="Adobe Acrobat Full Version" <?php if (isset($dataArr['s3']))  echo $checked; ?> />Adobe Acrobat Full Version</label>
		<label><input type="checkbox" name="s4"  value="Adobe Photoshop"     		 <?php if (isset($dataArr['s4']))  echo $checked; ?> />Adobe Photoshop</label>
		<label><input type="checkbox" name="s5"  value="Adobe Illustrator"   		 <?php if (isset($dataArr['s5']))  echo $checked; ?> />Adobe Illustrator</label>
		<label><input type="checkbox" name="s6"  value="Adobe InDesign"			 <?php if (isset($dataArr['s6']))  echo $checked; ?> />Adobe InDesign</label>
		<label><input type="checkbox" name="s7"  value="Adobe CS Standard"			 <?php if (isset($dataArr['s7']))  echo $checked; ?> />Adobe CS Standard</label>
		<label><input type="checkbox" name="s8"  value="Adobe CS Web"				 <?php if (isset($dataArr['s8']))  echo $checked; ?> />Adobe CS Web</label>
		<label><input type="checkbox" name="s9"  value="WebPDM"					 <?php if (isset($dataArr['s9']))  echo $checked; ?> />WebPDM</label>
		<label><input type="checkbox" name="s10" value="ADP Time Clock"			 <?php if (isset($dataArr['s10'])) echo $checked; ?> />ADP Time Clock</label>
		<label><input type="checkbox" name="s11" value="Accumark"            		 <?php if (isset($dataArr['s11'])) echo $checked; ?> />Accumark</label>
		<label><input type="checkbox" name="s12" value="AutoCAD"             		 <?php if (isset($dataArr['s12'])) echo $checked; ?> />AutoCAD</label>
		<label><input type="checkbox" name="s13" value="Solomon Financials"		 <?php if (isset($dataArr['s13'])) echo $checked; ?> />Solomon Financials</label>
		<label><input type="checkbox" name="sOther" id="sOther" value="sOtherStr"	 <?php if (isset($dataArr['sOther'])) echo $checked; ?> class="section-open"/>Other</label>

		<span id="sOtherStr" class="hidethis" style="display: none">
			<label>Specify: <input type="text" name="sOtherStr" value="<?php if (isset($dataArr['sOtherStr'])) echo
			$dataArr['sOtherStr']; ?>" style="width: 540px; " /></label>
		</span>

		<label>
			Reason for this new software:
			<input type="text" name="sReason" id="sReason" size="100" value="<?php if (isset($dataArr['sReason'])) echo $dataArr['sReason']; ?>" />
		</label>
	</fieldset>

	<fieldset id="newHardware" class="hidethis" style="display: none">
		<legend>Hardware needed*</legend>
		<fieldset class="radios">
			<label>
				<input type="radio" name="computer" value="Dell Laptop" id="compLaptop"
						<?php if (isset($computer1)) echo $computer1; ?> />Dell Laptop
			</label>
			<label>
				<input type="radio" name="computer" value="Mac Book" id="compMacBook"
				       <?php if (isset($computer3)) echo $computer3; ?> />Mac Book
			</label>
			<label>
				<input type="radio" name="computer" value="Desktop" id="compDesktop"
						<?php if (isset($computer2)) echo $computer2; ?> />Desktop
			</label>
			<label><input type="radio" name="computer" value="Neither" id="compNeither" />Neither</label>
		</fieldset>

        <label><input type="checkbox" name="h9" value="Apple Monitor"					<?php if (isset($dataArr['h9'])) echo $checked; ?> />Apple Monitor</label>
		<label><input type="checkbox" name="h1" value="Monitor"					<?php if (isset($dataArr['h1'])) echo $checked; ?> />Monitor</label>
		<label><input type="checkbox" name="h2" value="Keyboard"					<?php if (isset($dataArr['h2'])) echo $checked; ?> />Keyboard</label>
		<label><input type="checkbox" name="h3" value="Mouse"						<?php if (isset($dataArr['h3'])) echo $checked; ?> />Mouse</label>
		<!--label><input type="checkbox" name="h4" value="Blackberry (Verizon)"		<?php if (isset($dataArr['h4'])) echo $checked; ?> />Blackberry (Verizon)</label-->
		<label><input type="checkbox" name="h5" value="Wireless Card (Verizon)"	<?php if (isset($dataArr['h5'])) echo $checked; ?> />Wireless Card (Verizon)</label>
		<label><input type="checkbox" name="h6" value="Android Device (Verizon)"	<?php if (isset($dataArr['h6'])) echo $checked; ?> />Android Device (Verizon)</label>
		<label><input type="checkbox" name="h7" value="iPhone (Verizon)"	<?php if (isset($dataArr['h7'])) echo
		$checked; ?> />iPhone (Verizon)</label>
		<label><input type="checkbox" name="h8" value="iPad (Verizon)"	<?php if (isset($dataArr['h8'])) echo $checked; ?> />iPad (Verizon)</label>
		<label><input type="checkbox" name="hOther" id="hOther" value="hOtherStr"	<?php if (isset($dataArr['hOther'])) echo $checked; ?> class="section-open"/>Other</label>
		<span id="hOtherStr" class="hidethis" style="display: none">
			<label>Specify: <input type="text" name="hOtherStr" value="<?php if (isset($dataArr['hOtherStr'])) echo $dataArr['hOtherStr']; ?>" style="width: 540px; "/></label>
		</span>
		<label for="hReason">Reason/Description for the selected hardware:</label>
		<textarea name="hReason" id="hReason" cols="100" rows="5"><?php
			if (isset($dataArr['hReason'])) echo $dataArr['hReason'];
		?></textarea>
	</fieldset>

	<fieldset id="sharedAccess" class="hidethis" style="display: none">
		<legend>Access to network drives</legend>
		<label for="sharename">Type the path to the folder (i.e. F:\Home\Information Technology\Asset Management) and then select
			&quot;Add to the list&quot; *:</label>
		<div class="list-section">
			<div>
				<input type="text" name="sharename" id="sharename" size="25" /><br />
				<input type="hidden" name="folders" id="folders" />
				<label for="shareNamesList">Folder List:</label>
				<?php
					//this is for the width of the select, because is not rendered the same way in gecko/webkit/MSIE
					//echo $_SERVER['HTTP_USER_AGENT'];
					if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")){
						echo "<select name='shareNamesList' id='shareNamesList' size='3' style='width: 185px'>\n";
					}else if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "AppleWebKit")){
						echo "<select name='shareNamesList' id='shareNamesList' size='3' style='width: 185px'>\n";
					}else{
						echo "<select name='shareNamesList' id='shareNamesList' size='3' style='width: 176px'>\n";
					}

					if (isset($dataArr['folders']))	{
						//echo "<option>$dataArr['folders']</option>\n";
						$foldersArr = json_decode($dataArr['folders'], true); //convert into array
						//print_r($foldersArr);
						for ($i = 0; $i < count($foldersArr); $i++)	{
							echo "<option value=\"$foldersArr[$i]\">$foldersArr[$i]</option>\n";
						}
					}

					echo "</select>";
				?>
			</div>
			<div class="buttons">
				<input type="button" value="Add to the list" id="btnAddFolder" data-select="#shareNamesList"
				       data-text="#sharename" /><br/>
				<input type="button" value="Remove from the list" id="btnDelFolder" data-select="#shareNamesList" /><br/>
				<b>NOTE:</b> The same access is going to be given for each path you add to the list.<br/>
				In order to submit the form there should be at least one folder path in this list.
			</div>
		</div>

		<p style="clear: both; margin-top: 10px;">Select from the following lists, the users that will be able to read or save documents in
				the folders specified above.</p>
		<div class="list-section">
			<div>
				<label for="readUsersList">Users with <strong>READ ONLY</strong> access:</label>
				<input type="hidden" id="readUsers"  name="readUsers"  value="<?php if (isset($dataArr['readUsers'])) echo $dataArr['readUsers']; ?>" />
				<select id='readUsersList' class='userList' multiple='multiple' size='3' style='width: 304px'>
				<!-- TODO: select the previously selected users here. -->
				</select>
			</div>
			<div>
				<label for="writeUsersList">Users with <strong>WRITE</strong> access:</label>
				<input type="hidden" id="writeUsers" name="writeUsers" value="<?php if (isset($dataArr['writeUsers'])) echo $dataArr['writeUsers']; ?>" />
				<select id='writeUsersList' class='userList' multiple='multiple' size='3' style='width: 304px'>
				<!-- TODO: select the previously selected users here. -->
				</select>
			</div>
		</div>
	</fieldset>

	<fieldset id="distroLists" class="hidethis" style="display: none">
		<legend>Email Distribution Lists</legend>
		Select what you want to do:<br /><br />

		<label><input type="checkbox" name="dlCreate" value="dlCreateDiv" class="section-open"/>Create a new
			distribution list</label>
		<label><input type="checkbox" name="dlDelete" value="dlDeleteDiv" class="section-open"/>Delete a distribution
			list</label>
		<label><input type="checkbox" name="dlAdd" value="dlAddDiv" class="section-open"/>Add employees to an existing
			distribution list</label>
		<label><input type="checkbox" name="dlRemove" value="dlRemoveDiv" class="section-open"/>Remove employees from
			an existing distribution list</label>

		<br />

		<fieldset id="dlCreateDiv" class="hidethis" style="display: none;">
			<legend>Create a new Distribution List</legend>
			Type the name for the new Distribution List in the box then highlight the employees that will be in this new list and click on &quot;Add Request&quot;<br />
			<br />
			<label for="distroNewName">Name for the new distribution list:</label>
			<div class="list-section">
				<div>
					<input type="text" size="45" id="distroNewName" />
					<label for="userList1">Employees:</label>
					<?php
						//this is for the width of the select, because is not rendered the same way in gecko/webkit/MSIE
						//echo $_SERVER['HTTP_USER_AGENT'];
						if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
							echo "<select name='userList1' id='userList1' class='userList' multiple='multiple' size='3' style='width: 304px'>\n";
						else
						if ($name = strstr($_SERVER['HTTP_USER_AGENT'], "AppleWebKit"))
							echo "<select name='userList1' id='userList1' class='userList' multiple='multiple' size='3' style='width: 304px'>\n";
						else
							echo "<select name='userList1' id='userList1' class='userList' multiple='multiple' size='3' style='width: 296px'>\n";

						echo "</select>";
					?>
				</div>
				<div class="buttons">
					<input type="button" id="dlCreate" value="Add Request" />
				</div>
			</div>
		</fieldset>

		<fieldset id="dlDeleteDiv" class="hidethis" style="display: none;">
			<legend>Delete a distribution list</legend>

			<label for="distroList1">Highlight one or more distribution lists and then select &quot;Add
				Request&quot;</label><br/>
			<div class="list-section">
				<div>
					<select name="distroList1" id="distroList1" class="distroList" multiple="multiple" size="10"></select>
				</div>
				<div class="buttons">
					<input type="button" id="dlDelete" value="Add Request" />
				</div>
			</div>
		</fieldset>

		<fieldset id="dlAddDiv" class="hidethis" style="display: none">
			<legend>Add employees to an existing distribution list</legend>

			Highlight employees from the left and one or several distributions lists from the right and then select
			&quot;Add Request&quot;<br/><br/>

			<div class="list-section">
				<div>
					<label>Employees:<br/>
						<select name="userList2" id='userList2' class="userList" multiple="multiple" size="10"
						        style="width: 300px; "></select>
					</label>
				</div>
				<div>
					<label>Distribution Lists:<br/>
						<select name="distroList2" id="distroList2" class="distroList" multiple="multiple"
						        size="10"></select>
					</label>
				</div>
				<div class="buttons">
					<input type="button" id="dlAdd" value="Add Request" />
				</div>
			</div>
		</fieldset>

		<fieldset id="dlRemoveDiv" class="hidethis" style="display: none">
			<legend>Remove employees from an existing distribution list</legend>

			Highlight employees from the left and one or several distributions lists from the right and then select
			&quot;Add Request&quot;<br/><br/>

			<div class="list-section">
				<div>
					<label>Employees:<br/>
						<select name="userList3" id='userList3' class="userList" multiple="multiple" size="10"
						        style="width: 300px; "></select>
					</label>
				</div>
				<div>
					<label>Distribution Lists:<br/>
						<select name="distroList3" id="distroList3" class="distroList" multiple="multiple"
						        size="10"></select>
					</label>
				</div>
				<div class="buttons">
					<input type="button" id="dlRemove" value="Add Request" />
				</div>
			</div>
		</fieldset>

		<br />
		<label for="actionList">Request list*: </label>
		<input type="button" id="btnDelRequest" value="Remove request" data-select="#actionList" />
		<input type="hidden" name="actions" id="actions" />
		<select name="actionList" id="actionList" size="10" style="width: 700px; margin-top: 6px; ">
			<?php
				if (isset($dataArr['actions'])){
					//echo "<option>$dataArr['actions']</option>\n";
					$actionsArr = json_decode($dataArr['actions'], true);
					//print_r($actionsArr);
					for ($i = 0; $i < count($actionsArr); $i++){
						echo "<option value=\"$actionsArr[$i]\">$actionsArr[$i]</option>\n";
					}
				}
			?>
		</select>
	</fieldset>

	<fieldset id="webExUsersDiv" class="hidethis" style="display: none">
		<legend>WebEx Account</legend>

		<label for="webExUserList">Select the user(s) who will be getting accounts:</label><br />
		<input type="hidden" name="webExUsers" id="webExUsers" />
		<select id='webExUserList' class='userList' multiple='multiple' size='3' style='width: 304px'></select>
	</fieldset>

	<fieldset>
		<legend>Assessment/Consultation/General Notes</legend>

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
			<option>Merch Team</option>
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
			<option>Retail Store</option>
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
				your name and email address below.  If you are not please fill in your leader's contact information and he/she will receive
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
