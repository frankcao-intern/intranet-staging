<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<label for="editAuthor"><h3 class="c">Author:</h3></label>
	<input type="text" id="editAuthor" size="30" value="<?=
		isset($revision['revision_text']["author_name"]) ?	$revision['revision_text']["author_name"] : ''
	?>" />
    <h3 class="c">Page Title:</h3>
    <div class="edit-page-property" data-key="title"><?=isset($title) ? $title : 'no title'?></div>
</div>

