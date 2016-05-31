<?php
/**
 * Created by: cravelo
 * Date: 8/15/11
 * Time: 10:27 AM
 */
?>
<div class="rotator-d">
	<div class="items">
		<ul><li>
			<?php for($i = 0; $i < count($articles); $i++): ?>
				<?php if (!isset($articles[$i])){ continue; } ?>
				<?php $aRevision = $articles[$i]['revision_text']; ?>
				<div class="item">
					<h2 class="c c-b"><?php echo anchor('articles/'.$articles[$i]['section_id'], $articles[$i]['section_title']); ?></h2>
					<p class="thumb">
						<a href="<?php echo site_url('articles/'.$articles[$i]['page_id']); ?>" class="item-link">
							<?php if (isset($aRevision->main_image) and isset($aRevision->main_image[0])): ?>
								<img
									src="<?php echo site_url('/images/src/'.$aRevision->main_image[0]->src.'/w/139/zc/86'); ?>"
									height="86"
									alt="<?php echo htmlentities($aRevision->main_image[0]->alt, 2, 'UTF-8'); ?>" />
							<?php else: ?>
								<span style="display: block; width: 139px; height: 86px;">&nbsp;</span>
							<?php endif; ?>
							<span class="title"><?php echo ucwords($aRevision->teaser); ?></span>
						</a>
					</p>
					<div class="main">
						<p class="image">
							<?php if (isset($aRevision->main_image) and isset($aRevision->main_image[0])): ?>
								<img
									src="<?php echo site_url('/images/src/'.$aRevision->main_image[0]->src.'/w/618/zc/383'); ?>"
									height="383"
									alt="<?php echo htmlentities($aRevision->main_image[0]->alt, 2, 'UTF-8'); ?>" />
							<?php else: ?>
								<span style="display:block; width: 619px; height: 383px;">&nbsp;</span>
							<?php endif; ?>
						</p>
						<div class="caption">
							<?php if ($articles[$i]['title'] != ""): ?>
								<h2 class="a">
									<?php echo anchor('articles/'.$articles[$i]['page_id'], htmlentities($articles[$i]['title'], 2, 'UTF-8')); ?>
								</h2>
							<?php endif; ?>
							<p>
								<?php echo $aRevision->article; ?>
								<?php echo anchor('articles/'.$articles[$i]['page_id'], "&nbsp;&nbsp;&nbsp;&nbsp;Read More&nbsp;&nbsp;&#x25ba;", 'class="more-a"'); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endfor; ?>
		</li></ul>
	</div>
</div> <!--/ .rotator-d -->
