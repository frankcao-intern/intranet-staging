<?php
/**
 * Created by: cravelo
 * Date: 4/16/12
 * Time: 10:52 AM
 */

$author_name = val($revision['revision_text']["author_name"]);
$author = val($revision['revision_text']["author"], 'empty');
$other = array();
$user_sections = val($user_sections, array());

if (!isset($tab)): ?>
	<div class="header-a">
		<p><?=isset($breadcrumbs) ? $breadcrumbs['url'] : "&nbsp;"?></p>
		<h2><?=val($back_link, '&nbsp;')?></h2>
		<?php //if (isset($edit)){ $this->load->view('page_parts/publishing_dropdown'); }?>
	</div>
	<div class="article-header">
		<h3 class="a edit-page-property" data-key="title"><?=empty($title) ? "Untitled Page" : $title?></h3>
		<p class="d">
			<time
				datetime="<?=date("Y-m-d", strtotime($date_published))?>"
				pubdate="pubdate" data-key="date_published" class="edit-datepicker"><?=date("F j, Y", strtotime($date_published))
			?></time>
			<?php if (isset($edit)): ?>
				<br />by
				<label for="editAuthor" class="offset">Enter an author: </label>
				<input type="text" id="editAuthor" size="30" value="<?=$author_name?>" />
			<?php elseif ($author != 'empty'): ?>
				<br />
				<?php if ($author != 'custom'): ?>
					by <?=anchor("/profiles/".$author, $author_name, 'rel="author"')?>
				<?php else: ?>
					by <span rel="author"><?=$author_name?></span>
				<?php endif; ?>
			<?php endif; ?>
		</p>
	</div>
<?php endif; ?>
