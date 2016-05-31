<?php
//print_r($_REQUEST); exit();
include_once('../utils/settings.php');

if (!defined("EF_IT_LOAN_FORM")){
	header('Location: '.$settings['base_url']);
}

//GENERAL SECTION---------------------------------------------------------------------------------------
$requestTypes = "";
$lineArr = array();
$lineArr[] = "<h1>IT Department Equipment Loan Request Form</h1><strong>Requested by: </strong>".$_POST["userName"];
$lineArr[] = "<strong>Requestor Email: </strong>".$_POST["reqEmail"];
$lineArr[] = "<strong>Date of request: </strong>".$_POST["reqTimeStamp"];
$lineArr[] = "<strong>Date Needed: </strong>".$_POST["needDate"];
$lineArr[] = "<strong>Will Return On: </strong>".$_POST["returnDate"];
$textDesc = $_POST["textDesc"];
if ( $textDesc != "" )
{
    $lineArr[]= "";
    $lineArr[] = "---------- Additional Notes ----------";
    $lineArr[] = "$textDesc";
}

//EQUIPMENT SECTION---------------------------------------------------------------------------------------
$lineArr[] = "<h2>Equipment Needed</h2><strong>Equipment needed: </strong>";
$hardwareIndex = count($lineArr) - 1;

//LIST OF NEW HARDWARE NEEDED
for ($i = 1; $i <= 6; $i++){
	if (isset($_POST["h$i"]) and ($_POST["h$i"] != "")){
		$lineArr[$hardwareIndex] .= $_POST["h$i"].", ";
	}
}

//OTHER NO LISTED HARDWARE
if (isset($_POST["hOther"])){
	$lineArr[] = "<strong>Other equipment needed: </strong>".$_POST["hOtherStr"];
}

//REASON
$lineArr[] = "<strong>Reason/Description for the selected equipment: </strong>";
$lineArr[] = $_POST["hReason"];

//USAGE SECTION---------------------------------------------------------------------------------------
$lineArr[] = "<h2>Usage Requirements</h2><strong>This equipment will be used at: </strong>";
$hardwareIndex = count($lineArr) - 1;

//LIST OF usage requirements
for ($i = 1; $i <= 3; $i++){
	if (isset($_POST["u$i"]) and ($_POST["u$i"] != "")){
		$lineArr[$hardwareIndex] .= $_POST["u$i"].", ";
	}
}

//OTHER USAGE REQUIREMENTS NOT LISTED
if (isset($_POST["uOther"])){
	$lineArr[] = "<strong>Other usage requirements: </strong>".$_POST["uOtherStr"];
}

//REASON
$lineArr[] = "<strong>Additional description for usage requirements: </strong>";
$lineArr[] = $_POST["uReason"];

//SIGNATURE ---------------------------------------------------------------------------------------

$lineArr[] = "<h2>Manager Signature</h2><strong>Account to be charged: </strong>".$_POST["account"];
$lineArr[] = "<strong>Manager's Name: </strong>".$_POST["mUserName"];
$lineArr[] = "<strong>Manager's User Name: </strong>".$_POST["mUser"];
$lineArr[] = "<strong>Manager's Email: </strong>".$_POST["mUserEmail"];
$lineArr[] = "<strong>Date signed: </strong>".$_POST["signDate"];

//$lineArr[] = $_POST["reqEmail"].", ".$_POST["mUserEmail"]; echo implode("<br><br>", $lineArr); exit(); //DEBUG

//SENDING EMAILS ---------------------------------------------------------------------------------------
$from = "\"EILEEN FISHER IT Department\" <noreply@eileenfisher.com>";
$host = "efexc01.eileenfisher.net";//this is using a local smtp server (IIS or other)
$port = "25";
$body = implode("<br><br>", $lineArr);
ini_set('SMTP', $host);
ini_set('smtp_port', $port);
ini_set('sendmail_from', 'noreply@eileenfisher.com');

//CONFIRMATION EMAIL, THIS WILL BE SENT TO REQUESTER AND THE MANAGER ---------------------------------------------------------------------------------------------------
$to = $_POST["reqEmail"].", ".$_POST["mUserEmail"];
$subject = "IT Equipment Loan Request Form - ".$_POST["reqTimeStamp"];
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: ".$_POST['reqEmail']."\r\n";
$headers .= "Cc: ".$_POST['mUserEmail']."\r\n";
$headers .= "Subject: $subject\r\n";
$headerForUsers = "<p>Hello ".$_POST["userName"].",</p><p>This is a confirmation email to let
	you know that your request was approved by your leader and sent to the Helpdesk for processing.</p>";
$msg = $headerForUsers.$body;

//echo $headers; exit();//DEBUG 
//echo $msg; exit();//DEBUG

if (!mail($to, $subject, $msg, $headers))
	echo "<p>I apologize, I am unable to send confirmation emails at this time.</p><p>Please notify the helpdesk at ext. 4024.</p><p>Thank you</p>";

//THIS WILL BE THE EMAIL FOR THE HELPDESK AND INTERESTED PARTIES -----------------------------------------------------------------------------
$helpDeskEmail = "support@eileenfisher.com";

$subject = "Equipment Loan Request - ".$_POST["userName"];

$to = "$helpDeskEmail";
$cc = array();
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: $helpDeskEmail\r\n";
$headers .= "Subject: $subject\r\n";
//echo "PARAM TO: $to\n\n$headers";

$link = $settings['base_url']."?p=helpdesk&f=".$_POST['requestSerial'];

$msg = "Hello Helpdesk,<br><br>\n\nAn IT Equipment Loan Request Form was filled by: ".$_POST['userName']."<br /><br />";
$msg .= "<a href='$link'>Click here to see it</a><br /><br />";
$msg .= "THIS REQUEST WILL AUTOMATICALLY EXPIRE AFTER 60 DAYS.";

if (!mail($to, $subject, $msg, $headers))
	exit("<p>I apologize, I am unable to send confirmation emails at this time. Because of this error, the
		Helpdesk won't receive your form for processing. This form will remain open for approval. Please try again
		later, and if the problem persist please contact the Helpdesk at extension 4024.\n\nThank you.</p>");

echo("<p>Approval completed, the request was submitted to the Helpdesk for processing.</p>");
echo("<p>Shortly you will receive a confirmation e-mail, if you do not receive it, please call the helpdesk at
			extension 4024. Ref #: " . $_POST['requestSerial'] . "</p>");
echo("<p>You can close this window now.</p>");

//OVERWRITING REQUEST SO WE DONT GET DOUBLE APPROVALS -------------------------------------------------------------
require_once('../utils/result.class.php');
$result->isError = false;
$result->approved = true;
$result->strResult = $lineArr;

$fTmp = fopen('../completed/'.$_POST['requestSerial'], "w+");
fwrite($fTmp, json_encode($result));
fclose($fTmp);
?>
