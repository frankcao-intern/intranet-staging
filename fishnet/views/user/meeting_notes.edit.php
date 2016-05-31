<p class="back-a"><?php echo anchor("/articles/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" id="teaser"><?php
		echo ($revision['revision_text']["teaser"] == "") ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>
	
	<label for="editAuthor"><h3 class="c">Author:</h3></label>
	<input type="text" id="editAuthor" size="30" value="<?=
		isset($revision['revision_text']["author_name"]) ?	$revision['revision_text']["author_name"] : ''
	?>" />
</div>
