<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css">

<?php
/**
 * This is an array of usernames with access to it while on the pilot
 * Once we open it in January you can delete this array and the line below #42 to leave it open to everyone
 */
$pilotgroup = array(
	//fishnet team
	'cravelo',
	'sdurkee',
	'aking',
	'nashton',
	'cmyers',
	'abenjamin',
	//web team
	'hmoustafa',
	'mbarskaia',
	'paulk',
	'mbaresh',
	'billm',
	//PnC
	'gferguson',
	'pamy',
	'acukier',
	'amyh',
	'annp',
	'celestet',
	'cherylc',
	'erivera',
	'hvanier',
	'imendez',
	'kathyd',
	'laurenb',
	'lritter',
	'lvanagas',
	'llee',
	'pamelas',
	'pheilman',
	'reisab',
	'sharis',
	'squinn',
	'susans',
	'tashab',
	'yvettej',
	//accounting
	'brendao',
	'hayleyg',
	'aglazier',
	'agailie',
	'cmalespin',
	'cathyj',
	'esequeira',
	'franm',
	'hpurewal',
	'jderfuss',
	'jlawson',
	'jof',
	'kenp',
	'lidiar',
	'lguillen',
	'pphilip',
	'prakashr',
	'richardl',
	'rogeru',
	'rrammal',
	'tfrancis',
	'tessl',
	'zkutsenko'
	
)

?>

<?php if (!isset($edit)): ?>
	<div class="header-a">
		<p>Employee Directory</p>
		<?php if ($its_me): ?>
			<a class="question-mark" href="<?=site_url("/about/who#missinginfo")?>"
			   title="Click here if there is missing/incorrect information on your profile">
				Click here if there is missing/incorrect information on your profile
			</a>
		<?php endif; ?>
		<h2><?=anchor("/who", "Who's Who")?></h2>
	</div>
	<div id="whoTabs" >
		<ul>
		    <li><a href="#tabs-profile">Profile</a></li>
		    <?php if ($its_me and (array_search($user['username'], $pilotgroup) !== FALSE)):?>
		        <li>
			        <?=anchor(sprintf('addon/attendance/index/%s/%s/tab', date('Y'), $user['user_id']), "Attendance")?>
		        </li>
			<?php endif; ?>
		</ul>
	    <div id="tabs-profile">
		    <section class="primary">
				<div class="columns double-b profile vcard">
			        <div class="primary">
			            <h2 class="a fn" title="<?=val($user['first_name']).' '.val($user['last_name'])?>">
			                <?=val($user['display_name'])?>
			            </h2>
			            <p class="d title"><?=e($user['title'], "No job title listed")?></p>
			            <p class="work-info departments">
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
			            <div class="work-info section-a">
			                <div class="contact-info">
			                    <h3 class="offset">Contact Information</h3>
			                    <p class="tel">
			                        <em><span class="type">Phone</span>: </em>
			                        <span class="value">
			                            <?=e($user['phonenumber'], "No phone number listed")?>
			                        </span>
			                    </p>
				                <?php if (!empty($user['cellphone'])): ?>
				                    <p class="tel">
				                        <em><span class="type">Cell Phone</span>: </em>
				                        <span class="value">
				                            <?=$user['cellphone']?>
				                        </span>
				                    </p>
					            <?php endif; ?>
			                    <p class="tel">
			                        <em><span class="type">Fax</span>:</em>
			                        <span class="value">
			                            <?=e($user['fax'], "No fax number listed")?>
			                        </span>
			                    </p>
			                    <p>
			                        <em>Email: </em>
			                        <?php $email = isset($user['email']) ?
										   str_replace("@", "/at/", $user['email']) :
										   "No email address listed"; ?>
			                        <a href="mailto:<?=$email?>"><?=$email?></a>
			                    </p>
			                    <p>
			                        <em>Desk Location: </em>
									<span class="locality"><?=isset($user['location']) ?
										anchor("/who/people/qkey/location/q/".base64_encode($user['location']),
											$user['location']) :
			                            "No Location Listed"?>
									</span>
			                    </p>
			                    <p>
			                        <em>Preferred Contact Method: </em>
		                            <span><?=e($user['pref_contact_method'])?></span>
			                    </p>
			                    <?php if (isset($user['start_date'])): ?>
			                        <p>
			                            <em>At EF since: </em>
			                            <span><?=date("F d, Y", strtotime($user['start_date']))?></span>
			                        </p>
			                    <?php endif; ?>
			                    <?php if (isset($user['extra_contact_info']) and !empty($user['extra_contact_info'])):?>
			                    <div class="section-a">
				                    <h3 class="offset">Other Contact Information</h3>
			                        <ul class="list-c">
			                            <?php $list = explode("<br>", $user['extra_contact_info']); ?>
		                                <?php foreach($list as $li){ echo "<li>$li</li>"; }?>
			                        </ul>
				                </div>
			                    <?php endif; ?>
			                </div>
			                <h3 class="c">About My Work</h3>
			                <p class="about-my-work"><?=val($user['joboverview'])?></p>
			            </div>

			            <div class="section-a">
			                <h3 class="c">About Me</h3>
			                <div class="edit-profile-wysiwyg article about-me" data-key="biography"><?php
			                    if (isset($user['biography']) and !empty($user['biography'])){
			                        echo $user['biography'];
			                    }else{
			                        echo "<p>You can enter here information about you.</p>";
			                    }
			                ?></div>
			            </div>
			        </div> <!--/ .primary -->

			        <div class="secondary">
			            <p class="image">
			                <a href="<?=val($user_picture).'/'.time()?>" class="fancybox">
			                    <img src="<?=val($user_picture)."/w/239/".time()?>" alt="<?=val($user['display_name'])?>"
			                         class="photo" width="239">
			                </a>
			            </p>
			        </div> <!--/ .secondary -->
			    </div>
			</section><!--/ #primary -->
		    <aside class="secondary">
			    <!--?php if(isset($distros) and (count($distros) != 0)): ?-->
				    <!--div class="section-a">
					    <h2 class="c collapsible">Group Affiliations</h2>
					    <ul class="list-c" id="profileGroups"-->
						    <!--?php foreach($distros as $distro): ?-->
						    <!--li--><!--?=anchor("who/memberof/q/".base64_encode($distro['dn']),
			    $distro['name'])?></li-->
						    <!--?php endforeach; ?>
					    </ul>
					</div-->
			    <!--?php endif; ?-->

			    <?php if(isset($mypages) and(count($mypages) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible">My Pages</h2>
					    <ul class="list-c">
						    <?php foreach($mypages as $page): ?>
						    <li><?=anchor("/article/".$page['page_id'], $page['title'])?></li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>

			    <?php if(isset($my_private) and(count($my_private) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible collapsible-closed">My Private Pages</h2>
					    <ul class="list-c">
						    <?php foreach($my_private as $page): ?>
						    <li><?=anchor("/article/".$page['page_id'], $page['title'])?></li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>
			    
			    
			    
			    <?php if(isset($subscriptions) and(count($subscriptions) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible collapsible-closed">Subscriptions</h2>
					    <ul class="list-c">
						    <?php foreach($subscriptions as $page): ?>
							    <li id="sub<?=$page['page_id']; ?>" data-pid="<?php echo $page['page_id']?>">
								    <?=anchor('article/'.$page['page_id'], $page['title'])?>
								    &nbsp;-&nbsp;
								    <a class="js-page-delete">Delete</a>
							    </li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>

			    <?php if(isset($drafts) and(count($drafts) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible collapsible-closed">My Drafts</h2>
					    <ul class="list-c">
						    <?php foreach($drafts as $page): ?>
							    <li><?=anchor("/article/".$page['page_id'], $page['title'])?></li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>

                <?php if(isset($reviews) ): ?>
                <?php //if(isset($reviews) and(count($reviews) != 0)): ?>
                    <div class="section-a">
                        <h2 class="c collapsible collapsible-closed">Reviews</h2>
                        <ul class="list-c">
                            <?php foreach($reviews as $page): ?>
                                <li><?=anchor("/article/".$page['page_id'], $page['title'])?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(isset($events) and (count($events) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible collapsible-closed">Event Drafts</h2>
					    <ul class="list-c">
						    <?php foreach($events as $event): ?>
							    <li><?=anchor("/event/".$event['event_id'], $event['event_title'])?></li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>
			    
			    <?php if(isset($trashcan) and(count($trashcan) != 0)): ?>
				    <div class="section-a">
					    <h2 class="c collapsible collapsible-closed">Deleted Pages</h2>
					    <ul class="list-c">
						    <?php foreach($trashcan as $page): ?>
							    <li id="trashCan<?=$page['page_id']; ?>" data="<?php echo $page['page_id']?>">
								    <?=$page['title']?>
								    &nbsp;-&nbsp;
								    <a class="js-page-restore">Restore</a>
								    &nbsp;-&nbsp;
								    <a class="js-page-purge">Purge</a>
							    </li>
						    <?php endforeach; ?>
					    </ul>
				    </div>
			    <?php endif; ?>
		    </aside> <!--/ #secondary -->
	    </div>
	</div>
<?php endif; ?>
