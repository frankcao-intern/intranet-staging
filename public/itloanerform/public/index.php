<?php
	session_name("EFLoanerForm");
	session_start();

	define("EF_IT_LOAN_FORM", TRUE);
	include("../utils/settings.php");
	include("../utils/logger.php");

	$mgrReview = isset($_GET['f']) ? true : false;

	$dataArr = array();
	if ($mgrReview){
		$filename = "../completed/".$_GET['f'];
		if (file_exists($filename)){
			$dataArr = json_decode(file_get_contents($filename), true);
		}else{
			exit();
		}
	}

	$requested_page = $settings['pages']['form'].".php";
	if (isset($_GET['p'])){
		$page = preg_replace('[^A-Za-z9-0]', '', $_GET['p']);

		if (isset($settings['pages'][$page])){
			$page = $settings['pages'][$page].".php";
		}
		
		if (file_exists($page)) { $requested_page = $page; }
	}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>IT Equipment Loan Request Form v0.9</title>
	<meta name="description" content="">
	<meta name="keywords" content="">

	<meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

    <!-- CSS: implied media=all -->
	<link rel="stylesheet" href="assets/css/jquery-ui-1.8.16.custom.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <!-- end CSS-->

	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="assets/js/libs/jquery-1.6.4.min.js"><\/script>')</script>
	<script>
		(function(w){
			/*Global stuff*/
			w.formEngine = {
				SID: '<?php echo sha1(session_id()); ?>',
				base_url: '<?php echo $settings['base_url']; ?>',
				mgrReview: <?php echo $mgrReview ? 'true' : 'false'; ?>,
				displayError: function(data){
					$("#errorDiag").dialog('option', 'strError', data).dialog('open');
				},
				sectionOpeners: function(){
					if (this.checked){
						$("#" + this.value).show();
					}else{
						$("#" + this.value).hide();
					}
				}
			};
		}(window));
	</script>
</head>

<body>

	<!-- dialogs and other hidden divs -->
	<div id="errorDiag" title=""><span id="errorDiagMsg"></span></div>

	<div class="maincont">
		<?php include($requested_page); ?>
	</div>

	<!-- JavaScript at the bottom for fast page loading -->
	<script defer src="assets/js/lib/jquery-ui-1.8.16.custom.min.js"></script>
	<script defer src="assets/js/lib/jquery.placeholder.min.js"></script>
	<script defer src="assets/js/lib/json2_mini.js"></script>
	<?php if (isset($page_js)): ?><script defer src="assets/js/<?php echo $page_js; ?>.js"></script><?php endif; ?>
	<script defer src="assets/js/main.js"></script>
	<!-- end scripts-->

</body>
</html>
