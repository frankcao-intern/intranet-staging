<?php
/**
 * User: cravelo
 * Date: Jun 11, 2010
 * Time: 5:30:22 PM
 */
?>
<section class="primary">
	<div class="header-a">
		<p><?php echo anchor("/profiles/".$user['user_id'], "&#x25c4; Save");  ?></p>
		<h2>Edit your profile</h2>
	</div>
	<?php if (strtolower($this->session->userdata('role')) == 'admin'): ?>
		<div class="section-a">
			<h3 class="c">Hidden? (if = 1 this user will be hidden from Who's Who)</h3>
			<div class="edit-profile-inline" data-key="hidden"><?php //don't indent or put in a new line
					if (isset($user['hidden'])){
						echo $user['hidden'];
					}else{
						echo "0";
					}
			?></div>
		</div>
	<?php endif; ?>
	<div class="section-a">
		<h3 class="c">About Me</h3>
		<div class="edit-profile-wysiwyg" data-key="biography">
			<?php
				if (isset($user['biography']) AND ($user['biography'] != "")){
					echo $user['biography'];
				}else{
					echo "<p>You can enter here information about you.</p>";
				}
			?>
		</div>
	</div>
	<div class="section-a">
		<h3 class="c">About my Work</h3>
		<p class="edit-profile-textarea" data-key="joboverview"><?php //this code cant have indentations because they show up when you edit the field.
			if ($user['joboverview'] == "")
				echo "Enter here a list of the things you do or the things people may contact you for, as well as any project you lead, etc. ";
			else
				echo $user['joboverview'];
			?></p>
	</div>
	<div class="section-a">
		<h3 class="c">Contact Information</h3>
		<p class="tel">
			<em><span class="type">Cell Phone</span>:</em>
			<span class="edit-profile-inline" data-key="cellphone"><?php //don't indent or put in a new line
				if ($user['cellphone'] == "")
					echo "Entering your cellphone is optional";
				else
					echo $user['cellphone'];
			?></span>
		</p>
		<p>
			<em>Preferred Contact Method:</em>
			<span class="edit-profile-pcm" data-key="pref_contact_method"><?php //don't indent or put in a new line
				echo $user['pref_contact_method'];
			?></span>
		</p>
	</div>
	<div class="section-a">
		<h3 class="c">Other Contact Information</h3>
		<p class="edit-profile-textarea" data-key="extra_contact_info"><?php //don't indent or put in a new line
			if ($user['extra_contact_info'] == "")
				echo "Enter here any other contact information you would like to share.";
			else
				echo $user['extra_contact_info'];
			?></p>
	</div>
	<div class="section-a edit-profile-pic">
		<h3 class="c">Profile Picture</h3>
		<p>Your picture will be saved automatically after you upload it.</p>
		<a href="<?php echo "$user_picture?".time() ?>" class="fancybox">
			<img data-username="<?=strtolower($user['username']); ?>" src="<?php echo "$user_picture?".time() ?>" alt="<?php echo $user['display_name']?>" class="photo">
		</a>
	</div>
	<div class="section-a">
		<h3 class="c">My LINKS</h3>
		<ul class='edit_who_links' id="links">
			<?php $my_links = $this->favorites->get($user['user_id']); ?>
			<?php foreach($my_links as $link): ?>
				<li>
					<h2 class="c"><?=anchor(prep_url($link['url']), $link['title'].' >')?></h2>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="header-a">
		<p><?php echo anchor("/profiles/".$user['user_id'], "&#x25c4; Save");  ?></p>
		<h2>Edit your profile</h2>
	</div>
</section>
