<?php
/**
 * @filename sys_crud.php
 * @date 06/18/2012
 * @author cravelo
 */

$table_name = val($table_name);
//var_dump($$table_name);
$data = val($$table_name, array());
$fields = val($data[0], array());
$fields = array_keys($fields);
?>

<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/datatables/jquery.dataTables.css?<?=FISHNET_VERSION?>" />
<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/datatables/jquery.dataTables_jqueryui.css?<?=FISHNET_VERSION?>" />

<div class="primary">
	<div class="header-a">
		<p>&nbsp;</p>
		<h2>Editing: <span id="tableName"><?=$table_name?></span></h2>
		<p class="more-a"><button id="addNew">Add new</button><button id="deleteSelected">Delete Selected</button></p>
	</div>

	<table id="dataTable">
		<thead>
			<tr>
				<?php foreach($fields as $field): ?>
				<th scope="column"><?=$field?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $row): ?>
				<tr>
					<?php $len = count($row); ?>
					<?php for($i = 0; $i < $len; $i++): ?>
						<td data-key="<?=$fields[$i]?>"><?=htmlspecialchars(val($row[$fields[$i]]), 2, 'UTF-8')?></td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div id="addNewDiag" title="Add a new record">
		<?=form_open('/crud/insert')?>
		<input type="hidden" name="table_key" value="<?=$fields[0]?>">
		<input type="hidden" name="table_name" value="<?=$table_name?>">
		<div class="field-group-a">
			<script type="application/template" id="formTempl">
				<p class="field">
					<label for="{{fname}}">{{fname}}</label>
					<input type="text" name="{{fname}}" id="{{fname}}" />
				</p>
			</script>
		</div>
		<?=form_close()?>
	</div>
</div>
