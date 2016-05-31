<?php
	//print_r($comments);
	$comments = val($comments, array());
	$comment_count = count($comments);
?>
<section id="comments">
	<link rel="stylesheet" href="<?=STATIC_URL?>css/comments.css" />
	<div class="section-a comments">
		<a name="comments"></a>
		<h2 class="c">Comments</h2>
		<h3>
			There are <span class="comment-count"><?=($comment_count === 0) ? 'no' : $comment_count?></span> comments
			<?php if ($allow_comments): ?>
				- <a href="#leavecomment">Leave a comment</a>
			<?php else: ?>
				- Comments are closed for this page
			<?php endif; ?>
		</h3>

		<?php foreach ($comments as $comment){
			$this->load->view('page_parts/comment', array('comment' => $comment));
		} ?>
	</div>
	<?php if ($allow_comments)://if we are allowing comments show the form ?>
		<div id="commentForm">
			<a name="leavecomment"></a>
			<div id="comment-reply-0" class="relative comments"></div>
		</div>
	<?php endif; ?>
</section>
