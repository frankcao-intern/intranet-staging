<?php
/**
 * Created by: cravelo
 * Date: 8/29/11
 * Time: 3:44 PM
 */

$tabs = val($tabs, array());
?>

<div class="team-tabs">
	<ul>
		<?php foreach($tabs as $tab): ?>
			<?php
				$tabTitle = htmlentities($tab['title'], ENT_COMPAT, 'UTF-8', false);
				$url = site_url("article/".$tab['page_id']."/layout/tab");
			?>
			<li id="<?=$tab['page_id']?>">
				<a href="<?=(isset($edit)) ? "#tab_".$tab['page_id'] : $url?>">
					<?=$tabTitle?><span>&nbsp;</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if (isset($edit)): ?>
		<?php foreach($tabs as $tab): ?>
			<div id="<?="tab_".$tab['page_id']?>">
				<?=anchor("edit/".$tab['page_id'], "Edit - ".$tab['title'])."<br /><br />"?>
			</div>
		<?php endforeach ?>
	<?php endif; ?>
</div>
