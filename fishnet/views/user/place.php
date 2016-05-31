
<div class="primary place">
	<?php if (!isset($tab)): ?>
		<div class="header-a">
			<p><?=isset($breadcrumbs) ? $breadcrumbs['url'] : "&nbsp;"?></p>
			<h2><?=val($back_link, '&nbsp;')?></h2>
		</div>
	<?php endif; ?>

	<?php $this->load->view('page_parts/image_stack',
		array(
			 'width' => 380,
			 'height' => 236,
			 'picure_info' => true
		)
	); ?>
	<div class="place-info">
		<h2 class="a edit-page-property" data-key="title"><?=(($title == "") and $edit) ? "no title" : $title; ?></h2>
		<p class="address">
			<strong>Address: </strong><span class="edit-textarea" data-key="address"><?php
				echo ($revision['revision_text']['address'] == "") ?
						"Address" :
						$revision['revision_text']['address'];
			?></span>
		</p>
		<p class="phone">
			<strong>Phone: </strong><span class="edit-textinline" data-key="phone"><?php
				echo ($revision['revision_text']['phone'] == "") ?
						"Phone" :
						$revision['revision_text']['phone'];
			?></span>
		</p>
		<p class="fax">
			<strong>Fax: </strong><span class="edit-textinline" data-key="fax"><?php
				echo ($revision['revision_text']['fax'] == "") ?
						"Phone" :
						$revision['revision_text']['fax'];
			?></span>
		</p>
		<?php if (isset($edit)): ?>
			<p>
			<strong>Contact Person: </strong>
			<span class="edit-textinline" data-key="contact_name"><?php
				echo ($revision['revision_text']['contact_name'] == "") ?
						"Contact Name" :
						$revision['revision_text']['contact_name'];
			?></span>
			<span class="edit-textinline" data-key="contact_email"><?php
				echo ($revision['revision_text']['contact_email'] == "") ?
						"Contact Email" :
						$revision['revision_text']['contact_email'];
			?></span>
			</p>
		<?php else: ?>
			<p class="contact">
				<strong>Contact Person: </strong>
				<?=safe_mailto($revision['revision_text']['contact_email'],
					$revision['revision_text']['contact_name'])?>
			</p>
		<?php endif; ?>
		<p class="info">
			<strong>Additional Information: </strong>
			<span class="edit-textarea" data-key="info"><?php
				echo ($revision['revision_text']['info'] == "") ?
						"Information" :
						$revision['revision_text']['info'];
			?></span>
		</p>
		<p class="links">
			<?php if (isset($edit)): ?>
				<strong>Place URL: </strong>
				<span class="edit-textinline" data-key="place_url"><?php
					echo ($revision['revision_text']['place_url'] == "") ?
						"website" :
						$revision['revision_text']['place_url'];
				?></span>
			<?php else: ?>
				<?=anchor($revision['revision_text']['place_url'], "Website", 'target="_blank" class="place-url"')?>
			<?php endif; ?>
			<?php if (isset($edit)): ?>
				<strong>Map URL: </strong>
				<span class="edit-textinline" data-key="map_url"><?php
					echo ($revision['revision_text']['map_url'] == "") ?
						"Map URL" :
						$revision['revision_text']['map_url'];
				?></span>
			<?php else: ?>
				<?=anchor($revision['revision_text']['map_url'], "Map &amp; Directions", 'target="_blank" class="map-url"')?>
			<?php endif; ?>
			<?php if (isset($edit)): ?>
				<strong>Booking URL: </strong>
				<span class="edit-textinline" data-key="book_url"><?php
					echo ($revision['revision_text']['book_url'] == "") ?
						"Booking URL" :
						$revision['revision_text']['book_url'];
				?></span>
			<?php else: ?>
				<?=anchor($revision['revision_text']['book_url'], "Book Now", 'target="_blank" class="book-url"')?>
			<?php endif; ?>
		</p>
	</div>
</div>

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
