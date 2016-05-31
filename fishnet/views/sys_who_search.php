<?php
/**
 * User: cravelo
 * Date: Jun 11, 2010
 * Time: 12:09:10 PM
 */
?>

<link type="text/css" rel="stylesheet" href="<?=STATIC_URL?>css/templates/sys_who.css?<?=FISHNET_VERSION?>" />

<section class="primary">
	<div class="header-a">
		<p><?=anchor("/who", "&#x25c4; Who's Who")?> | Employee Directory</p>
		<h2><?=anchor("/who", "Who's Who")?></h2>
	</div>

    <div class="search-directory">
        <form action="" method="post" class="search">
            <p class="search-field">
                <input type="hidden" id="whoUserName" />
                <label for="whoSearchQuery">Search the Employee Directory</label>
                <input type="search" id="whoSearchQuery" name="whoSearchQuery" autofocus="autofocus"
                       placeholder="e.g. kathy or smith" />
                <button type="submit" id="whoSubmit">Go</button>
            </p>
        </form>
    </div>

	<div class="section-a">
		<h2 class="c">
			<?=count($revision['results'])?> results for <em>"<?=$revision['query']['q']?>"</em>
		</h2>
		<ul class="columns quadruple-a search-results-a">
			<?php if (isset($revision['results']) and is_array($revision['results']) and (count($revision['results']) > 0)): ?>
				<?php foreach($revision['results'] as $user): ?>
					<?php
						$display_name = (isset($user['display_name'])) ? $user['display_name'] : "";
						$username = strtolower((isset($user['username']) and !empty($user['username'])) ?
								$user['username'] : "default");
					?>
					<li class="col vcard">
						<p class="image">
							<a href="<?=site_url("/profiles/".(isset($user['user_id']) ? $user['user_id'] : $username))?>">
								<?php $user_picture = site_url("images/profile/$username/w/187/zc/210"); ?>
								<img src="<?=$user_picture?>" alt="<?=$display_name?>" class="photo" />
							</a>
						</p>
						<div>
							<h2 class="b fn">
								<?=anchor("/profiles/".(isset($user['user_id']) ? $user['user_id'] : $username), $display_name)?>
							</h2>
							<p class="title"><?=val($user['title'], "No title listed")?></p>
							<p>
								<?php
									if (isset($user['department']) and isset($user['department_id'])){
										echo anchor("who/department/".$user['department_id'], $user['department']);

										if (isset($user['parent_dep'])){
											echo " >> ";
											echo anchor("who/department/".$user['parent_dep_id'], $user['parent_dep']);
										}
									}
								?>
							</p>
							<!--?php if (isset($user['status']) and !empty($user['status'])): ?>
								<p class="status ui-state-active"><span
									class="ui-icon ui-icon-info"></span--><!--?=
									$user['status']
								?></p-->
							<!--?php endif; ?-->
						</div>
						<div class="tooltip-contact">
							<div class="inner">
								<h3 class="d">Phone</h3>
								<p class="tel">
									<?=(isset($user['phonenumber']) and ($user['phonenumber'] != '')) ? $user['phonenumber'] : "No phone number listed"?>
								</p>
								<h3 class="d">Fax</h3>
								<p class="fax">
									<?=(isset($user['fax']) and ($user['fax'] != '')) ? $user['fax'] : "No fax number listed"?>
								</p>
								<p class="mail">
									<?php $email = isset($user['email']) ? str_replace("@", "/at/", $user['email']) : "No email address listed"; ?>
									<a href="mailto:<?=$email; ?>" title="<?php echo $email?>">&nbsp;</a>
								</p>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			<?php else: ?>
				There are no results for this criteria.
			<?php endif; ?>
		</ul>
	</div> <!--/ .section-a -->
</section> <!--/ #primary -->

<?php $this->load->view('page_parts/who_rightcol');
