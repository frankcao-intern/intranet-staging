

<section class="primary">
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="double-e content-a generic-page-d">
		<div class="videos one   image-stack">
			<ul>
				<?php
					$height = 191;
					$width = 309;

					$videoURL1 = $revision['revision_text']["videoURL"];
					$videoURL2 = $revision['revision_text']["videoURL2"];
					$videoURL3 = $revision['revision_text']["videoURL3"];
					$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";
					$filename1 = ($videoURL1 == '') ? false :
							$protocol.STATIC_URL.'videos/'
							.substr($videoURL1, 0, 1).'/'
							.substr($videoURL1, 0, 2).'/'
							.substr($videoURL1, 0, 3).'/'
							.substr($videoURL1, 0, 4)."/$videoURL1";
					$filename2 = ($videoURL2 == '') ? false :
							$protocol.STATIC_URL.'videos/'
							.substr($videoURL2, 0, 1).'/'
							.substr($videoURL2, 0, 2).'/'
							.substr($videoURL2, 0, 3).'/'
							.substr($videoURL2, 0, 4)."/$videoURL2";
					$filename3 = ($videoURL3 == '') ? false :
							$protocol.STATIC_URL.'videos/'
							.substr($videoURL3, 0, 3).'/'
							.substr($videoURL3, 0, 2).'/'
							.substr($videoURL3, 0, 3).'/'
							.substr($videoURL3, 0, 4)."/$videoURL3";

					$thumbnails = $revision['revision_text']['main_image'];
				?>
				<?php for($i = 0; $i < 3; $i++): ?>
					<?php $filename = ${"filename".($i + 1)}; ?>
					<?php if ($filename !== false): ?>
						<li>
							<div id="video<?=$i + 1; ?>" style="width: <?php echo $width; ?>px; height: <?php echo $height?>px;">
								<?php
									if (isset($thumbnails[$i]["src"])){
										$image_properties = array(
											'src' => 'images/src/'.$thumbnails[$i]['src']."/w/760/zc/408",
											'alt' =>  isset($thumbnails[$i]["alt"]) ? htmlentities($thumbnails[$i]["alt"], ENT_COMPAT, 'UTF-8', false) : "",
											'width' => $width,
											'height' => $height,
											'data-credit' => isset($thumbnails[$i]["credit"]) ? htmlentities($thumbnails[$i]["credit"], ENT_COMPAT, 'UTF-8', false) : "",
											'data-desc' => isset($thumbnails[$i]["desc"]) ? htmlentities($thumbnails[$i]["desc"], ENT_COMPAT, 'UTF-8', false) : ""
										);

										echo img($image_properties);
									}else{
										echo '<img src="#" alt="Image is missing or corrupted.">';
									}
								?>
								<p><?=$filename?></p>
							</div>
						</li>
					<?php endif; ?>
				<?php endfor; ?>
			</ul>
		</div>

		<div class="two">
			<div class="edit-wysiwygadv article" data-key="article"><?=$revision['revision_text']['article']?></div>
		</div>
	</div>
</section> <!--/ #primary -->

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
