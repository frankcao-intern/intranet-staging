<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */

?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:400,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/scrollstyles_it.css" media="all" />
<!--link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/scrollstyles_infra.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/scrollstyles_wholesale.css" media="all" /-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/sidescroll_it.js"></script>
<!--script type="text/javascript" src="<?=STATIC_URL?>js/lib/sidescroll_infra.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/lib/sidescroll_wholesale.js"></script-->
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/info_tech.css">

<section class="primary" style="width: 773px;" >

	<div class="header-a">
		<?php if (isset($edit)): ?>
			<?=anchor("/department/$page_id", "&#x25c4; Preview Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?>
		<?php else: ?>
			<p><?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?><?=date("l, F j, Y")?></p>			
		<?php endif; ?>

		<h2><?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?></h2>
	</div>
	

	
	<!--h4>The Information Technology (IT) team provides the infrastructure,
	support and technicals leadership with the company. Our work encompasses a range
	of areas.</h4-->
	
	
	<div style="background-color: #ebebff; height: 219px; padding: 5px; border: 2px solid #ccc;">
		<h3 class="c">Technical Services...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="helpdesk">
			<?php echo (isset($revision['revision_text']['helpdesk']) and (!empty($revision['revision_text']['helpdesk']))) ?
				$revision['revision_text']['helpdesk'] : "Enter your team description here."; ?>
		</div>
	</div>

	<br style="line-height: 20px;">	
	<div style="background-color: #d8d8ff; height: 219px; padding:5px; border: 2px solid #ccc;">
		<h3 class="c">Infrastructure...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="infra">
			<?php echo (isset($revision['revision_text']['infra']) and (!empty($revision['revision_text']['infra']))) ?
				$revision['revision_text']['infra'] : "Enter your team description here."; ?>
		</div>
	</div>

	<br style="line-height: 20px;">
	<div style="background-color: #ebebff; height: 219px; padding:5px; border: 2px solid #ccc;">
		<h3 class="c">Wholesale Business Applications...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="wholesale">
			<?php echo (isset($revision['revision_text']['wholesale']) and (!empty($revision['revision_text']['wholesale']))) ?
				$revision['revision_text']['wholesale'] : "Enter your team description here."; ?>
		</div>
	</div>
	
	<br style="line-height: 20px;">
	<div style="background-color: #d8d8ff; height: 219px; padding:5px; border: 2px solid #ccc;">
		<h3 class="c">Retail Systems Support...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="retail">
			<?php echo (isset($revision['revision_text']['retail']) and (!empty($revision['revision_text']['retail']))) ?
				$revision['revision_text']['retail'] : "Enter your team description here."; ?>
		</div>
	</div>
	
	<br style="line-height: 20px;">
	<div style="background-color: #ebebff; height: 219px; padding:5px; border: 2px solid #ccc;">
		<h3 class="c">Web Technology...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="web">
			<?php echo (isset($revision['revision_text']['web']) and (!empty($revision['revision_text']['web']))) ?
				$revision['revision_text']['web'] : "Enter your team description here."; ?>
		</div>
	</div>
	
	<br style="line-height: 20px;">
	<div style="background-color: #d8d8ff; height: 219px; padding:5px; border: 2px solid #ccc; ">
		<h3 class="c">PLM...</h3>
		<div class="edit-wysiwygadv team-desc clearfix" data-key="plm">
			<?php echo (isset($revision['revision_text']['plm']) and (!empty($revision['revision_text']['plm']))) ?
				$revision['revision_text']['plm'] : "Enter plm information here."; ?>
		</div>
	</div>

	<br>
	<h4>Technology has become increasingly important to our processes, systems and enhanced
	connection within the company.  The IT team partners with individuals and teams
	throughout the organization to identify ways for greater simplicity and efficiency and to
	think with them about how technology can provide solutions that satisfy these needs.</h4>
	
	<!--?php $this->load->view('page_parts/team_tabs', array('tabs' => val($tabs, array()))); ?-->

</section> <!--/ #primary -->

<aside class="secondary" style="width: 192px;">
	<br style="line-height: 66px;">
	<div id="tipster">
		<div id="tipsterContent" class="topMargin">
			<div id="tipsterSideScroll">
				<h2 class="sideScrollHeadline">Technical Support TEAM</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="scrollWrapper">
					<ul id="sideScrollItems">
						<li class="scrollBtn">
							<a href="/profiles/30" title="Clarence Faison">
								<img src="/images/profile/clarencef/w/80/zc/80" alt="Clarence Faison"></a>
							<div>
							<div><a href="/profiles/30" title="Clarence Faison"><b>Clarence Faison</b></a></div>
							<div>Manager of Technical Services</div>
							<div>P: 914.721.4179</div>
							<a href="mailto:cfaison@eileenfisher.com" title="cfaison@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/7513" title="Damion Edwards">
								<img src="/images/profile/dedwards/w/80/zc/80" alt="Damion Edwards"></a>
							<div>
							<div><a href="/profiles/7513" title="Damion Edwards"><b>Damion Edwards</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 212.420.5900</div>
							<a href="mailto:dedwards@eileenfisher.com" title="dedwards@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/4930" title="Joshua Goodwin">
								<img src="/images/profile/jgoodwin/w/80/zc/80" alt="Joshua Goodwin"></a>
							<div>
							<div><a href="/profiles/4930" title="Joshua Goodwin"><b>Joshua Goodwin</b></a></div>
							<div>Digital Media Specialist</div>
							<div>P: 201.420.5900</div>
							<a href="mailto:jgoodwin@eileenfisher.com" title="jgoodwin@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/5205" title="Kevin Brewer">
								<img src="/images/profile/kbrewer/w/80/zc/80" alt="Kevin Brewer"></a>
							<div>
							<div><a href="/profiles/5205" title="Kevin Brewer"><b>Kevin Brewer</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 914.591.5700</div>
							<a href="mailto:kbrewer@eileenfisher.com" title="kbrewer@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/4921" title="Michael Agosto">
								<img src="/images/profile/magosto/w/80/zc/80" alt="Michael Agosto"></a>
							<div>
							<div><a href="/profiles/4921" title="Michael Agosto"><b>Michael Agosto</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 914.721.2847</div>
							<a href="mailto:magosto@eileenfisher.com" title="magosto@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/4983" title="Michael Berkowitz">
								<img src="/images/profile/mberkowitz/w/80/zc/80" alt="Michael Berkowitz"></a>
							<div>
							<div><a href="/profiles/4983" title="Michael Berkowitz"><b>Michael Berkowitz</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 914.721.2845</div>
							<a href="mailto:mberkowitz@eileenfisher.com" title="mberkowitz@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/7341" title="Tony Stanton">
								<img src="/images/profile/tstanton/w/80/zc/80" alt="Tony Stanton"></a>
							<div>
							<div><a href="/profiles/7341" title="Tony Stanton"><b>Tony Stanton</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 914.721.2845</div>
							<a href="mailto:tstanton@eileenfisher.com" title="tstanton@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="scrollBtn">
							<a href="/profiles/7291" title="Victor Scott">
								<img src="/images/profile/vscott/w/80/zc/80" alt="Victor Scott"></a>
							<div>
							<div><a href="/profiles/7291" title="Victor Scott"><b>Victor Scott</b></a></div>
							<div>Helpdesk/Technical Support Associate</div>
							<div>P: 914.721.2845</div>
							<a href="mailto:vscott@eileenfisher.com" title="vscott@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						
						</ul><!--sideScrollItem-->
				</div><!--scrollWrapper-->
				<div id="sideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--tipsterSideScroll-->
		</div>
	</div>
	<br style="line-height: 18px;">
	<div id="infrastructure">
		<div id="infraContent" class="topMargin">
			<div id="infraSideScroll">
				<h2 class="sideScrollHeadline">Infrastructure Team</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="infraScrollWrapper">
					<ul id="infraSideScrollItems">
						<li class="infraScrollBtn">
							<a href="/profiles/17" title="Nelson Diaz">
								<img src="/images/profile/nelsond/w/80/zc/80" alt="Nelson Diaz"></a>
							<div>
							<div><a href="/profiles/17" title="Nelson Diaz"><b>Nelson Diaz</b></a></div>
							<div>Manager-IT Infrastructure</div>
							<div>P: 914.721.4028</div>
							<a href="mailto:nelsond@eileenfisher.com" title="nelsond@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="infraScrollBtn">
							<a href="/profiles/7616" title="Andres Faciolince">
								<img src="/images/profile/afaciolince/w/80/zc/80" alt="Andres Faciolince"></a>
							<div>
							<div><a href="/profiles/7616" title="Andres Faciolinc"><b>Andres Faciolinc</b></a></div>
							<div>IT Infrastructure Associate</div>
							<div>P: 914.648.3683</div>
							<a href="mailto:afaciolince@eileenfisher.com" title="afaciolince@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						</ul><!--infraSideScrollItem-->
				</div><!--infraScrollWrapper-->
				<div id="infraSideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--infraSideScroll-->
		</div>
	</div>
	
	<br style="line-height: 19px;">
	<div id="wholesale">
		<div id="wbaContent" class="topMargin">
			<div id="wbaSideScroll">
				<h2 class="sideScrollHeadline">Wholesale Bus. Apps Team</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="wbaScrollWrapper">
					<ul id="wbaSideScrollItems">
						<li class="wbaScrollBtn">
							<a href="/profiles/5137" title="David Litwak">
								<img src="/images/profile/dlitwak/w/80/zc/80" alt="David Litwak"></a>
							<div>
							<div><a href="/profiles/5137" title="David Litwak"><b>David Litwak</b></a></div>
							<div>Manager, Supply Chain Systems</div>
							<div>P: 914.721.4169</div>
							<a href="mailto:dlitwak@eileenfisher.com" title="dlitwak@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="wbaScrollBtn">
							<a href="/profiles/161" title="Alok Mukherjee">
								<img src="/images/profile/amukherjee/w/80/zc/80" alt="Alok Mukherjee"></a>
							<div>
							<div><a href="/profiles/161" title="Alok Mukherjee"><b>Alok Mukherjee</b></a></div>
							<div>Business Analyst</div>
							<div>P: 201.583.8733</div>
							<a href="mailto:amukherjee@eileenfisher.com" title="amukherjee@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="wbaScrollBtn">
							<a href="/profiles/62" title="Hinesh Patel">
								<img src="/images/profile/hineshp/w/80/zc/80" alt="Hinesh Patel"></a>
							<div>
							<div><a href="/profiles/62" title="Hinesh Patel"><b>Hinesh Patel</b></a></div>
							<div>Senior System Administrator</div>
							<div>P: 201.583.8732</div>
							<a href="mailto:hineshp@eileenfisher.com" title="hineshp@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="wbaScrollBtn">
							<a href="/profiles/40" title="Robert Paul">
								<img src="/images/profile/rpaul/w/80/zc/80" alt="Robert Paul"></a>
							<div>
							<div><a href="/profiles/40" title="Robert Paul"><b>Robert Paul</b></a></div>
							<div>System Administrator</div>
							<div>P: 201.583.8711</div>
							<a href="mailto:rpaul@eileenfisher.com" title="rpaul@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						</ul><!--sideScrollItem-->
				</div><!--scrollWrapper-->
				<div id="wbaSideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--tipsterSideScroll-->
		</div>
	</div>
	
	<br style="line-height: 18px;">
	<div id="retail">
		<div id="retailContent" class="topMargin">
			<div id="retailSideScroll">
				<h2 class="sideScrollHeadline">Retail Sys. Support Team</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="retailScrollWrapper">
					<ul id="retailSideScrollItems">
						<li class="retailScrollBtn">
							<a href="/profiles/54" title="George Peppin">
								<img src="/images/profile/gpeppin/w/80/zc/80" alt="George Peppin"></a>
							<div>
							<div><a href="/profiles/54" title="George Peppin"><b>George Peppin</b></a></div>
							<div>Manager of Retail Systems</div>
							<div>P: 914.721.2872</div>
							<a href="mailto:gpeppin@eileenfisher.com" title="gpeppin@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="retailScrollBtn">
							<a href="/profiles/4598" title="Anthony Bailey">
								<img src="/images/profile/abailey/w/80/zc/80" alt="Anthony Bailey"></a>
							<div>
							<div><a href="/profiles/4598" title="Anthony Bailey"><b>Anthony Bailey</b></a></div>
							<div>POS Analyst</div>
							<div>P: 914.721.2846</div>
							<a href="mailto:abailey@eileenfisher.com" title="abailey@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="retailScrollBtn">
							<a href="/profiles/193" title="Michael Ko">
								<img src="/images/profile/mko/w/80/zc/80" alt="Michael Ko"></a>
							<div>
							<div><a href="/profiles/193" title="Michael Ko"><b>Michael Ko</b></a></div>
							<div>Sr. Business Analyst</div>
							<div>P: 914.721.4014</div>
							<a href="mailto:mko@eileenfisher.com" title="mko@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="retailScrollBtn">
							<a href="/profiles/4997" title="Myriha Burce">
								<img src="/images/profile/mburce/w/80/zc/80" alt="Myriha Burce"></a>
							<div>
							<div><a href="/profiles/4997" title="Myriha Burce"><b>Myriha Burce</b></a></div>
							<div>Jr. Analyst</div>
							<div>P: 212.466.4971</div>
							<a href="mailto:mburce@eileenfisher.com" title="mburce@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						</ul><!--sideScrollItem-->
				</div><!--scrollWrapper-->
				<div id="retailSideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--tipsterSideScroll-->
		</div>
	</div>
	
	<br style="line-height: 18px;">
	<div id="web">
		<div id="webContent" class="topMargin">
			<div id="webSideScroll">
				<h2 class="sideScrollHeadline">Web Technology Team</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="webScrollWrapper">
					<ul id="webSideScrollItems">
						<li class="webScrollBtn">
							<a href="/profiles/4561" title="Michael Baresh">
								<img src="/images/profile/mbaresh/w/80/zc/80" alt="Michael Baresh"></a>
							<div>
							<div><a href="/profiles/4561" title="Michael Baresh"><b>Michael Baresh</b></a></div>
							<div>Manager, Web Technologies</div>
							<div>P: 201.583.8708</div>
							<a href="mailto:mbaresh@eileenfisher.com" title="mbaresh@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="webScrollBtn">
							<a href="/profiles/39" title="Hassan Moustafa">
								<img src="/images/profile/hmoustafa/w/80/zc/80" alt="Hassan Moustafa"></a>
							<div>
							<div><a href="/profiles/39" title="Hassan Moustafa"><b>Hassan Moustafa</b></a></div>
							<div>Senior Web Applications Developer</div>
							<div>P: 201.583.8731</div>
							<a href="mailto:hmoustafa@eileenfisher.com" title="hmoustafa@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="webScrollBtn">
							<a href="/profiles/5130" title="Karen LaPorta">
								<img src="/images/profile/kyoung/w/80/zc/80" alt="Karen LaPorta"></a>
							<div>
							<div><a href="/profiles/5130" title="Karen LaPorta"><b>Karen LaPorta</b></a></div>
							<div>Web Developer</div>
							<div>P: 914.721.4152</div>
							<a href="mailto:klaporta@eileenfisher.com" title="klaporta@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="webScrollBtn">
							<a href="/profiles/4489" title="Marina Barskaia">
								<img src="/images/profile/mbarskaia/w/80/zc/80" alt="Marina Barskaia"></a>
							<div>
							<div><a href="/profiles/4489" title="Marina Barskaia"><b>Marina Barskaia</b></a></div>
							<div>Senior Web Developer</div>
							<div>P: 201.583.8728</div>
							<a href="mailto:mbarskaia@eileenfisher.com" title="mbarskaia@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						<li class="webScrollBtn">
							<a href="/profiles/22" title="Paul Kanakaraj">
								<img src="/images/profile/paulk/w/80/zc/80" alt="Paul Kanakaraj"></a>
							<div>
							<div><a href="/profiles/22" title="Paul Kanakaraj"><b>Paul Kanakaraj</b></a></div>
							<div>Ecommerce System Analyst</div>
							<div>P: 914.721.4017</div>
							<a href="mailto:paulk@eileenfisher.com" title="paulk@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						</ul><!--sideScrollItem-->
				</div><!--scrollWrapper-->
				<div id="webSideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--tipsterSideScroll-->
		</div>
	</div>
	
	<br style="line-height: 18px;">
	<div id="plm">
		<div id="plmContent" class="topMargin">
			<div id="plmSideScroll">
				<h2 class="sideScrollHeadline">PLM Team</h2>
				<!--div class="col" style="font-size: large;"><b>Technical Support</b></div-->
				
				<div id="plmScrollWrapper">
					<ul id="plmSideScrollItems">
						<li class="plmScrollBtn">
							<a href="/profiles/4575" title="Nathalie Rizos">
								<img src="/images/profile/nrizos/w/80/zc/80" alt="Nathalie Rizos"></a>
							<div>
							<div><a href="/profiles/4575" title="Nathalie Rizos"><b>Nathalie Rizos</b></a></div>
							<div>Business Analyst-Wholesale Systems</div>
							<div>P: 917.534.2612</div>
							<a href="mailto:nrizos@eileenfisher.com" title="nrizos@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30"></a>
							</div>
						</li>
						<li class="plmScrollBtn">
							<a href="/profiles/24" title="Jackie Spellen">
								<img src="/images/profile/jackies/w/80/zc/80" alt="Jackie Spellen"></a>
							<div>
							<div><a href="/profiles/24" title="Jackie Spellen"><b>Jackie Spellen</b></a></div>
							<div>EDI Assistant</div>
							<div>P: 201.583.8712</div>
							<a href="mailto:jackies@eileenfisher.com" title="jackies@eileenfisher.com">
							<img src="/assets/images/tooltip-contact-email.png" alt="email" width="30" style="background-color: #fff;"></a>
							</div>
						</li>
						</ul><!--sideScrollItem-->
				</div><!--scrollWrapper-->
				<div id="plmSideArrowDown"><div class="more" style="padding-right: 20px;">Click for next team member</div></div>
			</div><!--tipsterSideScroll-->
		</div>
	</div>
</aside>
