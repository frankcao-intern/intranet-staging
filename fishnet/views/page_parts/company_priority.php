<?php
/**
 * Created by: cravelo
 * Date: 4/23/12
 * Time: 12:59 PM
 */

$id = val($id, "main_image");

?>

<?php if (isset($edit)): ?>
	<?php $this->load->view('page_parts/image_stack', array(
		'width' => 240,
		'height' => 240,
		'id' => $id,
		'class' => 'col'
	)) ?>
<?php else: ?>
	<?php
		$main_image = val($revision['revision_text'][$id], array());
		$src = val($main_image[0]['src'], 'error');
		$flip = val($main_image[0]['flip'], false);
		$flip = (($flip === true) or ($flip === "true"));
		$angle = (int)val($main_image[0]['angle'], 0);
		$img = get_image_html($src, 240, 240, $flip, $angle);
		$alt = ucfirst(htmlentities(val($main_image[0]['alt']), ENT_COMPAT, 'UTF-8', false));
		$credit = val($main_image[0]["credit"]);
	?>
	<a href="#<?=$id?>" class="priority-link col">
		<img <?=$img?> alt="<?=$alt?>" />
		<p class="caption"><?=$credit?></p>
	</a>
	<div id="<?=$id?>" class="priority hide">
		<img <?=$img?> alt="<?=$alt?>" />
		<div class="desc">
			<hgroup>
				<h3><?=$alt?></h3>
				<h4><?=$credit?></h4>
			</hgroup>
			<?=val($main_image[0]["desc"])?>
		</div>
	</div>
<?php endif; ?>
