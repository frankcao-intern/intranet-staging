<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.treeview.css" />

<section class="primary">
	<div class="header-a">
        <h2><?=val($title, 'Untitled Page')?></h2>
    </div>
	<?php
		$htmlStr = ($nodirs) ? '<ul id="formsList">' : '<ul id="treeview">';

		/**
		 * Takes a multidimensional array and creates nested HTML lists.
		 *
		 * @param array $mArray the array created by documents->arrayFromDir
		 * @param string $htmlStr reference to the string to store the resulting HTML
		 * @param bool $nodirs whether the array is flat or multi-dimensional
		 * @return void
		 * $name is the folder stucture
		 * $item are the files under each folder
		 */
		function multiArrayToHTML($mArray, &$htmlStr, $nodirs) {
			foreach ( $mArray as $name => $item ) {
				if ($item == "") continue;
				//print_r($item);
				if ( is_array($item) ) {
					$htmlStr .= ($nodirs) ? '' : '<li>&nbsp;<span>'.$name."</span><ul>\n";
					if (!$nodirs) asort($mArray[$name]);
					multiArrayToHTML($item, $htmlStr, $nodirs);
					$htmlStr .= ($nodirs) ? '' : "</ul>\n</li>\n";
				
				//Facilites machforms redirect
				/*}elseif($item == "documents/forms/Facilities/Automated Forms/Retail Event Setup Request Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=48047', "Retail Stores - Event Setup Form")."</li>\n";*/
				}elseif($item == "documents/forms/Facilities/Automated Forms/Keycard Request Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=96962', "Keycard Request Form")."</li>\n";
				}elseif($item == "documents/forms/Facilities/Automated Forms/Desk Request Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=15193', "Desk Request Form")."</li>\n";
				}elseif($item == "documents/forms/Facilities/Automated Forms/IRV Meeting and Event Setup Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=59284', "IRV Meeting and Event Setup Form")."</li>\n";
				}elseif($item == "documents/forms/Facilities/Automated Forms/NYC Meeting and Event Setup Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=7005', "NYC Meeting and Event Setup Form")."</li>\n";
				}elseif($item == "documents/forms/Facilities/Automated Forms/Car Registration Form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=2388', "Car Registration Form")."</li>\n";
				
				//The Learning Lab automated form redirect
				}elseif ($item == "documents/forms/The Learning Lab/Learning Lab Space Request.docx"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://efli4life.wufoo.com/forms/learning-lab-space-request/', "Learning Lab Space Request")."</li>\n";	
				}elseif($item == "documents/forms/Wholesale Sales/Specialist Update Form(place_holder).pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=53732', "Specialist Update Form")."</li>\n";
				
				//HR machforms redirect
				}elseif($item == "documents/forms/Human Resources/Recognition/Recognize_Someone(place_holder).pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=28683', "Recognize Someone Today (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Human Resources/Benefits/Flu_Shot(place_holder).pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=54449', "Flu Shot Reinbursement Request Form (E-form)")."</li>\n";	
				}elseif($item == "documents/forms/Human Resources/Work-Related Injuries/Accident & Injury Report - Employee.doc"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=74417', "Employee-Accident & Injury Report (E-form)")."</li>\n";	
				//HR Staffing machforms redirect
				}elseif($item == "documents/forms/Human Resources/Staffing/Applicant Worksheet.DOC"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=99215', "New Hire Offer Worksheet (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Human Resources/Staffing/Applicant Recommendation Form.doc"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=21', "Applicant Recommendation (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Human Resources/Staffing/Internal Job Application.doc"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=98318', "Internal Job Application (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Accounting/Accident Insurance/Accident & Injury Report - Customer.doc"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=121694', "Non-Employee Accident & Injury Report (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Accounting/Accident Insurance/Accident & Injury Report - Employee.doc"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=74417', "Employee Accident & Injury Report (E-form)")."</li>\n";
				}elseif($item == "documents/forms/Human Resources/Benefits/401k/401(k) Bonus Deferral Election for 2015 e-form.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=82961', "401(k) Bonus Deferral Election for 2015(E-form)")."</li>\n";
				
				//Employee Discount machforms redirect
				}elseif($item == "documents/forms/Employee Discounts/Friends Program/Friends Program FAQs.docx"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/article/6006', "Friends Program FAQs")."</li>\n";
				}elseif($item == "documents/forms/Employee Discounts/Friends Program/Friends Program Inquiries & Card Request Form.docx"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('https://fishnet.eileenfisher.com/forms/view.php?id=154251', "Friends Program Inquiries & Card Request Form (E-form)")."</li>\n";
					
	
				//The IT automated form request redirect
				/*}elseif ($item == "documents/forms/Information Technology/Remote Access Services User's Guide.pdf"){
					$htmlStr .= '<li class="forms-file">'.
					anchor('/article/3251/', "Automated Forms Request")."</li>\n";	
				*/	
				}else{
					
					$htmlStr .= '<li class="forms-file">'.
					anchor('/proxy/downloadDoc/'.base64_encode($item),
					htmlentities(pathinfo($item, PATHINFO_FILENAME), ENT_COMPAT, 'UTF-8', false)).
					"</li>\n";
					
				}
			}
		}

		multiArrayToHTML($forms, $htmlStr, $nodirs);
	?>
	
	<?php if($nodirs): ?>
	
	<!--div class="col titled-columns"-->
		<ul class="digest-nav">
		<li class="sort-link"><font size="3"><?=anchor("/efpublic/0","Forms & Documents by Category")?></font></li>
		<li class="sort-link" style="border-right: none"><font size="3"><a href="#" class="all_forms">All Forms & Documents</a></font></li>
			<li class="highlighter">&nbsp;</li></ul>
				<!--?=$htmlStr?></ul-->
			
			
		<!--font size="3"><?=anchor("/efpublic/0", "Forms & Documents by Category ")?></font-->
		<!--a href="#" style="background-color: #FFFFFF;"><font color="#333333" size="3" >&nbsp;&nbsp;|
				&nbsp;&nbsp; All Forms & Documents </font></a-->
	
		<div class="alpha-list">
			<form class="header-right" action="#" method="get">
			    <label class="offset" for="quickSearch">Search documents:</label>
			    <font size="2"> <input type="text" id="quickSearch" name="q" autofocus="autofocus"
				   placeholder=" Type to search documents" size="60" style="height:26px;"
				    /></font><br/><br/>
					<?=$htmlStr?>
			</form>
		</div>		
	<!--/div-->

	<?php else: ?>
	

	<div class="col tree titled-columns">
		<ul class="digest-nav">
			<li class="sort-link"><font size="3"><a href="#" class="category">Forms & Documents by Category</a></font></li>
			<li class="sort-link"><font size="3"><?=anchor("/efpublic/1","All Forms & Documents")?></font>></li>
			<li class="highlighter">&nbsp;</li></ul>
				<?=$htmlStr?>
		
			<!--a href="#" style="background-color: #ffffff;"><font color="black" size="3">
			Forms &	Documents by Category &nbsp;&nbsp;|&nbsp;&nbsp;</font></a><font size="3"-->
			<!--?=anchor("/efpublic/1", "All Forms & Documents")?></font-->
			<!--?=$htmlStr?-->
	</div>
	<?php endif; ?>
</section><!--/ #primary -->

<aside class="secondary">
    <div class="section-a">
	<h3 class="about">Consider this your one-stop-shop for easily accessing the forms and documents you need.</h3>
    </div>
	<div class="section-a">
		<div class="col">
			<h2 class="c">Guidelines & Resources</h2>
			<div class="edit-wysiwygadv listbox" data-key="resources">
				<?=isset($revision['revision_text']['resources'])
				? $revision['revision_text']['resources']:''?>
			</div>
		</div>
	</div>

</aside>	


