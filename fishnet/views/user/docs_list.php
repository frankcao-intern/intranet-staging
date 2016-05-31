<?php
$list = array();
//process published pages
foreach($pages as $page){
	$list[] = array(
		"last_mod" => date('Y/m/d', strtotime($page['date_published'])).' - ',
		"url" => "article/{$page['page_id']}",
		"title" => $page['title']
	);
}

//process documents
if (isset($forms) and is_array($forms)){
	foreach($forms as $doc){
		$list[] = array(
			"last_mod" => $doc['last_mod'].' - ',
			"url" => "http://docs.eileenfisher.com/action.php?kt_path_info=ktcore.actions.document.view&fDocumentId=".$doc['id'],
			"title" => htmlentities($doc['title'], ENT_COMPAT, 'UTF-8', false)
		);
	}
}

$last_mod_date = (val($revision['revision_text']['last_mod_date']) === "true");
?>

<section class="primary docs-list-page">
	<?php $this->load->view('page_parts/kt_template_header'); ?>

	<input type="hidden" id="pageColumns"
		   value="<?php
				if (isset($revision['revision_text']["columns"])){
					echo ($revision['revision_text']["columns"] == "") ? "1" : $revision['revision_text']["columns"];
				}else{ echo "1"; }
			?>" />

	<div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>

	<ul class="list-toolbar">
		<li class="list-search">
			<form action="#" method="get">
                <label for="quickSearch">Search:</label>
                <input type="text" id="quickSearch" name="q" placeholder="e.g. wellness" />
            </form>
		</li>

        <li class="list-sorting">
            <form action="#" method="get">
                Sort by:
                <input type="radio" name="sort1" id="sort1" class="btn-bydate" checked><label for="sort1">Date</label>
                <input type="radio" name="sort1" id="sort2" class="btn-alpha"><label for="sort2" class="last">Title</label>
            </form>
        </li>
	</ul>

	<ul class="docs-list styled-bulletlist article">
		<?php foreach($list as $item): ?>
		<li>
			<?php if (!$last_mod_date): ?><span class="doc-lastmod"><?=$item['last_mod']?></span><?php endif; ?>
			<span class="doc-title"><?=anchor($item['url'], $item['title'])?></span>
		</li>
		<?php endforeach; ?>
	</ul>

    <ul class="list-toolbar">
        <li class="list-search">
            <form action="#" method="get">
                <label for="quickSearch">Search:</label>
                <input type="text" id="quickSearch" name="q" placeholder="e.g. wellness" />
            </form>
        </li>

        <li class="list-sorting">
            <form action="#" method="get">
                Sort by:
                <input type="radio" name="sort1" id="sort1" class="btn-bydate" checked><label for="sort1">Date</label>
                <input type="radio" name="sort1" id="sort2" class="btn-alpha"><label for="sort2" class="last">Title</label>
            </form>
        </li>
    </ul>

	<div class="edit-wysiwygadv article" data-key="article1"><?=val($revision['revision_text']['article1'])?></div>
</section><!--/ #primary -->

<?php if (!isset($tab)){ $this->load->view("page_parts/related_info"); } ?>
