<?php
/**
 * Created by: cravelo
 * Date: 8/29/11
 * Time: 3:44 PM
 */
?>

<div class="team-tabs">
	<ul>
		<?php if (isset($tabs)) foreach($tabs as $tab): ?>
			<?php
				$tabTitle = htmlentities($tab['title'], 2, 'UTF-8');
				$url = site_url("articles/".$tab['page_id']."/tab/true");
			?>
			<li id="<?php echo $tab['page_id']; ?>">
				<a href="<?php echo (isset($edit) and ($edit == 'edit')) ? "#tab_".$tab['page_id'] : $url; ?>">
					<?php echo $tabTitle; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if (isset($edit) and ($edit == 'edit')): ?>
		<?php if (isset($tabs)) foreach($tabs as $tab): ?>
			<div id="<?php echo "tab_".$tab['page_id']; ?>">
				<?php echo anchor("edit/".$tab['page_id'], "Edit - ".$tab['title'])."<br /><br />"; ?>
			</div>
		<?php endforeach ?>
	<?php endif; ?>
</div>
