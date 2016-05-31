<?php
/**
 * @filename team_blog.php
 * @author   : cravelo
 * @date     : 8/2/12 4:55 PM
 */

$articles = val($articles, array());
if (count($articles) > 0){
	$blog_title = $articles[0]['section_title'];
	$blog_id = $articles[0]['section_id'];
}
?>

<?php if(isset($blog_title)): ?>
<div class="section-a">
	<h2 class="c"><?=anchor("article/$blog_id", "Read more Information Technology Blog Articles &#x25ba;")?>
	<!--?=anchor("article/$blog_id", $blog_title)?--></h2>
	<?php foreach($articles as $article): ?>
	<?php
	$articleLink = site_url("article/".$article['page_id']);
	$articleAnchor = "article/".$article['page_id'];
	$revision = $article['revision_text'];
	$src = val($revision->main_image[0]->src, 'error');
	$flip = val($revision->main_image[0]->flip, false);
	$flip = (($flip === true) or ($flip === "true"));
	$angle = (int)val($revision->main_image[0]->angle, 0);
	$img = get_image_html($src, 380, 236, $flip, $angle);
	/*$img = get_image_html($src, 618, 383, $flip, $angle);*/
	?>
	<div class="section-a entry">
		<p class="image">
			<a href="<?=site_url($articleAnchor)?>">
				<img <?=$img?> alt="<?=htmlentities(val($revision->main_image[0]->alt), ENT_COMPAT, 'UTF-8', false)?>" />
			</a>
		</p>
		<h3 class="b"><?=anchor($articleAnchor, $article['title'])?></h3>
		<p class="published">
			<?=date("F d, Y",strtotime($article['date_published']))?>
			&nbsp;|&nbsp;
			<?=(int)$article['comments_count']." ".(($article['comments_count'] == 1) ? "comment" : "comments")?>
		</p>
		<p><?=val($revision->article, "...")?></p>
		<p class="more-a"><?=anchor($articleAnchor, "Read More&nbsp;&nbsp;&#x25ba;")?></p>
	</div>
	<?php endforeach; ?>
	<!--div class="section-a entry">
		<p class="more-a">
			<?=anchor("article/$blog_id", "Read more articles in our blog &#x25ba;")?>
		</p>
	</div-->
</div>
<?php endif; ?>
