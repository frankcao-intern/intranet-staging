<?php
/**
 * Created by: cravelo
 * Date: 4/3/12
 * Time: 10:01 AM
 */
?>

<div class="section-a">
	<h2 class="c">Tags</h2>
	<ul class="tagcloud">
		<?php foreach(val($all_tags, array()) as $tag): ?>
			<li id="<?=$tag['tag_id']; ?>" class="tag-<?php echo (ceil($tag['popularity'] / 10))?>">
				<a href="<?=site_url("/article/tag/".$tag['tag_name'])."/section/$page_id"; ?>">
					<?php echo $tag['tag_name']?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
