<?php
/**
 * Created by: cravelo
 * Date: 3/1/12
 * Time: 1:01 PM
 */

$articles = val($articles, array());
?>
<div class="columns double-f rotator-a">
	<div class="items">
		<!--div class="columns double-b"-->
		<ul>
			<!--div class="columns double-b"-->
			<?php for($i = 0; $i < count($articles); $i += 1): ?>
				
				<li>
					<?php for($j = $i; $j < ($i + 5); $j++): ?>
					
						<?php if (!isset($articles[$j])){ break; } ?>
						<?php $main_image = val($articles[$j]['revision_text']->main_image, array()); ?>
						
						<div class="item">
							<p class="thumb">
								<a href="<?=site_url("article/".$articles[$j]['page_id'])?>">
									<?php
									$src = val($main_image[0]->src, 'error');
									$flip = val($main_image[0]->flip, false);
									$flip = (($flip === true) or ($flip === "true"));
									$angle = (int)val($main_image[0]->angle, 0);
									$img = get_image_html($src, 491, 275, $flip, $angle);
									$thumb = get_image_html($src, 82, 44, $flip, $angle);
									$alt = ucfirst(val($main_image[0]->alt));
									$teaser = val($articles[$j]['revision_text']->teaser);
									$article = val($articles[$j]['revision_text']->article);
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
										<?=anchor("article/".$articles[$j]['page_id'],
											/*htmlentities($articles[$j]['revision_text']->article, ENT_COMPAT, 'UTF-8', false))*/
											htmlentities($articles[$j]['title'], ENT_COMPAT, 'UTF-8', false))?>
										<!--p class="more-b">
											<?=anchor('article/'.$articles[$j]['page_id'],
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

