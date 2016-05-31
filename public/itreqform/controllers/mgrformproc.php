<?php
//print_r($_REQUEST); exit();

$htmlHead = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n
			<html xmlns='http://www.w3.org/1999/xhtml'>\n
			<head>\n
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n
			<style media='all' type='text/css'>\n
			body { font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; color: #404040;	background: #FCF8E9; padding:40px 20px 20px 20px; }\n
			fieldset { background: #C7D0BD; padding: 10px; border: 1px solid #fff; border-color: #fff #666661 #666661 #fff; margin-bottom: 36px; }\n
			fieldset.action { background: #C7D0BD; border-color: #e5e5e5 #797c80 #797c80 #e5e5e5; margin-top: -20px; }\n
			legend { background-color: #92897C; color:#FFFFFF; font: 17px/21px Calibri, Arial, Helvetica, sans-serif; padding: 0 10px; margin: -26px 0 0 -11px; font-weight: bold; border: 1px solid #fff; border-color: #e5e5c3 #505014 #505014 #e5e5c3; }\n
			</style>\n
			</head>\n
			<body>\n";

//GENERAL SECTION---------------------------------------------------------------------------------------
$requestTypes = "";
$lineArr = array();
$lineArr[] = "<fieldset><legend>IT Department Requisition Form</legend>
              Requested by: ".$_POST["userName"];
$lineArr[] = "Requestor Email: ".$_POST["reqEmail"];
$lineArr[] = "For User(s): ".implode(", ", json_decode($_POST['forUsers'], true));
$lineArr[] = "Date of request: ".$_POST["reqTimeStamp"];
$lineArr[] = "Date Needed: ".$_POST["needDate"];
$textDesc = $_POST["textDesc"];
if ( $textDesc != "" )
{
    $lineArr[]= "";
    $lineArr[] = "---------- Assessment/Consultation/General Notes ----------";
    $lineArr[] = "$textDesc";
}

//NEW USER SECTION---------------------------------------------------------------------------------------
if (isset($_POST["t1"]))
{
	$requestTypes .= "New User, ";
	$lineArr[] = "</fieldset><fieldset><legend>New User Information</legend><br>".
				"Legal Name: ".$_POST["uName"];
	$lineArr[] = "Title: ".$_POST["uTitle"];
	$lineArr[] = "Type: ".$_POST['uHours']." ".$_POST["uType"];
	$lineArr[] = "Location: ".$_POST["uLocation"];
	$lineArr[] = "Starting Date: ".$_POST["uDate"];
}

//NEW SOFTWARE SECTION---------------------------------------------------------------------------------------
if (isset($_POST["t2"]))
{
	$requestTypes .= "Software, ";
	$lineArr[] = "</fieldset><fieldset><legend>New Software Information</legend><br>
					Software Needed: ";
	$softIndex = count($lineArr) - 1;
	//LIST OF REQUIRED SOFTWARE
	for ($j = 0; $j <= 14; $j++)//13 is the total number of software choices in the form
	{
		if (isset($_POST["s$j"]))
			$lineArr[$softIndex] .= $_POST["s$j"].", ";
	}
	//OTHER SOFTWARE
	if (isset($_POST["sOther"]))
	{
		$lineArr[] = "Other Software: ".$_POST["sOtherStr"];
	}
	//REASON
	$lineArr[] = "Reason/Description for the selected software:";
	$lineArr[] = $_POST["sReason"];
}

//NEW HARDWARE SECTION---------------------------------------------------------------------------------------
if (isset($_POST["t3"]))
{
	$requestTypes .= "Hardware, ";
	$lineArr[] = "</fieldset><fieldset><legend>New Hardware Information</legend><br>";
	if (isset ($_POST["computer"]))
		$lineArr[] .= "Hardware Needed: ".$_POST["computer"].", ";
	$hardwareIndex = count($lineArr) - 1;
	
	//LIST OF NEW HARDWARE NEEDED
	for ($j = 0; $j <= 9; $j++)//9 is the total number of hardware choices in the form
	{
		if (isset($_POST["h$j"]) and ($_POST["h$j"] != ""))
			$lineArr[$hardwareIndex] .= $_POST["h$j"].", ";
	}
	
	//OTHER NO LISTED HARDWARE
	if (isset($_POST["hOther"]))
	{
		$lineArr[] = "Other Hardware: ".$_POST["hOtherStr"];
	}
	
	//REASON
	$lineArr[] = "Reason/Description for the selected hardware:";
	$lineArr[] = $_POST["hReason"];
}

//SHARED DRIVES SECTION---------------------------------------------------------------------------------------
if (isset($_POST["t4"]))
{
	$requestTypes .= "Shared Drives, ";
	$lineArr[] = "</fieldset><fieldset><legend>Shared Drives</legend><br>
					Folders: ".implode(", ", json_decode($_POST["folders"], true));
	$lineArr[] = "Users that require write access: ";
	$lineArr[] = implode(", ", json_decode($_POST["writeUsers"], true));
	$lineArr[] = "Other users that require access (Read only): ";
	$lineArr[] = implode(", ", json_decode($_POST["readUsers"], true));
}

//DISTRO LISTS SECTION---------------------------------------------------------------------------------------
if (isset($_POST["t5"]))
{
	$requestTypes .= "Distribution Lists, ";
	$lineArr[] = "</fieldset><fieldset><legend>Email Distribution Lists</legend><br>";
	$lineArr[] = implode("<br>\n", json_decode($_POST["actions"], true));
}

//WEBEX ACCOUT ----------------------------------------------------------------------------------------------
if (isset($_POST["t6"]))
{
	$requestTypes .= "WebEx Account, ";
	$lineArr[] = "</fieldset><fieldset><legend>WebEx Account</legend><br>";
	$lineArr[] = "Please create a WebEx account for the following user(s): ";
	$webExUsers = json_decode($_POST["webExUsers"], true);
	if (count($webExUsers) != 0)
	{
		$lineArr[] = implode("<br>\n", $webExUsers);
	}
}

$lineArr[] = "</fieldset><fieldset><legend>Manager Signature</legend><br>
			  Account to be charged: ".$_POST["account"];
$lineArr[] = "Manager's Name: ".$_POST["mUserName"];
$lineArr[] = "Manager's User Name: ".$_POST["mUser"];
$lineArr[] = "Manager's Email: ".$_POST["mUserEmail"];
$lineArr[] = "Date signed: ".$_POST["signDate"];
$lineArr[] = "</fieldset>";

$lineArr[] = "</body></html>";

//$lineArr[] = $_POST["reqEmail"].", ".$_POST["mUserEmail"]; print_r($lineArr); exit(); //DEBUG

//SENDING EMAILS ---------------------------------------------------------------------------------------
$from = "\"EILEEN FISHER IT Department\" <noreply@eileenfisher.com>";
$host = "efexc01.eileenfisher.net";//this is using a local smtp server (IIS or other)
$port = "25";
//$host = "ssl://smtp.gmail.com"; //This are the settings to use google's smtp server to send the emails
//$port = "465";
//$username = "";
//$password = "";
$body = implode("<br><br>", $lineArr);
ini_set('SMTP', $host);
ini_set('smtp_port', $port);
ini_set('sendmail_from', 'noreply@eileenfisher.com');

//CONFIRMATION EMAIL, THIS WILL BE SENT TO REQUESTER AND THE MANAGER ---------------------------------------------------------------------------------------------------
$to = $_POST["reqEmail"].", ".$_POST["mUserEmail"];
$subject = "IT Requisition Form - ".$_POST["reqTimeStamp"];
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: ".$_POST['reqEmail']."\r\n";
$headers .= "Cc: ".$_POST['mUserEmail']."\r\n";
$headers .= "Subject: $subject\r\n";
$headerForUsers = "<fieldset class='action'>Hello ".$_POST["userName"].",<br><br>This is a confirmation email to let you know that your request was approved by your leader and sent to the Helpdesk for processing.</fieldset>";
$msg = $htmlHead.$headerForUsers.$body;

//echo $headers; exit();//DEBUG 
//echo $msg; exit();//DEBUG

if (!mail($to, $subject, $msg, $headers))
	echo "<p>I apologize, I am unable to send confirmation emails at this time.</p><p>Please notify the helpdesk at ext. 4024.</p><p>Thank you</p>";

//THIS WILL BE THE EMAIL FOR THE HELPDESK AND INTERESTED PARTIES -----------------------------------------------------------------------------
$helpDeskEmail = "helpdeskteam@eileenfisher.com";
$ccWebPDM = "pdmsupportteam@eileenfisher.com";
$ccBlueCherry = "bcsupport@eileenfisher.com";
$ccPLM = "plmsupportteam@eileenfisher.com";

$subject = "ReqForm - ".$_POST["userName"]." - ".$requestTypes;

$to = "$helpDeskEmail";
$cc = array();
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: $helpDeskEmail\r\n";

if (isset($_POST["s2"]) or isset($_POST["s13"])) //s2 = BlueCherry, s13 = Salomon financials
{
    $cc[] = $ccBlueCherry;
    $to .= ", $ccBlueCherry";
}
if (isset($_POST["s9"])) //s9 = WebPDM
{
    $cc[] = $ccWebPDM;
    $to .= ", $ccWebPDM";
}
if (isset($_POST["s14"])) //s14 = PLM
{
    $cc[] = $ccPLM;
    $to .= ", $ccPLM";
}
if (count($cc) != 0)
{
	$headers .= "Cc: ".implode(", ", $cc)."\r\n";
	
}
$headers .= "Subject: $subject\r\n";

//echo "PARAM TO: $to\n\n$headers";

if (!mail($to, $subject, $htmlHead.$body, $headers))
	exit("<p>I apologize, I am unable to send confirmation emails at this time. Because of this error, the helpdesk won't receive your form for processing. This form will remain open for approval. Please try again later, and if the problem persist please contact the helpdesk at extension 4024.\n\nThank you.</p>");

echo("<p>Approval completed, the request was submitted to the Helpdesk for processing.</p>");
echo("<p>Shortly you will receive a confirmation e-mail, if you do not receive it, please call the helpdesk at extension 4024 and report this issue.</p>");
echo("<p>You can close this window now.</p>");

//OVERWRITING REQUEST SO WE DONT GET DOUBLE APPROVALS -------------------------------------------------------------
require_once('../utils/result.class.php');
$result->isError = true;
$result->strResult = "<b>This request was approved already.</b>\n<p>To check the status of the request or if you had any problems with it, please contact the helpdesk at extension 4024.</p>\n<p>Thank you.</p>";

$fTmp = fopen('../completed/'.$_POST['requestSerial'], "w+");
fwrite($fTmp, json_encode($result));
fclose($fTmp);
?>
