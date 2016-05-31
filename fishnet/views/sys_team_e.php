<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */
?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" />
<section class="primary">
	<?php $this->load->view('page_parts/team_header'); ?>

	<?php $articles = array_merge(
				val($section1, array()),
				val($section2, array()),
				val($section3, array())
	); ?>
	<?php $this->load->view('page_parts/btl_rotator', array('articles' => $articles)); ?>

	<h2 class="b">About us</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
			$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>

	<?php $this->load->view('page_parts/team_tabs', array('tabs' => val($tabs, array()))); ?>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/team_rightcol');
