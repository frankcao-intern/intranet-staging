<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL; ?>css/editor_styles.css" media="screen" />
<section class="primary">
	<div class="image-stack edit_img" id="main_image">
        <?php
            if (isset($revision['revision_text']["main_image"])){
                $height = 471;
                $width = 760;

                $main_image = $revision['revision_text']["main_image"];
                if (count($main_image) > 0) {
                    if (isset($main_image[0]["src"])){
                        $image_properties = array(
                            'src' => site_url('/images/src/'.$main_image[0]["src"]."/w/760/zc/$height"),
                            'alt' => isset($main_image[0]["alt"]) ? $main_image[0]["alt"] : "",
                            'title' => isset($main_image[0]["credit"]) ? $main_image[0]["credit"] : "",
                            /*'width' => $width,*/
                            'height' => $height,
                            'class' => 'main_image'
                        );
                        echo anchor(site_url('/images/src/'.$main_image[0]["src"]), img($image_properties), "rel='main_image' class='fancybox' name='0'");
                    }else{
                        echo anchor(site_url('/images/src/#'), '<img src="#" alt="Image is missing or corrupted.">', "rel='main_image' class='fancybox' name='0'");
                    }

                    for ($i = 1; $i < count($main_image); $i++)
                    {
                        if (isset($main_image[$i]["src"])){
                            $image_properties = array(
                                'src' => site_url('/images/src/'.$main_image[$i]["src"]),
                                'alt' => isset($main_image[$i]["alt"]) ? $main_image[$i]["alt"] : "",
                                'title' => isset($main_image[$i]["credit"]) ? $main_image[$i]["credit"] : "",
                                'class' => 'main_image',
                                'style' => 'display: none'
                            );

                            echo anchor(site_url('/images/src/'.$revision['revision_text']["main_image"][$i]["src"]), img($image_properties), "rel='main_image' class='fancybox' name='$i'");
                        }else{
                            echo anchor(site_url('/images/src/#'), '<img src="#" alt="Image is missing or corrupted.">', "rel='main_image' class='fancybox' name='0'");
                        }
                    }
                }
            }
		?>
		<!--button class="play">Play video</button-->
		<?php if (isset($revision['revision_text']["main_image"][0]["credit"]) and ($revision['revision_text']["main_image"][0]["credit"] != "")): ?>
			<p class="picture-credits picture-credits-a">
				Photo By: <?php echo $revision['revision_text']["main_image"][0]["credit"]; ?>
			</p>
		<?php endif; ?>
	</div>
	<div class="header-d">
		<p class="breadcrumbs-a">
			<a href="<?php echo site_url("/"); ?>">Home</a>
			<?php $breadcrumbs = json_decode($this->session->userdata('breadcrumbs'), true); ?>
			<?php if (isset($breadcrumbs)): ?>
				&nbsp;>&nbsp;<a href="<?php echo $breadcrumbs[0]['url']; ?>"><?php echo $breadcrumbs[0]['title']; ?></a>
			<?php endif; ?>
		</p>
		<?php if (count($revision['revision_text']["main_image"]) > 1): ?>
			<p class="more-a"><a id="viewGallery">&nbsp;&nbsp;&nbsp;&nbsp;View Gallery ></a></p>
		<?php endif; ?>
		<?php if (isset($revision['revision_text']["main_image"][0]["alt"])): ?>
			<p class="more-a picture-caption"><?php echo $revision['revision_text']["main_image"][0]["alt"]; ?></p>
		<?php endif; ?>
	</div>
	<h1 class="a edit-page-property" data-key="title"><?php echo (($title == "") and $edit) ? "no title" : $title; ?></h1>
	<h2 class="d">
		<?php echo date("F j, Y", strtotime($date_published)); ?>
		<?php if ($revision['revision_text']["author"] != 'empty'): ?>
			by <?php echo anchor("/profiles/".$revision['revision_text']["author"], $revision['revision_text']["author_name"]); ?>
		<?php endif; ?>
	</h2>

	<?php if(isset($revision['revision_text']['people'])): ?>
		<ul class="edit-section lfnotes search-results-a" data-key="lf_notes">
			<?php $lfnotes = $revision['revision_text']['lf_notes']; ?>
			<?php for ($i = 0; $i < count($lfnotes); $i++): ?>
				<?php
				$rowpos = ($i % 2) ? 'right' : 'left';
				$lfnote = $revision['revision_text']['people'][$lfnotes[$i]['username']];
				$lfnote['text'] = $lfnotes[$i]['text'];
				?>
				<li class="<?php echo $rowpos ?>">
					<div class="lfnote_text">
						<div class="col vcard noteright">
							<?php if (isset($lfnote['user_picture'])): ?>
								<div class="image">
									<a href="<?php echo site_url('profiles/'.$lfnote['username']); ?>">
										<img src="<?php echo $lfnote['user_picture']; ?>" alt="<?php echo isset($lfnote['displayname']) ? $lfnote['displayname'] : ""; ?>" class="photo">
									</a>
								</div>
							<?php endif; ?>
							<div>
								<h2 class="b fn">
									<a href="<?php echo isset($lfnote['username']) ? site_url('profiles/'.$lfnote['username']) : ""; ?>"><?php echo isset($lfnote['displayname']) ? $lfnote['displayname'] : "" ?></a>
								</h2>
								<p class="title"><?php echo isset($lfnote['title']) ? $lfnote['title'] : "No title listed."; ?></p>
								<p><?php echo isset($lfnote['i_department']) ? $lfnote['i_department'] : "No department listed."; ?></p>
							</div>
							<div class="tooltip-contact">
								<div class="inner">
									<h3 class="d">Phone</h3>
										<p class="tel"><?php echo isset($lfnote['phonenumber']) ? $lfnote['phonenumber'] : "No phone listed" ?></p>
									<h3 class="d">Fax</h3>
									<p class="fax">
										<?php echo isset($lfnote['fax']) ? $lfnote['fax'] : "No fax listed."?></p>
									<p class="mail">
										<?php if (isset($lfnote['email'])): ?>
											<a href="<?php echo "mailto:".$lfnote['email']; ?>" title="<?php echo $lfnote['email']; ?>">&nbsp;</a>
										<?php else: ?>
											<a href="#" title="No email listed">&nbsp;</a>
										<?php endif; ?>
									</p>
								</div>
							</div>
						</div>

						<?php echo $lfnote['text']; ?>
					</div>
				</li>
			<?php endfor; ?>
		</ul>
	<?php endif; ?>
</section> <!--/ #primary -->

<?php $this->load->view("includes/related_info");
