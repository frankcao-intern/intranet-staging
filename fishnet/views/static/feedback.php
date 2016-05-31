
<head>
<style type="text/css">
.required-char {
	color: #800000;
}
.field-group-a {
	font-family: SourceSansProSemibold;
	font-size: small;
}
</style>
</head>

<?php
/**
 * Created By: cravelo
 * Date: Jan 4, 2011
 * Time: 3:47:05 PM
 */
$this->load->helper('form'); 


if (isset($_POST['email'], $_FILES['file']))
{
/*	$body = <<<BODY
	From: {$_POST['email']}
	
	Detail:
		Name:	{$_FILES['file']['name']}
		Size:   {$_FILES['file']['size']}
		Type:	{$_FILES['file']['type']}
BODY;*/
	mail_file($to, $from, $subject, '', $_FILES['file']);
	
	
}

?> 

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/home", "&#x25c4;&nbsp;Home Page")?></p>
		<h2>Feedback</h2>
	</div>

	<?=form_open("/server/feedback/".sha1($this->session->userdata('display_name'))."/".time(), 'id="feedbackForm"')?>
		<div class="field-group-a">
					
			<div class="field-group-a" >
				
				<!--Requester name (readonly)-->
				<label for="name">Requested by:</label>
				<input name="name" id="name" style="background-color:#eae5dc; border:0px;" readonly value="<?=$this->session->userdata('display_name')?>"/>
				
				<!--Email of requester-->
				<label for="email" style="padding-left:5px;">Your E-mail:<span class="required-char">*</span></label>
				<input type="text" name="email" id="email" required size="38"/>
	
				<!-- Date needed-->
				<label for="date" style="padding-left:35px;">Date needed:<span class="required-char">*</span></label>
				<input type="text" id="date" name="date" required onclick="$('#date').datepicker();$('#date').datepicker('option', 'minDate', 2);$('#date').datepicker('show');" size="10" onfocus="$('#date').datepicker();$('#date').datepicker('option', 'minDate', 2);$('#date').datepicker('show');"/>

			</div>
			
			<div class="field-group-a">
				<label for="requestType">Type of Request:<span class="required-char">*</span></label>
				<select id="requestType" name="requestType">
					<option>Who's Who Correction</option>
					<option>Editorial Content</option>
					<option>Knowledge Tree Access</option>
					<option>Machform Access</option>
					<option>Enhancement Requests</option>
					<option>Issues</option>
					<option>Other</option>
				</select>
							
				<label for="dept" style="padding-left:30px;">Your Department:</label>
				<select id="dept" name="dept"> 
					<option ></option>
					<option>Accounting</option>
					<option>Advertising & Communication</option>
					<option>Architecture, Planning & Design</option>
					<option>Business Planning</option>
					<option>Core Concept</option>
					<option>Customer Service</option>
					<option>Department Store Merchandising</option>
					<option>Department Store Sales</option>
					<option>Design</option>
					<option>Distribution Center</option>
					<option>EF Community Foundation</option>
					<option>Ecommerce</option>
					<option>Facilities</option>
					<option>Global</option>
					<option>Graphic Design</option>
					<option>Green Eileen</option>
					<option>Human Resources</option>
					<option>Information Technology</option>
					<option>Internal Communications</option>
					<option>Leadership,Learning & Development</option>
					<option>Manufacturing</option>
					<option>Marketing</option>
					<option>Merchandising</option>
					<option>Product Development</option>
					<option>Retail Buying</option>
					<option>Retail G&A</option>
					<option>Retail LL&D</option>
					<option>Retail Planning / Allocation</option>
					<option>Retail Store</option>
					<option>Store Ops</option>
					<option>Social Consciousness</option>
					<option>Specialty Store Sales</option>
					<option>Visuals</option>
					<option>Wholesale Planning & Ops</option>
				</select>
			</div>
			
			<!--div class="field-group-a">
				<label for="File" >Upload any file:</label>
				<input type="file" id="file "name="attach[]" multiple />
			</div-->
			
			<!--div class="field-group-a">
				<label for="subject_specific">Specify:</label>
				<input type="text" id="subject_specific" name="subject_specific" />
			</div-->
			<div class="field-group-a">
				<label for="desc">
					Brief description of request/issue:<span class="required-char">*</span>
				</label>
				<textarea id="desc" name="desc" rows="8" cols="90" required></textarea>
			</div>
			
			<fieldset class="action" style="border-style: none; padding: 0 0 20px 0;">
				<input type="submit" id="submitFeedback" value="Submit" class="ui-button ui-widget ui-state-default ui-corner-all"/>
			</fieldset>
			
		</div>
	<?=form_close()?>

</section>

<aside class="secondary">
	<h2 class="c">When you enter your feedback, please be as specific as possible:</h2>
	<ul class="list-a list-a-b">
		<li>Share the URL of the page you are referring to.</li>
		<li>Name the specific article or page you are commenting on; or,</li>
		<li>if you receive an error message, copy and paste this language here.</li>
		<li>You should receive a response within 48 hours at that time we will discuss if the request can be completed by the requested date.</li>
	</ul>
</aside>
