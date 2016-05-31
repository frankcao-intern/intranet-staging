<?php
$results = val($search_results, array());
$results_count = count($results);
$who_search = val($who_search, array());
?>

<section class="primary">
	<div class="header-a">
		<p><?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?></p>
		<h2>Search Results for "<?=val($search)?>"</h2>
	</div>

	<div class="search-results">
		<?php if(count($who_search) > 0): ?>
			<!--Who's Who results-->
			<div class="section-a">
				<h2 class="c">Who's Who</h2>
				<div class="content">
					<ul class="columns quadruple-a search-results-a">
						<?php foreach($who_search as $i => $user): ?>
							<li class="col vcard">
								<?php
								$display_name = (isset($user['display_name'])) ? $user['display_name'] : "";
								$username = strtolower((isset($user['username']) and !empty($user['username'])) ?
									                       $user['username'] : "default");
								$user_picture = site_url("images/profile/$username/w/167/zc/210");
								?>
								<p class="image">
									<a href="<?=site_url("/profiles/".$user['user_id'])?>">
										<img src="<?=$user_picture; ?>" alt="<?php echo $display_name?>" class="photo" />
									</a>
								</p>
								<div>
									<h2 class="b fn">
										<?=anchor("/profiles/".$user['user_id'], $display_name)?>
									</h2>
									<p class="title"><?php if (isset($user['title'])) echo $user['title']; else echo "No title listed" ?></p>
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
								</div>
								<div class="tooltip-contact">
									<div class="inner">
										<h3 class="d">Phone</h3>
										<p class="tel">
											<?php if (isset($user['phonenumber']) and $user['phonenumber']) echo $user['phonenumber']; else echo "No phone number listed" ?>
										</p>
										<h3 class="d">Fax</h3>
										<p class="fax">
											<?php if (isset($user['fax']) and ($user['fax'] != '')) echo $user['fax']; else echo "No fax number listed" ?>
										</p>
										<p class="mail">
											<?php $email = (isset($user['email']) and ($user['email'] != '')) ? str_replace("@", "/at/", $user['email']) : "No email address listed"; ?>
											<a href="mailto:<?=$email; ?>" title="<?php echo $email?>">&nbsp;</a>
										</p>
									</div>
								</div>
							</li>
							<?php if ($i == 3): ?>
	                            <li>
	                                <p class="more-a"><?=anchor("who/people/q/".base64_encode($search), 'View All Results >')?></p>
	                            </li>
							<?php break; endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div> <!--/ .Who's Who -->
		<?php endif; ?>
		<!--Pages results-->
		<div class="section-a">
			<?php if ($results_count > 0): ?>
                <hgroup>
					<h2 class="c">Articles and Pages</h2>
	                <h3 class="d">Showing <?=$per_page?> from a total of <?=$total_rows?> results</h3>
				</hgroup>

				<?php if (isset($this->pagination)): ?>
			        <ul class="digest-nav">
						<?=$this->pagination->create_links()?>
			        </ul>
				<?php endif; ?>

				<div class="article">
	                <ul class="search-results-b">
					<?php for($i = 0; $i < $results_count; $i++): ?>
						<?php $revision = $results[$i]['revision_text']; ?>
						<li class="<?=(($i % 2)==0) ? 'alt' : ''?>">
							<h3 class="b"><?=anchor("article/".$results[$i]['obj_id'], $results[$i]['page_title'])?></h3>
							<p class="date"><?=date("F d, Y",strtotime($results[$i]['page_date_published']))?></p>
							<p><?=(empty($revision->article)) ? "" : $revision->article.'...'?></p>
						</li>
					<?php endfor; ?>
					</ul>
		        </div>

				<?php if (isset($this->pagination)): ?>
					<div class="section-a">
		                <ul class="digest-nav">
							<?=$this->pagination->create_links()?>
		                </ul>
			        </div>
				<?php endif; ?>
			<?php else: ?>
				<div class="article">
					<p>
						<strong>Sorry! </strong>
						Nothing was found using that keyword. Check the spelling or try something different.
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section> <!--/ #primary -->

<aside class="secondary">
	<div class="section-a">
		<h2 class="c">How to search fishNET</h2>
		<ul class="list-a">
            <li>Type in the search box the word, phrase or number you are looking for and press Go or the enter key.</li>
			<li>
				When searching be mindful of "Stop words", these are terms that will not produce search results
				-words like our, I'm, an or that.
			</li>
			<li>
				If you type your phrase in quotes then all words are included and only pages with that exact phrase
				will be returned. For example if you search for -our clothes- add quotes "our clothes".
			</li>
			<li>
				For a complete list of "Stop words" look at Apendix A or the
				<a href="/documents/userguide.pdf">fishNET User Guide</a>.
			</li>
		</ul>
	</div>
</aside> <!--/ #secondary -->
