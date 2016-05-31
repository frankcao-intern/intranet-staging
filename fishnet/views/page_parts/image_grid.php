<?php
/**
 * Created by: cravelo
 * Date: 4/19/12
 * Time: 2:42 PM
 */
?>

<ul id="main_image" class="edit_img">
	<?php
		$per_row = 4;
		$main_image = val($revision['revision_text']['main_image'], array());
	?>
	<?php for($i = 0; $i < count($main_image); $i += $per_row): ?>
		<?php for($j = $i; $j < $i + $per_row; $j++): ?>
			<?php if (isset($main_image[$j])): ?>
				<?php
					$src = val($main_image[$j]['src'], 'error');
					$flip = val($main_image[$j]['flip'], false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[$j]['angle'], 0);
					$img = get_image_html($src, 760, 471, $flip, $angle, false);
					$anchor = get_image($src, false,false, $flip, $angle);
					$anchor = basename($anchor);
				?>
				<li class="row<?=$i / $per_row?> image">
					<a href="<?=site_url("images/src/$anchor")?>" rel="main_image" class="fancybox" data-index="<?=$j?>">
						<img <?=$img?> alt="<?=ucfirst(htmlentities(val($main_image[$i]['alt']), ENT_COMPAT, 'UTF-8', false))?>"
							data-date="<?=date("Y-m-d", val($main_image[$i]["date"], 0))?>"
							data-desc="<?=htmlentities(val($main_image[$i]["desc"]), ENT_COMPAT, 'UTF-8', false)?>"
							data-credit="<?=htmlentities(val($main_image[$i]["credit"]), ENT_COMPAT, 'UTF-8', false)?>" />
					</a>
				</li>
			<?php endif; ?>
		<?php endfor; ?>
	<?php endfor; ?>
</ul>
