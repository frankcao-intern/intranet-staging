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

    if($review_data != false):
        if($review_data['reviewer_id'] == $user_id && $review_data['status'] == 0):
    ?>
    <h2 class="c">Review Status </h2>

        <p >
            <div class="my-notify-info">
                Review request status - <b> pending.</b>
            </div>
        </p>
    <?php
        else:
    ?>
        <p >
            <div class="my-notify-success">
                Review request status - <b> published.</b>
            </div>
        </p>
    <?php
        endif;
    endif;
    ?>
    <p><button class="button" id="btnReview">Request for review</button></p>

</aside> <!--/ #secondary -->
