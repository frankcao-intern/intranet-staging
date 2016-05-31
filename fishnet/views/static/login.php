<?php
/**
 * Created by: cravelo
 * Date: 7/5/11
 * Time: 10:03 AM
 */

$uri_string = $this->uri->uri_string();
$uri_string = str_replace('login/index/', '', $uri_string);
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
			Please Login
		</h2>
		<?=form_open("login/index/$uri_string")?>
		<p>
		   <?php
			  echo form_label('Username: ', 'username');
			  echo form_input('username', set_value('username'), 'id="username" autofocus');
		   ?>
		</p>

		<p>
		   <?php
			  echo form_label('Password:', 'password');
			  echo form_password('password', '', 'id="password"');
		   ?>
		</p>

		<p>
			<?php
			
				//$link = array(
				//	'type' => 'link',
				//	'content' => 'Forgot Password?',
				//	'href' => 'https://password.eileenfisher.com'
					
				//);
				
				//echo form_button($link);				
				
				$data = array(
					'value' => 'Login',
					'type' => 'submit',
					'content' => 'Login'
				);

				echo form_button($data);
		   ?>
		</p>
		
		<p>
			<?=anchor("Https://password.eileenfisher.com", "Forgot my password?")?>
			
			
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
