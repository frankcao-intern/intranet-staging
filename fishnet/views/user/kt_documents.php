<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.quicksearch.js"></script>
<!--[if lte IE 9]>
	<script type="text/javascript" src="<?=STATIC_URL?>js/lib/jquery.autocolumn.js"></script>
<![endif]-->

<section class="primary kt-documents">
	<?php $this->load->view('page_parts/kt_template_header'); ?>

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
					$htmlStr .= '<li class="forms-file">'.$item['last_mod'].' - ';
					$htmlStr .= anchor("http://docs.eileenfisher.net/action.php?kt_path_info=ktcore.actions."
										."document.view&fDocumentId=".$item['id'],
									htmlentities($item['title'], 2, 'UTF-8'),
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

	<?="$htmlStr\n</ul>"?>

	<div class="edit-wysiwygadv article" data-key="article1"><?=val($revision['revision_text']['article1'])?></div>
</section><!--/ #primary -->

<?php if (!isset($tab)){ $this->load->view("page_parts/related_info"); }
