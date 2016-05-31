<?php
/**
 * Created by: cravelo
 * Date: 7/28/11
 * Time: 11:36 AM
 * single comment template
 */
//var_export($comment);
$commID = $comment['comment_id'];
$isReply = ($comment['response_to'] != $commID);
$comment_class = $isReply ? 'comment-reply' : '';
$author = (isset($revision['revision_text']["author"]) and
		$revision['revision_text']["author"] == $comment['user_id']) ? 'comment-author' : '' ;
$admin = ($comment['role'] == 'admin') ? 'comment-admin' : '' ;
$staff = ($comment['role'] == 'editor') ? 'comment-staff' : '' ;
?>
<div class="comment-a <?php echo $comment_class.' '.$author.' '.$staff.' '.$admin?>" id="comment-<?php echo $commID?>">
	<div>
		<a href="<?php echo site_url("profiles/".$comment['user_id']); ?>" title="<?php echo $comment['displayname']?>">
			<img height="64" class="avatar" src="<?php echo $comment['user_picture']; ?>"
				 alt="<?php echo $comment['displayname']; ?>">
		</a>
		<p class="meta">
			<?php if (!$isReply and $allow_comments): ?>
				<span class="reply">
					<a class="js-action-reply" data-id="<?php echo $commID; ?>">Reply</a>
				</span>
			<?php endif; ?>

			<span class="reply">
				<?php if((strtolower($this->session->userdata('role')) == 'admin') or
						((strtolower($this->session->userdata('role')) == 'editor') and
						($comment['role'] != 'admin'))): ?>
					<a class="js-action-delete" data-id="<?php echo $commID; ?>">Delete</a>
				<?php endif; ?>
			</span>

			<span class="posted"><?php echo date("g:iA, F jS, Y", strtotime($comment['timestamp'])) ?></span>
			<span class="author"><em><?php echo anchor("profiles/".$comment['user_id'], $comment['displayname'], "title=\"".$comment['displayname']."\""); ?></em> said:</span>
		</p>
		<blockquote><p><?php echo $comment['comment_text']; ?></p></blockquote>
	</div>
	<div id="comment-reply-<?php echo $commID; ?>" class="comment-reply-form relative comments"></div>
</div>
