<?php
/**
 * Created by: cravelo
 * Date: 10/14/11
 * Time: 9:42 AM
 */
?>

<!--[if lt IE 9]>
	<script src="<?=STATIC_URL?>js/lib/html5shiv.js"></script>
<![endif]-->
<?php if (file_exists(APPPATH."../public/assets/css/templates/$template_name.css") === TRUE): ?>
	<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?=$template_name?>.css?<?=FISHNET_VERSION?>" />
<?php endif; ?>

<?php if (file_exists(APPPATH."/views/$template_name.php")): ?>
	<?php $this->load->view($template_name); ?>
<?php endif; ?>

<script>
    var coreEngine = coreEngine || {};
	$.extend(coreEngine, {
		csrf_token:     '<?=$this->security->get_csrf_hash()?>'
	});
</script>
<script type="text/javascript" src="<?=STATIC_URL?>js/main.js"></script>
<?php if (file_exists(APPPATH."../public/assets/js/templates/$template_name.js") === TRUE): ?>
	<script src="<?=STATIC_URL?>js/templates/<?=$template_name?>.js?<?=FISHNET_VERSION?>"></script>
<?php endif; ?>
