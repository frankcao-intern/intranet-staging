<?php define('EDIT_MODE', true); ?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.multiselect.css" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/edit_mode.css">

<img src="<?=STATIC_URL?>images/loading.gif" class="loading">

<div id="mgrGalleryDiag" title="Manage Gallery" class="photo-editor" style="display: none;">
	<div class="intro">
		<strong>HELP</strong>
		<ul class="styled-bulletlist">
			<li>Change the order by dragging the top left corner of each image.
		   	<li>Tools from top to bottom: Rotate, flip, crop, assign/edit titles and revert back to original.
			<li>Delete by clicking on the image to select it (selected = orange border) then click on "Delete".
			<li>Close to cancel your changes (except Delete and Crop).
		</ul>
		<p><strong>Note:</strong> The first image in the order will be the main article image. </p>
	</div>
	<ul id="imageList"></ul>
</div>

<div id="jcropDiag" style="display: none;">
	<input name="x" type="hidden" />
	<input name="y" type="hidden" />
	<input name="w" type="hidden" />
	<input name="h" type="hidden" />
</div>

<div id="captionDiag" style="display: none;" title="Add Caption/Credits">
	<label for="imgUrl">Image URL: </label>
	<input id="imgUrl" type="text" size="50" readonly="readonly" />
	<label for="imgCaption">Caption: </label>
	<input id="imgCaption" type="text" size="50" />
	<label for="imgByLine">Credits: </label>
	<input id="imgByLine" type="text" size="50" />
	<label for="imgDate">Date: </label>
	<input id="imgDate" type="text" size="50" />
	<label for="imgDesc">Description: </label>
	<textarea id="imgDesc" rows="4" cols="43"></textarea>
</div>

<div id="addLinkDiag" title="Add New Link" style="display: none">
	<label for="linkURL">URL (start with http(s)://): </label>
	<input id="linkURL" type="text" size="50" /><br/>
	<label for="linkText">Title: </label>
	<input id="linkText" type="text" size="50" />
</div>
