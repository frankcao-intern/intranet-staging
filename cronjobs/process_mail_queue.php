<?php
/**
 * Created by: cravelo
 * Date: 8/11/11
 * Time: 1:51 PM
 */

set_time_limit(300); //5 minutes
$host = "mail.eileenfisher.com";
$port = "25";
ini_set('SMTP', $host);
ini_set('smtp_port', $port);

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/process_mail_queue-".date("Y-m-d").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Processing queue...\n");

$query = $db->query("
	SELECT * FROM fn_mail_queue LIMIT 0,250
");

$ar = 0;

if ($query){
	$deleteQuery = "DELETE FROM fn_mail_queue WHERE ";
	$fishnet = "\"FishNET\" <no-reply@eileenfisher.com>";

	while($row = $query->fetch()){
		$to = $row["to"];
		$subject = $row["subject"];
		$body = $row["message"];
		$from = (isset($row["from"]) and !empty($row['from'])) ? $row['from'] : $fishnet;

		//headers
		$headers  = "Content-Type:  text/plain\n";
		$headers .= "From: $from\n";
		$headers .= "Subject: $subject\n";

		//send it
		if (mail($to, $subject, $body, $headers) === FALSE){
			fwrite($f, date("Y-m-d H:i:s")." ERROR: Message ".$row["email_id"]." was not sent. Will try again later.\n");
		}else{
			$deleteQuery .= "email_id=".$row["email_id"].' OR ';
		}
	}

	$deleteQuery .= 'email_id=NULL';

	$ar = $db->exec($deleteQuery);
}

fwrite($f, date("Y-m-d H:i:s")." Batch complete, emails sent: $ar\n");

fclose($f);

unset($db);
