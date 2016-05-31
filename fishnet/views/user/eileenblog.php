
<section class="primary">
	<div class="header-a">
		<h2><?=anchor($this->uri->segment(1)."/$page_id", val($title, 'Untitled Page'))?></h2>
		<span data-key="subtitle" class="edit-textinline" id="subtitle">stories about who we are</span>
	</div>
	
	<br><br>
	<div class="columns triple-c">
		<div class="col">
			<div id="parable">
				<a href='/journal/3401'>PARABLE
				</a>
			</div>	
		</div>
		<div class="col">
			<div id="wrd">
				<a href="http://www.eileenfisher.com/EileenFisher/collection/resort/our_30th_anniversary.jsp" rel="external">
				WEARING, READING, DOING  </a>
			</div>		
		</div>
		<div class="col">
			<div id="roots">
				<a href="http://www.eileenfisher.com/EileenFisher/collection/resort/our_30th_anniversary.jsp" rel="external">ROOTS</a>
			</div>		
		</div>
	</div>
	<ul class="columns triple-a section-a">
		<?php foreach($articles as $article): ?>
			<?php $revision = $article['revision_text']; ?>
			<li class="col">
				<p class="image">
					<a href="<?=site_url("article/".$article['page_id'])?>">
						<?php
						$src = val($article['revision_text']->main_image[0]->src, 'error');
						$flip = val($article['revision_text']->main_image[0]->flip, false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($article['revision_text']->main_image[0]->angle, 0);
						$img = get_image_html($src, 325, 240, $flip, $angle);
						?>
						<img <?=$img?> alt="<?=$article['title']?>" />
					</a>
				</p>
				<div>
					<h2 class="b"><?=anchor("article/".$article['page_id'], $article['title'])?></h2>
					
				</div>
				
			</li>
			
		<?php endforeach; ?>
		</ul>

		<!--?php foreach(val($articles, array()) as $article): ?-->
			<!--?php
				$articleLink = site_url("article/".$article['page_id']);
				$articleAnchor = "article/".$article['page_id'];
				$revision = $article['revision_text'];
				$src = val($revision->main_image[0]->src, 'error');
				$flip = val($revision->main_image[0]->flip, false);
				$flip = (($flip === true) or ($flip === "true"));
				$angle = (int)val($revision->main_image[0]->angle, 0);
				$img = get_image_html($src, 380, 236, $flip, $angle);
			?>
			<div class="section-a entry">
				<p class="image">
					<a href="<?=site_url($articleAnchor)?>">
						<img <?=$img?> alt="<?=htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false)?>" />
					</a>
				<h3 class="b"><?=anchor($articleAnchor, $article['title'])?></h3>
				</p>
			</div-->
		<!--?php endforeach; ?-->
	
	
</section> <!--/ #primary -->


