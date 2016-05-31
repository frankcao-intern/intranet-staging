<?php
/**
 * Created by: cravelo
 * Date: 8/11/11
 * Time: 2:43 PM
 */
?>

<div id="sharePageDiag" style="display: none;" title="Share this page">
	<label for="shareEmail">Start typing a name then select from the list:</label>
	<input id="shareEmail" type="text" />
	<ul></ul>
	<label for="shareMsg">Message:</label>
	<textarea id="shareMsg" rows="5" cols="32"></textarea>
</div>

<aside class="secondary article-sidebar">
	<div class="buttonset">
		<a id="btnSave">Save to My Links
		</a><a id="btnShare">Share
		</a><a id="btnPrint">Print
		</a>
	</div>
	<?php if (count($tags) > 0): ?>
		<div class="section-a">
			<h2 class="c">Tags</h2>
			<ul class="list-c">
				<?php foreach($tags as $tag): ?>
					<li><a href="<?php echo site_url("articles/tag/".$tag); ?>"><?php echo $tag; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php if ((isset($revision['revision_text']['related_info']) and
		($revision['revision_text']['related_info'] !== "")) or (isset($edit) and ($edit == 'edit') ) ): ?>
		<div class="section-a">
			<h2 class="c">Related Links + Info</h2>
			<div class="edit-wysiwygadv" data-key="related_info">
				<?=$revision['revision_text']['related_info']; ?>
			</div>
		</div>
	<?php endif; ?>
</aside> <!--/ #secondary -->
