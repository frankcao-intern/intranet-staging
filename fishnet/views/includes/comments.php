<?php
	//print_r($comments);
	if(!isset($allow_comments)){
		$allow_comments = false;
	}
	$comment_count = count($comments);
?>
<section id="comments">
	<?php if (($comment_count > 0) or $allow_comments ): //if there is anything to display ?>
		<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/comments.css" />

		<div class="section-a comments">
			<a name="comments"></a>
			<h2 class="c">Comments</h2>
			<h3>
				There are <span class="comment-count"><?php echo ($comment_count === 0) ? 'no' : $comment_count; ?></span> comments
				<?php if ($allow_comments): ?>
					- <a href="#leavecomment">Leave a comment</a>
				<?php else: ?>
					- Comments are closed for this page
				<?php endif; ?>
			</h3>

			<?php foreach ($comments as $comment){ $this->load->view('includes/comment', array('comment' => $comment)); } ?>
		</div>
	<?php endif; ?>

	<?php if ($allow_comments)://if we are allowing comments show the form ?>
		<a name="leavecomment"></a>
		<div id="comment-reply-0" class="relative comments"></div>
	<?php endif; ?>
</section>
