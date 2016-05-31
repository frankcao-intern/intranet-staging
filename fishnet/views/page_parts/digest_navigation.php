<?php
/**
 * Created by: cravelo
 * Date: 4/10/12
 * Time: 3:39 PM
 */
?>

<ul class="digest-nav">
	<li><span>Sort by: </span></li>
	<li class="sort-link"><a href="#" class="recent">Most Recent</a></li>
	<li class="sort-link"><a href="#" class="comments">Most Comments</a></li>
    <li class="sort-link"><a href="#" class="popular">Most Read</a></li>
    <li class="highlighter">&nbsp;</li>
	<span class="right">
		<li class="nav-link">
			<?=anchor(implode('/', array_merge(array($this->uri->segment(1), $page_id), val($prev, array()))),
					val($prev_text)) ?>
		</li>
		<li class="nav-link <?=$show_next ? '' : 'disabled'?>">
			<?php if ($show_next): ?>
				<?=anchor(implode('/', array_merge(array($this->uri->segment(1), $page_id), val($next, array()))),
						val($next_text))?>
			<?php else: ?>
				<span><?=val($next_text)?></span>
			<?php endif; ?>
		</li>
		<li class="nav-link">
			<a class="icon" href="<?=site_url("article/rss/$page_id")?>">
				<img src="<?=STATIC_URL?>images/feed-icon-14x14.png" alt="<?=$title?> Feed" height="16" width="16" />
			</a>
		</li>
	</span>
</ul>
