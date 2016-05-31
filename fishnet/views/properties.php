<?php
/**
 * User: cravelo
 * Date: Jul 27, 2010
 * Time: 10:49:52 AM
 * this are the page properties.
 */

$page_type = val($page_type, 'page');
$title = character_limiter(val($title, "Untitled $page_type"), 35, '...');
?>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/article/$page_id", "&#x25c4; Go back to the $page_type")?></p>
		<h2><?=ucfirst($page_type)?> Settings - <?=$title?></h2>
	</div>

	<?php $this->load->view('page_parts/properties_'.$page_type); ?>

	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/article/$page_id", "&#x25c4; Go back to the $page_type")?></p>
		<h2><?=ucfirst($page_type)?> Settings - <?=$title?></h2>
	</div>
</section> <!--/ #primary -->
