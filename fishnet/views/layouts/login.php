<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<meta charset="utf-8" />

	<title><?=val($title, 'untitled')?></title>

	<link rel="Shortcut Icon" href="<?=STATIC_URL?>images/icon11.ico" type="image/x-icon" />

	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery-ui-1.8.23.custom.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/screen.css" />
	<?php if (file_exists(APPPATH."../public/assets/css/templates/$template_name.css") === TRUE): ?>
		<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?=$template_name?>.css?<?=FISHNET_VERSION?>" />
	<?php endif; ?>

	<!--[if lt IE 9]>
		<script src="<?=STATIC_URL?>js/lib/html5shiv.js"></script>
	<![endif]-->
</head>
<body>

	<?php if (file_exists(APPPATH."/views/$template_name.php")): ?>
		<?php $this->load->view($template_name); ?>
	<?php endif; ?>

	<script src="<?=STATIC_URL?>js/lib/jquery-1.8.1.js"></script>
	<script src="<?=STATIC_URL?>js/lib/jquery-ui-1.8.23.custom.js"></script>
	<?php if (file_exists(APPPATH."../public/assets/js/templates/$template_name.js") === TRUE): ?>
		<script src="<?=STATIC_URL?>js/templates/<?=$template_name?>.js?<?=FISHNET_VERSION?>"></script>
	<?php endif; ?>
</body>
</html>

