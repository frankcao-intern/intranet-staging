<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?=//dont indent
		(isset($revision['revision_text']["teaser"]) and ($revision['revision_text']["teaser"] == "")) ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>
</div>
