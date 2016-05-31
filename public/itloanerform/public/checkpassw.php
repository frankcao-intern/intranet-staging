<?php
session_name("EFLoanerForm");
session_start();

//print_r($_POST);

require_once('../utils/result.class.php');

if (!isset($_POST["SID"]) or ($_POST["SID"] != sha1(session_id()))){
	$result->strResult = "ERROR: Invalid Session ID";
	sendResult();
}else{
	if (isset($_POST["user"]) and ($_POST["user"] != "")){
		$user = $_POST["user"];
	}else{
		$result->strResult = "ERROR: Username can't be empty.";
		sendResult();
	}
	
	if (isset($_POST["passw"]) and ($_POST["passw"] != "")){
		$passw = $_POST["passw"];
	}else{
		$result->strResult = "ERROR: Password can't be empty.";
		sendResult();
	}

	if (isset ($user) and isset ($passw)){
		//connect to ldap server
		$ldapconn = ldap_connect("efadc01.eileenfisher.net")
			or $ldapconn = ldap_connect("eileenfisher.net");

		if ($ldapconn){
			ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			//binding to ldap server
			@$ldapbind = ldap_bind($ldapconn, "$user@eileenfisher.net", $passw);
			//echo $ldapbind;
			if ($ldapbind){
				$result->isError = false;
				$result->strResult = $_POST["formID"];
				sendResult();
			}else{
				$result->strResult = "WARNING: Password is incorrect, please try again. 2 more bad tries and your account will be locked.";
				sendResult();
			}

			ldap_unbind($ldapconn);
		}else{
			$result->strResult = "ERROR: Could not connect to the LDAP server";
			sendResult();
		}
	}
}

function sendResult(){
	global $result;

	echo json_encode($result);
	exit();
}

?>
