<?php
/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 9:41 AM
 */
 ?>

<div class="primary photo-albums">
	<span class="hide page-columns"><?php
		if (isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])){
			echo $revision['revision_text']["columns"];
		}else{
			echo "1";
		}
	?></span>
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="edit-wysiwygadv article clearfix" data-key="article"><?=val($revision['revision_text']['article'])?></div>

	<?php if (isset($edit)): ?>
		<button id="addNewAlbum">Add a new Album</button>
	<?php endif; ?>

	<div id="photos" class="clearfix">
	<?php foreach($revision['revision_text'] as $key => $main_image):?>
		<?php if (preg_match('/main_image(\d+)/i', $key)): ?>
			<?php if (isset($edit)): ?>
				<?php $this->load->view('page_parts/image_stack', array('width' => 172, 'height' => false, 'id' => $key)); ?>
			<?php else: ?>
				<div class="item">
					<?php $this->load->view('page_parts/image_stack', array('width' => 172, 'id' => $key)); ?>
					<?php
						$src = val($main_image[0]['src'], 'error');
						$flip = val($main_image[0]['flip'], false);
						$flip = (($flip === true) or ($flip === "true"));
						$angle = (int)val($main_image[0]['angle'], 0);
						$thumb = get_image_html($src, 172, false, $flip, $angle, false);
						$alt = ucfirst(htmlentities(val($main_image[0]['alt']), ENT_COMPAT, 'UTF-8', false));
						$data_date = date("Y-m-d", val($main_image[0]["date"], 0));
					?>
					<img <?=$thumb?> data-date="<?=$data_date?>" alt="<?=$alt?>" class="thumb" />
					<h4><?=$alt?></h4>
					<?php if (isset($main_image[0]["credit"]) and !empty($main_image[0]["credit"])): ?>
						<p class="credits">by: <?=htmlentities($main_image[0]["credit"], ENT_COMPAT, 'UTF-8', false)?></p>
					<?php endif; ?>
					<div class="desc"><?=val($main_image[0]["desc"])?></div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
</div>

<?php if (!isset($tab)) { $this->load->view("page_parts/related_info"); } ?>

<?php if (!isset($edit)): ?>
	<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.isotope.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?=STATIC_URL?>js/templates/user/text_only.js"></script>
