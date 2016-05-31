<?php
/**
 * Created by: cravelo
 * Date: 8/11/11
 * Time: 2:43 PM
 */

$tags = val($tags, array());
?>

<div id="sharePageDiag" style="display: none;" title="Share this page">
	<label for="shareEmail">Start typing a name then select from the list:</label>
	<input id="shareEmail" type="text" />
	<ul></ul>
	<label for="shareMsg">Message:</label>
	<textarea id="shareMsg" rows="5" cols="28"></textarea>
</div>

<aside class="secondary article-sidebar">
	<div class="buttonset section-a">
		<a id="btnSave">Save
		</a><a id="btnShare">Share
		</a><a id="btnPrint">Print
		</a>
	</div>

	<?php if (count($tags) > 0): ?>
		<div class="section-a">
			<h2 class="c">Tags</h2>
			<ul class="list-c">
				<?php foreach($tags as $tag): ?>
					<li><a href="<?=site_url("article/tag/".$tag); ?>"><?php echo $tag?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if (!empty($revision['revision_text']['related_info']) or isset($edit)): ?>
		<div class="section-a">
			<h2 class="c c-b">Related Links + Info</h2>
			<div class="edit-wysiwygadv related-info" data-key="related_info">
				<?=val($revision['revision_text']['related_info']); ?>
			</div>
		</div>
	<?php endif; ?>
</aside> <!--/ #secondary -->
