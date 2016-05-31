<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */
$links = val($revision['revision_text']['links']);
$tabs = val($tabs, array());

if (is_string($links)){
	$links = json_decode($links, true);
}?>



<!--link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" /-->
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
					
				<li><a href="#itteams">What Do We Offer?</a></li>
				<li><a href="ourproj">Our Projects</a></li>
				<!--li><a href="icons">Support Icons</a></li-->
				
			</ul>
			<div id="itteams" class="columns double-a" style="width: 730px;">
				
				<div class="col" style="width: 300px; padding-left: 1px;">
					<img src="/assets/images/globe.jpg" alt="support" width="330px;">
				</div>
				<div class="col" style="float: right; width: 380px; ">
					<p>The IT Team is made up of the following teams</p>
					<h3 class="c">Technical Services...</h3>
					<h3 class="c">Infrastructure...</h3>
					<h3 class="c">Wholesale Business Applications...</h3>
					<h3 class="c">Retail Systems Support...</h3>
					<h3 class="c">Web Technology...</h3>
					<h3 class="c">PLM</h3>
					<p><!--a href="/article/3486"--><a href="/article/4976">
					<b>Read more</b> </a>on these different hats of IT and how they can provide you with the help you may desire</p>
				</div>
			</div>
		</div>
		<?php endif; ?>
		
		
		
		
	<br style="line-height: 30px;">
	<h2 class="b">IT Team Mission</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
			$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>
	
	<?php $this->load->view("page_parts/it_blog"); ?>		
	

</section> <!--/ #primary -->

<aside class="secondary" style="width: 192px; border-top: hidden;">
	
	<div class="section-a">
		<h2 class="c">Additional Resources</h2>
		<div style="padding-bottom: 5px;">
			<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" style="background: #FF9900; color: #fff;">
				<span class="ui-button-text"><a href="/article/4983" style="color: #000;">NEW TO EF? </a></span>
			</button>
		</div>
	
		
		<ul class="edit_links styled-bulletlist" id="links">
			<?php foreach($links as $link): ?>
				<?php $linkTitle = htmlentities(val($link['title']), ENT_COMPAT, 'UTF-8', false); ?>
				
					<?php if (isset($edit)): ?>
					<li>
						<span data-url="<?=prep_url(val($link['url']))?>" data-title="<?=val($link['title'])?>">
							<?=$linkTitle?>
						</span>
					</li>
					<?php else: ?>
					<div style="padding-bottom: 5px;">
						<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" style="background: #86827E; color: #000;">
							<span class="ui-button-text">
							<?=anchor(prep_url(val($link['url'])), $linkTitle)?>
						</span></button>
						<!--button style="text-align: left; padding-left: 2px; " ><?=anchor(prep_url(val($link['url'])), $linkTitle)?></button-->
					</div>	
					<?php endif; ?>
				<!--/li-->
			
			<?php endforeach; ?>
		</ul>
	</div>
</aside>