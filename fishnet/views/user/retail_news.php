<!--<link rel="stylesheet" media="screen, projection" href="--><?//=STATIC_URL?><!--css/lib/fullcalendar/fullcalendar.css" />-->
<link rel="stylesheet" media="print" href="<?=STATIC_URL?>css/lib/fullcalendar/fullcalendar.print.css" />

<section class="primary kt-documents">
	<!--?php $this->load->view('page_parts/article_header'); ?-->

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
			<?php if (isset($edit)){ $this->load->view('page_parts/publishing_dropdown'); }?>
        </div>
        <div class="article-header">
            <h3 class="a edit-page-property" data-key="title"><?=empty($title) ? "Untitled Page" : $title?></h3>


            <div class="section-a">
                <h2 class="c">Timeline - <?=anchor('calendar/'.SID_RETAIL_CALENDAR, 'Retail Calendar &#x25ba;')?></h2>
                <div id="calendar"></div>
            </div>
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

	<input type="hidden" id="pageColumns"
		   value="<?php
				if (isset($revision['revision_text']["columns"])){
					echo ($revision['revision_text']["columns"] == "") ? "1" : $revision['revision_text']["columns"];
				}else{ echo "1"; }
			?>" />

    <div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>

	<?php
		//generate HTML
		$htmlStr = '<ul class="forms-list">';

		/**
		 * Takes a multidimensional array and creates nested HTML lists.
		 *
		 * @param array $mArray results from arrayFromKTResults, file structure in array form
		 * @param string $htmlStr by reference: the html code where I should append the file list
		 * @param bool $nodirs whether to create a file structure or just a list of all the files
		 * @return bool
		 */
		function KTArrayToHTML($mArray, &$htmlStr, $nodirs) {

			foreach ( $mArray as $name => $item ) {
				if ($item == "") continue;
				if ( is_array($item) and !isset($item['title']) ) {
					$htmlStr .= ($nodirs) ? '' : '<li>&nbsp;<span>'.$name."</span><ul>\n";
					if (!$nodirs) asort($mArray[$name]);
					KTArrayToHTML($item, $htmlStr, $nodirs);
					$htmlStr .= ($nodirs) ? '' : "</ul>\n</li>\n";
				}else{
					$htmlStr .= '<li class="forms-file">';
					$htmlStr .= anchor("http://docs.eileenfisher.net/action.php?kt_path_info=ktcore.actions."
										."document.view&fDocumentId=".$item['id'],
									htmlentities($item['title'], ENT_COMPAT, 'UTF-8', false),
									'target="_blank"');
					$htmlStr .= "</li>\n";
				}
			}

			return true;
		}

		if (isset($forms) and is_array($forms)){
			KTArrayToHTML($forms, $htmlStr, true);
		}else if(isset($isError) and ($isError == true)){
			echo $errorStr;
		}
	?>

	<div class="section-a">
		<h2 class="c">This week's documents</h2>

		<form action="#" method="get">
			<label for="quickSearch">Search:</label>
			<input type="text" id="quickSearch" name="q" placeholder="Type to search the file list" />
		</form>

		<?="$htmlStr\n</ul>"?>
	</div>
</section><!--/ #primary -->

<div id="sharePageDiag" style="display: none;" title="Share this page">
	<label for="shareEmail">Start typing a name then select from the list:</label>
	<input id="shareEmail" type="text" />
	<ul></ul>
	<label for="shareMsg">Message:</label>
	<textarea id="shareMsg" rows="5" cols="32"></textarea>
</div>

<aside class="secondary article-sidebar">
	<div class="buttonset section-a">
		<a id="btnSave">Save
		</a><a id="btnShare">Share
		</a><a id="btnPrint">Print
		</a>
	</div>
	<div class="section-a">
		<h2 class="c"><?=anchor('calendar/'.SID_RETAIL_CALENDAR, 'Retail Calendar')?></h2>
		<div id="miniCalendar" class="mini-calendar"><?=SID_RETAIL_CALENDAR?></div>
	</div>
	<?php if (!empty($revision['revision_text']['related_info']) or isset($edit)): ?>
	<div class="section-a">
		<h2 class="c">In this Issue</h2>
		<div class="edit-wysiwygadv related-info" data-key="related_info">
			<?=val($revision['revision_text']['related_info']); ?>
		</div>
	</div>
	<?php endif; ?>
</aside> <!--/ #secondary -->
