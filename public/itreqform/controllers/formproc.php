<?php
require_once('../utils/result.class.php');
require_once('../utils/settings.php');

date_default_timezone_set("America/New_York");
$reqTimeStamp = date("m/d/Y h:m:s a");

$completedFormsDir = '../completed/';
$fileName = sha1("$reqTimeStamp - ".$_POST['userName']);
$filePath = $completedFormsDir.$fileName;
$link = $settings['base_url']."?f=$fileName";

// this will delete any created forms, approved or not, form the server after 90 days.
$dir = opendir($completedFormsDir);
while (false !== ($file = readdir($dir))){
    if (($file != ".") and ($file != "..") and ($file != "index.html") and (time() - filectime($completedFormsDir.$file) > 7776000)){
        unlink($completedFormsDir.$file);
    }
}
closedir($dir);
//end

//gathering form values
$_POST['reqTimeStamp'] = $reqTimeStamp;
$_POST['requestSerial'] = $fileName;
//print_r($_POST);exit();
$fTmp = fopen($filePath, "w+");
$result->isError = false;
$result->strResult = $_POST;
fwrite($fTmp, json_encode($result));
fclose($fTmp);
//end

//sending the email to the manager
$from = "\"Eileen Fisher IT Department\" <noreply@eileenfisher.com>";
$subject = "IT Requisition Form - $reqTimeStamp";
$host = "efexc01.eileenfisher.net";//this is using a local smtp server (IIS or other)
$port = "25";
//$host = "ssl://smtp.gmail.com"; //This are the settings to use google's smtp server to send the emails
//$port = "465";
//$username = "";
//$password = "";
ini_set('SMTP', $host);
ini_set('smtp_port', $port);
ini_set('sendmail_from', 'noreply@eileenfisher.com');

//--------------------------------------------------- EMAIL FOR USER ---------------------------------------------------------------
$to = $_POST["reqEmail"];//requestor email
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: $to\r\n";
$headers .= "Subject: $subject\r\n";

$body = "Hello ".$_POST['userName'].",<br><br>\n\nThis is a confirmation email to let you know that your form was saved successfully and is awaiting your leader's approval.\n\n<br><br>";
$body .= "THIS REQUEST WILL AUTOMATICALLY EXPIRE AFTER 60 DAYS IF YOUR LEADER DOESN'T APPROVE.<br><br>\n\n";
$body .= "You will receive another email when your leader approves the form to confirm that the helpdesk has received it.";
$body .= "<br><br><br><br>Confirmation #: $fileName";

mail($to, $subject, $body, $headers) or
	die("<p>There was an error sending your confimation email. This is your reference: $reqTimeStamp<p>");

//------------------------------EMAIL FOR MANAGER ---------------------------------------------------------------------
$to = $_POST["mUserEmail"];//send to he manager
$headers  = "Content-Type:  text/html\r\n";
$headers .= "From: $from\r\n";
$headers .= "To: $to\r\n";
$headers .= "Subject: $subject\r\n";

$body = "Hello ".$_POST['mUserName'].",<br><br>\n\nAn IT Requisition Form was filled by: ".$_POST['userName']." and you were listed as his/her leader, or you are the Director or VP of the area. ";
$body .= "Please review this request and make any necessary changes, approve it by typing in your windows password when prompted.<br><br>";
$body .= "THIS REQUEST WILL AUTOMATICALLY EXPIRE AFTER 60 DAYS.<br><br>";
$body .= "<a href='$link'>Click here to proceed</a>";

mail($to, $subject, $body, $headers) or
	die("<p>There was an error sending the email to your leader, you can send it yourself include this link on it</p><p>$link</p>");

echo("<p>An email was successfully sent to ".$_POST['mUserName']." &lt;$to&gt; for his/her approval.</p>");
echo("<p>You will receive a confirmation e-mail now and another one when the request is approved by your leader.</p>");
echo("<p>You can close this window now.</p>");
?>
