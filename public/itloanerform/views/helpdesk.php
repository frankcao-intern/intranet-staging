<?php
/**
 * Created by: cravelo
 * Date: 12/6/11
 * Time: 11:05 AM
 */

?>

<div id="helpdesk">
	<?php if (isset($dataArr['helpdesk']['hpEmail'])): ?>
		<script>
			$(document).ready(function(){
				$("#helpdesk").slideUp();
			});
		</script>
	<?php endif; ?>
	<?php echo implode("<br><br>", $dataArr['strResult']); ?>
</div>
<form id="hpForm" method="post" action="<?php echo $settings['base_url']; ?>?p=hpsave">
	<input type="hidden" id="requestSerial" name="requestSerial" value="<?php echo $_GET['f']; ?>">
	<fieldset>
		<legend>For IT Use Only</legend>
		<label for="jobNumber">Job Number:</label>
		<input type="text" name="jobNumber" id="jobNumber" autocomplete="off" size="30" value="<?php if (isset($dataArr['helpdesk']['jobNumber'])) echo $dataArr['helpdesk']['jobNumber']; ?>" />
		<input type="button" id="btnJobNumber" value="Save" />
	</fieldset>
	<fieldset>
		<legend>Pick up</legend>
		<div class="horiz-checks">
			<label>
				<input type="checkbox" name="powerSupply" id="powerSupply"
						value="Power supply/Charger" <?php if (isset($dataArr['helpdesk']['powerSupply'])) echo $checked; ?> />
				Power supply/Charger
			</label>
			<label>
				<input type="checkbox" name="netCable" id="netCable"
						value="Network Cable" <?php if (isset($dataArr['helpdesk']['netCable'])) echo $checked; ?> />
				Network Cable
			</label>
			<label>
				<input type="checkbox" name="USBCable" id="USBCable"
						value="USB Data Cable" <?php if (isset($dataArr['helpdesk']['USBCable'])) echo $checked; ?> />
				USB	Data Cable
			</label>
		</div>
		<div class="vertical-checks">
			<label>
				<input type="checkbox" name="tested" id="tested" value="Equipment Tested/Configured?"
						<?php if (isset($dataArr['helpdesk']['tested'])) echo $checked; ?> />
				Equipment Tested/Configured?
			</label>
			<label>
				<input type="checkbox" name="trained" id="trained" value="User Trained"
						<?php if (isset($dataArr['helpdesk']['trained'])) echo $checked; ?> />
				User Trained
			</label>
		</div>
		<?php if (isset($dataArr['helpdesk']['userEmail'])): ?>
			<div class="signature">
				<p>User signed the pickup on: <?php echo $dataArr['helpdesk']['userSignDate']; ?></p>
				<p>User's Email: <?php echo $dataArr['helpdesk']['userEmail']; ?></p>
			</div>
		<?php else: ?>
			<div class="signature">
				<p>USER PICKING UP: To sign this request fill in your username and password.</p>
				<label>
					User's Email:
					<input type="text" name="userEmail" id="userEmail" autocomplete="off" size="35" readonly="readonly" />
				</label>
				<label>
					User Windows Username: <input type="text" name="uUserName" id="uUserName" autocomplete="off" />
				</label>
				<label>
					User Windows Password: <input type="password" name="userPassw" id="userPassw" />
				</label>
				<label>
					Date:
					<input name="userSignDate" id="userSignDate" type="text" readonly="readonly"
					       value="<?php echo date("m/d/Y"); ?>"/>
				</label>
				<input type="button" id="btnUserSign" value="Sign" />
			</div>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>Return</legend>
		<div class="horiz-checks">
			<label>
				<input type="checkbox" name="powerSupply1" id="powerSupply1" value="Power supply/Charger"
					<?php if (isset($dataArr['helpdesk']['powerSupply1'])) echo $checked; ?> />
				Power supply/Charger
			</label>
			<label>
				<input type="checkbox" name="netCable1" id="netCable1" value="Network Cable"
						<?php if (isset($dataArr['helpdesk']['netCable1'])) echo $checked; ?> />
				Network Cable
			</label>
			<label>
				<input type="checkbox" name="USBCable1" id="USBCable1" value="USB Data Cable"
						<?php if (isset($dataArr['helpdesk']['USBCable1'])) echo $checked; ?> />
				USB Data Cable
			</label>
		</div>
		<?php if (isset($dataArr['helpdesk']['hpEmail'])): ?>
			<div class="signature">
				<p class="redNotice">
					This request was completed by: <?php echo $dataArr['helpdesk']['hpEmail']; ?>
					on: <?php echo $dataArr['helpdesk']['hpSignDate']; ?></p>
			</div>
		<?php else: ?>
			<div class="signature">
				<p>HELPDESK: To sign this request fill in your username and password.</p>
				<label>
					Your Email:
					<input type="text" name="hpEmail" id="hpEmail" autocomplete="off" size="35" readonly="readonly" />
				</label>
				<label>
					Your Windows Username: <input type="text" name="hpUserName" id="hpUserName" autocomplete="off" />
				</label>
				<label>
					Your Windows Password: <input type="password" name="hpPassw" id="hpPassw" />
				</label>
				<label>
					Date:
					<input name="hpSignDate" id="hpSignDate" type="text" readonly="readonly" value="<?php echo date("m/d/Y"); ?>"/>
				</label>
				<input type="button" id="btnHPSign" value="Sign" />
			</div>
		<?php endif; ?>
	</fieldset>
</form>
