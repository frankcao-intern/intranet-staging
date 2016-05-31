<?php define('EDIT_MODE', true); ?>

<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/edit_mode.css">

<img src="<?php echo STATIC_URL; ?>images/loading.gif" class="loading">

<div id="mgrGalleryDiag" title="Manage Gallery" class="photo-editor"  style="display: none;">
	<div class="intro">
		<p>This dialog lets you manage your photos. From here you can change the order by dragging and dropping images, rotate and crop images, delete images, and assign/edit titles.</p>
		<p>Note: The first imag in the order will be the main article image. </p>
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
	<input id="imgDate" type="text" size="50" readonly="readonly" />
	<label for="imgDesc">Description: </label>
	<textarea id="imgDesc" rows="4" cols="43"></textarea>
</div>

<script defer src="<?php echo STATIC_URL; ?>js/lib/tinymce/plugins/ibrowser/interface/common.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/lib/tinymce/jquery.tinymce.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/lib/jquery.myeditable.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/lib/fileuploader.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/lib/jquery.jcrop.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/lib/json2.js"></script>
<script defer src="<?php echo STATIC_URL; ?>js/edit_mode.js"></script>
