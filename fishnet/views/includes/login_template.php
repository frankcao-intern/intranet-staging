<!doctype html public "☀☀☀ ☃ ♨♨♨">
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<!-- Always force latest IE rendering engine & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>

	<meta charset="utf-8" />

	<link rel="Shortcut Icon" href="<?=STATIC_URL?>images/icon11.ico" type="image/x-icon" />

	<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/lib/jquery-ui-1.8.16.custom.min.css" />
	<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/screen.css" />
	<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/templates/<?php echo $template_name; ?>.css" />

	<!--[if lt IE 9]>
		<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/html5.js"></script>
	<![endif]-->
</head>
<body>

<?php $this->load->view($template_name); ?>

<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery-1.6.4.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/jquery-ui-1.8.16.custom.js"></script>
<!--<script type="text/javascript" src="--><?php //echo STATIC_URL; ?><!--js/main.js"></script>-->
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/templates/<?php echo $template_name; ?>.js"></script>
</body>
</html>

