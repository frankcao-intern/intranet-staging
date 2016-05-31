<?php
/**
 * User: cravelo
 * Date: Jul 27, 2010
 * Time: 10:49:52 AM
 * this are the page properties.
 */
?>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/json2.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery.autocolumn.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/templates/page_properties.lib.js"></script>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?php echo anchor("/articles/$page_id", "&#x25c4; Go back to the Article"); ?></p>
		<h1 class="a">Article Properties - <?php echo $title; ?></h1>
	</div>

	<!--form action="" method="post" class="properties-a"-->
	<!--h2 class="a">General Settings</h2-->
		<div class="field-group-a relative">
			<h3 class="c">General Settings</h3>
			<ul class="settings-a">
				<li>
					<?php echo form_checkbox(array('name' => "allow_comments", 'class' => 'js-gen-settings', 'checked' => (boolean)($allow_comments))); ?>
					<label for="allow_comments"><strong>Allow New Comments</strong> This allows other users to comment on this page.</label>
				</li>
			</ul>
			<p class="field">
				<label for="expiration_date"><abbr title="Setting an expiration date will delete this page at the end of that day. Deleted pages are purged after 30 days!">Expiration Date: </abbr></label>
				<input type="text" id="expiration_date" name="expiration_date" class="js-gen-settings" value="<?php echo $expiration_date; ?>" />
			</p>
			<div class="field page-property">
				<p class="select-c templates">
					<label for="template_id" style="font-size: 1.1em;"><abbr title="Use this dropdown to change the page's template, please bear in mind that some templates are not compatible with others and your page might get corrupted.">Template: </abbr></label>
					<select name="template_id" id="template_id" class="js-gen-settings">
					<?php
						foreach($templates as $template){
							$selected = ($template['template_id'] == $template_id) ? 'selected="selected"' : '';
							echo '<option value="'.$template['template_id']."\"$selected>".$template['template_title'].'</option>';
						}
					?>
					</select>
				</p>
			</div>
		</div>
		<div class="field-group-a">
			<h3 class="c">Display Control Dates</h3>
			<div class="field">
				<p class="one">
					<label for="date_published"><abbr title='"Date published" designates the specific date on which pages will appear in sliders and lists. You can select a date for future publishing. This also conveys the order in which pages will appear.'>Date Published: </abbr></label>
					<input type="text" id="date_published" name="date_published" class="js-gen-settings" value="<?php echo date("Y-m-d", strtotime($date_published)); ?>" />
				</p>
				<p class="two">
					<label for="show_until"><abbr title='"Show until" selects the last date by which the page will be shown in sliders and lists.'>Show Until: </abbr></label>
					<input type="text" id="show_until" name="show_until" class="js-gen-settings" value="<?php echo $show_until; ?>" />
				</p>
			</div>
		</div>
		<div class="field-group-a">
			<h3 class="c">Publishing</h3>
			<ul class="settings-a">
				<li>
					<?php echo form_checkbox(array('name' => 'published','class' => 'js-gen-settings', 'checked' => (boolean)($published))); ?>
					<label for="published"><strong>Published</strong> When a page is unpublished then only the people with edit rights can view it.</label>
				</li>
			</ul>
			<div class="field">
				<p class="desc">This is a list of sections you have permissions to publish to, this page is published to the ones that are selected below. You can select more than one section.</p>
				<ul id="sections" class="sortable-list">
					<?php $other = array(); ?>
					<?php foreach($sections as $section): ?>
						<?php if ($section['permPublish'] == 0): ?>
							<?php if ($section['selected'] == 1) $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
						<?php else: ?>
							<li id="s<?php echo $section['page_id']; ?>" class="<?php echo ($section['selected'] == 1) ? 'ui-selected' : ''; ?>"><?php echo $section['title']; ?></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php if (count($other) > 0): ?>
					<p class="sections-other">This page is also published to the following sections: </p>
					<ul class="sections-other">
						<?php foreach($other as $section): ?>
							<li id="s<?php echo $section['id']; ?>"><?php echo $section['title']; ?>,&nbsp;</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<h3 class="c">Tags</h3>
			<div class="field">
				<p class="desc">
					Tags allow to group pages together for search and also some templates have special content buckets for a specific tags.
					Tags will auto complete, if you are using special tags always select them form the list (e.g. Featured), otherwise just type the
					keywords associated with this page. You tags will appear on the autocomplete list next time. Special tags are case insensitive. (e.g. Featured = featured)
				</p>
			</div>
			<p class="field">
				<label for="tags">Add Tags: </label>
				<input id="tags" type="text" value="<?php echo $tags; ?>" />
			</p>
		</div>
		<div class="field-group-a">
			<h3 class="c">Featured Story</h3>
			<ul class="settings-a">
				<li>
					<?php echo form_checkbox(array('name' => "featured", 'class' => 'js-gen-settings', 'checked' => (boolean)($featured))); ?>
					<label for="featured"><strong>Featured</strong> Certain sections of the site organize information in featured and non-featured.</label>
				</li>
			</ul>
			<div class="one">
				<p class="field">
					<label for="featured_from"><abbr title='"Featured from" indicates the first date on which a page will be featured.'>Featured From: </abbr></label>
					<input type="text" id="featured_from" name="featured_from" class="js-gen-settings" value="<?php echo $featured_from; ?>" />
				</p>
			</div>
			<div class="two">
				<p class="field">
					<label for="featured_until"><abbr title='"Featured until" indicates the last date on which a page will be featured.'>Featured Until: </abbr></label>
					<input type="text" id="featured_until" name="featured_until" class="js-gen-settings" value="<?php echo $featured_until; ?>" />
				</p>
			</div>
		</div>
		<p><button class="btn-save-prop">Save Properties</button></p>
	<!-- /form -->
	<div class="section-a">
		<form action="" method="post" class="properties-a">
			<div class="header-d">
				<h2 class="c">Permissions</h2>
				<p class="action"><a id="btnAddPerm" class="add-a">Add New Group</a></p>
			</div>

			<table class="b">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Read</th>
						<th>Write</th>
						<th>Delete</th>
						<th>Permissions</th>
						<th>Properties</th>
						<th>All</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="propPermissions">
					<?php //var_dump($permissions); ?>
					<?php foreach($permissions as $perm): ?>
						<tr>
							<th scope="row" id="gid-<?php echo $perm['group_id']; ?>"><?php echo $perm['group_name'] ?></th>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_READ)
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_WRITE)
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_DELETE)
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_PERM)
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_PROPERTIES)
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox perm-checkbox-all",
									'checked' => (boolean)((int)$perm['access'] == PERM_ALL)
								));
							?></td>
							<td class="actions">
								<a href="#" class="perm-btn-delete delete-a">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p><button class="btn-save-prop">Save Properties</button></p>
			<?php if(isset($canPerm) and !$canPerm): ?>
				<script>
					$(document).ready(function(){
						$(".perm-checkbox").attr("disabled", true);
						$("#btnAddPerm, #btnUpdPerm, .perm-btn-delete").remove();
					});
				</script>
			<?php endif; ?>
		</form>
	</div>
	<div class="section-a">
		<h2 class="c">Revisions</h2>
		<table class="b">
			<thead>
				<tr>
					<th>Revision</th>
					<th>Date Created</th>
					<th>Created By</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($all_revs) and $all_revs): ?>
					<?php foreach($all_revs as $rev): ?>
						<tr>
							<td><?php echo $rev['revision_id']; ?></td>
							<td><?php echo $rev['date_created']; ?></td>
							<td><?php echo $rev['username']; ?></td>
							<td class="actions">
								<a class="revs-btn-view edit" title="View this version">View</a>
								<a class="revs-btn-revert move" title="Revert back to this revision">Revert</a>
								<a class="revs-btn-delete delete-b" title="Delete">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="header-a">
		<p><?php echo anchor("/articles/$page_id", "&#x25c4; Go back to the Article"); ?></p>
		<h1 class="a">Article Properties - <?php echo $title; ?></h1>
	</div>
</section> <!--/ #primary -->
