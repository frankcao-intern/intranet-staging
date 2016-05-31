<?php
/**
 * Created by: cravelo
 * Date: 7/5/11
 * Time: 10:03 AM
 */
?>

<section class="primary">
	<div class="login-form">
		<h2 class="logo">
			<?php
				$logo = $this->session->userdata('logo');
				if ($logo){
					$logo = STATIC_URL.'images/'.$logo;
				}else{
					$logo = STATIC_URL.'images/logo-gradient.png';
				}
			?>
			<img src="<?=$logo?>" height="64" width="122" alt="FISHNET" />
			Change your Password
		</h2>
		<?=form_open('login/changepassword')?>
		<?=form_hidden('username', isset($username) ? $username : $this->session->userdata('username'))?>
		<p>
			<?=form_label('Current password: ', 'old_password')?>
			<?=form_password('old_password', set_value('old_password'), 'id="old_password" autofocus')?>
		</p>
		<p>
			<?=form_label('New password:', 'password')?>
			<?=form_password('password', '', 'id="password"')?>
		</p>
		<p>
			<?=form_label('Confirm New password:', 'repassword')?>
			<?=form_password('repassword', '', 'id="repassword"')?>
		</p>
		<p>
			<?php
				$data = array(
					'value' => 'Change Password',
					'type' => 'submit',
					'content' => 'Change Password'
				);

				echo form_button($data);

				$data = array(
					'value' => 'Cancel',
					'type' => 'button',
					'content' => 'Cancel',
					'id' => 'btnCancel'
				);

				echo form_button($data);
		   ?>
		</p>
		<?=form_close()?>
	</div>
	<?php if (isset($error) or (validation_errors() !== '')): ?>
		<div class="ui-state-error ui-corner-all">
			<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert">Icon</span>
			<p><?=$error?></p>
			<p><?=validation_errors()?></p>
		</div>
	<?php endif; ?>
</section>
