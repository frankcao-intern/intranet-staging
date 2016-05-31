<?php
/**
 * User: cravelo
 * Date: Oct 26, 2010
 * Time: 12:11:14 PM
 */
?>
<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?php echo anchor("/btl", "&#x25c4; Behind The Label"); ?></p>
		<h1>Change Featured Section</h1>
	</div>

	<?php form_open("btl/featuredcategory"); ?>
		<div class="field-group-a">
			<p class="radio">
				<label><input type="radio" name="btl_category" value="6" id="6" />&nbsp;Our Product</label>
			</p>
			<p class="radio">
				<label><input type="radio" name="btl_category" value="7" id="7" />&nbsp;Storytelling</label>
			</p>
			<p class="radio">
				<label><input type="radio" name="btl_category" value="9" id="9" />&nbsp;In the Bag</label>
			</p>
		</div>
		<div class="field-group-a">
			<p class="buttons">
				<button type="submit">Change Featured Category</button>
			</p>
		</div>
	<?php form_close(); ?>
</section>

