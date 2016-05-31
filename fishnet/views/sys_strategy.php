<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */
	$vimeoID = preg_replace("/[^\d]+/", '', $revision['revision_text']['videoURL']); // -- http://vimeo.com/ID
	$width = val($params['w'], 759);
	$height = val($params['h'], 427);
	$protocol = $this->config->item('protocol');

?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" />
<section class="primary">
	<?php $this->load->view('page_parts/team_header'); ?>

	<iframe src="<?=$protocol?>://player.vimeo.com/video/<?=$vimeoID?>?byline=0&portrait=0&color=999999"
			width="<?=$width?>"	height="<?=$height?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen
			allowFullScreen>
	</iframe>

	<br>
	<h2 class="b">About us</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
			$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>

	<?php $this->load->view('page_parts/team_tabs', array('tabs' => val($tabs, array()))); ?>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/related_info');
