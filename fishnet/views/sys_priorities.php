<?php
/**
 * Created by: cravelo
 * Date: 4/23/12
 * Time: 8:53 AM
 */
?>

<section class="primary">
	<div class="header-a header-a-space-bottom-b"
		 style="border-left: 12px solid <?=$revision['revision_text']['color']?>">
		<p><?php if (isset($edit)): ?>
				<?=anchor("/article/$page_id", "&#x25c4; Preview Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");?>
			<?php else: ?>
				<?=isset($back_link) ? "&#x25c4; $back_link" : "&nbsp;"?>
		<?php endif; ?></p>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>

	<div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>

    <div class="triple-a columns">
		<?php $this->load->view('page_parts/company_priority', array(
		                                                            'id' => 'sustainability',
		                                                       )) ?>
		<?php $this->load->view('page_parts/company_priority', array(
		                                                            'id' => 'leadership',
		                                                       )) ?>
		<?php $this->load->view('page_parts/company_priority', array(
		                                                            'id' => 'broadening',
		                                                       )) ?>
    </div>
</section> <!--/ Primary -->

<?php $this->load->view("page_parts/related_info");
