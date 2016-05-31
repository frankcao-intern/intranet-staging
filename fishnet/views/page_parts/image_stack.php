<?php
/**
 * Created by: cravelo
 * Date: 3/9/12
 * Time: 3:23 PM
 */

$id = val($id, 'main_image');
$hidden = val($hidden, true);
?>

<div class="image-stack <?=val($class)?>">
	<ul class="edit_img" id="<?=$id?>">
		<?php $main_image = val($main_image, val($revision['revision_text'][$id], array())); ?>
		<?php for ($i = val($startingPic, 0); $i < count($main_image); $i++): ?>

			<li style="<?=(($i !== 0) and $hidden) ? 'display: none' : ''?>">
				<?php
					$src = val($main_image[$i]['src'], 'error');
					$flip = val($main_image[$i]['flip'], false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[$i]['angle'], 0);
					$img = get_image_html($src, val($width, false), val($height, false), $flip, $angle);
					$anchor = get_image($src, false, false, $flip, $angle);
					$anchor = basename($anchor);
					$alt = ucfirst(val($main_image[$i]['alt']));
					$data_credit = htmlspecialchars(val($main_image[$i]["credit"]), 2, 'UTF-8');
				?>
				<div class="image">
					<a href="<?=site_url("images/src/$anchor")?>" rel="<?=$id?>" class="fancybox" data-index="<?=$i?>"
					   title="<?=htmlentities($alt, ENT_COMPAT, 'UTF-8')?>">
						<?php if (isset($edit) or ($i === 0) or !$hidden): ?>
							<img <?=$img?> alt="<?=htmlentities($alt, ENT_COMPAT, 'UTF-8')?>"
								data-date="<?=date("Y-m-d", val($main_image[$i]["date"], 0))?>"
								data-desc="<?=htmlentities(val($main_image[$i]["desc"]), ENT_COMPAT, 'UTF-8', false)?>"
								data-credit="<?=$data_credit?>" />
						<?php endif; ?>
					</a>
				</div>
				<?php if (count($main_image) > 1): ?>
					<div class="view-gallery-back">&nbsp;</div>
					<p class="view-gallery"><a>View Gallery</a></p>
				<?php endif; ?>
				<?php if (isset($picture_info)): ?>
					<div class="picture-info">
						<?php if (!empty($alt)): ?>
							<p class="picture-caption">
								<?=$alt?>
							</p>
						<?php endif; ?>
						<?php if (!empty($data_credit)): ?>
							<p class="picture-credits">
								Photo By: <?=$data_credit?>
							</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endfor; ?>
	</ul>
</div>
