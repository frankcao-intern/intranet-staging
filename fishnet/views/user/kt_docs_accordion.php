<section class="primary kt-accordion">
	<?php $this->load->view('page_parts/kt_template_header'); ?>

	<div class="edit-wysiwygadv article" data-key="article"><?=val($revision['revision_text']['article'])?></div>

	<?php
		//generate HTML
		$htmlStr = "<div class=\"accordion\">\n";

		/**
		 * Takes a multidimensional array and creates nested HTML lists.
		 *
		 * @param array $mArray results from arrayFromKTResults, file structure in array form
		 * @param string_reference $htmlStr the html code where I should append the file list
		 * @param bool $nodirs whether to create a file structure or just a list of all the files
		 * @return bool
		 */
		function generateTopLevel($mArray, &$htmlStr, $nodirs) {
			foreach ( $mArray as $name => $item ) {
				if ($item == "") continue;
				if ( is_array($item) and !isset($item['title']) ) {
					$htmlStr .= ($nodirs) ? '' : '<h3><a href="#">'.$name."</a></h3>\n<div>\n<ul class=\"forms-list\">\n";
					if (!$nodirs) asort($mArray[$name]);
					generateTopLevel($item, $htmlStr, true);
					$htmlStr .= ($nodirs) ? '' : "</ul>\n</div>\n";
				}else{
					$htmlStr .= '<li class="forms-file">'.$item['last_mod'].' - ';
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
		generateTopLevel($forms, $htmlStr, false);
	}

	//var_dump($forms);

	?>

	<?="$htmlStr\n</div>"?>

	<div class="edit-wysiwygadv article" data-key="article1"><?=val($revision['revision_text']['article1'])?></div>
</section><!--/ #primary -->

<?php if (!isset($tab)){ $this->load->view("page_parts/related_info"); }
