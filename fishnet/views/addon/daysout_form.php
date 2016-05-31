<?php
/**
 * Created by: cravelo
 * Date: 2/21/12
 * Time: 2:09 PM
 */
?>

<section class="primary">
	<?php
		echo form_open('addon/daysout/formproc', 'class="days-out"');
		echo form_hidden('user_id', $this->session->userdata('user_id'));
	?>
		<div class="field-group-a">
			<h2 class="c">General information *</h2>
			<div class="field">
				<p class="one">
					<?=form_label('Name: ', 'displayname')?>
					<?=form_input('displayname', $this->session->userdata('displayname'), 'id="displayname"')?>
				</p>
				<p class="two">
					<?=form_label('Date: ', 'date')?>
					<?=form_input('date', date('Y-m-d'), 'id="date" readonly')?>
				</p>
			</div>
			<p class="field">
				<?=form_label('Location: ', 'location')?>
				<?=form_input('location', $this->session->userdata('location'), 'id="location" required')?>
			</p>
			<h3 class="c">This is a:</h3>
			<p class="field">
				<?=form_radio('use_notice', 0, true, 'id="use_notice1" required')?>
				<?=form_label('Request: ', 'use_notice1')?>
			</p>
			<p class="field">
				<?=form_radio('use_notice', 1, false, 'id="use_notice2" required')?>
				<?=form_label('Use Notice: ', 'use_notice2')?>
			</p>
		</div>
		<div class="field-group-a">
			<h2 class="c">Days requested *</h2>
			<?=form_button(null, "Add a request", 'id="addDay"')?>
			<textarea id="dayTempl" style="display: none">
				<tr>
					<td>
						<select name="day_type{{name}}">
							<?php foreach($day_types as $day_type): ?>
								<option value="<?=$day_type['id']?>"><?=$day_type['name']?></option>
							<?php endforeach;?>
						</select>
					</td>
					<td><input type="text" name="date_from{{name}}" value="{{from}}" readonly></td>
					<td><input type="text" name="date_to{{name}}" value="{{to}}" readonly></td>
					<td class="total">
						<span>1</span>
						<input type="hidden" name="total{{name}}" value="1" readonly>
					</td>
					<td class="actions"><a class="delete" href="#">Delete</a></td>
				</tr>
			</textarea>
			<table>
				<thead>
					<tr>
						<th>Type of day
						<th>From
						<th>To
						<th>Total
						<th>Delete
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<td class="text_right" colspan="3">TOTAL:
						<td>0
				</tfoot>
			</table>
			<?=form_hidden('req_count', 0)?>
			<?=form_hidden('total_days', 0)?>
		</div>
		<div class="field-group-a">
			<h2 class="c">Leader *</h2>
			<p><strong>IMPORTANT NOTE:</strong> Select someone from the list:</p>
			<p class="field">
				<?=form_label("Leader's name:", 'mgrName')?>
				<?=form_input('mgr_name', '', 'id="mgrName"')?>
				<?=form_hidden('mgr_id', '')?>
				<?=form_hidden('mgr_email', '')?>
			</p>
		</div>
		<div class="field-group-a">
			<p class="submit"><?=form_submit('submit', 'Submit');?></p>
		</div>
	<?=form_close();?>
</section>
