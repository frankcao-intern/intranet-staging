<section class="primary">
	<?php if (!isset($tab)): ?>
		<div class="header-a">
			<p><?=isset($breadcrumbs) ? $breadcrumbs['url'] : "&nbsp;"?></p>
			<h2><?=val($back_link, '&nbsp;')?></h2>
		</div>
	<?php endif; ?>
	<div id="main_slide">
		<?php $this->load->view('page_parts/image_stack', array('width' => 353, 'height' => 471)); ?>
		<div class="caption">
			<h2>As Seen In</h2>
			<p class="edit-textarea as-seen-in" data-key="teaser"><?=$revision['revision_text']['teaser']?></p>
			<p>
				On
				<time datetime="<?=date("Y-m-d", strtotime($date_published))?>"
				      pubdate data-key="date_published" class="edit-datepicker"><?=
						date("F j, Y", strtotime($date_published))
				?></time>
			</p>
			<div class="edit-textarea desc article" data-key="article"><?=
				$revision['revision_text']['article'];
			?></div>
		</div>
	</div>
</section> <!--/ #primary -->

<?php isset($tab) ? "" : $this->load->view("page_parts/related_info");
