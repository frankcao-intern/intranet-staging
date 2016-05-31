<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<meta charset="utf-8" />
	<?php if (file_exists(APPPATH."../public/assets/css/templates/$template_name.css") === TRUE): ?>
		<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?=$template_name?>.css?<?=FISHNET_VERSION?>" />
	<?php endif; ?>
	<style>
		body, html { overflow: hidden; padding: 0; margin: 0; width: 100%; height: 100%; background: transparent; }
	</style>
</head>
<body>
	<?php if (file_exists(APPPATH."/views/$template_name.php")): ?>
		<?php $this->load->view($template_name); ?>
	<?php endif; ?>

	<script>
		(function($){
			var coreEngine = coreEngine || {};

			$.extend(coreEngine, {
				siteRoot: "<?=base_url()?>",
				csrf_token:     '<?=$this->security->get_csrf_hash()?>'
			});

			document.documentElement.className  += " js";
		}(jQuery));
	</script>
	<?php if (file_exists(APPPATH."../public/assets/js/templates/$template_name.js") === TRUE): ?>
		<script src="<?=STATIC_URL?>js/templates/<?=$template_name?>.js?<?=FISHNET_VERSION?>"></script>
	<?php endif; ?>
</body>
</html>
