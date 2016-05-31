<?php
/**
 * Created by: cravelo
 * Date: 4/16/12
 * Time: 12:44 PM
 */

$author_name = val($revision['revision_text']["author_name"]);
$author = val($revision['revision_text']["author"], 'empty');

if (!isset($tab)): ?>
	<div class="header-a">
		<p><?=isset($breadcrumbs) ? $breadcrumbs['url'] : "&nbsp;"?></p>
		<h2><?=val($back_link, '&nbsp;')?></h2>
		<?php if (isset($edit)){ $this->load->view('page_parts/publishing_dropdown'); }?>
	</div>
<?php endif; ?>

<div class="article-header clearfix">
	<?php if (!isset($tab)): ?>
		<div class="left">
			<h2 class="a edit-page-property" data-key="title"><?=empty($title) ? "Untitled Page" : $title?></h2>
			<p class="d">
				<time datetime="<?=date("Y-m-d", strtotime($date_published))?>"
				      pubdate data-key="date_published" class="edit-datepicker"><?=
					date("F j, Y", strtotime($date_published))
				?></time>
				<?php if (isset($edit)): ?>
					<br />by <input type="text" id="editAuthor" size="30" value="<?=$author_name?>" />
				<?php elseif ($author != 'empty'): ?>
					<br />by <?=anchor("/profiles/".$author, $author_name, 'rel="author"')?>
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>
	<div class="right">
	</div>
</div>
