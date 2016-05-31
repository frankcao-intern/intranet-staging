<p class="back-a"><?=anchor("/article/$page_id", "&#x25c4; Preview Page")?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?=//dont indent
		(isset($revision['revision_text']["teaser"]) and ($revision['revision_text']["teaser"] == "")) ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>

	<h3 class="c">Number of Columns:</h3>
	<div class="edit-textinline" data-key="columns"><?php //dont indent
		if (isset($revision['revision_text']["columns"])){
			echo ($revision['revision_text']["columns"] == "") ? "1" : $revision['revision_text']["columns"];
		}else{ echo "1"; }
	?></div>
</div>
