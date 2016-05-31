<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */

$main_image = val($revision['revision_text']['main_image'], array());
?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" />
<section class="primary">
	<?php $this->load->view('page_parts/team_header'); ?>

	<?php if(isset($edit)): ?>
		<?php $this->load->view('page_parts/image_stack', array('width' => 760, 'height' => 471)); ?>
	<?php else: ?>
		<div class="rotator-b">
			<div class="rotator">
				<ul>
					<?php for($i = 0; $i < count($main_image); $i++): ?>
					<?php
					$src = val($main_image[$i]['src'], 'error');
					$flip = val($main_image[$i]['flip'], false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[$i]['angle'], 0);
					$img = get_image_html($src, 760, 471, $flip, $angle);
					$anchor = get_image($src, false,false, $flip, $angle);
					$anchor = basename($anchor);
					?>
					<li>
						<a href="<?=site_url("images/src/$anchor")?>" rel="main_image" class="fancybox">
							<img <?=$img?> alt="<?=ucfirst(htmlentities(val($main_image[$i]['alt']), ENT_COMPAT, 'UTF-8', false))?>"/>
						</a>
					</li>
					<?php endfor; ?>
				</ul>
			</div>
			<div class="rotator-controls rotator-controls-a rotator-controls-b"></div>
		</div>
	<?php endif; ?>

	<h2 class="b">About us</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
			$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>

	<?php $this->load->view('page_parts/team_tabs', array('tabs' => val($tabs, array()))); ?>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/team_rightcol');
