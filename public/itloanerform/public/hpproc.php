<?php
/**
 * Created by: cravelo
 * Date: 12/6/11
 * Time: 11:01 AM
 */
 
include_once('../utils/result.class.php');

$a = isset($_POST['a']) ? $_POST['a'] : false;

if (!$a){ $result->isError = true; $result->strResult = 'You need to specify an action to take'; sendResult(); }

$fTmp = fopen('../completed/'.$_POST['f'], "r");
$dataArr = json_decode(fgets($fTmp), true);
fclose($fTmp);

switch ($a){
	case 'savejob':
		$dataArr['helpdesk']['jobNumber'] = $_POST['jobNumber'];
		$result->strResult = 'Job number was saved successfully';
	break;
	case 'usersign':
		$dataArr['helpdesk']['powerSupply'] = ($_POST['powerSupply'] == 'true') ? $_POST['powerSupply'] : null;
		$dataArr['helpdesk']['netCable'] = ($_POST['netCable'] == 'true') ? $_POST['netCable'] : null;
		$dataArr['helpdesk']['USBCable'] = ($_POST['USBCable'] == 'true') ? $_POST['USBCable'] : null;
		$dataArr['helpdesk']['tested'] = ($_POST['tested'] == 'true') ? $_POST['tested'] : null;
		$dataArr['helpdesk']['trained'] = ($_POST['trained'] == 'true') ? $_POST['trained'] : null;
		$dataArr['helpdesk']['userEmail'] = $_POST['userEmail'];
		$dataArr['helpdesk']['userSignDate'] = $_POST['userSignDate'];
		$result->strResult = 'Signed successfully';
	break;
	case 'hpsign':
		$dataArr['helpdesk']['powerSupply1'] = ($_POST['powerSupply'] == 'true') ? $_POST['powerSupply'] : null;
		$dataArr['helpdesk']['netCable1'] = ($_POST['netCable'] == 'true') ? $_POST['netCable'] : null;
		$dataArr['helpdesk']['USBCable1'] = ($_POST['USBCable'] == 'true') ? $_POST['USBCable'] : null;
		$dataArr['helpdesk']['hpEmail'] = $_POST['hpEmail'];
		$dataArr['helpdesk']['hpSignDate'] = $_POST['hpSignDate'];
		$result->strResult = 'Signed successfully';
	break;
}

$fTmp = fopen('../completed/'.$_POST['f'], "w+");
fwrite($fTmp, json_encode($dataArr));
fclose($fTmp);

sendResult();

function sendResult(){
	global $result;
	
	echo json_encode($result);
	exit();
}
