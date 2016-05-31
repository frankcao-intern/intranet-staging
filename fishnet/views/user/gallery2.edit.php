<p class="back-a"><?php echo anchor("/articles/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<h3 class="c">Teaser:</h3>
	<div class="edit-textarea" data-key="teaser"><?=//dont indent
		(isset($revision['revision_text']["teaser"]) and ($revision['revision_text']["teaser"] == "")) ?
				"Enter the teaser here" :
				$revision['revision_text']['teaser'];
	?></div>

	<h3 class="c"><label for="editAuthor">Author:</label></h3>
<input type="text" id="editAuthor" size="30" value="<?=
		isset($revision['revision_text']["author_name"]) ?	$revision['revision_text']["author_name"] : ''
	?>" />
</div>
