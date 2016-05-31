<head>
<style type="text/css" media="screen">
	.player .controls .play{
		height: 46px;
		width: 46px;
		/*display: none;*/
		background-image: url('http://irvcravelo.eileenfisher.net/assets/images/hp_buttons/play.png');}

</style>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:400,300' rel='stylesheet' type='text/css'>
<!--link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/scrollstyles.css" media="all" /-->
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/hp_news_withpic.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/hp_news_withpic_IE9.css" media="all" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--script type="text/javascript" src="<?=STATIC_URL?>js/lib/sidescroll.js"></script-->
<script type="text/javascript" src="<?=STATIC_URL?>js/shared/hp_news_withpic.js"></script>

<!--script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://freestyleacademy.rocks/jquery/easing.js"></script>
<script type="text/javascript" src="http://freestyleacademy.rocks/jquery/easing.compatibility.js"></script>
<script type="text/javascript" src="http://freestyleacademy.rocks/jquery/css-transform.js"></script>
<script type="text/javascript" src="http://freestyleacademy.rocks/jquery/animate-css-rotate-scale.js"></script>
<script type="text/javascript" src="http://freestyleacademy.rocks/Scripts/videoDisplay.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.freestyleacademy.rocks/styles/videoDisplay853x480.css">
<style type="text/css">
#overlayBox{
	width:0%;
	opacity: 0;
}	
#displayBox{
	width:0px;
	height: 0px;
}
#displayBoxContentHolder{
	width:494px;
	height: 275px;
	position: absolute;
	left: -508px;
	bottom:-71px;
}

</style-->
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo site_url("/news/rss") ?>"
      xmlns="http://www.w3.org/1999/html"/>
</head>

<section class="primary">
	<br>

	<?php $this->load->view('page_parts/hp_news_withpic', array('articles' => $news)); ?>
	
	<!--a href="#" id="showYouTubeVideoLink">Video</a-->
	<div id="touchpoints">

		<!-- Purpose & Strategy Bucket on the homepage -->
		<a href='/article/5657' id="ps"><!--img id='cp'--></a>
		
		<!-- The VISION2020 Bucket on the homepage -->
		<a href='/departments/4711' id="vision2020"><!--img id='vision2020'--></a>
		
		<!-- The Opportunity Tank Bucket on the homepage -->
		<a href='/article/3484' id="optank"><!--img id='optank'--></a>
		
		<!-- The Our Company Bucket on the homepage -->
		<a href='/article/5901' id="bcorp"><!--img id='ourcompany'--></a>

		<!-- The EF Engage Bucket on the homepage -->
		<!--img src="/assets/images/hp_buttons/button-1.jpg"-->
		<a href='http://eileenfisherlearninglab.com' id='learninglab'><!--img id='ourbrand'--></a>
		


       </div>
		
	<br style="line-height: 25px;">
		
	<div> <!--class="columns triple-a titled-columns"-->
	

		<?php $articles = array(
			//isset($btl) ? $btl : null,
			isset($video) ? $video : null,
			//isset($journal) ? $journal : null
		); ?>
		
		<?php foreach ($articles as $idx => $article): ?>
			
				<?php if (isset($article[0])): ?>
					<?php $article = $article[0]; //there's a limit of 1 so there is always 1 item in this array ?>
					<?php $articleRev = $article['revision_text']; ?>
					
					<!--?php
							if (!isset($featured[$j])){ continue; }
							$revision = $featured[$j]['revision_text'];
							$filename = preg_replace("/[^\d]+/", '', $revision->videoURL);
							$videoLink = site_url("video/".$featured[$j]['page_id']);
							$src = val($revision->main_image[0]->src, 'error');
							$flip = val($revision->main_image[0]->flip, false);
							$flip = (($flip === true) or ($flip === "true"));
							$angle = (int)val($revision->main_image[0]->angle, 0);
							$img = get_image_html($src, 139, 86, $flip, $angle);
							$alt = htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false);
						?-->
					
					<?php
											
						$src = val($articleRev->main_image[0]->src, 'error');
						$flip = val($articleRev->main_image[0]->flip, false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($articleRev->main_image[0]->angle, 0);
						$img = get_image_html($src, 759, 427, $flip, $angle) ;
						$teaser = val($article['revision_text']->teaser);
						$video = preg_replace("/[^\d]+/",'', val($article['revision_text']->videoURL));
						$protocol = $this->config->item('protocol');
						//$videoTitle = "EF VIDEOS: ";
					?>
						<div id="efvideos" style="border-bottom: 1px solid #b2b2b2;width: 759px; line-height: 1.0em;">							
							<h2 class="c" style="margin-top: 7px; margin-bottom: 1px; color: #86827E; text-transform: none;">
								<!--?=anchor("article/".$article['page_id'], "EF VIDEOS: ")?-->
								<?=anchor("article/".$article['page_id'], 
								htmlentities("EF VIDEOS: ".$article['title'], ENT_COMPAT, 'UTF-8', false))?>
							</h2>	
						</div>
						
						<h5 class="more-c" style="font-size: 1.1em; text-align: left; margin-top: 5px;">
							<?=anchor("video", "WATCH MORE VIDEOS")?>
						</h5>
						
						
						<!--img <?=$img?>alt="<?=htmlentities($articleRev->main_image[0]->alt, ENT_COMPAT, 'UTF-8', false)?>"/-->
								
									
						<iframe
							src="<?=$protocol?>://player.vimeo.com/video/<?=$video?>?api=1&player_id=vimeo-player-1"
							id="vimeo-player-1" width="759" height="427" frameborder="0"
							data-progress="true" data-bounce = "true" date-seek = "true"
							webkitAllowFullScreen mozallowfullscreen allowFullScreen>
									alt="<?=htmlentities($articleRev->main_image[0]->alt, ENT_COMPAT, 'UTF-8', false)?>
						</iframe>
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
						<script src="<?=STATIC_URL?>js/lib/vimeo.ga.min.js" type="text/javascript"></script>
						
			
				<?php endif; ?>
			
		<?php endforeach; ?>
	</div> <!--/ section-a EFV -->

	<br style="line-height: 20px;">

	<div class="columns double-a titled-columns">
		<div class="col" style="margin-left: 0px;">
			<h2 class="b" style="background-color: #575757; color: white; padding-left: 8px; padding-top: 4px;">Bulletin Board Latest Posts</h2>
			<div id="bulletin">
			<ul>
				<?php $people = $revision['revision_text']['people']; ?>
				<?php foreach($topics as $t): //var_dump($t); ?>
					<li>
						<div style="width: 70px; padding: 0px;" >
							<img src="<?php	echo $people[$t['topic_first_poster_name']]['user_picture'].'/w/70/zc/70'; ?>"
								 alt="<?php echo $people[$t['topic_first_poster_name']]['display_name'] ?>" height="70" />
						</div>		 
						<div style="padding: 5px 0 0 15px; ">
							<h3 class="d" style="margin-bottom: 0px;"><a href="<?php echo site_url('bulletinboard/viewtopic.php?f=' . $t['forum_id'] . '&amp;t=' . $t['topic_id'] . '&amp;p=' . $t['topic_last_post_id'] . '#p' . $t['topic_last_post_id']);?>" style="color: #464646; font-size: 1.1em;" ><?php echo html_entity_decode($t['topic_title']);?></a></h3>
							<p style="margin-top: 2px;">By: <?=anchor('profiles/'.$people[$t['topic_first_poster_name']]['user_id'], $people[$t['topic_first_poster_name']]['display_name'])?></p>
							<p style="margin-top: 2px;">Date Posted: <?=date('M d, Y', $t['topic_time'])?></p>
							<p style="margin-top: 2px;">Replies: <?php echo $t['topic_replies'] ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<span class="more-b" style="font-size: 1.1em;"><a href="bulletinboard">READ MORE POSTS</a></span>
			</div>
			
			<!--?=anchor("bulletinboard", "READ MORE POSTS", 'class="more-b"')?-->
		</div>
		
	
	
	
		<!-- old anniversaries section -->
		<div class="col" style="margin-left: 1px;">
			<h2 class="b" style="background-color: #575757; color: white; padding-left: 8px; padding-top: 4px;" >This Week's Anniversaries</h2>
			<!--span class="more-b">CLICK FOR MORE</span-->
			<div id="anivWrap">
				<div class="scroll-pane">
					<ul>
						<?php //print_r($anniversaries); ?>
						<?php for($i = 0; $i < count($anniversaries); $i++) :?>
							<?php
								//user profile picture
								$user_name = $anniversaries[$i]['username'];
								$user_name = strtolower((isset($user_name) and !empty($user_name)) ?
										$user_name : "default");
								$user_picture = site_url("images/profile/$user_name/w/70/zc/70");
								$years = $anniversaries[$i]['years_served'];
							?>
							<li>
								<div style="width: 70px; padding: 0px;"><!--background-color:#e2e1d1;" -->
									<a href="<?=site_url("profiles/".$anniversaries[$i]['user_id']); ?>" title="<?php echo $anniversaries[$i]['display_name']?>">
										<img src="<?=$user_picture; ?>" alt="<?php echo $anniversaries[$i]['display_name']?>" height="70"/>
									</a>
								</div>	
								
								<div>
									<h3 class="d" style="margin-bottom: 0px;"><a href="<?=site_url("profiles/".$anniversaries[$i]['user_id']); ?>" title="<?php echo $anniversaries[$i]['display_name']; ?>" style="color: #464646; font-size: 1.1em;"><?php echo $anniversaries[$i]['display_name']?></a></h3>
									<p style="margin-top: 2px;">Celebrating <?=$years." ".(($years == 1) ? "Year" : "Years")?></p>
									<p style="margin-top: 2px;">On <?=date("F d", strtotime($anniversaries[$i]['start_date']))?></p>
								</div>
							</li>
						<?php endfor; ?>
					</ul>
				</div>
				
			</div>
			<span class="more-b" style="font-size: 1.1em;">
				<!--a href="#" style="padding-left: 353px;">&#9660;</a-->
				
				<!--a href="#">SCROLL DOWN TO SEE MORE&nbsp;&nbsp;&nbsp;&#9660;</a-->
				<a href="#" style="padding-right: 2px;">SCROLL DOWN TO SEE MORE &nbsp;&#x25bc;</a>
			</span>
		</div>
	
		
		<!-- scrolling functionality similar to the tipster on ecommerce-->
		<!--div class="col"-->

	</div>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/hp_rightcol_NEW'); ?>
<!--div id="overlayBox"></div>
<div id="displayBox">
	<div id="closeX">X</div>
	<div id="displayBoxContentHolder">
		<iframe
			src="https://player.vimeo.com/video/70345696"
			width="494" height="275" frameborder="0" 
			webkitAllowFullScreen mozallowfullscreen allowFullScreen>
				alt="<?=htmlentities($articleRev->main_image[0]->alt, ENT_COMPAT, 'UTF-8', false)?>
		</iframe>
	
	</div>
</div-->
<!--script>
    function reloadIt() {
		if (window.location.href.substr(-2) !== "?r") {
			    window.location = window.location.href + "?r";
		}
    }
    
    setTimeout('reloadIt()',2000)();
</script-->


	
<?php
	$assets = $this->load->get_var('assets');
	$assets[] = 'jquery.cycle.all';
	$this->load->vars(array('assets' => $assets));

	if(ENVIRONMENT == 'production'): ?>
		<!-- CLICK HEAT -->
		<script type="text/javascript" src="<?=base_url()?>clickheat/js/clickheat.js"></script>
		<script type="text/javascript">
			var clickHeatSite = 'intranet',
				clickHeatGroup = 'portal',
				clickHeatServer = '<?=base_url()?>clickheat/click.php';
			initClickHeat();
		</script>
<?php endif; ?>

