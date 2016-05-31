<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */

?>
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/info_tech.css">
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/user/journal.css" />

	
<section class="primary">

	<div class="header-a" style="width:947px;">
		<?php if (isset($edit)): ?>
			<?=anchor("/article/$page_id", "&#x25c4; Preview Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?>
		<?php else: ?>
			<p><?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?><?=date("l, F j, Y")?></p>			
		<?php endif; ?>

		<h2><?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?></h2>
	</div>


	<div class="section-a" style="margin-top: 0;" >
		<!--h2 class="c" style="margin-bottom: 2px; text-align: center; font-size: 1.6em;">E-mail Helpdesk</h2-->
		
		<a href="mailto:HelpdeskTeam@eileenfisher.com" title="HelpdeskTeam@eileenfisher.com">
		<img class="it-corner-all" src="/assets/images/helpdesk_email.jpg" alt="email" width="135" style="border:1px solid #2b2627; box-shadow: 10px 10px 5px #888888;"></a>

		<!--h2 class="c" style="margin-bottom: 2px; text-align: center; font-size: 1.6em;">Call Helpdesk</h2-->
		<img class="it-corner-all" src="/assets/images/helpdesk_phone4.jpg" alt="phone" height="131" width="135" style="border: 1px solid #2b2627; box-shadow: 10px 10px 5px #888888; margin-left: 15px;">

		<!--button class="button"-->
		<a href="mailto:BlueCherrySupportTeam@eileenfisher.com" title="BlueCherrySupportTeam@eileenfisher.com">
		<img class="it-corner-all" src="/assets/images/bluecherry_2.jpg" alt="bluecherry" height="131" width="135" style="border:1px solid #2b2627; margin-left: 15px; box-shadow: 10px 10px 5px #888888; /*padding-left: 18px;*/"></a>
		<!--/button-->
		
		<a href="mailto:plmsupportteam@eileenfisher.com" title="PlmSupportTeam@eileenfisher.com">
		<img class="it-corner-all" src="/assets/images/PLM_2.jpg" alt="plm" width="135" style="border:1px solid #2b2627; box-shadow: 10px 10px 5px #888888; margin-left: 15px;"></a>
	</div>

	
</section> <!--/ #primary -->
