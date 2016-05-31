<p class="back-a"><?php echo anchor("/article/$page_id", "&#x25c4; Preview Page");  ?></p>
<div>
	<!--h3 class="c">Cover Image:</h3>
	<?php $this->load->view('page_parts/image_stack', array('width' => 760, 'height' => 471)); ?>
-->

	<h3 class="c">Page Title:</h3>
	<p class="edit-page-property" data-key="title"><?=empty($title) ? "Untitled Page" : $title?></p>

	<h3 class="c">The ID of the section to link back to. Use 1 for the Home Page:</h3>
	<p class="edit-textinline" data-key="back_link_id"><?=val($revision['revision_text']['back_link_id'], 1)?></p>

	<h3 class="c">Color (Hex Value e.g. #000000):</h3>
	<p class="edit-textinline" data-key="color"><?=val($revision['revision_text']['color'], '#000000')?></p>
</div>
