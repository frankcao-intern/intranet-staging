<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */

$tabs = val($tabs, array());
$stores = val($stores, array());
$stores_by_state = array();
for ($i = 0; $i < count($stores); $i++){
	$state = $stores[$i]['state'];
	$stores_by_state[$state][] = $stores[$i];
}
?>
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/user/journal.css" />
<section class="primary">
	<?php $this->load->view('page_parts/team_header'); ?>

	<div class="team-tabs">
		<?php if (isset($edit)): ?>
			<div>
				<?php foreach($tabs as $tab): ?>
					<?=anchor("edit/".$tab['page_id'], "Edit - ".$tab['title'])."<br /><br />"?>
				<?php endforeach ?>
			</div>
		<?php else: ?>
			<ul>
				<?php
				foreach($tabs as $tab):
					$tabTitle = htmlentities($tab['title'], ENT_COMPAT, 'UTF-8', false);
					$url = site_url("article/".$tab['page_id']."/layout/tab");
				?>
					<li id="<?=$tab['page_id']?>">
						<a href="<?=(isset($edit)) ? "#tab_".$tab['page_id'] : $url?>">
								<?=$tabTitle?><span>&nbsp;</span>
						</a>
					</li>
				<?php endforeach; ?>

				<li><a href="#usMapTab">US Map</a></li>
			</ul>
			<div id="usMapTab">
				<div id="usMap"></div>
				<?php foreach ($stores_by_state as $st => $state_stores): ?>
					<div class="state-store-list" id="<?=$st?>">
						<ul class="styled-bulletlist">
							<?php foreach ($state_stores as $store): ?>
								<?php
									if (isset($store['link']) and !empty($store['link'])){
										$store['name'] = anchor($store['link'], $store['name']);
									}
								?>
								<li data-lat="<?=$store['latitude']?>" data-long="<?=$store['longitude']?>"
								    data-name="<?=ucwords(strtolower($store['name']))?>">
									<?php echo implode(', ', array(
											ucwords(strtolower($store['name'])),
											ucwords(strtolower($store['city'])),
											strtoupper($store['state'])
										));?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
				<div class="link-list article">
					<p>
						<a href="#ui-tabs-1">Go Fish</a>&nbsp;|&nbsp;
						<a href="#ui-tabs-2">Store List</a>&nbsp;|&nbsp;
						<a href="/staticpages/specialty_suggest">Recommend a Store</a>&nbsp;|&nbsp;
						<a title="Go Fish FAQ" href="/article/1732">FAQ</a> <!--TODO: I hate having this magic number here -->
					</p>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<h2 class="b">About us</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
		$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>

	<?php $this->load->view('page_parts/team_blog'); ?>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/team_rightcol'); ?>

<script src="<?=STATIC_URL?>js/lib/raphael.js"></script>
