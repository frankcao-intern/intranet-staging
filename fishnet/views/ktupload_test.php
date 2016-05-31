<?php
/**
 * Created by: cravelo
 * Date: 12/7/11
 * Time: 2:58 PM
 */

/*$server_url = 'http://effs02.eileenfisher.net/ktwebservice/ktwebservice.php';
$url = "$server_url?method=login&password=efisher5130&username=admin";
$session_id = null;

$str = @file_get_contents($url);
if ($str !== false){
	$response = new SimpleXMLElement($str);
	if ((string)$response->status_code == 0){
		$session_id = $response->results;
	}
}else{
	echo "There was an error logging in to KT.";
	exit();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
curl_setopt($ch, CURLOPT_URL, "http://effs02.eileenfisher.net/ktwebservice/upload.php");
curl_setopt($ch, CURLOPT_POST, true);

$post = array(
	'myfile' => "@".getcwd()."/../static/images/btn-bg.png",
	'session_id' => $session_id,
	'action' => 'A'
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);

if ($response){
	$components = array();
	parse_str($response, $components);
	//var_dump($components);
	if ($response['status_code'] == 0){
		$response = unserialize($components['upload_status']);
		//var_dump($response);
		if ($response['myfile']['error'] == 0){
			$url = "$server_url?method=add_document&session_id=$session_id&folder_id=11&title=".
					urlencode(pathinfo($response['myfile']['name'], PATHINFO_FILENAME))."&filename=".
					urlencode($response['myfile']['name'])."&documenttype=default&tempfilename=".
					urlencode($response['myfile']['tmp_name']);
			//echo $url;
			$str = @file_get_contents($url);
			//echo $str;
			if ($str !== false){
				$response = new SimpleXMLElement($str);
				var_dump($response);
				if ((int)$response->status_code == 0){
					echo anchor('http://effs02.eileenfisher.net/action.php?kt_path_info=
							ktcore.actions.document.view&fDocumentId='.$response->results->document_id,
					            $response->results->title);
				}else{
					echo "There was an error committing the file to the repository on KnowledgeTree";
					exit();
				}
			}else{
				echo "There was an error committing the file to the repository on KnowledgeTree";
				exit();
			}
		}else{
			echo "There was a problem uploading the file to KnowledgeTree";
			exit();
		}
	}else{
		echo "There was a problem uploading the file to KnowledgeTree";
		exit();
	}
}else{
	echo curl_error($ch);
}*/

?>
<script src="<?=STATIC_URL?>js/lib/jquery-1.6.4.js"></script>
<script src="<?=STATIC_URL?>js/lib/tinymce/jquery.tinymce.js"></script>
<link rel="stylesheet" href="<?=STATIC_URL?>js/lib/fancybox/jquery.fancybox-1.3.1.css" />
<script src="<?=STATIC_URL?>js/lib/fancybox/jquery.fancybox-1.3.1.js"></script>
<script>
	(function($){
		var Engine = {
			static_url: "<?=STATIC_URL?>",
			button: function(){
				$('#btnAddImg').click(function(){
					var html = '<a href="'+ Engine.static_url + 'images/ads_logo.png" class="fancybox" rel="gallery">' +
							'<img src="'+ Engine.static_url + 'images/ads_logo.png" alt="Ads/Placements" /></a>';
					$('textarea.tinymce').tinymce().execCommand('mceInsertContent', false, html);
				});

				$("#btnSave").click(function(){
					$("#results").empty().append($('textarea.tinymce').html());

					$(".fancybox").fancybox();
				});
			},
			initTinyMCE: function(){
				$('textarea.tinymce').tinymce({
					// Location of TinyMCE script
                    script_url : Engine.static_url + "js/lib/tinymce/tiny_mce.js",

					 // General options
					mode : "textareas",
					theme : "advanced",
					plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

					// Theme options
					theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true
				});
			}
		};

		$(document).ready(function(){
			Engine.initTinyMCE();
			Engine.button();
		});
	}(jQuery));
</script>

<label>Testing creating elements on tinyMCE:
<textarea class="tinymce" rows="" cols=""></textarea>
</label>

	<div id="results"></div>

<input type="button" value="Add Image" id="btnAddImg" />
<input type="button" value="Save" id="btnSave" />
