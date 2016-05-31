<?php
/**
 * Created by: cravelo
 * Date: 12/6/11
 * Time: 9:47 AM
 */

include_once('../utils/settings.php');
if (!defined("EF_IT_LOAN_FORM")){
	header('Location: '.$settings['base_url']);
}

$page_js = 'helpdesk';

if ($dataArr['approved'] !== true): ?>
	<?php $link = $settings['base_url']."?f=".$dataArr['strResult']['requestSerial']; ?>
	<p>This request hasn't been approved yet. Please click here to approve it: <a href="<?php echo $link?>"></a></p>
<?php else:
	$checked = "checked='checked'";
	include("../views/helpdesk.php");
endif; ?>
