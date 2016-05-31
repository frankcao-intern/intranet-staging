<?php
/**
 * Created by: cravelo
 * Date: 10/14/11
 * Time: 9:42 AM
 */
?>

<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/lib/html5.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/templates/<?php echo $template_name; ?>.css" />

<?php $this->load->view($template_name); ?>

<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/main.js"></script>
<script type="text/javascript" src="<?php echo STATIC_URL; ?>js/templates/<?php echo $template_name; ?>.js"></script>
