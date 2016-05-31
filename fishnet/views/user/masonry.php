<?php
/**
 * Created by: cravelo
 * Date: 9/13/11
 * Time: 9:41 AM
 */
 ?>

<div class="primary masonry">
	<span class="hide page-columns"><?php
		if (isset($revision['revision_text']["columns"]) and !empty($revision['revision_text']["columns"])){
			echo $revision['revision_text']["columns"];
		}else{
			echo "1";
		}
	?></span>
	<?php $this->load->view('page_parts/article_header') ?>

	<div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>

	<?php $this->load->view('page_parts/masonry'); ?>
</div>

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info"); ?>

<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.isotope.js"></script>
<script type="text/javascript" src="<?=STATIC_URL?>js/templates/user/text_only.js"></script>
