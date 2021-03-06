<?php
/**
 * Created by: cravelo
 * Date: 3/1/12
 * Time: 1:01 PM
 */

$articles = val($articles, array());

$arr = array();
foreach($articles as $k => $va){
    if($va['sort_order'] == null )
        $va['sort_order'] = $k;

    $arr[] = $va;
}

// sorting the whole array based on sort_order
usort($arr, 'cmp');


?>
<script type="text/javascript" src="http://freestyleacademy.rocks/Scripts/videoDisplay.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.freestyleacademy.rocks/styles/videoDisplay853x480.css">
<style type="text/css">
#overlayBox{
	width:0%;
	height: 0%;
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

</style>
<div class="columns double-f rotator-a">
	<div class="items">
		<!--div class="columns double-b"-->
		<ul>
			<!--div class="columns double-b"-->
			<?php for($i = 0; $i < count($arr); $i += 1): ?>
				
				<li>
					<?php for($j = $i; $j < ($i + 5); $j++): ?>
					
						<?php if (!isset($arr[$j])){ break; } ?>
						<?php $main_image = val($arr[$j]['revision_text']->main_image, array()); ?>
						
						<div class="item">
							<p class="thumb">
								<a href="<?=site_url("article/".$arr[$j]['page_id'])?>">
									<?php
									$src = val($main_image[0]->src, 'error');
									$flip = val($main_image[0]->flip, false);
									$flip = (($flip === true) or ($flip === "true"));
									$angle = (int)val($main_image[0]->angle, 0);
									$img = get_image_html($src, 491, 275, $flip, $angle);
									$thumb = get_image_html($src, 82, 44, $flip, $angle);
									$alt = ucfirst(val($main_image[0]->alt));
									$teaser = val($arr[$j]['revision_text']->teaser);
									$article = val($arr[$j]['revision_text']->article);
									?>
									<!--div class="overlay">
									<span class="play"-->
									<img <?=$thumb?> alt="<?=$alt?>" style="margin-left:176px; border: 1px solid #eeeeee;"/>
									<!--/span></div-->
									<p class="title" style="width: 156px; max-height: 40px; padding-top: 7px; padding-left: 25px;"><?=htmlentities(ucwords($teaser), ENT_COMPAT, 'UTF-8', false)?></p>
								</a>
							</p>
							<div class="main">
								<p class="image"><img <?=$img?> alt="<?=$alt?>" /></p>
								<div class="caption">
									<h3 class="b">
										<?=anchor("article/".$arr[$j]['page_id'],
											/*htmlentities($articles[$j]['revision_text']->article, ENT_COMPAT, 'UTF-8', false))*/
											htmlentities($arr[$j]['title'], ENT_COMPAT, 'UTF-8', false))?>
										<!--p class="more-b">
											<?=anchor('article/'.$arr[$j]['page_id'],
												"READ ARTICLE")?>
										</p-->
									</h3>
									<p>
										<?=htmlentities($article, ENT_COMPAT, 'UTF-8', false)?>
									</p>

								</div>
							</div>
						</div>
						
					<?php endfor; ?>
				</li>
				
				
				
			<?php endfor; ?>
			<!--/div-->
			</ul>
		<!--/div-->
		<!--/ul-->
	</div> <!--/ .items -->


	
	<div class="rotator-controls">
		
		<!--p class="current current-a"><em class="min"></em> <span>of</span> <em class="max"></em></p-->
		<!--p class="rotator-nav"><button class="prev" style="border: none;">Previous page</button--><!--button class="next">Next page</button--><!--/p-->
		<!--p class="rotator-nav"--><!--button class="next">Next page</button></p-->
		<p class="more-a">
			<?=anchor("/article/".val($articles[0]['section_id'], $page_id), "SEE ALL COMPANY NEWS")?>
		</p>
	</div>
</div> <!--/ .rotator-a -->

<!--a href="#" id="showYouTubeVideoLink">Video</a>

<div id="overlayBox" style="width: 0%; height: 0px;"></div>
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
