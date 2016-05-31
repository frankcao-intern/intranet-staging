<?php
/**
 * Created By: cravelo
 * Date: Jan 4, 2011
 * Time: 3:47:05 PM
 */

$this->load->helper('form');
 ?>
<section class="primary">
	<?=form_open("/server/suggest_store", 'id="specialtySuggest"')?>
		<div class="field-group-a">
			<h2 class="c">Suggest a store</h2>

			<p class="field">
				<label for="from">Your name *:</label>
				<input type="text" name="from" id="from" value="<?=$this->session->userdata("display_name")?>" />
			</p>
			<p class="field">
				<label for="email">Your email *:</label>
				<input type="text" name="email" id="email" value="<?=$this->session->userdata("email")?>" />
			</p>
			<div class="field field-2">
				<p class="col">
					<label for="name">Store name *:</label>
					<input type="text" id="name" name="name" value="<?=set_value('name')?>"
					       class="<?=(form_error('name') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('name')?>" />

				</p>
				<p class="col">
					<label for="website">Website (optional):</label>
					<input type="text" id="website" name="website" value="<?=set_value('website')?>"
					       class="<?=(form_error('website') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('website')?>" />
				</p>
			</div>

			<div class="field field-2">
				<p class="col">
					<label for="contact_name">Owner's name (optional):</label>
					<input type="text" id="contact_name" name="contact_name" value="<?=set_value('contact_name')?>"
					       class="<?=(form_error('contact_name') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('contact_name')?>" />
				</p>
				<p class="col">
					<label for="email">Email address (optional):</label>
					<input type="text" id="email" name="email" value="<?=set_value('email')?>"
					       class="<?=(form_error('email') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('email')?>" />
				</p>
			</div>
			<div class="field field-2">
				<p class="col">
					<label for="phone">Phone *:</label>
					<input type="text" id="phone" name="phone" value="<?=set_value('phone')?>"
					       class="<?=(form_error('phone') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('phone')?>" />
				</p>
				<p class="col">
					<label for="fax">Fax (optional):</label>
					<input type="text" id="fax" name="fax" value="<?=set_value('fax')?>"
					       class="<?=(form_error('fax') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('fax')?>" />
				</p>
			</div>
			<p class="field">
				<label for="street_address1">Street Address 1 *:</label>
				<input type="text" id="street_address1" name="street_address1" value="<?=set_value('street_address1')?>"
				       class="<?=(form_error('street_address1') === '') ? '' : 'ui-state-error'?>"
				       title="<?=form_error('street_address1')?>" />
			</p>
			<p class="field">
				<label for="street_address2">Street Address 2:</label>
				<input type="text" id="street_address2" name="street_address2" value="<?=set_value('street_address2')?>"
				       class="<?=(form_error('street_address2') === '') ? '' : 'ui-state-error'?>"
				       title="<?=form_error('street_address2')?>" />
			</p>
			<div class="field field-3">
				<p class="col">
					<label for="city">City *:</label>
					<input type="text" id="city" name="city" value="<?=set_value('city')?>"
					       class="<?=(form_error('city') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('city')?>" />
				</p>
				<p class="col">
					<label for="state">State *:</label>
					<input type="text" id="state" name="state" value="<?=set_value('state')?>"
					       class="<?=(form_error('state') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('state')?>" />
				</p>
				<p class="col">
					<label for="zip">Zip *:</label>
					<input type="text" id="zip" name="zip" value="<?=set_value('zip')?>"
					       class="<?=(form_error('zip') === '') ? '' : 'ui-state-error'?>"
					       title="<?=form_error('zip')?>" />
				</p>
			</div>

			<p class="field">
				<label for="brands">
					Brands they carry *:
				</label>
				<textarea id="brands" name="brands" rows="8" cols="90"
				          class="<?=(form_error('brands') === '') ? '' : 'ui-state-error'?>"
				          title="<?=form_error('brands')?>"><?=
					set_value('brands');
				?></textarea>
			</p>

			<p class="field">
				<label for="message">
					Tell us about the store *:
				</label>
				<textarea id="message" name="message" rows="8" cols="90"
				          class="<?=(form_error('message') === '') ? '' : 'ui-state-error'?>"
				          title="<?=form_error('message')?>"><?=
					set_value('message');
				?></textarea>
			</p>
		</div>
		<div class="field-group-a">
			<p class="submit">
				<button id="submit" type="submit">Submit</button>
			</p>
		</div>
	<?=form_close()?>
</section>
