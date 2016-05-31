<?php
/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 9:41 AM
 */
 ?>

<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>

<h3 class="c">Teaser:</h3>
<div class="edit-textarea" data-key="teaser"><?=//dont indent
	(isset($revision['revision_text']["teaser"]) and !empty($revision['revision_text']["teaser"])) ?
		$revision['revision_text']['teaser'] : "Enter your teaser here.";
?></div>

<h3 class="c">Number of Columns:</h3>
<div class="edit-textinline" data-key="columns"><?= //don't indent
	(isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])) ?
		$revision['revision_text']["columns"] : "1";
?></div>
