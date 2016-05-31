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
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/templates/page_properties.lib.js"></script>

<section class="primary">
	<div class="header-a header-a-space-bottom-a">
		<p><?php echo anchor("/articles/$page_id", "&#x25c4; Go back to the Calendar"); ?></p>
		<h1 class="a">Calendar Properties - <?php echo $title; ?></h1>
	</div>

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
						<th>Publish</th>
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
							<th scope="row" id="gid-<?php echo $perm['group_id'] ?>"><?php echo $perm['group_name'] ?></th>
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
									'checked' => (boolean)((int)$perm['access'] & PERM_PUBLISH)
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
			<p><button class="btn-save-prop">Save Permissions</button></p>
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
	<div class="header-a">
		<p><?php echo anchor("/articles/$page_id", "&#x25c4; Go back to the Calendar"); ?></p>
		<h1 class="a">Calendar Properties - <?php echo $title; ?></h1>
	</div>
</section> <!--/ #primary -->
