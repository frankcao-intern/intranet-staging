<?php
/**
 * Created by: cravelo
 * Date: 3/28/12
 * Time: 4:31 PM
 */
$widths = array(372, 182);
$canWrite = isset($canWrite) and $canWrite;
?>

<?php if (isset($edit)): ?>
	<?php $this->load->view('page_parts/image_stack', array('width' => 760, 'height' => 471)); ?>
<?php else: ?>
	<?php $main_image = val($revision['revision_text']['main_image'], array()); ?>
	<div id="photos" class="clearfix">
		<?php for ($i = 1; $i < count($main_image); $i++): ?>
			<div class="item" data-date="<?=val($main_image[$i]['date'], 0)*1000?>">
				<?php
					$src = val($main_image[$i]['src'], 'error');
					$flip = val($main_image[$i]['flip'], false);
					$flip = (($flip === true) or ($flip === "true"));
					$angle = (int)val($main_image[$i]['angle'], 0);
					if ($i === 1){
						$width = $widths[0];
					}else{
						$width = $widths[1];
					}
					$thumb = get_image_html($src, $width, false, $flip, $angle, false);
					$img = basename(get_image($src, false, false, $flip, $angle));
					$alt = ucfirst(htmlentities(val($main_image[$i]['alt']), ENT_COMPAT, 'UTF-8', false));
					$data_date = date("Y-m-d", val($main_image[$i]["date"], 0));
					if (isset($main_image[$i]["credit"]) and !empty($main_image[$i]["credit"])){
						$credits = htmlentities($main_image[$i]["credit"], ENT_COMPAT, 'UTF-8', false);
					}else{
						$credits = 'Anonymous';
					}
				?>
				<a rel="photos" href="<?=site_url("images/src/$img")?>" title="<?=$alt?>" >
					<img <?=$thumb?> data-date="<?=$data_date?>" alt="<?=$alt?>" />
				</a>
				<div class="caption" id="img<?=$i?>">
					<span class="js-data" data-img_id="<?=$i?>" data-gallery_id="main_image"></span>
					<h3 data-key='alt'><?=$alt?></h3>
					<p class="credits">
						Photo by: <span data-key="credits"><?=$credits?></span> -
						<a href="<?=site_url("images/src/$img")?>" class="download" download>Download</a>
					</p>
				</div>
			</div>
		<?php endfor; ?>
	</div>
<?php endif; ?>
