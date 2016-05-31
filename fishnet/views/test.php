<?php
/**
 * Created By: cravelo
 * Date: Jan 6, 2011
 * Time: 1:59:24 PM
 */
?>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=11;IE=10;IE=9;IE=8;IE=7;IE=Edge;chrome=1">
	<meta charset="utf-8" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:400,300' rel='stylesheet' type='text/css'>
<!--link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/scrollstyles.css" media="all" /-->
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/hp_news_withpic.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?=STATIC_URL?>css/lib/hp_news_withpic_IE9.css" media="all" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--script type="text/javascript" src="<?=STATIC_URL?>js/lib/sidescroll.js"></script-->
<script type="text/javascript" src="<?=STATIC_URL?>js/shared/hp_news_withpic.js"></script>
</head>
<!--link rel="stylesheet" href="<?=STATIC_URL?>css/templates/includes/sys_team_all.css" /-->
<section class="primary">
        <br>
	<?php $this->load->view('page_parts/hp_news_withpic', array('articles' => $news)); ?>
        
        <div style="width: 760px; padding-top: 35px;">

		
		<!-- The EF Engage Bucket on the homepage -->
		<!--img src="/assets/images/hp_buttons/button-1.jpg"-->
		<a href='http://www.eileenfisher.com/EileenFisher/company/brand/Design.jsp?bmLocale=en_US' id='ourbrand'><!--img id='ourbrand'--></a>
		
		<!--img src='/assets/images/hp_buttons/button-1.jpg' onmouseover="this.src='/assets/images/hp_buttons-1-rollover.jpg'"
				onmouseout="this.src='/assets/images/hp_buttons/button-1.jpg'" height="55px" width="146px" margin-right="4.5px" id='ourbrand'-->
		
		<!--img onmouseover="bigImg(this)" onmouseout="normalImg(this)" border="0" src="smiley.gif" alt="Smiley" width="32" height="32"-->
		
		
		<!-- The VISION2020 Bucket on the homepage -->
		<a href='/departments/4711' id="vision2020"><!--img id='vision2020'--></a>

		<!-- The Company Priorities Bucket on the homepage -->
		<a href='/priorities' id="cp"><!--img id='cp'--></a>

		<!-- The Opportunity Tank Bucket on the homepage -->
		<a href='/article/3484' id="optank"><!--img id='optank'--></a>
		
		<!-- The Our Company Bucket on the homepage -->
		<a href='http://www.eileenfisher.com/EileenFisher/company/brand/mission.jsp?bmLocale=en_US' rel="external" id="ourcompany"><!--img id='ourcompany'--></a>

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
							src="<?=$protocol?>://player.vimeo.com/video/<?=$video?>"
							width="759"	height="427" frameborder="0" id="videos" webkitAllowFullScreen mozallowfullscreen allowFullScreen>

																			alt="<?=htmlentities($articleRev->main_image[0]->alt, ENT_COMPAT, 'UTF-8', false)?>
						</iframe>
						
			
			
				<?php endif; ?>
			
		<?php endforeach; ?>
	</div> <!--/ section-a EFV -->

	<br style="line-height: 20px;">

</section> <!--/ #primary -->

<!--?php $this->load->view('page_parts/team_right');--?>
