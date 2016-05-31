<?php
/**
 * Created by: cravelo
 * Date: 4/6/12
 * Time: 3:07 PM
 */
?>

<?php if(isset($canPerm) and $canPerm): ?>
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
						<th>Edit</th>
						<th>Delete</th>
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
									'checked' => (boolean)((int)$perm['access'] & PERM_READ),
									'data-perm' => PERM_READ
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_WRITE),
								    'data-perm' => PERM_WRITE
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
			                        'class' => "perm-checkbox",
			                        'checked' => (boolean)((int)$perm['access'] & PERM_DELETE),
			                        'data-perm' => PERM_DELETE
			                    ));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_PUBLISH),
									'data-perm' => PERM_PUBLISH
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_PERM),
									'data-perm' => PERM_PERM
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox",
									'checked' => (boolean)((int)$perm['access'] & PERM_PROPERTIES),
									'data-perm' => PERM_PROPERTIES
								));
							?></td>
							<td><?php
								echo form_checkbox(array(
									'class' => "perm-checkbox perm-checkbox-all",
									'checked' => (boolean)((int)$perm['access'] == PERM_ALL),
									'data-perm' => PERM_ALL
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
		</form>
	</div>
<?php endif; ?>
