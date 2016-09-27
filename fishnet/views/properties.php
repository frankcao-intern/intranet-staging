<?php
/**
 * User: cravelo
 * Date: Jul 27, 2010
 * Time: 10:49:52 AM
 * this are the page properties.
 */

$page_type = val($page_type, 'page');
$title = character_limiter(val($title, "Untitled $page_type"), 35, '...');
?>

<div id="sharePageDiag" style="barkground-color: #000; display: none;" title="Share this page">
    <label for="shareEmail">Start typing a name then select from the list:</label>
    <input id="shareEmail" type="text" />
    <ul></ul>
    <label for="shareMsg">Notes for Reviewer:</label>
    <textarea id="shareMsg" rows="5" cols="28"></textarea>
</div>
<div id="reviewPageDiag" style="barkground-color: #000; display: none;" title="Share this page">
    <label for="reviewEmail">Start typing a name then select from the list:</label>
    <input id="reviewEmail" type="text" />
    <ul></ul>
    <label for="shareMsg">Notes for Reviewer:</label>
    <textarea id="shareMsg" rows="5" cols="28"></textarea>
</div>

<section class="primary">
	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/article/$page_id", "&#x25c4; Go back to the $page_type")?></p>
		<h2><?=ucfirst($page_type)?> Settings - <?=$title?></h2>
	</div>

	<?php $this->load->view('page_parts/properties_'.$page_type); ?>

	<div class="header-a header-a-space-bottom-b">
		<p><?=anchor("/article/$page_id", "&#x25c4; Go back to the $page_type")?></p>
		<h2><?=ucfirst($page_type)?> Settings - <?=$title?></h2>
	</div>
</section> <!--/ #primary -->

<aside class="secondary">
    <?php
        $user_id = $this->session->userdata('user_id');
        $review_data = get_page_review_details($page_id, $user_id);
        //pr($review_data);
        if($review_data['reviewer_id'] == $user_id && $review_data['status'] == 0):
    ?>
    <h2 class="c">Review Status </h2>

        <p >
        <div class="my-notify-info">
            You are requested to review and pulish this article.
        </div>
        </p>
    <?php
        endif;
    ?>
    <div class="button-a section-a" >
        <a id="btnReview" style="cursor: hand; text-decoration: none;">Request for review</a>
    </div>

    <!--<div class="section-a">
        <p class="settings-a">
            <label for="tags">Who: </label>
            <input id="approve-article" type="text" name="approve-article" value="" />
        </p>
        <p>
            <button class="btnArticleReview">Request for review</button>
        </p>
    </div>-->

</aside> <!--/ #secondary -->
